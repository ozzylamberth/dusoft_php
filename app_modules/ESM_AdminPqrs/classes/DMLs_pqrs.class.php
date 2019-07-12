<?php

class DMLs_pqrs extends ConexionBD {
    /*     * *******************************
     * Constructor
     * ******************************* */

    function DMLs_pqrs()
    {
        
    }

    /*     * ************************************************************************************
     * Insertar informacion de casos PQRS
     * @return boolean
     * ************************************************************************************* */

    function insertarCasoLogistica($datos)
    {
       
//echo "<pre>"; 
//print_r($datos);
        $tercero_id = "NULL";
        $tipo_tercero_id = "NULL";
        $farmacia = "NULL";
        $centro_utilidad = "NULL";
        $empresa_id = $datos["empresa_id"];

        if ($datos['tipo_cliente'] == 'CL')
        {
            $tercero_id = "'" . pg_escape_string($datos['tercero_id_seleccionado']) . "'";
            $tipo_tercero_id = "'" . pg_escape_string($datos['tipo_tercero_id_seleccionado']) . "'";
        }
        else
        {
            $farmacia = "'" . pg_escape_string($datos["farmacia"]) . "'";
            $centro_utilidad = "'" . pg_escape_string($datos["centro_utilidad"]) . "'";
        }

        if (trim($empresa_id) == '')
        {
            $empresa_id = $datos["empresa"];
        }
       
          
        $sql = "INSERT INTO esm_registro_pqrs_logistica   VALUES " .
                "(  DEFAULT   ,  'LO' || lastval()  ,    '" . pg_escape_string($datos["resp_caso"]) . "', 
                    NULL,    'A001',  '" . pg_escape_string($datos["prioridad"]) . "',  '" . UserGetUID() . "', 
                     '" . pg_escape_string($datos["numerodocumento"]) . "',  NULL, NULL,
                     NULL, " .
                " now(),  '" . $empresa_id . "',  {$centro_utilidad} , {$farmacia} , NULL,  '" . pg_escape_string($datos["fecharecepcion"]) . "',
                    '" . pg_escape_string($datos["tipodocumento"]) . "', {$tercero_id}, {$tipo_tercero_id}) RETURNING codigo";
  
         $id=$this->reazliarInsercionCaso($sql, true);
         $sql = "";
         $ids= $id['codigo'];
        for ($i=1;$datos['numerocasos']>=$i;$i++){
            $sql .= "INSERT INTO  esm_registro_pqrs_logistica_productos VALUES 
                    (DEFAULT,'$ids','" . pg_escape_string($datos["productoid$i"]) . "','" . pg_escape_string($datos["cantidaddespachada$i"]) . "','" . pg_escape_string($datos["cantidadrecibida$i"]) . "','" . pg_escape_string($datos["novedad$i"]) . "'); ";
          } 
         
