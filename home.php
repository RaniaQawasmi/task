<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

require_once "app/Controllers/StudentController.php";
$controller = new StudentController();
$student = $controller->getById($_SESSION['student_id']);

// تحديد الصفحة النشطة للـ sidebar
$active = 'home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="styles.css" />
<title>Home</title>
</head>
<body>

<div class="layout">
  <?php include 'sidebar.php'; ?>

  <div class="content">
    <div class="home-hero" style="background-image: url('uploads/<?php echo $student['photo']; ?>');">
      <div class="overlay"></div>
      <div class="hero-text">
        <h1>Welcome</h1>
        <h2><?php echo $student['full_name']; ?></h2>
        <p></p>
      </div>
    </div>
  </div>
</div>

</body>
</html>