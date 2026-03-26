<?php 

 require('../config.inc.php');
 mysqli_set_charset($conn,"utf8");
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        คะแนนสอบ
        <small> ผู้สมัครระดับปวช </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home_menu.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">คะแนนสอบ</a></li>
        <li class="active"> ผู้สมัครระดับ ปวช</li>
      </ol>
    </section>
       <section class="content"> 
        <div class="row">
         <div class="col-xs-12">
         <div class="box">
            <div class="box-header">
               
                 
                    <div class="col-sm-6">
                        <h3 class="box-title">ข้อมูลคะแนนสอบ   ผู้สมัครระดับปวช</h3>
                      </div>
                         
                        <div class="col-sm-4">
                         <p> <font color="#0000ff">  ส่งออกข้อมูล </font> <a href="excel_export_n.php">  <img src="../images/excel.jpg" width="15%">  </a></p>
                        </div>
                 
            </div>
              
              <?php 
                $sql = "select * from tb_depart"; 
                 $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $dep_id = $row['id_std_name'];
                           // echo $dep_id;
                           echo '<a href="home_menu.php?page=show_score_std&dep='.$dep_id.'" class="btn btn-info" role="button">'.$dep_id.'</a> ';
                        }
                }
                if(isset($_GET['dep'])){
                   $dep = $_GET["dep"];
                }else{
                   $dep = "";
                }
              ?>
              <font color="#0000ff">  ส่งออกข้อมูล(แยกสาขา) </font> <a href="excel_export_dep.php?dep=<?php echo $dep; ?>">  <img src="../images/excel.jpg" width="50">  </a>
            <!-- /.box-header -->
            <div class="box-body">
              
                

              <table id="example1" class="table table-bordered table-striped">
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
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

  <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">จัดการข้อมูลผู้สอบ </h4>
      </div>
      <div class="modal-body">
        <?php 
          
          $sql = "select * from tb_log_test where id='".$data."'";
                    $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                         $row = $result->fetch_assoc();
                              $data_in = $row['dete_in']; 
                      }

                      if($data_in=="") $data_in="ยังไม่ได้เข้าทำข้อสอบ";
             
        ?>
        <p>ผู้เข้าสอบได้เข้าสอบเวลา: <font color="#ff0000" >  <?php  echo $data_in;  ?> </font>   </p>
         <p class="lead"> ต้องการ ลบข้อมูลการทำข้อสอบผู้สมัคร </p>  
      </div>
      <div class="modal-footer">
        <a href="del_std_test.php?id=<?php echo $data; ?>"  class="btn btn-primary"    >ลบข้อมูล</a>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>