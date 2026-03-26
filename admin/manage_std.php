<?php
/**
 * Page: add_user.php
 */
require('../config.inc.php');
mysqli_set_charset($conn, "utf8");

// ดึงสาขาแบบไม่ซ้ำกัน (GROUP BY)
$sql_dept = "SELECT id_std_name, de_name 
             FROM tb_depart 
             WHERE publice = 1 
             GROUP BY de_name 
             ORDER BY de_name ASC";
$res_dept = $conn->query($sql_dept);
?>

<style>
    .card-add { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.08); background: #fff; }
    .input-custom { border-radius: 10px !important; padding: 12px 15px !important; height: auto !important; border: 1px solid #e2e8f0 !important; font-size: 15px; }
    .btn-save { border-radius: 10px; padding: 15px; font-weight: 600; font-size: 18px; margin-top: 10px; box-shadow: 0 4px 10px rgba(60,141,188,0.3); }
    .label-title { font-weight: 600; color: #475569; margin-bottom: 8px; display: block; }
</style>

<section class="content-header">
    <h1><i class="fa fa-user-plus text-primary"></i> เพิ่มข้อมูลผู้สมัครสอบ</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box card-add">
                <div class="box-header with-border" style="padding: 20px;">
                    <h3 class="box-title" style="font-weight: bold;">แบบฟอร์มลงทะเบียนนักศึกษาใหม่</h3>
                </div>
                
                <form action="add_user_process.php" method="post" id="userForm">
                    <div class="box-body" style="padding: 30px;">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-title">เลขบัตรประชาชน (13 หลัก)</label>
                                    <input type="text" name="idcard" class="form-control input-custom" maxlength="13" placeholder="กรอกเลขบัตรประชาชน" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label-title">รหัสประจำตัวผู้สอบ (ID)</label>
                                    <input type="text" name="id" class="form-control input-custom" placeholder="กรอกรหัสประจำตัว" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="label-title">ชื่อ-นามสกุล</label>
                            <input type="text" name="name" class="form-control input-custom" placeholder="คำนำหน้า ชื่อ นามสกุล" required>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="label-title">เลือกสาขาวิชา</label>
                                    <select name="de_id" id="de_id_select" class="form-control input-custom select2" style="width: 100%;" onchange="getMajorText(this)" required>
                                        <option value="">-- กรุณาเลือกสาขา --</option>
                                        <?php while($dept = $res_dept->fetch_assoc()): ?>
                                            <option value="<?php echo $dept['id_std_name']; ?>">
                                                <?php echo $dept['de_name']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="label-title">ระดับการศึกษา</label>
                                    <select name="level" class="form-control input-custom" required>
                                        <option value="1">ระดับ ปวช.</option>
                                        <option value="2">ระดับ ปวส. (ม.6)</option>
                                        <option value="3">ระดับ ปวส. (ปวช.)</option>
                                        <option value="4">ระดับ ป.ตรี (ทลบ.)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 15px;">
                            <label class="label-title text-primary">สาขาวิชาที่ระบบจะบันทึก (Major)</label>
                            <input type="text" name="major" id="major_name_val" class="form-control" readonly 
                                   style="background-color: #f8fafc; border: 1px solid #cbd5e1; font-weight: bold; color: #1e293b;" 
                                   placeholder="กรุณาเลือกสาขาด้านบน..." required>
                        </div>

                    </div>

                    <div class="box-footer" style="padding: 20px 30px; background: #f8fafc; border-radius: 0 0 15px 15px;">
                        <button type="submit" class="btn btn-primary btn-save btn-block">
                            <i class="fa fa-save"></i> ยืนยันบันทึกข้อมูลนักศึกษา
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
// ฟังก์ชัน Native JS ดึงค่า Text จาก Select
function getMajorText(sel) {
    var text = sel.options[sel.selectedIndex].text;
    if (text != "" && text != "-- กรุณาเลือกสาขา --") {
        document.getElementById('major_name_val').value = text;
    } else {
        document.getElementById('major_name_val').value = "";
    }
}

$(document).ready(function() {
    // เรียกใช้ Select2 เพื่อความสวยงาม
    $('.select2').select2();

    // ดักจับจังหวะ Submit อีกครั้งเพื่อความแม่นยำ 100%
    $('#userForm').on('submit', function() {
        var sel = document.getElementById('de_id_select');
        var text = sel.options[sel.selectedIndex].text;
        if(text != "" && text != "-- กรุณาเลือกสาขา --") {
            document.getElementById('major_name_val').value = text;
        }
        
        if(document.getElementById('major_name_val').value == "") {
            alert("กรุณาเลือกสาขาวิชาให้ถูกต้อง");
            return false;
        }
        return true;
    });
});
</script>