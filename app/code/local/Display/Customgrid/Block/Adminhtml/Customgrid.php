<?php
class Display_Customgrid_Block_Adminhtml_Customgrid extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct()
    {
        $this->_controller = 'adminhtml_customgrid'; //locations of the grid.php
        $this->_blockGroup = 'display_customgrid';
        $this->_headerText = Mage::helper('customgrid')->__('Manage Page');
        $this->_addButtonLabel = Mage::helper('customgrid')->__('Add Page');
        parent::__construct();
    }


}