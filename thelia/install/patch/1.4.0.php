<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Cnx.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Variable.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Message.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Messagedesc.class.php");

	$version = new Variable();
	$version->charger("version");
	$version->valeur = "140";
	$version->maj();
	
	$message = new Message();
	$message->nom = 'cr�ation client';
	$message->add();
	
	$message->charger('cr�ation client');
	
	$messagedesc = new Messagedesc();
	$messagedesc->message = $message->id;
	$messagedesc->lang=1;
	$messagedesc->intitule = 'Cr�ation compte client';
	$messagedesc->titre = 'Cr�ation compte client';
	$messagedesc->description = 'Bonjour, Vous recevez ce mail pour vous avertir que votre compte vient d\'�tre cr�e sur __NOM_SITE__. Vos identifiants sont les suivants : e-mail : __EMAIL__ mot de passe : __MOT_DE_PASSE__ Vous pouvez modifier ces informations sur le <a href=\"__URL_SITE__\"\>site</a>';
	$messagedesc->add();
	
?>