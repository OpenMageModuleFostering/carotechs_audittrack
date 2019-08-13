<?php
class Carotechs_Audittrack_Model_Mysql4_Audittrack extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
      // Note that the audittrack_id refers to the key field in your database table.
      $this->_init('audittrack/audittrack', 'log_id');
    }
}