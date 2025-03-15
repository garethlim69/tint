<?php
require '../Config/db.php';
require '../Config/profpic.php'; 

$stmt = $pdo->query("SELECT name, email, phone_number, faculty FROM academicsupervisor");
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contacts - Academic Supervisor</title>
  <link rel="stylesheet" href="IntCoHeader.css">
  <link rel="stylesheet" href="IC_Contact_AS.css">
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
           <a href="IntCoHome.php">Home</a>
           
         </div>
   
         </div>
       <div class="profile">
       <img class="profile_icon" id="profile-picture" src="<?php echo $_SESSION['profile_picture']; ?>" style="border-radius: 50%;">
         <div class="profile_dropdown">
             <a href="ICProfileSetting.php"> <img class="settingicon" src="picture/setting.png">  Settings</a>
             <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
             </div> 
       </div>
 
       
     </div>
<!-- Contact - Industry Supervisor-->
<div class="flexbox">
<h2 class = "Contacts_IS">Contacts - Academic Supervisor</h2>

<!-- searchbar -->
<div class="searchbar">
  <input type="search" id="searchInput" placeholder="Search...">
</div>
</div>
<!-- ASContactsTables -->
  <table class="ISContactsTable">
    <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Phone No.</th>
      <th>Faculty</th>
    </tr>
  </thead>
  <tbody>
      <?php foreach ($contacts as $contact): ?>
        <tr>
          <td><?= htmlspecialchars($contact['name']) ?></td>
          <td><a href="mailto:<?= htmlspecialchars($contact['email']) ?>"><?= htmlspecialchars($contact['email']) ?></a></td>
          <td><?= htmlspecialchars($contact['phone_number']) ?></td>
          <td><?= htmlspecialchars($contact['faculty']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <script>
  document.getElementById("searchInput").addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll(".ISContactsTable tbody tr");

    rows.forEach(row => {
      let text = row.textContent.toLowerCase();
      row.style.display = text.includes(filter) ? "" : "none";
    });
  });
</script>
  </body>
</html>
