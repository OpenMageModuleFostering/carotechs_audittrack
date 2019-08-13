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

include_once("Mage/Adminhtml/controllers/Catalog/ProductController.php");

class Carotechs_Audittrack_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
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
		 	$this->variables();
            $productId1 = $this->getRequest()->getParam('id');
            $this->_store_id= $this->getRequest()->getParam('store');
            //get product details from product id
            $model1 = Mage::getModel('catalog/product');
            $_product1 = $model1->setStoreId($this->_store_id)->load($productId1);
            $price =  $_product1->getPrice();

			  parent::saveAction();

      		$p_data = $this->getRequest()->getPost();
		    $current_price=$p_data['product']['price'];
	 
	        $pro = Mage::registry('product');
            $productId = $pro->getId();
            
            //get product details from product id
            $model = Mage::getModel('catalog/product');
            $_product = $model->setStoreId($this->_store_id)->load($productId);
            $name=   $_product->getName();
			$p_name=   $_product->getName();
            $sku=    $_product->getSku(); 
	
      if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Product"))
      {
  	 //echo $this->_userFirstname;die();
			if($price!='')
            {
				$msg='Updated';
			}
			else
            {
				$msg='Added';
			}
			$str="Id->".$productId.", "."Name->".$name.", "."Sku->".$sku;			
            $now=date('Y-m-d');
                //connect to Database
	            $audittrack_model=Mage::getModel('audittrack/audittrack');
				$data['log_action']=$msg;
				$data['adminrole']=$this->_roleName;
				$data['website']=$this->_store_name;
				$data['updated_by']=$this->_userFirstname;
				$data['log_comments']=$str;
				$data['log_entity']='Product';
				$audittrack_model->setData($data);
				$audittrack_model->save();

	   	   if(Mage::getStoreConfig("Carotechs_Audittrack/AudittrackPriceAlert/Productprice"))
		   {
			if($price!=$current_price)
			{
				//trans_email_ident_custom1_email
					/* Sender Name */
				$from=Mage::getStoreConfig('trans_email/ident_custom2/name');
				/* Sender Email */
				$to=Mage::getStoreConfig("Carotechs_Audittrack/AudittrackPriceAlert/Email");
				$subject='Product update notification.';
				$headers = 'From: BDS Sales <sales@backdropsource.com>' . "\r\n";
			    $headers .= "Content-type: text/html\r\n";
				$message = 'Product Details are changed for <b>'.$p_name.'</b><br/>';
				$message .= '<b> ID :</b>'.$productId.'<br/><b>Sku :</b>'.$sku.'<br/><b>Previous Price : </b>'.$price.'<br/><b>Current Price :</b>'.$current_price.'<br/><b>Website :</b>'.$this->_store_name;
				//echo $message;die();
				mail($to,$subject,$message,$headers);
			}
		 }
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
          if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Product"))
          {
                //get product id from parameter
                $productId      = $this->getRequest()->getParam('id');
                //get product details from product id
                $model = Mage::getModel('catalog/product');
                $_product = $model->setStoreId($this->_store_id)->load($productId);
                $name=   $_product->getName();
                $price=  $_product->getPrice();
                $url=    $_product->getProductUrl();
                $sku=    $_product->getSku();
                
                //get details of current user
                
                $str="Id->".$productId.", "."Name->".$name.", "."Sku->".$sku;
                $now=date('Y-m-d');
               
  	            $audittrack_model=Mage::getModel('audittrack/audittrack');
	
				$data['log_action']='Deleted';
				$data['adminrole']=$this->_roleName;
				$data['website']=$this->_store_name;				
				$data['updated_by']=$this->_userFirstname;				
				$data['log_comments']=$str;
				$data['log_entity']='Product';
				$audittrack_model->setData($data);
				$audittrack_model->save();
       }
      
   	   parent::deleteAction();  
    }
  
    public function massDeleteAction()
    {
	 $this->variables();
		if(Mage::getStoreConfig("Carotechs_Audittrack/Audittrack/Product"))
        {
			$productIds = $this->getRequest()->getParam('product');

			$str='Deleted Products: ';
			$now=date('Y-m-d');
			$i=1;
			
			foreach ($productIds as $productId)
			{
				$product = Mage::getSingleton('catalog/product')->setStoreId($this->_store_id)->load($productId);
				$str .= ' ('.$i.') '."Id->".$productId.", "."Name->".$product->getName().", "."Sku->".$product->getSku();
				$i++;
			}

			$audittrack_model=Mage::getModel('audittrack/audittrack');
			$data['log_action']='Mass Delete';
			$data['adminrole']=$this->_roleName;
			$data['website']=$this->_store_name;				
			$data['updated_by']=$this->_userFirstname;				
			$data['log_comments']=$str;
			$data['log_entity']='Product';
			$audittrack_model->setData($data);
			$audittrack_model->save();			
		}
		parent::massDeleteAction();
	}
	
	public function massStatusAction()
    {
		 parent::massStatusAction();
		 $this->variables();
		 $productIds = (array)$this->getRequest()->getParam('product');
		 $str='Updated Products: ';
		 $i=1;
			foreach ($productIds as $productId)
			{
				$product = Mage::getSingleton('catalog/product')->setStoreId($this->_store_id)->load($productId);
				$str .= ' ('.$i.') '."Id->".$productId.", "."Name->".$product->getName().", "."Sku->".$product->getSku();
				$i++;
			}
			$audittrack_model=Mage::getModel('audittrack/audittrack');
			$data['log_action']='Mass Status Update';
			$data['adminrole']=$this->_roleName;
			$data['website']=$this->_store_name;				
			$data['updated_by']=$this->_userFirstname;				
			$data['log_comments']=$str;
			$data['log_entity']='Product';
			$audittrack_model->setData($data);
			$audittrack_model->save();	
	}
}
?>