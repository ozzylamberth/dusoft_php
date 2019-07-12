
/*
* selectortarifario.js  13/04/2004 depende de selectorplan.php
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* Proposito del Archivo: realizar la busqueda de los planes
* y mostrar el plan tarifario del plan seleccionado
*/

function abrirVentana(nombre, url, frm)
{
	var str = "width=900,height=600,resizable=no,status=no,scrollbars=yes,top=200,left=200";
	var t1 = frm.tarifario1.value;
	var em = frm.empresacon.value;
	var tp = frm.tipoplacon.value;
	var es = frm.estadocont.value;

	var url2 = url+"?tarifario1="+t1+"&empresacon="+em+"&tipoplacon="+tp+"&estadocont="+es;
	var rem = window.open(url2, nombre, str);
	if (rem != null)
	{
		if (rem.opener == null)
		{
			rem.opener = self;
		}
	}
}

function cambio(frm)
{
	var t1 = frm.tarifario1.value;
	var em = frm.empresacon.value;
	var tp = frm.tipoplacon.value;
	var es = frm.estadocont.value;
	window.location='selectorplan.php?tarifario1='+t1+"&empresacon="+em+"&tipoplacon="+tp+"&estadocont="+es;
}

function copiarValor(frm)
{
	var t1=frm.tarifario1.options[frm.tarifario1.options.selectedIndex].value;
	var t2=frm.plan.value;
	if(t1!=-1)
	{
		window.opener.document.contratari.tarifario1.value = t1;
		window.opener.document.contratari.tarifario2.value = t2;
		close();
	}
}
