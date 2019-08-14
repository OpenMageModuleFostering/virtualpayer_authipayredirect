<?php

class Virtualpayer_Authipayredirect_Block_Adminhtml_Authipayredirect_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('authipayredirectGrid');
      $this->setDefaultSort('timestamp');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('authipayredirect/authipayredirect')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }
  
     /**
     * Return Current work store
     *
     * @return Mage_Core_Model_Store
     */
    protected function _getStore()
    {
        return Mage::app()->getStore();
    }

  protected function _prepareColumns()
  {

     $this->addColumn('order_id', array(
          'header'    => Mage::helper('authipayredirect')->__('Order ID'),
          'index'     => 'order_id',
     ));

      $this->addColumn('timestamp', array(
          'header'    => Mage::helper('authipayredirect')->__('Timestamp'),
          'type'        => 'datetime',
          'index'     => 'timestamp',
     ));


      $this->addColumn('oid', array(
          'header'    => Mage::helper('authipayredirect')->__('authipayredirect Order ID'),
          'index'     => 'oid',
     ));

      $this->addColumn('status', array(
          'header'    => Mage::helper('authipayredirect')->__('Status'),
          'index'     => 'status',
          'width'     => '50px',

     ));

      $this->addColumn('fail_reason', array(
          'header'    => Mage::helper('authipayredirect')->__('Fail Reason'),
          'index'     => 'fail_reason',
     ));

      $this->addColumn('cardnumber', array(
          'header'    => Mage::helper('authipayredirect')->__('Card Number'),
          'index'     => 'cardnumber',
     ));

      $this->addColumn('currency', array(
          'header'    => Mage::helper('authipayredirect')->__('Currency'),
          'index'     => 'currency',
          'width'     => '50px',
     ));

      $this->addColumn('refnumber', array(
          'header'    => Mage::helper('authipayredirect')->__('Referance Number'),
          'index'     => 'refnumber',
     ));

      $this->addColumn('chargetotal', array(
          'header'    => Mage::helper('authipayredirect')->__('Amount'),
          'index'     => 'chargetotal',
     ));

      $this->addColumn('paymentMethod', array(
          'header'    => Mage::helper('authipayredirect')->__('Payment Method'),
          'index'     => 'paymentMethod',
     ));

      $this->addColumn('processor_response_code', array(
          'header'    => Mage::helper('authipayredirect')->__('Response Code'),
          'index'     => 'processor_response_code',
     ));

    
	
		$this->addExportType('*/*/exportCsv', Mage::helper('authipayredirect')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('authipayredirect')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('authipayredirect_id');
        $this->getMassactionBlock()->setFormFieldName('authipayredirect');
        
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}