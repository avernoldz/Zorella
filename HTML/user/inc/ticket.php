<?php
session_start();
include '../../../inc/Include.php';

if (isset($_POST['ticket'])) {

    $branch = isset($_POST['branch']) ? mysqli_real_escape_string($conn, $_POST['branch']) : '';
    $flighttype = isset($_POST['flighttype']) ? mysqli_real_escape_string($conn, $_POST['flighttype']) : '';
    $origin = isset($_POST['origin']) ? mysqli_real_escape_string($conn, $_POST['origin']) : '';
    $ticket = isset($_POST['ticket']) ? mysqli_real_escape_string($conn, $_POST['ticket']) : '';
    $destination = isset($_POST['destination']) ? mysqli_real_escape_string($conn, $_POST['destination']) : '';
    $classtype = isset($_POST['classtype']) ? mysqli_real_escape_string($conn, $_POST['classtype']) : '';
    $departure = isset($_POST['departure']) ? mysqli_real_escape_string($conn, $_POST['departure']) : '';
    $arrival = isset($_POST['arrival']) ? mysqli_real_escape_string($conn, $_POST['arrival']) : null; // Can be null
    $hotel = isset($_POST['hotel']) ? mysqli_real_escape_string($conn, $_POST['hotel']) : null; // Can be null
    $attraction = isset($_POST['attraction']) ? mysqli_real_escape_string($conn, $_POST['attraction']) : null; // Can be null
    $direct = isset($_POST['direct']) ? mysqli_real_escape_string($conn, $_POST['direct']) : '';
    $airline = isset($_POST['airline']) ? mysqli_real_escape_string($conn, $_POST['airline']) : '';
    $source = isset($_POST['source']) ? mysqli_real_escape_string($conn, $_POST['source']) : '';
    $tickettype = isset($_POST['tickettype']) ? mysqli_real_escape_string($conn, $_POST['tickettype']) : '';
    $userid = isset($_POST['userid']) ? mysqli_real_escape_string($conn, $_POST['userid']) : '';
    $status = "Pending";

    $fields = ['adult', 'child', 'infant', 'senior'];

    $_SESSION['booking_type'] = $source;
    foreach ($fields as $field) {
        $$field = isset($_POST[$field]) ? (int)$_POST[$field] : 0;
    }

    $lastInsertedId = null;

    $insert = "INSERT INTO `ticket`(`branch`, `ticket`, `flighttype`, `origin`, `destination`, `classtype`, `departure`, `arrival`, `directflight`, `adult`, `child`, `infant`, `senior`, `airline`, `tickettype`, `status`, `userid`) 
    VALUES ('$branch', '$source', '$flighttype','$origin','$destination','$classtype','$departure','$arrival','$direct','$adult','$child','$infant','$senior','$airline', '$tickettype', '$status', '$userid')";

    if (mysqli_query($conn, $insert)) {
        $_SESSION['total'] = $adult + $child + $infant + $senior;
        $lastInsertedId = mysqli_insert_id($conn);
        $_SESSION['booking_id'] = $lastInsertedId;
    } else {
        echo mysqli_error($conn);
    }

    $insert3 = "INSERT INTO `booking` (`booking_type`, `booking_id`, `status`, `branch`, `userid` ) 
    VALUES ('$source','$lastInsertedId', '$status', '$branch', '$userid')";

    if (!mysqli_query($conn, $insert3)) {
        echo mysqli_error($conn);
    }

    if ($source == 'Customize') {

        $insert2 = "INSERT INTO `hotel`( `hotel`, `attraction`, `ticketid`, `userid`) 
        VALUES ('$hotel', '$attraction', '$lastInsertedId','$userid')";

        if (mysqli_query($conn, $insert2)) {
            header("Location: ../personal-info.php?userid=$userid&ticketid=$lastInsertedId");
            exit();
        } else {
            echo mysqli_error($conn);
        }
    } else {
        header("Location: ../personal-info.php?userid=$userid&ticketid=$lastInsertedId");
        exit();
    }


?>

    <div class="row body">
        <div class="row wd-100">
            <div class="col-4">
                <label for="" style="font-size: 20px;">Applicant
                    <?php $b = $x + 1;
                    echo "$b" ?></label>
            </div>
        </div>
        <div class="row mt-2 wd-100">

            <div class="col-4">
                <label for="">Mobile Number</label><label for="" class="required">*</label>
                <input type="text" class="form-control" placeholder="0912312324" maxlength="12" name="number<?php echo "[$x]" ?>" required>
            </div>
            <div class="col-4">
                <label for="">Email Address</label><label for="" class="required">*</label>
                <input type="email" class="form-control" placeholder="juandelacruz@gmail.com" name="email<?php echo "[$x]" ?>" required>
            </div>
            <div class="col-4">
                <label for="">Confirm Email Address</label><label for="" class="required">*</label>
                <input type="email" class="form-control" placeholder="juandelacruz@gmail.com" required name="confirm-email">
            </div>
        </div>

        <div class="row mt-5 wd-100">
            <div class="col-4">
                <label for="">Last Name</label><label for="" class="required">*</label>
                <input type="text" class="form-control" placeholder="Dela Cruz" name="lastname<?php echo "[$x]" ?>" required>
            </div>
            <div class="col-4">
                <label for="">First Name</label><label for="" class="required">*</label>
                <input type="text" class="form-control" placeholder="Juan" name="firstname<?php echo "[$x]" ?>" required>
            </div>
            <div class="col-4">
                <label for="">Middle Name</label><label for="" class="required">*</label>
                <input type="text" class="form-control" placeholder="Bertho" name="middlename<?php echo "[$x]" ?>" required>
            </div>
        </div>

        <div class="row mt-5 wd-100">
            <div class="col-4">
                <label for="age">Age</label>
                <select name="age<?php echo "[$x]" ?>" id="age" class="form-control">
                    <?php
                    for ($i = 1; $i <= 100; $i++) {
                        echo "
                                                <option>$i</option>
                                                ";
                    }
                    ?>
                </select>
            </div>
            <div class="col-4">
                <label for="">Date of Birth</label><label for="" class="required">*</label>
                <input type="date" name="bdate<?php echo "[$x]" ?>" class="form-control" required>
            </div>
            <div class="col-4">
                <label for="">Sex</label><label for="" class="required">*</label><br>
                <input type="radio" name="sex<?php echo "[$x]" ?>" value="male" required>
                <label for="" class="ml-2 w-500">Male</label>
                <input type="radio" name="sex<?php echo "[$x]" ?>" value="female" class="ml-5">
                <label for="" class="ml-2 w-500">Female</label>
            </div>
        </div>

        <div class="row mt-5 wd-100">
            <div class="col-4">
                <label for="civil">Civil Status</label><label for="" class="required">*</label>
                <select name="civil<?php echo "[$x]" ?>" id="civil" class="form-control">
                    <option value="">Single</option>
                    <option value="">Married</option>
                    <option value="">Separated</option>
                    <option value="">Divorce</option>
                    <option value="">Widowed</option>
                </select>
            </div>
            <div class="col-8">
                <label for="">Complete Address</label><label for="" class="required">*</label>
                <input type="text" name="address<?php echo "[$x]" ?>" class="form-control" required
                    placeholder="123 Street Purok 2 Brgy. Bagumbayan, Pasay City, Metro Manila">
            </div>
        </div>


        <div class="row mt-5 wd-100">
            <div class="col-4">
                <div class="form-group">
                    <label for="passport">Passport</label><label for="" class="required">*</label>
                    <input type="file" class="form-control-file" id="passport" name="passport<?php echo "[$x]" ?>" required>
                </div>
            </div>

            <div class="col-4">
                <div class="form-group">
                    <label for="id">Valid ID</label><label for="" class="required">*</label>
                    <input type="file" class="form-control-file" id="id" name="valid-id<?php echo "[$x]" ?>" required>
                </div>
            </div>
        </div>
    </div>

<?php

}
