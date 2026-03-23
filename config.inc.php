<?php 
$server = "localhost"; 
$user = "root"; 
$pass =""; 
$db = "test"; 
$conn = new mysqli($server, $user, $pass, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
 
?> 