<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'connect.php';

if (isset($_POST['import']) && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];

    if (($handle = fopen($file, 'r')) !== false) {
        $conn->beginTransaction();

        $header = fgetcsv($handle); // ข้ามหัวตาราง (row แรก)

        $stmt = $conn->prepare("INSERT INTO truck_reg (license_plate, truck_type, truck_color, department, group_type, driver, licensen, phone, truck_number, garbage_type)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $inserted = 0;
        $skipped = 0;

        while (($data = fgetcsv($handle)) !== false) {
            // ตรวจสอบทะเบียนซ้ำ
            $check = $conn->prepare("SELECT id FROM truck_reg WHERE license_plate = ?");
            $check->execute([$data[0]]);

            if ($check->rowCount() > 0) {
                $skipped++;
                continue;
            }

            $stmt->execute($data);
            $inserted++;
        }

        $conn->commit();
        fclose($handle);

        echo "<script>
            alert('✅ นำเข้าเรียบร้อย: เพิ่ม $inserted รายการ / ข้าม $skipped รายการที่ซ้ำ');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>alert('ไม่สามารถเปิดไฟล์ได้'); window.history.back();</script>";
    }
} else {
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Import ทะเบียนรถ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            📥 นำเข้าทะเบียนรถจากไฟล์ CSV
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">เลือกไฟล์ .csv</label>
                    <input type="file" name="csv_file" accept=".csv" class="form-control" required>
                </div>
                <button type="submit" name="import" class="btn btn-success">นำเข้า</button>
            </form>
            <hr>
            <p class="text-muted">
                ✅ ไฟล์ควรมีหัวตารางแบบนี้:<br>
                <code>license_plate,truck_type,truck_color,department,truck_number,garbage_type</code><br>
                <!-- ❌ ไม่ต้องมีคอลัมน์ <code>id</code> หรือ <code>created_at</code> -->
            </p>
        </div>
    </div>
</div>
</body>
</html>

<?php } ?>
