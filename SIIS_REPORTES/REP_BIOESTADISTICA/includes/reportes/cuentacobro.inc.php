<?php

/**
 * $Id: cuentacobro.inc.php,v 1.2 2006/07/31 20:48:21 lorena Exp $
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


  function DevolucionesProducto($cuenta,$noFacturado)
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

	function EncabezadoCuentaCobro($PlanId,$Ingreso)
  {
        list($dbconn) = GetDBconn();
				$query = "	SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, 
										b.nombre_tercero, a.protocolos, b.direccion, b.telefono
									FROM planes as a, terceros as b
									WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var=$resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        return $var;
  }

	function GetDatosCuentaCobro($cuenta)
	{
				unset($_SESSION['CAJA']['FACTURA']['encabezado']);
				$var[0]=EncabezadoCuentaCobro($cuenta);
				$_SESSION['CAJA']['FACTURA']['encabezado']=$var[0];
				list($dbconn) = GetDBconn();
				
				//siempre se hace la del paciente
				$query = "(
									select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
									a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
									b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
									e.texto1, e.texto2, e.mensaje, f.*
									from cuentas_detalle as a, tarifarios_detalle as b,
									fac_facturas_cuentas as c, documentos as e, fac_facturas as f
									where a.numerodecuenta=$cuenta and a.cargo=b.cargo
									and a.tarifario_id=b.tarifario_id
									and a.cargo!='DESCUENTO'
									and c.numerodecuenta=a.numerodecuenta
									and c.sw_tipo=0
									and a.empresa_id=e.empresa_id
									and c.prefijo=e.prefijo
									and c.prefijo=f.prefijo
									and c.factura_fiscal=f.factura_fiscal

									UNION

									select '' as prefijo,  NULL AS factura_fiscal, a.valor_nocubierto,a.precio,
									c.codigo_producto as cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
									c.descripcion as desccargo, a.departamento, NULL AS grupo_tipo_cargo,
									NULL as sw_tipo,'' as texto1, '' as texto2, '' as mensaje, 
									NULL as empresa_id, NULL as prefijo ,NULL as factura_fiscal,NULL as estado,
									NULL as usuario_id,NULL as fecha_registro,NULL as total_factura,
									NULL as gravamen,NULL as valor_cargos,NULL as valor_cuota_paciente,
									NULL as valor_cuota_moderadora,NULL as descuento,NULL as plan_id,
									NULL as tipo_id_tercero,NULL as tercero_id,NULL as sw_clase_factura,
									NULL as concepto,NULL as total_capitacion_real,NULL as documento_id,
									NULL as tipo_factura,NULL as documento_contable_id,NULL as saldo
									from cuentas_detalle as a,bodegas_documentos_d as b, 
									inventarios_productos c
									where a.numerodecuenta=$cuenta
									and a.consecutivo=b.consecutivo
									and b.codigo_producto=c.codigo_producto
									)";
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
				//print_r($var);
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

	function EmpresaCuentaCobro($empresa)
  {
        list($dbconn) = GetDBconn();
				$query = "SELECT tipo_id_tercero,id, razon_social, direccion,
													telefonos,fax
											FROM empresas
											WHERE empresa_id='$empresa';";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var=$resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        return $var;
  }

	function PacienteCuentaCobro($empresa, $prefijo, $numero)
  {
        list($dbconn) = GetDBconn();
				$query = "SELECT d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_apellido as nombre,
										d.tipo_id_paciente, d.paciente_id, e.total_factura, g.poliza,
										d.residencia_direccion, d.residencia_telefono, b.fecha_registro,
										b.fecha_cierre
									FROM fac_facturas_cuentas a,
												cuentas b, ingresos c, pacientes d,
												fac_facturas e, ingresos_soat f, soat_eventos g
									WHERE a.empresa_id='$empresa'
										AND a.prefijo='$prefijo'
										AND a.factura_fiscal=$numero
										AND a.numerodecuenta=b.numerodecuenta
										AND b.ingreso=c.ingreso
										AND c.tipo_id_paciente=d.tipo_id_paciente 
										AND c.paciente_id=d.paciente_id
										AND a.prefijo=e.prefijo
										AND a.factura_fiscal=e.factura_fiscal
										AND c.ingreso=f.ingreso
										AND f.evento=g.evento;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var=$resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
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
										c.descripcion,ter.nombre_tercero,y.cargo as cargoliquidacion,y.descripcion as descargoliquidacion,usu.usuario
                    from tarifarios_detalle as b,
										cuentas_detalle as a 
										left join cuentas_codigos_agrupamiento as c on(a.codigo_agrupamiento_id=c.codigo_agrupamiento_id)
										
										left join cuentas_cargos_qx_procedimientos as x on(c.cuenta_liquidacion_qx_id=x.cuenta_liquidacion_qx_id AND a.transaccion=x.transaccion)
										left join tarifarios_detalle as y on(y.tarifario_id=x.tarifario_id AND y.cargo=x.cargo)
										
										
										left join cuentas_detalle_profesionales as prof on(a.transaccion=prof.transaccion)
										left join terceros as ter on(prof.tipo_tercero_id=ter.tipo_id_tercero AND prof.tercero_id=ter.tercero_id)
										left join system_usuarios as usu on(usu.usuario_id=a.usuario_id)
                    where a.numerodecuenta=$cuenta and a.cargo=b.cargo
										and a.tarifario_id=b.tarifario_id
										and a.consecutivo is null
                    and a.cargo!='DCTOREDON'
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

//************************************************************************
function GenerarCuentaCobro($datos=array())
{
	IncludeLib("funciones_admision");
	IncludeLib("funciones_facturacion");
	$_SESSION['REPORTES']['VARIABLE']='cuenta_cobro';
	$_SESSION['CUENTACOBRO']['PALAN_ID']=$datos[PlanId];
	$_SESSION['CUENTACOBRO']['INGRESO']=$datos[Ingreso];
	$_SESSION['CUENTACOBRO']['EMPRESA']=$datos[empresa];
	$_SESSION['CUENTACOBRO']['PREFIJO']=$datos[prefijo];
	$_SESSION['CUENTACOBRO']['NUMERO']=$datos[numero];
	$_SESSION['CUENTACOBRO']['CUENTA']=$datos[cuenta];

	$Dir="cache/cuentacobro".$datos[cuenta].".pdf";

	require_once("classes/fpdf/html_class.php");
	include_once("classes/fpdf/conversor.php");

	define('FPDF_FONTPATH','font/');
	$pdf2=new PDF();
	$pdf2->AddPage();
	$pdf2->SetFont('Arial','',6);

					
					$dat=DatosPrincipales($datos[cuenta]);
					$empresa=EncabezadoCuentaCobro($datos[PlanId],$datos[Ingreso]);
					$arr=GetDatosCuentaCobro($datos[empresa],$datos[numero],$tdatos[prefijo]);
					$empresaCC=EmpresaCuentaCobro($datos[empresa]);
					$pacienteCC=PacienteCuentaCobro($datos[empresa],$datos[prefijo],$datos[numero]);
					if($this->datos['sw_copia']==TRUE)
					{
						$copia='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;COPIA';
					}
					else
					{
						$copia='';
					}
					/***** generamos el html ********/

					//$salida="<table width='100%' border=1>";
					$texto='';
					for($i=1;$i<sizeof($arr);$i++)
					{
            			//factura cliente
						if($arr[$i][prefijo]!=NULL AND $arr[$i][factura_fiscal]!=NULL
							AND empty($prefijo) AND empty($prefijo))
						{
							$prefijo=$arr[$i][prefijo];
							$factura=$arr[$i][factura_fiscal];
						}
						if($arr[$i][texto1]!=NULL AND $texto=='')
						{
							$texto=$arr[$i][texto1];
						}
					
					}

					//if($pacienteCC[total_factura] >0)
					if($dat[total_cuenta] >0)
					{
						$html.="<table width= 100 border=0>";
						$html.="  <TR><font size='3'><b>";
						$html.="<TD bgcolor='#CCCCCC'  COLSPAN='2' WIDTH=90><label><font size='2'><b>TOTAL EN LETRAS</b> </font>:  ".convertir_a_letras($dat[total_cuenta])." CON 00/100</label></TD>";
						$html.="  </TR>";
						$html.="  </table>";
					}


					$html.="<BR><BR><BR>";
					$html.="<table width=100 border=0> ";
					$html.="  <TR><font size='3'>";
					$html.="<TD colspan='3' WIDTH=300 align=\"left\"><label>POR CONCEPTO DE SERVICIOS DE SALUD CORRESPONDIENTES AL SEGURO OBLIGATORIO DE ACCIDENTE DE TRANSITO</label></TD>";
					$html.="</font></TR>";
					$html.="  <TR><font size='3'><b>";
					$html.="<TD WIDTH='100%' align=\"left\" colspan='3'>&nbsp;</TD>";
					$html.="  </TR>";

					$html.="  <TR><font size='2'><b>";
					$html.="<TD WIDTH='50%' align=\"left\" colspan='3'><label>PRESTADOS A: </label></TD>";
					$html.="  </TR>";
					$html.=" <TR>";
					$html.="<TD  WIDTH=120 colspan='3' HEIGHT=25>                   PACIENTE: ".$pacienteCC[nombre]."</TD>";
					$html.="  </b></font></TR>";
					$html.="  <TR><font size='2'><b>";
					$html.="<TD  WIDTH=200 colspan='3' HEIGHT=25>                   DOCUMENTO: ".$pacienteCC[tipo_id_paciente].' - '.$pacienteCC[paciente_id]." HISTORIA: ".$pacienteCC[paciente_id]."</TD>";
					$html.="  </b></font></TR>";
					$html.="  <TR><font size='2'><b>";
					$html.="<TD  WIDTH=200 colspan='3' HEIGHT=25>                   POLIZA Nro: ".$pacienteCC[poliza]."</TD>";
					$html.="  </b></font></TR>";
					$html.="  <TR>";
					$html.="<TD WIDTH='100%' align=\"right\" colspan='3'>&nbsp;</TD>";
					$html.="  </TR>";
					$html.="  <TR>";
					$html.="<TD WIDTH='100%' align=\"right\" colspan='3'>&nbsp;</TD>";
					$html.=" </TR>";
					$fecharegistro=explode(' ',$pacienteCC[fecha_registro]);
					$fechacierre=explode(' ',$pacienteCC[fecha_cierre]);
					$html.="  <TR><font size='2'><b>";
					$html.="<TD WIDTH=130 align=\"left\" HEIGHT=25>FECHA INGRESO: ".$fecharegistro[0]."</TD>";
					$html.="<TD WIDTH=130 align=\"left\" HEIGHT=25>FECHA EGRESO: ". $fechacierre[0]."</TD>";
					$html.="<TD WIDTH=80 align=\"left\" HEIGHT=25>DIAS ESTANCIA - -</TD>";
					$html.="  </b></font></TR>";
					$html.="  <TR>";
					$html.="<TD WIDTH='100%' align=\"right\" colspan='3'>&nbsp;</TD>";
					$html.="  </TR>";
					$html.="  <TR>";
					$html.="<TD WIDTH='100%' align=\"right\" colspan='3'>&nbsp;</TD>";
					$html.=" </TR>";
					$html.="  <TR>";
					$html.="<TD WIDTH='100%' align=\"right\" colspan='3'>&nbsp;</TD>";
					$html.="  </TR>";
					$html.="  <TR>";
					$html.="<TD WIDTH='100%' align=\"right\" colspan='3'>&nbsp;</TD>";
					$html.=" </TR>";
					$html.="  <TR><font size='5'><b>";
					$html.="<TD WIDTH=100 align=\"center\">--------------------</TD>";
					$html.="<TD WIDTH=150 align=\"left\">&nbsp;</TD>";
					$html.="  </b></font></TR>";
					$html.="  <TR><font size='2'><b>";
					$html.="<TD WIDTH=120 align=\"center\">FIRMA Y SELLO</TD>";
					$html.="<TD WIDTH=150 align=\"left\">FECHA ENTREGA</TD>";
					$html.="  </b></font></TR>";
					$total_factura=0;
					$html.="</table>";

					$total_factura=$pacienteCC[total_factura];
						 
