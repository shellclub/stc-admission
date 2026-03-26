<?php
require("../config.inc.php");
mysqli_set_charset($conn, "utf8");

// 1. ตั้งค่าการแบ่งหน้า (Pagination)
$per_page = 10; 
$page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page_num < 1) $page_num = 1;
$start_from = ($page_num - 1) * $per_page;

// 2. รับค่า Filter วิชา
$filter_subject = isset($_GET['sub']) ? mysqli_real_escape_string($conn, $_GET['sub']) : '';

// 3. ดึงรายชื่อวิชาทั้งหมดสำหรับ Dropdown
$sql_sub = "SELECT DISTINCT subject FROM tb_test WHERE subject != '' ORDER BY subject ASC";
$res_sub = $conn->query($sql_sub);

// 4. สร้าง Query หลักและนับจำนวนทั้งหมด
$where_clause = ($filter_subject != "") ? " WHERE subject = '$filter_subject'" : "";
$sql_count = "SELECT COUNT(*) as total FROM tb_test" . $where_clause;
$res_count = $conn->query($sql_count);
$total_data = $res_count->fetch_assoc()['total'];
$total_pages = ceil($total_data / $per_page);

$sql_main = "SELECT * FROM tb_test" . $where_clause . " ORDER BY id DESC LIMIT $start_from, $per_page";
$res_test = $conn->query($sql_main);
?>

