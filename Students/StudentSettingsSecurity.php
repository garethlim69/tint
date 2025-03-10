<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two Section Webpage</title>
    <link rel="stylesheet" href="StudentHeader.css">  
    <link rel="stylesheet" href="StudentSettingsSecurity.css">  
    
    
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
        <img class ="profile_icon"
        src="picture/profile.png">
        <div class="profile_dropdown">
            <a href="StudentSettingsProfile.php"> <img class="settingicon" src="picture/setting.png"> Setting</a>
            <a href="/T-int/Intco/Intco/Login1.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
           </div> 
      </div>

      
    </div>
    
    <div>
        <h2 class = "content1">Settings</h2>
        
        <div class="tab">
          <a href="StudentSettingsProfile.php" class="tabword">Profile</a>
          <a href="StudentSettingsNotifications.php" class="tabword">Notifications</a>
          <a href="StudentSettingsSecurity.php" class="tabword1">Security</a>
        </div>
<div class="totalbox">
        <div class="overallbox1">
            <p>Current Password</p>
            <input class= "textbox1" type="password" id="password">
            <img src="picture/eyeopen.png" id="togglePassword" class="eye-icon" alt="Show Password">
            
        </div>

        <div class="overallbox1">
            <p>New Password</p>
            <input class= "textbox1" type="password" id="password2">
            <img src="picture/eyeopen.png" id="togglePassword2" class="eye-icon" alt="Show Password">
        </div>

        <div class="overallbox1">
            <p>Repeat Password</p>
            <input class= "textbox1" type="password" id="password3">
            <img src="picture/eyeopen.png" id="togglePassword3" class="eye-icon" alt="Show Password">
        </div>
        <button class="chgpw">Change Password</button>
    </div>

    </div>

    <script src="pw.js"></script>
</body>
</html>