<?php

	include_once(realpath(dirname(__FILE__)) . "/../classes/Administrateur.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../classes/Navigation.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../classes/Modules.class.php");
	
	if(! session_id())
		session_start();
		
	if( ! isset($_SESSION["util"]->id) ) exit;
	
	function autorisation($nomplugin){	
		$module = new Modules();
		$module->charger($nomplugin);
		if(! $module->actif)
			return 0;
			
	}

?>