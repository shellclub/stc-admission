<? 
/*
session_start(); 
if(isset($_SESSION['user1'])) 
$_SESSION['user1'] = $user1; 
else {
	echo " <script> alert('��س� login �������к�'); </script>";
	echo  "<meta http-equiv='refresh' content='0; url=index.php'>";
} */
?>
<html >
<head>
<style type="text/css">
<!--
.style4 {font-size: 14px; font-weight: bold; font-family: "Times New Roman", Times, serif; }
body {
	background-image: url(Images/cc.jpg);
}
-->
</style>
<title>��������ͺ::STC</title><table width="800" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
  <!--DWLayoutTable-->
  <tr>
    <td width="800" height="146" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <!--DWLayoutTable-->
      <tr>
        <td width="800" height="146"><img src="../Images/baneradd.jpg" width="800" height="146" /></td>
      </tr>
    </table>    </td>
  </tr>
  <tr bgcolor="#EAFDFC">
    <td height="547" valign="top" bgcolor="#FFFFFF"><form action="upload_file.php"  method="post" enctype="multipart/form-data" name="form1" id="form1">
      <table width="100%" height="50" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999">
        <tr>
          <td bgcolor="#EEEEEE">�Թ�յ�͹�Ѻ ::: <? echo $_SESSION['user1']; ?>::: �������к� [<a href="madmin.php?user1=<? echo $user1 ; ?>">��Ѻ˹����ѡ</a>][<a href="logout.php">�͡�ҡ�к�</a>] </td>
        </tr>
      </table>
      <p class="style4">�ٻ�Ҿ�Ӷ��
        <input name="userfile[0]" type="file" id="userfile[0]" />
      </p>
      <p><strong class="style4">�ٻ�Ҿ�ӵͺ � </strong>
          <input name="userfile[1]" type="file" id="userfile[1]" />
          <span class="style4"><strong class="style4">�ٻ�Ҿ�ӵͺ </strong>� </span>
          <label>
          <input name="userfile[2]" type="file" id="userfile[2]" />
          </label>
      </p>
      <p><span class="style4"><strong class="style4">�ٻ�Ҿ�ӵͺ</strong> � </span>
          <label>
          <input name="userfile[3]" type="file" id="userfile[3]" />
          </label>
          <span class="style4"><strong class="style4">�ٻ�Ҿ�ӵͺ</strong> � </span>
          <label>
          <input name="userfile[4]" type="file" id="userfile[4]" />
          </label>
      </p>
      <p class="style4">&nbsp;</p>
      <p><span class="style4">��ͤ���⨷��Ӷ��
        </span>
        <textarea name="q_t" cols="100" rows="3"></textarea>
      </p>
      <p><span class="style4">��ͤ����ӵͺ � </span>
          <label>
          <input name="c1" type="text" size="100" />
          </label>
        </p>
        <p><span class="style4">��ͤ����ӵͺ � </span>
          <label>
          <input name="c2" type="text" size="100" />
          </label>
        </p>
        <p><span class="style4">��ͤ����ӵͺ � </span>
          <label>
          <input name="c3" type="text" size="100" />
          </label>
        </p>
        <p><span class="style4">��ͤ����ӵͺ � </span>
          <label>
          <input name="c4" type="text" size="100" />
          </label>
        </p>
        <p>&nbsp;</p>
        <p><span class="style4">��¤ӵͺ���</span>
          <label>
          <select name="answer" size="1">
            <option value="1">�</option>
            <option value="2">�</option>
            <option value="3">�</option>
            <option value="4">�</option>
          </select>
          </label>
          <span class="style4"> �ش����ͺ���</span> 
          <label>
           <select name="idgrouptest" size="1">
             <option value="1">1</option>
             <option value="2">2</option>
             <option value="3">3</option>
             <option value="4">4</option>
           </select>
          </label>
          <span class="style4">
          ����ͺ�Ԫ�</span>
          <label>
          <select name="subject" size="1" id="subject">
            <option value="������Ѵ�ҧ������¹(SAT)" selected>������Ѵ�ҧ������¹(SAT)</option>
            <option value="�Է����ʵ���鹰ҹ">�Է����ʵ���鹰ҹ</option>
            <option value="��Ե��ʵ���鹰ҹ">��Ե��ʵ���鹰ҹ</option>
            <option value="������Ѵ�ԧ��ҧ">������Ѵ�ԧ��ҧ</option>
            <option value="�ѧ��ɾ�鹰ҹ">�ѧ��ɾ�鹰ҹ</option>
            <option value="͹ء���Ţ��Ե">͹ء���Ţ��Ե</option>
            <option value="������">������</option>
          </select>
          </label>
        </p>
        <p align="center">
          <input name="send" type="submit" class="style4" value="�ѹ�֡������" />
            <input name="Submit2" type="reset" class="style4" value="¡��ԡ" />
        </p>
        <p>
          <label></label>
          <label></label>
        </p>
    </form></td>
  </tr>
  <tr>
    <td height="35" bgcolor="#FFFFFF"><hr /></td>
  </tr>
  <tr>
    <td height="37" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <!--DWLayoutTable-->
      <tr>
        <td width="800" height="35"><img src="../Images/dbanner.jpg" width="800" height="35" /></td>
      </tr>
    </table>
    </td>
  </tr>
</table>




