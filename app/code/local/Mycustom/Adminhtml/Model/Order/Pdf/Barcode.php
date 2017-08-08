<?php
class Mycustom_Adminhtml_Model_Order_Pdf_Barcode extends Mage_Sales_Model_Order_Pdf_Abstract{

    public function getPdf($orderIds = array())
    {

        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);

        $this->x = 30;
        $this->y = 30;
        $page  = $this->newPage();
        $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 11);

        $symbology_order = Mage::helper('meanbee_barcodes/config')->getOrderNumberBarcodeSymbology();
        foreach ($orderIds as $orderId) {
            $_order = Mage::getModel('sales/order')->load($orderId);
            $src = 'http://api-bwipjs.rhcloud.com/?bcid='. $symbology_order. '&text=896'. $_order->getIncrementId();
            $this->insertImg($page, $src, $this->x, $this->y);

            $page->drawText($_order->getIncrementId(), $this->x +10, $this->y -80, 'UTF-8');
            $this->x += 140;
            if($this->x >= ($page->getWidth() - 140)){
                $this->y -= 110;
                $this->x = 30;
            }
        }
        $this->_afterGetPdf();
        return $pdf;
    }

    /**
     * Create new page and assign to PDF object
     *
     * @param  array $settings
     * @return Zend_Pdf_Page
     */
    public function newPage(array $settings = array())
    {
        /* Add new table head */
        $page = $this->_getPdf()->newPage(Zend_Pdf_Page::SIZE_A4);
        $this->_getPdf()->pages[] = $page;
        $this->y = 800;
        return $page;
    }

    public function insertImg(&$page, $src, $x1, $top = 830){
        $image       = $this->getImgFromUrl($src);
//        $top         = 830; //top border of the page
        $widthLimit  = 90; //half of the page width
        $heightLimit = 90; //assuming the image is not a "skyscraper"
        $width       = $image->getPixelWidth();
        $height      = $image->getPixelHeight();

        //preserving aspect ratio (proportions)
        $ratio = $width / $height;
        if ($ratio > 1 && $width > $widthLimit) {
            $width  = $widthLimit;
            $height = $width / $ratio;
        } elseif ($ratio < 1 && $height > $heightLimit) {
            $height = $heightLimit;
            $width  = $height * $ratio;
        } elseif ($ratio == 1 && $height > $heightLimit) {
            $height = $heightLimit;
            $width  = $widthLimit;
        }

        $y1 = $top - $height;
        $y2 = $top;
//        $x1 = 70;
        $x2 = $x1 + $width;

        //coordinates after transformation are rounded by Zend
        $page->drawImage($image, $x1, $y1, $x2, $y2);
    }

    public function getImgFromUrl($imageUrl){
        $imgPath = sys_get_temp_dir() . '/' . 'qr.png';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $imageUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $raw = curl_exec($ch);

        if (is_file($imgPath)) {
            unlink($imgPath);
        }

        $fp = fopen($imgPath, 'x');
        fwrite($fp, $raw);
        fclose($fp);
        $image = Zend_Pdf_Image::imageWithPath($imgPath);
        unlink($imgPath);
        return $image;
    }
}