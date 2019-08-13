<?php
class Carotechs_Audittrack_Model_Mysql4_Audittrack_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('audittrack/audittrack');
    }
}