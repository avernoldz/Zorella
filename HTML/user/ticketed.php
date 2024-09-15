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
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
    $active = "Ticketed";
    include "../../inc/Include.php";
    include "side-bar.php";
    ?>
    <div class="overlay">
        <div class="spinner-grow text-light" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>

    <form action="inc/ticket.php" style="width: 100%;" id="ticket" class="needs-validation" method="POST" novalidate>
        <?php
        include "header.php";
        ?>
        <div class="main">
            <div class="row wrap" id="ticketed">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4>Ticketed</h4>
                        </div>
                    </div>

                    <div class="row body">
                        <div class="col-12" style="display:flex; justify-content: flex-end;">
                            <a href="#" class="btn btn-info active ml-4" data-value="Round Trip">Round Trip</a>
                            <a href="#" class="btn btn-info ml-4" data-value="One Way">One Way</a>
                            <a href="#" class="btn btn-info ml-4" data-value="Multi-City">Multi-City</a>
                        </div>

                        <div class="col-4">
                            <label for="branch">Branch</label><label for="" class="required">*</label>
                            <select name="branch" class="form-control" id="branch" required>
                                <option selected>Laguna</option>
                                <option>Metro Manila</option>
                            </select>
                        </div>

                        <div class="col-4">
                            <label for="flighttype">Flight Type</label><label for="" class="required">*</label>
                            <select name="flighttype" class="form-control flight" id="flighttype">
                                <option value="Domestic">Domestic</option>
                                <option value="International">International</option>
                            </select>
                        </div>
                        <div class="row mt-4 wd-100">
                            <div class="col-4">
                                <label for="origin">Origin</label><label for="" class="required">*</label>
                                <input class="form-control" list="datalistOptions" name="origin" id="origin" placeholder="Type to search origin" required>
                                <datalist id="datalistOptions" class="origin">
                                </datalist>
                            </div>
                            <div class="col-4">
                                <label for="destination">Destination</label><label for="" class="required">*</label>
                                <input class="form-control" list="datalistOptions" name="destination" id="destination" placeholder="Type to search destination" required>
                                <datalist id="datalistOptions" class="origin">
                                </datalist>
                            </div>
                            <div class="col-4">
                                <label for="classtype">Class Type</label><label for="" class="required">*</label>
                                <select name="classtype" id="classtype" class="form-control">
                                    <option selected>Economy</option>
                                    <option>Premium Economy</option>
                                    <option>Business</option>
                                    <option>First Class</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4 wd-100">
                            <div class="col-4">
                                <label for="departure">Departure</label><label for="" class="required">*</label>
                                <input type="date" id="departure" placeholder="Departure" name="departure"
                                    class="form-control" required>
                            </div>
                            <div class="col-4 arrival-container">
                                <label for="arrival">Arrival</label><label for="" class="required">*</label>
                                <input type="date" id="arrival" placeholder="Arrival" name="arrival"
                                    class="form-control" required>
                            </div>
                            <div class="col-4">
                                <label for="">Direct Flight</label><label for="" class="required">*</label><br>
                                <label for="directflight" class="w-500">Yes &nbsp;&nbsp;</label>
                                <input type="radio" name="direct" id="directflight" value="Yes" required>
                                <label for="directflight2" class="ml-5 w-500">No &nbsp;&nbsp;</label>
                                <input type="radio" name="direct" id="directflight2" value="No" required>
                                <div class="invalid-feedback">
                                    Please select direct flight type
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4 wd-100">
                            <div class="col-2">
                                <label for="adult">Adult</label><label for="" class="required">*</label><small> (12+ years
                                    old)</small>

                                <input type="number" class="form-control" id="adult" placeholder="" maxlength="12"
                                    name="adult" required>
                            </div>

                            <div class="col-2">
                                <label for="child">Child</label><small> (2 - 11 years
                                    old)</small>
                                <input type="number" class="form-control" id="child" placeholder="" min="0"
                                    maxlength="12" name="child">
                            </div>

                            <div class="col-2">
                                <label for="infant">Infant</label><small> (1 - 23 months
                                    old)</small>
                                <input type="number" class="form-control" id="infant" placeholder="" min="0"
                                    maxlength="12" name="infant">
                            </div>

                            <div class="col-2">
                                <label for="senior">Senior</label><small> (60+ years
                                    old)</small>
                                <input type="number" class="form-control" id="senior" placeholder="" min="0"
                                    maxlength="12" name="senior">
                            </div>
                        </div>

                        <div class="col-4 mt-4">
                            <label for="airline">Preferred Airline</label>
                            <select name="airline" id="airline" class="form-control">
                                <option selected>Philippine Airlines</option>
                                <option>Cebu Pacific</option>
                                <option>Philippines AirAsia</option>
                                <option>Royal Air Philippines</option>
                                <option>None</option>
                            </select>
                        </div>

                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <input type="hidden" name="source" value="Ticketed">
                        <input type="hidden" name="tickettype" id="tickettype" value="Round Trip">
                        <div class="col-12 mt-4" style="display:flex; justify-content: flex-end;">
                            <button id="next" class="btn btn-primary pl-5 pr-5 btn-next" type="button" name="ticketd">Next</button>
                            <button style="display:none" type="submit" name="ticket" id="submit">Next</button>
                        </div>
                    </div>
                </div>
            </div>
    </form>
    </div>

    <script src="js/app.js"></script>
    <script src="js/validation.js"></script>

</body>

</html>