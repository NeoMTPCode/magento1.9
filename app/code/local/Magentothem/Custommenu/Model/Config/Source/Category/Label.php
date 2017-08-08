<?php
class Magentothem_Custommenu_Model_Config_Source_Category_Label extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{	
	public function getAllOptions()
	{
		if (!$this->_options)
		{
            return array(
                array('value'=>'', 'label'=>Mage::helper('adminhtml')->__('None')),
                array('value'=>'sale', 'label'=>Mage::helper('adminhtml')->__('sale')),
                array('value'=>'new', 'label'=>Mage::helper('adminhtml')->__('new')),
            );
        }
		return $this->_options;
    }
}