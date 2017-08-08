<?php

require_once('Mage/Sales/controllers/OrderController.php');

class Mycustom_Sales_OrderController extends Mage_Sales_OrderController {

    public function cancelorderAction(){
        $increment_id = $this->getRequest()->getPost('increment_id');
        $order = Mage::getModel('sales/order')
            ->loadByIncrementID($increment_id);
//            ->getCollection()
//            ->addAttributeToSelect('*')
//            ->addAttributeToFilter('increment_id', $increment_id);

//        echo $order->getFirstItem()->getStatus();
        foreach($order->getAllItems() as $item){
            $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($item->getProductId());
            $tmp_stock = $stockItem->getQty();
            $stockItem->setData('qty', $tmp_stock + $item->getQtyOrdered());
            $stockItem->save();
            unset($stockItem);
        }
        $order->setStatus(Mage_Sales_Model_Order::STATE_CANCELED, true);
        $order->setState(Mage_Sales_Model_Order::STATE_CANCELED)->save();
        Mage::getSingleton('core/session')->addSuccess('Hủy đơn hàng thành công!');
        $this->_redirect('sales/order/history');
    }

    public function returnAction(){
//        $item_id = $this->getRequest()->getPost('item_id');
//        $increment_id = $this->getRequest()->getPost('increment_id');
//        $reason = $this->getRequest()->getPost('reason');
//        $note = $this->getRequest()->getPost('note');
//        $type_return = $this->getRequest()->getPost('type_return');
//        $method_return = $this->getRequest()->getPost('method_return');
        $current_store = Mage::app()->getStore()->getStoreId();

        if ($postData = $this->getRequest()->getPost())
        {
            $model = Mage::getModel('mycustom_sales/return');
            $postData['store_id'] = $current_store;
            $postData['product_items'] =  implode(",", $postData['item_id']);
            $postData['type'] = $postData['type_return'];
            $postData['method'] = $postData['method_return'];
            $postData['order_id'] = $postData['increment_id'];
            unset($postData['item_id']);
            try {
//                $model->setData('type_return', $postData['type_return']);
//                $model->setData('method_return', $postData['method_return']);
//                $model->setData('reason', $postData['reason']);
//                $model->setData('note', $postData['note']);
//                $model->setData('product_items', $postData['product_items']);
                $model
                    ->setData($postData)
                    ->save();
                Mage::getSingleton('core/session')->addSuccess('Gửi yêu cầu thành công !');
//                $this->_redirect('*/*/');
                $this->_redirectReferer();
                return;
            } catch (Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
//                Mage::getSingleton('customer/session')->setfilmsData($this->getRequest()->getPost());
                return;
            }

        }
////            ->getCollection()
////            ->addAttributeToSelect('*')
////            ->addAttributeToFilter('increment_id', $increment_id);
//
////        echo $order->getFirstItem()->getStatus();
//        $order->setStatus(Mage_Sales_Model_Order::STATE_CANCELED, true)->save();
//        $this->_redirect('sales/order/history');
    }
}