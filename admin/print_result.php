<?php
require('../config.inc.php');
mysqli_set_charset($conn, "utf8");

// ตั้งค่าเขตเวลาเป็นประเทศไทย
date_default_timezone_set('Asia/Bangkok');

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (empty($id)) die("Error: ID not found");

// ดึงข้อมูลผลสอบ JOIN กับข้อมูลผู้ใช้ และ Log การเข้าสอบ
$sql = "SELECT t.test, u.name, u.major, l.date_in, l.dete_log 
        FROM tb_test_data t 
        LEFT JOIN tb_user_new u ON t.id = u.id 
        LEFT JOIN tb_log_test l ON t.id = l.id 
        WHERE t.id = '$id'";

$result = $conn->query($sql);
$data = $result->fetch_assoc();
$test_results = json_decode($data['test'], true);

// --- ฟังก์ชันคำนวณระยะเวลาที่ใช้สอบ ---
function calculate_time_spent($start_time, $end_time) {
    if (empty($start_time) || empty($end_time)) return "ไม่ทราบข้อมูล";

    // จัดการรูปแบบ d/m/Y,H:i ให้เป็นมาตรฐาน PHP
    $format = "d/m/Y,H:i";
    $start_obj = DateTime::createFromFormat($format, $start_time);
    $end_obj = DateTime::createFromFormat($format, $end_time);

    if ($start_obj && $end_obj) {
        $diff = $start_obj->diff($end_obj);
        $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
        return $minutes . " นาที " . $diff->s . " วินาที";
    }
    return "รูปแบบเวลาไม่ถูกต้อง";
}

// ฟังก์ชันแปลงวันที่ไทย
function thai_date_full($time){
    if(empty($time) || $time == '0000-00-00 00:00:00') return "-";
    
    $thai_month_arr = array(
        "0"=>"", "1"=>"มกราคม", "2"=>"กุมภาพันธ์", "3"=>"มีนาคม", "4"=>"เมษายน",
        "5"=>"พฤษภาคม", "6"=>"มิถุนายน", "7"=>"กรกฎาคม", "8"=>"สิงหาคม",
        "9"=>"กันยายน", "10"=>"ตุลาคม", "11"=>"พฤศจิกายน", "12"=>"ธันวาคม"                 
    );
    
    if (strpos($time, '/') !== false) {
        $time = str_replace(',', ' ', $time);
        $parts = explode(' ', $time);
        $date_parts = explode('/', $parts[0]);
        $time_val = strtotime($date_parts[2].'-'.$date_parts[1].'-'.$date_parts[0].' '.$parts[1]);
    } else {
        $time_val = strtotime($time);
    }

    $thai_date_return = date("j", $time_val);
    $thai_date_return .= " " . $thai_month_arr[date("n", $time_val)];
    $thai_date_return .= " " . (date("Y", $time_val) + 543);
    $thai_date_return .= " เวลา " . date("H:i", $time_val) . " น.";
    return $thai_date_return;
}

$time_spent = calculate_time_spent($data['date_in'], $data['dete_log']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Print Result - <?php echo $id; ?></title>
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Sarabun', sans-serif; padding: 20px; background: #fff; color: #000; }
        .header-print { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .info-exam { margin-bottom: 15px; font-size: 14px; background: #f9f9f9; padding: 10px; border-radius: 5px; }
        .table-print { width: 100%; border: 1px solid #000 !important; }
        .table-print th, .table-print td { 
            border: 1px solid #000 !important; 
            padding: 4px !important; 
            text-align: center; 
            font-size: 13px;
        }
        .text-bold { font-weight: bold; }
        @media print {
            .no-print { display: none; }
            .info-exam { background: none !important; border: 1px solid #ccc; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print();">

    <div class="no-print text-center" style="margin-bottom: 20px;">
        <button onclick="window.print();" class="btn btn-success btn-lg">พิมพ์รายงาน / Save PDF</button>
        <button onclick="window.close();" class="btn btn-default btn-lg">ปิดหน้าต่าง</button>
    </div>

    <div class="header-print">
        <h3 style="margin-bottom: 5px;">รายงานผลการทดสอบออนไลน์</h3>
        <p style="font-size: 16px; margin-bottom: 5px;">
            <b>ชื่อ-นามสกุล:</b> <?php echo $data['name']; ?> &nbsp;&nbsp;
            <b>รหัสผู้สอบ:</b> <?php echo $id; ?>
        </p>
        <p style="font-size: 14px;"><b>สาขาวิชา:</b> <?php echo $data['major']; ?></p>
    </div>

    <div class="info-exam">
        <div class="row">
            <div class="col-xs-4"><b>วันที่เข้าสอบ:</b><br><?php echo thai_date_full($data['date_in']); ?></div>
            <div class="col-xs-4"><b>วันที่ส่งข้อสอบ:</b><br><?php echo thai_date_full($data['dete_log']); ?></div>
            <div class="col-xs-4 text-right">
                <div style="font-size: 16px; color: #d9534f; font-weight: bold;">
                    <i class="fa fa-clock-o"></i> ระยะเวลาที่ใช้: <?php echo $time_spent; ?>
                </div>
            </div>
        </div>
    </div>

    <table class="table-print">
        <thead>
            <tr style="background: #f4f4f4;">
                <th>ข้อ</th><th>รหัส</th><th>เฉลย</th><th>ตอบ</th><th>ผล</th>
                <th>ข้อ</th><th>รหัส</th><th>เฉลย</th><th>ตอบ</th><th>ผล</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = count($test_results);
            $half = ceil($total / 2);
            $score = 0;

            for ($i = 0; $i < $half; $i++) {
                echo "<tr>";
                renderRow($test_results[$i], $score);
                if (isset($test_results[$i + $half])) {
                    renderRow($test_results[$i + $half], $score);
                } else {
                    echo "<td></td><td></td><td></td><td></td><td></td>";
                }
                echo "</tr>";
            }

            function renderRow($item, &$score) {
                $is_correct = (trim($item['answer']) == trim($item['choice']));
                if($is_correct) $score++;
                $status = $is_correct ? "✓" : "✗";
                echo "<td>{$item['num']}</td>";
                echo "<td>{$item['id']}</td>";
                echo "<td class='text-bold'>{$item['answer']}</td>";
                echo "<td class='text-bold'>{$item['choice']}</td>";
                echo "<td>{$status}</td>";
            }
            ?>
        </tbody>
    </table>

    <div style="margin-top: 20px; border-top: 2px solid #000; padding-top: 10px;">
        <table style="width: 100%;">
            <tr>
                <td style="font-size: 20px;"><b>คะแนนรวมที่ได้: <?php echo $score; ?> / <?php echo $total; ?> คะแนน</b></td>
                <td class="text-right" style="font-size: 12px; color: #666;">
                    พิมพ์รายงานเมื่อ: <?php echo thai_date_full(date('Y-m-d H:i:s')); ?>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>