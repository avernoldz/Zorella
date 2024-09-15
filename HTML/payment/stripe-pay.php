<!DOCTYPE html>
<html>

<head>
    <title>Stripe Example</title>
    <meta charset="UTF-8" />
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

    <form method="post" action="checkout.php">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="product1">Product 1:</label>
            <input type="number" id="product1" name="product1" value="1" min="0" required>
            <span>Booking - $1.00</span>
        </div>
        <div>
            <label for="product2">Product 2:</label>
            <input type="number" id="product2" name="product2" value="1" min="0" required>
            <span>Hat - $7.00</span>
        </div>
        <button type="submit">Checkout</button>
    </form>
</body>

</html>