<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasMensajesSistema.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 



 class ControlDespachos extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ControlDespachos(){}

  


  
 /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function Listar_DespachosAFarmacia($datos,$buscador,$offset)
{
		
		if(!empty($buscador['solicitud_prod_a_bod_ppal_id']))
		$filtro = "	AND a.solicitud_prod_a_bod_ppal_id = '".trim($buscador['solicitud_prod_a_bod_ppal_id'])."'	";
		
		if(!empty($buscador['numero']))
		$filtro .= "	AND a.numero = '".trim($buscador['numero'])."'	";
		$sql = "SELECT
		a.empresa_id,
		a.prefijo,
		a.numero,
		a.farmacia_id,
		d.descripcion||'-'||c.descripcion as farmacia,
		f.nombre as usuario_pedido,
		g.nombre as usuario_cruce,
		a.solicitud_prod_a_bod_ppal_id,
		a.fecha_registro as fecha_cruce,
		b.fecha_registro as fecha_pedido,
		a.usuario_id,
		a.sw_despachado,
		a.fecha_despacho,
		a.neveras,
		a.temperatura,
		a.numero_cajas,
		a.transportadora_id,
		a.numero_guia,
		a.conductor,
		a.sw_recibido,
		a.numero_cajas_recibidas,
		a.usuario_id_despacha,
		a.usuario_id_recibe,
		CASE WHEN a.sw_despachado = '1'
		THEN 'DESPACHADO@ok.png@disabled'
		ELSE
		'DESPACHAR@ambulancia.png@' END AS estado_despacho
		FROM
		inv_bodegas_movimiento_despachos_farmacias as a
		JOIN solicitud_productos_a_bodega_principal as b ON (a.solicitud_prod_a_bod_ppal_id = b.solicitud_prod_a_bod_ppal_id)
		JOIN bodegas as c ON (b.farmacia_id = c.empresa_id)
		AND (b.centro_utilidad = c.centro_utilidad)
		AND (b.bodega = c.bodega)
		JOIN centros_utilidad as d ON (c.empresa_id = d.empresa_id)
		AND (c.centro_utilidad = d.centro_utilidad)
		JOIN empresas as e ON (d.empresa_id = e.empresa_id)
		JOIN system_usuarios as f ON (b.usuario_id = f.usuario_id)
		JOIN system_usuarios as g ON (a.usuario_id = g.usuario_id)
		WHERE
		d.descripcion||'-'||c.descripcion ILIKE '%".trim($buscador['nombre_farmacia'])."%'
		AND f.nombre ILIKE '%".trim($buscador['usuario'])."%'
		AND a.prefijo ILIKE '%".trim($buscador['prefijo'])."%'
		AND a.empresa_id = '".trim($datos['empresa_id'])."' ";
		$sql .= $filtro;
		$cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
		$this->ProcesarSqlConteo($cont,$offset);
		$sql .= " ORDER BY a.solicitud_prod_a_bod_ppal_id ASC,a.fecha_registro ASC,a.sw_despachado ASC ";
		$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
		/*$this->debug=true;*/
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
  
  




 /*
  * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
  * una Clase (laboratorio) que pertenece a un grupo
  */
function Listar_Transportadoras()
{
        //$this->debug=true;
	$sql=" SELECT
	transportadora_id,
	descripcion,
	sw_carropropio,
	CASE WHEN sw_carropropio = '1'
	THEN '(TRANSPORTE PROPIO)'
	ELSE '(TRANSPORTE EXTERNO)' END as carro
	FROM
	inv_transportadoras
	WHERE
	estado = '1'
	ORDER BY sw_carropropio;";
              
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
  


	function Despachar($buscador,$datos)
	{
	/*$this->debug=true;*/
	$sql  = "UPDATE inv_bodegas_movimiento_despachos_farmacias ";
	$sql .= " SET ";
	$sql .= " 	transportadora_id = '".trim($datos['transportadora_id'])."',
				numero_guia = '".trim($datos['numero_guia'])."',
				conductor = '".trim($datos['conductor'])."',
				neveras = '".trim($datos['neveras'])."',
				temperatura = '".trim($datos['temperatura'])."',
				numero_cajas = '".trim($datos['numero_cajas'])."',
				sw_despachado = '1',
				fecha_despacho = NOW(),
				usuario_id_despacha = '".UserGetUID()."'
				";
	$sql .= " WHERE
				empresa_id = '".trim($buscador['empresa_id'])."'
				AND prefijo = '".trim($buscador['prefijo'])."' 
				AND numero = '".trim($buscador['numero'])."' ";
	if(!$rst = $this->ConexionBaseDatos($sql)) 
	return false;
	else
	return true;

	$rst->Close();

	}
		
		
/*
* Saca un listado de SubClases(Moleculas) que han sido asignadas a 
* una Clase (laboratorio) que pertenece a un grupo
*/
function Listar_DespachosFisicosAFarmacia($datos,$buscador,$offset)
{
		/*$this->debug=true;*/
		if(!empty($buscador['solicitud_prod_a_bod_ppal_id']))
		$filtro = "	AND a.solicitud_prod_a_bod_ppal_id = '".trim($buscador['solicitud_prod_a_bod_ppal_id'])."'	";
		
		if(!empty($buscador['numero']))
		$filtro .= "	AND a.numero = '".trim($buscador['numero'])."'	";
		$sql = "SELECT
		a.empresa_id,
		a.prefijo,
		a.numero,
		a.farmacia_id,
		d.descripcion||'-'||c.descripcion as farmacia,
		f.nombre as usuario_pedido,
		g.nombre as usuario_cruce,
		h.nombre as usuario_despacha,
		a.solicitud_prod_a_bod_ppal_id,
		a.fecha_registro as fecha_cruce,
		b.fecha_registro as fecha_pedido,
		a.fecha_despacho as fecha_despacho,
		a.usuario_id,
		a.sw_despachado,
		a.neveras,
		a.temperatura,
		a.numero_cajas,
		a.transportadora_id,
		a.numero_guia,
		a.conductor,
		a.sw_recibido,
		a.numero_cajas_recibidas,
		a.usuario_id_despacha,
		a.usuario_id_recibe,
		CASE WHEN a.sw_recibido = '1'
		THEN 'RECIBIDO@ok.png@disabled'
		ELSE
		'INGRESAR@informacion.png@' END AS estado_recepcion
		FROM
		inv_bodegas_movimiento_despachos_farmacias as a
		JOIN solicitud_productos_a_bodega_principal as b ON (a.solicitud_prod_a_bod_ppal_id = b.solicitud_prod_a_bod_ppal_id)
		JOIN bodegas as c ON (b.farmacia_id = c.empresa_id)
		AND (b.centro_utilidad = c.centro_utilidad)
		AND (b.bodega = c.bodega)
		JOIN centros_utilidad as d ON (c.empresa_id = d.empresa_id)
		AND (c.centro_utilidad = d.centro_utilidad)
		JOIN empresas as e ON (d.empresa_id = e.empresa_id)
		JOIN system_usuarios as f ON (b.usuario_id = f.usuario_id)
		JOIN system_usuarios as g ON (a.usuario_id = g.usuario_id)
		LEFT JOIN system_usuarios as h ON (a.usuario_id_despacha = h.usuario_id)
		WHERE
		d.descripcion||'-'||c.descripcion ILIKE '%".trim($buscador['nombre_farmacia'])."%'
		AND f.nombre ILIKE '%".trim($buscador['usuario'])."%'
		AND a.prefijo ILIKE '%".trim($buscador['prefijo'])."%'
		AND a.farmacia_id = '".trim($datos['empresa_id'])."' 
		AND b.centro_utilidad = '".trim($datos['centro_utilidad'])."' 
		AND b.bodega = '".trim($datos['bodega'])."' 
		AND a.sw_despachado = '1' ";
		
		$sql .= $filtro;
		$cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
		$this->ProcesarSqlConteo($cont,$offset);
		$sql .= " ORDER BY a.solicitud_prod_a_bod_ppal_id ASC,a.fecha_registro ASC,a.sw_recibido ASC ";
		$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
		
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
	
	
	
	function Recibir($buscador,$datos)
	{
	/*$this->debug=true;*/
	$sql  = "UPDATE inv_bodegas_movimiento_despachos_farmacias ";
	$sql .= " SET ";
	$sql .= " 	numero_cajas_recibidas = '".trim($datos['numero_cajas'])."',
				sw_recibido = '1',
				fecha_recibido = NOW(),
				usuario_id_recibe = '".UserGetUID()."' ";
	$sql .= " WHERE
				empresa_id = '".trim($buscador['empresa_id'])."'
				AND prefijo = '".trim($buscador['prefijo'])."' 
				AND numero = '".trim($buscador['numero'])."' ";
	if(!$rst = $this->ConexionBaseDatos($sql)) 
	return false;
	else
	return true;

	$rst->Close();

	}
  
	}
	
?>