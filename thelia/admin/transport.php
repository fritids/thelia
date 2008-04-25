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
        include_once("../classes/Zone.class.php"); 
        include_once("../classes/Modules.class.php");
        include_once("../classes/Transzone.class.php");
		include_once("../lib/JSON.php");
		
?>
<?php
	function chargert(){

                $i=0;
                   
                $tab = array();
   
				$modules = new Modules();
				$query = "select * from $modules->table where type='2' and actif='1'";
				$resul = mysql_query($query, $modules->link);
				
				while ($row = mysql_fetch_object($resul)) {
					 $modules = new Modules();
					 $modules->charger($row->nom);
					 $tab[$i] = new Modules();
                     $tab[$i++] = $modules;	
					

				}
   
			   
                return tabSerialise($tab);
	}	
	

	function chargerz($transport, $type){
                $i=0;
                $zone = new Zone();
				$transzone = new Transzone();
				$liste="";
				$tab="";
				
               if($type=="d"){
					$query = "select * from $transzone->table where transport='$transport'";               	
					$resul = mysql_query($query, $transzone->link);				
				
					while($row = mysql_fetch_object($resul))
						$liste .= "'$row->zone', ";
				
					$liste = substr($liste, 0, strlen($liste) - 2);
					
					if(!$liste) $liste="''";
					$query = "select * from $zone->table where id in ($liste)";
              
               }
               
               else if($type=="h")  {
               
				$query = "select * from $transzone->table where transport='$transport'";               			
				$resul = mysql_query($query, $transzone->link);				
				
				while($row = mysql_fetch_object($resul))
					$liste .= "'$row->zone', ";
			
				$liste = substr($liste, 0, strlen($liste) - 2);
				if(!$liste) $liste="''";
				$query = "select * from $zone->table where id not in ($liste)";

              
               }	
              
               else  $query = "select * from $zone->table";
               
                $resul = mysql_query($query, $zone->link);	
               
                while($row = mysql_fetch_object($resul)){
                        $zone = new Zone();
                        $zone->charger($row->id);

                        
                        $zone->nom = supprAccent($zone->nom);
                        
                        $tab[$i] = new Zone();
                        $tab[$i++] = $zone;
                }

 
               if(!$tab) { return "";}
         
                return tabSerialise($tab);
                


	}	
	
	function valide($transport, $strd, $strh){

				$transzone = new Transzone();
								
	 			$json = new Services_JSON();

				
	 			$strd = stripslashes($strd);
	 			$resd = $json->decode($strd);
	 			
	 			$strh = stripslashes($strh);
	 			$resh = $json->decode($strh);
	 		
	 			$query = "delete from $transzone->table where transport='$transport'";
				$resul = mysql_query($query, $transzone->link);
			
	 			for($i=0; $i<count($resd); $i++){
	 				$transzone = new Transzone();
	 				$transzone->transport=$transport;
	 				$transzone->zone=$resd[$i];
	 				$transzone->actif="1";
	 				$transzone->add();	 			
	 			
	 			}
	 			

	}



?>
<?php
		//$sajax_debug_mode = 1;
      	sajax_init();        
      	sajax_export("chargert");
        sajax_export("chargerz");
      	sajax_export("chargertzone");
        sajax_export("valide");
 
        sajax_handle_client_request();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>THELIA / BACK OFFICE</title>
