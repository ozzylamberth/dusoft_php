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
  /**
	* Funcion donde se lista los centros de utilidad de la empresa
	* @return array $datos vector que contiene la informacion de la consulta de los Tipos 
	* de Identificacion
	*/
		
	function ListarCentrodeUtilidad($empresa)
	{
	
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
	/*
		* Funcion donde se consulta la informacion delas pre ordenes generadas
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function consultarInformacionPreOrden($filtros,$offset,$orden_pedido_id)
		{
		
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
	           WHERE    p.farmacia_id=e.empresa_id
	                    and     p.usuario_id=s.usuario_id
	                    and     p.sw_preorden=1 ";
			if($filtros['orden']!="")
			$sql.=" and p.preorden_id= ".$filtros['orden']." ";
			
			if($filtros['farmacia'] != "")
				$sql .= " AND     e.razon_social  ILIKE '%".$filtros['farmacia']."%' ";
			
			if($orden_pedido_id!="")
			{
			$sql.=" and p.preorden_id= ".$orden_pedido_id." ";
			}
			
			$cont="select COUNT(*) from (".$sql.") AS A";
			
			$this->ProcesarSqlConteo($cont,$offset);
			
			$sql .= "ORDER BY  p.fecha_registro DESC ";
			$sql .="  ";
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
		* Funcion donde se consulta la informacion de los proveedores
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
    
	
    Function ListarProveedoresGeneradosPO($preorden_id)
    {
	  		
  		$sql = "  SELECT  DISTINCT  t.codigo_proveedor_id,
                                  t.sw_unificada,
                                  ter.nombre_tercero
  				FROM       			        informacion_preorden_detalle t,
                									terceros ter,
                									terceros_proveedores p
                WHERE     			t.codigo_proveedor_id=p.codigo_proveedor_id
                AND        			p.tipo_id_tercero=ter.tipo_id_tercero
                AND			        p.tercero_id=ter.tercero_id  
                AND        			t.preorden_id=".$preorden_id." 
                AND  				    t.sw_unificada=0
                AND  				    t.codigo_proveedor_id in ( select  p.codigo_proveedor_id
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
   /*
		* Funcion donde se consulta el detalle de las preordenes de comrpra
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	 	function ConsultarDetallePreOrden($preorden_id,$filtros,$offset)
    {
      	
      
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
                            i.cantidad as cant,
							i.contenido_unidad_venta,
                            u.abreviatura,
                            m.molecula_id,
                            m.descripcion as molecula,
                            l.laboratorio_id,
                            l.descripcion as laboratorio,
                            ter.tipo_id_tercero,
                            ter.tercero_id
      		FROM              informacion_preorden_detalle t,
                    				informacion_preorden  p,
                    				inventarios_productos i,
                    				terceros_proveedores pro,
                    				terceros ter,
                    				inv_subclases_inventarios s,
                    				inv_moleculas m,
                    				inv_clases_inventarios c,
                    				inv_laboratorios l,
                    				unidades u
      		WHERE           p.preorden_id=t.preorden_id
                  and     t.codigo_producto=i.codigo_producto
                  and     t.codigo_proveedor_id=pro.codigo_proveedor_id
                  and     pro.tipo_id_tercero=ter.tipo_id_tercero
                  and     pro.tercero_id=ter.tercero_id
                  and     i.grupo_id=s.grupo_id
                  and 	  i.clase_id=s.clase_id
                  and     i.subclase_id=s.subclase_id
                  and     s.molecula_id=m.molecula_id
                  and     s.grupo_id=c.grupo_id
                  and     s.clase_id=c.clase_id
                  and     c.laboratorio_id=l.laboratorio_id
                  and     t.sw_unificada=0
                  and     p.sw_preorden=1
                  and     i.unidad_id=u.unidad_id
                  and     t.preorden_id='".$preorden_id."'
                  and     t.codigo_proveedor_id=".$filtros['proveedor_id']."		";
      	      
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
    /*
		* Funcion donde se consulta la informacion  Detalle de la preorden de compras
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
       
	  function SeleccionarInformacionDetalle($preorden_id,$proveedor_id)
	  {
	     
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
                        ter.tercero_id,
                        t.valor_unitario
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
            		and 	  i.clase_id=s.clase_id
            		and     i.subclase_id=s.subclase_id
            		and     s.molecula_id=m.molecula_id
            		and     s.grupo_id=c.grupo_id
            		and     s.clase_id=c.clase_id
            		and     c.laboratorio_id=l.laboratorio_id
            		and     t.sw_unificada=0
            		and     p.sw_preorden=1
            		and     t.preorden_id='".$preorden_id."'
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
   /*
		* Funcion donde se inserta  las ordenes de pedido  que han sido unificadas  por preorden 
		* @return boolean de acuerdo a la ejecucion del sql.
	*/
	  
		function  insertarOrden_Pedido($pedido_id,$codigo_proveedor_id,$empresa)
		{
		 
			 $indice = array();
        $pedidoid=$pedido_id + 1;
		    $this->ConexionTransaccion();
		
			$sql  = "INSERT INTO compras_ordenes_pedidos( 
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
                                                 ".$pedidoid.",
                                                  ".$codigo_proveedor_id.",
                                                  '".$empresa."',
                                                  NOW(),
                                                  ".UserGetUID().",
                                                  1,
                                                  NULL,
                                                  NULL,
                                                  0,
                                                  '".$empresa."'									
                                                ); ";
			
      			if(!$rst = $this->ConexionTransaccion($sql))
      			{
      			return false;
      			}

      			$this->Commit();
      			return true;
		}
		 /*
		* Funcion donde se consulta el ultimo registro se la  orden de compras 
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		 
		function SeleccionarMaxcompras_ordenes_pedidos($codigo_proveedor_id,$empresa_id)
		{
		
		    if($codigo_proveedor_id=="-1")
			{
			$sql = "  SELECT   MAX(orden_pedido_id) AS numero FROM compras_ordenes_pedidos; ";
			}
			else
			{
			$sql = "  SELECT   MAX(orden_pedido_id) AS numero FROM compras_ordenes_pedidos
					  where    codigo_proveedor_id='".$codigo_proveedor_id."'
					  and      empresa_id_pedido ='".$empresa_id."'; ";
			}

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
		* Funcion donde se inserta  el detalle de las ordenes de compra
		* @return boolean de acuerdo a la ejecucion del sql.
	*/ 
   
		function Ingresarcompras_ordenes_pedidos_detalle($datos,$orden_pedido_id)
		{
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
                              preorden_detalle_id,
                              valor_unitario
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
                ".$fila['preorden_detalle_id'].",
                ".$fila['valor_unitario']."
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
		* Funcion donde  se actualiza el estado de unificadas de la pre orden
		* @return boolean de acuerdo a la ejecucion del sql.
	*/
        
		function ActuEstado($preorden_id,$codigo_proveedor_id)
		{
      
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
	 /*
		* Funcion donde  se  consulta las preordenes que no han estan unificadas.
		* @return boolean de acuerdo a la ejecucion del sql.
	*/
        
		function consultarSw_Unificados($preorden_id)
		{
		   
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
  /*
		* Funcion donde  se insertan las condiciones  para las ordenes de compras
		* @return boolean de acuerdo a la ejecucion del sql.
	*/
		
		function  insertarCondicionesOrden_Pedido($empresa_id,$orden_pedido_id,$condicion)
		{
			 
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
    
 
	  /*
		* Funcion donde se Consultan  las ordenes de compras generadas pero que no esten unificadas
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	  function ConsultarOrdenComprasGeneradas($filtros,$offset)
	  {
		
			$sql= "  SELECT o.orden_pedido_id,
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
					FROM        compras_ordenes_pedidos  o,
									    system_usuarios  s,
											empresas e,
											terceros_proveedores p,
											terceros ter
				  WHERE       o.empresa_id=e.empresa_id
              and     o.usuario_id=s.usuario_id
              and     o.codigo_proveedor_id=p.codigo_proveedor_id
              and     p.tipo_id_tercero=ter.tipo_id_tercero
              and     p.tercero_id=ter.tercero_id 
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
	   /*
		* Funcion donde se Consulta el detalle de la orden de compra
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	  function  ConsultarDetalleCompra($orden_pedido_id)
	  
	  {
	  
	    $sql  = " select  d.orden_pedido_id,
                        d.codigo_producto,
                        d.numero_unidades,
                        d.valor,
                        fc_descripcion_producto(d.codigo_producto) as producto
                        
              from      compras_ordenes_pedidos_detalle d
                        
            WHERE       
                      d.orden_pedido_id='".$orden_pedido_id."'; ";
	  
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
		* Funcion donde se Consulta  los datos de la empresa que realiza el pedido
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	     function  EmpresasOrden_Pedido()
		 {
		 
				$sql= " SELECT     DISTINCT   	c.empresa_id,
                                        c.sw_unificada,
                                        e.razon_social
				        FROM  				         	compras_ordenes_pedidos c,
                                        empresas e
				       WHERE         			      c.empresa_id=e.empresa_id 
				       AND  	    			        c.sw_unificada=0 ";
				

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
      /*
		* Funcion donde se Consulta  las ordenes de pedido
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		 
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
          AND        				  p.tipo_id_tercero=ter.tipo_id_tercero
          AND			      	  	p.tercero_id=ter.tercero_id  
          AND  					      t.sw_unificada=0
          AND                 t.empresa_id='".$empresa."'
          AND                 t.estado='1'
          AND                   t.orden_pedido_id in (select orden_pedido_id from compras_ordenes_pedidos_detalle )
          AND  				        t.codigo_proveedor_id in ( select  p.codigo_proveedor_id
                                              FROM       			    terceros_proveedores p
                                              GROUP BY   		    	p.codigo_proveedor_id ,ter.nombre_tercero)
      
         "; 
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
  /*
		* Funcion donde consulta el detalle de las ordenes de pedido por proveedor
		* @return array $datos vector que contiene la informacion de la consulta.
	*/		
			function ListarDetalleOrdenPedidoXProveedor($empresa,$proveedor)
			{
			   
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
					from                      compras_ordenes_pedidos o,
                                    compras_ordenes_pedidos_detalle d,
                                    inventarios_productos i,
                                    inv_subclases_inventarios s,
                                    inv_moleculas m,
                                    inv_clases_inventarios c,
                                    inv_laboratorios l
                WHERE               o.orden_pedido_id=d.orden_pedido_id
                and                 d.codigo_producto=i.codigo_producto
                and                 i.grupo_id=s.grupo_id
                and 	              i.clase_id=s.clase_id
                and                 i.subclase_id=s.subclase_id
                and                 s.molecula_id=m.molecula_id
                and                 s.grupo_id=c.grupo_id
                and                 s.clase_id=c.clase_id
                and                 c.laboratorio_id=l.laboratorio_id
                and                 o.estado = '1' 
                and                 o.sw_unificada='0'
                and                 d.numero_unidades > numero_unidades_recibidas
                and                 o.empresa_id='".$empresa."'
                and                  o.codigo_proveedor_id='".$proveedor."' ";

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
      function ListarDetalleOrdenPedidoXProveedorDos($empresa,$proveedor)
			{
			   
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
					from                      compras_ordenes_pedidos o,
                                    compras_ordenes_pedidos_detalle d,
                                    inventarios_productos i,
                                    inv_subclases_inventarios s,
                                    inv_moleculas m,
                                    inv_clases_inventarios c,
                                    inv_laboratorios l
                WHERE               o.orden_pedido_id=d.orden_pedido_id
                and                 d.codigo_producto=i.codigo_producto
                and                 i.grupo_id=s.grupo_id
                and 	              i.clase_id=s.clase_id
                and                 i.subclase_id=s.subclase_id
                and                 s.molecula_id=m.molecula_id
                and                 s.grupo_id=c.grupo_id
                and                 s.clase_id=c.clase_id
                and                 c.laboratorio_id=l.laboratorio_id
                and                 o.estado = '1' 
                and                 o.sw_unificada='0'
                and                 d.numero_unidades_recibidas IS NULL
                and                 o.empresa_id='".$empresa."'
                and                  o.codigo_proveedor_id='".$proveedor."' ";

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
      
      
      
      
      
      
      
      
      
      
      
      
    /*
		* Funcion donde se consulta las ordenes de compras que van hacer unidicadas
		* @return array $datos vector que contiene la informacion de la consulta.
	*/		
		    function UnificarDatos2($codigo_producto,$proveedor)
			{
			   
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
	/*
		* Funcion donde  se insertan el documento de pedido
		* @return boolean de acuerdo a la ejecucion del sql.
	*/
			function  ingresarDocumentoDePedido($empresa_id,$codigo_proveedor_id,$observacion)
			{
			
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
  /*
		* Funcion donde se selecciona el ultimo registro del documento de pedido generado.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/		
		
		function SeleccionarDocumentoDePedido($empresa,$proveedor)
		{
		   
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
		/*
		* Funcion donde se selecciona el ultimo registro del documento de pedido generado.
		* @return boolean de acuerdo a la ejecucion del sql.
	*/		
		
		function  InsertarDatosPendientes($datos,$id)
		{
		  
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
  /*
		* Funcion donde se consulta el documento de pedido
		* @return array $datos vector que contiene la informacion de la consulta.
	*/		
		
		function ConsultarDocumentoPedidoOP($prod_pend_op_id)
		{
		 
			$sql="SELECT    d.prod_pend_op_id_d,
                      d.prod_pend_op_id,
                      d.empresa_id,
                      d.codigo_producto,
                      d.numero_unidades,
                      d.valor,
                      d.porc_iva,
                      d.fecha_registro
					FROM 	      productos_pendientes_ordenpedido_d d
					WHERE     	d.prod_pend_op_id=".$prod_pend_op_id."
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
     /*
		* Funcion donde se selecciona actualizan las ordenes de pedido que han sido unificadas y las preordenes que ya han sido asignadas a una orden de compra
	     * @return boolean de acuerdo a la ejecucion del sql.
	*/		
	
		function ActualizarSw_unificadaOp($empresa_id,$codigo_proveedor_id,$orden_pedido_id,$prod_pend_op_id)
		{
		  
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
  /*
		* Funcion donde se Inserta el detalle de las ordenes de pedido
		* @return boolean de acuerdo a la ejecucion del sql.
	*/		
		function Ingresarcompras_ordenes_pedidos_detalle_d($datos,$orden_pedido_id)
		{
			
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
			
  /*
		* Funcion donde se  consulta la informacion del usuario actual
		* @return array $datos vector que contiene la informacion de la consulta.
	*/		
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
  /*
		* Funcion donde se  consulta las  condiciones activas   ya establecidas para las ordenes de compras
		* @return array $datos vector que contiene la informacion de la consulta.
	*/		
		 function ConsultarCondicionesEstablecidas()
		{
			
			$sql = " SELECT condicion_compra_id,
                      descripcion,
                      estado 
					FROM 	inv_condiciones_compra
					WHERE 	estado = '1' ";
		 	 
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
     /*
		* Funcion donde se  listan los proveedores de las ordenes de compras
		* @return array $datos vector que contiene la informacion de la consulta.
	*/		
		
		function ListarProveedoresOrdenCompra()
		{
		
			$sql = " SELECT  DISTINCT  	t.codigo_proveedor_id,
                                  ter.nombre_tercero
              FROM    			      compras_ordenes_pedidos t,
                                  terceros ter,
                                  terceros_proveedores p
              WHERE  				      t.codigo_proveedor_id=p.codigo_proveedor_id
              AND    				      p.tipo_id_tercero=ter.tipo_id_tercero
              AND					        p.tercero_id=ter.tercero_id 
              and                 t.estado='1'					
              AND    			      	t.codigo_proveedor_id in ( select  p.codigo_proveedor_id
              FROM       			    terceros_proveedores p
              GROUP BY   		      p.codigo_proveedor_id ,ter.nombre_tercero) ";
					
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
  /*
		* Funcion donde se  los proveedores de ordenes de compras que existen 
		* @return array $datos vector que contiene la informacion de la consulta.
	*/		
		
		Function ConsultarProveedoresOrdenCompra($codprove)
		{
		
			$sql = "SELECT  DISTINCT  t.codigo_proveedor_id,
                                t.fecha_orden,
                                ter.nombre_tercero,
                                ter.tipo_id_tercero,
                                ter.tercero_id,
                                u.nombre,
                                t.orden_pedido_id
					FROM   	               compras_ordenes_pedidos t,
                  							terceros ter,
                  							terceros_proveedores p,
                  							system_usuarios u
					WHERE      t.codigo_proveedor_id=p.codigo_proveedor_id
					AND        p.tipo_id_tercero=ter.tipo_id_tercero
					AND		     p.tercero_id=ter.tercero_id  
					AND       t.sw_unificada=0
					AND        t.estado='1'
					AND        t.usuario_id=u.usuario_id
					AND        t.codigo_proveedor_id='".$codprove."'	"; 
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
     /*
		* Funcion donde se  consulta el detalle de las ordenes  de compras
		* @return array $datos vector que contiene la informacion de la consulta.
	*/		
		
		function  ConsultarOrdenCompraDetalle($orden)
	  {
	   
	    $sql  = " 		select      d.codigo_producto,
                                d.numero_unidades,
                                d.valor,
                                d.numero_unidades_recibidas,
                                i.descripcion as producto,
                                m.molecula_id,
                                m.descripcion as molecula,
                                l.laboratorio_id,
                                l.descripcion as laboratorio,
                                i.cantidad as cant,
                                u.unidad_id as abreviatura,
                                i.contenido_unidad_venta
						from			          compras_ordenes_pedidos_detalle d,
                                inventarios_productos i,
                                inv_subclases_inventarios s,
                                inv_moleculas m,
                                inv_clases_inventarios c,
                                inv_laboratorios l,
                                unidades u
						WHERE			   	d.codigo_producto=i.codigo_producto
						and     			i.grupo_id=s.grupo_id
						and 				  i.clase_id=s.clase_id
						and     			i.subclase_id=s.subclase_id
						and     			s.molecula_id=m.molecula_id
						and     			s.grupo_id=c.grupo_id
						and     			s.clase_id=c.clase_id
						and     			c.laboratorio_id=l.laboratorio_id
						and     			i.unidad_id=u.unidad_id
						and     			d.orden_pedido_id='".$orden."'
						and     			d.numero_unidades_recibidas IS NULL
						and     			d.estado='1'
						UNION
											select 		d.codigo_producto,
    														d.numero_unidades,
    														d.valor,
    														d.numero_unidades_recibidas,
    														i.descripcion as producto,
    														m.molecula_id,
    														m.descripcion as molecula,
    														l.laboratorio_id,
    														l.descripcion as laboratorio,
    														i.cantidad as cant,
    														u.unidad_id as abreviatura,
                                i.contenido_unidad_venta                                
											from    	compras_ordenes_pedidos_detalle d,
                                inventarios_productos i,
                                inv_subclases_inventarios s,
                                inv_moleculas m,
                                inv_clases_inventarios c,
                                inv_laboratorios l,
                                unidades u
											WHERE   	d.codigo_producto=i.codigo_producto
											and    		i.grupo_id=s.grupo_id
											and 		i.clase_id=s.clase_id
											and     	i.subclase_id=s.subclase_id
											and     	s.molecula_id=m.molecula_id
											and     	s.grupo_id=c.grupo_id
											and     	s.clase_id=c.clase_id
											and     	c.laboratorio_id=l.laboratorio_id
											and     	i.unidad_id=u.unidad_id
									        and     	d.orden_pedido_id='".$orden."'
											and   		d.numero_unidades_recibidas < d.numero_unidades 
											and    		d.estado='1' ";
	  
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
		* Funcion donde se  consulta el detalle de las ordenes  de compras
		* @return array $datos vector que contiene la informacion de la consulta.
	*/		
    
	  function  ConsultarOrdenCompraDetalle_($orden)
	  {
	    
	    $sql  = " 	SELECT 	orden_pedido_id,
            							codigo_producto,
            							numero_unidades,
            							valor,
            							porc_iva,
            							numero_unidades_recibidas,
            							preorden_detalle_id,
            							valor_unitario
					FROM 	          compras_ordenes_pedidos_detalle
					WHERE 	        orden_pedido_id='".$orden."'
					and    	        numero_unidades_recibidas IS NULL
					and              estado='1'
    					UNION
                SELECT 	orden_pedido_id,
                        codigo_producto,
                        numero_unidades,
                        valor,
                        porc_iva,
                        numero_unidades_recibidas,
                        preorden_detalle_id,
                        valor_unitario
                FROM 		compras_ordenes_pedidos_detalle
                WHERE   orden_pedido_id='".$orden."'
                and   	numero_unidades_recibidas < numero_unidades 
                and     estado='1' ";
	  
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
		* Funcion donde se Inserta a la tabla temporal las ordenes de pedidos que van hacer unificadas
      * @return boolean de acuerdo a la ejecucion del sql.
	*/		
    function Ingresar_tmpcompras_ordenes_pedidos($datos,$proveedor)
		{
     
			foreach($datos as $item=>$fila)
			{
				$this->ConexionTransaccion();
                  if(empty($fila['valor_unitario']))
                  {
                     $fila['valor_unitario']=0;
                  }
                   if(empty($fila['preorden_detalle_id']))
                  {
                     $fila['preorden_detalle_id']=0;
                  }
                  
                  
                if(!empty($fila['numero_unidades_recibidas']))				  
				{
					 $sql .= "INSERT INTO   tmp_Unificadas_Orden_Pedidos
	                        (
                                  item_id,
                                  orden_pedido_id,
                                  codigo_proveedor_id,
                                  codigo_producto,
                                  numero_unidades,
                                  valor,
                                  porc_iva,
                                  numero_unidades_recibidas,
                                  preorden_detalle_id,
                                  valor_unitario,
                                  usuario_id,
                                  fecha_registro
						    )
	                VALUES
	                (
                                nextval('tmp_unificadas_orden_pedidos_item_id_seq'),
                                ".$fila['orden_pedido_id'].",
                                ".$proveedor.",
                                '".$fila['codigo_producto']."',
                                ".$fila['numero_unidades'].",
                                ".$fila['valor'].",
                                ".$fila['porc_iva'].",
                                ".$fila['numero_unidades_recibidas'].",
                                ".$fila['preorden_detalle_id'].",
                                ".$fila['valor_unitario'].",
                                ".UserGetUID().",
                                NOW()
                                );
                                ";
				}
				else 
				{
					 $sql .= "INSERT INTO   tmp_Unificadas_Orden_Pedidos
	                        (
                                  item_id,
                                  orden_pedido_id,
                                  codigo_proveedor_id,
                                  codigo_producto,
                                  numero_unidades,
                                  valor,
                                  porc_iva,
                                  numero_unidades_recibidas,
                                  preorden_detalle_id,
                                  valor_unitario,
                                  usuario_id,
                                  fecha_registro
						    )
	                VALUES
	                (
                                  nextval('tmp_unificadas_orden_pedidos_item_id_seq'),
                                  ".$fila['orden_pedido_id'].",
                                  ".$proveedor.",
                                  '".$fila['codigo_producto']."',
                                  ".$fila['numero_unidades'].",
                                  ".$fila['valor'].",
                                  ".$fila['porc_iva'].",
                                  0,
                                  ".$fila['preorden_detalle_id'].",
                                  ".$fila['valor_unitario'].",
                                  ".UserGetUID().",
                                  NOW()
                                  );
                                  ";
				}
								
				
			}
			if(!$rst1 = $this->ConexionTransaccion($sql))
			{
				return false;
			}
			$this->Commit();
			return true;
		}
  /*
		* Funcion donde se  consulta la informacion temporal de la orden de compras
		* @return array $datos vector que contiene la informacion de la consulta.
	*/			
		function  Consultar_tmp_OrdenPedidoDetalle($proveedor)
	  {
	   
	    	$sql  = " 	SELECT 	DISTINCT d.orden_pedido_id,
            								To_char(d.fecha_registro,'yyyy-mm-dd') as fecha_registro, 
            								u.nombre
        						FROM  	tmp_unificadas_orden_pedidos d,
                            system_usuarios u
        						WHERE 	d.codigo_proveedor_id=".$proveedor." and d.usuario_id=u.usuario_id ";
	  
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
		* Funcion donde se  consulta la informacion de la tabla temporal 
		* @return array $datos vector que contiene la informacion de la consulta.
	*/			  
	  function  Consultar_tmp_OrdenPedido($proveedor,$orden)
	  {
	      
	    	$sql  = " 	SELECT 	DISTINCT d.orden_pedido_id,
                    To_char(d.fecha_registro,'yyyy-mm-dd') as fecha_registro, 
                    u.nombre
                    FROM 	tmp_unificadas_orden_pedidos d,
                    system_usuarios u
						WHERE 	d.codigo_proveedor_id=".$proveedor." 
						and     d.orden_pedido_id=".$orden."
						and     d.usuario_id=u.usuario_id ";
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
		* Funcion donde se  elimina la informacion de la tabla temporal
		* @return boolean de acuerdo a la ejecucion del sql.
	*/		
    
	  function  Eliminar_tmp_OrdenPedido($proveedor)
	  {
        
        $this->ConexionTransaccion();  
        $sql = "DELETE FROM tmp_unificadas_orden_pedidos ";
        $sql .= "WHERE codigo_proveedor_id = ".$proveedor." ;";

        if(!$rst = $this->ConexionTransaccion($sql))
        {
        return false;      
        }

        $this->Commit();

        return true;      
	  	  
	  }
	/*
		* Funcion donde consulta la informacion  detallada del proveedor
		* @return array $datos vector que contiene la informacion de la consulta.
	*/		
		function InformacionDetalleProveedor($proveedor)
		{
		
			$sql = "	SELECT 	t.tipo_id_tercero,
        								t.tercero_id,
        								t.nombre_tercero,
        								t.dv,
        								p.codigo_proveedor_id
						FROM    terceros t,
						        terceros_proveedores p
						WHERE   t.tipo_id_tercero=p.tipo_id_tercero
						and     t.tercero_id=p.tercero_id
						and     t.codigo_proveedor_id=".$proveedor."; ";
	
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
		* Funcion donde consulta la informacion  temporal de la unificacion de la orden de compra, se realiza por proveedor
		* @return array $datos vector que contiene la informacion de la consulta.
	*/		
		function SeleccionarInformacionTmp_OrdenPedido($proveedor)
		{
		  
			$sql = " SELECT 	orden_pedido_id,
                        codigo_proveedor_id,
                        codigo_producto,
                        numero_unidades,
                        valor,
                        porc_iva,
                        numero_unidades_recibidas,
                        preorden_detalle_id,
                        valor_unitario
					FROM 		      tmp_unificadas_orden_pedidos
					WHERE 		    codigo_proveedor_id = '".$proveedor."' ";
		
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
		* Funcion donde se actualiza los estados de las ordenes de compras pasan a unificadas cuando se esta unificando por proveedor
	* @return boolean de acuerdo a la ejecucion del sql.
	*/		
    
		function Ingresarcompras_ordenes_pedidos_d($datos,$orden_pedido_id)
		{
			
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
                                preorden_detalle_id,
                                valor_unitario
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
                              ".$fila['numero_unidades_recibidas'].",
                              ".$fila['preorden_detalle_id'].",
                              ".$fila['valor_unitario']."
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
		* Funcion donde se actualiza los estados de las ordenes de compras pasan a unificadas cuando se esta unificando por proveedor
		* @return boolean de acuerdo a la ejecucion del sql.
	*/		
		function ActuEstadosOrdenesPedidoUnificadas($datos,$proveedor)
		{
		  
			foreach($datos as $item=>$fila)
			{
				$sql .= "	UPDATE  compras_ordenes_pedidos
                  SET    sw_unificada=1              
                  WHERE  codigo_proveedor_id = ".$proveedor."
                  AND   orden_pedido_id =".$fila['orden_pedido_id']."; ";
			}
				if(!$resultado = $this->ConexionBaseDatos($sql))
				{
				$cad="Operacion Invalida";
				return false;
				} 
				return true;
		}
  /*
		* Funcion donde se selecciona Informacion adicional de la orden de compras.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/		
		
		Function ConsultarInformacionOrdenCompra($orden)
		{
		
			$sql = " SELECT     orden_pedido_id,
                          To_char(fecha_orden,'yyyy-mm-dd') as fecha_orden 
                FROM 	    compras_ordenes_pedidos
                WHERE 	  orden_pedido_id = '".$orden."' ";
		
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
		* Funcion donde se Consultan el tipo id .
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
    
		function ConsultarTipoId()
      {
       
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
	  
	function ListarTercerosProveedores($EmpresaId,$TipoId,$Nombre)
    {
	  	
  		$sql = "SELECT  
		
				ter.*,
				p.*
		
  				FROM       	
				
				terceros ter,
                terceros_proveedores p
				
                WHERE     			
									ter.nombre_tercero ILIKE '%".$Nombre."%'
									AND
									ter.tipo_id_tercero ILIKE '%".$TipoId."%'
									AND
									ter.tipo_id_tercero=p.tipo_id_tercero
									AND			        
									ter.tercero_id=p.tercero_id  
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
	
	
	function InformacionTercerosProveedores($CodigoProveedorId)
    {	
  		$sql = "SELECT  
		
				ter.*,
				p.*
		
  				FROM       	
				
				terceros ter,
                terceros_proveedores p
				
                WHERE     			
									p.codigo_proveedor_id = ".$CodigoProveedorId."
									AND
									p.tipo_id_tercero = ter.tipo_id_tercero
									AND			        
									p.tercero_id = ter.tercero_id
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

	function IngresarOrdenCompra($orden_pedido_id,$codigoproveedorid,$empresa_id)
		{
				
		 		$this->ConexionTransaccion();
				$sql .= "INSERT INTO   compras_ordenes_pedidos
                        (
                                	orden_pedido_id,
									codigo_proveedor_id,
									empresa_id,
									fecha_orden,
									usuario_id,
									estado
						)
                          VALUES
                          (
                              ".$orden_pedido_id.",
                              '".$codigoproveedorid."',
                              '".$empresa_id."',
                              NOW(),
                              ".UserGetUID().",
                              1
                          )
                              ";
			
			if(!$rst1 = $this->ConexionTransaccion($sql))
			{
				return false;
			}
			$this->Commit();
			return true;
		}	
	
  function ListaLaboratorios()
  {
 // $codigo_barras=eregi_replace("'","-",$CodigoBarras);
    
	  $sql="
            Select 
                    laboratorio_id,
                    descripcion
                    from
                          inv_laboratorios
                    where
                          estado = '1'
                          
                          ORDER BY descripcion ASC
                          ";
         
  
  if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
   }
   
   function ListaMoleculas()
  {
 // $codigo_barras=eregi_replace("'","-",$CodigoBarras);
     
	  $sql="
            Select 
                    molecula_id,
                    descripcion
                    from
                          inv_moleculas
                    where
                          estado = '1'
                          
                          ORDER BY descripcion ASC
                          ";
         
  
  if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
   }
	
  	function ListaProductosInventario($CodigoProducto,$Descripcion,$Concentracion,$Empresa_Id,$ClaseId,$SubClaseId,$offset)
    {
      // $codigo_barras=eregi_replace("'","-",$CodigoBarras);
      $sql = "Select grp.descripcion as Grupo,
                    sub.descripcion as Subclase,
                    prod.codigo_producto,
                    fc_descripcion_producto(prod.codigo_producto) as descripcion,
                    prod.porc_iva as iva,
                    grp.sw_medicamento,
                    inv.costo_ultima_compra
              from  inv_grupos_inventarios grp,
                    inv_clases_inventarios cla,
                    inv_subclases_inventarios sub,
                    inventarios_productos prod,
                    unidades uni,
                    inventarios inv
              WHERE prod.subclase_id = sub.subclase_id
              and   sub.clase_id = prod.clase_id
              and   sub.grupo_id = prod.grupo_id
              and   sub.clase_id = cla.clase_id
              and   cla.grupo_id = prod.grupo_id
              and   cla.grupo_id = grp.grupo_id
              and   prod.unidad_id = uni.unidad_id
						  and   prod.codigo_producto = inv.codigo_producto
						  and	  inv.empresa_id = '".$Empresa_Id."'
						  and	  prod.estado = '1' ";
        if($Descripcion != "")
          $sql .= "AND    prod.descripcion ILike '".$Descripcion."%' ";
        
        if($CodigoProducto != "")
          $sql .= "AND	  prod.codigo_producto ILike '".$CodigoProducto."' ";
        if($Concentracion != "")
          $sql .= "AND	  prod.contenido_unidad_venta ILike '%".$Concentracion."%' ";
        
        if($ClaseId != "")
          $sql .= "AND   prod.clase_id = '".$ClaseId."' ";
        
        if($SubClaseId != "")
          $sql .= "AND   prod.subclase_id = '".$SubClaseId."' ";
              
        if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
          return false;
              
        $sql .= "ORDER BY prod.descripcion ASC ";
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";  

  
  if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
   }
  
  
  
	function AgregarItemOC($OrdenPedidoId,$Empresa_Id,$CodigoProducto,$NumeroUnidades,$Valor,$PorcIva)
		{
			
			$this->ConexionTransaccion();
			$sql = "INSERT INTO   compras_ordenes_pedidos_detalle
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
                ".$OrdenPedidoId.",
                '".$CodigoProducto."',
                ".$NumeroUnidades.",
                ".$Valor.",
                ".$PorcIva.",
                1
                );
                ";
			
			if(!$rst1 = $this->ConexionTransaccion($sql))
			{
				return false;
			}
			$this->Commit();
			return true;
		}
		
		function  ConsultarOC($orden_pedido_id)
	  
	  {
	 
	    $sql  = " select
						d.item_id,
						d.orden_pedido_id,
            d.codigo_producto,
						
						d.porc_iva as iva,
                        d.numero_unidades,
                        d.valor,
						fc_descripcion_producto(i.codigo_producto) as descripcion,
                        u.unidad_id as abreviatura 
              from      compras_ordenes_pedidos_detalle d,
                        inventarios_productos i,
            			inv_subclases_inventarios s,
            			inv_clases_inventarios c,
            			unidades u
            WHERE       d.codigo_producto=i.codigo_producto
            and         i.grupo_id=s.grupo_id
            and       	i.clase_id=s.clase_id
            and         i.subclase_id=s.subclase_id
            and         s.grupo_id=c.grupo_id
            and         s.clase_id=c.clase_id
            and         i.unidad_id=u.unidad_id
		        and         d.orden_pedido_id='".$orden_pedido_id."'; ";
	  
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
	
	function  Eliminar_ComprasOrdenPedido_Detalle($OrdenPedidoId,$Empresa_Id)
	  {
        $this->ConexionTransaccion();  
        $sql = "DELETE FROM compras_ordenes_pedidos_detalle ";
        $sql .= "WHERE orden_pedido_id = ".$OrdenPedidoId." ;";

        if(!$rst = $this->ConexionTransaccion($sql))
        {
        return false;      
        }

        $this->Commit();

        return true;      
	  	  
	  }
	  
	  function  Eliminar_ComprasOrdenPedido($OrdenPedidoId,$Empresa_Id)
	  {
        $this->ConexionTransaccion();  
        $sql = "DELETE FROM compras_ordenes_pedidos ";
        $sql .= "WHERE orden_pedido_id = ".$OrdenPedidoId." ;";

        if(!$rst = $this->ConexionTransaccion($sql))
        {
        return false;      
        }

        $this->Commit();

        return true;      
	  	  
	  }
    	function SeleccionarInformacionEmpresa($empresaid)
		{
		
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
		
		
			$sql = "  SELECT 	d.item_id,
								d.orden_pedido_id,
								d.codigo_producto,
                fc_descripcion_producto(d.codigo_producto) as nombre,
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
	function SeleccionarMaxiPedido()
	{
	
		$sql = " SELECT   MAX(orden_pedido_id) AS numero FROM compras_ordenes_pedidos; ";
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
		
    function AnularOC($orden_pedido_id)
		{
			$sql = "	 UPDATE  compras_ordenes_pedidos
                  SET    estado= '2'              
                  WHERE  orden_pedido_id = ".$orden_pedido_id." ";
		
      if(!$resultado = $this->ConexionBaseDatos($sql))
				return false;
		 
      return true;
		}
	}
?>