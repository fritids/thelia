<?php
include_once(realpath(dirname(__FILE__)) . "/../lib/phpMailer/class.phpmailer.php");

 
class Mail extends PHPMailer{
 
   function __construct(){
		$this->LE = "\n";
   }
         
	public function AddrFormat($addr) {
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$formatted = $this->SecureHeader($addr[0]);
		}
		else{
    		if(empty($addr[1])) {
       			$formatted = $this->SecureHeader($addr[0]);
     		} else {
     			$formatted = $this->EncodeHeader($this->SecureHeader($addr[1]), 'phrase') . " <" . $this->SecureHeader($addr[0]) . ">";
     		}
		}
     	return $formatted;
   }
 
}
