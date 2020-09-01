<?php
/**
 * Copyright (C) 2019 Phppot
 * 
 * Distributed under MIT license with an exception that, 
 * you don’t have to include the full MIT License in your code.
 * In essense, you can use it on commercial software, modify and distribute free.
 * Though not mandatory, you are requested to attribute this URL in your code or website.
 */
namespace Phppot\Model;

use Phppot\DataSource;
use Phppot\Config;

class Checkout
{

    private $ds;

        function __construct()
    {
        require_once __DIR__ . './../lib/DataSource.php';
        $this->ds = new DataSource();
    }

    /**
     * to get the member record based on the subscription_key
     *
     * @param string $subscriptionKey
     * @return array result record
     */
    public function getOrder($orderId)
    {
        $query = 'SELECT * FROM tbl_order where id = ?';
        $paramType = 'i';
        $paramValue = array(
            $orderId
        );
        $result = $this->ds->select($query, $paramType, $paramValue);
        return $result;
    }

    public function insertOrder($customerDetail, $billingAddress, $product)
    {
        $current_time = date("Y-m-d H:i:s");
        $query = 'INSERT INTO tbl_order (name, email, item_code, item_price, currency, address_line_1, address_line_2, country, state, city, zip, create_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $paramType = 'sssissssssss';
        $paramValue = array(
            $customerDetail["name"],
            $customerDetail["email"],
            $product["itemNumber"],
            $product["itemPrice"],
            Config::CURRENCY,
            $billingAddress['addrLine1'],
            $billingAddress['addrLine2'],
            $billingAddress['country'],
            $billingAddress['state'],
            $billingAddress['city'],
            $billingAddress['zipCode'],
            $current_time
        );
        $insertStatus = $this->ds->insert($query, $paramType, $paramValue);
        return $insertStatus;
    }

    public function updatePayment($transactionId, $paymentStatus, $orderID)
    {
        $query = "UPDATE tbl_order set txn_id = ?, payment_status = ? WHERE id = ?";
        
        
        $paramType = 'ssi';
        $paramValue = array(
            $transactionId,
            $paymentStatus,
            $orderID
        );
        $this->ds->execute($query, $paramType, $paramValue);
    }
}