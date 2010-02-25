<?php
header('Content-Type: text/html; charset=ISO-8859-1');
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
include_once(realpath(dirname(__FILE__)) . "/../../fonctions/divers.php");
?>
<?php if(! est_autorise("acces_commandes")) exit; ?>
<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Commande.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Client.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Venteprod.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Statutdesc.class.php");

$critere = $_GET["critere"];
$order = $_GET["order"];
$commande = new Commande();

$query = "select * from $commande->table where 1 order by $critere $order limit 0,30";

$resul = mysql_query($query, $commande->link);

$venteprod = new Venteprod();
$i=0;
while($row = mysql_fetch_object($resul)){
	
	$client = new Client();
	$client->charger_id($row->client);

	$statutdesc = new Statutdesc();
	$statutdesc->charger($row->statut);
	
	$query2 = "SELECT sum(prixu*quantite) as total FROM $venteprod->table where commande='$row->id'"; 
	$resul2 = mysql_query($query2, $venteprod->link);
	$total = round(mysql_result($resul2, 0, "total"), 2);

	$port = $row->port;
	$total -= $row->remise;
	$total += $port;
	
	$jour = substr($row->date, 8, 2);
	$mois = substr($row->date, 5, 2);
	$annee = substr($row->date, 2, 2);
	
	$heure = substr($row->date, 11, 2);
	$minute = substr($row->date, 14, 2);
	$seconde = substr($row->date, 17, 2);
	
	if(!($i%2)) $fond="ligne_claire_rub";
	else $fond="ligne_fonce_rub";
	$i++;
?>
<ul class="<?php echo($fond); ?>">
<li style="width:142px;"><?php echo($row->ref); ?></li>
<li style="width:104px;"><?php echo($jour . "/" . $mois . "/" . $annee . " " . $heure . ":" . $minute . ":" . $seconde); ?></li>
<li style="width:200px;"><?php echo($client->entreprise); ?></li>
<li style="width:200px;"><a href="client_visualiser.php?ref=<?php echo($client->ref); ?>" class="txt_vert_11"><?php echo($client->prenom . " " . $client->nom); ?></a></li>
<li style="width:91px;"><?php echo(round($total, 2)); ?></li>
<li style="width:70px;"><?php echo($statutdesc->titre); ?></li>
<li style="width:40px;"><a href="commande_details.php?ref=<?php echo($row->ref); ?>" class="txt_vert_11">Détails</a></li>
<li style="width:10px;"><a href="#" onclick="supprimer('<?php echo($row->id); ?>')"><img src="gfx/supprimer.gif" width="9" height="9" border="0" /></a></li>
</ul>
<?php
}
?>