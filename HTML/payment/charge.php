<?php

require __DIR__ . "../../../../vendor/autoload.php";

\Stripe\Stripe::setApiKey('sk_test_51PyxAB06jOG0j2pjskzM6sb0RUFVJPfQrTEvHbfxgnJlpCCYlT4FzffETMPRhP7ifd1TkIy93TvhRtXtOcYdQnZU00wma0vOYx'); // Replace with your Stripe secret key

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['stripeToken'];
    $amountPHP = floatval($_POST['amount']); // Get amount in PHP from the form

    // Convert amount from PHP to cents (Stripe requires amount in the smallest currency unit)
    $amountCents = $amountPHP * 100;

    try {
        // Create a charge: this will charge the user's card
        $charge = \Stripe\Charge::create([
            'amount' => $amountCents, // Amount in cents
            'currency' => 'php', // Set currency to PHP
            'description' => 'Example charge in PHP',
            'source' => $token,
        ]);

        echo 'Success! Your payment of PHP ' . number_format($amountPHP, 2) . ' has been processed.';
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Handle error
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request method.';
}
