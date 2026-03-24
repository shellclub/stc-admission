<?php 
session_start();
date_default_timezone_set('Asia/Bangkok');
require('config.inc.php');
mysqli_set_charset($conn, "utf8");

// 1. รับค่ารหัสผู้สอบ
if (isset($_REQUEST['std_id'])) {
    $ids1 = mysqli_real_escape_string($conn, $_REQUEST['std_id']);
    $_SESSION['std_id'] = $ids1;
} elseif (isset($_SESSION['std_id'])) {
    $ids1 = $_SESSION['std_id'];
} else {
    header("Location: index.html"); exit();
}

// 2. ดึงข้อมูลนักเรียนและเช็คคะแนนซ้ำ
$res_user = $conn->query("SELECT * FROM tb_user_new WHERE id='$ids1' LIMIT 1");
if ($row = $res_user->fetch_assoc()) {
    $sname = $row['name'];
    $major = $row['major'];
    if ($row['score'] !== null && $row['score'] > 0) {
        echo "<script>alert('คุณได้ทำข้อสอบเรียบร้อยแล้ว'); window.location='index.html';</script>";
        exit();
    }
}

// 3. จัดการเวลาถอยหลัง
$res_time = $conn->query("SELECT date_in FROM tb_log_test WHERE id='$ids1' LIMIT 1");
if ($res_time && $row_time = $res_time->fetch_assoc()) {
    $start_str = str_replace(',', ' ', $row_time['date_in']);
    $dt = DateTime::createFromFormat('d/m/Y H:i', $start_str);
    $startTime = ($dt) ? $dt->getTimestamp() : strtotime($start_str);
    $elapsed = time() - $startTime;
} else {
    $date_now = date('d/m/Y,H:i');
    $conn->query("INSERT INTO tb_log_test (id, date_in,dete_log,num_test) VALUES ('$ids1', '$date_now','','0')");
    $elapsed = 0;
}
$remain_seconds = 3600 - $elapsed;
if ($remain_seconds < 0) $remain_seconds = 0;

// 4. ระบบแบ่งหน้า (Pagination)
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > 4) $page = 4;
$start_idx = ($page - 1) * $limit;

// 5. โหลดข้อสอบ 40 ข้อ (เรียก rand_test_depo.php)
include("rand_test_depo.php"); 

