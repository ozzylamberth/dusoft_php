<?php

/**
 * $Id: ordenservicio.report.php,v 1.1.1.1 2009/09/11 20:36:20 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de ordenservicio para impresora pos
 */

class ordenservicio_report extends pos_reports_class
{

    //constructor por default
    function ordenservicio_report()
    {
        $this->pos_reports_class();
        return true;
    }

    /**
    *
    */
    function CrearReporte()
    {
	IncludeLib("tarifario_cargos");
	$reporte=&$this->driver; //obtener el driver
	$datos=&$this->datos; //obtener los datos enviados al reporte.
	$reporte->PrintFTexto($datos[0][razon_social],true,$align='center',false,true);
	$reporte->PrintFTexto($datos[0][tipo_id_tercero].' '.$datos[0][id],false,'center',false,false);
        $edad=$this->EdadPacientes($datos[0][paciente_id],$datos[0][tipo_id_paciente]);
        $edadTotal=explode(':',$edad);
	//$reporte->PrintFTexto($datos[0][direccion].' '.$datos[0][municipio].' '.$datos[0][departamento],false,'center',false,false);
	$reporte->SaltoDeLinea();
	$reporte->PrintFTexto('ORDEN SERVICIO No. '.$datos[1][orden_servicio_id],true,'center',false,false);
	$reporte->SaltoDeLinea();
	//$reporte->PrintFTexto('Fecha impresiï¿½n: '.date('d/m/Y h:i'),false,'left',false,false);
	$cad=substr('Atendio : '.$datos[0][usuario_id].' - '.$datos[0][usuario],0,42);
	$reporte->PrintFTexto($cad,false,'left',false,false);
	$reporte->SaltoDeLinea();
	$reporte->PrintFTexto('Identifi: '.$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id],false,'left',false,false);
        $reporte->PrintFTexto('Edad: '.$edadTotal[0],false,'left',false,false);
	$reporte->PrintFTexto('Paciente: '.$datos[0][nombre],false,'left',false,false);
	$reporte->PrintFTexto('Cliente : '.$datos[0][nombre_tercero],false,'left',false,false);
	$reporte->PrintFTexto('Plan    : '.$datos[0][plan_descripcion],false,'left',false,false);
	$reporte->PrintFTexto('Tipo Afi: '.$datos[0][tipo_afiliado_nombre].' - Rango: '.$datos[0][rango],false,'left',false,false);
        if(!empty($datos[0][horas_estimadas]))
	  $reporte->PrintFTexto('Tirmpo: '.$datos[0][horas_estimadas].': '.$datos[0][minutos_estimados],false,'left',false,false);
	$PtoAtencion=$this->BuscarPuntoAtencion($datos[0][tipo_id_paciente],$datos[0][paciente_id]);
	
	if(!empty($PtoAtencion))
	{
		$reporte->PrintFTexto('Pto Atencion: '.$PtoAtencion,false,'left',false,false);
	}
				
