<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Selection</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 18px;
            color: #444;
            margin-bottom: 10px;
        }

        .payment-type,
        .payment-method {
            margin-bottom: 20px;
            text-align: left;
        }

        .radio-container,
        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .radio-container input[type="radio"],
        .checkbox-container input[type="checkbox"] {
            display: none;
        }

        .radio-checkmark,
        .checkbox-checkmark {
            border: 2px solid #ddd;
            border-radius: 4px;
            height: 20px;
            width: 20px;
            display: inline-block;
            margin-right: 10px;
            position: relative;
        }

        .radio-container input[type="radio"]:checked+.radio-checkmark {
            background-color: #4caf50;
            border-color: #4caf50;
        }

        .checkbox-container input[type="checkbox"]:checked+.checkbox-checkmark {
            background-color: #4caf50;
            border-color: #4caf50;
        }

        .checkbox-checkmark::after {
            content: '';
            position: absolute;
            left: 6px;
            top: 2px;
            width: 8px;
            height: 12px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        button {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Select Payment Options</h1>
        <form>
            <div class="payment-type">
                <h2>Payment Type</h2>
                <label class="radio-container">
                    <input type="radio" name="paymentType" value="installment">
                    <span class="radio-checkmark"></span>
                    Installment
                </label>
                <label class="radio-container">
                    <input type="radio" name="paymentType" value="full">
                    <span class="radio-checkmark"></span>
                    Full Payment
                </label>
            </div>

            <div class="payment-method">
                <h2>Payment Method</h2>
                <label class="checkbox-container">
                    <input type="checkbox" name="paymentMethod" value="gcash">
                    <span class="checkbox-checkmark"></span>
                    Gcash
                </label>
                <label class="checkbox-container">
                    <input type="checkbox" name="paymentMethod" value="credit-card">
                    <span class="checkbox-checkmark"></span>
                    Credit Card
                </label>
                <label class="checkbox-container">
                    <input type="checkbox" name="paymentMethod" value="paymaya">
                    <span class="checkbox-checkmark"></span>
                    Paymaya
                </label>
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>

</html>