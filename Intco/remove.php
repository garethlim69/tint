<?php
require '../Config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['remove']) && isset($_POST['student_id'])) {
  $studentId = $_POST['student_id'];

  error_log("Removing student: " . $studentId);

  $query = "UPDATE internshipoffer SET as_email = NULL WHERE student_id = ?";
  $stmt = $pdo->prepare($query);
  
  if ($stmt->execute([$studentId])) {
    if ($stmt->rowCount() > 0) {
      error_log("Student $studentId unassigned successfully.");
      echo "Student removed successfully!";
    } else {
      error_log("Student $studentId was already unassigned.");
      echo "Student was already unassigned.";
    }
  } else {
    error_log("SQL Execution Failed: " . implode(" | ", $stmt->errorInfo()));
    echo "Error unassigning student.";
  }
} else {
  echo "Invalid request.";
}
?>
