<?php
// Include session check
require_once 'session_check.php';

// Ensure this file is accessed via POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: changePassword.html");
    exit();
}

// Get form data
$currentPassword = isset($_POST['currentPassword']) ? $_POST['currentPassword'] : '';
$newPassword = isset($_POST['newPassword']) ? $_POST['newPassword'] : '';
$confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';

// Validate required fields
if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    header("Location: changePassword.html?error=All fields are required.");
    exit();
}

// Validate password length
if (strlen($newPassword) < 6) {
    header("Location: changePassword.html?error=New password must be at least 6 characters long.");
    exit();
}

// Validate password match
if ($newPassword !== $confirmPassword) {
    header("Location: changePassword.html?error=New passwords do not match.");
    exit();
}

// Check if new password is different from current
if ($currentPassword === $newPassword) {
    header("Location: changePassword.html?error=New password must be different from current password.");
    exit();
}

// Database connection parameters
$host = "localhost";
$db = "ACM72178";
$usr = "root";
$pw_db = "";

// Create connection
$conID = new mysqli($host, $usr, $pw_db, $db);

// Check connection
if ($conID->connect_error) {
    die("Connection failed: " . $conID->connect_error);
}

// Get member number from session
$memberNo = $_SESSION['memberNo'];

// Verify current password
$verifyQuery = "SELECT MemberNo FROM membersPW WHERE MemberNo = ? AND PassWord = SHA1(?)";
$stmt = $conID->prepare($verifyQuery);
$stmt->bind_param("is", $memberNo, $currentPassword);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Current password is incorrect
    $stmt->close();
    $conID->close();
    header("Location: changePassword.html?error=Current password is incorrect.");
    exit();
}
$stmt->close();

// Update password
$updateQuery = "UPDATE membersPW SET PassWord = SHA1(?) WHERE MemberNo = ?";
$stmt = $conID->prepare($updateQuery);
$stmt->bind_param("si", $newPassword, $memberNo);

if ($stmt->execute()) {
    $stmt->close();
    $conID->close();
    // Redirect to dashboard with success message
    header("Location: dashboard.php?msg=Password changed successfully!");
    exit();
} else {
    echo "Error updating password: " . $stmt->error;
    $stmt->close();
    $conID->close();
}
?>
