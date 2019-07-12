<?php

class DMLs_repositorio extends ConexionBD {

    var $offset;
    var $paginaActual;
    var $conteo;
    var $limit;

    /*     * *******************************
     * Constructor
     * ******************************* */

    function DMLs_repositorio() {
        
    }

    /*     * ************************************************************************************
     * Listar tipo de archivos parametrizados para el cargue al repositorio
     * de documentos
     * @return array
     * ************************************************************************************* */

    function Listar_TipoArch($tipo) {
		$condicion = "";
		if($tipo ===1){
			//$condicion = "WHERE tipo_nombre NOT IN ('SELECTIVO','CORTES','GLOSAS','ORDEN_SUMINISTRO','ORDEN_REQUISICION')";
			$condicion = "WHERE tipo_archivo_id IN ('3','5','7','10','11','12','13','14')";
		}
        $sql = "SELECT tipo_archivo_id AS cod_tipo, tipo_nombre FROM
                      tipo_archivos_repositorio ".$condicion."
                   ORDER BY tipo_nombre ASC ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $tipos = Array();
        while (!$resultado->EOF) {
            $tipos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        return $tipos;
    }
	
	
	
	
	 /*     * ************************************************************************************
     * Listar tipo de Documento
     * de documentos
     * @return array
     * ************************************************************************************* */

    function Listar_TipoDocumento($tipo) {
		$condicion = "";
		if($tipo ===1){
			//$condicion = "WHERE tipo_nombre NOT IN ('SELECTIVO','CORTES','GLOSAS','ORDEN_SUMINISTRO','ORDEN_REQUISICION')";
			$condicion = "WHERE tipo_archivo_id IN ('11','7','10','12','13','14')";
		}
        $sql = "SELECT tipo_archivo_id AS cod_tipo, tipo_nombre FROM
                      tipo_archivos_repositorio ".$condicion."
                   ORDER BY tipo_nombre ASC ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $tipos = Array();
        while (!$resultado->EOF) {
            $tipos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        return $tipos;
    }

    /*     * ************************************************************************************
     * Listar tipo de archivos parametrizados para el cargue al repositorio
     * de documentos con excepcion de documentos que no solicitan 
     * medicamentos en el diligenciamiento de la forma
     * @return array
     * ************************************************************************************* */

    function Listar_TipoArchExc() {
        $sql = "SELECT tipo_archivo_id AS cod_tipo, tipo_nombre FROM
                                tipo_archivos_repositorio
				   WHERE tipo_archivo_id IN (10,5,11,12,13,14)
			  ORDER BY tipo_nombre ASC ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $tipos = Array();
        while (!$resultado->EOF) {
            $tipos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        return $tipos;
    }

    /*     * ************************************************************************************
     * Insertar datos en la tabla de control de archivos cargados al repositorio
     * @return boolean
     * ************************************************************************************* */

    function InsertarTransRepositorio($nombreFile, $size, $type, $user, $request) {

        $return_id = " ";
        $sql = "INSERT INTO esm_documentos_repositorio ";
        $sql .= "                   ( ";
        $sql .= "                     documentos_repositorio_id, ";
        $sql .= "                     tipo_archivo, ";
        $sql .= "                     nombre_archivo, ";
        $sql .= "                     size_archivo_bytes, ";
        $sql .= "                     formato_archivo, ";
		
		$valorCorte;
		
		if($request['valorFacturado'] == ""){
			$valorCorte = 0;
		}else{
		
			$valorCorte = $request['valorFacturado'];
		}
        switch ($request['tipo_arch']) {
            case 1: //Ordenes requisicion
                $sql .= "                     empresa_doc, ";
                $sql .= "                     centro_utilidad_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "	                   dpto_doc, ";
                $sql .= "	                   num_orden_req, ";
                break;

            case 2: //Ordenes suministro
                $sql .= "                     empresa_doc, ";
                $sql .= "                     centro_utilidad_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "	                   num_orden_sum, ";
                break;

            case 3: //Formulas
                $sql .= "                     empresa_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "                     num_formula, ";
                $sql .= "                     tipo_paciente_id, ";
                $sql .= "                     paciente_id, ";
                break;

            case 4: //CTC
                $sql .= "                     empresa_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "                     num_formula, ";
                $sql .= "                     tipo_paciente_id, ";
                $sql .= "                     paciente_id, ";
                $sql .= "                     valor_ctc, ";
                $sql .= "                     nombre_paciente,";
                $sql .= "                     medico_formula,";
                $sql .= "                     medico_autoriza,";
                $sql .= "                     tduracion_tutela,";
                $sql .= "                     tipo_tiempo_duracion,";
                $return_id = ", num_formula";
                break;

            case 5: //Facturas
                $sql .= "                     empresa_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "                     num_factura, ";
                $sql .= "                     tipo_factura, ";
                $sql .= "                     fecha_factura, ";
                $return_id = ", num_factura";
                break;

            case 6: //Glosas
                $sql .= "                     empresa_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "                     num_glosa, ";
                $sql .= "                     num_factura_glosa, ";
                $sql .= "                     valor_glosa, ";
                break;

            case 7: //Informes
                $sql .= "                     empresa_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "                     tipo_informe, ";
                $sql .= "                     nombre_informe, ";
                $sql .= "                     fecha_ini_inf, ";
				//NUEVOS 
                $sql .= "                     selectivo,";
                $sql .= "                     tipo_producto_id,";
                $sql .= " 					  fecha_radicacion, ";
                $sql .= " 					  corte_cant_formulas, ";	
                //$sql .= " 					  valor_corte, ";
                $sql .= "                     corte_entregado_por, ";
                $sql .= "                     corte_auditado_por, ";


                $sql .= "                     tipo_paciente_id, ";
                $sql .= "                     paciente_id, ";
                $sql .= "                     tipo_selectivo, ";
                break;

            case 8: //Pendientes dispensados
                $sql .= "                     empresa_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "                     num_formula, ";
                $sql .= "                     tipo_paciente_id, ";
                $sql .= "                     paciente_id, ";
				
				
                break;

            case 9: //cortes
                $sql .= "                     empresa_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "                     numero_corte, ";
                $sql .= "                     corte_cant_formulas, ";
                $sql .= "                     valor_corte, ";
                $sql .= "                     fecha_corte_ini, ";
                $sql .= "                     fecha_corte_fin, ";
                $sql .= "                     corte_entregado_por, ";
                $sql .= "                     corte_auditado_por, ";
                break;

            case 10: //tutelas
                $sql .= "                     empresa_doc, ";
                $sql .= "                     centro_utilidad_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "	                   nombre_accionante, ";
                $sql .= "	                   nombre_paciente, ";
                $sql .= "                     tipo_paciente_id, ";
                $sql .= "                     paciente_id, ";
                $sql .= "                     tipo_tutela, ";
                $sql .= "                     radicado, ";
                $sql .= "                     sentencia, ";
                $sql .= "                     tduracion_tutela, ";
                $sql .= "                     autoriza_tutela, ";
		$sql .= "                     tipo_tiempo_duracion, ";
                $return_id = ", radicado";
                break;
            case 11: //ALTO COSTO
                $sql .= "                     empresa_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "                     num_formula, ";
                $sql .= "                     tipo_paciente_id, ";
                $sql .= "                     paciente_id, ";
                $sql .= "                     nombre_paciente,";
                $sql .= "                     medico_formula,";                
                $sql .= "                     fecha_entrega,";
                $sql .= "                     observacion,";
                $return_id = ", num_formula";
                break;
            case 12: //CODIGO_2000
                $sql .= "                     empresa_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "                     num_formula, ";
                $sql .= "                     tipo_paciente_id, ";
                $sql .= "                     paciente_id, ";
                $sql .= "                     nombre_paciente,";
                $sql .= "                     medico_formula,";                
                $sql .= "                     fecha_entrega,";
                $sql .= "                     observacion,";
                $return_id = ", num_formula";
                break;
            case 13: //RECOBRO_MAGISTERIO
                $sql .= "                     empresa_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "                     num_formula, ";
                $sql .= "                     tipo_paciente_id, ";
                $sql .= "                     paciente_id, ";
                $sql .= "                     nombre_paciente,";
                $sql .= "                     medico_formula,";                
                $sql .= "                     fecha_entrega,";
                $sql .= "                     observacion,";
                $return_id = ", num_formula";
                break;
            case 14: //	RECOBRO_PASIVO
                $sql .= "                     empresa_doc, ";
                $sql .= "                     bodega_doc, ";
                $sql .= "                     num_formula, ";
                $sql .= "                     tipo_paciente_id, ";
                $sql .= "                     paciente_id, ";
                $sql .= "                     nombre_paciente,";
                $sql .= "                     medico_formula,";                
                $sql .= "                     fecha_entrega,";
                $sql .= "                     observacion,";
                $return_id = ", num_formula";
                break;
        }

        $sql .= "                   usuario_id, ";
        $sql .= "                   fecha_registro ";
        $sql .= "                   ) ";
        $sql .= "       VALUES ";
        $sql .= "                   ( ";
        $sql .= "                    DEFAULT, ";
        $sql .= "                   " . $request['tipo_arch'] . ", ";
        $sql .= "                   '" . $nombreFile . "', ";
        $sql .= "                   '" . $size . "', ";
        $sql .= "                   '" . $type . "', ";

        switch ($request['tipo_arch']) {
		
			
			
            case 1: //Ordenes requisicion
                $sql .= "                   '" . $request['empresa_arch'] . "', ";
                $sql .= "                   '" . $request['centro_utilidad_arch'] . "', ";
                $sql .= "                   '" . $request['bodega_arch'] . "', ";
                $sql .= "	                 '" . $request['dpto_arch'] . "', ";
                $sql .= "	                 '" . $request['num_requisicion'] . "', ";
                break;

            case 2: //Ordenes suministro
                $sql .= "                   '" . $request['empresa_arch'] . "', ";
                $sql .= "                   '" . $request['centro_utilidad_arch'] . "', ";
                $sql .= "                   '" . $request['bodega_arch'] . "', ";
                $sql .= "                   '" . $request['num_suministro'] . "', ";
                break;

            case 3: //Formulas
                $sql .= "                   '" . $request['empresa_arch'] . "', ";
                $sql .= "                   '" . $request['bodega_arch'] . "', ";
                $sql .= "                   '" . $request['num_formula'] . "', ";
                $sql .= "                   '" . $request['tipo_id'] . "', ";
                $sql .= "                   '" . $request['num_id'] . "', ";
                break;

            case 4: //CTC
                $sql .= "                   '" . $request['empresa_id'] . "', ";
                $sql .= "                   '" . $request['bodega'] . "', ";
                $sql .= "                   '" . $request['num_formula'] . "', ";
                $sql .= "                   '" . $request['tipo_id'] . "', ";
                $sql .= "                   '" . $request['num_id'] . "', ";
                $sql .= "                    " . $request['val_ctc'] . ", ";
                $sql .= "                   '" . $request['nombrePaciente'] . "', ";
                $sql .= "                   '" . $request['medicoFormula'] . "', ";
                $sql .= "                   '" . $request['medicoAutoriza'] . "', ";
                $sql .= "                   '" . $request['tiempoDuracion'] . "', ";
                $sql .= "                   '" . $request['tipo_tiem_durCTC'] . "', ";
                break;

            case 5: //Facturas
                $sql .= "                   '" . $request['empresa_arch'] . "', ";
                $sql .= "                   '" . $request['bodega_arch'] . "', ";
                $sql .= "                   '" . $request['num_factura'] . "', ";
                $sql .= "                   '" . $request['tipo_fac'] . "', ";
                $sql .= "                   '" . $request['fecha_fac'] . "'::date, ";
                break;

            case 6: //Glosas
                $sql .= "                   '" . $request['empresa_arch'] . "', ";
                $sql .= "                   '" . $request['bodega_arch'] . "', ";
                $sql .= "                   '" . $request['num_glosa'] . "', ";
                $sql .= "                   '" . $request['num_fac_glo'] . "', ";
                $sql .= "                   " . $request['val_glosa'] . ", ";
                break;

            case 7: //Informes
                $sql .= "                   '" . $request['empresa_id'] . "', ";
                $sql .= "                   '" . $request['bodega'] . "', ";
                $sql .= "                   '" . $request['tipo_infor'] . "', ";
                $sql .= "                   '" . $request['nom_infor'] . "', ";
                $sql .= "                   '" . $request['fecha_infor'] . "'::date, ";
								//NUEVOS
                $sql .= "                   '" . $request['selectivoEstado'] . "', "; 
                $sql .= "                   '" . $request['tipo_pro'] . "', "; 
                $sql .= "                   '" . $request['fecha_infor'] . "'::date, "; 
                $sql .= "                   '" . $request['nom_infor'] . "', "; 
                //$sql .= "                    " .  $valorCorte . ", ";
                $sql .= "                   '" . $request['nomQuienEntrega'] . "', ";
                $sql .= "                   '" . $request['nomQuienRecibe'] . "', ";

                $sql .= "                   '" . $request['tipoIdPaciente'] . "', ";
                $sql .= "                   '" . $request['nro_idenficacion'] . "', ";
                $sql .= "                   '" . $request['tipoSelectivo'] . "', ";
				
				
                break;
			
			
			
			
			
            case 8: //Pendientes dispensados
                $sql .= "                   '" . $request['empresa_arch'] . "', ";
                $sql .= "                   '" . $request['bodega_arch'] . "', ";
                $sql .= "                   '" . $request['num_formula'] . "', ";
                $sql .= "                   '" . $request['tipo_id'] . "', ";
                $sql .= "                   '" . $request['num_id'] . "', ";
                break;

            case 9: //cortes
                $sql .= "                   '" . $request['empresa_arch'] . "', ";
                $sql .= "                   '" . $request['bodega_arch'] . "', ";
                $sql .= "                   '" . $request['num_corte'] . "', ";
                $sql .= "                   '" . $request['cant_form'] . "', ";
                $sql .= "                   " . $request['val_corte'] . ", ";
                $sql .= "                   '" . $request['fecha_ini_corte'] . "'::date, ";
                $sql .= "                   '" . $request['fecha_fin_corte'] . "'::date, ";
                $sql .= "                   '" . $request['entrega'] . "', ";
                $sql .= "                   '" . $request['audita'] . "', ";
                break;

            case 10: //tutelas
                $sql .= "                   '" . $request['empresa_arch'] . "', ";
                $sql .= "                   '" . $request['centro_utilidad_arch'] . "', ";
                $sql .= "                   '" . $request['bodega_arch'] . "', ";
                $sql .= "	            '" . $request['accionante'] . "', ";
                $sql .= "	            '" . $request['nombre_paciente'] . "', ";
                $sql .= "                   '" . $request['tipo_id'] . "', ";
                $sql .= "                   '" . $request['num_id'] . "', ";
                $sql .= "                   '" . $request['tipo_tut'] . "', ";
                $sql .= "                   '" . $request['nradicado'] . "', ";
                $sql .= "                   '" . $request['sentencia'] . "', ";
                $sql .= "                   '" . $request['tduracion'] . "', ";
                $sql .= "                   '" . $request['autoriza'] . "', ";
		$sql .= "                   '" . $request['tipo_tiempo_duracion'] . "', ";
                break;
            case 11: //ALTO COSTO
                $sql .= "                   '" . $request['empresa_id'] . "', ";
                $sql .= "                   '" . $request['bodega'] . "', ";
                $sql .= "                   '" . $request['num_formula'] . "', ";
                $sql .= "                   '" . $request['tipo_id'] . "', ";
                $sql .= "                   '" . $request['num_id'] . "', ";
                $sql .= "                   '" . $request['nombrePaciente'] . "', ";
                $sql .= "                   '" . $request['medicoFormula'] . "', ";
                $sql .= "                   '" . $request['fecha_infor'] . "', ";
                $sql .= "                   '" . $request['observacion'] . "', ";
                break;
            case 12: //CODIGO_2000
                $sql .= "                   '" . $request['empresa_id'] . "', ";
                $sql .= "                   '" . $request['bodega'] . "', ";
                $sql .= "                   '" . $request['num_formula'] . "', ";
                $sql .= "                   '" . $request['tipo_id'] . "', ";
                $sql .= "                   '" . $request['num_id'] . "', ";
                $sql .= "                   '" . $request['nombrePaciente'] . "', ";
                $sql .= "                   '" . $request['medicoFormula'] . "', ";
                $sql .= "                   '" . $request['fecha_infor'] . "', ";
                $sql .= "                   '" . $request['observacion'] . "', ";
                break;
            case 13: //RECOBRO_MAGISTERIO
                $sql .= "                   '" . $request['empresa_id'] . "', ";
                $sql .= "                   '" . $request['bodega'] . "', ";
                $sql .= "                   '" . $request['num_formula'] . "', ";
                $sql .= "                   '" . $request['tipo_id'] . "', ";
                $sql .= "                   '" . $request['num_id'] . "', ";
                $sql .= "                   '" . $request['nombrePaciente'] . "', ";
                $sql .= "                   '" . $request['medicoFormula'] . "', ";
                $sql .= "                   '" . $request['fecha_infor'] . "', ";
                $sql .= "                   '" . $request['observacion'] . "', ";
                break;
            case 14: //RECOBRO_PASIVO
                $sql .= "                   '" . $request['empresa_id'] . "', ";
                $sql .= "                   '" . $request['bodega'] . "', ";
                $sql .= "                   '" . $request['num_formula'] . "', ";
                $sql .= "                   '" . $request['tipo_id'] . "', ";
                $sql .= "                   '" . $request['num_id'] . "', ";
                $sql .= "                   '" . $request['nombrePaciente'] . "', ";
                $sql .= "                   '" . $request['medicoFormula'] . "', ";
                $sql .= "                   '" . $request['fecha_infor'] . "', ";
                $sql .= "                   '" . $request['observacion'] . "', ";
                break;
        }
        $sql .= "                  " . $user . ", ";
        $sql .= "                   now() ";
        $sql .= "                    ) RETURNING documentos_repositorio_id {$return_id}; ";

//         echo "<pre> a";
//          print_r($sql);
//          echo "</pre>";
//          exit(); 
       
        if (!$rst = $this->ConexionBaseDatos($sql)){
            return false;
        }
        $repId = array();
        $repId = $rst->GetRowAssoc($ToUpper = false);
/*
         echo "<pre>".$sql;
        if (!$rst = $this->ConexionBaseDatos($sql)){
         echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>"; exit();
            return false;
        }
        echo "<pre> qqqqqqqqqqqqqqqqqqqqqq"; print_r($rst);exit();
 */

        return $repId;
    }

    /*     * *********************************************
      Obtener listado de codigos en temporal
     * ********************************************** */

    function GetlistaTmp($numero, $tipoArch) {

        if ($tipoArch == 5) {

            $sql = "SELECT codigo_producto, descripcion 
                    FROM productos_docs_repositorio_tmp
                    WHERE tipo_archivo = {$tipoArch}
                    AND  numero_factura = '{$numero}'
            ";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            $datos = array();
            while (!$rst->EOF) {
                $datos[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
        }
        if ($tipoArch == 10 || $tipoArch == 4 || $tipoArch == 11 || $tipoArch == 12 || $tipoArch == 13 || $tipoArch == 14) {

            $sql = "SELECT codigo_producto, descripcion 
                    FROM productos_docs_repositorio_tmp
                    WHERE tipo_archivo = {$tipoArch}
                    AND  radicado = '{$numero}'
            ";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            $datos = array();
            while (!$rst->EOF) {
                $datos[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
        }

        return $datos;
    }

    /*     * ************************************************************************************
     * Guardar medicamentos para el documento a cargar en el rep.
     * @return boolean
     * ************************************************************************************* */

    function SaveProds($repositorioId, $productos, $tipoArch, $numero) {

        foreach ($productos as $key => $value) {
            $sql = "INSERT INTO public.repositorio_detalle ";
            $sql .= "                     ( ";
            $sql .= "                       detalle_repositorio_id,";
            $sql .= "                       repositorio_id,";
            $sql .= "                       codigo_producto,";
            $sql .= "                       descripcion,";
            $sql .= "                       fecha_registro ";
            $sql .= "                     ) ";
            $sql .= "                     VALUES ";
            $sql .= "                     ( ";
            $sql .= "                       DEFAULT, ";
            $sql .= "                       " . $repositorioId . ", ";
            $sql .= "                       '" . $value['codigo_producto'] . "', ";
            $sql .= "                       '" . $value['descripcion'] . "', ";
            $sql .= "                       now() ";
            $sql .= "                      ) ";


            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;
        }

        //borrar tmp
        $sql_auxiliar = " AND radicado='{$numero}'";
        if($tipoArch == 5)
            $sql_auxiliar = " AND numero_factura='{$numero}'";

        $sql = "DELETE FROM productos_docs_repositorio_tmp 
                WHERE  tipo_archivo = {$tipoArch}
                {$sql_auxiliar}
        ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;


        return true;
    }

    /*     * ************************************************************************************
     * Listar registros de los documentos cargados al repositorio
     * @return boolean
     * ************************************************************************************* */

    function Listar_datosRep($filtros, $offset) {
        /* $this->debug=true; */
        if ($filtros['tipo_doc'] == '-1')
            $filtros['tipo_doc'] = "";
        if ($filtros['filtroempresa'] == '-1')
            $filtros['filtroempresa'] = "";
        if ($filtros['filtrobodega'] == '-1')
            $filtros['filtrobodega'] = "";


        if ($filtros['tipo_doc'] != "")
			if ($filtros['tipo_doc'] == "110"){
            $where .= " AND dr.selectivo IN('0','1')";
		   }if ($filtros['tipo_doc'] != "110"){
            $where .= " AND dr.tipo_archivo = " . $filtros['tipo_doc'] . " ";
			}
        if ($filtros['fecha'] != "")
            $where .= " AND dr.fecha_registro::date = '" . $filtros['fecha'] . "'::date ";
        if ($filtros['filtroempresa'] != "")
            $where .= " AND dr.empresa_doc = '" . $filtros['filtroempresa'] . "'  ";
        if ($filtros['filtrobodega'] != "")
            $where .= " AND dr.bodega_doc = '" . $filtros['filtrobodega'] . "'  ";
		
		
        $sql = " 
              SELECT 
				dr.tipo_archivo,
                ar.tipo_nombre,
				dr.nombre_archivo,
				dr.formato_archivo,
				dr.num_orden_req,
				dr.num_orden_sum,
				dr.num_formula,
				dr.paciente_id,
				dr.tipo_paciente_id,
				dr.num_glosa,
				dr.num_factura_glosa,
				dr.numero_corte,
				dr.fecha_corte_ini,
				dr.fecha_corte_fin,
				dr.nombre_informe,
				dr.fecha_ini_inf,
				dr.fecha_fin_inf,
				dr.num_factura,
				dr.fecha_factura,
				dr.tipo_factura,
				dr.tipo_tutela,
				dr.radicado,
				dr.autoriza_tutela,
				
				dr.medico_formula,
				dr.medico_autoriza,
				dr.tduracion_tutela,
				
				
				CASE WHEN dr.tipo_producto_id='1' THEN 'Normales'
                     WHEN dr.tipo_producto_id='2' THEN 'Alto costo'
                     WHEN dr.tipo_producto_id='3' THEN 'Controlados'
                     WHEN dr.tipo_producto_id='4' THEN 'Insumos'
                     WHEN dr.tipo_producto_id='5' THEN 'Nevera'
                      ELSE ' ' 
                  END as desc_tipo_producto,
				dr.corte_entregado_por,
				dr.corte_auditado_por,
				dr.valor_corte,
				dr.valor_ctc,
				CASE WHEN dr.selectivo='0' THEN 'Diario'
                     WHEN dr.selectivo='1' THEN 'Semanal'
                      ELSE ' ' 
                END as selectivo,
				dr.corte_cant_formulas,
				
				CASE WHEN dr.tipo_tiempo_duracion='1' THEN 'Dia'
                     WHEN dr.tipo_tiempo_duracion='2' THEN 'Mes'
                     WHEN dr.tipo_tiempo_duracion='3' THEN 'Año'
                     ELSE ' '
					 END as tipo_tiempo_duracion,
					 
				CASE WHEN dr.tipo_selectivo='1' THEN 'Si'
                     WHEN dr.tipo_tiempo_duracion='0' THEN 'No'
                     ELSE ' '
					 END as tipo_selectivo,observacion,fecha_entrega
				
				
			FROM esm_documentos_repositorio dr,
            		   tipo_archivos_repositorio ar
	                   WHERE dr.tipo_archivo = ar.tipo_archivo_id
	 " . $where . "  ";

        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);

        $sql .= "ORDER BY dr.tipo_archivo, dr.fecha_registro  ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*     * ************************************************************************************
     * Listar medicamentos relacionados en documentos especiales cargados el
     * repositorio
     * @return boolean
     * ************************************************************************************* */

    function Listar_datosRepProd($filtros, $offset) {
	
        /* $this->debug=true; */
        if ($filtros['tipo_doc'] == '-1')
            $filtros['tipo_doc'] = "";

        $sql_auxiliar = array();
        
        if ($filtros['tipo_doc'] != ""){
            $where = " dr.tipo_archivo = " . $filtros['tipo_doc'] . " ";
            array_push($sql_auxiliar, $where);
        }
        if ($filtros['fecha'] != ""){
            $where = " dr.fecha_registro::date = '" . $filtros['fecha'] . "'::date ";
            array_push($sql_auxiliar, $where);
        }
        if ($filtros['codigoid'] != ""){
            $where = " drd.codigo_producto = '" . $filtros['codigoid'] . "'  ";
            array_push($sql_auxiliar, $where);
        }
        if ($filtros['descripcion'] != ""){
            $where = " drd.descripcion ILIKE '%" . $filtros['descripcion'] . "%'   ";
            array_push($sql_auxiliar, $where);
        }
		
        $where = join(" AND ",$sql_auxiliar);
	 $sql = " 
		SELECT	emp.razon_social,
				dr.empresa_doc,
                dr.bodega_doc,
				dr.nombre_archivo,
				dr.formato_archivo,
				dr.num_orden_req,
				dr.num_orden_sum,
				dr.num_formula,
				dr.paciente_id,
				dr.nombre_paciente, 
				dr.tipo_tutela,
				dr.radicado,
				drd.codigo_producto,
				drd.descripcion,
				dr.fecha_registro,
                                dr.observacion,
                                dr.fecha_entrega
		FROM esm_documentos_repositorio dr,repositorio_detalle drd, empresas emp
		WHERE
		     dr.documentos_repositorio_id = drd.repositorio_id 
			  AND emp.empresa_id = dr.empresa_doc AND 
	    ".$where."  ";
		
        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);

        $sql .= "ORDER BY dr.fecha_registro  ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* Busqueda de producto en inventarios bodega */

    function BuscarProducto($empresa_id, $bodega, $aumento, $aumento2, $offset) {

        $sql = "SELECT DISTINCT
                b.codigo_producto,
                fc_descripcion_producto(b.codigo_producto) AS descripcion,
		a.unidad_id,
		c.descripcion as descripcion_unidad,
                a.contenido_unidad_venta,
                h.descripcion as laboratorio					  
                FROM inventarios as b, inventarios_productos as a, unidades as c, inv_laboratorios as h
                WHERE b.codigo_producto = a.codigo_producto  
                " . $aumento . "
		" . $aumento2 . "
		AND b.empresa_id = '{$empresa_id}'        
                AND a.unidad_id = c.unidad_id
                AND a.clase_id = h.laboratorio_id ";

        /* echo "<pre>" ;
          var_dump($sql);
          echo "</pre>" ;
          exit(); */

        $this->ProcesarSqlConteoXjx("SELECT COUNT(*) FROM (" . $sql . ") A", 10, $offset);

        $sql .= " LIMIT " . $this->limit . " OFFSET " . $this->offset . "";
        //RETURN $sql;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF) {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        return $cuentas;
    }

    /*     * ******************************************************************************
     * Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
     * importantes a la hora de referenciar al paginador
     * 
     * @param String Cadena que contiene la consulta sql del conteo 
     * @param int numero que define el limite de datos,cuando no se desa el del 
     *        usuario,si no se pasa se tomara por defecto el del usuario 
     * @return boolean 
     * ******************************************************************************* */

    function ProcesarSqlConteoXjx($consulta, $limite = null, $offset = null) {
        $this->offset = 0;
        $this->paginaActual = 1;
        if ($limite == null) {
            $this->limit = GetLimitBrowser();
        } else {
            $this->limit = $limite;
        }

        if ($offset) {
            $this->paginaActual = intval($offset);
            if ($this->paginaActual > 1) {
                $this->offset = ($this->paginaActual - 1) * ($this->limit);
            }
        }

        if (!$result = $this->ConexionBaseDatos($consulta))
            return false;

        if (!$result->EOF) {
            $this->conteo = $result->fields[0];
            $result->MoveNext();
        }
        $result->Close();


        return true;
    }

    /* Guardar productos de tutela en tabla temporal */

    function SaveProdsTemp($tipoArch, $codigo, $descripcion, $numero) {

        $campo_auxiliar = "";

        if ($tipoArch == 10 || $tipoArch == 4 || $tipoArch == 11 || $tipoArch == 12 || $tipoArch == 13 || $tipoArch == 14) { //Tutela CTC
            $campo_auxiliar = 'radicado';
        }
        if ($tipoArch == 5 ) {
            $campo_auxiliar = 'numero_factura';
        }


        $sql = "INSERT INTO productos_docs_repositorio_tmp  (id, tipo_archivo, codigo_producto, descripcion, fecha_registro, {$campo_auxiliar})
                VALUES (DEFAULT, {$tipoArch}, '{$codigo}', '{$descripcion}', NOW(), '{$numero}')";

        /* echo "<pre>";
          var_dump($sql);
          echo "</pre>";
          exit(); */


        if (!$result = $this->ConexionBaseDatos($sql))
            return false;


        return true;
    }

    /* Borrar productos temporales del doc tutela */

    function BorraProdsTmp($tipoArch, $numero, $codigo) {

        $sql_auxiliar = " ";
        if ($tipoArch == 10 || $tipoArch == 4 || $tipoArch == 11 || $tipoArch == 12 || $tipoArch == 13 || $tipoArch == 14)
            $sql_auxiliar = "AND  radicado = '{$numero}' ";

        if ($tipoArch == 5)
            $sql_auxiliar = "AND  numero_factura = '{$numero}' ";

        $sql = "DELETE FROM productos_docs_repositorio_tmp 
                WHERE  tipo_archivo = {$tipoArch} 
                AND  codigo_producto = '{$codigo}'
                {$sql_auxiliar}";

        /* var_dump($sql);
          exit(); */

        if (!$result = $this->ConexionBaseDatos($sql))
            return false;

        return true;
    }

    /* Obtener productos temporales del doc tutela */

    function GetProdTmp($tipoArch, $numero) {

        $sql_auxiliar = " ";
        if ($tipoArch == 10 || $tipoArch == 4 || $tipoArch == 11 || $tipoArch == 12 || $tipoArch == 13 || $tipoArch == 14)
            $sql_auxiliar = "AND  radicado = '{$numero}' ";

        if ($tipoArch == 5)
            $sql_auxiliar = "AND  numero_factura = '{$numero}' ";

        $sql = "SELECT a.codigo_producto, a.descripcion, a.fecha_registro, a.radicado, 
                case when b.sw_regulado ='1' then 'Si' else 'No' end as regulado
                FROM productos_docs_repositorio_tmp a
                inner join  inventarios_productos b on a.codigo_producto = b.codigo_producto
                WHERE tipo_archivo = {$tipoArch}
                {$sql_auxiliar}";



        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $pdctos = array();
        while (!$rst->EOF) {
            $pdctos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $pdctos;
    }

}

?>