<?php
  require("../config.inc.php"); 
  mysqli_set_charset($conn,"utf8");

  // ดึงข้อมูลกลุ่มวิชามาแสดงใน Dropdown
  $sql_group = "SELECT * FROM tb_group_test ORDER BY idgroup ASC";
  $res_group = $conn->query($sql_group);
?>

<div class="content-wrapper">
    <section class="content-header">
      <h1>
        ระบบทดสอบวัดความรู้
        <small>วิทยาลัยเทคนิคสุพรรณบุรี</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home_menu.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">เพิ่มข้อสอบเข้าสู่ระบบ</li>
      </ol>
    </section>

    <section class="content">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">เพิ่มข้อสอบเข้าสู่ระบบ</h3>
        </div>
        
        <form class="form-horizontal" name="loginform" id="loginform" 
              action="action_test.php?<?php if(isset($_REQUEST['ac']) && $_REQUEST['ac']=='edit'){ echo "action=update"; }else{ echo "action=addnew"; } ?>&ref=1" 
              ENCTYPE="multipart/form-data" method="post">
          
          <div class="box-body">
            
            <div class="form-group row">
              <label class="col-sm-2 control-label text-red">รูป คำถาม</label>
              <div class="col-xs-4">
                <input name="userfile[0]" type="file" id="userfile[0]" class="form-control" />
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 control-label">รูป คำตอบที่ 1</label>
              <div class="col-xs-4">
                <input name="userfile[1]" type="file" id="userfile[1]" class="form-control" />
              </div>
              <label class="col-sm-2 control-label">รูป คำตอบที่ 2</label>
              <div class="col-xs-4">
                <input name="userfile[2]" type="file" id="userfile[2]" class="form-control" />
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 control-label">รูป คำตอบที่ 3</label>
              <div class="col-xs-4">
                <input name="userfile[3]" type="file" id="userfile[3]" class="form-control" />
              </div>
              <label class="col-sm-2 control-label">รูป คำตอบที่ 4</label>
              <div class="col-xs-4">
                <input name="userfile[4]" type="file" id="userfile[4]" class="form-control" />
              </div>
            </div>

            <hr>

            <div class="form-group">
              <label class="col-sm-2 control-label">โจทย์คำถาม (Text)</label>
              <div class="col-sm-9">
                <textarea name="q_t" class="form-control" rows="3" placeholder="พิมพ์โจทย์คำถามที่นี่..."></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">คำตอบที่ 1</label>
              <div class="col-sm-9">
                <input name="c1" type="text" class="form-control" placeholder="ข้อความตัวเลือกที่ 1" />
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">คำตอบที่ 2</label>
              <div class="col-sm-9">
                <input name="c2" type="text" class="form-control" placeholder="ข้อความตัวเลือกที่ 2" />
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">คำตอบที่ 3</label>
              <div class="col-sm-9">
                <input name="c3" type="text" class="form-control" placeholder="ข้อความตัวเลือกที่ 3" />
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">คำตอบที่ 4</label>
              <div class="col-sm-9">
                <input name="c4" type="text" class="form-control" placeholder="ข้อความตัวเลือกที่ 4" />
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label text-green">เฉลยข้อที่</label>
              <div class="col-sm-3">
                <select name="answer" class="form-control">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                </select>
              </div>

              <label class="col-sm-2 control-label text-blue">ชุดของคำถาม (วิชา)</label>
              <div class="col-sm-4">
                <select name="idgrouptest" class="form-control select2">
                  <?php while($row = $res_group->fetch_assoc()) { ?>
                    <option value="<?php echo $row['idgroup']; ?>">
                        <?php echo $row['idgroup']; ?> - <?php echo $row['subject']; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>

          </div> <div class="box-footer text-center">
            <button type="submit" class="btn btn-warning btn-lg">
                <i class="fa fa-save"></i> บันทึกข้อมูลข้อสอบ
            </button>
            <button type="reset" class="btn btn-default btn-lg">ยกเลิก</button>
          </div>
        </form>
      </div>
    </section>
</div>

<script>
  $(function () {
    $(".select2").select2();
    // ถ้ามี iCheck ให้เปิดใช้งาน
    if (typeof $('input').iCheck === 'function') {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-red',
          increaseArea: '20%' 
        });
    }
  }); 
</script>