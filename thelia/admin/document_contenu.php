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
	
	if(!isset($action)) $action="";
	
?>
<?php
	include_once("../fonctions/divers.php");
	include_once("../classes/Document.class.php");  
	include_once("../classes/Contenu.class.php");
?>
<?php
	
	switch($action){
		case 'modclassement' : modclassement($id, $contid, $type); break;		
		case 'ajouter' : ajouter($contid, $_FILES['doc']['tmp_name'], $_FILES['doc']['name']); break;
		case 'modifier' : modifier($id, $titre, $chapo, $description); break;
		case 'supprimer' : supprimer($id);

	}
	

?>


<?php
	

	function modclassement($id, $contid, $type){

      	$doc = new Document();
        $doc->charger($id);
        $doc->changer_classement($id, $type);
	}	
	
	function ajouter($contenu, $doc, $doc_name){

		if($doc != ""){

			$fich = substr($doc_name, 0, strlen($doc_name)-4);
			$ext = substr($doc_name, strlen($doc_name)-3);
			
			$document = new Document();
			$documentdesc = new Documentdesc();

		 	$query = "select max(classement) as maxClassement from $document->table where contenu='" . $contenu . "'";

	 		$resul = mysql_query($query, $document->link);
     		$maxClassement = mysql_result($resul, 0, "maxClassement");
     					
			$document->contenu = $contenu;
			$document->classement = $maxClassement+1;
			
			$lastid = $document->add();
			$document->charger($lastid);
			$fich = eregfic($fich);
			$document->fichier = $fich . "_" . $contenu . "." . $ext;
			$document->maj();
					
			copy("$doc", "../client/document/" . $fich . "_" . $contenu . "." . $ext);
		}
	
	}

	function modifier($id, $titre, $chapo, $description){
	
		$documentdesc = new Documentdesc();
		$documentdesc->document = $id;
		$documentdesc->lang = "1";
	
		$documentdesc->charger($id);
		
		$documentdesc->titre = $titre;
		$documentdesc->chapo = $chapo;
		$documentdesc->description = $description;
	
		if(!$documentdesc->id)
			$documentdesc->add();
		else 
			$documentdesc->maj();

	}


	function supprimer($id){
		
			$document = new Document();
			$document->charger($id);
			
			if(file_exists("../client/document/$document->fichier")){
				 unlink("../client/document/$document->fichier");
			}
			
			$document->supprimer();
			
	}	
	
?>

<?php
	$contenu = new Contenu();
	$contenudesc = new Contenudesc();
	
	$contenu->charger($contid);
	$contenudesc->charger($contenu->id);
	
	
	$contenudesc->chapo = ereg_replace("<br/>", "\n", $contenudesc->chapo);
	$contenudesc->description = ereg_replace("<br/>", "\n", $contenudesc->description);


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>THELIA / BACK OFFICE</title>
<script language="JavaScript" type="text/JavaScript">
<!--



function MM_preloadDocuments() { //v3.0
  var d=document; if(d.documents){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadDocuments.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Document; d.MM_p[j++].src=a[i];}}
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
<p class="titre_rubrique">Gestion des documents par contenu</p>
 
  <table width="100%"  border="0" cellpadding="0" cellspacing="2" class="fond_F0F0F0">
<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" ENCTYPE="multipart/form-data">
	<input type="hidden" name="action" value="ajouter" />
	<input type="hidden" name="contid" value="<?php echo($contid); ?>" /> 
    <tr>
      <td  width="40%" align="left" valign="middle" class="arial11_bold_626262">Ajouter un document:</td>
      <td>
      <input type="hidden" name="action" value="ajouter">
      <input type="hidden" name="contenu" value="<?php echo($contenu->id); ?>">
      <input type="file" name="doc" class="form"><br/>
      <input type="submit" value="Ajouter"></td>
    </tr>
</form>
        <?php
			$document = new Document();
			$documentdesc = new Documentdesc();

			$query = "select * from $document->table where contenu='$contid' order by classement";
			$resul = mysql_query($query, $document->link);

			while($row = mysql_fetch_object($resul)){
				$document = new Document();
				$documentdesc = new Documentdesc();
				
				$documentdesc->charger($row->id);
        ?>
                

			<tr>
		    <td colspan="2" height="1" class="fond_CDCDCD"></td>
		    </tr>
		      <td  width="20%" align="left" valign="middle" class="arial11_bold_626262"><a href="../client/document/<?php echo($row->fichier); ?>" target="_blank"><?php echo($row->fichier); ?></a></td>
		  	  <td width="20%">
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
				<input type="hidden" name="action" value="modifier" />
				<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
				<input type="hidden" name="contid" value="<?php echo $contid; ?>" />

					Titre : <br />
					<input type="text" name="titre" size="35" value="<?php echo $documentdesc->titre ?>" /> <br /><br />
					Chapo : <br />
					<textarea name="chapo" rows="3" cols="40"><?php echo $documentdesc->chapo ?></textarea>
					Description : <br />
					<textarea name="description" rows="10" cols="40"><?php echo $documentdesc->description ?></textarea>
					<input type="submit" value="Enregistrer" />
				</form>
			  </td>	
		      <td  width="20%" align="left" valign="middle" class="arial11_bold_626262"><div align="center"><a href="<?php echo $_SERVER['PHP_SELF'] . "?id=".$row->id."&contid=$contid&action=modclassement&type=M"; ?>"><img src="gfx/bt_flecheh.gif" border="0"></a><a href="<?php echo $_SERVER['PHP_SELF'] . "?id=".$row->id."&contid=$contid&action=modclassement&type=D"; ?>"><img src="gfx/bt_flecheb.gif" border="0"></a></div></td>      
		      <td  width="20%" align="left" valign="middle" class="arial11_bold_626262"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?id=<?php echo($row->id); ?>&contid=<?php echo($contid); ?>&action=supprimer"  class="txt_vert_11">Supprimer</a></td>

		      <td width="60%">&nbsp;</td>
			 </tr>
			<tr>
		    <td colspan="2" height="1" class="fond_CDCDCD"></td>
		    </tr>
                
             
   <?php
   
   	}
                
   ?>
	
	

    
    </form>
    
	<tr>
    <td colspan="2" height="1" class="fond_CDCDCD"></td>
    </tr>

  </table>
</form>
</body>

	
				
</html>

            
