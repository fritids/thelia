<?php
	include_once(realpath(dirname(__FILE__)) . "/Prodprixmult.class.php");
?>
<?php
	if($_REQUEST['action'] == "ajoutmult"){
		$caracdisp = new Caracdisp();
		
		$query_caract = "select * from $caracdisp->table where caracteristique='" . $_REQUEST['caracteristique'] . "'";
		$resul_caract = mysql_query($query_caract, $caracdisp->link);

		$principal = new Variable();
		$principal->charger("principal");
		
		$refsimple = new Variable();
		$refsimple->charger("refsimple");
		
		$i = 0;
		
		while($row = mysql_fetch_object($resul_caract)){

			$produit = new Produit();
			$produit->ref = $_REQUEST['ref'] . $row->id;
			$produit->prix = $_REQUEST['prix'];
			$produit->prix2 = $_REQUEST['prix2'];
			$produit->tva = $_REQUEST['tva'];
			$produit->ecotaxe = $_REQUEST['ecotaxe'];
			$produit->garantie = $_REQUEST['garantie'];
	 	 	if($_REQUEST['promo'] == "on") $produit->promo = 1; else $produit->promo = 0;
	 	 	if($_REQUEST['reappro'] == "on") $produit->reappro = 1; else $produit->reappro = 0;	 	 
	 	 	if($_REQUEST['nouveaute'] == "on") $produit->nouveaute = 1; else $produit->nouveaute = 0;
	 	 	if($_REQUEST['ligne'] == "on") $produit->ligne = 1; else $produit->ligne = 0;
			$produit->rubrique = $_REQUEST['rubrique'];
			$produit->poids = $_REQUEST['poids'];
			$produit->stock = $_REQUEST['stock'];
			$lastid = $produit->add();
			
			$produitdesc = new Produitdesc();
			$produitdesc->produit = $lastid;		
			$produitdesc->lang = "1";
			$produitdesc->titre = $_REQUEST['titre'];
			$produitdesc->chapo = $_REQUEST['chapo'];
			$produitdesc->description = $_REQUEST['description'];		
			$produitdesc->add();
			
			$query_test = "select * from caracdisp where caracteristique='" . $principal->valeur . "' order by id";	
			$resul_test = mysql_query($query_test, $principal->link);
			$valoui = mysql_result($resul_test, 0, "id");
			$valnon = mysql_result($resul_test, 1, "id");
			
			$caracval = new Caracval();
			$caracval->produit = $lastid;
			$caracval->caracteristique = $principal->valeur;
			if(! $i)
				$caracval->caracdisp = $valoui;
			else
				$caracval->caracdisp = $valnon;
			$caracval->add();
		
			$caracval = new Caracval();
			$caracval->produit = $lastid;
			$caracval->caracteristique = $refsimple->valeur;
			$caracval->valeur = $_REQUEST['ref'];
			$caracval->add();
			
			$caracval = new Caracval();
			$caracval->produit = $lastid;
			$caracval->caracteristique = $caracteristique;
			$caracval->caracdisp = $row->id;
			$caracval->add();
			
			$i++;
			
		}		
?>
<script type="text/javascript">
	alert("Produits ajoutes");
	location="parcourir.php?parent=<?php echo $_REQUEST['rubrique']; ?>";
</script>
<?php		
	}
?>

<div id="contenu_int"> 

	   <p class="titre_rubrique">Création de produits multiples déclinés</p>
	     <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Création de produits multiples déclinés</a>              
	    </p>
	     <table width="710" border="0" cellpadding="5" cellspacing="0">
	     <tr>
	       <td width="600" height="30" class="titre_cellule_tres_sombre">Fiche produit</td>
	     </tr>
	   </table>
	
