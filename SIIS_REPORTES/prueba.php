<html>
<head>
<title> barra de menu </title>
<!-- please see http://www.brainjar.com for terms of use. -->

<style type="text/css">

div.menubar,
div.menubar a.menubutton,
div.menu,
div.menu a.menuitem {
font-family: "ms sans serif", arial, sans-serif;
font-size: 8pt;
font-style: normal;
font-weight: normal;
color: #000000;
}

div.menubar {
background-color: #d0d0d0;
border: 2px solid;
border-color: #f0f0f0 #909090 #909090 #f0f0f0;
padding: 4px 2px 4px 2px;
text-align: left;
}

div.menubar a.menubutton {
background-color: transparent;
border: 1px solid #d0d0d0;
color: #000000;
cursor: default;
left: 0px;
margin: 1px;
padding: 2px 6px 2px 6px;
position: relative;
text-decoration: none;
top: 0px;
z-index: 100;
}

div.menubar a.menubutton:hover {
background-color: transparent;
border-color: #f0f0f0 #909090 #909090 #f0f0f0;
color: #000000;
}

div.menubar a.menubuttonactive,
div.menubar a.menubuttonactive:hover {
background-color: #a0a0a0;
border-color: #909090 #f0f0f0 #f0f0f0 #909090;
color: #ffffff;
left: 1px;
top: 1px;
}

div.menu {
background-color: #d0d0d0;
border: 2px solid;
border-color: #f0f0f0 #909090 #909090 #f0f0f0;
left: 0px;
padding: 0px 1px 1px 0px;
position: absolute;
top: 0px;
visibility: hidden;
z-index: 101;
}

div.menu a.menuitem {
color: #000000;
cursor: default;
display: block;
padding: 3px 1em;
text-decoration: none;
white-space: nowrap;
}

div.menu a.menuitem:hover, div.menu a.menuitemhighlight {
background-color: #000080;
color: #ffffff;
}

div.menu a.menuitem span.menuitemtext {}

div.menu a.menuitem span.menuitemarrow {
margin-right: -.75em;
}

div.menu div.menuitemsep {
border-top: 1px solid #909090;
border-bottom: 1px solid #f0f0f0;
margin: 4px 2px;
}

</style>

<script type="text/javascript">

function browser() {
var ua, s, i;
this.isie = false;
this.isns = false;
this.version = null;

ua = navigator.useragent;

s = "msie";
if ((i = ua.indexof(s)) >= 0) {
this.isie = true;
this.version = parsefloat(ua.substr(i + s.length));
return;
}

s = "netscape6/";
if ((i = ua.indexof(s)) >= 0) {
this.isns = true;
this.version = parsefloat(ua.substr(i + s.length));
return;
}

// treat any other "gecko" browser as ns 6.1.

s = "gecko";
if ((i = ua.indexof(s)) >= 0) {
this.isns = true;
this.version = 6.1;
return;
}
}

var browser = new browser();

//----------------------------------------------------------------------------
// code for handling the menu bar and active button.
//----------------------------------------------------------------------------

var activebutton = null;

// capture mouse clicks on the page so any active button can be
// deactivated.

if (browser.isie)
document.onmousedown = pagemousedown;
else
document.addeventlistener("mousedown", pagemousedown, true);

function pagemousedown(event) {
var el;
if (activebutton == null)
return;

if (browser.isie)
el = window.event.srcelement;
else
el = (event.target.tagname ? event.target : event.target.parentnode);

if (el == activebutton)
return;

if (getcontainerwith(el, "div", "menu") == null) {
resetbutton(activebutton);
activebutton = null;
}
}

function buttonclick(event, menuid) {
var button;

if (browser.isie)
button = window.event.srcelement;
else
button = event.currenttarget;

button.blur();

if (button.menu == null) {
button.menu = document.getelementbyid(menuid);
menuinit(button.menu);
}

if (activebutton != null)
resetbutton(activebutton);

if (button != activebutton) {
depressbutton(button);
activebutton = button;
}
else
activebutton = null;

return false;
}

