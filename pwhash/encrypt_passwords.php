<?php
require '../Config/db.php'; // Include database connection

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT); // Hash using bcrypt
}

// Example: Hash and insert a password for a student (Modify for different roles)
$email = "Ava.Bennett@taylors.edu.my"; // Replace with actual email
$plain_password = "23857"; // Replace with actual password
$hashed_password = hashPassword($plain_password);

try {
    $stmt = $pdo->prepare("UPDATE academicsupervisor SET password = :password WHERE email = :email");
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    echo "Password updated successfully!";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
