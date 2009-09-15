<?php

	include_once(realpath(dirname(__FILE__)) . "/../classes/Administrateur.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../classes/Navigation.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../classes/Modules.class.php");
	
	if(! session_id())
		session_start();
		
	if( ! isset($_SESSION["util"]->id) ) exit;
	
	function autorisation($nomplugin){	
		$module = new Modules();
		if($module->charger($nomplugin) && $module->actif && $module->est_autorise())
			return 1;
			
		exit;
			
	}

?>