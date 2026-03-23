<?php
/**
 * File: import_user_process.php
 * ประมวลผลนำเข้าข้อมูลลงตาราง tb_user_new
 */
require('../config.inc.php');

if(isset($_POST['submit_import'])) {
    $file_tmp = $_FILES['file_import']['tmp_name'];
    
    if ($_FILES['file_import']['size'] > 0) {
        
        $file = fopen($file_tmp, "r");
        
        // ข้ามบรรทัดแรก (Header)
        fgetcsv($file, 10000, ",");
        
        $count_success = 0;
        $count_skip = 0;

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            
            // ป้องกันข้อมูลว่างหรือบรรทัดเปล่า
            if(empty($column[0])) continue;

            // Mapping ข้อมูลตามตาราง tb_user_new
            $idcard = mysqli_real_escape_string($conn, $column[0]);
            $id     = mysqli_real_escape_string($conn, $column[1]);
            $name   = mysqli_real_escape_string($conn, $column[2]);
            $major  = mysqli_real_escape_string($conn, $column[3]);
            $de_id  = mysqli_real_escape_string($conn, $column[4]);
            $level  = (int)$column[5];
            $score  = (int)$column[6];

            // ตรวจสอบว่ามี idcard นี้อยู่ในระบบหรือยัง (เพื่อไม่ให้ Insert ซ้ำ)
            $check = $conn->query("SELECT idcard FROM tb_user_new WHERE idcard = '$idcard'");
            
            if($check->num_rows == 0) {
                // คำสั่ง Insert
                $sql = "INSERT INTO tb_user_new (idcard, id, name, major, de_id, level, score) 
                        VALUES ('$idcard', '$id', '$name', '$major', '$de_id', '$level', '$score')";
                
                if($conn->query($sql)) {
                    $count_success++;
                }
            } else {
                $count_skip++;
            }
        }
        fclose($file);

        echo "<script>
                alert('นำเข้าสำเร็จ: $count_success รายการ \\nข้ามรายการที่ซ้ำ: $count_skip รายการ');
                window.location.href='home_menu.php?page=show_data_std_all';
              </script>";
    } else {
        echo "<script>alert('ไฟล์ว่างเปล่า'); window.history.back();</script>";
    }
}
?>