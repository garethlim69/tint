<?php
require '../Config/db.php';
require '../Config/profpic.php';
$supervisorEmail = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"]) && isset($_POST["completed_tasks"])) {
    $supervisorEmail = $_POST["email"];
    $completedTasks = intval($_POST["completed_tasks"]);
    $action = $_POST["action"];

    if (!empty($supervisorEmail)) {
        if ($action === "increase") {
            $stmt = $pdo->prepare("UPDATE industrysupervisor SET completed_tasks = ? WHERE email = ?");
            $stmt->execute([$completedTasks, $supervisorEmail]);
        } elseif ($action === "decrease") {
            $stmt = $pdo->prepare("UPDATE industrysupervisor SET completed_tasks = ? WHERE email = ?");
            $stmt->execute([$completedTasks - 1, $supervisorEmail]);
        }

        echo json_encode(["status" => "success", "message" => "Task updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid email"]);
    }
    exit;
}
 $stmt = $pdo->prepare("SELECT completed_tasks FROM industrysupervisor WHERE email = ?");
$stmt->execute([$supervisorEmail]);
$supervisorData = $stmt->fetch(PDO::FETCH_ASSOC);
$completedTasks = $supervisorData['completed_tasks'] ?? 0;
 $stmt = $pdo->query("SELECT task_name, due_date FROM istasks ORDER BY due_date ASC");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tasks</title>
  <link rel="stylesheet" href="ISheader.css">
  <link rel="stylesheet" href="ISTask.css">
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
          <a href="ISHome.php">Home</a>
          
        </div>
       
       <div class="navigationbar_link"> Contacts

        <div class ="contact">
            <a href="ISContactsStudent.php">Students</a>
            <a href="ISContactsIC.php">Internship Coordinator</a>
            <a href="ISContactAS.php">Academic Supervisor</a>
        </div>

       </div>
       
            <div class="navigationbar_link">
              <a href="ISDocument.php">Document</a>
            </div>
        <div class="navigationbar_link"> 
          <a href="ISTasks.php">Tasks</a>

        </div>
        </div>
      <div class="profile">
      <img class="profile_icon" id="profile-picture" src="<?php echo $_SESSION['profile_picture']; ?>" style="border-radius: 50%;">
        <div class="profile_dropdown">
            <a href="ISProfileSetting.php"> <img class="settingicon" src="picture/setting.png">  Settings</a>
            <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
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
                    url: "ISTasks.php",
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