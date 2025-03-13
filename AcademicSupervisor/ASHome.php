<?php
require '../Config/db.php';

// SESSION VARS
$supervisorEmail = 'Ava.Bennett@taylors.edu.my'; // Replace with actual session variable

// Fetch completed tasks count
$stmt = $pdo->prepare("SELECT completed_tasks FROM academicsupervisor WHERE email = ?");
$stmt->execute([$supervisorEmail]);
$supervisorData = $stmt->fetch(PDO::FETCH_ASSOC);
$completedTasks = $supervisorData['completed_tasks'] ?? 0;

// Fetch total tasks count
$taskCountStmt = $pdo->query("SELECT COUNT(*) AS total_tasks FROM astasks");
$totalTasks = $taskCountStmt->fetch(PDO::FETCH_ASSOC)['total_tasks'] ?? 1; // Default to 1 to avoid division by zero

// Calculate progress percentage
$progressPercentage = ($completedTasks / $totalTasks) * 100;
$progressPercentage = round($progressPercentage, 1); // Round to 1 decimal place

// Fetch latest task to be completed
$taskStmt = $pdo->prepare("SELECT task_name, due_date FROM astasks ORDER BY due_date ASC LIMIT 1 OFFSET ?");
$taskStmt->execute([$completedTasks]);
$nextTask = $taskStmt->fetch(PDO::FETCH_ASSOC);

// Default values if no tasks are left
$taskName = $nextTask['task_name'] ?? "All Tasks Completed!";
$taskDueDate = isset($nextTask['due_date']) ? date("d/m/Y", strtotime($nextTask['due_date'])) : "N/A";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Academic Supervisor Home</title>
  <link rel="stylesheet" href="ASheader.css">
  <link rel="stylesheet" href="ASHome_body.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
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
<!-- Current Tasks -->
<h2 class = "CurretTaskTitle">
    Current Task
</h2>

<!-- GradeFeedBack-->
<div class="GradeFeedBack">
        <div class="GradeFeedBack_font">
            <?= htmlspecialchars($taskName) ?>
        </div>
        <div class="duedate">
            Due on: <?= htmlspecialchars($taskDueDate) ?>
        </div>
    </div>

 <!-- progress tracker-->
 <div class="progress_tracker">
        <h3>Progress Tracker</h3>
        <div class="bar">
            <div class="progress" style="width: <?= $progressPercentage ?>%;"></div> <!-- Dynamic progress bar -->
        </div>
        <div class="percentage"><?= $progressPercentage ?>%</div>
    </div>
  </body>
</html>
