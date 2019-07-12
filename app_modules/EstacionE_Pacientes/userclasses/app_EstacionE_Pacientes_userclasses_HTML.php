
<?php

/**
 * $Id: app_EstacionE_Pacientes_userclasses_HTML.php,v 1.22 2005/08/19 19:02:17 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria (parte del tratamiento del paciente) 
 */


/**
* Modulo de EstacionE_Pacientes (PHP).
*
//*
*
* @author  <@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_EstacionE_Pacientes_userclasses_HTML.php
*
//*
**/

class app_EstacionE_Pacientes_userclasses_HTML extends app_EstacionE_Pacientes_user
{
	function app_EstacionE_Pacientes_user_HTML()
	{
		$this->app_EstacionE_Pacientes_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}




/**********************esta va para estacionE_Paciente******************************/
	/**
	*		ListPacientesPorIngresar => vista de un listado de los pacientes pendientes por ingresar al departamento
	*
	*		llamado desde el subproceso 1->"Asignar cama" del proceso "ingreso de pacientes a la estaci&oacute;n de enfermer&iacute;a"
	*		llamado desde el subproceso 2->"Cambio estacion de enfermeria antes del ingreso al dpto" del proceso "ingreso de pacientes a la estaci&oacute;n de enfermer&iacute;a"
	*		Vista 1 => 1.1.1.H => ListPacientesPorIngresar lista los pacientes que se encuentran en la tabla "pendientes_x_hospitalizar"
	*		con los datos retornados en la matriz $pacientes en la funcion "GetPacientesPendientesXHospitalizar"
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array datos de la estacion
	* 	@return boolean
	*/
	function ListPacientesPorIngresar($datos_estacion,$tipo,$pac)
	{
		$pacientes = $this->GetPacientesPendientesXHospitalizar($datos_estacion,$tipo,$pac);
  	if($pacientes === "ShowMensaje")
		{
			$mensaje = "DATOS GUARDADOS SATISFACTORIAMENTE ! ";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MEN&Uacute; ESTACION";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		if(is_array($pacientes))
		{
			$this->salida .= ThemeAbrirTabla('LISTADO DE PACIENTES POR INGRESAR - [ '.$datos_estacion[descripcion5].' ]')."<BR>";
			$this->salida .= "<table width=\"100%\" cellpadding=\"2\">";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
			$this->salida .= "</table><br>\n";
			$this->salida .= "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td colspan=\"8\">PACIENTES POR INGRESAR</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td>PACIENTE</td>\n";
			$this->salida .= "		<td>IDENTIFICACION</td>\n";
			$this->salida .= "		<td>CUENTA</td>\n";
			$this->salida .= "		<td>VIA INGRESO</td>\n";
			$this->salida .= "		<td>ESTACION ORIGEN</td>\n";
			$this->salida .= "		<td colspan=\"3\">ACCIONES</td>\n";
			$this->salida .= "	</tr>\n";

			for($i=0; $i<sizeof($pacientes); $i++)
			{
				$viaIngreso = $this->GetViaIngresoPaciente($pacientes[$i][4]);//le envio el ingreso
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "<tr class=\"$estilo\">\n";
				//$linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$pacientes[$i][4],"retorno"=>"CallListPacientesPorIngresar","datos_estacion"=>$datos_estacion));
				$this->salida .= "	<td nowrap>".$pacientes[$i][0]." ".$pacientes[$i][1]."</td>\n";
				$this->salida .= "	<td align=\"center\">".$pacientes[$i][3]." ".$pacientes[$i][2]."</td>\n";
				$this->salida .= "	<td align=\"center\">".$pacientes[$i][6]."</td>\n";
				$this->salida .= "	<td align=\"center\">".$viaIngreso[via_ingreso_nombre]."&nbsp;</td>\n";
				$this->salida .= "	<td align=\"center\">".$pacientes[$i][9]."&nbsp;</td>\n";
				$linkIngresar = ModuloGetURL('app','EstacionE_Pacientes','user','CallListadoCamas',array("datos"=>$pacientes[$i],"datos_estacion"=>$datos_estacion));
				$linkRemitir = ModuloGetURL('app','EstacionE_Pacientes','user','CallListadoEstaciones',array("datos"=>$pacientes[$i],"datos_estacion"=>$datos_estacion));
				$linkCancelar = ModuloGetURL('app','EstacionE_Pacientes','user','CallFrmCancelarPendientePorHospitalizar',array("datos"=>$pacientes[$i],"datos_estacion"=>$datos_estacion));
				$retorno = ModuloGetURL('app','EstacionE_Pacientes','user','CallListPacientesPorIngresar');
				$linkVerAutorizacion = ModuloGetURL('app','Autorizacion','user','BuscarDetalleAutorizaciones',array("IngresoId"=>$pacientes[$i][4],"accion"=>$retorno));
				$link = ModuloGetURL('app','EstacionE_Pacientes','user','CallReservaCama',array("datos"=>$pacientes[$i],"datos_estacion"=>$datos_estacion));

				$this->salida .= "	<td align=\"center\"><a href=\"$linkIngresar\">ASIGNAR CAMA</a></td>\n"; //envio al caso 1.1.2
				$this->salida .= "	<td align=\"center\"><a href=\"$linkRemitir\">REMITIR A EE</a></td>\n";  //envio al caso 1.2.1
				//$this->salida .= "	<td align=\"center\"><a href=\"$linkCancelar\">CANCELAR</a></td>\n";
				$this->salida .= "	<td align=\"center\">RESERVAR CAMA</td>\n";
				//$this->salida .= "	<td align=\"center\"><a href='$link' >RESERVAR CAMA</a></td>\n";//<a href=\"$linkVerAutorizacion\"></a>
				$this->salida .= "</tr>\n";
			}
			$this->salida .= "</table><br>\n";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
			$this->salida .= themeCerrarTabla();
		}//pacientes por ingresar
		return true;
	}//fin ListPacientesPorIngresar
	/**********************esta va para estacionE_Paciente******************************/


/*
		*		FrmCancelarPendientePorHospitalizar
		*
		*		Formulario que permite confirmar la cancelacion de una orden de hospitalizacion
		*		antes del ingreso a la estacion
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@param array datos de la orden
		*		@param array datos de la estacion
		*		@return bool
		*/
		function FrmCancelarPendientePorHospitalizar($datos,$datos_estacion)
		{
			$this->salida .= "<form name=\"CancelHospitalizacion\" method=\"POST\" action=\"$action\"><br>\n";
			$this->salida .= ThemeAbrirTabla("CONFIRMACION DE CANCELACION DE ORDEN DE HOSPITALIZACION")."<br>";
			$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
			$this->salida .= "		<tr align='center' class='label'>\n";
			$this->salida .= "			<td><font size='2'><b>¿REALMENTE DESEA CANCELAR LA ORDEN DE HOSPITALIZACION Nº <label class='label_mark'><font size='2'>".$datos[5]."</font></label><br>CORRESPONDIENTE AL PACIENTE <label class='label_mark'><font size='2'>&nbsp;".$datos[0]." ".$datos[1]."</font></label> ?</b></font></td>\n";
			$this->salida .= "		</tr>\n";
			$hrefAceptar  = ModuloGetURL('app','EstacionE_Pacientes','user','CancelarPendientePorHospitalizar',array("orden_hosp"=>$datos[5],"datos_estacion"=>$datos_estacion));
			$hrefCancelar = ModuloGetURL('app','EstacionE_Pacientes','user','CallListPacientesPorIngresar',array("datos_estacion"=>$datos_estacion));
			$this->salida .= "		<tr align='center' class='label'>\n";
			$this->salida .= "			<td><br><br><a href=\"$hrefAceptar\"><font size='2'><b>SI</b></font></a> &nbsp; &nbsp; &nbsp;<a href=\"$hrefCancelar\"><font size='2'><b>NO</b></font></a></td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}//FrmCancelarPendientePorHospitalizar



	 /*****************esta funcion debe ir a estacionE_pacientes ******************************/
	/**
	*		ListPacientesPorIngresar
	*
	*		Muestra los pacientes que tienen orden egreso pendiente
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array datos dela estacion
	* 	@return boolean
	*/



/*
//funcion original
	function ListPacientesPorEgresar($datos_estacion,$tipo,$pac)
	{
		$egresos = $this->GetPacientesPendientesXEgresar($datos_estacion,$tipo,$pac);

		if(is_array($egresos))
		{
			$this->salida .= ThemeAbrirTabla('LISTADO DE PACIENTES POR EGRESAR - [ '.$datos_estacion[descripcion5].' ]')."<BR>";
			$this->salida .= "<table align='center' width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=modulo_table_list>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td colspan=\"8\">PACIENTES POR EGRESAR</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td colspan='2'>PACIENTE</td>\n";
			$this->salida .= "		<td>IDENTIFICACION</td>\n";
			$this->salida .= "		<td>TIPO EGRESO</td>\n";
			$this->salida .= "		<td>FECHA<br>ORDEN EGRESO</td>\n";
			$this->salida .= "		<td>OBSERVACION</td>\n";
			$this->salida .= "		<td>ACCIONES</td>\n";
			$this->salida .= "		<td>RESUMEN HC</td>\n";
			$this->salida .= "	</tr>\n";
			$i=0;
			$reporte= new GetReports();
			foreach($egresos as $key => $value)
			{
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "<tr class=\"$estilo\">\n";
				$this->salida .= "	<td nowrap>\n";
				if($value['estado'] == '0'){
					$this->salida .= "	<img src='".GetThemePath()."/images/EstacionEnfermeria/egreso.png' width=18 height=18 align='middle' border=0>\n";;
				}
				else{
					$this->salida .= "	&nbsp;\n";
				}
				$this->salida .= "	</td>\n";
				$this->salida .= "	<td nowrap>".$value['primer_nombre']." ".$value['segundo_nombre']." ".$value['primer_apellido']." ".$value['segundo_apellido']."</td>\n";
				$this->salida .= "	<td align=\"center\">".$value['tipo_id_paciente']." ".$value['paciente_id']."</td>\n";
				$this->salida .= "	<td align=\"center\">".$value['descegreso']."</td>\n";
				$this->salida .= "	<td align=\"center\">".$value['fecha_egreso']."</td>\n";
				$this->salida .= "	<td align=\"center\">".$value['observacion']."</td>\n";
				$this->salida .= "	<td align=\"center\">\n";

				$bodegaPaciente = $this->VerificarExistenciasBodegaPaciente($value['ingreso']);
				unset($solicitudesDevPendientes);
				$solicitudesDevPendientes = $this->VerificaSolicitudesDevolucionPendientes($value['ingreso']);

				if($value['tipo_egreso'] == 5)//Traslado dpto
				{
					//obtengo el departamento al que remite el medico
					$dptoRemision = $this->GetDptoRemision($value['egreso_dpto_id']);
					if(!$dptoRemision){
						return false;
					}
					elseif($dptoRemision === "ShowMensaje"){
						$this->salida .= "No se encontr&oacute; el departamento al cual<br>se debe remitir especificado<br> por el m&eacute;dico\n";
					}
					else
					{
						if($value['estado'] == '1'){//ya la enfermera hizo la orden
							$this->salida .= "	Pendiente Traslado a ".$dptoRemision['descripcion']."\n";
						}
						else
						{
							if($solicitudesDevPendientes === "DevPendiente"){
								$this->salida .= "El paciente tiene solicitudes de devolucion pendientes por despachaar de bodega.\n";
							}
							elseif($bodegaPaciente === "ShowMensaje")
							{
								$value['departamento'] = $dptoRemision['departamento'];
								$value['descDepartamento'] = $dptoRemision['descripcion'];
								$link = ModuloGetURL('app','EstacionE_Pacientes','user','trasladarDpto',array("datosRemision"=>$value,"datos_estacion"=>$datos_estacion));
								$this->salida .= "	<a href=\"$link\">Trasladar</a>\n";
							}
							elseif(is_array($bodegaPaciente))
							{
								$link = ModuloGetURL('app','EstacionE_Pacientes','user','EgresosDevolucionMedicamentos',array("ingreso"=>$value['ingreso'],"vectorMedicamentos"=>$bodegaPaciente,"datos_estacion"=>$datos_estacion));
								$this->salida .= "	<a href=\"$link\">Solicitar Devolucion Medicamentos</a>\n";
							}
						}
					}
				}
				elseif($value['tipo_egreso'] == 1)
				{//remision externa
					if($value['estado'] == '1'){//ya la enfermera hizo la remision externa
						$this->salida .= "	Pendiente liquidaci&oacute;n de cuenta\n";
					}
					else
					{
						if($solicitudesDevPendientes === "DevPendiente"){
								$this->salida .= "El paciente tiene solicitudes de devolucion pendientes por despachar de bodega.\n";
						}
						elseif($bodegaPaciente === "ShowMensaje")
						{
							$link = ModuloGetURL('app','EstacionE_Pacientes','user','CallFrmRemisionExterna',array("datosRemision"=>$value,"datos_estacion"=>$datos_estacion));
							$this->salida .= "	<a href=\"$link\">Remitir</a>\n";
						}
						elseif(is_array($bodegaPaciente))
						{
							$link = ModuloGetURL('app','EstacionE_Pacientes','user','EgresosDevolucionMedicamentos',array("ingreso"=>$value['ingreso'],"vectorMedicamentos"=>$bodegaPaciente,"datos_estacion"=>$datos_estacion));
							$this->salida .= "	<a href=\"$link\">Solicitar Devolucion Medicamentos</a>\n";
						}
					}
				}
				elseif($value['tipo_egreso'] == 2)
				{//Morgue
					if($value['estado'] == '1'){//ya la enfermera hizo la SOLICITUD DEE CANCELACION DE CUENTA
						$this->salida .= "	Pendiente liquidaci&oacute;n de cuenta\n";
					}
					else
					{
						if($solicitudesDevPendientes === "DevPendiente"){
								$this->salida .= "El paciente tiene solicitudes de devolucion pendientes por despachar de bodega.\n";
						}
						elseif($bodegaPaciente === "ShowMensaje")
						{
							$link = ModuloGetURL('app','EstacionE_Pacientes','user','DarSalidaPaciente',array("datosRemision"=>$value,"datos_estacion"=>$datos_estacion));
							$this->salida .= "	<a href=\"$link\">Dar salida</a>\n";
						}
						elseif(is_array($bodegaPaciente))
						{
							$link = ModuloGetURL('app','EstacionE_Pacientes','user','EgresosDevolucionMedicamentos',array("ingreso"=>$value['ingreso'],"vectorMedicamentos"=>$bodegaPaciente,"datos_estacion"=>$datos_estacion));
							$this->salida .= "	<a href=\"$link\">Solicitar Devolucion Medicamentos</a>\n";
						}
					}
				}
				if($value['tipo_egreso'] == 3)
				{//alta
					if($value['estado'] == '1'){//ya la enfermera hizo la SOLICITUD DEE CANCELACION DE CUENTA
						$this->salida .= "	Pendiente liquidaci&oacute;n de cuenta\n";
					}
					else
					{
						if($solicitudesDevPendientes === "DevPendiente"){
								$this->salida .= "El paciente tiene solicitudes de devolucion pendientes por despachar de bodega.\n";
						}
						elseif($bodegaPaciente === "ShowMensaje")
				{
							$link = ModuloGetURL('app','EstacionE_Pacientes','user','DarSalidaPaciente',array("datosRemision"=>$value,"datos_estacion"=>$datos_estacion));
							$this->salida .= "	<a href=\"$link\">Dar de alta</a>\n";
						}
						elseif(is_array($bodegaPaciente))
						{
							$link = ModuloGetURL('app','EstacionE_Pacientes','user','EgresosDevolucionMedicamentos',array("ingreso"=>$value['ingreso'],"vectorMedicamentos"=>$bodegaPaciente,"datos_estacion"=>$datos_estacion));
							$this->salida .= "	<a href=\"$link\">Solicitar Devolucion Medicamentos</a>\n";
						}
					}
				}
				$this->salida .= "	</td>\n";

				//nuevo.....
				$this->salida .= "	<td>\n";
				$this->salida.=$reporte->GetJavaReport_HC($value['ingreso'],array());
				$funcion=$reporte->GetJavaFunction();
				$this->salida .= "	<a href=\"javascript:$funcion\">RESUMEN HC</a>\n";
				$this->salida .= "	</td>\n";
				$this->salida .= "</tr>\n";
				$i++;
			}
			$this->salida .= "</table><br>\n";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
			$this->salida .= themeCerrarTabla();
		}//pacientes por egresar
		return true;
	}//fin ListPacientesPorEgresar
*/



	//funcion de prueba para la clinica de occidente de cali
	function ListPacientesPorEgresar($datos_estacion,$tipo,$pac,$cama)
	{
		$egresos = $this->GetPacientesPendientesXEgresar($datos_estacion,$tipo,$pac);
		if($egresos === "ShowMensaje")
		{
			$mensaje = "NO HAY PACIENTES PENDIENTES POR EGRESAR DE LA ESTACI&Oacute;N '".$datos_estacion[descripcion5]."'";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MEN&Uacute; ESTACION";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}

		if(is_array($egresos))
		{
			$this->salida .= ThemeAbrirTabla('LISTADO DE PACIENTES POR EGRESAR - [ '.$datos_estacion[descripcion5].' ]')."<BR>";
			$this->salida .= "<table align='center' width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=modulo_table_list>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td colspan=\"7\">PACIENTES POR EGRESAR</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td colspan='2'>PACIENTE</td>\n";
			//$this->salida .= "		<td>IDENTIFICACION</td>\n";
			$this->salida .= "		<td width=\"10%\">TIPO EGRESO</td>\n";
			$this->salida .= "		<td width=\"20%\">FECHA<br>ORDEN EGRESO</td>\n";
			$this->salida .= "		<td width=\"40%\" >OBSERVACION</td>\n";
			$this->salida .= "		<td width=\"10%\">ACCIONES</td>\n";
			$this->salida .= "		<td width=\"10%\" >RESUMEN HC</td>\n";
			$this->salida .= "	</tr>\n";
			$i=0;
			$reporte= new GetReports();
			foreach($egresos as $key => $value)
			{
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "<tr class=\"$estilo\">\n";
				$this->salida .= "	<td nowrap>\n";
				if($value['estado'] == '0'){
					$this->salida .= "	<img src='".GetThemePath()."/images/atencion_citas.png' width=18 height=18 align='middle' border=0>\n";
				}
				else{
					$this->salida .="<img src='".GetThemePath()."/images/servicios.png' width=18 height=18 align='middle' border=0>\n";
				}
				$this->salida .= "	</td>\n";
				$this->salida .= "	<td nowrap>".$value['primer_nombre']." ".$value['segundo_nombre']." ".$value['primer_apellido']." ".$value['segundo_apellido']."<br><label class='label_mark'>".$value['tipo_id_paciente']." ".$value['paciente_id']."</label></td>\n";
				//$this->salida .= "	<td align=\"center\">".$value['tipo_id_paciente']." ".$value['paciente_id']."</td>\n";
				$this->salida .= "	<td align=\"center\">".$value['descegreso']."</td>\n";
				$this->salida .= "	<td align=\"center\">".$value['fecha_egreso']."</td>\n";
				$this->salida .= "	<td align=\"center\">".$value['observacion']."</td>\n";
				$this->salida .= "	<td align=\"center\">\n";

				//$bodegaPaciente = $this->VerificarExistenciasBodegaPaciente($value['ingreso']);
				//unset($solicitudesDevPendientes);
				//$solicitudesDevPendientes = $this->VerificaSolicitudesDevolucionPendientes($value['ingreso']);
				$conteo_evolucion=$this->BuscarEvolucion_Pac($value['ingreso']);//revisemos si tiene evoluciones abiertas.



				if($conteo_evolucion < 1)
				{
					$link = ModuloGetURL('app','EstacionE_Pacientes','user','DarSalida',array("cama"=>$cama,"egreso_dpto_id"=>$value['egreso_dpto_id'],"ingreso"=>$value['ingreso'],"tipo_id"=>$tipo,"pac"=>$pac,"datos_estacion"=>$datos_estacion));
					$this->salida .= "	<a href=\"$link\">Dar de alta</a>\n";
				}
				else
				{
							$this->salida .= "	<label class='label_mark'>Advertencia</label>\n";
				}

				$this->salida .= "	</td>\n";

				//nuevo.....
				$this->salida .= "	<td>\n";
				$this->salida.=$reporte->GetJavaReport_HC($value['ingreso'],array());
				$funcion=$reporte->GetJavaFunction();
				$this->salida .= "	<a href=\"javascript:$funcion\">RESUMEN HC</a>\n";
				$this->salida .= "	</td>\n";
				$this->salida .= "</tr>\n";
				$i++;
			}

			if($conteo_evolucion < 1)
			{
					$linknota = ModuloGetURL('app','EstacionE_Pacientes','user','Insertar_Nota_Enfermeria',array("datos_estacion"=>$datos_estacion,"tipo_id"=>$tipo,"pac"=>$pac,"cama"=>$cama));
					$this->salida .= "<form name=forma method=\"POST\" action=$linknota>	<tr><td class='$estilo' colspan=2 align='center'><input  type=hidden name='ingreso' value=".$value['ingreso']."><input  class='input-submit' type=submit name='enviar' value='Guardar Información'></td><td class='$estilo' colspan='5'><textarea name='obs'  cols=80 rows=8>".$_REQUEST['obs']."</textarea><sub><b>NOTA FINAL</b></sub>&nbsp;&nbsp;&nbsp;&nbsp;\n";
					$this->salida .= "	</td></tr></form>\n";
					$this->salida .= "</table><br>\n";
			}
			else
			{
					$this->salida .= "<tr align='center'><td class='$estilo' colspan=2 align='center'></td><td class='$estilo' colspan='5'><label class='label_mark'>NO SE PUEDE SACAR EL PACIENTE DEBIDO A QUE TIENE EVOLUCIONES ABIERTAS !</label>\n";
					$this->salida .= "	</td></tr>\n";
					$this->salida .= "</table><br>\n";

			}

			if($conteo_evolucion >= 1)
			{
				$datos=$this->BuscarEvolucion_Pac($value['ingreso'],1);//revisemos si tiene evoluciones abiertas.
				$this->salida.="<table  align=\"center\" border=\"0\" width=\"90%\">";
				$this->salida.="<tr class=\"modulo_table_list_title \">";
				$this->salida.="  <td colspan='4'>INFORMACION DE EVOLUCIONES ABIERTAS</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_table_list_title \">";
				$this->salida.="  <td>No.EVOLUCION</td>";
				$this->salida.="  <td>ESPECIALIDAD</td>";
				$this->salida.="  <td>PROFESIONAL</td>";
				$this->salida.="  <td>FECHA</td>";
				$this->salida.="</tr>";
				for($i=0;$i<sizeof($datos);$i++)
				{
								$rcaja=$datos[$i][recibo_caja];
								$empresa=$datos[$i][empresa_id];
								$centro=$datos[$i][centro_utilidad];
								$fech=$datos[$i][fecha_registro];
								
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); align=\"center\">";
						  	$this->salida.="<td>".$datos[$i][evolucion_id]."</td>";
								$this->salida.="  <td>".$datos[$i][descripcion]."</td>";
								$this->salida.="  <td>".$datos[$i][nombre]."</td>";
								$this->salida.="  <td>".$this->FormateoFechaLocal($datos[$i][fecha])."</td>";
								$this->salida.="</tr>";
				}
				$this->salida.="</table>";

			}
			
			
			

			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
			$this->salida .= themeCerrarTabla();
		}//pacientes por egresar

		return true;
	}//fin ListPacientesPorEgresar

	/*****************esta funcion debe ir a estacionE_pacientes ******************************/


	/*
	*		FrmRemisionExterna
	*
	*		Muestra un listado de los centros de remision a los que puede ser remitido un paciente
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array datos de la remision
	*		@param array datos de la estacion
	*		@return bool
	*/
	function FrmRemisionExterna($datosRemision,$datos_estacion)
	{
		$centrosRemision = $this->GetCentrosRemision();


	  	if($centrosRemision === "ShowMensaje")
		{
			$mensaje = "NO SE ENCONTRARON CENTROS DE REMISI&Oacute;N";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MEN&Uacute; ESTACION";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		if(is_array($centrosRemision))
		{
			$linkIngresar = ModuloGetURL('app','EstacionE_Pacientes','user','RemitirPacienteAcentroRemision',array("datos_estacion"=>$datos_estacion));
			$this->salida .= "<form name=\"FrmRemitirCentro\" method=\"POST\" action=\"$linkIngresar\"><br>\n";
			$this->salida .= ThemeAbrirTabla('LISTADO DE CENTROS DE REMISION')."<BR>";
			$this->salida .= "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td>CENTRO</td>\n";
			$this->salida .= "		<td>NIVEL</td>\n";
			$this->salida .= "		<td>PAIS</td>\n";
			$this->salida .= "		<td>DPTO</td>\n";
			$this->salida .= "		<td>MUNICIPIO</td>\n";
			$this->salida .= "		<td>REMITIR</td>\n";
			$this->salida .= "	</tr>\n";
			$i=0;
			foreach($centrosRemision as $key => $value)
			{
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "<tr class=\"$estilo\">\n";
				$this->salida .= "	<td nowrap>".$value['descripcion']."</td>\n";
				$this->salida .= "	<td align='center'>".$value['nivel']."</td>\n";
				$this->salida .= "	<td>".$value['pais']."</td>\n";
				$this->salida .= "	<td>".$value['dpto']."</td>\n";
				$this->salida .= "	<td>".$value['mpio']."</td>\n";
				$link = ModuloGetURL('app','EstacionE_Pacientes','user','trasladarDpto',array("datosRemision"=>$value,"datos_estacion"=>$datos_estacion));
				$this->salida .= "	<td align=\"center\"><input type='radio' name='RemitirAcentro' value='".$datosRemision['egreso_dpto_id'].".-.".$value['centro_remision']."'></td>\n";
				$this->salida .= "</tr>\n";
				$i++;
			}
			$this->salida .= "</table><br><br>\n";
			$this->salida .= "<div class='normal_10' align='center'><br><input type='submit' class='input-submit' name='submit' value='REMITIR A CENTRO SELECCIONADO'>";
			$this->salida .= "</form>\n";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
			$this->salida .= themeCerrarTabla();
		}
		return true;
	}//FrmRemisionExterna


	

  /*FUNCION QUE VA EN EL MOD ESTACIONE_PACIENTES*/
	/**
	*		ListadoPacientesEstacion => vista de los pacientes de la estaci&oacute;n
	*
	*		subproceso 3->"CambioCama" del proceso "ingreso de pacientes a la estaci&oacute;n de enfermer&iacute;a"
	* 	Vista 5 => ListadoPacientesEstacion lista los pacientes de la estacion
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@return boolean
	*/
	function ListadoPacientesEstacion($datos_estacion)
	{

 		$pacientes = $this->GetPacientesEstacion($datos_estacion[estacion_id]);
		$pacUrgencias = $this->GetPacientesUrgencias($datos_estacion[estacion_id]);

		if($pacientes ===  "ShowMensaje" && $pacUrgencias === "ShowMensaje")
		{
			$mensaje = "NO HAY PACIENTES EN LA ESTACION";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MEN&Uacute; ESTACION";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}

		$this->salida .= ThemeAbrirTabla('LISTADO DE PACIENTES DE LA ESTACION - [ '.$datos_estacion[descripcion5].' ]');
		if($pacientes != "ShowMensaje")
		{
			$mostrar ="\n<script language='javascript'>\n";
			$mostrar.="function mOvr(src,clrOver) {;\n";
			$mostrar.="src.style.background = clrOver;\n";
			$mostrar.="}\n";

			$mostrar.="function mOut(src,clrIn) {\n";
			$mostrar.="src.style.background = clrIn;\n";
			$mostrar.="}\n";
			$mostrar.="</script>\n";
			$this->salida .="$mostrar";
			$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

			$this->salida .= "<table width=\"100%\" cellpadding=\"2\" border=\"0\">";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><a href='".$href."'>Volver al Menu Estaci&oacute;n</a>";
			$this->salida .= "</table>\n";
			$this->salida .= "<br><table width=\"100%\" cellpadding=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\"><td colspan='8' height='30'>PACIENTES OBSERVACION</td></tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td colspan='2'>PACIENTE</td>\n";
			$this->salida .= "		<td>IDENTIFICACION</td>\n";
			$this->salida .= "		<td>HABITACION</td>\n";
			$this->salida .= "		<td>CAMA</td>\n";
			$this->salida .= "		<td>CUENTA</td>\n";
			$this->salida .= "		<td colspan=\"2\">ACCION</td>\n";
			$this->salida .= "	</tr>\n";


				$vector_ingresos=array();//reiniciamos el vector q va a comparar los ingresos.

			for($i=0; $i<sizeof($pacientes); $i++)
			{

			  $conteo_salida=$this->VerificarSalida($pacientes[$i][10]);
				if($conteo_salida==='0')
				{

					if(in_array($pacientes[$i][4], $vector_ingresos)==FALSE)
					{
							if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
							$this->salida .= "<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
							$this->salida .= "	<td  width=18 height=18 align=\"center\">\n";
							//consultar con el ingreso si el paciente tiene pendiente traslado
							$Traslado = $this->VerificaEgresoPendientePaciente($pacientes[$i][4]);
							if($Traslado['estado'] == 0){//tiene egreso pendiente
								$this->salida .= "		<img src='".GetThemePath()."/images/honorarios.png' width=18 height=18 align='middle' border=0>\n";
							}
							elseif($Traslado['tipo_egreso']==5 && $Traslado['estado'] == 1){//pendiente traslado dpto
								$this->salida .= "		PT\n";
							}
							elseif($Traslado['tipo_egreso']==1 && $Traslado['estado'] == 1){//pendiente liquidar cuenta centro remi
								$this->salida .= "		PS\n";
							}
							elseif($Traslado['tipo_egreso']==2 && $Traslado['estado'] == 1){//pendiente morgue
								$this->salida .= "		PS\n";
							}
							elseif($Traslado['tipo_egreso']==3 && $Traslado['estado'] == 1){//pendiente liquidar cuenta alta
								$this->salida .= "		PS\n";
							}
							elseif($Traslado === "ShowMensaje"){//NO tiene egreso pendiente
								$this->salida .= "		&nbsp;\n";
							}
							$this->salida .= "	</td>\n";
							$linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$pacientes[$i][4],"retorno"=>"CallListadoPacientesEstacion","datos_estacion"=>$datos_estacion));
							$this->salida .= "	<td nowrap><a href=\"$linkVerDatos\">".$pacientes[$i][0]." ".$pacientes[$i][1]."</a></td>\n";
							$this->salida .= "	<td align=\"center\">".$pacientes[$i][3]." ".$pacientes[$i][2]."</td>\n";
							$this->salida .= "	<td align=\"center\">".$pacientes[$i][9]."</td>\n";
							$this->salida .= "	<td align=\"center\">".$pacientes[$i][8]."</td>\n";
							$this->salida .= "	<td align=\"center\">".$pacientes[$i][6]."</td>\n";

							$x = $this->GetDatosTrasladado($pacientes[$i][5]);
							if(sizeof($x))
							{//el paciente est&aacute; pendiente de ser ingresado en la estacion a la cual fue trasladado
								//$link = "<img src=\"". GetThemePath() ."/images/checkS.gif\" width=15 heigh=15 border='0'>&nbsp;<a href=\"".ModuloGetURL('app','EstacionE_Pacientes','user','ShowDatosTraslado',array("paciente"=>$pacientes[$i],"datos"=>$x,"datos_estacion"=>$datos_estacion))."\">TRASLADO EE</a>";
								$link = "<img src=\"". GetThemePath() ."/images/checkS.gif\" width=15 heigh=15 border='0'>&nbsp;TRASLADO EE";

							}
							else //muestra el link que permite trasladar al paciente
							{
								$href = ModuloGetURL('app','EstacionE_Pacientes','user','CallListadoEstaciones',array("SwTrasladoEE"=>'1',"datos"=>$pacientes[$i],"datos_estacion"=>$datos_estacion));
								//$link = "<img src=\"". GetThemePath() ."/images/uf.png\" width=15 heigh=15 border='0'>&nbsp;<a href=\"$href\">TRASLADO EE</a>";
								$link = "<img src=\"". GetThemePath() ."/images/uf.png\" width=15 heigh=15 border='0'>&nbsp;TRASLADO EE";
								$cambioCama = ModuloGetURL('app','EstacionE_Pacientes','user','CallListadoCamas',array("SwCambioCama"=>'1',"datos"=>$pacientes[$i],"datos_estacion"=>$datos_estacion));
								$linkCambioCama = "<img src=\"". GetThemePath() ."/images/cama.png\" width=15 heigh=15 border='0'>&nbsp;<a href=\"$cambioCama\">CAMBIO CAMA</a>";
							}

							$this->salida .= "	<td align=\"center\">".$linkCambioCama."&nbsp;</td>\n";
							$this->salida .= "	<td align=\"center\">".$link."</td>\n";
					/*$Pacientes[$i][0]  = PRIMER_NOMBRE." ".$data->SEGUNDO_NOMBRE;
							$Pacientes[$i][1]  = $data->PRIMER_APELLIDO." ".$data->SEGUNDO_APELLIDO;
							$Pacientes[$i][2]  = $data->PACIENTE_ID;
							$Pacientes[$i][3]  = $data->TIPO_ID_PACIENTE;
							$Pacientes[$i][4]  = $data->ING;
							$Pacientes[$i][5]  = $data->ORDEN_HOSP;
							$Pacientes[$i][6]  = $data->CUENTA;
							$Pacientes[$i][7]  = $data->PLAN;
							$Pacientes[$i][8]  = $resultado->fields[0];//cama
							$Pacientes[$i][9]  = $resultado->fields[1];//pieza
							$Pacientes[$i][10] = $data->ING_DPTO;
							$Pacientes[$i][11] = $data->FEC_ING;*/
							$this->salida .= "</tr>\n";
							$this->salida .= "</tr>\n";
							unset($linkCambioCama);
							$vector_ingresos[$i]=$pacientes[$i][4];

						}
						else
						{
							//$vector_ingresos[$i]=$pacientes[$i][4];
						}

				}
			}//fin for
						$this->salida .= "</table><br>\n";
			//$this->salida .= "<div class=\"label\" align=\"center\">TOTAL PACIENTES HOSPITALIZADOS = ".sizeof($pacientes)."<br>\n";
				$sw_p=1; //habilirtamos la tabla de estadistica;

			//}
		}//if pacientes
		/*else
		{
echo "hola some body help me";
		}*/



		//para linkear reportes
		// la funcion solo se puede instanciar una sola vez por documento pero
		// la puede llamar varias veces para varios reportes
		$reporte= new GetReports();
		$mostrar=$reporte->GetJavaReport('app','EstacionE_Pacientes','Listado_Pacientes',array('estacion'=>$datos_estacion[estacion_id],'nombre'=>$datos_estacion[descripcion5],'empresa'=>$datos_estacion[descripcion1]),array('rpt_name'=>'Pacientes_estacion'.$datos_estacion[estacion_id],'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$nombre_funcion=$reporte->GetJavaFunction();


					//esta funcion nos retorna el arreglo con los datos para armar el HTML.
	//	$RUTA=$this->ReportePacientesEstacion($datos_estacion[estacion_id]);
	//	$RUTA = $_ROOT ."cache/$RUTA";

		/*esta funcion extrae los datos para generar los reportes*/
		//$RUTA = $_ROOT ."cache/estacion_enf.pdf";
		//$RUTA = $_ROOT ."classes/classbuscador/buscador.php?tipo=$tipo";

		/*$mostrar ="\n<script language='javascript'>\n";
		$mostrar.="var rem=\"\";\n";
		$mostrar.="  function abreVentana(){\n";
		$mostrar.="    var nombre=\"\"\n";
		$mostrar.="    var url2=\"\"\n";
		$mostrar.="    var str=\"\"\n";
		$mostrar.="    var ALTO=screen.height\n";
		$mostrar.="    var ANCHO=screen.width\n";
		$mostrar.="    var nombre=\"REPORTE\";\n";
		$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
		$mostrar.="    var url2 ='$RUTA';\n";
		$mostrar.="    rem = window.open(url2, nombre, str)};\n";
		$mostrar.="</script>\n";*/
		$this->salida.="$mostrar";


		if($pacUrgencias != "ShowMensaje")
		{
			$this->salida .= "<br><table width=\"100%\" cellpadding=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\"><td colspan='3' height='25'>PACIENTES URGENCIAS</td></tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td>PACIENTE</td>\n";
			$this->salida .= "		<td>IDENTIFICACION</td>\n";
			$this->salida .= "		<td>CUENTA</td>\n";
			$this->salida .= "	</tr>\n";

			foreach($pacUrgencias as $key => $value)
			{
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

				$this->salida .= "<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
				$linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$value[ingreso],"retorno"=>"CallListadoPacientesEstacion","datos_estacion"=>$datos_estacion));
				$this->salida .= "	<td nowrap><img src=\"".GetThemePath()."/images/consulta_ur.png\">&nbsp;<a href=\"$linkVerDatos\">".$value[primer_nombre]." ".$value[segundo_nombre]." ".$value[primer_apellido]." ".$value[segundo_apellido]."</a></td>\n";
				$this->salida .= "	<td nowrap align='left'>".$value[tipo_id_paciente]." ".$value[paciente_id]."</td>\n";
				$this->salida .= "	<td nowrap align='center'>".$value[numerodecuenta]."</td>\n";
				$this->salida .= "</tr>\n";
				$i++;
			}
			$this->salida .= "</table>\n";
			//$this->salida .= "<br><div class=\"label\" align=\"center\">TOTAL PACIENTES URGENCIAS = ".sizeof($pacUrgencias)."<br>\n";
			$sw_u=1; //habilirtamos la tabla de estadistica;
		}//if($pacUrgencias)

				if($sw_u==1 AND $sw_p==1)
				{
						$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">";
						$this->salida .= "<tr class='hc_table_submodulo_list_title'><td colspan='3' class=\"label\" align=\"center\">ESTADISTICAS</td></tr>\n";
						$this->salida .= "<tr class='modulo_list_oscuro'><td class=\"label\" align=\"center\"><img src=\"".GetThemePath()."/images/flecha_der.gif\"  width='10' height='10'>&nbsp;TOTAL PACIENTES OBSERVACION &nbsp=&nbsp;".sizeof($vector_ingresos)."</td>\n";
						$this->salida .= "<td class=\"label\" align=\"center\"><img src=\"".GetThemePath()."/images/flecha_der.gif\"  width='10' height='10'>&nbsp;TOTAL PACIENTES URGENCIAS  &nbsp;=&nbsp;".sizeof($pacUrgencias)."</td>";
						$this->salida .= "<td class=\"label_error\" align=\"center\"><img src=\"".GetThemePath()."/images/flecha_der.gif\"  width='10' height='10'>&nbsp;TOTAL PACIENTES  EN LA ESTACION  &nbsp;=&nbsp;".(sizeof($vector_ingresos)+sizeof($pacUrgencias))."\n";
						$this->salida .= "</td></tr>\n";
						$this->salida .= "<td class='modulo_list_oscuro' colspan='3' align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/mail_find.png\">&nbsp;<a href=\"javascript:$nombre_funcion\">GENERAR REPORTE ESTACION</a>\n";
						$this->salida .= "</td></tr></table>\n";
 				}
				if($sw_u!=1 AND  $sw_p==1)
				{
						$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">";
						$this->salida .= "<tr class='hc_table_submodulo_list_title'><td colspan='2' class=\"label\" align=\"center\">ESTADISTICAS</td></tr>\n";
						$this->salida .= "<tr class='modulo_list_oscuro'><td class=\"label\" align=\"center\"><img src=\"".GetThemePath()."/images/flecha_der.gif\"  width='10' height='10'>&nbsp;TOTAL PACIENTES OBSERVACION &nbsp;=&nbsp;".sizeof($vector_ingresos)."</td>\n";
						$this->salida .= "<td class=\"label_error\" align=\"center\"><img src=\"".GetThemePath()."/images/flecha_der.gif\"  width='10' height='10'>&nbsp;TOTAL PACIENTES  EN LA ESTACION  &nbsp;=&nbsp;".sizeof($vector_ingresos)."\n";
						$this->salida .= "</td></tr>\n";
						$this->salida .= "<td class='modulo_list_oscuro' colspan='3' align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/mail_find.png\">&nbsp;<a href=\"javascript:$nombre_funcion\">GENERAR REPORTE ESTACION</a>\n";
						$this->salida .= "</td></tr></table>\n";

				}
				if($sw_u==1 AND  $sw_p !=1)
				{
						$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">";
						$this->salida .= "<tr class='hc_table_submodulo_list_title'><td colspan='2' class=\"label\" align=\"center\">ESTADISTICAS</td></tr>\n";
						$this->salida .= "<tr class='modulo_list_oscuro'><td class=\"label\" align=\"center\"><img src=\"".GetThemePath()."/images/flecha_der.gif\"  width='10' height='10'>&nbsp;TOTAL PACIENTES URGENCIAS  &nbsp;=&nbsp;".sizeof($pacUrgencias)."</td>";
						$this->salida .= "<td class=\"label_error\" align=\"center\"><img src=\"".GetThemePath()."/images/flecha_der.gif\"  width='10' height='10'>&nbsp;TOTAL PACIENTES  EN LA ESTACION  &nbsp;=&nbsp;".sizeof($pacUrgencias)."\n";
						$this->salida .= "</td></tr>\n";
						$this->salida .= "</td></tr></table>\n";
				}

		//if($pacientes != "ShowMensaje" && $pacUrgencias != "ShowMensaje"){
		//$this->salida .= "<div class=\"label\" align=\"center\">TOTAL PACIENTES EN LA ESTACION = ".(sizeof($pacientes)+sizeof($pacUrgencias))."<br>\n";
		//}
		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "<br><div class='normal_10' align='center'><a href='".$href."'>Volver al Menu Estaci&oacute;n</a>";
		$this->salida .= themeCerrarTabla();
		return true;
	}//ListadoPacientesEstacion

 /*FUNCION QUE VA EN EL MOD ESTACIONE_PACIENTES*/

/**
	*		ShowDatosTraslado => muestra que estaci&oacute;n solicit&oacute; traslado a que EE
	*
	*		vista 6 => ShowDatosTraslado => muestra a que estacion fue trasladado el paciente cuando le den click al link
	*		llamado desde la vista 5 "ListadoPacientesEstacion" link pendiente traslado
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@return boolean
	*/
	function ShowDatosTraslado()
	{
		$paciente = $_REQUEST['paciente'];
		$estacion = $_REQUEST['datos'];
		$datos_estacion = $_REQUEST['datos_estacion'];
		$this->salida .= ThemeAbrirTabla('DATOS DE LA SOLICITUD DE TRASLADO')."<br>";
		$this->salida .= "<table width=\"100%\" cellpadding=\"2\" border=\"0\" class=\"normal_10\">\n";
		$this->salida .= "	<tr>\n";
		$this->salida .= "		<td>\n";
		$this->salida .= "			<table width=\"100%\" cellpadding=\"2\" border=\"1\" class=\"modulo_table_list\">\n";
		$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "					<td>PACIENTE</td>\n";
		$this->salida .= "					<td>IDENTIFICACION</td>\n";
		$this->salida .= "					<td>ESTACION ORIGEN</td>\n";
		$this->salida .= "					<td>ESTACION DESTINO</td>\n";
		$this->salida .= "				</tr>\n";
		$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
		$this->salida .= "					<td align=\"center\">".$paciente[0]." ".$paciente[1]."</td>\n";
		$this->salida .= "					<td align=\"center\">".$paciente[3]." ".$paciente[2]."</td>\n";
		$this->salida .= "					<td align=\"center\">".$estacion[0][estorigen]."</td>\n";
		$this->salida .= "					<td align=\"center\">".$estacion[0][estdestino]."</td>\n";
		$this->salida .= "				</tr>\n";
		$this->salida .= "			</table>\n";
		$this->salida .= "		</td>\n";
		$this->salida .= "	</tr>\n";
		$link = $link = ModuloGetURL('app','EstacionE_Pacientes','user','CallListadoPacientesEstacion',array("datos_estacion"=>$datos_estacion));
		$this->salida .= "<tr><td align=\"center\"><a href=\"$link\">REGRESAR</a></td></tr>\n";
		$this->salida .= "</table><br>\n";
		$this->salida .= themeCerrarTabla();
		return true;
	}// fin ShowDatosTraslado



//------------------------------------------------------------------------------
	/**
	*		ListadoEstaciones => vista de un listado de las estaciones del departamento
	*
	*		llamado desde vista 1, link Remitir EE => subproceso 2->"Cambio estacion de enfermeria antes del ingreso al dpto" del proceso "ingreso de pacientes a la estaci&oacute;n de enfermer&iacute;a"
	*		vista 4 => 1.2.1.H => ListadoEstaciones lista las estaciones del departamento
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array => matriz con los datos del paciente a ingresar
	*		@param boolean => Sw para identificar si es traslado de EE a un paciente ya ingresado &oacute; cambio de EE antes del ingreso
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function ListadoEstaciones($datos,$SwTrasladoEE,$datos_estacion)
	{
		$estaciones = $this->GetEstacionesDpto($datos_estacion);
		if(!$estaciones){
			return false;
		}
		if($estaciones === "ShowMensaje")
		{
			$mensaje = "NO SE ENCONTRARON ESTACIONES EN EL DEPARTAMENTO";
			$titulo = "MENSAJE";
			//$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallListPacientesPorIngresar');
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MEN&Uacute; ESTACION";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}

		$this->salida .= ThemeAbrirTabla('TRASLADO DE ESTACION')."<br>";
		$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		//$this->salida .= "			<td>HABITACION</td>\n";
		//$this->salida .= "			<td>CAMA</td>\n";
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>ID</td>\n";
		$this->salida .= "			<td>CUENTA</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		//$this->salida .= "			<td>".$datos[9]."</td>\n";
		//$this->salida .= "			<td>".$datos[8]."</td>\n";
		$this->salida .= "			<td>".$datos[0]." ".$datos[1]."</td>\n";
		$this->salida .= "			<td>".$datos[3]." ".$datos[2]."</td>\n";
		$this->salida .= "			<td>".$datos[4]."</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "	</table><br>\n";

		$this->salida .= "<table width=\"100%\" cellpadding=\"2\" border=\"0\" class=\"modulo_table_list\" align=\"center\">\n";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<td>ESTACION</td>\n";
		$this->salida .= "		<td>ACCIONES</td>\n";
		$this->salida .= "	</tr>\n";

		for ($i=0; $i<sizeof($estaciones); $i++)
		{
	  if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
		$this->salida .= "	<tr class=\"$estilo\">\n";
		$this->salida .= "		<td><img src=\"".GetThemePath()."/images/flecha_der.gif\"  width='10' height='10'>&nbsp;<label class='label_mark'>".$estaciones[$i][1]."</label></td>\n";


		if($SwTrasladoEE){//ojo esto es por si voy a hacer el subproceso 4 "traslado EE dentro del dpto" en lugar del subproceso 2 "cambio de estacion antes del ingreso"
			$linkRemitir = ModuloGetURL('app','EstacionE_Pacientes','user','UpdateTrasladoEstacion',array("datos"=>$datos,"estacionDestino"=>$estaciones[$i][0],"datos_estacion"=>$datos_estacion));
		}
		else{//cambio de estacion antes del ingreso
			$linkRemitir = ModuloGetURL('app','EstacionE_Pacientes','user','UpdateCambioEstacion',array("datos"=>$datos,"estacionDestino"=>$estaciones[$i][0],"datos_estacion"=>$datos_estacion));
		}
		$this->salida .= "		<td align=\"center\"><img src=\"".GetThemePath()."/images/uf.png\"  width='14' height='14'>&nbsp;<a href=\"$linkRemitir\">REMITIR</a></td>\n";
		$this->salida .= "	</tr>\n";
		}
		$this->salida .= "</table><br>\n";
		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
		$this->salida .= themeCerrarTabla();
		return true;
	}//ListadoEstaciones


	//esta va para estacionE_Paciente
	/**
	*		ListadoCamas => muestra un listado de las camas disponibles de la EE
	*
	*		llamado desde VISTA 1 => LINK INGRESAR
	*		vista 2 => 1.1.2.H => ListadoCamas lista las camas disponibles de la EE indicando si son cubiertas por el plan del paciente
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@return boolean
	*		@param array => matriz con los datos del paciente
	*		@param boolean => defgine si va a realizar un cambio de cama o asignaci&oacute;n de cama
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function ListadoCamas($datos,$swCambioCama,$datos_estacion)
	{
			$mostrar ="\n<script language='javascript'>\n";
			$mostrar.="function mOvr(src,clrOver,i) {;\n";
			$mostrar.="src.style.background = clrOver;\n";
			$mostrar.= "document.getElementById(i).style.background = clrOver;\n";
			//$mostrar.= "document.getElementById(i).style='font-weight:bold';\n";

			$mostrar.="}\n";

			$mostrar.="function mOut(src,clrIn,i) {\n";
			$mostrar.="src.style.background = clrIn;\n";
			$mostrar.= "document.getElementById(i).style.background = clrIn;\n";
			$mostrar.="}\n";
			$mostrar.="</script>\n";
			$this->salida .="$mostrar";


		$vc = $vp = array();
		$datosCamas = $this->GetCamasDisponibles($datos_estacion[estacion_id],$datos[7]); //print_r($datosCamas);
																											//ee, planPaciente, estadoCama
		if(empty($datosCamas)){
			//$mensaje = "NO SE ENCONTRARON CAMAS DISPONIBLES EN LA ESTACION PARA EL PLAN ".$datos[7];
			$mensaje = "NO SE ENCONTRARON CAMAS DISPONIBLES";
			$titulo = "MENSAJE";
			//$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallListPacientesPorIngresar');
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MENU ESTACION";

			if(!$swCambioCama)
			{
				$linkVirtual = ModuloGetURL('app','EstacionE_Pacientes','user','CallCrear_Asignar_Cama_Virtual',array("swCambioCama"=>$swCambioCama,"datos"=>$datos,"datos_estacion"=>$datos_estacion));
				$impresion = "	<div class='normal_10' align='center'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" border='0' width=10 heigth=10 >&nbsp;<a href=\"$linkVirtual\">ASIGNACION CAMA VIRTUAL</a></div>\n";
			}
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton,$impresion);
			return true;
		}
		$impresion='';
		$this->salida .= ThemeAbrirTabla('LISTADO DE CAMAS DISPONIBLES EN '.$datos_estacion[descripcion5])."<br>";
		$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		if($swCambioCama)
		{
			$this->salida .= "		<td>HABITACIÓN</td>\n";
			$this->salida .= "		<td>CAMA</td>\n";
		}
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>IDENTIFICACIÓN</td>\n";
		$this->salida .= "			<td>CUENTA</td>\n";
		$this->salida .= "			<td>INGRESO</td>\n";
		$this->salida .= "			<td>No ORDEN HOSPITALIZACIÓN</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		if($swCambioCama)
		{
			$this->salida .= "			<td>".$datos[9]."</td>\n";
			$this->salida .= "			<td>".$datos[8]."</td>\n";
		}
		$this->salida .= "			<td>".$datos[0]." ".$datos[1]."</td>\n";
		$this->salida .= "			<td>".$datos[3]." ".$datos[2]."</td>\n";
		$this->salida .= "			<td>".$datos[6]."</td>\n";
		$this->salida .= "			<td>".$datos[4]."</td>\n";
		$this->salida .= "			<td>".$datos[5]."</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "	</table><br>\n";

		$this->salida .= "<table width=\"100%\" cellpadding=\"2\" border=\"0\" >\n";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<td>HAB.</td>\n";
		$this->salida .= "		<td>CARGO</td>\n";
		//$this->salida .= "		<td>SERVICIO</td>\n";
		$this->salida .= "		<td>TIPO DE CAMA</td>\n";
		$this->salida .= "		<td>CAMA</td>\n";
		$this->salida .= "		<td>VALOR ($)</td>\n";
		$this->salida .= "		<td>COB (%)</td>\n";
		$this->salida .= "		<td>VAL. PACIENTE</td>\n";
		$this->salida .= "		<td colspan=\"1\">ACCION</td>\n";

		$this->salida .= "	</tr>\n";

		//ModuloSetVar('app','EstacionE_Pacientes','ActivarDepurador',false);
		$i=0;
		$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

		foreach($datosCamas[$datos_estacion[estacion_id]] as $pieza => $datospieza)
		{
			$i++;
			$num_camas=sizeof($datospieza);

		  if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

			$this->salida .= "	<tr  class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]','$i'); onmouseover=mOvr(this,'#7A99BB','$i');>\n";
			$this->salida .= "		<td id=$i align=\"center\" rowspan=\"".$num_camas."\">".$pieza."</td>\n";

		//			$this->salida .= "		<td align=\"center\" rowspan=\"".sizeof($vc)."\">".$vp[1]."</td>\n";

			$j=0;

			foreach($datospieza as $k =>$dato_cama)//datos de las camas
			{
				if ($j!=0)//para que haga la primera fila completa, las demas son del rowspan
				{ $this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]','$i'); onmouseover=mOvr(this,'#7A99BB','$i');>\n"; }
					$j++;

				//$vc[$j][7] este es el cargo de tarifarios_detalle.
				$this->salida .= "	<td align=\"center\">".$dato_cama[cargo]."</td>\n";

				//este tipo servicio de cama si es normal,virtual,ambulatoria
				///$desc=$this->Traer_Tipos_Servicios_Cama($vc[$j][16]);

				//$this->salida .= "	<td align=\"left\">".$desc[0][descripcion]."</td>\n";
				$this->salida .= "	<td align=\"left\">".$dato_cama[desc_cargo]."</td>\n";
				$this->salida .= "	<td align=\"center\">".$dato_cama[cama]."</td>\n";
				$this->salida .= "	<td align=\"center\">".FormatoValor(0)."</td>\n";
				$this->salida .= "	<td align=\"center\">".FormatoValor(0)."</td>\n";
				$this->salida .= "	<td align=\"center\">".FormatoValor(0)."</td>\n";
				//$this->salida .= "	<td align=\"center\">$ ".number_format($noCubre, 2, ',', '.')."</td>\n";

				if($swCambioCama)//[12]=>1 => es cambio de cama P.1.3, no asignacion de cama P.1.1
				{//ojo esto es por si voy a hacer el subproceso 3 "cambio de cama" en lugar de "asignar cama" del subproceso 1
					//vector que guarda la informacion de la cama, descripcion,cama donde vamos a guardar.
					$data_cama[0]=$dato_cama[cama];
					$data_cama[1]=$dato_cama[desc_cargo];
					$data_cama[2]=$dato_cama[cargo];
					$data_cama[3]=$dato_cama[tipo_cama_id];
					$data_cama[4]=$dato_cama[tipo_clase_cama_id];
					$linkAsignaCama = ModuloGetURL('app','EstacionE_Pacientes','user','UpdateCamaPaciente',array("datos"=>$datos,"cama"=>$dato_cama[cama],"pieza"=>$pieza,"datosCamaPaciente"=>$data_cama,"datos_estacion"=>$datos_estacion));
					$LINK_TITLE='CAMBIO DE CAMA';
					unset($data_cama);
				}
				else //asignacion de cama P.1.1
				{
					//$linkAsignaCama = ModuloGetURL('app','EstacionEnfermeria','user','CallIngresarPaciente',array("nombres"=>$datos[0], "apellidos"=>$datos[1], "paciente_id"=>$datos[2],"tipo_paciente_id"=>$datos[3],"ing_id"=>$datos[4],"orden_hosp"=>$datos[5],"cuenta"=>$datos[6],"plan"=>$datos[7],"cama"=>'1',"pieza"=>'1',"cu_id"=>'1'));
					//vector que guarda la informacion de la cama, descripcion,cama donde vamos a guardar.
					$data_cama[0]=$dato_cama[cama];
					$data_cama[1]=$dato_cama[desc_cargo];
					$data_cama[2]=$dato_cama[cargo];
					$data_cama[3]=$dato_cama[tipo_cama_id];
					$data_cama[4]=$dato_cama[tipo_clase_cama_id];
					$linkAsignaCama = ModuloGetURL('app','EstacionE_Pacientes','user','CallIngresarPaciente',array("datos"=>$datos,"pieza"=>$pieza,"datosCamaPaciente"=>$data_cama,"datos_estacion"=>$datos_estacion));//					"pieza"=>$vp[0],"cama"=>$vc[$j][0],"tipoCama"=>$vc[$j][1],"cu_id"=>$this->cu_id));
					$LINK_TITLE='ASIGNACION DE CAMA';
					unset($data_cama);
				}

				$this->salida .= "	<td align=\"center\"><a href=\"$linkAsignaCama\">$LINK_TITLE</a></td>\n";
// 				if(empty($vc[$j][10]))
// 				{  $this->salida .= "	<td align=\"center\" class=\"label_error\">NO TIENE EQUIVALENCIAS</td>\n";  }
// 				elseif($vc[$j][13]==1)
// 				{  $this->salida .= "	<td align=\"center\" class=\"label_error\">NO ESTA CONTRATADO</td>\n";  }
// 				else
// 				{
// 						if($vc[$j][4] < 100) //class=\"Resalte\" al link que esta de primero.
// 						{		$this->salida .= "	<td align=\"center\"><a href=\"$linkAsignaCama\" >$LINK_TITLE</a></td>\n";  }
// 						else
// 						{		$this->salida .= "	<td align=\"center\"><a href=\"$linkAsignaCama\">$LINK_TITLE</a></td>\n";  }
// 				}
// 				$this->salida .= "</tr>\n";
 			}
		}	//ModuloSetVar('app','EstacionE_Pacientes','ActivarDepurador',true);
		$this->salida .= "</table><br>\n";

		if(!$swCambioCama)
		{
			$linkVirtual = ModuloGetURL('app','EstacionE_Pacientes','user','CallCrear_Asignar_Cama_Virtual',array("swCambioCama"=>$swCambioCama,"datos"=>$datos,"datos_estacion"=>$datos_estacion));
			$this->salida .= "	<div class='normal_10' align='center'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" border='0' width=10 heigth=10 >&nbsp;<a href=\"$linkVirtual\">ASIGNACION CAMA VIRTUAL</a></div>\n";
		}

		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
		$this->salida .= themeCerrarTabla();
		return true;
	}//ListadoCamas







//esta funcion me muestra las piezas y las camas virtuales q hay para asignar la cama virtual.
function Crear_Asignar_Cama_Virtual($datos,$datos_estacion,$swCambioCama)
{
		$this->salida .= ThemeAbrirTabla('ASIGNACIÓN DE CAMA VIRTUAL DE LA ESTACION - [ '.$datos_estacion[descripcion5].' ]');
		$this->salida .= "	<table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		if($swCambioCama)
		{
			$this->salida .= "		<td>HABITACIÓN</td>\n";
			$this->salida .= "		<td>CAMA</td>\n";
		}
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>IDENTIFICACIÓN</td>\n";
		$this->salida .= "			<td>CUENTA</td>\n";
		$this->salida .= "			<td>INGRESO</td>\n";
		$this->salida .= "			<td>No ORDEN HOSPITALIZACIÓN</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		if($swCambioCama)
		{
			$this->salida .= "			<td>".$datos[9]."</td>\n";
			$this->salida .= "			<td>".$datos[8]."</td>\n";
		}
		$this->salida .= "			<td>".$datos[0]." ".$datos[1]."</td>\n";
		$this->salida .= "			<td>".$datos[3]." ".$datos[2]."</td>\n";
		$this->salida .= "			<td>".$datos[6]."</td>\n";
		$this->salida .= "			<td>".$datos[4]."</td>\n";
		$this->salida .= "			<td>".$datos[5]."</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "	</table><br>\n";


		$arr_habit=$this->Revisar_Habitaciones_Existentes($datos_estacion['estacion_id']);
		if(is_array($arr_habit))
		{

					$this->salida .= "<SCRIPT language='javascript'>\n";
					$this->salida .= "function Pintartd(clrIn,i,x){\n";
					$this->salida .= "  if(x==true){\n";
					$sw=0;
					for($i=0;$i<sizeof($arr_habit);$i++)
					{

						if( $i % 2){ $color='#DDDDDD';}
							else {$color='#CCCCCC';}

						$this->salida .= "document.getElementById($i).style.background = '$color';\n";
						if(!empty($arr_habit[$i]['sw_virtual']))
						{$sw=$sw +1;}
						if($sw==0)
						{
							$s=$i+1;
							$this->salida .= "document.getElementById($s).style.background = clrIn;\n";
						}
					}
					$this->salida .= "document.getElementById(i).style.background = '#7A99BB';\n";
					$this->salida .= "    }\n";
					$this->salida .= "  else{\n";
					$this->salida .= "document.getElementById(i).style.background = clrIn;\n";

					$this->salida .= "  }\n";
					$this->salida .= "}\n";

					$this->salida .= "function pasar(obj,x){\n";
					$this->salida .= "  if(x==1){\n";
					$this->salida .= "  document.formin.text_tipo.value='PIEZA VIRTUAL';\n";
					$this->salida .= "}else{\n";
					$this->salida .= "  document.formin.text_tipo.value='PIEZA AMBULATORIA';\n";
					$this->salida .= "}\n";
					$this->salida .= "}\n";
					$this->salida .= "</SCRIPT>\n";

					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</able>";
					
					$this->salida.="<table  align=\"center\" border=\"2\"  width=\"90%\">";
					$this->salida .= "<tr class=\"modulo_list_claro\"><td>";

					$href = ModuloGetURL('app','EstacionE_Pacientes','user','GenerarCamaVirtual',array("swCambioCama"=>$swCambioCama,"datos_estacion"=>$datos_estacion,"datos"=>$datos));
					$this->salida .= "<form name='formin' action='".$href."' method='POST'><br>\n";
					$this->salida.="<table  align=\"center\" border=\"1\"  width=\"40%\">";

          $this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"left\"  colspan=\"2\">TIPO DE SERVICIO DE CAMA</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td>DESCRIPCION</td>";
					$this->salida.="  <td></td>";
					$this->salida.="</tr>";

					$this->salida.="<tr  class=modulo_list_claro align=\"center\">";
					$this->salida.="  <td><label class='label_mark'><b>CAMA VIRTUAL</b></label></td>";
					$this->salida.="  <td><input type=\"radio\" name=\"tipoc\" onclick=pasar(this,1) value='2'></td>";
					$this->salida.="</tr>";

					$this->salida.="<tr  class=modulo_list_oscuro align=\"center\">";
					$this->salida.="  <td><label class='label_mark'><b>CAMA AMBULATORIA</b></label></td>";
					$this->salida.="  <td><input type=\"radio\" name=\"tipoc\" onclick=pasar(this,2) value='3'></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";


					$this->salida.="<table  align=\"center\" border=\"1\"  width=\"70%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"left\" colspan=\"5\">SELECCIONE LA PIEZA DONDE VA A CREAR LA CAMA</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td>PIEZA</td>";
					$this->salida.="  <td>DESCRIPCION</td>";
					$this->salida.="  <td>UBICACION</td>";
					$this->salida.="  <td><sub>Camas<br>Especiales</sub></td>";
					$this->salida.="  <td></td>";
					$this->salida.="</tr>";
					$sw=0;


					for($i=0;$i<sizeof($arr_habit);$i++)
					{
							if( $i % 2){ $estilo='modulo_list_claro';$color='#DDDDDD';}
							else {$estilo='modulo_list_oscuro';$color='#CCCCCC';}

              if(!empty($arr_habit[$i]['sw_virtual']))
							{$sw=$sw +1; $clase="<label class='label_mark'><b>";$_clase="</b></label>";}else{$clase='';$_clase='';}
							$this->salida.="<tr id=$i class=\"$estilo\" align=\"center\">";
							$this->salida.="  <td>$clase".$arr_habit[$i]['pieza']."$_clase</td>";
							$this->salida.="  <td>$clase".$arr_habit[$i]['descripcion']."$_clase</td>";
							$this->salida.="  <td>$clase".$arr_habit[$i]['ubicacion']."$_clase</td>";
							$contador=$this->Conteo_Camas_Especiales($arr_habit[$i]['pieza']);
							$this->salida.="  <td>(".FormatoValor($contador).")</td>";
							$this->salida.="  <td><input type=\"radio\" name=\"opcion\" onclick=Pintartd('$color','$i',this.checked) value=".$arr_habit[$i]['pieza']."></td>";
          		$this->salida.="</tr>";

					}
					if($sw==0)
					{
							$this->salida.="<tr id=$i class=\"$estilo\" align=\"center\">";
							$this->salida.="  <td><input type=\"text\" class='input-text' name=\"text_tipo\" READONLY></td>";
							$this->salida.="  <td><textarea name=desc class='input-text' cols=30 rows=3>PIEZA ESPECIAL</textarea></td>";
							$this->salida.="  <td><textarea name=ubic class='input-text' cols=30 rows=3>ESTACION &nbsp; ".$datos_estacion['descripcion5']."</textarea></td>";
							$this->salida.="  <td>&nbsp;</td>";
							$this->salida.="  <td><input type=\"radio\" name=\"opcion\" onclick=Pintartd('$color','$i',this.checked) value='[-@@@-]'></td>";
          		$this->salida.="</tr>";
					}

					$this->salida.="</table><br>";
					$this->salida.="<table   align=\"center\" border=\"1\" width=\"70%\">";


					$this->salida.="<tr class=\"modulo_table_title\"><td>SELECCIONE EL TIPO DE CAMA</td><td align='center'>COLOQUE LA UBICACIÓN DE LA CAMA</td></tr>";

					$this->salida.="<tr>";

					$VectorTiposCamas=$this->Traer_Tipos_Cama_excepciones($datos_estacion['estacion_id'],$datos[7]);

					if(!is_array($VectorTiposCamas))
					{
						$VectorTiposCamas=$this->Traer_Tipos_Cama($datos_estacion['estacion_id']);
					}



					if(sizeof($VectorTiposCamas))
					{
							//TIPO CAMAS
							$this->salida .= "<td align='center' class=\"modulo_list_claro\"><select name=\"tipo_cama\" class=\"select\">\n";

							for($j=0; $j<sizeof($VectorTiposCamas); $j++)
							{
								if($VectorTiposCamas[$j][tipo_cama_id]==$tipo_cama_act) //verificar si hay un request..
								{
									$this->salida .= "							<option value=\"".$VectorTiposCamas[$j][tipo_cama_id]."*".$VectorTiposCamas[$j][cargo]."*".$VectorTiposCamas[$j][tipo_clase_cama_id]."\"selected>".$VectorTiposCamas[$j][descripcion]."</option>\n";
								}
								else
								{
									$this->salida .= "							<option value=\"".$VectorTiposCamas[$j][tipo_cama_id]."*".$VectorTiposCamas[$j][cargo]."*".$VectorTiposCamas[$j][tipo_clase_cama_id]."\">".$VectorTiposCamas[$j][descripcion]."</option>\n";
								}
							}
							$this->salida .= "								</select>\n";
					}
					else
					{$this->salida .= "<td class=\"modulo_list_claro\"><label class='label_error'>POR FAVOR LLENAR LAS TABLAS tipos_camas_excepcion_plan,tipos_camas,estaciones_tipos_camas_permitidos </label></td>";}

					$this->salida.="</td><td align=\"center\" class=\"modulo_list_claro\"><input type=\"text\" class='input-text' maxlength=\"20\"  name=\"ubic_cama\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";


					$this->salida.="<table   align=\"center\" border=\"0\" width=\"20%\">";
					$this->salida.="<tr class=\"modulo_list_claro\"><td class=\"modulo_list_claro\"><input class=\"input-submit\" type=submit name=mandar value='ASIGNAR CAMA VIRTUAL'></td></tr>";
					$this->salida.="</form>";
					$this->salida.="</table>";
					$this->salida.="</td></tr></table>";
		}
		else
  {


  }

		$href = ModuloGetURL('app','EstacionE_Pacientes','user','CallListadoCamas',array("datos_estacion"=>$datos_estacion,"datos"=>$datos));
		$this->salida .= "			<div class='normal_10' align='center'><br><a href='".$href."'><< Volver</a>";


		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";

		$this->salida .= themeCerrarTabla();
		return true;
}




/**
	*		FrmReservaCamas => muestra un listado de las camas a reservar
	*
	*  llamado desde VISTA 1 => LINK INGRESAR
	*		vista 2 => 1.1.2.H => ListadoCamas lista las camas disponibles de la EE indicando si son cubiertas por el plan del paciente
	*
	*		@Author jaja
	*		@access Private
	*		@return boolean
	*		@param array => matriz con los datos del paciente
	*		@param boolean => define si va a realizar un cambio de cama o asignaci&oacute;n de cama
	*		@param array datos de la estacion
	*		@return boolean [quitar]
	*/
	function FrmReservaCamas($datos,$swCambioCama,$datos_estacion)
	{
			$mostrar ="\n<script language='javascript'>\n";
			$mostrar.="function mOvr(src,clrOver,i) {;\n";
			$mostrar.="src.style.background = clrOver;\n";
			$mostrar.= "document.getElementById(i).style.background = clrOver;\n";
			//$mostrar.= "document.getElementById(i).style='font-weight:bold';\n";

			$mostrar.="}\n";

			$mostrar.="function mOut(src,clrIn,i) {\n";
			$mostrar.="src.style.background = clrIn;\n";
			$mostrar.= "document.getElementById(i).style.background = clrIn;\n";
			$mostrar.="}\n";
			$mostrar.="</script>\n";
			$this->salida .="$mostrar";


		$vc = $vp = array();
		$datosCamas = $this->GetCamasOcupadas($datos_estacion[estacion_id],$datos[7]); //print_r($datosCamas);
																											//ee, planPaciente, estadoCama
		if(empty($datosCamas)){
			$mensaje = "NO SE ENCONTRARON CAMAS DISPONIBLES EN LA ESTACION PARA EL PLAN ".$datos[7];
			$titulo = "MENSAJE";
			//$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallListPacientesPorIngresar');
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MENU ESTACION";

			if(!$swCambioCama)
			{
				//$linkVirtual = ModuloGetURL('app','EstacionE_Pacientes','user','CallCrear_Asignar_Cama_Virtual',array("swCambioCama"=>$swCambioCama,"datos"=>$datos,"datos_estacion"=>$datos_estacion));
				//$impresion = "	<div class='normal_10' align='center'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" border='0' width=10 heigth=10 >&nbsp;<a href=\"$linkVirtual\">ASIGNACION CAMA VIRTUAL</a></div>\n";
			}
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton,$impresion);
			return true;
		}
		$impresion='';
		$this->salida .= ThemeAbrirTabla('LISTADO DE CAMAS DISPONIBLES EN '.$datos_estacion[descripcion5])."<br>";
		$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		if($swCambioCama)
		{
			$this->salida .= "		<td>HABITACIÓN</td>\n";
			$this->salida .= "		<td>CAMA</td>\n";
		}
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>IDENTIFICACIÓN</td>\n";
		$this->salida .= "			<td>CUENTA</td>\n";
		$this->salida .= "			<td>INGRESO</td>\n";
		$this->salida .= "			<td>ORDEN HOSP.</td>\n";
		$this->salida .= "			<td><sub># RESERVAS</sub></td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		if($swCambioCama)
		{
			$this->salida .= "			<td>".$datos[9]."</td>\n";
			$this->salida .= "			<td>".$datos[8]."</td>\n";
		}
		$this->salida .= "			<td>".$datos[0]." ".$datos[1]."</td>\n";
		$this->salida .= "			<td>".$datos[3]." ".$datos[2]."</td>\n";
		$this->salida .= "			<td>".$datos[6]."</td>\n";
		$this->salida .= "			<td>".$datos[4]."</td>\n";
		$this->salida .= "			<td>".$datos[5]."</td>\n";

		$reserva=$this->RevisarReservas_X_ingreso($datos[4]);
		$this->salida .= "			<td>$reserva</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "	</table><br>\n";

		$accion = ModuloGetURL('app','EstacionE_Pacientes','user','InsertarReserva',array("datos"=>$datos,"swCambioCama"=>$swCambioCama,"datos_estacion"=>$datos_estacion));
		$this->salida .= "	<form name=\"formaMensaje\" action=\"$accion\" method=\"post\">\n";

		$this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= " <table>";

		$this->salida .= "<table width=\"100%\" cellpadding=\"2\" border=\"0\" >\n";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<td>HAB.</td>\n";
		$this->salida .= "		<td>CARGO</td>\n";
		//$this->salida .= "		<td>SERVICIO</td>\n";
		$this->salida .= "		<td>TIPO DE CAMA</td>\n";
		$this->salida .= "		<td>CAMA</td>\n";
		$this->salida .= "		<td>VALOR ($)</td>\n";
		$this->salida .= "		<td>COB (%)</td>\n";
		$this->salida .= "		<td>VAL. PACIENTE</td>\n";
		$this->salida .= "		<td><sub># RESERVAS</sub></td>\n";
		$this->salida .= "		<td colspan=\"1\">RESERVA</td>\n";

		$this->salida .= "	</tr>\n";


		//ModuloSetVar('app','EstacionE_Pacientes','ActivarDepurador',false);
		$i=0;
		$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

		foreach($datosCamas[$datos_estacion[estacion_id]] as $pieza => $datospieza)
		{
			$i++;
			$num_camas=sizeof($datospieza);


		  if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

			$this->salida .= "	<tr  class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]','$i'); onmouseover=mOvr(this,'#7A99BB','$i');>\n";
			$this->salida .= "		<td id=$i align=\"center\" rowspan=\"".$num_camas."\">".$pieza."</td>\n";

			$j=0;

			foreach($datospieza as $k =>$dato_cama)//datos de las camas
			{
				if ($j!=0)//para que haga la primera fila completa, las demas son del rowspan
				{ $this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]','$i'); onmouseover=mOvr(this,'#7A99BB','$i');>\n"; }
					$j++;
				$this->salida .= "	<td align=\"center\">".$dato_cama[cargo]."</td>\n";

				$this->salida .= "	<td align=\"left\">".$dato_cama[desc_cargo]."</td>\n";
				$this->salida .= "	<td align=\"center\">".$dato_cama[cama]."</td>\n";
				$this->salida .= "	<td align=\"center\">".FormatoValor(0)."</td>\n";
				$this->salida .= "	<td align=\"center\">".FormatoValor(0)."</td>\n";
				$this->salida .= "	<td align=\"center\">".FormatoValor(0)."</td>\n";

				$count_x=$this->RevisarReservas_X_cama($dato_cama[cama],$dato_cama[pieza]);
				$this->salida .= "	<td align=\"center\"><label class='label_mark'>".FormatoValor($count_x)."</label></td>\n";


				if($swCambioCama)//[12]=>1 => es cambio de cama P.1.3, no asignacion de cama P.1.1
				{//ojo esto es por si voy a hacer el subproceso 3 "cambio de cama" en lugar de "asignar cama" del subproceso 1
					//vector que guarda la informacion de la cama, descripcion,cama donde vamos a guardar.
					$data_cama[0]=$dato_cama[cama];
					$data_cama[1]=$dato_cama[desc_cargo];
					$data_cama[2]=$dato_cama[cargo];
					$data_cama[3]=$dato_cama[tipo_cama_id];
					$data_cama[4]=$dato_cama[tipo_clase_cama_id];
					$linkAsignaCama = ModuloGetURL('app','EstacionE_Pacientes','user','UpdateCamaPaciente',array("datos"=>$datos,"cama"=>$dato_cama[cama],"pieza"=>$pieza,"datosCamaPaciente"=>$data_cama,"datos_estacion"=>$datos_estacion));
					//$LINK_TITLE='CAMBIO DE CAMA';
					$LINK_TITLE="<input type='radio'  name='op' value=".$dato_cama[cama]."$".$dato_cama[pieza].">";
					unset($data_cama);
				}
				else //asignacion de cama P.1.1
				{
					//$linkAsignaCama = ModuloGetURL('app','EstacionEnfermeria','user','CallIngresarPaciente',array("nombres"=>$datos[0], "apellidos"=>$datos[1], "paciente_id"=>$datos[2],"tipo_paciente_id"=>$datos[3],"ing_id"=>$datos[4],"orden_hosp"=>$datos[5],"cuenta"=>$datos[6],"plan"=>$datos[7],"cama"=>'1',"pieza"=>'1',"cu_id"=>'1'));
					//vector que guarda la informacion de la cama, descripcion,cama donde vamos a guardar.
					$data_cama[0]=$dato_cama[cama];
					$data_cama[1]=$dato_cama[desc_cargo];
					$data_cama[2]=$dato_cama[cargo];
					$data_cama[3]=$dato_cama[tipo_cama_id];
					$data_cama[4]=$dato_cama[tipo_clase_cama_id];
					$linkAsignaCama = ModuloGetURL('app','EstacionE_Pacientes','user','CallIngresarPaciente',array("datos"=>$datos,"pieza"=>$pieza,"datosCamaPaciente"=>$data_cama,"datos_estacion"=>$datos_estacion));//					"pieza"=>$vp[0],"cama"=>$vc[$j][0],"tipoCama"=>$vc[$j][1],"cu_id"=>$this->cu_id));
					$LINK_TITLE="<input type='radio'  name='op' value=".$dato_cama[cama]."$".$dato_cama[pieza].">";
					unset($data_cama);
				}

				$this->salida .= "	<td align=\"center\"><a href=\"$linkAsignaCama\">$LINK_TITLE</a></td>\n";
		}
		}	//ModuloSetVar('app','EstacionE_Pacientes','ActivarDepurador',true);
		$this->salida .= "</table><br>\n";

		if(!$swCambioCama)
		{
			//$linkVirtual = ModuloGetURL('app','EstacionE_Pacientes','user','CallCrear_Asignar_Cama_Virtual',array("swCambioCama"=>$swCambioCama,"datos"=>$datos,"datos_estacion"=>$datos_estacion));
			//$this->salida .= "	<div class='normal_10' align='center'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" border='0' width=10 heigth=10 >&nbsp;<a href=\"$linkVirtual\">ASIGNACION CAMA VIRTUAL</a></div>\n";
		}

		$this->salida .= "	<div  align='center'><input type='submit' class='input-submit' name='mandar' value='Reservar'></div>\n";
		$this->salida .="</form>";
		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
		$this->salida .= themeCerrarTabla();
		return true;
	}//ListadoCamas





	function ListadoCamas1111($datos,$swCambioCama,$datos_estacion)
	{
		IncludeLib("tarifario");
		$vc = $vp = array();
		$datosCamas = $this->GetCamasDisponiblesSegunPlan($datos_estacion[estacion_id], $datos[7], 1); //print_r($datosCamas);
																											//ee, planPaciente, estadoCama
		if(!$datosCamas){
			return false;
		}
		if($datosCamas === "ShowMensaje")
		{
			$mensaje = "NO SE ENCONTRARON CAMAS DISPONIBLES EN LA ESTACION PARA EL PLAN ".$datos[7];
			$titulo = "MENSAJE";
			//$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallListPacientesPorIngresar');
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MENU ESTACION";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		$this->salida .= ThemeAbrirTabla('LISTADO DE CAMAS DISPONIBLES EN '.$datos_estacion[descripcion5])."<br>";
		$this->salida .= "	<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		if($swCambioCama)
		{
			$this->salida .= "		<td>HABITACION</td>\n";
			$this->salida .= "		<td>CAMA</td>\n";
		}
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>IDENTIFICACIÓN</td>\n";
		$this->salida .= "			<td>CUENTA</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		if($swCambioCama)
		{
			$this->salida .= "			<td>".$datos[9]."</td>\n";
			$this->salida .= "			<td>".$datos[8]."</td>\n";
		}
		$this->salida .= "			<td>".$datos[0]." ".$datos[1]."</td>\n";
		$this->salida .= "			<td>".$datos[3]." ".$datos[2]."</td>\n";
		$this->salida .= "			<td>".$datos[4]."</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "	</table><br>\n";

		$this->salida .= "<table width=\"100%\" cellpadding=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<td>HAB.</td>\n";
		$this->salida .= "		<td>CARGO</td>\n";
		//$this->salida .= "		<td>SERVICIO</td>\n";
		$this->salida .= "		<td>TIPO DE CAMA</td>\n";
		$this->salida .= "		<td>CAMA</td>\n";
		$this->salida .= "		<td>VALOR ($)</td>\n";
		$this->salida .= "		<td>COB (%)</td>\n";
		$this->salida .= "		<td>VAL. PACIENTE</td>\n";
		$this->salida .= "		<td colspan=\"1\">ACCION</td>\n";
		$this->salida .= "	</tr>\n";

		for($i=0; $i<sizeof($datosCamas); $i++)
		{
			$vp = $datosCamas[$i][0];

			$vc = $datosCamas[$i][1];

		  if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

			$this->salida .= "	<tr class=\"$estilo\">\n";
			$this->salida .= "		<td align=\"center\" rowspan=\"".sizeof($vc)."\">".$vp[0]."</td>\n";
//			$this->salida .= "		<td align=\"center\" rowspan=\"".sizeof($vc)."\">".$vp[1]."</td>\n";

			for($j=0; $j < sizeof($vc); $j++)//datos de las camas
			{
				if ($j!=0)//para que haga la primera fila completa, las demas son del rowspan
				{ $this->salida.="<tr class=\"$estilo\">\n"; }

				//revisamos los diferentes casos que se pueden dar para determinar si
				//esta o no contratado.
				//$vc[$j][13] ->sw_no_contratado //$vc[$j][12] ->por_cobertura de excepciones.

				//si este  sw_no_contratado esta en 0  se muestra el porcentaje de la excepcion
				if(empty($vc[$j][13]))
				{
					$noCubre = $vc[$j][3] - (($vc[$j][3] * $vc[$j][4])/ 100);
				}
				elseif($vc[$j][13]==0)
				{$noCubre = $vc[$j][3] - (($vc[$j][3] * $vc[$j][12])/ 100);}
				// 4 por_cobertura  3 precio
				//
				if(empty($vc[$j][1]))
				{
						$desc_cargo=$this->GetDescripcionCargoCama($vc[$j][8],$vc[$j][0]);
						$vc[$j][1]=$desc_cargo;
				}
				//$vc[$j][7] este es el cargo de tarifarios_detalle.
				$this->salida .= "	<td align=\"center\">".$vc[$j][7]."</td>\n";

				//este tipo servicio de cama si es normal,virtual,ambulatoria
				///$desc=$this->Traer_Tipos_Servicios_Cama($vc[$j][16]);

				//$this->salida .= "	<td align=\"left\">".$desc[0][descripcion]."</td>\n";
				$this->salida .= "	<td align=\"left\">".$vc[$j][1]."</td>\n";
				$this->salida .= "	<td align=\"center\">".$vc[$j][0]."</td>\n";
				$this->salida .= "	<td align=\"center\">".FormatoValor($vc[$j][3])."</td>\n";
				$this->salida .= "	<td align=\"center\">".FormatoValor($vc[$j][4])."</td>\n";
				$this->salida .= "	<td align=\"center\">".$noCubre."</td>\n";
				//$this->salida .= "	<td align=\"center\">$ ".number_format($noCubre, 2, ',', '.')."</td>\n";

				if($swCambioCama)//[12]=>1 => es cambio de cama P.1.3, no asignacion de cama P.1.1
				{//ojo esto es por si voy a hacer el subproceso 3 "cambio de cama" en lugar de "asignar cama" del subproceso 1
					$linkAsignaCama = ModuloGetURL('app','EstacionE_Pacientes','user','UpdateCamaPaciente',array("datos"=>$datos,"cama"=>$vc[$j][0],"pieza"=>$vp[0],"datosCamaPaciente"=>$vc[$j],"datos_estacion"=>$datos_estacion));
					$LINK_TITLE='CAMBIO DE CAMA';
				}
				else //asignacion de cama P.1.1
				{
					//$linkAsignaCama = ModuloGetURL('app','EstacionEnfermeria','user','CallIngresarPaciente',array("nombres"=>$datos[0], "apellidos"=>$datos[1], "paciente_id"=>$datos[2],"tipo_paciente_id"=>$datos[3],"ing_id"=>$datos[4],"orden_hosp"=>$datos[5],"cuenta"=>$datos[6],"plan"=>$datos[7],"cama"=>'1',"pieza"=>'1',"cu_id"=>'1'));
					$linkAsignaCama = ModuloGetURL('app','EstacionE_Pacientes','user','CallIngresarPaciente',array("datos"=>$datos,"pieza"=>$vp[0],"datosCamaPaciente"=>$vc[$j],"datos_estacion"=>$datos_estacion));//					"pieza"=>$vp[0],"cama"=>$vc[$j][0],"tipoCama"=>$vc[$j][1],"cu_id"=>$this->cu_id));
					$LINK_TITLE='ASIGNACION DE CAMA';
				}
				if(empty($vc[$j][10]))
				{  $this->salida .= "	<td align=\"center\" class=\"label_error\">NO TIENE EQUIVALENCIAS</td>\n";  }
				elseif($vc[$j][13]==1)
				{  $this->salida .= "	<td align=\"center\" class=\"label_error\">NO ESTA CONTRATADO</td>\n";  }
				else
				{
						if($vc[$j][4] < 100) //class=\"Resalte\" al link que esta de primero.
						{		$this->salida .= "	<td align=\"center\"><a href=\"$linkAsignaCama\" >$LINK_TITLE</a></td>\n";  }
						else
						{		$this->salida .= "	<td align=\"center\"><a href=\"$linkAsignaCama\">$LINK_TITLE</a></td>\n";  }
				}
				$this->salida .= "</tr>\n";
			}
		}
		$this->salida .= "</table><br>\n";
          $this->salida .= "	<div class='normal_10' align='center'><a href=\"$linkAsignaCama\">ASIGNACION CAMA VIRTUAL</a></div>\n";
		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
		$this->salida .= themeCerrarTabla();
		return true;
	}//ListadoCamas


	//funcion del mod estacion de enfermeria_pacientes
	/**
	*		IngresarPaciente => vista en la que se pide a la enfermera comentarios del ingreso
	*
	*		llamado desde el subproceso1->"Asignar cama" del proceso "ingreso de pacientes a la estaci&oacute;n de enfermer&iacute;a"
	*		vista 3 => 1.1.3.H => IngresarPaciente pide observaciones y llama a la funcion que inserta en la bd
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array => matriz con los datos del paciente a ingresar
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function IngresarPaciente($datos,$datos_estacion)
	{	
     
		$this->salida .= ThemeAbrirTabla('INGRESAR PACIENTE A LA ESTACION - [ '.$datos_estacion[descripcion5].' ]');

		/*if($_REQUEST['spya']==1)
		{
			$VectorTiposCamas=$this->Traer_Tipos_Cama($datos_estacion[estacion_id]);
		}*/
		$this->salida .= "	<table class='modulo_table_title' align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		if($swCambioCama)
		{
			$this->salida .= "		<td>HABITACIÓN</td>\n";
			$this->salida .= "		<td>CAMA</td>\n";
		}
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>IDENTIFICACIÓN</td>\n";
		$this->salida .= "			<td>CUENTA</td>\n";
		$this->salida .= "			<td>INGRESO</td>\n";
		$this->salida .= "			<td>No ORDEN HOSPITALIZACIÓN</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		if($swCambioCama)
		{
			$this->salida .= "			<td>".$datos[9]."</td>\n";
			$this->salida .= "			<td>".$datos[8]."</td>\n";
		}
		$linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$datos[4],"retorno"=>"CallRetornoIngresarPaciente","datos"=>$datos,"datos_estacion"=>$datos_estacion));
		$this->salida .= "			<td><a href='$linkVerDatos'>".$datos[0]." ".$datos[1]."</a></td>\n";
		$this->salida .= "			<td>".$datos[3]." ".$datos[2]."</td>\n";
		$this->salida .= "			<td>".$datos[6]."</td>\n";
		$this->salida .= "			<td>".$datos[4]."</td>\n";
		$this->salida .= "			<td>".$datos[5]."</td>\n";
		$this->salida .= "		</tr>\n";
		$linkIngresar = ModuloGetURL('app','EstacionE_Pacientes','user','InsertarPaciente',array("datos"=>$datos,"datos_estacion"=>$datos_estacion));
		$this->salida .= "			<form name=\"ingresars\" method=\"POST\" action=\"$linkIngresar\">\n";
		$this->salida .= "	</table>\n";


