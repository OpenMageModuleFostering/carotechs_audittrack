<?php
class Carotechs_Audittrack_Block_Adminhtml_Audittrack_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('audittrackGrid');
      $this->setDefaultSort('log_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('audittrack/audittrack')->getCollection()->setOrder('log_date');
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('log_id', array(
          'header'    => Mage::helper('audittrack')->__('ID'),
          'align'     =>'left',
          'index'     => 'log_id',
		  'width'	  => '20px'
      ));
      $this->addColumn('log_date', array(
          'header'    => Mage::helper('audittrack')->__('Date'),
          'align'     =>'left',
		  'type'	  => 'datetime',
          'index'     => 'log_date',
		  'width'	  => '180px'		  
      ));
      $this->addColumn('log_entity', array(
          'header'    => Mage::helper('audittrack')->__('Entity'),
          'align'     =>'left',
          'index'     => 'log_entity',
		  'width'	  => '180px'		  		  
      ));
      $this->addColumn('log_action', array(
          'header'    => Mage::helper('audittrack')->__('Action'),
          'align'     =>'left',
          'index'     => 'log_action',
		  'width'	  => '180px'
      ));
      $this->addColumn('log_comments', array(
          'header'    => Mage::helper('audittrack')->__('Details'),
          'align'     =>'left',
          'index'     => 'log_comments',
      ));
      $this->addColumn('updated_by', array(
          'header'    => Mage::helper('audittrack')->__('Updated By'),
          'align'     =>'left',
          'index'     => 'updated_by',
      ));
      $this->addColumn('adminrole', array(
          'header'    => Mage::helper('audittrack')->__('Role'),
          'align'     =>'left',
          'index'     => 'adminrole',
      ));
      $this->addColumn('website', array(
          'header'    => Mage::helper('audittrack')->__('Website'),
          'align'     =>'left',
          'index'     => 'website',
      ));
	$this->addExportType('*/*/exportCsv', Mage::helper('audittrack')->__('CSV'));
	$this->addExportType('*/*/exportXml', Mage::helper('audittrack')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('log_id');
        $this->getMassactionBlock()->setFormFieldName('audittrack');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('audittrack')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('audittrack')->__('Are you sure?')
        ));

        return $this;
    }

  public function getRowUrl($row)
  {
      return "";//$this->getUrl('*/*/edit', array('id' => $row->getId()));
  }
}