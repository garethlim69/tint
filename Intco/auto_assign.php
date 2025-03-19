<?php
require '../Config/db.php';

// Get all academic supervisors
$teacherQuery = "SELECT DISTINCT as_email FROM internshipoffer WHERE as_email IS NOT NULL";
$stmt = $pdo->prepare($teacherQuery);
$stmt->execute();
$teachers = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (empty($teachers)) {
  die("No Academic Supervisors Found!");
}

// Get all assigned students and map companies to their academic supervisors
$assignedQuery = "SELECT io.student_id, io.as_email, isup.company_name
                  FROM internshipoffer io
                  JOIN industrysupervisor isup ON io.is_email = isup.email
                  WHERE io.as_email IS NOT NULL";
$stmt = $pdo->prepare($assignedQuery);
$stmt->execute();
$assignedStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);

$companyToSupervisor = [];
foreach ($assignedStudents as $student) {
  $companyToSupervisor[$student['company_name']] = $student['as_email'];
}

// Get unassigned students grouped by company
$studentQuery = "SELECT s.student_id, s.name, isup.company_name 
    FROM student s 
    LEFT JOIN internshipoffer io ON io.student_id = s.student_id 
    LEFT JOIN industrysupervisor isup ON io.is_email = isup.email 
    WHERE io.as_email IS NULL
    ORDER BY isup.company_name";
$stmt = $pdo->prepare($studentQuery);
$stmt->execute();
$unassignedStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($unassignedStudents)) {
  die("No students left to assign.");
}

// Group students by company
$companyGroups = [];
foreach ($unassignedStudents as $student) {
  $companyGroups[$student['company_name']][] = $student;
}

// Get current student count per academic supervisor
$teacherSlots = [];
foreach ($teachers as $teacher) {
  $assignedCountQuery = "SELECT COUNT(*) FROM internshipoffer WHERE as_email = ?";
  $stmt = $pdo->prepare($assignedCountQuery);
  $stmt->execute([$teacher]);
  $teacherSlots[$teacher] = $stmt->fetchColumn();
}

// Sort supervisors by availability (least students first)
asort($teacherSlots);

// Assign students while keeping company groups together
$assignments = [];

foreach ($companyGroups as $company => $students) {
  if (isset($companyToSupervisor[$company]) && $teacherSlots[$companyToSupervisor[$company]] < 5) {
    // A supervisor is already assigned to this company; assign all students to the same supervisor
    $selectedTeacher = $companyToSupervisor[$company];
  } else {
    // Find a supervisor with enough space
    $selectedTeacher = null;
    foreach ($teacherSlots as $teacher => $count) {
      if ($count + count($students) <= 5) {
        $selectedTeacher = $teacher;
        break;
      }
    }
  }

  if ($selectedTeacher) {
    foreach ($students as $student) {
      $assignments[] = ['teacher' => $selectedTeacher, 'student_id' => $student['student_id']];
      $teacherSlots[$selectedTeacher]++; // Increase assigned count
    }
    $companyToSupervisor[$company] = $selectedTeacher; // Update mapping
  }
}

// Update database with assignments
$updateQuery = "UPDATE internshipoffer SET as_email = ? WHERE student_id = ?";
$stmt = $pdo->prepare($updateQuery);

foreach ($assignments as $assign) {
  $stmt->execute([$assign['teacher'], $assign['student_id']]);
}

echo "Auto-assignment Successful";
?>
