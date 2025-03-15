<?php
require '../Config/db.php';

// SESSION VARIABLES
$student_id = '1928374';
$supervisor = null;

if ($student_id) {
  $stmt = $pdo->prepare("SELECT 
          isup.name AS industry_supervisor_name,
          isup.email AS industry_supervisor_email,
          isup.phone_number AS industry_supervisor_phone,

          asup.name AS academic_supervisor_name,
          asup.email AS academic_supervisor_email,
          asup.phone_number AS academic_supervisor_phone,

          icoor.name AS internship_coordinator_name,
          icoor.email AS internship_coordinator_email,
          icoor.phone_number AS internship_coordinator_phone
      FROM internshipoffer io
      JOIN industrysupervisor isup ON io.is_email = isup.email
      JOIN academicsupervisor asup ON io.as_email = asup.email
      JOIN student s ON io.student_id = s.student_id
      LEFT JOIN internshipcoordinator icoor ON s.faculty = icoor.faculty
      WHERE io.student_id = CAST(:student_id AS VARCHAR)");

  // Ensure student_id is treated as a string
  $stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
  $stmt->execute();
  $supervisor = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Academic Supervisor Home</title>
  <link rel="stylesheet" href="StudentHeader.css">
  <link rel="stylesheet" href="StudentContacts.css">
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
      <img class="profile_icon"
        src="picture/profile.png">
      <div class="profile_dropdown">
        <a href="StudentSettingsProfile.php"> <img class="settingicon" src="picture/setting.png"> Setting</a>
        <a href="/T-int/Intco/Intco/Login1.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
      </div>
    </div>


  </div>

  <!-- Contact -->
  <h2 class="Contacts_Students">Contacts</h2>

  <!-- StudentContactsTables -->
  <table class="StudentContactsTable">
    <thead>
      <tr>
        <th>Name</th>
        <th>Role</th>
        <th>Email Address</th>
        <th>Phone No.</th>
      </tr>
    </thead>
    <tbody>
        <?php if ($supervisor): ?>
            <tr>
                <td><?= htmlspecialchars($supervisor['industry_supervisor_name']) ?></td>
                <td>Industry Supervisor</td>
                <td><a href="mailto:<?= htmlspecialchars($supervisor['industry_supervisor_email']) ?>"><?= htmlspecialchars($supervisor['industry_supervisor_email']) ?></a></td>
                <td><?= htmlspecialchars($supervisor['industry_supervisor_phone']) ?></td>
            </tr>
            <tr>
                <td><?= htmlspecialchars($supervisor['academic_supervisor_name']) ?></td>
                <td>Academic Supervisor</td>
                <td><a href="mailto:<?= htmlspecialchars($supervisor['academic_supervisor_email']) ?>"><?= htmlspecialchars($supervisor['academic_supervisor_email']) ?></a></td>
                <td><?= htmlspecialchars($supervisor['academic_supervisor_phone']) ?></td>
            </tr>
            <tr>
                <td><?= htmlspecialchars($supervisor['internship_coordinator_name']) ?></td>
                <td>Internship Coordinator</td>
                <td><a href="mailto:<?= htmlspecialchars($supervisor['internship_coordinator_email']) ?>"><?= htmlspecialchars($supervisor['internship_coordinator_email']) ?></a></td>
                <td><?= htmlspecialchars($supervisor['internship_coordinator_phone']) ?></td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="4">No supervisors found for this student.</td>
            </tr>
        <?php endif; ?>
    </tbody>
  </table>
</body>

</html>