function buttonmouseover(event, menuid) {
var button;

if (browser.isie)
button = window.event.srcelement;
else
button = event.currenttarget;

if (activebutton != null && activebutton != button)
buttonclick(event, menuid);
}

function depressbutton(button) {
var x, y;

button.classname += " menubuttonactive";

x = getpageoffsetleft(button);
y = getpageoffsettop(button) + button.offsetheight;

if (browser.isie) {
x += button.offsetparent.clientleft;
y += button.offsetparent.clienttop;
}

button.menu.style.left = x + "px";
button.menu.style.top = y + "px";
button.menu.style.visibility = "visible";
}

function resetbutton(button) {
removeclassname(button, "menubuttonactive");

if (button.menu != null) {
closesubmenu(button.menu);
button.menu.style.visibility = "hidden";
}
}


function menumouseover(event) {
var menu;
if (browser.isie)
menu = getcontainerwith(window.event.srcelement, "div", "menu");
else
menu = event.currenttarget;

if (menu.activeitem != null)
closesubmenu(menu);
}

function menuitemmouseover(event, menuid) {
var item, menu, x, y;
if (browser.isie)
item = getcontainerwith(window.event.srcelement, "a", "menuitem");
else
item = event.currenttarget;
menu = getcontainerwith(item, "div", "menu");

if (menu.activeitem != null)
closesubmenu(menu);
menu.activeitem = item;

item.classname += " menuitemhighlight";

if (item.submenu == null) {
item.submenu = document.getelementbyid(menuid);
menuinit(item.submenu);
}

x = getpageoffsetleft(item) + item.offsetwidth;
y = getpageoffsettop(item);

var maxx, maxy;

if (browser.isns) {
maxx = window.scrollx + window.innerwidth;
maxy = window.scrolly + window.innerheight;
}
if (browser.isie && browser.version < 6) {
maxx = document.body.scrollleft + document.body.clientwidth;
maxy = document.body.scrolltop + document.body.clientheight;
}
if (browser.isie && browser.version >= 6) {
maxx = document.documentelement.scrollleft + document.documentelement.clientwidth;
maxy = document.documentelement.scrolltop + document.documentelement.clientheight;
}
maxx -= item.submenu.offsetwidth;
maxy -= item.submenu.offsetheight;

if (x > maxx)
x = math.max(0, x - item.offsetwidth - item.submenu.offsetwidth
+ (menu.offsetwidth - item.offsetwidth));
y = math.max(0, math.min(y, maxy));

item.submenu.style.left = x + "px";
item.submenu.style.top = y + "px";
item.submenu.style.visibility = "visible";

if (browser.isie)
window.event.cancelbubble = true;
else
event.stoppropagation();
}

function closesubmenu(menu) {
if (menu == null || menu.activeitem == null)
return;

if (menu.activeitem.submenu != null) {
closesubmenu(menu.activeitem.submenu);
menu.activeitem.submenu.style.visibility = "hidden";
menu.activeitem.submenu = null;
}
removeclassname(menu.activeitem, "menuitemhighlight");
menu.activeitem = null;
}

function menuinit(menu) {
var itemlist, spanlist
var textel, arrowel;
var itemwidth;
var w, dw;
var i, j;

if (browser.isie) {
menu.style.lineheight = "2.5ex";
spanlist = menu.getelementsbytagname("span");
for (i = 0; i < spanlist.length; i++)
if (hasclassname(spanlist[i], "menuitemarrow")) {
spanlist[i].style.fontfamily = "webdings";
spanlist[i].firstchild.nodevalue = "4";
}
}

itemlist = menu.getelementsbytagname("a");
if (itemlist.length > 0)
itemwidth = itemlist[0].offsetwidth;
else
return;

for (i = 0; i < itemlist.length; i++) {
spanlist = itemlist[i].getelementsbytagname("span")
textel = null
arrowel = null;
for (j = 0; j < spanlist.length; j++) {
if (hasclassname(spanlist[j], "menuitemtext"))
textel = spanlist[j];
if (hasclassname(spanlist[j], "menuitemarrow"))
arrowel = spanlist[j];
}
if (textel != null && arrowel != null)
textel.style.paddingright = (itemwidth
- (textel.offsetwidth + arrowel.offsetwidth)) + "px";
}

if (browser.isie) {
w = itemlist[0].offsetwidth;
itemlist[0].style.width = w + "px";
dw = itemlist[0].offsetwidth - w;
w -= dw;
itemlist[0].style.width = w + "px";
}
}

