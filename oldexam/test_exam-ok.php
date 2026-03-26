<?php 
session_start();
date_default_timezone_set('Asia/Bangkok');
require('config.inc.php');
mysqli_set_charset($conn, "utf8");

// 1. ตรวจสอบรหัสผู้สอบ (ดึงจาก URL หรือ Session)
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

// 3. จัดการเวลาเริ่มสอบ (ดึงจาก DB เพื่อให้ Refresh แล้วนับต่อ)
$sql_time = "SELECT date_in FROM tb_log_test WHERE id='$ids1' LIMIT 1";
$res_time = $conn->query($sql_time);
if ($res_time && $row_time = $res_time->fetch_assoc()) {
    $start_str = str_replace(',', ' ', $row_time['date_in']);
    $dateTimeObj = DateTime::createFromFormat('d/m/Y H:i', $start_str);
    $startTime = ($dateTimeObj) ? $dateTimeObj->getTimestamp() : strtotime($start_str);
    $elapsedSeconds = time() - $startTime;
} else {
    $date_now = date('d/m/Y,H:i');
    $conn->query("INSERT INTO tb_log_test (id, date_in, num_test, score) VALUES ('$ids1', '$date_now', '0', '0')");
    $elapsedSeconds = 0;
}
$elapsedSeconds = ($elapsedSeconds < 0) ? 0 : $elapsedSeconds;

// 4. ระบบแบ่งหน้า (Pagination)
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_idx = ($page - 1) * $limit;

// 5. โหลดข้อสอบ (include rand_test.php ที่ใช้ RAND($seed) แล้ว)
include("rand_test.php"); 

// ฟังก์ชันดึงคำตอบเดิมมาติ๊กให้
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
    <title>ระบบทดสอบออนไลน์ - STC</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="swalert/dist/sweetalert2.min.css">
    <style>
        body { background: #f4f7f6; padding-top: 60px; user-select: none; -webkit-user-select: none; }
        .navbar-fixed-top { box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .choice-container { display: block; margin-bottom: 12px; cursor: pointer; }
        .choice-container input { display: none; }
        .checkmark { 
            display: flex; align-items: center; padding: 15px; background: #fff; 
            border: 2px solid #dee2e6; border-radius: 12px; transition: 0.2s; 
        }
        .choice-container input:checked + .checkmark { 
            background: #e8f5e9; border-color: #28a745; color: #155724; font-weight: bold;
        }
        .img-q { max-width: 100%; height: auto; border-radius: 8px; margin: 10px 0; }
        .pagination > .active > a { background-color: #28a745 !important; border-color: #28a745 !important; }
    </style>
</head>
<body class="hold-transition skin-green layout-top-nav">

<nav class="navbar navbar-fixed-top bg-green">
    <div class="container-fluid">
        <div class="navbar-header" style="display:flex; justify-content: space-between; width: 100%; padding: 0 15px;">
            <span class="navbar-brand" id="time_tt" style="color:white; font-size: 16px;">เวลา: 60:00</span>
            <span class="navbar-brand" id="numtest" style="color:white; font-size: 16px;">ทำไป 0/60 ข้อ</span>
        </div>
    </div>
</nav>

<div class="container">
    <div class="box box-solid" style="margin-top: 15px; border-radius: 10px;">
        <div class="box-body">
            <strong>ชื่อ-นามสกุล:</strong> <?php echo $sname; ?> | <strong>สาขา:</strong> <?php echo $major; ?>
            <div class="progress progress-xxs" style="margin-top:10px;">
                <div id="perc" class="progress-bar progress-bar-warning" style="width: 0%"></div>
            </div>
        </div>
    </div>

    <form action="check_ans.php" method="POST" id="form1">
        <input type="hidden" name="std_id" value="<?php echo $ids1; ?>">
        
        <?php for($i=$start_idx; $i < ($start_idx + $limit) && $i < 60; $i++): ?>
        <div class="box box-widget" style="border-radius: 10px;">
            <div class="box-header with-border">
                <h4 class="text-green">ข้อที่ <?php echo $i+1; ?></h4>
                <?php if($img_q[$i]) echo "<img src='ImagesQuestions/{$img_q[$i]}' class='img-q'>"; ?>
                <p style="font-size: 18px;"><?php echo $question[$i]; ?></p>
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
                                    <?php if($img) echo "<img src='ImagesQuestions/$img' style='max-width:80px'><br>"; ?>
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

        <div class="text-center" style="margin-bottom: 60px;">
            <ul class="pagination pagination-lg">
                <?php for($p=1; $p<=6; $p++): ?>
                    <li class="<?php if($page==$p) echo 'active'; ?>">
                        <a href="?page=<?php echo $p; ?>&std_id=<?php echo $ids1; ?>"><?php echo $p; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
            <br><br>
            <button type="button" class="btn btn-block btn-success btn-lg" style="border-radius:10px" onclick="finishExam()">ส่งคำตอบทั้งหมด (Submit)</button>
        </div>
    </form>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="swalert/dist/sweetalert2.min.js"></script>

<script>
// --- บล็อกการ Refresh ---
$(document).on("keydown", function(e) {
    if ((e.which || e.keyCode) == 116 || (e.ctrlKey && (e.which || e.keyCode) == 82)) e.preventDefault();
});
window.onbeforeunload = function() { return "ระบบกำลังทำงาน คุณต้องการออกจากหน้าสอบใช่หรือไม่?"; };

// --- นาฬิกาเดินต่อ ---
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
    $('#time_tt').text("เวลา: " + m + ":" + (s < 10 ? "0"+s : s));
    $('#perc').css('width', (totalSeconds/3600*100) + '%');
}, 1000);

// --- ระบบบันทึกและนับข้อ ---
function updateCount() {
    $.get('get_count.php', { std_id: '<?php echo $ids1; ?>' }, function(data) {
        $('#numtest').text("ทำไป " + data + " / 60 ข้อ");
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
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'ตกลง ส่งคำตอบ'
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