<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasParametrizarPrioridadBodegasDespachos.class.php,
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
  
  
  
  class ConsultasParametrizarPrioridadBodegasDespachos extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasParametrizarPrioridadBodegasDespachos(){}
	
  
  
  
  function Listar_Empresas()
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              EM.razon_social AS Empresa,
              EM.direccion,
              EM.telefonos,
              d.departamento,
              m.municipio,
              EM.empresa_id,
              EM.sw_prioridad_despacho
							FROM		
              empresas EM,
              tipo_dptos d,
              tipo_mpios m
							WHERE		
              EM.empresa_id = EM.empresa_id
              and
              m.tipo_mpio_id = EM.tipo_mpio_id
              and
              m.tipo_dpto_id = EM.tipo_dpto_id
              and
              m.tipo_pais_id = EM.tipo_pais_id
              and
              m.tipo_dpto_id = d.tipo_dpto_id
              and
              EM.sw_activa ='1'
              and
              EM.sw_tipo_empresa <> '2'
              order by sw_prioridad_despacho ASC
              ";
		   
       
       
        
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    
    function CentroUtilidadXEmpresa($EmpresaId)
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              centro_utilidad,
              descripcion
							FROM		
              centros_utilidad
              WHERE		
              empresa_id = '".$EmpresaId."';
              ";
		   
        
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    
    function BodegasXCentroUtilXEmpresa($EmpresaId,$CentroUtilidad)
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              bodega,
              descripcion
							FROM		
              bodegas
              WHERE		
              empresa_id = '".$EmpresaId."'
              and
              centro_utilidad = '".$CentroUtilidad."';
             
              ";
		   
        
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
     
	}
	
?>