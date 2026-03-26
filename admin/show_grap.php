<?php
/**
 * ==============================================================================
 * Function: Advanced Academic Dashboard 2026
 * Description: ระบบวิเคราะห์คะแนนแบบแยกส่วน พร้อมสถิติ 3 อันดับสูงสุด
 * ==============================================================================
 */
require('../config.inc.php');
mysqli_set_charset($conn, "utf8");

// 1. รับค่าระดับชั้น (1=ปวช, 2=ปวส.ม6, 3=ปวส.ปวช, 4=ป.ตรี)
$level_filter = isset($_GET['lvl']) ? (int)$_GET['lvl'] : 1;

// 2. ตั้งค่าคะแนนเต็มและธีมสีตามเงื่อนไข
$full_score = ($level_filter <= 2) ? 60 : 40; 
$level_name = ""; $theme_color = "";

switch($level_filter) {
    case 1: $level_name = "ระดับ ปวช."; $theme_color = "#3498db"; break;
    case 2: $level_name = "ระดับ ปวส. (ม.6)"; $theme_color = "#2ecc71"; break;
    case 3: $level_name = "ระดับ ปวส. (ปวช.)"; $theme_color = "#f39c12"; break;
    case 4: $level_name = "ระดับ ป.ตรี"; $theme_color = "#9b59b6"; break;
}

// 3. ดึงสถิติภาพรวมของระดับที่เลือก
$sql_stats = "SELECT COUNT(id) as total, MAX(score) as mx, MIN(NULLIF(score, 0)) as mn, AVG(NULLIF(score, 0)) as av 
              FROM tb_user_new WHERE level = '$level_filter' AND score > 0";
$res = $conn->query($sql_stats)->fetch_assoc();

$num_total = $res['total'] ?? 0;
$num_max   = $res['mx'] ?? 0;
$num_min   = $res['mn'] ?? 0;
$avg_num   = $res['av'] ?? 0;
$percent_max = ($num_max > 0) ? round(($num_max / $full_score) * 100) : 0;

// 4. ดึงข้อมูล Top 3 (ส่วนที่เพิ่มมา: สำคัญมากสำหรับ Admin)
$sql_top3 = "SELECT name, major, score FROM tb_user_new 
             WHERE level = '$level_filter' AND score > 0 
             ORDER BY score DESC LIMIT 3";
$res_top3 = $conn->query($sql_top3);

// 5. เตรียมข้อมูลกราฟรายแผนก
$name_dep = []; $max_chart = [];
$dept_query = $conn->query("SELECT id_std_name, de_name FROM tb_depart WHERE publice = 1");
while($dept = $dept_query->fetch_assoc()){
    $d_id = $dept['id_std_name'];
    $stats = $conn->query("SELECT MAX(score) as mx FROM tb_user_new WHERE de_id = '$d_id' AND level = '$level_filter' AND score > 0")->fetch_assoc();
    if($stats['mx'] > 0) {
        $name_dep[] = $dept['de_name'];
        $max_chart[] = $stats['mx'];
    }
}
?>

