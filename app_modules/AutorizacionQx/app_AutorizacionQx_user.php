<?php

/**
 * $Id: app_AutorizacionQx_user.php,v 1.6 2005/09/26 18:23:42 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

/**
*Contiene los metodos para realizar las autorizaciones.
*/

class app_AutorizacionQx_user extends classModulo
{
		var $color;
		var $limit;
		var $conteo;

    function app_AutorizacionQx_user()
    {
            $this->limit=GetLimitBrowser();
            return true;
    }

		function AutorizarSolicitud()
		{
					if(empty($_SESSION['AUTORIZACIONES']))
					{
								$this->error = "AUTORIZACION NULA";
								$this->mensajeDeError = "DATOS DE LA AUTORIZACIÓN VACIOS.";
								return false;
					}

					if(empty($_SESSION['AUTORIZACIONES']['RETORNO']))
					{
								$this->error = "AUTORIZACION ";
								$this->mensajeDeError = "EL RETORNO DE LA AUTORIZACIÓN ESTA VACIO.";
								return false;
					}

					$PacienteId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
					$TipoId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
					$PlanId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];
					$solicitud=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['solicitud'];
					$empresa=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['empresa'];

					if(empty($PacienteId) || empty($TipoId) || empty($PlanId) || empty($solicitud) || empty($empresa))
					{
									$this->error = "AUTORIZACION ";
									$this->mensajeDeError = "DATOS DE LA AUTORIZACIÓN INCOMPLETOS.";
									return false;
					}

