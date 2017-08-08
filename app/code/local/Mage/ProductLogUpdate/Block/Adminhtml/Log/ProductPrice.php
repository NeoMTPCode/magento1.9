<?php

class Mage_ProductLogUpdate_Adminhtml_Log_ProductPrice extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'mage_productlogupdate';
        $this->_controller = 'adminhtml_log_productprice';
        $this->_headerText = Mage::helper('mage_productlogupdate')->__('Product Price');

        parent::__construct();
        $this->_removeButton('add');
    }
}