<?php

session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: netflixlogin.php");
    exit;
}

    $id = $_SESSION['user_id'];
    $username = $_SESSION['user_name'];

    $showWelcome = !isset($_SESSION['welcome_shown']) || $_SESSION['welcome_shown'] == false;
     if ($showWelcome) {
       $_SESSION['welcome_shown'] = true; // Update the session variable after showing the welcome message
     }

  ?> 


<?php
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "netflix_webapp";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle file uploads (thumbnail and video link)
    $thumbnail = $_FILES['thumbnail']['name'];
    $thumbnail_temp = $_FILES['thumbnail']['tmp_name'];
    move_uploaded_file($thumbnail_temp, "thumbnails/$thumbnail");

    // $video_link = $_FILES['video_link']['name'];
    // $video_temp = $_FILES['video_link']['tmp_name'];
    // move_uploaded_file($video_temp, "videos/$video_link");

    // Sanitize and validate other form fields
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $language = mysqli_real_escape_string($conn, $_POST['language']);
    $video_link = mysqli_real_escape_string($conn, $_POST['video_link']);

    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);

    // Check if a movie with the same title already exists
    $check_sql = "SELECT id FROM movies WHERE title = '$title'";
    $check_result = $conn->query($check_sql);
    if ($check_result && $check_result->num_rows > 0) {
        die("Error: Movie with the same title already exists!");
    }

    // Insert movie data into the database
    $sql = "INSERT INTO movies (title, year, language, genre, duration, thumbnail, video_link) 
            VALUES ('$title', '$year', '$language', '$genre', '$duration', '$thumbnail', '$video_link')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch movies from the database
$sql = "SELECT * FROM movies";
$result = $conn->query($sql);

