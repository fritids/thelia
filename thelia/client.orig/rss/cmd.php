<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                            		 */
/*                                                                                   */
/*      Copyright (c) Octolys Development		                                     */
/*		email : thelia@octolys.fr		        	                             	 */
/*      web : http://www.octolys.fr						   							 */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 2 of the License, or            */
/*      (at your option) any later version.                                          */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*      along with this program; if not, write to the Free Software                  */
/*      Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    */
/*                                                                                   */
/*************************************************************************************/
?>
<?php
	include("../../classes/Variable.class.php");
	$code = new Variable();
	$code->charger("rsspass"); 
	if($_GET['rsspass'] != $code->valeur) exit;

	$site = new Variable();
	$site->charger("urlsite");

        $nom = new Variable();
        $nom->charger("nomsite");
?>
<?php echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>"; ?>

<rss version="0.91" xmlns:dc="http://purl.org/dc/elements/1.1/">

<channel>
	<title><?php echo($nom->valeur); ?></title>
	<link><?php echo($site->valeur); ?></link>
	<description></description>
	<language>fr</language>

<?php
	include_once("../../classes/Commande.class.php");
	include_once("../../classes/Venteprod.class.php");
	include_once("../../classes/Statutdesc.class.php");
	include_once("../../classes/Client.class.php");

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
		<title><?php echo($jour); ?>/<?php echo($mois); ?>/<?php echo($annee); ?> <?php echo($heure); ?>:<?php echo($minute); ?>:<?php echo($seconde); ?> <?php echo($client->prenom); ?> <?php echo($client->nom); ?> <?php echo($statutdesc->titre); ?></title>
		<link><?php echo($site->valeur); ?>/admin/commande_details.php?ref=<?php echo($row->ref); ?></link>
		<description><?php echo($res); ?></description>

		<dc:date><?php echo($date); ?>T<?php echo($heure); ?>:<?php echo($minute); ?>:<?php echo($seconde); ?>Z</dc:date>
		<dc:format>text/html</dc:format>
		<dc:language>fr</dc:language>
		</item>
	
<?php	
	}
?>		

</channel>

</rss>