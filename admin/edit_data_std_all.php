<?php 
  require('../config.inc.php');
  mysqli_set_charset($conn,"utf8");

  $id = $_REQUEST['id']; 
  $std_id = $_REQUEST['std_id'];
  $std_fname = $_REQUEST['std_fname'];
  $std_lname = $_REQUEST['std_lname'];

  //echo $id." ".$std_id." ".$std_fname." ".$std_lname;

  
  $sql = "update tb_std set std_id = '".$std_id."' , std_fname = '".$std_fname."', std_lname = '".$std_lname."'  where id ='".$id."'";
  $result = $conn->query($sql);

  if($result )  {
        echo "<script> alert('แก้ไขข้อมูลเรียบร้อย'); </script>";
        echo '<script type="text/javascript">
                window.location = "home_menu.php?page=show_data_std_all"
            </script>'; 
  }
?>