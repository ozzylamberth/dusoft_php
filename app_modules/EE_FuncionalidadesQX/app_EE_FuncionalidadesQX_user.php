<?php

/**
 * $Id: app_EE_FuncionalidadesQX_user.php,v 1.9 2007/01/09 20:25:01 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_FuncionalidadesQX_user extends classModulo
{
     
     /**
     * Valida si el usuario esta logueado en La Estacion de Enfermeria y si tiene permiso
     * Para este componente ('01'= Admision - Asignacion Cama)
     *
     * @return boolean
     * @access private
     */
     function GetUserPermisos($componente=null)
     {
          $estacion_id = $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()];
          
          if($componente)
          {
               if(!empty($_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO'][UserGetUID()][$estacion_id]['COMPONENTES'][$componente]))
               {
                    return true;
               }
               else
               {
                    return null;
               }
          }
     
          if(!empty($_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO'][UserGetUID()][$estacion_id]))
          {
               return true;
          }
          else
          {
               return null;
          }
     }

     /**
     * Retorna los datos de la estacion de enfermeria actual.
     *
     * @return array
     * @access private
     */
     function GetdatosEstacion()
     {
          $estacion_id = $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()];
          return $_SESSION['EE_PanelEnfermeria']['DATOS_ESTACION'][$estacion_id];
     } 
     
     
     //Realizamos el traslado de Estacion al paciente.
     function DarSalida()
     {
          $ingreso = $_REQUEST['ingreso'];
          $cuenta = $_REQUEST['cuenta'];
          $conducta = $_REQUEST['conducta'];
          $estacion_origen = $_REQUEST['estacion_origen'];

          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          
          $query = "UPDATE estacion_enfermeria_qx_pacientes_ingresados
          		SET sw_estado='2', fecha_egreso=now()
                    WHERE numerodecuenta=".$cuenta."";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al actualizar el estado del paciente en pacientes_urgencias<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
               
          $query_Conducta = "UPDATE hc_ordenes_medicas SET sw_estado = '0'
                             WHERE ingreso = ".$conducta[ingreso]."
                             AND evolucion_id = ".$conducta[evolucion_id]."
                             AND hc_tipo_orden_medica_id = '11';";
          $result = $dbconn->Execute($query_Conducta);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          
          //Actualizo el Departamento Actual del Paciente.
          if(!empty($estacion_origen))
          {
               $SqlDpto = "SELECT departamento
                           FROM estaciones_enfermeria
                           WHERE estacion_id = '".$estacion_origen."';";
               $result = $dbconn->Execute($SqlDpto);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar Actualizar el Departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$updateDPTO;
                    $dbconn->RollbackTrans();
                    return false;
               }
               if(!empty($result->fields[0]))
               {
                    
                    $updateDPTO = "UPDATE ingresos SET departamento_actual = '".$result->fields[0]."'
                                   WHERE ingreso = ".$ingreso.";";
                    $result = $dbconn->Execute($updateDPTO);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al ejecutar la conexion";
                         $this->mensajeDeError = "Error al intentar Actualizar el Departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$updateDPTO;
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
          }
		//Fin Actualizo el Departamento Actual del Paciente.
          
          if(!empty($_REQUEST['obs']))
          {
               $evolucion = $this->BuscarEvolucion($ingreso);
               if(empty($evolucion)){$evolucion='NULL';}
               
               $queryInsert = "INSERT INTO hc_notas_enfermeria_descripcion
                                        (descripcion,
                                        evolucion_id,
                                        usuario_id,
                                        fecha_registro,
                                        ingreso)
                              VALUES ('".$_REQUEST['obs']."',
                                        ".$evolucion.",
                                        ".UserGetUID().",
                                        '".date("Y-m-d")."',
                                        ".$ingreso.")";
               
               $result = $dbconn->Execute($queryInsert);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
          
          $dbconn->CommitTrans();
          $mensaje = "EL PACIENTE FUE TRASLADADO A LA ESTACION DE HOSPITALIZACION";
          $titulo = "MENSAJE";
		$url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $link = "PANEL ENFERMERIA";
          $this->FrmMSG($url,$titulo,$mensaje,$link);
          return true;
     }

     //Realizamos la Salida del paciente.
     function DarSalidaInstitucion()
     {
          $ingreso = $_REQUEST['ingreso'];
          $cuenta = $_REQUEST['cuenta'];
          $conducta = $_REQUEST['conducta'];
          $dpto_egreso = $_REQUEST['dpto_egreso'];

          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          
          $query = "UPDATE estacion_enfermeria_qx_pacientes_ingresados
          		SET sw_estado='2', fecha_egreso=now()
          		WHERE numerodecuenta=".$cuenta."";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al actualizar el estado del paciente en pacientes_urgencias<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          
          $query_Conducta = "UPDATE hc_ordenes_medicas SET sw_estado = '0'
                             WHERE ingreso = ".$conducta[ingreso]."
                             AND evolucion_id = ".$conducta[evolucion_id]."
                             AND hc_tipo_orden_medica_id = '".$conducta[hc_tipo_orden_medica_id]."';";
          $result = $dbconn->Execute($query_Conducta);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          
          if(!empty($_REQUEST['obs']))
          {
               $evolucion = $this->BuscarEvolucion($ingreso);
		     if(empty($evolucion)){$evolucion='NULL';}

               $queryInsert = "INSERT INTO hc_notas_enfermeria_descripcion
                                        (descripcion,
                                        evolucion_id,
                                        usuario_id,
                                        fecha_registro,
                                        ingreso)
                              VALUES ('".$_REQUEST['obs']."',
                                        ".$evolucion.",
                                        ".UserGetUID().",
                                        '".date("Y-m-d")."',
                                        ".$ingreso.")";
               
               $result = $dbconn->Execute($queryInsert);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$queryInsert;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
          
          
          $SQLmovimiento = "SELECT cama FROM movimientos_habitacion
          			   WHERE ingreso = ".$ingreso."
                            AND numerodecuenta = ".$cuenta."
                            AND fecha_egreso IS NULL;";
          $result = $dbconn->Execute($SQLmovimiento);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al actualizar el estado del paciente en pacientes_urgencias<br><br>".$dbconn->ErrorMsg()."<br><br>".$SQLmovimiento;
               $dbconn->RollbackTrans();
               return false;
          }
          list($cama) = $result->FetchRow();
          if(!empty($cama))
          {
               $query = "UPDATE movimientos_habitacion
                         SET fecha_egreso = '".date("Y-m-d H:i:s")."'
                         WHERE fecha_egreso ISNULL AND
                         ingreso = ".$ingreso."";
               $result = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
              
               $query = "UPDATE camas
                         SET estado = '1'
                         WHERE cama = '".$cama."';";
               $result = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
		}
          
          $SQLurgencia = "SELECT count(*) FROM pacientes_urgencias
          			 WHERE ingreso = ".$ingreso."
                          AND sw_estado IN ('0','1');";
          $result = $dbconn->Execute($SQLurgencia);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al actualizar el estado del paciente en pacientes_urgencias<br><br>".$dbconn->ErrorMsg()."<br><br>".$SQLurgencia;
               $dbconn->RollbackTrans();
               return false;
          }
          
          if($result->fields[0] >= 1)
          {
          
               if($conducta[hc_tipo_orden_medica_id] == '06')
               { $estado = '5'; }
               elseif($conducta[hc_tipo_orden_medica_id] == '07')
               { $estado = '6'; }
               elseif($conducta[hc_tipo_orden_medica_id] == '99')
               { $estado = '4'; }

               $query = "UPDATE pacientes_urgencias SET sw_estado = '$estado'
                         WHERE ingreso=".$ingreso."";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al actualizar el estado del paciente en pacientes_urgencias<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
               
               $query = "DELETE from pendientes_x_hospitalizar
                         WHERE ingreso=".$ingreso.";";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al eliminar de pendientes_x_hospitalizar<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
           
          // Actualizo el estado de ingresos para un proximo ingreso.
          $queryIngreso = "UPDATE ingresos
                    	  SET estado = '2'
                    	  WHERE ingreso = ".$ingreso.";";
          $result = $dbconn->Execute($queryIngreso);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          
          $Observaciones = "Salida desde la Estacion de Cirugía, El paciente se encuentra a Paz y Salvo con la Institución.";
          $querySalida = "INSERT INTO ingresos_salidas   (ingreso,
          									   fecha_registro,
                                                          usuario_id,
                                                          observacion_salida,
                                                          departamento_egreso)
          								VALUES (".$ingreso.",
                                                  	   now(),
                                                          ".UserGetUID().",
                                                          '".$Observaciones."',
                                                          '".$dpto_egreso."');";
          $result = $dbconn->Execute($querySalida);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }          
                   

          $query_borrar = "DELETE FROM estaciones_enfermeria_ingresos_pendientes
          		 	  WHERE numerodecuenta = ".$cuenta.";";
          $result = $dbconn->Execute($query_borrar);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al borrar el paciente de la tabla estaciones_enfermeria_ingresos_pendientes.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }

  
          $dbconn->CommitTrans();
          $mensaje = "EL PACIENTE FUE DADO DE ALTA DESDE LA ESTACION DE CIRUGIA";
          $titulo = "MENSAJE";
		$url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $link = "PANEL ENFERMERIA";
          $this->FrmMSG($url,$titulo,$mensaje,$link);
          return true;
     }
     
     /*
	* Funcion que obtiene los datos si desde otros departamentos se aprobo la salida del paciente
	*/
	function BusquedaVistos_ok_salida($conducta)
     {
          $query = "SELECT A.*, B.* 
          		FROM hc_tiposvistosok_salida AS A LEFT JOIN hc_vistosok_salida_detalle AS B ON (a.visto_id = b.visto_id)
				WHERE B.ingreso = ".$conducta[ingreso]."
				AND B.evolucion_id = ".$conducta[evolucion_id]."
                    ORDER BY A.visto_id ASC;";
		GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar actualizar el estado del egreso del departamento.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          while($data = $result->FetchRow())
          {
          	$vistos[$data['visto_id']] = $data; 
          }
     	return $vistos;
     }
     
     /*
	* Inserta el visto ok por parte de la EE (PACIENTE A PAS Y SALVO) 
	*/
     function Insertar_Vistobueno()
     {
     	$conducta = $_REQUEST['conducta'];
          $query = "INSERT INTO hc_vistosok_salida_detalle (ingreso,
     											evolucion_id,
          										visto_id,
                                                            usuario,
                                                            observacion)
          								VALUES   (".$conducta[ingreso].",
                                                  		".$conducta[evolucion_id].",
                                                            '01',
                                                            ".UserGetUID().",
                                                            'Visto bueno desde EE');";
          list($dbconn) = GetDBconn();
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar actualizar el estado del egreso del departamento.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          $this->FrmAltaPacienteCirugia($_REQUEST['datosPaciente'], $_REQUEST['datos_estacion'], $conducta);
     	return true;
     }

     /**
     * Funcion que obtiene la informacion de insumos en bodega.
     */
     function GetInfoCuentasActivas($ingreso)
     {
          list($dbconnect) = GetDBconn();
     	$query = "SELECT COUNT(numerodecuenta)
                    FROM cuentas
                    WHERE ingreso = ".$ingreso."
                    AND estado = '1';";
          $result = $dbconnect->Execute($query);
          
          if($result->fields[0] > 0)
          {
          	return '1';
          }else
          {
          	return '0';
          }     
     }

     /**
     * Funcion que obtiene la evolucion de un paciente.
     */
     function BuscarEvolucion($ingreso)
     {
          list($dbconn) = GetDBconn();
          $query = "select b.evolucion_id from hc_evoluciones as b
                    where b.ingreso='$ingreso'
                    and b.estado='1'
                    and b.fecha_cierre=(select max(fecha_cierre) from hc_evoluciones	where ingreso='$ingreso')";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
	     }
          return $result->fields[0];
     }
     
     /**
     * Funcion que obtiene la informacion de las devoluciones pendientes.
     */
	function CerrarEvolucionesAbiertas()
     {
     	$evolucion = $_REQUEST['evolucion'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente  = $_REQUEST['datosPaciente'];
          $estado = $_REQUEST['estado'];
          $conducta = $_REQUEST['conducta'];     
          
          list($dbconnect) = GetDBconn();
     	$query = "UPDATE hc_evoluciones 
          		SET estado = '0'
                    WHERE evolucion_id = ".$evolucion.";";
          $dbconnect->Execute($query);         
          if ($dbconnect->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          $this->FrmAltaPacienteCirugia($datosPaciente, $datos_estacion, $conducta);
          return true;
     }

     /**
	*		GetEstacionesDpto =>
	*
	*		obtiene las EE (diferentes a la actual) del departamento.
	*
	*		@access Public
	*		@return bool-array-string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetEstacionesDpto($datos_estacion)
	{
		$query = "SELECT a.estacion_id, a.descripcion, a.departamento 
                    FROM estaciones_enfermeria a, 
					     departamentos b
                    WHERE a.sw_estacion_cirugia IS NULL
					AND   a.departamento = b.departamento
					AND   b.empresa_id = '".$datos_estacion['empresa_id']."'
					AND   b.centro_utilidad = '".$datos_estacion['centro_utilidad']."'
                    ORDER BY a.descripcion;";
		list($dbconn) = GetDBconn();
		
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar seleccionar las estaciones del departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF){
			return "ShowMensaje";
		}

		$i=0;
		while ($data = $result->FetchNextObject())
		{
			$estaciones[$i][0] = $data->ESTACION_ID;
			$estaciones[$i][1] = $data->DESCRIPCION;
               $estaciones[$i][2] = $data->DEPARTAMENTO;
			$i++;
		}
		return $estaciones;
	}//GetEstacionesDpto
     
     
     /**
     *		UpdateTrasladoEstacion
     *
     *		Inserta en la tabla de pxh la estacion origen en la que se encuentr a el paciente y la destino
     *
     *		@access Public
     *		@return bool
     */
     function UpdateTrasladoEstacion()
     {
          $datosPaciente = $_REQUEST['datosPaciente'];
          $ee_destino = $_REQUEST['estacionDestino'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $conducta = $_REQUEST['conducta'];
		$Prox_Dpto = $_REQUEST['Prox_Dpto'];
          
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
  
          		
          $query = "UPDATE estacion_enfermeria_qx_pacientes_ingresados
          		SET sw_estado='2', fecha_egreso=now()
          		WHERE numerodecuenta=".$datosPaciente[numerodecuenta]."";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al actualizar el estado del paciente en pacientes_urgencias<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          
          $query_Conducta = "UPDATE hc_ordenes_medicas SET sw_estado = '0'
                             WHERE ingreso = ".$conducta[ingreso]."
                             AND evolucion_id = ".$conducta[evolucion_id]."
                             AND hc_tipo_orden_medica_id = '".$conducta[hc_tipo_orden_medica_id]."';";
          $result = $dbconn->Execute($query_Conducta);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          
          $query_borrar = "DELETE FROM estaciones_enfermeria_ingresos_pendientes
          		 	  WHERE numerodecuenta = ".$datosPaciente[numerodecuenta].";";
          $result = $dbconn->Execute($query_borrar);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al borrar el paciente de la tabla estaciones_enfermeria_ingresos_pendientes.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          
          $SQLmovimiento = "SELECT cama FROM movimientos_habitacion
          			   WHERE ingreso = ".$datosPaciente[ingreso]."
                            AND numerodecuenta = ".$datosPaciente[numerodecuenta]."
                            AND fecha_egreso IS NULL;";
          $result = $dbconn->Execute($SQLmovimiento);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al actualizar el estado del paciente en pacientes_urgencias<br><br>".$dbconn->ErrorMsg()."<br><br>".$SQLmovimiento;
               $dbconn->RollbackTrans();
               return false;
          }
          list($cama) = $result->FetchRow();
          if(!empty($cama))
          {
               $query = "UPDATE movimientos_habitacion
                         SET fecha_egreso = '".date("Y-m-d H:i:s")."'
                         WHERE fecha_egreso ISNULL AND
                         ingreso = ".$datosPaciente[ingreso].";";
               $result = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
              
               $query = "UPDATE camas
                         SET estado = '1'
                         WHERE cama = '".$cama."';";
               $result = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
		}
          
          $SQLurgencia = "SELECT count(*) FROM pacientes_urgencias
          			 WHERE ingreso = ".$datosPaciente[ingreso]."
                          AND sw_estado IN ('0','1');";
          $result = $dbconn->Execute($SQLurgencia);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al actualizar el estado del paciente en pacientes_urgencias<br><br>".$dbconn->ErrorMsg()."<br><br>".$SQLurgencia;
               $dbconn->RollbackTrans();
               return false;
          }
          
          if($result->fields[0] >= 1)
          {
               if($conducta[hc_tipo_orden_medica_id] == '06')
               { $estado = '5'; }
               elseif($conducta[hc_tipo_orden_medica_id] == '07')
               { $estado = '6'; }
               elseif($conducta[hc_tipo_orden_medica_id] == '99')
               { $estado = '4'; }

               $query = "UPDATE pacientes_urgencias SET sw_estado = '$estado'
                         WHERE ingreso=".$datosPaciente[ingreso]."";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al actualizar el estado del paciente en pacientes_urgencias<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
               
               $query = "DELETE from pendientes_x_hospitalizar
                         WHERE ingreso=".$datosPaciente[ingreso].";";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al eliminar de pendientes_x_hospitalizar<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
          
          if(empty($datosPaciente[diagnostico_id]))
          { $diagnosticos = "NULL"; }else{$diagnosticos = "'".$datosPaciente[diagnostico_id]."'";}
          
          if(empty($datosPaciente[tipo_id_tercero]))
          { $tipo_id_tercero = "NULL"; }else{ $tipo_id_tercero = "'".$datosPaciente[tipo_tercero_id]."'";}
          
          if(empty($datosPaciente[tercero_id]))
          { $tercero = "NULL"; }else{ $tercero = "'".$datosPaciente[tercero_id]."'";}
          
          if(empty($datosPaciente[nombre_medico_externo]))
          { $nombre_medico_externo = "NULL"; }else{ $nombre_medico_externo = "'".$datosPaciente[nombre_medico_externo]."'";}
          
          if(empty($datosPaciente[observaciones]))
          { $observaciones = 'Trasladado desde Estación de Cirugia.'; }
          
          $query1 = "INSERT INTO estaciones_enfermeria_ingresos_pendientes (numerodecuenta,
                                                                            estacion_id,
                                                                            fecha_registro,
                                                                            usuario_registro,
                                                                            estacion_origen,
                                                                            tipo_cama_id,
                                                                            diagnostico_id,
                                                                            observaciones,
                                                                            sw_aislamiento,
                                                                            tipo_id_tercero,
                                                                            tercero_id,
                                                                            nombre_medico_externo)
          											  VALUES  (".$datosPaciente[numerodecuenta].",
                                                                            '".$ee_destino."',
                                                                            '".date('Y-m-d H:i:s')."',
                                                                            ".UserGetUID().",
                                                                            '".$datos_estacion[estacion_id]."',
                                                                            NULL,
                                                                            ".$diagnosticos.",
                                                                            '".$observaciones."',
                                                                            '0',
                                                                            ".$tipo_id_tercero.",
                                                                            ".$tercero.",
                                                                            ".$nombre_medico_externo.");";
          
          $result = $dbconn->Execute($query1);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          
          //Actualizo el Departamento Actual del Paciente.
          $updateDPTO = "UPDATE ingresos SET departamento_actual = '".$Prox_Dpto."'
          			WHERE ingreso = ".$datosPaciente['ingreso'].";";
          $result = $dbconn->Execute($updateDPTO);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al intentar Actualizar el Departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$updateDPTO;
               $dbconn->RollbackTrans();
               return false;
          }
		//Fin Actualizo el Departamento Actual del Paciente.

          
          $dbconn->CommitTrans();
          $mensaje = "EL PACIENTE FUE TRASLADADO A CUIDADOS INTENSIVOS PARA SU RECUPERACION";
          $titulo = "TRASLADO DE ESTACION";
          $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $link = "Panel Cirugia";
          $this->frmMSG($url, $titulo, $mensaje, $link);
          return true;
     }// RemitirAEstacion()
     
     
     //funcion que obtiene las evoluciones abiertas de cada paciente.
	function BuscarEvolucion_Pac($ingreso,$sw)
	{
          list($dbconn) = GetDBconn();
	      //sw es un switche q sirve para sacar toda la informacion del medico, si este esta null
          //solo hariamos el conteo las evoluciones abiertas
          if(empty($sw))
          {
               $query = "select COUNT(evolucion_id) from hc_evoluciones
                         where ingreso='$ingreso'
                         and estado='1'";
               $result=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               return $result->fields[0];
          }
			
          $query = "select usuario_id,evolucion_id from hc_evoluciones
                    where ingreso='$ingreso'
                    and estado='1'";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
							
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
		
          for($r=0;$r<sizeof($vector);$r++)
          {
               $query = "SELECT x.tipo_profesional,x.descripcion,b.nombre,c.fecha,c.evolucion_id
                         FROM profesionales_usuarios a, profesionales b,hc_evoluciones c,
                         tipos_profesionales x
                         WHERE a.tipo_tercero_id=b.tipo_id_tercero and
                         a.tercero_id=b.tercero_id and
                         a.usuario_id=".$vector[$r][usuario_id]."
                         AND c.evolucion_id=".$vector[$r][evolucion_id]."
                         AND x.tipo_profesional=b.tipo_profesional";
               $result=$dbconn->Execute($query);
               while (!$result->EOF)
               {
                    $vector2[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }

               if(!$vector2)
               {
                    $query = "SELECT '5' AS tipo_profesional, 'OTRO' AS descripcion,
                                   b.nombre,
                                   c.fecha, c.evolucion_id
                              FROM system_usuarios b, hc_evoluciones c
                              WHERE c.evolucion_id=".$vector[$r][evolucion_id]."
                              AND c.usuario_id = ".$vector[$r][usuario_id]."
                              AND c.usuario_id = b.usuario_id;";
                    $result=$dbconn->Execute($query);
                    while (!$result->EOF)
                    {
                         $vector2[]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
                    }
               }
          }		
          return $vector2;
	}

     
     /*
	* Cambiamos el formato timestamp a un formato de fecha legible para el usuario
	*/
	function FormateoFechaLocal($fecha)
	{
          if(!empty($fecha))
          {
               $f=explode(".",$fecha);
               $fecha_arreglo=explode(" ",$f[0]);
               $fecha_real=explode("-",$fecha_arreglo[0]);
               return strftime("%A, %d de %B de %Y",strtotime($fecha_arreglo[0]));
          }
          else
          {
               return "-----";
          }
		return true;
	}
   
}//end of class

?>
