<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript" type="text/javascript">
// MENU CONTEXTUAL
// by Iv?n Nieto
// Version 1.1 (01/11/2005)
//
// Este script y otros muchos pueden
// descarse on-line de forma gratuita
// en El C?digo: www.elcodigo.com

/* DEFINICION DE LOS MENUS: MODIFICA LOS DATOS DE LOS ARRAYS PARA CREAR TUS PROPIOS MENUS */
/* lista_menus es la lista con los nombres de los menus */
var lista_menus = new Array (
"Menu1",		//menu numero 1
"Menu2",		//menu numero 2
"Menu3"			//menu numero 3
)

/* Menu1: este menu se escribe con EscribeMenu( "Menu1", 1, datos_menu1 );
	  este menu se abre con javascript:LanzaMenu('Menu1', 1);
	"Menu1" es el nombre del menu, utilizado para referirse al menu en todo el script.
	1 es el indice del elemento del array lista_menus que contiene el nombre anterior.
	datos_menu1 es el nombre del array con los datos del menu.
*/
var datos_menu1 = new Array (
/*URL								Texto del menu*/
"index.php", 					"Inicio        ",
"javascript:LanzaMenu('Menu2', 1);", 				"Taller      > ",
"http://www.elcodigo.com/tutoriales/tutoriales.html", 		"Tutoriales    "	//OJO, el ultimo no lleva coma
)

/* Menu2: este menu se escribe con EscribeMenu( "Menu2", 2, datos_menu2 ); 
          este menu se abre con javascript:LanzaMenu('Menu2', 1);*/
var datos_menu2 = new Array (
"http://www.elcodigo.com/taller/javascript/indices.html",	"JavaScript         ",
"http://www.elcodigo.com/taller/dhtml/indices.html",		"DHTML              ",
"javascript:LanzaMenu('Menu3', 1);",				"Otros lenguajes  > "	//OJO, el ultimo no lleva coma
)

/* Menu3: este menu se escribe con EscribeMenu( "Menu3", 3, datos_menu3 ); 
          este menu se abre con javascript:LanzaMenu('Menu3', 1);*/
var datos_menu3 = new Array (
"http://www.elcodigo.com/taller/perl/indices.html", 		"PERL        ",
"http://www.elcodigo.com/taller/html/indices.html", 		"CSS y HTML  "		//OJO, el ultimo no lleva coma
)


/* NO HACE FALTA CAMBIAR NADA A PARTIR DE AQUI */
/* Variables */
var xpos = 0
var ypos = 0
var TemporizadorDestino = null
var origen = -1
var destino = -1

/*
Cuando se hace click en cualquier parte, ClickRaton() registra las coordenadas x e y, 
y las almacena en xpos e ypos. Si se pulso sobre un enlace, se creara un menu.
La funcion PosicionaMenu() utiliza xpos e ypos para posicionarlo.
*/
function ClickRaton(e){
	/*La gestion de eventos con IE4 e IE5 utiliza el objeto window.event, que no forma
	parte de DOM2. IE5 soporta getElementById, pero sigue usando este objeto para la
	gestion de eventos, por lo que hay que tratarle de forma exclusiva */
	if (!e) var e = window.event
	
	if (e.pageX || e.pageY) {						//N6
		xpos = e.pageX + 'px'
		ypos = e.pageY + 'px'
	} else if (e.clientX || e.clientY) {					//resto
		xpos = e.clientX + document.body.scrollLeft + 'px'
		ypos = e.clientY + document.body.scrollTop + 'px'
	}
	
	return true
}

/*
Mueve el menu a las coordenadas actuales donde esta el puntero del raton, registradas 
por ClickRaton(). PosicionaMenu() es invocada dentro de LanzaMenu().
*/
function PosicionaMenu( nombre_menu ) {
	if (document.getElementById) {				//DOM2 browsers
		document.getElementById(nombre_menu).style.left = xpos
		document.getElementById(nombre_menu).style.top = ypos
	} else if (document.all) {				//IE4+
		document.all[nombre_menu].style.pixelLeft = xpos
		document.all[nombre_menu].style.pixelTop = ypos
	}
}

/*
LanzaMenu() muestra o esconde un menu. nombre_menu especifica que
menu debe ser mostrado o escondido. El argumento on es boolean. Si vale 
1 se muestra el menu. Si vale 0 se oculta.
*/
function LanzaMenu(nombre_menu, on) {

	PosicionaMenu(nombre_menu) 	//Posiciona el menu a xos e ypos

	if (on){ 							//Mostrar menu
		if (document.getElementById) {				//DOM2 browsers
			document.getElementById(nombre_menu).style.visibility = "visible"
		} else if (document.all) {				//IE4+
			document.all[nombre_menu].style.visibility = "visible"
		}

	} else {							//Ocultar menu
		if (document.getElementById) {				//DOM2 browsers
			document.getElementById(nombre_menu).style.visibility = "hidden"
		} else if (document.all) {				//IE4+
			document.all[nombre_menu].style.visibility = "hidden"
		}
	}
}

