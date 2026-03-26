<?php 
 require('../config.inc.php');
 mysqli_set_charset($conn,"utf8");
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ข้อมูลผู้สมัคร
        <small>ข้อมูลผู้สมัครระดับปวส</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home_menu.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">ข้อมูลผู้สมัคร</a></li>
        <li class="active">ข้อมูลผู้สมัครระดับ ปวส</li>
      </ol>
    </section>
<section class="content"> 
        <div class="row">
         <div class="col-xs-12">
         <div class="box">
            <div class="box-header">
              <h3 class="box-title">ข้อมูลผู้สมัคร ระดับปวส</h3>
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
                  <th>รหัสบัตรประชาชน</th>
                  <th>รหัสผู้เข้าสอบ</th>
                  <th>ชื่อ</th>
                  <th>สาขาที่สอบ</th>
                  <th>สถานะ</th>
                  <th>แก้ไขข้อมูล</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <?php 
                    $i=1;
                    $sql = "select * from tb_user_new  where level = '2' or level = '3' order by de_id  ";
                    $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                           $score = $row['score'];
                           if($score==0) { 
                             $pic = "icon_play.png";
                             $sel = 1;
                           }
                           else if($score ==-1) {
                             $pic = "icon_false.png";
                             $sel =0;
                           } 
                           else {
                             $pic = "icon_true.png";
                             $sel =0;
                           }
                           

                  ?>
                  <td><?php echo $i  ?></td>
                  <td><?php echo $row['idcard'];  ?></td>
                  <td><?php echo $row['id'];  ?></td>
                  <td><?php echo $row['name'];  ?></td>
                  <td><?php echo $row['major'];  ?></td>
                  <td><?php echo $row['de_id']; ?></td>
                  <td><?php if($sel==1){  ?>  <img src="../images/<?php echo $pic; ?>" width="25"><?php } else{  ?>
                  <img src="../images/<?php echo $pic; ?>" width="25"> <?php } ?>
                  </td>
                  <td> 
                    <?php if($score == 0) { ?>  
                  <button type="button" class="btn btn-warning" data-toggle="modal" 
                          data-target="#exampleModal" 
                          data-whatever="<?php echo $row['id']; ?>"
                          data-stdid="<?php echo $row['id']; ?>"
                          data-stdfname="<?php echo $row['name']; ?>"
                          data-stdlevel="<?php echo $row['level']; ?>"
                          > แก้ไขข้อมูล </button>
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
 
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">แก้ไขข้อมูล   </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="edit_data_std_y1.php" method="post" enctype="multipart/form">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">รหัสข้อมูล:</label>
            <input type="text" class="form-control" id="id"  name="id" readonly>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">รหัสผู้สมัคร:</label>
            <input type="text" class="form-control" id="stdid"  name="std_id">
          </div>
           
          <div class="form-group">
            <label for="message-text" class="col-form-label">เลือกพื้นฐานความรู้</label>
            <select class="form-control" id="stdlevel"name="std_level">
                      <option value="2"> ม.6 </option>
                      <option value="3"> ปวช. </option>
            </select>
          </div>
           
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก  </button>
        <button type="submit" class="btn btn-primary">แก้ไขข้อมูล</button>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript">
$('#exampleModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever')
  var stdid = button.data('stdid')
  var stdfname = button.data('stdfname') 
  var stdlname = button.data('stdlname') 
  //var stdlevel = button.data('stdlevel') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('.modal-title').text('แก้ไขข้อมูลผู้สมัคร ' + recipient)
  //modal.find('.modal-body input').val(recipient)
  modal.find('.modal-body #id').val(recipient)
  modal.find('.modal-body #stdid').val(stdid)
  modal.find('.modal-body #stdfname').val(stdfname)
  modal.find('.modal-body #stdlname').val(stdlname)
  //modal.find('.modal-body #stdlevel').val(stdlevel)
})

</script>