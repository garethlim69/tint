<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['otp_email'];
    $entered_otp = $_POST['otp'];

    $stmt = $pdo->prepare("SELECT otp_code, expires_at FROM otp_codes WHERE email = ?");
    $stmt->execute([$email]);
    $otp_data = $stmt->fetch();

    if ($otp_data && $otp_data['otp_code'] == $entered_otp && strtotime($otp_data['expires_at']) > time()) {
        echo "OTP verified!";
        $_SESSION['verified'] = true;
    } else {
        echo "Invalid or expired OTP.";
    }
}
?>
