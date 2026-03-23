<?php
require('../config.inc.php');
mysqli_set_charset($conn, "utf8");

// --- ดึงข้อมูลสถิติ ---
$sql = "SELECT 
            COUNT(id) as total_users, 
            MAX(score) as max_s, 
            MIN(NULLIF(score, 0)) as min_s, 
            AVG(score) as avg_s 
        FROM tb_user_new";
$res = $conn->query($sql)->fetch_assoc();

$num_total = $res['total_users'] ?? 0;
$num_max   = $res['max_s'] ?? 0;
$num_min   = $res['min_s'] ?? 0;
$avg_num   = $res['avg_s'] ?? 0;

// จำนวนนักศึกษาทั้งหมด (จากตารางหลัก)
$countstd = $conn->query("SELECT COUNT(id) as num FROM tb_user_new")->fetch_assoc()['num'] ?? 1;

// คำนวณเปอร์เซ็นต์ผู้เข้าสอบ
$percent_std = ($num_total / $countstd) * 100;

// จำนวนผู้เข้าสอบวันนี้
$today_date = date('d/m/Y');
$day = $conn->query("SELECT COUNT(id) as num FROM tb_log_test WHERE LEFT(dete_log,10) = '$today_date'")->fetch_assoc()['num'] ?? 0;

// --- เตรียมข้อมูลสำหรับกราฟ (แยกตามแผนก) ---
$name_dep = []; $min_s = []; $max_s = [];
$dept_query = $conn->query("SELECT de_type, de_name FROM tb_depart WHERE de_type ='1'");
while($dept = $dept_query->fetch_assoc()){
    $name_dep[] = $dept['de_name'];
    $d_id = $dept['de_type'];
    
    $stats = $conn->query("SELECT MAX(score) as mx, MIN(NULLIF(score, 0)) as mn FROM tb_user_new WHERE de_id = '$d_id'")->fetch_assoc();
    $max_s[] = $stats['mx'] ?? 0;
    $min_s[] = $stats['mn'] ?? 0;
}
?>

<style>
    .content-wrapper { background: #f4f7f6 !important; }
    /* ปรับแต่ง Small Boxes ให้ดูโมเดิร์นด้วย Gradient */
    .info-box-new {
        border-radius: 15px;
        padding: 20px;
        color: white;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .bg-grad-blue   { background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%); }
    .bg-grad-green  { background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%); }
    .bg-grad-orange { background: linear-gradient(45deg, #fa709a 0%, #fee140 100%); }
    .bg-grad-purple { background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); }
    
    .info-box-new h3 { font-size: 28px; margin: 0; font-weight: bold; z-index: 2; position: relative; }
    .info-box-new p { font-size: 14px; opacity: 0.9; z-index: 2; position: relative; }
    .info-box-new .icon-bg {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 80px;
        opacity: 0.2;
    }
    .card-box {
        background: white;
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Dashboard <small>ภาพรวมระบบทดสอบ</small></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="info-box-new bg-grad-blue">
                    <h3><?php echo $num_max; ?> / <?php echo $num_min; ?></h3>
                    <p>คะแนนสูงสุด / ต่ำสุด</p>
                    <div class="icon-bg"><i class="ion ion-trophy"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="info-box-new bg-grad-green">
                    <h3><?php echo round($percent_std, 1); ?>%</h3>
                    <p>เปอร์เซ็นต์ผู้เข้าสอบ</p>
                    <div class="icon-bg"><i class="ion ion-stats-bars"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="info-box-new bg-grad-orange">
                    <h3><?php echo $day; ?> <small style="color:white">วันนี้</small></h3>
                    <p>ผู้เข้าสอบ (ทั้งหมด <?php echo $num_total; ?>)</p>
                    <div class="icon-bg"><i class="ion ion-person-add"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="info-box-new bg-grad-purple">
                    <h3><?php echo round($avg_num, 2); ?></h3>
                    <p>ค่าเฉลี่ยคะแนนสอบ</p>
                    <div class="icon-bg"><i class="ion ion-ios-calculator"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box card-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"></i> วิเคราะห์คะแนนตามแผนกวิชา</h3>
                    </div>
                    <div class="box-body">
                        <div style="height: 300px;">
                            <canvas id="canvas"></canvas>
                        </div>
                    </div>
                    <div class="box-footer" style="background: transparent; border-top: 1px solid #f4f4f4;">
                        <div class="row">
                            <div class="col-xs-4 text-center">
                                <h4 class="text-red"><b><?php echo $num_max; ?></b></h4>
                                <span>Max Score</span>
                            </div>
                            <div class="col-xs-4 text-center">
                                <h4 class="text-green"><b><?php echo $num_min; ?></b></h4>
                                <span>Min Score</span>
                            </div>
                            <div class="col-xs-4 text-center">
                                <h4 class="text-blue"><b><?php echo round($avg_num,2); ?></b></h4>
                                <span>Average</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('canvas').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($name_dep); ?>,
            datasets: [
                {
                    label: 'คะแนนสูงสุด',
                    data: <?php echo json_encode($max_s); ?>,
                    backgroundColor: '#4facfe',
                    borderRadius: 5
                },
                {
                    label: 'คะแนนต่ำสุด',
                    data: <?php echo json_encode($min_s); ?>,
                    backgroundColor: '#fa709a',
                    borderRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>