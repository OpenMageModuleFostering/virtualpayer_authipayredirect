<?php

class Virtualpayer_Authipayredirect_Model_Mysql4_Authipayredirect extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('authipayredirect/authipayredirect', 'authipayredirect_id');
    }
}