		$this->salida .= "			<table width=\"90%\" align=\"center\" cellpadding=\"2\" border=\"1\" >\n";
		$this->salida .= "				<tr class=\"modulo_table_title\">\n";
		//$this->salida .= "					<td>PACIENTE</td>\n";
	  //$this->salida .= "					<td>ID</td>\n";
		$this->salida .= "					<td>HAB.</td>\n";
		$this->salida .= "					<td>CAMA</td>\n";
		$this->salida .= "					<td>DESCRIPCION</td>\n";
		//$this->salida .= "					<td>TIPO</td>\n";
		$this->salida .= "					<td>OBSERVACIONES</td>\n";
		$this->salida .= "				</tr>\n";


    $this->salida .= "				<tr class=\"modulo_list_claro\">\n";
		//$this->salida .= "					<td>".$datos[0]." ".$datos[1]."</td>\n";
		//$this->salida .= "					<td align=\"center\">".$datos[3]." ".$datos[2]."</td>\n";
		$this->salida .= "					<td align=\"center\">".$datos[11]."</td>\n";
		$this->salida .= "					<td align=\"center\">".$datos[12]."</td>\n";
		$this->salida .= "					<td align=\"center\">".urldecode($datos[13])."</td>\n";


	/*	if($_REQUEST['spya']==1)
		{
			if(sizeof($VectorTiposCamas))
			{
					//TIPO CAMAS
			$this->salida .= "<td class=\"modulo_list_claro\"><select name=\"tipo_cama[]\" class=\"select\">\n";

					for($j=0; $j<sizeof($VectorTiposCamas); $j++)
					{
						if($VectorTiposCamas[$j][tipo_cama_id]==$tipo_cama_act) //verificar si hay un request..
						{
							$this->salida .= "							<option value=\"".$VectorTiposCamas[$j][tipo_cama_id]."\"selected>".$VectorTiposCamas[$j][descripcion]."</option>\n";
						}
						else
						{
							$this->salida .= "							<option value=\"".$VectorTiposCamas[$j][tipo_cama_id]."\">".$VectorTiposCamas[$j][descripcion]."</option>\n";
						}
					}
					$this->salida .= "								</select>\n";
			}
			$this->salida .= "								</td>\n";
		}
		else
		{
			$link = ModuloGetURL('app','EstacionE_Pacientes','user','CallIngresarPacienteCambioTipoCama',array("datos"=>$datos,"datos_estacion"=>$datos_estacion,"spya"=>1));
			$this->salida .= "					<td align=\"center\"><a href='$link'>[".$datos[25]." ]</a></td>\n";

		}*/

