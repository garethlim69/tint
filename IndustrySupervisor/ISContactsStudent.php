<?php
require '../Config/db.php';
require '../Config/profpic.php';
$industry_supervisor_email = $_SESSION['id'];

$stmt = $pdo->prepare("
    SELECT 
        s.name AS student_name, 
        s.email AS student_email, 
        s.phone_number AS student_phone,
        a.name AS academic_supervisor_name
    FROM internshipoffer io
    JOIN student s ON io.student_id = s.student_id
    JOIN academicsupervisor a ON io.as_email = a.email
    WHERE io.is_email = :industry_supervisor_email
");

$stmt->execute(['industry_supervisor_email' => $industry_supervisor_email]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contacts - Student</title>
  <link rel="stylesheet" href="ISheader.css">
  <link rel="stylesheet" href="ISContactsStudent_body.css">
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
  <h2 class="Contacts_Students">Contacts - Students</h2>


  <table class="StudentContactsTable">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone No.</th>
        <th>Academic Supervisor</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($students) > 0): ?>
        <?php foreach ($students as $student): ?>
          <tr>
            <td><?= htmlspecialchars($student['student_name']) ?></td>
            <td><a href="mailto:<?= htmlspecialchars($student['student_email']) ?>"><?= htmlspecialchars($student['student_email']) ?></a></td>
            <td><?= htmlspecialchars($student['student_phone']) ?></td>
            <td><?= htmlspecialchars($student['academic_supervisor_name']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5">No students found for this industry supervisor.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>

</html>