/**************************************************************************************
* $Id: Formulacion.js,v 1.12 2009/05/15 14:07:00 hugo Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Hugo F. Manrique	
**************************************************************************************/
var titulo = "";
var contenedor = "";
var error = "";
var contador;
var clases;
var productocd;
var inicio;
var datosEnvio = new Array();
var hiZ = 2;
var XDiv1 = "";

function CrearEdicion(capa,num_reg,ini)
{
	if(ini == '1')
	{
		XDiv1 = capa;
		dEnvio = new Array();
		dEnvio[0] = num_reg;
		jsrsExecute("classes/modules/procesos.php",DesplegarEdicion,"CrearInterfaceSolucion",dEnvio);
	}
}

function DesplegarEdicion(html)
{
	DatosObligatorias('tituloS','Soluciones','errorS');
	IniciarEdicion('editar solucion');
	document.getElementById(error).innerHTML = '';
	document.getElementById('ContenedorSolucion').innerHTML = html;
	MostrarCapas(contenedor);
}

function ActualizarSolucion(estado,num_mezcla)
{
	datosEnvio[2] = estado;
	jsrsExecute("classes/modules/procesos.php",RetornoNull,"Actualizarsolucion",datosEnvio);
}

function MostrarCapas(Seccion)
{
	e = xGetElementById(Seccion);
	e.style.display = 'block';
}

function ActualizarDatos(estado,codigo)
{
	datosEnvio[2] = estado;
	jsrsExecute("classes/modules/procesos.php",RetornoNull,"ActualizarMedicamento",datosEnvio);
}

function RetornoNull(){}

function EvaluarDatosSuspension(objeto)
{
	if(objeto.observacion.value =="")
		document.getElementById(error).innerHTML = '<center>SE DEBE INGRESAR UNA JUSTIFICACIÓN</center>';
	else
	{
		datosEnvio[0] = objeto.observacion.value;
		datosEnvio[1] = productocd;
		MostrarSpan('d2Container');
		EnviarInformacion();
	}
}

function EvaluarDatosEdicion(objeto,num_reg)
{
	mensaje = "";
	if(!IsNumeric(objeto.volumeninput.value))
		mensaje = '<center>EL VALOR INGRESADO PARA EL VOLUMEN DE INFUSION ES INCORRECTO</center>';
	else if(objeto.volumenselect.value == "0")
			mensaje = '<center>SE DEBE SELECCIONAR LAS UNIDADES DEL VOLUMEN DE INFUSION</center>';
	document.getElementById(error).innerHTML = mensaje;
	if(mensaje == '')
	{
		dEnvio = new Array();
		dEnvio[0] = num_reg;
		dEnvio[1] = objeto.volumeninput.value;
		dEnvio[2] = objeto.volumenselect.value;
		dEnvio[3] = objeto.observacion.value;
		dEnvio[4] = XDiv1;
		
		MostrarSpan(contenedor);
		jsrsExecute("classes/modules/procesos.php",ActualizarIS,"ActualizarEdicionSolucion",dEnvio);
	}
}

function ActualizarIS(html)
{
	document.getElementById(XDiv1).innerHTML = html;
}


function EvaluarDatosSuspensionS(objeto)
{
	if(objeto.observacion.value =="")
		document.getElementById(error).innerHTML = '<center>SE DEBE INGRESAR UNA JUSTIFICACIÓN</center>';
	else
	{
		datosEnvio[0] = objeto.observacion.value;
		datosEnvio[1] = productocd;
		MostrarSpan('d2Container');
		EnviarInformacionSolucion();
	}
}

function EnviarInformacion()
{
	if(!datosEnvio[0]) datosEnvio[0] = '';
	datosEnvio[1] = productocd;
	CambiarImagen(contador,clases,productocd,inicio);
}

function EnviarInformacionSolucion()
{
	datosEnvio[0] = '';
	datosEnvio[1] = productocd;

	CambiarImagenS(contador,clases,productocd,inicio);
}

