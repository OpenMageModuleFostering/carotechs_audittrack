<?php
class Carotechs_Audittrack_Adminhtml_AudittrackController extends Mage_Adminhtml_Controller_action
{
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('audittrack/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
	public function indexAction()
	{
		$this->_initAction()->renderLayout();
	}
    public function exportCsvAction()
    {
        $fileName   = 'audittrack.csv';
        $content    = $this->getLayout()->createBlock('audittrack/adminhtml_audittrack_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }
    public function exportXmlAction()
    {
        $fileName   = 'audittrack.xml';
        $content    = $this->getLayout()->createBlock('audittrack/adminhtml_audittrack_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }
	
	    public function massDeleteAction() {
        $audittrackIds = $this->getRequest()->getParam('audittrack');
        if(!is_array($audittrackIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($audittrackIds as $audittrackId) {
                    $audittrack = Mage::getModel('audittrack/audittrack')->load($audittrackId);
                    $audittrack->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($audittrackIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}