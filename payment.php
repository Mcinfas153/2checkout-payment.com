<?php
use Phppot\Config;
use Phppot\Model\Checkout;

session_start();
require_once 'Config.php';

// Check if token is not empty
if (! empty($_POST['token'])) {
    
    $token = $_POST['token'];
    $currency = Config::CURRENCY;
    
    // Card information
    $card_num = $_POST['cardNumber'];
    $card_cvv = $_POST['cvv'];
    $card_exp_month = $_POST['expiryMonth'];
    $card_exp_year = $_POST['expiryYear'];
    
    // Customer information
    $customerDetail['name'] = $_POST['cardHolderName'];
    $customerDetail['email'] = $_POST['cardHolderEmail'];
    
    $billingAddress['addrLine1'] = $_POST['addressLine1'];
    $billingAddress['addrLine2'] = $_POST['addressLine2'];
    $billingAddress['city'] = $_POST['city'];
    $billingAddress['state'] = $_POST['state'];
    $billingAddress['zipCode'] = $_POST['zip'];
    $billingAddress['country'] = $_POST['country'];
    
    // Product information
    $product['itemNumber'] = $_POST["itemNumber"];
    $product['itemPrice'] = $_POST["itemPrice"];
    
    require_once 'Model/Checkout.php';
    $checkout = new Checkout();
    
    $orderID = $checkout->insertOrder($customerDetail, $billingAddress, $product);
    
    require_once 'Model/TwoCheckoutService.php';
    $twoCheckoutService = new TwoCheckoutService();
    
    $twoCheckoutService->verifyAuthentication();
    $successMessage = "";
    $errorMessage = "";
    $paymentResponse = $twoCheckoutService->chargeCard($orderID, $token, $currency, $customerDetail, $billingAddress, $product['itemPrice']);
    
    if (! empty($paymentResponse["charge"])) {
        $charge = $paymentResponse["charge"];
        if ($charge['response']['responseCode'] == 'APPROVED') {
            $transactionId = $charge['response']['transactionId'];
            $status = $charge['response']['responseCode'];
            
            $checkout->updatePayment($transactionId, $status, $orderID);
            
            header("Location: return.php?status=success&itemNumber=".$orderID);
        } else {
            $_SESSION["transaction_error"] = "Payment is waiting for approval.";
            header("Location: return.php?status=transaction_failed");
        }
    } else if($paymentResponse["message"]) {
        if(!empty($paymentResponse["message"])) {
            $_SESSION["transaction_error"] = $paymentResponse["message"];
        }
        header("Location: return.php?status=transaction_failed");
    }
} else {
    header("Location: return.php?status=invalid_token");
}
?>
 
