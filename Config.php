<?php
namespace Phppot;

class Config
{

    const CURRENCY = 'USD';

    const SELLER_ID = '';

    const PUBLISHABLE_KEY = '';

    const PRIVATE_KEY = '';

    public function productDetail()
    {
        $product = array(
            'WWPS235' => array(
                "itemName" => 'Kindle Paperwhite (10th gen) - 6" 8GB, WiFi',
                'itemPrice' => '130.00',
            ),
        );
        return $product;
    }

    public function monthArray()
    {
        $months = array(
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July ',
            'August',
            'September',
            'October',
            'November',
            'December',
        );
        return $months;
    }
}
