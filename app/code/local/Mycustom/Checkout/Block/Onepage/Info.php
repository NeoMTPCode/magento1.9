<?php

class Mycustom_Checkout_Block_Onepage_Info extends Mage_Core_Block_Template{
    public function getMethods()
    {
//        $methods = $this->getData('methods');
//        if ($methods === null) {
//            $quote = $this->getQuote();
//            $store = $quote ? $quote->getStoreId() : null;
//            $methods = array();
//            foreach ($this->helper('payment')->getStoreMethods($store, $quote) as $method) {
//                if ($this->_canUseMethod($method) && $method->isApplicableToQuote(
//                        $quote,
//                        Mage_Payment_Model_Method_Abstract::CHECK_ZERO_TOTAL
//                    )) {
//                    $this->_assignMethod($method);
//                    $methods[] = $method;
//                }
//            }
//            $this->setData('methods', $methods);
//        }
//        return $methods;
    }
    public function getActivePaymentMethods()
    {
        $payments = Mage::getModel('payment/config')->getActiveMethods();

        $methods = array();

        foreach ($payments as $paymentCode => $paymentModel) {
            $paymentTitle = Mage::getStoreConfig('payment/' . $paymentCode . '/title');
            $methods[$paymentCode] = array(
                'label' => $paymentTitle,
                'value' => $paymentCode,
            );
        }
        return $methods;
    }
}