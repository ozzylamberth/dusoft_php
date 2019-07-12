<?php

/* * ****************************************************************************
 * $Id: doc_Bodegas_I007.class.php,v 1.1 2009/07/17 19:08:20 johanna Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * $Revision: 1.1 $ 
 * 
 * @autor Jaime Gomez
 * ****************************************************************************** */

if (!IncludeClass('BodegasDocumentos')) {
    die(MsgOut("Error al incluir archivo", "BodegasDocumentos"));
}

class doc_bodegas_I007 {

    function DatosParaEditar($tmp_doc_id, $usuario_id) {
        $ClassDOC = new BodegasDocumentos();
        $datos = $ClassDOC->GetInfoBodegaDocumentoTMP($tmp_doc_id, $usuario_id);
        return $datos;
    }

    function EliminarItem($bodegas_doc_id, $item) {
        //list($bodegas_doc_id,$i) = explode("@",$tr);
        $ClassDOC = new BodegasDocumentos();
        $OBJETO = $ClassDOC->GetOBJ($bodegas_doc_id);
        $resultado = $OBJETO->DelItemDocTemporal($item);
        return $resultado;
    }

//   function SacarProductosTMP($doc_tmp_id,$usuario_id)
//    {   
//        $ClassDOC= new BodegasDocumentos();
//        $datos=$ClassDOC->GetInfoBodegaDocumentoTMP($doc_tmp_id,$usuario_id);
//        $OBJETO=$ClassDOC->GetOBJ($datos['bodegas_doc_id']);
//        $tabla_de_productos=$OBJETO->GetItemsDocTemporal($doc_tmp_id,$usuario_id);
//        return $tabla_de_productos;
//   }

    function SacarProductosTMP($doc_tmp_id, $usuario_id) {//
        $ClassDOC = new BodegasDocumentos();
        $datos = $ClassDOC->GetInfoBodegaDocumentoTMP($doc_tmp_id, $usuario_id);
        IF (!EMPTY($datos)) {
            $OBJETO = $ClassDOC->GetOBJ($datos['bodegas_doc_id']);
            $tabla_de_productos = $OBJETO->GetItemsDocTemporal($doc_tmp_id, $usuario_id);
            return $tabla_de_productos;
        } else {
            var_dump($ClassDOC->ErrMsg());
        }
    }

    //CrearDoc($bodegas_doc_id, $observacion, $tipo_id_tercero, $tercero_id, $prestamo);
    function CrearDoc($bodegas_doc_id, $observacion, $tipo_id_tercero, $tercero_id, $prestamo, $tipo_tercero) {
        $ClassDOC = new BodegasDocumentos($bodegas_doc_id);
        $OBJETO = $ClassDOC->GetOBJ(); //($observacion,$tipo_prestamo_id, $tipo_id_tercero, $tercero_id, $usuario_id , $documento_compra, $fecha_doc_compra
        $RETORNO = $OBJETO->NewDocTemporal($observacion, $prestamo, $tipo_id_tercero, $tercero_id, UserGetUID(), $tipo_tercero);
        //    $RETORNO=$OBJETO->GetInfoBodegaDocumento($bodegas_doc_id);
        echo $OBJETO->Err() . $OBJETO->ErrMsg();
        //var_dump($RETORNO);
        if (!is_object($ClassDOC)) {
            die(MsgOut("Error al crear la clase", "BodegasDocumentos"));
        }
        return $RETORNO;
    }

    function TraerDatos($bodegas_doc_id) {
        $ClassDOC = new BodegasDocumentos($bodegas_doc_id);
        $OBJETO = $ClassDOC->GetOBJ();
        $RETORNO = $OBJETO->GetInfoBodegaDocumento($bodegas_doc_id);
        //var_dump($RETORNO);
        if (!is_object($ClassDOC)) {
            die(MsgOut("Error al crear la clase", "BodegasDocumentos"));
        }
        return $RETORNO;
    }

