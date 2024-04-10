<?php

if (isset($_POST["logout"])) {
  $_SESSION = array();

  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
  }

  session_destroy();

  if (isset($_COOKIE["user"])) {
    setcookie("user", '', time() - 3600, '/');
  }

  header("Location: ../login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0 !important;
    }

    nav {
      background-color: #2c3e50;
      color: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .nav_button {
      border: none !important;
      background-color: #2c3e50;
      font-size: 16px;
      text-decoration: none;
      color: #fff;
      padding: 10px 20px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .nav_button:hover {
      background-color: #34495e;
      cursor: pointer !important;
      ;
    }
  </style>
</head>

<body>
  <nav>
    <div><a class="nav_button" href="../protected_pages/user_home_page.php">Home</a></div>
    <div><a class="nav_button" href="../globa_pages/about_us.php">About Us</a></div>
    <form method="post">
      <input class="nav_button" type="submit" value="Log Out" name="logout">
    </form>
  </nav>
</body>

</html>