<?php
require('../config.inc.php');

echo '<html><head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head><body>';

// เนื่องจากไฟล์นี้จะรันทันทีเมื่อถูกเรียก เราจะใช้ JS ของ SweetAlert ถามก่อน
?>
<script>
    Swal.fire({
        title: 'ยืนยันการล้างข้อมูล?',
        text: "ข้อมูลผู้สอบทั้งหมดจะถูกลบและไม่สามารถกู้คืนได้!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบทั้งหมด!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete_all_user.php?confirm=true';
        } else {
            window.location.href = 'home_menu.php?page=show_data_std_all';
        }
    });
</script>

<?php
if(isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
    $sql = "TRUNCATE TABLE tb_user_new";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            Swal.fire('สำเร็จ!', 'ล้างข้อมูลทั้งหมดเรียบร้อยแล้ว', 'success')
            .then(() => { window.location.href='home_menu.php?page=show_data_std_all'; });
        </script>";
    }
}
echo '</body></html>';
?>