<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

require_once "app/Controllers/StudentController.php";

$controller = new StudentController();
$student = $controller->getById($_SESSION['student_id']);

$message = "";

$maxDate = date('Y-m-d', strtotime('-5 years'));
$editing = isset($_POST['edit_mode']) ? true : false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    try {
        $controller->update($_SESSION['student_id'], $_POST, $_FILES);
        $student = $controller->getById($_SESSION['student_id']);
        $message = "Profile updated successfully";
        $editing = false;
    } catch (Exception $e) {
        $message = $e->getMessage();
        $editing = true;
    }
}
$active = 'profile';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="styles.css" />
<title>Student Dashboard</title>
</head>
<body>

<div class="layout">
    <?php include 'sidebar.php'; ?>

    <div class="content">
       

        <main class="main">
            <section class="section">
                <?php if($message != ""): ?>
                <p class="success"><?php echo $message; ?></p>
                <?php endif; ?>

                <form class="registration-form" method="POST" enctype="multipart/form-data">

                    <!-- Personal Information -->
                    <section class="form-section">
                        <h2>Personal Information</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Email</label>
                                <?php if($editing): ?>
                                <input name="email" type="email" value="<?php echo $student['email']; ?>" required />
                                <?php else: ?>
                                <span class="readonly-field"><?php echo $student['email']; ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label>Phone</label>
                                <?php if($editing): ?>
                                <input name="phone" type="tel" value="<?php echo $student['phone']; ?>" pattern="^07[0-9]{8}$" />
                                <?php else: ?>
                                <span class="readonly-field"><?php echo $student['phone']; ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label>Date of Birth</label>
                                <?php if($editing): ?>
                                <input name="dob" type="date" value="<?php echo $student['dob']; ?>" max="<?php echo $maxDate; ?>" required />
                                <?php else: ?>
                                <span class="readonly-field"><?php echo $student['dob']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <fieldset class="radio-group">
                            <legend>Gender</legend>
                            <?php if($editing): ?>
                            <label><input type="radio" name="gender" value="Male" <?php if($student['gender']=="Male") echo "checked"; ?> /> Male</label>
                            <label><input type="radio" name="gender" value="Female" <?php if($student['gender']=="Female") echo "checked"; ?> /> Female</label>
                            <?php else: ?>
                            <span class="readonly-field"><?php echo $student['gender']; ?></span>
                            <?php endif; ?>
                        </fieldset>
                    </section>

                    <!-- Academic -->
                    <section class="form-section">
                        <h2>Academic Information</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Grade</label>
                                <?php if($editing): ?>
                                <select name="grade" required>
                                    <option value="">Select grade</option>
                                    <?php for($i=1;$i<=12;$i++): ?>
                                    <option value="<?php echo $i; ?>" <?php if($student['grade']==$i) echo "selected"; ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                                <?php else: ?>
                                <span class="readonly-field"><?php echo $student['grade']; ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label>Preferred Schedule</label>
                                <?php if($editing): ?>
                                <select name="schedule" required>
                                    <option value="">Select schedule</option>
                                    <option value="Morning" <?php if($student['schedule']=="Morning") echo "selected"; ?>>Morning</option>
                                    <option value="Afternoon" <?php if($student['schedule']=="Afternoon") echo "selected"; ?>>Afternoon</option>
                                    <option value="Evening" <?php if($student['schedule']=="Evening") echo "selected"; ?>>Evening</option>
                                </select>
                                <?php else: ?>
                                <span class="readonly-field"><?php echo $student['schedule']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <fieldset class="checkbox-group">
                            <legend>Subjects</legend>
                            <?php 
                            $subjects = explode(", ", $student['subjects']);
                            $allSubjects = ["Math","Science","English","History"];
                            ?>
                            <?php if($editing): ?>
                            <?php foreach($allSubjects as $sub): ?>
                            <label><input type="checkbox" name="subjects[]" value="<?php echo $sub; ?>" <?php if(in_array($sub,$subjects)) echo "checked"; ?> /> <?php echo $sub; ?></label>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <span class="readonly-field"><?php echo $student['subjects']; ?></span>
                            <?php endif; ?>
                        </fieldset>
                    </section>

                    <!-- Photo & Notes -->
                    <section class="form-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Photo</label>
                                <?php if(!empty($student['photo'])): ?>
                                <img src="uploads/<?php echo $student['photo']; ?>" width="120" />
                                <?php endif; ?>
                                <?php if($editing): ?>
                                <input name="photo" type="file" accept="image/jpeg,image/png" />
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label>Notes</label>
                                <?php if($editing): ?>
                                <textarea name="notes" maxlength="200"><?php echo $student['notes']; ?></textarea>
                                <?php else: ?>
                                <span class="readonly-field"><?php echo $student['notes']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>

                    <div class="actions">
                        <?php if($editing): ?>
                        <button type="submit" name="update_profile" class="btn primary">Update Profile</button>
                        <?php else: ?>
                        <button type="submit" name="edit_mode" class="btn primary">Edit Profile</button>
                        <?php endif; ?>
                    </div>

                </form>

                

            </section>
        </main>

        <footer class="footer">
            <p>International Academy School • 2026</p>
        </footer>
    </div>
</div>
</body>
</html>