<?php

/**
 * $Id: vouchercargos_CRV.inc.php,v 1.7 2008/07/18 15:14:04 julian Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

  
	function DatosPrincipales($cuenta)
	{
				list($dbconn) = GetDBconn();
				$query = "select count(*)
									from cuentas as a,
									fac_facturas_cuentas l
									where a.numerodecuenta=$cuenta 
									and a.numerodecuenta=l.numerodecuenta;";
				$result = $dbconn->Execute($query);
        if ($result->fields[0] > 0)
        {
					$query = "select (a.valor_total_paciente -(a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos)) as saldo,
										a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
										c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
										e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
										e.residencia_telefono, e.residencia_direccion, d.departamento_actual as dpto, h.descripcion,
										i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid, i.id, j.departamento,
										k.municipio, d.fecha_registro, a.valor_total_empresa, a.total_cuenta, a.valor_nocubierto,
										a.valor_cuota_paciente, a.valor_cuota_moderadora
										from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
										empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d,
										fac_facturas_cuentas l
										where a.numerodecuenta=$cuenta and a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
										and b.tipo_tercero_id=c.tipo_id_tercero
										and d.ingreso=a.ingreso and d.tipo_id_paciente=e.tipo_id_paciente
										and d.paciente_id=e.paciente_id
										and a.numerodecuenta=l.numerodecuenta  
										and l.empresa_id=i.empresa_id  
										and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
										and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
										and d.departamento_actual=h.departamento";
				}
				else
				{
					$query = "select (a.valor_total_paciente -(a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos)) as saldo,
										a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
										c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
										e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
										e.residencia_telefono, e.residencia_direccion, d.departamento_actual as dpto, h.descripcion,
										i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid, i.id, j.departamento,
										k.municipio, d.fecha_registro, a.valor_total_empresa, a.total_cuenta, a.valor_nocubierto,
										a.valor_cuota_paciente, a.valor_cuota_moderadora
										from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
										empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d
										where a.numerodecuenta=$cuenta 
										and a.plan_id=b.plan_id 
										and a.empresa_id=i.empresa_id
										and b.tercero_id=c.tercero_id
										and b.tipo_tercero_id=c.tipo_id_tercero
										and d.ingreso=a.ingreso 
										and d.tipo_id_paciente=e.tipo_id_paciente
										and d.paciente_id=e.paciente_id
										and i.tipo_pais_id=j.tipo_pais_id 
										and i.tipo_dpto_id=j.tipo_dpto_id
										and i.tipo_pais_id=k.tipo_pais_id 
										and i.tipo_dpto_id=k.tipo_dpto_id 
										and i.tipo_mpio_id=k.tipo_mpio_id
										and d.departamento_actual=h.departamento";
				}
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				$var=$result->GetRowAssoc($ToUpper = false);
				$result->Close();
				return $var;
	}


  function BuscarHa()
  {
        list($dbconn) = GetDBconn();
        $query = "select a.fecha_ingreso, a.fecha_egreso, a.cama
                  from movimientos_habitacion as a
                  where a.numerodecuenta=$cuenta";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        while(!$result->EOF)
        {
                $var[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
        }
        $result->Close();
        return $var;
  }

  function CargosFacturaHoja($cuenta,$noFacturado,$paquete)
  {
        if($paquete==1)
	{
        	if($noFacturado=='1')
		{
            		$filFacturado=" AND a.sw_paquete_facturado='0'";
          	}
		else
		{
            		$filFacturado=" AND a.sw_paquete_facturado='1'";
          	}
          	$filPaquete=" AND a.paquete_codigo_id IS NOT NULL";  
        }
	else
	{
		if($noFacturado=='1')
		{
			$filFacturado=" and a.facturado='0'";
		}
		else
		{
			$filFacturado=" and a.facturado='1'";
		}
          	$filPaquete=" AND a.paquete_codigo_id IS NULL";  
        }  
        list($dbconn) = GetDBconn();
        $querys = "select a.*, b.grupo_tipo_cargo, b.descripcion as desccargo,
			c.descripcion,ter.nombre_tercero,y.cargo as cargoliquidacion,
                    	y.descripcion as descargoliquidacion,usu.usuario,za.descripcion as via
                    	
		   from tarifarios_detalle as b,cuentas_detalle as a 
			
			left join cuentas_codigos_agrupamiento as c on(a.codigo_agrupamiento_id=c.codigo_agrupamiento_id)
			left join cuentas_cargos_qx_procedimientos as x on(c.cuenta_liquidacion_qx_id=x.cuenta_liquidacion_qx_id AND a.transaccion=x.transaccion)
			left join tarifarios_detalle as y on(y.tarifario_id=x.tarifario_id AND y.cargo=x.cargo)										
			left join cuentas_liquidaciones_qx as z on(x.cuenta_liquidacion_qx_id=z.cuenta_liquidacion_qx_id)                   
			left join qx_vias_acceso as za on(z.via_acceso=za.via_acceso)                   
                    
			left join cuentas_detalle_profesionales as prof on(a.transaccion=prof.transaccion)
			left join terceros as ter on(prof.tipo_tercero_id=ter.tipo_id_tercero AND prof.tercero_id=ter.tercero_id)
			left join system_usuarios as usu on(usu.usuario_id=a.usuario_id)
                   
		   where a.numerodecuenta=$cuenta and a.cargo=b.cargo and a.tarifario_id=b.tarifario_id
			and a.consecutivo is null and a.cargo!='DCTOREDON' and a.cargo!='APROVREDON'
			$filFacturado $filPaquete
		   
		   order by a.codigo_agrupamiento_id,x.consecutivo_procedimiento,b.descripcion asc";
										
        $result = $dbconn->Execute($querys);
        if ($dbconn->ErrorNo() != 0)
	{
        	$this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        while(!$result->EOF)
        {
                $var[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
        }
        return $var;
  }
  
  function ProfesionalFacturaHoja($transaccion)
  {
  	//jab--Query para cargar nombre del profesional
	list($dbconn) = GetDBconn();
	$querys = "select p.nombre 
	from os_maestro_cargos as omc, os_maestro as om, os_cruce_citas as occ, agenda_citas_asignadas as acs,
	agenda_citas as ac,agenda_turnos as at, profesionales as p

	where omc.transaccion=$transaccion and omc.numero_orden_id=om.numero_orden_id
	and om.numero_orden_id=occ.numero_orden_id 
	and occ.agenda_cita_asignada_id=acs.agenda_cita_asignada_id
	and acs.agenda_cita_id=ac.agenda_cita_id and ac.agenda_turno_id=at.agenda_turno_id
	and at.tipo_id_profesional=p.tipo_id_tercero and at.profesional_id=p.tercero_id";
										
        $result = $dbconn->Execute($querys);
        if ($dbconn->ErrorNo() != 0)
	{
        	$this->error = "Error al Cargar el nombre del profesional!.";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        /*while(!$result->EOF)
        {*/
                $var[0]=$result->fields[0];
                //$result->MoveNext();
        //}
        return $var;
  }
  
	function Generarvouchercargos_CRV($datos)
	{
		unset($_SESSION['NOMBRE']['MEDICO']);
      		$_SESSION['REPORTES']['VARIABLE']='vouchercargos';
		$dat=DatosPrincipales($datos[numerodecuenta]);
		$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO']=$dat;
      	//IncludeLib("tarifario");
		IncludeLib("funciones_admision");
		IncludeLib("funciones_facturacion");
		$Dir="cache/vouchercargos_CRV".$datos[numerodecuenta].".pdf";
		require_once("classes/fpdf/html_class.php");
		include_once("classes/fpdf/conversor.php");
		
		define('FPDF_FONTPATH','font/');
		//$pdf2=new PDF('P','mm','vouchercargos');
		$pdf2=new PDF(); //jab
		$pdf2->AddPage();
		$pdf2->SetFont('Arial','',6);
		//$usu=NombreUsuario();
		$html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
		$var=CargosFacturaHoja($datos[numerodecuenta]);
		
		//$var2=ProfesionalFacturaHoja($datos[numerodecuenta]); //jab
		//unset($_SESSION['NOMBRE']['MEDICO']);
		//$_SESSION['NOMBRE']['MEDICO']=$var2[0];
		
		$total=$descuentos=$pagado=0;
		$direc='';
		$totalcar=$totalins=0;
		
		for($i=0; $i<sizeof($var);)
		{
			if(!empty($var[$i][codigo_agrupamiento_id]) AND empty($var[$i][consecutivo]))
			{
				$sub=0;
				//$html.="<tr><td width=60></td><td width=700><B>".$var[$i][descripcion]."</B></td></tr>";
				$x=$i;
				while($var[$i][codigo_agrupamiento_id]==$var[$x][codigo_agrupamiento_id])
				{
					$d=$x;
/*					if($var[$d][cargoliquidacion]){
					if($var[$d][cargoliquidacion]!=$var[$d-1][cargoliquidacion]){
					$html.="<tr><td width=80></b>PROCEDIMIENTO:</td><td width=400></b>".$var[$d][cargoliquidacion]." - ".$var[$d][descargoliquidacion]."</td></tr>";
					$html.="<tr><td width=80></b>VIA ACCESO:</td><td width=400></b>".$var[$d][via]."</td></tr>";
					}
					}	*/
					$cant=$valor=0;
					while($var[$x][cargo]==$var[$d][cargo] AND $var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
					{
						$var2=ProfesionalFacturaHoja($var[$i][transaccion]); //jab
					
						if(!empty($var2[0])) //jab
						{
							//$html.="<tr><td width=760>&nbsp;</td></tr>";
							$html.="<tr><td width=80></b>PROFESIONAL :</td><td width=400></b>".$var2[0]."</td></tr>"; //jab
						}
						
						$html.="<tr><td width=60>".FechaStamp($var[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$d][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$d][departamento]."</td><td width=180>".substr($var[$x][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$d][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$d][usuario]."</td></tr>";                                
						//Esta parte se desarrollo para la impresion de las cadenas que son muy largas
						//y no se alcanzan a mostrar                                 
/*						if(strlen($var[$x][desccargo])>37){                                
						$totalCadenas=(int)(strlen($var[$x][desccargo])/37);
						$totalCadenasRes=(strlen($var[$x][desccargo])%37);                      
						$inicioCad=37;                      
						if(($totalCadenas)>1){                                  
						$cont=0;
						$inicioCad=37;                                                                    
						while($cont<($totalCadenas-1)){                                    
						$html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($var[$x][desccargo],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";                                  
						$cont++;
						$inicioCad+=37;                                    
						}                                  
						}
						if($totalCadenasRes>0){                                                                  
						$html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($var[$x][desccargo],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";                                  
						}                                
						}*/
						//fin
						/*if(!empty($var[$d][nombre_tercero])) jab--nombre profesional query anterior
						{
							$html.="<tr><td width=80></b>PROFESIONAL.:</td><td width=400></b>".$var[$d][nombre_tercero]."</td></tr>";
						}*/
						/*if(!empty($var2[$d][nombre]))//jab--nombre profesional
						{
							$html.="<tr><td width=80></b>PROFESIONAL :</td><td width=400></b>".$var2[$d][nombre]."</td></tr>";
						}*/
						$sub+=$var[$d][valor_cargo];
						$valor+=$var[$d][valor_cargo];
						$cant+=$var[$d][cantidad];
						$d++;
						$html.="<tr><td width=760>&nbsp;</td></tr>";
						$html.="<tr><td width=300>-----------FIRMA PACIENTE-----------</td><td width=220><B>  TOTAL ---------------------------------------------------------------</B></td><td width=60 align=\"RIGHT\"><B>".FormatoValor($sub)."</B></td></tr><BR>";
						for($n=0; $n<15; $n++) //jab--for para pasar a una hoja nueva.
						{
						$html .="<tr><td width=300>&nbsp;</td></tr><BR>";
						}
					}
					$x=$d;
/*					if(empty($var[$i][cargoliquidacion])){
					$html.="<tr><td width=420><B>  TOTAL-------------------------------------------------------------------------------------------------------------------</B></td><td width=40 align=\"CENTER\"><B>".FormatoValor($cant)."</B></td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
					}*/
				}
				$totalcar+=$sub;
				$i=$x;
			}
			elseif(empty($var[$i][codigo_agrupamiento_id]))
			{
				for($j=0; $j<(int)$var[$i][cantidad]; $j++)
				{
					$var2=ProfesionalFacturaHoja($var[$i][transaccion]); //jab
					
					if(!empty($var2[0])) //jab
					{
						//$html.="<tr><td width=760>&nbsp;</td></tr>";
						$html.="<tr><td width=80></b>PROFESIONAL :</td><td width=400></b>".$var2[0]."</td></tr>"; //jab
					}
					
					$valor_cargo = $var[$i][valor_cargo]/$var[$i][cantidad];
					$valor_cubierto = $var[$i][valor_cubierto]/$var[$i][cantidad];
					$html.="<tr><td width=60>".FechaStamp($var[$i][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$i][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$i][departamento]."</td><td width=180>".substr($var[$i][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor(($j+1)/($j+1))."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($valor_cargo)."</td><td width=60 align=\"CENTER\">".FormatoValor($valor_cubierto)."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$i][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$i][usuario]."</td></tr>";
					//Esta parte se desarrollo para la impresion de las cadenas que son muy largas
					//y no se alcanzan a mostrar 
/*					if(strlen($var[$i][desccargo])>37){
					$totalCadenas=(int)(strlen($var[$i][desccargo])/37);
					$totalCadenasRes=(strlen($var[$i][desccargo])%37);  
					$inicioCad=37;                      
					if(($totalCadenas)>1){
					$cont=0;
					$inicioCad=37;                                                                    
					while($cont<($totalCadenas-1)){                                    
					$direc.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($var[$i][desccargo],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";                                       
					$cont++;
					$inicioCad+=37;                                    
					}
					$inicioCad=$inicioCad;                                                    
					}
					if($totalCadenasRes>0){
					$direc.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($var[$i][desccargo],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";                                       
					}
					}*/
					//fin
					
					//$totalcar+=$var[$i][valor_cargo];
					$html.="<tr><td width=760>&nbsp;</td></tr>";
					$html.="<tr><td width=300>-----------FIRMA PACIENTE-----------</td><td width=220><B>  TOTAL ---------------------------------------------------------------</B></td><td width=60 align=\"RIGHT\"><B>".FormatoValor($var[$i][valor_cargo])."</B></td></tr><BR>";
					for($n=0; $n<15; $n++) //jab--for para pasar a una hoja nueva.
					{
					$html .="<tr><td width=300>&nbsp;</td></tr><BR>";
					}
				}
				$i++;
			}
			else
			{
				$i++;
			}
		}
		$pdf2->WriteHTML($html);
		$pdf2->Output($Dir,'F');
		return true;
	}
?>
