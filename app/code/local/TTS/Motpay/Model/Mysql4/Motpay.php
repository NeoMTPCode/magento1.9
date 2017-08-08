<?php

class TTS_Motpay_Model_Mysql4_Motpay extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the motpay_id refers to the key field in your database table.
        $this->_init('motpay/motpay', 'motpay_id');
    }
}