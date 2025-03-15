<?php
require '../Config/db.php';

// SESSION VARS
$academic_supervisor_email = 'Charlotte.Harrison@taylors.edu.my'; // Replace with actual academic supervisor email

$stmt = $pdo->prepare("
    SELECT DISTINCT
        isup.name AS industry_supervisor_name, 
        isup.email AS industry_supervisor_email, 
        isup.phone_number AS industry_supervisor_phone, 
        isup.company_name
    FROM internshipoffer io
    JOIN industrysupervisor isup ON io.is_email = isup.email
    WHERE io.as_email = :academic_supervisor_email
");

$stmt->execute(['academic_supervisor_email' => $academic_supervisor_email]);
$industry_supervisors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact - Internship Supervisor</title>
  <link rel="stylesheet" href="ASheader.css">
  <link rel="stylesheet" href="ASContactIS_body.css">
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
<div class="flexbox">
<h2 class = "Contacts_IS">Contacts - Industry Supervisor</h2>

</div>
<!-- ICContactsTables -->
  <table class="ISContactsTable">
    <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Phone No.</th>
      <th>Company Name</th>
    </tr>
  </thead>
    <!-- row1 -->
     <tbody>
     <?php if ($industry_supervisors): ?>
        <?php foreach ($industry_supervisors as $supervisor): ?>
            <tr>
                <td><?= htmlspecialchars($supervisor['industry_supervisor_name']) ?></td>
                <td><a href="mailto:<?= htmlspecialchars($supervisor['industry_supervisor_email']) ?>">
                    <?= htmlspecialchars($supervisor['industry_supervisor_email']) ?>
                </a></td>
                <td><?= htmlspecialchars($supervisor['industry_supervisor_phone']) ?></td>
                <td><?= htmlspecialchars($supervisor['company_name']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No industry supervisors found for this academic supervisor.</td>
        </tr>
    <?php endif; ?>
  </tbody>
  </table>
  </body>
</html>
