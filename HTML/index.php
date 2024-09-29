<?php
session_start();
session_regenerate_id();

if (isset($_GET['logout'])) {
    unset($_SESSION['userid']);
    echo "<script>window.location.href='index.php?index';</script>";
}

if (isset($_GET['Logout'])) {
    unset($_SESSION['adminid']);
    echo "<script>window.location.href='index.php';</script>";
}


// if(isset($_SESSION['userid'])) {
//     header("Location:dashboard.php?userid=$_SESSION[userid]");
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/base6.css">
    <link rel="stylesheet" href="../CSS/user6.css">
    <?php include "../inc/cdn.php" ?>
    <title>Login</title>
    <style>
        .row,
        .col {
            margin: 0;
            padding: 0;
        }

        ul a {
            transition: 0.3s;
        }

        ul .hover:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <?php
    include "../inc/Include.php";

    ?>


    <div class="wrapper">
        <div class="header">
            <div class="row" style="align-items: center;">
                <div class="col">
                    <div class="col">
                        <img src="../Assets/icon.png" alt="zorella-logo" width="70px" style="margin-left: 80px;">
                    </div>
                </div>
                <div class="col">
                    <ul>
                        <li class="hover"><a href="#info">Contact us</a></li>
                        <li class="hover"><a href="#" data-toggle="modal" data-target="#sign-up">Create Account</a></li>
                        <li>
                            <div class="dropdown dropleft">
                                <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false" style="color: white;">
                                    <i class="fa-solid fa-bars fa-fw"></i>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <h2 class="dropdown-header">Sign in as</h2>
                                    <hr>
                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                        data-target="#sign-admin">Admin</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                        data-target="#sign-in">User</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="logo">
            <div class="row one">
                <div class="col" style="text-align:center;">
                    <h1>Zorella Travel and Tours</h1>
                    <h2 style="font-family: Analouge;">Your connection to the world</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="gallery">
        <div class="slide-gallery">
            <img src="../Assets/1.webp" alt="tourist-spot">
            <img src="../Assets/2.webp" alt="tourist-spot">
            <img src="../Assets/3.webp" alt="tourist-spot">
            <img src="../Assets/4.webp" alt="tourist-spot">
            <img src="../Assets/5.webp" alt="tourist-spot">
            <img src="../Assets/6.webp" alt="tourist-spot">
            <img src="../Assets/7.webp" alt="tourist-spot">
            <img src="../Assets/8.webp" alt="tourist-spot">
            <img src="../Assets/9.webp" alt="tourist-spot">
            <img src="../Assets/10.webp" alt="tourist-spot">
        </div>

        <div class="slide-gallery">
            <img src="../Assets/1.webp" alt="tourist-spot">
            <img src="../Assets/2.webp" alt="tourist-spot">
            <img src="../Assets/3.webp" alt="tourist-spot">
            <img src="../Assets/4.webp" alt="tourist-spot">
            <img src="../Assets/5.webp" alt="tourist-spot">
            <img src="../Assets/6.webp" alt="tourist-spot">
            <img src="../Assets/7.webp" alt="tourist-spot">
            <img src="../Assets/8.webp" alt="tourist-spot">
            <img src="../Assets/9.webp" alt="tourist-spot">
            <img src="../Assets/10.webp" alt="tourist-spot">
        </div>
    </div>

    <div class="choose-us">
        <div class="row choose">
            <div class="col-6 mr-5 pl-5">
                <img src="../Assets/choose-us.webp" alt="tourist-spot" height="90%" style="border-radius:4px;">
                <img src="../Assets/choose-us2.webp" alt="tourist-spot" height="90%" style="border-radius:4px;"
                    class="img-pos">
            </div>
            <div class="col" style="margin-top: 48px;margin-left:48px;">
                <h1>Why choose us?</h1>
                <p>When you plan your trip with one of our experienced travel advisors, you’ll discover a world of
                    difference creating memorable and carefree vacations. We know all vacations are not the same. With
                    our
                    first-hand experience, ability to expertly customize every aspect of your trip and access to the
                    very
                    best brands, lowest prices and most value - we ensure every journey is extraordinary!</p>
                <div class="button" style="margin-top: 48px;">
                    <button>Book now <i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="bg one">
                    <div class="feed-back">
                        <h1>HAPPY TRAVELERS</h1>
                        <p class="text-justify">"Makes traveling so easy! We just couldn't imagine planning the
                            honeymoon we
                            wanted while
                            also planning our wedding, so a friend recommended Explorateur and man I am glad she did.
                            They took my thoughts and ran with them and came back with two honeymoon options. We picked
                            Australia and the flights, hotels and transportation were all done for us. We had an amazing
                            honeymoon and did not have to think twice about any of the planning, just showed up with our
                            passports and suitcases!”</p>
                        <h3>-Rachel Salvatore</h3>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="bg two">
                    <div class="feed-back">
                        <h1>HAPPY TRAVELERS</h1>
                        <p class="text-justify">"Makes traveling so easy! We just couldn't imagine planning the
                            honeymoon we
                            wanted while
                            also planning our wedding, so a friend recommended Explorateur and man I am glad she did.
                            They took my thoughts and ran with them and came back with two honeymoon options. We picked
                            Australia and the flights, hotels and transportation were all done for us. We had an amazing
                            honeymoon and did not have to think twice about any of the planning, just showed up with our
                            passports and suitcases!”</p>
                        <h3>-Rachel Salvatore</h3>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="bg three">
                    <div class="feed-back">
                        <h1>HAPPY TRAVELERS</h1>
                        <p class="text-justify">"Makes traveling so easy! We just couldn't imagine planning the
                            honeymoon we
                            wanted while
                            also planning our wedding, so a friend recommended Explorateur and man I am glad she did.
                            They took my thoughts and ran with them and came back with two honeymoon options. We picked
                            Australia and the flights, hotels and transportation were all done for us. We had an amazing
                            honeymoon and did not have to think twice about any of the planning, just showed up with our
                            passports and suitcases!”</p>
                        <h3>-Rachel Salvatore</h3>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="best-offers">
        <div class="row offers">
            <div class="col-12">
                <h1 class="w-700">Explore Philippines with best deals</h1>
                <label for="" style="font-family: Inter;" class="w-500">Pick a vibe and explore the top destinations in
                    the Philippines</label>
                <div class="row mt-5">
                    <div class="col-3">
                        <img src="../Assets/best2.jpg" alt="promo-tours">
                    </div>
                    <div class="col-3">
                        <img src="../Assets/best3.jpg" alt="promo-tours">
                    </div>
                    <div class="col-3">
                        <img src="../Assets/best4.jpg" alt="promo-tours">
                    </div>
                    <div class="col-3">
                        <img src="../Assets/best5.jpg" alt="promo-tours">
                    </div>
                </div>
            </div>
        </div>

        <div class="row offers" style="margin-top:36px;">
            <div class="col-12">
                <h1 class="w-700">Top experiences on Zorella</h1>
                <label for="" style="font-family: Inter;" class="w-500">The best tours, activities and tickets</label>
                <div class="row mt-5">
                    <div class="col-3">
                        <img src="../Assets/top1.jpg" alt="promo-tours">
                    </div>
                    <div class="col-3">
                        <img src="../Assets/top2.jpg" alt="promo-tours">
                    </div>
                    <div class="col-3">
                        <img src="../Assets/top3.jpg" alt="promo-tours">
                    </div>
                    <div class="col-3">
                        <img src="../Assets/top4.jpg" alt="promo-tours">
                    </div>
                    <div class="col-3">
                        <img src="../Assets/top5.jpg" alt="promo-tours">
                    </div>
                    <div class="col-3">
                        <img src="../Assets/top6.jpg" alt="promo-tours">
                    </div>
                    <div class="col-3">
                        <img src="../Assets/top7.jpg" alt="promo-tours">
                    </div>
                    <div class="col-3">
                        <img src="../Assets/top8.jpg" alt="promo-tours">
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="contact-us" id="info">
        <div class="row contact">
            <div class="col-5">
                <div style="margin-left: 80px;">
                    <h1>CONTACT</h1>
                    <label for="" class="w-500" style="font-family: Inter;">We would love to hear from you!</label>

                    <div style="font-family: Inter;" class="mt-5">
                        <h5><i class="fa-solid fa-location-dot fa-fw mr-3"></i> Calumpang Liliw Laguna</h5>
                        <h5><a href="https://www.facebook.com/profile.php?id=100086190451044" target="_blank"><i
                                    class="fa-brands fa-facebook fa-fw mr-3"></i> Saint Rita Travel and Tours Services
                        </h5></a>
                        <h5><a href="https://www.facebook.com/ZorellaTravel.Tours" target="_blank"><i
                                    class="fa-brands fa-facebook fa-fw mr-3"></i> Zorella Travel and Tours</h5></a>
                        <h5><a href="mailto:saintritainquiry@gmail.com"><i class="fa-solid fa-envelope fa-fw mr-3"></i>
                                saintritainquiry@gmail.com</h5></a>
                        <h5><a href="mailto:zorellatravelandtours@gmail.com"><i
                                    class="fa-solid fa-envelope fa-fw mr-3"></i>
                                zorellatravelandtours@gmail.com</h5></a>
                        <h5><i class="fa-solid fa-phone fa-fw mr-3"></i> 09237374474</h5>
                        <h5><i class="fa-solid fa-phone fa-fw mr-3"></i> 09999915336</h5>
                    </div>
                </div>

            </div>
            <div class="col-7">
                <form action="" class="mt-4">
                    <div class="div">
                        <div class="form-group">
                            <label for="fullname">Fullname</label>
                            <input type="text" class="form-control" id="fullname" aria-describedby="emailHelp"
                                placeholder="Juan B. Dela Cruz" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" aria-describedby="emailHelp"
                                placeholder="juandelacruz@gmail.com" required>
                        </div>

                        <div class="form-group">
                            <label for="textarea">Message</label>
                            <textarea class="form-control" id="textarea" rows="3" required
                                placeholder="Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec ullamcorper nulla non metus auctor fringilla nullam quis risus."></textarea>
                        </div>

                        <div class="button" style="margin-top: 24px;">
                            <button>Message <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <label class="w-500">Zorella | <i class="fa-solid fa-copyright"></i> All Rights Reserved</label>
    </footer>

    <form action="" method="POST">
        <div class="modal" id="sign-up" tabindex="-1" role="dialog" aria-labelledby="sign-upTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="close">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="float:right;opacity:1;padding:0;">
                            <span aria-hidden="true">&times;</span>
                    </div>
                    </button>
                    <div class="modal-header" style="border-bottom:none;display:block;padding-top:0;">
                        <h3 class="modal-title" id="sign-upTitle" style="text-align:center;">Sign up</h3>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <input type="text" class="form-control form" id="firstname" name="firstname"
                                        aria-describedby="emailHelp" placeholder="&#xf007; Firstname"
                                        style="font-family:Arial, FontAwesome" required>
                                </div>
                            </div>
                            <div class="col" style="margin-left: 16px;">
                                <div class="form-group">
                                    <input type="text" class="form-control form" id="lastname" name="lastname"
                                        aria-describedby="emailHelp" placeholder="&#xf007; Lastname"
                                        style="font-family:Arial, FontAwesome" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control form" id="email" name="email"
                                aria-describedby="emailHelp" placeholder="&#xf0e0; Email"
                                style="font-family:Arial, FontAwesome" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form" id="number" name="number"
                                aria-describedby="emailHelp" placeholder="&#xf0e0; Phone Number"
                                style="font-family:Arial, FontAwesome" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control form" id="password" name="password"
                                aria-describedby="emailHelp" placeholder="&#xf023; Password"
                                style="font-family:Arial, FontAwesome" required>
                        </div>

                        <button type="submit" class="btn btn-primary" name="sign-up" style="width:100%;">Sign
                            up</button>
                    </div>
                    <div class="div" style="text-align:center;">
                        <label>Already a member?<span style="text-decoration:none;"> <a href="#" id="in">Sign
                                    in</a></span></label><br>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="" method="POST">
        <div class="modal" id="sign-in" tabindex="-1" role="dialog" aria-labelledby="sign-inTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="close">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="float:right;opacity:1;padding:0;">
                            <span aria-hidden="true">&times;</span>
                    </div>
                    </button>
                    <div class="modal-header" style="border-bottom:none;display:block;padding-top:0;">
                        <h3 class="modal-title" id="sign-inTitle" style="text-align:center;">Sign in</h3>
                    </div>
                    <div class="modal-body">
                        <?php
                        if (isset($_GET['login-first'])) {
                            echo "
                                    <div class='row login-first'>
                                        <div class='col'>
                                            <h6>Login first</h6>
                                            <label>You must log in to continue.</label>
                                        </div>
                                    </div>
                                    <script>$('#sign-in').modal('show');</script>
                                    ";
                        }

                        if (isset($_GET['wrong-password-or-email'])) {
                            echo "
                                <div class='row error'>
                                    <div class='col'>
                                        <h6>Incorrect password or username</h6>
                                        <label>It looks like you entered a slight misspelling of your email or password. Email or password is incorrect, please try again.</label>
                                    </div>
                                </div>
                                <script>$('#sign-in').modal('show');</script>
                                ";
                        }
                        ?>
                        <div class="form-group">
                            <input type="email" class="form-control form" id="email-sign" name="email-sign"
                                aria-describedby="emailHelp" placeholder="&#xf0e0; Email"
                                style="font-family:Arial, FontAwesome" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control form" id="password-sign" name="password-sign"
                                aria-describedby="emailHelp" placeholder="&#xf023; Password"
                                style="font-family:Arial, FontAwesome" required>
                            <div class="div w-100 text-right mt-1">
                                <input type="checkbox" name="show" id="showPassword" class="text-right">
                                <small class="text-right" class="mr-3">Show Password</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" name="sign-in" style="width:100%;">Sign
                            in</button>
                    </div>
                    <div class="div" style="text-align:center;">
                        <label>Need an account?<span style="text-decoration:none;"> <a href="#" id="here">Sign
                                    up</a></span></label><br>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <form action="" method="POST">
        <div class="modal" id="sign-admin" tabindex="-1" role="dialog" aria-labelledby="sign-adminTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="close">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="float:right;opacity:1;padding:0;">
                            <span aria-hidden="true">&times;</span>
                    </div>
                    </button>
                    <div class="modal-header" style="border-bottom:none;display:block;padding-top:0;">
                        <h3 class="modal-title" id="sign-adminTitle" style="text-align:center;">Sign in</h3>
                    </div>
                    <div class="modal-body">
                        <?php
                        if (isset($_GET['Login-first'])) {
                            echo "
                                    <div class='row login-first'>
                                        <div class='col'>
                                            <h6>Login first</h6>
                                            <label>You must log in to continue.</label>
                                        </div>
                                    </div>
                                    <script>$('#sign-admin').modal('show');</script>
                                    ";
                        }

                        if (isset($_GET['wrong-password-email'])) {
                            echo "
                                <div class='row error'>
                                    <div class='col'>
                                        <h6>Incorrect password or username</h6>
                                        <label>It looks like you entered a slight misspelling of your email or password. Email or password is incorrect, please try again.</label>
                                    </div>
                                </div>
                                <script>$('#sign-admin').modal('show');</script>
                                ";
                        }
                        ?>
                        <div class="form-group">
                            <input type="email" class="form-control form" id="email" name="email-admin"
                                aria-describedby="emailHelp" placeholder="&#xf0e0; Email"
                                style="font-family:Arial, FontAwesome" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control form" id="password" name="password-admin"
                                aria-describedby="emailHelp" placeholder="&#xf023; Password"
                                style="font-family:Arial, FontAwesome" required>
                            <div class="div w-100 text-right mt-1">
                                <input type="checkbox" name="show" id="showPassword" class="text-right">
                                <small class="text-right" class="mr-3">Show Password</small>
                            </div>
                        </div>

                        <button type="submit" name="admin" class="btn btn-primary" style="width:100%;">Sign in</button>
                    </div>
                    <div class="div" style="text-align:center;">
                        <label>Not an admin?<span style="text-decoration:none;"> <a href="#" id="here-admin">Sign
                                    in here</a></span></label><br>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php
    $options = ['cost' => 12,];

    if (isset($_POST['sign-up'])) {

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $number = $_POST['number'];
        $password = $_POST['password'];

        $hash_pass = password_hash("$password", PASSWORD_BCRYPT, $options);
        $query = "INSERT INTO user(firstname, lastname, email, password, phonenumber)
                        VALUES('$firstname', '$lastname', '$email', '$hash_pass', '$number')";
        // $results = mysqli_query($conn, $query);


        if (mysqli_query($conn, $query)) {
            echo "<script>window.location.href='index.php?alert=1';</script>";
        } else {
            echo mysqli_error($conn);
        }
    }

    if (isset($_POST['sign-in'])) {

        $email = $_POST['email-sign'];
        $password = $_POST['password-sign'];

        $query = "SELECT * FROM user where email = '$email'";
        $results = mysqli_query($conn, $query);

        if (mysqli_num_rows($results) > 0) {
            $rows = mysqli_fetch_array($results);
            $pwd_hashed = $rows['password'];

            if (password_verify($password, $pwd_hashed)) {
                $_SESSION['userid'] = $rows['userid'];

                if (isset($_SESSION['redirect_url'])) {
                    $redirectUrl = 'http://localhost' . $_SESSION['redirect_url'];
                    unset($_SESSION['redirect_url']); // Clear the session variable
                    // header("Location: $redirectUrl");
                    echo "<script>window.location.href='$redirectUrl';</script>";
                    exit();
                } else {
                    echo "<script>window.location.href='user/dashboard.php?userid=$rows[userid]';</script>";
                }
            } else {
                echo "<script>window.location.href='index.php?wrong-password-or-email';</script>";
            }
        } else {
            echo "<script>window.location.href='index.php?wrong-password-or-email';</script>";
        }
    }


    if (isset($_POST['admin'])) {

        $email = $_POST['email-admin'];
        $password = $_POST['password-admin'];

        $query = "SELECT * FROM admin where email = '$email'";
        $results = mysqli_query($conn, $query);

        if (mysqli_num_rows($results) > 0) {
            $rows = mysqli_fetch_array($results);
            $pwd_hashed = $rows['password'];

            if (password_verify($password, $pwd_hashed)) {
                $_SESSION['adminid'] = $rows['adminid'];
                echo "<script>window.location.href='admin/dashboard-admin.php?adminid=$rows[adminid]';</script>";
            } else {
                echo "<script>window.location.href='index.php?wrong-password-email';</script>";
            }
        } else {
            echo "<script>window.location.href='index.php?wrong-password-email';</script>";
        }
    }

    ?>

</body>

<script>
    // $('.carousel').carousel(true);
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(window.location.search);
    const alert = urlParams.get('alert');
    const wrongPassword = urlParams.get('wrong-password-or-email');

    if (alert == 1) {
        Swal.fire({
            title: "Saved",
            text: "New account successfully created. ",
            icon: "success"
        });
    }

    $('#showPassword').change(function() {
        var form = $(this).closest('form');
        var passwordInput = form.find('input[type="password"], input[type="text"]'); // Select both types

        if ($(this).is(':checked')) {
            passwordInput.attr('type', 'text'); // Show password
        } else {
            passwordInput.attr('type', 'password'); // Hide password
        }
    });

    $('#here-admin').click(function() {
        $('#sign-admin').modal('hide');
        $('#sign-in').modal('show');
    })
    $('#here').click(function() {
        $('#sign-in').modal('hide');
        $('#sign-up').modal('show');
    })
    $('#in').click(function() {
        $('#sign-up').modal('hide');
        $('#sign-in').modal('show');
    })
</script>

</html>