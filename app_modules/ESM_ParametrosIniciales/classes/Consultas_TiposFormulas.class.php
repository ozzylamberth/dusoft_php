<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Consultas_TiposFormulas.class.php,
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
  
  
  
  class Consultas_TiposFormulas extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function Consultas_TiposFormulas(){}
	
  
    /**********************************************************************************
		* Insertar una molécula en la base de datos. Datos enviados desde formulario de Moleculas
		* 
		* @return token
		************************************************************************************/
		
	function Insertar_TipoFormula($datos)
	{
	  $sql  = "INSERT INTO esm_tipos_formulas (";
    $sql .= "       descripcion_tipo_formula, ";
    $sql .= "       usuario_id ";
	  $sql .= "          ) ";
    $sql .= "VALUES ( ";
    $sql .= "        '".$datos['descripcion']."', ";
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
		function Listar_TiposFormulas($cod_anatomofarmacologico,$descripcion,$offset)
		{
		//	$this->debug=true;
      $sql = "SELECT 
                    *
                    FROM 
                    esm_tipos_formulas ";
      $sql .= " where ";
      $sql .= "        descripcion_tipo_formula ILIKE '%".$descripcion."%' ";
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
       
    $sql .= " ORDER BY tipo_formula_id ";
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
		* Busca si existe una Molécula con el código enviado desde formulario usuario
		* 
		* @return array
		***************************************************************************************/
		function Buscar_TipoFormula($tipo_formula_id)
		{
			//$this->debug=true;
    	$sql = "SELECT	
                    tipo_formula_id,
                    descripcion_tipo_formula as descripcion
              FROM		
                  esm_tipos_formulas
              WHERE		
                  tipo_formula_id =".$tipo_formula_id.";";
						
			
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

	
  function Modificar_TipoFormula($datos)
	{
	  
    //$this->debug=true;
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