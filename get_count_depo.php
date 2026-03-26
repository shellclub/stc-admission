<?php
session_start();
require('config.inc.php');
$std_id = $_REQUEST['std_id'];

// นับจำนวนแถวที่มีการบันทึกคำตอบของเด็กคนนี้
// แต่ต้องนับเฉพาะข้อที่อยู่ในชุด depo (ถ้ามีการสอบหลายแบบผสมกัน)
include("rand_test_depo.php");
$ids_list = implode("','", $id_s);

$sql = "SELECT count(*) as total FROM tb_user_answers 
        WHERE std_id = '$std_id' 
        AND question_id IN ('$ids_list')";

$res = $conn->query($sql);
$row = $res->fetch_assoc();

echo $row['total'];
$conn->close();
?>