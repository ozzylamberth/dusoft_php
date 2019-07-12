<?php

/**
 * $Id: solicitudes.report.php,v 1.1.1.1 2009/09/11 20:36:19 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de ordenservicio para impresora pos
 */

class solicitudes_report extends pos_reports_class
{

    //constructor por default
    function solicitudes_report()
    {
        $this->pos_reports_class();
        return true;
    }

		/**
		*
		*/
    function CrearReporte()
    {

        $reporte=&$this->driver; //obtener el driver
        $datos=&$this->datos; //obtener los datos enviados al reporte.

        $reporte->PrintFTexto($datos[0][razon_social],true,$align='center',false,true);
        $edad=$this->EdadPacientes($datos[0][paciente_id],$datos[0][tipo_id_paciente]);
        $edadTotal=explode(':',$edad);
        $reporte->PrintFTexto($datos[0][tipo_id_tercero].' '.$datos[0][id],false,'center',false,false);
        //$reporte->PrintFTexto($datos[0][direccion].' '.$datos[0][municipio].' '.$datos[0][departamento],false,'center',false,false);
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto('Fecha    : '.date('d/m/Y h:m'),false,'left',false,false);
        //var=$this->NombreUsuario();
        $cad=substr('Atendio : '.$datos[0][usuario_id].' - '.$datos[0][usuario],0,42);
        $reporte->PrintFTexto($cad,false,'left',false,false);
        //$reporte->PrintFTexto('Atendio  : '.$datos[0][usuario_id].' - '.$datos[0][usuario],false,'left',false,false);
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto('Identifi: '.$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id],false,'left',false,false);
        $reporte->PrintFTexto('Edad: '.$edadTotal[0],false,'left',false,false);
        $reporte->PrintFTexto('Paciente: '.$datos[0][nombre],false,'left',false,false);
        $reporte->PrintFTexto('Cliente : '.$datos[0][nombre_tercero],false,'left',false,false);
        $reporte->PrintFTexto('Plan    : '.$datos[0][plan_descripcion],false,'left',false,false);
        $reporte->PrintFTexto('Tipo Afi: '.$datos[0][tipo_afiliado_nombre].'     Rango: '.$datos[0][rango],false,'left',false,false);
        $fech=explode(".",$datos[0][fecha]);
        $reporte->SaltoDeLinea();
        $pro=$this->Profesional($datos[1][evolucion_id]);
        $reporte->PrintFTexto('Profesional: '.$pro[0][nombre_tercero],false,'left',false,false);
        for($j=0; $j<sizeof($pro); $j++)
        {
                        $reporte->PrintFTexto('Espec: '.$pro[$j][descripcion],false,'left',false,false); 
        }
        $reporte->SaltoDeLinea();
        $reporte->PrintFTexto('SOLICITUD DE AUTORIZACIONES',true,'center',false,false);
        
