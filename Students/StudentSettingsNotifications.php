<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two Section Webpage</title>
    <link rel="stylesheet" href="StudentHeader.css">  
    <link rel="stylesheet" href="StudentSettingsNotifications.css">  
    
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
    
    <div class="content">
        <h2 class = "content1">Settings</h2>

         <div class="tab">
          <a href="StudentSettingsProfile.php" class="tabword">Profile</a>
          <a href="StudentSettingsNotifications.php" class="tabword1">Notifications</a>
          <a href="StudentSettingsSecurity.php" class="tabword">Security</a>
        </div>
         
         <div class="image">
            <div>
   <p class="cap">Email Notifications</p>
   <div class="firstline">
   <p class="inline1">Task Due Date Reminder</p>
   <div class="switchcenter">
   <label class="switch">
    <input type="checkbox">
    <span class="slider"></span>
</label>
</div>
</div>
<div class="secondline">
   <p class="inline2">Days in advance to send email reminders</p>
   <div class="dropdown">
    <button class="dropbtn">7</button>
    <div class="dropdown-content">
        <a href="#">1</a>
        <a href="#">2</a>
        <a href="#">3</a>
        <a href="#">4</a>
        <a href="#">5</a>
        <a href="#">6</a>
        <a href="#">7</a>
    </div>
</div>
</div>
   

    
        </div>
        </div>
       

    </div>
</body>
</html>