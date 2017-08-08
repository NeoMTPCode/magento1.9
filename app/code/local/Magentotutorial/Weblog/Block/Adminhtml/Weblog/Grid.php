<?php
class Magentotutorial_Weblog_Block_Adminhtml_Weblog_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct()
    {
        parent::__construct();
        $this->setUseAjax(true);
        $this->setId('weblogGrid'); // set’s the ID of our grid
        $this->setDefaultSort('value_id'); // sorting column to use in our grid
        $this->setDefaultDir('ASC'); // sorting order
        $this->setSaveParametersInSession(true); // sets your grid operations in session
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('weblog/blogpost')->getCollection();

        $entityTypeId = Mage::getModel('eav/entity')
            ->setType('catalog_product')
            ->getTypeId();
        $prodNameAttrId = Mage::getModel('eav/entity_attribute')
            ->loadByCode($entityTypeId, 'name')
            ->getAttributeId();
        $statusAttrId = Mage::getModel('eav/entity_attribute')
            ->loadByCode($entityTypeId, 'status')
            ->getAttributeId();
        $collection->getSelect()
            ->join(array('prod' =>
            Mage::getSingleton('core/resource')->getTableName('catalog/product')),
            'prod.entity_id = main_table.entity_id')
            ->joinLeft(
                array('cpev' => 'catalog_product_entity_varchar'),
                'cpev.entity_id = main_table.entity_id AND cpev.attribute_id='.$prodNameAttrId,
                array('name' => 'value')
            )
            ->joinLeft(
                array('cpei' => 'catalog_product_entity_int'),
                'cpei.entity_id = main_table.entity_id AND cpei.attribute_id='. $statusAttrId,
                array('status' => 'value')
            )
            ->joinLeft(
                array('cpw' => 'catalog_product_website'),
                'cpw.product_id = main_table.entity_id',
                array('website_id' => 'website_id')
            );
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareColumns()
    {
        $store = $this->_getStore();

        $helper = Mage::helper('weblog');
        $this->addColumn('entity_id', array(
            'header'    => $helper->__('Id'),
            'align'     => 'left',
            'index'     => 'entity_id',
            'type'  => 'number'
        ));

        $this->addColumn('product_name', array(
            'header'    => $helper->__('Product Name'),
            'width'     => '150px',
            'index'     => 'name'
        ));

        $this->addColumn('sku', array(
            'header'    => $helper->__('SKU'),
            'width'     => '80px',
            'index'     => 'sku'
        ));

        $this->addColumn('price', array(
            'header'    => $helper->__('Price'),
            'width'     => '150px',
            'index'     => 'price',
            'type'  => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode()
        ));
        $this->addColumn('final_price', array(
            'header'    => $helper->__('Final Price'),
            'width'     => '150px',
            'index'     => 'price',
            'type'  => 'price',
            'currency_code' => $store->getBaseCurrency()->getCode()
        ));

        $this->addColumn('from_date', array(
            'header'    => $helper->__('From Date'),
            'width'     => '150px',
            'index'     => 'from_date',
            'type'   => 'datetime'
        ));

        $this->addColumn('to_date', array(
            'header'    => $helper->__('To Date'),
            'width'     => '150px',
            'index'     => 'to_date',
            'type'   => 'datetime'
        ));

        $this->addColumn('status', array(
            'header'    => $helper->__('Status'),
            'width' => '70px',
            'index' => 'status',
            'type'  => 'options',
            'options' => Mage::getSingleton('catalog/product_status')->getOptionArray()
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('website_id',
                array(
                    'header'=> Mage::helper('weblog')->__('Websites'),
                    'width' => '100px',
                    'sortable'  => false,
                    'index'     => 'website_id',
                    'type'      => 'options',
                    'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash()
                ));
        }
        $this->addColumn('updated_at', array(
            'header'    => $helper->__('Updated At'),
            'width'     => '150px',
            'index'     => 'updated_at',
            'type'   => 'datetime'
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareLayout()
    {
        $this->setChild('store_switcher',
            $this->getLayout()->createBlock('adminhtml/store_switcher')
                ->setSwitchUrl($this->getUrl('*/*/*', array('_current'=>true, '_query'=>false, 'store'=>null)))
                ->setTemplate('store/switcher/enhanced.phtml')
        );
        return parent::_prepareLayout();
    }
}