<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include(__DIR__ . "/../connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tar_fc = $_POST['tar-fc'];
    $tar_fb = $_POST['tar-fb'];
    $tar_rc = $_POST['tar-rc'];
    $tar_rb = $_POST['tar-rb'];
    $tar_3rd = $_POST['tar-3rd'];
    $tar_sub = $_POST['tar-sub'];


    // เพิ่มข้อมูล
    $stmt = $conn->prepare("INSERT INTO sewing_target (fc, fb, rc, rb, 3rd, sub)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $tar_fc,
        $tar_fb,
        $tar_rc,
        $tar_rb,
        $tar_3rd,
        $tar_sub
    ]);

    echo "<script>alert('✅ เพิ่มข้อมูลเรียบร้อยแล้ว'); location.href='../index.php';</script>";
}
?>
