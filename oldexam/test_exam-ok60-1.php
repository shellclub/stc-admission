<?php 
session_start();
date_default_timezone_set('Asia/Bangkok');
require('config.inc.php');
mysqli_set_charset($conn, "utf8");

// 1. ตรวจสอบรหัสผู้สอบ
if (isset($_REQUEST['std_id'])) {
    $ids1 = mysqli_real_escape_string($conn, $_REQUEST['std_id']);
    $_SESSION['std_id'] = $ids1;
} elseif (isset($_SESSION['std_id'])) {
    $ids1 = $_SESSION['std_id'];
} else {
    header("Location: index.html"); exit();
}

// 2. ดึงข้อมูลนักเรียน
$sql_user = "SELECT * FROM tb_user_new WHERE id='$ids1'";
$res_user = $conn->query($sql_user);
if ($row = $res_user->fetch_assoc()) {
    $sname = $row['name'];
    $major = $row['major'];
    $level_txt = ($row['level'] == 1) ? "ปวช." : (($row['level'] == 4) ? "ป.ตรี" : "ปวส.");
}


// 3. จัดการเวลาเริ่มสอบ
$sql_time = "SELECT date_in FROM tb_log_test WHERE id='$ids1' LIMIT 1";
$res_time = $conn->query($sql_time);
if ($res_time && $row_time = $res_time->fetch_assoc()) {
    $start_str = str_replace(',', ' ', $row_time['date_in']);
    $dateTimeObj = DateTime::createFromFormat('d/m/Y H:i', $start_str);
    $startTime = ($dateTimeObj) ? $dateTimeObj->getTimestamp() : strtotime($start_str);
    $elapsedSeconds = time() - $startTime;
} else {
    $date_now = date('d/m/Y,H:i');
    $conn->query("INSERT INTO tb_log_test (id, date_in, num_test) VALUES ('$ids1', '$date_now', '0')");
    $elapsedSeconds = 0;
}
$elapsedSeconds = ($elapsedSeconds < 0) ? 0 : $elapsedSeconds;

// 4. ระบบแบ่งหน้า
$limit = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// ดักจับ: ถ้าค่า page น้อยกว่า 1 ให้กลับไปเป็นหน้า 1 เสมอ
if ($page < 1) {
    $page = 1;
}

// คำนวณ Index เริ่มต้น
$start_idx = ($page - 1) * $limit;

// ดักจับเพิ่ม: ถ้า start_idx ดันติดลบ ให้เป็น 0 (ป้องกัน Error Undefined key)
if ($start_idx < 0) {
    $start_idx = 0;
}

// 5. โหลดข้อสอบ
include("rand_test.php"); 

