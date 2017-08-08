<?php

require_once('Mage/Checkout/controllers/CartController.php');

class Mycustom_Checkout_CartController extends Mage_Checkout_CartController
{
//    public function updatePostAction()
//    {
//        if (!$this->_validateFormKey()) {
//            $this->_redirect('*/*/');
//            return;
//        }
//
//        $updateAction = (string)$this->getRequest()->getParam('update_cart_action');
//
//        switch ($updateAction) {
//            case 'empty_cart':
//                $this->_emptyShoppingCart();
//                break;
//            case 'update_qty':
//                $this->_updateShoppingCart();
//                break;
//            default:
//                $this->_updateShoppingCart();
//        }
////        $this->_goBack();
//    }
//
//    public function _updateShoppingCart()
//    {
//        try {
//            $cartData = $this->getRequest()->getParam('cart');
//            if (is_array($cartData)) {
//                $filter = new Zend_Filter_LocalizedToNormalized(
//                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
//                );
//                foreach ($cartData as $index => $data) {
//                    if (isset($data['qty'])) {
//                        $cartData[$index]['qty'] = $filter->filter(trim($data['qty']));
//                    }
//                }
//                $cart = $this->_getCart();
//                if (! $cart->getCustomerSession()->getCustomer()->getId() && $cart->getQuote()->getCustomerId()) {
//                    $cart->getQuote()->setCustomerId(null);
//                }
//
//                $cartData = $cart->suggestItemsQty($cartData);
//                $cart->updateItems($cartData)
//                    ->save();
//            }
//            $this->_getSession()->setCartWasUpdated(true);
//
//            $item = Mage::getModel('sales/quote_item')->load($this->getRequest()->getParam('itemId'));
//            $_total_tmp = Mage::helper('core')->currency($item->getRowTotal(), true, false);
//            $this->getResponse()->setBody($_total_tmp);
//        } catch (Mage_Core_Exception $e) {
//            $this->_getSession()->addError(Mage::helper('core')->escapeHtml($e->getMessage()));
//        } catch (Exception $e) {
//            $this->_getSession()->addException($e, $this->__('Cannot update shopping cart.'));
//            Mage::logException($e);
//        }
//    }
}