<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: ListaPrecios.class.php,v 1.5 2008/08/15 16:10:21 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : ListaPrecios
  * Clase donde se hace el maejo logico de las listas de precios
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.5 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ListaPrecios extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function ListaPrecios(){}
     /**
    * Funcion donde se validan los permisos de un usuario sobre el modulo
    * 
    * @return mixed
    */
    function ObtenerPermisos()
    {
      $sql  = "SELECT	EM.empresa_id AS empresa, ";
			$sql .= "				EM.razon_social AS razon_social ";
			$sql .= "FROM	  userpermisos_lista_precios US,";
      $sql .= "       empresas EM ";
			$sql .= "WHERE	US.usuario_id = ".UserGetUID()." ";
			$sql .= "AND    US.empresa_id = EM.empresa_id ";
			$sql .= "AND    US.sw_activo = '1' ";
			
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
    * Funcion donde se obtiene los grupos tarifarios 
    *
    * @param string $tarifario Identificador del tarifario
    *
    * @return mixed
    */
    function ObtenerGruposTarifarios($tarifario)
    {      
      $sql  = "SELECT GT.grupo_tarifario_id,";
      $sql .= " 	    GT.grupo_tarifario_descripcion ";
      $sql .= "FROM   grupos_tarifarios GT, ";
      $sql .= "       (";
      $sql .= "         SELECT DISTINCT grupo_tarifario_id ";
      $sql .= "         FROM   tarifarios_detalle ";
      $sql .= "         WHERE  tarifario_id = '".$tarifario."' ";
      $sql .= "       ) AS TD ";
      
      $sql .= "WHERE  TD.grupo_tarifario_id = GT.grupo_tarifario_id ";
      $sql .= "ORDER BY GT.grupo_tarifario_descripcion ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se obtiene los tipos tarifarios 
    *
    * @return mixed
    */
    function ObtenerTiposTarifarios()
    {      
      $sql  = "SELECT tipo_tarifario_id,";
      $sql .= " 	    descripcion ";
      $sql .= "FROM   tipos_tarifarios ";
      $sql .= "ORDER BY descripcion ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se obtiene los subgrupos tarifarios
    *
    * @param string $grupotarifario Identificador del grupo tarifario
    * @param string $tarifario Identificador del tarifario
    *
    * @return mixed
    */
    function ObtenerSubgruposTarifarios($grupotarifario,$tarifario)
    {      
      $sql  = "SELECT ST.subgrupo_tarifario_id,";
      $sql .= " 	    ST.subgrupo_tarifario_descripcion  ";
      $sql .= "FROM   subgrupos_tarifarios ST, ";
      $sql .= "       (";
      $sql .= "         SELECT DISTINCT subgrupo_tarifario_id ";
      $sql .= "         FROM   tarifarios_detalle ";
      $sql .= "         WHERE  tarifario_id = '".$tarifario."' ";
      $sql .= "         AND    grupo_tarifario_id = '".$grupotarifario."' ";
      $sql .= "       ) AS TD ";
      $sql .= "WHERE  ST.grupo_tarifario_id = '".$grupotarifario."' ";
      $sql .= "AND    ST.subgrupo_tarifario_id = TD.subgrupo_tarifario_id ";
      $sql .= "ORDER BY subgrupo_tarifario_descripcion ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se obtienen los tarifarios
    *
    * @param string $tipo_tarifario Identificador del tipo tarifario
    *
    * @return mixed
    */
    function ObtenerTarifarios($tipo_tarifario)
    {      
      $sql  = "SELECT tarifario_id,";
      $sql .= " 	    descripcion ";
      $sql .= "FROM   tarifarios ";
      $sql .= "WHERE  tipo_tarifario_id = '".$tipo_tarifario."' ";
      $sql .= "ORDER BY descripcion ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se crea la lista de precios
    *
    * @param array $datos Arreglo de datos con la informacion de la lista de precios
    *
    * @return mixed
    */
    function IngresarListaPrecios($datos)
    {
      $indice = array();
      $sql = "SELECT NEXTVAL('listas_precios_cargos_lista_codigo_seq') AS indice";
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
            
      if (!$rst->EOF)
      {
        $indice = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      $sql  = "INSERT INTO listas_precios_cargos (";
      $sql .= "   lista_codigo,";
      $sql .= " 	descripcion_lista,"; 	
      $sql .= "   observacion ,";	
      $sql .= "   fecha_inicio_lista ,";	
      $sql .= "   fecha_fin_lista ,";	
      $sql .= "   usuario_id ";
      $sql .= "   ) ";
      $sql .= "VALUES ( ";
      $sql .= "    ".$indice['indice'].","; 
      $sql .= "   '".strtoupper($datos['descripcion_lista'])."', ";
      $sql .= "   '".$datos['observacion']."', ";
      $sql .= "   '".$this->DividirFecha($datos['fecha_inicio'])."', ";
      $sql .= "   '".$this->DividirFecha($datos['fecha_fin'])."', ";
      $sql .= "    ".UserGetUID()." ";
      $sql .= "); ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
      {
        $sql = "SELECT setval('eps_solicitudes_ordenes_numero_solicitud_orden_seq',".($indice['indice']-1).") ";
        $this->ConexionBaseDatos($sql);
        return false;
      }
      return $indice['indice'];
    }
    /**
    * Funcion donde se obtiene la infornacion de la las listas de precios
    * creadas
    *
    * @param array $filtros Arreglo de datos con los filtros
    * @param string $off Cadena que contiene el offset para la busqueda
    *
    * @return mixed
    */
    function ObtenerListas($filtros,$off)
    {
      $sql  = "SELECT DISTINCT LI.lista_codigo,";
      $sql .= " 	    LI.descripcion_lista,";
      $sql .= " 	    LI.sw_estado,";
      $sql .= " 	    LI.observacion, ";
      $sql .= "       TO_CHAR(LI.fecha_inicio_lista,'DD/MM/YYYY') AS fecha_inicio_lista ,";	
      $sql .= "       TO_CHAR(LI.fecha_fin_lista,'DD/MM/YYYY') AS fecha_fin_lista,";	
      $sql .= " 	    COALESCE(LD.lista_codigo,0) AS detalle ";
      $sql .= "FROM   listas_precios_cargos LI LEFT JOIN ";
      $sql .= "       listas_precios_cargos_detalle LD ";
      $sql .= "       ON ( LD.lista_codigo = LI.lista_codigo) ";
      $sql .= "WHERE  TRUE ";
      if($filtros['lista_codigo'])
        $sql .= "AND     LI.lista_codigo = '".$filtros['lista_codigo']."'  ";
            
      if($filtros['descripcion_lista'] != "")
        $sql .= "AND     LI.descripcion_lista ILIKE '%".$filtros['descripcion_lista']."%' ";
      
      $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
			$this->ProcesarSqlConteo($cont,$off);
      
      $sql .= "ORDER BY LI.lista_codigo ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se obtiene la informacion de los cargos para ser adicionados a una lista de precios
    *
    * @param array $datos Arreglo de datos con los filtros de busqueda
    *
    * @return mixed
    */
    function ObtenerCargos($datos)
    {
      $sql  = "SELECT TD.tarifario_id,";
      $sql .= "       TD.cargo,";
      $sql .= " 	    TD.descripcion,";
      $sql .= " 	    TD.precio, ";
      //$sql .= " 	    TE.cargo_base, ";
      $sql .= " 	    COALESCE(LD.valor,0) AS nuevo_precio ";
      //$sql .= "FROM   tarifarios_equivalencias TE,";
      $sql .= "FROM   tarifarios_detalle TD LEFT JOIN ";
      $sql .= "       listas_precios_cargos_detalle LD ";
      $sql .= "       ON( LD.lista_codigo = ".$datos['lista_codigo']." AND ";
      $sql .= "           LD.tarifario_id = TD.tarifario_id AND ";
      $sql .= "           LD.cargo = TD.cargo) ";
      
      $sql .= "WHERE 	TD.grupo_tarifario_id = '".$datos['grupo_tarifario_id']."' ";
      $sql .= "AND 	  TD.subgrupo_tarifario_id = '".$datos['subgrupo_tarifario_id']."' ";
      $sql .= "AND 	  TD.tarifario_id = '".$datos['tarifario_id']."' ";
      //$sql .= "AND    TE.tarifario_id = TD.tarifario_id ";
      //$sql .= "AND    TE.cargo = TD.cargo ";

      $sql .= "AND 	  LD.lista_codigo IS NULL  ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funciuon donde se obtiene los cargos que hacen parte de una lista
    *
    * @param int $lista Identificador de una lista
    * @param array $datos Arreglo de datos con los filtros de busqueda
    *
    * @return mixed
    */
    function ObtenerCargosLista($lista,$datos,$off)
    {
      $sql  = "SELECT TA.tarifario_id,";
      $sql .= "       TA.descripcion AS descripcion_tarifario,";
      $sql .= "       CU.descripcion AS descripcion_cups,";
      $sql .= "       TD.cargo,";
      $sql .= " 	    TD.descripcion,";
      $sql .= " 	    TD.precio, ";
      $sql .= " 	    LD.valor ";
      $sql .= "FROM   tarifarios_detalle TD, ";
      $sql .= "       tarifarios_equivalencias TE, ";
      $sql .= "       cups CU, ";
      $sql .= "       tarifarios TA, ";
      $sql .= "       listas_precios_cargos_detalle LD ";
      $sql .= "WHERE 	LD.lista_codigo = ".$lista." ";
      $sql .= "AND    LD.tarifario_id = TD.tarifario_id ";
      $sql .= "AND    LD.cargo = TD.cargo ";      
      $sql .= "AND    TE.tarifario_id = TD.tarifario_id ";
      $sql .= "AND    TE.cargo = TD.cargo ";
      $sql .= "AND    CU.cargo = TE.cargo_base ";
      $sql .= "AND    TA.tarifario_id = TD.tarifario_id ";
			
      if($datos['cargo']) 
        $sql .= "AND    TD.cargo = '".$datos['cargo']."' ";
      
      if($datos['cargo_cups']) 
        $sql .= "AND    TD.cargo = '".$datos['cargo_cups']."' ";
      
      if($datos['descripcion_cargo'])
        $sql .= "AND    CU.descripcion ILIKE '%".$datos['descripcion_cargo']."%' ";

      if($datos['descripcion_cups'])
        $sql .= "AND    CU.descripcion ILIKE '%".$datos['descripcion_cups']."%' ";
      
      $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
			$this->ProcesarSqlConteo($cont,$off);
      
      $sql .= "ORDER BY TD.tarifario_id,TD.cargo ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]." ".$rst->fields[1]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $datos;
    }
    /**
    * Funciuon donde se vinculan los cargos con la lista
    *
    * @param array $datos Arreglo de datos con la informacion de los cargos
    * @param int $lista_codigo Identificador de una lista
    * @param int $tarifario Identificador del tarifario
    *
    * @return boolean
    */
    function RegistrarCargosLista($datos,$lista_codigo,$tarifario)
    {
      foreach($datos as $key => $dtl)
      {
        if($dtl['cargo'])
        {
          $sql  = "INSERT INTO listas_precios_cargos_detalle (";
          $sql .= "     lista_codigo,"; 	
          $sql .= "     tarifario_id ,";
          $sql .= "     cargo,	";
          $sql .= "     valor, ";
          $sql .= "     fecha_inicial ";
          $sql .= "     ) ";
          $sql .= "VALUES (";
          $sql .= "      ".$lista_codigo.",";
          $sql .= "     '".$tarifario."',";
          $sql .= "     '".$dtl['cargo']."',";
          $sql .= "      ".($dtl['precio'] + ($dtl['precio'] * $dtl['porcentaje']/100)).",";
          $sql .= "      NOW() ";
          $sql .= "     ); ";
          
          $sql .= "UPDATE listas_precios_cargos ";
          $sql .= "SET    usuario_modificacion = ".UserGetUID().", ";
          $sql .= " 	    fecha_modificacion = NOW() ";
          $sql .= "WHERE  lista_codigo = ".$lista_codigo."; ";
          
          if(!$rst = $this->ConexionBaseDatos($sql)) return false;
        }
      }
      return true;
    }
    /**
    * Funcion donde se vincula un proveedor con una lista
    *
    * @param int $datso arreglo de datos con a informacion a ingresar
    * @param int $lista_codigo Identificador de una lista
    *
    * @return boolean
    */
    function VincularProveedor($datos,$lista_codigo)
    {
      (!$datos['numero_contrato'])? $datos['numero_contrato'] = "NULL":$datos['numero_contrato'] = "'".$datos['numero_contrato']."'"; 
      $sql  = "INSERT INTO listas_precios_cargos_proveedores (";
      $sql .= "     lista_codigo,"; 	
      $sql .= "     codigo_proveedor_id, ";
      $sql .= "     numero_contrato ";
      $sql .= "     ) ";
      $sql .= "VALUES (";
      $sql .= "      ".$lista_codigo.",";
      $sql .= "      ".$datos['codigo_proveedor'].", ";
      $sql .= "      ".$datos['numero_contrato']." ";
      $sql .= "     ); ";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      return true;
    }
    /**
    * Funcion donde se desvincula un proveedor con una lista
    *
    * @param int $codigo_proveedor Identificador del proveedor
    * @param int $lista_codigo Identificador de una lista
    *
    * @return boolean
    */
    function DesvincularProveedor($codigo_proveedor,$lista_codigo)
    {
      $sql  = "DELETE FROM listas_precios_cargos_proveedores ";
      $sql .= "WHERE lista_codigo = ".$lista_codigo." "; 	
      $sql .= "AND    codigo_proveedor_id = ".$codigo_proveedor." ";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      return true;
    }
    /**
    * Funcion donde se actualiza el valor de un cargo en la lista
    *
    * @param int $lista_codigo Identificador de una lista
    * @param string $tarifario_id Identificador del tarifario
    * @param string $cargo Identificador del cargo
    * @param float $precio Precio del cargo
    * @param float $porcentaje Porcentaje de modificacion
    *
    * @return boolean
    */
    function ActualizarValor($lista_codigo,$tarifario_id,$cargo,$precio,$porcentaje)
    {
      $sql  = "UPDATE listas_precios_cargos_detalle ";
      $sql .= "SET    valor = ".($precio + ($precio * $porcentaje/100))." ";
      $sql .= "WHERE  lista_codigo = ".$lista_codigo." "; 	
      $sql .= "AND    tarifario_id = '".$tarifario_id."' ";
      $sql .= "AND    cargo = '".$cargo."'	";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return true;
    }
    /**
    * Funcion donde se elimina un cargo de la lista
    *
    * @param array $datos Arreglo de datos con la informacion de los cargoa que seran eliminados
    * @param int $lista_codigo Identificador de la lista de precios
    * 
    * @return boolean
    */
    function EliminarCargosLista($datos,$lista_codigo)
    {
      foreach($datos as $key => $cargos)
      {
        foreach($cargos as $key1 => $dtl)
        {
          $sql  = "DELETE FROM listas_precios_cargos_detalle ";
          $sql .= "WHERE  lista_codigo = ".$lista_codigo." "; 	
          $sql .= "AND    tarifario_id = '".$key."' ";
          $sql .= "AND    cargo = '".$key1."'	";
          
          if(!$rst = $this->ConexionBaseDatos($sql)) return false;
        }
      }
      return true;
    }    
    /**
    * Funcion para el cambio de estado de la lista
    *
    * @param array $datos Arreglo de datos con la informacion para el cambio de estado
    *
    * @return boolean
    */
    function CambiarEstadoLista($datos)
    {
      $sql  = "UPDATE listas_precios_cargos ";
      $sql .= "SET    sw_estado = '".$datos['estado']."', "; 	
      $sql .= " 	    usuario_modificacion = ".UserGetUID().", ";
      $sql .= " 	    fecha_modificacion = NOW() ";      
      $sql .= "WHERE  lista_codigo = ".$datos['lista_codigo']." "; 	
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return true;
    }
    /**
    * Funcion donde se obtienen los proveedores que ya han sido asociados a una lista
    * determinada
    *
    * @param int $lista Identificador de la lista
    *
    * @return mixed
    */
    function ObtenerProveedoresLista($lista)
    { 
      $sql  = "SELECT TP.codigo_proveedor_id, ";
      $sql .= "       TR.tipo_id_tercero,  ";
      $sql .= "       TR.tercero_id, ";
      $sql .= "       TR.nombre_tercero,  ";
      $sql .= "       TR.telefono,  ";
      $sql .= "       TR.direccion  ";
      $sql .= "FROM   terceros_proveedores TP, ";
      $sql .= "       terceros TR,";
      $sql .= "       listas_precios_cargos_proveedores LP ";
      $sql .= "WHERE  TR.tercero_id = TP.tercero_id ";
      $sql .= "AND    TR.tipo_id_tercero = TP.tipo_id_tercero ";
      $sql .= "AND    TP.codigo_proveedor_id = LP.codigo_proveedor_id ";
      $sql .= "AND    LP.lista_codigo = ".$lista." ";
      $sql .= "ORDER BY TR.nombre_tercero ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $retorno;
    }
    /**
    * Funcion donde se obtienen los proveedores que aun no han sido asociados a una lista
    *
    * @param int $lista Identificador de la lista
    *
    * @return mixed
    */
    function ObtenerProveedores($lista)
    {      
      $sql  = "SELECT TP.codigo_proveedor_id, ";
      $sql .= "       TR.nombre_tercero  ";
      $sql .= "FROM   terceros_proveedores TP LEFT JOIN ";
      $sql .= "       listas_precios_cargos_proveedores LP ";
      $sql .= "       ON ( TP.codigo_proveedor_id = LP.codigo_proveedor_id AND ";
      $sql .= "            LP.lista_codigo = ".$lista."),  ";
      $sql .= "       terceros TR ";
      $sql .= "WHERE  TR.tercero_id = TP.tercero_id ";
      $sql .= "AND    TR.tipo_id_tercero = TP.tipo_id_tercero ";
      $sql .= "AND    TP.estado = '1' ";
      $sql .= "AND    LP.lista_codigo IS NULL ";
      $sql .= "ORDER BY TR.nombre_tercero ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			return $retorno;
    } 
    /**
    * Funcion donde se actualiza la informacion de una lista determinada
    *
    * @param array $datos Arreglo con la informacion de la lista a modificar
    *
    * @return boolean
    */
    function ActualizarInformacion($datos)
    {
      $sql  = "UPDATE listas_precios_cargos ";
      $sql .= "SET    descripcion_lista = '".strtoupper($datos['descripcion_lista'])."', ";
      $sql .= " 	    observacion = '".$datos['observacion']."', ";
      $sql .= " 	    usuario_modificacion = ".UserGetUID().", ";
      //$sql .= "       fecha_inicio_lista = '".$datos['fecha_inicio']."'::date, ";
      $sql .= "       fecha_fin_lista = '".$this->DividirFecha($datos['fecha_fin'])."', ";
      $sql .= " 	    fecha_modificacion = NOW() ";
      $sql .= "WHERE  lista_codigo = ".$datos['lista_codigo']." "; 	
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      return true;
    }
    /**
    *
    */
    function ObtenerPlanes()
    {
      $sql  = "SELECT  plan_id, ";
      $sql .= "        plan_descripcion, ";
      $sql .= "        TO_CHAR(fecha_inicio,'DD/MM/YYYY') AS fecha_inicio, ";
      $sql .= "        TO_CHAR(fecha_final,'DD/MM/YYYY') AS fecha_fin ";
      $sql .= "FROM    planes ";
      $sql .= "WHERE 	fecha_final >= NOW() ";
			$sql .= "AND 		estado = '1' ";
			$sql .= "AND 		fecha_inicio <= NOW() "; 
      $sql .= "ORDER BY plan_descripcion ";
      
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
    /**
    * Obtiene los tipos de afiliados registrados en la base de datos
    *
    * @return array
    */
    function ObtenerTiposAfiliados()
    {
        $sql  = "SELECT eps_tipo_afiliado_id,";
        $sql .= "       descripcion_eps_tipo_afiliado ";
        $sql .= "FROM   eps_tipos_afiliados ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    *
    */
    function IngresarPorcentajeCobertura($form,$plan_id,$lista)
    {
      $this->ConexionTransaccion();
      
      foreach($form['tipo_afiliado'] as $key => $dtl)
      {
        $sql  = "INSERT INTO listas_precios_cargos_planes ";
        $sql .= "   ( lista_codigo,";
        $sql .= " 	  plan_id,";
        $sql .= " 	  eps_tipo_afiliado_id,";
        $sql .= " 	  porcentaje_cobertura ";
        $sql .= "   ) ";
        $sql .= "VALUES ";
        $sql .= "   ( ";
        $sql .= "     ".$lista.", ";
        $sql .= "     ".$plan_id.", ";
        $sql .= "     '".$key."', ";
        $sql .= "     ".$dtl."  ";
        $sql .= "   ) ";
        
        if(!$rst = $this->ConexionTransaccion($sql))  return false;
      }
      
      $this->Commit();
      
      return true;
    }      
    /**
    *
    */
    function ActualizarPorcentajeCobertura($form,$plan_id,$lista)
    {
      $this->ConexionTransaccion();
      
      foreach($form['tipo_afiliado'] as $key => $dtl)
      {
        $sql  = "UPDATE listas_precios_cargos_planes ";
        $sql .= "SET 	  porcentaje_cobertura = ".$dtl." ";
        $sql .= "WHERE  eps_tipo_afiliado_id = '".$key."' ";
        $sql .= "AND    lista_codigo = ".$lista." ";
        $sql .= "AND 	  plan_id = ".$plan_id." ";
        
        if(!$rst = $this->ConexionTransaccion($sql))  return false;
      }
      
      $this->Commit();
      
      return true;
    }    
    /**
    *
    */
    function ObtenerPorcentajeCobertura($plan_id,$lista)
    {
      $sql  = "SELECT eps_tipo_afiliado_id,";
      $sql .= " 	    porcentaje_cobertura ";
      $sql .= "FROM   listas_precios_cargos_planes ";
      $sql .= "WHERE  lista_codigo = ".$lista." ";
      $sql .= "AND 	  plan_id = ".$plan_id." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      $datos = array();
      while (!$rst->EOF)
      {
          $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
  }
 ?>