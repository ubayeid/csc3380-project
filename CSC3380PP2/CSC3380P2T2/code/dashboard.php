<?php
// Include session check
require_once 'session_check.php';

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

// Get member information from session
$memberNo = $_SESSION['memberNo'];
$username = $_SESSION['username'];

// Get full member details from database
$query = "SELECT m.MemberNo, m.FirstName, m.LastName, m.Deposit, mp.eMail 
          FROM members m 
          INNER JOIN membersPW mp ON m.MemberNo = mp.MemberNo 
          WHERE m.MemberNo = ?";
          
$stmt = $conID->prepare($query);
$stmt->bind_param("i", $memberNo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $member = $result->fetch_assoc();
} else {
    // Member not found, destroy session and redirect
    session_destroy();
    header("Location: ../CSC3380P2T1/code/login2T1.html?error=Member information not found.");
    exit();
}

$stmt->close();
$conID->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Member Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .welcome {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .welcome h2 {
            color: #2e7d32;
            margin: 0;
        }
        .info-section {
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #555;
            width: 30%;
        }
        td {
            color: #333;
        }
        .actions {
            margin-top: 30px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            background-color: #2196F3;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #0b7dda;
        }
        .btn-danger {
            background-color: #f44336;
            color: white;
        }
        .btn-danger:hover {
            background-color: #da190b;
        }
        hr {
            margin: 30px 0;
            border: none;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Member Dashboard</h1>
        
        <div class="welcome">
            <h2>Welcome, <?php echo htmlspecialchars($member['FirstName'] . ' ' . $member['LastName']); ?>!</h2>
        </div>
        
        <?php
        // Display success/error messages if present
        if (isset($_GET['msg'])) {
            echo '<div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">';
            echo htmlspecialchars($_GET['msg']);
            echo '</div>';
        }
        if (isset($_GET['error'])) {
            echo '<div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">';
            echo htmlspecialchars($_GET['error']);
            echo '</div>';
        }
        ?>
        
        <div class="info-section">
            <h3>Your Information</h3>
            <table>
                <tr>
                    <th>Member Number:</th>
                    <td><?php echo htmlspecialchars($member['MemberNo']); ?></td>
                </tr>
                <tr>
                    <th>Username:</th>
                    <td><?php echo htmlspecialchars($username); ?></td>
                </tr>
                <tr>
                    <th>First Name:</th>
                    <td><?php echo htmlspecialchars($member['FirstName']); ?></td>
                </tr>
                <tr>
                    <th>Last Name:</th>
                    <td><?php echo htmlspecialchars($member['LastName']); ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo htmlspecialchars($member['eMail'] ?? 'Not provided'); ?></td>
                </tr>
                <tr>
                    <th>Deposit:</th>
                    <td>$<?php echo number_format($member['Deposit'], 2); ?></td>
                </tr>
            </table>
        </div>
        
        <hr>
        
        <div class="actions">
            <a href="changePassword.html" class="btn btn-secondary">Change Password</a>
            <a href="sqli_demo.php" class="btn btn-secondary">SQL Injection Demo</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>
