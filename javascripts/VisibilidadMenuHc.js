	/**************************************************************************************
	* $Id: VisibilidadMenuHc.js,v 1.16 2009/06/26 13:57:41 hugo Exp $ 
	* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* package IPSOFT-SIIS
	*
	* author Hugo F. Manrique	
	**************************************************************************************/
var valoresC = new Array();
var valoresD = new Array();
var valoresU = new Array();
var valoresV = new Array();
var codigosReceta = new Array();
var ocupado = 0;
var opcionSel = 0;
var liquidoesc = 0;
var mensajeerror = "";
var adicionales = "";

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

function valores(usuario,evolucion) 
{
 /*
        Esta funcion solo ejecuta jsrsExecute con los siguientes parametros:
        1: Fichero [url] del .php que ofrece el servicio
        2: nombre de la funcion que recibirá el resultado ... recibe siempre un parámetro
        3: nombre de la funcion a ejecutar en el servidor
        4: parametros a enviar al servidor ... en este una cadena ...
    */
	cadena = new Array("'"+usuario+"'","'"+evolucion+"'");
	jsrsExecute("classes/modules/procesos.php",valores2,"ActivarMenu",cadena);
}

function valores2(cadena){}

function creartabla(arreglo,opcion,direccion)
{
	cadena = new Array();
	cadena[0] = arreglo;
	cadena[1] = opcion;
	cadena[2] = direccion;

	if(opcion == 1) ocupado = 0;
	
	if(ocupado == 0)
	{
		MensajeFinal('ErrorSolicitud','');
		jsrsExecute("classes/modules/procesos.php",pintarTabla,"CrearTabla",cadena);
		
		ocupado = 1;
	}
	else if(ocupado == 1)
	{
		MensajeFinal('ErrorSolicitud','EN ESTE MOMENTO UD. ESTA FORMULANDO UM NEDICAMENTO,<br> ANULE O FORMULE, PARA ADICIONAR OTRO MEDICAMENTO');
	}
	opcionSel = opcion;
}

function pintarTabla(retorno)
{
	cadena = jsrsArrayFromString( retorno, "*" );
  
	if(cadena[1] == '0') cadena[1] = "";
	document.getElementById('Solicitud').innerHTML = cadena[1];
	try
  { 
    if(cadena[2] != '0' && cadena[2] != "")
      ProtocolosFormulacionII(cadena[2]);
  }
  catch(error){}
  
	if((cadena[1] == "" || cadena[2] == "") && opcionSel != 1)
	{
		document.getElementById('ErrorSolicitud').innerHTML = "";
		jsrsExecute("procesos.php",pintarMedicamento,"CrearMedicamentos",cadena);
	}
  
	if(ocupado == 1 && opcionSel == 1)	ocupado = 0;
	if(cadena[0] == "1") ocupado = 0;
}

function crearBusqueda(producto,ppactivo,bodega,pagina,ruta)
{
	cadena = new Array("'"+producto+"'","'"+ppactivo+"'","'"+bodega+"'","'"+pagina+"'","'"+ruta+"'");
	jsrsExecute("ScriptsRemotos/buscadorHtml.php",pintarResultado,"CrearResultado",cadena);
}

function pintarResultado(cadena)
{
	document.getElementById('Busqueda').innerHTML = cadena;
	MostrarSpan2('Busqueda');
}

function crearRecetaLiq(codigo,opcion,ruta)
{
	cadena = new Array("'"+codigo+"'","'"+opcion+"'","'"+ruta+"'");
	jsrsExecute("ScriptsRemotos/buscadorHtml.php",pintarReceta,"CrearReceta",cadena);
}

function crearReceta(codigo,opcion,ruta)
{
	document.getElementById('Error').innerHTML = "";
	cadena = new Array("'"+codigo+"'","'"+opcion+"'","'"+ruta+"'");
	jsrsExecute("ScriptsRemotos/buscadorHtml.php",pintarReceta,"CrearReceta",cadena);
}

