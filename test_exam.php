<?php 
session_start();
date_default_timezone_set('Asia/Bangkok');
require('config.inc.php');
mysqli_set_charset($conn, "utf8");

// 1. จัดการรหัสผู้สอบ (ดึงจาก URL หรือ Session)
if (isset($_REQUEST['std_id'])) {
    $ids1 = mysqli_real_escape_string($conn, $_REQUEST['std_id']);
    $_SESSION['std_id'] = $ids1;
} elseif (isset($_SESSION['std_id'])) {
    $ids1 = $_SESSION['std_id'];
} else {
    header("Location: index.html"); exit();
}

// 2. ดึงข้อมูลนักเรียน
$res_user = $conn->query("SELECT * FROM tb_user_new WHERE id='$ids1' LIMIT 1");
if ($row = $res_user->fetch_assoc()) {
    $sname = $row['name'];
    $major = $row['major'];
    $level_txt = ($row['level'] == 1) ? "ปวช." : (($row['level'] == 4) ? "ป.ตรี" : "ปวส.");
    $current_score = $row['score']; // ดึงคะแนนปัจจุบันจาก DB

    // --- เพิ่มส่วนตรวจสอบตรงนี้ ---
    // ถ้าคะแนนมีค่ามากกว่า 0 หรือไม่ได้เป็น NULL (สอบเสร็จแล้ว)
    if ($current_score !== null && $current_score > 0) {
        echo "<script src='bower_components/jquery/dist/jquery.min.js'></script>
              <script src='swalert/dist/sweetalert2.min.js'></script>
              <link rel='stylesheet' href='swalert/dist/sweetalert2.min.css'>
              <script>
                $(document).ready(function(){
                    Swal.fire({
                        icon: 'error',
                        title: 'เข้าสอบไม่ได้',
                        text: 'คุณได้ทำการส่งข้อสอบเรียบร้อยแล้ว ไม่สามารถทำข้อสอบซ้ำได้',
                        confirmButtonColor: '#d33'
                    }).then(() => { window.location='index.html'; });
                });
              </script>";
        exit(); // หยุดการทำงานของหน้านี้ทันที
    }
}

// 3. Logic เวลา (คำนวณวินาทีที่เหลือจาก DB)
$res_time = $conn->query("SELECT date_in FROM tb_log_test WHERE id='$ids1' LIMIT 1");
if ($res_time && $row_time = $res_time->fetch_assoc()) {
    // แปลง format d/m/Y,H:i เป็น timestamp
    $start_str = str_replace(',', ' ', $row_time['date_in']);
    $dt = DateTime::createFromFormat('d/m/Y H:i', $start_str);
    $startTime = ($dt) ? $dt->getTimestamp() : strtotime($start_str);
    $elapsed = time() - $startTime; // วินาทีที่ใช้ไปแล้ว
} else {
    $date_now = date('d/m/Y,H:i');
    $conn->query("INSERT INTO tb_log_test (id, date_in, num_test) VALUES ('$ids1', '$date_now', '0')");
    $elapsed = 0;
}
$remain_seconds = 3600 - $elapsed; // เวลาที่เหลือจริง (จาก 60 นาที)
if ($remain_seconds < 0) $remain_seconds = 0;

// 4. ระบบแบ่งหน้า
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$start_idx = ($page - 1) * $limit;

// 5. โหลดข้อสอบ (ต้องมี RAND($seed) ใน rand_test.php)
include("rand_test.php"); 

