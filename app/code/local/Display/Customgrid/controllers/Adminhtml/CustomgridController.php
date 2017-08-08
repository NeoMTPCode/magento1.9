<?php
class Display_Customgrid_Adminhtml_CustomgridController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('display_customgrid/set_time')->_addBreadcrumb('Movies Manager','Movies Manager');
        return $this;
    }

    public function indexAction() {
        $this->loadLayout();
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('display_customgrid/adminhtml_customgrid'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('display_customgrid/adminhtml_customgrid')->toHtml()
        );
    }
}