<?php

class Magentothem_Quickview_Block_Quickview extends Mage_Core_Block_Template
{

    const SYSTEM_FOLDER_CONFIG_IMAGE            = "magentothem/";
    const SYSTEM_PATH_CONFIG_ENABLE             = "quickview/quickview_config/enabled";
    const SYSTEM_PATH_CONFIG_IMAGE              = "quickview/quickview_config/image_upload";
    const SYSTEM_PATH_CONFIG_ELEMENT_CONTAINER  = "quickview/quickview_config/element_container";
    const SYSTEM_PATH_CONFIG_CATEGORY_ENABLE    = "quickview/quickview_config/category_enabled";
    const SYSTEM_PATH_CONFIG_PARENT_ELEMENT     = "quickview/quickview_config/parent_element";
    const SYSTEM_PATH_CONFIG_CHILDREN_ELEMENT   = "quickview/quickview_config/children_element";

    public function getAjaxLoadImage()
    {
        return Mage::getBaseUrl('media').self::SYSTEM_FOLDER_CONFIG_IMAGE.Mage::getStoreConfig(self::SYSTEM_PATH_CONFIG_IMAGE);
    }

    public function isEnabled()
    {
        $result = false;
        $config_enable = Mage::getStoreConfig(self::SYSTEM_PATH_CONFIG_ENABLE);
        if($config_enable == "1") {
            $result = true;
        }

        return $result;
    }

    public function isCategoryEnabled()
    {
        $result = false;
        $config_enable = Mage::getStoreConfig(self::SYSTEM_PATH_CONFIG_CATEGORY_ENABLE);
        if($config_enable == "1") {
            $result = true;
        }

        return $result;
    }

    public function getParentElement()
    {
        $result = Mage::getStoreConfig(self::SYSTEM_PATH_CONFIG_PARENT_ELEMENT);

        return $result;
    }

    public function getChildrenElement()
    {
        $result = Mage::getStoreConfig(self::SYSTEM_PATH_CONFIG_CHILDREN_ELEMENT);

        return $result;
    }

    public function getQuickViewElements()
    {
        $elements = array();
        $config_elements = Mage::getStoreConfig(self::SYSTEM_PATH_CONFIG_ELEMENT_CONTAINER);
        if($config_elements && $config_elements != '') {
            $elements = explode(";", $config_elements);
        }

        return $elements;
    }

}