    function BuscarProducto($empresa_id, $centro_utilidad, $bodega, $aumento, $offset) {
        $sql1 = "SELECT
                    count(*)
              FROM
                  existencias_bodegas as a,
                  inventarios_productos as b,
                  unidades as c
              WHERE
              a.empresa_id = '$empresa_id'
              AND a.centro_utilidad = '$centro_utilidad'
              AND a.bodega = '$bodega'
              " . $aumento . "
              AND b.codigo_producto = a.codigo_producto
              AND b.estado = '1'
              AND c.unidad_id = b.unidad_id";
        $this->ProcesarSqlConteo($sql1, 10, $offset);

        $sql = "SELECT
                  b.codigo_producto,
                  fc_descripcion_producto(b.codigo_producto) as descripcion,
                  b.unidad_id,
                  c.descripcion as descripcion_unidad,
                  a.existencia, 
                  d.costo,
                  b.porc_iva
              FROM
                  existencias_bodegas as a,
                  inventarios_productos as b,
                  unidades as c,
                  inventarios as d
              WHERE
              a.empresa_id = '$empresa_id'
              AND a.centro_utilidad = '$centro_utilidad'
              AND a.bodega = '$bodega'
              " . $aumento . " 
              AND b.codigo_producto = a.codigo_producto
              AND b.estado = '1'
              AND c.unidad_id = b.unidad_id
              AND d.empresa_id = a.empresa_id
              AND d.codigo_producto = a.codigo_producto
              
              order By b.descripcion ASC
              
              limit " . $this->limit . " OFFSET " . $this->offset . "";
      //  echo  $sql;
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

    function ContarProStip($empresa_id, $centro_utilidad, $bodega, $aumento) {
        $sql = "SELECT
                        count(*)
                  FROM
                      existencias_bodegas as a,
                      inventarios_productos as b,
                      unidades as c
                  WHERE
                  a.empresa_id = '$empresa_id'
                  AND a.centro_utilidad = '$centro_utilidad'
                  AND a.bodega = '$bodega'
                  " . $aumento . "
                  AND b.codigo_producto = a.codigo_producto
                  AND c.unidad_id = b.unidad_id";


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

    function GuardarTemporal($bodegas_doc_id, $doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id = null, $fecha_venc, $lotec) {
        $ClassDOC = new BodegasDocumentos($bodegas_doc_id);
        $OBJETO = $ClassDOC->GetOBJ();
        //echo $bodegas_doc_id;
        //print_r($OBJETO);
        $RETORNO = $OBJETO->AddItemDocTemporal($doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id = null, $fecha_venc, $lotec);
        //var_dump($RETORNO);
        return $RETORNO;
    }

    function llamarhijo() {

        echo "llegamos";
    }

    /*     * ****************************************************************************
     * funcion constructora 
     * ***************************************************************************** */

    function TraerTipoPrestamo($doc_tmp_id, $Usuario) {
        $sql = "SELECT 
                a.tipo_prestamo_id,
				a.tipo_id_tercero,
				a.tercero_id
              FROM
                  inv_bodegas_movimiento_tmp_prestamo as a
              WHERE
                a.doc_tmp_id  = " . $doc_tmp_id . " 
                and
                a.usuario_id  = " . $Usuario . ";
              ";

        //  print_r($sql);
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

    function CodigoProveedor($tercero_id, $tipo_id_tercero) {
        $sql = "SELECT 
                codigo_proveedor_id
              FROM
                  terceros_proveedores
              WHERE
                tipo_id_tercero = '" . $tipo_id_tercero . "' 
				and
				tercero_id = '" . $tercero_id . "';
              ";

        //print_r($sql);
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

    /**
     * MAURICIO
     */
    function Farmacias_prestamo($tipo_id_tercero, $tercero_id) {
        $sql = " SELECT a.prefijo, ";
        $sql .= "             a.numero ";
        //$sql .= "             b.id , b.tipo_id_tercero ";
        $sql .= " FROM  inv_bodegas_movimientos_devolucion_prestamo as a ";
        $sql .= "            ";
        $sql .= " WHERE a.farmacia_id= " . $tercero_id . "";
        $sql .= " AND   a.sw_documentoingreso=0 ";
        $sql .= " group by a.prefijo,a.numero ";
        //print_r($sql);
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

    /**
     * JOHANNA
     */
    /* function Farmacias_prestamo()
      {
      $sql  = " SELECT a.*, ";
      $sql .= "             b.razon_social, ";
      $sql .= "             b.id , b.tipo_id_tercero ";
      $sql .= " FROM  inv_bodegas_movimientos_devolucion_prestamo as a, ";
      $sql .= "            empresas as b ";
      $sql .= " WHERE a.farmacia_id=b.id ";
      $sql .= " AND   a.sw_documentoingreso=0 ";
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
      } */

    /**
     *
     */
    function Temporal_Farmacias($doc_tmp_id, $bodegas_doc_id) {
        $sql = " SELECT   tipo_tercero ";
        $sql .= " FROM     inv_bodegas_movimiento_tmp ";
        $sql .= " WHERE 	doc_tmp_id=" . $doc_tmp_id . " ";
        $sql .= " AND 	      bodegas_doc_id=" . $bodegas_doc_id . " ";
        //print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;
        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        return $documentos;
    }

    /**
     *
     */
    function ProductosTrasladoFarmacia($prefijo, $numero) {
        $sql = " SELECT e.*,fc_descripcion_producto(f.codigo_producto) as descripcion,g.descripcion as descripcion_unidad ";
        $sql .= " FROM  ";
        $sql .= "            inv_bodegas_movimiento_d as e, ";
        $sql .= "            inventarios_productos as f, ";
        $sql .= "            unidades as g ";
        $sql .= " WHERE    e.prefijo='" . $prefijo . "' ";
        $sql .= " AND      e.numero = " . $numero . " ";
        $sql .= " AND      e.codigo_producto=f.codigo_producto ";
        $sql .= " AND      f.unidad_id=g.unidad_id ";

        //print_r($sql);
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

    /**
     *
     */
    function Temporal_Inv_bodegasDetalle($doc_tmp_id, $codigo_producto) {
        $sql = " SELECT   * ";
        $sql .= " FROM     inv_bodegas_movimiento_tmp_d ";
        $sql .= " WHERE 	doc_tmp_id=" . $doc_tmp_id . " ";
        $sql .= " AND 	      codigo_producto='" . $codigo_producto . "' ";
        //print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;
        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        return $documentos;
    }

    /**
     *
     */
    function ItemBorrado($item) {
        $sql = " SELECT   * ";
        $sql .= " FROM     inv_bodegas_movimiento_tmp_d ";
        $sql .= " WHERE 	item_id =" . $item . " ";

        // print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;
        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        return $documentos;
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

    function ProcesarSqlConteo($consulta, $limite = null, $offset = null) {
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

    function ConsultarItemsExistencias($Codigo_Producto, $Empresa_Id, $Centro_Utilidad, $Bodega) {

        $query = "
									SELECT 	
                       codigo_producto,
                       existencia
									FROM	
                      existencias_bodegas
									WHERE 
                      empresa_id = '" . $Empresa_Id . "'
                      and
                      centro_utilidad = '" . $Centro_Utilidad . "'
                      and
                      bodega = '" . $Bodega . "'
                      and
                      codigo_producto = '" . $Codigo_Producto . "'
								";
        //print_r($query);

        if (!$result = $this->ConexionBaseDatos($query))
            return false;

        if ($result->RecordCount() > 0) {
            while (!$result->EOF) {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();

        return $vars;
    }

    function BuscarProductoLoteTemporal($doc_tmp_id, $usuario, $codigo_producto, $lote) {
        $sql = "SELECT
                        *
                  FROM
                      inv_bodegas_movimiento_tmp_d
                  WHERE
                          codigo_producto = '" . $codigo_producto . "'
                  AND     usuario_id = " . $usuario . "
                  AND     doc_tmp_id = " . $doc_tmp_id . "
                  AND     lote = '" . $lote . "';
                  ";


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

    /*     * ********************************************************************************
     * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
     * consulta sql 
     * 
     * @param  string  $sql  sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
     * @return rst 
     * ********************************************************************************** */

    function ConexionBaseDatos($sql) {
        list($dbconn) = GetDBConn();
        //$dbconn->debug=true;
        $rst = $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
            "<b class=\"label\">" . $this->frmError['MensajeError'] . "</b>";
            return false;
        }
        return $rst;
    }
	
	
	function TraerBodegasTipoPrestamos($tipo) {
		
		
        $sql = "SELECT a.sw_sincronizar
				FROM inv_bodegas_tipos_prestamos as a
				WHERE a.tipo_prestamo_id  like '%".$tipo."%';";
              

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

	
	

    /*     * ********************************************************************************
     * Funcion que permite crear una transaccion 
     * @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
     * @param char $num Numero correspondiente a la sentecia sql - por defect es 1
     *
     * @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
     *                se devuelve nada
     * ********************************************************************************* */
}

?>