<?php
session_start();
include 'db_connect.php';

// ดูการเข้าสู่ระบบ
if (!issrt($_SESSION['user_id'])){
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลโปรไฟล์เดิม
$sql = "SELECT profile_pic FROM users WHERE id = ? ";
$sql = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['profile_pic'] !== 'icon_default.jpg'){
    $file_path = "uploads/" . $user['profile_pic'];
    if (file_exists($file_path)){
        unlink($file_path); //ลบไฟล์จริงออกจากเซิฟ
    }
}

// ทำให้รูปเป็น default
$update_sql = "UPDATE users SET profile_pic = 'icon_default.jpg' WHERE id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_parem("i", $user_id);

if ($update_stmt->execute()){
    echo "<script>
            alert('✅ ลบรูปโปรไฟล์สำเร็จ!');
            window.location.href = 'edit_profile.php';
          </script>";  
} else {
    echo "<script>alert('❌ เกิดข้อผิดพลาดในการลบรูปโปรไฟล์'); history.back();</script>";
}

$conn->close();
?>