function pintarReceta(cadena)
{
	contenido  = jsrsArrayFromString( cadena, "*" );
	codigos  = jsrsArrayFromString( contenido[0], "~" );
	codigosReceta = codigos;
	
	if(document.getElementById('Receta').innerHTML && contenido[1] != "")
	{
		soluciones = document.medicamentos.sw_solucion;
		dosisnum = document.medicamentos.dosisnumerica;
		cantidades = document.medicamentos.cantidad;
		unidadesdosis = document.medicamentos.dosisunidad;
		
		j = 0;
		if(dosisnum.length)
		{
			for(i=0; i<dosisnum.length; i++)
			{
				if(document.medicamentos.codproducto[i].value == codigos[j])
				{
					valoresD[j] = dosisnum[i].value;
					valoresV[j] = unidadesdosis[i].selectedIndex;
					valoresC[j] = cantidades[i].value;
					valoresU[j] = soluciones[i].value;
					j++;
				}
			}
		}
		else
		{
			valoresD[j] = dosisnum.value;
			valoresC[j] = cantidades.value;
			valoresV[j] = unidadesdosis.selectedIndex;
			valoresU[j] = soluciones.value;
		}
	}
	document.getElementById('Receta').innerHTML =  contenido[1];
	if(contenido[1] != "")
	{
		dosisnum = document.medicamentos.dosisnumerica;
		cantidades = document.medicamentos.cantidad;
		unidadesdosis = document.medicamentos.dosisunidad;
		codproductos = document.medicamentos.codproducto;
		soluciones = document.medicamentos.sw_solucion;
		
		i = 0;
		for(j=0; j<valoresC.length; j++)
		{
			if(codproductos[j].value == codigosReceta[j])
			{
				if(!valoresC[j]) valoresC[j] = "";
				if(!valoresD[j]) valoresD[j] = "";
				//if(!valoresU[j]) valoresU[j] = "0";
				try
				{
					dosisnum[j].value = valoresD[j];
					cantidades[j].value = valoresC[j];
					unidadesdosis[j].selectedIndex = valoresV[j];
					soluciones[j].value = valoresU[j];
				}
				catch(error)
				{
					dosisnum.value = valoresD[j];
					cantidades.value = valoresC[j];
					unidadesdosis.selectedIndex = valoresV[j];
				}
			}
		}
	}
	if(contenido[1] != "" && contenido[0] != '')
	{
		MostrarSpan2("Opciones");
	}
	else
	{
		OcultarSpan("Opciones");
		OcultarSpan("GuardarMezcla");
		OcultarSpan("RecetarSolucion");
	}
}

function EvaluarResultados(opcion,ruta)
{
	j = 0;
	paso = EvaluarCampos();

	cadena = new Array();
	
	if(!paso)
	{
		if(document.medicamentos.nombre_mezcla.value == "")
		{
			mensajeerror = "SE DEBE INGRESAR EL NOMBRE DE LA SOLUCION";
			paso = true;
		}
		else if(document.medicamentos.grupo_mezcla.value == "0")
		{
			mensajeerror = "SE DEBE SELECCIONAR EL GRUPO AL CUAL PERTENECERA LA SOLUCION";
			paso = true;
		}
	}
	cadena[0] = document.medicamentos.grupo_mezcla.value;
	
	document.getElementById('Error').innerHTML = "<center><b class=\"label_error\">"+mensajeerror+"</b></center>";
 	if(!paso)
	{
		cadena[1] = document.medicamentos.nombre_mezcla.value;
		cadena[2] = adicionales;
		cadena[3] = ruta;
		jsrsExecute("ScriptsRemotos/buscadorHtml.php",ActualizarMenu,"IngresarMezcla",cadena);
	}
}

