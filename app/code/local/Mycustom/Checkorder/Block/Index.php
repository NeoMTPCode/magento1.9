<?php

class Mycustom_Checkorder_Block_Index extends Mage_Core_Block_Template{

    public function isCustomerHasOrders($customerId, $grandTotal = null)
    {
        $orderCollection = $this->getCustomerOrderCollection($customerId, $grandTotal);
        return (bool)$orderCollection->getSize();
    }

    public function checkLogginIn(){
        $customerSession = Mage::getSingleton('customer/session');
        if(!$customerSession->isLoggedIn()){
//            Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl());
            return false;
        }
        return true;
    }

    public function getCustomerOrderCollection($grandTotal = null)
    {
        if($this->checkLogginIn()){
            $customerSession = Mage::getSingleton('customer/session');
            $customer = $customerSession->getCustomer();
            $orderCollection = Mage::getModel('sales/order')->getCollection()
                ->addFieldToSelect('*')
                ->addAttributeToFilter('customer_id', $customer->getId())
                ->addAttributeToFilter('state', array('in' => Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates()))
                ->setOrder('created_at', 'desc');
            if ($grandTotal && $grandTotal > 0) {
                $orderCollection->addAttributeToFilter('base_grand_total', array ('gteq' => $grandTotal));
            }
            return $orderCollection;
        }
        return false;
    }

    public function getCurrentCustomer(){
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    public function getEnableCss($current_state, $current_status, $require = ''){
       switch ($current_state){
           case 'processing':{
               if($current_status == $current_state && !empty($require) && $require == $current_state){
                   echo 'enable-checkorder-wizard';
               }
               break;
           }
           case 'complete':{
                   echo 'enable-checkorder-wizard';
               break;
           }
       }
    }
}