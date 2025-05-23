<?php
$host = 'localhost';
$db   = 'ims_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$firstName = 'Admin';
$lastName = 'User';
$username = 'admin';
$password = 'admin123'; 
$role = 'admin';

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $check = $conn->prepare("SELECT id FROM users WHERE uname = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Admin user already exists.";
    } else {

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
