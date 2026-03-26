<?php
	header('Content-type: text/html; charset=utf-8');	
	require("../config.inc.php");
	
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
           window.location = "home_menu.php?page=manage_std"
      </script>';
    	} else {
       echo "<script> alert('Error:  ไม่สามารถแก้ไขข้อมูล ได้'); </script>";
   		 }
	break;
	
case 'addnew' :	
    $std_level = $_REQUEST['std_level']; 
	if($std_level =="") $std_level="1";
	$ids = $_REQUEST['stdid1'];
 	$sql="insert into tb_std(std_id,std_fname,std_lname,std_sex,std_s1,std_s2,std_tel,std_level,insert_date) 
	 values ('".$ids."','".$_REQUEST['std_fname']."','".$_REQUEST['std_lname']."','".$_REQUEST['std_sex']."','".$_REQUEST['std_s1']."','".$_REQUEST['std_s2']."','".$_REQUEST['std_tel']."','".$_REQUEST['std_level']."','".date("Y-m-d H:i:s")."')";
	mysqli_set_charset($conn,"utf8");
	if ($conn->query($sql) === TRUE) {
       echo "<script> alert('เพิ่มข้อมูลเรียบร้อยแล้ว'); </script>";
	   echo '<script type="text/javascript">
           window.location = "home_menu.php?page=manage_std"
      </script>';
    } else {
       echo "<script> alert('Error:  ไม่สามารถเพิ่มข้อมูล ได้'); </script>";
    }
	
	//mysql_query("SET NAMES 'utf8'");
	
	//echo "เพิ่มข้อมูล ผู้สมัครสอบ เรียบร้อยแล้ว";
	
	
	break;
case 'delete':
	
	$where="WHERE product_id='$product_id' ";
	$numrows = countRec('barcode','bill_saledetail',$where);
	if(!$numrows){
		
		$sql_product="DELETE FROM product WHERE product_id='$product_id' ";
		$result=mysql_query($sql_product)or die("ผิดพลาดเกี่ยวกับฐานข้อมูล product");
	
	}else{
		echo"ข้อมูลสินค้าอยู่ในระบบแล้ว ไม่สามารถลบได้ หากต้องการลบกรุณาติดต่อผู้ดูแลระบบ";
	}
		
	break;
case 'permission':

		if($publice=='1'){ 
			$sql = "UPDATE product SET publice='0' WHERE product_id=$product_id";
		}else{
			$sql = "UPDATE product SET publice='1' WHERE product_id=$product_id";	
		}
		mysql_query("SET NAMES 'utf8'");
		$result=mysql_query($sql)or die("ผิดพลาดเกี่ยวกับฐานข้อมูล permission");

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