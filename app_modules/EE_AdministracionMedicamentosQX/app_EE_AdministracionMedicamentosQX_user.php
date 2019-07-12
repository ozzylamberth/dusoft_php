<?php

/**
 * $Id: app_EE_AdministracionMedicamentosQX_user.php,v 1.5 2006/06/23 16:56:31 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_AdministracionMedicamentosQX_user extends classModulo
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
     */
     function Consulta_Solicitud_Medicamentos($programacion)
     {
          list($dbconnect) = GetDBconn();
          //query igual que el de cexterna pero se altero uniendo profesionales para hospitalizacion
          $query= "SELECT 
                    k.sw_uso_controlado, case when k.sw_pos = 1 then 'POS'
                    else 'NO POS' end as item, a.codigo_producto, a.cantidad,                    
                    h.descripcion as producto, c.descripcion as principio_activo, 
                    h.contenido_unidad_venta,
                    l.descripcion
               
               	FROM 
                    (SELECT x.codigo_producto, sum(x.cantidad) as cantidad
                     FROM estacion_enfermeria_qx_iym x
                     WHERE x.programacion_id = ".$programacion." and x.estado = '0'
                     GROUP BY x.codigo_producto) as a,
                    
                    inventarios_productos as h 
                    left join medicamentos as k on (h.codigo_producto = k.codigo_medicamento)
                    left join inv_med_cod_principios_activos as c on (k.cod_principio_activo = c.cod_principio_activo),
                    unidades as l
          
                    WHERE a.codigo_producto = h.codigo_producto
                    and h.unidad_id = l.unidad_id                    
                    order by k.sw_pos, a.codigo_producto";

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
     
          //obtener el tipo de usuario
          if (($this->tipo_profesional=='1') OR ($this->tipo_profesional=='2'))
          {
               $_SESSION['PROFESIONAL'.$pfj]=1;
          }
          else
          {
               $_SESSION['PROFESIONAL'.$pfj]=3;
          }
		//fin del tipo de usuario
          return $vector;
     }
     
     function SumatoriaDevoluciones_QX($programacion,$producto)
     {
          list($dbconnect) = GetDBconn();
		$query ="SELECT sum(cantidad) FROM estacion_enfermeria_qx_iym_devoluciones
          	    WHERE programacion_id = ".$programacion." 
                   AND codigo_producto = '$producto'
                   AND estado = '0';"; 
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
     
     
     function SumatoriaSuministrados_QX($programacion,$producto)
     {
          list($dbconnect) = GetDBconn();
		$query ="SELECT sum(cantidad_suministrada) FROM estacion_enfermeria_qx_iym_suministrados
          	    WHERE programacion_id = ".$programacion." 
                   AND codigo_producto = '$producto';";
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
	*		GetEstacionBodega
	*		obtiene la estacion asociada a una bodega.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
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
	*	Obtiene las existencias de los productos de una bodega.
	*
	*	@Author Jairo Duvan Diaz M.
	*	@access Public
	*	@return array, false ó string
	*	@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
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
	*	RevisarExistenciaBodega
	*
	*	obtiene la estacion asociada a una bodega.
	*
	*	@Author Jairo Duvan Diaz M.
	*	@access Public
	*	@return array, false ó string
	*	@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
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
     function SolucionNombreCantidad($solucion_id,$cantidad_id){
		list($dbconnect) = GetDBconn();
          $query= "SELECT a.descripcion,b.cantidad,b.unidad_id
		          FROM hc_medicamentos_soluciones a,hc_medicamentos_soluciones_cantidades b
          	     WHERE a.solucion_id='".$solucion_id."' AND b.cantidad_id='$cantidad_id'";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al buscar en la tabla";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
          	if($result->RecordCount()>0){
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
     
     //funcion que trae los medicamentos solicitados para cancelarlos.
     //pero desde controles de pacientes ya que no podemos filtrar por estacion
     function GetMedicamentosSolicitadosControlPacientes($ingreso,$emp)
     {
          list($dbconnect) = GetDBconn();
          $query= "SELECT a.sw_estado,
                    a.codigo_producto, a.sw_paciente_no_pos, a.cantidad,
                    a.dosis,a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
                    h.descripcion as producto, c.descripcion as principio_activo,
                    l.descripcion,x.solicitud_id,z.consecutivo_d,z.cant_solicitada,x.bodega
                  FROM
                    hc_medicamentos_recetados_hosp as a,
                    inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
                    unidades as l,hc_solicitudes_medicamentos x,hc_solicitudes_medicamentos_d z
	             WHERE x.ingreso = ".$ingreso."
                    and x.solicitud_id=z.solicitud_id
                    and x.empresa_id='$emp'
          
                    and x.sw_estado = '0'
                    and z.medicamento_id=a.codigo_producto
                    and a.evolucion_id=z.evolucion_id
          
                    and a.sw_estado = '1'
                    and	k.cod_principio_activo = c.cod_principio_activo
                    and  h.codigo_producto = k.codigo_medicamento
                    and  h.codigo_producto = a.codigo_producto
                    and h.unidad_id = l.unidad_id
                    ORDER BY z.solicitud_id DESC";
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
	*		GetEstacionBodega
	*
	*		obtiene el nombre de la bodega.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
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
          
                    FROM
                    inventarios_productos as h,
                    unidades as l,hc_solicitudes_medicamentos x,hc_solicitudes_insumos_d z
          
                    WHERE
                    x.ingreso = ".$ingreso."
                    and x.solicitud_id=z.solicitud_id
                    and x.empresa_id='$emp'
                    and x.sw_estado = '0'
                    and  h.codigo_producto = z.codigo_producto
                    and h.unidad_id = l.unidad_id
                    ORDER BY z.solicitud_id DESC";

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
     
     function TiposSolucionesProductos($op){
          $query="SELECT a.solucion_id,a.codigo_producto,b.descripcion,a.cantidad_id,c.cantidad,c.unidad_id,d.descripcion as nom_solucion,a.evolucion_id
               FROM hc_medicamentos_recetados_hosp a
               LEFT JOIN hc_medicamentos_soluciones_cantidades c ON (a.cantidad_id=c.cantidad_id)
               ,inventarios_productos b,hc_medicamentos_soluciones d
               WHERE a.solucion_id IS NOT NULL AND (";
               $con=1;
               foreach($op as $l=>$val){
                    $dato=explode(",",$val);
                    if($con==sizeof($op)){
                         $query.=" ( a.codigo_producto = '".$dato[1]."' AND a.evolucion_id = '".$dato[4]."' AND a.codigo_producto=b.codigo_producto)";
                    }else{
                         $query.=" ( a.codigo_producto = '".$dato[1]."' AND a.evolucion_id = '".$dato[4]."' AND a.codigo_producto=b.codigo_producto) OR ";
                    }
                    $con++;
                    }
          $query.=" ) AND a.solucion_id=d.solucion_id;";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if($result->RecordCount()>0){
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
	*	Revisar_Relacion_Medicamento_Bodegas
	*
	*	obtiene la estacion asociada a una bodega.
	*	Adaptacion Tizziano Perea.
	*	@access Public
	*	@return array, false ó string
	*	@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
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
			$filtro2="AND	a.medicamento_id = '$codigo'";
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
	*	InsertSolicitudMed_Para_Paciente
	*
	*	guarda o realiza la solicitud de los medicamentos para el paciente, 
	*    donde no se traen de bodega.
	*
	*	@Author Jairo Duvan Diaz M.
	*	Adaptacion Tizziano Perea
     *	@access Public
	*	@return array, false ó string
	*	@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function InsertSolicitudMed_Para_Paciente()
	{
		$bodega=$_REQUEST['bodega'];
		$datos_estacion = $_REQUEST['datos_estacion'];
		$datosPaciente = $_REQUEST['datosPaciente'];
		$op = $_SESSION['ESTACION_MED']['VECTOR_SOL_OP'];
		$cant = $_REQUEST['cantidad'];
		$area = $_REQUEST['area'];
		$nom = $_REQUEST['nom'];

		list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
		
          $query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_pacientes_solicitud_id_seq');";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "No se pudo traer la secuencia ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
               $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }

          for($i=0;$i<sizeof($op);$i++)
          {
               $dat_op=explode(",",$op[$i]);
               $query="INSERT INTO hc_solicitudes_medicamentos_pacientes_d
                                        (solicitud_id,
                                         codigo_producto,
                                         sw_estado,
                                         cantidad,
                                         ingreso
                                        )VALUES('$solicitud',
                                                '".$dat_op[0]."',
                                                '0',
                                                '".$dat_op[2]."',
                                                ".$datosPaciente[ingreso].");";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos_d ";
                    $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de medicamentos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
                              $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
	*		InsertSolicitudMed
	*
	*		guarda o realiza la solicitud de los medicamentos.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function InsertSolicitudMed()
	{
          $bodega = $_REQUEST['bodega'];
		$datos_estacion = $_REQUEST['datos_estacion'];
		$datosPaciente = $_REQUEST['datosPaciente'];
		$op=$_SESSION['ESTACION_MED']['VECTOR_SOL_OP'];
		$cant=$_REQUEST['cantidad'];
          $Seleccion=$_REQUEST['Seleccion'];
          $cantidadesSol=$_REQUEST['cantidadesSol'];

		list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
          
		$query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "No se pudo traer la secuencia ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
								'M')";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No se inserto en hc_solicitudes_medicamentos ";
               $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }

          for($i=0;$i<sizeof($op);$i++)
          {
               $dat_op=explode(",",$op[$i]);
		     $query="INSERT INTO hc_solicitudes_medicamentos_d
                                   (solicitud_id,
                                    medicamento_id,
                                    evolucion_id,
                                    cant_solicitada
                                   )VALUES('$solicitud',
                                           '".$dat_op[0]."',
                                           '".$dat_op[1]."',
                                           '".$dat_op[2]."')";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos_d ";
                    $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de medicamentos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
          }

		if(is_array($_REQUEST['checo']) || is_array($Seleccion))
		{
               //funcion que crea la solicitud de insumos automaticamente, cuando lo hacemos directamente desde 
               //las solicitudes de medicamentos.
               $query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
               $res=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se pudo traer la secuencia ";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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

                         if(!empty($codigo[1]))
                         {
	                         $query="INSERT INTO hc_solicitudes_insumos_d
                                                  (solicitud_id,
                                                   codigo_producto,
                                                   cantidad
                                                  )VALUES('$solicitud',
                                                          '".$codigo[0]."',
                                                          '".$codigo[1]."')"; 
                              $dbconn->Execute($query);
                              if ($dbconn->ErrorNo() != 0)
                              {
                                   $this->error = "No se inserto en hc_solicitudes_insumos_d ";
                                   $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
                         }
	               }
               }
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
                                   $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de medicamentos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
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
	*	Insertar_Recibido_Para_Pacientes
	*
	*	guarda o realiza la solicitud de los medicamentos para el paciente, 
	*    donde no se traen de bodega.
	*
	*	@Author Jairo Duvan Diaz M.
	*	@access Public
	*	@return array, false ó string
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
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
               $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
                         $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de medicamentos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
               $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de medicamentos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
		for($i=0;$i<sizeof($matriz);$i++)
		{

               $query="UPDATE hc_solicitudes_medicamentos
                       SET sw_estado='3'
                       WHERE solicitud_id='".$matriz[$i]."'";

               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }

		}
		$dbconn->CompleteTrans();//termina la transaccion

		if($spy==1)
		{
			$this->frmError["MensajeError"]="SOLICITUD CANCELADA SATISFACTORIAMENTE.";
			$this->MedicamentosIns_X_Recibir($datos_estacion,$bodega,$SWITCHE);
			return true;
		}
		else
		{
			$this->frmError["MensajeError"]="SOLICITUD CANCELADA SATISFACTORIAMENTE.";
			$this->CallFrmMedicamentos($datosPaciente,$datos_estacion);
			return true;
		}
	}

	/*
     * Funcion que permite mostrar los suministros q se le han 
     * realizado al paciente
     */
     function Consultar_Control_Suministro($codigo_producto,$evolucion_id,$ingreso)
	{
		list($dbconn) = GetDBconn();
		//trae todo lo del ingreso
		$query= "select a.hc_control_suministro_id, a.codigo_producto,
			a.evolucion_id, a.usuario_id_control, a.fecha_realizado,
			a.fecha_registro_control, a.cantidad_suministrada, a.observacion,
			e.nombre, g.nombre as nombre_usuario

			from hc_control_suministro_medicamentos a left join profesionales_usuarios f on
			(a.usuario_id_control = f.usuario_id) left join profesionales e on
			(f.tipo_tercero_id = e.tipo_id_tercero and f.tercero_id = e.tercero_id )
			left join system_usuarios g on (a.usuario_id_control = g.usuario_id ),
			hc_medicamentos_recetados_hosp b, hc_evoluciones c, ingresos d

			where a.codigo_producto = '".$codigo_producto."'
			and a.evolucion_id = b.evolucion_id and a.codigo_producto = b.codigo_producto
			and a.evolucion_id = c.evolucion_id and	 c.ingreso = d.ingreso and
			d.ingreso =  ".$ingreso."
			order by a.hc_control_suministro_id desc";


			//trae todo lo de la evolucion en especial
			$query1= "select a.hc_control_suministro_id, a.codigo_producto,
			  a.evolucion_id,	a.usuario_id_control, a.fecha_realizado,
			a.fecha_registro_control,	a.cantidad_suministrada, a.observacion,
			e.nombre, g.nombre as nombre_usuario

			from hc_control_suministro_medicamentos a left join profesionales_usuarios f on
			(a.usuario_id_control = f.usuario_id) left join profesionales e on
  			(f.tipo_tercero_id = e.tipo_id_tercero and f.tercero_id = e.tercero_id)
			left join system_usuarios g on (a.usuario_id_control = g.usuario_id),
			hc_medicamentos_recetados_hosp b

			where a.codigo_producto = '".$codigo_producto."'
			and a.evolucion_id = '".$evolucion_id."' and
			a.evolucion_id = b.evolucion_id	and a.codigo_producto = b.codigo_producto
			order by a.hc_control_suministro_id";

		//and a.evolucion_id = ".$evolucion_id."
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
	function total_suministro($codigo_producto,$evolucion_id,$ingreso)
     {	
     	list($dbconn) = GetDBconn();
          $query = "select sum(a.cantidad_suministrada) as totalitario 
			     from hc_control_suministro_medicamentos a 
			     left join hc_medicamentos_recetados_hosp b on (a.evolucion_id = b.evolucion_id and a.codigo_producto = b.codigo_producto)
				where a.codigo_producto = '".$codigo_producto."'
				and a.evolucion_id = ".$evolucion_id.";";
          
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
	*	GetEstacionBodega
	*
	*	obtiene la estacion asociada a una bodega.
	*
	*	@Author Jairo Duvan Diaz M.
	*	@access Public
	*	@return array, false ó string
	*	@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
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
                    WHERE
                    a.estacion_id='".$datos[estacion_id]."'
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

     /********************FUNCIONES SUMATORIA PARA BODEGAS DE PACIENTES**********/
     function Sumatorias_Cantidades_Para_Bodegas_De_Pacientes($ingreso,$estacion,$codigo)
     {
		//(+)
          list($dbconn) = GetDBconn();
          $sql="SELECT CASE WHEN SUM(d.cantidad) ISNULL THEN 0 ELSE SUM(d.cantidad) END 
                    FROM
                    ingresos a,cuentas b,cuentas_detalle c, bodegas_documentos_d d, bodegas_doc_numeraciones e,inventarios_productos f,
                    hc_solicitudes_medicamentos p
          
                    WHERE a.ingreso='$ingreso'
                    AND a.ingreso=b.ingreso
                    AND	b.numerodecuenta=c.numerodecuenta
                    AND	c.consecutivo is not null
                    AND	c.consecutivo=d.consecutivo
                    AND d.bodegas_doc_id=e.bodegas_doc_id
                    AND	d.codigo_producto=f.codigo_producto
                    AND	d.codigo_producto='$codigo'
                    AND p.bodegas_doc_id=d.bodegas_doc_id
                    AND p.numeracion=d.numeracion";

		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
		}

		$sumatoria_despacho=$result->fields[0];//sumatoria de despacho
		
		
		//(-)
          $sql= "SELECT CASE WHEN SUM(b.cantidad) ISNULL THEN 0 ELSE SUM(b.cantidad) END  FROM inv_solicitudes_devolucion a,
                    inv_solicitudes_devolucion_d b,bodegas_documentos_d c
                    WHERE
                    a.ingreso = ".$ingreso."
                    AND a.documento=b.documento
                    AND a.empresa_id='".$estacion[empresa_id]."'
                    AND a.centro_utilidad='".$estacion[centro_utilidad]."'
                    AND a.estacion_id='".$estacion[estacion_id]."'
                    AND a.estado='0'
                    AND b.codigo_producto='".$codigo."'
                    AND a.bodegas_doc_id=c.bodegas_doc_id
                    AND a.numeracion=c.numeracion";
          $result = $dbconn->Execute($sql);
					
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      		return false;
		}
	
          $sumatoria_devolucion=$result->fields[0];//sumatoria de devolucion.
			
          //(-)
          $query= "SELECT CASE WHEN SUM(a.cantidad_suministrada) ISNULL THEN 0 ELSE 
                    SUM(a.cantidad_suministrada) END FROM 
                    hc_control_suministro_medicamentos a,
                    hc_evoluciones b
                    WHERE
                    b.ingreso = ".$ingreso."
                    AND a.codigo_producto='".$codigo."'
                    AND a.evolucion_id=b.evolucion_id
                    ";
                    //Estado de la evolucion 1=Activa, 0=Cerrada 

		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      		return false;
		}
			
		$sumatoria_suministro=$result->fields[0];//sumatoria de devolucion.
		
		//(-)
		 
		$query= "SELECT CASE WHEN SUM(cantidad) ISNULL THEN 0 ELSE SUM(cantidad)  END  FROM 
                    hc_control_suministro_medicamentos_perdidas 
                    WHERE
                    ingreso = ".$ingreso."
                    AND codigo_producto='".$codigo."'";
          $result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      		return false;
		}
          $sumatoria_suministro_perdidas=$result->fields[0];//sumatoria de devolucion.

          //(+)
          //preguntar despues si hay que filtrar estado=0
	   	$query= "SELECT CASE WHEN SUM(z.cantidad) ISNULL THEN 0 ELSE SUM(z.cantidad) END
                    FROM
                    hc_recepcion_medicamentos_pacientes x,
                    hc_recepcion_medicamentos_pacientes_d z
                    WHERE
                    x.ingreso = $ingreso 
                    AND	z.codigo_producto = '$codigo' 
                    AND x.recepcion_id=z.recepcion_id";
			
          $result = $dbconn->Execute($query);
			
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
			
          $sumatoria_recepcion=$result->fields[0];//sumatoria de devolucion.
			
          //(-)
          $query= "SELECT CASE WHEN SUM(cantidad) ISNULL THEN 0 ELSE SUM(cantidad) END FROM 
                    hc_devolucion_medicamentos_pacientes 
                    WHERE
                    ingreso = ".$ingreso."
                    $filtro
                    AND codigo_producto='".$codigo."'";
                    $result = $dbconn->Execute($query);
			
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
		$sumatoria_devolucion_paciente=$result->fields[0];//sumatoria de devolucion.
		
          $SUMATORIA=$sumatoria_despacho-$sumatoria_devolucion-
          $sumatoria_suministro-$sumatoria_suministro_perdidas+$sumatoria_recepcion-$sumatoria_devolucion_paciente;
          return $SUMATORIA;
	}
	/********************FUNCIONES SUMATORIA PARA BODEGAS DE PACIENTES**********/


 	//Funcion q carga a la cuenta los medicamentos consumidos por el paciente.
	//funcion que inserta los suministros a un determinado paciente
	function InsertarSuministroPaciente()
	{
          $datosPaciente = $_REQUEST['datosPaciente'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          
          $tipo_solicitud = $_REQUEST['tipo_solicitud'];
          $checo = $_REQUEST['checo'];
          
          $vect = $_SESSION['MEDICINAS'];//arreglo q contiene los productos seleccionados.
         	$opciones = $_REQUEST['op'];
          $profesional_orden = $_REQUEST['ordeno'];

         $cantidad = $_REQUEST['cantidad_suministro'];
         $cantidad_real = $_REQUEST['Cantidad_Real'];
         for($i=0; $i<sizeof($cantidad); $i++)
         {
               if(empty($cantidad[$i]))
               {
                    $this->frmError["MensajeError"]="LA CANTIDAD NO PUEDE SER CERO.";
                    $this->Control_Suministro($datos_estacion,$datosPaciente,$vect,$opciones);
                    return true;
               }
               
               if(is_numeric($cantidad[$i])==0)
               {
                    $this->frmError["MensajeError"]="CANTIDAD INVALIDA, DIGITE SOLO NUMEROS.";
                    $this->Control_Suministro($datos_estacion,$datosPaciente,$vect,$opciones);
                    return true;
               }

               if($cantidad[$i] > $cantidad_real[$i])
               {
                    $this->frmError["MensajeError"]="CANTIDAD INVALIDA, ES MAYOR A LAS EXISTENCIAS.";
                    $this->Control_Suministro($datos_estacion,$datosPaciente,$vect,$opciones);
                    return true;
               }
         }
         
          if(empty($profesional_orden))
          {
               $this->frmError["MensajeError"]="DEBE SELECCIONAR EL PROFESIONAL QUE ORDENA EL SUMINISTRO DEL MEDICAMENTO.";
               $this->Control_Suministro($datos_estacion,$datosPaciente,$vect,$opciones);
               return true;
          }


          unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['cant']);
          unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectHora']);
          unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectMinutos']);
          unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['observacion_suministro']);

          $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectHora']=$fecha=$_REQUEST['selectHora'];
          $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectMinutos']=$minutos=$_REQUEST['selectMinutos'];
          $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['observacion_suministro']=$observacion=$_REQUEST['observacion_suministro'];
          
		$fecha_realizado = $_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectHora'].":".$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectMinutos'];
		list($dbconn) = GetDBconn();
		$dbconn->StartTrans();//inicia la transaccion
		
          for($j=0; $j<sizeof($opciones); $j++)
          {
               $V_Medicamentos = explode(",",$opciones[$j]);
               $sql= "INSERT INTO estacion_enfermeria_qx_iym_suministrados
                                   (  codigo_producto,
                                      cantidad_suministrada,
                                      programacion_id,
                                      ingreso,
                                      usuario_ordeno,
                                      usuario_suministro,
                                      observacion,
                                      fecha_registro) VALUES
                                   (
                                      '".$V_Medicamentos[0]."',
                                      '".$cantidad[$j]."',
                                      ".$datosPaciente[programacion_id].",
                                      ".$datosPaciente[ingreso].",
                                      ".$profesional_orden.",
                                      ".UserGetUID().",
                                      '".$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['observacion_suministro']."',
                                      '".date("Y-m-d H:i:s")."')";
               $dbconn->Execute($sql);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al insertar hc_control_suministro_medicamentos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
			
          $dbconn->CompleteTrans();   //termina la transaccion	
          $this->frmError["MensajeError"]="";
          
          $url = ModuloGetURL('app','EE_AdministracionMedicamentosQX','user','CallFrmMedicamentos',array('datosPaciente'=>$datosPaciente,'datos_estacion'=>$datos_estacion));
          $titulo = 'SISTEMA';
          $mensaje = 'DATOS GUARDADOS SATISFACTORIAMENTE.';
          $link = 'VOLVER';
          $this->frmMSG($url,$titulo,$mensaje,$link);
          return true;
	}
     
     
     function ReconocerProfesional()
	{
		list($dbconn) = GetDBconn();
		GLOBAL $ADODB_FETCH_MODE;           
     	$sql="SELECT usuario_id, nombre
                FROM profesionales
                ORDER BY nombre ASC;";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;		
          if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer profesional";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while($data = $result->FetchRow())
          {
          	$profesional[] = $data;
          }
          return $profesional;
	}

     /*
     * Esta funcion permite finalizar los medicamentos del paciente
     * cuando su consumo se halla totalizado.
     *
     * Adaptacion Tizziano Perea
     */
     function Finalizar_Medicamentos($Medicamento,$datosPaciente)
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          
          $dbconn->BeginTrans();
          
          //INSERTANDO NOTA PARA LA FINALIZACION
     	$query="INSERT INTO hc_notas_suministro_medicamentos
                              (codigo_producto, evolucion_id, observacion, tipo_observacion,
                              usuario_id_nota, fecha_registro_nota)
                              VALUES
                              ('".$Medicamento['codigo_producto']."',
                               ".$Medicamento['evolucion_id'].",
                               'Finalizacion del Tratamiento (Estacion de Enfermeria)',
                               '3', ".UserGetUID().", now())";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al insertar en hc_justificaciones_no_pos_hosp_respuestas_pos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->frmError["MensajeError"]="NO HA SIDO POSIBLE GENERAR NOTA";
               $dbconn->RollbackTrans();
               return false;
          }
          //FIN DE LA INSERCION
          else
          {
               $query= "UPDATE hc_medicamentos_recetados_hosp SET  sw_estado= '0'
                        WHERE codigo_producto = '".$Medicamento['codigo_producto']."'
                        AND evolucion_id = ".$Medicamento['evolucion_id'].";";

               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al actualizar la observacion en hc_os_solicitudes_apoyod";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
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
	function GetInsumos($bodega,$filtro,$cod,$sw)
	{
          $this->limit=GetLimitBrowser();
		list($dbconn) = GetDBconn();
		if($bodega=='*/*')
		{	$filtro_bodega="";}else{$filtro_bodega="AND a.bodega='$bodega'";}
		
		if(empty($_REQUEST['conteo'])){
		$query = "SELECT b.descripcion,b.descripcion_abreviada,producto_id,a.codigo_producto
                    	FROM
						existencias_bodegas a,
						inventarios_productos b,
						inv_grupos_inventarios c
                         WHERE 
						a.codigo_producto=b.codigo_producto
						AND c.grupo_id=b.grupo_id
						AND c.sw_insumos='1'
						$filtro_bodega
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

          $query="SELECT $distinct b.descripcion,b.descripcion_abreviada,producto_id,a.codigo_producto
                    FROM
                         existencias_bodegas a,
                         inventarios_productos b,
                         inv_grupos_inventarios c
                    WHERE 
                         a.codigo_producto=b.codigo_producto
                         AND c.grupo_id=b.grupo_id
                         AND c.sw_insumos='1'
                         $filtro_bodega
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
     
     
     //esta funcion genera la solicitud de insumos 
	function InsertarInsumosPaciente()
	{
		$datos_estacion = $_REQUEST["datos_estacion"];
		$datosPaciente = $_REQUEST["datosPaciente"];
          $bodega = $_REQUEST['bodega'];
		$op = $_REQUEST['op'];
		$cant = $_REQUEST['cant'];

		list($dbconn) = GetDBconn();
		$query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "No se pudo traer la secuencia ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
               $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
                         $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
		}
		$dbconn->CompleteTrans();   //termina la transaccion
		
          unset($_SESSION['EXISTENCIA']);
          unset($_SESSION['codigos_I']);
          unset($_SESSION['cantidad_a_perdi_sol_I']);

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
		$query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_pacientes_solicitud_id_seq');";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "No se pudo traer la secuencia ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
               $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
                         $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
          }
          $dbconn->CompleteTrans();   //termina la transaccion
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
			 $query="UPDATE hc_solicitudes_medicamentos
                         SET sw_estado='3'
					WHERE solicitud_id='".$matriz[$i]."'";

               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
		}
		$dbconn->CompleteTrans();   //termina la transaccion

		if($spy==1)
		{
			$this->frmError["MensajeError"]="SOLICITUD CANCELADA SATISFACTORIAMENTE.";
			$this->CallMedicamentosIns_X_Recibir($datos_estacion,$bodega,$SWITCHE);
			return true;
		}
		else
		{
			$this->frmError["MensajeError"]="SOLICITUD CANCELADA SATISFACTORIAMENTE.";
			$this->CallFrmMedicamentos($datosPaciente,$datos_estacion);
			return true;
		}
	}
     
     
     /*
     *	Llama la vista que muestra los pacientes que tienen medicamentos que pueden ser devueltos:
     *	osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
     *	es mayor a 0
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool
     */
     function CallFrmDevolucionMedicamentos()
     {
          if(!$this->FrmDevolucionMedicamentos($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['datosPaciente']))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmDevolucionMedicamentos\"";
               return false;
          }
          return true;
     }
     
     
	/*
     *	CallFrmDevolucionMedicamentos()
     *
     *	Llama la vista que muestra los pacientes que tienen medicamentos que pueden ser devueltos:
     *	osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
     *	es mayor a 0
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool
     */
     function CallFrmDevolucionInsumos()
     {
          if(!$this->FrmDevolucionInsumos($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['datosPaciente']))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmDevolucionMedicamentos\"";
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
		$bodega=$_REQUEST['bodega'];
		$datosPaciente = $_REQUEST['datosPaciente'];
		$SWITCHE = $_REQUEST['switche'];
		$datos_estacion = $_REQUEST['datos_estacion'];
		$medic = $_REQUEST['medic'];//$_SESSION['ESTACION']['VECTOR_DEV'][$_REQUEST['ingreso']][$bodega];
		$op = $_REQUEST['opt'];//aqui van los values osea las cajas de texto....
          $justificacion_devo = $_REQUEST['justificacion_devo'];//Justificacion de devolucion
          $parametro_id = $_REQUEST['parametro'];

          if($parametro_id == '-1' ){
               $this->frmError["MensajeError"]="SELECCIONE LA JUSTIFICACION DE LA DEVOLUCION.";
               $this->ConfirmarDevMed();
               return true;
		}

		list($dbconn) = GetDBconn();
		$dbconn->StartTrans();

		$query = "SELECT nextval('inv_solicitudes_devolucion_documento_seq');";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer el consecutivo ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			$dbconn->RollbackTrans();
			return false;
		}

		$doc=$res->fields[0];

		if(empty($doc))
		{
			$this->error = "Error al traer el consecutivo ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
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
                                   '".$medic[0][bodega]."',
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
               $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }

		for($i=0;$i<sizeof($medic);$i++)
		{
               if($op[$i]>0)
               {
                    $query="INSERT INTO inv_solicitudes_devolucion_d
                                   (    documento,
                                        codigo_producto,
                                        cantidad )
                                   VALUES
                                   (    '$doc',
                                        '".$medic[$i][codigo_producto]."',
                                        '".$op[$i]."' )";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se inserto en inv_solicitudes_devolucion_d ";
                         $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
		}
          $dbconn->CompleteTrans();   //termina la transaccion

          if($_REQUEST['accion'] == '1')
          {
               $this->frmError["MensajeError"]="DEVOLUCION REALIZADA SATISFACTORIAMENTE.";
               $this->FrmDevolucionInsumos($datos_estacion,$bodega,$datosPaciente);
          }
          else
          {
               $this->frmError["MensajeError"]="DEVOLUCION REALIZADA SATISFACTORIAMENTE.";
               $this->FrmDevolucionMedicamentos($datos_estacion,$bodega,$datosPaciente);
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
	*	FrmDevolucionMedicamentos
	*
	*	Muestra los medicamentos que pueden ser devueltos => Alex me dió esta formula:
	*	a la suma de medicamentos solicitados le resto la suma de los medicamentos devueltos
	*	ya sea que estén en espera de aceptacion de devoluciion o que ya hayan sido procesados
	*
	*	@Author Rosa Maria Angel
	*	@access Public
	*	@return boolean
	*	@param array => pacientes con ordenes de medicamentos
	*	@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetDevolucionMedicamentos($ingreso,$bodega,$letra)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT d.codigo_producto,f.descripcion,e.empresa_id,
                         e.centro_utilidad,e.bodega,
                         
                         (SELECT SUM(y.cantidad)
                         FROM ingresos v,cuentas w,cuentas_detalle x, bodegas_documentos_d y, bodegas_doc_numeraciones z
                         WHERE v.ingreso='$ingreso'
                         AND v.ingreso=w.ingreso
                         AND w.numerodecuenta=x.numerodecuenta
                         AND x.consecutivo is not null
                         AND x.consecutivo=y.consecutivo
                         AND y.bodegas_doc_id=z.bodegas_doc_id
                         AND y.codigo_producto=d.codigo_producto AND z.empresa_id=e.empresa_id AND z.centro_utilidad=e.centro_utilidad
                         AND z.bodega=e.bodega AND e.bodega='$bodega' AND x.cargo = 'IMD')
                         as suma1
                         
                         FROM
                         ingresos a,cuentas b,cuentas_detalle c, bodegas_documentos_d d, bodegas_doc_numeraciones e,inventarios_productos f,
                         hc_solicitudes_medicamentos ñ

                         WHERE a.ingreso='$ingreso'
                         AND a.ingreso=b.ingreso
                         AND b.numerodecuenta=c.numerodecuenta
                         AND c.consecutivo is not null
                         AND c.consecutivo=d.consecutivo
                         AND d.bodegas_doc_id=e.bodegas_doc_id
                         AND d.codigo_producto=f.codigo_producto
                         AND ñ.bodegas_doc_id=d.bodegas_doc_id
                         AND ñ.numeracion=d.numeracion
                         AND ñ.tipo_solicitud='$letra'
                         AND e.bodega='$bodega'";

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
	}//fin FrmDevolucionMedicamentos()

     	
     /*
     * Funcion que busca las solicitudes de devoluciones
     * Pendientes de cada respectivo paciente.
     */     
     function BusquedaDevoluciones_Pendientes($datos_estacion,$bodega,$datosPaciente,$producto)
     {
		GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
	     $query="SELECT SUM(b.cantidad) AS cantidad
                    FROM inv_solicitudes_devolucion a, 
                    	inv_solicitudes_devolucion_d b, inventarios c, 
                         inventarios_productos d,
                         existencias_bodegas e
                    WHERE a.empresa_id='".$datos_estacion['empresa_id']."' 
                    AND a.centro_utilidad='".$datos_estacion['centro_utilidad']."' 
                    AND a.bodega='".$bodega."' 
                    AND a.ingreso='".$datosPaciente['ingreso']."'
                    AND a.estacion_id='".$datos_estacion['estacion_id']."'
                    AND (a.estado='0' OR a.estado='1')
                    AND a.documento=b.documento
                    AND b.codigo_producto='$producto'
                    AND c.empresa_id=a.empresa_id 
                    AND c.codigo_producto=b.codigo_producto 
                    AND d.codigo_producto=b.codigo_producto 
                    AND a.empresa_id=e.empresa_id 
                    AND a.centro_utilidad=e.centro_utilidad 
                    AND a.bodega=e.bodega 
                    AND b.codigo_producto=e.codigo_producto;";
	
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = "Ocurrió en error al intentar obtener los resultados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          $devoluciones = $resultado->FetchRow();
          return $devoluciones;
     }


     /*
     * Funcion q Realiza el llamado de el Metodo de impresion de la formula medica.
     */
     function ReporteFormulaMedica_Para_Pacientes()
     {
          $reporte= new GetReports();
          $mostrar=$reporte->GetJavaReport('app','EE_AdministracionMedicamentosQX','solicitud_medicamentos_pacientes_estacion_html',array('datos_estacion'=>$_REQUEST[datos_estacion],'estacion'=>$_REQUEST[estacion],'bodega'=>$_REQUEST[bodega_estacion],'solicitud'=>$_REQUEST['solicitud']),array('rpt_name'=>'formula_medica_paciente','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
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
          //si el reporte es pdf se redirecciona al reporte de la clase de alex sino
          //sigue derecho y ejecuta el reporte pos
          if ($_REQUEST['mandarpdf']!='')
          {
               //lo de alex
               if(empty($_REQUEST['op']))
               {
          		$this->frmError["MensajeError"]="POR FAVOR SELECCIONE ALGUNA CASILLA, O EN SU DEFECTO TODAS (SEL. TODOS).";
                    $this->FrmImpresionMedicamentos($_REQUEST['estacion'],$_REQUEST['datos_estacion']);
                    return true;
               }
               else
               {
                    $reporte= new GetReports();
                    $mostrar=$reporte->GetJavaReport('app','EE_AdministracionMedicamentosQX','formula_medica_estacion_html',array('datos_estacion'=>$_REQUEST[datos_estacion],'estacion'=>$_REQUEST[estacion],'bodega'=>$_REQUEST[bodega_estacion], 'op'=>$_REQUEST['op']),array('rpt_name'=>'formula_medica_hosp_html','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                    $nombre_funcion=$reporte->GetJavaFunction();
                    $this->salida .=$mostrar;
                    $this->salida .="<body onload=".$nombre_funcion.">";
               }
               //fin de alex
          }
          else
          {
               if (!IncludeFile("classes/reports/reports.class.php"))
               {
                    $this->error = "No se pudo inicializar la Clase de Reportes";
                    $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
                    return false;
               }
     
               if(empty($_REQUEST['op']))
               {
                    $this->frmError["MensajeError"]="POR FAVOR SELECCIONE ALGUNA CASILLA, O EN SU DEFECTO TODAS (SEL. TODOS).";
                    $this->FrmImpresionMedicamentos($_REQUEST['estacion'],$_REQUEST['datos_estacion']);
                    return true;
               }
               
               //condicion para el reporte de los medicamentos seleccionados
               if(sizeof($_REQUEST['op'])>0)
               {
                    $search="";
                    $union= "";
                    $arr = $_REQUEST['op'];
                    $indice = 1;
                    foreach($arr as $x=>$y)
                    {
                         $vector=explode (",",$y);
                         if($indice==1)
                         {
                              $union = ' and  ((';
                         }
                         else
                         {
                              $union = ' or (';
                         }
                         $search.= "$union a.codigo_producto= '".$vector[0]."' and a.evolucion_id= ".$vector[1].")";
                         $indice++;
                    }
                    $search.=")";
               }
               else
               {
                    $AND="";
                    $search="";
               }
            
            //fin de la condicion
            list($dbconn) = GetDBconn();
            $query="SELECT btrim(w.primer_nombre||' '||w.segundo_nombre||' '||
                    w.primer_apellido||' '||w.segundo_apellido,'') as paciente,
                    w.tipo_id_paciente, w.paciente_id,
     
                    n.ingreso, n.fecha, w.residencia_direccion, w.residencia_telefono,
                    v.tipo_afiliado_id, t.plan_id, sw_tipo_plan, s.rango,
                    v.tipo_afiliado_nombre, p.nombre_tercero,	u.nombre_tercero as cliente,
                    r.descripcion as tipo_profesional,	p.tipo_id_tercero as tipo_id_medico,
                    p.tercero_id as	medico_id, q.tarjeta_profesional,	t.plan_descripcion,
                    a.evolucion_id, case when k.sw_pos = 1 then 'POS'	else 'NO POS' end as item,
                    a.sw_paciente_no_pos, a.codigo_producto,  h.descripcion as producto,
                    c.descripcion as principio_activo, m.nombre as via, a.dosis,
                    a.unidad_dosificacion, a.tipo_opcion_posologia_id, a.cantidad, l.descripcion,
                    h.contenido_unidad_venta,	a.observacion
     
                    FROM hc_medicamentos_recetados_hosp as a left join hc_vias_administracion as m
                    on (a.via_administracion_id = m.via_administracion_id)
                    left join hc_evoluciones as n on (a.evolucion_id= n.evolucion_id) left join
                    profesionales_usuarios as o on (n.usuario_id = o.usuario_id) left join
                    terceros as p	on (o.tipo_tercero_id = p.tipo_id_tercero AND
                    o.tercero_id = p.tercero_id) left join profesionales as q on
                    (o.tipo_tercero_id = q.tipo_id_tercero AND o.tercero_id = q.tercero_id)
                    left join tipos_profesionales as r on (q.tipo_profesional = r.tipo_profesional)
                    left join cuentas as s on (n.numerodecuenta = s.numerodecuenta) left join
                    planes as t	on (s.plan_id = t.plan_id) left join terceros as u on
                    (t.tipo_tercero_id = u.tipo_id_tercero AND t.tercero_id	= u.tercero_id)
                    left join tipos_afiliado as v on (s.tipo_afiliado_id = v.tipo_afiliado_id)
                    left join pacientes as w on (w.paciente_id= '".$_REQUEST[datos_estacion]['paciente_id']."'
                    and w.tipo_id_paciente = '".$_REQUEST[datos_estacion]['tipo_id_paciente']."'),
                    inv_med_cod_principios_activos as c, inventarios_productos as h,
                    medicamentos as k, unidades as l
     
                    WHERE	n.estado = '0' and a.sw_estado = '1' and
                    k.cod_principio_activo = c.cod_principio_activo
                    and h.codigo_producto = k.codigo_medicamento and
                    a.codigo_producto = h.codigo_producto
                    and h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
                    $search	order by k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto;";
                    
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }
                    else
                    {
                         while (!$result->EOF)
                         {
                              $var[]=$result->GetRowAssoc($ToUpper = false);
                              $result->MoveNext();
                         }
                    }
                    //$result->Close();
                    $var[0][uso_controlado]=$uso_controlado;
                    $var[0][razon_social]=$_SESSION['ESTACION_ENFERMERIA']['EMP'];
     
		          //obteniendo la cuota moderadora solo para cuando el plan es = 3 y sw_pos = 1
                    if($_REQUEST['sw_pos']=='1' AND $var[0][sw_tipo_plan]==3)
                    {
                         if((!empty($var[0][rango])) AND (!empty($var[0][plan_id])) AND
                         (!empty($var[0][tipo_afiliado_id])))
                         {
                              $query="select cuota_moderadora from planes_rangos
                              where plan_id = ".$var[0][plan_id]."
                              AND tipo_afiliado_id = '".$var[0][tipo_afiliado_id]."'
                              AND rango = '".$var[0][rango]."';";

                              $result = $dbconn->Execute($query);
                              if ($dbconn->ErrorNo() != 0)
                              {
                                   $this->error = "Error al Cargar el Modulo";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   return false;
                              }
                              else
                              {
                                   $cuotam=$result->GetRowAssoc($ToUpper = false);
                              }
                              $var[0][cuota_moderadora]=$cuotam;
                         }
                    }
     
                    //obteniendo la posologia para cada medicamento DE HOSPITALIZACION que se va a imprimir en la formula medica.
                    for($i=0;$i<sizeof($var);$i++)
                    {
                         $query == '';
                         unset ($vector);
                         if ($var[$i][tipo_opcion_posologia_id] == 1)
                         {
                              $query= "select periocidad_id, tiempo from hc_posologia_horario_op1_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
                         }
                         if ($var[$i][tipo_opcion_posologia_id] == 2)
                         {
                              $query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2_hosp as a, hc_horario as b where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."' and a.duracion_id = b.duracion_id";
                         }
                         if ($var[$i][tipo_opcion_posologia_id] == 3)
                         {
                              $query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
                         }
                         if ($var[$i][tipo_opcion_posologia_id] == 4)
                         {
                              $query= "select hora_especifica from hc_posologia_horario_op4_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
                         }
                         if ($var[$i][tipo_opcion_posologia_id] == 5)
                         {
                              $query= "select frecuencia_suministro from hc_posologia_horario_op5_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
                         }

                         if ($query!='')
                         {
                              $result = $dbconn->Execute($query);
                              if ($dbconn->ErrorNo() != 0)
                              {
                                   $this->error = "Error al buscar en la consulta de medicamentos recetados";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   return false;
                              }
                              else
                              {
                                   if ($var[$i][tipo_opcion_posologia_id] != 4)
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
                         $var[$i][posologia]=$vector;
                         unset($vector);
                    }
     
                    //hallando la evolucion maxima  caso especial de hospitalizacion.
                    $query= "SELECT a.evolucion_id, c.nombre_tercero,
                    c.tipo_id_tercero as tipo_id_medico,
                    c.tercero_id as medico_id, d.tarjeta_profesional,
                    e.descripcion as tipo_profesional
                    FROM hc_evoluciones a, profesionales_usuarios b,
                    terceros c, profesionales d,
                    tipos_profesionales e where (select max (evolucion_id) from hc_evoluciones
                    where ingreso = ".$var[0][ingreso]." and estado ='0') = a.evolucion_id
                    and a.usuario_id = b.usuario_id
                    and b.tipo_tercero_id = c.tipo_id_tercero AND b.tercero_id = c.tercero_id
                    and b.tipo_tercero_id = d.tipo_id_tercero AND b.tercero_id = d.tercero_id
                    and d.tipo_profesional = e.tipo_profesional";
     
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }
                    else
                    {
                         $medico_evol_max=$result->GetRowAssoc($ToUpper = false);
                    }
                    $var[0][medico_evol_max]=$medico_evol_max;
     
                    $classReport = new reports;
                    $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
                    $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='EstacionE_Medicamentos',$reporte_name='formula_medica_estacion',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
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
	     }
          $this->FrmImpresionMedicamentos();
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

}//end of class

?>
