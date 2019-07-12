/**************************************************************************************
* $Id: GruposSM.js,v 1.1 2006/08/18 20:33:34 hugo Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Hugo F. Manrique	
**************************************************************************************/

var prod = '';
var ppac = '';
var pagn = '';
var celid = '';

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
	
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/GruposSM.php",CrearResultado,"BuscarMedicamentos",cadena);
}

function CrearResultado(html)
{
	document.getElementById('resultado').innerHTML = html;

	MostrarSpan('resultado');
}

function AdicionarMedicamentos(codigo,producto,celda)
{
	datos = new Array();
	datos[0] = codigo;
	datos[1] = producto;
	datos[2] = celda;
	celid = 'ad'+codigo;

	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/GruposSM.php",AdicionarMedicamentosSeleccionados,"AdicionarMedicamentosSeleccionados",datos);
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

function ExtraerMedicamentos(codigo,producto,celda)
{
	datos = new Array();
	datos[0] = codigo;
	datos[1] = producto;
	datos[2] = celda;
	celid = 'ad'+codigo;
		
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/GruposSM.php",AdicionarMedicamentosSeleccionados,"ExtraerMedicamentosSeleccionados",datos);
}

function CrearGrupoMedicamento(envio)
{
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/GruposSM.php",Resultado,"CrearGrupoMedicamentos",envio);
}

function CrearGrupoSolucion(envio)
{
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/GruposSM.php",ResultadoSolucion,"CrearGrupoSolucion",envio);
}

function ResultadoSolucion(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );

	if(html[0] != '') 
		html[0] = "<center class='label_error'>"+html+"<center><br>";
	else
		html[0] = "<center class = 'normal_10AN'>EL GRUPO DE LA SOLUCION HA SIDO CREADO</center><br>";
	
	document.getElementById('errorS').innerHTML = html[0];
	document.getElementById('tablaplantillasM').innerHTML = html[1];
	document.gruposolucion.nombre_grupo.value = ''
}

function Resultado(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );
	if(html[0] != '') 
		html[0] = "<center class='label_error'>"+html+"<center><br>";
	else
		html[0] = "<center class = 'normal_10AN'>EL GRUPO DEL MEDICAMENTO HA SIDO CREADO</center><br>";
	
	document.getElementById('error').innerHTML = html[0];
	document.getElementById('tablaplantillas').innerHTML = html[1];
	document.getElementById('adicionados').innerHTML = "<font class=\"normal_10AN\">NO HAY MEDICAMENTOS ADICIONADOS</font>\n";
	OcultarSpan('resultado');
	LimpiarCampos(1);
}

var idcell = '';
function AgregarPlantilla(id,descripcion)
{
	cadena = new Array();
	cadena[0] = id;
	cadena[1] = descripcion;
	idcell = id;
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/GruposSM.php",AgregarPlantillaSelecionada,"AgregarPlantilla",cadena);
}

function EliminarPlantilla(id,descripcion)
{
	cadena = new Array();
	cadena[0] = id;
	cadena[1] = descripcion;
	idcell = id;
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/GruposSM.php",AgregarPlantillaSelecionada,"EliminarPlantilla",cadena);
}

function AgregarPlantillaM(id,descripcion)
{
	cadena = new Array();
	cadena[0] = id;
	cadena[1] = descripcion;
	idcell = 'S'+id;
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/GruposSM.php",AgregarPlantillaSelecionada,"AgregarPlantillaSolucion",cadena);
}

function EliminarPlantillaM(id,descripcion)
{
	cadena = new Array();
	cadena[0] = id;
	cadena[1] = descripcion;
	idcell = 'S'+id;
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/GruposSM.php",AgregarPlantillaSelecionada,"EliminarPlantillaSolucion",cadena);
}

function AgregarPlantillaSelecionada(html)
{
	document.getElementById('error').innerHTML = '';
	document.getElementById('errorS').innerHTML = '';
	
	document.getElementById(idcell).innerHTML = html;
}