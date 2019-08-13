<?php
class Carotechs_Audittrack_Block_Adminhtml_Audittrack extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_audittrack';
    $this->_blockGroup = 'audittrack';
    $this->_headerText = Mage::helper('audittrack')->__('Audit Tracks');
    $this->_addButtonLabel = Mage::helper('audittrack')->__('Add Audittrack');
    parent::__construct();
	$this->_removeButton('add');
  }
}