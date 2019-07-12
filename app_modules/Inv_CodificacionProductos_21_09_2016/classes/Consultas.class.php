<?php

define('ESTADO', true);//false : pruebas; true : produccion

class Consultas extends ConexionBD {

    /**
     * Contructor
     */
    function Consultas() {
        
    }

    /*



      if(!$rst = $this->ConexionBaseDatos($sql))
      return false;

      $datos = array(); //Definiendo que va a ser un arreglo.

      while(!$rst->EOF) //Recorriendo el Vector;
      {
      $datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
      $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
     */

    /*
     * Funcion que extrae el Id proximo de la tabla fabricantes.
     */

    function ProximoValorIdFabricante() {
        $sql = "SELECT nextval('inv_fabricantes_fabricante_id_seq'::regclass)";
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

    function BuscarFabricanteNombre($nombre) {
        $sql = "select
      descripcion
      from
      inv_fabricantes
      where
      descripcion ILIKE '%" . $nombre . "%';";
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

    function ValidarFabricanteNombre($nombre) {
        $sql = "SELECT * ";
        $sql .= "FROM   inv_fabricantes ";
        $sql .= "WHERE  descripcion ILIKE '" . $nombre . "' ";

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

    function BuscarMoleculaNombre($Nombre, $Codigo, $Sw_Medicamento, $offset) {


        $sql = "SELECT	
              mol.molecula_id,
              mol.descripcion as molecula, 
              mol.concentracion,
              mol.unidad_medida_medicamento_id,
              mol.estado
              
              FROM		
                  inv_moleculas mol
                  
                  
              WHERE		
                  mol.descripcion ILike '%" . $Nombre . "%'
                  And
                  mol.molecula_id ILike '%" . $Codigo . "%'
                  And
                  sw_medicamento = '" . $Sw_Medicamento . "'
                   ";


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

    function BuscarLaboratorioNombre($Nombre, $Codigo, $offset) {
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
			where
                        lab.laboratorio_id ILike '%" . $Codigo . "%'
                        and
                        lab.descripcion ILike '%" . $Nombre . "%'
                        and
                        lab.tipo_pais_id = pa.tipo_pais_id ";




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

    function IngresoLaboratorio_Fabricante($datos) {
        $sql = "INSERT INTO inv_fabricantes (";
        $sql .= "       fabricante_id     , ";
        $sql .= "       descripcion     , ";
        $sql .= "       registro_invima     , ";
        $sql .= "       tipo_pais_id    ) ";
        $sql .= "VALUES ( ";
        $sql .= "         " . $datos['fabricante_id'] . ",";
        $sql .= "        '" . strtoupper($datos['descripcion']) . "',";
        $sql .= "        '" . $datos['registro_invima'] . "',";
        $sql .= "        '" . $datos['pais'] . "'";
        $sql .= "       ); ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $rst->Close();
        return true;
    }

    function IngresoLaboratorioTitularInvima($CodigoLaboratorio_Id, $descripcion, $tipo_pais_id) {
        $sql = "INSERT INTO inv_titulares_reginvima (";
        $sql .= "       titular_reginvima_id     , ";
        $sql .= "       descripcion     , ";
        $sql .= "       tipo_pais_id    ) ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $CodigoLaboratorio_Id . "',";
        $sql .= "        '" . $descripcion . "',";
        $sql .= "        '" . $tipo_pais_id . "'";
        $sql .= "       ); ";
        //$this->debug="true";	
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    /*     * ************************************************************************************
     * Lista los tipos de id para un tercero
     * 
     * @return array
     * ************************************************************************************* */

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

    /*     * ************************************************************************************
     * Lista Actividades para un Tercero
     * 
     * @return array
     * ************************************************************************************* */

    function ListaActividades($id_grupo) {
        $sql = "select * from actividades_industriales
     where grupo_id='$id_grupo'";

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

    /*     * ************************************************************************************
     * Lista los Grupos de Actividades para un Tercero
     * 
     * @return array
     * ************************************************************************************* */

    function ListaGruposActividades() {
        $sql = "select * from actividades_industriales_grupos";

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

    /*     * ************************************************************************************
     * Obtiene Listad de Moleculas creadas.
     * 
     * @return array
     * ************************************************************************************* */

    function listar_moleculas($sw_medicamento, $offset) {
        //$this->debug=true;
        $sql = "SELECT	
              mol.molecula_id,
              mol.descripcion as molecula, 
              mol.estado
              
              FROM		
                inv_moleculas mol
              WHERE
                  mol.sw_medicamento = '" . $sw_medicamento . "'
                   ";

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;

        /*
         * 3) Paso Implementar paginador... Incluir paramento offset
         *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
         *  Organizar la Busqueda
         *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
         */

        $sql .= " ORDER BY mol.estado DESC, mol.molecula_id ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";



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

    /*     * ************************************************************************************
     * Busca las unidades de medida para los medicamentos
     * 
     * @return array
     * ************************************************************************************* */

    function Listar_Unidades_Medida_Medicamento() {
        $sql = "SELECT	unidad_medida_medicamento_id,descripcion
							FROM		inv_unidades_medida_medicamentos;";


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

    /*     * ********************************************************************************
     * Insertar una molécula en la base de datos. Datos enviados desde formulario de Moleculas
     * 
     * @return token
     * ********************************************************************************** */

    function Insertar_Molecula($datos) {
        $sql = "INSERT INTO inv_moleculas (";
        $sql .= "       molecula_id, ";
        $sql .= "       descripcion, ";
        $sql .= "       sw_medicamento, ";
        $sql .= "       estado, ";
        $sql .= "       usuario_id   ) ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $datos['molecula_id'] . "',";
        $sql .= "        '" . $datos['descripcion'] . "',";
        $sql .= "        '" . $datos['sw_medicamento'] . "',";
        $sql .= "        '" . $datos['estado'] . "',";
        $sql .= "        " . UserGetUID() . " ";
        $sql .= "       		); ";

        $sql .= "INSERT INTO inv_med_cod_principios_activos (";
        $sql .= "       cod_principio_activo     , ";
        $sql .= "       descripcion     ";
        $sql .= ") ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $datos['molecula_id'] . "',";
        $sql .= "        '" . $datos['descripcion'] . "'";
        $sql .= "       ); ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function Insertar_Molecula_WS($datos, $destino = '0') {
        require_once ('nusoap/lib/nusoap.php');

        $url_wsdl = $this->obtenerUrlMoleculas($destino);
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "insertar_molecula";
        $inputs = array('molecula_id' => $datos['molecula_id'],
            'descripcion' => $datos['descripcion'],
            'sw_medicamento' => $datos['sw_medicamento'],
            'estado' => $datos['estado'],
            'usuario_id' => UserGetUID());

        $resultado = $soapclient->call($function, $inputs);
		
        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    /*     * ************************************************************************************
     * Busca Paises de la Tabla tipo_pais
     * @param	NULL
     * @return	array
     * ************************************************************************************* */

    function BuscarPaises($valor) {
        $sql = "SELECT	tipo_pais_id,pais
							FROM		tipo_pais
							Order by pais;";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos[] = $rst->GetRowAssoc($ToUpper = false); //$datos[$rst->fields[0]] antes.
            $rst->MoveNext();
        }
        $rst->Close();
        //return ("ha salido de Buscar Paises");
        return $datos;
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

    /*     * ********************************************************************************
     * Insertar un laboratorio en la base de datos. Datos enviados desde formulario de Moleculas
     * 
     * @return token
     * ********************************************************************************** */

    function Insertar_Laboratorio($datos) {
        $sql = "INSERT INTO inv_laboratorios (";
        $sql .= "       laboratorio_id     , ";
        $sql .= "       descripcion     , ";
        $sql .= "       direccion     , ";
        $sql .= "       telefono     , ";
        $sql .= "       tipo_pais_id     , ";
        $sql .= "       estado,    ";
        $sql .= "       usuario_id   ) ";
        $sql .= "VALUES ( ";
        $sql .= "        '" . $datos['laboratorio_id'] . "',";
        $sql .= "        '" . strtoupper($datos['descripcion']) . "',";
        $sql .= "        '" . $datos['direccion'] . "',";
        $sql .= "        '" . $datos['telefono'] . "',";
        $sql .= "        '" . $datos['pais'] . "',";
        $sql .= "        '" . $datos['estado'] . "',";
        $sql .= "        " . UserGetUID() . " ";
        $sql .= "       ); ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $rst->Close();
        return true;
      
    }

    function Insertar_Laboratorio_WS($datos, $destino = '0') {
        require_once ('nusoap/lib/nusoap.php');

        $url_wsdl = $this->obtenerUrlLaboratorios($destino);     
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "insertar_laboratorio";
        $inputs = array('laboratorio_id' => $datos['laboratorio_id'],
            'descripcion' => $datos['descripcion'],
            'direccion' => $datos['direccion'],
            'telefono' => $datos['telefono'],
            'pais' => $datos['pais'],
            'estado' => $datos['estado'],
            'usuario_id' => UserGetUID());

        $resultado = $soapclient->call($function, $inputs);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    /*     * ********************************************************************************
     * Modificar un laboratorio en la base de datos. Datos enviados desde formulario de Laboratorios
     * 
     * @return token
     * ********************************************************************************** */
    /*
      $sql  = "Update ".$tabla." ";
      $sql .= "SET ".$campo." = '".$valor."'";
      $sql .= "Where ";
      $sql .= $campo_id."='".$id."';";
     */
    /*     * ************************************************************************************
     * Busca si existe una Molécula con el código enviado desde formulario usuario
     * 
     * @return array
     * ************************************************************************************* */

    function Buscar_Molecula($molecula_id) {
        //$this->debug=true;
        $sql = "SELECT	
              mol.molecula_id,
              mol.descripcion as molecula, 
              mol.estado,
              mol.sw_medicamento
              FROM		
                  inv_moleculas mol
                                    
              WHERE		
                  mol.molecula_id ='" . $molecula_id . "';";


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
     * Funciones para Modificar Laboratorios y Moléculas
     */

    function Modificar_Laboratorio($datos) {
        /* $num=count($datos);
          $i++

          foreach($datos as $indice => $valor)
          {
          $sql .= $indice."=".$valor;
          }
         */

        //$this->debug=true;
        $sql = "UPDATE inv_laboratorios ";
        $sql .= "SET descripcion = '" . strtoupper($datos['descripcion']) . "',";
        $sql .= "       telefono   = '" . $datos['telefono'] . "',";
        $sql .= "       direccion     ='" . $datos['direccion'] . "', ";
        $sql .= "       tipo_pais_id  ='" . $datos['pais'] . "', ";
        $sql .= "       usuario_id  = " . UserGetUID() . ", ";
        $sql .= "       fecha_registro  = NOW() ";
        $sql .= " Where ";
        $sql .= "laboratorio_id ='" . $datos['laboratorio_id'] . "';";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        else
            return true;

        $rst->Close();
    }

    function Modificar_Laboratorio_WS($datos, $destino = '0') {
        require_once ('nusoap/lib/nusoap.php');

        $url_wsdl = $this->obtenerUrlLaboratorios($destino);

        //$url_wsdl = "http://dusoft.cosmitet.net/SIIS/ws/codificacion_productos/ws_laboratorio.php?wsdl";
        //$url_wsdl = "http://10.0.1.80/SIIS/ws/codificacion_productos/ws_laboratorio.php?wsdl";
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "modificar_laboratorio";
        $inputs = array('laboratorio_id' => $datos['laboratorio_id'],
            'descripcion' => $datos['descripcion'],
            'direccion' => $datos['direccion'],
            'telefono' => $datos['telefono'],
            'pais' => $datos['pais'],
            'usuario_id' => UserGetUID());

        $resultado = $soapclient->call($function, $inputs);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    function Modificar_Molecula($datos) {
	
	
        $sql = "UPDATE inv_moleculas ";
        $sql .= "SET    descripcion = '" . strtoupper($datos['descripcion']) . "',";
        $sql .= "	      molecula_id = '" . $datos['molecula_id'] . "',";
        $sql .= "	      usuario_id = " . UserGetUID() . ", ";
        $sql .= "	      fecha_registro = NOW() ";
        $sql .= "Where  molecula_id ='" . $datos['molecula_id_old'] . "';";

        $sql .= "UPDATE inv_subclases_inventarios ";
        $sql .= "SET descripcion = '" . $datos['descripcion'] . "'";
        $sql .= " Where ";
        $sql .= "subclase_id ='" . $datos['molecula_id'] . "';";

        $sql .= "UPDATE inv_med_cod_principios_activos  ";
        $sql .= "SET descripcion = '" . $datos['descripcion'] . "'";
        $sql .= " Where ";
        $sql .= "cod_principio_activo ='" . $datos['molecula_id'] . "';";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $rst->Close();
        return true;
    }

    function Modificar_Molecula_WS($datos, $destino = '0') {
        require_once ('nusoap/lib/nusoap.php');

        $url_wsdl = $this->obtenerUrlMoleculas($destino);

        //$url_wsdl = "http://dusoft.cosmitet.net/SIIS/ws/codificacion_productos/ws_molecula.php?wsdl";
        //$url_wsdl = "http://10.0.1.80/SIIS/ws/codificacion_productos/ws_molecula.php?wsdl";
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "modificar_molecula";
        $inputs = array('molecula_id' => $datos['molecula_id'],
            'descripcion' => $datos['descripcion'],
            'concentracion' => $datos['concentracion'],
            'unidad_medida_medicamento_id' => $datos['unidad_medida_medicamento_id'],
            'sw_medicamento' => $datos['sw_medicamento']);

        $resultado = $soapclient->call($function, $inputs);

        if ($resultado) {
            return true;
        } else {
            return false;
        }
    }

    function Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id, $destino = '0') {
        require_once ('nusoap/lib/nusoap.php');

        $url_wsdl = $this->obtenerUrlMoleculas($destino);
        //$url_wsdl = "http://dusoft.cosmitet.net/SIIS/ws/codificacion_productos/ws_molecula.php?wsdl";
        //$url_wsdl = "http://10.0.1.80/SIIS/ws/codificacion_productos/ws_molecula.php?wsdl";
        $soapclient = new nusoap_client($url_wsdl, true);
        $function = "cambiar_estado";
        $inputs = array('tabla' => $tabla,
            'campo' => $campo,
            'valor' => $valor,
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
     * 	Funcion de Consulta SQL, que se encarga de buscar los diferentes Grupos
     * 	Existentes en el sistema.
     */

    function Listar_Laboratorios($offset) {
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
			lab.tipo_pais_id = pa.tipo_pais_id ";


        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;

        /*
         * 3) Paso Implementar paginador... Incluir paramento offset
         *  Ejecutar el conteo de Registros a encontrar con ProcesarSqlConteo.
         *  Organizar la Busqueda
         *  Aplicar $this->limit, significa numero de registros a mostrar y offset-> siguiente.
         */

        $sql .= "ORDER BY lab.estado DESC, lab.laboratorio_id ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

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
     * 	Funcion de Consulta SQL, que se encarga de buscar los diferentes Grupos
     * 	Existentes en el sistema.
     */

    function Listar_Grupos($Nulo) {
        $sql = "Select 

			grupo_id,
					descripcion

						from 
						inv_grupos_inventarios

						order by grupo_id;";


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
     * 	Funcion de Consulta SQL, que se encarga de buscar las diferentes Clases
     * 	Existentes en el sistema, segun el grupo.
     */

    function Listar_Clases($Grupo_Id) {
        $sql = "SELECT 
					clase_id,descripcion 
						FROM 
						inv_clases_inventarios 
							WHERE grupo_id='$grupo' 
							ORDER BY grupo_id,clase_id";


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
     * 	Funcion de Consulta SQL, que se encarga de buscar las diferentes Clases
     * 	Existentes en el sistema, segun el grupo.
     */

    function Listar_SubClases($Grupo_Id, $Clase_Id) {
        $sql = "SELECT 
					subclase_id,descripcion 
						FROM 
						inv_clases_inventarios 
							WHERE 
							grupo_id='$Grupo_Id'
							AND
							clase_id='$Clase_Id'
							ORDER BY grupo_id,clase_id,subclase_id";


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
     * @Autor: Jonier Murillo Hurtado
     * @Fecha: Julio 15 de 2011
     * @Observaciones: 
     *  Conjunto de Funciones necesarias para el ingreso del Factor de Conversión por parte de DUANA
     * <Inicia aqui>
     */

    function RegistrosFactorConversion($codmedicamento) {
        $sql = "SELECT inp.codigo_producto, fc_descripcion_producto(inp.codigo_producto) as despro, 
                 u.unidad_id, u.descripcion as desuni, 
                 hfc.unidad_dosificacion, hfc.factor_conversion as sw_unidad_minima
          FROM inventarios_productos inp INNER JOIN hc_formulacion_factor_conversion hfc
                ON hfc.codigo_producto = inp.codigo_producto INNER JOIN unidades u 
                ON u.unidad_id = hfc.unidad_id
          WHERE inp.codigo_producto = '" . $codmedicamento . "'";
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

    function SelectDosificacion() {
        $sql = "SELECT unidad_dosificacion
          FROM hc_unidades_dosificacion
          ORDER BY unidad_dosificacion";
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

    function InsertFacConversion($CodUni, $CodMed, $UniDos, $CanFacCon) {

        $sqlc = "SELECT * FROM hc_formulacion_factor_conversion ";
        $sqlc .= "WHERE unidad_id = '$CodUni' and codigo_producto = '$CodMed' and unidad_dosificacion = '$UniDos'";

        $resultado = $this->ConexionBaseDatos($sqlc);
        $i = 0;
        $documentos = Array();
        while (!$resultado->EOF) {
            $i = 1;
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();

        if ($i == 0) {
            $sql = "INSERT INTO hc_formulacion_factor_conversion (codigo_producto,";
            $sql .= "       unidad_id,";
            $sql .= "       unidad_dosificacion, ";
            $sql .= "       factor_conversion, usuario_id, fecha_registro) ";
            $sql .= "VALUES ( ";
            $sql .= "        '" . $CodMed . "',";
            $sql .= "        '" . $CodUni . "',";
            $sql .= "        '" . $UniDos . "',";
            $sql .= "        " . $CanFacCon . ",";
            $sql .= "        2, '2011-01-01'";
            $sql .= "       ); ";
        } else {

            $sql = "UPDATE hc_formulacion_factor_conversion SET factor_conversion = $CanFacCon ";
            $sql .= "WHERE unidad_id = '$CodUni' and codigo_producto = '$CodMed' and unidad_dosificacion = '$UniDos'";
        }

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $rst->Close();
        return true;
    }

    function EliminarFacCon($CodUni, $CodMed, $UniDos) {
        $sql = "DELETE FROM hc_formulacion_factor_conversion ";
        $sql .= "WHERE codigo_producto = '" . $CodMed . "' And unidad_id = '" . $CodUni . "' And unidad_dosificacion = '" . $UniDos . "'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $rst->Close();
        return true;
    }

    function SelectMedicamento($codmedicamento) {
        $sql = "SELECT inp.codigo_producto, fc_descripcion_producto(inp.codigo_producto) as despro, 
                 inp.unidad_id 
          FROM inventarios_productos inp 
          WHERE inp.codigo_producto = '" . $codmedicamento . "'";
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
     * @Autor: Jonier Murillo Hurtado
     * @Fecha: Julio 15 de 2011
     * @Observaciones: 
     *  Conjunto de Funciones necesarias para el ingreso del Factor de Conversión por parte de DUANA
     * <Termina aqui>
     */

    function obtenerUrlMoleculas($destino) {
		
		
        $produccion = ESTADO;
        //$produccion = false;

     
        if ($destino == '0') {
            //Sincronizar con Cosmitet            
            $url_produccion = "http://dusoft.cosmitet.net/dusoft/ws/codificacion_productos/ws_molecula.php?wsdl"; // Produccion                                    
            $url_pruebas = "http://10.0.0.3/pg9/steven.rojas/asistencial/ws/codificacion_productos/ws_producto.php?wsdl"; // Pruebas  
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '1') {
            //Sincronizar con Dumian
            $url_produccion = "http://10.0.0.41/DUMIAN/ws/codificacion_productos/ws_molecula.php?wsdl";
            $url_pruebas = "http://10.0.1.80/dumian/PRUEBAS_DUM/ws/codificacion_productos/ws_molecula.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '2') {
            //Sincronizar con Clinica Sta Sofía
            $url_produccion = "http://dusoft.cosmitet.net/CSSP/ws/codificacion_productos/ws_molecula.php?wsdl";
            $url_pruebas = "http://10.0.0.3/pg9/santa_sofia/CSSP/ws/codificacion_productos/ws_molecula.php?wsdl"; // Pruebas
		
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '3') {
            //Sincronizar con Cucuta
            $url_produccion = "http://10.200.1.13/MD/ws/codificacion_productos/ws_molecula.php?wsdl";
            $url_pruebas = "http://10.200.1.22/MD/ws/codificacion_productos/ws_molecula.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '4') {
            //Sincronizar con CMS
            $url_produccion = "http://10.0.0.41/CMS/ws/codificacion_productos/ws_molecula.php?wsdl";
            $url_pruebas = "http://10.0.1.80/dumian/CMS/ws/codificacion_productos/ws_molecula.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        }
    }

    function obtenerUrlLaboratorios($destino) {

        $produccion = ESTADO;
        //$produccion = false;

        if ($destino == '0') {
            //Sincronizar con Cosmitet            
            $url_produccion = "http://dusoft.cosmitet.net/dusoft/ws/codificacion_productos/ws_laboratorio.php?wsdl"; // Produccion                                    
            $url_pruebas = "http://10.0.0.3/pg9/steven.rojas/asistencial/ws/codificacion_productos/ws_producto.php?wsdl"; // Pruebas   
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '1') {
            //Sincronizar con Dumian
            $url_produccion = "http://10.0.0.41/DUMIAN/ws/codificacion_productos/ws_laboratorio.php?wsdl";
            $url_pruebas = "http://10.0.1.80/dumian/PRUEBAS_DUM/ws/codificacion_productos/ws_laboratorio.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '2') {
            //Sincronizar con Clinica Sta Sofía
            $url_produccion = "http://dusoft.cosmitet.net/CSSP/ws/codificacion_productos/ws_laboratorio.php?wsdl";
            $url_pruebas = "http://10.0.0.3/pg9/CSSP/ws/codificacion_productos/ws_laboratorio.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '3') {
            //Sincronizar con Cucuta
            $url_produccion = "http://10.200.1.13/MD/ws/codificacion_productos/ws_laboratorio.php?wsdl";
            $url_pruebas = "http://10.200.1.22/MD/ws/codificacion_productos/ws_laboratorio.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        } else if ($destino == '4') {
            //Sincronizar con CMS
            $url_produccion = "http://10.0.0.41/CMS/ws/codificacion_productos/ws_laboratorio.php?wsdl";
            $url_pruebas = "http://10.0.1.80/dumian/CMS/ws/codificacion_productos/ws_laboratorio.php?wsdl"; // Pruebas
            return ($produccion) ? $url_produccion : $url_pruebas;
        }
    }

}

?>