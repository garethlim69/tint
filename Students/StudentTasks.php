<?php
require '../Config/db.php';
require '../Config/profpic.php'; 
$studentId = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["student_id"]) && isset($_POST["completed_tasks"])) {
  $studentId = intval($_POST["student_id"]);
  $completedTasks = intval($_POST["completed_tasks"]);
  $action = $_POST["action"]; // "increase" or "decrease"

  if ($studentId > 0) {
    if ($action === "increase") {
      $stmt = $pdo->prepare("UPDATE student SET completed_tasks = ? WHERE student_id = ?");
      $stmt->execute([$completedTasks, $studentId]);
    } elseif ($action === "decrease") {
      $stmt = $pdo->prepare("UPDATE student SET completed_tasks = ? WHERE student_id = ?");
      $stmt->execute([$completedTasks - 1, $studentId]);
    }

    echo json_encode(["status" => "success", "message" => "Task updated successfully"]);
  } else {
    echo json_encode(["status" => "error", "message" => "Invalid student ID"]);
  }
  exit;
}



// Fetch completed tasks count from student table
$stmt = $pdo->prepare("SELECT completed_tasks FROM student WHERE student_id = ?");
$stmt->execute([$studentId]);
$studentData = $stmt->fetch(PDO::FETCH_ASSOC);
$completedTasks = $studentData['completed_tasks'] ?? 0;

// Fetch all tasks
$stmt = $pdo->query("SELECT task_name, due_date FROM studenttasks ORDER BY due_date ASC");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tasks</title>
  <link rel="stylesheet" href="StudentHeader.css">
  <link rel="stylesheet" href="StudentTasks.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <!-- navigationbar -->
  <div class="header">
    <div class="tint_logo">
      <img class="logo"
        src="picture/logo.png">
      <p class="tint_title">
        t-int
      </p>
    </div>

    <div class="navigationbar">
      <div class="navigationbar_link">
        <a href="StudentHome.php">Home</a>

      </div>

      <div class="navigationbar_link"> <a href="StudentContacts.php">Contacts</a>


      </div>

      <div class="navigationbar_link">
        <a href="StudentDocuments.php">Document</a>
      </div>
      <div class="navigationbar_link">
        <a href="StudentTasks.php">Tasks</a>

      </div>
    </div>
    <div class="profile">
    <img class="profile_icon" id="profile-picture" src="<?php echo $_SESSION['profile_picture']; ?>" style="border-radius: 50%;">
      <div class="profile_dropdown">
        <a href="StudentSettingsProfile.php"> <img class="settingicon" src="picture/setting.png">  Settings</a>
        <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
        </div>
    </div>


  </div>
  <!-- Tasks -->
  <div class="title" style="padding-right: 100pc; font-size: 21px;">
    <h2>Tasks</h2>
  </div>
  <div class="task-container">
    <?php if (!empty($tasks)): ?>
      <?php foreach ($tasks as $index => $task): ?>
        <?php
        // Determine if the task is completed
        $isCompleted = ($index < $completedTasks);
        $isLatestCompleted = ($index + 1 == $completedTasks);
        $dotClass = $isCompleted ? "green" : "red";
        $taskStatusText = $isCompleted
          ? "Task Completed! "
          : "Due on: " . date("d/m/Y", strtotime($task['due_date']));
        ?>
        <div class="task <?= $isCompleted ? "completed" : "pending"; ?>">
          <div class="timeline">
            <span class="dot <?= $dotClass; ?>"></span>
          </div>
          <div class="task-content">
            <h3><?= htmlspecialchars($task['task_name']); ?></h3>
            <p class="date"><?= $taskStatusText; ?></p>
          </div>
          <input type="radio" class="task-radio <?= ($isCompleted ? 'forced-checked' : '') ?>"
            data-task-number="<?= $index + 1; ?>"
            name="task"
            <?= $isCompleted ? "checked" : ""; ?>
            <?= ($index + 1 < $completedTasks) ? "disabled" : ""; ?>
            data-latest="<?= $isLatestCompleted ? 'true' : 'false'; ?>">
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No tasks available.</p>
    <?php endif; ?>
  </div>

  <script>
    $(document).ready(function() {
      $(".task-radio").click(function(event) {
        let taskNumber = $(this).data("task-number");
        let studentId = <?= json_encode($studentId); ?>;
        let isLatest = $(this).attr("data-latest") === "true"; // Check if it's the latest completed task
        let action;

        if (this.checked && !isLatest) {
          action = "increase"; // Increase completed_tasks if a new task is checked
        } else if (this.checked && isLatest) {
          action = "decrease"; // Decrease completed_tasks if the latest task is unchecked
          event.preventDefault(); // Prevent default radio behavior
          $(this).prop("checked", false); // Manually uncheck
        } else {
          return; // Do nothing if it's a previously completed task
        }

        console.log("Task Number:", taskNumber, "Action:", action);

        $.ajax({
          url: "StudentTasks.php",
          type: "POST",
          data: {
            student_id: studentId,
            completed_tasks: taskNumber,
            action: action
          },
          dataType: "json",
          success: function(response) {
            if (response.status === "success") {
              console.log("Task updated:", response);
              location.reload();
            } else {
              alert("Error: " + response.message);
              console.error("AJAX Error Response:", response);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            alert("AJAX request failed: " + textStatus + " - " + errorThrown);
            console.error("Full response:", jqXHR.responseText);
          }
        });
      });
    });
  </script>
</body>

</html>