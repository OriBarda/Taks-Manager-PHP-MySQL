<?php

session_start();

require_once 'config.php';

function generate_csrf_token()
{
  return bin2hex(random_bytes(32));
}

if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = generate_csrf_token();
  error_log("CSRF token generated: " . $_SESSION['csrf_token']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Verify CSRF token
  if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("CSRF token validation failed!");
  }

  // Sanitize and validate input
  $username = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z0-9_]+$/")));
  $password = $_POST['password'];
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);

  // Check if all required fields are provided and valid
  if (!$username || !$password || !$email || !$phone) {
    die("Invalid input data");
  }

  $username = trim($username);
  $password = trim($password);

  // Hash the password
  $hash = password_hash($password, PASSWORD_DEFAULT);

  // Connect to MySQL database
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Check if username, email, and phone already exist
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ? OR phone = ?");
  $stmt->bind_param("sss", $username, $email, $phone);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    echo "This user already exists";
  } else {
    // Insert the new user
    $stmt = $conn->prepare("INSERT INTO users (username, password, phone, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $hash, $phone, $email);
    if ($stmt->execute()) {
      echo "New record inserted successfully";
      header("Location: login.php");
      exit();
    } else {
      echo "Error: " . $stmt->error;
    }
  }

  $stmt->close();
  $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
    }

    .container {
      max-width: 500px;
      margin: 50px auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      display: block;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"] {
      width: calc(100% - 20px);
      padding: 10px;
      border: 2px solid #ccc;
      border-radius: 5px;
      transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="password"]:focus,
    input[type="email"]:focus {
      border-color: #007bff;
      outline: none;
    }

    .btn {
      display: inline-block;
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<style>
</style>
<?php
include("./tools/unprotected_navbar.php")
?>

<body>
  <div class="container">
    <h1>User Sign Up</h1>
    <form action="index.php" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
      <div class="form-group">
        <label for="username">Username:</label>
        <input name="username" type="text" id="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input name="password" type="password" id="password" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input name="email" type="email" id="email" required>
      </div>
      <div class="form-group">
        <label for="phone">Phone Number:</label>
        <input name="phone" type="text" id="phone" required>
      </div>
      <button name="submit" type="submit" class="btn">Sign Up</button>
    </form>
  </div>
</body>

</html>