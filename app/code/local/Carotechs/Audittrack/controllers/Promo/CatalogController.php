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
include_once("Mage/Adminhtml/controllers/Promo/CatalogController.php");

class Carotechs_Audittrack_Promo_CatalogController extends Mage_Adminhtml_Promo_CatalogController
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
            if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Promocat"))
            { 
				$audittrack_model=Mage::getModel('audittrack/audittrack');
                $str='';
				$data=$this->getRequest()->getPost();
				if($data['rule_id']!='')
				{
					$id=$data['rule_id'];
					$model = Mage::getModel('catalogrule/rule')->load($id);
					$name = $model->getName();
					$str="Id->".$id.", Name->".$name;
					$msg='Updated';
				}				
				else
				{
					$msg='Added';
					$str="Name->".$data['name'];
				}
                $now=date('Y-m-d');
				$data['log_date']=$now;
				$data['log_action']=$msg;
				$data['adminrole']=$this->_roleName;
				$data['website']=$this->_store_name;				
				$data['updated_by']=$this->_userFirstname;				
				$data['log_comments']=$str;
				$data['log_entity']='Catalog Price Rule';
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
           if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Promocat"))
           {
	            $audittrack_model=Mage::getModel('audittrack/audittrack');
			    $id = $this->getRequest()->getParam('id');
                $model = Mage::getModel('catalogrule/rule')->load($id);
                $name = $model->getName();
                $str="Id->".$id.", Name->".$name;
                $now=date('Y-m-d');
				$data['log_date']=$now;
				$data['log_action']='Deleted';
				$data['adminrole']=$this->_roleName;
				$data['website']=$this->_store_name;				
				$data['updated_by']=$this->_userFirstname;				
				$data['log_comments']=$str;
				$data['log_entity']='Catalog Price Rule';
				$audittrack_model->setData($data);
				$audittrack_model->save();

         }
         parent::deleteAction();
    }
}