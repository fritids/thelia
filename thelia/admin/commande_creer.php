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
	include_once("pre.php");
	include_once("auth.php");
	include_once("../classes/Pays.class.php");
	include_once("../classes/Paysdesc.class.php");
	include_once("../classes/Commande.class.php");
	include_once("../classes/Venteprod.class.php");
	include_once("../classes/Client.class.php");
	include_once("../classes/Venteadr.class.php");
	include_once("../classes/Modules.class.php");
	include_once("../classes/Modulesdesc.class.php");
	include_once("../fonctions/divers.php");
	if(isset($_POST["action"]) && $_POST["action"] == "ajouter"){
		$total = 0;
		$poids = 0;
		
		$modules = new Modules();
		$modules->charger_id($type_paiement);
		
		
		
		
		$client = new Client();
		$client->charger_ref($id_client);
		
		$commande = new Commande();
		$commande->client = $client->id;
		$commande->date = date("Y-m-d H:i:s"); 
		$commande->ref = "C" . date("ymdHis") . strtoupper(substr($client->prenom,0, 3));
		$commande->livraison = "L" . date("ymdHis") . strtoupper(substr($client->prenom,0, 3));
		$commande->transaction = date("His");
		$commande->transport = $type_livraison;
		$commande->paiement = $type_paiement;
		$commande->statut=1;
		
		$adr = new Venteadr();
		$adr->raison = $client->raison;
		$adr->nom = $client->nom;
		$adr->prenom = $client->prenom;
		$adr->adresse1 = $client->adresse1;
		$adr->adresse2 = $client->adresse2;
		$adr->adresse3 = $client->adresse3;
		$adr->cpostal = $client->cpostal;		
		$adr->ville = $client->ville;		
		$adr->tel = $client->telfixe . "  " . $client->telport;		
		$adr->pays = $client->pays;
		$adrcli = $adr->add();
		$commande->adrfact = $adrcli;
		$commande->adrlivr = $adrcli;
		
		$commande->facture = 0;
		
		$idcmd = $commande->add();
		$commande->charger($idcmd);

		
		for($i=0;$i<$_SESSION["commande"]->nbart;$i++){
			$produit = new Produit();
			$venteprod = new Venteprod();
			
			if($produit->charger($_SESSION["commande"]->venteprod[$i]->ref)){
				$produit->stock -=$_SESSION["commande"]->venteprod[$i]->quantite;
				$poids += $produit->poids;
			}
			
			$venteprod->ref = $_SESSION["commande"]->venteprod[$i]->ref;
			$venteprod->titre = $_SESSION["commande"]->venteprod[$i]->titre;
			$venteprod->quantite = $_SESSION["commande"]->venteprod[$i]->quantite;
			$venteprod->tva = $_SESSION["commande"]->venteprod[$i]->tva;
			$venteprod->prixu = $_SESSION["commande"]->venteprod[$i]->prixu;
			$venteprod->commande = $idcmd;
			$venteprod->add();
			
			$total += $venteprod->prixu * $venteprod->quantite;
			
		}
		$commande->remise = 0;
		if($client->pourcentage>0) $commande->remise = $total * $client->pourcentage / 100;
		if($remise != "") $commande->remise+=$remise;
		
		
		$commande->port = $fraisport;
		
		$commande->maj();
		
		modules_fonction("aprescommande", $commande);
		
		$nomclass=$modules->nom;
		$nomclass[0] = strtoupper($nomclass[0]);

		include_once("../client/plugins/" . $modules->nom . "/" . $nomclass . ".class.php");

	 	modules_fonction("mail", $commande, $modules->nom);

	//	$tmpobj = new $nomclass();
	//	$tmpobj->paiement($commande);
		
		header("location: commande_details.php?ref=".$commande->ref);
		
	}
	
	
	
	$_SESSION["commande"] = "";
	$_SESSION["commande"]->nbart = 0;
	$_SESSION["commande"]->venteprod = array();
	$_SESSION["commande"]->commande = new Commande();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
	include_once("title.php");
?>
<script type="text/javascript" src="../lib/jquery/jquery.js"></script>
<script type="text/javascript" src="../lib/jquery/jquery.autocomplete.js"></script>
<script type="text/javascript" src="../lib/jquery/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="../lib/jquery/thickbox-compressed.js"></script>

<link rel="stylesheet" type="text/css" href="../lib/jquery/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="../lib/jquery/thickbox.css" />

