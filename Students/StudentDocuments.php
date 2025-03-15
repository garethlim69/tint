<?php
// Supabase Credentials
define("SUPABASE_URL", "https://rbborpwwkrfhkcqvacyz.supabase.co");
define("SUPABASE_KEY", "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJiYm9ycHd3a3JmaGtjcXZhY3l6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDAwMzU2OTQsImV4cCI6MjA1NTYxMTY5NH0.pMLuryar6iAlkd110WblQtz8T_XdrKOpZEQHksHpuuM");
define("STORAGE_BUCKET", "documents");

require '../Config/profpic.php'; 
$student_id = $_SESSION['id'];
$file_type = '.docx';
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
  <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>
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
        <a href="StudentDocuments.php">Documents</a>
      </div>
      <div class="navigationbar_link">
        <a href="StudentTasks.php">Tasks</a>
      </div>
    </div>

    <div class="profile">
    <img class="profile_icon" id="profile-picture" src="<?php echo $_SESSION['profile_picture']; ?>" style="border-radius: 50%;">
      <div class="profile_dropdown">
        <a href="StudentSettingsProfile.php">
          <img class="settingicon" src="picture/setting.png">  Settings</a>
        <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
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
          <td>Last Submitted at</td>
        </tr>
      </thead>
      <tbody>
        <?php
        $documents = [
          "Industrial Training Visit Form",
          "Evaluation Form",
          "Reflective Journal",
          "Weekly Logbook",
          "Industrial Training Report"
        ];
        foreach ($documents as $index => $fileType) {
          $folder = in_array($fileType, ["Industrial Training Visit Form", "Evaluation Form", "Reflective Journal"]) ? "to fill" : "to mark";
          $fileName = "$folder/" . $student_id . "_" . $fileType . $file_type;
        ?>
          <tr>
            <td class="alignleft"><?php echo $fileType; ?></td>
            <td>
              <button class="download-template-btn"
                data-file-type="<?php echo $fileType; ?>">
                <img src="picture/download.png" alt="Download Template" width="35" height="35">
              </button>
            </td>
            <td>
              <input type="file" id="fileInput<?php echo $index; ?>"
                style="display: none;"
                data-file-type="<?php echo $fileType; ?>"
                data-folder="<?php echo $folder; ?>">
              <label for="fileInput<?php echo $index; ?>">
                <img src="picture/upload.png" alt="Upload" width="35" height="35" style="cursor: pointer;">
              </label>
            </td>
            <td id="status<?php echo $index; ?>">Checking...</td> <!-- Moved submission status here -->
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <script>
    // Initialize Supabase Client
    const supabaseUrl = "<?php echo SUPABASE_URL; ?>";
    const supabaseAnonKey = "<?php echo SUPABASE_KEY; ?>";
    const supabase = window.supabase.createClient(supabaseUrl, supabaseAnonKey);

    const studentId = "<?php echo $student_id; ?>";
    const bucketName = "<?php echo STORAGE_BUCKET; ?>";

    // Function to check file existence within a specific folder
    async function checkFileStatus(folder, fileName, statusId) {
      const filePath = `${folder}/${fileName}`;

      // Fetch the list of files in the specific folder
      const {
        data,
        error
      } = await supabase.storage.from(bucketName).list(folder);

      if (error) {
        console.error(`Error fetching files from folder '${folder}':`, error.message);
        document.getElementById(statusId).innerText = "To Be Submitted";
        return;
      }

      // Find the exact file
      const file = data.find(file => file.name === fileName);

      if (!file) {
        document.getElementById(statusId).innerText = "To Be Submitted";
        return;
      }

      // Get `updated_at` timestamp from the JSON response
      const lastUpdated = file.updated_at || "Unknown Time";

      // Convert timestamp to readable format
      const formattedDate = lastUpdated !== "Unknown Time" ? new Date(lastUpdated).toLocaleString() : "Uploaded (Date Not Available)";

      // Update the submission status in the table
      document.getElementById(statusId).innerText = `${formattedDate}`;
    }

    async function uploadFile(file, fileType, folder, statusId) {
      if (!file) return;

      // Ensure folder is correctly passed
      if (!folder) {
        console.error("Upload failed: Folder is null or undefined!");
        alert("Upload failed: Folder is not set!");
        return;
      }

      // Format the filename correctly
      const fileExtension = file.name.split('.').pop();
      const fileName = `${studentId}_${fileType}.${fileExtension}`;

      // Construct the full path
      const filePath = `${folder}/${fileName}`;

      console.log(`Uploading to: ${filePath}`); // Debugging log

      // Upload file to Supabase
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
        alert(`${fileType} successfully uploaded!`);
        console.log("Upload Successful:", data);
        checkFileStatus(folder, fileName, statusId); // Refresh status after upload
      }
    }

    // Function to download a document
    async function downloadFile(folder, fileName) {
      const filePath = `${folder}/${fileName}`;

      // Get signed URL for the file
      const {
        data,
        error
      } = await supabase.storage.from(bucketName).createSignedUrl(filePath, 60);

      if (error) {
        alert(`Download failed: ${error.message}`);
        console.error("Download Error:", error);
        console.log(`${folder}/${fileName}`);
        return;
      }

      // Trigger file download
      const link = document.createElement("a");
      link.href = data.signedUrl;
      link.download = fileName;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }

    // Attach event listeners to file inputs
    document.querySelectorAll('input[type="file"]').forEach((input, index) => {
      input.addEventListener("change", function() {
        const file = this.files[0];
        const fileType = this.getAttribute("data-file-type");
        const folder = this.getAttribute("data-folder");
        const statusId = `status${index}`;
        uploadFile(file, fileType, folder, statusId);
      });
    });

    // Attach event listeners to download buttons
    document.querySelectorAll('.download-btn').forEach((button, index) => {
      button.addEventListener("click", function() {
        const fileType = this.getAttribute("data-file-type");
        const folder = "templates/"
        const fileName = `${studentId}_${fileType}<?php echo $file_type; ?>`;
        downloadFile(folder, fileName);
      });
    });

    // Attach event listeners to template download buttons
    document.querySelectorAll('.download-template-btn').forEach((button, index) => {
      button.addEventListener("click", function() {
        const fileType = this.getAttribute("data-file-type");
        const fileName = `${fileType}<?php echo $file_type; ?>`; // Template file names don't have student IDs
        downloadFile("templates", fileName);
      });
    });

    function checkAllFiles() {
      const filesToCheck = [{
          folder: "to fill",
          name: "<?php echo $student_id; ?>_Industrial Training Visit Form<?php echo $file_type; ?>",
          id: "status0"
        },
        {
          folder: "to fill",
          name: "<?php echo $student_id; ?>_Evaluation Form<?php echo $file_type; ?>",
          id: "status1"
        },
        {
          folder: "to fill",
          name: "<?php echo $student_id; ?>_Reflective Journal<?php echo $file_type; ?>",
          id: "status2"
        },
        {
          folder: "to mark",
          name: "<?php echo $student_id; ?>_Weekly Logbook<?php echo $file_type; ?>",
          id: "status3"
        },
        {
          folder: "to mark",
          name: "<?php echo $student_id; ?>_Industrial Training Report<?php echo $file_type; ?>",
          id: "status4"
        }
      ];

      filesToCheck.forEach(file => {
        checkFileStatus(file.folder, file.name, file.id);
      });
    }

    // Run check on page load
    window.onload = checkAllFiles;
  </script>
</body>

</html>