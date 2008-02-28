<?php
//
// Payline Class v1.0.2 - 2007-11-23
// Copyright 2007 Experian
//


//
// OBJECTS DEFINITIONS
//

class util{

	/**
	 * make an array from a payline server response object.
	 * @params : $response : Objet response from experian
	 * @return : Object convert in an array
	**/
	static function responseToArray($response){
		$array = array();
		foreach($response as $k=>$v){
			if (is_object($v)) { $array[$k] = util::responseToArray($v); }
			else { $array[$k] = $v; }
		}
		return $array;
	}
}

//
// PL_PAYMENT OBJECT DEFINITION
//
class pl_payment{

	// ATTRIBUTES LISTING
	public $amount;
	public $currency;
	public $action;
	public $mode;
	public $contractNumber;
	public $differedActionDate;

	function __construct() {
		$this->currency = PAYMENT_CURRENCY;
		$this->action = PAYMENT_ACTION;
		$this->mode = PAYMENT_MODE; 
		$this->contractNumber = CONTRACT_NUMBER;
	}
}

//
// PL_ORDER OBJECT DEFINITION
//
class pl_order{

	// ATTRIBUTES LISTING
	public $ref;
	public $origin;
	public $country;
	public $taxes;
	public $amount;
	public $currency;
	public $date;
	public $quantity;
	public $comment;
	public $details;

	function __construct() {
		$this->date = date('d/m/Y H:i', time());
		$this->currency = ORDER_CURRENCY;
		$this->details = array();
	}
}

//
// PL_PRIVATEDATA OBJECT DEFINITION
//
class pl_privateData{

	// ATTRIBUTES LISTING
	public $key ;
	public $value;
}

//
// PL_ADDRESS OBJECT DEFINITION
//
class  pl_address{

	// ATTRIBUTES LISTING
	public $name;
	public $street1;
	public $street2;
	public $cityName;
	public $zipCode;
	public $country;
	public $phone;
}

//
// PL_BUYER OBJECT DEFINITION
//
class pl_buyer{

	// ATTRIBUTES LISTING
	public $email;
	public $shippingAdress;
	public $accountCreateDate;
	public $accountAverageAmount;
	public $accountOrderCount;

	function __construct() {
		$this->accountCreateDate = date('d/m/y', time());
	}
}

//
// PL_ORDERDETAIL OBJECT DEFINITION
//
class pl_orderDetail{
	
	// ATTRIBUTES LISTING
	public $ref;
	public $price;
	public $quantity;
	public $comment;
}

//
// PL_CARD OBJECT DEFINITION
//
class pl_card{

	// ATTRIBUTES LISTING
	public $number;
	public $type;
	public $expirationDate;
	public $cvx;
	public $ownerBirthdayDate;
	public $password;

	function __construct($type) {
		$this->accountCreateDate = date('d/m/y', time());
	}	
}

//
// PL_TRANSACTION OBJECT DEFINITION
//
class pl_transaction{

	// ATTRIBUTES LISTING
	public $id;
	public $isPossibleFraud;
	public $isDuplicated;
	public $date;
}


//
// PL_RESULT OBJECT DEFINITION
//
class pl_result{

	// ATTRIBUTES LISTING
	public $code;	
	public $shortMessage;	
	public $longMessage;
}

//
// PL_CAPTURE OBJECT DEFINITION
//
class pl_capture{

	// ATTRIBUTES LISTING
	public $transactionID;
	public $payment;
	
	function __construct() {
		$this->payment = new pl_payment();
	}
}

//
// PL_REFUND OBJECT DEFINITION
//
class pl_refund extends pl_capture {
	function __construct() {
		parent::__construct();
	}
}

//
// PAYLINESDK CLASS
//
class paylineSDK{

	// SOAP URL's
	const URL_SOAP = "http://obj.ws.payline.experian.com"; 
	public $WSDL_SOAP = "http://www.payline.com/wsdl/v4_0/homologation/WebPaymentAPI.wsdl";
	public $WSDL_DIRECT_SOAP = "http://www.payline.com/wsdl/v4_0/homologation/DirectPaymentAPI.wsdl";
	public $WSDL_MASS_SOAP = "http://www.payline.com/wsdl/v4_0/homologation/MassPaymentAPI.wsdl";

	// SOAP ACTIONS CONSTANTS
	const soap_result = 'result';
	const soap_authorization = 'authorization';
	const soap_card = 'card';
	const soap_order = 'order';
	const soap_orderDetail = 'orderDetail';
	const soap_payment = 'payment';
	const soap_transaction = 'transaction';
	const soap_privateData = 'privateData';
	const soap_buyer = 'buyer';
	const soap_address = 'address';
	const soap_capture = 'capture';
	const soap_refund = 'refund';
	const soap_refund_auth = 'refundAuthorization';

