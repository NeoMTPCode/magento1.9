<?php

class Mycustom_Configurable_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    public function updateOptionAction(){
        $productId = $this->getRequest()->getParam('productId');
        $product = Mage::getModel('catalog/product')->load($productId);
        $helper = Mage::helper('catalog/image');
        $productBlock = $this->getLayout()->createBlock('catalog/product_price');

        $arr = array(
            'id' => $product->getId(),
            'name'=>  $product->getName(),
            'price'=> $product->getPrice(),
            'specialPrice'=> $product->getPrice(),
            'shortDescription'=> $product->getShortDescription(),
            'description'=> $product->getDescription(),
            'galleryImgHtml'=> $this->getGalleryImgHtml($product, $helper),
            'imgZoomHtml' => $this->updateImgZoom($product, $helper),
            'priceHtml' => $productBlock->getPriceHtml($product, true)
        );
//        echo json_encode($arr);

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($arr));
    }

    public function getGalleryImgHtml($product, $helper){
//        $product = Mage::getModel('catalog/product')->load($productId);
        $_lightbox_w = Mage::getStoreConfig('prozoom/prozoom_config/lightbox_w');
        $_lightbox_h = Mage::getStoreConfig('prozoom/prozoom_config/lightbox_h');
        $_thumbnail_w = Mage::getStoreConfig('prozoom/prozoom_config/thumbnail_w');
        $_thumbnail_h = Mage::getStoreConfig('prozoom/prozoom_config/thumbnail_h');
        $html = '';
//        $helper = Mage::helper('catalog/image');
        foreach ($product->getMediaGalleryImages() as $_image) {
            $html .= "<li><a href= \"". $helper->init($product, 'image', $_image->getFile())->resize(1200, 1200)
                ."\" class= \"cloud-zoom-gallery\" title =\"" . Mage::helper('core')->escapeHtml($_image->getLabel())
                ."\" name=\"". $helper->init($product, 'image', $_image->getFile())->resize($_lightbox_w, $_lightbox_h)
                ."\" rel=\" useZoom: 'ma-zoom1', smallImage: '" . $helper->init($product, 'image', $_image->getFile())->resize(450, 450)
                ."'\">";
            $html .= "<img src =\""
                . $helper->init($product, 'thumbnail', $_image->getFile())->resize($_thumbnail_w, $_thumbnail_h)
                . "\" width= \"" . $_thumbnail_w . "\" height = \"" . $_thumbnail_h
                . "\" alt= \"". Mage::helper('core')->escapeHtml($_image->getLabel()) . "\"/></a></li>";
        }
        return $html;
    }

    public function getImageLabel($product = null, $mediaAttributeCode = 'image')
    {
        if (is_null($product)) {
            $product = $this->getProduct();
        }

        $label = $product->getData($mediaAttributeCode . '_label');
        if (empty($label)) {
            $label = $product->getName();
        }

        return $label;
    }

    public function updateImgZoom($_product, $helper){
        $_zoom_w = Mage::getStoreConfig('prozoom/prozoom_config/zoom_w');
        $_zoom_h = Mage::getStoreConfig('prozoom/prozoom_config/zoom_h');
//        $helper = Mage::helper('catalog/image');
        $_img = '<img src="'.$helper->init($_product, 'image')->resize($_zoom_w,$_zoom_h).'" alt="'.Mage::helper('core')->escapeHtml($this->getImageLabel($_product)).'" title="'.Mage::helper('core')->escapeHtml($this->getImageLabel($_product)).'" />';

        return "<a href=\"". $helper->init($_product, 'image')->resize(1200,1200). "\" class = \"cloud-zoom\" id=\"ma-zoom1\" style=\"position: relative; display: block;\"".
        " rel=\"adjustX:10, adjustY:-2, zoomWidth:500, zoomHeight:500\"> ".
        Mage::helper('catalog/output')->productAttribute($_product, $_img, 'image'). "</a>";
    }



}