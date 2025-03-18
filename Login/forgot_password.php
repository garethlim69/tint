<?php
session_start();
require '../Config/db.php'; // Ensure database connection
require '../composer/vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $roles = ['student', 'academicsupervisor', 'industrysupervisor', 'internshipcoordinator'];

  try {
    $userFound = false;
    foreach ($roles as $role) {
      $query = "SELECT * FROM $role WHERE email = :email";
      $stmt = $pdo->prepare($query);
      $stmt->bindParam(':email', $email);
      $stmt->execute();

      if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $userFound = true;

        // Generate a new random password
        $newPassword = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 10);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password in the database
        $updateQuery = "UPDATE $role SET password = :new_password WHERE email = :email";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':new_password', $hashedPassword);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->execute();

        // Send email with the new password
        $mail = new PHPMailer(true);
        try {
          $mail->isSMTP();
          $mail->Host = 'smtp.gmail.com';
          $mail->SMTPAuth = true;
          $mail->Username = 'garethlimjs@gmail.com';
          $mail->Password = 'gktl jblg vkpl vnqi'; // Use an app password if 2FA is enabled
          $mail->SMTPSecure = 'tls';
          $mail->Port = 587;

          $mail->setFrom('garethlimjs@gmail.com', 't-int Password Reset');
          $mail->addAddress($email);
          $mail->Subject = "Password Reset for t-int";
          $mail->Body = "Hello,\n\nYour new password is: $newPassword\n\nPlease log in and change your password immediately.\n\nBest regards,\nt-int Team";

          $mail->send();
          $success = "A new password has been sent to your email.";
        } catch (Exception $e) {
          $error = "Error sending email: " . $mail->ErrorInfo;
        }

        break;
      }
    }

    if (!$userFound) {
      $error = "No account found with this email.";
    }
  } catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - T-Int</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
  <div class="container">
    <div class="logo-section">
      <img src="tint logo.png" alt="T-Int Logo" class="logo">
      <span class="logo-text">t-int</span>
    </div>

    <form class="login-box" action="forgot_password.php" method="POST">
      <h3>Reset Password</h3>
      <input type="email" name="email" class="input-field" placeholder="Enter your email" required>
      <?php if (!empty($success)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
      <?php endif; ?>
      <?php if (!empty($error)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <button type="submit" class="login-btn">Reset Password</button>
      <a href="login.php" class="forgot-password">Back to Login</a>
    </form>
  </div>
</body>

</html>