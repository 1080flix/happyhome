<?php
include('../../config/db.php');

$id = $_POST['id'];
$name = $_POST['customer_name'];
$type = $_POST['customer_type'];
$tax_id = $_POST['tax_id'] ?? null;
$phone = $_POST['phone'] ?? null;
$email = $_POST['email'] ?? null;
$address = $_POST['address'] ?? null;
$branch = $_POST['branch'] ?? null;
$contact_name = $_POST['contact_name'] ?? null;
$contact_phone = $_POST['contact_phone'] ?? null;

$stmt = $conn->prepare("UPDATE customers SET 
  customer_name = ?, customer_type = ?, tax_id = ?, phone = ?, email = ?, address = ?, 
  branch = ?, contact_name = ?, contact_phone = ? WHERE id = ?");

$stmt->bind_param("sssssssssi", $name, $type, $tax_id, $phone, $email, $address, $branch, $contact_name, $contact_phone, $id);

if ($stmt->execute()) {
  header("Location: ../../modules/customer/customer-list.php");
  exit();
} else {
  echo "เกิดข้อผิดพลาด: " . $stmt->error;
}
