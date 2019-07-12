<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: NotasFacturas.class.php,v 1.1 2010/03/09 13:40:54 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: NotasFacturas
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class NotasFacturas extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function NotasFacturas(){}
    /*
    * Funcion donde se obtienen los permisos de los usuarios para acceder al modulo
    *
    * @return mixed
    */
		function ObtenerPermisos($usuario)
		{			
			$sql  = "SELECT	E.empresa_id AS empresa, ";
			$sql .= "				E.razon_social AS razon_social, ";
			$sql .= "				E.tipo_id_tercero,  ";
			$sql .= "				E.id  ";
			$sql .= "FROM	userpermisos_glosas_contabilizacion G,empresas E ";
			$sql .= "WHERE	G.usuario_id = ".$usuario." ";
			$sql .= "AND	G.empresa_id = E.empresa_id";

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
		* Funcion domde se seleccionan los tipos de id de los terceros 
		* 
		* @return array datos de tipo_id_terceros 
		*/
		function ObtenerTipoIdTerceros()
		{
			$sql  = "SELECT tipo_id_tercero, ";
      $sql .= "       descripcion ";
      $sql .= "FROM   tipo_id_terceros ";
	
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
    /**
		* Funcion donde se obtienen los los prefijos de las facturas para agregarlos al 
		* buscador
		*
    * @param string $empresa Identificador de la empresa 
    *
		* @return mixed
		*/
		function ObtenerPrefijos($empresa)
		{
			$sql  = "SELECT DISTINCT FF.prefijo ";
			$sql .= "FROM 	fac_facturas FF  ";
			$sql .= "WHERE 	FF.sw_clase_factura = '0'::bpchar  "; 
			$sql .= "AND 		FF.empresa_id = '".$empresa."'  "; 
			$sql .= "AND 		FF.estado = '0'::bpchar  ";
			
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
    * Funcion donde se obtienen las facturas segun los filtros de busqueda
    *
    * @param string $empresa Identificador de la empresa 
    * @param array $filtros Arreglo de datos con los filtros de la busqueda
    * @param integer $offset Identificador para paginar los resultados
    *
    * @return mixed
    */
    function ObtenerFacturas($empresa,$filtro,$offset)
		{
			$sql  = "SELECT FF.prefijo,";
			$sql .= "				FF.factura_fiscal,";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
			$sql .= "				FF.tipo_id_tercero,";
			$sql .= "				FF.tercero_id,";
			$sql .= "				FF.total_factura, ";
			$sql .= "				CASE WHEN FF.saldo IS NULL THEN FF.total_factura";
      $sql .= "            ELSE FF.saldo END AS saldo, ";
			$sql .= "				FF.empresa_id, ";
			$sql .= "				TE.nombre_tercero, ";
			$sql .= "				TN.tmp_nota_contado_id, ";
			$sql .= "				TN.observacion ";
			$sql .= "FROM   fac_facturas FF LEFT JOIN ";
			$sql .= "			  tmp_notas_credito_contado TN ";
			$sql .= "			  ON ( ";
			$sql .= "			     FF.empresa_id = TN.empresa_id AND";
			$sql .= "			     FF.prefijo = TN.prefijo AND ";
			$sql .= "			     FF.factura_fiscal = TN.factura_fiscal ";
      if($filtro['tipo_nota'])
        $sql .= "          AND TN.naturaleza = '".$filtro['tipo_nota']."' ";
			$sql .= "			  ), ";
			$sql .= "			  terceros TE ";
			$sql .= "WHERE 	FF.empresa_id = '".$empresa."' ";
			$sql .= "AND	 	FF.estado = '0' ";	
			$sql .= "AND	 	FF.sw_clase_factura = '0'::bpchar ";	
			$sql .= "AND		TE.tercero_id = FF.tercero_id ";
			$sql .= "AND		TE.tipo_id_tercero = FF.tipo_id_tercero ";
			
			if($filtro['prefijo'])
				$sql .= "AND	FF.prefijo = '".$filtro['prefijo']."' ";
			
      if($filtro['factura_fiscal'])
				$sql .= "AND	FF.factura_fiscal = ".$filtro['factura_fiscal']." ";

			if($filtro['tipo_id_tercero'] != "-1" && $filtro['documento'] !="")
      {
        $sql .= "AND	FF.tipo_id_tercero = '".$filtro['tipo_id_tercero']."' ";
        $sql .= "AND	FF.tercero_id = '".$filtro['documento']."' ";
      }
      
			if($filtro['fecha_factura'])
				$sql .= "AND FF.fecha_registro = '".$this->DividirFecha($filtro['fecha_factura'])."' ";
						
			$cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
			
			$this->ProcesarSqlConteo($cont,$offset);
				
			$sql .= "ORDER BY FF.fecha_registro,FF.prefijo,FF.factura_fiscal  ";
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
    /***
		* Funcion donde se obtienen los conceptos, para su seleccion al momento de hacer 
    * la nota
		* 
    * @param string $tipo_concepto Tipo de concepto
    * @param string $empresa Identificador de la empresa
    *
		* @return mixed
		**/
		function ObtenerConceptos($tipo_concepto,$empresa)
		{
			$sql  = "SELECT nota_contado_concepto_id,";
			$sql .= "				sw_naturaleza, ";
			$sql .= "				descripcion, ";
			$sql .= "				sw_centro_costo, ";
			$sql .= "				sw_tercero ";
			$sql .= "FROM		notas_contado_conceptos ";
			$sql .= "WHERE 	empresa_id ='".$empresa."' ";
			$sql .= "AND		sw_activo = '1' ";
			$sql .= "AND		sw_naturaleza = '".$tipo_concepto."' ";
			$sql .= "ORDER BY descripcion ";
		
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $conceptos = array();
      
			while(!$rst->EOF)
			{
				$conceptos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $conceptos;
		}
    /**
		* Funcion donde se obtiene la informacion del concepto seleccionado
		* 
    * @param integer $concepto Iddentificador del concepto 
    *
		* @return array datos de los conceptos de tesoreria 
		*/
		function ObtenerInformacionConcepto($concepto)
		{
      $sql  = "SELECT nota_contado_concepto_id,";
			$sql .= "				sw_naturaleza, ";
			$sql .= "				descripcion, ";
			$sql .= "				sw_centro_costo, ";
			$sql .= "				sw_tercero ";
			$sql .= "FROM		notas_contado_conceptos ";
			$sql .= "WHERE 	nota_contado_concepto_id = ".$concepto." ";
		
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $concepto = array();
			if(!$rst->EOF)
			{
				$concepto = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $concepto;
		}
    /**
		* Funcion donde se obtienen los departamentos, de la empresa
		* 
    * @param string $empresa Identificador de la empresa
    *
		* @return array
		*/
		function ObtenerDepartamentos($empresa)
		{
			$sql .= "SELECT	departamento,";
			$sql .= "				descripcion ";
			$sql .= "FROM		departamentos ";
			$sql .= "WHERE	empresa_id = '".$empresa."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      while(!$rst->EOF)
      {
      	$departamentos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }

      $rst->Close();	
			
			return $departamentos;			
		}
    /**
    * Funcion donde se obtiene la informacion de los terceros
    *
    * @param array $filtros Arreglo de datos con los filtros de la busqueda
    * @param integer $offset Identificador para paginar los resultados
    *
    * @return mixed
		*/
		function ObtenerTerceros($filtros,$offset)
		{
			$sql .= "SELECT tipo_id_tercero,"; 
			$sql .= "				tercero_id, ";
			$sql .= "				nombre_tercero ";			
			$sql .= "FROM 	terceros ";
			$sql .= "WHERE	tercero_id IS NOT NULL ";
			
			if($filtros['tipo_id_tercero'] != '-1')
				$sql .= "AND  tipo_id_tercero = '".$filtros['tipo_id_tercero']."' ";

			if($filtros['tercero_id'] != "")
				$sql .= "AND  tercero_id = '".$filtros['tercero_id']."' ";
        
      if($filtros['nombre_tercero'] != "")
				$sql .= "AND  nombre_tercero ILIKE '%".$filtros['nombre_tercero']."%' ";
			
			$cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
			
			$this->ProcesarSqlConteo($cont,$offset);
				
			$sql .= "ORDER BY nombre_tercero  ";
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
    /**
    * Funcion donde se obtienen los conceptos adicionados a una nota
    *
    * @param array $datos Arreglo con la informacion de la nota
    *
    * @return mixed
    */
    function ObtenerConceptosTmp($datos)
    {
      $sql  = "SELECT TN.tmp_concepto_id ,";
      $sql .= "       TN.valor,";
      $sql .= "       TN.naturaleza ,";
      $sql .= "       TN.concepto_id ,";
      $sql .= "       DE.descripcion AS departamento ,";
      $sql .= "       TE.nombre_tercero ,";
      $sql .= "       TN.tercero_id ,";
      $sql .= "       TN.tipo_id_tercero, ";
      $sql .= "				NC.descripcion ";
      $sql .= "FROM   tmp_notas_credito_contado_conceptos TN ";
			$sql .= "   		LEFT JOIN departamentos DE ";
			$sql .= "   		ON(DE.departamento = TN.departamento) ";			
      $sql .= "   		LEFT JOIN terceros TE ";
			$sql .= "   		ON( TE.tercero_id = TN.tercero_id AND ";
      $sql .= "           TE.tipo_id_tercero = TN.tipo_id_tercero ), ";
			$sql .= "   		notas_contado_conceptos NC ";
      $sql .= "WHERE  tmp_nota_contado_id  = ".$datos['tmp_nota_id']." ";
      $sql .= "AND    NC.nota_contado_concepto_id = TN.concepto_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
    }
    /**
    * Funcion donde se registra temporalmente la nota con el detalle de los conceptos
    *
    * @param array $datos Arreglo con la informacion a registrar
    *
    * @return boolean
    */
    function IngresarTemporalNota($datos)
    {
      $this->ConexionTransaccion();
      if(!$datos['tmp_nota_id'])
      {
        $sql = "SELECT NEXTVAL('tmp_notas_credito_contado_tmp_nota_contado_id_seq') AS tmp_nota_id ";
        if(!$rst =$this->ConexionTransaccion($sql))
          return false;
        
        if(!$rst->EOF)
        {
          $indice = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $datos['tmp_nota_id'] = $indice['tmp_nota_id'];
        $this->tmp_id = $indice['tmp_nota_id'];
        
        $sql  = "INSERT INTO tmp_notas_credito_contado ";
        $sql .= " ( ";
        $sql .= "   tmp_nota_contado_id, ";
        $sql .= "   empresa_id, ";
        $sql .= "   prefijo, ";
        $sql .= "   factura_fiscal, ";
        $sql .= "   usuario_id, ";
        $sql .= "   fecha_registro, ";
        $sql .= "   observacion , ";
        $sql .= "   naturaleza ";
        $sql .= " ) ";
        $sql .= "VALUES ";
        $sql .= " ( ";
        $sql .= "    ".$datos['tmp_nota_id'].", ";
        $sql .= "   '".$datos['empresa_id']."', ";
        $sql .= "   '".$datos['prefijo']."', ";
        $sql .= "    ".$datos['factura_fiscal'].", ";
        $sql .= "    ".$datos['usuario_id'].", ";
        $sql .= "    NOW(), ";
        $sql .= "   '".$datos['observacion']."', ";
        $sql .= "   '".$datos['tipo_nota']."' ";
        $sql .= " ) ";
        
        if(!$rst =$this->ConexionTransaccion($sql))
          return false;
      }
      $sql  = "INSERT INTO tmp_notas_credito_contado_conceptos";
      $sql .= " ( "; 
      $sql .= "   tmp_concepto_id ,";
      $sql .= "   valor,";
      $sql .= "   naturaleza ,";
      $sql .= "   tmp_nota_contado_id ,";
      $sql .= "   concepto_id ,";
      $sql .= "   departamento ,";
      $sql .= "   tercero_id ,";
      $sql .= "   tipo_id_tercero ";
      $sql .= " ) ";  
      $sql .= "VALUES ";  
      $sql .= " ( "; 
      $sql .= "    DEFAULT,"; 
      $sql .= "    ".$datos['valor_concepto'].",";  
      $sql .= "   '".$datos['naturaleza_concepto']."',";  
      $sql .= "    ".$datos['tmp_nota_id'].", ";
      $sql .= "    ".$datos['concepto'].",";  
      $sql .= "    ".(($datos['departamento'])? "'".$datos['departamento']."'":"NULL").", ";      
      $sql .= "    ".(($datos['tercero_id'])? "'".$datos['tercero_id']."'":"NULL").", ";      
      $sql .= "    ".(($datos['tipo_id_tercero'])? "'".$datos['tipo_id_tercero']."'":"NULL")." ";      
      $sql .= " ) ";
      
      if(!$rst =$this->ConexionTransaccion($sql))
        return false;
      
      $this->Commit();
      return true;
    }
    /**
    * Funcion donde se registra temporalmente la nota
    *
    * @param array $datos Arreglo con la informacion a registrar
    *
    * @return boolean
    */
    function RegistrarTemporalNota($datos)
    {
      $this->ConexionTransaccion();
      if(!$datos['tmp_nota_id'])
      {
        $sql = "SELECT NEXTVAL('tmp_notas_credito_contado_tmp_nota_contado_id_seq') AS tmp_nota_id ";
        if(!$rst =$this->ConexionTransaccion($sql))
          return false;
        
        if(!$rst->EOF)
        {
          $indice = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $datos['tmp_nota_id'] = $indice['tmp_nota_id'];
        $this->tmp_id = $indice['tmp_nota_id'];
        
        $sql  = "INSERT INTO tmp_notas_credito_contado ";
        $sql .= " ( ";
        $sql .= "   tmp_nota_contado_id, ";
        $sql .= "   empresa_id, ";
        $sql .= "   prefijo, ";
        $sql .= "   factura_fiscal, ";
        $sql .= "   usuario_id, ";
        $sql .= "   fecha_registro, ";
        $sql .= "   auditor_id , ";
        $sql .= "   observacion , ";
        $sql .= "   naturaleza ";
        $sql .= " ) ";
        $sql .= "VALUES ";
        $sql .= " ( ";
        $sql .= "    ".$datos['tmp_nota_id'].", ";
        $sql .= "   '".$datos['empresa_id']."', ";
        $sql .= "   '".$datos['prefijo']."', ";
        $sql .= "    ".$datos['factura_fiscal'].", ";
        $sql .= "    ".$datos['usuario_id'].", ";
        $sql .= "    NOW(), ";
        $sql .= "    ".(($datos['auditor_id'] && $datos['auditor_id']!='-1')? $datos['auditor_id']:"NULL").", ";
        $sql .= "   '".$datos['observa']."', ";
        $sql .= "   '".$datos['tipo_nota']."' ";
        $sql .= " ) ";
        
        if(!$rst =$this->ConexionTransaccion($sql))
          return false;
      }
      else
      {
        $sql  = "UPDATE tmp_notas_credito_contado ";
        $sql .= "SET    auditor_id  = ".(($datos['auditor_id'] && $datos['auditor_id']!='-1')? $datos['auditor_id']:"NULL").", ";
        $sql .= "       observacion = '".$datos['observa']."' ";
        $sql .= "WHERE  tmp_nota_contado_id = ".$datos['tmp_nota_id']." ";
        
        if(!$rst =$this->ConexionTransaccion($sql))
          return false;
      }
      $this->Commit();
      return true;
    }
    /**
    * Funcion donde se elimina un concepto dado de la nota temporal
    *
    * @param integer $concepto Identificador del concepto
    *
    * @return boolean
    */
    function EliminarConceptoTmp($concepto)
    {
      $this->ConexionTransaccion();

      $sql  = "DELETE FROM tmp_notas_credito_contado_conceptos ";
      $sql .= "WHERE  tmp_concepto_id = ".$concepto." ";
      
      if(!$rst =$this->ConexionTransaccion($sql))
        return false;
      
      $this->Commit();
      
      return true;
    }    
    /**
    * Funcion donde se elimina una nota temporal
    *
    * @param integer $tmp_nota_id Identificador de la nota
    *
    * @return boolean
    */
    function EliminarNotaTemporal($tmp_nota_id)
    {
      $this->ConexionTransaccion();

      $sql  = "DELETE FROM tmp_notas_credito_contado_conceptos ";
      $sql .= "WHERE  tmp_nota_contado_id = ".$tmp_nota_id." ";
      
      if(!$rst =$this->ConexionTransaccion($sql))
        return false;
      
      $sql  = "DELETE FROM tmp_notas_credito_contado ";
      $sql .= "WHERE  tmp_nota_contado_id = ".$tmp_nota_id." ";
      
      if(!$rst =$this->ConexionTransaccion($sql))
        return false;
      
      $this->Commit();
      
      return true;
    }
    /**
		* Funcion donde se toman de la base de datos los auditores internos registrados
		* 
		* @return array datos de las clasificaciones de las glosas 
		*/
		function ObtenerAuditoresInternos($empresa)
		{
			$sql  = "SELECT	U.usuario_id,";
			$sql .= "				U.nombre ";
			$sql .= "FROM		system_usuarios U,";
			$sql .= "				auditores_internos A ";
			$sql .= "WHERE	U.usuario_id = A.usuario_id ";
			$sql .= "AND		A.estado = '1' ";
			$sql .= "AND		A.empresa_id = '".$empresa."' ";
						
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
		* Funcion donde se realiza el proceso de cierre de una nota de contado
		*
		* @param array $nota Datos de la nota a cerrar
		*
		* @return boolean Verdadero si todo concluyo de manera correcta o falso en otro caso
		*/
		function CrearNotaContado($datos)
		{
			$this->ConexionTransaccion();
			
			$sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE ";//Bloqueo de tabla 
			if(!$rst = $this->ConexionTransaccion($sql)) 
        return false;

			$sql  = "SELECT prefijo,";
      $sql .= "       numeracion ";
      $sql .= "FROM   documentos ";
			$sql .= "WHERE  documento_id = ".$datos['documento_id']." ";
      $sql .= "AND    empresa_id = '".$datos['empresa_id']."' ";
					
			if(!$rst = $this->ConexionTransaccion($sql)) 
        return false;
			
			$numer = array();
			if(!$rst->EOF)
      {
      	$numer = $rst->GetRowAssoc($ToUpper = false);				
      	$rst->MoveNext();
      }
			
			if(empty($numer))
			{
				$this->mensajeDeError = "NO SE HAN PARAMETRIZADO LOS VALORES DEL DOCUMENTO";
				return false;
			}
			
			$sql  = "INSERT INTO notas_contado_".$datos['tabla']."";
      $sql .= "     ( ";
			$sql .= "				empresa_id ,";
			$sql .= "				prefijo,";
			$sql .= "				numero ,";
			$sql .= "				prefijo_factura ,";
			$sql .= "				factura_fiscal ,";
			$sql .= "				valor_nota ,";
			$sql .= "				fecha_registro ,";
			$sql .= "				usuario_id ,";
			$sql .= "				observacion ,";
			$sql .= "				documento_id,";
			$sql .= "				auditor_id ,";
			$sql .= "				estado";
      $sql .= "   ) ";
      $sql .= "SELECT empresa_id, ";
      $sql .= "       '".$numer['prefijo']."' AS prefijo_nota, ";
      $sql .= "        ".$numer['numeracion']." AS numero, ";
      $sql .= "       prefijo, ";
      $sql .= "       factura_fiscal, ";
      $sql .= "       ".$datos['total_nota']." AS valor, ";
      $sql .= "       fecha_registro, ";
      $sql .= "       ".$datos['usuario_id']." AS usuario, ";
      $sql .= "       observacion , ";
      $sql .= "       ".$datos['documento_id']." AS documento_id, ";
      $sql .= "       auditor_id , ";
      $sql .= "       '1' AS estado ";
      $sql .= "FROM   tmp_notas_credito_contado ";
      $sql .= "WHERE  tmp_nota_contado_id = ".$datos['tmp_nota_id']." ";
			
			if(!$rst = $this->ConexionTransaccion($sql)) 
        return false;
				
      $sql  = "INSERT INTO notas_contado_".$datos['tabla']."_d";
      $sql .= "   ( ";
      $sql .= "		  empresa_id, ";
      $sql .= "     numero, ";
      $sql .= "     prefijo , ";
      $sql .= "     nota_contado_concepto_id , ";
      $sql .= "     valor , ";
      $sql .= "     tercero_id , ";
      $sql .= "     tipo_id_tercero, ";
      $sql .= "     departamento, ";
      $sql .= "     naturaleza ";
      $sql .= "		) ";
      $sql .= "SELECT '".$datos['empresa_id']."' AS empresa, ";
      $sql .= "        ".$numer['numeracion']." AS numero, ";
      $sql .= "       '".$numer['prefijo']."' AS prefijo_nota, ";
      $sql .= "         concepto_id ,";
      $sql .= "         valor,";
      $sql .= "         tercero_id ,";
      $sql .= "         tipo_id_tercero, ";
      $sql .= "         departamento ,";
      $sql .= "         naturaleza ";
      $sql .= "FROM     tmp_notas_credito_contado_conceptos ";
      $sql .= "WHERE    tmp_nota_contado_id  = ".$datos['tmp_nota_id']." ";
			
			if(!$rst = $this->ConexionTransaccion($sql)) 
        return false;
			
			$sql  = "UPDATE documentos ";
			$sql .= "SET 	  numeracion = numeracion + 1 ";
			$sql .= "WHERE  documento_id = ".$datos['documento_id']." ";
      $sql .= "AND    empresa_id = '".$datos['empresa_id']."' ";
			
			if(!$rst = $this->ConexionTransaccion($sql)) 
        return false;
        
      $sql  = "DELETE FROM tmp_notas_credito_contado_conceptos ";
      $sql .= "WHERE  tmp_nota_contado_id = ".$datos['tmp_nota_id']." ";
      
      if(!$rst =$this->ConexionTransaccion($sql))
        return false;
      
      $sql  = "DELETE FROM tmp_notas_credito_contado ";
      $sql .= "WHERE  tmp_nota_contado_id = ".$datos['tmp_nota_id']." ";
      
      if(!$rst =$this->ConexionTransaccion($sql))
        return false;
      
			$this->Commit();
			
      $this->numero = $numer['numeracion'];
      $this->prefijo = $numer['prefijo'];

      
			return true;
		}
    /**
		* Funcion donde se obtiene el nombre de un usuario
		*
    * @param int $usuario Identificacion del usuario
		*
    * @return mixed
    */
		function ObtenerInformacionUsuario($usuario)
		{
			$sql .= "SELECT	nombre ";
			$sql .= "FROM		system_usuarios "; 
			$sql .= "WHERE	usuario_id = ".$usuario." ";		
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			if(!$rst->EOF)
			{
				$datos =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
    /**
    * funcion donde se obtiene la informacion de la nota credito o debito
    *
    * @param array $datos Arreglo con los filtros para la busqueda de la nota
    *
    * @return mixed
    */
    function ObtenerNota($datos)
    {
      $sql .= "SELECT NC.prefijo_factura ,";
			$sql .= "				NC.factura_fiscal ,";
			$sql .= "				NC.valor_nota ,";
			$sql .= "				NC.prefijo ,";
			$sql .= "				NC.numero ,";
			$sql .= "				NC.observacion ,";
			$sql .= "				TO_CHAR(NC.fecha_registro,'DD/MM/YYYY') AS fecha_registro ,";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS fecha_factura ,";
			$sql .= "				FF.total_factura, ";
			$sql .= "				FF.saldo, ";
			$sql .= "				AU.nombre AS auditor ,";
			$sql .= "				SU.nombre, ";
 			$sql .= "   		TE.tercero_id, ";
			$sql .= "   		TE.tipo_id_tercero, ";
			$sql .= "				TE.nombre_tercero ";
      $sql .= "FROM   notas_contado_".$datos['tabla']." NC ";
      $sql .= "       LEFT JOIN system_usuarios AU ";
      $sql .= "       ON(AU.usuario_id = NC.auditor_id), ";
      $sql .= "       system_usuarios SU, ";
      $sql .= "       fac_facturas FF, ";
      $sql .= "       terceros TE  ";
			$sql .= "WHERE  NC.empresa_id = '".$datos['empresa_id']."' ";
			$sql .= "AND	  NC.prefijo = '".$datos['prefijo']."'";
			$sql .= "AND 		NC.numero = ".$datos['numero']." ";
			$sql .= "AND 		NC.usuario_id = SU.usuario_id ";
			$sql .= "AND 		FF.prefijo = NC.prefijo_factura ";
			$sql .= "AND 		FF.factura_fiscal = NC.factura_fiscal ";
			$sql .= "AND		TE.tercero_id = FF.tercero_id ";
			$sql .= "AND		TE.tipo_id_tercero = FF.tipo_id_tercero ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			if (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
	 		
	 		return $datos;
    }
    /**
    * Funcion donde se obtiene el detalle de los conceptos de la nota debito o credto
    *
    * @param array $datos Arreglo con los filtros para la busqueda de la nota
    *
    * @return mixed
    */
    function ObtenerConceptosNota($datos)
    {
      $sql  = "SELECT NC.valor,";
      $sql .= "       NC.naturaleza ,";
      $sql .= "       NC.tercero_id ,";
      $sql .= "       NC.tipo_id_tercero, ";
      $sql .= "       DE.descripcion AS departamento ,";
      $sql .= "       TE.nombre_tercero ,";
      $sql .= "				ND.descripcion ";
      $sql .= "FROM   notas_contado_".$datos['tabla']."_d NC ";
			$sql .= "   		LEFT JOIN departamentos DE ";
			$sql .= "   		ON(DE.departamento = NC.departamento) ";			
      $sql .= "   		LEFT JOIN terceros TE ";
			$sql .= "   		ON( TE.tercero_id = NC.tercero_id AND ";
      $sql .= "           TE.tipo_id_tercero = NC.tipo_id_tercero ), ";
			$sql .= "   		notas_contado_conceptos ND ";
			$sql .= "WHERE  NC.empresa_id = '".$datos['empresa_id']."' ";
			$sql .= "AND	  NC.prefijo = '".$datos['prefijo']."'";
			$sql .= "AND 		NC.numero = ".$datos['numero']." ";
      $sql .= "AND    ND.nota_contado_concepto_id = NC.nota_contado_concepto_id ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
    }
  }
?>