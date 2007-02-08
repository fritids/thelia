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


var xmin;
var ymin;
var xmax;
var ymax;
var encours;
var i=0;


function cacher(div){
	document.getElementById(div).style.display="none";


}

function montrer(div){
        document.getElementById(div).style.display="block";
}

function chdisplay(div, disp){
        document.getElementById(div).style.display=disp;
}

function invdisplay(div){
        if(document.getElementById(div).style.display=="none")  document.getElementById(div).style.display="block";
        else document.getElementById(div).style.display="none";
}

function opacite(div, valeur){
		document.getElementById(div).style.filter ="Alpha(Opacity=" + valeur + ")";
		document.getElementById(div).style.opacity=  valeur/100;
}	
	
function retaille(div, direction, deb, fin){

	if(direction == "w") document.getElementById(div).style.width=deb+"px";
	else document.getElementById(div).style.height=deb+"px";
    document.getElementById(div).style.display="block";
	
	if(deb<fin) 
		if(deb+10<fin) deb+=10;
		else {
			deb=fin;
			if(direction == "w") document.getElementById(div).style.width=deb+"px";
			else document.getElementById(div).style.height=deb+"px";	
		}
		
	else if( deb-10>fin) deb-=10;
	else {
		deb=fin;
		if(direction == "w") document.getElementById(div).style.width=deb+"px";
		else document.getElementById(div).style.height=deb+"px";	
	}
	
	if(deb!=fin) setTimeout("retaille('" + div + "', '" + direction + "', " + deb + ", " + fin + ")", 30); 

	


}

function inverse(div1, div2){

	deplace(div1, 'h', document.getElementById(div1).offsetLeft, document.getElementById(div2).offsetLeft); 
	deplace(div1, 'v', document.getElementById(div1).offsetTop, document.getElementById(div2).offsetTop); 

	deplace(div2, 'h', document.getElementById(div2).offsetLeft, document.getElementById(div1).offsetLeft); 
	deplace(div2, 'v', document.getElementById(div2).offsetTop, document.getElementById(div1).offsetTop); 


}


function deplace(div, direction, deb, fin){

	if(direction == "h") document.getElementById(div).style.left=deb+"px";
	else document.getElementById(div).style.top=deb+"px";
    document.getElementById(div).style.display="block";
	
	if(deb<fin) 
		if(deb+12<fin) deb+=12;
		else {
			deb=fin;
			if(direction == "h") document.getElementById(div).style.left=deb+"px";
			else document.getElementById(div).style.top=deb+"px";
		}
	else if( deb-12>fin) deb-=12;
	else {
		deb=fin;
		if(direction == "h") document.getElementById(div).style.left=deb+"px";
		else document.getElementById(div).style.top=deb+"px";
	
	}
	if(deb!=fin) setTimeout("deplace('" + div + "', '" + direction + "', " + deb + ", " + fin + ")", 1); 

	


}


function apparait(div,opac){

	if(! opac) opac=0;
	opac = parseInt(opac);
	
	// Pour IE
	document.getElementById(div).style.filter ="Alpha(Opacity=" + opac*10 + ")";

	document.getElementById(div).style.opacity=  opac/10;
    document.getElementById(div).style.display="block";

	if(opac<10) {  
		opac+=1;
		setTimeout("apparait('" + div + "', '" + opac + "')", 60);
	}
	
}

	
function dragopac(div){
	 opacite(div, 50);
}

function dragplein(div){
	opacite( div, 100);
}

function eteindre(opac){
	var i;
	var listdiv;
	listdiv = document.getElementsByTagName("div");

	for(i=0; i<listdiv.length; i++)
		opacite(listdiv[i].id, opac)
	 
}

function Browser() {

  var ua, s, i;

  this.isIE    = false;
  this.isNS    = false;
  this.version = null;

  ua = navigator.userAgent;

  s = "MSIE";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isIE = true;
    this.version = parseFloat(ua.substr(i + s.length));
    return;
  }

  s = "Netscape6/";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isNS = true;
    this.version = parseFloat(ua.substr(i + s.length));
    return;
  }

  // Treat any other "Gecko" browser as NS 6.1.

  s = "Gecko";
  if ((i = ua.indexOf(s)) >= 0) {
    this.isNS = true;
    this.version = 6.1;
    return;
  }
}

