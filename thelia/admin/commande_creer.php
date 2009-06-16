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
/*$().ready(function(){
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
})*/

function lookup(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			$.get("listecli.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			});
		}
	} // lookup
	

	
	function fill(str) {
		if (str != "") {
			var tableau = str.split("|");
			$('#inputstring').val(tableau[1]);
			$('#id_client').val(tableau[0]);
			setTimeout("$('#suggestions').hide();", 200);
		}
		else{
			$('#inputstring').val(str);
			setTimeout("$('#suggestions').hide();", 200);
		}
	}

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
	<p><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="commande.php" class="lien04">Gestion des commandes</a>              
    </p>
    <!-- Début de la colonne de gauche -->  
<div id="bloc_description">
<div class="bordure_bottom">
<div class="entete_liste_client">
	<div class="titre">CR&Eacute;ATION D'UNE COMMANDE</div>
	<div class="fonction_valider"><a href="#" onclick="valid()">VALIDER LES MODIFICATIONS</a></div>
</div>
<form action="commande_creer.php" method="POST" id="formulaire">
	<input type="hidden" name="action" value="ajouter">
<ul class="ligne_claire_BlocDescription" style="background-image: url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">
		<li class="designation" style="width:280px; background-image: url(gfx/degrade_ligne1.png); background-repeat: repeat-x;">Choix du client <span class="note">(commencer &agrave; taper les coordonn&eacute;es du client dans le champs)</span></li>
		<li><input name="choixdecli" id="inputstring" onkeyup="lookup(this.value);" type="text" class="form" size="40" /></li>
		</ul>
		<div class="suggestionsBox" id="suggestions" style="display: none;">
			<img src="gfx/upArrow.png" style="float:left; margin:-10px 0 0 140px;" alt="upArrow" />
			<div class="suggestionList" id="autoSuggestionsList">
				&nbsp;
			</div>
		</div>
	
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:280px;">Ou cr&eacute;er un client</li>
		<li><div id="nclient">
				<input type="hidden" name="id_client" id="id_client" value=""> <a href="#TB_inline?height=400&amp;width=800&amp;inlineId=contenu_cli&amp;modal=true" class="thickbox">Cr&eacute;er un client</a>
				</div></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:280px;">Type de paiement</li>
		<li><select name="type_paiement" class="form_client">
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
				</select></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:280px;">Type de transport</li>
		<li><select name="type_livraison" class="form_client">
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
</li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:280px;">Montant des frais de port</li>
		<li><input type="text" name="fraisport" class="form" size="40"></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:280px;">Remise</li>
		<li><input type="text" name="remise" class="form" size="40"></li>
	</ul>

</form>
</div>
<div class="bordure_bottom" style="margin:10px 0 0px 0;">
<div class="entete_liste_client">
	<div class="titre">AJOUT DE PRODUITS</div>
	<div class="fonction_valider"><a href="#" onclick="addcom()">AJOUTER AU PANIER</a></div>
</div>
<form id="prod">
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:280px;">R&eacute;f&eacute;rence produit</li>
		<li><input type="text" name="ref" id="ref" value="" class="form" size="40" onblur="verifref()"></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:280px;">Titre du produit</li>
		<li><input type="text" name="titre" id="titre" value="" class="form" size="40"></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:280px;">Prix unitaire</li>
		<li><input type="text" name="prixu" id="prixu" value="" class="form" size="40"></li>
	</ul>
	<ul class="ligne_fonce_BlocDescription">
		<li class="designation" style="width:280px;">TVA applicable sur ce produit</li>
		<li><input type="text" name="tva" id="tva" value="" class="form" size="40"></li>
	</ul>
	<ul class="ligne_claire_BlocDescription">
		<li class="designation" style="width:280px;">Quantit&eacute;</li>
		<li><input type="text" name="qtite" id="qtite" value="" class="form" size="40"></li>
	</ul>
