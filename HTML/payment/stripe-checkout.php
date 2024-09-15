<?php
session_start();

// Include necessary files and libraries
include '../../inc/Include.php';
require __DIR__ . "../../../vendor/autoload.php";

// Retrieve Stripe secret key from environment variable or hardcode it for testing (not recommended for production)
$stripe_secret_key = "sk_test_51PyxAB06jOG0j2pjskzM6sb0RUFVJPfQrTEvHbfxgnJlpCCYlT4FzffETMPRhP7ifd1TkIy93TvhRtXtOcYdQnZU00wma0vOYx";

if (!$stripe_secret_key) {
    die('Stripe secret key is not configured.');
}

\Stripe\Stripe::setApiKey($stripe_secret_key);

// Sanitize and validate input
$userid = filter_input(INPUT_GET, 'userid', FILTER_SANITIZE_NUMBER_INT);
$bookid = filter_input(INPUT_GET, 'bookid', FILTER_SANITIZE_NUMBER_INT);

if (!$userid || !$bookid) {
    die('Invalid input.');
}

// Prepare and execute database query
$query = "SELECT user.firstname, user.lastname, user.email, paymentinfo.price
          FROM paymentinfo
          INNER JOIN user ON paymentinfo.userid = user.userid
          WHERE user.userid = ? AND paymentinfo.bookid = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $userid, $bookid);
$stmt->execute();
$res = $stmt->get_result();

$name = null;
$email = null;
$price = null;

if ($row = $res->fetch_assoc()) {
    $name = $row['firstname'] . " " . $row['lastname'];
    $email = $row['email'];
    $price = $row['price']; // Fetch price from database
} else {
    die('No matching records found.');
}



// Define product details with PHP currency
$products = [
    'product' => [
        'name' => 'Booking Trip',
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
                'name' => $product['name']
            ]
        ]
    ];
}

// Create the checkout session
try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'mode' => 'payment',
        'success_url' => 'http://localhost:3000/HTML/user/my-booking.php?userid=' . urlencode($userid),
        'cancel_url' => 'http://localhost:3000/HTML/user/my-booking.php?userid=' . urlencode($userid),
        'locale' => 'auto',
        'line_items' => $line_items,
        'customer_email' => $email, // Set the email for the customer
    ]);

    // Update booking status
    // $update = "UPDATE booking SET status = 'Paid' WHERE bookid = ?";
    // $update_stmt = $conn->prepare($update);
    // $update_stmt->bind_param('i', $bookid);
    // $update_stmt->execute();

    $_SESSION['paid'] = 'Your booking was successfuly paid. Thank you!';
    // Redirect to Stripe Checkout
    http_response_code(303);
    header('Location: ' . $checkout_session->url);
    exit();
} catch (Exception $e) {
    // Handle Stripe API errors
    error_log('Stripe API error: ' . $e->getMessage());
    die('An error occurred while creating the checkout session.');
}
