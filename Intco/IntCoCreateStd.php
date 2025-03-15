<?php
  require '../Config/db.php';
  require '../Config/profpic.php'; 

  $stmt = $pdo->query("SELECT name, email, phone_number, program_name FROM student");
  $result = $stmt ->fetchAll(PDO::FETCH_ASSOC);
 
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error_message = "";  // Variable to store error messages

if(isset($_POST["upload"])){
    if ($_FILES["fileToUpload"]["error"] == UPLOAD_ERR_NO_FILE) {
        $error_message = "Please select a file to upload!";
    } else {
        $file = $_FILES["fileToUpload"];
        $fileTmpName = $file["tmp_name"];
        $fileError = $file["error"];

        $fileExt = pathinfo($file["name"], PATHINFO_EXTENSION);
        $allowedType = ['csv'];

        if (in_array(strtolower($fileExt), $allowedType)) {
            if ($fileError === 0) {
                // Open the temporary file directly
                if (($fileload = fopen($fileTmpName, 'r')) !== false) {
                    // Skip unrelated rows to find the header
                    while (($header = fgetcsv($fileload, 0, ",")) !== false) {
                        if (count(array_filter($header)) > 1) 
                            break;
                    }

                    // Target column indexes
                    $targetColumnIndex = [
                        'name' => -1, 'studentid' => -1, 'email' => -1, 'phone' => -1,  
                        'program' => -1, 'password' => -1, 'faculty'=> -1  
                    ];
                    $studentid = ["student_id", "id", "stid", "std_id", "Student #"];
                    $phonenum = ["Mobile No", "phone", "contact num"];

                    foreach ($header as $index => $colName) {
                        if (stripos($colName, "name") !== false) $targetColumnIndex['name'] = $index;
                        foreach ($studentid as $studentTerm) {
                            if (stripos($colName, $studentTerm) !== false) {
                                $targetColumnIndex['studentid'] = $index; break;
                            }
                        }
                        if (stripos($colName, "email") !== false) $targetColumnIndex['email'] = $index;
                        foreach ($phonenum as $studentphone) {
                            if (stripos($colName, $studentphone) !== false) {
                                $targetColumnIndex['phone'] = $index; break;
                            }
                        }
                        if (stripos($colName, "phone") !== false) $targetColumnIndex['phone'] = $index;
                        if (stripos($colName, "specialisation") !== false) $targetColumnIndex['program'] = $index;
                        if (stripos($colName, "password") !== false) $targetColumnIndex['password'] = $index;
                        if (stripos($colName, "faculty") !== false) $targetColumnIndex['faculty'] = $index;
                    }

                    // Check if all required columns are found
                    $missingColumns = array_keys(array_filter($targetColumnIndex, fn($v) => $v === -1));
                    if (!empty($missingColumns)) {
                        $error_message = "❌ Missing required columns: " . implode(", ", $missingColumns) . ". Please check the CSV file format!";
                    } else {
                        // Read CSV data and insert into the database
                        while (($data = fgetcsv($fileload, 0, ",")) !== false) {
                            if (!isset($data[$targetColumnIndex['name']], $data[$targetColumnIndex['studentid']],
                                      $data[$targetColumnIndex['email']], $data[$targetColumnIndex['phone']],
                                      $data[$targetColumnIndex['program']], $data[$targetColumnIndex['faculty']],
                                      $data[$targetColumnIndex['password']])) {
                                continue; // Skip incorrectly formatted rows
                            }

                            $name = $data[$targetColumnIndex['name']];
                            $id = $data[$targetColumnIndex['studentid']];
                            $email = $data[$targetColumnIndex['email']];
                            $phone = $data[$targetColumnIndex['phone']];
                            $program = $data[$targetColumnIndex['program']];
                            $facultyq = $data[$targetColumnIndex['faculty']];
                            $ps = $data[$targetColumnIndex['password']];

                            // Query faculty table to ensure facultyq exists
                            $facultyCheck = $pdo->prepare("SELECT faculty_name FROM faculty WHERE faculty_name = :faculty");
                            $facultyCheck->execute([':faculty' => $facultyq]);
                            if (!$facultyCheck->fetch()) {
                                $error_message = "Error: The selected Faculty ($facultyq) does not exist in the database. Please check the CSV file or database records!";
                                break; // Stop processing
                            }

                            // Insert into database
                            $sql = "INSERT INTO student (student_id, name, email, phone_number, program_name, password, faculty) 
                                    VALUES (:stdid, :name, :email, :phone, :program, :password, :faculty)";
                            $mydb = $pdo->prepare($sql);
                            $mydb->execute([
                                ':stdid' => $id, ':name' => $name, ':email' => $email, 
                                ':phone' => $phone, ':program' => $program, 
                                ':password' => $ps, ':faculty' => $facultyq
                            ]);
                        }
                        fclose($fileload);
                        header("Location: IntCoCreateStd.php");
                        exit();
                    }
                } else {
                    $error_message = "Unable to read CSV file.";
                }
            } else {
                $error_message = "Error uploading file.";
            }
        } else {
            $error_message = "This file type is not allowed.";
        }
    }
}
 
 

