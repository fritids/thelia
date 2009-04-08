<?
		include("classes/Smtp.class.php");
		include_once(realpath(dirname(__FILE__)) . "/classes/Variable.class.php");

				$emailcontact = new Variable();
				$emailcontact->charger("emailcontact");
				$urlsite = new Variable();
				$urlsite->charger("nomsite");
                $smtp = new Smtp();
                $smtp->server = "127.0.0.1";
                $smtp->from = $emailcontact->valeur;
                $smtp->rcpt = "chabannatm@gmail.com";
                $smtp->subject = $urlsite->valeur;
                $smtp->texte = "Nom: " . $_POST['nom'] . " \nE-Mail: " . $_POST['email'] . "\nService: " . "\nSujet: " . $_POST['subject'] . "\nMessage: " . $_POST['message'];
//	$smtp->envoyer();
		mail("$smtp->rcpt", "$smtp->subject", "$smtp->texte", "From: " . "$smtp->from");
?>

<html>

	<head>
		<script language="javascript">
			function valide(){
				alert(unescape("Votre demande a %E9t%E9 prise en compte, nous mettons tout en oeuvre pour vous répondre dans les plus brefs d%E9lais, Merci et à bientôt"));
				location="index.php";
			}
		</script>
	</head>

	<body onLoad="valide()">

	</body>

</html>