function getSaved($std_id, $q_id, $choice_no, $conn) {
    $sql = "SELECT selected_choice FROM tb_user_answers WHERE std_id='$std_id' AND question_id='$q_id'";
    $res = $conn->query($sql);
    if ($res && $r = $res->fetch_assoc()) return ($r['selected_choice'] == $choice_no) ? "checked" : "";
    return "";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>ระบบทดสอบออนไลน์ - วิทยาลัยเทคนิคสุพรรณบุรี</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="swalert/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700&display=swap');
        
        body { 
            background: #f0f2f5; 
            padding-top: 80px; 
            padding-bottom: 100px;
            user-select: none; 
            font-family: 'Sarabun', sans-serif;
        }

        /* Navbar Header */
        .navbar-fixed-top { 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            border: none;
        }

        /* Card Style */
        .info-card, .question-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            border: none;
        }

        /* Question Text */
        .question-text {
            font-size: 1.2em;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        /* Choice Buttons */
        .choice-container { 
            display: block; 
            margin-bottom: 12px; 
            cursor: pointer; 
        }
        .choice-container input { display: none; }
        .checkmark { 
            display: flex; 
            align-items: center; 
            padding: 18px; 
            background: #ffffff; 
            border: 2px solid #edf2f7; 
            border-radius: 12px; 
            transition: all 0.3s ease; 
        }
        .choice-container:hover .checkmark {
            background: #f8fafc;
            border-color: #cbd5e0;
        }
        .choice-container input:checked + .checkmark { 
            background: #f0fff4; 
            border-color: #48bb78; 
            color: #22543d; 
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .img-q { 
            max-width: 100%; 
            height: auto; 
            border-radius: 10px; 
            margin-bottom: 20px;
            border: 1px solid #eee;
        }

        /* Pagination */
        .pagination > li > a { border-radius: 8px !important; margin: 0 3px; color: #4a5568; }
        .pagination > .active > a { background-color: #28a745 !important; border-color: #28a745 !important; }

        /* Footer */
        .footer-dev {
            background: #fff;
            padding: 20px;
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #718096;
            border-radius: 15px 15px 0 0;
        }
        .footer-logo { max-height: 50px; margin-bottom: 10px; }
        
    </style>
</head>
<body class="hold-transition skin-green layout-top-nav">

<nav class="navbar navbar-fixed-top bg-green">
    <div class="container">
        <div class="navbar-header" style="display:flex; justify-content: space-between; width: 100%; padding: 0 15px; align-items: center; height: 50px;">
            <span class="navbar-brand" id="time_tt" style="color:white; font-size: 1.1em; font-weight: bold;">
                <i class="fa fa-clock-o"></i> เวลา: 60:00
            </span>
            <span class="navbar-brand" id="numtest" style="color:white; font-size: 1.1em; font-weight: bold;">
                <i class="fa fa-pencil"></i> ทำไป 0/60 ข้อ
            </span>
        </div>
    </div>
</nav>

<div class="container">
    <div class="info-card">
        <div class="box-body" style="padding: 20px;">
            <div class="row">
                <div class="col-xs-3 col-sm-2 text-center">
                    <img src="images/logo_web.png" class="img-responsive" style="max-height: 70px; margin: 0 auto;">
                </div>
                <div class="col-xs-9 col-sm-10">
                    <h4 style="margin-top: 0; font-weight: 700; color: #2d3748;">วิทยาลัยเทคนิคสุพรรณบุรี</h4>
                    <p style="margin-bottom: 5px;"><strong>ผู้เข้าสอบ:</strong> <?php echo $sname; ?> | <strong>รหัส:</strong> <?php echo $ids1; ?></p>
                    <p style="margin-bottom: 10px;"><strong>สาขาวิชา:</strong> <?php echo $major; ?></p>
                    <div class="progress progress-xs" style="margin-bottom: 0;">
                        <div id="perc" class="progress-bar progress-bar-success" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="check_ans.php" method="POST" id="form1">
        <input type="hidden" name="std_id" value="<?php echo $ids1; ?>">
        
        <?php for($i=$start_idx; $i < ($start_idx + $limit) && $i < 60; $i++): ?>
        <div class="question-card">
            <div class="box-header with-border" style="padding: 20px;">
                <span class="label label-success" style="font-size: 14px;">ข้อที่ <?php echo $i+1; ?></span>
                <div style="margin-top: 15px;">
                    <?php if($img_q[$i]) echo "<img src='ImagesQuestions/{$img_q[$i]}' class='img-q'>"; ?>
                    <p class="question-text"><?php echo $question[$i]; ?></p>
                </div>
            </div>
            <div class="box-body" style="padding: 20px;">
                <div class="row">
                    <?php for($n=1; $n<=4; $n++): 
                        $txt = ${"choice".$n}[$i]; $img = ${"choice".$n."_img"}[$i];
                    ?>
                    <div class="col-xs-12 col-sm-6">
                        <label class="choice-container">
                            <input type="radio" name="select_<?php echo $id_s[$i]; ?>" value="<?php echo $n; ?>" 
                                   <?php echo getSaved($ids1, $id_s[$i], $n, $conn); ?>
                                   onchange="saveToDB('<?php echo $ids1; ?>', '<?php echo $id_s[$i]; ?>', <?php echo $n; ?>)">
                            <span class="checkmark">
                                <div style="width:100%">
                                    <?php if($img) echo "<img src='ImagesQuestions/$img' style='max-width:80px; margin-bottom:10px; border-radius:5px;'><br>"; ?>
                                    <?php echo $txt; ?>
                                </div>
                            </span>
                        </label>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <?php endfor; ?>

        <div class="text-center" style="margin-top: 30px;">
            <ul class="pagination">
                <?php for($p=1; $p<=6; $p++): ?>
                    <li class="<?php if($page==$p) echo 'active'; ?>">
                        <a href="?page=<?php echo $p; ?>&std_id=<?php echo $ids1; ?>"><?php echo $p; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
            <br><br>
            <button type="button" class="btn btn-block btn-success btn-lg" style="border-radius:12px; font-weight: bold; padding: 15px; box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);" onclick="finishExam()">
               <i class="fa fa-paper-plane"></i> ส่งคำตอบทั้งหมด (Submit)
            </button>
        </div>
    </form>
    
    <div class="footer-dev">
        <img src="images/logo_web.png" class="footer-logo">
        <p style="margin-bottom: 5px;"><strong>วิทยาลัยเทคนิคสุพรรณบุรี</strong></p>
        <p style="font-size: 0.9em;">พัฒนาโดย: นายศุภโชค พานทอง แผนกอิเล็กทรอนิกส์</p>
    </div>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="swalert/dist/sweetalert2.min.js"></script>

<script>
$(document).on("keydown", function(e) {
    if ((e.which || e.keyCode) == 116 || (e.ctrlKey && (e.which || e.keyCode) == 82)) e.preventDefault();
});
window.onbeforeunload = function() { return "ระบบกำลังทำงาน คุณต้องการออกจากหน้าสอบใช่หรือไม่?"; };

var totalSeconds = <?php echo $elapsedSeconds; ?>;
var timerVar = setInterval(function() {
    totalSeconds++;
    var remain = 3600 - totalSeconds;
    if (remain <= 0) {
        clearInterval(timerVar);
        window.onbeforeunload = null;
        $('#form1').submit();
    }
    var m = Math.floor(remain / 60);
    var s = remain % 60;
    $('#time_tt').html('<i class="fa fa-clock-o"></i> เวลา: ' + m + ":" + (s < 10 ? "0"+s : s));
    $('#perc').css('width', (totalSeconds/3600*100) + '%');
}, 1000);

function updateCount() {
    $.get('get_count.php', { std_id: '<?php echo $ids1; ?>' }, function(data) {
        $('#numtest').html('<i class="fa fa-pencil"></i> ทำไป ' + data + ' / 60 ข้อ');
    });
}

function saveToDB(sid, qid, val) {
    $.post('save_answer.php', { std_id: sid, question_id: qid, selected_choice: val }, function() {
        updateCount();
    });
}

function finishExam() {
    $.get('get_count.php', { std_id: '<?php echo $ids1; ?>' }, function(data) {
        Swal.fire({
            title: 'ยืนยันการส่งข้อสอบ?',
            text: "คุณทำไปแล้ว " + data + " จาก 60 ข้อ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'ตกลง ส่งคำตอบ',
            cancelButtonText: 'ยกเลิก'
        }).then((res) => {
            if (res.isConfirmed) {
                window.onbeforeunload = null;
                $('#form1').submit();
            }
        });
    });
}

$(document).ready(updateCount);
</script>
</body>
</html>
<?php $conn->close(); ?>