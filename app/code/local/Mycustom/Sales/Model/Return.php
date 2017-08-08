<?php
/**
 * Created by PhpStorm.
 * User: liz.cherukalethu
 * Date: 22/02/2017
 * Time: 10:35
 */

class Mycustom_Sales_Model_Return extends Mage_Core_Model_Abstract {

    public function __construct()
    {
        parent::__construct();
        $this->_init('mycustom_sales/return');
    }

}