<?php
/**
 * Created by PhpStorm.
 * User: liz.cherukalethu
 * Date: 22/02/2017
 * Time: 10:36
 */
class  Mycustom_Helpcenter_Model_Resource_Helpcenter_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    protected function _construct()
    {
        $this->_init('helpcenter/helpcenter');
    }
}