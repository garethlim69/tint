<?php
require_once '../Config/db.php';
require '../Config/profpic.php'; 

// Fetch selected role from POST request (default to "Student")
$role = isset($_POST['role']) ? $_POST['role'] : "Student";

// Map roles to database tables
$taskTable = "";
if ($role == "Student") {
    $taskTable = "studenttasks";
} elseif ($role == "Industry Supervisor") {
    $taskTable = "istasks";
} elseif ($role == "Academic Supervisor") {
    $taskTable = "astasks";
}

// Fetch tasks from the database
$tasks = [];
if ($taskTable !== "") {
  $stmt = $pdo->prepare("SELECT task_name, due_date FROM $taskTable ORDER BY due_date ASC"); // Sorting by soonest due date
  $stmt->execute();
  $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle task updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["task_name_old"], $_POST["task_name"], $_POST["due_date"])) {
    $oldTaskName = $_POST["task_name_old"];
    $newTaskName = $_POST["task_name"];
    $newDueDate = $_POST["due_date"];

    $updateStmt = $pdo->prepare("UPDATE $taskTable SET task_name = ?, due_date = ? WHERE task_name = ?");
    $updateStmt->execute([$newTaskName, $newDueDate, $oldTaskName]);

    exit("Task Updated Successfully!");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tasks</title>
  <link rel="stylesheet" href="IntCoHeader.css">
  <link rel="stylesheet" href="IntCoTasks.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <!-- Navigation Bar -->
  <div class="header">
    <div class="tint_logo">
      <img class="logo" src="picture/logo.png">
      <p class="tint_title">t-int</p>
    </div>

    <div class="navigationbar">
      <div class="navigationbar_link">
        <a href="IntCoHome.php">Home</a>
      </div>
    </div>

    <div class="profile">
    <img class="profile_icon" id="profile-picture" src="<?php echo $_SESSION['profile_picture']; ?>" style="border-radius: 50%;">
    <div class="profile_dropdown">
        <a href="ICProfileSetting.php"><img class="settingicon" src="picture/setting.png">  Settings</a>
        <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
        </div>
    </div>
  </div>

  <div class="title-container">
    <h2 class="title">Documents</h2>
    <div class="dropdown-container">
      <label for="role">Select Role:</label>
      <select id="role" name="role">
        <option value="Student" <?= $role === 'Student' ? 'selected' : '' ?>>Student</option>
        <option value="Industry Supervisor" <?= $role === 'Industry Supervisor' ? 'selected' : '' ?>>Industry Supervisor</option>
        <option value="Academic Supervisor" <?= $role === 'Academic Supervisor' ? 'selected' : '' ?>>Academic Supervisor</option>
      </select>
    </div>
  </div>

  <!-- Task Table -->
  <div class="table-container">
    <table class="task-table">
      <thead>
        <tr>
          <th>Task Name</th>
          <th>Due Date (yyyy/mm/dd)</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <tbody id="tasksContainer">
        <?php foreach ($tasks as $task): ?>
            <tr>
                <td class="alignleft"><?= htmlspecialchars($task['task_name']) ?></td>
                <td><?= htmlspecialchars($task['due_date']) ?></td>
                <td>
                    <button class="edit-task" 
                        data-task="<?= htmlspecialchars($task['task_name']) ?>" 
                        data-due="<?= htmlspecialchars($task['due_date']) ?>"><img src="picture/edit_icon.png" alt="Upload" width="35" height="35" style="cursor: pointer;"></button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
      </tbody>
    </table>
  </div>
  <!-- Edit Task Modal -->
  <div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Task</h2>
        <form id="editTaskForm">
            <input type="hidden" id="task_name_old" name="task_name_old">
            <label>Task Name:</label>
            <input type="text" id="task_name" name="task_name">
            <label>Due Date:</label>
            <input type="date" id="due_date" name="due_date">
            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function () {
    // Handle Role Selection Change
    $("#role").change(function () {
        let selectedRole = $(this).val();
        fetchTasks(selectedRole);
    });

    function fetchTasks(role) {
        $.ajax({
            url: "IntCoTasks.php",
            type: "POST",
            data: { role: role },
            success: function (response) {
                $("#tasksContainer").html($(response).find("#tasksContainer").html());
            },
            error: function () {
                alert("Error loading tasks.");
            }
        });
    }

    // Open Edit Modal
    $(document).on("click", ".edit-task", function () {
        let taskName = $(this).data("task");
        let dueDate = $(this).data("due");

        $("#task_name_old").val(taskName);
        $("#task_name").val(taskName);
        $("#due_date").val(dueDate);
        
        $("#editModal").show();
    });

    // Close Modal
    $(".close").click(function () {
        $("#editModal").hide();
    });

    // Update Task via AJAX
    $("#editTaskForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "IntCoTasks.php",
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                alert(response);
                $("#editModal").hide();
                fetchTasks($("#role").val());
            },
            error: function () {
                alert("Error updating task.");
            }
        });
    });

    // Load tasks for the default role
    fetchTasks($("#role").val());
});
</script>

</body>

</html>