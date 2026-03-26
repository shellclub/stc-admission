<?php 
  require('../config.inc.php');
  mysqli_set_charset($conn,"utf8");

  $id = $_REQUEST['id']; 
  $std_id = $_REQUEST['std_id'];
  $std_level = $_REQUEST['std_level'];
   
  //echo $id." ".$std_id." ".$std_fname." ".$std_lname;

  
  $sql = "update tb_std set std_id = '".$std_id."' , std_level = '".$std_level."'  where id ='".$id."'";
  $result = $conn->query($sql);

  if($result )  {
        echo "<script> alert('แก้ไขข้อมูลเรียบร้อย'); </script>";
        echo '<script type="text/javascript">
                window.location = "home_menu.php?page=show_data_std1"
            </script>'; 
  }
?>