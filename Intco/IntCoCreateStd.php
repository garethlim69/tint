<?php
require '../Config/db.php';
require '../Config/profpic.php';

if (isset($_POST["upload"])) {
  if ($_FILES["fileToUpload"]["error"] == UPLOAD_ERR_NO_FILE) {
    $error_message = "Please select a file to upload!";
  } else {
    $file = $_FILES["fileToUpload"];
    $fileTmpName = $file["tmp_name"];
    $fileExt = pathinfo($file["name"], PATHINFO_EXTENSION);

    if (strtolower($fileExt) !== 'csv') {
      $error_message = "This file type is not allowed. Please upload a CSV file.";
    } else {
      if (($fileload = fopen($fileTmpName, 'r')) !== false) {
        // Read and ignore the header row
        $header = fgetcsv($fileload, 0, ",");

        $_SESSION['csv_data'] = []; // Store data for preview

        while (($data = fgetcsv($fileload, 0, ",")) !== false) {
          // Map CSV columns to variables
          $student_id = trim($data[3]); // Student ID
          $name = trim($data[2]); // Student Name
          $email = trim($data[7]); // Taylor's Official Email
          $phone_number = trim($data[8]); // Mobile No
          $program_name = trim($data[5]); // Programme
          $company_supervisor_name = trim($data[11]); // Company Supervisor
          $industry_supervisor_email = trim($data[12]); // Email Address
          $company_name = trim($data[9]); // Company

          $password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);

          $faculty_query = $pdo->prepare("SELECT faculty FROM internshipcoordinator WHERE email = :email");
          $faculty_query->execute(['email' => $_SESSION['id']]);
          $faculty = $faculty_query->fetchColumn();

          $check_is = $pdo->prepare("SELECT email FROM industrysupervisor WHERE email = :email");
          $check_is->execute([':email' => $industry_supervisor_email]);
          $existing_is_email = $check_is->fetchColumn();

          $_SESSION['csv_data'][] = [
            'student_id' => $student_id,
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone_number,
            'program_name' => $program_name,
            'faculty' => $faculty,
            'password' => $password,
            'company_supervisor_name' => $company_supervisor_name,
            'industry_supervisor_email' => $industry_supervisor_email,
            'company_name' => $company_name,
            'existing_is_email' => $existing_is_email
          ];
        }

        fclose($fileload);
      } else {
        $error_message = "Unable to read CSV file.";
      }
    }
  }
}

if (isset($_POST['confirm_submit']) && !empty($_SESSION['csv_data'])) {
  foreach ($_SESSION['csv_data'] as $row) {
    $hashed_password = password_hash($row['password'], PASSWORD_DEFAULT);

    $insert_student = $pdo->prepare("INSERT INTO student (student_id, name, email, password, phone_number, program_name, faculty, completed_tasks, email_reminders) 
                                         VALUES (:student_id, :name, :email, :password, :phone_number, :program_name, :faculty, 0, 7)");
    $insert_student->execute([
      ':student_id' => $row['student_id'],
      ':name' => $row['name'],
      ':email' => $row['email'],
      ':password' => $hashed_password,
      ':phone_number' => $row['phone_number'],
      ':program_name' => $row['program_name'],
      ':faculty' => $row['faculty']
    ]);

    if (!$row['existing_is_email']) {
      $insert_is = $pdo->prepare("INSERT INTO industrysupervisor (email, name, password, company_name, completed_tasks, email_reminders)
                                        VALUES (:email, :name, :password, :company_name, 0, 7)");
      $insert_is->execute([
        ':email' => $row['industry_supervisor_email'],
        ':name' => $row['company_supervisor_name'],
        ':password' => $hashed_password,
        ':company_name' => $row['company_name']
      ]);
    }

    $insert_offer = $pdo->prepare("INSERT INTO internshipoffer (offer_id, student_id, is_email, as_email)
                                       VALUES (:offer_id, :student_id, :is_email, NULL)");
    $insert_offer->execute([
      ':offer_id' => "int_" . $row['student_id'],
      ':student_id' => $row['student_id'],
      ':is_email' => $row['industry_supervisor_email']
    ]);
  }

  unset($_SESSION['csv_data']);
  header("Location: IntCoCreateStd.php?success=true");
  exit();
}

if (isset($_POST['discard'])) {
  unset($_SESSION['csv_data']); // Clear the preview session
  header("Location: IntCoCreateStd.php"); // Reload page
  exit();
}

?>

<!DOCTYPE html>

<head>
  <meta charset="UTF-8">
  <title>Create</title>
  <link rel="stylesheet" href="IntCoHeader.css">
  <link rel="stylesheet" href="IntCoCreateStd.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
  <style>
    .alert {
      padding: 10px;
      background-color: #f8d7da;
      border: 1px solid #f5c2c7;
      color: #842029;
      border-radius: 4px;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <!-- navigationbar -->
  <div class="header">
    <div class="tint_logo">
      <img class="logo" src="picture/logo.png">
      <p class="tint_title">t-int</p>
    </div>
    <div class="navigationbar">
      <div class="navigationbar_link">
        <a href="IntCoHome.php">Home</a>
      </div>
    </div>
    <div class="profile">
      <img class="profile_icon" id="profile-picture" src="<?php echo $_SESSION['profile_picture']; ?>" style="border-radius: 50%;">
      <div class="profile_dropdown">
        <a href="ICProfileSetting.php"><img class="settingicon" src="picture/setting.png"> Settings</a>
        <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
      </div>
    </div>
  </div>

  <form method="post" enctype="multipart/form-data">
    <div class="button">
      <h2 class="CreateAS">Create</h2>
      <div class="mybutton">
        <label for="fileToUpload">Select a CSV file:</label>
        <input type="file" name="fileToUpload" accept=".csv" required>
        <button class="importbutton" type="submit" name="upload">Upload & Preview</button>
      </div>
    </div>
  </form>

  <?php if (!empty($_SESSION['csv_data'])): ?>
    <h3 class="CreateAS" style="padding-left: 20px;">Preview of Student Records to be Created</h3>
    <form method="post">
      <table border="1" class="StudentContactsTable">
        <thead>
          <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Program Name</th>
            <th>Faculty</th>
            <th>Company Supervisor</th>
            <th>Industry Supervisor Email</th>
            <th>Company</th>
            <th>Industry Supervisor Exists</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($_SESSION['csv_data'] as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['student_id']) ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['phone_number']) ?></td>
              <td><?= htmlspecialchars($row['program_name']) ?></td>
              <td><?= htmlspecialchars($row['faculty']) ?></td>
              <td><?= htmlspecialchars($row['company_supervisor_name']) ?></td>
              <td><?= htmlspecialchars($row['industry_supervisor_email']) ?></td>
              <td><?= htmlspecialchars($row['company_name']) ?></td>
              <td><?= $row['existing_is_email'] ? "Yes" : "No" ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <br>
      <div class="button_buttom" style="text-align: right;">
      <button type="submit" class="discard" name="discard">Discard</button>
      <button type="submit" class="submit" name="confirm_submit">Confirm & Submit</button>
      </div>
    </form>
  <?php endif; ?>

</body>

</html>