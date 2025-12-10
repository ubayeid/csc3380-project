<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acm72178";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$MemberNo = $_GET['MemberNo'];

$sql = "SELECT * FROM members2 WHERE MemberNo = $MemberNo";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo "<h2>Welcome, Member #$MemberNo</h2>";
echo "First Name: " . $row['FirstName'] . "<br>";
echo "Last Name: " . $row['LastName'] . "<br>";
echo "Deposit: " . $row['Deposit'] . "<br>";
echo "Created: " . $row['CreatedAt'] . "<br>";

$conn->close();
?>
