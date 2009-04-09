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

	<script language="javascript" type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
    <script language="javascript" type="text/javascript">

            tinyMCE.init({
	                language : "fr",
	                mode : "exact",
	            	elements : "description",
                    theme : "advanced",
                    plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",
					theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,|,image,link,unlink,code,undo,redo",
					theme_advanced_buttons2 : "",
					theme_advanced_buttons3 : "",
                    theme_advanced_toolbar_location : "top",
                    theme_advanced_toolbar_align : "left",
                    theme_advanced_statusbar_location : "bottom",
                    file_browser_callback : "fileBrowserCallBack",
					content_css : "<?php echo $style_chem->valeur ?>",
                    theme_advanced_resizing : true,


					template_external_list_url : "js/template_list.js",
					external_link_list_url : "js/link_list.js",
					external_image_list_url : "js/image_list.js",
					media_external_list_url : "js/media_list.js"
					
            });

		function fileBrowserCallBack(field_name, url, type, win) {
			var connector = "../../filemanager/browser.html?Connector=connectors/php/connector.php";
			var enableAutoTypeSelection = true;

			var cType;
			tinymcpuk_field = field_name;
			tinymcpuk = win;

			switch (type) {
				case "image":
					cType = "Image";
					break;
				case "flash":
					cType = "Flash";
					break;
				case "file":
					cType = "File";
					break;
			}

			if (enableAutoTypeSelection && cType) {
				connector += "&Type=" + cType;
			}

			window.open(connector, "tiny_mce", "modal,width=600,height=400");
		}
	</script>


