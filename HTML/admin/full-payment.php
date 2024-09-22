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
    <title>Full Payments</title>
    <style>
        #myTable_wrapper {
            width: 100%;
        }

        #myTable_wrapper thead {
            background: var(--maindark);
            color: white;
        }

        #myTable_wrapper .dt-buttons button {
            padding: 6px 12px;
            background: var(--maindark);
            border: 1px solid var(--maindark);
            border-radius: 2px;
            color: white;
        }

        #myTable_wrapper tbody td.dt-type-numeric,
        #myTable_wrapper thead tr th:nth-child(1) {
            text-align: left;
        }

        #myTable th:nth-child(6),
        #myTable td:nth-child(6) p {
            white-space: nowrap;
            /* Prevent text from wrapping */
            overflow: hidden;
            /* Hide overflowed text */
            text-overflow: ellipsis;
            /* Add ellipsis for overflowed text */
        }

        #myTable {
            table-layout: fixed;
        }

        p {
            margin: 0;
        }

        #myTable td {
            vertical-align: middle;
        }

        form .form-control {
            font-size: 12px;
        }
    </style>

</head>

<body>

    <?php
    $adminid = $_GET['adminid'];
    $active = "Full";
    $on = "active-pay";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";

    $query = "SELECT user.firstname, user.lastname, user.email, payment.*, paymentinfo.*, booking.*
    FROM paymentinfo
    INNER JOIN payment ON paymentinfo.payment_id = payment.paymentid
    INNER JOIN booking ON booking.booking_id = payment.booking_id AND booking.booking_type = payment.booking_type
    INNER JOIN user ON user.userid = paymentinfo.userid
    WHERE payment.payment_type = 'Full Payment' AND booking.branch = 'Calumpang'";
    $res = mysqli_query($conn, $query);

    ?>
    <div class="main">
        <div class="row bkq">
            <div class="col-12 case">
                <h1><i class="fa-solid fa-box-archive fa-fw"></i>&nbsp;&nbsp;Full Payments</h1>
                <hr>
                <div class="row frame-quote w-100">
                    <table id="myTable" class="table table-striped hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Booking Type</th>
                                <th>Total Price</th>
                                <th>Remaining Balance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="tbody-data">
                            <?php
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_array($res)) {
                            ?>
                                    <tr>
                                        <td><?php echo "$row[paymentinfoid]" ?></td>
                                        <td><?php echo "$row[firstname] $row[lastname]" ?></td>
                                        <td><?php echo "$row[email]" ?></td>
                                        <td><?php echo "$row[booking_type]" ?></td>
                                        <td>&#x20B1; <?php echo "$row[total_price]" ?></td>
                                        <td><?php
                                            if ($row['remaining_balance'] != '0') {
                                                echo "&#x20B1; $row[remaining_balance]";
                                            } else {
                                                echo 'Paid';
                                            }

                                            ?></td>
                                        <td class="text-center"><i class="fa-solid fa-pen fa-fw text-primary"></i></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
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
            $('#myTable').DataTable({
                layout: {
                    topStart: {
                        buttons: ["copy", "csv", "excel", "pdf", "print"],
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