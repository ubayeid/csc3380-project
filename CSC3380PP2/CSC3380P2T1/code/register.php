<?php
// Ensure this file is accessed via POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.html");
    exit();
}

// Get form data
$fName = isset($_POST['fName']) ? $_POST['fName'] : '';
$lName = isset($_POST['lName']) ? $_POST['lName'] : '';
$uName = isset($_POST['uName']) ? $_POST['uName'] : '';
$pw = isset($_POST['pw']) ? $_POST['pw'] : '';
$eMail = isset($_POST['eMail']) ? $_POST['eMail'] : '';
$deposit = isset($_POST['deposit']) ? $_POST['deposit'] : '';

// Validate required fields
if (empty($fName) || empty($lName) || empty($uName) || empty($pw) || empty($eMail) || empty($deposit)) {
    header("Location: register.html?msg=All fields are required.");
    exit();
}

// Validate First Name (alphabets only)
if (!preg_match("/^[A-Za-z]+$/", $fName)) {
    header("Location: register.html?msg=First name must contain only alphabetical characters.");
    exit();
}

// Validate Last Name (alphabets only)
if (!preg_match("/^[A-Za-z]+$/", $lName)) {
    header("Location: register.html?msg=Last name must contain only alphabetical characters.");
    exit();
}

// Validate Email format and UNCP domain
if (!filter_var($eMail, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.html?msg=Invalid email format.");
    exit();
}

if (strtolower(substr(strrchr($eMail, "@"), 1)) !== "uncp.edu") {
    header("Location: register.html?msg=Email must be a UNCP email address (@uncp.edu).");
    exit();
}

// Validate Password Length
if (strlen($pw) < 6) {
    header("Location: register.html?msg=Password must be at least 6 characters long.");
    exit();
}

// Validate Deposit (must be positive number)
if (!is_numeric($deposit) || $deposit < 0) {
    header("Location: register.html?msg=Deposit must be a positive number.");
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
        header("Location: login2T1.html?msg=Registration successful! You can now login with your credentials.");
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
