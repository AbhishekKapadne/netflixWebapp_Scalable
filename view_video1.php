<?php
session_start();

// Establish a database connection
$hostName = "netflixwebapp.mysql.database.azure.com";
$dbUser = "Abhishek_database";
$dbPassword = "UlsterScalableCW@1410";
$dbName = "netflix_database";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    $userId = null;
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define $video_source
$video_source = "local"; // You can change this value as needed

// Initialize variables
$video_link = '';
$videoId = null; // Initialize video ID

// Check if the 'video_link' parameter is provided in the URL
if (isset($_GET['video_link'])) {
    $video_link = $_GET['video_link'];

    // Prepare the SQL query to fetch video details
    $query = "SELECT id FROM movies WHERE video_link = ?";

    // Prepare and execute the statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $video_link);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch video details
        $row = $result->fetch_assoc();
        $videoId = $row['id'];
    } else {
        echo "Video not found.";
    }
}

// Handle like and dislike actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['like']) && $videoId !== null) {
        // Check if the user has already liked the video
        $checkLikeQuery = "SELECT * FROM likes WHERE video_id = $videoId AND user_id = ?";
        $stmt = $conn->prepare($checkLikeQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $likeResult = $stmt->get_result();

        if ($likeResult->num_rows === 0) {
            // Insert a new like
            $insertLikeQuery = "INSERT INTO likes (video_id, user_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insertLikeQuery);
            $stmt->bind_param("ii", $videoId, $userId);
            $stmt->execute();
        }
    }

    if (isset($_POST['dislike']) && $videoId !== null) {
        // Check if the user has already disliked the video
        $checkDislikeQuery = "SELECT * FROM dislikes WHERE video_id = $videoId AND user_id = ?";
        $stmt = $conn->prepare($checkDislikeQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $dislikeResult = $stmt->get_result();

        if ($dislikeResult->num_rows === 0) {
            // Insert a new dislike
            $insertDislikeQuery = "INSERT INTO dislikes (video_id, user_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insertDislikeQuery);
            $stmt->bind_param("ii", $videoId, $userId);
            $stmt->execute();
        }
    }
}

// Check if user has liked or disliked the video
$hasLiked = false;
$hasDisliked = false;

