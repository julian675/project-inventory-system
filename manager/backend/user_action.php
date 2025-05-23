<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin/login.php");
    exit;
}

$host = "localhost";
$db = "ims_db";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$action = $_GET['action'] ?? '';
$target_id = intval($_GET['id'] ?? 0);
$current_id = $_SESSION['user_id'];

if (!$target_id || !in_array($action, ['promote', 'remove'])) {
    die("Invalid request.");
}

if ($target_id === $current_id) {
    echo "You cannot perform this action on your own account.";
    exit;
}

if ($action === 'remove') {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $target_id);
    $stmt->execute();
    $stmt->close();
    echo "OK";
    exit;

}

if ($action === 'promote') {
    $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
    $stmt->bind_param("i", $target_id);
    $stmt->execute();
    $stmt->close();
    echo "OK";
    exit;
}
?>