					list($dbconn) = GetDBconn();
					$query = "SELECT sw_tipo_plan, sw_afiliacion, protocolos, sw_autoriza_sin_bd
										FROM planes
										WHERE estado='1' and plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
										and fecha_final >= now() and fecha_inicio <= now()";
					$results = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$this->fileError = __FILE__;
							$this->lineError = __LINE__;
							return false;
					}
					$results->Close();
					if ($dbconn->EOF) {
									$this->RetornarAutorizacion(false,'','El plan no existe, no tiene vigencia, o no esta activo',0);
									return true;
					}

					list($TipoPlan,$swAfiliados,$Protocolos,$swAutoSinBD)=$results->FetchRow();

					$query = "select protocolos from planes Where plan_id='$PlanId'";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "ERROR AL GUARDAR EN LA BASE DE DATOS";
									$this->mensajeDeError = "ERROR DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
									return false;
					}
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo']=$result->fields[0];
					$result->Close();

					$query = " SELECT a.plan_id FROM planes_auditores_int as a
											WHERE a.plan_id='$PlanId' and a.usuario_id=".UserGetUID()."";
					$result=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "ERROR AL GUARDAR EN LA BASE DE DATOS";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
									return false;
					}
					if(!$result->EOF)
					{
									$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUDITOR']=$result->fields[0];
					}

					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo']=$Protocolos;
					$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan']=$TipoPlan;
					if(($TipoPlan==0 AND $swAfiliados==1) OR ($swAfiliados==1))
					{    //funcion que busca en las bases de afiliados (en caso de plan cliente o capitado)
							$PacienteId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
							$TipoId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
							$Plan=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];
							if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
							{
									$this->error = "Error";
									$this->mensajeDeError = "No se pudo incluir : classes/notas_enfermeria/revision_sistemas.class.php";
									return false;
							}
							if(!class_exists('BDAfiliados'))
							{
									$this->error="Error";
									$this->mensajeDeError="no existe BDAfiliados";
									return false;
							}

							$class= New BDAfiliados($TipoId,$PacienteId,$Plan);
							if($class->GetDatosAfiliado()==false)
							{
									$this->frmError["MensajeError"]=$class->mensajeDeError;
							}

							if(!empty($class->salida))
							{
									$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$class->salida;
							}
					}


					$this->FormaAutorizacion();
					return true;
		}
		
	function InsertarAutorizacion()
	{
			//valida si elegio el tipo de autorizacion
			if(!empty($_REQUEST['Aceptar']) && $_REQUEST['TipoAutorizacion']==-1)
			{
							$this->frmError["MensajeError"]="DEBE ELEGIR EL TIPO DE AUTORIZACIÓN.";
							$this->FormaAutorizacion();
							return true;
			}
			elseif(!empty($_REQUEST['Aceptar']) && $_REQUEST['TipoAutorizacion']!=-1)
			{
							$this->FormaAutorizacionTipo($_REQUEST['TipoAutorizacion']);
							return true;
			}
									
			list($dbconn) = GetDBconn();	
			//insertar dialogo
			if(!empty($_REQUEST['Observacion']))
			{
					if(empty($_REQUEST['dialogo']))
					{
							$this->frmError["dialogo"]=1;
							$this->frmError["MensajeError"]="DEBE ESCRIBIR SU OBSERVACION.";
							$this->FormaAutorizacion();
							return true;
					}
								
					$query = "INSERT INTO hc_os_solicitudes_observaciones(
																			observacion_id,hc_os_solicitud_id,
																			fecha,usuario_id,
																			observacion,fecha_ultima_modificacion)
										VALUES (nextval('public.hc_os_solicitudes_observaciones_observacion_id_seq'),".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['solicitud'].",'now()',".UserGetUID().",'".$_REQUEST['dialogo']."','now()')";
					$results = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error select count(*)";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
									return false;
					}
					$this->frmError["MensajeError"]="LA OBSERVACION SE GUARDO CORRECTAMENTE.";					
					$this->FormaAutorizacion();
					return true;					
			}
			
	}
    function BuscarAutorizaciones($tabla)
    {
				list($dbconn) = GetDBconn();
				if($tabla=='autorizaciones_por_sistema')
				{
								$query = "select  b.nombre, a.* from $tabla as a, system_usuarios as b
														where a.autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
														and a.usuario_id=b.usuario_id";
				}
				else
				{
								$query = "select * from $tabla
														where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
				}
				$result=$dbconn->Execute($query);

				if(!$result->EOF)
				{
						while(!$result->EOF)
						{
										$var[]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
						}
				}
				return $var;
    }	

		function RetornarAutorizacion()
		{
					if(!empty($_REQUEST['Cancelar']))
					{  $_SESSION['AUTORIZACIONES']['VALIDACION']=3;  }

					$Contenedor=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
					$Modulo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
					$Tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
					$Metodo=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
					$argu=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS'];

					$this->ReturnMetodoExterno($Contenedor,$Modulo,$Tipo,$Metodo,$argu);
					return true;
		}

    function BuscarSwHc()
    {
        list($dbconn) = GetDBconn();
        $query = "select sw_hc from autorizaciones_niveles_autorizador
                  where nivel_autorizador_id='".$_SESSION['CentroAutorizacionQx']['NIVEL']."'";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
                return false;
        }
        if(!$result->EOF)
        {  $var=$result->fields[0];  }

        return $var;
    }

    function CantidadMeses($plan)
    {
        list($dbconn) = GetDBconn();
        $sql="select meses_consulta_base_datos from planes where plan_id=$plan;";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->fileError = __FILE__;
					$this->lineError = __LINE__;
          $dbconn->RollbackTrans();
          return false;
        }
        $result->Close();
        return $result->fields[0];
    }

    function BuscarEvolucion()
    {    $var='';
        list($dbconn) = GetDBconn();
        $query = "select b.evolucion_id from hc_evoluciones as b
                  where b.ingreso='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']."'
                  and b.fecha_cierre=(select max(fecha_cierre)
                  from hc_evoluciones  where ingreso='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']."')";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
                return false;
        }
        if(!$result->EOF)
        {  $var=$result->fields[0];  }

        return $var;
    }

    function DatosPlan()
    {
				list($dbconn) = GetDBconn();
				$query = "SELECT a.plan_descripcion, b.nombre_tercero
									FROM planes as a, terceros as b
									WHERE a.plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
									and a.tipo_tercero_id=b.tipo_id_tercero and a.tercero_id=b.tercero_id";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				$vars=$resulta->GetRowAssoc($ToUpper = false);
				return $vars;
    }

    function NombreAfiliado($Tipo)
    {
				list($dbconn) = GetDBconn();
				$query = "SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id
														FROM tipos_afiliado as a, planes_rangos as b
														WHERE b.plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
														and a.tipo_afiliado_id='$Tipo'";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$vars=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->Close();
				return $vars;
    }

		function Tipo_Afiliado()
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id
														FROM tipos_afiliado as a, planes_rangos as b
														WHERE b.plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
														and b.tipo_afiliado_id=a.tipo_afiliado_id";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}

				while(!$resulta->EOF)
				{
								$vars[]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
				}
				$resulta->Close();
				return $vars;
		}

		function Niveles()
		{
				list($dbconn) = GetDBconn();
				$query="SELECT DISTINCT rango
												FROM planes_rangos
												WHERE plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'";
				$result=$dbconn->Execute($query);
				while(!$result->EOF){
						$niveles[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
				}
				return $niveles;
		}

		function TiposAuto()
		{
					list($dbconn) = GetDBconn();
					$query = " SELECT tipo_autorizacion,descripcion FROM tipos_autorizacion
															WHERE tipo_autorizacion not in(3)";
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
									return false;
					}

					while(!$resulta->EOF)
					{
									$vars[]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
					}
					$resulta->Close();
					return $vars;
		}

		function DatosProcedimiento($solicitud)
		{
					list($dbconn) = GetDBconn();
					$query = "SELECT a.sw_estado, a.cantidad, a.sw_programado, b.descripcion as descargo,
										c.observacion, c.fecha, c.usuario_id, c.fecha_ultima_modificacion, d.nombre,
										f.descripcion as nivel, e.observacion as obsacto, e.fecha_tentativa_cirugia,
										g.descripcion as ambito, h.descripcion as finalidad, i.descripcion as tipo,
										b.cargo
										FROM hc_os_solicitudes as a
										left join hc_os_solicitudes_observaciones as c on(a.hc_os_solicitud_id=c.hc_os_solicitud_id)
										left join system_usuarios as d on(c.usuario_id=d.usuario_id),
										cups as b, hc_os_solicitudes_datos_acto_qx as e
										left join hc_os_solicitudes_niveles_autorizacion as f on (e.nivel_autorizacion=f.nivel),
										qx_ambitos_cirugias as g, qx_finalidades_procedimientos as h, qx_tipos_cirugia as i
										WHERE a.hc_os_solicitud_id=$solicitud and a.cargo=b.cargo
										and a.hc_os_solicitud_id=e.hc_os_solicitud_id and e.ambito_cirugia_id=g.ambito_cirugia_id
										and e.finalidad_procedimiento_id=h.finalidad_procedimiento_id
										and e.tipo_cirugia_id=i.tipo_cirugia_id";
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
									return false;
					}

					while(!$resulta->EOF)
					{
									$vars[]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
					}
					$resulta->Close();
					return $vars;
		}
		
		function Apoyos()
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.cargo, a.cantidad, b.descripcion, a.autorizacion 
									FROM hc_os_solicitudes_procedimientos_apoyos as a, cups as b
									WHERE a.hc_os_solicitud_id=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['solicitud']."
									and a.cargo=b.cargo";				
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
								return false;
				}

				while(!$resulta->EOF)
				{
								$vars[]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
				}
				$resulta->Close();
				return $vars;		
		}
		
		function Procedimientos()
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT b.cargo, b.descripcion, a.autorizacion  
									FROM hc_os_solicitudes_otros_procedimientos_qx as a, cups as b
									WHERE a.hc_os_solicitud_id=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['solicitud']."
									and a.procedimiento_id=b.cargo";				
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
								return false;
				}

				while(!$resulta->EOF)
				{
								$vars[]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
				}
				$resulta->Close();
				return $vars;		
		}		
		
		function Productos()
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.codigo_producto, a.cantidad, b.descripcion, a.autorizacion  
									FROM hc_os_solicitudes_otros_productos_inv as a, inventarios_productos as b
									WHERE a.hc_os_solicitud_id=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['solicitud']."
									and a.codigo_producto=b.codigo_producto";				
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
								return false;
				}

				while(!$resulta->EOF)
				{
								$vars[]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
				}
				$resulta->Close();
				return $vars;		
		}			
		
		function Estancia()
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT b.descripcion, a.cantidad_dias, a.autorizacion, a.tipo_cama_id,
									a.sw_pre_qx, a.sw_pos_qx, c.descripcion as tipocama, a.tipo_clase_cama_id
									FROM hc_os_solicitudes_estancia as a 
									left join tipos_camas as c on(a.tipo_cama_id=c.tipo_cama_id), 
									tipos_clases_camas as b
									WHERE a.hc_os_solicitud_id=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['solicitud']."
									and a.tipo_clase_cama_id=b.tipo_clase_cama_id";				
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
								return false;
				}

				while(!$resulta->EOF)
				{
								$vars[]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
				}
				$resulta->Close();
				return $vars;		
		}
		
		function TiposCamas($clase)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.descripcion, a.tipo_cama_id
									FROM tipos_camas as a
									WHERE a.tipo_clase_cama_id=$clase and a.empresa_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['empresa']."'";				
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
								return false;
				}

				while(!$resulta->EOF)
				{
								$vars[]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
				}
				$resulta->Close();
				return $vars;			
		}
		
		function AutorizacionesSolicitud()
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT a.autorizacion, a.observaciones, a.fecha_registro, a.sw_estado, b.nombre
									FROM autorizaciones_qx as a, system_usuarios as b
									WHERE a.hc_os_solicitud_id=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['solicitud']."
									and a.usuario_id=b.usuario_id";				
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
								return false;
				}

				while(!$resulta->EOF)
				{
								$vars[]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
				}
				$resulta->Close();
				return $vars;		
		}				
//-----------------------------------------------------------------------------
}//fin clase user

?>

