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
  
  
  
  class Consultas_Medico_IPS extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function Consultas_Medico_IPS(){}
	
  	/**************************************************************************************
		* OBTENER LOS TIPOS DE IDENTIFICACION DE UN TERCERO
		* 
		* @return array
		***************************************************************************************/
		function Obtener_IPS()
		{
	//	$this->debug=true;
		 $sql = "SELECT 
                    IPS.tipo_id_tercero||' - '|| IPS.tercero_id as identificacion,
					TER.nombre_tercero AS nombre,
                    TER.*
                    from 
                    esm_ips_terceros IPS,
					terceros TER
				where  IPS.tipo_id_tercero=TER.tipo_id_tercero
				and     IPS.tercero_id=TER.tercero_id
					
      ";
      

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
		
		function Insertar_ProfesionalESM($esm_empresas,$tipo_id_tercero,$tercero_id)
		{
		list($esm_tipo_id_tercero,$esm_tercero_id) = explode("@",$esm_empresas);
    
       $sql  = "INSERT INTO esm_ips_profesionales (";
		$sql .= "       tipo_id_tercero_ips, ";
		$sql .= "       tercero_id_ips, ";
		$sql .= "       tipo_id_tercero, ";
		$sql .= "       tercero_id ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		
		$sql .= "        '".$esm_tipo_id_tercero."', ";
		$sql .= "        '".$esm_tercero_id."',   ";
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
 		function Listado_ProfesionalesSinEsm($esm_tipo_id_tercero,$esm_tercero_id,$nombre,$offset)
		{
	
    // $this->debug=true;
      $sql = "SELECT 
                    p.tipo_id_tercero||' - '|| p.tercero_id as identificacion,
                    p.*,
                    tp.descripcion
                    from 
                    profesionales p,
                    tipos_profesionales tp
      ";
      $sql .= " where ";
      $sql .= "        p.nombre ILIKE '%".$nombre."%' ";
      $sql .= " and    p.tipo_profesional = tp.tipo_profesional ";
      $sql .= " and    p.estado = '1' ";
      $sql .= " and    p.tipo_id_tercero||' '|| p.tercero_id NOT IN (  
                                                                select 
                                                                tipo_id_tercero||' '||tercero_id
                                                                from 
                                                                esm_ips_profesionales
                                                                where
                                                                     tipo_id_tercero_ips = '".$esm_tipo_id_tercero."'
                                                                and  tercero_id_ips = '".$esm_tercero_id."'
                                                                )";
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
             
      $sql .= " ORDER BY p.nombre ASC ";
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
 		function Listado_ProfesionalesEnEsm($esm_tipo_id_tercero,$esm_tercero_id,$nombre,$offset)
		{
			//$this->debug=true;
      $sql = "SELECT 
                    p.tipo_id_tercero||' - '|| p.tercero_id as identificacion,
                    p.*,
                    tp.descripcion
                    from 
                    profesionales p,
                    tipos_profesionales tp,
                    esm_ips_profesionales epe
      ";
      $sql .= " where ";
      $sql .= "        epe.tipo_id_tercero_ips = '".$esm_tipo_id_tercero."' ";
      $sql .= " and    epe.tercero_id_ips = '".$esm_tercero_id."' ";
      $sql .= " and    epe.tipo_id_tercero = p.tipo_id_tercero ";
      $sql .= " and    epe.tercero_id = p.tercero_id ";
      $sql .= " and    p.nombre ILIKE '%".$nombre."%' ";
      $sql .= " and    p.tipo_profesional = tp.tipo_profesional ";
      $sql .= " and    p.estado = '1' ";      
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
             
      $sql .= " ORDER BY p.nombre ASC ";
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
		function Borrar_ProfesionalESM($esm_empresas,$tipo_id_tercero,$tercero_id)
		{
			list($esm_tipo_id_tercero,$esm_tercero_id) = explode("@",$esm_empresas);
      //$this->debug=true;
      $sql = " delete from esm_ips_profesionales ";
      $sql .= " where ";
      $sql .= "        tipo_id_tercero = '".$tipo_id_tercero."' ";
      $sql .= " and    tercero_id = '".$tercero_id."' ";
      $sql .= " and    tipo_id_tercero_ips = '".$esm_tipo_id_tercero."' ";
      $sql .= " and    tercero_id_ips = '".$esm_tercero_id."' ";
      
			//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
	
 
	}
	
?>