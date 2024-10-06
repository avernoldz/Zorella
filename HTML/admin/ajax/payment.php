<?php
include '../../../inc/Include.php';

if (isset($_POST["branch"])) {
    $branch = $_POST["branch"];
    $adminid = $_POST["adminid"];

    $query = "SELECT user.firstname, user.lastname, user.email, payment.*, paymentinfo.*, booking.*, h.amount AS tamount, h.paid_date AS date_paid
    FROM paymentinfo
    INNER JOIN payment ON paymentinfo.payment_id = payment.paymentid
    INNER JOIN booking ON booking.booking_id = payment.booking_id AND booking.booking_type = payment.booking_type
    INNER JOIN user ON user.userid = paymentinfo.userid
    INNER JOIN installmenthistory h ON paymentinfo.paymentinfoid = h.paymentinfoid
    WHERE booking.branch = '$branch'";
    $resPay = mysqli_query($conn, $query);

    if (mysqli_num_rows($resPay) > 0) {
        while ($rows = mysqli_fetch_array($resPay)) {
            $date = date_create("$rows[date_paid]");
            $dateFormat = date_format($date, "M d, Y");
?>

            <a href="full-payment.php?adminid=<?php echo "$adminid" ?>"
                class="populate-quote w-100">
                <div class="row populate-quote">
                    <div class="col-6">
                        <label for="" class="w-700">
                            <?php echo "$rows[firstname] $rows[lastname]" ?><br>
                        </label>
                    </div>
                    <div class="col-4">
                        <p><?php echo "PHP " . number_format($rows['tamount'], 2) ?></p>
                    </div>
                    <div class="col-2">
                        <p class="w-700"><?php echo "$dateFormat" ?></p>
                    </div>
                </div>
            </a>
<?php
        }
    } else {
        echo "<div class='text-center col'> No payments available</div>";
    }
}
