

<HTML>
<HEAD><H2><IMG SRC="logo_clinica.bmp" width="150" height="100" ALT="" ></H2>
<style>
<!--


a{text-decoration:none}
.look{font:normal 8pt sans-serif,Arial;color:#ffffff}
.look2{font:normal 11pt sans-serif,Arial;color:#ffffff}
.folding{cursor:hand;text-decoration: underline}
a:hover{color:yellow;text-decoration: underline}
//-->
</style>

<script language="JavaScript">
<!--
img1=new Image()
img1.src="Llin.gif"
img2=new Image()
img2.src="Llin.gif"
ns6_index=0

function change(e){

if(!document.all&&!document.getElementById)
return

if (!document.all&&document.getElementById)
ns6_index=1

var source=document.getElementById&&!document.all? e.target:event.srcElement
if (source.className=="folding"){
var source2=document.getElementById&&!document.all? source.parentNode.childNodes:source.parentElement.all
if (source2[2+ns6_index].style.display=="none"){
source2[0].src="flecha.jpg"
source2[2+ns6_index].style.display=''
}
else{
source2[0].src="flecha.jpg"
source2[2+ns6_index].style.display="none"
}
}
}
document.onclick=change
//-->
</script>
<TITLE>Menu de Usuario</TITLE>
</HEAD>
<BODY bgcolor="STEELBLUE" vlink="#ffffff" link="#fffff" alink="#ffffff" marginheight="20" topmargin="10">
<br>

<div class="look"><img src="flecha.jpg" width="24" height="21" align=middle class="folding"><a class="folding"> REPORTES</a>
<ul class="look1" style="list-style-image:;display:none">
<div class="look1"><img  width="0.1" height="0.1" align=left class="folding"><a class="folding"> CIRUGIA</a>
<ul class="look1" style="list-style-image:;display:none">
<li><a href="REP_CX/index.php" TARGET="derecha"> Estadistica</font></a></li>
</div>
<div class="look1"><img  width="0.1" height="0.1" align=left class="folding"><a class="folding"> CENSO</a>
<ul class="look1" style="list-style-image:;display:none">
<li><a href="REP_CENSO/index.php" TARGET="derecha"> Ingresos - Egresos - Hospitalizados</font></a></li>
</div>
<div class="look1"><img  width="0.1" height="0.1" align=left class="folding"><a class="folding"> BIOESTADISTICA</a>
<ul class="look1" style="list-style-image:;display:none">
<li><a href="REP_BIOESTADISTICA/index.php" TARGET="derecha"> Comparacion 72 hrs</font></a></li>
</div>


 </div>


