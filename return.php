<?php
namespace Phppot;
use Phppot\Model\Checkout;
session_start();

if (! empty($_GET["status"])) {
    $status = $_GET["status"];
    $errorMessage = "";
        if(!empty($_SESSION["transaction_error"])) {
            $errorMessage = $_SESSION["transaction_error"];
            unset($_SESSION["transaction_error"]);
        }
    ?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="./assets/css/style.css" rel="stylesheet" type="text/css">
<title>2checkout-payment-gateway-integration-using-PHP</title>
</head>
<body>
    <div id="response-container">
    <?php
    
    if ($status == "success") {
        $orderId = $_GET["itemNumber"];
        require_once "Model/Checkout.php";
        $checkout = new Checkout();
        $orderResult = $checkout->getOrder($orderId);
        ?>
    <p class="payment-response-1">Thank you for your Order.</p>
        <p class="payment-response-2">The transaction is made
            successfully. The order details are given below.</p>

        <div class="purchase-detail">
            <div class="col-name">Order ID</div>
            <div class="col-value"><?php echo $orderResult[0]["id"]; ?></div>
            <div class="col-name">Transaction ID</div>
            <div class="col-value"><?php echo $orderResult[0]["txn_id"]; ?></div>
            <div class="col-name">Total Price</div>
            <div class="col-value"><?php echo number_format($orderResult[0]["item_price"], 2) . " " . $orderResult[0]["currency"]; ?></div>
        </div>
<?php
    } else if ($status == "transaction_failed") {
        ?>
        <p class="payment-response-2"><?php echo $errorMessage; ?></p>
    <?php
    } if ($status == "invalid_token") {
        ?>
        <p class="payment-response-1">Payment attempt with invalid token.</p>
        <?php
    }
    ?>

    <a href="index.php" class="continue-shopping">Continue to Place
            Another Order</a>
    </div>
    <?php
}
?>
</body>
</html>