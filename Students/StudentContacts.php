<?php
require '../Config/db.php';

$stmt = $pdo->query("SELECT name, program_name, email, phone_number FROM student");
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Academic Supervisor Home</title>
  <link rel="stylesheet" href="StudentHeader.css">
  <link rel="stylesheet" href="StudentContacts.css">
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
  <div class="textbox_container">
    <input class="textbox" type="text" placeholder="Search..">
  </div>

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
      <?php foreach ($contacts as $contact): ?>
        <tr>
          <td><?= htmlspecialchars($contact['name']) ?></td>
          <td><?= htmlspecialchars($contact['program_name']) ?></td>
          <td><a href="mailto:<?= htmlspecialchars($contact['email']) ?>"><?= htmlspecialchars($contact['email']) ?></a></td>
          <td><?= htmlspecialchars($contact['phone_number']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>

</html>