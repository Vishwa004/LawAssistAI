<?php
include 'db.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$sql = "SELECT article, title, description FROM laws";
$params = [];
$types = "";

if (isset($data['article']) && !empty($data['article'])) {
    $search_query = trim($data['article']);
    
    // Remove "Article " prefix from search input
    $search_query = preg_replace('/\bArticle\s*/i', '', $search_query);
    
    // Allow partial matching
    $search_query = "%" . $search_query . "%";
    
    $sql .= " WHERE article LIKE ?";
    $params[] = $search_query;
    $types .= "s";
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$articles = [];
while ($row = $result->fetch_assoc()) {
    $articles[] = [
        "article" => "Article " . $row['article'], // Always return "Article X"
        "title" => $row['title'],
        "description" => $row['description']
    ];
}

echo json_encode($articles);

$stmt->close();
$conn->close();
?>