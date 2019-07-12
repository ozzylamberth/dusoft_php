/**************************************************************************************
* $Id: EliminarSM.js,v 1.2 2006/08/18 20:51:18 hugo Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Hugo F. Manrique	
**************************************************************************************/

var opcion = '-1';
var prod = '';
var ppac = '';
var pagn = '';
var celid = '';

function BuscarInformacionGrupo(datos)
{
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/EliminarSM.php",MostrarInformacionGrupo,"BuscarInformacionGrupo",datos);
}

function MostrarInformacionGrupo(html)
{
	plantilla = xGetElementById('tablaplantillas');
	plantilla.innerHTML = html;
}

function EliminarGrupoMedicaento(datos)
{
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/EliminarSM.php",MostrarEliminarGrupoMedicaento,"EliminarGrupoMedicaento",datos);
}

function MostrarEliminarGrupoMedicaento(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );
	xGetElementById('error').innerHTML = html[0];
	xGetElementById('selectgrupoM').innerHTML = html[1];
	xGetElementById('tablaplantillas').innerHTML = "INFORMACIÓN";
	OcultarSpan('Contenedor');
}

function BuscarInformacionSoluciones(datos)
{
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/EliminarSM.php",MostrarInformacionSoluciones,"BuscarInformacionSoluciones",datos);
}

function MostrarInformacionSoluciones(html)
{
	xGetElementById('errorS').innerHTML = "";
	xGetElementById('infogruposolucion').innerHTML = html;
}

function EliminarGrupoSoluciones(datos)
{
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/EliminarSM.php",MostrarEliminarGrupoSoluciones,"EliminarGrupoSoluciones",datos);
}

function MostrarEliminarGrupoSoluciones(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );
	xGetElementById('errorS').innerHTML = html[0];
	xGetElementById('selectgrupoSm').innerHTML = html[1];
	xGetElementById('infogruposolucion').innerHTML = "INFORMACIÓN";
	OcultarSpan('Contenedor');
}

function BuscarInformacionSolucionS(datos)
{
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/EliminarSM.php",MostrarInformacionSolucionS,"BuscarInformacionSolucionS",datos);
}

function MostrarInformacionSolucionS(html)
{
	xGetElementById('clasificados').innerHTML = html;
}

function EliminarSolucionesS1(datos)
{
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/EliminarSM.php",MostrarEliminarSolucionesS,"EliminarSolucionesS",datos);
}

function MostrarEliminarSolucionesS(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );
	xGetElementById('errorDefinir').innerHTML = html[0];
	xGetElementById('selectSolucionesS').innerHTML = html[1];
	xGetElementById('clasificados').innerHTML = "INFORMACIÓN";
	OcultarSpan('Contenedor');
}