function Recetar(ruta)
{
	try
	{
	paso = EvaluarCampos();
	cadena = new Array();
	
	if(!paso)
	{
		if(document.medicamentos.cantidadTotal.value == "")
		{
			mensajeerror = "SE DEBE INGRESAR LA CANTIDAD TOTAL";
			paso = true;
		}
		else if(document.medicamentos.volumeninput.value == "")
			{
				mensajeerror = "SE DEBE INGRESAR EL VOLUMEN DE LA SOLUCION";
				paso = true;
			}
			else if(document.medicamentos.volumenselect.value == "0")
			{
				mensajeerror = "FAVOR SELECCIONAR LAS UNIDADES EN LA QUE SE ADMINISTRARA LA SOLUCION";
				paso = true;
			}
	}
	
	document.getElementById('Error').innerHTML = "<center><b class=\"label_error\">"+mensajeerror+"</b></center>";
 	if(!paso)
	{
		cadena[0] = document.medicamentos.cantidadTotal.value;
		cadena[1] = document.medicamentos.volumeninput.value;
		cadena[2] = document.medicamentos.volumenselect.value;
		cadena[3] = document.medicamentos.observacion.value;
		cadena[4] = '1';
		cadena[5] = ruta;
		cadena[6] = adicionales;
		jsrsExecute("ScriptsRemotos/buscadorHtml.php",RecetarSolucion,"RecetarSolucion",cadena);
	}
	}catch(error){alert(error);}
}

function RecetarSolucion(cadena)
{
	window.opener.document.getElementById('MedicamentosNuevos').innerHTML = cadena;
	window.close();
}

function EvaluarCampos()
{
	j = 0;
	paso = false;
	liquido = false;
	adicionales = "";
	if(codigosReceta.length > 0)
	{
		productos = document.medicamentos.producto;
		dosisnum = document.medicamentos.dosisnumerica;
		cantidades = document.medicamentos.cantidad;
		soluciones = document.medicamentos.sw_solucion;
		unidadesdosis = document.medicamentos.dosisunidad;
		codproductos = document.medicamentos.codproducto;
		
		for(i=0; i<productos.length; i++ )
		{
			if(!IsNumeric(dosisnum[i].value))
			{
				paso = true;
				mensajeerror = "EL FORMATO DE LA DOSIS ES INCORRECTO PARA EL MEDICAMENTO "+productos[i].value;
				break;
			}	
			
			if(unidadesdosis[i].value == '0')
			{
				paso = true;
				mensajeerror = "SE DEBE SELECCIONAR UNA UNIDAD DE DOSIFICACION PARA EL MEDICAMENTO "+productos[i].value;
				break;
			}
			
			if(!IsNumeric(cantidades[i].value))
			{
				paso = true;
				mensajeerror = "EL FORMATO DE LA CANTIDAD ES INCORRECTO PARA EL MEDICAMENTO "+productos[i].value;
				break;
			}
			
			if(soluciones[i].value == '1') liquido = true;
			adicionales += codproductos[i].value+"#"+soluciones[i].value+"#"+dosisnum[i].value+"#"+unidadesdosis[i].value+"#"+cantidades[i].value+"*";			
		}
		
		/*for(i=0; i<document.medicamentos.elements.length; i++)
		{
			 if(document.medicamentos.elements[i].id == codigosReceta[j])
			{
				if(document.medicamentos.elements[i].value == "")
				{
					paso = true;
					mensajeerror = "SE DEBE INGRESAR TODA LA INFORMACÓN CORRESPONDIENTE A LA CANTIDAD DE LOS MEDICAMENTOS";
					break;
				}
				else if(!IsNumeric(document.medicamentos.elements[i].value))
					{
						paso = true;
						mensajeerror = "EL FORMATO DE LA CANTIDAD ES INCORRECTO PARA EL MEDICAMENTO CON CÓDIGO "+codigosReceta[j];
						break;
					}
					else if(!IsNumeric(document.medicamentos.elements[i].value))
						{
							paso = true;
							mensajeerror = "EL FORMATO DE LA CANTIDAD ES INCORRECTO PARA EL MEDICAMENTO CON CÓDIGO "+codigosReceta[j];
							break;
						}
				if(document.medicamentos.sw_solucion[j].value == '1') liquido = true;
				adicionales += document.medicamentos.elements[i].id+"#"+document.medicamentos.elements[i].value+"#"+document.medicamentos.sw_solucion[j].value+"*";
				j++;
			} 
		}*/
	}
	else
	{
		mensajeerror = "LA SOLUCION DEBE COMPONERSE DE MAS DE UN MEDICAMENTO ";
		paso = true;
	}
	if(!liquido && !paso)
	{
		mensajeerror = "LA FORMULACIÓN DE LA NUEVA SOLUCION DEBE CONFORMARSE DE MAS DE UN MEDICAMENTO Y POR LOS MENOS UNA SOLUCION";
		paso = true;
	}
	return paso;
}

