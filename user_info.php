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

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("ไม่พบข้อมูลผู้ใช้");
}

// ตรวจสอบว่ามีรูปโปรไฟล์หรือไม่ ถ้าไม่มีให้ใช้ icon_default.png
$profile_pic = (!empty($user['profile_pic'])) ? "uploads/" . $user['profile_pic'] : "uploads/icon_default.png";
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ข้อมูลผู้ใช้</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
                /* โหมดมืด */
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }

        /* ทำให้ Card เปลี่ยนสีในโหมดมืด */
        .card.dark-mode {
            background-color: #1e1e1e;
            color: #ffffff;
            border: 1px solid #333;
        }

        /* เปลี่ยนสีเฉพาะค่าของผู้ใช้ (username และ email) */
        body.dark-mode .user-data {
            color: #ffffff !important;
        }

        /* ปรับปุ่มให้เข้ากับ Dark Mode */
        body.dark-mode .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        body.dark-mode .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        /* ปุ่ม Toggle Dark Mode */
        .btn-toggle {
            cursor: pointer;
        }
    </style>

</head>
<body class="bg-light fade-in">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4 text-center" style="width: 400px;">
            <div class="d-flex justify-content-between">
                <h2 class="text-primary">ข้อมูลผู้ใช้</h2>
                <!-- ปุ่มสลับโหมด -->
                <button id="toggleDarkMode" class="btn btn-secondary btn-sm btn-toggle">🌙</button>
            </div>

            <!-- รูปโปรไฟล์ -->
            <div class="mt-3">
                <img id="profileImage" src="<?php echo htmlspecialchars($profile_pic); ?>?v=<?php echo time(); ?>"
                     class="rounded-circle border border-3 border-primary shadow" width="120" height="120">
            </div>

            <!-- แสดงข้อมูลผู้ใช้ -->
            <div class="mt-3">
                <p class="fw-bold">👤 ชื่อผู้ใช้: <span class="user-data"><?php echo htmlspecialchars($user['username']); ?></span></p>
                <p class="fw-bold">📧 อีเมล: <span class="user-data"><?php echo htmlspecialchars($user['email']); ?></span></p>
            </div>

            <!-- แก้ไขข้อมูล -->
            <a href="edit_profile.php" class="btn btn-info w-100 mt-3">แก้ไขข้อมูล</a>
            
            <!-- ออกจากระบบ -->
            <form action="logout.php" method="post" class="mt-3">
                <button type="submit" class="btn btn-danger w-100">ออกจากระบบ</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
     document.addEventListener("DOMContentLoaded", function() {
    if (localStorage.getItem("darkMode") === "enabled") {
        document.body.classList.add("dark-mode");
        document.querySelector(".card").classList.add("dark-mode");

        // เปลี่ยนสีเฉพาะค่าของผู้ใช้เป็นสีขาว
        document.querySelectorAll(".user-data").forEach(el => el.style.color = "#ffffff");

        document.getElementById("toggleDarkMode").textContent = "☀️";
    }
});

// ปุ่มสลับ Dark Mode
document.getElementById("toggleDarkMode").addEventListener("click", function() {
    let body = document.body;
    let card = document.querySelector(".card");

    if (body.classList.contains("dark-mode")) {
        body.classList.remove("dark-mode");
        card.classList.remove("dark-mode");

        // กลับสีเดิมเมื่อออกจาก Dark Mode
        document.querySelectorAll(".user-data").forEach(el => el.style.color = "");

        localStorage.setItem("darkMode", "disabled");
        this.textContent = "🌙";
    } else {
        body.classList.add("dark-mode");
        card.classList.add("dark-mode");

        // เปลี่ยนเฉพาะค่าของผู้ใช้เป็นสีขาว
        document.querySelectorAll(".user-data").forEach(el => el.style.color = "#ffffff");

        localStorage.setItem("darkMode", "enabled");
        this.textContent = "☀️";
    }
});
    </script>
</body>
</html>