/*MarcaOrigen() registra el menu desde el cual se ha movido el puntero del raton a otro menu.
Se invoca desde onMouseOut en el elemento div*/
function MarcaOrigen( menu ) {
	if (TemporizadorDestino)
		clearTimeout(TemporizadorDestino)

	origen = menu
	destino = -1
	TemporizadorDestino = setTimeout('CompruebaDestino()', 250)

}

/*MarcaDestino() registra el menu al cual se ha movido el puntero del raton desde otro meno.
Se invoca con onMouseOver en el elemento div*/
function MarcaDestino( menu ) {
	destino = menu
}

/*Esta funcion establece la logica de ocultacion de menus, en base al origen/destino del
movimiento del raton*/
function CompruebaDestino( menu ) {
	if ( destino == -1 ) {
		Ocultar( 0 )			//Oculta todos
	} else if ( destino < origen ) {
		Ocultar( destino )		//Oculta hijos de destino
	} else if ( destino == origen ) {
		Ocultar( destino )		//Oculta hijos de destino
	}
}

/*Ocultar() se encarga de hacer no visible uno o varios menus*/
function Ocultar( menuID ) {
	//Recorre la lista de menus y los oculta
	for (contador = menuID; contador < lista_menus.length; contador++) {
		eval("LanzaMenu('" + lista_menus[contador] + "', 0)")
	}
}

/*EscribeMenu() crea el codigo HTML para la capa del menu. Los parametros son:
nombre_menu, el string que da nombre al menu.
numero_menu, el identificador del menu (de acuerdo al orden en el array lista_menus).
datos_menu, el nombre del array con los datos del menu.*/
function EscribeMenu( nombre_menu, numero_menu, datos_menu ) {
	var espacio = / /gi;
	var mayorque = />/gi;
	var literal;
	var cadena_menu = '<div class="SubMenu" id="' + nombre_menu + '" onMouseOut="MarcaOrigen(' + numero_menu + ');" OnMouseOver="MarcaDestino(' + numero_menu + ');"><table class="TabMenu">\n'
	for( contador = 0; contador < datos_menu.length; contador = contador + 2 ) {
		literal = new String( datos_menu[contador+1] )
		datos_menu[contador+1] = literal.replace(espacio, " ")
		literal = new String( datos_menu[contador+1] )
		datos_menu[contador+1] = literal.replace(mayorque, ">")
		cadena_menu += '<tr><td><a class="MenuItem" href="' + datos_menu[contador] + '">' + datos_menu[contador+1] + '</a></td></tr>\n'
	}
	cadena_menu += '</table></div>\n'
	
	document.write(cadena_menu)
}

/*
Comienza a capturar los eventos de pulsacion de los botones del raton
una vez que se ha cargado la pagina. Cuando se haga click en cualquier parte del
documento el navegador llamara al gestor asociado al evento: ClickRaton().
*/
document.onclick = ClickRaton



</script>

<style TYPE="text/css">

/*
MENU CONTEXTUAL
by Iv?n Nieto
Version 1.1 (01/11/2005)

Este script y otros muchos pueden
descarse on-line de forma gratuita
en El C?digo: www.elcodigo.com
*/

.TabMenu {
	padding: 0px;
 	background-color: #D4D0C8;
 	vertical-align: top;
 	border-style: solid;
 	border-width: 1px;
 	border-bottom-color: #000000;
 	border-right-color: #000000;
 	border-left-color: #808080;
 	border-top-color: #808080;
 	text-align: left;
 }

.SubMenu {
	position: absolute;
	top: 0px;
	left: 0px;
	z-index: 1;
	visibility: hidden;
}

a.MenuItem {font-size: 0.8em; padding: 2px; font-family: Courier, Arial, Serif; text-decoration: none;}
a.MenuItem:link {color: #000000; }
a.MenuItem:hover {color: #FFFFFF; background: #0A246A;}
a.MenuItem:visited {color: #868686;}



</style>

</head>

<body>
<p>El siguiente enlace abre un ejemplo del men? contextual, que tiene 2 submen?s.</p>

<!-- Crea las capas de los diferentes menus -->
<script type="text/javascript" language="javascript">
EscribeMenu( "Menu1", 1, datos_menu1 );
EscribeMenu( "Menu2", 2, datos_menu2 );
EscribeMenu( "Menu3", 3, datos_menu3 );
</script>

<!-- Crea el enalce al menu -->
<p><a href="javascript:LanzaMenu('Menu1', 1);">Menu del Usuario</a></p>

</body>
</html>




</body>
</html>
