<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "termplern_db";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die ("ไม่สามารถเชื่อมต่อได้" .$conn->connect_error);
}
?>