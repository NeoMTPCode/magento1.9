<?php
class Magentotutorial_Weblog_Block_Monblock extends Mage_Core_Block_Template
{
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function methodblock()
    {

        $mess = '';
//        $collection = Mage::getModel('weblog/blogpost')
//            ->getCollection()
//            ->setOrder('films_id','asc');
//        foreach($collection as $item){
//            $mess.= $item->getData('name').'<br/>';
//        }
//
//        Mage::Singleton('adminhtml/session')->addSuccess('SUCCESSFULL');
        return $mess ;
    }

}
