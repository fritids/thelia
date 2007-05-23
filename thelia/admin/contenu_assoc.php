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
	include_once("../fonctions/divers.php");
	include_once("../classes/Rubrique.class.php");
	include_once("../classes/Contenu.class.php");
	include_once("../classes/Contenuassoc.class.php");
	include_once("../classes/Dossier.class.php");
	
	if(!isset($action)) $action="";

?>
<?php
	
	switch($action){
		case 'modclassement' : modclassement($id, $typec); break;		
		case 'ajouter' : ajouter($objet, $type, $contenu); break;
		case 'supprimer' : supprimer($id);

	}
	

?>


<?php


	function modclassement($id, $typec){

		$contenuassoc = new Contenuassoc();
		$contenuassoc->charger($id);

	 	$query = "select max(classement) as maxClassement from $contenuassoc->table where objet=\"" . $contenuassoc->objet . "\" and type=\"" . $contenuassoc->type . "\"";

		$resul = mysql_query($query, $contenuassoc->link);
        $maxClassement = mysql_result($resul, 0, "maxClassement");

		if($typec=="M"){

			if($contenuassoc->classement == 1)  return; 

			$query = "update $contenuassoc->table set classement=" . $contenuassoc->classement . " where classement=" . ($contenuassoc->classement-1) . " and objet=\"" . $contenuassoc->objet . "\" and type=\"" . $contenuassoc->type . "\"";

			$resul = mysql_query($query, $contenuassoc->link);
			 $contenuassoc->classement--;
		}
		
		else if($typec=="D"){

			if($contenuassoc->classement == $maxClassement) return; 

			
			$query = "update $contenuassoc->table set classement=" . $contenuassoc->classement . " where classement=" . ($contenuassoc->classement+1) . " and objet=\"" . $contenuassoc->objet . "\" and type=\"" . $contenuassoc->type . "\"";

			$resul = mysql_query($query, $contenuassoc->link);
			
			 $contenuassoc->classement++;
		}
		
		$contenuassoc->maj();

	
	}	
	
	function ajouter($objet, $type, $contenu){
	
		$contenuassoc = new Contenuassoc();
	
		$query = "select max(classement) as maxClassement from $contenuassoc->table where type='$type' and objet='$objet'";

		$resul = mysql_query($query, $contenuassoc->link);
   		$maxClassement = mysql_result($resul, 0, "maxClassement");

     	$contenuassoc->classement = $maxClassement+1;
	
		if($contenuassoc->existe($objet, $type, $contenu)) return;
		$contenuassoc->objet = $objet;
		$contenuassoc->type = $type;
		$contenuassoc->contenu = $contenu;
		$contenuassoc->add();
		

	}


	function supprimer($id){
		
		$contenuassoc = new Contenuassoc();
		$contenuassoc->charger($id);
		$contenuassoc->delete();
		
		
	}	
	
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>THELIA / BACK OFFICE</title>
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

<body>
<p class="titre_rubrique">Gestion des contenus associés</p>

 
  <table width="100%"  border="0" cellpadding="0" cellspacing="2" class="fond_F0F0F0">
<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" ENCTYPE="multipart/form-data">
	<input type="hidden" name="action" value="ajouter" />

        <?php
			$contenuassoc = new Contenuassoc();
			$contenudesc = new Contenudesc();

			$query = "select * from $contenuassoc->table where type='$type' and objet='$objet' order by classement";
			$resul = mysql_query($query, $contenuassoc->link);
			$liste="";
			
			while($row = mysql_fetch_object($resul)){
			
				$contenu = new Contenu();
				$contenu->charger($row->contenu);
				
				$contenudesc->charger($row->contenu);
				
				$liste .= "'" . $row->contenu . "',";
				
				$dossierdesc = new Dossierdesc();
				$dossierdesc->charger($contenu->dossier);
        ?>
                
	 <tr>
      <td  width="40%" align="left" valign="middle" class="arial11_bold_626262"><?php echo($dossierdesc->titre); ?> / <?php echo($contenudesc->titre); ?></td>
       <td width="20%" align="left" valign="middle" class="arial11_bold_626262"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?action=supprimer&id=<?php echo($row->id); ?>&objet=<?php echo($objet); ?>&type=<?php echo($type); ?>"  class="txt_vert_11">Supprimer</a></td>
      <td  width="40%" align="left" valign="middle" class="arial11_bold_626262"><div align="center"><a href="<?php echo $_SERVER['PHP_SELF'] . "?id=".$row->id."&action=modclassement&typec=M&objet=$objet&type=$type"; ?>"><img src="gfx/bt_flecheh.gif" border="0"></a><a href="<?php echo $_SERVER['PHP_SELF'] . "?id=".$row->id."&action=modclassement&typec=D&objet=$objet&type=$type"; ?>"><img src="gfx/bt_flecheb.gif" border="0"></a></div></td>
	 </tr>
	<tr>
    <td colspan="3" height="1" class="fond_CDCDCD"></td>
    </tr>

                 
   <?php
   
   	}
    
    $liste = substr($liste, 0, strlen($liste)-1);        
    if($liste=="") $liste="''";
                
   ?>
	
	
                
     <tr>
          <td><br /><br /></td>
     </tr>
     
        
        <?php
			$contenu = new Contenu();
			$contenudesc = new Contenudesc();

			$query = "select * from $contenu->table where id not in ($liste)";
			$resul = mysql_query($query, $contenu->link);
			while($row = mysql_fetch_object($resul)){
				$contenudesc->charger($row->id);
				
				$dossierdesc = new Dossierdesc();
				$dossierdesc->charger($row->dossier);
        ?>
        	 <tr>
      			<td width="40%" align="left" valign="middle" class="arial11_bold_626262"><?php echo($dossierdesc->titre); ?> / <?php echo($contenudesc->titre); ?></td>
      			<td width="20%" align="left" valign="middle" class="arial11_bold_626262">&nbsp;</td>
				<td width="40%"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?action=ajouter&objet=<?php echo($objet); ?>&type=<?php echo($type); ?>&contenu=<?php echo($row->id); ?>"  class="txt_vert_11">Activer</a></td>     		
 	<tr>
    <td colspan="3" height="1" class="fond_CDCDCD"></td>
    </tr>       	
        		
        		
        	</tr>
        <?php
        	}
        ?>

	

    
	<tr>
    <td colspan="2" height="1" class="fond_CDCDCD"></td>
    </tr>
</form>
  </table>

</body>

	
				
</html>

            