<link href="styles.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../fonctions/json.js"></script>
<script>
//-->
	
	var rest;
	var indexCours;
	
	function charger(){

		rest = eval(sx_chargert());

		var contenu;
		
		document.getElementById("divt").innerHTML="";

		if(rest) {
		
		for(i=0; i<rest.length; i++)  {
			var trindex;
			trindex = rest[i]['id'];

			contenu="";
			contenu=contenu + "<div style='border-bottom: solid #CDCDCD 1px;'>";
			
			contenu=contenu + "<span style=' position: absolute;  margin-left: 50px;'>";
			contenu=contenu + rest[i]['nom'];
			contenu=contenu + "</span>";

			contenu=contenu + "<span style=' width: 50px;margin-left: 400px;'>";
			contenu=contenu + "<a href=\"javascript:chargertzone('" + rest[i]['id'] + "')\"><img src='gfx/b_edit.png' width='16' height='16' border='0'></a>";			contenu=contenu + "</span>";

			contenu=contenu + "</div>";
			document.getElementById("divt").innerHTML=document.getElementById("divt").innerHTML + contenu;
			
 		
				
		}	
	}

			var element = document.createElement("option");
			element.text = "liste des zones";
			element.value="0";


  			var resz = eval(sx_chargerz('', ''));
  		  	for(i=0; i<resz.length; i++){
      		element = document.createElement("option");
      		 element.text=resz[i]['nom'];
      		 element.value=resz[i]['id'];
      		

      		}	                        
                    	     
	}
	

      function chargertzone(index){

      	indexCours = index;

     	var reszd = eval(sx_chargerz(index, 'd'));    
  		var reszh = eval(sx_chargerz(index, 'h'));

		document.getElementById('selectszonedm').options.length = 0;
       	document.getElementById('selectszonehm').options.length = 0;

      	if(reszd)
      		for(i=0; i<reszd.length; i++){
     	 	 var element = document.createElement("option");
      		 element.text=reszd[i]['nom'];
      		 element.value=reszd[i]['id'];

      		document.getElementById('selectszonedm').options[document.getElementById('selectszonedm').length]=element;
      	}
      	
      	if(reszh)

      	for(i=0; i<reszh.length; i++){
      	 var element = document.createElement("option");
      	 element.text=reszh[i]['nom'];
      	 element.value=reszh[i]['id'];
      	document.getElementById('selectszonehm').options[document.getElementById('selectszonehm').length]=element;
      	}

      }	  


	  function ajoutzone(type){
	  		
	      var index = document.getElementById('selectszoneh' + type).selectedIndex;
			var element = document.createElement("option");
      	 	element.text=document.getElementById('selectszoneh' + type).options[index].text;
      	    element.value=document.getElementById('selectszoneh' + type).options[index].value;
      	    document.getElementById('selectszoned' + type).options[document.getElementById('selectszoned'+type).length]=element;
      	    
	 		document.getElementById('selectszoneh' + type).remove(index); 
	 		
	 		    
	 		
	  }

	  function supprzone(type){
	  		
	      var index = document.getElementById('selectszoned' + type).selectedIndex;
			var element = document.createElement("option");
      	 	element.text=document.getElementById('selectszoned' + type).options[index].text;
      	    element.value=document.getElementById('selectszoned' + type).options[index].value;
      	    document.getElementById('selectszoneh' + type).options[document.getElementById('selectszoneh'+type).length]=element;
      	    
	 		document.getElementById('selectszoned' + type).remove(index);      
	     
	  }	  
	  
	  
	  function valide(){

	  	var zoned = new Array();
	  	var zoneh = new Array();
	  	
	  	for(i=0; i<document.getElementById('selectszonedm').length; i++)
	  		zoned[i] = document.getElementById('selectszonedm').options[i].value;

	 	for(i=0; i<document.getElementById('selectszonehm').length; i++)
	  		zoneh[i] = document.getElementById('selectszonehm').options[i].value;

	 	 sx_valide( indexCours, JSON.stringify(zoned), JSON.stringify(zoneh)); 
	 	 alert("Modification effectue"); 
}	

       
</script>
<script>
        <?php
	                sajax_show_javascript();
        ?>
</script>
</head>

<body onLoad="charger()">

<?php
	$menu="livraisons";
	include_once("entete.php");
?>

<div id="contenu_int"> 
   <p class="titre_rubrique">Gestion des transports</p>
    <p align="right" class="geneva11Reg_3B4B5B"><a href="accueil.php" class="lien04">Accueil </a><img src="gfx/suivant.gif" width="12" height="9" border="0" /><a href="gestlivraison.php" class="lien04"> Gestion des livraisons</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a href="#" class="lien04">Gestion des transports</a>    </p>
    <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">LISTE DES TRANSPORTS </td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
 
  <tr class="cellule_sombre_vide">
    <td height="30" colspan="2" class="cellule_sombre_vide"> <div class="geneva11bol_3B4B5B" id="divt">
						Chargement ...
      </div>
    </td>
    </tr>

  </table>
   <table width="710" border="0" cellpadding="5" cellspacing="0">
     <tr>
       <td width="600" height="30" class="titre_cellule_tres_sombre">MODIFIER UN TRANSPORT </td>
     </tr>
   </table>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">

 <tr class="cellule_claire_vide">
    <td width="34%" height="30" class="geneva11bol_3B4B5B">
      <div align="center">
        <select name="select" class="form" id="selectszonehm">
          <option selected="selected">listes des zones</option>
        </select>
      </div>
    </td>
    <td width="39%" height="30"> <div align="center">
                            <input name="Submit" type="button" onClick="ajoutzone('m')" class="geneva11bol_3B4B5B" value="Ajouter une zone &gt;&gt; ">
                            <br /><input name="Submit" type="button" onClick="supprzone('m')" class="geneva11bol_3B4B5B" value="&lt;&lt; Supprimer une zone">
    </div></td>
                          <td><div align="center">
                            <select name="select2" size="5" multiple="multiple" class="form" id="selectszonedm">
                              <option selected="selected">liste des zones</option>
                            </select>
                          </div> 
      </td>
    </tr>
 <tr class="cellule_claire_vide">
   <td height="30" class="cellule_claire_vide">&nbsp;</td>
   <td height="30" class="cellule_claire_vide"></td>
   <td class="cellule_claire_vide"> 
     <input name="Submit" type="button" onClick="valide();" class="geneva11bol_3B4B5B" value="Valider">
   </td>
 </tr>
  </table>

</div>
</body>
</html>
