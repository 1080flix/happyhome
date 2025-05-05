<?php
$base_path = '../../';
include($base_path . 'config/db.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
  // Check if draft exists
  $stmt = $pdo->prepare("SELECT * FROM sales_draft WHERE id = ?");
  $stmt->execute([$id]);
  $draft = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($draft) {
    // Delete draft details first
    $stmt = $pdo->prepare("DELETE FROM sales_draft_detail WHERE draft_id = ?");
    $stmt->execute([$id]);

    // Delete draft header
    $stmt = $pdo->prepare("DELETE FROM sales_draft WHERE id = ?");
    $stmt->execute([$id]);

    // Redirect to pos-form with data preloaded
    header("Location: pos-form.php?draft_id=" . $id);
    exit;
  }
}

// fallback if not found
header("Location: pos-form.php");
exit;
?>
