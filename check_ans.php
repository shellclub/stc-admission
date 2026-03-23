<?php 
session_start();
date_default_timezone_set('Asia/Bangkok');
require('config.inc.php');
mysqli_set_charset($conn, "utf8");

// 1. รับค่ารหัสผู้สอบ
$ids1 = isset($_POST['std_id']) ? mysqli_real_escape_string($conn, $_POST['std_id']) : (isset($_SESSION['std_id']) ? $_SESSION['std_id'] : '');

if (empty($ids1)) {
    echo "<script>alert('ไม่พบรหัสผู้สอบ กรุณาเข้าสู่ระบบใหม่'); window.location='index.html';</script>";
    exit();
}

// 2. ดึงข้อมูลนักเรียน
$sql_user = "SELECT * FROM tb_user_new WHERE id='$ids1' LIMIT 1";
$res_user = $conn->query($sql_user);
if ($row_user = $res_user->fetch_assoc()) {
    $name = $row_user['name'];
    $major = $row_user['major'];
}

// 3. เลือกว่าจะใช้ชุดข้อสอบไหน
$idc = substr($ids1, 0, 1);
$is_depo = false;

if ($idc == '4' || $idc == '3') {
    if ($idc == '4') {
        $is_depo = true;
    } else {
        $sql_lvl = "SELECT level FROM tb_user_new WHERE id = '$ids1'";
        $res_lvl = $conn->query($sql_lvl);
        if($row_lvl = $res_lvl->fetch_assoc()){
            if ($row_lvl['level'] != '2') { $is_depo = true; }
        }
    }
}

// --- จุดสำคัญ: ต้องแน่ใจว่าไฟล์ rand_test ทั้งสองใช้ $ids1 ในการสร้าง Seed ---
if ($is_depo) {
    include("rand_test_depo.php"); 
    $total_limit = 40; 
} else {
    include("rand_test.php"); 
    $total_limit = 50; 
}

// 4. ดึงคำตอบจาก DB (เช็คชื่อคอลัมน์ question_id และ selected_choice ให้ตรงกับ save_answer.php)
$sql_ans = "SELECT question_id, selected_choice FROM tb_user_answers WHERE std_id = '$ids1'";
$res_ans = $conn->query($sql_ans);
$user_answers = [];
if ($res_ans) {
    while($row_a = $res_ans->fetch_assoc()) {
        $user_answers[$row_a['question_id']] = $row_a['selected_choice'];
    }
}

// 5. ประมวลผลคะแนน
$correct = 0;
$results_log = [];
for($i=0; $i<$total_limit; $i++) {
    $q_id = $id_s[$i];      // ID จากไฟล์ rand_test
    $correct_choice = $ans[$i]; // เฉลยจากไฟล์ rand_test
    
    // ตรวจสอบค่าที่ส่งมาจาก DB
    $user_choice = isset($user_answers[$q_id]) ? $user_answers[$q_id] : 0;
    
    if (trim($user_choice) == trim($correct_choice) && $user_choice != 0) {
        $correct++;
    }
    
    $results_log[] = [
        'num' => ($i + 1),
        'id' => $q_id,
        'answer' => $correct_choice,
        'choice' => $user_choice
    ];
}

// 6. บันทึกลงฐานข้อมูล
$date_out = date('d/m/Y,H:i');
$json_results = mysqli_real_escape_string($conn, json_encode($results_log));

// ตรวจสอบชื่อคอลัมน์ dete_log หรือ date_log ในตารางของคุณ (ถ้าผิด คะแนนจะไม่เข้า)
$conn->query("UPDATE tb_log_test SET dete_log = '$date_out', num_test = '$correct' WHERE id = '$ids1'");
$conn->query("UPDATE tb_user_new SET score = '$correct' WHERE id = '$ids1'");
$conn->query("UPDATE tb_user_test SET score = '$correct' WHERE id = '$ids1'");
$conn->query("REPLACE INTO tb_test_data (id, test, date_end) VALUES ('$ids1', '$json_results', '$date_out')");

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>สรุปผลการทดสอบ - วิทยาลัยเทคนิคสุพรรณบุรี</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap');
        body { background: #f4f7f6; font-family: 'Sarabun', sans-serif; }
        .result-card {
            background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: 40px; overflow: hidden; border: none;
        }
        .result-header { background: #ffc107; color: #fff; padding: 30px; text-align: center; }
        .score-display {
            width: 150px; height: 150px; border: 8px solid #28a745; border-radius: 50%;
            margin: 20px auto; display: flex; align-items: center; justify-content: center;
            flex-direction: column; background: #fff; color: #28a745;
        }
        .score-num { font-size: 48px; font-weight: bold; line-height: 1; }
        .score-label { font-size: 14px; text-transform: uppercase; }
        .btn-home { border-radius: 50px; padding: 12px 40px; font-weight: bold; margin-top: 20px; }
        .footer-dev { background: #3c8dbc; color: #fff; padding: 15px; margin-top: 30px; border-radius: 0 0 20px 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="result-card">
                <div class="result-header">
                    <img src="images/logo_web.png" class="img-circle" width="80" style="background: #fff; padding: 5px; margin-bottom: 10px;">
                    <h3 style="margin: 0; font-weight: bold;">ส่งคำตอบเรียบร้อยแล้ว</h3>
                    <p style="margin: 0; opacity: 0.9;">วิทยาลัยเทคนิคสุพรรณบุรี</p>
                </div>
                
                <div class="box-body text-center" style="padding: 40px;">
                    <h4 style="color: #555;">คะแนนสอบของคุณคือ</h4>
                    <div class="score-display">
                        <span class="score-num"><?php echo $correct; ?></span>
                        <span class="score-label">คะแนน</span>
                    </div>
                    
                    <div class="row" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                        <div class="col-sm-6 text-left">
                            <p><strong>ชื่อ-นามสกุล:</strong> <?php echo $name; ?></p>
                            <p><strong>รหัสประจำตัวสอบ:</strong> <?php echo $ids1; ?></p>
                        </div>
                        <div class="col-sm-6 text-left">
                            <p><strong>สาขาที่เลือกสอบ:</strong> <?php echo $major; ?></p>
                            <p><strong>วันที่ส่ง:</strong> <?php echo $date_out; ?></p>
                        </div>
                    </div>

                    <a href="index.html" class="btn btn-danger btn-lg btn-home">
                        <i class="fa fa-home"></i> กลับสู่หน้าหลัก
                    </a>
                </div>

                <div class="footer-dev text-center">
                    <small>
                        <i class="fa fa-code"></i> พัฒนาโปรแกรมโดย นายศุภโชค พานทอง และ นายสุธีร์ แบนประเสริฐ แผนกอิเล็กทรอนิกส์
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>