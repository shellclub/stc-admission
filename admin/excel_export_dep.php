<?php
date_default_timezone_set('Asia/Bangkok');
$dates = date('d-m-y');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="คะแนนสอบรหัสสาขา_"'.$_REQUEST['dep'].'_.xls"');#ชื่อไฟล์
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
                  <th>วันที่สอบ</th>
                  <th>เวลาที่สอบ</th> 
                </tr>
                </thead>
                <tbody>
                <tr>
                  <?php 
                    $i=1;
                    
                    $sql = "SELECT * from tb_user_new inner JOIN tb_log_test on tb_user_new.id = tb_log_test.id where score > 0  and de_id='".$_REQUEST['dep']."'  order by score desc ";
                    $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                           $score = $row['score']; 
                            
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
                  <td><?php echo $score;  ?></td>
                  <td> <?php 
                  
                        $log =  explode(',',$row['dete_log']);
                        echo $log[0]; 
                        ?>
                  </td> 
                  <td>  <?php 
                        echo $log[1];
                        ?>
                  </td>
                </tr>
                  <?php $i++; }} ?>
                
              </table>
</body>
</html>
