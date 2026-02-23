<?php
session_start();

require_once "app/Controllers/StudentController.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try {

        $controller = new StudentController();

        $email = $_POST['email'];
        $password = $_POST['password'];

        $result = $controller->login($email, $password);

        if (is_array($result)) {
            $_SESSION['student_id'] = $result['id'];
            $_SESSION['student_name'] = $result['full_name'];

            header("Location: home.php");
            exit;
        } else {
            $error = "Login failed";
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Login</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="login-container">
<h2>Student Login</h2>

<?php if($error != ""): ?>
<p class="error"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" class="btn primary">Login</button>
    <a href="index.php" class="btn secondary">Registration</a>
</form>

</div>

</body>
</html>
