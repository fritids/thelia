<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Cnx.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Variable.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Message.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Messagedesc.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Devise.class.php");

	$version = new Variable();
	$version->charger("version");
	$version->valeur = "140";
	$version->maj();
	
	/************ RAJOUTER LE MESSAGE DANS L'INSTALL DE THELIA (thelia.sql) ***************/
	
	$message = new Message();
	$message->nom = 'création client';
	$message->add();
	
	$message->charger('création client');
	
	$messagedesc = new Messagedesc();
	$messagedesc->message = $message->id;
	$messagedesc->lang=1;
	$messagedesc->intitule = 'Création compte client';
	$messagedesc->titre = 'Création compte client';
	$messagedesc->description = 'Bonjour,<br /> Vous recevez ce mail pour vous avertir que votre compte vient d''être crée sur __NOM_SITE__.<br /> <br /> Vos identifiants sont les suivants :<br /> <br /> e-mail : __EMAIL__<br /> mot de passe : __MOT_DE_PASSE__<br /> <br /> Vous pouvez modifier ces informations sur le <a href="__URL_SITE__">site</a>';
	$messagedesc->add();
	
	$cnx = new Cnx();
	$query_cnx = "ALTER TABLE `messagedesc` ADD `descriptiontext` TEXT NOT NULL";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);

	$devise = new Devise();
	$devise->code = "EUR";
	$devise->taux = 1;
	$devise->add();
	
	$devise = new Devise();
	$devise->charger(1);
	$devise->code = "USD";
	$devise->maj();
	
	$devise = new Devise();
	$devise->charger(2);
	$devise->code = "GBP";
	$devise->maj();
	

		
		
		
		
	
	
?>