function DatosActuales(cont,clasesjs,datosprod,iniciox)
{
	contador = cont;
	clases = clasesjs;
	productocd = datosprod;
	inicio = iniciox;
}

function DatosObligatorias(tit,content,errorD)
{
	titulo = tit;
	contenedor = content;
	error = errorD;
}

function myOnDragStart(ele, mx, my)
{
  window.status = '';
  if (ele.id == titulo) xZIndex(contenedor, hiZ++);
  else xZIndex(ele, hiZ++);
  ele.myTotalMX = 0;
  ele.myTotalMY = 0;
}
	
function myOnDrag(ele, mdx, mdy)
{
  if (ele.id == titulo) 
	{
	  xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);
	}
	else 
	{
	  xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);
	}  
	ele.myTotalMX += mdx;
	ele.myTotalMY += mdy;
}

function myOnDragEnd(ele, mx, my)
{
}

function Reformular(codigo,estado,producto,dia)
{
	html  = "<form name=\"programas\" action=\"\" method=\"post\">\n";
	html += "	<table width=\"100%\" bgcolor=\"#FFFFFF\">\n";
	html += "		<tr>\n";
	html += "			<td align=\"center\">\n";
	html += "				<b class=\"label\">ESTA SEGURO QUE DESEA REFORMULAR EL MEDICAMENTO</b> <b class=\"label_mark\">"+producto+"</b><b class=\"label\">?</b>\n";
	html += "			</td>\n";
	html += "		</tr>\n"
	html += "		<tr>\n";
	html += "			<td align=\"center\" colspan=\"3\">\n";
	html += "				<input type=\"button\" name=\"ACEPTAR\" value=\"Aceptar\" class=\"input-submit\" onclick=\"ReformularMedicamento('"+codigo+"','"+estado+"','"+dia+"')\">\n";
	html += "				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
	html += "				<input type=\"button\" name=\"ACEPTAR\" value=\"Cancelar\" class=\"input-submit\" onclick=\"MostrarSpan('d2Container')\">\n";
	html += "			</td>\n";
	html += "		</tr>\n";
	html += "	</table>\n";
	html += "</form>\n";
	
	Iniciar();
	document.getElementById('d2Contents').innerHTML = html;
	MostrarCapas('d2Container');
}

function ReformularMedicamento(codigo,estado,dia)
{
	envio = new Array();
	envio[0] = codigo;
	envio[1] = estado;
	envio[2] = dia;
	MostrarSpan('d2Container');
	jsrsExecute("ScriptsRemotos/medicamentos.php",CrearResultado,"ActualizarMedicamento",envio);
}

function ReformularS(num_mezcla,estado,sol)
{
	html  = "<form name=\"programas\" action=\"\" method=\"post\">\n";
	html += "	<table width=\"100%\" bgcolor=\"#FFFFFF\">\n";
	html += "		<tr>\n";
	html += "			<td align=\"center\">\n";
	html += "				<b class=\"label\">ESTA SEGURO QUE DESEA REFORMULAR LA</b> <b class=\"label_mark\">"+sol+"</b> <b class=\"label\">?</b>\n";
	html += "			</td>\n";
	html += "		</tr>\n"
	html += "		<tr>\n";
	html += "			<td align=\"center\" colspan=\"3\">\n";
	html += "				<input type=\"button\" name=\"ACEPTAR\" value=\"Aceptar\" class=\"input-submit\" onclick=\"ReformularSolucion('"+num_mezcla+"','"+estado+"')\">\n";
	html += "				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
	html += "				<input type=\"button\" name=\"ACEPTAR\" value=\"Cancelar\" class=\"input-submit\" onclick=\"MostrarSpan('d2Container')\">\n";
	html += "			</td>\n";
	html += "		</tr>\n";
	html += "	</table>\n";
	html += "</form>\n";
	
	Iniciar();
	document.getElementById('d2Contents').innerHTML = html;
	MostrarCapas('d2Container');
}

