<?php

/**
 * $Id: app_Requisiciones_user.php,v 1.6 2007/07/11 20:54:11 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 */

class app_Requisiciones_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;

	function app_Requisiciones_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->Principal();
		return true;
	}
	
	function UsuariosRequisiciones()//Funci?n de permisos
	{
		list($dbconn) = GetDBconn();
		
		$query = "SELECT 	a.empresa_id,
											b.razon_social AS descripcion1,
											a.centro_utilidad,
											c.descripcion AS descripcion2,
											a.usuario_id,
											d.nombre
							FROM 	userpermisos_requisiciones AS a,
										empresas AS b,
										centros_utilidad AS c,
										system_usuarios AS d
							WHERE a.usuario_id=".UserGetUID()."
							AND 	a.empresa_id=b.empresa_id
							AND 	a.centro_utilidad=c.centro_utilidad
							AND 	a.empresa_id=c.empresa_id
							AND 	a.usuario_id=d.usuario_id;";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$result->EOF)
		{
			$vars[$result->fields[1]][$result->fields[3]]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		
		$mtz[0]='EMPRESAS';
		$mtz[1]='CENTRO DE UTILIDAD';
		$url[0]='app';
		$url[1]='Requisiciones';
		$url[2]='user';
		$url[3]='PrincipalRequisicion';
		$url[4]='PermisoReq';
		
		$this->salida .=gui_theme_menu_acceso('REQUISICIONES', $mtz, $vars, $url, ModuloGetURL('system','Menu'));
		return true;
	}

	function SetStyle($campo)//Mensaje de error en caso de no encontrar los datos
	{
		if ($this->frmError[$campo] || $campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
				return ("<tr><td class='label_error' colspan='2' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			else
			{
				return ("label_error");
			}
		}
		return ("label");
	}
	
	function BuscarDepartamentos($empresa)
	{
		list($dbconn) = GetDBconn();
		
		$query = "SELECT departamento,
											descripcion
							FROM departamentos
							WHERE empresa_id='".$empresa."'
							ORDER BY descripcion;";
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - BuscarDepartamentos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$result->EOF)
		{
			$vars[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		return $vars;
	}
	
	function GuardarDatos_CrearReq($departamento,$razonsod)
	{
		$depto=explode("__",$departamento);
		
		list($dbconn) = GetDBconn();

		$query = "SELECT nextval('compras_requisiciones_requisicion_id_seq');";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - GuardarDatos_CrearRequisicion SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$requisicion=$result->fields[0];
		
		$query = "INSERT INTO compras_requisiciones
							(
								requisicion_id,
								empresa_id,
								departamento,
								usuario_id,
								fecha_requisicion,
								razon_solicitud,
								estado
							)
							VALUES
							(
								$requisicion,
								'".$_SESSION['Req']['empresa_id']."',
								'".$depto[0]."',
								".UserGetUID().",
								now(),
								'".$razonsod."',
								'1'
							);";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - GuardarDatosTmp";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$_SESSION['Req']['requisicio']=$requisicion;
		$_SESSION['Req']['departaide']=$depto[0];
		$_SESSION['Req']['departades']=$depto[1];
		$_SESSION['Req']['fecharequi']=date("d-m-Y");
		$_SESSION['Req']['razonsod']=$razonsod;
		
		return true;
	}
	
	function GetInvGrupos()
	{
		list($dbconn) = GetDBconn();
		
		$query = "SELECT grupo_id,descripcion
							FROM inv_grupos_inventarios";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - GetInvClases";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
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
	
	function GetInvClases($grupo)
	{
		list($dbconn) = GetDBconn();
		
		if(!empty($grupo))
		{
			$p="WHERE grupo_id='$grupo'";
		}
		
		$query = "SELECT grupo_id,clase_id,descripcion
							FROM inv_clases_inventarios
							$p";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - GetInvClases";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
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
	
	function GetInvSubClases($grupo,$clase)
	{
		list($dbconn) = GetDBconn();
		
		if(!empty($grupo) AND !empty($clase))
		{
			$p="WHERE grupo_id='$grupo' 
					AND clase_id='$clase' ";
		}
		
		$query = "SELECT grupo_id,clase_id,descripcion
							FROM inv_subclases_inventarios
							$p";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - GetInvSubClases";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
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
	
	function ListaCotizarCompra($empresa,$requisicion,$codigo=null,$descp=null,$grupo=null,$clase=null,$subclase=null,$rest=null,$pagina=null)
	{
		$busqueda="";
		
		if(!empty($codigo))
			$busqueda.="AND b.codigo_producto ILIKE '$codigo%'";
		
		if(!empty($descp))
			$busqueda.="AND b.descripcion ILIKE '%$descp%'";
		
		if(!empty($grupo))
			$busqueda.="AND b.grupo_id='$grupo'";
		
		if(!empty($clase))
			$busqueda.="AND b.clase_id='$clase'";
		
		if(!empty($subclase))
			$busqueda.="AND b.subclase_id='$subclase'";
		
		list($dbconn) = GetDBconn();
		
		$query = "SELECT 	count(*)
							FROM inventarios AS a,
							inventarios_productos AS b
							$rest JOIN compras_requisiciones_detalle AS c 
							ON
							(
								c.requisicion_id=".$requisicion."
								AND c.codigo_producto=b.codigo_producto
							),
							unidades AS d
							WHERE a.empresa_id='".$empresa."'
							AND a.codigo_producto=b.codigo_producto
							AND a.estado=1
							AND b.unidad_id=d.unidad_id
							$busqueda
							";
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - ListaCotizarCompra Count()";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$this->conteo=$result->fields[0];
		$this->ProcesarSqlConteo(20,$pagina);
		
		if(!empty($rest))
			$order="ORDER BY b.descripcion";
		else
			$order="ORDER BY c.secuencia_pro";
		
		$query = "SELECT 	b.codigo_producto,
											b.descripcion,
											b.porc_iva,
											b.contenido_unidad_venta,
											d.descripcion AS desunidad,
											c.cantidad,
											c.requisicion_id
							FROM inventarios AS a,
							inventarios_productos AS b
							$rest JOIN compras_requisiciones_detalle AS c 
							ON
							(
								c.requisicion_id=".$requisicion."
								AND c.codigo_producto=b.codigo_producto
							),
							unidades AS d
							WHERE a.empresa_id='".$empresa."'
							AND a.codigo_producto=b.codigo_producto
							AND a.estado=1
							AND b.unidad_id=d.unidad_id
							$busqueda
							$order
							LIMIT ".$this->limit." OFFSET ".$this->offset."";
							
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - ListaCotizarCompra";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
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
	
	function ListarProductosCompra($empresa,$requisiciones)
	{
		list($dbconn) = GetDBconn();
		$i=0;
		foreach($requisiciones as $key=>$requisicion)
		{
			$query.= "(
									SELECT 	b.codigo_producto,
													b.descripcion,
													b.porc_iva,
													b.contenido_unidad_venta,
													d.descripcion AS desunidad,
													c.cantidad,
													a.costo,
													c.requisicion_id
									FROM 	inventarios AS a,
												inventarios_productos AS b
									JOIN 	compras_requisiciones_detalle AS c 
									ON
									(
										c.requisicion_id=".$requisicion."
										AND c.codigo_producto=b.codigo_producto
									),
									unidades AS d
									WHERE a.empresa_id='".$empresa."'
									AND a.codigo_producto=b.codigo_producto
									AND a.estado=1
									AND b.unidad_id=d.unidad_id
									
								)
								";
			if($i!=sizeof($requisiciones)-1)
				$query.= "UNION";
			
			$i++;
		}
		
		$query1 = "	
										SELECT DISTINCT A.codigo_producto,
															A.descripcion,
															A.porc_iva,
															A.contenido_unidad_venta,
															A.costo as valor,
															A.porc_iva as porcentaje_iva,
															A.descripcion AS desunidad,
															sum(A.cantidad) AS cantidad
											FROM 
											(
												$query
											)as A 
											GROUP BY 1,2,3,4,5,6,7
											ORDER BY A.descripcion";
						
		$result = $dbconn->Execute($query1);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - ListarProductosCompra";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
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
	
	function GuardarDatosOrdenCompra()//Guarda la orden de pedido una vez se haya confirmado
	{
		list($dbconn) = GetDBconn();
		
		$dbconn->BeginTrans();
		
		$proveedor=explode('.-.',$_REQUEST['proveedor']);
		
		$query = "SELECT orden_pedido_id
							FROM compras_ordenes_pedidos
							WHERE empresa_id='".$_SESSION['Req']['empresa_id']."'
							AND codigo_proveedor_id=".$proveedor[3]."
							AND estado='1';";
				
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->fields[0]<>NULL)
		{
			$ordenpedid=$result->fields[0];
		}
		else
		{
			$query = "SELECT NEXTVAL ('compras_ordenes_pedidos_orden_pedido_id_seq');";
			
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
			
			$ordenpedid=$result->fields[0];
			
			$query = "INSERT INTO compras_ordenes_pedidos
								(
									orden_pedido_id,
									codigo_proveedor_id,
									empresa_id,
									fecha_orden,
									estado,
									usuario_id
								)
								VALUES
								(
									".$ordenpedid.",
									".$proveedor[3].",
									'".$_SESSION['Req']['empresa_id']."',
									'".date("Y-m-d")."',
									'1',
									".UserGetUID()."
								);";
			
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
		}
		
		$datos=$_REQUEST['datosTodo'];
		
		foreach($datos as $key=>$valor)
		{
			list($codigoPro,$descripcion,$porcentaje_iva,$descunidad,$contenidoPre,$pos,$valor_costo,$cantidad,$valor_neto,$valor_total)=explode(".-.",$valor);
			
			$query = "INSERT INTO compras_ordenes_pedidos_detalle
								(
									orden_pedido_id,
									codigo_producto,
									numero_unidades,
									valor,
									porc_iva,
									estado
								)
								VALUES
								(
									".$ordenpedid.",
									'".$codigoPro."',
									".$cantidad.",
									".$valor_costo.",
									".$porcentaje_iva.",
									'1'
								);";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollBackTrans();
				return false;
			}
		}
		$dbconn->CommitTrans();
		$this->FormaMensaje($ordenpedid);
		return true;
	}
	
	
	
	function EliminarPro($codigo,$requisicion)
	{
		list($dbconn) = GetDBconn();
		
		$query = "DELETE FROM compras_requisiciones_detalle
							WHERE codigo_producto='$codigo'
							AND requisicion_id='$requisicion'";
								
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - EliminarPro";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		return true;
	}
	
	function UpdateCantidadRequisicion($codigo,$requisicion,$cantidad,$sw)
	{
		list($dbconn) = GetDBconn();
		
		if($sw=="1")
		{
			$query = "UPDATE compras_requisiciones_detalle
								SET cantidad=$cantidad
								WHERE codigo_producto='$codigo'
								AND requisicion_id=$requisicion;";
		}
		else
		{
			$query = "UPDATE compras_requisiciones_productos_no_catalogados
								SET cantidad=$cantidad
								WHERE numero=$codigo
								AND requisicion_id=$requisicion;";
		}
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - UpdateCantidadRequisicion $sw";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		return true;
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
	
	function GuardarX($codigo,$requisicion,$cantidad)
	{
		list($dbconn) = GetDBconn();
		
		$query = "INSERT INTO compras_requisiciones_detalle
							(
								requisicion_id,
								codigo_producto,
								cantidad,
								estado
							)
							VALUES
							(
								$requisicion,
								'$codigo',
								$cantidad,
								'1'
							);
						";
						
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - GuardarX SQL 1";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			
			$query = "
								SELECT max(secuencia_pro)
								FROM compras_requisiciones_detalle;
								";
						
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo Requisiciones - GuardarX SQL 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$seq=$result->fields[0]+1;
			
			$query = "UPDATE compras_requisiciones_detalle
								SET secuencia_pro=$seq
								WHERE requisicion_id=$requisicion
								AND codigo_producto='$codigo'
							";
						
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo Requisiciones - GuardarX SQL 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
				
		return true;
	}
	
	function GetProductoSel($codigo,$requisicion)
	{
		list($dbconn) = GetDBconn();
		
		$query = "SELECT count(*)
							FROM compras_requisiciones_detalle 
							WHERE requisicion_id=".$requisicion."
							AND codigo_producto='".$codigo."'
						";
						
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - GetProductoSel";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->fields[0] > 0)
			return true;
		else
			return false;
	}
	
	function GuardarNoProdCatalog($requisicion,$empresa,$departamento,$producto,$proveedor,$generico,$cantidad,$justif)
	{
		list($dbconn) = GetDBconn();

		if(!$proveedor)
			$proveedor="NULL";
		else
			$proveedor="'$proveedor'";
			
		if(!$generico)
			$generico="NULL";
		else
			$generico="'$generico'";
		
		$query = "INSERT INTO compras_requisiciones_productos_no_catalogados
							(
								requisicion_id,
								empresa_id,
								departamento,
								nombre_producto,
								nombre_proveedor,
								nombre_generico,
								cantidad,
								justificacion,
								usuario_id
							)
							VALUES
							(
								$requisicion,
								'$empresa',
								'$departamento',
								'$producto',
								$proveedor,
								$generico,
								$cantidad,
								'$justif',
								".UserGetUID()."
							);
						";
						
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - GuardarNoProdCatalog";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		return true;
	}
	
	function GetListadoProNoCatalog($requisicion)
	{
		list($dbconn) = GetDBconn();
						
		$query = "SELECT 	*
							FROM compras_requisiciones_productos_no_catalogados
							WHERE requisicion_id=$requisicion";
							
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - GetListadoProNoCatalog";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
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
	
	function EliminarProNoCatalog($codigo,$requisicion)
	{
		list($dbconn) = GetDBconn();
		
		$query = "DELETE FROM compras_requisiciones_productos_no_catalogados
							WHERE numero=$codigo
							AND requisicion_id='$requisicion'";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - EliminarProNoCatalog";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		return true;
	}
	
	function BuscarRequisicionCompra($empresa,$pagina,$requisicion,$fecha_ini,$fecha_fin,$departamento,$evento,$est1,$est2,$est3)
	{
		$busqueda="";

		if(!empty($requisicion))
			$busqueda.="AND a.requisicion_id=$requisicion";
		
		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			$busqueda.="AND date(a.fecha_requisicion)>='$fecha_ini'
									AND date(a.fecha_requisicion)<='$fecha_fin'";
		}
		
		if(!empty($departamento))
			$busqueda.="AND a.departamento='$departamento'";
		
		$order="ORDER BY a.fecha_requisicion DESC, a.requisicion_id DESC";
		
		
		/*if($evento==1)
		{
			$add="AND a.sw_listo='0'";
		}*/
		
		if($evento!=3)
		{
			$est="AND a.estado='1' $add";
		}
		else
		{
			if($est1=="true")
				$est="";
			elseif($est2=="true")
				$est="AND a.estado='1'";
			elseif($est3=="true")
				$est="AND a.estado='0'";
		}

		list($dbconn) = GetDBconn();
		
		$query = "SELECT count(*)
							FROM 	compras_requisiciones AS a,
										system_usuarios AS b,
										departamentos AS c
							WHERE a.empresa_id='".$empresa."'
							$est
							AND 	a.usuario_id=b.usuario_id
							AND 	a.empresa_id=c.empresa_id
							AND 	a.departamento=c.departamento
							$busqueda
							";
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - BuscarRequisicionCompra Count()";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$this->conteo=$result->fields[0];
		$this->ProcesarSqlConteo(20,$pagina);
		
		$query = "
							SELECT 	a.requisicion_id,
											a.departamento,
											--a.sw_listo,
											a.usuario_id,
											a.fecha_requisicion,
											a.razon_solicitud,
											a.observacion,
											a.estado,
											b.nombre,
											c.descripcion,
											(
												SELECT COUNT(d.codigo_producto)
												FROM compras_requisiciones_detalle AS d
												WHERE d.requisicion_id=a.requisicion_id
											) AS cantidad,
											(
												SELECT COUNT(*)
												FROM compras_requisiciones_productos_no_catalogados AS e
												WHERE e.requisicion_id=a.requisicion_id
											) AS cantidad2
							FROM 	compras_requisiciones AS a,
										system_usuarios AS b,
										departamentos AS c
							WHERE a.empresa_id='".$empresa."'
							$est
							AND 	a.usuario_id=b.usuario_id
							AND 	a.empresa_id=c.empresa_id
							AND 	a.departamento=c.departamento
							$busqueda
							$order
							LIMIT ".$this->limit." OFFSET ".$this->offset."";

		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - BuscarRequisicionCompra";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
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
	
	function ConfirmaRequisicion($requisicion)
	{
		list($dbconn) = GetDBconn();
		
		$query = "UPDATE compras_requisiciones
							SET sw_listo='1'
							WHERE requisicion_id=$requisicion";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - ConfirmaRequisicion";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		return true;
	}
	
	function RequiscionRealizada($requisicion)
	{
		list($dbconn) = GetDBconn();
		
		$query = "	SELECT COUNT(*)
									 	FROM compras_requisiciones
										WHERE requisicion_id=$requisicion
										--AND sw_listo='1'";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - ConfirmaRequisicion";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		return $result->fields[0];
	}
	
	
	function BuscarReq($empresa)
	{
		list($dbconn) = GetDBconn();
		
		$query = "
							SELECT 	a.requisicion_id,
											a.departamento,
											a.usuario_id,
											a.fecha_requisicion,
											a.razon_solicitud,
											a.observacion,
											a.estado,
											b.nombre,
											c.descripcion,
											(
												SELECT COUNT(d.codigo_producto)
												FROM compras_requisiciones_detalle AS d
												WHERE d.requisicion_id=a.requisicion_id
											) AS cantidad,
											(
												SELECT COUNT(*)
												FROM compras_requisiciones_productos_no_catalogados AS e
												WHERE e.requisicion_id=a.requisicion_id
											) AS cantidad2
							FROM 	compras_requisiciones AS a,
										system_usuarios AS b,
										departamentos AS c
							WHERE a.empresa_id='".$empresa."'
							AND 	a.estado='1'
							AND 	a.usuario_id=b.usuario_id
							AND 	a.empresa_id=c.empresa_id
							AND 	a.departamento=c.departamento
							ORDER BY a.fecha_requisicion,a.requisicion_id
							";

		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - BuscarReq";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
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
	
	function GuardarRazonCancelacion($jutif,$requisicion)
	{
		list($dbconn) = GetDBconn();
		
		$query = "UPDATE compras_requisiciones 
							SET observacion='$jutif', estado='0'
							WHERE requisicion_id=$requisicion";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - CancelarRequisicion";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		return true;
	
	}
	
	function GetProveedores()
	{
		list($dbconn) = GetDBconn();
		
		$query = 	"
								SELECT 	a.tipo_id_tercero,
												a.tercero_id,
												a.codigo_proveedor_id,
												b.nombre_tercero
								FROM	terceros_proveedores as a
								JOIN terceros as b
								ON
								(
									a.tipo_id_tercero = b.tipo_id_tercero
									AND a.tercero_id = b.tercero_id
								)
								ORDER BY b.nombre_tercero;
							";

		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Requisiciones - GetProveedores";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
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
	
	function FechaStamp($fecha)
	{
		if($fecha)
		{
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			
			return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
	}
	
}//fin de la clase
?>