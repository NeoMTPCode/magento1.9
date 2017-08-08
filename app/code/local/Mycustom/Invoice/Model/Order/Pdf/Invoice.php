<?php

class Mycustom_Invoice_Model_Order_Pdf_Invoice extends Mage_Sales_Model_Order_Pdf_Invoice{

    public function getPdf($invoices = array())
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new Zend_Pdf();
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);

        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($invoices as $invoice) {
            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->emulate($invoice->getStoreId());
                Mage::app()->setCurrentStore($invoice->getStoreId());
            }
            $page  = $this->newPage();
            $order = $invoice->getOrder();

            $this->insertHeaderCaption($page, $font);
            /* Add image */
//            $this->insertLogo($page, $invoice->getStore());
            /* Add address */
            $this->insertAddress($page, $invoice->getStore());
            /* Add head */
            $this->insertOrder(
                $page,
                $order,
                Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order->getStoreId())
            );
            /* Add document text and number */
//            $this->insertDocumentNumber(
//                $page,
//                Mage::helper('sales')->__('Invoice # ') . $invoice->getIncrementId()
//            );
//            $page->drawText(Mage::helper('sales')->__('Invoice # '). $invoice->getIncrementId());

            /* Add table */
            $this->_drawHeader($page);
            /* Add body */

            $index = 0;
            foreach ($invoice->getAllItems() as $item){
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                /* Draw STT */
                $page->drawText($index, 35, $this->y, 'UTF-8');
                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
                $index++;
            }
            /* Add totals */
            $this->insertTotals($page, $invoice);
            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->revert();
            }
        }
        $this->insertSignText($page);
        $this->_afterGetPdf();
        return $pdf;
    }

//    public function insertDocumentNumber(Zend_Pdf_Page $page, $text){
//        $number = substr($text, strrpos($text, ' ')+1);
//        $x = strpos($number, '0');
//        $root = substr($number, 0, $x);
//        $base = substr($number, $x);
//        parent::insertDocumentNumber($page, 'Number: ' . $root . '/' . $base);
//    }

    public function insertSignText($page)
    {
        $x = 25;
        $page->drawLine(25, $this->y, 570, $this->y);
        $this->y -= 15;
        $page->drawText(Mage::helper('sales')->__('Người mua (Buyer)'), $x, $this->y, 'UTF-8');
        $page->drawText(Mage::helper('sales')->__('Bên bán hàng (Seller)'), 300, $this->y, 'UTF-8');
        $this->y -= 15;
        $page->drawText(Mage::helper('sales')->__('Ký tên ghi rõ họ tên'), $x, $this->y, 'UTF-8');
        $page->drawText(Mage::helper('sales')->__('Ký tên ghi rõ họ tên'), 300, $this->y, 'UTF-8');
        $this->y -= 80;
        $page->drawText(Mage::helper('sales')->__('Đã nhận đủ hàng'), $x, $this->y, 'UTF-8');
    }

    public function insertHeaderCaption($page, $font)
    {
        $font_size = 12;
        $this->_setFontBold($page, $font_size);
        $this->y = 800;
        $x = 30;
        $company = 'Công ty trách nhiệm hữu hạn 2 thành viên shopbing';
        $address = 'Địa chỉ(Address): 140 Lê Trọng Tấn, Tây Thạnh, Tân Phú, Hồ Chí Minh';
        $phone = 'Số điện thoại(Tel): 0973722492';

        $textWidth = $this->getTextWidth($company, $font, $font_size);

        $page->drawText($company, ($page->getWidth() / 2) - ($textWidth/2), $this->y, 'UTF-8');
        $this->y -= 25;
//        $page->drawLine(25, $this->y, 570, $this->y);
        $x = 25;
//        $this->_setFontRegular($page, 10);
        $page->drawText($address, $x, $this->y, 'UTF-8');
        $this->y -= 25;
        $page->drawText($phone, $x , $this->y, 'UTF-8');
        $this->y -= 25;
    }

    public function getTextWidth($text, $font, $font_size)
    {
        $drawingString = iconv('UTF-8', 'UTF-16BE//IGNORE', $text);
        $characters = array();
        for ($i = 0; $i < strlen($drawingString); $i++) {
            $characters[] = (ord($drawingString[$i++]) << 8 ) | ord($drawingString[$i]);
        }
        $glyphs = $font->glyphNumbersForCharacters($characters);
        $widths = $font->widthsForGlyphs($glyphs);
        $stringWidth = (array_sum($widths) / $font->getUnitsPerEm()) * $font_size;
        return $stringWidth;
    }
}