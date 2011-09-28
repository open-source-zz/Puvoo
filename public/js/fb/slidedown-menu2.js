/************************************************************************************************************
Slide down menu
Copyright (C) October 2005  DTHMLGoodies.com, Alf Magne Kalleland

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA

Dhtmlgoodies.com., hereby disclaims all copyright interest in this script
written by Alf Magne Kalleland.

Alf Magne Kalleland, 2010
Owner of DHTMLgoodies.com

************************************************************************************************************/
var expandFirstItemAutomatically = false;	// Expand first menu item automatically ?
var initMenuIdToExpand = false;	// Id of menu item that should be initially expanded. the id is defined in the <li> tag.
var expandMenuItemByUrl = true;	// Menu will automatically expand by url - i.e. if the href of the menu item is in the current location, it will expand
var initialMenuItemAlwaysExpanded = true;	// NOT IMPLEMENTED YET




var dhtmlgoodies_slmenuObj;
var divToScroll = false;
var ulToScroll = false;
var divCounter = 1;
var otherDivsToScroll = new Array();
var divToHide = false;
var parentDivToHide = new Array();
var ulToHide = false;
var offsetOpera = 0;
if(navigator.userAgent.indexOf('Opera')>=0)offsetOpera=1;
var slideMenuHeightOfCurrentBox = 0;
var objectsToExpand = new Array();
var initExpandIndex = 0;
var alwaysExpanedItems = new Array();

var dg_activeItem = null;

function popMenusToShow()
{
	var obj = divToScroll;
	var endArray = new Array();
	while(obj && obj.tagName!='BODY'){
		if(obj.tagName=='DIV' && obj.id.indexOf('slideDiv')>=0){
			var objFound = -1;
			for(var no=0;no<otherDivsToScroll.length;no++){
				if(otherDivsToScroll[no]==obj){
					objFound = no;
				}
			}
			if(objFound>=0){
				otherDivsToScroll.splice(objFound,1);
			}
		}
		obj = obj.parentNode;
	}
}

function showSubMenu(e,inputObj)
{

	if(this && this.tagName)inputObj = this.parentNode;
	if(inputObj && inputObj.tagName=='LI'){
		divToScroll = inputObj.getElementsByTagName('DIV')[0];
		for(var no=0;no<otherDivsToScroll.length;no++){
			if(otherDivsToScroll[no]==divToScroll)return;
		}
	}
	hidingInProcess = false;
	if(otherDivsToScroll.length>0){
		if(divToScroll){
			if(otherDivsToScroll.length>0){
				popMenusToShow();
			}
			if(otherDivsToScroll.length>0){
				autoHideMenus();
				hidingInProcess = true;
			}
		}
	}
	if(divToScroll && !hidingInProcess){
		divToScroll.style.display='';
		otherDivsToScroll.length = 0;
		otherDivToScroll = divToScroll.parentNode;
		otherDivsToScroll.push(divToScroll);
		while(otherDivToScroll && otherDivToScroll.tagName!='BODY'){
			if(otherDivToScroll.tagName=='DIV' && otherDivToScroll.id.indexOf('slideDiv')>=0){
				otherDivsToScroll.push(otherDivToScroll);

			}
			otherDivToScroll = otherDivToScroll.parentNode;
		}
		ulToScroll = divToScroll.getElementsByTagName('UL')[0];
		if(divToScroll.style.height.replace('px','')/1<=1)scrollDownSub();
	}

	if(e || inputObj) {

		if(dg_activeItem) {
			dg_activeItem.className = dg_activeItem.className.replace('dhtmlgoodies_activeItem','');
		}
		var aTags = inputObj.getElementsByTagName('A');
		if(aTags.length>0) {
			aTags[0].className = aTags[0].className + ' dhtmlgoodies_activeItem';
			dg_activeItem = aTags[0];
			if(aTags[0].href.indexOf('#') == -1 || aTags[0].href.indexOf('#') < aTags[0].href.length-1){
				return true;
			}
		}

	}

	return false;


}



function autoHideMenus()
{
	if(otherDivsToScroll.length>0){
		divToHide = otherDivsToScroll[otherDivsToScroll.length-1];
		parentDivToHide.length=0;
		var obj = divToHide.parentNode.parentNode.parentNode;
		while(obj && obj.tagName=='DIV'){
			if(obj.id.indexOf('slideDiv')>=0)parentDivToHide.push(obj);
			obj = obj.parentNode.parentNode.parentNode;
		}
		var tmpHeight = (divToHide.style.height.replace('px','')/1 - slideMenuHeightOfCurrentBox);
		if(tmpHeight<0)tmpHeight=0;
		if(slideMenuHeightOfCurrentBox)divToHide.style.height = tmpHeight  + 'px';
		ulToHide = divToHide.getElementsByTagName('UL')[0];
		slideMenuHeightOfCurrentBox = ulToHide.offsetHeight;
		scrollUpMenu();
	}else{
		slideMenuHeightOfCurrentBox = 0;
		showSubMenu();
	}
}


function scrollUpMenu()
{

	var height = divToHide.offsetHeight;
	height-=15;
	if(height<0)height=0;
	divToHide.style.height = height + 'px';

	for(var no=0;no<parentDivToHide.length;no++){
		parentDivToHide[no].style.height = parentDivToHide[no].getElementsByTagName('UL')[0].offsetHeight + 'px';
	}
	if(height>0){
		setTimeout('scrollUpMenu()',5);
	}else{
		divToHide.style.display='none';
		otherDivsToScroll.length = otherDivsToScroll.length-1;
		autoHideMenus();
	}
}

