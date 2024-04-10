<?php
session_start();

if (isset($_SESSION["username"]) && isset($_COOKIE["user"])) {
  include("../tools/navbar.php");
  require_once '../config.php';
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  if (!empty($_POST["new_task"])) {
    // Sanitize user input

    if (!empty($_POST["task_name"] && $_POST["task_desc"] && $_POST["task_date"])) {
      $task_name = $_POST["task_name"];
      $task_desc = $_POST["task_desc"];
      $task_date = $_POST["task_date"];
      $user_id = $_SESSION["user_id"];

      // Prepare and execute the SQL statement to insert a new task
      $stmt = $conn->prepare("INSERT INTO tasks (task_name, task_description, task_date, task_status, user_id) VALUES (?, ?, ?, ?, ?)");
      $task_status = 0; // Default status
      $stmt->bind_param("ssssi", $task_name, $task_desc, $task_date, $task_status, $user_id);

      if ($stmt->execute()) {
        // Log successful insertion
        error_log("New record inserted successfully");

        // Redirect to the same page to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit(); // Ensure no further code execution after the redirect
      } else {
        echo "Error: " . $stmt->error;
      }
    }
  }


  if (!empty($_POST["change_status"])) {
    // Retrieve the task ID from the form submission
    $task_id = $_POST["task_id"];

    // Fetch current task status
    $stmt = $conn->prepare("SELECT task_status FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $current_status = $row["task_status"];

    // Calculate new status
    $new_status = ($current_status == 0) ? 1 : 0;

    // Update task status
    $stmt = $conn->prepare("UPDATE tasks SET task_status = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_status, $task_id);
    $stmt->execute();
  }

  if (!empty($_POST["delete"])) {
    // Retrieve the task ID from the form submission
    $task_id = $_POST["task_id"];

    // Delete task
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
  }


  // Fetch and display user's tasks
  $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ?");
  $stmt->bind_param("i", $_SESSION["user_id"]);
  $stmt->execute();
  $result = $stmt->get_result();
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
      body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

      .profile-info {
        padding: 20px;
        border-bottom: 1px solid #ddd;
      }

      .profile-info div {
        margin-bottom: 10px;
        color: #333;
      }

      .profile-info div:last-child {
        margin-bottom: 0;
      }

      .form-input {
        padding: 20px;
        border-bottom: 1px solid #ddd;
      }

      .form-input label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
      }

      .form-input input[type="text"],
      .form-input input[type="date"] {
        width: calc(100% - 10px);
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
        font-size: 16px;
        color: #333;
      }

      .form-input input[type="submit"] {
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 16px;
        text-transform: uppercase;
        transition: background-color 0.3s ease;
      }

      .form-input input[type="submit"]:hover {
        background-color: #0056b3;
      }

      .task-table {
        width: 100%;
        border-collapse: collapse;
      }

      .task-table th,
      .task-table td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: left;
        color: #333;
      }

      .task-table th {
        background-color: #f2f2f2;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 14px;
      }

      .task-actions form {
        display: inline;
        margin-right: 10px;
      }

      .task-actions form input[type="submit"] {
        padding: 8px 16px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        text-transform: uppercase;
        transition: background-color 0.3s ease;
      }

      .task-actions form input[type="submit"]:hover {
        background-color: #0056b3;
      }
    </style>
  </head>

  <body>
    <div class="container">
      <div class="header">
        <h1>Welcome to your Task Manager Page</h1>
      </div>
      <div class="profile-info">
        <div>User ID: <?php echo $_SESSION["user_id"]; ?></div>
        <div>Email: <?php echo $_SESSION["email"]; ?></div>
        <div>Username: <?php echo $_SESSION["username"]; ?></div>
        <div>Password: <?php echo $_SESSION["password"]; ?></div>
        <div>Phone: <?php echo $_SESSION["phone"]; ?></div>
      </div>
      <div class="form-input">
        <form action="user_home_page.php" method="post">
          <label for="task_name">Task Name:</label>
          <input type="text" name="task_name" id="task_name">
          <label for="task_desc">Task Description:</label>
          <input type="text" name="task_desc" id="task_desc">
          <label for="task_date">Task Date:</label>
          <input type="date" name="task_date" id="task_date">
          <input type="submit" value="Add Task" name="new_task">
        </form>
      </div>
      <section>
        <h2>User's Tasks</h2>
        <table class="task-table">
          <thead>
            <tr>
              <th>Task Name</th>
              <th>Description</th>
              <th>Date</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row["task_name"] . "</td>";
              echo "<td>" . $row["task_description"] . "</td>";
              echo "<td>" . $row["task_date"] . "</td>";
              echo "<td>" . ($row["task_status"] ? "Completed" : "In Progress") . "</td>";
              echo "<td class='task-actions'>
                    <form action='' method='post'>
                      <input type='hidden' name='task_id' value='" . $row["id"] . "'>
                      <input type='submit' name='delete' value='Delete'>
                    </form>
                    <form action='' method='post'>
                      <input type='hidden' name='task_id' value='" . $row["id"] . "'>
                      <input type='submit' name='change_status' value='Change Status'>
                    </form>
                  </td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </section>
    </div>
  </body>


  </html>


<?php
} else {
  header("Location: ../login.php");
}
?>