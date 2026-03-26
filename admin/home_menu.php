<?php
session_start();
// ตรวจสอบการ Login
// if(!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

require('../config.inc.php');
mysqli_set_charset($conn, "utf8");

$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';

// --- ส่วนการดึงข้อมูลสถิติ (Query) ---
$sql_total = "SELECT COUNT(id) AS num FROM tb_user_test WHERE score > 0";
$num_total = $conn->query($sql_total)->fetch_assoc()['num'] ?? 0;

$sql_all = "SELECT COUNT(id) AS num FROM tb_user_new";
$countstdall = $conn->query($sql_all)->fetch_assoc()['num'] ?? 0;

$levels = [
    'ปวช.' => "SELECT COUNT(id) AS num FROM tb_user_new WHERE level=1",
    'ปวส.' => "SELECT COUNT(id) AS num FROM tb_user_new WHERE level IN (2,3)",
    'ทลบ.' => "SELECT COUNT(id) AS num FROM tb_user_new WHERE level=4"
];
$countstd = $conn->query($levels['ปวช.'])->fetch_assoc()['num'] ?? 0;
$countstd1 = $conn->query($levels['ปวส.'])->fetch_assoc()['num'] ?? 0;
$countstd2 = $conn->query($levels['ทลบ.'])->fetch_assoc()['num'] ?? 0;

$sql_notest = "SELECT COUNT(id) AS num FROM tb_user_test WHERE score = 0";
$num_test = $conn->query($sql_notest)->fetch_assoc()['num'] ?? 0;