<style>
    .dashboard-card { background: #fff; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: none; margin-bottom: 25px; overflow: hidden; }
    .filter-pill { border-radius: 25px; padding: 8px 20px; margin: 5px; border: 1px solid #eee; background: #fff; transition: 0.3s; color: #666; font-weight: 500; }
    .filter-pill.active { background: <?php echo $theme_color; ?>; color: #fff; border-color: <?php echo $theme_color; ?>; box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
    
    /* Stats Info */
    .mini-stat { padding: 15px; text-align: center; border-radius: 10px; background: #f9f9f9; }
    .mini-stat h4 { margin: 0; font-weight: bold; font-size: 22px; }
    .mini-stat p { margin: 0; font-size: 11px; text-transform: uppercase; color: #888; }

    /* Top 3 List */
    .top-item { display: flex; align-items: center; padding: 12px; border-bottom: 1px solid #f1f1f1; }
    .top-rank { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 15px; color: #fff; }
    .rank-1 { background: #FFD700; } .rank-2 { background: #C0C0C0; } .rank-3 { background: #CD7F32; }

    /* Donut Text */
    .donut-container { position: relative; width: 220px; margin: 20px auto; }
    .donut-text { position: absolute; width: 100%; top: 40%; left: 0; text-align: center; }
    .donut-text b { font-size: 32px; display: block; line-height: 1; }
</style>

<section class="content-header">
    <h1><i class="fa fa-tachometer" style="color: <?php echo $theme_color; ?>"></i> Dashboard <small>วิเคราะห์ผลการสอบ</small></h1>
</section>

<section class="content">
    <div class="text-center" style="margin-bottom: 30px;">
        <a href="?page=show_grap&lvl=1" class="filter-pill <?php echo $level_filter==1?'active':''; ?>">ระดับ ปวช.</a>
        <a href="?page=show_grap&lvl=2" class="filter-pill <?php echo $level_filter==2?'active':''; ?>">ระดับ ปวส. (ม.6)</a>
        <a href="?page=show_grap&lvl=3" class="filter-pill <?php echo $level_filter==3?'active':''; ?>">ระดับ ปวส. (ปวช.)</a>
        <a href="?page=show_grap&lvl=4" class="filter-pill <?php echo $level_filter==4?'active':''; ?>">ระดับ ป.ตรี</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="dashboard-card" style="min-height: 520px;">
                <div class="box-header with-border text-center">
                    <h3 class="box-title">คะแนนสูงสุด: <?php echo $level_name; ?></h3>
                </div>
                <div class="box-body">
                    <div class="donut-container">
                        <canvas id="donutChart"></canvas>
                        <div class="donut-text">
                            <b style="color: <?php echo $theme_color; ?>"><?php echo $percent_max; ?>%</b>
                            <span>ของคะแนนเต็ม</span>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 25px;">
                        <div class="col-xs-6">
                            <div class="mini-stat">
                                <p>คะแนนสูงสุด</p>
                                <h4><?php echo $num_max; ?>/<?php echo $full_score; ?></h4>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="mini-stat">
                                <p>คะแนนเฉลี่ย</p>
                                <h4><?php echo round($avg_num, 1); ?></h4>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px;">
                        <h5 style="font-weight: bold; border-left: 3px solid <?php echo $theme_color; ?>; padding-left: 10px; margin-bottom: 15px;">3 อันดับคะแนนสูงสุด</h5>
                        <?php 
                        $rank = 1;
                        while($top = $res_top3->fetch_assoc()){ 
                        ?>
                        <div class="top-item">
                            <div class="top-rank rank-<?php echo $rank; ?>"><?php echo $rank; ?></div>
                            <div style="flex-grow: 1;">
                                <div style="font-weight: bold; font-size: 13px;"><?php echo $top['name']; ?></div>
                                <div style="font-size: 11px; color: #888;"><?php echo $top['major']; ?></div>
                            </div>
                            <div style="font-weight: bold; color: <?php echo $theme_color; ?>;"><?php echo $top['score']; ?></div>
                        </div>
                        <?php $rank++; } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="dashboard-card" style="min-height: 520px;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bar-chart"></i> คะแนนสูงสุดรายสาขาวิชา (<?php echo $level_name; ?>)</h3>
                </div>
                <div class="box-body">
                    <div style="height: 400px; width: 100%;">
                        <canvas id="barChart"></canvas>
                    </div>
                    <div style="margin-top: 20px; background: #fcfcfc; padding: 15px; border-radius: 10px;">
                        <i class="fa fa-info-circle text-primary"></i> <b>หมายเหตุ:</b> 
                        กราฟแสดงคะแนนสูงสุดของแต่ละสาขาในระดับ <b><?php echo $level_name; ?></b> เพื่อใช้ในการเปรียบเทียบมาตรฐานการสอบ 
                        (จำนวนผู้สอบในระดับนี้: <b><?php echo $num_total; ?></b> คน)
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Prompt', sans-serif";

    // 1. Donut Chart (Percent Score)
    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [<?php echo $percent_max; ?>, <?php echo (100-$percent_max); ?>],
                backgroundColor: ['<?php echo $theme_color; ?>', '#f2f2f2'],
                borderWidth: 0
            }]
        },
        options: { cutout: '82%', plugins: { tooltip: { enabled: false }, legend: { display: false } } }
    });

    // 2. Bar Chart (Major Statistics)
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($name_dep); ?>,
            datasets: [{
                label: 'คะแนนสูงสุด',
                data: <?php echo json_encode($max_chart); ?>,
                backgroundColor: '<?php echo $theme_color; ?>',
                borderRadius: 7,
                barThickness: 25
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: <?php echo $full_score; ?>, grid: { color: '#f5f5f5' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>