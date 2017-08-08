<?php
class TTS_Motpay_Block_Motpay extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getMotpay()
     { 
        if (!$this->hasData('motpay')) {
            $this->setData('motpay', Mage::registry('motpay'));
        }
        return $this->getData('motpay');
        
    }
}