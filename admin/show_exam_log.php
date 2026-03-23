<?php
/**
 * Page: show_exam_log.php
 * Description: ปรับปรุงขนาดตัวอักษรและ UI ให้สบายตาและสวยงามยิ่งขึ้น
 */
require('../config.inc.php');
mysqli_set_charset($conn, "utf8");
?>

<style>
    /* ปรับแต่ง Font และขนาดตัวอักษรภาพรวม */
    .content-header h1 { font-size: 28px; font-weight: 600; }
    
    .card-log { 
        border-radius: 15px; 
        border: none; 
        box-shadow: 0 8px 25px rgba(0,0,0,0.05); 
        background: #fff;
    }

    /* ปรับแต่งตารางให้ดูโปร่งและสบายตา */
    .table-log { border-collapse: separate; border-spacing: 0 10px; margin-top: -10px !important; }
    .table-log thead th { 
        background-color: #f8fafc; 
        color: #64748b; 
        font-weight: 600; 
        font-size: 15px; 
        padding: 15px !important;
        border: none !important;
        text-transform: none;
    }
    .table-log tbody tr { 
        background-color: #ffffff; 
        transition: all 0.2s;
    }
    .table-log tbody tr:hover { 
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        background-color: #fcfdfe !important;
    }
    .table-log tbody td { 
        padding: 18px 15px !important; 
        vertical-align: middle !important; 
        font-size: 16px; /* ขนาดตัวอักษรในตาราง */
        border-top: 1px solid #f1f5f9 !important;
    }

    /* ปรับแต่งองค์ประกอบย่อย */
    .id-badge-custom {
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 8px;
        color: #475569;
        font-family: 'Monaco', 'Consolas', monospace;
        font-weight: bold;
    }
    .name-text { font-size: 18px; color: #1e293b; margin-bottom: 2px; }
    .major-text { font-size: 14px; color: #94a3b8; }
    
    .time-box {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        font-size: 15px;
    }

    /* Status Badges */
    .badge-custom {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }
</style>

<section class="content-header">
    <h1>
        <i class="fa fa-history text-purple"></i> ประวัติการเข้าสอบ
        <small style="font-size: 16px;">ตรวจสอบบันทึกเวลาของผู้เข้าสอบ</small>
    </h1>
</section>

<section class="content">
    <div class="box card-log">
        <div class="box-header with-border" style="padding: 20px;">
            <h3 class="box-title" style="font-size: 20px; font-weight: 600;">
                <i class="fa fa-list-ul text-primary"></i> รายการบันทึกการเข้าสอบ
            </h3>
        </div>
        <div class="box-body">
            <table id="table_log" class="table table-log">
                <thead>
                    <tr>
                        <th width="60" class="text-center">ลำดับ</th>
                        <th width="180">รหัสผู้สอบ</th>
                        <th>ข้อมูลผู้เข้าสอบ</th>
                        <th class="text-center">เวลาเข้า (Check-in)</th>
                        <th class="text-center">เวลาส่ง (Check-out)</th>
                        <th class="text-center" width="130">ตัวเลือก</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $sql = "SELECT l.*, u.name, u.major 
                            FROM tb_log_test l
                            LEFT JOIN tb_user_new u ON l.id = u.id 
                            ORDER BY l.date_in DESC"; 
                    
                    $result = $conn->query($sql);
                    if ($result) {
                        while($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td class="text-center text-muted" style="font-weight: 500;"><?php echo $i++; ?></td>
                        <td>
                            <span class="id-badge-custom">
                                <i class="fa fa-id-card-o"></i> <?php echo $row['id']; ?>
                            </span>
                        </td>
                        <td>
                            <div class="name-text"><?php echo $row['name'] ?: 'ไม่ทราบชื่อ (ข้อมูลถูกลบ)'; ?></div>
                            <div class="major-text"><i class="fa fa-map-marker"></i> <?php echo $row['major']; ?></div>
                        </td>
                        <td class="text-center">
                            <div class="time-box text-green">
                                <i class="fa fa-clock-o"></i> <?php echo $row['date_in']; ?>
                            </div>
                        </td>
                        <td class="text-center">
                            <?php if(!empty($row['dete_log'])) { ?>
                                <div class="time-box text-blue">
                                    <i class="fa fa-check-circle-o"></i> <?php echo $row['dete_log']; ?>
                                </div>
                            <?php } else { ?>
                                <span class="badge-custom label-warning" style="color: #92400e; background: #fef3c7; padding: 5px 12px; border-radius: 12px;">
                                    <i class="fa fa-hourglass-half"></i> ยังไม่ส่งข้อสอบ
                                </span>
                            <?php } ?>
                        </td>
                        <td class="text-center">
                            <a href="home_menu.php?page=view_test_detail&id=<?php echo $row['id']; ?>" 
                               class="btn btn-primary btn-sm" 
                               style="border-radius: 8px; padding: 6px 15px; font-weight: 500;">
                                <i class="fa fa-search"></i> รายละเอียด
                            </a>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>เกิดข้อผิดพลาดในการดึงข้อมูล</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#table_log')) {
        $('#table_log').DataTable().destroy();
    }
    $('#table_log').DataTable({
        "order": [[ 0, "asc" ]],
        "pageLength": 10,
        "language": {
            "search": "<span style='font-size:16px;'>ค้นหาข้อมูล:</span>",
            "lengthMenu": "<span style='font-size:15px;'>แสดง _MENU_ รายการ</span>",
            "info": "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
            "paginate": {
                "next": "ถัดไป",
                "previous": "ก่อนหน้า"
            }
        }
    });
});
</script>