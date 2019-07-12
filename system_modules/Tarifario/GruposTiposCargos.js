
/*
* gruposclasesysubclases.php  13/04/2004
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @author Lorena Aragón
* @author Jairo Duvan Diaz Martinez
* @author Darling Liliana Dorado
* @version 1.0
* @package SIIS
* Proposito del Archivo: realizar la busqueda de la clasificación
* de los grupos, clases y subclases de los productos
*/

function cambioGrupo(p,frm,emp)
{
	var ban=frm.bandera.value;
	window.location='ClasificacionGrupos.php?grupo='+p+"&bandera="+ban+"&Empresa="+emp;
}

function ValoresSeleccion(grupo,clasePr,frm,emp)
{
	var p=frm.grupo.options[frm.grupo.options.selectedIndex].value;
	var c=frm.clasePr.options[frm.clasePr.options.selectedIndex].value;
	if(p!=-1 && c!=-1)
	{
		window.opener.document.forma.NomGrupo.value = frm.grupo.options[frm.grupo.options.selectedIndex].text;
		window.opener.document.forma.grupo.value = p;
		window.opener.document.forma.NomClase.value = frm.clasePr.options[frm.clasePr.options.selectedIndex].text;
		window.opener.document.forma.clasePr.value = c;
		close();
	}
}

function ParametrosBusqueda(grupo,clasePr,frm,emp)
{
	var p=frm.grupo.options[frm.grupo.options.selectedIndex].value;
	var c=frm.clasePr.options[frm.clasePr.options.selectedIndex].value;
	if(p!=-1)
	{
		window.opener.document.forma.NomGrupo.value = frm.grupo.options[frm.grupo.options.selectedIndex].text;
		window.opener.document.forma.grupo.value = p;
	}
	else
	{
		window.opener.document.forma.grupo.value = p;
		window.opener.document.forma.NomGrupo.value='-------';
	}
	if(c!=-1)
	{
		window.opener.document.forma.NomClase.value = frm.clasePr.options[frm.clasePr.options.selectedIndex].text;
		window.opener.document.forma.clasePr.value = c;
	}
	else
	{
		window.opener.document.forma.NomClase.value='-------';
		window.opener.document.forma.clasePr.value = c;
	}
	close();
}
