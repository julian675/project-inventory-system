<?php
session_start(); 

$servername = "localhost";
$username = "root";
$password = "";
$database = "ims_db";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname']); 
    $lname = trim($_POST['lname']); 
    $uname = trim($_POST['uname']); 
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "❌ Invalid email format!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (fname, lname, uname, email, password, role) VALUES (?, ?, ?, ?, ?, 'viewer')");

        if ($stmt) {
            $stmt->bind_param("sssss", $fname, $lname, $uname, $email, $hashedPassword);
            
            if ($stmt->execute()) {
                $_SESSION['message'] = "✅ New record created successfully!";
            } else {
                if (str_contains($stmt->error, 'Duplicate entry')) {
                    $_SESSION['message'] = "❌ Username or email already exists!";
                } else {
                    $_SESSION['message'] = "❌ Error: " . $stmt->error;
                }
            }

            $stmt->close();
        } else {
            $_SESSION['message'] = "❌ Failed to prepare statement: " . $conn->error;
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="register.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="image-section">
        <img src="img/reg-illu.png" class="img-fluid" alt="Registration Illustration">
    </div>
    
  <div class="form-wrapper">
        <h2 class="custom-heading register-title mb-4">Register</h2>

        <?php if ($message): ?>
            <div class="alert alert-info text-center"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Firstname:</label>
                <input type="text" name="fname" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Lastname:</label>
                <input type="text" name="lname" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Username:</label>
                <input type="text" name="uname" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3 text-center">
                <a href="login.php">Already Registered? Click here</a>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>





</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
