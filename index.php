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
            </div> <!-- End of Target -->

            <!-- Report -->
            <div class="tab-pane fade" id="report_data" role="tabpanel">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                    📊 Report
                    </div>
                    <div class="card-body">
                        <p>รายงานข้อมูลการผลิต</p>
                    </div>
                </div> <!-- End of Report Card -->

        </div> <!-- End of Tab Content -->
    
</body>
</html>