<?php
require('../config.inc.php');

if(isset($_POST['de_id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['de_id']);
    $type = mysqli_real_escape_string($conn, $_POST['de_type']);
    $name = mysqli_real_escape_string($conn, $_POST['de_name']);

    $sql = "UPDATE tb_depart SET de_type = '$type', de_name = '$name' WHERE de_id = '$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo $conn->error;
    }
}
?>