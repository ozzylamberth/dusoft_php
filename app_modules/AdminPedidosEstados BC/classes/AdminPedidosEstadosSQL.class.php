<?php
  class AdminPedidosEstadosSQL extends ConexionBD
  {
    /*********************************
    * Constructor
    *********************************/
    function AdminPedidosEstadosSQL(){}


    /**
    * Metodo para obtener los pedidos de las farmacias relacionadas a la empresa
    *
    * @param string $empre
    * @param integer $pedido_id
    * @return array
    * @access public
    */
    function ObtenerPedidosClientes($pedido_cliente_id)
    {
      $sql  = "SELECT VOP.pedido_cliente_id AS pedido, ";
      $sql .= "       CASE WHEN VOP.estado_pedido = '0' THEN 'Registrado' ";
      $sql .= "         WHEN VOP.estado_pedido = '1' THEN 'Separado' ";
      $sql .= "         WHEN VOP.estado_pedido = '2' THEN 'Auditado' ";
      $sql .= "         WHEN VOP.estado_pedido = '3' THEN 'En Despacho' ";
      $sql .= "         WHEN VOP.estado_pedido = '4' THEN 'Despachado' ";
      $sql .= "         ELSE '' END AS estado ";
      $sql .= "FROM   ventas_ordenes_pedidos VOP ";
      $sql .= "       LEFT JOIN terceros TR ON (VOP.tercero_id = TR.tercero_id) ";
      $sql .= "         AND (VOP.tipo_id_tercero = TR.tipo_id_tercero) ";
      $sql .= "       LEFT JOIN inv_tipos_bloqueos ITB ON (TR.tipo_bloqueo_id = ITB.tipo_bloqueo_id) ";
      $sql .= "WHERE  VOP.pedido_cliente_id = '".trim($pedido_cliente_id)."' ";
      $sql .= "AND    VOP.estado_pedido < '4' ";
      if(!empty($pedido_id)) {
        $sql .= "AND    VO.pedido_cliente_id = '".trim($pedido_cliente_id)."' ";
      }
      $sql .= "ORDER BY 1 ";
      //echo $sql;
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

		

    /**
    * Metodo para obtener los pedidos de las farmacias relacionadas a la empresa
    *
    * @param string $empre
    * @param integer $pedido_id
    * @return array
    * @access public
    */
    function ObtenerPedidosFarmacias($empresa,/*$farmacia_id,$centro_utilidad,$bodega,*/$pedido_id)
    {
      $sql  = "SELECT solicitud_prod_a_bod_ppal_id AS pedido, ";
  	  $sql .= "       CASE WHEN estado = '0' THEN 'Registrado' ";
  	  $sql .= "       	WHEN estado = '1' THEN 'Separado' ";
  	  $sql .= "       	WHEN estado = '2' THEN 'Auditado' ";
  	  $sql .= "       	WHEN estado = '3' THEN 'En Despacho' ";
  	  $sql .= "       	WHEN estado = '4' THEN 'Despachado' ELSE '' END AS estado ";
      $sql .= "FROM   solicitud_productos_a_bodega_principal SD ";
      $sql .= "WHERE  SD.empresa_destino = '".trim($empresa)."' ";
      /*$sql .= "AND    SD.farmacia_id = '".trim($farmacia_id)."' ";
      $sql .= "AND    SD.centro_utilidad = '".trim($centro_utilidad)."' ";
      $sql .= "AND    SD.bodega = '".trim($bodega)."' ";*/
      $sql .= "AND    SD.estado < '4' ";
      //$sql .= "AND	  SD.sw_despacho = '0' ";
      if(!empty($pedido_id)) {
      	$sql .= "AND    SD.solicitud_prod_a_bod_ppal_id LIKE '".$pedido_id."%' ";
      }
      $sql .= "UNION DISTINCT ";
      $sql .= "SELECT DISTINCT SD.solicitud_prod_a_bod_ppal_id, ";
      $sql .= "       CASE WHEN estado = '0' THEN 'Registrado' ";
  	  $sql .= "       	WHEN estado = '1' THEN 'Separado' ";
  	  $sql .= "       	WHEN estado = '2' THEN 'Auditado' ";
  	  $sql .= "       	WHEN estado = '3' THEN 'En Despacho' ";
  	  $sql .= "       	WHEN estado = '4' THEN 'Despachado' ELSE '' END AS estado ";
      $sql .= "FROM   solicitud_productos_a_bodega_principal SD, ";
      $sql .= "       solicitud_productos_a_bodega_principal_detalle SE, ";
      $sql .= "       inv_mov_pendientes_solicitudes_frm FR ";
	    $sql .= "WHERE  SD.empresa_destino = '".trim($empresa)."' ";
      /*$sql .= "AND    SD.farmacia_id = '".trim($farmacia_id)."' ";
      $sql .= "AND    SD.centro_utilidad = '".trim($centro_utilidad)."' ";
      $sql .= "AND    SD.bodega = '".trim($bodega)."' ";*/
      $sql .= "AND    SD.estado < '4' ";
      //$sql .= "AND	  SD.sw_despacho = '1' ";
      $sql .= "AND	  SD.solicitud_prod_a_bod_ppal_id = SE.solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND    SE.solicitud_prod_a_bod_ppal_id = FR.solicitud_prod_a_bod_ppal_id ";
      $sql .= "AND    SE.solicitud_prod_a_bod_ppal_det_id = FR.solicitud_prod_a_bod_ppal_det_id ";
      if(!empty($pedido_id)) {
      	$sql .= "AND    SD.solicitud_prod_a_bod_ppal_id LIKE '".$pedido_id."%' ";
      }
      $sql .= "ORDER BY 1 ";
      //echo $sql;
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



    /**
    * Metodo para verificar si existen ó no los registros de los estados del pedido del cliente.
    *
    * @param integer $pedido_id
    * @return array
    * @access public
    */
    function VerificarExistenciaEstadosPedidoCliente($pedido_id)
    {

      $sql  = "SELECT COUNT(VOP.venta_orden_pedido_estado_id) ";
      $sql .= "FROM   ventas_ordenes_pedidos_estado VOP ";
      $sql .= "WHERE  VOP.pedido_cliente_id = '".trim($pedido_id)."' ";
      //echo $sql."<br>";
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



    /**
    * Metodo para verificar si existen ó no los registros de los estados del pedido de la farmacia.
    *
    * @param integer $pedido_id
    * @return array
    * @access public
    */
    function VerificarExistenciaEstadosPedidoFarmacia($pedido_id)
    {

	    $sql  = "SELECT COUNT(SDE.solicitud_prod_a_bod_ppal_id) ";
      $sql .= "FROM   solicitud_productos_a_bodega_principal_estado SDE ";
      $sql .= "WHERE  SDE.solicitud_prod_a_bod_ppal_id = '".trim($pedido_id)."' ";
      //echo $sql."<br>";
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


    /**
    * Metodo para obtener los responsables de los estados del pedido del cliente
    *
    * @param $pedido_id
    * @return array
    * @access public
    */
    function ObtenerResponsablesEstadosPedidoCliente($pedido_id)
    {
	    $sql  = "SELECT VOP.responsable_id, VOP.estado, OB.nombre ";
      $sql .= "FROM   ventas_ordenes_pedidos_estado VOP, ";
      $sql .= "		  operarios_bodega OB ";
      $sql .= "WHERE  VOP.pedido_cliente_id = '".trim($pedido_id)."' ";
      $sql .= "  	  AND VOP.responsable_id = OB.operario_id ";
      $sql .= "ORDER BY VOP.estado ";
      //echo $sql."<br>";
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


    /**
    * Metodo para obtener el valor del "sw_despacho" del pedido de la farmacia
    *
    * @param $pedido_id
    * @return array
    * @access public
    */
    function ObtenerSwDespachoPedidoFarmacia($pedido_id)
    {
      $sql  = "SELECT sw_despacho ";
      $sql .= "FROM   solicitud_productos_a_bodega_principal ";
      $sql .= "WHERE  solicitud_prod_a_bod_ppal_id = '".trim($pedido_id)."' ";
      //echo $sql."<br>";
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


    /**
    * Metodo para obtener los responsables de los estados del pedido de la farmacia
    *
    * @param $pedido_id
    * @return array
    * @access public
    */
    function ObtenerResponsablesEstadosPedidoFarmacia($pedido_id)
    {
      $sql  = "SELECT SDE.responsable_id, SDE.estado, OB.nombre ";
      $sql .= "FROM   solicitud_productos_a_bodega_principal_estado SDE, ";
      $sql .= "     operarios_bodega OB ";
      $sql .= "WHERE  SDE.solicitud_prod_a_bod_ppal_id = '".trim($pedido_id)."' ";
      $sql .= "     AND SDE.responsable_id = OB.operario_id ";
      $sql .= "ORDER BY SDE.estado ";
      //echo $sql."<br>";
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


    /**
    * Metodo para crear los registros de los estados del pedido del cliente
    *
    * @param integer $ventas_ordenes_pedidos_id
    * @param integer $estado
    * @access public
    */
    function crearRegistrosResponsablesEstadosPedidoCliente($ventas_ordenes_pedidos_id, $estado)
    {
      $sql = " INSERT INTO ventas_ordenes_pedidos_estado(
      pedido_cliente_id,
      estado,
      usuario_id)
      VALUES     (
        '".trim($ventas_ordenes_pedidos_id)."',
        '".trim($estado)."',
        ".UserGetUID()."
      )";
      //echo $sql."<br>";
      if(!$resultado = $this->ConexionBaseDatos($sql))
      {
      $cad="Operacion Invalida";
      return false;//$cad;
      } 
      $resultado->Close();
      return true;
    }


    /**
    * Metodo para crear los registros de los estados del pedido de la farmacia
    *
    * @param integer $pedido_id
    * @param integer $estado
    * @access public
    */
    function crearRegistrosResponsablesEstadosPedidoFarmacia($pedido_id, $estado)
	  {
  		$sql = " INSERT INTO solicitud_productos_a_bodega_principal_estado(
  		solicitud_prod_a_bod_ppal_id,
  		estado,
  		usuario_id)
  		VALUES     (
  			'".trim($pedido_id)."',
  			'".trim($estado)."',
  			".UserGetUID()."
  		)";
  		
  		if(!$resultado = $this->ConexionBaseDatos($sql))
  		{
  		$cad="Operacion Invalida";
  		return false;//$cad;
  		} 
  		$resultado->Close();
  		return true;
	  }


    /**
    * Metodo para obtener los operarios de la bodega
    *
    * @return array
    * @access public
    */
	  function ObtenerOperariosBodega()
    {
	    $sql  = "SELECT operario_id, nombre ";
      $sql .= "FROM   operarios_bodega ";
      $sql .= "ORDER BY nombre ";
      //echo $sql."<br>";
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


    /**
    * Metodo para enviar el pedido del cliente al siguiente estado
    *
    * @param integer $pedido_id
    * @param integer $siguiente_estado
    * @access public
    */
    function EnviarPedidoSiguienteEstadoCliente($pedido_id, $siguiente_estado)
    {
      $sql = "  UPDATE ventas_ordenes_pedidos
                    SET estado_pedido = ".$siguiente_estado."
                  WHERE pedido_cliente_id = '".$pedido_id."';";
      //echo $sql;
      if(!$resultado = $this->ConexionBaseDatos($sql))
      {
      $cad="Operacion Invalida";
      return false;
      } 
      $resultado->Close();
      return true;
    }


    /**
    * Metodo para enviar el pedido de la farmacia al siguiente estado
    *
    * @param integer $pedido_id
    * @param integer $siguiente_estado
    * @access public
    */
    function EnviarPedidoSiguienteEstadoFarmacia($pedido_id, $siguiente_estado)
	  {
  		$sql = "  UPDATE solicitud_productos_a_bodega_principal
                	  SET estado = ".$siguiente_estado."
  	              WHERE solicitud_prod_a_bod_ppal_id = '".$pedido_id."' ;";
  		//echo $sql;
  		if(!$resultado = $this->ConexionBaseDatos($sql))
  		{
  		$cad="Operacion Invalida";
  		return false;
  		} 
  		$resultado->Close();
  		return true;
	  }


    /**
    * Metodo para asignar el responsable del pedido del cliente en el siguiente estado
    *
    * @param integer $pedido_id
    * @param integer $siguiente_estado
    * @param integer $responsable_id
    * @access public
    */
    function AsignarResponsableSiguienteEstadoPedidoCliente($pedido_id, $siguiente_estado, $responsable_id)
    {
      $sql = "  UPDATE ventas_ordenes_pedidos_estado
                    SET responsable_id = ".$responsable_id.", fecha = localtimestamp
                  WHERE pedido_cliente_id = '".$pedido_id."'
                  AND estado = '".$siguiente_estado."' ;";
      //echo $sql;
      if(!$resultado = $this->ConexionBaseDatos($sql))
      {
      $cad="Operacion Invalida";
      return false;
      } 
      $resultado->Close();
      return true;
    }


    /**
    * Metodo para asignar el responsable del pedido de la farmacia en el siguiente estado
    *
    * @param integer $pedido_id
    * @param integer $siguiente_estado
    * @param integer $responsable_id
    * @access public
    */
    function AsignarResponsableSiguienteEstadoPedidoFarmacia($pedido_id, $siguiente_estado, $responsable_id)
	  {
  		$sql = "  UPDATE solicitud_productos_a_bodega_principal_estado
                	  SET responsable_id = ".$responsable_id.", fecha = localtimestamp
  	              WHERE solicitud_prod_a_bod_ppal_id = '".$pedido_id."'
  	              AND estado = '".$siguiente_estado."' ;";
  		//echo $sql;
  		if(!$resultado = $this->ConexionBaseDatos($sql))
  		{
  		$cad="Operacion Invalida";
  		return false;
  		} 
  		$resultado->Close();
  		return true;
	  }

  }
?>