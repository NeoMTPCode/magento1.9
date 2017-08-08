<?php

class Mycustom_Sales_Block_Adminhtml_Return_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('return_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('Information page');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => 'About the page',
            'title' => 'About the page',
            'content' => $this->getLayout()
                ->createBlock('mycustom_sales/adminhtml_return_edit_tab_form')
                ->toHtml()
        ));

        $this->addTab('form_section2', array(
            'label' => 'Product Detail',
            'title' => 'Product Detail',
            'content' => $this->getLayout()
                ->createBlock('mycustom_sales/adminhtml_return_edit_detail')
                ->toHtml()
        ));

        return parent::_beforeToHtml();
    }
}