function scrollDownSub()
{
	if(divToScroll){
		var height = divToScroll.offsetHeight/1;
		var offsetMove =Math.min(15,(ulToScroll.offsetHeight - height));
		height = height +offsetMove ;
		divToScroll.style.height = height + 'px';

		for(var no=1;no<otherDivsToScroll.length;no++){
			var tmpHeight = otherDivsToScroll[no].offsetHeight/1 + offsetMove;
			otherDivsToScroll[no].style.height = tmpHeight + 'px';
		}
		if(height<ulToScroll.offsetHeight)setTimeout('scrollDownSub()',5); else {
			divToScroll = false;
			ulToScroll = false;
			if(objectsToExpand.length>0 && initExpandIndex<(objectsToExpand.length-1)){
				initExpandIndex++;

				showSubMenu(false,objectsToExpand[initExpandIndex]);
			}
		}
	}
}

function initSubItems(inputObj,currentDepth)
{
	divCounter++;
	var div = document.createElement('DIV');	// Creating new div
	div.style.overflow = 'hidden';
	div.style.position = 'relative';
	div.style.display='none';
	div.style.height = '1px';
	div.id = 'slideDiv' + divCounter;
	div.className = 'slideMenuDiv' + currentDepth;
	inputObj.parentNode.appendChild(div);	// Appending DIV as child element of <LI> that is parent of input <UL>
	div.appendChild(inputObj);	// Appending <UL> to the div
	var menuItem = inputObj.getElementsByTagName('LI')[0];
	while(menuItem){
		if(menuItem.tagName=='LI'){
			var aTag = menuItem.getElementsByTagName('A')[0];
			aTag.className='slMenuItem_depth'+currentDepth;
			var subUl = menuItem.getElementsByTagName('UL');
			if(subUl.length>0){
				initSubItems(subUl[0],currentDepth+1);
			}
			aTag.onclick = showSubMenu;
		}
		menuItem = menuItem.nextSibling;
	}
}

function initSlideDownMenu()
{
	dhtmlgoodies_slmenuObj = document.getElementById('dhtmlgoodies_slidedown_menu');
	dhtmlgoodies_slmenuObj.style.visibility='visible';
	var mainUl = dhtmlgoodies_slmenuObj.getElementsByTagName('UL')[0];
	var mainMenuItem = mainUl.getElementsByTagName('LI')[0];
	mainItemCounter = 1;
	while(mainMenuItem){
		if(mainMenuItem.tagName=='LI'){
			var aTag = mainMenuItem.getElementsByTagName('A')[0];
			aTag.className='slMenuItem_depth1';
			var subUl = mainMenuItem.getElementsByTagName('UL');
			if(subUl.length>0){
				mainMenuItem.id = 'mainMenuItem' + mainItemCounter;
				initSubItems(subUl[0],2);
				aTag.onclick = showSubMenu;
				mainItemCounter++;
			}
		}
		mainMenuItem = mainMenuItem.nextSibling;
	}	

	if(location.search.indexOf('mainMenuItemToSlide')>=0){
		var items = location.search.split('&');
		for(var no=0;no<items.length;no++){
			if(items[no].indexOf('mainMenuItemToSlide')>=0){
				values = items[no].split('=');
				showSubMenu(false,document.getElementById('mainMenuItem' + values[1]));
				initMenuIdToExpand = false;
			}
		}
	}else if(expandFirstItemAutomatically>0		){
		if(document.getElementById('mainMenuItem' + expandFirstItemAutomatically)){
			showSubMenu(false,document.getElementById('mainMenuItem' + expandFirstItemAutomatically));
			initMenuIdToExpand = false;
		}
	}


	if(expandMenuItemByUrl)
	{
		var aTags = dhtmlgoodies_slmenuObj.getElementsByTagName('A');
		var currentLocation = location.pathname;
		for(var no=0;no<aTags.length;no++){
			var hrefToCheckOn = aTags[no].href;
			if(hrefToCheckOn.indexOf(currentLocation)>=0 && hrefToCheckOn.indexOf('#')<hrefToCheckOn.length-1){
				initMenuIdToExpand = false;
				var obj = aTags[no].parentNode;
				while(obj && obj.id!='dhtmlgoodies_slidedown_menu'){
					if(obj.tagName=='LI'){
						var subUl = obj.getElementsByTagName('UL');
						if(initialMenuItemAlwaysExpanded)alwaysExpanedItems[obj.parentNode] = true;
						if(subUl.length>0){
							objectsToExpand.unshift(obj);
						}
					}
					obj = obj.parentNode;
				}
				showSubMenu(false,objectsToExpand[0]);
				break;
			}
		}
	}

	if(initMenuIdToExpand)
	{
		objectsToExpand = new Array();
		var obj = document.getElementById(initMenuIdToExpand)
		while(obj && obj.id!='dhtmlgoodies_slidedown_menu'){
			if(obj.tagName=='LI'){
				var subUl = obj.getElementsByTagName('UL');
				if(initialMenuItemAlwaysExpanded)alwaysExpanedItems[obj.parentNode] = true;
				if(subUl.length>0){
					objectsToExpand.unshift(obj);
				}
			}
			obj = obj.parentNode;
		}

		showSubMenu(false,objectsToExpand[0]);

	}

}

