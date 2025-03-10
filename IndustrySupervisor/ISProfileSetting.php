<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two Section Webpage</title>
    <link rel="stylesheet" href="ISheader.css">  
    <link rel="stylesheet" href="iSprofile.css">  
    
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
            <a href="ISHome.php">Home</a>
            
          </div>
         
         <div class="navigationbar_link"> Contacts
  
          <div class ="contact">
              <a href="ISContactsStudent.php">Students</a>
              <a href="ISContactsIC.php">Internship Coordinator</a>
              <a href="ISContanctAS.php">Academic Supervisor</a>
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
          <img class ="profile_icon"
          src="picture/profile.png">
          <div class="profile_dropdown">
              <a href="ISProfileSetting.php"> <img class="settingicon" src="picture/setting.png"> Setting</a>
              <a href="/T-int/Intco/Intco/Login1.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
            </div> 
        </div>
  
        
      </div>
    
    <!-- Settings -->
<h2 class = "content1">Settings</h2>
<div class="textbox_container">
<input class= "textbox" type="text" placeholder="Search..">
</div>
        <div class="tab">
            <a href="ISProfileSetting.php" class="tabword1">Profile</a>
            <a href="ISSettingsNotifications.php" class="tabword">Notifications</a>
            <a href="ISSettingsSecurity.php" class="tabword">Security</a>
        </div>

        <div class="image">
            <img src="picture/profile.png" class="imagelogo">
            <div class="buttonprofile">
   <button class="upload">Upload Profile Picture</button>
   <button class="remove">Remove Profile Picture</button>

    
        </div>
        </div>

        <div class="overallbox1">
            <p>Name</p>
            <input class= "textbox1" type="text">
        </div>

        <div class="overallbox1">
            <p>Email</p>
            <input class= "textbox1" type="text" placeholder="0123456@sd.taylors.edu.my">
        </div>

        <div class="overallbox1">
            <p>Compnay Name</p>
            <input class= "textbox1" type="text">
        </div>


    </div>
</body>
</html>