<?php 
/**
 * Page: show_current_test.php
 * Description: แสดงรายชื่อผู้ที่กำลังทำข้อสอบอยู่ในขณะนี้
 */
 require('../config.inc.php');
 mysqli_set_charset($conn,"utf8");
?>

<style>
    .card-test { background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: none; }
    .status-badge { padding: 5px 12px; border-radius: 20px; font-weight: 500; font-size: 12px; }
    .table-v-middle td { vertical-align: middle !important; }
</style>

<section class="content-header">
    <h1>
        <i class="fa fa-refresh fa-spin text-primary"></i> ผู้ที่กำลังสอบตอนนี้
        <small>รายชื่อผู้สมัครที่อยู่ระหว่างการทำข้อสอบ</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="home_menu.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">กำลังสอบตอนนี้</li>
    </ol>
</section>

<section class="content"> 
    <div class="row">
        <div class="col-xs-12">
            <div class="box card-test">
                <div class="box-header with-border">
                    <h3 class="box-title">รายการผู้เข้าสอบ (คะแนนยังเป็น 0)</h3>
                    <div class="box-tools pull-right">
                        <a href="export_excel_current.php" class="btn btn-default btn-sm">
                            <i class="fa fa-file-excel-o text-success"></i> Export รายชื่อนี้
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <table id="table_current" class="table table-hover table-v-middle">
                        <thead>
                            <tr class="bg-gray-light">
                                <th width="50">#</th>
                                <th width="120">รหัสผู้สอบ</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>สาขาวิชา</th>
                                <th class="text-center">เวลาที่เริ่มสอบ</th>
                                <th class="text-center">สถานะ</th>
                                <th class="text-center" width="100">จัดการ</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i=1;
                                // ดึงข้อมูลเฉพาะคนที่คะแนนเป็น 0 (กำลังสอบ)
                                $sql = "SELECT tb_user_test.id, tb_user_new.name, tb_user_new.major, 
                                               tb_log_test.date_in, tb_user_test.score 
                                        FROM tb_user_test 
                                        INNER JOIN tb_log_test ON tb_user_test.id = tb_log_test.id 
                                        INNER JOIN tb_user_new ON tb_user_new.id = tb_user_test.id 
                                        WHERE tb_user_test.score = 0";
                                
                                $result = $conn->query($sql);
                                if ($result && $result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><code><?php echo $row['id']; ?></code></td>
                                <td><b><?php echo $row['name']; ?></b></td>
                                <td><?php echo $row['major']; ?></td>
                                <td class="text-center"><?php echo $row['date_in']; ?></td>
                                <td class="text-center">
                                    <span class="label label-warning status-badge">กำลังทำข้อสอบ</span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm" 
                                            onclick="confirmReset('<?php echo $row['id']; ?>', '<?php echo $row['name']; ?>')">
                                        <i class="fa fa-trash"></i> ลบรายการสอบ
                                    </button>
                                </td> 
                            </tr>
                            <?php 
                                    }
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // ป้องกัน DataTable Error
    if ($.fn.DataTable.isDataTable('#table_current')) {
        $('#table_current').DataTable().destroy();
    }

    $('#table_current').DataTable({
        "ordering": true,
        "language": { "search": "ค้นหาด่วน:", "zeroRecords": "ขณะนี้ไม่มีผู้กำลังทำข้อสอบ" }
    });
});

// ฟังก์ชันลบรายการสอบ (Reset)
function confirmReset(id, name) {
    Swal.fire({
        title: 'ยืนยันการลบรายการสอบ?',
        text: "คุณต้องการลบข้อมูลการสอบของ " + name + " (รหัส " + id + ") ใช่หรือไม่? เมื่อลบแล้วนักศึกษาจะสามารถเข้าสอบใหม่ได้",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'ยืนยันการลบ',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // ส่งไปที่ไฟล์ลบ (ใช้ไฟล์เดิมของคุณ หรือสร้างใหม่ตามที่ผมเคยแนะนำ)
            window.location.href = 'del_std_test.php?id=' + id;
        }
    })
}
</script>