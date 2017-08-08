<?php

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