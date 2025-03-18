<?php
require '../Config/db.php';  // Database connection
require '../composer/vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function logMessage($message)
{
  $logFile = 'email_reminder_log.txt';
  $timestamp = date('Y-m-d H:i:s');
  file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

$demoUserEmail = "0363762@sd.taylors.edu.my";

$query = "SELECT email, email_reminders FROM student WHERE email = :email AND email_reminders > 0";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':email', $demoUserEmail);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
  $email = $user['email'];
  $daysInAdvance = (int) $user['email_reminders'];
  $today = new DateTime();

  $taskQuery = "SELECT task_name, due_date FROM studenttasks";
  $taskStmt = $pdo->prepare($taskQuery);
  $taskStmt->execute();
  $tasks = $taskStmt->fetchAll(PDO::FETCH_ASSOC);

  $tasksToSend = [];

  foreach ($tasks as $task) {
    $dueDate = new DateTime($task['due_date']); 
    $daysUntilDue = $today->diff($dueDate)->days;

    logMessage("Task: " . $task['task_name'] . " | Due Date: " . $task['due_date'] . " | Days Until Due: " . $daysUntilDue);

    if ($daysUntilDue == $daysInAdvance) {
      $tasksToSend[] = $task;
    }
  }

  if (!empty($tasksToSend)) {
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'garethlimjs@gmail.com';
      $mail->Password = 'gktl jblg vkpl vnqi';
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;

      $mail->setFrom('your-email@gmail.com', 'Task Reminder');
      $mail->addAddress($email);
      $mail->Subject = "Task Reminder Notification";

      $body = "Hello,\n\nYou have the following task(s) due in $daysInAdvance days:\n\n";
      foreach ($tasksToSend as $task) {
        $body .= "- " . $task['task_name'] . " (Due: " . $task['due_date'] . ")\n";
      }
      $body .= "\nPlease complete them on time.\n\nBest regards,\nt-int Team";
      $mail->Body = $body;

      if ($mail->send()) {
        logMessage("Reminder email sent successfully to $email.");
        echo "Reminder email sent successfully to $email.\n";
      } else {
        logMessage("Failed to send email to $email.");
        echo "Failed to send email.\n";
      }
    } catch (Exception $e) {
      logMessage("Error sending email: " . $mail->ErrorInfo);
      echo "Error sending email: " . $mail->ErrorInfo . "\n";
    }
  } else {
    logMessage("No tasks match the reminder criteria for $email.");
    echo "No upcoming tasks matching the reminder criteria for $email.\n";
  }
} else {
  logMessage("No email reminders enabled for $demoUserEmail.");
  echo "No email reminders enabled for $demoUserEmail.\n";
}
