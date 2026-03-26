<?php
/**
 * ==============================================================================
 * Function: Student Exam Score Report (Level: ปวช.)
 * Description: ระบบแสดงผลคะแนนสอบแยกตามสาขาวิชา พร้อมระบบส่งออก Excel
 * Author: Chokena (STC Developers)
 * Update Date: 2026-03-20
 * ==============================================================================
 */

require('../config.inc.php');
mysqli_set_charset($conn, "utf8");

// รับค่ารหัสแผนกจาก URL (ถ้ามี)
$dep_filter = isset($_GET['dep']) ? $_GET['dep'] : "";
?>

<style>
    .card-report {
        background: #fff; border-radius: 12px; border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 20px;
    }
    .box-header { padding: 20px; border-bottom: 1px solid #f4f4f4; }
    .box-title { font-weight: 600; color: #333; font-size: 18px; }
    
    /* ส่วนเลือกสาขาวิชา */
    .filter-section { padding: 15px 20px; background: #fcfcfc; border-bottom: 1px solid #f4f4f4; }
    .btn-dep {
        border-radius: 15px; margin: 2px; font-size: 12px; 
        border: 1px solid #ddd; transition: 0.3s;
    }
    .btn-dep.active { background: #3c8dbc; color: #fff; border-color: #3c8dbc; box-shadow: 0 2px 5px rgba(60,141,188,0.3); }
    
    /* สไตล์ตาราง */
    .table-custom thead th { background: #f9f9f9; color: #777; font-size: 13px; text-transform: uppercase; }
    .score-badge { font-weight: 700; color: #00a65a; font-size: 15px; }
    .score-zero { color: #dd4b39; font-weight: 600; }
</style>

<section class="content-header">
    <h1>
        <i class="fa fa-list-alt text-primary"></i> คะแนนสอบ 
        <small>ระดับประกาศนียบัตรวิชาชีพ (ปวช.)</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="home_menu.php"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
        <li class="active">คะแนนสอบ ปวช.</li>
    </ol>
</section>

<section class="content">
    <div class="container-fluid"> <div class="row">
            <div class="col-xs-12">
                <div class="box card-report">
                    
                    <div class="box-header">
                        <div class="row">
                            <div class="col-sm-7">
                                <h3 class="box-title">รายชื่อผู้เข้าสอบและผลคะแนน</h3>
                            </div>
                            <div class="col-sm-5 text-right">
                                <span class="text-muted" style="margin-right:10px;">ส่งออกไฟล์:</span>
                                <a href="excel_export_n.php" title="ส่งออกทั้งหมด">
                                    <img src="../images/excel.jpg" width="30" style="border-radius:4px; border:1px solid #ddd;">
                                </a>
                                <a href="excel_export_dep.php?dep=<?php echo $dep_filter; ?>" title="ส่งออกเฉพาะสาขา" style="margin-left:5px;">
                                    <img src="../images/excel.jpg" width="30" style="border-radius:4px; border:1px solid #3c8dbc;">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="filter-section">
                        <div style="margin-bottom: 5px; font-weight: 600; color: #888; font-size: 12px;">เลือกระบุตามสาขาวิชา:</div>
                        <a href="home_menu.php?page=show_score_std&dep=" class="btn btn-default btn-dep <?php echo ($dep_filter=='')?'active':''; ?>">แสดงทั้งหมด</a>
                        <?php 
                            $sql_dep = "SELECT id_std_name FROM tb_depart"; 
                            $res_dep = $conn->query($sql_dep);
                            while($row_dep = $res_dep->fetch_assoc()) {
                                $d_name = $row_dep['id_std_name'];
                                $is_active = ($dep_filter == $d_name) ? "active" : "";
                                echo '<a href="home_menu.php?page=show_score_std&dep='.$d_name.'" class="btn btn-default btn-dep '.$is_active.'">'.$d_name.'</a> ';
                            }
                        ?>
                    </div>

                    <div class="box-body table-responsive">
                        <table id="example1" class="table table-hover table-custom">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th>รหัสผู้สอบ</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>สาขาวิชา</th>
                                    <th>ระดับชั้น</th>
                                    <th class="text-center">คะแนน</th>
                                    <th>วันที่-เวลาที่สอบ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 1;
                                    // เงื่อนไขการ Query ข้อมูล
                                    if($dep_filter == "") {
                                        $sql = "SELECT * FROM tb_user_new 
                                                INNER JOIN tb_log_test ON tb_user_new.id = tb_log_test.id 
                                                WHERE score > 0 ORDER BY tb_user_new.level ASC";
                                    } else {
                                        $sql = "SELECT * FROM tb_user_new 
                                                INNER JOIN tb_log_test ON tb_user_new.id = tb_log_test.id 
                                                WHERE score > 0 AND de_id = '$dep_filter' ORDER BY score DESC";
                                    }

                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $score = $row['score'];
                                            
                                            // แปลงค่าระดับชั้น
                                            $lvl_text = "";
                                            if($row['level'] == 1) $lvl_text = "ปวช.";
                                            else if($row['level'] == 2) $lvl_text = "ปวส./ม.6";
                                            else if($row['level'] == 3) $lvl_text = "ปวส./ปวช.";
                                            else if($row['level'] == 4) $lvl_text = "ป.ตรี";
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><b class="text-primary"><?php echo $row['id']; ?></b></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><span class="label label-default" style="font-weight:400;"><?php echo $row['major']; ?></span></td>
                                    <td><?php echo $lvl_text; ?></td>
                                    <td class="text-center">
                                        <span class="<?php echo ($score > 0) ? 'score-badge' : 'score-zero'; ?>">
                                            <?php echo ($score > 0) ? $score : '0'; ?>
                                        </span>
                                    </td>
                                    <td><small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo $row['dete_log']; ?></small></td>
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