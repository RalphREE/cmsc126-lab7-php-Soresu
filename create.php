<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Retrieve the Personal Data from the form
    // The names inside $_POST['...'] MUST match the name attributes in the HTML form
    $name = $conn->real_escape_string(trim($_POST['name']));
    $age = intval($_POST['age']);
    $email = $conn->real_escape_string(trim($_POST['email']));

    // Retrieve the Academic Data from the form
    $course = $conn->real_escape_string(trim($_POST['course']));
    $year_level = intval($_POST['year_level']);
    // If checked it returns a value, otherwise we set it to 0 (false)
    $graduation_status = isset($_POST['graduation_status']) ? 1 : 0; 

    // Handle the Profile Image Upload 
    $target_dir = "uploads/"; // The folder where images will be saved
    
    // Create the folder if it somehow doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Get the name of the uploaded file
    $filename = basename($_FILES["profile_image"]["name"]);
    // Add a unique timestamp so files with the same name don't overwrite each other
    $target_file = $target_dir . time() . "_" . $filename; 
    
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($imageFileType, $allowed)) {
        echo "<script>alert('Error: Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.'); window.history.back();</script>";
        exit();
    }
    
    // Attempt to move the file from its temporary location to your uploads folder
    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        
        $conn->begin_transaction();
        
        try {
            // GENERATE THE CUSTOM ID: "2026-" followed by 5 random digits
            $new_student_id = "2026-" . sprintf('%05d', rand(0, 99999));

            // Insert into Table 1 (students) using the custom ID
            $sql1 = "INSERT INTO students (student_id, name, age, email) VALUES ('$new_student_id', '$name', '$age', '$email')";
            $conn->query($sql1);
            
            // Insert into Table 2 (academic_records) using that same ID to link them 
            $sql2 = "INSERT INTO academic_records (student_id, course, year_level, graduation_status, profile_image) 
                     VALUES ('$new_student_id', '$course', '$year_level', '$graduation_status', '$target_file')";
            $conn->query($sql2);
            
            $conn->commit();
            
            // Redirect the user back to the main page with the new custom ID
            echo "<script>
                    window.location.href = 'index.php?new_id=$new_student_id';
                  </script>";
                  
        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();
            echo "Transaction failed: " . $exception->getMessage();
        }
        
    } else {
        echo "Sorry, there was an error uploading your file. Make sure your form has enctype='multipart/form-data'.";
    }
    
    $conn->close();
}
?>