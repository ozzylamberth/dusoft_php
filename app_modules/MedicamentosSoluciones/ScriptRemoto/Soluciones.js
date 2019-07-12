/**************************************************************************************
* $Id: Soluciones.js,v 1.1 2006/08/18 20:33:47 hugo Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Hugo F. Manrique	
**************************************************************************************/

var prod = '';
var ppac = '';
var pagn = '';
var grupo = '';
var capa = 'resultado';
var celid = '';
var celid2 = '';
var longitud = 0;
var longitud2 = 0;
var sw_solucion = '0';

function CrearVariables(objeto,pagina)
{
	prod = objeto.producto.value;
	ppac = objeto.principio_activo.value;
	pagn = pagina;
	
	BuscarMedicamentos();
}

function CrearEnvio(objeto)
{
	prod = objeto.producto.value;
	ppac = objeto.principio_activo.value;
	pagn = '1';
	
	BuscarMedicamentos();
}

function CrearBusquedaClasifica(objeto)
{
	prod = objeto.producto.value;
	ppac = objeto.principio_activo.value;
	pagn = '1';
	capa = 'solicitud';
	
	BuscarClasificacion();
}

function CrearVariablesClasificar(objeto,pagina)
{
	prod = objeto.producto.value;
	ppac = objeto.principio_activo.value;
	pagn = pagina;
	capa = 'solicitud';
	
	BuscarClasificacion();
}

function BuscarClasificacion()
{
	cadena = new Array();
	
	cadena[0] = prod;
	cadena[1] = ppac;
	cadena[2] = pagn;
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/Soluciones.php",CrearResultadoClasifica,"BuscarMedicamentosClasificar",cadena);
}

function BuscarMedicamentos()
{
	cadena = new Array();
	
	cadena[0] = prod;
	cadena[1] = ppac;
	cadena[2] = pagn;
	
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/Soluciones.php",CrearResultado,"BuscarMedicamentos",cadena);
}

function CrearResultado(html)
{
	document.getElementById('resultado').innerHTML = html;

	MostrarSpan('resultado');
}

function CrearResultadoClasifica(html)
{
	document.getElementById('solicitud').innerHTML = html;

	MostrarSpan('solicitud');
}

function SeleccionarMedicamentos(datos)
{
	celid = 'ad'+datos[0];
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/Soluciones.php",AdicionarMedicamentosSeleccionados,"SeleccionarMedicamentos",datos);
}

function DeseleccionarMedicamentos(datos)
{
	celid = 'ad'+datos[0];	
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/Soluciones.php",AdicionarMedicamentosSeleccionados,"DeseleccionarMedicamentos",datos);
}

function AdicionarMedicamentosSeleccionados(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );
	document.getElementById('errorCrear').innerHTML = '';
	document.getElementById('adicionados').innerHTML = html[0];
	try
	{
		document.getElementById(celid).innerHTML = html[1];
	}
	catch(error){}
	
	sw_solucion = html[2];
	longitud = html[3];
}

function EvaluarCreacionSoluciones(objeto)
{
	var mensaje = '';
	var envio = new Array();

	envio[0] = objeto.nombresulucion.value;
	envio[1] = objeto.gruposolucion.value;
		
	if(envio[0] == '')
		mensaje = "<b class='label_error'>SE DEBE INGRESAR EL NOMBRE DE LA NUEVA SOLUCION</b>";
		else if(envio[1] == '-1')
			mensaje = "<b class='label_error'>SE DEBE SELECCIONAR EL GRUPO AL QUE PERTENECE LA SOLUCION</b>";
			else if( longitud < 2)
				mensaje = "<b class='label_error'>LA NUEVA SOLUCION DEBE COMPONERSE DE MAS DE UN MEDICAMENTO</b>";
				else if(sw_solucion == '0')
					mensaje = "<b class='label_error'>PARA LA NUEVA SOLUCION, SE DEBEN ADICIONAR MEDICAMENTOS QUE SEAN SOLUCIONES<br>PARA QUE PUEDA SER CREADA</b>";
	
	document.getElementById('errorCrear').innerHTML = mensaje;
	
	if(mensaje == '')
	{
		jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/Soluciones.php",CrearSoluciones,"CrearSoluciones",envio);
	}
}

