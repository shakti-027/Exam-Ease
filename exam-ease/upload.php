<?php
session_start();
require 'db.php';
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
  header("Location: login.php");
  exit;
}

$uploadDir = 'assets/uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf'])) {
  $department = $conn->real_escape_string($_POST['department']);
  $subject = $conn->real_escape_string($_POST['subject']);
  $year = $conn->real_escape_string($_POST['year']);
  $file = $_FILES['pdf'];
  $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

  if ($ext === 'pdf' && $file['size'] < 10 * 1024 * 1024) {
    $target = $uploadDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $target)) {
      $filenameEsc = $conn->real_escape_string($file['name']);
      $conn->query("INSERT INTO papers (department, subject, year, filename) VALUES ('$department', '$subject', '$year', '$filenameEsc')");
      $msg = "File uploaded successfully.";
    } else {
      $msg = "Upload failed.";
    }
  } else {
    $msg = "Only PDF files under 10MB allowed.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Upload Exam Paper</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h2>Upload New Exam Paper</h2>
  <a href="index.php" class="btn btn-secondary mb-3">← Back</a>
  <?php if ($msg): ?><div class="alert alert-info"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><label>Department</label><input type="text" name="department" class="form-control" required></div>
    <div class="mb-3"><label>Subject</label><input type="text" name="subject" class="form-control" required></div>
    <div class="mb-3"><label>Year</label><input type="text" name="year" class="form-control" required></div>
    <div class="mb-3"><label>PDF File</label><input type="file" name="pdf" accept="application/pdf" class="form-control" required></div>
    <button type="submit" class="btn btn-primary">Upload</button>
  </form>
</div>
</body>
</html>
