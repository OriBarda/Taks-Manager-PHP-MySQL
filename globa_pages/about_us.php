<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
    }

    .container {
      max-width: 800px;
      margin: 20px auto;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .header {
      background-color: #007bff;
      color: #fff;
      padding: 20px;
      text-align: center;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }

    .header h1 {
      margin: 0;
    }

    .content {
      padding: 20px;
      color: #333;
    }

    .content p {
      margin-bottom: 15px;
      line-height: 1.6;
    }

    .content ul {
      margin-bottom: 15px;
    }

    .content ul li {
      list-style-type: disc;
      margin-left: 20px;
    }
  </style>
</head>

<body>
  <?php include("../tools/navbar.php"); ?>
  <div class="container">
    <div class="header">
      <h1>About Us</h1>
    </div>
    <div class="content">
      <p>Welcome to our task manager website! We are dedicated to helping you organize your tasks and increase your productivity.</p>
      <p>Our mission is to provide a user-friendly platform where you can easily create, manage, and track your tasks, whether it's for personal or professional use.</p>
      <p>With our intuitive interface and powerful features, you can:</p>
      <ul>
        <li>Create tasks with detailed descriptions and due dates</li>
        <li>Organize tasks into categories or projects</li>
        <li>Set reminders and notifications for important deadlines</li>
        <li>Collaborate with team members or share tasks with friends and family</li>
        <li>Track your progress and visualize your achievements</li>
      </ul>
      <p>Whether you're a busy professional, a student with multiple assignments, or just someone who wants to stay organized, our task manager has everything you need to stay on top of your tasks and achieve your goals.</p>
      <p>Thank you for choosing our platform. We're here to support you on your journey to productivity!</p>
    </div>
  </div>
</body>

</html>