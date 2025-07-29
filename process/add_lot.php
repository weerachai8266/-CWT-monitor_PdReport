<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include(__DIR__ . "/../connect.php");

$lot_numbers = $_POST['lot_number'] ?? [];
$lot_models = $_POST['lot_model'] ?? [];
$lot_colors = $_POST['lot_color'] ?? [];

foreach ($lot_numbers as $i => $lot) {
    $model = $lot_models[$i] ?? '';
    $color = $lot_colors[$i] ?? '';
    if ($lot && $model && $color) {
        $stmt = $conn->prepare("INSERT INTO sewing_lot (lot, model, color) VALUES (?, ?, ?)");
        $stmt->execute([$lot, $model, $color]);
    }
}

    echo "<script>alert('✅ เพิ่มข้อมูลเรียบร้อยแล้ว'); location.href='../index.php';</script>";

?>