<?php
session_start();
session_regenerate_id();

if (!$_SESSION['adminid']) {
    header("Location:index.php?Login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../../inc/cdn.php" ?>
    <?php include "../../CSS/links.php" ?>
    <link rel="stylesheet" href="css/main.css">
    <title>Quotation</title>
</head>

<body>

    <?php
    $adminid = $_GET['adminid'];
    $active = "Quotations";
    $on = "off";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";

    $quotation = "SELECT *, quotation.email as email2, DATE(date) AS date FROM quotation INNER JOIN user ON quotation.userid = user.userid WHERE quotation.branch = 'Calumpang' ORDER BY quotation.quotationid DESC";
    $quotationResults = mysqli_query($conn, $quotation);


    ?>
    <div class="main">
        <div class="row bkq">
            <div class="col-12 case">
                <div class="overlay">
                    <div class="spinner-grow text-light" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
                <h1><i class="fa-solid fa-envelope fa-fw"></i>&nbsp;&nbsp;Quotations</h1>
                <hr>
                <div class="row frame-quote w-100">
                    <?php
                    if (mysqli_num_rows($quotationResults) > 0) {
                        while ($quotationRows = mysqli_fetch_array($quotationResults)) {
                            $date = date_create("$quotationRows[date]");
                            $dateFormat = date_format($date, "F d, Y");
                    ?>

                            <a href="view-quotation.php?adminid=<?php echo "$adminid&qoute=$quotationRows[quotationid]" ?>"
                                class="populate-quote w-100">
                                <div class="row populate-quote">
                                    <div class="col-3"><label for="" class="w-700">
                                            <?php echo "$quotationRows[firstname] $quotationRows[lastname]" ?><br>
                                        </label>
                                    </div>
                                    <div class="col-9 ellip">
                                        <span><?php echo "$quotationRows[title] - From $quotationRows[origin] to $quotationRows[destination], $quotationRows[pax] pax for $quotationRows[days], Travel Date: $dateFormat" ?></span>
                                    </div>
                                </div>
                            </a>
                    <?php
                        }
                    } else {
                        echo "<div class='text-center col'> No data found</div>";
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // var old = parseInt($('.row.ratings > div').attr("id"));
            // setInterval(() => {

            //     var current = old + 1;
            //     $('.row.frame-quote').load(" .row.frame-quote");
            // }, 5000);

            var urlA = "ajax/quotation.php";
            var urlCounts = "ajax/fetch-counts.php";

            $(document).ajaxSend(function() {
                $(".overlay").fadeIn(300);
            });

            $("#branch").change(function() {
                branch = $(this).children(":selected").attr("value");
                adminid = $(this).children(":selected").attr("class");

                $.ajax({
                    type: 'POST',
                    url: urlA,
                    data: {
                        branch: branch,
                        adminid: adminid
                    },
                    success: function(data) {
                        $('.row.frame-quote').html(data);
                    },
                    error: function(e) {
                        alert("error");
                    }
                }).done(function() {
                    // Third AJAX request for counts
                    $.ajax({
                        type: "POST",
                        url: urlCounts,
                        data: {
                            branch: branch,
                        },
                        dataType: "json",
                        success: function(response) {
                            // Update the counts in the HTML
                            $("#pending_count").text(response.pending_count);
                            $("#ticket_count").text(response.ticket_count);
                            $("#customize_count").text(response.customize_count);
                            $("#educ_count").text(response.educ_count);
                        },
                        error: function() {
                            alert("Error fetching counts");
                        },
                    }).done(function() {
                        $(".overlay").fadeOut(300);
                    });
                });
                console.log(branch);
            });
        })
    </script>
</body>

</html>