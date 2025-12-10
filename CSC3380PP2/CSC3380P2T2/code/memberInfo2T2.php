<?php
// Include session check
require_once 'session_check.php';

// Get user data from session
$username = $_SESSION['username'];
$memberno = $_SESSION['memberNo'];

// OTHER GLOBAL VARIABLES
$conID;
$fName = '';
$lName = '';
$deposit = 0;

// Functions
function openDatabase(&$conID) {
    $host = "localhost";
    $db = "ACM72178";
    $usr = "root";
    $pw = "";
    
    $conID = new mysqli($host, $usr, $pw, $db);
    
    if ($conID->connect_error) {
        die("Connection failed: " . $conID->connect_error);
    }
}

function getMemberInfo($conID, &$fName, &$lName, &$deposit, $memberno) {
    // Use prepared statement to prevent SQL injection
    $SQL = "SELECT FirstName, LastName, Deposit FROM members WHERE MemberNo = ?";
    $stmt = $conID->prepare($SQL);
    
    if (!$stmt) {
        die("Prepare failed: " . $conID->error);
    }
    
    $stmt->bind_param("i", $memberno);
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

// MAIN
openDatabase($conID);

if (!getMemberInfo($conID, $fName, $lName, $deposit, $memberno)) {
    echo "<h1>Your information not available - Contact the secretary</h1>";
    $conID->close();
    exit();
}

$conID->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Member Information - Task 2</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
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
            font-weight: bold;
        }
        .actions {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .btn-danger {
            background-color: #f44336;
            color: white;
        }
        .btn-danger:hover {
            background-color: #da190b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Member Information - Task 2 (Secure with Sessions)</h1>
        <table>
            <tr>
                <th>Member Number</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Deposit</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($memberno); ?></td>
                <td><?php echo htmlspecialchars($fName); ?></td>
                <td><?php echo htmlspecialchars($lName); ?></td>
                <td>$<?php echo number_format($deposit, 2); ?></td>
            </tr>
        </table>
        
        <div class="actions">
            <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>