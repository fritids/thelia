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
        include_once("../classes/Administrateur.class.php");
        include_once("../classes/Variable.class.php");
		include_once("pre.php");
		        
        session_start();
        
		if(!isset($action)) $action="";
		if(!isset($_SESSION["util"])) $_SESSION["util"]="";

        if($action == "identifier") {
                $admin = new Administrateur();
                if(! $admin->charger($identifiant, $motdepasse)) header("Location: index.php");
                else{
                        $_SESSION["util"] = new Administrateur();
                        $_SESSION["util"] = $admin;

                }
        }

	else if($_SESSION["util"] == "") header("Location: index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>

<?php
	include("../classes/Client.class.php");	
	include("../classes/Produit.class.php");	
	include("../classes/Commande.class.php");	

?>

<?php
	$client = new Client();
	$query = "select count(*) as nb from $client->table";
	$resul = mysql_query($query, $client->link);
	$nbclient = mysql_result($resul, 0, "nb");
	$client->destroy();
	
	$produit = new Produit();
	$query = "select count(*) as nb from $produit->table";
	$resul = mysql_query($query, $produit->link);
	$nbproduit = mysql_result($resul, 0, "nb");
	$produit->destroy();
	
	
	$commande = new Commande();
	$query = "select count(*) as nb from $commande->table where statut<'3'";
	$resul = mysql_query($query, $commande->link);
	$nbcmdinstance = mysql_result($resul, 0, "nb");
	$query = "select count(*) as nb from $commande->table where statut='3'";
	$resul = mysql_query($query, $commande->link);
	$nbcmdtraitement = mysql_result($resul, 0, "nb");	
	$query = "select count(*) as nb from $commande->table where statut='4'";
	$resul = mysql_query($query, $commande->link);
	$nbcmdlivree = mysql_result($resul, 0, "nb");	
	
	$query = "select * from commande where statut>=2 and statut<>5";
	$resul = mysql_query($query);
	
	$list="";
	while($row = mysql_fetch_object($resul)){
	
		$list .= "'" . $row->id . "'" . ",";
	}	
	
	$list = substr($list, 0, strlen($list)-1);
	$list == "";
	
	if($list == "") $list="''";
	
	$query = "SELECT sum(venteprod.quantite*venteprod.prixu) as ca FROM venteprod where commande in ($list)";
	$resul = mysql_query($query);
	$ca = round(mysql_result($resul, 0, "ca"), 2);
	
	$query = "SELECT sum(port)as ca FROM commande where id in ($list)";
	$resul = mysql_query($query);
	
	$ca += mysql_result($resul, 0, "ca");

	$query = "SELECT sum(remise)as ca FROM commande where id in ($list)";
	$resul = mysql_query($query);
	
	$ca -= mysql_result($resul, 0, "ca");
	
	$commande->destroy();
		
	$urlsite = new Variable();
	$urlsite->charger("urlsite");	
	
?>
<body>


<?php
	$menu="accueil";
	include("entete.php");

?>



<div id="contenu_int"> 
   <p class="titre_rubrique">Accueil</p>
   <p class="geneva12Reg_3B4B5B">Bienvenue <span class="geneva12Bold_3B4B5B"> <?php echo($_SESSION["util"]->prenom); ?></span>. Veuillez s&eacute;lectionnner une rubrique dans le menu de gauche. </p>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td height="30" align="left" valign="middle" class="titre_cellule">COMMANDES </td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_sombre">En instance : <span class="geneva11bol_3B4B5B"><?php echo($nbcmdinstance); ?></span> </td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_claire">Traitement en cours : <span class="geneva11bol_3B4B5B"><?php echo($nbcmdtraitement); ?></span> </td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_sombre">Livr&eacute;e(s) : <span class="geneva11bol_3B4B5B"><?php echo($nbcmdlivree); ?></span> </td>
     </tr>
   </table>
   <br />
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td height="30" align="left" valign="middle" class="titre_cellule">INFORMATIONS</td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_sombre">Clients : <span class="geneva11bol_3B4B5B"> <?php echo($nbclient); ?></span> </td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_claire">Produits : <span class="geneva11bol_3B4B5B"><?php echo($nbproduit); ?></span> </td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_sombre">Chiffre d'affaire  : <span class="geneva11bol_3B4B5B"><?php echo(round($ca, 2)); ?> &euro;</span> </td>
     </tr>
   </table>
   <br />
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td height="30" align="left" valign="middle" class="titre_cellule">CONNEXION</td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_sombre"><a href="#" class="txt_vert_11">Administration</a></td>
     </tr>
     <tr>
       <td height="30" align="left" valign="middle" class="cellule_claire"><a href="<?php echo($urlsite->valeur); ?>" class="txt_vert_11">Site en ligne </a></td>
     </tr>
   </table>
</div>
</body>
</html>
