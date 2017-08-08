<?php

class Mycustom_Sales_Block_Adminhtml_Return_Edit_Detail extends Mage_Sales_Block_Items_Abstract {

    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('head')->addCss('css/bootstrap.css');
        $this->setTemplate('mycustom/sales/order/return/detail.phtml');
        return parent::_prepareLayout();
    }

}