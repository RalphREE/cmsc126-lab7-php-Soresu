<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Retrieve the Personal Data from the form
    // The names inside $_POST['...'] MUST match the name attributes in the HTML form
    $name = $_POST['name'];
    $age = $_POST['age'];
    $email = $_POST['email'];

    // Retrieve the Academic Data from the form
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];
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
    
    // Attempt to move the file from its temporary location to your uploads folder
    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        
        // Insert into Table 1 (students)
        $sql1 = "INSERT INTO students (name, age, email) VALUES ('$name', '$age', '$email')";
        
        if ($conn->query($sql1) === TRUE) {
            
            // Get the student_id that MySQL just auto-generated for Table 1
            $last_id = $conn->insert_id;
            
            // Insert into Table 2 (academic_records) using that same ID to link them 
            // We save $target_file so the database only holds the text path (e.g., uploads/16834...image.jpg) 
            $sql2 = "INSERT INTO academic_records (student_id, course, year_level, graduation_status, profile_image) 
                     VALUES ('$last_id', '$course', '$year_level', '$graduation_status', '$target_file')";
            
            if ($conn->query($sql2) === TRUE) {
                // Redirect the user back to the main page
                echo "<script>
                        alert('Registration successful!');
                        window.location.href = 'index.php';
                      </script>";
            } else {
                echo "Error inserting academic record: " . $conn->error;
            }
        } else {
            echo "Error inserting student: " . $conn->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file. Make sure your form has enctype='multipart/form-data'.";
    }
    
    $conn->close();
}
?>