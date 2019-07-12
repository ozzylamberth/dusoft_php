<?php

/**
 * $Id: app_Solicitud_Medicamentos_PorBodega_user.php,v 1.17 2009/04/21 22:17:20 hugo Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @package IPSOFT-SIIS
*
*/

class app_Solicitud_Medicamentos_PorBodega_user extends classModulo
{

	/**
	* Funcion contructora que inicializa las variables
	* @return boolean
	*/
	function app_Solicitud_Medicamentos_PorBodega_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}
	
	/**
	* Funcion principal que se encarga de llamar al menu para la seleccion de la empresa
	* @return boolean
	*/

	function main()
	{      
		$this->Principal();
		return true;
	}
	
	function PermisosUsuarios()
	{
		list($dbconn) = GetDBconn();
		
		$query = "SELECT 	a.empresa_id,
											b.razon_social AS descripcion1,
											a.centro_utilidad,
											c.descripcion AS descripcion2,
											e.bodega,
											e.descripcion AS descripcion3,
											g.departamento,
											g.descripcion AS descripcion4,
											a.usuario_id,
											d.nombre
							FROM 	userpermisos_solicitudes_bodegas AS a,
										empresas AS b,
										centros_utilidad AS c,
										system_usuarios AS d,
										bodegas AS e,
										departamentos AS g
							WHERE a.usuario_id=".UserGetUID()."
							AND 	a.empresa_id=b.empresa_id
							AND 	a.centro_utilidad=c.centro_utilidad
							AND 	a.empresa_id=c.empresa_id
							AND 	a.usuario_id=d.usuario_id
							AND 	e.empresa_id=a.empresa_id
							AND 	e.centro_utilidad=a.centro_utilidad
							AND 	e.departamento=g.departamento
							ORDER BY e.descripcion;";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$result->EOF)
		{
			$vars[$result->fields[1]][$result->fields[3]][$result->fields[5]]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		
		$mtz[0]='EMPRESAS';
		$mtz[1]='CENTROS UTILIDAD';
		$mtz[2]='BODEGAS';
		$url[0]='app';
		$url[1]='Solicitud_Medicamentos_PorBodega';
		$url[2]='user';
		$url[3]='frmCuadroSolicitudes';
		$url[4]='PermisoSolMed';
		
		$this->salida .=gui_theme_menu_acceso('SOLICITUD MEDICAMENTOS', $mtz, $vars, $url, ModuloGetURL('system','Menu'));
		return true;
	}
	
	function GetSolicitudes($empresa,$centro_utilidad,$bodega,$sw)
	{
		list($dbconn) = GetDBconn();
		
		$query = "
								SELECT 	c.departamento,
												c.descripcion as dpto,
												a.estacion_id,
												b.descripcion as estacion,
												TO_CHAR(a.fecha_solicitud,'YYYY-MM-DD') as fecha,
												a.fecha_solicitud,
												a.solicitud_id as codigo,
												a.sw_impreso,
												a.usuario_imp,
												a.ingreso,
												d.nombre as usuarioestacion,
												a.usuario_id,
												c.descripcion as deptoestacion,
												e.numerodecuenta,
												e.rango,
												k.tipo_afiliado_nombre as tipo_afiliado_id,
												h.plan_descripcion,
												i.tipo_id_paciente,
												i.paciente_id,
												l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac
								FROM 	hc_solicitudes_medicamentos a,
											estaciones_enfermeria b,
											departamentos c,
											system_usuarios d,
											cuentas e,
											planes h,
											ingresos i,
											tipos_afiliado k,
											pacientes l
								WHERE a.bodega='".$bodega."' 
								AND 	a.empresa_id='".$empresa."' 
								AND		a.centro_utilidad='".$centro_utilidad."' 
								AND 	a.sw_estado='$sw' 
								AND 	a.estacion_id=b.estacion_id
								AND 	b.departamento=c.departamento 
								AND 	a.usuario_id=d.usuario_id 
								AND 	a.ingreso=e.ingreso 
								AND 	(e.estado='1' OR e.estado='2')
								AND 	a.ingreso=i.ingreso 
								AND 	e.plan_id=h.plan_id
								AND 	a.usuario_id!=0
								AND 	k.tipo_afiliado_id=e.tipo_afiliado_id 
								AND 	i.tipo_id_paciente=l.tipo_id_paciente 
								AND 	i.paciente_id=l.paciente_id
								";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - GetSolicitudes";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->RecordCount() > 0)
		{
			while(!$result->EOF)
			{
				$vars[$result->fields[1]][$result->fields[3]][$result->fields[7]][$result->fields[6]]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		
		return $vars;
	}
	
	function GetDevolucionesMedicamentos($empresa,$centro_utilidad,$bodega)
	{
		list($dbconn) = GetDBconn();
		
		$query = "
							SELECT c.departamento,
											c.descripcion as dpto,
											a.estacion_id,
											b.descripcion as estacion,
											a.documento as codigo,
											TO_CHAR(a.fecha_registro,'YYYY-MM-DD') as fecha,
											a.fecha_registro as fecha_solicitud,
											a.ingreso,
											d.nombre as usuarioestacion,
											a.usuario_id,
											c.descripcion as deptoestacion,
											e.rango,
											k.tipo_afiliado_nombre as tipo_afiliado_id,
											h.plan_descripcion,
											i.tipo_id_paciente,
											i.paciente_id,
											l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
											a.observacion,
											est.descripcion as parametro
							FROM 	inv_solicitudes_devolucion a
										LEFT JOIN estacion_enfermeria_parametros_devolucion est 
										ON
										(
											est.parametro_devolucion_id=a.parametro_devolucion_id
										),
										estaciones_enfermeria b,
										departamentos c,
										system_usuarios d,
										cuentas e,
										planes h,
										ingresos i,
										tipos_afiliado k,
										pacientes l
							WHERE a.bodega='".$bodega."' 
							AND 	a.empresa_id='".$empresa."' 
							AND 	a.centro_utilidad='".$centro_utilidad."' 
							AND 	a.estado='0' 
							AND 	a.estacion_id=b.estacion_id 
							AND 	b.departamento=c.departamento 
							AND 	a.usuario_id=d.usuario_id 
							AND 	a.ingreso=e.ingreso 
							AND 	(e.estado='1' OR e.estado='2')
							AND 	a.ingreso=i.ingreso
							AND 	a.usuario_id!=0
							AND 	e.plan_id=h.plan_id 
							AND 	k.tipo_afiliado_id=e.tipo_afiliado_id 
							AND 	i.tipo_id_paciente=l.tipo_id_paciente 
							AND 	i.paciente_id=l.paciente_id";

		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - GetDevolucionesMedicamentos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->RecordCount() > 0)
		{
			while(!$result->EOF)
			{
				$vars[$result->fields[1]][$result->fields[3]]['0'][$result->fields[4]]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		
		return $vars;
	}
	
	function GetInfoUsuario($usuario_id)
	{
		
		if(!$usuario_id)
			$usuario_id=UserGetUID();
			
		list($dbconn) = GetDBconn();
		
		$query="SELECT *
						FROM system_usuarios
						WHERE usuario_id=$usuario_id";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}
	
	
	function ObtenerCamaDespacho($ingreso,$solicitud)
	{
		list($dbconn) = GetDBconn();
		
		$query = 
							"	SELECT 	d.cama,d.pieza
								FROM 		hc_solicitudes_medicamentos as a
								JOIN cuentas as b
								ON
								(
									a.ingreso=b.ingreso
								)
								LEFT JOIN movimientos_habitacion as c 
								ON
								(
									b.numerodecuenta=c.numerodecuenta
								)
								JOIN camas as d 
								ON
								(
									c.cama=d.cama 
									AND c.fecha_egreso is NULL
								)
								WHERE a.ingreso=$ingreso
								AND a.solicitud_id='$solicitud'
								ORDER BY d.cama,d.pieza ASC
								;";
										
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - ObtenerCamaDevolucion";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->RecordCount() > 0)
		{
			while(!$result->EOF)
			{
				$vars=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		return $vars;
	
	}
	
	function ObtenerCamaDevolucion($ingreso,$documento)
	{
		list($dbconn) = GetDBconn();
		
		$query = 
							"	SELECT 	d.cama,d.pieza
								FROM 		inv_solicitudes_devolucion as a
								JOIN cuentas as b
								ON
								(
									a.ingreso=b.ingreso
								)
								LEFT JOIN movimientos_habitacion c 
								ON
								(
									b.numerodecuenta=c.numerodecuenta
								)
								JOIN camas d 
								ON
								(
									c.cama=d.cama 
									AND c.fecha_egreso is NULL
								)
								WHERE a.ingreso=$ingreso
								AND a.documento='$documento'
								ORDER BY d.cama,d.pieza ASC
								;";
										
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - ObtenerCamaDevolucion";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->RecordCount() > 0)
		{
			while(!$result->EOF)
			{
				$vars=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		return $vars;
	
	}
	
	function GetExistenciasMinimas($pagina,$empresa,$centro_utilidad,$bodega)
	{
		list($dbconn) = GetDBconn();
		
		$query = "SELECT count(*)
							FROM 		existencias_bodegas a,
        							inventarios b,
											inventarios_productos c,
											system_usuarios as d 
							WHERE a.empresa_id='".$empresa."' 
							AND 	a.centro_utilidad='".$centro_utilidad."' 
							AND 	a.bodega='".$bodega."' 
							AND 	a.codigo_producto=b.codigo_producto 
							AND 	a.empresa_id=b.empresa_id 
							AND 	a.codigo_producto=c.codigo_producto
							AND		a.usuario_id=d.usuario_id
							AND 	a.existencia < a.existencia_minima
							AND 	c.estado='1';";
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - GetExistenciasMinimas Count()";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$this->conteo=$result->fields[0];
		$this->ProcesarSqlConteo(20,$pagina);
		
		$query = "
							SELECT 	a.codigo_producto,
											a.existencia,
											a.existencia_minima,
        							a.existencia_maxima,
											c.descripcion as desprod,
											d.nombre as nombre_usuario,
											a.fecha_registro,
											TO_CHAR(a.fecha_registro,'YYYY-MM-DD') as fecha 
							FROM 		existencias_bodegas a,
        							inventarios b,
											inventarios_productos c,
											system_usuarios as d 
							WHERE a.empresa_id='".$empresa."' 
							AND 	a.centro_utilidad='".$centro_utilidad."' 
							AND 	a.bodega='".$bodega."' 
							AND 	a.codigo_producto=b.codigo_producto 
							AND 	a.empresa_id=b.empresa_id 
							AND 	a.codigo_producto=c.codigo_producto
							AND		a.usuario_id=d.usuario_id
							AND 	a.existencia < a.existencia_minima
							AND 	c.estado='1'
							ORDER BY c.descripcion
							LIMIT ".$this->limit." OFFSET ".$this->offset.";";

		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - GetExistenciasMinimas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->RecordCount() > 0)
		{
			while(!$result->EOF)
			{
				$vars[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		return $vars;
	}
	
	function ProcesarSqlConteo($limite=null,$offset=null)
	{
		$this->offset = 0;
		$this->paginaActual = 1;
		if($limite == null)
		{
			$this->limit = GetLimitBrowser();
		}
		else
		{
			$this->limit = $limite;
		}
		
		if($offset)
		{
			$this->paginaActual = intval($offset);
			if($this->paginaActual > 1)
			{
				$this->offset = ($this->paginaActual - 1) * ($this->limit);
			}
		}
		
		return true;
	}
	
	function GetConfirmacionTransferencias($empresa,$centro_utilidad,$bodega)
	{
    list($dbconn) = GetDBconn();
    
		$query="SELECT 	inv_documento_transferencia_id,
										bodega,
										centro_utilidad,
										fecha_transferencia
						FROM 	inv_documento_transferencia_bodegas 
						WHERE empresa_id='".$empresa."' 
						AND 	centro_utilidad='".$centro_utilidad."' 
						AND 	bodega_destino='".$bodega."'
						AND 	estado='0';";
						
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - GetConfirmacionTransferencias";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
		return $vars;
	}
	
	function NombreBodegasInventario($Bodega,$CentroUtili)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT bodega,descripcion 
						FROM bodegas 
						WHERE empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
						AND centro_utilidad= '$CentroUtili' 
						AND bodega='$Bodega'";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->EOF)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'bodegas' esta vacia";
				return false;
			}
			else
			{
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		$result->Close();
		return $vars;
	}
	
	function ConsultaProductosDocumentoTransaccion($consecutivo)
	{
		list($dbconn) = GetDBconn();
    
		$query="SELECT 	a.codigo_producto,
										b.descripcion,
										a.cantidad,
										inv.costo,
										b.sw_control_fecha_vencimiento
    				FROM 	inv_documento_transferencia_bodegas y,
									inv_documento_transferencia_bodegas_d a,
									inventarios_productos b,
									existencias_bodegas c,
									inventarios inv
    				WHERE y.inv_documento_transferencia_id=a.inv_documento_transferencia_id 
						AND a.inv_documento_transferencia_id='$consecutivo' 
						AND a.codigo_producto=b.codigo_producto 
						AND c.codigo_producto=a.codigo_producto 
						AND c.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
						AND c.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
						AND c.bodega='".$_SESSION['Bodegas']['bodega']."' 
						AND inv.codigo_producto=b.codigo_producto 
						AND inv.empresa_id=c.empresa_id";
		
		$result = $dbconn->Execute($query);
		
		if($result->EOF)
		{
				$this->error = "Error al ejecutar la consulta.<br>";
				$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
				return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
				return $vars;
			}
		}
	}
	
	/*function ConfirmarTransferenciasBodegas()
	{
		$ProductosDocumento=$this->ConsultaProductosDocumentoTransaccion($_REQUEST['consecutivo']);
		if($ProductosDocumento)
		{
      for($i=0;$i<sizeof($ProductosDocumento);$i++)
			{
        if($ProductosDocumento[$i]['sw_control_fecha_vencimiento_dest']=='1')
				{
          $datos=$this->FechasLotesProductos($_REQUEST['consecutivo'],$ProductosDocumento[$i]['codigo_producto']);
					$suma=$this->SumaFechasLotesProductos($_REQUEST['consecutivo'],$ProductosDocumento[$i]['codigo_producto']);
					if(!$datos)
					{
						$this->frmError["MensajeError"]="Es obligatoria la fecha de vencimiento y el lote para el producto con codigo".' '.$ProductosDocumento[$i]['codigo_producto'];
						$this->DetalleTransferenciaBodega($_REQUEST['consecutivo'],$_REQUEST['bodegaOrigen'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['FechaTransferencia']);
						return true;
					}
					elseif($suma['suma']<$ProductosDocumento[$i]['cantidad'])
					{
						$this->frmError["MensajeError"]="La Suma de las Cantidades Insertadas es menor a la Cantidad Total del Producto con Codigo".' '.$ProductosDocumento[$i]['codigo_producto'];
						$this->DetalleTransferenciaBodega($_REQUEST['consecutivo'],$_REQUEST['bodegaOrigen'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['FechaTransferencia']);
						return true;
					}
				}
			}
		}
    
		list($dbconn) = GetDBconn();
    
		$query="SELECT bodegas_doc_id 
						FROM bodegas_doc_numeraciones 
						WHERE tipo_movimiento='E' 
						AND sw_estado='1' 
						AND sw_traslado='1' 
						AND empresa_id='".$_SESSION['Bodegas']['Empresa']."' 
						AND centro_utilidad='".$centro_o."' 
						AND bodega='".$bodega_o."' 
						ORDER BY bodegas_doc_id";
		$result = $dbconn->Execute($query);
    
		if($result->RecordCount()<1)
		{
			$this->frmError['MensajeError']="Error al Realizar La Transferencia, No existe un Tipo de Documento en la Bodega Origen para Soportar la Repocision";
			return false;
		}
		$concepto=$result->fields[0];
		$numeracion=$this->AsignarNumeroDocumentoDespacho($concepto);
		$numeracion=$numeracion['numeracion'];
		
		$query="INSERT INTO bodegas_documentos
						(
							bodegas_doc_id,
							numeracion,
							fecha,
							total_costo,
							transaccion,
							observacion,
							usuario_id,
							fecha_registro,
							centro_utilidad_transferencia,
							bodega_destino_transferencia
						)
						VALUES
						(
							'$concepto',
							'$numeracion',
							'".date("Y-m-d")."',
							'0',
							NULL,
							'',
							'".UserGetUID()."',
							'".date("Y-m-d H:i:s")."',
							'".$_SESSION['Bodegas']['centro_id']."',
							'".$_SESSION['Bodegas']['bodega']."'
						);";
		$result=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$result=$dbconn->Execute("ROLLBACK;");
			return false;
		}
		else
		{
      for($i=0;$i<sizeof($ProductosDocumento);$i++)
			{
				$query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
				$result = $dbconn->Execute($query);
				$consecutivo=$result->fields[0];
				
				$query="SELECT costo 
								FROM inventarios 
								WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' 
								AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."'";
				
				$result = $dbconn->Execute($query);
				$costo=$result->fields[0];
				
				$query="INSERT INTO bodegas_documentos_d
								(
									consecutivo,
									codigo_producto,
									cantidad,
									total_costo,
									bodegas_doc_id,
									numeracion
								)
								VALUES
								(
									'$consecutivo',
									'".$ProductosDocumento[$i]['codigo_producto']."',
									'".$ProductosDocumento[$i]['cantidad']."',
									'$costo',
									'$concepto',
									'$numeracion'
								)";
				$result=$dbconn->Execute($query);
        
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$result=$dbconn->Execute("ROLLBACK;");
					return false;
				}
				else
				{
          if($ProductosDocumento[$i]['sw_control_fecha_vencimiento']=='1')
					{
						DescargarLotesBodega($_SESSION['BODEGAS']['Empresa'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['bodegaOrigen'],$ProductosDocumento[$i]['codigo_producto'],$ProductosDocumento[$i]['cantidad']);
          }
          $query="SELECT existencia 
									FROM existencias_bodegas 
									WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' 
									AND empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
									AND centro_utilidad='".$centro_o."' 
									AND bodega='".$bodega_o."'";
									
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					else
					{
						$datos=$result->RecordCount();
						if($datos)
						{
								$exis=$result->GetRowAssoc($toUpper=false);
						}
            $TotalExistencias=$exis['existencia']-$ProductosDocumento[$i]['cantidad'];
						if($TotalExistencias<0)
						{
								$this->frmError['MensajeError']="La Transferencia No tuvo Exito, no hay Suficientes Existencias en Bodega para el Producto".' '.$ProductosDocumento[$i]['codigo_producto'];
								
								return true;
						}
						$query="UPDATE existencias_bodegas 
										SET existencia='$TotalExistencias' 
										WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' 
										AND empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
										AND centro_utilidad='".$centro_o."' 
										AND bodega='".$bodega_o."'";
						$result = $dbconn->Execute($query);
						
						if($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$result=$dbconn->Execute("ROLLBACK;");
							return false;
						}
						
// 						else{
//                             $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='$CodigoPro' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
//                             $result = $dbconn->Execute($query);
//                             if($dbconn->ErrorNo() != 0){
//                                 $this->error = "Error al Cargar el Modulo";
//                                 $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                                 $this->GuardarNumeroDocumento($commit=false);
//                                 return false;
//                             }else{
//                               $Regs=$result->GetRowAssoc($toUpper=false);
//                                 if($Regs['existencia']==$TotalExistencias){
//                                   return 1;
//                                 }
//                             }
//                         }
						}
					}
				}
            $this->TotalizarDocumentoFinalBodega($numeracion,$concepto);
            //DOCUMENTO DE INGRESO A LA BODEGA
            $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='I' AND sw_estado='1' AND sw_traslado='1' AND
            empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' ORDER BY bodegas_doc_id";
            $result = $dbconn->Execute($query);
            if($result->RecordCount()<1){
                $mensaje="Error al Realizar La Transferencia, No existe un Tipo de Documento en la Bodega Destino para Soportar la Repocision";
                $titulo="TRANSFERENCIA ENTRE BODEGAS";
                $accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
                $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
                return true;
            }
            $concepto=$result->fields[0];
            $numeracion=AsignarNumeroDocumentoDespacho($concepto);
            $numeracion=$numeracion['numeracion'];
            $query="INSERT INTO bodegas_documentos(bodegas_doc_id,
                                                  numeracion,
                                                                                        fecha,
                                                                                        total_costo,
                                                                                        transaccion,
                                                                                        observacion,
                                                                                        usuario_id,
                                                                                        fecha_registro,
                                                                                        centro_utilidad_transferencia,
                                                                                        bodega_destino_transferencia)VALUES(
                                                                                        '$concepto',
                                                                                        '$numeracion',
                                                                                        '".date("Y-m-d")."',
                                                                                        '0',NULL,'',
                                                                                        '".UserGetUID()."',
                                                                                        '".date("Y-m-d H:i:s")."',
                                                                                        '".$_REQUEST['centroUtilidadOrigen']."',
                                                                                      '".$_REQUEST['bodegaOrigen']."')";
            $result=$dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
            }else{
                for($i=0;$i<sizeof($ProductosDocumento);$i++){
                    $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
                    $result = $dbconn->Execute($query);
                    $consecutivo=$result->fields[0];
                    $query="SELECT costo FROM inventarios WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."'";
                    $result = $dbconn->Execute($query);
                    $costo=$result->fields[0];
                    $query="INSERT INTO bodegas_documentos_d(consecutivo,
                                                                                                        codigo_producto,
                                                                                                        cantidad,
                                                                                                        total_costo,
                                                                                                        bodegas_doc_id,
                                                                                                        numeracion)VALUES(
                                                                                                        '$consecutivo',
                                                                                                        '".$ProductosDocumento[$i]['codigo_producto']."',
                                                                                                        '".$ProductosDocumento[$i]['cantidad']."',
                                                                                                        '$costo',
                                                                                                        '$concepto',
                                                                                                        '$numeracion')";
                    $result=$dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                    }else{
                        if($ProductosDocumento[$i]['sw_control_fecha_vencimiento_dest']=='1'){
                            $query="INSERT INTO bodegas_documentos_d_fvencimiento_lotes(fecha_vencimiento,
                                                                                                                                                        lote,
                                                                                                                                                        saldo,
                                                                                                                                                        cantidad,
                                                                                                                                                        empresa_id,
                                                                                                                                                        centro_utilidad,
                                                                                                                                                        bodega,
                                                                                                                                                        codigo_producto,
                                                                                                                                                        consecutivo
                                                                                                                                                        )SELECT
                                                                                                                                                        fecha_vencimiento,
                                                                                                                                                        lote,
                                                                                                                                                        '0',
                                                                                                                                                        cantidad,
                                                                                                                                                        '".$_SESSION['BODEGAS']['Empresa']."',
                                                                                                                                                        '".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                                                                                                        '".$_SESSION['BODEGAS']['BodegaId']."',
                                                                                                                                                        '".$ProductosDocumento[$i]['codigo_producto']."',
                                                                                                                                                        '$consecutivo'
                                                                                                                                                        FROM inv_bodegas_transferencia_fvencimiento_lotes
                                                                                                                                                        WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND inv_documento_transferencia_id='".$_REQUEST['consecutivo']."'";
                            $result=$dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Guardar en la Base de Datos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->GuardarNumeroDocumento($commit=false);
                                return false;
                            }
                        }
                        $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }else{
                            $datos=$result->RecordCount();
                            if($datos){
                                $exis=$result->GetRowAssoc($toUpper=false);
                            }
                            $TotalExistencias=$exis['existencia']+$ProductosDocumento[$i]['cantidad'];
                            $query="UPDATE existencias_bodegas SET existencia='$TotalExistencias' WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->GuardarNumeroDocumento($commit=false);
                                return false;
                            }
                        }
                    }
                }
        $query="DELETE FROM inv_documento_transferencia_bodegas WHERE inv_documento_transferencia_id='".$_REQUEST['consecutivo']."'";
                $result=$dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }
                $this->TotalizarDocumentoFinalBodega($numeracion,$concepto);
            }
            $this->GuardarNumeroDocumento($commit=true);
            $mensaje="La Transferencia Fue Exitosa";
            $titulo="TRANSFERENCIA ENTRE BODEGAS";
            $accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }
        $mensaje="La Transferencia No tuvo Exito, Consulte al Administrador del Sistema";
        $titulo="TRANSFERENCIA ENTRE BODEGAS";
        $accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
        $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
        return true;
    }*/
	
	
	function GetTipoSolicitudBodega($solicitud)
	{
		list($dbconn) = GetDBconn();
		
		$query = "SELECT 
										tipo_solicitud,
										b.observacion,
										b.fecha_registro,
										c.nombre
							FROM 	hc_solicitudes_medicamentos a
										LEFT JOIN hc_auditoria_solicitudes_medicamentos b 
										ON
										(
											a.solicitud_id=b.solicitud_id
										)
										LEFT JOIN system_usuarios c 
										ON
										(
											b.usuario_id=c.usuario_id
										)
							WHERE a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."'
							AND 	a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."'
							AND 	a.bodega='".$_SESSION['Bodegas']['bodega']."'
							AND 	a.solicitud_id='$solicitud'";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - GetTipoSolicitudBodega";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->EOF)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'hc_solicitudes_medicamentos' esta vacia ";
				return false;
			}
			else
			{
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}

		return $vars;
	}
	
	function GetMedicamentosSolicitud($solicitud)
	{
		$query = "(SELECT SMD.solicitud_id,
																						SMD.consecutivo_d,
																						NULL as mezcla_recetada_id,
																						SMD.medicamento_id,
																						SMD.evolucion_id,
																						SMD.cant_solicitada,
																						M.cod_forma_farmacologica,
																						INVP.descripcion as nomMedicamento,
																						FF.descripcion as FF,
																						INV.codigo_producto as codigo_medicamento
								FROM
										hc_solicitudes_medicamentos_d SMD,
																						medicamentos M,
																						inventarios INV,
																						inventarios_productos INVP,
																						inv_med_cod_forma_farmacologica FF
								WHERE
																				SMD.solicitud_id = '$solicitud'
																						AND SMD.medicamento_id=M.codigo_medicamento
																						AND INV.codigo_producto = M.codigo_medicamento
																						AND INV.empresa_id ='".$_SESSION['Bodegas']['empresa_id']."'
																						AND INV.codigo_producto = INVP.codigo_producto
																						AND FF.cod_forma_farmacologica = M.cod_forma_farmacologica
								ORDER BY SMD.solicitud_id)";
		
		list($dbconn) = GetDBconn();
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - GetMedicamentosSolicitud";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos>0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}	
  
  function GetInsumosYMedicamentosSolicitud($solicitud)
	{
		$query = "(
                SELECT  SMD.solicitud_id,
                        SMD.consecutivo_d,
                        NULL as mezcla_recetada_id,
                        SMD.medicamento_id,
                        SMD.evolucion_id,
                        SMD.cant_solicitada,
                        M.cod_forma_farmacologica,
                        INVP.descripcion as nomMedicamento,
                        FF.descripcion as FF,
                        INV.codigo_producto as codigo_medicamento,
                        'M' AS tipo_producto
                FROM		hc_solicitudes_medicamentos_d SMD,
                        medicamentos M,
                        inventarios INV,
                        inventarios_productos INVP,
                        inv_med_cod_forma_farmacologica FF
								WHERE		SMD.solicitud_id = '$solicitud'
                AND     SMD.medicamento_id=M.codigo_medicamento
                AND     INV.codigo_producto = M.codigo_medicamento
                AND     INV.empresa_id ='".$_SESSION['Bodegas']['empresa_id']."'
                AND     INV.codigo_producto = INVP.codigo_producto
                AND     FF.cod_forma_farmacologica = M.cod_forma_farmacologica
              )
              UNION ALL 
              (
                SELECT  SMD.solicitud_id,
                        SMD.consecutivo_d,
                        NULL as mezcla_recetada_id,
                        SMD.codigo_producto as medicamento_id,
                        NULL as evolucion_id,
                        SMD.cantidad as cant_solicitada,
                        NULL as cod_forma_farmacologica,
                        INVP.descripcion as nomMedicamento,
                        NULL as FF,
                        INV.codigo_producto as codigo_medicamento,
                        'I' AS tipo_producto
                FROM    hc_solicitudes_insumos_d SMD,
                        inventarios INV,
                        inventarios_productos INVP,
                        existencias_bodegas EXIS,
                        unidades UNI
                WHERE   SMD.solicitud_id = '$solicitud' 
                AND		  SMD.codigo_producto = INVP.codigo_producto 
                AND		  INVP.codigo_producto = INV.codigo_producto 
                AND		  INV.empresa_id  = '".$_SESSION['Bodegas']['empresa_id']."' 
                AND		  INV.codigo_producto = EXIS.codigo_producto 
                AND		  INV.empresa_id = EXIS.empresa_id 
                AND		  EXIS.centro_utilidad = '".$_SESSION['Bodegas']['centro_id']."' 
                AND		  EXIS.bodega = '".$_SESSION['Bodegas']['bodega']."' 
                AND		  INVP.unidad_id = UNI.unidad_id
              )
              ORDER BY 11 DESC,1";
		
		list($dbconn) = GetDBconn();
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - GetMedicamentosSolicitud";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos>0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}	
	
	function GetMezclasSolicitud($solicitud,$empresa)
	{
		$query = "(
								SELECT
											SMD.solicitud_id,
											SMD.consecutivo_d,
											SMD.mezcla_recetada_id,
											SMD.medicamento_id,
											SMD.evolucion_id,
											SMD.cant_solicitada,
											M.cod_forma_farmacologica,
											INVP.descripcion as nomMedicamento,
											FF.descripcion as FF,
											M.codigo_medicamento
								FROM hc_solicitudes_medicamentos_mezclas_d SMD,
											medicamentos M,
											inventarios INV,
											inventarios_productos INVP,
											inv_med_cod_forma_farmacologica FF
								WHERE SMD.solicitud_id = '$solicitud'
											AND SMD.medicamento_id=M.codigo_medicamento
											AND INV.codigo_producto = M.codigo_medicamento
											AND INV.empresa_id  = '".$_SESSION['Bodegas']['empresa_id']."'
											AND INV.codigo_producto=INVP.codigo_producto
											AND FF.cod_forma_farmacologica = M.cod_forma_farmacologica
								ORDER BY SMD.solicitud_id);";

		list($dbconn) = GetDBconn();
		
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - GetMezclasSolicitud";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos > 0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}


	function GetInsumosSolicitud($solicitud)
	{
		list($dbconn) = GetDBconn();
		
		$query = "(
									SELECT SMD.solicitud_id,
												SMD.consecutivo_d,
												NULL as mezcla_recetada_id,
												SMD.codigo_producto as medicamento_id,
												NULL as evolucion_id,
												SMD.cantidad as cant_solicitada,
												NULL as cod_forma_farmacologica,
												INVP.descripcion as nomMedicamento,
												NULL as FF,
												INV.codigo_producto as codigo_medicamento
									FROM  hc_solicitudes_insumos_d SMD,
												inventarios INV,
												inventarios_productos INVP,
												existencias_bodegas EXIS,
												unidades UNI
									WHERE SMD.solicitud_id = '$solicitud' 
									AND		SMD.codigo_producto = INVP.codigo_producto 
									AND		INVP.codigo_producto = INV.codigo_producto 
									AND		INV.empresa_id  = '".$_SESSION['Bodegas']['empresa_id']."' 
									AND		INV.codigo_producto = EXIS.codigo_producto 
									AND		INV.empresa_id = EXIS.empresa_id 
									AND		EXIS.centro_utilidad = '".$_SESSION['Bodegas']['centro_id']."' 
									AND		EXIS.bodega = '".$_SESSION['Bodegas']['bodega']."' 
									AND		INVP.unidad_id = UNI.unidad_id
									ORDER BY SMD.solicitud_id)";
							
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - GetInsumosSolicitud";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos>0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}
	
	
	function GetCantidadExistenteBodega($medicamento)
	{
		
		list($dbconn) = GetDBconn();
		
		$query = "SELECT existencia
							FROM existencias_bodegas
							WHERE empresa_id = '".$_SESSION['Bodegas']['empresa_id']."' 
							AND centro_utilidad = '".$_SESSION['Bodegas']['centro_id']."' 
							AND codigo_producto = '".$medicamento."' 
							AND bodega = '".$_SESSION['Bodegas']['bodega']."'";
							
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - GetCantidadExistenteBodega";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos > 0)
			{
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		return $vars;
	}
	
	function GetMedicamentosSimilares($cod_prod,$CantSolicitada)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT 	INV.codigo_producto,
										M.codigo_medicamento,
										INVP.descripcion as nomMedicamento,
										M.cod_concentracion,
										FF.descripcion as FF
						FROM 	Inventarios INV,
									inventarios_productos INVP,
									existencias_bodegas EB,
									medicamentos M,
									inv_med_cod_forma_farmacologica FF,
									(
										SELECT cod_principio_activo 
										FROM medicamentos 
										WHERE codigo_medicamento='".$cod_prod."'
									) PA
						WHERE PA.cod_principio_activo=M.cod_principio_activo
						AND 	INV.empresa_id='".$_SESSION['Bodegas']['empresa_id']."'
						AND 	INV.codigo_producto=INVP.codigo_producto
						AND 	EB.codigo_producto=INV.codigo_producto
						--AND EB.existencia >= ".$CantSolicitada."
						AND EB.empresa_id=INV.empresa_id
						AND EB.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."'
						AND EB.bodega='".$_SESSION['Bodegas']['bodega']."'
						AND M.codigo_medicamento = INV.codigo_producto
						AND FF.cod_forma_farmacologica = M.cod_forma_farmacologica";
						
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{ 
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - GetMedicamentosSimilares";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		while (!$result->EOF)
		{
				$vars[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
		}
		
		return $vars;
	}
	
	
	function rowspanMezclas($solicitud,$mezcla)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT count(*) as contador 
						FROM hc_solicitudes_medicamentos_mezclas_d 
						WHERE solicitud_id='$solicitud' 
						AND mezcla_recetada_id='$mezcla'";
				
		$result = $dbconn->Execute($query);
				
		if($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - rowspanMezclas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$datos=$result->RecordCount();
		if($datos > 0)
		{
				$vars=$result->GetRowAssoc($toUpper=false);
		}
		
		return $vars;
	}
	
	
	function ValidarDocumentoBodega()
	{
		
		list($dbconn) = GetDBconn();
		
		$query="SELECT bodegas_doc_id 
						FROM bodegas_doc_numeraciones 
						WHERE sw_transaccion_medicamentos='1' 
						AND sw_estado='1' 
						AND tipo_movimiento='E'
						AND empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
						AND centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
						AND bodega='".$_SESSION['Bodegas']['bodega']."' 
						ORDER BY bodegas_doc_id";
						
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - ValidarDocumentoBodega";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$concepto=$result->fields[0];
		
		return $concepto;
	}
	
	function DescripcionProductoInv($codigo)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT descripcion
						FROM inventarios_productos
						WHERE codigo_producto='".$codigo."'";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega- DescripcionProductoInv";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$vars=$result->GetRowAssoc($toUpper=false);
		}
		$result->Close();
		return $vars;
	}

	
	function ConfirmacionDespachoDetalleSolicitud($SolicitudId,$TipoSolicitud)
	{
		list($dbconn) = GetDBconn();
		
		if($TipoSolicitud=='I')
		{
			$query="SELECT 	a.codigo_producto,
											a.cantidad,
											b.descripcion,
											a.consecutivo_d,
											d.costo
							FROM 	hc_solicitudes_insumos_d a,
										inventarios_productos b,
										existencias_bodegas c,
										inventarios d
							WHERE a.solicitud_id=".$SolicitudId." 
							AND a.codigo_producto=b.codigo_producto 
							AND b.codigo_producto=c.codigo_producto 
							AND c.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
							AND c.bodega='".$_SESSION['Bodegas']['bodega']."' 
							AND c.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."'
							AND b.codigo_producto=d.codigo_producto
							ORDER BY a.solicitud_id,codigo_producto;
							";
		}
    else if($TipoSolicitud=='D')
    {
      $query="(
                SELECT 	a.solicitud_id,
                        a.codigo_producto,
  											a.cantidad,
  											b.descripcion,
                        '' AS forma,
                        NULL AS evolucion_id,
  											NULL AS ingreso,
  											a.consecutivo_d,
  											d.costo
  							FROM 	hc_solicitudes_insumos_d a,
  										inventarios_productos b,
  										existencias_bodegas c,
  										inventarios d
  							WHERE a.solicitud_id=".$SolicitudId." 
  							AND a.codigo_producto=b.codigo_producto 
  							AND b.codigo_producto=c.codigo_producto 
  							AND c.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
  							AND c.bodega='".$_SESSION['Bodegas']['bodega']."' 
  							AND c.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."'
  							AND b.codigo_producto=d.codigo_producto
              )
							UNION ALL
							(
                SELECT 	a.solicitud_id,
                        a.medicamento_id as codigo_producto,
  											a.cant_solicitada as cantidad,
  											b.descripcion,
  											ff.descripcion as forma,
  											a.evolucion_id,
  											a.ingreso,
  											a.consecutivo_d,
  											e.costo
                FROM 	  hc_solicitudes_medicamentos_d a,
										inventarios_productos b,
										medicamentos c,
										inv_med_cod_forma_farmacologica ff,
										existencias_bodegas d,
										inventarios e
  							WHERE a.solicitud_id=".$SolicitudId." 
  							AND a.medicamento_id=b.codigo_producto 
  							AND b.codigo_producto=c.codigo_medicamento 
  							AND FF.cod_forma_farmacologica=c.cod_forma_farmacologica 
  							AND b.codigo_producto=d.codigo_producto 
  							AND d.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
  							AND d.bodega='".$_SESSION['Bodegas']['bodega']."' 
  							AND d.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."'
  							AND b.codigo_producto=e.codigo_producto
              )
              ORDER BY 1,2;";
    }
    
		else
		{
			$query="SELECT 	a.medicamento_id as codigo_producto,
											a.cant_solicitada as cantidad,
											b.descripcion,
											ff.descripcion as forma,
											a.evolucion_id,
											a.ingreso,
											a.consecutivo_d,
											e.costo
							FROM 	hc_solicitudes_medicamentos_d a,
										inventarios_productos b,
										medicamentos c,
										inv_med_cod_forma_farmacologica ff,
										existencias_bodegas d,
										inventarios e
							WHERE a.solicitud_id=".$SolicitudId." 
							AND a.medicamento_id=b.codigo_producto 
							AND b.codigo_producto=c.codigo_medicamento 
							AND FF.cod_forma_farmacologica=c.cod_forma_farmacologica 
							AND b.codigo_producto=d.codigo_producto 
							AND d.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
							AND d.bodega='".$_SESSION['Bodegas']['bodega']."' 
							AND d.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."'
							AND b.codigo_producto=e.codigo_producto
							ORDER BY a.solicitud_id,a.medicamento_id;";
		}
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0) 
		{
				$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - ConfirmacionDespachoDetalleSolicitud";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		
		if($result->RecordCount()>0)
		{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
		}
		
		return $vars;
	}
	
	
	function MotivosCancelacionDespacho()
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT motivo_id,descripcion
						FROM bodegas_motivos_cancelacion_despacho;";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - MotivosCancelacionDespacho";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while(!$result->EOF)
			{
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}
		return $vars;
	}
	
	
	function ConfirmarDespachoSol($tipoSolicitud,$SolicitudId,$EstacionId,$Ingreso,$Fecha,$Concepto,$usuarioestacion,$datos,$medicaSol)
	{	
		list($dbconn) = GetDBconn();

		$query="SELECT nextval('bodegas_documento_despacho_med_documento_despacho_id_seq');";
		$result = $dbconn->Execute($query);
		$documento=$result->fields[0];

		$query="INSERT INTO bodegas_documento_despacho_med
						(
							documento_despacho_id,
							bodegas_doc_id,
							fecha,
							total_costo,
							usuario_id,
							fecha_registro
						)
						VALUES
						(
							'$documento',
							$Concepto,
							'$Fecha',
							'0',
							".UserGetUID().",
							now()
						)";

		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - ConfirmarDespacho SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		foreach($datos as $key=>$valor)
		{
			list($codigo_producto,$CantidadDespachar,$CantidadPendiente,$costoProducto,$consecutivo_d,$decision,$motivocan,$observacion)=explode("__",$valor);
				
			if($CantidadDespachar > 0)
			{
				if($tipoSolicitud == 'I' || $medicaSol[$codigo_producto] == "I")
				{
					$query="INSERT INTO bodegas_documento_despacho_ins_d
									(
										documento_despacho_id,
										codigo_producto,
										cantidad,
										total_costo,
										consecutivo_solicitud
									)
									VALUES
									(
										$documento,
										'".$codigo_producto."',
										".$CantidadDespachar.",
										$costoProducto,
										$consecutivo_d
									);";
				}
				else
				{
          $query="INSERT INTO bodegas_documento_despacho_med_d
									(
										documento_despacho_id,
										codigo_producto,
										cantidad,
										total_costo,
										consecutivo_solicitud
									)
									VALUES
									(
										$documento,
										'".$codigo_producto."',
										".$CantidadDespachar.",
										$costoProducto,
										$consecutivo_d
									);";
				}
				
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - ConfirmarDespacho SQL 2 - $tipoSolicitud";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$result = $dbconn->Execute("COMMIT;");
			}
		}
	 	
		$this->TotalizarDocDepacho($documento,$tipoSolicitud);
				
		$query="UPDATE hc_solicitudes_medicamentos 
						SET sw_estado='1',
						documento_despacho='".$documento."' 
						WHERE solicitud_id='".$SolicitudId."' 
						AND tipo_solicitud='".$tipoSolicitud."'";
			
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - ConfirmarDespacho SQL 3 ";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$result = $dbconn->Execute("COMMIT;");
		
		$b1=true;
		$b2=true;
		foreach($datos as $key=>$valor)
		{
			list($codigo_producto,$CantidadDespachar,$CantidadPendiente,$costoProducto,$consecutivo_d,$decision,$motivocan,$observacion)=explode("__",$valor);
			
			list($decisionC,$codigo_pro)=explode("@",$decision);

			if(empty($codigo_pro))
				$codigo_pro=$codigo_producto;
			
			if($decisionC==1)
			{
				if($b1)
				{
					$query="SELECT nextval('hc_solicitudes_medicamentos_solicitud_id_seq');";
					$result = $dbconn->Execute($query);
					$solicitudActiva=$result->fields[0];
					
					$query="INSERT INTO hc_solicitudes_medicamentos
									(
										solicitud_id,
										ingreso,
										bodega,
										empresa_id,
										centro_utilidad,
										usuario_id,
										sw_estado,
										fecha_solicitud,
										estacion_id,
										tipo_solicitud
									)
									VALUES
									(
										'$solicitudActiva',
										'".$Ingreso."',
										'".$_SESSION['Bodegas']['bodega']."',
										'".$_SESSION['Bodegas']['empresa_id']."',
										'".$_SESSION['Bodegas']['centro_id']."',
										'".$usuarioestacion."',
										'0',
										'".date("Y-m-d H:i:s")."',
										'".$EstacionId."',
										'".$tipoSolicitud."'
									);";
					
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Guardar en la Base de Datos Solicitud_Medicamentos_PorBodega - ConfirmarDespacho SQL 4";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					$b1=false;
				}
				$query="";
				if($tipoSolicitud=='I' || $medicaSol[$codigo_pro] == "I")
				{
					$query="INSERT INTO hc_solicitudes_insumos_d
									(
										codigo_producto,
										cantidad,
										solicitud_id
									)
									VALUES
									(
										'$codigo_pro',
										'$CantidadPendiente',
										'$solicitudActiva'
									);";
				}
				else
				{
					$query="INSERT INTO hc_solicitudes_medicamentos_d
									(
										solicitud_id,
										medicamento_id,
										cant_solicitada,
										ingreso
									)
									VALUES
									(
										'$solicitudActiva',
										'$codigo_pro',
										'$CantidadPendiente',
										'".$Ingreso."'
									);";
				}
	
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
							$this->error = "Error al Guardar en la Base de Datos Solicitud_Medicamentos_PorBodega - ConfirmarDespacho SQL 5";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
				}
				$result = $dbconn->Execute("COMMIT;");
			}
			
			if($decisionC==2)
			{
				if(!$motivocan)
					$motivocan="NULL";
				
				if(!$observacion)
					$observacion="NULL";
				else
					$observacion="'$observacion'";
				
				if($b2)
				{
					$query="SELECT nextval('hc_solicitudes_medicamentos_solicitud_id_seq');";
					$result = $dbconn->Execute($query);
					$solicitudInactiva=$result->fields[0];
					
					$query="INSERT INTO hc_solicitudes_medicamentos
									(
										solicitud_id,
										ingreso,
										bodega,
										empresa_id,
										centro_utilidad,
										usuario_id,
										sw_estado,
										fecha_solicitud,
										estacion_id,
										tipo_solicitud
									)
									VALUES
									(
										'$solicitudInactiva',
										'".$Ingreso."',
										'".$_SESSION['Bodegas']['bodega']."',
										'".$_SESSION['Bodegas']['empresa_id']."',
										'".$_SESSION['Bodegas']['centro_id']."',
										'".$usuarioestacion."',
										'3',
										'".date("Y-m-d H:i:s")."',
										'".$EstacionId."',
										'".$tipoSolicitud."'
									);";
									
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Guardar en la Base de Datos Solicitud_Medicamentos_PorBodega - ConfirmarDespacho SQL 6";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					$b2=false;
				}
				
				if($tipoSolicitud=='I' || $medicaSol[$codigo_pro] == "I")
				{
					$query="SELECT nextval('hc_solicitudes_insumos_d_consecutivo_d_seq')";
					$result = $dbconn->Execute($query);
					$consec=$result->fields[0];
					
					$query="INSERT INTO hc_solicitudes_insumos_d
									(
										consecutivo_d,
										codigo_producto,
										cantidad,
										solicitud_id,
										codigo_producto_despachado
									)
									VALUES
									(
										'$consec',
										'$codigo_pro',
										'$CantidadPendiente',
										'$solicitudInactiva',
										'$codigo_producto'
									);";
					
					$query.="	INSERT INTO hc_solicitudes_insumos_motivos_cancela
										(
											consecutivo_d,
											motivo_id,
											observaciones
										)
										VALUES
										(
											'$consec',
											".$motivocan.",
											".$observacion."
										);";
				}
				else
				{
					$query="SELECT nextval('hc_solicitudes_medicamentos_d_consecutivo_d_seq')";
					$result = $dbconn->Execute($query);
					$consec=$result->fields[0];
											
					$query="INSERT INTO hc_solicitudes_medicamentos_d
									(
										consecutivo_d,
										solicitud_id,
										medicamento_id,
										cant_solicitada,
										ingreso,
										codigo_producto_despachado
									)
									VALUES
									(
										'$consec',
										'$solicitudInactiva',
										'$codigo_pro',
										'$CantidadPendiente',
										'".$Ingreso."',
										'$codigo_producto'
									);";
											
					$query.="	INSERT INTO hc_solicitudes_medicamentos_motivos_cancela
										(
											consecutivo_d,
											motivo_id,
											observaciones
										)
										VALUES
										(
											'$consec',
											".$motivocan.",
											".$observacion."
										);";
				}
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Guardar en la Base de Datos Solicitud_Medicamentos_PorBodega - ConfirmarDespacho SQL 7";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$result = $dbconn->Execute("COMMIT;");
			}
		}
		
		return true;
	}
	
	
	function TotalizarDocDepacho($Documento,$tipoSolicitud)
	{
		list($dbconn) = GetDBconn();
			
		if($tipoSolicitud!='I')
		{
			$query="SELECT sum(total_costo*cantidad) as tcosto 
							FROM bodegas_documento_despacho_med_d
							WHERE documento_despacho_id='$Documento'";
		}
		else
		{
			$query="SELECT sum(total_costo*cantidad) as tcosto 
							FROM bodegas_documento_despacho_ins_d
							WHERE documento_despacho_id='$Documento'";
		}
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - TotalizarDocDepacho 1";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		
		$query="UPDATE bodegas_documento_despacho_med 
						SET total_costo='".$vars['tcosto']."' 
						WHERE documento_despacho_id='$Documento'";
						
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - TotalizarDocDepacho 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		$result = $dbconn->Execute("COMMIT;");
		
		return true;
	}
	
	function ProductosDevolucion($Documento)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT 	b.codigo_producto,
										b.cantidad,
										d.descripcion,
										d.sw_control_fecha_vencimiento,
										b.consecutivo
						FROM 	inv_solicitudes_devolucion a,
									inv_solicitudes_devolucion_d b,
									inventarios c,
									inventarios_productos d,
									existencias_bodegas e
						WHERE a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
						AND a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
						AND a.bodega='".$_SESSION['Bodegas']['bodega']."' 
						AND a.documento='$Documento' 
						AND a.documento=b.documento 
						AND c.empresa_id=a.empresa_id 
						AND c.codigo_producto=b.codigo_producto 
						AND d.codigo_producto=b.codigo_producto 
						AND a.empresa_id=e.empresa_id 
						AND a.centro_utilidad=e.centro_utilidad 
						AND a.bodega=e.bodega 
						AND b.codigo_producto=e.codigo_producto 
						AND b.estado='0'";

		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - ProductosDevolucion";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}
	
	function SumaFechasLotesProductosDevol($consecutivo,$codigoProducto)
	{
		
		list($dbconn) = GetDBconn();
		
		$query="SELECT sum(cantidad) as suma 
						FROM inv_solicitudes_devolucion_fvencimiento_lotes 
						WHERE consecutivo='$consecutivo' 
						AND codigo_producto='$codigoProducto'";
						
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - SumaFechasLotesProductosDevol";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}

		return $vars;
	}
	
	function FechasLotesProductosDevol($consecutivo,$codigoProducto)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT 	fecha_vencimiento,
										lote,
										cantidad 
						FROM 		inv_solicitudes_devolucion_fvencimiento_lotes 
						WHERE 	consecutivo='$consecutivo' 
						AND 		codigo_producto='$codigoProducto'";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - FechasLotesProductosDevol";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
						$vars[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
				}
			}
		}
		return $vars;
	}
	
	function RealizarDevolucionMedicamentos($SelectDatos,$EstacionId,$NombreEstacion,$Documento,$Fecha,$Ingreso,$observaciones)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT 	c.numerodecuenta,
										c.plan_id 
										FROM cuentas c 
										WHERE c.ingreso='$Ingreso' 
										AND (c.estado='1' OR c.estado='2');";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - RealizarDevolucionMedicamentos SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$numeroDeCuenta=$result->fields[0];
		$PlanId=$result->fields[1];
		
		if(empty($PlanId) || empty($numeroDeCuenta))
		{
			$this->frmError["MensajeError"]= "VERIFICAR CUENTA DE PACIENTE ESTE ACTIVA";
			return false;
		}
		
		$ProductosDocumento=$this->ProductosDevolucion($Documento);
		
		foreach($SelectDatos as $keyD=>$valorD)
		{
			$cadena=explode('__',$valorD);
			
			$CodigoPro=$cadena[0];
			$Cantidad=$cadena[1];
			$numeroComsecutivo=$cadena[2];
			
			$datos=$this->FechasLotesProductosDevol($numeroComsecutivo,$CodigoPro);
			$suma=$this->SumaFechasLotesProductosDevol($numeroComsecutivo,$CodigoPro);
			
			if($datos)
			{
				$this->frmError["MensajeError"]="Es obligatoria la fecha de vencimiento y el lote para el producto con codigo".' '.$valor['codigo_producto'];
				return false;
			}
			elseif($suma['suma']<$valor['cantidad'])
			{
				$this->frmError["MensajeError"]="La Suma de las Cantidades Insertadas es menor a la Cantidad Total del Producto con Codigo".' '.$valor['codigo_producto'];
				return false;
			}
		}
		
		$query="SELECT 	a.departamento,
										b.empresa_id,
										b.centro_utilidad,
										b.servicio
						FROM 		estaciones_enfermeria a,
										departamentos b
						WHERE  	a.estacion_id='".$EstacionId."' 
						AND 		a.departamento=b.departamento";
						
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - RealizarDevolucionMedicamentos SQL 2";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		
		$query="SELECT bodegas_doc_id 
						FROM bodegas_doc_numeraciones 
						WHERE sw_transaccion_medicamentos='1' 
						AND sw_estado='1' 
						AND tipo_movimiento='I'
						AND empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
						AND centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
						AND bodega='".$_SESSION['Bodegas']['bodega']."' 
						ORDER BY bodegas_doc_id;";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Solicitud_Medicamentos_PorBodega - RealizarDevolucionMedicamentos SQL 3";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$concepto=$result->fields[0];
		
		if(empty($concepto))
		{
			$this->frmError["MensajeError"]= "NO EXISTE UN DOCUMENTO DE BODEGA CREADO PARA ESTE TIPO DE MOVIMIENTOS";
			return false;
		}

		$numeracion=$this->AsignarNumeroDocumentoDespacho($concepto);
		$numeracion=$numeracion['numeracion'];
		
		$codigoAgrupamiento=$this->InsertarBodegasDocumentos($concepto,$numeracion,date("Y/m/d"),'','DIMD');
		
		foreach($SelectDatos as $keyD=>$valorD)
		{
			$cadena=explode('__',$valorD);
			
			$CodigoPro=$cadena[0];
			$Cantidad=$cadena[1];
			$numeroComsecutivo=$cadena[2];
			
			$costoProducto=$this->HallarCostoProducto($_SESSION['Bodegas']['empresa_id'],$CodigoPro);
			$query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
			$result=$dbconn->Execute($query);
			$Consecutivo=$result->fields[0];
			
			$InsertarDocumentod=$this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$CodigoPro,$Cantidad,$costoProducto);
			
			if($InsertarDocumentod==1)
			{
				$trans=$this->InsertarBodegasDocumentosdCober($Consecutivo,date('Y-m-d H:i:s'),$numeroDeCuenta,$CodigoPro,$Cantidad,$valorD['precio'],$codigoAgrupamiento,$PlanId,$vars['servicio'],$vars['empresa_id'],$vars['centro_utilidad'],$vars['departamento'],'1','DIMD');

				$query="SELECT existencia 
								FROM existencias_bodegas 
								WHERE empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
								AND centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
								AND bodega='".$_SESSION['Bodegas']['bodega']."' 
								AND codigo_producto='$CodigoPro'";
				
				$result = $dbconn->Execute($query);
				$Existencias=$result->fields[0];
				$ModifExist=$this->ModificacionExistenciasResta($Existencias,$Cantidad,$_SESSION['Bodegas']['empresa_id'],$_SESSION['Bodegas']['centro_id'],$_SESSION['Bodegas']['bodega'],$CodigoPro);
				
				$query="SELECT cantidad_acum 
								FROM hc_bodega_paciente 
								WHERE ingreso='$Ingreso' 
								AND medicamento_id='$CodigoPro';";
				$result = $dbconn->Execute($query);
				
				$cantidaArestar=$result->fields[0];
				
				if($cantidaArestar==$Cantidad)
				{
					$query="DELETE 
									FROM hc_bodega_paciente 
									WHERE ingreso='$Ingreso' 
									AND medicamento_id='$CodigoPro'";
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0) 
					{
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					$result = $dbconn->Execute("COMMIT;");
				}
				else
				{
					$cantidadTotalMed=$cantidaArestar-$Cantidad;

					$query="UPDATE hc_bodega_paciente 
									SET cantidad_acum='$cantidadTotalMed' 
									WHERE ingreso='$Ingreso'
									AND medicamento_id='$CodigoPro'";
									
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					$result = $dbconn->Execute("COMMIT;");
				}
				
				$query="INSERT INTO bodegas_documentos_d_fvencimiento_lotes
								(
									fecha_vencimiento,
									lote,
									saldo,
									cantidad,
									empresa_id,
									centro_utilidad,
									bodega,
									codigo_producto,
									consecutivo
								)
								(
									SELECT 	fecha_vencimiento,
													lote,
													'0',
													cantidad,
												'".$_SESSION['Bodegas']['empresa_id']."',
												'".$_SESSION['Bodegas']['centro_id']."',
												'".$_SESSION['Bodegas']['bodega']."',
												codigo_producto,
												'$Consecutivo' 
									FROM inv_solicitudes_devolucion_fvencimiento_lotes 
									WHERE consecutivo='$numeroComsecutivo' 
									AND codigo_producto='$CodigoPro'
								);";
				
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				
				$query="UPDATE inv_solicitudes_devolucion_d 
                            SET estado='2' 
                            WHERE documento='".$Documento."'
                            AND codigo_producto='$CodigoPro';";
				
				$result = $dbconn->Execute($query);
			
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Guardar en Bases de Datos - inv_solicitudes_devolucion_d SQL estado[1]";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$result = $dbconn->Execute("COMMIT;");
			}
		}
		
		$totalizCostoDoc=$this->TotalizarCostoDocumento($numeracion,$concepto);
		
		$query="SELECT count(*)
						FROM inv_solicitudes_devolucion_d 
						WHERE documento='$Documento'
						AND estado='0'";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Guardar en la Base de Datos - inv_solicitudes_devolucion_d estado [1]";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$t=$result->fields[0];
		
		if($t==0)
		{
			$query="UPDATE inv_solicitudes_devolucion 
							SET estado='1',
							bodegas_doc_id='$concepto',
							numeracion='$numeracion' 
							WHERE documento='$Documento'";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Guardar en la Base de Datos inv_solicitudes_devolucion estado [1]";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		
		$result = $dbconn->Execute("COMMIT;");
		return true;
	}
	
	
	function MotivosCancelacionDevolucion()
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT 	motivo_id,
										descripcion
						FROM 		inv_solicitudes_devolucion_motivos_cancelacion;";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}
	
	function GuardarCancelacionDevoluciones($SelectDatos,$documento,$motivo,$observa)
	{
		list($dbconn) = GetDBconn();

		foreach($SelectDatos as $key=>$valor)
		{
			(list($codigo,$cantidad,$consecutivo)=explode('__',$valor));
			
			$query.="	INSERT INTO inv_solicitudes_devoluciones_canceladas
								(
									consecutivo,
									motivo_id,
									observaciones
								)
								VALUES
								(
									'$consecutivo',
									'".$motivo."',
									'".$observa."'
								);";
								
			$query.="UPDATE inv_solicitudes_devolucion_d 
							SET estado='1' 
							WHERE consecutivo='".$consecutivo."';";
		}
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Guardar en Bases de Datos - GuardarCancelacionDevoluciones SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$query="SELECT * 
							FROM inv_solicitudes_devolucion_d 
							WHERE documento='".$documento."' 
							AND estado='0'";
							
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo - GuardarCancelacionDevoluciones SQL 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$datos=$result->RecordCount();
				if($datos<1)
				{
					$query="UPDATE inv_solicitudes_devolucion 
									SET estado='2' 
									WHERE documento='".$documento."'";
					
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Cargar el Modulo - GuardarCancelacionDevoluciones SQL 3";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					return true;
				}
			}
		}
		$result->Close();
		
		return true;
	}
	

		function AsignarNumeroDocumentoDespacho($concepto,&$dbconn){
			if(!is_object($dbconn)){
					list($dbconn) = GetDBconn();
			}
			if((!empty($concepto))){
					$sql="BEGIN WORK;  LOCK TABLE bodegas_doc_numeraciones IN ROW EXCLUSIVE MODE";
					$result = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0){
							die(MsgOut("Error al iniciar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
							return false;
					}
					$sql="UPDATE bodegas_doc_numeraciones set numeracion=numeracion + 1
											WHERE  bodegas_doc_id= $concepto";
					$result = $dbconn->Execute($sql);
					if($dbconn->ErrorNo() != 0){
							die(MsgOut("Error al actualizar numeracion","Error DB : " . $dbconn->ErrorMsg()));
							return false;
					}
					if($dbconn->Affected_Rows() == 0){
							die(MsgOut("Error al actualizar numeracion","El tipo de numeracion '$concepto' no existe."));
							return false;
					}
					$sql="SELECT numeracion FROM bodegas_doc_numeraciones WHERE bodegas_doc_id=$concepto";
					$result = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0){
							die(MsgOut("Error al actualizar numeracion","Error DB : " . $dbconn->ErrorMsg()));
							return false;
					}
					if($result->EOF){
							die(MsgOut("Error al actualizar numeracion","El tipo de numeracion '$concepto' no existe."));
							return false;
					}
					list($numerodoc['numeracion'])=$result->fetchRow();
					return $numerodoc;
			}
	}
	
	
	function TotalizarCostoDocumento($numeracion,$concepto)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT sum(total_costo*cantidad) as sumaCosto 
						FROM bodegas_documentos_d  
						WHERE bodegas_doc_id='$concepto' 
						AND numeracion='$numeracion'";
		
		$result = $dbconn->Execute($query);
		$sumaCosto=$result->fields[0];
		
		$query="UPDATE bodegas_documentos 
						SET total_costo='$sumaCosto' 
						WHERE bodegas_doc_id='$concepto' 
						AND numeracion='$numeracion'";
						
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Guardar en la Base de Datos - TotalizarCostoDocumento";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$result->Close();
		return true;
	}
		
	function HallarCostoProducto($Empresa,$Codigo)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT costo FROM inventarios 
						WHERE empresa_id='$Empresa' 
						AND codigo_producto='$Codigo'";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() !=0 )
		{
			$this->error = "Error al Guardar en la Base de Datos - HallarCostoProducto";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datosCont=$result->RecordCount();
			if($datosCont)
			{
				$vars=$result->GetRowAssoc($toUpper=false);
				$costoProducto=$vars['costo'];
			}
		}
		return $costoProducto;
	}

	function InsertarBodegasDocumentos($concepto,$numeracion,$Fecha,$observaciones,$tipoCargo)
	{
		list($dbconn) = GetDBconn();
		
		$query = "INSERT INTO bodegas_documentos
							(
								bodegas_doc_id,
								numeracion,
								fecha,
								total_costo,
								transaccion,
								observacion,
								usuario_id,
								fecha_registro
							)
							VALUES
							(
								'$concepto',
								'$numeracion',
								'$Fecha','0',
								NULL,
								'$observaciones',
								'".UserGetUID()."',
								'".date("Y-m-d H:i:s")."'
							);";

		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Guardar en la Base de Datos SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$query = "SELECT nextval('cuentas_codigos_agrupamiento_codigo_agrupamiento_id_seq');";
			$result = $dbconn->Execute($query);
			$codigoAgrupamiento=$result->fields[0];
			
			if($tipoCargo=='DIMD')
			{
				$descrip='DEVOLUCION DE MEDICAMENTOS';
			}else
			{
				$descrip='DESCARGO DE MEDICAMENTOS';
			}
			if(!empty($_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']))
			{
				$NoLiquidacion="'".$_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']."'";
			}
			else
			{
				$NoLiquidacion='NULL';
			}
			
			$query = "INSERT INTO cuentas_codigos_agrupamiento
								(
									codigo_agrupamiento_id,
									descripcion,
									bodegas_doc_id,
									numeracion,
									cuenta_liquidacion_qx_id
								)
								VALUES
								(
									'$codigoAgrupamiento',
									'".$descrip."',
									'$concepto',
									'$numeracion',
									$NoLiquidacion
								);";
			
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al Guardar en la Base de Datos SQL 2";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			else
			{
					return $codigoAgrupamiento;
			}
		}
		return '0';
	}
	
	function InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$Codigo,$Cantidad,$costoProducto)
	{
		list($dbconn) = GetDBconn();
		
		$query = "INSERT INTO bodegas_documentos_d
							(
								consecutivo,
								codigo_producto,
								cantidad,
								total_costo,
								bodegas_doc_id,
								numeracion
							)
							VALUES
							(
								'$Consecutivo',
								'$Codigo',
								'$Cantidad',
								'$costoProducto',
								'$concepto',
								'$numeracion'
							);";

		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Guardar en la Base de Datos - InsertarBodegasDocumentosd";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->GuardarNumeroDocumento($commit=false);
			return false;
		}
		else
		{
			return 1;
		}
	}

	function InsertarBodegasDocumentosdCober($Consecutivo,$fechaCargo,$cuenta,$codigo,$cantidad,$precio,$codigoAgrupamiento,$planId,$Servicio,$Empresa,$CentroUtili,$departamento,$devolucion,$tipoCargo)
	{
	
			IncludeClass("LiquidacionCargosInventario");
			$objLIM = new LiquidacionCargosInventario;
			
			$datosAdicionales['cuenta'] = $cuenta;
			$datosAdicionales['plan_id'] = $planId;
			$datosAdicionales['precio'] = $precio;
			$datosAdicionales['departamento'] = $departamento;
			$datosAdicionales['servicio'] = NULL;
			$datosAdicionales['evolucion_id'] = NULL;
			$datosAdicionales['descuento_manual_empresa'] = 0;
			$datosAdicionales['descuento_manual_paciente'] = 0;
			$datosAdicionales['aplicar_descuento_empresa'] = false;
			$datosAdicionales['aplicar_descuento_paciente'] = false;
			
			list($dbconn) = GetDBconn();
			$varsCuenDet=$objLIM->GetLiquidacionProducto($codigo, $Empresa, $cantidad, $datosAdicionales);
			//$varsCuenDet=LiquidarIyM($cuenta,$codigo,$cantidad,$descuento_manual_empresa=0,$descuento_manual_paciente=0,$aplicar_descuento_empresa=false,$aplicar_descuento_paciente=false,NULL,$planId,$autorizar=false,$departamento,$Empresa);
			$autorizacion_int=$varsCuenDet['autorizacion_int'];
			if(!$autorizacion_int){$autorizacion_int1='NULL';}else{$autorizacion_int1="'$autorizacion_int'";}
			$autorizacion_ext=$varsCuenDet['autorizacion_ext'];
			if(!$autorizacion_ext){$autorizacion_ext1='NULL';}else{$autorizacion_ext1="'$autorizacion_ext'";}

			$query="SELECT nextval('cuentas_detalle_transaccion_seq')";
			$result=$dbconn->Execute($query);
			$Transaccion=$result->fields[0];
			if($devolucion=='1'){
				$valor_cargo=($varsCuenDet['valor_cargo']*-1);
					$valor_nocubierto=($varsCuenDet['valor_nocubierto']*-1);
					$valor_cubierto=($varsCuenDet['valor_cubierto']*-1);
			}else{
		$valor_cargo=$varsCuenDet['valor_cargo'];
					$valor_nocubierto=$varsCuenDet['valor_nocubierto'];
					$valor_cubierto=$varsCuenDet['valor_cubierto'];
			}
			if(empty($tipoCargo)){
		$tipoCargo='IMD';
			}
				$query = "INSERT INTO cuentas_detalle(transaccion,
														empresa_id,centro_utilidad,
														numerodecuenta,departamento,tarifario_id,
														cargo,cantidad,precio,
														porcentaje_descuento_empresa,valor_cargo,valor_nocubierto,
														valor_cubierto,facturado,fecha_cargo,
														usuario_id,fecha_registro,sw_liq_manual,
														valor_descuento_empresa,valor_descuento_paciente,porcentaje_descuento_paciente,
														servicio_cargo,autorizacion_int,autorizacion_ext,
														porcentaje_gravamen,sw_cuota_paciente,sw_cuota_moderadora,
														codigo_agrupamiento_id,consecutivo,cargo_cups,sw_cargue,departamento_al_cargar)VALUES
														('$Transaccion','$Empresa','$CentroUtili',
														$cuenta,'$departamento','SYS',
														'$tipoCargo','$cantidad','".$varsCuenDet['precio_plan']."',
														'".$varsCuenDet['porcentaje_descuento_empresa']."','".$valor_cargo."','".$valor_nocubierto."',
														'".$valor_cubierto."','".$varsCuenDet['facturado']."','$fechaCargo',
														'".UserGetUID()."','".date('Y-m-d H:i:s')."','0',
														'".$varsCuenDet['valor_descuento_empresa']."','".$varsCuenDet['valor_descuento_paciente']."','".$varsCuenDet['porcentaje_descuento_paciente']."',
														'$Servicio',$autorizacion_int1,$autorizacion_ext1,
														'".$varsCuenDet['porcentaje_gravamen']."','".$varsCuenDet['sw_cuota_paciente']."','".$varsCuenDet['sw_cuota_moderadora']."',
																																								'$codigoAgrupamiento','$Consecutivo',NULL,'3','$departamento')";
	
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Guardar en la Base de Datos - InsertarBodegasDocumentosdCober SQL 1";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}else{
					//Falta Validar lo de la Cuenta estado
						$query = "SELECT a.transaccion,
												a.cargo,
												a.cantidad,
												a.departamento_al_cargar
										FROM cuentas_detalle a, bodegas_documentos_d b
										WHERE a.numerodecuenta='$cuenta' AND a.consecutivo=b.consecutivo AND
										b.codigo_producto='$codigo' AND a.consecutivo <> '$Consecutivo' AND a.sw_liq_manual='0'";
	
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
								$this->error = "Error en la Base de Datos - InsertarBodegasDocumentosdCober SQL 2";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}else{
				$datos=$result->RecordCount();
								if($datos){
										$i=0;
										while(!$result->EOF){
						$vars[$i]=$result->GetRowAssoc($toUpper=false);
												
												$datosAdicionales['cuenta'] = $cuenta;
												$datosAdicionales['plan_id'] = $planId;
												$datosAdicionales['precio'] = $precio;
												$datosAdicionales['servicio'] = NULL;
												$datosAdicionales['evolucion_id'] = NULL;
												$datosAdicionales['descuento_manual_empresa'] = 0;
												$datosAdicionales['descuento_manual_paciente'] = 0;
												$datosAdicionales['aplicar_descuento_empresa'] = false;
												$datosAdicionales['aplicar_descuento_paciente'] = false;
												$datosAdicionales['departamento'] = $vars[$i]['departamento_al_cargar'];
												//$varsCuenDet=LiquidarIyM($cuenta,$codigo,$vars[$i]['cantidad'],$descuento_manual_empresa=0,$descuento_manual_paciente=0,$aplicar_descuento_empresa=false,$aplicar_descuento_paciente=false,NULL,$planId,$autorizar=false,$vars[$i]['departamento_al_cargar'],$Empresa);
												$varsCuenDet=$objLIM->GetLiquidacionProducto($codigo, $Empresa, $vars[$i]['cantidad'], $datosAdicionales);
			
												if($vars[$i]['cargo']=='DIMD'){
							$valor_cargo=($varsCuenDet['valor_cargo']*-1);
														$valor_nocubierto=($varsCuenDet['valor_nocubierto']*-1);
														$valor_cubierto=($varsCuenDet['valor_cubierto']*-1);
												}else{
							$valor_cargo=$varsCuenDet['valor_cargo'];
														$valor_nocubierto=$varsCuenDet['valor_nocubierto'];
							$valor_cubierto=$varsCuenDet['valor_cubierto'];
												}
												$query = "UPDATE cuentas_detalle
												SET precio='".$varsCuenDet['precio_plan']."',
												porcentaje_descuento_empresa='".$varsCuenDet['porcentaje_descuento_empresa']."',
												valor_cargo='".$valor_cargo."',valor_nocubierto='".$valor_nocubierto."',
												valor_cubierto='".$valor_cubierto."',
												facturado='".$varsCuenDet['facturado']."',valor_descuento_empresa='".$varsCuenDet['valor_descuento_empresa']."',
												valor_descuento_paciente='".$varsCuenDet['valor_descuento_paciente']."',porcentaje_descuento_paciente='".$varsCuenDet['porcentaje_descuento_paciente']."',
												porcentaje_gravamen='".$varsCuenDet['porcentaje_gravamen']."',sw_cuota_paciente='".$varsCuenDet['sw_cuota_paciente']."',
												sw_cuota_moderadora='".$varsCuenDet['sw_cuota_moderadora']."'
												WHERE transaccion='".$vars[$i]['transaccion']."'";
	
						$result1 = $dbconn->Execute($query);
												if($dbconn->ErrorNo() != 0){
														$this->error = "Error al Actualizar en la Base de Datos - InsertarBodegasDocumentosdCober SQL 3";
														$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														return false;
												}
												$result->MoveNext();
												$i++;
										}
								}
						}
				return $Transaccion;
			}
			return false;
    }
		
		
		function ModificacionExistenciasResta($Existencias,$cantidadDevol,$Empresa,$CentroUtili,$BodegaId,$Codigo)
		{
			list($dbconn) = GetDBconn();
			
			$ExistenciasTotal= $Existencias + $cantidadDevol;
			
			$query="UPDATE existencias_bodegas 
							SET existencia='$ExistenciasTotal' 
							WHERE empresa_id='$Empresa' 
							AND centro_utilidad='$CentroUtili' 
							AND bodega='$BodegaId' 
							AND codigo_producto='$Codigo'";
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->GuardarNumeroDocumento($commit=false);
					return false;
			}
			return true;
		}
	
  function ModificacionExistencias($Existencias,$cantidadSolici,$Empresa,$CentroUtili,$BodegaId,$Codigo)
	{
		list($dbconn) = GetDBconn();
		
		$ExistenciasTotal= $Existencias - $cantidadSolici;
		
		$query="UPDATE existencias_bodegas 
						SET existencia='$ExistenciasTotal' 
						WHERE empresa_id='$Empresa' 
						AND centro_utilidad='$CentroUtili' 
						AND bodega='$BodegaId' 
						AND codigo_producto='$Codigo'";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Actualizar en la Base de Datos - ModificacionExistencias";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		return 1;
	}
	
	function DatosSolicitudesDepartamento($departamento)
	{
		list($dbconn) = GetDBconn();
		
		$query="
						(
							SELECT 	i.tipo_id_paciente||' '||i.paciente_id,
											a.solicitud_id,
											det.consecutivo_d,
											a.estacion_id,
											a.fecha_solicitud,
											a.ingreso,
											d.nombre as usuarioestacion,
											a.usuario_id,
											c.descripcion as deptoestacion,
											e.rango,
											k.tipo_afiliado_nombre as tipo_afiliado_id,
											h.plan_descripcion,
											l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
											j.cama,
											j.pieza,
											a.tipo_solicitud,
											b.descripcion as nomestacion,
											det.medicamento_id as codigo_producto,
											invp.descripcion_abreviada as desmed,
											det.cant_solicitada,
											bu.descripcion as ubicacion,
											u.abreviatura,
											exis.existencia
							FROM 		hc_solicitudes_medicamentos a,
											estaciones_enfermeria b,
											departamentos c,
											system_usuarios d,
											cuentas e
							LEFT JOIN movimientos_habitacion f 
							ON
							(
								e.numerodecuenta=f.numerodecuenta 
								AND f.fecha_egreso is NULL
							)
							LEFT JOIN camas j 
							ON
							(
								f.cama=j.cama
							),
							planes h,
							ingresos i,
							tipos_afiliado k,
							pacientes l,
							hc_solicitudes_medicamentos_d det,
							inventarios_productos invp
							LEFT JOIN existencias_bodegas exis 
							ON 
							(
								invp.codigo_producto=exis.codigo_producto 
								AND exis.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
								AND exis.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
								AND exis.bodega='".$_SESSION['Bodegas']['bodega']."'
							)
							LEFT JOIN bodegas_ubicaciones bu 
							ON
							(
								exis.ubicacion_id=bu.ubicacion_id
							),
							unidades u
							WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."' 
							AND a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
							AND a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
							AND a.sw_estado='0' 
							AND a.estacion_id=b.estacion_id 
							AND b.departamento='".$departamento."'
							AND b.departamento=c.departamento 
							AND a.usuario_id=d.usuario_id 
							AND a.ingreso=e.ingreso 
							AND (e.estado='1' OR e.estado='2')
							AND a.ingreso=i.ingreso 
							AND e.plan_id=h.plan_id 
							AND k.tipo_afiliado_id=e.tipo_afiliado_id 
							AND i.tipo_id_paciente=l.tipo_id_paciente 
							AND i.paciente_id=l.paciente_id 
							AND a.solicitud_id=det.solicitud_id 
							AND det.medicamento_id=invp.codigo_producto 
							AND invp.unidad_id=u.unidad_id
							ORDER BY l.tipo_id_paciente,l.paciente_id,a.fecha_solicitud,a.solicitud_id,det.medicamento_id 
						)
						UNION
						(
							SELECT i.tipo_id_paciente||' '||i.paciente_id,
									a.solicitud_id,
									det.consecutivo_d,
									a.estacion_id,
									a.fecha_solicitud,
									a.ingreso,
									d.nombre as usuarioestacion,
									a.usuario_id,
									c.descripcion as deptoestacion,
									e.rango,
									k.tipo_afiliado_nombre as tipo_afiliado_id,
									h.plan_descripcion,
									l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
									j.cama,
									j.pieza,
									a.tipo_solicitud,
									b.descripcion as nomestacion,
									det.codigo_producto,
									invp.descripcion_abreviada as desmed,
									det.cantidad as cant_solicitada,
									bu.descripcion as ubicacion,
									u.abreviatura,
									exis.existencia
							FROM hc_solicitudes_medicamentos a,
									estaciones_enfermeria b,
									departamentos c,
									system_usuarios d,
									cuentas e
									LEFT JOIN movimientos_habitacion f 
									ON
									(
										e.numerodecuenta=f.numerodecuenta 
										AND f.fecha_egreso is NULL
									)
									LEFT JOIN camas j 
									ON
									(
										f.cama=j.cama
									),
									planes h,
									ingresos i,
									tipos_afiliado k,
									pacientes l,
									hc_solicitudes_insumos_d det,
									inventarios_productos invp
									LEFT JOIN existencias_bodegas exis 
									ON 
									(
										invp.codigo_producto=exis.codigo_producto 
										AND exis.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
										AND exis.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
										AND exis.bodega='".$_SESSION['Bodegas']['bodega']."'
									)
									LEFT JOIN bodegas_ubicaciones bu 
									ON 
									(
										exis.ubicacion_id=bu.ubicacion_id
									),
									unidades u
							WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."' 
							AND a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
							AND a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
							AND a.sw_estado='0' 
							AND a.estacion_id=b.estacion_id 
							AND b.departamento='".$departamento."'
							AND b.departamento=c.departamento 
							AND a.usuario_id=d.usuario_id 
							AND a.ingreso=e.ingreso 
							AND (e.estado='1' OR e.estado='2')
							AND a.ingreso=i.ingreso 
							AND e.plan_id=h.plan_id 
							AND k.tipo_afiliado_id=e.tipo_afiliado_id 
							AND i.tipo_id_paciente=l.tipo_id_paciente 
							AND i.paciente_id=l.paciente_id 
							AND a.solicitud_id=det.solicitud_id 
							AND det.codigo_producto=invp.codigo_producto 
							AND invp.unidad_id=u.unidad_id
							ORDER BY l.tipo_id_paciente,l.paciente_id,a.fecha_solicitud,a.solicitud_id,det.codigo_producto 
						);";
			
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars[$result->fields[0]][$result->fields[1]][$result->fields[2]]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
		
		return $vars;
	}
	
	
	function GuardaDespachoMedDepartamentoConfirmacion($datos,$pendiente,$cancelar,$motivo,$observacion,$departamento,$descripcionDpto,$concepto)
	{
		list($dbconn) = GetDBconn();
		$l=0;
		foreach($datos as $Solici=>$value)
		{
			$query="SELECT 	date(a.fecha_solicitud) as fecha,
											a.tipo_solicitud,
											a.ingreso,
											a.estacion_id 
							FROM hc_solicitudes_medicamentos a 
							WHERE a.solicitud_id='".$Solici."';";
							
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Guardar en la Base de Datos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				if($result->RecordCount()>0)
				{
					$vars=$result->GetRowAssoc($toUpper=false);
					$result->Close();
					$fecha=$vars['fecha'];
					$TipoSolicitud=$vars['tipo_solicitud'];
					$ingreso=$vars['ingreso'];
					$EstacionId=$vars['estacion_id'];
				}
			}
			$query="SELECT nextval('bodegas_documento_despacho_med_documento_despacho_id_seq')";
			$result = $dbconn->Execute($query);
			$documento=$result->fields[0];
			
			$query="INSERT INTO bodegas_documento_despacho_med
							(
								documento_despacho_id,
								bodegas_doc_id,
								fecha,
								total_costo,
								observacion,
								usuario_id,
								fecha_registro
							)
							VALUES
							(
								'$documento',
								$concepto,
								'".date("Y-m-d")."',
								'0',
								'',
								'".UserGetUID()."',
								'".date("Y-m-d H:i:s")."'
							);";
							
			$result = $dbconn->Execute($query);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Guardar en la Tabla bodegas_documento_despacho_med";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				foreach($value as $keyVal=>$valores)//por cada medicamento de la solicitud
				{
					if($valores[9]==" => ")
						$valores[9]="";
					
					if($valores[9])
					{
						list($cod_pro,$nombre_pro)=explode(" => ",$valores[9]);
					}
					else
					{
						$cod_pro=$valores[1];
					}
					
					$contador=$valores[3];
					$costoProducto=$this->HallarCostoProducto($_SESSION['Bodegas']['empresa_id'],$cod_pro);
					if($TipoSolicitud!='I')
					{
						$query="SELECT nextval('bodegas_documento_despacho_med_d_consecutivo_depacho_seq')";
						$result = $dbconn->Execute($query);
						$consecutivo=$result->fields[0];
						
						$query="INSERT INTO bodegas_documento_despacho_med_d
										(
											consecutivo_depacho,
											documento_despacho_id,
											codigo_producto,
											cantidad,
											total_costo,
											consecutivo_solicitud
										)
										VALUES
										(
											'$consecutivo',
											'$documento',
											'".$cod_pro."',
											'".$valores[8]."',
											'$costoProducto',
											'".$valores[5]."'
										);";
					}
					else
					{
						$query="SELECT nextval('bodegas_documento_despacho_ins_d_consecutivo_depacho_seq')";
						$result = $dbconn->Execute($query);
						$consecutivo=$result->fields[0];
						
						$query="INSERT INTO bodegas_documento_despacho_ins_d
										(
											consecutivo_depacho,
											documento_despacho_id,
											codigo_producto,
											cantidad,
											total_costo,
											consecutivo_solicitud
										)
										VALUES
										(
											'$consecutivo',
											'$documento',
											'".$cod_pro."',
											'".$valores[8]."',
											'$costoProducto',
											'".$valores[5]."'
										);";
					}
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al Guardar en el detalle del Documento bodegas_documento_despacho_med";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					$result = $dbconn->Execute("COMMIT;");
				}
				
				$totalizCostoDoc=$this->TotalizarDocDepacho($documento,$TipoSolicitud);

				$query="UPDATE hc_solicitudes_medicamentos 
								SET sw_estado='1',
								documento_despacho='".$documento."' 
								WHERE solicitud_id=".$Solici." 
								AND tipo_solicitud= '".$TipoSolicitud."'";
				
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Guardar en la Tabla hc_solicitudes_medicamentos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$result = $dbconn->Execute("COMMIT;");
			}
			
			if(sizeof($pendiente)>0)
			{
				$insertada=0;
				$i=0;

				foreach($pendiente as $SolicitudCodigo=>$cantidad)
				{
					(list($Solicitud,$Codigo,$canti,$evolucion,$usuario_id,$codigo_depachado)=explode('||//',$cantidad));
					
					if($Solicitud==$Solici && $insertada==0)
					{
						$query="SELECT nextval('hc_solicitudes_medicamentos_solicitud_id_seq')";
						$result = $dbconn->Execute($query);
						$solicitudActiva=$result->fields[0];
						
						$query="INSERT INTO hc_solicitudes_medicamentos
										(
											solicitud_id,
											ingreso,
											bodega,
											empresa_id,
											centro_utilidad,
											usuario_id,
											sw_estado,
											fecha_solicitud,
											estacion_id,
											tipo_solicitud
										)
										VALUES
										(
											'$solicitudActiva',
											'".$ingreso."',
											'".$_SESSION['Bodegas']['bodega']."',
											'".$_SESSION['Bodegas']['empresa_id']."',
											'".$_SESSION['Bodegas']['centro_id']."',
											'".$usuario_id."',
											'0',
											'".date("Y-m-d H:i:s")."',
											'".$EstacionId."',
											'".$TipoSolicitud."'
										);";
										
					$result = $dbconn->Execute($query);
					
					if($dbconn->ErrorNo() != 0)
					{
							$this->error = "Error al Guardar en la Tabla hc_solicitudes_medicamentos por Pendientes";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					$vectorSolicitudes[]=$solicitudActiva;
					$insertada=1;
				}
				
				if($Solicitud==$Solici)
				{
					$query="";
					if($TipoSolicitud=='I')
					{
						$query="INSERT INTO hc_solicitudes_insumos_d
										(
											codigo_producto,
											cantidad,
											solicitud_id
										)
										VALUES
										(
											'$Codigo',
											'$canti',
											'$solicitudActiva'
										);";
										
						/*$query.="	UPDATE hc_solicitudes_insumos_d 
											SET codigo_producto_despachado='$codigo_producto'
											WHERE solicitud_id=$Solicitud;";*/
					}
					else
					{
						if(!$evolucion)
							$evolucion="NULL";
						$query="INSERT INTO hc_solicitudes_medicamentos_d
										(
											solicitud_id,
											medicamento_id,
											evolucion_id,
											cant_solicitada,
											ingreso
										)
										VALUES
										(
											'$solicitudActiva',
											'$Codigo',
											$evolucion,
											'$canti',
											$ingreso
										);";
										
						/*$query.="	UPDATE hc_solicitudes_medicamentos_d 
											SET codigo_producto_despachado='$codigo_producto'
											WHERE solicitud_id=$Solicitud;";*/
					}
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0) 
					{
						$this->error = "Error al Guardar en el detalle de la Tabla hc_solicitudes medicamentos e insumos $TipoSolicitud por Pendientes";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
				}
				$i++;
				$result = $dbconn->Execute("COMMIT;");
			}
		}
		
		if(sizeof($cancelar)>0 AND sizeof($observacion)>0)
		{
			$insertada=0;
			$i=0;
			
			foreach($cancelar as $SolicitudCodigo=>$cantidad)
			{
				(list($Solicitud,$Codigo,$canti,$evolucion,$usuario_id,$codigo_despachado)=explode('||//',$cantidad));
								
				$Motivos=$motivo;
				$Observaciones=$observacion;
				
				if(!$Motivos[$i] OR $Motivos[$i]==-1) 
				{
					$Motivos[$i]="NULL";
				}
				
				if(!$Observaciones[$i])
				{
					$Observaciones[$i]="NULL";
				}
				else
				{
					$Observaciones[$i]="'".$Observaciones[$i]."'";
				}
				
				if($Solicitud==$Solici && $insertada==0)
				{
					$query="SELECT nextval('hc_solicitudes_medicamentos_solicitud_id_seq')";
					$result = $dbconn->Execute($query);
					$solicitudInactiva=$result->fields[0];
					
					$query="INSERT INTO hc_solicitudes_medicamentos
									(
										solicitud_id,
										ingreso,
										bodega,
										empresa_id,
										centro_utilidad,
										usuario_id,
										sw_estado,
										fecha_solicitud,
										estacion_id,
										tipo_solicitud
									)
									VALUES
									(
										'$solicitudInactiva',
										'".$ingreso."',
										'".$_SESSION['Bodegas']['bodega']."',
										'".$_SESSION['Bodegas']['empresa_id']."',
										'".$_SESSION['Bodegas']['centro_id']."',
										'".$usuario_id."',
										'3',
										'".date("Y-m-d H:i:s")."',
										'".$EstacionId."',
										'".$TipoSolicitud."'
									);";
									
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al Guardar en la Tabla hc_solicitudes_medicamentos por Canceladas";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
					$insertada=1;
				}
				
				if($Solicitud==$Solici)
				{
					if($TipoSolicitud=='I')
					{
						$query="SELECT nextval('hc_solicitudes_insumos_d_consecutivo_d_seq')";
						$result = $dbconn->Execute($query);
						$consec=$result->fields[0];
						
						$query="INSERT INTO hc_solicitudes_insumos_d
										(
											consecutivo_d,
											codigo_producto,
											cantidad,
											solicitud_id,
											codigo_producto_despachado
										)
										VALUES
										(
											'$consec',
											'$Codigo',
											'$canti',
											'$solicitudInactiva',
											'$codigo_despachado'
											
										);";
						$query.="INSERT INTO hc_solicitudes_insumos_motivos_cancela
										(
											consecutivo_d,
											motivo_id,
											observaciones
										)
										VALUES
										(
											'$consec',
											".$Motivos[$i].",
											".$Observaciones[$i]."
										);";
					}
					else
					{
						$query="SELECT nextval('hc_solicitudes_medicamentos_d_consecutivo_d_seq')";
						$result = $dbconn->Execute($query);
						$consec=$result->fields[0];
						
						$query="INSERT INTO hc_solicitudes_medicamentos_d
										(
											consecutivo_d,
											solicitud_id,
											medicamento_id,
											evolucion_id,
											cant_solicitada,
											ingreso,
											codigo_producto_despachado
										)
										VALUES
										(
											'$consec',
											'$solicitudInactiva',
											'$Codigo',
											'$evolucion',
											'$canti',
											$ingreso,
											'$codigo_despachado'
										);";
						
						$query.="INSERT INTO hc_solicitudes_medicamentos_motivos_cancela
										(
											consecutivo_d,
											motivo_id,
											observaciones
										)
										VALUES
										(
											'$consec',
											".$Motivos[$i].",
											".$Observaciones[$i]."
										);";
						}
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0) 
						{
							$this->error = "Error al Guardar en el detalle de la Tabla por Canceladas";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
					}
					$i++;
					$result = $dbconn->Execute("COMMIT;");
				}
			}
			$l++;
		}
		return true;
	}
	

	function FechasLotesProductosDevolDpto($departamento,$codigoProducto)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT a.fecha_vencimiento,a.lote,a.cantidad,a.consecutivo,a.codigo_producto
						FROM inv_solicitudes_devolucion_fvencimiento_lotes a,
						(
							SELECT b.consecutivo
							FROM inv_solicitudes_devolucion a,
							inv_solicitudes_devolucion_d b,
							estaciones_enfermeria est,
							cuentas e
							WHERE a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
							AND a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
							AND a.bodega='".$_SESSION['Bodegas']['bodega']."' 
							AND a.estado='0' 
							AND a.documento=b.documento 
							AND b.estado='0' 
							AND b.codigo_producto='".$codigoProducto."' 
							AND a.estacion_id=est.estacion_id 
							AND est.departamento='".$departamento."' 
							AND a.ingreso=e.ingreso 
							AND (e.estado='1' OR e.estado='2')
						) as consecutivos
						WHERE a.consecutivo=consecutivos.consecutivo 
						AND a.codigo_producto='".$codigoProducto."'";
			
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
		return $vars;
	}

	
	function ProductosTotalesDevolucion($departamento)
	{
		list($dbconn) = GetDBconn();
		
		$query="SELECT 	b.codigo_producto,
										sum(b.cantidad) as cantidad,
										(
											SELECT d.descripcion 
											FROM inventarios_productos d 
											WHERE d.codigo_producto=b.codigo_producto
										) as descripcion,
										(
											SELECT f.sw_control_fecha_vencimiento 
											FROM inventarios_productos f 
											WHERE f.codigo_producto=b.codigo_producto
										) as sw_control_fecha_vencimiento
						FROM 		inv_solicitudes_devolucion a,
										inv_solicitudes_devolucion_d b,
										estaciones_enfermeria est,
										cuentas e
						WHERE a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
									AND a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
									AND a.bodega='".$_SESSION['Bodegas']['bodega']."' 
									AND a.estado='0' 
									AND a.documento=b.documento 
									AND b.estado='0' 
									AND a.estacion_id=est.estacion_id 
									AND est.departamento='".$departamento."' 
									AND a.ingreso=e.ingreso 
									AND (e.estado='1' OR e.estado='2')
						GROUP BY b.codigo_producto";
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars[$result->fields[0]][]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
		return $vars;
	}
	
	
	function RealizarDevolucionMedicamentosDpto($checkboxDevol,$departamento,$concepto)
	{
		foreach($checkboxDevol as $producto=>$datos)
		{
			(list($cantidad,$cantidadLotes)=explode('||//',$datos));
			
			if($cantidadLotes)
			{
				if($cantidadLotes < $cantidad)
				{
					$this->frmError["MensajeError"]="La Suma de las Cantidades Insertadas es menor a la Cantidad Total del Producto con Codigo".' '.$producto;
					return false;
				}
			}
		}
		
		list($dbconn) = GetDBconn();
		
		$numeracion=$this->AsignarNumeroDocumentoDespacho($concepto);
		$numeracion=$numeracion['numeracion'];
		$codigoAgrupamiento=$this->InsertarBodegasDocumentos($concepto,$numeracion,date("Y/m/d"),'','DIMD');
		
		foreach($checkboxDevol as $CodigoPro=>$datos)
		{
			(list($Cantidad,$cantidadLotes)=explode('||//',$datos));
			$costoProducto=$this->HallarCostoProducto($_SESSION['Bodegas']['empresa_id'],$CodigoPro);
			
			$query="SELECT nextval('bodegas_documentos_d_consecutivo_seq');";
			$result=$dbconn->Execute($query);
			$Consecutivo=$result->fields[0];
			
			$InsertarDocumentod=$this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$CodigoPro,$Cantidad,$costoProducto);
			
			if($InsertarDocumentod==1)
			{
				$query="SELECT existencia 
								FROM existencias_bodegas 
								WHERE empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
								AND centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
								AND bodega='".$_SESSION['Bodegas']['bodega']."' 
								AND codigo_producto='$CodigoPro'";
				
				$result = $dbconn->Execute($query);
				$Existencias=$result->fields[0];
				$ModifExist=$this->ModificacionExistenciasResta($Existencias,$Cantidad,$_SESSION['Bodegas']['empresa_id'],$_SESSION['Bodegas']['centro_id'],$_SESSION['Bodegas']['bodega'],$CodigoPro);
				
				$query="SELECT DISTINCT 
												a.documento,
												a.estacion_id,
												est.departamento,
												dpto.empresa_id,
												dpto.centro_utilidad,
												dpto.servicio,
												a.ingreso,
												e.numerodecuenta,
												e.plan_id
								FROM inv_solicitudes_devolucion a,
								inv_solicitudes_devolucion_d b,
								estaciones_enfermeria est,
								departamentos dpto,
								cuentas e
								WHERE a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
								AND a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
								AND a.bodega='".$_SESSION['Bodegas']['bodega']."' 
								AND a.estado='0' 
								AND b.estado='0' 
								AND a.documento=b.documento 
								AND b.codigo_producto='".$CodigoPro."' 
								AND a.estacion_id=est.estacion_id 
								AND est.departamento='".$departamento."' 
								AND est.departamento=dpto.departamento 
								AND a.ingreso=e.ingreso 
								AND (e.estado='1' OR e.estado='2')";

				$result=$dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Guardar en la Base de Datos SQL 1";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					$datos=$result->RecordCount();
					if($datos)
					{
						while(!$result->EOF)
						{
							$Documentos[]=$result->GetRowAssoc($toUpper=false);
							$result->MoveNext();
						}
					}
					
					for($i=0;$i<sizeof($Documentos);$i++)
					{
						$query="SELECT 	a.consecutivo,
														a.cantidad
										FROM inv_solicitudes_devolucion_d a 
										WHERE a.documento='".$Documentos[$i]['documento']."' 
										AND a.codigo_producto='".$CodigoPro."' 
										AND a.estado='0'";
						
						$result=$dbconn->Execute($query);
						
						if($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Guardar en la Base de Datos SQL 1";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						else
						{
							unset($DetalleDoc);
							$datos=$result->RecordCount();
							if($datos)
							{
								while(!$result->EOF)
								{
									$DetalleDoc[]=$result->GetRowAssoc($toUpper=false);
									$result->MoveNext();
								}
							}
						}
						
						if($DetalleDoc)
						{
							for($j=0;$j<sizeof($DetalleDoc);$j++)
							{
								$CantidadProd=$DetalleDoc[$j]['cantidad'];
								$this->InsertarBodegasDocumentosdCober($Consecutivo,date('Y-m-d H:i:s'),$Documentos[$i]['numerodecuenta'],$CodigoPro,$CantidadProd,$varsPr[$j]['precio'],$codigoAgrupamiento,$Documentos[$i]['plan_id'],$Documentos[$i]['servicio'],$Documentos[$i]['empresa_id'],$Documentos[$i]['centro_utilidad'],$Documentos[$i]['departamento'],'1','DIMD');
								
								$query="SELECT cantidad_acum 
												FROM hc_bodega_paciente 
												WHERE ingreso='".$Documentos[$i]['ingreso']."' 
												AND medicamento_id='$CodigoPro'";
								
								$result = $dbconn->Execute($query);
								
								$cantidaArestar=$result->fields[0];
								
								if($cantidaArestar==$CantidadProd)
								{
									$query="DELETE FROM hc_bodega_paciente 
													WHERE ingreso='".$Documentos[$i]['ingreso']."' 
													AND medicamento_id='$CodigoPro'";
									
									$result = $dbconn->Execute($query);
									if($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al Guardar en la Base de Datos SQL 3";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
								}
								else
								{
									$cantidadTotalMed=$cantidaArestar-$CantidadProd;
									$query="UPDATE hc_bodega_paciente SET cantidad_acum='$cantidadTotalMed' WHERE ingreso='".$Documentos[$i]['ingreso']."' AND medicamento_id='$CodigoPro'";
									$result = $dbconn->Execute($query);
									if($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al Guardar en la Base de Datos";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
								}
								
								$query="INSERT INTO bodegas_documentos_d_fvencimiento_lotes
												(
													fecha_vencimiento,
													lote,
													saldo,
													cantidad,
													empresa_id,
													centro_utilidad,
													bodega,
													codigo_producto,
													consecutivo
												)
												(
													SELECT 	fecha_vencimiento,
																	lote,
																	'0',
																	cantidad,
																	'".$_SESSION['Bodegas']['empresa_id']."',
																	'".$_SESSION['Bodegas']['centro_id']."',
																	'".$_SESSION['Bodegas']['bodega']."',
																	codigo_producto,
																	'$Consecutivo' 
													FROM inv_solicitudes_devolucion_fvencimiento_lotes 
													WHERE consecutivo='".$DetalleDoc[$j]['consecutivo']."' 
													AND codigo_producto='$CodigoPro'
												)";

								$result = $dbconn->Execute($query);
								if($dbconn->ErrorNo() != 0) 
								{
									$this->error = "Error al Guardar en la Base de Dato SQL4";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
							}
							
							$query="UPDATE inv_solicitudes_devolucion 
											SET estado='1',
											bodegas_doc_id=$concepto,
											numeracion=$numeracion 
											WHERE documento=".$Documentos[$i]['documento']."";
							
							$result = $dbconn->Execute($query);
							
							if($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al Guardar en la Base de Datos inv_solicitudes_devolucion SQL 5";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
						}
						$result = $dbconn->Execute("COMMIT;");
					}
				}
			}
			$result = $dbconn->Execute("COMMIT;");
		}
		$result->Close();
		$totalizCostoDoc=$this->TotalizarCostoDocumento($numeracion,$concepto);
		return true;
	}
	
	function SumaFechasLotesProductosDevolDpto($departamento,$codigoProducto)
	{
		list($dbconn) = GetDBconn();

		$query="SELECT sum(a.cantidad) as suma
						FROM inv_solicitudes_devolucion_fvencimiento_lotes a,
						(
							SELECT b.consecutivo
							FROM inv_solicitudes_devolucion a,
							inv_solicitudes_devolucion_d b,
							estaciones_enfermeria est,
							cuentas e
							WHERE a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
							AND a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
							AND a.bodega='".$_SESSION['Bodegas']['bodega']."'
							AND a.estado='0' 
							AND a.documento=b.documento 
							AND b.estado='0' 
							AND b.codigo_producto='".$codigoProducto."' 
							AND a.estacion_id=est.estacion_id 
							AND est.departamento='".$departamento."' 
							AND a.ingreso=e.ingreso 
							AND (e.estado='1' OR e.estado='2')
						) as consecutivos
						WHERE a.consecutivo=consecutivos.consecutivo 
						AND a.codigo_producto='$codigoProducto'";
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		$result->Close();
		return $vars;
	}
	
	
	function InsertarFechaVencimientoLoteDevolDpto($fecha_ven,$CantidadLote,$cantidad,$lote,$consecutivo,$codigo_pro,$departamento)
	{
		$cadena=explode('/',$fecha_ven);
		$fecha=$cadena[2].'-'.$cadena[1].'-'.$cadena[0];
		
		if(mktime(0,0,0,$cadena[1],$cadena[0],$cadena[2])<mktime(0,0,0,date('m'),date('d'),date('Y')))
		{
			$this->frmError["MensajeError"]="La fecha de Vencimiento no puede ser menor a la Actual";
			return false;
		}
		
		$sumaTotal=$this->SumaFechasLotesProductosDevolDpto($departamento,$codigo_pro);
		if($sumaTotal['suma']+$CantidadLote>$cantidad)
		{
			$this->frmError["MensajeError"]="La suma de las Cantidades supera la Cantidad Total del producto con cogigo".' '.$codigo_pro;
			return true;
		}
	
		list($dbconn) = GetDBconn();
		
		$query="SELECT result.consecutivo,
										(	CASE WHEN result.cantidad_insertada IS NULL 
											THEN result.cantidad 
											ELSE result.cantidad-result.cantidad_insertada 
											END 
										) as cantidad
						FROM (
										SELECT b.consecutivo,
										b.cantidad,
										(
											SELECT sum(x.cantidad) 
											FROM inv_solicitudes_devolucion_fvencimiento_lotes x 
											WHERE x.consecutivo=b.consecutivo 
											AND x.codigo_producto='".$codigo_pro."'
										) as cantidad_insertada
										FROM inv_solicitudes_devolucion a,
										inv_solicitudes_devolucion_d b,
										estaciones_enfermeria est,
										cuentas e
						WHERE a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
						AND a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
						AND a.bodega='".$_SESSION['Bodegas']['bodega']."' 
						AND a.estado='0' 
						AND a.documento=b.documento 
						AND b.estado='0' 
						AND b.codigo_producto='".$codigo_pro."' 
						AND a.estacion_id=est.estacion_id 
						AND est.departamento='".$departamento."' 
						AND a.ingreso=e.ingreso 
						AND (e.estado='1' OR e.estado='2')) as result
						WHERE (result.cantidad_insertada < result.cantidad OR result.cantidad_insertada IS NULL) 
						ORDER BY result.consecutivo";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
		}
		else
		{
			if($result->RecordCount()>0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		//Fin Consulta
		$i=0;
		while($i<sizeof($vars) && $CantidadLote>0)
		{
			if($vars[$i]['cantidad']<=$CantidadLote)
			{
				$cantidadInsertar=$vars[$i]['cantidad'];
				$CantidadLote-=$vars[$i]['cantidad'];
			}
			else
			{
				$cantidadInsertar=$CantidadLote;
				$CantidadLote=0;
			}
			
			$queryy.="INSERT INTO inv_solicitudes_devolucion_fvencimiento_lotes
								(
									consecutivo,
									codigo_producto,
									fecha_vencimiento,
									lote,
									cantidad
								)
								VALUES
								(
									'".$vars[$i]['consecutivo']."',
									'".$codigo_pro."',
									'$fecha',
									'".$lote."',
									'".$cantidadInsertar."'
								);";
			$i++;
		}
			
		$result = $dbconn->Execute($queryy);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$result = $dbconn->Execute("COMMIT;");
		$result->Close();
		return true;
	}

	function LlamaEliminarFechaVDevol($consecutivo,$codigo_pro,$CantidadLote,$fecha_ven,$lote)
	{
		list($dbconn) = GetDBconn();
		
		$query="DELETE FROM 
						inv_solicitudes_devolucion_fvencimiento_lotes 
						WHERE consecutivo='".$consecutivo."' 
						AND codigo_producto='".$codigo_pro."' 
						AND cantidad='".$CantidadLote."' 
						AND fecha_vencimiento='".$fecha_ven."' 
						AND lote='".$lote."'";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo  - LlamaEliminarFechaVDevol";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return true;
	}
	
	function ImprimirDevolucionIndividual()
	{
		$cadenausuario=$_REQUEST['usuarioId'].' - '.$_REQUEST['usuarioestacion'];
		$cadenausuario=substr($cadenausuario,0,31);
		$cadenaestacion=$_REQUEST['EstacionId'].' - '.$_REQUEST['NombreEstacion'];
		$cadenaestacion=substr($cadenaestacion,0,31);
		$cadptoestacion=$_REQUEST['deptoestacion'];
		$cadptoestacion=str_pad($cadptoestacion,0,31);
		$cadnombrepac=$_REQUEST['nombrepac'];
		$cadnombrepac=str_pad($cadnombrepac,0,31);

		if(!IncludeFile("classes/reports/reports.class.php"))
		{
			$this->error = "No se pudo inicializar la Clase de Reportes";
			$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
			return false;
		}
		$productos=$this->productosReportImprimir($_REQUEST['SolicitudId']);
		
		$classReport = new reports;
		$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
		$reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='InvBodegas',$reporte_name='reportDevolucion',
		$datos=array("razonsocial"=>$_SESSION['Bodegas']['empresa_desc'],"BodegaId"=>$_SESSION['Bodegas']['bodega'],
		"Bodega"=>$_SESSION['Bodegas']['bodega_desc'],"cadenaestacion"=>$cadenaestacion,"cadptoestacion"=>$cadptoestacion,
		"documento"=>$_REQUEST['SolicitudId'],"Fecha"=>$_REQUEST['Fecha'],"cadenausuario"=>$cadenausuario,"productos"=>$productos,
		"rango"=>$_REQUEST['rango'],"tipoafil"=>$_REQUEST['tipoafil'],"cama"=>$_REQUEST['cama'],"pieza"=>$_REQUEST['pieza'],"plan"=>$_REQUEST['plan'],"tipoidPac"=>$_REQUEST['tipoidPac'],"paciente"=>$_REQUEST['paciente'],"cadnombrepac"=>$cadnombrepac),$impresora,$orientacion='',$unidades='',$formato='',$html=1);
		if(!$reporte){
						$this->error = $classReport->GetError();
						$this->mensajeDeError = $classReport->MensajeDeError();
						unset($classReport);
						return false;
		}
		$resultado=$classReport->GetExecResultado();
		unset($classReport);
		if(!empty($resultado[codigo]))
		{
				"El PrintReport retorno : " . $resultado[codigo] . "<br>";
		}
		$this->frmCuadroSolicitudes();
		return true;
  }
	
	
	function productosReportImprimir($Documento)
	{
		list($dbconn) = GetDBconn();
		
		$query="(SELECT x.codigo_producto,x.descripcion_abreviada as desmed,b.cantidad,x2.descripcion as ubicacion,u.abreviatura
		FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d b,unidades u,inventarios_productos x
		LEFT JOIN existencias_bodegas x1 ON (x.codigo_producto=x1.codigo_producto AND x1.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND x1.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND x1.bodega='".$_SESSION['Bodegas']['bodega']."')
		LEFT JOIN bodegas_ubicaciones x2 ON (x1.ubicacion_id=x2.ubicacion_id)
		WHERE a.documento='".$Documento."' AND a.documento=b.documento AND b.estado='0' AND b.codigo_producto=x.codigo_producto AND x.unidad_id=u.unidad_id)
		";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}else{
				$datos=$result->RecordCount();
				if($datos){
						while(!$result->EOF) {
								$vars[]=$result->GetRowAssoc($toUpper=false);
								$result->MoveNext();
						}
				}
		}
		
		$result->Close();
		return $vars;
	}
	
	
	function InsertarFechaVencimientoLoteDevol($fecha_ven,$cantidadLote,$cantidad,$lote,$consecutivo,$codigo_pro)
	{
		
		$cadena=explode('/',$fecha_ven);
		$fecha=$cadena[2].'-'.$cadena[1].'-'.$cadena[0];
		
		if(!$fecha_ven || !$lote || !$cantidadLote)
		{
			$this->frmError["MensajeError"]="La fecha de Vencimiento, Cantidad y el Lote son Datos Obligatorios";
			return false;
		}

		if(mktime(0,0,0,$cadena[1],$cadena[0],$cadena[2])<mktime(0,0,0,date('m'),date('d'),date('Y')))
		{
			$this->frmError["MensajeError"]="La fecha de Vencimiento no puede ser menor a la Actual";
			return false;
		}
		
		$sumaTotal=$this->SumaFechasLotesProductosDevol($consecutivo,$codigo_pro);
		if($sumaTotal['suma']+$cantidadLote>$cantidad)
		{
			$this->frmError["MensajeError"]="La suma de las Cantidades supera la Cantidad Total del producto con cogigo".' '.$_REQUEST['codigoProducto'];
			return false;
		}
		
		list($dbconn) = GetDBconn();

		$query="INSERT INTO inv_solicitudes_devolucion_fvencimiento_lotes
						(
							consecutivo,
							codigo_producto,
							fecha_vencimiento,
							lote,
							cantidad
						)
						VALUES
						(
							'".$consecutivo."',
							'".$codigo_pro."',
							'$fecha',
							'".$lote."',
							'".$cantidadLote."'
						)";
						
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return true;
	}
	

	function ImprimirSolicitudMed()
	{
		$cadenausuario=$_REQUEST['usuarioId'].' - '.$_REQUEST['usuarioestacion'];
		$cadenausuario=substr($cadenausuario,0,31);
		$cadenaestacion=$_REQUEST['EstacionId'].' - '.$_REQUEST['NombreEstacion'];
		$cadenaestacion=substr($cadenaestacion,0,31);
		$cadptoestacion=$_REQUEST['deptoestacion'];
		$cadptoestacion=str_pad($cadptoestacion,0,31);
		$cadnombrepac=$_REQUEST['nombrepac'];
		$cadnombrepac=str_pad($cadnombrepac,0,31);
	
		if(!IncludeFile("classes/reports/reports.class.php"))
		{
			$this->error = "No se pudo inicializar la Clase de Reportes";
			$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
			return false;
		}
		$classReport = new reports;
		$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
		
		$reporte=$classReport->PrintReport(
		$tipo_reporte='pos',
		$tipo_modulo='app',
		$modulo='Solicitud_Medicamentos_PorBodega',
		$reporte_name='reportSolicitud',
		$datos=array("razonsocial"=>$_SESSION['Bodegas']['empresa_desc'],"BodegaId"=>$_SESSION['Bodegas']['bodega'],
		"Bodega"=>$_SESSION['Bodegas']['bodega_desc'],"cadenaestacion"=>$cadenaestacion,"cadptoestacion"=>$cadptoestacion,
		"SolicitudId"=>$_REQUEST['SolicitudId'],"Fecha"=>$_REQUEST['Fecha'],"cadenausuario"=>$cadenausuario,"medicamentos"=>$_REQUEST['medicamentos'],
		"rango"=>$_REQUEST['rango'],"tipoafil"=>$_REQUEST['tipoafil'],"cama"=>$_REQUEST['cama'],"pieza"=>$_REQUEST['pieza'],"plan"=>$_REQUEST['plan'],"tipoidPac"=>$_REQUEST['tipoidPac'],"paciente"=>$_REQUEST['paciente'],"cadnombrepac"=>$cadnombrepac,"sw"=>$_REQUEST['sw']),$impresora,$orientacion='',$unidades='',$formato='',$html=1);
		
		if(!$reporte)
		{
			$this->error = $classReport->GetError();
			$this->mensajeDeError = $classReport->MensajeDeError();
			unset($classReport);
			return false;
		}
		
		$resultado=$classReport->GetExecResultado();
		
		unset($classReport);
		
		if(!empty($resultado[codigo]))
		{
				"El PrintReport retorno : " . $resultado[codigo] . "<br>";
		}
		$this->frmCuadroSolicitudes();
		return true;
	}
	
	function medicamentosReportImprimir($Solicitud,$sw)
	{
		list($dbconn) = GetDBconn();
		$query="(SELECT a.*,x2.descripcion as ubicacion
				FROM
					(SELECT x.codigo_producto,x.descripcion_abreviada as desmed,b.cant_solicitada,u.abreviatura
							FROM hc_solicitudes_medicamentos a,hc_solicitudes_medicamentos_d b,unidades u,inventarios_productos x
							WHERE a.solicitud_id='$Solicitud' AND a.solicitud_id=b.solicitud_id AND b.medicamento_id=x.codigo_producto AND x.unidad_id=u.unidad_id
							AND a.sw_estado='$sw'
					) as a
				LEFT JOIN existencias_bodegas x1 ON (a.codigo_producto=x1.codigo_producto AND x1.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND x1.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND x1.bodega='".$_SESSION['Bodegas']['bodega']."')
				LEFT JOIN bodegas_ubicaciones x2 ON (x1.ubicacion_id=x2.ubicacion_id)
				)
						UNION
						(    SELECT a.*,y2.descripcion as ubicacion
							FROM
								(SELECT y.codigo_producto,y.descripcion_abreviada as desmed,d.cant_solicitada,u.abreviatura
										FROM hc_solicitudes_medicamentos c,hc_solicitudes_medicamentos_mezclas_d d,unidades u,inventarios_productos y
										WHERE c.solicitud_id='$Solicitud' AND c.solicitud_id=d.solicitud_id AND d.medicamento_id=y.codigo_producto AND y.unidad_id=u.unidad_id
										AND c.sw_estado='$sw'
								) as a
								LEFT JOIN existencias_bodegas y1 ON(a.codigo_producto=y1.codigo_producto AND y1.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND y1.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND y1.bodega='".$_SESSION['Bodegas']['bodega']."')
								LEFT JOIN bodegas_ubicaciones y2 ON(y1.ubicacion_id=y2.ubicacion_id)
				)
						UNION
						(    SELECT a.*,z2.descripcion as ubicacion
							FROM
								(SELECT z.codigo_producto,z.descripcion_abreviada as desmed,f.cantidad as cant_solicitada,u.abreviatura
										FROM hc_solicitudes_medicamentos e,hc_solicitudes_insumos_d f,unidades u,inventarios_productos z
										WHERE e.solicitud_id='$Solicitud' AND e.solicitud_id=f.solicitud_id AND f.codigo_producto=z.codigo_producto AND z.unidad_id=u.unidad_id
										AND e.sw_estado='$sw'
								)as a
								LEFT JOIN existencias_bodegas z1 ON (a.codigo_producto=z1.codigo_producto AND z1.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND z1.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND z1.bodega='".$_SESSION['Bodegas']['bodega']."')
								LEFT JOIN bodegas_ubicaciones z2 ON (z1.ubicacion_id=z2.ubicacion_id)
				)";

		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}else{
				$datos=$result->RecordCount();
				if($datos){
						while(!$result->EOF) {
								$vars[]=$result->GetRowAssoc($toUpper=false);
								$result->MoveNext();
						}
				}
		}
		$result->Close();
		return $vars;
	}

	function ImprimirTotalesSolicitudesDpto()
	{
		
		$vars=$this->DatosTotalesSolicitudesDpto($_REQUEST['estacion_id'],$_REQUEST['SolicitudId'],$_REQUEST['sw'],$_REQUEST['sw_imp']);
		
		if(!IncludeFile("classes/reports/reports.class.php"))
		{
			$this->error = "No se pudo inicializar la Clase de Reportes";
			$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
			return false;
		}
		$classReport = new reports;
		
		$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
		
		$reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='Solicitud_Medicamentos_PorBodega',$reporte_name='reportSolicitudesTotalesDpto',
		
		array("Datos"=>$vars,"razonsocial"=>$_SESSION['Bodegas']['empresa_id'],"BodegaId"=>$_SESSION['Bodegas']['bodega'],
		"Bodega"=>$_SESSION['Bodegas']['bodega'],"CentroUtilidad"=>$_SESSION['Bodegas']['centro_desc'],"departamento"=>$_REQUEST['departamento'],
		"descripcionDpto"=>$_REQUEST['descripcionDpto']),$impresora,$orientacion='',$unidades='',$formato='',$html=1);
		
		if(!$reporte)
		{
			$this->error = $classReport->GetError();
			$this->mensajeDeError = $classReport->MensajeDeError();
			unset($classReport);
			return false;
		}
		
		$resultado=$classReport->GetExecResultado();
		
		unset($classReport);
		if(!empty($resultado[codigo]))
		{
				"El PrintReport retorno : " . $resultado[codigo] . "<br>";
		}
		
		$solicitudes=explode(".-.",$_REQUEST['solicitudes']);
		
		$this->UpdateImpreso($solicitudes);
		$this->frmCuadroSolicitudes();
		
		return true;
  }
	
	
	function UpdateImpreso($solicitudes)
	{
		list($dbconn) = GetDBconn();
		
		
		$query1="	SELECT max(sw_impreso)
							FROM hc_solicitudes_medicamentos";
			
		$result = $dbconn->Execute($query1);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$num=$result->fields[0];
		
		foreach($solicitudes as $soli)
		{
			$query1="	SELECT sw_impreso
								FROM hc_solicitudes_medicamentos
								WHERE solicitud_id=$soli;";
				
			$result1 = $dbconn->Execute($query1);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$sw_impreso=$result1->fields[0];
			
			if($sw_impreso==0)
			{
				$query="	UPDATE hc_solicitudes_medicamentos
									SET sw_impreso=".($num+1).",
									usuario_imp=".UserGetUID()."
									WHERE solicitud_id=$soli;";
				
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					echo $dbconn->ErrorMsg();
					return false;
				}
			}
			else break;
		}
		return true;
	}
	
	function DatosTotalesSolicitudesDpto($estacion_id,$solicitud,$sw,$sw_imp)
	{
			list($dbconn) = GetDBconn();
			
			if($solicitud)
				$cond="AND a.solicitud_id=$solicitud";
			
			if(!$sw_imp)
				$imp="AND a.sw_impreso=0";
			else
				$imp="AND a.sw_impreso=".$sw_imp;
			
			$query = "
						(SELECT a.codigo_producto,a.cant_solicitada,invp.descripcion_abreviada as desmed,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
								FROM
								(SELECT det.medicamento_id as codigo_producto,sum(det.cant_solicitada) as cant_solicitada
						FROM hc_solicitudes_medicamentos a,hc_solicitudes_medicamentos_d det,estaciones_enfermeria b
						WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."' AND a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND
													a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND a.sw_estado='$sw' AND
													a.solicitud_id=det.solicitud_id AND a.estacion_id=b.estacion_id AND b.departamento='".$departamento."'
													$cond
													$imp
						GROUP BY det.medicamento_id) as a,
													inventarios_productos invp
													LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['Bodegas']['empresa']."' AND exis.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND exis.bodega='".$_SESSION['Bodegas']['bodega']."')
													LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
													,unidades u
												WHERE a.codigo_producto=invp.codigo_producto AND invp.unidad_id=u.unidad_id ORDER BY invp.descripcion_abreviada
												)";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}else{
					$datos=$result->RecordCount();
					if($datos){
							while(!$result->EOF) {
									$vars[]=$result->GetRowAssoc($toUpper=false);
									$result->MoveNext();
							}
					}
			}
			$query ="(SELECT a.codigo_producto,a.cant_solicitada,invp.descripcion_abreviada as desmed,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
								FROM
								(SELECT det.codigo_producto,sum(det.cantidad) as cant_solicitada
						FROM hc_solicitudes_medicamentos a,hc_solicitudes_insumos_d det,estaciones_enfermeria b
						WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."' AND a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND
													a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND a.sw_estado='$sw' AND
													a.solicitud_id=det.solicitud_id AND a.estacion_id=b.estacion_id AND b.estacion_id='".$estacion_id."'
													$cond
													$imp
						GROUP BY det.codigo_producto) as a,
													inventarios_productos invp
													LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND exis.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND exis.bodega='".$_SESSION['Bodegas']['bodega']."')
													LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
													,unidades u
												WHERE a.codigo_producto=invp.codigo_producto AND invp.unidad_id=u.unidad_id ORDER BY invp.descripcion_abreviada)
												";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}else{
					$datos=$result->RecordCount();
					if($datos){
							while(!$result->EOF) {
									$vars1[]=$result->GetRowAssoc($toUpper=false);
									$result->MoveNext();
							}
					}
			}
			$result->Close();
			$vector[0]=$vars;
			$vector[1]=$vars1;
			return $vector;
	}
	
	
	function ImprimirTotalesDevolucionesDpto()
	{
		$vars=$this->DatosTotalesDevolucionesDpto($_REQUEST['estacion_id']);
		
		if(!IncludeFile("classes/reports/reports.class.php"))
		{
			$this->error = "No se pudo inicializar la Clase de Reportes";
			$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
			return false;
		}
		$classReport = new reports;
		
		$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
		
		$reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='Solicitud_Medicamentos_PorBodega',$reporte_name='reportDevolucionesTotalesDpto',
		array("Datos"=>$vars,"razonsocial"=>$_SESSION['Bodegas']['empresa_desc'],"BodegaId"=>$_SESSION['Bodegas']['bodega'],
		"Bodega"=>$_SESSION['Bodegas']['bodega_desc'],"CentroUtilidad"=>$_SESSION['Bodegas']['centro_desc'],"departamento"=>$_REQUEST['departamento'],
		"descripcionDpto"=>$_REQUEST['descripcionDpto']),$impresora,$orientacion='',$unidades='',$formato='',$html=1);
		if(!$reporte)
		{
				$this->error = $classReport->GetError();
				$this->mensajeDeError = $classReport->MensajeDeError();
				unset($classReport);
				return false;
		}
		
		$resultado=$classReport->GetExecResultado();
		
		unset($classReport);
		if(!empty($resultado[codigo]))
		{
				"El PrintReport retorno : " . $resultado[codigo] . "<br>";
		}
		
		$this->frmCuadroSolicitudes();
		return true;
  }
	
	function DatosTotalesDevolucionesDpto($estacion_id)
	{
		list($dbconn) = GetDBconn();
		
		$query ="(
							SELECT a.codigo_producto,a.cantidad,invp.descripcion_abreviada as desmed,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
							FROM
							(
								SELECT det.codigo_producto,sum(det.cantidad) as cantidad
								FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d det,estaciones_enfermeria b
								WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."' AND a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND
															a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND a.estado='0' AND
															a.documento=det.documento AND det.estado='0' AND a.estacion_id=b.estacion_id AND b.departamento='".$estacion_id."'
								GROUP BY det.codigo_producto
							) as a,
							inventarios_productos invp
							LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND exis.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND exis.bodega='".$_SESSION['Bodegas']['bodega']."')
							LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
							,unidades u
							WHERE a.codigo_producto=invp.codigo_producto 
							AND invp.unidad_id=u.unidad_id 
							ORDER BY invp.descripcion_abreviada
						)
						";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}else{
				$datos=$result->RecordCount();
				if($datos){
						while(!$result->EOF) {
								$vars[]=$result->GetRowAssoc($toUpper=false);
								$result->MoveNext();
						}
				}
		}
		$result->Close();
		return $vars;
	}	
	
}//fin clase user

?>