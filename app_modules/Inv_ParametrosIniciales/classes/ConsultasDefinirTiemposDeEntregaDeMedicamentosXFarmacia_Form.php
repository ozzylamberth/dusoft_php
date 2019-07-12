<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultasDefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica.class.php,
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */ 
  
  
  
  class ConsultasDefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica extends ConexionBD
  {
    /**
    * Contructor
    */
    
	function ConsultasDefinirTiemposDeEntregaDeMedicamentosXFarmacia_FormulaMedica(){}
	
  
  
  
  function Listar_Farmacias($offset)
		{
				//$this->debug=true;
        $sql = "
              SELECT	
              EM.razon_social AS Empresa,
              EM.direccion,
              EM.telefonos,
              d.departamento,
              m.municipio,
              EM.empresa_id
							FROM		
              empresas EM,
              tipo_dptos d,
              tipo_mpios m
							WHERE		
              EM.empresa_id = EM.empresa_id
              and
              EM.sw_tipo_empresa = '1'
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
	
 function BuscarFarmacia($Empresa_Id)
		{
			//$this->debug=true;
    	$sql = "SELECT	
              EM.razon_social AS Empresa,
              EM.direccion,
              EM.telefonos,
              d.departamento,
              m.municipio,
              EM.empresa_id,
              EM.codigo_sgsss,
              EM.fax
							FROM		
              empresas EM,
              tipo_dptos d,
              tipo_mpios m
							WHERE		
              EM.empresa_id = '".$Empresa_Id."'
              and
              EM.sw_tipo_empresa = '1'
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
                ;";
						
			
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
  
  
  
 function Buscar_FarmaciaTiempoEntrega($Empresa_Id)
		{
			//$this->debug=true;
    	$sql = "SELECT	
              tiempo_entrega
							From
              inv_farmacias_tiempoentrega_medicamentos
              WHERE		
              empresa_id = '".$Empresa_Id."'
              ;";
						
			
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
   
   function ModificarDaticos($datos)
	{
	  
   // $this->debug=true;
    $sql  = "UPDATE inv_farmacias_tiempoentrega_medicamentos ";
    $sql .= "SET tiempo_entrega = '".$datos['tiempo_entrega']."'";
	  $sql .= " Where ";
    $sql .= "empresa_id ='".$datos['empresa_id']."';";
	  
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
  
  
  
  
  
  
		
	function GuardarDaticos($datos)
	{
	  //$this->debug=true;
    $sql  = "INSERT INTO inv_farmacias_tiempoentrega_medicamentos (";
    $sql .= "       empresa_id     , ";
	  $sql .= "       tiempo_entrega      ";
	  $sql .= "       ) ";
      $sql .= "VALUES ( ";
      $sql .= "        '".$datos['empresa_id']."',";
	    $sql .= "        '".$datos['tiempo_entrega']."'";
	    $sql .= "        ";
      $sql .= "       ); ";			
		//$this->debug=true;
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
				else
				return true;
				
			$rst->Close();
			
		}
    

	
	
	}
	
?>