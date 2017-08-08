<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Paypal
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class TTS_Motpay_StandardController extends Mage_Core_Controller_Front_Action
{

    public function redirectAction()
    {
        $session = Mage::getSingleton('checkout/session');
        if ($this->getRequest()->getParam('motpay') != 1) {
            $url = Mage::getModel('motpay/motpay')->getUrlMotpay($session->getLastRealOrderId());
        }
        $this->_redirectUrl($url);
    }

    /**
     * When a customer cancel payment from paypal.
     */

    public function successAction()
    {
        $order_id = $this->null2unknown($_GET ["order_id"]);
        $access_key = $this->null2unknown($_GET ["access_key"]);
//        $response_code = $this->null2unknown($_GET ["response_code"]);
        $trans_ref = $this->null2unknown($_GET ["trans_ref"]);
        $command = "close_transaction";

        $data = "access_key=".$access_key."&command=".$command."&trans_ref=".$trans_ref;
        $signature = hash_hmac("sha256", $data, Mage::getModel('motpay/motpay')->getSecret());
        $data.= "&signature=" . $signature;

        $json_bankCharging = Mage::getModel('motpay/motpay')->execPostRequest($data);
        $decode_bankCharging = json_decode($json_bankCharging,true);  // decode json
        // Ex: {"amount":10000,"trans_status":"close","response_time": "2014-12-31T00:52:12Z","response_message":"Giao dịch thành công","response_code":"00","order_info":"test dich vu","order_id":"001","trans_ref":"44df289349c74a7d9690ad27ed217094", "request_time":"2014-12-31T00:50:11Z","order_type":"ND"}

        $response_message = $decode_bankCharging["response_message"];
        $response_code = $decode_bankCharging["response_code"];
        $amount = $decode_bankCharging["amount"];

        if($response_code == "00")
        {
//            echo $response_message."-".$amount;
            $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
            $order->setData('state', "complete");
            $order->setStatus("complete");
            $history = $order->addStatusHistoryComment('Order was set to Complete by our automation tool.', false);
            $history->setIsCustomerNotified(false);
            $order->save();
            $this->_redirect('checkout/onepage/success', array('_secure' => true));
        }
        else{
//            echo $response_message;
            return $this->_redirect('checkout/onepage/failure', array('_secure' => true));
        }
    }

    public function null2unknown($data)
    {
        if ($data == "") {
            return "No Value Returned";
        } else {
            return $data;
        }
    }
}
