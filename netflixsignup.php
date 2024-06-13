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
        <div class="login-block word-login-page">
            <div class="container-new container">
                <div class="row">
                    <div class="col-md-12 col-lg-6 col-12 mx-md-auto">
                        <div class="logo-iamge">
                            <img src="././LoginSignupAssests/images/logo/logoNetflix.png" alt="" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-12 login-sec mx-md-auto">
                        <!-- PHP CODE -->
                        <?php
                        $hostName = "netflixwebapp.mysql.database.azure.com";
                        $dbUser = "Abhishek_database";
                        $dbPassword = "UlsterScalableCW@1410";
                        $dbName = "netflix_database";
                        $conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
                         if (!$conn) {
                             die("Something went wrong: " . mysqli_connect_error());
                         }
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                          
                            $fullname = $_POST["fullname"];
                            $email = $_POST["email"];
                            $password = $_POST["password"];
                            $repeatpassword = $_POST["repeat_password"];

                            $errors = array();

                            if (empty($fullname) || empty($email) || empty($password) || empty($repeatpassword)) {
                                $errors[] = "All fields are required";
                            }

                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $errors[] = "Email is not valid";
                            }

                            if (strlen($password) < 8) {
                                $errors[] = "Password must be at least 8 characters long";
                            }

                            if ($password !== $repeatpassword) {
                                $errors[] = "Passwords do not match";
                            }

                            if (count($errors) == 0) {
                                $sql = "SELECT * FROM users WHERE email = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("s", $email);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    $errors[] = "Email already exists!";
                                } else {
                                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                                    $sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("sss", $fullname, $email, $passwordHash);
                                    if ($stmt->execute()) {
                                        header("Location: netflixlogin.php");
                                    } else {
                                        echo "<div class='alert alert-danger'>Something went wrong</div>";
                                    }
                                }
                            }

                            if (count($errors) > 0) {
                                foreach ($errors as $error) {
                                    echo "<div class='alert alert-danger'>$error</div>";
                                }
                            }
                            $conn->close();
                        }
                        ?>

                        <!-- PHP CODE -->
                        <div class="signupFormMainDiv">
                            <h2>Sign Up</h2>
                            <div class="Donortab">
                                <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="form-label">
                                        <label for="fullname">Name</label>
                                        <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Name">
                                    </div>
                                    <div class="form-label">
                                        <label for="email">Email Address</label>
                                        <input type="text" id="email" name="email" class="form-control" placeholder="abc@gmail.com">
                                    </div>
                                    <div class="form-label passwordfield">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                                    </div>
                                    <div class="form-label passwordfield">
                                        <label for="repeat_password">Confirm Password</label>
                                        <input type="password" id="repeat_password" name="repeat_password" class="form-control" placeholder="Confirm Password">
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-login w-300 btn-signup">Sign Up</button>
                                    <p class="text-center fs-16 color-white">Already have an account?<span class="c-blue"><a href="./netflixlogin.php" class="cus-anchor-word backtosignin"> Sign In</a></span></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="./LoginSignupAssests/plugin/bootstrap5/js/bootstrap.min.js"></script>

</body>

</html>