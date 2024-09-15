<?php

require __DIR__ . "../../../vendor/autoload.php";

\Stripe\Stripe::setApiKey('sk_test_51PyxAB06jOG0j2pjskzM6sb0RUFVJPfQrTEvHbfxgnJlpCCYlT4FzffETMPRhP7ifd1TkIy93TvhRtXtOcYdQnZU00wma0vOYx'); // Replace with your Stripe secret key

$payload = @file_get_contents('php://input');
$event = null;

try {
    $event = \Stripe\Event::constructFrom(
        json_decode($payload, true)
    );
} catch (\UnexpectedValueException $e) {
    http_response_code(400);
    exit();
}

if ($event->type === 'checkout.session.completed') {
    $session = $event->data->object;

    // Retrieve customer details
    $customer_id = $session->customer;
    $customer = \Stripe\Customer::retrieve($customer_id);

    $email = $customer->email;
    $name = $customer->name;

    // Handle the data (e.g., store in your database)
}

// Respond to Stripe
http_response_code(200);
