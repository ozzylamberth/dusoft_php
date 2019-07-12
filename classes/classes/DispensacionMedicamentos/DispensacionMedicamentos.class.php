<?php

/**
* $Id: DispensacionMedicamentos.class.php,v 1.10 
*/

/**
* Clase para la dispensacion o despacho de Medicamentos
*
* @author Sandra Viviana Pantoja Torres
* @version 1.0 
* @package SIIS
*/
class DispensacionMedicamentos extends  ConexionBD
{
  /*
		* Constructor de la clase
	*/
    
 
    function DispensacionMedicamentos()
    {
      
    }
	
	 /*
		* Funcion donde se Consultan  la numeracion y el tipo de documento de acuerdo a la empresa.
		* @param string $empresa cadena con el tipo id de la empresa
		* @param string $centro cadena con el tipo id del centro de utilidad
		* @param string $bodega  cadena con el tipo id de la bodega
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function Seleccionbodegas_doc_numeraciones($empresa,$centro,$bodega)
		{
                   $sql = " SELECT   bodegas_doc_id,
                                     empresa_id,
                                     centro_utilidad,
                                     bodega,
                                     tipo_doc_bodega_id,
                                     prefijo,
                                     descripcion,
                                     numeracion
                             FROM    bodegas_doc_numeraciones
                             WHERE   empresa_id = '".$empresa."'
                             AND     centro_utilidad = '".$centro."' 
                             AND     bodega = '".$bodega."' ; ";
                             
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
		* Funcion donde se Ingresa la informacion a  la tabla inv_bodegas_documentos
		* @param  array $datos vector que contiene la informacion de la consulta anterior 
		* @param integer $total_costo   total costo de los productos despachados
		* @param string $observacion  cadena con la observacion del despacho
		* @param integer $numero  contiene el numero del documento
		* @return boolean.
	*/
    
