<?php

class Mycustom_Adminhtml_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Controller_Action{

//    protected function _initAction() {
//        $this->loadLayout()
//            ->_setActiveMenu('sales_order/items')
//            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
//        return $this;
//    }
//
//    public function indexAction() {
//        $this->_initAction()
//            ->renderLayout();
//    }

    public function pdfbarcodeAction(){
        $config = Mage::helper('meanbee_barcodes/config');
        if (!$config->getAnnotatorEnabled()) {
            return;
        }
        $orderIds = $this->getRequest()->getPost('order_ids');

        $flag = false;
        if (!empty($orderIds)) {
            $flag = true;
            if (!isset($pdf)){
                $pdf = Mage::getModel('mycustom_adminhtml/order_pdf_barcode')->getPdf($orderIds);
            } else {
                $pages = Mage::getModel('mycustom_adminhtml/order_pdf_barcode')->getPdf($orderIds);
                $pdf->pages = array_merge ($pdf->pages, $pages->pages);
            }

            if ($flag) {
                return $this->_prepareDownloadResponse(
                    'barcode_order'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(),
                    'application/pdf'
                );
            } else {
                $this->_getSession()->addError($this->__('There are no printable documents related to selected orders.'));
                $this->_redirect('*/*/');
            }
        }
        $this->_redirect('*/*/');
    }
}