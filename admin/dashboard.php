<?php
/**
 * ==============================================================================
 * Function: Dashboard Academic Analytics (Level Filtered)
 * Description: ระบบประมวลผลสถิติคะแนนสอบ แยกตามแผนกวิชาและระดับการศึกษา (ปวช./ปวส./ป.ตรี)
 * Author: Chokena (STC Developers)
 * Update Date: 2026-03-20
 * ==============================================================================
 */

require('../config.inc.php');
mysqli_set_charset($conn, "utf8");

// 1. รับค่าระดับการศึกษา (lvl: 1=ปวช, 2=ปวส, 4=ป.ตรี)
$level_filter = isset($_GET['lvl']) ? (int)$_GET['lvl'] : 1;
$level_name = "";
switch($level_filter) {
    case 1: $level_name = "ระดับ ปวช."; break;
    case 2: $level_name = "ระดับ ปวส. (ม.6)"; break;
    case 3: $level_name = "ระดับ ปวส. (ปวช)"; break;
    case 4: $level_name = "ระดับ ป.ตรี (ทลบ.)"; break;
    default: $level_name = "ระดับ ปวช.";
}

// 2. ดึงข้อมูลสถิติภาพรวม (เฉพาะผู้ที่มีคะแนน > 0)
$sql_stats = "SELECT 
                COUNT(id) as total_users, 
                MAX(score) as max_s, 
                MIN(NULLIF(score, 0)) as min_s, 
                AVG(NULLIF(score, 0)) as avg_s 
              FROM tb_user_new WHERE score > 0";
$res = $conn->query($sql_stats)->fetch_assoc();

$num_total = $res['total_users'] ?? 0;
$num_max   = $res['max_s'] ?? 0;
$num_min   = $res['min_s'] ?? 0;
$avg_num   = $res['avg_s'] ?? 0;

// หาจำนวนผู้สมัครทั้งหมดเพื่อคำนวณ % การเข้าสอบ
$countstd_res = $conn->query("SELECT COUNT(id) as num FROM tb_user_new");
$countstd = ($countstd_res->fetch_assoc()['num']) ?: 1;
$percent_std = ($num_total / $countstd) * 100;

// 3. เตรียมข้อมูลสำหรับกราฟ (กรองเฉพาะ Level ที่เลือก และมีคะแนน > 0)
$name_dep = []; $min_chart = []; $max_chart = [];
$dept_query = $conn->query("SELECT id_std_name, de_name FROM tb_depart WHERE publice = 1");

while($dept = $dept_query->fetch_assoc()){
    $d_id = $dept['id_std_name'];
    $q_chart = "SELECT MAX(score) as mx, MIN(NULLIF(score, 0)) as mn 
                FROM tb_user_new 
                WHERE de_id = '$d_id' AND level = '$level_filter' AND score > 0";
    $stats = $conn->query($q_chart)->fetch_assoc();
    
    if($stats['mx'] > 0) {
        $name_dep[] = $dept['de_name'];
        $max_chart[] = $stats['mx'];
        $min_chart[] = $stats['mn'] ?? 0;
    }
}
?>

