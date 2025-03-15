<?php
require '../Config/db.php';

// SESSION VARS
$industry_supervisor_email = 'Amelia.Mitchell@samsung.com'; // Replace with actual email

$stmt = $pdo->prepare("
    SELECT DISTINCT 
        ic.name AS coordinator_name, 
        ic.email AS coordinator_email, 
        ic.phone_number AS coordinator_phone, 
        ic.faculty AS coordinator_faculty
    FROM internshipcoordinator ic
    JOIN student s ON ic.faculty = s.faculty
    JOIN internshipoffer io ON s.student_id = io.student_id
    WHERE io.is_email = :industry_supervisor_email
");

$stmt->execute(['industry_supervisor_email' => $industry_supervisor_email]);
$coordinators = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact - Internship Coordinator</title>
  <link rel="stylesheet" href="ISheader.css">
  <link rel="stylesheet" href="ISContactIC_body.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
</head>
  <body>
    <!-- navigationbar -->
    <div class = "header"
    >
      <div class = "tint_logo">
        <img class="logo" 
        src="/picture/logo.png" >
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
            <a href="ISContanctAS.php">Academic Supervisor</a>
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
        <img class ="profile_icon"
        src="picture/profile.png">
        <div class="profile_dropdown">
            <a href="ISProfileSetting.php"> <img class="settingicon" src="picture/setting.png"> Setting</a>
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
      <th>Faculty</th>
    </tr>
  </thead>
    <!-- row1 -->
     <tbody>
     <?php if (count($coordinators) > 0): ?>
        <?php foreach ($coordinators as $coordinator): ?>
            <tr>
                <td><?= htmlspecialchars($coordinator['coordinator_name']) ?></td>
                <td><a href="mailto:<?= htmlspecialchars($coordinator['coordinator_email']) ?>"><?= htmlspecialchars($coordinator['coordinator_email']) ?></a></td>
                <td><?= htmlspecialchars($coordinator['coordinator_phone']) ?></td>
                <td><?= htmlspecialchars($coordinator['coordinator_faculty']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No internship coordinators found for this industry supervisor.</td>
        </tr>
    <?php endif; ?>
  </tbody>
  </table>
  </body>
</html>
