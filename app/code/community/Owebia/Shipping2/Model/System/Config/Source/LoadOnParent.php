<?php
/**
 * Copyright © 2008-2016 Owebia. All rights reserved.
 * See COPYING.txt for license details.
 */

class Owebia_Shipping2_Model_System_Config_Source_LoadOnParent
    extends Mage_Adminhtml_Model_System_Config_Source_Category
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray($addEmpty = true)
    {
        $options = array(
            array(
                'label' => Mage::helper('owebia_shipping2')->__('Self'),
                'value' => '0'
            ),
            array(
                'label' => Mage::helper('owebia_shipping2')->__('Parent'),
                'value' => '1'
            ),
        );
        return $options;
    }
}
