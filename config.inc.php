<?php 
// Docker: use service name 'db' | Local: use 'localhost'
$server = "db"; 
$user = "root"; 
$pass = "technicalsuphanburi2023!!!"; 
$db = "test"; 
$conn = new mysqli($server, $user, $pass, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

mysqli_set_charset($conn, "utf8mb4");
?> 