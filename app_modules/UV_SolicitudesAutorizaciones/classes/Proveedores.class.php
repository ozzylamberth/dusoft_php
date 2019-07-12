<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: Proveedores.class.php,v 1.9 2009/02/04 14:19:51 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : Proveedores
  * Clase encargada de hacer las consultas y actualizaciones de los proveedores
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.9 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Proveedores extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function Proveedores(){}
    /**
    * Funcion donde se obtienen los proveedores de cargos
    *
    * @param array $cargos Arreglo con los datos de los cargos abuscar
    * @param string $grupotipocargo Cadena con el grupo tipo cargo
    * @param array $filtros Arreglo de datos con los filtros
    * @param string $off Cadena que contiene el offset para la busqueda
    *
    * @return mixed
    */
    function ObtenerProveedores($cargos,$grupotipocargo,$paciente,$solicitud,$filtros,$off)
    {
      if(empty($cargos)) return '';
      
      $retorno = array();
      if(sizeof($cargos) > 1)
        $retorno = $this->ObtenerProveedoresVariosCargos($cargos,$grupotipocargo,$filtros,$off);
      else
        $retorno = $this->ObtenerProveedoresUnCargo($cargos,$grupotipocargo,$paciente,$solicitud,$filtros,$off);
      
      return $retorno;
    }
    /**
    * Funcion donde se obtienen los proveedores cuando se ha seleccionado varios cargos
    *
    * @return mixed
    */
    function ObtenerProveedoresVariosCargos($cargos,$grupotipocargo,$filtros,$off)
    {
      $sql  = "SELECT DISTINCT TP.codigo_proveedor_id, ";
      $sql .= "       TR.tipo_id_tercero,  ";
      $sql .= "       TR.nombre_tercero,  ";
      $sql .= "       TR.telefono,  ";
      $sql .= "       TR.direccion,  ";
      $sql .= "       TR.tercero_id, ";
      $sql .= "       TR.tipo_id_tercero ";
      $sql .= "FROM   tarifarios_equivalencias TE,";
      $sql .= "       tarifarios_detalle TD,";
      $sql .= "       cups CU,";
      $sql .= "       listas_precios_cargos LI,";
      $sql .= "       listas_precios_cargos_detalle LD,";
      $sql .= "       listas_precios_cargos_proveedores LP,";
      $sql .= "       terceros_proveedores TP, ";
      $sql .= "       terceros TR ";
      $sql .= "WHERE  TE.cargo_base = CU.cargo ";
      $sql .= "AND    TE.tarifario_id = TD.tarifario_id ";
      $sql .= "AND    TD.tarifario_id = LD.tarifario_id ";
      $sql .= "AND    TD.cargo = TE.cargo ";
      $sql .= "AND    TD.cargo = LD.cargo ";
      $sql .= "AND    LD.lista_codigo = LI.lista_codigo ";
      $sql .= "AND    LP.lista_codigo = LI.lista_codigo ";
      $sql .= "AND    LP.codigo_proveedor_id = TP.codigo_proveedor_id ";
      $sql .= "AND    LI.sw_estado = '1' ";
      //$sql .= "AND    (LD.fecha_final > NOW()::date OR LD.fecha_final IS NULL)  ";
      //$sql .= "AND    LD.fecha_inicial < NOW()::date  ";
      $sql .= "AND    CU.grupo_tipo_cargo = '".$grupotipocargo."' ";
      $sql .= "AND    TR.tercero_id = TP.tercero_id ";
      $sql .= "AND    TR.tipo_id_tercero = TP.tipo_id_tercero ";
      if($filtros['tipo_id_tercero'] && $filtros['tipo_id_tercero']!= '-1')
        $sql .= "AND     TR.tipo_id_tercero = '".$filtros['tipo_id_tercero']."'  ";
      
      if($filtros['tercero_id'])
        $sql .= "AND     TR.tercero_id = '".$filtros['tercero_id']."' ";
      
      if($filtros['nombre'] != "")
        $sql .= "AND     TR.nombre_tercero ILIKE '%".$filtros['nombre']."%' ";
        
      $dat = "";
      foreach($cargos as $key=> $crg)
        ($dat == "")? $dat .= "'".$crg."'": $dat .= ",'".$crg."'";
        
      $sql .= "AND    CU.cargo IN (".$dat.") ";
            
      $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
			$this->ProcesarSqlConteo($cont,$off);
      
      $sql .= "ORDER BY TR.nombre_tercero ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $retorno;
    }    
    /**
    *
    */
    function ObtenerProveedoresUnCargo($cargos,$grupotipocargo,$paciente,$solicitud,$filtros,$off)
    { 
      $sql  = "SELECT  TP.codigo_proveedor_id, ";
      $sql .= "        TR.tipo_id_tercero, ";
      $sql .= "        TR.nombre_tercero, ";
      $sql .= "        TR.telefono, ";
      $sql .= "        TR.direccion, ";
      $sql .= "        TR.tercero_id,"; 
      $sql .= "        TR.tipo_id_tercero,";
      $sql .= "        LD.valor,";
      $sql .= "        LD.cargo,";
      $sql .= "        LD.tarifario_id,";
      $sql .= "        LD.sw_pesos,";
      $sql .= "        LI.lista_codigo,";
      $sql .= "        LZ.porcentaje_cobertura ";
      $sql .= "FROM    tarifarios_equivalencias TE,"; 
      $sql .= "        tarifarios_detalle TD, ";
      $sql .= "        cups CU, ";
      $sql .= "        listas_precios_cargos LI, ";
      $sql .= "        listas_precios_cargos_detalle LD, ";
      $sql .= "        listas_precios_cargos_proveedores LP, ";
      $sql .= "        listas_precios_cargos_planes LZ,";
      $sql .= "        terceros_proveedores TP, ";
      $sql .= "        terceros TR ";
      $sql .= "WHERE   TE.cargo_base = CU.cargo ";
      $sql .= "AND     TE.tarifario_id = TD.tarifario_id "; 
      $sql .= "AND     TD.tarifario_id = LD.tarifario_id  ";
      $sql .= "AND     TD.cargo = TE.cargo ";
      $sql .= "AND     TD.cargo = LD.cargo ";
      $sql .= "AND     LP.codigo_proveedor_id = TP.codigo_proveedor_id "; 
      $sql .= "AND     TR.tercero_id = TP.tercero_id  ";
      $sql .= "AND     TR.tipo_id_tercero = TP.tipo_id_tercero ";
      $sql .= "AND     LD.lista_codigo = LI.lista_codigo ";
      $sql .= "AND     LP.lista_codigo = LI.lista_codigo ";
      $sql .= "AND     LZ.lista_codigo = LI.lista_codigo ";
      $sql .= "AND     LI.sw_estado = '1' ";
      $sql .= "AND     CU.grupo_tipo_cargo = '".$grupotipocargo."' ";
      $sql .= "AND     CU.cargo = '".$cargos[key($cargos)]."' ";
      $sql .= "AND     LZ.eps_tipo_afiliado_id = '".$paciente['tipo_afiliado_id']."' ";
      $sql .= "AND     LZ.plan_id = ".$paciente['plan_id']." ";
      
      if($filtros['tipo_id_tercero'] && $filtros['tipo_id_tercero']!= '-1')
        $sql .= "AND     TR.tipo_id_tercero = '".$filtros['tipo_id_tercero']."'  ";
      
      if($filtros['tercero_id'])
        $sql .= "AND     TR.tercero_id = '".$filtros['tercero_id']."' ";
      
      if($filtros['nombre'] != "")
        $sql .= "AND     TR.nombre_tercero ILIKE '%".$filtros['nombre']."%' ";
      
      //ObtenerValorCargo($plan,$tipo_afiliado,$cargo,$tarifario,$proveedor)
      
      $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
			$this->ProcesarSqlConteo($cont,$off);
      
      $sql .= "ORDER BY TR.nombre_tercero ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
        $valor_unidad = $this->ObtenerValorCargo($solicitud,$retorno[$rst->fields[0]]['cargo'],$retorno[$rst->fields[0]]['tarifario_id'],$retorno[$rst->fields[0]]['lista_codigo'],$paciente);
        
        if($valor_unidad)
          $retorno[$rst->fields[0]]['valor'] = $valor_unidad;
        else if($valor_unidad === 0)
          unset($retorno[$rst->fields[0]]);
          
				$rst->MoveNext();
			}
			$rst->Close();

			return $retorno;
    }
    /**
    * Funcion donde se obtienen los cargos asociados al proveedor seleccionado
    *
    * @param array $cargos Arreglo con los datos de los cargos abuscar
    * @param string $grupotipocargo Cadena con el grupo tipo cargo
    * @param array $proveedor Identificador del proveedor seleccionado
    *
    * @return mixed
    */
    function ObtenerCargosProveedores($cargos,$grupotipocargo,$proveedor,$paciente,$solicitud)
    {
      if(empty($cargos)) return '';
          
      $sql  = "SELECT LP.codigo_proveedor_id, ";
      $sql .= "       CU.cargo,  ";
      $sql .= "       CU.sw_cantidad,  ";
      $sql .= "       CU.descripcion AS descripcion_cups,  ";
      $sql .= "       TE.tarifario_id,  ";      
      $sql .= "       TD.cargo AS cargo_equivalente, ";
      $sql .= "       TD.descripcion AS descripcion_equivalente, ";
      $sql .= "       LD.sw_pesos, ";
      $sql .= "       LD.lista_codigo,  ";
      $sql .= "       LD.valor,  ";
      $sql .= "       LZ.porcentaje_cobertura ";
      $sql .= "FROM   tarifarios_equivalencias TE,";
      $sql .= "       tarifarios_detalle TD,";
      $sql .= "       cups CU,";
      $sql .= "       listas_precios_cargos LI,";
      $sql .= "       listas_precios_cargos_detalle LD,";
      $sql .= "       listas_precios_cargos_proveedores LP, ";
      $sql .= "       listas_precios_cargos_planes LZ ";
      $sql .= "WHERE  TE.cargo_base = CU.cargo ";
      $sql .= "AND    TE.tarifario_id = TD.tarifario_id ";
      $sql .= "AND    TD.tarifario_id = LD.tarifario_id ";
      $sql .= "AND    TD.cargo = TE.cargo ";
      $sql .= "AND    TD.cargo = LD.cargo ";
      $sql .= "AND    LD.lista_codigo = LI.lista_codigo ";
      $sql .= "AND    LP.lista_codigo = LI.lista_codigo ";
      $sql .= "AND    LP.codigo_proveedor_id = ".$proveedor." ";
      $sql .= "AND    LI.sw_estado = '1' ";
      $sql .= "AND    LZ.lista_codigo = LI.lista_codigo ";
      $sql .= "AND    (LD.fecha_final > NOW()::date OR LD.fecha_final IS NULL)  ";
      $sql .= "AND    CU.grupo_tipo_cargo = '".$grupotipocargo."' ";
      $sql .= "AND    LZ.eps_tipo_afiliado_id = '".$paciente['tipo_afiliado_id']."' ";
      $sql .= "AND    LZ.plan_id = ".$paciente['plan_id']." ";
      
      $dat = "";
      foreach($cargos as $key=> $crg)
        ($dat == "")? $dat .= "'".$crg."'": $dat .= ",'".$crg."'";
        
      $sql .= "AND    CU.cargo IN (".$dat.") ";
      $sql .= "ORDER BY CU.cargo ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
      $i = 0;
			while(!$rst->EOF)
			{
				$retorno[$rst->fields[1]][$i] = $rst->GetRowAssoc($ToUpper = false);
        
        $valor_unidad = $this->ObtenerValorCargo($solicitud,$retorno[$rst->fields[1]][$i]['cargo_equivalente'],$retorno[$rst->fields[1]][$i]['tarifario_id'],$retorno[$rst->fields[1]][$i]['lista_codigo'],$paciente);
        if($valor_unidad)
          $retorno[$rst->fields[1]][$i]['valor'] = $valor_unidad;
        
        $i++;
				$rst->MoveNext();
			}
			$rst->Close();

			return $retorno;
    }
    /**
    * Funcion donde se obtienen los proveedores de medicamentos
    *
    * @param array $productos Arreglo con los datos de los medicamentos a buscar
    * @param array $filtros Arreglo de datos con los filtros
    * @param string $off Cadena que contiene el offset para la busqueda
    *
    * @return mixed
    */
    function ObtenerProveedoresMedicamentos($productos,$filtros,$off)
    {
      if(empty($productos)) return '';
      
      $retorno = array();
      if(sizeof($productos) == 1)
        $retorno = $this->ObtenerProveedoresUnMedicamentos($productos,$filtros,$off);
      else
        $retorno = $this->ObtenerProveedoresVariosMedicamentos($productos,$filtros,$off);
      
      return $retorno;
    }
    /**
    *
    */
    function ObtenerProveedoresVariosMedicamentos($productos,$filtros,$off)
    {
      $sql  = "SELECT DISTINCT TP.codigo_proveedor_id, ";
      $sql .= "       TR.tipo_id_tercero,  ";
      $sql .= "       TR.nombre_tercero,  ";
      $sql .= "       TR.telefono,  ";
      $sql .= "       TR.direccion,  ";
      $sql .= "       TR.tercero_id, ";
      $sql .= "       TR.tipo_id_tercero ";
      $sql .= "FROM   inventarios_productos IM, ";
      $sql .= "       listas_precios LI,";
      $sql .= "       listas_precios_detalle LD,";
      $sql .= "       listas_precios_medicamentos_proveedores LP,";
      $sql .= "       terceros_proveedores TP, ";
      $sql .= "       terceros TR ";
      $sql .= "WHERE  IM.codigo_producto = LD.codigo_producto ";
      $sql .= "AND    LD.codigo_lista = LI.codigo_lista ";
      $sql .= "AND    LP.codigo_lista = LI.codigo_lista ";
      $sql .= "AND    LP.codigo_proveedor_id = TP.codigo_proveedor_id ";
      //$sql .= "AND    (LD.fecha_final > NOW()::date OR LD.fecha_final IS NULL)  ";
      $sql .= "AND    TR.tercero_id = TP.tercero_id ";
      $sql .= "AND    TR.tipo_id_tercero = TP.tipo_id_tercero ";
      if($filtros['tipo_id_tercero'] && $filtros['tipo_id_tercero']!= '-1')
        $sql .= "AND     TR.tipo_id_tercero = '".$filtros['tipo_id_tercero']."'  ";
      
      if($filtros['tercero_id'])
        $sql .= "AND     TR.tercero_id = '".$filtros['tercero_id']."' ";
      
      if($filtros['nombre'] != "")
        $sql .= "AND     TR.nombre_tercero ILIKE '%".$filtros['nombre']."%' ";
        
      $dat = "";
      foreach($productos as $key=> $dtl)
        ($dat == "")? $dat .= "'".$dtl."'": $dat .= ",'".$dtl."'";
        
      $sql .= "AND    IM.codigo_producto IN (".$dat.") ";
      
      $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
			$this->ProcesarSqlConteo($cont,$off);
      
      $sql .= "ORDER BY TR.nombre_tercero ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $retorno;
    }
    /**
    *
    */
    function ObtenerProveedoresUnMedicamentos($productos,$filtros,$off)
    {
  
      $sql  = "SELECT TP.codigo_proveedor_id, ";
      $sql .= "       TR.tipo_id_tercero,  ";
      $sql .= "       TR.nombre_tercero,  ";
      $sql .= "       TR.telefono,  ";
      $sql .= "       TR.direccion,  ";
      $sql .= "       TR.tercero_id, ";
      $sql .= "       TR.tipo_id_tercero, ";
      $sql .= "       LD.precio ";
      $sql .= "FROM   inventarios_productos IM, ";
      $sql .= "       listas_precios LI,";
      $sql .= "       listas_precios_detalle LD,";
      $sql .= "       listas_precios_medicamentos_proveedores LP,";
      $sql .= "       terceros_proveedores TP, ";
      $sql .= "       terceros TR ";
      $sql .= "WHERE  IM.codigo_producto = LD.codigo_producto ";
      $sql .= "AND    LD.codigo_lista = LI.codigo_lista ";
      $sql .= "AND    LP.codigo_lista = LI.codigo_lista ";
      $sql .= "AND    LP.codigo_proveedor_id = TP.codigo_proveedor_id ";
      //$sql .= "AND    (LD.fecha_final > NOW()::date OR LD.fecha_final IS NULL)  ";
      $sql .= "AND    TR.tercero_id = TP.tercero_id ";
      $sql .= "AND    TR.tipo_id_tercero = TP.tipo_id_tercero ";
      if($filtros['tipo_id_tercero'] && $filtros['tipo_id_tercero']!= '-1')
        $sql .= "AND     TR.tipo_id_tercero = '".$filtros['tipo_id_tercero']."'  ";
      
      if($filtros['tercero_id'])
        $sql .= "AND     TR.tercero_id = '".$filtros['tercero_id']."' ";
      
      if($filtros['nombre'] != "")
        $sql .= "AND     TR.nombre_tercero ILIKE '%".$filtros['nombre']."%' ";
        
      $sql .= "AND    IM.codigo_producto = '".$productos[key($productos)]."' ";
      
      $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
			$this->ProcesarSqlConteo($cont,$off);
      
      $sql .= "ORDER BY TR.nombre_tercero ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $retorno;
    }
    /**
    * Funcion donde se obtienen los medicamentos asociados al proveedor
    *
    * @param array $productos Arreglo con los datos de los productos a buscar
    * @param string $proveedor Identificador del proveedor seleccionado
    *
    * @return mixed
    */
    function ObtenerMedicamentosProveedores($productos,$proveedor)
    {
      if(empty($productos)) return '';
      
      $sql  = "SELECT LP.codigo_proveedor_id, ";
      $sql .= "       IM.codigo_producto,  ";
      $sql .= "       IM.descripcion, ";
      $sql .= "       LD.codigo_lista,  ";
      $sql .= "       LD.precio  ";
      $sql .= "FROM   inventarios_productos IM, ";
      $sql .= "       medicamentos ME,";
      $sql .= "       listas_precios LI,";
      $sql .= "       listas_precios_detalle LD,";
      $sql .= "       listas_precios_medicamentos_proveedores LP,";
      $sql .= "       terceros_proveedores TP, ";
      $sql .= "       terceros TR ";
      $sql .= "WHERE  IM.codigo_producto = LD.codigo_producto ";
      $sql .= "AND    IM.codigo_producto = ME.codigo_medicamento ";
      $sql .= "AND    LD.codigo_lista = LI.codigo_lista ";
      $sql .= "AND    LP.codigo_lista = LI.codigo_lista ";
      $sql .= "AND    LP.codigo_proveedor_id = TP.codigo_proveedor_id ";
      //$sql .= "AND    (LD.fecha_final > NOW()::date OR LD.fecha_final IS NULL)  ";
      $sql .= "AND    TR.tercero_id = TP.tercero_id ";
      $sql .= "AND    TR.tipo_id_tercero = TP.tipo_id_tercero ";
      $dat = "";
      foreach($productos as $key=> $dtl)
        ($dat == "")? $dat .= "'".$dtl."'": $dat .= ",'".$dtl."'";
        
      $sql .= "AND    IM.codigo_producto IN (".$dat.") ";
      $sql .= "ORDER BY IM.codigo_producto ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $retorno;
    }
    /**
    * Funcion donde se obtienen los proveedores de conceptos
    *
    * @param array $filtros Arreglo de datos con los filtros
    * @param string $off Cadena que contiene el offset para la busqueda
    *
    * @return mixed
    */
    function ObtenerProveedoresConceptos($filtros,$off)
    {
      $sql  = "SELECT TP.codigo_proveedor_id, ";
      $sql .= "       TE.tipo_id_tercero,  ";
      $sql .= "       TE.nombre_tercero,  ";
      $sql .= "       TE.telefono,  ";
      $sql .= "       TE.direccion,  ";
      $sql .= "       TE.tercero_id, ";
      $sql .= "       TE.tipo_id_tercero ";
      $sql .= "FROM   terceros TE, ";
      $sql .= "       terceros_proveedores TP, ";
      $sql .= "       terceros_proveedores_servicios_salud TS ";
      $sql .= "WHERE  TE.tercero_id = TP.tercero_id ";
      $sql .= "AND    TE.tipo_id_tercero = TP.tipo_id_tercero ";
      $sql .= "AND    TE.tercero_id = TS.tercero_id ";
      $sql .= "AND    TE.tipo_id_tercero = TS.tipo_id_tercero ";
      $sql .= "AND    TS.estado = '1' ";
      if($filtros['tipo_id_tercero'] && $filtros['tipo_id_tercero']!= '-1')
        $sql .= "AND     TE.tipo_id_tercero = '".$filtros['tipo_id_tercero']."'  ";
      
      if($filtros['tercero_id'])
        $sql .= "AND     TE.tercero_id = '".$filtros['tercero_id']."' ";
      
      if($filtros['nombre'] != "")
        $sql .= "AND     TE.nombre_tercero ILIKE '%".$filtros['nombre']."%' ";
        
      $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
			$this->ProcesarSqlConteo($cont,$off);
      
      $sql .= "ORDER BY TE.nombre_tercero ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
      
      $datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
    }
    /**
		* Funcion domde se obtienen los tipos de identificacion de terceros 
		* 
		* @return mixed 
		*/
		function ObtenerTipoIdTerceros()
		{
			$sql  = "SELECT tipo_id_tercero, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   tipo_id_terceros ";
      $sql .= "ORDER BY descripcion ";
			
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
    * Funcion donde se obtiene el valor del cargo segun la cobertura, para el plan
    * y el tipo de afiliado
    *
    * @param integer $plan identificador del plan
    * @param string $cargo Identificador del cargo 
    * @param string $tarifario Identificador del tarifario
    *
    * @return array
    */
    function ObtenerValorCargo($solicitud,$cargo,$tarifario,$lista_codigo,$paciente)
    {
      $sql  = "SELECT  LD.valor ";
      $sql .= "FROM    tarifarios_equivalencias TE,";
      $sql .= "        tarifarios_detalle TD, ";
      $sql .= "        cups CU, ";
      $sql .= "        listas_precios_cargos_detalle LD, ";
      $sql .= "        listas_precios_cargos_planes LZ,";
      $sql .= "        eps_solicitudes_ordenes_cargos EC ";
      $sql .= "WHERE   TE.cargo_base = CU.cargo ";
      $sql .= "AND     TE.tarifario_id = TD.tarifario_id "; 
      $sql .= "AND     TD.cargo = TE.cargo ";
      $sql .= "AND     TD.tarifario_id = LD.tarifario_id  ";
      $sql .= "AND     TD.cargo = LD.cargo ";
      $sql .= "AND     LD.lista_codigo = LZ.lista_codigo ";
      $sql .= "AND     LD.lista_codigo = ".$lista_codigo." ";
      $sql .= "AND     CU.cargo = EC.cargo_qx ";
      $sql .= "AND     EC.numero_solicitud_orden 	= ".$solicitud." ";
      $sql .= "AND     LZ.eps_tipo_afiliado_id = '".$paciente['tipo_afiliado_id']."' ";
      $sql .= "AND     LZ.plan_id = ".$paciente['plan_id']." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;

      $datos = array();
			if (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      $valor_c = $datos['valor'];
      
      if (!$valor_c) return false;
      
      $sql  = "SELECT TU.valor ";
      $sql .= "FROM   ( ";  
      $sql .= "        SELECT tarifario_id,  ";
      $sql .= "               dc_valor*".$valor_c." AS valor,  ";
      $sql .= "               dc_cargo AS cargo ";
      $sql .= "        FROM   tarifarios_uvrs  ";
      $sql .= "        WHERE  tarifario_id = '".$tarifario."' ";
      $sql .= "        AND    dc_cargo = '".$cargo."' ";
      $sql .= "        UNION ALL ";
      $sql .= "        SELECT tarifario_id,  ";
      $sql .= "               da_valor*".$valor_c." AS valor,  ";
      $sql .= "               da_cargo AS cargo ";
      $sql .= "        FROM   tarifarios_uvrs  ";
      $sql .= "        WHERE  tarifario_id = '".$tarifario."' ";
      $sql .= "        AND    da_cargo = '".$cargo."' ";
      $sql .= "        UNION ALL ";
      $sql .= "        SELECT tarifario_id,  ";
      $sql .= "               dy_valor*".$valor_c." AS valor,  ";
      $sql .= "               dy_cargo AS cargo ";
      $sql .= "        FROM   tarifarios_uvrs  ";
      $sql .= "        WHERE  tarifario_id = '".$tarifario."' ";
      $sql .= "        AND    dy_cargo = '".$cargo."' ";
      $sql .= "        UNION ALL ";
      $sql .= "        SELECT tarifario_id,  ";
      $sql .= "               ds_valor AS valor, ";
      $sql .= "               ds_cargo AS cargo ";
      $sql .= "        FROM   tarifarios_uvrs_ds_rangos ";
      $sql .= "        WHERE  tarifario_id =  '".$tarifario."' ";
      $sql .= "        AND    ds_cargo = '".$cargo."' ";
      $sql .= "        AND    uvrs_min <= ".$valor_c." ";
      $sql .= " 	     AND    uvrs_max >= ".$valor_c." ";
      $sql .= "        UNION ALL ";
      $sql .= "        SELECT tarifario_id,  ";
      $sql .= "               dm_valor AS valor , ";
      $sql .= "               dm_cargo AS cargo ";
      $sql .= "        FROM   tarifarios_uvrs_dm_rangos ";
      $sql .= "        WHERE  tarifario_id = '".$tarifario."' ";
      $sql .= "        AND    dm_cargo = '".$cargo."' ";
      $sql .= "        AND    uvrs_min <= ".$valor_c." ";
      $sql .= " 	     AND    uvrs_max >= ".$valor_c." ";
      $sql .= "      ) AS TU ";
      //$sql .= "      plan_tarifario PL  ";
      //$sql .= "WHERE PL.tarifario_id = TU.tarifario_id  ";
      //$sql .= "AND   PL.plan_id = ".$plan." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;

      $datos = array();
			if (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
      
      
      return $datos['valor'];
    }
  }
 ?>