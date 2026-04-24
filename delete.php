<?php
/**
 * delete.php - Revised for Sprint 3
 * Permanently removes a student record using the required WHERE clause.
 */
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validation: If student_id is empty, alert and stop
    if (!isset($_POST['student_id']) || empty(trim($_POST['student_id']))) {
        echo "<script>alert('Error: No Student ID provided. Please search for a record first.'); window.location.href='index.php';</script>";
        exit();
    }

    $student_id = intval($_POST['student_id']);

    // Lab Manual Requirement: Use DELETE with a WHERE clause
    // 'ON DELETE CASCADE' in setup.php ensures linked academic records are also removed.
    $sql = "DELETE FROM students WHERE student_id = $student_id";

    if ($conn->query($sql) === TRUE) {
        if ($conn->affected_rows > 0) {
            echo "<script>alert('Student ID $student_id has been successfully deleted.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error: No record found with ID $student_id.'); window.location.href='index.php';</script>";
        }
    } else {
        echo "Database Error: " . $conn->error;
    }

    $conn->close();
} else {
    // If someone accesses this file directly via URL without POSTing an ID
    header("Location: index.php");
    exit();
}
?>