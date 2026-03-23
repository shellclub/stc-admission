<?php 
require('config.inc.php');
mysqli_set_charset($conn,"utf8");

$ids1 = mysqli_real_escape_string($conn, $_REQUEST['id_std1']);

// 1. ตรวจสอบว่าเคยทำข้อสอบไปหรือยัง
$sql_check = "SELECT * FROM tb_user_new WHERE idcard='$ids1' AND score > 0"; 
$result_check = $conn->query($sql_check); 

if ($result_check->num_rows > 0) {
    echo "<script src='bower_components/jquery/dist/jquery.min.js'></script>
          <script src='swalert/dist/sweetalert2.min.js'></script>
          <link rel='stylesheet' href='swalert/dist/sweetalert2.min.css'>
          <script> 
            $(document).ready(function(){
                Swal.fire({
                    icon: 'warning',
                    title: 'ทำข้อสอบไปแล้ว',
                    text: 'ไม่สามารถทำข้อสอบซ้ำได้ กรุณาติดต่อเจ้าหน้าที่คุมสอบ',
                    confirmButtonColor: '#d33'
                }).then(() => { window.location='index.html'; });
            });
          </script>";
} else { 
    // 2. ดึงข้อมูลนักเรียนมาแสดงเพื่อยืนยัน
    $sql = "SELECT * FROM tb_user_new WHERE idcard ='$ids1'"; 
    $result = $conn->query($sql); 
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); 
        $idstd = $row['id'];
        $name = $row['name'];
        $level = $row['level'];
        $major = $row['major'];
        $de_id1 = $row['de_id']; 
    } else {  
        echo "<script src='bower_components/jquery/dist/jquery.min.js'></script>
              <script src='swalert/dist/sweetalert2.min.js'></script>
              <link rel='stylesheet' href='swalert/dist/sweetalert2.min.css'>
              <script> 
                $(document).ready(function(){
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่พบข้อมูล',
                        text: 'รหัสประจำตัวประชาชนไม่ถูกต้อง หรือไม่มีในระบบ',
                        confirmButtonColor: '#3085d6'
                    }).then(() => { window.location='index.html'; });
                });
              </script>";
        exit();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ยืนยันข้อมูล :: วิทยาลัยเทคนิคสุพรรณบุรี</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background: #f4f7f6;
            padding-top: 50px;
        }
        .confirm-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
            max-width: 600px;
            margin: 0 auto;
        }
        .header-banner {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header-banner img {
            height: 80px;
            background: white;
            padding: 5px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .data-body { padding: 30px; }
        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #f8fafd;
            border-radius: 12px;
        }
        .info-icon {
            width: 50px;
            height: 50px;
            background: #eef2f7;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #2575fc;
            margin-right: 15px;
        }
        .info-content label {
            display: block;
            font-size: 12px;
            color: #888;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        .info-content span {
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }
        .btn-confirm {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        .btn-confirm:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
            color: white;
        }
        .footer-dev {
            text-align: center;
            margin-top: 30px;
            font-size: 11px;
            color: #aaa;
            padding-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="confirm-card">
        <div class="header-banner">
            <img src="images/logo_web.png" alt="Logo">
            <h3 style="margin:0; font-weight:700;">ยืนยันข้อมูลผู้สมัครสอบ</h3>
            <p style="margin:5px 0 0 0; opacity: 0.8;">วิทยาลัยเทคนิคสุพรรณบุรี</p>
        </div>

        <div class="data-body">
            <p class="text-center text-muted" style="margin-bottom:25px;">กรุณาตรวจสอบข้อมูลของท่านให้ถูกต้องก่อนเริ่มทำข้อสอบ</p>

            <div class="info-row">
                <div class="info-icon"><i class="fa fa-id-badge"></i></div>
                <div class="info-content">
                    <label>รหัสประจำตัวสอบ</label>
                    <span><?php echo $idstd; ?></span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon"><i class="fa fa-user"></i></div>
                <div class="info-content">
                    <label>ชื่อ - นามสกุล</label>
                    <span><?php echo $name; ?></span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon"><i class="fa fa-book"></i></div>
                <div class="info-content">
                    <label>สาขาวิชาที่สมัคร</label>
                    <span><?php echo $major; ?></span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon"><i class="fa fa-graduation-cap"></i></div>
                <div class="info-content">
                    <label>ระดับชั้น</label>
                    <span>
                        <?php 
                        if($level == 1) echo "ปวช.";
                        else if($level == 2) echo "ปวส. (ม.6)";
                        else if($level == 3) echo "ปวส. (ปวช.)";
                        else if($level == 4) echo "ป.ตรี";
                        ?>
                    </span>
                </div>
            </div>

            <form action="confirm_data.php" method="POST">
                <input type="hidden" name="ids" value="<?php echo $idstd; ?>"/>
                <input type="hidden" name="sname" value="<?php echo $name; ?>"/>
                <input type="hidden" name="de_id" value="<?php echo $de_id1; ?>"/>
                
                <button type="submit" class="btn btn-confirm">
                    <i class="fa fa-check-circle"></i> ยืนยันข้อมูลและเริ่มทำข้อสอบ
                </button>
            </form>
            
            <a href="index.html" class="btn btn-link btn-block" style="margin-top:10px; color: #888;">ข้อมูลไม่ถูกต้อง? กลับไปแก้ไข</a>
        </div>
    </div>

    <div class="footer-dev">
        พัฒนาโปรแกรมโดย นายศุภโชค พานทอง และ นายสุธีร์ แบนประเสริฐ แผนกอิเล็กทรอนิกส์
    </div>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>