function getcontainerwith(node, tagname, classname) {
while (node != null) {
if (node.tagname != null && node.tagname == tagname &&
hasclassname(node, classname))
return node;
node = node.parentnode;
}

return node;
}

function hasclassname(el, name) {
var i, list;
list = el.classname.split(" ");
for (i = 0; i < list.length; i++)
if (list[i] == name)
return true;

return false;
}

function removeclassname(el, name) {
var i, curlist, newlist;
if (el.classname == null)
return;
newlist = new array();
curlist = el.classname.split(" ");
for (i = 0; i < curlist.length; i++)
if (curlist[i] != name)
newlist.push(curlist[i]);
el.classname = newlist.join(" ");
}

function getpageoffsetleft(el) {
var x;
x = el.offsetleft;
if (el.offsetparent != null)
x += getpageoffsetleft(el.offsetparent);

return x;
}

function getpageoffsettop(el) {
var y;
y = el.offsettop;
if (el.offsetparent != null)
y += getpageoffsettop(el.offsetparent);

return y;
}

</script>
</head>
<body>


<!-- menu bar. -->
<div class="menubar" style="width:80%;">
<a class="menubutton" href="" onclick="return buttonclick(event, 'filemenu');" onmouseover="buttonmouseover(event, 'filemenu');">file</a>
<a class="menubutton" href="" onclick="return buttonclick(event, 'editmenu');" onmouseover="buttonmouseover(event, 'editmenu');">edit</a>
<a class="menubutton" href="" onclick="return buttonclick(event, 'viewmenu');" onmouseover="buttonmouseover(event, 'viewmenu');">view</a>
<a class="menubutton" href="" onclick="return buttonclick(event, 'toolsmenu');" onmouseover="buttonmouseover(event, 'toolsmenu');">tools</a>
<a class="menubutton" href="" onclick="return buttonclick(event, 'optionsmenu');" onmouseover="buttonmouseover(event, 'optionsmenu');">options</a>
<a class="menubutton" href="" onclick="return buttonclick(event, 'helpmenu');" onmouseover="buttonmouseover(event, 'helpmenu');">help</a>
</div>

<!-- main menus. -->

<div id="filemenu" class="menu" onmouseover="menumouseover(event)">
<a class="menuitem" href="blank.html">file menu item 1</a>
<a class="menuitem" href="" onclick="return false;" onmouseover="menuitemmouseover(event, 'filemenu2');"
><span class="menuitemtext">file menu item 2</span><span class="menuitemarrow">?</span></a>
<a class="menuitem" href="blank.html">file menu item 3</a>
<a class="menuitem" href="blank.html">file menu item 4</a>
<a class="menuitem" href="blank.html">file menu item 5</a>
<div class="menuitemsep"></div>
<a class="menuitem" href="blank.html">file menu item 6</a>
</div>

<div id="editmenu" class="menu" onmouseover="menumouseover(event)">
<a class="menuitem" href="blank.html">edit menu item 1</a>
<div class="menuitemsep"></div>
<a class="menuitem" href="blank.html">edit menu item 2</a>
<a class="menuitem" href="" onclick="return false;" onmouseover="menuitemmouseover(event, 'editmenu3');"
><span class="menuitemtext">edit menu item 3</span><span class="menuitemarrow">?</span></a>
<a class="menuitem" href="blank.html">edit menu item 4</a>
<div class="menuitemsep"></div>
<a class="menuitem" href="blank.html">edit menu item 5</a>
</div>

<div id="viewmenu" class="menu">
<a class="menuitem" href="blank.html">view menu item 1</a>
<a class="menuitem" href="blank.html">view menu item 2</a>
<a class="menuitem" href="blank.html">view menu item 3</a>
</div>

