<?php

/* * ****************************************************************************
 * $Id: DocumentosSQL.class.php,v 1.2 2007/02/06 20:42:34 jgomez Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * $Revision: 1.2 $ 
 * 
 * @autor Jaime Gomez
 * ****************************************************************************** */

class DocumentosSQL {

    function DocumentosSQL() {
        
    }

    /*     * ********************************************************************************
     * Funcion donde se listan modulos
     * 
     * @return array 
     * ********************************************************************************* */

    function ListarComponentesSegunGrupo($modulo) {
        GLOBAL $ADODB_FETCH_MODE;
        $sql = "select a.descripcion_grupo,a.grupo_id,b.modulo_tipo,b.modulo,b.componente_id,b.descripcion_componente 
      from  
      system_modulos_permisos_grupos_componentes as b,
      system_modulos_permisos_grupos as a 
      where a.modulo=b.modulo and 
      a.grupo_id=b.grupo_id and 
      b.modulo='" . $modulo . "' order by a.descripcion_grupo";
        //if(!$rst = $this->ConexionBaseDatos($sql)) 
        //{  $cad="ne se hizo la consulta";
        // return $cad;
        //}
        //else
        //{     
        list($dbconn) = GetDBconn();
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        $retorno = array();
        /* while(!$rst->EOF)
          {
          $retorno[] = $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
          } */

        while ($datos = $result->FetchRow()) {
            $retorno[$datos['descripcion_grupo']][] = $datos;
        }

        $result->Close();
        return $retorno;

        //}
    }

    /*     * **********************************************************************************
     *
     * Funcion que lista planes
     *
     * *********************************************************************************** */

