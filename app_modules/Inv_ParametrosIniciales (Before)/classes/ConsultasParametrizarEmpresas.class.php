<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasParametrizarEmpresas.class.php,
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
  
  
  
  class ConsultasParametrizarEmpresas extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasParametrizarEmpresas(){}
	
  
  
  
  /*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
		function Listar_Empresas($empresa_id,$razon_social,$offset)
		{
			//$this->debug=true;
      $sql = "SELECT 
                    emp.sw_estados,
                    emp.sw_vende,
                    emp.sw_tipo_empresa,
                    emp.empresa_id,
                    emp.razon_social,
                    p.pais,
                    d.departamento,
                    m.municipio,
                    emp.direccion
                        FROM 
                            empresas emp,
                            tipo_mpios m,
                            tipo_dptos d,
                            tipo_pais p
                    where
                          emp.sw_activa = '1'
                    and   emp.tipo_pais_id = m.tipo_pais_id
                    and   emp.tipo_dpto_id = m.tipo_dpto_id
                    and   emp.tipo_mpio_id = m.tipo_mpio_id
                    and   m.tipo_dpto_id = d.tipo_dpto_id
                    and   m.tipo_pais_id = d.tipo_pais_id
                    and   d.tipo_pais_id = p.tipo_pais_id
                    and   emp.razon_social ILIKE '%".$razon_social."%'
                    and   emp.empresa_id ILIKE '%".$empresa_id."%'
                    
                    ";
		
    if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
      
       
    $sql .= " ORDER BY emp.empresa_id ";
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
	}
	
?>