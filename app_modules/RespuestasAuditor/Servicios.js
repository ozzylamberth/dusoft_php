
/*
* ServiciosDepartamentos.php  21/08/2004
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* Proposito del Archivo: realizar la busqueda de los departamentos según el servicio elegido
*/

function cambioGrupo(p,frm)
{
	window.location='buscador.php?centro='+p;
}

function cambioGrupo2(j,frm,p)
{	
	window.location='buscador.php?centro='+p+'&unidad='+j;
}

function ValoresSeleccion(centro,unidad,departam,frm,emp)
{
	var p=frm.centro.options[frm.centro.options.selectedIndex].value;
     var j=frm.unidad.options[frm.unidad.options.selectedIndex].value;
	var c=frm.departam.options[frm.departam.options.selectedIndex].value;
	
     if(p == -1)
	{ window.opener.document.data.centroutilidad.value = ''; }
     else
     { 
          window.opener.document.data.centroutilidad.value = frm.centro.options[frm.centro.options.selectedIndex].text;
          window.opener.document.data.centroU.value = p;
	}
     if(j == -1)
     { window.opener.document.data.unidadfunc.value = ''; }
     else
     {
          window.opener.document.data.unidadfunc.value = frm.unidad.options[frm.unidad.options.selectedIndex].text;
          window.opener.document.data.unidadF.value = j;
     }
     if(c == -1)
     { window.opener.document.data.departamento.value = ''; }
     else
     {
          window.opener.document.data.departamento.value = frm.departam.options[frm.departam.options.selectedIndex].text;
          window.opener.document.data.DptoSel.value = c;
     }	
     close();
	
}
