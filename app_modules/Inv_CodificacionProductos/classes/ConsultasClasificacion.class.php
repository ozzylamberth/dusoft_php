<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: ConsultasClasificacion.class.php,v 1.12 2010/01/18 15:22:22 mauricio Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */

/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.12 $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Medina Santacruz
 */
 define('ESTADO', true);//false : pruebas; true : produccion
   
class ConsultasClasificacion extends ConexionBD {

    /**
     * Contructor
     */
    function ConsultasClasificacion() {
        
    }

    /*
     * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
     * una Clase (laboratorio) que pertenece a un grupo
     */

    function BuscarSubClasesConClase($Nombre, $Codigo, $CodigoGrupo, $CodigoClase, $Sw_Medicamento, $offset) {

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
            and
            sub.descripcion ILike '%" . $Nombre . "%'
            and
            sub.subclase_id ILike '%" . $Codigo . "%'
            ";

        //$this->debug=true;

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;

        /*
         * 3) Paso Implementar paginador... Incluir paramento offset
         *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
         *  Organizar la Busqueda
         *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
         */

        $sql .= "ORDER BY sub.descripcion ";
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

    /*
     * Saca un listado de SubClases(Moleculas) que no han sido asignadas a 
     * una Clase (laboratorio) que pertenece a un grupo
     */

    function BuscarSubClasesSinClase($Nombre, $Codigo, $CodigoGrupo, $CodigoClase, $Sw_Medicamento, $offset) {

        $sql = "
          SELECT 
          mol.molecula_id,
          mol.descripcion
          FROM 
          inv_moleculas mol
          where
          mol.molecula_id 
          NOT IN 
          (select molecula_id
          from
          inv_subclases_inventarios
          where
          grupo_id = '" . $CodigoGrupo . "'
          and
          clase_id = '" . $CodigoClase . "')
          and
          mol.descripcion ILike '%" . $Nombre . "%'
          and
          mol.molecula_id ILike '%" . $Codigo . "%'
          and
          mol.sw_medicamento = '" . $Sw_Medicamento . "'
          and
          mol.estado = '1' ";

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;

        /*
         * 3) Paso Implementar paginador... Incluir paramento offset
         *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
         *  Organizar la Busqueda
         *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
         */

        $sql .= "ORDER BY mol.molecula_id ";
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

    /*     * ********************************************************************************
     * Insertar una SubClase a Una Clase
     * 
     * @return token
     * ********************************************************************************** */

    function Insertar_SubclaseAClase($Grupo_id, $Clase_id, $SubClase_id, $DescripcionSubClase, $MoleculaId) {

        //$this->debug=true;
        $sql = "INSERT INTO inv_subclases_inventarios (";
        $sql .= "       grupo_id     , ";
        $sql .= "       clase_id     , ";
        $sql .= "       subclase_id     , ";
        $sql .= "       descripcion     , ";
        $sql .= "       molecula_id     ) ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $Grupo_id . "',";
        $sql .= "        '" . $Clase_id . "',";
        $sql .= "        '" . $SubClase_id . "',";
        $sql .= "        '" . $DescripcionSubClase . "',";
        $sql .= "        '" . $MoleculaId . "'";
        $sql .= "       ); ";

        //$this->debug=true;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function Insertar_SubClaseAClase_WS($Grupo_id, $Clase_id, $SubClase_id, $DescripcionSubClase, $MoleculaId, $destino = '0') {
        require_once ('nusoap/lib/nusoap.php');

        $url_wsdl = $this->obtenerUrl($destino);       
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "insertar_subclaseaclase";
        $inputs = array('grupo_id' => $Grupo_id,
            'clase_id' => $Clase_id,
            'subclase_id' => $SubClase_id,
            'descripcion' => $DescripcionSubClase,
            'molecula_id' => $MoleculaId);

        $resultado = $soapclient->call($function, $inputs);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
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

    /*
     * Saca un listado de SubClases(Moleculas) que no han sido asignadas a 
     * una Clase (laboratorio) que pertenece a un grupo
     */

    function ListadoSubClasesSinClase($CodigoGrupo, $CodigoClase, $Sw_Medicamento, $offset) {

        $sql = "
          SELECT 
          mol.molecula_id,
          mol.descripcion
          FROM 
          inv_moleculas mol
          
          where
          mol.molecula_id 
          NOT IN 
          (select molecula_id
          from
          inv_subclases_inventarios
          where
          grupo_id = '" . $CodigoGrupo . "'
          and
          clase_id = '" . $CodigoClase . "')
          and
          mol.sw_medicamento='" . $Sw_Medicamento . "'
          and
          mol.estado = '1' ";



        $sql .= " ORDER BY mol.descripcion ";


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

    function BuscarClasesAsignadas($CodigoGrupo, $CodigoClase, $NombreClase, $offset) {


        $sql = "
          SELECT 
          cla.clase_id,
          cla.descripcion
          FROM 
          inv_clases_inventarios cla
          where
          cla.grupo_id = '" . $CodigoGrupo . "'
          and
          cla.descripcion ILike '%" . $NombreClase . "%'
          AND
          cla.clase_id ILike '%" . $CodigoClase . "%'
          AND
          cla.sw_tipo_empresa=" . $_REQUEST['datos']['sw_tipo_empresa'] . ""
        ;

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;

        /*
         * 3) Paso Implementar paginador... Incluir paramento offset
         *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
         *  Organizar la Busqueda
         *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
         */

        $sql .= "ORDER BY cla.clase_id ";
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

    function BuscarClasesNoAsignadas_($CodigoGrupo, $Nombre, $Codigo, $offset) {
        $sql = "
          SELECT 
          lab.laboratorio_id,
          lab.descripcion
          FROM 
          inv_laboratorios lab
          where
          lab.laboratorio_id 
          NOT IN 
          (select laboratorio_id
          from
          inv_clases_inventarios
          where
          grupo_id = '" . $CodigoGrupo . "')
          and
          lab.descripcion ILike '%" . $Nombre . "%'
          and
         lab.laboratorio_id ILike '%" . $Codigo . "%'
          and
          lab.estado = '1' ";


        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;

        /*
         * 3) Paso Implementar paginador... Incluir paramento offset
         *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
         *  Organizar la Busqueda
         *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
         */

        $sql .= "ORDER BY lab.laboratorio_id ";
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

    /*
     * Funcion para Borrar SubClases
     */

    function Borrar_SubClase($Grupo_Id, $Clase_Id, $SubClase_Id) {

        // $this->debug=true;
        $sql = "DELETE FROM 
    inv_subclases_inventarios ";
        $sql .= "Where ";
        $sql .= "grupo_id ='" . $Grupo_Id . "'";
        $sql .= " and ";
        $sql .= "clase_id ='" . $Clase_Id . "'";
        $sql .= " and ";
        $sql .= "subclase_id ='" . $SubClase_Id . "';";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function Borrar_SubClase_WS($Grupo_Id, $Clase_Id, $SubClase_Id, $destino = '0') {
        require_once ('nusoap/lib/nusoap.php');

        $url_wsdl = $this->obtenerUrl($destino);       
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "borrar_subclase";
        $inputs = array('grupo_id' => $Grupo_Id,
            'clase_id' => $Clase_Id,
            'subclase_id' => $SubClase_Id);

        $resultado = $soapclient->call($function, $inputs);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Funcion para Borrar Clases
     */

    function Borrar_Clase($Grupo_Id, $Laboratorio_Id) {

        //$this->debug=true;
        $sql = "DELETE FROM 
    inv_clases_inventarios ";
        $sql .= "Where ";
        $sql .= "grupo_id ='" . $Grupo_Id . "'";
        $sql .= " and";
        $sql .= " clase_id ='" . $Laboratorio_Id . "';";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
        //return true;
            return $sql;

        $rst->Close();
    }

    function Borrar_Clase_WS($Grupo_id, $Clase_Id, $destino = '0') {
        require_once ('nusoap/lib/nusoap.php');

        $url_wsdl = $this->obtenerUrl($destino);
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "borrar_clase";
        $inputs = array('grupo_id' => $Grupo_id,
            'laboratorio_id' => $Clase_Id);

        $resultado = $soapclient->call($function, $inputs);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    /*     * ********************************************************************************
     * Insertar una Clase a Un grupo
     * 
     * @return token
     * ********************************************************************************** */

    function Insertar_ClasesAGrupo($Grupo_id, $ClaseId, $Descripcion, $LaboratorioId) {
        //$this->debug=true;
        $sql = "INSERT INTO inv_clases_inventarios (";
        $sql .= "       grupo_id     , ";
        $sql .= "       clase_id     , ";
        $sql .= "       descripcion     , ";
        $sql .= "       laboratorio_id  , ";
        $sql .= "       sw_tipo_empresa  ) ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $Grupo_id . "',";
        $sql .= "        '" . $ClaseId . "',";
        $sql .= "        '" . $Descripcion . "',";
        $sql .= "        '" . $LaboratorioId . "',";
        $sql .= "        '" . $_REQUEST['datos']['sw_tipo_empresa'] . "'";
        $sql .= "       ); ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function Insertar_ClasesAGrupo_WS($Grupo_id, $ClaseId, $Descripcion, $LaboratorioId, $destino = '0') {
        require_once ('nusoap/lib/nusoap.php');

        $url_wsdl = $this->obtenerUrl($destino);      
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "insertar_clasesagrupo";
        $inputs = array('grupo_id' => $Grupo_id,
            'clase_id' => $ClaseId,
            'descripcion' => $Descripcion,
            'laboratorio_id' => $LaboratorioId,
            'sw_tipo_empresa' => $_REQUEST['datos']['sw_tipo_empresa']);

        $resultado = $soapclient->call($function, $inputs);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Funcion que extrae el Id proximo de la tabla fabricantes.
     */

    function ListadoClasesxGrupo($CodigoGrupo, $offset) {
        //$this->debug=true;
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

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;

        /*
         * 3) Paso Implementar paginador... Incluir paramento offset
         *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
         *  Organizar la Busqueda
         *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
         */

        $sql .= "ORDER BY cla.clase_id ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";
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

    /*
     * Funcion que extrae el Id proximo de la tabla fabricantes.
     */

    function ListadoClasesSinGrupo($CodigoGrupo) {
        // $this->debug=true;
        $sql = "SELECT 
            lab.laboratorio_id,
            lab.descripcion
            FROM 
            inv_laboratorios lab
            where
            lab.laboratorio_id 
            NOT IN 
            (select COALESCE(laboratorio_id)
            from
            inv_clases_inventarios
            where
            grupo_id = '" . $CodigoGrupo . "'
            and
            laboratorio_id IS NOT NULL
            )
            and
            lab.estado = '1' ";

        $sql .= "ORDER BY lab.descripcion ";
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
     * Funcion para Modificar Grupos
     */

    function Modificar_Grupo($datos) {
      
        $sql = "UPDATE inv_grupos_inventarios ";
        $sql .= "SET descripcion = '" . $datos['descripcion'] . "'";
        $sql .= " Where ";
        $sql .= "grupo_id ='" . $datos['grupo_id'] . "';";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function Modificar_Grupo_WS($datos, $destino = '0') {
        require_once ('nusoap/lib/nusoap.php');

        $url_wsdl = $this->obtenerUrl($destino);
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "modificar_grupo";
        $inputs = array('grupo_id' => $datos['grupo_id'],
            'descripcion' => $datos['descripcion']);

        $resultado = $soapclient->call($function, $inputs);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    function Borrar_Registro_WS($tabla, $id, $campo_id, $destino = '0') {
        require_once ('nusoap/lib/nusoap.php');

        $url_wsdl = $this->obtenerUrl($destino);
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "borrar_registro";
        $inputs = array('tabla' => $tabla,
            'id' => $id,
            'campo_id' => $campo_id);

        $resultado = $soapclient->call($function, $inputs);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Funcion que extrae el Id proximo de la tabla fabricantes.
     */

    function ListadoGrupos() {
        $sql = "SELECT 
      grupo_id,
      descripcion,
      sw_medicamento
      from
      inv_grupos_inventarios
      order by grupo_id;";
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

    /*
     * Funcion que busca la existencia de un fabricante por su nombre
     */

    function BuscarGrupo($Codigo) {
        $sql = "select
      grupo_id,
      descripcion,
      sw_medicamento
      from
      inv_grupos_inventarios
      where
      grupo_id ='" . $Codigo . "';";
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

    /*     * ********************************************************************************
     * Insertar un laboratorio en la base de datos. Datos enviados desde formulario de Moleculas
     * 
     * @return token
     * ********************************************************************************** */

    function Insertar_Grupo($datos) {
        //$this->debug=true;
        $sql = "INSERT INTO inv_grupos_inventarios (";
        $sql .= "       grupo_id     , ";
        $sql .= "       descripcion     , ";
        $sql .= "       sw_medicamento )";

        $sql .= "VALUES ( ";
        $sql .= "        '" . $datos['grupo_id'] . "',";
        $sql .= "        '" . $datos['descripcion'] . "',";
        $sql .= "        '" . $datos['sw_medicamento'] . "'";
        $sql .= "       ); ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function Insertar_Grupo_WS($datos, $destino = '0') {
        require_once ('nusoap/lib/nusoap.php');

        $url_wsdl = $this->obtenerUrl($destino);     
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "insertar_grupo";
        $inputs = array('grupo_id' => $datos['grupo_id'],
            'descripcion' => $datos['descripcion'],
            'sw_medicamento' => $datos['sw_medicamento']);

        $resultado = $soapclient->call($function, $inputs);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    /*     * ************************************************************************************
     * Busca si existe un laboratorio con el código enviado desde formulario usuario
     * 
     * @return array
     * ************************************************************************************* */

    function Buscar_laboratorio($laboratorio_id) {
        //$this->debug=true;
        $sql = "SELECT	LAB.laboratorio_id,
							LAB.descripcion,
							LAB.direccion,
							LAB.telefono,
							LAB.tipo_pais_id,
							PA.pais
							FROM		
							inv_laboratorios LAB,
							tipo_pais PA
							WHERE		
							LAB.laboratorio_id ='" . $laboratorio_id . "'
							AND
							LAB.tipo_pais_id = PA.tipo_pais_id;";


        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * 	Funcion de Consulta SQL, que se encarga de buscar los diferentes Grupos
     * 	Existentes en el sistema.
     */

    function Listar_Laboratorios($var) {
        $sql = "Select 

			lab.laboratorio_id,
			lab.descripcion,
			lab.direccion,
			lab.telefono,
      lab.tipo_pais_id,
			pa.pais,
			lab.estado
						from 
						inv_laboratorios lab,
						tipo_pais pa
			Where
			lab.laboratorio_id = lab.laboratorio_id
			AND
			lab.tipo_pais_id = pa.tipo_pais_id
			ORDER BY lab.descripcion;";


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
     * Saca un listado de SubClases(Moleculas) que han sido asignadas a 
     * una Clase (laboratorio) que pertenece a un grupo
     */

    function ListadoSubClasesConClase($CodigoGrupo, $CodigoClase, $Sw_Medicamento, $offset) {

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


        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;

        /*
         * 3) Paso Implementar paginador... Incluir paramento offset
         *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
         *  Organizar la Busqueda
         *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
         */

        $sql .= "ORDER BY sub.clase_id ";
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

    function obtenerUrl($destino) {

        $produccion = ESTADO; 

        if ($destino == '0') {
            //Sincronizar con Cosmitet
           
		   $url_produccion = "http://dusoft.cosmitet.net/dusoft/ws/codificacion_productos/ws_producto.php?wsdl";
           $url_pruebas = "http://10.0.0.3/pg9/steven.rojas/asistencial/ws/codificacion_productos/ws_producto.php?wsdl"; //pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        } 
		else if ($destino == '1') {
            //Sincronizar con Dumian
            $url_produccion = "http://10.0.0.62/DUMIAN/ws/codificacion_productos/ws_producto.php?wsdl";  
            $url_pruebas = "http://10.0.1.80/dumian/PRUEBAS_DUM/ws/codificacion_productos/ws_producto.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '2') {
            //Sincronizar con Clinica Sta Sofía
            $url_produccion = "http://dusoft.cosmitet.net/CSSP/ws/codificacion_productos/ws_producto.php?wsdl";
            $url_pruebas = "http://10.0.0.3/pg9/santa_sofia/CSSP/ws/codificacion_productos/ws_producto.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '3') {
            //Sincronizar con Cucuta
            $url_produccion = "http://10.200.1.13/MD/ws/codificacion_productos/ws_producto.php?wsdl";
            $url_pruebas = "http://10.200.1.22/MD/ws/codificacion_productos/ws_producto.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '4') {
            //Sincronizar con CMS
            $url_produccion = "http://10.0.0.62/CMS/ws/codificacion_productos/ws_producto.php?wsdl";
            $url_pruebas = "http://10.0.1.80/dumian/CMS/ws/codificacion_productos/ws_producto.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        }else if ($destino == '5') {
            //Sincronizar con PEÑITAS
            $url_produccion = "http://10.60.1.11/dusoft/ws/codificacion_productos/ws_producto.php?wsdl";
            $url_pruebas = "http://10.0.0.3/pg9/sincelejo/ws/codificacion_productos/ws_producto.php?wsdl"; // Pruebas  
            return ($produccion) ? $url_produccion : $url_pruebas;
        }else if ($destino == '6') {
            //Sincronizar con CARTAGENA
            $url_produccion = "http://10.245.1.140/dusoft/ws/codificacion_productos/ws_producto.php?wsdl";
            $url_pruebas = "http://10.0.0.3/pg9/cartagena/dusoft/ws/codificacion_productos/ws_producto.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        }
    }

}

?>