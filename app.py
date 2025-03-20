import os
import pandas as pd
import chainlit as cl
from dotenv import load_dotenv
from groq import Groq
import chromadb
from chromadb.config import Settings
from langchain_huggingface import HuggingFaceEmbeddings
from langchain_community.vectorstores import Chroma
from langchain.prompts import PromptTemplate

load_dotenv()

# Initialize Groq and embeddings
client = Groq(api_key=os.getenv('GROQ_API_KEY'))
embeddings = HuggingFaceEmbeddings(model_name="all-MiniLM-L6-v2")

# Initialize ChromaDB
def get_chroma_client():
    persist_directory = "./chroma_db"
    os.makedirs(persist_directory, exist_ok=True)
    return chromadb.PersistentClient(path=persist_directory)

chroma_client = get_chroma_client()

# Load and process the CSV data
def load_data():
    df = pd.read_csv('ipc_sections.csv')
    df['combined_text'] = df.apply(
        lambda row: f"Section {row['Section']}: {row['Offense']} - Punishment: {row['Punishment']}", 
        axis=1
    )
    return df['combined_text'].tolist()

# Store documents in ChromaDB
def store_documents(texts, collection_name="ipc_sections"):
    try:
        # Delete existing collection if it exists
        existing_collections = chroma_client.list_collections()
        if any(col.name == collection_name for col in existing_collections):
            chroma_client.delete_collection(name=collection_name)
        
        # Create vector store
        vector_store = Chroma(
            client=chroma_client,
            collection_name=collection_name,
            embedding_function=embeddings
        )
        
        # Add texts to vector store
        vector_store.add_texts(texts)
        return vector_store
    except Exception as e:
        print(f"Error storing documents: {e}")
        return None

# Query function
async def query_database(question: str, vector_store):
    try:
        # Get relevant documents
        results = vector_store.similarity_search(question, k=3)
        context = " ".join([doc.page_content for doc in results])
        
        # Create Groq chat completion
        response = client.chat.completions.create(
            model="llama-3.3-70b-versatile",
            messages=[
                {
                    "role": "system",
                    "content": """Hi, Hello, your name is Law Assist AI and You are a legal assistant specialized in Indian Penal Code (IPC).
                    Provide accurate information based on the given context. If the information is not 
                    in the context, provide accurate information based on the user question."""
                },
                {
                    "role": "user",
                    "content": f"""Context: {context}
                    
                    Question: {question}
                    
                    Please provide a clear and concise answer based on the IPC sections mentioned in the context."""
                }
            ],
            max_tokens=1024,
            temperature=0.2
        )
        
        return response.choices[0].message.content
        
    except Exception as e:
        return f"An error occurred: {str(e)}"

# Chainlit setup
@cl.on_chat_start
async def start():
    # Load data and initialize vector store
    texts = load_data()
    vector_store = store_documents(texts)
    
    # Store vector store in user session
    cl.user_session.set("vector_store", vector_store)
    
    await cl.Message(
        content="""👋 Hello! I'm your IPC (Indian Penal Code) assistant.
        
I can help you with:
- Finding specific IPC sections
- Understanding offenses and their punishments
- Explaining legal terms and provisions

Please ask your questions about IPC sections!"""
    ).send()

@cl.on_message
async def main(message: cl.Message):
    # Get vector store from session
    vector_store = cl.user_session.get("vector_store")
    
    # Create a message for thinking state
    msg = cl.Message(content="🤔 Searching through IPC sections...")
    await msg.send()
    
    try:
        # Get response
        response = await query_database(message.content, vector_store)
        
        # Create elements for better visualization
        elements = []
        
        # If response mentions specific IPC sections, add them as elements
        if "Section" in response:
            elements.append(
                cl.Text(
                    name="relevant_sections",
                    content=response,
                    display="inline"
                )
            )
        
        # Send new message with response
        await cl.Message(
            content=response,
            elements=elements if elements else None
        ).send()
        
        # Remove the thinking message
        await msg.remove()
        
    except Exception as e:
        await cl.Message(content=f"An error occurred: {str(e)}").send()
        await msg.remove()

if __name__ == "__main__":
   
    cl.start()