<?php
$error = '';
$debug = ''; // For debugging

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fname = trim($_POST['fName']);
    $lname = trim($_POST['lName']);
    $email = trim($_POST['eMail']);
    $deposit = $_POST['deposit'];
    $username = trim($_POST['uName']);
    $password = $_POST['pw'];
    $confirmPassword = $_POST['cpw'];

    // Validate First Name (alphabets only)
    if (!preg_match("/^[A-Za-z]+$/", $fname)) {
        $error = 'First name must contain only alphabetical characters.';
    }
    // Validate Last Name (alphabets only)
    elseif (!preg_match("/^[A-Za-z]+$/", $lname)) {
        $error = 'Last name must contain only alphabetical characters.';
    }
    // Validate Email format and UNCP domain
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    }
    elseif (strtolower(substr(strrchr($email, "@"), 1)) !== "uncp.edu") {
        $error = 'Email must be a UNCP email address (@uncp.edu).';
    }
    // Validate Password Match
    elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    }
    // Validate Password Length
    elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    }
    // Validate Deposit
    elseif (!is_numeric($deposit) || $deposit < 0) {
        $error = 'Deposit must be a positive number.';
    }
    else {
        // Database connection
        $conID = new mysqli("localhost", "root", "", "ACM72178");
        
        if ($conID->connect_error) {
            $error = "Connection failed: " . $conID->connect_error;
        } else {
            // Check if username is taken
            $checkQuery = "SELECT UserName FROM membersPW WHERE UserName = ?";
            $stmt = $conID->prepare($checkQuery);
            
            if (!$stmt) {
                $error = "Prepare failed: " . $conID->error;
            } else {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $error = 'Username is already taken. Choose a different username.';
                    $stmt->close();
                } else {
                    $stmt->close();
                    
                    // Insert into membersPW table
                    $insertPW = "INSERT INTO membersPW (UserName, PassWord, eMail) VALUES (?, SHA1(?), ?)";
                    $stmt = $conID->prepare($insertPW);
                    
                    if (!$stmt) {
                        $error = "Prepare failed: " . $conID->error;
                    } else {
                        $stmt->bind_param("sss", $username, $password, $email);
                        
                        if ($stmt->execute()) {
                            // Get the auto-generated MemberNo
                            $lastMemberNo = $conID->insert_id;
                            $stmt->close();
                            
                            // Insert into members table
                            $insertMember = "INSERT INTO members (MemberNo, FirstName, LastName, Deposit) VALUES (?, ?, ?, ?)";
                            $stmt = $conID->prepare($insertMember);
                            
                            if (!$stmt) {
                                $error = "Prepare failed for members table: " . $conID->error;
                            } else {
                                $stmt->bind_param("issd", $lastMemberNo, $fname, $lname, $deposit);
                                
                                if ($stmt->execute()) {
                                    $stmt->close();
                                    $conID->close();
                                    // Success! Redirect to login
                                    header('Location: login2T2.php?msg=Registration successful! Please login with your credentials.');
                                    exit();
                                } else {
                                    $error = "Error inserting into members table: " . $stmt->error;
                                    $stmt->close();
                                }
                            }
                        } else {
                            $error = "Error inserting into membersPW table: " . $stmt->error;
                            $stmt->close();
                        }
                    }
                }
            }
            $conID->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page - Task 2</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        td {
            padding: 8px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"],
        input[type="reset"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        input[type="reset"] {
            background-color: #f44336;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        input[type="reset"]:hover {
            background-color: #da190b;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
            background-color: #ffebee;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .debug {
            color: blue;
            font-size: 12px;
            text-align: center;
            background-color: #e3f2fd;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        hr {
            margin: 20px 0;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function validate() {
            // Get form values
            const firstName = document.forms["myForm"]["fName"].value;
            const lastName = document.forms["myForm"]["lName"].value;
            const email = document.forms["myForm"]["eMail"].value;
            const password = document.forms["myForm"]["pw"].value;
            const confirmPassword = document.forms["myForm"]["cpw"].value;

            // Validate First Name (alphabets only)
            const namePattern = /^[A-Za-z]+$/;
            if (!namePattern.test(firstName)) {
                alert('First name must contain only alphabetical characters');
                return false;
            }

            // Validate Last Name (alphabets only)
            if (!namePattern.test(lastName)) {
                alert('Last name must contain only alphabetical characters');
                return false;
            }

            // Validate Email (must be UNCP email)
            const emailDomain = email.substring(email.lastIndexOf('@'));
            if (emailDomain.toLowerCase() !== '@uncp.edu') {
                alert('Email must be a UNCP email address (@uncp.edu)');
                return false;
            }

            // Validate Password Match
            if (password !== confirmPassword) {
                alert('Passwords do not match');
                return false;
            }

            // Validate Password Length
            if (password.length < 6) {
                alert('Password must be at least 6 characters long');
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Registration Form - Task 2 (Secure)</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($debug): ?>
            <div class="debug"><?php echo htmlspecialchars($debug); ?></div>
        <?php endif; ?>
        
        <form name="myForm" onsubmit="return validate()" method="post">
            <table>
                <tr>
                    <td>First Name:</td>
                    <td>
                        <input name="fName" type="text" value="<?php echo isset($_POST['fName']) ? htmlspecialchars($_POST['fName']) : ''; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td>
                        <input name="lName" type="text" value="<?php echo isset($_POST['lName']) ? htmlspecialchars($_POST['lName']) : ''; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>User Name:</td>
                    <td>
                        <input name="uName" type="text" value="<?php echo isset($_POST['uName']) ? htmlspecialchars($_POST['uName']) : ''; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>
                        <input name="eMail" type="email" value="<?php echo isset($_POST['eMail']) ? htmlspecialchars($_POST['eMail']) : ''; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td>
                        <input name="pw" type="password" required>
                    </td>
                </tr>
                <tr>
                    <td>Confirm Password:</td>
                    <td>
                        <input name="cpw" type="password" required>
                    </td>
                </tr>
                <tr>
                    <td>Deposit:</td>
                    <td>
                        <input name="deposit" type="number" step="0.01" min="0" value="<?php echo isset($_POST['deposit']) ? htmlspecialchars($_POST['deposit']) : ''; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <input type="submit" value="Submit">
                        <input type="reset" value="Reset">
                    </td>
                </tr>
            </table>
        </form>
        
        <hr>
        <p style="text-align: center;">
            Already have an account? <a href="login2T2.php">Login here</a>
        </p>
    </div>
</body>
</html>