function ActualizarMenu(cadena)
{
	if(cadena != "")
	{
		window.opener.document.getElementById('Mezclas').innerHTML = cadena;
		window.close();
	}
}

function EvaluarFormulacion(codigo,ruta,profesional,conversion)
{
	mensaje = "";
	justificacion = "";
	formulacion = new Array();
	formulacion[0] = codigo;
	formulacion[1] = document.getElementById('dosis'+codigo).value; 
	formulacion[2] = CalcularCantidad(codigo,conversion)+"";
	formulacion[3] = document.getElementById('dosiscant'+codigo).value; 
	formulacion[4] = document.getElementById('viasadmin'+codigo).value;
	formulacion[5] = document.getElementById('medicamento'+codigo).value;
	formulacion[6] = document.getElementById('frecuenciadosis0'+codigo).value;
	formulacion[7] = ruta;
	
	if(profesional == '3')
		formulacion[8] = document.getElementById('profesionalid').value;
	else
		formulacion[8] = "''";
	
	formulacion[9] = "N";
	formulacion[10] = document.getElementById('tratamiento'+codigo).value;;
	formulacion[11] = document.getElementById('frecuencia_Numero'+codigo).value;;
	formulacion[12] = document.getElementById('frecuencia_Intensidad'+codigo).value;;
	
	try{
		justificacion = document.getElementById('sw_nopos').checked;
	}catch(error){}
	
	if(justificacion)
		formulacion[9] = document.getElementById('sw_nopos').value;
	
	if(formulacion[4] == "" || formulacion[4] == "0")
		mensaje = "SE DEBE SELECCIONAR LA VIA DE ADMINISTRACIÓN DEL MEDICAMENTO ";
	else if(formulacion[6] == "")
		mensaje = "FAVOR INGRESAR LA FRECUENCIA DEL MEDICAMENTO";
    else if(!IsNumeric(formulacion[3]))
      mensaje = "LA CANTIDAD, INGRESADA EN LA DOSIS NO ES VALIDA ";
      else if(formulacion[1] == "0")
        mensaje = "SE DEBE SELECCIONAR LA DOSIS DEL MEDICAMENTO ";
        else if(!IsNumeric(formulacion[2]))
          mensaje = "LA CANTIDAD INGRESADA NO ES VALIDA ";
          else if(!IsNumeric(formulacion[10]))
			      mensaje = "EL VALOR INGRESADO PARA LOS DIAS DE TRATAMIENTO NO ES VALIDO";
            else if(profesional == '3')
            {
              if(formulacion[8] == "" || formulacion[8] == "0")
                mensaje = "SE DEBE SELECCIONAR EL PROFESIONAL QUE AUTORIZA LA FORMULACION ";
            }

	if(mensaje != "")
		mensaje = "<br><b class=\"label_error\">"+mensaje+"</b><br>";
	
	document.getElementById('ErrorSolicitud').innerHTML = mensaje;
	paso = false;
	
	if(mensaje == "")
	{
		jsrsExecute("classes/modules/procesos.php",pintarTabla,"IngresarMedicamento",formulacion);
		paso = true;
		ocupado = 0;
	}
	if(paso)
		MensajeFinal('ErrorSolicitud','MEDICAMENTO FORMULADO CORRECTAMENTE');
}

