<?php
session_start();

// Include necessary files and libraries
include '../../inc/Include.php';
require __DIR__ . "../../../vendor/autoload.php";
include '../admin/function/function.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;

$mail = new PHPMailer(true);
$dompdf = new Dompdf();

// Retrieve Stripe secret key from environment variable or hardcode it for testing (not recommended for production)
$stripe_secret_key = "sk_test_51PyxAB06jOG0j2pjskzM6sb0RUFVJPfQrTEvHbfxgnJlpCCYlT4FzffETMPRhP7ifd1TkIy93TvhRtXtOcYdQnZU00wma0vOYx";

if (!$stripe_secret_key) {
    die('Stripe secret key is not configured.');
}

if (!$_SESSION['userid']) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: http://localhost/Zorella/HTML/index.php?login-first");
    exit(); // Ensure no further code is executed
}

\Stripe\Stripe::setApiKey($stripe_secret_key);

// Sanitize and validate input
$userid = filter_input(INPUT_GET, 'userid', FILTER_SANITIZE_NUMBER_INT);
$payment_id = filter_input(INPUT_GET, 'payment_id', FILTER_SANITIZE_NUMBER_INT);
$bookid = filter_input(INPUT_GET, 'bookid', FILTER_SANITIZE_NUMBER_INT);
$booking_type = mysqli_escape_string($conn, $_GET['booking_type']);
$booking_id = mysqli_escape_string($conn, $_GET['booking_id']);
if (!$userid || !$payment_id) {
    die('Invalid input.');
}


if ($booking_type == 'Ticketed' || $booking_type == 'Customize') {
    // Prepare and execute database query
    $query = "
        SELECT
            u.userid,
            u.firstname,
            u.lastname,
            u.email,
            pt.downpayment,
            pt.total_price,
            pt.remaining_balance,
             p.payment_type,
             pt.paid_date,
             pt.price_to_pay,
            SUM(tc.adult + tc.child + tc.senior + tc.infant) AS pax,
            tc.departure as travel_date,
            tc.destination as tour
        FROM
            booking b
        INNER JOIN
            user u ON b.userid = u.userid
        INNER JOIN
            ticket tc ON (b.booking_type = 'Customize' OR b.booking_type = 'Ticketed') AND b.booking_id = tc.ticketid
        INNER JOIN
            payment p ON p.booking_id = b.booking_id AND (p.booking_type = 'Customize' OR p.booking_type = 'Ticketed')
        LEFT JOIN
            paymentinfo pt ON pt.payment_id = p.paymentid
        WHERE
            u.userid = ? AND pt.paymentinfoid = ?
        GROUP BY
            u.userid, u.firstname, u.lastname, u.email, pt.downpayment, pt.total_price, pt.remaining_balance, tc.departure ";


    processBookingPayment($conn, $userid, $payment_id, $bookid, $query, $mail);
} elseif ($booking_type == 'Educational') {

    $query = "
        SELECT
            u.userid,
            u.firstname,
            u.lastname,
            u.email,
            pt.downpayment,
             p.payment_type,
            pt.total_price,
            pt.paid_date,
            pt.price_to_pay,
            pt.remaining_balance,
            tc.pax, 
            tc.sdate as travel_date
        FROM
            booking b
        INNER JOIN
            user u ON b.userid = u.userid
        INNER JOIN
            educational tc ON b.booking_type = 'Educational' AND b.booking_id = tc.educationalid
        INNER JOIN
            payment p ON p.booking_id = b.booking_id AND p.booking_type = 'Educational'
        LEFT JOIN
            paymentinfo pt ON pt.payment_id = p.paymentid
        WHERE
            u.userid = ? AND pt.paymentinfoid = ?
        GROUP BY
            u.userid, u.firstname, u.lastname, u.email, pt.downpayment, pt.total_price, pt.remaining_balance, tc.sdate ";
    processBookingPayment($conn, $userid, $payment_id, $bookid, $query,  $mail);
} else {

    $query = "
        SELECT
            u.userid,
            u.firstname,
            u.lastname,
            u.email,
            pt.downpayment,
            pt.total_price,
            pt.remaining_balance,
            p.payment_type,
            pt.paid_date,
            pt.price_to_pay,
            tc.pax, 
            tc.tour_date as travel_date,
            tp.title as tour
        FROM
            booking b
        INNER JOIN
            user u ON b.userid = u.userid
        INNER JOIN
            tourbooking tc ON b.booking_type = 'Tour Package' AND b.booking_id = tc.tour_bookid
        INNER JOIN
            payment p ON p.booking_id = b.booking_id AND p.booking_type = 'Tour Package'
        LEFT JOIN
            paymentinfo pt ON pt.payment_id = p.paymentid
        LEFT JOIN
            tourpackage tp ON tp.tourid = tc.tourid
        WHERE
            u.userid = ? AND pt.paymentinfoid = ?
        GROUP BY
            u.userid, u.firstname, u.lastname, u.email, pt.downpayment, pt.total_price, pt.remaining_balance, tc.tour_date ";

    processBookingPayment($conn, $userid, $payment_id, $bookid, $query,  $mail);
}

