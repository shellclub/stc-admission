<section class="content-header">
    <h1><i class="fa fa-upload"></i> นำเข้าข้อมูลผู้สอบ <small>tb_user_new</small></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">อัปโหลดไฟล์ CSV</h3>
                </div>
                <form action="import_user_process.php" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="alert alert-info">
                            <h4><i class="icon fa fa-info"></i> รูปแบบคอลัมน์ใน CSV (เรียงลำดับ)</h4>
                              1. id | 2. name | 3. major | 4. de_id | 5. level | 6. score
                        </div>
                        <div class="form-group">
                            <label>เลือกไฟล์ CSV (Encoding: UTF-8)</label>
                            <input type="file" name="file_import" accept=".csv" required>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" name="submit_import" class="btn btn-success btn-block">
                            <i class="fa fa-save"></i> นำเข้าข้อมูลเข้าสู่ระบบ
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">ตัวอย่างข้อมูลในไฟล์ CSV</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr class="bg-gray">
                            <th>id</th>
                            <th>name</th>
                             <th>major</th>
                        </tr>
                        <tr>
                             
                            <td>4214681424</td>
                            <td>จันทร์ทิพย์ นายเฮ่อ</td>
                            <td>ช่างยนต์</td>
                        </tr>
                    </table>
                    <p class="text-muted" style="margin-top:10px;">
                        * หมายเหตุ: ชื่อและสาขาต้องรองรับภาษาไทย (ควรเซฟเป็น CSV UTF-8)
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>