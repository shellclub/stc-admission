<?php 
 require('../config.inc.php');
 mysqli_set_charset($conn,"utf8");
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ข้อมูลผู้สมัครสอบ 
        <small>  ข้อมูลผู้สอบ  </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home_menu.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">ผู้สอบตอนนี้</a></li>
        <li class="active"> จำนวนผู้สอบตอนนี้</li>
      </ol>
    </section>
       <section class="content"> 
        <div class="row">
         <div class="col-xs-12">
         <div class="box">
            <div class="box-header">
               
                 
                    <div class="col-sm-6">
                        <h3 class="box-title">ข้อมูลผู้สอบ</h3>
                      </div>
                         
                        <div class="col-sm-4">
                         <p> <font color="#0000ff">  ส่งออกข้อมูล </font> <a href="export_excel_data.php">  <img src="../images/excel.jpg" width="15%">  </a></p>
                        </div>
                  
            </div>
              
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ลำดับที่</th>
                  <th>รหัสผู้เข้าสอบ</th>
                  <th>ชื่อ</th>
                  <th>สาขา</th>
                  <th>เวลาที่เริ่มสอบ</th>
                  <th>สถานะ</th>
                  <th>จัดการ</th> 
                </tr>
                </thead>
                <tbody>
                <tr>
                  <?php 
                    $i=1;
                    $sql = "select * from tb_user_test INNER JOIN tb_log_test on tb_user_test.id=tb_log_test.id INNER JOIN tb_user_new on tb_user_new.id = tb_user_test.id where tb_user_test.score =0;";
                    $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                          
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
                  <td><?php echo $row['name'];  ?></td>
                  <td><?php echo $row['major'];  ?></td>
                  <td><?php echo $row['dete_in'];  ?></td>
                  <td><?php if($score==0) { ?> <font color="#ff0000"> <?php } ?> <?php echo $txt; ?>
                  <?php if($score==0) { ?> </font> <?php } ?></td>
                  <td><?php if($sel==0){ $data = $row['id']; ?> 
                    <a href="#" data-toggle="modal" 
                    data-target="#myModal"
                    data-stdid="<?php echo $row['id']; ?>" > 
                    <img src="../images/<?php echo $pic; ?>" width="25"></a> <?php } else{  ?>
                  <img src="../images/<?php echo $pic; ?>" width="25"> <?php } ?>
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
                  <th>เวลาที่เริ่มสอบ</th>
                  <th>สถานะ</th>
                   <th>จัดการ</th>  
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
      <form method="post" action="del_std_test.php">
      <div class="modal-body">
        <label class="control-label"> ลบข้อมูลรหัสผู้สอบ คือ </label>
        <input type="text" id="stdid" name="id" readonly >
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
        <input type="submit" class="btn btn-primary"  value="ลบข้อมูล"  ></input>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      </from>
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
$('#myModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever')
  var stdid = button.data('stdid')
  var stdfname = button.data('stdfname') 
  var stdlname = button.data('stdlname') 
  //var stdlevel = button.data('stdlevel') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('.modal-title').text('ลบข้อมูลผู้สมัคร ')
  //modal.find('.modal-body input').val(recipient)
  modal.find('.modal-body #id').val(recipient)
  modal.find('.modal-body #stdid').val(stdid)
  modal.find('.modal-body #stdfname').val(stdfname)
  modal.find('.modal-body #stdlname').val(stdlname)
  //modal.find('.modal-body #stdlevel').val(stdlevel)
})

</script>
