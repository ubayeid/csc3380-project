<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acm72178";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$MemberNo = $_GET['MemberNo'];

# Get member info using prepared statement
$stmt = $conn->prepare("SELECT * FROM members3 WHERE MemberNo = ?");
$stmt->bind_param("i", $MemberNo);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo "<h2>Welcome, Member #$MemberNo</h2>";
echo "First Name: " . htmlspecialchars($row['FirstName']) . "<br>";
echo "Last Name: " . htmlspecialchars($row['LastName']) . "<br>";
echo "Deposit: " . htmlspecialchars($row['Deposit']) . "<br>";
echo "Created: " . htmlspecialchars($row['CreatedAt']) . "<br>";

$stmt->close();
$conn->close();
?>