<script type="text/javascript">
$().ready(function(){
	$('#client').autocomplete("listecli.php",{
		mustMatch: true,
		width: 310,
		matchContains: true,
		autoFill: false,
		formatItem: function(data, i, n, value) {
			document.getElementById("id_client").value= value.split(".")[0];
		return value.split(".")[1] + " " + value.split(".")[2];
		},
		formatResult: function(data, value) {
		return value.split(".")[1] + " " + value.split(".")[2];
		}
	});
})

function creercli(){
	if(document.getElementById("raison").value != "" &&
	document.getElementById("nom").value != "" &&
	document.getElementById("prenom").value != "" &&
	document.getElementById("adresse1").value != "" &&
	document.getElementById("cpostal").value != "" &&
	document.getElementById("ville").value != "" &&
	document.getElementById("email1").value != "" &&
	document.getElementById("email2").value != ""){
		
		if(document.getElementById("email1").value == document.getElementById("email2").value){
			args = "";
			args += "&raison=" + document.getElementById("raison").value;
			args += "&nom=" + document.getElementById("nom").value;
			args += "&prenom=" + document.getElementById("prenom").value;
			args += "&adresse1=" + document.getElementById("adresse1").value;
			args += "&cpostal=" + document.getElementById("cpostal").value;
			args += "&ville=" + document.getElementById("ville").value;
			args += "&email1=" + document.getElementById("email1").value;
			args += "&email2=" + document.getElementById("email2").value;
			args += "&entreprise=" + document.getElementById("entreprise").value;
			args += "&siret=" + document.getElementById("siret").value;
			args += "&intracom=" + document.getElementById("intracom").value;
			args += "&adresse2=" + document.getElementById("adresse2").value;
			args += "&adresse3=" + document.getElementById("adresse3").value;
			args += "&pays=" + document.getElementById("pays").value;
			args += "&telfixe=" + document.getElementById("telfixe").value;
			args += "&telport=" + document.getElementById("telport").value;
			args += "&remise=" + document.getElementById("remise").value;
			args += "&type=" + document.getElementById("type").value;
			//ajax
			$.ajax({
				type:'GET',
				url:"ajoutcli.php",
				data:'action=ajouter'+args,
				success:function(html){
					$('#nclient').html(html)
				}
			})
			tb_remove();
		}
		else{
			alert("vérifier le mail");
		}
		
	}
	else{
		alert("vérifier les champs obligatoires");
		
	}
}


function verifref(){
	var ref = document.getElementById("ref").value;
	$.ajax({
		type:'GET',
		url:"verifref.php",
		data:"ref="+ref,
		success:function(html){
			$('#titre').val(html.split("|")[0]);
			$('#prixu').val(html.split("|")[1]);
			$('#tva').val(html.split("|")[2]);
			document.getElementById("qtite").value="";
		}
	})
}

function addcom(){
	$.ajax({
		type:'GET',
		url:'addcom.php',
	data:"ref="+document.getElementById('ref').value+"&titre="+document.getElementById('titre').value+"&prixu="+document.getElementById('prixu').value+"&tva="+document.getElementById('tva').value+"&qtite="+document.getElementById('qtite').value,
		success:function(html){
			$('#listecom').html(html);
			document.getElementById("prod").reset();
		}
	})
	
}

function valid(){
	if(document.getElementById('id_client').value != "" && document.getElementById('port') != ""){
		document.getElementById('formulaire').submit();
	}
	else{
		alert("vérifier le client et les frais de port");
	}
}
</script>

<body>

<div id="wrapper">
<div id="subwrapper">

<?php
	$menu="commande";
	include_once("entete.php");
