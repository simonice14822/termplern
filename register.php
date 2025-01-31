<?php
session_start();
include 'db_connect.php';

//รับค่ามาจากฟอร์ม
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// ตรวจสอบว่ากรอกครบทุกช่อง
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    die("กรุณากรอกข้อมูลให้ครบถ้วน");
}
// ตรวจสอบว่ารหัสผ่านใหม่มีความยาว ≥ 8 ตัวอักษร และมีตัวเลข
if (strlen($password) < 8 || !preg_match('/\d/', $password)) {
    die("รหัสผ่านต้องมีอย่างน้อย 8 ตัว และมีตัวเลข");
}

// ตรวจสอบว่ารหัสผ่านและการยืนยันรหัสผ่านตรงกันหรือไม่
if ($password !== $confirm_password) {
    die("รหัสผ่านไม่ตรงกัน");
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// ตรวจสอบว่าอีเมลซ้ำหรือไม่
$check_email_sql = "SELECT id FROM users WHERE email = ?";
$check_stmt = $conn->prepare($check_email_sql);
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    die("อีเมลนี้ถูกใช้ไปแล้ว");
}

// เพิ่มข้อมูลผู้ใช้ใหม่ในฐานข้อมูล
$insert_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("sss", $username, $email, $hashed_password);

if ($insert_stmt->execute()) {
    // สมัครสำเร็จ -> Redirect ไปหน้า login พร้อมส่งสถานะ
    header("Location: login.html?status=registered");
    exit();
} else {
    die("❌ เกิดข้อผิดพลาดในการสมัครสมาชิก");
}

$conn->close();
?>