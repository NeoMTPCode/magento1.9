<?php

class Mycustom_Sales_Block_Adminhtml_Return_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('sales');

        $fieldset = $form->addFieldset('sales_form',
            array('legend' => Mage::helper('sales')->__('Page Information')));
        $fieldset->addField('order_id', 'text',
            array(
                'label' => Mage::helper('sales')->__('Order Id'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'order_id'
        ));

        $fieldset->addField('reason', 'select',
            array(
                'label' => Mage::helper('sales')->__('Reason'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'reason',
                'values' => array('Thay đổi thông tin đơn hàng', 'Chọn nhầm sản phẩm', 'Sản phẩm lỗi')
        ));

        $fieldset->addField('note', 'textarea',
            array(
                'label' => Mage::helper('sales')->__('Note'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'note'
        ));

        $fieldset->addField('type', 'select',
            array(
                'label' => Mage::helper('sales')->__('Type'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'type',
                'values' => array('Đổi sản phẩm cùng loại', 'Hoàn tiền qua số thẻ quý khách sử dụng để thanh toán', 'Hoàn trả tiền cho bạn')
        ));

        $fieldset->addField('method', 'select',
            array(
                'label' => Mage::helper('sales')->__('Method'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'method',
                'values' => array('Đến đơn vị trả hàng', 'Đổi trả hàng tại bưu cục')
        ));

        $fieldset->addField('store_id', 'multiselect',
            array(
                'label' => Mage::helper('sales')->__('Store Id'),
                'required' => true,
                'name' => 'stores[]',
                'values' => Mage::getSingleton('adminhtml/system_store')
                    ->getStoreValuesForForm(false, true)
            ));

        $this->setForm($form);
        $form->setValues(Mage::registry('return_data')->getData());
        return parent::_prepareForm();
    }
}