<?php
$tsubject = array("ความถนัดทางการเรียน(SAT)", "วิทยาศาสตร์พื้นฐาน", "คณิตศาสตร์พื้นฐาน", "ความถนัดเชิงช่าง", "อังกฤษพื้นฐาน", "อนุกรมเลขคณิต", "ภาษาไทย");
$anum = array(10, 10, 5, 5, 5, 5, 10);
$numtest = 1;
$data = array(); 

require('config.inc.php');
mysqli_set_charset($conn, "utf8");

// --- ส่วนสำคัญ: สร้าง Seed จากรหัสประจำตัวนักเรียน ---
// แปลงรหัส $ids1 ให้เป็นตัวเลข เพื่อใช้เป็นค่าเริ่มต้นในการสุ่มที่คงที่สำหรับคนๆ นั้น
$seed = (int)preg_replace('/[^0-9]/', '', $ids1); 

for ($i = 0; $i < count($tsubject); $i++) {
    // ใช้ RAND($seed) เพื่อให้การสุ่มในหน้าถัดไป หรือตอน Refresh ได้ผลลัพธ์ลำดับเดิมเสมอ
    $sql = "SELECT * FROM tb_test WHERE subject = '$tsubject[$i]' ORDER BY RAND($seed) LIMIT $anum[$i]";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $id_s[$numtest - 1] = $row['id'];
            $img_q[$numtest - 1] = $row['q_pic'];
            $question[$numtest - 1] = $row['q_t'];
            $choice1_img[$numtest - 1] = $row['c1_pic'];
            $choice1[$numtest - 1] = $row['c1'];
            $choice2_img[$numtest - 1] = $row['c2_pic'];
            $choice2[$numtest - 1] = $row['c2'];
            $choice3_img[$numtest - 1] = $row['c3_pic'];
            $choice3[$numtest - 1] = $row['c3'];
            $choice4_img[$numtest - 1] = $row['c4_pic'];
            $choice4[$numtest - 1] = $row['c4'];
            $ans[$numtest - 1] = $row['answer'];
            
            // เก็บข้อมูลลง Array data (ถ้ายังจำเป็นต้องใช้ JSON)
            $data[] = array(
                'id' => $row['id'],
                'q_pic' => $row['q_pic'],
                'q_t' => $row['q_t'],
                'choices' => array(
                    'choice1_img' => $row['c1_pic'],
                    'choice1' => $row['c1'],
                    'choice2_img' => $row['c2_pic'],
                    'choice2' => $row['c2'],
                    'choice3_img' => $row['c3_pic'],
                    'choice3' => $row['c3'],
                    'choice4_img' => $row['c4_pic'],
                    'choice4' => $row['c4']
                ),
                'answer' => $row['answer']
            );
            $numtest++;
        }
    }
}
$datas = json_encode($data);

// หมายเหตุ: ห้ามใส่ $conn->close() ในนี้เด็ดขาด เพราะหน้าหลักยังต้องใช้ connection ต่อ
?>