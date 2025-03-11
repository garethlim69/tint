<?php
require '../Config/db.php';

// SESSION VARS
$supervisorEmail = 'Ava.Bennett@taylors.edu.my'; // Replace with actual session variable

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"]) && isset($_POST["completed_tasks"])) {
    $supervisorEmail = $_POST["email"];
    $completedTasks = intval($_POST["completed_tasks"]);
    $action = $_POST["action"]; // "increase" or "decrease"

    if (!empty($supervisorEmail)) {
        if ($action === "increase") {
            $stmt = $pdo->prepare("UPDATE academicsupervisor SET completed_tasks = ? WHERE email = ?");
            $stmt->execute([$completedTasks, $supervisorEmail]);
        } elseif ($action === "decrease") {
            $stmt = $pdo->prepare("UPDATE academicsupervisor SET completed_tasks = ? WHERE email = ?");
            $stmt->execute([$completedTasks - 1, $supervisorEmail]);
        }

        echo json_encode(["status" => "success", "message" => "Task updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid email"]);
    }
    exit;
}

// Fetch completed tasks count from academicsupervisor table
$stmt = $pdo->prepare("SELECT completed_tasks FROM academicsupervisor WHERE email = ?");
$stmt->execute([$supervisorEmail]);
$supervisorData = $stmt->fetch(PDO::FETCH_ASSOC);
$completedTasks = $supervisorData['completed_tasks'] ?? 0;

// Fetch all tasks
$stmt = $pdo->query("SELECT task_name, due_date FROM astasks ORDER BY due_date ASC");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tasks</title>
    <link rel="stylesheet" href="ASheader.css">
    <link rel="stylesheet" href="ASTask.css">
    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  </head>
  <body>
    <!-- navigationbar -->
    <div class = "header"
    >
      <div class = "tint_logo">
        <img class="logo" 
        src="picture/logo.png" >
        <p class ="tint_title">
            t-int
        </p>
      </div>
     
      <div class = "navigationbar">
        <div class="navigationbar_link">
          <a href="ASHome.php">Home</a>
          
        </div>
       
       <div class="navigationbar_link"> Contacts

        <div class ="contact">
            <a href="ASContactsStudent.php">Students</a>
            <a href="ASContactsIC.php">Internship Coordinator</a>
            <a href="ASContanctIS.php">Industry Supervisor</a>
        </div>

       </div>
       
            <div class="navigationbar_link">
              <a href="ASDocument.php">Document</a>
            </div>
        <div class="navigationbar_link"> 
          <a href="ASTasks.php">Tasks</a>

        </div>
        </div>
      <div class="profile">
        <img class ="profile_icon"
        src="picture/profile.png">
        <div class="profile_dropdown">
            <a href="ASProfileSetting.php"> <img class="settingicon" src="picture/setting.png"> Setting</a>
            <a href="/T-int/Intco/Intco/Login1.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
          </div> 
          </div>
        </div>
        <div class="title" style="padding-right: 100pc; font-size: 21px;">
        <h2>Tasks</h2>
    </div>

    <div class="task-container">
        <?php if (!empty($tasks)): ?>
            <?php foreach ($tasks as $index => $task): ?>
                <?php
                $isCompleted = ($index < $completedTasks);
                $isLatestCompleted = ($index + 1 == $completedTasks);
                $dotClass = $isCompleted ? "green" : "red";
                $taskStatusText = $isCompleted
                    ? "Task Completed!"
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
                let supervisorEmail = <?= json_encode($supervisorEmail); ?>;
                let isLatest = $(this).attr("data-latest") === "true"; 
                let action;

                if (this.checked && !isLatest) {
                    action = "increase"; 
                } else if (this.checked && isLatest) {
                    action = "decrease"; 
                    event.preventDefault();
                    $(this).prop("checked", false);
                } else {
                    return;
                }

                $.ajax({
                    url: "ASTasks.php",
                    type: "POST",
                    data: {
                        email: supervisorEmail,
                        completed_tasks: taskNumber,
                        action: action
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            location.reload();
                        } else {
                            alert("Error: " + response.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("AJAX request failed: " + textStatus + " - " + errorThrown);
                    }
                });
            });
        });
    </script>
</body>
</html>