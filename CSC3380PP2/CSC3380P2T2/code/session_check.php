<?php
// Session check helper - include this at the top of protected pages
session_start();

// Check if user is logged in
if (!isset($_SESSION['memberNo']) || !isset($_SESSION['username'])) {
    // User is not logged in, redirect to login page
    header("Location: ../../CSC3380P2T1/code/login2T1.html?error=Please login to access this page.");
    exit();
}
?>
