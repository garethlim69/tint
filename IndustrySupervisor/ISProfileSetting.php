<?php

require '../Config/profpic.php';
require '../Config/db.php'; // Include database connection

$userEmail = $_SESSION['id'];

// Fetch user details from the database
$query = "SELECT name, email, company_name FROM industrysupervisor WHERE email = :email";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':email', $userEmail);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings - Profile</title>
  <link rel="stylesheet" href="ISheader.css">
  <link rel="stylesheet" href="ISProfile.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>
</head>

<body onload="loadProfilePicture()">
  <div>
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
        <img class="profile_icon" id="profile-picture"
          src="picture/profile.png" style="border-radius: 50%;">
        <div class="profile_dropdown">
          <a href="ISProfileSetting.php"> <img class="settingicon" src="picture/setting.png">  Settings</a>
          <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
          </div>
      </div>


    </div>
    <div class="settings-container">
      <!-- Sidebar -->
      <div class="sidebar">
        <h2>Settings</h2>
        <ul>
    <li class="active">Profile</li>
    <li onclick="window.location.href='ISSettingsNotifications.php'">Notifications</li>
    <li onclick="window.location.href='ISSettingsSecurity.php'">Security</li>
</ul>

      </div>

      <!-- Main Content -->
      <div class="settings-content">

        <div class="profile-section">
          <div class="profile-picture-container">
            <img id="profile-picture" src="picture/profile.png" alt="Profile Picture" width="100">
            <div class="profile-buttons">
              <input type="file" id="profile-upload" accept="image/png">
              <button class="upload-btn" onclick="uploadProfilePicture()">Upload Profile Picture</button>
              <button class="remove-btn" onclick="removeProfilePicture()">Remove Profile Picture</button>
            </div>
          </div>

          <form method="POST">
            <div class="form-container">
              <label for="name">Name</label>
              <input type="text" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>

              <label for="email">Email</label>
              <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

              <label for="company_name">Company Name</label>
              <input type="text" id="company_name" value="<?php echo htmlspecialchars($user['company_name']); ?>" readonly>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  </div>
  <script>
    const userEmail = "<?php echo $userEmail; ?>";
    const supabaseUrl = "https://rbborpwwkrfhkcqvacyz.supabase.co"; // Replace with your actual Supabase URL
    const supabaseAnonKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJiYm9ycHd3a3JmaGtjcXZhY3l6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDAwMzU2OTQsImV4cCI6MjA1NTYxMTY5NH0.pMLuryar6iAlkd110WblQtz8T_XdrKOpZEQHksHpuuM"; // Replace with your actual Supabase Anon Key

    const supabase = window.supabase.createClient(supabaseUrl, supabaseAnonKey);

    async function uploadProfilePicture() {
      const fileInput = document.getElementById("profile-upload");
      const file = fileInput.files[0]; // Get selected file

      if (!file) {
        alert("Please select an image to upload.");
        return;
      }

      if (file.type !== "image/png") {
        alert("Only PNG files are allowed. Please upload a PNG image.");
        return;
      }

      const filePath = `${userEmail}.png`; // Use session email

      // Upload file to Supabase Storage
      const {
        data,
        error
      } = await supabase.storage
        .from("profile-pictures")
        .upload(filePath, file, {
          upsert: true, // Overwrite existing file if exists
          contentType: file.type
        });

      if (error) {
        console.error("Upload failed:", error.message);
        alert("Failed to upload profile picture.");
        return;
      }

      // Get public URL for the uploaded image
      const {
        data: urlData
      } = supabase.storage
        .from("profile-pictures")
        .getPublicUrl(filePath);

      // Update profile picture preview
      document.querySelectorAll("#profile-picture").forEach(img => {
        img.src = urlData.publicUrl; // Set to default profile picture
      });

      alert("Profile picture uploaded successfully!");
    }



    async function removeProfilePicture() {
      const confirmDelete = confirm("Are you sure you want to remove your profile picture?");
      if (!confirmDelete) return;

      const filePath = `${userEmail}.png`; // Profile picture path in Supabase

      // Delete the profile picture from Supabase Storage
      const {
        error
      } = await supabase.storage
        .from("profile-pictures")
        .remove([filePath]);

      if (error) {
        console.error("Failed to delete:", error.message);
        alert("Error deleting profile picture.");
        return;
      }

      //Reset profile picture to default across the UI
      document.querySelectorAll("#profile-picture").forEach(img => {
        img.src = "picture/profile.png"; // Set to default profile picture
      });

      alert("Profile picture removed successfully!");
    }

    async function loadProfilePicture() {
      const filePath = `${userEmail}.png`; // Path to user's profile picture

      // Get the public URL from Supabase Storage
      const {
        data
      } = supabase.storage.from("profile-pictures").getPublicUrl(filePath);

      if (data.publicUrl) {
        try {
          const response = await fetch(data.publicUrl, {
            method: "HEAD"
          }); // Only check if it exists
          if (response.ok) {
            document.querySelectorAll("#profile-picture").forEach(img => {
              img.src = data.publicUrl; // Set profile picture
            });
            return;
          }
        } catch (error) {
          console.error("Error fetching profile picture:", error);
        }
      }

      //If the image does not exist, set all profile pictures to default
      document.querySelectorAll("#profile-picture").forEach(img => {
        img.src = "picture/profile.png";
      });
    }
    document.addEventListener("DOMContentLoaded", function() {
      loadProfilePicture();
    });
  </script>
</body>

</html>