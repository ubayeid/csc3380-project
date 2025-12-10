<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "acm72178";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$UserName = $_POST['UserName'];
$Email    = $_POST['eMail'];
$PassWord = $_POST['PassWord'];

# Hash password using password_hash() (bcrypt by default)
$Hash = password_hash($PassWord, PASSWORD_DEFAULT);

# Insert into members3 using prepared statement
$sql1 = "INSERT INTO members3 (FirstName, LastName, Deposit)
         VALUES ('New', 'Member', 0)";

if (!$conn->query($sql1)) {
    die("Error inserting into members3: " . $conn->error);
}

$MemberNo = $conn->insert_id;

# Insert into memberspw3 using prepared statement
$stmt = $conn->prepare("INSERT INTO memberspw3 (UserName, PassWord, eMail, MemberNo)
                        VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $UserName, $Hash, $Email, $MemberNo);

if ($stmt->execute()) {
    echo "Registration successful!<br>";
    echo "Your MemberNo: $MemberNo<br>";
    echo "<a href='loginHash.html'>Go to login</a>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

