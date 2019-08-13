<?php
class Carotechs_Audittrack_Block_Audittrack extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getAudittrack()     
     { 
        if (!$this->hasData('audittrack')) {
            $this->setData('audittrack', Mage::registry('audittrack'));
        }
        return $this->getData('audittrack');
        
    }
}