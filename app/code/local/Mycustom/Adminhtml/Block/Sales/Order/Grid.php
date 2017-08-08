<?php

class Mycustom_Adminhtml_Block_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid{

    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();

        $this->getMassactionBlock()->addItem('delete_order', array(
            'label'=> Mage::helper('sales')->__('Delete Order'),
            'url'  => $this->getUrl('adminhtml/deleteorder/massDelete'),
            'confirm'  => Mage::helper('sales')->__('Are you sure you want to delete order?')
        ));

        // Append new mass action option
        $this->getMassactionBlock()->addItem(
            'pdfbarcode_order',
            array('label' => $this->__('Print Barcode'),
                'url'   => $this->getUrl('adminhtml/sales_order/pdfbarcode') //this should be the url where there will be mass operation
            )
        );
        return this;
    }

    public function getRowUrl($row)
    {
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
        }
        return false;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}