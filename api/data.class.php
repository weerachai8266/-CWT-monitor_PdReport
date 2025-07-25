<?php
class get_db {
    private $conn;
    private $tables = [
        'fc' => 'sewing_fc',
        'fb' => 'sewing_fb', 
        'rc' => 'sewing_rc',
        'rb' => 'sewing_rb',
        'third' => 'sewing_3rd',
        'sub' => 'sewing_subass'
    ];

    public function __construct($db) {
        $this->conn = $db;
    }

    // ดึงช่วงเวลาการทำงานจริงจากข้อมูล
    private function getWorkingHours($start_date, $end_date) {
        $all_hours = [];
        
        foreach ($this->tables as $line => $table_name) {
            // ตรวจสอบว่าตารางมีอยู่จริง
            $check_table = "SHOW TABLES LIKE :table_name";
            $stmt = $this->conn->prepare($check_table);
            $stmt->bindParam(':table_name', $table_name);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                error_log("Table $table_name does not exist");
                continue;
            }

            $query = "SELECT DISTINCT HOUR(created_at) as hour 
                      FROM " . $table_name . " 
                      WHERE DATE(created_at) BETWEEN :start_date AND :end_date ORDER BY hour";
            
            try {
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':start_date', $start_date);
                $stmt->bindParam(':end_date', $end_date);
                $stmt->execute();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $all_hours[] = (int)$row['hour'];
                }
            } catch (PDOException $e) {
                error_log("Error querying table $table_name: " . $e->getMessage());
            }
        }
        
        // ลบ duplicate และ sort
        $all_hours = array_unique($all_hours);
        sort($all_hours);
        
        // ถ้าไม่มีข้อมูล ให้ใช้เวลามาตรฐาน
        if (empty($all_hours)) {
            $all_hours = range(8, 16); // 8:00-16:00 เป็นค่าเริ่มต้น
        }
        
        return $all_hours;
    }

    // ดึงข้อมูลรายชั่วโมงแบบ Dynamic
    public function getHourlyReport($start_date, $end_date) {
        $result = [];
        $working_hours = $this->getWorkingHours($start_date, $end_date);
        
        // สร้าง labels สำหรับแกน x
        $labels = [];
        foreach ($working_hours as $hour) {
            $labels[] = sprintf('%02d:00', $hour);
        }
        $result['labels'] = $labels;
        
        foreach ($this->tables as $line => $table_name) {
            // ตรวจสอบว่าตารางมีอยู่จริง
            $check_table = "SHOW TABLES LIKE :table_name";
            $stmt = $this->conn->prepare($check_table);
            $stmt->bindParam(':table_name', $table_name);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                error_log("Table $table_name does not exist");
                $result[$line] = array_fill(0, count($working_hours), 0);
                continue;
            }

            $query = "SELECT 
                        HOUR(created_at) as hour,
                        SUM(qty) as total_qty,
                        COUNT(*) as total_items
                      FROM " . $table_name . " 
                      WHERE DATE(created_at) BETWEEN :start_date AND :end_date GROUP BY HOUR(created_at) ORDER BY hour";
            
            try {
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':start_date', $start_date);
                $stmt->bindParam(':end_date', $end_date);
                $stmt->execute();
                
                $hourly_data = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $hourly_data[$row['hour']] = (int)$row['total_qty'];
                }
                
                // จัดเรียงข้อมูลตามช่วงเวลาที่หา
                $line_data = [];
                foreach ($working_hours as $hour) {
                    $line_data[] = isset($hourly_data[$hour]) ? $hourly_data[$hour] : 0;
                }
                
                $result[$line] = $line_data;
                
            } catch (PDOException $e) {
                error_log("Error querying $table_name: " . $e->getMessage());
                $result[$line] = array_fill(0, count($working_hours), 0);
            }
        }
        
        return $result;
    }

    // ดึงข้อมูลรายวันตามช่วงวันที่
    public function getDailyReport($start_date, $end_date) {
        $result = [];
        $labels = [];
        
        // สร้างช่วงวันที่
        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            new DateTime($end_date . ' +1 day')
        );
        
        foreach ($period as $date) {
            $labels[] = $date->format('d/m');
        }
        $result['labels'] = $labels;
        
        foreach ($this->tables as $line => $table_name) {
            // ตรวจสอบว่าตารางมีอยู่จริง
            $check_table = "SHOW TABLES LIKE :table_name";
            $stmt = $this->conn->prepare($check_table);
            $stmt->bindParam(':table_name', $table_name);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                $result[$line] = array_fill(0, count($labels), 0);
                continue;
            }

            $query = "SELECT 
                        DATE(created_at) as date,
                        SUM(qty) as total_qty,
                        COUNT(*) as total_items
                      FROM " . $table_name . " 
                      WHERE DATE(created_at) BETWEEN :start_date AND :end_date GROUP BY DATE(created_at) ORDER BY date";
            
            try {
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':start_date', $start_date);
                $stmt->bindParam(':end_date', $end_date);
                $stmt->execute();
                
                $daily_data = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $daily_data[$row['date']] = (int)$row['total_qty'];
                }
                
                // จัดเรียงข้อมูลตามวันที่
                $line_data = [];
                foreach ($period as $date) {
                    $date_str = $date->format('Y-m-d');
                    $line_data[] = isset($daily_data[$date_str]) ? $daily_data[$date_str] : 0;
                }
                
                $result[$line] = $line_data;
                
            } catch (PDOException $e) {
                error_log("Error querying $table_name: " . $e->getMessage());
                $result[$line] = array_fill(0, count($labels), 0);
            }
        }
        
        return $result;
    }

    // ดึงข้อมูลสรุปรวม
    public function getSummaryReport($start_date, $end_date) {
        $result = [];
        
        foreach ($this->tables as $line => $table_name) {
            // ตรวจสอบว่าตารางมีอยู่จริง
            $check_table = "SHOW TABLES LIKE :table_name";
            $stmt = $this->conn->prepare($check_table);
            $stmt->bindParam(':table_name', $table_name);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                $result[$line] = [
                    'total_qty' => 0,
                    'total_items' => 0,
                    'unique_items' => 0
                ];
                continue;
            }

            $query = "SELECT 
                        SUM(qty) as total_qty,
                        COUNT(*) as total_items,
                        COUNT(DISTINCT item) as unique_items
                      FROM " . $table_name . " 
                      WHERE DATE(created_at) BETWEEN :start_date AND :end_date";
            
            try {
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':start_date', $start_date);
                $stmt->bindParam(':end_date', $end_date);
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $result[$line] = [
                    'total_qty' => (int)($row['total_qty'] ?? 0),
                    'total_items' => (int)($row['total_items'] ?? 0),
                    'unique_items' => (int)($row['unique_items'] ?? 0)
                ];
                
            } catch (PDOException $e) {
                error_log("Error querying $table_name: " . $e->getMessage());
                $result[$line] = [
                    'total_qty' => 0,
                    'total_items' => 0,
                    'unique_items' => 0
                ];
            }
        }
        
        return $result;
    }

    // ดึงข้อมูลส่งออก excel รายละเอียด
    public function getDetailReport($start_date, $end_date) {
        $result = [];

        foreach ($this->tables as $line => $table_name) {
            $query = "SELECT 
                        item, qty, 
                        DATE(created_at) as date, 
                        TIME(created_at) as time
                    FROM $table_name
                    WHERE DATE(created_at) BETWEEN :start_date AND :end_date 
                    ORDER BY created_at ASC";

            try {
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':start_date', $start_date);
                $stmt->bindParam(':end_date', $end_date);
                $stmt->execute();

                $result[$line] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $result[$line] = [];
            }
        }

        return $result;
    }


    // Debug function - ตรวจสอบข้อมูลในตาราง
    public function debugTableData($start_date, $end_date) {
        $debug_info = [];
        
        foreach ($this->tables as $line => $table_name) {
            try {
                // ตรวจสอบว่าตารางมีอยู่จริง
                $check_table = "SHOW TABLES LIKE :table_name";
                $stmt = $this->conn->prepare($check_table);
                $stmt->bindParam(':table_name', $table_name);
                $stmt->execute();
                
                if ($stmt->rowCount() == 0) {
                    $debug_info[$line] = ['error' => 'Table does not exist'];
                    continue;
                }

                // นับจำนวนข้อมูลทั้งหมด
                $count_query = "SELECT COUNT(*) as total FROM " . $table_name;
                $stmt = $this->conn->prepare($count_query);
                $stmt->execute();
                $total_count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

                // นับจำนวนข้อมูลในช่วงวันที่
                $date_count_query = "SELECT COUNT(*) as date_count FROM " . $table_name . " 
                                    WHERE DATE(created_at) BETWEEN :start_date AND :end_date";
                $stmt = $this->conn->prepare($date_count_query);
                $stmt->bindParam(':start_date', $start_date);
                $stmt->bindParam(':end_date', $end_date);
                $stmt->execute();
                $date_count = $stmt->fetch(PDO::FETCH_ASSOC)['date_count'];

                // ดูข้อมูลตัวอย่าง
                $sample_query = "SELECT * FROM " . $table_name . " 
                                WHERE DATE(created_at) BETWEEN :start_date AND :end_date LIMIT 3";
                $stmt = $this->conn->prepare($sample_query);
                $stmt->bindParam(':start_date', $start_date);
                $stmt->bindParam(':end_date', $end_date);
                $stmt->execute();
                $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $debug_info[$line] = [
                    'table_exists' => true,
                    'total_records' => $total_count,
                    'date_range_records' => $date_count,
                    'sample_data' => $samples
                ];

            } catch (PDOException $e) {
                $debug_info[$line] = ['error' => $e->getMessage()];
            }
        }
        
        return $debug_info;
    }
}
?>