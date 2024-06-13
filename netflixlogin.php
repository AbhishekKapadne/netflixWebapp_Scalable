<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix</title>
    <link rel="icon" href="./LoginSignupAssests/images/favicon/netflix.ico" type="image/x-icon">
    <link rel="stylesheet" href="./LoginSignupAssests/plugin/bootstrap5/css/bootstrap.min.css">
    <link rel="stylesheet" href="./LoginSignupAssests/css/style.css">
    <link rel="stylesheet" href="./LoginSignupAssests/css/responsive.css">
    <script src="./LoginSignupAssests/plugin/jquery/jquery-3.6.3.min.js"></script>
</head>

<body>
    <section class="main-word-page">
        <div class="login-block word-login-page ">
            <div class="container-new container">
                <!-- Login Form -->
                <div class="row">
                    <div class="col-md-12 col-lg-6 col-12 mx-md-auto">
                        <div class="logo-iamge">
                            <img src="./LoginSignupAssests/images/logo/logoNetflix.png" alt="" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-12 login-sec mx-md-auto ">
                        <!-- PHP CODE -->
                        <?php
                        session_start();

                        // Check if user is already logged in, redirect to index.php if logged in
                        if (isset($_SESSION['user_id'])) {
                            header("Location: index.php");
                            exit;
                        }

                        $hostName = "netflixwebapp.mysql.database.azure.com";
$dbUser = "Abhishek_database";
$dbPassword = "UlsterScalableCW@1410";
$dbName = "netflix_database";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }
                        if (isset($_POST["login"])) {

                            $email = $_POST["email"];
                            $password = $_POST["password"];
                            require_once "database.php";
                            $sql = "SELECT * FROM users WHERE email = '$email'";
                            $result = mysqli_query($conn, $sql);

                            if ($result) {
                                $user = mysqli_fetch_assoc($result);
                                if ($user) {
                                    if (password_verify($password, $user["password"])) {

                                        $_SESSION['user_id'] = $user['id'];
                                        $_SESSION['user_name'] = $user['fullname'];
                                        $_SESSION['welcome_shown'] = false;
                                        header("Location: index.php");
                                        exit();
                                    } else {
                                        echo "<div class='alert alert-danger'>Password Does not match</div>";
                                    }
                                } else {
                                    echo "<div class='alert alert-danger'>Email Does not exist</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger'>Error in database query</div>";
                            }
                        }
                        ?>
                        <!-- PHP CODE -->
                        <div class="loginFormMainDiv">
                            <h2>Sign In</h2>
                            <form class="login-form" method="POST" action="netflixlogin.php">
                                <!-- Added method and action attributes -->
                                <div class="form-label">

                                    <label for="emailaddress">Email address or mobile number</label>
                                    <input name="email" type="text" class="form-control"
                                        placeholder="Email address or mobile number">
                                </div>
                                <div class="form-label passwordfield">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                    <span toggle="#password-field" class="field-icon "><i class="mdi mdi-eye-off"></i>
                                    </span>
                                </div>
                                <button type="submit" name="login" class="btn btn-login  w-300">Sign In</button>
                                <!-- Added name attribute -->
                                <p class="text-center fs-16 color-white">New to Netflix? <span class="c-blue"><a
                                            href="./netflixSignup.php" class="cus-anchor-word gotosignup">Sign up
                                            now.</a></span></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="./LoginSignupAssests/plugin/bootstrap5/js/bootstrap.min.js"></script>

</body>

</html>