<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

include 'connect.php'; // เชื่อมต่อฐานข้อมูล 

class SewingReport {
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

    // ดึงข้อมูลรายชั่วโมงตามช่วงวันที่
    public function getHourlyReport($start_date, $end_date) {
        $result = [];
        
        foreach ($this->tables as $line => $table_name) {
            $query = "SELECT 
                        HOUR(created_at) as hour,
                        SUM(qty) as total_qty,
                        COUNT(*) as total_items
                      FROM " . $table_name . " 
                      WHERE DATE(created_at) BETWEEN :start_date AND :end_date
                      GROUP BY HOUR(created_at) 
                      ORDER BY hour";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();
            
            $hourly_data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $hourly_data[$row['hour']] = (int)$row['total_qty'];
            }
            
            // เติมข้อมูลช่วงเวลา 8:00-16:00 ที่ไม่มีข้อมูล
            for ($hour = 8; $hour <= 16; $hour++) {
                if (!isset($hourly_data[$hour])) {
                    $hourly_data[$hour] = 0;
                }
            }
            ksort($hourly_data);
            
            $result[$line] = array_values($hourly_data);
        }
        
        return $result;
    }

    // ดึงข้อมูลรายวันตามช่วงวันที่
    public function getDailyReport($start_date, $end_date) {
        $result = [];
        
        foreach ($this->tables as $line => $table_name) {
            $query = "SELECT 
                        DATE(created_at) as date,
                        SUM(qty) as total_qty,
                        COUNT(*) as total_items
                      FROM " . $table_name . " 
                      WHERE DATE(created_at) BETWEEN :start_date AND :end_date
                      GROUP BY DATE(created_at) 
                      ORDER BY date";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();
            
            $daily_data = [];
            $labels = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $daily_data[] = (int)$row['total_qty'];
                $labels[] = date('d/m', strtotime($row['date']));
            }
            
            $result[$line] = $daily_data;
            if ($line === 'fc') { // ใช้ labels จาก fc เป็นตัวแทน
                $result['labels'] = $labels;
            }
        }
        
        return $result;
    }

    // ดึงข้อมูลสรุปรวม
    public function getSummaryReport($start_date, $end_date) {
        $result = [];
        
        foreach ($this->tables as $line => $table_name) {
            $query = "SELECT 
                        SUM(qty) as total_qty,
                        COUNT(*) as total_items,
                        COUNT(DISTINCT item) as unique_items
                      FROM " . $table_name . " 
                      WHERE DATE(created_at) BETWEEN :start_date AND :end_date";
            
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
        }
        
        return $result;
    }

    // ดึงข้อมูลรายละเอียดสำหรับ Export Excel
    public function getDetailReport($start_date, $end_date) {
        $result = [];
        
        foreach ($this->tables as $line => $table_name) {
            $query = "SELECT 
                        id,
                        item,
                        qty,
                        status,
                        created_at,
                        DATE(created_at) as date,
                        TIME(created_at) as time
                      FROM " . $table_name . " 
                      WHERE DATE(created_at) BETWEEN :start_date AND :end_date 
                      ORDER BY created_at DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();
            
            $result[$line] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $result;
    }
}

// Handle API Requests
try {
    // ใช้ $conn จากไฟล์ connect.php ที่ include มาแล้ว
    $report = new SewingReport($conn);

    $start_date = $_GET['start_date'] ?? date('Y-m-d');
    $end_date = $_GET['end_date'] ?? date('Y-m-d');
    $type = $_GET['type'] ?? 'hourly'; // hourly, daily, summary, detail

    switch ($type) {
        case 'hourly':
            $data = $report->getHourlyReport($start_date, $end_date);
            $data['labels'] = ['8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'];
            break;
            
        case 'daily':
            $data = $report->getDailyReport($start_date, $end_date);
            break;
            
        case 'summary':
            $data = $report->getSummaryReport($start_date, $end_date);
            break;
            
        case 'detail':
            $data = $report->getDetailReport($start_date, $end_date);
            break;
            
        default:
            throw new Exception('Invalid report type');
    }

    echo json_encode([
        'success' => true,
        'data' => $data,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'type' => $type
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>