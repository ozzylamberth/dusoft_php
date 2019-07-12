
/*
* selectortarifario2.js  13/04/2004 depende de selectorplan2.php
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* Proposito del Archivo: realizar la busqueda de los planes
* y mostrar la infromación del contrato para guardar uno nuevo
*/

/* function abrirVentana10(nombre, url, frm)
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
}*/

function cambio(frm)
{
	var t1 = frm.tarifario1.value;
	var em = frm.empresacon.value;
	var tp = frm.tipoplacon.value;
	var es = frm.estadocont.value;
	window.location='selectorplan2.php?tarifario1='+t1+"&empresacon="+em+"&tipoplacon="+tp+"&estadocont="+es;
}

function copiarValor(frm)
{
	var t1=frm.tarifario1.options[frm.tarifario1.options.selectedIndex].value;
	var t2=frm.plan.value;
	var v1=frm.descr2ctra.value;
	var v2=frm.contactoctra.value;
	var v3=frm.feinictra.value;
	var v4=frm.fefinctra.value;
	var v5=frm.tipoTerceroId.value;
	var v6=frm.codigo.value;
	var v7=frm.nombre.value;
	var v8=frm.paragracar.value;
	var v9=frm.paragramed.value;
	var v0=frm.tipoparimd.value;
	if(t1!=-1)
	{
		window.opener.document.contratacion.tarifario1.value = t1;
		window.opener.document.contratacion.tarifario2.value = t2;
		window.opener.document.contratacion.descr2ctra.value = v1;
		window.opener.document.contratacion.contactoctra.value = v2;
		window.opener.document.contratacion.feinictra.value = v3;
		window.opener.document.contratacion.fefinctra.value = v4;
		window.opener.document.contratacion.tipoTerceroId.value = v5;
		window.opener.document.contratacion.codigo.value = v6;
		window.opener.document.contratacion.nombre.value = v7;
		window.opener.document.contratacion.paragracar.value = v8;
		window.opener.document.contratacion.paragramed.value = v9;
		window.opener.document.contratacion.tipoparimd.value = v0;
		close();
	}
}
