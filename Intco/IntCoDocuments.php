<?php
define("SUPABASE_URL", "https://rbborpwwkrfhkcqvacyz.supabase.co");
define("SUPABASE_KEY", "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InJiYm9ycHd3a3JmaGtjcXZhY3l6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDAwMzU2OTQsImV4cCI6MjA1NTYxMTY5NH0.pMLuryar6iAlkd110WblQtz8T_XdrKOpZEQHksHpuuM");
define("STORAGE_BUCKET", "documents");

require '../Config/profpic.php';
$file_type = '.docx';
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Documents</title>
  <link rel="stylesheet" href="IntCoHeader.css">
  <link rel="stylesheet" href="IntCoDocuments.css">
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
        <a href="IntCoHome.php">Home</a>

      </div>

    </div>
    <div class="profile">
      <img class="profile_icon" id="profile-picture" src="<?php echo $_SESSION['profile_picture']; ?>" style="border-radius: 50%;">
      <div class="profile_dropdown">
        <a href="ICProfileSetting.php"> <img class="settingicon" src="picture/setting.png"> Settings</a>
        <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
      </div>
    </div>


  </div>

  <div class="title-container">
    <h2 class="title">Documents</h2>
  </div>
  <p style="color: grey; text-align: right; padding-right: 20px; font-size: 15px;">(only .docx files accepted)</p>
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <td></td>
          <td>Upload Template</td>
          <td>Last Submission at</td>
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
          $fileName = "templates/"  . $fileType . $file_type;
        ?>
          <tr>
            <td class="alignleft"><?php echo $fileType; ?></td>
            <td>
              <input type="file" id="fileInput<?php echo $index; ?>"
                style="display: none;"
                data-file-type="<?php echo $fileType; ?>"
                data-folder="templates">
              <label for="fileInput<?php echo $index; ?>">
                <img src="picture/upload.png" alt="Upload" width="35" height="35" style="cursor: pointer;">
              </label>
            </td>
            <td id="status<?php echo $index; ?>">Checking...</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <script>
    const supabaseUrl = "<?php echo SUPABASE_URL; ?>";
    const supabaseAnonKey = "<?php echo SUPABASE_KEY; ?>";
    const supabase = window.supabase.createClient(supabaseUrl, supabaseAnonKey);

    const bucketName = "<?php echo STORAGE_BUCKET; ?>";
    const folder = "templates";

    async function checkFileStatus(fileName, statusId) {
      const folder = "templates";
      console.log(`Checking file: ${folder}/${fileName}`);

      const {
        data,
        error
      } = await supabase.storage.from(bucketName).list(folder);

      if (error) {
        console.error(`Error fetching files from '${folder}':`, error.message);
        document.getElementById(statusId).innerText = "To Be Submitted";
        return;
      }

      console.log("Supabase Response:", data);

      const file = data.find(file => file.name === fileName);

      if (!file) {
        document.getElementById(statusId).innerText = "To Be Submitted";
        return;
      }

      console.log("File Found:", file);

      const lastUpdated = file.updated_at || "Unknown Time";

      const formattedDate = lastUpdated !== "Unknown Time" ? new Date(lastUpdated).toLocaleString() : "Uploaded (Date Not Available)";

      document.getElementById(statusId).innerText = `${formattedDate}`;
    }

    async function uploadFile(file, fileType, statusId) {
      if (!file) return;

      if (!fileType) {
        console.error("Upload failed: File type is null or undefined!");
        alert("Upload failed: File type is not set!");
        return;
      }

      const fileExtension = file.name.split('.').pop().toLowerCase();
        if (fileExtension !== 'docx') {
          alert("Only .docx files are allowed!");
          return;
        }
      const fileName = `${fileType}.${fileExtension}`;

      const filePath = `${folder}/${fileName}`;

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
        alert(`${fileType} successfully uploaded!`);
        console.log("Upload Successful:", data);
        checkFileStatus(fileName, statusId);
      }
    }

    document.querySelectorAll('input[type="file"]').forEach((input, index) => {
      input.addEventListener("change", function() {
        const file = this.files[0];
        const fileType = this.getAttribute("data-file-type");
        const statusId = `status${index}`;
        uploadFile(file, fileType, statusId);
      });
    });

    function checkAllFiles() {
      const filesToCheck = [{
          name: "Industrial Training Visit Form<?php echo $file_type; ?>",
          id: "status0"
        },
        {
          name: "Evaluation Form<?php echo $file_type; ?>",
          id: "status1"
        },
        {
          name: "Reflective Journal<?php echo $file_type; ?>",
          id: "status2"
        },
        {
          name: "Weekly Logbook<?php echo $file_type; ?>",
          id: "status3"
        },
        {
          name: "Industrial Training Report<?php echo $file_type; ?>",
          id: "status4"
        }
      ];

      filesToCheck.forEach(file => {
        checkFileStatus(file.name, file.id);
      });
    }

    window.onload = checkAllFiles;
  </script>
</body>

</html>