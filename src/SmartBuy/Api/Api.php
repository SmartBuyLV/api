<?php
namespace SmartBuy\Api;

class Api
{
    /**
     * cookie lifetime in days
     * @var int
     */
    private $cookieLifetime = 0;
    private $accessKey;
    private $host = 'https://www.smartbuy.lv/api/v1';

    function __construct($accessKey = null, $cookieLifetime = 0)
    {
        $this->accessKey = $accessKey;
        if ($cookieLifetime) {
            $this->cookieLifetime = $cookieLifetime;
        }
    }

    public function init()
    {
        if (!empty($_GET['affiliate-smartbuy'])) {
            setcookie('smartbuy_referral', $_GET['affiliate-smartbuy'], strtotime('+' . $this->cookieLifetime . ' days'));
        }
    }

    public function registerOrder($referenceNumber, $amount, $rate = null)
    {
        if (empty($this->getReferral())) {
            return null;
        }

        $params = [
            'referralId' => $this->getReferral(),
            'referenceNumber' => $referenceNumber,
            'amount' => $amount,
            'rate' => $rate
        ];

        return $this->post('order', $params);
    }

    private function getReferral()
    {
        return (!empty($_COOKIE['smartbuy_referral']) ? (int)$_COOKIE['smartbuy_referral'] : 0);
    }

    private function post($method, $params = [])
    {
        $params['accessToken'] = $this->accessKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->host . '/' . $method);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
        ]);

        $return = curl_exec($ch);
        curl_close ($ch);

        return $return;
    }

}