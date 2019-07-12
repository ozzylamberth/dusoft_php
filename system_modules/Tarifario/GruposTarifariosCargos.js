
/*
* ClasificacionTarifarios.php  20/07/2004
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @author Lorena Aragón
* @author Jairo Duvan Diaz Martinez
* @author Darling Liliana Dorado
* @version 1.0
* @package SIIS
* Proposito del Archivo: realizar la busqueda de la clasificación de los Grupos y Subgrupos Tarifarios
*/

function cambioGrupo(p,frm,emp)
{
	var ban=frm.bandera.value;
	window.location='ClasificacionTarifarios.php?grupotarif='+p+"&bandera="+ban+"&Empresa="+emp;
}

function ValoresSeleccion(grupotarif,subgrtarif,frm,emp)
{
	var p=frm.grupotarif.options[frm.grupotarif.options.selectedIndex].value;
	var c=frm.subgrtarif.options[frm.subgrtarif.options.selectedIndex].value;
	if(p!=-1 && c!=-1)
	{
		window.opener.document.forma.nomgrupota.value = frm.grupotarif.options[frm.grupotarif.options.selectedIndex].text;
		window.opener.document.forma.grupotarif.value = p;
		window.opener.document.forma.nomsubgrta.value = frm.subgrtarif.options[frm.subgrtarif.options.selectedIndex].text;
		window.opener.document.forma.subgrtarif.value = c;
		close();
	}
}