	// ARRAY
	public $header_soap;
	public $items;
	public $privates;
	// ARRAY MASS
	public $massCapture;
	public $massRefund;

	// OPTIONS
	public $cancelURL = CANCEL_URL;
	public $securityMode = SECURITY_MODE;
	public $notificationURL = NOTIFICATION_URL;
	public $returnURL = RETURN_URL;
	public $customPaymentPageCode = CUSTOM_PAYMENT_PAGE_CODE;
	public $languageCode = LANGUAGE_CODE;

	/**
	 * contructor of PAYLINESDK CLASS
	**/
	function __construct() {
		$this->header_soap = array();
		$this->header_soap['proxy_host'] = $this->proxy_host = PROXY_HOST;
		$this->header_soap['proxy_port'] = $this->proxy_port = PROXY_PORT;
		$this->header_soap['proxy_login'] = $this->proxy_login = PROXY_LOGIN;
		$this->header_soap['proxy_password'] = $this->proxy_password = PROXY_PASSWORD;
		$this->header_soap['login'] = $this->login = MERCHANT_ID;
		$this->header_soap['password'] = $this->password = ACCESS_KEY;
		$this->header_soap['style'] = SOAP_DOCUMENT;
		$this->header_soap['use'] = SOAP_LITERAL;
		$this->items = array();
		$this->privates = array();
		$this->massCapture = array();
		$this->massRefund = array();
		if(PRODUCTION) {
			$this->WSDL_SOAP = "http://www.payline.com/wsdl/v4_0/production/WebPaymentAPI.wsdl";
			$this->WSDL_DIRECT_SOAP = "http://www.payline.com/wsdl/v4_0/production/DirectPaymentAPI.wsdl";
			$this->WSDL_MASS_SOAP = "http://www.payline.com/wsdl/v4_0/production/MassPaymentAPI.wsdl";
		}
	}

	/**
	 * function payment 
	 * @params : $array : array. the array keys are listed in pl_payment CLASS.
	 * @return : SoapVar : object
	 * @description : build pl_payment instance from $array and make SoapVar object for payment.
	**/
	protected function payment($array) {
		$payment = new pl_payment();
		if($array && is_array($array)){
			foreach($array as $k=>$v){
				if(array_key_exists($k, $payment)&&(strlen($v))){
					$payment->$k = $v;
				}
			}
		}
		return new SoapVar($payment, SOAP_ENC_OBJECT, paylineSDK::soap_payment, paylineSDK::URL_SOAP);
	}

	/**
	 * function order 
	 * @params : $array : array. the array keys are listed in pl_order CLASS.
	 * @return : SoapVar : object
	 * @description : build pl_order instance from $array and make SoapVar object for order.
	**/
	protected function order($array) {
		$order = new pl_order();
		if($array && is_array($array)){	
			foreach($array as $k=>$v){
				if(array_key_exists($k, $order)&&(strlen($v))){
					$order->$k = $v;
				}
			}
		}
		$allDetails = array();
		// insert orderDetails
		$order->details = $this->items;
		return new SoapVar($order, SOAP_ENC_OBJECT, paylineSDK::soap_order, paylineSDK::URL_SOAP);
	}


	/**
	 * function address 
	 * @params : $address : array. the array keys are listed in pl_address CLASS.
	 * @return : SoapVar : object
	 * @description : build pl_address instance from $array and make SoapVar object for address.
	**/
	protected function address($array) {
		$address = new pl_address();
		if($array && is_array($array)){
			foreach($array as $k=>$v){
				if(array_key_exists($k, $address)&&(strlen($v)))$address->$k = $v;
			}
		}
		return new SoapVar($address, SOAP_ENC_OBJECT, paylineSDK::soap_address, paylineSDK::URL_SOAP);
	}

	/**
	 * function buyer 
	 * @params : $array : array. the array keys are listed in pl_buyer CLASS. 
	 * @params : $address : array. the array keys are listed in pl_address CLASS.
	 * @return : SoapVar : object
	 * @description : build pl_buyer instance from $array and $address and make SoapVar object for buyer.
	**/
	protected function buyer($array,$address) {
		$buyer = new pl_buyer();
		if($array && is_array($array)){
			foreach($array as $k=>$v){
				if(array_key_exists($k, $buyer)&&(strlen($v)))$buyer->$k = $v;
			}
		}
		$buyer->shippingAdress = $this->address($address);
		return new SoapVar($buyer, SOAP_ENC_OBJECT, paylineSDK::soap_buyer, paylineSDK::URL_SOAP);
	}

