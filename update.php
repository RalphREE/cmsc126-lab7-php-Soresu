<?php
/**
 * update.php - Revised for Sprint 3
 * Handles processing the update with validation.
 */
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_action'])) {
    
    // Check if Student ID is missing or empty
    if (!isset($_POST['student_id']) || empty(trim($_POST['student_id']))) {
        echo "<script>alert('Error: No record selected for update.'); window.location.href='index.php';</script>";
        exit();
    }

    $student_id = $conn->real_escape_string(trim($_POST['student_id']));
    $name = $conn->real_escape_string(trim($_POST['name']));
    $age = intval($_POST['age']);
    $email = $conn->real_escape_string(trim($_POST['email']));
    $course = $conn->real_escape_string(trim($_POST['course']));
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
        
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($imageFileType, $allowed) && $_FILES["profile_image"]["size"] < 2000000) {
            
            $get_old_image_sql = "SELECT profile_image FROM academic_records WHERE student_id = '$student_id'";
            $old_image_result = $conn->query($get_old_image_sql);
            if ($old_image_result->num_rows > 0) {
                $old_image_row = $old_image_result->fetch_assoc();
                if (!empty($old_image_row['profile_image']) && file_exists($old_image_row['profile_image'])) {
                    unlink($old_image_row['profile_image']); 
                }
            }

            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $image_update_sql = ", profile_image = '$target_file'";
            }
        }
    }

    $sql1 = "UPDATE students SET name = '$name', age = '$age', email = '$email' WHERE student_id = '$student_id'";
    $sql2 = "UPDATE academic_records SET course = '$course', year_level = '$year_level', graduation_status = '$graduation_status' $image_update_sql WHERE student_id = '$student_id'";

    if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
        echo "<script>alert('Record updated successfully!'); window.location.href = 'index.php?action=read&student_id=$student_id';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>