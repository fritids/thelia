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
	include("auth.php");
	include_once("pre.php");
?>
<?php

        include("../lib/Sajax.php");
        include("../fonctions/divers.php");
        include("../classes/Rubdeclinaison.class.php");
        include("../classes/Declinaison.class.php");
        include("../classes/Rubrique.class.php");
        include("../classes/Lang.class.php");

      
	if(file_exists("../lib/JSON.php")) include_once("../lib/JSON.php");
	else include_once("lib/JSON.php");
	
	if(!isset($lang)) $lang="";
	if(!isset($parent)) $parent="";
	if(!isset($ref)) $ref="";


?>
<?php


	
	function charger($type, $rubrique){

                $i=0;
				$tab[$i]="";
                $rubdeclinaison = new Rubdeclinaison();
                $declinaison = new Declinaison();
                $declinaisondesc = new Declinaisondesc();
              
            	$query = "select ($declinaison->table.id) from $declinaison->table where boutique=\"" . $_SESSION['bout'] ."\"";

               $resul = mysql_query($query, $declinaison->link);
                while($row = mysql_fetch_object($resul)){                		

                		$res = $rubdeclinaison->charger($rubrique, $row->id);

                		if(($type=="h" && $res) || ($type=="d" && !$res)) continue;

                        $declinaisondesc->charger($row->id);
                        $tab[$i] = new Declinaisondesc();
                        $tab[$i++] = $declinaisondesc;
                }

				if(count($tab)) return tabSerialise($tab);
                else return "";
              
          
                
	}
	
	function valide($rubrique, $strd, $strh){

				$rubdeclinaison = new Rubdeclinaison();
								
	 			$json = new Services_JSON();

	 			$strd = stripslashes($strd);
	 			$resd = $json->decode($strd);
	 			
	 			$strh = stripslashes($strh);
	 			$resh = $json->decode($strh);
	 		
	 			$query = "delete from $rubdeclinaison->table where rubrique='$rubrique'";	
				$resul = mysql_query($query, $rubdeclinaison->link);


	 			for($i=0; $i<count($resd); $i++){
	 				$rubdeclinaison->rubrique = $rubrique;
	 				$rubdeclinaison->declinaison = $resd[$i];
	 				$rubdeclinaison->add();
	 			}

				$rubdeclinaison->destroy();

	}


	
?>
<?php
		//$sajax_debug_mode = 1;
      	sajax_init();
        sajax_export("charger");
        sajax_export("valide");

        sajax_handle_client_request();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<script type="text/javascript" src="../fonctions/json.js"></script>
<script language="JavaScript" type="text/JavaScript">


	var resd;
	var resh;
	
	function charger(id){
		document.getElementById('selectcd').options.length = 0;
        document.getElementById('selectch').options.length = 0;

		resh = eval(sx_charger('h', id));

    	  if(resh)
    	  
    	  	for(i=0; i<resh.length; i++){
      		element = document.createElement("option");
      		 element.text=resh[i]['titre'];
      		 element.value=resh[i]['declinaison'];
      		 document.getElementById('selectch').options[document.getElementById('selectch').length]=element;

      		}	 
   		
		resd = eval(sx_charger('d', id));

    	  	for(i=0; i<resd.length; i++){
      		element = document.createElement("option");
      		 element.text=resd[i]['titre'];
      		 element.value=resd[i]['declinaison'];
      		 document.getElementById('selectcd').options[document.getElementById('selectcd').length]=element;

      		}	 
      		
      		                     
            
      	     
	}
		  
	  function valide(id){

	  	var cd = new Array();
	  	var ch = new Array();
	  	
	  	for(i=0; i<document.getElementById('selectcd').length; i++){
	  		cd[i] = document.getElementById('selectcd').options[i].value;
	
	}

	 	for(i=0; i<document.getElementById('selectch').length; i++)
	  		ch[i] = document.getElementById('selectch').options[i].value;

	  	 sx_valide(id, JSON.stringify(cd), JSON.stringify(ch)); 
	  	 
	 	 charger(id);
	 	 
	 	 alert("Mise à jour effectuée");
	 	 location="rubrique_modifier.php?id=" + id;
}	


	  function ajout(){
			var index = document.getElementById('selectch').selectedIndex;
			var element = document.createElement("option");
      	 	element.text=document.getElementById('selectch').options[index].text;
      	    element.value=document.getElementById('selectch').options[index].value;
      	    document.getElementById('selectcd').options[document.getElementById('selectcd').length]=element;
      	    
	 		document.getElementById('selectch').remove(index);      
	 		
	  }

	  function suppr(){
	  		
	      var index = document.getElementById('selectcd').selectedIndex;
			var element = document.createElement("option");
      	 	element.text=document.getElementById('selectcd').options[index].text;
      	    element.value=document.getElementById('selectcd').options[index].value;
      	    document.getElementById('selectch').options[document.getElementById('selectch').length]=element;
      	    
	 		document.getElementById('selectcd').remove(index);      
	     
	  }	
	  
