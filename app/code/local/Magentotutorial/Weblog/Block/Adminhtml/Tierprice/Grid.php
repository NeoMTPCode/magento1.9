<?php
class Magentotutorial_Weblog_Block_Adminhtml_Tierprice_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct()
    {
        parent::__construct();
        $this->setUseAjax(true);
        $this->setId('tierprice-grid'); // set’s the ID of our grid
        $this->setDefaultSort('entity_id'); // sorting column to use in our grid
        $this->setDefaultDir('ASC'); // sorting order
        $this->setSaveParametersInSession(true); // sets your grid operations in session
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('weblog/tierprice')->getCollection()
            ->addFieldToFilter('updated_at', Mage::registry('weblog_data')->getData('updated_at'));
        $collection->getSelect()->join(
            array('s' => Mage::getSingleton('core/resource')->getTableName('customer_group')),
            's.customer_group_id = main_table.customer_group_id'
        );
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareColumns() {
        $this->addColumn('value_id', array(
            'header' => Mage::helper('weblog')->__('ID'),
            'type'      => 'number',
            'index'     => 'main_table.value_id'
        ));

        $this->addColumn('all_groups', array(
            'header' => Mage::helper('weblog')->__('All Group'),
            'type'      => 'number',
            'index' => 'all_groups'
        ));

        $this->addColumn('customer_group_id', array(
            'header' => Mage::helper('weblog')->__('Group Name'),
            'index' => 'customer_group_code',
            'width'     => 100
        ));

        $this->addColumn('value', array(
            'header'    => Mage::helper('weblog')->__('Tier Price'),
            'index'     => 'value',
            'type'      => 'currency',
            'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode()
        ));

        $this->addColumn('qty', array(
            'header'    => Mage::helper('weblog')->__('Qty'),
            'index'     => 'qty',
            'type'  => 'number'
        ));
//        $this->addColumn('website_id', array(
//            'header'    => Mage::helper('weblog')->__('Website ID'),
//            'index'     => 'website_id',
//            'type'      => 'string',
//            'sortable'  => false
//        ));
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('website_id',
                array(
                    'header'=> Mage::helper('weblog')->__('Websites'),
                    'width' => '100px',
                    'sortable'  => false,
                    'index'     => 'website_id',
                    'type'      => 'options',
                    'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
                ));
        }
        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

//    public function getRowUrl($row)
//    {
//        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
//    }
}