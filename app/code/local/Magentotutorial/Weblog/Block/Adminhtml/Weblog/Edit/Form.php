<?php
class Magentotutorial_Weblog_Block_Adminhtml_Weblog_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {

        //DELETE TAB LEFT
//        $form = new Varien_Data_Form(
//            array(
//                'id' => 'edit_form',
//                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))
//                ),
//                'method' => 'post',
//            )
//        );
//        $form->setUseContainer(true);
//        $this->setForm($form);
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('weblog');

        $fieldset = $form->addFieldset('weblog_form',
            array('legend' => Mage::helper('weblog')->__('Page Information')));
        $fieldset->addField('sku', 'text',
            array(
                'label' => Mage::helper('weblog')->__('SKU'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'sku',
                'readonly' => true
            ));
        $fieldset->addField('price', 'text',
            array(
                'label' => Mage::helper('weblog')->__('Price'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'price'
            ));

        $fieldset->addField('final_price', 'text',
            array(
                'label' => Mage::helper('weblog')->__('Final Price'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'final_price'
            ));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset->addField('from_date', 'date',
            array(
                'label' => Mage::helper('weblog')->__('From Date'),
                'class' => 'required-entry',
                'time'      =>    true,
                'image'  => $this->getSkinUrl('images/grid-cal.gif'),
                'required' => true,
                'format'    =>    $dateFormatIso,
                'input_format' =>  $dateFormatIso,
                'name' => 'from_date'
            ));
        $fieldset->addField('to_date', 'date',
            array(
                'label' => Mage::helper('weblog')->__('To Date'),
                'class' => 'required-entry',
                'time'      =>    true,
                'image'  => $this->getSkinUrl('images/grid-cal.gif'),
                'required' => true,
                'format'    =>    $dateFormatIso,
                'input_format' =>  $dateFormatIso,
                'name' => 'to_date'
            ));
//        $fieldset->addField('store_id', 'multiselect',
//            array(
//                'label' => Mage::helper('weblog')->__('Store Id'),
//                'required' => true,
//                'name' => 'stores[]',
//                'values' => Mage::getSingleton('adminhtml/system_store')
//                    ->getStoreValuesForForm(false, true)
//            ));

        $fieldset->addField('updated_at', 'date',
            array(
                'label' => Mage::helper('weblog')->__('Updated At'),
                'class' => 'required-entry',
                'time'      =>    true,
                'image'  => $this->getSkinUrl('images/grid-cal.gif'),
                'readonly' => true,
                'format'    =>    $dateFormatIso,
                'input_format' =>  $dateFormatIso,
                'name' => 'updated_at'
            ));
        $this->setForm($form);
        Mage::registry('weblog_data')->setData('sku',
            Mage::getModel('catalog/product')->load(Mage::registry('weblog_data')->getData('entity_id'))->getSku());
        $form->setValues(Mage::registry('weblog_data')->getData());
        return parent::_prepareForm();
    }
}
