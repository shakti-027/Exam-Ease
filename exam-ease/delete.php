<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
  header("Location: index.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id']);
  $filename = $_POST['filename'];

  $conn->query("DELETE FROM papers WHERE id = $id");

  $filepath = "assets/uploads/" . basename($filename);
  if (file_exists($filepath)) {
    unlink($filepath);
  }
}
header("Location: index.php");
exit;
