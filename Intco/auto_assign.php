<?php
require '../Config/db.php';

// Get all teachers
$teacherQuery = "SELECT DISTINCT as_email FROM internshipoffer WHERE as_email IS NOT NULL";
$stmt = $pdo->prepare($teacherQuery);
$stmt->execute();
$teachers = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (empty($teachers)) {
    die("🚨 No teachers found!");
}

//Get all unassigned students 
$studentQuery = "SELECT s.student_id, s.name, isup.company_name 
    FROM student s 
    LEFT JOIN internshipoffer io ON io.student_id = s.student_id 
    LEFT JOIN industrysupervisor isup ON io.is_email = isup.email 
    WHERE io.as_email IS NULL";
$stmt = $pdo->prepare($studentQuery);
$stmt->execute();
$unassignedStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($unassignedStudents)) {
    die("No students left to assign.");
}

// Create a mapping of teachers and their assigned companies
$teacherCompanies = [];
foreach ($teachers as $teacher) {
    $companyQuery = "SELECT DISTINCT isup.company_name FROM internshipoffer io 
        JOIN industrysupervisor isup ON io.is_email = isup.email
        WHERE io.as_email = ?";
    $stmt = $pdo->prepare($companyQuery);
    $stmt->execute([$teacher]);
    $teacherCompanies[$teacher] = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

//Assign students, prioritizing same-company first
$assignments = [];
$teacherSlots = []; // Track assigned count per teacher

foreach ($unassignedStudents as $student) {
    $company = $student['company_name'];
    $assigned = false;

    // First, try to assign the student to a teacher who already has students from the same company
    foreach ($teachers as $teacher) {
        $assignedCountQuery = "SELECT COUNT(*) FROM internshipoffer WHERE as_email = ?";
        $stmt = $pdo->prepare($assignedCountQuery);
        $stmt->execute([$teacher]);
        $assignedCount = $stmt->fetchColumn();

        if ($assignedCount < 5 && in_array($company, $teacherCompanies[$teacher])) {
            $assignments[] = ['teacher' => $teacher, 'student_id' => $student['student_id']];
            $teacherSlots[$teacher] = ($teacherSlots[$teacher] ?? 0) + 1;
            $assigned = true;
            break;
        }
    }

    // If no match, assign to any teacher with available slots
    if (!$assigned) {
        foreach ($teachers as $teacher) {
            if (($teacherSlots[$teacher] ?? 0) < 5) {
                $assignments[] = ['teacher' => $teacher, 'student_id' => $student['student_id']];
                $teacherSlots[$teacher] = ($teacherSlots[$teacher] ?? 0) + 1;
                break;
            }
        }
    }
}

// Apply assignments to the database
$updateQuery = "UPDATE internshipoffer SET as_email = ? WHERE student_id = ?";
$stmt = $pdo->prepare($updateQuery);

foreach ($assignments as $assign) {
    $stmt->execute([$assign['teacher'], $assign['student_id']]);
}

echo "Successfully assigned students to teachers!";
?>