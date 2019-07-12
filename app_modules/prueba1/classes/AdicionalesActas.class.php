<?php
  class AdicionalesActas extends ConexionBD
  {
    /**
    * Constructor
    */
    function AdicionalesActas(){}
		
    /**************************************************************************************
	* Listar evaluaciones visuales - formulario actas tecnicas
	* 
	* @return array
	***************************************************************************************/
    function Listar_EvaluacionesVisuales()
    {
       $sql="SELECT
                        *
                  FROM
                      esm_evaluacion_visual
                  WHERE
                      sw_estado = '1'; ";

	 if(!$resultado = $this->ConexionBaseDatos($sql))
			return $this->frmError['MensajeError'];

	 $cuentas=Array();
	 while(!$resultado->EOF)
	 {
		  $cuentas[ ] = $resultado->GetRowAssoc($ToUpper = false);
		  $resultado->MoveNext();
	 }

	 $resultado->Close();

	 return $cuentas;   
    }    
    

    /**************************************************************************************
	* Datos adicionales producto
	* 
	* @return array
	***************************************************************************************/    
	function BuscarItem($codigo)
    {
            $sql="SELECT
                        fc_descripcion_producto_alterno(invp.codigo_producto) as descripcion_producto,
                        invp.presentacioncomercial_id as prescom,
                        invp.cantidad as precantidad,
                        invp.codigo_invima as codinv,
                        fab.descripcion as fabricante
                  FROM
                        inventarios_productos invp,
                        inv_fabricantes fab
                  WHERE
                    invp.fabricante_id = fab.fabricante_id
                  AND  invp.codigo_producto = '".$codigo."' ";

   
            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

			$cad=Array();
			while(!$resultado->EOF)
			{
			  $cad = $resultado->GetRowAssoc($ToUpper = false);
			  $resultado->MoveNext();
			}
			
			$resultado->Close();
			return $cad;   
    }
	
	
	
	
	
	
    /**************************************************************************************
	* Dato pais empresas
	* 
	* @return array
	***************************************************************************************/
    function BuscarDatosEmpresa($CodigoEmpresa)
	{
		//$this->debug=true;
      $sql = "SELECT	
              EM.tipo_pais_id
							FROM		empresas EM
							WHERE		
              EM.empresa_id = '".$CodigoEmpresa."';";
						
			//$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
	}
	

    /**************************************************************************************
	* Obtener Nro Orden y cantidad producto relacionado en una factura
	* 
	* @return array datos
	***************************************************************************************/
	function ObtenerOrden($fac,$prov,$cod,$lote)
	{

	 $sql  ="   SELECT  irp.orden_pedido_id AS orden_pedido, sum(irpd.cantidad) AS cantidad ";
	 $sql .="	   FROM inv_facturas_proveedores fp ";
	 $sql .="		 JOIN inv_facturas_proveedores_d fpd ";
	 $sql .="		   ON (fp.codigo_proveedor_id = fpd.codigo_proveedor_id) AND (fp.numero_factura = fpd.numero_factura) ";
	 $sql .="		 JOIN inv_recepciones_parciales irp ";
	 $sql .="		   ON (fpd.recepcion_parcial_id = irp.recepcion_parcial_id) ";
	 $sql .="		 JOIN inv_recepciones_parciales_d irpd ";
	 $sql .="		   ON (irp.recepcion_parcial_id = irpd.recepcion_parcial_id) ";
	 $sql .="	WHERE fp.numero_factura = '".$fac."' ";
	 $sql .="		 AND fp.codigo_proveedor_id =".$prov."  ";
	 $sql .="		 AND fpd.codigo_producto = irpd.codigo_producto ";
	 $sql .="		 AND fpd.lote = irpd.lote ";
	 $sql .="		 AND irpd.codigo_producto = '".$cod."' ";
	 $sql .="		 AND irpd.lote = '".$lote."' ";
	 $sql .="GROUP BY 1 ";
	 
	 if(!$rst = $this->ConexionBaseDatos($sql))
	    return false;
		
	 $datos = array();
	 while(!$rst->EOF)
	 {
	  $datos = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }
   	 $rst->Close();
	 
     return $datos;
    }

    /**************************************************************************************
	* Insertar acta tecnica producto
	* 
	* @return array datos
	***************************************************************************************/
    function Insertar_Acta($Formulario,$Evisual)
    {

			$sql  = "INSERT INTO esm_acta_tecnica ( ";
			$sql .= "       acta_tecnica_id, ";
			$sql .= "       empresa_id, ";
			$sql .= "       centro_utilidad, ";
			$sql .= "       bodega, ";
			$sql .= "       usuario_id, ";
			$sql .= "       orden_pedido_id, ";
			$sql .= "       codigo_producto,     ";
			$sql .= "       lote,     ";
			$sql .= "       fecha_vencimiento,     ";
			$sql .= "       numero_factura,     ";
			$sql .= "       numero_remision,     ";
			$sql .= "       registro_sanitario,     ";
			$sql .= "       argumentacion_doble_muestreo,     ";
			$sql .= "       total_corrugadas,     ";
			$sql .= "       unidad_corrugadas,     ";
			$sql .= "       unidad_corrugadas_a_muestrear,     ";
			$sql .= "       corrugadas_a_muestrear,     ";
			$sql .= "       sw_concepto_calidad,     ";
			$sql .= "       observacion,     ";
			$sql .= "       responsable_realiza,     ";
			$sql .= "       responsable_verifica,     ";
			$sql .= "       cantidad,     ";
			$sql .= "       c_nc_lote,     ";
			$sql .= "       c_nc_vencimiento,     ";
			$sql .= "       prefijo,     ";
			$sql .= "       numero     ";
			$sql .= "       ) ";
			$sql .= " VALUES ( ";
			$sql .= "        DEFAULT, ";
			$sql .= "        '".$Formulario['empresa_id']."', ";
			$sql .= "        '".$Formulario['centro_utilidad']."', ";
			$sql .= "        '".$Formulario['bodega']."', ";
			$sql .= "        ".UserGetUID().", ";
			$sql .= "        ".$Formulario['orden_pedido_id'].", ";
			$sql .= "        '".$Formulario['codigo_producto']."', ";
			$sql .= "        '".$Formulario['lote']."', ";
			$sql .= "        '".$Formulario['fecha_vencimiento']."', ";
			$sql .= "        '".$Formulario['numero_factura']."', ";
			$sql .= "        '".$Formulario['numero_remision']."', ";
			$sql .= "        '".$Formulario['registro_sanitario']."', ";
			$sql .= "        '".$Formulario['argumentacion_doble_muestreo']."', ";
			$sql .= "        '".$Formulario['total_corrugadas']."', ";
			$sql .= "        '".$Formulario['unidad_corrugadas']."', ";
			$sql .= "        '".$Formulario['unidad_corrugadas_a_muestrear']."', ";
			$sql .= "        '".$Formulario['corrugadas_a_muestrear']."', ";
			$sql .= "        '".$Formulario['sw_concepto_calidad']."', ";
			$sql .= "        '".$Formulario['observacion']."', ";
			$sql .= "        '".$Formulario['responsable_realiza']."', ";
			$sql .= "        '".$Formulario['responsable_verifica']."', ";
			$sql .= "        ".$Formulario['cantidad'].", ";
			$sql .= "        '".$Formulario['c_nc_lote']."', ";
			$sql .= "        '".$Formulario['c_nc_vencimiento']."', ";
			$sql .= "        '".$Formulario['prefijo']."', ";
			$sql .= "        ".$Formulario['numero']." ";
			$sql .= "       )RETURNING(acta_tecnica_id); ";

			if(!$resultado = $this->ConexionBaseDatos($sql))
			   return false;
                else
				 
				 $ActaId=Array();
				 while(!$resultado->EOF)
				 {
				   $ActaId = $resultado->GetRowAssoc($ToUpper = false);
				   $resultado->MoveNext();
				 }
			
			$Tkey = 0;
			foreach($Evisual as $key=>$value)
			{
			
				$query  = " INSERT into esm_acta_tecnica_evaluacion_visual ( ";
				$query .= " acta_tecnica_id, ";
				$query .= " evaluacion_visual_id, ";
				$query .= " observaciones, ";
				$query .= " sw_cumple ) ";
				$query .= " VALUES ( ";
				$query .= " ".$ActaId['acta_tecnica_id'].", ";
				$query .= " ".$value['evaluacion_visual_id'].", ";
				$query .= " '".$Formulario['evaluacion_final_otro']."', ";
				
				if($Formulario[$value['evaluacion_visual_id']] = '1')
				  {
			         $query .= " '1' ";
				  }
				  else
					{ $query .= " '0' "; }
				$query .= ");";		
			
				if(!$result = $this->ConexionBaseDatos($query))
				   return false;
				   else
				     $Tkey++;
				   // $mensaje=$this->frmError['MensajeError'];
				   // $objResponse->assign("MensajeDeError","innerHTML",$mensaje_."<br>".$mensaje);  
			}
			//$result->Close(); 	
	        if($Tkey>0)
	          { return true;}
	
	}
	
	






	
  }
?>