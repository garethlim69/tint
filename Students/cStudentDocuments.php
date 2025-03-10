<?php
// Supabase Credentials
define("SUPABASE_URL", "https://rbborpwwkrfhkcqvacyz.supabase.co");
define("SUPABASE_KEY", "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJiYm9ycHd3a3JmaGtjcXZhY3l6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDAwMzU2OTQsImV4cCI6MjA1NTYxMTY5NH0.pMLuryar6iAlkd110WblQtz8T_XdrKOpZEQHksHpuuM");
define("STORAGE_BUCKET", "documents");

// SESSION VARIABLES
$student_id = '1928374';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"]) && isset($_POST["file_type"])) {
  $file = $_FILES["file"];
  $filePath = $file["tmp_name"];

  // Get the selected file type from the form
  $fileType = $_POST["file_type"];

  // Assign folder based on document type
  $folder = in_array($fileType, [
    "Industrial Training Visit Form",
    "Evaluation 1",
    "Evaluation 2",
    "Reflective Journal"
  ]) ? "to+fill" : "to+mark";

  // Keep the file extension
  $extension = pathinfo($file["name"], PATHINFO_EXTENSION);

  // Rename file with student ID and place in the correct folder
  $fileName = "$folder/" . $student_id . "_" . $fileType . "." . $extension;

  // Supabase Upload URL
  $url = SUPABASE_URL . "/storage/v1/object/" . STORAGE_BUCKET . "/" . urlencode($fileName) . "?upsert=true";

  $headers = [
    "Authorization: Bearer " . SUPABASE_KEY,
    "Content-Type: application/octet-stream"
  ];

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($filePath));

  $response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if ($http_code == 200) {
    echo "<script>alert('$fileType successfully uploaded!');</script>";
  } else {
    echo "<script>alert('Upload failed: HTTP Code: $http_code, Response: $response File Name: $fileType');</script>";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Documents</title>
  <link rel="stylesheet" href="StudentHeader.css">
  <link rel="stylesheet" href="StudentDocuments.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
  <div class="header">
    <div class="tint_logo">
      <img class="logo" src="picture/logo.png">
      <p class="tint_title">t-int</p>
    </div>

    <div class="navigationbar">
      <div class="navigationbar_link">
        <a href="StudentHome.php">Home</a>
      </div>
      <div class="navigationbar_link">
        <a href="StudentContacts.php">Contacts</a>
      </div>
      <div class="navigationbar_link">
        <a href="StudentDocuments.php">Document</a>
      </div>
      <div class="navigationbar_link">
        <a href="StudentTasks.php">Tasks</a>
      </div>
    </div>

    <div class="profile">
      <img class="profile_icon" src="picture/profile.png">
      <div class="profile_dropdown">
        <a href="StudentSettingsProfile.php">
          <img class="settingicon" src="picture/setting.png">
        </a>
        <a href="/T-int/Intco/Intco/Login1.php">
          <img class="logouticon" src="picture/logout.png"> Log Out
        </a>
      </div>
    </div>
  </div>
  <div class="title" style="padding-right: 100pc; font-size: 21px;">
    <h2>Documents</h2>
  </div>
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <td></td>
          <td>Download Template</td>
          <td>Upload Document</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="alignleft">Industrial Training Visit Form</td>
          <td>
            <a href="">
              <img src="picture/download.png" alt="Clickable Icon" width="35" height="35">
            </a>
          </td>
          <td>
            <form action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="file_type" value="Industrial Training Visit Form">
              <input type="file" name="file" id="fileInput1" style="display: none;" onchange="this.form.submit()">
              <label for="fileInput1">
                <img src="picture/upload.png" alt="Upload" width="35" height="35" style="cursor: pointer;">
              </label>
            </form>
          </td>
          <td>
            <a href="">
              <img src="picture/delete.png" alt="Clickable Icon" width="35" height="35">
            </a>
          </td>
        </tr>
        <tr>
          <td class="alignleft">Evaluation 1</td>
          <td>
            <a href="">
              <img src="picture/download.png" alt="Clickable Icon" width="35" height="35">
            </a>
          </td>
          <td>
            <form action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="file_type" value="Evaluation 1">
              <input type="file" name="file" id="fileInput2" style="display: none;" onchange="this.form.submit()">
              <label for="fileInput2">
                <img src="picture/upload.png" alt="Upload" width="35" height="35" style="cursor: pointer;">
              </label>
            </form>
          </td>
          <td>
            <a href="">
              <img src="picture/delete.png" alt="Clickable Icon" width="35" height="35">
            </a>
          </td>
        </tr>
        <tr>
          <td class="alignleft">Evaluation 2</td>
          <td>
            <a href="">
              <img src="picture/download.png" alt="Clickable Icon" width="35" height="35">
            </a>
          </td>
          <td>
            <form action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="file_type" value="Evaluation 2">
              <input type="file" name="file" id="fileInput3" style="display: none;" onchange="this.form.submit()">
              <label for="fileInput3">
                <img src="picture/upload.png" alt="Upload" width="35" height="35" style="cursor: pointer;">
              </label>
            </form>
          </td>
          <td>
            <a href="">
              <img src="picture/delete.png" alt="Clickable Icon" width="35" height="35">
            </a>
          </td>
        </tr>
        <tr>
          <td class="alignleft">Reflective Journal</td>
          <td>
            <a href="">
              <img src="picture/download.png" alt="Clickable Icon" width="35" height="35">
            </a>
          </td>
          <td>
            <form action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="file_type" value="Reflective Journal">
              <input type="file" name="file" id="fileInput4" style="display: none;" onchange="this.form.submit()">
              <label for="fileInput4">
                <img src="picture/upload.png" alt="Upload" width="35" height="35" style="cursor: pointer;">
              </label>
            </form>
          </td>
          <td>
            <a href="">
              <img src="picture/delete.png" alt="Clickable Icon" width="35" height="35">
            </a>
          </td>
        </tr>
        <tr>
          <td class="alignleft">Weekly Logbook</td>
          <td>
            <a href="">
              <img src="picture/download.png" alt="Clickable Icon" width="35" height="35">
            </a>
          </td>
          <td>
            <form action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="file_type" value="Weekly Logbook">
              <input type="file" name="file" id="fileInput5" style="display: none;" onchange="this.form.submit()">
              <label for="fileInput5">
                <img src="picture/upload.png" alt="Upload" width="35" height="35" style="cursor: pointer;">
              </label>
            </form>
          </td>
          <td>
            <a href="">
              <img src="picture/delete.png" alt="Clickable Icon" width="35" height="35">
            </a>
          </td>
        </tr>
        <tr>
          <td class="alignleft">Industrial Training Report</td>
          <td>
            <a href="">
              <img src="picture/download.png" alt="Clickable Icon" width="35" height="35">
            </a>
          </td>
          <td>
            <form action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="file_type" value="Industrial Training Report">
              <input type="file" name="file" id="fileInput6" style="display: none;" onchange="this.form.submit()">
              <label for="fileInput6">
                <img src="picture/upload.png" alt="Upload" width="35" height="35" style="cursor: pointer;">
              </label>
            </form>
          </td>
          <td>
            <a href="">
              <img src="picture/delete.png" alt="Clickable Icon" width="35" height="35">
            </a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</body>
</html>