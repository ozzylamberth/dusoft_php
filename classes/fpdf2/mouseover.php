Javascript: debes ubicar el siguiente script en la cabecera de la página (<head> ... </head>)



		$mostrar ="\n<script language='javascript'>\n";
		$mostrar.="var rem=\"\";\n";
		$mostrar.="  function abrirVentana(){\n";
		$mostrar.="    var nombre=\"\"\n";
		$mostrar.="    var url2=\"\"\n";
		$mostrar.="    var str=\"\"\n";
		$mostrar.="    var ALTO=screen.height\n";
		$mostrar.="    var ANCHO=screen.width\n";
		$mostrar.="    var nombre=\"buscador_General\";\n";
		$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
		$mostrar.="    var url2 ='$RUTA';\n";
		$mostrar.="    rem = window.open(url2, nombre, str)};\n";
		//$mostrar.="</script>\n";




$mostrar ="\n<script language='javascript'>\n";
$mostrar.="function mOvr(src,clrOver) {;\n";
$mostrar.=" if (!src.contains(event.fromElement)) {\n";
$mostrar.="src.style.cursor = 'hand';\n";
$mostrar.="src.bgColor = clrOver;\n";
$mostrar.="}\n";
$mostrar.="}\n";

$mostrar.="function mOut(src,clrIn) {\n";
$mostrar.="if (!src.contains(event.toElement)) {\n";
$mostrar.="src.style.cursor = 'default';\n";
$mostrar.="src.bgColor = clrIn;\n";
$mostrar.="}\n";
$mostrar.="}\n";
$mostrar.="function mClk(src) {\n";
$mostrar.="if(event.srcElement.tagName=='TD'){\n";
$mostrar.="src.children.tags('A')[0].click();\n";
$mostrar.="}\n";
$mostrar.="}\n";
$mostrar.="</script>\n";

Menú: estos menúes se realizan mediante una tabla y diferentes celdas, las cuales pueden ser filas o columnas, en este ejemplo veremos filas.
<table>
<tr>
<td onclick="mClk(this);" onmouseout="mOut(this,'#475B70');" onmouseover="mOvr(this,'#729233');" vAlign="center" width="171" style="border-bottom: 1px solid rgb(0,0,0); padding-left: 6; padding-top: 1; padding-bottom: 1" bgcolor="#475B70" height="12"><a style="COLOR: rgb(255,255,255); TEXT-DECORATION: none" href="link1.htm"><font face="Verdana" size="1">Artículos de JavaScript</font></a></td>
</tr>
<tr>
<td onclick="mClk(this);" onmouseout="mOut(this,'#475B70');" onmouseover="mOvr(this,'#729233');" vAlign="center" width="171" style="border-bottom: 1px solid rgb(0,0,0); padding-left: 6; padding-top: 1; padding-bottom: 1" bgcolor="#475B70" height="1"><a style="COLOR: rgb(255,255,255); TEXT-DECORATION: none" href="link2.htm"><font face="Verdana" size="1">Artículos de ASP</font></a></td>
</tr>
</table>