<?php
/*------------------------------------------------------------------------
# Websites: http://www.magentothem.com/
-------------------------------------------------------------------------*/ 
$installer = $this;

$installer->startSetup();
try {
	$installer->run("

	-- DROP TABLE IF EXISTS {$this->getTable('banner7')};
	CREATE TABLE {$this->getTable('banner7')} (
	  `banner7_id` int(11) unsigned NOT NULL auto_increment,
	  `title` varchar(255) NOT NULL default '',
	  `title2` varchar(255) NOT NULL default '',
	  `title3` varchar(255) NOT NULL default '',
	  `link` varchar(255) NOT NULL default '',
	  `image` varchar(255) NOT NULL default '',
	  `image2` varchar(255) NOT NULL default '',
	  `image3` varchar(255) NOT NULL default '',
	  `order` smallint(6) NOT NULL default '0',
	  `store_id` varchar(255) NOT NULL default '',
	  `description` text NOT NULL default '',
	  `status` smallint(6) NOT NULL default '0',
	  `created_time` datetime NULL,
	  `update_time` datetime NULL,
	  PRIMARY KEY (`banner7_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	");
	
	$installer->getConnection()->insertMultiple(
		$installer->getTable('admin/permission_block'),
		array(
				array('block_name' => 'banner7/banner7', 'is_allowed' => 1),
				array('block_name' => 'bestsellerproductslider/bestsellerproductslider', 'is_allowed' => 1),
				array('block_name' => 'featuredproductslider/featuredproductslider', 'is_allowed' => 1),
				array('block_name' => 'newproductslider/newproductslider', 'is_allowed' => 1),
				array('block_name' => 'testimonial/sidebar', 'is_allowed' => 1),
				array('block_name' => 'bestsellerproductlist/bestsellerproductlist', 'is_allowed' => 1),
				array('block_name' => 'brandslider/brandslider', 'is_allowed' => 1),
				array('block_name' => 'blog/menu_sidebar', 'is_allowed' => 1),
				array('block_name' => 'newproductlist/newproductlist', 'is_allowed' => 1),
				array('block_name' => 'catalog/product_list_related', 'is_allowed' => 1),
				array('block_name' => 'timer/timer', 'is_allowed' => 1),
				array('block_name' => 'newsletter/subscribe', 'is_allowed' => 1),
				array('block_name' => 'catlist/catlist', 'is_allowed' => 1),
				array('block_name' => 'vmegamenu/vmegamenu', 'is_allowed' => 1),
				array('block_name' => 'verticalmenu/verticalmenu', 'is_allowed' => 1),
			)
		);
} catch (Exception $e) {
    Mage::logException($e);
}

$installer->endSetup();