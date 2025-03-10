<!DOCTYPE php>
<php>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents</title>
    <link rel="stylesheet" href="StudentHeader.css">  
    <link rel="stylesheet" href="StudentDocuments.css">  
    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    
        <!-- navigationbar -->
    <div class = "header">
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
    
    <div >
        <h2 class = "content1">Documents</h2>

         <div class="templateborder">
            <p class="template">Document Template</p>
            <p class="template">Upload Submission</p>
         </div>

         <div class="templateborder1">
            <p class="docs">Weekly Logbook</p>
            <img class="r1a" src="picture/download.png" class="download">
            <img class="r1b" src="picture/upload.png" class="upload">
         </div>

         <div class="templateborder1">
            <p class="docs1">Appraisal Form</p>
            <img class="r2a" src="picture/download.png" class="download" >
            <img class="r1b" src="picture/upload.png" class="upload">
         </div>

         <div class="templateborder1">
            <p class="docs2">Feedback Form</p>
            <img class="r3a" src="picture/download.png" class="download">
            <img class="r1b" src="picture/upload.png" class="upload">
         </div>
    </div>
</body>
</php>