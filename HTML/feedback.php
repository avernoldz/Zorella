<?php
session_start();
session_regenerate_id();

if(!$_SESSION['userid']) {
    header("Location:index.php?login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../Connections/cdn.php"?>
    <?php include "../CSS/links.php"?>
    <title>Home</title>
    <style>
        .main .col.card .row {
            align-items: center;
        }

        .main .row.body .col {}

        .wd-100 {
            width: 100%;
        }

        #terms,
        #personal-info,
        #payment {
            display: none;
        }


        .rate {
            float: left;
            height: 46px;
            padding: 0 10px;
            display: flex;
            flex-wrap: wrap;
            flex-direction: row-reverse;
            justify-content: center;
        }

        .rate:not(:checked)>input {
            position: absolute;
            top: -9999px;
        }

        .rate:not(:checked)>label {
            float: right;
            width: 1em;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            font-size: 30px;
            color: #ccc;
        }

        .rate:not(:checked)>label:before {
            content: '★ ';
        }

        .rate>input:checked~label {
            color: #ffc700;
        }

        .rate:not(:checked)>label:hover,
        .rate:not(:checked)>label:hover~label {
            color: #deb217;
        }

        .rate>input:checked+label:hover,
        .rate>input:checked+label:hover~label,
        .rate>input:checked~label:hover,
        .rate>input:checked~label:hover~label,
        .rate>label:hover~input:checked~label {
            color: #c59b08;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
$active = "Feedback";
include "../Connections/Include.php";
include "side-bar.php";

?>
    <div class="main">
        <!-- <div class="row">
            <div class="col head">
                <h1><i class="fa-solid fa-house fa-fw"></i>&nbsp;&nbsp;Ticketed</h1>
            </div>
        </div> -->
        <form action="" style="width: 100%;" class="needs-validation" method="GET" novalidate>
            <?php include "header.php";?>
            <input type="hidden" value="<?php echo "$userid";?>"
                name="userid">

            <div class="row wrap" id="ticketed">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4><i class="fa-solid fa-plane"></i> Feedback and Ratings</h4>
                        </div>
                    </div>

                    <div class="row body">
                        <div class="col-12 mt-5">
                            <div class="form-group">
                                <label for="feedback">Write your comments and recommendations</label>
                                <textarea class="form-control" id="feedback" rows="6" name="feedback" required
                                    placeholder="Aliquam facilisis sapien nec diam eleifend sagittis. Duis commodo ullamcorper sapien, sit amet ultrices nibh placerat vel. Vestibulum fringilla mollis quam, ut malesuada velit dictum id. Mauris congue tempus elit, ac pretium tellus iaculis eu. Vestibulum eget ante viverra urna scelerisque mattis. Nullam venenatis augue mauris, sit amet feugiat lorem pharetra non. Nunc efficitur commodo mattis."></textarea>
                                <div class="invalid-feedback">
                                    Please write your comments and recommendations
                                </div>
                            </div>

                        </div>

                        <div class="col-12 rate">
                            <input type="radio" id="star5" name="rate" value="5" required />
                            <label for="star5" title="text">5 stars</label>
                            <input type="radio" id="star4" name="rate" value="4" required />
                            <label for="star4" title="text">4 stars</label>
                            <input type="radio" id="star3" name="rate" value="3" required />
                            <label for="star3" title="text">3 stars</label>
                            <input type="radio" id="star2" name="rate" value="2" required />
                            <label for="star2" title="text">2 stars</label>
                            <input type="radio" id="star1" name="rate" value="1" required />
                            <label for="star1" title="text">1 star</label>
                            <div class="invalid-feedback">
                                Please select your ratings
                            </div>
                        </div>

                        <input type="hidden"
                            value="<?php echo "$userid";?>"
                            name="userid">
                        <div class="col-12 mt-4" style="display:flex; justify-content: flex-end;">
                            <button type="submit" name="submit"
                                class="btn btn-primary pl-5 pr-5 btn-next">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php
        if(isset($_GET['submit'])) {
            $feedback = $_GET['feedback'];
            $rate = $_GET['rate'];
            $userid = $_GET['userid'];

            $query = "INSERT INTO ratings (feedback, ratings, userid) 
            VALUES('$feedback', '$rate', '$userid')";

            if(mysqli_query($conn, $query)) {
                echo "<script>window.location.href='feedback.php?userid=$userid&alert=1';</script>";
            } else {
                echo mysqli_error($conn);
            }

        }
?>
    <script>
        $(document).ready(function($) {
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(window.location.search);
            const alert = urlParams.get('alert');

            if (alert == 1) {
                toastr["success"]("Feedback and Ratings sent successfully")

                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
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
    </script>
</body>

</html>