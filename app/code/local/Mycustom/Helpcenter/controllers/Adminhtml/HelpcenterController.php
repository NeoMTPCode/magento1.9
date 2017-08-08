<?php
class Mycustom_Helpcenter_Adminhtml_HelpcenterController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('mycustom_helpcenter/set_time')->_addBreadcrumb('Page Manager','Page Manager');
        return $this;
    }

    public function indexAction() {
        $this->loadLayout();
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('mycustom_helpcenter/adminhtml_helpcenter'));
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('mycustom_helpcenter/adminhtml_helpcenter')->toHtml()
        );
    }

    public function editAction()
    {
        $pageId = $this->getRequest()->getParam('id');
        $page = Mage::getModel('helpcenter/helpcenter');
        if ($pageId)
        {
            $page->load($pageId);
            if ($page->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $page->setData($data)->setId($pageId);
                }

            }
            else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('helpcenter')->__('Example does not exist'));
                $this->_redirect('*/*/');
            }
        }
        Mage::register('helpcenter_data', $page);

        $this->loadLayout();
        $this->_setActiveMenu('mycustom_helpcenter/set_time');
        $this->_addBreadcrumb('Page Manager', 'Page Manager');
        $this->_addBreadcrumb('Page Description', 'Page Description');
        $this->getLayout()->getBlock('head')
            ->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()
            ->createBlock('mycustom_helpcenter/adminhtml_helpcenter_edit'))
            ->_addLeft($this->getLayout()
                ->createBlock('mycustom_helpcenter/adminhtml_helpcenter_edit_tabs')
            );
        $this->renderLayout();
//        $this->loadLayout();
//        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
//        $this->renderLayout();
    }


    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {

        if ($postData = $this->getRequest()->getPost())
        {
            $model = Mage::getModel('helpcenter/helpcenter');
            try {
                if($id = $this->getRequest()->getParam('id')){
                    $model->load($id);
                }
                if(isset($postData['stores'])) {
                    if(in_array('0',$postData['stores'])){
                        $postData['store_id'] = '0';
                    }
                    else{
                        $postData['store_id'] = implode(",", $postData['stores']);
                    }
                    unset($postData['stores']);
                }
                $model
                    ->addData($postData)
                    ->save();
                Mage::getSingleton('adminhtml/session')->addSuccess('successfully saved');
                Mage::getSingleton('adminhtml/session')->sethelpcenterData(false);
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setfilmsData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }

//            $this->_redirect('*/*/');
        }
    }

    public function deleteAction()
    {
        if($this->getRequest()->getParam('id') > 0)
        {
            try
            {
                $page = Mage::getModel('helpcenter/helpcenter');
                $page->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess('successfully deleted');
                $this->_redirect('*/*/');
            }
            catch (Exception $e)
            {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
}