	/**
	 * function contracts 
	 * @params : $contracts : array. array of contracts
	 * @return : $contracts : array. the same as params if exist, or an array with default contract defined in 
	 * configuration
	 * @description : Add datas to contract array
	**/
	protected function contracts($contracts) {
		if($contracts && is_array($contracts)){
			return $contracts;
		}
		return array(CONTRACT_NUMBER);
	}

	/**
	 * function getHeader
	 * @return : header_soap : array. see class contructor for array keys listing.
	 * @description : Return soap header
	**/
	public function getHeader() {
		return $this->header_soap;
	}

	/**
	 * function card 
	 * @params : $array : array. the array keys are listed in pl_card CLASS.
	 * @return : SoapVar : object
	 * @description : build pl_card instance from $array and make SoapVar object for card.
	**/
	protected function card($array) {
		$card = new pl_card($array['type']);
		if($array && is_array($array)){
			foreach($array as $k=>$v){
				if(array_key_exists($k, $card)&&(strlen($v))){
					$card->$k = $v;
				}
			}
		}
		return new SoapVar($card, SOAP_ENC_OBJECT, paylineSDK::soap_card, paylineSDK::URL_SOAP);
	}

	/**
	 * function setItem 
	 * @params : $item : array. the array keys are listed in PL_ORDERDETAIL CLASS.
	 * @description : Make $item SoapVar object and insert in items array
	**/
	public function setItem($item) {
		$orderDetail = new pl_orderDetail();
		if($item && is_array($item)){
			foreach($item as $k=>$v){
				if(array_key_exists($k, $orderDetail)&&(strlen($v)))$orderDetail->$k = $v;
			}
		}
		$this->items[] = new SoapVar($orderDetail, SOAP_ENC_OBJECT, paylineSDK::soap_orderDetail, paylineSDK::URL_SOAP);
	}

	/**
	 * function setPrivate 
	 * @params : $private : array.  the array keys are listed in PRIVATE CLASS.
	 * @description : Make $setPrivate SoapVar object  and insert in privates array
	**/
	public function setPrivate($array) {
		$private = new pl_privateData();
		if($array && is_array($array)){
			foreach($array as $k=>$v){
				if(array_key_exists($k, $private)&&(strlen($v)))$private->$k = $v;
			}
		}
		$this->privates[] = new SoapVar($private, SOAP_ENC_OBJECT, paylineSDK::soap_privateData, paylineSDK::URL_SOAP);
	}


	/****************************************************/
	//						WEB							//
	/****************************************************/

	/**
	 * function do_webpayment 
	 * @params : $array : array. the array keys are :
	 * payment, returnURL, cancelURL, order, notificationURL, contracts, 
	 * customPaymentPageCode, languageCode, securityMode, buyer, address
	 * @params : $debug : boolean . TRUE/FALSE or 0/1 
	 * @return : Array. Array from a payline server response object.
	 * @description : Do a payment request
	**/
	public function do_webpayment($array,$debug=0) {
		try{
			if($array && is_array($array)){
				if(isset($array['cancelURL'])&& strlen($array['cancelURL'])) $this->cancelURL = $array['cancelURL'];
				if(isset($array['notificationURL']) && strlen($array['notificationURL'])) $this->notificationURL = $array['notificationURL'];
				if(isset($array['returnURL'])&& strlen($array['returnURL'])) $this->returnURL = $array['returnURL'];
				if(isset($array['customPaymentPageCode'])&& strlen($array['customPaymentPageCode'])) $this->customPaymentPageCode = $array['customPaymentPageCode'];
				if(isset($array['languageCode'])&& strlen($array['languageCode'])) $this->languageCode = $array['languageCode'];
				if(isset($array['securityMode'])&& strlen($array['securityMode'])) $this->securityMode = $array['securityMode'];
				if(!isset($array['payment']))$array['payment'] = null;
				if(!isset($array['contracts'])||!strlen($array['contracts'][0]))$array['contracts'] = split(";", CONTRACT_NUMBER_LIST);
				if(!isset($array['buyer']))$array['buyer'] = null;	
				if(!isset($array['address']))$array['address'] = null;

				$doWebPaymentRequest = array (
					'payment' => $this->payment($array['payment']),
					'returnURL' => $this->returnURL,
					'cancelURL' => $this->cancelURL,
					'order' => $this->order($array['order']),
					'notificationURL' => $this->notificationURL,
					'selectedContractList' => $this->contracts($array['contracts']),
					'publicDataList' => $this->privates,
					'languageCode' => $this->languageCode,
					'customPaymentPageCode' => $this->customPaymentPageCode,
					'buyer' => $this->buyer($array['buyer'],$array['address']),
					'securityMode' => $this->securityMode);

				if($debug) {
					return util::responseToArray($doWebPaymentRequest);
				} else {
					$client = new SoapClient( $this->WSDL_SOAP, $this->header_soap);
					$doWebPaymentResponse = $client->doWebPayment($doWebPaymentRequest);
					return util::responseToArray($doWebPaymentResponse);
				}
			}
		}
		catch ( Exception $e ) {
			echo '<strong>ERROR : ' . $e->getMessage() . '</strong><br/>';
		}
	}	

