<?php
require("../config.inc.php");
mysqli_set_charset($conn, "utf8");

// รับค่า Action จาก URL (addnew หรือ update)
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == "addnew") {
    // 1. รับข้อมูลจาก Form
    $idgrouptest = mysqli_real_escape_string($conn, $_POST['idgrouptest']);
    $subject     = mysqli_real_escape_string($conn, $_POST['subject']);
    $q_t         = mysqli_real_escape_string($conn, $_POST['q_t']);
    $c1          = mysqli_real_escape_string($conn, $_POST['c1']);
    $c2          = mysqli_real_escape_string($conn, $_POST['c2']);
    $c3          = mysqli_real_escape_string($conn, $_POST['c3']);
    $c4          = mysqli_real_escape_string($conn, $_POST['c4']);
    $answer      = mysqli_real_escape_string($conn, $_POST['answer']);

    // 2. จัดการเรื่องรูปภาพ (Array userfile[0-4])
    $pic_names = array("", "", "", "", ""); // เตรียมตัวแปรเก็บชื่อไฟล์ 5 รูป
    $upload_path = "../img_test/"; // พาธที่เก็บรูป (ต้องสร้าง Folder นี้ไว้และ CHMOD 777)

    for ($i = 0; $i <= 4; $i++) {
        if (!empty($_FILES['userfile']['name'][$i])) {
            $temp_file = $_FILES['userfile']['tmp_name'][$i];
            $extension = pathinfo($_FILES['userfile']['name'][$i], PATHINFO_EXTENSION);
            
            // ตั้งชื่อไฟล์ใหม่: ประเภท_เวลา_สุ่มเลข.นามสกุล (เช่น Q_1710945600_1234.png)
            $type_prefix = ($i == 0) ? "Q" : "C".$i;
            $new_filename = $type_prefix . "_" . time() . "_" . rand(1000, 9999) . "." . $extension;
            
            if (move_uploaded_file($temp_file, $upload_path . $new_filename)) {
                $pic_names[$i] = $new_filename;
            }
        }
    }

    // 3. คำสั่ง SQL Insert
    $sql = "INSERT INTO tb_test (
                q_pic, q_t, 
                c1_pic, c1, 
                c2_pic, c2, 
                c3_pic, c3, 
                c4_pic, c4, 
                answer, idgroup, subject
            ) VALUES (
                '".$pic_names[0]."', '$q_t', 
                '".$pic_names[1]."', '$c1', 
                '".$pic_names[2]."', '$c2', 
                '".$pic_names[3]."', '$c3', 
                '".$pic_names[4]."', '$c4', 
                '$answer', '$idgrouptest', '$subject'
            )";

    // 4. แสดงผลลัพธ์ด้วย SweetAlert2
    echo '<html><head><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script></head><body>';
    
    if ($conn->query($sql)) {
        echo "<script>
            Swal.fire({
                title: 'บันทึกสำเร็จ!',
                text: 'เพิ่มข้อสอบใหม่เข้าสู่ระบบเรียบร้อยแล้ว',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            }).then((result) => {
                window.location.href = 'home_menu.php?page=add_test'; 
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire('เกิดข้อผิดพลาด!', '" . mysqli_error($conn) . "', 'error')
            .then(() => { window.history.back(); });
        </script>";
    }
    echo '</body></html>';
}
?>