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
	include("../fonctions/divers.php");
	include("../classes/Image.class.php");
	include("../classes/Rubrique.class.php");
	include("../classes/Variable.class.php");
	
	if(!isset($action)) $action="";
	
?>
<?php
	
	switch($action){
		case 'modclassement' : modclassement($id, $rubid, $type); break;		
		case 'ajouter' : ajouter($rubid); break;
		case 'supprimer' : supprimer($id);

	}
	

?>


<?php


	function modclassement($id, $rubid, $type){

		$image = new image();
		$image->charger($id);

	 	$query = "select max(classement) as maxClassement from $image->table where rubrique='" . $rubid . "'";

		$resul = mysql_query($query, $image->link);
        $maxClassement = mysql_result($resul, 0, "maxClassement");

		if($type=="M"){

			if($image->classement == 1)  return; 

			$query = "update $image->table set classement=" . $image->classement . " where classement=" . ($image->classement-1) . " and rubrique='" . $rubid . "'";
			$resul = mysql_query($query, $image->link);
			 $image->classement--;
		}
		
		else if($type=="D"){

			if($image->classement == $maxClassement) return; 

			
			$query = "update $image->table set classement=" . $image->classement . " where classement=" . ($image->classement+1) . " and rubrique='" . $rubid . "'";
			$resul = mysql_query($query, $image->link);
			
			 $image->classement++;
		}
		
		$image->maj();

	
	}	
	
	function ajouter($id){

		if(!isset($nomorig)) $nomorig="";

		for($i = 1; $i<6; $i++){
			$photo = $_FILES["photo" . $i]['tmp_name'];
			$photo_name = $_FILES["photo" . $i]['name'];
		

		if($photo != ""){

       	    $extension = substr($photo_name, strlen($nomorig)-3);
			$fich = substr($photo_name, 0, strlen($doc_name)-4);
			
			$photoprodw = new Variable();
			$photoprodw->charger("photorubw");

			$image = new Image();
			$imagedesc = new Imagedesc();
			
			$image->rubrique = $id;

		 	$query = "select max(classement) as maxClassement from $image->table where rubrique='" . $id . "'";

	 		$resul = mysql_query($query, $image->link);
     		$maxClassement = mysql_result($resul, 0, "maxClassement");
    					
			$image->classement = $maxClassement+1;

			
			
			$lastid = $image->add();
			
			$image->charger($lastid);
			$image->fichier = $fich . "_" . $lastid . "." . $extension;
			$image->maj();
			
			copy("$photo", "../client/gfx/photos/rubrique/grande/" . $fich . "_" . $lastid . "." . $extension);
	   		copy("$photo", "../client/gfx/photos/rubrique/petite/" . $fich . "_" . $lastid . "." . $extension);
    		resize("../client/gfx/photos/rubrique/petite/" . $fich . "_" . $lastid . "." . $extension, $photoprodw->valeur);
    		
		}
	 }
	}


	function supprimer($id){
		
			$image = new Image();
			$image->charger($id);
			
			if(file_exists("../client/gfx/photos/rubrique/petite/$image->fichier")){
				 unlink("../client/gfx/photos/rubrique/petite/$image->fichier");
				 unlink("../client/gfx/photos/rubrique/grande/$image->fichier");
			 
			}
			
			$image->supprimer();
			
			$image->destroy();
		
	}	
	
?>

<?php
	$rubrique = new Rubrique();
	
	$rubrique->charger($rubid);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>nomdedomaine.com</title>
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
	<input type="hidden" name="action" value="ajouter" />
 
  <table width="100%"  border="0" cellpadding="0" cellspacing="2" class="fond_F0F0F0">
	
	<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" ENCTYPE="multipart/form-data">

    <tr>
      <td  width="40%" align="left" valign="middle" class="arial11_bold_626262">Ajouter une photo:</td>
      <td>
      <input type="hidden" name="action" value="ajouter">
      <input type="hidden" name="rubid" value="<?php echo($rubid); ?>">
      <?php for($i=1; $i<6; $i++) { ?>
	      <input type="file" name="photo<?php echo($i); ?>"><br/>
	  <?php } ?>
	        <input type="submit" value="Ajouter"></td>
    </tr>
 	</form>


	<tr>
    <td colspan="2" height="1" class="fond_CDCDCD"></td>
    </tr>
       
        <?php
			$image = new Image();
			$imagedesc = new Imagedesc();

			$query = "select * from $image->table where rubrique='$rubid' order by classement";
			$resul = mysql_query($query, $image->link);

			while($row = mysql_fetch_object($resul)){
				$imagedesc->charger($row->id);
        ?>
                
	 <tr>
      <td  width="20%" align="left" valign="middle" class="arial11_bold_626262"><img src="../client/gfx/photos/rubrique/petite/<?php echo($row->fichier); ?>" / ></td>
      <td  width="20%" align="left" valign="middle" class="arial11_bold_626262"><div align="center"><a href="<?php echo $_SERVER['PHP_SELF'] . "?id=".$row->id."&action=modclassement&type=M&rubid=".$rubrique->id; ?>"><img src="gfx/bt_flecheh.gif" border="0"></a><a href="<?php echo v . "?id=".$row->id."&action=modclassement&type=D&rubid=".$rubrique->id; ?>"><img src="gfx/bt_flecheb.gif" border="0"></a></div></td>
       <td  width="20%" align="left" valign="middle" class="arial11_bold_626262"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?id=<?php echo($row->id); ?>&rubid=<?php echo($rubrique->id); ?>&action=supprimer">Supprimer</a></td>

      <td width="60%">&nbsp;</td>
	 </tr>
	<tr>
    <td colspan="2" height="1" class="fond_CDCDCD"></td>
    </tr>
                
              
   <?php
   
   	}
                
   ?>
	
	

    


  </table>
</body>

	
				
</html>

            
