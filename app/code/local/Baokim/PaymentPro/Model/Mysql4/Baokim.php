<?php

class Baokim_PaymentPro_Model_Mysql4_Baokim extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the baokim_id refers to the key field in your database table.
        $this->_init('paymentpro/baokim', 'baokim_id');
    }
}