<?php
require('../config.inc.php');
mysqli_set_charset($conn, "utf8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าและป้องกัน SQL Injection
    $idcard = mysqli_real_escape_string($conn, $_POST['idcard']);
    $id     = mysqli_real_escape_string($conn, $_POST['id']);
    $name   = mysqli_real_escape_string($conn, $_POST['name']);
    $de_id  = mysqli_real_escape_string($conn, $_POST['de_id']);
    $major  = mysqli_real_escape_string($conn, $_POST['major']);
    $level  = (int)$_POST['level'];

    echo '<html><head><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script></head><body>';

    // 1. ตรวจสอบว่า major มีค่ามาจริงหรือไม่
    if (empty($major) || $major == "-- กรุณาเลือกสาขา --") {
        echo "<script>
            Swal.fire('Error!', 'ไม่พบชื่อสาขาวิชา กรุณาลองใหม่อีกครั้ง', 'error')
            .then(() => { window.history.back(); });
        </script>";
        exit;
    }

    // 2. ตรวจสอบข้อมูลซ้ำ (ID หรือ เลขบัตร)
    $check_sql = "SELECT id FROM tb_user_new WHERE id = '$id' OR idcard = '$idcard' LIMIT 1";
    $check_res = $conn->query($check_sql);

    if ($check_res->num_rows > 0) {
        echo "<script>
            Swal.fire('ข้อมูลซ้ำ!', 'รหัสผู้สอบหรือเลขบัตรประชาชนนี้มีอยู่ในระบบแล้ว', 'warning')
            .then(() => { window.history.back(); });
        </script>";
    } else {
        // 3. บันทึกข้อมูลลง tb_user_new
        $sql = "INSERT INTO tb_user_new (idcard, id, name, major, de_id, level, score) 
                VALUES ('$idcard', '$id', '$name', '$major', '$de_id', '$level', '0')";
        
        if ($conn->query($sql)) {
            echo "<script>
                Swal.fire('สำเร็จ!', 'บันทึกข้อมูลคุณ $name เรียบร้อยแล้ว', 'success')
                .then(() => { window.location.href='home_menu.php?page=show_data_std_all'; });
            </script>";
        } else {
            echo "Database Error: " . $conn->error;
        }
    }
    echo '</body></html>';
}
?>