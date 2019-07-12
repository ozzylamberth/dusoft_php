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
  
  
  
  class Consultas_IPS extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function Consultas_IPS(){}
	
  	/**************************************************************************************
		* OBTENER LOS TIPOS DE IDENTIFICACION DE UN TERCERO
		* 
		* @return array
		***************************************************************************************/
		function Obtener_TiposId()
		{
		//$this->debug=true;
		$sql = "SELECT	*
		FROM		
		tipo_id_terceros;";

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
		
		function Insertar_TerceroIPS($tipo_id_tercero,$tercero_id)
		{
		$sql  = "INSERT INTO esm_ips_terceros (";
		$sql .= "       tipo_id_tercero, ";
		$sql .= "       tercero_id ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        '".$tipo_id_tercero."', ";
		$sql .= "        '".$tercero_id."' ";
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
		function Listar_Terceros($tipo_id_tercero,$tercero_id,$nombre_tercero,$offset)
		{
			//$this->debug=true;
         if($tipo_id_tercero!="")
        {
        $filtro =" and t.tipo_id_tercero = '".$tipo_id_tercero."' ";
        }
        if($tercero_id!="")
        {
        $filtro .=" and t.tercero_id = '".$tercero_id."' ";
        }
        
      $sql = "SELECT 
                    t.tipo_id_tercero||' - '|| t.tercero_id as identificacion,
                    t.*,
                    tp.pais ||'-'||td.departamento ||'-'||tm.municipio as ubicacion
                    FROM 
                    terceros t,
                    tipo_mpios tm,
                    tipo_dptos td,
                    tipo_pais tp ";
      $sql .= " where ";
      $sql .= "        t.nombre_tercero ILIKE '%".$nombre_tercero."%' ";
      $sql .= "       ".$filtro;
      $sql .= " and    t.tipo_pais_id = tm.tipo_pais_id ";
      $sql .= " and    t.tipo_dpto_id = tm.tipo_dpto_id ";
      $sql .= " and    t.tipo_mpio_id = tm.tipo_mpio_id ";
      $sql .= " and    tm.tipo_dpto_id = td.tipo_dpto_id ";
      $sql .= " and    tm.tipo_pais_id = td.tipo_pais_id ";
      $sql .= " and    td.tipo_pais_id = tp.tipo_pais_id ";
      $sql .= " and    t.tipo_id_tercero||' '|| t.tercero_id NOT IN (  
                                                                select 
                                                                tipo_id_tercero||' '|| tercero_id
                                                                from 
                                                                esm_ips_terceros
                                                                )";
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
       
    $sql .= " ORDER BY t.nombre_tercero ASC ";
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
    
    /*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
		function Listar_IPS($tipo_id_tercero,$tercero_id,$nombre_tercero,$offset)
		{
			//$this->debug=true;
      if($tipo_id_tercero!="")
        {
        $filtro =" and t.tipo_id_tercero = '".$tipo_id_tercero."' ";
        }
    if($tercero_id!="")
        {
        $filtro .=" and t.tercero_id = '".$tercero_id."' ";
        }
      
      $sql = "SELECT 
                    t.tipo_id_tercero||' - '|| t.tercero_id as identificacion,
                    t.*,
                    tp.pais ||'-'||td.departamento ||'-'||tm.municipio as ubicacion
                    FROM 
                    terceros t,
                    tipo_mpios tm,
                    tipo_dptos td,
                    tipo_pais tp,
                    esm_ips_terceros esm ";
      $sql .= " where ";
      $sql .= "        t.nombre_tercero ILIKE '%".$nombre_tercero."%' ";
      $sql .= "       ".$filtro;      
      $sql .= " and    t.tipo_id_tercero = esm.tipo_id_tercero ";
      $sql .= " and    t.tercero_id = esm.tercero_id ";
      $sql .= " and    t.tipo_pais_id = tm.tipo_pais_id ";
      $sql .= " and    t.tipo_dpto_id = tm.tipo_dpto_id ";
      $sql .= " and    t.tipo_mpio_id = tm.tipo_mpio_id ";
      $sql .= " and    tm.tipo_dpto_id = td.tipo_dpto_id ";
      $sql .= " and    tm.tipo_pais_id = td.tipo_pais_id ";
      $sql .= " and    td.tipo_pais_id = tp.tipo_pais_id ";
      
      
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
       
    $sql .= " ORDER BY t.nombre_tercero ASC ";
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
    	
       /*
	*	
	*/
		function Borrar_TerceroIPS($tipo_id_tercero,$tercero_id,$nombre_tercero,$offset)
		{
			//$this->debug=true;
      $sql = " delete from esm_ips_terceros ";
      $sql .= " where ";
      $sql .= "        tipo_id_tercero = '".$tipo_id_tercero."' ";
      $sql .= " and    tercero_id = '".$tercero_id."' ";
      
			//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
	
 
	}
	
?>