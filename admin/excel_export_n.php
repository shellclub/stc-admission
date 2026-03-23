<?php
date_default_timezone_set('Asia/Bangkok');
$dates = date('d-m-y');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="test_"'.$dates.'_.xls"');#ชื่อไฟล์
require('../config.inc.php');
mysqli_set_charset($conn,"utf8");
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<HTML>
<HEAD>
<meta http-equiv="Content-type" content="text/html;charset=utf8" />


<table x:str BORDER="1" >
<thead>
                <tr>
                  <th>ลำดับที่</th>
                  <th>รหัสผู้เข้าสอบ</th>
                  <th>ชื่อ</th>
                  <th>สาขา</th>
                  <th>ระดับชั้น</th>
                  <th>คะแนน</th>
                  <th>วันที่สอบ,เวลาที่สอบ</th> 
                </tr>
                </thead>
                <tbody>
                <tr>
                  <?php 
                    $i=1;
                    if(isset($_REQUEST['dep'])){
                       $dep = $_REQUEST['dep'];
                    }else{
                      $dep=0;
                    }
                    if($dep==0)
                      $sql = "SELECT * from tb_user_new inner JOIN tb_log_test on tb_user_new.id = tb_log_test.id where score > 0 order by tb_user_new.level ";
                    else 
                      $sql = "SELECT * from tb_user_new inner JOIN tb_log_test on tb_user_new.id = tb_log_test.id where score > 0  and de_id='".$_REQUEST['dep']."'  order by score desc ";
                    $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                           $sql3= "select * from tb_user_test  where id =".$row['id'];
                           $result2 = $conn->query($sql3);
                           $row2 = $result2->fetch_assoc();
                           $score = $row['score']; 
                           if($score==0) {
                             $txt = "เริ่มทำข้อสอบ";
                             $pic = "icon_play.png";
                             $sel =0;
                           }
                           else {
                             $sel =1;
                              $pic = "icon_true.png";
                             $txt = $score;
                           } 
                  ?>
                  <td><?php echo $i  ?></td>
                  <td><?php echo $row['id'];  ?></td>
                  <td><?php 
                          echo $row['name'];  ?></td>
                  <td><?php echo $row['major'];  ?></td>
                  <td><?php  $data_level = "";
                            if($row['level'] == 1) $data_level = "ปวช.";
                            else if($row['level'] == 3) $data_level = "ปวส./ ปวช."; 
                            else if($row['level'] ==2) $data_level =  "ปวส./ ม.6"; 
                            else if($row['level'] ==4) $data_level =  "ป.ตรี"; 
                            echo $data_level; ?></td>
                  <td><?php if($score==0) { ?> <font color="#ff0000"> <?php } ?> <?php echo $txt; ?>
                  <?php if($score==0) { ?> </font> <?php } ?></td>
                  <td> <?php echo $row['dete_log']; ?>
                  </td> 
                </tr>
                  <?php $i++; }} ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>ลำดับที่</th>
                  <th>รหัสผู้เข้าสอบ</th>
                  <th>ชื่อ</th>
                  <th>สาขา</th>
                  <th>ระดับชั้น</th>
                  <th>คะแนน</th>
                   <th>วันที่สอบ,เวลาที่สอบ</th>  
                </tr>
                </tfoot>
              </table>
</body>
</html>
