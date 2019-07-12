<?
/**
* Submodulo para el Cumplimiento de Procedimientos Realizados.
*
* Submodulo para manejar y detallar los procedimientos realizados por
* cada uno de los tipos de profesionales.
* @author Tizziano Perea O. <tizzianop@gmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Cumplimiento_ProcedimientosRealizados.php,v 1.20 2006/12/19 21:00:13 jgomez Exp $
*/


class Cumplimiento_ProcedimientosRealizados extends hc_classModules
{
	var $limit;
	var $conteo;


	function Cumplimiento_ProcedimientosRealizados()
	{
		$this->plan_id = $this->datosResponsable['plan_id'];
		$this->limit=GetLimitBrowser();
		return true;
	}


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

// 	function GetVersion()
// 	{
// 		$informacion=array(
// 		'version'=>'1',
// 		'subversion'=>'0',
// 		'revision'=>'0',
// 		'fecha'=>'',
// 		'autor'=>'TIZZIANO PEREA OCORO',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}


	function GetConsulta()
	{
		$pfj=$this->frmPrefijo;
		if(empty($_REQUEST['accion'.$pfj]))
		{
			$this->frmConsulta();
		}
		return $this->salida;
	}
     
/**
* Esta metodo captura los datos de la impresión de la Historia Clinica.
* @access private
* @return text Datos HTML de la pantalla.
*/
	function GetReporte_Html()
	{
		$imprimir=$this->frmHistoria();
		if($imprimir==false)
		{
			return true;
		}
		return $imprimir;
	}


/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetEstado()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query="SELECT count(*)
				FROM hc_sub_procedimientos_realizados_notas
				WHERE ingreso=".$this->ingreso.";";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$estado=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		if ($estado[count] == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
		return true;
	}


	function GetForma()
	{
		$pfj=$this->frmPrefijo;
		if(empty($_REQUEST['accion'.$pfj]))
		{
			$this->frmForma();
		}
		else
		{
			if($_REQUEST['accion'.$pfj]=='ListadoProcedimientos')
			{
				$this->frmForma();
			}

			if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Cargos')
			{
				$vectorA= $this->Busqueda_Avanzada_Cargos();
				$this->frmForma_Seleccion_Procedimientos($vectorA);
			}

			if($_REQUEST['accion'.$pfj]=='Llenar_Observacion_Procedimiento')
			{
				if ($_REQUEST['cargo'.$pfj] != '')
				{
					$this->Llenar_Observacion_Procedimiento($_REQUEST['cargo'.$pfj], $_REQUEST['descripcion'.$pfj], $_REQUEST['cantidad'.$pfj]);
				}
				else
				{
					$this->frmError["MensajeError"]="PARA ADICIONAR UN CARGO DIRECTO DEBE SELECCIONAR UN MEDICAMENTO DE LA LISTA";
					$this->frmForma();
				}
			}

			if($_REQUEST['accion'.$pfj]=='insertar_observacion')
			{
				if ($this->Insertar_Observacion()==true)
				{
					$_REQUEST = '';
					$this->frmForma();
				}
				else
				{
					$this->Llenar_Observacion_Procedimiento($_REQUEST['procedimiento_nota'.$pfj],$_REQUEST['cargo'.$pfj], $_REQUEST['descripcion'.$pfj]);
				}
			}

			if($_REQUEST['accion'.$pfj]=='observacion')
			{
				$this->frmForma_Modificar_Observacion($_REQUEST['evolucion'.$pfj],$_REQUEST['datos'.$pfj]);
			}

			if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Diagnosticos')
			{
				$vectorD = $this->Busqueda_Avanzada_Diagnosticos();
				$this->frmInsertarDX($_REQUEST['evolucion'.$pfj],$_REQUEST['datos'.$pfj],$vectorD);
			}

			if($_REQUEST['accion'.$pfj]=='insertar_varios_diagnosticos')
			{
                	$this->Insert_ProcedimientoDiagnostico();
				$this->frmForma_Modificar_Observacion($_REQUEST['evolucion'.$pfj],$_REQUEST['datos'.$pfj]);
			}

			if($_REQUEST['accion'.$pfj]=='modificar')
			{
				$this->Modificar_ProcedimientoRealizado();
				$this->frmForma();
			}

			if($_REQUEST['accion'.$pfj]=='eliminar_diagnostico')
			{
				$this->Eliminar_Diagnostico_Solicitado($_REQUEST['codigo'.$pfj]);
				$this->frmForma_Modificar_Observacion($_REQUEST['evolucion'.$pfj],$_REQUEST['datos'.$pfj]);
			}

			if($_REQUEST['accion'.$pfj]=='eliminacion_individual')
			{
				$this->Eliminar_Procedimiento_Individual($_REQUEST['procedimiento_detalle_id'.$pfj]);
				if ($_SESSION['BASIC'] == 0)
				{
					$datos = $this->Consulta_Procedimiento_Realizado();
					$this->frmForma_Modificar_Observacion($_REQUEST['evolucion'.$pfj],$datos);
					unset ($_SESSION['BASIC']);
				}
				else
				{
					$this->frmForma();
					unset ($_SESSION['BASIC']);
				}
			}

			if($_REQUEST['accion'.$pfj]=='eliminar')
			{
				$this->Eliminar_Procedimiento();
				$this->frmForma();
			}

			if($_REQUEST['accion'.$pfj]=='resumen')
			{
				$info_general = $this->Resumen_Procedimientos_Totales($_REQUEST['info_evolucion'.$pfj],$_REQUEST['info_ingreso'.$pfj],$_REQUEST['info_usuario'.$pfj]);
				$this->frmForma_Resumen_Procedimientos($info_general,$_REQUEST['info_evolucion'.$pfj],$_REQUEST['info_ingreso'.$pfj]);
			}
               
               if($_REQUEST['accion'.$pfj]=='InsertarDX')
               {
               	$this->frmInsertarDX($_REQUEST['evolucion'.$pfj],$_REQUEST['datos'.$pfj],'');
               }
               
               if($_REQUEST['accion'.$pfj]=='InsertarCaracteristicas')
               {
               	$this->frmInsertarCaracteristicas($_REQUEST['evolucion'.$pfj],$_REQUEST['datos'.$pfj],$_REQUEST['cargo'.$pfj]);
               }
               
               if($_REQUEST['accion'.$pfj]=='volver_DX')
               {
               	$this->frmForma_Modificar_Observacion($_REQUEST['evolucion'.$pfj],$_REQUEST['datos'.$pfj]);
               }
               
               if($_REQUEST['accion'.$pfj]=='modificar_caracteristicas')
			{
                    $this->Insertar_CaracteristicaProcedimiento($_REQUEST['cargo'.$pfj], $_REQUEST['tiposala'.$pfj]);
                    $this->frmForma_Modificar_Observacion($_REQUEST['evolucion'.$pfj],$_REQUEST['datos'.$pfj]);
               }

		}
		return $this->salida;
	}


     function Busqueda_Avanzada_Cargos()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $opcion = ($_REQUEST['criterio1'.$pfj]);
          $cargo = ($_REQUEST['cargo'.$pfj]);
          $descripcion = STRTOUPPER($_REQUEST['descripcion'.$pfj]);
     
          $filtroTipoCargo = '';
          $busqueda1 = '';
          $busqueda2 = '';
     
          //Opciones de filtrado: Cargo, Descripcion, Frecuentes.
     
          if ($opcion == '-1')
          {
               if ($cargo != '')
               {
                    $busqueda1 =" AND a.cargo_cups LIKE '$cargo%'";
               }
     
               if ($descripcion != '')
               {
                    $busqueda2 ="AND b.descripcion LIKE '%$descripcion%'";
               }
     
               if(empty($_REQUEST['conteo'.$pfj]))
               {
                    if ($this->tipo_profesional == 3 OR $this->tipo_profesional == 4 OR $this->tipo_profesional == 7 OR $this->tipo_profesional == 8)
                    {
                         $query= "SELECT count(*)
                                  FROM hc_sub_procedimientos_realizados_cups_dpto AS A, cups AS B
                                  WHERE A.cargo_cups = B.cargo
                                  AND A.departamento = '".$this->departamento."'
                                  AND A.sw_enfermeria = '1'
                                  $busqueda1 $busqueda2;";
                    }
                    else
                    {
                         $query= "SELECT count(*)
                                  FROM hc_sub_procedimientos_realizados_cups_dpto AS A, cups AS B
                                  WHERE A.cargo_cups = B.cargo
                                  AND A.departamento = '".$this->departamento."'
                                  AND A.tipo_profesional = '".$this->tipo_profesional."'
                                  $busqueda1 $busqueda2;";
                    }
                    
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }
                    list($this->conteo)=$resulta->fetchRow();
               }
               else
               {
                    $this->conteo=$_REQUEST['conteo'.$pfj];
               }
               if(!$_REQUEST['Of'.$pfj])
               {
                    $Of='0';
               }
               else
               {
                    $Of=$_REQUEST['Of'.$pfj];
                    if($Of > $this->conteo)
                    {
                         $Of=0;
                         $_REQUEST['Of'.$pfj]=0;
                         $_REQUEST['paso1'.$pfj]=1;
                    }
               }
          }
     
          if ($this->tipo_profesional == 3 OR $this->tipo_profesional == 4 OR $this->tipo_profesional == 7 OR $this->tipo_profesional == 8)
          {
               $query= "SELECT A.*, B.*
                         FROM hc_sub_procedimientos_realizados_cups_dpto AS A, cups AS B
                         WHERE A.cargo_cups = B.cargo
                         AND A.departamento = '".$this->departamento."'
                         AND A.sw_enfermeria = '1'
                         $busqueda1 $busqueda2
                         LIMIT ".$this->limit." OFFSET $Of;";
          }
          else
          {
               $query= "SELECT A.*, B.*
                         FROM hc_sub_procedimientos_realizados_cups_dpto AS A, cups AS B
                         WHERE A.cargo_cups = B.cargo
                         AND A.departamento = '".$this->departamento."'
                         AND A.tipo_profesional = '".$this->tipo_profesional."'
                         $busqueda1 $busqueda2
                         LIMIT ".$this->limit." OFFSET $Of;";
          }
     
          //Se debe agregar busqueda de cargos por Frecuencia. Tabla hc_frecuentes.
          
          $resulta = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $i=0;
          while(!$resulta->EOF)
          {
               $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
               $i++;
          }
          if($this->conteo==='0')
          {
               $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
               return false;
          }
          $resulta->Close();
          return $var;
     }



	function Info_Profesional()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$sql = "SELECT tipo_tercero_id, tercero_id
				FROM profesionales_usuarios
				WHERE usuario_id=".UserGetUID().";";

		$resulta = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al en busqueda de datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			list($profesional) = $resulta->GetRows();
			return $profesional;
		}
	}



	function Insertar_Observacion()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		
          $Cargo = $_REQUEST['cargo'.$pfj];
		$Observacion = $_REQUEST['observacion'.$pfj];
		$Descripcion = $_REQUEST['descripcion'.$pfj];
		$Cantidad = $_REQUEST['cantidad'.$pfj];
    
		/*********************INSERTO CARGO EN CUENTAS DETALLE***********************************/
		$query="SELECT sw_tipo_cargo
                  FROM hc_sub_procedimientos_realizados_cups_dpto
                  WHERE cargo_cups='".$Cargo."' AND departamento='".$this->departamento."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();			
			return false;
		}else{
			$procedimientoQX=$result->fields[0];
		}
		$chequeadosCargados=0;
		if($procedimientoQX!='QX'){
      
          IncludeLib('funciones_facturacion');
          $profesional = $this->Info_Profesional();
      
          if($_REQUEST['malla_validadora'.$pfj]['validacion_cargar_cuenta'] == 1)
          {
               foreach($_SESSION['VectorCargos'] as $k => $v){
                    $carguito = $v['cargo'];
                    $tarifario = $v['tarifario_id'];
               }  
               
               $Vector_Funciones[] = array('cargo'=>$carguito,'tarifario'=>$tarifario,'servicio'=>$this->servicio,
                                           'aut_int'=>$_REQUEST['malla_validadora'.$pfj]['aut_int'],'aut_ext'=>$_REQUEST['malla_validadora'.$pfj]['aut_ext'],
                                           'cups'=>$Cargo,'cantidad'=>$Cantidad,'departamento'=>$this->departamento,'sw_cargue'=>3,'tipo_tercero'=>$profesional[0],'tercero'=>$profesional[1]);
                         
               if(InsertarCuentasDetalle($this->empresa_id,$this->centro_utilidad,$this->cuenta,$this->plan_id,$Vector_Funciones,'')==false){
                    return false;
               }
               
          }
          else{                
                    foreach($_SESSION['VectorCargos'] as $kk => $vv)
                    {   
                         if (!empty($_REQUEST['check_cargo'.$kk]))
                         {
                              $procedimientos=explode(',',$_REQUEST['check_cargo'.$kk]);            
                              unset($Vector_Funciones);
                              
                              $Vector_Funciones[] = array('cargo'=>$procedimientos[1],'tarifario'=>$procedimientos[0],'servicio'=>$this->servicio,
                                                       'aut_int'=>$_REQUEST['malla_validadora'.$pfj]['aut_int'],'aut_ext'=>$_REQUEST['malla_validadora'.$pfj]['aut_ext'],
                                                       'cups'=>$Cargo,'cantidad'=>$Cantidad,'departamento'=>$this->departamento,'sw_cargue'=>3,'tipo_tercero'=>$profesional[0],'tercero'=>$profesional[1]);
                         
                              if(InsertarCuentasDetalle($this->empresa_id,$this->centro_utilidad,$this->cuenta,$this->plan_id,$Vector_Funciones,'')==false){
                                   return false;
                              }
                              $chequeadosCargados=1;            
                         }        
                    }    
               }        
          }
		/*******************FIN INSERTO CARGO EN CUENTAS DETALLE*****************************/

		//Inicio de Insercion con RollbackTrans().
		$dbconn->BeginTrans();

		/****************************INSERTO EQUIVALENCIA EN PENDIENTES POR CARGAR****************************/
    
    
		if (($_REQUEST['malla_validadora'.$pfj]['validacion_cargar_cuenta'] == 0 && $chequeadosCargados!=1) || $procedimientoQX=='QX')
		{
               if(empty($_REQUEST['malla_validadora'.$pfj]['aut_int']))
               { $_REQUEST['malla_validadora'.$pfj]['aut_int']='NULL'; }
               
               if(empty($_REQUEST['malla_validadora'.$pfj]['aut_ext']))
	          { $_REQUEST['malla_validadora'.$pfj]['aut_ext']='NULL'; }
               	
               $query = "INSERT INTO procedimientos_pendientes_cargar (ingreso,
                                                                       cargo_cups,
                                                                       evolucion_id,
                                                                       autorizacion_int,
                                                                       autorizacion_ext)
                                                                 VALUES(".$this->ingreso.",
                                                                        '$Cargo',
                                                                        ".$this->evolucion.",
                                                                        ".$_REQUEST['malla_validadora'.$pfj]['aut_int'].",
                                                                        ".$_REQUEST['malla_validadora'.$pfj]['aut_ext'].");";
     
               $resulta = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al insertar en hc_cargos_directos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }

               $sql = "SELECT MAX (procedimiento_pendiente_cargar_id)
                         FROM procedimientos_pendientes_cargar
                         WHERE ingreso = ".$this->ingreso."
                         AND evolucion_id = ".$this->evolucion."
                         AND cargo_cups = '$Cargo';";
     
               $resulta=$dbconn->Execute($sql);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al insertar en hc_cargos_directos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               else
               {
                    list($procedimiento_pendiente_id) = $resulta->FetchRow();
               }

               foreach($_SESSION['VectorCargos'] as $k => $v)
               {
                    if (!empty($_REQUEST['check_cargo'.$k]))
                    {
                         $var = $_REQUEST['check_cargo'.$k];
                         $procedimientos=explode(',',$_REQUEST['check_cargo'.$k]);
                         $query = "INSERT INTO procedimientos_pendientes_cargar_det
                                   (procedimiento_pendiente_cargar_id,tarifario_id,cargo)
                                   VALUES($procedimiento_pendiente_id,'$procedimientos[0]','$procedimientos[1]');";
          
                         $resulta=$dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al insertar en hc_cargos_directos";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                    }
               }
          }
		/**************************FIN INSERTO EQUIVALENCIA EN PENDIENTES POR CARGAR**************************/

		$query = "SELECT *
				FROM hc_sub_procedimientos_realizados_notas
				WHERE ingreso = ".$this->ingreso."
				AND evolucion_id = ".$this->evolucion.";";
    
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		while(!$resulta->EOF)
		{
			$indice=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}

		/*******************************INSERTO NOTA*********************************/
		if ($indice[ingreso] != $this->ingreso && $indice[evolucion_id] != $this->evolucion)
		{
			$query = '';
			$query = "INSERT INTO hc_sub_procedimientos_realizados_notas (ingreso,
															  evolucion_id,
															  descripcion_medica,
															  usuario_id,
															  fecha_registro)
                                                                      VALUES(".$this->ingreso.",
															  ".$this->evolucion.",
															  NULL,
															  ".$this->usuario_id.",
															  now());";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar en hc_sub_procedimientos_realizados_notas";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		//Fin de insercion de nota.
		/*******************************INSERTO CARGO*********************************/
		$query = '';
		$query = "INSERT INTO hc_sub_procedimientos_realizados_notas_detalle (ingreso,
																evolucion_id,
																cargo,
																cantidad)
                                                                      VALUES   (".$this->ingreso.",
																".$this->evolucion.",
																'$Cargo',
																$Cantidad);";
                                  
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al insertar en hc_sub_procedimientos_realizados_notas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		//Fin de insercion de cargo.
		/*****************************INSERTO EQUIVALENCIAS***************************/
		$query = '';
		$query ="SELECT MAX (procedimiento_detalle_id)
			    FROM hc_sub_procedimientos_realizados_notas_detalle
			    WHERE ingreso = ".$this->ingreso."
			    AND evolucion_id = ".$this->evolucion.";";
    
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al insertar en hc_cargos_directos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
			list ($descripcion_id) = $resulta->FetchRow();

			foreach($_SESSION['VectorCargos'] as $k => $v)
			{
				if (!empty($_REQUEST['check_cargo'.$k]))
				{
					$procedimientos=explode(',',$_REQUEST['check_cargo'.$k]);
					$sql2 = "INSERT INTO hc_sub_procedimientos_realizados_notas_detalle_equivalencias
													(cargo,
													tarifario_id,
													cantidad,
													procedimiento_detalle_id)
											VALUES  ('$procedimientos[1]',
													'$procedimientos[0]',
													$Cantidad,
													$descripcion_id);";
                         
					$resulta = $dbconn->Execute($sql2);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al insertar en hc_cargos_directos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}
		}
		$dbconn->CommitTrans();
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
	}


	function Insertar_CaracteristicaProcedimiento($cargo, $tipoSala)
     {
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();

          $Procedimiento = explode(",",$cargo);
          
          $sql ="SELECT count(*)
          	  FROM hc_sub_procedimientos_realizados_caracteristicas
                 WHERE ingreso = ".$this->ingreso."
                 AND evolucion_id = ".$this->evolucion."
                 AND cargo_cups = '".$Procedimiento[0]."';";
          
          $resulta = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Consultar en hc_sub_procedimientos_realizados_caracteristicas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
          if($resulta->fields[0] == 1)
          {
          	$sql ="DELETE FROM hc_sub_procedimientos_realizados_caracteristicas
               	  WHERE ingreso = ".$this->ingreso."
                      AND evolucion_id = ".$this->evolucion."
                      AND cargo_cups = '".$Procedimiento[0]."';";
               $resulta = $dbconn->Execute($sql);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Borrar de hc_sub_procedimientos_realizados_caracteristicas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }               
               
          }

          $query ="INSERT INTO hc_sub_procedimientos_realizados_caracteristicas
          									(ingreso,
                                                        evolucion_id,
                                                        cargo_cups,
                                                        tipo_sala_id)
          							   VALUES (".$this->ingreso.",
                                                		 ".$this->evolucion.",
                                                        '".$Procedimiento[0]."',
                                                        '".$tipoSala."');";
          $resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al insertar en hc_sub_procedimientos_realizados_caracteristicas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;                                                
     }
     
     function Modificar_ProcedimientoRealizado()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$Observacion = $_REQUEST['obs'.$pfj];
		$sql = "UPDATE hc_sub_procedimientos_realizados_notas
				SET descripcion_medica = '".$Observacion."'
				WHERE ingreso = ".$this->ingreso."
				AND evolucion_id = ".$this->evolucion.";";

		$resulta = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al actualizar hc_sub_procedimientos_realizados_notas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
	}


	function Consulta_Procedimiento_Realizado()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo'.$pfj]))
		{
			$query = "SELECT count(*)
					  FROM hc_sub_procedimientos_realizados_notas_detalle
					  WHERE ingreso=".$this->ingreso.";";

			$resulta = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'.$pfj];
      		if($Of > $this->conteo)
			{
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}

		$query= "SELECT C.descripcion_medica, C.usuario_id, A.ingreso, A.evolucion_id, A.cargo, A.cantidad, A.procedimiento_detalle_id, B.descripcion
				FROM hc_sub_procedimientos_realizados_notas AS C
				LEFT JOIN hc_sub_procedimientos_realizados_notas_detalle AS A ON (A.ingreso = C.ingreso AND A.evolucion_id = C.evolucion_id)
				LEFT JOIN cups AS B ON (A.cargo = B.cargo)
				WHERE A.ingreso = ".$this->ingreso."
				ORDER BY A.evolucion_id DESC
				LIMIT ".$this->limit." OFFSET $Of;";

		$resulta = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		while(!$resulta->EOF)
		{
			$datos[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		if($this->conteo==='0')
		{
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		return $datos;
	}


	function Resumen_Procedimientos_Totales($info_evolucion, $info_ingreso, $info_usuario)
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query = "SELECT A.descripcion_medica, A.usuario_id, A.fecha_registro,
				  B.cargo, B.cantidad, C.cargo AS cargo_equivalencia, C.tarifario_id, C.cantidad AS cantidad_equivalencia, D.usuario, D.nombre, D.descripcion AS descripcion_usuario, E.descripcion AS nombre_cargo, F.descripcion AS nombre_equivelancia
				  FROM hc_sub_procedimientos_realizados_notas AS A,
				  system_usuarios AS D,
				  hc_sub_procedimientos_realizados_notas_detalle AS B LEFT JOIN
				  hc_sub_procedimientos_realizados_notas_detalle_equivalencias AS C ON (B.procedimiento_detalle_id = C.procedimiento_detalle_id)
				  LEFT JOIN tarifarios_detalle AS F ON (F.tarifario_id = C.tarifario_id AND F.cargo = C.cargo),
				  cups AS E
				  WHERE A.ingreso = $info_ingreso
				  AND A.evolucion_id = $info_evolucion
				  AND A.usuario_id = $info_usuario
				  AND A.usuario_id = D.usuario_id
				  AND A.ingreso = B.ingreso
				  AND A.evolucion_id = B.evolucion_id
				  AND B.cargo = E.cargo;;";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		while(!$result->EOF)
		{
			$info_general[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		return $info_general;
	}
     
     
     function Get_InformacionSala($evolucion, $ingreso)
     {
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query = "SELECT A.*, B.descripcion, C.descripcion AS nombre_procedimiento 
          		FROM hc_sub_procedimientos_realizados_caracteristicas AS A,
                    qx_tipos_salas AS B,
                    cups AS C
          		WHERE ingreso= ".$ingreso."
                    AND evolucion_id = ".$evolucion."
                    AND A.tipo_sala_id = B.tipo_sala_id
                    AND A.cargo_cups = C.cargo;";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
          while(!$result->EOF)
		{
			$InformacionSala[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		return $InformacionSala;
     }


	function Busqueda_Avanzada_Diagnosticos()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$codigo       = STRTOUPPER ($_REQUEST['codigo'.$pfj]);
		$diagnostico  =STRTOUPPER($_REQUEST['diagnostico'.$pfj]);

		$busqueda1 = '';
		$busqueda2 = '';

		if ($codigo != '')
		{
			$busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
		}

		if (($diagnostico != '') AND ($codigo != ''))
		{
			$busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
		}

		if (($diagnostico != '') AND ($codigo == ''))
		{
			$busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
		}

		if(empty($_REQUEST['conteo'.$pfj]))
		{
			$query = "SELECT count(*)
						FROM diagnosticos
						$busqueda1 $busqueda2";

			$resulta = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'.$pfj];
			if($Of > $this->conteo)
			{
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}

		$query = "
					SELECT diagnostico_id, diagnostico_nombre
					FROM diagnosticos
					$busqueda1 $busqueda2 order by diagnostico_id
					LIMIT ".$this->limit." OFFSET $Of;";

		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}

		if($this->conteo==='0')
		{
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			return false;
		}
		$resulta->Close();
		return $var;
	}


	function Insert_ProcedimientoDiagnostico()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
          
          $sw_dx_finalidad = $_REQUEST['sw_dx_finalidad'.$pfj];
          
		foreach($_REQUEST['opD'.$pfj] as $index=>$codigo)
		{
			$tipo_dx = $_REQUEST['dx'.$index.$pfj];
			if($tipo_dx == '')
			{
				$tipo_dx = '1';
			}
               
               //BUSQUEDA DE DX REPETIDO EN PROCEDIMIENTO
               $query="SELECT count(*) 
                       FROM hc_sub_procedimientos_realizados_notas_diagnosticos
                       WHERE diagnostico_id = '".$codigo."'
                       AND ingreso = ".$this->ingreso."
                       AND evolucion_id = ".$this->evolucion."
                       AND sw_finalidad_dx = '$sw_dx_finalidad';"; 
              
               $resulta=$dbconn->Execute($query);
			if ($resulta->fields[0]==0)
               { 
                    //BUSQUEDA DE DX PRINCIPAL EN EL PROCEDIMIENTO
                    $sql="SELECT count(*) 
                            FROM hc_sub_procedimientos_realizados_notas_diagnosticos
                            WHERE ingreso = ".$this->ingreso."
                            AND evolucion_id = ".$this->evolucion."
                            AND sw_principal = '1'
                            AND sw_finalidad_dx = '$sw_dx_finalidad';";
                    $resulta=$dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $this->frmError["MensajeError"]="EL DIAGNOSTICO ".$codigo." YA FUE ASIGNADO.";
                         return false;
                    }
                    
                    //INSERCION DE 1 DX PRINCIPAL
                    if($resulta->fields[0]==0)
                    {
                         $query="INSERT INTO hc_sub_procedimientos_realizados_notas_diagnosticos
                                        (diagnostico_id, ingreso, evolucion_id, sw_principal, tipo_diagnostico, sw_finalidad_dx)
                                 VALUES ('".$codigo."', ".$this->ingreso.", ".$this->evolucion.", '1', '$tipo_dx', '$sw_dx_finalidad');";
                    }
                    //INSERCION DE LOS DEMAS DX'S (NO PRINCIPALES)
                    else
                    {
                         $query="INSERT INTO hc_sub_procedimientos_realizados_notas_diagnosticos
                                        (diagnostico_id, ingreso, evolucion_id, sw_principal, tipo_diagnostico, sw_finalidad_dx)
                                 VALUES ('".$codigo."', ".$this->ingreso.", ".$this->evolucion.", '0', '$tipo_dx', '$sw_dx_finalidad');";
                    }
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $this->frmError["MensajeError"]="EL DIAGNOSTICO '".$codigo."' YA FUE ASIGNADO.";
                         return false;
                    }
                    else
                    {
                         $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                    }
               }
               //FIN BUSQUEDA DE DX REPETIDO EN INGRESO
               else
               {
                    $this->frmError["MensajeError"]="EL DIAGNOSTICO '".$codigo."' YA FUE ASIGNADO.";
               }
		}// Fin foreach
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
	}


	function Diagnosticos_Solicitados($sw_finalidad_dx)
	{
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT A.diagnostico_id, B.diagnostico_nombre, 
          			 A.sw_principal, A.tipo_diagnostico
				 FROM hc_sub_procedimientos_realizados_notas AS C
				 LEFT JOIN hc_sub_procedimientos_realizados_notas_diagnosticos AS A ON (C.ingreso = A.ingreso AND C.evolucion_id = A.evolucion_id)
				 LEFT JOIN diagnosticos AS B ON (A.diagnostico_id = B.diagnostico_id)
				 WHERE C.ingreso = ".$this->ingreso."
				 AND C.evolucion_id = ".$this->evolucion."
                     AND A.sw_finalidad_dx = '$sw_finalidad_dx'
                     ORDER BY A.sw_principal DESC;";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la tabla apoyod_tipos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			$i=0;
			while (!$result->EOF)
			{
				$vector[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
		}
		$result->Close();
		return $vector;
	}


	function Eliminar_Diagnostico_Solicitado($codigo)
	{
          $pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
          
          $sw_dx_finalidad = $_REQUEST['sw_dx_finalidad'.$pfj];

		$query="DELETE FROM hc_sub_procedimientos_realizados_notas_diagnosticos
          	   WHERE diagnostico_id = '".$codigo."'
                  AND ingreso = ".$this->ingreso."
                  AND evolucion_id = ".$this->evolucion."
                  AND sw_finalidad_dx = '$sw_dx_finalidad';";
		
          $resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL DIAGNOSTICO";
			return false;
		}
		else
		{
			$sql="SELECT diagnostico_id, sw_principal
               	 FROM hc_sub_procedimientos_realizados_notas_diagnosticos
                     WHERE ingreso = ".$this->ingreso."
                     AND evolucion_id = ".$this->evolucion."
                     AND sw_finalidad_dx = '$sw_dx_finalidad';";
			$resulta=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "NO HAY DIAGNOSTICOS DISPONIBLES";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
			else
			{
				$vector=$resulta->GetRowAssoc($ToUpper = false);
			}
   
			if ($_REQUEST['principal'.$pfj]=='1')
			{
				$sql2="UPDATE hc_sub_procedimientos_realizados_notas_diagnosticos
                    	  SET sw_principal='1' 
                           WHERE ingreso = ".$this->ingreso."
                     	  AND evolucion_id = ".$this->evolucion."
                           AND sw_finalidad_dx = '$sw_dx_finalidad'
                           AND diagnostico_id='".$vector['diagnostico_id']."';";
				$resulta=$dbconn->Execute($sql2);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al insertar en hc_diagnosticos_ingreso";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$this->RegistrarSubmodulo($this->GetVersion());
        return true;
			}
		}
		$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." FUE ELIMINADO SATISFACTORIAMENTE.";
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
	}


	function Eliminar_Procedimiento()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$SQL = "SELECT A.detalle_equivalencia_id, A.procedimiento_detalle_id, B.procedimiento_detalle_id
				FROM hc_sub_procedimientos_realizados_notas_detalle AS B
				JOIN hc_sub_procedimientos_realizados_notas_detalle_equivalencias AS A ON (B.procedimiento_detalle_id = A.procedimiento_detalle_id)
				WHERE B.ingreso = ".$this->ingreso."
				AND B.evolucion_id = ".$this->evolucion.";";

		$result=$dbconn->Execute($SQL);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL REGISTRO";
			$dbconn->RollbackTrans();
			return false;
		}

		$detalle_equivalencia_id = $result->GetRows();

		if (!empty ($detalle_equivalencia_id ))
		{
			foreach($detalle_equivalencia_id as $k => $v)
			{
				$sql2 .= "DELETE FROM hc_sub_procedimientos_realizados_notas_detalle_equivalencias
						  WHERE detalle_equivalencia_id = ".$v[0].";";
			}
			$result=$dbconn->Execute($sql2);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL REGISTRO";
				$dbconn->RollbackTrans();
				return false;
			}
		}

		$query ='';

		$query = "DELETE FROM hc_sub_procedimientos_realizados_notas_diagnosticos
				  WHERE ingreso = ".$this->ingreso."
				  AND evolucion_id = ".$this->evolucion.";";

		$query .= "DELETE FROM hc_sub_procedimientos_realizados_notas_detalle
				   WHERE ingreso = ".$this->ingreso."
				   AND evolucion_id = ".$this->evolucion.";";

		$query .= "DELETE FROM hc_sub_procedimientos_realizados_notas
				   WHERE ingreso = ".$this->ingreso."
				   AND evolucion_id = ".$this->evolucion.";";

		$result=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL REGISTRO";
			$dbconn->RollbackTrans();
			return false;
		}

		$dbconn->CommitTrans();
		$this->RegistrarSubmodulo($this->GetVersion());
    $this->frmError["MensajeError"]="SOLICITUD ELIMINADA SATISFACTORIAMENTE.";
		return true;
	}


	function Eliminar_Procedimiento_Individual($procedimiento_detalle_id)
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();

		$SQL = "SELECT Count(*) FROM hc_sub_procedimientos_realizados_notas_detalle
					 WHERE ingreso = ".$this->ingreso."
					 AND evolucion_id = ".$this->evolucion.";";

		$result=$dbconn->Execute($SQL);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL REGISTRO";
			$dbconn->RollbackTrans();
			return false;
		}
		else
		{
			list ($count) = $result->FetchRow();
			if ($count > 1)
			{
				$query = "DELETE FROM hc_sub_procedimientos_realizados_notas_detalle_equivalencias
						WHERE procedimiento_detalle_id = $procedimiento_detalle_id;";

				$query.="DELETE FROM hc_sub_procedimientos_realizados_notas_detalle
						WHERE procedimiento_detalle_id = $procedimiento_detalle_id;";
				$_SESSION['BASIC'] = 0;
			}
			else
			{
				$query = "DELETE FROM hc_sub_procedimientos_realizados_notas_diagnosticos
						  WHERE ingreso = ".$this->ingreso."
						  AND evolucion_id = ".$this->evolucion.";";

				$query .= "DELETE FROM hc_sub_procedimientos_realizados_notas_detalle_equivalencias
						   WHERE procedimiento_detalle_id = $procedimiento_detalle_id;";

				$query .= "DELETE FROM hc_sub_procedimientos_realizados_notas_detalle
						   WHERE ingreso = ".$this->ingreso."
						   AND evolucion_id = ".$this->evolucion.";";

				$query .= "DELETE FROM hc_sub_procedimientos_realizados_notas
						   WHERE ingreso = ".$this->ingreso."
						   AND evolucion_id = ".$this->evolucion.";";
				$_SESSION['BASIC'] = 1;
			}

			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL REGISTRO";
				$dbconn->RollbackTrans();
				return false;
			}
		}
		$dbconn->CommitTrans();
		$this->RegistrarSubmodulo($this->GetVersion());
    return true;
	}
     
     //cor - clzc - spqx
	function TipoFinalidad(){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT * FROM qx_finalidades_procedimientos";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla qx_finalidades_procedimientos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  while(!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		$result->Close();
		return $vector;
	}
     
     //cor - clzc - spqx
	function TipoSalas_QX(){

		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query= "SELECT descripcion,tipo_sala_id 
		FROM qx_quirofanos 
		WHERE sw_programacion='0' AND departamento='".$this->departamento."'";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla qx_finalidades_procedimientos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  while(!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		$result->Close();
		return $vector;
	}


}
?>
