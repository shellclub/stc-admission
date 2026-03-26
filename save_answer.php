<?php
require('config.inc.php');
if(isset($_POST['std_id']) && isset($_POST['question_id'])){
    $std_id = mysqli_real_escape_string($conn, $_POST['std_id']);
    $q_id = mysqli_real_escape_string($conn, $_POST['question_id']);
    $choice = mysqli_real_escape_string($conn, $_POST['selected_choice']);

    // ใช้ REPLACE INTO เพื่อให้ Update ค่าเดิมถ้ามีอยู่แล้ว หรือ Insert ถ้ายังไม่มี
    $sql = "REPLACE INTO tb_user_answers (std_id, question_id, selected_choice) 
            VALUES ('$std_id', '$q_id', '$choice')";
    if($conn->query($sql)){
        echo "success";
    }
}
?>