function ReformularSolucion(num_mezcla,estado)
{
	envio = new Array();
	envio[0] = num_mezcla;
	envio[1] = estado;
	jsrsExecute("ScriptsRemotos/medicamentos.php",CrearResultado,"ActualizarSolucion",envio);
}

function VisualizarHistorial(codigo_producto)
{
	jsrsExecute("classes/modules/ScriptsRemotos/historial.php",MostrarHtml,"ConsultarHistorial",new Array(codigo_producto));
}

function MostrarHtml(html)
{
	DatosObligatorias('tituloS','Soluciones','errorS');
	IniciarEdicion('historial');
	document.getElementById(error).innerHTML = '';
	document.getElementById('ContenedorSolucion').innerHTML = html;
	MostrarCapas(contenedor);
}

function RetornoNull(html){}

function CrearProfesionales()
{
	jsrsExecute("classes/modules/procesos.php",VisualizarProfesionales,"CrearProfesionales",new Array('1'));
}

function VisualizarProfesionales(html)
{
	DatosObligatorias('tituloS','Soluciones','errorS');
	IniciarEdicion('SELECCIONAR PROFESIONAL');
	document.getElementById(error).innerHTML = '';
	document.getElementById('ContenedorSolucion').innerHTML = html;
	MostrarCapas(contenedor);
}

var nombrecampoconfirma = "confirmacion";
var nombrecapa = "";
function EvaluarConfirmacion(codigo,num_reg,capa)
{
	arregloE = new Array();
	arregloE[0] = codigo;
	arregloE[1] = num_reg;
	arregloE[2] = document.confirma.observacion.value;
	
	if(capa != undefined)
		nombrecapa = capa;
		
	nombrecampoconfirma = "confirmacion"+codigo;
	if(document.confirma.observacion.value == "")
		document.getElementById(error).innerHTML = '<center>SE DEBE INGRESAR UNA NOTA</center>';
	else
		jsrsExecute("classes/modules/procesos.php",ConfirmarFormula,"ConfirmarFormulacion",arregloE);
}

function ConfirmarFormula(campo)
{
	try
	{	
		document.getElementById(nombrecampoconfirma).innerHTML = '';

		if(nombrecapa != "")
		{
			xGetElementById(nombrecapa).innerHTML = "<center class='label_mark'>MEDICAMENTO CONFIRMADO</center>";
			e = document.getElementById('MedicamentosFinalizados');
			if(e.style.display == 'none') e.style.display = '';
		}
	}
	catch(error){}
	MostrarSpan('Confirmacion');
	
	jsrsExecute("classes/modules/procesos.php",ActivarBoton,"GetEstado",new Array('1'));	
}

function ActivarBoton(html)
{
	if(html == '0')	xGetElementById('boton').style.display = '';
}

function VerHistorial(datos)
{
	jsrsExecute("classes/modules/ScriptsRemotos/historial.php",MostrarHtml,"VerHistorialSolucion",datos);
}

function CrearVariables(objeto,pagina)
{
	cadena = new Array();
	cadena[0] = objeto.codigo.value;
	cadena[1] = objeto.diagnostico.value;
	cadena[2] = pagina;
	jsrsExecute("ScriptsRemotos/justificacion.php",MostrarDatosDiag,"BuscarDiagnosticos",cadena,true);
}

function MostrarDatosDiag(html)
{
	xGetElementById('resultado').innerHTML = html; 
}

function AgregarDiagnostico(llave,valor)
{
	if(valor)
		jsrsExecute("ScriptsRemotos/justificacion.php",AgregarDatosDiag,"AgregarDiagnostico",new Array(llave));
	else
		jsrsExecute("ScriptsRemotos/justificacion.php",AgregarDatosDiag,"EliminarDiagnostico",new Array(llave));
}

function AgregarDatosDiag(html)
{
	xGetElementById('diagnosticos').innerHTML = html; 
}