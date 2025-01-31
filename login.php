<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if(!$email || !$password){
        die("กรุณากรอกข้อมูลให้ครบทุกช่อง");
    }

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        if(password_verify($password, $row['password'])){
            
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];

            header("Location: user_info.php");
            exit();
        }else{
            echo "รหัสผ่านไม่ถูกต้อง";
        }
    }else{
        echo "ไม่พบบัญชีผู้ใช้นี้";
    }
}

$conn->close();
?>