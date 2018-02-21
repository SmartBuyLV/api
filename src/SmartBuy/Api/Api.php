<?php
namespace SmartBuy\Api;

class Api
{
    /**
     * cookie lifetime in days
     * @var int
     */
    private $cookieLifetime = 0;
    private $cookieName = 'affiliate-smartbuy';
    private $accessKey;
    private $host = 'https://www.smartbuy.lv/api/v1';

    function __construct($accessKey = null, $cookieLifetime = 0, $cookieName = null)
    {
        $this->accessKey = $accessKey;
        if ($cookieLifetime) {
            $this->cookieLifetime = $cookieLifetime;
        }
        if ($cookieName) {
            $this->cookieName = $cookieName;
        }
    }

    public function init()
    {
        if (!empty($_GET['affiliate-smartbuy'])) {
            setcookie($this->cookieName, $_GET['affiliate-smartbuy'], strtotime('+' . $this->cookieLifetime . ' days'));
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
        return (!empty($_COOKIE[$this->cookieName]) ? (int)$_COOKIE[$this->cookieName] : 0);
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