// ฟังก์ชันตรวจสอบคำตอบเดิมที่บันทึกไว้ใน DB
function getSaved($std_id, $q_id, $choice_no, $conn) {
    // ต้องมั่นใจว่าตาราง tb_user_answers มี column ชื่อ std_id, question_id, selected_choice
    $stmt = $conn->prepare("SELECT selected_choice FROM tb_user_answers WHERE std_id=? AND question_id=?");
    $stmt->bind_param("ss", $std_id, $q_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $r = $res->fetch_assoc()) {
        return ($r['selected_choice'] == $choice_no) ? "checked" : "";
    }
    return "";
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>SAT Exam (40 ข้อ) - STC</title>
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="swalert/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap');
        body { background: #f4f7f6; padding-top: 70px; font-family: 'Sarabun', sans-serif; user-select: none; }
        .navbar-custom { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border: none; }
        .exam-card { background: white; border-radius: 15px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .choice-container { display: block; margin-bottom: 12px; cursor: pointer; }
        .choice-container input { display: none; }
        .checkmark { 
            display: flex; align-items: center; padding: 18px; background: #fff; 
            border: 2px solid #f0f0f0; border-radius: 12px; transition: 0.2s; 
        }
        .choice-container input:checked + .checkmark { 
            background: #e8f5e9; border-color: #28a745; color: #155724; font-weight: bold;
        }
        .img-q { max-width: 100%; border-radius: 10px; margin: 10px 0; border: 1px solid #eee; }
        .pagination > .active > a { background-color: #28a745 !important; border-color: #28a745 !important; }
    </style>
</head>
<body class="hold-transition layout-top-nav">

<nav class="navbar navbar-fixed-top navbar-custom">
    <div class="container-fluid">
        <div style="display:flex; justify-content: space-between; align-items:center; padding: 8px 15px;">
            <span class="navbar-brand" id="time_tt" style="color:#333; font-weight:bold;"><i class="fa fa-clock-o text-green"></i> 00:00</span>
            <span class="navbar-brand" id="numtest" style="color:#333; font-weight:bold;"><i class="fa fa-pencil text-green"></i> 0/40</span>
        </div>
    </div>
</nav>

<div class="container">
    <div class="exam-card" style="padding: 15px; margin-top: 10px;">
        <div class="row">
             <div class="col-xs-2">
                <img src="images/logo_web.png" class="img-responsive" style="max-height: 40px;">
            </div>
            <div class="col-xs-8">
                <strong>ผู้สอบ:</strong> <?php echo $sname; ?> | <strong>สาขา:</strong> <?php echo $major; ?>
            </div>
            <div class="col-xs-2 text-right">
                <strong>หน้า <?php echo $page; ?>/4</strong>
            </div>
        </div>
    </div>

    <form action="check_ans.php" method="POST" id="form1">
        <input type="hidden" name="std_id" value="<?php echo $ids1; ?>">
        
        <?php for($i=$start_idx; $i < ($start_idx + $limit) && $i < 40; $i++): ?>
        <div class="exam-card">
            <div class="box-header with-border" style="padding: 20px;">
                <span class="label label-success">ข้อที่ <?php echo $i+1; ?></span>
                <?php if($img_q[$i]) echo "<br><img src='ImagesQuestions/{$img_q[$i]}' class='img-q'>"; ?>
                <p style="font-size: 18px; margin-top:10px; font-weight:bold; color:#2c3e50;"><?php echo $question[$i]; ?></p>
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
                                    <?php if($img) echo "<img src='ImagesQuestions/$img' style='max-width:80px; margin-bottom:5px'><br>"; ?>
                                    <?php echo $txt; ?>
                                </div>
                            </span>
                        </label>
                    </div>
                    <?php endfor; //echo $ans[$i]; ?>
                </div>
            </div>
        </div>
        <?php endfor; ?>

        <div class="text-center" style="margin-bottom: 50px;">
            <ul class="pagination pagination-lg">
                <?php for($p=1; $p<=4; $p++): ?>
                    <li class="<?php if($page==$p) echo 'active'; ?>">
                        <a href="?page=<?php echo $p; ?>&std_id=<?php echo $ids1; ?>"><?php echo $p; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
            <br><br>
            <?php if($page == 4): ?>
            <button type="button" class="btn btn-block btn-success btn-lg" style="border-radius:15px; font-weight:bold; padding:20px;" onclick="finishExam()">
                <i class="fa fa-send"></i> ส่งคำตอบทั้งหมด (Submit)
            </button>
            <?php endif; ?>
        </div>
    </form>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="swalert/dist/sweetalert2.min.js"></script>

<script>
$(document).on("keydown", function(e) { if ((e.which || e.keyCode) == 116 || (e.ctrlKey && (e.which || e.keyCode) == 82)) e.preventDefault(); });

var remainSeconds = <?php echo (int)$remain_seconds; ?>; 
var timerVar = setInterval(function() {
    remainSeconds--;
    if (remainSeconds <= 0) {
        clearInterval(timerVar);
        window.onbeforeunload = null;
        Swal.fire('หมดเวลา', 'ระบบกำลังส่งคำตอบอัตโนมัติ', 'warning').then(() => $('#form1').submit());
    }
    var m = Math.floor(remainSeconds / 60);
    var s = remainSeconds % 60;
    $('#time_tt').html("<i class='fa fa-clock-o text-green'></i> เวลา: " + m + ":" + (s < 10 ? "0"+s : s));
}, 1000);

function updateCount() { 
    $.get('get_count_depo.php', { std_id: '<?php echo $ids1; ?>' }, function(d) { 
        $('#numtest').html("<i class='fa fa-pencil text-green'></i> ทำไป " + d + " / 40"); 
    }); 
}

function saveToDB(sid, qid, v) { 
    $.post('save_answer.php', { std_id: sid, question_id: qid, selected_choice: v }, function() { 
        updateCount(); 
    }); 
}

function finishExam() {
    $.getJSON('get_unanswered_depo.php', { std_id: '<?php echo $ids1; ?>' }, function(unanswered) {
        let titleText = 'ยืนยันการส่งข้อสอบ?';
        let subText = "คุณทำไปแล้ว " + (40 - unanswered.length) + " จาก 40 ข้อ";
        if (unanswered.length > 0) {
            titleText = 'คุณยังทำไม่ครบ!';
            subText = "ข้อที่ยังไม่ได้ทำคือ: " + unanswered.join(', ');
        }
        Swal.fire({
            title: titleText,
            text: subText,
            icon: unanswered.length > 0 ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'ตกลง ส่งคำตอบ',
            cancelButtonText: 'กลับไปทำต่อ'
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