var browser = new Browser();

// Global object to hold drag information.

var dragObj = new Object();
dragObj.zIndex = 0;

function dragStart(event, id, x1, y1, x2, y2) {
  
  var el;
  var x, y;
  encours=id;	
  ymin = y1;
  xmin = x1;
  xmax = x2;
  ymax = y2;
  dragopac(id);  
  // If an element id was given, find it. Otherwise use the element being
  // clicked on.

  if (id)
    dragObj.elNode = document.getElementById(id);
  else {
    if (browser.isIE)
      dragObj.elNode = window.event.srcElement;
    if (browser.isNS)
      dragObj.elNode = event.target;

    // If this is a text node, use its parent element.

    if (dragObj.elNode.nodeType == 3)
      dragObj.elNode = dragObj.elNode.parentNode;
  }

  // Get cursor position with respect to the page.

  if (browser.isIE) {
    x = window.event.clientX + document.documentElement.scrollLeft
      + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop
      + document.body.scrollTop;
  }
  if (browser.isNS) {
    x = event.clientX + window.scrollX;
    y = event.clientY + window.scrollY;
  }

  // Save starting positions of cursor and element.

  dragObj.cursorStartX = x;
  dragObj.cursorStartY = y;
  dragObj.elStartLeft  = parseInt(dragObj.elNode.style.left, 10);
  dragObj.elStartTop   = parseInt(dragObj.elNode.style.top,  10);

  if (isNaN(dragObj.elStartLeft)) dragObj.elStartLeft = 0;
  if (isNaN(dragObj.elStartTop))  dragObj.elStartTop  = 0;

  // Update element's z-index.

  dragObj.elNode.style.zIndex = ++dragObj.zIndex;

  // Capture mousemove and mouseup events on the page.

  if (browser.isIE) {
    document.attachEvent("onmousemove", dragGo);
    document.attachEvent("onmouseup",   dragStop);
    window.event.cancelBubble = true;
    window.event.returnValue = false;
  }
  if (browser.isNS) {
    document.addEventListener("mousemove", dragGo,   true);
    document.addEventListener("mouseup",   dragStop, true);
    event.preventDefault();
  }
}

function dragGo(event) {

  var x, y;
  // Get cursor position with respect to the page.

  if (browser.isIE) {
    x = window.event.clientX + document.documentElement.scrollLeft
      + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop
      + document.body.scrollTop;
  }
  if (browser.isNS) {
    x = event.clientX + window.scrollX;
    y = event.clientY + window.scrollY;
  }

  // Move drag element by the same amount the cursor has moved.
  
  dragObj.elNode.style.left = (dragObj.elStartLeft + x - dragObj.cursorStartX) + "px";
  dragObj.elNode.style.top  = (dragObj.elStartTop  + y - dragObj.cursorStartY) + "px";

  if (browser.isIE) {
    window.event.cancelBubble = true;
    window.event.returnValue = false;
  }
  if (browser.isNS)
    event.preventDefault();
}

function dragStop(event) {
  
if (browser.isIE) {
    x = window.event.clientX + document.documentElement.scrollLeft
      + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop
      + document.body.scrollTop;
  }
  if (browser.isNS) {
    x = event.clientX + window.scrollX;
    y = event.clientY + window.scrollY;
  }
  
  dragplein(encours);
  if(x>xmax || x<xmin)  document.getElementById(encours).style.left=dragObj.elStartLeft + "px";
  if(y>ymax || y<ymin)  document.getElementById(encours).style.top=dragObj.elStartTop + "px";
  // Stop capturing mousemove and mouseup events.

  if (browser.isIE) {
    document.detachEvent("onmousemove", dragGo);
    document.detachEvent("onmouseup",   dragStop);
  }
  if (browser.isNS) {
    document.removeEventListener("mousemove", dragGo,   true);
    document.removeEventListener("mouseup",   dragStop, true);
  }
}

