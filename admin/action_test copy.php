<?php
	header('Content-type: text/html; charset=utf-8');	
	require("../config.inc.php");



	 
      
        foreach($_FILES['userfile']['tmp_name'] as $key => $tmpfile){
         $q_pic = $_FILES['userfile']['name'][0];
         $c1_pic = $_FILES['userfile']['name'][1];
         $c2_pic = $_FILES['userfile']['name'][2];
         $c3_pic = $_FILES['userfile']['name'][3];
         $c4_pic = $_FILES['userfile']['name'][4];
         $filename = $_FILES['userfile']['name'][$key];
       
        if(!is_uploaded_file($_FILES['userfile']['name']['key'])){
         if($_FILES['userfile']['size'][$key]>60000){
            echo "* ขนาดไฟล์รูปภาพไม่ควรเกิน 60 k ";
            $status=0;
         }
          else {$dest = "../ImagesQuestions/".$filename;
          if(move_uploaded_file($tmpfile,$dest)){
            $status=1;
          }
         }
        }
       } 
       $q_t = $_REQUEST['q_t'];
       $c1= $_REQUEST['c1'];
       $c2= $_REQUEST['c2'];
       $c3= $_REQUEST['c3'];
       $c4= $_REQUEST['c4'];
       $answer = $_REQUEST['answer']; 
       $idgrouptest = $_REQUEST['idgrouptest'];
       $subject = $_REQUEST['subject'];

              $sql="insert into tb_test values(' ','$q_pic', '$q_t','$c1_pic','$c1','$c2_pic','$c2','$c3_pic','$c3','$c4_pic','$c4','$answer','$idgrouptest','$subject') ";
              mysqli_set_charset($conn,"utf8");
              //echo $sql;  
              
              if ($conn->query($sql) === TRUE) {
                    echo "<script> alert('เพิ่มข้อมูลเรียบร้อยแล้ว'); </script>";
                    echo '<script type="text/javascript">
                    window.location = "home_menu.php?page=add_test"
                    </script>';
                }
               
              // echo  "อัพโหลดข้อมูลเรียบร้อย";
        
    
?>