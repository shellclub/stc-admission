<?php 
 require('../config.inc.php');
 mysqli_set_charset($conn,"utf8");
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ข้อมูลผู้สมัคร
        <small>ข้อมูลผู้สมัครระดับ  ปริญญาตรี</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home_menu.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">ข้อมูลผู้สมัคร</a></li>
        <li class="active">ข้อมูลผู้สมัครระดับ ปริญญาตรี</li>
      </ol>
    </section>
<section class="content"> 
        <div class="row">
         <div class="col-xs-12">
         <div class="box">
            <div class="box-header">
              <h3 class="box-title">ข้อมูลผู้สมัคร ระดับปริญาตรี</h3>
            </div>

            

            <!-- /.box-header -->
            <div class="box-body">
               
                <div >
                         <p> <font color="#0000ff">  ส่งออกข้อมูล </font> <a href="export_excel_data_stu.php">  
                           <img src="../images/excel.jpg" width="50">  </a></p>
                </div>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ลำดับที่</th>
                  <th>รหัสผู้เข้าสอบ</th>
                  <th>ชื่อ</th>
                  <th>นามสกุล</th>
                  <th>สาขาที่สอบ</th>
                  <th>สถานะ</th>
                  <th>แก้ไขข้อมูล</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <?php 
                    $i=1;
                    $sql = "select * from tb_std where std_level=4   order by id  ";
                    $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                           $sql = "select de_name FROM  tb_depart  where id_std_name='".$row['std_s2']."'"; 
                           $result1 = $conn->query($sql);
                           $row1 = $result1->fetch_assoc();
                           
                           $sql = "select score FROM  tb_user  where id='".$row['std_id']."'"; 
                           $result2 = $conn->query($sql);
                           if($result2->num_rows > 0) {
                              $rowd = $result2->fetch_assoc();
                              $data_l = $rowd['score']; 
                           }else{
                             $data_l = -1;
                           }
                           if($data_l==0) { 
                             $pic = "icon_play.png";
                             $sel = 1;
                           }
                           else if($data_l ==-1) {
                             $pic = "icon_false.png";
                             $sel =0;
                           } 
                           else {
                             $pic = "icon_true.png";
                             $sel =0;
                           }
                  ?>
                  <td><?php echo $i  ?></td>
                  <td><?php echo $row['std_id'];  ?></td>
                  <td><?php echo $row['std_fname'];  ?></td>
                  <td><?php echo $row['std_lname'];  ?></td>
                  <td><?php echo $row1['de_name']; ?></td>
                  <td>
                    <?php if($sel==1){  ?>  <img src="../images/<?php echo $pic; ?>" width="25"><?php } else{  ?>
                  <img src="../images/<?php echo $pic; ?>" width="25"> <?php } ?>
                  </td>
                  <td> 
                  <?php if($data_l == -1 ) { ?>  
                  <button type="button" class="btn btn-warning" data-toggle="modal" 
                          data-target="#exampleModal" 
                          data-whatever="<?php echo $row['id']; ?>"
                          data-stdid="<?php echo $row['std_id']; ?>"
                          data-stdfname="<?php echo $row['std_fname']; ?>"
                          data-stdlname="<?php echo $row['std_lname']; ?>"
                          data-stdlevel="<?php echo substr($row['std_id'],0,1); ?>"
                          >แก้ไขข้อมูล</button>
                     <?php } else  echo "ไม่สามารถแก้ไขข้อมูลได้" ?>
                  </td>
                </tr>
                  <?php $i++; }} ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>ลำดับที่</th>
                  <th>รหัสผู้เข้าสอบ</th>
                  <th>ชื่อ</th>
                  <th>นามสกุล</th>
                  <th>สาขาที่สอบ</th>
                  <th>สถานะ</th>
                  <th>แก้ไขข้อมูล</th>
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
 
