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

include_once("Mage/Adminhtml/controllers/Catalog/CategoryController.php");

class Carotechs_Audittrack_Catalog_CategoryController extends Mage_Adminhtml_Catalog_CategoryController
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
            if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Category"))
            {
                $cat = Mage::registry('category');
                $catId = $cat->getId();
                $_category = Mage::getModel('catalog/category')->load($catId);
                $name=   $_category->getName();
                $str="Id->".$catId.", Name->".$name;
                $now=date('Y-m-d');
                
			$audittrack_model=Mage::getModel('audittrack/audittrack');

			$data['log_action']='Updated';
			$data['adminrole']=$this->_roleName;
			$data['website']=$this->_store_name;				
			$data['updated_by']=$this->_userFirstname;				
			$data['log_comments']=$str;
			$data['log_entity']='Category';
			$audittrack_model->setData($data);
			$audittrack_model->save();			
        }
    }
        
     public function editAction()
     {
            //calling parent action.
            parent::editAction();
     }
     
     public function deleteAction()
     {
		 $this->variables();    
	
	        if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Category"))
            {
	            $_category_id  = $this->getRequest()->getParam('id');
                $_category = Mage::getModel('catalog/category')->load($_category_id);
                $url=   $_category->getUrl();
                $name=   $_category->getName();
                
                $str="Id->".$_category_id.", Name->".$name;
                $now=date('Y-m-d');
                
         	$audittrack_model=Mage::getModel('audittrack/audittrack');

			$data['log_action']='Deleted';
			$data['adminrole']=$this->_roleName;
			$data['website']=$this->_store_name;				
			$data['updated_by']=$this->_userFirstname;				
			$data['log_comments']=$str;
			$data['log_entity']='Category';
			$audittrack_model->setData($data);
			$audittrack_model->save();	
            }
             parent::deleteAction();
     }
}
?>