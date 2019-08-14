<?php

class VirtualPayer_AuthipayRedirect_Model_Mysql4_AuthipayRedirect extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('authipayredirect/authipayredirect', 'authipayredirect_id');
    }
}