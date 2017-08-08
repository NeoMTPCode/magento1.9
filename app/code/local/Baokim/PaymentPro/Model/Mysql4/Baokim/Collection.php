<?php

class Baokim_PaymentPro_Model_Mysql4_Baokim_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('paymentpro/baokim');
    }
}