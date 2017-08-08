<?php
class Mycustom_Sales_Block_Adminhtml_Return_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{

    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        //you will notice that assigns the same blockGroup the Grid Container
        $this->_blockGroup = 'mycustom_sales';
        // and the same container
        $this->_controller = 'adminhtml_return';
        //we define the labels for the buttons save and delete
        $this->_updateButton('save', 'label','Save return');
        $this->_updateButton('delete', 'label', 'Delete return');

        $_increment_id = Mage::registry('return_data')->getData('order_id');
        $_order_tmp = Mage::getModel('sales/order')->loadByIncrementId($_increment_id);
        $_url_tmp = Mage::helper('adminhtml')->getUrl('adminhtml/sales_order_creditmemo/new', array('order_id' => $_order_tmp->getId()));
        $this->addButton('credit_memos', array(
            'label'     => Mage::helper('sales')->__('Credit Memos'),
            'onclick'   => "setLocation('{$_url_tmp}')",
            'class'     => 'go'
        ));
    }

    public function getHeaderText()
    {
        if( Mage::registry('return_data') && Mage::registry('return_data')->getId() )
        {
            return 'Edit a page '.$this->htmlEscape( Mage::registry('return_data')->getTitle() );
        }
        else
        {
            return 'Add a page';
        }
    }
}
