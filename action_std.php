<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="swalert/dist/sweetalert2.min.js"> </script>
    <link rel="stylesheet" href="swalert/dist/sweetalert2.min.css">
</head>
<body>

<?php
	date_default_timezone_set('Asia/Bangkok');
	header('Content-type: text/html; charset=utf-8');	
	require("config.inc.php");
	
//echo $_REQUEST['action']; 	
if(isset($_REQUEST['action'])) {//อัพเดทข้อมูล
		
switch($_REQUEST['action']) {
	 
case 'update':	
		$ids = $_REQUEST['stdid1'].$_REQUEST['stdid2'];
		$sql="UPDATE tb_std SET std_id='".$ids."',std_fname='".$_REQUEST['std_fname']."',std_lname='".$_REQUEST['std_lname']."',std_sex='".$_REQUEST['std_sex']."',std_s1='".$_REQUEST['std_s1']."' ,std_gpa='".$_REQUEST['std_gpa']."',std_tel='".$_REQUEST['std_tel']."',insert_date='".date("Y-m-d H:i:s")."'    WHERE id='".$_REQUEST['id']."' ";
		 mysqli_set_charset($conn,"utf8");
		if ($conn->query($sql) === TRUE) {
       echo "<script> alert('แก้ไขข้อมูลเรียบร้อย'); </script>";
	   echo '<script type="text/javascript">
           window.location = "index-login.html"
      </script>';
    	} else {
       echo "<script> alert('Error:  ไม่สามารถแก้ไขข้อมูล ได้'); </script>";
	   echo '<script type="text/javascript">
	   window.location = "index-login.html"
  </script>';
   		 }
	break;
	
case 'addnew' :	
	$ids = $_REQUEST['stdid1'];
	$sql1 = "select * from tb_std where std_id = '".$_REQUEST['idc1']."'";
  //echo $sql1;
  $result = $conn->query($sql1);
  $rowcount=mysqli_num_rows($result);
  if ($rowcount > 0) { ?>

			<script >  Swal.fire({
				icon: 'error',
				title: 'ลงทะเบียนสอบเรียบร้อยแล้ว ไม่สามารถลงทะเบียนสอบอีกได้',
				showConfirmButton: false,
				timer: 1500
			  }).then(function() {
					window.location = "index.php";});
			  </script>

<?php 
   /*
    echo "<script> alert('ลงทะเบียนเรียบร้อยแล้ว ไม่สามารถลงทะเบียนได้'); </script>";
    echo '<script type="text/javascript">
    window.location = "index-login.html"
</script>';
*/
  }else{
	if($_REQUEST['std_fname']==""){  ?>


<script >  Swal.fire({
				icon: 'error',
				title: 'กรุณากรอก ชื่อ - นามสกุล เพื่อลงทะเบียนสอบ ',
				showConfirmButton: false,
				timer: 1500
			  }).then(function() {
					window.location = "index.php";});
			  </script>

	<?php
		/*
		echo "<script> alert('กรุณากรอก ชื่อ - นามสกุล ให้เรียบร้อย'); </script>";
		echo '<script type="text/javascript">
				window.location = "index.php"
		   </script>';*/
	
	 }
	else if($_REQUEST['std_s2']==""){ ?>

<script >  Swal.fire({
				icon: 'error',
				title: 'กรอกรหัสประจำตัวผู้สมัครผิด กรุณากรอกข้อมูลใหม่เพื่อลงทะเบียน ',
				showConfirmButton: false,
				timer: 1500
			  }).then(function() {
					window.location = "index.php";});
			  </script>
<?php 
	/*
		echo "<script> alert('กรอกรหัสประจำตัวที่สมัครผิด กรุณากรอกใหม่'); </script>";
		echo '<script type="text/javascript">
				window.location = "index.php"
		   </script>';  */
	}
	else{
    	$std_level = $_REQUEST['std_level']; 
		if($std_level =="") $std_level="1";
		$ids = $_REQUEST['stdid1'];
 		$sql="insert into tb_std(std_id,std_fname,std_lname,std_sex,std_s1,std_s2,std_tel,std_level,insert_date) 
	 		values ('".$ids."','".$_REQUEST['std_fname']."','".$_REQUEST['std_lname']."','".$_REQUEST['std_sex']."','".$_REQUEST['std_s1']."','".$_REQUEST['std_s2']."','".$_REQUEST['std_tel']."','".$_REQUEST['std_level']."','".date("Y-m-d H:i:s")."')";
			mysqli_set_charset($conn,"utf8");
		if ($conn->query($sql) === TRUE) { ?>
		
       		<script >  Swal.fire({
				icon: 'success',
				title: 'ลงทะเบียนเรียบร้อยแล้ว กรุณารอสักครู่ เพื่อเข้าสู่ระบบ',
				showConfirmButton: false,
				timer: 1500
			  }).then(function() {
					window.location = "index-login.html";});
			  </script>
		<?php
	   		/*
			echo '<script type="text/javascript">
           			window.location = "index-login.html"
      			</script>'; */
    	} else {
       		echo "<script> alert('Error:  ไม่สามารถเพิ่มข้อมูล ได้'); </script>";
			echo '<script type="text/javascript">
			   window.location = "index-login.html"
		  </script>';   
    	}
	}
   }
	//mysql_query("SET NAMES 'utf8'");
	
	//echo "เพิ่มข้อมูล ผู้สมัครสอบ เรียบร้อยแล้ว";
	
	
	break;
 
/*
case 'del_file':
	
			$resultDoc = mysql_query("SELECT doc FROM article_data WHERE id='$id'")or die("ผิดพลาดเกี่ยวกับฐานข้อมูล del1");
			$dbarrDoc=mysql_fetch_array($resultDoc);
			if($dbarrDoc['doc'] && file_exists($docDir.$dbarr['doc'])){
				unlink($docDir.$dbarrDoc['doc']);
			}
			$sqlDoc=mysql_query("UPDATE article_data SET doc='' WHERE id='$id' ")or die("ผิดพลาดเกี่ยวกับฐานข้อมูล del2");;
			//echo"ลบข้อมูลเรียบร้อยแล้ว";
			
	break;
case 'up':

		$sql=mysql_query("SELECT list,id FROM article_data WHERE list > '$list' AND cat_productid=$cat_productid ORDER BY list ASC LIMIT 0,1 ");
		$row=mysql_fetch_assoc($sql);
		
		$sql_b=mysql_query("UPDATE article_data SET list='".$row['list']."' WHERE id='$id'");
		$sql_a=mysql_query("UPDATE article_data SET list='".$list."' WHERE id='".$row['id']."'");
		
	break;
case 'down':

		$sql=mysql_query("SELECT list,id FROM article_data WHERE list < '$list' AND cat_productid=$cat_productid ORDER BY list DESC LIMIT 0,1 ");
		$row=mysql_fetch_assoc($sql);
		
		$sql_b=mysql_query("UPDATE article_data SET list='".$row['list']."' WHERE id='$id'");
		$sql_a=mysql_query("UPDATE article_data SET list='".$list."' WHERE id='".$row['id']."'");

	break;
	*/
}// จบ switch

	

	exit();

	
	
}// จบ ตรวจสอบการส่ง action
   


?>

</body>
</html>