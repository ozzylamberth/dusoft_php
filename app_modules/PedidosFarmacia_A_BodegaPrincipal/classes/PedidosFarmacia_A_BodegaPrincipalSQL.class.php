<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: PedidosFarmacia_A_BodegaPrincipalSQL.class.php,v 1.3 2010/02/03 14:15:01 sandra Exp $Revision: 1.3 $
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/

	class PedidosFarmacia_A_BodegaPrincipalSQL extends ConexionBD
	{
	/*
	* Constructor de la clase
	*/
	function PedidosFarmacia_A_BodegaPrincipalSQL(){}

	/**
	* Funcion donde se Consultan los Tipos de identificacion 
	* @return array $datos vector que contiene la informacion de la consulta de los Tipos 
	* de Identificacion
	*/

		function ObtenerPermisos()
		{
			/*$sql  = "SELECT   	a.empresa_id, ";
			$sql .= "           b.razon_social AS descripcion1, ";
			$sql .= "           a.centro_utilidad, ";
			$sql .= "           c.descripcion AS descripcion2, ";
			$sql .= "           a.usuario_id ";
			$sql .= "FROM 	    userpermisos_Pedidos_Farmacia_a_BPrincipal a ";
			$sql .= "           JOIN centros_utilidad AS c ON (a.empresa_id = c.empresa_id)
								AND (a.centro_utilidad = c.centro_utilidad)";
			$sql .= "           JOIN empresas AS b ON (c.empresa_id = b.empresa_id)";
			$sql .= "WHERE      a.usuario_id= ".UserGetUID()."  ";
			$sql .= "           AND b.sw_tipo_empresa='1' ";
			$sql .= "           ORDER BY a.empresa_id ";*/
			//			$this->debug=true;
			
			$sql = "SELECT 
			a.empresa_id,
			d.razon_social as descripcion1,
			a.centro_utilidad,
			c.descripcion as descripcion2,
			b.bodega,
			b.descripcion as descripcion3
			FROM
			userpermisos_pedidos_farmacia_a_bprincipal AS a
			JOIN bodegas as b ON (a.empresa_id = b.empresa_id)
			AND (a.centro_utilidad = b.centro_utilidad)
			AND (b.estado = '1')
			JOIN centros_utilidad as c ON (b.empresa_id = c.empresa_id)
			AND (b.centro_utilidad = c.centro_utilidad)
			JOIN empresas as d ON (c.empresa_id = d.empresa_id)
			AND (sw_activa = '1')
			WHERE
			a.usuario_id = '".UserGetUID()."';";
			/*print_r($sql);*/
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[$rst->fields[1]][$rst->fields[3]][$rst->fields[5]] = $rst->GetRowAssoc($ToUpper = false);
			/*$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);*/
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	/*
		* Funcion donde se Consultan los centros de Utilidades de la farmacia.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	   	function ListarCentrodeUtilidad($empresa)
		{
			$sql  = "SELECT   	empresa_id, centro_utilidad,descripcion,Ubicacion";
			$sql .= "           From centros_utilidad  ";
			$sql .= "WHERE      empresa_id='".trim($empresa)."'  ";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;	
		}
	/*
		* Funcion donde se obtienen las bodegas asociadas a la farmacia..
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ObtenerBodegaFarmacia($empresa,$centro_utilidad)
		{
		
			$sql  = "SELECT   	e.bodega, ";
			$sql .= "           e.descripcion AS descripcion3   ";
			$sql .= "FROM 	    empresas AS b, ";
			$sql .= "           centros_utilidad AS c, ";
			$sql .= "           bodegas AS e ";
			$sql .= "WHERE     	e.empresa_id=c.empresa_id  ";
			$sql .= "           AND 	e.empresa_id = c.empresa_id ";
			$sql .= "           AND 	e.centro_utilidad = c.centro_utilidad ";
			$sql .= "			AND		c.empresa_id = b.empresa_id";
			$sql .= "           AND 	c.empresa_id='".trim($empresa)."'
								
								AND  b.sw_tipo_empresa='1' ";
			$sql .= "           ORDER BY e.descripcion; ";/*AND     c.centro_utilidad = '".trim($centro_utilidad)."'*/
			/*print_r($sql);*/
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;	
		}
	/*
		* Funcion donde se obtienen los tipos de productos que existen
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	
		function TipoProductos()
		{
			
			$sql  = "SELECT   	tipo_producto_id,descripcion FROM inv_tipo_producto order by tipo_producto_id ASC ";
		
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;	
		}
	/*
		* Funcion donde se obtienen el principio activo o moleculas de los productos
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function inv_moleculas()
		{
		
			$sql  = "SELECT   molecula_id, ";
			$sql .="		      descripcion, ";
			$sql .="		      concentracion,unidad_medida_medicamento_id ";
			$sql .="FROM      inv_moleculas ";
			$sql .="ORDER by  descripcion";
					
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;	
		}
	/*
		* Funcion donde se obtiene el perfil terapeutico de los productos.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function Inv_med_anatofarmacologico()
		{
			//  $this->debug = true;
			$sql  = "SELECT   cod_anatomofarmacologico,descripcion FROM inv_med_cod_anatofarmacologico    ORDER by descripcion ";
		
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;	
		}
	/*
		* Funcion donde se obtienen todos los productos de la bodega  seleccionada  de la farmacia
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ListarTodoProductosDeFarmacia($tipo_producto_id,$empresa,$bodega,$filtros,$offset)
		 {
	    /*$this->debug=true;*/
$sql = " Select 
x.existencia,
 x.empresa_id, 
 x.codigo_producto,
 x.local_prod, 
 fc_descripcion_producto(x.codigo_producto)as producto,
 c.descripcion as laboratorio,
 s.descripcion as molecula, 
 p.sw_requiereautorizacion_despachospedidos,
 p.codigo_alterno, 
 p.codigo_barras, 
 p.contenido_unidad_venta, 
 u.descripcion as unidad
 From existencias_bodegas AS x 
 JOIN inventarios AS i ON (x.empresa_id=i.empresa_id)
 AND (x.codigo_producto=i.codigo_producto)
 JOIN inventarios_productos AS p ON (i.codigo_producto=p.codigo_producto) 
 JOIN inv_tipo_producto AS ti ON (p.tipo_producto_id=ti.tipo_producto_id) 
 JOIN inv_subclases_inventarios AS s ON (p.grupo_id=s.grupo_id) 
 AND (p.clase_id=s.clase_id) 
 AND (p.subclase_id=s.subclase_id) 
 JOIN inv_clases_inventarios AS c ON (s.grupo_id=c.grupo_id) 
 AND (s.clase_id=c.clase_id)
 JOIN unidades AS u ON (u.unidad_id=p.unidad_id) 
 LEFT JOIN inv_med_cod_anatofarmacologico AS cod ON (p.cod_anatofarmacologico=cod.cod_anatomofarmacologico)
 WHERE  TRUE      		
            and     x.estado='1'   
      	     and     x.bodega = '".trim($bodega)."'
            and     x.empresa_id = '".trim($empresa)."' 
            and     ti.tipo_producto_id='".trim($tipo_producto_id)."'
			and p.estado='1' ";
  
  /*and     p.sw_requiereautorizacion_despachospedidos='0'  */
  
			if($filtros['cod_anatomofarmacologico']!= "")
			$sql.=" AND  p.cod_anatofarmacologico  ILIKE '%".trim($filtros['cod_anatomofarmacologico'])."%' ";
			
			if($filtros['molecula_id']!= "")
			$sql.=" and s.descripcion  ILIKE '%". $filtros['molecula_id']."%' ";
						
			if($filtros['codigo_producto'])
			{
			$sql.=" and p.codigo_producto= '".$filtros['codigo_producto']."' ";
			}
			
			if($filtros['descripcion'] != "")
			$sql .= "AND    p.descripcion  ILIKE '%".$filtros['descripcion']."%' ";
			
			
			$cont="select COUNT(*) from (".$sql.") AS A";
			$sql .= "  group by x.existencia, 
								x.empresa_id,
								x.codigo_producto,
								x.local_prod,
								c.descripcion,
								s.descripcion,
								p.sw_requiereautorizacion_despachospedidos,
								p.descripcion,
								p.codigo_alterno, 
								p.codigo_barras,
								p.contenido_unidad_venta,
								u.descripcion
								ORDER by   p.descripcion ";
			/*print_r($sql);    */
			$this->ProcesarSqlConteo($cont,$offset);
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			//echo $sql;
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			$datos = array();
			while (!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	/*
		*  Funcion donde se insertan los datos a la tabla temporal solicitud_pro_a_bod_prpal_tmp
		*  @return  boolean de acuerdo a la ejecucion del sql.
	*/
		function IngresarDatosSolicitud_pro_a_bod_prpal_tmp($far,$Centrid,$bod,$codigo_producto,$cantidad,$tipoprod)
		{
  /*$this->debug=true;*/
		    $cade=$far."".$Centrid."".$codigo_producto;
							
			$this->ConexionTransaccion();
			
			$sql = "INSERT INTO solicitud_pro_a_bod_prpal_tmp
						(
							soli_a_bod_prpal_tmp_id,	
							farmacia_id,
							centro_utilidad,
							bodega,
							codigo_producto,
							cantidad_solic,
							usuario_id,
							tipo_producto
						)
						VALUES
						(
							 '".trim($cade)."',
							 '".trim($far)."',
							'".trim($Centrid)."',
							  '".trim($bod)."' ,
							'".trim($codigo_producto)."',
							 ".trim($cantidad).",
							 ".UserGetUID().",
							".trim($tipoprod)."
											);
				";
				
			  if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
				return true;
		
		}
	/*
		
		* Funcion donde se Eliminan los datos de la tabla solicitud_pro_a_bod_prpal_tmp
		* @return array $datos vector que contiene la informacion de la consulta.
	
	*/
		function Eliminar_DatosSolicitud_pro_a_bod_prpal_tmp($far,$Centrid,$bod,$codigo_producto)
		{
			
			
			$cade=$far."".$Centrid."".$codigo_producto;
			$sql = " Delete     FROM  solicitud_pro_a_bod_prpal_tmp ";
			$sql .= "where  	soli_a_bod_prpal_tmp_id='".$cade."'  and farmacia_id='".$far."'  and centro_utilidad='".$Centrid."' and bodega ='".$bod."' AND usuario_id='".UserGetUID()."' ;  ";
		    	
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	/*
		*  Funcion donde se obtienen  todos los datos de la tabla solicitud_pro_a_bod_prpal_tmp
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		
		function ConsultarSolicitud_pro_a_bod_prpal_tmp($farmacia,$centro,$bodega)
		{
			
			$sql  = "SELECT  soli_a_bod_prpal_tmp_id, ";
			$sql .="		     farmacia_id, ";
			$sql .="         centro_utilidad, ";
			$sql .="		     bodega, ";
			$sql .="		     codigo_producto, ";
			$sql .="         cantidad_solic, ";
			$sql .="		     tipo_producto,usuario_id ";
			$sql .=" FROM    solicitud_pro_a_bod_prpal_tmp  where farmacia_id='".$farmacia."' and centro_utilidad='".$centro."' and bodega='".$bodega."' and usuario_id =".UserGetUID()." ; ";

					
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    
    	/*
		*  Funcion donde se obtienen  todos los datos de la tabla solicitud_pro_a_bod_prpal_tmp por usuario
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		
		function ConsultarSolicitudPorUsuario($farmacia,$centro,$bod)
		{
		
			$sql  = "SELECT  soli_a_bod_prpal_tmp_id, ";
			$sql .="		     farmacia_id, ";
			$sql .="         centro_utilidad, ";
			$sql .="		     bodega, ";
			$sql .="		     codigo_producto, ";
			$sql .="         cantidad_solic, ";
			$sql .="		     tipo_producto,usuario_id ";
			$sql .=" FROM    solicitud_pro_a_bod_prpal_tmp  ";
      $sql .=" where   usuario_id='".UserGetUID()."'  and farmacia_id='".$farmacia."' and centro_utilidad='".$centro."' and bodega='".$bod."' ; ";

					
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
     
	/*
		*  Funcion donde se inserta la primera parte del documento de pedido  
		*  @return  boolean de acuerdo a la ejecucion del sql.
	*/		
		function IngresoSolicitud_Productos_A_Bodega_principal($far,$Centrid,$bod,$observacion,$tipoprod,$EmpresaDes,$tipo_pedido)
		{
		   
		   	$this->ConexionTransaccion();
			
			$sql = "INSERT INTO Solicitud_Productos_A_Bodega_principal
						(
							Solicitud_Prod_A_Bod_ppal_id,	
							farmacia_id,
							centro_utilidad,
							bodega,
							observacion,
						    usuario_id, 
							fecha_registro,
							empresa_destino,
							sw_despacho,
							tipo_pedido
						)
						VALUES
						(
							NEXTVAL('solicitud_productos_a_bodega_p_solicitud_prod_a_bod_ppal_id_seq'),
							 '".$far."',
							'".$Centrid."',
							  '".$bod."' ,
							'".$observacion."',
							".UserGetUID().",
							NOW(),
							'".$EmpresaDes."',
							0,
							".$tipo_pedido."
											);
							";
				
			  if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
				return true;
		}
	/*
		*  Funcion donde se obtienen  el valor maximo del campo solicitud_prod_a_bod_ppal_id de la tabla solicitud_productos_a_bodega_principal
		* @return array $datos vector que contiene la informacion de la consulta.
	*/

		function SelecMaxSolicitud_productos_a_bodega_principal($farmacia,$centro,$bodega,$empresa_destino)
		{
			//$this->debug=true;
			$sql = "SELECT (COALESCE(MAX(solicitud_prod_a_bod_ppal_id),0)) AS solicitud_prod_a_bod_ppal_id FROM  solicitud_productos_a_bodega_principal where usuario_id=".UserGetUID()." and farmacia_id='".$farmacia."'
							       and  centro_utilidad='".$centro."' and bodega='".$bodega."' and empresa_destino='".$empresa_destino."'	";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		
		
		}
	/*
		* Funcion donde se los datos de la tabla temporal por usuario
		* @return array $datos vector que contiene la informacion de la consulta.
	*/	
		
		function Consultartmp($farmacia,$centro,$bod)
		{
			//$this->debug = true;
			
			$sql  = "SELECT  soli_a_bod_prpal_tmp_id, ";
			$sql .="		 farmacia_id, ";
			$sql .="         centro_utilidad, ";
			$sql .="		 bodega, ";
			$sql .="		 codigo_producto, ";
			$sql .="         cantidad_solic, ";
			$sql .="		 tipo_producto,usuario_id ";
			$sql .=" FROM    solicitud_pro_a_bod_prpal_tmp where farmacia_id='".$farmacia."' and centro_utilidad='".$centro."' and bodega='".$bod."' and usuario_id=".UserGetUID()."; ";

					
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		
	/*
		*  Funcion donde se inserta la parte final del documento de pedido  
		*  @return  boolean de acuerdo a la ejecucion del sql.
	*/
			
		function IngresoProductos_A_Bodega_principal_detalle($Solicitud_Prod_A_Bod_ppal_id,$farmacia,$centroU,$Bodega,$ConInf)
		{
		 
			foreach($ConInf as $item=>$fila)
			{
						
				$this->ConexionTransaccion();
				
				$sql .= "INSERT INTO Solicitud_Productos_A_Bodega_principal_detalle
							(
								Solicitud_Prod_A_Bod_ppal_det_id,	
								Solicitud_Prod_A_Bod_ppal_id,
								farmacia_id,
								centro_utilidad,
								bodega,
								codigo_producto,
								cantidad_Solic,
								Tipo_producto,
								usuario_id,
								fecha_registro
							
							)
							VALUES
							(
								NEXTVAL('solicitud_productos_a_bodega__solicitud_prod_a_bod_ppal_det_seq'),
								".$Solicitud_Prod_A_Bod_ppal_id.",
								'".$farmacia."',
								'".$centroU."',
								 '".$Bodega."',
								'".$fila['codigo_producto']."',
								".$fila['cantidad_solic'].",
								'".$fila['tipo_producto']."',
								".UserGetUID().",
								 now()
							);
						";
			}	
							
			if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
			return true;
			
		}
	/*
		* Funcion donde se elimina todos los datos de la tabla Solicitud_pro_a_bod_prpal_tmp
		* @return array $datos vector que contiene la informacion de la consulta.
	*/	
		function EliminarSolicitud_pro_a_bod_prpal_tmp($tipo_producto,$farmacia_id,$centro_utilidad,$bodega)
		{
			//$this->debug=true;
			$sql.= " Delete from  Solicitud_pro_a_bod_prpal_tmp where tipo_producto='".$tipo_producto."'
			          and  farmacia_id='".$farmacia_id."'
					  and   centro_utilidad='".$centro_utilidad."' 
                      and   bodega='".$bodega."' 
					  and  usuario_id=".UserGetUID()." ; ";
				    
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	/*
		* Funcion donde se obtienen todos los datos generales del documento de pedido generado
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		
		function consulta_solicitud_productos_a_bodega_principal($empresa,$bod,$centro,$filtros,$offset)
		{
			//$this->debug=true;
			$sql  = "Select	 s.solicitud_prod_a_bod_ppal_id, ";
			$sql .= "		 s.farmacia_id,  ";
			$sql .= " 		 s.centro_utilidad, ";
			$sql .= "		 s.bodega, ";
			$sql .= " 		 s.observacion, ";
			$sql .= " 		 To_char(s.fecha_registro,'DD-MM-YYYY') AS fecha_registro, ";
			$sql .= " 		 u.usuario_id, ";
			$sql .= " 		 u.nombre ";
			$sql .= " From   solicitud_productos_a_bodega_principal s,system_usuarios  u";
			$sql .="  WHERE  s.usuario_id=u.usuario_id and farmacia_id = '".$empresa."' and centro_utilidad= '".$centro."' "; 
			$sql .= "        and bodega ='".$bod."'  and sw_despacho=0 ";
	       
			$FechaI=$filtros['fecha_inicio'];
			$FechaF=$filtros['fecha_final'];

			$fdatos=explode("-", $FechaI);
			$fedatos= $fdatos[2]."-".$fdatos[1]."-".$fdatos[0];
		
			$fdtos=explode("-", $FechaF);
			$fecdtos= $fdtos[2]."-".$fdtos[1]."-".$fdtos[0];
							
			if($fedatos && $filtros['fecha_final'] )
			{
			$sql.=" AND fecha_registro >= '".$fedatos." 00:00:00'  AND   fecha_registro <= '".$fecdtos." 24:00:00'";
			}
			
			if($filtros['pedido'] != "")
			$sql .= "AND    solicitud_prod_a_bod_ppal_id= ".$filtros['pedido']." ";
			
			
			$cont= "   select COUNT(*) from (".$sql.") AS A";
			
			$sql .= "  ORDER by   solicitud_prod_a_bod_ppal_id DESC ";
			$this->ProcesarSqlConteo($cont,$offset);
			
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			$datos = array();
			while (!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	/*
		* Funcion donde se obtienen el detalle de todos los items  del documento de pedido generado
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		/*function ObtenerDetalleDeSolicitu($solicitud_prod_a_bod_ppal_id,$farmacia_id,$bodega,$centro_utilidad,$TipoProducto,$offset)
		{
			//$this->debug = true;
			if($TipoProducto !="")
			{
				$aumento = "AND       p.tipo_producto_id = '".$TipoProducto."' ";
			}
		else
			{
          $aumento = "";
			}
      $this->debug=true;
			$sql  = "SELECT   	e.solicitud_prod_a_bod_ppal_det_id,";
			$sql .= "        	e.solicitud_prod_a_bod_ppal_id,  ";
			$sql .= "			e.cantidad_solic, ";
			$sql .= "			e.farmacia_id,  ";
			$sql .= "			e.centro_utilidad, ";
			$sql .= "			e.bodega, ";
			$sql .= "			p.codigo_producto,";
			$sql .= "     fc_descripcion_producto(p.codigo_producto) as producto,";
			$sql .= "			";
			$sql .= "			x.local_prod as localiza, ";
			$sql .= "     t.tipo_producto_id,";
			$sql .= "			t.descripcion as tipo ";
			$sql .= "FROM  		solicitud_productos_a_bodega_principal_detalle e,  ";
			$sql .= "          	inventarios_productos p, ";
			$sql .= " 			inventarios i,  ";
			$sql .= " 			existencias_bodegas x, ";
			$sql .= " 			inv_tipo_producto t  ";
			$sql .= " WHERE  	";
			$sql .= "           e.codigo_producto=p.codigo_producto";
			$sql .= " AND       e.bodega=x.bodega ";
			$sql .= " AND       p.codigo_producto=i.codigo_producto ";
			$sql .= " AND       i.codigo_producto=x.codigo_producto";
			$sql .= " AND       i.empresa_id =  x.empresa_id";
			$sql .= " ".$aumento;
			$sql .= " AND       p.tipo_producto_id=t.tipo_producto_id ";
			$sql .= " AND       e.solicitud_prod_a_bod_ppal_id='".$solicitud_prod_a_bod_ppal_id."'";
			$sql .= " AND       e.farmacia_id='".$farmacia_id."' ";
			$sql .= " AND       e.bodega='".$bodega."' ";
			$sql .= " AND       e.centro_utilidad='".$centro_utilidad."' ";
			$cont= "   select COUNT(*) from (".$sql.") AS A";
			$sql .= " ORDER BY ";
			$sql .= " 			x.local_prod,  t.descripcion,  ";
			$sql .= " 			p.descripcion ASC ";
*/
			/*if($offset!="-1")
      {
			$this->ProcesarSqlConteo($cont,$offset);
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			}
      */
	/*		if(!$rst = $this->ConexionBaseDatos($sql))
			
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}*/
    
    function ObtenerDetalleDeSolicitu($solicitud_prod_a_bod_ppal_id,$farmacia_id,$bodega,$centro_utilidad,$TipoProducto,$offset)
		{
			//$this->debug = true;
			if($TipoProducto !="")
			{
				$aumento = "AND       p.tipo_producto_id = '".$TipoProducto."' ";
			}
		else
			{
          $aumento = "";
			}
      /*$this->debug=true;*/
		$sql  = "SELECT  e.solicitud_prod_a_bod_ppal_det_id,";
		$sql .= "      		e.solicitud_prod_a_bod_ppal_id,  ";
		$sql .= "			e.cantidad_solic, ";
		$sql .= "			e.farmacia_id,  ";
		$sql .= "			e.centro_utilidad, ";
		$sql .= "			e.bodega, ";
		$sql .= "			p.codigo_producto,";
		$sql .= "     		fc_descripcion_producto(p.codigo_producto) as producto,";
		$sql .= "    		t.tipo_producto_id,";
		$sql .= "			t.descripcion as tipo, ";
		$sql .= "			p.sw_requiereautorizacion_despachospedidos, ";
		$sql .= "			e.observacion ";
		$sql .= "FROM  		solicitud_productos_a_bodega_principal_detalle as e  ";
		$sql .= "         JOIN inventarios_productos as p ON (e.codigo_producto = p.codigo_producto) ";
		$sql .= " 		LEFT JOIN inv_fabricantes as f ON (p.fabricante_id = f.fabricante_id)	";
		$sql .= " 	    JOIN inv_tipo_producto t ON (p.tipo_producto_id = t.tipo_producto_id)  ";
		$sql .= " WHERE  TRUE	";
		$sql .= "         AND (e.solicitud_prod_a_bod_ppal_id='".trim($solicitud_prod_a_bod_ppal_id)."')   ";
		$sql .= " ".$aumento;
		/*$cont= "   select COUNT(*) from (".$sql.") AS A";*/
		$sql .= " ORDER BY ";
		$sql .= " 			p.sw_requiereautorizacion_despachospedidos ASC,  ";
		$sql .= " 			f.descripcion ASC,  ";
		$sql .= " 			p.descripcion ASC ";

			/*if($offset!="-1")
      {
			$this->ProcesarSqlConteo($cont,$offset);
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			}
      */
			if(!$rst = $this->ConexionBaseDatos($sql))
			
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    
    
	/*
		* Funcion donde se obtienen los datos temporales
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	
		function ConsultarSolicitud_bod_prpal_tmp($farmacia,$centro,$bod)
		{
			//  $this->debug = true;
			
			$sql  = "SELECT  soli_a_bod_prpal_tmp_id, ";
			$sql .="		 farmacia_id, ";
			$sql .="         centro_utilidad, ";
			$sql .="		 bodega, ";
			$sql .="		 codigo_producto, ";
			$sql .="         cantidad_solic, ";
			$sql .="		 tipo_producto,usuario_id ";
			$sql .=" FROM    solicitud_pro_a_bod_prpal_tmp  ";
			$sql .=" WHERE	 usuario_id=".UserGetUID()." and farmacia_id='".$farmacia."'  and centro_utilidad='".$centro."' and bodega='".$bod."' ";
	
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	 /**
    * Funcion que permite obtener el nombre de un usuario
    * @param string $usuario_id
    * @return array $datos con el nombre del usuario 
    **/
    function GetNombreUsuario($usuario_id)
    {
        $sql="  SELECT nombre
                FROM system_usuarios
                WHERE usuario_id='".trim($usuario_id)."'";
                
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

        $datos=Array();
        while(!$resultado->EOF)
        {
        $datos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
        }

        $resultado->Close();
        return $datos;
    }
	/*
		* Funcion donde se obtiene la informacion de la farmacia
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	
	    function ObtenerInformacion($Farmacia)
		{
			// $this->debug = true;
           $sql ="  SELECT e.empresa_id,
					e.tipo_id_tercero,
					e.id,
					e.razon_social,
					e.representante_legal,
					e.codigo_sgsss,
					e.tipo_pais_id,
					e.tipo_dpto_id,
					e.tipo_mpio_id,
					e.direccion,
					e.telefonos,
					e.fax,
					e.codigo_postal,
					e.website,
					e.email,
					e.sw_activa,
					t.municipio,
					d.departamento,
					p.pais
			FROM    empresas e,
			        tipo_mpios t,
					tipo_dptos d,
					tipo_pais p
			WHERE   e.tipo_pais_id=t.tipo_pais_id
			and     e.tipo_dpto_id=t.tipo_dpto_id
            and		e.tipo_mpio_id =t.tipo_mpio_id
			and 	t.tipo_pais_id=d.tipo_pais_id
            and		t.tipo_dpto_id=d.tipo_dpto_id
            and     d.tipo_pais_id=p.tipo_pais_id 
            and     e.empresa_id='".$Farmacia."'
            and     e.sw_activa='1';	";			
			

			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	
	/*
		* Funcion donde se elimina todos los datos de la tabla Solicitud_pro_a_bod_prpal_tmp
		* @return array $datos vector que contiene la informacion de la consulta.
	*/	
		function EliminarSol_pro_a_bod_prpal_tmp($farmacia_id,$centro_utilidad,$bodega)
		{
			//$this->debug=true;
			$sql.= " Delete from  Solicitud_pro_a_bod_prpal_tmp 
                      where farmacia_id='".$farmacia_id."'
					  and   centro_utilidad='".$centro_utilidad."' 
                      and   bodega='".$bodega."' 
					  and  usuario_id=".UserGetUID()." ; ";
				    
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		
		function GetNombreUsuarioImprime()
		{
			$sql="  SELECT nombre,descripcion
					FROM system_usuarios
					WHERE usuario_id=".UserGetUID()." ";
					
			if(!$resultado = $this->ConexionBaseDatos($sql))
			return false;

			$datos=Array();
			while(!$resultado->EOF)
			{
			$datos[] = $resultado->GetRowAssoc($ToUpper = false);
			$resultado->MoveNext();
			}

			$resultado->Close();
			return $datos;
		}
	/* */
	/*
		* Funcion donde se obtienen el detalle de los tipos de productos existentes en el pedido
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ObtenerDetalleDeSolicitu2($solicitud_prod_a_bod_ppal_id)
		{
			//$this->debug = true;
			
			$sql ="	SELECT t.tipo_producto,
					   p.descripcion  as produc
             FROM   
             solicitud_productos_a_bodega_principal_detalle t 
             join inv_tipo_producto  
             p on(t.tipo_producto=p.tipo_producto_id)
				WHERE  t.solicitud_prod_a_bod_ppal_id = '".$solicitud_prod_a_bod_ppal_id."' ";
							  
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		
				function ObtenerCabecera($solicitud_prod_a_bod_ppal_id)
		{
			/*$this->debug = true;*/
			
			$sql ="	SELECT 
			 t.*,
			 b.descripcion as nombre_bodega
             FROM   
             solicitud_productos_a_bodega_principal t
			 JOIN bodegas as b ON (t.farmacia_id = b.empresa_id)
			 AND (t.centro_utilidad = b.centro_utilidad)
			 AND (t.bodega = b.bodega)
             WHERE  
			 t.solicitud_prod_a_bod_ppal_id = '".$solicitud_prod_a_bod_ppal_id."' ";
							  
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    /* */
	/*
		* Funcion donde se obtienen usuario quien realizo el pedido
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		
		function ConsultarUsuarioRealizaPedido($solicitud_prod_a_bod_ppal_id)
		{
		  $sql = " SELECT t.usuario_id, To_char(T.fecha_registro,'MM-DD-YYYY') AS fecha_registro,u.nombre,u.descripcion
 				  FROM     solicitud_productos_a_bodega_principal t, system_usuarios u
 				  WHERE    t.solicitud_prod_a_bod_ppal_id = '".$solicitud_prod_a_bod_ppal_id."'
                  and      u.usuario_id=t.usuario_id			  ";
		if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	/*
		* Funcion donde se actualizan las cantidades por producto
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	
		function ActualizarCantidades($solic,$producto,$canti)
		{
		  $sql = " update solicitud_productos_a_bodega_principal_detalle 
		           set cantidad_solic=".$canti."
				   where solicitud_prod_a_bod_ppal_id='".$solic."' and 	codigo_producto='".$producto."' ";
				   
					if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
					$datos = array();
					while(!$rst->EOF)
					{
					$datos[] = $rst->GetRowAssoc($ToUpper);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
        }
		/*
		*  Funcion donde se obtienen  todos los datos de las empresas que no son farmacias
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		
		function ConsultarEmpresas()
		{
			//$this->debug = true;
			
			$sql = " SELECT empresa_id,
			                razon_social
					 FROM   empresas
					 WHERE sw_tipo_empresa='0' ";
			
					
			
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	/*
	*/
	   function consultarDatosSeleccionados($farmacia,$centro,$bodega,$tipo)
	   {
			$sql = " SELECT s.soli_a_bod_prpal_tmp_id,
							s.codigo_producto,
							s.cantidad_solic as cantidad,
							fc_descripcion_producto(s.codigo_producto)as producto,
							s.observacion
					 FROM   solicitud_pro_a_bod_prpal_tmp s
					 WHERE  s.farmacia_id = '".$farmacia."' 
					 AND    s.centro_utilidad = '".$centro."' 
					 AND   s.bodega = '".$bodega."'
					 AND   s.usuario_id = '".UserGetUID()."'
					 AND   s.tipo_producto= '".$tipo."' ";
					 
					if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
					$datos = array();
					while(!$rst->EOF)
					{
					$datos[] = $rst->GetRowAssoc($ToUpper);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos; 
	    }
		
		/*
		* Funcion donde se Consultan las empresas que no son farmacias
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	   	function ListaEmpresas()
		{
			$sql  = "SELECT  empresa_id,razon_social
					FROM    empresas
					WHERE   sw_tipo_empresa= '0' 
					and     sw_activa='1' ";
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			$datos = array();
			while (!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/*
		* Funcion donde se Consultan los centros de utilidades de la empresa
		* @return array $datos vector que contiene la informacion de la consulta.
	*/ 
		function ListarCentroUtilidad($empresa)
		{
		
			$sql  = "SELECT centro_utilidad,descripcion 
			         FROM   centros_utilidad
 					 WHERE  empresa_id = '".$empresa."' ";
					 
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			$datos = array();
			while (!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		
		}
			/*
		* Funcion donde se Consultan las bodegas de la empresa seleccionada
		* @return array $datos vector que contiene la informacion de la consulta.
	*/ 
		
		function ListarBodegaEmp($empresa,$centro)
		{
			
			$sql  = "SELECT  bodega,descripcion
					FROM    bodegas 
					WHERE   empresa_id = '".$empresa."'
					AND     centro_utilidad = '".$centro."' ";
					 
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			$datos = array();
			while (!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		
		}
	/*
		*  Funcion donde se inserta la empresa destino para el documento de pedido
		*  @return  boolean de acuerdo a la ejecucion del sql.
	*/		
		function Ingresar_Empresas_destino($far,$centro,$bodega,$empresa_d,$centro_d,$bodega_d)
		{
		   
		   	$this->ConexionTransaccion();
			
			$sql = "INSERT INTO solicitud_Bodega_principal_aux
						(
							farmacia_id,
							centro_utilidad,
							bodega,
							empresa_destino,
							centro_destino,
							bogega_destino,
						    usuario_id
						)
						VALUES
						(
							'".trim($far)."',
							'".trim($centro)."',
							'".trim($bodega)."',
							'".trim($empresa_d)."',
							'".trim($centro_d)."',
							'".trim($bodega_d)."',
							".UserGetUID()."
						);
							";
				
			  if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
				return true;
		}
		
		function Consultar_Empresa_aux($farmacia,$centro,$bodega)
		{
		 
			$sql = " SELECT A.empresa_destino,
							A.centro_destino,
							A.bogega_destino,
							E.razon_social,
							B.descripcion as bodega,
							C.descripcion as centro
						
					FROM    solicitud_bodega_principal_aux A,
					        bodegas B,
							Centros_utilidad C,
							Empresas    E
					WHERE   A.empresa_destino=B.empresa_id
					AND     A.centro_destino=B.centro_utilidad
					AND     A.bogega_destino=B.bodega
                    AND     B.empresa_id=C.empresa_id
					AND     B.centro_utilidad=C.centro_utilidad
					AND     C.empresa_id=E.empresa_id
					AND     A.farmacia_id = '".$farmacia."' 
					AND     A.centro_utilidad = '".$centro."'
					AND     A.bodega = '".$bodega."' 
					AND     A.usuario_id = ".UserGetUID()." ";
					if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					$datos = array();
					while (!$rst->EOF)
					{
					$datos[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
		}
		/**/
		function  ConsultarExistencias_Actuales($empresa,$centro,$bodega,$codigo)
		{
		 
			$sql = " SELECT   existencia
                     FROM     existencias_bodegas
					 WHERE    empresa_id = '".trim($empresa)."' 
					 AND      centro_utilidad = '".trim($centro)."' 
					 AND      codigo_producto = '".trim($codigo)."' 
					 AND       bodega = '".trim($bodega)."' ";
					 /*print_r($sql);*/
					if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					$datos = array();
					while (!$rst->EOF)
					{
					$datos[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
		}
	/**/
		function ConsultarId_solicitudPendientes($empresa_destino)
		{
			$sql = " SELECT  solicitud_prod_a_bod_ppal_id
			         FROM    solicitud_productos_a_bodega_principal
					 WHERE   empresa_destino = '".$empresa_destino."' 
					 AND     sw_despacho = '0' ";
					 if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					$datos = array();
					while (!$rst->EOF)
					{
					$datos[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
		
		}
		/**/
		function ConsultarId_solicitudPendientes_1($empresa_destino)
		{
			$sql = " SELECT  solicitud_prod_a_bod_ppal_id
			         FROM    solicitud_productos_a_bodega_principal
					 WHERE   empresa_destino = '".$empresa_destino."' 
					 AND     sw_despacho = '1' ";
					 if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					$datos = array();
					while (!$rst->EOF)
					{
					$datos[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
		
		}
			
	/**/
    function Consultarid_solicitud($solicitud,$producto)
		{
			$sql = " SELECT  cantidad_solic
					  FROM 	 solicitud_productos_a_bodega_principal_detalle
					 WHERE   solicitud_prod_a_bod_ppal_id=".$solicitud."
					 and     codigo_producto = '".$producto."' ; ";
					
					if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					$datos = array();
					while (!$rst->EOF)
					{
					$datos[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;

		}
    /**
    *
    */
    function ObtenerSolicitudes($empresa_destino)
		{
			$sql = " SELECT SUM(cantidad_solic) AS cantidad,
                      SD.codigo_producto
			         FROM   solicitud_productos_a_bodega_principal SP,
                      solicitud_productos_a_bodega_principal_detalle SD
               WHERE  SP.empresa_destino = '".$empresa_destino."' 
               AND    SP.solicitud_prod_a_bod_ppal_id = SD.solicitud_prod_a_bod_ppal_id
               AND    SP.sw_despacho = '0' 
               GROUP BY SD.codigo_producto";
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
      $datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
      }
			$rst->Close();
			return $datos;
		}    
    /**
    *
    */
    function ObtenerSolicitudesDespachadas($empresa_destino)
		{
			$sql = " SELECT SUM(SM.cantidad_pendiente) AS cantidad_pendiente,
                      SD.codigo_producto
			         FROM   solicitud_productos_a_bodega_principal SP,
                      solicitud_productos_a_bodega_principal_detalle SD,
                      inv_mov_pendientes_solicitudes_frm SM
               WHERE  SP.empresa_destino = '".trim($empresa_destino)."' 
               AND    SP.solicitud_prod_a_bod_ppal_id = SD.solicitud_prod_a_bod_ppal_id
               AND    SM.solicitud_prod_a_bod_ppal_det_id = SD.solicitud_prod_a_bod_ppal_det_id
               AND    SP.sw_despacho = '1' 
               GROUP BY SD.codigo_producto";
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			
      $datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
      }
			$rst->Close();
			return $datos;
		}
	/**/
	 function Consultarid_solicitud_det_id($solicitud,$producto)
		{
			$sql = " SELECT  solicitud_prod_a_bod_ppal_det_id
					  FROM 	 solicitud_productos_a_bodega_principal_detalle
					 WHERE   solicitud_prod_a_bod_ppal_id=".trim($solicitud)."
					 and     codigo_producto = '".trim($producto)."' ; ";
					
					if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					$datos = array();
					while (!$rst->EOF)
					{
					$datos[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;

		}
	/**/
	function cantidad_pendiente_inv_mto($solicitud_det_id)
	{
		$sql = " SELECT cantidad_pendiente
         	     FROM   inv_mov_pendientes_solicitudes_frm
				 WHERE  solicitud_prod_a_bod_ppal_det_id = '".trim($solicitud_det_id)."'; ";
				   if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					$datos = array();
					while (!$rst->EOF)
					{
					$datos[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
	
	}
	/**/
	  function BuscarTotalPedidosEmpresa($EmpresaId,$CodigoProducto)
    {
	  $sql ="
				SELECT
				SUM((b.numero_unidades - b.cantidad_despachada)) as total,
				b.codigo_producto
				FROM
				ventas_ordenes_pedidos AS a
				JOIN ventas_ordenes_pedidos_d AS b ON (a.pedido_cliente_id = b.pedido_cliente_id)
				AND (a.estado = '1')
				AND (a.empresa_id = '".trim($EmpresaId)."')
				AND (b.numero_unidades <> b.cantidad_despachada)
				GROUP BY b.codigo_producto
				UNION
				SELECT SUM(x.cantidad_solic) as total,
				x.codigo_producto
				FROM
				solicitud_pro_a_bod_prpal_tmp as x
				JOIN solicitud_bodega_principal_aux as y ON(x.farmacia_id = y.farmacia_id)
				AND (x.centro_utilidad = y.centro_utilidad)
				AND (x.bodega = y.bodega)
				AND (x.usuario_id = y.usuario_id)
				WHERE y.empresa_destino = '".trim($EmpresaId)."'
				group BY x.codigo_producto; ";
	/*print_r($sql);*/
      /*$sql  = "SELECT SUM(vopd.numero_unidades) as total,
                      vopd.codigo_producto
               FROM   ventas_ordenes_pedidos vop,
                      ventas_ordenes_pedidos_d vopd
               WHERE  vop.empresa_id= '".$EmpresaId."'
               AND    vop.fecha_envio IS NULL
               AND    vop.pedido_cliente_id = vopd.pedido_cliente_id
               --AND    vopd.codigo_producto='".$CodigoProducto."'
               GROUP BY vopd.codigo_producto
						  ";  */
    
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }  
		/*
		* Funcion donde se elimina todos los datos de la tabla auxiliar  para los pedidos
		* @return array $datos vector que contiene la informacion de la consulta.
	*/	
		function EliminarAux_solicitudes($farmacia_id,$centro_utilidad,$bodega)
		{
			//$this->debug=true;
			$sql.= " Delete from  solicitud_bodega_principal_aux 
                      where farmacia_id='".$farmacia_id."'
					  and   centro_utilidad='".$centro_utilidad."' 
                      and   bodega='".$bodega."' 
					  and   usuario_id=".UserGetUID()." ; ";
				    
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		
    		function  BuscarUsuario_Bloqueo($farmacia_id,$centro_utilidad,$bodega,$codigo_producto)
		{
		 
			$sql = " SELECT   b.nombre,
                        b.usuario_id
                     FROM     solicitud_pro_a_bod_prpal_tmp a,
                              system_usuarios b
					 WHERE    
                    a.farmacia_id = '".trim($farmacia_id)."'
                    AND a.centro_utilidad = '".trim($centro_utilidad)."'
                    AND a.codigo_producto = '".trim($codigo_producto)."'
					AND a.usuario_id = b.usuario_id ";
					if(!$rst = $this->ConexionBaseDatos($sql))	return false;
					$datos = array();
					while (!$rst->EOF)
					{
					$datos = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
					}
					$rst->Close();
					return $datos;
		}
		
	}
 ?>