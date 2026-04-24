<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection without a specific database first
$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1. Create database
$sql = "CREATE DATABASE IF NOT EXISTS soresu_db";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Selecting database
$conn->select_db("soresu_db");

// Step 2. Create Table 1: students (Personal Info)
$sql_table1 = "CREATE TABLE IF NOT EXISTS students (
    student_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    age INT(2) NOT NULL,
    email VARCHAR(40) NOT NULL
)";

if ($conn->query($sql_table1) === TRUE) {
    echo "Table 'students' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Step 3. Create Table 2: academic_records (Academic Info & File Path)
// Linked to Table 1 via 'student_id'
$sql_table2 = "CREATE TABLE IF NOT EXISTS academic_records (
    record_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT(6) UNSIGNED,
    course VARCHAR(40) NOT NULL,
    year_level INT(1) NOT NULL,
    graduation_status BOOLEAN NOT NULL,
    profile_image VARCHAR(255) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
)";

if ($conn->query($sql_table2) === TRUE) {
    echo "Table 'academic_records' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$conn->close();
?>