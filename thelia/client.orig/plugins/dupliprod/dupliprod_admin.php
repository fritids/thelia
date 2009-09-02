<?php
	include_once(realpath(dirname(__FILE__)) . "/../../../fonctions/authplugins.php");

	autorisation("dupliprod");

	include_once(realpath(dirname(__FILE__)) . "/Dupliprod.class.php");
	
	if($_REQUEST['action'] == "dupliquer"){
		$test = new Produit();
		if(! $test->charger($_REQUEST['nouvelle_ref'])){
			$produit = new Produit();
			if($produit->charger($_REQUEST['courante_ref'])){
		
				$query_dup = "select max(classement) as maxclassement from $produit->table where rubrique=\"" . $produit->rubrique . "\"";
				$resul_dup = mysql_query($query_dup, $produit->link);
				$classement = mysql_result($resul_dup, 0, "maxclassement");
				
				$produitdesc = new Produitdesc();
				$produitdesc->charger($produit->id);
				
				$newproduit = new Produit();
				$newproduitdesc = new Produitdesc();
				
				$newproduit = $produit;
				$newproduit->id = "";
				$newproduit->ref = $_REQUEST['nouvelle_ref'];
				$newproduit->classement = $classement + 1;
				
				$lastid = $newproduit->add();
				
				$newproduitdesc = $produitdesc;
				$newproduitdesc->id = "";
				$newproduitdesc->produit = $lastid;
				$newproduitdesc->add();
				
?>
				<script type="text/javascript">
					alert("Duplication correcte");
					location="parcourir.php?parent=<?php echo $produit->rubrique; ?>";
				</script>
<?php
			}
			
			else{
				
?>
				<script type="text/javascript">
					alert("Le produit n'existe pas");
				</script>
<?php
			}
			
		
?>

<?php
 			
		}
		
		else {
			
?>	
	<script type="text/javascript">
		alert("Ref non disponible");
	</script>
<?php		
		}
		
	}
?>

	<div id="contenu_int"> 
	   <p class="titre_rubrique">Duplication de produits</p>
	     <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="#" class="lien04">Changement de references</a>              
	    </p>
	     <table width="710" border="0" cellpadding="5" cellspacing="0">
	     <tr>
	       <td width="600" height="30" class="titre_cellule_tres_sombre">Changer une référence</td>
	   </tr>
	   </table>

<form action="module.php" method="post">
<input type="hidden" name="nom" value="dupliprod" />
<input type="hidden" name="action" value="dupliquer" />

<table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr class="cellule_claire">
    <td width="20%" height="30">Ref courante<input type="texte" name="courante_ref" /></td>
 	<td width="20%" height="30">Nouvelle ref <input type="texte" name="nouvelle_ref" /></td>
 	<td width="20%" height="30"><input type="submit" value="changer" /></td>

  </tr>

  </table>
</form>
</div>
</body>
</html>
