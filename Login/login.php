<?php
session_start();
require '../Config/db.php'; // Ensure database connection

$error = ""; // Store error messages

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
                  
                  // ✅ Set $_SESSION['id'] based on role
                  if ($role === 'student') {
                      $_SESSION['id'] = substr($email, 0, 7); // Extract first 7 digits
                  } else {
                      $_SESSION['id'] = $email; // Store full email for other roles
                  }

                  // Redirect based on role
                  $redirectPages = [
                      'student' => '../Students/StudentHome.php',
                      'academicsupervisor' => '../AcademicSupervisor/ASHome.php',
                      'industrysupervisor' => '../IndustrySupervisor/ISHome.php',
                      'internshipcoordinator' => '../Intco/IntCoHome.php'
                  ];
                  header("Location: " . $redirectPages[$role]);
                  exit();
              } else {
                  $error = "Invalid password";
              }
          }
      }
      if (empty($error)) {
          $error = "User not found";
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
            <button type="submit" class="login-btn">Login</button>
            <a href="#" class="forgot-password">Forgotten Password?</a>
        </form>

        <!-- Show error message if login fails -->
        <?php if (!empty($error)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
