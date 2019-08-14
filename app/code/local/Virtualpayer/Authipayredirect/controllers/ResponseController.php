<?php
/**
 * Virtualpayer_Authipayredirect extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Virtualpayer
 * @package    Virtualpayer_Authipayredirect
 * @copyright  Copyright (c) 2016 VirtualPayer
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtualpayer_Authipayredirect_ResponseController extends Mage_Core_Controller_Front_Action
{
  /**
     * @return void
     */
    public function indexAction()
    {
    	$session = Mage::getSingleton('checkout/session');
        $post = $this->getRequest()->getPost();

	    if($post){
			if (isset($post['oid'])) {
				if(Mage::getModel('authipayredirect/redirect')->processRedirectResponse($post)){
					$session->setQuoteId($session->getAuthipayRedirectRedirectQuoteId());
		    	    $this->getResponse()->setBody($this->getLayout()->createBlock('authipayredirect/redirect_success')->toHtml());
				}else{
			        $this->getResponse()->setBody($this->getLayout()->createBlock('authipayredirect/redirect_error')->toHtml());
				}
			}
        }else{
        	//set the quote as inactive after back from Authipay
	        $session->getQuote()->setIsActive(false)->save();
    	    $this->_redirect('checkout/onepage/success', array('_secure'=>true));
        }
    }

    /**
     * @return
     */
	 public function successAction(){
        $session = $this->getOnepage()->getCheckout();
        if (!$session->getLastSuccessQuoteId()) {
            $this->_redirect('checkout/cart');
            return;
        }

        $lastQuoteId = $session->getLastQuoteId();
        $lastOrderId = $session->getLastOrderId();
        $lastRecurringProfiles = $session->getLastRecurringProfileIds();
        if (!$lastQuoteId || (!$lastOrderId && empty($lastRecurringProfiles))) {
            $this->_redirect('checkout/cart');
            return;
        }

        $session->clear();
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($lastOrderId)));
        $this->renderLayout();
		var_dump($this->getLayout()->getUpdate()->getHandles());
    }
	
	/**
     * @return
     */
	
    public function failureAction(){
		$session = Mage::getSingleton('checkout/session');
        $lastQuoteId = $session->getLastQuoteId();
        $lastOrderId = $session->getLastOrderId();

        if (!$lastQuoteId || !$lastOrderId) {
            $this->_redirect('checkout/cart');
            return;
        }

        $order = Mage::getModel('sales/order')->loadByAttribute('entity_id', $lastOrderId);

       	if ($order->getId()) {
        	$order->addStatusToHistory('canceled', $session->getErrorMessage())->save();
	    }

        $this->_redirect('checkout/onepage/failure');
        return;
    }
}