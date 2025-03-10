<?php
require '../Config/db.php';

$as_email = "Benjamin.Reed@taylors.edu.my";
$stmt = $pdo->prepare("SELECT s.student_id, s.name FROM internshipoffer io 
                       JOIN student s ON io.student_id = s.student_id 
                       WHERE io.as_email = :as_email");

$stmt->bindParam(':as_email', $as_email, PDO::PARAM_STR);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Academic Supervisor Document</title>
  <link rel="stylesheet" href="ASheader.css">
  <link rel="stylesheet" href="ASDocument.css">
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
        <a href="ISHome.php">Home</a>

      </div>

      <div class="navigationbar_link"> Contacts

        <div class="contact">
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
      <img class="profile_icon"
        src="picture/profile.png">
      <div class="profile_dropdown">
        <a href="ISProfileSetting.php"> <img class="settingicon" src="picture/setting.png"> Setting</a>
        <a href="/T-int/Intco/Intco/Login1.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
      </div>
    </div>
  </div>
  <div class="title-container">
    <h2 class="title">Documents</h2>

    <div class="dropdown-container">
        <label for="student">Select Student:</label>
        <select id="student" name="student">
            <option value="">-- Select --</option>
            <?php foreach ($students as $student): ?>
                <option value="<?= $student['student_id']; ?>">
                    <?= htmlspecialchars($student['name']) . " - " . $student['student_id']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

  <div class="title2" style="padding-left: 50px;font-size: 21px;">
    <h2>To Fill and Submit</h2>
  </div>
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <td></td>
          <td>Download Template</td>
          <td>Upload Submission</td>
          <td></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="alignleft">Industrial Training Visit Form</td>
          <td><i class="fas fa-download">
              <a href="">
                <img src="picture/download.png" alt="Clickable Icon" width="35" height="35">
              </a>
            </i></td>
          <td><i class="fas fa-upload">
              <a href="">
                <img src="picture/upload.png" alt="Clickable Icon" width="35" height="35">
              </a>
            </i></td>
        </tr>
      </tbody>
    </table>
  </div>
  <hr>
  <div class="title2" style="padding-left: 50px;font-size: 21px;">
    <h2>To Download and Mark</h2>
  </div>
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <td></td>
          <td>Download Submission</td>
          <td>Marks Awarded</td>
          <td></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="alignleft">Evaluation Form</td>
          <td><i class="fas fa-download">
              <a href="">
                <img src="picture/download.png" alt="Clickable Icon" width="35" height="35">
              </a>
            </i></td>
          <td>N/A</td>
          <td>
            <j class="fas fa-edit">
              <a href="">
                <img src="picture/edit.webp" alt="Clickable Icon" width="35" height="35">
              </a>
              </i>
          </td>
        </tr>
        <tr>
          <td class="alignleft">Reflective Journal</td>
          <td><i class="fas fa-download">
              <a href="">
                <img src="picture/download.png" alt="Clickable Icon" width="35" height="35">
              </a>
            </i></td>
          <td>N/A</td>
          <td>
            <j class="fas fa-edit">
              <a href="">
                <img src="picture/edit.webp" alt="Clickable Icon" width="35" height="35">
              </a>
              </i>
          </td>
        </tr>
        <tr>
          <td class="alignleft">Weekly Logbook</td>
          <td><i class="fas fa-download">
              <a href="">
                <img src="picture/download.png" alt="Clickable Icon" width="35" height="35">
              </a>
            </i></td>
          <td>N/A</td>
          <td>
            <j class="fas fa-edit">
              <a href="">
                <img src="picture/edit.webp" alt="Clickable Icon" width="35" height="35">
              </a>
              </i>
          </td>
        </tr>
        <tr>
          <td class="alignleft">Industrial Training Report</td>
          <td><i class="fas fa-download">
              <a href="">
                <img src="picture/download.png" alt="Clickable Icon" width="35" height="35">
              </a>
            </i></td>
          <td>N/A</td>
          <td>
            <j class="fas fa-edit">
              <a href="">
                <img src="picture/edit.webp" alt="Clickable Icon" width="35" height="35">
              </a>
              </i>
          </td>
        </tr>
      </tbody>
    </table>
  </div>




  <!-- Documents -->
  <!-- <div class="flexbox">
<h2 class = "Documents">
    Documents
</h2> -->
  <!-- Select Student -->
  <!-- <div class ="Select_Student">  Select Student : 
    <div class = "student_dropbox"> 
         Colon ABUAquaKIlista - 0457834
         <img class = "ShowMoreIcon"src="picture/showmore.webp">
    
    <div class = "student_dropbox_list">
        <a href="ISDocument.php">Polon MaRindsdsdse - 045dsdsdsdsdsddsdsdsdsdsdsdsdsdsds7834</a> 
        <a href="ISDocument.php">Polon MaRine - 0457834</a> 
    </div>
</div>
</div>
</div> -->
  <!-- To fill and Submit  -->
  <!-- <h3 class = "fill_and_submit">
  To Fill and Submit
</h3> -->



  <!-- Submission Part-->
  <!-- <div class ="submission_part">
 <div class = "Submission_Status">
    <div class ="Status" >Download Template
     <div class = "Download">
        <img class= "download_icon" src="picture/download.png" , onclick="">
        <img class = "download_icon" src="picture/download.png">
     
    </div>
    </div>

    <div class ="Status" >Upload Document
      <div class = "Download">
         <img class= "download_icon" src="picture/upload.png" , onclick="">
         <img class = "download_icon" src="picture/upload.png">
      
     </div>
     </div>
    
    </div>
    </div>
 
 
Submission Title
 <div class = "Submission_Title"></div>
 <div class = "Title" >Appraisal 1</div> 
 <div class = "Title" >Appraisal 2</div> 
</div> -->

  <!-- line-->
  <!-- <hr> -->

  <!-- To Download and Mark  -->
  <!-- <h4 class="fill_and_submit">
  To Download and Mark
  </h4> -->
  <!-- edit marks popup-->
  <!-- <div class="popup" id="popup-1">
  <div class="overlay"></div>
  <div class="content">
    <h5 class="edit_title"> <u>Edit Self-reflection Marks</u></h5>
    <p class="Mark_Awarded"> Mark Awarded: 
      <input class="marksinput" type="text"> /20
    </p>
    <div class="Button">
      <button onclick="togglePopup()",  class="cancel_button">Cancel</button>
      <button onclick="togglePopup()",  class="save_button"> Save</button>
    </div>
  </div>
</div> -->

  <!-- Submission Part-->
  <!-- 
<div class ="submission_part">
  <div class = "Submission_Status">

    <div class ="Status" >Download Marking Rubric  
      <div class = "Download">
         <img class= "download_icon" src="picture/download.png" , onclick="">
         
     </div>
     </div>

     <div class ="Status" >Download Submissiom  
      <div class = "Download">
         <img class= "download_icon" src="picture/download.png" , onclick="">
         
     </div>
     </div>
     
     <div class ="Status" >Marks Awards 
         <div class ="Marks">
         <div class="act_marks">15/20</div>
         
         </div>
         <div class ="edit">
             <img class="edit_icon" src="picture/edit.webp" , onclick="togglePopup()">
             
         </div>
     </div>
     </div>
  
   -->
  <!-- Submission Title-->
  <!-- <div class = "Submission_Title"></div>
  <div class = "Title" >Self-reflection</div> 
 
 </div>

<script src="ISDocument.js"></script> -->
</body>

</html>