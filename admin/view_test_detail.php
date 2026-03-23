<?php
/**
 * Page: view_test_detail.php
 * Description: แสดงผลการตอบรายข้อ พร้อมคำนวณระยะเวลาที่ใช้สอบ
 */
require('../config.inc.php');
mysqli_set_charset($conn, "utf8");

// ตั้งค่า Timezone เพื่อความแม่นยำในการคำนวณ
date_default_timezone_set('Asia/Bangkok');

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (empty($id)) {
    echo "<div class='alert alert-danger'>ไม่พบรหัสผู้สอบ</div>";
    exit;
}

// 1. ดึงข้อมูล JSON และข้อมูลประวัติการเข้าสอบ (JOIN tb_log_test)
$sql = "SELECT t.test, u.name, u.major, l.date_in, l.dete_log 
        FROM tb_test_data t
        LEFT JOIN tb_user_new u ON t.id = u.id 
        LEFT JOIN tb_log_test l ON t.id = l.id
        WHERE t.id = '$id'";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    echo "<div class='alert alert-warning'>ไม่พบข้อมูลการสอบ</div>";
    exit;
}

$data = $result->fetch_assoc();
$test_results = json_decode($data['test'], true);

// --- ส่วนคำนวณเวลาที่ใช้สอบ ---
$time_spent = "ไม่ทราบข้อมูล";

if (!empty($data['date_in']) && !empty($data['dete_log'])) {
    // ระบุรูปแบบให้ตรงกับข้อมูลใน DB คือ วัน/เดือน/ปี,ชั่วโมง:นาที
    $format = "d/m/Y,H:i"; 
    
    $start = DateTime::createFromFormat($format, $data['date_in']);
    $end = DateTime::createFromFormat($format, $data['dete_log']);

    // ตรวจสอบว่าแปลงสำเร็จหรือไม่ก่อนคำนวณ
    if ($start && $end) {
        $interval = $start->diff($end);
        
        // คำนวณเป็นนาทีทั้งหมด
        $minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
        $time_spent = $minutes . " นาที " . $interval->s . " วินาที";
    } else {
        // หากรูปแบบใน DB ไม่ตรงกับ d/m/Y,H:i ให้ลองใช้ระบบคำนวณแบบพื้นฐาน
        $time_spent = "รูปแบบเวลาในระบบไม่ถูกต้อง";
    }
}
?>

<style>
    .info-header { background: #f9f9f9; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #eee; }
    .time-badge { background: #605ca8; color: #fff; padding: 5px 15px; border-radius: 15px; font-weight: bold; }
    @media print { .no-print { display: none !important; } }
</style>

<section class="content-header">
    <h1><i class="fa fa-file-text-o text-primary"></i> รายละเอียดการตอบข้อสอบ</h1>
</section>

<section class="content">
    <div class="box box-solid shadow">
        <div class="box-header with-border">
            <h3 class="box-title" style="font-size: 20px;">
                ข้อมูลผู้สอบ: <b><?php echo $data['name']; ?></b> 
            </h3>
            <div class="box-tools pull-right">
                <a href="print_result.php?id=<?php echo $id; ?>" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fa fa-print"></i> พิมพ์ผลคะแนน
                </a>
            </div>
        </div>
        <div class="box-body">
            
            <div class="info-header">
                <div class="row">
                    <div class="col-md-6">
                        <p><b>ชื่อ-นามสกุล:</b> <?php echo $data['name']; ?> (<?php echo $id; ?>)</p>
                        <p><b>สาขาวิชา:</b> <?php echo $data['major']; ?></p>
                    </div>
                    <div class="col-md-6 text-right">
                        <p><i class="fa fa-clock-o"></i> <b>เริ่มสอบ:</b> <?php echo $data['date_in']; ?></p>
                        <p><i class="fa fa-send"></i> <b>ส่งข้อสอบ:</b> <?php echo $data['dete_log']; ?></p>
                        <p><b>ใช้เวลาสอบทั้งสิ้น:</b> <span class="time-badge"><?php echo $time_spent; ?></span></p>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr style="background-color: #f4f4f4;">
                        <th width="80" class="text-center">ข้อที่</th>
                        <th class="text-center">รหัสข้อถาม</th>
                        <th class="text-center">เฉลย</th>
                        <th class="text-center">เลือกตอบ</th>
                        <th width="120" class="text-center">ผลลัพธ์</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $score_total = 0;
                        $correct = 0; $wrong = 0;
                        foreach ($test_results as $item) {
                            $is_correct = (trim($item['answer']) == trim($item['choice']));
                            if($is_correct) { $score_total++; $correct++; } else { $wrong++; }
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $item['num']; ?></td>
                        <td class="text-center text-muted"><?php echo $item['id']; ?></td>
                        <td class="text-center text-success"><b><?php echo $item['answer']; ?></b></td>
                        <td class="text-center <?php echo $is_correct ? 'text-primary' : 'text-danger'; ?>">
                            <b><?php echo $item['choice']; ?></b>
                        </td>
                        <td class="text-center">
                            <?php echo $is_correct ? '<span class="text-success">ถูก</span>' : '<span class="text-danger">ผิด</span>'; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr style="background: #eee; font-size: 16px;">
                        <th colspan="2" class="text-center">สรุปผลการสอบ</th>
                        <th class="text-center text-success">ถูก: <?php echo $correct; ?></th>
                        <th class="text-center text-danger">ผิด: <?php echo $wrong; ?></th>
                        <th class="text-center" style="background: #3c8dbc; color: #fff;">คะแนน: <?php echo $score_total; ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</section>