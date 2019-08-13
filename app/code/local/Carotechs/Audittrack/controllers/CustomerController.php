<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer admin controller
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
include_once("Mage/Adminhtml/controllers/CustomerController.php");

class Carotechs_Audittrack_CustomerController extends Mage_Adminhtml_CustomerController
{
    /**
     * Save customer action
     */
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
	  
      if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Customer"))
      {
		  $data = $this->getRequest()->getPost();
            //get product id from registry
            $customer = Mage::registry('current_customer');
            $customerId = $customer->getId();
            
            //get product details from product id
            $model = Mage::getModel('customer/customer');
            $customer = $model->load($customerId);
            $email = $customer->getEmail();  // To get Email Address of a customer.
            
            $firstname = $customer->getFirstname();  // To get Firstname of a customer.
            $lastname= $customer->getLastname();
            //get details of current user

             $str='Id->'.$customerId.', Name->'.$firstname.' '.$lastname;
             $now=date('Y-m-d');
            
			$audittrack_model=Mage::getModel('audittrack/audittrack');

			$data['log_action']='Updated';
			$data['adminrole']=$this->_roleName;
			$data['website']=$this->_store_name;				
			$data['updated_by']=$this->_userFirstname;				
			$data['log_comments']=$str;
			$data['log_entity']='Customer';
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
        if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Customer"))
        {
            $customerId = $this->getRequest()->getParam('id');
            $model = Mage::getModel('customer/customer');
            $customer = $model->load($customerId);
            $email = $customer->getEmail();  // To get Email Address of a customer.
            
            $firstname = $customer->getFirstname();  // To get Firstname of a customer.
            $lastname= $customer->getLastname();

            $str='Id->'.$customerId.', Name->'.$firstname.' '.$lastname;
            $now=date('Y-m-d');
			
			$audittrack_model=Mage::getModel('audittrack/audittrack');

			$data['log_action']='Deleted';
			$data['adminrole']=$this->_roleName;
			$data['website']=$this->_store_name;				
			$data['updated_by']=$this->_userFirstname;				
			$data['log_comments']=$str;
			$data['log_entity']='Customer';
			$audittrack_model->setData($data);
			$audittrack_model->save();	
       }
       parent::deleteAction();
    }

    public function massDeleteAction()
    {
	 $this->variables();
		if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Customer"))
        {
			$customersIds = $this->getRequest()->getParam('customer');

			$str='Deleted Customers: ';
			$now=date('Y-m-d');
			$i=1;
			foreach ($customersIds as $customerId)
			{
				$customer = Mage::getSingleton('customer/customer')->load($customerId);
				$str .= ' ('.$i.') '."Id->".$customerId.", "."Name->".$customer->getName();
				$i++;
			}
			$audittrack_model=Mage::getModel('audittrack/audittrack');

			$data['log_action']='Mass Delete';
			$data['adminrole']=$this->_roleName;
			$data['website']=$this->_store_name;				
			$data['updated_by']=$this->_userFirstname;				
			$data['log_comments']=$str;
			$data['log_entity']='Customer';
			$audittrack_model->setData($data);
			$audittrack_model->save();	
		}
		parent::massDeleteAction();
	}
}