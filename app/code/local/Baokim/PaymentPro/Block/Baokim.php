<?php

class Baokim_PaymentPro_Block_Baokim extends Mage_Payment_Model_Method_Cc
{

	public function _prepareLayout()
	{
		return parent::_prepareLayout();
	}

	public function getPaymentPro()
	{
		if (!$this->hasData('paymentpro')) {
			$this->setData('paymentpro', Mage::registry('paymentpro'));
		}
		return $this->getData('paymentpro');

	}
}