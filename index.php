<?php
// Database connection settings
$host = 'localhost';
$db   = 'ims_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Admin credentials
$firstName = 'Admin';
$lastName = 'User';
$username = 'admin';
$password = 'admin123'; // Default password
$role = 'admin';

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if user already exists
    $check = $conn->prepare("SELECT id FROM users WHERE uname = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Admin user already exists.";
    } else {
        // Insert new admin
        $stmt = $conn->prepare("INSERT INTO users (fname, lname, uname, password, role) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("sssss", $firstName, $lastName, $username, $hashedPassword, $role);
        $stmt->execute();
        echo "Admin user created successfully.<br>Username: <b>$username</b><br>Password: <b>$password</b>";
    }
} catch (Exception $e) {
    echo "Error creating admin: " . $e->getMessage();
}

$conn->close();
?>
