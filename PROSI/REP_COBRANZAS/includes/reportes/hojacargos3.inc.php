<?php

/**
 * $Id: hojacargos3.inc.php,v 1.15 2007/06/06 16:36:31 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

  function InsumosCuentaC3($cuenta,$noFacturado,$paquete)
  {
        if($paquete==1){
          if($noFacturado=='1'){
            $filFacturado=" AND a.sw_paquete_facturado='0'";
          }else{
            $filFacturado=" AND a.sw_paquete_facturado='1'";
          }
          $filPaquete=" AND a.paquete_codigo_id IS NOT NULL";  
        }else{
				  if($noFacturado=='1'){
					 $filFacturado=" and a.facturado='0'";
				  }else{
					 $filFacturado=" and a.facturado='1'";
				  }
          $filPaquete=" AND a.paquete_codigo_id IS NULL";  
        }  
        list($dbconn) = GetDBconn();
	      $querys = "SELECT f.descripcion as desagru,
									a.*, 									
									(CASE WHEN p_act.cod_principio_activo <> '000000' THEN
                    (CASE WHEN p_act.descripcion <> '000000' THEN 
                      p_act.descripcion
                     ELSE 
                      e.descripcion
                     END
                    )
                   ELSE e.descripcion 
                   END) as descripcion, 
									
									c.codigo_producto, g.descripcion as bodega,usu.usuario,
                  (CASE 
                    WHEN u.abreviatura IS NOT NULL THEN u.abreviatura 
                    ELSE u.unidad_id  
                  END) as unidad_venta
                  
									FROM cuentas_detalle as a
									left join system_usuarios as usu on(usu.usuario_id=a.usuario_id),
									bodegas_documentos_d as c, inventarios_productos as e
                  JOIN unidades u ON (e.unidad_id=u.unidad_id)
									LEFT JOIN medicamentos med ON(e.codigo_producto=med.codigo_medicamento)
									LEFT JOIN inv_med_cod_principios_activos p_act ON(p_act.cod_principio_activo=med.cod_principio_activo),
									bodegas_doc_numeraciones as b, bodegas as g,
									cuentas_codigos_agrupamiento as f
									WHERE a.numerodecuenta=$cuenta
									$filFacturado
									and a.cargo <>'DIMD'
									and a.consecutivo=c.consecutivo
									and a.codigo_agrupamiento_id=f.codigo_agrupamiento_id
									and c.codigo_producto=e.codigo_producto
									and c.bodegas_doc_id=b.bodegas_doc_id
									and g.bodega=b.bodega                  
                  $filPaquete                  
									order by f.codigo_agrupamiento_id,e.descripcion, c.codigo_producto";
							
        $result = $dbconn->Execute($querys);
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
        return $var;
  }

  function DevolucionesInsumosCuentaC3($cuenta,$noFacturado,$paquete)
  {
        if($paquete==1){
          if($noFacturado=='1'){
            $filFacturado=" AND a.sw_paquete_facturado='0'";
          }else{
            $filFacturado=" AND a.sw_paquete_facturado='1'";
          }
          $filPaquete=" AND a.paquete_codigo_id IS NOT NULL";  
        }else{
          if($noFacturado=='1'){
					 $filFacturado=" and a.facturado='0'";
				  }else{
					 $filFacturado=" and a.facturado='1'";
				  }
          $filPaquete=" AND a.paquete_codigo_id IS NULL";  
        }  	
				list($dbconn) = GetDBconn();
        
	      $querys = "SELECT f.descripcion as desagru,
									a.*,                   
                  (CASE WHEN med.codigo_medicamento IS NOT NULL THEN 
                    (CASE WHEN p_act.cod_principio_activo <> '000000' THEN 
                      p_act.descripcion
                     ELSE 
                      e.descripcion
                     END
                    )
                   ELSE e.descripcion 
                   END) as descripcion, 
									
									c.codigo_producto, g.descripcion as bodega,usu.usuario,
                  (CASE 
                    WHEN u.abreviatura IS NOT NULL THEN u.abreviatura 
                    ELSE u.unidad_id  
                  END) as unidad_venta
									FROM cuentas_detalle as a
									left join system_usuarios as usu on(usu.usuario_id=a.usuario_id),
									bodegas_documentos_d as c, inventarios_productos as e
                  JOIN unidades u ON (e.unidad_id=u.unidad_id)
									LEFT JOIN medicamentos med ON(e.codigo_producto=med.codigo_medicamento)
									LEFT JOIN inv_med_cod_principios_activos p_act ON(p_act.cod_principio_activo=med.cod_principio_activo)
									,bodegas_doc_numeraciones as b, bodegas as g,
									cuentas_codigos_agrupamiento as f
									WHERE a.numerodecuenta=$cuenta
									$filFacturado									
									and a.tarifario_id='SYS'
									and a.cargo='DIMD'
									and a.consecutivo=c.consecutivo
									and a.codigo_agrupamiento_id=f.codigo_agrupamiento_id
									and c.codigo_producto=e.codigo_producto
									and c.bodegas_doc_id=b.bodegas_doc_id
									and g.bodega=b.bodega                  
                  $filPaquete                  
									order by f.codigo_agrupamiento_id,e.descripcion, c.codigo_producto";
				
        $result = $dbconn->Execute($querys);
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
        return $var;
  }	
	
	function DatosPrincipales3($cuenta)
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
									k.municipio, d.fecha_registro, a.valor_total_empresa, a.total_cuenta
                  from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
                  empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d,fac_facturas_cuentas l
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
										k.municipio, d.fecha_registro, a.valor_total_empresa, a.total_cuenta
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


  function BuscarHa3()
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

  function CargosFacturaHoja3($cuenta,$noFacturado,$paquete)
  {
        if($paquete==1){
          if($noFacturado=='1'){
            $filFacturado=" AND a.sw_paquete_facturado='0'";
          }else{
            $filFacturado=" AND a.sw_paquete_facturado='1'";
          }
          $filPaquete=" AND a.paquete_codigo_id IS NOT NULL";  
        }else{
				  if($noFacturado=='1'){
					 $filFacturado=" and a.facturado='0'";
				  }else{
					 $filFacturado=" and a.facturado='1'";
				  }
          $filPaquete=" AND a.paquete_codigo_id IS NULL";  
        }  
        list($dbconn) = GetDBconn();
        $querys = "select a.*, b.grupo_tipo_cargo, b.descripcion as desccargo,
										c.descripcion,ter.nombre_tercero,y.cargo as cargoliquidacion,
                    y.descripcion as descargoliquidacion,usu.usuario,za.descripcion as via
                    from tarifarios_detalle as b,
										cuentas_detalle as a 
										left join cuentas_codigos_agrupamiento as c on(a.codigo_agrupamiento_id=c.codigo_agrupamiento_id)
										
										left join cuentas_cargos_qx_procedimientos as x on(c.cuenta_liquidacion_qx_id=x.cuenta_liquidacion_qx_id AND a.transaccion=x.transaccion)
										left join tarifarios_detalle as y on(y.tarifario_id=x.tarifario_id AND y.cargo=x.cargo)
										left join cuentas_liquidaciones_qx as z on(x.cuenta_liquidacion_qx_id=z.cuenta_liquidacion_qx_id)                   
                    left join qx_vias_acceso as za on(z.via_acceso=za.via_acceso)                   
                    
										left join cuentas_detalle_profesionales as prof on(a.transaccion=prof.transaccion)
										left join terceros as ter on(prof.tipo_tercero_id=ter.tipo_id_tercero AND prof.tercero_id=ter.tercero_id)
										left join system_usuarios as usu on(usu.usuario_id=a.usuario_id)

                    where a.numerodecuenta=$cuenta and a.cargo=b.cargo
										and a.tarifario_id=b.tarifario_id
										and a.consecutivo is null
                    and a.cargo!='DIMD' and a.cargo!='DCTOREDON'
										and a.cargo!='APROVREDON'
										$filFacturado                    
                    $filPaquete                    
                    order by a.codigo_agrupamiento_id,c.cuenta_liquidacion_qx_id,a.transaccion,b.descripcion asc";
							
        $result = $dbconn->Execute($querys);
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
        return $var;
  }
	
	function Devoluciones3($cuenta)
	{
        list($dbconn) = GetDBconn();	
				$query = "SELECT a.prefijo, a.recibo_caja, a.fecha_registro, b.nombre, a.total_devolucion
									FROM rc_devoluciones as a, system_usuarios as b
									WHERE a.numerodecuenta=$cuenta and a.usuario_id=b.usuario_id";
						
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
				if(!$result->EOF)
				{
						while(!$result->EOF)
						{
										$var[]=$result->GetRowAssoc($ToUpper = false);
										$total+=$result->fields[4];
										$result->MoveNext();
						}
						$var[0]['devoluciones']=$total;
				}
        return $var;									
	}

  function GenerarHojaCargos3($datos)
  {
      $_SESSION['REPORTES']['VARIABLE']='hoja_cargos';
			$dat=DatosPrincipales3($datos[numerodecuenta]);
      $_SESSION['REPORTES']['HOJACARGOS']['ARREGLO']=$dat;
      //IncludeLib("tarifario");
			IncludeLib("funciones_admision");
			IncludeLib("funciones_facturacion");
      $Dir="cache/hojacargos3".$datos[numerodecuenta].".pdf";
			require_once("classes/fpdf/html_class.php");
			include_once("classes/fpdf/conversor.php");
      define('FPDF_FONTPATH','font/');
      $pdf2d=new PDF();
      $pdf2d->AddPage();
      $pdf2d->SetFont('Arial','',6);
      //$usu=NombreUsuario();
      $html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
      $var=CargosFacturaHoja3($datos[numerodecuenta]);
      $total=$descuentos=$pagado=0;
      $direc='';
      $totalcar=$totalins=0;
      for($i=0; $i<sizeof($var);)
      {
						if(!empty($var[$i][codigo_agrupamiento_id]) AND empty($var[$i][consecutivo]))
						{		
								
								$sub=0;
								$html.="<tr><td width=60></td><td width=700><B>".$var[$i][descripcion]."</B></td></tr>";
								$x=$i;														
								
								while($var[$i][codigo_agrupamiento_id]==$var[$x][codigo_agrupamiento_id])
								{
										$d=$x;
										if($var[$d][cargoliquidacion]){
											if($var[$d][cargoliquidacion]!=$var[$d-1][cargoliquidacion]){
												$html.="<tr><td width=80></b>PROCEDIMIENTO:</td><td width=400></b>".$var[$d][cargoliquidacion]." - ".$var[$d][descargoliquidacion]."</td></tr>";
                        $html.="<tr><td width=80></b>VIA ACCESO:</td><td width=400></b>".$var[$d][via]."</td></tr>";
											}
										}										
										
										$cant=$valor=0;
										//while($var[$x][cargo]==$var[$d][cargo]
									//				AND $var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
										//{
																$valpac=$var[$d][valor_nocubierto];
																$html.="<tr><td width=60>".FechaStamp($var[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$d][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$d][departamento_al_cargar]."</td><td width=180>".substr($var[$x][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$d][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$d][usuario]."</td></tr>";
                                //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
                                //y no se alcanzan a mostrar
                                if(strlen($var[$x][desccargo])>37){                                                              
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
                                  $inicioCad=$inicioCad;                                                    
                                }
                                if($totalCadenasRes>0){
                                  $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($var[$x][desccargo],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                                }
                                }
                                //fin
																if(!empty($var[$d][nombre_tercero])){
																	$html.="<tr><td width=80></b>PROFESIONAL:</td><td width=400></b>".$var[$d][nombre_tercero]."</td></tr>";
																}
																$sub+=$var[$d][valor_cargo];
																$valor+=$var[$d][valor_cargo];
																$cant+=$var[$d][cantidad];
																$d++;
										//}
										$x=$d;
										//$html.="<tr><td width=420><B>  TOTAL-------------------------------------------------------------------------------------------------------------------</B></td><td width=40 align=\"CENTER\"><B>".FormatoValor($cant)."</B></td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
								}
								$html.="<tr><td width=520><B>  TOTAL ".$var[$i][descripcion]."----------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
								$totalcar+=$sub;
								$i=$x;
						}
						elseif(empty($var[$i][codigo_agrupamiento_id]))
						{
								$valpac=$var[$i][valor_nocubierto];
								$direc.="<tr><td width=60>".FechaStamp($var[$i][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$i][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$i][departamento_al_cargar]."</td><td width=180>".substr($var[$i][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$i][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$i][usuario]."</td></tr>";
                //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
                //y no se alcanzan a mostrar 
                if(strlen($var[$i][desccargo])>37){                                                             
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
                }
                //fin
								if(!empty($var[$i][nombre_tercero])){
									$direc.="<tr><td width=80></b>PROFESIONAL:</td><td width=400></b>".$var[$i][nombre_tercero]."</td></tr>";
								}
								$totalcar+=$var[$i][valor_cargo];
								$i++;
						}
						else
						{ $i++; }
      }
      //$html.=$direc;
			//-------------------	MEDICAMENTOS-----------------------------			
      $ins=InsumosCuentaC3($datos[numerodecuenta]);
      $totalins=0;
      if(!empty($ins))
      {
           $sub=0;
          $html.="<tr><td width=700><b>".$ins[0][desagru]."</b></td></tr>";
          for($i=0; $i<sizeof($ins);)
          {
          
              $d=$i;
              $cant=$valor=0;
              while($ins[$i][bodega]==$ins[$d][bodega])
              {
									$k=$d;
									$valor=0;
									while($ins[$k][codigo_agrupamiento_id]==$ins[$d][codigo_agrupamiento_id])
									{   
											$valpac=$ins[$k][valor_nocubierto];
											$html.="<tr><td width=60>".FechaStamp($ins[$k][fecha_cargo])."</td><td width=60 align='CENTER'>".$ins[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$k][departamento_al_cargar]."</td><td width=180>".substr($ins[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".$ins[$k][cantidad]." ".$ins[$k][unidad_venta]."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$ins[$k][transaccion]."</td><td width=40 align=\"CENTER\">".$ins[$k][usuario]."</td></tr>";
                      //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
                      //y no se alcanzan a mostrar   
                      if(strlen($ins[$k][descripcion])>37){                                                                             
                      $totalCadenas=(int)(strlen($ins[$k][descripcion])/37);
                      $totalCadenasRes=(strlen($ins[$k][descripcion])%37);  
                      $inicioCad=37;                      
                      if(($totalCadenas)>1){
                        $cont=0;
                        $inicioCad=37;                                                                    
                        while($cont<($totalCadenas-1)){                                    
                          $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                          $cont++;
                          $inicioCad+=37;                                    
                        }
                        $inicioCad=$inicioCad;                                                    
                      }
                      if($totalCadenasRes>0){
                        $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                      }
                      }
                      //fin
											$total+=$ins[$k][total_costo];
											$cant+=$ins[$k][cantidad];
											$valor+=$ins[$k][valor_cargo];
											$totalins+=$ins[$k][valor_cargo];
											$sub+=$ins[$k][valor_cargo];
											$k++;
									}
									$d=$k;
									$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
			
              }
              //$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
							//$totalins+=$valor;
              $i=$d;
          }
          $html.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
					$medicamentos=$sub;
      }
			//--------------DEVOLUCIONES MEDICAMENTOS----------------------
      $ins=DevolucionesInsumosCuentaC3($datos[numerodecuenta]);
      $totalins=$devoluciones=0;
      if(!empty($ins))
      {
           $sub=0;
          $html.="<tr><td width=700><b>".$ins[0][desagru]."</b></td></tr>";
          for($i=0; $i<sizeof($ins);)
          {
              $d=$i;
              $cant=$valor=0;
              while($ins[$i][bodega]==$ins[$d][bodega])
              {
									$k=$d;
									$valor=0;
									while($ins[$k][codigo_agrupamiento_id]==$ins[$d][codigo_agrupamiento_id])
									{
											$valpac=$ins[$k][valor_cuota_paciente]+$ins[$k][valor_cuota_moderadora]+$ins[$d][valor_nocubierto];
											$html.="<tr><td width=60>".FechaStamp($ins[$k][fecha_cargo])."</td><td width=60 align='CENTER'>".$ins[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$k][departamento_al_cargar]."</td><td width=180>".substr($ins[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".$ins[$k][cantidad]." ".$ins[$k][unidad_venta]."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$ins[$k][transaccion]."</td><td width=40 align=\"CENTER\">".$ins[$k][usuario]."</td></tr>";
                      //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
                      //y no se alcanzan a mostrar 
                      if(strlen($ins[$k][descripcion])>37){                                                             
                      $totalCadenas=(int)(strlen($ins[$k][descripcion])/37);
                      $totalCadenasRes=(strlen($ins[$k][descripcion])%37);  
                      $inicioCad=37;                      
                      if(($totalCadenas)>1){
                        $cont=0;
                        $inicioCad=37;                                                                    
                        while($cont<($totalCadenas-1)){                                    
                          $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                          $cont++;
                          $inicioCad+=37;                                    
                        }
                        $inicioCad=$inicioCad;                                                    
                      }
                      if($totalCadenasRes>0){
                        $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                      }
                      }
                      //fin
											$total+=$ins[$k][total_costo];
											$cant+=$ins[$k][cantidad];
											$valor+=$ins[$k][valor_cargo];
											$totalins+=$ins[$k][valor_cargo];
											$sub+=$ins[$k][valor_cargo];
											$k++;
									}
									$d=$k;
									$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
			
              }
              //$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
							//$totalins+=$valor;
              $i=$d;
          }
          $html.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
					$devoluciones=$sub;
      }
			if(!empty($medicamentos))
			{
          $html.="<tr><td width=520><B>  TOTAL MEDICAMENTOS E INSUMOS ---------------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($medicamentos+$devoluciones)."</B></td></tr>";
			}
			//--------------------HABITACIONES-----------------------------
      $html.=$ha;
      $html.=$direc;
      $html.="<tr><td width=520><B>TOTAL DE CARGOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($totalcar)."</B></td></tr>";
      $html.="<tr><td width=520><B>TOTAL DE MEDICAMENTOS E INSUMOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($medicamentos+$devoluciones)."</B></td></tr>";

      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";

			//--------------------HABITACIONES-----------------------------
			//Arranque cali movimientos de habitaciones
			
			if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php")) 
			{
					die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
			}
			
			$liqHab = new LiquidacionHabitaciones;
			$hab = $liqHab->LiquidarCargosInternacion($datos[numerodecuenta],false);
						
			if(is_array($hab))
			{
				$html.="<tr><td width=700><b>HABITACIONES</b></td></tr>";
				$html.="<tr>";
				$html.="<td width=100><b>TARIF.</b></td>";
				$html.="<td width=100><b>CARGO</b></td>";
				$html.="<td width=300><b>DESCRIPCION</b></td>";
				$html.="<td width=100><b>PRECIO</b></td>";
				$html.="<td width=100><b>CANTIDAD</b></td>";
				$html.="<td width=100><b>TOTAL</b></td>";
				$html.="</tr>";	
				$total=0;
				for($i=0; $i<sizeof($hab); $i++)
				{		
						$html.="<tr>";
						$html.="<td width=100><b>".$hab[$i][tarifario_id]."</b></td>";
						$html.="<td width=100><b>".$hab[$i][cargo]."</b></td>";
						$html.="<td width=300><b>".$hab[$i][descripcion]."</b></td>";
						$html.="<td width=100><b>".FormatoValor($hab[$i][precio_plan])."</b></td>";
						$html.="<td width=100><b>".$hab[$i][cantidad]."</b></td>";
						$html.="<td width=100><b>".FormatoValor($hab[$i][valor_cargo])."</b></td>";
						$html.="</tr>";							
						$totalEstancia +=$hab[$i][valor_cargo];			
				}				
				$html.="<tr>";
				$html.="<td width=700><b>TOTAL ESTANCIA:</b></td>";
				$html.="<td width=100><b>".FormatoValor($totalEstancia)."</b></td>";				
				$html.="</tr>";								
				
				$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
				
			}
			//fin
      
      
      //PAQUETES  estos son los cargos de paquetes
      
      $var=CargosFacturaHoja3($datos[numerodecuenta],0,1);
      $ins=InsumosCuentaC3($datos[numerodecuenta],0,1);
      $total=$descuentos=$pagado=0;
      $direc='';
      //$totalcar=$totalins=0;
      if(!empty($var) || !empty($ins)){
        $totalcar=$totalins=0;
        $html.="<tr><td width=760><BR></td></tr>";
        $html.="<tr><td width=760>---------------------------------------------------------------------------------------------------      PAQUETES      ---------------------------------------------------------------------------------------------------------------</td></tr>";
      }
      for($i=0; $i<sizeof($var);)
      {
            if(!empty($var[$i][codigo_agrupamiento_id]) AND empty($var[$i][consecutivo]))
            {   
                
                $sub=0;
                $html.="<tr><td width=60></td><td width=700><B>".$var[$i][descripcion]."</B></td></tr>";
                $x=$i;                            
                
                while($var[$i][codigo_agrupamiento_id]==$var[$x][codigo_agrupamiento_id])
                {
                    $d=$x;
                    if($var[$d][cargoliquidacion]){
                      if($var[$d][cargoliquidacion]!=$var[$d-1][cargoliquidacion]){
                        $html.="<tr><td width=80></b>PROCEDIMIENTO:</td><td width=400></b>".$var[$d][cargoliquidacion]." - ".$var[$d][descargoliquidacion]."</td></tr>";
                        $html.="<tr><td width=80></b>VIA ACCESO:</td><td width=400></b>".$var[$d][via]."</td></tr>";
                      }
                    }                   
                    
                    $cant=$valor=0;
                    //while($var[$x][cargo]==$var[$d][cargo]
                  //        AND $var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
                    //{
                                $valpac=$var[$d][valor_nocubierto];
                                $html.="<tr><td width=60>".FechaStamp($var[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$d][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$d][departamento_al_cargar]."</td><td width=180>".substr($var[$x][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$d][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$d][usuario]."</td></tr>";
                                //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
                                //y no se alcanzan a mostrar
                                if(strlen($var[$x][desccargo])>37){                                                              
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
                                  $inicioCad=$inicioCad;                                                    
                                }
                                if($totalCadenasRes>0){
                                  $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($var[$x][desccargo],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                                }
                                }
                                //fin
                                if(!empty($var[$d][nombre_tercero])){
                                  $html.="<tr><td width=80></b>PROFESIONAL:</td><td width=400></b>".$var[$d][nombre_tercero]."</td></tr>";
                                }
                                $sub+=$var[$d][valor_cargo];
                                $valor+=$var[$d][valor_cargo];
                                $cant+=$var[$d][cantidad];
                                $d++;
                    //}
                    $x=$d;
                    //$html.="<tr><td width=420><B>  TOTAL-------------------------------------------------------------------------------------------------------------------</B></td><td width=40 align=\"CENTER\"><B>".FormatoValor($cant)."</B></td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
                }
                $html.="<tr><td width=520><B>  TOTAL ".$var[$i][descripcion]."----------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
                $totalcar+=$sub;
                $i=$x;
            }
            elseif(empty($var[$i][codigo_agrupamiento_id]))
            {
                $valpac=$var[$i][valor_nocubierto];
                $direc.="<tr><td width=60>".FechaStamp($var[$i][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$i][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$i][departamento_al_cargar]."</td><td width=180>".substr($var[$i][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$i][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$i][usuario]."</td></tr>";
                //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
                //y no se alcanzan a mostrar 
                if(strlen($var[$i][desccargo])>37){                                                             
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
                }
                //fin
                if(!empty($var[$i][nombre_tercero])){
                  $direc.="<tr><td width=80></b>PROFESIONAL:</td><td width=400></b>".$var[$i][nombre_tercero]."</td></tr>";
                }
                $totalcar+=$var[$i][valor_cargo];
                $i++;
            }
            else
            { $i++; }
      }
      //$html.=$direc;
      //------------------- MEDICAMENTOS-----------------------------     
      
      $totalins=0;
      if(!empty($ins))
      {
           $sub=0;
          $html.="<tr><td width=700><b>".$ins[0][desagru]."</b></td></tr>";
          for($i=0; $i<sizeof($ins);)
          {
          
              $d=$i;
              $cant=$valor=0;
              while($ins[$i][bodega]==$ins[$d][bodega])
              {
                  $k=$d;
                  $valor=0;
                  while($ins[$k][codigo_agrupamiento_id]==$ins[$d][codigo_agrupamiento_id])
                  {   
                      $valpac=$ins[$k][valor_nocubierto];
                      $html.="<tr><td width=60>".FechaStamp($ins[$k][fecha_cargo])."</td><td width=60 align='CENTER'>".$ins[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$k][departamento_al_cargar]."</td><td width=180>".substr($ins[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".$ins[$k][cantidad]." ".$ins[$k][unidad_venta]."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$ins[$k][transaccion]."</td><td width=40 align=\"CENTER\">".$ins[$k][usuario]."</td></tr>";
                      //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
                      //y no se alcanzan a mostrar   
                      if(strlen($ins[$k][descripcion])>37){                                                                             
                      $totalCadenas=(int)(strlen($ins[$k][descripcion])/37);
                      $totalCadenasRes=(strlen($ins[$k][descripcion])%37);  
                      $inicioCad=37;                      
                      if(($totalCadenas)>1){
                        $cont=0;
                        $inicioCad=37;                                                                    
                        while($cont<($totalCadenas-1)){                                    
                          $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                          $cont++;
                          $inicioCad+=37;                                    
                        }
                        $inicioCad=$inicioCad;                                                    
                      }
                      if($totalCadenasRes>0){
                        $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                      }
                      }
                      //fin
                      $total+=$ins[$k][total_costo];
                      $cant+=$ins[$k][cantidad];
                      $valor+=$ins[$k][valor_cargo];
                      $totalins+=$ins[$k][valor_cargo];
                      $sub+=$ins[$k][valor_cargo];
                      $k++;
                  }
                  $d=$k;
                  $html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
      
              }
              //$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
              //$totalins+=$valor;
              $i=$d;
          }
          $html.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
          $medicamentos=$sub;
      }
      //--------------DEVOLUCIONES MEDICAMENTOS----------------------
      $ins=DevolucionesInsumosCuentaC3($datos[numerodecuenta],0,1);
      //$totalins=$devoluciones=0;
      if(!empty($ins))
      {
           $sub=$totalins=$devoluciones=0;
           //$sub=0;
          $html.="<tr><td width=700><b>".$ins[0][desagru]."</b></td></tr>";
          for($i=0; $i<sizeof($ins);)
          {
              $d=$i;
              $cant=$valor=0;
              while($ins[$i][bodega]==$ins[$d][bodega])
              {
                  $k=$d;
                  $valor=0;
                  while($ins[$k][codigo_agrupamiento_id]==$ins[$d][codigo_agrupamiento_id])
                  {
                      $valpac=$ins[$k][valor_cuota_paciente]+$ins[$k][valor_cuota_moderadora]+$ins[$d][valor_nocubierto];
                      $html.="<tr><td width=60>".FechaStamp($ins[$k][fecha_cargo])."</td><td width=60 align='CENTER'>".$ins[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$k][departamento_al_cargar]."</td><td width=180>".substr($ins[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".$ins[$k][cantidad]." ".$ins[$k][unidad_venta]."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$ins[$k][transaccion]."</td><td width=40 align=\"CENTER\">".$ins[$k][usuario]."</td></tr>";
                      //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
                      //y no se alcanzan a mostrar 
                      if(strlen($ins[$k][descripcion])>37){                                                             
                      $totalCadenas=(int)(strlen($ins[$k][descripcion])/37);
                      $totalCadenasRes=(strlen($ins[$k][descripcion])%37);  
                      $inicioCad=37;                      
                      if(($totalCadenas)>1){
                        $cont=0;
                        $inicioCad=37;                                                                    
                        while($cont<($totalCadenas-1)){                                    
                          $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                          $cont++;
                          $inicioCad+=37;                                    
                        }
                        $inicioCad=$inicioCad;                                                    
                      }
                      if($totalCadenasRes>0){
                        $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                      }
                      }
                      //fin
                      $total+=$ins[$k][total_costo];
                      $cant+=$ins[$k][cantidad];
                      $valor+=$ins[$k][valor_cargo];
                      $totalins+=$ins[$k][valor_cargo];
                      $sub+=$ins[$k][valor_cargo];
                      $k++;
                  }
                  $d=$k;
                  $html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
      
              }
              //$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
              //$totalins+=$valor;
              $i=$d;
          }
          $html.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
          $devoluciones=$sub;
      }
      if(!empty($medicamentos))
      {
          $html.="<tr><td width=520><B>  TOTAL MEDICAMENTOS E INSUMOS ---------------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($medicamentos+$devoluciones)."</B></td></tr>";
      }
      
      $html.=$ha;
      $html.=$direc;
      $html.="<tr><td width=520><B>TOTAL DE CARGOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($totalcar)."</B></td></tr>";
      $html.="<tr><td width=520><B>SUBTOTAL DE MEDICAMENTOS E INSUMOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($medicamentos)."</B></td></tr>";
      $html.="<tr><td width=520><B>SUBTOTAL DEVOLUCIONES MEDICAMENTOS E INSUMOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($devoluciones)."</B></td></tr>";
      $html.="<tr><td width=520><B>TOTAL DE MEDICAMENTOS E INSUMOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($medicamentos+$devoluciones)."</B></td></tr>";
      if(!empty($var) || !empty($ins)){
        $html.="<tr><td width=760>-----------------------------------------------------------------------------------------------     FIN PAQUETES      ------------------------------------------------------------------------------------------------------------</td></tr>";
        $html.="<tr><td width=760><BR></td></tr>";
      }
      
      
      //FIN PAQUETES

      $caja=PagosCuenta($datos[numerodecuenta]);
			$abono=0;
      if(!empty($caja))
      {
          $html.="<tr><td width=80 align=\"CENTER\">INGCAJA</td><td width=100 align=\"CENTER\">FECHA</td><td width=140 align=\"CENTER\">CAJERA</td><td width=70 align=\"CENTER\">EFECTIVO</td><td width=70 align=\"CENTER\">CHEQUES</td><td width=70 align=\"CENTER\">TARJETAS</td><td width=70 align=\"CENTER\">BONOS</td><td width=40 align=\"CENTER\">RET FTE</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"CENTER\">TOTAL PAGADO</td></tr>";
          for($i=0; $i<sizeof($caja); $i++)
          {
              $html.="<tr><td width=80 align=\"CENTER\">".$caja[$i][prefijo]."".$caja[$i][recibo_caja]."</td><td width=100 align=\"CENTER\">".$caja[$i][fecha_ingcaja]."</td><td width=140 align=\"CENTER\">".$caja[$i][nombre]."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_efectivo])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_cheques])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_tarjetas])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_bonos])."</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"RIGHT\">".FormatoValor($caja[$i][total_abono])."</td></tr>";
							$abono+=$caja[$i][total_abono];
          }
      }
			else
			{
					$caja='';
					$caja=PagosCajaRapida($datos[numerodecuenta]);
					$abono=0;
					if(!empty($caja))
					{
							$html.="<tr><td width=80 align=\"CENTER\">INGCAJA</td><td width=100 align=\"CENTER\">FECHA</td><td width=140 align=\"CENTER\">CAJERA</td><td width=70 align=\"CENTER\">EFECTIVO</td><td width=70 align=\"CENTER\">CHEQUES</td><td width=70 align=\"CENTER\">TARJETAS</td><td width=70 align=\"CENTER\">BONOS</td><td width=40 align=\"CENTER\">RET FTE</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"CENTER\">TOTAL PAGADO</td></tr>";
							for($i=0; $i<sizeof($caja); $i++)
							{
									$html.="<tr><td width=80 align=\"CENTER\">".$caja[$i][prefijo]."".$caja[$i][factura_fiscal]."</td><td width=100 align=\"CENTER\">".$caja[$i][fecha_registro]."</td><td width=140 align=\"CENTER\">".$caja[$i][nombre]."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_efectivo])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_cheques])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_tarjetas])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_bonos])."</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"RIGHT\">".FormatoValor($caja[$i][total_abono])."</td></tr>";
									$abono+=$caja[$i][total_abono];
							}
					}
			}
			$dev = Devoluciones3($datos[numerodecuenta]);	
			if(!empty($dev))
			{						
          $html.="<tr><td width=80 align=\"CENTER\">DEVCAJA</td><td width=100 align=\"CENTER\">FECHA</td><td width=140 align=\"CENTER\">CAJERA</td><td width=320 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"CENTER\">TOTAL DEVOLUCION</td></tr>";
          for($i=0; $i<sizeof($dev); $i++)
          {
							$fec=explode('.',$dev[$i][fecha_registro]);
              $html.="<tr><td width=80 align=\"CENTER\">".$dev[$i][prefijo]."".$dev[$i][recibo_caja]."</td><td width=100 align=\"CENTER\">".$fec[0]."</td><td width=140 align=\"CENTER\">".$dev[$i][nombre]."</td><td width=320 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"RIGHT\">".FormatoValor($dev[$i][total_devolucion])."</td></tr>";
          }
			}			
			$html.="<tr><td><br></td></tr>";
      $html.="<tr><td width=520>TOTAL DE ABONOS: </td><td width=240 align=\"RIGHT\">".FormatoValor($abono)."</td></tr>";
	
			//hay devoluciones
			if(!empty($dev))
			{
				$html.="<tr><td width=520>TOTAL DEVOLUCIONES: </td><td width=240 align=\"RIGHT\">".FormatoValor($dev[0]['devoluciones'])."</td></tr>";
			
			}	
			//APROVECHAMIENTO
			$apro=BuscarCargoAjusteApro($datos[numerodecuenta]);
			if(!empty($apro))
			{
      		$html.="<tr><td width=520>APROVECHAMIENTOS POR REDONDEO: </td><td width=240 align=\"RIGHT\">".FormatoValor($apro)."</td></tr>";
			}
			//DESCUENTO
			$des=BuscarCargoAjusteDes($datos[numerodecuenta]);
			if(!empty($des))
			{
     		 	$html.="<tr><td width=520>DESCUENTO POR REDONDEO: </td><td width=240 align=\"RIGHT\">".FormatoValor($des)."</td></tr>";
			}
      $html.="<tr><td width=520>TOTAL CUENTA: </td><td width=240 align=\"RIGHT\">".FormatoValor($dat[total_cuenta]+$totalEstancia)."</td></tr>";
      $html.="<tr><td width=150>CARGO A CUENTA DE: </td><td width=370>".$dat[nombre_tercero]."</td><td width=240 align=\"RIGHT\">".FormatoValor($dat[valor_total_empresa])."</td></tr>";
			$saldo=SaldoCuentaPaciente($datos[numerodecuenta]);
			$html.="<tr><td width=520>SALDO PACIENTE: </td><td width=240 align=\"RIGHT\">".FormatoValor($saldo)."</td></tr>";
			$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
			//********************************************
			//CARGOS, INSUMOS Y MEDICAMENTOS NO FACTURADOS
			//********************************************
			
			$var=CargosFacturaHoja3($datos[numerodecuenta],1);		
			$ins=InsumosCuentaC3($datos[numerodecuenta],1);	
			$insDev=DevolucionesInsumosCuentaC3($datos[numerodecuenta],1);			
			if(!empty($var)){
				$total=$descuentos=$pagado=0;
				$direc='';
				$totalcar=$totalins=0;
				UNSET($_SESSION['REPORTES']['VARIABLE']);
				$html.="<tr><td width=700><b>CARGOS NO FACTURADOS</b></td></tr>";
				$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
				//$html.="<tr><td width=60 align='CENTER'>CARGO</td><td width=200 align=\"CENTER\">DESCRIPCION</td><td width=40>CANT.</td><td width=70 align=CENTER>PRECIO</td><td width=70 align=\"CENTER\">VALOR NO CUB.</td><td width=75 align=\"CENTER\">VALOR CUB.</td><td width=80 align=\"CENTER\">VAL. CARGO</td></tr>";
				//$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";				
				
				for($i=0; $i<sizeof($var);){
					if(!empty($var[$i][codigo_agrupamiento_id]) AND empty($var[$i][consecutivo])){
						$sub=0;
						$html.="<tr><td width=60></td><td width=700><B>".$var[$i][descripcion]."</B></td></tr>";
						$x=$i;
						while($var[$i][codigo_agrupamiento_id]==$var[$x][codigo_agrupamiento_id]){
							$d=$x;
							$cant=$valor=0;							
							$html.="<tr><td width=60>".FechaStamp($var[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$d][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$d][departamento_al_cargar]."</td><td width=180>".substr($var[$x][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=40 align=\"CENTER\">".$var[$d][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$d][usuario]."</td></tr>";
              //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
              //y no se alcanzan a mostrar  
              if(strlen($var[$x][desccargo])>37){                                                            
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
                $inicioCad=$inicioCad;                                                    
              }
              if($totalCadenasRes>0){
                $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($var[$x][desccargo],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
              }
              }
              //fin
							if(!empty($var[$d][nombre_tercero])){
								$html.="<tr><td width=80></b>PROFESIONAL:</td><td width=400></b>".$var[$d][nombre_tercero]."</td></tr>";
							}
							$sub+=$var[$d][valor_cargo];
							$valor+=$var[$d][valor_cargo];
							$cant+=$var[$d][cantidad];
							$d++;
							$x=$d;
						}
						//$html.="<tr><td width=520><B>  TOTAL ".$var[$i][descripcion]."----------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
						$totalcar+=$sub;
						$i=$x;
					}elseif(empty($var[$i][codigo_agrupamiento_id])){
						$direc.="<tr><td width=60>".FechaStamp($var[$i][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$i][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$i][departamento_al_cargar]."</td><td width=180>".substr($var[$i][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=40 align=\"CENTER\">".$var[$i][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$i][usuario]."</td></tr>";
            //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
            //y no se alcanzan a mostrar
            if(strlen($var[$i][desccargo])>37){                                                              
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
            }
            //fin
						if(!empty($var[$i][nombre_tercero])){
							$direc.="<tr><td width=80></b>PROFESIONAL:</td><td width=400></b>".$var[$i][nombre_tercero]."</td></tr>";
						}
						$totalcar+=$var[$i][valor_cargo];
						$i++;
					}else{
						$i++;
					}
				}
			}
			if($ins){	
				$totalins=0;				
				$sub=0;
				$html.="<tr><td width=700><b>".$ins[0][desagru]."</b></td></tr>";
				for($i=0; $i<sizeof($ins);){
					$d=$i;
					$cant=$valor=0;
					while($ins[$i][bodega]==$ins[$d][bodega]){
						$k=$d;
						$valor=0;
						while($ins[$k][codigo_agrupamiento_id]==$ins[$d][codigo_agrupamiento_id]){
							$valpac=$ins[$k][valor_nocubierto];
							$html.="<tr><td width=60>".FechaStamp($ins[$k][fecha_cargo])."</td><td width=60 align='CENTER'>".$ins[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$k][departamento_al_cargar]."</td><td width=180>".substr($ins[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".$ins[$k][cantidad]." ".$ins[$k][unidad_venta]."</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=40 align=\"CENTER\">".$ins[$k][transaccion]."</td><td width=40 align=\"CENTER\">".$ins[$k][usuario]."</td></tr>";
              //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
              //y no se alcanzan a mostrar   
              if(strlen($ins[$k][descripcion])>37){                                                           
              $totalCadenas=(int)(strlen($ins[$k][descripcion])/37);
              $totalCadenasRes=(strlen($ins[$k][descripcion])%37);  
              $inicioCad=37;                      
              if(($totalCadenas)>1){
                $cont=0;
                $inicioCad=37;                                                                    
                while($cont<($totalCadenas-1)){                                    
                  $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                  $cont++;
                  $inicioCad+=37;                                    
                }
                $inicioCad=$inicioCad;                                                    
              }
              if($totalCadenasRes>0){
                $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
              }
              }
              //fin
							$total+=$ins[$k][total_costo];
							$cant+=$ins[$k][cantidad];
							$valor+=$ins[$k][valor_cargo];
							$totalins+=$ins[$k][valor_cargo];
							$sub+=$ins[$k][valor_cargo];
							$k++;
						}
						$d=$k;
						$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>0</B></td></tr>";
					}								
					$i=$d;
				}
				//$html.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
				$medicamentos=$sub;
				
			}	
			if($insDev){
				$totalins=$devoluciones=0;				
				$sub=0;
				$html.="<tr><td width=700><b>".$insDev[0][desagru]."</b></td></tr>";
				for($i=0; $i<sizeof($insDev);){
					$d=$i;
					$cant=$valor=0;
					while($insDev[$i][bodega]==$insDev[$d][bodega]){
						$k=$d;
						$valor=0;
						while($insDev[$k][codigo_agrupamiento_id]==$insDev[$d][codigo_agrupamiento_id]){
							$valpac=$insDev[$k][valor_cuota_paciente]+$insDev[$k][valor_cuota_moderadora]+$insDev[$d][valor_nocubierto];
							$html.="<tr><td width=60>".FechaStamp($insDev[$k][fecha_cargo])."</td><td width=60 align='CENTER'>".$insDev[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$insDev[$k][departamento_al_cargar]."</td><td width=180>".substr($insDev[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".$insDev[$k][cantidad]." ".$insDev[$k][unidad_venta]."</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=40 align=\"CENTER\">".$insDev[$k][transaccion]."</td><td width=40 align=\"CENTER\">".$insDev[$k][usuario]."</td></tr>";
              //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
              //y no se alcanzan a mostrar  
              if(strlen($insDev[$k][descripcion])>37){                                                            
              $totalCadenas=(int)(strlen($insDev[$k][descripcion])/37);
              $totalCadenasRes=(strlen($insDev[$k][descripcion])%37);  
              $inicioCad=37;                      
              if(($totalCadenas)>1){
                $cont=0;
                $inicioCad=37;                                                                    
                while($cont<($totalCadenas-1)){                                    
                  $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($insDev[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                  $cont++;
                  $inicioCad+=37;                                    
                }
                $inicioCad=$inicioCad;                                                    
              }
              if($totalCadenasRes>0){
                $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($insDev[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
              }
              }
              //fin
							$total+=$insDev[$k][total_costo];
							$cant+=$insDev[$k][cantidad];
							$valor+=$insDev[$k][valor_cargo];
							$totalins+=$insDev[$k][valor_cargo];
							$sub+=$insDev[$k][valor_cargo];
							$k++;
						}
						$d=$k;
						$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$insDev[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>0</B></td></tr>";
					}
					$i=$d;
				}
				//$html.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
				$devoluciones=$sub;
			}	
      
      //PAQUETES  estos son los cargos de paquetes
      
      $var=CargosFacturaHoja3($datos[numerodecuenta],1,1);
      $ins=InsumosCuentaC3($datos[numerodecuenta],1,1);
      $total=$descuentos=$pagado=0;
      $direc='';
      $totalcar=$totalins=0;
      if(!empty($var) || !empty($ins)){
        $html.="<tr><td width=760><BR></td></tr>";
        $html.="<tr><td width=760>---------------------------------------------------------------------------------------------------      PAQUETES      ---------------------------------------------------------------------------------------------------------------</td></tr>";
      }
      for($i=0; $i<sizeof($var);)
      {
            if(!empty($var[$i][codigo_agrupamiento_id]) AND empty($var[$i][consecutivo]))
            {   
                
                $sub=0;
                $html.="<tr><td width=60></td><td width=700><B>".$var[$i][descripcion]."</B></td></tr>";
                $x=$i;                            
                
                while($var[$i][codigo_agrupamiento_id]==$var[$x][codigo_agrupamiento_id])
                {
                    $d=$x;
                    if($var[$d][cargoliquidacion]){
                      if($var[$d][cargoliquidacion]!=$var[$d-1][cargoliquidacion]){
                        $html.="<tr><td width=80></b>PROCEDIMIENTO:</td><td width=400></b>".$var[$d][cargoliquidacion]." - ".$var[$d][descargoliquidacion]."</td></tr>";
                        $html.="<tr><td width=80></b>VIA ACCESO:</td><td width=400></b>".$var[$d][via]."</td></tr>";
                      }
                    }                   
                    
                    $cant=$valor=0;
                    //while($var[$x][cargo]==$var[$d][cargo]
                  //        AND $var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
                    //{
                                $valpac=$var[$d][valor_nocubierto];
                                $html.="<tr><td width=60>".FechaStamp($var[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$d][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$d][departamento_al_cargar]."</td><td width=180>".substr($var[$x][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$d][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$d][usuario]."</td></tr>";
                                //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
                                //y no se alcanzan a mostrar
                                if(strlen($var[$x][desccargo])>37){                                                              
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
                                  $inicioCad=$inicioCad;                                                    
                                }
                                if($totalCadenasRes>0){
                                  $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($var[$x][desccargo],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                                }
                                }
                                //fin
                                if(!empty($var[$d][nombre_tercero])){
                                  $html.="<tr><td width=80></b>PROFESIONAL:</td><td width=400></b>".$var[$d][nombre_tercero]."</td></tr>";
                                }
                                $sub+=$var[$d][valor_cargo];
                                $valor+=$var[$d][valor_cargo];
                                $cant+=$var[$d][cantidad];
                                $d++;
                    //}
                    $x=$d;
                    //$html.="<tr><td width=420><B>  TOTAL-------------------------------------------------------------------------------------------------------------------</B></td><td width=40 align=\"CENTER\"><B>".FormatoValor($cant)."</B></td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
                }
                $html.="<tr><td width=520><B>  TOTAL ".$var[$i][descripcion]."----------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
                $totalcar+=$sub;
                $i=$x;
            }
            elseif(empty($var[$i][codigo_agrupamiento_id]))
            {
                $valpac=$var[$i][valor_nocubierto];
                $direc.="<tr><td width=60>".FechaStamp($var[$i][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$i][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$i][departamento_al_cargar]."</td><td width=180>".substr($var[$i][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$i][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$i][usuario]."</td></tr>";
                //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
                //y no se alcanzan a mostrar 
                if(strlen($var[$i][desccargo])>37){                                                             
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
                }
                //fin
                if(!empty($var[$i][nombre_tercero])){
                  $direc.="<tr><td width=80></b>PROFESIONAL:</td><td width=400></b>".$var[$i][nombre_tercero]."</td></tr>";
                }
                $totalcar+=$var[$i][valor_cargo];
                $i++;
            }
            else
            { $i++; }
      }
      //$html.=$direc;
      //------------------- MEDICAMENTOS-----------------------------     
      
      $totalins=0;
      if(!empty($ins))
      {
           $sub=0;
          $html.="<tr><td width=700><b>".$ins[0][desagru]."</b></td></tr>";
          for($i=0; $i<sizeof($ins);)
          {
          
              $d=$i;
              $cant=$valor=0;
              while($ins[$i][bodega]==$ins[$d][bodega])
              {
                  $k=$d;
                  $valor=0;
                  while($ins[$k][codigo_agrupamiento_id]==$ins[$d][codigo_agrupamiento_id])
                  {   
                      $valpac=$ins[$k][valor_nocubierto];
                      $html.="<tr><td width=60>".FechaStamp($ins[$k][fecha_cargo])."</td><td width=60 align='CENTER'>".$ins[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$k][departamento_al_cargar]."</td><td width=180>".substr($ins[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".$ins[$k][cantidad]." ".$ins[$k][unidad_venta]."</td><td width=60 align=\"CENTER\">".$ins[$k][precio]."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$ins[$k][transaccion]."</td><td width=40 align=\"CENTER\">".$ins[$k][usuario]."</td></tr>";
                      //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
                      //y no se alcanzan a mostrar   
                      if(strlen($ins[$k][descripcion])>37){                                                                             
                      $totalCadenas=(int)(strlen($ins[$k][descripcion])/37);
                      $totalCadenasRes=(strlen($ins[$k][descripcion])%37);  
                      $inicioCad=37;                      
                      if(($totalCadenas)>1){
                        $cont=0;
                        $inicioCad=37;                                                                    
                        while($cont<($totalCadenas-1)){                                    
                          $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                          $cont++;
                          $inicioCad+=37;                                    
                        }
                        $inicioCad=$inicioCad;                                                    
                      }
                      if($totalCadenasRes>0){
                        $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                      }
                      }
                      //fin
                      $total+=$ins[$k][total_costo];
                      $cant+=$ins[$k][cantidad];
                      $valor+=$ins[$k][valor_cargo];
                      $totalins+=$ins[$k][valor_cargo];
                      $sub+=$ins[$k][valor_cargo];
                      $k++;
                  }
                  $d=$k;
                  $html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
      
              }
              //$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
              //$totalins+=$valor;
              $i=$d;
          }
          $html.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
          $medicamentos=$sub;
      }
      //--------------DEVOLUCIONES MEDICAMENTOS----------------------
      $ins=DevolucionesInsumosCuentaC3($datos[numerodecuenta],1,1);
      //$totalins=$devoluciones=0;
      if(!empty($ins))
      {
          $totalins=$devoluciones=$sub=0;
          //$sub=0;
          $html.="<tr><td width=700><b>".$ins[0][desagru]."</b></td></tr>";
          for($i=0; $i<sizeof($ins);)
          {
              $d=$i;
              $cant=$valor=0;
              while($ins[$i][bodega]==$ins[$d][bodega])
              {
                  $k=$d;
                  $valor=0;
                  while($ins[$k][codigo_agrupamiento_id]==$ins[$d][codigo_agrupamiento_id])
                  {
                      $valpac=$ins[$k][valor_cuota_paciente]+$ins[$k][valor_cuota_moderadora]+$ins[$d][valor_nocubierto];
                      $html.="<tr><td width=60>".FechaStamp($ins[$k][fecha_cargo])."</td><td width=60 align='CENTER'>".$ins[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$k][departamento_al_cargar]."</td><td width=180>".substr($ins[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".$ins[$k][cantidad]." ".$ins[$k][unidad_venta]."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$k][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$ins[$k][transaccion]."</td><td width=40 align=\"CENTER\">".$ins[$k][usuario]."</td></tr>";
                      //Esta parte se desarrollo para la impresion de las cadenas que son muy largas
                      //y no se alcanzan a mostrar 
                      if(strlen($ins[$k][descripcion])>37){                                                             
                      $totalCadenas=(int)(strlen($ins[$k][descripcion])/37);
                      $totalCadenasRes=(strlen($ins[$k][descripcion])%37);  
                      $inicioCad=37;                      
                      if(($totalCadenas)>1){
                        $cont=0;
                        $inicioCad=37;                                                                    
                        while($cont<($totalCadenas-1)){                                    
                          $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                          $cont++;
                          $inicioCad+=37;                                    
                        }
                        $inicioCad=$inicioCad;                                                    
                      }
                      if($totalCadenasRes>0){
                        $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td></tr>";
                      }
                      }
                      //fin
                      $total+=$ins[$k][total_costo];
                      $cant+=$ins[$k][cantidad];
                      $valor+=$ins[$k][valor_cargo];
                      $totalins+=$ins[$k][valor_cargo];
                      $sub+=$ins[$k][valor_cargo];
                      $k++;
                  }
                  $d=$k;
                  $html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
      
              }
              //$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
              //$totalins+=$valor;
              $i=$d;
          }
          $html.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
          $devoluciones=$sub;
      }
      if(!empty($medicamentos))
      {
          $html.="<tr><td width=520><B>  TOTAL MEDICAMENTOS E INSUMOS ---------------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($medicamentos+$devoluciones)."</B></td></tr>";
      }
      
      $html.=$ha;
      $html.=$direc;
      $html.="<tr><td width=520><B>TOTAL DE CARGOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($totalcar)."</B></td></tr>";
      $html.="<tr><td width=520><B>TOTAL DE MEDICAMENTOS E INSUMOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($medicamentos+$devoluciones)."</B></td></tr>";
      if(!empty($var) || !empty($ins)){
        $html.="<tr><td width=760>-----------------------------------------------------------------------------------------------     FIN PAQUETES      ------------------------------------------------------------------------------------------------------------</td></tr>";
        $html.="<tr><td width=760><BR></td></tr>";
      }
      
      
      //FIN PAQUETES
      
			if(!empty($var) || !empty($ins) || !empty($insDev)){
				/*for($i=0; $i<sizeof($NoFacturados);$i++)
				{
					//$html.="<tr><td width=60 align='LEFT'>".$NoFacturados[$i][cargo]."</td><td width=200 align=\"LEFT\">".substr($NoFacturados[$i][descripcion],0,37)."</td><td width=40>".FormatoValor($NoFacturados[$i][cantidad])."</td><td width=70 align=CENTER>".FormatoValor($NoFacturados[$i][precio])."</td><td width=70 align=\"CENTER\">".FormatoValor($NoFacturados[$i][valor_nocubierto])."</td><td width=75 align=\"CENTER\">".FormatoValor($NoFacturados[$i][valor_cubierto])."</td><td width=80 align=\"CENTER\">".FormatoValor($NoFacturados[$i][valor_cargo])."</td></tr>";
					$html.="<tr><td width=60 align='LEFT'>".$NoFacturados[$i][cargo]."</td><td width=200 align=\"LEFT\">".substr($NoFacturados[$i][descripcion],0,37)."</td><td width=40>".FormatoValor($NoFacturados[$i][cantidad])."</td><td width=70 align=CENTER>".FormatoValor(0)."</td><td width=70 align=\"CENTER\">".FormatoValor(0)."</td><td width=75 align=\"CENTER\">".FormatoValor(0)."</td><td width=80 align=\"CENTER\">".FormatoValor(0)."</td></tr>";
				}*/
				$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
				$html.="<tr><td width=760>CUALQUIER DUDA AL RESPECTO DE ESTE DOCUMENTO, POR FAVOR COMUNIQUELO A COORDINACION GENERAL</td></tr>";
				$html.="</table>";
				$pdf2d->WriteHTML($html);
				$pdf2d->Output($Dir,'F');
			}
			else
			{
			  $html.="<tr><td width=760>CUALQUIER DUDA AL RESPECTO DE ESTE DOCUMENTO, POR FAVOR COMUNIQUELO A COORDINACION GENERAL</td></tr>";
				$html.="</table>";
				$pdf2d->WriteHTML($html);
				$pdf2d->Output($Dir,'F');
			}
			//--------------------------------------------
      return true;
 }
?>
