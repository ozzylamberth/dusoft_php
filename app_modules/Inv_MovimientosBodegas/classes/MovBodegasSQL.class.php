<?php

/* * ****************************************************************************
 * $Id: MovBodegasSQL.class.php,v 1.1 2009/07/17 19:08:23 johanna Exp $
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

class MovBodegasSQL {

    var $mensajeDeError;

    /*     * *********************
     * constructora
     * *********************** */

    function MovBodegasSQL() {
        
    }
	
	/**
	*+Descripcion MEtodo encargado de consultar por torre los productos de un documento
	**/
	function Listar_Torres($empresa_id, $prefijo, $numero) {
         
		 $sql = "SELECT DISTINCT(a.torre) as torre 
		FROM ( SELECT
		   
			 CASE WHEN (SELECT tor.torre FROM param_torreproducto as tor WHERE tor.codigo_producto = b.codigo_producto AND tor.empresa_id = a.empresa_id LIMIT 1) is null THEN 'Sin definir' 
			 ELSE (SELECT tor.torre FROM param_torreproducto as tor WHERE tor.codigo_producto = b.codigo_producto AND tor.empresa_id = a.empresa_id LIMIT 1) END as torre
		FROM  
			inv_bodegas_movimiento_d as a,
			inventarios_productos as b,  
			unidades as c
		WHERE
			a.empresa_id = '$empresa_id'
			AND a.prefijo = '$prefijo'
			AND a.numero = $numero
			AND b.codigo_producto = a.codigo_producto
			AND c.unidad_id = b.unidad_id
			ORDER BY a.codigo_producto
			) as a";
                
		 
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
	
    /**
     * funcion que sirve para traer el sw manual de de los documentos TMP e007
     *
     * */
    function TraerSW($usuario_id, $doc_tmp_id) {

        $SQL = "  SELECT sw_costo_manual
            FROM
            inv_bodegas_movimiento_tmp_conceptos_egresos
            WHERE
            usuario_id=" . $usuario_id . "
            AND doc_tmp_id=" . $doc_tmp_id . "";

        if (!$resultado = $this->ConexionBaseDatos($SQL)) {
            return false;
        } else {
            $deptos = array();
            while (!$resultado->EOF) {
                $deptos[] = $resultado->GetRowAssoc($ToUpper = false);
                $resultado->MoveNext();
            }

            $resultado->Close();
            //return $sql1;
            return $deptos;
        }
    }

    /*     * ********************************************************************************
     * Funcion que inserta  departamentos, 
     * 
     * @return vector
     * ********************************************************************************* */

    function GXD($id_pais, $departamentox) {

        $sql = "select max(tipo_dpto_id) from tipo_dptos
      where tipo_pais_id='" . $id_pais . "'";
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;
        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        if (!empty($documentos)) {
            $codigo_dep = $documentos[0]['max'] + 1;
        } else {
            $codigo_dep = 1;
        }
        $sql = "insert into tipo_dptos values('" . $codigo_dep . "','" . $id_pais . "','" . strtoupper($departamentox) . "');";
        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $cad = "no se hizo la inserci�";
            //return $cad;
            return $sql;
        } else {
            $cad = $codigo_dep;
            $rst->Close();
            return $cad;
        }
    }

    /*     * ********************************************************************************
     * Funcion que inserta  municipios, 
     * 
     * @return vector
     * ********************************************************************************* */

    function GXM($id_pais, $id_dept, $Municipio) {

        $sql = "select max(tipo_mpio_id) from tipo_mpios
      where tipo_pais_id='" . $id_pais . "' and  tipo_dpto_id='" . $id_dept . "'";
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;
        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        if (!empty($documentos)) {
            $codigo_mun = $documentos[0]['max'] + 1;
        } else {
            $codigo_mun = 1;
        }
        $sql = "insert into tipo_mpios values('" . $id_pais . "','" . $id_dept . "','" . $codigo_mun . "','" . strtoupper($Municipio) . "');";
        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $cad = "no se hizo la inserci�";
            //return $cad;
            return $sql;
        } else {
            $cad = $codigo_mun;
            $rst->Close();
            return $cad;
        }
    }

    function Consultadpto($departamentox) {
        $sql1 = "select tipo_dpto_id from tipo_dptos
   where departamento='" . strtoupper($departamentox) . "'";
        if (!$resultado = $this->ConexionBaseDatos($sql1)) {
            return false;
        } else {
            $deptos = array();
            while (!$resultado->EOF) {
                $deptos[] = $resultado->GetRowAssoc($ToUpper = false);
                $resultado->MoveNext();
            }

            $resultado->Close();
            //return $sql1;
            return $deptos;
        }
    }

    function Consultampio($pais, $depto, $Municipio) {
        $sql1 = "select tipo_mpio_id from tipo_mpios
           where 
            tipo_pais_id='" . $pais . "' AND
            tipo_dpto_id='" . $depto . "' AND
            municipio='" . strtoupper($Municipio) . "'";
        if (!$resultado = $this->ConexionBaseDatos($sql1)) {
            //return $sql1;
            return false;
        } else {
            $munis = array();
            while (!$resultado->EOF) {
                $munis[] = $resultado->GetRowAssoc($ToUpper = false);
                $resultado->MoveNext();
            }

            $resultado->Close();
            //return $sql1;
            return $munis;
        }
    }

    function Perdidas() {


        $sql = "  select
          tipo_perdida_id,
          descripcion
          from
          inv_bodegas_tipos_perdidas
          where
          sw_estado='1'";

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

    /*     * **********************************************************************************
     *
     * Funcion que consulta paises.
     *
     * *********************************************************************************** */

    function DePX($id_pais) {
        $sql = "select * 
       from tipo_dptos
       where tipo_pais_id='" . $id_pais . "'    
       order by departamento";

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

    /*     * **********************************************************************************
     *
     * Funcion que consulta paises.
     *
     * *********************************************************************************** */

    function DeMX($id_pais, $id_dpto) {
        $sql = "select * 
       from tipo_mpios
       where tipo_pais_id='" . $id_pais . "'    
       and tipo_dpto_id='" . $id_dpto . "'
       order by municipio";

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

    /*     * ********************************************************************************
     * Funcion que inserta  en la tabla cg_parametros_documentos, 
     * 
     * @return mensaje de confirmacion
     * ********************************************************************************* */

    function GuardarPersonas($tipo_identificacion, $id_tercero, $nombre, $pais, $departamento, $municipio, $direccion, $telefono, $faz, $email, $celular, $perjur) {
        //var_dump($direccion);
        if ($direccion == "") {
            $direccion = "NULL";
        } else {
            $direccion = "'" . $direccion . "'";
        }
        if ($telefono == 0) {
            $telefono = "NULL";
        } else {
            $telefono = "'" . $telefono . "'";
        }
        if ($faz == 0) {
            $faz = "NULL";
        } else {
            $faz = "'" . $faz . "'";
        }
        if ($email == 0) {
            $email = "NULL";
        } else {
            $email = "'" . $email . "'";
        }
        if ($celular == 0) {
            $celular = "NULL";
        } else {
            $celular = "'" . $celular . "'";
        }

        $sql = "insert into  terceros
      values('" . $tipo_identificacion . "','" . $id_tercero . "','" . $pais . "',
             '" . $departamento . "','" . $municipio . "'," . $direccion . "," . $telefono . ",
             " . $faz . "," . $email . "," . $celular . ",'" . $perjur . "','0'," . UserGetUID() . ",now(),NULL,'" . $nombre . "');";
//           tipo_id_tercero   tercero_id  tipo_pais_id  
//           tipo_dpto_id  tipo_mpio_id  direccion
//          telefono  fax   email   celular   
//          sw_persona_juridica   cal_cli   usuario_id  
//          fecha_registro  busca_persona   nombre_tercero           
        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $cad = $this->frmError['MensajeError'];
            $cad = "HAY PROBLEMAS CON LA INSERCION ES POSIBLE QUE ESTE TERCERO YA EXISTE";
            return $cad;
        } else {
            //$cad=$sql;
            $cad = "EXITO";
            $rst->Close();
            return $cad;
        }
    }

    /*     * ************************************
     * listar TIPOS EGRESOS
     * ************************************ */

    function Tipos_Egresos() {
        $sql = "  SELECT
            concepto_egreso_id,
            descripcion,
            sw_exige_tercero,
            sw_exige_departamento,
            sw_estado,
            sw_permite_costo_manual   
            FROM
            inv_bodegas_conceptos_egresos
            WHERE
            sw_estado='1'
            ";



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

    /*     * ************************************
     * listar aprovechamineto
     * ************************************ */

    function Prestamos() {
        $sql = "  select
          tipo_prestamo_id,
          descripcion,sw_pres_dev
          from
          inv_bodegas_tipos_prestamos
          where
          sw_estado='1'";

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

    /*     * ************************************
     * listar aprovechamineto
     * ************************************ */

    function PrestamosPr() {
        $sql = "  select
          tipo_prestamo_id,
          descripcion,sw_pres_dev
          from
          inv_bodegas_tipos_prestamos
          where sw_estado='1' 
          AND    sw_pres_dev='1'
          OR    sw_pres_dev='3' ";
        //print_r($sql);
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

    /*     * ************************************
     * listar aprovechamineto
     * ************************************ */

    function PrestamosDev() {
        $sql = "  select
          tipo_prestamo_id,
          descripcion,sw_pres_dev
          from
          inv_bodegas_tipos_prestamos
          where sw_estado='1' 
          AND    sw_pres_dev='0'
          OR    sw_pres_dev='3' ";
        //print_r($sql);
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

    /*     * ************************************
     * listar aprovechamineto
     * ************************************ */

    function Aprovechar() {
        $sql = "  select
          tipo_aprovechamiento_id,
          descripcion
          from
          inv_bodegas_tipos_aprovechamiento
          where
          sw_estado='1'";

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

    /*     * ******************************************************
     * function para obtener los datos de un documento
     * ******************************************************* */

    function SacarDocumento($empresa_id, $prefijo, $numero) {
        $ClassDOC = new BodegasDocumentos();
        $datosDoc = $ClassDOC->GetDoc($empresa_id, $prefijo, $numero, $detalle = true);
        return $datosDoc;
    }

    /*     * ******************************************************
     * function extrae los documentos de bodega
     * ****************************************************** */

    function ObtenerDocumentosFinal($oset, $empresa_id, $centro_utilidad, $bodega, $usuario_id, $tipo_movimiento, $tipo_doc_bodega_id) {
        $oset = $oset - 1;
        $ClassDOC = new BodegasDocumentos();
        $contador = $ClassDOC->GetDocumentos_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id = null, $count = true, $limit = null, $offset = null, $tipo_movimiento, $tipo_doc_bodega_id);
        $limit = 20;
        $oset = $limit * $oset;
        $datos = $ClassDOC->GetDocumentos_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id = null, $count = false, $limit, $oset, $tipo_movimiento, $tipo_doc_bodega_id);
        $vector['datos'] = $datos;
        $vector['contador'] = $contador;
        return $vector;
    }

    /*     * *********************************************
     * funcion para consultar documentos
     * ************************************************* */

    function ObtenerTiposDocumentos($empresa_id, $centro_utilidad, $bodega, $tipo_movimiento, $usuario) {
        $ClassDOC = new BodegasDocumentos();
        $tipos_doc = $ClassDOC->GetTiposDocumentos_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $tipo_movimiento, $usuario_id = null);
        return $tipos_doc;
    }

    function ObtenerClasesDocumentos($empresa_id, $centro_utilidad, $bodega, $usuario_id = null) {
        $ClassDOC = new BodegasDocumentos();
        $clases = $ClassDOC->GetTiposMovimiento_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id = null);
        return $clases;
    }

    /*     * *********************************************************************************
     *
     * ******************************************************************************** */

    function CrearDocumentoOriginal($bodegas_doc_id, $doc_tmp_id, $usuario_id) {
        $ClassDOC = new BodegasDocumentos();
        $OBJETO = $ClassDOC->GetOBJ($bodegas_doc_id);
        $resultado = $OBJETO->CrearDocumento($doc_tmp_id, $usuario_id);
        $this->mensajeDeError = $OBJETO->Err() . $OBJETO->ErrMsg();
        //VAR_DUMP($resultado);
        return $resultado; //$resultado
    }

    /**
     * Funcion donde se controla la creacion de los documentos de despacho
     *
     * @param integer $bodegas_doc_id
     * @param integer $doc_tmp_id
     * @param integer $cliente
     * @param array $pedido Arreglo de datos con la informacion del pedido
     * @param integer $usuario_id
     *
     * @return mixed
     */
    function CrearDocumentoOriginalF($bodegas_doc_id, $doc_tmp_id, $cliente, $pedido, $usuario_id) {
        $ClassDOC = new BodegasDocumentos();
        $OBJETO = $ClassDOC->GetOBJ($bodegas_doc_id);

        $resultado = $OBJETO->CrearDocumento($doc_tmp_id, $cliente, $pedido, $usuario_id);
        if (!$resultado) {
            $this->mensajeDeError = $OBJETO->Err() . $OBJETO->ErrMsg();
            return false;
        }

        return $resultado;
    }

    /**
     *
     */
    function EliminarDocTemporal($bodegas_doc_id, $doc_tmp_id, $usuario_id) {
        $ClassDOC = new BodegasDocumentos();
        $OBJETO = $ClassDOC->GetOBJ($bodegas_doc_id);
        $resultado = $OBJETO->DelDocTemporal($doc_tmp_id, $usuario_id);
        return $resultado;
    }

    function ObtenerDocsTmpUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id) {
        $ClassDOC = new BodegasDocumentos();
        $datos = $ClassDOC->GetDocumentosTMP_BodegaUsuario($empresa_id, $centro_utilidad, $bodega, $usuario_id);
        //var_dump($datos);
        return $datos;
    }

    function ObtenerDocumetosTemporales($form) {
        $sql = "SELECT 
		t.*, 
		c.inv_tipo_movimiento AS tipo_movimiento, 
		b.tipo_doc_general_id AS tipo_doc_bodega_id, 
		c.descripcion AS tipo_clase_documento, 
		b.prefijo, 
		b.descripcion, 
		a.empresa_id, 
		a.centro_utilidad, 
		a.bodega, 
		SU.nombre 
		FROM inv_bodegas_movimiento_tmp t 
		JOIN inv_bodegas_documentos as a ON (t.bodegas_doc_id = a.bodegas_doc_id) 
		JOIN documentos as b  ON (a.documento_id = b.documento_id)
		AND (a.empresa_id = b.empresa_id)
		JOIN tipos_doc_generales as c ON (b.tipo_doc_general_id = c.tipo_doc_general_id) 
		JOIN system_usuarios as SU ON (t.usuario_id = SU.usuario_id) 
		WHERE TRUE ";
        $sql .= "AND    a.empresa_id = '" . trim($form['empresa']) . "' ";
        $sql .= "AND    a.centro_utilidad = '" . trim($form['centro_utilidad']) . "' ";
        $sql .= "AND    a.bodega = '" . trim($form['bodega']) . "' ";

        if ($form['tipos_doc'] != '-1')
            $sql .= "AND   b.tipo_doc_general_id = '" . $form['tipos_doc'] . "' ";

        if ($form['clas_doc'] != '-1')
            $sql .= "AND   c.inv_tipo_movimiento = '" . $form['clas_doc'] . "' ";

        if ($form['numero_doc'])
            $sql .= "AND   t.doc_tmp_id = " . $form['numero_doc'] . " ";

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (" . $sql . ") AS A ", null, $form['offset']))
            return false;

        $sql .= "ORDER BY t.fecha_registro ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";
        /* print_r($sql); */
        GLOBAL $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        //$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        $datos = array();

        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        /* while($fila = $rst->FetchRow())
          { */
        /* $datos[$fila['tipo_movimiento']][$fila['tipo_doc_bodega_id']][$fila['doc_tmp_id']]=$fila; */
        /*   $datos[$fila['tipo_movimiento']][$fila['tipo_doc_bodega_id']][$fila['doc_tmp_id']]=$fila;
          } */

        $rst->Close();
        return $datos;
    }

    /*     * **********************************************************************************
     *
     * Funcion que consulta nombre de la empresa
     *
     * *********************************************************************************** */

    function ColocarDptos($CENTRO) {
        $sql = " select departamento,  descripcion
       from departamentos
       
       where
        centro_utilidad='" . $CENTRO . "'
        AND    
        empresa_id='" . SessionGetVar("EMPRESA") . "'";


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

    function ColocarProyectos() {
        $sql = " 	SELECT 	proyecto_id,  
						descripcion
				FROM inv_bodegas_movimiento_proyectos
				WHERE empresa_id='" . SessionGetVar("EMPRESA") . "'
				AND sw_estado = '1'
				ORDER BY 2";


        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $proyectos = Array();
        while (!$resultado->EOF) {

            $proyectos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $proyectos;
    }

    /*     * **********************************************************************************
     *
     * Funcion que consulta nombre de la empresa
     *
     * *********************************************************************************** */

    function ColocarCentro2() {
        $sql = " select descripcion,centro_utilidad
       from centros_utilidad
       where empresa_id='" . SessionGetVar("EMPRESA") . "'";


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

    /*     * **********************************************************************************
     *
     * Funcion que consulta nombre de la empresa
     *
     * *********************************************************************************** */

    function ColocarCentro1($centro) {
        $sql = " select descripcion,centro_utilidad
       from centros_utilidad
       where TRUE
       and empresa_id='" . trim(SessionGetVar("EMPRESA")) . "'";


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

    /*     * *****************
     * bodegas name
     * ****************** */

    function bodegasname1($bodegax, $centro) {
        $sql = " select descripcion,bodega
              from bodegas
              where 
              empresa_id='" . trim(SessionGetVar("EMPRESA")) . "'
              AND centro_utilidad='" . trim($centro) . "'
              AND bodega <> '" . trim($bodegax) . "';";
        /* print_r($sql); */
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

    /*     * *****************
     * bodegas name
     * ****************** */

    function bodegasname2($bodegax, $centro) {
        $sql = " select descripcion,bodega
              from bodegas
              where 
              empresa_id='" . SessionGetVar("EMPRESA") . "'
              AND centro_utilidad='" . $centro . "'
              AND bodega <> '$bodegax'";
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

    /*     * *****************
     * bodegas name
     * ****************** */

    function OrdenesRequisicion_Despacho() {
        $sql = " select *
              from esm_orden_requisicion
              where empresa_id IS NULL
                    and sw_estado = '1'  
                      ";
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

    /*     * *****************
     * bodegas name
     * ****************** */

    function OrdenesRequisicion($empresa_id) {
        $sql = " select *
              from esm_orden_requisicion
              where empresa_id ='" . $empresa_id . "'
                    and sw_estado = '1'  
                      ";
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

    /*     * *****************
     * bodegas name
     * ****************** */

    function OrdenRequisicion($orden_requisicion_id) {
        $sql = " select *
              from esm_orden_requisicion
              where orden_requisicion_id =" . $orden_requisicion_id . "
              ";
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {

            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $documentos;
    }

    /*     * *****************
     * bodegas name
     * ****************** */

    function Documentos_TrasladoDevolucion($empresa_id) {
        $sql = " 
				select 
						b.empresa_id,
						a.prefijo,
						a.numero,
						b.centro_utilidad,
						b.bodega,
						c.descripcion as bodega_,
						d.descripcion as centro,
						e.razon_social
						from
						inv_bodegas_movimiento_traslados_esm_devoluciones as a
						JOIN inv_bodegas_movimiento as b ON (a.empresa_id = b.empresa_id) and (a.prefijo = b.prefijo) and (a.numero = b.numero)
						JOIN bodegas as c ON (b.empresa_id = c.empresa_id) and (b.centro_utilidad = c.centro_utilidad) and (b.bodega = c.bodega)
						JOIN centros_utilidad as d ON (c.empresa_id = d.empresa_id) and (c.centro_utilidad = d.centro_utilidad)
						JOIN empresas as e ON (c.empresa_id = e.empresa_id)
						where
						     a.sw_estado = '0'
						and  a.empresa_id = '" . $empresa_id . "'		
              ";
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

    /*     * *****************
     * bodegas name
     * ****************** */

    function bodegasname($bodegax) {
        $sql = " select descripcion  from bodegas
              where bodega='$bodegax'";

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

    /*     * **********************************************************************************
     *
     * Funcion que consulta paises.
     *
     * *********************************************************************************** */

    function Paises() {
        $sql = " select * from tipo_pais order by pais";

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

    /*     * ******************************************************************************
     * FUNCION QUE CUENTA TERCEROS SEGUN TIPO DE BUSQUEDA
     * ******************************************************************************* */

    function ContarTercerosStip($tipo_id, $id, $nombre) {

        if ($tipo_id == "0" && $id == "0" && $nombre == "0") {
            $sql1 = "select count(*) from terceros";
        } elseif ($tipo_id != "0" && $id != "0" && $nombre != "0") {
            $sql1 = "select count(*) 
        from terceros
        where tipo_id_tercero='" . $tipo_id . "' 
        and tercero_id='" . $id . "'
        or nombre_tercero ILIKE '%" . strtoupper($nombre) . "%'";
        } elseif ($tipo_id != "0" && $id != "0" && $nombre == "0") {
            $sql1 = "select count(*) 
        from terceros
        where tipo_id_tercero='" . $tipo_id . "' 
        and tercero_id='" . $id . "'";
        } elseif ($tipo_id == "0" && $id == "0" && $nombre != "0") {
            $sql1 = "select count(*) 
        from terceros
        where nombre_tercero ILIKE '%" . strtoupper($nombre) . "%'";
        } elseif ($tipo_id != "0" && $id == "0" && $nombre == "0") {
            $sql1 = "select count(*) 
        from terceros
        where tipo_id_tercero='" . $tipo_id . "'";
        } elseif ($tipo_id != "0" && $id == "0" && $nombre != "0") {
            $sql1 = "select count(*) 
        from terceros
        where tipo_id_tercero='" . $tipo_id . "' 
        and nombre_tercero ILIKE '%" . strtoupper($nombre) . "%'";
        } elseif ($tipo_id != "0" && $id != "0" && $nombre != "0") {
            $sql1 = "select count(*) 
        from terceros
        where tipo_id_tercero='" . $tipo_id . "' 
        and tercero_id='" . $id . "'
        or nombre_tercero ILIKE '%" . strtoupper($nombre) . "%'";
        } elseif ($tipo_id == "0" && $id != "0" && $nombre == "0") {
            $sql1 = "select count(*) 
        from terceros
        where tercero_id='" . $id . "'
        ";
        }
        ///




        if (!$resultado = $this->ConexionBaseDatos($sql1))
            return false;

        $cuentas = Array();
        while (!$resultado->EOF) {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $cuentas;
    }

    /*     * **********************************************************************************
     *
     * Funcion que consulta tipos de id terceros.
     *
     * *********************************************************************************** */

    function Terceros_id() {
        $sql = " select * from tipo_id_terceros order by indice_de_orden";

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

    /*     * **************************************************************
     * funcion para sacar los documentos de la bodega
     * ***************************************************************** */

    function PonerDocumentosBodega($usuario, $empresa, $cent_utility, $bodega) {
        $retorno = BodegasDocumentos::GetTipoDocumentosUsuario($empresa, $cent_utility, $bodega, $usuario);
        return $retorno;
    }

    /*     * *************************************************************
     * fUNCION PARA COLOCAR LAS BODEGAS 
     * **************************************************************** */

    function ColocarBodegas($usuario, $empresa) {
        $documentos = BodegasDocumentos::GetBodegasUsuario($empresa, $usuario);
        return $documentos;
    }

    /*     * ****************************************************************************
     * ELIMINAR PRODUCTO CUADRADO
     * ****************************************************************************** */

    function EliminarAjuste($toma_fisica_id, $etiqueta) {

        $sql = "DELETE FROM inv_toma_fisica_update
      WHERE
       toma_fisica_id=" . $toma_fisica_id . "
       AND etiqueta=" . $etiqueta;

        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida al borrar datos";
            return $cad;
        } else {
            $cad = "Ajuste Eliminada Correctamente";
            return $cad;
        }
    }

    /*     * ********************************************************************** */

    function AddCuadrar($toma_fisica_id, $etiqueta, $num_conteo, $sw_manual, $empresa_id, $centro_utilidad, $bodega, $codigo_producto, $existencia, $nueva_existencia) {

        $sql = "INSERT INTO inv_toma_fisica_update(
    toma_fisica_id,
    etiqueta,
    num_conteo,
    sw_manual,
    empresa_id,
    centro_utilidad,
    bodega,
    codigo_producto,
    existencia,
    nueva_existencia)
    VALUES (" . $toma_fisica_id . ",
            " . $etiqueta . ",
            " . $num_conteo . ",
            '" . $sw_manual . "',
            '" . $empresa_id . "',
            '" . $centro_utilidad . "',
            '" . $bodega . "',
            '" . $codigo_producto . "',
            " . $existencia . ",
            " . $nueva_existencia . ")";


        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $cad = "no se hizo la insercion";
            return $cad;
        } else {
            $cad = "Producto Cuadrado Satisfactoriamente";
            $rst->Close();
            return $cad;
        }
    }

    function GetNoCuadre($toma, $etiqueta) {
        $sql = "SELECT
          x.*,
          y.conteo as conteo_1,
          CASE WHEN y.usuario_valido IS NULL THEN '0' ELSE '1' END as validacion_conteo_1,
          (x.existencia - y.conteo) as diferencia_1,
          z.conteo as conteo_2,
          CASE WHEN z.usuario_valido IS NULL THEN '0' ELSE '1' END as validacion_conteo_2,
          (x.existencia - z.conteo) as diferencia_2,
          w.conteo as conteo_3,
          CASE WHEN w.usuario_valido IS NULL THEN '0' ELSE '1' END as validacion_conteo_3,
          (x.existencia - w.conteo) as diferencia_3
      FROM
      (
          SELECT
              a.toma_fisica_id,
              a.etiqueta,
              a.centro_utilidad,
              a.bodega,
              a.empresa_id,
              b.codigo_producto,
              b.descripcion,
              b.unidad_id,
              c.descripcion as descripcion_unidad,
              e.existencia
          FROM
              inv_toma_fisica_d as a,
              inventarios_productos as b,
              unidades as c,
              existencias_bodegas as e
      
          WHERE
              a.toma_fisica_id = " . $toma . "
              AND a.etiqueta = " . $etiqueta . "
              AND b.codigo_producto = a.codigo_producto
              AND c.unidad_id = b.unidad_id
              AND e.empresa_id = a.empresa_id
              AND e.centro_utilidad  = a.centro_utilidad
              AND e.bodega = a.bodega
              AND e.codigo_producto = a.codigo_producto
      
      ) AS x LEFT JOIN inv_toma_fisica_conteos AS y
      ON(
          y.toma_fisica_id = x.toma_fisica_id
          AND y.etiqueta = x.etiqueta
          AND y.num_conteo = 1
      )
      LEFT JOIN inv_toma_fisica_conteos AS z
      ON(
          z.toma_fisica_id = x.toma_fisica_id
          AND z.etiqueta = x.etiqueta
          AND z.num_conteo = 2
      )
      LEFT JOIN inv_toma_fisica_conteos AS w
      ON(
          w.toma_fisica_id = x.toma_fisica_id
          AND w.etiqueta = x.etiqueta
          AND w.num_conteo = 3
      )";

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

    function GetValidacionCont($toma_fisica_id, $etiqueta, $num_conteo) {
        $sql = "
      SELECT
      CASE WHEN usuario_valido IS NULL THEN '0' ELSE '1' END as validado
      FROM inv_toma_fisica_conteos
      WHERE toma_fisica_id = $toma_fisica_id
      AND etiqueta = $etiqueta
      AND num_conteo = $num_conteo
   ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;
        list($salida) = $resultado->FetchRow();
        $resultado->Close();
        return $salida;
    }

    function SacarNoCuadroC3($toma) {
        $sql = "SELECT
    etiqueta,
    codigo_producto,
    descripcion,
    costo,
    existencia,
    descripcion_unidad,
    conteo_1,
    conteo_2,
    conteo_3,
    diferencia_3,
    diferencia_1con3,
    diferencia_2con3,
    validacion_conteo_3

FROM tomas_fisicas
WHERE toma_fisica_id=" . $toma . "
AND nueva_existencia IS NULL
AND conteo_3 IS NOT NULL";

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

    /*     * **********************************************************************************
     *
     * Funcion que consulta nombre de la empresa
     *
     * *********************************************************************************** */

    function ColocarCentro($centro) {
        $sql = " select descripcion
       from centros_utilidad
       where  centro_utilidad = '" . strtoupper($centro) . "'
       and empresa_id='" . SessionGetVar("EMPRESA") . "'";

        // print_r($sql);
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

    /*     * **********************************************************************************
     *
     * Funcion que consulta nombre de la empresa
     *
     * *********************************************************************************** */

    function ColocarEmpresa($empresa) {
        $sql = "SELECT  empresa_id,";
        $sql .= " 	      tipo_id_tercero,";
        $sql .= " 	      id,";
        $sql .= " 	      razon_social,";
        $sql .= " 	      representante_legal,";
        $sql .= " 	      codigo_sgsss,";
        $sql .= " 	      tipo_pais_id,";
        $sql .= " 	      tipo_dpto_id,";
        $sql .= " 	      tipo_mpio_id,";
        $sql .= " 	      direccion,";
        $sql .= " 	      telefonos,";
        $sql .= " 	      fax,";
        $sql .= " 	      codigo_postal,";
        $sql .= " 	      website,";
        $sql .= " 	      email,";
        $sql .= " 	      sw_activa ";
        $sql .= "FROM    empresas ";
        $sql .= "WHERE   empresa_id = '" . strtoupper($empresa) . "'";


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

    /*     * *******************************************
     * actualizacion para la validacion de listas
     * ******************************************** */

    function ValidarActualizarConteo($datos) {

        $sql = "";
        for ($i = 0; $i < count($datos); $i++) {

            list($toma_fisica_id, $etiqueta, $num_conteo, $cantidad) = explode("@", $datos[$i]);
            $sql.=" UPDATE inv_toma_fisica_conteos
          SET conteo=" . $cantidad . ",
              usuario_valido=" . UserGetUID() . ",
              fecha_validacion=now()
          WHERE
          toma_fisica_id=" . $toma_fisica_id . "
          AND etiqueta=" . $etiqueta . "
          AND num_conteo=" . $num_conteo . ";";
        }
        //return $sql;
        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return $this->frmError['MensajeError']; //$cad;
        } else {
            $cad = "Productos Validados Exitosamente";
            return $cad;
        }
    }

    function GetNumeroLista($toma_fisica_id) {
        $sql = "LOCK TABLE inv_toma_fisica_numeros_listas IN ROW EXCLUSIVE MODE;";
        $sql .= "SELECT numero_lista FROM inv_toma_fisica_numeros_listas WHERE toma_fisica_id = $toma_fisica_id;";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $val_retorno = 1;

        if ($resultado->EOF) {
            $sql = "INSERT INTO inv_toma_fisica_numeros_listas(toma_fisica_id,numero_lista) VALUES ($toma_fisica_id,2);";
            if (!$this->ConexionBaseDatos($sql))
                return false;
        }
        else {
            list($val_retorno) = $resultado->FetchRow();
            $resultado->Close();

            $sql = "UPDATE inv_toma_fisica_numeros_listas SET numero_lista = numero_lista + 1 WHERE toma_fisica_id = $toma_fisica_id;";
            if (!$this->ConexionBaseDatos($sql))
                return false;
        }
        return $val_retorno;
    }

    /*     * **********************************************************************************
     * Funcion que lista cuentas
     * *********************************************************************************** */

    function BuscarCuentasStip($tip_bus, $elemento, $offset, $empresa) {

        if ($tip_bus == 0) {
            $sql1 = "select  count(*) 
            from 
            cg_conf.cg_plan_de_cuentas where empresa_id='" . $empresa . "'";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select * from cg_conf.cg_plan_de_cuentas  
            where empresa_id='" . $empresa . "' order by cuenta 
            limit " . $this->limit . " OFFSET " . $this->offset . "";
        }

        if ($tip_bus == 1) {
            $sql1 = "select count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where 
      cuenta LIKE '" . strtoupper($elemento) . "%' and empresa_id='" . $empresa . "' ";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select * from cg_conf.cg_plan_de_cuentas where cuenta LIKE '" . strtoupper($elemento) . "%' 
       and empresa_id='" . $empresa . "' order by cuenta
       limit " . $this->limit . " OFFSET " . $this->offset . "";
        }

        if ($tip_bus == 2) {
            $sql1 = "select count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where 
      descripcion LIKE '%" . strtoupper($elemento) . "%' and empresa_id='" . $empresa . "' ";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select * from cg_conf.cg_plan_de_cuentas where descripcion LIKE '%" . strtoupper($elemento) . "%' 
       and empresa_id='" . $empresa . "' order by cuenta
       limit " . $this->limit . " OFFSET " . $this->offset . "";
        }


        if ($tip_bus == 3) {

            list($elemento1, $elemento2) = explode("-", $elemento);
            $sql1 = "select  count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where cuenta >= '" . $elemento1 . "' and cuenta <= '" . $elemento2 . "' and empresa_id='" . $empresa . "'";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select  * from cg_conf.cg_plan_de_cuentas 
       where cuenta >= '" . $elemento1 . "' and cuenta <= '" . $elemento2 . "'
        and empresa_id='" . $empresa . "' order by cuenta 
       limit " . $this->limit . " OFFSET " . $this->offset . "";
        }

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $cuentas = Array();
        while (!$resultado->EOF) {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $cuentas;
    }

    /*     * **********************************************************************************
     *
     * Funcion que cuenta cuentas
     *
     * *********************************************************************************** */

    function ContarCuentasStip($toma_fisica, $aumento) {

        $sql1 = "select count(*)
          FROM
          inv_toma_fisica_d as b,
          inventarios_productos as c,
          unidades as d
          WHERE
          b.toma_fisica_id = " . $toma_fisica . "
          " . $aumento . "
          AND c.codigo_producto = b.codigo_producto
          AND d.unidad_id = c.unidad_id";

        if (!$resultado = $this->ConexionBaseDatos($sql1))
            return false;

        $cuentas = Array();
        while (!$resultado->EOF) {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $cuentas;
    }

    /*     * **********************************************************************************
     *
     * Funcion que consulta tipos de documentos.
     *
     * *********************************************************************************** */

    function NombreCuenta($cuenta) {

        $sql = " select descripcion from cg_conf.cg_plan_de_cuentas  
               where cuenta='" . $cuenta . "' and 
               empresa_id='" . SessionGetVar("EMPRESA") . "' 
               order by cuenta";

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

    /*     * **********************************************************************************
     *
     * Funcion que lista los tipos documentos segun el tipo de empresa.
     *
     * *********************************************************************************** */

    function ListarTiposDocumentos() {

        $sql = "select * from tipos_doc_generales where sw_doc_sistema='0' order by tipo_doc_general_id"; //


        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $resultado->fields[1] = strtoupper($resultado->fields[1]);
            $resultado->fields[1] = ereg_replace("�", "E", $resultado->fields[1]);
            $documentos[$resultado->fields[1]] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        return $documentos;
    }

    /*     * **********************************************************************************
     *
     * Funcion que lista los tipos documentos segun el tipo de empresa.
     *
     * *********************************************************************************** */

    function ListarEmpresas() {

        /* $sql=" select * from empresas
          WHERE sw_activa = '1'
          order by razon_social,empresa_id"; */

        $sql = "SELECT DISTINCT
				a.*
				FROM
				empresas AS a
				JOIN inv_bodegas_userpermisos as b ON (a.empresa_id = b.empresa_id)
				WHERE TRUE
				AND a.sw_activa = '1'
				AND b.usuario_id = '" . UserGetUID() . "';";


        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $resultado->fields[3] = strtoupper($resultado->fields[3]);
            $resultado->fields[3] = ereg_replace("�", "E", $resultado->fields[3]);
            $documentos[$resultado->fields[3]] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $documentos;
    }

    /*     * **********************************************************************************
     *
     * Funcion que saca los departamentos
     *
     * *********************************************************************************** */

    function Departamentos() {
        $sql1 = "select centro_de_costo_id,descripcion from cg_conf.centros_de_costo
           ORDER BY descripcion";
        if (!$resultado = $this->ConexionBaseDatos($sql1))
            return false;
        $cuentas = array();
        while (!$resultado->EOF) {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $cuentas;
    }

    /*     * **********************************************************************************
     *
     * Funcion que saca los departamentos
     *
     * *********************************************************************************** */

    function Departamentos_d($depto) {
        $sql1 = "select descripcion from cg_conf.centros_de_costo
           where centro_de_costo_id='" . $depto . "' ORDER BY descripcion";
        if (!$resultado = $this->ConexionBaseDatos($sql1))
        //return $sql1;
            return false;
        $cuentas = array();
        while (!$resultado->EOF) {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        //return $sql1;
        return $cuentas;
    }

    /*     * **********************************************************************************
     *
     * Funcion que saca los TERCEROS_ EN GENERAL($pagina,$criterio1,$criterio2,$criterio);
     *
     * *********************************************************************************** */

    function Terceros_General($pagina, $tipo_id, $id, $nombre, $op) {
        $whr = "";

        $sql = "SELECT DISTINCT TE.* ";
        $sql .= "FROM   terceros TE ";
        switch ($op) {
            case 1:
                $sql .= "   ";
                $whr .= " 	";
                $whr .= "   ";
                break;
        }
        $sql .= "WHERE TRUE ";
        if ($nombre)
            $sql .= "AND   TE.nombre_tercero ILIKE '%" . $nombre . "%'";
        if ($id)
            $sql .= "AND   TE.tercero_id='" . $id . "' ";
        if ($tipo_id != "0")
            $sql .= "AND   TE.tipo_id_tercero='" . $tipo_id . "' ";
        $sql .= $whr;

        $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (" . $sql . ") A", 10, $pagina);

        $sql .= "ORDER BY TE.nombre_tercero ";

        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

        //print_r($sql);

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $cuentas = array();
        while (!$resultado->EOF) {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $cuentas;
    }

    /*     * **********************************************************************************
     *
     * Funcion que saca los TERCEROS($pagina,$criterio1,$criterio2,$criterio);
     *
     * *********************************************************************************** */

    function Terceros($pagina, $tipo_id, $id, $nombre, $op, $empresa_id) {
        $whr = "";

        $sql = "SELECT DISTINCT TE.* ";
        $sql .= "FROM   terceros TE ";
        switch ($op) {
            case 1:
                $sql .= "   ,inv_bodegas_movimiento_prestamos IB ";
                $whr .= "AND  IB.empresa_id = '" . $empresa_id . "' 	";
                $whr .= "AND  IB.sw_devolucion = '0' 	";
                $whr .= "AND  IB.tipo_movimiento = 'E' 	";
                $whr .= "AND  TE.tipo_id_tercero = IB.tipo_id_tercero 	";
                $whr .= "AND  TE.tercero_id = IB.tercero_id ";
                break;
            case 2:
                $sql .= "   ,inv_bodegas_movimiento_prestamos IB ";
                $whr .= "AND  IB.empresa_id = '" . $empresa_id . "' 	";
                $whr .= "AND  IB.sw_devolucion = '0' 	";
                $whr .= "AND  IB.tipo_movimiento = 'I' 	";
                $whr .= "AND  TE.tipo_id_tercero = IB.tipo_id_tercero 	";
                $whr .= "AND  TE.tercero_id = IB.tercero_id ";
                break;
            case 3:
                $sql .= "   ,inv_bodegas_movimiento_prestamos IB ";
                $whr .= "AND  IB.empresa_id = '" . $empresa_id . "' 	";
                $whr .= "AND  IB.sw_devolucion = '0' 	";
                $whr .= "AND  IB.tipo_movimiento = 'I' 	";
                $whr .= "AND  TE.tipo_id_tercero = IB.tipo_id_tercero 	";
                $whr .= "AND  TE.tercero_id = IB.tercero_id ";
                break;
        }
        $sql .= "WHERE TRUE ";
        if ($nombre)
            $sql .= "AND   TE.nombre_tercero ILIKE '%" . $nombre . "%'";
        if ($id)
            $sql .= "AND   TE.tercero_id='" . $id . "' ";
        if ($tipo_id != "0")
            $sql .= "AND   TE.tipo_id_tercero='" . $tipo_id . "' ";
        $sql .= $whr;
        /* print_r($sql); */
        $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (" . $sql . ") A", 5, $pagina);

        $sql .= "ORDER BY TE.nombre_tercero ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";
        //print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $cuentas = array();
        while (!$resultado->EOF) {
            $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $cuentas;
    }

    /*     * **********************************************************************************
     *
     * Funcion que consulta tipos de documentos.
     *
     * *********************************************************************************** */

    function TiposDocumento() {
        $sql = " select * from tipos_doc_generales Order by descripcion";



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

    function NombreUsu($usuario_id) {
        $sql = " select nombre
        from system_usuarios
        where usuario_id='" . trim($usuario_id) . "'";

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

    /*     * *****************************
     * nom terceros
     * ******************************* */

    function Nombre($tercero_id) {
        $sql = " select *
        from terceros
        where tercero_id='" . trim($tercero_id) . "'";

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

    function Nombres($tipo_id, $tercero_id) {
        $sql = "SELECT TE.* ";
        $sql .= "FROM   terceros TE ";
        $sql .= "WHERE  tercero_id='" . trim($tercero_id) . "' ";
        $sql .= "AND    tipo_id_tercero='" . $tipo_id . "' ";

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

    function ConsultaSolicitudProyecto($bodegas_doc_id) {

        $SQL = "  SELECT a.sw_maneja_proyectos
            FROM
            documentos a, inv_bodegas_documentos b
            WHERE b.bodegas_doc_id=" . $bodegas_doc_id . "
            AND a.documento_id=b.documento_id";

        if (!$resultado = $this->ConexionBaseDatos($SQL)) {
            return false;
        } else {
            $manejaproyectos = array();
            while (!$resultado->EOF) {
                $manejaproyectos[] = $resultado->GetRowAssoc($ToUpper = false);
                $resultado->MoveNext();
            }

            $resultado->Close();
            //return $sql1;
            return $manejaproyectos;
        }
    }

    /*     * ******************************************************************************
     * Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
     * importantes a la hora de referenciar al paginador
     * 
     * @param String Cadena que contiene la consulta sql del conteo 
     * @param int numero que define el limite de datos,cuando no se desa el del 
     * 			 usuario,si no se pasa se tomara por defecto el del usuario 
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

    function SacarNombreProyecto($bodegas_doc_id, $empresa_id, $prefijo, $numero) {
        $ClassDOC = new BodegasDocumentos();
        $OBJETO = $ClassDOC->GetOBJ($bodegas_doc_id);
        $resultado = $OBJETO->GetNombreProyecto($empresa_id, $prefijo, $numero);
        return $resultado;
    }

    function ActuDocumentos($empresa, $prefijo, $numero, $observaciones, $codigop, $cantidad, $fecha, $lote, $abreviatura) {
        //$datos = array();
        //$fdatos=explode("-", $datos['fecha_inicio']);
        //$fedatos= $fdatos[2]."/".$fdatos[1]."/".$fdatos[0];

        $sql = "";

        $sql.= " UPDATE inv_bodegas_movimiento_tmp
              SET    observacion='" . $observaciones . "'         
              WHERE  empresa_id='" . $empresa . "'
              AND    prefijo ='" . $prefijo . "'
              AND    numero =" . $numero . " ;";

        $sql.= " UPDATE inv_bodegas_movimiento_d
              SET    cantidad=" . $cantidad . ",
                     fecha_vencimiento='" . $fecha . "',
                     lote='" . $lote . "'                     
              WHERE  empresa_id='" . $empresa . "'
              AND    prefijo ='" . $prefijo . "'
              AND    numero =" . $numero . " 
              AND    codigo_producto ='" . $codigop . "' ;";
        //}
        //return $sql;
        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false; //$cad;
        }
        return true;
    }

    function ActuEstado($abreviatura, $usuario, $doc_tmp_id, $tipo_documento) {
        $sql = "SELECT sw_crear_documento ";
        $sql .= "FROM   inv_estados_documentos ";
        $sql .= "WHERE  abreviatura='" . $abreviatura . "' ";
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        if (!$resultado->EOF) {
            $datos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        $sql = " UPDATE inv_bodegas_movimiento_tmp
              SET    abreviatura='" . $abreviatura . "'              
              WHERE  usuario_id='" . $usuario . "'
              AND    doc_tmp_id ='" . $doc_tmp_id . "';
              ";
        if ($datos['sw_crear_documento'] == '1') {
            $sql.=" UPDATE  para_documentosg
              SET     sw_verifico=1
              WHERE   abreviatura='" . $abreviatura . "' 
              AND     tipo_doc_general_id='" . $tipo_documento . "'
              AND     doc_tmp_id =" . $doc_tmp_id . " ";
        }

        //print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false; //$cad;
        }

        return true;
    }

    function GuardarDevolucion($tipo_doc_bodega_id, $empresa_id, $observacion, $doc_tmp_id) {
        //$this->ConexionTransaccion();
        $sql = " INSERT INTO  devolucion_documento(
                           id_devolucion_doc,              
                           tipo_doc_general_id,	
                           observacion,
                           id_doc_generl,
                           usuario_id,
                           fecha_registro,
                           empresa_id)
               VALUES     (default,
                           '" . $tipo_doc_bodega_id . "',
                           '" . $observacion . "',
                           " . $doc_tmp_id . ",
                           " . UserGetUID() . ",
                           NOW(),
                           '" . $empresa_id . "')";
        //print_r($sql);       
        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false; //$cad;
        }
        return true;
    }

    function GuardarParGrabar($tipo_doc_bodega_id, $abreviatura, $doc_tmp_id) {
        //$this->ConexionTransaccion();
        $sql = " INSERT INTO  para_documentosg(
                           id_para_documentosg,              
                           tipo_doc_general_id,	
                           abreviatura,
                           doc_tmp_id,
                           usuario_id,
                           fecha_registro)
               VALUES     (default,
                           '" . $tipo_doc_bodega_id . "',
                           '" . $abreviatura . "',
                           " . $doc_tmp_id . ",
                           " . UserGetUID() . ",
                           NOW() )";
        //  print_r($sql);         
        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false; //$cad;
        }
        return true;
    }

    function ConsultaEstadosPermisos($tipo_doc_general_id) {
        $sql = "SELECT   a.abreviatura, b.descripcion ";
        $sql .= "FROM     paramestadosdocum as a, ";
        $sql .= "         inv_estados_documentos as b, ";
        $sql .= "         inv_bodegas_usuarios_estados_documentos as c ";
        $sql .= "WHERE    a.tipo_doc_general_id='" . $tipo_doc_general_id . "' ";
        $sql .= "AND      a.abreviatura=b.abreviatura ";
        $sql .= "AND      c.abreviatura=b.abreviatura ";
        $sql .= "AND      c.usuario_id =" . UserGetUID() . " ";
        //print_r($sql);
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

    function ConsultaEstadosPermisosp($tipo_doc_general_id, $doc_tmp_id) {
        $sql = "SELECT   a.abreviatura, b.descripcion,c.sw_verifico ";
        $sql .= "FROM     paramestadosdocum as a, ";
        $sql .= "         inv_estados_documentos as b,para_documentosg c ";
        $sql .= "WHERE    a.tipo_doc_general_id='" . $tipo_doc_general_id . "' ";
        $sql .= "AND      a.abreviatura=b.abreviatura ";
        $sql .= "AND      c.abreviatura=b.abreviatura ";
        $sql .= "AND      a.tipo_doc_general_id=c.tipo_doc_general_id ";
        $sql .= "AND      c.doc_tmp_id=" . $doc_tmp_id . " ";
        //print_r($sql);
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

    function ConsultaSw_verificar($tipo_doc_general_id, $doc_tmp_id) {
        $sql = "SELECT   a.abreviatura,a.sw_verifico,b.descripcion ";
        $sql .= "FROM     para_documentosg a,inv_estados_documentos b ";
        $sql .= "WHERE    a.tipo_doc_general_id='" . $tipo_doc_general_id . "' ";
        $sql .= "AND      a.doc_tmp_id=" . $doc_tmp_id . " ";
        $sql .= "AND      a.sw_verifico=0 ";
        $sql .= "AND      a.abreviatura=b.abreviatura ";
        //print_r($sql);
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

    function ConsultaPardocg($doc_tmp_id) {
        $sql = "SELECT   * ";
        $sql .= "FROM     para_documentosg ";
        $sql .= "WHERE    doc_tmp_id=" . $doc_tmp_id . " ";
        $sql .= "AND      usuario_id=" . UserGetUID() . " ";
        //$sql .= "AND      tipo_doc_general_id= '".$tipo_doc_general_id."' ";    
        //print_r($sql);
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

    function ConsultaVerificarS($tipo_doc_general_id, $empresa_id) {
        $sql = "SELECT   sw_verifico ";
        $sql .= "FROM     paramestadosdocum ";
        $sql .= "WHERE    tipo_doc_general_id='" . $tipo_doc_general_id . "' ";
        $sql .= "AND      empresa_id='" . $empresa_id . "' ";

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

    function ConsultaEstadosTmp($usuario, $doc_tmp_id) {
        $sql = "SELECT   abreviatura ";
        $sql .= "FROM     inv_bodegas_movimiento_tmp ";
        $sql .= "WHERE    usuario_id=" . $usuario . " ";
        $sql .= "AND      doc_tmp_id=" . $doc_tmp_id . " ";
        //print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function ConsultaTmp($usuario, $doc_tmp_id) {
        $sql = "SELECT   a.*,b.descripcion  ";
        $sql .= "FROM     inv_bodegas_movimiento_tmp_d AS a,inventarios_productos AS b ";
        $sql .= "WHERE    a.usuario_id=" . $usuario . " ";
        $sql .= "AND      a.doc_tmp_id=" . $doc_tmp_id . " ";
        $sql .= "AND      a.codigo_producto=b.codigo_producto";
        // print_r($sql);
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

    function ConsultaInventarioProducto($EmpresaId, $CodigoProducto) {
        $sql = "SELECT   * ";
        $sql .= "FROM     inventarios ";
        $sql .= "WHERE    codigo_producto = '" . $CodigoProducto . "' ";
        $sql .= "AND      empresa_id = '" . $EmpresaId . "' ";

        //print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        //print_r($documentos);
        return $documentos;
    }

    function ConsultaTmpPendientes($doc_tmp_id) {
        $sql = "SELECT  sum(b.cantidad_solic) as cantidad_pediente,b.codigo_producto ";
        $sql .= "FROM     inv_bodegas_movimiento_tmp_d AS a,solicitud_productos_a_bodega_principal_detalle AS b ";
        $sql .= "WHERE    a.doc_tmp_id=" . $doc_tmp_id . " ";
        $sql .= "AND      a.codigo_producto=b.codigo_producto ";
        $sql .= "AND      b.sw_pendiente=1 ";
        $sql .= "GROUP BY b.codigo_producto ";
        //print_r($sql);
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

    function ConsultaTmpProdu($doc_tmp_id) {
        $sql = "SELECT   a.*,b.existencia_minima  ";
        $sql .= "FROM     inv_bodegas_movimiento_tmp_d AS a,inventarios as b ";
        $sql .= "WHERE    a.doc_tmp_id=" . $doc_tmp_id . " ";
        $sql .= "AND      a.codigo_producto=b.codigo_producto ";
        $sql .= "AND      a.empresa_id=b.empresa_id ";
        //print_r($sql);
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

    function ConsultaRuta($empresa_id) {
        $sql .= " SELECT  b.rutaviaje_destinoempresa_id,";
        $sql .= "         a.descripcion as ruta,";
        $sql .= "         b.empresa_id,";
        $sql .= "         d.descripcion ";
        $sql .= " FROM    inv_rutasviajes_origen as a, ";
        $sql .= "         inv_rutasviajes_destinos as b, ";
        $sql .= "         empresas as c, ";
        $sql .= "         inv_zonas as d, ";
        $sql .= "         inv_zonas_mpios as e ";
        $sql .= " WHERE   a.empresa_id='" . $empresa_id . "' ";
        $sql .= " AND     a.empresa_id=b.empresa_id ";
        $sql .= " AND     a.rutaviaje_origen_id=b.rutaviaje_origen_id ";
        $sql .= " AND     b.empresa_id=c.empresa_id ";
        $sql .= " AND   c.tipo_dpto_id=e.tipo_dpto_id ";
        $sql .= " AND   c.tipo_mpio_id=e.tipo_mpio_id ";
        $sql .= " AND   e.zona_id=d.zona_id ";
        //print_r($sql);
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

    function ConsultaDevolucion_doc($tipo_doc_general_id, $doc_tmp_id) {
        $sql = "SELECT   * ";
        $sql .= "FROM     devolucion_documento ";
        $sql .= "WHERE    tipo_doc_general_id 	='" . $tipo_doc_general_id . "' ";
        $sql .= "AND      id_doc_generl=" . $doc_tmp_id . " ";
        //print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    /**
     *
     * @return boolean
     */
    function BorrarDevolucion_doc($tipo_doc_general_id, $doc_tmp_id) {
        $sql = "DELETE FROM devolucion_documento ";
        $sql .= "WHERE       tipo_doc_general_id 	='" . $tipo_doc_general_id . "' ";
        $sql .= "AND         id_doc_generl=" . $doc_tmp_id . " ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        return true;
    }

    /**
     * Funcion donde se actualiza la informcion del pedido de las farmacias 
     *
     * @param integer $doc_tmp_id Identficador del documento temporal
     * @param integer $pedido Identificador del pedido
     *
     * @return boolean
     */
    function BorrarTmpInv_Farmacias($doc_tmp_id, $pedido) {
        $sql = "DELETE FROM inv_bodegas_movimiento_tmp_despachos_farmacias ";
        $sql .= "WHERE  doc_tmp_id 	= " . $doc_tmp_id . " ";
        $sql .= "AND      usuario_id 	= " . UserGetUID() . " ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $sql = " UPDATE solicitud_productos_a_bodega_principal ";
        $sql .= " SET    sw_despacho = '1' ";
        $sql .= " WHERE  solicitud_prod_a_bod_ppal_id = " . $pedido . " ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        return $documentos;
    }

    function BorrarTmpFarmacias($doc_tmp_id) {
        $sql = "DELETE FROM inv_bodegas_movimiento_tmp_despachos_farmacias ";
        $sql .= "WHERE  doc_tmp_id 	= " . $doc_tmp_id . " ";
        $sql .= "AND      usuario_id 	= " . UserGetUID() . " ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        return $documentos;
    }

    function BorrarTmpClientes($doc_tmp_id) {
        $sql = "DELETE FROM inv_bodegas_movimiento_tmp_despachos_clientes ";
        $sql .= "WHERE  doc_tmp_id 	= " . $doc_tmp_id . " ";
        $sql .= "AND      usuario_id 	= " . UserGetUID() . " ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        return $documentos;
    }

    /**
     * Funcion donde se actualiza la informcion del pedido del cliente
     *
     * @param integer $doc_tmp_id Identficador del documento temporal
     * @param integer $pedido Identificador del pedido
     *
     * @return boolean
     */
    function BorrarTmpInv_Clientes($doc_tmp_id, $pedido) {
        $sql = "DELETE FROM inv_bodegas_movimiento_tmp_despachos_clientes ";
        $sql .= "WHERE  doc_tmp_id 	='" . $doc_tmp_id . "' ";
        $sql .= "AND      usuario_id 	=" . UserGetUID() . " ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $sql = "UPDATE ventas_ordenes_pedidos ";
        $sql .= "SET    fecha_envio = NOW() ";
        $sql .= "WHERE  pedido_cliente_id = " . $pedido . " ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        return true;
    }

    /**
     *
     * @return boolean
     */
    function Borrarpara_docg($tipo_doc_general_id, $doc_tmp_id) {
        $sql = "DELETE FROM para_documentosg ";
        $sql .= "WHERE       tipo_doc_general_id 	='" . $tipo_doc_general_id . "' ";
        $sql .= "AND         doc_tmp_id=" . $doc_tmp_id . " ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        return true;
    }

    function Consultausuaritmp($doc_tmp, $bodega) {
        $sql = "SELECT   TO_CHAR(fecha_registro,'yyyy-mm-dd') as fecha_reg,  ";
        $sql .= "         usuario_id,doc_tmp_id,bodegas_doc_id,observacion ";
        $sql .= "FROM     inv_bodegas_movimiento_tmp ";
        $sql .= "WHERE    doc_tmp_id=" . $doc_tmp . " ";
        $sql .= "AND      bodegas_doc_id=" . $bodega . " ";
        $sql .= "AND      usuario_id=" . UserGetUID() . " ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    /**
     *
     */
    function ConsultaEmpresa($Empresa) {
        $sql = "SELECT   *  ";
        $sql .= "FROM     empresas ";
        $sql .= "WHERE    empresa_id='" . $Empresa . "' ";


        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    /*
     * CONSULTAR DOCUMENTO DE VERIFICACION DE PRODUCTOS DEVUELTOS POR LA FARMACIA
     */

    function ConsultarDocumentoVerificacion($Empresa, $Prefijo, $Numero) {
        $sql = "SELECT   *  ";
        $sql .= "FROM     inv_documento_verificacion ";
        $sql .= "WHERE    empresa_id='" . $Empresa . "' ";
        $sql .= "AND      prefijo ='" . $Prefijo . "' ";
        $sql .= "AND      numero = " . $Numero . " ";

        // print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    /*
     * CONSULTAR DOCUMENTO DE VERIFICACION DE PRODUCTOS DEVUELTOS POR LA FARMACIA
     */

    function ConsultarDocumentoVerificacionDetalle($Empresa, $Prefijo, $Numero) {
        $sql = "SELECT   
                    ivd.*,
                    fc_descripcion_producto(ivd.codigo_producto) as descripcion_producto,
                    nd.descripcion ";
        $sql .= "FROM     inv_documento_verificacion_d as ivd, ";
        $sql .= "         inv_novedades_devoluciones as nd ";
        $sql .= "WHERE    ivd.empresa_id='" . $Empresa . "' ";
        $sql .= "AND      ivd.prefijo ='" . $Prefijo . "' ";
        $sql .= "AND      ivd.numero = " . $Numero . " ";
        $sql .= "AND      ivd.novedad_devolucion_id = nd.novedad_devolucion_id ";


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

    /*
     * CONSULTAR DOCUMENTO DE AUTORIZACIONES DE INGRESO DE PRODUCTOS
     */

    function ConsultarAutorizacionesIngreso($Empresa, $Prefijo, $Numero) {
        $sql = "SELECT   
                    *,
                    fc_descripcion_producto(codigo_producto) as descripcion_producto ";
        $sql .= " FROM      inv_bodegas_movimiento_ordenes_compra_prod_autorizados ";

        $sql .= " WHERE    empresa_id='" . $Empresa . "' ";
        $sql .= " AND      prefijo ='" . $Prefijo . "' ";
        $sql .= " AND      numero = " . $Numero . " ";



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

    /**
     * Funcion donde consulta parametrizacion de la torre de cada producto y su dueño
     *
     * @return booleano
     */
    function Buscarparamprod($empresa_id, $codigo_producto) {
        //$this->debug=true;
        $sql = "SELECT	* ";
        $sql .= "FROM		param_torreproducto ";
        $sql .= "WHERE	empresa_id='" . $empresa_id . "' ";
        $sql .= "AND	  codigo_producto='" . $codigo_producto . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    /**
     * Funcion donde 
     *
     * @return booleano
     */
    function Buscarpedidos() {
        $sql = "SELECT	min(solicitud_prod_a_bod_ppal_id) ";
        $sql .= "FROM		solicitud_productos_a_bodega_principal ";
        $sql .= "WHERE	sw_despacho=0 ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

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
     * Funcion donde 
     *
     * @return booleano
     */
    function BuscarTodospedidos($solicitud) {
        //$this->debug=true;
        $sql = "SELECT	* ";
        $sql .= "FROM		solicitud_productos_a_bodega_principal ";
        $sql .= "WHERE	solicitud_prod_a_bod_ppal_id=" . $solicitud . " ";

        //print_r($sql);
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    /**
     * Funcion donde 
     *
     * @return booleano
     */
    function FarmaciaPedidosTmp($doc_tmp_id) {
        $sql = "SELECT	* ";
        $sql .= "FROM		inv_bodegas_movimiento_tmp_despachos_farmacias ";
        $sql .= "WHERE	doc_tmp_id=" . $doc_tmp_id . " ";
        $sql .= "AND      usuario_id = " . UserGetUID() . " ";
        //print_r($sql);
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $documentos;
    }

    /* function BorrarTmpFarmacias($doc_tmp_id)
      {
      $sql  = " DELETE FROM inv_bodegas_movimiento_tmp_despachos_farmacias ";
      $sql .= " WHERE  doc_tmp_id = '".$doc_tmp_id."' ";
      /*
      DELETE FROM inv_bodegas_movimiento_tmp_despachos_farmacias WHERE usuario_id='400' AND doc_tmp_id='9'
     */
    /* if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;

      return true;
      } */

    /* function BorrarTmpClientes($doc_tmp_id)
      {
      $sql  = " DELETE FROM inv_bodegas_movimiento_tmp_despachos_clientes ";
      $sql .= " WHERE  doc_tmp_id = '".$doc_tmp_id."' ";
      /*
      DELETE FROM inv_bodegas_movimiento_tmp_despachos_farmacias WHERE usuario_id='400' AND doc_tmp_id='9'
     */
    /* if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;

      return true;
      } */

    /**
     *
     */
    function SiEsFarmacia($prefijo, $numero) {
        $sql = "SELECT	* ";
        $sql .= "FROM		inv_bodegas_movimiento_despachos_farmacias ";
        $sql .= "WHERE	numero=" . $numero . " ";
        $sql .= "AND  	prefijo='" . $prefijo . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $documentos;
    }

    /**
     * Funcion donde 
     *
     * @return booleano
     */
    function SIfarmacia($doc_tmp_id, $numero_pedido) {
        $sql = "SELECT	* ";
        $sql .= "FROM		inv_bodegas_movimiento_tmp_despachos_farmacias ";
        $sql .= "WHERE	doc_tmp_id=" . $doc_tmp_id . " ";
        $sql .= "AND  	solicitud_prod_a_bod_ppal_id=" . $numero_pedido . " ";
        $sql .= "AND  	usuario_id=" . UserGetUID() . " ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $documentos;
    }

    /**
     * Funcion donde 
     *
     * @return booleano
     */
    function SIcliente($doc_tmp_id, $numero_pedido) {
        $sql = "SELECT	* ";
        $sql .= "FROM		inv_bodegas_movimiento_tmp_despachos_clientes ";
        $sql .= "WHERE	doc_tmp_id=" . $doc_tmp_id . " ";
        $sql .= "AND    pedido_cliente_id = " . $numero_pedido . " ";
        $sql .= "AND    usuario_id = " . UserGetUID() . " ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $documentos;
    }

    /**
     * Funcion donde 
     *
     * @return booleano
     */
    function valorunitario($codigo_producto, $empresa_id) {
        //$this->debug=true;
        $sql = "SELECT	valor_pactado ";
        $sql .= "FROM		contratacion_produc_prov_detalle ";
        $sql .= "WHERE	codigo_producto='" . $codigo_producto . "' ";
        $sql .= "AND      empresa_id='" . $empresa_id . "' ";
        //print_r($sql);
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function ActuSolicitudFarma($solicitud_prod_a_bod_ppal_id) {
        $sql = " UPDATE solicitud_productos_a_bodega_principal
              SET    sw_despacho='1'              
              WHERE  solicitud_prod_a_bod_ppal_id='" . $solicitud_prod_a_bod_ppal_id . "'
              ";
        //print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false; //$cad;
        }

        return true;
    }

    /**
     * Funcion donde se actualiza la informacion del rotulo
     *
     * @param integer $documento_id Identificador del documento
     * @param integer $tmp_documento_id Identificador del documento temporal
     *
     * @return boolean
     */
    function ActuInvCrotulo($documento_id, $tmp_documento_id) {
        $sql = " UPDATE inv_rotulo_caja ";
        $sql .= " SET    documento_id = " . $documento_id . "  ";
        $sql .= " WHERE  documento_id = " . $tmp_documento_id . "  ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        return true;
    }

    /**
     * Funcion donde se consulta si se debe sincronizar
     *
     * @return booleano
     */
    function ClientesSincroniza($tipo_tercero_id,$tercero_id) {
        $sql =  " SELECT contrato_cliente_id, sw_sincroniza,
                    (SELECT nombre_tercero
                    FROM terceros  as b 
                    WHERE b.tipo_id_tercero =a.tipo_id_tercero 
                    AND b.tercero_id=a.tercero_id) as nombre_tercero
                    FROM vnts_contratos_clientes as a
                    WHERE a.tipo_id_tercero ='{$tipo_tercero_id}'
                    AND a.tercero_id='{$tercero_id}' ;" ;
      
            if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado->EOF) {
            $var = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $var;
    }
    /**
     * Funcion donde 
     *
     * @return booleano
     */
    function ClientesPedidosTmp($doc_tmp_id) {
        $sql = "SELECT	* ";
        $sql .= "FROM		inv_bodegas_movimiento_tmp_despachos_clientes ";
        $sql .= "WHERE	doc_tmp_id=" . $doc_tmp_id . " ";
        $sql .= "AND	  usuario_id=" . UserGetUID() . " ";
        //print_r($sql);
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $documentos;
    }

    /**
     * Funcion donde 
     *
     * @return booleano
     */
    function LoteyFecha($empresa_id, $centro_utilidad, $codigo_producto) {
        //$this->debug=true;
        $sql = "SELECT	* ";
        $sql .= "FROM		existencias_bodegas_lote_fv ";
        $sql .= "WHERE	empresa_id='" . $empresa_id . "' ";
        $sql .= "AND		centro_utilidad='" . $centro_utilidad . "' ";
        $sql .= "AND	codigo_producto='" . $codigo_producto . "' ";

        //print_r($sql);
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

    function ParmaBusquedaDoc($empresa_id, $usuario_bodega) {
        //$this->debug=true;
        $sql = "SELECT	* ";
        $sql .= "FROM		parametros_busqueda_bodegas ";
        $sql .= "WHERE	empresa_id='" . $empresa_id . "' ";
        $sql .= "AND		usuario_bodega=" . $usuario_bodega . " ";


        //print_r($sql);
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function ActuLoteyFec($empresa_id, $centro_utilidad, $lote, $codigo_producto) {
        //$this->debug=true;
        $sql = "SELECT	fecha_vencimiento ";
        $sql .= "FROM		existencias_bodegas_lote_fv ";
        $sql .= "WHERE	empresa_id='" . $empresa_id . "' ";
        $sql .= "AND		centro_utilidad='" . $centro_utilidad . "' ";
        $sql .= "AND	codigo_producto='" . $codigo_producto . "' ";
        $sql .= "AND	lote='" . $lote . "' ";

        //print_r($sql);
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    function ArchivoAdjunto($empresa_id, $numero) {
        //$this->debug=true;
        $sql = "SELECT	archivo_nombre ";
        $sql .= "FROM	  archivo_docue ";
        $sql .= "WHERE	empresa_id='" . $empresa_id . "' ";
        $sql .= "AND		numero='" . $numero . "' ";

        //print_r($sql);
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    /**
     * Funcion donde 
     *
     * @return booleano
     */
    function PrestamoDocumentos($tipo_id_tercero, $tercero_id, $tipo_doc_general) {
        //$this->debug=true;
        $sql = "SELECT	a.*,b.descripcion ";
        $sql .= "FROM	   inv_bodegas_movimiento_prestamos as a, inv_bodegas_tipos_prestamos as b, inv_movimientos_bodegas_relacion_pre_dev as c, documentos as d ";
        $sql .= "WHERE	 a.tipo_id_tercero='" . $tipo_id_tercero . "' ";
        $sql .= "AND		   a.tercero_id=" . $tercero_id . " ";
        $sql .= "AND		   b.tipo_prestamo_id=a.tipo_prestamo_id ";
        $sql .= "AND       c.tipo_doc_general_id_i='" . $tipo_doc_general . "' ";
        $sql .= "AND       c.tipo_doc_general_id_e=d.tipo_doc_general_id ";
        $sql .= "AND       d.prefijo=a.prefijo ";
        /* print_r($sql); */
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

    /**
     *
     */
    function DocumentosDePrestamo($tipo_id_tercero, $tercero_id, $empresa_id, $tipo_movimiento) {
        //$this->debug=true;
        $sql = "SELECT	a.* ";
        $sql .= "FROM	   inv_bodegas_movimiento_prestamos as a ";
        $sql .= "WHERE	   a.tipo_id_tercero='" . $tipo_id_tercero . "' ";
        $sql .= "AND	   a.tercero_id='" . $tercero_id . "'";
        $sql .= "AND	   a.empresa_id='" . $empresa_id . "' ";
        $sql .= "AND	   a.tipo_movimiento='" . $tipo_movimiento . "' ";
        $sql .= "AND       a.sw_devolucion='0' ";

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

    /**
     * Funcion donde 
     *
     * @return booleano
     */
    function PrestamoDevolDocumentos($tipo_id_tercero, $tercero_id, $tipo_doc_general) {
        //$this->debug=true;
        $sql = "SELECT	a.*,b.descripcion ";
        $sql .= "FROM	   inv_bodegas_movimiento_prestamos as a, inv_bodegas_tipos_prestamos as b, inv_movimientos_bodegas_relacion_pre_dev as c, documentos as d ";
        $sql .= "WHERE	 a.tipo_id_tercero='" . $tipo_id_tercero . "' ";
        $sql .= "AND		   a.tercero_id=" . $tercero_id . " ";
        $sql .= "AND		   b.tipo_prestamo_id=a.tipo_prestamo_id ";
        $sql .= "AND       c.tipo_doc_general_id_e='" . $tipo_doc_general . "' ";
        $sql .= "AND       c.tipo_doc_general_id_i=d.tipo_doc_general_id ";
        $sql .= "AND       d.prefijo=a.prefijo ";
        //print_r($sql);
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        //print_r($documentos);
        return $documentos;
    }

    /**
     * Funcion donde 
     *
     * @return booleano
     */
    function InvDevolDocumentos() {
        //$this->debug=true;
        $sql = "SELECT nextval('inv_bodegas_movimiento_devoluciones_documento_devolucion_01_seq'::regclass) as documento_devolucion";

        //print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        //print_r($documentos);
        return $documentos;
    }

    /**
     *
     */
    function RegistrarBusqueda($usuario, $empresa) {
        $sql = "SELECT cnt_busquedas_codigos_barras ";
        $sql .= "FROM   usuarios_busquedas ";
        $sql .= "WHERE  usuario_id = " . $usuario . " ";
        $sql .= "AND    empresa_id = '" . $empresa . "' ";
        $sql .= "AND    fecha_busqueda = NOW()::date ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $bsq = array();
        if (!$rst->EOF) {
            $bsq = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();

        if (empty($bsq)) {
            $sql = "INSERT INTO usuarios_busquedas ";
            $sql .= "   ( ";
            $sql .= "     empresa_id ,";
            $sql .= "     usuario_id ,";
            $sql .= "     fecha_busqueda ,";
            $sql .= "     cnt_busquedas_codigos_barras";
            $sql .= "   ) ";
            $sql .= "VALUES";
            $sql .= "   (";
            $sql .= "     '" . $empresa . "',";
            $sql .= "      " . $usuario . ",";
            $sql .= "      NOW(),";
            $sql .= "      1";
            $sql .= "   )";
        } else {
            $sql = "UPDATE usuarios_busquedas ";
            $sql .= "SET    cnt_busquedas_codigos_barras = cnt_busquedas_codigos_barras+1 ";
            $sql .= "WHERE  usuario_id = " . $usuario . " ";
            $sql .= "AND    empresa_id = '" . $empresa . "' ";
            $sql .= "AND    fecha_busqueda = NOW()::date ";
        }

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        return true;
    }

    /**
     * Funcion donde 
     *
     * @return booleano
     */
    function TmpDevoluciones($doc_tmp_id) {
        $sql = "SELECT * ";
        $sql .= "FROM   inv_bodegas_movimiento_tmp_devoluciones ";
        $sql .= "WHERE  doc_tmp_id = " . $doc_tmp_id . "; ";

        //print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        //$documentos=Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        //print_r($documentos);
        return $documentos;
    }

    function ActPrestamo($prefijo, $numero, $tipo_id_tercero, $tercero_id) {
        $sql = " UPDATE inv_bodegas_movimiento_prestamos
                 SET    	  sw_devolucion= 1
                 WHERE  prefijo='" . $prefijo . "'
                 AND      numero=" . $numero . "
                 AND      tipo_id_tercero='" . $tipo_id_tercero . "'
                 AND      tercero_id= '" . $tercero_id . "'
              ";
        //print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";
            return false; //$cad;
        }
    }

    /**
     * Funcion donde se otienen las farmacis que han hecho pedidos
     *
     * @param string $empresa_id Identificador de la empresa a la que se hacen pedidos
     *
     * @return mixed
     */
    function ObtenerFarmaciasPedidos($empresa_id) {

        $sql = "SELECT EM.empresa_id,
		SD.centro_utilidad,
		SD.bodega,
		EM.razon_social||' ::: '||a.descripcion as razon_social  ";
        $sql .= "FROM   solicitud_productos_a_bodega_principal as SD ";
        $sql .= "		  JOIN bodegas as a ON (SD.farmacia_id = a.empresa_id)";
        $sql .= "			AND(SD.centro_utilidad = a.centro_utilidad)";
        $sql .= "			AND(SD.bodega = a.bodega)";
        $sql .= "		  JOIN centros_utilidad as b ON (a.empresa_id = b.empresa_id)";
        $sql .= "			AND (a.centro_utilidad = b.centro_utilidad)";
        $sql .= "		  JOIN empresas as EM ON(b.empresa_id = EM.empresa_id)";
        $sql .= "WHERE  SD.empresa_destino = '" . trim($empresa_id) . "' ";
        $sql .= "AND	  SD.sw_despacho = '0' ";
        $sql .= "UNION DISTINCT ";
        $sql .= "SELECT EM.empresa_id,
				SD.centro_utilidad,
				SD.bodega,
				EM.razon_social||' ::: '||a.descripcion as razon_social ";
        $sql .= "FROM   solicitud_productos_a_bodega_principal SD ";
        $sql .= "		  JOIN bodegas as a ON (SD.farmacia_id = a.empresa_id)";
        $sql .= "			AND(SD.centro_utilidad = a.centro_utilidad)";
        $sql .= "			AND(SD.bodega = a.bodega)";
        $sql .= "		  JOIN centros_utilidad as b ON (a.empresa_id = b.empresa_id)";
        $sql .= "			AND (a.centro_utilidad = b.centro_utilidad)";
        $sql .= "		  JOIN empresas as EM ON(b.empresa_id = EM.empresa_id) ";
        $sql .= "       JOIN solicitud_productos_a_bodega_principal_detalle SE ON (SD.solicitud_prod_a_bod_ppal_id = SE.solicitud_prod_a_bod_ppal_id) ";
        $sql .= "       JOIN inv_mov_pendientes_solicitudes_frm FR ON (SE.solicitud_prod_a_bod_ppal_id = FR.solicitud_prod_a_bod_ppal_id)";
        $sql .= "		  AND  (SE.solicitud_prod_a_bod_ppal_det_id = FR.solicitud_prod_a_bod_ppal_det_id)";
        $sql .= "WHERE  SD.empresa_destino = '" . trim($empresa_id) . "' ";
        $sql .= "AND	  SD.sw_despacho = '1' ";
        $sql .= "ORDER BY empresa_id ";

        /* print_r($sql); */
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    /**
     * Funcion donde se obtienen los pedidos de las farmacias
     *
     * @param string $empresa Identificador de la empresa
     * @param string $farmacia Identificador de la farmacia
     *
     * @return booleano
     */
    function ObtenerPedidosFarmacia($empresa, $farmacia) {
        $info = explode("@", $farmacia);
        $farmacia_id = $info[0];
        $centro_utilidad = $info[1];
        $bodega = $info[2];

        $sql = "SELECT solicitud_prod_a_bod_ppal_id ";
        $sql .= "FROM   solicitud_productos_a_bodega_principal SD ";
        $sql .= "WHERE  SD.empresa_destino = '" . trim($empresa) . "' ";
        $sql .= "AND    SD.farmacia_id = '" . trim($farmacia_id) . "' ";
        $sql .= "AND    SD.centro_utilidad = '" . trim($centro_utilidad) . "' ";
        $sql .= "AND    SD.bodega = '" . trim($bodega) . "' ";
        $sql .= "AND	  SD.sw_despacho = '0' ";
        //$sql .= "AND    SD.estado = '2' ";//En estado de Auditoria - LGTL
        $sql .= "UNION DISTINCT ";
        $sql .= "SELECT DISTINCT SD.solicitud_prod_a_bod_ppal_id ";
        $sql .= "FROM   solicitud_productos_a_bodega_principal SD, ";
        $sql .= "       solicitud_productos_a_bodega_principal_detalle SE, ";
        $sql .= "       inv_mov_pendientes_solicitudes_frm FR ";
        $sql .= "WHERE  SD.empresa_destino = '" . trim($empresa) . "' ";
        $sql .= "AND    SD.farmacia_id = '" . trim($farmacia_id) . "' ";
        $sql .= "AND    SD.centro_utilidad = '" . trim($centro_utilidad) . "' ";
        $sql .= "AND    SD.bodega = '" . trim($bodega) . "' ";
        $sql .= "AND	  SD.sw_despacho = '1' ";
        $sql .= "AND	  SD.solicitud_prod_a_bod_ppal_id = SE.solicitud_prod_a_bod_ppal_id ";
        $sql .= "AND    SE.solicitud_prod_a_bod_ppal_id = FR.solicitud_prod_a_bod_ppal_id ";
        $sql .= "AND    SE.solicitud_prod_a_bod_ppal_det_id = FR.solicitud_prod_a_bod_ppal_det_id ";
        //$sql .= "AND    SD.estado = '2' ";//En estado de Auditoria - LGTL
        $sql .= "ORDER BY 1 ";
        /* print_r($sql); */
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    /**
     * Funcion donde se obtienen las zonas que maneja la empresa
     *
     * @param string $empresa_id Identificador de la empresa
     */
    function ObtenerRutasViajes($datos) {
        $sql .= " SELECT  a.descripcion as ruta,";
        $sql .= "         b.empresa_id,";
        $sql .= "         d.descripcion ";
        $sql .= " FROM    inv_rutasviajes_origen as a, ";
        $sql .= "         inv_rutasviajes_destinos as b, ";
        $sql .= "         empresas as c, ";
        $sql .= "         inv_zonas as d, ";

        $sql .= " WHERE   a.empresa_id='" . $datos['empresa_id'] . "' ";
        $sql .= " AND     a.empresa_id=b.empresa_id ";
        $sql .= " AND     a.rutaviaje_origen_id=b.rutaviaje_origen_id ";
        $sql .= " AND     b.empresa_id=c.empresa_id ";
        $sql .= " AND     c.tipo_dpto_id=e.tipo_dpto_id ";
        $sql .= " AND     c.tipo_mpio_id=e.tipo_mpio_id ";
        $sql .= " AND   e.zona_id=d.zona_id ";
        //print_r($sql);
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        if (!$resultado->EOF) {
            $documentos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $documentos;
    }

    /**
     * Funcion que sirve para consultar los departamentos de colombia
     *
     * @param string $tipo_pais_id
     *
     * @return string $forma con los datos
     */
    function ObtenerDepartamentos($tipo_pais_id) {
        $sql = "SELECT * ";
        $sql .= "FROM   tipo_dptos ";
        $sql .= "WHERE  tipo_pais_id = '" . $tipo_pais_id . "' ";

        $datos = array();
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    /**
     * Funcion donde se consultan los municipios
     *
     * @param array $datos Vector con la informacion delpais, municipio
     *               y ciudad por defecto
     *
     * @return array
     */
    function ObtenerMunicipios($datos) {
        $sql = "SELECT TM.tipo_pais_id   , ";
        $sql .= "       TM.tipo_dpto_id   , ";
        $sql .= "       TM.tipo_mpio_id   , ";
        $sql .= "       TM.municipio ";
        $sql .= "FROM   tipo_mpios TM ";
        $sql .= "WHERE  TM.tipo_pais_id = '" . $datos['tipo_pais_id'] . "' ";
        $sql .= "AND    TM.tipo_dpto_id = '" . $datos['tipo_dpto_id'] . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /**
     * Metodo para obtener el detalle de un documento de despacho
     *
     * @param string $empresa_id identificador del documento
     * @param string $prefijo
     * @param integer $numero
     *
     * @return mixed
     */
    function GetDocumentoDespacho($empresa_id, $prefijo, $numero) {
        $sql = "SELECT EM.empresa_id,  ";
        $sql .= "       EM.tipo_id_tercero, ";
        $sql .= "       EM.id AS tercero_id, ";
        $sql .= "       EM.razon_social AS nombre_tercero, ";
        $sql .= "       EM.direccion, ";
        $sql .= "       TM.municipio, ";
        $sql .= "       TD.departamento ";
        $sql .= "FROM   inv_bodegas_movimiento a, ";
        $sql .= "       inv_bodegas_movimiento_despachos_farmacias b, ";
        $sql .= "       empresas EM, ";
        $sql .= "       tipo_mpios TM,";
        $sql .= "       tipo_dptos TD ";
        $sql .= "WHERE  a.empresa_id = '" . $empresa_id . "' ";
        $sql .= "AND    a.prefijo = '" . $prefijo . "' ";
        $sql .= "AND    a.numero = " . $numero . " ";
        $sql .= "AND    a.empresa_id = b.empresa_id ";
        $sql .= "AND    a.prefijo = b.prefijo ";
        $sql .= "AND    a.numero = b.numero ";
        $sql .= "AND    b.farmacia_id = EM.empresa_id ";
        $sql .= "AND    EM.tipo_pais_id = TM.tipo_pais_id ";
        $sql .= "AND    EM.tipo_dpto_id = TM.tipo_dpto_id ";
        $sql .= "AND   	EM.tipo_mpio_id = TM.tipo_mpio_id ";
        $sql .= "AND    TM.tipo_pais_id = TD.tipo_pais_id ";
        $sql .= "AND    TM.tipo_dpto_id = TD.tipo_dpto_id ";
        //print_r($sql);
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        if (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $datos['tipo'] = "FARMACIA";
            $datos['titulo'] = "INFORMACI�N DE LA FARMACIA";
            $rst->MoveNext();
        }
        $rst->Close();

        if (empty($datos)) {
            $sql = "SELECT TE.tipo_id_tercero, ";
            $sql .= "       TE.tercero_id, ";
            $sql .= "       TE.nombre_tercero, ";
            $sql .= "       TE.direccion, ";
            $sql .= "       TM.municipio, ";
            $sql .= "       TD.departamento ";
            $sql .= "FROM   inv_bodegas_movimiento a, ";
            $sql .= "       inv_bodegas_movimiento_despachos_clientes b, ";
            $sql .= "       terceros TE, ";
            $sql .= "       tipo_mpios TM,";
            $sql .= "       tipo_dptos TD ";
            $sql .= "WHERE  a.empresa_id = '" . $empresa_id . "' ";
            $sql .= "AND    a.prefijo = '" . $prefijo . "' ";
            $sql .= "AND    a.numero = " . $numero . " ";
            $sql .= "AND    a.empresa_id = b.empresa_id ";
            $sql .= "AND    a.prefijo = b.prefijo ";
            $sql .= "AND    a.numero = b.numero ";
            $sql .= "AND    b.tipo_id_tercero = TE.tipo_id_tercero ";
            $sql .= "AND    b.tercero_id = TE.tercero_id ";
            $sql .= "AND    TE.tipo_pais_id = TM.tipo_pais_id ";
            $sql .= "AND    TE.tipo_dpto_id = TM.tipo_dpto_id ";
            $sql .= "AND   	TE.tipo_mpio_id = TM.tipo_mpio_id ";
            $sql .= "AND    TM.tipo_pais_id = TD.tipo_pais_id ";
            $sql .= "AND    TM.tipo_dpto_id = TD.tipo_dpto_id ";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;

            if (!$rst->EOF) {
                $datos = $rst->GetRowAssoc($ToUpper = false);
                $datos['tipo'] = "CLIENTE";
                $datos['titulo'] = "INFORMACI�N DEL CLIENTE";
                $rst->MoveNext();
            }
            $rst->Close();
        }
        //print_r($sql);
        return $datos;
    }

    /*     * ********************************************************************************
     * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
     * consulta sql 
     * 
     * @param 	string  $sql	sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
     * @return rst 
     * ********************************************************************************** */

    function ConexionBaseDatos($sql) {
        list($dbconn) = GetDBConn();
        //	$dbconn->debug=true;
        $rst = $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
            $this->mensajeDeError = "<b class=\"label\">" . $this->frmError['MensajeError'] . "</b>";
            return false;
        }
        return $rst;
    }

    /**
     * Funcion donde se consultan los documentos de devolucion hechos con el I006
     *
     * @param array $datos Vector con la informacion de los terceros por el documento
     * 
     *
     * @return array
     */
    function ObtenerTerceroDocumentoIngresoDevolucionPrestamo($Prefijo, $Numero) {
        $sql = "SELECT  t.* ";

        $sql .= "FROM   inv_bodegas_movimiento_devoluciones ibmd, ";
        $sql .= "       terceros t ";
        $sql .= "WHERE  ibmd.prefijo = '" . $Prefijo . "' ";
        $sql .= "AND    ibmd.numero = '" . $Numero . "' ";
        $sql .= "AND    ibmd.tipo_id_tercero = t.tipo_id_tercero ";
        $sql .= "AND    ibmd.tercero_id = t.tercero_id ";

        //print_r($sql);
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /**
     * Funcion donde se consultan los documentos de devolucion hechos con el I007
     * Ingreso Por Prestamo de Otras Entidades
     * @param array $datos Vector con la informacion de los terceros por el documento
     * 
     *
     * @return array
     */
    function ObtenerTerceroDocumentoIngresoPrestamo($Prefijo, $Numero) {
        $sql = "SELECT  t.* ";

        $sql .= "FROM   inv_bodegas_movimiento_prestamos ibmd, ";
        $sql .= "       terceros t ";
        $sql .= "WHERE  ibmd.prefijo = '" . $Prefijo . "' ";
        $sql .= "AND    ibmd.numero = '" . $Numero . "' ";
        $sql .= "AND    ibmd.tipo_id_tercero = t.tipo_id_tercero ";
        $sql .= "AND    ibmd.tercero_id = t.tercero_id ";

        //print_r($sql);
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * 	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
     * 	Existentes en el sistema, segun el grupo.
     */

    function ObtenerContratoId($empresa_id) {
        //$this->debug=true;
        $sql = "SELECT pc.plan_id, pl.*,
              lp.codigo_lista
              FROM esm_parametros_contrato as pc
              JOIN planes as pl ON (pc.plan_id = pl.plan_id) and (pl.estado = '1')
              JOIN listas_precios as lp ON (pl.lista_precios = lp.codigo_lista) and (lp.codigo_lista = 
              (
              select lpd.codigo_lista
              from
              listas_precios_detalle as lpd
              where
              empresa_id= '" . $empresa_id . "'
              group by(lpd.codigo_lista)
              ))
              where pc.empresa_id IS NULL
              and pc.sw_estado = '1'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function Precio($plan_id, $codigo_producto, $empresa_id, $sw_bodegamindefensa, $rango_pendiente = '0') {
        $sql = " select 
                    fc_precio_producto_plan(" . $plan_id . ",'" . $codigo_producto . "','" . $empresa_id . "','" . $sw_bodegamindefensa . "','" . $rango_pendiente . "') as precio;";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function codigo_mindefensa($codigo_producto) {
        $sql = " select 
                    fc_codigo_mindefensa('" . $codigo_producto . "') as codigo_mindefensa;";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /**
     * Funcion donde busca los productos bloqueados por lote
     *
     * @param var  $codigo_producto la informacion del codigo de producto
     * @return booleano
     */
    function Parametros_Retencion($empresa_id, $anio_retencion) {

        if ($anio_retencion != "") {
            $wh .= "AND	anio = '" . $anio_retencion . "' ";
        } else {
            $wh .= "AND	anio = TO_CHAR(NOW(),'YYYY') ";
        }
        $sql = "SELECT	* ";
        $sql .= "FROM	vnts_bases_retenciones ";
        $sql .= "WHERE	estado='1' ";
        $sql .= $wh;
        $sql .= "AND	empresa_id = '" . $empresa_id . "'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        if (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    function FacturaProveedorCabecera($EmpresaId, $CodigoProveedorId, $NumeroFactura) {
        //$this->debug=true;
        $sql = "SELECT     
			   c.*,
			   u.*,
			   f.subtotal,
			   f.iva_total,
			   f.total,
			   TO_CHAR(c.fecha_registro,'YYYY') as anio_factura";
        $sql .= " FROM     
					inv_facturas_proveedores c
					JOIN (
					SELECT
					x.codigo_proveedor_id,
					x.numero_factura,
					SUM(((x.valor/((x.porc_iva/100)+1))*x.cantidad)) as subtotal,
					SUM(((x.valor-(x.valor/((x.porc_iva/100)+1)))*x.cantidad)) as iva_total,
					SUM((x.valor * x.cantidad)) as total
					FROM
					inv_facturas_proveedores_d as x
					WHERE
					x.numero_factura = '" . $NumeroFactura . "'
					and x.codigo_proveedor_id = " . $CodigoProveedorId . "
					group by
					x.codigo_proveedor_id,
					x.numero_factura
					) as f ON (c.numero_factura = f.numero_factura)
					AND (c.codigo_proveedor_id = f.codigo_proveedor_id),
					system_usuarios u ";
        $sql .= "WHERE     
					c.codigo_proveedor_id=" . $CodigoProveedorId . " 
					and 
					c.empresa_id='" . $EmpresaId . "' 
					and
					c.numero_factura ='" . $NumeroFactura . "'
					and
					c.usuario_id = u.usuario_id 
					";

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

    function Listado_FacturasCliente($empresa_id, $filtros, $offset) {

        if ($filtros['prefijo'] != "")
            $where .= " AND a.prefijo = '" . $filtros['prefijo'] . "' ";
        if ($filtros['numero'] != "")
            $where .= " AND a.factura_fiscal = '" . $filtros['numero'] . "' ";
        if ($filtros['tipo_id_tercero'] != "")
            $where .= " AND a.tipo_id_tercero = '" . $filtros['tipo_id_tercero'] . "' ";


        $sql = "SELECT
	a.empresa_id,
	a.factura_fiscal,
	a.prefijo,
	a.documento_id,
	i.descripcion,
	i.texto1,
	i.texto2,
	i.texto3,
	i.mensaje,
	a.tipo_id_tercero,
	a.tercero_id,
	c.nombre_tercero,
	c.direccion,
	c.telefono,
	h.pais||'-'||g.departamento ||'-'||f.municipio as ubicacion,
	a.fecha_registro,
	a.usuario_id,
	a.tipo_id_vendedor,
	a.vendedor_id,
	b.nombre,
	a.valor_total,
	a.saldo,
	a.pedido_cliente_id,
	a.observaciones,
	a.fecha_vencimiento_factura,
	a.porcentaje_rtf,
	a.porcentaje_ica,
	a.porcentaje_reteiva,
	e.razon_social,
	e.tipo_id_tercero as tipo_id_empresa,
	e.id,
	e.direccion as direccion_empresa,
	e.telefonos as telefono_empresa,
	e.digito_verificacion,
	l.pais as pais_empresa,
	k.departamento as departamento_empresa,
	j.municipio as municipio_empresa,
	TO_CHAR(a.fecha_registro,'YYYY') as anio_factura,
	m.subtotal,
	m.iva_total
	FROM
	inv_facturas_despacho as a
	JOIN vnts_vendedores as b ON (a.tipo_id_vendedor = b.tipo_id_vendedor)
	AND (a.vendedor_id = b.vendedor_id)
	JOIN terceros as c ON (a.tipo_id_tercero = c.tipo_id_tercero)
	AND (a.tercero_id = c.tercero_id)
	JOIN system_usuarios as d ON (a.usuario_id = d.usuario_id)
	JOIN empresas as e ON (a.empresa_id = e.empresa_id)
	JOIN tipo_mpios as f ON (c.tipo_pais_id = f.tipo_pais_id)
	AND (c.tipo_dpto_id = f.tipo_dpto_id)
	AND (c.tipo_mpio_id = f.tipo_mpio_id)
	JOIN tipo_dptos as g ON (f.tipo_pais_id = g.tipo_pais_id)
	AND (f.tipo_dpto_id = g.tipo_dpto_id)
	JOIN tipo_pais as h ON (g.tipo_pais_id = h.tipo_pais_id)
	
	JOIN tipo_mpios as j ON (e.tipo_pais_id = j.tipo_pais_id)
	AND (e.tipo_dpto_id = j.tipo_dpto_id)
	AND (e.tipo_mpio_id = j.tipo_mpio_id)
	JOIN tipo_dptos as k ON (j.tipo_pais_id = k.tipo_pais_id)
	AND (j.tipo_dpto_id = k.tipo_dpto_id)
	JOIN tipo_pais as l ON (k.tipo_pais_id = l.tipo_pais_id)
	
	JOIN documentos as i ON (a.empresa_id = i.empresa_id)
	AND (a.documento_id = i.documento_id)
	
	JOIN (
		SELECT
		a.empresa_id,
		a.prefijo,
		a.factura_fiscal,
		SUM((a.valor_unitario*a.cantidad)) as subtotal,
		SUM(((a.valor_unitario*a.cantidad)*(a.porc_iva/100))) as iva_total
		FROM
		inv_facturas_despacho_d as a
		group by a.empresa_id,a.prefijo,a.factura_fiscal
		)as m ON (m.empresa_id= a.empresa_id)
		AND (m.prefijo = a.prefijo)
		AND (m.factura_fiscal = a.factura_fiscal)
	
	WHERE
	a.empresa_id = '" . $empresa_id . "' 
	AND a.tercero_id ILIKE '%" . $filtros['tercero_id'] . "%'
	AND c.nombre_tercero ILIKE '%" . $filtros['nombre_tercero'] . "%' ";
        $sql .= $where;

        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, null, $offset);
        $sql .= "ORDER BY a.fecha_registro DESC ";
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

    /**
     * Funcion donde se consultan los documentos de devolucion hechos con el I007
     * Ingreso Por Prestamo de Otras Entidades
     * @param array $datos Vector con la informacion de los terceros por el documento
     * 
     *
     * @return array
     */
    function JustificacionesDespachos($empresa_id, $prefijo, $numero) {
        $sql = "SELECT  ";
        $sql .= " codigo_producto, ";
        $sql .= " fc_descripcion_producto(codigo_producto) as descripcion, ";
        $sql .= " cantidad_pendiente, ";
        $sql .= " observacion ";
        $sql .= "FROM   inv_bodegas_movimiento_justificaciones_pendientes ";
        $sql .= "WHERE  empresa_id = '" . $empresa_id . "' ";
        $sql .= "AND    prefijo = '" . $prefijo . "' ";
        $sql .= "AND    numero = " . $numero . ";";

        /* print_r($sql); */
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

    /**
     * Funcion donde se consultan los documentos de devolucion hechos con el I007
     * Ingreso Por Prestamo de Otras Entidades
     * @param array $datos Vector con la informacion de los terceros por el documento
     * 
     *
     * @return array
     */
    function DocumentosDespacho_AFarmacia($empresa_id, $centro_utilidad, $bodega) {
        $sql = "SELECT
	a.empresa_id,
	a.prefijo||'-'||a.numero as despacho,
	a.empresa_id||'@'||a.prefijo||'@'||a.numero as documento,
	'DESPACHADO POR: '|| c.razon_social ||' PEDIDO: '|| a.solicitud_prod_a_bod_ppal_id as observacion
	FROM
	inv_bodegas_movimiento_despachos_farmacias as a
	JOIN solicitud_productos_a_bodega_principal as b ON(a.solicitud_prod_a_bod_ppal_id = b.solicitud_prod_a_bod_ppal_id)
	JOIN empresas as c ON (a.empresa_id = c.empresa_id)
	WHERE
	b.farmacia_id = '" . trim($empresa_id) . "'
	AND b.centro_utilidad = '" . trim($centro_utilidad) . "'
	AND b.bodega = '" . trim($bodega) . "'
	AND a.sw_confirma = '0'
	ORDER BY a.numero;";

        /* print_r($sql); */
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

    function obtener_prefijo_fi($empresa_id, $tipo_documento) {

        $sql = "
                select COALESCE(b.prefijo ,'') as prefijo_fi
                from documentos a 
                inner join prefijos_financiero b on a.prefijos_financiero_id = b.id
                where a.prefijo='{$tipo_documento}' and a.empresa_id='{$empresa_id}' ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        while (!$resultado->EOF) {
            $datos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        // return $sql;
        return $datos;
    }
    
    function obtener_codigo_invima_producto($codigo_producto) {
        $sql = "SELECT codigo_invima FROM inventarios_productos WHERE codigo_producto = '{$codigo_producto}'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        if (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }
    
    function obtener_observaciones_ordene_compra($prefijo, $numero) {

        $sql = "
                select b.observacion  
                from inv_recepciones_parciales a 
                inner join compras_ordenes_pedidos b on a.orden_pedido_id = b.orden_pedido_id
                where a.prefijo='{$prefijo}' and a.numero={$numero};
        ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        if (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }
 
    
    function obtener_encabezado_devolucion_proveedores($empresa_id, $prefijo, $numero) {

        $sql = "
            select * from inv_bodegas_movimiento_devolucion_proveedor a            
            WHERE a.empresa_id = '{$empresa_id}' AND a.prefijo = '{$prefijo}' AND a.numero = {$numero}; ";
            
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $datos = Array();
        while (!$resultado->EOF) {
            $datos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        return $datos;
    }
    
    function obtener_detalle_devolucion_proveedores($empresa_id, $prefijo, $numero) {

        $sql = "
                SELECT
                a.*,
                b.descripcion,
                b.unidad_id,
                b.contenido_unidad_venta,                
                fc_descripcion_producto(b.codigo_producto) as nombre,
                (a.valor_unitario * a.cantidad) as valor,
                (a.valor_unitario*(a.porcentaje_gravamen/100)) as iva,
                (a.valor_unitario+(a.valor_unitario*(a.porcentaje_gravamen/100))) as valor_unitario_iva,
                ((a.cantidad)*(a.valor_unitario+(a.valor_unitario*(a.porcentaje_gravamen/100)))) as valor_total_iva,
                (((a.total_costo)/((a.porcentaje_gravamen/100)+1))/a.cantidad) as valor_unit_1,
                ((a.total_costo/a.cantidad)-(((a.total_costo)/((a.porcentaje_gravamen/100)+1))/a.cantidad)) as iva_1,
                ((((a.total_costo)/((a.porcentaje_gravamen/100)+1))/a.cantidad)*a.cantidad) as valor_total_1,
                (((a.total_costo/a.cantidad)-(((a.total_costo)/((a.porcentaje_gravamen/100)+1))/a.cantidad))*a.cantidad) as iva_total_1,
                c.sw_insumos,
                c.sw_medicamento
                FROM inv_bodegas_movimiento_d a
                inner join inventarios_productos b on a.codigo_producto = b.codigo_producto   
                inner join inv_grupos_inventarios c on b.grupo_id = c.grupo_id
                WHERE a.empresa_id = '{$empresa_id}' AND a.prefijo = '{$prefijo}' AND a.numero = {$numero}
                ORDER BY a.codigo_producto ";
                
                /*echo "<pre>";
                print_r($sql);
                echo "</pre>";*/
                
            
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $datos = Array();
        while (!$resultado->EOF) {
            $datos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        return $datos;
    }
    
   
    
     /*********************************
     * funcion que retorna el tipo documento
      * 
      * @return vector tipos_id_pacientes
     */
    function tipo_Paciente(){
        $sql="SELECT
              tipo_id_paciente,descripcion,indice_de_orden,codigo_alterno
              FROM
              tipos_id_pacientes
              ORDER BY indice_de_orden ASC;";
        if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $tipo=Array();
                  while(!$resultado->EOF)
                  {
                    $tipo[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                  
                  return $tipo;
    }

}

?>