<?php


class Baokim_PaymentPro_Block_Info_Baokim extends Mage_Payment_Block_Info
{
	public function getBankMethodId()
	{
		$bank_method_id = $this->getInfo()->getBankMethodId();
		return $bank_method_id;
	}
}
?>