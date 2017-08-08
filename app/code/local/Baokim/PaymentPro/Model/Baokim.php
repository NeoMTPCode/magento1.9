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
class Baokim_PaymentPro_Model_Baokim extends Mage_Payment_Model_Method_Abstract
{

	protected $_code = 'paymentpro';

	protected $_formBlockType = 'paymentpro/form_baokim';
	protected $_infoBlockType = 'paymentpro/info_baokim';
	protected $_canAuthorize = true;


	const BAOKIM_API_SELLER_INFO = '/payment/rest/payment_pro_api/get_seller_info';
	const BAOKIM_API_PAY_BY_CARD = '/payment/rest/payment_pro_api/pay_by_card';

	const BAOKIM_BPN = '/bpn/verify';

	const BAOKIM_LIVE = 'https://www.baokim.vn';
	const BAOKIM_TEST = 'http://kiemthu.baokim.vn';


	const BAOKIM_TRANSACTION_STATUS_COMPLETED = 4;	//Trạng thái giao dịch trên bảo kim: hoàn thành
	const BAOKIM_TRANSACTION_STATUS_TEMP_HOLDING = 13;	//trạng thái giao dịch trên bảo kim: đang tạm giữ

	const METHOD_IMMEDIATE = '2';
	const METHOD_SAFE = '1';

	const ORDER_STATUS_BAOKIM_PROCESS = 'process_baokim';
	const ORDER_STATUS_BAOKIM_COMPLETE = 'complete_baokim';

	/**
	 * Assign data to info model instance
	 *
	 * @param mixed $data
	 * @return $this|Mage_Payment_Model_Info
	 */
	public function assignData($data)
	{
		if (!($data instanceof Varien_Object)) {
			$data = new Varien_Object($data);
		}
		$info = $this->getInfoInstance();
		$info->setBankMethodId($data->getBankMethodId());

		Mage::getSingleton('core/session')->setMyCustomData($info->getBankMethodId());
		$details = array();
		if ($this->get_business_account()) {
			$details['business_account'] = $this->get_business_account();
		}
		if ($this->get_secure_pass()) {
			$details['secure_pass'] = $this->get_secure_pass();
		}
		if ($this->get_api_username()) {
			$details['api_username'] = $this->get_api_username();
		}
		if ($this->get_api_password()) {
			$details['api_password'] = $this->get_api_password();
		}
		if ($this->get_api_signature()) {
			$details['api_signature'] = $this->get_api_signature();
		}
		if ($this->get_payment_method()) {
			$details['payment_method'] = $this->get_payment_method();
		}
		if ($this->get_test_mode()) {
			$details['test_mode'] = $this->get_test_mode();
		}
		if ($this->get_test_mode()) {
			$details['test_mode'] = $this->get_test_mode();
		}

		return $this;
	}

	/**
	 * Validate payment method information object
	 *
	 * @internal param \Mage_Payment_Model_Info $info
	 * @return  Mage_Payment_Model_Abstract
	 */
	public function validate()
	{
		/*
        * calling parent validate function
        */
		parent::validate();
		$info = $this->getInfoInstance();
		$errorMsg = false;
		$bank_method_id = $info->getBankMethodId();
		if (empty($bank_method_id)) {
			$bank_method_id = $this->get_bank_method_id();
		}

		if (empty($bank_method_id)) {
			$errorMsg = Mage::helper('payment')->__('Bank payment method not found');
		}
		if ($errorMsg) {
			Mage::throwException($errorMsg);
		}
		//This must be after all validation conditions

		return $this;
	}

	/**
	 * Get Bank method id
	 *
	 * @return mixed
	 */
	public function get_bank_method_id()
	{
		if(!isset($_SESSION)){
			session_start();
		}
		$sessionfree = Mage::getSingleton('core/session', array('name' => 'frontend'));
		$bank_method_id = $sessionfree->getMyCustomData();
		return $bank_method_id;
	}

	public function get_business_account()
	{
		return $this->getConfigData('business_account');
	}

	public function get_secure_pass()
	{
		return $this->getConfigData('secure_pass');
	}

	public function get_api_username()
	{
		return $this->getConfigData('api_username');
	}

	public function get_api_password()
	{
		return $this->getConfigData('api_password');
	}

