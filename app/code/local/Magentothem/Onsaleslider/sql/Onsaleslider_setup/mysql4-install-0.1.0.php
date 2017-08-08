<?php
$installer = new Mage_Eav_Model_Entity_Setup('core_setup');
try {
	$installer->getConnection()->insertMultiple(
		$installer->getTable('admin/permission_block'),
		array(
				array('block_name' => 'onsaleslider/onsaleslider', 'is_allowed' => 1),
			)
		);
} catch (Exception $e) {
    Mage::logException($e);
}

$installer->endSetup();