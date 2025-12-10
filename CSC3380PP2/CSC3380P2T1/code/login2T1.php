<?php
// Get form data (with basic sanitization)
$uName1 = isset($_POST['uName']) ? trim($_POST['uName']) : '';
$pw1 = isset($_POST['pw']) ? $_POST['pw'] : '';

// Validate that fields are not empty
if (empty($uName1) || empty($pw1)) {
    header("Location: login2T1.html?error=Username and password are required.");
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
    die("Connection failed: " . $conID->connect_error . "<br>Please check:<br>1. MySQL is running in XAMPP Control Panel<br>2. Database 'ACM72178' exists in phpMyAdmin<br>3. Import the SQL file if database doesn't exist");
}

// Query to check username and password (VULNERABLE VERSION FOR EDUCATIONAL PURPOSES)
$query = "SELECT membersPW.MemberNo, members.FirstName, members.LastName 
          FROM membersPW 
          INNER JOIN members ON membersPW.MemberNo = members.MemberNo 
          WHERE membersPW.UserName = '$uName1' AND membersPW.PassWord = SHA1('$pw1')";

$result = $conID->query($query);

if ($result && $result->num_rows == 1) {
    // Login successful - start session
    session_start();
    
    $row = $result->fetch_assoc();
    $memberNo = $row['MemberNo'];
    $firstName = $row['FirstName'];
    $lastName = $row['LastName'];
    
    // Store user information in session
    $_SESSION['memberNo'] = $memberNo;
    $_SESSION['username'] = $uName1;
    $_SESSION['firstName'] = $firstName;
    $_SESSION['lastName'] = $lastName;
    
    // Close database connection
    $conID->close();
    
    // Redirect to dashboard
    header("Location: ../../CSC3380P2T2/code/dashboard.php");
    exit();
} else {
    // Login failed
    header("Location: login2T1.html?error=Invalid username or password!");
    exit();
}

$conID->close();
?>