<?php
namespace Phppot;

use Phppot\Config;
require_once "Config.php";

$productCode = "WWPS235";
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="./assets/css/style.css" rel="stylesheet" type="text/css">
<title>2checkout-payment-gateway-integration-using-PHP</title>
</head>
<body>
    <div id="formContainer">
    <?php
$productDetail = Config::productDetail();
$itemName = $productDetail[$productCode]["itemName"];
$itemPrice = $productDetail[$productCode]["itemPrice"];
?>
        <div class="product-row">
            <p class="product"><?php echo $itemName; ?></p>
            <div class="price float-right"><?php echo $itemPrice; ?> <?php echo Config::CURRENCY; ?>
                </div>
        </div>
        <form id="paymentForm" novalidate method="post"
            action="payment.php">
            <input type="hidden" id="itemNumber" name="itemNumber"
                value="<?php echo $productCode; ?>" /> <input
                type="hidden" id="itemPrice" name="itemPrice"
                value="<?php echo $itemPrice; ?>" /> <input
                type="hidden" id="seller_id"
                value="<?php echo Config::SELLER_ID; ?>" /> <input
                type="hidden" id="publishable_key"
                value="<?php echo Config::PUBLISHABLE_KEY; ?>" />
            <div class="field-row col2 float-left">
                <label>Card Holder Name</label> <input type="text"
                    class="demoInputBox required" name="cardHolderName"
                    id="cardHolderName">
            </div>
            <div class="field-row col2 float-right">
                <label>Email</label> <input type="email"
                    class="demoInputBox required" name="cardHolderEmail"
                    id="cardHolderEmail">
            </div>
            <div class="field-row">
                <label>Card Number</label> <input type="text"
                    class="demoInputBox required" name="cardNumber"
                    id="cardNumber">
            </div>

            <div class="field-row col2 float-left">
                <label>Expiry Month / Year</label> <br /> <select
                    name="expiryMonth" id="expiryMonth"
                    class="demoSelectBox required">
                                    <?php
$months = Config::monthArray();
$count = count($months);
for ($i = 0; $i < $count; $i++) {
    $monthValue = $i + 1;
    if (strlen($i) < 2) {
        $monthValue = "0" . $monthValue;
    }
    ?>
                                    <option
                        value="<?php echo $monthValue; ?>"><?php echo $months[$i]; ?></option>
                                    <?php
}
?>
                            </select> <select name="expiryYear"
                    id="expiryYear" class="demoSelectBox required">
                                                  <?php
for ($i = date("Y"); $i <= 2030; $i++) {
    $yearValue = substr($i, 2);
    ?>
                                    <option
                        value="<?php echo $yearValue; ?>"><?php echo $i; ?></option>
                                    <?php
}
?>
                            </select>
            </div>
            <div class="field-row">
                <label>CVV</label><br />
                <input type="text" name="cvv" id="cvv"
                    class="demoInputBox cvv-input required">
            </div>

            <p class="sub-head">Billing Address:</p>
            <div class="field-row col2 float-left">
                <label>Address Line1</label> <input type="text"
                    class="demoInputBox required" name="addressLine1"
                    id="addressLine1">
            </div>
            <div class="field-row col2 float-right">
                <label>Address Line2</label> <input type="email"
                    class="demoInputBox" name="addressLine2"
                    id="addressLine2">
            </div>
            <div class="field-row col2 float-left">
                <label>Country</label> <input type="text"
                    class="demoInputBox required" name="country" id="country">
            </div>
            <div class="field-row col2 float-right">
                <label>State</label> <input type="text"
                    class="demoInputBox required" name="state" id="state">
            </div>
            <div class="field-row col2 float-left">
                <label>City</label> <input type="text"
                    class="demoInputBox required" name="city" id="city">
            </div>
            <div class="field-row col2 float-right">
                <label>Zip</label> <input type="text"
                    class="demoInputBox required" name="zip" id="zip">
            </div>
            <div class="clear-float">
                <input id="token" name="token" type="hidden" value="">
                <input type="button" id="submit-btn" class="btnAction"
                    value="Send Payment">
                <div id="loader">
                    <img alt="loader" src="./images/LoaderIcon.gif" />
                </div>
            </div><div id="error-message"></div>
        </form>
    </div>
    <!-- jQuery library -->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script
        src="vendor/jquery-creditcardvalidator/jquery.creditCardValidator.js"></script>
    <script src="./assets/js/validation.js"></script>

    <!-- 2Checkout JavaScript library -->
    <script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
    <script>
            // A success callback of TCO token request
            var success = function (data) {
                // Set the token in the payment form
                $('#paymentForm #token').val(data.response.token.token);

                $("#error-message").hide();
                $("#error-message").html("");

                // Submit the form with TCO token
                $('#paymentForm').submit();
            };

            // A Error callback of TCO token request.
            var error = function (data) {
                var errorMsg = "";
                if (data.errorCode === 200) {
                    tokenRequest();
                } else {
                    errorMsg = data.errorMsg;
                    $("#error-message").show();
                    $("#error-message").html(errorMsg);
                    $("#submit-btn").show();
                    $("#loader").hide();
                }
            };

            function tokenRequest() {
                	var valid = validate();
                if (valid == true) {
                    $("#submit-btn").hide();
                    $("#loader").css("display", "inline-block");
                    var args = {
                        sellerId: $('#seller_id').val(),
                        publishableKey: $('#publishable_key').val(),
                        ccNo: $("#cardNumber").val(),
                        cvv: $("#cvv").val(),
                        expMonth: $("#expiryMonth").val(),
                        expYear: $("#expiryYear").val()
                    };

                    // Request 2Checkout token
                    TCO.requestToken(success, error, args);
                }
            }

            $(function () {
            	   TCO.loadPubKey('sandbox');

                $("#submit-btn").on('click', function (e) {
                   tokenRequest();
                   return false;
                });
            });
        </script>
</body>
</html>