</form>
</div>	
	
	<div id="contenu_cli" style="display:none">
	<div id="bloc_description">
	<div class="entete_liste_client">
			<div class="titre">CR&Eacute;ATION D'UN CLIENT</div>
			<div class="fonction_valider"><a href="#" onclick="creercli()">VALIDER LES MODIFICATIONS</a></div>
		</div>
		<table width="100%" cellpadding="5" cellspacing="0">
    <tr class="claire">
        	<th class="designation" width="290">Soci&eacute;t&eacute;</th>
		    <th><input name="entreprise" type="text" class="form" size="40" <?php if(isset($entreprise)) echo "value=\"$entreprise\""; ?> /></th>
	</tr>
	<tr class="fonce">
			<td class="designation">Siret</td>
		    <td><input name="siret" type="text" class="form" size="40" <?php if(isset($siret)) echo "value=\"$siret\""; ?> /></td>
	</tr>
	<tr class="claire">
		    <td class="designation">N° Intracommunautaire</td>
		    <td><input name="intracom" type="text" class="form" size="40" <?php if(isset($intracom)) echo "value=\"$intracom\""; ?> /></td></tr>
	<tr class="fonce">
			<td class="designation">Civilit&eacute; <?php if($erreurraison) echo "obligatoire"; ?></td>
			<td><input name="raison" type="radio" class="form" value="1" <?php if(isset($raison) && $raison == 1) echo "checked"; ?>/>
		Madame
		<input name="raison" type="radio" class="form" value="2" <?php if(isset($raison) && $raison == 2) echo "checked"; ?>/>
		Mademoiselle
		<input name="raison" type="radio" class="form" value="3" <?php if(isset($raison) && $raison == 3) echo "checked"; ?>/>
		Monsieur</td>
	</tr>
	<tr class="claire">
		   	<td class="designation">Nom <?php if($erreurnom) echo "obligatoire";  ?></td>
		    <td><input name="nom" type="text" class="form" size="40" <?php if(isset($nom)) echo "value=\"$nom\""; ?> /></td>
	</tr>
	<tr class="fonce">
		    <td class="designation">Pr&eacute;nom <?php if($erreurprenom) echo "obligatoire"; ?></td>
		    <td><input name="prenom" type="text" class="form" size="40" <?php if(isset($prenom)) echo "value=\"$prenom\""; ?> /></td>
	</tr>
	<tr class="claire">
		    <td class="designation">Adresse <?php if($erreuradresse) echo "obligatoire"; ?></td>
		    <td><input name="adresse1" type="text" class="form" size="40" <?php if(isset($adresse1)) echo "value=\"$adresse1\""; ?>/></td>
	</tr>
	<tr class="fonce">
		    <td class="designation">Adresse suite</td>
		    <td><input name="adresse2" type="text" class="form" size="40" <?php if(isset($adresse2)) echo "value=\"$adresse2\""; ?>/></td>
	</tr>
	<tr class="claire">
		    <td class="designation">Adresse suite 2</td>
		    <td><input name="adresse3" type="text" class="form" size="40" <?php if(isset($adresse3)) echo "value=\"$adresse3\""; ?>/></td>
	</tr>     
	<tr class="fonce">
		    <td class="designation">Code postal <?php if($erreurcpostal) echo "obligatoire"; ?></td>
		    <td><input name="cpostal" type="text" class="form" size="40" <?php if(isset($cpostal)) echo "value=\"$cpostal\""; ?>/></td>
	</tr>
	<tr class="claire">	
			<td class="designation">Ville <?php if($erreurville) echo "obligatoire"; ?></td>
			<td><input name="ville" type="text" class="form" size="40" <?php if(isset($ville)) echo "value=\"$ville\""; ?>/></td>
	</tr>
	<tr class="fonce">
		    <td class="designation">Pays <?php if($erreurpays) echo "obligatoire"; ?></td>
		    <td><select name="pays" class="form_client">
		     <?php
		      	$pays = new Pays();
		      	$query ="select * from $pays->table";

		      	$resul = mysql_query($query, $pays->link);
		      	while($row = mysql_fetch_object($resul)){
					$paysdesc = new Paysdesc();
					$paysdesc->charger($row->id);

		      ?>
		      <option value="<?php echo $row->id; ?>" <?php if($paysform == $row->id){ echo "selected"; } ?> ><?php echo($paysdesc->titre); ?></option>
		      <?php } ?>
		      </select>
			</td>
	</tr>
	<tr class="claire">
		    <td class="designation">T&eacute;l&eacute;phone fixe</td>
		    <td><input name="telfixe" type="text" class="form" size="40" <?php if(isset($telfixe)) echo "value=\"$telfixe\""; ?>/></td>
	</tr>
	<tr class="fonce">
		    <td class="designation">T&eacute;l&eacute;phone portable</td>
		    <td><input name="telport" type="text" class="form" size="40" <?php if(isset($telport)) echo "value=\"$telport\""; ?>/></td>
	</tr>
	<tr class="claire">
		    <td class="designation">E-mail <?php if($erreurmail) echo "obligatoire"; else if($erreurmailexiste) echo "existe déjà"; ?></td>
		    <td><input name="email1" type="text" class="form" size="40" /></td>
	</tr>
	<tr class="fonce">
		    <td class="designation">Confirmation e-mail</td>
		    <td><input name="email2" type="text" class="form" size="40" /></td>
	</tr>
	<tr class="claire">
		    <td class="designation">Remise </td>
		    <td><input name="pourcentage" type="text" class="form" size="40" <?php if(isset($remise)) echo "value=\"$remise\""; ?>/></td>
	</tr>     
	<tr class="foncebottom">
		    <td class="designation">Revendeur </td>
		    <td><input type="checkbox" name="type" class="form" <?php if(isset($type)) echo "checked"; ?>/></td>
	</tr> 
</table>
		<input type="button" value="annuler" onclick="tb_remove()">
	</div>
	</div>
</div>
<!-- fin du bloc description -->
<!-- bloc colonne de droite -->   
<div id="bloc_colonne_droite">
		<div id="listecom"> </div>
</div>
</div> 
<?php include_once("pied.php");?>
</div>
</div>
</body>
</html>
