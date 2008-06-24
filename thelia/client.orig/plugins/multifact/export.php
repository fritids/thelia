<?php
	include_once(realpath(dirname(__FILE__)) . "/Multifact.class.php");
?>
<?php

			if($_REQUEST['action'] == "export"){


				$facture = new Multifact();
				$facture->creer($_REQUEST['cmd']);

			}
		?>
