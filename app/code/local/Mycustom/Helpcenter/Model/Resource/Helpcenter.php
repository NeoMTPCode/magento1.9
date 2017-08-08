<?php
/**
 * Created by PhpStorm.
 * User: liz.cherukalethu
 * Date: 22/02/2017
 * Time: 10:36
 */
class Mycustom_Helpcenter_Model_Resource_Helpcenter extends Mage_Core_Model_Resource_Db_Abstract
{
    /***
     * Initialize resource model
     */
    public function _construct()
    {
        $this->_init('helpcenter/helpcenter','entity_id');
    }

}