<?php
session_start();
session_regenerate_id();

if (!$_SESSION['adminid']) {
    header("Location:index.php?Login-first");
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
    <link rel="stylesheet" href="css/main.css">
    <title>Gcash</title>
    <style>
        p {
            margin: 0;
        }

        .row.p-2:nth-of-type(odd) {
            background: #eff5ff;
        }

        .row.p-2:nth-of-type(even) {
            background: #f6fbff;
        }
    </style>

</head>

<body>

    <?php
    $adminid = $_GET['adminid'];
    $active = "Gcash";
    $on = "active-pay";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";

    $query = "SELECT * FROM gcash";
    $res = mysqli_query($conn, $query);
    ?>
    <div class="main">
        <div class="row bkq">
            <div class="col-12 case">
                <h1><i class="fa-solid fa-box-archive fa-fw"></i>&nbsp;&nbsp;Gcash</h1>
                <hr>
                <?php
                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_array($res)) {
                        $rand = random_strings(4);
                ?>
                        <div class="row p-2">
                            <div class="col-12 flex">
                                <p class="w-700"><?php echo htmlspecialchars($row['paymentinfoid'])  ?></p>
                                <p><?php echo htmlspecialchars($row['img']) ?></p>
                                <button class="btn btn-primary btn-sm" data-target="#<?php echo $rand ?>" data-toggle="modal">View</button>
                            </div>
                        </div>

                        <div class="modal fade" style="font-size: 14px;" id="<?php echo $rand ?>" tabindex="-1" role="dialog" aria-labelledby="acceptLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" style="font-size: 16px; font-weight:bold;">Gcash Receipt</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body p-0">
                                        <div class="row w-100">
                                            <img src="<?php echo "gcash/" . htmlspecialchars($row['img']) ?>" alt="Image" style="max-width: 100%;text-align:center;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<div class='w-100 text-center'>No images found.</div>";
                }
                ?>

            </div>
        </div>

    </div>

    <div class="overlay">
        <div class="spinner-grow text-light" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>

    <script src="../user/js/validation.js"></script>
    <script>
        $(document).ready(function() {

            $('.row.p-2').each(function(index) {
                if (index % 2 === 0) {
                    $(this).css('background-color', '#eff5ff'); // Odd index (1st, 3rd, ...)
                } else {
                    $(this).css('background-color', '#f6fbff'); // Even index (2nd, 4th, ...)
                }
            });

            $('#myTable').DataTable({
                layout: {
                    topStart: {
                        buttons: ["copy", "pdf", "print"],
                    },
                },
            });

            var urlA = "ajax/load.php";
            var urlCounts = "ajax/fetch-counts.php";

            $(document).ajaxSend(function() {
                $(".overlay").fadeIn(300);
            });

            $("#branch").change(function() {
                branch = $(this).children(":selected").attr("value");
                adminid = $(this).children(":selected").attr("class");
                fullpayment = 'fullpayment';
                payment = 'Full Payment';
                $.ajax({
                    type: 'POST',
                    url: urlA,
                    data: {
                        payment: payment,
                        fullpayment: fullpayment,
                        branch: branch,
                        adminid: adminid
                    },
                    success: function(data) {
                        $('.tbody-data').html(data);
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
                            $("#tour_count").text(response.tour_count);
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

        });
    </script>
</body>

</html>