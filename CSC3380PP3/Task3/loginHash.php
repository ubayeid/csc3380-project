<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acm72178";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$UserName = $_POST['UserName'];
$PassWord = $_POST['PassWord'];

# Get hash + MemberNo using prepared statement
$stmt = $conn->prepare("SELECT PassWord, MemberNo
                        FROM memberspw3
                        WHERE UserName = ?");
$stmt->bind_param("s", $UserName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Invalid username.";
    $stmt->close();
    $conn->close();
    exit;
}

$row = $result->fetch_assoc();
$RealHash  = $row['PassWord'];
$MemberNo  = $row['MemberNo'];

# Verify password using password_verify()
if (password_verify($PassWord, $RealHash)) {
    header("Location: memberInfoHash.php?MemberNo=$MemberNo");
    exit;
} else {
    echo "Login failed. Incorrect password.";
}

$stmt->close();
$conn->close();
?>

