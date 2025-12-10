<?php
// Get form data
$uName1 = $_POST['uName'];
$pw1 = $_POST['pw'];

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

// Query to check username and password
$query = "SELECT membersPW.MemberNo, members.FirstName, members.LastName 
          FROM membersPW 
          INNER JOIN members ON membersPW.MemberNo = members.MemberNo 
          WHERE membersPW.UserName = ? AND membersPW.PassWord = SHA1(?)";

$stmt = $conID->prepare($query);
$stmt->bind_param("ss", $uName1, $pw1);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // Login successful
    $row = $result->fetch_assoc();
    $memberNo = $row['MemberNo'];
    $firstName = $row['FirstName'];
    $lastName = $row['LastName'];
    
    echo "<html><head><title>Welcome</title></head><body>";
    echo "<h2>Welcome $firstName $lastName!</h2>";
    echo "<p>Your Member Number is: $memberNo</p>";
    echo "<p><a href='login.html'>Logout</a></p>";
    echo "</body></html>";
} else {
    // Login failed
    header("Location: login.html?error=Invalid username or password!");
    exit();
}

$stmt->close();
$conID->close();
?>