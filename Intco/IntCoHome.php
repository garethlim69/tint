<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICHOME</title>
    <link rel="stylesheet" href="IntCoHeader.css">  
    <link rel="stylesheet" href="IntCoHome.css">  
    <script>
      function toggleChat() {
          const chatBox = document.getElementById("chat-box");
          chatBox.style.display = chatBox.style.display === "block" ? "none" : "block";
      }
  </script>
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
           <a href="IntCoHome.php">Home</a>
           
         </div>
   
         </div>
       <div class="profile">
         <img class ="profile_icon"
         src="picture/profile.png">
         <div class="profile_dropdown">
             <a href="ICProfileSetting.php"> <img class="settingicon" src="picture/setting.png"> Setting</a>
             <a href="/T-int/Intco/Intco/Login1.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
            </div> 
       </div>
 
  <!-- content -->

     </div>
    
    <div class="content">
        <div class="grid-container">
            <!-- Create -->
            <div class="grid-item1">
                <img src="picture/create.png" class="grid-image">
                <span class="block">Create</span>
                <img src="picture/down.png" class="down-image">
                <div class="create_dropdown">
                    <a href="IntCoCreateStd.php">Students</a>
                   </div> 
            </div>
              <!-- manage -->
            <div class="grid-item2">
                <img src="picture/manage.png" class="grid-image">
                <span class="block">Manage</span>
                <img src="picture/down.png" class="down-image">
                <div class="manage_dropdown">
                    <a href="IntCoDocuments.php">Documents</a>
                    <a href="IntCoTasks.php">Tasks</a>
            
                   </div> 
            </div>
             <!-- Contacts -->
            <div class="grid-item3">
                <img src="picture/contacts.png" class="grid-image">
                <span class="block">Contacts</span>
                <img src="picture/down.png" class="down-image">
                <div class="contacts_dropdown">
                    <a href="IC_Contact_Student.php">Students</a>
                    <a href="IC_Contact_AS.php">Academic Supervisors</a>
                    <a href="IC_Contact_IS.php">Industrial Supervisors</a>
                   </div> 
            </div>
            <div class="grid-item">
              <a class="assign" href="IC_Assign.php">
                  <img src="picture/assign.png" class="grid-image">
                  <p>Assign</p>
              </a>
          </div>
        </div>
     
      
     <!-- Chat bot -->
        <div class="chatbot" onclick="toggleChat()"> 
          <img src="picture/chatbot.png" alt="Right Logo" class="chatbotlogo">
       </div>

     <!-- Chat Window -->
    <div id="chat-box" class="chat-box">
      <div class="chat-header">
          <span>t-int AI Chatbot</span>
          <button class="close-btn" onclick="toggleChat()">✖</button>
      </div>
      <div class="chat-body">
          <p class="chat-message">Hello, I'm t-int, how can I help you?</p>
      </div>
      <div class="chat-footer">
          <input type="text" placeholder="Ask t-int anything..">
          <button>Send</button>
      </div>
  </div>

   
</body>
</html>