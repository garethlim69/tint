<?php
require '../Config/db.php';

// SESSION VARS
$as_email = "Charlotte.Harrison@taylors.edu.my";

// Fetch students linked to the academic supervisor
$stmt = $pdo->prepare("SELECT s.student_id, s.name FROM internshipoffer io 
                       JOIN student s ON io.student_id = s.student_id 
                       WHERE io.as_email = :as_email");
$stmt->bindParam(':as_email', $as_email, PDO::PARAM_STR);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Define documents at the start (fixes undefined variable issue)
$documents = ["Evaluation Form", "Reflective Journal", "Weekly Logbook", "Industrial Training Report"];

$selected_student = $_GET['student_id'] ?? null;
$student_marks = [];

if ($selected_student) {
    foreach ($documents as $doc) {
        // Check if marks exist for the student and document
        $stmt = $pdo->prepare("SELECT marks FROM student_marks WHERE student_id = :student_id AND document_name = :document_name");
        $stmt->execute(['student_id' => $selected_student, 'document_name' => $doc]);
        $marks = $stmt->fetchColumn();

        if ($marks === false) {
            // No record found, insert new row with NULL marks
            $stmt = $pdo->prepare("INSERT INTO student_marks (student_id, document_name, marks) VALUES (:student_id, :document_name, NULL)");
            $stmt->execute(['student_id' => $selected_student, 'document_name' => $doc]);
            $marks = null;
        }

        // Store results in an array
        $student_marks[$doc] = ($marks === null) ? "To Be Graded" : $marks;
    }
}

// Handle form submission for updating marks
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'], $_POST['document'])) {
  ob_start(); // Prevent header issues

  $student_id = $_POST['student_id'];
  $document_name = urldecode($_POST['document']); // Fix space encoding

  // Fix: Ensure marks are properly handled
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

      // Debugging
      $updated_row = $stmt->fetch(PDO::FETCH_ASSOC);
      echo "<script>alert('Updated Row: " . json_encode($updated_row) . "');</script>";

      // Redirect to refresh page
      header("Location: ASDocument.php?student_id=" . $student_id);
      exit();

  } catch (PDOException $e) {
      echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
  }
}

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
      <img class="profile_icon"
        src="picture/profile.png">
      <div class="profile_dropdown">
        <a href="ASProfileSetting.php"> <img class="settingicon" src="picture/setting.png"> Setting</a>
        <a href="/T-int/Intco/Intco/Login1.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
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
    // Initialize Supabase Client
    const supabaseUrl = "https://rbborpwwkrfhkcqvacyz.supabase.co"; // Replace with your actual Supabase URL
const supabaseAnonKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJiYm9ycHd3a3JmaGtjcXZhY3l6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDAwMzU2OTQsImV4cCI6MjA1NTYxMTY5NH0.pMLuryar6iAlkd110WblQtz8T_XdrKOpZEQHksHpuuM"; // Replace with your actual Supabase Anon Key
const supabase = window.supabase.createClient(supabaseUrl, supabaseAnonKey);

const bucketName = "documents"; // Ensure this matches your Supabase Storage bucket
    let selectedStudentId = ""; // Variable to store selected student ID

    // Ensure student ID updates properly
document.getElementById("student").addEventListener("change", function () {
    selectedStudentId = this.value.trim(); // Trim to remove spaces
    console.log("Updated Selected Student ID:", selectedStudentId);
});


    // Function to download documents
    async function downloadFile(documentType, folder) {
        if (!selectedStudentId && folder !== "templates") {
            alert("Please select a student first!");
            return;
        }

        // File name format
        const fileName = folder === "templates" ? `${documentType}.docx` : `${selectedStudentId}_${documentType}.docx`;
        const filePath = `${folder}/${fileName}`;

        console.log("Attempting to download:", filePath);

        // Generate signed URL
        const { data, error } = await supabase.storage.from(bucketName).createSignedUrl(filePath, 60);

        if (error) {
            alert(`Download failed: ${error.message}`);
            console.error("Download Error:", error);
            return;
        }

        // Create hidden <a> tag to trigger download
        const link = document.createElement("a");
        link.href = data.signedUrl;
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Function to upload documents
async function uploadFile(file, documentType) {
    if (!file) return;

    // Always fetch the latest student ID
    let studentDropdown = document.getElementById("student");
    selectedStudentId = studentDropdown ? studentDropdown.value.trim() : "";

    if (!selectedStudentId) {
        alert("Please select a student first!");
        return;
    }

    const fileExtension = file.name.split('.').pop();
    const fileName = `${selectedStudentId}_${documentType}.${fileExtension}`;
    const filePath = `to mark/${fileName}`;

    console.log(`Uploading to: ${filePath}`);

    const { data, error } = await supabase.storage.from(bucketName).upload(filePath, file, { upsert: true });

    if (error) {
        alert(`Upload failed: ${error.message}`);
        console.error("Upload Error:", error);
    } else {
        alert(`${documentType} successfully uploaded!`);
        console.log("Upload Successful:", data);
    }
}


    // Attach event listeners to download buttons
    document.querySelectorAll('.download-btn').forEach(button => {
        button.addEventListener("click", function () {
            const documentType = this.getAttribute("data-document-type");
            const folder = this.getAttribute("data-folder");
            downloadFile(documentType, folder);
        });
    });

    // Attach event listeners to upload inputs
    document.querySelectorAll('.upload-input').forEach(input => {
        input.addEventListener("change", function () {
            const file = this.files[0];
            const documentType = this.getAttribute("data-document-type");
            uploadFile(file, documentType);
        });
    });

    function updatePage() {
    let studentId = document.getElementById("student").value;
    window.location.href = "?student_id=" + studentId;
}

document.addEventListener("DOMContentLoaded", function () {
    let modal = document.getElementById("editModal");
    let closeBtn = document.querySelector(".close");

    document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", function () {
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

    closeBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
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