<?php

/**
 * $Id: app_EE_AsignacionCama_user.php,v 1.27 2007/06/04 21:42:02 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_AsignacionCama_user extends classModulo
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
     
     /**
     * Retorna los datos del paciente a Ingresar.
     *
     * @return array
     * @access private
     */
     function GetDatosPacientePorIngresar()
     {
     	if(!empty($_REQUEST['numero_registro']))
          	$_SESSION['numero_registro'] = $_REQUEST['numero_registro']; 
          
          if(empty($_SESSION['numero_registro'])) return null;
          
          list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;
     
          $query = "SELECT    a.*,
                              b.descripcion as descripcion_estacion_origen,
                              c.descripcion as descripcion_tipo_cama,
                              d.diagnostico_nombre,
                              CASE WHEN e.nombre_tercero IS NOT NULL THEN e.nombre_tercero ELSE a.nombre_medico_externo END as profesional
                         FROM
                         (
                         SELECT
                              a.numero_registro,
                              a.estacion_origen,
                              a.tipo_cama_id,
                              a.diagnostico_id,
                              a.observaciones,
                              a.sw_aislamiento,
                              a.tipo_id_tercero,
                              a.tercero_id,
                              a.nombre_medico_externo,
                              a.fecha_registro,
                              a.usuario_registro,
                              a.numerodecuenta,
                              (SELECT verificacionpaciente_ecirugia(a.numerodecuenta)) as paciente_cirugia,
                              c.ingreso,
                              c.fecha_ingreso,
                              d.plan_id,
                              d.plan_descripcion,
                              e.paciente_id,
                              e.tipo_id_paciente,
                              e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo
     
                         FROM
                              estaciones_enfermeria_ingresos_pendientes a,
                              cuentas b,
                              ingresos c,
                              planes d,
                              pacientes e
     
                         WHERE
                              a.numero_registro = '".$_SESSION['numero_registro']."'
                              AND a.sw_estado = '1'
                              AND b.numerodecuenta = a.numerodecuenta
                              AND c.ingreso = b.ingreso
                              AND d.plan_id = b.Plan_id
                              AND e.paciente_id = c.paciente_id
                              AND e.tipo_id_paciente = c.tipo_id_paciente
                         ) AS a
                         LEFT JOIN estaciones_enfermeria b ON (a.estacion_origen = b.estacion_id)
                         LEFT JOIN tipos_camas c ON (c.tipo_cama_id = a.tipo_cama_id)
                         LEFT JOIN diagnosticos d ON (d.diagnostico_id = a.diagnostico_id)
                         LEFT JOIN terceros e ON (e.tipo_id_tercero = a.tipo_id_tercero AND e.tercero_id = a.tercero_id)
                         ORDER BY a.fecha_registro";
     
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "EE_AsignacionCama - GetDatosPacientePorIngresar - 01";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
     
          if($resultado->EOF)
          {
               return null;
          }
     
          $fila = $resultado->FetchRow();
          $resultado->Close();
     
          return $fila;
     }
     
     /*
     **
     *		GetViaIngresoPaciente
     *
     *		Con el ingreso del paciente obtengo la via de ingreso
     *		@param integer => numero de ingreso
     */
     function GetViaIngresoPaciente($ingreso)
     {
          $query = "SELECT I.via_ingreso_id, VI.via_ingreso_nombre
                                   FROM ingresos I,
                                             vias_ingreso VI
                                   WHERE I.ingreso = $ingreso AND
                                                  VI.via_ingreso_id =  I.via_ingreso_id;";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar consultar la vía de ingreso del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if(!$result->EOF)
               {
                    $viaIngreso = $result->FetchRow();
                    return $viaIngreso;
               }
               else{
                    return "ShowMensaje";
               }
          }
     }//GetViaIngresoPaciente
       

     /**
     *	CallListadoCamas
     *
     *	Subproceso" Asignar cama" del proceso "ingreso de pacientes a la estación de enfermería"
     *	CallListadoCamas recibe el paciente a ingresar al dpto de la tabla pendientes_x_remitir y 
     *	llama a la funcion que me muestra el listado de las camas disponibles
     *
     *	@Author Tizziano Perea
     *	@access Public
     *	@return bool
     */
     function CallListadoCamas($datosPaciente,$swCambioCama,$datos_estacion,$conducta)
     {
          unset($_SESSION['ESTACION_ENF']['AUDITORIA']['USUARIO_AUTO']);
          if(empty($datosPaciente))
          {
               $datosPaciente = $_REQUEST['datosPaciente']; //vector con los datos del paciente.
               $datos_estacion = $_REQUEST['datos_estacion']; //vector con los datos de la estacion.
          }

          //ojo esto es por si voy a hacer el subproceso 3 "cambio de cama" en lugar de "asignar cama" del subproceso 1
          if($_REQUEST['SwCambioCama']){
               $swCambioCama = $_REQUEST['SwCambioCama'];
          }
          
          if($_REQUEST['conducta'])
          	$conducta = $_REQUEST['conducta'];
          
          if(!$this->FrmListadoCamas($datosPaciente,$swCambioCama,$datos_estacion,$conducta))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmListadoCamas\"";
               return false;
          }
          return true;
     }//fin CallListadoCamas
     
     /**
     *		CallIngresarPaciente
     *
     *		subproceso 1->Asignar cama del proceso "ingreso de pacientes a la estación de enfermería"
     *		1.1.3.U => CallIngresarPaciente llama a la ultima interfaz en la que se piden los ultimos datos
     *		viene del link Asignar cama de la vista 2
     *
     *		@access Public
     *		@return bool
     */
     function CallIngresarPaciente()
     {
          $datosPaciente = $_REQUEST['datosPaciente']; //vector con los datos del paciente
          $datosCamaPaciente = $_REQUEST['datosCamaPaciente'];
          $conducta = $_REQUEST['conducta'];

          $datosPaciente['pieza']=$_REQUEST['pieza'];
          $datosPaciente['cama']=$datosCamaPaciente[0];
          $datosPaciente['desc_cargo']=$datosCamaPaciente[1];
          $datosPaciente['cargo']=$datosCamaPaciente[2];
          $datosPaciente['tipo_cama_id']=$datosCamaPaciente[3];
          $datosPaciente['tipo_clase_cama_id']=$datosCamaPaciente[4];//tipo de clase de cama.
          if(!$this->IngresarPaciente($datosPaciente,$_REQUEST['datos_estacion'],$conducta))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"IngresarPaciente\"";
               return false;
          }
          return true;
     }//fin CallIngresarPaciente

     /**
     *		InsertarPaciente
     *
     *		subproceso 1->Asignar cama del proceso "ingreso de pacientes a la estación de enfermería"
     *		1.1.4.U => InsertarPaciente llama a la ultima interfaz en la que se piden los ultimos datos
     *		viene del link ingresar de la vista 3
     *
     *		@access Public
     *		@return bool
     *		@param array => matriz con los datos del paciente a insertar
     *		@param array => datos de la estacion y clinica
     */
     function InsertarPaciente($datosPaciente,$datos_estacion)
     {
          if(!$datos_estacion){
               $datos_estacion = $_REQUEST['datos_estacion'];
          }
          
          if(!$conducta)
			$conducta = $_REQUEST['conducta'];

		$observaciones = $_REQUEST['observaciones'];
          if(empty($observaciones))
          { $observaciones = "NULL";}else{ $observaciones = "'".$observaciones."'";}

          if(!is_array($datosPaciente))
          {
               $datosPaciente = $_REQUEST['datosPaciente']; //vector con los datos del paciente y la cama asignada
          }

          if(empty($datosPaciente[tipo_cama_id]))
          { $tipo_cama_id = "NULL"; }else{ $tipo_cama_id = "".$datosPaciente[tipo_cama_id]."";}

          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          $query="INSERT INTO movimientos_habitacion (ingreso_dpto_id,
                                                      numerodecuenta,
                                                      fecha_ingreso,
                                                      fecha_egreso,
                                                      cama,
                                                      ingreso,
                                                      precio,
                                                      cargo,
                                                      sw_excedente,
                                                      tipo_cama_id,
                                                      transaccion,
                                                      departamento,
                                                      estacion_id,
                                                      autorizacion_int,
                                                      autorizacion_ext,
                                                      observacion)
          							VALUES  (NULL,
                                                      ".$datosPaciente[numerodecuenta].",
                                                      now(),
                                                      NULL,
                                                      '".$datosPaciente[cama]."',
                                                      ".$datosPaciente[ingreso].",
                                                      NULL,
                                                      '".$datosPaciente[cargo]."',
                                                      NULL,
                                                      ".$tipo_cama_id.",
                                                      NULL,
                                                      '".$datos_estacion[departamento]."',
                                                      '".$datos_estacion[estacion_id]."',
                                                      NULL,
                                                      NULL,
                                                      ".$observaciones.");";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar ingresar el paciente al departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          
          if(empty($datosPaciente[diagnostico_id]))
          { $diagnosticos = "NULL"; }else{$diagnosticos = "'".$datosPaciente[diagnostico_id]."'";}
          
          if(empty($datosPaciente[tipo_id_tercero]))
          { $tipo_id_tercero = "NULL"; }else{ $tipo_id_tercero = "'".$datosPaciente[tipo_id_tercero]."'";}
          
          if(empty($datosPaciente[tercero_id]))
          { $tercero = "NULL"; }else{ $tercero = "'".$datosPaciente[tercero_id]."'";}
          
          if(empty($datosPaciente[nombre_medico_externo]))
          { $nombre_medico_externo = "NULL"; }else{ $nombre_medico_externo = "'".$datosPaciente[nombre_medico_externo]."'";}
          
          if(empty($datosPaciente[observaciones]))
          { $observaciones = "NULL"; }else{ $observaciones = "'".$datosPaciente[observaciones]."'";}
          
          if(empty($datosPaciente[estacion_origen]))
          { $estacion_origen = "NULL"; }else{ $estacion_origen = "'".$datosPaciente[estacion_origen]."'";}
          
          $query_Num = "SELECT MAX(numero_registro) FROM estaciones_enfermeria_ingresos_realizados;";
          $result = $dbconn->Execute($query_Num);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar ingresar el paciente al departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query_Num;
               $dbconn->RollbackTrans();
               return false;
          }
          list($numeroReg) = $result->FetchRow();
          $numeroReg = 1 + $numeroReg;

          if(empty($datosPaciente[fecha_registro]))
          { $fechaReg = "now()"; }else{ $fechaReg = $datosPaciente[fecha_registro]; }

          if(empty($datosPaciente[usuario_registro]))
          { $usuario = "".UserGetUID().""; }else{ $usuario = "".$datosPaciente[usuario_registro].""; }
          
          if(empty($datosPaciente[sw_aislamiento]))
          { $aislar = "'0'"; }else{ $aislar = "'".$datosPaciente[sw_aislamiento]."'"; }
          
          $query1="INSERT INTO estaciones_enfermeria_ingresos_realizados (numero_registro,
          												    numerodecuenta,
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
                                                                          nombre_medico_externo,
                                                                          fecha_registro_ingreso,
                                                                          usuario_registro_ingreso)
          											VALUES  (".$numeroReg.",
                                                                 	    ".$datosPaciente[numerodecuenta].",
                                                                          '".$datos_estacion[estacion_id]."',
                                                                          '$fechaReg',
                                                                          ".$usuario.",
                                                                          ".$estacion_origen.",
                                                                          ".$tipo_cama_id.",
                                                                          ".$diagnosticos.",
                                                                          ".$observaciones.",
                                                                          $aislar,
                                                                          ".$tipo_id_tercero.",
                                                                          ".$tercero.",
                                                                          ".$nombre_medico_externo.",
                                                                          now(),
                                                                          ".UserGetUID().");";
          $result = $dbconn->Execute($query1);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar ingresar el paciente al departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query1;
               $dbconn->RollbackTrans();
               return false;
          }
          
          if(!empty($datosPaciente[numero_registro]))
          {
               $query2="DELETE FROM estaciones_enfermeria_ingresos_pendientes
                    WHERE numero_registro = ".$datosPaciente[numero_registro].";";
               
               $result = $dbconn->Execute($query2);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar ingresar el paciente al departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query2;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
          
          //4. ocupar la nueva cama asignada
          $query = "UPDATE camas
                    SET estado='0'
                    WHERE cama = '".$datosPaciente[cama]."'";

          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al intentar cambiar el estado de la nueva cama<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          
          if($conducta[hc_tipo_orden_medica_id] == '01' OR $conducta[hc_tipo_orden_medica_id] == '02' OR $conducta[hc_tipo_orden_medica_id] == '04')
          {
          	$query_Conducta = "UPDATE hc_ordenes_medicas SET sw_estado = '0'
               			    WHERE ingreso = ".$conducta[ingreso]."
                                  AND evolucion_id = ".$conducta[evolucion_id].";";
               $result = $dbconn->Execute($query_Conducta);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          if($_SESSION['Internacion_Directa'] == true)
          {
           	$query_paciente = "UPDATE pacientes_urgencias SET sw_estado = '5'
               			    WHERE ingreso = ".$datosPaciente[ingreso]."
                                  AND estacion_id = '".$datos_estacion[estacion_id]."';";
               $result = $dbconn->Execute($query_paciente);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
               $_SESSION['Internacion_Directa'] = false;
          }
          
          $dbconn->CommitTrans();
          $mensaje = "EL PACIENTE SE ENCUENTRA EN LA CAMA ".$datosPaciente[cama]." DE LA ESTACION ".$datos_estacion[estacion_descripcion]."";
          $titulo = "ASIGNACION DE CAMAS";
          $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $link = "Panel Enfermeria";
          $this->frmMSG($url, $titulo, $mensaje, $link);
          return true;
     }
     
     /**
     *		CallListadoPacientesEstacion
     *
     *		subproceso 3->"CambioCama" del proceso "ingreso de pacientes a la estación de enfermería"
     *		UpdateCamaPaciente
     *
     *		@Author Tizziano Perea
     *		@access Public
     *		@return bool
     */
     function UpdateCamaPaciente()
     {
          $datosPaciente = $_REQUEST['datosPaciente'];
          $datosCamaPaciente = $_REQUEST['datosCamaPaciente'];
		
          $cama_antigua = $datosPaciente['cama'];
     
          $datosPaciente['pieza']=$_REQUEST['pieza'];
          $datosPaciente['cama']=$datosCamaPaciente[0];
          $datosPaciente['desc_cargo']=$datosCamaPaciente[1];
          $datosPaciente['cargo']=$datosCamaPaciente[2];
          $datosPaciente['tipo_cama_id']=$datosCamaPaciente[3];
          $datosPaciente['tipo_clase_cama_id']=$datosCamaPaciente[4];//tipo de clase de cama.

          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          
          //como el cambio de cama es en la misma estacion traigo estos datos para el nuevo movimiento
          $query = "SELECT departamento,estacion_id from movimientos_habitacion
                    WHERE fecha_egreso IS NULL AND numerodecuenta = ".$datosPaciente[numerodecuenta]."
                    AND ingreso=".$datosPaciente[ingreso].";";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al intentar hacer el cierre de la habitacion actual<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          $var=$result->GetRowAssoc($ToUpper = false);
          $result->Close();

          //1.hago cierre de habitacion actual
          $query = "UPDATE movimientos_habitacion
                    SET 	fecha_egreso = '".date("Y-m-d H:i:s")."'
                    WHERE fecha_egreso IS NULL 
                    AND numerodecuenta = ".$datosPaciente[numerodecuenta]."
                    AND ingreso=".$datosPaciente[ingreso].";";

          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al intentar hacer el cierre de la habitacion actual<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          else
          {
			//----cambio dar se agregaron dos campos nuevos estacion_id,departamento
               $query = "INSERT INTO movimientos_habitacion ( numerodecuenta,
                                                              fecha_ingreso,
                                                              fecha_egreso,
                                                              cama,
                                                              ingreso,
                                                              precio,
                                                              cargo,
                                                              sw_excedente,
                                                              tipo_cama_id,
                                                              departamento,
                                                              estacion_id,
                                                              autorizacion_ext,
                                                              autorizacion_int,
                                                              observacion)
                                                       VALUES(".$datosPaciente[numerodecuenta].",
                                                              now(),
                                                              NULL,
                                                              '".$datosPaciente[cama]."',
                                                              '".$datosPaciente[ingreso]."',
                                                              0,
                                                              '".$datosPaciente[cargo]."',
                                                              '0',
                                                              ".$datosPaciente[tipo_cama_id].",
                                                              '".$var[departamento]."',
                                                              '".$var[estacion_id]."',
                                                              NULL,
                                                              NULL,
                                                              NULL);";
               $result = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar asignar la nueva habitacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
               else
               {
                    //3. desocupar la cama que tiene actualmente
                    $query = "UPDATE camas
                              SET estado='1'
                              WHERE cama = '".$cama_antigua."'";

                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al ejecutar la conexion";
                         $this->mensajeDeError = "Error al intentar desocupar la habitacion anterior<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
                    else
                    {
                         //4. ocupar la nueva cama asignada
                         $query = "UPDATE camas
                                   SET estado='0'
                                   WHERE cama = '".$datosPaciente[cama]."'";

                         $result = $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al ejecutar la conexion";
                              $this->mensajeDeError = "Error al intentar cambiar el estado de la nueva cama<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         else{
                              $dbconn->CommitTrans();
                         }
                    }//ejecutó el update para desocupar la cama
               }//ejecutó el insert en mov_habitacion
          }//ejecutó el update cierre de habitacion
          $mensaje = "EL PACIENTE SE ENCUENTRA EN LA CAMA ".$datosPaciente[cama]." DE LA ESTACION ".$datos_estacion[estacion_descripcion]."";
          $titulo = "CAMBIO DE CAMA";
          $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $link = "Panel Enfermeria";
          $this->frmMSG($url, $titulo, $mensaje, $link);
          return true;
     }
     
     
     /*
     * Funcion que trae los tipos de camas de tipos_camas_excepciones_plan
     */
	function Traer_Tipos_Cama_excepciones($estacion,$plan_id)
	{
     	list($dbconn) = GetDBconn();
     	$query = "SELECT a.tipo_cama_id,a.descripcion,precio_lista,cargo
                    	  --,a.tipo_clase_cama_id
                    FROM tipos_camas_excepcion_plan a,
                    	estaciones_tipos_camas_permitidos b
                    WHERE a.plan_id='$plan_id'
                    AND b.estacion_id='$estacion'
                    AND a.tipo_cama_id=b.tipo_cama_id";
      
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al traer los tipos de camas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;
      		return false;
		}

          while (!$result->EOF)
          {
               $var[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }
		return $var;
	}


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
          
		$Medicamentos = $this->GetInformacionMedicamentos_BodegaPaciente($datosPaciente[ingreso], 'M', 1);
          
          if($Medicamentos == '1')
          {
          	$sql = "SELECT Count(B.bodega)
                       FROM bodegas AS A,
                            bodegas_estaciones AS B
                            LEFT JOIN bodegas_estaciones AS C ON (C.bodega = B.bodega)
                       WHERE B.estacion_id = '".$datos_estacion[estacion_id]."'
                       AND   C.estacion_id = '".$ee_destino."'
                       AND   A.sw_consumo_directo = '0'
                       AND   B.bodega = A.bodega;";
               $result = $dbconn->Execute($sql);
               if($result->fields[0] == 0)
               {
                    $mensaje = "EL PACIENTE TIENE EXISTENCIAS EN SU BODEGA LAS CUALES NO PUEDEN SER TRASLADADAS<BR> A OTRA ESTACION";
                    $titulo = "FALLO EN TRASLADO DEL PACIENTE";
                    $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
                    $link = "Panel Enfermeria";
                    $this->frmMSG($url, $titulo, $mensaje, $link);
                    return true;           
               }
          }

          //Internacion Directa. 
          if($ee_destino == $datos_estacion['estacion_id'] AND $datos_estacion['sw_consulta_urgencia']!='1')
          {
			$restriccion = "SELECT count(movimiento_id)
               			 FROM movimientos_habitacion
                               WHERE numerodecuenta = ".$datosPaciente[numerodecuenta]."
                     		 AND ingreso = ".$datosPaciente[ingreso]."
                     		 AND fecha_egreso IS NULL;";
               $result = $dbconn->Execute($restriccion);
               if($result->fields[0] >= 1)
               {
                    $mensaje = "EL PACIENTE YA ESTA INTERNADO EN LA ESTACION Y TIENE UNA CAMA ASIGNADA";
                    $titulo = "FALLO EN ASIGNACION DE CAMA";
                    $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
                    $link = "Panel Enfermeria";
                    $this->frmMSG($url, $titulo, $mensaje, $link);
                    return true;           
               }
               else
               {
                    $_SESSION['Internacion_Directa'] = true;
                    $this->CallListadoCamas($datosPaciente,'',$datos_estacion,$conducta);
                    return true;
               }
          }

          //Proceso de Cierre de Estacion para un paciente internado.
          $query0 = "UPDATE movimientos_habitacion SET fecha_egreso = '".date('Y-m-d H:i:s')."'
          		 WHERE numerodecuenta = ".$datosPaciente[numerodecuenta]."
                     AND ingreso = ".$datosPaciente[ingreso]."
                     AND fecha_egreso IS NULL;";
          $result = $dbconn->Execute($query0);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al intentar actualizar la fecha de egreso del departamento.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
         
          //Insercion en Pendiente por asignar cama en la Estacion (estaciones_enfermeria_ingresos_pendientes).
          if(empty($datosPaciente[diagnostico_id]))
          { $diagnosticos = "NULL"; }else{$diagnosticos = "'".$datosPaciente[diagnostico_id]."'";}
          
          if(empty($datosPaciente[tipo_id_tercero]))
          { $tipo_id_tercero = "NULL"; }else{ $tipo_id_tercero = "'".$datosPaciente[tipo_tercero_id]."'";}
          
          if(empty($datosPaciente[tercero_id]))
          { $tercero = "NULL"; }else{ $tercero = "'".$datosPaciente[tercero_id]."'";}
          
          if(empty($datosPaciente[nombre_medico_externo]))
          { $nombre_medico_externo = "NULL"; }else{ $nombre_medico_externo = "'".$datosPaciente[nombre_medico_externo]."'";}
          
          if(empty($datosPaciente[observaciones]))
          { $observaciones = 'Trasladado de Estación'; }
          
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
          
          // Actualizacion de traslado de medicamentos.
          if($Medicamentos == '1')
          {
               $query = "UPDATE estaciones_enfermeria_ingresos_pendientes
                         SET sw_traslado_medicamentos = '1'
                         WHERE numerodecuenta = ".$datosPaciente[numerodecuenta].";";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
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
          
          //Actualizacion de Conducta Medica y Cama.
          if($conducta[hc_tipo_orden_medica_id] == '01' OR  $conducta[hc_tipo_orden_medica_id] == '02' OR $conducta[hc_tipo_orden_medica_id] == '04')
          {
          	$query_Conducta = "UPDATE hc_ordenes_medicas SET sw_estado = '0'
               			    WHERE ingreso = ".$conducta[ingreso]."
                                  AND evolucion_id = ".$conducta[evolucion_id].";";
               $result = $dbconn->Execute($query_Conducta);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
          
          if($_REQUEST['estado'] == 'ConsultaURG')
          {
           	$query_paciente = "UPDATE pacientes_urgencias SET sw_estado = '5'
               			    WHERE ingreso = ".$datosPaciente[ingreso]."
                                  AND estacion_id = '".$datos_estacion[estacion_id]."';";
               $result = $dbconn->Execute($query_paciente);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }else
          {
               $query = "UPDATE camas
                         SET estado = '1'
                         WHERE cama = '".$datosPaciente[cama]."';";
               $result = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al actualizar datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
		
          $dbconn->CommitTrans();
          
          $mensaje = "EL PACIENTE FUE TRASLADADO A OTRA ESTACION";
          $titulo = "TRASLADO DE ESTACION";
          $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $link = "Panel Enfermeria";
          $this->frmMSG($url, $titulo, $mensaje, $link);
          return true;
     }// RemitirAEstacion()
     

     /**
     *	UpdateCambioEstacion
     *
     *	subproceso 2->CambioEEAntesIngreso del proceso "ingreso de pacientes a la estación de enfermería"
     *	1.2.2.U => UpdateCambioEstacion actualiza la db con los datos de la nueva estacion
     *
     *	@access Public
     *	@return bool
     */
     function UpdateCambioEstacion()
     {
          $datosPaciente = $_REQUEST['datosPaciente']; //vector con los datos del paciente sizeof=10
          $ee_destino = $_REQUEST['estacionDestino'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $conducta = $_REQUEST['conducta'];
          $Prox_Dpto = $_REQUEST['Prox_Dpto'];

          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
                    
          if($conducta[hc_tipo_orden_medica_id] == '01' OR  $conducta[hc_tipo_orden_medica_id] == '02' OR $conducta[hc_tipo_orden_medica_id] == '04')
          {
          	$query_Conducta = "UPDATE hc_ordenes_medicas SET sw_estado = '0'
               			    WHERE ingreso = ".$conducta[ingreso]."
                                  AND evolucion_id = ".$conducta[evolucion_id].";";
               $result = $dbconn->Execute($query_Conducta);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
          
          if($_REQUEST['estado'] == 'ConsultaURG')
          {
           	$query_paciente = "UPDATE pacientes_urgencias SET sw_estado = '5'
               			    WHERE ingreso = ".$datosPaciente[ingreso]."
                                  AND estacion_id = '".$datos_estacion[estacion_id]."';";
               $result = $dbconn->Execute($query_paciente);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
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

          $query = "UPDATE estaciones_enfermeria_ingresos_pendientes
                    SET estacion_id = '".$ee_destino."',
                        fecha_registro = '".date('Y-m-d H:i:s')."',
                        estacion_origen = '".$datos_estacion[estacion_id]."',
                        usuario_registro = ".UserGetUID().",
                        observaciones = 'Remitido de Estación'
                   WHERE numerodecuenta = ".$datosPaciente[numerodecuenta].";";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al tratar de asignar la estacion seleccinada al paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          else
          {     
			$dbconn->CommitTrans();
               
               $mensaje = "EL PACIENTE FUE TRASLADADO A OTRA ESTACION";
               $titulo = "TRASLADO DE ESTACION";
               $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $link = "Panel Enfermeria";
               $impresion = "";
               $this->frmMSG($url, $titulo, $mensaje, $link);
               return true;
          }
     }//UpdateCambioEstacion()
     

     function ValidarProgramacion_Cirugia($datosPaciente)
     {
          list($dbconn) = GetDBconn();
          $query_programacion = "SELECT A.programacion_id
						   
                                 FROM qx_programaciones AS A
                                 LEFT JOIN estacion_enfermeria_qx_pendientes_ingresar AS C ON (A.programacion_id=C.programacion_id),
                                 	   qx_quirofanos_programacion AS B 
                                 
                                 WHERE A.paciente_id = '".$datosPaciente[paciente_id]."'
                                 AND A.tipo_id_paciente = '".$datosPaciente[tipo_id_paciente]."'
                                 AND A.programacion_id = B.programacion_id 
                                 AND B.qx_tipo_reserva_quirofano_id = '3' 
                                 AND A.estado = '1' 
                                 AND C.programacion_id IS NULL;";          
          $result = $dbconn->Execute($query_programacion);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          list($programacion) = $result->FetchRow();
          return $programacion;
     }
     
     function ValidarSolicitudes_Cirugia($datosPaciente)
     {
          list($dbconn) = GetDBconn();
            $query_programacion = "SELECT *   
            
            FROM hc_os_solicitudes_datos_acto_qx l, hc_os_solicitudes_acto_qx m,
            os_ordenes_servicios as a, pacientes as b, planes c, 
            os_internas as f, cups g, servicios h,os_maestro i
            LEFT JOIN hc_os_solicitudes x on(i.hc_os_solicitud_id=x.hc_os_solicitud_id AND i.cargo_cups=x.cargo)                 
            LEFT JOIN qx_programaciones_ordenes xx ON(xx.numero_orden_id=i.numero_orden_id)         
            
            WHERE l.acto_qx_id=m.acto_qx_id AND m.hc_os_solicitud_id=i.hc_os_solicitud_id 
            AND a.orden_servicio_id=i.orden_servicio_id AND i.numero_orden_id=f.numero_orden_id 
            AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id 
            AND a.tipo_id_paciente='".$datosPaciente[tipo_id_paciente]."' AND a.paciente_id='".$datosPaciente[paciente_id]."' 
            AND a.servicio=h.servicio AND g.cargo=f.cargo AND c.plan_id=a.plan_id            
            AND i.sw_estado=1 AND DATE(i.fecha_activacion) <= NOW() 
            AND xx.programacion_id IS NULL 
            ORDER BY c.plan_id,i.fecha_vencimiento,a.orden_servicio_id;";          
           
            $result = $dbconn->Execute($query_programacion);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al ejecutar la conexion";
                $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                $dbconn->RollbackTrans();
                return false;
            }
            $datos = $result->RecordCount();
            return $datos;
     }
     
     /**
     *	IngresarPaciente_EstacionCirugia
     *
     *	Funcion que hace posible el traslado de un paciente a una Estacion
     *	de cirugia, la cual es un dpto. totalmente diferente.
     *
     *	@access Public
     *	@return bool
     */
     function IngresarPaciente_EstacionCirugia()
     {
          $datosPaciente = $_REQUEST['datosPaciente'];
          $EstacionCirugia = $_REQUEST['DptoCirugia'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $programacion = $_REQUEST['programacion'];

          if(!$datosPaciente[numerodecuenta])
          {
               $mensaje = "EL PACIENTE NO TIENE UN NUMERO DE CUENTA ACTIVO";
               $titulo = "MENSAJE DEL SISTEMA";
               $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $link = "Panel Enfermeria";
               $this->frmMSG($url, $titulo, $mensaje, $link);
               return true;
          }

          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();

          if(empty($datosPaciente[observaciones]))
          { $observaciones = "'Trasladado al Departamento de Cirugia desde la Estacion ".$datos_estacion[estacion_descripcion]."'"; }
          else
          { $observaciones = "'".$datosPaciente[observaciones]."'"; }
          
          if(empty($_REQUEST['programacion'])){
            $programacionQX='NULL';
          }else{
            $programacionQX="'".$_REQUEST['programacion']."'";            
          }
                   
          $query1 = "INSERT INTO estacion_enfermeria_qx_pendientes_ingresar(numerodecuenta,
                                                                            departamento,
                                                                            sw_estado,
                                                                            estacion_origen,
                                                                            observaciones,
                                                                            fecha_registro,
                                                                            usuario_id,
                                                                            programacion_id,
                                                                            fecha_ingreso_estacion)
          											  VALUES  (".$datosPaciente[numerodecuenta].",
                                                                            '".$EstacionCirugia."',
                                                                            '1',
                                                                            '".$datos_estacion[estacion_id]."',
                                                                            ".$observaciones.",
                                                                            now(),
                                                                            ".UserGetUID().",
                                                                            $programacionQX,
                                                                            now());";
          
          $result = $dbconn->Execute($query1);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al intentar trasladar al paciente de estación<br><br>".$dbconn->ErrorMsg()."<br><br>".$query1;
               $dbconn->RollbackTrans();
               return false;
          }
          
          //Actualizo el Departamento Actual del Paciente.
          $updateDPTO = "UPDATE ingresos SET departamento_actual = '".$EstacionCirugia."'
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
          
          $mensaje = "EL PACIENTE FUE TRASLADADO A CIRUGIA";
          $titulo = "TRASLADO DPTO DE CIRUGIA";
          $url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $link = "Panel Enfermeria";
          $this->frmMSG($url, $titulo, $mensaje, $link);
          return true;
     
     } //fin IngresarPaciente_EstacionCirugia()


          
     //funcion q llama a la asignacion de cama virtual.
     function CallCrear_Asignar_Cama_Virtual($datosPaciente,$datos_estacion,$swCambioCama,$conducta)
     {
	     if(empty($datosPaciente))
          {
               $datosPaciente = $_REQUEST['datosPaciente'];
               $datos_estacion = $_REQUEST['datos_estacion'];
               $swCambioCama = $_REQUEST['swCambioCama'];
          }
          
          if($_REQUEST['conducta'])
          	$conducta = $_REQUEST['conducta'];

          $this->Crear_Asignar_Cama_Virtual($datosPaciente,$datos_estacion,$swCambioCama,$conducta);
          return true;
     }
     
     
     /* Generacion de la cama virtual para la insercion del paciente
     * en la estacion de enfermeria.
     */
	function GenerarCamaVirtual()
	{
		$datosPaciente = $_REQUEST['datosPaciente'];
		$opcion = $_REQUEST['opcion'];
		$tipo_serv = $_REQUEST['tipoc'];
		$datos_estacion = $_REQUEST['datos_estacion'];
		$descripcion = $_REQUEST['desc'];
		$ubicacion = $_REQUEST['ubic'];
		$ubicacion_cama= $_REQUEST['ubic_cama'];

          if($_REQUEST['conducta'])
          	$conducta = $_REQUEST['conducta'];
		
          //-------
		$arr_tipocama=$_REQUEST['tipo_cama'];
		$data=explode("*",$arr_tipocama);
		$tipocama=$data[0];
		$cargo=$data[1];
		$tipo_clase_cama_id=$data[2];
		//-------

		if(empty($tipo_serv))
		{
			$this->frmError["MensajeError"] = "SELECCIONE EL TIPO DE SERVICIO DE CAMA !";
			$this->CallCrear_Asignar_Cama_Virtual($datosPaciente,$datos_estacion,$swCambioCama,$conducta);
			return true;
		}

		if(empty($opcion))
		{
			$this->frmError["MensajeError"] = "SELECCIONE LA PIEZA DONDE VA A CREAR LA CAMA !";
			$this->CallCrear_Asignar_Cama_Virtual($datosPaciente,$datos_estacion,$swCambioCama,$conducta);
			return true;
		}


		if(strlen($descripcion) > 20)
		{
			$this->frmError["MensajeError"] = "LA DESCRIPCION DE LA PIEZA DEBE SER MENOR DE 20 CARACTERES !";
			$this->CallCrear_Asignar_Cama_Virtual($datosPaciente,$datos_estacion,$swCambioCama,$conducta);
			return true;
		}

		if(strlen($ubicacion) > 20)
		{
			$this->frmError["MensajeError"] = "LA UBICACION DE LA PIEZA DEBE SER MENOR DE 20 CARACTERES !";
			$this->CallCrear_Asignar_Cama_Virtual($datosPaciente,$datos_estacion,$swCambioCama,$conducta);
			return true;
		}

		if(strlen($ubicacion_cama) > 20)
		{
			$this->frmError["MensajeError"] = "LA UBICACION DE LA CAMA DEBE SER MENOR DE 20 CARACTERES !";
			$this->CallCrear_Asignar_Cama_Virtual($datosPaciente,$datos_estacion,$swCambioCama,$conducta);
			return true;
		}

		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query="SELECT sw_virtual FROM piezas 
                  WHERE pieza='$opcion' 
                  --AND sw_virtual ISNULL
			   AND estacion_id='".$datos_estacion['estacion_id']."'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer la habitación";
			$this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			$dbconn->RollbackTrans();
			return false;
		}
		if ($result->RecordCount() < 1 AND ($result->fields[0] <> '2' OR $result->fields[0] <> '3'))
		{
               $NUMERO=$this->AsignarPiezaVirtual();
               
               //3 Cama ambulatoria 
               //2 Cama virtual
               if($tipo_serv=='3')  
               {$nom='A';$sw=3;$nomc="A";}else{$nom='V';$sw=2;$nomc="V";}
               
               $query = "INSERT INTO piezas (pieza,
                                             estacion_id,
                                             descripcion,
                                             cantidad_camas,
                                             ubicacion,
                                             sw_virtual)
                                   VALUES   ('$nom$NUMERO',
                                             '".$datos_estacion['estacion_id']."',
                                             '$descripcion',
                                             1,
                                             '$ubicacion',
                                             '$sw')";

          	$result = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al crear la PIEZA";
                    $this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }

               $NUMEROC=$this->AsignarCamaVirtual();
               $query = "INSERT INTO camas ( cama,
                                             pieza,
                                             sw_virtual,
                                             tipo_cama_id,
                                             ubicacion,
                                             estado)
                                   VALUES  ( '$nomc$NUMEROC',
                                             '$nom$NUMERO',
                                             '$sw',
                                             '$tipocama',
                                             '$ubicacion_cama',
                                             '1')";

               $result = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al insertar la cama";
                    $this->mensajeDeError = "error de insercion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }


               //--para llenar el vector
               $query="SELECT descripcion FROM cups
                       WHERE cargo='$cargo'";
               $result = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al tratar de traer cargo de cups";
                    $this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
               $decripcion_cargo=$result->fields[0];
               //retornar a asignar la cama
               //este vector es muy importante ya que lo mandamos a
               //la parte de ingreso del paciente, tener mucho cuidado con el.
               $data_cama[0]=$nomc.$NUMEROC;
               $data_cama[1]=$decripcion_cargo;
               $data_cama[2]=$cargo;
               $data_cama[3]=$tipocama;
               $data_cama[4]=$tipo_clase_cama_id;
               $_PIEZA_ESPECIAL=$nom.$NUMERO; //debemos mandar la pieza a callingresopacientes
               //--para llenar el vector
		}
		else
		{//es por q existe la pieza

               $query="SELECT a.cama,a.pieza FROM camas a,piezas b
                       WHERE (a.sw_virtual='2' OR  a.sw_virtual='3')
                       AND (b.sw_virtual='2' OR  b.sw_virtual='3')
                       AND a.estado='1'
                       --AND a.pieza=b.pieza
                       AND b.estacion_id='".$datos_estacion['estacion_id']."'
                       ORDER BY cama ASC LIMIT 1 OFFSET 0 ";
               $result = $dbconn->Execute($query);
               $cama=$result->fields[0];
               $pieza=$result->fields[1];
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al tratar de crear la habitación";
                    $this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
						
               if($result->RecordCount() > 0)
               {
               	//3 Cama ambulatoria
                    //2 Cama virtual
                    if($tipo_serv=='3')
                    {$nom='A';$sw=3;$nomc="A";}else{$nom='V';$sw=2;$nomc="V";}

                    $query = "UPDATE camas
                              SET
                              tipo_cama_id='$tipocama',
                              sw_virtual='$sw',
                              ubicacion='$ubicacion_cama',
                              pieza='$opcion'
                              WHERE cama = '$cama'
                              AND pieza= '$pieza';";

                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al ejecutar la conexion";
                         $this->mensajeDeError = "Error al intentar crear la cama<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
								
                    $query = "SELECT COUNT(*)
                              FROM piezas
                              WHERE pieza = '$pieza'
                              AND (sw_virtual='2' OR sw_virtual='3')";

                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al ejecutar la conexion";
                         $this->mensajeDeError = "Error al intentar recalcular el numero de camas de la pieza<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }

                    //es por que la habitación era virtual o ambulatoria,si es normal no actualizamos.
                    if($result->fields[0] > 0)
                    {
                         $query = "UPDATE piezas
                                   SET
                                   sw_virtual='$sw'
                                   WHERE pieza= '$pieza' ;";

                         $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al actualizar piezas";
                              $this->mensajeDeError = "Error al intentar crear la cama<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                              $dbconn->RollbackTrans();
                              return false;
                         }

                         $query = "SELECT COUNT(*)
                                   FROM camas
                                   WHERE pieza = '$pieza'";

                         $result = $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al ejecutar la conexion";
                              $this->mensajeDeError = "Error al intentar recalcular el numero de camas de la pieza<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                              $dbconn->RollbackTrans();
                              return false;
                         }

                         $query = "UPDATE piezas
                                   SET cantidad_camas= ".$result->fields[0]."
                                   WHERE pieza = '$pieza'";

                         $result = $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al ejecutar la conexion";
                              $this->mensajeDeError = "Error al intentar recalcular el numero de camas de la pieza<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                              $dbconn->RollbackTrans();
                              return false;
                         }
                    }

                    //--para llenar el vector
                    $query="SELECT descripcion FROM cups
                            WHERE cargo='$cargo'";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al tratar de traer cargo de cups";
                         $this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }

                    $decripcion_cargo=$result->fields[0];
                    //retornar a asignar la cama
                    //este vector es muy importante ya que lo mandamos a
                    //la parte de ingreso del paciente, tener mucho cuidado con el.
                    $data_cama[0]=$cama;
                    $data_cama[1]=$decripcion_cargo;
                    $data_cama[2]=$cargo;
                    $data_cama[3]=$tipocama;
                    $data_cama[4]=$tipo_clase_cama_id;
                    $_PIEZA_ESPECIAL=$opcion; //debemos mandar la pieza a callingresopacientes
		          //--para llenar el vector
               }
               else
               {
                    $query="SELECT a.cama,a.pieza FROM camas a,piezas b
                            WHERE (a.sw_virtual='2' OR  a.sw_virtual='3')
                            AND a.estado='1'
                            AND b.estacion_id='".$datos_estacion['estacion_id']."'
                            ORDER BY cama ASC LIMIT 1 OFFSET 0 ";
                    $result = $dbconn->Execute($query);
                    $cama=$result->fields[0];
                    $pieza=$result->fields[1];
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al tratar de crear la habitación";
                         $this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }

				//es por q en realidad existe la cama pero es normal
                    if($result->RecordCount() > 0)
                    {
                    	//3 Cama ambulatoria 
                         //2 Cama virtual
                         if($tipo_serv=='3')
                         {$nom='A';$sw=3;$nomc="A";}else{$nom='V';$sw=2;$nomc="V";}

	                    $query = "UPDATE camas
                              	SET
                                   tipo_cama_id='$tipocama',
                                   sw_virtual='$sw',
                                   ubicacion='$ubicacion_cama',
                                   pieza='$opcion'
                                   WHERE cama = '$cama'
                                   AND pieza= '$pieza' ;";
					$dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al ejecutar la conexion";
                              $this->mensajeDeError = "Error al intentar crear la cama<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                              $dbconn->RollbackTrans();
                              return false;
                         }

                         $query="SELECT descripcion FROM cups
                         	   WHERE cargo='$cargo'";	
                         $result = $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al tratar de traer cargo de cups";
                              $this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                              $dbconn->RollbackTrans();
                              return false;
                         }

                         $decripcion_cargo=$result->fields[0];
                         //retornar a asignar la cama
                         //este vector es muy importante ya que lo mandamos a
                         //la parte de ingreso del paciente, tener mucho cuidado con el.
                         $data_cama[0]=$cama;
                         $data_cama[1]=$decripcion_cargo;
                         $data_cama[2]=$cargo;
                         $data_cama[3]=$tipocama;
                         $data_cama[4]=$tipo_clase_cama_id;
                         $_PIEZA_ESPECIAL=$opcion; //debemos mandar la pieza a callingresopacientes
                         //este caso es cuando quitamos una cama de una pieza y la asociamos a otra
                    }
                    else //es por q en realidad existe la camapero es virtual
                    {
                    	//3 Cama ambulatoria 
                         //2 Cama virtual                         
                         if($tipo_serv=='3')
					{$nom='A';$sw=3;$nomc="A";}else{$nom='V';$sw=2;$nomc="V";}

                         $NUMEROC=$this->AsignarCamaVirtual();
                         $query = "INSERT INTO camas ( cama,
                                                       pieza,
                                                       sw_virtual,
                                                       tipo_cama_id,
                                                       ubicacion,
                                                       estado)
                                               VALUES ('$nomc$NUMEROC',
                                                       '$opcion',
                                                       '$sw',
                                                       '$tipocama',
                                                       '$ubicacion_cama',
                                                       '1')";

                         $result = $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al insertar la cama virtual";
                              $this->mensajeDeError = "error de insercion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                              $dbconn->RollbackTrans();
                              return false;
                         }

                         $query="SELECT descripcion FROM cups
                                 WHERE cargo='$cargo'";
                         $result = $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al tratar de traer cargo de cups";
                              $this->mensajeDeError = "Ocurrió un error en la conexión de la base de datos o la habitación ya existe<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                              $dbconn->RollbackTrans();
                              return false;
                         }

                         $decripcion_cargo=$result->fields[0];
                         //retornar a asignar la cama
                         //este vector es muy importante ya que lo mandamos a
                         //la parte de ingreso del paciente, tener mucho cuidado con el.
                         $data_cama[0]=$nomc.$NUMEROC;
                         $data_cama[1]=$decripcion_cargo;
                         $data_cama[2]=$cargo;
                         $data_cama[3]=$tipocama;
                         $data_cama[4]=$tipo_clase_cama_id;
                         $_PIEZA_ESPECIAL=$opcion; //debemos mandar la pieza a callingresopacientes
                    }
			}
		}

		$dbconn->CommitTrans();
		$mensaje = "SE GENERO LA CAMA CORRECTAMENTE !";
		$titulo = "MENSAJE DEL SISTEMA";
		$url = ModuloGetURL('app','EE_AsignacionCama','user','CallIngresarPaciente',array("datosPaciente"=>$datosPaciente,"datosCamaPaciente"=>$data_cama,"pieza"=>$_PIEZA_ESPECIAL,"datos_estacion"=>$datos_estacion,'conducta'=>$conducta));
		$link = "CONTINUAR";
		$this->frmMSG($url, $titulo, $mensaje, $link);
		return true;
	}
     
     /**
     * Esta funcion asigna un numero de pieza virtual.
     * y en consulta externa.
     * @access private
     * @return boolean
     */
     function AsignarPiezaVirtual()
     {
          list($dbconn) = GetDBconn();
          $sql="select lpad(nextval('asignarPiezavirtual_seq'),3,0)";
          $result = $dbconn->Execute($sql);
          $dato=$result->fields[0];
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->fileError = __FILE__;
               $this->lineError = __LINE__;
               return false;
          }
	     return $dato;
     }
     
     /**
     * Esta funcion asigna un numero cama virtual.
     * y en consulta externa.
     * @access private
     * @return boolean
     */
     function AsignarCamaVirtual()
     {
          list($dbconn) = GetDBconn();
          $sql="select lpad(nextval('asignarcamavirtual_seq'),3,'0')";
          $result = $dbconn->Execute($sql);
          $dato=$result->fields[0];
	     if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->fileError = __FILE__;
               $this->lineError = __LINE__;
               return false;
	     }
		return $dato;
     }


     /**
     *		CallListadoEstaciones
     *
     *		subproceso 2->"Cambio estacion de enfermeria antes del ingreso al dpto" del proceso "ingreso de pacientes a la estación de enfermería"
     *		llamado desde la funcion 1.1.1.H ->remitir a estacion
     *		1.2.1.U => CallListadoEstaciones muestra la vista de las estaciones
     *
     *		@Author Tizziano Perea
     *		@access Public
     *		@return bool
     */
     function CallListadoEstaciones()
     {
          $datosPaciente = $_REQUEST['datosPaciente']; //vector con los datos del paciente sizeof=10
          $conducta = $_REQUEST['conducta'];// Vector de presencia de Conducta Medica.
          $estado = $_REQUEST['estado'];// Estado de deshabilitacion de la Asignacion de Camas.
          $swCambioCama = $_REQUEST['SwCambioCama'];// Estado de deshabilitacion de la Asignacion de Camas.
          
          if(!$this->ListadoEstaciones($datosPaciente,$_REQUEST['SwTrasladoEE'],$_REQUEST['datos_estacion'],$conducta,$estado,$swCambioCama))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"ListadoEstaciones\"";
               return false;
          }
          return true;
     }
     
     /**
     *		CallCambioCargoIngresarPaciente
     *
     *		regresa a la pantalla de confirmacion para ingresar al paciente + cama.
     *
     *		@Author Rosa Maria Angel
     *		@access Public
     *		@return bool
     */
     function CallCambioCargoIngresarPaciente()
     {
          $datosPaciente = $_REQUEST['datosPaciente']; //vector con los datos del paciente
          $conducta = $_REQUEST['conducta'];

          if(empty($_REQUEST['opcion']))
          {
               $this->frmError["MensajeError"] = "ESCOGA UNA OPCION POR FAVOR";
               $this->DecisionCambioCargo($datosPaciente,$_REQUEST['datos_estacion'],$_REQUEST['spya'],$conducta);
               return true;
          }

          $valores=explode("$",$_REQUEST['opcion']);
          
          $datosPaciente['desc_cargo']=$valores[0];
          $datosPaciente['cargo']=$valores[1];
          $datosPaciente['tipo_cama_id']=$valores[2];
          $datosPaciente['tipo_clase_cama_id']=$valores[3];//tipo de clase de cama.
          
          if(!$this->IngresarPaciente($datosPaciente,$_REQUEST['datos_estacion'],$conducta))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"IngresarPaciente\"";
               return false;
          }
     	return true;
     }//fin Call

     /**
     *		CallIngresarPaciente
     *
     *		subproceso 1->Asignar cama del proceso "ingreso de pacientes a la estación de enfermería"
     *		1.1.3.U => CallIngresarPaciente llama a la ultima interfaz en la que se piden los ultimos datos
     *		viene del link Asignar cama de la vista 2
     *
     *		@Author Rosa Maria Angel
     *		@access Public
     *		@return bool
     */
     function CallRetornoIngresarPaciente()
     {
          $datosPaciente = $_REQUEST['datosPaciente']; //vector con los datos del paciente
          $conducta = $_REQUEST['conducta'];

          if(!$this->IngresarPaciente($datosPaciente,$_REQUEST['datos_estacion'],$conducta))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"IngresarPaciente\"";
               return false;
          }
          return true;
     }//fin CallRetornoIngresarPaciente

     
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
	*	GetEstacionesCirugia =>
	*
	*	obtiene las Estaciones de Cirugia o Departamentos de Cirugia.
	*
	*	@access Public
	*	@return bool-array-string
	*	@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
     function GetEstacionesCirugia($datos_estacion)
     {
          global $ADODB_FETCH_MODE;
          $query = "SELECT A.*, B.descripcion
                    FROM estacion_enfermeria_qx_departamentos AS A,
                    	departamentos AS B
                    WHERE A.empresa_id = '".$datos_estacion[empresa_id]."'
                    AND A.centro_utilidad = '".$datos_estacion[centro_utilidad]."'
                    AND A.departamento = B.departamento
                    ORDER BY B.descripcion;";
		list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar seleccionar las estaciones del departamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF){
			return "ShowMensaje";
		}
		while ($data = $resultado->FetchRow())
		{
			$estacionesCirugia[] = $data;
		}
		return $estacionesCirugia;
     }


	/*Funcion que retorna las camas disponibles q se encuentra en
     * la respectiva estacion de la busqueda.
     */
     function GetCamasDisponibles ($estacion,$plan)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          
          if(!empty($estacion))
          {
                    $filtro="c.estacion_id='$estacion' and";
          }

		//revisamos primero dando prioridad a la tabla tipos_camas_excepcion_plan
		$query="SELECT b.*, c.*, d.cargo,e.descripcion as desc_cargo,d.descripcion as des_tipo
                   FROM  camas b,
                   		piezas c, 
                         tipos_camas_excepcion_plan d,
                         cups e
			    WHERE $filtro b.pieza=c.pieza
                   AND d.tipo_cama_id=b.tipo_cama_id
                   AND e.cargo=d.cargo
                   AND hb_estado_cama(cama)=0
                   AND d.plan_id='$plan'
                   AND c.sw_virtual ISNULL
                   AND b.sw_virtual='1' 
                   ORDER BY b.pieza,b.cama ASC";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          
          $result = $dbconn->Execute($query);

          if($result->RecordCount() < 1)
          {
               $query="SELECT b.*,c.*,d.cargo,e.descripcion as desc_cargo,d.descripcion as des_tipo
                       FROM camas b,piezas c, tipos_camas d,cups e
                       WHERE $filtro b.pieza=c.pieza
                       AND d.tipo_cama_id=b.tipo_cama_id
                       AND e.cargo=d.cargo
                       AND c.sw_virtual ISNULL
                       AND hb_estado_cama(cama)=0
                       AND b.sw_virtual='1' 
                       ORDER BY b.pieza,b.cama ASC";
                       
               $result = $dbconn->Execute($query);
          }

		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer las camas disponibles";
			$this->mensajeDeError = "Error al intentar obtener las camas disponibles de la estación de enfermería<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		$datosCamas=$result->GetRows();
		foreach($datosCamas as  $k => $v)
		{
			$salida[$v['estacion_id']][$v['pieza']][$v['cama']]=$v;
		}
		return $salida;
	}//fin GetCamasDisponibles
     

     /*
     * Funcion que permite realizar una revision de las piezas exiatentes en la EE
     */
	function Revisar_Habitaciones_Existentes($estacion_id)
	{
          list($dbconn) = GetDBconn();
          $query="SELECT * FROM piezas WHERE estacion_id='$estacion_id' ORDER BY sw_virtual ASC";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar la habitación";
               $this->mensajeDeError = "Ocurrió un error en la conexión de la bd<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          $i=0;
          while(!$result->EOF)
          {
               $var[$i]= $result->GetRowAssoc($ToUpper = false);
               $i++;
               $result->MoveNext();
          }
          return $var;
	}
     
     /*
	*revisa cuantas camas especiales, ya sean virtuales o ambulatorias posee la habitación.
     */
	function Conteo_Camas_Especiales($pieza)
	{
          list($dbconn) = GetDBconn();
          $query = "SELECT COUNT(*)
                    FROM camas
                    WHERE pieza = '$pieza'
                    AND sw_virtual IN('2','3')";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Error al intentar recalcular el numero de camas de la pieza<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          return $result->fields[0];
	}
     
     /*
     * Funcion que trae los tipos de camas
     */
	function Traer_Tipos_Cama($estacion)
	{
		list($dbconn) = GetDBconn();
          $query = "SELECT a.tipo_cama_id,a.descripcion,precio_lista,cargo
                    FROM tipos_camas a,
					estaciones_tipos_camas_permitidos b
                    WHERE b.estacion_id='$estacion'
                    AND a.tipo_cama_id=b.tipo_cama_id";
          $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al traer los tipos de camas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;
			return false;
		}
          while (!$result->EOF)
          {
               $var[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }
		return $var;
	}

     /*
     * Funcion que trae los tipos de camas
     */
     function GetDescripcionCama($cargo)
     {
	     list($dbconn) = GetDBconn();
          $query = "SELECT descripcion FROM cups WHERE cargo='$cargo'";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al traer la descripcion de la tabla camas/cups";
               return false;
          }
          return $result->fields[0]; //retornamos la descripcion.
     }
     
     /*
     * Funcion que obtiene los medicamentos que estan pendientes por
     * traspaso de estacion.
     */
     function ProductosPendientes_X_Traspaso($ingreso)
     {
	     list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;

          $sql = "SELECT A.descripcion, 
                    	B.cod_principio_activo, B.concentracion_forma_farmacologica, 
                    	B.cod_forma_farmacologica,
               		C.descripcion AS principio,
                    	D.descripcion AS forma_farma,
                         BP.codigo_producto,
                         BP.stock
               FROM bodega_paciente AS BP
                    LEFT JOIN inventarios_productos AS A ON (BP.codigo_producto = A.codigo_producto),
                    medicamentos AS B,
                    inv_med_cod_principios_activos AS C,
                    inv_med_cod_forma_farmacologica AS D
               WHERE  BP.ingreso = ".$ingreso."
               AND    BP.sw_tipo_producto = 'M'
               AND    BP.stock > 0
               AND    A.codigo_producto = B.codigo_medicamento
               AND    B.cod_principio_activo = C.cod_principio_activo
               AND    B.cod_forma_farmacologica = D.cod_forma_farmacologica;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al traer la descripcion de la tabla camas/cups";
               return false;
          }
		
          while($data = $resultado->FetchRow())
          {
          	$Medicamentos[] = $data;
          }
          
          return $Medicamentos;
     }
     
     