		$this->salida .= "					<td align=\"center\"><textarea name=\"observaciones\" cols='80' rows='6'  class=\"textarea\"></textarea></td>\n";
		$this->salida .= "				</tr>\n";
		$this->salida .= "			</table><br>\n";



		//$this->salida .= "<div class='normal_10' align=\"left\"><a href='$link'>CAMBIAR CARGO</a></div>\n";



		$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"ASIGNAR CAMA\"></form></td>";


		$link = ModuloGetURL('app','EstacionE_Pacientes','user','DecisionCambioCargo',array("datos"=>$datos,"datos_estacion"=>$datos_estacion));
		$this->salida .= "           <form name=\"forma\" action=\"$link\" method=\"post\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"CAMBIAR CARGO\"></form></td>";
		$this->salida .= "</tr>";
		$this->salida.="</table>";


		//$this->salida .= "  			<div class='normal_10' align='center'><input type=\"submit\" name=\"Submit\" value=\"ASIGNAR CAMA\" class=\"input-submit\"></div>\n";
		//$this->salida .= "			</form>";

		$href = ModuloGetURL('app','EstacionE_Pacientes','user','CallListadoCamas',array("datos_estacion"=>$datos_estacion,"datos"=>$datos));
		$this->salida .= "			<div class='normal_10' align='center'><br><a href='".$href."'><< Volver</a>";

		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
		$this->salida .= "			<br><div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
		$this->salida .= "  	</td>\n";
		$this->salida .= themeCerrarTabla();
		return true;
	}//IngresarPaciente






	function RevisarUser()
	{
          $datos=$_REQUEST['datos'];
          $datos_estacion=$_REQUEST['datos_estacion'];
          if(!$_REQUEST['usuario'] || !$_REQUEST['pass'])
          {
               if(!$_REQUEST['usuario']){ $this->frmError["usuario"]=1; }
               if(!$_REQUEST['pass']){ $this->frmError["pass"]=1; }
               $this->frmError["MensajeError"]="Faltan datos de autenticacion.";
               $this->DecisionCambioCargo($datos,$datos_estacion);
               return true;
          }

		//cambio realiado para la sos....
		$usuario_id = UserValidarUsuario($_REQUEST['usuario'],$_REQUEST['pass']);


		if($usuario_id ==false)
		{
               if(!$_REQUEST['usuario']){ $this->frmError["usuario"]=1; }
               if(!$_REQUEST['pass']){ $this->frmError["pass"]=1; }
               $this->frmError["MensajeError"]="usuario no valido";
               $this->DecisionCambioCargo($datos,$datos_estacion);
               return true;
		}
		$conteo=$this->BuscarAutorizacionParaCambioCargo($datos_estacion[estacion_id],$usuario_id);
		if($conteo < 1)
		{
			if(!$_REQUEST['usuario']){ $this->frmError["usuario"]=1; }
			if(!$_REQUEST['pass']){ $this->frmError["pass"]=1; }
			$this->frmError["MensajeError"]="USUARIO NO AUTORIZADO";
			$this->DecisionCambioCargo($datos,$datos_estacion);
			return true;
		}
		else
		{
				//variable de session q llenamos para la auditoria de cambio de cargo.
				$_SESSION['ESTACION_ENF']['AUDITORIA']['USUARIO_AUTO']=$usuario_id;
				$this->DecisionCambioCargo($datos,$datos_estacion,'verdadero');
				return true;
		}
		
	}




	//funcion en la cual revisa si tiene autorizacion para cambiar el cargo
	//$SW_USER_LEGAL esta variable si esta activa significa q puede cambiar el cargo
	//sin problemas.

	function DecisionCambioCargo($datos,$datos_estacion,$SW_USER_LEGAL)
	{
          if(empty($datos))
          {
               $datos=$_REQUEST['datos'];
               $datos_estacion=$_REQUEST['datos_estacion'];
          }

          $this->salida .= ThemeAbrirTabla('CAMBIO DE CARGO DE CAMA');

          $this->salida .= "	<table class='modulo_table_title' align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\">\n";
          $this->salida .= "		<tr class=\"modulo_table_title\">\n";
          if($swCambioCama)
          {
               $this->salida .= "		<td>HABITACION</td>\n";
               $this->salida .= "		<td>CAMA</td>\n";
          }
          $this->salida .= "			<td>PACIENTE</td>\n";
          $this->salida .= "			<td>IDENTIFICACION</td>\n";
          $this->salida .= "			<td>CUENTA</td>\n";
          $this->salida .$this->salida .= "			<td>INGRESO</td>\n";
          $this->salida .= "			<td>No ORDEN HOSPITALIZACIÓN</td>\n";
          $this->salida .= "	</tr>\n";
          $this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
          if($swCambioCama)
          {
               $this->salida .= "			<td>".$datos[9]."</td>\n";
               $this->salida .= "			<td>".$datos[8]."</td>\n";
          }
          $linkVerDatos = ModuloGetURL('app','EstacionE_Pacientes','user','CallMostrarDatosIngreso',array("ingresoID"=>$datos[4],"retorno"=>"DecisionCambioCargo","datos"=>$datos,"datos_estacion"=>$datos_estacion));
          $this->salida .= "			<td><a href='$linkVerDatos'>".$datos[0]." ".$datos[1]."</a></td>\n";
          $this->salida .= "			<td>".$datos[3]." ".$datos[2]."</td>\n";
          $this->salida .= "			<td>".$datos[6]."</td>\n";
          $this->salida .= "			<td>".$datos[4]."</td>\n";
          $this->salida .= "			<td>".$datos[5]."</td>\n";
          $this->salida .= "		</tr>\n";
   		$this->salida .= "	</table>\n";



          if($SW_USER_LEGAL=='verdadero')
          {
               $conteo=1;
          }
          else
          {
               //buscamos si este usuario es autorizador .... si no es pedimos buscar.
               $conteo=$this->BuscarAutorizacionParaCambioCargo($datos_estacion[estacion_id]);
          }

          if($conteo < 1)
          {
                    $action3 = ModuloGetURL('app','EstacionE_Pacientes','user','RevisarUser',array("datos"=>$datos,"datos_estacion"=>$datos_estacion,"spya"=>1));
                    $this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
                    $this->salida.="<br><br><table border=\"0\"  align=\"center\"   width=\"45%\" >";
                    $this->salida .="".$this->SetStyle("MensajeError")."";
                    $this->salida.="<tr>";
                    $this->salida .= "<td  colspan=\"2\"  align=\"center\" class=\"modulo_table_title\" >Autenticación de Usuario Autorizador de Cambio de Cargo</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr>";
                    $this->salida.="<tr class=\"modulo_list_claro\">";
                    $this->salida .= "<td   width=\"35%\" align=\"center\" class=\"".$this->SetStyle("usuario")."\">Usuario :</td>";
                    $this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"text\" align=\"center\" name=\"usuario\"</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"modulo_list_claro\">";
                    $this->salida .= "<td   width=\"35%\"  align=\"center\"  class=\"".$this->SetStyle("pass")."\">Password :</td>";
                    $this->salida .= "<td  align=\"center\"><input class=\"input-text\" type=\"password\" align=\"center\" name=\"pass\"</td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";

                    $this->salida.="<table align=\"center\" width='40%' border=\"0\">";
                    $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Cambiar\"></form></td>";


                    $href = ModuloGetURL('app','EstacionE_Pacientes','user','CallRetornoIngresarPaciente',array("datos_estacion"=>$datos_estacion,"datos"=>$datos));
                    $this->salida .= "           <form name=\"forma\" action=\"$href\" method=\"post\">";
                    $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
                    $this->salida .= "</tr>";
                    $this->salida.="</table><br>";
          }
          else
          {
                    $this->salida.="<br><table  width=\"100%\">";
                    $this->salida .="".$this->SetStyle("MensajeError")."";
                    $this->salida.="</table>";

                    //debemos darle prioridad a esta tabla, logicamente si esta el plan de este paciente
                    $dats=$this->Traer_Tipos_Cama_excepciones($datos_estacion['estacion_id'],$datos[7]);

                    if(!is_array($dats))
                    {
                         $dats=$this->Traer_Tipos_Cama($datos_estacion[estacion_id]);
                    }

                    if(is_array($dats))
                    {
                                   $href = ModuloGetURL('app','EstacionE_Pacientes','user','CallCambioCargoIngresarPaciente',array("datos_estacion"=>$datos_estacion,"datos"=>$datos,"spya"=>$SW_USER_LEGAL));
                                   $this->salida .= "           <form name=\"forma\" action=\"$href\" method=\"post\">";
                                   $this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"85%\">";
                                   $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                   $this->salida.="  <td>Cargo</td>";
                                   $this->salida.="  <td>Descripcion</td>";
                                   $this->salida.="  <td>Precio Lista</td>";
                                   $this->salida.="  <td></td>";
                                   $this->salida.="</tr>";

                                   for($i=0;$i<sizeof($dats);$i++)
                                   {
                                             if($datos[14] !=$dats[$i][cargo])
                                             {//este es el cargo.
                                                  if( $i % 2){ $estilo='modulo_list_claro';}
                                                  else {$estilo='modulo_list_oscuro';}
                                                  $this->salida.="<tr class=\"$estilo\" align=\"center\">";
                                                  $this->salida.="  <td>".$dats[$i][cargo]."</td>";
                                                  $desc=$this->GetDescripcionCama($dats[$i][cargo],'');
                                                  $this->salida.="  <td>$desc</td>";
                                                  $desc=urlencode($desc);
                                                  $this->salida.="  <td><input type='text' name='precio' value=".FormatoValor($dats[$i][precio_lista])." READONLY></td>";
                                                  $this->salida.="  <td><input type=radio name=opcion value=".$desc."$".$dats[$i][cargo]."$".$dats[$i][tipo_cama_id]."$".$dats[$i][tipo_clase_cama_id]."></td>";
                                                  $this->salida.="</tr>";
                                             }
                                   }
                    }
                    else
                    {
                              $this->salida.="<table  align=\"center\" border=\"0\"  width=\"85%\">";
                              $this->salida .= "    <td  align=\"center\"><label class='label_error'>NO HAY CONFIGURACION DE TIPOS DE CAMAS PARA ESTA ESTACIÓN</label>";
                              $this->salida .= "    </td>";


                    }
                    $this->salida.="</table>";
                    $this->salida.="<table align=\"center\" width='40%' border=\"0\">";
                    $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Cambiar\"></form></td>";


                    $href = ModuloGetURL('app','EstacionE_Pacientes','user','CallRetornoIngresarPaciente',array("datos_estacion"=>$datos_estacion,"datos"=>$datos));
                    $this->salida .= "           <form name=\"forma\" action=\"$href\" method=\"post\">";
                    $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
                    $this->salida .= "</tr>";
                    $this->salida.="</table><br>";

			}

          $this->salida .= themeCerrarTabla();
          return true;
	}


     function SetStyle($campo)
     {
          if ($this->frmError[$campo] || $campo=="MensajeError"){
               if ($campo=="MensajeError"){
                    $arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
                    return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
               }
               return ("label_error");
          }
     	return ("label");
       }


	 //funcion de mod estacion de enfermeria_pacientes
	/**
	*		MostrarDatosIngreso => muestra los datos del ingreso del paciente
	*
	*		Muestra los datos del ingreso del paciente
	*		Utilizada en la function del listado de pacientes de la estacion
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param integer => Ingreso del paciente a consultar datos del ingreso
	*		@param string => href a donde debe dirigirse el link regresar
	*   @param string => modulo donde se debe dirigir el link regresar
	*		@param array datos de la estacion
	*		@param array datos-->caso especial el cual contiene informacion del paciente..
	*		@return boolean
	*/
	function MostrarDatosIngreso($ingresoID,$retorno,$datos_estacion,$modulo='',$datos)
	{
		if(!$x = $this->GetDatosPaciente_Info($ingresoID))//funcion del api realizada por jaime
		{
			if(empty($modulo)){$modulo='EstacionE_Pacientes';}
			$mensaje = "NO SE ENCONTRARON LOS DATOS DEL PACIENTE";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app',$modulo,'user',$retorno,array("datos_estacion"=>$datos_estacion,"estacion"=>$datos_estacion,"datos"=>$datos));
			$boton = "REGRESAR";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		$ContactosPaciente = $this->GetContactosPaciente($ingresoID);

		$this->salida .= ThemeAbrirTabla('INFORMACI&Oacute;N DEL PACIENTE','60%');//[ '.$datos_estacion[descripcion5].' ] -
		$this->salida .= "<br><table align=\"center\"  width=70% cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table\">\n";
		
		$this->salida .= "	<tr class=\"modulo_table\">\n";
		$this->salida .= "		<td class=\"label\">RESPONSABLE</td><td class=\"modulo_list_claro\">".$x['nombre_tercero']."</td >\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "	<tr class=\"modulo_table\">\n";
		$this->salida .= "		<td class=\"label\">PLAN</td><td class=\"modulo_list_claro\">".$x['plan_descripcion']."</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "	<tr class=\"modulo_table\">\n";
		$this->salida .= "		<td class=\"label\">TIPO AFILIADO</td><td class=\"modulo_list_claro\">".$x['tipo_afiliado_nombre']."</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "</table>\n";
		
		$this->salida .= "<br><table width=70% align=\"center\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
		$this->salida .= "	<tr class=\"modulo_list_oscuro\">\n";
		$this->salida .= "		<td class=\"label\">PACIENTE</td><td class=\"modulo_list_claro\"><b>".strtoupper($x['primer_nombre'])." ".strtoupper($x['segundo_nombre'])." ".strtoupper($x['primer_apellido'])." ".strtoupper($x['segundo_apellido'])."</b></td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "	<tr class=\"modulo_list_oscuro\">\n";
		$this->salida .= "		<td class=\"label\">IDENTIFICACION</td><td class=\"modulo_list_claro\"><b>".$x['tipo_id_paciente']." ".$x['paciente_id']."</b></td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "	<tr class=\"modulo_list_oscuro\" >\n";
		$this->salida .= "		<td class=\"label\">HISTORIA CLINICA</td><td class=\"modulo_list_claro\">".$x['historia_prefijo']." ".$x['historia_numero']."</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "	<tr class=\"modulo_list_oscuro\">\n";
		$this->salida .= "		<td class=\"label\">SEXO</td><td class=\"modulo_list_claro\">".$x['sexo_id']."</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "	<tr class=\"modulo_list_oscuro\">\n";
		$this->salida .= "		<td class=\"label\">FECHA NACIMIENTO</td><td class=\"modulo_list_claro\">".$x['fecha_nacimiento']."</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "	<tr class=\"modulo_list_oscuro\">\n";
		$this->salida .= "		<td class=\"label\" nowrap=\"yes\">DIRECCION RESIDENCIA</td><td class=\"modulo_list_claro\" nowrap=\"yes\">".$x['residencia_direccion'].". ".$x['municipio'].", ".$x['departamento'].", ".$x['pais']."</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "	<tr class=\"modulo_list_oscuro\">\n";
		$this->salida .= "		<td class=\"label\" nowrap=\"yes\">TELEFONO RESIDENCIA</td><td class=\"modulo_list_claro\" nowrap=\"yes\">".$x['residencia_telefono']."</td>\n";
		$this->salida .= "	</tr>\n";
		if($ContactosPaciente && $ContactosPaciente != "ShowMensaje")
		for($i=0; $i<sizeof($ContactosPaciente); $i++)
		{
			$this->salida .= "<tr valign=\"top\">\n";
			$this->salida .= "	<td class=\"label\">ACUDIENTE ".($i+1)."</td>\n";
			$this->salida .= "	<td>".strtoupper($ContactosPaciente[$i][nombre_completo])."\n";
			if($ContactosPaciente[$i][parentesco]){
				$this->salida .= "			<br> PARENTESCO: ".$ContactosPaciente[$i][parentesco]."\n";
			}
			if($ContactosPaciente[$i][telefono]){
				$this->salida .= "			<br> TELEFONO: ".$ContactosPaciente[$i][telefono]."\n";
			}
			if($ContactosPaciente[$i][direccion]){
				$this->salida .= "			<br> DIRECCI&Oacute;N: ".$ContactosPaciente[$i][direccion]."\n";
			}
			if($i>0){
				$this->salida .= "		<br>";
			}
			$this->salida .= "		</td><td>&nbsp;</td>\n";
			$this->salida .= "	</tr>\n";
		}

		$this->salida .= "<tr><td align=\"center\" colspan=\"2\">&nbsp;</td></tr>\n";
		if(empty($modulo)){$modulo='EstacionE_Pacientes';}
  		$link = ModuloGetURL('app',$modulo,'user',$retorno,array("datos_estacion"=>$datos_estacion,"estacion"=>$datos_estacion,"datos"=>$datos));
		$this->salida .= "<tr><td align=\"center\" colspan=\"2\"><a href=\"$link\"><b><sub>REGRESAR</sub></b></a></td></tr>\n";
		$this->salida .= "</table><br>\n";
		$this->salida .= themeCerrarTabla();
		return true;
	}// fin MostrarDatosIngreso

	/**********************esta va para estacionE_ControlPaciente******************************/
	/**
	*		FormaMensaje => muestra mensajes al usuario
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param string => mensaje a mostrar
	*		@param string => titulo de la tabla
	*		@param string => action del form
	*		@param string => value del input-submit
	*		@return boolean
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton,$impresion)
	{
		$this->salida .= ThemeAbrirTabla($titulo)."<br>";
		$this->salida .= "<table width=\"60%\" align=\"center\" class=\"normal_10\" border='0'>\n";
		$this->salida .= "	<form name=\"formaMensaje\" action=\"$accion\" method=\"post\">\n";
		$this->salida .= "		<tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>\n";
		if(!empty($boton)){
			$this->salida .= "	<tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>\n";
		}
		else{
			$this->salida .= "	<tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr>\n";
		}
		$this->salida .= "	</form>\n";
		$this->salida .= "</table><br><br>\n";
		$this->salida .=$impresion;
		$this->salida .= themeCerrarTabla();
		return true;
	}//fin FormaMensaje

/**********************esta va para estacionE_ControlPaciente******************************/


//funcion del modulo estacione_medicamento
	//#######################################################################################
	// plan terapeutico
	//#######################################################################################
	/**
	*		ListMedicamentosPendientesXSolicitar
	*
	*		ListMedicamentosPendientesXSolicitar: muestra los datos obtenidos con la funcion "GetPacientesConMedicamentosPorSolicitar"
	*		la cual obtiene los pacientes con medicamentos_ordenados cuyo sw_estado=2 y tipo_despacho=4
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function ListMedicamentosPendientesXSolicitar($datos_estacion)
	{
		$pendientesXConfirmar = $this->GetPacientesConMedicamentosPorSolicitar($datos_estacion);//print_r($pendientesXConfirmar);
		if(!$pendientesXConfirmar){
			return false;
		}
		if($pendientesXConfirmar === "ShowMensaje")
		{
			$mensaje = "NO HAY MEDICAMENTOS ORDENADOS";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$boton = "VOLVER AL MEN&Uacute; ESTACION";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		else
		{
			$this->salida .= ThemeAbrirTabla('PACIENTES CON ORDENES DE MEDICAMENTOS [ '.$datos_estacion[descripcion5].' ]');
			$this->salida .= "<br><table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td>HABITACION</td>\n";
			$this->salida .= "				<td>CAMA</td>\n";
			$this->salida .= "				<td>PACIENTE</td>\n";
			$this->salida .= "				<td>IDENTIFICACION</td>\n";
			$this->salida .= "				<td colspan='3'>ACCIONES</td>\n";
			$this->salida .= "		</tr>\n";
			$i=0;
			foreach($pendientesXConfirmar as $key => $value)
			{
				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "			<tr class=\"$estilo\" align=\"center\">\n";
				$this->salida .= "				<td>".$value[pieza]."</td>\n";
				$this->salida .= "				<td>".$value[cama]."</td>\n";
				$this->salida .= "				<td>".$value[primer_nombre]." ".$value[segundo_nombre]." ".$value[primer_apellido]." ".$value[segundo_apellido]."</td>\n";
				$this->salida .= "				<td>".$value[tipo_id_paciente]." ".$value[paciente_id]."</td>\n";
				$href = ModuloGetURL('app','EstacionE_Pacientes','user','CallVerMedicamentosPorSolicitarPaciente',array("Paciente"=>$value,"datos_estacion"=>$datos_estacion));//"Paciente"=>$Paciente,
				$this->salida .= "				<td align=\"center\"><a href=\"$href\">Ver Medicamentos</a></td>\n";
				$hrefTD = ModuloGetURL('app','EstacionE_Pacientes','user','CallFrmImpresionTarjetasDroga',array("datos_estacion"=>$datos_estacion,"datos_paciente"=>$value));
				$this->salida .= "				<td align=\"center\"><a href=\"$hrefTD\">Imprimir Tarjeta Droga</a></td>\n";
				$hrefLP = ModuloGetURL('app','EstacionE_Pacientes','user','CallFrmImpresionLiquidosParenterales',array("datos_estacion"=>$datos_estacion,"datos_paciente"=>$value));
				$this->salida .= "				<td align=\"center\"><a href=\"$hrefLP\">Imprimir Etiqueta Liquidos</a></td>\n";
				$this->salida .= "		</tr>\n";
				$i++;
			}
			$this->salida .= "	</table>\n";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<div align='center' class='normal_10'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
			$this->salida .= themeCerrarTabla();
		}
		return true;
	}//fin	ListMedicamentosPendientesXSolicitar()
//funcion del modulo estacione_medicamento

	/**
	*		VerMedicamentosPorSolicitarPaciente
	*
	*		Muestra los medicamentos ordenados y "vigentes" (sw_estado=2) del paciente seleccionado
	*		no se restringir&aacute; el numero de pedidos por orden ni tampoco las cantidades a solicitar
	*		y solo se pedir&aacute; a la bodega recetada por el medico
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param array => datos del paciente
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function VerMedicamentosPorSolicitarPaciente($datosPaciente,$datos_estacion)
	{
		$SolicitadosBodega = $this->GetMedicamentosPendientesSolicitadosBodega($datosPaciente[ingreso],$datos_estacion);
		$SolicitadosPaciente = $this->GetMedicamentosPendientesSolicitadosPaciente($datosPaciente[ingreso],$datos_estacion);
		$Medicamentos = $this->GetMedicamentosRecetados($datosPaciente[ingreso],$datos_estacion);
		$Mezclas = $this->GetMezclasRecetadas($datosPaciente[ingreso],$datos_estacion);

		//no se porque habia comentareado lo siguiente:
		if (!$Medicamentos || !$Mezclas){
			return false;
		}

		if($Medicamentos === "ShowMensaje" && $Mezclas === "ShowMensaje")
		{
			$mensaje = "NO SE ENCONTRARON LOS DETALLES DE LOS MEDICAMENTOS PENDIENTES DEL PACIENTE";
			$titulo = "MENSAJE";
			$accion = ModuloGetURL('app','EstacionE_Pacientes','user','CallListMedicamentosPendientesXSolicitar',array("datos_estacion"=>$datos_estacion));
			$boton = "IR A MEDICAMENTOS PENDIENTES POR CONFIRMAR";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		else
		{//encabezado comun para mediamentos y mezclas
			$this->salida .= ThemeAbrirTabla('LISTADO DE MEDICAMENTOS ORDENADOS - [ '.$datos_estacion[descripcion5].' ]');
			$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td>\n";
			$this->salida .= "			<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr class=\"modulo_table_title\">\n";
			$this->salida .= "					<td>HABITACION</td>\n";
			$this->salida .= "					<td>CAMA</td>\n";
			$this->salida .= "					<td>PACIENTE</td>\n";
			$this->salida .= "					<td>ID</td>\n";
			$this->salida .= "					<td>CUENTA</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr align='center' class='modulo_list_oscuro'>\n";
			$this->salida .= "					<td><b>".$datosPaciente[pieza]."</b></td>\n";
			$this->salida .= "					<td><b>".strtoupper($datosPaciente[cama])."</td>\n";
			$this->salida .= "					<td><b>".$datosPaciente[primer_nombre]." ".$datosPaciente[segundo_nombre]." ".$datosPaciente[primer_apellido]." ".$datosPaciente[segundo_apellido]."</b></td>\n";
			$this->salida .= "					<td><b>".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</b></td>\n";
			$this->salida .= "					<td><b>".$datosPaciente[numerodecuenta]."</b></td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr><td>&nbsp;</td></tr>\n";
			//fin encabezado comun

			if(($SolicitadosPaciente != "ShowMensaje" && is_array($SolicitadosPaciente) )|| ($SolicitadosBodega != "ShowMensaje" && is_array($SolicitadosBodega)))
			{
				$action = ModuloGetURL('app','EstacionEnfermeria','user','CancelarSolicitudesMedicamentos',array('datosPaciente'=>$datosPaciente,"datos_estacion"=>$datos_estacion));
				$this->salida .= "<form name='CancelarSolicitudesMedicamentos' method=\"POST\" action=\"$action\">\n";
				$this->salida .= "<tr> \n";
				$this->salida .= "	<td colspan=\"2\"> \n";
				$this->salida .= "		<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\" class=\"modulo_table_list\">\n";
				$this->salida .= "			<tr class=\"modulo_table_title\"><td>MEDICAMENTOS SOLICITADOS</td></tr>\n";
				$this->salida .= "			<tr>\n";
				$this->salida .= "				<td>\n";
				$this->salida .= "					<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			}

			if($SolicitadosBodega != "ShowMensaje" && is_array($SolicitadosBodega))
			{
				$this->salida .= "						<tr> \n";
				$this->salida .= "							<td colspan=\"2\"> \n";
				$this->salida .= "								<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
				$this->salida .= "									<tr class=\"modulo_table_list_title\"><td colspan=\"10\" height=\"25\">LISTADO DE MEDICAMENTOS SOLICITADOS A BODEGA</td></tr>\n";
				$this->salida .= "									<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "										<td>CANCELAR</td>\n";
				$this->salida .= "										<td>SOLICITUD</td>\n";
				$this->salida .= "										<td>FECHA <br> PEDIDO</td>\n";
				$this->salida .= "										<td>ESTADO</td>\n";
				$this->salida .= "										<td>MEZCLA</td>\n";
				$this->salida .= "										<td>MEDICAMENTO</td>\n";
				$this->salida .= "										<td>CANT <BR> SOLICITADA</td>\n";
				$this->salida .= "									</tr>\n";
				/*solicitud_id	consecutivo_d	?column?	medicamento_id	evolucion_id	cant_solicitada	forma_farmaceutica	nommedicamento	ff	fecha_solicitud	sw_estado*/
				//para contar el rowspan
				foreach ($SolicitadosBodega as $key=>$value)//while ($data = $result->FetchNextObject())
				{
					$vect[$value[solicitud_id]][0]++;
					$vect[$value[solicitud_id]][1]++;
				}
				$i=0;
				$estados = array(0=>'Sin despacho', 1=>'Despachado', 2=>'Recibido', 3=>'Cancelado');//
				foreach ($SolicitadosBodega as $key=>$value)//while ($data = $result->FetchNextObject())
				{
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "								<tr class=\"$estilo\" align=\"center\">\n";

					if ((array_key_exists($value[solicitud_id], $vect)) && ($vect[$value[solicitud_id]][0] == $vect[$value[solicitud_id]][1]))
					{
						if($value[sw_estado] == 0){
							$cancelar = "<input type='checkbox' name='CancelarSolicitudBodega[]' value='".$value[solicitud_id]."'>";
						}
						else{
							$cancelar = "&nbsp;";
						}
						$this->salida .= "								<td rowspan='".$vect[$value[solicitud_id]][0]."'>".$cancelar."</td>\n";
						$this->salida .= "								<td rowspan='".$vect[$value[solicitud_id]][0]."'>".$value[solicitud_id]."</td>\n";
						$this->salida .= "								<td rowspan='".$vect[$value[solicitud_id]][0]."'>".$value[fecha_solicitud]."</td>\n";
						$this->salida .= "								<td rowspan='".$vect[$value[solicitud_id]][0]."'>".$estados[$value[sw_estado]]."</td>\n";
					}
					if(empty($value[mezcla_recetada_id])) { $valor = "---"; }
					else { $valor = $value[mezcla_recetada_id]; }

					$this->salida .= "									<td>".$valor."</td>\n";
					$this->salida .= "									<td align='left'>".$value[medicamento_id]." => ".$value[nommedicamento]." ".$value[ff]."</td>\n";
					$this->salida .= "									<td>".$value[cant_solicitada]."</td>\n";//.-."..".-."..".-.".."
					$vect[$value[solicitud_id]][1]--;
					$this->salida .= "								</tr>\n";
					$i++;
				}
				$this->salida .= "								</table>\n";
				$this->salida .= "							</td>\n";
				$this->salida .= "						</tr>\n";
				$this->salida .= "						<tr><td>&nbsp;</td></tr>\n";//espacio
			}

			if($SolicitadosPaciente != "ShowMensaje" && is_array($SolicitadosPaciente))
			{
				$this->salida .= "						<tr> \n";
				$this->salida .= "							<td colspan=\"2\"> \n";
				$this->salida .= "								<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
				$this->salida .= "									<tr class=\"modulo_table_list_title\"><td colspan=\"10\" height=\"25\">LISTADO DE MEDICAMENTOS SOLICITADOS AL PACIENTE</td></tr>\n";
				$this->salida .= "									<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "										<td>CANCELAR</td>\n";//&nbsp;<input type=\"checkbox\" name=\"selectodo\" onclick=\"Seleccionartodos(this.form,this.checked)\">
				$this->salida .= "										<td>FECHA <br> PEDIDO</td>\n";
				$this->salida .= "										<td>MEZCLA</td>\n";
				$this->salida .= "										<td>MEDICAMENTO</td>\n";
				$this->salida .= "										<td>CANT <BR> SOLICITADA</td>\n";
				$this->salida .= "									</tr>\n";

				foreach ($SolicitadosPaciente as $key=>$value)
				{
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "								<tr class=\"$estilo\" align=\"center\">\n";
					$this->salida .= "									<td><input type='checkbox' name='CancelarSolicitudPaciente[]' value='".$value[consecutivo].".-.".$value[mezcla_recetada_id]."'></td>\n";
					$this->salida .= "									<td>".$value[fecha_solicitud]."</td>\n";
					$this->salida .= "									<td>".$value[mezcla_recetada_id]."</td>\n";
					$this->salida .= "									<td align='left'>".$value[medicamento_id]." => ".$value[nommedicamento]." ".$value[ff]."</td>\n";
					$this->salida .= "									<td>".$value[cant_solicitada]."</td>\n";//.-."..".-."..".-.".."
					$this->salida .= "								</tr>\n";
					$i++;
				}
				$this->salida .= "								</table>\n";
				$this->salida .= "							</td>\n";
				$this->salida .= "						</tr>\n";
			}
			if(($SolicitadosPaciente != "ShowMensaje" && is_array($SolicitadosPaciente) )|| ($SolicitadosBodega != "ShowMensaje" && is_array($SolicitadosBodega)))
			{
				//$this->salida .= "			<tr><td>&nbsp;</td></tr>\n";//espacio
				$this->salida .= "						<tr align='center'><td><input type='submit' value='CANCELAR SOLICITUDES SELECCIONADAS' name='SubmitCancelarPedidos' class='input-submit'></td></tr>\n";//espacio
				$this->salida .= "					</table>\n";
				$link = ModuloGetURL('app','EstacionEnfermeria','user','CallListMedicamentosPendientesXSolicitar',array("datos_estacion"=>$datos_estacion));
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$link."'>Volver a Medicamentos ordenados</a>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
				$this->salida .= "				</td> \n";
				$this->salida .= "			</tr> \n";
				$this->salida .= "		</table> \n";
				$this->salida .= "	</td> \n";
				$this->salida .= "</tr> \n";
				$this->salida .= "</form>\n";//espacio
			}

			######################### MEDICAMENTOS ORDENADOS ######################################
			if($Medicamentos != "ShowMensaje" || $Mezclas != "ShowMensaje")
			{
				$action = ModuloGetURL('app','EstacionEnfermeria','user','PedirMedicamentos',array("datos_estacion"=>$datos_estacion));
				$this->salida .= "<form method=\"POST\" action=\"$action\">\n";
				$this->salida .= "<tr> \n";
				$this->salida .= "	<td colspan=\"2\">\n<br>";
				$this->salida .= "		<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\" class=\"modulo_table_list\">\n";
				$this->salida .= "			<tr class=\"modulo_table_title\"><td>MEDICAMENTOS ORDENADOS</td></tr>\n";
				$this->salida .= "			<tr>\n";
				$this->salida .= "				<td> \n";
				$this->salida .= "					<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			}

			if($Medicamentos != "ShowMensaje")
			{
				$this->salida .= "						<tr>\n";
				$this->salida .= "							<td>\n";
				$this->salida .= "								<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
				$this->salida .= "									<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "										<td colspan='7' height='40'>MEDICAMENTOS ORDENADOS</td>\n";
				$this->salida .= "									</tr>\n";
				$this->salida .= "									<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "										<td>EVO</td>\n";
				$this->salida .= "										<td>MEDICAMENTO</td>\n";
				$this->salida .= "										<td>POS</td>\n";
				$this->salida .= "										<td>POSOLOG&Iacute;A</td>\n";
				$this->salida .= "										<td>CANT. <BR> RECETADA</td>\n";
				$this->salida .= "										<td>CANT. A<BR> SOLICITAR</td>\n";
				$this->salida .= "										<td>SOLICITAR A</td>\n";
				$this->salida .= "									</tr>\n";
				$i=0;
				foreach ($Medicamentos as $key=>$value)//while ($data = $result->FetchNextObject())
				{
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
					$this->salida .= "								<tr class=\"$estilo\" align=\"center\">\n";
					$this->salida .= "									<td>".$value[evolucion_id]."</td>\n";
					$this->salida .= "									<td align='left'>".$value[medicamento_id]." => ".$value[nommedicamento]." ".$value[nomff]."</td>\n";

					if($value[pos] == 0) {$Pos = "No Pos"; } else {$Pos = "Pos";}
					$this->salida .= "									<td>".$Pos."</td>\n";

					$datos = $this->ObtenerPlanTerapeutico($value[evolucion_id],$value[medicamento_id],$datos_estacion);
					$xc = $this->Posologia($datos[0]);

					if(!empty($value[indicacion_suministro])){
						$indicacion = "<br>".$value[indicacion_suministro];} else {$indicacion = "";
					}
					$this->salida .= "									<td>".$xc." ".$indicacion."</td>\n";
					$this->salida .= "									<td>".$value[cantidad_total]." ".$value[nomff]."</td>\n";
					$this->salida .= "									<td><input type=\"text\" name=\"cantidad[]\" value=\"".$value[cantidad_total]."\" size=\"8\" align='right' class=\"input-text\"\"> </td>\n";
					if(!$bodega = $this->GetBodegaDelDepartamento($datos_estacion)){
						return false;
					}
					$this->salida .= "									<td><select name=\"MedicamentosXconfirmar[]\"  class=\"select\">\n";
					$this->salida .= "												<option value=\"NoPedir.-.".$i."\">------</option> \n";
					$this->salida .= "												<option value=\"AlPaciente.-.".$i.".-.".$value[medicamento_id].".-.".$value[evolucion_id]."\">Paciente</option> \n";
					$this->salida .= "												<option value=\"".$bodega[bodega].".-.".$i.".-.".$value[medicamento_id].".-.".$value[evolucion_id].".-.".$value[bodega]."\">".$bodega[descripcion]."</option> \n";
					$this->salida .= "											</select>\n";
					$this->salida .= "									</td>\n";
					$this->salida .= "								</tr>\n";
					$i++;
				}
				$this->salida .= "								</table>\n";
				$this->salida .= "							</td>\n";
				$this->salida .= "						</tr>\n";
			}//hay medicamentos

			//###################################################################MAY MEZCLAS
			if($Mezclas != "ShowMensaje")
			{
				//hacer un vector de mezclas para ordenar por mezcla
				$vecMezclas = array();
				foreach ($Mezclas as $key=>$value)//while ($data = $result->FetchNextObject())
				{
					$vecM = $value[mezcla_recetada_id];

					if(strcmp($value[mezcla_recetada_id],$vecM) == 0)
					{ $contMezclas[$value[mezcla_recetada_id]]++;
						//$vect = [$value[mezcla_recetada_id]]++;
					}
				}
				$vect = $contMezclas;
				$this->salida .= "						<tr><td>&nbsp;</td></tr>\n";//espacio
				$this->salida .= "						<tr>\n";
				$this->salida .= "							<td>\n";
				$this->salida .= "								<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
				$this->salida .= "									<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "										<td colspan='9' height='40'>MEZCLAS RECETADAS</td>\n";
				$this->salida .= "									</tr>\n";
				$this->salida .= "									<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "										<td>EVO</td>\n";
				$this->salida .= "										<td>MEZCLA</td>\n";
				$this->salida .= "										<td>POSOLOG&Iacute;A</td>\n";
				$this->salida .= "										<td>MEDICAMENTO</td>\n";
				$this->salida .= "										<td>POS</td>\n";
				$this->salida .= "										<td>CANT. <BR> RECETADA</td>\n";
				$this->salida .= "										<td>CANT. A <BR> SOLICITAR</td>\n";
				$this->salida .= "										<td>SOLICITAR A</td>\n";
				$this->salida .= "									</tr>\n";

				for($i=0; $i<sizeof($Mezclas); $i++) //foreach ($Mezclas as $key=>$value)//while ($data = $result->FetchNextObject())
				{
					if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";

					if($contMezclas[$Mezclas[$i][mezcla_recetada_id]] == $vect[$Mezclas[$i][mezcla_recetada_id]])
					{//." ".$Mezclas[$i][nomff]
						$this->salida .= "							<tr class=\"$estilo\" align=\"center\">\n";
						$this->salida .= "								<td rowspan=\"".$contMezclas[$Mezclas[$i][mezcla_recetada_id]]."\">".$Mezclas[$i][evolucion_id]."</td>\n";
						$this->salida .= "								<td rowspan=\"".$contMezclas[$Mezclas[$i][mezcla_recetada_id]]."\">".$Mezclas[$i][mezcla_recetada_id]."</td>\n";
						$xx = $this->ObtenerPlanTerpeuticoMezclas($Mezclas[$i][evolucion_id],$Mezclas[$i][mezcla_recetada_id]);
						$xc = $this->PosologiaMezcla($xx[0]);
						if(!empty($Mezclas[$i][observaciones])){$observa = "<br>OBSERVACIONES: ".$Mezclas[$i][observaciones];} else { $observa = "";}
						$this->salida .= "								<td rowspan=\"".$contMezclas[$Mezclas[$i][mezcla_recetada_id]]."\">".$xc." ".$observa."</td>\n";//
						$this->salida .= "								<td align='left'>".$Mezclas[$i][medicamento_id]." => ".$Mezclas[$i][nommedicamento]." ".$Mezclas[$i][nomff]."</td>\n";
						if($Mezclas[$i][sw_pos] == 0) {$Pos = "No Pos"; } else {$Pos = "Pos";}
						$this->salida .= "								<td>".$Pos."</td>\n";
						$this->salida .= "								<td>".$Mezclas[$i][cantidad]." ".$Mezclas[$i][nomff]."</td>\n";
						$this->salida .= "								<td> <input type=\"text\" name=\"cantidadMezclas[]\" value=\"".$Mezclas[$i][cantidad]."\" size=\"8\" align='right' class=\"input-text\" \"> </td> \n";//
						$this->salida .= "								<input type=\"hidden\" name=\"MezclasXcantidad[]\" value=\"".$Mezclas[$i][mezcla_recetada_id].".-.".$Mezclas[$i][medicamento_id].".-.".$Mezclas[$i][evolucion_id].".-.".$i."\"> </td>\n";
						//$this->salida .= "							<td> <input type=\"checkbox\" name=\"MezclasXconfirmar[]\" value=\"".$Mezclas[$i][mezcla_recetada_id].".-.".$Mezclas[$i][medicamento_id].".-.".$Mezclas[$i][evolucion_id].".-.".$Mezclas[$i][bodega].".-.".$Mezclas[$i][cantidad].".-.".$i."\"> </td> \n";
						if(!$bodega = $this->GetBodegaDelDepartamento($datos_estacion)){
							return false;
						}
						$this->salida .= "								<td rowspan=\"".$contMezclas[$Mezclas[$i][mezcla_recetada_id]]."\">\n";
						$this->salida .= "									<select name=\"MezclasXconfirmar[".$Mezclas[$i][mezcla_recetada_id]."]\"  class=\"select\">\n";
						$this->salida .= "										<option value=\"NoPedir\">------</option> \n";
						$this->salida .= "										<option value=\"AlPaciente\">Paciente</option> \n";
						$this->salida .= "	  								<option value=\"".$bodega[bodega]."\">".$bodega[descripcion]."</option> \n";
						$this->salida .= "									</select>\n";
						$this->salida .= "								</td>\n";
						$this->salida .= "							</tr>\n";
						$vect[$Mezclas[$i][mezcla_recetada_id]]--;
					}
					else
					{
						$this->salida .= "							<tr class=\"$estilo\" align=\"center\">\n";
						$this->salida .= "								<td align='left'>".$Mezclas[$i][medicamento_id]." => ".$Mezclas[$i][nommedicamento]." ".$Mezclas[$i][nomff]."</td>\n";
						/*aqui no se pone la bodega porque tiene rowspan*/
						if($Mezclas[$i][sw_pos] == 0) {$Pos = "No Pos"; } else {$Pos = "Pos";}
						$this->salida .= "								<td>".$Pos."</td>\n";
						$this->salida .= "								<td>".$Mezclas[$i][cantidad]." ".$Mezclas[$i][nomff]."</td>\n";
						$this->salida .= "								<td><input type=\"text\" name=\"cantidadMezclas[]\" value=\"".$Mezclas[$i][cantidad]."\" size=\"8\" align='right' class=\"input-text\"\"> </td>\n";
						$this->salida .= "								<input type=\"hidden\" name=\"MezclasXcantidad[]\" value=\"".$Mezclas[$i][mezcla_recetada_id].".-.".$Mezclas[$i][medicamento_id].".-.".$Mezclas[$i][evolucion_id].".-.".$i."\"> </td>\n";
						$this->salida .= "							</tr>\n";
					}//fin else
				}//fin for
				$this->salida .= "								</table>\n";
				$this->salida .= "							</td>\n";
				$this->salida .= "						</tr>\n";
			}//fin mezclas

			if($Medicamentos != "ShowMensaje" || $Mezclas != "ShowMensaje")
			{
				$this->salida .= "						<tr><td>&nbsp;</td></tr>\n";//espacio
				$this->salida .= "						<tr align=\"center\"><td><input type=\"submit\" name=\"submit\" value=\"SOLICITAR MEDICAMENTOS SELECCIONADOS\" class=\"input-submit\"> <input type=\"reset\" name=\"Reset\" value=\"REESTABLECER\" class=\"input-submit\"></td></tr>\n";
				$this->salida .= "						<input type=\"hidden\" name=\"datosPaciente\" value=\"".urlencode(addslashes(serialize($datosPaciente)))."\">";
				$this->salida .= "					</table>\n";
				$link = ModuloGetURL('app','EstacionEnfermeria','user','CallListMedicamentosPendientesXSolicitar',array("datos_estacion"=>$datos_estacion));
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$this->salida .= "<div class='normal_10' align='center'><br><a href='".$link."'>Volver a Medicamentos ordenados</a>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
				$this->salida .= "				</td> \n";
				$this->salida .= "			</tr> \n";
				$this->salida .= "		</table> \n";
				$this->salida .= "	</td> \n";
				$this->salida .= "</tr> \n";
				$this->salida .= "</form>\n";//espacio
			}
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
	}//fin VerMedicamentosPorSolicitarPaciente

