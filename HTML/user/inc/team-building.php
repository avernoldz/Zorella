<?php
session_start();
include '../../../inc/Include.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['educational'])) {
        // Retrieve and escape POST data
        $sdate = isset($_POST['sdate']) ? mysqli_real_escape_string($conn, $_POST['sdate']) : '';
        $edate = isset($_POST['edate']) ? mysqli_real_escape_string($conn, $_POST['edate']) : '';
        $pax = isset($_POST['pax']) ? mysqli_real_escape_string($conn, $_POST['pax']) : '';
        $branch = isset($_POST['branch']) ? mysqli_real_escape_string($conn, $_POST['branch']) : '';
        $hotel = isset($_POST['hotel']) ? mysqli_real_escape_string($conn, $_POST['hotel']) : null;
        $attraction = isset($_POST['attraction']) ? mysqli_real_escape_string($conn, $_POST['attraction']) : null;
        $userid = $_SESSION['userid'];
        $status = "Pending";
        $booking_type = "Educational";
        $_SESSION['booking_type'] = $booking_type;

        $lastInsertedId = null;

        // Insert data into database
        $insert = "INSERT INTO `educational`(`sdate`, `edate`, `pax`, `hotel`, `attraction`, `status`, `userid`) 
            VALUES ('$sdate', '$edate', '$pax', '$hotel', '$attraction', '$status', '$userid')";

        if (mysqli_query($conn, $insert)) {
            $_SESSION['total'] = 1;
            $lastInsertedId = mysqli_insert_id($conn);
            $_SESSION['booking_id'] = $lastInsertedId;

            $insert3 = "INSERT INTO `booking` (`booking_type`, `booking_id`, `status`, `branch`, `userid` ) 
            VALUES ('$booking_type','$lastInsertedId', '$status', '$branch', '$userid')";

            if (!mysqli_query($conn, $insert3)) {
                echo mysqli_error($conn);
            }

?>
            <div class="row mt-4 wd-100">
                <div class="col-4">
                    <label for="">Mobile Number</label><label for="" class="required">*</label>
                    <input type="text" class="form-control" placeholder="0912312324" maxlength="12" name="number" required>
                </div>
                <div class="col-4">
                    <label for="">Email Address</label><label for="" class="required">*</label>
                    <input type="email" class="form-control" placeholder="juandelacruz@gmail.com" name="email" required>
                </div>
                <div class="col-4">
                    <label for="">Confirm Email Address</label><label for="" class="required">*</label>
                    <input type="email" class="form-control" placeholder="juandelacruz@gmail.com" required name="confirm-email">
                    <div class="invalid-feedback">
                        Email addresses do not match.
                    </div>
                </div>
            </div>

            <div class="row mt-4 wd-100">
                <div class="col-4">
                    <label for="">Last Name</label><label for="" class="required">*</label>
                    <input type="text" class="form-control" placeholder="Dela Cruz" name="lastname" required>
                </div>
                <div class="col-4">
                    <label for="">First Name</label><label for="" class="required">*</label>
                    <input type="text" class="form-control" placeholder="Juan" name="firstname" required>
                </div>
                <div class="col-4">
                    <label for="">Middle Name</label><label for="" class="required">*</label>
                    <input type="text" class="form-control" placeholder="Bertho" name="middlename">
                </div>
            </div>

            <div class="row mt-4 wd-100">
                <div class="col-4">
                    <label for="">Position</label><label for="" class="required">*</label>
                    <input type="text" name="position" class="form-control" required>
                </div>
                <div class="col-4">
                    <label for="">Pickup Location</label><label for="" class="required">*</label>
                    <input type="text" name="pickup" class="form-control" required>
                </div>
            </div>
<?php
        } else {
            echo "Error inserting into database: " . mysqli_error($conn);
        }
    } else {
        echo "No POST data or educational key not set.";
    }
} else {
    echo "Invalid request method.";
}
?>