// InsDestino2.php  09/12/2003
// --------------------------------------------------------------------------------------//
// eHospital v 0.1                                                                       //
// Copyright (C) 2003 InterSoftware Ltda.                                               //
// Emai: intersof@telesat.com.co                                                       //
// -----------------------------------------------------------------------------------//
// Autor: Jairo Duvan Diaz Martinez, Darling Liliana Dorado M, Lorena Aragón                     //
// Proposito del Archivo: realizar la busqueda de los paises de origen de los       //
// pacientes,y permite adicionar departamentos y municipos.                        //
//                                                                                //
// ------------------------------------------------------------------------------//
var f;
var v;
var z;

function abrirVentana(nombre, url, ancho, altura, x, frm, n)
{
	f=frm;
	z=n;

	var str = "width=430,height=300,resizable=yes,status=no,scrollbars=yes,top=200,left=200";

	if(z==1)
	{
		var p = frm.pais.value;
		var d = frm.dpto.value;
		var c = frm.mpio.value;
    var url2 = url+"?pais="+p+"&dpto="+d+"&mpio="+c+"&spia="+z;
	}
	else
	{
		var p = frm.paisE.value;
		var d = frm.dptoE.value;
		var c = frm.mpioE.value;
		var url2 = url+"?paisE="+p+"&dptoE="+d+"&mpioE="+c+"&spia="+z;
	}

	var rem = window.open(url2, nombre, str);

	if (rem != null)
	{
		if (rem.opener == null)
		{
			rem.opener = self;
		}
	}
}

function cambio(p)
{
	window.location='selector.php?pais='+p;
}

function cambioDpto(frm,n)
{
	var p = frm.pais.value;
	var d = frm.dpto.value;
	window.location="selector.php?dpto="+d+"&pais="+p;
}

function cambioMpio(frm,n)
{
	var p = frm.pais.value;
	var d = frm.dpto.value;
	var c = frm.mpio.value;
	window.location="selector.php?mpio="+c+"&dpto="+d+"&pais="+p;
}

function cambioComuna(frm,n)
{
	var p = frm.pais.value;
	var d = frm.dpto.value;
	var c = frm.mpio.value;
	var b = frm.comuna.value;
	window.location="selector.php?comuna="+b+"&mpio="+c+"&dpto="+d+"&pais="+p;
}

function copiarValor(pais,dpto,ciudad,comuna,barrio,frm,spia)
{
	var p=frm.pais.options[frm.pais.options.selectedIndex].value;
	var d=frm.dpto.options[frm.dpto.options.selectedIndex].value;
	var c=frm.mpio.options[frm.mpio.options.selectedIndex].value;
	if(frm.comuna.value!=0)
	{  var x=frm.comuna.options[frm.comuna.options.selectedIndex].value;  }
	else
	{  var x=0;  }

	if(frm.barrio.value!=0)
	{  var b=frm.barrio.options[frm.barrio.options.selectedIndex].value;  }
	else
	{  var b=0;  }

	if(frm.boton.value==1)
	{
		if(p!=0 && d!=0 && c!=0)
		{
			window.opener.document.forma.npais.value = frm.pais.options[frm.pais.options.selectedIndex].text;
			window.opener.document.forma.pais.value = p;
			window.opener.document.forma.ndpto.value = frm.dpto.options[frm.dpto.options.selectedIndex].text;
			window.opener.document.forma.dpto.value = d;
			window.opener.document.forma.nmpio.value = frm.mpio.options[frm.mpio.options.selectedIndex].text;
			window.opener.document.forma.mpio.value = c;
			if(x!=0)
			{
				window.opener.document.forma.ncomuna.value = frm.comuna.options[frm.comuna.options.selectedIndex].text;
				window.opener.document.forma.comuna.value = x;
			}
			if(b!=0)
			{
				z=b.split(',');
				window.opener.document.forma.ncomuna.value = z[2];
				window.opener.document.forma.comuna.value = z[1];
				window.opener.document.forma.nbarrio.value = frm.barrio.options[frm.barrio.options.selectedIndex].text;
				window.opener.document.forma.barrio.value = z[0];
				window.opener.document.forma.estrato.value = z[3];
			}
			close();
		}
	}
	else
	{
		if(p!=0 && d!=0 && c!=0)
		{
			window.opener.document.forma.npaisE.value = frm.pais.options[frm.pais.options.selectedIndex].text;
			window.opener.document.forma.paisE.value = p;
			window.opener.document.forma.ndptoE.value = frm.dpto.options[frm.dpto.options.selectedIndex].text;
			window.opener.document.forma.dptoE.value = d;
			window.opener.document.forma.nmpioE.value = frm.mpio.options[frm.mpio.options.selectedIndex].text;
			window.opener.document.forma.mpioE.value = c;
			window.opener.document.forma.ncomunaE.value = frm.comuna.options[frm.comuna.options.selectedIndex].text;
			window.opener.document.forma.comunaE.value = x;
			window.opener.document.forma.nbarrioE.value = frm.barrio.options[frm.barrio.options.selectedIndex].text;
			window.opener.document.forma.barrioE.value = b;
			close();
		}
	}
}

function EditarDpto(pais)
{
// window.location="selector.php?pais="+pais;
}

function EditarMpio(pais,dpto)
{
//var d="selector.php?pais"+pais+"&dpto="+dpto;
//alert(d);
	window.location="selector.php?pais="+pais+"&dpto="+dpto;
}