function getSaved($std_id, $q_id, $choice_no, $conn) {
    $res = $conn->query("SELECT selected_choice FROM tb_user_answers WHERE std_id='$std_id' AND question_id='$q_id'");
    if ($res && $r = $res->fetch_assoc()) return ($r['selected_choice'] == $choice_no) ? "checked" : "";
    return "";
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>SAT Exam - วิทยาลัยเทคนิคสุพรรณบุรี</title>
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="swalert/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap');
        body { background: #f4f7f6; padding-top: 70px; font-family: 'Sarabun', sans-serif; user-select: none; }
        .navbar-custom { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border: none; }
        .exam-card { background: white; border-radius: 15px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 25px; overflow: hidden; }
        .exam-header { background: #fff; padding: 20px; border-bottom: 1px solid #f0f0f0; }
        .choice-container { display: block; margin-bottom: 12px; cursor: pointer; }
        .choice-container input { display: none; }
        .checkmark { 
            display: flex; align-items: center; padding: 18px; background: #fff; 
            border: 2px solid #f0f0f0; border-radius: 12px; transition: 0.2s; 
        }
        .choice-container input:checked + .checkmark { 
            background: #e8f5e9; border-color: #28a745; color: #155724; font-weight: bold;
        }
        .img-q { max-width: 100%; border-radius: 10px; margin: 10px 0; }
        .footer-stc { background: white; padding: 30px; margin-top: 50px; border-top: 1px solid #ddd; }
        .pagination > .active > a { background-color: #28a745 !important; border-color: #28a745 !important; }
    </style>
</head>
<body class="hold-transition layout-top-nav">

<nav class="navbar navbar-fixed-top navbar-custom">
    <div class="container-fluid">
        <div style="display:flex; justify-content: space-between; align-items:center; padding: 8px 15px;">
            <span class="navbar-brand" id="time_tt" style="color:#333; font-weight:bold;"><i class="fa fa-clock-o text-green"></i> 00:00</span>
            <span class="navbar-brand" id="numtest" style="color:#333; font-weight:bold;"><i class="fa fa-pencil text-green"></i> ทำไป 0/50</span>
        </div>
    </div>
</nav>

<div class="container">
    <div class="exam-card" style="padding: 15px; margin-top: 10px;">
        <div class="row">
            <div class="col-xs-3 col-sm-2">
                <img src="images/logo_web.png" class="img-responsive" style="max-height: 50px;">
            </div>
            <div class="col-xs-9 col-sm-10">
                <strong>ผู้สอบ:</strong> <?php echo $sname; ?> | <strong>สาขา:</strong> <?php echo $major; ?><br>
                <div class="progress progress-xxs" style="margin: 5px 0 0 0;">
                    <div id="perc" class="progress-bar progress-bar-success" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>

    <form action="check_ans.php" method="POST" id="form1">
        <input type="hidden" name="std_id" value="<?php echo $ids1; ?>">
        
        <?php for($i=$start_idx; $i < ($start_idx + $limit) && $i < 50; $i++): ?>
        <div class="exam-card">
            <div class="exam-header">
                <span class="label label-success">ข้อที่ <?php echo $i+1; ?></span>
                <?php if($img_q[$i]) echo "<br><img src='ImagesQuestions/{$img_q[$i]}' class='img-q'>"; ?>
                <p style="font-size: 18px; margin-top:10px; font-weight:bold;"><?php echo $question[$i]; ?></p>
            </div>
            <div class="box-body">
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
                                    <?php if($img) echo "<img src='ImagesQuestions/$img' style='max-width:80px; margin-bottom:5px'><br>"; ?>
                                    <?php echo $txt; ?>
                                </div>
                            </span>
                        </label>
                    </div>
                     
                    <?php  
                        endfor; // echo $ans[$i]; ?>
                </div>
            </div>
        </div>
        <?php endfor; ?>

        <div class="text-center" style="margin-bottom: 40px;">
            <ul class="pagination pagination-lg">
                <?php for($p=1; $p<=5; $p++): ?>
                    <li class="<?php if($page==$p) echo 'active'; ?>">
                        <a href="?page=<?php echo $p; ?>&std_id=<?php echo $ids1; ?>"><?php echo $p; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
            <br><br>
            <button type="button" class="btn btn-block btn-success btn-lg" style="border-radius:15px; font-weight:bold; padding:15px;" onclick="finishExam()"><i class="fa fa-send"></i> ส่งคำตอบ (Submit)</button>
        </div>
    </form>
</div>

<footer class="footer-stc text-center">
    <p>พัฒนาโปรแกรมโดย: <strong>นายศุภโชค พานทอง</strong><br>แผนกอิเล็กทรอนิกส์ วิทยาลัยเทคนิคสุพรรณบุรี</p>
</footer>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="swalert/dist/sweetalert2.min.js"></script>

<script>
// --- ล็อกไม่ให้กด F5 หรือ Ctrl+R (แต่ไม่ขึ้น Alert ยืนยัน) ---
$(document).on("keydown", function(e) { 
    if ((e.which || e.keyCode) == 116 || (e.ctrlKey && (e.which || e.keyCode) == 82)) e.preventDefault(); 
});

// --- ลบ window.onbeforeunload ออกไปแล้ว เพื่อให้เปลี่ยนหน้าได้ทันที ---

var remainSeconds = <?php echo (int)$remain_seconds; ?>; 
var timerVar = setInterval(function() {
    remainSeconds--;
    if (remainSeconds <= 0) {
        clearInterval(timerVar);
        // ไม่ต้องมี window.onbeforeunload = null; แล้ว
        Swal.fire('หมดเวลา', 'ระบบกำลังส่งคำตอบอัตโนมัติ', 'warning').then(() => $('#form1').submit());
    }
    var m = Math.floor(remainSeconds / 60);
    var s = remainSeconds % 60;
    $('#time_tt').html("<i class='fa fa-clock-o text-green'></i> เวลา: " + m + ":" + (s < 10 ? "0"+s : s));
    $('#perc').css('width', (100 - (remainSeconds/3600*100)) + '%');
}, 1000);

function updateCount() { 
    $.get('get_count.php', { std_id: '<?php echo $ids1; ?>' }, function(d) { 
        $('#numtest').html("<i class='fa fa-pencil text-green'></i> ทำไป " + d + " / 50"); 
    }); 
}

function saveToDB(sid, qid, v) { 
    $.post('save_answer.php', { std_id: sid, question_id: qid, selected_choice: v }, function() { 
        updateCount(); 
    }); 
}

// เปลี่ยนฟังก์ชัน finishExam เป็นแบบนี้เพื่อตรวจสอบเบื้องต้น
function finishExam() {
    // ลองทดสอบว่าคลิกติดไหมด้วย console.log
    console.log("Finish Exam Clicked");

    $.getJSON('get_unanswered.php', { std_id: '<?php echo $ids1; ?>' })
    .done(function(unanswered) {
        let titleText = 'ยืนยันการส่งข้อสอบ?';
        let subText = "คุณทำไปแล้ว " + (50 - unanswered.length) + " จาก 50 ข้อ";
        let iconType = 'question';
        let confirmColor = '#28a745';

        if (unanswered.length > 0) {
            titleText = 'คุณยังทำไม่ครบ!';
            subText = "ข้อที่ยังไม่ได้ทำคือ: " + unanswered.join(', ');
            iconType = 'warning';
            confirmColor = '#d33';
        }

        Swal.fire({
            title: titleText,
            text: subText,
            icon: iconType,
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            confirmButtonText: 'ตกลง ส่งคำตอบ',
            cancelButtonText: 'กลับไปทำต่อ'
        }).then((res) => {
            if (res.isConfirmed) {
                // ใช้คำสั่งนี้เพื่อส่งฟอร์มโดยตรง
                document.getElementById('form1').submit();
            }
        });
    })
    .fail(function() {
        // กรณีไฟล์ get_unanswered.php มีปัญหา ให้ส่งแบบปกติไปก่อน
        if(confirm("ยืนยันการส่งข้อสอบ (ระบบตรวจสอบข้อว่างขัดข้อง)?")) {
            document.getElementById('form1').submit();
        }
    });
}
</script>
</body>
</html>
<?php $conn->close(); ?>