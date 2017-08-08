<?php
class Magentotutorial_Weblog_Block_Adminhtml_Weblog_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{

    public function __construct()
    {
        $this->_objectId = 'id';
        //you will notice that assigns the same blockGroup the Grid Container
        $this->_blockGroup = 'magentotutorial_weblog';
        // and the same container
        $this->_controller = 'adminhtml_weblog';
        //we define the labels for the buttons save and delete
//        $this->_updateButton('save', 'label','Save page');
//        $this->_updateButton('delete', 'label', 'Delete page');
        parent::__construct();
        $this->_removeButton('add');
        $this->_removeButton('save');
    }

    public function getHeaderText()
    {
        if( Mage::registry('weblog_data') && Mage::registry('weblog_data')->getId() )
        {
            return 'Edit Log ID Product #'.$this->htmlEscape(Mage::registry('weblog_data')->getData('entity_id'));
        }
        else
        {
            return 'Add Log';
        }
    }
    protected function _prepareLayout() {
        $this->setChild('store_switcher', $this->getLayout()->createBlock('adminhtml/store_switcher', 'store_switcher')->setUseConfirm(false)
        );
        return parent::_prepareLayout();
    }

}
