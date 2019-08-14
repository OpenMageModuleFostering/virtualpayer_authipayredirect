<?php

class VirtualPayer_AuthipayRedirect_Model_Mysql4_AuthipayRedirect_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('authipayredirect/authipayredirect');
    }
}