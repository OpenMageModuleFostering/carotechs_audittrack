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


include_once("Mage/Adminhtml/controllers/Catalog/Product/AttributeController.php");
class Carotechs_Audittrack_Catalog_Product_AttributeController extends Mage_Adminhtml_Catalog_Product_AttributeController
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
            //calling parent action.
            parent::saveAction();
	        $this->variables();
            if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Attribute"))
            { 
			$now=date('Y-m-d');
			 $id=$this->getRequest()->getParam('attribute_id');
				if($id!='')
				{
				    $model  = Mage::getModel('catalog/resource_eav_attribute');
					$_setattr = $model->load($id);
					$name=$_setattr->getAttributeCode();
					$str='Updated';				
					$msg="Id->".$id.", Code->".$name;					
				}
				else
				{
					$data=$this->getRequest()->getPost();
					$str='Added';
					$msg="Code->".$data['attribute_code'];
				}
					$audittrack_model=Mage::getModel('audittrack/audittrack');
		
					$data['log_action']=$str;
					$data['adminrole']=$this->_roleName;
					$data['website']=$this->_store_name;				
					$data['updated_by']=$this->_userFirstname;				
					$data['log_comments']=$msg;
					$data['log_entity']='Attribute';
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
      		  if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Attribute"))
          	  {
                $id = $this->getRequest()->getParam('attribute_id');
                $model = Mage::getModel('catalog/resource_eav_attribute');
                $_att = $model->load($id);
                $name=$_att->getName();
				$str="Id->".$id.", Code->".$name;
                $now=date('Y-m-d');
				$audittrack_model=Mage::getModel('audittrack/audittrack');
	
				$data['log_action']='Deleted';
				$data['adminrole']=$this->_roleName;
				$data['website']=$this->_store_name;				
				$data['updated_by']=$this->_userFirstname;				
				$data['log_comments']=$str;
				$data['log_entity']='Attribute';
				$audittrack_model->setData($data);
				$audittrack_model->save();
            }
             parent::deleteAction();
    }
}

?>