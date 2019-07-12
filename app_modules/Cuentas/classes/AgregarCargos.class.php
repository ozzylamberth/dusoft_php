<?php
  /******************************************************************************
  * $Id: AgregarCargos.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.7 $ 
	* 
	* @autor
  ********************************************************************************/

	IncludeClass('AgregarCargosHTML','','app','Cuentas');
	class AgregarCargos
	{
		function AgregarCargos(){}
		/**********************************************************************************
		* 
		* 
		* @return array 
		***********************************************************************************/
			/**
			* Busca el departamento y su descripcion en la tabla departamentos.
			* @access public
			* @return array
			*/
			function Departamentos($EmpresaId,$CentroU)
			{
					if($CentroU)
					{ $CU="and centro_utilidad='$CentroU'"; }
	
					list($dbconn) = GetDBconn();
					$query = "SELECT a.departamento,a.descripcion
											FROM departamentos as a, servicios as b WHERE a.empresa_id='$EmpresaId' $CU
											and a.servicio=b.servicio and b.sw_asistencial=1";
                      
					$result = $dbconn->Execute($query);
	
						if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						else{
							if($result->EOF){
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "La tabla maestra 'departamentos' esta vacia ";
								return false;
							}
								while (!$result->EOF) {
									$vars[]=$result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
								}
						}
				$result->Close();
				return $vars;
			}
      
    /*  
    *      Funcion que busca la ubicacion del paciente, si el paciente posee un ingreso pendiente en alguna estacion de enfermeria, busca la última fecha de egreso de su anterior estación.
    *      String $cuenta Cuenta del paciente.
    *      return array Con los datos de la última fecha de egreso de la estación de enfermería en la que se encontraba. 
    */  
    function BuscarFechaEgresoMaxEstacionPaciente($cuenta)
    {        
      list($dbconn) = GetDBconn();
      
      $sql  = " SELECT 	    eeip.estacion_origen,
                                    ee.sw_estacion_cirugia,
                                    ee.descripcion as descripcion_estacion_origen,
                                    ee2.descripcion as descripcion_estacion_destino
                    FROM        estaciones_enfermeria_ingresos_pendientes as eeip
                                    LEFT JOIN estaciones_enfermeria as ee on (eeip.estacion_origen = ee.estacion_id),                                 
                                    estaciones_enfermeria as ee2
                    WHERE      eeip.numerodecuenta = ".$cuenta." AND
                                    eeip.estacion_id = ee2.estacion_id  ; ";
            
      $result = $dbconn->Execute($sql);
      
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Consultar en estaciones_enfermeria_ingresos_pendientes";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      else
      {
        while (!$result->EOF)
        {
          $pacienteEEIP[]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
        }
      }
      $result->Close();
      ////----
      
      if(empty($pacienteEEIP))
      {
        $sql  = " SELECT 	    eeqpi.estacion_origen,
                                      ee.sw_estacion_cirugia,
                                      ee.descripcion as descripcion_estacion_origen,
                                      ee2.descripcion as descripcion_estacion_destino
                      FROM       estacion_enfermeria_qx_pendientes_ingresar as eeqpi
                                     LEFT JOIN estaciones_enfermeria as ee on (eeqpi.estacion_origen = ee.estacion_id),
                                     departamentos as dp,
                                     estaciones_enfermeria as ee2
                      WHERE     eeqpi.numerodecuenta = ".$cuenta." AND
                                     eeqpi.departamento = dp.departamento AND
                                     dp.departamento = ee2.departamento ; ";
          
        $result = $dbconn->Execute($sql);
            
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Consultar en estaciones_enfermeria_ingresos_pendientes";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        else
        {
          while (!$result->EOF)
          {
            $pacienteEEIP[]=$result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
          }
        }
        $result->Close();
      }
      
      if(!empty($pacienteEEIP))
      {
        if($pacienteEEIP[0]['sw_estacion_cirugia']==1 && !empty($pacienteEEIP[0]['estacion_origen']))
        {
          $sql  = " SELECT      eeqpi.numero_registro, 
                                        '".$pacienteEEIP[0]['estacion_origen']."' as estacion_origen,
                                        '".$pacienteEEIP[0]['descripcion_estacion_destino']."' as descripcion_estacion_destino,
                                        eeqpi.fecha_ingreso,
                                        eeqpi.fecha_egreso
                       FROM        estacion_enfermeria_qx_pacientes_ingresados as eeqpi,
                                        (SELECT		 max(	numero_registro) as 	numero_registro                                                   
                                        FROM         estacion_enfermeria_qx_pacientes_ingresados
                                        WHERE       numerodecuenta = ".$cuenta.")AS eeqpi2 
                       WHERE      eeqpi.numerodecuenta = ".$cuenta." AND
                                        eeqpi.numero_registro = eeqpi2.numero_registro ; ";
                                                  
          $result = $dbconn->Execute($sql);
          
          if ($dbconn->ErrorNo() != 0) 
          {
            $this->error = "Error al Consultar en estaciones_enfermeria_ingresos_pendientes";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          else
          {
            while (!$result->EOF)
            {
              $pacienteEEQPI[]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
            }
          }
          $result->Close();
          return $pacienteEEQPI;
        }
        if($pacienteEEIP[0]['sw_estacion_cirugia']!=1 && !empty($pacienteEEIP[0]['estacion_origen']))
        {
          $sql  = " SELECT      mh2.movimiento_id, 
                                        '".$pacienteEEIP[0]['estacion_origen']."' as estacion_origen,
                                        '".$pacienteEEIP[0]['descripcion_estacion_destino']."' as descripcion_estacion_destino,
                                        mh.fecha_ingreso,
                                        mh.fecha_egreso
                       FROM        movimientos_habitacion as mh,
                                        (SELECT		    max(movimiento_id) as movimiento_id                                                   
                                        FROM         movimientos_habitacion
                                        WHERE       numerodecuenta = ".$cuenta.")AS mh2 
                       WHERE      mh.numerodecuenta = ".$cuenta." AND
                                        mh.movimiento_id = mh2.movimiento_id ; ";
                                                
          $result = $dbconn->Execute($sql);
          
          if ($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Consultar en estaciones_enfermeria_ingresos_pendientes";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          else
          {
            while (!$result->EOF)
            {
              $pacienteMH[]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
            }
          }
          $result->Close();
          return $pacienteMH;    
        }  
      }
      return $pacienteEEIP;  
    }      
      
      function FechaIngresoEgreso($ingreso)
      {
        list($dbconn) = GetDBconn();
        $query = "SELECT        i.fecha_ingreso,
                                          in_sa.fecha_registro
                        FROM          ingresos as i  LEFT JOIN 
                                          (     SELECT  ings.fecha_registro,
                                                             ings.ingreso
                                                FROM     ingresos_salidas as ings
                                                WHERE   ings.ingreso=".$ingreso.") as in_sa ON  (i.ingreso=in_sa.ingreso) 
                        WHERE        i.ingreso = ".$ingreso." ;";
                 
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
        }
               
        while(!$result->EOF)
        {
                $vars[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
        }
           
        $result->Close();
        return $vars;      
      }

			/**
			CONSULTA PROFESIONALES
			
			**/
			function Profesionales()
			{
									list($dbconn) = GetDBconn();
									$query = "SELECT tipo_id_tercero,tercero_id,nombre 
														FROM profesionales
														WHERE tipo_profesional in('1','2','11','12','13') 
														ORDER BY nombre";
									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error al Cargar el Modulo";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													echo $this->mensajeDeError;
													return false;
									}

									while(!$result->EOF)
									{
													$vars[]=$result->GetRowAssoc($ToUpper = false);;
													$result->MoveNext();
									}

									$result->Close();
									return $vars;
			}

			/**
			*BUSCAR USUARIOS
			*/
			function BuscarUsuarios($PlanId)
			{
					list($dbconn) = GetDBconn();
					$query = " SELECT b.nombre, b.usuario_id
											FROM planes_auditores_int as a, system_usuarios as b
											WHERE a.plan_id='$PlanId' and a.usuario_id=".UserGetUID()."
											and a.usuario_id=b.usuario_id";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					while(!$result->EOF)
					{
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
					$result->Close();
					return $var;
			}
	
			/**
			* Busca los datos de los cargos que se acaban de agregar.
			* @access public
			* @return array
			* @param int numero de la cuenta
			*/
			function DatosTmpCuentas($Cuenta)
			{
						$Usuario=UserGetUID();
						list($dbconn) = GetDBconn();
						$query="SELECT a.*, b.tipo_tercero_id, b.tercero_id
																		FROM tmp_cuentas_detalle as a
																		left join tmp_cuentas_detalle_profesionales as b on(a.transaccion=b.transaccion)
																		WHERE a.numerodecuenta=$Cuenta AND a.usuario_id=$Usuario";
						$result=$dbconn->Execute($query);
	
						if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						while(!$result->EOF)
						{
								$var[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
						}
				$result->Close();
				return $var;
			}
			/**
			* Inserta los cargos en cuenta_detalles y ayudas_diagnosticas
			* @ access public
			* @ return boolean
			*/
			function GuardarTodosCargos($EmpresaId,$CUtilidad,$PlanId,$Cuenta)
			{
					//IncludeLib('funciones_facturacion');
					//echo '<br>E->'.$EmpresaId.'<br>CU->'.$CUtilidad.'<br>CTA->'.$Cuenta.'<br>Plan->'.$PlanId;

          $Datos=$this->DatosTmpCuentas($Cuenta);
					list($dbconn) = GetDBconn();
          
					for($i=0; $i<sizeof($Datos); $i++)
					{
						$arreglo[]=array('fecha_cargo'=>$Datos[$i][fecha_cargo],'cargo'=>$Datos[$i][cargo],'tarifario'=>$Datos[$i][tarifario_id],'servicio'=>$Datos[$i][servicio_cargo],'aut_int'=>$Datos[$i][autorizacion_int],'aut_ext'=>$Datos[$i][autorizacion_ext],'tipo_tercero'=>$Datos[$i][tipo_tercero_id],'tercero'=>$Datos[$i][tercero_id],'cups'=>$Datos[$i][cargo_cups],'cantidad'=>$Datos[$i][cantidad],'departamento'=>$Datos[$i][departamento],'sw_cargue'=>$Datos[$i][sw_cargue]);
					}
	
					$sql =" DELETE FROM tmp_cuentas_detalle WHERE numerodecuenta=".$_REQUEST['Cuenta']."";
					//$insertar = InsertarCuentasDetalle($_SESSION['CUENTAS']['EMPRESA'],$_SESSION['CUENTAS']['CENTROUTILIDAD'],$_REQUEST['Cuenta'],$_REQUEST['PlanId'],$arreglo,$sql);
					  
          $insertar = InsertarCuentasDetalle($EmpresaId,$CUtilidad,$Cuenta,$PlanId,$arreglo,$sql);
					  
          if(!empty($insertar))
					{
									//$Nombres=$this->BuscarNombresPaciente($_REQUEST['TipoId'],$_REQUEST['PacienteId']);
									//$Apellidos=$this->BuscarApellidosPaciente($_REQUEST['TipoId'],$_REQUEST['PacienteId']);
									$mensaje='TODOS LOS CARGOS SE GUARDARON EN LA CUENTA No. '.$_REQUEST['Cuenta'].' '.$Nombres.' '.$Apellidos;
					}
					else
					{  $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";  }

					//$accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$_REQUEST['Cuenta'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Pieza'=>$_REQUEST['Pieza'],'Cama'=>$_REQUEST['Cama'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso']));
					$accion=SessionGetvar('AccionVolverCargos');
					$fact = new AgregarCargosHTML();
					$html = $fact->FormaMensaje($mensaje,'AGREGAR TODOS LOS CARGOS',$accion,$boton);
					return $html;
			}

			/**
			INSERTAR CARGOS TMP
			**/
			function InsertarCargosTmp(&$obj,$EmpresaId,$CUtilidad,$PlanId,$Cuenta)
			{
				if(empty($EmpresaId) AND empty($CUtilidad))
				{
					$EmpresaId = SessionGetVar('Empresa');
					$CUtilidad = SessionGetVar('Cutilidad');
					$Cuenta = SessionGetVar('Cuenta');
					$PlanId = SessionGetVar('Plan');
				}
        
        if(!$CUtilidad)
        {
          $CUtilidad = $_REQUEST['CUtilidad'];
          SessionSetVar('Cutilidad',$_REQUEST['CUtilidad']);
        }
				// unset($_SESSION['CUENTAS']['ADD_CARGOS']);exit;
				IncludeLib("tarifario_cargos");
				IncludeLib("funciones_facturacion");
				if (IncludeClass("rips"))
				{		
						$j = 0;
						foreach ($_SESSION['CUENTAS']['ADD_CARGOS'] AS $i => $v)
						{
								//$Departamento=$_REQUEST['Departamento'];
								$Departamento=$v[departamento];
								$Precio=$_REQUEST['Precio'];
								//$CargoCups=$_REQUEST['Cargo'];
								$CargoCups=$v[codigo];
								$_REQUEST['Descripcion']=$v[descripcion];
								$_REQUEST['MedInt']=$v[profesional];
								//$Cantidad=$_REQUEST['Cantidad'];
								$Cantidad=$v[cantidad];
								$Cuenta=$v[Cuenta];
								$Nivel=$_REQUEST['Nivel'];
								$PlanId=$v[PlanId];
								$Ingreso=$_REQUEST['Ingreso'];
								$Fecha=$_REQUEST['Fecha'];
								$TipoId=$_REQUEST['TipoId'];
								$PacienteId=$_REQUEST['PacienteId'];
								//$FechaCargo=$_REQUEST['FechaCargo'];
								$FechaCargo=$v[fecha_cargo];
								$f=explode('/',$FechaCargo);
								$FechaCargo=$f[2].'-'.$f[1].'-'.$f[0];
								$SystemId=UserGetUID();

								$var[1]=$Departamento;
								$var[2]=$TarifarioId;
								$var[3]=$CargoCups;
								$var[4]=$Cantidad;
								$var[5]=$Precio;
								$var[6]=$Gravamen;
								$var[9]=$GrupoTarifario;
								$var[10]=$SubGrupoTarifario;
								$var[11]=$FechaCargo;
		
								if(!$Cantidad || !$CargoCups || $FechaCargo === '--' || !$FechaCargo){
												if(!$Cantidad){ $this->frmError["Cantidad"]=1; }
												if(!$CargoCups){ $this->frmError["Cargo"]=1; }
												if(!$FechaCargo){ $this->frmError["FechaCargo"]=1; }
										$mensaje='Faltan datos obligatorios.';
										//if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'Modi',$Cobertura)){
												//return false;
										//}
										$titulo = '';
										$accion = SessionGetVar("AccionVolverCargos");
										echo $mensaje;
										$forma = new AgregarCargosHTML();
										$html = $forma->FormaMensaje($mensaje,$titulo,$accion,$boton);
										return $html;
								}
								if(!$Departamento || $Departamento==-1){
										$mensaje='Seleccione el Departamento.';
										//if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'Modi',$Cobertura)){
												//return false;
										//}
												//echo $mensaje;
										$titulo = '';
										$accion = SessionGetVar("AccionVolverCargos");
										echo $mensaje;
										$forma = new AgregarCargosHTML();
										$html = $forma->FormaMensaje($mensaje,$titulo,$accion,$boton);
										return $html;
								}
		
								list($dbconn) = GetDBconn();
								$query ="SELECT b.servicio FROM departamentos as b
																WHERE b.departamento='$Departamento'";
								$results = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
								}
								$Servicio=$results->fields[0];
		
								$f = (int) $Cantidad;
								$y = $Cantidad - $f;

								if($y != 0)
								{
									if($y != 0){ $this->frmError["Cantidad"]=1; }
									$mensaje='La Cantidad debe ser entera.';
									$titulo = '';
									$accion = SessionGetVar("AccionVolverCargos");
									echo $mensaje;
									$forma = new AgregarCargosHTML();
									$html = $forma->FormaMensaje($mensaje,$titulo,$accion,$boton);
									return $html;
								}
				//----------esto es cuando digitan el codigo del cargo---------------
								if(empty($_REQUEST['Descripcion']))
								{
									$key1="cargo";
									$filtro = "( lower ($key1) like '%$CargoCups' or lower ($key1) like '%$CargoCups%' or lower ($key1) like '$CargoCups%')";
									$campos_select = "a.descripcion, a.cargo ";
		
									$resulta = BuscardoCargosCups($filtro, $campos_select,'','');
									//$resulta = BuscardoCargosCups($PlanId, '', $filtro, $campos_select, $fetch_mode_assoc=false, '','');
									if($resulta->RecordCount() > 1)
									{
											//if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'',$Cobertura)){
													//return false;
											//}
											$mensaje='Existen dos cargos con el mismo Codígo, Porfavor Busque el Cargo.';
											$titulo = '';
											$accion = SessionGetVar("AccionVolverCargos");
											echo $mensaje;
											$forma = new AgregarCargosHTML();
											$html = $forma->FormaMensaje($mensaje,$titulo,$accion,$boton);
											return $html;
									}
									elseif($resulta->RecordCount() == 0)
									{
											$mensaje='El Cargo No Existe, No tiene Equivalencias o No esta Contratado.';
											//if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'',$Cobertura)){
													//return false;
											//}
											$titulo = '';
											$accion = SessionGetVar("AccionVolverCargos");
											echo $mensaje;
											$forma = new AgregarCargosHTML();
											$html = $forma->FormaMensaje($mensaje,$titulo,$accion,$boton);
											return $html;
									}
								}
							//-----------------VALIDAR LAS EQUIVALENCIAS DEL CARGO CUPS --------------------
								//traer solo equivalencias contratadas
								$equi='';
								$equi=ValdiarEquivalencias($PlanId,$CargoCups);

								if(sizeof($equi)==1)
								{
												$TarifarioId=$equi[0][tarifario_id];
												$Cargo=$equi[0][cargo];
												$GrupoTarifario=$equi[0][grupo_tarifario_id];
												$SubGrupoTarifario=$equi[0][subgrupo_tarifario_id];
								}
								elseif(sizeof($equi) > 1)
								{
										//tiene varias equivalencias
										$fact = new AgregarCargosHTML();
                                //FormaVariasEquivalencias($Departamento,$Servicio,$CargoCups,$nombre,$equi,$TipoId,$PacienteId,$Cuenta,$Nivel,$PlanId,$Fecha,$Ingreso,$cantidad,$FechaCargo,$profesional)
										$html = $fact->FormaVariasEquivalencias($Departamento,$Servicio,$CargoCups,$_REQUEST['Descripcion'],$equi,$TipoId,$PacienteId,$Cuenta,$Nivel,$PlanId,$Fecha,$Ingreso,$Cantidad,$FechaCargo,$_REQUEST['MedInt'],$EmpresaId,$CUtilidad);
										return $html;
								}
								else
								{
										//if(!$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,'',$Cobertura)){
														//return false;
										//}
										$mensaje = 'EL CARGO NO TIENE EQUIVALENCIAS O NO ESTA CONTRATADO PARA ESTE PLAN.';
										$titulo = '';
										$accion = SessionGetVar("AccionVolverCargos");
										echo $mensaje;
										$forma = new AgregarCargosHTML();
										$html = $forma->FormaMensaje($mensaje,$titulo,$accion,$boton);
										return $html;
								}
								list($dbconn) = GetDBconn();
								$AutoInt=1;
								$AutoExt='NULL';
								//--------------------valida si no necesita autorizacion-------------------------
								$msg='';
								$query = "SELECT autorizacion_cargo_cups_int($PlanId,'$CargoCups','$Servicio')";
										//$query = "select autorizacion_cobertura('$PlanId','$TarifarioId','$Cargo','$Servicio')";
								$result = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										echo $query.'  '.$this->mensajeDeError;
										return false;
								}
								if($result->fields[0]!='NoRequiere')
								{
												$msg .='<BR>EL CARGO NECESITA AUTORIZACION INTERNA';
												$autoInt=1;
												$AutoInt=0;
								}
								$query = "SELECT autorizacion_cargo_cups_ext($PlanId,'$CargoCups','$Servicio')";
								$result = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0)
								{
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										echo $query.'  '.$this->mensajeDeError;
										return false;
								}
								if($result->fields[0]!='NoRequiere')
								{
										$msg .='<BR>EL CARGO NECESITA AUTORIZACION EXTERNA';
										$autoExt=1;
										$AutoExt=0;
								}
																//------------------fin validacion de autorizacion--------------------
								//no tiene ninguna y necesita autorizacion
								$usu=$this->BuscarUsuarios($PlanId);
								if(($autoExt==1 OR $autoInt==1) AND !empty($usu))
								{
										$auto[]=array('tarifario'=>$TarifarioId,'cargo'=>$Cargo,'cantidad'=>$Cantidad,'descripcion'=>$_REQUEST['Descripcion'],'cups'=>$CargoCups);
								echo 'msg->'.$msg; exit;
/*											unset($_SESSION['SOLICITUDAUTORIZACION']);
											unset($_SESSION['AUTORIZACIONES']);
																									$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']['CARGOS'][]=array('tarifario'=>$TarifarioId,'cargo'=>$Cargo,'cantidad'=>$Cantidad,'descripcion'=>$_REQUEST['Descripcion'],'cups'=>$CargoCups);
											$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$TipoId;
											$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$PacienteId;
											$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']=$Cargo;
											$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO']=$TarifarioId;
											$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$PlanId;
											$_SESSION['AUTORIZACIONES']['AUTORIZAR']['cantidad']=$Cantidad;
											$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']['CARGOS']=$auto;
											$_SESSION['FACTURACION']['CARGO']=$Cargo;
											$_SESSION['FACTURACION']['CUPS']=$CargoCups;
											$_SESSION['FACTURACION']['CANTIDAD']=$Cantidad;
											$_SESSION['FACTURACION']['TARIFARIO']=$TarifarioId;
											$_SESSION['FACTURACION']['CargoD']=$CargoD;
											$_SESSION['FACTURACION']['Apoyo']=$Apoyo;
											$_SESSION['FACTURACION']['SW']=$SW;
											$_SESSION['FACTURACION']['LIQ']=$Liq;
											$_SESSION['FACTURACION']['DEPTO']=$Departamento;
											$_SESSION['FACTURACION']['SERVICIO']=$Servicio;
											$_SESSION['FACTURACION']['FECHACARGO']=$FechaCargo;
		
											$mensaje='El Cargo: '.$_REQUEST['Descripcion'].' Necesita Autorización para ser Cargado.'.$msg;
											$arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
											$c='app';
											$m='Facturacion';
											$me='AutorizarCargos';
											$me2='Cargos';
											$Titulo='AUTORIZAR CARGO';
											$boton1='ACEPTAR';
											$boton2='CANCELAR';
											$this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
											return true;*/
									}
		
									//PROFESIONAL
									if(!empty($_REQUEST['MedInt']))
									{
											$p=explode('||',$_REQUEST['MedInt']);
									}
									//Paso todas las validaciones
									//pide datos adicionales necesrios para el rips
									$arreglo1[]=array('fecha_cargo'=>$FechaCargo,'cargo'=>$Cargo,'tarifario'=>$TarifarioId,'servicio'=>$Servicio,'aut_int'=>$autoInt,'aut_ext'=>$autoExt,'tipo_tercero'=>$p[0],'tercero'=>$p[1],'cups'=>$CargoCups,'cantidad'=>$Cantidad,'departamento'=>$Departamento,'sw_cargue'=>3);
									//unset($_SESSION['TMP_DATOS']);
									//$_SESSION['TMP_DATOS']['sw_pide_otro_frm']=0;
									$DatosAdicionalesRips = new AgregarCargosHTML();
									$adicionalesRips = $DatosAdicionalesRips->PideDatosAdicionalesRips(&$obj,$CUtilidad,$CargoCups,$equi[0][tarifario_id],$EmpresaId,$arreglo1,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
			
//*****************
//echo $adicionalesRips.'<br>';
/*	if($adicionalesRips === 'sin_tipo_rips')
		{

			$arreglo[]=array('fecha_cargo'=>$FechaCargo,'cargo'=>$Cargo,'tarifario'=>$TarifarioId,'servicio'=>$Servicio,'aut_int'=>$autoInt,'aut_ext'=>$autoExt,'tipo_tercero'=>$p[0],'tercero'=>$p[1],'cups'=>$CargoCups,'cantidad'=>$Cantidad,'departamento'=>$Departamento,'sw_cargue'=>3);
			$insertar = InsertarTmpCuentasDetalle($EmpresaId,$CUtilidad,$Cuenta,$PlanId,$arreglo);
			//if(!empty($insertar))
			//	{//ojo falta implementar el ciclo para > 1 cargo
			//	//sin rips
			//					$mensaje="EL CARGO FUE GRABADO.";
			//					$fact = new AgregarCargos();
			//					$html = $this->GuardarTodosCargos($EmpresaId,$CUtilidad,$PlanId,$Cuenta);
			//	}
			//	else
			//	{
			//					$mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";
			//					$html = $DatosAdicionalesRips->FormaAgregarCargos(&$obj,$EmpresaId,$CUtilidad,$PlanId,$Cuenta,$mensaje);
			//	}
			if(empty($insertar))
			{echo  $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR."."[".get_class($this)."][".__LINE__."]";exit;
			// $html = $DatosAdicionalesRips->FormaAgregarCargos(&$obj,$EmpresaId,$CUtilidad,$PlanId,$Cuenta,$mensaje);                 }else{                                        $Datos=$this->DatosTmpCuentas($Cuenta);//                                      list($dbconn) = GetDBconn();                                        for($i=0; $i<sizeof($Datos); $i++)                                        {                                                $array[]=array('fecha_cargo'=>$Datos[$i][fecha_cargo],'cargo'=>$Datos[$i][cargo],'tarifario'=>$Datos[$i][tarifario_id],'servicio'=>$Datos[$i][servicio_cargo],'aut_int'=>$Datos[$i][autorizacion_int],'aut_ext'=>$Datos[$i][autorizacion_ext],'tipo_tercero'=>$Datos[$i][tipo_tercero_id],'tercero'=>$Datos[$i][tercero_id],'cups'=>$Datos[$i][cargo_cups],'cantidad'=>$Datos[$i][cantidad],'departamento'=>$Datos[$i][departamento],'sw_cargue'=>$Datos[$i][sw_cargue]);                                        }                                        $sql =" DELETE FROM tmp_cuentas_detalle WHERE numerodecuenta=".$_REQUEST['Cuenta']."";                                        //$insertar = InsertarCuentasDetalle($_SESSION['CUENTAS']['EMPRESA'],$_SESSION['CUENTAS']['CENTROUTILIDAD'],$_REQUEST['Cuenta'],$_REQUEST['PlanId'],$arreglo,$sql);                                        $insertar = InsertarCuentasDetalle($EmpresaId,$CUtilidad,$Cuenta,$PlanId,$array,$sql);                                        if(!empty($insertar))                                        {                                                                        //$Nombres=$this->BuscarNombresPaciente($_REQUEST['TipoId'],$_REQUEST['PacienteId']);                                                                        //$Apellidos=$this->BuscarApellidosPaciente($_REQUEST['TipoId'],$_REQUEST['PacienteId']);
				$mensaje='TODOS LOS CARGOS SE GUARDARON EN LA CUENTA No. '.$_REQUEST['Cuenta'].' '.$Nombres.' '.$Apellidos;
			}
			else
			{  $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";  }
				UNSET($array);
				UNSET($arreglo);
			// echo $mensaje.$i.'<br>';
			$j=$j+1;
			//echo sizeof($_SESSION['CUENTAS']['ADD_CARGOS']);
			if($j>=sizeof($_SESSION['CUENTAS']['ADD_CARGOS']))
			{
				$mensaje='TODOS LOS CARGOS SE GUARDARON EN LA CUENTA No. '.$_REQUEST['Cuenta'].' '.$Nombres.' '.$Apellidos;
				$accion=SessionGetvar('AccionVolverCargos');
				$fact = new AgregarCargosHTML();
				$html = $fact->FormaMensaje($mensaje,'AGREGAR TODOS LOS CARGOS',$accion,$boton);
				return $html;
			}
*/
//*****************
//echo $adicionalesRips.'<br>';
									if($adicionalesRips === 'sin_tipo_rips')
									{
											$arreglo[]=array('fecha_cargo'=>$FechaCargo,'cargo'=>$Cargo,'tarifario'=>$TarifarioId,'servicio'=>$Servicio,'aut_int'=>$autoInt,'aut_ext'=>$autoExt,'tipo_tercero'=>$p[0],'tercero'=>$p[1],'cups'=>$CargoCups,'cantidad'=>$Cantidad,'departamento'=>$Departamento,'sw_cargue'=>3);
											$insertar = InsertarTmpCuentasDetalle($EmpresaId,$CUtilidad,$Cuenta,$PlanId,$arreglo);
/*											if(!empty($insertar))
											{//ojo falta implementar el ciclo para > 1 cargo
											//sin rips
												$mensaje="EL CARGO FUE GRABADO.";
												$fact = new AgregarCargos();
												$html = $this->GuardarTodosCargos($EmpresaId,$CUtilidad,$PlanId,$Cuenta);
											}
											else
											{
												$mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";
												$html = $DatosAdicionalesRips->FormaAgregarCargos(&$obj,$EmpresaId,$CUtilidad,$PlanId,$Cuenta,$mensaje);
											}
											//$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
											//echo $mensaje;
											return $html;*/
											if(empty($insertar))
											{
												$mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR."."[".get_class($this)."][".__LINE__."]";exit;
												//$html = $DatosAdicionalesRips->FormaAgregarCargos(&$obj,$EmpresaId,$CUtilidad,$PlanId,$Cuenta,$mensaje);
											}
											else
											{
												$Datos=$this->DatosTmpCuentas($Cuenta);
												for($i=0; $i<sizeof($Datos); $i++)
												{
													$array[]=array('fecha_cargo'=>$Datos[$i][fecha_cargo],'cargo'=>$Datos[$i][cargo],'tarifario'=>$Datos[$i][tarifario_id],'servicio'=>$Datos[$i][servicio_cargo],'aut_int'=>$Datos[$i][autorizacion_int],'aut_ext'=>$Datos[$i][autorizacion_ext],'tipo_tercero'=>$Datos[$i][tipo_tercero_id],'tercero'=>$Datos[$i][tercero_id],'cups'=>$Datos[$i][cargo_cups],'cantidad'=>$Datos[$i][cantidad],'departamento'=>$Datos[$i][departamento],'sw_cargue'=>$Datos[$i][sw_cargue]);
												}
								
												$sql =" DELETE FROM tmp_cuentas_detalle WHERE numerodecuenta=".$_REQUEST['Cuenta']."";
												//$insertar = InsertarCuentasDetalle($_SESSION['CUENTAS']['EMPRESA'],$_SESSION['CUENTAS']['CENTROUTILIDAD'],$_REQUEST['Cuenta'],$_REQUEST['PlanId'],$arreglo,$sql);
												$insertar = InsertarCuentasDetalle($EmpresaId,$CUtilidad,$Cuenta,$PlanId,$array,$sql);
                        if(!empty($insertar))
												{
																//$Nombres=$this->BuscarNombresPaciente($_REQUEST['TipoId'],$_REQUEST['PacienteId']);
																//$Apellidos=$this->BuscarApellidosPaciente($_REQUEST['TipoId'],$_REQUEST['PacienteId']);
																$mensaje='TODOS LOS CARGOS SE GUARDARON EN LA CUENTA No. '.$_REQUEST['Cuenta'].' '.$Nombres.' '.$Apellidos;
												}
												else
												{  $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";  }
												UNSET($array);
												UNSET($arreglo);
												// echo $mensaje.$i.'<br>';
												$j=$j+1;
												//echo sizeof($_SESSION['CUENTAS']['ADD_CARGOS']);
												if($j>=sizeof($_SESSION['CUENTAS']['ADD_CARGOS']))
												{
													$mensaje='TODOS LOS CARGOS SE GUARDARON EN LA CUENTA No. '.$_REQUEST['Cuenta'].' '.$Nombres.' '.$Apellidos;
													$accion=SessionGetvar('AccionVolverCargos');
													$fact = new AgregarCargosHTML();
													$html = $fact->FormaMensaje($mensaje,'AGREGAR TODOS LOS CARGOS',$accion,$boton);
													return $html;
												}
											}
									}
									else
									if(!empty($adicionalesRips))
									{
											//$arreglo[]=array('fecha_cargo'=>$FechaCargo,'cargo'=>$Cargo,'tarifario'=>$TarifarioId,'servicio'=>$Servicio,'aut_int'=>$autoInt,'aut_ext'=>$autoExt,'tipo_tercero'=>$p[0],'tercero'=>$p[1],'cups'=>$CargoCups,'cantidad'=>$Cantidad,'departamento'=>$Departamento,'sw_cargue'=>3);
											//$insertar = InsertarTmpCuentasDetalle($_SESSION['CUENTAS']['EMPRESA'],$_SESSION['CUENTAS']['CENTROUTILIDAD'],$Cuenta,$PlanId,$arreglo);
											//if(!empty($insertar))
											//{
											//  $mensaje="EL CARGO FUE GRABADO.";
											//}
											//else
											//{  $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";  }
											//$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
											return $adicionalesRips;
									}
									else
									{
											$mensaje="ERROR: DATOS NECESARIOS PARA RIPS NO VALIDOS.";
											$forma = new AgregarCargosHTML();
											$html = $forma->FormaAgregarCargos(&$obj,$EmpresaId,$CUtilidad,$PlanId,$Cuenta,$mensaje);
											//$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
											//echo $mensaje;
											return $html;
									}
						}//FIN FOR
				}
				else
				{
					$html = "No se pudo cargar la clase RIPS";
				}
				return $html;
			}
		/**********************************************************************************
		*
		***********************************************************************************/
		function InsertarCargoTmpEquivalencias()
		{
				//cambio lorena porque se cae el programa cuando mandaban este vector por request
				$vector=$_SESSION['FACTURACION']['VECTOR_EQUIVALENCIAS'];
				//fin cambio
				unset($_SESSION['FACTURACION']['VECTOR_EQUIVALENCIAS']);
				IncludeLib("tarifario_cargos");
				IncludeLib("funciones_facturacion");
				$f=0;
				foreach($_REQUEST as $k => $v)
				{
						if(substr_count($k,'cargo') AND $f==0)
						{
								$f++;
								$var=explode('||',$v);
								$CargoCups=$var[3];
						}
				}

				if($f==0)
				{
						//$this->frmError["MensajeError"]="ERROR DATOS VACIOS: Debe elegir alguna Cargo Equivalente.";
						$msg = "ERROR DATOS VACIOS: Debe elegir alguna Cargo Equivalente.";
						$dat = new AgregarCargosHTML();
						$html = $dat->FormaVariasEquivalencias($_REQUEST['departamento'],$_REQUEST['servicio'],$_REQUEST['cups'],$_REQUEST['descripcion'],$vector,$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Cuenta'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['cantidad'],$_REQUEST['fechacargo'],$_REQUEST['profesional'],$_REQUEST['EmpresaId'],$_REQUEST['CUtilidad'],$msg);
						return $html;
				}
				$Cuenta=$_REQUEST['Cuenta'];
				$Servicio=$_REQUEST['servicio'];
				$PlanId=$_REQUEST['PlanId'];
				$Departamento=$_REQUEST['departamento'];
				$Cantidad=$_REQUEST['cantidad'];
				$TipoId=$_REQUEST['TipoId'];
				$PacienteId=$_REQUEST['PacienteId'];
				$Nivel=$_REQUEST['Nivel'];
				$Fecha=$_REQUEST['Fecha'];
				$Ingreso=$_REQUEST['Ingreso'];
				$FechaCargo=$_REQUEST['fechacar'];

				$usu=$this->BuscarUsuarios($PlanId);
				list($dbconn) = GetDBconn();
				$AutoInt=1;
				$AutoExt='NULL';
				//--------------------valida si no necesita autorizacion-------------------------
				$msg='';
				$query = "select autorizacion_cargo_cups_int($PlanId,'$CargoCups','$Servicio')";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if($result->fields[0]!='NoRequiere')
				{
								$msg .='<BR>EL CARGO NECESITA AUTORIZACION INTERNA';
								$autoInt=1;
								$AutoInt=0;
				}
				$query = "select autorizacion_cargo_cups_ext($PlanId,'$CargoCups','$Servicio')";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if($result->fields[0]!='NoRequiere')
				{
								$msg .='<BR>EL CARGO NECESITA AUTORIZACION EXTERNA';
								$autoExt=1;
								$AutoExt=0;
				}
				//------------------fin validacion de autorizacion--------------------

				//PROFESIONAL
				if(!empty($_REQUEST['profesional']))
				{       $p=explode('||',$_REQUEST['profesional']);   }

				$auto='';
				foreach($_REQUEST as $k => $v)
				{
							if(substr_count($k,'cargo'))
							{           //2descripcion  3cargo_cups
									$var=explode('||',$v);

									if(($autoExt==1 OR $autoInt==1) AND !empty($usu))
									{
													$auto[]=array('tarifario'=>$var[0],'cargo'=>$var[1],'cantidad'=>$Cantidad,'descripcion'=>$var[2],'cups'=>$var[3]);
									}
									else
									{
													$arreglo[]=array('fecha_cargo'=>$_REQUEST['fechacar'],'cargo'=>$var[1],'tarifario'=>$var[0],'servicio'=>$_REQUEST['servicio'],'aut_int'=>$autoInt,'aut_ext'=>$autoExt,'tipo_tercero'=>$p[0],'tercero'=>$p[1],'cups'=>$var[3],'cantidad'=>$Cantidad,'departamento'=>$_REQUEST['departamento'],'sw_cargue'=>3);
									}
							}
				}
					//MauroB
					//Paso todas las validaciones
					//pide datos adicionales necesrios para el rips
					unset($_SESSION['TMP_DATOS']);
					$_SESSION['TMP_DATOS']['sw_pide_otro_frm']=0;
					$dat = new AgregarCargosHTML();   
                               //PideDatosAdicionalesRips(&$obj,$CUtilidad,$cargos_cups,$tarifario_id,$EmpresaId,$arreglo,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)
					$adicionalesRips=$dat->PideDatosAdicionalesRips(&$dat,$_REQUEST['CUtilidad'],$CargoCups,$equi[0][tarifario_id],$_REQUEST['EmpresaId'],$arreglo,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
					//MauroB
				if(is_array($auto))
				{//hay cargos que necesitan autorizacion
		/*			unset($_SESSION['SOLICITUDAUTORIZACION']);
					unset($_SESSION['AUTORIZACIONES']);
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$TipoId;
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$PacienteId;
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGO']=$Cargo;
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TARIFARIO']=$TarifarioId;
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO']['CARGOS']=$auto;
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$PlanId;
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['cantidad']=$Cantidad;
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['servicio']=$Servicio;
					$_SESSION['FACTURACION']['tercero']=$p[1];
					$_SESSION['FACTURACION']['tipo_tercero']=$p[0];
					$_SESSION['FACTURACION']['CARGO']=$Cargo;
					$_SESSION['FACTURACION']['CANTIDAD']=$Cantidad;
					$_SESSION['FACTURACION']['TARIFARIO']=$TarifarioId;
					$_SESSION['FACTURACION']['CargoD']=$CargoD;
					$_SESSION['FACTURACION']['Apoyo']=$Apoyo;
					$_SESSION['FACTURACION']['SW']=$SW;
					$_SESSION['FACTURACION']['LIQ']=$Liq;
					$_SESSION['FACTURACION']['DEPTO']=$Departamento;
					$_SESSION['FACTURACION']['SERVICIO']=$Servicio;
					$_SESSION['FACTURACION']['FECHACARGO']=$FechaCargo;

					$mensaje='Se Necesita Autorización para los Cargos.'.$msg;
					$arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
					$c='app';
					$m='Facturacion';
					$me='AutorizarCargos';
					$me2='Cargos';
					$Titulo='AUTORIZAR CARGO';
					$boton1='ACEPTAR';
					$boton2='CANCELAR';
					$this->ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2);
					return true;*/
					$mensaje='Se Necesita Autorización para los Cargos.'.$msg;
					echo $mensaje;
					return $mensaje;
				}
				else
				{
						$mensaje=$msg;
						if($adicionalesRips === 'sin_tipo_rips')
						{
								//$arreglo[]=array('fecha_cargo'=>$_REQUEST['fechacar'],'cargo'=>$var[1],'tarifario'=>$var[0],'servicio'=>$_REQUEST['servicio'],'aut_int'=>$autoInt,'aut_ext'=>$autoExt,'tipo_tercero'=>$p[0],'tercero'=>$p[1],'cups'=>$var[3],'cantidad'=>$Cantidad,'departamento'=>$_REQUEST['departamento'],'sw_cargue'=>3);
						//$html = $dat->FormaVariasEquivalencias($_REQUEST['departamento'],$_REQUEST['servicio'],$_REQUEST['cups'],$_REQUEST['descripcion'],$vector,$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Cuenta'],$_REQUEST['Nivel'],$_REQUEST['PlanId'],$_REQUEST['Fecha'],$_REQUEST['Ingreso'],$_REQUEST['cantidad'],$_REQUEST['fechacargo'],$_REQUEST['profesional'],$_REQUEST['EmpresaId'],$_REQUEST['CUtilidad'],$msg);
								$insertar = InsertarTmpCuentasDetalle($_REQUEST['EmpresaId'],$_REQUEST['CUtilidad'],$_REQUEST['Cuenta'],$_REQUEST['PlanId'],$arreglo);
								if(!empty($insertar))
								{
									$mensaje="EL CARGO FUE GRABADO.";
									$fact = new AgregarCargos();
									$html = $fact->GuardarTodosCargos($_REQUEST['EmpresaId'],$_REQUEST[CU],$PlanId,$_REQUEST['Cuenta']);
								}
								else
								{  $mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR.";  }
								//$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura);
								$titulo = '';
								$accion = SessionGetVar("AccionVolverCargos");
								echo $mensaje;
								$forma = new AgregarCargosHTML();
								$html = $forma->FormaMensaje($mensaje,$titulo,$accion,$boton);
								//$html = $dat->FormaAgregarCargos(&$dat,'','','','',$mensaje);
								return $html;
						}
						else
						if($adicionalesRips)
						{
								return $adicionalesRips;
						}
						else
						{
								$msg="ERROR: DATOS NECESARIOS PARA RIPS NO VALIDOS.";
								//$this->FormaCargos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$msg,$D,$var='',$ValEmpresa,$Cobertura);
								//return true;
								$titulo = '';
								$accion = SessionGetVar("AccionVolverCargos");
								echo $mensaje;
								$forma = new AgregarCargosHTML();
								$html = $forma->FormaMensaje($msg,$titulo,$accion,$boton);
								//$html = $dat->FormaAgregarCargos(&$dat,'','','','',$msg);
								return $html;
						}
				}
		}    
    
		/**********************************************************************************
		*
		***********************************************************************************/
		function DatosEncabezadoEmpresa($emp,$ctu)
		{
			$sql  = "SELECT	  ";
			$sql .= "				c.descripcion, ";
			$sql .= "				e.razon_social ";
			$sql .= "FROM 	empresas e, ";
			$sql .= "				centros_utilidad c ";
			$sql .= "WHERE  e.empresa_id = '".$emp."'  ";
			$sql .= "AND 		c.centro_utilidad = '".$ctu."' ";
			$sql .= "AND		c.empresa_id = e.empresa_id ";
			list($dbconn) = GetDBconn();
			$rst = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					echo $this->mensajeDeError;
					return false;
			}
			
			$datos = array();

			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		/**
		* Trae los tipos de finalidad de una consulta
		*   @return Array
		*/
		function ConsultaTiposFinalidad()
		{
						list($dbconn) = GetDBconn();

			$query = "
						SELECT  tipo_finalidad_id,
														detalle
						FROM        hc_tipos_finalidad
						ORDER BY tipo_finalidad_id
				";
				global $ADODB_FETCH_MODE;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Consultar los tipos de finalidad";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						echo $this->mensajeDeError;
						return false;
				}
				while(!$result->EOF)
				{
						$dato = $result->FetchRow();
						$var[]= $dato;
				}

				$result->Close();
				return $var;
		}

		/**
		* Trae los tipos de Causas externas de una consulta
		*   @return Array
		*/
		function ConsultaCausaExterna()
		{
						list($dbconn) = GetDBconn();
			
			$query = "
						SELECT  causa_externa_id,
														descripcion
						FROM        causas_externas
						ORDER BY causa_externa_id
				";
				
				global $ADODB_FETCH_MODE;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Consultar los tipos de Causas externas";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						echo $this->mensajeDeError;
						return false;
				}
				while(!$result->EOF)
				{
						$dato = $result->FetchRow();
						$var[]= $dato;
				}

				$result->Close();
				return $var;
		}
		/**
		* Trae los tipos de Diagnostico para una consulta
		*   @return Array
		*/
		function ConsultaDiagnostico()
		{
						list($dbconn) = GetDBconn();

			$query = "
						SELECT  diagnostico_id,
														diagnostico_nombre
						FROM        diagnosticos
						ORDER BY diagnostico_nombre
				";
				global $ADODB_FETCH_MODE;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Consultar los tipos de Diagnostico";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						echo $this->mensajeDeError;
						return false;
				}
				while(!$result->EOF)
				{
						$dato = $result->FetchRow();
						$var[]= $dato;
				}

				$result->Close();
				return $var;
		}
	}
?>