<?php
$base_path = '../../';
include($base_path . 'config/check_login.php');

// รับ ID จาก URL แล้วส่งต่อไป API
$id = $_GET['id'] ?? null;

if ($id) {
  header("Location: ../../api/product/delete.php?id=$id");
} else {
  header("Location: product-list.php");
}
exit();
