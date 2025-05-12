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
$alertType = 'success'; // Default to success

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $alertType = $_SESSION['alert_type'] ?? 'success';
    unset($_SESSION['message'], $_SESSION['alert_type']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname']); 
    $lname = trim($_POST['lname']); 
    $uname = trim($_POST['uname']); 
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "❌ Invalid email format!";
        $_SESSION['alert_type'] = 'danger';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (fname, lname, uname, email, password, role) VALUES (?, ?, ?, ?, ?, 'viewer')");

        if ($stmt) {
            $stmt->bind_param("sssss", $fname, $lname, $uname, $email, $hashedPassword);
            
            if ($stmt->execute()) {
                $_SESSION['message'] = "✅ New record created successfully!";
                $_SESSION['alert_type'] = 'success';
            } else {
                if (str_contains($stmt->error, 'Duplicate entry')) {
                    $_SESSION['message'] = "❌ Username or email already exists!";
                } else {
                    $_SESSION['message'] = "❌ Error: " . $stmt->error;
                }
                $_SESSION['alert_type'] = 'danger';
            }

            $stmt->close();
        } else {
            $_SESSION['message'] = "❌ Failed to prepare statement: " . $conn->error;
            $_SESSION['alert_type'] = 'danger';
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
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/register.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    </head>
    <body>
<?php if ($message): ?>
    <div id="alert-message" 
        class="alert alert-<?= htmlspecialchars($alertType) ?> text-center position-absolute top-0 start-50 translate-middle-x mt-3" 
        style="z-index: 1000;">
        <?= htmlspecialchars($message) ?>
    </div>

    <script>
        // Auto-dismiss after 2 seconds
        setTimeout(function () {
            var alertBox = document.getElementById('alert-message');
            if (alertBox) {
                alertBox.style.display = 'none';
            }
        }, 2000);

        // Optional: Dismiss on click
        document.addEventListener('click', function () {
            var alertBox = document.getElementById('alert-message');
            if (alertBox) {
                alertBox.style.display = 'none';
            }
        });
    </script>
<?php endif; ?>

    <div class="container">
        <div class="content-wrapper">
            
            <div class="left-content">
                <img src="img/reg-illu.png" />
            </div>
            <div class="right-content">
                <div class="header-text">
                    <h4>Register</h4>
                </div>
                <div class="form-section">
                    <form method="POST">
            <form method="POST" action="register.php">
                <div class="input-group-custom">
                    <label class="form-label">Firstname:</label>
                    <input type="text" name="fname" placeholder="Enter your firstname" required>
                </div>

                <div class="input-group-custom">
                    <label class="form-label">Lastname:</label>
                    <input type="text" name="lname" placeholder="Enter your lastname" required>
                </div>

                <div class="input-group-custom">
                    <label class="form-label">Username:</label>
                    <input type="text" name="uname" placeholder="Enter your username" required>
                </div>

                <div class="input-group-custom">
                    <label class="form-label">Email:</label>
                    <input type="text" name="email" placeholder="Enter your email" required>
                </div>

                <div class="input-group-custom">
                    <label class="form-label">Password:</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>

                <div class="form-footer">
                    <button type="submit" class="login-btn">Submit</button>
                    <p class="signup-text">Already registered? <a href="login.php">Click here!</a></p>
                </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>