	public function get_api_signature()
	{
		return $this->getConfigData('api_signature');
	}

	public function get_payment_method()
	{
		if($this->getConfigData('payment_method')){
			return self::METHOD_IMMEDIATE;
		}
		return self::METHOD_SAFE;
	}

	public function get_test_mode()
	{
		return $this->getConfigData('test_mode');
	}

	public function getOrderPlaceRedirectUrl()
	{
		return Mage::getUrl('paymentpro/index/redirect', array('_secure' => true));
	}

	/**
	 * Call API GET_SELLER_INFO
	 *  + Create bank list show to frontend
	 *
	 * @param $method_code
	 * @return string
	 */
	public function get_seller_info($method_code)
	{
		$param = array(
			'business' => $this->get_business_account(),
		);
		$call_restfull = new Baokim_PaymentPro_Model_CallRestful();
		$call_API = $call_restfull->call_API("GET", $param, self::BAOKIM_API_SELLER_INFO, $this);
		if (is_array($call_API)) {
			if (isset($call_API['error'])) {
				//Mage::getSingleton('paymentpro/baokimPayment')->addError("call_API".json_encode($call_API['error']));
				return "<strong style='color:red'>call_API" . json_encode($call_API['error']) . "- code:" . $call_API['status'] . "</strong> - " . "System error. Please contact to administrator";
			}
		}

		$seller_info = json_decode($call_API, true);
		if (!empty($seller_info['error'])) {
			//	Mage::getSingleton('paymentpro/baokimPayment')->addError("eller_info".json_encode($seller_info['error']));
			return "<strong style='color:red'>eller_info" . json_encode($seller_info['error']) . "</strong> - " . "System error. Please contact to administrator";
		}

		$banks = $seller_info['bank_payment_methods'];
		if ($this->get_test_mode()) {
			$baokim_mode = 'thử nghiệm';
		} else {
			$baokim_mode = 'thực tế';
		}
		$html_plus = '</br>';
		$html_plus .= '<span class="vmpayment_cardinfo" style="margin-left: 16px;">Chọn thẻ tín dụng của bạn muốn sử dụng.  <b>Đây là một giao dịch ' . $baokim_mode . ' </b>';
		$html_plus .= '</span>';
		$html_plus .= '<div class="content" id="payment">';
		$html_plus .= '<div class="credit-card">';
		$i = 0;
		foreach ($banks as $bank) {
			if ($bank['payment_method_type'] == 1 || $bank['payment_method_type'] == 2) {
				$html_plus .= '<img src=' . $bank['logo_url'] . ' class="logo_bank" onClick="choose_bank(this.id)" id="bank_' . $bank['id'] . '"/>';
			}
		}
		$html_plus .= '<input type="hidden" value="" id="' . $method_code . '_bank_method_id" name="payment[bank_method_id]"/>';
		$html_plus .= '</div>';
		$html_plus .= '</div>';

		return $html_plus;
	}

	/**
	 * Call API PAY_BY_CARD
	 *  + Get Order info
	 *  + Sent order, action payment
	 *
	 * @param $orderid
	 * @return mixed
	 */
	public function pay_by_card($orderid)
	{
		$_order = Mage::getModel('sales/order')->loadByIncrementId($orderid);

		$bank_method_id = $this->get_bank_method_id();
		$getGrandTotal = $_order->getGrandTotal();
		$getGrandTotalArr = explode(".", $getGrandTotal);
		$getGrandTotalArr0 = $getGrandTotalArr[0];
		$getGrandTotalArr1 = $getGrandTotalArr[1];
		$getGrandTotalArr1 = substr($getGrandTotalArr1, 0, 2);
		$amount_total = $getGrandTotalArr0 . '.' . $getGrandTotalArr1;

		$url_success = Mage::getUrl('paymentpro/index/success');
		$url_cancel = Mage::getUrl('paymentpro/index/cancel');
		$params['business'] = strval($this->get_business_account());
		$params['bank_payment_method_id'] = $bank_method_id;
		$params['transaction_mode_id'] = strval($this->get_payment_method());
		$params['escrow_timeout'] = 3;

		$params['order_id'] = $orderid;
		$params['total_amount'] = $amount_total;
		$params['shipping_fee'] = strval($_order->getBaseShippingAmount()); //isset($method->no_shipping) ? $method->no_shipping : 0,
		$params['tax_fee'] = strval($_order->getBaseTaxAmount());
		$params['currency_code'] = strval($_order->getBaseCurrencyCode());

		$params['url_success'] = $url_success;
		$params['url_cancel'] = $url_cancel;
		$params['url_detail'] = '';

		$params['order_description'] = 'Thanh toán đơn hàng từ Website '. Mage::getUrl('') . ' với mã đơn hàng ' . $orderid;
		$params['payer_name'] = strval($_order->getCustomerFirstname() . " " . $_order->getCustomerLastname());
		$params['payer_email'] = strval($_order->getCustomerEmail());
		$params['payer_phone_no'] = strval($_order->getBillingAddress()->getTelephone());
		$params['payer_address'] = 'Địa chỉ thanh toán';

		$call_restfull = new Baokim_PaymentPro_Model_CallRestful();
		$result = json_decode($call_restfull->call_API("POST", $params, self::BAOKIM_API_PAY_BY_CARD, $this), true);

		return $result;
	}

