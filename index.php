<?php
require_once "app/Controllers/StudentController.php";

$controller = new StudentController();
$message = "";
$messageType = ""; // success أو error

// التعامل مع POST عند الضغط على Submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $controller->register($_POST, $_FILES);
        $message = "Student registered successfully";
        $messageType = "success";
    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Student Registration</title>
<link rel="stylesheet" href="styles.css" />
<style>
/* رسائل النجاح */
.message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    padding: 10px 15px;
    border-radius: 5px;
    margin-bottom: 15px;
    font-weight: bold;
}

/* رسائل الأخطاء */
.message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    padding: 10px 15px;
    border-radius: 5px;
    margin-bottom: 15px;
    font-weight: bold;
}
</style>
</head>
<body>

<header class="page-header">
<div class="header-content">
<h1>Student Registration</h1>
<p class="subtitle">Join our School</p>
</div>
</header>

<main class="main">
<section class="section">

<!-- عرض رسالة النجاح أو الخطأ -->
<?php if($message != ""): ?>
<div class="message <?php echo $messageType; ?>">
    <?php echo $message; ?>
</div>
<?php endif; ?>

<form class="registration-form" method="POST" enctype="multipart/form-data" novalidate>

<!-- Personal Information -->
<section class="form-section">
<h2>Personal Information</h2>
<div class="form-grid">

<div class="form-group">
<label for="name">Full Name</label>
<input id="name" name="full_name" type="text" required minlength="3" pattern="[A-Za-z\s]+" />
</div>

<div class="form-group">
<label for="email">Email</label>
<input id="email" name="email" type="email" required />
</div>

<div class="form-group">
<label for="password">Password</label>
<input id="password" name="password" type="password" required minlength="6" />
</div>

<div class="form-group">
<label for="phone">Phone</label>
<input id="phone" name="phone" type="tel" required pattern="^07[0-9]{8}$" />
</div>

<div class="form-group">
<label for="dob">Date of Birth</label>
<input id="dob" name="dob" type="date" required />
</div>

</div>

<fieldset class="radio-group">
<legend>Gender</legend>
<label><input type="radio" name="gender" value="Male" required /> Male</label>
<label><input type="radio" name="gender" value="Female" /> Female</label>
</fieldset>
</section>

<!-- Academic Information -->
<section class="form-section">
<h2>Academic Information</h2>
<div class="form-grid">

<div class="form-group">
<label for="grade">Grade</label>
<select id="grade" name="grade" required>
<option value="">Select grade</option>
<?php for($i=1;$i<=12;$i++): ?>
<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php endfor; ?>
</select>
</div>

<div class="form-group">
<label for="schedule">Preferred Schedule</label>
<select id="schedule" name="schedule" required>
<option value="">Select schedule</option>
<option value="Morning">Morning</option>
<option value="Afternoon">Afternoon</option>
<option value="Evening">Evening</option>
</select>
</div>

</div>

<fieldset class="checkbox-group">
<legend>Subjects</legend>
<label><input type="checkbox" name="subjects[]" value="Math" /> Math</label>
<label><input type="checkbox" name="subjects[]" value="Science" /> Science</label>
<label><input type="checkbox" name="subjects[]" value="English" /> English</label>
<label><input type="checkbox" name="subjects[]" value="History" /> History</label>
</fieldset>
</section>

<!-- Photo & Notes -->
<section class="form-section">
<div class="form-grid">

<div class="form-group">
<label for="photo">Upload Student Photo</label>
<input id="photo" name="photo" type="file" accept="image/jpeg,image/png" />
<small class="help-text">Images only, max 2MB</small>
</div>

<div class="form-group">
<label for="notes">Notes</label>
<textarea id="notes" name="notes" maxlength="200"></textarea>
<small class="help-text">Maximum 200 characters</small>
</div>

</div>
</section>

<div class="actions">
<div class="buttons-row">
<button type="submit" class="btn primary">Submit</button>
<button type="reset" class="btn secondary">Reset</button>
</div>

<a href="login.php" class="login-link"> Log In</a>
</div>

</form>
</section>
</main>

<footer class="footer">
<p>International Academy School • 2026</p>
</footer>

<script>
// Client-side validation
document.querySelector(".registration-form").addEventListener("submit", function(e) {

let name = document.getElementById("name").value.trim();
let email = document.getElementById("email").value.trim();
let phone = document.getElementById("phone").value.trim();
let dob = document.getElementById("dob").value;
let grade = document.getElementById("grade").value;
let schedule = document.getElementById("schedule").value;
let subjects = document.querySelectorAll("input[name='subjects[]']:checked");
let photo = document.getElementById("photo").files[0];

if (name.length < 3) { alert("Full Name must be at least 3 characters"); e.preventDefault(); return; }
let password = document.getElementById("password").value;
if (password.length < 6) { alert("Password must be at least 6 characters"); e.preventDefault(); return; }

let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
if (!emailPattern.test(email)) { alert("Please enter a valid email"); e.preventDefault(); return; }

let phonePattern = /^07[0-9]{8}$/;
if (!phonePattern.test(phone)) { alert("Phone must start with 07 and be exactly 10 digits"); e.preventDefault(); return; }

let birthDate = new Date(dob);
let age = new Date().getFullYear() - birthDate.getFullYear();
if (age < 5) { alert("Student must be at least 5 years old"); e.preventDefault(); return; }

if (grade === "") { alert("Please select a grade"); e.preventDefault(); return; }
if (schedule === "") { alert("Please select preferred schedule"); e.preventDefault(); return; }
if (subjects.length === 0) { alert("Please select at least one subject"); e.preventDefault(); return; }

if (photo) {
if (photo.size > 2 * 1024 * 1024) { alert("Image must be less than 2MB"); e.preventDefault(); return; }
let allowedTypes = ["image/jpeg","image/png"];
if (!allowedTypes.includes(photo.type)) { alert("Only JPG or PNG images allowed"); e.preventDefault(); return; }
}

});
</script>

</body>
</html>