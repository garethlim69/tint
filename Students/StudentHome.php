<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Academic Supervisor Home</title>
  <link rel="stylesheet" href="StudentHeader.css">
  <link rel="stylesheet" href="StudentHome.css">
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
<!-- Current Tasks -->
<h2 class = "CurretTaskTitle">
    Current Task
</h2>

<!-- GradeFeedBack-->
 <div class="GradeFeedBack">
    <div class="GradeFeedBack_font">
        Grade Feedback Form
    </div>
    <div class ="duedate">
        Due on: 24/08/2024
    </div>
 </div>

 <!-- progress tracker-->
<div class = "progress_tracker">
    Progress Tracker
<div class = "bar">
    <div class = "progress"> </div>
    
</div>
<div class="percentage">
    0%</div>

</div>
 <!--  chatbot-->
    <div class="chatbot">
        <img class ="chaticon"
        src="picture/chatbot.png">
       
       
    </div>
  </body>
</html>