<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Consultas_TipoEvento.class.php,
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
  
  
  
  class Consultas_TiposRequisicion extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function Consultas_TiposRequisicion(){}
	
  
    /**********************************************************************************
		* Insertar una mol?cula en la base de datos. Datos enviados desde formulario de Moleculas
		* 
		* @return token
		************************************************************************************/
		
		function Insertar_TipoRequision($datos)
		{
		$sql  = "INSERT INTO esm_tipos_ordenes_requisicion (";
		$sql .= "       descripcion_orden_requisicion, ";
		$sql .= "       movimiento, ";
		$sql .= "       usuario_id ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        '".$datos['descripcion']."', ";
		$sql .= "        '".$datos['movimiento']."', ";
		$sql .= "        ".UserGetUID()." ";
		$sql .= "       ); ";			
		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();

		}
	
  /*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
		function Listado_TiposRequisicion($cod_anatomofarmacologico,$descripcion,$offset)
		{
		//	$this->debug=true;
		$sql = "SELECT 
		*
		FROM 
		esm_tipos_ordenes_requisicion ";
		$sql .= " where ";
		$sql .= "        descripcion_orden_requisicion ILIKE '%".$descripcion."%' ";
		if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
		return false;


		$sql .= " ORDER BY tipo_orden_requisicion ";
		$sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";

    
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
    	
		/**************************************************************************************
		* Busca si existe una Mol?cula con el c?digo enviado desde formulario usuario
		* 
		* @return array
		***************************************************************************************/
		function Buscar_TipoRequisicion($tipo_orden_requisicion)
		{
		//$this->debug=true;
		$sql = "SELECT	
		*
		FROM		
		esm_tipos_ordenes_requisicion
		WHERE		
		tipo_orden_requisicion = ".$tipo_orden_requisicion.";";


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

	
		function Modificar_TipoRequision($datos)
		{

		//$this->debug=true;
		$sql  = "UPDATE esm_tipos_ordenes_requisicion ";
		$sql .= "SET    ";
		$sql .= "       descripcion_orden_requisicion   = '".$datos['descripcion']."',";
		$sql .= "       movimiento   = '".$datos['movimiento']."'";
		$sql .= " Where ";
		$sql .= "tipo_orden_requisicion =".$datos['tipo_orden_requisicion'].";";

		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();

		}
 
	}
	
?>