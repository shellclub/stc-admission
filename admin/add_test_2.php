 <?php
	
	require("../config.inc.php");	
    mysqli_set_charset($conn,"utf8");

	if(isset($_REQUEST['ac'])){
		
	switch($_REQUEST['ac']){
		case 'edit':
		$sql = "SELECT tb_std.*,tb_depart.* FROM tb_std 		
		LEFT JOIN tb_depart ON tb_depart.de_id = tb_std.std_s1			
		 WHERE id='".$_REQUEST['id']."' AND tb_std.std_level = '1'";
     $result = $conn->query($sql);
        $fet_edit=  $result->fetch_assoc();

   // echo $fet_edit['id'];
		break;
		case 'del':		
	     $sql = "DELETE  FROM tb_std  WHERE id='".$_REQUEST['id']."' ";
       if ($conn->query($sql) === TRUE) {
            echo "<script> alert('ลบข้อมูลเรียบร้อย'); </script>";
        } else {
            echo "<script> alert('ไม่สามารถลบข้อมูลได้'); </script>";
      }		 
		break;
		}
		
	}
?>
 
   
<!-- Default box -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ระบบทดสอบวัดความรู้
        <small>วิทยาลัยเทคนิคสุพรรณบุรี</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home_menu.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">เพิ่มข้อสอบเข้าสู่ระบบ</li>
      </ol>
    </section>
     <section class="content">

          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">เพิ่มข้อสอบเข้าสู่ระบบ</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
             <div class="box box-warning">
             <div class="box-header with-border">
             <h3 class="box-title">เพิ่มข้อสอบเข้าสู่ระบบ </h3>
             </div>             
             <form class="form-horizontal" name="loginform" id="loginform" action="action_test_2.php?<?php if($_REQUEST['ac']=='edit'){ echo "action=update"; }else{ echo "action=addnew"; } ?>&ref=1"  ENCTYPE="multipart/form-data" onSubmit="return ajaxSubmit(this)" method="post">            
             <div class="box-body">

                <div class="form-group row">
                      <label for="std_id" class="col-sm-2 control-label">รูป คำถาม</label>
                    <div class="col-xs-2">
                      <input name="userfile[0]" type="file" id="userfile[0]" />
                    </div>       
                     
                </div>

                <div class="form-group row">
                      <label for="std_id" class="col-sm-2 control-label">รูป คำตอบที่ 1</label>
                    <div class="col-xs-2">
                      <input name="userfile[1]" type="file" id="userfile[1]" />
                       
                    </div>
                       
                    <label for="std_id" class="col-sm-2 control-label">รูป คำตอบที่ 2</label>
                    <div class="col-xs-3">
                    <input name="userfile[2]" type="file" id="userfile[2]" />
                       
                    </div>
                      
                </div>
                <div class="form-group row">
                      <label for="std_id" class="col-sm-2 control-label">รูป คำตอบที่ 3</label>
                    <div class="col-xs-2">
                      <input name="userfile[3]" type="file" id="userfile[3]" />
                       
                    </div>
                       
                    <label for="std_id" class="col-sm-2 control-label">รูป คำตอบที่ 4</label>
                    <div class="col-xs-3">
                    <input name="userfile[4]" type="file" id="userfile[4]" />
                       
                    </div>
                      
                </div>         
                      <div class="form-group">
                      <label for="stopic" class="col-sm-2 control-label">คำถาม</label>
                      <div class="col-sm-6">
                                <textarea name="q_t" cols="100" rows="10"></textarea>
                      </div>
                    </div>
                     
                                    <!-- radio -->
                  <div class="form-group">
                        <label for="stopic" class="col-sm-2 control-label">เฉลยคำตอบ ข้อที่ </label>
                        <div class="col-xs-2">
                        <select name="answer" size="1">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                        </div>
                        <label for="stopic" class="col-sm-2 control-label">ชุดของคำถาม  </label>
                        <div class="col-xs-2">
                        <select name="idgrouptest" size="1">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                        </div>
                  </div>              
                     
 <?php
  
  if($_REQUEST['ac'] =='edit'){
    $idc1 = substr($fet_edit['std_id'],0,5);
  }else{
    $idc1 = substr($_REQUEST['idc1'],0,5);
  }
	$sql = "SELECT * FROM tb_depart WHERE de_type='1'and id_std_name = '".$idc1."' ";
    $result = $conn->query($sql); 
 if($result->num_rows >0){
      $row = $result->fetch_assoc(); 
      $chma = 1; 
?>     

                      <input type="hidden"   id="std_s1"  name="std_s1" value="<?php echo $row['de_id']; ?>"> </input>
                      <input type="text"  class="form-control"   id="name_dep"  name="name_dep" value="<?php echo $row['de_name']; ?>" readonly> </input>
                            
<?php } else{
        $chma = 0;
        if(strlen($idc1)==3) 
        echo "<script> alert('ไม่ม่ข้อมูลสาขานี้ กรุณากรอกข้อมูลใหม่'); </script>";
         
        
}?> 
                     
                 
                     
                     
                       <div class="form-group">
                      <label for="std_tel" class="col-sm-2 control-label">รหัสชุดคำถาม </label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="std_tel"  name="subject" >
                        
                      </div>
                    </div>                    
                   
                   
                  <div class="col-sm-6">
                       
                    <button type="submit" class="btn btn-info pull-right"><?php if($_REQUEST['id']){ echo "แก้ไขข้อมูล"; } else { echo "เพิ่มข้อมูล"; } ?></button>
                  </div>
               


              </form>
             
             
             </div>
            </div><!-- /.box-body -->
            </div>
          <div class="box">
                <div class="box-header">
                  <h3 class="box-title">แสดงรายชื่อนักเรียนที่สมัครเรียน ประเภททั่วไป ระดับชั้น  ประกาศนียบัตรวิชาชีพ ปวช. </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>ลำดับที่</th>
                        <th>รหัสประจำตัว</th>
                        <th>ชื่อ-นามกสุล</th>
                        <th>สาขาที่เลือก</th>
                        <th>เกรดที่จบ</th>
                        <th>จัดการ</th>
                      </tr>
                    </thead>
                    <tbody>
