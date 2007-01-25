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

	include_once("../classes/Variable.class.php");
	$style_chem = new Variable();
	$style_chem->charger("style_chem");

?>
<script language="javascript" type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		language : "fr",
		mode : "exact",
	    elements : "description",
		theme : "advanced",
		plugins : "table,advlink",
		theme_advanced_disable : "bold,italic,underline,hr,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,formatselect,separator,help,anchor,cleanup,link,unlink,bullist,numlist,outdent,indent,undo,redo,removeformat,visualaid,image",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		file_browser_callback : "fileBrowserCallBack",
		content_css : "<?php echo $style_chem->valeur ?>",
	  	paste_use_dialog : false,
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,
		paste_auto_cleanup_on_paste : true,
		paste_convert_headers_to_strong : false,
		paste_strip_class_attributes : "all",
		paste_remove_spans : false,
		paste_remove_styles : false,
		extended_valid_elements : "object[classid|codebase|width|height|align],param[name|value],embed[quality|type|pluginspage|width|height|src|align]"		
	});
	
	function fileBrowserCallBack(field_name, url, type, win) {
		// This is where you insert your custom filebrowser logic
		alert("Filebrowser callback: field_name: " + field_name + ", url: " + url + ", type: " + type);

		// Insert new URL, this would normaly be done in a popup
		win.document.forms[0].elements[field_name].value = "someurl.htm";
	}
	</script>