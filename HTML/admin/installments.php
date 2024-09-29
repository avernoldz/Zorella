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
    <title>Installment Payments</title>
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
    $active = "Installments";
    $on = "active-pay";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";

    $query = "SELECT user.firstname, user.lastname, user.email, payment.*, paymentinfo.*, booking.*
    FROM paymentinfo
    INNER JOIN payment ON paymentinfo.payment_id = payment.paymentid
    INNER JOIN booking ON booking.booking_id = payment.booking_id AND booking.booking_type = payment.booking_type
    INNER JOIN user ON user.userid = paymentinfo.userid
    WHERE payment.payment_type = 'Installment' AND booking.branch = 'Calumpang'";
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
                                    $string = random_strings(4);
                                    $string2 = random_strings(5);
                                    $select = "SELECT * FROM installmenthistory WHERE paymentinfoid = '$row[paymentinfoid]' ORDER BY paid_date DESC";
                                    $ress = mysqli_query($conn, $select);
                                    $dataArray = mysqli_fetch_all($ress, MYSQLI_ASSOC);
                            ?>
                                    <tr>
                                        <td><?php echo "$row[paymentinfoid]" ?></td>
                                        <td><?php echo "$row[firstname] $row[lastname]" ?></td>
                                        <td><?php echo "$row[email]" ?></td>
                                        <td><?php echo "$row[booking_type]" ?></td>
                                        <td>&#x20B1; <?php echo "$row[total_price]" ?></td>
                                        <td>&#x20B1; <?php echo "$row[remaining_balance]" ?></td>
                                        <td class="text-center">
                                            <i class="fa-solid fa-pen fa-fw text-secondary" style="cursor: pointer;" data-target="#<?php echo $string2; ?>" data-toggle="modal"></i>
                                            <i class="fa-solid fa-eye fa-fw text-secondary" style="cursor: pointer;" data-target="#<?php echo $string; ?>" data-toggle="modal"></i>

                                            <div class="modal fade" style="font-size: 14px;" id="<?php echo $string; ?>" tabindex="-1" role="dialog" aria-labelledby="acceptLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" style="font-size: 16px; font-weight:bold;">Payment History</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class=" table table-striped table-hover" style="font-size: 12px;">
                                                                <thead class="thead-light">
                                                                    <th class="p-1" style="width: 70%;">Date</th>
                                                                    <th class="p-1">Amount</th>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (count($dataArray) > 0) {
                                                                        foreach (array_reverse($dataArray) as $dataItem): ?>
                                                                            <tr>
                                                                                <td class="text-left p-1"><?php echo $formattedDate = (new DateTime($dataItem['paid_date']))->format('F j, Y : h:i A'); ?> </td>
                                                                                <td class="text-left p-1">PHP <?php echo number_format($dataItem['amount'], 2) ?></td>
                                                                            </tr>
                                                                    <?php
                                                                        endforeach;
                                                                    } else {
                                                                        echo "<tr><td colspan='2' class='p-1'>No available payments</td></tr>";
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal" style="font-size: 14px;">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" style="font-size: 14px;" id="<?php echo $string2; ?>" tabindex="-1" role="dialog" aria-labelledby="acceptLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" style="font-size: 16px; font-weight:bold;">Payment</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="inc/gcash.php" style="width: 100%;" id="ticket" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                                                            <div class="modal-body mb-4 mt-3">
                                                                <div class="row w-100">
                                                                    <div class="col-12 text-left">
                                                                        <label for="">Amount Paid</label><label for="" class="required">*</label>
                                                                        <input type="text" id="paid_amount"
                                                                            placeholder="PHP 0,000.00" name="paid_amount"
                                                                            class="form-control" required>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="paymentinfoid" value="<?php echo $row['paymentinfoid'] ?>">
                                                                <input type="hidden" name="adminid" value="<?php echo $adminid ?>">
                                                                <input type="hidden" name="type" value="Install">
                                                                <input type="hidden" name="balance" value="<?php echo $row['remaining_balance'] ?>">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal" style="font-size:14px;">Close</button>
                                                                <button style="display:none" type="submit" name="update" id="submit" style="font-size:14px;">Next</button>
                                                                <button type="button" class="btn btn-primary nextSubmit" style="font-size:14px;">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

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
                installment = 'installment';
                payment = 'Installment';
                $.ajax({
                    type: 'POST',
                    url: urlA,
                    data: {
                        payment: payment,
                        installment: installment,
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