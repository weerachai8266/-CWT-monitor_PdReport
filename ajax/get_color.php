<?php
require_once '../connect.php';

$q = $_GET['term'] ?? ''; 
$stmt = $conn->prepare("SELECT DISTINCT color FROM item WHERE color LIKE ? ORDER BY color ASC LIMIT 10"); // กรองค่า color ที่ไม่ซ้ำกัน
// $stmt = $conn->prepare("SELECT model FROM item WHERE model LIKE ? ORDER BY model ASC LIMIT 10");    // ไม่กรองค่า model ที่ไม่ซ้ำกัน
$stmt->execute(["%$q%"]);
$items = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($items);

?>