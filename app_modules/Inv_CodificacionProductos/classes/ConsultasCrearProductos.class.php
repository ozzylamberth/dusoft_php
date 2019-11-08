<?php

/**
 * @package IPSOFT-DUSOFT
 * @version $Id: ConsultasCrearProductos.class.php,v 1.8 2010/01/26 18:17:44 sandra Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */

/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 *
 * @package IPSOFT-DUSOFT
 * @version $Revision: 1.8 $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Medina Santacruz
 */
 define('ESTADO', true);//false : pruebas; true : produccion
 
class ConsultasCrearProductos extends ConexionBD {

    /**   
     * Contructor
     */
    function ConsultasCrearproductos() {
        
    }

    //Para el Buscador
    //Busqueda Por ClasesXGrupos
    function ListadoClasesxGrupo($CodigoGrupo) {
   
        $sql = "
          SELECT 
          cla.clase_id,
          cla.descripcion
          FROM 
            inv_clases_inventarios cla
          where
          cla.grupo_id = '" . $CodigoGrupo . "'
          and
          cla.sw_tipo_empresa='" . $_REQUEST['datos']['sw_tipo_empresa'] . "'
          ";

        $sql .= "ORDER BY cla.clase_id ;";

        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function ListadoSubClasesConClase($CodigoGrupo, $CodigoClase) {

        $sql = "
            select
            sub.subclase_id,
            sub.descripcion
            from
            inv_subclases_inventarios sub
            where
            sub.grupo_id = '" . $CodigoGrupo . "'
            and
            sub.clase_id = '" . $CodigoClase . "'
            ";


        $sql .= "ORDER BY sub.subclase_id; ";



        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function BusquedaTitular($Nombre) {
        $sql = "
            select
            titular_reginvima_id as codigo,
            descripcion
            from
            inv_titulares_reginvima
            where
            descripcion ILike '%" . $Nombre . "%';";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function BusquedaFabricante($Nombre) {
        $sql = "select fabricante_id as codigo,
                   descripcion
            from   inv_fabricantes
            where  descripcion ILike '%" . $Nombre . "%'
            ORDER BY descripcion ";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function BuscarAcuerdo228($Codigo) {
        $sql = "
            select
            cod_acuerdo228_id as codigo,
            descripcion
            from
            inv_codigo_acuerdo_228
            where
            cod_acuerdo228_id = '" . $Codigo . "';";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function BuscarMensaje($Codigo) {
        $sql = "
            select
            mensaje_id as codigo,
            descripcion
            from
            inv_mensajes_producto
            where
            mensaje_id = '" . $Codigo . "';";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function ListaEspecialidadxProducto_($CodigoProducto, $offset) {
        $sql = "
            select 
                  esp.especialidad,
                  esp.descripcion
                  from 
                  especialidades esp
                  where
                  esp.especialidad Not In
                  (
                  select 
                  espx.especialidad
                  from
                  inv_especialidad_x_producto espx
                  where
                  espx.codigo_medicamento <> '" . $CodigoProducto . "'
                  )";

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;


        $sql .= "ORDER BY lab.laboratorio_id ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";


        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos; 
    }
	
	 function InsertarMedicamentoNivelUsoAtencion($nivelesAtencion,$CodigoProducto,$metodo,$destino) {
        require_once ('nusoap/lib/nusoap.php');
    
        //$url_wsdl = $this->obtenerUrl(0);
		$url_wsdl = $this->obtenerUrl($destino);
      
        $soapclient = new nusoap_client($url_wsdl, true);
        
        $inputs = array(	
			'codigo_producto' => trim($CodigoProducto),   
			'niveles' => trim($nivelesAtencion)	
        );
		
        $resultado = $soapclient->call($metodo, $inputs);
			
		  /*echo " ---------------------------- \n";
		  echo "<pre> InsertarMedicamentoNivelUsoAtencion Destino = ".$url_wsdl ."\n";
          print_r($inputs);
		  echo "metodo: ". $metodo ."\n";
		  echo " nivelesAtencion: ".$nivelesAtencion."\n";
		  echo "resultado  = ".$resultado."\n";
		  echo "ERROR ".$soapclient->getError()."\n";
          echo "</pre>";*/
          //exit();  
        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }
	

    //para Asignar Nivel de Atencion y Uso a un Medicamento
    function Asignar($Tabla, $Valor, $CodigoProducto, $Campos, $metodo) {
	
        $ProductoxNivel = $CodigoProducto . "" . $Valor;
        //$this->debug=true;
        $sql .= "INSERT INTO " . $Tabla . " ( ";
        $sql .= $Campos;
        $sql .= "       ) ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $ProductoxNivel . "',";
        $sql .= "        '" . $Valor . "',";
        $sql .= "        '" . $CodigoProducto . "'";
        $sql .= "       ); ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    //para Asignar Nivel de Atencion y Uso a un Medicamento
    function Borrar($Tabla, $Valor, $CodigoProducto, $Campo) {

        $ProductoxNivel = $CodigoProducto . "" . $Valor;
        //$this->debug=true;
        $sql .= "DELETE FROM " . $Tabla . " ";
        $sql .= "where  ";
        $sql .= "        " . $Campo;
        $sql .= "        =";
        $sql .= "        '" . $ProductoxNivel . "'";
        $sql .= "; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function Modificar_ProductoMedicamento($Formulario_Productos) {

        $sql = "UPDATE medicamentos";
        $sql .= "       SET ";
        /* $sql .= "       via_administracion_id =";
          $sql .= "        '".$Formulario_Productos['via_administracion_id']."',"; */
        $sql .= "       sw_fotosensible =";
        $sql .= "        '" . $Formulario_Productos['sw_manejo_luz'] . "',";
        $sql .= "       cod_forma_farmacologica =";
        $sql .= "        '" . $Formulario_Productos['cod_forma_farmacologica'] . "',";
        $sql .= "       concentracion_forma_farmacologica =";
        $sql .= "        '" . $Formulario_Productos['concentracion'] . "',";
        $sql .= "       cod_principio_activo =";
        $sql .= "        '" . $Formulario_Productos['cod_principio_activo'] . "',";
        $sql .= "       cod_concentracion =";
        $sql .= "        '" . $Formulario_Productos['cod_concentracion'] . "',";
        $sql .= "       sw_liquidos_electrolitos =";
        $sql .= "        '" . $Formulario_Productos['sw_liquidos_electrolitos'] . "',";
        $sql .= "       sw_uso_controlado =";
        $sql .= "        '" . $Formulario_Productos['sw_uso_controlado'] . "',";
        $sql .= "       sw_antibiotico =";
        $sql .= "        '" . $Formulario_Productos['sw_antibiotico'] . "',";
        $sql .= "       sw_refrigerado =";
        $sql .= "        '" . $Formulario_Productos['sw_refrigerado'] . "',";
        $sql .= "       sw_alimento_parenteral =";
        $sql .= "        '" . $Formulario_Productos['sw_alimento_parenteral'] . "',";
        $sql .= "       sw_alimento_enteral =";
        $sql .= "        '" . $Formulario_Productos['sw_alimento_enteral'] . "',";
        $sql .= "       dias_previos_vencimiento =";
        $sql .= "        '" . $Formulario_Productos['dias_previos_vencimiento'] . "',";
        //campos que vienen a modificar de medicamentos Cosmitet
        $sql .= "       cod_anatomofarmacologico =";
        $sql .= "        '" . $Formulario_Productos['cod_anatofarmacologico'] . "',";
        $sql .= "       sw_pos =";
        $sql .= "        '" . $Formulario_Productos['sw_pos'] . "',";
        $sql .= "       codigo_cum =";
        $sql .= "        '" . $Formulario_Productos['codigo_cum'] . "',";
        $sql .= "       unidad_medida_medicamento_id ="; 
        $sql .= "        '" . $Formulario_Productos['cod_forma_farmacologica'] . "',";//SE MODIFICA 31/12/2015
        $sql .= "       sw_farmacovigilancia =";
        $sql .= "        '" . $Formulario_Productos['sw_farmacovigilancia'] . "',";
        $sql .= "       descripcion_alerta =";
        $sql .= "        '" . $Formulario_Productos['descripcion_alerta'] . "',";
        $sql .= "       usuario_id =";
        $sql .= "        " . UserGetUID() . ",";
        $sql .= "       fecha_registro = ";
        $sql .= "        NOW() ";
        $sql .= " where ";
        $sql .= "codigo_medicamento =";
        $sql .= "        '" . $Formulario_Productos['codigo_producto'] . "';";

        // $this->debug=true;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function GuardarAuditoria_Medicamento($codigo_producto, $version) {
        /* $this->debug=true; */
        $sql = "SELECT guardar_auditoria_medicamentos('" . $codigo_producto . "', " . UserGetUID() . ", '" . $version . "'); ";
        //$this->debug=true;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return $sql;

        $rst->Close();
    }

    function obtenerUrl($destino) {
 
        //$produccion = true;
        $produccion = ESTADO;    
		  
		  //echo "produccion -------> ". $produccion;
		  
        if ($destino == '0') {
            //Sincronizar con Cosmitet      
            $url_produccion = "http://dusoft.cosmitet.net/dusoft/ws/codificacion_productos/ws_producto.php?wsdl";
            $url_pruebas = "http://10.0.0.3/pg9/steven.rojas/asistencial/ws/codificacion_productos/ws_producto.php?wsdl";// Pruebas
			//echo "produccion  ------->>>> " .$produccion . "URL ".$url_pruebas;
			
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '1') {
            //Sincronizar con Dumian
            $url_produccion = "http://10.0.0.62/DUMIAN/ws/codificacion_productos/ws_producto.php?wsdl";
            $url_pruebas = "http://10.0.1.80/dumian/PRUEBAS_DUM/ws/codificacion_productos/ws_producto.php?wsdl";// Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '2') {
            //Sincronizar con Clinica Sta Sof�a
//            $url_produccion = "http://dusoft.cosmitet.net/CSSP/ws/codificacion_productos/ws_producto.php?wsdl";
            $url_produccion = "http://dusoft.clinicasantasofia.com/CSSP/ws/codificacion_productos/ws_producto.php?wsdl";
            $url_pruebas = "http://10.0.0.3/pg9/santa_sofia/CSSP/ws/codificacion_productos/ws_producto.php?wsdl";// Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '3') {
            //Sincronizar con Cucuta
            $url_produccion = "http://10.200.1.13/MD/ws/codificacion_productos/ws_producto.php?wsdl";
            $url_pruebas = "http://10.200.1.22/MD/ws/codificacion_productos/ws_producto.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;  
        } else if ($destino == '4') {
            //Sincronizar con CM
            $url_produccion = "http://10.0.0.62/CMS/ws/codificacion_productos/ws_producto.php?wsdl";
            $url_pruebas = "http://10.0.1.80/dumian/CMS/ws/codificacion_productos/ws_producto.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        }else if ($destino == '5') {
            //Sincronizar con PEÑITAS
            $url_produccion = "http://10.60.1.11/dusoft/ws/codificacion_productos/ws_producto.php?wsdl";
            $url_pruebas = "http://10.0.0.3/pg9/sincelejo/ws/codificacion_productos/ws_producto.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '6') {
            //Sincronizar con CARTAGENA
            $url_produccion = "http://10.245.1.140/dusoft/ws/codificacion_productos/ws_producto.php?wsdl";
            $url_pruebas = "http://10.0.0.3/pg9/cartagena/dusoft/ws/codificacion_productos/ws_producto.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        } 
    } 

    function Modificar_ProductoMedicamento_WS($Formulario_Productos, $destino = '0') {  
        require_once ('nusoap/lib/nusoap.php');
        $url_wsdl = $this->obtenerUrl($destino);
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "modificar_productomedicamento";
	
        $inputs = array(
			'descripcion_cod_anatofarmacologico'=> trim($Formulario_Productos['anatoFarmacologicoDescripcionM']),
		    'descripcion_med_cod'=> trim($Formulario_Productos['comercialFarmacoDescripcionM']),
		    'unidad_dosificacion'=> trim($Formulario_Productos['cod_comerfarmacologicoM']),
		    'sw_dosificacion'=> '2',
			//'descripcion_medida_medicamento'=> trim($Formulario_Productos['cod_medida_medicamento']),
			'descripcion_medida_medicamento'=> trim($Formulario_Productos['cod_forma_farmacologica']),
			'descripcion_principio_activo'=> trim($Formulario_Productos['principioActivoDescripcion']),
			'sw_manejo_luz' => $Formulario_Productos['sw_manejo_luz'],
            'cod_forma_farmacologica' => $Formulario_Productos['cod_forma_farmacologica'],
            'concentracion' => $Formulario_Productos['concentracion'],
            'cod_principio_activo' => $Formulario_Productos['cod_principio_activo'],
            'cod_concentracion' => $Formulario_Productos['cod_concentracion'],
            'sw_liquidos_electrolitos' => $Formulario_Productos['sw_liquidos_electrolitos'],
            'sw_uso_controlado' => $Formulario_Productos['sw_uso_controlado'],
            'sw_antibiotico' => $Formulario_Productos['sw_antibiotico'],
            'sw_refrigerado' => $Formulario_Productos['sw_refrigerado'],
            'sw_alimento_parenteral' => $Formulario_Productos['sw_alimento_parenteral'],
            'sw_alimento_enteral' => $Formulario_Productos['sw_alimento_enteral'],
            'dias_previos_vencimiento' => $Formulario_Productos['dias_previos_vencimiento'],
            'cod_anatofarmacologico' => $Formulario_Productos['anatoFarmacologicoDescripcionM'],
            'sw_pos' => $Formulario_Productos['sw_pos'],
            'codigo_cum' => $Formulario_Productos['codigo_cumM'],
            'unidad_id' => $Formulario_Productos['cod_forma_farmacologica'],//Cambio 31/12/2015
            'sw_farmacovigilancia' => $Formulario_Productos['sw_farmacovigilancia'],
            'descripcion_alerta' => $Formulario_Productos['descripcion_alerta'],
            'usuario_id' => UserGetUID(),
            'codigo_producto' => $Formulario_Productos['codigo_producto'],
			'tipo_pais_titular_reginvima_id' => $Formulario_Productos['tipo_pais_id'],
			'descripcion_titular_reginvima' =>  $Formulario_Productos['descripcion_titular_reginvima_id'],
			'titular_reginvima_id' =>  $Formulario_Productos['titular_reginvima_id'],
			'estado_invima' => $Formulario_Productos['estado_invima']
        );
			
        $resultado = $soapclient->call($function, $inputs);
		
			/*echo "<pre> resultado ---->";
				print_r($resultado);
				
            echo "</pre>";*/
		
		
        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    //Insertar Datos de Insumos/Medicamentos a la Base de datos
    function Modificar_ProductoInsumo($Formulario_Productos) {
        /* $this->debug=true; */
        $sql = "UPDATE inventarios_productos";
        $sql .= "       SET ";
        $sql .= "       descripcion =";
        $sql .= "        '" . $Formulario_Productos['descripcion'] . "',";
        $sql .= "       descripcion_abreviada =";
        $sql .= "        '" . $Formulario_Productos['descripcion_abreviada'] . "',";
        $sql .= "       codigo_cum =";
        $sql .= "        '" . $Formulario_Productos['codigo_cum'] . "',";
        $sql .= "       codigo_alterno =";
        $sql .= "        '" . $Formulario_Productos['codigo_alterno'] . "',";
        $sql .= "       codigo_barras =";
        $sql .= "        '" . $Formulario_Productos['codigo_barras'] . "',";
        $sql .= "       fabricante_id =";
        $sql .= "        '" . $Formulario_Productos['fabricante_id'] . "',";
        $sql .= "       sw_pos =";
        $sql .= "        '" . $Formulario_Productos['sw_pos'] . "',";
        $sql .= "       cod_acuerdo228_id =";
        $sql .= "        '" . $Formulario_Productos['cod_acuerdo228_id'] . "',";
        $sql .= "       cod_forma_farmacologica =";
        $sql .= "        '" . $Formulario_Productos['unidad_id'] . "',";
        $sql .= "       unidad_id =";
        $sql .= "        '" . $Formulario_Productos['unidad_id'] . "',";
        $sql .= "       contenido_unidad_venta =";
        $sql .= "        '" . $Formulario_Productos['cantidad'] . "',";
        $sql .= "       cod_anatofarmacologico =";
        $sql .= "        '" . $Formulario_Productos['cod_anatofarmacologico'] . "',";
		$sql .= "       cod_unspsc =";
        $sql .= "        '" . $Formulario_Productos['cod_unspsc'] . "',";
        $sql .= "       mensaje_id =";
        $sql .= "        '" . $Formulario_Productos['mensaje_id'] . "',";
        $sql .= "       codigo_mindefensa =";
        $sql .= "        '" . $Formulario_Productos['codigo_mindefensa'] . "',";
        $sql .= "       codigo_invima =";
        $sql .= "        '" . $Formulario_Productos['codigo_invima'] . "',";
        $sql .= "       vencimiento_codigo_invima =";
        $sql .= "        '" . $Formulario_Productos['vencimiento_codigo_invima'] . "',";
        $sql .= "       titular_reginvima_id =";
        $sql .= "        '" . $Formulario_Productos['titular_reginvima_id'] . "',";
        $sql .= "       porc_iva =";
        $sql .= "        '" . $Formulario_Productos['porc_iva'] . "',";
        $sql .= "       sw_generico =";
        $sql .= "        '" . $Formulario_Productos['sw_generico'] . "',";
        $sql .= "       sw_venta_directa =";
        $sql .= "        '" . $Formulario_Productos['sw_venta_directa'] . "',";
        $sql .= "       tipo_pais_id =";
        $sql .= "        '" . $Formulario_Productos['tipo_pais_id'] . "',";
        $sql .= "       tipo_producto_id =";
        $sql .= "        '" . $Formulario_Productos['tipo_producto_id'] . "',";
        $sql .= "       presentacioncomercial_id =";
        $sql .= "        '" . $Formulario_Productos['presentacioncomercial_id'] . "',";
        $sql .= "       cantidad =";
        $sql .= "        '" . $Formulario_Productos['cantidad_p'] . "', ";
        $sql .= "       tratamiento_id =";
        $sql .= (($Formulario_Productos['tratamiento_id']) ? $Formulario_Productos['tratamiento_id'] : "NULL" );
        $sql .= "		,usuario_id = " . UserGetUID() . "";
        $sql .= "		,fecha_registro = NOW(), ";
        $sql .= "       cod_adm_presenta =";
        $sql .= "        '" . $Formulario_Productos['cod_presenta'] . "', ";
        $sql .= "       dci_id =";
        $sql .= "        '" . $Formulario_Productos['dci'] . "', ";

        $sql .= "       estado_unico =";
        $sql .= "        '0', ";
//        $sql .= "        '" . trim($Formulario_Productos['estado_unico']) . "', ";

        $sql .= "       sw_solicita_autorizacion =";
        $sql .= "        '" . trim($Formulario_Productos['sw_solicita_autorizacion']) . "', ";

        $sql .= "       sw_regulado =";
        $sql .= "        '" . trim($Formulario_Productos['sw_regulado']) . "', ";

        $sql .= "       rips_no_pos =";
        $sql .= "        '" . trim($Formulario_Productos['rips_no_pos']) . "', ";

        $sql .= "       tipo_riesgo_id =";
        $sql .= "        '" . trim($Formulario_Productos['tipo_riesgo']) . "', ";
		
		$sql .= "       estado_invima =";
        $sql .= "        '" . trim($Formulario_Productos['estado_invima']) . "' ";

        $sql .= "		where ";
        $sql .= "codigo_producto =";
        $sql .= "        '" . $Formulario_Productos['codigo_producto'] . "';";
        //$this->debug=true;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return $sql;

        $rst->Close();
    }

    //Insertar Datos de Auditoria de Modificac�n de Insumos/Medicamentos
    function GuardarAuditoria_ProductoInsumo($codigo_producto, $version) {
        /* $this->debug=true; */
        $sql = "SELECT guardar_auditoria_inventarios_productos('" . $codigo_producto . "', " . UserGetUID() . ", '" . $version . "'); ";
        //$this->debug=true;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return $sql;

        $rst->Close();
    }

    function Modificar_ProductoInsumo_WS($Formulario_Productos, $destino = '0', $estadoUnico = '0') {  
        require_once ('nusoap/lib/nusoap.php');

        if (empty($Formulario_Productos['tratamiento_id'])) {
            $Formulario_Productos['tratamiento_id'] = "NULL";
        }
		
        $url_wsdl = $this->obtenerUrl($destino);
   
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "modificar_productoinsumo";
			
        $inputs = array(
			'descripcion_tratamiento'=>  trim($Formulario_Productos['tratamientosEspecialesDescripcion']),
			'descripcion_cod_anatofarmacologico'=>  trim($Formulario_Productos['anatoFarmacologicoDescripcion']),
			'descripcion_unidad'=>  trim($Formulario_Productos['unidadMedidaDescripcion']), 
			'abreviatura_unidad'=>  trim($Formulario_Productos['unidad_id']),
			'descripcion_med_cod'=>  trim($Formulario_Productos['comercialFarmacoDescripcion']),
			'unidad_dosificacion'=>  trim($Formulario_Productos['cod_comerfarmacologico']),
			'sw_dosificacion'=> '2',
			'grupo_id' =>  trim($Formulario_Productos['grupo_id']),
			'descripcion_grupo' => trim($Formulario_Productos['descripcion_grupo']),
			'sw_medicamento' =>  trim($Formulario_Productos['sw_medicamento']),
			'clase_id'=> trim($Formulario_Productos['clase_id']),
			'descripcion_clase' =>  trim($Formulario_Productos['descripcion_clase']),
			'sw_tipo_empresa' =>  trim($Formulario_Productos['sw_tipo_empresa']),
			'descripcion_subclase' => trim($Formulario_Productos['descripcion_subclase']),
			'molecula_id' =>  trim($Formulario_Productos['molecula_id']),
			'subclase_id' => trim($Formulario_Productos['subclase_id']),
			'descripcion' => $Formulario_Productos['descripcion'],
            'descripcion_abreviada' => $Formulario_Productos['descripcion_abreviada'],
            'codigo_cum' => $Formulario_Productos['codigo_cum'],
            'codigo_alterno' => $Formulario_Productos['codigo_alterno'],
            'codigo_barras' => $Formulario_Productos['codigo_barras'],
            'fabricante_id' => $Formulario_Productos['fabricante_id'],
            'sw_pos' => $Formulario_Productos['sw_pos'],
            'cod_acuerdo228_id' => $Formulario_Productos['cod_acuerdo228_id'],
            'unidad_id' => $Formulario_Productos['unidad_id'],
            'cantidad' => $Formulario_Productos['cantidad'],
            'cod_anatofarmacologico' => $Formulario_Productos['cod_anatofarmacologico'],
            'mensaje_id' => $Formulario_Productos['mensaje_id'],
            'codigo_mindefensa' => $Formulario_Productos['codigo_mindefensa'],
            'codigo_invima' => $Formulario_Productos['codigo_invima'],
            'vencimiento_codigo_invima' => $Formulario_Productos['vencimiento_codigo_invima'],
            'porc_iva' => $Formulario_Productos['porc_iva'],
            'sw_generico' => $Formulario_Productos['sw_generico'],
            'sw_venta_directa' => $Formulario_Productos['sw_venta_directa'],
            'tipo_pais_id' => $Formulario_Productos['tipo_pais_id'],
            'tipo_producto_id' => $Formulario_Productos['tipo_producto_id'],
            'presentacioncomercial_id' => $Formulario_Productos['presentacioncomercial_id'],
            'cantidad_p' => $Formulario_Productos['cantidad_p'],
            'tratamiento_id' => $Formulario_Productos['tratamiento_id'],
            'usuario_id' => '2',//UserGetUID(),
            'cod_presenta' => $Formulario_Productos['cod_presenta'],
            'dci' => $Formulario_Productos['dci'],
            'estado_unico' => '0',//$estadoUnico,//'1', //$Formulario_Productos['estado_unico'], 
            'sw_solicita_autorizacion' => $Formulario_Productos['sw_solicita_autorizacion'],
            'codigo_producto' => $Formulario_Productos['codigo_producto'],
            'rips_no_pos' => trim($Formulario_Productos['rips_no_pos']),
            'tipo_riesgo_id' => trim($Formulario_Productos['tipo_riesgo']),
			'tipo_pais_titular_reginvima_id' => $Formulario_Productos['tipo_pais_id'],
			'descripcion_titular_reginvima' =>  $Formulario_Productos['descripcion_titular_reginvima'],
			'titular_reginvima_id' => $Formulario_Productos['titular_reginvima_id'],
			'estado_invima' => $Formulario_Productos['estado_invima'],
			'cod_unspsc' => $Formulario_Productos['cod_unspsc']
			
			
        );
		
	               
        $resultado = $soapclient->call($function, $inputs);
        
         // echo "<pre> RRESULTADO DE LA URL *****//////-----".$destino."\n";
          /*print_r($inputs);
		  echo "resultado = ".$resultado;
		  echo $soapclient->getError();*/
          //echo "</pre>";
          //exit(); 

        if ($resultado) {
            return true;
        } else {
            echo "<pre> URL: ".$url_wsdl;
            echo "<pre>";
            echo $soapclient->getError();
            return false;
        }
    }

    //Insertar Datos de Insumos/Medicamentos a la Base de datos
    function Insertar_ProductoInsumo($Formulario_Productos) {
        /* $this->debug=true; */
        //print_r()


        $codigo_barras = eregi_replace("'", "-", $Formulario_Productos['codigo_barras']);
        $sql = "INSERT INTO inventarios_productos (";
        $sql .= "       grupo_id, ";
        $sql .= "       clase_id, ";
        $sql .= "       subclase_id,";
        $sql .= "       producto_id,";
        $sql .= "       descripcion,";
        $sql .= "       descripcion_abreviada,";
        $sql .= "       codigo_producto,";
        $sql .= "       codigo_cum,";
        $sql .= "       codigo_alterno,";
        $sql .= "       codigo_barras,";
        $sql .= "       fabricante_id,";
        $sql .= "       sw_pos,";
        $sql .= "       cod_acuerdo228_id,";
        $sql .= "       unidad_id,";
        $sql .= "       contenido_unidad_venta,";
        $sql .= "       cod_anatofarmacologico,";
        $sql .= "       mensaje_id,";
        $sql .= "       codigo_mindefensa,";
        $sql .= "       codigo_invima,";
        $sql .= "       vencimiento_codigo_invima,";
        $sql .= "       titular_reginvima_id,";
        $sql .= "       porc_iva,";
        $sql .= "       sw_generico,";
        $sql .= "       sw_venta_directa,";
        $sql .= "       tipo_pais_id,";
        $sql .= "       tipo_producto_id,";
        $sql .= "       presentacioncomercial_id,";
        $sql .= "       cantidad,";
        $sql .= "       tratamiento_id,";
        $sql .= "       usuario_id,";
        $sql .= "       cod_adm_presenta,";
        $sql .= "       dci_id,";
        $sql .= "       estado_unico,";
        $sql .= "       cod_forma_farmacologica,";
        $sql .= "       sw_solicita_autorizacion, ";
        $sql .= "       sw_regulado, ";
        $sql .= "       rips_no_pos, ";
        $sql .= "       tipo_riesgo_id, ";
		$sql .= "       estado_invima,";
		$sql .= "       cod_unspsc";
        $sql .= "       ) ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $Formulario_Productos['grupo_id'] . "',";
        $sql .= "        '" . $Formulario_Productos['clase_id'] . "',";
        $sql .= "        '" . $Formulario_Productos['subclase_id'] . "',";
        $sql .= "        '" . $Formulario_Productos['producto_id'] . "',";
        $sql .= "        '" . $Formulario_Productos['descripcion'] . "',";
        $sql .= "        '" . $Formulario_Productos['descripcion_abreviada'] . "',";
        $sql .= "        '" . $Formulario_Productos['codigo_producto'] . "',";
        $sql .= "        '" . $Formulario_Productos['codigo_cum'] . "',";
        $sql .= "        '" . $Formulario_Productos['codigo_alterno'] . "',";
        $sql .= "        '" . $codigo_barras . "',";
        $sql .= "        '" . $Formulario_Productos['fabricante_id'] . "',";
        $sql .= "        '" . $Formulario_Productos['sw_pos'] . "',";
        $sql .= "        '" . $Formulario_Productos['cod_acuerdo228_id'] . "',";
        $sql .= "        '" . $Formulario_Productos['unidad_id'] . "',";
        $sql .= "        '" . $Formulario_Productos['cantidad'] . "',";
        $sql .= "        '" . $Formulario_Productos['cod_anatofarmacologico'] . "',";
        $sql .= "        '" . $Formulario_Productos['mensaje_id'] . "',";
        $sql .= "        '" . $Formulario_Productos['codigo_mindefensa'] . "',";
        $sql .= "        '" . $Formulario_Productos['codigo_invima'] . "',";
        $sql .= "        '" . $Formulario_Productos['vencimiento_codigo_invima'] . "',";
        $sql .= "        '" . $Formulario_Productos['titular_reginvima_id'] . "',";
        $sql .= "        '" . $Formulario_Productos['porc_iva'] . "',";
        $sql .= "        '" . $Formulario_Productos['sw_generico'] . "',";
        $sql .= "        '" . $Formulario_Productos['sw_venta_directa'] . "',";
        $sql .= "        '" . $Formulario_Productos['tipo_pais_id'] . "',";
        $sql .= "        '" . $Formulario_Productos['tipo_producto_id'] . "',";
        $sql .= "        '" . $Formulario_Productos['presentacioncomercial_id'] . "',";
        $sql .= "        '" . $Formulario_Productos['cantidad_p'] . "',";
        $sql .= "        " . (($Formulario_Productos['tratamiento_id']) ? $Formulario_Productos['tratamiento_id'] : "NULL" );
        $sql .= "        ," . UserGetUID() . ",";
        $sql .= "        '" . $Formulario_Productos['cod_presenta'] . "', ";
        $sql .= "        '" . $Formulario_Productos['dci'] . "', ";
        $sql .= "        '0', ";
//        $sql .= "        '" . trim($Formulario_Productos['estado_unico']) . "', ";
        $sql .= "        '" . trim($Formulario_Productos['unidad_id']) . "', ";
        $sql .= "        '" . trim($Formulario_Productos['sw_solicita_autorizacion']) . "', ";
        $sql .= "        '" . trim($Formulario_Productos['sw_regulado']) . "', ";
        $sql .= "        '" . trim($Formulario_Productos['rips_no_pos']) . "',";
        $sql .= "        '" . trim($Formulario_Productos['tipo_riesgo']) . "',";
		$sql .= "        '" . trim($Formulario_Productos['estado_invima']) . "',";
		$sql .= "        '" . trim($Formulario_Productos['cod_unspsc']) . "'";
        $sql .= "         ); ";

        //$this->debug=true;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;
        //return $sql;

        $rst->Close();
    }

    function Insertar_ProductoInsumo_WS($Formulario_Productos, $destino = '0', $estadoUnico = '0') {
        $codigo_barras = eregi_replace("'", "-", $Formulario_Productos['codigo_barras']);

        if ($Formulario_Productos['tratamiento_id'] != "") {
            $tratamiento = $Formulario_Productos['tratamiento_id'];
        } else {
            $tratamiento = "NULL";
        }

        require_once ('nusoap/lib/nusoap.php');
		
        $url_wsdl = $this->obtenerUrl($destino);

        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "insertar_productoinsumo";
       /* $inputs = array('grupo_id' => $Formulario_Productos['grupo_id'],
            'clase_id' => $Formulario_Productos['clase_id'],
            'subclase_id' => $Formulario_Productos['subclase_id'],
            'producto_id' => $Formulario_Productos['producto_id'],
            'descripcion' => $Formulario_Productos['descripcion'],
            'descripcion_abreviada' => $Formulario_Productos['descripcion_abreviada'],
            'codigo_producto' => $Formulario_Productos['codigo_producto'],
            'codigo_cum' => $Formulario_Productos['codigo_cum'],
            'codigo_alterno' => $Formulario_Productos['codigo_alterno'],
            'codigo_barras' => $codigo_barras,
            'fabricante_id' => $Formulario_Productos['fabricante_id'],
            'sw_pos' => $Formulario_Productos['sw_pos'],
            'cod_acuerdo228_id' => $Formulario_Productos['cod_acuerdo228_id'],
            'unidad_id' => $Formulario_Productos['unidad_id'],
            'contenido_unidad_venta' => $Formulario_Productos['cantidad'],
            'cod_anatofarmacologico' => $Formulario_Productos['cod_anatofarmacologico'],
            'mensaje_id' => $Formulario_Productos['mensaje_id'],
            'codigo_mindefensa' => $Formulario_Productos['codigo_mindefensa'],
            'codigo_invima' => $Formulario_Productos['codigo_invima'],
            'vencimiento_codigo_invima' => $Formulario_Productos['vencimiento_codigo_invima'],
            'titular_reginvima_id' => $Formulario_Productos['titular_reginvima_id'],
            'porc_iva' => $Formulario_Productos['porc_iva'],
            'sw_generico' => $Formulario_Productos['sw_generico'],
            'sw_venta_directa' => $Formulario_Productos['sw_venta_directa'],
            'tipo_pais_id' => $Formulario_Productos['tipo_pais_id'],     
			'descripcion_titular_reginvima' =>  $Formulario_Productos['descripcion_titular_reginvima'], 
            'tipo_producto_id' => $Formulario_Productos['tipo_producto_id'],
            'presentacioncomercial_id' => $Formulario_Productos['presentacioncomercial_id'],
            'cantidad' => $Formulario_Productos['cantidad_p'],
            'tratamiento_id' => $tratamiento,
            'usuario_id' => UserGetUID(),
            'cod_adm_presenta' => $Formulario_Productos['cod_presenta'],
            'dci_id' => $Formulario_Productos['dci'],
            'estado_unico' => '0', //trim($Formulario_Productos['estado_unico']),
            'cod_forma_farmacologica' => trim($Formulario_Productos['unidad_id']),
            'sw_solicita_autorizacion' => trim($Formulario_Productos['sw_solicita_autorizacion']),
            'rips_no_pos' => trim($Formulario_Productos['rips_no_pos']),
            'tipo_riesgo_id' => trim($Formulario_Productos['tipo_riesgo'])
     
			
        );*/
		 $inputs = array(
			'descripcion_tratamiento'=>  trim($Formulario_Productos['tratamientosEspecialesDescripcion']), //ok
			'descripcion_cod_anatofarmacologico'=>  trim($Formulario_Productos['anatoFarmacologicoDescripcion']),//OK
			'descripcion_unidad'=>  trim($Formulario_Productos['unidadMedidaDescripcion']), //OK
			'abreviatura_unidad'=>  trim($Formulario_Productos['unidad_id']),
			'descripcion_med_cod'=>  trim($Formulario_Productos['comercialFarmacoDescripcion']),
			'unidad_dosificacion'=>  trim($Formulario_Productos['cod_comerfarmacologico']),
			'sw_dosificacion'=> '2',
			'grupo_id' =>  trim($Formulario_Productos['grupo_id']),
			'descripcion_grupo' => trim($Formulario_Productos['descripcion_grupo']),
			'sw_medicamento' =>  trim($Formulario_Productos['sw_medicamento']),
			'clase_id'=> trim($Formulario_Productos['clase_id']),
			'descripcion_clase' =>  trim($Formulario_Productos['descripcion_clase']),
			'sw_tipo_empresa' =>  trim($Formulario_Productos['sw_tipo_empresa']),
			'descripcion_subclase' => trim($Formulario_Productos['descripcion_subclase']),
			'molecula_id' =>  trim($Formulario_Productos['molecula_id']),
			'subclase_id' => trim($Formulario_Productos['subclase_id']),
			'descripcion' => $Formulario_Productos['descripcion'],
            'descripcion_abreviada' => $Formulario_Productos['descripcion_abreviada'],
            'codigo_cum' => $Formulario_Productos['codigo_cum'],
            'codigo_alterno' => $Formulario_Productos['codigo_alterno'],
            'codigo_barras' => $Formulario_Productos['codigo_barras'],
            'fabricante_id' => $Formulario_Productos['fabricante_id'],
            'sw_pos' => $Formulario_Productos['sw_pos'],
            'cod_acuerdo228_id' => $Formulario_Productos['cod_acuerdo228_id'],
            'unidad_id' => $Formulario_Productos['unidad_id'],
            'cantidad' => $Formulario_Productos['cantidad'],
            'cod_anatofarmacologico' => $Formulario_Productos['cod_anatofarmacologico'],
            'mensaje_id' => $Formulario_Productos['mensaje_id'],
            'codigo_mindefensa' => $Formulario_Productos['codigo_mindefensa'],
            'codigo_invima' => $Formulario_Productos['codigo_invima'],
            'vencimiento_codigo_invima' => $Formulario_Productos['vencimiento_codigo_invima'],
            'porc_iva' => $Formulario_Productos['porc_iva'],
            'sw_generico' => $Formulario_Productos['sw_generico'],
            'sw_venta_directa' => $Formulario_Productos['sw_venta_directa'],
            'tipo_pais_id' => $Formulario_Productos['tipo_pais_id'],
            'tipo_producto_id' => $Formulario_Productos['tipo_producto_id'],
            'presentacioncomercial_id' => $Formulario_Productos['presentacioncomercial_id'],
            'cantidad_p' => $Formulario_Productos['cantidad_p'],
            'tratamiento_id' => $Formulario_Productos['tratamiento_id'],
            'usuario_id' => '2',//UserGetUID(),
            'cod_presenta' => $Formulario_Productos['cod_presenta'],
            'dci' => $Formulario_Productos['dci'],
            'estado_unico' => '0',//$estadoUnico, //$Formulario_Productos['estado_unico'], 
            'sw_solicita_autorizacion' => $Formulario_Productos['sw_solicita_autorizacion'],
            'codigo_producto' => $Formulario_Productos['codigo_producto'],
            'rips_no_pos' => trim($Formulario_Productos['rips_no_pos']),
            'tipo_riesgo_id' => trim($Formulario_Productos['tipo_riesgo']),
			'tipo_pais_titular_reginvima_id' => $Formulario_Productos['tipo_pais_id'],
			'descripcion_titular_reginvima' =>  $Formulario_Productos['descripcion_titular_reginvima'],
			'titular_reginvima_id' => $Formulario_Productos['titular_reginvima_id'],
			//NUevos campos
			'contenido_unidad_venta' => $Formulario_Productos['cantidad'],
			'cod_forma_farmacologica' => trim($Formulario_Productos['unidad_id']),
			'cod_adm_presenta' => $Formulario_Productos['cod_presenta'],
			'dci_id' => $Formulario_Productos['dci'],
			'estado_invima' => $Formulario_Productos['estado_invima'],
			'cod_unspsc' => $Formulario_Productos['cod_unspsc']
			
			
        );

        $resultado = $soapclient->call($function, $inputs);
		/*echo "<pre> ---->> DATOS "; 

		print_r($inputs);
		exit();*/
		
		/*echo "Destino ".$destino ." url_wsdl ". $url_wsdl. "<br>";
		echo "<pre> ========== resultado ========== \n <br>"; 
		print_r($resultado);
		echo "<br>ERROR ".$soapclient->getError() ."\n <br>";*/
		
		//exit();
        //return $resultado;
		
  
        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

//Insercion de Medicamentos.
    function Insertar_ProductoMedicamento($Formulario_Productos) {

        //$this->debug=true;
        $sql = "INSERT INTO medicamentos (";
        $sql .= "       codigo_medicamento, ";
        $sql .= "       descripcion_alerta, ";
        $sql .= "       sw_farmacovigilancia,";
        $sql .= "       sw_fotosensible,";


        $sql .= "       cod_anatomofarmacologico,";
        $sql .= "       cod_principio_activo,";
        $sql .= "       cod_forma_farmacologica,";
        $sql .= "       sw_pos,";
        $sql .= "       codigo_cum,";
        $sql .= "       dias_previos_vencimiento,";
        $sql .= "       sw_liquidos_electrolitos,";
        $sql .= "       sw_uso_controlado,";
        $sql .= "       sw_antibiotico,";
        $sql .= "       sw_refrigerado,";
        $sql .= "       sw_alimento_parenteral,";
        $sql .= "       unidad_medida_medicamento_id,";
        $sql .= "       sw_alimento_enteral,";
        $sql .= "       concentracion_forma_farmacologica,";
        $sql .= "       cod_concentracion,";
        $sql .= "       usuario_id ";


        $sql .= "       ) ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $Formulario_Productos['codigo_producto'] . "',";
        $sql .= "        '" . $Formulario_Productos['descripcion_alerta'] . "',";
        $sql .= "        '" . $Formulario_Productos['sw_farmacovigilancia'] . "',";
        $sql .= "        '" . $Formulario_Productos['sw_manejo_luz'] . "',";


        $sql .= "        '" . $Formulario_Productos['cod_anatofarmacologico'] . "',";
        $sql .= "        '" . $Formulario_Productos['cod_principio_activo'] . "',";
        $sql .= "        '" . $Formulario_Productos['cod_forma_farmacologica'] . "',";
        $sql .= "        '" . $Formulario_Productos['sw_pos'] . "',";
        $sql .= "        '" . $Formulario_Productos['codigo_cum'] . "',";
        $sql .= "        '" . $Formulario_Productos['dias_previos_vencimiento'] . "',";
        $sql .= "        '" . $Formulario_Productos['sw_liquidos_electrolitos'] . "',";
        $sql .= "        '" . $Formulario_Productos['sw_uso_controlado'] . "',";
        $sql .= "        '" . $Formulario_Productos['sw_antibiotico'] . "',";
        $sql .= "        '" . $Formulario_Productos['sw_refrigerado'] . "',";
        $sql .= "        '" . $Formulario_Productos['sw_alimento_parenteral'] . "',";
        $sql .= "        '" . $Formulario_Productos['unidad_id'] . "',";
        $sql .= "        '" . $Formulario_Productos['sw_alimento_enteral'] . "',";
        $sql .= "        '" . $Formulario_Productos['concentracion'] . "',";
        $sql .= "        '" . $Formulario_Productos['cod_concentracion'] . "',";
        $sql .= "        " . UserGetUID() . " ";

        $sql .= "       ); ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function Insertar_ProductoMedicamento_WS($Formulario_Productos, $destino = '0') {
        require_once ('nusoap/lib/nusoap.php');

        $url_wsdl = $this->obtenerUrl($destino);
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "insertar_productomedicamento";
        $inputs = array(
			
			'descripcion_cod_anatofarmacologico' => trim($Formulario_Productos['anatoFarmacologicoDescripcionM']),   
			'descripcion_med_cod' => trim($Formulario_Productos['comercialFarmacoDescripcionM']),
			'unidad_dosificacion' => trim($Formulario_Productos['cod_comerfarmacologicoM']),
			'sw_dosificacion' => '2',
			//'descripcion_medida_medicamento' => trim($Formulario_Productos['cod_medida_medicamento']),
			'descripcion_medida_medicamento' => trim($Formulario_Productos['cod_forma_farmacologica']),
			'descripcion_principio_activo' => trim($Formulario_Productos['principioActivoDescripcion']),			
			'codigo_medicamento' => $Formulario_Productos['codigo_producto'],
            'descripcion_alerta' => $Formulario_Productos['descripcion_alerta'],
            'sw_farmacovigilancia' => $Formulario_Productos['sw_farmacovigilancia'],
            'sw_fotosensible' => $Formulario_Productos['sw_manejo_luz'],
            'cod_anatomofarmacologico' => $Formulario_Productos['cod_anatofarmacologico'],
            'cod_principio_activo' => $Formulario_Productos['cod_principio_activo'],
            'cod_forma_farmacologica' => $Formulario_Productos['cod_forma_farmacologica'],
            'sw_pos' => $Formulario_Productos['sw_pos'],
            'codigo_cum' => $Formulario_Productos['codigo_cum'],
            'dias_previos_vencimiento' => $Formulario_Productos['dias_previos_vencimiento'],
            'sw_liquidos_electrolitos' => $Formulario_Productos['sw_liquidos_electrolitos'],
            'sw_uso_controlado' => $Formulario_Productos['sw_uso_controlado'],
            'sw_antibiotico' => $Formulario_Productos['sw_antibiotico'],
            'sw_refrigerado' => $Formulario_Productos['sw_refrigerado'],
            'sw_alimento_parenteral' => $Formulario_Productos['sw_alimento_parenteral'],
            'unidad_medida_medicamento_id' => $Formulario_Productos['unidad_id'],
            'sw_alimento_enteral' => $Formulario_Productos['sw_alimento_enteral'],
            'concentracion_forma_farmacologica' => $Formulario_Productos['concentracion'],
            'cod_concentracion' => $Formulario_Productos['cod_concentracion'],
            'usuario_id' => UserGetUID()
        );
		
        $resultado = $soapclient->call($function, $inputs);
		
		 /* echo "<pre> METODO Insertar_ProductoMedicamento_WS <br>";
		  echo "<pre> Destino <br> ".$destino;
          print_r($inputs);
		
		  echo "resultado  = ".$resultado;
		  echo "<br>ERROR ".$soapclient->getError();
          echo "</pre>";*/
		  
        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    function InsertarEspxProd_($Especialidad, $CodigoProducto) {

        $sql = "INSERT INTO inv_especialidad_x_producto (";
        $sql .= "       especialidad_x_producto_id, ";
        $sql .= "       codigo_medicamento    , ";
        $sql .= "       especialidad     ";
        $sql .= "       ) ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $CodigoProducto . "" . $Especialidad . "',";
        $sql .= "        '" . $CodigoProducto . "',";
        $sql .= "        '" . $Especialidad . "'";
        $sql .= "       ); ";



        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }
	
	
	
	
	
	
    function Medicamento_NivelUso_Atencion($Tabla, $CodigoMedicamento, $campo) {
		
        $sql = "             select 
                             " . $campo . "
                          
                    from
                            " . $Tabla . "
                   where
                          codigo_producto = '" . $CodigoMedicamento . "';";


        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function BuscadorProductosCreados($Grupo_id, $Clase_Id, $SubClase_Id, $Cod_Anatofarmacologico, $Codigo_Barras, $Descripcion) {

        $sql = "
          Select 
                          grp.grupo_id || ' ' ||grp.descripcion as Grupo,
                          cla.clase_id || ' ' || cla.descripcion as Clase,
                          sub.subclase_id || ' ' || sub.descripcion Subclase,
                          prod.codigo_producto,
                          prod.descripcion,
                          uni.descripcion || ' X ' || prod.cantidad as presentacion,
                          imf.descripcion as Forma,
                          prod.porc_iva as iva,
                          prod.estado,
                          grp.sw_medicamento
                    from
                          inv_grupos_inventarios grp,
                          inv_clases_inventarios cla,
                          inv_subclases_inventarios sub,
                          inventarios_productos prod,
                          unidades uni,
                          inv_med_cod_forma_farmacologica imf
                    where
                          prod.grupo_id ILike '%" . $grupo_Id . "%'
                          and
                          prod.clase_id ILike '%" . $Clase_Id . "%'
                          and
                          prod.subclase_id ILike '%" . $Subclase_Id . "%'
                          and
                          prod.cod_anatofarmacologico ILike '%" . $Cod_Anatofarmacologico . "%'
                          and
                          prod.codigo_barras ILike '%" . $Codigo_Barras . "%'
                          and
                          prod.descripcion ILike '%" . $Descripcion . "%' 
                          and
                          prod.subclase_id = sub.subclase_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.clase_id = cla.clase_id
                          and
                          cla.grupo_id = prod.grupo_id
                          and
                          cla.grupo_id = grp.grupo_id
                          and
                          prod.unidad_id = uni.unidad_id
                          and
                          prod.cod_forma_farmacologica = imf.cod_forma_farmacologica;";
    }

    
    function Buscar_Medicamento($CodigoMedicamento) {
        $sql = "SELECT
                        med.sw_fotosensible,
                        med.cod_forma_farmacologica,
                        inv.cod_adm_presenta AS cod_concentracion,
                        med.concentracion_forma_farmacologica,
                        med.sw_farmacovigilancia,
                        med.descripcion_alerta,
                        med.sw_liquidos_electrolitos,
                        med.sw_uso_controlado,
                        med.sw_antibiotico,
                        med.sw_refrigerado,
                        med.sw_alimento_parenteral,
                        med.sw_alimento_enteral,
                        med.dias_previos_vencimiento,
                        med.cod_principio_activo                          
                    FROM
                        medicamentos med, inventarios_productos inv
                    WHERE
                        med.codigo_medicamento = inv.codigo_producto
                        AND med.codigo_medicamento = '" . $CodigoMedicamento . "';";
	//echo "<pre>";print_r($sql);
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Buscar_DetalleMedicamento($CodigoMedicamento) {
        $sql = "SELECT DISTINCT ON (codigo_medicamento)
                             CASE med.sw_fotosensible WHEN 0 THEN 'No' ELSE 'Si' END AS sw_fotosensible,
                             inv.cod_adm_presenta AS cod_concentracion,
                             med.concentracion_forma_farmacologica,
                             CASE med.sw_farmacovigilancia WHEN 0 THEN 'No' ELSE 'Si' END AS sw_farmacovigilancia,
                             med.descripcion_alerta,
                             CASE med.sw_liquidos_electrolitos WHEN 0 THEN 'No' ELSE 'Si' END AS sw_liquidos_electrolitos,
                             CASE med.sw_uso_controlado WHEN 0 THEN 'No' ELSE 'Si' END AS sw_uso_controlado,
                             CASE med.sw_antibiotico WHEN 0 THEN 'No' ELSE 'Si' END AS sw_antibiotico,
                             CASE med.sw_refrigerado WHEN 0 THEN 'No' ELSE 'Si' END AS sw_refrigerado,
                             CASE med.sw_alimento_parenteral WHEN 0 THEN 'No' ELSE 'Si' END AS sw_alimento_parenteral,
                             CASE med.sw_alimento_enteral WHEN 0 THEN 'No' ELSE 'Si' END AS sw_alimento_enteral,
                             med.dias_previos_vencimiento,
                             med.cod_principio_activo,
                             si.descripcion AS descripcion_subclase
                        FROM
                              medicamentos med
                              INNER JOIN inventarios_productos inv ON (med.codigo_medicamento = inv.codigo_producto)
                              INNER JOIN inv_subclases_inventarios si ON (inv.grupo_id = si.grupo_id AND inv.clase_id = si.clase_id AND inv.subclase_id = si.subclase_id)
                        WHERE
                              med.codigo_medicamento = '" . $CodigoMedicamento . "';";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Buscar_AuditoriaMedicamento($CodigoMedicamento) {
        $sql = "SELECT DISTINCT ON (medicamento_auditoria_id)
                             CASE med.sw_fotosensible WHEN 0 THEN 'No' ELSE 'Si' END AS sw_fotosensible,
                             inv.cod_adm_presenta AS cod_concentracion,
                             med.concentracion_forma_farmacologica,
                             CASE med.sw_farmacovigilancia WHEN 0 THEN 'No' ELSE 'Si' END AS sw_farmacovigilancia,
                             med.descripcion_alerta,
                             CASE med.sw_liquidos_electrolitos WHEN 0 THEN 'No' ELSE 'Si' END AS sw_liquidos_electrolitos,
                             CASE med.sw_uso_controlado WHEN 0 THEN 'No' ELSE 'Si' END AS sw_uso_controlado,
                             CASE med.sw_antibiotico WHEN 0 THEN 'No' ELSE 'Si' END AS sw_antibiotico,
                             CASE med.sw_refrigerado WHEN 0 THEN 'No' ELSE 'Si' END AS sw_refrigerado,
                             CASE med.sw_alimento_parenteral WHEN 0 THEN 'No' ELSE 'Si' END AS sw_alimento_parenteral,
                             CASE med.sw_alimento_enteral WHEN 0 THEN 'No' ELSE 'Si' END AS sw_alimento_enteral,
                             med.dias_previos_vencimiento,
                             med.cod_principio_activo,
                             med.fecha_modificacion,
                             med.version,
                             su.nombre,
                             si.descripcion AS descripcion_subclase
                        FROM
                              medicamentos_auditoria med
                              INNER JOIN system_usuarios su ON (med.usuario_id_modificador = su.usuario_id)
                              INNER JOIN inventarios_productos inv ON (med.codigo_medicamento = inv.codigo_producto)
                              INNER JOIN inv_subclases_inventarios si ON (inv.grupo_id = si.grupo_id AND inv.clase_id = si.clase_id AND inv.subclase_id = si.subclase_id)
                        WHERE
                              med.codigo_medicamento = '" . $CodigoMedicamento . "';";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Guardar_AuditoriaEstadoProducto($codigo_producto, $nuevo_estado) {
        $sql .= "INSERT INTO inventarios_productos_auditoria_estado (codigo_producto, nuevo_estado, usuario_id_modificador) 
                    VALUES ('" . $codigo_producto . "', '" . $nuevo_estado . "', " . UserGetUID() . ")";
        /* $this->debug=true; */
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else {
            $documentos = Array();
            while (!$rst->EOF) {
                $documentos = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
            return $documentos;
        }
    }

    function Buscar_AuditoriaEstadoProducto($CodigoMedicamento) {
        $sql = "SELECT DISTINCT ON (ipae.inventario_producto_auditoria_estado_id)
                             CASE ipae.nuevo_estado WHEN 0 THEN 'Inactivo' ELSE 'Activo' END AS nuevo_estado,
                             ipae.fecha_modificacion,
                             su.nombre
                        FROM
                              inventarios_productos_auditoria_estado ipae
                              INNER JOIN system_usuarios su ON (ipae.usuario_id_modificador = su.usuario_id)
                        WHERE
                              ipae.codigo_producto = '" . $CodigoMedicamento . "';";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Buscar_ProductoBarras($CodigoBarras) {
        $codigo_barras = eregi_replace("'", "-", $CodigoBarras);
        $sql = "select 
                              grp.grupo_id || ' ' ||grp.descripcion as Grupo,
                              cla.clase_id || ' ' || cla.descripcion as Clase,
                              sub.subclase_id || ' ' || sub.descripcion Subclase,
                              prod.producto_id,
                              prod.codigo_cum,
                              prod.codigo_alterno,
                              prod.codigo_barras,
                              prod.descripcion,
                              prod.fabricante_id,
                              prod.sw_pos,
                              prod.cod_acuerdo228_id,
                              prod.cod_forma_farmacologica,
                              prod.unidad_id,
                              prod.cantidad,
                              prod.cod_anatofarmacologico,
                              prod.mensaje_id,
                              prod.codigo_mindefensa,
                              prod.codigo_invima,
                              prod.vencimiento_codigo_invima,
                              prod.titular_reginvima_id,
                              prod.porc_iva,
                              prod.sw_generico,
                              prod.sw_venta_directa,
                              prod.tipo_producto_id,
                              fab.descripcion as fabricante,
                              itri.descripcion as titular
                          
                    from
                            inv_grupos_inventarios grp,
                            inv_clases_inventarios cla,
                            inv_subclases_inventarios sub,
                            inventarios_productos prod,
                            inv_fabricantes fab,
                            inv_titulares_reginvima itri
                   where
                          prod.codigo_barras = '" . $codigo_barras . "'
                          and
                          prod.fabricante_id = fab.fabricante_id
                          and
                          prod.titular_reginvima_id = itri.titular_reginvima_id
                          and
                          sub.subclase_id = prod.subclase_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.clase_id = cla.clase_id
                          and
                          cla.grupo_id = prod.grupo_id
                          and
                          grp.grupo_id = prod.grupo_id;";


        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Buscar_Producto($CodigoProducto) {
        $sql = "select  
                              grp.grupo_id || ' ' ||grp.descripcion as Grupo,
                              cla.clase_id || ' ' || cla.descripcion as Clase,
                              sub.subclase_id || ' ' || sub.descripcion as Subclase,
                              sub.subclase_id,
                              prod.grupo_id as grupo_id_producto,
                              prod.clase_id as clase_id_producto,
                              prod.subclase_id as subclase_id_producto,
							  grp.descripcion as descripcionGrupo,
							  grp.sw_medicamento as sw_medicamento,
							  cla.descripcion as descripcionClase,
							  cla.sw_tipo_empresa as sw_tipo_empresa,
							  sub.descripcion as descripcionSubClase,
							  sub.molecula_id,
                              prod.producto_id,
                              prod.codigo_cum,
                              prod.codigo_alterno,
                              prod.codigo_barras,
                              prod.descripcion,
                              prod.descripcion_abreviada,
                              prod.fabricante_id,
                              prod.sw_pos,
                              prod.cod_acuerdo228_id,
                              prod.cod_forma_farmacologica,
                              prod.unidad_id,
                              prod.contenido_unidad_venta as cantidad,
                              prod.cod_anatofarmacologico,
                              prod.mensaje_id,
                              prod.codigo_mindefensa,
                              prod.codigo_invima,
                              prod.vencimiento_codigo_invima,
                              prod.titular_reginvima_id,
                              prod.porc_iva,
                              prod.sw_generico,
                              prod.sw_venta_directa,
                              prod.tipo_producto_id,
                              prod.tipo_pais_id,
                              fab.descripcion as fabricante,
                              itri.descripcion as titular,
                              prod.cantidad as cantidad_p,
                              prod.presentacioncomercial_id,
                              prod.tratamiento_id,
                              prod.cod_adm_presenta,
                              prod.dci_id,
                              prod.estado_unico,
                              prod.sw_solicita_autorizacion,
                              prod.sw_regulado,
                              prod.rips_no_pos,
                              prod.tipo_riesgo_id,
							  prod.estado_invima,
							  prod.cod_unspsc
                    from 
                            inv_grupos_inventarios grp,
                            inv_clases_inventarios cla,
                            inv_subclases_inventarios sub,
                            inventarios_productos prod,
                            inv_fabricantes fab,
                            inv_titulares_reginvima itri
                   where
                          prod.codigo_producto = '" . $CodigoProducto . "' 
                          and prod.fabricante_id = fab.fabricante_id 
                          and prod.titular_reginvima_id = itri.titular_reginvima_id 
                          and sub.subclase_id = prod.subclase_id 
                          and sub.clase_id = prod.clase_id 
                          and sub.grupo_id = prod.grupo_id 
                          and sub.clase_id = cla.clase_id 
                          and cla.grupo_id = prod.grupo_id 
                          and grp.grupo_id = prod.grupo_id;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Buscar_DetalleProducto($CodigoProducto) {
        $sql = "SELECT DISTINCT ON (prod.codigo_producto)
                              grp.grupo_id || ' ' ||grp.descripcion as Grupo,
                              cla.clase_id || ' ' || cla.descripcion as Clase,
                              sub.subclase_id || ' ' || sub.descripcion as Subclase,
                              prod.producto_id,
                              prod.codigo_cum,
                              prod.codigo_alterno,
                              prod.codigo_barras,
                              prod.descripcion,
                              prod.descripcion_abreviada,
                              CASE prod.sw_pos WHEN 0 THEN 'No' ELSE 'Si' END AS sw_pos,
                              CASE prod.cod_acuerdo228_id WHEN '' THEN '--' ELSE prod.cod_acuerdo228_id END AS cod_acuerdo228_id,
                              prod.contenido_unidad_venta as cantidad,
                              prod.cod_anatofarmacologico,
                              prod.mensaje_id,
                              prod.codigo_mindefensa,
                              prod.codigo_invima,
                              prod.vencimiento_codigo_invima,
                              prod.porc_iva,
                              CASE prod.sw_generico WHEN 0 THEN 'No' ELSE 'Si' END AS sw_generico,
                              CASE prod.sw_venta_directa WHEN 0 THEN 'No' ELSE 'Si' END AS sw_venta_directa,
                              fab.descripcion as fabricante,
                              itri.descripcion as titular,
                              prod.cantidad as cantidad_p,
                              prod.cod_adm_presenta,
                              prod.dci_id,
                              CASE prod.sw_regulado WHEN 0 THEN 'No' ELSE 'Si' END AS sw_regulado,
                              CASE prod.rips_no_pos WHEN '' THEN '--' ELSE prod.rips_no_pos END AS rips_no_pos,
                              tp.pais,
                              un.descripcion AS descripcion_unidad,
                              pc.descripcion AS descripcion_presentacion_comercial,
                              mca.descripcion AS anatofarmacologico,
                              tippro.descripcion AS descripcion_tipo_producto,
                              
                              trapro.descripcion AS descripcion_tratamiento_producto
                    FROM
                            inv_grupos_inventarios grp 
                            INNER JOIN inventarios_productos prod ON (grp.grupo_id = prod.grupo_id)
                            INNER JOIN inv_clases_inventarios cla ON (cla.grupo_id = prod.grupo_id)
                            INNER JOIN inv_subclases_inventarios sub ON (sub.subclase_id = prod.subclase_id AND sub.clase_id = prod.clase_id AND sub.grupo_id = prod.grupo_id AND sub.clase_id = cla.clase_id)
                            INNER JOIN inv_fabricantes fab ON (prod.fabricante_id = fab.fabricante_id)
                            LEFT OUTER JOIN inv_titulares_reginvima itri ON (prod.titular_reginvima_id = itri.titular_reginvima_id)
                            LEFT OUTER JOIN tipo_pais tp ON (prod.tipo_pais_id = tp.tipo_pais_id)
                            LEFT OUTER JOIN unidades un ON (prod.unidad_id = un.unidad_id)
                            LEFT OUTER JOIN inv_presentacioncomercial pc ON (prod.presentacioncomercial_id = pc.presentacioncomercial_id)
                            LEFT OUTER JOIN inv_med_cod_anatofarmacologico mca ON (prod.cod_anatofarmacologico = mca.cod_anatomofarmacologico)
                            LEFT OUTER JOIN inv_tipo_producto tippro ON (prod.tipo_producto_id = tippro.tipo_producto_id)
                            
                            LEFT OUTER JOIN inv_tratamientos_productos trapro ON (prod.tratamiento_id = trapro.tratamiento_id)
                   WHERE
                            prod.codigo_producto = '" . $CodigoProducto . "' ";
        //echo $sql;
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Buscar_AuditoriaProducto($CodigoProducto) {
        $sql = "SELECT DISTINCT ON (prod.inventario_producto_auditoria_id)
                              grp.grupo_id || ' ' ||grp.descripcion as Grupo,
                              cla.clase_id || ' ' || cla.descripcion as Clase,
                              sub.subclase_id || ' ' || sub.descripcion as Subclase,
                              prod.producto_id,
                              prod.codigo_cum,
                              prod.codigo_alterno,
                              prod.codigo_barras,
                              prod.descripcion,
                              prod.descripcion_abreviada,
                              CASE prod.sw_pos WHEN 0 THEN 'No' ELSE 'Si' END AS sw_pos,
                              CASE prod.cod_acuerdo228_id WHEN '' THEN '--' ELSE prod.cod_acuerdo228_id END AS cod_acuerdo228_id,
                              prod.contenido_unidad_venta as cantidad,
                              prod.cod_anatofarmacologico,
                              prod.mensaje_id,
                              prod.codigo_mindefensa,
                              prod.codigo_invima,
                              prod.vencimiento_codigo_invima,
                              prod.porc_iva,
                              CASE prod.sw_generico WHEN 0 THEN 'No' ELSE 'Si' END AS sw_generico,
                              CASE prod.sw_venta_directa WHEN 0 THEN 'No' ELSE 'Si' END AS sw_venta_directa,
                              fab.descripcion as fabricante,
                              itri.descripcion as titular,
                              prod.cantidad as cantidad_p,
                              prod.cod_adm_presenta,
                              prod.dci_id,
                              CASE prod.sw_regulado WHEN 0 THEN 'No' ELSE 'Si' END AS sw_regulado,
                              CASE prod.rips_no_pos WHEN '' THEN '--' ELSE prod.rips_no_pos END AS rips_no_pos,
                              tp.pais,
                              un.descripcion AS descripcion_unidad,
                              pc.descripcion AS descripcion_presentacion_comercial,
                              mca.descripcion AS anatofarmacologico,
                              tippro.descripcion AS descripcion_tipo_producto,                              
                              trapro.descripcion AS descripcion_tratamiento_producto,
                              su.nombre AS usuario_modificador,
                              prod.fecha_modificacion,
                              prod.version
                    FROM
                            inv_grupos_inventarios grp 
                            INNER JOIN inventarios_productos_auditoria prod ON (grp.grupo_id = prod.grupo_id)
                            INNER JOIN inv_clases_inventarios cla ON (cla.grupo_id = prod.grupo_id)
                            INNER JOIN inv_subclases_inventarios sub ON (sub.subclase_id = prod.subclase_id AND sub.clase_id = prod.clase_id AND sub.grupo_id = prod.grupo_id AND sub.clase_id = cla.clase_id)
                            INNER JOIN inv_fabricantes fab ON (prod.fabricante_id = fab.fabricante_id)
                            LEFT OUTER JOIN inv_titulares_reginvima itri ON (prod.titular_reginvima_id = itri.titular_reginvima_id)
                            LEFT OUTER JOIN tipo_pais tp ON (prod.tipo_pais_id = tp.tipo_pais_id)
                            LEFT OUTER JOIN unidades un ON (prod.unidad_id = un.unidad_id)
                            LEFT OUTER JOIN inv_presentacioncomercial pc ON (prod.presentacioncomercial_id = pc.presentacioncomercial_id)
                            LEFT OUTER JOIN inv_med_cod_anatofarmacologico mca ON (prod.cod_anatofarmacologico = mca.cod_anatomofarmacologico)
                            LEFT OUTER JOIN inv_tipo_producto tippro ON (prod.tipo_producto_id = tippro.tipo_producto_id)                            
                            LEFT OUTER JOIN inv_tratamientos_productos trapro ON (prod.tratamiento_id = trapro.tratamiento_id)
                            INNER JOIN system_usuarios su ON (prod.usuario_id_modificador = su.usuario_id)
                   WHERE
                            prod.codigo_producto = '" . $CodigoProducto . "' ";
        //echo $sql;
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Lista_Productos_CreadosBuscados($Grupo_Id, $Clase_Id, $SubClase_Id, $Descripcion, $CodAnatofarmacologico, $CodigoBarras, $codigo_producto, $tipo_producto, $offset) {
        $codigo_barras = eregi_replace("'", "-", $CodigoBarras);

        if (!empty($Grupo_Id))
            $filtro .= " and prod.grupo_id = '" . $Grupo_Id . "' ";
        if (!empty($Clase_Id))
            $filtro .= " and prod.clase_id = '" . $Clase_Id . "' ";
        if (!empty($SubClase_Id))
            $filtro .= " and prod.subclase_id = '" . $SubClase_Id . "' ";
        if (!empty($Descripcion))
            $filtro .= " and prod.descripcion ILike '%" . $Descripcion . "%'  ";

        if (!empty($CodAnatofarmacologico))
            $filtro .= " and prod.cod_anatofarmacologico = '" . $CodAnatofarmacologico . "' ";

        if (!empty($CodigoBarras))
            $filtro .= " and prod.codigo_barras ILike '%" . $codigo_barras . "%' ";

        if (!empty($codigo_producto))
            $filtro .= " and prod.codigo_producto ILike '%" . $codigo_producto . "%' ";

        if (!empty($tipo_producto))
            $filtro .= " and prod.sw_regulado  =  '" . $tipo_producto . "' ";

        // $this->debug=true;
        $sql = "
            Select 
                          grp.descripcion as Grupo,
                          cla.descripcion as Clase,
                          sub.descripcion as Subclase,
                          prod.codigo_producto,
                          prod.descripcion,
                          prod.contenido_unidad_venta || '  ' || uni.descripcion as presentacion,
                          prod.porc_iva as iva,
                          prod.estado,
                          grp.sw_medicamento,
                          pc.descripcion || ' X ' || prod.cantidad as presentacion_comercial,
                          CASE WHEN    prod.sw_regulado = '1' THEN 'SI' ELSE 'NO'  END AS producto_regulado
                          
                    from
                          inv_grupos_inventarios grp,
                          inv_clases_inventarios cla,
                          inv_subclases_inventarios sub,
                          inventarios_productos prod,
                          unidades uni,
                          inv_presentacioncomercial as pc
                          
                    where
                         grp.grupo_id = cla.grupo_id
                          and
                          cla.sw_tipo_empresa ='" . $_REQUEST['datos']['sw_tipo_empresa'] . "'
                          and
                          cla.clase_id = sub.clase_id
                          and
                          sub.grupo_id = grp.grupo_id
                          and
                          sub.subclase_id = prod.subclase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          prod.unidad_id = uni.unidad_id
                          and
                          prod.presentacioncomercial_id = pc.presentacioncomercial_id
                          " . $filtro . "
                           ";

        //echo $sql;

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;


        $sql .= "ORDER BY grp.grupo_id, prod.estado DESC,prod.descripcion ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Lista_Productos_Creados($offset) {
        //$this->debug=true;

        $sql = "
              Select 
                          grp.descripcion as Grupo,
                          cla.descripcion as Clase,
                          sub.descripcion as Subclase,
                          prod.codigo_producto,
                          prod.descripcion,
                          prod.contenido_unidad_venta || '  ' || uni.descripcion as presentacion,
                          prod.porc_iva as iva,
                          prod.estado,
                          grp.sw_medicamento,
                          pc.descripcion || ' X ' || prod.cantidad as presentacion_comercial,
                            CASE WHEN    prod.sw_regulado = '1' THEN 'SI' ELSE 'NO'  END AS producto_regulado
                    from
                          inv_grupos_inventarios grp,
                          inv_clases_inventarios cla,
                          inv_subclases_inventarios sub,
                          inventarios_productos prod,
                          unidades uni,
                          inv_presentacioncomercial as pc
                    where
                          grp.grupo_id = cla.grupo_id
                          and
                          cla.sw_tipo_empresa ='" . $_REQUEST['datos']['sw_tipo_empresa'] . "'
                          and
                          cla.clase_id = sub.clase_id
                          and
                          sub.grupo_id = grp.grupo_id
                          and
                          sub.subclase_id = prod.subclase_id
                          and
                          sub.grupo_id = prod.grupo_id
                          and
                          sub.clase_id = prod.clase_id
                          and
                          prod.unidad_id = uni.unidad_id
                          and
                          prod.presentacioncomercial_id = pc.presentacioncomercial_id
                          ";

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;


        $sql .= "ORDER BY grp.grupo_id, prod.estado DESC,prod.descripcion ASC ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";


        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_EspecialidadesxProducto($CodigoProducto) {
        $sql = "
            select 
                      espx.especialidad_x_producto_id as codigo,
                      esp.descripcion
                  from 
                      especialidades esp,
                      inv_especialidad_x_producto espx
                  where
                      espx.codigo_medicamento = '" . $CodigoProducto . "'
                      and
                      espx.especialidad = esp.especialidad
                      order by esp.descripcion;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_ViasAdministracion($CodigoProducto) {
        $sql = "
            select 
                  viad.via_administracion_id,
                  viad.nombre
                  from 
                  hc_vias_administracion viad
                  where
                  viad.via_administracion_id Not In
                                          (
                                          select 
                                          viadp.via_administracion_id
                                          from
                                          inv_medicamentos_vias_administracion  viadp
                                          where
                                          viadp.codigo_medicamento = '" . $CodigoProducto . "'
                                          ) 
                  order by viad.nombre;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_Vias_De_Administracion() {

        $sql = "
            select
            via_administracion_id as codigo,
            nombre
            from
            hc_vias_administracion
            order by nombre;";

        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_Vias_Administracion() {
        $sql = "
            select 
                  via_administracion_id as codigo,
                  nombre
                  from 
                  hc_vias_administracion
                  order by nombre;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_ViasAdministracionxProducto($CodigoProducto) {
        $sql = "
            select 
                      viadx.codigo_medicamento,
                      viadx.via_administracion_id,
                      viad.nombre
                  from 
                      hc_vias_administracion viad,
                      inv_medicamentos_vias_administracion viadx
                  where
                      viadx.codigo_medicamento = '" . $CodigoProducto . "'
                      and
                      viadx.via_administracion_id = viad.via_administracion_id
                      order by viad.nombre;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_PresentacionesComerciales() {
        $sql = "
            select
            presentacioncomercial_id as codigo,
            descripcion
            from
            inv_presentacioncomercial
            order by descripcion;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_Especialidades($CodigoProducto) {
        $sql = "
            select 
                  esp.especialidad,
                  esp.descripcion
                  from 
                  especialidades esp
                  where
                  esp.especialidad Not In
                                          (
                                          select 
                                          espx.especialidad
                                          from
                                          inv_especialidad_x_producto espx
                                          where
                                          espx.codigo_medicamento = '" . $CodigoProducto . "'
                                          ) 
                  order by esp.descripcion;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_PrincipiosActivos() {
        $sql = "
            select 
                  pa.cod_principio_activo,
                  pa.descripcion
                  from 
                  inv_med_cod_principios_activos pa
                  
                  order by pa.cod_principio_activo;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_Tipos_Productos() {
        $sql = "
            select
            tipo_producto_id as codigo,
            descripcion
            from
            inv_tipo_producto
            order by codigo;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_Perfiles_Terapeuticos() {
        $sql = "
            select  
            cod_anatomofarmacologico as codigo,
            descripcion
            from
            inv_med_cod_anatofarmacologico
            order by descripcion;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;
        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        // return $sql;
        return $documentos;
    }

//GERMAN
    function Listar_Codigos_Bienes_Servicios() {
        $sql = "
            select  
            codigo,
            descripcion
            from
            inv_codificacion_bienes_servicios
			where estado = '1'
            order by descripcion;";
			
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;
        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
		
        return $documentos;
    }
//FIN

    function Listar_TratamientosProductos() {
        $sql = " SELECT
			tratamiento_id,
			descripcion
			FROM
			inv_tratamientos_productos;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_Presentacion_Comercial() {
        $sql = "
            select
            cod_forma_farmacologica as codigo,
            descripcion,
			unidad_dosificacion
            from
            inv_med_cod_forma_farmacologica
            order by descripcion;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_CodigosConcentracion() {
        $sql = "
            select
            cod_concentracion as codigo,
            descripcion
            from
            inv_med_cod_concentraciones
            order by descripcion;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_Unidades_Medida() {
        $sql = "
            select
            u.unidad_id as codigo,
            u.descripcion,
            u.abreviatura
            from unidades u
			INNER JOIN inv_med_cod_forma_farmacologica f ON (u.unidad_id = f.cod_forma_farmacologica)
			WHERE f.estado = '1'
            order by descripcion;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_Unidades_MedidaMedicamentos() {
        $sql = "
            select
            unidad_medida_medicamento_id as codigo,
            descripcion,
            abreviatura
            from
            inv_unidades_medida_medicamentos
            order by descripcion;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Listar_Paises() {
        $sql = "
            select
            tipo_pais_id as codigo,
            pais
            from
            tipo_pais
            order by pais;";
        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function Consecutivo_Producto($Grupo_Id, $Clase_Id, $SubClase_Id) {
        $sql = "
            select
            inv_mostrar_serial('" . $Grupo_Id . "','" . $Clase_Id . "','" . $SubClase_Id . "') as producto_id;";

        //$this->debug=true;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function InsertarViadxProd($via_administracion_id, $CodigoProducto) {
        //$this->debug=true;
        $sql = "INSERT INTO inv_medicamentos_vias_administracion (";
        $sql .= "       codigo_medicamento    , ";
        $sql .= "       via_administracion_id     ";
        $sql .= "       ) ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $CodigoProducto . "',";
        $sql .= "        '" . $via_administracion_id . "'";
        $sql .= "       ); ";



        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function BorrarViadxProd($tabla, $id, $campo_id, $Anex, $CodigoProducto) {

        // $this->debug=true;
        $sql = "Delete from " . $tabla . " ";
        $sql .= "Where " . $campo_id . " = '" . $id . "' 
      " . $Anex . "= '" . $CodigoProducto . "';";




        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    //Insertar Datos de Insumos/Medicamentos a la Base de datos
    function Modificar_ClasificacionProducto($Formulario) {
        /* $this->debug=true; */
        $sql .= "UPDATE medicamentos";
        $sql .= "       SET ";
        $sql .= "       cod_principio_activo = '" . $Formulario['select_subclase_id'] . "' ";
        $sql .= "where ";
        $sql .= "codigo_medicamento = '" . $Formulario['codigo_producto'] . "'; ";

        $sql .= "UPDATE inventarios_productos";
        $sql .= "       SET ";
        $sql .= "       grupo_id = '" . $Formulario['select_grupo_id'] . "', ";
        $sql .= "       clase_id = '" . $Formulario['select_clase_id'] . "', ";
        $sql .= "       subclase_id = '" . $Formulario['select_subclase_id'] . "' ";
        $sql .= "where ";
        $sql .= "codigo_producto = '" . $Formulario['codigo_producto'] . "' ";
        $sql .= "RETURNING codigo_producto;";
        /* $this->debug=true; */

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else {
            $documentos = Array();
            while (!$rst->EOF) {
                $documentos = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
            $info_ws=$this->Modificar_ClasificacionProducto_WS($Formulario,"0");
            $info_ws1=$this->Modificar_ClasificacionProducto_WS($Formulario, "1");
            $info_ws2=$this->Modificar_ClasificacionProducto_WS($Formulario, "2");
            $info_ws3=$this->Modificar_ClasificacionProducto_WS($Formulario, "3");
            $info_ws4=$this->Modificar_ClasificacionProducto_WS($Formulario, "4");
            $info_ws5=$this->Modificar_ClasificacionProducto_WS($Formulario, "5");
            return $info_ws;
        }
    }
    
     function Modificar_ClasificacionProducto_WS($Formulario, $destino) {
        require_once ('nusoap/lib/nusoap.php');
        
		$url_wsdl = $this->obtenerUrl($destino);
        $soapclient = new nusoap_client($url_wsdl, true);

        $function = "Modificar_ClasificacionProducto";
        $inputs = array('grupo_id' => $Formulario['select_grupo_id'],
                        'clase_id' => $Formulario['select_clase_id'],
                        'subclase_id' => $Formulario['select_subclase_id'],
                        'codigo_producto' => $Formulario['codigo_producto']
                        );

        $resultado = $soapclient->call($function, $inputs);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }     

    function Consulta_Factor_Conversion($codigo_producto) {

        $sql = "SELECT factor_conversion FROM hc_formulacion_factor_conversion WHERE codigo_producto='$codigo_producto'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else {
            //$documentos=Array();
            while (!$rst->EOF) {
                //$factor = $rst->GetRowAssoc($ToUpper = false);
                $factor = $rst->fields[0];
                $rst->MoveNext();
            }
            $rst->Close();

            return $factor;
        }
    }

    function consultar_tipos_riesgos() {

        $sql = "select * from inv_tipos_riesgos_insumos ;";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $datos = Array();
        while (!$resultado->EOF) {
            $datos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $datos;
    }
	
	
	function consultarEstadosInvima(){
	    $sql = "select * from estados_invima where id != 0 order by descripcion;"; 

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $datos = Array();
        while (!$resultado->EOF) {
            $datos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $datos;
		
	}

}

?>
