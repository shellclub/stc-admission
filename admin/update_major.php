<?php
require('../config.inc.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $de_id = $_POST['de_id'];
    $de_type = $_POST['de_type'];
    $de_name = $_POST['de_name'];

    // ป้องกัน SQL Injection
    $de_type = mysqli_real_escape_string($conn, $de_type);
    $de_name = mysqli_real_escape_string($conn, $de_name);

    $sql = "UPDATE tb_depart SET 
            de_type = '$de_type', 
            de_name = '$de_name' 
            WHERE de_id = '$de_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('แก้ไขข้อมูลเรียบร้อยแล้ว');
                window.location.href='home_menu.php?menu=major'; 
              </script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>