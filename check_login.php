<?php
session_start();
include('config/db.php');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
  header("Location: login.php?error=กรุณากรอกชื่อผู้ใช้และรหัสผ่าน");
  exit();
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  if ($password === $row['password']) {
    // สำเร็จ: บันทึก session แล้ว redirect
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];
    header("Location: dashboard.php");
    exit();
  } else {
    header("Location: login.php?error=รหัสผ่านไม่ถูกต้อง");
    exit();
  }
} else {
  header("Location: login.php?error=ไม่พบผู้ใช้งานนี้ในระบบ");
  exit();
}
