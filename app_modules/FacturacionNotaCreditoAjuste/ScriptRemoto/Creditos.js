/**
* $Id: Creditos.js,v 1.2 2010/03/12 18:41:36 hugo Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Hugo F. Manrique	
*/
var pref = '';
var pagn = '';
var fact = '';

function IsNumeric(valor)
{
	var log = valor.length; 
	var sw="S";
	var puntos = 0;
	for (x=0; x<log; x++)
	{ 
		v1 = valor.substr(x,1);
		v2 = parseInt(v1);
		//Compruebo si es un valor numérico
		if(v1 == ".")
		{
			puntos ++;
		}
		else if (isNaN(v2)) 
		{ 
			sw= "N";
			break;
		}
	}
	if(log == 0) sw = "N";
	if(puntos > 1) sw = "N";
	if(sw=="S") 
		return true;
	return false;
} 

function CrearNotaCredito(objeto)
{
	cadena = new Array();
	cadena[0] = objeto.observa.value;
	cadena[1] = objeto.prefijo.value;
	cadena[2] = objeto.factura.value;
	cadena[3] = objeto.auditor_sel.value;
	
	mensaje = "";
	if(cadena[1] == "")
		mensaje = "<b class=\"label_error\">SE DEBE ADICIONAR LA FACTURA,<br>QUE SE VERA AFECTADA POR LA NOTA CREDITO</b>";
	else
		jsrsExecute("app_modules/FacturacionNotaCreditoAjuste/ScriptRemoto/Creditos.php",CrearResultado,"CrearNotaCredito",cadena);
	
	xGetElementById('error').innerHTML = mensaje;
}

function CrearResultado(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );
	xGetElementById('error').innerHTML = html[1];
	xGetElementById('notasCredito').innerHTML = html[0];
	OcultarSpan('Contenedor');
	document.generarNota.factura.value = "";
	document.generarNota.prefijo.value = "";
	document.generarNota.observa.value = "";
	
	html = "<a href=\"javascript:IniciarB();MostrarSpan('FacturasB')\">ADICIONAR FACTURA</a>\n";
	xGetElementById('factura').innerHTML = html;
}

function EliminarNotaCredito(datos)
{
	jsrsExecute("app_modules/FacturacionNotaCreditoAjuste/ScriptRemoto/Creditos.php",CrearResultado,"EliminarNotaCredito",datos);
}

function AnularNotaCredito(datos)
{
	objeto = document.oculta;
	if(objeto.motivo.value == '0' || objeto.observa.value == "")
		xGetElementById('error').innerHTML = "<center>LOS DATOS SOLICITADOS SON OBLIGATORIOS</center>";
	else
	{
		datos[2] = objeto.motivo.value;
		datos[3] = objeto.observa.value;
		xGetElementById('error').innerHTML = "";
		jsrsExecute("app_modules/FacturacionNotaCreditoAjuste/ScriptRemoto/Creditos.php",CrearResultadoAnulacion,"AnularNotaCredito",datos);
	}
}

function CerrarNotaCredito(datos)
{
	jsrsExecute("app_modules/FacturacionNotaCreditoAjuste/ScriptRemoto/Creditos.php",CrearResultadoCerrar,"CerrarNotaCredito",datos);
	xGetElementById('confirmacion').innerHTML = "<b class=\"normal_10AN\">CERRANDO NOTA.......</b>";
	xGetElementById('confirmacionI').innerHTML = "<b class=\"normal_10AN\">CERRANDO NOTA.......</b>";
}

function CrearResultadoCerrar(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );
	xGetElementById('notasCredito').innerHTML = html[0];
	xGetElementById('confirmacion').innerHTML = html[1];
  
  OcultarSpan('ContenedorI');
	document.generarNota.factura.value = "";
	document.generarNota.prefijo.value = "";
	document.generarNota.observa.value = "";
	document.generarNota.auditor_sel.selectedIndex = '0';
	document.oculta.action = "javascript:OcultarSpan('Contenedor')";
	html = "<a href=\"javascript:IniciarB();MostrarSpan('FacturasB')\">ADICIONAR FACTURA</a>\n";
	xGetElementById('factura').innerHTML = html;
}

function CrearResultadoAnulacion(cadena)
{
	xGetElementById('confirmacion').innerHTML = cadena;
	xGetElementById('obligatorios').innerHTML = "";
	xGetElementById('cancelar').innerHTML = "";
	xGetElementById('cerrar').innerHTML = "";
	document.oculta.action = "javascript:document.buscador.submit()";
}

