<?php
require '../Config/db.php';
require '../Config/profpic.php';
$is_email = $_SESSION['id'];


$stmt = $pdo->prepare("SELECT s.student_id, s.name FROM internshipoffer io 
                       JOIN student s ON io.student_id = s.student_id 
                       WHERE io.is_email = :is_email");

$stmt->bindParam(':is_email', $is_email, PDO::PARAM_STR);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Documents</title>
  <link rel="stylesheet" href="ISheader.css">
  <link rel="stylesheet" href="ISDocument.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>
</head>

<body>
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
      <img class="profile_icon" id="profile-picture" src="<?php echo $_SESSION['profile_picture']; ?>" style="border-radius: 50%;">
      <div class="profile_dropdown">
        <a href="ISProfileSetting.php"> <img class="settingicon" src="picture/setting.png"> Settings</a>
        <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
      </div>
    </div>


  </div>
  <div class="title-container">
    <h2 class="title">Documents</h2>
    <!-- Documents -->
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
  <p style="color: grey; text-align: right; padding-right: 20px; font-size: 15px;">(only .docx files accepted)</p>
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <td></td>
          <td>Download Document</td>
          <td>Upload Document</td>
          <td>Last Submitted at</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="alignleft">Evaluation Form</td>
          <td>
            <button class="download-btn" data-document-type="Evaluation Form">
              <img src="picture/download.png" alt="Download" width="35" height="35">
            </button>
          </td>
          <td>
            <input type="file" id="uploadEvaluation" class="upload-input" data-document-type="Evaluation Form" style="display: none;">
            <label for="uploadEvaluation">
              <img src="picture/upload.png" alt="Upload" width="35" height="35" style="cursor: pointer;">
            </label>
          </td>
          <td id="statusEvaluation">Checking...</td>
        </tr>
        <tr>
          <td class="alignleft">Reflective Journal</td>
          <td>
            <button class="download-btn" data-document-type="Reflective Journal">
              <img src="picture/download.png" alt="Download" width="35" height="35">
            </button>
          </td>
          <td>
            <input type="file" id="uploadReflective" class="upload-input" data-document-type="Reflective Journal" style="display: none;">
            <label for="uploadReflective">
              <img src="picture/upload.png" alt="Upload" width="35" height="35" style="cursor: pointer;">
            </label>
          </td>
          <td id="statusReflective">Checking...</td>
        </tr>
      </tbody>

    </table>
  </div>
  <script>
    const supabaseUrl = "https://rbborpwwkrfhkcqvacyz.supabase.co";
    const supabaseAnonKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJiYm9ycHd3a3JmaGtjcXZhY3l6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDAwMzU2OTQsImV4cCI6MjA1NTYxMTY5NH0.pMLuryar6iAlkd110WblQtz8T_XdrKOpZEQHksHpuuM";
    const supabase = window.supabase.createClient(supabaseUrl, supabaseAnonKey);

    const bucketName = "documents";
    let selectedStudentId = "";
    document.getElementById("student").addEventListener("change", function() {
      selectedStudentId = this.value;
      console.log("Selected Student ID:", selectedStudentId);
      checkAllFileStatuses();
    });

    function getDocumentFolder(documentType) {
      return "to mark";
    }
    async function downloadFile(documentType) {
      if (!selectedStudentId) {
        alert("Please select a student first!");
        return;
      }

      const folder = getDocumentFolder(documentType);
      const fileName = `${selectedStudentId}_${documentType}.docx`;
      const filePath = `${folder}/${fileName}`;

      console.log("Attempting to download:", filePath);

      const {
        data,
        error
      } = await supabase.storage.from(bucketName).createSignedUrl(filePath, 60);

      if (error) {
        alert(`Download failed: ${error.message}`);
        console.error("Download Error:", error);
        return;
      }

      const link = document.createElement("a");
      link.href = data.signedUrl;
      link.download = fileName;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
    async function uploadFile(file, documentType, statusId) {
      if (!file) return;
      if (!selectedStudentId) {
        alert("Please select a student first!");
        return;
      }

      const fileExtension = file.name.split('.').pop().toLowerCase();
      if (fileExtension !== 'docx') {
        alert("Only .docx files are allowed!");
        return;
      }
      const fileName = `${selectedStudentId}_${documentType}.${fileExtension}`;
      const filePath = `to mark/${fileName}`;

      console.log(`Uploading to: ${filePath}`);

      const {
        data,
        error
      } = await supabase.storage.from(bucketName).upload(filePath, file, {
        upsert: true
      });

      if (error) {
        alert(`Upload failed: ${error.message}`);
        console.error("Upload Error:", error);
      } else {
        alert(`${documentType} successfully uploaded!`);
        checkFileStatus(documentType, statusId);
      }
    }
    document.querySelectorAll('.download-btn').forEach(button => {
      button.addEventListener("click", function() {
        const documentType = this.getAttribute("data-document-type");
        downloadFile(documentType);
      });
    });
    document.querySelectorAll('.upload-input').forEach(input => {
      input.addEventListener("change", function() {
        const file = this.files[0];
        const documentType = this.getAttribute("data-document-type");
        uploadFile(file, documentType);
      });
    });
    async function checkFileStatus(documentType, statusId) {
      const statusElement = document.getElementById(statusId);
      if (!statusElement) return;

      if (!selectedStudentId) {
        statusElement.innerText = "No Student Selected";
        return;
      }

      const folder = getDocumentFolder(documentType);
      const fileName = `${selectedStudentId}_${documentType}.docx`;
      const filePath = `${folder}/${fileName}`;

      console.log(`Checking file: ${filePath}`);
      const {
        data,
        error
      } = await supabase.storage.from(bucketName).list(folder);

      if (error) {
        console.error(`Error fetching files from '${folder}':`, error.message);
        statusElement.innerText = "Not Found";
        return;
      }
      const file = data.find(file => file.name === fileName);

      if (!file) {
        statusElement.innerText = "Not Yet Submitted";
        return;
      }

      console.log("File Found:", file);
      const lastUpdated = file.updated_at || "Unknown Time";
      const formattedDate = lastUpdated !== "Unknown Time" ? new Date(lastUpdated).toLocaleString() : "Uploaded (Date Not Available)";
      statusElement.innerText = `${formattedDate}`;
    }

    function checkAllFileStatuses() {
      if (!selectedStudentId) {
        console.log("No student selected. Setting default status.");
        document.getElementById("statusEvaluation").innerText = "No Student Selected";
        document.getElementById("statusReflective").innerText = "No Student Selected";
        return;
      }

      const filesToCheck = [{
          documentType: "Evaluation Form",
          statusId: "statusEvaluation"
        },
        {
          documentType: "Reflective Journal",
          statusId: "statusReflective"
        }
      ];

      filesToCheck.forEach(file => {
        checkFileStatus(file.documentType, file.statusId);
      });
    }
    window.onload = checkAllFileStatuses;
  </script>
</body>

</html>