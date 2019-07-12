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
  
  
  
  class Consultas_TipoEvento extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function Consultas_TipoEvento(){}
	
  
    /**********************************************************************************
		* Insertar una molécula en la base de datos. Datos enviados desde formulario de Moleculas
		* 
		* @return token
		************************************************************************************/
		
	function Insertar_TipoEvento($datos)
	{
	  $sql  = "INSERT INTO esm_tipos_eventos (";
    $sql .= "       descripcion_tipo_evento, ";
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
		function Listar_TiposEventos($cod_anatomofarmacologico,$descripcion,$offset)
		{
		//	$this->debug=true;
      $sql = "SELECT 
                    *
                    FROM 
                    esm_tipos_eventos ";
      $sql .= " where ";
      $sql .= "        descripcion_tipo_evento ILIKE '%".$descripcion."%' ";
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
       
    $sql .= " ORDER BY tipo_evento_id ";
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
		function Buscar_tipo_evento($tipo_evento_id)
		{
			//$this->debug=true;
    	$sql = "SELECT	
                    tipo_evento_id,
                    descripcion_tipo_evento as descripcion
              FROM		
                  esm_tipos_eventos
              WHERE		
                  tipo_evento_id =".$tipo_evento_id.";";
						
			
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

	
  function Modificar_TipoEvento($datos)
	{
	  
    //$this->debug=true;
    $sql  = "UPDATE esm_tipos_eventos ";
    $sql .= "SET    ";
	  $sql .= "       descripcion_tipo_evento   = '".$datos['descripcion_tipo_evento']."'";
	  $sql .= " Where ";
    $sql .= "tipo_evento_id =".$datos['tipo_evento_id'].";";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
		}
 
	}
	
?>