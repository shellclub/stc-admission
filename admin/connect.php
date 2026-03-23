<?
$servername = "localhost";
$username = "root";       // miniiotc_email
$password = "itsuphan";  //itsuphan
$dbname = "test";    // miniiotc_email


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
 mysqli_set_charset($conn,"utf8");
?>