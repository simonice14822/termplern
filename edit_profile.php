<?php
session_start();
include 'db_connect.php';

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$sql = "SELECT username, email, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขโปรไฟล์</title>
    
    <!-- ✅ ใช้ Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="width: 400px;">
            <h2 class="text-center text-primary">✏️ แก้ไขโปรไฟล์</h2>
            
            <!-- ✅ แสดงรูปโปรไฟล์ -->
            <div class="text-center">
                <img src="uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" class="rounded-circle" width="100" height="100">
            </div>

            <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="mt-3">
                <div class="mb-3">
                    <label class="form-label">ชื่อผู้ใช้</label>
                    <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">เปลี่ยนรูปโปรไฟล์</label>
                    <input type="file" name="profile_image" class="form-control">
                </div>
                <button type="submit" class="btn btn-success w-100">💾 บันทึกการเปลี่ยนแปลง</button>
            </form>
            <form action="delete_profile.php" method="POST" class="mt-3">
                <button type="submit" class="btn btn-danger w-100">🗑️ ลบรูปโปรไฟล์</button>
            </form>

            <!-- ลิงค์ไปเปลี่ยนรหัสผ่าน -->
            <a href="change_password.php" class="btn btn-warning w-100 mt-3">เปลี่ยนรหัสผ่าน</a>

            <a href="user_info.php" class="btn btn-secondary w-100 mt-3">🔙 กลับ</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
