<?php
/**
 * delete.php
 * Handles the deletion of a student record.
 */
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    $student_id = intval($_POST['student_id']);

    // Because of 'ON DELETE CASCADE' in setup.php, 
    // deleting from 'students' will automatically delete from 'academic_records'.
    $sql = "DELETE FROM students WHERE student_id = $student_id";

    if ($conn->query($sql) === TRUE) {
        if ($conn->affected_rows > 0) {
            echo "<script>
                    alert('Record (ID: $student_id) has been permanently deleted.');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "<script>
                    alert('No record found with ID: $student_id');
                    window.location.href = 'index.php';
                  </script>";
        }
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
} else {
    // Redirect if accessed directly without POST
    header("Location: index.php");
    exit();
}
?>