<?php
/**
 * Created by PhpStorm.
 * User: Hieu
 * Date: 08/08/2014
 * Time: 17:25
 */
class Baokim_paymentpro_Block_Adminhtml_PaymentMethod
{
	public function toOptionArray()
	{
		return array(
			array(
				'value' => Baokim_PaymentPro_Model_BaokimPayment::METHOD_IMMEDIATE,
				'label' => 'Immediate'
			),
			array(
				'value' => Baokim_PaymentPro_Model_BaokimPayment::METHOD_SAFE,
				'label' => 'Safe'
			),
		);
	}
}
