<?php
$installer = $this;
$installer->startSetup();
$installer->run("DROP TABLE IF EXISTS {$this->getTable('caro_log_details')};
CREATE TABLE `caro_log_details` (
  `log_id` int(11) NOT NULL auto_increment,
  `log_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `log_action` varchar(50) NOT NULL,
  `adminrole` varchar(25) NOT NULL,
  `website` varchar(25) NOT NULL,  
  `updated_by` varchar(25) NOT NULL,
  `log_comments` varchar(255) NOT NULL,
  `log_entity` varchar(50) NOT NULL,
  PRIMARY KEY  (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
");
$installer->endSetup();
?>