<?php

require_once('Mage/Adminhtml/controllers/Catalog/ProductController.php');

class Mycustom_Adminhtml_Adminhtml_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $productId      = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);

        $data = $this->getRequest()->getPost();
        if ($data) {
            $this->_filterStockData($data['product']['stock_data']);

            $product = $this->_initProductSave();
            $tier_price = $product->getData('tier_price');
            $old_product = Mage::getModel('catalog/product')->load($productId);
            $old_tier_price = $old_product->getTierPrice();
            try {
                $product->save();
                #Add Product Price Log
                $productId = $product->getId();
                $blogpost = Mage::getModel('weblog/blogpost');
                $log_tierprice = Mage::getModel('weblog/tierprice');
                if($old_product->getPrice() != $product->getPrice() || $old_product->getSpecialPrice() != $product->getSpecialPrice()) {
                    $blogpost->setData('entity_id', $productId);
                    $blogpost->setData('price', $product->getPrice());
                    $blogpost->setData('final_price', $product->getSpecialPrice());
                    $blogpost->setData('from_date', $product->getSpecialFromDate());
                    $blogpost->setData('to_date', $product->getSpecialToDate());
                    $blogpost->save();
                }
                for ($i = 0; $i< count($tier_price); $i++) {
                    if($old_tier_price[$i]['price'] != $tier_price[$i]['price'] || $old_tier_price[$i]['price_qty'] != $tier_price[$i]['price_qty']){
                        $tiers = array(
                            'entity_id' => $product->getId(),
                            'all_groups' => ($tier_price[$i]['cust_group'] == '32000')?0:1,
                            'customer_group_id' => ($tier_price[$i]['cust_group'] < 10)? (int)$tier_price[$i]['cust_group']:0,
                            'value' => $tier_price[$i]['price'],
                            'qty' => $tier_price[$i]['price_qty'],
                            'website_id' => $tier_price[$i]['website_id']
                        );
                        $log_tierprice->addData($tiers);
                        $log_tierprice->save();
                    }
                }
                if (isset($data['copy_to_stores'])) {
                    $this->_copyAttributesBetweenStores($data['copy_to_stores'], $product);
                }

                $this->_getSession()->addSuccess($this->__('The product has been saved.'));
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage())
                    ->setProductData($data);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
                $redirectBack = true;
            }
        }

        if ($redirectBack) {
            $this->_redirect('*/*/edit', array(
                'id'    => $productId,
                '_current'=>true
            ));
        } elseif($this->getRequest()->getParam('popup')) {
            $this->_redirect('*/*/created', array(
                '_current'   => true,
                'id'         => $productId,
                'edit'       => $isEdit
            ));
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
    }
}