function MensajeFinal(elemento,mensaje)
{
	document.getElementById(elemento).innerHTML = "<br><center><b class=\"label_mark\">"+mensaje+"</b></center>";
}

function FinalizarMedicamento(estado,codigo,i)
{
  datos = new Array();
	datos[0] = '';
	datos[1] = codigo;
	datos[2] = estado;
	datos[3] = "'"+i+"'";
	jsrsExecute("../modules/procesos.php",PintarFinalizado,"FinalizarMedicamento",datos);
}

function PintarFinalizado(cadena)
{
	resultado  = jsrsArrayFromString( cadena, "*" );
	document.getElementById('CapaFormula'+resultado[0]).innerHTML = resultado[1];
	
	e = document.getElementById('MedicamentosFinalizados');
	if(e.style.display == 'none') e.style.display = '';
}

function ActualizarFormulacionMedicamento(datosformula)
{
	jsrsExecute("ScriptsRemotos/medicamentos.php",ActualizarDatosForma,"ActualizarFormulacion",datosformula);
}

function pintarMedicamento(html)
{
  impresion = jsrsArrayFromString( html, "||" );
	document.getElementById('MedicamentosNuevos').innerHTML = impresion[0];
  
  if(impresion[1]) JustificarII(impresion[1]);
}

function JustificarII(codigo)
{
	var url="../FrecuenciaMedicamentos/Justificacion.class.php?codigo="+codigo;
	window.open(url,'Formulación','scrollbars=yes,fullscreen=yes');
}

function CrearResultado(html)
{
	if(html != "")
	{
		window.opener.document.getElementById('MedicamentosNuevos').innerHTML = html;
		window.close();
	}
}

function FinalizarSolucion(estado,num_mezcla,i)
{
	datos = new Array();
	datos[0] = '';
	datos[1] = num_mezcla;
	datos[2] = estado;
	datos[3] = i;
	jsrsExecute("classes/modules/procesos.php",PintarSolucionFinalizada,"FinalizarSolucion",datos);
}

function PintarSolucionFinalizada(cadena)
{
	resultado  = jsrsArrayFromString( cadena, "*" );
	document.getElementById('CapaSolucion'+resultado[0]).innerHTML = resultado[1];
	
	e = document.getElementById('MedicamentosFinalizados');
	if(e.style.display == 'none') e.style.display = 'block';
}

function CrearAntecedentes(arreglo)
{
	jsrsExecute("classes/modules/ScriptsRemotos/antecentes.php",ActualizarAntecedentes,"IngresarAntecente",arreglo);
}

function OcultarAntecedente(arreglo)
{
	jsrsExecute("classes/modules/ScriptsRemotos/antecentes.php",ActualizarAntecedentes,"OcultarAntecente",arreglo);
}

function OcultarAntecedenteFami(arreglo)
{
	jsrsExecute("classes/modules/ScriptsRemotos/antecentes.php",ActualizarAntecedentes,"OcultarAntecenteFami",arreglo);
}

function CrearAntecedentesFami(arreglo)
{
	jsrsExecute("classes/modules/ScriptsRemotos/antecentes.php",ActualizarAntecedentes,"IngresarAntecenteFami",arreglo);
}
var trid = '';
var trTitle = '';
function CrearIngresoDatos(arreglo,encabezado)
{
	trid = "Adictiva"+arreglo[0];
	trTitle = encabezado;
	jsrsExecute("classes/modules/ScriptsRemotos/antecentes.php",CrearAntecedenteT,"CrearAntecedenteT",arreglo);
}

function CrearAntecedenteT(html)
{
	document.getElementById('ContentS').innerHTML = html;
	MostrarSpan('ContenedorS');
	IniciarSustancias(trTitle);
}

