<?php
session_start();
session_regenerate_id();

if (!$_SESSION['userid']) {
    header("Location:index.php?login-first");
}

function random_strings($length_of_string)
{

    // String of all alphanumeric character
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Shuffle the $str_result and returns substring
    // of specified length
    return substr(
        str_shuffle($str_result),
        0,
        $length_of_string
    );
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

        .row.populate-quote {
            background-color: var(--sub);
            padding: 8px;
            border-radius: 4px;
            margin: 6px 24px;
            cursor: pointer;
        }

        .modal table.table-borderless tbody td {
            border: none;
            border-bottom: 1px solid #ddd;
        }

        label {
            margin: 0;
        }

        .row.populate-qoute:nth-child(even) {
            background-color: #ffb3a5;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
    $active = "Quotation";
    include "../../inc/Include.php";
    include "side-bar.php";

    ?>
    <div class="main">
        <form action="" style="width: 100%;" class="needs-validation" method="GET" novalidate>
            <?php include "header.php"; ?>
            <input type="hidden" value="<?php echo "$userid"; ?>"
                name="userid">

            <div class="row wrap" id="ticketed">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4>Request Quotation</h4>
                        </div>
                    </div>

                    <div class="row body">
                        <div class="row w-100 flex">
                            <h1 class="w-700">Requests</h1>
                            <div class="col-2">
                                <button type="button" style="float:right;" class="btn btn-success" data-toggle="modal"
                                    data-target="#request">
                                    <i class="fa-solid fa-add fa-fw"></i>Request
                                </button>
                            </div>
                        </div>

                        <div class="row w-100">
                            <?php
                            $quotation = "SELECT * FROM quotation WHERE userid = '$userid' ORDER BY quotationid DESC";
                            $res = mysqli_query($conn, $quotation);

                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_array($res)) {
                                    $date = date_create("$row[date]");
                                    $dateFormat = date_format($date, "F d, Y");

                                    $rand = random_strings(5);
                            ?>
                                    <div class="row populate-quote w-100 " data-toggle="modal"
                                        data-target="<?php echo "#$rand" ?>">
                                        <div class="col-5">
                                            <small><label for="" class="w-700">
                                                    <?php echo "$row[title] - From $row[origin] to $row[destination]" ?><br>
                                                </label>
                                            </small>
                                        </div>
                                        <div class="col-7 ellip">
                                            <small>
                                                <span><?php echo "$row[pax] pax for $row[days] Travel Date: $dateFormat" ?></span>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="modal" id="<?php echo $rand ?>" tabindex="-1" aria-labelledby="<?php echo $rand ?>Label" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="<?php echo $rand ?>Label">Quotation Information</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <td>
                                                                Trip Title
                                                            </td>
                                                            <td class="w-700">
                                                                <?php echo "$row[title]" ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Origin
                                                            </td>
                                                            <td class="w-700">
                                                                <?php echo "$row[origin]" ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Destination
                                                            </td>
                                                            <td class="w-700">
                                                                <?php echo "$row[destination]" ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Travel Date
                                                            </td>
                                                            <td class="w-700">
                                                                <?php echo "$dateFormat" ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                PAX
                                                            </td>
                                                            <td class="w-700">
                                                                <?php echo "$row[pax]" ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Duration
                                                            </td>
                                                            <td class="w-700">
                                                                <?php echo "$row[days]" ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Email
                                                            </td>
                                                            <td class="w-700">
                                                                <?php echo "$row[email]" ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Branch
                                                            </td>
                                                            <td class="w-700">
                                                                <?php echo "$row[branch]" ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Status
                                                            </td>
                                                            <td class="w-700">
                                                                <?php
                                                                if ($row['status'] == 'Pending') {
                                                                    echo "<span class='text-warning'>Pending</span>";
                                                                } else {
                                                                    echo "<span class='text-success'>Quotation sent to email</span>";
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                            <?php
                                }
                            } else {
                                echo "<div class='col-12 text-center'>No data</div>";
                            }

                            ?>
                        </div>

                        <div class="modal fade" id="request" tabindex="-1" role="dialog" aria-labelledby="requestTitle"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="request">Request Quotation</h5>
                                    </div>
                                    <div class="modal-body mb-4 mt-4">
                                        <div class="row w-100">
                                            <div class="col-8">
                                                <label for="">Title</label><label for="" class="required">*</label>
                                                <input type="text" id="title"
                                                    placeholder="Trip to Hongkong from Philippines" name="title"
                                                    class="form-control" required>
                                                <div class="invalid-feedback">
                                                    Please input your trip title
                                                </div>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col-4 mt-5">
                                                <label for="branch">Branch</label><label for="" class="required">*</label>
                                                <select name="branch" class="form-control" id="branch" required>
                                                    <option selected>Calumpang</option>
                                                    <option>Calamba</option>
                                                </select>
                                            </div>

                                            <div class="col-4 mt-5">
                                                <label for="">Flight Type</label><label for="" class="required">*</label>
                                                <select name="type" id="" class="form-control flight" onchange="loadCountries()">
                                                    <option value="dom">Domestic</option>
                                                    <option value="inter">International</option>
                                                </select>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col-4 mt-5">
                                                <label for="">Origin</label><label for="" class="required">*</label>
                                                <input class="form-control" list="datalistOptions" name="origin" id="origin" placeholder="Type to search origin" required>
                                                <datalist id="datalistOptions" class="origin">
                                                </datalist>
                                                <div class="invalid-feedback">
                                                    Please select your place of origin
                                                </div>
                                            </div>
                                            <div class="col-4  mt-5">
                                                <label for="">Destination</label><label for=""
                                                    class="required">*</label>
                                                <input class="form-control" list="datalistOptions" name="destination" id="destination" placeholder="Type to search destination" required>
                                                <datalist id="datalistOptions" class="origin">
                                                </datalist>
                                                <div class="invalid-feedback">
                                                    Please select your destination
                                                </div>
                                            </div>
                                            <div class="col-4  mt-5">
                                                <label for="date">Date</label><label for="" class="required">*</label>
                                                <input type="date" id="date" placeholder="Departure" name="date"
                                                    class="form-control" required>
                                                <div class="invalid-feedback">
                                                    Please select a valid date
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-5 w-100">
                                            <div class="col-4">
                                                <label for="">Duration</label><label for="" class="required">*</label>
                                                <select name="days" id="" class="form-control">
                                                    <?php
                                                    for ($i = 1; $i <= 100; $i++) {
                                                        echo "
                                                <option>$i days</option>
                                                ";
                                                    }
                                                    ?>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select your trip duration
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <label for="pax">Pax</label><label for="" class="required">*</label>
                                                <input type="number" id="pax" placeholder="1" name="pax"
                                                    class="form-control" required>
                                                <div class="invalid-feedback">
                                                    Please input for how many pax
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <label for="">Email</label><label for="" class="required">*</label>
                                                <input type="email" id="email" placeholder="abcded@gmail.com"
                                                    name="email" class="form-control" required>
                                                <div class="invalid-feedback">
                                                    Please input a valid email address
                                                </div>
                                            </div>
                                            <input type="hidden"
                                                value="<?php echo "$userid"; ?>"
                                                name="userid">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <?php

    if (isset($_GET['submit'])) {
        $title = $_GET['title'];
        $origin = $_GET['origin'];
        $branch = $_GET['branch'];
        $destination = $_GET['destination'];
        $date = $_GET['date'];
        $days = $_GET['days'];
        $pax = $_GET['pax'];
        $email = $_GET['email'];
        $status = 'Pending';
        $userid = $_GET['userid'];

        $query = "INSERT INTO quotation(title, origin, branch, destination, date, days, pax, email, status, userid) 
        VALUES ('$title','$origin', '$branch', '$destination', '$date', '$days', '$pax', '$email', '$status', '$userid')";

        if (mysqli_query($conn, $query)) {
            echo "<script>window.location.href='quotation.php?userid=$userid&alert=1';</script>";
        } else {
            echo mysqli_error($conn);
        }
    }
    ?>

    <script src="js/app.js"></script>
    <script src="js/validation.js"></script>
    <script>
        $(document).ready(function($) {
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    var forms = document.getElementsByClassName('needs-validation');
                    // Loop over them and prevent submission
                    var validation = Array.prototype.filter.call(forms, function(
                        form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        });

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(window.location.search);
        const alert = urlParams.get('alert');

        if (alert == 1) {
            toastr["success"]("Request for quotation sent successfully")

            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": false,
                "preventDuplicates": false,
                "positionClass": "toast-bottom-center",
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }
    </script>
</body>

</html>