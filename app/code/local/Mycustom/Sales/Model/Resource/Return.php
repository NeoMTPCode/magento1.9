<?php
class Mycustom_Sales_Model_Resource_Return extends Mage_Core_Model_Resource_Db_Abstract{

    public function _construct()
    {
        $this->_init('mycustom_sales/return','entity_id');
    }

}