function EvaluarConsumoT(objeto,metodo)
{
	mensaje = "";
	toxico = new Array();
	toxico[0] = objeto.patron.value; 
	toxico[1] = objeto.ultimosustancia.value;
	toxico[2] = objeto.problemasxconsumo.value; 
	toxico[3] = objeto.Einicio.value;
	toxico[4] = objeto.consumotiempo.value;
	toxico[5] = objeto.tiempoconsumotipo.value;
	toxico[6] = objeto.consecutivo.value;
	toxico[7] = objeto.sustanciaid.value;
		
	if(toxico[0] == '-1')
		mensaje = "SE DEBE SELECCIONAR EL PATRON DE CONSUMO";
	else if(toxico[1] == '-1')
		mensaje = "SE DEBE SELECCIONAR CUANDO FUE EL ULTIMO CONSUMO";
		else if(toxico[2] == '-1')
			mensaje = "SE DEBE SELECCIONAR LOS PROBLEMAS POR CONSUMO";
			else if(!IsNumeric(toxico[3]))
				mensaje = "LA EDAD INGRESADA NO ES VALIDA ";
				else if(!IsNumeric(toxico[4]))
					mensaje = "EL TIEMPO DE CONSUMO NO ES VALIDO";
	document.getElementById('errorS').innerHTML = '<center>'+mensaje+'</center>';
	
	if(mensaje == '')
		jsrsExecute("classes/modules/ScriptsRemotos/antecentes.php",ActualizarToxico,metodo,toxico);

} 

function ActualizarToxico(html)
{
	Cerrar('ContenedorS');
	document.getElementById(trid).innerHTML = html;
}

function EliminarInstitucion(id,institucion,j)
{
	html  = "<center><b class='label'>ESTA SEGURO QUE DESEA ELIMINAR EL PROGRAMA DE REHABILITACION "+j+" ";
	html += "PERTENECIENTE A LA INSTITUCIÓN:</b><b class='label_mark'> "+institucion+"</b><b>?</b>";
	html += "</center><br>";
	html += "<center>";
	html += "<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"EliminarInstitucionQ('"+id+"')\">&nbsp;&nbsp;&nbsp;\n";
	html += "<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Cancelar\" onclick=\"Cerrar('ContenedorS')\">\n";
	html += "</center><br>";

	document.getElementById('ContentS').innerHTML = html;
	MostrarSpan('ContenedorS');
	IniciarSustancias('ELIMINAR INSTITUCIÓN');
}

function EliminarInstitucionQ(id)
{
	Cerrar('ContenedorS');
	jsrsExecute("classes/modules/ScriptsRemotos/antecentes.php",ActualizarIntitucion,"EliminarInstitucion",id);
}

function ActualizarIntitucion(html)
{
	document.getElementById('Instituciones').innerHTML = html;
}

function IngresarInstitucion()
{
	html  = "<form name=\"programas\" action=\"\" method=\"post\">\n";
	html += "	<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n";
	html += "		<tr class=\"modulo_list_claro\">\n";
	html += "			<td class=\"modulo_table_list_title\"  style=\"text-align:left\">INSTITUCIÓN</td>\n";
	html += "			<td align=\"center\" colspan=\"2\">\n";
	html += "				<input type\"text\" style=\"width:100%\" name=\"Institucion\" maxlength=\"100\" class=\"input-text\">\n";
	html += "			</td>\n";
	html += "		</tr>\n"
	html += "		<tr class=\"modulo_list_claro\">\n";
	html += "			<td class=\"modulo_table_list_title\" style=\"text-align:left\">ESTANCIA:</td>\n";
	html += "			<td>\n";
	html += "				<input type=\"text\" name=\"estanciatexto\"  maxlength=\"3\" size=\"5\" class=\"input-text\" onkeypress=\"return acceptNum(event)\">\n";
	html += "			</td>\n";
	html += "			<td>\n";
	html += "				<select name=\"tiempo\" class=\"select\">\n";
	html += "					<option value=\"A\">Años</option>\n";
	html += "					<option value=\"M\">Meses</option>\n";
	html += "					<option value=\"D\">Dias</option>\n";
	html += "				</select>\n";
	html += "			</td>\n";
	html += "		</tr>\n"
	html += "		<tr class=\"modulo_list_claro\">\n";
	html += "			<td align=\"center\" colspan=\"3\">\n";
	html += "				<input type=\"button\" name=\"INSERTAR\" value=\"Adicionar\" class=\"input-submit\" onclick=\"AdicionarPrograma(document.programas)\">\n";
	html += "			</td>\n";
	html += "		</tr>\n";
	html += "	</table>\n";
	html += "</form>\n";

	document.getElementById('ContentS').innerHTML = html;
	MostrarSpan('ContenedorS');
	IniciarSustancias('ADICIONAR INSTITUCIÓN');	
}

