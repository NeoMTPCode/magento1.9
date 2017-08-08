<?php
class Magentotutorial_Weblog_Model_Resource_Tierprice extends Mage_Core_Model_Resource_Db_Abstract{
    protected function _construct()
    {
        $this->_init('weblog/tierprice', 'value_id');
    }
}