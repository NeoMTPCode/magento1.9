<?php
/**
 * Created by PhpStorm.
 * User: Hieu
 * Date: 15/09/2014
 * Time: 11:47
 */
$installer = $this;

$connection = $installer->getConnection();
$installer->startSetup();
$data = array(
	array('process_baokim', 'Process BaoKim'),
	array('complete_baokim', 'Complete BaoKim')
);
$connection = $installer->getConnection()->insertArray(
	$installer->getTable('sales/order_status'),
	array('status', 'label'),
	$data
);
$installer->endSetup();