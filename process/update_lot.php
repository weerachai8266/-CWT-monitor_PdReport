<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include(__DIR__ . "/../connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $lot_number = $_POST['lot'];
    $model = $_POST['model'];
    $color = $_POST['color'];

    // อัปเดตข้อมูล
    $stmt = $conn->prepare("UPDATE sewing_lot SET
        lot = ?,
        model = ?,
        color = ?
        WHERE id = ?");
        
    $stmt->execute([
        $lot_number,
        $model,
        $color,
        $id
    ]);

    echo "<script>alert('✅ แก้ไขข้อมูลสำเร็จ'); location.href='../index.php';</script>";
}
?>
