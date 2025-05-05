<?php
include('../../config/db.php');

$stmt = $conn->prepare("SELECT * FROM settings WHERE id = 1");
$stmt->execute();
$settings = $stmt->get_result()->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($settings);
