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
$alertType = 'success'; // Default alert type

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $alertType = $_SESSION['alert_type'] ?? 'success';
    unset($_SESSION['message'], $_SESSION['alert_type']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uname = trim($_POST['uname']);
    $password = $_POST['password'];

    // ✅ Updated to include role
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE uname = ?");
    if ($stmt) {
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $uname;
                $_SESSION['role'] = $user['role'];
                $_SESSION['message'] = "✅ Login successful!";
                $_SESSION['alert_type'] = 'success';

                // ✅ Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: manager/dashboard.php"); // Fallback
                }
                exit;
            } else {
                $_SESSION['message'] = "❌ Incorrect password!";
                $_SESSION['alert_type'] = 'danger';
            }
        } else {
            $_SESSION['message'] = "❌ Username not found!";
            $_SESSION['alert_type'] = 'danger';
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "❌ Failed to prepare statement: " . $conn->error;
        $_SESSION['alert_type'] = 'danger';
    }

    // Reload login page on error
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
        <link href="css/login.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    </head>
    <body>
      <?php if ($message): ?>
    <div id="alert-message" 
        class="alert alert-<?= htmlspecialchars($alertType) ?> text-center position-fixed top-0 start-50 translate-middle-x mt-3 shadow"
        style="z-index: 9999; width: max-content; max-width: 90%;">
        <?= htmlspecialchars($message) ?>
    </div>

    <script>
        // Auto-hide after 2 seconds (2000 ms)
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
            <div class="header-text">
                <h4>Login</h4>
                <p class="subtitle">Access Your Inventory Dashboard</p>
            </div>
            <div class="form-section">
                <form method="POST">
                    <div class="input-group-custom">
                        <label class="form-label">Username:</label>
                        <input type="text" name="uname" placeholder="Enter your username" required>
                    </div>

                    <div class="input-group-custom">
                        <label class="form-label">Password:</label>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="login-btn">Login</button>
                </form>

        <p class="signup-text">Not registered yet? <a href="register.php">Create a new account</a></p>
            </div>
        
        </div>
        <div class="right-content">
            <img src="img/login-illu.jpg" alt="Example Image" />
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>

