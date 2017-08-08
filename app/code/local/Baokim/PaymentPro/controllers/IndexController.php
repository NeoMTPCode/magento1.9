<?php
/**
 * Plugin Name: BaoKim Payment Pro Gateway
 * Description: Thanh toán với Bảo Kim đảm bảo tuyệt đối cho mọi giao dịch
 * - Tích hợp thanh toán qua baokim.vn cho các merchant site có đăng ký API.
 * - Thực hiện lấy thông tin tài khoản người bán                             *
 *          danh sách các phương thức thanh toán ngân hàng qua email
 * - Gửi thông tin thanh toán tới baokim.vn để xử lý việc thanh toán.
 * - Xác thực tính chính xác của thông tin được gửi về từ baokim.vn
 * Version: 1.0
 * Author: hieunn
 * Author URI: http://developer.baokim.vn/
 * License: BaoKim, Jsc 2013
 */
class Baokim_PaymentPro_IndexController extends Mage_Core_Controller_Front_Action
{
	public function redirectAction()
	{
//		$session = Mage::getSingleton('checkout/session');
//
//		if ($this->getRequest()->getParam('paymentpro') != 1) {
//			$result = Mage::getModel('paymentpro/baokim')->pay_by_card($session->getLastRealOrderId());
//
//			if(!empty($result['error'])){
//				$errorMsg = Mage::helper('payment')->__($result['error']);
//				if ($errorMsg) {
//					Mage::throwException($errorMsg);
//				}
//			}
//			$baokim_url = $result['redirect_url'] ? $result['redirect_url'] : $result['guide_url'];
//			$this->_redirectUrl($baokim_url);
//		}
	}

	/**
	 * When a customer success payment from Baokim.
	 */
	public function  successAction()
	{
		$secure_pass = Mage::getModel('paymentpro/baokim')->get_secure_pass();
		$dataGet = $_GET;
		if(empty($secure_pass)){
			Mage::log("Baokim payment pro : Secure pass is missing", null, 'BaoKim.log');
			$errorMsg = Mage::helper('payment')->__("Secure pass is missing. Can't complete order");
			if ($errorMsg) {
				Mage::throwException($errorMsg);
			}
		}

		if(empty($_GET['checksum'])){
			Mage::log("Baokim payment pro : Invalid parameters: checksum is missing", null, 'BaoKim.log');
			return $this->_redirect('checkout/onepage/failure', array('_secure' => true));
		}
		$order_id = $_GET['order_id'];
		$checksum = $_GET['checksum'];
		unset($_GET['checksum']);
		ksort($_GET);
		if(strcasecmp($checksum,hash_hmac('SHA1',implode('',$_GET),$secure_pass))===0){
			$order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
			$order->addStatusHistoryComment('Baokim Transaction Process',Baokim_PaymentPro_Model_Baokim::ORDER_STATUS_BAOKIM_PROCESS) ->setIsCustomerNotified(false);
			$order->setStatus(Mage_Sales_Model_Order::STATE_PROCESSING);
			$order->save();

			return $this->_redirect('checkout/onepage/success', array('_secure' => true));
		}else{
			Mage::log("Baokim payment pro : Invalid data: Validate checksum from Baokim return fail - paymentpro/index/success : " . print_r($dataGet, true), null, 'BaoKim.log');
			return $this->_redirect('checkout/onepage/failure', array('_secure' => true));
		}

	}

	/**
	 * When a customer cancel payment from Baokim.
	 */
	public function cancelAction(){
		$session = Mage::getSingleton('checkout/session');
		if ($session->getLastRealOrderId()) {
			$order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
			if ($order->getId()) {
				$order->cancel()->save();
			}
			Mage::helper('paymentpro/data')->restoreQuote();
		}
//		$this->_redirect('checkout/cart');
	}

	public function bpnAction(){
		if (Mage::getModel('paymentpro/baokim')->check_bpn_request_is_valid()) {
			Mage::getModel('paymentpro/baokim')->successful_request();
		}
	}

}