        $this->reazliarInsercionCaso($sql, true);
        return $id; 
    }

    function insertarCasoServicioAlCliente($datos)
    {
        $sql = "INSERT INTO esm_registro_pqrs_servicio_al_cliente   VALUES " .
                "(  DEFAULT   ,  'SC' || lastval()  ,    '" . pg_escape_string($datos["resp_caso"]) . "',  '" . pg_escape_string($datos["categoria"]) . "',    'A001',  '" . pg_escape_string($datos["prioridad"]) . "',  '" . UserGetUID() . "' " .
                ", '" . pg_escape_string($datos["cedulaencontrada"]) . "', '" . pg_escape_string($datos["tipoencontrado"]) . "'," .
                " now(),  '" . pg_escape_string($datos["empresa_id"]) . "',  '" . pg_escape_string($datos["centro_utilidad"]) . "' , '" . pg_escape_string($datos["farmacia"]) . "') RETURNING codigo";

        return $this->reazliarInsercionCaso($sql, true);
    }

    function reazliarInsercionCaso($sql, $obtenercodigo = false)
    {
        //$this->debug = true;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $id = array();
        while (!$rst->EOF)
        {
            $id = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        if (!$obtenercodigo)
        {
            return true;
        }
        else
        {
            return $id;
        }
    }

    function insertar_caso($datos, $archivo)
    {
        //Para cada area se maneja el mismo codigo por area eje: empresa con id 03 tiene area LO001, entonces empresa con id 01 igualmente logistica L0001
        //logistica
        
        if (count($archivo) > 0 && is_uploaded_file($archivo['archivo_pqrs']['tmp_name'])){
            $resultado = $this->validarRecurso($archivo['archivo_pqrs']['error']);

            if($resultado != ""){
                echo "<center style= 'color:red; padding-top:50px; font-size:15px;'>".$resultado."</center>";
                return;
            }
        }
        $codigo = array();

        if ($datos["responsable_area"] === "LO001")
        {
           // echo "<pre>"; print_r($datos);
            $codigo = $this->insertarCasoLogistica($datos);

            if (!$codigo)
            {
                return false;
            }
        }
        else if ($datos["responsable_area"] === "SC002")
        {
            $codigo = $this->insertarCasoServicioAlCliente($datos);

            if (!$codigo)
            {
                return false;
            }
        }

        $sql = "INSERT INTO esm_registro_pqrs_d VALUES( DEFAULT, '" . $codigo["codigo"] . "', '" . pg_escape_string($datos["observacion"]) . "', now(), '" . UserGetUID() . "'  )";

        $this->modificarRutaAdjunto($archivo, $datos["responsable_area"], $codigo["codigo"]);
        //echo $sql;

        return array ('insert'=>$this->reazliarInsercionCaso($sql),'codigo'=>$codigo["codigo"]);
//        return  $this->reazliarInsercionCaso($sql);
    }

    function modificarRutaAdjunto($archivo, $responsable, $codigo)
    {
        

        if (count($archivo) > 0 && is_uploaded_file($archivo['archivo_pqrs']['tmp_name']))
        {
            $dir_siis = GetVarConfigAplication('DIR_SIIS');
            
            $carpeta = $dir_siis . "pqrs/";
            if (!is_dir($carpeta)){
                mkdir($carpeta);
            }

            $nombre_archivo =  time() . "_" . $archivo['archivo_pqrs']['name'];
            // $this->BorrarArchivos( $dir_siis."tmp/".$nombre_archivo);
            $resultado = move_uploaded_file($archivo['archivo_pqrs']['tmp_name'], $carpeta.$nombre_archivo);
            if ($resultado)
            {
                if ($responsable === "LO001")
                {
                    $sql = "UPDATE esm_registro_pqrs_logistica SET archivo ='" . $nombre_archivo . "' where codigo = '" . $codigo . "'";
                }
                else
                {
                    $sql = "UPDATE esm_registro_pqrs_servicio_al_cliente SET archivo ='" . $nombre_archivo . "' where codigo = '" . $codigo . "'";
                }

                $this->reazliarInsercionCaso($sql);
            }
            else
            {
                 
                echo "El documento no pudo ser adjuntado: " . $nombre_archivo;
                
            }
        }
    }
    
    
    function validarRecurso($error){
         if($error!= 0)
        {
          switch ($error) 
          {
            case UPLOAD_ERR_INI_SIZE:
                return "EL ARCHIVO QUE SE ESTA SUBIENDO EXCEDE EL TAMA�O PERMITIDO";
            break;
            case UPLOAD_ERR_FORM_SIZE:
                return "EL ARCHIVO QUE SE ESTA SUBIENDO EXCEDE EL TAMA�O PERMITIDO EN LA FORMA";
            break;
            case UPLOAD_ERR_PARTIAL:
                 return "EL ARCHIVO SOLO FUE SUBIDO PARCIALMENTE";
            break;
            case UPLOAD_ERR_NO_FILE:
                return "EL ARCHIVO NO FUE SUBIDO";
            break;
            case UPLOAD_ERR_NO_TMP_DIR:
                return "NO HAY DIRECTORIO TEMPORAL PARA SUBIR EL ARCHIVO";
            break;
            case UPLOAD_ERR_CANT_WRITE:
                return "HA OCURRIDO UN ERROR AL MOMENTO DE COPIAR EL ARCHIVO A DISCO";
            break;
            case UPLOAD_ERR_EXTENSION:
                return "HA OCURRIDO UN ERROR CON LA EXTENSION DEL ARCHIVO";
            break;
            default:
                return "HA OCURRIDO UN ERROR DESCONOCIDO MIENTRAS SE REALIZABA EL PROCESO";
            break;
          }

        }
        
        return "";
    }

    /*     * ************************************************************************************
     * Listado de informacion de casos PQRS
     * @return boolean
     * ************************************************************************************* */

    /*     * ************************************************************************************
     * Listado de informacion de casos PQRS
     * @return boolean
     * ************************************************************************************* */

    function Listar_datosPqrsAct($filtros, $offset, $permisos)
    {
        
        if ($filtros['descripcion_producto'] != "")
            $filtro .= " AND a.descripcion ILIKE '%" . pg_escape_string($filtros['descripcion_producto']) . "%' ";
        if ($filtros['caso'] != "")
            $filtro .= " AND a.codigo ILIKE '%" . pg_escape_string($filtros['caso']) . "%' ";
        if ($filtros['fecha_ini'] != "")
            $filtro .= " AND a.fecha_registro::date >= '" . $filtros['fecha_ini'] . "'::date ";
        if ($filtros['fecha_fin'] != "")
            $filtro .= " AND a.fecha_registro::date <= '" . $filtros['fecha_fin'] . "'::date ";

        if ($filtros['estado'] != "")
            $filtro .= " AND a.estado_codigo = '" . pg_escape_string($filtros['estado']) . "'";
         if ($filtros['producto'] != "")
            $filtro .= " AND descripcion ilike  '%" . pg_escape_string($filtros['producto']) . "%'";
        
        if ($filtros['cod_producto'] != "")
            $filtro .= " AND codigo_producto =  '" . pg_escape_string($filtros['cod_producto']) . "'";

        $permiso = "";
        foreach ($permisos as $value)
        {
            foreach ($value as $key2 => $value2)
            {
                if ($key2 == "sw_mostrar_todos")
                {
                    $permiso = $value2;
                }
            }
        }

        $condicion = "";
        if (!is_null(SessionGetVar("buscar_propios")))
        {
            //se limita a traer solo propios si no tiene permisos
            if ($permiso == "0")
            {
                $condicion = " and a.usuario_id = '" . UserGetUID() . "'";
            }
        }
        else
        {
            //es responsable del area se lista los casos por area

            if (!is_null(SessionGetVar("responsable")))
            {
                $responsable = SessionGetVar("responsable");
                $condicion = " AND a.areas_empresa_id =" . $responsable["area_empresa_id"];
            }
        }




        $sql = " 
           SELECT * FROM(
                    SELECT '' as codigol,ip.descripcion,ip.codigo_producto, b.descripcion as area_empresa,  ca.descripcion AS categoria, b.empresa_id, a.usuario_id, a.codigo, a.areas_empresa_id, a.estado_codigo, p.nombre as prioridad, p.dias_vigencia,  c.descripcion as estado_caso , bo.descripcion as farmacia, s.usuario,
                    ( select aa.observacion from esm_registro_pqrs_d  aa where aa.id_caso = a.codigo order by aa.fecha_registro ASC limit 1) as observacion,
                    a.fecha_registro , a.calificacion, a.tercero_id, a.tipo_id_tercero, q.nombre_tercero, a.archivo
                    from esm_registro_pqrs_logistica a
                    inner join areas_empresa b on a.areas_empresa_id = b.id 
                    inner join categoria_casos_pqrs ca on a.categoria_id = ca.id 
                    inner join estados_casos_pqrs c on a.estado_codigo = c.codigo
                    left join bodegas bo on a.empresa_id = bo.empresa_id and bo.centro_utilidad = a.centro_utilidad and bo.bodega = a.bodega
                    inner join system_usuarios s on a.usuario_id = s.usuario_id
                    inner join esm_prioridades_caso p on a.prioridad = p.id
                    inner join inventarios_productos as ip on (a.codigo_producto=ip.codigo_producto)
                    left join terceros q on a.tercero_id = q.tercero_id and a.tipo_id_tercero = q.tipo_id_tercero

                    union 

                    select '' as codigol,'' as descripcion,'' as codigo_producto , c.descripcion as area_empresa,  ca.descripcion as categoria, c.empresa_id, b.usuario_id, b.codigo, b.areas_empresa_id, b.estado_codigo, p.nombre as prioridad,  p.dias_vigencia, d.descripcion as estado_caso , bd.descripcion as farmacia, s.usuario,
                    ( select aa.observacion from esm_registro_pqrs_d  aa where aa.id_caso = b.codigo order by aa.fecha_registro ASC limit 1) as observacion,
                    b.fecha_registro, b.calificacion, '' as tercero_id, '' as tipo_id_tercero, '' as nombre_tercero, b.archivo
                     from esm_registro_pqrs_servicio_al_cliente b
                    inner join areas_empresa c on b.areas_empresa_id = c.id
                    inner join categoria_casos_pqrs ca on b.categoria_id = ca.id
                    inner join estados_casos_pqrs d on b.estado_codigo = d.codigo
                    inner join bodegas bd on b.empresa_id = bd.empresa_id and bd.centro_utilidad = b.centro_utilidad and bd.bodega = b.bodega
                    inner join system_usuarios s on b.usuario_id = s.usuario_id
                    inner join esm_prioridades_caso p on b.prioridad = p.id
                    
                    union
                    
                    SELECT distinct on (a1.codigo) a1.codigo as codigol, ip.descripcion,ip.codigo_producto, b.descripcion as area_empresa,  ca.descripcion AS categoria, b.empresa_id, a.usuario_id, a.codigo, a.areas_empresa_id, a.estado_codigo, p.nombre as prioridad, p.dias_vigencia,  c.descripcion as estado_caso , bo.descripcion as farmacia, s.usuario,
                    ( select aa.observacion from esm_registro_pqrs_d  aa where aa.id_caso = a.codigo order by aa.fecha_registro ASC limit 1) as observacion,
                    a.fecha_registro , a.calificacion, a.tercero_id, a.tipo_id_tercero, q.nombre_tercero, a.archivo
                    from esm_registro_pqrs_logistica a
	            inner join esm_registro_pqrs_logistica_productos as a1 on (a1.codigo =a.codigo)
                    inner join areas_empresa b on a.areas_empresa_id = b.id 
                    inner join categoria_casos_pqrs_novedad ca on a1.categoria_id = ca.id 
                    inner join estados_casos_pqrs c on a.estado_codigo = c.codigo
                    left join bodegas bo on a.empresa_id = bo.empresa_id and bo.centro_utilidad = a.centro_utilidad and bo.bodega = a.bodega
                    inner join system_usuarios s on a.usuario_id = s.usuario_id
                    inner join esm_prioridades_caso p on a.prioridad = p.id                    
                    inner join inventarios_productos as ip on (a1.codigo_producto=ip.codigo_producto )
                    left join terceros q on a.tercero_id = q.tercero_id and a.tipo_id_tercero = q.tipo_id_tercero 
                                
              ) AS a   WHERE a.empresa_id = '" . SessionGetVar("empresa_id") . "'
              {$condicion}    
              {$filtro}";
        $cont = "SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);

        $sql .= " order by a.fecha_registro desc  ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
    function buscarEncabezadoCaso($caso)
    {
        $sql = "SELECT 
                    a.codigo_producto, a.cantidad_recibida, a.cantidad_despachada,
                    a.tipo_documento, a.fecha_recepcion, a.numero_documento,
                    e.codigo_novedad_plano as categoria,
                    d.descripcion as laboratorio,
                    --b.descripcion as descripcion_producto,
                    fc_descripcion_producto(b.codigo_producto) as descripcion_producto,
                    e.descripcion as novedad, 
                    f.descripcion as presentacion,
                    g.descripcion as farmacia,
                    h.nombre_tercero
                    FROM esm_registro_pqrs_logistica a
                    inner join inventarios_productos b on a.codigo_producto = b.codigo_producto
                    inner join inv_clases_inventarios c on b.clase_id = c.clase_id AND b.grupo_id = c.grupo_id
                    inner join inv_laboratorios d on c.laboratorio_id = d.laboratorio_id 
                    inner join categoria_casos_pqrs e on a.categoria_id = e.id
                    left join bodegas g on a.centro_utilidad = g.centro_utilidad AND g.empresa_id = a.empresa_id AND g.bodega = a.bodega
                    inner join inv_presentacioncomercial f on b.presentacioncomercial_id = f.presentacioncomercial_id
                    left join terceros h on a.tercero_id = h.tercero_id and a.tipo_id_tercero = h.tipo_id_tercero 
                    WHERE a.codigo = '" . $caso . "'
                        
union 

SELECT 
                    a1.codigo_producto, a1.cantidad_recibida, a1.cantidad_despachada,
                    a.tipo_documento, a.fecha_recepcion, a.numero_documento,
                    e.id as categoria,
                    d.descripcion as laboratorio,
                    --b.descripcion as descripcion_producto,
                    fc_descripcion_producto(b.codigo_producto) as descripcion_producto,
                    e.descripcion as novedad, 
                    f.descripcion as presentacion,
                    g.descripcion as farmacia,
                    h.nombre_tercero
                    FROM esm_registro_pqrs_logistica a
                    inner join esm_registro_pqrs_logistica_productos as a1 on (a1.codigo =a.codigo)
                    inner join inventarios_productos b on a1.codigo_producto = b.codigo_producto
                    inner join inv_clases_inventarios c on b.clase_id = c.clase_id AND b.grupo_id = c.grupo_id
                    inner join inv_laboratorios d on c.laboratorio_id = d.laboratorio_id 
                    inner join categoria_casos_pqrs_novedad e on a1.categoria_id = e.id
                    left join bodegas g on a.centro_utilidad = g.centro_utilidad AND g.empresa_id = a.empresa_id AND g.bodega = a.bodega
                    inner join inv_presentacioncomercial f on b.presentacioncomercial_id = f.presentacioncomercial_id
                    left join terceros h on a.tercero_id = h.tercero_id and a.tipo_id_tercero = h.tipo_id_tercero  
                    WHERE a.codigo ='" . $caso . "'



";


        if (!$rst = $this->ConexionBaseDatos($sql))
        {
            return false;
        }

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function buscarUsuarioResponsable($id, $area = null)
    {
        $sql = "SELECT * FROM responsable_area_empresa WHERE usuario_id = " . $id;

        if (!is_null($area))
        {
            $sql .= " AND area_empresa_id = " . $area;
        }

        //echo $sql;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    /*     * ************************************************************************************
     * Lista Datos de un caso Pqrs especifico / query minimo
     * @return boolean
     * ************************************************************************************* */

    function Listar_CasosUpd($caso)
    {

        $sql = "SELECT a .registro_pqrs_d_id, a.id_caso,a.observacion,to_char(a.fecha_registro, 'yyyy-mm-dd HH12:MI:SS') as  fecha_registro,a.usuario_id, b.usuario FROM esm_registro_pqrs_d a INNER JOIN system_usuarios b on a.usuario_id = b.usuario_id  WHERE a.id_caso = '" . pg_escape_string($caso) . "'; ";
        //echo $sql;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    /*     * ************************************************************************************
     * Actualizar casos Pqrs
     * ************************************************************************************* */

    function obtenerPacienteCaso($numcaso){
        
        $sql = "SELECT a.*,(b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||segundo_apellido) as nombre_paciente"
                . " FROM esm_registro_pqrs_servicio_al_cliente a "
                . " inner join pacientes b on (a.paciente_id=b.paciente_id and b.tipo_id_paciente=a.tipo_id_paciente) "
                . " WHERE a.codigo = '" . pg_escape_string($numcaso) . "' limit 1; ";
      

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
    /*     * ************************************************************************************
     * Actualizar casos Pqrs
     * @return boolean
     * ************************************************************************************* */

    function ActualizarCasoPqrs($numcaso, $empresa, $observ, $cerrar, $calificacion, $codigocaso)
    {


        $sql = "INSERT INTO esm_registro_pqrs_d (registro_pqrs_d_id, id_caso, observacion, fecha_registro, usuario_id) VALUES " .
                " (DEFAULT, '" . pg_escape_string($numcaso) . "', '" . pg_escape_string($observ) . "', now(), '" . pg_escape_string(UserGetUID()) . "'  ) ";

        if (!$rst = $this->ConexionBaseDatos($sql))
        {
            return false;
        }


        if ($cerrar)
        {

            $sql = "UPDATE  esm_registro_pqrs_logistica SET estado_codigo = 'C002', calificacion='" . $calificacion . "' WHERE codigo = '" . $numcaso . "';";
            
            $sql .= "UPDATE  esm_registro_pqrs_servicio_al_cliente SET estado_codigo = 'C002', calificacion='" . $calificacion . "' WHERE codigo = '" . $numcaso . "';";


            if (!$rst = $this->ConexionBaseDatos($sql))
            {
                return false;
            }
        }


        return true;
    }

    function obtenerCasoConEncabezado()
    {
        
    }

    //Enero 07 2014
    function obtenerAreasPorEmpresa($empresa_id)
    {
        $sql = "SELECT *  FROM areas_empresa WHERE empresa_id ='" . pg_escape_string($empresa_id) . "'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc(false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function obtenerCategoriaPorArea($areaid)
    {
        $sql = "SELECT *  FROM categoria_casos_pqrs WHERE area_empresa_id ='" . pg_escape_string($areaid) . "'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc(false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function obtenerPrioridadPorArea($areaid)
    {
        $sql = "SELECT *  FROM esm_prioridades_caso WHERE areas_empresa_id ='" . pg_escape_string($areaid) . "'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc(false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function obtenerPaciente($id, $tipo)
    {
        $sql = "SELECT paciente_id, primer_nombre, segundo_nombre, primer_apellido, sexo_id, segundo_apellido, fecha_nacimiento, residencia_direccion, residencia_telefono,celular_telefono,email FROM pacientes " .
                " WHERE paciente_id = '" . pg_escape_string($id) . "' AND tipo_id_paciente = '" . pg_escape_string($tipo) . "'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = "";
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc(false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
    
    function obtenerTerceroLogistica($id, $tipo){
        $sql = "SELECT tercero_id,tipo_id_tercero, nombre_tercero FROM terceros " .
                " WHERE tercero_id = '" . pg_escape_string($id) . "' AND tipo_id_tercero = '" . pg_escape_string($tipo) . "'";
        
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = "";
        while (!$rst->EOF)
        {
            $datos = $rst->GetRowAssoc(false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function obtenerTiposIndentificacion()
    {
        $sql = "SELECT tipo_id_paciente FROM tipos_id_pacientes";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc(false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function obtenerProductos($busquedad, $empresa_id)
    {

        $sql = " SELECT a.codigo_producto, a.codigo_producto || ' - ' || fc_descripcion_producto(a.codigo_producto) AS descripcion FROM inventarios  a 
                    INNER JOIN inventarios_productos b on a.codigo_producto = b.codigo_producto
                    WHERE a.empresa_id='" . pg_escape_string($empresa_id) . "' AND  fc_descripcion_producto(a.codigo_producto) ILIKE '%" . pg_escape_string($busquedad) . "%' LIMIT 15";

        // $this->debug = true;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF)
        {
            $datos[] = $rst->GetRowAssoc(false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    function esAlfanumerico($String)
    {
        return preg_match('/\d/', $String) > 0;
    }

}

?>
