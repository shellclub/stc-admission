<?php
session_start();
require('config.inc.php');
$std_id = $_REQUEST['std_id'];

// 1. โหลดชุดข้อสอบที่เด็กคนนี้ได้รับ (ต้องใช้ไฟล์เดียวกับหน้าสอบ)
include("rand_test_depo.php"); 
// เราจะได้ $id_s ซึ่งเป็น Array ของ ID ข้อสอบ 40 ข้อ

// 2. ดึงคำตอบที่บันทึกไว้แล้ว
$sql = "SELECT question_id FROM tb_user_answers WHERE std_id = '$std_id'";
$res = $conn->query($sql);
$answered = [];
while($row = $res->fetch_assoc()){
    $answered[] = $row['question_id'];
}

// 3. ตรวจสอบว่าใน 40 ข้อนี้ ข้อไหนไม่มีใน $answered
$unanswered_nodes = [];
for($i=0; $i<40; $i++) {
    if(!in_array($id_s[$i], $answered)) {
        $unanswered_nodes[] = ($i + 1); // เก็บเป็นเลขข้อที่ 1, 2, 3...
    }
}

echo json_encode($unanswered_nodes);
$conn->close();
?>