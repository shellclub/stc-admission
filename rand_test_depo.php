<?php
require('config.inc.php');
mysqli_set_charset($conn, "utf8");

// 1. จัดการตัวแปร std_id (รองรับทั้งการเรียกตรงและเรียกผ่าน include)
$std_id_raw = isset($ids1) ? $ids1 : (isset($_REQUEST['std_id']) ? $_REQUEST['std_id'] : '');

// 2. แยกส่วนรหัสเพื่อหา "กลุ่มวิชา" (Subject) ตาม Logic เดิมของคุณ
$nid = substr($std_id_raw, 0, 1); 
if($nid == '3') {
    $subject_code = substr($std_id_raw, 0, 5);
} elseif($nid == '4') {
    $subject_code = substr($std_id_raw, 0, 4);
} else {
    $subject_code = $std_id_raw; // กรณีอื่นๆ
}

// 3. สร้าง Seed สำหรับการสุ่มให้คงที่ (ใช้ตัวเลขจากรหัสผู้สอบ)
// ทำให้เวลา Refresh หรือเปลี่ยนหน้า ข้อสอบจะยังเรียงลำดับเดิม
$seed = (int)preg_replace('/[^0-9]/', '', $std_id_raw); 

$numtest = 0;
$id_s = array();
$img_q = array();
$question = array();
$ans = array();

// 4. ดึงข้อมูลข้อสอบ 40 ข้อ
$sql = "SELECT * FROM tb_test 
        WHERE subject = '$subject_code' 
        ORDER BY RAND($seed) 
        LIMIT 40";

$result = $conn->query($sql); 

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $id_s[$numtest] = $row['id'];
        $img_q[$numtest] = $row['q_pic'];
        $question[$numtest] = $row['q_t'];
        
        $choice1_img[$numtest] = $row['c1_pic'];
        $choice1[$numtest] = $row['c1'];
        
        $choice2_img[$numtest] = $row['c2_pic'];
        $choice2[$numtest] = $row['c2'];
        
        $choice3_img[$numtest] = $row['c3_pic'];
        $choice3[$numtest] = $row['c3'];
        
        $choice4_img[$numtest] = $row['c4_pic'];
        $choice4[$numtest] = $row['c4'];
        
        $ans[$numtest] = $row['answer'];
        $numtest++;
    }
}

// หมายเหตุ: ปิดการเชื่อมต่อในหน้าหลักหลังจากดึงข้อมูลครบแล้ว
// $conn->close(); 
?>