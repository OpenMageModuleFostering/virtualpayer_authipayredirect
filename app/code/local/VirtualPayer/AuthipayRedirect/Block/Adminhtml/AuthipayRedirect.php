<?php
class VirtualPayer_AuthipayRedirect_Block_Adminhtml_AuthipayRedirect extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_authipayredirect';
    $this->_blockGroup = 'authipayredirect';
    $this->_headerText = Mage::helper('authipayredirect')->__('Authipay Redirect Transactions');
    parent::__construct();
    $this->_removeButton('add');
  }
}