
/*
* ServiciosDepartamentos.php  21/08/2004
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* Proposito del Archivo: realizar la busqueda de los departamentos según el servicio elegido
*/

function cambioGrupo(p,frm)
{
	window.location='ServiciosDepartamentos.php?servicio='+p;
}

function ValoresSeleccion(servicio,departam,frm,emp)
{
	var p=frm.servicio.options[frm.servicio.options.selectedIndex].value;
	var c=frm.departam.options[frm.departam.options.selectedIndex].value;
	if(p!=-1 && c!=-1)
	{
		window.opener.document.forma.nombserv.value = frm.servicio.options[frm.servicio.options.selectedIndex].text;
		window.opener.document.forma.servicio.value = p;
		window.opener.document.forma.nombdepa.value = frm.departam.options[frm.departam.options.selectedIndex].text;
		window.opener.document.forma.departam.value = c;
		close();
	}
}
