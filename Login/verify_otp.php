<?php
session_start();
require '../Config/db.php'; // Ensure database connection

if (!isset($_GET['email'])) {
  header("Location: login.php");
  exit();
}

$email = $_GET['email'];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['otp'])) {
  $otp = trim($_POST['otp']);

  // Check if OTP is valid and not expired
  $query = "SELECT otp_code, expires_at FROM otp_codes WHERE email = :email";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':email', $email);
  $stmt->execute();
  $otpData = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($otpData) {
    $storedOtp = $otpData['otp_code'];
    $expiresAt = strtotime($otpData['expires_at']);
    $currentTime = time();

    if ($otp == $storedOtp && $currentTime <= $expiresAt) {
      $deleteQuery = "DELETE FROM otp_codes WHERE email = :email";
      $deleteStmt = $pdo->prepare($deleteQuery);
      $deleteStmt->bindParam(':email', $email);
      $deleteStmt->execute();

      // Redirect to dashboard
      header("Location: ../Students/StudentHome.php");
      exit();
    } else {
      $error = "Invalid or expired OTP. Please try again.";
    }
  } else {
    $error = "No OTP found. Please request a new one.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify OTP - T-Int</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
  <div class="container">
    <form class="login-box" action="verify_otp.php?email=<?php echo urlencode($email); ?>" method="POST">
      <h3>Enter OTP</h3>
      <input type="text" name="otp" class="input-field" placeholder="Enter OTP" required>
      <button type="submit" class="login-btn">Verify OTP</button>
    </form>

    <?php if (!empty($error)): ?>
      <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
  </div>
</body>

</html>