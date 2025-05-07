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

    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $uname = $_POST['uname'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE uname = ?");
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['uname'];

                header("Location: index.php");
                exit;
            } else {
                $error = "❌ Incorrect password.";
            }
        } else {
            $error = "❌ Username not found.";
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="login.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    </head>
    <body>

    <div class="login-container">
    <div class="login-form">
        <h4>Login</h4>
        <p class="subtitle">See your growth and get support!</p>

        <button class="google-btn">
            <img src="https://img.icons8.com/color/16/000000/google-logo.png"/>
            Sign in with Google
        </button>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center mt-2"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group-custom">
                <input type="text" name="uname" placeholder="Enter your username" required>
            </div>

            <div class="input-group-custom">
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="options">
                <label><input type="checkbox" name="remember"> Remember me</label>
                <a href="#">Forgot password?</a>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <p class="signup-text">Not registered yet? <a href="register.php">Create a new account</a></p>
    </div>

    <div class="login-illustration">
        <img src="img/bg.jpg" alt="Illustration"> 
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>