<style>
    /* ป้องกันเนื้อหาล้นและปรับแต่งกล่องสถิติ */
    .info-box-new {
        border-radius: 12px; padding: 18px; color: white; margin-bottom: 20px;
        position: relative; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .bg-grad-blue   { background: linear-gradient(45deg, #2193b0, #6dd5ed); }
    .bg-grad-green  { background: linear-gradient(45deg, #11998e, #38ef7d); }
    .bg-grad-orange { background: linear-gradient(45deg, #ee0979, #ff6a00); }
    .bg-grad-purple { background: linear-gradient(45deg, #4568dc, #b06ab3); }
    
    .info-box-new h3 { font-size: 26px; margin: 0 0 5px 0; font-weight: bold; position: relative; z-index: 2; }
    .info-box-new p { font-size: 13px; opacity: 0.9; margin: 0; position: relative; z-index: 2; }
    .info-box-new .icon-bg { position: absolute; right: -5px; bottom: -5px; font-size: 60px; opacity: 0.15; }

    .card-box { background: #fff; border-radius: 12px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
    .filter-tabs { display: flex; gap: 8px; }
    .btn-lvl { border-radius: 15px; padding: 4px 15px; font-size: 13px; border: 1px solid #ddd; background: #fff; color: #666; transition: 0.2s; }
    .btn-lvl.active { background: #3c8dbc; color: #fff; border-color: #3c8dbc; box-shadow: 0 2px 6px rgba(60,141,188,0.3); }
    
    /* แก้ไขการแสดงผล Canvas ให้พอดีกรอบ */
    .chart-container { position: relative; height: 350px; width: 100%; }
</style>

<section class="content-header">
    <h1>แผงควบคุม <small>สรุปผลการทดสอบออนไลน์</small></h1>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="info-box-new bg-grad-blue">
                    <h3><?php echo $num_max; ?> / <?php echo $num_min; ?></h3>
                    <p>คะแนน สูงสุด / ต่ำสุด</p>
                    <div class="icon-bg"><i class="fa fa-star"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="info-box-new bg-grad-green">
                    <h3><?php echo round($percent_std, 1); ?>%</h3>
                    <p>เปอร์เซ็นต์ผู้เข้าสอบวันนี้</p>
                    <div class="icon-bg"><i class="fa fa-check-square-o"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="info-box-new bg-grad-orange">
                    <h3><?php echo $num_total; ?> <small style="color:#fff">คน</small></h3>
                    <p>จำนวนผู้ส่งคำตอบแล้ว</p>
                    <div class="icon-bg"><i class="fa fa-users"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="info-box-new bg-grad-purple">
                    <h3><?php echo round($avg_num, 2); ?></h3>
                    <p>คะแนนเฉลี่ยรวม</p>
                    <div class="icon-bg"><i class="fa fa-calculator"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box card-box">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="font-weight:600;"><i class="fa fa-bar-chart text-primary"></i> ผลคะแนนตามแผนก: <?php echo $level_name; ?></h3>
                        <div class="box-tools">
                            <div class="filter-tabs">
                                <a href="?page=dashboard&lvl=1" class="btn-lvl <?php echo $level_filter==1?'active':''; ?>">ปวช.</a>
                                <a href="?page=dashboard&lvl=2" class="btn-lvl <?php echo $level_filter==2?'active':''; ?>">ปวส. (ม.6) </a>
                                <a href="?page=dashboard&lvl=3" class="btn-lvl <?php echo $level_filter==3?'active':''; ?>">ปวส. (ปวช.)</a>
                                <a href="?page=dashboard&lvl=4" class="btn-lvl <?php echo $level_filter==4?'active':''; ?>">ป.ตรี</a>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if(count($name_dep) > 0): ?>
                            <div class="chart-container">
                                <canvas id="academicChart"></canvas>
                            </div>
                        <?php else: ?>
                            <div class="text-center" style="padding: 60px 0; color: #bbb;">
                                <i class="fa fa-bar-chart fa-3x"></i>
                                <p style="margin-top:10px;">ยังไม่มีข้อมูลผู้เข้าสอบในระดับที่เลือก</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    <?php if(count($name_dep) > 0): ?>
    const ctx = document.getElementById('academicChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($name_dep); ?>,
            datasets: [
                {
                    label: 'คะแนนสูงสุด',
                    data: <?php echo json_encode($max_chart); ?>,
                    backgroundColor: 'rgba(33, 147, 176, 0.8)',
                    borderColor: '#2193b0',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'คะแนนต่ำสุด',
                    data: <?php echo json_encode($min_chart); ?>,
                    backgroundColor: 'rgba(238, 9, 121, 0.7)',
                    borderColor: '#ee0979',
                    borderWidth: 1,
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: '#f5f5f5' } }
            }
        }
    });
    <?php endif; ?>
</script>