// Prepare and execute database query
function processBookingPayment($conn, $userid, $payment_id, $bookid, $query, $mail)
{
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $userid, $payment_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $name = null;
    $email = null;
    $price = null;
    $travel_date = null;
    $pax = null;
    $tour = null;
    $payment_type = null;

    if ($row = $res->fetch_assoc()) {
        $name = $row['firstname'] . " " . $row['lastname'];
        $email = $row['email'];

        if (!empty($row['paid_date'])) {
            // Check if paid_date has a value
            $price = $row['price_to_pay'];
        } else {
            $price = $row['downpayment'];
        }
        $balance = $row['remaining_balance'];
        // Fetch price from database
        $travel_date = $row['travel_date']; // Fetch price from database
        $pax = $row['pax']; // Fetch price from database
        $remaining_balance = $row['remaining_balance']; // Fetch price from database
        $payment_type = $row['payment_type']; // Fetch price from database
        $tour = isset($row['tour']) ? $row['tour'] : ''; // Fetch price from database
    } else {
        die('No matching records found.');
    }


    $remaining_balance = $balance - $price;

    // Define product details with PHP currency
    $products = [
        'product' => [
            'title' => 'Downpayment',
            'name' => 'Booking Trip',
            'description' => 'Secure your booking with a downpayment.',
            'price' => $price * 100, // Convert price to cents
            'currency' => 'php'
        ]
    ];

    // Initialize line items array
    $line_items = [];

    // Build line items with fixed quantity
    foreach ($products as $key => $product) {
        $line_items[] = [
            'quantity' => 1, // Fixed quantity
            'price_data' => [
                'currency' => $product['currency'],
                'unit_amount' => $product['price'],
                'product_data' => [
                    'name' => $product['name'],
                    'description' => $product['description'],
                ]
            ]
        ];
    }

    // Create the checkout session
    try {
        // Update booking status
        if ($remaining_balance == '0.00' || $remaining_balance == 0.00) {
            $stat = 'Installment';
        } else {
            $stat = 'Paid';
        }
        // $stat = ($payment_type == 'Installment') ? 'Installment' : 'Paid';

        $update = "UPDATE booking SET status = '$stat' WHERE bookid = ?";
        $update_stmt = $conn->prepare($update);
        $update_stmt->bind_param('i', $bookid);
        $update_stmt->execute();

        $update2 = "INSERT INTO installmenthistory (amount, paymentinfoid) VALUES (?, ?)";
        $update2_stmt = $conn->prepare($update2);
        $update2_stmt->bind_param('si', $price, $payment_id);
        $update2_stmt->execute();

        $update3 = "UPDATE paymentinfo SET remaining_balance = '$remaining_balance' WHERE paymentinfoid = ?";
        $update3_stmt = $conn->prepare($update3);
        $update3_stmt->bind_param('i', $payment_id);
        $update3_stmt->execute();

        $body = generatePaymentPDF($name, $tour, $price, $pax, $travel_date, $remaining_balance);
        $pdfFilePath = savePDFConfirm($body, $name, 'confirm-payment');
        $confirmation_pdf = basename($pdfFilePath);

        sendEmail($mail, $email, $pdfFilePath, $tour, 'Acknowledgement Receipt ');

        $checkout_session = \Stripe\Checkout\Session::create([
            'mode' => 'payment',
            'success_url' => 'http://localhost/Zorella/HTML/user/my-booking.php?userid=' . urlencode($userid),
            'cancel_url' => 'http://localhost/Zorella/HTML/user/my-booking.php?userid=' . urlencode($userid),
            'locale' => 'auto',
            'line_items' => $line_items,
            'customer_email' => $email, // Set the email for the customer
        ]);

        $_SESSION['paid'] = 'Your booking was successfully paid. Thank you!';
        // Redirect to Stripe Checkout
        http_response_code(303);
        header('Location: ' . $checkout_session->url);
        exit();
    } catch (Exception $e) {
        // Handle Stripe API errors
        error_log('Stripe API error: ' . $e->getMessage());
        die('An error occurred while creating the checkout session.');
    }
}

