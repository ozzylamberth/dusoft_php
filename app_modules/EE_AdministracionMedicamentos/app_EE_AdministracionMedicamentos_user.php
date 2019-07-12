<?php
  /**
  * $Id: app_EE_AdministracionMedicamentos_user.php,v 1.10 2011/05/02 12:51:02 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
  * @package IPSOFT-SIIS
  */
  class app_EE_AdministracionMedicamentos_user extends classModulo
  {
    /**
    * Valida si el usuario esta logueado en La Estacion de Enfermeria y si tiene permiso
    * Para este componente ('01'= Admision - Asignacion Cama)
    *
    * @return boolean
    * @access private
    */
    function GetUserPermisos($componente=null)
    {
          $estacion_id = $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()];
          
          if($componente)
          {
               if(!empty($_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO'][UserGetUID()][$estacion_id]['COMPONENTES'][$componente]))
               {
                    return true;
               }
               else
               {
                    return null;
               }
          }
     
          if(!empty($_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO'][UserGetUID()][$estacion_id]))
          {
               return true;
          }
          else
          {
               return null;
          }
    }
    /**
    * Retorna los datos de la estacion de enfermeria actual.
    *
    * @return array
    * @access private
    */
    function GetdatosEstacion()
    {
      $estacion_id = $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()];
      return $_SESSION['EE_PanelEnfermeria']['DATOS_ESTACION'][$estacion_id];
    }
    /*
    * Consulta de los medicamentos que fueron recetados al paciente
    * Adaptacion Tizziano Perea.
    *PLANTERAPEUTICO
    */
    function Consulta_Solicitud_Medicamentos($ingreso)
    {
          list($dbconnect) = GetDBconn();
		  
          GLOBAL $ADODB_FETCH_MODE;
          $query= " SELECT  A.ingreso,
                            A.codigo_producto,
                            (SELECT bp.stock_paciente FROM bodega_paciente bp WHERE bp.ingreso = A.ingreso and bp.codigo_producto = 'A.codigo_producto')AS stockbodega,
                            (SELECT (bp.cantidad_en_solicitud)	FROM bodega_paciente bp WHERE bp.ingreso = A.ingreso and bp.codigo_producto = A.codigo_producto)AS solicitadoval,
                            A.num_reg,
                            A.num_reg_formulacion,
                            A.sw_estado,
                            A.observacion,
                            A.via_administracion_id,
                            A.unidad_dosificacion,
                            A.dosis,
                            A.frecuencia,
                            A.sw_confirmacion_formulacion,
                            A.sw_requiere_autorizacion_no_pos,
                            A.dias_tratamiento,
                            A.justificacion_no_pos_id,
                            A.cantidad*(CASE WHEN A.dias_tratamiento IS NULL THEN 5000 ELSE A.dias_tratamiento END)AS cantidad,          
                            'M' AS tipo_solicitud,
                            G.evolucion_id, 
                            G.usuario_id, 
                            TO_CHAR(B.fecha_registro,'YYYY-MM-DD HH24:MI:SS') as fecha_registro,
                            --C.descripcion AS producto,
                            fc_descripcion_producto(A.codigo_producto) as producto,
                            C.descripcion_abreviada, 
                            C.contenido_unidad_venta,
                            C.unidad_id,
                            D.nombre AS via_administracion,
                            CASE WHEN E.sw_pos = '1' THEN 'POS' 
                                 ELSE 'NO POS' END AS codigo_pos,
                            F.descripcion AS unidad
                    FROM    hc_formulacion_medicamentos AS A
                            INNER JOIN hc_formulacion_medicamentos_eventos G 
                            ON(G.num_reg = A.num_reg_formulacion),
                            hc_formulacion_medicamentos_eventos AS B,
                            inventarios_productos AS C,
                            hc_vias_administracion AS D,
                            medicamentos AS E,
                            unidades AS F
                    
                    WHERE A.ingreso = ".$ingreso."
                    AND A.num_reg = B.num_reg
                    AND A.sw_estado IN ('1','2')
                    AND A.codigo_producto = C.codigo_producto
                    AND A.via_administracion_id = D.via_administracion_id
                    AND A.codigo_producto = E.codigo_medicamento
                    AND F.unidad_id = C.unidad_id
                    ORDER BY A.sw_estado, G.evolucion_id;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconnect->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          while ($data = $result->FetchRow())
          {
               $vector[] = $data;
          }
     
          return $vector;
    }
     /*
     * Consulta de las soluciones que fueron recetadas al paciente
     * Adaptacion Tizziano Perea.
     */
     function Consulta_Solicitud_Soluciones($ingreso)
     {
          list($dbconnect) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          $query= "SELECT A.*, 'S' AS tipo_solicitud,
                          B.evolucion_id, B.usuario_id, B.fecha_registro,
                          I.codigo_producto, I.sw_solucion, I.cantidad AS cantidad_producto,
                          I.unidad_dosificacion AS unidad_suministro, I.dosis,
                          C.descripcion AS producto, C.descripcion_abreviada, C.contenido_unidad_venta,
                          C.unidad_id,
                          F.descripcion AS unidad
                    	 
                          
                    FROM hc_formulacion_mezclas AS A,
     		          hc_formulacion_mezclas_eventos AS B,
                         hc_formulacion_mezclas_detalle AS I,
                         inventarios_productos AS C,
					medicamentos AS E,
                         unidades AS F
                    
                    WHERE A.ingreso = ".$ingreso."
                    AND A.num_reg = B.num_reg
                    AND A.num_mezcla = B.num_mezcla
                    AND A.num_mezcla = I.num_mezcla
                    AND A.sw_estado IN ('1','2')                    
                    AND I.codigo_producto = C.codigo_producto
                    AND I.codigo_producto = E.codigo_medicamento
                    AND C.unidad_id = F.unidad_id
                    ORDER BY A.sw_estado";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconnect->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          while ($data = $result->FetchRow())
          {
               $vector[] = $data;
          }
     
          return $vector;
     }
    /**
    * Funcion donde se realiza la busqueda de las solicitudes de Insumos hechas
    *
    * @param integer $ingreso Identificador del ingreso
    *
    * @return mixed
    */
    function Consulta_Solicitud_Insumos($ingreso)
    {
      list($dbconnect) = GetDBconn();
      GLOBAL $ADODB_FETCH_MODE;

      $query = " SELECT DISTINCT
                        B.codigo_producto,
                        X.empresa_id, 
                        X.centro_utilidad, 
                        INV.descripcion,
                        BOD.codigo_producto AS non_existencia,     
                        BOD.stock_paciente
                FROM    bodegas_documento_despacho_med A,
                        bodegas_documento_despacho_ins_d B,
                        hc_solicitudes_medicamentos X,
                        inventarios_productos INV
                        LEFT JOIN bodega_paciente BOD 
                        ON( INV.codigo_producto = BOD.codigo_producto AND 
                            BOD.ingreso = ".$ingreso." )
                WHERE   X.ingreso = ".$ingreso."
                AND     X.tipo_solicitud = 'I'
                AND     X.sw_estado IN ('2','5')
                AND     A.documento_despacho_id = X.documento_despacho
                AND     B.documento_despacho_id = A.documento_despacho_id
                AND     B.codigo_producto = INV.codigo_producto
                ORDER BY non_existencia;";

      $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      $result = $dbconnect->Execute($query);
      $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
 
      if ($dbconnect->ErrorNo() != 0)
      {
        $this->error = "Error al buscar en la consulta de medicamentos recetados";
        $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
        return false;
      }
      
      while ($data = $result->FetchRow())
        $vector[] = $data;
     
      return $vector;
    }
     /**
     *       GetEstacionBodega
     *       obtiene la estacion asociada a una bodega.
     *
     *       @Author Jairo Duvan Diaz M.
     *       @return array, false � string
     *       @param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
     */
     function GetEstacionBodega($datos,$sw)
     {
          if($sw==1)
          {
               $filtro="AND b.sw_consumo_directo='0'";
          }
          elseif($sw==2)
          {
               $filtro="AND b.sw_consumo_directo='1'";
          }
          list($dbconn) = GetDBconn();
          $query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.descripcion
                    FROM bodegas_estaciones a,bodegas b
                    WHERE  a.estacion_id='".$datos[estacion_id]."'
                    AND a.centro_utilidad=b.centro_utilidad
                    AND a.empresa_id=b.empresa_id
                    AND a.bodega=b.bodega
                    $filtro
                    AND a.centro_utilidad='".$datos[centro_utilidad]."'
                    AND a.empresa_id='".$datos[empresa_id]."'";
          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $result = $dbconn->Execute($query);
     
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     
          if($result->EOF)
          {
               return '';
          }
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
          return $vector;
     }
     /**
     *   Obtiene las existencias de los productos de una bodega.
     *
     *   @Author Jairo Duvan Diaz M.
     *   @access Public
     *   @return array, false � string
     *   @param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
     */
     function Get_Existencia_producto_Bodega($codigo,$estacion)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT DISTINCT(bodega) FROM  existencias_bodegas
                    WHERE codigo_producto='$codigo'
                    AND empresa_id='".$estacion[empresa_id]."'
                    AND centro_utilidad='".$estacion[centro_utilidad]."'";
          
               $resulta=$dbconn->execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     
          if($result->EOF)
          {
               return '';
          }
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
          return $vector;
     }
     /**
     *   RevisarExistenciaBodega
     *
     *   obtiene la estacion asociada a una bodega.
     *
     *   @Author Jairo Duvan Diaz M.
     *   @access Public
     *   @return array, false � string
     *   @param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
     */
     function RevisarExistenciaBodega($estacion,$bodega,$codigo)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT existencia FROM existencias_bodegas
                    WHERE empresa_id='".$estacion['empresa_id']."'
                    AND centro_utilidad='".$estacion['centro_utilidad']."'
                    AND bodega='$bodega'
                    AND codigo_producto='$codigo'";
     
          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          return $resulta->fields[0]; //empresa
     }
     /*
     * Obtiene las posologias de los diferentes productos recetados al paciente.
     */     
     function Consulta_Solicitud_Medicamentos_Posologia($codigo_producto, $tipo_posologia, $evolucion_id)
     {
          list($dbconnect) = GetDBconn();
          $query == '';
          if ($tipo_posologia == 1)
          {
     	     $query= "select periocidad_id, tiempo from hc_posologia_horario_op1_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
          }
          if ($tipo_posologia == 2)
          {
               $query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2_hosp as a, hc_horario as b where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto' and a.duracion_id = b.duracion_id";
          }
          if ($tipo_posologia == 3)
          {
          	$query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
          }
          if ($tipo_posologia == 4)
          {
          	$query= "select hora_especifica from hc_posologia_horario_op4_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
          }
          if ($tipo_posologia == 5)
          {
          	$query= "select frecuencia_suministro from hc_posologia_horario_op5_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
          }
     
          if ($query!='')
          {
               $result = $dbconnect->Execute($query);
               if ($dbconnect->ErrorNo() != 0)
               {
                    $this->error = "Error al buscar en la consulta de medicamentos recetados";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
               }
               else
               {
                    if ($tipo_posologia != 4)
                    {
                         while (!$result->EOF)
                         {
                              $vector[]=$result->GetRowAssoc($ToUpper = false);
                              $result->MoveNext();
                         }
                    }
                    else
                    {
                         while (!$result->EOF)
                         {
                              $vector[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
                              $result->MoveNext();
                         }
                    }
               }
          }
          return $vector;
    }
    /*
    * Funcion valida para las mezclas de medicamentos.
    * Responsable: Lorena Aragon
    */     
    function SolucionNombreCantidad($solucion_id,$cantidad_id)
    {
      list($dbconnect) = GetDBconn();
      $query= " SELECT  a.descripcion,
                        b.cantidad,b.unidad_id
                FROM    hc_medicamentos_soluciones a,
                        hc_medicamentos_soluciones_cantidades b
                WHERE   a.solucion_id='".$solucion_id."' 
                AND     b.cantidad_id='$cantidad_id' ";
      $result = $dbconnect->Execute($query);
      if($dbconnect->ErrorNo() != 0)
      {
        $this->error = "Error al buscar en la tabla";
        $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
        return false;
      }
      else
      {
        if($result->RecordCount()>0)
        {
          $vector=$result->GetRowAssoc($ToUpper = false);
        }
      }
      return $vector;
    }
     //funcion que trae los med solicitados del pacientes para para cancelarlos.
     //pero desde controles de pacientes ya que no podemos filtrar por estacion
     //[duvan] preguntar los estados q podemnos visualizar.... 0,1 ,2...
     function Get_Medicamentos_Solicitados_Para_Pacientes($ingreso,$empresa,$solicitud,$codigo)
     {
          if($codigo)
          {
          	$filtro="AND z.codigo_producto='$codigo'";
          }
     
          list($dbconnect) = GetDBconn();
          $query= "SELECT h.codigo_producto,h.descripcion as producto,
                         h.descripcion_abreviada, l.descripcion,sum(z.cantidad) as cantidad
     
                         FROM 
                         inventarios_productos as h, unidades as l,
                         hc_solicitudes_medicamentos_pacientes x,
                         hc_solicitudes_medicamentos_pacientes_d z 
                         WHERE 
                         x.ingreso =$ingreso and x.solicitud_id=z.solicitud_id 
                         and x.sw_estado = '0' and z.sw_estado = '0' 
                         $filtro
                         and h.codigo_producto = z.codigo_producto and h.unidad_id = l.unidad_id 
                    GROUP BY h.codigo_producto,h.descripcion,h.descripcion_abreviada, l.descripcion";
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                         $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
                         $i++;
               }
          }
          return $vector;
     }
     
     //funcion que trae los med recibidos 
	//[duvan] preguntar los estados q podemnos visualizar.... 0,1 ,2...
     function Recepcion_Med_Ins_Para_Pacientes($ingreso,$codigo,$estacion)
     {
          list($dbconnect) = GetDBconn();
          $query= "SELECT SUM(z.cantidad) AS cantidad
                   FROM
                    hc_recepcion_medicamentos_pacientes x,
                    hc_recepcion_medicamentos_pacientes_d z
		         WHERE
                    x.ingreso = '$ingreso' AND
                    z.codigo_producto = '$codigo' AND
                    z.estado='0' AND
                    x.estacion_id = '$estacion' AND
                    x.recepcion_id=z.recepcion_id";
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
          return $result->fields[0];
          }
     }
    /**
    * Funcion que trae los medicamentos solicitados para cancelarlos.
    * pero desde controles de pacientes ya que no podemos filtrar por estacion
    *
    * @param integer $ingreso Identificador del ingreso
    * @param string $emp Identificador de la empresa
    *
    * @return mixed
    */
    function GetMedicamentosSolicitadosControlPacientes($ingreso,$emp)
    { //H.descripcion AS producto, fc_descripcion_producto(A.codigo_producto)as producto
      list($dbconnect) = GetDBconn();
      
      $query= " (
                  SELECT DISTINCT A.ingreso,
                          A.codigo_producto,
                          fc_descripcion_producto(A.codigo_producto)as producto,
                          C.descripcion AS principio_activo,
                          L.descripcion, 
                          X.solicitud_id, 
                          X.bodega,
                          Z.consecutivo_d, 
                          Z.cant_solicitada
                  FROM		hc_formulacion_medicamentos AS A,
                          inventarios_productos AS H, 
                          medicamentos AS K,
                          inv_med_cod_principios_activos AS C,
                          unidades AS L,
                          hc_solicitudes_medicamentos AS X,
                          hc_solicitudes_medicamentos_d AS Z
                  WHERE   X.ingreso = ".$ingreso."
                  AND X.solicitud_id = Z.solicitud_id
                  AND X.empresa_id='$emp'
                  AND X.sw_estado = '0'
                  AND A.codigo_producto = Z.medicamento_id
                  AND A.ingreso = Z.ingreso
                  AND A.sw_estado IN ('1','2','0')
                  AND K.cod_principio_activo = C.cod_principio_activo
                  AND A.codigo_producto = K.codigo_medicamento
                  AND A.codigo_producto = H.codigo_producto
                  AND H.unidad_id = L.unidad_id
                )
                UNION ALL
                (
                  SELECT DISTINCT X.ingreso,
                          Z.medicamento_id AS codigo_producto,
                          H.descripcion AS producto,
                          C.descripcion AS principio_activo,
                          L.descripcion, 
                          X.solicitud_id, 
                          X.bodega,
                          Z.consecutivo_d, 
                          Z.cant_solicitada
                  FROM		inventarios_productos AS H, 
                          medicamentos AS K,
                          inv_med_cod_principios_activos AS C,
                          unidades AS L,
                          hc_solicitudes_medicamentos AS X,
                          hc_solicitudes_medicamentos_d AS Z
                          LEFT JOIN hc_formulacion_medicamentos AS A
                          ON (A.codigo_producto = Z.medicamento_id AND
                              Z.ingreso = A.ingreso)
                  WHERE   X.ingreso = ".$ingreso."
                  AND     X.solicitud_id = Z.solicitud_id
                  AND     X.empresa_id = '".$emp."'
                  AND     X.sw_estado = '0'
                  AND     K.cod_principio_activo = C.cod_principio_activo
                  AND     Z.medicamento_id = K.codigo_medicamento
                  AND     Z.medicamento_id = H.codigo_producto
                  AND     H.unidad_id = L.unidad_id
                  AND     A.codigo_producto IS NULL
                )
                UNION ALL
                (
                  SELECT DISTINCT A.ingreso,
                          D.codigo_producto,
                          H.descripcion AS producto,
                          C.descripcion AS principio_activo,
                          L.descripcion, X.solicitud_id, X.bodega,
                          Z.consecutivo_d, Z.cant_solicitada
                  FROM
                     hc_formulacion_mezclas AS A,
                     hc_formulacion_mezclas_detalle AS D,
                     inventarios_productos AS H, 
                     medicamentos AS K,
                     inv_med_cod_principios_activos AS C,
                  unidades AS L,
                     hc_solicitudes_medicamentos AS X,
                     hc_solicitudes_medicamentos_d AS Z
           
                  WHERE X.ingreso = ".$ingreso."
                  AND X.solicitud_id = Z.solicitud_id
                  AND X.empresa_id='$emp'
                  AND X.sw_estado = '0'
                  AND A.num_mezcla = D.num_mezcla
                  AND D.codigo_producto = Z.medicamento_id
                  AND A.ingreso = Z.ingreso
                  AND A.sw_estado IN ('1','2','0')
                  AND K.cod_principio_activo = C.cod_principio_activo
                  AND D.codigo_producto = K.codigo_medicamento
                  AND D.codigo_producto = H.codigo_producto
                  AND H.unidad_id = L.unidad_id
                )
                ORDER BY consecutivo_d ASC, solicitud_id DESC;";
                 
      $result = $dbconnect->Execute($query);
      if ($dbconnect->ErrorNo() != 0)
      {
        $this->error = "Error al buscar en la consulta de medicamentos recetados";
        $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
        return false;
      }
      else
      { 
        $i=0;
        while (!$result->EOF)
        {
          $vector[$i]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
          $i++;
        }
      }
      return $vector;
    }
     
     /**
     *       GetEstacionBodega
     *
     *       obtiene el nombre de la bodega.
     *
     *       @Author Jairo Duvan Diaz M.
     *       @access Public
     *       @return array, false � string
     *       @param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
     */
     function TraerNombreBodega($estacion,$bodega)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT descripcion FROM bodegas
                    WHERE empresa_id='".$estacion['empresa_id']."'
                    AND centro_utilidad='".$estacion['centro_utilidad']."'
                    AND bodega='$bodega'";
               $resulta=$dbconn->execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               return $resulta->fields[0];
     }

     
     //aqui nos damos cuenta si ya se despacharon los medicamentos
     function GetPacientesConMedicamentosPorDesp($ingreso,$letra,$estacion,$op)
     {
          list($dbconn) = GetDBconn();
          if($op == 1)
          { $sw_estado = "AND sw_estado='2'";}
          else
          { $sw_estado = "AND sw_estado IN('1','5')";}
               
          $query="SELECT COUNT(*) FROM hc_solicitudes_medicamentos
                    WHERE ingreso='".$ingreso."'
                    $sw_estado
                    AND tipo_solicitud='$letra'
                    AND estacion_id='$estacion'";
          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          if($resulta->fields[0]>0)
          {
               return 1;
          }
          return '';
     }
     
     //funcion que trae los imsunos solicitados para cancelarlos.
	//pero desde controles de pacientes ya que no podemos filtrar por estacion
     function GetInsumosSolicitadosControlPacientes($ingreso,$emp)
     {
          list($dbconnect) = GetDBconn();
     	$query= "select 
                    h.codigo_producto,z.cantidad,
                    h.descripcion as producto,h.descripcion_abreviada,
                    l.descripcion,x.solicitud_id,z.consecutivo_d,x.bodega
          
                    FROM  inventarios_productos as h,
                          unidades as l,
                          hc_solicitudes_medicamentos x,
                          hc_solicitudes_insumos_d z
                    WHERE
                    x.ingreso = ".$ingreso."
                    and x.solicitud_id=z.solicitud_id
                    and x.empresa_id='$emp'
                    and x.sw_estado = '0'
                    and  h.codigo_producto = z.codigo_producto
                    and h.unidad_id = l.unidad_id
                    ORDER BY z.consecutivo_d ASC,z.solicitud_id DESC";

          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                         $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
                         $i++;
               }
          }
          return $vector;
     }

     /*
     * Funcion valida para las mezclas de medicamentos.
     * Responsable: Lorena Aragon
     */     
     
     function TiposSolucionesProductos($op)
     {
	     $query="SELECT a.solucion_id,a.codigo_producto,b.descripcion,a.cantidad_id,c.cantidad,c.unidad_id,d.descripcion as nom_solucion,a.evolucion_id
               FROM hc_medicamentos_recetados_hosp a
               LEFT JOIN hc_medicamentos_soluciones_cantidades c ON (a.cantidad_id=c.cantidad_id)
               ,inventarios_productos b,hc_medicamentos_soluciones d
               WHERE a.solucion_id IS NOT NULL AND (";
               $con=1;
               foreach($op as $l=>$val)
               {
                    $dato=explode(",",$val);
                    if(!empty($dato[1]))
                    {
                         if($con==sizeof($op)){
                              $query.=" ( a.codigo_producto = '".$dato[1]."' AND a.evolucion_id = '".$dato[4]."' AND a.codigo_producto=b.codigo_producto)";
                         }else{
                              $query.=" ( a.codigo_producto = '".$dato[1]."' AND a.evolucion_id = '".$dato[4]."' AND a.codigo_producto=b.codigo_producto) OR ";
                         }
                    }
               $con++;
               }

          $query.=" ) AND a.solucion_id=d.solucion_id;";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if($result->RecordCount()>0)
          {
          	while ($data = $result->FetchRow()) {
               	$vars[$data['solucion_id']][$data['codigo_producto']]=$data;
          	}
          }
          return $vars;
     }
     
     function ProductosInventarioSolucion($solucionId,$empresa_id,$centro_utilidad,$bodega){
          list($dbconnect) = GetDBconn();
          $query= "SELECT b.codigo_producto,b.descripcion
               FROM hc_medicamentos_soluciones_productos a,inventarios_productos b,existencias_bodegas c
               WHERE a.solucion_id='".$solucionId."' AND a.codigo_producto=b.codigo_producto AND
               a.codigo_producto=c.codigo_producto AND c.bodega='$bodega' AND
               c.empresa_id='$empresa_id' AND c.centro_utilidad='$centro_utilidad'";
          $result = $dbconnect->Execute($query);
          if($dbconnect->ErrorNo() != 0){
               $this->error = "Error al buscar en la tabla";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }else{
               if($result->RecordCount()>0){
                    while(!$result->EOF){
                         $vector[]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
                    }
               }
          }
          return $vector;
     }
     
     /**
     *   Revisar_Relacion_Medicamento_Bodegas
     *
     *   obtiene la estacion asociada a una bodega.
     *   Adaptacion Tizziano Perea.
     *   @access Public
     *   @return array, false � string
     *   @param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
     */
     function Revisar_Relacion_Medicamento_Bodegas($codigo,$bodega,$cadena,$agrupar)
     {
          list($dbconn) = GetDBconn();
          if(strlen($cadena)>0)
          {
               $filtro="AND a.insumo_id IN($cadena)";
          }
          if(!empty($codigo))
          {
               $filtro2="AND a.medicamento_id = '$codigo'";
          }
          if(!empty($agrupar))
          {
               $filtro3="AND a.codigo_agrupamiento = $agrupar";
          }
                    
          $query="SELECT a.medicamento_id,a.insumo_id,b.codigo_producto,
                         b.descripcion,a.cantidad, a.codigo_agrupamiento 
                    FROM hc_solicitudes_relacion_medicamento_insumos a,
                         inventarios_productos b
                    WHERE
                         a.insumo_id = b.codigo_producto 
                         $filtro2
                         $filtro
                         $filtro3
                         ORDER BY a.codigo_agrupamiento;";
     
          $result=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while (!$result->EOF)
          {
               $var[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }
          return $var;
     }
     
     /**
     *   InsertSolicitudMed_Para_Paciente
     *
     *   guarda o realiza la solicitud de los medicamentos para el paciente, 
     *    donde no se traen de bodega.
     *
     *   @Author Jairo Duvan Diaz M.
     *   Adaptacion Tizziano Perea
     *  @access Public
     *   @return array, false � string
     *   @param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
     */
     function InsertSolicitudMed_Para_Paciente()
     {
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $bodega=$_REQUEST['bodega'];
          
          // Detalle de la solicitud
          $productos_detalle = $_SESSION['VECTOR_DETALLE_PRODUCTOS'];
          
          $area = $_REQUEST['area'];
          $nom = $_REQUEST['nom'];
     
          list($dbconn) = GetDBconn();
               $dbconn->BeginTrans();
          
          $query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_pacientes_solicitud_id_seq');";
          $res=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No se pudo traer la secuencia ";
               $this->mensajeDeError = "Ocurri� un error al intentar obtener el siguiente valor de la secuencia.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          $solicitud=$res->fields[0];
     
          $query="INSERT INTO  hc_solicitudes_medicamentos_pacientes
                    (solicitud_id,
                         ingreso,
                         usuario_id,
                         sw_estado,
                         fecha_solicitud,
                         estacion_id,
                         observaciones,
                         nombre_recibe_solicitud,
                         tipo_solicitud
                         )VALUES('$solicitud',
                              ".$datosPaciente[ingreso].",
                              ".UserGetUID().",
                              '0',
                              '".date("Y-m-d H:i:s")."',
                              '".$datos_estacion[estacion_id]."',
                              '$area',
                              '$nom',
                              'M');";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No se inserto en hc_solicitudes_medicamentos_pacientes ";
               $this->mensajeDeError = "Ocurri� un error al realizar la solicitud de medicamentos para pacientes.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }

          for($i=0;$i<sizeof($productos_detalle);$i++)
          {
               $query="INSERT INTO hc_solicitudes_medicamentos_pacientes_d
                                   (solicitud_id,
                                        codigo_producto,
                                        sw_estado,
                                        cantidad,
                                        ingreso)
                                   VALUES('$solicitud',
                                          '".$productos_detalle[$i]['codigo']."',
                                          '0',
                                          ".$productos_detalle[$i]['cantidad'].",
                                          ".$datosPaciente[ingreso].")";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos_d ";
                    $this->mensajeDeError = "Ocurri� un error al insertar el detalle de la solicitud de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
                         
          if(is_array($_REQUEST['checo']) and $para_q_no_entre=='xxx')
          {
               //funcion que crea la solicitud de insumos automaticamente, cuando lo hacemos directamente desde 
               //las solicitudes de medicamentos.
               $query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
               $res=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se pudo traer la secuencia ";
                    $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
               $solicitud=$res->fields[0];
                    
               $query="INSERT INTO hc_solicitudes_medicamentos
                                        (solicitud_id,
                                        ingreso,
                                        bodega,
                                        empresa_id,
                                        centro_utilidad,
                                        usuario_id,
                                        sw_estado,
                                        fecha_solicitud,
                                        estacion_id,
                                        tipo_solicitud
                                        )VALUES('$solicitud',
                                             ".$datosPaciente[ingreso].",
                                             '".$bodega."',
                                             '".$datos_estacion[empresa_id]."',
                                             '".$datos_estacion[centro_utilidad]."',
                                             ".UserGetUID().",
                                             '0',
                                             '".date("Y-m-d H:i:s")."',
                                             '".$datos_estacion[estacion_id]."',
                                             'I')";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                    $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
                         
               for($r=0;$r<sizeof($_REQUEST['checo']);$r++)
               {
                    $codigo=explode(",",$_REQUEST['checo'][$r]);
                    //[0]-> medicamento_id
                    //[1]-> codigo_producto o insumo_id

                    if(!empty($codigo[1]))
                    {
                         $query="INSERT INTO hc_solicitudes_insumos_d
                                                  (solicitud_id,
                                                  codigo_producto,
                                                  cantidad
                                                  )VALUES('$solicitud',
                                                       '".$codigo[0]."',
                                                       '".$codigo[1]."');";
                         $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "No se inserto en hc_solicitudes_insumos_d ";
                              $this->mensajeDeError = "Ocurri� un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                              $dbconn->RollbackTrans();
                              return false;
                         }
                    }
               }
          }   
          $dbconn->CommitTrans();
          $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
          $this->CallFrmMedicamentos($datosPaciente,$datos_estacion);
          return true;
     }
     
     
     /**
     *       InsertSolicitudMed
     *
     *       guarda o realiza la solicitud de los medicamentos.
     *
     *       @Author Jairo Duvan Diaz M.
     *       @access Public
     *       @return array, false � string
     *       @param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
     */
     function InsertSolicitudMed()
     {
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $bodega = $_REQUEST['bodega'];
          
          // Detalle de la solicitud
          $productos_detalle = $_SESSION['VECTOR_DETALLE_PRODUCTOS'];

          // Vector de Insumos
          $Seleccion = $_REQUEST['Seleccion'];          

          //Validacion de productos solicitados por la estacion a la bodega
          if(!is_array($productos_detalle))
          {
               $this->frmError["MensajeError"]="NO SE SELECCIONO NINGUN PRODUCTO PARA LA SOLICITUD DE MEDICAMENTOS.";
               $this->CallFrmMedicamentos($datosPaciente,$datos_estacion);
               return true;
          }
          
          list($dbconn) = GetDBconn();
          //$dbconn->debug = true;
          $dbconn->BeginTrans();
               
          $query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
          $res=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No se pudo traer la secuencia ";
               $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          $solicitud=$res->fields[0];
     
     
          $query="INSERT INTO hc_solicitudes_medicamentos
	                   (solicitud_id,
                         ingreso,
                         bodega,
                         empresa_id,
                         centro_utilidad,
                         usuario_id,
                         sw_estado,
                         fecha_solicitud,
                         estacion_id,
                         tipo_solicitud)
                        VALUES('$solicitud',
                              ".$datosPaciente[ingreso].",
                              '".$bodega."',
                              '".$datos_estacion[empresa_id]."',
                              '".$datos_estacion[centro_utilidad]."',
                              ".UserGetUID().",
                              '0',
                              '".date("Y-m-d H:i:s")."',
                              '".$datos_estacion[estacion_id]."',
                              'M')";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No se inserto en hc_solicitudes_medicamentos ";
               $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
     
          for($i=0;$i<sizeof($productos_detalle);$i++)
          {
               if($productos_detalle[$i]['evolucion_id'] == "")
               { $evolucion = 'NULL'; } else { $evolucion = $productos_detalle[$i]['evolucion_id']; }
               
               $query="INSERT INTO hc_solicitudes_medicamentos_d
                                   (solicitud_id,
                                    medicamento_id,
                                    evolucion_id,
                                    cant_solicitada,
                                    ingreso)
                                      VALUES('$solicitud',
                                             '".$productos_detalle[$i]['codigo']."',
                                             ".$evolucion.",
                                             ".$productos_detalle[$i]['cantidad'].",
                                             ".$productos_detalle[$i]['ingreso'].")";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos_d ";
                    $this->mensajeDeError = "Ocurri� un error al insertar la solicitud de medicamentos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

          //Cuando vienen insumos en la solicitud de medicamentos.
          
          if(is_array($_REQUEST['checo']) || is_array($Seleccion))
          {
                    //funcion que crea la solicitud de insumos automaticamente, cuando lo hacemos directamente desde 
                    //las solicitudes de medicamentos.
                    $query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
                    $res=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se pudo traer la secuencia ";
                         $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                         return false;
                    }
                    $solicitud=$res->fields[0];
                         
                    $query="INSERT INTO hc_solicitudes_medicamentos
                                             (solicitud_id,
                                             ingreso,
                                             bodega,
                                             empresa_id,
                                             centro_utilidad,
                                             usuario_id,
                                             sw_estado,
                                             fecha_solicitud,
                                             estacion_id,
                                             tipo_solicitud
                                             )VALUES('$solicitud',
                                                  ".$datosPaciente[ingreso].",
                                                  '".$bodega."',
                                                  '".$datos_estacion[empresa_id]."',
                                                  '".$datos_estacion[centro_utilidad]."',
                                                  ".UserGetUID().",
                                                  '0',
                                                  '".date("Y-m-d H:i:s")."',
                                                  '".$datos_estacion[estacion_id]."',
                                                  'I')";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                         $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
                         
                    if(is_array($_REQUEST['checo']))
                    {
                         for($r=0;$r<sizeof($_REQUEST['checo']);$r++)
                         {
                              $codigo=explode(",",$_REQUEST['checo'][$r]);
                              //[0]-> medicamento_id
                              //[1]-> codigo_producto o insumo_id
						$cantidad = $codigo[1] * $_REQUEST['Factor'][$r];
                              if(!empty($codigo[1]))
                              {
                                   $query="INSERT INTO hc_solicitudes_insumos_d
                                                       (solicitud_id,
                                                        codigo_producto,
                                                        cantidad
                                                       )VALUES('$solicitud',
                                                            '".$codigo[0]."',
                                                            '".$cantidad."')"; 
                                   $dbconn->Execute($query);
                                   if ($dbconn->ErrorNo() != 0)
                                   {
                                        $this->error = "No se inserto en hc_solicitudes_insumos_d ";
                                        $this->mensajeDeError = "Ocurri� un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                                        $dbconn->RollbackTrans();
                                        return false;
                                   }
                              }
                    	}
                    }
                    // Lo mismo realizado por lorena (Solicitud automatica de insumos).
                    if(is_array($Seleccion)){
                         foreach($Seleccion as $indice=>$valor){
                              if($valor!=-1){
                                   $dat_op=explode(",",$valor);
                                   $query="INSERT INTO hc_solicitudes_insumos_d
                                             (solicitud_id,
                                              codigo_producto,
                                              cantidad
                                             )VALUES('$solicitud',
                                                  '".$dat_op[0]."',
                                                  '".$cantidadesSol[$indice]."');";
               
                                   $dbconn->Execute($query);
                                   if ($dbconn->ErrorNo() != 0)
                                   {
                                        $this->error = "No se inserto en hc_solicitudes_medicamentos_d ";
                                        $this->mensajeDeError = "Ocurri� un error al insertar la solicitud de medicamentos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                                        $dbconn->RollbackTrans();
                                        return false;
                                   }
                         }
                    }
               }
          }
          // Vector que contiene el detalle de los productos de la solicitud
          unset($_SESSION['VECTOR_DETALLE_PRODUCTOS']);

          $dbconn->CommitTrans();
          $_REQUEST['grupo_tab'] = 2;
          $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
          $this->CallFrmMedicamentos($datosPaciente,$datos_estacion);
          return true;
    }
    /**
    *   Insertar_Recibido_Para_Pacientes
    *
    *   guarda o realiza la solicitud de los medicamentos para el paciente, 
    *    donde no se traen de bodega.
    *
    *   @Author Jairo Duvan Diaz M.
    *   @access Public
    *   @return array, false � string
    */
    function Insertar_Recibido_Para_Pacientes()
    {
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $cant = $_REQUEST['cantidad'];
          $cantidad_sol = $_REQUEST['cant_sol'];
          $area = $_REQUEST['area'];
          $nom = $_REQUEST['nom'];
          $codigo = $_REQUEST['codigo'];
          $solicitud = $_REQUEST['solicitud'];
          $data = $_REQUEST['data'];
     
          list($dbconn) = GetDBconn();
          $contador=0;
          $query="SELECT NEXTVAL('public.hc_recepcion_medicamentos_pacientes_recepcion_id_seq');";
          $res=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No se pudo traer la secuencia ";
               $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
     
          $recepcion=$res->fields[0];
     
          $query="INSERT INTO  hc_recepcion_medicamentos_pacientes
                    	(recepcion_id,
                         ingreso,
                         usuario_id,
                         fecha_recepcion,
                         estacion_id,
                         observaciones,
                         nombre_entrega
                         )VALUES('$recepcion',
                              ".$datosPaciente[ingreso].",
                              ".UserGetUID().",
                              '".date("Y-m-d H:i:s")."',
                              '".$datos_estacion[estacion_id]."',
                              '$area',
                              '$nom');";
          $dbconn->StartTrans();
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No se inserto en hc_solicitudes_medicamentos_pacientes ";
               $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
     
          for($w=0;$w<sizeof($data);$w++)
          {
               $e=explode(",",$data[$w]);
               if(!empty($e[0]))
               {
                    $solicitud=$e[0];
                    $codigo=$e[1];unset($e);
               }
     
               $cant=$_REQUEST['cantidad'][$w][$codigo];
               $cant_sol=$_REQUEST['cant_sol'][$w][$codigo];
               $cant_rec=$_REQUEST['cant_rec'][$w][$codigo];

               if(($cant)>0 and is_numeric($cant))
               {
                    $query="INSERT INTO hc_recepcion_medicamentos_pacientes_d
                                             (recepcion_id,
                                             codigo_producto,
                                             cantidad,
                                             ingreso
                                             )VALUES('$recepcion',
                                                  '".$codigo."',
                                                  $cant,
                                                  ".$datosPaciente[ingreso].")";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se inserto en hc_solicitudes_medicamentos_d ";
                         $this->mensajeDeError = "Ocurri� un error al insertar la solicitud de medicamentos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
                                             
                    $query= "SELECT SUM(z.cantidad) AS cantidad
                              FROM
                              hc_recepcion_medicamentos_pacientes x,
                              hc_recepcion_medicamentos_pacientes_d z
                              WHERE
                              x.ingreso = '".$datosPaciente[ingreso]."' AND
                              z.codigo_producto = '$codigo' AND
                              z.estado='0' AND
                              x.estacion_id = '".$datos_estacion[estacion_id]."'
                              AND x.recepcion_id=z.recepcion_id";
                    $result = $dbconn->Execute($query);
          
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al buscar en la consulta de medicamentos recetados";
                         $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
                              
                    if($result->fields[0] >= $cant_sol)
                    {
                         $query="UPDATE hc_solicitudes_medicamentos_pacientes_d
                                   SET  sw_estado='1' WHERE ingreso='".$datosPaciente[ingreso]."' 
                                   AND codigo_producto = '$codigo'";
                         $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al buscar en la consulta de medicamentos recetados";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                         //actualizamos la recepcion de med y cambiamos en 1 cuando ya hallamos cumplido
                         //con la solicitud
                         $sql="UPDATE hc_recepcion_medicamentos_pacientes_d 
                              SET estado='1' 
                              WHERE ingreso='".$datosPaciente[ingreso]."' 
                              AND codigo_producto = '$codigo'";
                         $dbconn->Execute($sql);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al buscar en la consulta de medicamentos recetados";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                    }
               }
               else{$contador++;}
          }
          if($contador==sizeof($data))
          {
               $dbconn->RollbackTrans();
               $this->frmError["MensajeError"]="LOS DATOS NO SE GUARDARON.";
          }else
          {
               $dbconn->CompleteTrans();
               $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
          }//termina la transaccion
          $this->Recibir_X_Para_Pacientes($datos_estacion,$datosPaciente,$codigo,$solicitud,$data);
          return true;
    }
     /*
     * Funcion de Cancelacion de Insumos / Medicamentos
     * Solicitados al paciente.
     */
     function Cancelar_Sol_X_Med_Pacientes()
     {
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $codigo=$_REQUEST['codigo_producto'];
          //$solicitud=$_REQUEST['solicitud'];//solicitud no va a llegar. ojo con eso...
     
          list($dbconn) = GetDBconn();
          $query="UPDATE hc_solicitudes_medicamentos_pacientes_d
                  SET sw_estado='2'
                  WHERE codigo_producto='$codigo'
                  AND ingreso='".$datosPaciente[ingreso]."'";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No se actualizo en hc_solicitudes_medicamentos_pacientes_d ";
               $this->mensajeDeError = "Ocurri� un error al insertar la solicitud de medicamentos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
                    
          $this->frmError["MensajeError"]="MEDICAMENTO CANCELADO SATISFACTORIAMENTE.";
          $this->CallFrmMedicamentos($datosPaciente,$datos_estacion);
          return true;
     }

     
     /*
     * Funcion de Cancelacion de Insumos / Medicamentos
     * Solicitados a las bodegas asociadas a la EE.
     */
     function CancelSolicitudMedicametos()
     {
          $matriz = $_REQUEST['matriz'];
          $bodega = $_REQUEST['bodega'];
          $SWITCHE = $_REQUEST['switche'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $obs = $_REQUEST['obs'];
          $spy = $_REQUEST['spia'];
     
          //este vector se puede utilizar en un futuro ya que viene la solicitud y el consecutivo
          //entonces lo dejamos alli mientras.
          $op=$_REQUEST['opcion'];
     
          list($dbconn) = GetDBconn();
          $dbconn->StartTrans();
          
          $acumulado = 0;
          for($i=0;$i<sizeof($matriz);$i++)
          {

               $QUERYSELECT = "SELECT solicitud_id 
               			 FROM hc_solicitudes_medicamentos
                               WHERE solicitud_id = '".$matriz[$i]."'
                               AND sw_estado IN ('1','5');";
               $resulta = $dbconn->Execute($QUERYSELECT);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                    $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
                
               if(empty($resulta->fields[0]))
               {
                    $query="UPDATE hc_solicitudes_medicamentos
                            SET sw_estado='3'
                            WHERE solicitud_id='".$matriz[$i]."'";
     
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                         $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
     
                    $query="INSERT INTO hc_auditoria_solicitudes_medicamentos
                            (fecha_registro,usuario_id,observacion,solicitud_id)
                            VALUES(now(),'".UserGetUID()."','$obs','".$matriz[$i]."');";
     
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "no se inserto en  hc_auditoria_solicitudes_medicamentos";
                         $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
			}
               else
               { $acumulado = 1; }
     	}
     	$dbconn->CompleteTrans();//termina la transaccion

          if($acumulado == 1)
          {
          	$comentario = "LA (O ALGUNA DE LAS) SOLICITUD (ES) YA HABIA (N) SIDO PREVIAMENTE DESPACHADA (S) <BR> 
               			POR LO TANTO NO FUE (RON) CANCELADA (S). <BR><BR>
                              SOLICITUD ATENDIDA SATISFACTORIAMENTE.";
          }
          else
          {
          	$comentario = "SOLICITUD CANCELADA SATISFACTORIAMENTE.";
          }
          
          if($spy==1)
          {
               $this->frmError["MensajeError"] = $comentario;
               $this->MedicamentosIns_X_Recibir($datos_estacion,$bodega,$SWITCHE);
               return true;
          }
          else
          {
               $this->frmError["MensajeError"] = $comentario;
               $this->CallFrmMedicamentos($datosPaciente,$datos_estacion);
               return true;
          }
     }

     /*
     * Funcion que permite mostrar los suministros q se le han 
     * realizado al paciente
     */
     function Consultar_Control_Suministro($codigo_producto, $ingreso, $tipo_solicitud)
     {
          list($dbconn) = GetDBconn();

          $_tableName = "";
          //Busqueda de suministros por tipos de producto (Medicamentos - Soluciones)
          if($tipo_solicitud == "M")
          {
            $_tableName = "hc_formulacion_suministro_medicamentos";
          }else{
            $_tableName = "hc_formulacion_suministro_soluciones";
          }
          
          // Todos los suministros del ingreso
          $query = "SELECT A.*, B.nombre
                    FROM  $_tableName AS A, 
                          system_usuarios AS B
                    WHERE A.ingreso = '".$ingreso."'
                    AND A.codigo_producto = '".$codigo_producto."'
                    AND A.sw_estado = '1'
                    AND A.usuario_id_control = B.usuario_id
                    ORDER BY suministro_id DESC;";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al consultar el medicamento";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
     }
    /*
    * Funcion que permite mostrar los suministros q se le han 
    * realizado al paciente
    */
    function Consultar_Control_SuministroInsumos($codigo_producto, $ingreso, $tipo_solicitud)
    {
      list($dbconn) = GetDBconn();

      $_tableName = "";
      //Busqueda de suministros por tipos de producto (Medicamentos - Soluciones)
      if($tipo_solicitud == "I")
        $_tableName = "hc_formulacion_suministro_medicamentos";
      else
        $_tableName = "hc_formulacion_suministro_soluciones";
      
      // Todos los suministros del ingreso
      $query = "SELECT A.*, B.nombre
                FROM  $_tableName AS A, 
                      system_usuarios AS B
                WHERE A.ingreso = '".$ingreso."'
                AND A.codigo_producto = '".$codigo_producto."'
                AND A.sw_estado = '1'
                AND A.usuario_id_control = B.usuario_id
                ORDER BY suministro_id DESC;";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al consultar el medicamento";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      else
      { 
        $i=0;
        while (!$result->EOF)
        {
          $vector[$i]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
          $i++;
        }
      }
      $result->Close();
      return $vector;
    }
     /*
     * Funcion que realiza la sumatoria de los suministros q se
     * le han realizado al paciente.
     */
     function SUMTotal_Suministro($codigo_producto,$ingreso)
     {  
          list($dbconn) = GetDBconn();
          $query = "SELECT (SUM(A.cantidad_suministrada) +
          			   SUM(A.cantidad_perdidas) + 
                            SUM(A.cantidad_aprovechada)) AS totalitario 
                    FROM hc_formulacion_suministro_medicamentos AS A,
                    	hc_formulacion_medicamentos AS B
                    WHERE A.codigo_producto = '".$codigo_producto."'
                    AND A.ingreso = ".$ingreso."
                    AND A.sw_estado = '1'
                    AND A.num_reg_formulacion = B.num_reg_formulacion;";
          
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al consultar el medicamento";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          list($total) = $result->FetchRow();
          return $total;
     }
     
     
     /*
     * Funcion que realiza la sumatoria de los suministros q se
     * le han realizado al paciente.
     */
     function SUMTotal_SuministroSoluciones($codigo_producto,$ingreso)
     {  
          list($dbconn) = GetDBconn();
          $query = "SELECT (SUM(A.cantidad_suministrada) +
                            SUM(A.cantidad_perdidas) + 
                            SUM(A.cantidad_aprovechada)) AS totalitario 
                    FROM hc_formulacion_suministro_soluciones AS A,
                    	hc_formulacion_mezclas AS B
                    WHERE A.codigo_producto = '".$codigo_producto."'
                    AND A.ingreso = ".$ingreso."
                    AND A.sw_estado = '1'
                    AND A.num_mezcla = B.num_mezcla;";
          
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al consultar el medicamento";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          list($total) = $result->FetchRow();
          return $total;
     }


     /**
     *   GetEstacionBodega
     *
     *   obtiene la estacion asociada a una bodega.
     *
     *   @Author Jairo Duvan Diaz M.
     *   @access Public
     *   @return array, false � string
     *   @param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
     */
     function GetEstacionBodega_Existencias($datos,$sw,$codigo)
     {
          if($sw==1)
          {
               $filtro="AND b.sw_consumo_directo='0'";
               $Order="ORDER BY sw_bodega_principal DESC";
          }
          elseif($sw==2)
          {
               $filtro="AND b.sw_consumo_directo='1'";
               $Order="ORDER BY sw_bodega_principal DESC";
          }
          elseif($sw==3)
          {
	          $filtro="AND (b.sw_consumo_directo = '1' OR b.sw_consumo_directo = '0')";
               $Order="ORDER BY sw_bodega_principal DESC";          
          }
          
          list($dbconn) = GetDBconn();
          $query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.descripcion,c.existencia
                    FROM bodegas_estaciones a,bodegas b,existencias_bodegas c
                    WHERE a.estacion_id='".$datos[estacion_id]."'
                    AND a.centro_utilidad=b.centro_utilidad
                    AND a.empresa_id=b.empresa_id
                    AND a.bodega=b.bodega
                    AND a.bodega=c.bodega
                    AND c.existencia > 0
                    AND (b.sw_restriccion_stock = '0' OR b.sw_restriccion_stock = '1')
                    $filtro
                    AND c.codigo_producto='$codigo'
                    AND a.empresa_id=c.empresa_id
                    AND a.centro_utilidad=c.centro_utilidad
                    AND a.centro_utilidad='".$datos[centro_utilidad]."'
                    AND a.empresa_id='".$datos[empresa_id]."'
                    $Order;";

               $result = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al buscar en la consulta de medicamentos recetados";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
     
          if($result->EOF)
          {
               return '';
          }
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
          $result->close();
          return $vector;
     }
     
     /**************************************************************************************
                    IMPLEMENTACION NUEVA VERSION BODEGA PACIENTE 
     **************************************************************************************/

     function BusquedaProducto_Soluciones($ingreso,$codigo_producto)
     {
          list($dbconn) = GetDBconn();
		$sql = "SELECT (A.cantidad * B.cantidad) AS total
          	   FROM hc_formulacion_mezclas AS A, 
                  	   hc_formulacion_mezclas_detalle AS B
                  WHERE A.ingreso = ".$ingreso."
                  AND A.sw_estado = '1'
                  AND A.num_mezcla = B.num_mezcla
                  AND B.codigo_producto = '".$codigo_producto."';";
          $resultado = $dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          return $resultado->fields[0];
     }
     
     function GetCantidades_BodegaPaciente($ingreso,$codigo_producto)
     {
          list($dbconn) = GetDBconn();
          //$dbconn->debug = true;
          GLOBAL $ADODB_FETCH_MODE;
          $sql="SELECT * 
	           FROM bodega_paciente
                WHERE ingreso = ".$ingreso."
                AND codigo_producto = '".$codigo_producto."';";
	     $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     	$_BodegaPaciente = $resultado->FetchRow();
          return $_BodegaPaciente;
     }
     
    function GetCantidades_BodegaPacienteEquivalente($ingreso,$codigo_producto)
    {
      list($dbconn) = GetDBconn();
      GLOBAL $ADODB_FETCH_MODE;
      //$dbconn->debug=true;    
      $sql  = "SELECT A.* ";
      $sql .= "FROM   bodega_paciente A, ";
      $sql .= "       ( ";
      $sql .= "         SELECT A.consecutivo_solicitud, ";
      $sql .= "                A.documento_despacho_id, ";
      $sql .= "                B.medicamento_id ";
      $sql .= "         FROM   bodegas_documento_despacho_med_d A, ";
      $sql .= "                hc_solicitudes_medicamentos_d B ";
      $sql .= "         WHERE  codigo_producto = '".$codigo_producto."' ";
      //$sql .= "         AND    documento_despacho_id = NEW.documento_despacho ";
      $sql .= "         AND    A.consecutivo_solicitud = B.consecutivo_d ";
      $sql .= "         AND    B.ingreso = ".$ingreso." ";
      $sql .= "       ) B ";
      $sql .= "WHERE A.ingreso = ".$ingreso." ";
      $sql .= "AND   A.codigo_producto = B.medicamento_id ";
      
	    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      $resultado = $dbconn->Execute($sql);
      $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al buscar en la consulta de medicamentos recetados";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
     	$_BodegaPaciente = $resultado->FetchRow();
      return $_BodegaPaciente;
    }
     
     function SuministrosMedicamentos_Formulacion($ingreso,$codigo)
     {
          list($dbconn) = GetDBconn();
          $query= "SELECT CASE WHEN SUM(a.cantidad_suministrada) ISNULL THEN 0 ELSE 
                    SUM(a.cantidad_suministrada) END FROM 
                    hc_control_suministro_medicamentos a,
                    hc_evoluciones b,
                    hc_medicamentos_recetados_hosp as c
                    WHERE
                    b.ingreso = ".$ingreso."
                    AND a.codigo_producto='".$codigo."'
                    AND a.evolucion_id=b.evolucion_id
                    AND b.ingreso = c.ingreso
                    AND a.evolucion_id = c.evolucion_id
                    AND a.codigo_producto = c.codigo_producto
                    AND c.sw_estado IN ('1','2','0')
                    $filtroEstacion";

          $result = $dbconn->Execute($query);
     
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
     
          $sumatoria_suministro = $result->fields[0];//sumatoria de devolucion.
          return $sumatoria_suministro;
     }
     
     function SumatoriasCantidades_Devolucion($ingreso, $estacion, $codigo, $evolucion)
     {
          list($dbconnect) = GetDBconn();
          $sql= "SELECT CASE WHEN SUM(b.cantidad) IS NULL THEN '0' 
                 ELSE SUM(b.cantidad) END
                    
                 FROM inv_solicitudes_devolucion a, 
                    inv_solicitudes_devolucion_d b, inventarios c, 
                    inventarios_productos d,
                    existencias_bodegas e,
                    hc_medicamentos_recetados_hosp hmr
                              
               WHERE a.empresa_id='".$estacion['empresa_id']."' 
               AND a.centro_utilidad='".$estacion['centro_utilidad']."' 
               AND a.ingreso='".$ingreso."'
               AND a.estacion_id='".$estacion['estacion_id']."'
               AND (a.estado='0' OR a.estado='1')
               AND a.documento=b.documento
               AND b.codigo_producto='$codigo'
               AND c.empresa_id=a.empresa_id 
               AND c.codigo_producto=b.codigo_producto 
               AND d.codigo_producto=b.codigo_producto 
               AND a.empresa_id=e.empresa_id 
               AND a.centro_utilidad=e.centro_utilidad 
               AND a.bodega=e.bodega 
               AND b.codigo_producto=e.codigo_producto
               AND hmr.evolucion_id = b.evolucion_id
               AND hmr.codigo_producto = b.codigo_producto
               AND hmr.sw_estado IN ('1','2','0');";

          $result = $dbconnect->Execute($sql);
                         
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          return $result->fields[0];//sumatoria de devolucion.;
     }
     
     function GetInformacionProductosBodegaPaciente_X_Recibir($ingreso)
     {
          list($dbconnect) = GetDBconn();
    		$query = "SELECT SUM(cantidad_pendiente_por_recibir)
                    FROM bodega_paciente
                    WHERE ingreso = ".$ingreso.";";
          $result = $dbconnect->Execute($query);
          if($result->fields[0] > 0)
          {
          	return '1';
          }else
          {
               return '0';
          }
     }
     
      function GetInformacionProductos_BodegaPaciente($ingreso, $filtro)
      {
        list($dbconnect) = GetDBconn();
        
        $query = "SELECT SUM(stock_almacen)
                    FROM bodega_paciente
                    WHERE ingreso = ".$ingreso."
                    AND sw_tipo_producto = '".$filtro."';";
        $result = $dbconnect->Execute($query);
        if($result->fields[0] > 0)
        {
         	return '1';
        }
        else
        {
         	return '0';
        }
      }
     
     /**************************************************************************************
                    IMPLEMENTACION NUEVA VERSION BODEGA PACIENTE 
     **************************************************************************************/

     /*
     * SeleccionUnidadSuministro
     *
     * Funcion que realiza la busqueda de las unidades que son iguales
     * en dosificacion y unidad de medida del medicamento.
     */
     function SeleccionUnidadSuministro($unidad_dosificacion, $unidad)
     {
          list($dbconn) = GetDBconn();
		  GLOBAL $ADODB_FETCH_MODE;
          
          $UnidadDos = "SELECT Count(*)
          		    FROM hc_formulacion_cruce_unidades
                        WHERE unidad_dosificacion = '".trim($unidad_dosificacion)."'
                        AND unidad_id = '".trim($unidad)."';";

     	$resultado = $dbconn->Execute($UnidadDos);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al seleccionar el Factor de conversion";
               $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          if($resultado->fields[0] > 0)
          { return 1; } else { return 0; }
     }
     
     
     /*
     * SeleccionFactorConversion
     *
     * Funcion que selecciona el factor de conversion de un medicamento
     * para su suministro en una unidad diferente
     */
     function SeleccionFactorConversion($codigo, $unidad, $unidad_dosificacion)
     {
          list($dbconn) = GetDBconn();
		  GLOBAL $ADODB_FETCH_MODE;
          
          $select_Fac = "SELECT * 
          			FROM hc_formulacion_factor_conversion
                         WHERE codigo_producto = '".$codigo."'
                         AND unidad_id = '".trim($unidad)."'
                         AND unidad_dosificacion = '".trim($unidad_dosificacion)."';";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($select_Fac);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al seleccionar el Factor de conversion";
               $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          $factor = $resultado->FetchRow();
     	return $factor;
     }

          
     //Funcion q carga a la cuenta los medicamentos consumidos por el paciente.
     //funcion que inserta los suministros a un determinado paciente
    function InsertarSuministroPaciente($datosPaciente, $datos_estacion, $vect, $bodega, $tipo_solicitud, $fecha_realizado, $ProductosSUM, $CantidadesSUM, $ingreso_F, $checo, $num_F, $perdidas, $cantidad_recetada, $factorC,$ing=false)
    {
      list($dbconn) = GetDBconn();
      //$dbconn->debug = true;
      $dbconn->StartTrans();//inicia la transaccion
          
      // Para casos de consumo directo.
      if($bodega !="*/*")
      {
               //Creamos la solicitud Automaticamente, con sus respectivas cantidades.
               $query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
               $res=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se pudo traer la secuencia ";
                    $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
               
               $solicitud=$res->fields[0];
               
               $BodegaConsumo = explode(",",$bodega);
               $query="INSERT INTO hc_solicitudes_medicamentos
                              (    solicitud_id,
                                   ingreso,
                                   bodega,
                                   empresa_id,
                                   centro_utilidad,
                                   usuario_id,
                                   sw_estado,
                                   fecha_solicitud,
                                   estacion_id,
                                   tipo_solicitud
                              )VALUES(  '$solicitud',
                                        ".$datosPaciente[ingreso].",
                                        '".$BodegaConsumo[1]."',
                                        '".$datos_estacion[empresa_id]."',
                                        '".$datos_estacion[centro_utilidad]."',
                                        ".UserGetUID().",
                                        '4',
                                        '".date("Y-m-d H:i:s")."',
                                        '".$datos_estacion[estacion_id]."',
                                        'M');";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                    $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
               
               $lenght = strlen($factorC[0]);
               $divisor = 0;
               if($lenght == 1) 
               {
                $divisor = 10;
               }
               elseif($lenght == 2) 
               {
                $divisor = 100;
               }
               elseif($lenght == 3) 
               {
                $divisor = 1000;
               }
               elseif($lenght == 4) 
               {
                $divisor = 10000;
               }
               
               if($divisor)
               {
                $tmp_factor = (int)$factorC[0]/(int)$divisor;
               }

               for($i=0;$i<sizeof($ProductosSUM);$i++)
               {
                    if(!empty($CantidadesSUM[$i]) OR !empty($perdidas[$i]))
                    {
                        $CantidadSol = $CantidadesSUM[$i] + $perdidas[$i];

                        if($divisor)
                        {
                          if(($tmp_factor/$CantidadSol) <> 1)
                          {
                           //$CantidadSol = $CantidadSol/$divisor;
                           $CantidadSol = $CantidadSol/$factorC[0];
                          }
                          else
                          {
                           $CantidadSol = $CantidadSol;
                          }
                        }

                         $query="INSERT INTO hc_solicitudes_medicamentos_d
                                        ( solicitud_id,
                                             medicamento_id,
                                             cant_solicitada,
                                             evolucion_id,
                                             ingreso
                                        )VALUES(  '$solicitud',
                                                  '".$ProductosSUM[$i]."',
                                                  '".$CantidadSol."',
                                                  NULL,
                                                  ".$ingreso_F[$i]."
                                        );";
                         $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "No se inserto en hc_solicitudes_insumos_d ";
                              $this->mensajeDeError = "Ocurri� un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                              echo $this->mensajeDeError."[" . get_class($this) . "][" . __LINE__ . "]";
                              $dbconn->RollbackTrans();
                              return false;
                         }
                    }
               }
               
               $query="SELECT plan_id, numerodecuenta 
                       FROM cuentas WHERE ingreso='".$datosPaciente[ingreso]."'
                       AND estado = '1'";
               $result=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se encontro plan ni cuenta  ";
                    $this->mensajeDeError = "Ocurri� un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
               
               //termina la transaccion 
               $dbconn->CompleteTrans();

               // Va a Inventarios Bodega y realiza la Transaccion y Despacho.
               unset($_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']);      
               $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['SOLICITUD']=$solicitud;
               $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['CUENTA']=$result->fields[1];
               $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['PLAN']=$result->fields[0];
          	$this->ReturnMetodoExterno('app','InvBodegas','user','DespachoMyIAutomatico');
               $VALOR = $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['RETORNO'];

               if($VALOR == '1')//si retrona 4 es por que esta bien
               { 
                    $this->error = $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['Mensaje'];
                    $this->mensajeDeError = "Ocurri� un error al insertar la solicitud de insumos<br>".$query;
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]= $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['Mensaje'];
                    $this->Control_Suministro();
                    return true;
               }
			unset($_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']);
      }
/************************ INSERTAR EN formulacion_suministros para control**********/

          if($bodega !="*/*")
          {
          	$id_consumo = "1";
          }else
          {
          	$id_consumo = "0";
          }
          
          for($i=0;$i<sizeof($ProductosSUM);$i++)
          {
               //Parametros para insercion de productos.
               $_tableName = $_constraint = "";
               if($tipo_solicitud == "M")
               {
                    $_tableName = "hc_formulacion_suministro_medicamentos";
                    $_constraint = "num_reg_formulacion";
               }else{
                    $_tableName = "hc_formulacion_suministro_soluciones";
                    $_constraint = "num_mezcla";
               }
               
               is_array($_REQUEST['observacion_suministro'])?$obs=$_REQUEST['observacion_suministro'][$i]:$obs=$_REQUEST['observacion_suministro'];
               
               if(!$obs)
               {
                    $observacion = " ";
               }else{
                    $observacion = $obs;
               }
               
               //Asignacion para Insercion.
               if(!$perdidas[$i])
               { $perdidas[$i] = 0; }
               
               if(!$aprovechamiento[$i])
               { $aprovechamiento[$i] = 0; }
               
          	if(!$CantidadesSUM[$i])
               { $CantidadesSUM[$i] = 0; }
              
               
               if(($CantidadesSUM[$i] > 0) OR ($perdidas[$i] > 0))
               {
                    // Asignacion Propia para descuento de BodegaPaciente
                    if($factorC[$i] AND $CantidadesSUM[$i] > 0)
                    {
                         $ValorRealS = "";
                         $ValorRealS = (($CantidadesSUM[$i] * 100) / $factorC[$i]);
                         $ValorRealS = $ValorRealS / 100;
                         $CantidadesSUM[$i] = $ValorRealS;
                    }

                    if($factorC[$i] AND $perdidas[$i] > 0)
                    {
                         $ValorRealS = "";
                         $ValorRealS = (($perdidas[$i] * 100) / $factorC[$i]);
                         $ValorRealS = $ValorRealS / 100;
                         $perdidas[$i] = $ValorRealS;
                    }
                    
                    $sql= "INSERT INTO  $_tableName
                                        (  
                                             ingreso,
                                             codigo_producto,  
                                             usuario_id_control,
                                             fecha_realizado,
                                             fecha_registro_control,  
                                             cantidad_suministrada,
                                             cantidad_perdidas,
                                             cantidad_aprovechada,
                                             estacion_id,
                                             observacion,
                                             $_constraint,
                                             sw_id_consumo) 
                                   VALUES(
                                             ".$datosPaciente[ingreso].",
                                             '".$ProductosSUM[$i]."',
                                             '".UserGetUID()."',
                                             '".$fecha_realizado[$i]."',
                                             '".date("Y-m-d H:i:s")."',
                                             '".$CantidadesSUM[$i]."',
                                             '".$perdidas[$i]."',
                                             '".$aprovechamiento[$i]."',
                                             '".$datos_estacion[estacion_id]."',
                                             '".$observacion."',
                                             ".$num_F[$i].",
                                             '".$id_consumo."')";
					    
                    $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar hc_control_suministro_medicamentos";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
          }

/************************ INSERTAR EN formulacion_suministros para control**********/

/********************************** Finalizacion del Medicamento *******************/
          unset($total_sumistrado);
          $acumulado = 0;
          for($i=0;$i<sizeof($ProductosSUM);$i++)
          {
               if($tipo_solicitud == "M")
               {
                    $total_sumistrado = $this->SUMTotal_Suministro($ProductosSUM[$i], $datosPaciente[ingreso]);
                    $total_sumistrado = round($total_sumistrado , 2);
                    $Receta = round($cantidad_recetada[$i] , 2);
                    /*if((($_REQUEST['BodegaPaciente'][$i]-($CantidadesSUM[$i]+$perdidas[$i])))==0 and $total_sumistrado >= $Receta)*/
					if($_REQUEST['BodegaPaciente'][$i]==0 and $total_sumistrado >= $Receta)
                    {
                         $this->Finalizar_Medicamentos($ProductosSUM[$i], $ingreso_F[$i], $tipo_solicitud, '');
                         $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array('datosPaciente'=>$datosPaciente,'datos_estacion'=>$datos_estacion));
                         $titulo = 'MEDICAMENTO FINALIZADO';
                         $mensaje = 'El Medicamento Fue Finalizado';
                         $link = 'VOLVER';
                         $this->frmMSG($url,$titulo,$mensaje,$link);
                         return true;
                    }
               }else
               {
                    $total_sumistrado = $this->SUMTotal_SuministroSoluciones($ProductosSUM[$i], $datosPaciente[ingreso]);
                    $total_sumistrado = round($total_sumistrado , 2);
                    $Receta = round($cantidad_recetada[$i] , 2);
                    if($total_sumistrado >= $Receta)
                    {
                         $acumulado = $acumulado + 1;
                         if($acumulado == sizeof($ProductosSUM))
                         {
                              $this->Finalizar_Medicamentos('', $ingreso_F[$i], $tipo_solicitud, $num_F[$i]);
                              $url = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array('datosPaciente'=>$datosPaciente,'datos_estacion'=>$datos_estacion));
                              $titulo = 'SOLUCION FINALIZADA';
                              $mensaje = 'La Solucion Fue Finalizada';
                              $link = 'VOLVER';
                              $this->frmMSG($url,$titulo,$mensaje,$link);
                              return true;
                         }
                    }
               }
          }
          
/********************************** Finalizacion del Medicamento *******************/
          unset($_REQUEST['cantidad_suministrada']);
          unset($_REQUEST['aprovechamiento']);
          unset($_REQUEST['perdidas']);

          $dbconn->CompleteTrans();   //termina la transaccion 
	  if($ing==true){
	    $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
	    $this->Control_Suministro($datos_estacion,$datosPaciente,$tipo_solicitud);
	  }
          return true;
     }
    /**
    * Funcion q carga a la cuenta los insumos consumidos por el paciente.
    * funcion que inserta los suministros insumos a un determinado paciente
    */
    function InsertarSuministroInsumosPaciente($datosPaciente, $datos_estacion, $vect, $bodega, $tipo_solicitud, $fecha_realizado, $ProductosSUM, $CantidadesSUM, $ingreso_F, $checo, $num_F, $perdidas, $cantidad_recetada, $factorC,$ing=false)
    {
			
          list($dbconn) = GetDBconn();
          
          $dbconn->StartTrans();//inicia la transaccion
          // INSERTAR EN formulacion_suministros para control

          if($bodega !="*/*")
          {
          	$id_consumo = "1";
          }else
          {
          	$id_consumo = "0";
          }
          
          for($i=0;$i<sizeof($ProductosSUM);$i++)
          {
               //Parametros para insercion de productos.
               $_tableName = $_constraint = "";
			/// Cambio Carlos Renteria para que los insumos vayan a la tabla respectiva.
               if($tipo_solicitud == "I")
               {
					$_tableName = "hc_formulacion_suministro_medicamentos";
                    $_constraint = "num_reg_formulacion";
               }else{
					$_tableName = "hc_formulacion_suministro_soluciones";
                    $_constraint = "num_mezcla";
               }
               
                is_array($_REQUEST['observacion_suministro'])?$obs=$_REQUEST['observacion_suministro'][$i]:$obs=$_REQUEST['observacion_suministro'];
               
               if(!$obs)
               {
                    $observacion = " ";
               }else{
                    $observacion = $obs;
               } 
               
               //Asignacion para Insercion.
               if(!$perdidas[$i])
               { $perdidas[$i] = 0; }
               
               if(!$aprovechamiento[$i])
               { $aprovechamiento[$i] = 0; }
               
          	if(!$CantidadesSUM[$i])
               { $CantidadesSUM[$i] = 0; }
              
               
               if(($CantidadesSUM[$i] > 0) OR ($perdidas[$i] > 0))
               {
                    // Asignacion Propia para descuento de BodegaPaciente
                    if($factorC[$i] AND $CantidadesSUM[$i] > 0)
                    {
                         $ValorRealS = "";
                         $ValorRealS = (($CantidadesSUM[$i] * 100) / $factorC[$i]);
                         $ValorRealS = $ValorRealS / 100;
                         $CantidadesSUM[$i] = $ValorRealS;
                    }

                    if($factorC[$i] AND $perdidas[$i] > 0)
                    {
                         $ValorRealS = "";
                         $ValorRealS = (($perdidas[$i] * 100) / $factorC[$i]);
                         $ValorRealS = $ValorRealS / 100;
                         $perdidas[$i] = $ValorRealS;
                    }
                    
                     $sql= "INSERT INTO  $_tableName
                                        (  
                                             ingreso,
                                             codigo_producto,  
                                             usuario_id_control,
                                             fecha_realizado,
                                             fecha_registro_control,  
                                             cantidad_suministrada,
                                             cantidad_perdidas,
                                             cantidad_aprovechada,
                                             estacion_id,
                                             observacion,
                                             sw_id_consumo
                                        ) 
                                   VALUES(
                                             ".$datosPaciente[ingreso].",
                                             '".$ProductosSUM[$i]."',
                                             '".UserGetUID()."',
                                             '$fecha_realizado',
                                             '".date("Y-m-d H:i:s")."',
                                             '".$CantidadesSUM[$i]."',
                                             '".$perdidas[$i]."',
                                             '".$aprovechamiento[$i]."',
                                             '".$datos_estacion[estacion_id]."',
                                             '".$observacion."',
                                             '".$id_consumo."')";
		    
		    error_log(print_r($sql,true));
                    $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al insertar hc_control_suministro_medicamentos";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
          }
	  
          unset($_REQUEST['cantidad_suministrada']);
          unset($_REQUEST['aprovechamiento']);
          unset($_REQUEST['perdidas']);
      
          $dbconn->CompleteTrans();   //termina la transaccion
	  if($ing==true){
	    $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
	    $this->Control_SuministroInsumos($datos_estacion,$datosPaciente,$tipo_solicitud);
	  }
	  //return $sql;
          return true;
    }
     
     /*
     * Esta funcion permite anular suministros de medicamentos realizados al paciente
     *
     * Adaptacion Tizziano Perea
     */
     function AnularSuminitrosPaciente()
     {
          $Suministro_id = $_REQUEST['suministro'];
          $Observacion = $_REQUEST['observacion'];
          $tipo_solicitud =  $_REQUEST['tipo_solicitud'];
          
          if(empty($Observacion) OR $Observacion == "")
          {
               $this->frmError["MensajeError"]="DEBE HABER UNA JUSTIFICACION PARA LA ANULACION DEL SUMINISTRO.";
               $this->Control_Suministro($_REQUEST['datos_estacion'],$_REQUEST['datosPaciente'],$_REQUEST['tipo_solicitud']);
               return true;
          }
          
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          
          if($tipo_solicitud == "M")
          {
	          $query = "INSERT INTO hc_formulacion_suministro_medicamentos_auditoria
                                                ( suministro_id,
                                                  fecha_registro,
                                                  usuario_id,
                                                  observacion )
                                        VALUES  (".$Suministro_id.",
                                                  'NOW()',
                                                  ".UserGetUID().",
                                                  '".$Observacion."');";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al actualizar la observacion en hc_formulacion_medicamentos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               else
               {
                    $queryU = "UPDATE hc_formulacion_suministro_medicamentos
                               SET sw_estado = '0'
                               WHERE suministro_id = $Suministro_id;";
                    $dbconn->Execute($queryU);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al actualizar la observacion en hc_formulacion_medicamentos";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
          }else{
               $query = "INSERT INTO hc_formulacion_suministro_soluciones_auditoria
                                                ( suministro_id,
                                                  fecha_registro,
                                                  usuario_id,
                                                  observacion )
                                        VALUES  (".$Suministro_id.",
                                                  'NOW()',
                                                  ".UserGetUID().",
                                                  '".$Observacion."');";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al actualizar la observacion en hc_formulacion_medicamentos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               else
               {
                    $queryU = "UPDATE hc_formulacion_suministro_soluciones
                               SET sw_estado = '0'
                               WHERE suministro_id = ".$Suministro_id.";";
                    $dbconn->Execute($queryU);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al actualizar la observacion en hc_formulacion_medicamentos";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
          }
          
          $dbconn->CommitTrans();
          $this->frmError["MensajeError"]="EL REGISTRO DEL SUMINISTRO SE ANULO SATISFACTORIAMENTE.";
          $this->Control_Suministro($_REQUEST['datos_estacion'],$_REQUEST['datosPaciente'],$_REQUEST['tipo_solicitud']);        return true;
     }
     /*
	@Autor: Carlos Renteria
	@Fecha: 02/11/2012
	@Descripcion: Esta funcion permite anular los insumos del paciente.
	*/	 
     function AnularSuminitrosInsumosPaciente()
     {
/* 			echo '<pre>';
			print_r($_REQUEST);
			echo '</pre>'; */
		  $Suministro_id = $_REQUEST['suministro'];
          $Observacion = $_REQUEST['observacion'];
          $tipo_solicitud =  $_REQUEST['tipo_solicitud'];
          
          if(empty($Observacion) OR $Observacion == "")
          {
               $this->frmError["MensajeError"]="DEBE HABER UNA JUSTIFICACION PARA LA ANULACION DEL SUMINISTRO.";
               $this->Control_Suministro($_REQUEST['datos_estacion'],$_REQUEST['datosPaciente'],$_REQUEST['tipo_solicitud']);
               return true;
          }
          
          list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          
          if($tipo_solicitud == "M")
          {
	          $query = "INSERT INTO hc_formulacion_suministro_medicamentos_auditoria
                                                ( suministro_id,
                                                  fecha_registro,
                                                  usuario_id,
                                                  observacion )
                                        VALUES  (".$Suministro_id.",
                                                  'NOW()',
                                                  ".UserGetUID().",
                                                  '".$Observacion."');";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al actualizar la observacion en hc_formulacion_medicamentos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               else
               {
                    $queryU = "UPDATE hc_formulacion_suministro_medicamentos
                               SET sw_estado = '0'
                               WHERE suministro_id = $Suministro_id;";
                    $dbconn->Execute($queryU);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al actualizar la observacion en hc_formulacion_medicamentos";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
          }else{
               $query = "INSERT INTO hc_formulacion_suministro_soluciones_auditoria
                                                ( suministro_id,
                                                  fecha_registro,
                                                  usuario_id,
                                                  observacion )
                                        VALUES  (".$Suministro_id.",
                                                  'NOW()',
                                                  ".UserGetUID().",
                                                  '".$Observacion."');";
												  
												  
				//echo $query.'<br>';
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al actualizar la observacion en hc_formulacion_medicamentos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               else
               {
                    $queryU = "UPDATE hc_formulacion_suministro_medicamentos
                               SET sw_estado = '0'
                               WHERE suministro_id = ".$Suministro_id.";";

                    //echo $queryU;
					$dbconn->Execute($queryU);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al actualizar la observacion en hc_formulacion_medicamentos";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
          }
          
          $dbconn->CommitTrans();
          $this->frmError["MensajeError"]="EL REGISTRO DEL SUMINISTRO SE ANULO SATISFACTORIAMENTE.";
          $this->Control_SuministroInsumos($_REQUEST['datos_producto'],$_REQUEST['datos_estacion'],$_REQUEST['datosPaciente'],$_REQUEST['tipo_solicitud']);        
		  //"bandera"=>0
		  return true;
	 
	 }
	 
     /*
     * Esta funcion permite finalizar los medicamentos del paciente
     * cuando su consumo se halla totalizado.
     *
     * Adaptacion Tizziano Perea
     */
     function Finalizar_Medicamentos($Medicamento,$ingreso,$tipo_solicitud,$Mezcla)
     {
          list($dbconn) = GetDBconn();
          
          $dbconn->BeginTrans();
          
          if($tipo_solicitud == "M")
          {
               $query = "INSERT INTO hc_formulacion_medicamentos_eventos ( ingreso,
                                                                           codigo_producto,
                                                                           evolucion_id,
                                                                           usuario_id,
                                                                           fecha_registro,
                                                                           sw_estado,
                                                                           observacion,
                                                                           via_administracion_id,
                                                                           unidad_dosificacion,
                                                                           dosis,
                                                                           frecuencia,
                                                                           cantidad,
                                                                           usuario_registro,
                                                                           dias_tratamiento)
                    SELECT ingreso,codigo_producto,evolucion_id,usuario_id,now(),
                           '8','Finalizacion del Suministro desde la Estacion de Enfermeria',
                           via_administracion_id,unidad_dosificacion,dosis,frecuencia,cantidad,
                           ".UserGetUID().",dias_tratamiento
                         FROM hc_formulacion_medicamentos_eventos
                         WHERE codigo_producto = '".$Medicamento."'
                         AND ingreso = ".$ingreso."
                         ORDER BY num_reg DESC
                         LIMIT 1 OFFSET 0;";
          }
          else{
               $query = "INSERT INTO hc_formulacion_mezclas_eventos (      num_mezcla,
                                                                           ingreso,
                                                                           evolucion_id,
                                                                           usuario_id,
                                                                           fecha_registro,
                                                                           sw_estado,
                                                                           observacion,
                                                                           volumen_infusion,
                                                                           unidad_volumen,
                                                                           cantidad  )
                    SELECT num_mezcla,ingreso,evolucion_id,".UserGetUID().",now(),
                           '8','Finalizacion del Suministro desde la Estacion de Enfermeria',
                           volumen_infusion,unidad_volumen,cantidad
                    FROM hc_formulacion_mezclas_eventos
                    WHERE num_mezcla = '".$Mezcla."'
                    AND ingreso = ".$ingreso."
                    ORDER BY num_reg DESC
                    LIMIT 1 OFFSET 0;";
          }
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Finalizar la solucion o medicamento";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          $dbconn->CommitTrans();
          return true;
	}
	
     
     /*$num es el numero de opcion que escogio en el combo */
     /*$busca es la busqueda*/
     function GetFiltro($num,$busca)
     {
          switch($num)
          {
               case "1":
               {
                    $buscar = trim($busca);
                    if(is_numeric($buscar))
                    {
                         $filtro="AND a.codigo_producto like '%".$buscar."%'";
                    }
                    else
                    {
                         $filtro="";
                    }
                    break;
               }
               case "2":
               {
               $buscar = strtolower(trim($busca));
                    if(!empty($buscar))
                    {
                         $filtro="AND lower(b.descripcion) like '%".$buscar."%'";
                    }
                    break;
               }
          }
          return $filtro;
	}
     
     //trae los insumos de la tabla inventarios
    function GetInsumos($bodega,$filtro,$cod,$sw,$datos_estacion)
    {
      $this->limit=GetLimitBrowser();
      list($dbconn) = GetDBconn();
          
      if($bodega=='*/*')
      {   $filtro_bodega="";}else{$filtro_bodega=" AND a.bodega='$bodega' AND a.centro_utilidad='".$datos_estacion['centro_utilidad']."' AND a.empresa_id='".$datos_estacion['empresa_id']."'" ;}
          
          $filtro_existencia = " AND a.existencia > 0 ";
		  if(empty($_REQUEST['conteo']))
      {
          $query = "SELECT b.descripcion,b.descripcion_abreviada,producto_id,a.codigo_producto, a.existencia
                    FROM
                    existencias_bodegas a,
                    inventarios_productos b,
                    inv_grupos_inventarios c,
					inv_clases_inventarios d
                    WHERE 
                    a.codigo_producto=b.codigo_producto
                    AND c.grupo_id=b.grupo_id
                    AND c.sw_insumos='1'
					AND d.grupo_id = b.grupo_id
					AND d.clase_id = b.clase_id
                    AND a.estado='1'
                    AND b.estado='1'
					AND d.sw_tipo_empresa = '1'
                    $filtro_bodega
					$filtro_existencia
                    $filtro";

          $result = $dbconn->Execute($query);
          list($this->conteo)=$result->RecordCount();
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $this->conteo=$result->RecordCount();
          }else{
          	$this->conteo=$_REQUEST['conteo'];
     	}
     	if(!$_REQUEST['Of']){
               $Of='0';
	     }else{
     	     $Of=$_REQUEST['Of'];
     	}
     
          if($bodega=="-1" OR empty($bodega))
          {
               return '';
          }
          
          if($cod != 0)
          {
	          $filtro_temp = "AND a.codigo_producto = '".$cod."'";
               $distinct = "DISTINCT";
               $limites = "";
          }else
          { $distinct = "";}
          
          if(empty($filtro) AND $sw == 0){ $limites = "LIMIT " . $this->limit . " OFFSET $Of";}
          elseif(empty($filtro) AND $sw == 1){$limites = "";}

          $query="SELECT $distinct fc_descripcion_producto(a.codigo_producto) as descripcion,b.descripcion_abreviada,producto_id,a.codigo_producto, a.existencia
                  FROM
                         existencias_bodegas a,
                         inventarios_productos b,
                         inv_grupos_inventarios c,
						 inv_clases_inventarios d
                    WHERE 
                         a.codigo_producto=b.codigo_producto
                         AND c.grupo_id=b.grupo_id
                         AND c.sw_insumos='1'
                         AND d.grupo_id = b.grupo_id
						 AND d.clase_id = b.clase_id
                         AND a.estado='1'
                         AND b.estado='1'
					     $filtro_bodega
						 $filtro_existencia
                         $filtro
                         $filtro_temp
                         
					UNION	 
					SELECT $distinct fc_descripcion_producto(a.codigo_producto) as descripcion,b.descripcion_abreviada,producto_id,a.codigo_producto, a.existencia
                  FROM
                         existencias_bodegas a,
                         inventarios_productos b,
                         inv_grupos_inventarios c,
						 inv_clases_inventarios d
                    WHERE 
                         a.codigo_producto=b.codigo_producto
                         AND c.grupo_id=b.grupo_id
                         AND b.tipo_producto_id = '4'
                         AND d.grupo_id = b.grupo_id
						 AND d.clase_id = b.clase_id
                         AND a.estado='1'
                         AND b.estado='1'
					     $filtro_bodega
						 $filtro_existencia
                         $filtro
                         $filtro_temp
                         $limites;";
     
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
          }
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
          return $vector;
     }//
    /**
    * esta funcion genera la solicitud de insumos 
    */
    function InsertarInsumosPaciente()
    {
          $datos_estacion = $_REQUEST["datos_estacion"];
          $datosPaciente = $_REQUEST["datosPaciente"];
               $bodega = $_REQUEST['bodega'];
          $op = $_REQUEST['op'];
          $cant = $_REQUEST['cant'];
     
          list($dbconn) = GetDBconn();
          
          if (!empty($_SESSION['EXISTENCIA']))
          {
               $query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
               $res=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se pudo traer la secuencia ";
                    $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
     
               $solicitud=$res->fields[0];
     
               $query="INSERT INTO hc_solicitudes_medicamentos
                              ( solicitud_id,
                                   ingreso,
                                   bodega,
                                   empresa_id,
                                   centro_utilidad,
                                   usuario_id,
                                   sw_estado,
                                   fecha_solicitud,
                                   estacion_id,
                                   tipo_solicitud
                                   )VALUES('$solicitud',
                                        ".$datosPaciente[ingreso].",
                                        '".$bodega."',
                                        '".$datos_estacion[empresa_id]."',
                                        '".$datos_estacion[centro_utilidad]."',
                                        ".UserGetUID().",
                                        '0',
                                        '".date("Y-m-d H:i:s")."',
                                        '".$datos_estacion[estacion_id]."',
                                        'I')";
               $dbconn->StartTrans();
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                    $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
     
               for($i=1;$i<=sizeof($_SESSION['EXISTENCIA']);$i++)                           
               {
                    foreach($_SESSION['EXISTENCIA'][$i] as $index=>$valor)
                    {
                         $dat_op=explode("*",$_SESSION['EXISTENCIA'][$i][$index]);
                         $query="INSERT INTO hc_solicitudes_insumos_d
                                        ( solicitud_id,
                                             codigo_producto,
                                             cantidad
                                        )VALUES('$solicitud',
                                             '".$dat_op[0]."',
                                             '".$dat_op[1]."')";
                         
                         $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "No se inserto en hc_solicitudes_insumos_d ";
                              $this->mensajeDeError = "Ocurri� un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                              $dbconn->RollbackTrans();
                              return false;
                         }
                    }
               }
               $dbconn->CompleteTrans();   //termina la transaccion
          }
          else
          {
               $this->frmError["MensajeError"]="DEBE SELECCIONAR LOS INSUMOS QUE SERAN SOLICITADOS.";
               $this->AgregarInsumos_A_Paciente($datos_estacion,$datosPaciente);
               return true;
          }
     
          unset($_SESSION['EXISTENCIA']);
          unset($_SESSION['codigos_I']);
          unset($_SESSION['cantidad_a_perdi_sol_I']);
          
          $_REQUEST['grupo_tab'] = 2;
          $this->frmError["MensajeError"]="INSUMOS SOLICITADOS SATISFACTORIAMENTE.";
          $this->CallFrmMedicamentos($datosPaciente,$datos_estacion);
          return true;
    }
    //funcion para insertar las solicitudes de insumos para pacientes, por ejemplo el paciente xxx
    //se consigue los insumos por fuera de la clinica
    function Insertar_Solicitud_Insumos_Para_Paciente()
    {
          $datos_estacion = $_REQUEST["datos_estacion"];
          $datosPaciente = $_REQUEST["datosPaciente"];
          $bodega = $_REQUEST['bodega'];
          $op = $_REQUEST['op'];
          $cant = $_REQUEST['cant'];
          $area = $_REQUEST['area'];
          $nom = $_REQUEST['nom'];
          $nom = $_SESSION['MEDICA_DATOS_SOL_PAC']['SOL_PAC_NOM'];
          $area = $_SESSION['MEDICA_DATOS_SOL_PAC']['SOL_PAC_AREA'];
     
          list($dbconn) = GetDBconn();
          
          if (!empty($_SESSION['EXISTENCIA']))
          {
               $query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_pacientes_solicitud_id_seq');";
               $res=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se pudo traer la secuencia ";
                    $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
     
               $solicitud=$res->fields[0];
     
               $query="INSERT INTO hc_solicitudes_medicamentos_pacientes
                                   (solicitud_id,
                                   ingreso,
                                   usuario_id,
                                   sw_estado,
                                   fecha_solicitud,
                                   observaciones,
                                   nombre_recibe_solicitud,
                                   estacion_id,
                                   tipo_solicitud
                                   )VALUES('$solicitud',
                                             ".$datosPaciente[ingreso].",
                                             ".UserGetUID().",
                                             '0',
                                             '".date("Y-m-d H:i:s")."',
                                             '$area',
                                             '$nom',
                                             '".$datos_estacion[estacion_id]."',
                                             'I');";
               $dbconn->StartTrans();
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No (x) se inserto en hc_solicitudes_medicamentos_pacientes ";
                    $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
                                        
               for($i=1;$i<=sizeof($_SESSION['EXISTENCIA']);$i++)                           
               {            
                    foreach($_SESSION['EXISTENCIA'][$i] as $index=>$valor)
                    {
                         $dat_op=explode("*",$_SESSION['EXISTENCIA'][$i][$index]);
                         $query="INSERT INTO hc_solicitudes_medicamentos_pacientes_d
                                             (solicitud_id,
                                             codigo_producto,
                                             cantidad,
                                             ingreso
                                             )VALUES('$solicitud',
                                                       '".$dat_op[0]."',
                                                       '".$dat_op[1]."',
                                                       ".$datosPaciente[ingreso]."
                                                  )";
                         $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "No se inserto en hc_solicitudes_insumos_d ";
                              $this->mensajeDeError = "Ocurri� un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                              $dbconn->RollbackTrans();
                              return false;
                         }
                    }
               }
               $dbconn->CompleteTrans();   //termina la transaccion
          }else
          {
               $this->frmError["MensajeError"]="DEBE SELECCIONAR LOS INSUMOS QUE SERAN SOLICITADOS.";
               $this->AgregarInsumos_A_Paciente($datos_estacion,$datosPaciente);
               return true;
          }
          
          unset($_SESSION['EXISTENCIA']);
          unset($_SESSION['codigos_I']);
          unset($_SESSION['cantidad_a_perdi_sol_I']);
          unset($_SESSION['MEDICA_DATOS_SOL_PAC']);
          $this->frmError["MensajeError"]="INSUMOS PARA PACIENTES SOLICITADOS SATISFACTORIAMENTE.";
          $this->CallFrmMedicamentos($datosPaciente,$datos_estacion);
          return true;
     }

	/*
     * Funcion que permite realizar la cancelacion de los insumos
     * del paciente que fueron solicitados a la bodega de la estacion
     */
     function CancelSolicitudInsumos()
	{
          $matriz = $_REQUEST['matriz'];
          $bodega = $_REQUEST['bodega'];
          $SWITCHE = $_REQUEST['switche'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $obs = $_REQUEST['obs'];
          $spy = $_REQUEST['spia'];
     
          //este vector se puede utilizar en un futuro ya que viene la solicitud y el consecutivo
          //entonces lo dejamos alli mientras.
          $op=$_REQUEST['opcion'];
     
          list($dbconn) = GetDBconn();
          $dbconn->StartTrans();
          
          for($i=0;$i<sizeof($matriz);$i++)
          {
               $QUERYSELECT = "SELECT solicitud_id 
               			 FROM hc_solicitudes_medicamentos
                               WHERE solicitud_id = '".$matriz[$i]."'
                               AND sw_estado IN ('1','5');";
               $resulta = $dbconn->Execute($QUERYSELECT);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                    $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
                
               if(empty($resulta->fields[0]))
               {
                    $query="UPDATE hc_solicitudes_medicamentos
                            SET sw_estado='3'
                            WHERE solicitud_id='".$matriz[$i]."'";
     
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                         $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
     
                    $query="INSERT INTO hc_auditoria_solicitudes_medicamentos
                                   (fecha_registro,usuario_id,observacion,solicitud_id)
                            VALUES (now(),'".UserGetUID()."','$obs','".$matriz[$i]."');";
          
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "no se inserto en  hc_auditoria_solicitudes_medicamentos";
                         $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
               else
               { $acumulado = 1; }
          }
          $dbconn->CompleteTrans();   //termina la transaccion
     
          if($acumulado == 1)
          {
          	$comentario = "LA (O ALGUNA DE LAS) SOLICITUD (ES) YA HABIA (N) SIDO PREVIAMENTE DESPACHADA (S) <BR> 
               			POR LO TANTO NO FUE (RON) CANCELADA (S). <BR><BR>
                              SOLICITUD ATENDIDA SATISFACTORIAMENTE.";
          }
          else
          {
          	$comentario = "SOLICITUD CANCELADA SATISFACTORIAMENTE.";
          }
          
          if($spy==1)
          {
               $this->frmError["MensajeError"] = $comentario;
               $this->CallMedicamentosIns_X_Recibir($datos_estacion,$bodega,$SWITCHE);
               return true;
          }
          else
          {
               $this->frmError["MensajeError"] = $comentario;
               $this->CallFrmMedicamentos($datosPaciente,$datos_estacion);
               return true;
          }
     }
     
     
     /*
     *  Llama la vista que muestra los pacientes que tienen medicamentos que pueden ser devueltos:
     *  osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
     *  es mayor a 0
     *
     *  @Author Rosa Maria Angel
     *  @access Public
     *  @return bool
     */
     function CallFrmDevolucionMedicamentos()
     {
          if(!$this->FrmDevolucionMedicamentos($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['datosPaciente']))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurri� un error al intentar cargar la vista \"FrmDevolucionMedicamentos\"";
               return false;
          }
          return true;
     }
     
     
	/*
     *  CallFrmDevolucionMedicamentos()
     *
     *  Llama la vista que muestra los pacientes que tienen medicamentos que pueden ser devueltos:
     *  osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
     *  es mayor a 0
     *
     *  @Author Rosa Maria Angel
     *  @access Public
     *  @return bool
     */
     function CallFrmDevolucionInsumos()
     {
          if(!$this->FrmDevolucionInsumos($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['datosPaciente']))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurri� un error al intentar cargar la vista \"FrmDevolucionMedicamentos\"";
               return false;
          }
          return true;
     }

     
     /*
     *  Llama la vista que muestra los pacientes que tienen medicamentos que pueden ser devueltos:
     *  osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
     *  es mayor a 0
     *
     *  @Author Rosa Maria Angel
     *  @access Public
     *  @return bool
     */
     function CallFrmDevolucionMedicamentosExterno()
     {
          if(!$this->FrmDevolucionMedicamentosExterno($_REQUEST['datos_estacion'],$_REQUEST['bodega']))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurri� un error al intentar cargar la vista \"FrmDevolucionMedicamentos\"";
               return false;
          }
          return true;
     }

     
     /*
     *  CallFrmDevolucionInsumosExterno()
     *
     *  Llama la vista que muestra los pacientes que tienen medicamentos que pueden ser devueltos:
     *  osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
     *  es mayor a 0
     *
     *  @Author Rosa Maria Angel
     *  @access Public
     *  @return bool
     */
     function CallFrmDevolucionInsumosExterno()
     {
          if(!$this->FrmDevolucionInsumosExterno($_REQUEST['datos_estacion'],$_REQUEST['bodega']))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurri� un error al intentar cargar la vista \"FrmDevolucionMedicamentos\"";
               return false;
          }
          return true;
     }
     

    /*
    * Funcion que permite realizar la solicitud de devolucion de 
    * medicamentos de paciente, solicitados de las bodegas asociadas a la EE
    */     
    function InsertDevolucionMedicamento()
    {
      $bodega         = $_REQUEST['bodega'];
      $datosPaciente  = $_SESSION['datosPaciente_ConfDev'];
      $SWITCHE        = $_REQUEST['switche'];
      $datos_estacion = $_SESSION['datos_estacion_ConfDev'];

      if($_REQUEST['accion'] == '1')
      { $medic = $_SESSION['ESTACION']['VECTOR_DEV_INS'][$_REQUEST['ingreso']][$bodega]; $letra = "I";}
      else { $medic = $_SESSION['ESTACION']['VECTOR_DEV'][$_REQUEST['ingreso']][$bodega]; $letra = "M";}

      if($_REQUEST['opt'])
        $op = $_REQUEST['opt'];
      else
        $op = SessionGetVar("op");

      $despachos = $_REQUEST['despachos'];
      $solicitado = $_REQUEST['solicitado'];

      //Justificacion de devolucion
      $justificacion_devo = $_REQUEST['justificacion_devo'];
      $parametro_id = $_REQUEST['parametro'];
      $Medica = $_REQUEST['medica'];

      if($parametro_id == '-1' )
      {
        $this->frmError["MensajeError"]="SELECCIONE LA JUSTIFICACION DE LA DEVOLUCION.";

        if($_REQUEST['accion'] == '1')
          $this->ConfirmarDevIns();         
        else
          $this->ConfirmarDevMed();

        return true;
     	}
          
      if(!is_array($op))
      {
        $this->frmError["MensajeError"] = "NO SE SELECCIONARON LOS PRODUCTOS QUE SERAN CANCELADOS.";
               
        if($_REQUEST['accion'] == '1')
          $this->ConfirmarDevIns();         
        else
         	$this->ConfirmarDevMed();
        return true;
     	}

      list($dbconn) = GetDBconn();
      
		  $dbconn->BeginTrans();
     
      $query = "SELECT nextval('inv_solicitudes_devolucion_documento_seq');";
      $res=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al traer el consecutivo ";
        $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
        $dbconn->RollbackTrans();
        return false;
      }
     
      $doc=$res->fields[0];
     
          if(empty($doc))
          {
            $this->error = "Error al traer el consecutivo ";
            $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
            $dbconn->RollbackTrans();
            return false;
          }
     
          $query="INSERT INTO inv_solicitudes_devolucion
                                   (    empresa_id,
                                        centro_utilidad,
                                        documento,
                                        bodega,
                                        fecha,
                                        observacion,
                                        usuario_id,
                                        fecha_registro,
                                        estacion_id,
                                        estado,
                                        ingreso,
                                        parametro_devolucion_id
                                   )
                                   VALUES
                                   (
                                        '".$datos_estacion[empresa_id]."',
                                        '".$datos_estacion[centro_utilidad]."',
                                        '$doc',
                                        '".$bodega."',
	                                   '".date("Y-m-d")."',
                                        '$justificacion_devo',                                   
                                        ".UserGetUID().",
                                        now(),
                                        '".$datos_estacion[estacion_id]."',
                                        '0',
                                        '".$_REQUEST['ingreso']."',
                                        '$parametro_id'
                                   )";
     
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No se inserto en inv_solicitudes_devolucion ";
               $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }

          for($i=0;$i<sizeof($Medica);$i++)
          {
            if($op[$i]>0)
            {
              $sol = ($solicitado[$i])? "'".$solicitado[$i]."'": "NULL";
              $dev = ($despachos[$solicitado[$i]][$Medica[$i]])? $despachos[$solicitado[$i]][$Medica[$i]]: $despachos[$Medica[$i]];
              $query="INSERT INTO inv_solicitudes_devolucion_d
                        (   
                          documento,
                          codigo_producto,
                          cantidad,
                          documentos_despachos,
                          codigo_producto_sol
                        )
                        VALUES
                        (    
                          '".$doc."',
                          '".$Medica[$i]."',
                          '".$op[$i]."',
                          '".$dev."',
                           ".$sol."
                        )
                      ";
              $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0)
              {
                   $this->error = "No se inserto en inv_solicitudes_devolucion_d ";
                   $this->mensajeDeError = "Ocurri� un error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                   $dbconn->RollbackTrans();
                   return false;
              }
            }
          }
          
          //termina la transaccion
          $dbconn->CommitTrans();

          // Reseteo variables de datos
          unset($_SESSION['datos_estacion_ConfDev']);
          unset($_SESSION['datosPaciente_ConfDev']);

          if($_REQUEST['accion'] == '1')
          {
               if($_REQUEST['externo'] == true)
               {
                    $this->frmError["MensajeError"]="DEVOLUCION REALIZADA SATISFACTORIAMENTE.";
                    $this->FrmDevolucionInsumosExterno($datos_estacion,$bodega,$datosPaciente);
               }
               else
               {
                    $this->frmError["MensajeError"]="DEVOLUCION REALIZADA SATISFACTORIAMENTE.";
                    $this->FrmDevolucionInsumos($datos_estacion,$bodega,$datosPaciente);
               }
          }
          else
          {
               if($_REQUEST['externo'] == true)
               {
                    $this->frmError["MensajeError"]="DEVOLUCION REALIZADA SATISFACTORIAMENTE.";
                    $this->FrmDevolucionMedicamentosExterno($datos_estacion,$bodega,$datosPaciente);
               }
               else
               {
                    $this->frmError["MensajeError"]="DEVOLUCION REALIZADA SATISFACTORIAMENTE.";
                    $this->FrmDevolucionMedicamentos($datos_estacion,$bodega,$datosPaciente);
               }
          }
          return true;
    }
     /*
     * Funcion que asocia los motivos de devolucion
     */     
     function Get_ParametrosDevolucion()
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();      
	     $query="SELECT * 
     		   FROM estacion_enfermeria_parametros_devolucion
                  ORDER BY parametro_devolucion_id ASC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
               
          while ($data = $result->FetchRow()){
               $vars[] = $data;
          }
               
          $result->Close();
          return $vars;
     }


     /**
     *   GetDevolucionMedicamentos
     *
     *   Muestra los medicamentos que pueden ser devueltos.
     *   a la suma de medicamentos solicitados le resto la suma de los medicamentos devueltos
     *   ya sea que est�n en espera de aceptacion de devoluciion o que ya hayan sido procesados
     *
     *   @Author Rosa Maria Angel
     *   @access Public
     *   @return boolean
     *   @param array => pacientes con ordenes de medicamentos
     *   @param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
     */
    function GetDevolucionMedicamentos($letra, $ingreso)
    {
      list($dbconn) = GetDBconn();
		//$dbconn->debug=true;  
      $query = "SELECT  B.cantidad, 
                        B.codigo_producto,
                        HM.medicamento_id,
                        X.empresa_id, 
                        X.centro_utilidad, 
                        X.bodega,
                        --INV.descripcion,
                        fc_descripcion_producto(INV.codigo_producto) as descripcion,
                        fc_descripcion_producto(HM.medicamento_id) as solicitado,
                        A.documento_despacho_id,
                        BOD.codigo_producto AS non_existencia, 
                        (B.cantidad - (COALESCE(BOD.cantidad_en_devolucion, 0))) AS validacion,
                        FF.descripcion as ff,
                        M.concentracion_forma_farmacologica as concentracion
                    
                FROM    bodegas_documento_despacho_med AS A,
                        bodegas_documento_despacho_med_d AS B,
            						medicamentos M,
            						inv_med_cod_forma_farmacologica FF,
                        hc_solicitudes_medicamentos AS X,
                        hc_solicitudes_medicamentos_d HM,
                        inventarios_productos AS INV
                        LEFT JOIN bodega_paciente AS BOD 
                        ON (INV.codigo_producto = BOD.codigo_producto 
                             AND BOD.ingreso = ".$ingreso.")
                    
                WHERE   X.ingreso = ".$ingreso."
                AND     X.tipo_solicitud = '$letra'
                AND     X.sw_estado IN ('2','5')
                AND     A.documento_despacho_id = X.documento_despacho
                AND     B.documento_despacho_id = A.documento_despacho_id
                AND     B.codigo_producto = INV.codigo_producto
                AND     B.consecutivo_solicitud = HM.consecutivo_d
                AND     HM.solicitud_id = X.solicitud_id
                AND     INV.codigo_producto =M.codigo_medicamento 
                AND     FF.cod_forma_farmacologica = M.cod_forma_farmacologica
                ORDER BY HM.medicamento_id;";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          if($result->EOF)
          {
               return '';
          }
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
          
          $flag = false;
          $productos = array();
          foreach($vector as $k => $dtl)
          {
            $productos[$dtl['codigo_producto']] .= (empty($productos[$dtl['codigo_producto']]))? $dtl['documento_despacho_id']: ",".$dtl['documento_despacho_id'];
          	if($dtl['validacion'] > 0) $flag = true;
          }
          
          if($flag) return array("medicamebtos"=>$vector,"despachos"=>$productos);

          return null;
    }//fin FrmDevolucionMedicamentos()
    /**
    * Metodo donde se obtienen los medicamento de los cuales se puede realizar devolucion
    */
    function ObtenerMedicamentosDevolucion($letra, $ingreso, $bodega)
    { 
//                      SL.solicitado,
//                      SL.descripcion,
      $sql = "SELECT  SL.stock, 
                      SL.codigo_producto,
                      SL.inividual,
                      SL.medicamento_id,
                      SL.empresa_id, 
                      SL.centro_utilidad, 
                      SL.bodega,
                      fc_descripcion_producto(SL.codigo_producto) as descripcion,
                      fc_descripcion_producto(SL.medicamento_id) as solicitado,
                      SL.documento_despacho_id,
                      SL.ff,
                      SL.concentracion,
                      DS.solicitado AS cantidad,
                      DS.solicitado - COALESCE(DE.devuelto,0) AS pendiente
              FROM    (
                        SELECT  BOD.stock_almacen - BOD.cantidad_en_devolucion AS stock, 
                                B.codigo_producto,
                                B.cantidad AS inividual,
                                HM.medicamento_id,
                                X.empresa_id, 
                                X.centro_utilidad, 
                                X.bodega,
                                PR1.descripcion AS descripcion,
                                PR2.descripcion AS solicitado,
                                A.documento_despacho_id,
                                FF.descripcion as ff,
                                M.concentracion_forma_farmacologica as concentracion
                        FROM    bodegas_documento_despacho_med AS A,
                                bodegas_documento_despacho_med_d AS B,
                                hc_solicitudes_medicamentos AS X,
                                hc_solicitudes_medicamentos_d HM,
                                medicamentos M,
                                inventarios_productos PR1,
                                inventarios_productos PR2,
                                inv_med_cod_forma_farmacologica FF,
                                bodega_paciente BOD
                        WHERE   X.ingreso = ".$ingreso."
                        AND     X.tipo_solicitud = '".$letra."'
                        AND     X.sw_estado IN ('2','5')
                        AND     X.solicitud_id = HM.solicitud_id
                        AND     X.documento_despacho = A.documento_despacho_id
                        AND     A.documento_despacho_id = B.documento_despacho_id
                        AND     B.codigo_producto = PR1.codigo_producto
                        AND     B.consecutivo_solicitud = HM.consecutivo_d
                        AND     HM.medicamento_id = BOD.codigo_producto
                        AND     HM.medicamento_id = PR2.codigo_producto
                        AND     HM.ingreso = BOD.ingreso
                        AND     BOD.sw_tipo_producto = '".$letra."'
                        AND     B.codigo_producto = M.codigo_medicamento 
                        AND     FF.cod_forma_farmacologica = M.cod_forma_farmacologica
                        AND     (BOD.stock_almacen - BOD.cantidad_en_devolucion) > 0
                      ) SL LEFT JOIN
                      (
                        SELECT  SUM(ID.cantidad) AS devuelto,
                                ID.codigo_producto,
                                ID.codigo_producto_sol
                        FROM 	  inv_solicitudes_devolucion IV,
                                inv_solicitudes_devolucion_d ID
                        WHERE   IV.ingreso = ".$ingreso."
                        AND     IV.estado IN ('0','1','9')
                        AND     IV.documento = ID.documento
                        AND     ID.estado IN ('0','2')
                        GROUP BY ID.codigo_producto, ID.codigo_producto_sol
                      ) DE
                      ON( SL.medicamento_id = DE.codigo_producto_sol AND
                          SL.codigo_producto = DE.codigo_producto ),
                      (
                        SELECT  SUM(BD.cantidad) AS solicitado,
                                HM.medicamento_id,
                                BD.codigo_producto
                        FROM    hc_solicitudes_medicamentos HS,
                                hc_solicitudes_medicamentos_d HM,
                                bodegas_documento_despacho_med_d BD
                        WHERE   HS.ingreso = ".$ingreso."
                        AND     HS.tipo_solicitud = '".$letra."'
                        AND     HS.sw_estado IN ('2','5')
                        AND     HS.solicitud_id = HM.solicitud_id
                        AND     HM.consecutivo_d = BD.consecutivo_solicitud
                        AND     HS.documento_despacho = BD.documento_despacho_id
                        GROUP BY HM.medicamento_id, BD.codigo_producto
                      ) DS
              WHERE   SL.codigo_producto = DS.codigo_producto
              AND     SL.medicamento_id = DS.medicamento_id
              AND     DS.solicitado - COALESCE(DE.devuelto,0) > 0
              ORDER BY SL.medicamento_id,SL.documento_despacho_id DESC;";
      
      $cxn = new ConexionBD();

      $datos = array();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
        return false;
      
      $devoluciones = array();
      while (!$rst->EOF)
      {
        if(empty($datos[$rst->fields[3]][$rst->fields[1]]))
        {
          if(empty($pendientes[$rst->fields[3]]))
            $pendientes[$rst->fields[3]] = $rst->fields[0];
          
          if($pendientes[$rst->fields[3]] > 0)
          {
            $valor = ($pendientes[$rst->fields[3]] > $rst->fields[2])? $rst->fields[2]: $pendientes[$rst->fields[3]];
            $datos[$rst->fields[3]][$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
            $datos[$rst->fields[3]][$rst->fields[1]]['pendiente'] = $valor;
          }
        }
        else
        {
          if($pendientes[$rst->fields[3]] > 0)
          {
            $valor = ($pendientes[$rst->fields[3]] > $rst->fields[2])? $rst->fields[2]: $pendientes[$rst->fields[3]];
            $datos[$rst->fields[3]][$rst->fields[1]]['documento_despacho_id'] .= ",".$rst->fields[9];
            $datos[$rst->fields[3]][$rst->fields[1]]['pendiente'] += $valor;
          }
        }
        $pendientes[$rst->fields[3]] -= $rst->fields[2];
        if($pendientes[$rst->fields[3]] == 0) $pendientes[$rst->fields[3]] = -1;
        
        $rst->MoveNext();
      }
      $rst->Close();
      
      if(empty($datos))
        return null;
    
      return $datos;
    }
     /**
     *   GetDevolucionInsumos
     *
     *   Muestra los insumos que pueden ser devueltos.
     *   a la suma de insumos solicitados le resto la suma de los insumos devueltos
     *   ya sea que est�n en espera de aceptacion de devoluciion o que ya hayan sido procesados
     *
     *   @Author Rosa Maria Angel
     *   @access Public
     *   @return boolean
     *   @param array => pacientes con ordenes de insumos
     *   @param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
     */
     function GetDevolucionInsumos($letra, $ingreso)
     {
          list($dbconn) = GetDBconn();
          
          $query = "SELECT B.cantidad, 
                           B.codigo_producto,
                           X.empresa_id, 
                           X.centro_utilidad, 
                           X.bodega,
                           --INV.descripcion,
                           fc_descripcion_producto(INV.codigo_producto) as descripcion,
                           BOD.codigo_producto AS non_existencia,
                           A.documento_despacho_id,                           
                           (B.cantidad - (COALESCE(BOD.cantidad_en_devolucion, 0))) AS validacion
                    
                    FROM bodegas_documento_despacho_med AS A,
                         bodegas_documento_despacho_ins_d AS B,
                         hc_solicitudes_medicamentos AS X,
                         inventarios_productos AS INV
                         LEFT JOIN bodega_paciente AS BOD 
                         ON (INV.codigo_producto = BOD.codigo_producto 
                             AND BOD.ingreso = ".$ingreso.")
                    
                    WHERE X.ingreso = ".$ingreso."
                    AND X.tipo_solicitud = '$letra'
                    AND X.sw_estado IN ('2','5')
                    AND A.documento_despacho_id = X.documento_despacho
                    AND B.documento_despacho_id = A.documento_despacho_id
                    AND B.codigo_producto = INV.codigo_producto
                    ORDER BY non_existencia;";

          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          if($result->EOF)
          {
               return '';
          }
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
          
          $flag = false;
          $productos = array();
          foreach($vector as $k => $dtl)
          {
            $productos[$dtl['codigo_producto']] .= (empty($productos[$dtl['codigo_producto']]))? $dtl['documento_despacho_id']: ",".$dtl['documento_despacho_id'];
          	if($dtl['validacion'] > 0) $flag = true;
          }
          
          if($flag) return array("medicamebtos"=>$vector,"despachos"=>$productos);

          return null;
	}//fin FrmDevolucionMedicamentos()

               
     /*
     * Funcion que busca las solicitudes de devoluciones
     * Pendientes de cada respectivo paciente.
     */     
     function BusquedaDevoluciones_Pendientes($datos_estacion,$bodega,$datosPaciente,$letra)
     {
	     GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          
          if($letra == 'M')
          {
               $query = "SELECT a.bodega, b.codigo_producto, d.descripcion, b.cantidad 
						FF.descripcion as ff,
                         j.concentracion_forma_farmacologica as concentracion
                         FROM inv_solicitudes_devolucion a, inv_solicitudes_devolucion_d b
                         LEFT JOIN bodega_paciente x ON (b.codigo_producto = x.codigo_producto
                                                         AND x.sw_tipo_producto = '$letra'
                                                         AND x.ingreso = '".$datosPaciente['ingreso']."'),
                         inventarios_productos d,
                         medicamentos j
						inv_med_cod_forma_farmacologica FF
                         
                         WHERE a.empresa_id = '".$datos_estacion['empresa_id']."' 
                         AND a.centro_utilidad = '".$datos_estacion['centro_utilidad']."'
                         AND a.bodega = '".$bodega."'
                         AND a.ingreso = '".$datosPaciente['ingreso']."'
                         AND a.estacion_id = '".$datos_estacion['estacion_id']."'
                         AND (a.estado='0' OR a.estado='1') 
                         AND a.documento=b.documento
                         AND d.codigo_producto = b.codigo_producto
                         AND d.codigo_producto = j.codigo_medicamento
						AND   FF.cod_forma_farmacologica = j.cod_forma_farmacologica
                         ORDER BY d.descripcion;";
                         
          }
          else
          {
               $query = "SELECT a.bodega, 
                                d.codigo_producto, d.descripcion, 
                                b.cantidad 
                    
                    FROM inv_solicitudes_devolucion a, 
                         inv_solicitudes_devolucion_d b, 
                         inventarios_productos d, 
                         bodega_paciente x 
                    
                    WHERE a.empresa_id = '".$datos_estacion['empresa_id']."'
                    AND a.centro_utilidad = '".$datos_estacion['centro_utilidad']."'
                    AND a.bodega = '".$bodega."'
                    AND a.ingreso = '".$datosPaciente['ingreso']."'
                    AND a.estacion_id = '".$datos_estacion['estacion_id']."'
                    AND (a.estado='0' OR a.estado='1')
                    AND a.documento=b.documento
                    AND b.codigo_producto = d.codigo_producto 
                    AND x.sw_tipo_producto = '$letra'
                    AND x.codigo_producto = d.codigo_producto 
                    AND a.ingreso = x.ingreso
                    ORDER BY d.descripcion;";
          }
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = "Ocurri� un error al intentar obtener los resultados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          while($data = $resultado->FetchRow())
          {
			$devoluciones[] = $data;
          }
          return $devoluciones;
     }
     
     
     /*
     * Funcion q obtiene las cantidades pendientes de medicamentos los cuales deben 
     * ser despachados desde la bodega.
     */
	function GetCantidades_EnDevolucion($ingreso, $codigo, $bodega)
     {
          list($dbconn) = GetDBconn();
          //$dbconn->debug = true;
	     $query="SELECT COALESCE(SUM(B.cantidad), 0) AS cantidad
                  FROM 
                         inv_solicitudes_devolucion AS A,
                         inv_solicitudes_devolucion_d AS B
                  WHERE A.ingreso = ".$ingreso."
                  AND A.estado IN ('0','1','9')
                  AND B.estado IN ('0','2')
                  AND A.bodega = '".$bodega."'
                  AND A.documento = B.documento
                  AND B.codigo_producto = '".$codigo."';";
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = "Ocurri� un error al intentar obtener los resultados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          
          return $resultado->fields[0];
     }     

     
     /*
     * Funcion q obtiene las cantidades pendientes de medicamentos los cuales deben 
     * ser despachados desde la bodega.
     */
     function SumatoriasCantidades_Pendientes($ingreso,$codigo_producto,$empresa,$estacion_id)
     {
          list($dbconn) = GetDBconn();
	     $query="SELECT SUM(B.cant_solicitada) 
                  FROM 
                    hc_solicitudes_medicamentos AS A,
                    hc_solicitudes_medicamentos_d AS B
                  WHERE A.ingreso = ".$ingreso."
                  AND A.empresa_id= '".$empresa."'
                  AND A.sw_estado = '0'
                  AND A.estacion_id = '".$estacion_id."'
                  AND A.solicitud_id = B.solicitud_id
                  AND B.medicamento_id = '".$codigo_producto."';";
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = "Ocurri� un error al intentar obtener los resultados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
     	return $resultado->fields[0];
     }
     
     
     /*
     * Funcion Obtiene los datos del profesional que formulo los medicamentos a solicitar.
     */
     function ProfesionalFormulacion_Medicamento($usuario_id)
     {
          list($dbconn) = GetDBconn();
     	$query="SELECT usuario ||' - '|| nombre 
                  FROM system_usuarios
                  WHERE usuario_id = ".$usuario_id.";";
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = "Ocurri� un error al intentar obtener los resultados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
     	list($Profesional) = $resultado->FetchRow();
          return $Profesional;
     }
     
     /**
     * Funcion que consulta la formulacion del medicamento.
     */
     function Consulta_Solicitud_Medicamentos_Historial($codigo_producto,$ingreso)
     {
          list($dbconnect) = GetDBconn();
          $query= "select o.nombre, n.fecha, o.tipo_profesional,  a.sw_estado, k.sw_uso_controlado,
                    case when k.sw_pos = 1 then 'POS' else 'NO POS' end as item,
                    a.codigo_producto, a.sw_paciente_no_pos, a.cantidad, a.dosis, m.nombre as via,
                    a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id, h.descripcion
                    as producto, c.descripcion as principio_activo, h.contenido_unidad_venta,
                    l.descripcion, a.evolucion_id from hc_medicamentos_recetados_hosp as a left join
                    hc_vias_administracion as m on (a.via_administracion_id = m.via_administracion_id),
                    inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
                    unidades as l,
          
                    hc_evoluciones n, profesionales o, profesionales_usuarios p
          
                    where n.ingreso = ".$ingreso."
                    and a.evolucion_id = n.evolucion_id and
          
                    n.usuario_id = p.usuario_id and
                    p.tipo_tercero_id = o.tipo_id_tercero and
                    p.tercero_id = o.tercero_id 
                    and (a.sw_estado = '9' or a.sw_estado = '1')
                    and a.codigo_producto = '".$codigo_producto."' and
          
                    k.cod_principio_activo = c.cod_principio_activo and
                    h.codigo_producto = k.codigo_medicamento and
                    a.codigo_producto = h.codigo_producto and
                    h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
                    order by a.evolucion_id, k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto";

          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
          return false;
          }
          else
          {
               while (!$result->EOF)
               {
                    $vector[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          $result->Close();
     	return $vector;
     }
     
     function Consultar_Notas_Suministro($codigo_producto,$ingreso)
     {
          list($dbconnect) = GetDBconn();
          $query= "select g.nombre as nombre_usuario, b.sw_estado, b.unidad_dosificacion, e.nombre, a.hc_nota_suministro_id,
          a.codigo_producto, a.evolucion_id, a.observacion, a.tipo_observacion, a.usuario_id_nota,
          a.fecha_registro_nota, z.descripcion as producto from hc_notas_suministro_medicamentos a
          left join profesionales_usuarios f on (a.usuario_id_nota = f.usuario_id) left join
          profesionales e on (f.tipo_tercero_id = e.tipo_id_tercero and f.tercero_id = e.tercero_id)
          left join system_usuarios g on (a.usuario_id_nota = g.usuario_id),
          hc_medicamentos_recetados_hosp b
               left join inventarios_productos z on (b.codigo_producto = z.codigo_producto), hc_evoluciones c, ingresos d
          where a.codigo_producto = '".$codigo_producto."' and a.evolucion_id = b.evolucion_id and
          a.codigo_producto = b.codigo_producto and a.evolucion_id = c.evolucion_id and
          c.ingreso = d.ingreso and d.ingreso = ".$ingreso." order by a.hc_nota_suministro_id";
     
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al consultar el medicamento";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
	}




     /*
     * Funcion q Realiza el llamado de el Metodo de impresion de la formula medica.
     */
     function ReporteFormulaMedica_Para_Pacientes()
     {
          $reporte= new GetReports();
          $mostrar=$reporte->GetJavaReport('app','EE_AdministracionMedicamentos','solicitud_medicamentos_pacientes_estacion_html',array('datos_estacion'=>$_REQUEST[datos_estacion],'estacion'=>$_REQUEST[estacion],'bodega'=>$_REQUEST[bodega_estacion],'solicitud'=>$_REQUEST['solicitud']),array('rpt_name'=>'formula_medica_paciente','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $nombre_funcion=$reporte->GetJavaFunction();
          $this->salida .=$mostrar;
     	$this->salida .="<body onload=".$nombre_funcion.">";
          $this->FrmMedicamentos($_REQUEST[estacion],$_REQUEST[datos_estacion]);
          return true;
     }
     
     /*
     * Funcion q Realiza el llamado de el Metodo de impresion de la formula medica.
     */
     function ReporteFormulaMedica()
	{
          if (!IncludeFile("classes/reports/reports.class.php"))
          {
               $this->error = "No se pudo inicializar la Clase de Reportes";
               $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
               return false;
          }
          
          //Datos del paciente y EE
          $datosPaciente = $_REQUEST['datosPaciente'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          //Datos del paciente y EE

		//cargando criterios.
          $criterio_paciente   = $datosPaciente['paciente_id'];
	     $criterio_tipo_id    = $datosPaciente['tipo_id_paciente'];
          $criterio_ingreso    = $datosPaciente['ingreso'];
          //fin de criterios

          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          
          // Informacion de los datos del Paciente.
          $queryI="SELECT btrim(w.primer_nombre||' '||w.segundo_nombre||' '||w.primer_apellido||' '||w.segundo_apellido,'') AS paciente,
                         w.tipo_id_paciente, w.paciente_id, w.sexo_id, w.fecha_nacimiento,
                         w.residencia_direccion, w.residencia_telefono,
                         x.historia_numero, x.historia_prefijo, 
                         y.fecha_ingreso, y.ingreso, 
                         now() as fecha_cierre,
                         v.tipo_afiliado_id, v.tipo_afiliado_nombre, 
                         t.plan_id, t.sw_tipo_plan, t.plan_descripcion, 
                         s.rango,
                         p.nombre_tercero, p.nombre_tercero AS cliente,
                         em.tipo_id_tercero AS tipo_empresa, em.id, em.razon_social
                         
          
               FROM   pacientes AS w
                      LEFT JOIN historias_clinicas AS x ON (w.paciente_id = x.paciente_id AND w.tipo_id_paciente = x.tipo_id_paciente),
                      ingresos AS y,
                      cuentas AS s
                      LEFT JOIN tipos_afiliado AS v ON (s.tipo_afiliado_id = v.tipo_afiliado_id),
                      planes AS t,
                      terceros AS p,
                      empresas AS em
               
               WHERE  w.paciente_id = '".$criterio_paciente."'
               AND    w.tipo_id_paciente = '".$criterio_tipo_id."'
               AND    y.ingreso = ".$criterio_ingreso."
               AND    y.paciente_id = w.paciente_id
               AND    y.tipo_id_paciente = w.tipo_id_paciente
               AND    y.ingreso = s.ingreso
               AND    em.empresa_id = s.empresa_id
               AND    s.plan_id = t.plan_id    
               AND    t.tercero_id = p.tercero_id
               AND    t.tipo_tercero_id = p.tipo_id_tercero;";          
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($queryI);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;          

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          $datosPacienteM = $result->FetchRow();
                         
          $queryM="SELECT A.*, 'M' AS tipo_solicitud,
                          B.evolucion_id, B.usuario_id, B.fecha_registro,
                          C.descripcion AS producto, C.descripcion_abreviada, C.contenido_unidad_venta,
                          C.unidad_id,
                          D.nombre AS via_administracion,
                          CASE WHEN E.sw_pos = '1' THEN 'POS' ELSE 'NO POS' END AS codigo_pos,
                          F.descripcion AS unidad
                         
                    FROM hc_formulacion_medicamentos AS A,
                         hc_formulacion_medicamentos_eventos AS B,
                         inventarios_productos AS C,
                         hc_vias_administracion AS D,
                         medicamentos AS E,
                         unidades AS F
                    
                    WHERE A.ingreso = ".$criterio_ingreso."
                    AND A.num_reg_formulacion = B.num_reg
                    AND A.sw_estado = '1'
                    AND A.codigo_producto = C.codigo_producto
                    AND A.via_administracion_id = D.via_administracion_id
                    AND A.codigo_producto = E.codigo_medicamento
                    AND F.unidad_id = C.unidad_id
                    ORDER BY A.sw_estado, B.evolucion_id;";

          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($queryM);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;          

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }                         
          while ($data = $result->FetchRow())
          {
               $vectorM[] = $data;
          }        
          
          // Creando Vector de Medicamentos
          $vectorOriginal = array();
          if($vectorM)
          { array_push($vectorOriginal, $vectorM); }
          
          $queryS="SELECT A.*, 'S' AS tipo_solicitud,
                          B.evolucion_id, B.usuario_id, B.fecha_registro,
                          I.codigo_producto, I.sw_solucion, I.cantidad AS cantidad_producto,
                          I.unidad_dosificacion AS unidad_suministro, I.dosis,
                          C.descripcion AS producto, C.descripcion_abreviada, C.contenido_unidad_venta,
                          C.unidad_id,
                          F.descripcion AS unidad
                         
                         
                    FROM hc_formulacion_mezclas AS A,
                         hc_formulacion_mezclas_eventos AS B,
                         hc_formulacion_mezclas_detalle AS I,
                         inventarios_productos AS C,
                         medicamentos AS E,
                         unidades AS F
                    
                    WHERE A.ingreso = ".$criterio_ingreso."
                    AND A.num_reg = B.num_reg
                    AND A.num_mezcla = B.num_mezcla
                    AND A.num_mezcla = I.num_mezcla
                    AND A.sw_estado = '1'
                    AND I.codigo_producto = C.codigo_producto
                    AND I.codigo_producto = E.codigo_medicamento
                    AND C.unidad_id = F.unidad_id
                    ORDER BY A.sw_estado;";
               
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($queryS);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;          

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }                         
          while ($dataS = $result->FetchRow())
          {
               $vectorS[] = $dataS;
          }
          
          if($vectorS)
          { array_push($vectorOriginal, $vectorS); }
     
     
          $qdatosP = "SELECT  r.descripcion as tipo_profesional,
                              p.tipo_id_tercero as tipo_id_medico, p.tercero_id as medico_id, 
                              q.tarjeta_profesional, q.nombre AS nombre_tercero
                         
                    FROM     profesionales AS q,
                              terceros AS p,
                              tipos_profesionales AS r
                              
                    WHERE q.usuario_id = ".UserGetUID()."
                    AND   q.tipo_id_tercero = p.tipo_id_tercero
                    AND   q.tercero_id = p.tercero_id
                    AND   q.tipo_profesional = r.tipo_profesional";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($qdatosP);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;          

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          $datosProfesional = $result->FetchRow();
          
          $vectorGeneral = array();
          array_push($vectorGeneral, $datosPacienteM);
          array_push($vectorGeneral, $vectorOriginal);
          array_push($vectorGeneral, $datosProfesional);          

          if($_REQUEST['impresion_pos']=='1')
          {
               $classReport = new reports;
               $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
               $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='EE_AdministracionMedicamentos',$reporte_name='formula_medica_estacion',$vectorGeneral,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
               if(!$reporte)
               {
                    $this->error = $classReport->GetError();
                    $this->mensajeDeError = $classReport->MensajeDeError();
                    unset($classReport);
                    return false;
               }

               $resultado=$classReport->GetExecResultado();
               unset($classReport);

               if(!empty($resultado[codigo]))
               {
                    "El PrintReport retorno : " . $resultado[codigo] . "<br>";
               }
          
               $this->CallFrmMedicamentos($datosPaciente,$datos_estacion);
          }
          return true;
     }

     //funciones medicamentos.

     //DARLING
     /**
     * Separa la fecha del formato timestamp
     * @access private
     * @return string
     * @param date fecha
     */
     function FechaStamp($fecha)
     {
          if($fecha){
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
          }
	}


     /**
     * Separa la hora del formato timestamp
     * @access private
     * @return string
     * @param date hora
     */
     function HoraStamp($hora)
     {
          $hor = strtok ($hora," ");
          for($l=0;$l<4;$l++)
          {
               $time[$l]=$hor;
               $hor = strtok (":");
          }

          $x = explode (".",$time[3]);
          return  $time[1].":".$time[2].":".$x[0];
     }
    /**
    * Metodo donde se obtienen los datos de los medicamentos en bodega paciente
    * para un ingreso dado
    *
    * @param integer $ingreso Identificador del ingreso
    *
    * 2return mixed
    */
    function ObtenerInformacionBodegaPaciente($ingreso)
    {
      $sql  = "SELECT ingreso,";
      $sql .= " 	    codigo_producto,";
      $sql .= " 	    sw_tipo_producto,";
      $sql .= " 	    stock,";
      $sql .= " 	    stock_paciente,";
      $sql .= " 	    stock_almacen,";
      $sql .= " 	    cantidad_en_solicitud,";
      $sql .= " 	    cantidad_pendiente_por_recibir,";
      $sql .= " 	    cantidad_en_devolucion,";
      $sql .= " 	    total_solicitado,";
      $sql .= " 	    total_cancelado,";
      $sql .= " 	    total_cancelado_antes_de_confirmar,";
      $sql .= " 	    total_cancelado_por_la_bodega,";
      $sql .= " 	    total_despachado,";
      $sql .= " 	    total_recibido,";
      $sql .= " 	    total_devuelto,";
      $sql .= " 	    total_consumo_directo,";
      $sql .= " 	    total_suministrado,";
      $sql .= " 	    total_perdidas,";
      $sql .= " 	    total_aprovechamiento ";
      $sql .= "FROM   bodega_paciente ";
      $sql .= "WHERE  ingreso = ".$ingreso." ";
      
      $cxn = new ConexionBD();
	    if(!$rst = $cxn->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }

    function ObtenerBodegaPaciente($ingreso, $medicamento)
     {

          list($dbconnect) = GetDBconn();
          $query  = "SELECT stock ";
          $query .= "FROM   bodega_paciente ";
          $query .= "WHERE  ingreso = ".$ingreso." and codigo_producto = '".$medicamento."' ";

          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }

          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                         $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
                         $i++;
               }
          }
          return $vector;
     }
    
    function ObtenerBodegaPaciente1($ingreso, $medicamento)
     {
          list($dbconnect) = GetDBconn();
          $query  = "SELECT stock_paciente ";
          $query .= "FROM   bodega_paciente ";
          $query .= "WHERE  ingreso = ".$ingreso." and codigo_producto = ".$medicamento." ";


          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
          }
          return $query;
     }
    /**
    * Metodo donde se obtienen el visto bueno de enfermeria de un paciente
    *
    * @param integer $ingreso Identificador del ingreso
    *
    * 2return mixed
    */
    function ObtenerVistoBuenoPaciente($ingreso)
    {
      $sql  = "SELECT ingreso,";
      $sql .= " 	    evolucion_id,";
      $sql .= " 	    visto_id,";
      $sql .= " 	    usuario,";
      $sql .= " 	    observacion,";
      $sql .= " 	    fecha_registro ";
      $sql .= "FROM   hc_vistosok_salida_detalle ";
      $sql .= "WHERE  ingreso = ".$ingreso." ";
      
      $cxn = new ConexionBD();
	    if(!$rst = $cxn->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }
    function ObtenerFactorConv($medicamento, $unidad)
     {

          list($dbconnect) = GetDBconn();
          $query  = "SELECT factor_conversion ";
          $query .= "FROM   hc_formulacion_factor_conversion ";
          $query .= "WHERE  codigo_producto = '".$medicamento."' and unidad_dosificacion = '".$unidad."' ";

          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }

          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                         $vector[]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
                         $i++;
               }
          }
          return $vector;
     }
    function ObtenerFactorConv1($unidad_id, $medicamento, $unidad)
     {

          list($dbconnect) = GetDBconn();
          $query  = "SELECT factor_conversion ";
          $query .= "FROM   hc_formulacion_factor_conversion ";
          $query .= "WHERE  unidad_id = '".$unidad_id."' and codigo_producto = '".$medicamento."' and unidad_dosificacion = '".$unidad."' ";

          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }

          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                         $vector[]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
                         $i++;
               }
          }
          return $vector;
     }
     function ObtenerEntidad($empresa_id,$centro_utilidad)
     {

          list($dbconnect) = GetDBconn();
          $query  = "SELECT entidad_id,nombre_entidad";
          $query .= " FROM   entidad";
	  $query .= " WHERE empresa_id LIKE '".$empresa_id."' ";
	  $query .= " AND centro_utilidad LIKE '".$centro_utilidad."'";
	 

          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }

          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                         $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
                         $i++;
               }
          }
          return $vector;
     }	
     
     function BodegaInventario($bodega)
     {		
        $sql  = "SELECT sw_inventario ";
        $sql .= "FROM 	bodegas_inventario a ";
        $sql .= "WHERE  a.bodega ='".$bodega."'  ";

        $cxn = new ConexionBD();

        $datos = array();
        if(!$rst = $cxn->ConexionBaseDatos($sql))
          return false;

        while(!$rst->EOF)
        {
          $datos =  $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
     }     
    
  }//end of class
?>