<?php     
      
      

		$sql = "SELECT tb_std.*,tb_depart.* FROM tb_std 		
		LEFT JOIN tb_depart ON tb_depart.de_id = tb_std.std_s1			
		 WHERE tb_std.std_level = '1' AND std_qota='0' ";
         $result = $conn->query($sql); 
		//$num=$result->num_rows;
		$no=1;
		$tname=array('1'=>"นาย",'2'=>"นางสาว");
        if($result->num_rows >0){
		while($fet=$result->fetch_assoc()){
		 
?>              
                   
                        <tr <?php if($objArr[0]==1){ echo "class=\"danger\" "; } ?>>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $fet['std_id']; ?></td>
                        <td><?php  echo $tname[$fet['std_sex']]."".$fet['std_fname']."   ".$fet['std_lname']; ?></td>                       
                        <td><?php echo $fet['de_name']; ?></td>
                        <td><?php echo $fet['std_gpa']; ?></td>
                        <td  align="center"><a href="home_menu.php?page=manage_std&ac=edit&id=<?php echo $fet['id']; ?>" title="<?php echo "แก้ไขรายชื่อ".$tname[$fet['std_sex']]."".$fet['std_fname']."   ".$fet['std_lname']; ?>"><img src="../images/edit.png" width="32"> | <a href="home_menu.php?page=manage_std&ac=del&id=<?php echo $fet['id']; ?>" title="<?php echo "ต้องการลบ".$tname[$fet['std_sex']]."".$fet['std_fname']."   ".$fet['std_lname']; ?>" onClick="return confirm ('ต้องการลบ <?php echo $tname[$fet['std_sex']]."".$fet['std_fname']."   ".$fet['std_lname']; ?> ?')"><img src="../images/del.jpg" width="32"></a></td>
                      </tr> 
                    
<?php
$no++;
	}}
?> 

                    </tfoot>
                  </table>               
                </div><!-- /.box-body -->
                            </div>
            <div class="box-footer">
            แสดงรายการนักเรียน นักศึกษา สมัครใหม่
            </div><!-- /.box-footer-->
          </div><!-- /.box -->
    </div>
</section>        
          
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="myModalLabel">Please Wait..การดำเนินการอาจใช้เวลาหลายนาที..อย่าดำเนินการใดๆ จนกว่าหน้าจอจะปิดลง</h4>
      </div>
      <div class="modal-body center-block">
                  <div class="progress">
                    <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                      <span class="sr-only">40% Complete (success)</span>
                    </div>
                  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->                 

<!--  model    -->
<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">เพิ่มข้อมูลโรงเรียนที่จบ </h4>
        </div>
        <div class="modal-body">
         <div class="box-body">
             <div class="box box-warning">
             <div class="box-header with-border">
             <h3 class="box-title">กรอกข้อมูลโรงเรียนให้เรียบร้อย แล้วบันทึกข้อมูล</h3>
             </div>             
             <form class="form-horizontal" name="loginform" id="loginform" action="action/action_school.php?p=manage_std&action=addnew&ref=<?php echo $_REQUEST['ref']; ?>"  ENCTYPE="multipart/form-data" onSubmit="return ajaxSubmit(this)" method="post">             
             <div class="box-body">
    
                      <div class="form-group">
                      <label for="stopic" class="col-sm-4 control-label">ชื่อโรงเรียน ภาษาไทย</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="sc_name" name="sc_name" placeholder="ชื่อ ภาษาไทย" value="<?php echo $fet_edit['sc_name']; ?>">
                      </div>
                    </div>
                      <div class="form-group">
                      <label for="stopic" class="col-sm-4 control-label">ชื่อโรงเรียน ภาษาอังกฤษ</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="sc_eng" name="sc_eng" placeholder="ชื่อ ภาษาอังกฤษ" value="<?php echo $fet_edit['sc_eng']; ?>">
                      </div>
                    </div>
                  
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    
                    <button type="submit" class="btn btn-info pull-right"><?php if($_REQUEST['id']){ echo "แก้ไขข้อมูล"; } else { echo "เพิ่มข้อมูล"; } ?></button>
              </div>
              </form>             
             
             </div>
            </div><!-- /.box-body -->

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
   
<script>
      $(function () {
		$(".select2").select2();
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-red',
          increaseArea: '20%' // optional
        });
      });	
       $('.submit').click(function(){
          validateForm();   
    });
       
</script>
 