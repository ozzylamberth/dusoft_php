<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id      DispensacionSQL.class.php,v  . 4
 * @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Sandra Viviana Pantoja Torr
 */
class DispensacionSQL extends ConexionBD {
    /*
     * Constructor de la clase
     */

    function DispensacionSQL() {
        
    }

    /**
     * Funcion donde se verifica el permiso del usuar
     * * @return array $datos vector que contiene la informacion de la consulta
     */
    function ObtenerPermisos() {

        $sql = " SELECT  a.empresa_id,
                        b.razon_social AS razon_social,
                        a.centro_utilidad,
                        c.descripcion AS centro_utilidad_des,
                        a.bodega,
                        e.descripcion as Bodega_des
          from        userpermisos_Dispensacion a,
                      bodegas e,
                      centros_utilidad c,
                      empresas b
          where     a.empresa_id=e.empresa_id
          and       a.centro_utilidad=e.centro_utilidad
          and       a.bodega=e.bodega
          and       e.empresa_id=c.empresa_id
          and       e.centro_utilidad=c.centro_utilidad
          and       c.empresa_id=b.empresa_id
          and       a.sw_activo = '1'
          and      a.usuario_id = " . UserGetUID() . "          ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[$rst->fields[1]][$rst->fields[3]][$rst->fields[5]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }

        $rst->Close();
        return $datos;
    }
        function CambiarestadoPendientesDispensar($disp_id,$justifi){
            $this->ConexionTransaccion();
               $sql = " UPDATE 
                            hc_pendientes_por_dispensar set 
                            sw_estado = '2',
                            usurio_reg_pendiente = ".UserGetUID().",
                            fecha_noreclama = now(),
                            justificacion_pendiente = '$justifi'
                            WHERE 
                            hc_pendiente_dispensacion_id = '$disp_id' ;";

            if (!$rst = $this->ConexionTransaccion($sql))
                        return false;
                    $this->Commit();
                    return true;
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
     * Funcion donde se Consultan la formulacion del paciente.
     * @param array $datos vector que contiene la informacion de la consulta
     */

    function ObtenerFormulasMedicas($filtros, $offset, $plan_atencion) {

        $util = AutoCarga::factory('ClaseUtil');
         $entrar=true;
         $sql2=" ";
        if ($filtros) {
            $sql1 .= " WHERE TRUE ";
            if ($filtros['evolucion_id'] != ""){
                $sql1 .= "AND   a.evolucion_id = '" . $filtros['evolucion_id'] . "' ";
            }
            if ($filtros['evolucion'] != ""){
                $sql1 .= "AND   a.evolucion_id = '" . $filtros['evolucion'] . "' ";
                $entrar=false;
                $this->offset=0;
            }
            if ($filtros['tipo_id_paciente'] != '-1' && $filtros['tipo_id_paciente'] != ''){
                $sql1 .= "AND   a.tipo_id_paciente = '" . $filtros['tipo_id_paciente'] . "' ";
            }
            if ($filtros['paciente_id']){
                $sql1 .= "AND   a.paciente_id = '" . $filtros['paciente_id'] . "' ";
            }
            if ($filtros['nombres']) {
                $sql1 .= "AND   a.nombres ILIKE  '%" .$filtros['nombres']."%'";
            }
            if ($filtros['apellidos']) {
               $sql1 .= "AND   a.apellidos ILIKE  '%" .$filtros['apellidos']."%'";               
            }
            if ($filtros['formula']!= "") {
                $sql1 .= "AND   a.numero_formula =  '" .$filtros['formula']."'";
                $sql2  =" WHERE numero_formula =  '" .$filtros['formula']."' ";
                $entrar=false;
                $this->offset=0;
            }
        }
        $sql = "   SELECT * FROM (
                        SELECT DISTINCT
                        '0' AS tipo_formula,
                        --'FORMULA DUANA' AS descripcion_tipo_formula,
                        a.transcripcion_medica,
                        CASE WHEN (a.transcripcion_medica='0' or a.transcripcion_medica ='2') THEN 'FORMULACION' ELSE 'TRANSCRIPCION' END AS descripcion_tipo_formula,
                        TO_CHAR(a.fecha_registro,'YYYY-MM-DD') AS fecha_registro,
                        c.fecha_finalizacion,
                        d.fecha_formulacion,
                        a.tipo_id_paciente,
                        a.paciente_id,
                        b.primer_apellido ||' '||b.segundo_apellido AS apellidos,
                        b.primer_nombre||' '||b.segundo_nombre AS nombres,
                        e.nombre,
                        a.evolucion_id,
                        coalesce(a.numero_formula, 0) AS numero_formula,
                        f.tipo_bloqueo_id,
                        f.descripcion AS bloqueo,
                        COALESCE(i.plan_id,0) as plan_id,
                        i.plan_descripcion,
                        edad(b.fecha_nacimiento) as edad,
                        b.sexo_id,
                        '1' as sw_entrega_med,
                        a.fecha_registro AS registro,
                        CURRENT_DATE as hoy,
                        a.refrendar
                        FROM hc_formulacion_antecedentes AS a
                        inner join pacientes as b ON (a.tipo_id_paciente = b.tipo_id_paciente) AND (a.paciente_id = b.paciente_id)
                        left join inv_tipos_bloqueos as f ON (b.tipo_bloqueo_id=f.tipo_bloqueo_id) AND (f.estado='1')
                        inner join (
                            SELECT
                            tipo_id_paciente,
                            paciente_id,
                            evolucion_id,
                            MAX(TO_CHAR(fecha_finalizacion,'YYYY-MM-DD')) AS fecha_finalizacion
                            FROM hc_formulacion_antecedentes $sql2 GROUP BY 1,2,3
                        ) AS c ON (a.tipo_id_paciente = c.tipo_id_paciente) AND (a.paciente_id = c.paciente_id) AND (a.evolucion_id = c.evolucion_id)
                        join (
                            SELECT
                            tipo_id_paciente,
                            paciente_id,
                            evolucion_id,
                            MIN(TO_CHAR(fecha_formulacion,'YYYY-MM-DD')) AS fecha_formulacion
                            FROM hc_formulacion_antecedentes $sql2 GROUP BY 1,2,3
                        ) AS d ON (a.tipo_id_paciente = d.tipo_id_paciente) 
                        AND (a.paciente_id = d.paciente_id) 
                        AND (a.evolucion_id = d.evolucion_id)
                        inner join system_usuarios as e ON (a.medico_id = e.usuario_id) 
                        inner join eps_afiliados as g ON (g.afiliado_tipo_id=b.tipo_id_paciente) 
                        AND (g.afiliado_id=b.paciente_id)
                        inner join planes_rangos AS h ON (g.plan_atencion=h.plan_id) 
                        AND (g.tipo_afiliado_atencion=h.tipo_afiliado_id) 
                        AND (g.rango_afiliado_atencion=h.rango)
                        inner join planes as i ON (h.plan_id=i.plan_id)
                        WHERE a.codigo_medicamento IS NOT NULL
                    ) AS a  ";
                       $sql.=$sql1;    

                        if($entrar){
                        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (" . $sql . ") A", $offset)){ 
                               return false;
                          }
                        }  
                    $this->limit = 20;
                    $whr .= " ORDER BY a.fecha_registro DESC ";
                    $whr .= " LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";     
                   
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
    /*
     * Funcion donde se Consultan la formulacion del paciente.
     * @param array $datos vector que contiene la informacion de la consulta
     */

    function ObtenerFormulasPendientes($filtros, $offset, $plan_atencion) {

        $util = AutoCarga::factory('ClaseUtil');
         $entrar=false;
         $this->offset=0;
         $sql2=" ";
        if ($filtros) {
            $sql1 = " ";
            if ($filtros['evolucion_id'] != ""){
                $sql1 .= " AND   HP.EVOLUCION_ID = '" . $filtros['evolucion_id'] . "' ";
            }
            if ($filtros['evolucion'] != ""){
                $sql1 .= " AND   HP.EVOLUCION_ID = '" . $filtros['evolucion'] . "' ";
           //     $entrar=false;
                
            }
            if ($filtros['tipo_id_paciente'] != '-1' && $filtros['tipo_id_paciente'] != ''){
                $sql1 .= " AND   HF.TIPO_ID_PACIENTE = '" . $filtros['tipo_id_paciente'] . "' ";
            }
            if ($filtros['paciente_id']){
                $sql1 .= " AND   HF.PACIENTE_ID = '" . $filtros['paciente_id'] . "' ";
            }
//            if ($filtros['nombres']) {
//                $sql1 .= "AND   a.nombres ILIKE  '%" .$filtros['nombres']."%'";
//            }
//            if ($filtros['apellidos']) {
//               $sql1 .= "AND   a.apellidos ILIKE  '%" .$filtros['apellidos']."%'";               
//            }
            if ($filtros['formula']!= "") {
                $sql1 .= " AND   HF.NUMERO_FORMULA =  '" .$filtros['formula']."'";
            //    $entrar=false;
                $this->offset=0;
            }
        }
        $sql = " 
                 SELECT DISTINCT HP.CODIGO_MEDICAMENTO,
                    HF.NUMERO_FORMULA,
                    HP.EVOLUCION_ID,
                    (P.PRIMER_NOMBRE||' '||P.SEGUNDO_NOMBRE||' '||P.PRIMER_APELLIDO||' '||P.SEGUNDO_APELLIDO) AS NOMBREPACIENTES, 
                    P.TIPO_ID_PACIENTE,
                    P.PACIENTE_ID,
                    edad(P.FECHA_NACIMIENTO) AS EDAD,
                    (CASE WHEN P.SEXO_ID='F' THEN 'FEMENINO' WHEN P.SEXO_ID='M' THEN 'MASCULINO' END) AS SEXO,
                    P.RESIDENCIA_DIRECCION,
                    P.RESIDENCIA_TELEFONO,
                    HP.CODIGO_MEDICAMENTO,
                    FC_DESCRIPCION_PRODUCTO_ALTERNO(HP.CODIGO_MEDICAMENTO) AS DESCRIPCION_PROD,
                    HP.CANTIDAD,
                    HP.hc_pendiente_dispensacion_id
                 FROM
                    HC_PENDIENTES_POR_DISPENSAR AS HP
                    INNER JOIN HC_FORMULACION_ANTECEDENTES AS HF  ON (HF.EVOLUCION_ID=HP.EVOLUCION_ID AND HP.SW_ESTADO='0')
                    INNER JOIN PACIENTES P ON (P.TIPO_ID_PACIENTE=HF.TIPO_ID_PACIENTE AND P.PACIENTE_ID=HF.PACIENTE_ID)
                 WHERE 
                 
                    HP.SW_ESTADO='0' 
                ";
                       $sql.=$sql1;    
                       
                        if($entrar){
                        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (" . $sql . ") A", $offset)){          
                            return false;
                          }
                        }  
                    $this->limit = 20;
                    $whr .= " ORDER BY HF.NUMERO_FORMULA DESC ";
                    $whr .= " LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";


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

