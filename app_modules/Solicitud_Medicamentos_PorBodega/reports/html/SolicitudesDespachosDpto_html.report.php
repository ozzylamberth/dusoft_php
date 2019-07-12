<?php

/**
 * $Id: SolicitudesDespachosDpto_html.report.php,v 1.7 2009/04/21 22:17:20 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */
	
class SolicitudesDespachosDpto_html_report
{
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;

	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function SolicitudesDespachosDpto_html_report($datos=array())
	{
		$this->datos=$datos;
		return true;
	}

	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
																'subtitulo'=>'',
																'logo'=>'logocliente.png',
																'align'=>'left'));
		return $Membrete;
	}
	
	function CrearReporte()
	{
		$style= "style=\"font-size:11px; font-weight:bold;\"";
		$style1= "style=\"font-size:11px\"";
		
		$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$Salida.="	<tr>";
		$Salida.="		<td><img src=\"../../../../images/logocliente.png\" border=\"0\"></td>";
		$Salida.="	</tr>";
		$Salida.="</table><br>";
		if($this->datos['sw']==1)
			$titulo="SOLICITUDES DESPACHADAS SIN CONFIRMAR DE MEDICAMENTOS E INSUMOS";
		else
			$titulo="SOLICITUDES DE MEDICAMENTOS E INSUMOS";
		
		$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$Salida.="<tr>";
		$Salida.="  <td $style align=\"center\" width=\"100%\">$titulo</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td $style align=\"center\" width=\"100%\">ESTACION :&nbsp&nbsp&nbsp; ".$this->datos['estacion']."</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td $style align=\"center\" width=\"100%\">BODEGA :&nbsp&nbsp&nbsp; ".$_SESSION['Bodegas']['bodega']." - ".$_SESSION['Bodegas']['bodega_desc']."</td>";
		$Salida.="</tr>";
		$Salida.="</table><BR>";
		$marca=0;
		$solicitudes=explode(".-.",$this->datos['solicitudes']);
		$vector=$this->DatosSolicitudesDepartamento($this->datos['estacion_id'],$this->datos['SolicitudId'],$this->datos['sw'],$this->datos['sw_imp']);
		if($vector)
    {
      $marca = $marca1 = true;
			foreach($vector as $paciente=>$vector)
      {
				$pacienteAnt=-1;
				foreach($vector as $solicitudId=>$vector1)
        {
					$solicitudIdAnt=-1;
          $mk_md = $mk_in = false;
					foreach($vector1 as $consecutivoId=>$datos)
          {
						if($paciente!=$pacienteAnt)
            {
							if(!$marca) $Salida.="</table><br>\n";
              if(!$marca)$Salida.="<br><br>";
              
              $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">\n";
							$Salida.="<tr>\n";
							$Salida.="  <td $style align=\"left\" width=\"15%\">PACIENTE</td>\n";
							$Salida.="  <td $style1  align=\"left\" colspan=\"3\">$paciente ".$datos['nombrepac']."</td>\n";
							$Salida.="</tr>\n";
							$Salida.="<tr>\n";
							$Salida.="  <td $style align=\"left\" width=\"15%\">PLAN</td>\n";
							$Salida.="  <td $style1  align=\"left\" width=\"30%\">".$datos['plan_descripcion']."</td>\n";
							$Salida.="  <td $style align=\"left\" width=\"15%\">PIEZA Y CAMA</td>\n";
							$Salida.="  <td $style1 align=\"left\">".$datos['pieza']." ".$datos['cama']."</td>\n";
							$Salida.="</tr>\n";
							$Salida.="<tr>\n";
							$Salida.="  <td $style align=\"left\" width=\"15%\">TIPO AFILIADO</td>\n";
							$Salida.="  <td $style1  align=\"left\" width=\"30%\">".$datos['tipo_afiliado_id']."</td>\n";
							$Salida.="  <td $style align=\"left\" width=\"15%\">RANGO</td>\n";
							$Salida.="  <td $style1  align=\"left\">".$datos['rango']."</td>\n";
							$Salida.="</tr>\n";
							$Salida.="</table>\n";
              $marca1 = false;
              $marca = true;
						}
						
            $codigo_pro=$datos['codigo_producto'];
            $descrip=$datos['desmed'];
            $cantidad=$datos['cant_solicitada'];
            $abreviatura=$datos['abreviatura'];
            if($this->datos['sw']==1)
            {
              $codigo_pro=$datos['codigo_producto_des'];
              $descrip=$datos['desmed_des'];
              $cantidad=$datos['cantidad_des'];
              $abreviatura=$datos['abreviatura_des'];
            }
            
            if($solicitudId!=$solicitudIdAnt)
            {
              if(!$marca)$Salida.="</table><br>\n";
              
              $marca = false;
              $Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\" rules=\"all\" class=\"normal_10\">\n";
              $Salida.="<tr>";
              $Salida.="  <td $style align=\"left\" width=\"15%\">No. SOLICITUD</td>";
              $Salida.="  <td $style1  align=\"left\" width=\"30%\">$solicitudId</td>";
              $Salida.="  <td $style align=\"left\" width=\"15%\">FECHA</td>";
              (list($fecha,$HoraTot)=explode(' ',$datos['fecha_solicitud']));
              (list($ano,$mes,$dia)=explode('-',$fecha));
              (list($hora,$min)=explode(':',$HoraTot));                    
              $Salida.="  <td $style1 align=\"left\" colspan=\"2\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
              $Salida.="</tr>";
              $Salida.="<tr>";
              $Salida.="  <td $style align=\"left\" width=\"15%\">ESTACION</td>";
              $Salida.="  <td $style1  align=\"left\" width=\"30%\">".$datos['nomestacion']."</td>";
              $Salida.="  <td $style align=\"left\" width=\"15%\">USUARIO QUE SOLICITA</td>";
              $Salida.="  <td $style1  colspan=\"2\" align=\"left\">".$datos['usuario_id'].' - '.$datos['usuarioestacion']."</td>";
              $Salida.="</tr>";
              
            }
            if(($datos['tipo_producto'] == "M" || !$datos['tipo_producto']) && !$mk_md)
            {
              $mk_md = true;
              $Salida.="<tr>";
              $Salida.="  <td $style align=\"center\" width=\"15%\">CODIGO</td>";
              $Salida.="  <td $style align=\"center\" colspan=\"3\" width=\"60%\">MEDICAMENTO</td>";
              $Salida.="  <td $style  align=\"center\" width=\"25%\">CANTIDAD</td>";
              $Salida.="</tr>";
						}            
            if($datos['tipo_producto'] == "I" && !$mk_in)
            {
              $mk_in = true;
              $Salida.="<tr>";
              $Salida.="  <td $style align=\"center\" width=\"15%\">CODIGO</td>";
              $Salida.="  <td $style align=\"center\" colspan=\"3\" width=\"60%\">INSUMO</td>";
              $Salida.="  <td $style  align=\"center\" width=\"25%\">CANTIDAD</td>";
              $Salida.="</tr>";
						}
            $Salida.="<tr>";
            $Salida.="  <td $style1 align=\"left\" >".$codigo_pro."</td>";
            $Salida.="  <td $style1 align=\"left\" colspan=\"3\">".$descrip."</td>";
            $Salida.="  <td $style1  align=\"left\" >".FormatoValor($cantidad)." ".$abreviatura."</td>";
            $Salida.="</tr>\n";
            if($datos['ubicacion'])
            {
              $Salida.="<tr>\n";
              $Salida.="  <td colspan=\"5\" $style1 align=\"left\">".$datos['ubicacion']."</td>\n";
              $Salida.="</tr>\n";
            }
 						$pacienteAnt=$paciente;
            $solicitudIdAnt=$solicitudId;
					}
        }
        //$Salida .= "<br><br>\n"; 
      }
      if(!$marca) $Salida.="</table><br>\n";
    }
		
		$usuario=$this->GetInfoUsuario();
		
		$Salida.= "<br><div align=\"right\"><label style=\"font-size:10px\"> Imprimió: ".$usuario[usuario_id]." - ".$usuario[nombre]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha Impresión:&nbsp;&nbsp;".date("Y-m-d , g:i a")."</label></div>";
		
		$this->UpdateImpreso($solicitudes);
		
		echo $Salida;
	}
	
	function UpdateImpreso($solicitudes)
	{
		list($dbconn) = GetDBconn();
		
		
		$query1="	SELECT max(sw_impreso)
							FROM hc_solicitudes_medicamentos";
			
		$result = $dbconn->Execute($query1);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$num=$result->fields[0];
		
		foreach($solicitudes as $soli)
		{
			$query1="	SELECT sw_impreso
								FROM hc_solicitudes_medicamentos
								WHERE solicitud_id=$soli;";
				
			$result1 = $dbconn->Execute($query1);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$sw_impreso=$result1->fields[0];
			
			if($sw_impreso==0)
			{
				$query="	UPDATE hc_solicitudes_medicamentos
									SET sw_impreso=".($num+1).",
									usuario_imp=".UserGetUID()."
									WHERE solicitud_id=$soli;";
				
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					echo $dbconn->ErrorMsg();
					return false;
				}
			}
			else break;
		}
		return true;
	}
	
	function GetInfoUsuario($usuario_id)
	{
		
		if(!$usuario_id)
			$usuario_id=UserGetUID();
			
		list($dbconn) = GetDBconn();
		
		$query="SELECT *
						FROM system_usuarios
						WHERE usuario_id=$usuario_id";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}
	
	function DatosSolicitudesDepartamento($estacion_id,$solicitud,$sw,$sw_imp=0)
	{
		list($dbconn) = GetDBconn();
		$imp="";
		if($solicitud)
		{
			$cond="AND a.solicitud_id=$solicitud";
		}
		else
		{
			if(!$sw_imp)
				$imp="AND a.sw_impreso=0";
			else
				$imp="AND a.sw_impreso=".$sw_imp;
		}

		if($sw==1)
		{
				$query ="
									(
										SELECT  ing.tipo_id_paciente||' '||ing.paciente_id,
														a.solicitud_id,
														bgd.consecutivo_solicitud,
														a.tipo_solicitud,
														a.estacion_id,
														a.fecha_solicitud as fecha_solicitud,
														a.ingreso,
														su.nombre as usuarioestacion,
														a.usuario_id,
														dep.descripcion as deptoestacion,
														cu.rango,
														k.tipo_afiliado_nombre as tipo_afiliado_id,
														p.plan_descripcion,
														pa.primer_nombre||' '||pa.segundo_nombre||' '||pa.primer_apellido||' '||pa.segundo_apellido as nombrepac,
														est.descripcion as nomestacion,
														j.cama,
														j.pieza,
														bgd.codigo_producto as codigo_producto_des,
														invp.descripcion as desmed_des,
														bgd.cantidad as cantidad_des,
														u.abreviatura as abreviatura_des,
														bu.descripcion as ubicacion,
														exis.existencia,
                            'M' AS tipo_producto
											FROM 	hc_solicitudes_medicamentos a,
														bodegas_documento_despacho_med as bg,
														bodegas_documento_despacho_med_d as bgd,
														ingresos as ing,
														estaciones_enfermeria as est,
														departamentos as dep,
														system_usuarios as su,
														cuentas cu
														LEFT JOIN movimientos_habitacion f 
														ON
														(
															cu.numerodecuenta=f.numerodecuenta 
															AND f.fecha_egreso is NULL
														)
														LEFT JOIN camas j 
														ON
														(
															f.cama=j.cama
														),
														tipos_afiliado k,
														planes p,
														pacientes pa,
														inventarios_productos invp
														LEFT JOIN existencias_bodegas exis 
														ON 
														(
															invp.codigo_producto=exis.codigo_producto 
															AND exis.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
															AND exis.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
															AND exis.bodega='".$_SESSION['Bodegas']['bodega']."' 
														)
														LEFT JOIN bodegas_ubicaciones bu 
														ON 
														(
															exis.ubicacion_id=bu.ubicacion_id
														),
														unidades u
										WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."'  
										AND 	a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
										AND 	a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
										AND 	est.estacion_id='$estacion_id'
										AND 	a.sw_estado='$sw'
										$imp
										AND 	a.documento_despacho=bg.documento_despacho_id
										AND 	bgd.documento_despacho_id=bg.documento_despacho_id
										AND 	bgd.codigo_producto=invp.codigo_producto
										AND 	a.ingreso=ing.ingreso
										AND 	a.estacion_id=est.estacion_id
										AND 	est.departamento=dep.departamento
										AND 	a.ingreso=cu.ingreso
										AND 	a.usuario_id=su.usuario_id
										AND 	(cu.estado='1' OR cu.estado='2')
										AND 	k.tipo_afiliado_id=cu.tipo_afiliado_id 
										AND 	cu.plan_id=p.plan_id 
										AND 	ing.tipo_id_paciente=pa.tipo_id_paciente 
										AND 	ing.paciente_id=pa.paciente_id 
										AND 	invp.unidad_id=u.unidad_id
										$cond
								)
								UNION
								(
										SELECT  ing.tipo_id_paciente||' '||ing.paciente_id,
													a.solicitud_id,
													bgd.consecutivo_solicitud,
													a.tipo_solicitud,
													a.estacion_id,
													a.fecha_solicitud as fecha_solicitud,
													a.ingreso,
													su.nombre as usuarioestacion,
													a.usuario_id,
													dep.descripcion as deptoestacion,
													cu.rango,
													k.tipo_afiliado_nombre as tipo_afiliado_id,
													p.plan_descripcion,
													pa.primer_nombre||' '||pa.segundo_nombre||' '||pa.primer_apellido||' '||pa.segundo_apellido as nombrepac,
													est.descripcion as nomestacion,
													j.cama,
													j.pieza,
													bgd.codigo_producto as codigo_producto_des,
													invp.descripcion as desmed_des,
													bgd.cantidad as cantidad_des,
													u.abreviatura as abreviatura_des,
													bu.descripcion as ubicacion,
													exis.existencia,
                          'I' AS tipo_producto
										FROM 	hc_solicitudes_medicamentos a,
													bodegas_documento_despacho_med as bg,
													bodegas_documento_despacho_ins_d as bgd,
													ingresos as ing,
													estaciones_enfermeria as est,
													departamentos as dep,
													system_usuarios as su,
													cuentas cu
													LEFT JOIN movimientos_habitacion f 
													ON
													(
														cu.numerodecuenta=f.numerodecuenta 
														AND f.fecha_egreso is NULL
													)
													LEFT JOIN camas j 
													ON
													(
														f.cama=j.cama
													),
													tipos_afiliado k,
													planes p,
													pacientes pa,
													inventarios_productos invp
													LEFT JOIN existencias_bodegas exis 
													ON 
													(
														invp.codigo_producto=exis.codigo_producto 
														AND exis.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
														AND exis.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
														AND exis.bodega='".$_SESSION['Bodegas']['bodega']."' 
													)
													LEFT JOIN bodegas_ubicaciones bu 
													ON 
													(
														exis.ubicacion_id=bu.ubicacion_id
													),
													unidades u
										WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."' 
										AND 	a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
										AND 	a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
										AND 	est.estacion_id='$estacion_id'
										AND 	a.sw_estado='$sw'
										$imp
										AND 	a.documento_despacho=bg.documento_despacho_id
										AND 	bgd.documento_despacho_id=bg.documento_despacho_id
										AND 	bgd.codigo_producto=invp.codigo_producto
										AND 	a.ingreso=ing.ingreso
										AND 	a.estacion_id=est.estacion_id
										AND 	est.departamento=dep.departamento
										AND 	a.ingreso=cu.ingreso
										AND 	a.usuario_id=su.usuario_id
										AND 	(cu.estado='1' OR cu.estado='2')
										AND 	k.tipo_afiliado_id=cu.tipo_afiliado_id 
										AND 	cu.plan_id=p.plan_id 
										AND 	ing.tipo_id_paciente=pa.tipo_id_paciente 
										AND 	ing.paciente_id=pa.paciente_id 
										AND 	invp.unidad_id=u.unidad_id
										$cond
								);	
							";					
		}
		else
		{
				$query = "(
										SELECT 	i.tipo_id_paciente||' '||i.paciente_id,
														a.solicitud_id,
														det.consecutivo_d,
														a.estacion_id,
														a.fecha_solicitud as fecha_solicitud,
														a.ingreso,
														d.nombre as usuarioestacion,
														a.usuario_id,
														c.descripcion as deptoestacion,
														e.rango,
														k.tipo_afiliado_nombre as tipo_afiliado_id,
														h.plan_descripcion,
														l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
														j.cama,
														j.pieza,
														a.tipo_solicitud,
														b.descripcion as nomestacion,
														det.medicamento_id as codigo_producto,
														invp.descripcion as desmed,
														det.cant_solicitada,
														bu.descripcion as ubicacion,
														u.abreviatura,
														exis.existencia,
                            'M' AS tipo_producto
											FROM 	hc_solicitudes_medicamentos a,
														estaciones_enfermeria b,
														departamentos c,
														system_usuarios d,
														cuentas e
											LEFT JOIN movimientos_habitacion f 
											ON
											(
												e.numerodecuenta=f.numerodecuenta 
												AND f.fecha_egreso is NULL
											)
											LEFT JOIN camas j 
											ON
											(
												f.cama=j.cama
											),
											planes h,
											ingresos i,
											tipos_afiliado k,
											pacientes l,
											hc_solicitudes_medicamentos_d det,
											inventarios_productos invp
											LEFT JOIN existencias_bodegas exis 
											ON 
											(
												invp.codigo_producto=exis.codigo_producto 
												AND exis.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
												AND exis.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
												AND exis.bodega='".$_SESSION['Bodegas']['bodega']."'
											)
											LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id),
											unidades u
											WHERE 	a.bodega='".$_SESSION['Bodegas']['bodega']."' 
											AND 		a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
											AND			a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
											AND 		a.sw_estado='$sw'
											$imp
											AND 		a.estacion_id=b.estacion_id 
											AND 		b.estacion_id='".$estacion_id."'
											AND 		b.departamento=c.departamento 
											AND 		a.usuario_id=d.usuario_id 
											AND 		a.ingreso=e.ingreso 
											AND 		(e.estado='1' OR e.estado='2')
											AND 		a.ingreso=i.ingreso 
											AND 		e.plan_id=h.plan_id 
											AND 		k.tipo_afiliado_id=e.tipo_afiliado_id 
											AND 		i.tipo_id_paciente=l.tipo_id_paciente 
											AND 		i.paciente_id=l.paciente_id 
											AND			a.solicitud_id=det.solicitud_id 
											AND			det.medicamento_id=invp.codigo_producto 
											AND 		invp.unidad_id=u.unidad_id
											$cond
											ORDER BY l.tipo_id_paciente,l.paciente_id,a.fecha_solicitud
									)
									UNION ALL
									(
										SELECT 	i.tipo_id_paciente||' '||i.paciente_id,
														a.solicitud_id,
														det.consecutivo_d,
														a.estacion_id,
														a.fecha_solicitud,
														a.ingreso,
														d.nombre as usuarioestacion,
														a.usuario_id,
														c.descripcion as deptoestacion,
														e.rango,
														k.tipo_afiliado_nombre as tipo_afiliado_id,
														h.plan_descripcion,
														l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
														j.cama,
														j.pieza,
														a.tipo_solicitud,
														b.descripcion as nomestacion,
														det.codigo_producto,
														invp.descripcion as desmed,
														det.cantidad as cant_solicitada,
														bu.descripcion as ubicacion,
														u.abreviatura,exis.existencia,
                            'I' AS tipo_producto
										FROM 		hc_solicitudes_medicamentos a,
														estaciones_enfermeria b,
														departamentos c,
														system_usuarios d,
														cuentas e
										LEFT JOIN movimientos_habitacion f 
										ON
										(
											e.numerodecuenta=f.numerodecuenta 
											AND f.fecha_egreso is NULL
										)
										LEFT JOIN camas j 
										ON
										(
											f.cama=j.cama
										),
										planes h,
										ingresos i,
										tipos_afiliado k,
										pacientes l,
										hc_solicitudes_insumos_d det,
										inventarios_productos invp
										LEFT JOIN existencias_bodegas exis 
										ON 
										(
											invp.codigo_producto=exis.codigo_producto 
											AND exis.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
											AND exis.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
											AND exis.bodega='".$_SESSION['Bodegas']['bodega']."'
										)
										LEFT JOIN bodegas_ubicaciones bu 
										ON 
										(
											exis.ubicacion_id=bu.ubicacion_id
										),
										unidades u
							WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."' 
							AND 	a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' 
							AND 	a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' 
							AND 	a.sw_estado='$sw'
							$imp
							AND 	a.estacion_id=b.estacion_id 
							AND 	b.estacion_id='".$estacion_id."'
							AND 	b.departamento=c.departamento 
							AND 	a.usuario_id=d.usuario_id 
							AND 	a.ingreso=e.ingreso 
							AND 	(e.estado='1' OR e.estado='2')
							AND 	a.ingreso=i.ingreso 
							AND 	e.plan_id=h.plan_id 
							AND 	k.tipo_afiliado_id=e.tipo_afiliado_id 
							AND 	i.tipo_id_paciente=l.tipo_id_paciente 
							AND 	i.paciente_id=l.paciente_id 
							AND		a.solicitud_id=det.solicitud_id 
							AND 	det.codigo_producto=invp.codigo_producto 
							AND invp.unidad_id=u.unidad_id
							$cond
							ORDER BY l.tipo_id_paciente,l.paciente_id,a.fecha_solicitud)";
		}
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars[$result->fields[0]][$result->fields[1]][$result->fields[2]]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
		
		return $vars;
	}
}
$VISTA = "HTML";
$_ROOT = "../../../../";
include	 $_ROOT."includes/enviroment.inc.php";
$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
IncludeFile($filename);


$obj=new SolicitudesDespachosDpto_html_report($_REQUEST);
$obj->CrearReporte();

?>