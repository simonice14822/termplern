<?php
session_start();
include 'db_connect.php';

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$profile_image = $_FILES['profile_image'];

// ✅ ตรวจสอบว่าอีเมลที่กรอกไม่มีซ้ำ
$check_email_sql = "SELECT id FROM users WHERE email = ? AND id != ?";
$check_stmt = $conn->prepare($check_email_sql);
$check_stmt->bind_param("si", $email, $user_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    die("<script>alert('❌ อีเมลนี้ถูกใช้ไปแล้ว'); history.back();</script>");
}

// ✅ ตรวจสอบว่ามีการอัปโหลดรูปใหม่หรือไม่
if (!empty($profile_image['name'])) {
    $target_dir = "uploads/";
    $file_name = time() . "_" . basename($profile_image["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // ตรวจสอบว่าเป็นไฟล์รูปภาพ
    $check = getimagesize($profile_image["tmp_name"]);
    if ($check === false) {
        die("<script>alert('❌ ไฟล์ที่อัปโหลดไม่ใช่รูปภาพ!'); history.back();</script>");
    }

    // ตรวจสอบประเภทไฟล์
    $allowed_types = ["jpg", "jpeg", "png"];
    if (!in_array($imageFileType, $allowed_types)) {
        die("<script>alert('❌ อนุญาตเฉพาะไฟล์ JPG, JPEG และ PNG เท่านั้น!'); history.back();</script>");
    }

    // อัปโหลดไฟล์ใหม่
    if (move_uploaded_file($profile_image["tmp_name"], $target_file)) {
        $update_sql = "UPDATE users SET username = ?, email = ?, profile_pic = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssi", $username, $email, $file_name, $user_id);
    } else {
        die("<script>alert('❌ อัปโหลดรูปภาพล้มเหลว!'); history.back();</script>");
    }
} else {
    // ✅ อัปเดตแค่ชื่อและอีเมล ถ้าไม่มีการอัปโหลดรูปใหม่
    $update_sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $username, $email, $user_id);
}

// ✅ ทำการอัปเดตข้อมูล
if ($stmt->execute()) {
    echo "<script>
            alert('✅ อัปเดตโปรไฟล์สำเร็จ!');
            window.location.href = 'user_info.php';
          </script>";
} else {
    echo "<script>alert('❌ เกิดข้อผิดพลาดในการอัปเดตโปรไฟล์'); history.back();</script>";
}

$conn->close();
?>
