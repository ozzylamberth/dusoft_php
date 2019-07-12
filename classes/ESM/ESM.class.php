<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ESM.class.php,v 1.2 2010/04/20 15:38:46 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : ESM
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio a. Medina
  */
  IncludeClass('ConexionBD');
  class ESM extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function ESM(){}
    
    /**
    *
    */
    function ConsultarListaDetalle($Formulario,$empresa_id,$offset)
      {
       
        $sql .= " SELECT 
                         fc_descripcion_producto_alterno(codigo_producto) as descripcion,
                         codigo_producto,
                         resultado,
                         porcentaje,
                         precio,
                         sw_porcentaje,
                         valor_inicial ";
        $sql .= " from (";
                      $sql .= "SELECT 
                                        inv.codigo_producto,
                                        '0' as resultado,
                                        '0' as porcentaje,
                                        '0' as precio,
                                        '0' as sw_porcentaje,
                                        inv.costo as valor_inicial ";
                                        
                      $sql .= " FROM     
                                        inventarios inv,
                                        inventarios_productos invp ";
                      $sql .= " WHERE    
                                           inv.empresa_id = '".$empresa_id."'
                                     and   inv.codigo_producto NOT IN (
                                                                  select codigo_producto
                                                                         from
                                                                         listas_precios_detalle
                                                                         where
                                                                         codigo_lista = '".$Formulario['codigo_lista']."'
                                                                  ) 
                                     and inv.codigo_producto = invp.codigo_producto 
                                     and descripcion ILIKE '%".$Formulario['descripcion']."%' ";
                      $sql .= " UNION ";                      
                      $sql .= " SELECT 
                                        lpd.codigo_producto,
                                        '1' as resultado,
                                        lpd.porcentaje,
                                        lpd.precio,
                                        lpd.sw_porcentaje,
                                        lpd.valor_inicial ";
                      $sql .= " FROM   
                                        listas_precios_detalle lpd,
                                        inventarios_productos invp ";
                      $sql .= " WHERE       lpd.codigo_lista = '".$Formulario['codigo_lista']."' 
                                      and   lpd.empresa_id = '".$empresa_id."'
                                      and   lpd.codigo_producto = invp.codigo_producto 
                                      and   invp.descripcion ILIKE '%".$Formulario['descripcion']."%'
                                      ";
        $sql .= "       ) as T ";
         $sql .= " ORDER BY resultado DESC ";
        
        if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
    
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
 
       
        
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
	 /*   SALDO DEL TOPE */
	 
	     function Consultar_saldo_tope_($fecha_actual,$tipo_id_tercero,$tercero_id)
        {
		
				$sql = "  SELECT  saldo_tope
						  FROM    esm_empresas_topes
						  WHERE   fecha_inicio_tope <= '".$fecha_actual."'
						  AND     fecha_final_tope >= '".$fecha_actual."'
						  AND     tipo_id_tercero = '".$tipo_id_tercero."'
						  AND     tercero_id = '".$tercero_id."'
                   ";
			
				 if(!$rst = $this->ConexionBaseDatos($sql))
          				return false;
          				$datos = array();
          				while(!$rst->EOF)
          				{
          				$datos = $rst->GetRowAssoc($ToUpper);
          				$rst->MoveNext();
          				}
          				$rst->Close();
          				return $datos;
		
      }
	 
	 /*   RESTA  DEL TOPE */
	 
	 
	 function Disminuir_Tope_x_esm($fecha_actual,$tipo_id_tercero,$tercero_id,$valor_t)
		{
			
			$this->ConexionTransaccion();
		
		
			$sql ="  	update   esm_empresas_topes
		                 set     saldo_tope= saldo_tope - ".$valor_t."
						 WHERE   fecha_inicio_tope <= '".$fecha_actual."'
						  AND     fecha_final_tope >= '".$fecha_actual."'
						  AND     tipo_id_tercero = '".$tipo_id_tercero."'
						  AND     tercero_id = '".$tercero_id."'
						  ";
		 
				  
				if(!$rst = $this->ConexionTransaccion($sql))
				
				return false;

				$this->Commit();
				return true;
		}              
	 
	 /* MENU PARA EL TOPE DEL ESM */
	 
	 
	 
	  function Menu($opcion,$valor_t,$tipo_id_tercero,$tercero_id)
		{
			switch($opcion)
			{
				case '1':
				 			
					$today = date("Y-m-d"); 
				    $this->Disminuir_Tope_x_esm($today,$tipo_id_tercero,$tercero_id,$valor_t);
				    $tope=$this->Consultar_saldo_tope_($today,$tipo_id_tercero,$tercero_id);
				
				break;
				default:
				break;                         
      
			 
		    }  
	   }
	  
	  
	  
	  
    
  }
?>