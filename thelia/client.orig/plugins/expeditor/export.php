<?php
	include_once(realpath(dirname(__FILE__)) . "/Expeditor.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Commande.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Client.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Venteadr.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Venteprod.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Produit.class.php");
?>
<?php


			if($_REQUEST['action'] == "export"){
				header("Content-Type: application/csv-tab-delimited-table");
				header("Content-disposition: filename=expeditor_thelia" . ".csv");

				$res="";

				for($i=0; $i<count($_REQUEST['cmd']); $i++){
					
					$commande = new Commande();
					$commande->charger_ref($_REQUEST['cmd'][$i]);

					$client = new Client();
					$client->charger_id($commande->client);

					$poids = 0;

					$venteprod = new Venteprod();

					$query_vprod = "select * from $venteprod->table where commande=\"" . $commande->id . "\"";
					$resul_vprod  = mysql_query($query_vprod , $venteprod->link);

					while($row = mysql_fetch_object($resul_vprod )){
						$tmpprod = new Produit();
						$tmpprod->charger($row->ref);
						$poids += $tmpprod->poids;
					}

					$adr = new Venteadr();
					$adr->charger($commande->adrlivr);
					
					$nom = $adr->prenom . " " . $adr->nom;
			
					$expeditor = new Expeditor();
					$expeditor->charger($adr->pays);
					$pays = $expeditor->alpha2;
							
					
					
					$res .= "\"" . $commande->ref . "\";\"$nom\";\"$adr->adresse1\";\"$adr->adresse2\";\"$adr->adresse3\";\"$adr->cpostal\";\"$adr->ville\";\"$pays\";\"$adr->tel\";\"$poids\"";
					$res .= "\r\n";
					$commande->statut = 3;
					$commande->maj();
			
				}

					echo "$res";
			}
		?>