	/**
	 * Hàm thực hiện nhận và kiểm tra dữ liệu từ Bảo Kim
	 *
	 * @return bool
	 */
	public function check_bpn_request_is_valid()
	{
		$req = '';
		//Kiểm tra sự tồn tại dữ liệu nhận từ BaoKim
		if (empty($_POST)) {
			Mage::log("Baokim payment pro : Khong nhan duoc du lieu tu BaoKim", null, 'BaoKim.log');
			return false;
		}

		//Lấy url verify BPN
		if ($this->get_test_mode()) {
			$baokim_url = self::BAOKIM_TEST.self::BAOKIM_BPN;
		}else{
			$baokim_url = self::BAOKIM_LIVE.self::BAOKIM_BPN;
		}

		//Kiểm tra thư viện cURL
		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
		Mage::log("Baokim payment pro : Check data BPN is validation...", null, 'BaoKim.log');
		Mage::log("Baokim payment pro : BPN Data"  . print_r($_POST, true), null, 'BaoKim.log');

		/**
		 * Gửi dữ liệu về Bảo Kim. Kiểm tra tính chính xác của dữ liệu
		 * @param $result Kết quả xác thực thông tin trả về.
		 * @paran $status Mã trạng thái trả về.
		 * @error $error  Lỗi được ghi vào file bpn.log
		 */
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $baokim_url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error = curl_error($ch);
		Mage::log("Baokim payment pro : Data sent \r\n"  . $req, null, 'BaoKim.log');
		Mage::log("Baokim payment pro : Confirm with - "  .$baokim_url, null, 'BaoKim.log');
		if ($result != '' && strstr($result, 'VERIFIED') && $status == 200) {
			Mage::log("Baokim payment pro : => VERIFIED" , null, 'BaoKim.log');
			return true;
		} else {
			Mage::log("Baokim payment pro : => INVALID" , null, 'BaoKim.log');
		}
		if ($error)
			Mage::log("Baokim payment pro :=> | ERROR:" . $error , null, 'BaoKim.log');

		return false;
	}

