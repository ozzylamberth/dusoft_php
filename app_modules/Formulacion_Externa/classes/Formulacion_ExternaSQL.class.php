<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: 	Formulacion_ExternaSQL.class.php,v 1.24 
 * @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torres 
 */
class Formulacion_ExternaSQL extends ConexionBD {
    /*
     * Constructor de la clase
     */

    function Formulacion_ExternaSQL() {
        
    }

    /**
     * Funcion donde se verifica el permiso del usuario 
     * * @return array $datos vector que contiene la informacion de la consulta
     */
    function ObtenerPermisos() {
        $sql = " SELECT  a.empresa_id,
                              a.centro_utilidad,
                              b.razon_social as razon,
                              c.descripcion as centro,
                              a.usuario_id,
                              bod.bodega,
                              bod.descripcion,
							  a.sw_privilegios,
							  c.sw_topes
                 FROM    	userpermisos_Formulacion_Externa as a
							JOIN bodegas as bod ON (a.empresa_id = bod.empresa_id)
							AND (a.centro_utilidad = bod.centro_utilidad)
							JOIN centros_utilidad as c ON (bod.centro_utilidad = c.centro_utilidad)
							AND (bod.empresa_id = c.empresa_id)
							JOIN empresas as b ON (c.empresa_id = b.empresa_id)
                 WHERE
                           a.usuario_id = " . UserGetUID() . "
                 and       b.sw_activa = '1'
                 and        a.sw_activo = '1'
                  ORDER BY bod.descripcion                 ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[$rst->fields[2]] [$rst->fields[3]] [$rst->fields[6]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se Consultan los diferentes tipos de identificacion.
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarTipoId() {
        $sql = "SELECT    tipo_id_tercero, descripcion ";
        $sql .= "FROM      tipo_id_terceros ";
        $sql .= "ORDER BY  tipo_id_tercero ";
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

    /*
     * Funcion donde se busca y Consulta la informacion de  los pacientes .
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Consultar_Datospacientes($filtros, $offset) {

        if ($filtros) {
            $sql = "SELECT   DISTINCT  PA.paciente_id,
                            PA.tipo_id_paciente,
                            PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos,
                            PA.primer_nombre||' '||PA.segundo_nombre AS nombres,
                            to_char(PA.fecha_nacimiento,'dd-mm-yyyy') as fecha_nacimiento,
                            PA.residencia_direccion,
                            PA.residencia_telefono,
                            PA.sexo_id,
                            edad(PA.fecha_nacimiento) as edad 
						FROM            pacientes PA, tipos_id_pacientes TIPOS
						WHERE  		    PA.tipo_id_paciente= TIPOS.tipo_id_paciente			";

            if ($filtros) {
                if ($filtros['tipo_id_paciente'] != '-1' && $filtros['tipo_id_paciente'] != '')
                    $sql .= " AND    PA.tipo_id_paciente = '" . $filtros['tipo_id_paciente'] . "' ";
                if ($filtros['paciente_id'])
                    $sql .= " AND   PA.paciente_id = '" . $filtros['paciente_id'] . "' ";

                if ($filtros['nombres'] || $filtros['apellidos']) {
                    $util = AutoCarga::factory('ClaseUtil');
                    $sql .= "AND      " . $util->FiltrarNombres($filtros['nombres'], $filtros['apellidos'], "PA");
                }
            }


            if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (" . $sql . ") A", $offset))
                return false;

            $sql .= "ORDER BY apellidos,nombres ";
            $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

            if (!$rst = $this->ConexionBaseDatos($sql . $whr, null))
                return false;
            $datos = array();
            while (!$rst->EOF) {
                $datos[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
            }
            $rst->Close();
            return $datos;
        }
    }

    /*
     * Funcion donde se busca las Farmacias
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function consultar_Farmacias() {

        $sql = " SELECT  	empresa_id,
                        razon_social
                FROM    empresas
                WHERE  sw_tipo_empresa='1'
                ORDER BY  razon_social ";
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

    /*
     * Funcion donde se busca el identificador de la formula de papel
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Consultar_Identificador_formula($formula_papel, $tipo, $paciente_id) {
        $sql = " 	SELECT  formula_id
                    FROM 	esm_formula_externa
                    WHERE 	formula_papel = '" . $formula_papel . "' 
                    AND 	tipo_id_paciente = '" . $tipo . "'
                    AND paciente_id= '" . $paciente_id . "' ";
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

    /*
     * Funcion donde se buscan los medicamentos dispensados por lote (total despachado)
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Medicamentos_Dispensados_Esm_x_lote_total($formula_id) {

        $fecha_hoy = date('Y-m-d');
        $sql = " 
                  select SUM(a.numero_unidades) as numero_unidades,
                          a.codigo_producto,a.fecha_vencimiento,a.lote,
                          a.tiempo_tratamiento,a.descripcion_prod,
                          a.unidad_tiempo_tratamiento,
                          a.sw_pactado,
                          fc_descripcion_producto_molecula(a.codigo_producto) as molecula
                  from (
                  select
                            dd.codigo_producto,
                            SUM(dd.cantidad) as numero_unidades,
                            dd.fecha_vencimiento ,
                            dd.lote,
                            fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
                            med.tiempo_tratamiento,
                            med.unidad_tiempo_tratamiento,
                            dd.sw_pactado
                  FROM					
                            esm_formulacion_despachos_medicamentos as dc,
                            bodegas_documentos as d,
                            bodegas_documentos_d AS dd  left join esm_formula_externa_medicamentos med ON(med.formula_id =" . $formula_id . " and dd.codigo_producto=med.codigo_producto )
                  WHERE   	dc.bodegas_doc_id = d.bodegas_doc_id
                  and       dc.numeracion = d.numeracion
                  and       dc.formula_id =" . $formula_id . "
                  and       d.bodegas_doc_id = dd.bodegas_doc_id
                  and       d.numeracion = dd.numeracion
                  group by dd.codigo_producto,
                          dd.cantidad,
                          dd.fecha_vencimiento ,
                          dd.lote,
                          med.tiempo_tratamiento,
                          med.unidad_tiempo_tratamiento,
                          dd.sw_pactado
        UNION
              SELECT    dd.codigo_producto,
                        SUM(dd.cantidad) as numero_unidades,
                        dd.fecha_vencimiento , 
                        dd.lote,
                        fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
                        med.tiempo_tratamiento,
                        med.unidad_tiempo_tratamiento,
                        dd.sw_pactado
              FROM 	    esm_formulacion_despachos_medicamentos_pendientes tmp,
                        bodegas_documentos as d, 
                        bodegas_documentos_d AS dd  left join esm_formula_externa_medicamentos med ON(med.formula_id = " . $formula_id . " and dd.codigo_producto=med.codigo_producto )

            WHERE 	tmp.bodegas_doc_id = d.bodegas_doc_id 
            and     tmp.numeracion = d.numeracion 
            and     d.bodegas_doc_id = dd.bodegas_doc_id 
            and     d.numeracion = dd.numeracion 
            and     tmp.formula_id =" . $formula_id . "  group by dd.codigo_producto,
						dd.cantidad,
						dd.fecha_vencimiento ,
						dd.lote,
						med.tiempo_tratamiento,
						med.unidad_tiempo_tratamiento,
						dd.sw_pactado
			)as a
			group by  a.codigo_producto,a.fecha_vencimiento,a.lote,
                a.tiempo_tratamiento,a.descripcion_prod,
                a.unidad_tiempo_tratamiento,
                a.sw_pactado					  ";


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

    /*
     * Funcion donde se buscan  el diagnosrico de la formula
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Diagnostico_Real($tmp_id) {

        $sql = "SELECT   DXT.diagnostico_id,
                        DX.diagnostico_nombre
					FROM          esm_formula_externa_diagnosticos DXT,
                        diagnosticos DX
					WHERE         DXT.diagnostico_id=DX.diagnostico_id 
					and           DXT.formula_id='" . $tmp_id . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos [] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se Ingresa la Informacion de Farmacovigilancia
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ingreso_farmacovigilancia($request) {

        $this->ConexionTransaccion();

        $inst = $request['institucion'];
        $institucion = explode(">", $inst);
        $farmacia_id = $institucion[0];
        $fecha_notifica = explode("-", $request['fecha_notifica']);
        $fecha_notifi = $fecha_notifica[2] . "-" . $fecha_notifica[1] . "-" . $fecha_notifica[0];
        $fecha_sospecha = explode("-", $request['fecha_sospecha']);
        $fecha_sospe = $fecha_sospecha[2] . "-" . $fecha_sospecha[1] . "-" . $fecha_sospecha[0];

        $sql = "INSERT INTO esm_farmaco_vigilancia( 
                                                      esm_farmaco_id,		
                                                      empresa_id,
                                                      fecha_notificacion,		
                                                      formula_papel,		
                                                      tipo_id_paciente,		
                                                      paciente_id,		
                                                      fecha_sospecha,	
                                                      observacion,		
                                                      diagnostico,		
                                                      usuario_id, 
                                                      reaccion_adversa
                                                      )VALUES( 
                                                      nextval('esm_farmaco_vigilancia_esm_farmaco_id_seq'),
                                                      '" . $farmacia_id . "', 
                                                      '" . $fecha_notifi . "', 
                                                      '" . $request['formula'] . "',
                                                      '" . $request['tipo_id_paciente'] . "',
                                                      '" . $request['paciente_id'] . "',
                                                      '" . $fecha_sospe . "',
                                                      '" . $request['observaciones'] . "',
                                                      '" . $request['diagnostico'] . "',
                                                      " . UserGetUID() . ",
                                                      '" . $request['reacciones'] . "'
                                                        ) ";
        if (!$rst = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();

        $sql2 = " SELECT MAX(esm_farmaco_id) as esm_farmaco_id FROM  esm_farmaco_vigilancia
                                    WHERE   formula_papel ='" . $request['formula'] . "' 
                                    AND     tipo_id_paciente = '" . $request['tipo_id_paciente'] . "'
                                    AND     paciente_id =  '" . $request['paciente_id'] . "' 
                                    AND     usuario_id =" . UserGetUID() . " ";

        if (!$rst = $this->ConexionBaseDatos($sql2))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se Ingresa el detalle  de Farmacovigilancia
     * @return boolean.
     */

    function Ingreso_Farmacovigilancia_id($esm_farmaco_id, $observa, $fecha_in, $fecha_fin, $producto, $lote, $fecha, $dosis) {

        $this->ConexionTransaccion();

        if (!empty($observa)) {
            $observas = " ,indicacion_motivo,";
            $observa = " ,'" . $observa . "', ";
        } else {
            $observas = " ,";
            $observa = " ,";
        }

        if (!empty($fecha_in)) {
            $fecha_inici = explode("-", $fecha_in);
            $fecha_ini = $fecha_inici[2] . "-" . $fecha_inici[1] . "-" . $fecha_inici[0];
            $fechai = " fecha_inicio, ";
            $fecha_i = " '" . $fecha_ini . "', ";
        } else {
            $fechai = " , ";
            $fecha_i = " ,";
        }

        if (!empty($fecha_fin)) {
            $fecha_fina = explode("-", $fecha_fin);
            $fecha_f = $fecha_fina[2] . "-" . $fecha_fina[1] . "-" . $fecha_fina[0];
            $fechaf = " fecha_finalizacion ";
            $fecha_fin = " '" . $fecha_f . "' ";
        } else {
            $fechaf = " ";
            $fecha_fin = " ";
        }

        $sql = " INSERT INTO esm_farmaco_vigilancia_d
                (
                      esm_farmaco_d_id,		
                      esm_farmaco_id,		
                      codigo_medicamento,
                      frecuencia,
                      fecha_vencimiento,
                      lote						 
                      $observas
                      $fechai
                      $fechaf
                )
                VALUES
                (
                      NEXTVAL('esm_farmaco_vigilancia_d_esm_farmaco_d_id_seq'),
                      '" . $esm_farmaco_id . "',
                      '" . $producto . "',
                      '" . $dosis . "',
                      '" . $fecha . "',
                      '" . $lote . "'
                      $observa
                      $fecha_i
                      $fecha_fin
							);
							";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se buscan los reportes de farmacovigilancia 
     * @return boolean.
     */

    function consulta_Informacion($filtros, $offset) {

        $sql = "SELECT 	VIG.esm_farmaco_id,
                      VIG.esm_tipo_id_tercero,
                      VIG.esm_tercero_id,
                      To_char(VIG.fecha_notificacion,'DD-MM-YYYY') AS fecha_notificacion,
                      VIG.formula_papel,
                      VIG.tipo_id_paciente,
                      VIG.paciente_id,
                      To_char(VIG.fecha_sospecha,'DD-MM-YYYY') AS fecha_sospecha,
                      VIG.observacion,
                      VIG.diagnostico,
                      VIG.usuario_id,
                      PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos,
                      PA.primer_nombre||' '||PA.segundo_nombre AS nombres,
                      to_char(PA.fecha_nacimiento,'dd-mm-yyyy') as fecha_nacimiento,
                      PA.residencia_direccion,
                      PA.residencia_telefono,
                      PA.sexo_id,
                      edad(PA.fecha_nacimiento) as edad,
                      VIG.empresa_id,
                      MP.municipio,
                      TD.departamento,
                      TP.pais,
                      USU.nombre,
                      USU.descripcion

			FROM 	          esm_farmaco_vigilancia  VIG JOIN empresas EMP ON(VIG.empresa_id=EMP.empresa_id),
                      pacientes PA,
                      tipo_mpios MP,
                      tipo_dptos TD,
                      tipo_pais TP,
                      system_usuarios USU
			WHERE           VIG.tipo_id_paciente=PA.tipo_id_paciente
			AND             VIG.paciente_id=PA.paciente_id
			AND             EMP.tipo_pais_id=MP.tipo_pais_id
			AND             EMP.tipo_dpto_id=MP.tipo_dpto_id
			AND             EMP.tipo_mpio_id=MP.tipo_mpio_id
			AND             MP.tipo_pais_id=TD.tipo_pais_id
			AND             MP.tipo_dpto_id=TD.tipo_dpto_id
			AND             TD.tipo_pais_id=TP.tipo_pais_id 
			AND             VIG.usuario_id=USU.usuario_id ";

        $FechaI = $filtros['fecha_inicio'];
        $FechaF = $filtros['fecha_final'];
        $fdatos = explode("-", $FechaI);
        $fedatos = $fdatos[2] . "-" . $fdatos[1] . "-" . $fdatos[0];
        $fdtos = explode("-", $FechaF);
        $fecdtos = $fdtos[2] . "-" . $fdtos[1] . "-" . $fdtos[0];

        if (!empty($FechaI) && (empty($FechaF))) {
            $sql.=" AND VIG.fecha_registro = '" . $fedatos . " 00:00:00' ";
        } else {
            if ($filtros['fecha_inicio'] && $filtros['fecha_final']) {
                $sql.=" AND VIG.fecha_registro >= '" . $fedatos . " 00:00:00'  AND   VIG.fecha_registro <= '" . $fecdtos . " 24:00:00'";
            }

            if ($filtros['esm_farmaco_id']) {
                $sql.=" AND VIG.esm_farmaco_id=" . $filtros['esm_farmaco_id'] . " ";
            }

            if ($filtros['paciente_id']) {
                $sql.=" AND VIG.paciente_id='" . $filtros['paciente_id'] . "' ";
            }
            if ($filtros['tipo_id_paciente']) {
                $sql.=" AND VIG.tipo_id_paciente='" . $filtros['tipo_id_paciente'] . "' ";
            }

            if ($filtros['nombre']) {
                $sql.=" AND PA.primer_nombre||' '||PA.segundo_nombre ILIKE '%" . $filtros['nombre'] . "%' ";
            }
        }
        $cont = "   select COUNT(*) from (" . $sql . ") AS A";
        $sql .= "  ORDER by   VIG.esm_farmaco_id DESC ";
        $this->ProcesarSqlConteo($cont, $offset);
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset;
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
     * Funcion donde se buscan  el diagnosrico de la formula
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function planes_parametrizados() {

        $sql = "SELECT   plan_id,
                        plan_descripcion
               FROM     planes
                     
              WHERE     estado='1'
              and       sw_afiliados='1'
              order by empresa_id,plan_descripcion             
              ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos [] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se Elimina la formulacion temporal  de los medicamentos
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Eliminar_POS_tmp($tipopaciente, $paciente_id) {

        $sql .= " Delete     FROM  esm_formula_externa_medicamentos_tmp 
                    where  	   usuario_id=" . UserGetUID() . " 
                    and        tipo_id_paciente = '" . $tipopaciente . "' 
                    and         paciente_id='" . $paciente_id . "' ";


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

    /*
     * Funcion donde se Elimina EL diagnostico de la formulacion temporal
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Eliminar_DXT_tmp($tipopaciente, $paciente_id) {

        $sql .= " DELETE     FROM  esm_formula_externa_diagnosticos_tmp 
                  WHERE  	   usuario_id=" . UserGetUID() . " 
                  AND        tipo_id_paciente = '" . $tipopaciente . "' 
                  AND        paciente_id='" . $paciente_id . "' ";


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

    /*
     * Funcion donde se Elimina la cabecera de formulacion temporal
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Eliminar_cabec_tmp($tipopaciente, $paciente_id) {

        $sql .= "DELETE     FROM  esm_formula_externa_tmp 
			         WHERE      usuario_id=" . UserGetUID() . " 
					     AND        tipo_id_paciente = '" . $tipopaciente . "' 
               AND         paciente_id='" . $paciente_id . "' ";


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

    /*
     * Funcion donde se  verifica si el paciente se encuentra en proceso de formulacion
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Validar_Paciente_tmp($datos) {
        $sql = " SELECT     TMP.tmp_formula_id,
                            TMP.tmp_empresa_id,
                            TMP.tmp_formula_papel,
                            SYS.nombre,
                            SYS.usuario
                FROM        esm_formula_externa_tmp TMP,
                            system_usuarios SYS
                WHERE       TMP.tipo_id_paciente =  '" . $datos['tipo_id_paciente'] . "'
                AND         TMP.paciente_id ='" . $datos['paciente_id'] . "' 
                AND         TMP.usuario_id=SYS.usuario_id ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consulta la informacion del paciente
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ObtenerDatosAfiliado_($datos) {

        $sql = "SELECT AD.afiliado_tipo_id AS tipo_id_paciente , ";
        $sql .= "       AD.afiliado_id AS paciente_id, ";
        $sql .= "       AD.primer_apellido    , ";
        $sql .= "       AD.segundo_apellido   , ";
        $sql .= "       AD.primer_nombre  , ";
        $sql .= "       AD.segundo_nombre     , ";
        $sql .= "       AD.fecha_nacimiento, ";
        $sql .= "       AD.tipo_sexo_id   , ";
        $sql .= "       AD.tipo_pais_id   , ";
        $sql .= "       AD.tipo_dpto_id   , ";
        $sql .= "       AD.tipo_mpio_id   , ";
        $sql .= "       AD.zona_residencia    , ";
        $sql .= "       AD.direccion_residencia   , ";
        $sql .= "       AD.telefono_residencia, ";
        $sql .= "       TO_CHAR(AD.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
        $sql .= "        edad_completa(AD.fecha_nacimiento) as edad, ";
        $sql .= " 	edad(AD.fecha_nacimiento) as edad_s, ";
        $sql .= "       AF.plan_atencion,";
        $sql .= " 	    AF.tipo_afiliado_atencion,";
        $sql .= " 	    AF.tipo_afiliado_atencion as tipo_afiliado_id,";
        $sql .= " 	    AF.rango_afiliado_atencion, ";
        $sql .= " 	    AF.rango_afiliado_atencion as rango, ";
        $sql .= " 	    PL.plan_descripcion ";
        $sql .= "FROM   eps_afiliados_datos AD,";
        $sql .= "       eps_afiliados AF, ";
        $sql .= "       planes PL ";
        $sql .= "WHERE  AD.afiliado_tipo_id = '" . $datos['tipo_id_paciente'] . "' ";
        $sql .= "AND    AD.afiliado_id = '" . $datos['paciente_id'] . "' ";
        $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
        $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
        $sql .= "AND    AF.estado_afiliado_id IN ('AC') ";
        $sql .= "AND    AF.plan_atencion = PL.plan_id ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consulta el tipo de plan y el tipo de vinculacion del paciente
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Dato_Adionales_afiliacion($datos) {

        $sql = "	SELECT  EPS.eps_tipo_afiliado_id,
                        AFI.descripcion_eps_tipo_afiliado as vinculacion,
                        TIPOP.descripcion AS tipo_plan
                FROM  	eps_afiliados EPS,
                        eps_tipos_afiliados AFI,
                        planes_rangos  PLAR,
                        planes PLA,
                        tipos_planes TIPOP
                WHERE 	EPS.afiliado_id =  '" . $datos['paciente_id'] . "' 
                AND     EPS.afiliado_tipo_id='" . $datos['tipo_id_paciente'] . "'
                AND     EPS.plan_atencion= '" . $datos['plan_id'] . "' 
                AND     EPS.eps_tipo_afiliado_id=AFI.eps_tipo_afiliado_id
                AND     EPS.plan_atencion=PLAR.plan_id
                AND     EPS.tipo_afiliado_atencion=PLAR.tipo_afiliado_id
                AND     EPS.rango_afiliado_atencion=PLAR.rango
                AND     PLAR.plan_id=PLA.plan_id
                AND     PLA.sw_tipo_plan=TIPOP.sw_tipo_plan ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }
    
       
                /**
                *  Funcion donde se consultan la numeracion de las formulas y el prefijo
                ** @return string numeracion con (prefijo - numeracion) de formula
                */
                function Numeracion_Formula($formula_id)
                    { 
                        $sql = " select bdn.prefijo||' - '||dc.numeracion as numeracion
                                  from
                                  esm_formulacion_despachos_medicamentos as dc
                                  INNER JOIN bodegas_doc_numeraciones bdn ON (bdn.bodegas_doc_id= dc.bodegas_doc_id)
                                  WHERE dc.formula_id = ".$formula_id." ;";

                                        if(!$rst = $this->ConexionBaseDatos($sql))
          				return false;
          				while(!$rst->EOF)
          				{
          				$datos = $rst->GetRowAssoc($ToUpper);
          				$rst->MoveNext();
          				}
          				$rst->Close();
          				return $datos;
                        return $datos;

                    }

    /* Funcion que permite realizar la busqueda de los diagnosticos 
      @ return array con los datos de la informacion */

    function Busqueda_Avanzada_Diagnosticos($tipo_id_paciente, $paciente_id, $codigo, $diagnostico, $offset) {
        $filtro = '';

        /* $filtro = "AND (sexo_id='".$Datos_Paciente['tipo_sexo_id']."' OR sexo_id is null)
          AND (edad_max>=".$edad_paciente." OR edad_max is null)
          AND (edad_min<=".$edad_paciente." OR edad_min is null)"; */

        $sql = "SELECT diagnostico_id, diagnostico_nombre
              FROM   diagnosticos
					    WHERE   diagnostico_id ='" . $codigo . "'
					    AND     diagnostico_nombre LIKE '%" . $diagnostico . "%'
              $filtro and  diagnostico_id NOT IN (SELECT diagnostico_id
							                                    FROM   esm_formula_externa_diagnosticos_tmp 
                                                  WHERE  usuario_id = " . UserGetUID() . "
                                                  AND   tipo_id_paciente = '" . $tipo_id_paciente . "'
                                                  AND    paciente_id = '" . $paciente_id . "'
													 ) ";


        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (" . $sql . ") A", $offset))
            return false;

        $whr .= "ORDER BY diagnostico_nombre ";
        $whr .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

        if (!$rst = $this->ConexionBaseDatos($sql . $whr, null))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* Funcion que permite Ingresar los diagnosticos de la formula 
      @ return boolean */

    function Insertar_DX_tipo_Diagnostico_TMP($dx_, $tipo_id_paciente, $paciente_id) {

        $this->ConexionTransaccion();
        $sql = "INSERT INTO esm_formula_externa_diagnosticos_tmp
                    ( 
                                        usuario_id,
                                        tipo_id_paciente,
                                        paciente_id,
                                        diagnostico_id
                    )VALUES(
                      " . UserGetUID() . ",
                      '" . $tipo_id_paciente . "',
                      '" . $paciente_id . "',
                      '" . $dx_ . "'
                      )";
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        $rst1->Close();
        return true;
    }

    /* Funcion que permite consultar los diagnosticos temporales de la formula 
      @ return array con los datos de la informacion */

    function Diagnostico_Temporal_S($tipo_id_paciente, $paciente_id) {

        $sql = "SELECT   DXT.diagnostico_id,
                        DX.diagnostico_nombre
              FROM      esm_formula_externa_diagnosticos_tmp DXT,
                        diagnosticos DX
              WHERE  DXT.usuario_id = " . UserGetUID() . "
              AND    DXT.tipo_id_paciente = '" . $tipo_id_paciente . "'
              AND    paciente_id = '" . $paciente_id . "'
              AND   DXT.diagnostico_id=DX.diagnostico_id ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos [] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* Funcion que permite eliminar  diagnosticos temporales de la formula 
      @ return array con los datos de la informacion */

    function Eliminar_DX_tm($tipo_id, $id_paciente, $dx) {

        $sql = " DELETE     FROM  esm_formula_externa_diagnosticos_tmp  ";
        $sql .= "WHERE  	  diagnostico_id='" . $dx . "' 
                   AND        usuario_id=" . UserGetUID() . "
                   AND        tipo_id_paciente = '" . $tipo_id . "'
                   AND        paciente_id = '" . $id_paciente . "'
                  ;  ";

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

    /* Funcion que permite consultar los profesionales
      @ return array con los datos de la informacion */

    function profesionales_() {

        $sql = "SELECT  PR.tipo_id_tercero,
                        PR.tercero_id,
                        PR.nombre,
                        TIP.descripcion
                        
                 FROM   profesionales as PR,
                        tipos_profesionales  as TIP
                 WHERE  PR.tipo_profesional =TIP.tipo_profesional
                 ORDER BY PR.nombre,TIP.descripcion
                    
           ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos [] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* Funcion que permite consultar si la formula en papel digitalizada existe
      @ return array con los datos de la informacion */

    function Consulta_Formula_Existente($formula_papel, $tipo_id_paciente, $paciente_id) {



        $sql = "SELECT   formula_papel
                FROM      esm_formula_externa
                WHERE     formula_papel = '" . trim($formula_papel) . "'
     			AND       tipo_id_paciente = '" . $tipo_id_paciente . "'
                AND       paciente_id = '" . $paciente_id . "' 

                and       sw_estado!='2' 
                and       sw_corte='0'";

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

    /* Funcion que permite Ingresar la  formula  al temporal  

      @ return array con los datos de la informacion */

    function Guardar_Tmp_Cabecera_formulacionI($request, $datos_empresa) {

        $this->ConexionTransaccion();

        $sql = "SELECT nextval('esm_formula_externa_tmp_tmp_formula_id_seq') AS documento ";
        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $indice = $rst->GetRowAssoc($ToUpper = false);
        $documento = $indice['documento'];


        $fecha_recepcion = explode("/", $request['fecha_recepcion']);
        $fecha_recepcion_f = $fecha_recepcion[2] . "-" . $fecha_recepcion[1] . "-" . $fecha_recepcion[0];

        list($tipo_id_tercero, $tercero_id) = explode("@", $request['profesional']);
        $hora_formula = $request['Horas'] . ":" . $request['minuto'];
        list($ems_tipo_id_tercero, $esm_tercero_id) = explode("@", $request['esm']);
        
        $tipo_pais_id = ($request['tipo_pais_id']== "") ? 'NULL' : "'{$request['tipo_pais_id']}'";
        $tipo_dpto_id = ($request['tipo_dpto_id']== "") ? 'NULL' : "'{$request['tipo_dpto_id']}'";
        $tipo_mpio_id = ($request['tipo_mpio_id']== "") ? 'NULL' : "'{$request['tipo_mpio_id']}'";

        $sql = "INSERT INTO esm_formula_externa_tmp
                (
                    tmp_formula_id,
                    tmp_empresa_id,		
                    tmp_formula_papel,
                    fecha_formula,
                    hora_formula,
                    tipo_id_tercero,	
                    tercero_id,	
                    tipo_id_paciente,	
                    paciente_id,	
                    plan_id,		
                    rango,	
                    tipo_afiliado_id,
                    usuario_id,
                    tipo_formula,
                    tipo_pais_id,
                    tipo_dpto_id,
                    tipo_mpio_id
                    )
                    VALUES
                    (
                    $documento,
                     '" . $datos_empresa['empresa_id'] . "',
                    '" . $request['formula_papel'] . "',
                    '" . $fecha_recepcion_f . "',
                    '" . $hora_formula . "',
                    '" . trim($tipo_id_tercero) . "',
                    '" . $tercero_id . "',
                    '" . $request['tipo_id_paciente'] . "',
                    '" . $request['paciente_id'] . "',
                    '" . $request['plan_id'] . "',
                    '" . $request['rango'] . "',
                    '" . $request['tipo_afiliado'] . "',
                    " . UserGetUID() . ",
                    '" . $request['tipo_formula'] . "',
                    $tipo_pais_id,
                    $tipo_dpto_id,
                    $tipo_mpio_id ); ";
        
        /*echo "<pre>";
        print_r($sql);
        echo "</pre>";
        exit();*/

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $sql = " update     esm_formula_externa_diagnosticos_tmp 
                  set 	    tmp_formula_id=$documento
                  where     usuario_id = " . UserGetUID() . "
                  and       tipo_id_paciente = '" . $request['tipo_id_paciente'] . "'
                  AND       paciente_id = '" . $request['paciente_id'] . "' ";



        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }



        $this->Commit();
        $rst1->Close();
        return true;
    }

    /* Funcion que permite consultar el ultimo registro del paciente al cual se le realiza la formula 
      @ return array con los datos de la informacion */

    function Consulta_Max_Formulacion_tmp($empresa, $tipo_paciente, $paciente) {


        $sql = "SELECT (COALESCE(MAX(esm.tmp_formula_id),0)) AS tmp_id FROM  
                     esm_formula_externa_tmp as esm
                     where esm.usuario_id=" . UserGetUID() . " 
					 and esm.tmp_empresa_id='" . trim($empresa) . "'
					and  esm.tipo_id_paciente='" . trim($tipo_paciente) . "'
					and esm.paciente_id='" . trim($paciente) . "' ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* Funcion que permite consultar la informacion completa de la cabecera de la formula.
      @ return array con los datos de la informacion */

    function consultar_Formulacion_TITMP($request, $empresa, $tmp_id, $datos_empresa) {
        $sql = " SELECT            
				FR.*,
				to_char(FR.fecha_formula,'dd-mm-yyyy') as fecha_formula,
				PRO.nombre AS profesional,
				PAC.primer_nombre || ' ' || PAC.segundo_nombre|| ' ' || PAC.primer_apellido|| ' ' ||PAC.segundo_apellido AS nombre_paciente,
				PAC.sexo_id,
				edad(PAC.fecha_nacimiento) as edad,
				TIPOPROF.descripcion as descripcion_profesional,
				PLAN.plan_descripcion,
				tipos.descripcion_tipo_formula,
				CASE
				WHEN c.tipo_formula_id IS NOT NULL AND b.tipo_formula_id IS NULL AND ('1' = '" . trim($datos_empresa['sw_topes']) . "')
				THEN c.tope_mensual
				WHEN c.tipo_formula_id IS NOT NULL AND b.tipo_formula_id IS NOT NULL AND ('1' = '" . trim($datos_empresa['sw_topes']) . "')
				THEN b.saldo
				END as tope,
                                aa.municipio,
                                bb.departamento
                FROM             
				esm_formula_externa_tmp FR 
                LEFT JOIN  profesionales PRO ON(FR.tipo_id_tercero=PRO.tipo_id_tercero)and (FR.tercero_id=PRO.tercero_id)
                 LEFT JOIN  tipos_profesionales TIPOPROF ON(PRO.tipo_profesional=TIPOPROF.tipo_profesional)
                 LEFT JOIN  pacientes  PAC ON(FR.tipo_id_paciente=PAC.tipo_id_paciente) and (FR.paciente_id=PAC.paciente_id)
                 LEFT JOIN   planes_rangos PLANR ON(FR.plan_id=PLANR.plan_id) and (FR.rango=PLANR.rango)and(FR.tipo_afiliado_id=PLANR.tipo_afiliado_id)
                 LEFT JOIN   planes PLAN ON(PLANR.plan_id=PLAN.plan_id)
                 JOIN esm_tipos_formulas tipos ON (FR.tipo_formula=tipos.tipo_formula_id)
				 LEFT JOIN esm_topes_dispensacion_mensual AS b ON (tipos.tipo_formula_id = b.tipo_formula_id)
				   AND (b.empresa_id = '" . trim($datos_empresa['empresa_id']) . "')
				   AND (b.centro_utilidad = '" . trim($datos_empresa['centro_utilidad']) . "')
				   AND (b.lapso = '" . (date('Ym')) . "')
				 LEFT JOIN esm_topes_dispensacion as c ON (tipos.tipo_formula_id = c.tipo_formula_id)
				   AND (c.empresa_id = '" . trim($datos_empresa['empresa_id']) . "')
				   AND (c.centro_utilidad = '" . trim($datos_empresa['centro_utilidad']) . "')
                left join tipo_mpios aa on FR.tipo_pais_id = aa.tipo_pais_id and FR.tipo_dpto_id = aa.tipo_dpto_id and FR.tipo_mpio_id  = aa.tipo_mpio_id   
                left join tipo_dptos bb on aa.tipo_pais_id = bb.tipo_pais_id and aa.tipo_dpto_id = bb.tipo_dpto_id                          
                WHERE   
					FR.tmp_formula_id = " . trim($tmp_id) . " 
					AND     FR.tmp_empresa_id = '" . trim($empresa) . "' 
					AND     FR.tipo_id_paciente = '" . trim($request['tipo_id_paciente']) . "'
					AND     FR.paciente_id = '" . trim($request['paciente_id']) . "'
					AND     FR.usuario_id =" . UserGetUID() . "  ";
        
        /*echo "<pre>";
        print_r($sql);
        echo "</pre>";
        exit();*/

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* Funcion que permite consultar el diagnostico temporal
      @ return array con los datos de la informacion */

    function Diagnostico_Temporal($tipo_id_paciente, $paciente_id, $tmp_id) {

        $sql = "SELECT   DXT.diagnostico_id,
                        DX.diagnostico_nombre
					FROM          esm_formula_externa_diagnosticos_tmp DXT,
                        diagnosticos DX
					WHERE  DXT.usuario_id = " . UserGetUID() . "
					AND    DXT.tipo_id_paciente = '" . $tipo_id_paciente . "'
					AND    paciente_id = '" . $paciente_id . "'
					AND   DXT.diagnostico_id=DX.diagnostico_id 
					and   DXT.tmp_formula_id='" . $tmp_id . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos [] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consulta  informacion completa del  producto
     * @return array $datos vector con la informacion de los productos
     */

    function ConsultarListaDetalle($filtros, $empresa_id, $tipo_id_paciente, $paciente_id, $datos_empresa, $offset) {



        if ($filtros['principio_activo'] != "" || $filtros['descripcion'] != "" || $filtros['codigo_barras'] != "") {
            $sql = " SELECT                      
							exis.codigo_producto,
							CASE 
							WHEN (exis.existencia - COALESCE(x.total,0)) >0 THEN
							(exis.existencia - COALESCE(x.total,0))
							ELSE '0'
							END as existencia,
							invp.sw_requiereautorizacion_despachospedidos,
							fc_descripcion_producto_molecula(exis.codigo_producto) as molecula,
							invp.descripcion,
							med.cod_principio_activo,
							CASE 
							WHEN x.codigo_medicamento IS NOT NULL 
							THEN 'style=\"width:100%;background:#00FF40;\" '
							ELSE ''
							END as color,
							COALESCE(inv.cantidad_max_formulacion,0)as cantidad_max_formulacion

							FROM    existencias_bodegas exis 
							JOIN inventarios_productos invp ON (exis.codigo_producto = invp.codigo_producto)
							LEFT JOIN inventarios as inv ON(exis.codigo_producto = inv.codigo_producto)
							AND (exis.empresa_id = inv.empresa_id)
							LEFT JOIN  medicamentos med ON(invp.codigo_producto=med.codigo_medicamento)
							LEFT JOIN  inv_med_cod_principios_activos ppa  ON(med.cod_principio_activo =ppa.cod_principio_activo)
							LEFT JOIN  (
												SELECT 
												SUM(a.cantidad) as total,
												a.codigo_medicamento
												FROM
												esm_pendientes_por_dispensar AS a
												JOIN inventarios_productos AS b ON (a.codigo_medicamento = b.codigo_producto)
												JOIN esm_formula_externa as c ON (a.formula_id = c.formula_id)
												WHERE TRUE
												AND b.tratamiento_id IS NOT NULL
												AND c.sw_estado IN ('0','1')
												AND a.sw_estado = '0'
												AND c.empresa_id = '" . trim($datos_empresa['empresa_id']) . "'  
												AND c.centro_utilidad = '" . trim($datos_empresa['centro_utilidad']) . "' 
												AND c.bodega = '" . trim($datos_empresa['bodega']) . "' 
												group by a.codigo_medicamento
											) as x ON (exis.codigo_producto = x.codigo_medicamento)
							WHERE  TRUE ";

            $sql .= "    and 	   exis.empresa_id = '" . trim($datos_empresa['empresa_id']) . "'  
							and      exis.centro_utilidad='" . trim($datos_empresa['centro_utilidad']) . "'
							and      exis.bodega = '" . trim($datos_empresa['bodega']) . "' ";
            $sql .= "			   and exis.estado = '1' ";
            if (!empty($filtros['principio_activo'])) {
                $sql .= "  AND 	ppa.descripcion ilike '%" . $filtros['principio_activo'] . "%' ";
            }
            if (!empty($filtros['descripcion'])) {

                $sql .= "  and  invp.descripcion ilike '%" . $filtros['descripcion'] . "%' ";
            }
            if (!empty($filtros['codigo_barras'])) {

                $sql .= "  	and invp.codigo_barras = '" . $filtros['codigo_barras'] . "'  ";
            }
            /* $sql .= "     and   inv.codigo_producto not in 
              (SELECT      codigo_producto
              FROM       esm_formula_externa_medicamentos_tmp
              WHERE  	tipo_id_paciente='".$tipo_id_paciente."'
              AND       paciente_id='".$paciente_id."'
              AND       usuario_id=".UserGetUID()." )"; */

            $sql .= " ORDER BY   exis.existencia DESC,invp.descripcion ASC ";

            /* echo "<pre>";
              print_r($sql);
              echo "</pre>";
              exit(); */

            if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
                return false;

            $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";
        }

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

    /* Funcion que permite Ingresar los medicamentos de una  formula  al temporal  
      @ return boolean */

    function Medicamentos_ambulatorios_Ingreso($formula_id, $codigo, $cantidad, $formulario) {

        $this->ConexionTransaccion();
        $sql = "INSERT INTO esm_formula_externa_medicamentos_tmp
                                      (fe_medicamento_id,
                                       tmp_formula_id,
                                       codigo_producto, 
                                       cantidad,
                                       tiempo_tratamiento,
                                       unidad_tiempo_tratamiento,
                                       tipo_id_paciente,
                                       paciente_id,
                                        usuario_id )
                                      VALUES (DEFAULT,
                                      " . $formula_id . ",
                                      '" . $codigo . "',
                                      " . $cantidad . ", 
                                       " . $formulario['tiempo_entrega'] . ",
                                       '4',
                                       '" . $formulario['tipo_id_paciente'] . "',
                                       '" . $formulario['paciente_id'] . "',
                                     " . UserGetUID() . "
                  
                  )";
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }



        $this->Commit();
        $rst1->Close();
        return true;
    }

    /* Funcion que permite consultar medicamentos formulados
      @ return array con los datos de la informacion */

    function Medicamentos_Formulados_tmp($tipo_id, $id_paciente, $tmp_id) {


        $sql = "SELECT  tmp.fe_medicamento_id,
                        tmp.codigo_producto,
                        tmp.cantidad,
                        tmp.observacion,
                        tmp.dosis,
                        tmp.unidad_dosificacion,
                        tmp.tiempo_tratamiento,
                        tmp.unidad_tiempo_tratamiento,
                        tmp.periodicidad_entrega,
                        tmp.unidad_periodicidad_entrega,
                        tmp.via_administracion_id,
						
						tmp.sw_autorizado,
						tmp.usuario_autoriza_id, 	
						tmp.observacion_autorizacion,
						tmp.fecha_registro_autorizacion,
						
                        fc_descripcion_producto_alterno(tmp.codigo_producto) as descripcion_prod,
                        A.descripcion as producto,
                        b.concentracion_forma_farmacologica,
                        b.unidad_medida_medicamento_id,
                        b.factor_conversion,
                        b.factor_equivalente_mg,
                        d.descripcion as forma,
                        c.descripcion as principio_activo,
                        tmp.sw_marcado
					 FROM       esm_formula_externa_medicamentos_tmp tmp,
                      inventarios_productos A LEFT JOIN medicamentos b ON (A.codigo_producto = b.codigo_medicamento) LEFT JOIN inv_med_cod_principios_activos c on(b.cod_principio_activo = c.cod_principio_activo) LEFT JOIN inv_med_cod_forma_farmacologica  d ON(b.cod_forma_farmacologica = d.cod_forma_farmacologica)
							
					 WHERE  tmp.usuario_id = " . UserGetUID() . "
					 AND    tmp.tipo_id_paciente = '" . $tipo_id . "'
					 AND    tmp.paciente_id = '" . $id_paciente . "'
					 AND    tmp.tmp_formula_id='" . $tmp_id . "'
					 AND    tmp.codigo_producto= A.codigo_producto	 ;    ";


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

    /* Funcion que permite marcar un producto formulado
      @ return array con los datos de la informacion */

    function Update_Marcar($codigo_producto, $tmp_id) {

        $this->ConexionTransaccion();
        $sql = " update esm_formula_externa_medicamentos_tmp 
				set 	 	  sw_marcado='1'
				where    	codigo_producto = '" . $codigo_producto . "'
        and      tmp_formula_id= '" . $tmp_id . "'
				 ";
        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }

        $this->Commit();
        $rst1->Close();
        return true;
    }

    /* Funcion que permite consultar los datos  de una formula  que estan en un temporal
      @ return array con los datos de la informacion */

    function consultar_Formulacion_ITMP($request, $empresa) {

        $sql = "SELECT  tmp_formula_id,
                      tmp_empresa_id,
                      tmp_formula_papel,
                      to_char(fecha_formula,'dd-mm-yyyy') as fecha_formula,
                      hora_formula,
                      tipo_id_tercero,
                      tercero_id,
                      tipo_id_paciente,
                      paciente_id,
                      plan_id,
                      rango,
                      tipo_afiliado_id,
                      semanas_cotizadas,
					  sw_autorizado,					  
					  usuario_autoriza_id,
					  observacion_autorizacion,
					  fecha_registro_autorizacion
					FROM    esm_formula_externa_tmp
					WHERE   tmp_formula_id = " . $request['tmp_id'] . " 
					AND     tmp_empresa_id = '" . $empresa . "' 
					AND     tipo_id_paciente = '" . $request['tipo_id_paciente'] . "'
					AND     paciente_id = '" . $request['paciente_id'] . "'
					AND     usuario_id =" . UserGetUID() . " ";

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

    /* Funcion que permite crar  la formula real  
      @ return boolean */

    function FormulaReal_AMB($datos_empresa, $Cabecera_Formulacion_, $DX_, $MEDIC_, $Datos_Paciente) {

           
        
        $this->ConexionTransaccion();

        /* $sql  = "SELECT nextval('esm_formula_externa_formula_id_seq') AS formula_id ";
          if(!$rst = $this->ConexionTransaccion($sql))
          return false;

          $indice = $rst->GetRowAssoc($ToUpper = false);
          $formula_id = $indice['formula_id']; */
        $sql = " INSERT INTO esm_formula_externa
                (
                      formula_id,
                      empresa_id,
					  centro_utilidad,
					  bodega,
                      formula_papel,
                      fecha_formula,
                      tipo_formula,
                      tipo_id_tercero,	
                      tercero_id,	
                      tipo_id_paciente,	
                      paciente_id,	
                      plan_id,		
                      rango,	
                      tipo_afiliado_id,
                      usuario_id,
                      sw_estado,
					  sw_autorizado,
					  usuario_autoriza_id,
					  observacion_autorizacion,
					  fecha_registro_autorizacion,
                                          tipo_pais_id,
                                          tipo_dpto_id,
                                          tipo_mpio_id
									
                )
                    VALUES
                (
                  DEFAULT,
                  '" . $datos_empresa['empresa_id'] . "' ,
                  '" . $datos_empresa['centro_utilidad'] . "' ,
                  '" . $datos_empresa['bodega'] . "' ,
                  '" . $Cabecera_Formulacion_['tmp_formula_papel'] . "' ,
                  '" . $Cabecera_Formulacion_['fecha_formula'] . "' ,
                '" . $Cabecera_Formulacion_['tipo_formula'] . "' ,
                  '" . $Cabecera_Formulacion_['tipo_id_tercero'] . "',
                  '" . $Cabecera_Formulacion_['tercero_id'] . "',
                  '" . $Cabecera_Formulacion_['tipo_id_paciente'] . "',
                  '" . $Cabecera_Formulacion_['paciente_id'] . "',
                  " . $Cabecera_Formulacion_['plan_id'] . ",
                  '" . $Datos_Paciente['rango'] . "',
                  '" . $Datos_Paciente['tipo_afiliado_id'] . "',
                  " . UserGetUID() . ",
                  '0', 
				  '" . trim($Cabecera_Formulacion_['sw_autorizado']) . "',
				  " . (($Cabecera_Formulacion_['usuario_autoriza_id'] == "") ? "NULL" : "'" . trim($Cabecera_Formulacion_['usuario_autoriza_id']) . "'") . ",
				  " . (($Cabecera_Formulacion_['observacion_autorizacion'] == "") ? "NULL" : "'" . trim($Cabecera_Formulacion_['observacion_autorizacion']) . "'") . ",
				  " . (($Cabecera_Formulacion_['fecha_registro_autorizacion'] == "") ? "NULL" : "'" . trim($Cabecera_Formulacion_['fecha_registro_autorizacion']) . "'") . ",
				  " . (($Cabecera_Formulacion_['tipo_pais_id'] == "") ? "NULL" : "'" . trim($Cabecera_Formulacion_['tipo_pais_id']) . "'") . ",
				  " . (($Cabecera_Formulacion_['tipo_dpto_id'] == "") ? "NULL" : "'" . trim($Cabecera_Formulacion_['tipo_dpto_id']) . "'") . ",
				  " . (($Cabecera_Formulacion_['tipo_mpio_id'] == "") ? "NULL" : "'" . trim($Cabecera_Formulacion_['tipo_mpio_id']) . "'") . "				 
                )RETURNING(formula_id); ";

        /*echo "<pre>";
        print_r($sql);
        echo "</pre>";
        exit();*/

        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $indice = $rst->GetRowAssoc($ToUpper = false);
        $formula_id = $indice['formula_id'];
        $sql = "";

        foreach ($DX_ as $key => $dtl) {

            $sql .=" INSERT INTO esm_formula_externa_diagnosticos
                    ( 
                              fe_diagnostico_id,
                              formula_id,
                              diagnostico_id
                    )VALUES(
                              DEFAULT,
	                            $formula_id,
	                            '" . $dtl['diagnostico_id'] . "'
	                            
                    );";
        }
        foreach ($MEDIC_ as $key => $dtl_m) {
            /* $sql  = "SELECT nextval('esm_formula_externa_medicamentos_fe_medicamento_id_seq') AS documento ";
              if(!$rst = $this->ConexionTransaccion($sql))
              return false;

              $indice = $rst->GetRowAssoc($ToUpper = false);
              $documento = $indice['documento'];

              if(!$rst = $this->ConexionTransaccion($sql))
              return false; */

            $sql .=" INSERT INTO esm_formula_externa_medicamentos
                    (
                            fe_medicamento_id,
                            formula_id,
                            codigo_producto, 
                            cantidad,
                            tiempo_tratamiento,
                            unidad_tiempo_tratamiento,
                            sw_marcado,
                            sw_ocultar,
                            sw_autorizado,
                            usuario_autoriza_id, 	
                            observacion_autorizacion, 	
                            fecha_registro_autorizacion
                            
                    )
                    VALUES (
                            DEFAULT,
                            $formula_id,
                            '" . trim($dtl_m['codigo_producto']) . "',
                            " . trim($dtl_m['cantidad']) . ", 
                            " . trim($dtl_m['tiempo_tratamiento']) . ",
                            '" . trim($dtl_m['unidad_tiempo_tratamiento']) . "',
                            '" . trim($dtl_m['sw_marcado']) . "',
                            '" . trim($dtl_m['sw_ocultar']) . "',
                            '" . trim($dtl_m['sw_autorizado']) . "',
                            " . (($dtl_m['usuario_autoriza_id'] == "") ? "NULL" : "'" . trim($dtl_m['usuario_autoriza_id']) . "'") . ",
                            " . (($dtl_m['observacion_autorizacion'] == "") ? "NULL" : "'" . trim($dtl_m['observacion_autorizacion']) . "'") . ",
                            " . (($dtl_m['fecha_registro_autorizacion'] == "") ? "NULL" : "'" . trim($dtl_m['fecha_registro_autorizacion']) . "'") . "
                    );";
        }

        if (!$rst = $this->ConexionTransaccion($sql))
            return false;
        $this->Commit();
        $rst->Close();
        return true;
    }

    /* Funcion que permite eliminar el temporal completo 
      @ return array con los datos de la informacion */

    function Eliminar_tmp($request, $empresa) {
        $sql = "  DELETE  FROM  esm_formula_externa_medicamentos_tmp  
                  WHERE         tmp_formula_id = " . $request['tmp_id'] . " 
                  AND           tipo_id_paciente = '" . $request['tipo_id_paciente'] . "'
                  AND           paciente_id = '" . $request['paciente_id'] . "'
                  AND           usuario_id =" . UserGetUID() . ";  ";


        $sql .= " DELETE    FROM  esm_formula_externa_diagnosticos_tmp  
                    WHERE   tmp_formula_id = " . $request['tmp_id'] . " 
                    ; ";


        $sql .= " DELETE  FROM  esm_formula_externa_tmp  
                    WHERE   tmp_formula_id = " . $request['tmp_id'] . " 
                    AND     tmp_empresa_id = '" . $empresa . "' 
                    AND     tipo_id_paciente = '" . $request['tipo_id_paciente'] . "'
                    AND     paciente_id = '" . $request['paciente_id'] . "'
                    AND     usuario_id =" . UserGetUID() . " ";

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

    /* Funcion que permite consultar el ultimo registro del paciente 
      @ return array con los datos de la informacion */

    function Consulta_Max_Formulacion($empresa, $tipo_paciente, $paciente) {
        $sql = "SELECT (COALESCE(MAX(formula_id),0)) AS   tmp_id 
                FROM    esm_formula_externa 
                WHERE 	empresa_id='" . trim($empresa) . "'
                AND     tipo_id_paciente='" . trim($tipo_paciente) . "'
                AND     paciente_id='" . trim($paciente) . "'
                AND     sw_estado='0'
                AND     sw_corte='0'	 ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* Funcion que permite consultar la informacion de la formula real
      @ return array con los datos de la informacion */

    function Consulta_Formulacion_Real_I($formula) {

        $sql = " SELECT     FR.formula_papel,
		                              FR.formula_id,
                                      to_char(FR.fecha_formula,'dd-mm-yyyy') as fecha_formula,
                                      FR.hora_formula,
                                      FR.tipo_id_tercero,
                                      FR.tercero_id,
                                      FR.tipo_id_paciente,
                                      FR.paciente_id,
                                      FR.plan_id,
                                      FR.rango,
                                      FR.tipo_afiliado_id,
                                      FR.semanas_cotizadas,
                                      FR.usuario_id,
                                      to_char(FR.fecha_registro,'dd-mm-yyyy') as fecha_registro,
                                      PROF.nombre as profesional,
                                      TIPOPROF.descripcion as descripcion_profesional,
                                      PAC.primer_nombre || ' ' ||  PAC.segundo_nombre|| ' ' || PAC.primer_apellido|| ' ' ||PAC.segundo_apellido AS nombre_paciente,
                                      PAC.sexo_id,
                                      edad(PAC.fecha_nacimiento) as edad,
                                      PLAN.plan_descripcion,
                                      tipos.descripcion_tipo_formula
                                                                            
                  FROM                esm_formula_externa as FR
                  LEFT JOIN           profesionales  as PROF ON (FR.tipo_id_tercero=PROF.tipo_id_tercero) AND (FR.tercero_id=PROF.tercero_id)
                 LEFT JOIN                tipos_profesionales as TIPOPROF ON (PROF.tipo_profesional =TIPOPROF.tipo_profesional)
                  LEFT JOIN                pacientes  as PAC ON (FR.tipo_id_paciente=PAC.tipo_id_paciente) AND	(FR.paciente_id=PAC.paciente_id)
                  LEFT JOIN                planes_rangos as PLANR ON(FR.plan_id=PLANR.plan_id) AND	(FR.rango=PLANR.rango)AND (FR.tipo_afiliado_id=PLANR.tipo_afiliado_id)
                  LEFT JOIN                planes  as PLAN ON(PLANR.plan_id=PLAN.plan_id),
                  esm_tipos_formulas tipos                   
                              
                  WHERE   FR.formula_id = '" . $formula . "'    AND     FR.tipo_formula=tipos.tipo_formula_id  ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* Funcion que permite consultar si el usuario tiene permiso para dispensar la formulacion Externa
      @ return array con los datos de la informacion */

    function ObtenerPermisos_dispensacion($empresa) {
        $sql = " SELECT  a.empresa_id,
                          b.razon_social AS razon_social,
                          a.centro_utilidad,
                          c.descripcion AS centro_utilidad_des,
                          a.bodega,
                          e.descripcion as Bodega_des
                  FROM    userpermisos_DispensacionESM a,
                          bodegas e,
                          centros_utilidad c,
                          empresas b
                  WHERE    a.empresa_id=e.empresa_id
                  and      a.centro_utilidad=e.centro_utilidad
                  and      a.bodega=e.bodega
                  and      e.empresa_id=c.empresa_id
                  and      e.centro_utilidad=c.centro_utilidad
                  and      c.empresa_id=b.empresa_id
                  and      b.sw_activa = '1'
                  and      a.usuario_id = " . UserGetUID() . "
                  and      a.empresa_id='" . $empresa['empresa_id'] . "'
                  and      a.centro_utilidad='" . $empresa['centro_utilidad'] . "'
                  and      a.bodega='" . trim($empresa['bodega']) . "'
                  
                  
                  
                  ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    /* Funcion que permite consultar si el usuario tiene permiso para dispensar la formulacion Externa
      @ return array con los datos de la informacion */

    function Medicamentos_Formulados_R($tmp_id) {


        $sql = "SELECT    tmp.fe_medicamento_id,
                          tmp.codigo_producto,
                          tmp.cantidad,
                          tmp.observacion,
                          tmp.dosis,
                          tmp.unidad_dosificacion,
                          tmp.tiempo_tratamiento,
                          tmp.unidad_tiempo_tratamiento,
                          tmp.periodicidad_entrega,
                          tmp.unidad_periodicidad_entrega,
                          tmp.via_administracion_id,
                          fc_descripcion_producto_alterno(tmp.codigo_producto) as descripcion_prod,
                          A.descripcion as producto,
                          b.concentracion_forma_farmacologica,
                          b.unidad_medida_medicamento_id,
                          b.factor_conversion,
                          b.factor_equivalente_mg,
                          d.descripcion as forma,
                          c.descripcion as principio_activo,
                          d.cod_forma_farmacologica,
                          tmp.sw_marcado,
                          tmp.sw_ocultar
              FROM        esm_formula_externa_medicamentos tmp,
                          inventarios_productos A
              LEFT JOIN   medicamentos b ON (A.codigo_producto = b.codigo_medicamento)
              LEFT JOIN   inv_med_cod_principios_activos c on(b.cod_principio_activo = c.cod_principio_activo)
              LEFT JOIN   inv_med_cod_forma_farmacologica  d ON(b.cod_forma_farmacologica = d.cod_forma_farmacologica)
							
					 WHERE    tmp.formula_id='" . trim($tmp_id) . "'
					 AND    tmp.codigo_producto= A.codigo_producto
					
			 ;  ";

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

    /* Funcion que permite consultar el numero de formula 
      @ return array con los datos de la informacion */

    function consultar_Formulacion_Papel($tmp_id) {
        $sql = "SELECT  formula_papel
                FROM    esm_formula_externa 
								WHERE   formula_id = " . $tmp_id . " ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* Funcion que permite Actualizar el estado de la formula a anulada 
      @ return array con los datos de la informacion */

    function Actulizar_Estado_Formula_($formula) {

        $this->ConexionTransaccion();
        $sql = " update esm_formula_externa 
                        set 	 sw_estado='2',
                        observacion='" . $request['observacion'] . "',
                        usuario_modifica_id=" . UserGetUID() . ",
                        fecha_modificacion=now()
            		 where  formula_id = " . $formula . "
				         ";
        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }

    /**
     * Obtiene la informacion de un afiliado determinado
     * @param array $datos Vector con la informacion del tipo e identificacion
     * del afiliado
     * @return array
     */
    function ObtenerDatosAfiliados($datos) {

        $sql = "SELECT AD.afiliado_tipo_id AS tipo_id_paciente , ";
        $sql .= "       AD.afiliado_id AS paciente_id, ";
        $sql .= "       AD.primer_apellido    , ";
        $sql .= "       AD.segundo_apellido   , ";
        $sql .= "       AD.primer_nombre  , ";
        $sql .= "       AD.segundo_nombre     , ";
        $sql .= "       AD.fecha_nacimiento, ";
        $sql .= "       AD.tipo_sexo_id   , ";
        $sql .= "       AD.tipo_pais_id   , ";
        $sql .= "       AD.tipo_dpto_id   , ";
        $sql .= "       AD.tipo_mpio_id   , ";
        $sql .= "       AD.zona_residencia    , ";
        $sql .= "       AD.direccion_residencia   , ";
        $sql .= "       AD.telefono_residencia, ";
        $sql .= "       TO_CHAR(AD.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
        $sql .= "        edad_completa(AD.fecha_nacimiento) as edad, ";
        $sql .= " 	edad(AD.fecha_nacimiento) as edad_s, ";
        $sql .= "       AF.plan_atencion,";
        $sql .= " 	    AF.tipo_afiliado_atencion,";
        $sql .= " 	    AF.rango_afiliado_atencion, ";
        $sql .= " 	    PL.plan_descripcion ";
        $sql .= "FROM   eps_afiliados_datos AD,";
        $sql .= "       eps_afiliados AF, ";
        $sql .= "       planes PL ";
        $sql .= "WHERE  AD.afiliado_tipo_id = '" . $datos['tipo_id_paciente'] . "' ";
        $sql .= "AND    AD.afiliado_id = '" . $datos['paciente_id'] . "' ";
        $sql .= "AND    AD.afiliado_tipo_id = AF.afiliado_tipo_id ";
        $sql .= "AND    AD.afiliado_id = AF.afiliado_id ";
        $sql .= "AND    AF.estado_afiliado_id IN ('AC') ";
        $sql .= "AND    AF.plan_atencion = PL.plan_id ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /**
     * Funcion para obtener la informacion del plan
     * @param integer $plan Identificador del plan
     * @return array
     */
    function ObtenerInformacionPlan($plan) {

        $sql = "SELECT plan_id,";
        $sql .= "       plan_descripcion,";
        $sql .= "       sw_afiliados, ";
        $sql .= "       sw_tipo_plan ";
        $sql .= "FROM   planes ";
        $sql .= "WHERE  plan_id = " . $plan . " ";
        $sql .= "ORDER BY plan_descripcion ";

        if (!$result = $this->ConexionBaseDatos($sql, __LINE__))
            return false;

        $datos = array();
        if (!$result->EOF) {
            $datos = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $datos;
    }

    /*
     * Funcion para consultar si el p�ciente es un afiliado
     * @return array
     */

    function ObtenerDatosPlanAfiliado($tipo_id_afiliado, $afiliado) {
        $sql = " SELECT afiliado_tipo_id,afiliado_id
                FROM    eps_afiliados
                WHERE   afiliado_tipo_id = '" . $tipo_id_afiliado . "' 
                AND afiliado_id ='" . $afiliado . "'
				AND	estado_afiliado_id = 'AC';";
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

    /*
     * Funcion para el Diagnostico del temporalo
     * @return array
     */

    function Eliminar_DX_Ttmp($tipo_id, $id_paciente) {

        $sql = " Delete     FROM  esm_formula_externa_diagnosticos_tmp  ";
        $sql .= "where  	 usuario_id=" . UserGetUID() . "
               AND         tipo_id_paciente = '" . $tipo_id . "'
               AND         paciente_id = '" . $id_paciente . "'
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

    /* Funcion que  permite consultar la informacion general del reporte de famacovigilancia
      @ return array */

    function Consultar_cabe_Faramaco($farmaco) {


        $sql = "SELECT 	VIG.esm_farmaco_id,
					VIG.esm_tipo_id_tercero,
					VIG.esm_tercero_id,
					To_char(VIG.fecha_notificacion,'DD-MM-YYYY') AS fecha_notificacion,
					VIG.formula_papel,
					VIG.tipo_id_paciente,
					VIG.paciente_id,
					To_char(VIG.fecha_sospecha,'DD-MM-YYYY') AS fecha_sospecha,
					VIG.observacion,
					VIG.diagnostico,
					VIG.usuario_id,
					PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos,
					PA.primer_nombre||' '||PA.segundo_nombre AS nombres,
					to_char(PA.fecha_nacimiento,'dd-mm-yyyy') as fecha_nacimiento,
					PA.residencia_direccion,
					PA.residencia_telefono,
					PA.sexo_id,
					edad(PA.fecha_nacimiento) as edad,
					EMP.razon_social,
					
					MP.municipio,
					TD.departamento,
					TP.pais,
					USU.nombre,
					USU.descripcion,
					VIG.reaccion_adversa
					
			FROM 	esm_farmaco_vigilancia  VIG JOIN empresas EMP ON (VIG.empresa_id=EMP.empresa_id),
					pacientes PA,
		
					tipo_mpios MP,
					tipo_dptos TD,
					tipo_pais TP,
					system_usuarios USU
					
					
			WHERE   VIG.tipo_id_paciente=PA.tipo_id_paciente
			AND     VIG.paciente_id=PA.paciente_id
		
			
			AND     EMP.tipo_pais_id=MP.tipo_pais_id
			AND     EMP.tipo_dpto_id=MP.tipo_dpto_id
			AND     EMP.tipo_mpio_id=MP.tipo_mpio_id
			AND     MP.tipo_pais_id=TD.tipo_pais_id
			AND     MP.tipo_dpto_id=TD.tipo_dpto_id
			AND     TD.tipo_pais_id=TP.tipo_pais_id 
			AND     VIG.usuario_id=USU.usuario_id 
			AND     VIG.esm_farmaco_id='" . $farmaco . "' ";


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

    /* funcion que permite consultar el detalle del reporte de farmacovigilancia */

    function Farmaco_v_d_consulta($farmaco) {
        $sql = " SELECT   codigo_medicamento,
							indicacion_motivo,
							fecha_inicio,
							fc_descripcion_producto_alterno(codigo_medicamento)as producto,
							fc_codigo_mindefensa(codigo_medicamento) as codigo_producto_mini,
							
							fecha_finalizacion,
							lote,
							frecuencia,
							fecha_vencimiento
				FROM 		esm_farmaco_vigilancia_d
				WHERE 		esm_farmaco_id = '" . $farmaco . "' ";

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

    /* Funcion que  permite consultar el usuario que registro el reporte de famacovigilancia
      @ return array */

    function Consultar_cabe_Faramaco_Usuario($farmaco) {

        $sql = "SELECT 	VIG.usuario_id
					
			FROM 	esm_farmaco_vigilancia  VIG,
					
					system_usuarios USU
					
					
			WHERE    VIG.usuario_id=USU.usuario_id 
			AND     VIG.esm_farmaco_id=" . $farmaco . " ";


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

    /* Funcion que  permite consultar el usuario que registro el reporte de famacovigilancia es un profesional
      @ return array */

    function Verificar_Usuario_Profesional($usuario) {

        $sql = " SELECT PROF.tipo_tercero_id,
						PROF.tercero_id,
						TIP.descripcion,
						TER.direccion,
						TER.telefono,
						PRO.nombre
					
				FROM 	profesionales_usuarios PROF,
				        profesionales PRO,
						tipos_profesionales TIP,
						TERCEROS TER
				WHERE 	PROF.usuario_id = " . $usuario . "
				AND     PROF.tipo_tercero_id=PRO.tipo_id_tercero
				and     PROF.tercero_id=PRO.tercero_id
				and     PRO.tipo_id_tercero=TER.tipo_id_tercero
				AND     PRO.tercero_id=TER.tercero_id
				AND    PRO.tipo_profesional=TIP.tipo_profesional";

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

    /* Funcion que  permite consultar el usuario que registro el reporte de famacovigilancia en caso de que no sea un profesional
      @ return array */

    function Consultar_Usuario_NO_Profesional($usuario) {
        $sql = " SELECT  nombre,
								descripcion,
								telefono
						FROM 	system_usuarios
						WHERE 	usuario_id = '" . $usuario . "' ";

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
     * Funcion donde se  consulta los productos temporales    
      @return array $datos vector que contiene la informacion de la consulta.
     */

    function Cantidad_ProductoTemporal($doc_tmp_id, $principio_activo, $codigo_producto) {

        $sql = "  SELECT  COALESCE(sum(cantidad_despachada),0) as total,
                          codigo_formulado
                  FROM    esm_dispensacion_medicamentos_tmp 
                  WHERE   codigo_formulado='" . trim($codigo_producto) . "'
			           	AND     	formula_id_tmp = " . trim($doc_tmp_id) . " ";

        /* if($principio_activo!="")
          {
          $sql .="   and    med.cod_principio_activo = '".$principio_activo."'  ";
          }else
          {
          $sql .="   and    invp.codigo_producto = '".$codigo_producto."'  ";

          } */
        $sql .= "  GROUP BY 	codigo_formulado ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF) {
            $cuentas = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $cuentas;
    }

    /*
     * Funcion donde se  consultan las existencias por productos    
      @return array $datos vector que contiene la informacion de la consulta.
     */

    function Consultar_ExistenciasBodegas($principio_activo, $Formulario, $producto, $pendiente = null) {

        if ($Formulario['lote'] != "") {
            $filtro = " and fv.lote = '" . $Formulario['lote'] . "' ";
        }

        /* and (fv.codigo_producto||''||fv.lote NOT IN 
          (
          select
          codigo_producto||''||lote as producto
          from
          esm_dispensacion_medicamentos_tmp
          where
          empresa_id = '".trim($Formulario['empresa_id'])."'
          and  centro_utilidad = '".trim($Formulario['centro_utilidad'])."'
          and  bodega = '".trim($Formulario['bodega'])."'
          )) */
        /* $sql = "
          SELECT
          fc_descripcion_producto_alterno(fv.codigo_producto) as producto,
          fv.*
          FROM    existencias_bodegas_lote_fv AS fv
          JOIN      existencias_bodegas as ext ON (fv.empresa_id = ext.empresa_id)
          AND     (fv.centro_utilidad = ext.centro_utilidad)
          AND     (fv.bodega = ext.bodega)
          AND     (fv.codigo_producto = ext.codigo_producto)
          JOIN    inventarios as inv ON (ext.empresa_id = inv.empresa_id)
          AND     (ext.codigo_producto = inv.codigo_producto)
          JOIN    inventarios_productos as invp ON (inv.codigo_producto = invp.codigo_producto)
          WHERE
          fv.empresa_id = '".trim($Formulario['empresa_id'])."'
          AND     fv.centro_utilidad = '".trim($Formulario['centro_utilidad'])."'
          AND     fv.bodega = '".trim($Formulario['bodega'])."'
          AND     fv.existencia_actual > 0
          AND     fv.codigo_producto = '".$producto."'
          $filtro
          ORDER BY invp.descripcion ASC,fv.fecha_vencimiento ASC
          "; */
        /* $this->debug=true; */
        if ($pendiente === '1') {
            $sql = "
                    SELECT
                                  fc_descripcion_producto_alterno(fv.codigo_producto) as producto,
                                  fv.*
                    FROM    existencias_bodegas_lote_fv AS fv
                    JOIN    inventarios_productos as invp ON (fv.codigo_producto = invp.codigo_producto)
					JOIN  (	SELECT
								x.subclase_id as molecula
								FROM
										inventarios_productos AS x
								WHERE
								x.codigo_producto =  '" . trim($producto) . "'
								) as a ON (invp.subclase_id = a.molecula)
                    WHERE 
								fv.empresa_id = '" . trim($Formulario['empresa_id']) . "'
                    AND     	fv.centro_utilidad = '" . trim($Formulario['centro_utilidad']) . "'
                    AND     	fv.bodega = '" . trim($Formulario['bodega']) . "'
                    AND     	fv.existencia_actual > 0
					AND		fv.estado = '1'
                    $filtro
                    ORDER BY invp.descripcion ASC,fv.fecha_vencimiento ASC ";
        }
        else
            $sql = "
                    SELECT
                                  fc_descripcion_producto_alterno(fv.codigo_producto) as producto,
                                  fv.empresa_id,
								  fv.centro_utilidad, 	
								  fv.codigo_producto, 	
								  fv.bodega, 	
								  fv.fecha_vencimiento, 	
								  fv.lote, 	
								  fv.existencia_inicial, 	
								  (fv.existencia_actual - COALESCE(x.total,0)) as existencia_actual, 	
								  fv.estado
                    FROM    existencias_bodegas_lote_fv AS fv
                    JOIN    inventarios_productos as invp ON (fv.codigo_producto = invp.codigo_producto)
					LEFT JOIN  (
												SELECT 
												SUM(a.cantidad) as total,
												a.codigo_medicamento
												FROM
												esm_pendientes_por_dispensar AS a
												JOIN inventarios_productos AS b ON (a.codigo_medicamento = b.codigo_producto)
												JOIN esm_formula_externa as c ON (a.formula_id = c.formula_id)
												WHERE TRUE
												AND b.tratamiento_id IS NOT NULL
												AND c.sw_estado IN ('0','1')
												AND a.sw_estado = '0'

												AND c.empresa_id = '" . trim($Formulario['empresa_id']) . "'  
												AND c.centro_utilidad = '" . trim($Formulario['centro_utilidad']) . "' 
												AND c.bodega = '" . trim($Formulario['bodega']) . "' 
												group by a.codigo_medicamento
											) as x ON (fv.codigo_producto = x.codigo_medicamento)
                    WHERE 
								fv.empresa_id = '" . trim($Formulario['empresa_id']) . "'
                    AND     fv.centro_utilidad = '" . trim($Formulario['centro_utilidad']) . "'
                    AND     fv.bodega = '" . trim($Formulario['bodega']) . "'
                    AND     (fv.existencia_actual - COALESCE(x.total,0)) > 0
					AND		fv.estado = '1'
                    AND     fv.codigo_producto = '" . $producto . "'
                    $filtro
                    ORDER BY invp.descripcion ASC,fv.fecha_vencimiento ASC  ";

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

    /*
     * Funcion donde se  buscan los productos por lote     
      @return array $datos vector que contiene la informacion de la consulta.
     */

    function Buscar_ProductoLote($doc_tmp_id, $codigo_producto, $lote, $codigo_productoD) {

        $sql = " SELECT * ";
        $sql .= " FROM   esm_dispensacion_medicamentos_tmp ";
        $sql .= " WHERE  	formula_id_tmp = " . $doc_tmp_id . " ";
        $sql .= " and    codigo_producto = '" . $codigo_productoD . "' ";
        $sql .= " and    lote = '" . $lote . "'   
                  and codigo_formulado= '" . $codigo_producto . "' ";
        $datos = array();
        if (!$rst = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        if (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se guardan los productos en el temporal    
      @return boolean.
     */

    function GuardarTemporal($formula_id, $codigo_producto, $cantidad, $fecha_venc, $lotec, $formulario, $formulado, $f_rango = '0') {

        $this->ConexionTransaccion();
        list( $dia, $mes, $ano ) = split('[/.-]', $fecha_venc);
        $fecha_venc = $ano . "-" . $mes . "-" . $dia;
        $sql = "INSERT INTO   	esm_dispensacion_medicamentos_tmp
                  (
                                    esm_dispen_tmp_id,
                                     empresa_id, 
                                    centro_utilidad,
                                    bodega, 
                                    codigo_producto,
                                    cantidad_despachada,
                                    fecha_vencimiento, 
                                    lote,
                                    codigo_formulado,
                                    usuario_id,
                                    sw_entregado_off,
                                    formula_id_tmp
                 )VALUES 
                (				DEFAULT,
                        '" . trim($formulario['empresa_id']) . "',
                        '" . trim($formulario['centro_utilidad']) . "',
                        '" . trim($formulario['bodega_']) . "', 
                        '" . trim($codigo_producto) . "',
                        " . trim($cantidad) . ", 
                        '" . trim($fecha_venc) . "', 
                        '" . trim($lotec) . "',
                        '" . trim($formulado) . "',
                        " . UserGetUID() . ",
                        " . trim($f_rango) . ",
                        " . trim($formula_id) . "
                        
                ); ";


        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }

    /* Funcion que permite consultar si existe un medicamento formulado
      @ return array con los datos de la informacion */

    function Medicamento_Formulado_tmp($tmp_id, $medicamento) {


        $sql = "SELECT  tmp.fe_medicamento_id,
                                   tmp.sw_marcado,
                                   tmp.cantidad
					 FROM       esm_formula_externa_medicamentos_tmp tmp
					 WHERE  tmp.usuario_id = " . UserGetUID() . "
					 AND    tmp.tmp_formula_id='" . trim($tmp_id) . "'
           AND    tmp.codigo_producto='" . trim($medicamento) . "'; 

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

    /*
     * Funcion donde se Elimina la formulacion temporal  de los medicamentos 
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Eliminar_Medicamento_tmp($medicamento, $formula_id) {
        $this->ConexionTransaccion();


        $sql .= " Delete     FROM  esm_formula_externa_medicamentos_tmp 
                    where  	   usuario_id=" . UserGetUID() . " 
                    and        	codigo_producto = '" . trim($medicamento) . "' 
                    and         	tmp_formula_id='" . trim($formula_id) . "' ";


        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }

    /*
     * Funcion que permite eliminar un producto que se encuentra en el temporal      
      @return array $datos vector que contiene la informacion de la consulta.
     */

    function EliminarProducto_tmpP($formula_id, $codigo_producto, $seria_id) {

        $this->ConexionTransaccion();
        $sql = "  DELETE FROM  esm_dispensacion_medicamentos_tmp 
                  WHERE   esm_dispen_tmp_id ='" . trim($seria_id) . "'
                  AND     formula_id='" . trim($formula_id) . "'
                  AND     codigo_producto = '" . trim($codigo_producto) . "' ";

        if (!$rst = $this->ConexionTransaccion($sql))
            return false;
        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se consultan los productos temporales     
      @return array $datos vector que contiene la informacion de la consulta.
     */

    function Buscar_veces_producto_formulado($formula_id, $producto_formulado) {


        $sql = "SELECT  COUNT(esm_dispen_tmp_id) as cantidad
                FROM    esm_dispensacion_medicamentos_tmp
                WHERE   formula_id_tmp = '" . trim($formula_id) . "'  
                AND      codigo_formulado='" . trim($producto_formulado) . "'  ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* Funcion que permite consultar si existe un medicamento formulado
      @ return array con los datos de la informacion */

    function Medicamentos_Formulados_tmp_t($tmp_id) {

        $sql = "SELECT   
                tmp.codigo_producto,
                fc_descripcion_producto_alterno(tmp.codigo_producto) as descripcion_prod,
                tmp.cantidad,
                tmp.tiempo_tratamiento,
                tmp.unidad_tiempo_tratamiento,
                tmp.sw_marcado,
                tmp.sw_ocultar
                FROM esm_formula_externa_medicamentos_tmp tmp WHERE tmp.tmp_formula_id='" . trim($tmp_id) . "';";


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

    /* Funcion donde se  consultan los productros del temporal     
      @return array $datos vector que contiene la informacion de la consulta.
     */

    function Buscar_Productos_despacho_tmp($doc_tmp_id, $codigo_producto) {

        $sql = " SELECT *, fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod ";
        $sql .= " FROM   esm_dispensacion_medicamentos_tmp ";
        $sql .= " WHERE  	formula_id_tmp = " . $doc_tmp_id . " ";
        $sql .= " and    codigo_formulado = '" . $codigo_producto . "' ";

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

    /* Funcion que permite  actualizar la cantidad solicitada en la formulacion
      @ return array con los datos de la informacion */

    function Update_cantidad_formulacion_tmp($formula_id, $medicamento, $cantidad) {
        $this->ConexionTransaccion();
        $sql .= " update esm_formula_externa_medicamentos_tmp   ";
        $sql .= "set 	 	  cantidad=" . $cantidad . " ";
        $sql .= " WHERE  	tmp_formula_id = '" . $formula_id . "' ";
        $sql .= " and    codigo_producto = '" . $medicamento . "'  ";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }

        $this->Commit();
        $rst1->Close();
        return true;
    }

    /*
     * Funcion que permite eliminar 
      los temporales del producto formulado
      @return boolean
     */

    function Eliminarformulados_tmp($formula_id, $codigo_producto) {

        $this->ConexionTransaccion();
        $sql = "  DELETE FROM  esm_dispensacion_medicamentos_tmp 
                  WHERE      formula_id_tmp='" . trim($formula_id) . "'
                  AND     codigo_formulado = '" . trim($codigo_producto) . "' ";

        if (!$rst = $this->ConexionTransaccion($sql))
            return false;
        $this->Commit();
        return true;
    }

    /*
     * Funcion que permite consultar el temporal de los productos a despachar      
      @return array $datos vector que contiene la informacion de la consulta.
     */

    function Buscar_producto_tmp_conc($formula_id) {


        $sql = " SELECT  esm_dispen_tmp_id,
                        formula_id_tmp,
                        empresa_id,
                        centro_utilidad,
                        bodega,
                        codigo_producto,
                        cantidad_despachada,
                        to_char(fecha_vencimiento,'dd-mm-yyyy')as fecha_vencimiento,
                        lote,
                        fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod
                FROM    esm_dispensacion_medicamentos_tmp
                WHERE   formula_id_tmp = '" . $formula_id . "' ";
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

    /*
     * Funcion donde se consulta los datos de la formula temporal 
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function consultar_Formulacion_TMP($empresa, $tmp_id) {



        $sql = " SELECT
							FR.*,
                            to_char(FR.fecha_formula,'dd-mm-yyyy') as fecha_formula,
                            PRO.nombre AS profesional,
                            PAC.primer_nombre || ' ' ||  PAC.segundo_nombre|| ' ' || PAC.primer_apellido|| ' ' ||PAC.segundo_apellido AS nombre_paciente,
                            PAC.sexo_id,
                            edad(PAC.fecha_nacimiento) as edad,
                            TIPOPROF.descripcion as descripcion_profesional,
                            PLAN.plan_descripcion 
                      
           FROM             esm_formula_externa_tmp FR 
                 LEFT JOIN  profesionales PRO ON(FR.tipo_id_tercero=PRO.tipo_id_tercero)and (FR.tercero_id=PRO.tercero_id)
                 LEFT JOIN  tipos_profesionales TIPOPROF ON(PRO.tipo_profesional=TIPOPROF.tipo_profesional)
                 LEFT JOIN  pacientes  PAC ON(FR.tipo_id_paciente=PAC.tipo_id_paciente) and (FR.paciente_id=PAC.paciente_id)
                 LEFT JOIN   planes_rangos PLANR ON(FR.plan_id=PLANR.plan_id) and (FR.rango=PLANR.rango)and(FR.tipo_afiliado_id=PLANR.tipo_afiliado_id)
                 LEFT JOIN   planes PLAN ON(PLANR.plan_id=PLAN.plan_id)
                      
          WHERE   FR.tmp_formula_id = " . $tmp_id . " 
					AND     FR.tmp_empresa_id = '" . $empresa['empresa_id'] . "' 
				
					AND     FR.usuario_id =" . UserGetUID() . "    ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se eliminan las reservas de productos de tratamientos especiales
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ReservasProductos($empresa_id) {



        $sql = " SELECT fc_eliminar_reservas_dispensacion('" . trim($empresa_id) . "') as mensaje;";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* Funcion que permite consultar medicamentos formulados
      @ return array con los datos de la informacion */

    function Medicamentos_Formulados_tmp_($tmp_id) {


        $sql = "SELECT 
                tmp.codigo_producto,
                tmp.cantidad,
                tmp.tiempo_tratamiento,
                tmp.unidad_tiempo_tratamiento,
                tmp.sw_marcado,
                tmp.sw_ocultar,
                tmp.sw_autorizado,
                tmp.usuario_autoriza_id, 	
                tmp.observacion_autorizacion,
                tmp.fecha_registro_autorizacion                      
                FROM esm_formula_externa_medicamentos_tmp tmp
                WHERE tmp.usuario_id = " . UserGetUID() . "
                AND tmp.tmp_formula_id='" . $tmp_id . "' ; ";

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

    /*
     * Funcion donde se consulta los datos de la formula temporal 
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Formula_CamposB_tmp($empresa, $tmp_id) {



        $sql = " SELECT         tmp_formula_papel,
                                        to_char(fecha_formula,'YYYY-MM-DD') as fecha_formula,
                                        tipo_formula,
                                        tipo_id_tercero,
                                        tercero_id,
                                        tipo_id_paciente,
                                        paciente_id,
                                        plan_id,
                                        rango,
                                        tipo_afiliado_id,
                                        semanas_cotizadas,
										sw_autorizado,					  
										usuario_autoriza_id,
										observacion_autorizacion,
										fecha_registro_autorizacion,
                                                                                tipo_pais_id,
                                                                                tipo_dpto_id,
                                                                                tipo_mpio_id
                      
           FROM             esm_formula_externa_tmp FR 
             
                      
          WHERE   tmp_formula_id = " . $tmp_id . " 
					AND    tmp_empresa_id = '" . $empresa['empresa_id'] . "' 
					AND     usuario_id =" . UserGetUID() . "    ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* Funcion que permite consultar si el usuario tiene permiso para dispensar la formulacion Externa

      /*
     * Funcion que permite consultar los medicamentos que quedaron  pendientes por despachar      
      @return array $datos vector que contiene la informacion de la consulta.
     */

    function Medicamentos_Pendientes_Esm($formula_id) {


        $sql = " select codigo_medicamento,
                          SUM(numero_unidades) as total,
                          fc_descripcion_producto_alterno(codigo_medicamento) as descripcion_prod
                        from
							          (
                            SELECT
                                  dc.codigo_medicamento,
                                  SUM(dc.cantidad) as numero_unidades
									
                          FROM       esm_pendientes_por_dispensar as dc
                          WHERE      dc.formula_id = " . $formula_id . "
                          AND        dc.sw_estado = '0'
                          GROUP BY (dc.codigo_medicamento)
                        ) as A
                        group by (codigo_medicamento)
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

    /**
     * Funcion donde se consultan los medicamentos dispensados por lote
     * * @return array $datos vector que contiene la informacion de la consulta
     */
    function Medicamentos_Dispensados_Esm_x_lote($formula_id) {

        $fecha_hoy = date('Y-m-d');
        $sql = " 	  select
                        dd.codigo_producto,
                        dd.cantidad as numero_unidades,
                        dd.fecha_vencimiento ,
                        dd.lote,
                        fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
                        d.usuario_id,
                        sys.nombre,
                        sys.descripcion
                       
						  FROM
							  esm_formulacion_despachos_medicamentos as dc,
							  bodegas_documentos as d,
							  bodegas_documentos_d AS dd,
							  system_usuarios  sys
						  WHERE
                          dc.bodegas_doc_id = d.bodegas_doc_id
						  and        dc.numeracion = d.numeracion
						  and        dc.formula_id = " . $formula_id . "
						  and        d.bodegas_doc_id = dd.bodegas_doc_id
						  and        to_char(d.fecha_registro,'YYYY-mm-dd')='" . $fecha_hoy . "'
						  and        d.numeracion = dd.numeracion
						  and       d.usuario_id=sys.usuario_id
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

    /**
     *  Funcion donde se consultan los medicamentos pendientes que fueron dispensados 
     * * @return array $datos vector que contiene la informacion de la consulta
     */
    function pendientes_dispensados_ent($formula_id) {

        $fecha_hoy = date('Y-m-d');

        $sql = "    SELECT   dd.codigo_producto,
                          dd.cantidad as numero_unidades,
                          dd.fecha_vencimiento , 
                          dd.lote,
                          fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
                          dd.sw_pactado,
                          dd.total_costo
                    FROM  esm_formulacion_despachos_medicamentos_pendientes tmp,
                          bodegas_documentos as d, 
                          bodegas_documentos_d AS dd
                   WHERE tmp.bodegas_doc_id = d.bodegas_doc_id 
                   and tmp.numeracion = d.numeracion 
                   and d.bodegas_doc_id = dd.bodegas_doc_id 
                   and to_char(d.fecha_registro,'YYYY-mm-dd')='" . $fecha_hoy . "'
                   and d.numeracion = dd.numeracion 
                   and  tmp.formula_id = '" . $formula_id . "' ";


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
     *  Funcion donde se consultan los medicamentos pendientes que fueron dispensados sin incluir  fecha de despacho
     * * @return array $datos vector que contiene la informacion de la consulta
     */
    function pendientes_dispensados_total($formula_id) {

        $sql = "    SELECT   dd.codigo_producto,
                          dd.cantidad as numero_unidades,
                          dd.fecha_vencimiento , 
                          dd.lote,
                          fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
                          dd.sw_pactado,
                          dd.total_costo
                    FROM  esm_formulacion_despachos_medicamentos_pendientes tmp,
                          bodegas_documentos as d, 
                          bodegas_documentos_d AS dd
                   WHERE tmp.bodegas_doc_id = d.bodegas_doc_id 
                   and tmp.numeracion = d.numeracion 
                   and d.bodegas_doc_id = dd.bodegas_doc_id 
                   and d.numeracion = dd.numeracion 
                   and  tmp.formula_id = '" . $formula_id . "' ";


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
     *  Funcion donde se consultan los medicamentos pendientes  
     * * @return array $datos vector que contiene la informacion de la consulta
     */
    function Consultar_Medicamentos_Detalle_P($Formulario, $formula_id) {

        if ($Formulario['codigo_barras'] != "") {
            $filtro = " and A.codigo_barras = '" . $Formulario['codigo_barras'] . "' ";
        }

        //if($Formulario['descripcion']!="" || $Formulario['codigo_barras']!="")
        // {

        $sql = "SELECT         
				tmp.codigo_medicamento as codigo_producto,
				SUM(tmp.cantidad) as cantidad,
				fc_descripcion_producto_alterno(tmp.codigo_medicamento) as descripcion_prod,
				MED.cod_principio_activo							
				FROM            esm_pendientes_por_dispensar tmp,
				inventarios_productos A 
				left join  medicamentos MED ON (A.codigo_producto=MED.codigo_medicamento)

				WHERE    tmp.formula_id='" . trim($formula_id) . "'
				AND  tmp.codigo_medicamento= A.codigo_producto 
				and 	tmp.sw_estado='0'
				AND  A.descripcion ILIKE '%" . trim($Formulario['descripcion']) . "%' 
				" . $filtro;
        $sql .= " group by  tmp.codigo_medicamento,MED.cod_principio_activo ";


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

    /*
     * Funcion donde se  buscan los productos por lote     
      @return array $datos vector que contiene la informacion de la consulta.
     */

    function Buscar_ProductoLoteP($doc_tmp_id, $codigo_producto, $lote, $codigo_productoD) {

        $sql = " SELECT * ";
        $sql .= " FROM   esm_dispensacion_medicamentos_tmp ";
        $sql .= " WHERE  formula_id = " . $doc_tmp_id . " ";
        $sql .= " and    codigo_producto = '" . $codigo_productoD . "' ";
        $sql .= " and    lote = '" . $lote . "'   
                  and codigo_formulado= '" . $codigo_producto . "' ";

        $datos = array();
        if (!$rst = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        if (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se  consulta los productos temporales    
      @return array $datos vector que contiene la informacion de la consulta.
     */

    function Cantidad_ProductoTemporalP($doc_tmp_id, $principio_activo, $codigo_producto) {

        $sql = "  SELECT  COALESCE(sum(cantidad_despachada),0) as total,
                          codigo_formulado
                  FROM    esm_dispensacion_medicamentos_tmp 
                  WHERE   codigo_formulado='" . trim($codigo_producto) . "'
			           	AND     	formula_id = " . trim($doc_tmp_id) . " ";
        /* if($principio_activo!="")
          {
          $sql .="   and    med.cod_principio_activo = '".$principio_activo."'  ";
          }else
          {
          $sql .="   and    invp.codigo_producto = '".$codigo_producto."'  ";

          } */
        $sql .= "  GROUP BY 	codigo_formulado ";

        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        $cuentas = Array();
        while (!$resultado->EOF) {
            $cuentas = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $cuentas;
    }

    /*
     * Funcion donde se guardan los productos en el temporal    
      @return boolean.
     */

    function GuardarTemporalP($formula_id, $codigo_producto, $cantidad, $fecha_venc, $lotec, $formulario, $formulado, $f_rango = '0') {

        $this->ConexionTransaccion();
        list( $dia, $mes, $ano ) = split('[/.-]', $fecha_venc);
        $fecha_venc = $ano . "-" . $mes . "-" . $dia;
        $sql = "INSERT INTO   	esm_dispensacion_medicamentos_tmp
                  (
                                    esm_dispen_tmp_id,
                                     empresa_id, 
                                    centro_utilidad,
                                    bodega, 
                                    codigo_producto,
                                    cantidad_despachada,
                                    fecha_vencimiento, 
                                    lote,
                                    codigo_formulado,
                                    usuario_id,
                                    sw_entregado_off,
                                    formula_id
                 )VALUES 
                (				DEFAULT,
                        '" . $formulario['empresa_id'] . "',
                        '" . $formulario['centro_utilidad'] . "',
                        '" . $formulario['bodega'] . "', 
                        '" . $codigo_producto . "',
                        " . $cantidad . ", 
                        '" . $fecha_venc . "', 
                        '" . $lotec . "',
                        '" . $formulado . "',
                        " . UserGetUID() . ",
                        " . $f_rango . ",
                        " . $formula_id . "
                        
                ); ";


        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se consultan los productos temporales     
      @return array $datos vector que contiene la informacion de la consulta.
     */

    function Buscar_producto_tmp_p($formula_id) {

        $sql = " SELECT  esm_dispen_tmp_id,
                        formula_id,
                        empresa_id,
                        centro_utilidad,
                        bodega,
                        codigo_producto,
                        cantidad_despachada,
                        fecha_vencimiento,
                        lote,
                        codigo_formulado,
                        fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod
                FROM    esm_dispensacion_medicamentos_tmp
                WHERE   formula_id = '" . $formula_id . "' ";
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

    /*
     * Funcion donde se  consulta el usuario con privilegios
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Usuario_Privilegios_($Formulario) {

        $sql = "	SELECT  sw_privilegios
                	FROM     userpermisos_Formulacion_Externa
                  WHERE    empresa_id= '" . $Formulario['empresa_id'] . "' 
                  AND     centro_utilidad = '" . $Formulario['centro_utilidad'] . "'
                  AND    usuario_id =  " . UserGetUID() . "
                  AND    sw_activo = '1' ";

        $datos = array();
        if (!$rst = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];
        if (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /**
     *  Funcion donde se guarda la autorizacion de que una formula vencida se pueda despachar 
     * * @return array $datos vector que contiene la informacion de la consulta
     */
    function UpdateAutorizacion_por_Formula($formula_id, $observacion) {
        $this->ConexionTransaccion();
        $sql = "   	 update  esm_formula_externa_tmp
		                 set 	 sw_autorizado='1', 
								usuario_autoriza_id= " . UserGetUID() . ",
								observacion_autorizacion='" . $observacion . "',
								fecha_registro_autorizacion=now()
						 
						WHERE   	tmp_formula_id= " . $formula_id . "
						
						  ";
        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }

    /*
     * Funcion que permite eliminar un producto que se encuentra en el temporal      
      @return array $datos vector que contiene la informacion de la consulta.
     */

    function EliminarProducto_tmp($formula_id, $codigo_producto, $seria_id) {
        $this->ConexionTransaccion();
        if ($seria_id != "")
            $filtro = " AND esm_dispen_tmp_id ='" . trim($seria_id) . "' ";

        $sql = "  DELETE FROM  esm_dispensacion_medicamentos_tmp 
		WHERE   TRUE
		" . $filtro . "	  
		AND     formula_id_tmp='" . trim($formula_id) . "'
		AND     codigo_producto = '" . trim($codigo_producto) . "' ";
        if (!$rst = $this->ConexionTransaccion($sql))
            return false;
        $this->Commit();
        return true;
    }

    /*
     * Funcion donde se  consulta el ultimo registro dispensado por principio activo o por codigo del producto 
      @return array $datos vector que contiene la informacion de la consulta.
     */

     function ConsultarUltimoResg_Dispens_($principio_activo, $paciente_id, $tipo_id_paciente, $producto, $today, $fecha_dias) {
   
        
        $sql = "
                SELECT A.resultado,
                A.fecha_registro,
                A.unidades,
                A.nombre,
                A.razon_social
                FROM (
                    SELECT distinct inve.codigo_producto,
			MAX(to_char(d.fecha_registro,'YYYY-mm-dd')) AS fecha_registro,
			'1' as resultado,           
			SYS.nombre,
			EMPRE.razon_social,
			hc.codigo_medicamento,
			dd.codigo_producto,
			SUM(dd.cantidad) as unidades
			FROM hc_formulacion_antecedentes hc
			INNER JOIN hc_formulacion_despachos_medicamentos as dc  ON(dc.evolucion_id=hc.evolucion_id and dc.sw_estado='1')
			INNER JOIN bodegas_documentos as d ON (dc.bodegas_doc_id = d.bodegas_doc_id and dc.numeracion = d.numeracion )
			INNER JOIN bodegas_documentos_d AS dd ON (d.bodegas_doc_id = dd.bodegas_doc_id  and d.numeracion = dd.numeracion )
			INNER JOIN system_usuarios  SYS ON (d.usuario_id=SYS.usuario_id)
			INNER JOIN inventarios_productos inve ON (dd.codigo_producto=inve.codigo_producto) 
			INNER JOIN medicamentos mm ON (inve.codigo_producto=mm.codigo_medicamento)
			INNER JOIN bodegas_doc_numeraciones  NUME ON (d.bodegas_doc_id=NUME.bodegas_doc_id)
			INNER JOIN empresas EMPRE ON (NUME.empresa_id=EMPRE.empresa_id)
                        where true ";
        if ($principio_activo != "") {
            $sql .="and mm.cod_principio_activo='" . $principio_activo . "' ";
        } else {
            $sql .="and inve.codigo_producto='" . $producto . "' ";
        }

        $sql .= "   and hc.tipo_id_paciente='" . $tipo_id_paciente . "'
                    and hc.paciente_id='" . $paciente_id . "'                   
                    GROUP BY 1,3,4,5,6,7

                    UNION

                    SELECT distinct inve.codigo_producto,
                    MAX(to_char(d.fecha_registro,'YYYY-mm-dd')) AS fecha_registro,
                    '1' as resultado,           
                    SYS.nombre,
                    EMPRE.razon_social,
                    hc.codigo_medicamento,
                    dd.codigo_producto,
                    SUM(dd.cantidad) as unidades
                    FROM hc_formulacion_despachos_medicamentos_pendientes as dc
                    INNER JOIN hc_formulacion_antecedentes hc ON(dc.evolucion_id=hc.evolucion_id)
                    INNER JOIN bodegas_documentos as d ON (dc.bodegas_doc_id = d.bodegas_doc_id AND dc.numeracion = d.numeracion)
                    INNER JOIN bodegas_documentos_d AS dd ON (d.bodegas_doc_id = dd.bodegas_doc_id and d.numeracion = dd.numeracion)
                    INNER JOIN inventarios_productos inve  ON (dd.codigo_producto=inve.codigo_producto)
                    left join medicamentos mm ON (inve.codigo_producto=mm.codigo_medicamento) 
                    INNER JOIN system_usuarios SYS ON (d.usuario_id=SYS.usuario_id)
                    INNER JOIN bodegas_doc_numeraciones  NUME ON (d.bodegas_doc_id=NUME.bodegas_doc_id and d.bodegas_doc_id=NUME.bodegas_doc_id)
                    INNER JOIN empresas EMPRE ON ( NUME.empresa_id=EMPRE.empresa_id)
                    WHERE true ";
        if ($principio_activo != "") {
            $sql .= "and mm.cod_principio_activo='" . $principio_activo . "' ";
        } else {
            $sql .= "and inve.codigo_producto='" . $producto . "' ";
        }
        $sql .= "
                     and hc.tipo_id_paciente='" . $tipo_id_paciente . "'
                     and hc.paciente_id='" . $paciente_id . "'
                     GROUP BY 1,3,4,5,6,7
                     
union


    SELECT 	'',									
                MAX(to_char(a.fecha_registro,'YYYY-MM-DD')) AS fecha_registro,
                '1' as resultado,                
                g.nombre,
                f.descripcion||'-'||i.razon_social as razon_social,
                '','',
                SUM(b.cantidad) as unidades
                FROM
                bodegas_documentos as a
                JOIN bodegas_documentos_d as b ON (a.bodegas_doc_id = b.bodegas_doc_id)
                AND (a.numeracion = b.numeracion)
                JOIN esm_formulacion_despachos_medicamentos as c ON (a.bodegas_doc_id = c.bodegas_doc_id)
                AND (a.numeracion = c.numeracion)
                JOIN esm_formula_externa as d ON (c.formula_id = d.formula_id)
                JOIN bodegas_doc_numeraciones as e ON (a.bodegas_doc_id = e.bodegas_doc_id)
                JOIN centros_utilidad as f ON (e.empresa_id = f.empresa_id)
                AND (e.centro_utilidad = f.centro_utilidad)
                JOIN empresas as i ON (f.empresa_id = i.empresa_id)
                JOIN system_usuarios as g ON (a.usuario_id = g.usuario_id)
                JOIN inventarios_productos as h ON (b.codigo_producto = h.codigo_producto)
                WHERE TRUE  ";
           if ($principio_activo != "")
            $sql .= " and h.subclase_id='" . trim($principio_activo) . "' ";
        else
            $sql .= "and 		b.codigo_producto='" . trim($producto) . "'  	";
        $sql .= "  
                and 		d.tipo_id_paciente='" . $tipo_id_paciente . "'
                and 		d.paciente_id='" . $paciente_id . "'
                and 		c.sw_estado='1'
                and 		d.sw_estado IN ('0','1')
                and   	d.fecha_registro <= ('" . $today . "'::date +'1 day' ::interval)::date
		and 		d.fecha_registro >= '" . $fecha_dias . "'::date 
                GROUP BY 1,3,4,5,6,7
union


    SELECT 	'',									
                MAX(to_char(a.fecha_registro,'YYYY-MM-DD')) AS fecha_registro,
                '0' as resultado,                
                g.nombre,
                f.descripcion||'-'||i.razon_social as razon_social,
                '','',
                SUM(b.cantidad) as unidades
                FROM
               bodegas_documentos as a
                JOIN bodegas_documentos_d as b ON (a.bodegas_doc_id = b.bodegas_doc_id)
                AND (a.numeracion = b.numeracion)
                JOIN esm_formulacion_despachos_medicamentos_pendientes as c ON (a.bodegas_doc_id = c.bodegas_doc_id)
                AND (a.numeracion = c.numeracion)
                JOIN esm_formula_externa as d ON (c.formula_id = d.formula_id)
                JOIN bodegas_doc_numeraciones as e ON (a.bodegas_doc_id = e.bodegas_doc_id)
                JOIN centros_utilidad as f ON (e.empresa_id = f.empresa_id)
                AND (e.centro_utilidad = f.centro_utilidad)
                JOIN empresas as i ON (f.empresa_id = i.empresa_id)
                JOIN system_usuarios as g ON (a.usuario_id = g.usuario_id)
                JOIN inventarios_productos as h ON (b.codigo_producto = h.codigo_producto)
                WHERE TRUE  ";
           if ($principio_activo != "")
            $sql .= " and h.subclase_id='" . trim($principio_activo) . "' ";
        else
            $sql .= "and 		b.codigo_producto='" . trim($producto) . "'  	";
        $sql .= "  
                and 		d.tipo_id_paciente='" . $tipo_id_paciente . "'
                and 		d.paciente_id='" . $paciente_id . "'
                and 		d.sw_estado IN ('0','1')
                and   	d.fecha_registro <= ('" . $today . "'::date +'1 day' ::interval)::date
		and 		d.fecha_registro >= '" . $fecha_dias . "'::date 
                GROUP BY 1,3,4,5,6,7	


           ) AS A ORDER BY  A.resultado, A.fecha_registro ASC  ";
		
		//echo "sql " . $sql;
		
            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;
            $datos = array();
            while (!$rst->EOF) {
                $datos = $rst->GetRowAssoc($ToUpper);
                $rst->MoveNext();
            }
            $rst->Close();
        


        return $datos;
    }
    /**
     *  Funcion donde se guarda la autorizacion de para despachar los medicamentos  
     * * @return array $datos vector que contiene la informacion de la consulta
     */
    function UpdateAutorizacion_por_medicamento($formula_id, $observacion, $producto) {
        $this->ConexionTransaccion();
        $sql = "   	 update  esm_formula_externa_medicamentos_tmp
		                 set 	 sw_autorizado='1', 
								usuario_autoriza_id= " . UserGetUID() . ",
								observacion_autorizacion='" . $observacion . "',
								fecha_registro_autorizacion=now()
						 
						WHERE   tmp_formula_id = " . $formula_id . "
						AND 	codigo_producto = '" . $producto . "'
						  ";
        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }

    /**
     *  Funcion donde sese consulta si el medicamento esta autorizado para despachar 
     * * @return array $datos vector que contiene la informacion de la consulta
     */
    function ConsultaAutorizacion_por_medicamento($formula_id, $producto) {

        $sql = "   	 SELECT   sw_autorizado
		                    FROM    esm_formula_externa_medicamentos_tmp
                        WHERE   tmp_formula_id = '" . $formula_id . "'
                        AND 	codigo_producto = '" . $producto . "'
						  ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /**
     *  Funcion donde sese consulta si el medicamento esta Marcado o no 
     * * @return array $datos vector que contiene la informacion de la consulta
     */
    function ConsultaMarcado_por_medicamento($formula_id, $producto) {

        $sql = "   	 SELECT   sw_marcado
		                    FROM    esm_formula_externa_medicamentos_tmp
                        WHERE   tmp_formula_id = '" . $formula_id . "'
                        AND 	codigo_producto = '" . $producto . "'
                        AND   sw_marcado='1'
						  ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se consultar el temporal de la formula
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarInformacion_Temporal_Formula($formula_id) {

        $sql = "   SELECT   sys.nombre,
			                    emp.razon_social,
                          u.nombre as nombre_creador,
                          tmp.usuario_id
                
			          FROM    
                          esm_dispensacion_medicamentos_tmp as tmp
               JOIN       bodegas bod ON (tmp.empresa_id=bod.empresa_id and tmp.centro_utilidad=bod.centro_utilidad and tmp.bodega=bod.bodega)
							 JOIN       centros_utilidad  cen ON (bod.empresa_id=cen.empresa_id and bod.centro_utilidad=cen.centro_utilidad)
							 JOIN       empresas emp ON (cen.empresa_id=emp.empresa_id)
							 LEFT JOIN  system_usuarios sys ON (sys.usuario_id=tmp.usuario_id)
							 LEFT JOIN (
                            SELECT 
                                      b.nombre,
                                      a.formula_id_tmp,
                                      a.usuario_id
                           FROM
                                      esm_dispensacion_medicamentos_tmp as a
                          JOIN system_usuarios as b ON (a.usuario_id = b.usuario_id)
                          WHERE   
                                a.formula_id_tmp = " . $formula_id . "
                                GROUP BY a.formula_id_tmp,b.nombre,a.usuario_id
					  		 ) as u ON (tmp.usuario_id = u.usuario_id)
							 
                WHERE    tmp.formula_id_tmp = " . $formula_id . "
                and 	   tmp.formula_id_tmp = u.formula_id_tmp ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /*
     * Funcion donde se Elimina la formulacion temporal  de los medicamentos seleccionados para entregar
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Eliminar_dis_tmp() {

        $sql .= " Delete     FROM  esm_dispensacion_medicamentos_tmp 
                    where  	   usuario_id=" . UserGetUID() . " 
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

    /*
     * Funcion donde se consultan los tipos de formulas
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function Consultar_Tipos_Formulas($datos) {

        $sql = " SELECT 
						a.tipo_formula_id,
						a.descripcion_tipo_formula,
						CASE
						WHEN c.tipo_formula_id IS NOT NULL AND b.tipo_formula_id IS NULL AND ('1' = '" . trim($datos['sw_topes']) . "')
						THEN c.tope_mensual
						WHEN c.tipo_formula_id IS NOT NULL AND b.tipo_formula_id IS NOT NULL AND ('1' = '" . trim($datos['sw_topes']) . "')
						THEN b.saldo
						END as tope
		FROM   esm_tipos_formulas as a
				   LEFT JOIN esm_topes_dispensacion_mensual AS b ON (a.tipo_formula_id = b.tipo_formula_id)
				   AND (b.empresa_id = '" . trim($datos['empresa_id']) . "')
				   AND (b.centro_utilidad = '" . trim($datos['centro_utilidad']) . "')
				   AND (b.lapso = '" . (date('Ym')) . "')
				   
				   LEFT JOIN esm_topes_dispensacion as c ON (a.tipo_formula_id = c.tipo_formula_id)
				   AND (c.empresa_id = '" . trim($datos['empresa_id']) . "')
				   AND (c.centro_utilidad = '" . trim($datos['centro_utilidad']) . "')
				   WHERE  a.sw_estado = '1'  
		ORDER BY a.descripcion_tipo_formula ASC  ";

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
     * Funcion donde se consultan los tipos de formulas
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ObtenerPendientes_Pacientes($filtros, $datos_empresa, $offset) {

        $sql = " 	
		SELECT 
		d.tipo_id_paciente ||' ' ||d.paciente_id||' - '||primer_apellido ||' '||segundo_apellido ||' ' ||primer_nombre||' '|| segundo_nombre as paciente,
		c.formula_id,
		c.formula_papel,
		a.fecha_registro,
		a.cantidad,
		a.codigo_medicamento ||': '||fc_descripcion_producto_alterno(a.codigo_medicamento) as producto,
		b.tratamiento_id,
		CASE 
		WHEN b.tratamiento_id IS NOT NULL 
		THEN 'style=\"background:#00FF40;\" '
		ELSE ''
		END as color,
		a.esm_pendiente_dispensacion_id
		FROM
		esm_pendientes_por_dispensar AS a
		JOIN inventarios_productos AS b ON (a.codigo_medicamento = b.codigo_producto)
		JOIN esm_formula_externa as c ON (a.formula_id = c.formula_id)
		JOIN pacientes as d ON (c.tipo_id_paciente = d.tipo_id_paciente)
		AND (c.paciente_id = d.paciente_id)
		WHERE TRUE
		AND c.sw_estado IN ('0','1')
		AND a.sw_estado = '0'
		AND c.empresa_id = '" . trim($datos_empresa['empresa_id']) . "'  
		AND c.centro_utilidad = '" . trim($datos_empresa['centro_utilidad']) . "' 
		AND c.bodega = '" . trim($datos_empresa['bodega']) . "' ";
        if ($filtros['formula_papel'])
            $sql .= "	AND c.formula_papel ILIKE '%" . $filtros['formula_papel'] . "%'	";
        if ($filtros['producto'])
            $sql .= "	AND b.descripcion ILIKE '%" . $filtros['producto'] . "%'	";
        if ($filtros['paciente'])
            $sql .= "	AND d.tipo_id_paciente ||' ' ||d.paciente_id||' - '||primer_apellido ||' '||segundo_apellido ||' ' ||primer_nombre||' '|| segundo_nombre ILIKE '%" . $filtros['paciente'] . "%'	";

        $cont = " SELECT COUNT(*) FROM (" . $sql . ") A  ";
        $this->ProcesarSqlConteo($cont, $offset);

        $sql .= " ORDER BY b.tratamiento_id ASC ";
        $sql .= " LIMIT " . $this->limit . " OFFSET " . $this->offset;

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[$rst->fields[5]][] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* Funcion que permite INACTIVAR PENDIENTES
      @ return array con los datos de la informacion */

    function Inactivar_Pendientes($datos) {

        $this->ConexionTransaccion();
        $sql = " UPDATE esm_pendientes_por_dispensar
		SET sw_estado = '2'
		WHERE TRUE
		AND sw_estado = '0'
		AND esm_pendiente_dispensacion_id = '" . trim($datos['esm_pendiente_dispensacion_id']) . "'	";
        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        $rst->Close();
        return true;
    }

    function GuardaMarcaEntregaEnFarmacia($formula_id, $codigo_producto, $cantPendiente, $valor) {

        if (trim($valor) == "false" || trim($valor) == "" || trim($valor) == "0") {
            $val = "0";

            $sql = "DELETE FROM esm_pendientes_entrega_en_farmacia_tmp 
					WHERE formula_id = '$formula_id' AND codigo_medicamento = '$codigo_producto' AND fecha = current_date";
        } else {
            $val = "1";

            $sql = "INSERT INTO esm_pendientes_entrega_en_farmacia_tmp
					(formula_id,codigo_medicamento,cantidad_pendiente,sw_entrega_farmacia,fecha,hora)
					values
					($formula_id,'$codigo_producto',$cantPendiente,$val,current_date,current_time);";
        }

        /*
          $sql = "UPDATE esm_dispensacion_medicamentos_tmp
          SET
          sw_entrega_farmacia = '$val'
          WHERE formula_id = $formula_id AND
          codigo_producto = '$codigo_producto'";
         */

        //echo "SQL:$sql<br>";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $rst->Close();
        return true;
    }

    function GuardarLlamada($request) {

        $sql = "INSERT INTO llamadas_pacientes 
				(llamada_id,formula_id,contacto_nombre,contacto_parentezco,observacion,fecha,usuario_id,tel_contacto)
				VALUES
				(default," . $request['formula_id'] . ",'" . $request['nomcontacto'] . "','" . $request['parentezcocontacto'] . "',
				 '" . $request['observaciones'] . "',now()," . UserGetUID() . ",'" . $request['telefono'] . "') RETURNING llamada_id ;";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $LlamadaId = array();
        $LlamadaId = $rst->GetRowAssoc($ToUpper = false);


        $sql = "SELECT codigo_medicamento, fc_descrip_producto(codigo_medicamento) AS descripcion, cantidad ";
        $sql .= "   FROM esm_pendientes_por_dispensar WHERE formula_id = " . $request['formula_id'] . "; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $pend = array();
        while (!$rst->EOF) {
            $pend[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }


        foreach ($pend as $key => $value) {

            $sql = "INSERT INTO llamadas_pacientes_d ";
            $sql .= "             (  ";
            $sql .= "               llamada_detalle_id, ";
            $sql .= "               llamada_registro, ";
            $sql .= "               codigo_producto, ";
            $sql .= "               descripcion, ";
            $sql .= "               cantidad ";
            $sql .= "              ) ";
            $sql .= "             VALUES ";
            $sql .= "              ( ";
            $sql .= "               DEFAULT, ";
            $sql .= "              " . $LlamadaId['llamada_id'] . ", ";
            $sql .= "              '" . $value['codigo_medicamento'] . "', ";
            $sql .= "              '" . $value['descripcion'] . "', ";
            $sql .= "              '" . $value['cantidad'] . "' ";
            $sql .= "              ) ;";

            if (!$rst = $this->ConexionBaseDatos($sql))
                return false;
        }
        $rst->Close();

        return true;
    }

    function Consultar_Historial_Llamadas($formula_id) {
        $sql = "SELECT ll.llamada_id, ll.formula_id, ll.contacto_nombre, 
	                           ll.contacto_parentezco, ll.tel_contacto, ll.observacion,
				               to_char(ll.fecha,'YYYY-MM-DD HH12:MI:SS A.M.') as fecha,
				               ll.usuario_id, u.usuario
				     FROM llamadas_pacientes ll
			  LEFT JOIN system_usuarios u ON (ll.usuario_id = u.usuario_id)
				WHERE ll.formula_id = '" . $formula_id . "'
				ORDER BY ll.fecha DESC ";

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

    function ocultar_productos_pendientes_tmp($formula_id, $codigo_producto, $sw_ocultar) {

        $this->ConexionTransaccion();

        $sql = "UPDATE esm_formula_externa_medicamentos_tmp SET sw_ocultar='{$sw_ocultar}' WHERE tmp_formula_id='{$formula_id}' AND codigo_producto='{$codigo_producto}'";

        if (!$rst1 = $this->ConexionTransaccion($sql)) {
            return false;
        }

        $this->Commit();
        $rst1->Close();
        return true;
    }

    function consultar_productos_pendientes_ocultos($formula_id, $codigo_producto) {

        $sql = "SELECT * FROM esm_formula_externa_medicamentos a where a.formula_id={$formula_id} and a.codigo_producto='{$codigo_producto}'; ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }

    function consultar_municipios($termino_busqueda, $offset) {
        
        $sql = "select 
                a.tipo_pais_id,
                a.tipo_dpto_id,
                a.tipo_mpio_id,
                a.municipio,
                b.departamento
                from tipo_mpios a
                inner join tipo_dptos b on a.tipo_pais_id = b.tipo_pais_id and a.tipo_dpto_id = b.tipo_dpto_id
                where a.municipio ilike '%{$termino_busqueda}%' ";

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $offset))
            return false;

        $sql .= " ORDER BY municipio ";
        $sql .= " LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

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