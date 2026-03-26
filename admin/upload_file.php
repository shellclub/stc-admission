<HTML>
<title>����ͺ STC: SuphanBuri Technical Collect</title> 
<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-874">
</head>
<style type="text/css">
<!--
.style4 {font-size: 14px; font-weight: bold; font-family: "Times New Roman", Times, serif; }
-->
</style>
<script type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<body onLoad="MM_preloadImages('Images/btnadd.gif','Images/btnshowa.gif')"><table width="800" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
  <!--DWLayoutTable-->
  <tr>
    <td height="148" colspan="4" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <!--DWLayoutTable-->
      <tr>
        <td width="800" height="146"><img src="../Images/baneradd.jpg" width="800" height="146" /></td>
      </tr>
    </table>    </td>
  </tr>
  <tr bgcolor="#EAFDFC">
    <td height="36" colspan="4" valign="top" bgcolor="#FFFFFF"><table width="100%" border="1" cellpadding="0" cellspacing="0">
        <tr>
          <td bgcolor="#EEEEEE">�Թ�յ�͹�Ѻ ::: ::: �������к� [��Ѻ˹����ѡ][�͡�ҡ�к�] </td>
        </tr>
      </table>
      <p align="center"><br>
        <?
		$status = 2;
		if(isset($_POST["send"])){
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
       else {$dest = "C:/AppServ/www/teststc2/ImagesQuestions/".$filename;
	   if(move_uploaded_file($tmpfile,$dest)){
	     $status=1;
	   }
	  }
	 }
    } 
	if($status == 1){
		include "../connect/connect.php";
	       $sql="insert into tb_test values(' ','$q_pic', '$q_t','$c1_pic','$c1','$c2_pic','$c2','$c3_pic','$c3','$c4_pic','$c4','$answer','$idgrouptest','$subject') ";
		   mysql_query("SET NAMES TIS620");
		   mysql_query("SET character_set_results=tis620");
		   mysql_query("SET character_set_client=tis620");
           mysql_query("SET character_set_connection=tis620");
            mysql_db_query($dbname,$sql);
	        mysql_close();
	        echo  "อัพโหลดข้อมูลเรียบร้อย";
	} 
	else if($status== 2){
	   include "../connect/connect.php";
	       $sql="insert into tb_test values(' ','$q_pic', '$q_t','$c1_pic','$c1','$c2_pic','$c2','$c3_pic','$c3','$c4_pic','$c4','$answer','$idgrouptest','$subject') ";
		   mysql_query("SET NAMES TIS620");
		   mysql_query("SET character_set_results=tis620");
		   mysql_query("SET character_set_client=tis620");
           mysql_query("SET character_set_connection=tis620");
            mysql_db_query($dbname,$sql);
	        mysql_close();
	        echo  "อัพโหลดข้อมูลเรียบร้อย";
	}
	else {
		 echo "ไม่สามารถอัพโหลดข้อมูลได้ ";
	}
  }
   
?>
    </p></td>
  </tr>
  <tr bgcolor="#EAFDFC">
    <td width="145" rowspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <!--DWLayoutTable-->
      <tr>
        <td width="800" height="34" bgcolor="#FFFFFF"><a href="addtest.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image2','','Images/btnadd.gif',1)"><img src="../Images/btnadda.gif" name="Image2" width="145" height="34" border="0" id="Image2" /></a></td>
        </tr>
    </table></td>
  <td width="1" height="1"></td>
  <td width="152"></td>
  <td width="497"></td>
  </tr>
  <tr bgcolor="#EAFDFC">
    <td height="35"></td>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <!--DWLayoutTable-->
      <tr>
        <td width="154" height="35"><a href="showalltest.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image3','','Images/btnshowa.gif',1)"><img src="../Images/btnshow.gif" name="Image3" width="145" height="34" border="0"></a></td>
      </tr>
      </table></td>
  <td></td>
  </tr>
  <tr bgcolor="#EAFDFC">
    <td height="23">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  
</table>
