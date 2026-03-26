<?php
require('config.inc.php');
$std_id = mysqli_real_escape_string($conn, $_GET['std_id']);
$res = $conn->query("SELECT COUNT(*) as total FROM tb_user_answers WHERE std_id = '$std_id'");
echo ($res->fetch_assoc())['total'];
?>