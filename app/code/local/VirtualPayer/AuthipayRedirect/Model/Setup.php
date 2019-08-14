<?php

class VirtualPayer_AuthipayRedirect_Model_Setup extends Mage_Eav_Model_Entity_Setup
{

	public function createStaticBlocks(){
		$error = Mage::getModel('cms/block');
		$error->setTitle('AuthipayRedirect Error Message')
				->setIdentifier('authipayredirect_error')
				->setContent('{{var response.message}}')
				->setCreationTime(date('Y-m-d H:i:s'))
				->setUpdateTime(date('Y-m-d H:i:s'))
				->setIsActive(1)
				->setStores(0)
				->save();
						
		$success = Mage::getModel('cms/block');
		$success->setTitle('AuthipayRedirect Success Message')
				->setIdentifier('authipayredirect_success')
				->setContent('{{var response.message}}')
				->setCreationTime(date('Y-m-d H:i:s'))
				->setUpdateTime(date('Y-m-d H:i:s'))
				->setIsActive(1)
				->setStores(0)
				->save();
		
		$redirect = Mage::getModel('cms/block');
		$redirect->setTitle('AuthipayRedirect Redirect Message')
				->setIdentifier('authipayredirect_redirect')
				->setContent('You will be redirected to Authipay in a few seconds.')
				->setCreationTime(date('Y-m-d H:i:s'))
				->setUpdateTime(date('Y-m-d H:i:s'))
				->setIsActive(1)
				->setStores(0)
				->save();
	}

}