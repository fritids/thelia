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
	$message->nom = 'cr�ation client';
	$message->add();
	
	$message->charger('cr�ation client');
	
	$messagedesc = new Messagedesc();
	$messagedesc->message = $message->id;
	$messagedesc->lang=1;
	$messagedesc->intitule = 'Cr�ation compte client';
	$messagedesc->titre = 'Cr�ation compte client';
	$messagedesc->description = 'Bonjour,&lt;br /&gt; Vous recevez ce mail pour vous avertir que votre compte vient d''�tre cr�e sur __NOM_SITE__.&lt;br /&gt; &lt;br /&gt; Vos identifiants sont les suivants :&lt;br /&gt; &lt;br /&gt; e-mail : __EMAIL__&lt;br /&gt; mot de passe : __MOT_DE_PASSE__&lt;br /&gt; &lt;br /&gt; Vous pouvez modifier ces informations sur le &lt;a href=&quot;__URL_SITE__&quot;&gt;site&lt;/a&gt;';
	$messagedesc->add();
	
	$cnx = new Cnx();
	$query_cnx = "ALTER TABLE `messagedesc` ADD `descriptiontext` TEXT NOT NULL";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);

	$devise = new Devise();
	$devise->charger(1);
	$devise->code = "USD";
	$devise->maj();
	
	$devise = new Devise();
	$devise->charger(2);
	$devise->code = "GBP";
	$devise->maj();
	

		
		
		
		
	
	
?>