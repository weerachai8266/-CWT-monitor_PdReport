<?php
include 'connect.php';

if(isset($_GET['filter_truck_date1'])) {
    $date_t1 = $_GET['filter_truck_date1'];
} else {
    $date_t1 = date("Y-m-d");
}
if(isset($_GET['filter_truck_date2'])) {
    $date_t2 = $_GET['filter_truck_date2'];
} else {
    $date_t2 = date("Y-m-d");
}

// ตรวจสอบรูปแบบของวันที่
$date_t1 = mysqli_real_escape_string($conn, $date_t1);
$date_t2 = mysqli_real_escape_string($conn, $date_t2);

// ตั้งค่า Header สำหรับไฟล์ CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=queue_export.csv');

// สร้างตัวแปรสำหรับเขียนข้อมูล
$output = fopen('php://output', 'w');

// เขียนหัวตารางลงไฟล์ CSV
fputcsv($output, ['ทะเบียน', 'ประเภทรถ', 'บริษัท', 'สินค้า', 'ผู้รับเหมา','REMARK1', 'วันที่เข้าชั่ง', 'เวลาเข้าชั่ง', 'น้ำหนักเข้าชั่ง (kg)', 'วันที่ชั่งออก', 'เวลาชั่งออก', 'น้ำหนักชั่งออก (kg)', 'น้ำหนักสุทธิ (kg)']);

// ดึงข้อมูลจากฐานข้อมูล
// $stmt_truck = $conn->prepare("SELECT * FROM truck_data WHERE DATE(DAYIN) BETWEEN ? AND ? ORDER BY id ASC");
// $stmt_truck->bind_param("ss", $date_t1, $date_t2);
// $stmt_truck->execute();
// $result_truck = $stmt_truck->get_result();

$stmt_truck = $conn->prepare("
    SELECT 
        t.*, 
        ctype.name AS cartype_name,
        comp.name AS company_name,
        prod.name AS product_name,
        sub.name AS subcon_name
    FROM truck_data t
    LEFT JOIN truck_cartype ctype ON t.CARTYPE = ctype.id
    LEFT JOIN truck_company comp ON t.COMPANY = comp.id
    LEFT JOIN truck_product prod ON t.PRODUCT = prod.id
    LEFT JOIN truck_subcon sub ON t.SUBCON = sub.id
    WHERE DATE(t.DAYIN) BETWEEN ? AND ?
    ORDER BY t.id ASC
");
$stmt_truck->bind_param("ss", $date_t1, $date_t2);
$stmt_truck->execute();
$result_truck = $stmt_truck->get_result();

while ($row = $result_truck->fetch_assoc()) {
    // $safe_date = '"' . $row['create_date'] . '"';

    // เขียนข้อมูลแต่ละแถวลงใน CSV
    fputcsv($output, [
        $row['TRUCK'],
        $row['cartype_name'],
        $row['company_name'],
        $row['product_name'],
        $row['subcon_name'],
        $row['REMARK1'],
        $row['DAYIN'],
        $row['TMIN'],
        $row['W1'],
        $row['DAYOUT'],
        $row['TMOUT'],
        $row['W2'],
        $row['W1'] - $row['W2'],
    ]);
}

// ปิดไฟล์ CSV
fclose($output);
exit();
?>
