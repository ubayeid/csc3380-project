<?php
// Start session
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page
header("Location: ../../CSC3380P2T1/code/login2T1.html?msg=You have been successfully logged out.");
exit();
?>