function AdicionarPrograma(objeto)
{
	mensaje = "";
	programa = new Array();
	programa[0] = objeto.Institucion.value; 
	programa[1] = objeto.estanciatexto.value;
	programa[2] = objeto.tiempo.value; 
		
	if(programa[0] == '')
		mensaje = "SE DEBE INGRESAR EL NOMBRE DE LA INSTITUCIÓN";
		else if(!IsNumeric(programa[1]))
			mensaje = "LA CANTIDAD INGRESADA EN LA ESTANCIA ES INCORRECTA ";

	document.getElementById('errorS').innerHTML = '<center>'+mensaje+'</center>';
	
	if(mensaje == '')
	{
		Cerrar('ContenedorS');
		jsrsExecute("classes/modules/ScriptsRemotos/antecentes.php",ActualizarIntitucion,"AdicionarInstitucion",programa);
	}
}

function CrearAntecedentesGineco(arreglo)
{
	jsrsExecute("classes/modules/ScriptsRemotos/antecentesgineco.php",ActualizarAntecedentes,"IngresarAntecenteGineco",arreglo);
}

function CrearAntecedentesGinecoPyp(arreglo)
{
	jsrsExecute("classes/modules/ScriptsRemotos/antecentesgineco.php",AntecenteGinecoPyp,"IngresarAntecenteGinecoPyp",arreglo);
}

function OcultarAntecedenteGineco(arreglo)
{
	jsrsExecute("classes/modules/ScriptsRemotos/antecentesgineco.php",ActualizarAntecedentes,"OcultarAntecenteGineco",arreglo);
}

function SolicitudesInterconsulta(arreglo)
{
	jsrsExecute("classes/modules/InterCPN/Inter.php",VInterconsultas,"SolicitudInter",arreglo);
}

function CalcularCantidad(key,conversion)
{
  fnumero = document.getElementById('frecuencia_Numero'+key).value;
  fintensidad = document.getElementById('frecuencia_Intensidad'+key).value;
  
  dosis = document.getElementById('dosis'+key).value;
  dosiscantidad = document.getElementById('dosiscant'+key).value;

  factor = 1
  try
  {  
    if(!IsNumeric(dosiscantidad))
      return "0";
   
    if(fnumero == '' || fintensidad == '')
      return "0";
    
    for(i=0; i<conversion.length; i++)
    {
      if(conversion[i][0] == dosis)
        factor = conversion[i][1]*1;
    }
  }
  catch(error){}
  
  if(fintensidad == "Hora(s)")
    cantidad = (dosiscantidad*1) * 24/(fnumero*1);
  else if(fintensidad == "Minuto(s)")
    cantidad = (dosiscantidad*1) * 24/((fnumero*1)/60);
    else
      cantidad = dosiscantidad*1;
  
  conve = cantidad/factor;
  valor = Math.round(conve);
  if(valor < conve) valor++;
  
  return valor;
}