<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['memberNo']) && isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit();
}

// GLOBAL VARIABLES
$memberNo1 = null;
$error = '';
$conID = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['uName']);
    $password = $_POST['pw'];
    
    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        openDatabase($conID);
        
        if (pwOK($conID, $username, $password, $memberNo1)) {
            // Regenerate session ID for security
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['username'] = $username;
            $_SESSION['memberNo'] = $memberNo1;
            
            $conID->close();
            
            // Redirect to dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "User name or password is wrong - Try again";
        }
        
        if ($conID) {
            $conID->close();
        }
    }
}

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

function pwOK($conID, $uName1, $pw1, &$memberNo1) {
    // SECURE VERSION - Using prepared statements to prevent SQL injection
    $SQL = "SELECT MemberNo FROM membersPW WHERE UserName = ? AND PassWord = SHA1(?)";
    
    $stmt = $conID->prepare($SQL);
    if (!$stmt) {
        die("Prepare failed: " . $conID->error);
    }
    
    $stmt->bind_param("ss", $uName1, $pw1);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $memberNo1 = $row['MemberNo'];
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - Task 2</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-spacing: 10px;
        }
        td {
            padding: 5px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"],
        input[type="reset"] {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        input[type="reset"] {
            background-color: #f44336;
            color: white;
        }
        input[type="reset"]:hover {
            background-color: #da190b;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }
        #msg {
            color: green;
            font-size: 14px;
            text-align: center;
        }
        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ddd;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .label-cell {
            text-align: right;
            font-weight: bold;
            color: #555;
        }
    </style>
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const msg = urlParams.get('msg');
            if (msg) {
                document.getElementById('msg').textContent = msg;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Login - Task 2 (Secure with Sessions)</h2>
        <p class="error"><?php if ($error) echo htmlspecialchars($error); ?></p>
        
        <form method="post">
            <table>
                <tr>
                    <td class="label-cell">User name:</td>
                    <td><input name="uName" type="text" required></td>
                </tr>
                <tr>
                    <td class="label-cell">Password:</td>
                    <td><input name="pw" type="password" required></td>
                </tr>
                <tr>
                    <td><input name="login" value="Login" type="submit"></td>
                    <td><input type="reset" value="Clear"></td>
                </tr>
            </table>
        </form>
        
        <hr/>
        <p id="msg">If you are new, you have to <a href="register2T2.php">register</a> here</p>
    </div>
</body>
</html>