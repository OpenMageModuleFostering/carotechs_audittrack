<?php

/**
 * Carotechs Audittrack Extension
 *
 *
 * @category   Carotechs
 * @package    Carotechs_Audittrack
 * @author     Carotechs
 * @copyright  Copyright (c) 2011 Carotechs. 

 */
include_once("Mage/Adminhtml/controllers/Catalog/Product/SetController.php");

class Carotechs_Audittrack_Catalog_Product_SetController extends Mage_Adminhtml_Catalog_Product_SetController
{
 	protected function _construct()
    {
		 $this->_resource = Mage::getSingleton('core/resource');
         $this->_writeConnection = $this->_resource->getConnection('core_write');
	}

	public function variables()
	{
         $this->_user=Mage::getSingleton('admin/session')->getUser();
         $this->_userFirstname = $this->_user->getUsername();
		 $this->_roleId = implode('', $this->_user->getRoles());
		 $this->_roleName = Mage::getModel('admin/roles')->load($this->_roleId)->getRoleName();
		 $this->_store_id= $this->getRequest()->getParam('store');
	     $this->_store_name=Mage::getModel('core/store')->load($this->_store_id)->getName();
 		 if($this->_store_name=='')
	 	 $this->_store_name='default';
	}

     public function saveAction()
         {
            parent::saveAction();
            $this->variables();
			$now=date('Y-m-d');
            if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Attributeset"))
            {
				$id=$this->getRequest()->getParam('id');
				if($id!='')
				{
				    $model  = Mage::getModel('eav/entity_attribute_set');
					$_setattr = $model->load($id);
					$name=$_setattr->getAttributeSetName();
					$str='Updated';
					$msg="Id->".$id.", Name->".$name;					
				}
				else
				{
					$data=$this->getRequest()->getPost();
					$str='Added';
					$msg="Code->".$data['attribute_set_name'];
				}
					$audittrack_model=Mage::getModel('audittrack/audittrack');
		
					$data['log_action']=$str;
					$data['adminrole']=$this->_roleName;
					$data['website']=$this->_store_name;				
					$data['updated_by']=$this->_userFirstname;				
					$data['log_comments']=$msg;
					$data['log_entity']='Attribute Set';
					$audittrack_model->setData($data);
					$audittrack_model->save();	
				}
     }
     
     public function editAction()
     {
            parent::editAction();
     }
     
     public function deleteAction()
       {
         $this->variables();
	     if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Attributeset"))
            {
                $id = $this->getRequest()->getParam('id');
                $model  = Mage::getModel('eav/entity_attribute_set');
                $_setattr = $model->load($id);
                $name=   $_setattr->getAttributeSetName();
				$str="Id->".$id.", Name->".$name;
                $now=date('Y-m-d');
				$audittrack_model=Mage::getModel('audittrack/audittrack');
	
				$data['log_action']='Deleted';
				$data['adminrole']=$this->_roleName;
				$data['website']=$this->_store_name;				
				$data['updated_by']=$this->_userFirstname;				
				$data['log_comments']=$str;
				$data['log_entity']='Attribute Set';
				$audittrack_model->setData($data);
				$audittrack_model->save();	
            }
            parent::deleteAction();
       }
}