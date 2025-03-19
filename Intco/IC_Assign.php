<?php
  require '../Config/db.php';
  require '../Config/profpic.php'; 

  $stmt1 = $pdo->query("SELECT s.name AS student_name,s.student_id, s.email as student_email, io.is_email, isup.name AS is_name, isup.company_name as company
 FROM internshipoffer AS io
 JOIN student AS s ON io.student_id = s.student_id
 JOIN industrysupervisor AS isup ON io.is_email = isup.email
");
$students = $stmt1->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_students'])) {
  require '../Config/db.php';

  $teacherId = $_POST['teacher_id'];
  $selectedStudents = $_POST['students'] ?? [];
  $allStudents = [];

  $stmt = $pdo->prepare("SELECT student_id FROM internshipoffer WHERE as_email = :teacherId OR as_email IS NULL");
  $stmt->execute(['teacherId' => $teacherId]);
  $allStudents = $stmt->fetchAll(PDO::FETCH_COLUMN);

  foreach ($allStudents as $studentId) {
      if (in_array($studentId, $selectedStudents)) {
          
          $updateQuery = "UPDATE internshipoffer SET as_email = :teacherId WHERE student_id = :studentId";
          $stmt = $pdo->prepare($updateQuery);
          $stmt->execute(['teacherId' => $teacherId, 'studentId' => $studentId]);
      } else {
          
          $updateQuery = "UPDATE internshipoffer SET as_email = NULL WHERE student_id = :studentId";
          $stmt = $pdo->prepare($updateQuery);
          $stmt->execute(['studentId' => $studentId]);
      }
  }

  header("Location: ".$_SERVER['PHP_SELF']."?teacher_id=".$teacherId);
  exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assign Students</title>
  <link rel="stylesheet" href="IC_Assign.css">
  <link rel="stylesheet" href="IntCoHeader.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

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

  <div class="container">
    <div class="title-row">
      <h2>Assign</h2>
      <div class="top-actions">
        <div class="search-box">
          <input type="text" placeholder="Search..." id="searchinputAS">
          <button class="auto-assign-btn" id="autoAssignBtn">Auto-assign</button>
        </div>
      </div>
    </div>
    <table class="outer-table">
      <thead>
        <tr>
          <th>Academic Supervisor Name</th>
          <th>Email</th>
          <th>Faculty</th>
          <th>No. of Students</th>
        </tr>
      </thead>
      <tbody>
        
      <?php
$stmt3 = $pdo->query("SELECT name, email, faculty, no_of_students FROM academicsupervisor");
$teachersResult = $stmt3->fetchAll(PDO::FETCH_ASSOC);

foreach ($teachersResult as $teacher): 
    $teacherId = $teacher['email'];
    $teacherTarget = "sub-" . preg_replace('/[^a-zA-Z0-9]/', '_', $teacherId);

    $countQuery = "SELECT COUNT(*) AS student_count FROM internshipoffer WHERE as_email = :teacherId";
    $stmtCount = $pdo->prepare($countQuery);
    $stmtCount->bindParam(':teacherId', $teacherId, PDO::PARAM_STR);
    $stmtCount->execute();
    $studentCount = $stmtCount->fetch(PDO::FETCH_ASSOC)['student_count'];
?>
    <tr class="main-row" data-target="<?= $teacherTarget ?>">
        <td class="toggle-subrow">
            
            + <?= htmlspecialchars($teacher['name']) ?>
        </td>
        <td><a href="mailto:<?= htmlspecialchars($teacher['email']) ?>" class="email-link"><?= htmlspecialchars($teacher['email']) ?></a></td>
        <td><?= htmlspecialchars($teacher['faculty']) ?></td>
        <td><span class="student-count"><?= $studentCount ?>/5</td>
    </tr>

    <tr id="<?= $teacherTarget ?>" class="sub-row">
        <td colspan="4">
            <table class="inner-table">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Email</th>
                        <th>Company Name</th>
                        <th>Industry Supervisor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $studentsQuery = "SELECT 
                        s.name AS student_name,
                        s.student_id, 
                        s.email AS student_email, 
                        io.is_email, 
                        isup.name AS is_name, 
                        isup.company_name AS company 
                        FROM internshipoffer AS io 
                        JOIN student AS s ON io.student_id = s.student_id 
                        JOIN industrysupervisor AS isup ON io.is_email = isup.email 
                        WHERE io.as_email = :teacherId ORDER BY s.name ASC";

                    $stmt4 = $pdo->prepare($studentsQuery);
                    $stmt4->bindParam(':teacherId', $teacherId, PDO::PARAM_STR);
                    $stmt4->execute();
                    $studentsResult = $stmt4->fetchAll(PDO::FETCH_ASSOC);

                    if (count($studentsResult) > 0):
                        foreach ($studentsResult as $studentlist):
                    ?>
                    <tr>
                        <td>
                        <button class="delete-student-btn" data-student-id="<?= htmlspecialchars($studentlist['student_id']) ?>" 
        style="border:none; background:none; cursor:pointer;">
        <img src="picture/delete_icon.png" alt="Delete" style="width:15px; height:15px;">
    </button>
                            <?= htmlspecialchars($studentlist['student_name']) ?>
                        </td>
                        <td><?= htmlspecialchars($studentlist['student_id']) ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($studentlist['student_email']) ?>" class="email-link"><?= htmlspecialchars($studentlist['student_email']) ?></a></td>
                        <td><?= htmlspecialchars($studentlist['company']) ?></td>
                        <td><?= htmlspecialchars($studentlist['is_name']) ?></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5">No students assigned</td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="showmore_withcircle_icon_container" colspan="5">

                            <img class="showmore_withcircle_icon openModalBtn" src="picture/edit_icon.png" data-teacher-id="<?= htmlspecialchars($teacherId, ENT_QUOTES, 'UTF-8') ?>" 
     onclick="openModal(<?= json_encode($teacherId) ?>)">
                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
<?php endforeach; ?>

      
      </tbody>
    </table>

   
  </div>

  
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit/Add Student</h2>
        </div>
        <form method="post">
        <input type="hidden" id="teacherIdInput" name="teacher_id" value="">
            <div class="modal-body">
                <div class="search-row">
                    <div class="left-label">
                        <label>Student Name:</label>
                    </div>
                    <input type="text" placeholder="Search..." id="searchInputstd">
                </div>

                <div class="table-container2">
                    <table class="student-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th>Email</th>
                                <th>Company Name</th>
                                <th>Industry Supervisor Name</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody">
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button class="cancel-btn" id="cancelBtn">Cancel</button>
               <button class="change-btn" id="addBtn" name="assign_students">Change</button>
            </div>
        
    </div>
</div>
</form>

  

  <script>
    document.querySelectorAll('.main-row').forEach(row => {
      const toggleCell = row.querySelector('.toggle-subrow');
      if (!toggleCell) return;

      const targetId = row.getAttribute('data-target');
      const subRow = document.getElementById(targetId);
      if (!subRow) return;

      subRow.style.display = 'none';

      toggleCell.addEventListener('click', () => {
        if (subRow.style.display === 'none') {
          subRow.style.display = 'table-row';
        } else {
          subRow.style.display = 'none';
        }
      });
    });

   document.querySelectorAll('.openModalBtn').forEach(button => {
    button.addEventListener('click', function () {
        let teacherId = this.getAttribute('data-teacher-id');
        console.log("Teacher ID extracted from button:", teacherId);

        if (!teacherId) {
            console.error("Teacher ID not found!");
            return;
        }

        console.log("Teacher ID Set in Modal:", teacherId); 

        document.getElementById('teacherIdInput').value = teacherId;

        fetch('fetch_students.php?teacher_id=' + teacherId)
            .then(response => response.text()) 
            .then(data => {
                document.getElementById('studentTableBody').innerHTML = data;
                document.getElementById('modalOverlay').style.display = 'flex';
            })
            .catch(error => console.error('Error loading students:', error));
    });
});

document.getElementById('cancelBtn').addEventListener('click', function (event) {
    event.preventDefault();
    document.getElementById('modalOverlay').style.display = 'none';
});


document.getElementById('searchinputAS').addEventListener('keyup', function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('.outer-table tbody tr.main-row');

    rows.forEach(row => {
      let studentName = row.cells[0].textContent.toLowerCase();
        if (studentName.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
    




    document.getElementById('searchInputstd').addEventListener('keyup', function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('.student-table tbody tr');

    rows.forEach(row => {
        let studentName = row.cells[1].textContent.toLowerCase();
        if (studentName.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
    

    
   
document.addEventListener('DOMContentLoaded', function () {
    function updateCheckboxState() {
        const checkboxes = document.querySelectorAll('.select-student');
        let checkedCheckboxes = document.querySelectorAll('.select-student:checked');
        let checkedCount = checkedCheckboxes.length;

        console.log(`Checked count: ${checkedCount}`);

        checkboxes.forEach(cb => {
            if (checkedCount >= 5 && !cb.checked) {
                cb.disabled = true;
                console.log(`Disabled checkbox: ${cb.value}`);
            } else {
                cb.disabled = false;
            }
        });
    }

    document.addEventListener('change', function (event) {
        if (event.target.classList.contains('select-student')) {
            updateCheckboxState();
        }
    });

    document.querySelectorAll('.openModalBtn').forEach(button => {
        button.addEventListener('click', function () {
            setTimeout(() => {
                updateCheckboxState();
            }, 200);
        });
    });
});
  
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('autoAssignBtn').addEventListener('click', async function () {
        let teacherButtons = document.querySelectorAll('.openModalBtn');
        let teacherIds = [];

        teacherButtons.forEach(button => {
            let teacherId = button.getAttribute('data-teacher-id');
            if (teacherId && !teacherIds.includes(teacherId)) {
                teacherIds.push(teacherId);
            }
        });

        if (teacherIds.length === 0) {
            alert(" No Academic Supervisors Found!");
            return;
        }

        console.log("Auto-Assigning for Teachers:", teacherIds);

        for (let i = 0; i < teacherIds.length; i++) {
            let teacherId = teacherIds[i];
            console.log(`Processing Teacher ID: ${teacherId}`);

            try {
                let response = await fetch('auto_assign.php?teacher_id=' + teacherId);
                let result = await response.text();
                console.log(`Completed Auto-Assign for Teacher ${teacherId}:`, result);
            } catch (error) {
                console.error(`Error assigning students for Teacher ${teacherId}:`, error);
            }
        }

        alert("Auto-Assignment Completed for All Teachers!");
        location.reload(); 
    });
});
    
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-student-btn').forEach(button => {
        button.addEventListener('click', function () {
            let studentId = this.getAttribute('data-student-id');

            if (confirm("Are you sure you want to unassign this student?")) {
                
                fetch('remove.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'student_id': studentId,
                        'remove': 'true'
                    })
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    if (data.includes("Student removed successfully!")) {
                        window.location.reload();
                    } else {
                        alert("Error unassigning student: " + data);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});





  </script>
</body>
</html>