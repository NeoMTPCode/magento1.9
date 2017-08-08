<?php
/*------------------------------------------------------------------------
# Websites: http://www.magentothem.com/
-------------------------------------------------------------------------*/
class Mycustom_Sales_Block_Adminhtml_Return extends Mage_Adminhtml_Block_Widget_Grid_Container{

    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_return';
        $this->_blockGroup = 'mycustom_sales';
        $this->_headerText = Mage::helper('sales')->__('Item Manager');
        $this->_addButtonLabel = Mage::helper('sales')->__('Add Item');

    }

    public function getSaveUrl()
    {
        $this->setData('form_action_url', 'save');
        return $this->getFormActionUrl();
    }
}