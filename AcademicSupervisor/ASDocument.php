<?php
require '../Config/db.php';
require '../Config/profpic.php';
$as_email = $_SESSION['id'];

$stmt = $pdo->prepare("SELECT s.student_id, s.name FROM internshipoffer io 
                       JOIN student s ON io.student_id = s.student_id 
                       WHERE io.as_email = :as_email");
$stmt->bindParam(':as_email', $as_email, PDO::PARAM_STR);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

$documents = ["Evaluation Form", "Reflective Journal", "Weekly Logbook", "Industrial Training Report"];

$selected_student = $_GET['student_id'] ?? null;
$student_marks = [];

if ($selected_student) {
  foreach ($documents as $doc) {
    $stmt = $pdo->prepare("SELECT marks FROM student_marks WHERE student_id = :student_id AND document_name = :document_name");
    $stmt->execute(['student_id' => $selected_student, 'document_name' => $doc]);
    $marks = $stmt->fetchColumn();

    if ($marks === false) {
      $stmt = $pdo->prepare("INSERT INTO student_marks (student_id, document_name, marks) VALUES (:student_id, :document_name, NULL)");
      $stmt->execute(['student_id' => $selected_student, 'document_name' => $doc]);
      $marks = null;
    }

    $student_marks[$doc] = ($marks === null) ? "To Be Graded" : $marks;
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'], $_POST['document'])) {
  ob_start();

  $student_id = $_POST['student_id'];
  $document_name = urldecode($_POST['document']);

  $marks = isset($_POST['marks']) && $_POST['marks'] !== "" ? intval($_POST['marks']) : null;

  try {
    $stmt = $pdo->prepare("UPDATE student_marks 
                             SET marks = :marks 
                             WHERE student_id = :student_id 
                             AND document_name = :document_name RETURNING *");

    if ($marks === null) {
      $stmt->bindValue(':marks', null, PDO::PARAM_NULL);
    } else {
      $stmt->bindValue(':marks', $marks, PDO::PARAM_INT);
    }
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
    $stmt->bindParam(':document_name', $document_name, PDO::PARAM_STR);
    $stmt->execute();

    header("Location: ASDocument.php?student_id=" . $student_id);
    exit();
  } catch (PDOException $e) {
    echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
  }
}
$weighted_marks = "Incomplete";

if ($selected_student) {
  if (!in_array(null, $student_marks, true) && !in_array("", $student_marks, true)) {
    $weighted_marks = round(
      (is_numeric($student_marks["Evaluation Form"]) ? $student_marks["Evaluation Form"] / 100 * 45 : 0) +
        (is_numeric($student_marks["Reflective Journal"]) ? $student_marks["Reflective Journal"] / 100 * 30 : 0) +
        (is_numeric($student_marks["Weekly Logbook"]) ? $student_marks["Weekly Logbook"] / 100 * 5 : 0) +
        (is_numeric($student_marks["Industrial Training Report"]) ? $student_marks["Industrial Training Report"] / 100 * 5 : 0)
    );
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Documents</title>
  <link rel="stylesheet" href="ASheader.css">
  <link rel="stylesheet" href="ASDocument.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>
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
        <a href="ASHome.php">Home</a>
      </div>

      <div class="navigationbar_link"> Contacts

        <div class="contact">
          <a href="ASContactsStudent.php">Students</a>
          <a href="ASContactsIC.php">Internship Coordinator</a>
          <a href="ASContanctIS.php">Academic Supervisor</a>
        </div>

      </div>

      <div class="navigationbar_link">
        <a href="ASDocument.php">Document</a>
      </div>
      <div class="navigationbar_link">
        <a href="ASTasks.php">Tasks</a>

      </div>
    </div>
    <div class="profile">
      <img class="profile_icon" id="profile-picture" src="<?php echo $_SESSION['profile_picture']; ?>" style="border-radius: 50%;">
      <div class="profile_dropdown">
        <a href="ASProfileSetting.php"> <img class="settingicon" src="picture/setting.png"> Settings</a>
        <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
      </div>
    </div>
  </div>
  <div class="title-container">
    <h2 class="title">Documents</h2>
    <div class="dropdown-container">
      <label for="student">Select Student:</label>
      <select id="student" name="student" onchange="updatePage()">
        <option value="">-- Select --</option>
        <?php foreach ($students as $student): ?>
          <option value="<?= $student['student_id']; ?>" <?= ($selected_student == $student['student_id']) ? 'selected' : ''; ?>>
            <?= htmlspecialchars($student['name']) . " - " . $student['student_id']; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="title2" style="padding-left: 50px;font-size: 21px;">
    <h2>To Fill and Submit</h2>
    <p style="color: grey; text-align: right; padding-right: 20px; font-size: 15px;">(only .docx files accepted)</p>
  </div>
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <td></td>
          <td>Download Template</td>
          <td>Upload Submission</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="alignleft">Industrial Training Visit Form</td>
          <td>
            <button class="download-btn" data-document-type="Industrial Training Visit Form" data-folder="templates">
              <img src="picture/download.png" alt="Download Template" width="35" height="35">
            </button>
          </td>
          <td>
            <input type="file" id="uploadITVF" class="upload-input" data-document-type="Industrial Training Visit Form" style="display: none;">
            <label for="uploadITVF">
              <img src="picture/upload.png" alt="Upload" width="35" height="35" style="cursor: pointer;">
            </label>
          </td>
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
        <?php foreach ($documents as $doc): ?>
          <tr>
            <td class="alignleft"><?= htmlspecialchars($doc) ?></td>
            <td>
              <button class="download-btn" data-document-type="<?= htmlspecialchars($doc) ?>" data-folder="to mark">
                <img src="picture/download.png" alt="Download Document" width="35" height="35">
              </button>
            </td>
            <td><?= $selected_student ? ($student_marks[$doc] ?? "To Be Graded") : "No Student Selected" ?></td>
            <td>
              <button class="edit-btn" data-student-id="<?= htmlspecialchars($selected_student) ?>" data-document="<?= htmlspecialchars($doc) ?>">
                <img src="picture/edit.webp" alt="Edit Marks" width="35" height="35">
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>

    </table>
  </div>
  <hr>
  <div class="title2" style="padding-left: 50px;font-size: 21px;">
    <h2>Student Grades</h2>
  </div>
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <td></td>
          <td>Weighted Marks</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="alignleft">
            <?= isset($selected_student) ?
              htmlspecialchars($students[array_search($selected_student, array_column($students, 'student_id'))]['name']) .
              " - " . $selected_student : "No Student Selected"; ?>
          </td>
          <td><?= is_numeric($weighted_marks) ? "$weighted_marks/85" : "Incomplete"; ?></td>
        </tr>

      </tbody>
    </table>
    <div id="editModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Marks</h2>
        <form id="editForm" method="POST">
          <input type="hidden" name="student_id" id="student_id">
          <input type="hidden" name="document" id="document">
          <label for="marks">Marks:</label>
          <input type="number" name="marks" id="marks" min="0" max="100" placeholder="Enter marks or leave empty for NULL">
          <button type="submit">Save</button>
        </form>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>

    <script>
      const supabaseUrl = "https://rbborpwwkrfhkcqvacyz.supabase.co";
      const supabaseAnonKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJiYm9ycHd3a3JmaGtjcXZhY3l6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDAwMzU2OTQsImV4cCI6MjA1NTYxMTY5NH0.pMLuryar6iAlkd110WblQtz8T_XdrKOpZEQHksHpuuM";
      const supabase = window.supabase.createClient(supabaseUrl, supabaseAnonKey);

      const bucketName = "documents";
      let selectedStudentId = "";

      document.getElementById("student").addEventListener("change", function() {
        selectedStudentId = this.value.trim();
        console.log("Updated Selected Student ID:", selectedStudentId);
      });

      async function downloadFile(documentType, folder) {
        if (!selectedStudentId && folder !== "templates") {
          alert("Please select a student first!");
          return;
        }

        const fileName = folder === "templates" ? `${documentType}.docx` : `${selectedStudentId}_${documentType}.docx`;
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

      async function uploadFile(file, documentType) {
        if (!file) return;

        const fileExtension = file.name.split('.').pop().toLowerCase();
        if (fileExtension !== 'docx') {
          alert("Only .docx files are allowed!");
          return;
        }

        let studentDropdown = document.getElementById("student");
        selectedStudentId = studentDropdown ? studentDropdown.value.trim() : "";

        if (!selectedStudentId) {
          alert("Please select a student first!");
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
          console.log("Upload Successful:", data);
        }
      }

      document.querySelectorAll('.download-btn').forEach(button => {
        button.addEventListener("click", function() {
          const documentType = this.getAttribute("data-document-type");
          const folder = this.getAttribute("data-folder");
          downloadFile(documentType, folder);
        });
      });

      document.querySelectorAll('.upload-input').forEach(input => {
        input.addEventListener("change", function() {
          const file = this.files[0];
          const documentType = this.getAttribute("data-document-type");
          uploadFile(file, documentType);
        });
      });

      function updatePage() {
        let studentId = document.getElementById("student").value;
        window.location.href = "?student_id=" + studentId;
      }

      document.addEventListener("DOMContentLoaded", function() {
        let modal = document.getElementById("editModal");
        let closeBtn = document.querySelector(".close");

        document.querySelectorAll(".edit-btn").forEach(button => {
          button.addEventListener("click", function() {
            let studentId = this.getAttribute("data-student-id");
            let documentName = this.getAttribute("data-document");

            let studentIdField = document.getElementById("student_id");
            let documentField = document.getElementById("document");

            if (studentIdField && documentField) {
              studentIdField.value = studentId;
              documentField.value = documentName;
              modal.style.display = "block";
            }
          });
        });

        closeBtn.addEventListener("click", function() {
          modal.style.display = "none";
        });

        window.addEventListener("click", function(event) {
          if (event.target === modal) {
            modal.style.display = "none";
          }
        });
      });

      function updatePage() {
        let studentId = document.getElementById("student").value;
        window.location.href = "?student_id=" + studentId;
      }
    </script>

</body>

</html>