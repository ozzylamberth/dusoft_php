<?php
require("includes/session.php");
require("includes/config.php");
require("includes/database.php");
require("includes/funciones.php");
$cod_ded = $_REQUEST['cod_ded'];

open_database();

$sql = "SELECT NOM_ALUM, DEDICATORIA FROM DEDICATORIA WHERE COD_DED = ". $cod_ded;
		$result = execute_query($dbh, $sql);
		$row = fetch_object($result);
 ?>


<HTML>
<HEAD>
<title><< Mensaje de <?=$row->NOM_ALUM?> >></title>
    <SCRIPT LANGUAGE="JavaScript">

<!-- Este y otros mucho scripts puedes encontrarlos en -->
<!-- MundoJavascript.com -->

<!-- Begin
//slider's width
var swidth=350

//slider's height
var sheight=72

//slider's speed
var sspeed=1

//messages: change to your own; use as many as you'd like; set up Hyperlinks to URLs as you normally do: <a target=... href="... URL ...">..message..</a>
var singletext=new Array()
singletext[0]='<div align="center"><font face=Arial size=3 color="white"><b><?=$row->DEDICATORIA?></b></div></FONT>'

if (singletext.length>1)
i=1
else
i=0
function start(){
if (document.all){
ieslider1.style.top=sheight
iemarquee(ieslider1)
}
else if (document.layers){
document.ns4slider.document.ns4slider1.top=sheight
document.ns4slider.document.ns4slider1.visibility='show'
ns4marquee(document.ns4slider.document.ns4slider1)
}
else if (document.getElementById&&!document.all){
document.getElementById('ns6slider1').style.top=sheight
ns6marquee(document.getElementById('ns6slider1'))
}
}
function iemarquee(whichdiv){
iediv=eval(whichdiv)
if (iediv.style.pixelTop>0&&iediv.style.pixelTop<=sspeed){
iediv.style.pixelTop=0
setTimeout("iemarquee(iediv)",100)
}
if (iediv.style.pixelTop>=sheight*-1){
iediv.style.pixelTop-=sspeed
setTimeout("iemarquee(iediv)",100)
}
else{
iediv.style.pixelTop=sheight
iediv.innerHTML=singletext[i]
if (i==singletext.length-1)
i=0
else
i++
}
}
function ns4marquee(whichlayer){
ns4layer=eval(whichlayer)
if (ns4layer.top>0&&ns4layer.top<=sspeed){
ns4layer.top=0
setTimeout("ns4marquee(ns4layer)",100)
}
if (ns4layer.top>=sheight*-1){
ns4layer.top-=sspeed
setTimeout("ns4marquee(ns4layer)",100)
}
else{
ns4layer.top=sheight
ns4layer.document.write(singletext[i])
ns4layer.document.close()
if (i==singletext.length-1)
i=0
else
i++
}
}
function ns6marquee(whichdiv){
ns6div=eval(whichdiv)
if (parseInt(ns6div.style.top)>0&&parseInt(ns6div.style.top)<=sspeed){
ns6div.style.top=0
setTimeout("ns6marquee(ns6div)",100)
}
if (parseInt(ns6div.style.top)>=sheight*-1){
ns6div.style.top=parseInt(ns6div.style.top)-sspeed
setTimeout("ns6marquee(ns6div)",100)
}
else{
ns6div.style.top=sheight
ns6div.innerHTML=singletext[i]
if (i==singletext.length-1)
i=0
else
i++
}
}
//  End -->
</script>
</HEAD>

<BODY>
<BODY onLoad="start()">
 <div align="center">
<span style="borderWidth:1; borderColor:red; width:350; height:72; background:navy">
<ilayer id="ns4slider" width="&{swidth};" height="&{sheight};">
<layer id="ns4slider1" height="&{sheight};" onMouseOver="sspeed=0;" onMouseOut="sspeed=5">
<script language="JavaScript">
if (document.layers)
document.write(singletext[0])
</script>
</layer></ilayer>
<script language="JavaScript">
if (document.all){
document.writeln('<div style="position:relative;overflow:hidden;width:'+swidth+';height:'+sheight+';clip:rect(0 '+swidth+' '+sheight+' 0);border:1 solid red;" onmouseover="sspeed=0;" onmouseout="sspeed=1">')
document.writeln('<div id="ieslider1" style="position:relative;width:'+swidth+';">')
document.write(singletext[0])
document.writeln('</div></div>')
}
if(document.getElementById&&!document.all){
document.writeln('<div style="position:relative;overflow:hidden;width:'+swidth+';height:'+sheight+';clip:rect(0 '+swidth+' '+sheight+' 0);border:1px solid red;" onmouseover="sspeed=0;" onmouseout="sspeed=1">')
document.writeln('<div id="ns6slider1" style="position:relative;width:'+swidth+';">')
document.write(singletext[0])
document.writeln('</div></div>')
}
</script></span>
</div>
<form>
<p>
<input type="button"
       value="Cerrar esta ventana"
       onclick="window.close();">
</p>
</form>
 </BODY>
</HTML>
