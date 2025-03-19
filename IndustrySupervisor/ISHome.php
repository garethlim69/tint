<?php
require '../Config/db.php';
require '../Config/profpic.php';
$supervisorEmail = $_SESSION['id'];

$stmt = $pdo->prepare("SELECT completed_tasks FROM industrysupervisor WHERE email = ?");
$stmt->execute([$supervisorEmail]);
$supervisorData = $stmt->fetch(PDO::FETCH_ASSOC);

$completedTasks = $supervisorData['completed_tasks'] ?? 0;
$taskCountStmt = $pdo->query("SELECT COUNT(*) AS total_tasks FROM istasks");
$totalTasks = $taskCountStmt->fetch(PDO::FETCH_ASSOC)['total_tasks'] ?? 1;
$progressPercentage = ($completedTasks / $totalTasks) * 100;
$progressPercentage = round($progressPercentage, 1);

$taskStmt = $pdo->prepare("SELECT task_name, due_date FROM istasks ORDER BY due_date ASC LIMIT 1 OFFSET ?");
$taskStmt->execute([$completedTasks]);
$nextTask = $taskStmt->fetch(PDO::FETCH_ASSOC);
$taskName = $nextTask['task_name'] ?? "All Tasks Completed!";
$taskDueDate = isset($nextTask['due_date']) ? date("d/m/Y", strtotime($nextTask['due_date'])) : "N/A";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home</title>
  <link rel="stylesheet" href="ISheader.css">
  <link rel="stylesheet" href="ISHome_body.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
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
        <a href="ISHome.php">Home</a>

      </div>

      <div class="navigationbar_link"> Contacts

        <div class="contact">
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
        <a href="ISProfileSetting.php"> <img class="settingicon" src="picture/setting.png"> Settings</a>
        <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
      </div>
    </div>


  </div>

  <h2 class="CurretTaskTitle">
    Current Task
  </h2>

  <div class="GradeFeedBack">
    <div class="GradeFeedBack_font">
      <?= htmlspecialchars($taskName) ?>
    </div>
    <div class="duedate">
      Due on: <?= htmlspecialchars($taskDueDate) ?>
    </div>
  </div>
  <div class="progress_tracker">
    <h3>Progress Tracker</h3>
    <div class="bar">
      <div class="progress" style="width: <?= $progressPercentage ?>%;"></div>
    </div>
    <div class="percentage"><?= $progressPercentage ?>%</div>
  </div>
</body>

</html>