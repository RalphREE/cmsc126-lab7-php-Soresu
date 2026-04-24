<?php
$servername = "localhost";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Force drop the old integer-based database
$conn->query("DROP DATABASE IF EXISTS soresu_db");

// 2. Create a fresh database
$sql = "CREATE DATABASE soresu_db";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

$conn->select_db("soresu_db");

// 3. Create Table 1 with the correct VARCHAR rule
$sql_table1 = "CREATE TABLE students (
    student_id VARCHAR(20) PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    age INT(2) NOT NULL,
    email VARCHAR(40) NOT NULL
)";

if ($conn->query($sql_table1) === TRUE) {
    echo "Table 'students' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// 4. Create Table 2 with the correct VARCHAR rule
$sql_table2 = "CREATE TABLE academic_records (
    record_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20),
    course VARCHAR(40) NOT NULL,
    year_level INT(1) NOT NULL,
    graduation_status TINYINT(1) DEFAULT 0,
    profile_image VARCHAR(255),
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
)";

if ($conn->query($sql_table2) === TRUE) {
    echo "Table 'academic_records' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$conn->close();
?>