	/**
	 * function get_webPaymentDetails 
	 * @params : $token : string
	 * @return : Array. Array from a payline server response object.
	 * @description : Get payment details
	**/
	public function get_webPaymentDetails($token) {
		try{
		$getWebPaymentDetailsRequest = array ('token' => $token);
		$client = new SoapClient($this->WSDL_SOAP, $this->header_soap);
		$getWebPaymentDetailsResponse = $client->getWebPaymentDetails($getWebPaymentDetailsRequest);
		return util::responseToArray($getWebPaymentDetailsResponse);
		}
		catch ( Exception $e ) {
			echo '<strong>ERROR : ' . $e->getMessage() . '</strong><br/>';
		}
		
	}


	/****************************************************/
	//						DIRECT						//
	/****************************************************/	

	/**
	 * function do_authorization 
	 * @params : $array : array. the array keys are :
	 * payment, card, order, privateDataList, buyer
	 * @return : Array. Array from a payline server response object.
	 * @description : Do a payment authorization
	**/
	public function do_authorization($array) {
		try{
		$doAuthorizationRequest = array (
			'payment' => $this->payment($array['payment']),
			'card' =>  $this->card($array['card']),
			'order' => $this->order($array['order']),
			'privateDataList' =>  $this->privates,
			'buyer' => $this->buyer($array['buyer'],$array['address']));
		$client = new SoapClient($this->WSDL_DIRECT_SOAP, $this->header_soap);
		$doAuthorizationResponse = $client->doAuthorization($doAuthorizationRequest);
		return util::responseToArray($doAuthorizationResponse);
				}
		catch ( Exception $e ) {
			echo '<strong>ERROR : ' . $e->getMessage() . '</strong><br/>';
		}
	}

	/**
	 * function do_capture 
	 * @params : $array : array. the array keys are: transactionID, payment
	 * @return : Array. Array from a payline server response object.
	 * @description : Do a payment capture
	**/
	public function do_capture($array) {
		try{
		$doCaptureRequest = array (
			'transactionID' =>$array['transactionID'],
			'payment' =>  $this->payment($array['payment']));
		$client = new SoapClient($this->WSDL_DIRECT_SOAP, $this->header_soap);
		$doCaptureResponse = $client->doCapture($doCaptureRequest);
		return util::responseToArray($doCaptureResponse);
		}
		catch ( Exception $e ) {
			echo '<strong>ERROR : ' . $e->getMessage() . '</strong><br/>';
		}		
	} 

	/**
	 * function do_refund 
	 * @params : $array : array. the array keys are :
	 * transactionID, payment, comment
	 * @return : Array. Array from a payline server response object.
	 * @description : Do a payment refund
	**/
	public function do_refund($array) {
		try{
		$doRefundRequest = array (
			'transactionID' =>$array['transactionID'],
			'payment' =>$this->payment($array['payment']),
			'comment' =>$array['comment']);
		$client = new SoapClient($this->WSDL_DIRECT_SOAP, $this->header_soap);
		$doRefundResponse = $client->doRefund($doRefundRequest);
		return util::responseToArray($doRefundResponse);
		}
		catch ( Exception $e ) {
			echo '<strong>ERROR : ' . $e->getMessage() . '</strong><br/>';
		}
	} 

