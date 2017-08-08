<?php
/**
 * Created by PhpStorm.
 * User: liz.cherukalethu
 * Date: 22/02/2017
 * Time: 10:35
 */

class Display_Customgrid_Model_Displaygrid extends Mage_Core_Model_Abstract {

    public function __construct()
    {
        parent::__construct();
        $this->_init('customgrid/displaygrid');
    }

}