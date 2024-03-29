<?php

class Virtualpayer_Authipayredirect_Adminhtml_AuthipayredirectController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('authipayredirect/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Authipay Transactions Manager'), Mage::helper('adminhtml')->__('Authipay Transactions Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('authipayredirect/authipayredirect')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('authipayredirect_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('authipayredirect/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('authipayredirect Manager'), Mage::helper('adminhtml')->__('authipayredirect Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Code News'), Mage::helper('adminhtml')->__('Code News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('authipayredirect/adminhtml_authipayredirect_edit'))
				->_addLeft($this->getLayout()->createBlock('authipayredirect/adminhtml_authipayredirect_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('authipayredirect')->__('Code does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
	
	public function uploadAction() {
			$this->loadLayout();
			$this->_setActiveMenu('authipayredirect/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('authipayredirect Manager'), Mage::helper('adminhtml')->__('authipayredirect Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Code Import'), Mage::helper('adminhtml')->__('Code Import'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('authipayredirect/adminhtml_authipayredirect_import'))
				->_addLeft($this->getLayout()->createBlock('authipayredirect/adminhtml_authipayredirect_import_tabs'));

			$this->renderLayout();
	}
	
	public function importCSV(){
		try {	
			$uploader = new Varien_File_Uploader('filename');
       		$uploader->setAllowedExtensions(array('csv'));
			$uploader->setAllowRenameFiles(false);
			$uploader->setFilesDispersion(false);
					
			$path = Mage::getBaseDir('media') . DS ;
			$uploader->save($path, $_FILES['filename']['name'] );

			$row = 1;
			if (($handle = fopen($path . $_FILES['filename']['name'], "r")) !== FALSE) {
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			    	if($row == 1){
				        $num = count($data);				        
				        $headings = array();
				        for ($c=0; $c < $num; $c++) {
							$headings[$c] = $data[$c];
						}
				    	$row++;
				    	continue;
			    	}else{
    					$model = Mage::getModel('authipayredirect/authipayredirect');
				        $row++;
				        $num = count($data);				        
				        for ($c=0; $c < $num; $c++) {
		            		$model->setData($headings[$c], $data[$c]);
		            	}
						$model->save();
				    }
			    }
			    fclose($handle);
			}else{
		        Mage::throwException('File Not Found');
			}
			
		} catch (Exception $e) {
      		Mage::throwException($e->getMessage());
        }
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				$this->importCSV();
				$this->_redirect('*/*/');
				return;
			}
	  			
			$model = Mage::getModel('authipayredirect/authipayredirect');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('authipayredirect')->__('Code was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('authipayredirect')->__('Unable to find code to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('authipayredirect/authipayredirect');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Code was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $authipayredirectIds = $this->getRequest()->getParam('authipayredirect');
        if(!is_array($authipayredirectIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select code(s)'));
        } else {
            try {
                foreach ($authipayredirectIds as $authipayredirectId) {
                    $authipayredirect = Mage::getModel('authipayredirect/authipayredirect')->load($authipayredirectId);
                    $authipayredirect->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d code(s) were successfully deleted', count($authipayredirectIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $authipayredirectIds = $this->getRequest()->getParam('authipayredirect');
        if(!is_array($authipayredirectIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select code(s)'));
        } else {
            try {
                foreach ($authipayredirectIds as $authipayredirectId) {
                    $authipayredirect = Mage::getSingleton('authipayredirect/authipayredirect')
                        ->load($authipayredirectId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d code(s) were successfully updated', count($authipayredirectIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    public function massAssignAction(){
    	$authipayredirectIds = $this->getRequest()->getParam('authipayredirect');
        if(!is_array($authipayredirectIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select code(s)'));
        } else {
            try {
                foreach ($authipayredirectIds as $authipayredirectId) {
                    $authipayredirect = Mage::getSingleton('authipayredirect/authipayredirect')
                        ->load($authipayredirectId)
                        ->setAgentId($this->getRequest()->getParam('agent_id'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d code(s) were successfully updated', count($authipayredirectIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'authipayredirect.csv';
        $content    = $this->getLayout()->createBlock('authipayredirect/adminhtml_authipayredirect_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'authipayredirect.xml';
        $content    = $this->getLayout()->createBlock('authipayredirect/adminhtml_authipayredirect_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
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