<?php
header("Content-Type: application/json");
require 'db.php';

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

$title    = trim($data['title'] ?? '');
$category = trim($data['category'] ?? '');
$price    = intval($data['price'] ?? 0);
$stock    = intval($data['stock'] ?? 0);

if (empty($title) || empty($category) || !$price || !$stock) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "All product fields are required!"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO products (title, category, price, stock) VALUES (:title, :category, :price, :stock)");
    $stmt->execute([
        ':title'    => $title,
        ':category' => $category,
        ':price'    => $price,
        ':stock'    => $stock
    ]);
    echo json_encode(["status" => "success", "message" => "Product added to catalog!"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>