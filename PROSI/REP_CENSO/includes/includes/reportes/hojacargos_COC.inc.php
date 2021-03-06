<?php

/**
** $Id: $
**/

 /*
  function InsumosCuentaC($cuenta,$noFacturado,$paquete)
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
            $filFacturado=" AND a.facturado='0'";
          }else{
            $filFacturado=" AND a.facturado='1'";
          }
          $filPaquete=" AND a.paquete_codigo_id IS NULL";  
        }
				
        list($dbconn) = GetDBconn();
        $querys = "select f.descripcion as desagru,
									a.*, e.descripcion, c.codigo_producto, g.descripcion as bodega,
									g.empresa_id, g.centro_utilidad, g.bodega as idbodega,
                  (CASE 
                    WHEN u.abreviatura IS NOT NULL THEN u.abreviatura 
                    ELSE u.unidad_id  
                  END) as unidad_venta
									from cuentas_detalle as a,
									bodegas_documentos_d as c, inventarios_productos as e
                  JOIN unidades u ON (e.unidad_id=u.unidad_id),
									bodegas_doc_numeraciones as b, bodegas as g,
									cuentas_codigos_agrupamiento as f
									where a.numerodecuenta=$cuenta
									$filFacturado
									and a.cargo <> 'DIMD'
									and a.consecutivo=c.consecutivo
									and a.codigo_agrupamiento_id=f.codigo_agrupamiento_id
									and c.codigo_producto=e.codigo_producto
									and c.bodegas_doc_id=b.bodegas_doc_id
									and g.bodega=b.bodega                  
                  $filPaquete									
                  order by g.empresa_id,g.centro_utilidad,g.bodega,a.departamento,e.descripcion, c.codigo_producto";
        
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
*/

  function InsumosCuentaC($cuenta,$noFacturado,$paquete)
  {     
        if($paquete==1){
          if($noFacturado=='1'){
            $filFacturado=" AND a.sw_paquete_facturado = '0'";
          }else{
            $filFacturado=" AND a.sw_paquete_facturado = '1'";
          }
          $filPaquete=" AND a.paquete_codigo_id IS NOT NULL";            
        }else{
          if($noFacturado=='1'){
            $filFacturado=" AND a.facturado = '0'";
          }else{
            $filFacturado=" AND a.facturado = '1'";
          }
          $filPaquete=" AND a.paquete_codigo_id IS NULL";  
        }
				
				list($dbconn) = GetDBconn();
				$querys = "SELECT f.descripcion as desagru,a.*,
					(CASE WHEN p_act.cod_principio_activo <> '000000' THEN
						(CASE WHEN p_act.descripcion <> '000000' THEN 
							(p_act.descripcion||' '||
							(CASE WHEN u.abreviatura IS NOT NULL THEN
							u.abreviatura 
							ELSE u.unidad_id END)||' '||         
							e.contenido_unidad_venta)
								
							ELSE             
							e.descripcion
							END
						)
					ELSE e.descripcion 
					END) as descripcion,
					c.codigo_producto, g.descripcion as bodega,
					g.empresa_id, g.centro_utilidad, g.bodega as idbodega,
					(CASE 
						WHEN u.abreviatura IS NOT NULL THEN u.abreviatura 
						ELSE u.unidad_id  
					END) as unidad_venta
				FROM cuentas_detalle as a,
						bodegas_documentos_d as c, inventarios_productos as e
				JOIN unidades u ON (e.unidad_id = u.unidad_id)
				LEFT JOIN medicamentos med ON(e.codigo_producto = med.codigo_medicamento)        
				LEFT JOIN inv_med_cod_principios_activos p_act ON(p_act.cod_principio_activo = med.cod_principio_activo),
						bodegas_doc_numeraciones as b, bodegas as g,
						cuentas_codigos_agrupamiento as f, departamentos dep         
			
				WHERE a.numerodecuenta = $cuenta
				$filFacturado
				AND a.cargo <> 'DIMD'
				AND a.consecutivo = c.consecutivo
				AND a.departamento_al_cargar = dep.departamento
				AND a.consecutivo = c.consecutivo
				AND c.codigo_producto = e.codigo_producto                
				AND a.codigo_agrupamiento_id = f.codigo_agrupamiento_id
				AND c.codigo_producto = e.codigo_producto
				AND c.bodegas_doc_id = b.bodegas_doc_id
				AND g.bodega = b.bodega                  
				$filPaquete									
				--AND f.cuenta_liquidacion_qx_id IS NULL
				ORDER BY g.empresa_id,g.centro_utilidad,g.bodega,a.departamento,e.descripcion, c.codigo_producto";

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
  
  function GetDatosIyM($Cod_producto)
  {
      list($dbconn) = GetDBconn();
      $querys = "SELECT I.*
                FROM  inventarios_productos AS I
                WHERE I.codigo_producto = '$Cod_producto';";
      
      $result = $dbconn->Execute($querys);
      if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
      }
      if(!$result->EOF)
          $var=$result->GetRowAssoc($ToUpper = false);
      return $var;
    }
  
  function GetDatosBodega($EmpresaId,$CentroUtilidad,$BodegaId)
  {
      list($dbconn) = GetDBconn();
      $querys = "SELECT B.*
                FROM  bodegas AS B
                WHERE B.empresa_id = '$EmpresaId'
                AND B.centro_utilidad = '$CentroUtilidad' 
                AND B.bodega = '$BodegaId';";
      
      $result = $dbconn->Execute($querys);
      if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
      }
      if(!$result->EOF)
          $var=$result->GetRowAssoc($ToUpper = false);
      return $var;
    }
    
  function DevolucionesProducto($cuenta,$noFacturado,$paquete)
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
				unset($var);
        list($dbconn) = GetDBconn();
        $querys = " SELECT sum(a.cantidad), sum(a.valor_cargo), c.codigo_producto, 
										g.empresa_id,g.centro_utilidad,g.bodega, a.departamento, sum(a.valor_nocubierto), sum(a.valor_cubierto) 
										FROM  cuentas_detalle as a, bodegas_documentos_d as c,
										bodegas_doc_numeraciones as b, bodegas as g
										WHERE a.numerodecuenta=$cuenta and a.cargo='DIMD' 
										and a.consecutivo=c.consecutivo 
										and c.bodegas_doc_id=b.bodegas_doc_id
										and g.bodega=b.bodega
										$filFacturado                    
                    $filPaquete                    
										GROUP BY g.empresa_id,g.centro_utilidad,g.bodega,a.departamento,c.codigo_producto";
        $result = $dbconn->Execute($querys);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        if(!$result->EOF)
        {
						while(!$result->EOF)
						{
                $var[$result->fields[3].'||//'.$result->fields[4].'||//'.$result->fields[5].'||//'.$result->fields[6].'||//'.$result->fields[2]]=array('cantidad'=>$result->fields[0],'valor'=>$result->fields[1],'cubierto'=>$result->fields[8],'nocubierto'=>$result->fields[7]);
                $result->MoveNext();						
						}             
						$result->Close();
        }
        return $var;
  }	

  
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
                    and a.cargo!='DCTOREDON'
										and a.cargo!='APROVREDON'
										$filFacturado                    
                    $filPaquete                   
                    order by a.codigo_agrupamiento_id,x.consecutivo_procedimiento,b.descripcion asc";
										
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
	
	function Devoluciones($cuenta)
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

  function GenerarHojaCargos_COC($datos)
  {
      $_SESSION['REPORTES']['VARIABLE']='hoja_cargos';
			$dat=DatosPrincipales($datos[numerodecuenta]);
			$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO']=$dat;
      //IncludeLib("tarifario");
			IncludeLib("funciones_admision");
			IncludeLib("funciones_facturacion");
			$Dir="cache/hojacargos_COC".$datos[numerodecuenta].".pdf";
			//if($datos[cuentas]!='noinstanciar' OR $datos[cuentas]==NULL)
			//{
			require_once("classes/fpdf/html_class.php");
			include_once("classes/fpdf/conversor.php");
			//}
			define('FPDF_FONTPATH','font/');
			$pdf2=new PDF();
			$pdf2->AddPage();
			$pdf2->SetFont('Arial','',6);
			//$usu=NombreUsuario();
			$html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
			$var=CargosFacturaHoja($datos[numerodecuenta]);
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
										while($var[$x][cargo]==$var[$d][cargo]
													AND $var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
										{
																$html.="<tr><td width=60>".FechaStamp($var[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$d][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$d][departamento]."</td><td width=180>".substr($var[$x][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$d][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$d][usuario]."</td></tr>";                                
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
										}
										$x=$d;
                    if(empty($var[$i][cargoliquidacion])){
										  $html.="<tr><td width=420><B>  TOTAL-------------------------------------------------------------------------------------------------------------------</B></td><td width=40 align=\"CENTER\"><B>".FormatoValor($cant)."</B></td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
                    }
								}
								$html.="<tr><td width=520><B>  TOTAL ".$var[$i][descripcion]."----------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
								$totalcar+=$sub;
								$i=$x;
						}
						elseif(empty($var[$i][codigo_agrupamiento_id]))
						{
								$direc.="<tr><td width=60>".FechaStamp($var[$i][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$i][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$i][departamento]."</td><td width=180>".substr($var[$i][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$i][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$i][usuario]."</td></tr>";
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
			
			//------------forma un vector con devoluciones
			$medDevo = DevolucionesProducto($datos[numerodecuenta]);			                  
      $ins=InsumosCuentaC($datos[numerodecuenta]);
      $totalins=0;
      if(!empty($ins))
      {
           $sub=0;
          $html.="<tr><td width=700><b>".$ins[0][desagru]."</b></td></tr>";
          for($i=0; $i<sizeof($ins);)
          {
              $d=$i;
              $cant=$valor=0;   
              $totalBodega=0;                   
              while($ins[$i][bodega]==$ins[$d][bodega])
              {   
									$k=$d;
									$valor=$totalBodega=0;
									while($ins[$k][departamento]==$ins[$d][departamento] && $ins[$k][bodega]==$ins[$d][bodega])
									{
											$h=$k;
											$cant=$valor=$precio=$valCub=0;
											while($ins[$k][codigo_producto]==$ins[$h][codigo_producto])
											{
													$valpac=$ins[$h][valor_nocubierto];
													$total+=$ins[$h][total_costo];
													$cant+=$ins[$h][cantidad];
													$valor+=$ins[$h][valor_cargo];
													//$totalins+=$ins[$h][valor_cargo];
													//$sub+=$ins[$h][valor_cargo];
													$precio+=$ins[$h][precio];	
													$valCub+=$ins[$h][valor_cubierto];
                          $precioUnitario=$ins[$h][precio];		
													$h++;
											} 
											$valor = $valor + $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['valor'];
											$valpac = $valpac + $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['nocubierto'];
											$valCub = $valCub + $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['cubierto'];
											$cant = $cant - $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['cantidad'];
                      UNSET($medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]);
											$sub+=$valor;
											$totalins+=$valor;
											$totalBodega+=$valor;											
											$html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>".$ins[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$k][departamento]."</td><td width=180>".substr($ins[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=LEFT>".$cant." ".$ins[$k][unidad_venta]."</td><td width=60 align=\"CENTER\">".FormatoValor($precioUnitario)."</td><td width=60 align=\"CENTER\">".FormatoValor($valor)."</td><td width=60 align=\"CENTER\">".FormatoValor($valCub)."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";
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
                          $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";                          
                          $cont++;
                          $inicioCad+=37;                                    
                        }
                        $inicioCad=$inicioCad;                                                    
                      }
                      if($totalCadenasRes>0){
                        $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";                                                  
                      }
                      }
                      //fin
											$k=$h;																		
									}
									$d=$k;
									$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($totalBodega)."</B></td></tr>";
									/*							
                  $valpac=$ins[$d][valor_cuota_paciente]+$ins[$d][valor_cuota_moderadora]+$ins[$d][valor_nocubierto];
                  $html.="<tr><td width=60>".FechaStamp($ins[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$ins[$d][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$d][departamento]."</td><td width=180>".substr($ins[$d][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($ins[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=60 align=\"CENTER\">".$ins[$d][transaccion]."</td></tr>";
                  $total+=$ins[$d][total_costo];
                  $cant+=$ins[$d][cantidad];
                  $valor+=$ins[$d][valor_cargo];
                  $totalins+=$ins[$d][valor_cargo];
                   $sub+=$ins[$d][valor_cargo];
                  $d++;*/
              }
              //$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
              $i=$d;
          }
          //MEDICAMENTOS DEVUELTOS POR DEPARTAMENTOS QUE NO LOS HAN PEDIDO
          if(sizeof($medDevo)>0)
          {
            $totalBodega=0;                     
            foreach($medDevo AS $i => $v)
            { //0->empresa
              //1->centro utilidad
              //2->bodega
              //3->departamento
              //4->codigo_producto
              $datmedDevo = explode('||//',$i);
              $datos_producto = GetDatosIyM($datmedDevo[4]);
              $datos_bodega = GetDatosBodega($datmedDevo[0],$datmedDevo[1],$datmedDevo[2]);
              $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>".$datmedDevo[4]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$datmedDevo[3]."</td><td width=180>".$datos_producto[descripcion]."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=LEFT>".$v[cantidad]."</td><td width=60 align=\"CENTER\">".FormatoValor($v[valor])."</td><td width=60 align=\"CENTER\">".FormatoValor($v[valor])."</td><td width=60 align=\"CENTER\">".FormatoValor($v[cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($v[nocubierto])."</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";
              $html.="<tr><td width=80>&nbsp;</td><td width=440><B>  $datos_bodega[descripcion] ----------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($v[valor])."</B></td></tr>";
              $sub+=$v[valor];
              $totalins+=$v[valor];
              //$totalBodega+=$v[valor];                     
            }
          }
          //FIN MEDICAMENTOS DEVUELTOS POR DEPARTAMENTOS QUE NO LOS HAN PEDIDO
          
          $html.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
      }
      $html.=$ha;
      $html.=$direc;
      $html.="<tr><td width=520><B>TOTAL DE CARGOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($totalcar)."</B></td></tr>";
      $html.="<tr><td width=520><B>TOTAL DE MEDICAMENTOS E INSUMOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($totalins)."</B></td></tr>";

      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
			
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
      
        $var=CargosFacturaHoja($datos[numerodecuenta],0,1);
        $medDevo = DevolucionesProducto($datos[numerodecuenta],0,1);                        
        $ins=InsumosCuentaC($datos[numerodecuenta],0,1);
        
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
                      while($var[$x][cargo]==$var[$d][cargo]
                            AND $var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
                      {
                                  $html.="<tr><td width=60>".FechaStamp($var[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$d][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$d][departamento]."</td><td width=180>".substr($var[$x][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$d][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$d][usuario]."</td></tr>";                                
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
                      }
                      $x=$d;
                      $html.="<tr><td width=420><B>  TOTAL-------------------------------------------------------------------------------------------------------------------</B></td><td width=40 align=\"CENTER\"><B>".FormatoValor($cant)."</B></td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
                  }
                  $html.="<tr><td width=520><B>  TOTAL ".$var[$i][descripcion]."----------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
                  $totalcar+=$sub;
                  $i=$x;
              }
              elseif(empty($var[$i][codigo_agrupamiento_id]))
              {
                  $direc.="<tr><td width=60>".FechaStamp($var[$i][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$i][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$i][departamento]."</td><td width=180>".substr($var[$i][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$i][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$i][usuario]."</td></tr>";
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
                
        
        if(!empty($ins))
        {
            $sub=0;
            $html.="<tr><td width=700><b>".$ins[0][desagru]."</b></td></tr>";
            for($i=0; $i<sizeof($ins);)
            {
                $d=$i;
                $cant=$valor=0;   
                $totalBodega=0;                   
                while($ins[$i][bodega]==$ins[$d][bodega])
                {   
                    $k=$d;
                    $valor=0;
                    while($ins[$k][departamento]==$ins[$d][departamento] && $ins[$k][bodega]==$ins[$d][bodega])
                    {
                        $h=$k;
                        $cant=$valor=$precio=$valCub=0;
                        while($ins[$k][codigo_producto]==$ins[$h][codigo_producto])
                        {
                            $valpac=$ins[$h][valor_nocubierto];
                            $total+=$ins[$h][total_costo];
                            $cant+=$ins[$h][cantidad];
                            $valor+=$ins[$h][valor_cargo];
                            //$totalins+=$ins[$h][valor_cargo];
                            //$sub+=$ins[$h][valor_cargo];
                            $precio+=$ins[$h][precio];  
                            $valCub+=$ins[$h][valor_cubierto];
                            $precioUnitario=$ins[$h][precio];   
                            $h++;
                        }                        
                        $valor = $valor + $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['valor'];
                        $valpac = $valpac + $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['nocubierto'];
                        $valCub = $valCub + $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['cubierto'];
                        $cant = $cant - $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['cantidad'];
                        $sub+=$valor;
                        $totalins+=$valor;
                        $totalBodega+=$valor;                     
                        $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>".$ins[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$k][departamento]."</td><td width=180>".substr($ins[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=LEFT>".FormatoValor($cant)." ".$ins[$k][unidad_venta]."</td><td width=60 align=\"CENTER\">".FormatoValor($precioUnitario)."</td><td width=60 align=\"CENTER\">".FormatoValor($valor)."</td><td width=60 align=\"CENTER\">".FormatoValor($valCub)."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";
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
                            $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";                          
                            $cont++;
                            $inicioCad+=37;                                    
                          }
                          $inicioCad=$inicioCad;                                                    
                        }
                        if($totalCadenasRes>0){
                          $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";                                                  
                        }
                        }
                        //fin
                        $k=$h;                                    
                    }
                    $d=$k;
                    $html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($totalBodega)."</B></td></tr>";
                    /*              
                    $valpac=$ins[$d][valor_cuota_paciente]+$ins[$d][valor_cuota_moderadora]+$ins[$d][valor_nocubierto];
                    $html.="<tr><td width=60>".FechaStamp($ins[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$ins[$d][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$d][departamento]."</td><td width=180>".substr($ins[$d][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($ins[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=60 align=\"CENTER\">".$ins[$d][transaccion]."</td></tr>";
                    $total+=$ins[$d][total_costo];
                    $cant+=$ins[$d][cantidad];
                    $valor+=$ins[$d][valor_cargo];
                    $totalins+=$ins[$d][valor_cargo];
                    $sub+=$ins[$d][valor_cargo];
                    $d++;*/
                }
                //$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
                $i=$d;
            }
            $html.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
        }
        if(!empty($var) || !empty($ins)){
          $html.=$ha;
          $html.=$direc;          
          $html.="<tr><td width=520><B>TOTAL DE CARGOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($totalcar)."</B></td></tr>";
          $html.="<tr><td width=520><B>TOTAL DE MEDICAMENTOS E INSUMOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($totalins)."</B></td></tr>";
        } 
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
          $html.="<tr><td width=80 align=\"CENTER\">INGCAJA</td><td width=100 align=\"CENTER\">FECHA</td><td width=140 align=\"CENTER\">CAJERA</td><td width=70 align=\"CENTER\">EFECTIVO</td><td width=70 align=\"CENTER\">CHEQUES</td><td width=70 align=\"CENTER\">TARJETAS</td><td width=70 align=\"CENTER\">BONOS</td><td width=40 align=\"CENTER\">RET FTE</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"CENTER\">TOTAL PAGADO</td></tr>";
          for($i=0; $i<sizeof($caja); $i++)
          {
              $html.="<tr><td width=80 align=\"CENTER\">".$caja[$i][prefijo]."".$caja[$i][factura_fiscal]."</td><td width=100 align=\"CENTER\">".$caja[$i][fecha_registro]."</td><td width=140 align=\"CENTER\">".$caja[$i][nombre]."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_efectivo])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_cheques])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_tarjetas])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_bonos])."</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"RIGHT\">".FormatoValor($caja[$i][total_abono])."</td></tr>";
							$abono+=$caja[$i][total_abono];
          }
			}
			$dev = Devoluciones($datos[numerodecuenta]);	
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
			$html.="<tr><td width=520>VALOR NO CUBIERTO: </td><td width=240 align=\"RIGHT\">".FormatoValor($dat[valor_nocubierto])."</td></tr>";
			$html.="<tr><td width=520>VALOR COPAGO: </td><td width=240 align=\"RIGHT\">".FormatoValor($dat[valor_cuota_paciente])."</td></tr>";
			$html.="<tr><td width=520>VALOR CUOTA MODERADORA: </td><td width=240 align=\"RIGHT\">".FormatoValor($dat[valor_cuota_moderadora])."</td></tr>";
			$saldo=SaldoCuentaPaciente($datos[numerodecuenta]);
			$html.="<tr><td width=520>SALDO PACIENTE: </td><td width=240 align=\"RIGHT\">".FormatoValor($saldo)."</td></tr>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      //$pdf2->Output($Dir,'F');
			//********************************************
			//CARGOS, INSUMOS Y MEDICAMENTOS NO FACTURADOS
			//********************************************
			$var=CargosFacturaHoja($datos[numerodecuenta],$noFacturado='1');
			if(!empty($var))
			{
				$total=$descuentos=$pagado=0;
				$direc='';
				$totalcar=$totalins=0;
				UNSET($_SESSION['REPORTES']['VARIABLE']);
				$html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
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
							while($var[$x][cargo]==$var[$d][cargo] AND $var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id]){
								$html.="<tr><td width=60>".FechaStamp($var[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$d][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$d][departamento]."</td><td width=180>".substr($var[$x][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=40 align=\"CENTER\">".$var[$d][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$d][usuario]."</td></tr>";
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
							}
							$x=$d;
							$html.="<tr><td width=420><B>  TOTAL-------------------------------------------------------------------------------------------------------------------</B></td><td width=40 align=\"CENTER\"><B>".FormatoValor($cant)."</B></td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\"><B>0</B></td></tr>";
						}
						//$html.="<tr><td width=520><B>  TOTAL ".$var[$i][descripcion]."----------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
						$totalcar+=$sub;
						$i=$x;
					}elseif(empty($var[$i][codigo_agrupamiento_id])){
						$direc.="<tr><td width=60>".FechaStamp($var[$i][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$i][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$i][departamento]."</td><td width=180>".substr($var[$i][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=40 align=\"CENTER\">".$var[$i][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$i][usuario]."</td></tr>";
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
			$medDevo = DevolucionesProducto($datos[numerodecuenta],$noFacturado='1');	
      $ins=InsumosCuentaC($datos[numerodecuenta],1);
      $totalins=0;
      if(!empty($ins)){
				$sub=0;
				$html.="<tr><td width=700><b>".$ins[0][desagru]."</b></td></tr>";
				for($i=0; $i<sizeof($ins);){
					$d=$i;
					$cant=$valor=0;
					while($ins[$i][bodega]==$ins[$d][bodega]){
						$k=$d;
						$valor=0;
						while($ins[$k][departamento]==$ins[$d][departamento]){
							$h=$k;
							$cant=$valor=$precio=$valCub=0;
							while($ins[$k][codigo_producto]==$ins[$h][codigo_producto]){
								$valpac=$ins[$h][valor_nocubierto];
								$total+=$ins[$h][total_costo];
								$cant+=$ins[$h][cantidad];
								$valor+=$ins[$h][valor_cargo];
								//$totalins+=$ins[$h][valor_cargo];
								//$sub+=$ins[$h][valor_cargo];
								$precio+=$ins[$h][precio];	
								$valCub+=$ins[$h][valor_cubierto];		
								$h++;
							}
							$valor = $valor + $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['valor'];
							$valpac = $valpac + $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['nocubierto'];
							$valCub = $valCub + $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['cubierto'];
							$cant = $cant - $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['cantidad'];
							$sub+=$valor;
							$totalins+=$valor;
																		
							$html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>".$ins[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$k][departamento]."</td><td width=180>".substr($ins[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($cant)." ".$ins[$k][unidad_venta]."</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";
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
                  $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";                  
                  $cont++;
                  $inicioCad+=37;                                    
                }
                $inicioCad=$inicioCad;                                                    
              }
              if($totalCadenasRes>0){
                $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";                  
              }
              }
              //fin
							$k=$h;																		
						}
						$d=$k;
						$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>0</B></td></tr>";
						
          }
					$i=$d;
        }
        //$html.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>0</B></td></tr>";
      }
      
      //PAQUETES  estos son los cargos de paquetes
      
      $var=CargosFacturaHoja($datos[numerodecuenta],1,1);
      $medDevo = DevolucionesProducto($datos[numerodecuenta],1,1);                        
      $ins=InsumosCuentaC($datos[numerodecuenta],1,1);
      
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
                    while($var[$x][cargo]==$var[$d][cargo]
                          AND $var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
                    {
                                $html.="<tr><td width=60>".FechaStamp($var[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$d][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$d][departamento]."</td><td width=180>".substr($var[$x][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$d][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$d][usuario]."</td></tr>";                                
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
                    }
                    $x=$d;
                    $html.="<tr><td width=420><B>  TOTAL-------------------------------------------------------------------------------------------------------------------</B></td><td width=40 align=\"CENTER\"><B>".FormatoValor($cant)."</B></td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
                }
                $html.="<tr><td width=520><B>  TOTAL ".$var[$i][descripcion]."----------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
                $totalcar+=$sub;
                $i=$x;
            }
            elseif(empty($var[$i][codigo_agrupamiento_id]))
            {
                $direc.="<tr><td width=60>".FechaStamp($var[$i][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$i][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$i][departamento]."</td><td width=180>".substr($var[$i][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$i][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$i][usuario]."</td></tr>";
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
              
      
      if(!empty($ins))
      {
          $sub=0;
          $html.="<tr><td width=700><b>".$ins[0][desagru]."</b></td></tr>";
          for($i=0; $i<sizeof($ins);)
          {
              $d=$i;
              $cant=$valor=0;   
              $totalBodega=0;                   
              while($ins[$i][bodega]==$ins[$d][bodega])
              {   
                  $k=$d;
                  $valor=0;
                  while($ins[$k][departamento]==$ins[$d][departamento] && $ins[$k][bodega]==$ins[$d][bodega])
                  {
                      $h=$k;
                      $cant=$valor=$precio=$valCub=0;
                      while($ins[$k][codigo_producto]==$ins[$h][codigo_producto])
                      {
                          $valpac=$ins[$h][valor_nocubierto];
                          $total+=$ins[$h][total_costo];
                          $cant+=$ins[$h][cantidad];
                          $valor+=$ins[$h][valor_cargo];
                          //$totalins+=$ins[$h][valor_cargo];
                          //$sub+=$ins[$h][valor_cargo];
                          $precio+=$ins[$h][precio];  
                          $valCub+=$ins[$h][valor_cubierto];
                          $precioUnitario=$ins[$h][precio];   
                          $h++;
                      }                        
                      $valor = $valor + $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['valor'];
                      $valpac = $valpac + $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['nocubierto'];
                      $valCub = $valCub + $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['cubierto'];
                      $cant = $cant - $medDevo[$ins[$k][empresa_id].'||//'.$ins[$k][centro_utilidad].'||//'.$ins[$k][idbodega].'||//'.$ins[$k][departamento].'||//'.$ins[$k][codigo_producto]]['cantidad'];
                      $sub+=$valor;
                      $totalins+=$valor;
                      $totalBodega+=$valor;                     
                      $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>".$ins[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$k][departamento]."</td><td width=180>".substr($ins[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=LEFT>".FormatoValor($cant)." ".$ins[$k][unidad_venta]."</td><td width=60 align=\"CENTER\">".FormatoValor($precioUnitario)."</td><td width=60 align=\"CENTER\">".FormatoValor($valor)."</td><td width=60 align=\"CENTER\">".FormatoValor($valCub)."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";
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
                          $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";                          
                          $cont++;
                          $inicioCad+=37;                                    
                        }
                        $inicioCad=$inicioCad;                                                    
                      }
                      if($totalCadenasRes>0){
                        $html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=180>".substr($ins[$k][descripcion],$inicioCad,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";                                                  
                      }
                      }
                      //fin
                      $k=$h;                                    
                  }
                  $d=$k;
                  $html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($totalBodega)."</B></td></tr>";
                  /*              
                  $valpac=$ins[$d][valor_cuota_paciente]+$ins[$d][valor_cuota_moderadora]+$ins[$d][valor_nocubierto];
                  $html.="<tr><td width=60>".FechaStamp($ins[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$ins[$d][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$d][departamento]."</td><td width=180>".substr($ins[$d][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($ins[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=60 align=\"CENTER\">".$ins[$d][transaccion]."</td></tr>";
                  $total+=$ins[$d][total_costo];
                  $cant+=$ins[$d][cantidad];
                  $valor+=$ins[$d][valor_cargo];
                  $totalins+=$ins[$d][valor_cargo];
                  $sub+=$ins[$d][valor_cargo];
                  $d++;*/
              }
              //$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
              $i=$d;
          }
          $html.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
      }
      
      if(!empty($var) || !empty($ins)){
        $html.=$ha;
        $html.=$direc;          
        $html.="<tr><td width=520><B>TOTAL DE CARGOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($totalcar)."</B></td></tr>";
        $html.="<tr><td width=520><B>TOTAL DE MEDICAMENTOS E INSUMOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($totalins)."</B></td></tr>";
      } 
      if(!empty($var) || !empty($ins)){
        $html.="<tr><td width=760>-----------------------------------------------------------------------------------------------     FIN PAQUETES      ------------------------------------------------------------------------------------------------------------</td></tr>";
        $html.="<tr><td width=760><BR></td></tr>";
      }
      
      
      //FIN PAQUETES
      
      	
			if(!empty($var) || !empty($medDevo) || !empty($ins)){
				$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
				$html.="<tr><td width=760>CUALQUIER DUDA AL RESPECTO DE ESTE DOCUMENTO, POR FAVOR COMUNIQUELO A COORDINACION GENERAL</td></tr>";
				$html.="</table>";
				$pdf2->WriteHTML($html);
				$pdf2->Output($Dir,'F');
			}else{
				$html.="<tr><td width=760>CUALQUIER DUDA AL RESPECTO DE ESTE DOCUMENTO, POR FAVOR COMUNIQUELO A COORDINACION GENERAL</td></tr>";
				$html.="</table>";
				$pdf2->WriteHTML($html);
				$pdf2->Output($Dir,'F');
			}
			//--------------------------------------------
      return true;
 }
?>
