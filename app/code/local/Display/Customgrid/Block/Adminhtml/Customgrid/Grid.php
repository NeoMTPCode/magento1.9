<?php
class Display_Customgrid_Block_Adminhtml_Customgrid_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct()
    {
        parent::__construct();
        $this->setId('customgridGrid'); // set’s the ID of our grid
        $this->setDefaultSort('entity_id'); // sorting column to use in our grid
        $this->setDefaultDir('ASC'); // sorting order
        $this->setSaveParametersInSession(true); // sets your grid operations in session
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('customgrid/displaygrid')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('customgrid')->__('Id'),
            'align'     =>'left',
            'index'     => 'entity_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('customgrid')->__('Title'),
            'align'     =>'left',
            'index'     => 'title',
        ));

        $this->addColumn('content', array(
            'header'    => Mage::helper('customgrid')->__('Content'),
            'align'     =>'left',
            'index'     => 'content',
        ));

        $this->addColumn('url', array(
            'header'    => Mage::helper('customgrid')->__('Url'),
            'align'     =>'left',
            'index'     => 'url',
        ));

        $this->addColumn('create_at', array(
            'header'    => Mage::helper('customgrid')->__('Create At'),
            'align'     =>'left',
            'index'     => 'create_at',
        ));

        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('customgrid')->__('Updated At'),
            'width'     => '150px',
            'index'     => 'updated_at',
        ));

        $this->addColumn('store_id', array(
            'header'    => Mage::helper('customgrid')->__('Store Id'),
            'width'     => '150px',
            'index'     => 'store_id',
        ));

        return parent::_prepareColumns();
    }
}