<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_destroy(); // Destroy the session
    header("Location: netflixlogin.php"); // Redirect to login page
    exit;
} else {
    // Redirect to index if accessed directly without form submission
    header("Location: index.php");
    exit;
}
?>