		function IngresarInv_Bodegas_documentos($datos,$total_costo,$observacion,$numero)
		{
           		
			$this->ConexionTransaccion();
			
			$bodegas_doc_id=$datos[0]['bodegas_doc_id'];
         
        
            
          $sql = "INSERT INTO bodegas_documentos(bodegas_doc_id,numeracion,fecha,
                                                          total_costo,transaccion,observacion,
                                                          usuario_id,fecha_registro)
                                                           VALUES( 
                                                           ".$bodegas_doc_id.",
                                                           ".$numero.",
                                                            now(),
                                                            ".$total_costo.",
                                                            null,
                                                            '".$observacion."',
                                                            ".UserGetUID().",
                                                            now() ); ";
                                     
                                     
                            
			
			  if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
				return true;
		}
    /*
    * Funcion donde segun la opcion  realiza diferentes funciones
    * @param string $tipo_id_paciente  cadenael tipo de identificacion del paciente
    * @param string $paciente_id  cadena con el numero de identificacion del paciente
    * @param string $empresa cadena con el tipo id de la empresa
    * @param integer $bodegas_doc_id id del documento de bodega
    * @param integer $numerac   numeracion del documento
    * @param integer $variableDias  contiene el numero de dias que se ha parametrizado
    * @param  array $dats vector que contiene la informacion del paciente
    * @param integer $total_costo   total costo de los productos despachados
    * @param string $observacion  cadena con la observacion del despacho	
    */
		function MenuOpcion($opcion,$tipo_id_paciente,$paciente_id,$empresa,$bodegas_doc_id,$numerac,$variableDias,$dats,$total_costo,$observacion,$emp,$bodega)
		{
			switch($opcion)
			{
        case '1':
          $inser = $this->IngresarInv_Bodegas_documentos($dats,$total_costo,$observacion,$numerac);
          $datos=$this->SelecrHc_formulacion_despachos_medicamentos($tipo_id_paciente,$paciente_id);
					$info=$this->Insertarhc_formulacion_despachos_medicamentos($datos,$tipo_id_paciente,$paciente_id,$empresa,$bodegas_doc_id,$variableDias);
          $indt=$this->InsertarInv_bodegas_documento_d($datos,$bodegas_doc_id,$numerac,$emp,$bodega);
          $dat =$this->EliminarTodoTemporal($tipo_id_paciente,$paciente_id);
        break;
        default:
        break;                         
      }
		}
    /*
    * Funcion donde se selecciona la informacion de la tabla temporal  hc_formulacion_despachos_medicamentos_tmp
    * @param string $tipo_id_paciente  cadenael tipo de identificacion del paciente
    * @param string $paciente_id  cadena con el numero de identificacion del paciente
    * @return array $datos vector que contiene la informacion de la consulta.
    */ 
		function SelecrHc_formulacion_despachos_medicamentos($tipo_id_paciente,$paciente_id)
		{
            $sql = " SELECT    m.*,
                                 t.*
              FROM    medicamento_farmacia_tmp m,
                       hc_formulacion_despachos_medicamentos_tmp t,
                       inventarios_productos i
              WHERE   m.codigo_medicamento_forumulado=i.codigo_producto
               and    t.codigo_medicamento=i.codigo_producto
               and     m.tipo_id_paciente='".$tipo_id_paciente."'
               AND     m.paciente_id='".$paciente_id."' ;" ;
               
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
    * Funcion donde se inserta la informacion completa del despacho al paciente 
    * @param array $datos vector que contiene la informacion de la consulta a .
    * @param string $tipo_id_paciente  cadenael tipo de identificacion del paciente
    * @param string $paciente_id  cadena con el numero de identificacion del paciente
    * @param string $empresa cadena con el tipo id de la empresa
    * @param integer $bodegas_doc_id id del documento de bodega
    * @param integer $variableDias  contiene el numero de dias que se ha parametrizado
    * @return boolean
    */ 
		function Insertarhc_formulacion_despachos_medicamentos($datos,$tipo_id_paciente,$paciente_id,$empresa,$bodegas_doc_id,$variableDias)
		{
				$this->ConexionTransaccion();
			foreach($datos as $item=>$fila)
			{
			  $total_dias=$fila['totaldias'];
        $dias = $total_dias - $variableDias;
			  
        if($fila['fecha_formulacion']==$fila['fecha_proxima_entrega'])
				{
					$fila['fecha_formulacion'];
          $fdatos = explode("-", $fila['fecha_formulacion']);
					$fecha_formula= $fdatos[2]."-".$fdatos[1]."-".$fdatos[0];

					list($a,$m,$d)=split("-", $fila['fecha_formulacion']);
					
					$fecha_Entrega = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));
					list($a,$m,$d) = split("-",$fecha_Entrega);
          
					$fecha_proxima = date("Y-m-d",(mktime(0,0,0, $m,($d+ $dias),$a)));
  		}
  		else 
  		{
  			list($a,$m,$d) = explode("-", $fila['fecha_proxima_entrega']);

  			$fecha_Entrega = date("Y-m-d",(mktime(0,0,0, $m,($d- $variableDias),$a)));
  			list($a,$m,$d) = split("-",$fecha_Entrega);
  			
  			$fecha_proxima = date("Y-m-d",mktime(0,0,0, $m,($d + $dias),$a));
  		}

  		$sql .= "INSERT INTO   hc_formulacion_despachos_medicamentos
            (
              hc_formuladesp_medicamentos_id,
              tipo_id_paciente,
              paciente_id, 
              codigo_medicamento, 
              fecha_entrega,
              cantidad_entrega, 
              unidad_entrega,
              fecha_proxima_entrega,
              empresa_id, 
              fecha_registro, 
              usuario_id, 
              codigo_medicamento_despachado,
              persona_reclama, 
              persona_reclama_tipo_id,
              persona_reclama_id, 
              observacion,
              bodegas_doc_id
  		      )
            VALUES 
            ( 
              DEFAULT,
              '".$tipo_id_paciente."', 
              '".$paciente_id."',
              '".$fila['codigo_medicamento']."', 
              '".$fecha_Entrega."',
              ".$fila['cantidad_entrega'].",
              '".$fila['unidad_entrega']."',
              '".$fecha_proxima."', 
              '".$empresa."', 
              NOW(), 
              ".UserGetUID().", 
              '".$fila['codigo_medicamento_despachado']."', 
              '".$fila['persona_reclama']."', 
              '".$fila['persona_reclama_tipo_id']."', 
              '".$fila['persona_reclama_id']."',
              '".$fila['observacion']."' ,
               ".$bodegas_doc_id."
             ); " ;
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
      }

      $this->Commit();
      return true;
		}
	  /*
		* Funcion donde se inserta la informacion detallada del despacho al paciente
		* @param array $datos vector que contiene la informacion de la consulta a .
		* @param integer $bodegas_doc_id id del documento de bodega
		* @param integer $numero  contiene el numero del documento
		* @return boolean
	*/ 
       
		function InsertarInv_bodegas_documento_d($datos,$bodegas_doc_id,$numerac,$empresa,$bodega)
		{
      $this->ConexionTransaccion();
			foreach($datos as $item=>$fila)
			{
				$sql = "INSERT INTO bodegas_documentos_d
                  ( 
                    consecutivo,
                    codigo_producto,
                    cantidad,
                    total_costo,
                    bodegas_doc_id,
                    numeracion,
                    fecha_vencimiento,
                    lote
                  )
                  VALUES
                  (
                      NEXTVAL('bodegas_documentos_d_consecutivo_seq'),
                    '".$fila['codigo_medicamento_despachado']."',
                    ".$fila['cantidad_entrega'].",
                    ".$fila['total_costo'].",
                    ".$bodegas_doc_id.",
                    ".$numerac." ,
                    '".$fila['fecha_vencimiento']."' ,
                    '".$fila['lote']."'  
                  ) ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
        
        $sql  = "UPDATE existencias_bodegas ";
        $sql .= "SET    existencia = existencia- ".$fila['cantidad_entrega']." "; 
        $sql .= "WHERE  empresa_id='".$empresa['empresa_id']."' ";
        $sql .= "AND    centro_utilidad = '".$empresa['empresa_id']."' ";
        $sql .= "AND    bodega = '".$bodega."'  ";
        $sql .= "AND    codigo_producto = '".$fila['codigo_medicamento_despachado']."'; ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;

        $sql  = "UPDATE existencias_bodegas_lote_fv ";
        $sql .= "SET    existencia_actual = existencia_actual -".$fila['cantidad_entrega']." "; 
        $sql .= "WHERE  empresa_id='".$empresa['empresa_id']."' ";
        $sql .= "AND    centro_utilidad = '".$empresa['empresa_id']."' ";
        $sql .= "AND    bodega = '".$bodega."'  ";
        $sql .= "AND    codigo_producto = '".$fila['codigo_medicamento_despachado']."' ";
        $sql .= "AND    fecha_vencimiento = '".$fila['fecha_vencimiento']."'  ";
        $sql .= "AND    lote = '".$fila['lote']."' ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
      }
      $this->Commit();
      return true;
		}              
     /*
		* Funcion donde se elimina todos los registros de las tablas temporales
		* @param string $tipo_id_paciente  cadenael tipo de identificacion del paciente
		* @param string $paciente_id  cadena con el numero de identificacion del paciente
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		
    
                
		function EliminarTodoTemporal($tipo_id_paciente,$paciente_id)
		{
   
			$sql8 = "                    DELETE FROM medicamento_farmacia_tmp
                                      WHERE  tipo_id_paciente ='".$tipo_id_paciente."'
                                      AND     paciente_id='".$paciente_id."';
                                     
                                     DELETE  FROM   hc_formulacion_despachos_medicamentos_tmp 
                                     WHERE   tipo_id_paciente ='".$tipo_id_paciente."'
                                     AND     paciente_id='".$paciente_id."' ;";
                                   
                                      if(!$rst = $this->ConexionBaseDatos($sql8))
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
		* Funcion donde se actualiza la numeracion del documento
		* @param integer $bodegas_doc_id id del documento de bodega
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	     
		function AsignarNumeroDocumentoDespacho($bodegas_doc_id)
		{
        
            $sql="BEGIN WORK;  LOCK TABLE bodegas_doc_numeraciones IN ROW EXCLUSIVE MODE ;";
            $sql.="UPDATE bodegas_doc_numeraciones set numeracion=numeracion + 1
                        WHERE  bodegas_doc_id= '".$bodegas_doc_id."'";
                        
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
                   
}//fin de la clase

?>