if ($videoId !== null && isset($_SESSION['user_id'])) {
    $checkLikeQuery = "SELECT * FROM likes WHERE video_id = $videoId AND user_id = ?";
    $stmt = $conn->prepare($checkLikeQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $likeResult = $stmt->get_result();
    $hasLiked = $likeResult->num_rows > 0;

    $checkDislikeQuery = "SELECT * FROM dislikes WHERE video_id = $videoId AND user_id = ?";
    $stmt = $conn->prepare($checkDislikeQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $dislikeResult = $stmt->get_result();
    $hasDisliked = $dislikeResult->num_rows > 0;

    // Count total likes and dislikes
    $countLikesQuery = "SELECT COUNT(*) AS total_likes FROM likes WHERE video_id = $videoId";
    $countDislikesQuery = "SELECT COUNT(*) AS total_dislikes FROM dislikes WHERE video_id = $videoId";

    $totalLikesResult = $conn->query($countLikesQuery);
    $totalLikes = $totalLikesResult->fetch_assoc()['total_likes'];

    $totalDislikesResult = $conn->query($countDislikesQuery);
    $totalDislikes = $totalDislikesResult->fetch_assoc()['total_dislikes'];
}

// Check if 'filename' parameter exists in the URL
if (isset($_GET['filename'])) {
    $movieId = (int) $_GET['filename']; // Ensure the ID is an integer
    $query = "SELECT video_link,id FROM movies WHERE id = $movieId";
    $result = $conn->query($query);
    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $videoLink = $row['video_link'];
            $videoId = $row['id'];

            //like count code
            $sql = "SELECT video_id, COUNT(*) AS like_count 
            FROM likes 
            WHERE `like` = 1 AND video_id = ? 
            GROUP BY video_id";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $videoId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $videoLikesCount = $result->fetch_assoc();
                $likesVideo = $videoLikesCount['like_count'];
            } else {
                $likesVideo = 0; // No likes found
            }

            $stmt->close();

            // Count dislikes
            $dislikesql = "SELECT video_id, COUNT(*) AS dislike_count 
            FROM likes 
            WHERE `dislike` = 1 AND video_id = ? 
            GROUP BY video_id";

            $dislikequery = $conn->prepare($dislikesql);
            $dislikequery->bind_param("i", $videoId);
            $dislikequery->execute();
            $dislikeresult = $dislikequery->get_result();

            if ($dislikeresult->num_rows > 0) {
                $videoDisLikesCount = $dislikeresult->fetch_assoc();
                $disLikesVideo = $videoDisLikesCount['dislike_count'];
            } else {
                $disLikesVideo = 0; // No dislikes found
            }

            $dislikequery->close();

            //comment code

            $Comment = "SELECT * FROM Comment WHERE video_id = ?";
            $commentquery = $conn->prepare($Comment);
            $commentquery->bind_param("i", $videoId); // Assuming video_id is an integer
            $commentquery->execute();
            $commentqueryresult = $commentquery->get_result();
        } else {
            echo "No video link found for the given ID.";
        }
    } else {
        echo "Error executing query: " . $conn->error;
    }
    $result->free();
} else {
    echo "No filename provided.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix</title>
    <link rel="icon" href="./LoginSignupAssests/images/favicon/netflix.ico" type="image/x-icon">
    <link rel="stylesheet" href="./UsersAssests/css/bootstrap.min.css">
    <link rel="stylesheet" href="./UsersAssests/css/custom.css">
    <link rel="stylesheet" href="./LoginSignupAssests/css/style.css">
    <link rel="preload" href="./UsersAssests/css/swiper-bundle.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="./UsersAssests/css/swiper-bundle.min.css">
    </noscript>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .like,
        .dislike {
            background-color: transparent;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .like.clicked,
        .dislike.clicked {
            color: blue;
        }

        .like,
        .dislike {
            background-color: transparent;
            border: none;
            cursor: pointer;
            font-size: 16px;
            outline: none;
        }

        .like.clicked,
        .dislike.clicked {
            color: #e50914;
            /* Netflix red color */
            background-color: #fff;
        }

        .like:hover,
        .dislike:hover {
            color: #e50914;
            /* Netflix red color */
            background-color: #fff;
            font-weight: bold;
        }

        .like:focus,
        .dislike:focus {
            outline: none;
        }

        /* Style for the container */
        .row {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        /* Style for the spans */
        .row span {
            margin: 0 20px;
            font-size: 14px;
            color: #666;
        }

        /* CSS */
        .like-container {
            display: flex;
            align-items: center;
        }

        .like-count-input {
            width: 50px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            font-size: 16px;
            margin-right: 10px;
        }

        body {
            background-color: #141414;
            /* Netflix dark background */
            color: #fff;
            /* White text for better contrast */
            font-family: Arial, sans-serif;
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }

        button {
            background-color: #e50914;
            /* Netflix red */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border-radius: 5px;
        }

        button.clicked {
            background-color: #b20710;
            /* Darker red when clicked */
        }

        button:hover {
            background-color: #f40612;
            /* Brighter red on hover */
        }

        input.like-count-input {
            background-color: #333;
            border: 1px solid #555;
            color: white;
            padding: 5px;
            width: 50px;
            margin-left: 10px;
            border-radius: 5px;
            text-align: center;
        }


        .like-button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
        }

        .like-button:hover {
            background-color: #0056b3;
        }

        form.cmtsection {
            align-items: center;
            display: flex;
        }

        span.cmttitle {
            font-size: 20px;
            font-weight: 600;
            color: #fff;
        }

        .cmttextarea {
            border-radius: 10px;
            border: 1px solid red;
            outline: none;
            font-size: 18px;
            padding: 10px;
            font-weight: 700;
        }

        .cmttextarea:focus,.cmttextarea:focus-visible{
            border: 4px solid red;
        }
    </style>
</head>

<body>

    <div id="martini-home">
        <div class="page-wrapper">
            <div class="header-banner-wrapper">
                <header>
                    <nav class="navbar navbar-expand-lg navbar-light navbar-hover fixed-top header">
                        <div class="container">
                            <a class="logo-white" href="#"><img loading="lazy"
                                    src="./LoginSignupAssests/images/logo/logoNetflix.png" class="img-fluid"
                                    alt="header-logo"></a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target="#navbarHover" aria-controls="navbarHover" aria-expanded="false"
                                aria-label="Navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarHover">
                                <ul class="navbar-nav mx-auto">
                                    <li class="nav-item px-2">
                                        <a class="nav-link tx-AFAFAF fs-18" href="index.php">Home</a>
                                    </li>
                                    <li class="nav-item px-2">
                                        <a class="nav-link tx-AFAFAF fs-18" href="#">Tv Shows</a>
                                    </li>
                                    <li class="nav-item px-2">
                                        <a class="nav-link tx-AFAFAF fs-18" href="#">Movies</a>
                                    </li>
                                </ul>


                                <ul class="navbar-nav ml-auto d-flex">
                                    <li class="nav-item nav-box px-2">
                                        <a class="nav-link logoutbtn  py-0" href="./netflixlogin.php">LOGOUT</a>
                                    </li>
                                </ul>

                            </div>
                        </div>

                    </nav>
                </header>


                <div class="header-height-50"></div>
                <!-- Add comment form -->


                <!--Movie-banner section-->
                <div class="sectional-padding-2"></div>
                <div class="Container moviecontainer">
                    <div class="row">

                        <?php if ($videoLink !== ''):
                            ?>
                            <iframe src="<?php echo $videoLink; ?>" autoplay width="1500" height="500" frameborder="0"
                                allowfullscreen></iframe>

                        <?php else: ?>
                            <p>Video not found.</p>
                        <?php endif; ?>
                    </div>

                    <div class="row like-comment">
                        <button id="like" class="like <?php echo $hasLiked ? 'clicked' : ''; ?>">Like</button>
                        <input type="number" id="like-count" class="like-count-input" disabled
                            value="<?php echo isset($likesVideo) ? $likesVideo : 0; ?>" readonly>

                        <button id="dislike"
                            class="dislike <?php echo $hasDisliked ? 'clicked' : ''; ?>">Dislike</button>
                        <input type="number" id="dislike-count" class="like-count-input" disabled
                            value="<?php echo isset($disLikesVideo) ? $disLikesVideo : 0; ?>" readonly>
                    </div>

                    <div class="row like-comment">
                        <form action="comment.php" class="cmtsection" method="post">
                            <span class="cmttitle">Comment</span>
                            <input type="hidden" name="user_id" value="<?php echo $userId ?? ''; ?>">
                            <input type="hidden" name="video_id" value="<?php echo $videoId ?? ''; ?>">
                            <textarea name="comment" class="cmttextarea" rows="4" cols="40" id="comment"></textarea>
                            <button type="submit">Post</button>
                        </form>
                    </div>

                    <div class="row">
                        <div class="comments-container">
                            <?php if ($commentqueryresult->num_rows > 0): ?>
                                <div class="comments-container">
                                    <?php while ($resultComment = $commentqueryresult->fetch_assoc()): ?>
                                        <div class="comment addedcmt">
                                            <span><strong>Comment:</strong> <?php echo $resultComment['Comment']; ?></span>
                                            <div class="reply-form" style="display: none;">

                                            </div>
                                            <!-- You can add more interactive elements such as like/dislike buttons, edit/delete options, etc. -->
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p>No comments found for this video.</p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

<script>
    const userid = "<?php echo htmlspecialchars($userId); ?>";
    const videoId = "<?php echo htmlspecialchars($videoId); ?>";

    $('#like').click(function () {
        // AJAX call
        $.ajax({
            url: 'likedislike.php', // Replace with the URL of your server-side script
            type: 'get',
            data: {
                user_id: userid,
                video_id: videoId,
                Like: 1,
                Dislike: 0
            },
            success: function (response) {
                console.log("Response:", response);
                var responseObj = JSON.parse(response);
                var likesCount = responseObj.likes;
                var dislikesCount = responseObj.dislikes;

                $('#dislike-count').val(dislikesCount);
                $('#like-count').val(likesCount);
            },
            error: function (xhr, status, error) {
                // Handle any errors
                console.log('Error:', error);
            }
        });
    });

    $('#dislike').click(function () {
        // AJAX call
        $.ajax({
            url: 'likedislike.php', // Replace with the URL of your server-side script
            type: 'get',
            data: {
                user_id: userid,
                video_id: videoId,
                Like: 0,
                Dislike: 1
            },
            success: function (response) {
                // Log the entire response
                console.log("Response:", response);
                var responseObj = JSON.parse(response);
                var likesCount = responseObj.likes;
                var dislikesCount = responseObj.dislikes;

                $('#dislike-count').val(dislikesCount);
                $('#like-count').val(likesCount);




            },
            error: function (xhr, status, error) {
                // Handle any errors
                console.error(xhr.responseText); // Log the detailed error message
            }
        });
    });



</script>

</html>