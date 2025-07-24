<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CWT Production</title>
    <!-- <link rel="shortcut icon" href="https://cdn.dinoq.com/datafilerepo/greenpower/greenpowerlogo.ico" type="image/x-icon"> -->
    <link rel="icon" href="img/favicon_circular.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom font for Inter (preferred for modern UI) */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        /* Fallback to Poppins if Inter is not desired, but Inter is generally cleaner for UI */
        /* @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap'); */

        body {
            font-family: 'Inter', sans-serif; /* Using Inter as the primary font */
            background-color: #f3f4f6; /* Light gray background */
        }
        /* Custom scrollbar for table-responsive */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #e0e0e0;
            border-radius: 10px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }
        .line-card {
            border-left: 4px solid #007bff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .line-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .line-card.fc { border-left-color: #28a745; }
        .line-card.fb { border-left-color: #ffc107; }
        .line-card.rc { border-left-color: #dc3545; }
        .line-card.rb { border-left-color: #6f42c1; }
        .line-card.third { border-left-color: #fd7e14; }
        .line-card.sub { border-left-color: #20c997; }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        .card-header {
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-weight: 400;
        }

        .input-group-text {
            border-radius: 8px 0 0 8px;
            background-color: #f9fafb;
            border-color: #d1d5db;
            font-weight: 500;
        }
    </style>
    <!-- SweetAlert2 for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery (kept for existing JS logic) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body class="bg-light">
    <div class="container mt-4">

        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
            <img src="img/logo-chaiwattana.png" alt="cwt" style="height: 60px;">
            <h1 class="mb-0 mx-auto" style="color:rgb(0, 0, 0); font-family: 'Poppins', sans-serif; font-weight: 600;">
                <!-- Chai Watana Tannery Group -->
            </h1>
            <div style="width: 60px;"></div>
        <!-- </div> -->

        <!-- แถบเมนู -->
        <ul class="nav nav-pills" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="man-tab" data-bs-toggle="tab" data-bs-target="#man" type="button" role="tab">Man Power</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="target-tab" data-bs-toggle="tab" data-bs-target="#target" type="button" role="tab">Target</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="report-tab" data-bs-toggle="tab" data-bs-target="#report_data" type="button" role="tab">Report</button>
            </li>
        </ul>
        </div>

        <div class="tab-content mt-3" id="myTabContent">
            
            <!-- man power -->
            <div class="tab-pane fade show active" id="man" role="tabpanel">
                <!-- man act -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white">
                    🧑‍🤝‍🧑 Man Power ACT
                    </div>
                    <div class="card-body">

                        <form method="post" action="process/add_aman.php">
                            <?php 
                            $stmt = $conn->query("SELECT * FROM sewing_aman ORDER BY id DESC LIMIT 1");
                            foreach ($stmt as $row): 
                            ?>
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label">ช่วงเวลา</label>
                                    <select name="man-shift" class="form-select" required>
                                        <option value="" disabled selected>เลือก</option>
                                        <option value="เช้า">เช้า</option>
                                        <option value="บ่าย">บ่าย</option>
                                        <option value="OT">OT</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">ชั่วโมงทำงาน</label>
                                    <input type="text" name="man-hour" class="form-control" required>
                                </div>
                            </div>
                            <br>
                            <hr>

                            <div class="row g-3 mt-1">
                                <!-- <div class="col-md-2">
                                    <label class="form-label">Shift</label>
                                    <select name="man-shift" class="form-select" required>
                                        <option value="<?= $row['shift'] ?>" disabled selected><?= $row['shift'] ?></option>
                                        <option value="Day">Day</option>
                                        <option value="Night">Night</option>
                                    </select>
                                </div> -->
                                
                                <div class="col-md-2">
                                    <label class="form-label">F/C</label>
                                    <input type="number" name="man-fc" class="form-control" value="<?= $row['fc_act'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">F/B</label>
                                    <input type="number" name="man-fb" class="form-control" value="<?= $row['fb_act'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">R/C</label>
                                    <input type="number" name="man-rc" class="form-control" value="<?= $row['rc_act'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">R/B</label>
                                    <input type="number" name="man-rb" class="form-control" value="<?= $row['rb_act'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">3RD</label>
                                    <input type="number" name="man-3rd" class="form-control" value="<?= $row['3rd_act'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">SUB</label>
                                    <input type="number" name="man-sub" class="form-control" value="<?= $row['subass_act'] ?>" required>
                                </div>

                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-success mt-3">💾 บันทึกข้อมูล</button>
                                    <!-- <a href="import_truck.php" class="btn btn-outline-primary mt-3 ms-2">📥 นำเข้าจากไฟล์ CSV</a> -->
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </form>
                        <!-- <br> -->
                         <hr class="my-3 border-gray-200">
                        
                        <div class="overflow-x-auto rounded-lg shadow-md">
                            <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-blue-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-2 text-center text-sm font-bold text-gray-700 uppercase tracking-wider rounded-tl-lg">DATE</th>
                                        <!-- <th scope="col" class="px-6 py-2 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">Shift</th> -->
                                        <th scope="col" class="px-6 py-2 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">ช่วงเวลา</th>
                                        <th scope="col" class="px-6 py-2 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">Hour</th>
                                        <th scope="col" class="px-6 py-2 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">F/C</th>
                                        <th scope="col" class="px-6 py-2 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">F/B</th>
                                        <th scope="col" class="px-6 py-2 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">R/C</th>
                                        <th scope="col" class="px-6 py-2 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">R/B</th>
                                        <th scope="col" class="px-6 py-2 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">3RD</th>
                                        <th scope="col" class="px-6 py-2 text-center text-sm font-bold text-gray-700 uppercase tracking-wider">SUB</th>
                                        <th scope="col" class="px-6 py-2 text-center text-sm font-bold text-gray-700 uppercase tracking-wider rounded-tr-lg">EDIT</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">
                                <?php 
                                    $stmt = $conn->query("SELECT * FROM sewing_aman where date(created_at) = CURDATE() ORDER BY id DESC"); // แสดงเฉพาะข้อมูลที่มีวันที่ตรงกับวันนี้
                                    // $total_license = $stmt->rowCount();
                                    // $i = $total_license;                        
                                    foreach ($stmt as $row): 
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900 text-center"><?= htmlspecialchars($row['created_at']) ?></td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900 text-center"><?= htmlspecialchars($row['shift']) ?></td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900 text-center"><?= htmlspecialchars($row['thour']) ?></td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900 text-center"><?= htmlspecialchars($row['fc_act']) ?></td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900 text-center"><?= htmlspecialchars($row['fb_act']) ?></td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900 text-center"><?= htmlspecialchars($row['rc_act']) ?></td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900 text-center"><?= htmlspecialchars($row['rb_act']) ?></td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900 text-center"><?= htmlspecialchars($row['3rd_act']) ?></td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-900 text-center"><?= htmlspecialchars($row['subass_act']) ?></td>
                                    <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">
                                        <!-- ปุ่มแก้ไข -->
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">✏️</button>
                                        <!-- ปุ่มลบ -->
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['id'] ?>">🗑️</button>
                                    </td>
                                </tr>
                                <!-- Modal แก้ไข -->
                                <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post" action="process/update_aman.php">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title">📝 Edit man power ACT</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3 mt-1">
                                                        <div class="col">
                                                            <label class="form-label">shift</label>
                                                            <select name="shift" class="form-select mb-2" required>
                                                                <option value="<?= $row['shift'] ?>" disabled selected><?= $row['shift'] ?></option>
                                                                <option value="เช้า">เช้า</option>
                                                                <option value="บ่าย">บ่าย</option>
                                                                <option value="OT">OT</option>
                                                            </select>
                                                        </div>
                                                        <div class="col">
                                                            <label class="form-label">Hour</label>
                                                            <input name="hour" class="form-control mb-2" value="<?= $row['thour'] ?>" required>

                                                        </div>
                                                    </div>
                                                    <div class="row g-3 mt-1">
                                                        <div class="col">
                                                            <label class="form-label">F/C</label>
                                                            <input name="fc_act" class="form-control mb-2" value="<?= $row['fc_act'] ?>" required>
                                                        </div>
                                                        <div class="col">
                                                            <label class="form-label">F/B</label>
                                                            <input name="fb_act" class="form-control mb-2" value="<?= $row['fb_act'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 mt-1">
                                                        <div class="col">
                                                            <label class="form-label">R/C</label>
                                                            <input name="rc_act" class="form-control mb-2" value="<?= $row['rc_act'] ?>" required>
                                                        </div>
                                                        <div class="col">
                                                            <label class="form-label">R/B</label>
                                                            <input name="rb_act" class="form-control mb-2" value="<?= $row['rb_act'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3 mt-1">
                                                        <div class="col">
                                                            <label class="form-label">3RD</label>
                                                            <input name="3rd_act" class="form-control mb-2" value="<?= $row['3rd_act'] ?>" required>
                                                        </div>
                                                        <div class="col">
                                                            <label class="form-label">SUB</label>
                                                            <input name="sub_act" class="form-control mb-2" value="<?= $row['subass_act'] ?>" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">บันทึก</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal ลบ -->
                                <div class="modal fade" id="deleteModal<?= $row['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post" action="process/delete_man.php">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">ยืนยันการลบ</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                <div class="modal-body">
                                                คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลคนวันที่  <strong><?= $row['created_at'] ?></strong> ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-danger">ลบข้อมูล</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div> <!-- End of Table -->                      
                    </div>
                </div> <!-- End of MAN ACT -->
                
            </div> <!-- End of MAN POWER -->

            <!-- Target -->
            <div class="tab-pane fade" id="target" role="tabpanel">
                <!-- Target Card -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white">
                    🎯 Target (Pcs / Hr)
                    </div>
                    <div class="card-body">

                        <form method="post" action="process/add_target.php">
                            <?php 
                            $stmt = $conn->query("SELECT * FROM sewing_target ORDER BY id DESC LIMIT 1");
                            foreach ($stmt as $row): ?>

                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label">F/C</label>
                                    <input type="number" name="tar-fc" class="form-control mb-2" value="<?= $row['fc'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">F/B</label>
                                    <input type="number" name="tar-fb" class="form-control" value="<?= $row['fb'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">R/C</label>
                                    <input type="number" name="tar-rc" class="form-control" value="<?= $row['rc'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">R/B</label>
                                    <input type="number" name="tar-rb" class="form-control" value="<?= $row['rb'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">3RD</label>
                                    <input type="number" name="tar-3rd" class="form-control" value="<?= $row['3rd'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">SUB</label>
                                    <input type="number" name="tar-sub" class="form-control" value="<?= $row['subass'] ?>" required>
                                </div>

                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-success mt-3">💾 บันทึกข้อมูล</button>
                                </div>                                
                            </div>
                            <?php endforeach; ?>
                        </form>      
                    </div>
                </div> <!-- End of Target Card -->

                <!-- productivity plan -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white">
                    🎯 Productivity Plan
                    </div>
                    <div class="card-body">

                        <form method="post" action="process/add_product_p.php">
                            <?php 
                            $stmt = $conn->query("SELECT * FROM sewing_productivity_plan ORDER BY id DESC LIMIT 1");
                            foreach ($stmt as $row): ?>

                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label">F/C</label>
                                    <input type="text" name="product-fc" class="form-control mb-2" value="<?= $row['fc'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">F/B</label>
                                    <input type="text" name="product-fb" class="form-control" value="<?= $row['fb'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">R/C</label>
                                    <input type="text" name="product-rc" class="form-control" value="<?= $row['rc'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">R/B</label>
                                    <input type="text" name="product-rb" class="form-control" value="<?= $row['rb'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">3RD</label>
                                    <input type="text" name="product-3rd" class="form-control" value="<?= $row['3rd'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">SUB</label>
                                    <input type="text" name="product-sub" class="form-control" value="<?= $row['subass'] ?>" required>
                                </div>

                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-success mt-3">💾 บันทึกข้อมูล</button>
                                </div>                                
                            </div>
                            <?php endforeach; ?>
                        </form>      
                    </div>
                </div> <!-- End of Productivity Plan -->

                <!-- MAN PLAN -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white">
                    🧑‍🤝‍🧑 Man Power PLAN
                    </div>
                    <div class="card-body">

                        <form method="post" action="process/add_pman.php">
                            <?php 
                            $stmt = $conn->query("SELECT * FROM sewing_pman ORDER BY id DESC LIMIT 1");
                            foreach ($stmt as $row): 
                            ?>

                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label">F/C</label>
                                    <input type="number" name="pman-fc" class="form-control mb-2" value="<?= $row['fc_plan'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">F/B</label>
                                    <input type="number" name="pman-fb" class="form-control" value="<?= $row['fb_plan'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">R/C</label>
                                    <input type="number" name="pman-rc" class="form-control" value="<?= $row['rc_plan'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">R/B</label>
                                    <input type="number" name="pman-rb" class="form-control" value="<?= $row['rb_plan'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">3RD</label>
                                    <input type="number" name="pman-3rd" class="form-control" value="<?= $row['3rd_plan'] ?>" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">SUB</label>
                                    <input type="number" name="pman-sub" class="form-control" value="<?= $row['subass_plan'] ?>" required>
                                </div>

                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-success mt-3">💾 บันทึกข้อมูล</button>
                                </div>                                
                            </div>
                            <?php endforeach; ?>
                        </form>      
                    </div>
                </div> <!-- End of MAN PLAN -->

            </div> <!-- End of Target -->

            <!-- Report -->
            <div class="tab-pane fade" id="report_data" role="tabpanel">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        📊 Report
                    </div>
                    <div class="card-body">
                        <!-- Report Filter -->
                        <div class="row" id="report-filter-form">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <label class="input-group-text">วันที่</label>
                                    <input class="form-control" type="date" id="report_date_start" value="<?= date('Y-m-d') ?>">
                                    <input class="form-control" type="date" id="report_date_end" value="<?= date('Y-m-d') ?>">
                                </div>    
                            </div>  
                            <div class="col-md-3">
                                <label class="form-label"></label>
                            </div>

                            <div class="col-md-5 d-flex justify-content-end gap-3 align-items-start">
                                <button id="btnFilter" class="btn btn-primary">ตกลง</button>
                                <a id="btnExport" href="#" class="btn btn-success">Excel</a>
                            </div>
                        </div>  <!-- End of Report Filter -->

                        <hr class="my-4">

                        <!-- Charts Section -->
                        <div class="row">
                            <!-- Line F/C -->
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="card line-card fc">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">📈 Line F/C</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartFC"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Line F/B -->
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="card line-card fb">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">📈 Line F/B</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartFB"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Line R/C -->
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="card line-card rc">
                                    <div class="card-header bg-danger text-white">
                                        <h6 class="mb-0">📈 Line R/C</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartRC"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Line R/B -->
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="card line-card rb">
                                    <div class="card-header" style="background-color: #6f42c1; color: white;">
                                        <h6 class="mb-0">📈 Line R/B</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartRB"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Line 3RD -->
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="card line-card third">
                                    <div class="card-header" style="background-color: #fd7e14; color: white;">
                                        <h6 class="mb-0">📈 Line 3RD</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chart3RD"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Line Sub -->
                            <div class="col-lg-6 col-md-12 mb-4">
                                <div class="card line-card sub">
                                    <div class="card-header" style="background-color: #20c997; color: white;">
                                        <h6 class="mb-0">📈 Line Sub Assy</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="chartSub"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of Charts Row -->

                        <!-- Summary Statistics -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">📋 สรุปผลรวม</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-2">
                                                <div class="border rounded p-3">
                                                    <h5 class="text-success" id="totalFC">0</h5>
                                                    <small>F/C ชิ้น</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="border rounded p-3">
                                                    <h5 class="text-warning" id="totalFB">0</h5>
                                                    <small>F/B ชิ้น</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="border rounded p-3">
                                                    <h5 class="text-danger" id="totalRC">0</h5>
                                                    <small>R/C ชิ้น</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="border rounded p-3">
                                                    <h5 style="color: #6f42c1;" id="totalRB">0</h5>
                                                    <small>R/B ชิ้น</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="border rounded p-3">
                                                    <h5 style="color: #fd7e14;" id="total3RD">0</h5>
                                                    <small>3RD ชิ้น</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="border rounded p-3">
                                                    <h5 style="color: #20c997;" id="totalSub">0</h5>
                                                    <small>Sub ชิ้น</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  <!-- End of Summary Statistics -->

                       
                    </div>
                </div> <!-- End of Report Card -->
            </div> <!-- End of Report -->

        </div> <!-- End of Tab Content -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let charts = {};
        let currentData = {};
        let updateInterval;

        // API Configuration
        const API_BASE = 'api/get_report_data.php';
        
        // Chart colors configuration
        const CHART_COLORS = {
            fc: '#28a745',
            fb: '#ffc107', 
            rc: '#dc3545',
            rb: '#6f42c1',
            third: '#fd7e14',
            sub: '#20c997'
        };

        // Line names in Thai
        const LINE_NAMES = {
            fc: 'F/C ชิ้น',
            fb: 'F/B ชิ้น',
            rc: 'R/C ชิ้น', 
            rb: 'R/B ชิ้น',
            third: '3RD ชิ้น',
            sub: 'Sub ชิ้น'
        };

        // Set default dates to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('report_date_start').value = today;
        document.getElementById('report_date_end').value = today;

        // API Functions
        async function fetchReportData(type = 'hourly') {
            const startDate = document.getElementById('report_date_start').value;
            const endDate = document.getElementById('report_date_end').value;
            
            try {
                showLoading(true);
                hideError();
                
                const response = await fetch(`${API_BASE}?type=${type}&start_date=${startDate}&end_date=${endDate}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (!result.success) {
                    throw new Error(result.message || 'Unknown error occurred');
                }
                
                return result.data;
                
            } catch (error) {
                console.error('API Error:', error);
                showError(error.message);
                throw error;
            } finally {
                showLoading(false);
            }
        }

        // UI Helper Functions
        function showLoading(show = true) {
            const loadingState = document.getElementById('loadingState');
            const spinner = document.getElementById('loadingSpinner');
            
            if (show) {
                loadingState.classList.remove('d-none');
                spinner.classList.remove('d-none');
            } else {
                loadingState.classList.add('d-none');
                spinner.classList.add('d-none');
            }
        }

        function showError(message) {
            const errorState = document.getElementById('errorState');
            const errorMessage = document.getElementById('errorMessage');
            
            errorMessage.textContent = message;
            errorState.classList.remove('d-none');
        }

        function hideError() {
            const errorState = document.getElementById('errorState');
            errorState.classList.add('d-none');
        }

        // Chart configuration
        const chartConfig = {
            type: 'bar',
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10
                        }
                    }
                }
            }
        };

        // Create charts
        function createChart(canvasId, data, color, label) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            charts[canvasId] = new Chart(ctx, {
                ...chartConfig,
                data: {
                    labels: currentData.labels || [],
                    datasets: [{
                        label: label,
                        data: data || [],
                        backgroundColor: color + '80',
                        borderColor: color,
                        borderWidth: 2,
                        borderRadius: 4
                    }]
                }
            });
        }

        // Initialize all charts
        function initializeCharts() {
            Object.keys(CHART_COLORS).forEach(line => {
                const canvasId = line === 'third' ? 'chart3RD' : `chart${line.toUpperCase()}`;
                createChart(canvasId, [], CHART_COLORS[line], LINE_NAMES[line]);
            });
        }

        // Update charts with new data
        function updateCharts(data) {
            currentData = data;
            
            Object.keys(CHART_COLORS).forEach(line => {
                const canvasId = line === 'third' ? 'chart3RD' : `chart${line.toUpperCase()}`;
                const chart = charts[canvasId];
                
                if (chart && data[line]) {
                    chart.data.labels = data.labels || [];
                    chart.data.datasets[0].data = data[line] || [];
                    chart.update('none'); // Animation disabled for better performance
                }
            });
        }

        // Update summary totals
        async function updateSummary() {
            try {
                const summaryData = await fetchReportData('summary');
                
                Object.keys(summaryData).forEach(line => {
                    const elementId = line === 'third' ? 'total3RD' : `total${line.toUpperCase()}`;
                    const element = document.getElementById(elementId);
                    
                    if (element && summaryData[line]) {
                        element.textContent = summaryData[line].total_qty || 0;
                        
                        // Add animation effect
                        element.style.transform = 'scale(1.1)';
                        setTimeout(() => {
                            element.style.transform = 'scale(1)';
                        }, 200);
                    }
                });
                
            } catch (error) {
                console.error('Error updating summary:', error);
            }
        }

        // Load and display report data
        async function loadReportData() {
            try {
                // Load hourly data for charts
                const hourlyData = await fetchReportData('hourly');
                updateCharts(hourlyData);
                
                // Load summary data
                await updateSummary();
                
                console.log('Report data loaded successfully');
                
            } catch (error) {
                console.error('Error loading report data:', error);
            }
        }

        // Real-time update function
        function startRealTimeUpdate() {
            const checkbox = document.getElementById('realTimeUpdate');
            
            if (checkbox.checked) {
                updateInterval = setInterval(async () => {
                    await loadReportData();
                    console.log('Real-time update completed');
                }, 30000); // Update every 30 seconds
            } else {
                if (updateInterval) {
                    clearInterval(updateInterval);
                }
            }
        }

        // Event Listeners
        document.getElementById('btnFilter').addEventListener('click', async function() {
            await loadReportData();
        });

        document.getElementById('btnExport').addEventListener('click', function(e) {
            e.preventDefault();
            
            const startDate = document.getElementById('report_date_start').value;
            const endDate = document.getElementById('report_date_end').value;
            
            // Open export URL in new window
            const exportUrl = `api/export_excel.php?start_date=${startDate}&end_date=${endDate}`;
            window.open(exportUrl, '_blank');
        });

        document.getElementById('realTimeUpdate').addEventListener('change', function() {
            startRealTimeUpdate();
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', async function() {
            // Initialize empty charts first
            initializeCharts();
            
            // Load initial data
            await loadReportData();
            
            // Start real-time updates if enabled
            startRealTimeUpdate();
            
            console.log('Sewing report system initialized');
        });
    </script>
    
</body>
</html>