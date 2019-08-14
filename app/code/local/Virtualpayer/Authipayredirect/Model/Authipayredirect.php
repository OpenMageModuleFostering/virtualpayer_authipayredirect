<?php

class Virtualpayer_Authipayredirect_Model_Authipayredirect extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('authipayredirect/authipayredirect');
    }    
}