	/**
	 * Thực hiện cập nhập trạng thái đon hàng sau khi hoàn thiện kiểm tra thông tin thanh toán
	 */
	public function successful_request()
	{
		$order_id = $_POST['order_id'];
		$transaction_id = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : '';
		$transaction_status = isset($_POST['transaction_status']) ? $_POST['transaction_status'] : '';
		$total_amount = isset($_POST['total_amount']) ? $_POST['total_amount'] : '';
		$currency_rate = isset($_POST['usd_vnd_exchange_rate']) ? $_POST['usd_vnd_exchange_rate'] : '';
		$net_amount = isset($_POST['net_amount']) ? $_POST['net_amount'] : '';
		$fee_amount = isset($_POST['fee_amount']) ? $_POST['fee_amount'] : '';
		$customer_name = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';
		$customer_address = isset($_POST['customer_address']) ? $_POST['customer_address'] : '';

		//Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
		$isVaild = $this->isValidOrderInfo($transaction_status, $total_amount, $order_id);
		if ($isVaild) {
			//TODO: trường hợp đối soát thông tin BPN thành công => hoàn thành đơn hàng (website merchant có thể edit lại phần này theo yêu cầu)
			$order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
			$comment_status = '';
			$order_status = '';
			switch ($transaction_status) {
				case 4:

					$comment_status = 'Nhận BPN : Thực hiện thanh toán thành công với đơn hàng # ' . $order_id . '. Giao dịch hoàn thành.';
					$order_status = self::ORDER_STATUS_BAOKIM_COMPLETE;
					break;
				case 13:
					$comment_status = 'Nhận BPN : Thực hiện thanh toán thành công với đơn hàng # ' . $order_id . '.Giao dịch đang tạm giữ.';
					$order_status = self::ORDER_STATUS_BAOKIM_PROCESS;
					break;
			}
//			$order->addStatusHistoryComment($comment_status, false);
//			$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING,$order_status, true)->save();

			$order->addStatusHistoryComment($comment_status,$order_status) ->setIsCustomerNotified(false);
			$order->setStatus(Mage_Sales_Model_Order::STATE_PROCESSING);
			$order->save();

			$comments = 'Bao Kim xac nhan don hang [' . $order_status . ']';
			Mage::log("Baokim payment pro : " . $comments , null, 'BaoKim.log');
		}
	}

	/**
	 * Kiểm tra thông tin đơn hàng và đối soát với thông tin trên BPN gồm:
	 *          - Trạng thái giao dịch.
	 *          - Mã đơn hàng.
	 *          - Số tiền giao dịch.
	 *
	 * @param $transaction_status Trạng thái giao dịch từ BaoKim
	 *                           4 : giao dịch hoàn thành
	 *                          13 : Giao dịch tạm giữ
	 *
	 * @param $total_amount     Số tiền thanh toán ở BaoKim
	 * @param $order_id         Mã đơn hàng thanh toán từ BaoKim
	 * @return bool             True : Không xảy ra lỗi trong quá trình kiểm thông tin.
	 *                          False : Có lỗi trong quá trình kiểm tra thông tin. Tiến hành ghi log.
	 */
	public function isValidOrderInfo($transaction_status, $total_amount, $order_id)
	{
		$confirm = '';

		//Danh sách các trạng thái giao dịch có thể coi là thành công (có thể giao hàng)
		$success_transaction_status = array(self::BAOKIM_TRANSACTION_STATUS_COMPLETED, self::BAOKIM_TRANSACTION_STATUS_TEMP_HOLDING);

		//Kiểm tra trạng thái giao dịch
		if (in_array($transaction_status, $success_transaction_status)) {

			//Lấy thông tin order
			if (!is_numeric($order_id) && ($order_id == 0)) {
				$confirm .= "\r\n" . 'Invalid order id: # ' . $order_id;
			}

			//Kiểm tra sự tồn tại của đơn hàng
			$order_info = Mage::getModel('sales/order')->loadByIncrementId($order_id);
			$order_data = $order_info->getData();

			if(empty($order_data)) {
				$confirm .= "\r\n" . "This order doesn't exist with order id: # "  . $order_id;
			}

			//Kiểm tra số tiền đã thanh toán phải >= giá trị đơn hàng
			//Lấy giá trị đơn hàng
			$getGrandTotal = $order_info->getGrandTotal();
			$getGrandTotalArr = explode(".", $getGrandTotal);
			$getGrandTotalArr0 = $getGrandTotalArr[0];
			$getGrandTotalArr1 = $getGrandTotalArr[1];
			$getGrandTotalArr1 = substr($getGrandTotalArr1, 0, 2);
			$order_amount_total = $getGrandTotalArr0 . '.' . $getGrandTotalArr1;

			if ($total_amount < $order_amount_total) {
				$confirm .= "\r\n" . 'Receipt amount : ' . $total_amount . ' less than purchase order value: '.$order_amount_total .' with order id # ' . $order_id;
			}

		} else {
			$confirm .= "\r\n" . 'Transaction status invalid :' . $transaction_status;
		}

		if ($confirm == '') {
			return true;
		}
		$confirm .= "\r\n" . '=>  ERROR';
		Mage::log("Baokim payment pro : " . $confirm, null, 'BaoKim.log');
		return false;
	}

}