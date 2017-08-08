<?php
/*------------------------------------------------------------------------
# Websites: http://www.magentothem.com/
-------------------------------------------------------------------------*/
class Mycustom_Helpcenter_Block_Adminhtml_Helpcenter extends Mage_Adminhtml_Block_Widget_Grid_Container{

    public function __construct()
    {
        $this->_controller = 'adminhtml_helpcenter';
        $this->_blockGroup = 'mycustom_helpcenter';
        $this->_headerText = Mage::helper('helpcenter')->__('Item Manager');
        $this->_addButtonLabel = Mage::helper('helpcenter')->__('Add Item');
        parent::__construct();
    }

    public function getSaveUrl()
    {
        $this->setData('form_action_url', 'save');
        return $this->getFormActionUrl();
    }
}