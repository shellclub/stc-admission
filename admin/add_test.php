<?php
  require("../config.inc.php"); 
  mysqli_set_charset($conn,"utf8");
?>

<style>
    /* ปรับพื้นหลัง Wrapper ทั้งหมด */
    .content-wrapper { 
        background-color: #f4f6f9 !important; 
        min-height: 100vh;
    }

    /* ปรับแต่ง Container หลักให้เต็มหน้าจอ */
    .full-content {
        padding: 20px 15px !important;
        margin: 0 auto !important;
        width: 100% !important;
    }

    /* ปรับแต่งกล่อง Box ให้ดูเด่นและตรงกลาง */
    .box-custom { 
        border-radius: 12px; 
        border: none; 
        box-shadow: 0 0 20px rgba(0,0,0,0.1); 
        background: #fff;
        margin-bottom: 30px;
    }

    .box-header { 
        padding: 20px 25px; 
        border-bottom: 1px solid #f0f0f0 !important;
    }

    /* หัวข้อ Section */
    .section-title { 
        font-size: 18px; 
        font-weight: 700; 
        color: #2d3748; 
        margin: 30px 0 20px 0; 
        border-left: 6px solid #f39c12; 
        padding-left: 15px; 
    }

    /* กล่องตัวเลือก */
    .choice-section { 
        background: #ffffff; 
        padding: 20px; 
        border-radius: 15px; 
        border: 1px solid #e2e8f0; 
        margin-bottom: 20px;
        transition: 0.3s;
    }
    
    .input-flat {
        border-radius: 8px !important;
        border: 1px solid #d1d5db !important;
        padding: 10px 12px !important;
        height: auto !important;
        width: 100%;
    }
</style>

<div class="content ">
    <section class="content-header" style="padding: 25px 15px;">
        <h1 style="font-weight: 700; color: #1a202c;">
            <i class="fa fa-plus-circle text-orange"></i> เพิ่มข้อสอบใหม่ 
            <small style="color: #718096;">ระบบจัดการฐานข้อมูลคำถาม</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid box-custom">
                    <form action="action_test.php?<?php if(isset($_REQUEST['ac']) && $_REQUEST['ac']=='edit'){ echo "action=update"; }else{ echo "action=addnew"; } ?>&ref=1" 
                          ENCTYPE="multipart/form-data" method="post">
                        
                        <div class="box-body" style="padding: 35px;">
                            
                            <div class="row" style="background: #fff9f0; padding: 25px; margin: 0 0 30px 0; border-radius: 15px; border: 1px solid #fee2e2;">
                                <div class="col-md-4">
                                    <label style="font-weight: 600; color: #4a5568;">รหัสชุดคำถาม (ID Group)</label>
                                    <input type="text" name="idgrouptest" class="form-control input-flat" placeholder="เช่น 101" required>
                                </div>
                                <div class="col-md-8">
                                    <label style="font-weight: 600; color: #4a5568;">ชื่อวิชา / หัวข้อชุดคำถาม (Subject)</label>
                                    <input type="text" name="subject" class="form-control input-flat" placeholder="ระบุชื่อวิชาหรือคำอธิบายชุดข้อสอบ" required>
                                </div>
                            </div>

                            <div class="section-title">1. โจทย์คำถาม</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="choice-section" style="border-top: 5px solid #f39c12;">
                                        <textarea name="q_t" class="form-control input-flat" rows="4" placeholder="พิมพ์โจทย์คำถามที่นี่..."></textarea>
                                        <div style="margin-top: 20px; background: #f8fafc; padding: 15px; border-radius: 10px;">
                                            <label style="color: #4a5568;"><i class="fa fa-image"></i> รูปภาพประกอบโจทย์ (ถ้ามี)</label>
                                            <input name="userfile[0]" type="file" style="margin-top: 5px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="section-title">2. ตัวเลือกคำตอบ</div>
                            <div class="row">
                                <?php 
                                $colors = ['#3182ce', '#38a169', '#d69e2e', '#e53e3e'];
                                for($i=1; $i<=4; $i++){ 
                                ?>
                                <div class="col-md-3 col-sm-6">
                                    <div class="choice-section" style="border-top: 5px solid <?php echo $colors[$i-1]; ?>;">
                                        <label style="color: <?php echo $colors[$i-1]; ?>; font-weight: 700; margin-bottom: 10px; display: block;">ตัวเลือกที่ <?php echo $i; ?></label>
                                        <input name="c<?php echo $i; ?>" type="text" class="form-control input-flat" placeholder="ข้อความคำตอบที่ <?php echo $i; ?>" />
                                        <div style="margin-top: 15px;">
                                            <label class="text-muted"><small>รูปภาพประกอบ</small></label>
                                            <input name="userfile[<?php echo $i; ?>]" type="file">
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>

                            <div class="section-title">3. เฉลยคำตอบ</div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div style="background: #f0fff4; padding: 20px; border-radius: 15px; border: 1px solid #c6f6d5;">
                                        <label style="font-weight: 700; color: #276749;">เฉลยข้อที่ถูกต้อง</label>
                                        <select name="answer" class="form-control input-flat" style="font-weight: bold; font-size: 16px;">
                                            <option value="1">ตัวเลือกที่ 1</option>
                                            <option value="2">ตัวเลือกที่ 2</option>
                                            <option value="3">ตัวเลือกที่ 3</option>
                                            <option value="4">ตัวเลือกที่ 4</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div> <div class="box-footer text-center" style="padding: 40px; border-top: 1px solid #f0f0f0; background: #fff; border-radius: 0 0 12px 12px;">
                            <button type="submit" class="btn btn-warning btn-lg" style="border-radius: 50px; padding: 15px 80px; font-weight: 700; font-size: 20px; box-shadow: 0 10px 15px rgba(243, 156, 18, 0.2);">
                                <i class="fa fa-save"></i> บันทึกข้อสอบลงระบบ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>