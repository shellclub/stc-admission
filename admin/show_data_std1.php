<?php 
/**
 * ==============================================================================
 * Function: ข้อมูลผู้ลงทะเบียนสอบทั้งหมด
 * Description: ระบบจัดการข้อมูลผู้สมัครสอบ พร้อมสถานะการเข้าสอบและระบบแก้ไขข้อมูล
 * Author: Chokena (STC Developers)
 * Update Date: 2026-03-20
 * ==============================================================================
 */
 require('../config.inc.php');
 mysqli_set_charset($conn,"utf8");
?>

<style>
    /* UI Enhancements */
    .card-std {
        background: #fff; border-radius: 12px; border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 20px;
    }
    .box-header { padding: 20px; border-bottom: 1px solid #f4f4f4; }
    .box-title { font-weight: 600; color: #333; font-size: 18px; }
    
    /* Status Badge & Icons */
    .status-icon { width: 22px; transition: 0.3s; }
    .status-icon:hover { transform: scale(1.2); }
    
    /* Export Section */
    .export-btn {
        display: inline-flex; align-items: center; background: #f8f9fa;
        padding: 5px 15px; border-radius: 8px; border: 1px solid #ddd;
        color: #1d6f42; font-weight: 600; transition: 0.3s;
    }
    .export-btn:hover { background: #e2e6ea; text-decoration: none; color: #155130; }

    /* Modal Styling */
    .modal-content { border-radius: 15px; border: none; }
    .modal-header { background: #3c8dbc; color: white; border-radius: 15px 15px 0 0; }
    .modal-title { font-weight: 600; }
    .close { color: white; opacity: 1; }
</style>

<section class="content-header">
    <h1>
        <i class="fa fa-users text-primary"></i> ข้อมูลผู้ลงทะเบียน
        <small>รายชื่อผู้สมัครสอบทั้งหมดในระบบ</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="home_menu.php"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
        <li class="active">ข้อมูลผู้ลงทะเบียนสอบ</li>
    </ol>
</section>

<section class="content"> 
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="box card-std">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-sm-8">
                                <h3 class="box-title">รายชื่อผู้สมัครสอบ</h3>
                            </div>
                            <div class="col-sm-4 text-right">
                                <a href="export_excel_data_stu.php?lvl=2" class="export-btn">
                                    <img src="../images/excel.jpg" width="25" style="margin-right:8px;"> ส่งออกข้อมูล Excel
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="box-body table-responsive">
                        <table id="example1" class="table table-hover table-striped" style="width:100%">
                            <thead>
                                <tr class="bg-gray-light">
                                    <th width="5%">#</th>
                                     
                                    <th>รหัสผู้สมัคร</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>สาขา</th>
                                    <th>ระดับชั้น</th>
                                    <th class="text-center">สถานะ</th>
                                    <th class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i=1;
                                    $sql = "SELECT * FROM tb_user_new where level='2' or level ='3' ORDER BY de_id";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $score = $row['score'];
                                            // กำหนดไอคอนตามสถานะ
                                            if($score == 0) { $pic = "icon_play.png"; $can_edit = false; }
                                            else if($score == -1) { $pic = "icon_false.png"; $can_edit = true; }
                                            else { $pic = "icon_true.png"; $can_edit = false; }

                                            // กำหนดระดับชั้น
                                            $lvl = $row['level'];
                                            $data_level = ($lvl == 1) ? "ปวช." : (($lvl == 3) ? "ปวส./ ปวช." : (($lvl == 2) ? "ปวส./ ม.6" : "ป.ตรี"));
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>  
                                    <td><b class="text-primary"><?php echo $row['id']; ?></b></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['major']; ?></td>
                                    <td><span class="label label-default"><?php echo $data_level; ?></span></td>
                                    <td class="text-center">
                                        <img src="../images/<?php echo $pic; ?>" class="status-icon" title="สถานะสอบ">
                                    </td>
                                    <td class="text-center"> 
                                        <?php if($can_edit) { ?>  
                                            <button type="button" class="btn btn-warning btn-sm btn-flat" 
                                                data-toggle="modal" 
                                                data-target="#exampleModal" 
                                                data-whatever="<?php echo $row['id']; ?>"
                                                data-stdid="<?php echo $row['std_id']; ?>"
                                                data-stdfname="<?php echo $row['std_fname']; ?>"
                                                data-stdlname="<?php echo $row['std_lname']; ?>"
                                                data-stdselect = "<?php echo $data_level; ?>">
                                                <i class="fa fa-edit"></i> แก้ไข
                                            </button>
                                        <?php } else { ?>
                                            <span class="text-muted" style="font-size:11px;">ล็อคข้อมูลแล้ว</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php $i++; }} ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="edit_data_std_all.php" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-user-edit"></i> แก้ไขข้อมูลผู้สมัคร</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>รหัสข้อมูล:</label>
                            <input type="text" class="form-control" id="id" name="id" readonly style="background:#eee;">
                        </div>
                        <div class="form-group col-md-6">
                            <label>รหัสผู้สมัคร:</label>
                            <input type="text" class="form-control" id="stdid" name="std_id">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>ชื่อ:</label>
                            <input type="text" class="form-control" id="stdfname" name="std_fname">
                        </div>
                        <div class="form-group col-md-6">
                            <label>นามสกุล:</label>
                            <input type="text" class="form-control" id="stdlname" name="std_lname">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>ระดับการศึกษา:</label>
                        <input type="text" class="form-control" id="stdlevel" name="std_level" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript">
$('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var modal = $(this);
    modal.find('.modal-body #id').val(button.data('whatever'));
    modal.find('.modal-body #stdid').val(button.data('stdid'));
    modal.find('.modal-body #stdfname').val(button.data('stdfname'));
    modal.find('.modal-body #stdlname').val(button.data('stdlname'));
    modal.find('.modal-body #stdlevel').val(button.data('stdselect'));
});
</script>