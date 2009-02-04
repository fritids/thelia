<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Cnx.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Variable.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Message.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Messagedesc.class.php");

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
	$messagedesc->description = 'Bonjour,&lt;br /&gt; Vous recevez ce mail pour vous avertir que votre compte vient d''être crée sur __NOM_SITE__.&lt;br /&gt; &lt;br /&gt; Vos identifiants sont les suivants :&lt;br /&gt; &lt;br /&gt; e-mail : __EMAIL__&lt;br /&gt; mot de passe : __MOT_DE_PASSE__&lt;br /&gt; &lt;br /&gt; Vous pouvez modifier ces informations sur le &lt;a href=&quot;__URL_SITE__&quot;&gt;site&lt;/a&gt;';
	$messagedesc->add();
	
	$cnx = new Cnx();
	$query_cnx = "ALTER TABLE `messagedesc` ADD `descriptiontext` TEXT NOT NULL";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);
	/**********************************************/
	/***** A RAJOUTER DANS LA CLASSE MESSAGEDESC **/
	/***** LORS DE LA PUBLICAITON DE LA 1.4.0    **/
	/**********************************************/
	
	/*** A RAJOUTER DANS PluginsPaiements.class.php******************/
		/*$corpstext = $msgdesc->descriptiontext;
		$corpstext = $this->substitmail($corpstext,$commande);*/
	/**** APRÈS ************/
	/*$corps = $msgdesc->description;
	$corps = $this->substitmail($corps, $commande);*/
	
	/******* A RAJOUTER ************/
	/*$corpstext2 = $msgdesc->descriptiontext;
	$corpstext2 = $this->substitmail($corpstext2,$commande);*/
	
	/******** APRÈS *********/
		/*$emailcontact = new Variable();
		$emailcontact->charger("emailcontact");	
		$sujet2 = $this->substitmail($msgdesc->titre, $commande);
		$corps2 = $this->substitmail($corps2, $commande);*/
		
	/************** ENVOI DU MAIL AU CLIENT **********/
	//envoi du mail au client
	/*$mailclient = new PHPMailer();
	$mailclient->IsMail();
	$mailclient->FromName = $nomsite->valeur;
	$mailclient->From = $emailcontact->valeur;
	$mailclient->Subject = $sujet;
	$mailclient->MsgHTML($corps);
	$mailclient->AltBody = $corpstext;
	$mailclient->AddAddress($client->email,$client->nom." ".$client->prenom);
	
	$mailclient->send();*/
	
	
	/************** ENVOI DU MAIL A L'ADMIN **************/
	//envoi du mail a l'admin
	/*$mail = new PHPMailer();
	$mail->IsMail();
	$mail->FromName = $nomsite->valeur;
	$mail->From = $emailcontact->valeur;
	$mail->Subject = $sujet2;
	$mail->MsgHTML($corps2);
	$mail->AltBody = $corpstext2;
	$mail->AddAddress($emailcontact->valeur,$nomsite->valeur);
	
	$mail->send();*/
	/*****************************************************/
	
	/************** CHANGEMENT DU MOT DE PASSE ***********/
	// changement du mot de passe
	/*function chmdp($email){
		$msg = new Message();
		$msgdesc = new Messagedesc();
		
		$tclient  = new Client();
		if( $tclient->charger_mail($email)){
			$pass = genpass(8);
			$tclient->motdepasse = $pass;
			$tclient->crypter();
			$tclient->maj();
        
			$msg->charger("changepass");
			$msgdesc->charger($msg->id);

			$sujet = $msgdesc->titre;	

			$emailcontact = new Variable();
            $emailcontact->charger("emailcontact");

			$nomsite = new Variable();
			$nomsite->charger("nomsite");
                
            $corps = $msgdesc->description;  
  			$corpstext = $msqdesc->descriptiontext;
			
			$mail = new PHPMailer();
			$mail->IsMail();
			$mail->From = $emailcontact->valeur;
			$mail->FromName = $nomsite->valeur;
			$mail->Subject = $sujet;
			$mail->MsgHTML($corps." ".$pass);
			$mail->AltBody = $corpstext." ".$pass;
			$mail->AddAddress($tclient->email,$tclient->nom." ".$tclient->prenom);
			$mail->send();
 		
		}


	}*/
	/**********************************************************/
	
	/****** reporter toutes ces modifs dans l'instal de thelia *****/
		
		
		
		
	
	
?>