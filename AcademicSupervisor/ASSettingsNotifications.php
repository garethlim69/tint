<?php
require '../Config/profpic.php'; 
require '../Config/db.php'; 
$userEmail =  $_SESSION['id'];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $taskReminder = isset($_POST["taskReminder"]) && $_POST["taskReminder"] === "true" ? 1 : 0;
  $reminderDays = isset($_POST["reminderDays"]) ? (int)$_POST["reminderDays"] : 0;

  // Ensure valid range (0 to 14)
  if ($reminderDays < 0 || $reminderDays > 14) {
      echo "Invalid reminder days value.";
      exit();
  }

  // Update user settings
  $query = "UPDATE academicsupervisor SET email_reminders = :reminderDays WHERE email = :email";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':reminderDays', $reminderDays);
  $stmt->bindParam(':email', $userEmail);

  if ($stmt->execute()) {
      echo "success";
  } else {
      echo "error";
  }
  exit();
}

// Fetch user's current email_reminders setting
$query = "SELECT email_reminders FROM academicsupervisor WHERE email = :email";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':email', $userEmail);
$stmt->execute();
$emailReminderDays = $stmt->fetchColumn();

// Determine toggle state (OFF if 0, ON if >0)
$reminderEnabled = ($emailReminderDays > 0) ? "checked" : "";
$selectedDays = ($emailReminderDays > 0) ? $emailReminderDays : "1"; // Default to 1 day if OFF

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings - Notifications</title>
  <link rel="stylesheet" href="ASheader.css">
  <link rel="stylesheet" href="ASSettingsNotifications.css">
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
        <a href="ASHome.php">Home</a>

      </div>

      <div class="navigationbar_link"> Contacts
        <div class="contact">
          <a href="ASContactsStudent.php">Students</a>
          <a href="ASContactsIC.php">Internship Coordinator</a>
          <a href="ASContanctIS.php">Academic Supervisor</a>
        </div>

      </div>

      <div class="navigationbar_link">
        <a href="ISDocument.php">Document</a>
      </div>
      <div class="navigationbar_link">
        <a href="ASTasks.php">Tasks</a>

      </div>
    </div>
    <div class="profile">
    <img class="profile_icon" id="profile-picture" src="<?php echo $_SESSION['profile_picture']; ?>" style="border-radius: 50%;">
      <div class="profile_dropdown">
        <a href="ASProfileSetting.php"> <img class="settingicon" src="picture/setting.png">  Settings</a>
        <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
        </div>
    </div>
  </div>

  <div class="settings-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Settings</h2>
      <ul>
        <li onclick="window.location.href='ASProfileSetting.php'">Profile</li>
        <li class="active">Notifications</li>
        <li onclick="window.location.href='ASSettingsSecurity.php'">Security</li>
      </ul>
    </div>
    <!-- Main Content -->
    <div class="settings-content">
    <div class="profile-section">
        <h2>Email Notifications</h2>
        
        <!-- Task Due Date Reminder Toggle -->
        <div class="notification-setting">
            <label for="taskReminderToggle">Task Due Date Reminder</label>
            <label class="switch">
                <input type="checkbox" id="taskReminderToggle" onchange="toggleReminderSettings()" <?php echo $reminderEnabled; ?>>
                <span class="slider round"></span>
            </label>
        </div>

        <!-- Hidden Subfield for Days in Advance -->
        <div id="reminderDaysContainer" style="display: <?php echo ($emailReminderDays > 0) ? 'block' : 'none'; ?>;">
            <label for="reminderDays">Days in advance to send email reminder:</label>
            <select id="reminderDays" name="reminderDays">
                <?php
                for ($i = 1; $i <= 14; $i++) {
                    $selected = ($i == $selectedDays) ? "selected" : "";
                    echo "<option value=\"$i\" $selected>$i"."</option>";
                }
                ?>
            </select>
        </div>

        <!-- Save Button -->
        <button class="save-btn" onclick="saveNotificationSettings()">Save Settings</button>
    </div>
</div>

<script>
    function toggleReminderSettings() {
        const reminderDaysContainer = document.getElementById("reminderDaysContainer");
        const toggle = document.getElementById("taskReminderToggle");
        
        // Show dropdown only when toggle is checked
        reminderDaysContainer.style.display = toggle.checked ? "block" : "none";
    }

    function saveNotificationSettings() {
        const isReminderEnabled = document.getElementById("taskReminderToggle").checked;
        const reminderDays = isReminderEnabled ? document.getElementById("reminderDays").value : 0;

        fetch("ASSettingsNotifications.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `taskReminder=${isReminderEnabled}&reminderDays=${reminderDays}`
        })
        .then(response => response.text())
        .then(data => alert("Settings updated successfully!"))
        .catch(error => alert("Error updating settings"));
    }
</script>

</script>

</body>
</html>