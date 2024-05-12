<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('..\include\head.php');?>
    <!-- Add your CSS styles here if needed -->
    <style>

  .containers {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 0px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
		form{
			width: 500px;
			padding-left: 50px;
			padding-bottom: 50px;
		}
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .payment-options {
            margin-top: 10px;
        }
    
    </style>
</head>
<body>
    <div class="containers"></div>
    <?php include('..\include\header.php');?>
    <h1 style="padding-left: 50px; padding-bottom: 30px; padding-top:30px">Checkout</h1>

    <!-- User Information Form -->
    <h2 style="padding-left: 50px;">Delivery Information</h2>
    <?php
        // PHP code for fetching user information
    ?>

    <!-- Order Summary -->
    <h2>Order Summary</h2>
    <?php
        // PHP code for displaying order summary
    ?>

    <!-- Payment Information -->
    <h2>Payment Information</h2>
    <form action="#" method="post">
        <label>Select Payment Method:</label><br><br>
        <!-- Credit Card Payment -->
        <input type="radio" id="credit_card" name="payment_method" value="credit_card" checked>
        <label for="credit_card">Credit Card</label><br><br>
        <div class="credit-card-input">
            <label for="cardNumber">Card Number:</label>
            <input type="text" id="cardNumber" name="cardNumber" maxlength="16" placeholder="Enter your card number" required><br><br>
            <label for="cardHolderName">Cardholder Name:</label>
            <input type="text" id="cardHolderName" name="cardHolderName" placeholder="Enter cardholder's name" required><br><br>
            <label for="expirationDate">Expiration Date:</label>
            <input type="text" id="expirationDate" name="expirationDate" placeholder="MM/YYYY" required><br><br>
            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" minlength="3" maxlength="4" placeholder="Enter CVV" required><br><br>
        </div>

        <!-- Other Payment Methods -->
        <input type="radio" id="paypal" name="payment_method" value="paypal">
        <label for="paypal">PayPal</label><br><br>

        <input type="radio" id="cash_on_delivery" name="payment_method" value="cash_on_delivery">
        <label for="cash_on_delivery">Cash on Delivery</label><br><br>

        <input type="submit" value="Place Order">
    </form>

    <?php include('..\include\footer.php');?>

    <!-- JavaScript for card validation -->
    <script>
        document.querySelector('#cardNumber').addEventListener('input', function (e) {
            var input = e.target.value.replace(/\D/g, '').substring(0, 16);
            input = input != '' ? input.match(/.{1,4}/g).join(' ') : '';
            e.target.value = input;
        });

        document.querySelector('#expirationDate').addEventListener('input', function (e) {
            var input = e.target.value.replace(/\D/g, '').substring(0, 6);
            input = input != '' ? input.match(/.{1,2}/g).join('/') : '';
            e.target.value = input;
        });

        document.querySelector('#cvv').addEventListener('input', function (e) {
            var input = e.target.value.replace(/\D/g, '').substring(0, 4);
            e.target.value = input;
        });
    </script>
</body>
</html>