?>

	
<div id="contenu_int">
	<p align="left"><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="commande.php" class="lien04">Gestion des commandes</a>              
    </p>
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
	 	<tr>
			<td width="100%" height="30" class="titre_cellule_tres_sombre">Cr&eacute;ation d'une commande</td>
		</tr>
	</table>
	<br />
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
	 	<tr>
			<td width="100%" height="30" class="titre_cellule_tres_sombre">Liste de la commande</td>
		</tr>
	</table>
	<form action="commande_creer.php" method="POST" id="formulaire">
	<input type="hidden" name="action" value="ajouter">
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tr>
			<td height="30" class="titre_cellule">Client</td>
	       	<td class="cellule_sombre">
				<div id="nclient">
	         	<input name="client" id="client" type="text" class="form" size="40" />
				<input type="hidden" name="id_client" id="id_client" value=""> <a href="#TB_inline?height=400&amp;width=800&amp;inlineId=contenu_cli&amp;modal=true" class="thickbox">Cr&eacute;er un client</a>
				</div>
	      	</td>
		</tr>
		<tr>
			<td height="30" class="titre_cellule">Paiement</td>
			<td class="cellule_claire">
				<select name="type_paiement">
					<option value="">Choisir... </option>
				<?php
					$modules = new Modules();
					$query = "select * from $modules->table where type=1 and actif=1";
					$resul = mysql_query($query,$modules->link);
					while($row = mysql_fetch_object($resul)){
						$modulesdesc = new Modulesdesc();
						$modulesdesc->charger($row->nom);
						?>
						<option value="<?php echo $row->id; ?>"><?php echo $modulesdesc->titre; ?></option>
						<?php
					}
				?>
				</select>
				</td>
		</tr>
		<tr>
			<td height="30" class="titre_cellule">Livraison</td>
			<td class="cellule_claire">
				<select name="type_livraison">
					<option value="">Choisir... </option>
				<?php
					$modules = new Modules();
					$query = "select * from $modules->table where type=2 and actif=1";
					$resul = mysql_query($query,$modules->link);
					while($row = mysql_fetch_object($resul)){
						$modulesdesc = new Modulesdesc();
						$modulesdesc->charger($row->nom);
						?>
						<option value="<?php echo $row->id; ?>"><?php echo $modulesdesc->titre; ?></option>
						<?php
					}
				?>
				</select>
				</td>
			</tr>
			<tr>
				<td height="30" class="titre_cellule">Frais de port</td>
				<td class="cellule_claire"><input type="text" name="fraisport" class="form" size="40"></td>
			</tr>
			<tr>
				<td height="30" class="titre_cellule">Remise</td>
				<td class="cellule_claire"><input type="text" name="remise" class="form" size="40"></td>
			</tr>
		</table>
		<span id="listecom"> </span>
		</form>
		<br />
		<table width="100%" border="0" cellpadding="5" cellspacing="0">
		 	<tr>
				<td width="100%" height="30" class="titre_cellule_tres_sombre">Ajout d'un produit</td>
			</tr>
		</table>
		<form id="prod">
		<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tr>
			<td height="30" class="titre_cellule">R&eacute;f&eacute;rence produit</td>
			<td class="cellule_claire"><input type="text" name="ref" id="ref" value="" class="form" size="40" onblur="verifref()"></td>
		</tr>
		<tr>
			<td height="30" class="titre_cellule">Titre</td>
			<td class="cellule_claire"><input type="text" name="titre" id="titre" value="" class="form" size="40"></td>
		</tr>
		<tr>
			<td height="30" class="titre_cellule">Prix unitaire</td>
			<td class="cellule_claire"><input type="text" name="prixu" id="prixu" value="" class="form" size="40"></td>
		</tr>
		<tr>
			<td height="30" class="titre_cellule">TVA</td>
			<td class="cellule_claire"><input type="text" name="tva" id="tva" value="" class="form" size="40"></td>
		</tr>
		<tr>
			<td height="30" class="titre_cellule">quantit&eacute;</td>
			<td class="cellule_claire"><input type="text" name="qtite" id="qtite" value="" class="form" size="40"></td>
		</tr>
		<tr>
			<td colspan="2"><a href="#" onclick="addcom()">VALIDER LES MODIFICATIONS</a></td>
		</tr>	
	</table>
	</form>
	
	
	<div id="contenu_cli" style="display:none">
		<table width="100%" border="0" cellpadding="5" cellspacing="0">
		     <tr>
		       <td height="30" class="titre_cellule">SOCI&Eacute;T&Eacute;</td>
		       <td class="cellule_sombre">
		         <input name="entreprise" id="entreprise" type="text" class="form" size="40" />
		      </td>
		     </tr>
		      <tr>
		       <td height="30" class="titre_cellule">SIRET</td>
		       <td class="cellule_sombre">
		         <input name="siret" id="siret" type="text" class="form" size="40"  />
		      </td>
		     </tr>
		      <tr>
		       <td height="30" class="titre_cellule">N° INTRACOMMUNAUTAIRE</td>
		       <td class="cellule_sombre">
		         <input name="intracom" id="intracom" type="text" class="form" size="40" />
		      </td>
		     </tr>
		     <tr>
		       <td height="30" class="titre_cellule">CIVILIT&Eacute; *</td>
		       <td class="cellule_claire">
		         <input name="raison" id="raison" type="radio" class="form" value="1" />
		Madame
		<input name="raison" id="raison" type="radio" class="form" value="2" />
		Mademoiselle
		<input name="raison" id="raison" type="radio" class="form" value="3" />
		Monsieur</td>
		     </tr>
		     <tr>
		       <td height="30" class="titre_cellule">NOM *</td>
		       <td class="cellule_sombre">
		       <input name="nom" id="nom" type="text" class="form" size="40"  />       </td>
		     </tr>
		     <tr>
		       <td height="30" class="titre_cellule">PR&Eacute;NOM *</td>
		       <td class="cellule_claire">
		         <input name="prenom" id="prenom" type="text" class="form" size="40" />
		       </td>
		     </tr>
		     <tr>
		       <td width="250" height="30" class="titre_cellule">ADRESSE *</td>
		       <td width="440" class="cellule_sombre">
		         <input name="adresse1" id="adresse1" type="text" class="form" size="40" />
		      </td>
		     </tr>
		     <tr>
		       <td width="250" height="30" class="titre_cellule">ADRESSE SUITE</td>
		       <td width="440" class="cellule_sombre">
		         <input name="adresse2" id="adresse2" type="text" class="form" size="40" />
		      </td>
		     </tr>
		     <tr>
		       <td width="250" height="30" class="titre_cellule">ADRESSE SUITE 2</td>
		       <td width="440" class="cellule_sombre">
		         <input name="adresse3" id="adresse3" type="text" class="form" size="40" />
		      </td>
		     </tr>     
		     <tr>
		       <td height="30" class="titre_cellule">CODE POSTAL *</td>
		       <td class="cellule_claire">
		         <input name="cpostal" id="cpostal" type="text" class="form" size="40"/>
		      </td>
		     </tr>
		     <tr>
		       <td height="30" class="titre_cellule">VILLE *</td>
		       <td class="cellule_sombre">
		         <input name="ville" id="ville" type="text" class="form" size="40" />
		       </td>
		     </tr>
		     <tr>
		       <td height="30" class="titre_cellule">PAYS *</td>
		       <td class="cellule_claire">
		    <select name="pays" id="pays">
		     <?php
		      	$pays = new Pays();
		      	$query ="select * from $pays->table";

		      	$resul = mysql_query($query, $pays->link);
		      	while($row = mysql_fetch_object($resul)){
					$paysdesc = new Paysdesc();
					$paysdesc->charger($row->id);

		      ?>
		      <option value="<?php echo $row->id; ?>" <?php if($row->id == 64) echo "selected";  ?> ><?php echo($paysdesc->titre); ?></option>
		      <?php } ?>
		      </select>

		       </td>
		     </tr>
		     <tr>
		       <td height="30" class="titre_cellule">T&Eacute;L&Eacute;PHONE FIXE</td>
		       <td class="cellule_sombre">
		         <input name="telfixe" id="telfixe" type="text" class="form" size="40" />
		      </td>
		     </tr>
		     <tr>
		       <td height="30" class="titre_cellule">T&Eacute;L&Eacute;PHONE PORTABLE </td>
		       <td class="cellule_claire">
		         <input name="telport" id="telport" type="text" class="form" size="40" />
		      </td>
		     </tr>
		     <tr>
		       <td height="30" class="titre_cellule">E-MAIL *</td>
		       <td class="cellule_sombre">
		         <input name="email1" id="email1" type="text" class="form" size="40" />
		      </td>
		     </tr>
			<tr>
		       <td height="30" class="titre_cellule">CONFIRMATION E-MAIL *</td>
		       <td class="cellule_sombre">
		         <input name="email2" id="email2" type="text" class="form" size="40" />
		      </td>
		     </tr>
		     <tr>
		       <td height="30" class="titre_cellule">Remise </td>
		       <td class="cellule_claire">
		         <input name="pourcentage" id="remise" type="text" class="form" size="40" />
		      </td>
		     </tr>     
		     <tr>
		       <td height="30" class="titre_cellule">Revendeur </td>
		       <td class="cellule_claire">
		         <input type="checkbox" name="type" id="type" class="form" /> 
		    </td>
		     </tr>
		</table>
		<input type="button" value="annuler" onclick="tb_remove()"> <input type="button" value="valider" onclick="creercli()">
	</div>
</div>
</div>
</div>
