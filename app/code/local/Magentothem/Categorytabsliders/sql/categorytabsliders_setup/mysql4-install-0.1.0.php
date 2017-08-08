<?php
$this->startSetup();

$this->addAttribute('catalog_category', 'cattab_image', array(
    'group'                    => 'General Information',
    'label'                    => 'Category Tab Image',
    'input'                    => 'image',
    'type'                     => 'varchar',
    'backend'                  => 'catalog/category_attribute_backend_image',
    'global'                   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'                  => true,
    'required'                 => false,
    'user_defined'             => true,
    'order'                    => 22
));

$this->endSetup();