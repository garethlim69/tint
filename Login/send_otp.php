<?php
session_start();
require './composer/vendor/autoload.php';
require '../Config/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function generateOTP() {
    return rand(100000, 999999);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $otp = generateOTP();
    $expires_at = date("Y-m-d H:i:s", strtotime("+5 minutes"));

    $stmt = $pdo->prepare("INSERT INTO otp_codes (email, otp_code, expires_at) VALUES (?, ?, ?) 
                           ON CONFLICT (email) DO UPDATE SET otp_code = EXCLUDED.otp_code, expires_at = EXCLUDED.expires_at");
    $stmt->execute([$email, $otp, $expires_at]);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'garethlimjs@gmail.com'; 
        $mail->Password = 'gktl jblg vkpl vnqi'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your-email@gmail.com', 'Your App');
        $mail->addAddress($email);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP code is: $otp. It expires in 5 minutes.";

        $mail->send();
        $_SESSION['otp_email'] = $email;
        echo "OTP sent!";
    } catch (Exception $e) {
        echo "Failed to send OTP: " . $mail->ErrorInfo;
    }
}
?>
