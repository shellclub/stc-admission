<?php
/**
 * ==============================================================================
 * Function: Export Student Data (Level Filtered) - No Citizen ID
 * Description: ส่งออกข้อมูลผู้สมัครแยกตามระดับชั้น โดยไม่แสดงรหัสบัตรประชาชน
 * Author: Chokena (STC Developers)
 * Update Date: 2026-03-20
 * ==============================================================================
 */

// 1. ตั้งค่า Header สำหรับ Excel
date_default_timezone_set('Asia/Bangkok');
$dates = date('d-m-Y_H-i');

// 2. รับค่าระดับชั้นจาก URL (lvl: 0=ทั้งหมด, 1=ปวช, 2=ปวส, 4=ป.ตรี)
$level_filter = isset($_GET['lvl']) ? (int)$_GET['lvl'] : 0;
$filename_suffix = "All";

if ($level_filter == 1) $filename_suffix = "ปวช"; 
elseif ($level_filter == 2) $filename_suffix = "ปวส"; 
elseif ($level_filter == 4) $filename_suffix = "ป.ตรี"; 

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ข้อมูลนักศึกษา_" . $filename_suffix . "_$dates.xls");
header("Pragma: no-cache");
header("Expires: 0");

// 3. เชื่อมต่อฐานข้อมูล
require('../config.inc.php');
mysqli_set_charset($conn, "utf8");

?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
    <style>
        .header-bg { background-color: #3c8dbc; color: #ffffff; font-weight: bold; text-align: center; height: 30px; }
        .text-center { text-align: center; }
        .text-red { color: #ff0000; }
    </style>
</head>
<body>
    <table x:str border="1">
        <thead>
            <tr>
                <th colspan="7" style="font-size: 16px; font-weight: bold; height: 40px; vertical-align: middle;">
                    รายงานข้อมูลผู้ลงทะเบียนสอบ (<?php 
                        if($level_filter == 0) echo "ทั้งหมด";
                        elseif($level_filter == 1) echo "ระดับ ปวช.";
                        elseif($level_filter == 2) echo "ระดับ ปวส.";
                        elseif($level_filter == 4) echo "ระดับ ป.ตรี";
                    ?>)
                </th>
            </tr>
            <tr>
                <th class="header-bg" style="width: 50px;">ลำดับ</th>
                <th class="header-bg" style="width: 120px;">รหัสผู้สมัคร</th>
                <th class="header-bg" style="width: 200px;">ชื่อ-นามสกุล</th>
                <th class="header-bg" style="width: 250px;">สาขาวิชาที่สอบ</th>
                <th class="header-bg" style="width: 100px;">ระดับชั้น</th>
                <th class="header-bg" style="width: 120px;">สถานะ</th>
                <th class="header-bg" style="width: 80px;">คะแนน</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            
            // 4. สร้าง SQL ตามเงื่อนไข Level
            if ($level_filter == 0) {
                $sql = "SELECT * FROM tb_user_new ORDER BY level ASC, de_id ASC, id ASC";
            } elseif ($level_filter == 1) {
                $sql = "SELECT * FROM tb_user_new WHERE level = 1 ORDER BY de_id ASC, id ASC";
            } elseif ($level_filter == 2) {
                $sql = "SELECT * FROM tb_user_new WHERE level IN (2,3) ORDER BY de_id ASC, id ASC";
            } elseif ($level_filter == 4) {
                $sql = "SELECT * FROM tb_user_new WHERE level = 4 ORDER BY de_id ASC, id ASC";
            }

            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $score = $row['score'];
                    
                    // แปลงข้อความระดับชั้น
                    $lvl = $row['level'];
                    $data_level = ($lvl == 1) ? "ปวช." : (($lvl == 3) ? "ปวส./ ปวช." : (($lvl == 2) ? "ปวส./ ม.6" : "ป.ตรี"));

                    // จัดการสถานะและคะแนน
                    if($score > 0) {
                        $status_text = "สอบเรียบร้อย";
                        $display_score = $score;
                        $color = "";
                    } elseif($score == 0) {
                        $status_text = "รอเข้าสอบ";
                        $display_score = "0";
                        $color = "text-red";
                    } else {
                        $status_text = "ยังไม่ส่งคำตอบ";
                        $display_score = "-";
                        $color = "text-red";
                    }
            ?>
            <tr>
                <td class="text-center"><?php echo $i; ?></td>
                <td x:str class="text-center"><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['major']; ?></td>
                <td class="text-center"><?php echo $data_level; ?></td>
                <td class="text-center <?php echo $color; ?>"><?php echo $status_text; ?></td>
                <td class="text-center <?php echo $color; ?>" style="font-weight: bold;"><?php echo $display_score; ?></td>
            </tr>
            <?php 
                    $i++; 
                } 
            } else {
                echo "<tr><td colspan='7' class='text-center'>ไม่มีข้อมูลในระดับที่เลือก</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>