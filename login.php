<?php
session_start();

// Database connection parameters
require_once 'config.php';

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST["submit"])) {
  if (!empty($_POST["username"]) && !empty($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare SQL statement with placeholders to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // Bind parameters
    $stmt->execute(); // Execute query
    $result = $stmt->get_result(); // Get result

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      if (password_verify($password, $row['password'])) {
        // Password is correct, proceed with login
        $_SESSION["username"] = $row["username"];
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["email"] = $row["email"];
        $_SESSION["password"] = $row["password"];
        $_SESSION["phone"] = $row["phone"];
        // Store any other user data you need in the session
        $cookie_name = "user";
        $cookie_value = $row["username"];
        setcookie($cookie_name, $cookie_value, time() + (3600), "/", "", true, true);
        echo "Login successful";
        header("Location: ./protected_pages/user_home_page.php");
        exit;
      } else {
        // Incorrect password
        echo "Invalid password";
      }
    } else {
      // User not found
      echo "User not found";
    }
  }
}

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
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
    input[type="password"] {
      width: 100%;
      padding: 12px;
      border: 2px solid #ccc;
      border-radius: 5px;
      transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
      border-color: #007bff;
      outline: none;
    }

    .btn {
      display: block;
      width: 100%;
      padding: 12px;
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
  <?php
  include("./tools/unprotected_navbar.php")
  ?>
</head>

<body>
  <div class="container">
    <h1>User Login</h1>
    <form action="login.php" method="post">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
      </div>
      <input type="submit" name="submit" class="btn" />
    </form>
  </div>
</body>

</html>