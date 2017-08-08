<?php
/*------------------------------------------------------------------------
# Websites: http://www.magentothem.com/
-------------------------------------------------------------------------*/ 
class Magentotutorial_Weblog_Block_Adminhtml_Weblog extends Mage_Adminhtml_Block_Widget_Grid_Container{

    public function __construct()
    {
        parent::__construct();

        $this->_controller = 'adminhtml_weblog';
        $this->_blockGroup = 'magentotutorial_weblog';
        $this->_headerText = Mage::helper('weblog')->__('Item Manager');
//        $this->_addButtonLabel = Mage::helper('weblog')->__('Add Item');
        $this->_removeButton('add');

//        $this->_addButton('save', array(
//            'label' => $this->_saveButtonLabel,
//            'onclick' => 'categorySubmit(\'' . $this->getSaveUrl() . '\')',
//            'class' => 'Save',
//        ));
    }


    public function getSaveUrl() {
        return $this->getUrl('*/*/save', array('store' => $this->getRequest()->getParam('store')));
    }

    protected function _afterToHtml($html) {
        return $this->_prependHtml() . parent::_afterToHtml($html) . $this->_appendHtml();
    }

    private function _prependHtml() {
        $html = '
    	<form id="featured_edit_form" action="' . $this->getSaveUrl() . '" method="post" enctype="multipart/form-data">
    	<input name="form_key" type="hidden" value="' . $this->getFormKey() . '" />
    		<div class="no-display">
        		<input type="hidden" name="featured_products" id="in_featured_products" value="" />
    		</div>
		</form>
    	';

        return $html;
    }

    private function _appendHtml() {
        $html = '';
        return $html;
    }

    public function getHeaderHtml() {
        return '<h3 style="background-image: url(' . $this->getSkinUrl('images/product_rating_full_star.gif') . ');" class="' . $this->getHeaderCssClass() . '">' . $this->getHeaderText() . '</h3>';
    }

    protected function _prepareLayout() {
        $this->setChild('store_switcher', $this->getLayout()->createBlock('adminhtml/store_switcher', 'store_switcher')->setUseConfirm(false)
        );
        return parent::_prepareLayout();
    }

    public function getGridHtml() {
        return $this->getChildHtml('store_switcher') . $this->getChildHtml('grid');
    }
}