/*************************************************************************************
	FUNCIONES DE PARA EL CAMBIO O TRASLADO DE LOS PACIENTES DE ESTACION
*************************************************************************************/
     
     /**
     * Funcion que obtiene la informacion de las devoluciones pendientes.
     */
	function GetInformacionDevolucion_BodegaPaciente($ingreso)
     {
          list($dbconnect) = GetDBconn();
     	$query = "SELECT SUM(cantidad_en_devolucion)
                    FROM bodega_paciente
                    WHERE ingreso = ".$ingreso.";";
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
     * Funcion que obtiene la informacion de medicamentos en bodega.
     */
     function GetInformacionMedicamentos_BodegaPaciente($ingreso, $filtro, $sw)
     {
          list($dbconnect) = GetDBconn();
          if($sw == 0)
          { $campo = "SUM(cantidad_en_solicitud)"; }
          elseif($sw == 1)
          { $campo = "SUM(stock_almacen)"; }
          elseif($sw == 2)
          { $campo = "SUM(cantidad_pendiente_por_recibir)"; }
          
     	$query = "SELECT ".$campo."
                    FROM bodega_paciente
                    WHERE ingreso = ".$ingreso."
                    AND sw_tipo_producto = '$filtro';";
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
     * Funcion que obtiene la informacion de insumos en bodega.
     */
     function GetInformacionSuministros_BodegaPaciente($ingreso, $filtro, $sw)
     {
          list($dbconnect) = GetDBconn();
          if($sw == 0)
          { $campo = "SUM(cantidad_en_solicitud)"; }
          elseif($sw == 1)
          { $campo = "SUM(stock_almacen)"; }
          elseif($sw == 2)
          { $campo = "SUM(cantidad_pendiente_por_recibir)"; }
          
     	$query = "SELECT ".$campo."
                    FROM bodega_paciente
                    WHERE ingreso = ".$ingreso."
                    AND sw_tipo_producto = '$filtro';";
          $result = $dbconnect->Execute($query);
          if($result->fields[0] > 0)
          {
          	return '1';
          }else
          {
          	return '0';
          }
     }
     
     //
     function RestriccionAsignacionCama($cuenta)
     {
          list($dbconnect) = GetDBconn();
          $query = "SELECT Count(sw_traslado_medicamentos)
                    FROM estaciones_enfermeria_ingresos_pendientes
                    WHERE numerodecuenta = ".$cuenta."
                    AND sw_traslado_medicamentos = '1';";
          $result = $dbconnect->Execute($query);
          if($result->fields[0] > 0)
          {
          	return '1';
          }else
          {
          	return '0';
          }
     	
     }

/*************************************************************************************
	FUNCIONES DE PARA EL CAMBIO O TRASLADO DE LOS PACIENTES DE ESTACION
*************************************************************************************/

}//end of class

?>
