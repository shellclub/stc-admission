<?php 
session_start();
require('config.inc.php');
mysqli_set_charset($conn,"utf8");

$ids1 = mysqli_real_escape_string($conn, $_REQUEST['ids']);
$name = mysqli_real_escape_string($conn, $_REQUEST['sname']);
$de_id = mysqli_real_escape_string($conn, $_REQUEST['de_id']);
$score = 50; // ค่าเริ่มต้น

// เช็คประเภทผู้สอบเพื่อเลือกไฟล์ข้อสอบ
$idc = substr($ids1, 0, 1); 
if ($idc == '3') {
    $sql = "SELECT level FROM tb_user_new WHERE id = '$ids1'"; 
    $result = $conn->query($sql); 
    $row = $result->fetch_assoc();
    $level = $row['level'];
    
    if ($level == '2') {
        $link = "test_exam.php";
    } else {
        $link = "test_exam_depo.php";
        $score = 40;
    }
} else if ($idc == '4') {
    $link = "test_exam_depo.php";
    $score = 40;
} else {
    $link = "test_exam.php";
}

// ตรวจสอบหรือบันทึกข้อมูลการเข้าสอบเบื้องต้น
$res_check = $conn->query("SELECT * FROM tb_user_test WHERE id = '$ids1'"); 
if ($res_check->num_rows == 0) {
    $conn->query("INSERT INTO tb_user_test (id, name, id_de, score) VALUES ('$ids1', '$name', '$de_id', '0')");
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>คำแนะนำการสอบ :: วิทยาลัยเทคนิคสุพรรณบุรี</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background: #f0f2f5;
            padding-top: 40px;
        }
        .instruction-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 700px;
            margin: 0 auto;
        }
        .header-banner {
            background: #f39c12; /* สีเหลืองทองตามธีมเดิมแต่ทำให้ดูพรีเมียมขึ้น */
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header-banner img {
            height: 70px;
            background: white;
            padding: 5px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .content-body { padding: 40px; }
        .rule-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .rule-icon {
            min-width: 40px;
            height: 40px;
            background: #fff4e5;
            color: #e67e22;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
        }
        .rule-text h4 { margin: 0 0 5px 0; font-weight: 700; color: #333; }
        .rule-text p { margin: 0; color: #666; font-size: 15px; }

        .warning-box {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            border-radius: 12px;
            padding: 20px;
            margin: 30px 0;
        }
        .warning-box h5 { color: #c53030; font-weight: 700; margin-top: 0; }
        .warning-box ul { padding-left: 20px; margin-bottom: 0; color: #742a2a; }

        .btn-start {
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-size: 20px;
            font-weight: bold;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }
        .btn-start:hover {
            background: #219150;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.4);
            color: white;
        }
        .footer-dev {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #888;
            padding-bottom: 40px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="instruction-card">
        <div class="header-banner">
            <img src="images/logo_web.png" alt="Logo">
            <h3 style="margin:0; font-weight:700;">ระเบียบและคำแนะนำการสอบ</h3>
            <p style="margin:5px 0 0 0; opacity: 0.9;">วิทยาลัยเทคนิคสุพรรณบุรี</p>
        </div>

        <div class="content-body">
            <div class="rule-item">
                <div class="rule-icon"><i class="fa fa-file-text-o"></i></div>
                <div class="rule-text">
                    <h4>จำนวนข้อสอบ</h4>
                    <p>ข้อสอบมีทั้งหมด <strong><?php echo $score; ?> ข้อ</strong> แบบปรนัยเลือกตอบ</p>
                </div>
            </div>

            <div class="rule-item">
                <div class="rule-icon"><i class="fa fa-clock-o"></i></div>
                <div class="rule-text">
                    <h4>เวลาในการทำสอบ</h4>
                    <p>คุณมีเวลาทั้งหมด <strong>60 นาที</strong> ระบบจะส่งคำตอบอัตโนมัติเมื่อหมดเวลา</p>
                </div>
            </div>

            <div class="rule-item">
                <div class="rule-icon"><i class="fa fa-check-circle-o"></i></div>
                <div class="rule-text">
                    <h4>การส่งผลสอบ</h4>
                    <p>เมื่อทำเสร็จเรียบร้อย ให้กดปุ่ม <strong>"ส่งคำตอบ"</strong> เพื่อบันทึกคะแนน</p>
                </div>
            </div>

            <div class="warning-box">
                <h5><i class="fa fa-exclamation-triangle"></i> ข้อควรระวังสำคัญ:</h5>
                <ul>
                    <li><strong>ห้าม</strong> กดปุ่ม Back (ย้อนกลับ) หรือปุ่ม Refresh หน้าจอขณะทำสอบ</li>
                    <li><strong>ห้าม</strong> ปิดเบราว์เซอร์หรือสลับหน้าต่างโปรแกรมไปมา</li>
                    <li>เมื่อกดเริ่มทำข้อสอบแล้ว จะไม่สามารถกลับมาทำซ้ำได้อีก</li>
                </ul>
            </div>

            <form action="<?php echo $link; ?>" method="GET">
                <input type="hidden" name="std_id" value="<?php echo $ids1; ?>">
                <button type="submit" class="btn btn-start">
                    <i class="fa fa-play-circle"></i> เข้าสู่ห้องสอบและเริ่มทำเวลา
                </button>
            </form>
        </div>
    </div>

    <div class="footer-dev">
        <i class="fa fa-user"></i> พัฒนาโดย นายศุภโชค พานทอง และ นายสุธีร์ แบนประเสริฐ แผนกอิเล็กทรอนิกส์
    </div>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>