    /* INFORMACION DE LOS MEDICAMENTOS */

    function Medicamentos_Formulados_R($paciente) {

        $sql = "SELECT  hc.codigo_medicamento,
                hc.fecha_finalizacion,
                hc.dosis,
                hc.unidad_dosificacion,
                hc.frecuencia,
                hc.tiempo_total,
                hc.perioricidad_entrega,
                hc.descripcion,
                hc.tiempo_perioricidad_entrega,
                hc.unidad_perioricidad_entrega,
                hc.cantidad,
                a.cantidad as cantidad_entrega,
                hc.fecha_modificacion,
                pric.descripcion as principio_activo,
                fc_descripcion_producto_alterno(hc.codigo_medicamento) as descripcion_prod
                FROM hc_formulacion_antecedentes  hc LEFT JOIN medicamentos med ON (hc.codigo_medicamento=med.codigo_medicamento)
                LEFT JOIN inv_med_cod_principios_activos pric ON (med.cod_principio_activo=pric.cod_principio_activo)
                INNER JOIN hc_medicamentos_recetados_amb a ON hc.codigo_medicamento = a.codigo_producto AND hc.evolucion_id = a.evolucion_id
                WHERE hc.tipo_id_paciente = '{$paciente['0']['tipo_id_paciente']}' AND 
                hc.paciente_id ='{$paciente['0']['paciente_id']}' AND hc.evolucion_id ='{$paciente['0']['evolucion_id']}' --AND hc.sw_mostrar='1' 
                 ";
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
    /* INFORMACION DE LOS MEDICAMENTOS */

    function Productos_sin_existencia($evolucion) {

        $sql = "SELECT  hc_dispen_tmp_id,
                    evolucion_id,
                    a.codigo_producto,
                    a.lote,
                    a.fecha_vencimiento,
                    cantidad_despachada,
                    existencia_actual,
                    fc_descripcion_producto_alterno(a.codigo_producto) as descripcion_prod,
                    case when cantidad_despachada>=existencia_actual then '0' else '1' end as estado_existencia,c.descripcion
                FROM hc_dispensacion_medicamentos_tmp as a
                inner join existencias_bodegas_lote_fv as b on (a.empresa_id=b.empresa_id and 
                                                                a.centro_utilidad=b.centro_utilidad and 
                                                                a.bodega=b.bodega and 
                                                                a.codigo_producto=b.codigo_producto and 
                                                                a.lote=b.lote and 
                                                                a.fecha_vencimiento=b.fecha_vencimiento)
                inner join bodegas c on (a.empresa_id=c.empresa_id and 
                                         a.centro_utilidad=c.centro_utilidad and 
                                         a.bodega=c.bodega)
                WHERE evolucion_id ='$evolucion'  and 
                   sw_entregado_off='1' and cantidad_despachada>=existencia_actual
                   order by c.descripcion,evolucion_id,estado_existencia";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $var = $rst->GetRowAssoc($ToUpper = false);
            $datos[$var['codigo_producto']] = $rst->GetRowAssoc($ToUpper = false);
            $this->EliminarDatosTMP_DISPENSACION($var['evolucion_id'], $var['codigo_producto'], $var['hc_dispen_tmp_id']);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }
    
    function intervaloFechaformula($fecha,$dias,$operacion){
        $sql=" select to_char(fecha, 'yyyy-mm-dd')as fecha
                from 
              (SELECT CAST('$fecha' AS DATE) $operacion CAST('$dias days' AS INTERVAL) as fecha)as d;";
        
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        while (!$resultado->EOF) {
            $var = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        return $var;        

    } 
    
    //realiza operaci??n de sumar o restar meses a una fecha
    function operacion_meses($fecha,$mes,$operacion){
        $sql=" select to_char(fecha, 'yyyy-mm-dd')as fecha
                from 
              (SELECT CAST('$fecha' AS DATE) $operacion CAST('$mes month' AS INTERVAL) as fecha)as d;";
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];

        while (!$resultado->EOF) {
            $var = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        return $var;        
    } 

    /* PRODUCTOS QUE HAN SIDO REFORMULADOS */

    function Consultar_Medicamentos_Reformulados($tipo_id_paciente,$paciente_id,$numero_formula,$codigo_producto,$numero_entrega) {
        
        $sql = "
                SELECT medicamentos_refrendados_id, tipo_id_paciente, paciente_id, numero_formula, 
                    transcripcion_medica, numero_entrega, codigo_medicamento, fecha_refrendacion, 
                    fecha_finalizacion
                FROM medicamentos_refrendados
                WHERE tipo_id_paciente = '{$tipo_id_paciente}' AND  
                      paciente_id = '{$paciente_id}' AND 
                      numero_formula = '{$numero_formula}' AND
                      codigo_medicamento = '{$codigo_producto}' AND
                      numero_entrega = '{$numero_entrega}';";

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
	
	/**
	*	@author Cristian Ardila
	*	@fecha  19/04/2016
	*	+Descripcion: Funcion encargada de consultar el numero de veces en que se ha
	*				  Dispensado una formula
	**/
	
	function ConsultarNumeroEntregaFormula($evolucion_id) {
        
	 $sql = "SELECT b.*, c.numero_entregas
					FROM (
                        SELECT a.evolucion_id,1 from hc_formulacion_despachos_medicamentos a
                        union 
                        select a.evolucion_id,2 from hc_formulacion_despachos_medicamentos_pendientes a
                        union
                        select a.evolucion_id,3 from hc_dispensacion_medicamentos_tmp a                       
                    ) as b INNER JOIN (
							SELECT count(evolucion_id) as numero_entregas, evolucion_id
							FROM hc_formulacion_despachos_medicamentos WHERE evolucion_id = {$evolucion_id} GROUP BY evolucion_id
                    ) c ON b.evolucion_id = c.evolucion_id            
                    WHERE b.evolucion_id={$evolucion_id} ";  

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
	/**
	*	@author Cristian Ardila
	*	@fecha  25/04/2016
	*	+Descripcion Funcion encargada de consultar la formula refrendada segun el
	*	             tipo de paciente, identificacion, numero de formula, tipo de formula y el
	*				 numero de formula
	**/
	function ConsultarFormulaRefrendada($dtl,$cantidadEntregaFormula) {
        $cantidadEntregaFormula['numero_entregas'] = $cantidadEntregaFormula['numero_entregas']+1;
        $sql = "SELECT medicamentos_refrendados_id, tipo_id_paciente, paciente_id, numero_formula, 
                    transcripcion_medica, numero_entrega, codigo_medicamento, fecha_refrendacion, 
                    fecha_finalizacion
                FROM medicamentos_refrendados
                WHERE tipo_id_paciente = '{$dtl['tipo_id_paciente']}' AND  
                      paciente_id = '{$dtl['paciente_id']}' AND 
                      numero_formula = '{$dtl['numero_formula']}' AND
					  transcripcion_medica = '{$dtl['transcripcion_medica']}' AND 
                      numero_entrega = '{$cantidadEntregaFormula['numero_entregas']}';";
		
		//echo "sql ". $sql;
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
	
	
	/**
	*@author Cristian Ardila
	*@fecha  28/04/2016 19:04
	*+Descripcion: Metodo el cual consultara la ultima fecha de refrendacion
	* a trvaes del numero de formula y el tipo de formula
	**/
	function consultarUltimaFechaRefrendacion($dtl) {
       
        $sql = "SELECT  max(fecha_refrendacion) as fecha_refrendacion
                FROM medicamentos_refrendados
                WHERE 
                      numero_formula = '{$dtl['numero_formula']}' AND
					  transcripcion_medica = '{$dtl['transcripcion_medica']}';";
		
		//echo "sql ". $sql;
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
	
	/**
	*@author Cristian Ardila
	*@fecha  28/04/2016
	*+Descripcion: Metodo encargado de consultar todos los medicamentos dispensados
	*			   hasta el momento de una formula a traves de su numero de evolucion
	**/
	function medicamentosDespachados($evolucion) {

        $sql = " select
                                dd.codigo_producto,
                                dd.cantidad as numero_unidades,
                                dd.fecha_vencimiento ,
                                dd.lote,
                                fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,

                                d.usuario_id,
                           
                                'dispensacion_hc' as sistema, 
                                to_char(d.fecha_registro,'YYYY-mm-dd') as fecha_entrega,
                                to_char(now()- d.fecha_registro,'dd') as dias_de_entregado
                                FROM
                                  hc_formulacion_despachos_medicamentos as dc,

                                  bodegas_documentos as d,
                                  bodegas_documentos_d AS dd
                                  
                                WHERE
                                     dc.bodegas_doc_id = d.bodegas_doc_id
                                and        dc.numeracion = d.numeracion


                                and        dc.evolucion_id = ".$evolucion."

                                and        d.bodegas_doc_id = dd.bodegas_doc_id

                                and        d.numeracion = dd.numeracion
                               

                  
                           order by fecha_entrega asc; 
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
	*@author Cristian Ardila
	*@fecha  28/04/2016
	*+Descripcion Metodo encargado de actualizar el estado de un producto pendiente por dispensar
	*			  a estado de entregado que es 1
	*
	**/
	function actualizarPendientesPorDespachados($evolucion) {
	
        $sql = " UPDATE hc_pendientes_por_dispensar SET sw_estado = '1'
                        WHERE evolucion_id  = ".$evolucion.";
                   ";



       if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }
	
	
	
	
	
	
	
	
	
    /* PRODUCTOS QUE HAN SIDO REFORMULADOS */


    function Consultar_Fecha_Finalizacion_Reformulados($tipo_id_paciente,$paciente_id,$numero_formula) {
        
        $sql = "
                SELECT max(a.fecha_refrendacion) as fecha_refrendacion
                FROM medicamentos_refrendados as a
                INNER JOIN hc_formulacion_antecedentes as b on 
                           (a.tipo_id_paciente=b.tipo_id_paciente and 
                            a.paciente_id=b.paciente_id and 
                            a.numero_formula=b.numero_formula and 
                            a.codigo_medicamento=b.codigo_medicamento)
                WHERE a.tipo_id_paciente = '{$tipo_id_paciente}' AND  
                      a.paciente_id = '{$paciente_id}' AND 
                      a.numero_formula = '{$numero_formula}' AND
                      a.numero_entrega = '1'
                      limit 1;";
//        echo "<pre>".$sql;          
        if (!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];
        $datos = Array();
        while (!$resultado->EOF) {
            $datos = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        $resultado->Close();
        return $datos['fecha_refrendacion'];
    }
    
    /* PRODUCTOS QUE HAN SIDO FORMULADOS */

    function Consultar_Medicamentos_Detalle_($Formulario, $evolucion) {

        /*if ($Formulario['codigo_barras'] != "") {
            $filtro = " and invp.codigo_barras = '" . $Formulario['codigo_barras'] . "' ";
        }*/
        
        
		//(ceiling(ceiling(hc.fecha_finalizacion - hc.fecha_registro)/30))  as numero_entregas,
	
        $sql = "SELECT  hc.codigo_medicamento as codigo_producto,
                   ceiling(ceiling(hc.fecha_finalizacion - hc.fecha_registro)/30)  as numero_entregas,
               (hc.fecha_finalizacion - hc.fecha_registro)  as diferencia_final_inicio,
                hc.fecha_registro,
                hc.fecha_finalizacion,
                hc.dosis,
                hc.unidad_dosificacion,
                hc.frecuencia,
                hc.tiempo_total,
                hc.perioricidad_entrega,
                hc.descripcion,
                hc.tiempo_perioricidad_entrega,
                hc.unidad_perioricidad_entrega,
                hc.cantidad,       
                a.cantidad as  cantidad_entrega,
                hc.fecha_modificacion,
                pric.descripcion as principio_activo,
                pric.cod_principio_activo,
                fc_descripcion_producto_alterno(hc.codigo_medicamento) as descripcion_prod,
                hc.sw_autorizado,
                hc.tipo_id_paciente,
                hc.paciente_id,
                TO_CHAR(hc.fecha_formulacion,'YYYY-MM-DD') AS fecha_formulacion,
                refrendar,
                hc.numero_formula
                FROM   hc_formulacion_antecedentes hc
                LEFT JOIN  medicamentos med ON(hc.codigo_medicamento=med.codigo_medicamento)
                LEFT JOIN inv_med_cod_principios_activos pric ON (med.cod_principio_activo=pric.cod_principio_activo)
                LEFT JOIN  inventarios_productos invp ON(hc.codigo_medicamento=invp.codigo_producto)
                JOIN hc_medicamentos_recetados_amb a ON hc.codigo_medicamento = a.codigo_producto AND hc.evolucion_id = a.evolucion_id
                WHERE    hc.evolucion_id='{$evolucion}' AND invp.descripcion ILIKE '%{$Formulario['descripcion']}%'  {$filtro} ";
// echo "<pre>".$sql;
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

    /* CONSULTAR  TMP */

    function Cantidad_ProductoTemporal($doc_tmp_id, $principio_activo, $codigo_producto) {

        $sql = "    SELECT COALESCE(sum(tmp.cantidad_despachada),0) as total,tmp.codigo_formulado
            from   hc_dispensacion_medicamentos_tmp tmp
                   LEFT JOIN medicamentos med ON(tmp.codigo_formulado=med.codigo_medicamento)
                 LEFT JOIN inventarios_productos invp ON(tmp.codigo_formulado=invp.codigo_producto)
            where  tmp.codigo_formulado='" . $codigo_producto . "'
            and    tmp.evolucion_id = " . $doc_tmp_id . " ";

        if ($principio_activo != "") {

            $sql .="   and    med.cod_principio_activo = '" . $principio_activo . "'  ";
        } else {


            $sql .="   and    invp.codigo_producto = '" . $codigo_producto . "'  ";
        }
        $sql .= "  GROUP BY   codigo_formulado ";

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

    /* EXISTENCIAS EN BODEGA */

    function Consultar_ExistenciasBodegas($principio_activo, $Formulario, $farmacia, $centrou, $bodega, $producto, $unidad_medida_medicamento_id, $concentracion_forma_farmacologica) {

        if ($Formulario['lote'] != "") {
            $filtro = " and fv.lote = '" . $Formulario['lote'] . "' ";
        }

        if ($Formulario['codigo_barras'] != "") {
            $filtro = " and invp.codigo_barras = '{$Formulario['codigo_barras']}' ";
        }
        
        $sql = "    SELECT
                    fc_descripcion_producto_alterno(fv.codigo_producto) as producto,
                    fv.*
                    FROM existencias_bodegas_lote_fv AS fv
                    JOIN existencias_bodegas as ext ON (fv.empresa_id = ext.empresa_id) and (fv.centro_utilidad = ext.centro_utilidad) and (fv.bodega = ext.bodega) and (fv.codigo_producto = ext.codigo_producto)
                    JOIN inventarios as inv ON (ext.empresa_id = inv.empresa_id) and (ext.codigo_producto = inv.codigo_producto)
                    JOIN inventarios_productos as invp ON (inv.codigo_producto = invp.codigo_producto)
                    LEFT JOIN medicamentos med ON (fv.codigo_producto=med.codigo_medicamento)
                    where fv.empresa_id = '" . trim($Formulario['empresa_id']) . "' and fv.centro_utilidad = '" . trim($Formulario['centro_utilidad']) . "'
                    and fv.bodega = '" . trim($Formulario['bodega']) . "' and fv.existencia_actual > 0 $filtro   ";

        if ($principio_activo != "") {
            $sql .="   and    med.cod_principio_activo = '" . trim($principio_activo) . "'  ";
        } else {
            $sql .="   and    fv.codigo_producto = '" . trim($producto) . "'  ";
        }

        $sql .="   ORDER BY invp.descripcion ASC,fv.fecha_vencimiento ASC  ";

        /* echo "<pre>";
          print_r($sql);
          echo "</pre>";
          exit(); */

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

    /* BUSCAR INFORMACION DEL PRODUCTO POR LOTE EN EL TMP */

    function Buscar_ProductoLote($doc_tmp_id, $codigo_producto, $lote, $codigo_productoD) {


        $sql = "SELECT * ";
        $sql .= "FROM   hc_dispensacion_medicamentos_tmp ";
        $sql .= "WHERE  evolucion_id = " . $doc_tmp_id . " ";
        $sql .= "and    codigo_producto = '" . $codigo_productoD . "' ";
        $sql .= "and    lote = '" . $lote . "'  and codigo_formulado= '" . $codigo_producto . "' ";

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

    /* GUARDAR TMP */

    function GuardarTemporal($evolucion, $codigo_producto, $cantidad, $fecha_venc, $lotec, $empresa, $bodega, $formulado, $f_rango = '0') {

        $this->ConexionTransaccion();


        $sql = "INSERT INTO hc_dispensacion_medicamentos_tmp
            (
                    hc_dispen_tmp_id,
                    evolucion_id,
                    empresa_id,
                    centro_utilidad,
                    bodega,
                    codigo_producto,
                    cantidad_despachada,
                    fecha_vencimiento,
                    lote,
                    codigo_formulado,
                    usuario_id,
                    sw_entregado_off
            )VALUES
            (       DEFAULT,
                    " . $evolucion . ",
                    '" . $empresa['empresa_id'] . "',
                    '" . $empresa['centro_utilidad'] . "',
                    '" . $bodega . "',
                    '" . $codigo_producto . "',
                    " . $cantidad . ",
                    '" . $fecha_venc . "',
                    '" . $lotec . "',
                    '" . $formulado . "',
                     " . UserGetUID() . ",
                     " . $f_rango . "
            ); ";

        /* echo "<pre>";
          print_r($sql);
          echo "</pre>";
          exit(); */

        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }

    function buscarProductoTemporal($evolucion, $codigo, $fechav, $lote) {

        $sql = "SELECT  * FROM     hc_dispensacion_medicamentos_tmp
            
                    where evolucion_id = {$evolucion} and codigo_producto= '{$codigo}' and fecha_vencimiento = '{$fechav}' and lote = '{$lote}'";

        /* echo "<pre>";
          print_r($sql);
          echo "</pre>";
          //exit(); */


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

    /* CONSULTAR PRODUCTOS TMP */

    function Buscar_producto_tmp_c($evolucion) {

        $sql = " SELECT  hc_dispen_tmp_id,
                evolucion_id,
            empresa_id,
            centro_utilidad,
            bodega,
            codigo_producto,
            cantidad_despachada,
            fecha_vencimiento,
            lote,
            fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod

        FROM    hc_dispensacion_medicamentos_tmp
        WHERE   evolucion_id = '" . $evolucion . "' ";
      //  echo "<pre>".$sql;
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

    /* ELIMINAR PRODUCTOS */

    function EliminarDatosTMP_DISPENSACION($evolucion, $codigo_producto, $seria_id) {

        $this->ConexionTransaccion();
        $sql = " DELETE FROM  hc_dispensacion_medicamentos_tmp
            WHERE   hc_dispen_tmp_id ='" . $seria_id . "'
            AND     evolucion_id='" . $evolucion . "'
            AND     codigo_producto = '" . $codigo_producto . "' ";
        if (!$rst = $this->ConexionTransaccion($sql))
            return false;
        $this->Commit();
        return true;
    }

    /* CONSULTAR INFORMACION DE LA CABECERA DE LA FORMULA */

    function ObtenerFormulasCabecera($evolucion, $filtros,$refrenda) {

        $sql = " SELECT  DISTINCT HF.tipo_id_paciente,
              HF.paciente_id,
              TO_CHAR(HF.fecha_registro,'YYYY-MM-DD') AS fecha_registro,
              TO_CHAR(HF.fecha_finalizacion,'YYYY-MM-DD') AS fecha_finalizacion,
              TO_CHAR(HF.fecha_formulacion,'YYYY-MM-DD') AS fecha_formulacion,
              PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos,
              PA.primer_nombre||' '||PA.segundo_nombre AS nombres,
              SU.nombre,
              HF.evolucion_id,
              BL.tipo_bloqueo_id,
              BL.descripcion AS bloqueo,
              PLA.plan_descripcion,
              edad(PA.fecha_nacimiento) as edad,
              PA.sexo_id,
              PLA.plan_id


              FROM    hc_formulacion_antecedentes HF,
                      pacientes PA LEFT JOIN eps_afiliados EPS ON (EPS.afiliado_tipo_id=PA.tipo_id_paciente AND EPS.afiliado_id=PA.paciente_id),
                      system_usuarios SU,
            inv_tipos_bloqueos BL,
            planes_rangos PR,
            planes    PLA
              WHERE   HF.sw_formulado='1' ";
        if(!($refrenda=='1')){
          $sql .= "    AND     HF.fecha_finalizacion >= '" . $filtros['fecha'] . "' ";
            }          
          $sql .= "      AND     HF.sw_mostrar='1'
              AND     HF.tipo_id_paciente = PA.tipo_id_paciente
              AND     HF.paciente_id = PA.paciente_id
        AND     PA.tipo_bloqueo_id=BL.tipo_bloqueo_id
        AND     BL.estado='1'
              AND     SU.usuario_id = HF.medico_id
        AND     EPS.plan_atencion=PR.plan_id
        AND     EPS.tipo_afiliado_atencion=PR.tipo_afiliado_id
        AND     EPS.rango_afiliado_atencion=PR.rango
        AND     PR.plan_id=PLA.plan_id
        AND     HF.evolucion_id='" . $evolucion . "' ;";


//echo "<pre>".$sql;
        if (!$rst = $this->ConexionBaseDatos($sql, null))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    /* MEDICAMENTOS PENDIENTES */

     function Medicamentos_Pendientes($evolucion) {
	
        $sql = " select codigo_medicamento,fecha_registro,
                 SUM(numero_unidades) as total,
                 fc_descripcion_producto_alterno(codigo_medicamento) as descripcion_prod
                 from (
                        select                        
                        dc.codigo_medicamento,
                        dc.fecha_registro,    
                        SUM(dc.cantidad) as numero_unidades  
                        FROM hc_pendientes_por_dispensar as dc
                        WHERE dc.evolucion_id = {$evolucion} and dc.sw_estado = '0'
                        group by dc.codigo_medicamento,dc.fecha_registro
                  ) as A group by codigo_medicamento,fecha_registro
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

    /*  PENDIENTES */

    function Consultar_Medicamentos_Detalle_P($Formulario, $evolucion) {


        /*if ($Formulario['codigo_barras'] != "") {
            $filtro = " and A.codigo_barras = '" . $Formulario['codigo_barras'] . "' ";
        }*/

        //if($Formulario['descripcion']!="" || $Formulario['codigo_barras']!="")
        //  {

        /*
          CASE WHEN (d.fecha_registro + '".$horas."'::interval) >= (now())
          THEN '0' ELSE '1' END as f_rango
         */
        $horas = ModuloGetVar("", "", "ESM_TiempoMaxEntregaPendientes");


        $sql = "SELECT         tmp.codigo_medicamento as codigo_producto,
                    SUM(tmp.cantidad) as cantidad,
                    fc_descripcion_producto_alterno(tmp.codigo_medicamento) as descripcion_prod,

                    MED.cod_principio_activo,
                    CASE WHEN (MAX (tmp.fecha_registro+ '" . $horas . "hr'::interval) >= (now()))
                       THEN '0' ELSE '1' END as f_rango



           FROM   hc_pendientes_por_dispensar tmp,
              inventarios_productos A left join medicamentos MED ON (A.codigo_producto=MED.codigo_medicamento)
 



           WHERE    tmp.evolucion_id='" . $evolucion . "'
           AND    tmp.codigo_medicamento= A.codigo_producto and   tmp.sw_estado='0'

           AND     A.descripcion ILIKE '%" . $Formulario['descripcion'] . "%'
            " . $filtro;

        $sql .= " group by  tmp.codigo_medicamento,MED.cod_principio_activo ";


        /* echo "<pre>";
          print_r($sql);
          echo "</pre>";
          exit(); */


        //}
        // print_r($sql);
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

    function ObtenerFormulasCabecera_por_evolucion($evolucion,$tipo_id_paciente,$paciente_id) {

        $sql = " SELECT  DISTINCT HF.tipo_id_paciente,
                HF.paciente_id,
                TO_CHAR(HF.fecha_registro,'YYYY-MM-DD') AS fecha_registro,
                TO_CHAR(HF.fecha_finalizacion,'YYYY-MM-DD') AS fecha_finalizacion,
                TO_CHAR(HF.fecha_formulacion,'YYYY-MM-DD') AS fecha_formulacion,
                PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos,
                PA.primer_nombre||' '||PA.segundo_nombre AS nombres,
                SU.nombre,
                HF.evolucion_id,
                BL.tipo_bloqueo_id,
                BL.descripcion AS bloqueo,
                PLA.plan_descripcion,
                edad(PA.fecha_nacimiento) as edad,
                PA.sexo_id,
                PLA.plan_id,
                PA.residencia_direccion,
                PA.residencia_telefono,
                HF.numero_formula
                FROM    hc_formulacion_antecedentes HF,
                pacientes PA LEFT JOIN eps_afiliados EPS ON (EPS.afiliado_tipo_id=PA.tipo_id_paciente AND EPS.afiliado_id=PA.paciente_id),
                system_usuarios SU,
                inv_tipos_bloqueos BL,
                planes_rangos PR,
                planes    PLA
                WHERE   HF.sw_formulado='1'
                --AND     HF.sw_mostrar='1'
                AND     HF.tipo_id_paciente = PA.tipo_id_paciente
                AND     HF.paciente_id = PA.paciente_id
                AND     PA.tipo_bloqueo_id=BL.tipo_bloqueo_id
                AND     BL.estado='1'
                AND     SU.usuario_id = HF.medico_id
                AND     EPS.plan_atencion=PR.plan_id
                AND     EPS.tipo_afiliado_atencion=PR.tipo_afiliado_id
                AND     EPS.rango_afiliado_atencion=PR.rango
                AND     PR.plan_id=PLA.plan_id
                AND     HF.evolucion_id='" . $evolucion . "' ";
        if($paciente_id!=''){
        $where=" and a.tipo_id_paciente='{$tipo_id_paciente}'
                 and a.paciente_id='{$paciente_id}' ";
        }
        $sql = "select distinct  ON (a.evolucion_id)
                a.evolucion_id,
                a.numero_formula,
                a.tipo_id_paciente,
                a.paciente_id,
                to_char(a.fecha_registro,'YYYY-MM-DD') as fecha_registro,
                to_char(a.fecha_finalizacion,'YYYY-MM-DD') as fecha_finalizacion,
                to_char(a.fecha_formulacion,'YYYY-MM-DD') as fecha_formulacion,
                b.primer_apellido ||' '|| b.segundo_apellido AS apellidos,
                b.primer_nombre||' '||b.segundo_nombre AS nombres,
                edad(b.fecha_nacimiento) as edad,
                b.sexo_id,
                b.residencia_direccion,
                b.residencia_telefono,
                e.plan_id,
                e.plan_descripcion,
                f.nombre,
                g.tipo_bloqueo_id,
                g.descripcion AS bloqueo,
                h.tipo_formula,
                i.descripcion_tipo_formula
                from hc_formulacion_antecedentes a
                inner join hc_evoluciones h on a.evolucion_id = h.evolucion_id
                inner join pacientes b on a.tipo_id_paciente = b.tipo_id_paciente and a.paciente_id = b.paciente_id
                left join  eps_afiliados c on b.tipo_id_paciente = c.afiliado_tipo_id AND b.paciente_id = c.afiliado_id
                inner join planes_rangos d on c.plan_atencion = d.plan_id and c.tipo_afiliado_atencion = d.tipo_afiliado_id and c.rango_afiliado_atencion = d.rango
                inner join planes e on d.plan_id = e.plan_id
                inner join system_usuarios f on a.medico_id = f.usuario_id
                inner join inv_tipos_bloqueos g on b.tipo_bloqueo_id = g.tipo_bloqueo_id
                left join esm_tipos_formulas i on h.tipo_formula = i.tipo_formula_id
                where 
                    a.evolucion_id='{$evolucion}'  
                    $where
                    and a.sw_formulado='1' 
                    and g.estado='1' ; ";
                    
//        echo "<pre>"; 
//          print_r($sql);
//          echo "</pre>";
//          exit(); 
                    

        if (!$rst = $this->ConexionBaseDatos($sql, null))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }
    
    function Formula_Refrendada($evolucion){
        $sql="Select 
               refrendar
              FROM   hc_formulacion_antecedentes
              WHERE evolucion_id='{$evolucion}'
              limit 1;";
              
        if (!$rst = $this->ConexionBaseDatos($sql, null))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos['refrendar'];
    }

    function Medicamentos_Pendientes_($evolucion) {



        $sql = " select codigo_medicamento,
                    SUM(numero_unidades) as total,
                  fc_descripcion_producto_alterno(codigo_medicamento) as descripcion_prod

                    from
                        (
                        select
                        dc.codigo_medicamento,
                        SUM(dc.cantidad) as numero_unidades

                        FROM  hc_pendientes_por_dispensar as dc
                   WHERE      dc.evolucion_id = " . $evolucion . "
                   and        dc.sw_estado = '0'
                  group by(dc.codigo_medicamento)
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

    /*
     * Funcion donde se ingresa inicialmente los datos de los medicamentos a despachar e una tabla temporal
     * @return boolean.
     */

    function Medicamento_Farmacia_tmp($tipo_id_paciente, $paciente_id, $cantidad_entrega, $codigo_medicamento_forumulado, $dosis, $fecha_finalizacion, $fecha_formulacion, $fecha_proxima_entrega, $evolucion_id, $tiempo_perioricidad, $unidad_perioricidad) {

        $this->ConexionTransaccion();

        if ($fecha_proxima_entrega == "") {
            $cade = $codigo_medicamento_forumulado . " " . $paciente_id;
            $fecha_proxima_entrega = $fecha_formulacion;
        }
        else
            $cade = $codigo_medicamento_forumulado . " " . $paciente_id;
        $sql = "INSERT INTO medicamento_farmacia_tmp( ";
        $sql .= "       medicafarma_id,
                  tipo_id_paciente,
                  paciente_id,
                  evolucion_id,
                  cantidad_entrega,
                  tiempo_perioricidad_entrega,
                  unidad_perioricidad_entrega,
                  codigo_medicamento_formulado,
                  dosis,
                  fecha_finalizacion,
                  fecha_formulacion,
                  fecha_proxima_entrega
            )VALUES(
                        '" . $cade . "',
                        '" . $tipo_id_paciente . "',
                        '" . $paciente_id . "' ,
                        " . $evolucion_id . ",
                        " . $cantidad_entrega . ",
                        " . $tiempo_perioricidad . ",
                        '" . $unidad_perioricidad . "' ,
                        '" . $codigo_medicamento_forumulado . "',
                        " . $dosis . ",
                        '" . $fecha_finalizacion . "',
                        '" . $fecha_formulacion . "',
                        '" . $fecha_proxima_entrega . "'
                        ";
        $sql .= "       ); ";

        if (!$rst = $this->ConexionTransaccion($sql)) {
            return false;
        }
        $this->Commit();
        return true;
    }

    /*
     * Funcion donde   se Eliminan los datos de la tabla temporal.
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function EliminarDatosFormula($tipo_id_paciente, $paciente_id, $codigo_medicamento_forumulado, $evolucion_id) {
        $sql = " DELETE FROM  medicamento_farmacia_tmp
           WHERE   tipo_id_paciente ='" . $tipo_id_paciente . "'
           AND     paciente_id='" . $paciente_id . "'
           AND    codigo_medicamento_forumulado = '" . $codigo_medicamento_forumulado . "'
           and    evolucion_id=" . $evolucion_id . " ";


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
     * Funcion donde se Consultan la informacion d e la tabla temporal.
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarInformacion($tipo_id_paciente, $paciente_id, $evolucion_id) {

        $sql = " SELECT *
          FROM      medicamento_farmacia_tmp
          WHERE     tipo_id_paciente='" . $tipo_id_paciente . "'
          AND       paciente_id='" . $paciente_id . "'
          AND         evolucion_id=" . $evolucion_id . " ";

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
     * Funcion donde se Consultan los productos que se pueden despachar de acuerdo al medicamento formulado
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarInforma($empresa, $centro, $bodega, $molecula) {

        $sql = " SELECT      INVP.codigo_producto,
                      INVP.descripcion as nombre_medicamento,
                      INVP.contenido_unidad_venta,
                      INVP.sw_generico,
                      clas.descripcion as laboratorio,
                      INV.costo,
                      u.descripcion,
                      TO_CHAR(LF.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento,
                      LF.lote,
                      LF.existencia_actual As cantidad,
                      M.cod_concentracion

            FROM        inventarios_productos INVP,
                inv_clases_inventarios clas,
                inv_subclases_inventarios s,
                inventarios INV,
                existencias_bodegas EB,
                unidades u,
                existencias_bodegas_lote_fv LF,
                medicamentos M
        WHERE   INVP.subclase_id = '" . $molecula . "'
        AND   INVP.clase_id = s.clase_id
        AND   M.codigo_medicamento = INV.codigo_producto
        AND   INVP.subclase_id = s.subclase_id
        AND   INVP.grupo_id=s.grupo_id
        AND   s.clase_id=clas.clase_id
        AND   s.grupo_id=clas.grupo_id
        AND   INV.codigo_producto=INVP.codigo_producto
        AND   EB.codigo_producto=INV.codigo_producto
        AND   EB.empresa_id=INV.empresa_id
        AND   INVP.unidad_id=u.unidad_id
        AND   EB.empresa_id = LF.empresa_id
        AND   EB.centro_utilidad = LF.centro_utilidad
        AND   EB.bodega = LF.bodega
        AND   LF.empresa_id='" . $empresa . "'
        AND   LF.centro_utilidad='" . $centro . "'
        AND   LF.bodega='" . $bodega . "'
        AND   EB.codigo_producto=LF.codigo_producto
        AND   LF.existencia_actual > 0
        ORDER BY LF.fecha_vencimiento ";

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
     * Funcion donde se Consultan el factor de conversion de acuerdo al medicamento.
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarFactorConversion($medicamento) {

        $sql = "  SELECT  HF.codigo_producto,
                              HF.unidad_id,
                              HF.unidad_dosificacion,
                              HF.factor_conversion
                        FROM  hc_formulacion_factor_conversion HF,
                              unidades UN
                        WHERE HF.codigo_producto='" . $medicamento . "'
                        AND   HF.unidad_id = UN.unidad_id;";

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
     * Funcion donde se Consultan los datos del paciente.
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    Function DatosPaciente($tipo_id_paciente, $paciente_id) {

        $sql = " SELECT   paciente_id,
              tipo_id_paciente,
              primer_apellido,
              segundo_apellido,
              primer_nombre,
              segundo_nombre,
              sexo_id,
              residencia_direccion,
              residencia_telefono,
              to_char(fecha_nacimiento,'dd-mm-yyyy') as fecha_nacimiento,
              edad(fecha_nacimiento) as edad,
              primer_apellido ||' '||segundo_apellido AS apellidos,
              primer_nombre||' '||segundo_nombre AS nombres
         FROM     pacientes
         WHERE   paciente_id = '" . $paciente_id . "' AND tipo_id_paciente = '" . $tipo_id_paciente . "';
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
     * Funcion donde se Consultan el ultimo registro que se ingreso a la tabla  hc_formulacion_despachos_medicamentos
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarUltimoResg($tipo_id_paciente, $paciente_id, $codigo_medicamento, $evolucion) {

        $sql = "SELECT (COALESCE(MAX(hc_formuladesp_medicamentos_id),0)) AS maxi  FROM hc_formulacion_despachos_medicamentos
              where     tipo_id_paciente= '" . $tipo_id_paciente . "'
               AND      paciente_id = '" . $paciente_id . "'
               AND     codigo_medicamento='" . $codigo_medicamento . "'
         AND      evolucion_id='" . $evolucion . "'
        AND        sw_estado='0' ";
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
     * Funcion donde se Consultan el ultimo registro que se ingreso a la tabla  hc_formulacion_despachos_medicamentos pero que no se le despacho
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultarUltimoResgNoDespachado($tipo_id_paciente, $paciente_id, $codigo_medicamento, $evolucion) {

        $sql = "SELECT (COALESCE(MAX(hc_formuladesp_medicamentos_id),0)) AS maxi  FROM hc_formulacion_despachos_medicamentos
              where     tipo_id_paciente= '" . $tipo_id_paciente . "'
               AND      paciente_id = '" . $paciente_id . "'
               AND     codigo_medicamento='" . $codigo_medicamento . "'
         AND      evolucion_id='" . $evolucion . "'
         AND      sw_estado='1' ";
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
     * Funcion donde se consultan los datos de la persona logueada
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function GetNombreUsuarioImprime() {
        $sql = "  SELECT nombre,descripcion
          FROM system_usuarios
          WHERE usuario_id=" . UserGetUID() . " ";

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

    /*     * Funcion donde se consultas los planes asociados a la Farmacia
     * @return array $datos vector que contiene la informacion de la consulta.
     */

    function ConsultaPlanes_Bodega($farmacia) {

        $sql = "   SELECT a.plan_id,
                        p.plan_descripcion
                 FROM   bodegas_farmacia_asoc_formulas a,
                  planes p
           WHERE  p.plan_id=a.plan_id
           and    farmacia_id = '" . $farmacia . "'
           order by  p.plan_descripcion  ";
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

    /**/

    Function Consultar_DatosA_Paciente($request) {

        $sql = "SELECT  to_char(fecha_nacimiento,'dd-mm-yyyy') as fecha_nacimiento,
            residencia_direccion,
            residencia_telefono,
            sexo_id,
            edad(fecha_nacimiento) as edad
        FROM  pacientes
        WHERE   paciente_id = '" . $request['paciente_id'] . "'
        AND     tipo_id_paciente = '" . $request['tipo_id_paciente'] . "' ";
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

    /*
     * Funcion donde se ingresar el evento
     * @return boolean
     */

    function Registrar_Evento($datos, $request, $informacion) {

        $this->ConexionTransaccion();

        $sql = " update hc_despacho_medicamentos_eventos ";
        $sql .= " set   sw_estado='0'  ";
        $sql .= " where paciente_id='" . $datos['paciente_id'] . "' ";
        $sql .= " and   tipo_id_paciente='" . $datos['tipo_id_paciente'] . "' ";
        $sql .= " and   evolucion_id =" . $datos['evolucion_id'] . "   ";
        $sql .= " and   sw_estado ='1' ;  ";


        $sql .= " INSERT INTO hc_despacho_medicamentos_eventos(";
        $sql .= "            hc_despacho_evento, ";
        $sql .= "      paciente_id, ";
        $sql .= "      tipo_id_paciente, ";
        $sql .= "      evolucion_id, ";
        $sql .= "      observacion, ";
        $sql .= "            fecha_evento, ";
        $sql .= "      Fecha_Registro, ";
        $sql .= "            Usuario_id ";
        $sql .= "            )VALUES(";
        $sql .= "      nextval('hc_despacho_medicamentos_eventos_hc_despacho_evento_seq'), ";
        $sql .= "      '" . $datos['paciente_id'] . "', ";
        $sql .= "      '" . $datos['tipo_id_paciente'] . "', ";
        $sql .= "             " . $datos['evolucion_id'] . ", ";
        $sql .= "             '" . $request['observar'] . "', ";
        $sql .= "             '" . $request['fecha_inicio'] . "', ";
        $sql .= "             now(),  ";
        $sql .= "               " . UserGetUID() . " ";
        $sql .= "          ) RETURNING hc_despacho_evento;   ";

        if (!$rst = $this->ConexionTransaccion($sql))
            return false;
        $info = Array();
        while (!$rst->EOF) {
            $info = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $sql = " ";


        foreach ($informacion as $indice => $valor) {
            $sql .= "  insert into hc_despacho_medicamentos_eventos_d(   ";
            $sql .= "            hc_despacho_evento_d, ";
            $sql .= "      hc_despacho_evento, ";
            $sql .= "      codigo_medicamento, ";
            $sql .= "      cantidad ";
            $sql .= "            )VALUES(";
            $sql .= "      default, ";
            $sql .= "      " . $info['hc_despacho_evento'] . ", ";
            $sql .= "      '" . $valor['codigo_medicamento'] . "', ";
            $sql .= "             " . $valor['total'] . " ";
            $sql .= "             );  ";
        }


        if (!$rst = $this->ConexionTransaccion($sql))
            return false;


        $this->Commit();
        return true;
    }

    /**/

    Function ConsultarEventoActivo($pacienteid, $tipopaciente, $evolucion) {

        $sql = "   SELECT  EV.hc_despacho_evento,
            EV.evolucion_id,
            EV.observacion,
            to_char(EV.fecha_evento,'dd-mm-yyyy') as fecha_evento,
            PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos,
            PA.primer_nombre||' '||PA.segundo_nombre AS nombres,
            SU.nombre,
            EV.paciente_id,
            EV.tipo_id_paciente

        FROM    hc_despacho_medicamentos_eventos EV,
                  pacientes PA,
            system_usuarios SU
        WHERE   EV.sw_estado= '1'
        AND     SU.usuario_id = EV.usuario_id
        AND     EV.tipo_id_paciente = PA.tipo_id_paciente
        AND     EV.paciente_id = PA.paciente_id
        AND     EV.paciente_id='" . $pacienteid . "'
        AND     EV.tipo_id_paciente='" . $tipopaciente . "'
                AND     EV.evolucion_id='" . $evolucion . "'      ";

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

    /**/

    Function CerrarEventoPaciente($datos) {

        $this->ConexionTransaccion();

        $sql = " update hc_despacho_medicamentos_eventos ";
        $sql .= " set   sw_estado='0'  ";
        $sql .= " where paciente_id='" . $datos['paciente_id'] . "' ";
        $sql .= " and   tipo_id_paciente='" . $datos['tipo_id_paciente'] . "' ";
        $sql .= " and   evolucion_id =" . $datos['evolucion_id'] . "   ";
        $sql .= " and   sw_estado ='1' ;  ";
        if (!$rst = $this->ConexionTransaccion($sql))
            return false;



        $this->Commit();
        return true;
    }

    /* USUARIO QUE REALIZA LA FORMULA */

    function Profesional_formula($evolucion) {

        $sql = "  SELECT hc.medico_id,
              pro.nombre,
              pro.tipo_id_tercero,
                            pro.tercero_id,
              tipos.descripcion
            FROM   hc_formulacion_antecedentes hc
            LEFT JOIN profesionales_usuarios usu ON(hc.medico_id=usu.usuario_id)
            LEFT JOIN profesionales pro ON (usu.tipo_tercero_id=pro.tipo_id_tercero) and (usu.tercero_id=pro.tercero_id)
            LEFT JOIN tipos_profesionales tipos ON (pro.tipo_profesional=tipos.tipo_profesional)
            WHERE  hc.evolucion_id =" . $evolucion . "
                    ; ";

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

    /* MEDICAMENTOS DISPENSADOS */

     function Medicamentos_Dispensados_Esm_x_lote($evolucion,$ultimo) {
            $fech=" to_char(d.fecha_registro,'YYYY-mm-dd') as fecha_entrega ";
          if($ultimo==1){
              $fech=" max(to_char(d.fecha_registro,'YYYY-mm-dd')) as fecha_entrega ";
              $group=" GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12 order by fecha_entrega desc ";
          }

       $sql = "    select
              dd.codigo_producto,
              dd.cantidad as numero_unidades,
              dd.fecha_vencimiento ,
              dd.lote,
              fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
              fc_descripcion_producto_alterno(dd.codigo_producto) as molecula,
              d.usuario_id,
              sys.nombre,
              sys.descripcion,

              dd.sw_pactado,
              dd.total_costo,
			  inv.grupo_id,
              $fech
              FROM
                hc_formulacion_despachos_medicamentos as dc,

                bodegas_documentos as d,
                bodegas_documentos_d AS dd,
                system_usuarios  sys,
				inventarios_productos inv
              WHERE
                   dc.bodegas_doc_id = d.bodegas_doc_id
              and        dc.numeracion = d.numeracion


              and        dc.evolucion_id = " . $evolucion . "

              and        d.bodegas_doc_id = dd.bodegas_doc_id
              and        d.numeracion = dd.numeracion
              and       d.usuario_id=sys.usuario_id
			  and       inv.codigo_producto  = dd.codigo_producto
              $group ";
				
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

    /* CONSULTAR  LOS PENDIENTES DISPENSADOS */

    function pendientes_dispensados_ent($evolucion) {

        $fecha_hoy = date('Y-m-d');

        $sql = "    
                SELECT   dd.codigo_producto,
                dd.cantidad as numero_unidades,
                dd.fecha_vencimiento,
                dd.lote,
                fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,
                dd.sw_pactado,
                fc_descripcion_producto_molecula(dd.codigo_producto) as molecula,
                dd.total_costo,
                to_char(d.fecha_registro,'YYYY-mm-dd') as fecha_entrega,
                '1' as pendiente_dispensado,(select fecha_registro as fecha_entrega
		from
		hc_pendientes_por_dispensar  AS e 
		where 
		e.evolucion_id  ='" . $evolucion . "' and sw_estado='1' limit 1) as fecha_pendiente
                FROM hc_formulacion_despachos_medicamentos_pendientes tmp
                inner join bodegas_documentos as d on (tmp.bodegas_doc_id = d.bodegas_doc_id and tmp.numeracion = d.numeracion)
                inner join bodegas_documentos_d AS dd on (d.bodegas_doc_id = dd.bodegas_doc_id and d.numeracion = dd.numeracion)
                WHERE 
                tmp.evolucion_id = '" . $evolucion . "' ;";


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

    /* CONSULTAR EL UTLMO REGISTRO DISPENSADO POR  PRINCIPIO ACTIVO  SI LO HAY SI NO POR CODIGO DE PRODUCTO */

    function ConsultarUltimoResg_Dispens_($principio_activo, $paciente_id, $tipo_id_paciente, $producto, $today, $fecha_dias) {


        $sql = "
                SELECT A.resultado,
                A.fecha_registro,
                A.unidades,
                A.nombre,
                A.razon_social
                FROM (
                    SELECT to_char(d.fecha_registro,'YYYY-mm-dd') AS fecha_registro,
                    '1' as resultado,
                    SUM(dd.cantidad) as unidades,
                    SYS.nombre,
                    EMPRE.razon_social
                    FROM hc_formulacion_despachos_medicamentos as dc
                    JOIN hc_formulacion_antecedentes hc ON(dc.evolucion_id=hc.evolucion_id),
                    bodegas_documentos as d,
                    bodegas_documentos_d AS dd ,
                    inventarios_productos inve  left join medicamentos mm ON (inve.codigo_producto=mm.codigo_medicamento),
                    system_usuarios  SYS,
                    bodegas_doc_numeraciones  NUME,
                    empresas EMPRE
                    WHERE dc.bodegas_doc_id = d.bodegas_doc_id
                    and dc.numeracion = d.numeracion
                    and d.bodegas_doc_id = dd.bodegas_doc_id
                    and d.numeracion = dd.numeracion
                    and dd.codigo_producto=inve.codigo_producto
                    and d.usuario_id=SYS.usuario_id
                    and d.bodegas_doc_id=NUME.bodegas_doc_id
                    and NUME.empresa_id=EMPRE.empresa_id ";
        if ($principio_activo != "") {
            $sql .="and mm.cod_principio_activo='" . $principio_activo . "' ";
        } else {
            $sql .="and inve.codigo_producto='" . $producto . "' ";
        }

        $sql .= "   and hc.tipo_id_paciente='" . $tipo_id_paciente . "'
                    and hc.paciente_id='" . $paciente_id . "'
                    and dc.sw_estado='1'
                    GROUP BY d.fecha_registro,resultado,SYS.nombre,razon_social

                    UNION

                    SELECT to_char(d.fecha_registro,'YYYY-mm-dd') AS fecha_registro,
                    '0' as resultado,
                    SUM(dd.cantidad) as unidades,
                    SYS.nombre,
                    EMPRE.razon_social
                    FROM hc_formulacion_despachos_medicamentos_pendientes as dc
                    JOIN hc_formulacion_antecedentes hc ON(dc.evolucion_id=hc.evolucion_id) ,
                    bodegas_documentos as d,
                    bodegas_documentos_d AS dd ,
                    inventarios_productos inve  left join medicamentos mm ON (inve.codigo_producto=mm.codigo_medicamento) ,
                    system_usuarios  SYS,
                    bodegas_doc_numeraciones  NUME,
                    empresas EMPRE
                    WHERE dc.bodegas_doc_id = d.bodegas_doc_id
                    and dc.numeracion = d.numeracion
                    and d.bodegas_doc_id = dd.bodegas_doc_id
                    and d.numeracion = dd.numeracion
                    and dd.codigo_producto=inve.codigo_producto
                    and d.usuario_id=SYS.usuario_id
                    and d.bodegas_doc_id=NUME.bodegas_doc_id
                    and NUME.empresa_id=EMPRE.empresa_id  ";
        if ($principio_activo != "") {
            $sql .= "and mm.cod_principio_activo='" . $principio_activo . "' ";
        } else {
            $sql .= "and inve.codigo_producto='" . $producto . "' ";
        }
        $sql .= "
                     and hc.tipo_id_paciente='" . $tipo_id_paciente . "'
                     and hc.paciente_id='" . $paciente_id . "'
                     GROUP BY d.fecha_registro,resultado,SYS.nombre,razon_social
                     
union

    SELECT 										
                MAX(to_char(a.fecha_registro,'YYYY-MM-DD')) AS fecha_registro,
                '1' as resultado,
                SUM(b.cantidad) as unidades,
                g.nombre,
                --d.formula_papel,
                f.descripcion||'-'||i.razon_social as razon_social

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
                GROUP BY 2,4,5	
union

    SELECT 										
                MAX(to_char(a.fecha_registro,'YYYY-MM-DD')) AS fecha_registro,
                '0' as resultado,
                SUM(b.cantidad) as unidades,
                g.nombre,
                --d.formula_papel,
                f.descripcion||'-'||i.razon_social as razon_social

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
                GROUP BY 2,4,5	


           ) AS A    ORDER BY  A.resultado ASC ";


     /*   echo "<pre>";
          print_r($sql);
          echo "</pre>";
          exit();*/




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

    /*  USUARIO CON PROVILEGIOS */

    function Usuario_Privilegios_($Formulario) {

        $sql = "  SELECT sw_privilegios
                  FROM userpermisos_dispensacion
                  WHERE empresa_id= '{$Formulario['empresa_id']}' AND centro_utilidad = '{$Formulario['centro_utilidad']}' AND bodega = '" . trim($Formulario['bodega']) . "'
                  AND usuario_id =  " . UserGetUID() . " AND    sw_activo = '1' ";

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

    /* AUTORIZACION PARA LOS MEDICAMENTOS A DESPACHAR */

    function UpdateAutorizacion_por_medicamento($formula_id, $observacion, $producto) {
        $this->ConexionTransaccion();
        $sql = "     update    hc_formulacion_antecedentes
              set    sw_autorizado='1',
                  usuario_autoriza_id= " . UserGetUID() . ",
                  observacion_autorizacion='" . $observacion . "',
                  fecha_registro_autorizacion=now()

            WHERE     evolucion_id = " . $formula_id . "
            AND     codigo_medicamento = '" . $producto . "'
              ";
        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }

    /* Actualizar el estado de la Formula */

    function UpdateEstad_Form($evolucion) {

        $sql = "     update hc_formulacion_antecedentes
                     set    sw_estado='0'
            WHERE   evolucion_id = '" . $evolucion . "'  ";

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

    /* Actualizar el estado de la Formula */

    function Finalizo_Formula($evolucion) {

        $sql = "     update hc_formulacion_antecedentes
                     set    sw_estado='0',  sw_mostrar ='0'
            WHERE   evolucion_id = '" . $evolucion . "'  ";

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

    function tratamiento_finalizado($evolucion, $codigo_producto) {

        $sql = " update hc_formulacion_antecedentes set sw_estado='0', sw_mostrar ='0' WHERE   evolucion_id = '{$evolucion}' and codigo_medicamento='{$codigo_producto}' ";
        /* echo "<pre>";
          print_r($sql);
          echo "</pre>";
          exit(); */

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

    function obtenerCantidadPendienteFormula($evolucion) {


        $sql = "
                select 
                a.evolucion_id, 
                sum(a.cantidad)::integer as cantidad_total_solicitada, 
                coalesce(b.cantidad_total_despachada,0) as cantidad_total_despachada,
                coalesce((sum(a.cantidad)::integer - b.cantidad_total_despachada), sum(a.cantidad)::integer) as cantidad_pendiente
                from hc_medicamentos_recetados_amb a 
                left join (
                        select bb.evolucion_id, sum(bb.cantidad_total_despachada)::integer as cantidad_total_despachada
                        from (
                                select a.evolucion_id, sum(c.cantidad) as cantidad_total_despachada, 1 
                                from hc_formulacion_despachos_medicamentos a
                                inner join bodegas_documentos b on a.bodegas_doc_id = b.bodegas_doc_id and a.numeracion = b.numeracion
                                inner join bodegas_documentos_d c on b.bodegas_doc_id = c.bodegas_doc_id and b.numeracion = c.numeracion                                
                                group by 1
                                union all
                                select a.evolucion_id, sum(c.cantidad) as cantidad_total_despachada, 2  
                                from hc_formulacion_despachos_medicamentos_pendientes a
                                inner join bodegas_documentos b on a.bodegas_doc_id = b.bodegas_doc_id and a.numeracion = b.numeracion
                                inner join bodegas_documentos_d c on b.bodegas_doc_id = c.bodegas_doc_id and b.numeracion = c.numeracion
                                group by 1
                        ) as bb group by 1
                ) as b on a.evolucion_id = b.evolucion_id
                where a.evolucion_id={$evolucion} group by 1, 3, b.cantidad_total_despachada ";

        /* echo "<pre>";
          print_r($sql);
          echo "</pre>";
          exit(); */


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

    /* medicamentos dispensados por lote */

    function Medicamentos_Dispensados_Esm_x_lote_total($evolucion) {


        $fecha_hoy = date('Y-m-d');


        $sql = " 
            
                select 
                *
                 from(
                            select
                                dd.codigo_producto,
                                dd.cantidad as numero_unidades,
                                dd.fecha_vencimiento ,
                                dd.lote,
                                fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,

                                d.usuario_id,
                                sys.nombre,
                                sys.descripcion,
                                'dispensacion_hc' as sistema, 
                                to_char(d.fecha_registro,'YYYY-mm-dd') as fecha_entrega,
                                to_char(now()- d.fecha_registro,'dd') as dias_de_entregado
                                FROM
                                  hc_formulacion_despachos_medicamentos as dc,

                                  bodegas_documentos as d,
                                  bodegas_documentos_d AS dd,
                                  system_usuarios  sys
                                WHERE
                                     dc.bodegas_doc_id = d.bodegas_doc_id
                                and        dc.numeracion = d.numeracion


                                and        dc.evolucion_id = ".$evolucion."

                                and        d.bodegas_doc_id = dd.bodegas_doc_id

                                and        d.numeracion = dd.numeracion
                                and       d.usuario_id=sys.usuario_id

                    union
                        select
                          dd.codigo_producto,
                          dd.cantidad as numero_unidades,
                          dd.fecha_vencimiento ,
                          dd.lote,
                          fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,

                          d.usuario_id,
                          sys.nombre,
                          sys.descripcion,
                          'dispensacion_viejo' as sistema,
                          to_char(d.fecha_registro,'YYYY-mm-dd') as fecha_entrega,
                          to_char(now()- d.fecha_registro,'dd') as dias_de_entregado
                          FROM
                            hc_formulacion_despachos_medicamentos_pendientes as dc,
                            bodegas_documentos as d,
                            bodegas_documentos_d AS dd,
                            system_usuarios  sys
                          WHERE
                               dc.bodegas_doc_id = d.bodegas_doc_id
                          and        dc.numeracion = d.numeracion


                          and        dc.evolucion_id = ".$evolucion."

                          and        d.bodegas_doc_id = dd.bodegas_doc_id

                          and        d.numeracion = dd.numeracion
                          and       d.usuario_id=sys.usuario_id)as k
                           order by fecha_entrega asc; 
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
	
	
    function Medicamentos_Dispensados_Esm_x_lote_total_sin_pendientes($evolucion) {

        $sql = " 
                            select
                                dd.codigo_producto,
                                dd.cantidad as numero_unidades,
                                dd.fecha_vencimiento ,
                                dd.lote,
                                fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod,

                                d.usuario_id,
                                sys.nombre,
                                sys.descripcion,
                                'dispensacion_hc' as sistema, 
                                to_char(d.fecha_registro,'YYYY-mm-dd') as fecha_entrega,
                                to_char(now()- d.fecha_registro,'dd') as dias_de_entregado
                                FROM
                                  hc_formulacion_despachos_medicamentos as dc,

                                  bodegas_documentos as d,
                                  bodegas_documentos_d AS dd,
                                  system_usuarios  sys
                                WHERE
                                     dc.bodegas_doc_id = d.bodegas_doc_id
                                and        dc.numeracion = d.numeracion


                                and        dc.evolucion_id = ".$evolucion."

                                and        d.bodegas_doc_id = dd.bodegas_doc_id

                                and        d.numeracion = dd.numeracion
                                and       d.usuario_id=sys.usuario_id

                   
                           order by fecha_entrega asc; ";

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

    function Numero_Medicamentos_Dispensados($evolucion) {
        $sql = " select codigo_producto,count(w.codigo_producto) as entregado from
                            (
                            select
                                                dd.codigo_producto,
                                                to_char(now()- d.fecha_registro,'dd') as dias_de_entregado
                                                FROM
                                                  hc_formulacion_despachos_medicamentos as dc,

                                                  bodegas_documentos as d,
                                                  bodegas_documentos_d AS dd,
                                                  system_usuarios  sys
                                                WHERE
                                                     dc.bodegas_doc_id = d.bodegas_doc_id
                                                and        dc.numeracion = d.numeracion


                                                and        dc.evolucion_id = '$evolucion' --126133

                                                and        d.bodegas_doc_id = dd.bodegas_doc_id

                                                and        d.numeracion = dd.numeracion
                                                and       d.usuario_id=sys.usuario_id

                                    union
                                        select
                                          dd.codigo_producto,
                                          to_char(now()- d.fecha_registro,'dd') as dias_de_entregado
                                          FROM
                                            hc_formulacion_despachos_medicamentos_pendientes as dc,

                                            bodegas_documentos as d,
                                            bodegas_documentos_d AS dd,
                                            system_usuarios  sys
                                          WHERE
                                               dc.bodegas_doc_id = d.bodegas_doc_id
                                          and        dc.numeracion = d.numeracion


                                          and        dc.evolucion_id = '$evolucion' --126133

                                          and        d.bodegas_doc_id = dd.bodegas_doc_id

                                          and        d.numeracion = dd.numeracion
                                          and       d.usuario_id=sys.usuario_id
                            ) as w
                            group by codigo_producto order by 2 desc limit 1";

//echo "<pre>".$sql;
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $datos = array();
        while (!$rst->EOF) {
            $datos[] =$rst->GetRowAssoc($ToUpper);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }


    /* MEDICAMENTOS PENDIENTES DISPENSADOS TOTAL */

    function pendientes_dispensados_ent_TOTAL($evolucion) {

        $fecha_hoy = date('Y-m-d');

       /* $sql = "    SELECT   dd.codigo_producto,
                          dd.cantidad as numero_unidades,
                          dd.fecha_vencimiento ,
                          dd.lote,
                          fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod

           FROM hc_formulacion_despachos_medicamentos_pendientes tmp,
                            bodegas_documentos as d,
                            bodegas_documentos_d AS dd
           WHERE tmp.bodegas_doc_id = d.bodegas_doc_id
           and tmp.numeracion = d.numeracion
           and d.bodegas_doc_id = dd.bodegas_doc_id
           and d.numeracion = dd.numeracion
            and  tmp.evolucion_id = '" . $evolucion . "' ";*/
			
			$sql = "SELECT   dd.codigo_producto,
                          dd.cantidad as numero_unidades,
                          dd.fecha_vencimiento ,
                          dd.lote,
                          fc_descripcion_producto_alterno(dd.codigo_producto) as descripcion_prod

           FROM hc_formulacion_despachos_medicamentos_pendientes tmp,
   
                            bodegas_documentos_d AS dd
           WHERE tmp.bodegas_doc_id = dd.bodegas_doc_id
           and tmp.numeracion = dd.numeracion

            and  tmp.evolucion_id ='" . $evolucion . "' ";

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

    /* ACTUALIZAR  ESTADO DE LA FORMULA cuando  esta vencida  */

    function UpdateEstad_Form_venci($evolucion) {

        $sql = "     update hc_formulacion_antecedentes
                     set    sw_mostrar ='0'

            WHERE   evolucion_id = '" . $evolucion . "'  ";

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

    function consultar_dispensacion_temporal($evolucion) {

        $sql = "SELECT  
                hc_dispen_tmp_id,
                evolucion_id,
                empresa_id,
                centro_utilidad,
                bodega,
                codigo_formulado,
                codigo_producto,
                cantidad_despachada,
                TO_CHAR(fecha_vencimiento,'DD-MM-YYYY') as fecha_vencimiento,
                lote,
                fc_descripcion_producto_alterno(codigo_producto) as descripcion_prod
                FROM hc_dispensacion_medicamentos_tmp
                WHERE evolucion_id = '{$evolucion}' ";

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

    function consultar_tipo_formulas() {

        $sql = "SELECT 
                a.tipo_formula_id,
                a.descripcion_tipo_formula						
		FROM esm_tipos_formulas as a				     
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

    Function guardar_tipo_fornula($evolucion_id, $tipo_formula) {

        $this->ConexionTransaccion();

        $sql = " UPDATE hc_evoluciones set tipo_formula = '$tipo_formula' WHERE evolucion_id = '$evolucion_id' ;";

        if (!$rst = $this->ConexionTransaccion($sql))
            return false;

        $this->Commit();
        return true;
    }

}

?>