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

    public function orderRegister($referenceNumber, $products = array(), $referralId = null)
    {
        $referralId = ($referralId ? $referralId : $this->getReferral());
        $params = array(
            'referralId' => $referralId,
            'referenceNumber' => $referenceNumber,
            'products' => $products
        );

        return $this->call('orders', 'PUT', $params);
    }

    public function orderCancel($referenceNumber, $reason = null)
    {
        $params = array(
            'referenceNumber' => $referenceNumber,
            'reason' => $reason
        );

        return $this->call('orders', 'PATCH', $params);
    }

    public function orderDelete($referenceNumber)
    {
        $params = array(
            'referenceNumber' => $referenceNumber,
        );

        return $this->call('orders', 'DELETE', $params);
    }

    public function registerOrder($referenceNumber, $amount, $rate = null, $referralId = null)
    {
        $referralId = ($referralId ? $referralId : $this->getReferral());

        if (empty($referralId)) {
            return null;
        }

        $params = array(
            'referralId' => $referralId,
            'referenceNumber' => $referenceNumber,
            'amount' => $amount,
            'rate' => $rate
        );

        return $this->post('order', $params);
    }

    public function getReferral()
    {
        return (!empty($_COOKIE[$this->cookieName]) ? (int)$_COOKIE[$this->cookieName] : 0);
    }

    private function call($method, $request, $params = array())
    {

        $params['accessToken'] = $this->accessKey;
        $data = json_encode($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->host . '/' . $method);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-type: application/json'
        ));

        $return = curl_exec($ch);
        curl_close ($ch);

        return $return;
    }

    private function post($method, $params = array())
    {
        $params['accessToken'] = $this->accessKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->host . '/' . $method);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json'
        ));

        $return = curl_exec($ch);
        curl_close ($ch);

        return $return;
    }

}