        /* VARIABLE USADA PARA LA IMPRESIÓN DE LA TARJETA DE PRESENTACIÓN
         * DEBE IMPRIMIRSE UNA SOLA VEZ, SI LOS PROCEDIMIENTOS QUIRUGICOS SON
         * MAS DE DOS
         */
        $ingnivel = 0;
        for($i=1; $i<sizeof($datos);$i++)
        {
                $bdDA = $this->TraerPlanD($datos[$i][hc_os_solicitud_id]);
                $tarifario_iddea="";
                $tarifario_dedea="";
                if (count($bdDA) > 0){
                    $tarifario_iddea = $bdDA['grupo_tarifario_id'];
                    $tarifario_dedea = $bdDA['grupo_tarifario_descripcion'];
                    $subtarifario_iddea = $bdDA['subgrupo_tarifario_id'];
                    $subtarifario_dedea = $bdDA['subgrupo_tarifario_descripcion'];
                }

                $reporte->SaltoDeLinea();
                $inter=$this->Interconsulta($datos[$i][hc_os_solicitud_id]);
                $reporte->PrintFTexto($datos[$i][hc_os_solicitud_id].' - '.$datos[$i][cargos].' - ( '.$datos[$i][cantidad].' )'.$datos[$i][descar].' '.$inter,false,'left',false,false);
                $reporte->SaltoDeLinea();
                $reporte->PrintFTexto('GRUPO TARIFARIO: '.$tarifario_iddea.' - '.$tarifario_dedea,false,'left',false,false);
                $reporte->SaltoDeLinea();
                $reporte->PrintFTexto('SUBGRUPO TARIFARIO: '.$subtarifario_iddea.' - '.$subtarifario_dedea,false,'left',false,false);
                $reporte->SaltoDeLinea();
                
                if(!empty($datos[$i][trap]))
                {  $reporte->PrintFTexto($datos[$i][trap].' días de Tramite.',false,'left',false,false);  }
                elseif(!empty($datos[$i][tra]))
                {  $reporte->PrintFTexto($datos[$i][tra].' días de Tramite.',false,'left',false,false);  }

            
                $reporte->SaltoDeLinea();
                $diagComp=$this->DiagnosticoCompleto($datos[$i][evolucion_id]);
                $diagS=$this->DiagnosticoSolicitudCompleto($datos[$i][hc_os_solicitud_id]);
                if(!empty($diagS))
                {  $diagnostico=$diagS;}	
                else
                { $diagnostico=$diagComp;}
                foreach($diagnostico as $key => $dtl)
                {
                $reporte->PrintFTexto($dtl[diagnostico_id].' - '.$dtl[diagnostico_nombre],false,'left',false,false);
                }

                //EMPIEZAN MODIFICACION -- JONIER
                $cadenapre =  "";
                $cadenapos =  "";
                $solsol = $this->ObtenerSolicitudes($datos[$i][hc_os_solicitud_id]);
                for($d=0; $d < count($solsol); $d++){
                  $fechasolicitudqx = $solsol[$d][fecha_solicitud];
                  $fechatentativacirugia = $solsol[$d][fecha_tentativa_cirugia];
                  $niveld = $solsol[$d][nivel];

                  if ($solsol[$d][sw_pre_qx] == 1){
                      if (empty($cadenapre))
                        $cadenapre .= $solsol[$d][descripcion];
                      else
                        $cadenapre .= ", ".$solsol[$d][descripcion];
                  }elseif ($solsol[$d][sw_pos_qx] == 1){
                      
                      if (empty($cadenapos))
                        $cadenapos .= $solsol[$d][descripcion];
                      else
                        $cadenapos .= ", ".$solsol[$d][descripcion];
                  }
                }
                
                if ($ingnivel == 0){
                    if (count($solsol) > 0){
                        $reporte->PrintFTexto('Nivel: '.$niveld,false,'left',false,false); 
                        $reporte->PrintFTexto('Fecha Sugerida: '.$fechatentativacirugia,false,'left',false,false); 
                        $reporte->PrintFTexto('Estancia prequirurgica: '.$cadenapre,false,'left',false,false); 
                        $reporte->PrintFTexto('Estancia posquirurgica: '.$cadenapos,false,'left',false,false); 
                    }
                }
                
                if (!empty($datos[$i][os_tipo_solicitud_id])){
                    $tipsol = $datos[$i][os_tipo_solicitud_id];
                }else{
                    $tipsol = "";
                }
                    
                if(!empty($tipsol)){
                    $tabla = "";
                    if($tipsol == 'APD'){
                      $tabla = "hc_os_solicitudes_apoyod";
                    }elseif($tipsol == 'QX'){
                      $tabla = "hc_os_solicitudes_acto_qx";
                      $ingnivel = 1;
                    }elseif($tipsol == 'PNQ'){
                      $tabla = "hc_os_solicitudes_no_quirurgicos";
                    }elseif($tipsol == 'INT'){
                      $tabla = "hc_os_solicitudes_interconsultas";
                    }

                    $cadobse = "";
                    if(!empty($tabla)){
                      $obser = $this->ObtenerObservacionSolicitud($datos[$i][hc_os_solicitud_id], $tabla);
                      if (count($obser) > 0){
                          $cadobse = $obser[0][observacion];
                          $reporte->SaltoDeLinea();
                          $reporte->PrintFTexto('ObservaciÃ³n: '.$cadobse,false,'left',false,false); 
                      }
                    }
                }
        }
        $reporte->PrintFTexto('SOLICITUDES DE DIAGNOSTICOS',true,'center',false,false);