$sql_major = "SELECT COUNT(de_id) AS num FROM tb_depart WHERE publice = 1";
$nummajor = $conn->query($sql_major)->fetch_assoc()['num'] ?? 0;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ระบบจัดการการทดสอบ | วิทยาลัยเทคนิคสุพรรณบุรี</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../dist/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body, h1, h2, h3, h4, h5, h6, .main-sidebar, .content-header {
            font-family: 'Prompt', sans-serif !important;
        }
        .main-header .logo { font-weight: 600; letter-spacing: 1px; }
        .sidebar-menu li.header { color: #8aa4af; background: #1a2226; padding: 12px 15px; }
        .content-wrapper { background-color: #ecf0f5; min-height: 90vh; }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <a href="home_menu.php" class="logo">
            <span class="logo-mini"><b>S</b>TC</span>
            <span class="logo-lg"><b>EXAM</b> SYSTEM</span>
        </a>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <?php require_once('showname.php'); ?>
                    <li><a href="../logout.php"><i class="fa fa-sign-out"></i> ออกจากระบบ</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../images/logo_web.png" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>Admin STC</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> ระบบออนไลน์</a>
                </div>
            </div>

            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">เมนูหลัก</li>
                <li class="<?= ($page == '') ? 'active' : '' ?>">
                    <a href="home_menu.php"><i class="fa fa-dashboard"></i> <span>หน้าแรก (Dashboard)</span></a>
                </li>
                 <li><a href="home_menu.php?page=show_grap"><i class="fa fa-pie-chart"></i> <span>กราฟวิเคราะห์ข้อมูล</span></a></li>
                <li class="header">รายงานและคะแนน</li>
                <li class="<?= ($page == 'show_score_std') ? 'active' : '' ?>">
                    <a href="home_menu.php?page=show_score_std">
                        <i class="fa fa-list-alt text-aqua"></i> <span>แสดงคะแนนสอบ</span>
                        <span class="pull-right-container"><span class="label label-primary pull-right"><?php echo $num_total; ?></span></span>
                    </a>
                </li>
                <li class="<?= ($page == 'show_test_std') ? 'active' : '' ?>">
                    <a href="home_menu.php?page=show_test_std">
                        <i class="fa fa-clock-o text-orange"></i> <span>กำลังสอบตอนนี้</span>
                        <span class="pull-right-container"><span class="label label-primary pull-right"><?php echo $num_test; ?></span></span>
                    </a>
                </li>
                <li>
  <a href="home_menu.php?page=show_exam_log">
    <i class="fa fa-history text-purple"></i> <span>ประวัติการเข้าสอบ</span>
  </a>
</li>  
                <li class="header">จัดการข้อมูล</li>
                <li class="treeview <?= in_array($page, ['show_data_std_all','show_data_std','show_data_std1','show_data_std2']) ? 'active' : '' ?>">
                    <a href="#">
                        <i class="fa fa-files-o"></i> <span>ข้อมูลผู้สมัคร</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="home_menu.php?page=show_data_std_all"><i class="fa fa-circle-o"></i> ทั้งหมด (<?php echo $countstdall; ?>)</a></li>
                        <li><a href="home_menu.php?page=show_data_std"><i class="fa fa-circle-o"></i> ระดับ ปวช. (<?php echo $countstd; ?>)</a></li>
                        <li><a href="home_menu.php?page=show_data_std1"><i class="fa fa-circle-o"></i> ระดับ ปวส. (<?php echo $countstd1; ?>)</a></li>
                        <li><a href="home_menu.php?page=show_data_std2"><i class="fa fa-circle-o"></i> ระดับ ทลบ. (<?php echo $countstd2; ?>)</a></li>
                    </ul>
                </li>
                
                <li><a href="home_menu.php?page=show_major"><i class="fa fa-university"></i> <span>สาขาวิชาในระบบ</span></a></li>
                <li><a href="home_menu.php?page=manage_std"><i class="fa fa-plus-circle text-yellow"></i> <span>เพิ่มข้อมูลผู้สมัคร</span></a></li>
                <li>
  <a href="home_menu.php?page=import_user">
    <i class="fa fa-upload text-aqua"></i> <span>นำเข้าข้อมูลผู้สอบ</span>
  </a>
</li>
<li>
  <a href="javascript:void(0);" onclick="confirmClearAll()">
    <i class="fa fa-trash text-red"></i> <span>ล้างข้อมูลระบบ</span>
  </a>
  
</li>
                <li class="header">ข้อสอบ</li>
                
                <li><a href="home_menu.php?page=add_test"><i class="fa fa-edit"></i> <span>ระบบจัดการข้อสอบ</span></a></li>
           <li>  
    <a href="home_menu.php?page=view_test">
        <i class="fa fa-folder-open"></i> <span>คลังข้อสอบ (แยกวิชา)</span>
    </a>
</li>
           
              </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <?php 
            if($page == "") {
                include('dashboard.php'); 
            } else {
                $file = $page . '.php';
                if(file_exists($file)) {
                    include($file);
                } else {
                    echo "<section class='content'><div class='alert alert-danger'>ไม่พบไฟล์ที่ต้องการ (404 Not Found)</div></section>";
                }
            }
        ?>
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs"><b>Version</b> 2.4.0</div>
        <strong>Copyright &copy; 2026 <a href="http://www.stc.ac.th">วิทยาลัยเทคนิคสุพรรณบุรี</a>.</strong> 
        Develop by Chokena
    </footer>

</div>  
</body>
<script src="../bower_components/jquery/dist/jquery.min.js"></script>

<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script src="../dist/js/adminlte.min.js"></script>

<script>
  $(function () {
    // ใช้คำสั่งนี้เพื่อเปิดการแบ่งหน้าให้กับทุกตารางที่มี class 'datatable'
    $('.table').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "language": {
        "sEmptyTable":     "ไม่มีข้อมูลในตาราง",
        "sInfo":           "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
        "sInfoEmpty":      "แสดง 0 ถึง 0 จากทั้งหมด 0 รายการ",
        "sLengthMenu":     "แสดง _MENU_ รายการ",
        "sSearch":         "ค้นหา:",
        "oPaginate": {
            "sFirst":    "หน้าแรก",
            "sLast":     "หน้าสุดท้าย",
            "sNext":     "ถัดไป",
            "sPrevious": "ก่อนหน้า"
        }
      }
    });
  });

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  function confirmClearAll() {
    Swal.fire({
        title: 'ยืนยันการล้างข้อมูลระบบ?',
        text: "ข้อมูลการสอบและประวัติทั้งหมดจะถูกลบและไม่สามารถกู้คืนได้!",
        icon: 'warning',
        input: 'password', // เพิ่มช่องกรอกรหัสผ่าน
        inputPlaceholder: 'กรุณากรอกรหัสผ่านเพื่อยืนยัน',
        inputAttributes: {
            autocapitalize: 'off',
            autocorrect: 'off'
        },
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ยืนยันการลบ',
        cancelButtonText: 'ยกเลิก',
        preConfirm: (password) => {
            // ตรวจสอบรหัสผ่านที่นี่
            if (password === 'itsuphan') {
                return true;
            } else {
                Swal.showValidationMessage('รหัสผ่านไม่ถูกต้อง!');
                return false;
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // ส่งไปที่ไฟล์ PHP สำหรับล้างข้อมูล (ปรับ URL ตามจริงของคุณ)
            window.location.href = 'delete_all_user.php?confirm=true';
        }
    })
}
 
/*

function confirmClearAll() {
    Swal.fire({
        title: 'ยืนยันการล้างข้อมูลระบบ?',
        text: "ข้อมูลการสอบและประวัติทั้งหมดจะถูกลบและไม่สามารถกู้คืนได้!",
        icon: 'warning',
        input: 'password', // เพิ่มช่องกรอกรหัสผ่าน
        inputPlaceholder: 'กรุณากรอกรหัสผ่านเพื่อยืนยัน',
        inputAttributes: {
            autocapitalize: 'off',
            autocorrect: 'off'
        },
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ยืนยันการลบ',
        cancelButtonText: 'ยกเลิก',
        preConfirm: (password) => {
            // ตรวจสอบรหัสผ่านที่นี่
            if (password === 'itsuphan') {
                return true;
            } else {
                Swal.showValidationMessage('รหัสผ่านไม่ถูกต้อง!');
                return false;
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // ส่งไปที่ไฟล์ PHP สำหรับล้างข้อมูล (ปรับ URL ตามจริงของคุณ)
            window.location.href = 'clear_data.php?action=all';
        }
    })
}
*/
</script>
</html>