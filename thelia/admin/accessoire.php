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
?>
<?php
	include_once("../lib/Sajax.php");
	include_once("../fonctions/divers.php");
	include_once("../classes/Accessoire.class.php");
	include_once("../classes/Produit.class.php");	

	if(!isset($action)) $action="";
?>
<?php
	function chercher($motcle,$courant) {
		
		$res="";
		
		$produit = new Produit();
		$produitdesc = new Produitdesc();

		$query = "select * from $produitdesc->table  LEFT JOIN $produit->table ON $produit->table.id=$produitdesc->table.id WHERE $produit->table.ref like '%$motcle%' or titre like '%$motcle%' OR chapo like '%$motcle%' OR description like '%$motcle%'";	
		$resul = mysql_query($query);
		
		$num = mysql_numrows($resul);
		
		
		while($row = mysql_fetch_object($resul)){
			$res .= "<a href=\"#\" onClick=\"do_ajouter($row->produit, $courant)\">" . $row->titre . "</a>" . "<br />";
		}

		
		return $res;
	
	}
	

	
	sajax_init();
	// $sajax_debug_mode = 1;
	sajax_export("chercher");
	sajax_export("afficher");
	sajax_export("ajouter");
	sajax_export("supprimer");

	sajax_handle_client_request();

?>
<?php
	
	switch($action){
		case 'modclassement' : modclassement($id, $produit, $type); break;	
		case 'ajouter' : ajouter($produit, $photo, $photo_name); break;
		case 'supprimer' : supprimer($id);

	}
	

?>
<?php


	function modclassement($id, $produit, $type){

		$accessoire = new Accessoire();
		$accessoire->charger($id);

	 	$query = "select max(classement) as maxClassement from $accessoire->table where produit='" . $produit . "'";

		$resul = mysql_query($query, $accessoire->link);
        $maxClassement = mysql_result($resul, 0, "maxClassement");
	 
		if($type=="M"){

			if($accessoire->classement == 1)  return; 

			$query = "update $accessoire->table set classement=" . $accessoire->classement . " where classement=" . ($accessoire->classement-1) . " and produit='" . $produit . "'";

			$resul = mysql_query($query, $accessoire->link);
			 $accessoire->classement--;
		}
		
		else if($type=="D"){

			if($accessoire->classement == $maxClassement) return; 

			
			$query = "update $accessoire->table set classement=" . $accessoire->classement . " where classement=" . ($accessoire->classement+1) . " and produit='" . $produit . "'";
			$resul = mysql_query($query, $accessoire->link);
			
			 $accessoire->classement++;
		}
		
		$accessoire->maj();

	}
	
	function ajouter($id, $courant){

		$temp =  new Accessoire();	
		
		if($id != "" && ! $temp->charger_uni($courant, $id)){

			$accessoire = new Accessoire();

		    $query = "select max(classement) as maxClassement from $accessoire->table where produit='" . $courant . "'";
		
	        $resul = mysql_query($query, $accessoire->link);
   		    $maxClassement = mysql_result($resul, 0, "maxClassement");

	 		$accessoire->classement = $maxClassement + 1;
					
			$accessoire->produit = $courant;
			$accessoire->accessoire = $id;
			
			$lastid = $accessoire->add();
			
		}


		return afficher($courant);

	}

	function afficher($courant){
			$res = "  <table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"2\" class=\"fond_F0F0F0\">";
			
			$accessoire = new Accessoire();
			$prodtemp = new Produit();
			$prodtempdesc = new Produitdesc();
			
			$query = "select * from $accessoire->table where produit='$courant' order by classement";
			$resul = mysql_query($query, $accessoire->link);

			while($row = mysql_fetch_object($resul)){
				$prodtemp->charger_id($row->accessoire);
				$prodtempdesc->charger($prodtemp->id);
				
        
                
$res .= "	 <tr>"
."      <td  width=\"60%\" align=\"left\" valign=\"middle\" class=\"arial11_bold_626262\">$prodtempdesc->titre </td>"
."      <td  width=\"20%\" align=\"left\" valign=\"middle\" class=\"arial11_bold_626262\"><a href=\"" . $_SERVER['PHP_SELF'] . "?id=".$row->id."&action=modclassement&type=M&produit=".$accessoire->produit . "\"><img src=\"gfx/bt_flecheh.gif\" border=\"0\"></a><a href=\"" . $_SERVER['PHP_SELF'] . "?id=".$row->id. "&action=modclassement&type=D&produit=".$accessoire->produit . "\"><img src=\"gfx/bt_flecheb.gif\" border=\"0\"></a></td>"
."      <td  width=\"20%\" align=\"left\" valign=\"middle\" class=\"arial11_bold_626262\"><a href=\"#\" onClick=\"do_supprimer($row->id, $courant)\">Supprimer</a></td>"
."      <td width=\"60%\">&nbsp;</td>"
."	 </tr>"
."	<tr>"
."    <td colspan=\"2\" height=\"1\" class=\"fond_CDCDCD\"></td>"
."    </tr>";
          
   		}
 
    $res.=" <tr>"
 
    
   	."</table>";
   		
   		return $res;
	
	
	}

	function supprimer($id, $courant){
		
			$accessoire = new Accessoire();
			$accessoire->charger($id);

			$accessoire->supprimer();

			return afficher($courant);

	}	
	
?>

<?php
	$produit = new Produit();
	$produitdesc = new Produitdesc();
	
	$produit->charger($ref);
	$produitdesc->charger($produit->id);
	
	$produitdesc->chapo = ereg_replace("<br/>", "\n", $produitdesc->chapo);
	$produitdesc->description = ereg_replace("<br/>", "\n", $produitdesc->description);


?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>THELIA / BACK OFFICE</title>
	<script>
	<?php
	sajax_show_javascript();
	?>

	function do_afficher(res) {
		document.getElementById('liste').innerHTML = res;
	}
		
	function do_load(courant){
		x_afficher(courant, do_afficher);
	}
	
	function do_chercher(courant) {
		// get the folder name
		var motcle;
		motcle = document.getElementById("motcle").value;
		x_chercher(motcle, courant, do_resultat);
	}
	

	function do_resultat(res) {
		document.getElementById('resultat').innerHTML = res;
	}
	
	function do_ajouter(id, courant) {
		x_ajouter(id, courant, do_afficher);
	}

	function do_supprimer(id, courant) {
		x_supprimer(id, courant, do_afficher);
	}
		
	</script>
<script language="JavaScript" type="text/JavaScript">
<!--



function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<style type="text/css">
<!--
body {
	margin-top: 0px;
}
-->
</style>

<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body class="bodyssfond" onLoad="do_load(<?php echo($produit->id); ?>)">
 
 <div id="conteneur" name="conteneur">
 
  <div id="entete" name="entete">
    <span class="arial11_bold_626262">Chercher un produit:</span>  <input type="text" name="motcle" id="motcle" onkeyup="do_chercher(<?php echo($produit->id); ?>)"  class="form"/>

    </div>
 
  <div id="liste" name="liste"> 
  
  

	</div>

	<div class="arial11_bold_626262" id="resultat" name="resultat">



	</div>


</div>
</body>

	
				
</html>

            
