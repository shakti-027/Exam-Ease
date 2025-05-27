<?php
session_start();
require 'db.php';

$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;
$filterDept = $_GET['department'] ?? '';
$filterSubject = $_GET['subject'] ?? '';
$filterYear = $_GET['year'] ?? '';

// Get distinct departments for sidebar
$departments = [];
$dresult = $conn->query("SELECT DISTINCT department FROM papers ORDER BY department ASC");
while ($row = $dresult->fetch_assoc()) {
  $departments[] = $row['department'];
}

// Filtered papers
$sql = "SELECT * FROM papers WHERE 1";
if ($filterDept) $sql .= " AND department LIKE '%" . $conn->real_escape_string($filterDept) . "%'";
if ($filterSubject) $sql .= " AND subject LIKE '%" . $conn->real_escape_string($filterSubject) . "%'";
if ($filterYear) $sql .= " AND year = '" . $conn->real_escape_string($filterYear) . "'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>

<head>
  <title>Exam Papers Archive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    header{
      box-shadow: 2px 2px 6px black;
      /* background-color: lightsteelblue; */
    }
    .container-fluid{
      height: 100vh;
    }
    .sidebar {
      /* min-height: 100vh; */
      height: auto;
      box-shadow: 0 0 4px black;
      border-radius: 8px;
      padding: 1rem;
    }
    .sidebar a {
      display: block;
      margin-bottom: 0.5rem;
      text-decoration: none;
    }
    .content {
      padding: 1rem;
    }
    .dep{
      font-size: 1.1rem;
      font-weight:500;
      text-transform: capitalize;
      transition: .4s ease-out;
    }
    .dep:hover{
      box-shadow: 2px 2px 4px gray;
      width: 200px;
      background-color: white;
      padding: 5px 2px;
      border-radius: 5px;
    }
    @media screen and (max-width: 600px){
    .btns{
       display: flex;
    justify-content: space-between; 
    flex-wrap: wrap; 
    margin: 10px;
    }
    .btns a{
        flex: 1; 
    margin: 5px; 
    padding: 10px;
    border: none; 
    color: white;
    border-radius: 5px; 
    cursor: pointer; 
    }
  }
  </style>
</head>

<body>
   <header class="bg-light p-2 mb-2">
      <div>
        <h2 class="text-dark text-center">
          Exam-Ease
        </h2>
      </div>
    </header>
  <div class="container-fluid bg-white">
   
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 sidebar px-3 ml-5">

        <?php if ($isAdmin): ?>
          <div class="mb-3 btns">
            <a href="upload.php" class="btn btn-success">Upload Paper</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
          </div>
        <?php else: ?>
          <div class="mb-3 text-end">
            <a href="login.php" class="btn btn-outline-primary text-decoration-none"> <i class="fas fa-sign-in-alt me-2"></i>Admin Login</a>
          </div>
        <?php endif; ?>


        <h4> <i class="bi bi-buildings"></i>All Departments</h4>
        <a href="index.php" class="text-decoration-none text-muted">All Departments</a>
        <?php foreach ($departments as $dept): ?>
          <ul>
            <li>
              <a href="?department=<?= urlencode($dept) ?>" class="dep text-decoration-none text-dark"><?= htmlspecialchars($dept) ?></a>
            </li>
          </ul>
        <?php endforeach; ?>
      </div>

      <!-- Main content -->
      <div class="col-md-10 content px-5">
        <h2 class="mb-4">📚 Previous Year Exam Papers</h2>

        <!-- filter for paper -->
        <form method="get" class="row g-3 mb-4">
          <div class="col-md-3">
            <input class="form-control" name="department" placeholder="Filter by Department" value="<?= htmlspecialchars($filterDept) ?>">
          </div>
          <div class="col-md-3">
            <input name="subject" class="form-control" placeholder="Filter by Subject" value="<?= htmlspecialchars($filterSubject) ?>">
          </div>
          <div class="col-md-3">
            <input name="year" class="form-control" placeholder="Filter by Year" value="<?= htmlspecialchars($filterYear) ?>">
          </div>
          <div class="col-md-3 d-grid">
            <button class="btn btn-primary">Apply Filters</button>
          </div>
        </form>

          <!-- uploade Papers -->
        <ul class="list-group">
          <?php if ($result->num_rows === 0): ?>
            <li class="list-group-item">No papers found.</li>
          <?php else: ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong><?= htmlspecialchars($row['department']) ?> / <?= htmlspecialchars($row['subject']) ?> (<?= $row['year'] ?>)</strong><br>
                  <?= htmlspecialchars($row['filename']) ?>
                </div>
                <div class="d-flex">
                  <a href="assets/uploads/<?= urlencode($row['filename']) ?>" target="_blank" class="btn btn-sm btn-outline-primary me-2">View</a>
                  <a href="assets/uploads/<?= urlencode($row['filename']) ?>" download class="btn btn-sm btn-success me-2">Download</a>
                  <?php if ($isAdmin): ?>
                    <form method="POST" action="delete.php" onsubmit="return confirm('Delete this paper?');">
                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                      <input type="hidden" name="filename" value="<?= htmlspecialchars($row['filename']) ?>">
                      <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                  <?php endif; ?>
                </div>
              </li>
            <?php endwhile; ?>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</body>

</html>