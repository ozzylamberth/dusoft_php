<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: InfoProductosDisponiblesPplSQL.class.php,v 1.0
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/

	class InfoProductosDisponiblesPplSQL extends ConexionBD
	{
	/*
	* Constructor de la clase
	*/
	function InfoProductosDisponiblesPplSQL(){}

	/**
	* Funcion donde se Consultan el permiso
	* @return array $datos vector que contiene la informacion de la consulta 
	* de Identificacion
	*/
		function ObtenerPermisos()
		{
		  
			$sql  = "SELECT   	a.empresa_id, ";
			$sql .= "           b.razon_social AS descripcion1, ";
			$sql .= "           a.usuario_id ";
			$sql .= "FROM 	    userpermisos_InfoProductosDisponiblePpl  a, ";
			$sql .= "           empresas AS b ";
			$sql .= "WHERE      a.usuario_id= ".UserGetUID()."  ";
			$sql .= "           AND 	a.empresa_id=b.empresa_id ";
			$sql .= "           AND  b.sw_tipo_empresa='1' ";

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
      * Funcion donde se consulta los productos para una determinada empresa.
      * @return array $datos vector con la informacion. 
      */
      function ObtenerProductos($empresa,$centro,$bodega,$filtros, $offset)
      {
	  
        $sql = " SELECT  x.empresa_id,
		                 x.centro_utilidad,
						 x.bodega,
						 x.codigo_producto,
						 x.existencia,
						 p.descripcion,
						 fc_descripcion_producto(x.codigo_producto) as nombre_producto,
						 p.contenido_unidad_venta,u.descripcion as unidad,  c.descripcion as laboratorio
				FROM    existencias_bodegas x,
						inventarios i, 
						inventarios_productos p,
						unidades u,
						inv_subclases_inventarios s,
						inv_clases_inventarios c
				WHERE   x.empresa_id=i.empresa_id
				AND     x.codigo_producto=i.codigo_producto
				AND     i.codigo_producto=p.codigo_producto
				AND     p.unidad_id=u.unidad_id
				AND     p.grupo_id=s.grupo_id
				AND     p.clase_id=s.clase_id
				AND     p.subclase_id=s.subclase_id
				AND     s.grupo_id=c.grupo_id 
				and     s.clase_id=c.clase_id
				and     x.empresa_id = '".$empresa."' 
				AND     x.centro_utilidad = '".$centro."'
				AND     x.bodega = '".$bodega."'
				 ";
        
    
				
      if($filtros['descripcion']!="")
      {
      
       $sql.=" and p.descripcion LIKE '%".$filtros['descripcion']."%'  ";
      }
	 
			  if($filtros['codigo_producto']!="")
			  {
			  $sql.=" and x.codigo_producto LIKE '%".$filtros['codigo_producto']."%'  ";
			  }

			  $cont="select COUNT(*) from (".$sql.") AS A";
			  $this->ProcesarSqlConteo($cont,$offset);
			  $sql .= "ORDER BY p.descripcion  ";

			  $sql .= "LIMIT ".$this->limit." OFFSET  ".$this->offset;
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
	* Funcion donde se Consultan las cantidades pendientes por despachar de un producto
	* @return array $datos vector que contiene la informacion 
	* de Identificacion
	*/
		
		function ConsultarInformacionPendientes($empresa,$codigopro)
		{
		   
		   $sql = " SELECT  SUM(frm.cantidad_pendiente) AS pendiente
					FROM    inv_mov_pendientes_solicitudes_frm frm,solicitud_productos_a_bodega_principal_detalle del
					WHERE   frm.solicitud_prod_a_bod_ppal_id=del.solicitud_prod_a_bod_ppal_id 
					AND     frm.solicitud_prod_a_bod_ppal_det_id=del.solicitud_prod_a_bod_ppal_det_id
					
					AND     frm.empresa_id = '".$empresa."'
					AND     del.codigo_producto='".$codigopro."'
					AND     frm.cantidad_pendiente >0 ";
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
	* Funcion donde se Consultan las cantidades disponibles 
	* @return array $datos vector que contiene la informacion de la consulta 
	* de Identificacion
	*/
		function ConsultarDisponibles($empresa,$centro,$bodega,$codprod)
		{

		  $sql = " SELECT fv.codigo_producto,fv.fecha_vencimiento,fv.lote,fv.existencia_actual,p.descripcion,p.contenido_unidad_venta,u.descripcion as unidad
					FROM   existencias_bodegas_lote_fv fv,existencias_bodegas x,inventarios i, inventarios_productos p,unidades u
					WHERE   fv.empresa_id=x.empresa_id
					AND     fv.centro_utilidad=x.centro_utilidad
					AND     fv.bodega=x.bodega
					AND     fv.codigo_producto=x.codigo_producto
					AND     x.empresa_id=i.empresa_id
					AND     x.codigo_producto=i.codigo_producto
					AND     i.codigo_producto=p.codigo_producto
					AND     p.unidad_id=u.unidad_id
					and     fv.empresa_id = '".$empresa."' 
					AND    fv.centro_utilidad = '".$centro."'
					AND    fv.bodega = '".$bodega."'
					AND   fv.existencia_actual>0
					AND   fv.codigo_producto='".$codprod."'  ";
		 
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
	* Funcion donde se consulta la informacion de la empresa seleccionada
	* @return array $datos vector que contiene la informacion de la consulta de los Tipos 
	* de Identificacion
	*/
		function ConsultarInformacionEmpresaSelec($empresa,$centro,$bodega)
		{
			$sql = " select e.empresa_id,e.razon_social,c.centro_utilidad ,c.descripcion as centro ,b.bodega,b.descripcion as bodega_des
					from bodegas b,empresas e,centros_utilidad c
					where b.empresa_id=c.empresa_id
					and   b.centro_utilidad=c.centro_utilidad
					and   c.empresa_id=e.empresa_id
					and   b.empresa_id='".$empresa."'
					and   b.centro_utilidad='".$centro."'
					and   b.bodega='".$bodega."' ";
		
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
		*/
		function ConsultarInformacionProducto($producto)
		{
		  $sql = " SELECT i.codigo_producto,i.descripcion, i.contenido_unidad_venta,u.descripcion as unidad,c.descripcion as laboratorio,i.tipo_producto_id
		           FROM   inventarios_productos i , unidades u, inv_subclases_inventarios s,inv_clases_inventarios c 
				   WHERE  i.codigo_producto = '".$producto."'
				   and    i.unidad_id=u.unidad_id
				   and    s.grupo_id=i.grupo_id
				   and    s.subclase_id=i.subclase_id
				   and    s.clase_id=i.clase_id
				   and    s.grupo_id=c.grupo_id
				   and    s.clase_id=c.clase_id ";
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
		*  Funcion donde se inserta la primera parte del documento de pedido  
		*  @return  boolean de acuerdo a la ejecucion del sql.
	*/		
		function IngresoSolicitud_Productos_A_Bodega_principal($far,$Centrid,$bod,$observacion,$tipoprod,$EmpresaDes)
		{
		   // $this->debug=true;
			
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
							sw_despacho
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
							0
											);
							";
				
			  if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
				return true;
		}
			function ConsultarDatosFarmacia($farmacia)
		{
		    //$this->debug=true;
			$sql = "  SELECT centro_utilidad,bodega
					  FROM   bodegas
					  WHERE  empresa_id = '".$farmacia."'  and estado='1' order by bodega ";
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
		function SelecMaxSolicitud_productos_a_bodega_principal()
		{
			//$this->debug=true;
			$sql = "SELECT (COALESCE(MAX(solicitud_prod_a_bod_ppal_id),0)) AS solicitud_prod_a_bod_ppal_id FROM  solicitud_productos_a_bodega_principal;	";
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
			
		function IngresoProductos_A_Bodega_principal_detalle($Solicitud_Prod_A_Bod_ppal_id,$farmacia,$centroU,$Bodega,$codigo_producto,$cantidad,$tipo_producto)
		{
			//$this->debug=true;
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
								'".$codigo_producto."',
								".$cantidad.",
								'".$tipo_producto."',
								".UserGetUID().",
								 now()
							);
						";
				
							
			if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
			return true;
			
		}
		
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
		
		 Function Consultarid_solicitud($solicitud,$producto)
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
		 Function Consultarid_solicitud_det_id($solicitud,$producto)
		{
			$sql = " SELECT  solicitud_prod_a_bod_ppal_det_id
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
		Function cantidad_pendiente_inv_mto($solicitud_det_id)
	{
		$sql = " SELECT cantidad_pendiente
         	     FROM   inv_mov_pendientes_solicitudes_frm
				 WHERE  solicitud_prod_a_bod_ppal_det_id = '".$solicitud_det_id."'; ";
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
	  function BuscarTotalPedidosEmpresa($EmpresaId,$CodigoProducto)
      {

	//$this->debug = true;
      $sql  = "SELECT SUM(vopd.numero_unidades) as total
                from
                ventas_ordenes_pedidos vop,
                ventas_ordenes_pedidos_d vopd
                where
                vop.empresa_id= '".$EmpresaId."'
                and
                vop.fecha_envio IS NULL
                and
                vop.pedido_cliente_id = vopd.pedido_cliente_id
                and
                vopd.codigo_producto='".$CodigoProducto."'
						  ";
    
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
	}
 ?>