<?php
require_once '../config.php';

$servername = DB_HOST;
$username = DB_USER;
$password = DB_PASS;
$dbname = DB_NAME;

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DROP DATABASE IF EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "database  droped successfully<br>";
} else {
    echo "Error droping database: " . $conn->error;
}
// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error;
}

// Select the created database
$conn->select_db($dbname);


// Create table for users
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phone VARCHAR(15) NOT NULL, 
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created successfully<br>";
} else {
    echo "Error creating table 'users': " . $conn->error;
}

// Create table for tasks
$sql = "CREATE TABLE IF NOT EXISTS tasks (
    id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(255) NOT NULL,
    task_description VARCHAR(255) NOT NULL,
    task_date DATE NOT NULL,
    task_status BOOLEAN NOT NULL,
    task_created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id INT(6) UNSIGNED,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";


if ($conn->query($sql) === TRUE) {
    echo "Table 'tasks' created successfully<br>";
} else {
    echo "Error creating table 'tasks': " . $conn->error;
}

$conn->close();
