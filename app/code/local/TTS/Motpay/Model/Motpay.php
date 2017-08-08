<?php

class TTS_Motpay_Model_Motpay extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'motpay';
    protected $_formBlockType = 'motpay/form';
    protected $_infoBlockType = 'motpay/info';

    public function execPostRequest($data)
    {
        $url = 'http://api.1pay.vn/bank-charging/service';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function getTitle()
    {
        return $this->getConfigData('title');
    }

    public function getAccessKey()
    {
        return $this->getConfigData('motpay_access_key');
    }

    public function getCommand()
    {
        return $this->getConfigData('motpay_command');
    }

    public function getSecret()
    {
        return $this->getConfigData('motpay_secret');
    }

    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('motpay/standard/redirect', array('_secure' => true));
    }

    public function getUrlMotpay($orderid)
    {
        $_order = Mage::getModel('sales/order')->loadByIncrementId($orderid);
        $getGrandTotal = $_order->getGrandTotal();
        $getGrandTotalArr = explode(".", $getGrandTotal);
        $getGrandTotalArr0 = $getGrandTotalArr[0];

        $access_key = $this->getAccessKey();           // require your access key from 1pay
        $secret = $this->getSecret();               // require your secret key from 1pay
        $command = $this->getCommand();
        $return_url = 'http://127.0.0.1/shopbing/motpay/standard/success'; //url api response
        $amount = $getGrandTotalArr0; //$_POST['amount'];   // >10000
        $order_id = $orderid; //$_POST['order_id'];
        $order_info = $_order->getCustomerId();; //$_POST['order_info'];

        $data = "access_key=".$access_key."&amount=".$amount."&command=".$command."&order_id=".$order_id."&order_info=".$order_info."&return_url=".$return_url;
        $signature = hash_hmac("sha256", $data, $secret);
        $data.= "&signature=".$signature;
        $json_bankCharging = $this->execPostRequest($data);
        $decode_bankCharging = json_decode($json_bankCharging,true);  // decode json
        $pay_url = $decode_bankCharging["pay_url"];
        return $pay_url;
    }

    public function getResponseDescription($responseCode)
    {
        switch ($responseCode) {
            case "0" :
                //$result = "Giao dịch thành công - Approved";
                break;
            case "1" :
                $result = "Bank Declined";
                break;
            case "3" :
                $result = "Merchant not exist";
                break;
            case "4" :
                $result = "Invalid access code";
                break;
            case "5" :
                $result = "Invalid amount";
                break;
            case "6" :
                $result = "Invalid currency code";
                break;
            case "7" :
                $result = "Unspecified Failure ";
                break;
            case "8" :
                $result = "Invalid card Number";
                break;
            case "9" :
                $result = "Invalid card name";
                break;
            case "10" :
                $result = "Expired Card";
                break;
            case "11" :
                $result = "Card Not Registed Service(internet banking)";
                break;
            case "12" :
                $result = "Invalid card date";
                break;
            case "13" :
                $result = "Exist Amount";
                break;
            case "21" :
                $result = "Insufficient fund";
                break;
            case "99" :
                $result = "User cancel";
                break;
            default :
                //$result = "Giao dịch thất bại - Failured";
        }
        return $result;
    }

    public function transStatus($hashValidated, $txnResponseCode)
    {
        $transStatus = "";
        if ($hashValidated == "CORRECT" && $txnResponseCode == "0") {
            $transStatus = "Transaction Success";
        } elseif ($txnResponseCode != "0") {
            $transStatus = "Transaction Fail </br>" . $this->getResponseDescription($txnResponseCode);
        } elseif ($hashValidated == "INVALID HASH") {
            $transStatus = "Transaction Pendding";
        }
        return $transStatus;
    }
}