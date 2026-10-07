<?php
header("Content-Type: application/json");
require 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    $items = $stmt->fetchAll();
    echo json_encode(["status" => "success", "data" => $items]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>