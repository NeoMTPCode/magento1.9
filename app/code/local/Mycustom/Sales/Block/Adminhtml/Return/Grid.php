<?php
class Mycustom_Sales_Block_Adminhtml_Return_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct()
    {
        parent::__construct();
        $this->setId('return_items_grid'); // set’s the ID of our grid
        $this->setUseAjax(true);
        $this->setDefaultSort('order_id'); // sorting column to use in our grid
        $this->setDefaultDir('ASC'); // sorting order
        $this->setSaveParametersInSession(true); // sets your grid operations in session
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('mycustom_sales/return')->getCollection();
        $collection->getSelect()->join(
            array('s' => Mage::getSingleton('core/resource')->getTableName('sales/order')),
            'main_table.order_id = s.increment_id');
//        $collection->join('sales/order', 'order_id=entity_id', array('name'=>'name', 'sku' =>'sku', 'qty_ordered'=>'qty_ordered' ), null,'left');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('order_id', array(
            'header'    => Mage::helper('sales')->__('Order Id'),
            'align'     =>'left',
            'type'      => 'int',
            'index'     => 'order_id',
            'filter_index' => 'main_table.order_id',
        ));

        $this->addColumn('product_items', array(
            'header'    => Mage::helper('sales')->__('Product Items'),
            'align'     =>'left',
            'index'     => 'product_items'
        ));

        $this->addColumn('reason', array(
            'header'    => Mage::helper('sales')->__('Reason'),
            'align'     =>'left',
            'index'     => 'reason'
        ));

        $this->addColumn('note', array(
            'header'    => Mage::helper('sales')->__('Note'),
            'align'     =>'left',
            'index'     => 'note'
        ));

        $this->addColumn('type', array(
            'header'    => Mage::helper('sales')->__('Type'),
            'align'     =>'left',
            'index'     => 'type'
        ));

        $this->addColumn('method', array(
            'header'    => Mage::helper('sales')->__('Method'),
            'width'     => '150px',
            'index'     => 'method'
        ));

        $this->addColumn('created_at', array(
            'header'    => Mage::helper('sales')->__('Created At'),
            'width'     => '150px',
            'index'     => 'created_at'
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));

        $this->addColumn('store_id', array(
            'header'    => Mage::helper('sales')->__('Store Id'),
            'width'     => '150px',
            'type'      => 'store',
            'store_view'=> true,
            'display_deleted' => true,
            'filter_index' => 'main_table.store_id',
            'index'     => 'store_id'
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}