<?php
session_start();
include 'db_connect.php';

header('Content-Type: application/json'); // ส่งข้อมูลเป็น JSON
$response = ["status" => "error", "message" => ""];

if (!isset($_SESSION['user_id'])) {
    $response["message"] = "กรุณาเข้าสู่ระบบก่อนอัปโหลด";
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_image"])) {
    $target_dir = "uploads/";
    $file_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // ตรวจสอบว่าเป็นไฟล์รูปภาพ
    $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
    if ($check === false) {
        $response["message"] = "ไฟล์ที่อัปโหลดไม่ใช่รูปภาพ!";
        echo json_encode($response);
        exit();
    }

    // ตรวจสอบประเภทไฟล์
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        $response["message"] = "อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น!";
        echo json_encode($response);
        exit();
    }

    // ย้ายไฟล์ไปยังโฟลเดอร์ uploads
    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        // อัปเดตฐานข้อมูล
        $sql = "UPDATE users SET profile_pic = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $file_name, $user_id);

        if ($stmt->execute()) {
            $response["status"] = "success";
            $response["message"] = "อัปโหลดสำเร็จ!";
            $response["new_image"] = "uploads/" . $file_name;
        } else {
            $response["message"] = "เกิดข้อผิดพลาดในการอัปเดตฐานข้อมูล";
        }
    } else {
        $response["message"] = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์!";
    }
}

$conn->close();
echo json_encode($response);
exit();
?>
