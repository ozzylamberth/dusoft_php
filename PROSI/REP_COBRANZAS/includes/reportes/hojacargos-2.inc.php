<?php

/**
 * $Id: hojacargos.inc.php,v 1.21 2006/01/20 14:23:34 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

  function InsumosCuentaC($cuenta,$noFacturado)
  {
				if($noFacturado=='1'){
					$filFacturado=" and a.facturado='0'";
				}else{
					$filFacturado=" and a.facturado='1'";
				}
        list($dbconn) = GetDBconn();
	      $querys = "select f.descripcion as desagru,
									a.*, e.descripcion, c.codigo_producto, g.descripcion as bodega,
									g.empresa_id, g.centro_utilidad, g.bodega as idbodega
									from cuentas_detalle as a,
									bodegas_documentos_d as c, inventarios_productos as e,
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
									order by a.departamento,e.descripcion, c.codigo_producto";
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


  function DevolucionesProducto($cuenta)
  {			
				if($noFacturado=='1'){
					$filFacturado=" and a.facturado='0'";
				}else{
					$filFacturado=" and a.facturado='1'";
				}
				unset($var);
        list($dbconn) = GetDBconn();
	      $querys = " SELECT sum(a.cantidad), sum(a.valor_cargo), c.codigo_producto, 
										g.bodega, sum(a.valor_nocubierto), sum(a.valor_cubierto) 
										FROM  cuentas_detalle as a, bodegas_documentos_d as c,
										bodegas_doc_numeraciones as b, bodegas as g
										WHERE a.numerodecuenta=$cuenta and a.cargo='DIMD' 
										and a.consecutivo=c.consecutivo 
										and c.bodegas_doc_id=b.bodegas_doc_id
										and g.bodega=b.bodega
										$filFacturado
										GROUP BY g.bodega,c.codigo_producto";
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
                $var[$result->fields[3].$result->fields[2]]=array('cantidad'=>$result->fields[0],'valor'=>$result->fields[1],'cubierto'=>$result->fields[5],'nocubierto'=>$result->fields[4]);
                $result->MoveNext();						
						}             
						$result->Close();
        }
        return $var;
  }	

	
	function DatosPrincipales($cuenta)
	{
        list($dbconn) = GetDBconn();
        $query = "select (a.valor_total_paciente -(a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos)) as saldo,
                  a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
                  c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
                  e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
                  e.residencia_telefono, e.residencia_direccion, d.departamento_actual as dpto, h.descripcion,
                  i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid, i.id, j.departamento,
									k.municipio, d.fecha_registro, a.valor_total_empresa, a.total_cuenta
                  from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
                  empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d
                  where a.numerodecuenta=$cuenta and a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
                  and b.tipo_tercero_id=c.tipo_id_tercero
                  and d.ingreso=a.ingreso and d.tipo_id_paciente=e.tipo_id_paciente
                  and d.paciente_id=e.paciente_id
                  and a.empresa_id=i.empresa_id and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                  and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
                  and d.departamento_actual=h.departamento";

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

  function CargosFacturaHoja($cuenta,$noFacturado)
  {
				if($noFacturado=='1'){
					$filFacturado=" and a.facturado='0'";
				}else{
					$filFacturado=" and a.facturado='1'";
				}
        list($dbconn) = GetDBconn();
        $querys = "select a.*, b.grupo_tipo_cargo, b.descripcion as desccargo,
										c.descripcion,ter.nombre_tercero
                    from tarifarios_detalle as b,
										cuentas_detalle as a 
										left join cuentas_codigos_agrupamiento as c on(a.codigo_agrupamiento_id=c.codigo_agrupamiento_id)
										left join cuentas_detalle_profesionales as prof on(a.transaccion=prof.transaccion)
										left join terceros as ter on(prof.tipo_tercero_id=ter.tipo_id_tercero AND prof.tercero_id=ter.tercero_id)
                    where a.numerodecuenta=$cuenta and a.cargo=b.cargo
										and a.tarifario_id=b.tarifario_id
										and a.consecutivo is null
                    and a.cargo!='DIMD' and a.cargo!='DCTOREDON'
										and a.cargo!='APROVREDON'
										$filFacturado
                    order by a.codigo_agrupamiento_id,b.descripcion asc";
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

    /**
    *
    */
    /*function DetalleCuentaNoFacturados($Cuenta)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT  a.transaccion,
																	a.cargo,
																	a.cantidad,
																	a.precio,
																	a.valor_nocubierto,
																	a.fecha_registro,
																	a.tarifario_id,
																	a.valor_cubierto,
																	a.valor_cargo,
																	a.porcentaje_descuento_paciente,
																	a.porcentaje_descuento_empresa,
																	a.valor_descuento_empresa,
																	a.valor_descuento_paciente,
																	case facturado when 1 then a.valor_cargo else 0 end as fac,
																	a.autorizacion_int as interna,
																	a.autorizacion_ext as externa,
																	a.codigo_agrupamiento_id,
																	a.consecutivo,
																	b.descripcion
													FROM cuentas_detalle a,
																tarifarios_detalle b
													WHERE a.numerodecuenta='$Cuenta' 
													AND a.facturado=0
													AND a.tarifario_id=b.tarifario_id
													AND a.cargo=b.cargo
													ORDER BY a.codigo_agrupamiento_id";
      $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
      while(!$result->EOF)
      {
          $arre[]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
      }
      $result->Close();
            return $arre;
    }*/

  function GenerarHojaCargos($datos)
  {
      $_SESSION['REPORTES']['VARIABLE']='hoja_cargos';
			$dat=DatosPrincipales($datos[numerodecuenta]);
			$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO']=$dat;
      //IncludeLib("tarifario");
			IncludeLib("funciones_admision");
			IncludeLib("funciones_facturacion");
			$Dir="cache/hojacargos".$datos[numerodecuenta].".pdf";
			require("classes/fpdf/html_class.php");
			include("classes/fpdf/conversor.php");
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
										$cant=$valor=0;
										while($var[$x][cargo]==$var[$d][cargo]
													AND $var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
										{
																$html.="<tr><td width=60>".FechaStamp($var[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$d][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$d][departamento]."</td><td width=180>".substr($var[$x][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=60 align=\"CENTER\">".$var[$d][transaccion]."</td></tr>";
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
								$direc.="<tr><td width=60>".FechaStamp($var[$i][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$i][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$i][departamento]."</td><td width=180>".substr($var[$i][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=60 align=\"CENTER\">".$var[$i][transaccion]."</td></tr>";
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
              while($ins[$i][bodega]==$ins[$d][bodega])
              {
									$k=$d;
									$valor=0;
									while($ins[$k][departamento]==$ins[$d][departamento])
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
													$h++;
											}
											$valor = $valor + $medDevo[$ins[$k][idbodega].$ins[$k][codigo_producto]]['valor'];
											$valpac = $valpac + $medDevo[$ins[$k][idbodega].$ins[$k][codigo_producto]]['nocubierto'];
											$valCub = $valCub + $medDevo[$ins[$k][idbodega].$ins[$k][codigo_producto]]['cubierto'];
											$cant = $cant - $medDevo[$ins[$k][idbodega].$ins[$k][codigo_producto]]['cantidad'];
											$sub+=$valor;
											$totalins+=$valor;
																						
											$html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>".$ins[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$k][departamento]."</td><td width=180>".substr($ins[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($cant)."</td><td width=60 align=\"CENTER\">".FormatoValor($precio)."</td><td width=60 align=\"CENTER\">".FormatoValor($valor)."</td><td width=60 align=\"CENTER\">".FormatoValor($valCub)."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";
											$k=$h;																		
									}
									$d=$k;
									$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
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
			$saldo=SaldoCuentaPaciente($datos[numerodecuenta]);
			$html.="<tr><td width=520>SALDO PACIENTE: </td><td width=240 align=\"RIGHT\">".FormatoValor($saldo)."</td></tr>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      //$pdf2->Output($Dir,'F');
			//********************************************
			//CARGOS, INSUMOS Y MEDICAMENTOS NO FACTURADOS
			//********************************************
			$var=CargosFacturaHoja($datos[numerodecuenta],$noFacturado='1');
			$medDevo = DevolucionesProducto($datos[numerodecuenta],$noFacturado='1');	
      $ins=InsumosCuentaC($datos[numerodecuenta],$noFacturado='1');
			if(!empty($var) || !empty($medDevo) || !empty($ins)){
				UNSET($_SESSION['REPORTES']['VARIABLE']);	
				$html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
				$html.="<tr><td width=700><b>CARGOS NO FACTURADOS</b></td></tr>";
				$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
			}	
			if(!empty($var))
			{
				$total=$descuentos=$pagado=0;
				$direc='';
				$totalcar=$totalins=0;							
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
								$html.="<tr><td width=60>".FechaStamp($var[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$d][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$d][departamento]."</td><td width=180>".substr($var[$x][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">".$var[$d][transaccion]."</td></tr>";
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
						$direc.="<tr><td width=60>".FechaStamp($var[$i][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$i][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$i][departamento]."</td><td width=180>".substr($var[$i][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">".$var[$i][transaccion]."</td></tr>";
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
							$valor = $valor + $medDevo[$ins[$k][idbodega].$ins[$k][codigo_producto]]['valor'];
							$valpac = $valpac + $medDevo[$ins[$k][idbodega].$ins[$k][codigo_producto]]['nocubierto'];
							$valCub = $valCub + $medDevo[$ins[$k][idbodega].$ins[$k][codigo_producto]]['cubierto'];
							$cant = $cant - $medDevo[$ins[$k][idbodega].$ins[$k][codigo_producto]]['cantidad'];
							$sub+=$valor;
							$totalins+=$valor;
																		
							$html.="<tr><td width=60>&nbsp;</td><td width=60 align='CENTER'>".$ins[$k][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$k][departamento]."</td><td width=180>".substr($ins[$k][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($cant)."</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">0</td><td width=60 align=\"CENTER\">&nbsp;</td></tr>";
							$k=$h;																		
						}
						$d=$k;
						$html.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>0</B></td></tr>";
						
          }
					$i=$d;
        }
        //$html.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>0</B></td></tr>";
      }	
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
