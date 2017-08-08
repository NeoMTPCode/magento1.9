<?php
class Magentothem_Categorytabsliders_Block_Categorytabsliders_Advanced extends Mage_Catalog_Block_Product_Abstract
{
   protected function _construct()
   { 
     if($this->getConfig('enabled') != 1) return false;
	   parent::_construct();
   }
   
    protected function _beforeToHtml(){
        return parent::_beforeToHtml();
    }
    public function getTitle()
    {
        return $this->getData('title');
    }
	
	public function getIdentify() {
	    return $this->getData('identify');
    }
	
    public function getProductsCount()
    {
        return $this->getData('products_count');
    }
	
	public function getProductsOnRow() {
	    return $this->getData('product_on_row');
    }
	public function getConfig($att) {
		$config = Mage::getStoreConfig('categorytabsliders');
		if (isset($config['categorytabsliders_config']) ) {
			$value = $config['categorytabsliders_config'][$att];
			return $value;
		} else {
			throw new Exception($att.' value not set');
		}
	}
	
	function getProductCate($id = NULL) {
        $_rootcatID = Mage::app()->getStore()->getRootCategoryId();
		$storeId = Mage::app()->getStore()->getId();
        $_category = Mage::getModel('catalog/category')->load($id);
        $product = Mage::getModel('catalog/product');
        $json_products = array();
        //load the category's products as a collection
        $_productCollection = $product->getCollection()
                ->joinField('category_id','catalog/category_product','category_id','product_id=entity_id',null,'left')
				->addAttributeToSelect('*')
                ->addCategoryFilter($_category)
				->addAttributeToFilter('category_id', array('in' => $_rootcatID));
				Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($_productCollection);
				Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($_productCollection);
		$productLimits = $this->getProductsCount();
		if(!$productLimits) $productLimits = 10;
		$_productCollection->setPageSize($productLimits);
        $_productCollection->load();
		return $_productCollection;
		
    }
	
	public function cut_string_categorytab($string,$number) {
		if(strlen($string) <= $number) {
			return $string;
		}
		else {	
			if(strpos($string," ",$number) > $number){
				$new_space = strpos($string," ",$number);
				$new_string = substr($string,0,$new_space)."..";
				return $new_string;
			}
			$new_string = substr($string,0,$number)."..";
			return $new_string;
		}
	}
}
