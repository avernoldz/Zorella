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

        #terms,
        #personal-info,
        #payment {
            display: none;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
    $active = "Ticketed";
    include "../../inc/Include.php";
    include "side-bar.php";
    ?>
    <form action="ticket.php" style="width: 100%;" class="needs-validation" method="GET" novalidate>
        <?php
        include "header.php";
        ?>
        <div class="main">
            <input type="hidden" value="<?php echo "$userid"; ?>"
                name="userid">
            <input type="hidden" value="round-trip" name="trip">
            <div class="row wrap" id="ticketed">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4><i class="fa-solid fa-plane"></i> Ticketed</h4>
                        </div>
                    </div>

                    <div class="row body">
                        <div class="col-12" style="display:flex; justify-content: flex-end;">
                            <a href="#" class="btn btn-info ml-4">Round Trip</a>
                            <a href="one-way.php?userid=<?php echo "$userid" ?>"
                                class="btn btn-light ml-4">One Way</a>
                            <a href="multi-city.php?userid=<?php echo "$userid" ?>"
                                class="btn btn-light ml-4">Multi-City</a>
                        </div>

                        <div class="col-4">
                            <label for="branch">Branch</label><label for="" class="required">*</label>
                            <select name="branch" class="form-control" id="branch" required>
                                <option>Select Branch</option>
                                <option>Calumpang</option>
                                <option>Calumpang</option>
                            </select>
                        </div>

                        <div class="col-4">
                            <label for="">Flight Type</label><label for="" class="required">*</label>
                            <select name="type" id="" class="form-control">
                                <option selected>Domestic</option>
                                <option>International</option>
                            </select>
                        </div>
                        <div class="row mt-5 wd-100">
                            <div class="col-4">
                                <label for="">Origin</label><label for="" class="required">*</label>
                                <select name="origin" id="" class="form-control">
                                    <option selected>Philippine</option>
                                    <option>Hongkong</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="">Destination</label><label for="" class="required">*</label>
                                <select name="destination" id="" class="form-control">
                                    <option selected>Boracay</option>
                                    <option>Siargao</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="">Class Type</label><label for="" class="required">*</label>
                                <select name="class" id="" class="form-control">
                                    <option selected>Economy</option>
                                    <option>Premium Economy</option>
                                    <option>Business</option>
                                    <option>First Class</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-5 wd-100">
                            <div class="col-4">
                                <label for="departure-date">Departure</label><label for="" class="required">*</label>
                                <input type="date" id="departure-date" placeholder="Departure" name="departure"
                                    class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="arrival-date">Arrival</label><label for="" class="required">*</label>
                                <input type="date" id="arrival-date" placeholder="Arrival" name="arrival"
                                    class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="">Direct Flight</label><label for="" class="required">*</label><br>
                                <label for="" class="w-500">Yes &nbsp;&nbsp;</label><input type="radio" name="direct">
                                <label for="" class="ml-5 w-500">No &nbsp;&nbsp;</label><input type="radio"
                                    name="direct">
                            </div>
                        </div>

                        <div class="row mt-5 wd-100">
                            <div class="col-2">
                                <label for="">Adult</label><label for="" class="required">*</label><small> (12+ years
                                    old)</small>

                                <input type="number" class="form-control" id="adult" placeholder="1" maxlength="12"
                                    name="adult" required>
                            </div>

                            <div class="col-2">
                                <label for="">Child</label><small> (2 - 11 years
                                    old)</small>
                                <input type="number" class="form-control" id="child" placeholder="1" min="0"
                                    maxlength="12" name="child">
                            </div>

                            <div class="col-2">
                                <label for="">Infant</label><small> (1 - 23 months
                                    old)</small>
                                <input type="number" class="form-control" id="infant" placeholder="1" min="0"
                                    maxlength="12" name="infant">
                            </div>

                            <div class="col-2">
                                <label for="">Senior</label><small> (60+ years
                                    old)</small>
                                <input type="number" class="form-control" id="senior" placeholder="1" min="0"
                                    maxlength="12" name="senior">
                            </div>
                        </div>

                        <div class="col-4 mt-5">
                            <label for="">Preferred Airline</label>
                            <select name="airline" id="" class="form-control">
                                <option value="" selected>Philippine Airlines</option>
                                <option value="">Cebu Pacific</option>
                                <option value="">None</option>
                            </select>
                        </div>
                        <div class="col-12 mt-5" style="display:flex; justify-content: flex-end;">
                            <button id="next-ticket" class="btn btn-primary pl-5 pr-5 btn-next">Next</button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row wrap" id="personal-info">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4><i class="fa-solid fa-plane"></i> Personal Information</h4>
                        </div>
                    </div>

                    <div class="row info">
                        <div class="row data">

                        </div>

                        <div class="col-12 mt-5" style="display:flex; justify-content: flex-end;">
                            <button id="previous-info"
                                class="btn btn-outline-secondary mr-3 pl-5 pr-5 btn-prev">Previous</button>
                            <button id="next-info" class="btn btn-primary pl-5 pr-5 btn-next">Next</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row wrap" id="terms">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4><i class="fa-solid fa-plane"></i> Terms and Conditions</h4>
                        </div>
                    </div>

                    <div class="row body">
                        <div class="col-12" style="display:flex; justify-content: center;text-align:center;">
                            <label>Terms and Conditions</label>
                        </div>

                        <div class="col-12">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce vitae sagittis mi.
                                Maecenas venenatis quam porttitor ultricies bibendum. Nam condimentum nisi eget mi
                                facilisis, a viverra elit sodales. Integer lobortis venenatis nunc, id fermentum nulla
                                vulputate nec. Pellentesque sapien metus, luctus eu rutrum id, lacinia at neque. Sed
                                malesuada purus nec ligula mattis, in tristique nunc pulvinar. Donec vestibulum elit sit
                                amet cursus tincidunt. Maecenas molestie ex sit amet neque semper scelerisque.</p>
                            <p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
                                himenaeos. Mauris sed consectetur nunc, et sodales orci. Praesent ultrices ex in ex
                                malesuada tempus. Integer a sodales nisi. Nulla et mi et dolor sodales vehicula ac vitae
                                magna. Praesent eget efficitur magna. Quisque efficitur est arcu, in blandit velit
                                lobortis at. Nulla id tempor lacus. Sed semper dui est, quis sagittis lorem consequat
                                gravida. Cras efficitur, est blandit imperdiet euismod, arcu lectus congue odio, eu
                                tincidunt tortor nisl a massa. Praesent sapien diam, congue vitae massa vel, interdum
                                rhoncus elit. Nulla massa purus, tristique quis luctus in, maximus sit amet justo.
                                Pellentesque ultrices mattis erat, quis eleifend nibh sollicitudin varius.</p>
                            <p>Sed turpis elit, ornare vitae sapien sit amet, tempus gravida ipsum. Vestibulum ante
                                ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Etiam lobortis
                                risus dui, nec gravida tortor mattis eget. Proin dignissim eros ac sem posuere, in
                                rhoncus augue dignissim. Maecenas convallis eros in nulla tempor cursus. Ut nec erat
                                risus. Praesent ut tristique ex. Ut commodo at purus at volutpat. Morbi egestas viverra
                                massa, elementum porta tortor congue vel. Cras tristique magna non magna pulvinar
                                lobortis sed quis nulla. Cras purus eros, mattis non scelerisque vel, sollicitudin vitae
                                enim. Maecenas quis magna id est varius euismod vitae ac dui. In in lobortis elit. Fusce
                                varius lorem sem, eget placerat dui commodo in. Etiam eu magna libero. Ut et ligula non
                                erat posuere porttitor eget eget elit.</p>
                            <p>Aliquam scelerisque sapien sed dui tempus laoreet. Duis aliquam mollis nulla, id
                                dignissim eros efficitur ac. Quisque malesuada mi non diam maximus malesuada.
                                Suspendisse laoreet nibh a sem rhoncus, quis hendrerit quam condimentum. Etiam finibus,
                                risus nec rhoncus aliquet, mi purus sollicitudin leo, ullamcorper eleifend augue est
                                quis erat. Vestibulum eu magna massa. Sed fringilla tortor ornare velit interdum, at
                                tristique lacus egestas. Pellentesque aliquet, tellus a sagittis venenatis, enim sem
                                sollicitudin nulla, ut lacinia diam nulla fringilla massa. Sed sed accumsan ex. Nam
                                vitae justo vestibulum, vehicula purus vel, accumsan nunc. Vestibulum maximus dapibus mi
                                vel pellentesque. Donec eget magna aliquam, iaculis justo nec, gravida metus. Aenean ac
                                lobortis tortor.</p>
                            <p>Proin a nunc in massa molestie ornare. Morbi quis magna sapien. Morbi mauris nulla,
                                malesuada sed tortor placerat, ultricies mattis diam. Praesent in libero in odio sodales
                                sollicitudin. Duis non enim consequat, scelerisque ex vel, congue nisl. Aenean sit amet
                                elit eros. Nulla vulputate ligula vehicula, tincidunt eros vel, tristique ipsum. Cras
                                lectus arcu, congue nec neque vitae, dictum euismod risus. Pellentesque mollis neque a
                                orci auctor pellentesque.</p>
                            <p>Nullam vitae est orci. Fusce ornare ante id porta faucibus. Vivamus condimentum libero ut
                                leo pulvinar, eget euismod mauris vestibulum. Mauris odio libero, suscipit vitae
                                imperdiet eget, molestie id ante. Sed molestie orci non egestas rhoncus. Duis suscipit
                                tincidunt dolor. Morbi in blandit enim, a pretium erat. In libero risus, consequat
                                maximus vestibulum ac, consequat quis risus. Donec venenatis interdum leo nec finibus.
                                Aenean auctor luctus lacus, ac viverra felis consectetur bibendum. Maecenas tempus
                                iaculis aliquam. Sed lobortis rutrum nunc, sed varius dolor facilisis in. Aliquam rutrum
                                dolor elit, vel suscipit lacus condimentum sed. Nulla vel lectus fermentum, pretium
                                tellus vel, pulvinar leo. Duis fringilla tellus in augue suscipit auctor.</p>
                            <p>Maecenas lacinia est vitae ligula facilisis, sed mollis elit vestibulum. Donec vehicula
                                sodales velit eget ultricies. Suspendisse faucibus, nisl hendrerit ultricies rhoncus,
                                ante nisl luctus nibh, ut consectetur erat nibh convallis dolor. Lorem ipsum dolor sit
                                amet, consectetur adipiscing elit. Nulla ornare auctor magna, ac sagittis nibh ultricies
                                et. Integer risus dui, pretium non efficitur ut, condimentum nec turpis. Fusce convallis
                                dignissim scelerisque. Mauris eu lacinia tellus. Nulla facilisi. Sed sit amet lectus
                                lectus. Aliquam hendrerit ultricies sapien nec scelerisque. Nullam vestibulum, erat
                                vitae ullamcorper consequat, metus mauris efficitur nulla, tristique porta sem massa
                                sollicitudin arcu. Phasellus neque augue, pellentesque in auctor ut, scelerisque sed
                                dui.</p>
                            <p>Proin ullamcorper nisl ac nulla elementum placerat. Sed massa mauris, fringilla ac
                                rhoncus sed, rhoncus non quam. Vivamus tincidunt egestas blandit. Etiam at neque
                                bibendum justo molestie egestas vel sit amet nisl. Curabitur sem urna, volutpat a
                                consequat accumsan, semper quis tellus. Suspendisse hendrerit ligula massa, sit amet
                                aliquam ligula pharetra eget. Praesent vel justo eget lectus mattis rhoncus. Class
                                aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
                                Morbi at vestibulum magna. Etiam vitae mauris a felis vestibulum feugiat vel feugiat
                                metus.</p>
                            <p>Aliquam facilisis sapien nec diam eleifend sagittis. Duis commodo ullamcorper sapien, sit
                                amet ultrices nibh placerat vel. Vestibulum fringilla mollis quam, ut malesuada velit
                                dictum id. Mauris congue tempus elit, ac pretium tellus iaculis eu. Vestibulum eget ante
                                viverra urna scelerisque mattis. Nullam venenatis augue mauris, sit amet feugiat lorem
                                pharetra non. Nunc efficitur commodo mattis.</p>
                            <p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
                                himenaeos. Nulla nisl eros, convallis vel gravida vitae, blandit sed neque. Vestibulum
                                molestie sapien et vehicula fermentum. Vivamus maximus maximus mauris ac aliquam. Nam
                                mattis auctor quam eget aliquet. Mauris tempor id ante vel aliquam. Sed leo tortor,
                                interdum id sodales a, malesuada ac ligula.</p>
                        </div>
                        <div class="col-12 mt-5">
                            <input type="checkbox" name="conditions" value="yes" id="conditions">
                            <label for="" class="w-700">I agree to the terms and conditions.</label>
                        </div>

                        <div class="col-12 mt-5" style="display:flex; justify-content: flex-end;">
                            <button id="previous-terms" type="button"
                                class="btn btn-outline-secondary mr-3 pl-5 pr-5 btn-prev">Previous</button>
                            <button id="next-terms" class="btn btn-primary pl-5 pr-5 btn-next">Next</button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row wrap" id="payment">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4><i class="fa-solid fa-plane"></i> Payment</h4>
                        </div>
                    </div>

                    <div class="row body">

                        <div class="row wd-100">
                            <div class="col-12">
                                <h4>Please choose payment method</h4>
                            </div>
                        </div>

                        <div class="row mt-4 wd-50">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" style="display:block;" type="checkbox"
                                        name="payment-type" id="full" value="Full Payment">
                                    <label for="full" class="ml-5"><i class="fa-solid fa-wallet mr-2"></i>
                                        &nbsp;Full Payment</label>
                                </div>
                                <div class="form-check">
                                    <select name="" id="" class="form-control">
                                        <option value="">--</option>
                                        <option value="">Installments</option>
                                        <option value="">Monthly</option>
                                        <option value="">Quarterly</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 wd-100">
                            <div class="col-12 ">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment" id="gcash" value="gcash"
                                        required>
                                    <label for="gcash" class="via"><i class="fa-solid fa-wallet mr-2"></i>
                                        &nbsp;Gcash</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment" id="paymaya"
                                        value="paymaya" required>
                                    <label for="paymaya" class="via"><i class="fa-solid fa-wallet mr-2"></i>
                                        &nbsp;Paymaya</label>
                                </div>
                            </div>

                            <div class="col-12 ">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment" id="credit"
                                        value="credit" required>
                                    <label for="credit" class="via"><i class="fa-solid fa-credit-card mr-2"></i>
                                        &nbsp;Debit/Credit Card</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-5" style="display:flex; justify-content: flex-end;">
                            <button id="previous-info"
                                class="btn btn-outline-secondary mr-3 pl-5 pr-5 btn-prev">Previous</button>
                            <button id="submit" class="btn btn-primary pl-5 pr-5 btn-next">Next</button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
    </div>

    <script>
        $(document).ready(function($) {

            $(function() {
                $("input[name='number']").on('input', function(e) {
                    $(this).val($(this).val().replace(/[^0-9]/g, ''));
                });
            });

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


            let currentCardIndex = 0;
            const cardList = $(".wrap");

            goNextCard = function() {
                currentCardIndex++;
                if (currentCardIndex == cardList.length) {
                    Swal.fire({
                        title: "Error",
                        text: "Please fill out all required(*) forms.",
                        icon: "error"
                    });
                } else {
                    const $card = $(this).closest('.row').parent().closest('.row').hide();
                    $card.next().show();
                    console.log($card);
                }
            };

            goPrevCard = function() {
                currentCardIndex--;
                if (currentCardIndex < 0) {
                    alert("last card, you can submit NOW");
                    // your submit code of your form etc..
                } else {
                    const $card = $(this).closest('.row').parent().closest('.row').hide();
                    $card.prev().show();
                    console.log($card);
                }
            };

            $(".btn-next").click(goNextCard);
            $(".btn-prev").click(goPrevCard);

            //Disable submit until checkbox is checked
            $(function() {
                $('#conditions').on('change', function() {
                    $('#next-terms').prop("disabled", !this
                        .checked); //true: disabled, false: enabled
                }).trigger('change'); //page load trigger event
            });

            // $('#submit').click(function() {
            //     if ($('form')[0].checkValidity()) {
            //         alert('Success');
            //     } else {

            //         $('form')[0].reportValidity();
            // Swal.fire({
            //     title: "Error",
            //     text: "Please fill out all the forms that is required. ",
            //     icon: "error"
            // });
            //     }
            //     // $('form').unbind();
            // });
        });


        //Pass VALUE TOTAL PASSENGER

        $('#next-ticket').click(function() {
            var adult = $('#adult').val(); // What i want to pass to php
            var child = $('#child').val(); // What i want to pass to php
            var infant = $('#infant').val(); // What i want to pass to php
            var senior = $('#senior').val(); // What i want to pass to php

            $.ajax({
                type: 'post', // the method (could be GET btw)
                url: '../Faculty AJAX/total-passenger.php', // The file where my php code is
                data: {
                    adult: adult, // all variables i want to pass. In this case, only one.
                    child: child, // all variables i want to pass. In this case, only one.
                    infant: infant, // all variables i want to pass. In this case, only one.
                    senior: senior // all variables i want to pass. In this case, only one.
                },
                success: function(data) { // in case of success get the output, i named data
                    $('.row.data').html(data); // do something with the output, like an alert
                }
            });

        })
    </script>
</body>

</html>