		
		// remote scripting library
		// (c) copyright 2005 modernmethod, inc
		var sajax_debug_mode = false;
		var sajax_request_type = "GET";
		var sajax_target_id = "";
		
		function sajax_debug(text) {
			if (sajax_debug_mode)
				alert("RSD: " + text)
		}

 		function sajax_init_object() {
 			sajax_debug("sajax_init_object() called..")
 			
 			var A;
			try {
				A=new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					A=new ActiveXObject("Microsoft.XMLHTTP");
				} catch (oc) {
					A=null;
				}
			}
			if(!A && typeof XMLHttpRequest != "undefined")
				A = new XMLHttpRequest();
			if (!A)
				sajax_debug("Could not create connection object.");
			return A;
		}

		function sajax_parse_response(x) {
			/* return array(bool status, data) */
			var status = (x.responseText.charAt(0) == "+");
			var data = x.responseText.substring(2);
			return new Array(status, data);	
		}

		function sajax_do_call(func_name, async, args) {
			var i, x, n;
			var uri;
			var post_data;
			var target_id;
			var argc;

			argc = async ? args.length -1 : args.length;

			sajax_debug("in sajax_do_call().." + sajax_request_type + "/" + sajax_target_id);
			target_id = sajax_target_id;
			if (sajax_request_type == "") 
				sajax_request_type = "GET";
			
			uri = "";
			if (sajax_request_type == "GET") {
			
				if (uri.indexOf("?") == -1) 
					uri += "?rs=" + escape(func_name);
				else
					uri += "&rs=" + escape(func_name);
				uri += "&rst=" + escape(sajax_target_id);
				uri += "&rsrnd=" + new Date().getTime();
				
				for (i = 0; i < argc; i++) 
					uri += "&rsargs[]=" + escape(args[i]);

				post_data = null;
			} 
			else if (sajax_request_type == "POST") {
				post_data = "rs=" + escape(func_name);
				post_data += "&rst=" + escape(sajax_target_id);
				post_data += "&rsrnd=" + new Date().getTime();
				
				for (i = 0; i < argc; i++) 
					post_data = post_data + "&rsargs[]=" + escape(args[i]);
			}
			else {
				alert("Illegal request type: " + sajax_request_type);
			}
			
			x = sajax_init_object();
			x.open(sajax_request_type, uri, async);
			
			if (sajax_request_type == "POST") {
				x.setRequestHeader("Method", "POST " + uri + " HTTP/1.1");
				x.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			}

			sajax_debug(func_name + " uri = " + uri + "/post = " + post_data);

			if (async) {
				x.onreadystatechange = function() {
					if (x.readyState != 4) 
						return;
	
					sajax_debug("received " + x.responseText);
					var resp = sajax_parse_response(x);

					if(! resp[0]) {
						alert("Error: " + resp[1]);
					} else {
						if (target_id != "")  {
							document.getElementById(target_id).innerHTML = eval(resp[1]);
						} else {
							args[args.length-1](eval(resp[1]));
						}
					}
					delete resp;
				}

				x.send(post_data);
				ret = true;
				sajax_debug(func_name + " waiting..");

			} else {
				x.send(post_data);
				var resp = sajax_parse_response(x);
				if(! resp[0]) {
					alert("sync call failed: " + func_name);
					ret = false;
				} else {
					ret = (eval(resp[1]));
				}
				delete resp;
			}

			delete x;
			return ret;
		}
		
				
		// wrapper for gosaj		
		function x_gosaj() {
			sajax_do_call("gosaj", true,
				x_gosaj.arguments);
		}

		function sx_gosaj() {
			return sajax_do_call("gosaj", false,
				sx_gosaj.arguments);
		}
		
				
		// wrapper for ajoutsaj		
		function x_ajoutsaj() {
			sajax_do_call("ajoutsaj", true,
				x_ajoutsaj.arguments);
		}

		function sx_ajoutsaj() {
			return sajax_do_call("ajoutsaj", false,
				sx_ajoutsaj.arguments);
		}
		
				
		// wrapper for modifpasssaj		
		function x_modifpasssaj() {
			sajax_do_call("modifpasssaj", true,
				x_modifpasssaj.arguments);
		}

		function sx_modifpasssaj() {
			return sajax_do_call("modifpasssaj", false,
				sx_modifpasssaj.arguments);
		}
		
				
		// wrapper for modifcoordsaj		
		function x_modifcoordsaj() {
			sajax_do_call("modifcoordsaj", true,
				x_modifcoordsaj.arguments);
		}

		function sx_modifcoordsaj() {
			return sajax_do_call("modifcoordsaj", false,
				sx_modifcoordsaj.arguments);
		}
		
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


var tabDiv = new Array();
var boucleMoment;
	
function do_analyse(boucle, param) {
	boucleMoment = "thelia" + boucle;
	
	document.getElementById(boucleMoment).innerHTML=sx_gosaj(boucle, param);

	if(! document.getElementById(boucleMoment).innerHTML) return "";
}

function do_ajout(ref) {
	sx_ajoutsaj(ref);
 }

function do_modpass(pass) {
	sx_modifpasssaj(pass);
 }

function do_modcoord(raison, nom, prenom, telfixe, telport, email, adresse1, adresse2, adresse3, cpostal, ville, pays) {
	sx_modifcoordsaj(raison, nom, prenom, telfixe, telport, email, adresse1, adresse2, adresse3, cpostal, ville, pays);
	
}
