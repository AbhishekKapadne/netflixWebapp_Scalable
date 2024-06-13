<?php

require_once "database.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_GET['user_id'];
    $videoId = $_GET['video_id'];
    $like = $_GET['Like'];
    $dislike = $_GET['Dislike'];

    // Check if the user has already liked/disliked the video
    $sql = "SELECT * FROM likes WHERE user_id = ? AND video_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $userId, $videoId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($user) {
        // Update existing like/dislike
        $stmt = $conn->prepare("UPDATE `likes` SET `like` = ?, `dislike` = ? WHERE `user_id` = ?");
        $stmt->bind_param("iii", $like, $dislike, $userId);
    } else {
        // Insert new like/dislike
        $stmt = $conn->prepare("INSERT INTO `likes` (`video_id`, `user_id`, `like`, `dislike`) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $videoId, $userId, $like, $dislike);
    }

    if ($stmt->execute()) {
        // Fetch updated like/dislike counts
        $likeCount = getCount($conn, $videoId, 'like');
        $dislikeCount = getCount($conn, $videoId, 'dislike');
        
        // Return updated counts as JSON response
        $response = array('likes' => $likeCount, 'dislikes' => $dislikeCount);
        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add like/dislike.']);
    }
    $stmt->close();
    exit;
} else {
    // Handle invalid request method
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

function getCount($conn, $videoId, $type) {
    $sql = "SELECT COUNT(*) AS count FROM likes WHERE `$type` = 1 AND video_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $videoId);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    $stmt->close();
    return $count;
}
?>
