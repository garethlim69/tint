<?php
require '../Config/db.php';
require '../Config/profpic.php';
$industry_supervisor_email = $_SESSION['id'];

$stmt = $pdo->prepare("
    SELECT DISTINCT 
        a.name AS academic_supervisor_name, 
        a.email AS academic_supervisor_email, 
        a.phone_number AS academic_supervisor_phone,
        a.faculty AS academic_supervisor_faculty
    FROM internshipoffer io
    JOIN academicsupervisor a ON io.as_email = a.email
    WHERE io.is_email = :industry_supervisor_email
");

$stmt->execute(['industry_supervisor_email' => $industry_supervisor_email]);
$academic_supervisors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contacts - Academic Supervisor</title>
  <link rel="stylesheet" href="ISheader.css">
  <link rel="stylesheet" href="ISContactAS_body.css">
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
  <div class="flexbox">
    <h2 class="Contacts_AS">Contacts - Academic Supervisor</h2>
  </div>
  <table class="ISContactsTable">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone No.</th>
        <th>Faculty</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($academic_supervisors) > 0): ?>
        <?php foreach ($academic_supervisors as $as): ?>
          <tr>
            <td><?= htmlspecialchars($as['academic_supervisor_name']) ?></td>
            <td><a href="mailto:<?= htmlspecialchars($as['academic_supervisor_email']) ?>"><?= htmlspecialchars($as['academic_supervisor_email']) ?></a></td>
            <td><?= htmlspecialchars($as['academic_supervisor_phone']) ?></td>
            <td><?= htmlspecialchars($as['academic_supervisor_faculty']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="3">No academic supervisors found for this industry supervisor.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>

</html>