        $reporte->SaltoDeLinea();
        //verifica si el proveedor es interno
        $reporte->PrintEnd();
        //$reporte->OpenCajaMonedera();
        $reporte->PrintCutPaper();
        return true;
    }

    function Profesional($evolucion)
    {
	list($dbconn) = GetDBconn();
	$query = "select c.nombre_tercero, f.especialidad, g.descripcion
						from hc_evoluciones as a, profesionales_usuarios as b, terceros as c,
						profesionales_especialidades as f, especialidades as g
						where a.evolucion_id=".$evolucion." and a.usuario_id=b.usuario_id
						and b.tipo_tercero_id=c.tipo_id_tercero and b.tercero_id=c.tercero_id
						and f.tipo_id_tercero=c.tipo_id_tercero and f.tercero_id=c.tercero_id
						and f.especialidad=g.especialidad";
	$resulta=$dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
	}
	while(!$resulta->EOF)
	{
            $var[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
	}
	$resulta->Close();
	return $var;
    }
    
    function Interconsulta($hc_os_solicitud_id)
    {
	list($dbconn) = GetDBconn();
	$query = "select c.descripcion as especialidad_nombre
						from hc_os_solicitudes as a, hc_os_solicitudes_interconsultas as b, especialidades as c
						where a.hc_os_solicitud_id=b.hc_os_solicitud_id and b.especialidad=c.especialidad and
						a.hc_os_solicitud_id = $hc_os_solicitud_id";
	
	$resulta=$dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
	}
	if(!$resulta->EOF)
	{  $var=$resulta->fields[0];  }
        
        return $var;
    }
    
    function EdadPacientes($paciente_id,$tipo_id_paciente)
    {
	list($dbconn) = GetDBconn();
	$query = "select edad_completa(fecha_nacimiento) as edad
		  from   pacientes
		  where  paciente_id= '".$paciente_id."'
                  and    tipo_id_paciente='".$tipo_id_paciente."' 
                  ";
	
	$resulta=$dbconn->Execute($query);
	if ($dbconn->ErrorNo() != 0) 
	{
		$this->error = "Error al Guardar en la Base de Datos";
		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		return false;
	}
	if(!$resulta->EOF)
	{  $var=$resulta->fields[0];  }
	return $var;
    }
    /**
    * Obtiene la informacion del diagnostico
    *
    * @param integer $evolucion Identificador de la evolucion
    *
    * @return mixed
    */
    function DiagnosticoCompleto($evolucion)
    {
        $sql  = "SELECT DI.diagnostico_id, ";
        $sql .= "       DI.diagnostico_nombre ";
        $sql .= "FROM   hc_diagnosticos_ingreso HI,";
        $sql .= "       diagnosticos DI ";
        $sql .= "WHERE  HI.evolucion_id = ".$evolucion." ";
        $sql .= "AND    DI.diagnostico_id = HI.tipo_diagnostico_id ";
        $sql .= "ORDER BY sw_principal DESC ";

        list($dbconn) = GetDBconn();
        $rst = $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0)
          return false;

        $datos = array();
        while(!$rst->EOF)
        {
          $datos[] =  $rst->GetRowAssoc($ToUpper = false);
          $rst->MoveNext();
        }
        $rst->Close();

        $rst->Close();
        return $datos;
   }
   /**
  * Obtiene la informacion del diagnostico segun la solicitud
  *
  * @param integer $solicitud Identificador de la solicitud
  *
  * @return mixed
  */
  function DiagnosticoSolicitudCompleto($solicitud)
  {
  
    $sql  = "SELECT DI.diagnostico_id, ";
    $sql .= "       DI.diagnostico_nombre ";
    $sql .= "FROM   hc_os_solicitudes_diagnosticos HD, ";
    $sql .= "       diagnosticos DI ";
    $sql .= "WHERE  HD.hc_os_solicitud_id = ".$solicitud." ";
    $sql .= "AND    HD.diagnostico_id = DI.diagnostico_id ";
    
    
    list($dbconn) = GetDBconn();
    $rst = $dbconn->Execute($sql);
    if ($dbconn->ErrorNo() != 0) 
      return false;
    
    $datos = array();
    while(!$rst->EOF)
    {
      $datos[] =  $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    $rst->Close();

    $rst->Close();
    return $datos;
   }
        function ObtenerSolicitudes($hc_os_solicitud_id)
        {
              list($dbconn) = GetDBconn();
              $query = "SELECT  A.fecha_solicitud,
                                B.observacion,
                                C.fecha_tentativa_cirugia,
                                D.tipo_clase_cama_id,
                                D.sw_pre_qx,
                                D.sw_pos_qx,
                                F.descripcion,
                                E.descripcion as nivel
                        FROM hc_os_solicitudes A
                                INNER JOIN hc_os_solicitudes_acto_qx as B ON A.hc_os_solicitud_id = B.hc_os_solicitud_id
                                INNER JOIN hc_os_solicitudes_datos_acto_qx as C ON B.acto_qx_id = C.acto_qx_id
                                INNER JOIN hc_os_solicitudes_estancia as D ON D.acto_qx_id = C.acto_qx_id
                                INNER JOIN hc_os_solicitudes_niveles_autorizacion E ON E.nivel = C.nivel_autorizacion
                                LEFT JOIN cups as G ON G.cargo = A.cargo
                                RIGHT JOIN qx_grupos_tipo_cargo as H ON H.grupo_tipo_cargo = G.grupo_tipo_cargo
                                LEFT JOIN tipos_clases_camas F ON D.tipo_clase_cama_id = F.tipo_clase_cama_id
                        WHERE A.os_tipo_solicitud_id = 'QX' AND A.hc_os_solicitud_id = ".$hc_os_solicitud_id." 
                        ORDER BY D.sw_pre_qx DESC";
              $result = $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0)
              {
                $this->error = "Error al Cargar el Modulo EE";
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
              return $var;
        }
        
        function ObtenerObservacionSolicitud($hc_os_solicitud_id, $tabla)
        {
              list($dbconn) = GetDBconn();
              $query = "SELECT * FROM ".$tabla." WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id;


              $result = $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0)
              {
                $this->error = "Error al Cargar el Modulo EE";
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
              return $var;
        }
        
        function TraerPlanD($hc_os_solicitud_id) {
            list($dbconn) = GetDBconn();
            /*
            $query = "SELECT dd.*
                      FROM os_maestro om INNER JOIN os_maestro_cargos cmc ON (cmc.numero_orden_id = om.numero_orden_id)
                            INNER JOIN tarifarios_detalle dd ON (dd.tarifario_id = cmc.tarifario_id AND dd.cargo = cmc.cargo)
                      WHERE om.numero_orden_id = ".$numero_orden_id;
            */
            $query = " SELECT st.subgrupo_tarifario_id, st.subgrupo_tarifario_descripcion, 
                              gt.grupo_tarifario_id, gt.grupo_tarifario_descripcion
                       FROM hc_os_solicitudes hc INNER JOIN cups c ON (c.cargo = hc.cargo)
                              INNER JOIN subgrupos_tarifarios st ON (st.grupo_tarifario_id = c.grupo_tarifario_id AND st.subgrupo_tarifario_id = c.subgrupo_tarifario_id)
                              INNER JOIN grupos_tarifarios gt ON (gt.grupo_tarifario_id = st.grupo_tarifario_id)
                       WHERE hc.hc_os_solicitud_id = ".$hc_os_solicitud_id;


            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al traer la informacion de plan";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $plan = $result->GetRowAssoc($ToUpper = false);
            $result->Close();

            return $plan;
        }

}
?>
