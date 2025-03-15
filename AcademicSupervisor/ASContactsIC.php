<?php
require '../Config/db.php';

// SESSION VARS
$academic_supervisor_email = 'Charlotte.Harrison@taylors.edu.my'; // Replace with actual academic supervisor email

$stmt = $pdo->prepare("
    SELECT DISTINCT 
        ic.name AS coordinator_name, 
        ic.email AS coordinator_email, 
        ic.phone_number AS coordinator_phone
    FROM internshipcoordinator ic
    JOIN student s ON ic.faculty = s.faculty
    JOIN internshipoffer io ON s.student_id = io.student_id
    WHERE io.as_email = :academic_supervisor_email
");

$stmt->execute(['academic_supervisor_email' => $academic_supervisor_email]);
$coordinator = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact - Internship Coordinator</title>
  <link rel="stylesheet" href="ASheader.css">
  <link rel="stylesheet" href="ASContactIC_body.css">
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
<!-- Contact - Industry Supervisor-->
<h2 class = "Contacts_IC">Contacts - Internship Coordinator</h2>


<!-- ISContactsTables -->
  <table class="IContactsTable">
    <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Phone No.</th>
    </tr>
  </thead>
    <!-- row1 -->
     <tbody>
     <?php if ($coordinator): ?>
        <tr>
            <td><?= htmlspecialchars($coordinator['coordinator_name']) ?></td>
            <td><a href="mailto:<?= htmlspecialchars($coordinator['coordinator_email']) ?>">
                <?= htmlspecialchars($coordinator['coordinator_email']) ?>
            </a></td>
            <td><?= htmlspecialchars($coordinator['coordinator_phone']) ?></td>
        </tr>
    <?php else: ?>
        <tr>
            <td colspan="3">No internship coordinator found for this academic supervisor.</td>
        </tr>
    <?php endif; ?>
  </tbody>
  </table>
  </body>
</html>
