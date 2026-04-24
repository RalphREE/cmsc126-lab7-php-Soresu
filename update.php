<?php
/**
 * update.php - Revised for Sprint 3
 * Handles fetching a record and processing the update with validation.
 */
include 'db_connect.php';

// --- STAGE 2: PROCESS THE UPDATE (POST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_action'])) {
    
    // Check if Student ID is missing or empty
    if (!isset($_POST['student_id']) || empty(trim($_POST['student_id']))) {
        echo "<script>alert('Error: No record selected for update.'); window.location.href='index.php';</script>";
        exit();
    }

    $student_id = intval($_POST['student_id']);
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);
    $year_level = intval($_POST['year_level']);
    $graduation_status = isset($_POST['graduation_status']) ? 1 : 0;

    // Validation: Alert and do nothing if required fields are empty
    if (empty($name) || empty($age) || empty($email) || empty($course)) {
        echo "<script>alert('Update failed: All fields marked * are required.'); window.history.back();</script>";
        exit();
    }

    // Handle Optional Image Update
    $image_update_sql = "";
    if (!empty($_FILES["profile_image"]["name"])) {
        $target_dir = "uploads/";
        $filename = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $filename;
        
        // Lab Requirement: File type validation
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($imageFileType, $allowed) && $_FILES["profile_image"]["size"] < 2000000) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $image_update_sql = ", profile_image = '$target_file'";
            }
        }
    }

    // Update students table (Personal Info)
    $sql1 = "UPDATE students SET name = '$name', age = '$age', email = '$email' WHERE student_id = $student_id";
    
    // Update academic_records table (Academic Info)
    $sql2 = "UPDATE academic_records SET 
                course = '$course', 
                year_level = '$year_level', 
                graduation_status = '$graduation_status' 
                $image_update_sql 
             WHERE student_id = $student_id";

    if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
        echo "<script>alert('Record updated successfully!'); window.location.href = 'index.php?student_id=$student_id';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
    exit();
}

// --- STAGE 1: FETCH DATA FOR THE FORM (GET) ---
$row = null;
if (isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);
    $sql = "SELECT s.*, a.* FROM students s JOIN academic_records a ON s.student_id = a.student_id WHERE s.student_id = $student_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Student Record</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="main-content">
        <section class="card">
            <?php if ($row): ?>
            <div class="card-header">
                <h1>Update Record: <?= htmlspecialchars($row['name']) ?></h1>
            </div>
            <form action="update.php" method="POST" enctype="multipart/form-data" class="reg-form">
                <input type="hidden" name="student_id" value="<?= $row['student_id'] ?>">
                
                <label>Name *</label>
                <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
                
                <label>Age *</label>
                <input type="number" name="age" value="<?= htmlspecialchars($row['age']) ?>" required>
                
                <label>Email *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>

                <label>Course *</label>
                <input type="text" name="course" value="<?= htmlspecialchars($row['course']) ?>" required>

                <label>Year Level *</label>
                <select name="year_level" required>
                    <option value="1" <?= $row['year_level'] == 1 ? 'selected' : '' ?>>1st Year</option>
                    <option value="2" <?= $row['year_level'] == 2 ? 'selected' : '' ?>>2nd Year</option>
                    <option value="3" <?= $row['year_level'] == 3 ? 'selected' : '' ?>>3rd Year</option>
                    <option value="4" <?= $row['year_level'] == 4 ? 'selected' : '' ?>>4th Year</option>
                </select>

                <label>
                    <input type="checkbox" name="graduation_status" value="1" <?= $row['graduation_status'] ? 'checked' : '' ?>> Yes, Graduating
                </label>

                <label>Change Profile Image (Optional)</label>
                <input type="file" name="profile_image" accept="image/*">

                <div style="margin-top:20px; display:flex; gap:10px;">
                    <button type="submit" name="update_action" class="btn btn-warning">Update Record</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
            <?php else: ?>
                <div class="alert alert-error">No student selected. <a href="index.php">Go back</a></div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>