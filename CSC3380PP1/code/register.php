<?php
// Get form data
$fName = $_POST['fName'];
$lName = $_POST['lName'];
$uName = $_POST['uName'];
$pw = $_POST['pw'];
$eMail = $_POST['eMail'];
$deposit = $_POST['deposit'];

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

// Check if username already exists
$checkQuery = "SELECT UserName FROM membersPW WHERE UserName = ?";
$stmt = $conID->prepare($checkQuery);
$stmt->bind_param("s", $uName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Username already exists
    $stmt->close();
    $conID->close();
    header("Location: register.html?msg=Username already taken. Please choose another.");
    exit();
}
$stmt->close();

// Insert into membersPW table with hashed password
$insertPW = "INSERT INTO membersPW (UserName, PassWord, eMail) VALUES (?, SHA1(?), ?)";
$stmt = $conID->prepare($insertPW);
$stmt->bind_param("sss", $uName, $pw, $eMail);

if ($stmt->execute()) {
    // Get the auto-generated MemberNo
    $lastMemberNo = $conID->insert_id;
    $stmt->close();
    
    // Insert into members table
    $insertMember = "INSERT INTO members (MemberNo, FirstName, LastName, Deposit) VALUES (?, ?, ?, ?)";
    $stmt = $conID->prepare($insertMember);
    $stmt->bind_param("issd", $lastMemberNo, $fName, $lName, $deposit);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conID->close();
        // Redirect to login page with success message
        header("Location: login.html?msg=Registration successful! You can now login with your credentials.");
        exit();
    } else {
        echo "Error inserting into members table: " . $stmt->error;
        $stmt->close();
        $conID->close();
    }
} else {
    echo "Error inserting into membersPW table: " . $stmt->error;
    $stmt->close();
    $conID->close();
}
?>