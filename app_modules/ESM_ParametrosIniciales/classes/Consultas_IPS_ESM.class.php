<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Consultas_IPS_ESM.class.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra  Viviana Pantoja
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra  Viviana Pantoja
  */ 
  
  
  
  class Consultas_IPS_ESM extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function Consultas_IPS_ESM(){}
	
  	/**************************************************************************************
		* OBTENER LOS TIPOS DE IDENTIFICACION DE UN TERCERO
		* 
		* @return array
		***************************************************************************************/
		function Obtener_ESMs()
		{
		$sql = "SELECT 
                    t.tipo_id_tercero||' - '|| t.tercero_id as identificacion,
                    t.*,
                    tp.pais ||'-'||td.departamento ||'-'||tm.municipio as ubicacion
                    FROM 
                    terceros t,
                    tipo_mpios tm,
                    tipo_dptos td,
                    tipo_pais tp,
                    esm_empresas esm ";
      $sql .= " where ";
      $sql .= "        t.tipo_id_tercero = esm.tipo_id_tercero ";
      $sql .= " and    t.tercero_id = esm.tercero_id ";
      $sql .= " and    t.tipo_pais_id = tm.tipo_pais_id ";
      $sql .= " and    t.tipo_dpto_id = tm.tipo_dpto_id ";
      $sql .= " and    t.tipo_mpio_id = tm.tipo_mpio_id ";
      $sql .= " and    tm.tipo_dpto_id = td.tipo_dpto_id ";
      $sql .= " and    tm.tipo_pais_id = td.tipo_pais_id ";
      $sql .= " and    td.tipo_pais_id = tp.tipo_pais_id ";
      

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
    
        $sql  = "INSERT INTO esm_ips_esm (";
		$sql .= "       tipo_id_tercero, ";
		$sql .= "       tercero_id, ";
		$sql .= "       tipo_id_tercero_esm, ";
		$sql .= "       tercero_id_esm ";
		$sql .= "          ) ";
		$sql .= "VALUES ( ";
		$sql .= "        '".$tipo_id_tercero."', ";
		$sql .= "        '".$tercero_id."', ";
		$sql .= "        '".$esm_tipo_id_tercero."', ";
		$sql .= "        '".$esm_tercero_id."'    ";
		$sql .= "       ); ";			
		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();

		}
		/**/
		function ConsultarTipoId()
		{
			$sql  = "SELECT    tipo_id_tercero, descripcion ";
			$sql .= "FROM      tipo_id_terceros ";
			$sql .= "ORDER BY  tipo_id_tercero ";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		
  /*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
 		function Listado_ProfesionalesSinEsm($esm_tipo_id_tercero,$esm_tercero_id,$nombre,$identificacion,$tipo,$offset)
		{
		
		
	 //$this->debug=true;
      $sql = "SELECT 
                    IPS.tipo_id_tercero||' - '|| IPS.tercero_id as identificacion,
					TER.nombre_tercero AS nombre,
                    TER.*
                    from 
                    esm_ips_terceros IPS,
					terceros TER
					
      ";
		$sql .= " where ";
		$sql .= "        IPS.tipo_id_tercero=TER.tipo_id_tercero " ;
		$sql .= "  and   IPS.tercero_id=TER.tercero_id ";      
		$sql .= "  and      IPS.tipo_id_tercero ILIKE '%".$tipo."%' ";
		$sql .= "and     IPS.tercero_id ILIKE '%".$identificacion."%' ";
		$sql .= "and     TER.nombre_tercero  ILIKE '%".$nombre."%' ";
		$sql .= " and    IPS.tipo_id_tercero||' '||  IPS.tercero_id NOT IN (  
                                                                select 
                                                                tipo_id_tercero||' '|| tercero_id
                                                                from 
                                                                esm_ips_esm
                                                                where
                                                                     tipo_id_tercero_esm = '".$esm_tipo_id_tercero."'
                                                                and  tercero_id_esm= '".$esm_tercero_id."'
                                                                )";
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
             
      $sql .= " ORDER BY TER.nombre_tercero  ASC ";
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
 		function Listado_ProfesionalesEnEsm($esm_tipo_id_tercero,$esm_tercero_id,$nombre,$identificacion,$tipo,$offset)
		{
	//$this->debug=true;
       $sql = "SELECT 
                    IPS.tipo_id_tercero||' - '|| IPS.tercero_id as identificacion,
					TER.nombre_tercero AS nombre,
                    TER.*
                    from 
                    esm_ips_terceros IPS,
					terceros TER,
					esm_ips_esm EMS
					
      ";
		$sql .= " where ";
		$sql .= "        IPS.tipo_id_tercero=TER.tipo_id_tercero " ;
		$sql .= "  and   IPS.tercero_id=TER.tercero_id ";   
		$sql .= " and     IPS.tipo_id_tercero=EMS.tipo_id_tercero ";
		$sql .= " and     IPS.tercero_id=EMS.tercero_id ";
		$sql .= "  and      IPS.tipo_id_tercero ILIKE '%".$tipo."%' ";
		$sql .= "and     IPS.tercero_id ILIKE '%".$identificacion."%' ";
		$sql .= "and     TER.nombre_tercero  ILIKE '%".$nombre."%' ";
	
	
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
             
      $sql .= " ORDER BY TER.nombre_tercero  ASC ";
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
     // $this->debug=true;
      $sql = " delete from esm_ips_esm ";
      $sql .= " where ";
      $sql .= "        tipo_id_tercero = '".$tipo_id_tercero."' ";
      $sql .= " and    tercero_id = '".$tercero_id."' ";
      $sql .= " and    tipo_id_tercero_esm = '".$esm_tipo_id_tercero."' ";
      $sql .= " and    tercero_id_esm= '".$esm_tercero_id."' ";
      	 	
		//$this->debug=true;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;
		else
		return true;

		$rst->Close();
		}
	
 
	}
	
?>