	$total=0;
	$profe='';
	$copago=$moderadora=$nocub=0;
	$reporte->SaltoDeLinea();
	if(!empty($datos[0][observacion]))
	{
			$reporte->PrintFTexto('Observacion: '.$datos[0][observacion],false,'left',false,false);
			$reporte->SaltoDeLinea();
	}
        /* VARIABLE USADA PARA LA IMPRESIÓN DE LA TARJETA DE PRESENTACIÓN
         * DEBE IMPRIMIRSE UNA SOLA VEZ, SI LOS PROCEDIMIENTOS QUIRUGICOS SON
         * MAS DE DOS
         */
        $ingnivel = 0; $ingdiagnostico = 0;
	for($i=1; $i<sizeof($datos);)
	{
            $x=$i;
            while($datos[$i][cargo_cups]==$datos[$x][cargo_cups])
            {
                if(empty($datos[$x][evolucion_id]))
                {
                    $pro=$datos[$x][profesional];
                    if($pro!=$profe)
                    {
                        $profe=$pro;
                        $reporte->PrintFTexto('Profesional: '.$datos[$x][profesional],false,'left',false,false);
                        $reporte->SaltoDeLinea();
                    }
                }
                else
                {
                    $pro=$this->Profesional($datos[$x][evolucion_id]);
                    if($pro!=$profe)
                    {
                        $profe=$pro;
                        $reporte->PrintFTexto('Profesional: '.$pro[0][nombre_tercero],false,'left',false,false);
                        $reporte->PrintFTexto('Identificacion: '.$pro[0][tipo_id_tercero]." ".$pro[0][tercero_id],false,'left',false,false);
                        if($pro[0][tarjeta_profesional]!='')
                        {
                            $reporte->PrintFTexto('TP: '.$pro[0][tarjeta_profesional],false,'left',false,false);
                        }
                        for($j=0; $j<sizeof($pro); $j++)
                        {
                            $reporte->PrintFTexto('Espec: '.$pro[$j][descripcion],false,'left',false,false); 
                        }
                        $reporte->SaltoDeLinea();
                        
                    }
                }

                $inter=$datos[$x][especialidad_nombre];

                if ($ingdiagnostico == 0){
                    $diag=$this->DiagnosticoCompleto($datos[$x][evolucion_id]);
                    
                    if(!empty($diag))
                    {

                        $reporte->PrintFTexto('Diag:1 '.$diag[0]['diagnostico_id'].'  '.$diag[0]['diagnostico_nombre'],false,'left',false,false);
                    }
                    else
                    {
                        $diag=$this->DiagnosticoSolicitudCompleto($datos[$x][hc_os_solicitud_id]);
                        if(!empty($diag))
                        {
                            $reporte->PrintFTexto('SOLICITUDES DE DIAGNOSTICOS',true,'center',false,false);
                            for($d = 0; $d < count($diag); $d++){
                                $reporte->PrintFTexto($diag[$d]['diagnostico_id'].' - '.$diag[$d]['diagnostico_nombre'],false,'left',false,false);
                            }
                            $reporte->SaltoDeLinea();
                        }
                    }	
                }
                
                
                
                $reporte->PrintFTexto($datos[$x][numero_orden_id].' - '.$datos[$x][cargo_cups].' -  ( '.$datos[$i][cantidad].' ) '.$datos[$x][descripcion].' '.$inter,false,'left',false,false);
                $reporte->SaltoDeLinea();
                $ingdiagnostico = 1;

                //EMPIEZAN MODIFICACION -- JONIER
                
                //FUNCION QUE TRAE EL CODIGO DEL GRUPO Y SUBGRUPO TARIFARIO
                $bdDA = $this->TraerPlanD($datos[$x][hc_os_solicitud_id]);
                $tarifario_iddea="";
                $tarifario_dedea="";
                if (count($bdDA) > 0){
                    $tarifario_iddea = $bdDA['grupo_tarifario_id'];
                    $tarifario_dedea = $bdDA['grupo_tarifario_descripcion'];
                    $subtarifario_iddea = $bdDA['subgrupo_tarifario_id'];
                    $subtarifario_dedea = $bdDA['subgrupo_tarifario_descripcion'];
                }
                
                $reporte->PrintFTexto('GRUPO TARIFARIO: '.$tarifario_iddea.' - '.$tarifario_dedea,false,'left',false,false);
                $reporte->SaltoDeLinea();
                $reporte->PrintFTexto('SUBGRUPO TARIFARIO: '.$subtarifario_iddea.' - '.$subtarifario_dedea,false,'left',false,false);
                $reporte->SaltoDeLinea();
                
                $cadenapre =  "";
                $cadenapos =  "";
                //Trae el Tiempo de duración de la cirugia
                $solsol = $this->ObtenerTiempoDuracion($datos[$x][hc_os_solicitud_id]);
                if(count($solsol)){
                    $cadenatiempo = "Tiempo estimado de la cirugía: ".$solsol[0][horas_estimadas]." Horas ".$solsol[0][minutos_estimados]." Minutos.";
                }
                
                $solsol = $this->ObtenerSolicitudes($datos[$x][hc_os_solicitud_id]);
                
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
                
                if (count($solsol) > 0){
                    if($ingnivel == 0){
                        $reporte->PrintFTexto('Nivel: '.$niveld,false,'left',false,false); 
                        $reporte->PrintFTexto('Fecha Sugerida: '.$fechatentativacirugia,false,'left',false,false); 
                        $reporte->PrintFTexto('Estancia prequirurgica: '.$cadenapre,false,'left',false,false); 
                        $reporte->PrintFTexto('Estancia posquirurgica: '.$cadenapos,false,'left',false,false); 
                        if (!empty($cadenatiempo)){
                            $reporte->PrintFTexto($cadenatiempo,false,'left',false,false); 
                        }
                        $ingnivel = 1;
                    }
                }
                $tipsol = "";
                if (!empty($datos[$x][os_tipo_solicitud_id])){
                    $tipsol = $datos[$x][os_tipo_solicitud_id];
                }else{
                    $DTipsol = $this->ObtenerTipoSolicitud($datos[$x][hc_os_solicitud_id]);
                    if(count($DTipsol)>0){
                        $tipsol = $DTipsol[0]['os_tipo_solicitud_id'];
                    }
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
                    $material_especial="";
                    if(!empty($tabla)){
                        $obser = $this->ObtenerObservacionSolicitud($datos[$x][hc_os_solicitud_id], $tabla);
                        if (count($obser) > 0){
                            $cadobse = $obser[0][observacion];
                            $reporte->PrintFTexto('Observación: '.$cadobse,false,'left',false,false); 
                        }
                        if($tabla == "hc_os_solicitudes_acto_qx"){
                            $material_especial = $obser[0]['material_especial'];
                            if(!empty($material_especial)){
                                $reporte->PrintFTexto('Materiales Especiales: '.$material_especial,false,'left',false,false); 
                            }
                        } //UPDATE hc_os_solicitudes_acto_qx SET material_especial = 'EQUIPOS ELECTRICOS PRESTADOS' WHERE hc_os_solicitud_id = 2817831

                    }
                }
                //FIN ACTUALIZACIÓN JONIER
                $reporte->SaltoDeLinea();
                $reporte->PrintFTexto('Valida a Partir de: '.$this->FechaStamp($datos[$x][fecha_activacion]),false,'left',false,false);
                $reporte->PrintFTexto('Fecha Vencimiento : '.$this->FechaStamp($datos[$x][fecha_vencimiento]),false,'left',false,false);
                //11091927191 -- PUNCION CISTERNAL POR VIA LATERAL
                //4645429
                if (!(empty($datos[$x]['empresa_id']))){
                    $unifuncional = $this->ObtenerUnidadFuncional($datos[$x]['empresa_id'], $datos[$x]['centro_utilidad'], $datos[$x]['unidad_funcional']);
                    $reporte->SaltoDeLinea();
                    $reporte->PrintFTexto('PUNTO DE TOMADO: '.$unifuncional[0][descripcion],false,'left',false,false);
                    $reporte->PrintFTexto('Ubicación: '.$unifuncional[0][ubicacion],false,'left',false,false);
                    $reporte->PrintFTexto('Teléfono: '.$unifuncional[0][telefono],false,'left',false,false);
                }

                if(!empty($datos[$x][requisitos]))
                {
                    $reporte->SaltoDeLinea();
                    $reporte->PrintFTexto('Recomendaciones: '.$datos[$x][requisitos],false,'left',false,false);
                    $reporte->SaltoDeLinea();
                }
                $reporte->SaltoDeLinea();
                $x++;
            }
            $i=$x;
	}//FIN FOR $datos
        
