<?php
session_start();
include 'db_connect.php';

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// รับค่าจากฟอร์ม
$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// ตรวจสอบว่าใส่ค่าครบทุกช่อง
if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
    die("กรุณากรอกข้อมูลให้ครบถ้วน");
}

// ตรวจสอบรหัสผ่านใหม่ ≥ 8 ตัวอักษร และมีตัวเลข
if (strlen($new_password) < 8 || !preg_match('/\d/', $new_password)) {
    die("รหัสผ่านใหม่ต้องมีอย่างน้อย 8 ตัว และมีตัวเลข");
}

// ดึงรหัสผ่านเดิมจากฐานข้อมูล
$sql = "SELECT password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("ไม่พบข้อมูลผู้ใช้");
}

// ตรวจสอบรหัสผ่านเก่า
if (!password_verify($old_password, $user['password'])) {
    die("รหัสผ่านเก่าไม่ถูกต้อง");
}

// ตรวจสอบว่ารหัสผ่านใหม่และยืนยันรหัสผ่านตรงกันหรือไม่
if ($new_password !== $confirm_password) {
    die("รหัสผ่านใหม่ไม่ตรงกัน");
}

// แฮชรหัสผ่านใหม่
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// อัปเดตรหัสผ่านในฐานข้อมูล
$update_sql = "UPDATE users SET password = ? WHERE id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("si", $hashed_password, $user_id);

$status_message = "";
if ($update_stmt->execute()) {
    $status_message = "✅ เปลี่ยนรหัสผ่านเรียบร้อย!";
} else {
    $status_message = "❌ เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เปลี่ยนรหัสผ่าน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4 text-center" style="width: 400px;">
            <h2 class="text-primary">แจ้งเตือน</h2>
            <p><?php echo $status_message; ?></p>
            <a href="change_password.php" class="btn btn-primary w-100 mt-3">ย้อนกลับ</a>
        </div>
    </div>
</body>
</html>
