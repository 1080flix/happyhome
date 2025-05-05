<?php

function generateSaleCode($conn) {
  $today = date('Y-m-d');
  $res = $conn->query("SELECT COUNT(*) AS total FROM sales WHERE DATE(created_at) = '$today'");
  $count = $res->fetch_assoc()['total'] + 1;

  return 'POS' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
}