	$reporte->SaltoDeLinea();
	//verifica si el proveedor es interno
	if(!empty($datos[1][desdpto]))
	{
		$dpto=$this->DatosDepto($datos[1][departamento]);
		$ubicacion=$datos[0][direccion];
		if(!empty($dpto[ubicacion]))
		{  $ubicacion=$dpto[ubicacion];  }
		$tel=$datos[0][telefonos];
		if(!empty($dpto[telefono]))
		{  $tel=$dpto[telefono];  }
                
		$reporte->PrintFTexto('PRESTADOR : '.$datos[1][desdpto].' - '.$datos[0][razon_social],true,'left',false,false);
		$reporte->PrintFTexto('Direccion : '.$ubicacion,false,'left',false,false);
		$reporte->PrintFTexto('Telefonos : '.$tel,false,'left',false,false);
	}
	elseif(!empty($datos[1][nompro]))
	{
                if (empty($datos[1][dirpro]) and !empty($datos[1]['evolucion_id'])){
                    $vedepdea = $this->TraerDepartamento($datos[1]['evolucion_id']);
                    $depdea = $vedepdea[0]['departamento'];
                    //plan_proveedor_id, PPU.direccion, PPU.telefono
                    $veclidea = $this->TraerUnidFuncional($depdea, $datos[1]['plan_proveedor_id']); 
                    $reporte->PrintFTexto('PRESTADOR : '.$datos[1][nompro],true,'left',false,false);
                    $reporte->PrintFTexto('Direccion : '.$veclidea[0]['direccion'],false,'left',false,false);
                    $reporte->PrintFTexto('Telefonos : '.$veclidea[0]['telefono'],false,'left',false,false);
                }else{
                    $reporte->PrintFTexto('PRESTADOR : '.$datos[1][nompro],true,'left',false,false);
                    $reporte->PrintFTexto('Direccion : '.$datos[1][dirpro],false,'left',false,false);
                    $reporte->PrintFTexto('Telefonos : '.$datos[1][telpro],false,'left',false,false);
                }
	}
	if($datos[1][sw_estado]==7)
	{
		$reporte->SaltoDeLinea();
		$reporte->PrintFTexto('NOTA: '.$datos[0][nombre_tercero].' por favor hacer Tramite de la Transcripciï¿½n a '.$datos[0][razon_social],true,'left',false,false);
	}
	$reporte->SaltoDeLinea();
	$cargo_liq=array();
	$d=1;
	while($d<sizeof($datos))
	{
		$cargo_liq[]=array('tarifario_id'=>$datos[$d]['tarifario_id'],'cargo'=>$datos[$d]['cargo'],'cantidad'=>1,'autorizacion_int'=>$datos[$d]['autorizacion_int'],'autorizacion_ext'=>$datos[$d]['autorizacion_ext']);
			$d++;
	}
        
