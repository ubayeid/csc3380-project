<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acm72178";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$UserName = $_POST['UserName'];
$PassWord = $_POST['PassWord'];

# Get salt + hash + MemberNo
$sql = "SELECT PassWord, Salt, MemberNo
        FROM memberspw2
        WHERE UserName = '$UserName'";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Invalid username.";
    exit;
}

$row = $result->fetch_assoc();
$Salt      = $row['Salt'];
$RealHash  = $row['PassWord'];
$MemberNo  = $row['MemberNo'];

# Recompute salted password
$TryHash = sha1($PassWord . $Salt);

if ($TryHash === $RealHash) {
    header("Location: memberInfoSalt.php?MemberNo=$MemberNo");
    exit;
} else {
    echo "Login failed. Incorrect password.";
}

$conn->close();
?>