$movies = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
} else {
    echo "No movies found";
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <noscript>
        <link rel="stylesheet" href="./UsersAssests/css/swiper-bundle.min.css">
    </noscript>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .movie-card-banner img {
            width: 100%;
            height: 550px;
            object-fit: cover;
        }

        #search-form {
            margin-right: 10px;
        }

        a.nav-link.logoutbtn.py-0 {
            padding: 0;
        }

        #search-form input[type="search"] {
            width: 200px;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #000;
        }

        #search-form button {
            padding: 14px 15px;
            border: none;
            border-radius: 5px;
            background-color: #e50914;
            color: #fff;
            cursor: pointer;
        }

        .movie-card {
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 22px;
            left: 0;
            width: 100%;
            height: 550px;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .banner-hover {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            color: #fff;
            z-index: 2;
        }
        .logoutbtns{
            background-color: rgb(229, 9, 20);
            cursor: pointer;

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
                                        <a class="nav-link tx-AFAFAF fs-18" href="#">Home</a>
                                    </li>
                                    <li class="nav-item px-2">
                                        <a class="nav-link tx-AFAFAF fs-18" href="#">Tv Shows</a>
                                    </li>
                                    <li class="nav-item px-2">
                                        <a class="nav-link tx-AFAFAF fs-18" href="#">Movies</a>
                                    </li>
                                    <li class="nav-item px-2">
                                        <a class="nav-link tx-AFAFAF fs-18" data-toggle="modal"
                                            data-target="#exampleModal">Add Movies</a>
                                    </li>
                                </ul>
                                <form class="form-inline my-2 my-lg-0 ml-auto d-none" id="search-form" method="get"
                                    action="#">
                                    <input class="form-control mr-sm-2" type="search" placeholder="Search"
                                        aria-label="Search" name="q" id="search-input">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit"
                                        id="search-button">Search</button>
                                </form>

                                <ul class="navbar-nav ml-auto d-flex">
                                    <li class="nav-item px-2 m-lg-auto">
                                        <a class="nav-link search-icon" href="#"><i class="fa fa-search"></i> </a>
                                    </li>
                                    <li class="nav-item nav-box px-2">
                                        <!-- <a class="nav-link logoutbtn  py-0" href="./netflixLogin.php">LOGOUT</a> -->
                                        <form action="logout.php" method="post">
                                            <button type="submit"  class="logoutbtns">Logout</button>
                                        </form>
                                    </li>
                                </ul>

                            </div>
                        </div>

                    </nav>
                </header>


                <div class="header-height-50"></div>

                <!--Movie-banner section-->
                <div class="sectional-padding-2"></div>
                <section id="testimonial" class="movie-banner">
                    <div class="container-fluid container-padding">
                        <div class="row">
                            <div class="swiper-container" id="movie-slider">
                                <div class="swiper-wrapper">
                                    <?php
                                 
                                    foreach ($movies as $movie) {
                                        
                                        ?>
                                        
                                        <div class="swiper-slide">
                                            <a
                                                href="view_video1.php?filename=<?php echo urlencode($movie['id']); ?>">
                                                <div class="movie-card movie-card-banner"
                                                    data-video-url="<?php echo $movie['video_link']; ?>">
                                                    <div class="overlay"></div>
                                                    <img src="thumbnails/<?php echo $movie['thumbnail']; ?>" alt="thumbnail"
                                                        class="w-550 img-fluid">
                                                    <div class="banner-hover">
                                                        <h3><?php echo $movie['title']; ?></h3>
                                                        <h5><?php echo $movie['year'] . ' - ' . $movie['language'] . ' - ' . $movie['genre']; ?>
                                                        </h5>
                                                        <h5><?php echo $movie['duration'] . " Minutes"; ?></h5>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>


                                        <?php
                                    }
                                    

                                    ?>
                                </div>
                                <div class="sectional-padding"></div>
                                <div class="swiper-pagination"></div>

                            </div>
                        </div>
                    </div>
                    <div class="sectional-padding"></div>

                   
                </section>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog word-login-page">
                <div class="modal-content  login-sec">
                    <div class="modal-body ">
                        <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                            method="post" enctype="multipart/form-data">
                            <div class="form-label">
                                <label for="title">Movie Title</label>
                                <input type="text" id="title" name="title" class="form-control"
                                    placeholder="Movie Title" required>
                            </div>
                            <div class="form-label">
                                <label for="year">Movie Year</label>
                                <input type="text" id="year" name="year" class="form-control" placeholder="Movie Year"
                                    required>
                            </div>
                            <div class="form-label">
                                <label for="language">Language</label>
                                <input type="text" id="language" name="language" class="form-control"
                                    placeholder="Language" required>
                            </div>
                            <div class="form-label">
                                <label for="genre">Genre</label>
                                <input type="text" id="genre" name="genre" class="form-control" placeholder="Genre"
                                    required>
                            </div>
                            <div class="form-label">
                                <label for="thumbnail">Thumbnail</label>
                                <input type="file" id="thumbnail" name="thumbnail" class="form-control"
                                    placeholder="Thumbnail Image" required>
                            </div>
                            <div class="form-label">
                                <label for="video_link">Video Link</label>
                                <input type="text" id="video_link" name="video_link" class="form-control"
                                    placeholder="Video Link" required>
                            </div>
                            <div class="form-label">
                                <label for="duration">Duration (in minutes)</label>
                                <input type="number" id="duration" name="duration" class="form-control"
                                    placeholder="Duration" required>
                            </div>
                            <div class="">
                                <input type="submit" class="btn btn-login" value="Add Movie">
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- jquery-3.5.1 -->
    <script src="./UsersAssests/js/jquery.min.js"></script>

    <!-- Bootstrap 4 JS -->
    <script src="./UsersAssests/js/bootstrap.min.js"></script>
    <script src="./UsersAssests/js/popper.min.js"></script>
    <script src="./UsersAssests/js/swiper-bundle.min.js"></script>

    <!-- custom js-->
    <script src="./UsersAssests/js/custom.js"></script>
    <script>
        $(document).ready(function () {
            $(".search-icon").click(function () {
                $("#search-form").toggleClass("d-none");
            });
        });
    </script>
    <script>
        document.getElementById('search-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const searchTerm = document.getElementById('search-input').value.trim().toLowerCase();
            const movieCards = document.querySelectorAll('.movie-card');
            movieCards.forEach(function (card) {
                const title = card.querySelector('h3').innerText.trim().toLowerCase();
                const firstLetter = title.charAt(0);
                if (firstLetter === searchTerm) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Hide the swiper-wrapper if no search results are found
            const swiperWrapper = document.querySelector('.swiper-wrapper');
            const visibleCards = document.querySelectorAll('.movie-card[style="display: block;"]');
            if (visibleCards.length === 0) {
                swiperWrapper.style.display = 'none';
            } else {
                swiperWrapper.style.display = 'flex';
            }
        });


    </script>

<?php if ($showWelcome): ?>

<script>
  
  const username = "<?php echo htmlspecialchars($username); ?>";

      const Toast = Swal.mixin({
      toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
              toast.onmouseenter = Swal.stopTimer;
              toast.onmouseleave = Swal.resumeTimer;
          }
          });
          Toast.fire({
          icon: "success",
          title: `😍😍😘Hello, ${username}!  🎉`,
          });
   </script>
<?php endif; ?>

</body>

</html>