<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = $_POST['username'];
  $pass = $_POST['password'];
  if ($user === 'Admin' && $pass === 'Exam$2025') {
    $_SESSION['admin'] = true;
    header("Location: index.php");
    exit;
  } else {
    $error = "Invalid username or password";
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<style>
  header {
    box-shadow: 2px 2px 6px black;
  }

  section {
    margin-top: 200px;
  }
</style>

<body>

  <header class="bg-light p-2">
    <div>
      <h2 class="text-dark text-center">
        Exam-Ease
      </h2>
    </div>
  </header>
  <section class=" d-flex justify-content-center align-items-center">
    <form method="POST" class="shadow p-4 rounded py-5" style="width: 22rem;">
      <h2 class="text-center mb-4">Sign In</h2>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <div class="form-group mb-3">
        <label><i class="bi bi-person-bounding-box"></i>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="form-group mb-3">
        <label><i class="bi bi-key-fill"></i>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary btn-block w-100">Login</button>
    </form>
  </section>
</body>

</html>