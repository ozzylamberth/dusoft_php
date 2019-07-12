<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: Compras_Orden_ComprasSQL.class.php,v 1.2 2010/01/14 22:49:02 sandra Exp $Revision: 1.2 $
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/

	class Compras_Orden_ComprasSQL extends ConexionBD
	{
	/*
	* Constructor de la clase
	*/
	function Compras_Orden_ComprasSQL(){}

	/**
	* Funcion donde se Consultan los Tipos de identificacion 
	* @return array $datos vector que contiene la informacion de la consulta de los Tipos 
	* de Identificacion
	*/

	function ObtenerPermisos()
	{
			//$this->debug = true;
			$sql  = "SELECT   	a.empresa_id, ";
			$sql .= "           b.razon_social AS descripcion1, ";
			$sql .= "           a.centro_utilidad, ";
			$sql .= "           c.descripcion AS descripcion2, ";
			$sql .= "           a.usuario_id ";
			$sql .= "FROM 	    userpermisos_compras AS a, ";
			$sql .= "           empresas AS b, ";
			$sql .= "           centros_utilidad AS c ";
			$sql .= "WHERE      a.usuario_id= ".UserGetUID()."  ";
			$sql .= "           AND 	a.empresa_id=b.empresa_id ";
			$sql .= "           AND 	a.centro_utilidad=c.centro_utilidad ";
			$sql .= "           AND 	a.empresa_id=c.empresa_id AND  b.sw_tipo_empresa='0' ";

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
		
	function ListarCentrodeUtilidad($empresa)
	{
		//$this->debug = true;
			$sql  = "SELECT   	empresa_id, centro_utilidad,descripcion,Ubicacion";
			$sql .= "           From centros_utilidad  ";
			$sql .= "WHERE      empresa_id='".$empresa."'  ";
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
	function ObtenerBodegaFarmacia($empresa)
	{
			// $this->debug = true;
			$sql  = "SELECT   	";
			$sql .= "           e.bodega,  ";
			$sql .= "           e.descripcion AS descripcion3   ";
			$sql .= "FROM 	    empresas AS b, ";
			$sql .= "           centros_utilidad AS c, ";
			$sql .= "           bodegas AS e ";
			$sql .= "WHERE     	e.empresa_id=c.empresa_id  ";
			$sql .= "           AND 	e.centro_utilidad=c.centro_utilidad ";
			$sql .= "           AND 	c.empresa_id=b.empresa_id ";
			$sql .= "           AND 	b.empresa_id='".$empresa."' AND  b.sw_tipo_empresa='0' ";
			$sql .= "           ORDER BY e.descripcion; ";
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
	
	function   consultarInformacionPreOrden($filtros,$offset)
	{
		// //$this->debug=true;;
	    
		$sql = "SELECT 	p.preorden_id,
						p.farmacia_id,
						p.observacion,
						p.sw_preorden,
						To_char(p.fecha_registro,'dd-mm-yyyy') as fecha_registro,
			            p.usuario_id,
			            s.nombre,
						e.razon_social
				FROM    informacion_preorden  p,
				        system_usuarios  s,
						empresas e
		
				WHERE   p.farmacia_id=e.empresa_id
				and     p.usuario_id=s.usuario_id
				and     p.sw_preorden=1 ";
				
		
		if($filtros['fecha_inicio']!="")
		$sql.=" and p.fecha_registro ilike '%".$filtros['fecha_inicio']."%' ";
			
		if($filtros['orden']!="")
		$sql.=" and p.preorden_id= ".$filtros['orden']." ";
		
		if($filtros['farmacia'] != "")
			$sql .= " AND     e.razon_social  ILIKE '%".$filtros['farmacia']."%' ";
			
		$cont="select COUNT(*) from (".$sql.") AS A";
		$this->ProcesarSqlConteo($cont,$offset);
		$sql .= "ORDER BY e.razon_social ";
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
	
	Function ListarProveedoresGeneradosPO($preorden_id)
	{
	
		// //$this->debug=true;;
		$sql = "  SELECT  DISTINCT  t.codigo_proveedor_id,
									t.sw_unificada,
									ter.nombre_tercero
				FROM       			informacion_preorden_detalle t,
									terceros ter,
									terceros_proveedores p
				WHERE     			t.codigo_proveedor_id=p.codigo_proveedor_id
				AND        			p.tipo_id_tercero=ter.tipo_id_tercero
				AND			        p.tercero_id=ter.tercero_id  
				AND        			t.preorden_id=".$preorden_id." 
				AND  				t.sw_unificada=0
				AND  				t.codigo_proveedor_id in ( select  p.codigo_proveedor_id
				FROM       			terceros_proveedores p
				GROUP BY   			p.codigo_proveedor_id ,ter.nombre_tercero) ";
 
		if(!$rst = $this->ConexionBaseDatos($sql))
		return false;
		$datos = array();
		while (!$rst->EOF)
		{
		$datos[] = $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();
		}
		$rst->Close();
		return $datos;

	}
	
	
	function ConsultarDetallePreOrden($preorden_id,$filtros,$offset)
	{
	
		////$this->debug=true;;
	   $sql= " SELECT 	p.farmacia_id,
						p.observacion,
						p.sw_preorden,
			            t.preorden_detalle_id,
						t.preorden_id,
						t.codigo_proveedor_id,
						t.codigo_producto,
						t.cantidad,
						t.valor_total_pactado,
						t.fecha_registro,
						t.sw_unificada,
						ter.nombre_tercero,
						i.descripcion,
						m.molecula_id,
						m.descripcion as molecula,
						l.laboratorio_id,
						l.descripcion as laboratorio,
						ter.tipo_id_tercero,
						ter.tercero_id
		FROM    informacion_preorden_detalle t,
				informacion_preorden  p,
				inventarios_productos i,
				terceros_proveedores pro,
				terceros ter,
				inv_subclases_inventarios s,
				inv_moleculas m,
				inv_clases_inventarios c,
				inv_laboratorios l
		WHERE   p.preorden_id=t.preorden_id
		and     t.codigo_producto=i.codigo_producto
		and     t.codigo_proveedor_id=pro.codigo_proveedor_id
		and     pro.tipo_id_tercero=ter.tipo_id_tercero
		and     pro.tercero_id=ter.tercero_id
		and     i.grupo_id=s.grupo_id
		and 	i.clase_id=s.clase_id
		and     i.subclase_id=s.subclase_id
		and     s.molecula_id=m.molecula_id
		and     s.grupo_id=c.grupo_id
		and     s.clase_id=c.clase_id
		and     c.laboratorio_id=l.laboratorio_id
		and     t.sw_unificada=0
		and    p.sw_preorden=1
		and    t.preorden_id='".$preorden_id."'
        and    t.codigo_proveedor_id=".$filtros['proveedor_id']."		";
	      
		$cont="select COUNT(*) from (".$sql.") AS A";
		$this->ProcesarSqlConteo($cont,$offset);
		$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
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
	  function SeleccionarInformacionDetalle($preorden_id,$proveedor_id)
	  {
	  
	    ////$this->debug=true;;
			 $sql= " SELECT 	p.farmacia_id,
						p.observacion,
						p.sw_preorden,
			            t.preorden_detalle_id,
						t.preorden_id,
						t.codigo_proveedor_id,
						t.codigo_producto,
						t.cantidad,
						t.valor_total_pactado,
						t.fecha_registro,
						t.sw_unificada,
						ter.nombre_tercero,
						i.descripcion,
						m.molecula_id,
						m.descripcion as molecula,
						l.laboratorio_id,
						l.descripcion as laboratorio,
						ter.tipo_id_tercero,
						ter.tercero_id
		FROM    informacion_preorden_detalle t,
				informacion_preorden  p,
				inventarios_productos i,
				terceros_proveedores pro,
				terceros ter,
				inv_subclases_inventarios s,
				inv_moleculas m,
				inv_clases_inventarios c,
				inv_laboratorios l
		WHERE   p.preorden_id=t.preorden_id
		and     t.codigo_producto=i.codigo_producto
		and     t.codigo_proveedor_id=pro.codigo_proveedor_id
		and     pro.tipo_id_tercero=ter.tipo_id_tercero
		and     pro.tercero_id=ter.tercero_id
		and     i.grupo_id=s.grupo_id
		and 	i.clase_id=s.clase_id
		and     i.subclase_id=s.subclase_id
		and     s.molecula_id=m.molecula_id
		and     s.grupo_id=c.grupo_id
		and     s.clase_id=c.clase_id
		and     c.laboratorio_id=l.laboratorio_id
		and     t.sw_unificada=0
		and    p.sw_preorden=1
		and    t.preorden_id='".$preorden_id."'
        and    t.codigo_proveedor_id=".$proveedor_id."	;	";
	      
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
	  
		function  insertarOrden_Pedido($codigo_proveedor_id,$empresa_id,$empresac)
		{
		//$this->debug = true;
			 $indice = array();

			$sql = "SELECT NEXTVAL('compras_ordenes_pedidos_orden_pedido_id_seq') AS sq ";

			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
			if(!$rst->EOF)
			{
			$indice = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();     
			}
			$rst->Close(); 

			$sqlerror = "SELECT setval('compras_ordenes_pedidos_orden_pedido_id_seq', ".($indice['sq'])."); ";    
			
		    $this->ConexionTransaccion();
			
			$sql  = "INSERT INTO	 	compras_ordenes_pedidos( 
			                            orden_pedido_id,		
										codigo_proveedor_id,		
										empresa_id,		
										fecha_orden,		
										usuario_id,		
										estado,		
										fecha_envio,
										fecha_recibido,
										sw_unificada,
										empresa_id_pedido
									)
						VALUES( 
			                            ".$indice['sq'].",
										".$codigo_proveedor_id.",
										'".$empresac."',
										NOW(),
										".UserGetUID().",
										1,
										NULL,
										NULL,
                                        0,
                                       	'".$empresa_id."'									
								); ";
			
			if(!$rst = $this->ConexionTransaccion($sql))
			{
			return false;
			}

			$this->Commit();
			return true;
		}
		
		
		
		
		
		function SeleccionarMaxcompras_ordenes_pedidos($codigo_proveedor_id,$empresa_id)
		{
		    //$this->debug = true;
		    $sql = "  SELECT   MAX(orden_pedido_id) AS numero FROM compras_ordenes_pedidos
					  where    codigo_proveedor_id='".$codigo_proveedor_id."'
					  and      empresa_id_pedido ='".$empresa_id."'; ";

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
		
		function Ingresarcompras_ordenes_pedidos_detalle($datos,$orden_pedido_id)
		{
			////$this->debug=true;;
			foreach($datos as $item=>$fila)
			{
				$this->ConexionTransaccion();
						
				 $sql .= "INSERT INTO   compras_ordenes_pedidos_detalle
                        (
						           
					                orden_pedido_id,
					                codigo_producto,
					                numero_unidades,
					                valor,
					                porc_iva,
					                estado,
					                acta_autorizacion,
					                numero_unidades_recibidas,
									preorden_detalle_id
					    )
                VALUES
                (
                    ".$orden_pedido_id.",
				    '".$fila['codigo_producto']."',
                    ".$fila['cantidad'].",
					".$fila['valor_total_pactado'].",
					0,
                    1,
					null,
					null,
					".$fila['preorden_detalle_id']."
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
		function ActuEstado($preorden_id,$codigo_proveedor_id)
		{
		////$this->debug=true;;
		$sql = "	 UPDATE informacion_preorden_detalle
					 SET    sw_unificada=1              
				     WHERE  preorden_id =".$preorden_id."
				     AND    codigo_proveedor_id =".$codigo_proveedor_id." ;
		";
	
		if(!$resultado = $this->ConexionBaseDatos($sql))
		{
		$cad="Operacion Invalida";
		return false;
		} 
		return true;
		}
		
		function consultarSw_Unificados($preorden_id)
		{
		   ////$this->debug=true;;
		   $sql ="  SELECT 	preorden_detalle_id,
							preorden_id,
							sw_unificada
					FROM    informacion_preorden_detalle
					WHERE   sw_unificada='0'
					and     preorden_id=".$preorden_id." ";
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
		
		function  insertarCondicionesOrden_Pedido($empresa_id,$orden_pedido_id,$condicion)
		{
			//$this->debug = true;
			 $indice = array();

			$sql = "SELECT NEXTVAL('condiciones_orden_compra_condicionoc_id_seq') AS sq ";

			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
			if(!$rst->EOF)
			{
			$indice = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();     
			}
			$rst->Close(); 

			$sqlerror = "SELECT setval('condiciones_orden_compra_condicionoc_id_seq', ".($indice['sq'])."); ";    
			
		    $this->ConexionTransaccion();
			
			$sql  = "INSERT INTO	 	condiciones_orden_compra( 
										condicionoc_id,		
										empresa_id,		
										orden_pedido_id,
                                        condicion,										
										usuario_id,		
										fecha_registro )
						VALUES( 
			                            ".$indice['sq'].",
										'".$empresa_id."',
										".$orden_pedido_id.",
										'".$condicion."',
										".UserGetUID().",
										NOW()
								); ";
			
			if(!$rst = $this->ConexionTransaccion($sql))
			{
			return false;
			}

			$this->Commit();
			return true;
		}
		function ConsultarTipoId()
      {
        //   //$this->debug=true;;
        $sql  = "SELECT    tipo_id_tercero, descripcion ";
        $sql .= "FROM      tipo_id_terceros ";
        $sql .= "ORDER BY  tipo_id_tercero, descripcion ";
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
	  
	  function ConsultarOrdenComprasGeneradas($filtros,$offset)
	  {
		//	$this->debug = true;
			
			$sql= "  SELECT              	o.orden_pedido_id,
					                        o.codigo_proveedor_id,
											o.empresa_id,
											o.usuario_id,
											To_char(o.fecha_orden,'dd-mm-yyyy') as fecha_registro,
								            s.nombre,
											e.razon_social,
											ter.nombre_tercero,
											ter.tipo_id_tercero,
											ter.tercero_id,
											o.estado
					FROM                    compras_ordenes_pedidos  o,
									        system_usuarios  s,
											empresas e,
											terceros_proveedores p,
											terceros ter
						
					WHERE   o.empresa_id=e.empresa_id
					and     o.usuario_id=s.usuario_id
					and     o.codigo_proveedor_id=p.codigo_proveedor_id
			        and     p.tipo_id_tercero=ter.tipo_id_tercero
			        and     p.tercero_id=ter.tercero_id 
					and     o.estado=1 
					AND     o.sw_unificada='0'";

					if($filtros['tipo_id_tercero']!= "-1")
					$sql.=" and ter.tipo_id_tercero= '". $filtros['tipo_id_tercero']."' ";
					if($filtros['tercero_id'])
					{
					$sql.=" and ter.tercero_id= '".$filtros['tercero_id']."' ";
					}
					if($filtros['nombre_tercero'] != "")
					$sql .= "AND     ter.nombre_tercero ILIKE '%".$filtros['nombre_tercero']."%' ";
					
						
					
					$fdatos=explode("-", $filtros['fecha_inicio']);
					$fedatos= $fdatos[2]."-".$fdatos[1]."-".$fdatos[0];
					
					if($filtros['fecha_inicio']!="")
					$sql.=" and o.fecha_orden = '".$fedatos."' ";

					if($filtros['orden']!="")
					$sql.=" and o.orden_pedido_id= ".$filtros['orden']." ";

					if($filtros['farmacia'] != "")
					$sql .= " AND     e.razon_social  ILIKE '%".$filtros['farmacia']."%' ";

					$cont="select COUNT(*) from (".$sql.") AS A";
					$this->ProcesarSqlConteo($cont,$offset);
					$sql .= "ORDER BY o.orden_pedido_id  DESC ";
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
	  
	  function  ConsultarDetalleCompra($orden_pedido_id)
	  
	  {
	  //  $this->debug = true;
	    $sql  = " select  d.orden_pedido_id,
						d.codigo_producto,
						d.numero_unidades,
						d.valor,
						i.descripcion as producto,
						m.molecula_id,
						m.descripcion as molecula,
						l.laboratorio_id,
						l.descripcion as laboratorio
				from    compras_ordenes_pedidos_detalle d,
				        inventarios_productos i,
						inv_subclases_inventarios s,
						inv_moleculas m,
						inv_clases_inventarios c,
						inv_laboratorios l
				WHERE   d.codigo_producto=i.codigo_producto
				and     i.grupo_id=s.grupo_id
				and 	i.clase_id=s.clase_id
				and     i.subclase_id=s.subclase_id
				and     s.molecula_id=m.molecula_id
				and     s.grupo_id=c.grupo_id
				and     s.clase_id=c.clase_id
				and     c.laboratorio_id=l.laboratorio_id
		        and     d.orden_pedido_id='".$orden_pedido_id."'; ";
	  
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
	  
	     function  EmpresasOrden_Pedido()
		 {
		 
				$sql= " SELECT     DISTINCT   	c.empresa_id,
												c.sw_unificada,
												e.razon_social
				        FROM  					compras_ordenes_pedidos c,
												empresas e
				       WHERE         			c.empresa_id=e.empresa_id 
				       AND  	    			c.sw_unificada=0 ";
				

				if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while (!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;

		 }
		 
		 function ConsultarProveedoresOrden_Pedido($empresa,$offset)
		 {
		 		$sql = "  SELECT  DISTINCT  t.codigo_proveedor_id,
											t.sw_unificada,
											ter.nombre_tercero,
											ter.tipo_id_tercero,
											ter.tercero_id,
											t.empresa_id
					    FROM       			compras_ordenes_pedidos t,
											terceros ter,
											terceros_proveedores p
					WHERE     				t.codigo_proveedor_id=p.codigo_proveedor_id
					AND        				p.tipo_id_tercero=ter.tipo_id_tercero
					AND			      	  	p.tercero_id=ter.tercero_id  
					AND  					t.sw_unificada=0
					AND                     t.empresa_id='".$empresa."'
					AND                     t.estado='1'
					AND  				t.codigo_proveedor_id in ( select  p.codigo_proveedor_id
					FROM       			terceros_proveedores p
					GROUP BY   			p.codigo_proveedor_id ,ter.nombre_tercero) "; 
					
				if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
				return false;
      
              
               $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
					
				if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while (!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
			}
			
		
			
			function ListarDetalleOrdenPedidoXProveedor($empresa,$proveedor)
			{
			       $this->debug = true;
					$sql = "  select           o.orden_pedido_id,
												o.codigo_proveedor_id,
												o.empresa_id,
												o.fecha_orden,
												o.estado, 
												d.codigo_producto,
												d.numero_unidades,
												d.valor,
												i.descripcion as producto,
												m.molecula_id,
												m.descripcion as molecula,
												l.laboratorio_id,
												l.descripcion as laboratorio
					from                        compras_ordenes_pedidos o,
												compras_ordenes_pedidos_detalle d,
												inventarios_productos i,
												inv_subclases_inventarios s,
												inv_moleculas m,
												inv_clases_inventarios c,
												inv_laboratorios l
					WHERE                       o.orden_pedido_id=d.orden_pedido_id
										and     d.codigo_producto=i.codigo_producto
										and     i.grupo_id=s.grupo_id
										and 	i.clase_id=s.clase_id
										and     i.subclase_id=s.subclase_id
										and     s.molecula_id=m.molecula_id
										and     s.grupo_id=c.grupo_id
										and     s.clase_id=c.clase_id
										and     c.laboratorio_id=l.laboratorio_id
										and     o.estado = '1' 
										and     o.sw_unificada='0'
										and     d.numero_unidades > 0
										and     o.empresa_id='".$empresa."'
										and     o.codigo_proveedor_id='".$proveedor."' ";

							if(!$rst = $this->ConexionBaseDatos($sql))
							return false;
							$datos = array();
							while(!$rst->EOF)
							{
							$datos[$rst->fields[5]] = $rst->GetRowAssoc($ToUpper = false);
							$rst->MoveNext();
							}
							$rst->Close();
							return $datos;
			
			}
		    function UnificarDatos2($codigo_producto,$proveedor)
			{
			     $this->debug = true;
					$sql =" select DISTINCT  c.codigo_proveedor_id,
										   c.empresa_id,
										   c.sw_unificada ,
										   d.codigo_producto,
										  sum(d.numero_unidades)as numero,
										  sum( d.valor) as valor,
										  sum( d.porc_iva) as porc 	
									from  compras_ordenes_pedidos c,
										   compras_ordenes_pedidos_detalle d
									WHERE  c.orden_pedido_id=d.orden_pedido_id
									and   d.codigo_producto='".$codigo_producto."'
									and   c.codigo_proveedor_id='".$proveedor."'
									and   c.sw_unificada=0 group by 1,2,3,4  ";

						if(!$rst = $this->ConexionBaseDatos($sql))
							return false;
							$datos = array();
							while (!$rst->EOF)
							{
							$datos[] = $rst->GetRowAssoc($ToUpper = false);
							$rst->MoveNext();
							}
							$rst->Close();
							return $datos;
			}
			
			function  ingresarDocumentoDePedido($empresa_id,$codigo_proveedor_id,$observacion)
			{
			   $this->debug = true;
			 $indice = array();

			$sql = "SELECT NEXTVAL('productos_pendientes_ordenpedido_prod_pend_op_id_seq') AS sq ";

			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
			if(!$rst->EOF)
			{
			$indice = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();     
			}
			$rst->Close(); 

			$sqlerror = "SELECT setval('productos_pendientes_ordenpedido_prod_pend_op_id_seq', ".($indice['sq'])."); ";    
			
		    $this->ConexionTransaccion();
			
			$sql  = "INSERT INTO	 	Productos_pendientes_OrdenPedido( 
										Prod_Pend_OP_id,		
										empresa_id,		
										codigo_proveedor_id,
                                        observacion,										
										usuario_id,		
										fecha_registro,
                                        sw_asignado
										)
						VALUES( 
			                            ".$indice['sq'].",
										'".$empresa_id."',
										".$codigo_proveedor_id.",
										'".$observacion."',
										".UserGetUID().",
										NOW(),
										0
								); ";
			
		
			
			
			
			if(!$rst = $this->ConexionTransaccion($sql))
			{
			return false;
			}

			$this->Commit();
			return true;
 
		}
		
		
		function SeleccionarDocumentoDePedido($empresa,$proveedor)
		{
		 $this->debug = true;
			$sql = " SELECT COALESCE(MAX(prod_pend_op_id),0) as id FROM productos_pendientes_ordenpedido ";
								
								
            if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while (!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
		
		
		
		}
		
		
		function  InsertarDatosPendientes($datos,$id)
		{
		 $this->debug = true;
		   	foreach($datos as $item=>$fila)
			{
		
		    $this->ConexionTransaccion();
			$sql  .= "INSERT INTO	 	Productos_pendientes_OrdenPedido_d( 
										Prod_Pend_OP_id_d,		
										Prod_Pend_OP_id,
										empresa_id,		
										codigo_producto,
                                        numero_unidades,										
										valor,		
										porc_iva,
                                        fecha_registro
										)
						VALUES( 
			                             NEXTVAL('productos_pendientes_ordenpedido_d_prod_pend_op_id_d_seq'),
										".$id.",
										'".$fila['empresa_id']."',
										'".$fila['codigo_producto']."',
										'".$fila['numero']."',
										'".$fila['valor']."',
										'".$fila['porc']."',
										NOW()
								); ";
				
			
				
				
			}	
							
			if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
			return true;
			
		}
		
		function ConsultarDocumentoPedidoOP($prod_pend_op_id)
		{
		 $this->debug = true;
			$sql="SELECT    d.prod_pend_op_id_d,
							d.prod_pend_op_id,
							d.empresa_id,
							d.codigo_producto,
							d.numero_unidades,
							d.valor,
							d.porc_iva,
							d.fecha_registro
					FROM 	productos_pendientes_ordenpedido_d d
					        
					WHERE 	d.prod_pend_op_id=".$prod_pend_op_id."
					";
							
							if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while (!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
							
							
		}
		
		
		
		function ActualizarSw_unificadaOp($empresa_id,$codigo_proveedor_id,$orden_pedido_id,$prod_pend_op_id)
		{
		    ////$this->debug=true;;
				
			$sql = "	 UPDATE  compras_ordenes_pedidos
						SET    sw_unificada=1              
						WHERE  empresa_id ='".$empresa_id."'
						AND    codigo_proveedor_id =".$codigo_proveedor_id."
                        AND   	orden_pedido_id!=".$orden_pedido_id.";
		";
		
			$sql .= "	   UPDATE  productos_pendientes_ordenpedido
						SET    sw_asignado=1              
						WHERE  empresa_id ='".$empresa_id."'
						AND    codigo_proveedor_id =".$codigo_proveedor_id."
                        AND    	prod_pend_op_id=".$prod_pend_op_id.";
		";
		
	
		if(!$resultado = $this->ConexionBaseDatos($sql))
		{
		$cad="Operacion Invalida";
		return false;
		} 
		return true;
		}
		function Ingresarcompras_ordenes_pedidos_detalle_d($datos,$orden_pedido_id)
		{
			//$this->debug=true;;
			foreach($datos as $item=>$fila)
			{
				$this->ConexionTransaccion();
						
				 $sql .= "INSERT INTO   compras_ordenes_pedidos_detalle
                        (
					                orden_pedido_id,
					                codigo_producto,
					                numero_unidades,
					                valor,
					                porc_iva,
					                estado,
					                acta_autorizacion,
					                numero_unidades_recibidas,
									preorden_detalle_id
					    )
                VALUES
                (
                    ".$orden_pedido_id.",
				    '".$fila['codigo_producto']."',
                    ".$fila['numero_unidades'].",
					".$fila['valor'].",
					".$fila['porc_iva'].",
					 1,
					null,
					null,
					null
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
		
		
		function SeleccionarInformacionEmpresa($empresaid)
		{
		//$this->debug=true;;
			$sql = "SELECT 	empresa_id,
							tipo_id_tercero,
							id,
							razon_social,
							representante_legal,
							codigo_sgsss,
							direccion,
							telefonos,
							fax,
							codigo_postal,
							website,
							email
							
					FROM    empresas
					WHERE  empresa_id = '".$empresaid."' ";
		
				if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while (!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;

		
		}
		
		function ConsultarInformacionProveedor($codigo_proveedor_id)
		{
			//$this->debug=true;;
		
			$sql = " SELECT  p.codigo_proveedor_id,
							 p.tipo_id_tercero,
							 p.tercero_id,
							 p.estado,
							 t.direccion,
							 t.telefono,
							 t.fax,
							 t.email,
							 t.celular,
							 t.nombre_tercero,
							 t.dv,
							 p.porcentaje_rtf,
							 p.porcentaje_ica	
					FROM     terceros t, 
						     terceros_proveedores p
					where    p.tipo_id_tercero=t.tipo_id_tercero
					and      p.tercero_id=t.tercero_id
					and      p.codigo_proveedor_id=".$codigo_proveedor_id." ";
					
		if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while (!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
				
		}
		
		function ConsultarCondicionesCompra($orden_id)
		{

	     $sql = "  SELECT  condicionoc_id,
						empresa_id,
						orden_pedido_id,
						condicion
				FROM    condiciones_orden_compra
				WHERE   orden_pedido_id=".$orden_id." ";
	     
		   if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while (!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
		}
		
		function ConsultarDetalleDeOrdenCompra($orden_pedido_id)
		{
		
		//$this->debug=true;;
			$sql = "  SELECT 	d.item_id,
								d.orden_pedido_id,
								d.codigo_producto,
								d.numero_unidades,
								d.valor,
								i.porc_iva,
								d.estado,
								i.descripcion as producto,
								i.cantidad,
								d.valor_unitario,
								u.abreviatura
								
						FROM    compras_ordenes_pedidos_detalle d,
							   inventarios_productos i,
							   unidades u
						where   d.codigo_producto=i.codigo_producto
						and    d.orden_pedido_id=".$orden_pedido_id."
						and   d.estado=1  
						and    i.unidad_id=u.unidad_id ";
		          if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while (!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
		
		
		}
		
		 function  consultarInformacionUsuarioActual()
		 {
		 
			$sql ="  SELECT  usuario_id,
							 usuario,
							 nombre,
							 descripcion
					FROM     system_usuarios 
					WHERE    usuario_id =".UserGetUID()." ";
		 
		       if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				$datos = array();
				while (!$rst->EOF)
				{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				}
				$rst->Close();
				return $datos;
		 
		 
		 }
		
		
	}
 ?>