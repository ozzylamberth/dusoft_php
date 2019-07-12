<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Consultas_Topes.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 
  
  
  
  class Consultas_Topes extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function Consultas_Topes(){}
	

	/*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
	function Listar_CentrosUtilidad($filtro)
	{
	if(!empty($filtro))
		{
		$sql = " SELECT 
		b.empresa_id||'@'||a.centro_utilidad||'@'||b.razon_social||' - '||a.descripcion as farmacia,
		b.empresa_id,
		a.centro_utilidad,
		c.tipo_formula_id as tipo_formula_id_seleccionada,
		round(c.tope_mensual) as tope_mensual,
		COALESCE(d.tipo_formula_id,0) as tipo_formula_id,
		d.descripcion_tipo_formula,
		CASE
		WHEN c.tipo_formula_id IS NOT NULL THEN 'checked'
		ELSE ''
		END as checked,
		CASE
		WHEN c.tipo_formula_id IS NOT NULL THEN '1'
		ELSE '0'
		END as operacion
		FROM
		centros_utilidad as a
		JOIN empresas as b ON (a.empresa_id = b.empresa_id)
		LEFT JOIN esm_topes_dispensacion as c ON (b.empresa_id = c.empresa_id)
		AND (a.centro_utilidad = c.centro_utilidad)
		LEFT JOIN esm_tipos_formulas as d ON (c.tipo_formula_id = d.tipo_formula_id)
		AND (d.sw_estado = '1')
		WHERE TRUE 
		AND b.sw_tipo_empresa = '1'
		AND b.sw_activa = '1' "; 
		if($filtro['descripcion'])
		$sql .= " AND b.razon_social||' '||a.descripcion ILIKE '%".$filtro['descripcion']."%'" ;
		if($filtro['empresa_id'])
		$sql .= " AND b.empresa_id = '".trim($filtro['empresa_id'])."' " ;
		if($filtro['centro_utilidad'])
		$sql .= " AND a.centro_utilidad = '".trim($filtro['centro_utilidad'])."' " ;
		}
	/*if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
	return false;*/

	$sql .= " ORDER BY b.razon_social,a.descripcion ";
	/*$sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";*/

	if(!$rst = $this->ConexionBaseDatos($sql)) 
	return false;

	$datos = array(); //Definiendo que va a ser un arreglo.

		while(!$rst->EOF) //Recorriendo el Vector;
		{
		$datos[$rst->fields[0]][$rst->fields[5]] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
		$rst->MoveNext();
		}
	$rst->Close();
	return $datos;
	}
	
	/**************************************************************************************
	* Busca si existe una Molécula con el código enviado desde formulario usuario
	* 
	* @return array
	***************************************************************************************/
	function Listar_TiposFormulas()
	{
	//$this->debug=true;
	$sql = "SELECT	
	tipo_formula_id,
	descripcion_tipo_formula as descripcion
	FROM		
	esm_tipos_formulas
	WHERE	TRUE	
	AND sw_estado = '1'
	ORDER BY tipo_formula_id;";

	if(!$rst = $this->ConexionBaseDatos($sql)) 
	return false;

	$datos = array(); //Definiendo que va a ser un arreglo.

	while(!$rst->EOF) //Recorriendo el Vector;
		{
		$datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
		$rst->MoveNext();
		}
	$rst->Close();
	return $datos;
	}	
	
	
  
    /**********************************************************************************
		* Insertar una molécula en la base de datos. Datos enviados desde formulario de Moleculas
		* 
		* @return token
		************************************************************************************/
		
	function Ejecutar($sql)
	{
	
	if(!$rst = $this->ConexionBaseDatos($sql)) 
	 return false;
		else
	return true;
	$rst->Close();
	}
	
 
    	


	
  function Modificar_TipoFormula($datos)
	{
	  
   
    $sql  = "UPDATE esm_tipos_formulas ";
    $sql .= "SET    ";
	  $sql .= "       descripcion_tipo_formula   = '".$datos['descripcion']."'";
	  $sql .= " Where ";
    $sql .= "tipo_formula_id =".$datos['tipo_formula_id'].";";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
		}
 
	}
	
?>