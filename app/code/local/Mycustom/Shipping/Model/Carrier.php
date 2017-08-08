<?php
class Mycustom_Shipping_Model_Carrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface{

    protected $_code = 'mycustom_shipping';

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        $result = Mage::getModel('shipping/rate_result');
        //check price quote
        $_grandTotal = Mage::getSingleton('checkout/session')->getQuote()->getGrandTotal();
        if($_grandTotal >= $this->getConfigData('max_order_amount')){
            return false;
        }
        /* @var $result Mage_Shipping_Model_Rate_Result */
        $result->append($this->_getStandardShippingRate());
        return $result;
    }

    public function getAllowedMethods(){
        return array(
            'standard' => 'Standard',
        );
    }

    protected function _getStandardShippingRate()
    {
        $rate = Mage::getModel('shipping/rate_result_method');
        /* @var $rate Mage_Shipping_Model_Rate_Result_Method */

        $rate->setCarrier($this->_code);
        /**
         * getConfigData(config_key) returns the configuration value for the
         * carriers/[carrier_code]/[config_key]
         */
        $rate->setCarrierTitle($this->getConfigData('title'));

        $rate->setMethod('standand');
        $rate->setMethodTitle('Standard');

        $rate->setPrice($this->getConfigData('price'));
        $rate->setCost(0);

        return $rate;
    }
}