<form action="module.php" id="formulaire" method="post">
	<input type="hidden" name="nom" value="prodprixmult" />
	<input type="hidden" name="action" value="ajoutmult" />
	
	<table width="710" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td height="30" class="titre_cellule">Caractéristique à décliner:</td>
    <td class="cellule_sombre">
     	<select name="caracteristique">
		<?php
			$principal = new Variable();
			$principal->charger("principal");
			
			$refsimple = new Variable();
			$refsimple->charger("refsimple");
			
			$caracteristique = new Caracteristique();
			
			$query_caract =  "select * from $caracteristique->table where id not in(" . $principal->valeur . "," . $refsimple->valeur . ")";
			$resul_caract = mysql_query($query_caract, $caracteristique->link);
			echo $query_caract . "toto";
			while($row = mysql_fetch_object($resul_caract)){
				$caracteristiquedesc = new Caracteristiquedesc();
				$caracteristiquedesc->charger($row->id);
		?>
			<option value="<?php echo $caracteristiquedesc->caracteristique; ?>"><?php echo $caracteristiquedesc->titre; ?></option>
		<?php
			}
		
		?>
		
		<?php ?>
     	</select>
	</td>
  </tr>
  <tr>
    <td height="30" class="titre_cellule">REFERENCE DE BASE:</td>
    <td class="cellule_sombre">
      <input type="text" name="ref" id="ref_c" class="form" value="" >      </td>
  </tr>
  <tr>
    <td height="30" class="titre_cellule">TITRE DU PRODUIT </td>
    <td class="cellule_claire"><input name="titre" type="text" class="form" value="">      </td>
  </tr>
  <tr>
    <td height="30" class="titre_cellule">CHAPO (resumé de la description)</td>
    <td class="cellule_sombre">
      <textarea name="chapo" cols="40" rows="2" class="form"></textarea>      </td>
  </tr>
  <tr>
    <td height="30" class="titre_cellule">DESCRIPTION DU PRODUIT</td>
    <td class="cellule_claire">

			<textarea name="description" rows="18" cols="50" style="width: 100%"></textarea>

      </span></td>
  </tr>
	 <tr>
    <td height="30" colspan="2" class="titre_cellule_tres_sombre">Caractéristiques du produit </span></td>
    </tr>
  <tr>
    <td width="250" height="30" class="titre_cellule">PRIX</td>
    <td width="440" class="cellule_sombre">
      <input name="prix" type="text" class="form" value="" />
      </span></td>
  </tr>
  <tr>
    <td height="30" class="titre_cellule">PRIX PROMOTIONNE </td>
    <td class="cellule_claire">
      <input name="prix2" type="text" class="form" value="" />
      </span></td>
  </tr>
  <tr>
    <td height="30" class="titre_cellule">TVA </td>
    <td class="cellule_claire">
      <input name="tva" type="text" class="form" value="19.6" />
      </span></td>
  </tr>
  <tr>
    <td height="30" class="titre_cellule">Eco-taxe </td>
    <td class="cellule_claire">
      <input name="ecotaxe" type="text" class="form" value="" />
      </span></td>
  </tr>
  <tr>
    <td height="30" class="titre_cellule">NOUVEAUTE</td>
    <td class="cellule_sombre">
      <input name="nouveaute" type="checkbox" class="form"  />
      </span></td>
  </tr>
  <tr>
    <td height="30" class="titre_cellule">GARANTIE</td>
    <td class="cellule_claire">
      <input name="garantie" type="text" class="form" value="" />
      </span></td>
  </tr>
  <tr>
    <td height="30" class="titre_cellule">EN PROMO </td>
    <td class="cellule_sombre">
      <input name="promo" type="checkbox" class="form"  />
      </span></td>
  </tr>
  <tr>
    <td height="30" class="titre_cellule">EN REAPROVISIONNEMENT </td>
    <td class="cellule_claire">
      <input name="reappro" type="checkbox" class="form"  />
      </span></td>
  </tr>
  <tr>
    <td height="30" class="titre_cellule">EN LIGNE </td>
    <td class="cellule_sombre">
      <input name="ligne" type="checkbox" class="form"  />
      </span></td>
  </tr>
	 <tr>
    <td height="30" class="titre_cellule">APPARTENANCE (rubrique p&egrave;re) </td>
    <td class="cellule_sombre">
      <select name="rubrique" class="form">
                  	<option value="">&nbsp;</option>
   			        <?php 
					 	echo arbreOption(0, 1, 0); 
			 		 ?>  
         			</select>
      </span></td>
  </tr>
	 <tr>
    <td height="30" class="titre_cellule">POIDS</td>
    <td class="cellule_sombre">
      <input type="text" name="poids" value="" />
      </span></td>
  </tr>
  
	 <tr>
    <td height="30" class="titre_cellule">STOCK </td>
    <td class="cellule_sombre">
      <input type="text" name="stock" value="" />
      </span></td>
  </tr>        
 
          
</table>
		  
<table width="710" border="0" cellpadding="5" cellspacing="0">
  <tr><input type="submit" id="boutoncache" style="display: none">
    <td height="30" class="cellule_sombre2"><span class="sous_titre_rubrique"><span class="geneva11Reg_3B4B5B"><a href="#" onclick="document.getElementById('formulaire').submit();" class="txt_vert_11">Valider les modifications </a></span> <a href="#" onclick="document.getElementById('formulaire').submit();"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></span></td>
  </tr>
</table>
 </form>

</div>