function CrearSoluciones(html)
{
	LimpiarCampos(1);
	OcultarSpan('resultado');
	document.getElementById('errorCrear').innerHTML = "<b class='label_mark'>LA SOLUCION HA SIDO CREADA</b>";
	document.getElementById('adicionados').innerHTML = html;
}

function ClasificarSolucion(datos)
{
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/Soluciones.php",ClasificarSolucionResultado,"ClasificarSolucion",envio);
}

function CrearGrupo(objeto)
{
	var mensaje = '';
	var envio = new Array();

	envio[0] = objeto.nombre.value;
	envio[1] = '';
	
	if(objeto.pertenencia[0].checked)
		envio[1] = objeto.pertenencia[0].value;
	else if(objeto.pertenencia[1].checked)
		envio[1] = objeto.pertenencia[1].value;
		
	if(envio[0] == '')
		mensaje = "<b class='label_error'>SE DEBE INGRESAR EL NOMBRE DEL GRUPO</b>";
		else if(envio[1] == '')
			mensaje = "<b class='label_error'>SE DEBE SELECCIONAR EL GRUPO DE CLASIFICACION</b>";
	document.getElementById('Ecreacion').innerHTML = mensaje;
	
	if(mensaje == '')
	{
		jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/Soluciones.php",PintarGrupoClasificacion,"CrearGrupoClasificacion",envio);
	}
}

function PintarGrupoClasificacion(html)
{
	document.getElementById('selectX').innerHTML = html;
	ImprimirL(document.clasificacionSoluciones);
	OcultarSpan('Contenedor');
}

function AdicionarMedicamentosClasificar(datos)
{
	celid2 = 'is'+datos[0];
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/Soluciones.php",AdicionarMedicamentosClasificadosT,"AdicionarMedicamentosClasificar",datos);
}

function EliminarMedicamentosClasificar(datos)
{
	celid2 = 'is'+datos[0];	
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/Soluciones.php",AdicionarMedicamentosClasificadosT,"EliminarMedicamentosClasificar",datos);
}

function AdicionarMedicamentosClasificadosT(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );
	document.getElementById('errorDefinir').innerHTML = '';
	document.getElementById('clasificados').innerHTML = html[0];
	try
	{
		document.getElementById(celid2).innerHTML = html[1];
	}
	catch(error){}
	longitud2 = html[2];
}

function CrearMedicamentosAsociados(datos)
{
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/Soluciones.php",AdicionarMedicamentosAsociados,"CrearMedicamentosAsociados",datos);
}

function AdicionarMedicamentosAsociados(cadena)
{
	html = jsrsArrayFromString( cadena, "~" );
	document.getElementById('errorDefinir').innerHTML = '';
	document.getElementById('clasificados').innerHTML = html[0];
	longitud2 = html[1];
	OcultarSpan('solicitud');
}

function EvaluarClasificacion(objeto)
{
	cadena = new Array();
	cadena[0] = objeto.grupos.value;
	
	if(cadena[0] == '-1')
		document.getElementById('errorDefinir').innerHTML = "<b class=\"label_error\">NO SE HA SELECCIONADO EL GRUPO AL QUE SE ASOCIARAN LOS MEDICAMENTOS</b>";
		else if(longitud2 == 0)
			document.getElementById('errorDefinir').innerHTML = "<b class=\"label_error\">NO HAY MEDICAMENTOS SELECCIONADOS, PARA ASOCIAR AL GRUPO</b>";

	cadena[0] = cadena[0].split('*')[0];
	jsrsExecute("app_modules/MedicamentosSoluciones/ScriptRemoto/Soluciones.php",CrearAsociacionMedicamentos,"CrearAsociacionMedicamentos",cadena);
}

function CrearAsociacionMedicamentos(html)
{
	document.getElementById('errorDefinir').innerHTML = "<b class=\"label_mark\">LOS MEDICAMENTOS SE HAN ASOCIADO AL GRUPO</b>";
	LimpiarCamposClasificacion(1);
	document.getElementById('clasificacion').innerHTML = 'SIN CLASIFICACION';
	document.getElementById('clasificados').innerHTML = html;
	OcultarSpan('solicitud');
}