/*			$salida.="<table width='100%' border=5>";
			$salida.="  <TR><font size='3'><b>";
			$salida.="<TD bgcolor='#CCCCCC'  COLSPAN='2' WIDTH='70'><label><font size='2'><b>ATENDIO</b> </font>:&nbsp;".$arr[0][usuario_id]."-&nbsp;".$arr[0][usuario]." </label></TD>";
			$salida.="  </TR>";
			$salida.="  </table>";*/
			//**************
			//HOJA DE CARGOS
			//**************

			$html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
			$html.="<tr><td width=760 align='CENTER'>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
			$html.="<tr><td width=60 align=\"CENTER\">F CARGO</td><td width=60 align=\"CENTER\">CARGO</td><td width=50 align=\"CENTER\">HAB</td><td width=50 align=\"CENTER\">DPTO</td><td width=200 align=\"CENTER\">DESCRIPCION DEL CARGO</td><td width=40 align=\"CENTER\">CANT</td><td width=60 align=\"CENTER\">VALOR</td><td width=60 align=\"CENTER\">VALOR TOT</td><td width=60 align=\"CENTER\">VLR RECO</td><td width=60 align=\"CENTER\">VLR NO CUB</td><td width=40 align=\"CENTER\">TRAN</td><td width=40 align=\"CENTER\">USUARIO</td></tr>";
			$html.="<tr><td width=760 align='CENTER'>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
			$html.="</table>";

			$html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
			$var=CargosFacturaHoja($datos[cuenta]);
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
                      }
                    }	
										$cant=$valor=0;
										while($var[$x][cargo]==$var[$d][cargo]
													AND $var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
										{
																$html.="<tr><td width=60>".FechaStamp($var[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$var[$d][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$d][departamento]."</td><td width=180>".substr($var[$x][desccargo],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=40 align=\"CENTER\">".$var[$d][transaccion]."</td><td width=40 align=\"CENTER\">".$var[$d][usuario]."</td></tr>";
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
			$medDevo = DevolucionesProducto($datos[cuenta]);			

      $ins=InsumosCuentaC($datos[cuenta]);
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
              }
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
			$hab = $liqHab->LiquidarCargosInternacion($datos[cuenta],false);
						
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
			
      $caja=PagosCuenta($datos[cuenta]);
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
					$caja=PagosCajaRapida($datos[cuenta]);
					$abono=0;
          $html.="<tr><td width=80 align=\"CENTER\">INGCAJA</td><td width=100 align=\"CENTER\">FECHA</td><td width=140 align=\"CENTER\">CAJERA</td><td width=70 align=\"CENTER\">EFECTIVO</td><td width=70 align=\"CENTER\">CHEQUES</td><td width=70 align=\"CENTER\">TARJETAS</td><td width=70 align=\"CENTER\">BONOS</td><td width=40 align=\"CENTER\">RET FTE</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"CENTER\">TOTAL PAGADO</td></tr>";
          for($i=0; $i<sizeof($caja); $i++)
          {
              $html.="<tr><td width=80 align=\"CENTER\">".$caja[$i][prefijo]."".$caja[$i][factura_fiscal]."</td><td width=100 align=\"CENTER\">".$caja[$i][fecha_registro]."</td><td width=140 align=\"CENTER\">".$caja[$i][nombre]."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_efectivo])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_cheques])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_tarjetas])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_bonos])."</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=40 align=\"CENTER\">&nbsp;</td><td width=80 align=\"RIGHT\">".FormatoValor($caja[$i][total_abono])."</td></tr>";
							$abono+=$caja[$i][total_abono];
          }
			}
			$dev = Devoluciones($datos[cuenta]);	
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
      $html.="<tr><td width=520>TOTAL_ DE ABONOS: </td><td width=240 align=\"RIGHT\">".FormatoValor($abono)."</td></tr>";
	
			//hay devoluciones
			if(!empty($dev))
			{
				$html.="<tr><td width=520>TOTAL DEVOLUCIONES: </td><td width=240 align=\"RIGHT\">".FormatoValor($dev[0]['devoluciones'])."</td></tr>";
			
			}	
			//APROVECHAMIENTO
			$apro=BuscarCargoAjusteApro($datos[cuenta]);
			if(!empty($apro))
			{
      		$html.="<tr><td width=520>APROVECHAMIENTOS POR REDONDEO: </td><td width=240 align=\"RIGHT\">".FormatoValor($apro)."</td></tr>";
			}
			//DESCUENTO
			$des=BuscarCargoAjusteDes($datos[cuenta]);
			if(!empty($des))
			{
     		 	$html.="<tr><td width=520>DESCUENTO POR REDONDEO: </td><td width=240 align=\"RIGHT\">".FormatoValor($des)."</td></tr>";
			}
      $html.="<tr><td width=520>TOTAL CUENTA: </td><td width=240 align=\"RIGHT\">".FormatoValor($dat[total_cuenta]+$totalEstancia)."</td></tr>";
      $html.="<tr><td width=150>CARGO A CUENTA DE: </td><td width=370>".$dat[nombre_tercero]."</td><td width=240 align=\"RIGHT\">".FormatoValor($dat[valor_total_empresa])."</td></tr>";
			$saldo=SaldoCuentaPaciente($datos[cuenta]);
			$html.="<tr><td width=520>SALDO PACIENTE: </td><td width=240 align=\"RIGHT\">".FormatoValor($saldo)."</td></tr>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      //$pdf2->Output($Dir,'F');
			//********************************************
			//CARGOS, INSUMOS Y MEDICAMENTOS NO FACTURADOS
			//********************************************
			$var=CargosFacturaHoja($datos[cuenta],$noFacturado='1');
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
			$medDevo = DevolucionesProducto($datos[cuenta],$noFacturado='1');	
      $ins=InsumosCuentaC($datos[cuenta],$noFacturado='1');
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
				$html.="<tr><td width=760 align='CENTER'>CUALQUIER DUDA AL RESPECTO DE ESTE DOCUMENTO, POR FAVOR COMUNIQUELO A COORDINACION GENERAL</td></tr>";
				$html.="</table>";
				$pdf2->WriteHTML($html);
				$pdf2->Output($Dir,'F');
			}else{
				$html.="<tr><td width=760 align='CENTER'>CUALQUIER DUDA AL RESPECTO DE ESTE DOCUMENTO, POR FAVOR COMUNIQUELO A COORDINACION GENERAL</td></tr>";
				$html.="</table>";
				$pdf2->WriteHTML($html);
				$pdf2->Output($Dir,'F');
			}
			UNSET($_SESSION['CUENTACOBRO']);
			return true;
}
//************************************************************************

?>
