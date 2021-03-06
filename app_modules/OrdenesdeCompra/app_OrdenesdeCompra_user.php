<?php

/**
 * $Id: app_OrdenesdeCompra_user.php,v 1.9 2007/08/13 13:16:47 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 */

class app_OrdenesdeCompra_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;

	function app_OrdenesdeCompra_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->Principal();
		return true;
	}
	
	function UsuariosOrdenesdeCompra()//Funci� de permisos
	{
		list($dbconn) = GetDBconn();
		
		$query = "SELECT 	a.empresa_id,
											b.razon_social AS descripcion1,
											a.centro_utilidad,
											c.descripcion AS descripcion2,
											a.usuario_id,
											d.nombre
							FROM 	userpermisos_compras AS a,
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
		$url[1]='OrdenesdeCompra';
		$url[2]='user';
		$url[3]='PrincipalOC';
		$url[4]='PermisoOC';
		
			$this->salida .=gui_theme_menu_acceso('ORDENES DE COMPRA', $mtz, $vars, $url, ModuloGetURL('system','Menu'));
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
													c.cantidad_ordenada,
													a.costo,
													c.requisicion_id,
													a.precio_venta
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
									AND c.cantidad_ordenada < c.cantidad
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
															A.desunidad,
															A.precio_venta,
															sum(A.cantidad-A.cantidad_ordenada) AS cantidad
											FROM 
											(
												$query
											)as A 
											GROUP BY 1,2,3,4,5,6,7,8
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
		$_SESSION['OrdenCompra']['requisiciones']=$requisiciones;
		return $vars;
	}
	
	function GuardarDatosOrdenCompra()//Guarda la orden de pedido una vez se haya confirmado
	{
		list($dbconn) = GetDBconn();

		$proveedor=explode('.-.',$_REQUEST['proveedor']);
		
		/*$query = "SELECT orden_pedido_id
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
		{*/
			$query = "SELECT NEXTVAL ('compras_ordenes_pedidos_orden_pedido_id_seq');";
			
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
									'".$_SESSION['OC']['empresa_id']."',
									'".date("Y-m-d")."',
									'1',
									".UserGetUID()."
								);";
			
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo OrdenesdeCompra tabla:compras_ordenes_pedidos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		//}
		
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
				$this->error = "Error al Cargar el Modulo compras_ordenes_pedidos_detalle";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}


            //var_dump($_SESSION['OrdenCompra']['requisiciones']);
			foreach($_SESSION['OrdenCompra']['requisiciones'] as $valorR)
			{
				$query = "
										SELECT a.requisicion_id,cantidad,cantidad_ordenada
										FROM compras_requisiciones as a,
										compras_requisiciones_detalle as b
										WHERE a.estado='1'
										AND a.requisicion_id=b.requisicion_id
										AND b.codigo_producto = '$codigoPro'
										AND a.requisicion_id=$valorR
										AND b.cantidad_ordenada < b.cantidad
										ORDER BY a.requisicion_id
								";
					
				$result = $dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo OrdenesdeCompra - GuardarDatosOrdenCompra - SQL 1";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					$datosX=array();
					if($result->RecordCount() > 0)
					{
						while(!$result->EOF)
						{
							$datosX[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}
					}
				}



                $query = "UPDATE    compras_requisiciones
                             SET    orden_pedido_id=$ordenpedid,
                                    estado='2'
                            WHERE   requisicion_id=".$valorR.";";
                                            
                $result = $dbconn->Execute($query);

                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo OrdenesdeCompra - GuardarDatosOrdenCompra - Actualizar Orden Compra";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }


				$cantidad1=$cantidad;
				foreach($datosX as $valorX)
				{
					if($cantidad1 > 0)
					{
						if($cantidad1 >= ($valorX['cantidad']-$valorX['cantidad_ordenada']))
						{
							$cantidad1=$cantidad1 - ($valorX['cantidad']-$valorX['cantidad_ordenada']);
							$can=$valorX['cantidad'];
						}
						else
						{
							$can=$valorX['cantidad_ordenada']+$cantidad1;
							$cantidad1=0;
						}
						
						$query = "UPDATE compras_requisiciones_detalle
											SET cantidad_ordenada=$can
											WHERE codigo_producto='$codigoPro'
											AND requisicion_id=".$valorX['requisicion_id'].";";
											
						$result = $dbconn->Execute($query);
						
						if($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al Cargar el Modulo OrdenesdeCompra - GuardarDatosOrdenCompra - SQL 2";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
					}
				}
			}
		}
		
		$valorX['orden_pedido_id']=$ordenpedid;
		//$prov=$this->GetProveedores($proveedor[3]);
		$valorX['nombre_proveedor']=$proveedor[3];
		$valorX['fecha_orden']=date("Y-m-d");
		$usua=$this->GetNameUsuario(UserGetUID());
		$valorX['nombre_usuario']=$usua['nombre'];
		
		$this->FormaMensaje($valorX);
		return true;
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
											) AS cantidad2,
											(
												SELECT sum(g.cantidad) - sum(g.cantidad_ordenada)
												FROM compras_requisiciones_detalle AS g
												WHERE g.requisicion_id=a.requisicion_id
											) AS dif
							FROM 	compras_requisiciones AS a,
										system_usuarios AS b,
										departamentos AS c,
										compras_requisiciones_detalle AS f
							WHERE a.empresa_id='".$empresa."'
							AND 	a.estado='1'
							AND 	a.usuario_id=b.usuario_id
							AND 	a.empresa_id=c.empresa_id
							AND 	a.departamento=c.departamento
							AND 	f.requisicion_id=a.requisicion_id
							AND 	f.cantidad_ordenada < f.cantidad
							GROUP BY 1,2,3,4,5,6,7,8,9,10,11
							ORDER BY a.requisicion_id
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
	
	
	function GetProveedores($proveedor=null)
	{
		list($dbconn) = GetDBconn();
		
		if($proveedor)
			$filtro="AND a.codigo_proveedor_id=$proveedor";
		
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
								WHERE estado='1'
								$filtro
								ORDER BY b.nombre_tercero;
							";

		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdeCompra - GetProveedores";
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
	
	function GetNameUsuario($usuario_id)
	{
		
		list($dbconn) = GetDBconn();
		
		$query = 	"
							SELECT 	nombre
							FROM	system_usuarios
							WHERE usuario_id=$usuario_id
						";

		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdeCompra - GetNameUsuario";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	
	}
	
	function ListaCotizarCompra($empresa,$requisicion)
	{
		$busqueda="";
		
		list($dbconn) = GetDBconn();
		
		$query = "SELECT 	b.codigo_producto,
											b.descripcion,
											b.porc_iva,
											b.contenido_unidad_venta,
											d.descripcion AS desunidad,
											c.cantidad,
											c.requisicion_id
							FROM inventarios AS a,
							inventarios_productos AS b
							JOIN compras_requisiciones_detalle AS c 
							ON
							(
								c.requisicion_id=".$requisicion."
								AND c.codigo_producto=b.codigo_producto
							),
							unidades AS d
							WHERE a.empresa_id='".$empresa."'
							AND a.codigo_producto=b.codigo_producto
							AND a.estado='1'
							AND b.unidad_id=d.unidad_id
							ORDER BY b.descripcion";
							
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdeCompra - ListaCotizarCompra";
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
	
	function GetOrdenesCompra($proveedor)
	{
		
		list($dbconn) = GetDBconn();
		
		$query = 	"
								SELECT 	orden_pedido_id
								FROM	compras_ordenes_pedidos
								WHERE codigo_proveedor_id=$proveedor
								ORDER BY orden_pedido_id;
							";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdeCompra - GetOrdenesCompra";
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
		
		$result->Close();
		
		return $vars;
	}
	
	function GetProveedoresOrdenCompra()
	{
			
		list($dbconn) = GetDBconn();
		
		$query = 	"
								SELECT 	DISTINCT a.tipo_id_tercero,
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
								JOIN compras_ordenes_pedidos as c
								ON
								(
									a.codigo_proveedor_id=c.codigo_proveedor_id
								)
								ORDER BY b.nombre_tercero;
							";
		
		$result = $dbconn->Execute($query);
	
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdeCompra - GetOrdenesCompra";
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
		$result->Close();
		
		return $vars;
	}
	
	
	function BuscarOrdenes($pagina,$proveedor,$orden)
	{
		list($dbconn) = GetDBconn();
		
		if(!empty($proveedor))
		{
			$filtro.=" AND a.codigo_proveedor_id=$proveedor";
		}
		
		if(!empty($orden))
		{
			$filtro.=" AND a.orden_pedido_id=$orden";
		}
		
		$query = 	"
								SELECT 	count(*)
								FROM compras_ordenes_pedidos as a,
								terceros_proveedores as b,
								system_usuarios as c,
								terceros as d
								WHERE a.usuario_id=c.usuario_id
								AND a.codigo_proveedor_id=b.codigo_proveedor_id
								AND b.tercero_id=d.tercero_id
								AND b.tipo_id_tercero=d.tipo_id_tercero
								$filtro
							";
		
		$result = $dbconn->Execute($query);
		
		$this->conteo=$result->fields[0];
		$this->ProcesarSqlConteo(15,$pagina);
		
		$query = 	"
								SELECT 	a.orden_pedido_id,
												a.codigo_proveedor_id,
												b.tipo_id_tercero,
												b.tercero_id,
												a.fecha_orden,
												a.usuario_id,
												CASE a.estado
												WHEN '1' THEN 'ACTIVA'
												WHEN '3' THEN 'ENVIADA'
												END as estado,
												c.nombre as nombre_usuario,
												d.nombre_tercero as nombre_proveedor,
												(
													SELECT sum(e.numero_unidades*(e.valor+(e.valor*e.porc_iva)/100))
													FROM compras_ordenes_pedidos_detalle as e
													WHERE e.orden_pedido_id=a.orden_pedido_id
												) as total_compra
								FROM compras_ordenes_pedidos as a,
								terceros_proveedores as b,
								system_usuarios as c,
								terceros as d
								WHERE a.usuario_id=c.usuario_id
								AND a.codigo_proveedor_id=b.codigo_proveedor_id
								AND b.tercero_id=d.tercero_id
								AND b.tipo_id_tercero=d.tipo_id_tercero
								$filtro
								ORDER BY a.fecha_orden
								LIMIT ".$this->limit." OFFSET ".$this->offset."
							";
		
		$result = $dbconn->Execute($query);
	
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdeCompra - GetOrdenesCompra";
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
		$result->Close();
		
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
	
	function EnviarOrdenPedidoProCompra($proveedor,$orden)
	{
		list($dbconn) = GetDBconn();
		
		$query = "UPDATE compras_ordenes_pedidos
							SET estado='3',
							fecha_envio='".date("Y-m-d")."'
							WHERE orden_pedido_id=$orden
							AND codigo_proveedor_id=$proveedor
							AND empresa_id='".$_SESSION['OC']['empresa_id']."';";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdeCompra - EnviarOrdenPedidoProCompra";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		return true;
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