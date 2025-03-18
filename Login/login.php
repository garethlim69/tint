<?php
session_start();
require '../Config/db.php';
require '../composer/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $roles = ['student', 'academicsupervisor', 'industrysupervisor', 'internshipcoordinator'];

    try {
        foreach ($roles as $role) {
            $query = "SELECT * FROM $role WHERE email = :email";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user'] = $row;
                    $_SESSION['role'] = $role;

                    $_SESSION['id'] = ($role === 'student') ? substr($email, 0, 7) : $email;

                    if ($email === "0363762@sd.taylors.edu.my") { //CHANGE THIS TO REAL EMAIL ADDRESS
                        $otp = rand(100000, 999999);
                        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));

                        // Store OTP in the database
                        $otpQuery = "INSERT INTO otp_codes (email, otp_code, expires_at) 
                                     VALUES (:email, :otp, :expires_at)
                                     ON CONFLICT (email) 
                                     DO UPDATE SET otp_code = :otp, expires_at = :expires_at";

                        $otpStmt = $pdo->prepare($otpQuery);
                        $otpStmt->bindParam(':email', $email);
                        $otpStmt->bindParam(':otp', $otp);
                        $otpStmt->bindParam(':expires_at', $expiresAt);
                        $otpStmt->execute();

                        // Send OTP via Email
                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'garethlimjs@gmail.com';
                            $mail->Password = 'gktl jblg vkpl vnqi'; // Use App Password if 2FA is enabled
                            $mail->SMTPSecure = 'tls';
                            $mail->Port = 587;

                            $mail->setFrom('garethlimjs@gmail.com', 't-int OTP Verification');
                            $mail->addAddress($email);
                            $mail->Subject = "Your OTP Code";
                            $mail->Body = "Hello,\n\nYour OTP code for login is: $otp\n\nThis code expires in 5 minutes.\n\nBest regards,\nt-int Team";

                            $mail->send();
                            header("Location: verify_otp.php?email=" . urlencode($email));
                            exit();
                        } catch (Exception $e) {
                            $error = "Error sending OTP: " . $mail->ErrorInfo;
                        }
                    } else {
                        $redirectPages = [
                            'student' => '../Students/StudentHome.php',
                            'academicsupervisor' => '../AcademicSupervisor/ASHome.php',
                            'industrysupervisor' => '../IndustrySupervisor/ISHome.php',
                            'internshipcoordinator' => '../Intco/IntCoHome.php'
                        ];
                        header("Location: " . $redirectPages[$role]);
                        exit();
                    }
                } else {
                    $error = "Invalid password.";
                }
            }
        }
        if (empty($error)) {
            $error = "User not found.";
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - T-Int</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
  <div class="container">
    <div class="logo-section">
      <img src="tint logo.png" alt="T-Int Logo" class="logo">
      <span class="logo-text">t-int</span>
    </div>

    <form class="login-box" action="login.php" method="POST">
      <input type="email" name="email" class="input-field" placeholder="School or work e-mail" required>
      <input type="password" name="password" class="input-field" placeholder="Password" required>
      <?php if (!empty($error)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <button type="submit" class="login-btn">Login</button>
      <a href="forgot_password.php" class="forgot-password">Forgotten Password?</a>
    </form>
  </div>
</body>

</html>