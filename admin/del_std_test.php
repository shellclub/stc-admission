<?php
require('../config.inc.php');
$id_std = $_REQUEST['id'];
$sql = "delete  from tb_log_test  WHERE id=".$id_std."";
if ($conn->query($sql) === TRUE) {
    //echo "Record deleted successfully";
}  
$sql = "delete  from  tb_user  WHERE id=".$id_std."";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert(' delete  id = [$id_std ] >> ok   ');  window.location= 'home_menu.php?page=show_del_std_test' </script>";
} else {
    //echo "Error deleting record: " . $conn->error;
}


$conn->close();

?>
