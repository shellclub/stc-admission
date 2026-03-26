<?php 
 session_start();
 require('../config.inc.php');
 $user = $_REQUEST['user'];
 $pass = $_REQUEST['pass'];
 //echo $ids1;
 mysqli_set_charset($conn,"utf8");
 $sql = "select * from tb_admin where user='".$user."' and password = '".$pass."'"; 
 $result = $conn->query($sql); 
 if ($result->num_rows > 0) {
     $row = $result->fetch_assoc(); 
   echo "<script> window.location= 'home_menu.php' </script>";
     $id_s = $row['id'];
     $_SESSION["id_admin"]= $id_s;
 }
 
else{ 
    echo "<script> alert('User and Password Not Correct !!!'); window.location= 'index.html'; </script>";
     
     
    }  
 
 $conn->close();
?>
 