	/**
	 * function do_credit 
	 * @params : $array : array. the array keys are :
	 * transactionID, payment, card, comment
	 * @return : Array. Array from a payline server response object.
	 * @description : Do a payment credit
	**/
	public function do_credit($array) {
		try{
		$doCreditRequest = array (
			'payment' => $this->payment($array['payment']),
			'card' =>  $this->card($array['card']),
			'comment' =>$array['comment']);
		$client = new SoapClient($this->WSDL_DIRECT_SOAP, $this->header_soap);
		$doCreditResponse = $client->doCredit($doCreditRequest);
		return util::responseToArray($doCreditResponse);
		}
		catch ( Exception $e ) {
			echo '<strong>ERROR : ' . $e->getMessage() . '</strong><br/>';
		}		
	}
	
/****************************************************/
// 				MASS					//
/****************************************************/ 

/**
 * function setCapture 
 * @params : $array : array.  the array keys are listed in PL_CAPTURE CLASS.
 * @description : Make pl_capture object and insert in massCapture array
**/
public function setCapture($array){
	try{
		$capture = new pl_capture();
		if($array && is_array($array)){
			if(array_key_exists('transactionID', $capture)&&(strlen($array['transactionID']))){
				$capture->transactionID = $array['transactionID'];
				unset( $array['transactionID']);
			}	
			foreach($array as $k=>$v){
				if(array_key_exists($k, $capture->payment)&&(strlen($v))){
					$capture->payment->$k = $v;
				}
			}
			
		}
		//$this->massCapture[] = new SoapVar($capture, SOAP_ENC_OBJECT, paylineSDK::soap_capture, paylineSDK::URL_SOAP);
		$this->massCapture[] = $capture;
	}
	catch ( Exception $e ) {
		echo '<strong>ERROR : ' . $e->getMessage() . '</strong><br/>';
	}	
}

/**
 * function do_massCapture 
 * @params : $comment : a comment.
 * @return : Array. Array from a payline server response object.
 * @description : Make a doMassCaptureRequest with massCapture array, and comment
**/  
public function do_massCapture($comment){
	try{
		$doMassCaptureRequest = array (
		'captureAuthorizationList'=> $this->massCapture,
		'comment'=> $comment
		);
		$client = new SoapClient($this->WSDL_MASS_SOAP, $this->header_soap);
		$doMassCaptureResponse = $client->doMassCapture($doMassCaptureRequest);
		return util::responseToArray($doMassCaptureResponse);
	}
	catch ( Exception $e ) {
		echo '<strong>ERROR : ' . $e->getMessage() . '</strong><br/>';
	}
} 


/**
 * function setRefund 
 * @params : $array : array.  the array keys are listed in pl_refund CLASS.
 * @description : Make pl_refund object and insert in massRefund array
**/
public function setRefund($array){
	try{
		$refund = new pl_refund();
		if($array && is_array($array)){
			foreach($array as $k=>$v){
					if(array_key_exists($k, $refund)&&(strlen($v)))$refund->$k = $v;
			}
		}
		//$this->massRefund[] = new SoapVar($refund, SOAP_ENC_OBJECT, paylineSDK::soap_refund, paylineSDK::URL_SOAP);
		$this->massRefund[] = $refund;
	}
	catch ( Exception $e ) {
			echo '<strong>ERROR : ' . $e->getMessage() . '</strong><br/>';
	}

}

/**
 * function do_massCapture 
 * @params : $comment : a comment.
 * @return : Array. Array from a payline server response object.
 * @description : Make a doMassRefundRequest with massRefund array, and comment
**/  
public function do_massRefund($comment){
	try{
	
	$doMassRefundRequest = array (
	'refundAuthorizationList' => $this->massRefund,
	'comment' => $comment);	
	
	$client = new SoapClient($this->WSDL_MASS_SOAP, $this->header_soap);
	
	$doMassRefundResponse = $client->doMassRefund($doMassRefundRequest);	
	
	return util::responseToArray($doMassRefundResponse);
	}
	catch ( Exception $e ) {
			echo '<strong>ERROR : ' . $e->getMessage() . '</strong><br/>';
	}
} 

/**
 * function get_MassTraitmentDetails 
 * @params : $massTraitmentID : a massTraitmentID.
 * @return : Array. Array from a payline server response object.
 * @description : Make a getMassTraitmentDetailsRequest with massTraitmentID.
**/  
public function get_MassTraitmentDetails($massTraitmentID){
	try{
	$getMassTraitmentDetailsRequest  = array (
	'massTraitmentID' => $massTraitmentID
	);
	$client = new SoapClient($this->WSDL_MASS_SOAP, $this->header_soap);
	$getMassTraitmentDetailsResponse = $client->getMassTraitmentDetails($getMassTraitmentDetailsRequest);	
	return util::responseToArray($getMassTraitmentDetailsResponse);
	}
	catch ( Exception $e ) {
			echo '<strong>ERROR : ' . $e->getMessage() . '</strong><br/>';
	}
} 
	
	
}
?>