<?php
session_start();
include_once('storage.php');

// Redirect users to the login page if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Display the main page if logged in
include_once('main.php');
?>
