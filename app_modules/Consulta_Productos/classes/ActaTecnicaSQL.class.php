<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: ActaTecnicaSQL.class.php,v 1.0 2010/01/26 22:40:38 sandra Exp $Revision: 1.26 $
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Mauricio Adrian Medina Santacruz 
	*/
	class ActaTecnicaSQL extends ConexionBD
	{
	/*
	* Constructor de la clase
	*/
	function ActaTecnicaSQL(){}

	
  function Buscar_ActasTecnicas($empresa_id,$prefijo,$numero)
    {
            $sql="select
                      a.acta_tecnica_id,
                      a.prefijo,
                      a.numero,
                      a.codigo_producto,
                      fc_descripcion_producto(a.codigo_producto) as producto,
                      a.lote,
                      a.fecha_vencimiento,
                      a.observacion,
                      a.fecha_registro,
                      a.responsable_realiza,
                      a.responsable_verifica,
                      b.nombre
                      from
                      esm_acta_tecnica as a
                      JOIN system_usuarios as b ON (a.usuario_id = b.usuario_id)
                      where
                          a.empresa_id = '".$empresa_id."'
                      and a.prefijo = '".$prefijo."'
                      and a.numero = ".$numero."
                      order by a.fecha_registro; ";
        //print_r($sql);

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }
                $resultado->Close();
                return $cuentas;   
    }
  
  
  
  function Listar_EvaluacionesVisuales()
    {
            $sql="SELECT
                        *
                  FROM
                      esm_evaluacion_visual
                  WHERE
                          sw_estado = '1'; ";
         // print_r($sql);

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }

                $resultado->Close();

                return $cuentas;   
    } 
    
    function BuscarItem($empresa_id,$prefijo,$numero,$movimiento_id)
    {
            $sql="SELECT
                        fc_descripcion_producto_alterno(invp.codigo_producto) as descripcion_producto,
                        tmp.*,
                        invp.presentacioncomercial_id,
                        invp.cantidad as precantidad,
                        invp.codigo_invima,
                        fab.descripcion as fabricante
                  FROM
                      inv_bodegas_movimiento_d tmp,
                      inventarios_productos invp,
                      inv_fabricantes fab
                  WHERE
                          tmp.movimiento_id = ".$movimiento_id."
                    and   tmp.empresa_id = '".$empresa_id."'
                    and   tmp.prefijo = '".$prefijo."'
                    and   tmp.numero = ".$numero."
                    and   tmp.codigo_producto = invp.codigo_producto
                    and   invp.fabricante_id = fab.fabricante_id
                          ; ";
        // print_r($sql);

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }
                $resultado->Close();
                return $cuentas;   
    }

	
	/*
	* Funcion de Guardar Productos en la orden de Compra, en caso de un producto
	* llegue con diferentes lotes.
	*/
    function Insertar_ActaTmp($Formulario,$query)
    {
              
        $sql  = "INSERT INTO esm_acta_tecnica (";
        $sql .= "       acta_tecnica_id, ";
        $sql .= "       empresa_id, ";
        $sql .= "       centro_utilidad, ";
        $sql .= "       bodega, ";
        $sql .= "       usuario_id, ";
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
        $sql .= "       numero,     ";
        $sql .= "       movimiento_id     ";
        $sql .= ") ";
        $sql .= "VALUES ( ";
        $sql .= "        default, ";
        $sql .= "        '".$Formulario['empresa_id']."', ";
        $sql .= "        '".$Formulario['centro_utilidad']."', ";
        $sql .= "        '".$Formulario['bodega']."', ";
        $sql .= "        ".$Formulario['usuario_id'].", ";
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
        $sql .= "        ".$Formulario['numero'].", ";
        $sql .= "        ".$Formulario['movimiento_id']." ";
        $sql .= "       )RETURNING(acta_tecnica_id); ";
	
	if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];
                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }  
          foreach($Formulario as $key => $valor)
									{
									    if(is_numeric($key))
										{
										$query .= "	INSERT INTO esm_acta_tecnica_evaluacion_visual( ";
										$query .= "	acta_tecnica_id,  ";
										$query .= "	evaluacion_visual_id,  ";
										$query .= "	observaciones,  ";
										$query .= "	sw_cumple  ";
										$query .= "	) ";
										$query .= "VALUES ( ";
										$query .= "  ".$cuentas['acta_tecnica_id'].", ";
										$query .= "  ".$key.", ";
										$query .= "  '".$Formulario['evaluacion_final_otro']."', ";
										$query .= "  '".$valor['sw_cumple']."' ";
									   $query .= "       ); ";
										}
									
                  }
              if(!empty($query))
              {
              //print_r($query);
              $result = $this->ConexionBaseDatos($query);
              }
      return true;
    }
	
	/*
	* Funcion de Guardar Productos en la orden de Compra, en caso de un producto
	* llegue con diferentes lotes.
	*/
    function Modificar_ActaTmp($Formulario,$query)
    {
        $sql  = "UPDATE esm_acta_tecnica SET ";
        $sql .= "       codigo_producto = '".$Formulario['codigo_producto']."',     ";
        $sql .= "       lote = '".$Formulario['lote']."',     ";
        $sql .= "       fecha_vencimiento = '".$Formulario['fecha_vencimiento']."',     ";
        $sql .= "       numero_factura = '".$Formulario['numero_factura']."',     ";
        $sql .= "       numero_remision = '".$Formulario['numero_remision']."',     ";
        $sql .= "       registro_sanitario = '".$Formulario['registro_sanitario']."',     ";
        $sql .= "       argumentacion_doble_muestreo = '".$Formulario['argumentacion_doble_muestreo']."',     ";
        $sql .= "       total_corrugadas = '".$Formulario['total_corrugadas']."',     ";
        $sql .= "       unidad_corrugadas = '".$Formulario['unidad_corrugadas']."',     ";
        $sql .= "       unidad_corrugadas_a_muestrear = '".$Formulario['unidad_corrugadas_a_muestrear']."',     ";
        $sql .= "       corrugadas_a_muestrear = '".$Formulario['corrugadas_a_muestrear']."',     ";
        $sql .= "       sw_concepto_calidad = '".$Formulario['sw_concepto_calidad']."',     ";
        $sql .= "       observacion = '".$Formulario['observacion']."',     ";
        $sql .= "       responsable_realiza = '".$Formulario['responsable_realiza']."',     ";
        $sql .= "       responsable_verifica = '".$Formulario['responsable_verifica']."',     ";
        $sql .= "       cantidad = ".$Formulario['cantidad'].",     ";
        $sql .= "       c_nc_lote = '".$Formulario['c_nc_lote']."',     ";
        $sql .= "       c_nc_vencimiento = '".$Formulario['c_nc_vencimiento']."'     ";
        $sql .= " ";
        $sql .= " WHERE ";
        $sql .= "         acta_tecnica_id = ".$Formulario['acta_tecnica_id']."; ";
        $sql .= "  ";
		$sql .= "  ".$query;
			//print_r($sql);
		if(!$result = $this->ConexionBaseDatos($sql))
				return $this->frmError['MensajeError'];
	    else
         return true;
			$result->Close();   
    }
	
	function BuscarResgistroActa($empresa_id,$prefijo,$numero,$movimiento_id)
    {
            $sql="SELECT
                        *
                  FROM
                      esm_acta_tecnica
                  WHERE
                          movimiento_id = ".$movimiento_id."
                    and   prefijo = '".$prefijo."'
                    and   numero = ".$numero."
                    and   empresa_id = '".$empresa_id."'; ";
        //print_r($sql);

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }
                $resultado->Close();
                return $cuentas;   
    }
	
	function BuscarItem_EVisual($acta_tecnica_id,$evaluacion_visual_id)
    {
            $sql="SELECT
                        *
                  FROM
                      esm_acta_tecnica_evaluacion_visual
                  WHERE
                             acta_tecnica_id = ".$acta_tecnica_id."
                      and    evaluacion_visual_id = ".$evaluacion_visual_id."

					";
        // print_r($sql);

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }
                $resultado->Close();
                return $cuentas;   
    }
    
    function ActasTecnicas_Temporales($usuario_id,$doc_tmp_id)
    {
            $sql="SELECT
                        *
                  FROM
                      esm_acta_tecnica_tmp
                  WHERE
                          doc_tmp_id = ".$doc_tmp_id."
                    and   usuario_id = ".$usuario_id."; ";
        //print_r($sql);

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }
                $resultado->Close();
                return $cuentas;   
    }
    
    function EvaluacionesVisuales_Temporales($usuario_id,$doc_tmp_id)
    {
            $sql="SELECT
                        *
                  FROM
                      esm_acta_tecnica_evaluacion_visual_tmp
                  WHERE
                          doc_tmp_id = ".$doc_tmp_id."
                    and   usuario_id = ".$usuario_id."; ";
        //print_r($sql);

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }
                $resultado->Close();
                return $cuentas;   
    }
    
    /*
    * Funcion de Guardar Productos en la orden de Compra, en caso de un producto
    * llegue con diferentes lotes.
    */
    function Insertar_Acta($datos,$EvaluacionesVisuales_Productos,$docs)
    {
              
        foreach($datos as $key => $valor)
        {
        $sql = "INSERT INTO esm_acta_tecnica (";
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
        $sql .= ") ";
        $sql .= "VALUES ( ";
        $sql .= "        default, ";
        $sql .= "        '".$valor['empresa_id']."', ";
        $sql .= "        '".$valor['centro_utilidad']."', ";
        $sql .= "        '".$valor['bodega']."', ";
        $sql .= "        ".UserGetUID().", ";
        $sql .= "        ".$valor['orden_pedido_id'].", ";
        $sql .= "        '".$valor['codigo_producto']."', ";
        $sql .= "        '".$valor['lote']."', ";
        $sql .= "        '".$valor['fecha_vencimiento']."', ";
        $sql .= "        '".$valor['numero_factura']."', ";
        $sql .= "        '".$valor['numero_remision']."', ";
        $sql .= "        '".$valor['registro_sanitario']."', ";
        $sql .= "        '".$valor['argumentacion_doble_muestreo']."', ";
        $sql .= "        '".$valor['total_corrugadas']."', ";
        $sql .= "        '".$valor['unidad_corrugadas']."', ";
        $sql .= "        '".$valor['unidad_corrugadas_a_muestrear']."', ";
        $sql .= "        '".$valor['corrugadas_a_muestrear']."', ";
        $sql .= "        '".$valor['sw_concepto_calidad']."', ";
        $sql .= "        '".$valor['observacion']."', ";
        $sql .= "        '".$valor['responsable_realiza']."', ";
        $sql .= "        '".$valor['responsable_verifica']."', ";
        $sql .= "        ".$valor['cantidad'].", ";
        $sql .= "        '".$valor['c_nc_lote']."', ";
        $sql .= "        '".$valor['c_nc_vencimiento']."', ";
        $sql .= "        '".$docs['prefijo']."', ";
        $sql .= "        ".$docs['numero']." ";
        $sql .= "       )RETURNING(acta_tecnica_id); ";
        $query ="";        
        if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];
                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }
                
            foreach($EvaluacionesVisuales_Productos as $key => $evp)
            {
                if($valor['doc_tmp_id']==$evp['doc_tmp_id'] && 
                   $valor['item_id']==$evp['item_id'] &&
                   $valor['usuario_id']==$evp['usuario_id'])
                   {
                $query .= " INSERT into esm_acta_tecnica_evaluacion_visual ( ";
                $query .= " acta_tecnica_id, ";
                $query .= " evaluacion_visual_id, ";
                $query .= " observaciones, ";
                $query .= " sw_cumple ) ";
                $query .= " VALUES ( ";
                $query .= " ".$cuentas['acta_tecnica_id'].", ";
                $query .= " ".$evp['evaluacion_visual_id'].", ";
                $query .= " '".$evp['evaluacion_final_otro']."', ";
                $query .= " '".$evp['sw_cumple']."' ";
                $query .= ");";
                  }
                   
            }
             // print_r($sql);
            //  print_r($query);
              if(!empty($query))
              {
              $result = $this->ConexionBaseDatos($query);
              }
        }
			//$resultado->Close(); 
    }
       
}
 ?>