function CrearVariables(objeto,pagina)
{
	fact = objeto.factura.value;
	pref = objeto.prefijo.value;
	pagn = pagina;
	
	BuscarFacturas();
}

function BuscarFacturas(objeto)
{
	datos = new Array();
	datos[0] = pref;
	datos[1] = fact;
	datos[2] = pagn;
	
	jsrsExecute("app_modules/FacturacionNotaCreditoAjuste/ScriptRemoto/Creditos.php",CrearResultadoBusqueda,"BuscarFacturas",datos);
}

function CrearResultadoBusqueda(html)
{
	xGetElementById('resultado').innerHTML = html;
}

function FacturaSeleccionada(prefx,numero)
{
	html  = "<table width=\"100%\">\n";
	html += "	<tr class=\"normal_10AN\">\n";
	html += "		<td width='35%'>FACTURA ASOCIADA:</td>\n";
	html += "		<td width='15%'>"+prefx+" "+numero+"</td>\n";
	html += "		<td>\n";
	html += "			<a href=\"javascript:IniciarB();MostrarSpan('FacturasB')\">CAMBIAR FACTURA</a>\n";
	html += "		</td>\n";
	html += "	</tr>\n";
	html += "</table>\n";
	xGetElementById('factura').innerHTML = html;
	xGetElementById('error').innerHTML = "";
	document.generarNota.factura.value = numero;
	document.generarNota.prefijo.value = prefx;
}

function EvalAdicionarConcepto(objeto)
{
	concep = objeto.concepto.value.split('~'); 
	deptno = objeto.departamento.value; 
	tercer = objeto.tercero_identifica.value; 
	valor = objeto.valor_concepto.value; 
			
	mensaje = "";
	if(concep[0] == "0")
		mensaje = "SE DEBE INDICAR EL CONCEPTO QUE SE VA A ADICIONAR";
	else if(!IsNumeric(valor))
		mensaje = "EL VALOR DEl CONCEPTO NO ES VALIDO";
		else if(concep[2] == 1 && deptno == 0)
			mensaje = "SE DEBE SELECCIONAR EL DEPARTAMENTO ASOCIADO AL CONCEPTO";
			else if(concep[3] == 1 && tercer == "")
				mensaje = "SE DEBE SELECCIONAR EL TERCERO ASOCIADO AL CONCEPTO";
				else
				{
					
					tercero = new Array();
					if(tercer == "")
					{
						tercero[0] == "";
						tercero[1] == "";
					}
					else
					{
						tercero = tercer.split('*');
					}
					datos = new Array();
					
					datos[0] = "'"+concep[0];
					datos[1] = "'"+concep[1];
					datos[2] = valor;
					datos[3] = deptno;
					datos[4] = "'"+tercero[0];
					datos[5] = "'"+tercero[1];
					
					jsrsExecute("app_modules/FacturacionNotaCreditoAjuste/ScriptRemoto/Creditos.php",CrearConceptos,"AdicionarConcepto",datos);
				}
	xGetElementById('error').innerHTML = "<b class=\label_error\>"+mensaje+"</b>";
}

function CrearConceptos(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );
	xGetElementById('lista_conceptos').innerHTML = html[0];
	xGetElementById('error').innerHTML = html[1];
	
	if(html[2] == true)
	{
		document.adicionarconcepto.tercero_identifica.value = ""; 
		document.adicionarconcepto.nombre_tercero.value = ""; 
		document.adicionarconcepto.boton_tercero.disabled = true; 
		document.adicionarconcepto.departamento.selectedIndex = 0;
		document.adicionarconcepto.departamento.disabled = true; 
		document.adicionarconcepto.valor_concepto.value = ""; 
		document.adicionarconcepto.concepto.selectedIndex = 0; 
		Alerta();
	}
}

function EliminarConceptos(datos)
{
	jsrsExecute("app_modules/FacturacionNotaCreditoAjuste/ScriptRemoto/Creditos.php",CrearConceptos,"EliminarConceptos",datos);
}

function ActualizarInformacion(objeto)
{
	datos = new Array();
	datos[0] = objeto.observa.value;
	
	if(datos[0] != "")
		jsrsExecute("app_modules/FacturacionNotaCreditoAjuste/ScriptRemoto/Creditos.php",Ocultar,"ActualizarInformacion",datos);
	else
		OcultarSpan('Contenedor');
}

function Ocultar(html)
{
	OcultarSpan('Contenedor');
	xGetElementById('error').innerHTML = html;
}