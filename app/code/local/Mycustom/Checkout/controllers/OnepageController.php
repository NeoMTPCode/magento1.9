<?php

require_once('Mage/Checkout/controllers/OnepageController.php');

class Mycustom_Checkout_OnepageController extends Mage_Checkout_OnepageController {

    public function _initAction()
    {
        parent::_initAction();
        return $this;
    }

    public function repaymentAction(){

//        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure' => true)));
//        $this->getOnepage()->initCheckout();
//        $this->loadLayout();
//        $this->_initLayoutMessages('customer/session');
//        $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
//        $this->renderLayout();

        $this->loadLayout();
//        $block = $this->getLayout()->createBlock('checkout/onepage_progress')->setTemplate('checkout/onepage/progress/payment.phtml');
//        $this->getResponse()->setBody($block->toHtml());
        $this->renderLayout();
    }


    public function updateOrderAction(){
        $currentStoreId = Mage::app()->getStore()->getStoreId();
        $data = $this->getRequest()->getPost('payment', array());
        $_order_id = $this->getRequest()->getPost('orderId');
        $_update_status = Mage::getStoreConfig('payment/'. $data['method'] .'/order_status', $currentStoreId);
        $_order = Mage::getModel('sales/order')->loadByIncrementId($_order_id);
        $_order->setState(Mage_Sales_Model_Order::STATE_NEW, true);
        $_order->setStatus($_update_status);
        $payment = $_order->getPayment();
        $payment->setMethod($data['method']);
        $payment->save();
        $_order->save();
        if($data['method'] != 'checkmo'){
            $this->_redirect($data['method']. '/standard/redirect', array('_secure' => true));
        }else{
            $params = array('code' => $_order_id);
            $this->_redirect('checkout/onepage/successRepayment', array('_secure' => true, '_query' => $params));
            return;
//            $this->_addContent($this->getLayout()->createBlock('checkout/onepage_success'));
        }
//        $this->getResponse()->setHeader('Content-type', 'application/json');
//        $this->getResponse()->setBody($tmp);
    }

    public function successRepaymentAction()
    {
        if(!Mage::getSingleton('customer/session')->isLoggedIn()){
            $this->_redirect('/');
            return;
        }
//        if (!$session->getLastSuccessQuoteId()) {
//            $this->_redirect('checkout/cart');
//            return;
//        }
//
//        $lastQuoteId = $session->getLastQuoteId();
//        $lastOrderId = $session->getLastOrderId();
//        $lastRecurringProfiles = $session->getLastRecurringProfileIds();
//        if (!$lastQuoteId || (!$lastOrderId && empty($lastRecurringProfiles))) {
//            $this->_redirect('checkout/cart');
//            return;
//        }
        $this->loadLayout();
        Mage::register('code', $this->getRequest()->getParam('code'));
//        Mage::getSingleton('core/session')->setSomeSessionVar();// In the Controller
        $this->renderLayout();
    }

}
