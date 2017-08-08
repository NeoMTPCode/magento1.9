<?php

class Mycustom_Helpcenter_Block_Adminhtml_Helpcenter_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('helpcenter');
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(array('tab_id' => 'form_section'));
        $wysiwygConfig["files_browser_window_url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index');
        $wysiwygConfig["directives_url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive');
        $wysiwygConfig["directives_url_quoted"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive');
        $wysiwygConfig["widget_window_url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index');
        $wysiwygConfig["files_browser_window_width"] = (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_width');
        $wysiwygConfig["files_browser_window_height"] = (int) Mage::getConfig()->getNode('adminhtml/cms/browser/window_height');

        $plugins = $wysiwygConfig->getData("plugins");
        $plugins[0]["options"]["url"] = Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/system_variable/wysiwygPlugin');
        $plugins[0]["options"]["onclick"]["subject"] = "MagentovariablePlugin.loadChooser('".Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/system_variable/wysiwygPlugin')."', '{{html_id}}');";
        $plugins = $wysiwygConfig->setData("plugins",$plugins);

        $fieldset = $form->addFieldset('helpcenter_form',
            array('legend' => Mage::helper('helpcenter')->__('Page Information')));
        $fieldset->addField('title', 'text',
            array(
                'label' => Mage::helper('helpcenter')->__('Title'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'title'
        ));
        $fieldset->addField('url', 'text',
            array(
                'label' => Mage::helper('helpcenter')->__('Url'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'url'
            ));
        $fieldset->addField('store_id', 'multiselect',
            array(
                'label' => Mage::helper('helpcenter')->__('Store Id'),
                'required' => true,
                'name' => 'stores[]',
                'values' => Mage::getSingleton('adminhtml/system_store')
                    ->getStoreValuesForForm(false, true)
            ));
        $fieldset->addField('content', 'editor',
            array(
                'title'     => Mage::helper('helpcenter')->__('Content'),
                'label' => Mage::helper('helpcenter')->__('Content'),
                'class' => 'required-entry',
                'style' => 'width:900px; height:500px;',
                'wysiwyg' => true,
                'required' => true,
                'state' => 'html',
                'config' => $wysiwygConfig,
                'name' => 'content'
        ));


        $this->setForm($form);
        $form->setValues(Mage::registry('helpcenter_data')->getData());
        return parent::_prepareForm();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

}