if(isset($_POST["discard"])){
    $delete ="DELETE FROM student WHERE name LIKE 'Student%' ";
    $statement = $pdo->prepare($delete);
    $statement->execute();
    echo "<script>window.location.href='IntCoCreateStd.php';</script>";
    exit();
}
?>


<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Create - Student</title>
    <link rel="stylesheet" href="IntCoHeader.css">
    <link rel="stylesheet" href="IntCoCreateStd.css">
    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .alert{
    padding: 10px;
    background-color: #f8d7da;
    border: 1px solid #f5c2c7;
    color: #842029;
    border-radius: 4px;
    margin-bottom: 20px;}
    </style>
</head>
<body>
    <!-- navigationbar -->
    <div class="header">
        <div class="tint_logo">
            <img class="logo" src="picture/logo.png">
            <p class="tint_title">t-int</p>
        </div>
        <div class="navigationbar">
            <div class="navigationbar_link">
                <a href="IntCoHome.php">Home</a>
            </div>
        </div>
        <div class="profile">
        <img class="profile_icon" id="profile-picture" src="<?php echo $_SESSION['profile_picture']; ?>" style="border-radius: 50%;">
            <div class="profile_dropdown">
                <a href="ICProfileSetting.php"><img class="settingicon" src="picture/setting.png">  Settings</a>
                <a href="../Login/logout.php"> <img class="logouticon" src="picture/logout.png">Log Out</a>
                </div>
        </div>
    </div>
   
    <!-- form  -->
    <form action="IntCoCreateStd.php" method="post" enctype="multipart/form-data" id="uploadForm">
        <!-- error messages -->
        <?php if (!empty($error_message)): ?>
            <div class="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <!-- Example CSV Format:-->

        <h3>Example CSV Format:</h3>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Student Name</th>
        <th>Student ID</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Program</th>
        <th>Password</th>
        <th>Faculty</th>
    </tr>
    <tr>
        <td>John Doe</td>
        <td>123456</td>
        <td>johndoe@example.com</td>
        <td>0123456789</td>
        <td>Computer Science</td>
        <td>password123</td>
        <td>School of Computer Science (SCS)</td>
    </tr>
</table>

        <div class="button">
            <h2 class="CreateAS">Create - Student</h2>
            <div class="mybutton">
                <input type="file" id="uploadfile" name="fileToUpload" style="display:none" onchange="displayFileName()">
                <button type="button" class="importbutton" onclick="document.getElementById('uploadfile').click();">Import</button>
                <span id="selectedFileName"></span>
            </div>
        </div>

        <!-- StudentContactsTables -->
        <div>
            <table class="StudentContactsTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone No.</th>
                        <th>Program Name</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- row1 -->
                    <?php foreach($result as $line){ ?>
                    <tr>
                        <td><?= htmlspecialchars($line['name']); ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($line['email']); ?>"><?= htmlspecialchars($line['email']); ?></a></td>
                        <td><?= htmlspecialchars($line['phone_number']); ?></td>
                        <td><?= htmlspecialchars($line['program_name']); ?></td> 
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="button_bottom">
                <button type="submit" class="discard" name="discard">Discard</button>
                <input type="submit" name="upload" id="submit" value="Submit" class="submit">
            </div>
        </div>
    </form>

    <script>
    function displayFileName() {
        let fileInput = document.getElementById("uploadfile");  
        if(fileInput.files.length > 0) {
            let filename = fileInput.files[0].name;
            document.getElementById("selectedFileName").textContent = "Selected File : " + filename;
        }
    }
    </script>
</body>
</html>