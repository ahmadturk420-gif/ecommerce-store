<?php
header("Content-Type: application/json");
require 'db.php';

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

$name    = trim($data['customer_name'] ?? '');
$phone   = trim($data['customer_phone'] ?? '');
$address = trim($data['delivery_address'] ?? '');
$total   = intval($data['total_amount'] ?? 0);
$items   = $data['order_items'] ?? [];

if (empty($name) || empty($phone) || empty($address) || empty($items) || !$total) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Incomplete checkout details!"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_phone, delivery_address, total_amount, order_items, status) 
                           VALUES (:name, :phone, :address, :total, :items, 'Pending')");
    $stmt->execute([
        ':name'    => $name,
        ':phone'   => $phone,
        ':address' => $address,
        ':total'   => $total,
        ':items'   => json_encode($items)
    ]);
    echo json_encode(["status" => "success", "message" => "Order placed successfully!"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>