	$cargo_fact=array();
	$cargo_fact=LiquidarCargosCuentaVirtual($cargo_liq,'','','',$datos[0][plan_id] ,$datos[0][tipo_afiliado_id] ,$datos[0][rango] ,$datos[0][semanas_cotizacion],$datos[0][servicio]);
	$copago=$cargo_fact[valor_cuota_paciente];
	$moderadora=$cargo_fact[valor_cuota_moderadora];
	$total=$cargo_fact[valor_total_paciente];
	$reporte->PrintFTextoValor($datos[0][nombre_copago],$copago,0,true,11,false,'left');
	$reporte->PrintFTextoValor($datos[0][nombre_cuota_moderadora],$moderadora,0,true,11,false,'left');
	if($nocub > 0)
	{  $reporte->PrintFTextoValor('Valor no Cubierto',$nocub,0,true,11,false,'left');  }
	$reporte->PrintFTextoValor('TOTAL A PAGAR',$total,0,true,11,true,'right');
	$reporte->PrintEnd();
	
	return true;
    }



    function Profesional($evolucion)
    {
            list($dbconn) = GetDBconn();
            $query = "select c.tipo_id_tercero, c.tercero_id, c.nombre_tercero, f.especialidad, g.descripcion, h.tarjeta_profesional
										from hc_evoluciones as a, profesionales_usuarios as b, terceros as c,
										profesionales_especialidades as f, especialidades as g, profesionales h
										where a.evolucion_id=".$evolucion." and a.usuario_id=b.usuario_id
										and b.tipo_tercero_id=c.tipo_id_tercero and b.tercero_id=c.tercero_id
										and f.tipo_id_tercero=c.tipo_id_tercero and f.tercero_id=c.tercero_id
										and f.especialidad=g.especialidad
										and h.tipo_id_tercero=c.tipo_id_tercero
										and h.tercero_id=c.tercero_id";
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

    function DatosDepto($dpto)
    {
            list($dbconn) = GetDBconn();
            $query = "select ubicacion, telefono, text1, text2
                      from departamentos where departamento='$dpto'";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
						$var=$resulta->GetRowAssoc($ToUpper = false);
            return $var;
    }


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
          return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
      }
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

	
	function BuscarPuntoAtencion($tipo_id_paciente, $paciente_id)
	{
		list($dbconn) = GetDBconn();
		$query = "	SELECT 	b.eps_punto_atencion_nombre
					FROM 	eps_afiliados as a, eps_puntos_atencion as b
					WHERE 	a.afiliado_tipo_id = '".$tipo_id_paciente."' 
					AND		a.afiliado_id = '".$paciente_id."'
					AND     a.eps_punto_atencion_id = b.eps_punto_atencion_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$var=$result->fields[0];
		}
		$result->Close();
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

    function ObtenerUnidadFuncional($empresa_id, $centro_utilidad, $unidad_funcional)
    {
          list($dbconn) = GetDBconn();
          $query = "SELECT descripcion, ubicacion, telefono FROM unidades_funcionales WHERE empresa_id = '".$empresa_id."' AND centro_utilidad = '".$centro_utilidad."' AND unidad_funcional = '".$unidad_funcional."'";
          
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
    
    function ObtenerTiempoDuracion($hc_os_solicitud_id)
    {
          list($dbconn) = GetDBconn();
          $query = "SELECT hd.fecha_tentativa_cirugia, hd.horas_estimadas, hd.minutos_estimados 
                    FROM hc_os_solicitudes_acto_qx h INNER JOIN hc_os_solicitudes_datos_acto_qx hd ON hd.acto_qx_id = h.acto_qx_id
                    WHERE h.hc_os_solicitud_id = ".$hc_os_solicitud_id;
          
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
    function ObtenerTipoSolicitud($hc_os_solicitud_id)
    {
          list($dbconn) = GetDBconn();
          $query = "SELECT os_tipo_solicitud_id FROM hc_os_solicitudes WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id;
          
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo EE";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
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

    function TraerDepartamento($hc_evolucion)
    {
          list($dbconn) = GetDBconn();
          $query = "SELECT departamento FROM hc_evoluciones WHERE evolucion_id = ".$hc_evolucion;
          
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo EE";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
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

    function TraerUnidFuncional($departamento, $plan_id)
    {
          list($dbconn) = GetDBconn();
          $query = "SELECT PPU.direccion, PPU.telefono
                    FROM departamentos cq INNER JOIN planes_proveedores_unidades_funcionales PPU
                        ON (PPU.empresa_id = cq.empresa_id 
                        AND PPU.	centro_utilidad = cq.centro_utilidad 
                        AND PPU.unidad_funcional = cq.unidad_funcional)
                    WHERE cq.departamento='$departamento' AND PPU.plan_proveedor_id = $plan_id";
          
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
            $this->error = "Error al Cargar el Modulo EE";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
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
    
}
?>