</script>

<link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<script>
        <?php
	                sajax_show_javascript();
        ?>


</script>
<body onLoad="charger(<?php echo($id); ?>);">

<?php
	include("../classes/Boutique.class.php");
?>
<?php
	$menu="catalogue";
	include("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des d&eacute;clinaisonsf </p>
   <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a><a href="#" onclick="document.getElementById('formulaire').submit()"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a><a href="catalogue.php" class="lien04">Gestion</a><a href="catalogue.php" class="lien04">du catalogue</a>              
    
            <?php
                    $parentdesc = new Rubriquedesc();

                                        $parentdesc->charger($id, $lang);
                                        $parentnom = $parentdesc->titre;
                                        $parentdesc->destroy();

                                        $res = chemin($id);
                                        $tot = count($res)-1;

?>



                        <?php
                                while($tot --){
                        ?>
                        <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="rubrique_modifier.php?id=<?php echo($res[$tot+1]->id); ?>" class="lien04"> <?php echo($res[$tot+1]->titre); ?></a>
            <?php
                }

            ?>

                        <?php
                    $parentdesc = new Rubriquedesc();
                                        if($parent) $parentdesc->charger($parent);
                                        else $parentdesc->charger($id);
                                        $parentnom = $parentdesc->titre;
                                        $parentdesc->destroy();
                        ?>
                        <img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="rubrique_modifier.php?id=<?php echo($parentdesc->rubrique); ?>" class="lien02"> <?php echo($parentdesc->titre); ?></a>


			<img src="gfx/suivant.gif" width="12" height="9" border="0" /> 
            <?php if( !$ref) { ?>Ajouter<?php } else { ?> Modifier <?php } ?> </p>
   
   
   
    <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES DECLINAISONS </td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

      

    
  <tr >
    <td width="33%" height="30">
      <div align="right">
        <select name="select" size="5" multiple="multiple" id="selectch">
        </select>
        </div>
    </td>
    <td width="33%">
	  <div align="center">
	    <input type="button" value="Ajouter&gt;&gt;" onClick="ajout()" />&nbsp;
	    <input type="button" value="&lt;&lt;Supprimer" onClick="suppr()"  />
	      </div>
    </td>
    <td width="33%" height="30">
     
        <div align="left">
          <select name="select2" size="5" multiple="multiple" id="selectcd">
          </select>
            </div>
    </td>
  </tr>
  </table>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
    <tr>
      <td height="30" class="cellule_sombre2"><span class="sous_titre_rubrique"><span class="geneva11Reg_3B4B5B"><a href="#" onClick="valide(<?php echo($id); ?>)" class="txt_vert_11">Valider les modifications </a></span> <a href="#" onClick="valide(<?php echo($id); ?>)"><img src="gfx/suivant.gif" width="12" height="9" border="0" /></a></span></td>
    </tr>
  </table>
</div>
</body>
</html>
