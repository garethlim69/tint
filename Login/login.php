<?php
session_start();
require '../Config/db.php'; // Include PDO connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
                if (password_verify($password, $row['password'])) { // Verify hashed password
                    $_SESSION['user'] = $row;
                    $_SESSION['role'] = $role;
                    switch ($role) {
                case 'student':
                    header("Location: student_dashboard.php");
                    break;
                case 'academicsupervisor':
                    header("Location: as_dashboard.php");
                    break;
                case 'industrysupervisor':
                    header("Location: is_dashboard.php");
                    break;
                case 'internshipcoordinator':
                    header("Location: ic_dashboard.php");
                    break;
            }
            exit();
                } else {
                    echo "<script>alert('Invalid password'); window.location='index.html';</script>";
                    exit();
                }
            }
        }
        echo "<script>alert('User not found'); window.location='index.html';</script>";
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>


