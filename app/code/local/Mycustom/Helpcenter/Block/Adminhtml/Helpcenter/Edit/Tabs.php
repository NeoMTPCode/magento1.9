<?php

class Mycustom_Helpcenter_Block_Adminhtml_Helpcenter_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('helpcenter_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('Information page');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => 'About the page',
            'title' => 'About the page',
            'content' => $this->getLayout()
                ->createBlock('mycustom_helpcenter/adminhtml_helpcenter_edit_tab_form')
                ->toHtml()
        ));

        return parent::_beforeToHtml();
    }
}
