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

        #personal-info {
            display: none;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
    $active = "Educational/Team Building";
    include "../../inc/Include.php";
    include "side-bar.php";
    include "header.php";
    ?>

    <div class="overlay">
        <div class="spinner-grow text-light" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>

    <div class="main">
        <!-- <div class="row">
            <div class="col head">
                <h1><i class="fa-solid fa-house fa-fw"></i>&nbsp;&nbsp;Ticketed</h1>
            </div>
        </div> -->
        <form style="width: 100%;" id="team-building-form" class="needs-validation" method="POST" novalidate>
            <div class="row wrap" id="ticketed">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4>Educational/Team building</h4>
                        </div>
                    </div>

                    <div class="row body">
                        <div class="row mt-4 wd-100">
                            <div class="col-4">
                                <label for="branch">Branch</label><label for="" class="required">*</label>
                                <select name="branch" class="form-control" id="branch" required>
                                    <option selected>Calumpang</option>
                                    <option>Calamba</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4 wd-100">
                            <div class="col-4">
                                <label for="s-date">Start Date</label><label for="" class="required">*</label>
                                <input type="date" id="sdate" placeholder="Departure" name="sdate"
                                    class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="edate">End Date</label><label for="" class="required">*</label>
                                <input type="date" id="edate" placeholder="Arrival" name="edate"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="row mt-4 wd-100">
                            <div class="col-4">
                                <label for="">Hotel <span class="w-500 font-italic">(optional)</span></label>
                                <input type="text" class="form-control" placeholder="Okada" name="hotel">
                            </div>
                            <div class="col-4">
                                <label for="">Pax</label><label for="" class="required">*</label>
                                <input type="number" class="form-control" placeholder="4" min="1" name="pax" required>
                            </div>
                            <div class="col-4">
                                <label for="">Attractions</label><label for="" class="required">*</label>
                                <input type="text" class="form-control" placeholder="Zipline, Trekking"
                                    name="attraction">
                            </div>
                        </div>

                        <div class="col-12 mt-4" style="display:flex; justify-content: flex-end;">
                            <button id="next-t" class="btn btn-primary pl-5 pr-5 btn-next" type="button">Next</button>
                            <button style="display:none" type="submit" name="educational" id="submit">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form style="width: 100%;" id="lead-info" class="needs-validation" action="inc/lead-info.php" method="POST" novalidate>
            <div class="row wrap" id="personal-info">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4><i class="fa-solid fa-plane"></i> Lead Person Information</h4>
                        </div>
                    </div>

                    <div class="row body">
                        <div class="row info">

                        </div>
                        <div class="col-12 mt-5" style="display:flex; justify-content: flex-end;">
                            <button id="next" class="btn btn-primary pl-5 pr-5 btn-next" type="button">Next</button>
                            <button style="display:none" type="submit" name="lead-info">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <script src="js/validation.js"></script>
</body>

</html>