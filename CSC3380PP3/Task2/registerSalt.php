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

# Generate 16-byte salt (32 hex chars)
$Salt = bin2hex(random_bytes(16));

# Salted SHA1 (assignment method)
$Hash = sha1($PassWord . $Salt);

# Insert into members2
$sql1 = "INSERT INTO members2 (FirstName, LastName, Deposit)
         VALUES ('New', 'Member', 0)";

$conn->query($sql1);
$MemberNo = $conn->insert_id;

# Insert into memberspw2
$sql2 = "INSERT INTO memberspw2 (UserName, PassWord, eMail, Salt, MemberNo)
         VALUES ('$UserName', '$Hash', '$Email', '$Salt', $MemberNo)";

if ($conn->query($sql2)) {
    echo "Registration successful!<br>";
    echo "Your MemberNo: $MemberNo<br>";
    echo "<a href='loginSalt.html'>Go to login</a>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
