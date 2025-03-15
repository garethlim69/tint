<?php
require '../Config/db.php'; // Ensure database connection
require '../Config/profpic.php'; 
$studentID = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $currentPassword = $_POST["current_password"];
  $newPassword = $_POST["new_password"];
  $repeatPassword = $_POST["repeat_password"];

  // Step 1: Validate that new passwords match
  if ($newPassword !== $repeatPassword) {
    echo "<script>alert('New passwords do not match. Please try again.'); window.location.href = 'StudentSettingsSecurity.php';</script>";
    exit();
  }

  // Step 2: Fetch the current hashed password from the database
  $passwordQuery = "SELECT password FROM student WHERE student_id = :student_id";
  $passwordStmt = $pdo->prepare($passwordQuery);
  $passwordStmt->bindParam(':student_id', $studentID);
  $passwordStmt->execute();
  $storedPassword = $passwordStmt->fetchColumn();

  // Step 3: Verify the current password
  if (!password_verify($currentPassword, $storedPassword)) {
    echo "<script>alert('Current password is incorrect. Please try again.'); window.location.href = 'StudentSettingsSecurity.php';</script>";
    exit();
  }

  // Step 4: Hash the new password before saving it
  $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // Step 5: Update the password in the database
  $updatePasswordQuery = "UPDATE student SET password = :new_password WHERE student_id = :student_id";
  $updatePasswordStmt = $pdo->prepare($updatePasswordQuery);
  $updatePasswordStmt->bindParam(':new_password', $hashedNewPassword);
  $updatePasswordStmt->bindParam(':student_id', $studentID);

  if ($updatePasswordStmt->execute()) {
    echo "<script>alert('Password updated successfully!'); window.location.href = 'StudentSettingsSecurity.php';</script>";
    exit();
  } else {
    echo "<script>alert('Error updating password. Please try again.'); window.location.href = 'StudentSettingsSecurity.php';</script>";
    exit();
  }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Security</title>
    <link rel="stylesheet" href="StudentHeader.css">  
    <link rel="stylesheet" href="StudentSettingsSecurity.css">  
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
      <img class="profile_icon" id="profile-picture" src="<?php echo $_SESSION['profile_picture']; ?>" style="border-radius: 50%;">
        <div class="profile_dropdown">
            <a href="StudentSettingsProfile.php"> <img class="settingicon" src="picture/setting.png">  Settings</a>
            <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
           </div> 
      </div>

      
    </div>
    
    <div class="settings-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Settings</h2>
      <ul>
        <li onclick="window.location.href='StudentSettingsProfile.php'">Profile</li>
        <li onclick="window.location.href='StudentSettingsNotifications.php'">Notifications</li>
        <li class="active">Security</li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="settings-content">
      <div class="profile-section">
        <h2>Password</h2>
        <form method="POST">
          <label for="current_password">Current Password</label>
          <input type="password" id="current_password" name="current_password" required>

          <label for="new_password">New Password</label>
          <input type="password" id="new_password" name="new_password" required>

          <label for="repeat_password">Repeat New Password</label>
          <input type="password" id="repeat_password" name="repeat_password" required>

          <button type="submit" class="submit-btn">Update Password</button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>