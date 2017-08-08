<?php

class Magentotutorial_Weblog_Block_Adminhtml_Weblog_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('weblog_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('Information page');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => 'About the page',
            'title' => 'About the page',
            'content' => $this->getLayout()
                ->createBlock('magentotutorial_weblog/adminhtml_weblog_edit_tab_form')
                ->toHtml()
        ));
        return parent::_beforeToHtml();
    }
}