    function BuscarPlanesStip($tip_bus, $elemento, $offset, $empresa) {
        if ($tip_bus == 6 || $tip_bus == 0) {
            $sql1 = "select count(*) 
      from planes 
      where empresa_id='" . $empresa . "'";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select * from planes where empresa_id='" . $empresa . "'
      limit " . $this->limit . " OFFSET " . $this->offset . "";
        }

        if ($tip_bus == 1) {
            $sql1 = "select count(*) from planes
      where plan_id =" . $elemento . " and empresa_id='" . $empresa . "'";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select * from planes 
       where 
       plan_id =" . $elemento . " and empresa_id='" . $empresa . "'
       limit " . $this->limit . " OFFSET " . $this->offset . "";
        }


        if ($tip_bus == 2) {
            $sql1 = "select count(*) 
        from 
        planes
        where plan_descripcion LIKE '%" . strtoupper($elemento) . "%' and empresa_id='" . $empresa . "' ";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select * 
        from 
        planes 
        where plan_descripcion LIKE '%" . strtoupper($elemento) . "%' and empresa_id='" . $empresa . "' 
        order by plan_id 
        limit " . $this->limit . " OFFSET " . $this->offset . "";
        }

        if ($tip_bus == 3) {

            $sql1 = "select  count(*) 
      from 
      planes 
      where num_contrato = '" . $elemento . "' and empresa_id='" . $empresa . "'";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select  * 
       from 
       planes
       where num_contrato = '" . $elemento . "' and empresa_id='" . $empresa . "'
       order by plan_id
       limit " . $this->limit . " OFFSET " . $this->offset . "";
        }

        if ($tip_bus == 4) {

            $sql1 = "select  count(*) 
      from 
      planes 
      where tercero_id= '" . $elemento . "' and empresa_id='" . $empresa . "'";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select  * 
       from 
       planes
       where tercero_id = '" . $elemento . "' and empresa_id='" . $empresa . "'
       order by plan_id
       limit " . $this->limit . " OFFSET " . $this->offset . "";
        }


        if ($tip_bus == 5) {

            $sql1 = "select  count(*) 
      from 
      planes 
      where sw_tipo_plan= '" . $elemento . "' and empresa_id='" . $empresa . "'";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select  * 
       from 
       planes
       where sw_tipo_plan = '" . $elemento . "' and empresa_id='" . $empresa . "'
       order by plan_id
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

    /*     * ******************************************************************************
     * FUNCION QUE CUENTA PLANES SEGUN TIPO DE BUSQUEDA
     * ******************************************************************************* */

    function ContarPlanesStip($tip_bus, $elemento, $empresa) {
        if ($tip_bus == 6 || $tip_bus == 0) {
            $sql1 = "select count(*) 
      from planes 
      where empresa_id='" . $empresa . "'";
        }

        if ($tip_bus == 1) {
            $sql1 = "select count(*) from planes
      where plan_id =" . $elemento . " and empresa_id='" . $empresa . "'";
        }


        if ($tip_bus == 2) {
            $sql1 = "select count(*) 
        from 
        planes
        where plan_descripcion LIKE '%" . strtoupper($elemento) . "%' and empresa_id='" . $empresa . "' ";
        }

        if ($tip_bus == 3) {

            $sql1 = "select  count(*) 
      from 
      planes 
      where num_contrato = '" . $elemento . "' and empresa_id='" . $empresa . "'";
        }

        if ($tip_bus == 4) {

            $sql1 = "select  count(*) 
      from 
      planes 
      where tercero_id= '" . $elemento . "' and empresa_id='" . $empresa . "'";
        }


        if ($tip_bus == 5) {

            $sql1 = "select  count(*) 
      from 
      planes 
      where sw_tipo_plan= '" . $elemento . "' and empresa_id='" . $empresa . "'";
        }



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
     * Funcion que lista CLIENTES
     *
     * *********************************************************************************** */

    function BuscarClientesStip($tip_bus, $elemento, $offset) {
        if ($tip_bus == 4 || $tip_bus == 0) {
            $sql1 = "select count(*) 
      from 
      tipos_cliente";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select * from tipos_cliente 
      limit " . $this->limit . " OFFSET " . $this->offset . "";
        }

        if ($tip_bus == 1) {
            $sql1 = "select count(*) 
       from tipos_cliente
      where tipo_cliente ='" . $elemento . "'";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select * from tipos_cliente 
       where 
       tipo_cliente ='" . $elemento . "' order by tipo_cliente
       limit " . $this->limit . " OFFSET " . $this->offset . "";
        }


        if ($tip_bus == 2) {
            $sql1 = "select count(*) 
        from 
        tipos_cliente
        where descripcion LIKE '%" . strtoupper($elemento) . "%'";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select * 
        from 
        tipos_cliente
        where descripcion LIKE '%" . strtoupper($elemento) . "%' 
        order by tipo_cliente
        limit " . $this->limit . " OFFSET " . $this->offset . "";
        }

        if ($tip_bus == 3) {

            $sql1 = "select  count(*) 
      from 
      tipos_cliente
      where regimen_id = '" . $elemento . "'";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select  * 
       from 
       tipos_cliente
       where regimen_id = '" . $elemento . "' 
       order by tipo_cliente
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

    /*     * ******************************************************************************
     * FUNCION QUE CUENTA CLIENTES SEGUN TIPO DE BUSQUEDA
     * ******************************************************************************* */

    function ContarClientesStip($tip_bus, $elemento) {
        if ($tip_bus == 4 || $tip_bus == 0) {
            $sql1 = "select count(*) 
      from tipos_cliente";
        }

        if ($tip_bus == 1) {
            $sql1 = "select count(*) from tipos_cliente
      where tipo_cliente ='" . $elemento . "'";
        }


        if ($tip_bus == 2) {
            $sql1 = "select count(*) 
        from 
        tipos_cliente
        where descripcion LIKE '%" . strtoupper($elemento) . "%'";
        }

        if ($tip_bus == 3) {

            $sql1 = "select  count(*) 
      from 
      tipos_cliente
      where regimen_id = '" . $elemento . "'";
        }



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
     * Funcion que lista cuentas
     *
     * *********************************************************************************** */

    function BuscarCuentasStip($tip_bus, $elemento, $offset, $empresa) {

        if ($tip_bus == 4 || $tip_bus == 0) {
            $sql1 = "select  count(*) 
            from 
            cg_plan_de_cuentas where empresa_id='" . $empresa . "'";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select * from cg_plan_de_cuentas  
            where empresa_id='" . $empresa . "' order by cuenta 
            limit " . $this->limit . " OFFSET " . $this->offset . "";
        }

        if ($tip_bus == 2) {
            $sql1 = "select count(*) 
      from 
      cg_plan_de_cuentas 
      where 
      cuenta LIKE '" . strtoupper($elemento) . "%' and empresa_id='" . $empresa . "' ";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select * from cg_plan_de_cuentas where cuenta LIKE '" . strtoupper($elemento) . "%' 
       and empresa_id='" . $empresa . "' order by cuenta
       limit " . $this->limit . " OFFSET " . $this->offset . "";
        }


        if ($tip_bus == 1) {
            $sql1 = "select count(*) 
        from 
        cg_plan_de_cuentas 
        where cuenta ='" . $elemento . "' and empresa_id='" . $empresa . "'";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select * from cg_plan_de_cuentas 
        where cuenta ='" . $elemento . "' and empresa_id='" . $empresa . "' 
        order by cuenta 
        limit " . $this->limit . " OFFSET " . $this->offset . "";
        }

        if ($tip_bus == 3) {

            list($elemento1, $elemento2) = explode("-", $elemento);
            $sql1 = "select  count(*) 
      from 
      cg_plan_de_cuentas 
      where cuenta >= '" . $elemento1 . "' and cuenta <= '" . $elemento2 . "' and empresa_id='" . $empresa . "'";
            $this->ProcesarSqlConteo($sql1, 10, $offset);

            $sql = " select  * from cg_plan_de_cuentas 
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

    function ContarCuentasStip($tip_bus, $elemento, $empresa) {

        if ($tip_bus == 4 || $tip_bus == 0) {
            $sql1 = "select  count(*) 
         from 
         cg_plan_de_cuentas where empresa_id='" . $empresa . "'";
        }

        if ($tip_bus == 2) {
            $sql1 = "select count(*) 
      from 
      cg_plan_de_cuentas 
      where 
      cuenta LIKE '" . strtoupper($elemento) . "%' and empresa_id='" . $empresa . "' ";
        }


        if ($tip_bus == 1) {
            $sql1 = "select count(*) 
        from 
        cg_plan_de_cuentas 
        where cuenta ='" . $elemento . "' and empresa_id='" . $empresa . "'";
        }

        if ($tip_bus == 3) {

            list($elemento1, $elemento2) = explode("-", $elemento);
            $sql1 = "select  count(*) 
        from 
        cg_plan_de_cuentas 
        where cuenta >= '" . $elemento1 . "' and cuenta <= '" . $elemento2 . "' and empresa_id='" . $empresa . "'";
        }



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

    /*     * ********************************************************************************
     * Funcion que inserta  en la tabla cg_parametros_documentos, 
     * 
     * @return mensaje de confirmacion
     * ********************************************************************************* */

    function Auditoria($auditoria_id, $documento_id, $empresa_id, $fechareg, $usuario_id) {
        $sql = "insert into  auditoria_documentos
      values(" . $auditoria_id . ",'" . $documento_id . "','" . $empresa_id . "',
             '" . $fechareg . "','" . $usuario_id . "');";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $cad = "no se hizo la inserci?n";
            return $cad;
        } else {
            $cad = "Parametros Adicionados Satisfactoriamente";
            $rst->Close();
            return $cad;
        }
    }

    /*     * **********************************************************************************
     *
     * Funcion que saca el nuevo id
     *
     * *********************************************************************************** */

    function AudiId() {
        $sql1 = "select nextval('auditoria_documentos_auditoria_id_seq'::regclass)";
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

    /*     * ********************************************************************************
     * Funcion que inserta  en la tabla cg_parametros_documentos, 
     * 
     * @return mensaje de confirmacion
     * ********************************************************************************* */

    function Parametros($doc_id, $empresa_id, $cuenta, $naturaleza, $agrupar, $tipo_cliente, $plan_id, $indice) {
        if ($tipo_cliente == 0) {
            $tipo_cliente = 'NULL';
        }
        if ($plan_id == 0) {
            $plan_id = "NULL";
        }
        $sql = "insert into  cg_parametros_documentos
      values(" . $doc_id . ",'" . $empresa_id . "','" . $cuenta . "',
             '" . $naturaleza . "','" . $agrupar . "'," . $tipo_cliente . ",
              " . $plan_id . "," . $indice . ");";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $cad = "no se hizo la inserci?n";
            return $cad;
        } else {
            $cad = "Parametros Adicionados Satisfactoriamente";
            $rst->Close();
            return $cad;
        }
    }

    /*     * *****************************************************************************
     * up parametros
     * ****************************************************************************** */

    function UpParametros($doc_id, $empresa_id, $cuenta, $naturaleza, $agrupar, $tipo_cliente, $plan_id, $indice) {
        if ($tipo_cliente == 0) {
            $tipo_cliente = 'NULL';
        }
        if ($plan_id == 0) {
            $plan_id = "NULL";
        }
        $sql = "Update cg_parametros_documentos 
          SET cuenta=" . $cuenta . ",
          naturaleza='" . $naturaleza . "',
          sw_agrupar_cuentas='" . $agrupar . "',
          tipo_cliente=" . $tipo_cliente . ",
          plan_id=" . $plan_id . "
          where indice_automatico=" . $indice . "";


        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $cad = "no se hizo la inserci?n";
            return $cad;
        } else {
            $cad = "Parametros Actualizados Satisfactoriamente";
            $rst->Close();
            return $cad;
        }
    }

    /*     * ********************************************************************************
     * Funcion que inserta  en la tabla documentos, 
     * 
     * @return mensaje de confirmacion
     * ********************************************************************************* */

    function NueDocumento($doc_id, $empresa_id, $tipo_doc_id, $prefijo, $sw_estado, $numeracion, $numero_dig, $txt1, $txt2, $txt3, $mensaje, $descrip, $sw_conta, $prefijos_financiero_id) {
        
        if($prefijos_financiero_id =="")
            $prefijos_financiero_id = 'NULL';
        
        $sql = "insert into documentos 
                values(default,'" . $empresa_id . "','" . $tipo_doc_id . "',
                '" . strtoupper($prefijo) . "','" . $sw_estado . "'," . $numeracion . ",
                " . $numero_dig . ",'" . $txt1 . "','" . $txt2 . "',
                '" . $txt3 . "','" . $mensaje . "','" . $descrip . "','" . $sw_conta . "', {$prefijos_financiero_id});";

        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $cad = "no se hizo la inserci?n";
            return $cad;
        } else {
            $cad = "Documento Creado Satisfactoriamente";
            $rst->Close();
            return $cad;
        }
    }

    /*     * **********************************************************************************
     *
     * Funcion que consulta un documento determinado ,segun el tipo de empresa.
     *
     * *********************************************************************************** */

    function BuscarDocumento($doc_id, $empresa) {

        $sql = " 
            select a.*, coalesce(b.prefijo,'') as prefijo_fi from documentos a
            left join prefijos_financiero b on a.prefijos_financiero_id = b.id 
            where empresa_id='{$empresa}' and documento_id='{$doc_id}'";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {

            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();

        /*echo "<pre>";
        print_r($documentos);
        echo "</pre>";*/
        return $documentos;
    }

    /*     * **********************************************************************************
     *
     * Funcion que consulta si el prefijo ya exsite 
     *
     * *********************************************************************************** */

    function ConsultarPrefijo($prefijo, $empresa) {

        echo $sql = " select * from documentos where 
      empresa_id='" . $empresa . "' and prefijo='" . strtoupper($prefijo) . "' 
      and sw_estado=1";


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
     * Funcion que consulta nombre empresa
     *
     * *********************************************************************************** */

    function Consultaempresa($empresa_id) {

        echo $sql = " select razon_social from empresas where 
      empresa_id='" . $empresa_id . "'";


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
     * Funcion que consulta los parametros de un documento determinado ,segun el tipo de empresa.
     *
     * *********************************************************************************** */

    function BuscarParametroDocumento($doc_id, $empresa) {


        $sql = " select *
      from cg_parametros_documentos 
      where 
      empresa_id='" . $empresa . "' and documento_id='" . $doc_id . "' order by indice_automatico";
        // '".$doc_id."'


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
     * Funcion que consulta los parametros de un documento determinado ,segun el tipo de empresa.
     *
     * *********************************************************************************** */

    function BuscarParametroDocumentoi($doc_id, $empresa, $ia) {
        $sql = " select *
      from cg_parametros_documentos 
      where 
      empresa_id='" . $empresa . "' and documento_id='" . $doc_id . "' 
      and indice_automatico=" . $ia . " order by indice_automatico";
        // '".$doc_id."'


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
     * Funcion que consulta DESCRIPCION DEL CLIENTE 
     *
     * *********************************************************************************** */

    function BuscarNomCliente($tipo_cliente) {

        $sql = " select descripcion
      from tipos_cliente
      where 
      tipo_cliente='" . $tipo_cliente . "'";
        // '".$doc_id."'


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
     * Funcion que consulta DESCRIPCION DEL PLAN
     *
     * *********************************************************************************** */

    function BuscarNomPLAN($plan_id) {

        $sql = " select plan_descripcion
      from planes
      where 
      plan_id=" . $plan_id . "";
        // '".$doc_id."'


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
     * Funcion que consulta un descripcion de tipo de documento.
     *
     * *********************************************************************************** */

    function BuscarDescripcion($doc_tip) {

        $sql = " select descripcion from tipos_doc_generales
      where 
      tipo_doc_general_id='" . $doc_tip . "'";


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
     * Funcion que lista los documentos segun el tipo de empresa.
     *
     * *********************************************************************************** */

    function ListarDocumentos($id_doc, $empresa) {

        $sql = " 
            select a.*, coalesce(b.prefijo,'') as prefijo_fi from documentos a
            left join prefijos_financiero b on a.prefijos_financiero_id = b.id 
            where empresa_id='{$empresa}' and tipo_doc_general_id='{$id_doc}'  
            order by tipo_doc_general_id";

        /* echo "<pre>";
          print_r($sql);
          echo "</pre>"; */


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

        $sql = " select * from tipos_doc_generales 
       order by tipo_doc_general_id";


        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $documentos = Array();
        while (!$resultado->EOF) {
            $resultado->fields[1] = strtoupper($resultado->fields[1]);
            $resultado->fields[1] = ereg_replace("?", "E", $resultado->fields[1]);
            $documentos[$resultado->fields[1]] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $documentos;
    }

    /*     * **********************************************************************************
     *
     * Funcion que lista los tipos de clientes .
     *
     * *********************************************************************************** */

    function Clientes($offset) {

        $sql1 = "select count(*) 
      from 
      tipos_cliente";
        $this->ProcesarSqlConteo($sql1, 10, $offset);


        $sql = " select * from tipos_cliente 
      limit " . $this->limit . " OFFSET " . $this->offset . "";


        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $clientes = Array();
        while (!$resultado->EOF) {
            $clientes[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $clientes;
    }

// /************************************************************************************
// *
// *Funcion que lista los tipos de clientes .
// *
// *************************************************************************************/    
//     function Planes($offset,$empresa_id)
//     { 
//      
//       $sql1="select count(*) 
//       from 
//       planes where empresa_id='".$empresa_id."'";
//       $this->ProcesarSqlConteo($sql1,10,$offset);     
//       
//       echo $sql=" select * from planes where empresa_id='".$empresa_id."'
//       limit ".$this->limit." OFFSET ".$this->offset.""; 
//        
//      
//       if(!$resultado = $this->ConexionBaseDatos($sql))
//         return false;
//         
//       $planes=Array();
//       while(!$resultado->EOF)
//       {
//         $planes[] = $resultado->GetRowAssoc($ToUpper = false);
//         $resultado->MoveNext();
//       }
//       
//       $resultado->Close();
//       return $planes;
//      }     

    /*     * **********************************************************************************
     *
     * Funcion que cuenta cuentas
     *
     * *********************************************************************************** */
    function contar($empresa) {
        $sql1 = "select count(*) 
      from 
      cg_plan_de_cuentas 
      where empresa_id='" . $empresa . "'";
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
     * Funcion que cuenta clientes
     *
     * *********************************************************************************** */

    function contarCli() {
        $sql1 = "select count(*) 
      from 
      tipos_cliente";
        if (!$resultado = $this->ConexionBaseDatos($sql1))
            return false;
        $contador = array();
        while (!$resultado->EOF) {
            $contador[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $contador;
    }

    /*     * **********************************************************************************
     *
     * Funcion que cuenta planes
     *
     * *********************************************************************************** */

    function contarPlan() {
        $sql1 = "select count(*) 
      from 
      planes";
        if (!$resultado = $this->ConexionBaseDatos($sql1))
            return false;
        $contador = array();
        while (!$resultado->EOF) {
            $contador[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $contador;
    }

    /*     * **********************************************************************************
     *
     * Funcion que saca el nuevo id
     *
     * *********************************************************************************** */

    function NuevoId() {
        $sql1 = "select nextval('documentos_documento_id_seq'::regclass)";
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
     * Funcion que saca el nuevo indice automatico
     *
     * *********************************************************************************** */

    function NuevoIA() {
        $sql1 = "select nextval('cg_parametros_documentos_indice_automatico_seq'::regclass)";
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
     * Funcion que busca parametros
     *
     * *********************************************************************************** */

    function buscar($documento_id) {


        $sql = "select * from cg_parametros_documentos 
          where documento_id=" . $documento_id . "";

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
     * Funcion que busca Empresas
     *
     * *********************************************************************************** */

    function Empresas() {


        $sql = "select razon_social,empresa_id from empresas 
            where
            sw_activa = '1'
          ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*     * **********************************************************************************
     *
     * Funcion borrara parametros
     *
     * *********************************************************************************** */

    function borrarpara($ia) {


        $sql = "delete from cg_parametros_documentos 
          where indice_automatico=" . $ia . "";

        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida al borrar datos";
            return $cad;
        } else {
            $cad = "Parametro borrado correctamente";
            return $cad;
        }
    }

    /*     * **********************************************************************************
     *
     * Funcion que sirve para selecionar cuentas
     *
     * *********************************************************************************** */

    function VincularCuenta($offset, $empresa) {
        $sql1 = "select count(*) 
      from 
      cg_plan_de_cuentas 
      where empresa_id='" . $empresa . "'";
        $this->ProcesarSqlConteo($sql1, 10, $offset);

        $sql = " select * from cg_plan_de_cuentas 
      where empresa_id='" . $empresa . "' 
      order by cuenta
      limit " . $this->limit . " OFFSET " . $this->offset . "";

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
     * Funcion que actualiza nuevas cuentas
     *
     * *********************************************************************************** */

    function UpDocumento($empresa_id, $doc_id, $num_dig, $sw_conta, $msj, $descri, $txt1, $txt2, $txt3, $prefijos_financiero_id) {


        $sql_aux = "prefijos_financiero_id = NULL,";
        if ($prefijos_financiero_id != "")
            $sql_aux = "prefijos_financiero_id = {$prefijos_financiero_id}, ";

        $sql = "Update documentos 
          SET numero_digitos=" . $num_dig . ",
          {$sql_aux}    
          sw_contabiliza='" . $sw_conta . "',
          mensaje='" . $msj . "',
          texto1='" . $txt1 . "',
          texto2='" . $txt2 . "',
          texto3='" . $txt3 . "',
          descripcion='" . $descri . "'   
          where documento_id=" . $doc_id . " and empresa_id='" . $empresa_id . "'";

        if (!$resultado = $this->ConexionBaseDatos($sql)) {
            $cad = "Operacion Invalida";

            return $cad;
        } else {
            $cad = "Documento Actualizado Satisfactoriamente";
            return $cad;
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

    /*     * ********************************************************************************
     * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
     * consulta sql 
     * 
     * @param 	string  $sql	sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
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

    /*     * ********************************************************************************
     * Funcion que permite crear una transaccion 
     * @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
     * @param char $num Numero correspondiente a la sentecia sql - por defect es 1
     *
     * @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
     * 								 se devuelve nada
     * ********************************************************************************* */

    function obtener_prefijo_fi() {

        $sql = "SELECT id, prefijo, descripcion, prefijo||' -- '||descripcion as descripcion_completa FROM prefijos_financiero WHERE estado ='1'";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return false;

        $datos = Array();
        while (!$resultado->EOF) {
            $datos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $datos;
    }

}

?>