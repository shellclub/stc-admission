<?php 
 require('../config.inc.php');
 $id_s = $_SESSION["id_admin"];
 mysqli_set_charset($conn,"utf8");

 $sql = "select * from tb_admin where id = '".$id_s."'"; 
 $result = $conn->query($sql); 
 if ($result->num_rows > 0) {
     $row = $result->fetch_assoc(); 
     $user = $row['user'];
 }
else{ 
    echo "<script> alert('   Login Error !!!'); window.location= 'index.html'; </script>";     
}  
 
 $conn->close();

?>
<li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../images/logo_web.png" class="user-image" alt="User Image">
               <span class="hidden-xs">Admin </span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../images/logo_web.png" class="img-circle" alt="User Image">

                <p>
                 <?php echo $user;  ?>
                  <small></small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                 
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>