<?php
// Check if 'memberno' is set in the GET request
if (isset($_GET['memberno'])) {
    $memberno = $_GET['memberno'];
    // Validate that memberno is numeric to prevent SQL injection
    if (!is_numeric($memberno)) {
        die("Invalid member number");
    }
    $memberno = (int)$memberno; // Cast to integer for safety
} else {
    die("Member number not provided");
}

// Database connection
$host = "localhost";
$db = "ACM72178";
$usr = "root";
$pw = "";

$conID = new mysqli($host, $usr, $pw, $db);

if ($conID->connect_error) {
    die("Connection failed: " . $conID->connect_error);
}

// Function to get member information (using prepared statements to prevent SQL injection)
function getMemberInfo($conID, &$fName, &$lName, &$deposit, $memberno) {
    // Use prepared statement to prevent SQL injection
    $SQL = "SELECT FirstName, LastName, Deposit FROM members WHERE MemberNo = ?";
    $stmt = $conID->prepare($SQL);
    
    if (!$stmt) {
        die("Prepare failed: " . $conID->error);
    }
    
    $stmt->bind_param("i", $memberno); // 'i' for integer
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        $stmt->close();
        return false;
    } else {
        $row = $result->fetch_assoc();
        $fName = $row['FirstName'];
        $lName = $row['LastName'];
        $deposit = $row['Deposit'];
        $stmt->close();
        return true;
    }
}

// Function to create HTML output
function createMemberInfoHTML($fName, $lName, $deposit, $memberno) {
    $html = "<!DOCTYPE html>
        <html>
        <head>
            <title>Member Information</title>
            <style>
                table {
                    width: 50%;
                    border-collapse: collapse;
                    margin: 25px 0;
                    font-size: 18px;
                    text-align: left;
                }
                th, td {
                    padding: 12px;
                    border-bottom: 1px solid #ddd;
                }
                th {
                    background-color: #f2f2f2;
                }
            </style>
        </head>
        <body>
            <h1>Member Information</h1>
            <table>
                <tr>
                    <th>Member Number</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Deposit</th>
                </tr>
                <tr>
                    <td>$memberno</td>
                    <td>$fName</td>
                    <td>$lName</td>
                    <td>$$deposit</td>
                </tr>
            </table>
        </body>
        </html>";
    return $html;
}

// Main execution
$fName = "";
$lName = "";
$deposit = 0;

if (getMemberInfo($conID, $fName, $lName, $deposit, $memberno)) {
    echo createMemberInfoHTML($fName, $lName, $deposit, $memberno);
} else {
    echo "<h1>Member information not found</h1>";
}

$conID->close();
?>