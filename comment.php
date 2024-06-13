<?php

require_once "database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID and post ID from the AJAX request
 
    $userId = $_POST['user_id'];
    $videoId = $_POST['video_id'];
    $comment = $_POST['comment'];
    
    $stmt = $conn->prepare("INSERT INTO `comment` (`video_id`, `user_id`, `comment`) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $videoId, $userId, $comment);  
    
    $stmt->execute(); // Execute the prepared statement
    
    $videoId = (int)$_POST['video_id'];
    header("Location: view_video1.php?filename=$videoId"); // Redirect after inserting the comment
    
    exit(); // Ensure script execution stops after redirection
       
} else {
    // Handle invalid request method
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}