<div id="toolsmenu" class="menu" onmouseover="menumouseover(event)">
<a class="menuitem" href="" onclick="return false;" onmouseover="menuitemmouseover(event, 'toolsmenu1');"
><span class="menuitemtext">tools menu item 1</span><span class="menuitemarrow">?</span></a>
<a class="menuitem" href="blank.html">tools menu item 2</a>
<a class="menuitem" href="blank.html">tools menu item 3</a>
<div class="menuitemsep"></div>
<a class="menuitem" href="" onclick="return false;" onmouseover="menuitemmouseover(event, 'toolsmenu4');"
><span class="menuitemtext">tools menu item 4</span><span class="menuitemarrow">?</span></a>
<a class="menuitem" href="blank.html">tools menu item 5</a>
</div>

<div id="optionsmenu" class="menu">
<a class="menuitem" href="blank.html">options menu item 1</a>
<a class="menuitem" href="blank.html">options menu item 2</a>
<a class="menuitem" href="blank.html">options menu item 3</a>
</div>

<div id="helpmenu" class="menu">
<a class="menuitem" href="blank.html">help menu item 1</a>
<a class="menuitem" href="blank.html">help menu item 2</a>
<div class="menuitemsep"></div>
<a class="menuitem" href="blank.html">help menu item 3</a>
</div>

<!-- file sub menus. -->

<div id="filemenu2" class="menu">
<a class="menuitem" href="blank.html">file menu 2 item 1</a>
<a class="menuitem" href="blank.html">file menu 2 item 2</a>
</div>

<!-- edit sub menus. -->

<div id="editmenu3" class="menu" onmouseover="menumouseover(event)">
<a class="menuitem" href="blank.html">edit menu 3 item 1</a>
<a class="menuitem" href="blank.html">edit menu 3 item 2</a>
<div class="menuitemsep"></div>
<a class="menuitem" href="" onclick="return false;" onmouseover="menuitemmouseover(event, 'editmenu3_3');"
><span class="menuitemtext">edit menu 3 item 3</span><span class="menuitemarrow">?</span></a>
<a class="menuitem" href="blank.html">edit menu 3 item 4</a>
</div>

<div id="editmenu3_3" class="menu">
<a class="menuitem" href="blank.html">edit menu 3-3 item 1</a>
<a class="menuitem" href="blank.html">edit menu 3-3 item 2</a>
<a class="menuitem" href="blank.html">edit menu 3-3 item 3</a>
<div class="menuitemsep"></div>
<a class="menuitem" href="blank.html">edit menu 3-3 item 4</a>
</div>

<!-- tools sub menus. -->

<div id="toolsmenu1" class="menu">
<a class="menuitem" href="blank.html">tools menu 1 item 1</a>
<a class="menuitem" href="blank.html">tools menu 1 item 2</a>
<div class="menuitemsep"></div>
<a class="menuitem" href="blank.html">tools menu 1 item 3</a>
<a class="menuitem" href="blank.html">tools menu 1 item 4</a>
<div class="menuitemsep"></div>
<a class="menuitem" href="blank.html">tools menu 1 item 5</a>
</div>

<div id="toolsmenu4" class="menu" onmouseover="menumouseover(event)">
<a class="menuitem" href="blank.html">tools menu 4 item 1</a>
<a class="menuitem" href="blank.html">tools menu 4 item 2</a>
<a class="menuitem" href="blank.html" onclick="return false;" onmouseover="menuitemmouseover(event, 'toolsmenu4_3');"><span class="menuitemtext">tools menu 4 item 3</span><span class="menuitemarrow">?</span></a>
</div>

<div id="toolsmenu4_3" class="menu">
<a class="menuitem" href="blank.html">tools menu 4-3 item 1</a>
<a class="menuitem" href="blank.html">tools menu 4-3 item 2</a>
<a class="menuitem" href="blank.html">tools menu 4-3 item 3</a>
<a class="menuitem" href="blank.html">tools menu 4-3 item 4</a>
</div>

</body>
</html>
