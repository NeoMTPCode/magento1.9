<?php
//
//$installer = $this;
//$installer->startSetup();
//if ($installer->getConnection()->isTableExists($installer->getTable('display_customgrid/helpcenter')) != true) {
//    $table = $installer->getConnection()
//        ->newTable($installer->getTable('display_customgrid/helpcenter'))
//        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
//            'identity' => true,
//            'unsigned' => true,
//            'nullable' => false,
//            'primary' => true,
//            'auto_increment' => true
//        ), 'Id')
//        ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
//            'nullable' => false,
//        ), 'Title')
//        ->addColumn('content', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
//            'nullable' => false,
//        ), 'Content')
//        ->addColumn('url', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
//            'nullable' => false,
//        ), 'Url')
//        ->addColumn('create_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
//            'nullable' => false,
//            'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT
//        ), 'Create At')
//        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
//            'nullable' => false,
//            "default" => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE
//        ), 'Update At')
//        ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
//            'unsigned' => true,
//            'nullable' => false,
//        ), 'Store ID');
//
//    $installer->getConnection()->createTable($table);
//}
//$installer->endSetup();

$installer = $this;
$installer->startSetup();
$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('helpcenter')};
    CREATE TABLE `{$installer->getTable('helpcenter')}` (
      `entity_id` int(11) NOT NULL auto_increment,
      `title` text,
      `content` text,
      `url` text default NULL,
      `create_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
      `updated_at` timestamp NOT NULL default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `store_id` int(11),
      PRIMARY KEY  (`entity_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();