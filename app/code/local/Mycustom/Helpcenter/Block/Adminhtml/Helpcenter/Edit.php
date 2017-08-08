<?php
class Mycustom_Helpcenter_Block_Adminhtml_Helpcenter_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{

    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        //you will notice that assigns the same blockGroup the Grid Container
        $this->_blockGroup = 'mycustom_helpcenter';
        // and the same container
        $this->_controller = 'adminhtml_helpcenter';
        //we define the labels for the buttons save and delete
        $this->_updateButton('save', 'label','Save page');
        $this->_updateButton('delete', 'label', 'Delete page');
    }

    public function getHeaderText()
    {
        if( Mage::registry('helpcenter_data') && Mage::registry('helpcenter_data')->getId() )
        {
            return 'Edit a page '.$this->htmlEscape( Mage::registry('helpcenter_data')->getTitle() );
        }
        else
        {
            return 'Add a page';
        }
    }

    protected function _prepareLayout() {
        // Load Wysiwyg on demand and Prepare layout
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled() && ($block = $this->getLayout()->getBlock('head'))) {
            $block->setCanLoadTinyMce(true);
        }
        parent::_prepareLayout();
    }
}