function generatePaymentPDF($name, $tour, $price, $pax, $travel_date, $remaining_balance)
{


    ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <title>Confirmation Booking</title>
        <style>
            body {
                font-family: "Roboto", sans-serif !important;
                color: #333;
                font-size: 14px;
            }
        </style>
    </head>

    <body>
        <div class="row w-100">
            <div style="text-align:center;">
                <p><strong>ZORELLA TRAVEL AND TOURS</strong><br>
                    CALUMPANG LILIW LAGUNA<br>
                    Laguna, Philippines 4004<br>
                    +639237374423<br>
                    zorellatravelandtours@gmail.com
                </p>
                <br><br>
                <p style="font-size: 16px;font-weight: bold;">ACKNOWLEDGEMENT RECEIPT</p>
            </div>
            <div class="col-12">
                <p>Date: <strong><?php echo date('F d, Y') ?></strong></p>
            </div>
            <div class="col">
                <p>Received Payment by
                    <b style="text-decoration: underline;"><?php echo strtoupper($name) ?></b>.
                    Engaged in the business style of
                    <b style="text-decoration: underline;text-transform:uppercase"><?php echo "$tour $pax PAX (" . date('M j, Y', strtotime($travel_date)) . ")" ?></b>
                    the sum of
                    <b style="text-decoration: underline;"><?php echo strtoupper(numberToWords($price)) ?> PESOS (<?php echo 'PHP ' . number_format($price, 2)  ?>)</b> in partial/full payment, balance for
                    <b style="text-decoration: underline;">PHP <?php echo number_format($remaining_balance, 2) ?>.</b>
                </p>
            </div>
            <div class="col">
                <p><b>Payment Method:</b> Debit/Credit Card</p>
            </div>
            <div class="col" style="margin-top: 150px;">
                <p>CLIENT: <br>
                    <b style="text-transform:uppercase;text-decoration: underline;"><?php echo $name ?></b>
                </p>
            </div>
            <p style="text-align:left;margin-top:15rem;">RECEIVED BY: <br><strong style="text-decoration: underline;">Admins</strong><br></p>
            <p style="text-align:left;">CONFORME: <br><strong style="text-decoration: underline;">Ms. Bernadette Cagampan</strong><br>Owner/Operations Manager<br>ZORELLA TRAVEL AND TOURS</p>
        </div>
    </body>

    </html>
<?php
    return ob_get_clean();
}

function numberToWords($number)
{
    $words = [
        0 => 'ZERO',
        1 => 'ONE',
        2 => 'TWO',
        3 => 'THREE',
        4 => 'FOUR',
        5 => 'FIVE',
        6 => 'SIX',
        7 => 'SEVEN',
        8 => 'EIGHT',
        9 => 'NINE',
        10 => 'TEN',
        11 => 'ELEVEN',
        12 => 'TWELVE',
        13 => 'THIRTEEN',
        14 => 'FOURTEEN',
        15 => 'FIFTEEN',
        16 => 'SIXTEEN',
        17 => 'SEVENTEEN',
        18 => 'EIGHTEEN',
        19 => 'NINETEEN',
        20 => 'TWENTY',
        30 => 'THIRTY',
        40 => 'FORTY',
        50 => 'FIFTY',
        60 => 'SIXTY',
        70 => 'SEVENTY',
        80 => 'EIGHTY',
        90 => 'NINETY'
    ];

    if ($number < 100) {
        if (array_key_exists($number, $words)) {
            return $words[$number];
        }
        $tens = floor($number / 10) * 10;
        $units = $number % 10;
        return $words[$tens] . ($units ? ' ' . $words[$units] : '');
    }

    if ($number < 1000) {
        return $words[floor($number / 100)] . ' HUNDRED' .
            ($number % 100 ? ' AND ' . numberToWords($number % 100) : '');
    }

    if ($number < 1000000) {
        return numberToWords(floor($number / 1000)) . ' THOUSAND' .
            ($number % 1000 ? ' ' . numberToWords($number % 1000) : '');
    }

    return 'NUMBER TOO LARGE';
}


function savePDFConfirm($body, $name, $path)
{
    global $dompdf; // Use global variable to access the Dompdf instance
    $dompdf->loadHtml($body);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $date = date('Ymds');
    $pdfFilePath = '../admin/' . $path . '/' . $name . $date . '.pdf'; // Adjust the path as needed
    file_put_contents($pdfFilePath, $dompdf->output());

    return $pdfFilePath;
}
