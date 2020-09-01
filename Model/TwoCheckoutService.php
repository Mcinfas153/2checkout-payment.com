<?php
use Phppot\Config;

require_once("vendor/2checkout-php-master/lib/Twocheckout.php");

class TwoCheckoutService
{

    function verifyAuthentication()
    {
        Twocheckout::verifySSL(false);
        
        // Set API key
        Twocheckout::privateKey(Config::PRIVATE_KEY); // PRIVATE_KEY defined in config.php
        Twocheckout::sellerId(Config::SELLER_ID); // SELLER_ID defined in config.php
        Twocheckout::sandbox(true);
    }
    
    function chargeCard($orderID, $token, $currency, $customerDetail, $billingAddress, $itemPrice)
    {
        $successMessage = "";
        $errorMessage = "";
        try {
            // an array is created with customer sale parameters and passed it in auth() function of Twocheckout_Charge class for authorization.
            $charge = Twocheckout_Charge::auth(array(
                "merchantOrderId" => $orderID,
                "token" => $token,
                "currency" => $currency,
                "total" => $itemPrice,
                "billingAddr" => array(
                    "name" => $customerDetail['name'],
                    "addrLine1" => $billingAddress['addrLine1'],
                    "city" => $billingAddress['city'],
                    "state" => $billingAddress['state'],
                    "zipCode" => $billingAddress['zipCode'],
                    "country" => $billingAddress['country'],
                    "email" => $customerDetail['email']
                )
            ));
            
            $paymentResponse = array(
                "message" => "",
                "charge" => $charge
            );
        } catch (Twocheckout_Error $e) {
            $paymentResponse = array(
                "message" => $e->getMessage(),
                "charge" => ""
            );
        }
        return $paymentResponse;
    }
}
