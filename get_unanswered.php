<?php
session_start();
require('config.inc.php');
mysqli_set_charset($conn, "utf8");

// ดึงรหัสนักเรียนจาก Session หรือ GET
$std_id = isset($_SESSION['std_id']) ? $_SESSION['std_id'] : (isset($_GET['std_id']) ? $_GET['std_id'] : '');

if (empty($std_id)) {
    echo json_encode([]); 
    exit();
}

// สร้างตัวแปร $ids1 เพื่อให้ rand_test.php นำไปใช้สร้าง Seed สุ่มชุดเดิม
$ids1 = $std_id; 
include("rand_test.php"); 

// ดึง ID ข้อสอบที่ทำไปแล้วจาก Database
$sql = "SELECT question_id FROM tb_user_answers WHERE std_id = '$std_id'";
$res = $conn->query($sql);
$answered_ids = [];
while($row = $res->fetch_assoc()){
    $answered_ids[] = $row['question_id'];
}

// เช็คว่าใน 60 ข้อที่สุ่มมา มีข้อไหนที่ ID ไม่อยู่ในรายการที่ทำแล้ว
$unanswered = [];
for($i=0; $i<50; $i++) {
    // $id_s คือตัวแปร array จากไฟล์ rand_test.php
    if(!in_array($id_s[$i], $answered_ids)) {
        $unanswered[] = ($i + 1); 
    }
}

header('Content-Type: application/json');
echo json_encode($unanswered);
?>