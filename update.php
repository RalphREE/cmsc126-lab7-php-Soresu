<?php
/**
 * update.php
 * Handles fetching a record for editing and processing the update.
 */
include 'db_connect.php';

// STAGE 2: PROCESS THE UPDATE (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    $student_id = intval($_POST['student_id']);
    $name = $_POST['name'];
    $age = intval($_POST['age']);
    $email = $_POST['email'];
    $course = $_POST['course'];
    $year_level = intval($_POST['year_level']);
    $graduation_status = isset($_POST['graduation_status']) ? 1 : 0;

    // Handle Image Update (Optional)
    $profile_image_sql = "";
    if (!empty($_FILES["profile_image"]["name"])) {
        $target_dir = "uploads/";
        $filename = basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . time() . "_" . $filename;
        
        // Basic validation: Check file size (e.g., 2MB) and type
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($imageFileType, $allowed_types) && $_FILES["profile_image"]["size"] < 2000000) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_image_sql = ", profile_image = '$target_file'";
            }
        }
    }

    // Update students table
    $sql1 = "UPDATE students SET name = '$name', age = '$age', email = '$email' WHERE student_id = $student_id";
    
    // Update academic_records table
    $sql2 = "UPDATE academic_records SET 
                course = '$course', 
                year_level = '$year_level', 
                graduation_status = '$graduation_status' 
                $profile_image_sql 
             WHERE student_id = $student_id";

    if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
        echo "<script>alert('Record updated successfully!'); window.location.href = 'index.php?student_id=$student_id';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// STAGE 1: DISPLAY THE EDIT FORM (GET)
$row = null;
if (isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);
    $sql = "SELECT s.*, a.* FROM students s INNER JOIN academic_records a ON s.student_id = a.student_id WHERE s.student_id = $student_id LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Update Student — UPV</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="main-content">
        <section class="card">
            <div class="card-header">
                <h1>Edit Student Record</h1>
                <p>Modify the details for Student ID: <?= htmlspecialchars($student_id ?? '') ?></p>
            </div>
            
            <?php if ($row): ?>
            <form action="update.php" method="POST" enctype="multipart/form-data" class="reg-form">
                <input type="hidden" name="student_id" value="<?= $row['student_id'] ?>">
                
                <fieldset>
                    <legend>Personal Information</legend>
                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="name">Name <span class="req">*</span></label>
                            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="age">Age <span class="req">*</span></label>
                            <input type="number" name="age" min="0" max="99" value="<?= htmlspecialchars($row['age']) ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="req">*</span></label>
                        <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Academic Information</legend>
                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="course">Course <span class="req">*</span></label>
                            <input type="text" name="course" value="<?= htmlspecialchars($row['course']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="year_level">Year Level <span class="req">*</span></label>
                            <select name="year_level" required>
                                <?php for($i=1; $i<=4; $i++): ?>
                                    <option value="<?= $i ?>" <?= $row['year_level'] == $i ? 'selected' : '' ?>><?= $i ?>th Year</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group checkbox-group">
                        <label class="checkbox-wrap">
                            <input type="checkbox" name="graduation_status" value="1" <?= $row['graduation_status'] ? 'checked' : '' ?>> Yes, Graduating
                        </label>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Profile Photo (Optional Update)</legend>
                    <input type="file" name="profile_image" accept="image/*">
                    <p class="hint">Leave empty to keep existing image.</p>
                </fieldset>

                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn btn-warning btn-full">Update Record</button>
                    <a href="index.php" class="btn btn-secondary btn-full" style="text-align:center; padding-top:13px;">Cancel</a>
                </div>
            </form>
            <?php else: ?>
                <div class="alert alert-error">Record not found. <a href="index.php">Return home</a></div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>