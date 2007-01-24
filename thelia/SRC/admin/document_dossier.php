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
	
	if(!isset($action)) $action="";
	
?>
<?php
	include("../fonctions/divers.php");
	include("../classes/Document.class.php");  
	include("../classes/Dossier.class.php");
    
?>
<?php
	switch($action){
		case 'modclassement' : modclassement($id, $dosid, $type); break;		
		case 'ajouter' : ajouter($dossier, $_FILES['doc']['tmp_name'], $_FILES['doc']['name']); break;
		case 'supprimer' : supprimer($id);

	}
	

?>


<?php
	

	function modclassement($id, $dosid, $type){

		$document = new document();
		$document->charger($id);

	 	$query = "select max(classement) as maxClassement from $document->table where dossier='" . $dosid . "'";

		$resul = mysql_query($query, $document->link);
        $maxClassement = mysql_result($resul, 0, "maxClassement");

		if($type=="M"){

			if($document->classement == 1)  return; 

			$query = "update $document->table set classement=" . $document->classement . " where classement=" . ($document->classement-1) . " and dossier='" . $dosid . "'";
			$resul = mysql_query($query, $document->link);
			 $document->classement--;
		}
		
		else if($type=="D"){

			if($document->classement == $maxClassement) return; 

			
			$query = "update $document->table set classement=" . $document->classement . " where classement=" . ($document->classement+1) . " and dossier='" . $dosid . "'";
			$resul = mysql_query($query, $document->link);
			
			 $document->classement++;
		}
		
		$document->maj();

	
	}	
	
	function ajouter($dossier, $doc, $doc_name){

		if($doc != ""){

			$fich = substr($doc_name, 0, strlen($doc_name)-4);
			$ext = substr($doc_name, strlen($doc_name)-3);
			
			$document = new Document();
			$documentdesc = new Documentdesc();

		 	$query = "select max(classement) as maxClassement from $document->table where dossier='" . $dossier . "'";

	 		$resul = mysql_query($query, $document->link);
     		$maxClassement = mysql_result($resul, 0, "maxClassement");
     					
			$document->dossier = $dossier;
			$document->classement = $maxClassement+1;

			
			$lastid = $document->add();
			$document->charger($lastid);
			$fich = eregfic($fich);
			$document->fichier = $fich . "_" . $dossier . "." . $ext;
			$document->maj();
					
			copy("$doc", "../client/document/" . $fich . "_" . $dossier . "." . $ext);
		}

	}


	function supprimer($id){
		
			$document = new Document();
			$document->charger($id);
			
			if(file_exists("../client/document/$document->fichier")){
				 unlink("../client/document/$document->fichier");
			}
			
			$document->supprimer();
			
			$document->destroy();
		
	}	
	
?>

<?php
	$dossier = new dossier();
	$dossierdesc = new Dossierdesc();
	
	$dossier->charger($dosid);
	$dossierdesc->charger($dossier->id);
	$dossier->destroy();
	$dossierdesc->destroy();
	
	
	$dossierdesc->chapo = ereg_replace("<br/>", "\n", $dossierdesc->chapo);
	$dossierdesc->description = ereg_replace("<br/>", "\n", $dossierdesc->description);


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>nomdedomaine.com</title>
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
<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" ENCTYPE="multipart/form-data">
	<input type="hidden" name="action" value="ajouter" />
	<input type="hidden" name="id" value="<?php echo($id); ?>" /> 
 	<input type="hidden" name="dosid" value="<?php echo($dosid); ?>" /> 

  <table width="100%"  border="0" cellpadding="0" cellspacing="2" class="fond_F0F0F0">

    <tr>
      <td  width="40%" align="left" valign="middle" class="arial11_bold_626262">Ajouter un document:</td>
      <td>
      <input type="hidden" name="action" value="ajouter">
      <input type="hidden" name="dossier" value="<?php echo($dossier->id); ?>">
      <input type="file" name="doc"><br/>
      <input type="submit" value="Ajouter"></td>
    </tr>
        <?php
			$document = new Document();
			$documentdesc = new Documentdesc();

			$query = "select * from $document->table where dossier='$dossier->id' order by classement";
			$resul = mysql_query($query, $document->link);

			while($row = mysql_fetch_object($resul)){
				$documentdesc->charger($row->id);
        ?>
                
	 <tr>
      <td  width="20%" align="left" valign="middle" class="arial11_bold_626262"><a href="../client/document/<?php echo($row->fichier); ?>" target="_blank"><?php echo($row->fichier); ?></a></td>
      <td  width="20%" align="left" valign="middle" class="arial11_bold_626262"><div align="center"><a href="<?php echo $_SERVER['PHP_SELF'] . "?id=".$row->id."&action=modclassement&type=M&dosid=".$dossier->id; ?>"><img src="gfx/bt_flecheh.gif" border="0"></a><a href="<?php echo $_SERVER['PHP_SELF'] . "?id=".$row->id."&action=modclassement&type=D&dosid=".$dossier->id; ?>"><img src="gfx/bt_flecheb.gif" border="0"></a></div></td>      
      <td  width="20%" align="left" valign="middle" class="arial11_bold_626262"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?id=<?php echo($row->id); ?>&dosid=<?php echo($dossier->id;); ?>&action=supprimer">Supprimer</a></td>

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

            