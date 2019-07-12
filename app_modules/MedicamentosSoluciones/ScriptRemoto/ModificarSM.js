/**************************************************************************************
* $Id: ModificarSM.js,v 1.1 2006/08/18 20:33:41 hugo Exp $ 
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

function BuscarMedicamentosPlantillas(datos)
{
	opcion = datos[0];
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/ModificarSM.php",MostrarPlantilasMedicamentos,"BuscarMedicamentosPlantillas",datos);
}

function BuscarSolucionesPlantillas(datos)
{
	opcion = datos[0];
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/ModificarSM.php",MostrarPlantilasSoluciones,"BuscarSolucionesPlantillas",datos);
}

function MostrarPlantilasMedicamentos(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );
	plantilla = xGetElementById('tablaplantillas');
	medicamentos = xGetElementById('adicionados');
	
	plantilla.innerHTML = html[0];
	medicamentos.innerHTML = html[1];

	if(opcion != '-1') 
		MostrarSpan('buscador');
	else
		OcultarSpan('buscador');
}

function MostrarPlantilasSoluciones(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );
	error = xGetElementById('errorS');
	plantilla = xGetElementById('tablaplantillasM');
	
	error.innerHTML = "<b class='label_error'>"+html[0]+"</b>";
	plantilla.innerHTML = html[1];
	xGetElementById('soladicionada').innerHTML = html[2];
}

function CrearEnvio(objeto)
{
	prod = objeto.producto.value;
	ppac = objeto.principio_activo.value;
	pagn = '1';
	
	BuscarMedicamentos();
}

function CrearVariables(objeto,pagina)
{
	prod = objeto.producto.value;
	ppac = objeto.principio_activo.value;
	pagn = pagina;
	
	BuscarMedicamentos();
}

function BuscarMedicamentos()
{
	cadena = new Array();
	cadena[0] = prod;
	cadena[1] = ppac;
	cadena[2] = pagn;
	
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/ModificarSM.php",CrearResultado,"BuscarMedicamentos",cadena);
}

function CrearResultado(html)
{
	document.getElementById('resultado').innerHTML = html;

	MostrarSpan('resultado');
}

function ExtraerMedicamentos(codigo,producto,celda)
{
	datos = new Array();
	datos[0] = codigo;
	datos[1] = producto;
	datos[2] = celda;
	celid = 'ad'+codigo;
		
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/ModificarSM.php",AdicionarMedicamentosSeleccionados,"ExtraerMedicamentosSeleccionados",datos);
}

function AdicionarMedicamentosSeleccionados(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );
	document.getElementById('adicionados').innerHTML = html[0];
	document.getElementById('error').innerHTML = '';
	try
	{
		document.getElementById(celid).innerHTML = html[1];
	}
	catch(error){}
}

function AdicionarMedicamentos(codigo,producto,celda)
{
	datos = new Array();
	datos[0] = codigo;
	datos[1] = producto;
	datos[2] = celda;
	celid = 'ad'+codigo;

	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/ModificarSM.php",AdicionarMedicamentosSeleccionados,"AdicionarMedicamentosSeleccionados",datos);
}

var idcell = '';
function AgregarPlantilla(id,descripcion)
{
	cadena = new Array();
	cadena[0] = id;
	cadena[1] = descripcion;
	idcell = id;
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/ModificarSM.php",AgregarPlantillaSelecionada,"AgregarPlantilla",cadena);
}

function EliminarPlantilla(id,descripcion)
{
	cadena = new Array();
	cadena[0] = id;
	cadena[1] = descripcion;
	idcell = id;
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/ModificarSM.php",AgregarPlantillaSelecionada,"EliminarPlantilla",cadena);
}

function AgregarPlantillaSelecionada(html)
{
	document.getElementById('error').innerHTML = '';
	document.getElementById('errorS').innerHTML = '';
	
	document.getElementById(idcell).innerHTML = html;
}
 
function ModificarGrupoMedicamento(envio)
{
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/ModificarSM.php",Resultado,"ModificarGrupoMedicamento",envio);
}

function Resultado(cadena)
{
 	html = jsrsArrayFromString( cadena, "~" );
	if(html[0] != '')
	{
		html[0] = "<b class='label_error'>"+html[0]+"<b><br>";
	}
	else
	{
		html[0] = "<center class = 'normal_10AN'>EL GRUPO DEL MEDICAMENTO HA SIDO MODIFICADO</center><br>";
		document.getElementById('tablaplantillas').innerHTML = html[1];
		document.getElementById('adicionados').innerHTML = "<b class=\"normal_10AN\">NO HAY MEDICAMENTOS ADICIONADOS</b>\n";
		OcultarSpan('resultado');
		LimpiarCampos(1);
	}
	
	document.getElementById('error').innerHTML = html[0];
}

function AgregarPlantillaM(id,descripcion)
{
	cadena = new Array();
	cadena[0] = id;
	cadena[1] = descripcion;
	idcell = 'M'+id;
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/ModificarSM.php",AgregarPlantillaSelecionada,"AgregarPlantillaSolucion",cadena);
}

function EliminarPlantillaM(id,descripcion)
{
	cadena = new Array();
	cadena[0] = id;
	cadena[1] = descripcion;
	idcell = 'M'+id;
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/ModificarSM.php",AgregarPlantillaSelecionada,"EliminarPlantillaSolucion",cadena);
}
 
function ModificarPlantillaSolucion(envio)
{
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/ModificarSM.php",ResultadoSolucion,"ModificarPlantillaSolucion",envio);
}

function ResultadoSolucion(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );

	if(html[0] != '')
	{
		html[0] = "<b class='label_error'>"+html[0]+"</b><br>";
	}
	else
	{
		html[0] = "<b class = 'normal_10AN'>EL GRUPO DE LA SOLUCION HA SIDO MODIFICADO</b><br>";
		document.getElementById('tablaplantillasM').innerHTML = html[1];
		document.getElementById('soladicionada').innerHTML = 'SOLUCIONES ADICIONADAS';
		document.gruposolucion.gruposol.selectedIndex = 0;
	}
	document.getElementById('errorS').innerHTML = html[0];
}

var sacell = '';
function AdicionarSolucionesAsociadas(datos)
{
	sacell = 'sc'+datos[0];
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/ModificarSM.php",ResultadoExSolucion,"AdicionarSolucionesAsociadas",datos);
}

function ExtraerSolucionesAsociadas(datos)
{
	sacell = 'sc'+datos[0];
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/ModificarSM.php",ResultadoExSolucion,"ExtraerSolucionesAsociadas",datos);
}

function ResultadoExSolucion(html)
{
	xGetElementById(sacell).innerHTML = html;
}