<style>
    .content-wrapper { background-color: #f1f5f9 !important; }
    .page-header-custom { padding: 30px 20px; background: #fff; border-bottom: 1px solid #e2e8f0; margin-bottom: 25px; }
    
    /* Summary Card */
    .stat-card {
        background: #fff; padding: 20px; border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02); display: flex; align-items: center;
        border-left: 4px solid #f39c12; margin-bottom: 20px;
    }
    .stat-icon { width: 45px; height: 45px; background: #fff7ed; color: #f39c12; 
                 display: flex; align-items: center; justify-content: center; 
                 border-radius: 10px; font-size: 20px; margin-right: 15px; }

    /* Test Item Card */
    .test-item {
        background: #fff; border-radius: 15px; border: 1px solid #e2e8f0;
        margin-bottom: 25px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .test-item-header {
        padding: 15px 25px; background: #fafafa; border-bottom: 1px solid #f1f5f9;
        display: flex; justify-content: space-between; align-items: center;
    }
    .img-container {
        margin-top: 15px; background: #f8fafc; padding: 10px; 
        border-radius: 10px; border: 1px solid #e2e8f0; display: inline-block;
    }
    .file-name-label {
        margin-top: 8px; font-size: 11px; color: #64748b; font-family: 'Courier New', Courier, monospace;
    }

    /* Choice Grid */
    .choice-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px; }
    .choice-box {
        padding: 15px; border-radius: 10px; border: 1.5px solid #f1f5f9; 
        position: relative; transition: 0.2s;
    }
    .choice-active { background: #f0fdf4; border-color: #bbf7d0; }

    /* Pagination */
    .pagination-wrapper { text-align: center; margin: 40px 0; }
    .page-link-custom {
        display: inline-block; padding: 10px 18px; margin: 0 3px; border-radius: 8px;
        background: #fff; border: 1px solid #e2e8f0; color: #475569; text-decoration: none;
    }
    .page-link-custom.active { background: #f39c12; color: #fff; border-color: #f39c12; font-weight: bold; }
</style>

<div class="content">
    <div class="page-header-custom">
        <div class="row">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa fa-database"></i></div>
                    <div>
                        <div style="font-size: 12px; color: #94a3b8; font-weight: 600;">ผลการกรองวิชา</div>
                        <div style="font-size: 22px; font-weight: 700; color: #1e293b;"><?php echo number_format($total_data); ?> <small style="font-size: 14px;">ข้อ</small></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <form method="GET" action="home_menu.php" class="form-inline text-right" style="margin-top: 10px;">
                    <input type="hidden" name="page" value="view_test">
                    <div class="form-group">
                        <label style="margin-right: 10px;">เลือกวิชา: </label>
                        <select name="sub" class="form-control" onchange="this.form.submit()" style="min-width: 250px; border-radius: 8px;">
                            <option value="">-- ทั้งหมด --</option>
                            <?php if($res_sub) { while($s = $res_sub->fetch_assoc()) { 
                                $sel = ($filter_subject == $s['subject']) ? "selected" : "";
                                echo "<option value='{$s['subject']}' $sel>{$s['subject']}</option>";
                            }} ?>
                        </select>
                    </div>
                    <a href="home_menu.php?page=add_test" class="btn btn-warning" style="border-radius: 8px; margin-left: 10px;">
                        <i class="fa fa-plus"></i> เพิ่มข้อสอบ
                    </a>
                </form>
            </div>
        </div>
    </div>

    <section class="content" style="padding: 0 20px;">
        <?php if($res_test && $res_test->num_rows > 0) {
            $idx = $start_from + 1;
            while($row = $res_test->fetch_assoc()) { ?>
            <div class="test-item">
                <div class="test-item-header">
                    <span style="font-weight: 700; font-size: 15px; color: #334155;"># <?php echo $idx++; ?></span>
                    <span class="label" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a;"><?php echo $row['subject']; ?></span>
                </div>
                
                <div style="padding: 25px;">
                    <div style="font-size: 17px; font-weight: 500; color: #1e293b;"><?php echo $row['q_t']; ?></div>

                    <?php if(!empty($row['q_pic'])) { ?>
                        <div class="img-container">
                            <img src="../ImagesQuestions/<?php echo $row['q_pic']; ?>" style="max-width: 100%; max-height: 250px; border-radius: 5px;">
                            <div class="file-name-label"><i class="fa fa-file-image-o"></i> <?php echo basename($row['q_pic']); ?></div>
                        </div>
                    <?php } ?>

                    <div class="choice-grid">
                        <?php for($i=1; $i<=4; $i++) { 
                            $correct = ($row['answer'] == $i);
                            $cpic = $row['c'.$i.'_pic']; ?>
                            <div class="choice-box <?php echo $correct ? 'choice-active' : ''; ?>">
                                <div style="display: flex;">
                                    <span style="font-weight: bold; color: <?php echo $correct ? '#16a34a':'#94a3b8'; ?>; margin-right: 10px;"><?php echo $i; ?>.</span>
                                    <div style="color: #475569;"><?php echo $row['c'.$i]; ?></div>
                                </div>
                                <?php if(!empty($cpic)) { ?>
                                    <div class="img-container" style="margin-top: 10px; background: rgba(0,0,0,0.02); padding: 5px;">
                                        <img src="../ImagesQuestions/<?php echo $cpic; ?>" style="max-height: 80px; border-radius: 4px;">
                                        <div class="file-name-label" style="font-size: 9px;"><?php echo basename($cpic); ?></div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div style="padding: 12px 25px; background: #fafafa; border-top: 1px solid #f1f5f9; text-align: right;">
                    <a href="home_menu.php?page=edit_test&id=<?php echo $row['id']; ?>" class="btn btn-default btn-xs"><i class="fa fa-edit"></i> แก้ไข</a>
                    <button onclick="confirmDel(<?php echo $row['id']; ?>)" class="btn btn-link btn-xs text-danger" style="text-decoration: none;"><i class="fa fa-trash"></i> ลบ</button>
                </div>
            </div>
        <?php } ?>

            <div class="pagination-wrapper">
                <?php if($page_num > 1) { echo '<a href="home_menu.php?page=view_test&p='.($page_num-1).'&sub='.$filter_subject.'" class="page-link-custom"><i class="fa fa-angle-left"></i></a>'; } ?>
                <?php for($i=1; $i<=$total_pages; $i++) {
                    if($i <= 2 || $i > $total_pages - 2 || ($i >= $page_num - 1 && $i <= $page_num + 1)) {
                        echo '<a href="home_menu.php?page=view_test&p='.$i.'&sub='.$filter_subject.'" class="page-link-custom '.($i==$page_num ? 'active':'').'">'.$i.'</a>';
                    } elseif($i == 3 || $i == $total_pages - 2) {
                        echo '<span style="color:#cbd5e1; margin: 0 5px;">...</span>';
                    }
                } ?>
                <?php if($page_num < $total_pages) { echo '<a href="home_menu.php?page=view_test&p='.($page_num+1).'&sub='.$filter_subject.'" class="page-link-custom"><i class="fa fa-angle-right"></i></a>'; } ?>
            </div>

        <?php } else { ?>
            <div style="text-align: center; padding: 100px; background: #fff; border-radius: 15px; border: 2px dashed #e2e8f0;">
                <i class="fa fa-folder-open-o" style="font-size: 60px; color: #cbd5e1;"></i>
                <h4 style="color: #94a3b8; margin-top: 20px;">ไม่พบข้อมูลข้อสอบ</h4>
            </div>
        <?php } ?>
    </section>
</div>

<script>
function confirmDel(id) {
    if(confirm('ยืนยันการลบข้อสอบรหัส ' + id + ' ?')) {
        window.location.href = 'action_test.php?action=delete&id=' + id;
    }
}
</script>