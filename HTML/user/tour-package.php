<?php
session_start();
session_regenerate_id();

if (!$_SESSION['userid']) {
    header("Location:index.php?login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../../inc/cdn.php" ?>
    <?php include "../../CSS/links.php" ?>
    <title>Home</title>
    <style>
        .main .col.card .row {
            align-items: center;
        }

        .wd-100 {
            width: 100%;
        }

        .main .card-img-top {
            height: 200px;
            object-fit: cover;
            object-position: 0% 0%;
        }

        .main .card {
            border-radius: 12px;
            border: 1px solid rgb(0 0 0 / 8%);
            cursor: pointer;
            transition: 0.5s ease;
            box-shadow: 3px 1px 10px -5px rgba(0, 0, 0, 0.3);
        }

        .main .card:hover {
            transform: translateY(-2%);
        }

        .main .card .book-btn {
            background: var(--maindark);
            color: white;
            border: 1px solid var(--maindark);
            float: right;
            font-family: Analouge;
            padding: 6px 20px;
            font-size: 14px;
        }

        .main .card .book-btn:hover {
            background: transparent;
            border: 1px solid var(--maindark);
            color: var(--maindark);
        }

        .main .card .days {
            font-size: 14px;
        }

        .main .card h5 {
            margin-bottom: 4px;
            position: absolute;
            transform: translateY(-3.2rem);
            z-index: 10;
            color: white;
        }

        .flex h6 {
            margin: 0;
            font-size: 18px;
        }

        .flex h6,
        h5 {
            color: var(--maindark)
        }

        .main .card p {
            height: 130px;
        }

        .image-container {
            position: relative;
            /* Ensures the pseudo-element positions correctly */
            overflow: hidden;
            /* Keeps everything inside the container */
        }

        .card-img-top {
            display: block;
            /* Ensures the image takes up the full width of its container */
            width: 100%;
            /* Makes the image responsive */
        }

        .image-container::before {
            content: "";
            /* Required for pseudo-elements */
            position: absolute;
            top: 0;
            left: 0;
            background: linear-gradient(19deg, rgb(148 39 48 / 81%), rgb(0 0 0 / 0%));
            /* Slanted gradient effect */
            /* Gradient effect */
            /* Black overlay with opacity */
            height: 100%;
            width: 100%;
            z-index: 1;
            /* Makes sure the overlay appears above the image */
        }
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
    $active = "Tour";
    include "../../inc/Include.php";
    include "side-bar.php";
    include "header.php";

    $query = "SELECT * FROM tourpackage";
    $res = mysqli_query($conn, $query);

    ?>
    <div class="main">
        <form action="" style="width: 100%;" class="needs-validation" novalidate>
            <input type="hidden" value="<?php echo "$userid"; ?>"
                name="userid">
            <div class="row wrap" id="tour">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4><i class="fa-solid fa-plane"></i> Tour Packages</h4>
                        </div>
                    </div>

                    <div class="row body">
                        <div class="row mt-2 mb-3 wd-100">
                            <?php
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_array($res)) {
                            ?>
                                    <div class="card mr-3 mb-3" style="width: 18rem;">
                                        <div class="image-container">
                                            <img src="../admin/uploads/<?php echo "$row[img]" ?>" class="card-img-top" alt="tour-package">
                                        </div>

                                        <div class="card-body">
                                            <h5 class="card-title w-700"><?php echo "$row[title]" ?></h5>

                                            <h6 class="days w-700"><?php echo "$row[duration]" ?></h6>
                                            <p class="card-text"><?php echo "$row[description]" ?></p>
                                            <hr>
                                            <div class="flex">
                                                <h6 class="price w-700">&#x20B1; <?php echo "$row[price]" ?></h6>
                                                <a href="#" class="btn btn-primary book-btn">Book Now <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </div>

                        <!-- <div class="col-12 mt-5" style="display:flex; justify-content: flex-end;">
                            <button id="next-ticket" class="btn btn-primary pl-5 pr-5 btn-next">Next</button>
                        </div> -->

                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="js/validation.js"></script>
</body>

</html>