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
                        <h3 class="box-title">ลบข้อมูลผู้ทำข้อสอบ</h3>
                      </div>
                         
                        <div class="col-sm-4">
                         <form action="home_menu.php?page=show_del_std_test" method="get" class="sidebar-form">
                                <div class="input-group">
                         <input type="text" name="std_id" class="form-control" placeholder="Search...">
                            <span class="input-group-btn">
                            <button type="submit" name="page" value="show_del_std_test" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                            </button>
                        </span>
                        </div>
                       </form>
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
                  <th>นามสกุล</th>
                  <th>สาขา</th>
                  <th>คะแนน</th>
                  <th>จัดการ</th> 
                </tr>
                </thead>
                <tbody>
                <tr>
                  <?php 
                    $i=1;
                    $sql = "select * from tb_user  where id = ".$_REQUEST['std_id']." ";
                    $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                           $sql = "select de_name FROM  tb_depart  where id_std_name='".$row['de_id']."'"; 
                           $result1 = $conn->query($sql);
                           $row1 = $result1->fetch_assoc();
                           
                           $score = $row['score']; 
                           if($score==0) {
                             $txt = "เริ่มทำข้อสอบ";
                             $pic = "icon_play.png";
                             $sel =0;
                           }
                           else {
                             $sel =1;
                              $pic = "icon_true.png";
                             $txt = "ทำข้อสอบเรียบร้อยแล้ว";
                           } 
                  ?>
                  <td><?php echo $i  ?></td>
                  <td><?php echo $row['id'];  ?></td>
                  <td><?php echo $row['name'];  ?></td>
                  <td><?php echo $row['username'];  ?></td>
                  <td><?php echo $row1['de_name'];  ?></td>
                  <td><?php if($score==0) { ?> <font color="#ff0000"> <?php } ?> <?php echo $txt; ?>
                  <?php if($score==0) { ?> </font> <?php } ?></td>
                  <?php $name = $row['name']." ".$row['username']; ?>
                  <td><?php if($sel==0){ $data = $row['id']; ?> <a href="#" data-toggle="modal" data-target="#myModal" >  <img src="../images/<?php echo $pic; ?>" width="25"> </a> <?php } else{  ?>
                  <img src="../images/<?php echo $pic; ?>" width="25"> <?php } ?>
                      <a href="" data-toggle="modal" 
                          data-target="#editdata" 
                          data-whatever="<?php echo $row['id']; ?>"
                          data-stdid="<?php echo $row['id']; ?>"
                          data-stdfname="<?php echo $row['name']; ?>"
                          data-stdlname="<?php echo $row['username']; ?>"
                          data-stdselect = "<?php echo $row['de_name']; ?>"
                          
                          > <img src="../images/edit.png" width="25"> </a>
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
                  <th>สาขา</th>
                  <th>คะแนน</th>
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
        <p class="lead">รหัสผู้เข้าสอบ: <font color="#ff0000" >  <?php  echo $data;  ?> </font>   </p>
        <p class="lead">ชื่อผู้สอบ : <font color="#0000ff" >  <?php  echo $name;  ?> </font>   </p>
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

<div class="modal fade" id="editdata" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">แก้ไขข้อมูล   </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="edit_data_std_all.php" method="post" enctype="multipart/form">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">รหัสข้อมูล:</label>
            <input type="text" class="form-control" id="id"  name="id" readonly>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">รหัสผู้สมัคร:</label>
            <input type="text" class="form-control" id="stdid"  name="std_id">
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">ชื่อ</label>
            <input type="text" class="form-control" id="stdfname"  name="std_fname">
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">นามสกุล</label>
            <input type="text" class="form-control" id="stdlname"  name="std_lname">
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">พื้นฐานความรู้เดิม</label>
            <input type="text" class="form-control" id="stdlevel"  name="std_level">
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
$('#editdata').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever')
  var stdid = button.data('stdid')
  var stdfname = button.data('stdfname') 
  var stdlname = button.data('stdlname') 
  var stdselect = button.data('stdselect')
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
  modal.find('.modal-body #stdlevel').val(stdselect)
  //modal.find('.modal-body #stdlevel').val(stdlevel)
})

</script>