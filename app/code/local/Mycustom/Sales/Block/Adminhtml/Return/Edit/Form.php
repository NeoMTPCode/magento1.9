<?php
class Mycustom_Sales_Block_Adminhtml_Return_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))
                ),
                'method' => 'post',
            )
        );
        $form->setUseContainer(true);
        $this->setForm($form);

//        $fieldset = $form->addFieldset(
//            'general',
//            array(
//                'legend' => $this->__('Informations menu')
//            )
//        );
//        $menu = $menu = Mage::registry('current_menu'); //controller must return 'current_menu'
//
//        //for editing
//        if ($id = $menu->getId()) {
//            $fieldset->addField('entity_id', 'hidden', array(
//                'name' => 'entity_id',
//                'value' => $id
//            ));
//        }
//
//        $fieldset->addField('title', 'text', array(
//            'label' => $this->__('Nom'),
//            'name'  => 'title',
//            'required' => true,
//            'value' => $menu->getName()
//        ));
//
//        $form->setValues($menu->getData());
//
//        $form->setUseContainer(true);
//
//        $this->setForm($form);


        return parent::_prepareForm();
    }
}
