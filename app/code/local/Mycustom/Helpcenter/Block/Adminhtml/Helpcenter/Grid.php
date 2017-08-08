<?php
class Mycustom_Helpcenter_Block_Adminhtml_Helpcenter_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct()
    {
        parent::__construct();
        $this->setId('helpcenterGrid'); // set’s the ID of our grid
        $this->setDefaultSort('entity_id'); // sorting column to use in our grid
        $this->setDefaultDir('ASC'); // sorting order
        $this->setSaveParametersInSession(true); // sets your grid operations in session
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('helpcenter/helpcenter')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('helpcenter')->__('Id'),
            'align'     =>'left',
            'index'     => 'entity_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('helpcenter')->__('Title'),
            'align'     =>'left',
            'index'     => 'title',
        ));

        $this->addColumn('content', array(
            'header'    => Mage::helper('helpcenter')->__('Content'),
            'align'     =>'left',
            'index'     => 'content',
        ));

        $this->addColumn('url', array(
            'header'    => Mage::helper('helpcenter')->__('Url'),
            'align'     =>'left',
            'index'     => 'url',
        ));

        $this->addColumn('create_at', array(
            'header'    => Mage::helper('helpcenter')->__('Create At'),
            'align'     =>'left',
            'index'     => 'create_at',
        ));

        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('helpcenter')->__('Updated At'),
            'width'     => '150px',
            'index'     => 'updated_at',
        ));

        $this->addColumn('store_id', array(
            'header'    => Mage::helper('helpcenter')->__('Store Id'),
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
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}