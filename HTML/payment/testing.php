<!DOCTYPE html>
<html>

<head>
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #payment-form {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
        }

        input[type="number"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        #card-element {
            background: #f9f9f9;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        #card-errors {
            color: #dc3545;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <form id="payment-form" action="charge.php" method="POST">
        <h1>Payment</h1>
        <label for="amount">Amount (PHP):</label>
        <input type="number" id="amount" name="amount" step="0.01" min="0.01" required>

        <div id="card-element"></div>

        <button type="submit" id="submit-button">Pay</button>
        <div id="card-errors" role="alert"></div>
    </form>

    <script>
        var stripe = Stripe('pk_test_51PyxAB06jOG0j2pjxozWiwccYVN7waKv4AoiFYzZnnxhzdSHZdhgaQ75LOiMkPC5Gc92DlzgIInxMGTrRpGvPoQd00vDqljVc8'); // Replace with your Stripe publishable key
        var elements = stripe.elements();

        var card = elements.create('card');
        card.mount('#card-element');

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    var form = document.getElementById('payment-form');
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', result.token.id);
                    form.appendChild(hiddenInput);

                    // Add the amount as a hidden input
                    var amountInput = document.createElement('input');
                    amountInput.setAttribute('type', 'hidden');
                    amountInput.setAttribute('name', 'amount');
                    amountInput.setAttribute('value', document.getElementById('amount').value);
                    form.appendChild(amountInput);

                    form.submit();
                }
            });
        });
    </script>
</body>

</html>