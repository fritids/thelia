<?php

include_once(realpath(dirname(__FILE__)) . "/../../../classes/Navigation.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../../classes/Commande.class.php");
include_once(realpath(dirname(__FILE__)) . "/config.php");
include_once(realpath(dirname(__FILE__)) . "/lib/paylineSDK.php");
include_once(realpath(dirname(__FILE__)) . "/lib/lib_debug.php");	

session_start();

$total = $_SESSION['navig']->commande->total;
$total *= 100; 

$array = array();
$payline = new paylineSDK();

$array['payment']['amount'] = $total;
$array['order']['ref'] = $_SESSION['navig']->commande->ref;
$array['order']['amount'] = $total;

$result = $payline->do_webpayment($array);

if(isset($_POST['debug'])){
	echo '<H3>REQUEST</H3>';
	print_a($array, 0, true);
	echo '<H3>RESPONSE</H3>';
	print_a($result, 0, true);
}
else{
	if(isset($result) && $result['result']['code'] == '00000'){
		$commande = new Commande();
		$commande->charger($_SESSION['navig']->commande->id);
		$commande->transaction = $result['token'];
		$commande->maj();
		header("location:".$result['redirectURL']);
		exit();
	}
	elseif(isset($result)) {
	echo 'ERROR : '.$result['result']['code']. ' '.$result['result']['longMessage'].' <BR/>';
	}
	
}
?>