/*
		*		FrmImpresionTarjetasDroga
		*
		*		Muestra el formato de impresion con los datos de los medicamentos ordenados
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@param array datos de la estacion
		*		@param array datos del paciente
		*		@return bool
		*/
		function FrmImpresionTarjetasDroga($datos_estacion,$datos_paciente)
		{
			$Medicamentos = $this->GetMedicamentosRecetados($datos_paciente[ingreso],$datos_estacion);

			if (!$Medicamentos){// || !$Mezclas
				return false;
			}

			if($Medicamentos === "ShowMensaje")//&& $Mezclas === "ShowMensaje"
			{
				$mensaje = "NO SE ENCONTRARON LOS DETALLES DE LOS MEDICAMENTOS ORDENADOS AL PACIENTE";
				$titulo = "MENSAJE";
				$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$boton = "VOLVER A MEN&Uacute; ESTACI&Oacute;N";
				$this->FormaMensaje($mensaje,$titulo,$href,$boton);
				return true;
			}
			if($Medicamentos != "ShowMensaje")
			{
				foreach ($Medicamentos as $key => $value)
				{
					$this->salida .= "<table width=\"310\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
					$this->salida .= "	<tr><td colspan='2' align='center' class='label' height='30'>TARJETA DROGA</td></tr>\n";
					$this->salida .= "	<tr><td class='label'>FECHA</td><td class='normal_10'>".date("d-m-Y")."</td></tr>\n";
					$this->salida .= "	<tr><td class='label'>CAMA</td><td class='normal_10'>".$datos_paciente[cama]."</td></tr>\n";
					$this->salida .= "	<tr><td class='label'>NOMBRE</td><td class='normal_10'>".$datos_paciente[primer_nombre]." ".$datos_paciente[segundo_nombre]." ".$datos_paciente[primer_apellido]." ".$datos_paciente[segundo_apellido]."</td></tr>\n";
					$this->salida .= "	<tr><td class='label'>MEDICAMENTO</td><td class='normal_10'>".$value[nommedicamento]." ".$value[nomff]." ".$value[concentracion]."</td></tr>\n";
					$Posologia = $this->Posologia($value);
					$this->salida .= "	<tr><td class='label'>DOSIS</td><td class='normal_10'>".$Posologia."</td></tr>\n";
					$this->salida .= "	<tr><td class='label'>VIA</td><td class='normal_10'>".$value[viaadmin]."</td></tr>\n";
					/*$this->salida .= "	<tr><td class='label'>HORARIO</td><td></td></tr>\n";*/
					$this->salida .= "	<tr align='center'><td class='label' colspan='2' height='50' valign='bottom'>____________________________</td></tr>\n";
					$this->salida .= "	<tr align='center'><td class='label' colspan='2'>Firma Responsable</td></tr>\n";
					$this->salida .= "</table><br>\n";
				}
			}//hay medicamentos
			$link = ModuloGetURL('app','EstacionE_Pacientes','user','CallListMedicamentosPendientesXSolicitar',array("datos_estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$link."'>Volver a Medicamentos ordenados</a><br>";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
			return true;
		}

		/*
		*		FrmImpresionLiquidosParenterales
		*
		*		Muestra el formato de impresion de las mezclas recetadas
		*
		*		@Author Rosa Maria Angel
		*		@access Private
		*		@param array datos de la estacion
		*		@param array datos del paciente
		*		@return bool
		*/
		function FrmImpresionLiquidosParenterales($datos_estacion,$datos_paciente)
		{
			$Mezclas = $this->GetMezclasRecetadas($datos_paciente[ingreso],$datos_estacion);

			if (!$Mezclas){
				return false;
			}
			if($Mezclas === "ShowMensaje")
			{
				$mensaje = "NO SE ENCONTRARON LOS DETALLES DE LOS MEDICAMENTOS DE LAS MEZCLAS RECETADAS AL PACIENTE";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
				$boton = "VOLVER A MEN&Uacute; ESTACI&Oacute;N";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}

			if($Mezclas != "ShowMensaje")
			{//hacer un vector de mezclas para ordenar por mezcla
				$vecMezclas = array();
				foreach ($Mezclas as $key=>$value)
				{
					$vecM = $value[mezcla_recetada_id];
					if(strcmp($value[mezcla_recetada_id],$vecM) == 0){
						$contMezclas[$value[mezcla_recetada_id]][] = $value;
					}
				}
				$i=0;
				foreach ($contMezclas as $key => $value)
				{
					$this->salida .= "<table width=\"400\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
					$this->salida .= "	<tr><td colspan='2' align='center' class='label' height='30'>LIQUIDOS PARENTERALES</td></tr>\n";
					$this->salida .= "	<tr><td class='label'>FECHA</td><td class='normal_10'>".date("d-m-Y")."</td></tr>\n";
					$this->salida .= "	<tr><td class='label'>CAMA</td><td class='normal_10'>".$datos_paciente[cama]."</td></tr>\n";
					$this->salida .= "	<tr><td class='label'>NOMBRE</td><td class='normal_10'>".$datos_paciente[primer_nombre]." ".$datos_paciente[segundo_nombre]." ".$datos_paciente[primer_apellido]." ".$datos_paciente[segundo_apellido]."</td></tr>\n";
					$this->salida .= "	<tr><td class='label' valign='top'>MEDICAMENTO</td><td class='normal_10'>\n";

					foreach ($value as $A => $B)
					{
						if($B[solucion] == 1){
							$solucion = $B[nommedicamento]." ".$B[nomff]." ".$B[cantidad]." ".$B[nomff]."<br>";
						}
						else{
							$this->salida .= $B[nommedicamento]." ".$B[nomff]." ".$B[cantidad]." ".$B[nomff]."<br>";
						}
					}
					$this->salida .= "	</td></tr>\n";
					$this->salida .= "	<tr><td class='label' valign='top'>SOLUCI&Oacute;N</td><td class='normal_10'>$solucion</td></tr>\n";
					if($value[$i][frecuencia] == 0.00){
						$frec = "Bolo";
					}
					else{
						$frec = $value[$i][frecuencia]." Hora(s)";
					}
					$this->salida .= "	<tr><td class='label' valign='top' nowrap='yes'>PASAR SOLUCI&Oacute;N EN</td><td class='normal_10'>$frec</td></tr>\n";
					$this->salida .= "	<tr><td class='label' valign='top' nowrap='yes'>GOTEO</td><td class='normal_10'>".$value[$i][cantidad_calculo]." ".$value[$i][des_tipo_calculo]."</td></tr>\n";
					if(!empty($value[$i][observaciones])){$observa = "<br>OBSERVACIONES: ".$value[$i][observaciones];} else { $observa = "";}
					/*$xx = $this->ObtenerPlanTerpeuticoMezclas($value[$i][evolucion_id],$value[$i][mezcla_recetada_id]);
					$xc = $this->PosologiaMezcla($xx[0]);
					$this->salida .= "	<tr><td class='label' valign='top'>DOSIS</td><td>".$xc." ".$observa."</td></tr>\n";*/
					$this->salida .= "	<tr align='center'><td class='label' colspan='2' height='50' valign='bottom'>____________________________</td></tr>\n";
					$this->salida .= "	<tr align='center'><td class='label' colspan='2'>Firma Responsable</td></tr>\n";
					$this->salida .= "</table><br>\n";
					$i++;
				}
			}//fin mezclas
			$link = ModuloGetURL('app','EstacionEnfermeria','user','CallListMedicamentosPendientesXSolicitar',array("datos_estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$link."'>Volver a Medicamentos ordenados</a><br>";
			$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
			$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
			return true;
		}//FrmImpresionLiquidosParenterales


		function FormaSacarPacienteConsultaUrg($datos_estacion,$tipo,$pac,$nombre,$ingreso)
		{
				$this->salida .= ThemeAbrirTabla('PACIENTES POR EGRESAR CONSULTA DE URGENCIAS- [ '.$datos_estacion[descripcion5].' ]')."<BR>";
				$this->salida .= "<table align='center' width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
				$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "		<td colspan='2' width=\"40%\">PACIENTE</td>\n";
				$this->salida .= "		<td width=\"13%\">ACCIONES</td>\n";
				$this->salida .= "		<td colspan='2' width=\"20%\" >RESUMEN HC</td>\n";
				$this->salida .= "	</tr>\n";
				$reporte= new GetReports();
				$this->salida .= "<tr class=\"modulo_list_claro\">\n";
				$this->salida .= "	<td nowrap width=\"2%\">\n";
				$this->salida .= "	<img src='".GetThemePath()."/images/atencion_citas.png' width=18 height=18 align='middle' border=0>\n";
				$this->salida .= "	</td>\n";
				$this->salida .= "	<td nowrap>".$nombre."</label></td>\n";
				$conteo_evolucion=$this->BuscarEvolucion_Pac($ingreso);//revisemos si tiene evoluciones abiertas.
			if($conteo_evolucion < 1)
			{
				$link = ModuloGetURL('app','EstacionE_Pacientes','user','DarSalidaCosultaUrgencias',array("ingreso"=>$ingreso,"tipo_id"=>$tipo,"pac"=>$pac,"datos_estacion"=>$datos_estacion));
				$this->salida .= "	<td align='center'><a href=\"$link\">Dar de alta</a></td>\n";
			}
			else
			{
					$this->salida .= "	<td align='center'>Dar de alta</td>\n";
			}	
				$this->salida .= "	<td colspan='2' align='center'>\n";
				$this->salida.=$reporte->GetJavaReport_HC($ingreso,array());
				$funcion=$reporte->GetJavaFunction();
				$this->salida .= "	<a href=\"javascript:$funcion\">RESUMEN HC</a>\n";
				$this->salida .= "	</td>\n";
				$this->salida .= "	</tr>\n";
				//$this->salida .= "	</table>\n";
				//$solicitudesDevPendientes = $this->VerificaSolicitudesDevolucionPendientes($value['ingreso']);
			
				if($conteo_evolucion < 1)
			{
					$linknota = ModuloGetURL('app','EstacionE_Pacientes','user','Insertar_Nota_Enfermeria',array("ing"=>$ingreso,"datos_estacion"=>$datos_estacion,"tipo_id"=>$tipo,"pac"=>$pac,"cama"=>$cama,"nombre"=>$nombre,"retorno"=>1));
					$this->salida .= "<form name=forma method=\"POST\" action=$linknota>	<tr><td class='modulo_list_claro' colspan=2 align='center'><input  type=hidden name='ingreso' value=".$value['ingreso']."><input  class='input-submit' type=submit name='enviar' value='Guardar Información'></td><td class='hc_table_submodulo_list_title' colspan='5'><textarea name='obs'  cols=80 rows=8>".$_REQUEST['obs']."</textarea><sub><b>NOTA FINAL</b></sub></td>&nbsp;&nbsp;&nbsp;&nbsp;\n";
					$this->salida .= "	</td></tr></form>\n";
					$this->salida .= "</table><br>\n";
			}
			else
			{
					$this->salida .= "<tr align='center'><td colspan='5' class='modulo_list_claro' ><label class='label_mark'>NO SE PUEDE SACAR EL PACIENTE DEBIDO A QUE TIENE EVOLUCIONES ABIERTAS !</label>\n";
					$this->salida .= "	</td></tr>\n";
					$this->salida .= "</table><br>\n";

			}

			if($conteo_evolucion >= 1)
			{
				$datos=$this->BuscarEvolucion_Pac($ingreso,1);//revisemos si tiene evoluciones abiertas.
				$this->salida.="<table  align=\"center\" border=\"0\" width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_list_title \">";
				$this->salida.="  <td colspan='4'>INFORMACION DE EVOLUCIONES ABIERTAS</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_table_list_title \">";
				$this->salida.="  <td>No.EVOLUCION</td>";
				$this->salida.="  <td>ESPECIALIDAD</td>";
				$this->salida.="  <td>PROFESIONAL</td>";
				$this->salida.="  <td>FECHA</td>";
				$this->salida.="</tr>";
				for($i=0;$i<sizeof($datos);$i++)
				{
                         $rcaja=$datos[$i][recibo_caja];
                         $empresa=$datos[$i][empresa_id];
                         $centro=$datos[$i][centro_utilidad];
                         $fech=$datos[$i][fecha_registro];
                         
                         if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_oscuro';}
                         $this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); align=\"center\">";
                         $this->salida.="<td>".$datos[$i][evolucion_id]."</td>";
                         $this->salida.="  <td>".$datos[$i][descripcion]."</td>";
                         $this->salida.="  <td>".$datos[$i][nombre]."</td>";
                         $this->salida.="  <td>".$this->FormateoFechaLocal($datos[$i][fecha])."</td>";
                         $this->salida.="</tr>";
				}
				$this->salida.="</table>";
			}
				
               $href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$datos_estacion));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";
               $this->salida .= themeCerrarTabla();
               return true;
		}

}//fin de la clase
?>
