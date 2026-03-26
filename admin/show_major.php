<?php 
/**
 * ==============================================================================
 * Function: รายการสาขาวิชาที่เปิดสอบ (Fixed Version)
 * Description: แสดงข้อมูลสาขาวิชา พร้อมระบบป้องกัน DataTable Error
 * Author: Chokena (STC Developers)
 * Update Date: 2026-03-20
 * ==============================================================================
 */
 require('../config.inc.php');
 mysqli_set_charset($conn,"utf8");
?>

<style>
    /* UI Styling */
    .card-major {
        background: #fff; border-radius: 12px; border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 20px;
    }
    .box-header { padding: 20px; border-bottom: 1px solid #f4f4f4; }
    .box-title { font-weight: 600; color: #333; font-size: 18px; font-family: 'Prompt', sans-serif; }
    
    .export-btn {
        display: inline-flex; align-items: center; background: #fdfdfd;
        padding: 6px 15px; border-radius: 20px; border: 1px solid #28a745;
        color: #28a745; font-weight: 600; transition: 0.3s; font-size: 13px;
    }
    .export-btn:hover { background: #28a745; color: #fff; text-decoration: none; }

    .badge-level { padding: 5px 10px; border-radius: 4px; font-weight: 500; font-size: 11px; }
</style>

<section class="content-header">
    <h1>
        <i class="fa fa-university text-primary"></i> สาขาวิชาที่เปิดสอบ
        <small>จัดการรายชื่อสาขาวิชาในระบบ</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="home_menu.php"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
        <li class="active">สาขาวิชา</li>
    </ol>
</section>

<section class="content"> 
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="box card-major">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-sm-8">
                                <h3 class="box-title">ข้อมูลสาขาที่เปิดสอบทั้งหมด</h3>
                            </div>
                            <div class="col-sm-4 text-right">
                                <a href="export_excel_data.php" class="export-btn">
                                    <i class="fa fa-file-excel-o" style="margin-right:8px;"></i> ส่งออกสาขาวิชา Excel
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="box-body table-responsive">
                        <table id="table_major" class="table table-hover table-striped">
                            <thead>
                                <tr class="bg-gray-light">
                                    <th width="50">#</th>
                                    <th>รหัสสาขา</th>
                                    <th>สาขาวิชา</th>
                                    <th class="text-center">ระดับชั้น</th>
                                    <th class="text-center">รหัสย่อย (Mapping)</th>
                                    <th class="text-center" width="100">จัดการ</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i=1;
                                    $sql = "SELECT * FROM tb_depart WHERE publice = 1 ORDER BY de_type ASC";
                                    $result = $conn->query($sql);
                                    while($row = $result->fetch_assoc()) {
                                        
                                        $prefix = substr($row['de_type'], 0, 1);
                                        $level_name = ""; $badge_class = "";

                                        if($prefix == '2') { $level_name = "ปวช."; $badge_class = "label-info"; } 
                                        else if($prefix == '3') { $level_name = "ปวส."; $badge_class = "label-primary"; } 
                                        else if($prefix == '4') { $level_name = "ป.ตรี"; $badge_class = "label-success"; } 
                                        else { $level_name = "อื่นๆ"; $badge_class = "label-default"; }
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><code><?php echo $row['de_type']; ?></code></td>
                                    <td><b><?php echo $row['de_name']; ?></b></td>
                                    <td class="text-center">
                                        <span class="label <?php echo $badge_class; ?> badge-level">
                                            <?php echo $level_name; ?>
                                        </span>
                                    </td>
                                    <td class="text-center text-muted">
                                        <i class="fa fa-link"></i> <?php echo $row['id_std_name']; ?>
                                    </td>
                                    <td class="text-center"> 
                                        <button class="btn btn-default btn-xs" title="แก้ไข">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </td> 
                                </tr>
                                <?php $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // แก้ไขปัญหา "Cannot reinitialise DataTable"
    // 1. ตรวจสอบว่าตารางนี้เคยถูกประกาศไปหรือยัง
    if ($.fn.DataTable.isDataTable('#table_major')) {
        // 2. ถ้าเคยแล้ว ให้ทำลายตัวเก่าทิ้งก่อน
        $('#table_major').DataTable().destroy();
    }

    // 3. เริ่มประกาศ DataTable ใหม่พร้อมเมนูภาษาไทย
    $('#table_major').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "language": {
            "lengthMenu": "แสดง _MENU_ รายการ",
            "zeroRecords": "ไม่พบข้อมูล",
            "info": "แสดงรายการที่ _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
            "search": "ค้นหาด่วน:",
            "paginate": {
                "next": "ถัดไป",
                "previous": "ก่อนหน้า"
            }
        }
    });
});
</script>