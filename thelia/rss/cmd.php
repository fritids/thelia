<?
	include("../classes/Variable.class.php");
	$code = new Variable();
	$code->charger("rsspass"); 
	if(! isset($_GET['id']) || $_GET['id']!= $code->valeur || $code->valeur == "") exit;

	$site = new Variable();
	$site->charger("urlsite");

    $nom = new Variable();
    $nom->charger("nomsite");

?>
<? echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>"; ?>

<rss version="0.91" xmlns:dc="http://purl.org/dc/elements/1.1/">

<channel>
	<title><?= $nom->valeur ?></title>
	<link><?= $site->valeur ?></link>
	<description></description>
	<language>fr</language>

<?
	include("../classes/Commande.class.php");
	include("../classes/Venteprod.class.php");
	include("../classes/Statutdesc.class.php");
	include("../classes/Client.class.php");

	$commande = new Commande();
	$venteprod = new Venteprod();
	$statutdesc = new Statutdesc();
	$client = new Client();
 
	$query = "select * from $commande->table order by date desc limit 0,5";
	$resul = mysql_query($query, $commande->link);

	while($row = mysql_fetch_object($resul)){
		$date = substr($row->date, 0, 10);
		$heure = substr($row->date, 11);

		$jour = substr($date, 8,2);
		$mois = substr($date, 5, 2);
		$annee = substr($date, 0, 4);

		$heure = substr($row->date, 11, 2);
		$minute = substr($row->date, 14, 2);
		$seconde = substr($row->date, 17, 2);
                
		$query2 = "SELECT sum(prixu*quantite) as total FROM $venteprod->table where commande='$row->id'";
                $resul2 = mysql_query($query2, $venteprod->link);
                $total = round(mysql_result($resul2, 0, "total"), 2);
                $total = round($total - $row->remise, 2);

                $port = $row->port;
                $totcmdport = $row->port + $total;

                $statutdesc->charger($row->statut);

		$client->charger_id($row->client);

		$res="";

		$res .= "&lt;p&gt;" . $totcmdport . " euro" . "&lt;/p&gt;";

		$query3 = "SELECT *  FROM $venteprod->table where commande='$row->id'";
                $resul3 = mysql_query($query3, $venteprod->link);
		while($row3 = mysql_fetch_object($resul3)){
			$res .= "&lt;p&gt;" . $row3->ref . " " . $row3->titre . " " . $row3->quantite . "*" . $row3->prixu . " euro" . "&lt;/p&gt;";

		}
?>

		<item>
		<title><?= $jour ?>/<?= $mois ?>/<?= $annee ?> <?= $heure ?>:<?= $minute ?>:<?= $seconde ?> <?= $client->prenom ?> <?= $client->nom ?> <?= $statutdesc->titre ?></title>
		<link><?= $site->valeur ?>/admin/commande_details.php?ref=<?= $row->ref?></link>
		<description><?= $res ?></description>

		<dc:date><?=$date?>T<?= $heure ?>:<?= $minute ?>:<?= $seconde ?>Z</dc:date>
		<dc:format>text/html</dc:format>
		<dc:language>fr</dc:language>
		</item>
	
<?	
	}
?>		

</channel>

</rss>


