<?php
include '../../../inc/Include.php';

if (isset($_POST["branch"])) {
    $branch = $_POST["branch"];
    $adminid = $_POST["adminid"];

    $quotation = "SELECT *, quotation.email as email2, DATE(date) AS date FROM quotation INNER JOIN user ON quotation.userid = user.userid WHERE quotation.branch = '$branch' ORDER BY quotation.quotationid DESC";
    $quotationResults = mysqli_query($conn, $quotation);

    if (mysqli_num_rows($quotationResults) > 0) {
        while ($quotationRows = mysqli_fetch_array($quotationResults)) {
            $date = date_create("$quotationRows[date]");
            $dateFormat = date_format($date, "F d, Y");
?>

            <a href="view-quotation.php?adminid=<?php echo "$adminid&qoute=$quotationRows[quotationid]" ?>"
                class="populate-quote w-100">
                <div class="row populate-quote">
                    <div class="col-3">
                        <small><label for="" class="w-700">
                                <?php echo "$quotationRows[firstname] $quotationRows[lastname]" ?><br>
                            </label>
                        </small>
                    </div>
                    <div class="col-9 ellip">
                        <small>
                            <span><?php echo "$quotationRows[title] - From $quotationRows[origin] to $quotationRows[destination], $quotationRows[pax] pax for $quotationRows[days], Travel Date: $dateFormat" ?></span>
                        </small>
                    </div>
                </div>
            </a>
<?php
        }
    } else {
        echo "<div class='text-center col'> No data found</div>";
    }
}
