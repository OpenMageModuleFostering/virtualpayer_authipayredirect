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
 * @category   VirtualPayer
 * @package    Virtualpayer_Authipayredirect
 * @copyright  Copyright (c) 2016 VirtualPayer
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Virtualpayer_Authipayredirect_RedirectController extends Mage_Core_Controller_Front_Action
{
    /**
     * Order instance
     */
    protected $_order;
    
    /**
     * Get one page checkout model
     *
     * @return Mage_Checkout_Model_Type_Onepage
     */
    public function getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }
    
    /**
     *  Get order
     *
     *  @param    none
     *  @return	  Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if ($this->_order == null) {
        }
        return $this->_order;
    }

    /**
     * Get singleton with Authipay Connect Redirect order transaction information
     *
     * @return Mage_AuthipayRedirect_Model_Redirect
     */
    public function getRedirect()
    {
        return Mage::getSingleton('authipayredirect/redirect');
    }

    /**
     * @return void
     */
    public function indexAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setAuthipayredirectRedirectQuoteId($session->getQuoteId());
        $session->unsQuoteId();
        
        $this->loadLayout();
        $this->getLayout()->getBlock('content')->append($this->getLayout()->createBlock('authipayredirect/redirect_redirect'));
        $this->renderLayout();

    }

    /**
     * @return void
     */
    public function cancelAction()
    {
        $session = Mage::getSingleton('checkout/session');

        // cancel order
        if ($session->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
            if ($order->getId()) {
                $session->addNotice($this->__('Your Order has been declined, please try again or contact us'));
                $order->cancel()->save();
            }
        }

        $this->_redirect('checkout/cart');
    }
    
    /**
     * Order success action
     */
    public function successAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $post = $this->getRequest()->getPost();

	    if($post){
			if (isset($post['oid'])) {
				if(Mage::getModel('authipayredirect/redirect')->processRedirectResponse($post)){
					$session->setQuoteId($session->getAuthipayredirectRedirectQuoteId());
		    	    $this->getResponse()->setBody($this->getLayout()->createBlock('authipayredirect/redirect_success')->toHtml());
				}else{
			        $this->getResponse()->setBody($this->getLayout()->createBlock('authipayredirect/redirect_error')->toHtml());
				}
			}
        }else{
        	//set the quote as inactive after back from AIBMS
	        $session->getQuote()->setIsActive(false)->save();
    	    $this->_redirect('checkout/onepage/success', array('_secure'=>true));
        }
    }

    public function failureAction()
    {
        $lastQuoteId = $this->getOnepage()->getCheckout()->getLastQuoteId();
        $lastOrderId = $this->getOnepage()->getCheckout()->getLastOrderId();

        if (!$lastQuoteId || !$lastOrderId) {
            $this->_redirect('checkout/cart');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }

}
