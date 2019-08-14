<?php

class VirtualPayer_AuthipayRedirect_Model_AuthipayRedirect extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('authipayredirect/authipayredirect');
    }    
}