<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
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

    .nav-button {
      border: none;
      background-color: #2c3e50;
      font-size: 16px;
      text-decoration: none;
      color: #fff;
      padding: 10px 20px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .nav-button:hover {
      background-color: #34495e;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <nav>
    <div><a class="nav-button" href="login.php">Log In</a></div>
    <div><a class="nav-button" href="index.php">Sign Up</a></div>
  </nav>
</body>


</html>