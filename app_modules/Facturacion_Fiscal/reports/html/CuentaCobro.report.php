<?php

/**
 * $Id: CuentaCobro.report.php,v 1.1.1.1 2009/09/11 20:36:46 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class CuentaCobro_report
{

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function CuentaCobro_report($datos=array())
	{
	$this->datos=$datos;
			return true;
	}

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


	function GetMembrete()
	{
		$Membrete = array('file'=>'MembreteLogosSOS','datos_membrete'=>array('titulo'=>GetVarConfigAplication('Cliente'),
																'subtitulo'=>'FACTURA CAMBIARIA DE COMPRAVENTA ',
																'logo'=>'logocliente.png',
																'align'=>'center'));
		return $Membrete;
	}

    /**
    *
    */
    function CrearReporte()
    {

					include_once("classes/fpdf/conversor.php");
					
					IncludeLib("funciones_admision");
					IncludeLib("funciones_facturacion");
					$dat=$this->DatosPrincipales($this->datos['cuenta']);
					$empresa=$this->EncabezadoCuentaCobro($this->datos['PlanId'],$this->datos['Ingreso']);
					$arr=$this->GetDatosCuentaCobro($this->datos['empresa'],$this->datos['numero'],$this->datos['prefijo']);
					$empresaCC=$this->EmpresaCuentaCobro($this->datos['empresa']);
					$pacienteCC=$this->PacienteCuentaCobro($this->datos['empresa'],$this->datos['prefijo'],$this->datos['numero']);
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
					$salida1.="<table width='100%' border=1>";
					$salida1.="  <TR><b>";
					$salida1.="<TD WIDTH='200%' align=\"center\" colspan='2'><label><b><font size='2'>FECHA: </b>".date('d-m-Y')."</font></TD>";
					$salida1.="  </TR>";
					$salida1.="  <TR><b>";
					$salida1.="<TD WIDTH='30%'><label><b><font size='3'>CUENTA DE COBRO SOAT NRO: </b></font></TD>";
					$salida1.="<TD WIDTH='800%' align=\"left\"><label><font size='2'>".$this->datos['prefijo'].$this->datos['numero']." </font></label></TD>";
					$salida1.="  </TR>";
					$salida1.="  <TR><b>";
					$salida1.="<TD WIDTH='30%'><label><b><font size='3'>EMPRESA ASEGURADORA: </b></font></TD>";
					$salida1.="<TD WIDTH='800%' align=\"left\"><label><font size='2'>".$empresa[nombre_tercero]." </font></label></TD>";
					//$salida.="<TD WIDTH='80' align=\"left\"><label><font size='2'> <b>&nbsp;".$empresa[nombre_tercero]."&nbsp;".$empresa[tipo_id_tercero]."-&nbsp;".$empresa[id]."</b></font>&nbsp;<BR><font size='3'>".$empresa[direccion]."-&nbsp;".$empresa[plan_descripcion]." </font></label></TD>";
					$salida1.="  </TR>";
					$salida1.="  <TR align=\"center\"><b>";
					$salida1.="	<TD WIDTH='400%' colspan='2' align=\"center\">";
					$salida1.="		<table width='80%' border=1 align=\"center\">";
					$salida1.="  		<TR align=\"center\"><b>";
					$salida1.="				<TD WIDTH='30%' align=\"left\"><label><b><font size='3'>DEBE A: </b></font></TD><TD WIDTH='130%' align=\"left\"><label>".$empresaCC[razon_social]."</TD>";
					$salida1.="  		</TR>";
					$salida1.="  		<TR align=\"center\"><b>";
					$salida1.="				<TD WIDTH='30%' align=\"left\">&nbsp;</TD><TD WIDTH='30%' align=\"left\">".$empresaCC[tipo_id_tercero]."-".$empresaCC[id]."</TD>";
					$salida1.="  		</TR>";
					$salida1.="  		<TR align=\"center\"><b>";
					$salida1.="				<TD WIDTH='30%' align=\"left\"><label><b><font size='3'>DIRECCIÓN: </b></font></TD><TD WIDTH='100%' align=\"left\">".$empresa[direccion]."</TD>";
					$salida1.="			</TR>";
					$salida1.="			<TR align=\"center\"><b>";
					$salida1.="				<TD WIDTH='30%' align=\"left\"><label><b><font size='3'>TELEFONOS: </b></font></TD><TD WIDTH='100%' align=\"left\">".$empresa[telefono]."</TD>";
					$salida1.="			</TR>";
					$salida1.="			<TR align=\"center\"><b>";
					$salida1.="				<TD WIDTH='30%'  align=\"left\"><label><b><font size='3'>CENTRO DE COSTO: </b></font></TD><TD WIDTH='50%' align=\"left\">".$dat[deservicio]."</TD>";
					$salida1.="			</TR>";
					$salida1.="			<TR align=\"center\"><b>";
					$salida1.="				<TD WIDTH='30%'  align=\"left\"><label><b><font size='3'>LA SUMA DE: </b></font></TD><TD WIDTH='50%' align=\"left\">$".FormatoValor($pacienteCC[total_factura])."</TD>";
					$salida1.="			</TR>";
					$salida1.="		</table>";
					$salida1.="		</TD >";
					$salida1.="  </TR>";
					$salida1.="</table>";
					$salida.=$salida1;

					if($pacienteCC[total_factura] >0)
					{
						$salida.="<table width='100%' border=0>";
						$salida.="  <TR><font size='3'><b>";
						$salida.="<TD bgcolor='#CCCCCC'  COLSPAN='2' WIDTH='70'><label><font size='2'><b>TOTAL EN LETRAS</b> </font>:&nbsp;".convertir_a_letras($pacienteCC[total_factura])." CON 00/100</label></TD>";
						$salida.="  </TR>";
						$salida.="  </table>";
					}


					$salida.="<BR><BR><BR>";
					$salida.="<table width=100%' border=0>";
					$salida.="  <TR><font size='3'>";
					$salida.="<TD colspan='3' WIDTH='100%' align=\"left\"><label>POR CONCEPTO DE SERVICIOS DE SALUD CORRESPONDIENTES AL SEGURO OBLIGATORIO DE ACCIDENTE DE TRANSITO</label></TD>";
					$salida.="</font></TR>";
					$salida.="  <TR><font size='3'><b>";
					$salida.="<TD WIDTH='100%' align=\"left\" colspan='3'>&nbsp;</TD>";
					$salida.="  </TR>";

					$salida.="  <TR><font size='2'><b>";
					$salida.="<TD WIDTH='50%' align=\"left\" colspan='3'><label>PRESTADOS A: </label></TD>";
					$salida.="  </TR>";
					$salida.=" <TR>";
					$salida.="<TD  WIDTH='80%' colspan='3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PACIENTE: ".$pacienteCC[nombre]."</TD>";
					$salida.="  </b></font></TR>";
					$salida.="  <TR><font size='2'><b>";
					$salida.="<TD  WIDTH='80%' colspan='3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DOCUMENTO: ".$pacienteCC[tipo_id_paciente].' - '.$pacienteCC[paciente_id]." HISTORIA: ".$pacienteCC[paciente_id]."</TD>";
					$salida.="  </b></font></TR>";
					$salida.="  <TR><font size='2'><b>";
					$salida.="<TD  WIDTH='80%' colspan='3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;POLIZA Nro: ".$pacienteCC[poliza]."</TD>";
					$salida.="  </b></font></TR>";
					$salida.="  <TR>";
					$salida.="<TD WIDTH='100%' align=\"right\" colspan='3'>&nbsp;</TD>";
					$salida.="  </TR>";
					$salida.="  <TR>";
					$salida.="<TD WIDTH='100%' align=\"right\" colspan='3'>&nbsp;</TD>";
					$salida.=" </TR>";
					$fecharegistro=explode(' ',$pacienteCC[fecha_registro]);
					$fechacierre=explode(' ',$pacienteCC[fecha_cierre]);
					$salida.="  <TR><font size='2'><b>";
					$salida.="<TD WIDTH='30%' align=\"left\">FECHA INGRESO: ".$fecharegistro[0]."</TD>";
					$salida.="<TD WIDTH='30%' align=\"left\">FECHA EGRESO: ". $fechacierre[0]."</TD>";
					$salida.="<TD WIDTH='40%' align=\"left\">DIAS ESTANCIA -&nbsp;-</TD>";
					$salida.="  </b></font></TR>";
					$salida.="  <TR>";
					$salida.="<TD WIDTH='100%' align=\"right\" colspan='3'>&nbsp;</TD>";
					$salida.="  </TR>";
					$salida.="  <TR>";
					$salida.="<TD WIDTH='100%' align=\"right\" colspan='3'>&nbsp;</TD>";
					$salida.=" </TR>";
					$salida.="  <TR>";
					$salida.="<TD WIDTH='100%' align=\"right\" colspan='3'>&nbsp;</TD>";
					$salida.="  </TR>";
					$salida.="  <TR>";
					$salida.="<TD WIDTH='100%' align=\"right\" colspan='3'>&nbsp;</TD>";
					$salida.=" </TR>";
					$salida.="  <TR><font size='5'><b>";
					$salida.="<TD WIDTH='60%' align=\"center\">--------------------</TD>";
					$salida.="<TD WIDTH='40%' align=\"left\">&nbsp;</TD>";
					$salida.="  </b></font></TR>";
					$salida.="  <TR><font size='2'><b>";
					$salida.="<TD WIDTH='60%' align=\"center\">FIRMA Y SELLO</TD>";
					$salida.="<TD WIDTH='40%' align=\"left\">FECHA ENTREGA</TD>";
					$salida.="  </b></font></TR>";
					$total_factura=0;
					$salida.="</table>";

					$total_factura=$pacienteCC[total_factura];
						 
/*			$salida.="<table width='100%' border=5>";
			$salida.="  <TR><font size='3'><b>";
			$salida.="<TD bgcolor='#CCCCCC'  COLSPAN='2' WIDTH='70'><label><font size='2'><b>ATENDIO</b> </font>:&nbsp;".$arr[0][usuario_id]."-&nbsp;".$arr[0][usuario]." </label></TD>";
			$salida.="  </TR>";
			$salida.="  </table>";*/
			//**************
			//HOJA DE CARGOS
			//**************
			$salida.='-<BR><BR><BR><BR><BR>-';
			$salida.=$salida1;
			$salida.="<BR><BR>";
			$salida.="<table border=1 width='100%' align=\"CENTER\">";
			$salida.="<tr><td width='100%' align=\"CENTER\" colspan='10'>HOJA DE CARGOS</td></tr>";
			$salida.="<tr><td width='10%' align=\"CENTER\">F CARGO</td><td width='15%' align=\"CENTER\">CARGO</td><td width='10%' align=\"CENTER\">HAB</td><td width='10%' align=\"CENTER\">DPTO</td><td width='80%' align=\"CENTER\">DESCRIPCION DEL CARGO</td><td width='10%' align=\"CENTER\">CANT</td><td width='15%' align=\"CENTER\">VALOR</td><td width='15%' align=\"CENTER\">VALOR TOT</td><td width='10%' align=\"CENTER\">VLR RECO</td><td width='10%' align=\"CENTER\">VLR NO CUB</td><td width='10%' align=\"CENTER\">TRAN</td></tr>";
			//$salida.="</table>";
			$var=$this->CargosFacturaHoja($this->datos['cuenta']);
			$total=$descuentos=$pagado=0;
			$direc='';
			$totalcar=$totalins=0;

			//$salida.="<table border=1 width='100%' align='center'>";
			for($i=0; $i<sizeof($var);)
			{
						if(!empty($var[$i][codigo_agrupamiento_id]) AND empty($var[$i][consecutivo]))
						{
								$sub=0;
								$salida.="<tr><td width='30%'></td><td width='80%'><B>".$var[$i][descripcion]."</B></td></tr>";
								$x=$i;
								while($var[$i][codigo_agrupamiento_id]==$var[$x][codigo_agrupamiento_id])
								{
										$d=$x;
										$cant=$valor=0;
										while($var[$x][cargo]==$var[$d][cargo]
													AND $var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
										{
																//$salida.="<tr><td width='30%'>".FechaStamp($var[$d][fecha_cargo])."</td><td width='40%' align='CENTER'>".$var[$d][cargo]."</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='20%' align=\"CENTER\">".$var[$d][departamento]."</td><td width='80%'>".substr($var[$x][desccargo],0,37)."</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='20%' align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width='20%' align=\"CENTER\">".FormatoValor($var[$d][precio])."</td><td width='20%' align=\"CENTER\">".FormatoValor($var[$d][valor_cargo])."</td><td width='20%' align=\"CENTER\">".FormatoValor($var[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width='30%' align=\"CENTER\">".$var[$d][transaccion]."</td></tr>";
																$salida.="<tr><td width='30%'>".FechaStamp($var[$d][fecha_cargo])."</td><td width='40%' align='CENTER'>".$var[$d][cargo]."</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='20%' align=\"CENTER\">".$var[$d][departamento]."</td><td width='80%'>".substr($var[$x][desccargo],0,37)."</td><td width='20%' align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width='20%' align=\"CENTER\">".FormatoValor($var[$d][precio])."</td><td width='20%' align=\"CENTER\">".FormatoValor($var[$d][valor_cargo])."</td><td width='20%' align=\"CENTER\">".FormatoValor($var[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width='30%' align=\"CENTER\">".$var[$d][transaccion]."</td></tr>";
																if(!empty($var[$d][nombre_tercero])){
																	$salida.="<tr><td width='20%'></b>PROFESIONAL:</td><td width='80%' colspan=\"4\"></b>".$var[$d][nombre_tercero]."</td></tr>";
																}
																$sub+=$var[$d][valor_cargo];
																$valor+=$var[$d][valor_cargo];
																$cant+=$var[$d][cantidad];
																$d++;
										}
										$x=$d;
										$salida.="<tr><td width='80%'><B>  TOTAL</B></td><td width='20%' align=\"CENTER\"><B>".FormatoValor($cant)."</B></td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='10%' align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
								}
								$salida.="<tr><td width='80%'><B>  TOTAL ".$var[$i][descripcion]."</B></td><td width='20%' align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
								$totalcar+=$sub;
								$i=$x;
						}
						elseif(empty($var[$i][codigo_agrupamiento_id]))
						{
								$direc.="<tr><td width='10%'>".FechaStamp($var[$i][fecha_cargo])."</td><td width='30%' align='CENTER'>".$var[$i][cargo]."</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='20%' align=\"CENTER\">".$var[$i][departamento]."</td><td width='80%'>".substr($var[$i][desccargo],0,37)."</td><td width='10%' align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width='20%' align=\"CENTER\">".FormatoValor($var[$i][precio])."</td><td width='20%' align=\"CENTER\">".FormatoValor($var[$i][valor_cargo])."</td><td width='20%' align=\"CENTER\">".FormatoValor($var[$i][valor_cubierto])."</td><td width='20%' align=\"CENTER\">".FormatoValor($valpac)."</td><td width='20%' align=\"CENTER\">".$var[$i][transaccion]."</td></tr>";
								if(!empty($var[$i][nombre_tercero])){
									$direc.="<tr><td width='20%'></b>PROFESIONAL:</td><td width='80%' colspan=\"4\"></b>".$var[$i][nombre_tercero]."</td></tr>";
								}
								$totalcar+=$var[$i][valor_cargo];
								$i++;
						}
						else
						{ $i++; }
      }
			//------------forma un vector con devoluciones
			$medDevo = $this->DevolucionesProducto($this->datos['cuenta']);			

      $ins=$this->InsumosCuentaC($this->datos['cuenta']);
      $totalins=0;
      if(!empty($ins))
      {
           $sub=0;
          $salida.="<tr><td width='80%'><b>".$ins[0][desagru]."</b></td></tr>";
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
																						
											$salida.="<tr><td width='60%'>&nbsp;</td><td width='20%' align='CENTER'>".$ins[$k][codigo_producto]."</td><td width='20%' align=\"CENTER\">".$ins[$k][departamento]."</td><td width='60%'>".substr($ins[$k][descripcion],0,37)."</td><td width='10%' align=CENTER>".FormatoValor($cant)."</td><td width='15%' align=\"CENTER\">".FormatoValor($precio)."</td><td width='15%' align=\"CENTER\">".FormatoValor($valor)."</td><td width='20%' align=\"CENTER\">".FormatoValor($valCub)."</td><td width='20%' align=\"CENTER\">".FormatoValor($valpac)."</td></tr>";
											$k=$h;																		
									}
									$d=$k;
									$salida.="<tr><td width='80%'>&nbsp;</td><td width='60%'><B>  ".$ins[$i][bodega]."--------</B></td><td width='20%' align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
									/*							
                  $valpac=$ins[$d][valor_cuota_paciente]+$ins[$d][valor_cuota_moderadora]+$ins[$d][valor_nocubierto];
                  $salida.="<tr><td width=60>".FechaStamp($ins[$d][fecha_cargo])."</td><td width=60 align='CENTER'>".$ins[$d][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$d][departamento]."</td><td width=180>".substr($ins[$d][descripcion],0,37)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($ins[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=60 align=\"CENTER\">".$ins[$d][transaccion]."</td></tr>";
                  $total+=$ins[$d][total_costo];
                  $cant+=$ins[$d][cantidad];
                  $valor+=$ins[$d][valor_cargo];
                  $totalins+=$ins[$d][valor_cargo];
                   $sub+=$ins[$d][valor_cargo];
                  $d++;*/
              }
              //$salida.="<tr><td width=80>&nbsp;</td><td width=440><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
              $i=$d;
          }
          $salida.="<tr><td width='80%'><B>  TOTAL ".$ins[0][desagru]."</B></td><td width='40%' align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
      }
      $salida.=$ha;
      $salida.=$direc;
      $salida.="<tr><td width='60%' colspan=\"3\"><B>TOTAL DE CARGOS: </B></td><td width='20%' align=\"CENTER\" colspan=\"7\">&nbsp;</td><td width='50%' align=\"RIGHT\"><B>".FormatoValor($totalcar)."</B></td></tr>";
      //$salida.="<tr><td width='60%' colspan=\"5\"><B>TOTAL DE MEDICAMENTOS E INSUMOS: </B></td><td width='20%' align=\"CENTER\" colspan=\"5\">&nbsp;</td><td width='50%' align=\"RIGHT\"><B>".FormatoValor($totalins)."</B></td></tr>";

     // $salida.="<tr><td width='80%'>----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
			
			//Arranque cali movimientos de habitaciones
			
			if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php")) 
			{
					die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
			}
			
			$liqHab = new LiquidacionHabitaciones;
			$hab = $liqHab->LiquidarCargosInternacion($this->datos['cuenta'],false);
						
			if(is_array($hab))
			{
				$salida.="<tr><td width='60%'><b>HABITACIONES</b></td></tr>";
				$salida.="<tr>";
				$salida.="<td width='20%'><b>TARIF.</b></td>";
				$salida.="<td width='20%'><b>CARGO</b></td>";
				$salida.="<td width='40%'><b>DESCRIPCION</b></td>";
				$salida.="<td width='10%'><b>PRECIO</b></td>";
				$salida.="<td width='10%'><b>CANTIDAD</b></td>";
				$salida.="<td width='10%'><b>TOTAL</b></td>";
				$salida.="</tr>";	
				$total=0;
				for($i=0; $i<sizeof($hab); $i++)
				{		
						$salida.="<tr>";
						$salida.="<td width='20%'><b>".$hab[$i][tarifario_id]."</b></td>";
						$salida.="<td width='20%'><b>".$hab[$i][cargo]."</b></td>";
						$salida.="<td width='40%'><b>".$hab[$i][descripcion]."</b></td>";
						$salida.="<td width='10%'><b>".FormatoValor($hab[$i][precio_plan])."</b></td>";
						$salida.="<td width='10%'><b>".$hab[$i][cantidad]."</b></td>";
						$salida.="<td width='10%'><b>".FormatoValor($hab[$i][valor_cargo])."</b></td>";
						$salida.="</tr>";							
						$totalEstancia +=$hab[$i][valor_cargo];			
				}				
				$salida.="<tr>";
				$salida.="<td width='60%'><b>TOTAL ESTANCIA:</b></td>";
				$salida.="<td width='60%'><b>".FormatoValor($totalEstancia)."</b></td>";				
				$salida.="</tr>";
				
				//$salida.="<tr><td width='60%'>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
				
			}
			
			//fin
			
      $caja=PagosCuenta($this->datos['cuenta']);
			$abono=0;
      if(!empty($caja))
      {
          $salida.="<tr><td width='20%' align=\"CENTER\">INGCAJA</td><td width='20%' align=\"CENTER\">FECHA</td><td width='20%' align=\"CENTER\">CAJERA</td><td width='20%' align=\"CENTER\">EFECTIVO</td><td width='20%' align=\"CENTER\">CHEQUES</td><td width='20%' align=\"CENTER\">TARJETAS</td><td width='20%' align=\"CENTER\">BONOS</td><td width='20%' align=\"CENTER\">RET FTE</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='35%' align=\"CENTER\">TOTAL PAGADO</td></tr>";
          for($i=0; $i<sizeof($caja); $i++)
          {
              $salida.="<tr><td width='20%' align=\"CENTER\">".$caja[$i][prefijo]."".$caja[$i][recibo_caja]."</td><td width='20%' align=\"CENTER\">".$caja[$i][fecha_ingcaja]."</td><td width='60%' align=\"CENTER\">".$caja[$i][nombre]."</td><td width='30%' align=\"CENTER\">".FormatoValor($caja[$i][total_efectivo])."</td><td width='20%' align=\"CENTER\">".FormatoValor($caja[$i][total_cheques])."</td><td width='30%' align=\"CENTER\">".FormatoValor($caja[$i][total_tarjetas])."</td><td width='20%' align=\"CENTER\">".FormatoValor($caja[$i][total_bonos])."</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='40%' align=\"RIGHT\">".FormatoValor($caja[$i][total_abono])."</td></tr>";
							$abono+=$caja[$i][total_abono];
          }
      }
			else
			{
					$caja='';
					$caja=PagosCajaRapida($this->datos['cuenta']);
					$abono=0;
					$salida.="<tr><td width='20%' align=\"CENTER\">INGCAJA</td><td width='10%' align=\"CENTER\">FECHA</td><td width='20%' align=\"CENTER\" colspan=\"2\">CAJERA</td><td width='10%' align=\"CENTER\">EFECTIVO</td><td width='10%' align=\"CENTER\">CHEQUES</td><td width='10%' align=\"CENTER\">TARJETAS</td><td width='10%' align=\"CENTER\">BONOS</td><td width='10%' align=\"CENTER\">RET FTE</td><td width='20%' align=\"CENTER\">TOTAL PAGADO</td></tr>";
					for($i=0; $i<sizeof($caja); $i++)
					{
							$salida.="<tr><td width='20%' align=\"CENTER\">".$caja[$i][prefijo]."".$caja[$i][factura_fiscal]."</td><td width='10%' align=\"CENTER\">".$caja[$i][fecha_registro]."</td><td width='20%' align=\"CENTER\">".$caja[$i][nombre]."</td><td width='10%' align=\"CENTER\">".FormatoValor($caja[$i][total_efectivo])."</td><td width='10%' align=\"CENTER\">".FormatoValor($caja[$i][total_cheques])."</td><td width='10%' align=\"CENTER\">".FormatoValor($caja[$i][total_tarjetas])."</td><td width='10%' align=\"CENTER\">".FormatoValor($caja[$i][total_bonos])."</td><td width='10%' align=\"CENTER\">&nbsp;</td><td width='10%' align=\"RIGHT\">".FormatoValor($caja[$i][total_abono])."</td></tr>";
							$abono+=$caja[$i][total_abono];
					}
			}
			$dev = $this->Devoluciones($this->datos['cuenta']);	
			if(!empty($dev))
			{
					$salida.="<tr><td width='20%' align=\"CENTER\">DEVCAJA</td><td width='20%' align=\"CENTER\">FECHA</td><td width='20%' align=\"CENTER\">CAJERA</td><td width='40%' align=\"CENTER\">&nbsp;</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='30%' align=\"CENTER\">TOTAL DEVOLUCION</td></tr>";
					for($i=0; $i<sizeof($dev); $i++)
					{
							$fec=explode('.',$dev[$i][fecha_registro]);
							$salida.="<tr><td width='30%' align=\"CENTER\">".$dev[$i][prefijo]."".$dev[$i][recibo_caja]."</td><td width='30%' align=\"CENTER\">".$fec[0]."</td><td width='50%' align=\"CENTER\">".$dev[$i][nombre]."</td><td width='40%' align=\"CENTER\">&nbsp;</td><td width='10%' align=\"CENTER\">&nbsp;</td><td width='20%' align=\"RIGHT\">".FormatoValor($dev[$i][total_devolucion])."</td></tr>";
					}
			}
			$salida.="<tr><td><br></td></tr>";
      $salida.="<tr><td width='60%' colspan=\"3\">TOTAL DE ABONOS: </td><td width='20%' align=\"CENTER\" colspan=\"7\">&nbsp;</td><td width='60%' align=\"RIGHT\">".FormatoValor($abono)."</td></tr>";
	
			//hay devoluciones
			if(!empty($dev))
			{
				$salida.="<tr><td width='60%' colspan=\"3\">TOTAL DEVOLUCIONES: </td><td width='20%' align=\"CENTER\" colspan=\"7\">&nbsp;</td><td width='60%' align=\"RIGHT\">".FormatoValor($dev[0]['devoluciones'])."</td></tr>";
			
			}	
			//APROVECHAMIENTO
			$apro=BuscarCargoAjusteApro($this->datos['cuenta']);
			if(!empty($apro))
			{
      		$salida.="<tr><td width='60%' colspan=\"3\">APROVECHAMIENTOS POR REDONDEO: </td><td width='20%' align=\"CENTER\" colspan=\"7\">&nbsp;</td><td width='20%' align=\"RIGHT\">".FormatoValor($apro)."</td></tr>";
			}
			//DESCUENTO
			$des=BuscarCargoAjusteDes($this->datos['cuenta']);
			if(!empty($des))
			{
     		 	$salida.="<tr><td width='60%' colspan=\"3\">DESCUENTO POR REDONDEO: </td><td width='20%' align=\"CENTER\" colspan=\"7\">&nbsp;</td><td width='60%' align=\"RIGHT\">".FormatoValor($des)."</td></tr>";
			}
      $salida.="<tr><td width='60%' colspan=\"3\">TOTAL CUENTA: </td><td width='20%' align=\"CENTER\" colspan=\"7\">&nbsp;</td><td width='60%' align=\"RIGHT\">".FormatoValor($dat[total_cuenta]+$totalEstancia)."</td></tr>";
      $salida.="<tr><td width='60%' colspan=\"3\">CARGO A CUENTA DE: </td><td width='60%' colspan=\"7\">".$dat[nombre_tercero]."</td><td width='60%' align=\"RIGHT\">".FormatoValor($dat[valor_total_empresa])."</td></tr>";
			$saldo=SaldoCuentaPaciente($this->datos['cuenta']);
			$salida.="<tr><td width='60%' colspan=\"3\">SALDO PACIENTE: </td><td width='20%' align=\"CENTER\" colspan=\"7\">&nbsp;</td><td width='60%' align=\"RIGHT\">".FormatoValor($saldo)."</td></tr>";
      //$salida.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      //$pdf2->Output($Dir,'F');
			//********************************************
			//CARGOS, INSUMOS Y MEDICAMENTOS NO FACTURADOS
			//********************************************
			$var=$this->CargosFacturaHoja($datos[numerodecuenta],$noFacturado='1');
			$medDevo = $this->DevolucionesProducto($datos[numerodecuenta],$noFacturado='1');	
      $ins=$this->InsumosCuentaC($datos[numerodecuenta],$noFacturado='1');
			if(!empty($var) || !empty($medDevo) || !empty($ins)){
				UNSET($_SESSION['REPORTES']['VARIABLE']);	
				$salida.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
				$salida.="<tr><td width='80%'><b>CARGOS NO FACTURADOS</b></td></tr>";
				//$salida.="<tr><td width='60%'>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
			}	
			if(!empty($var))
			{
				$total=$descuentos=$pagado=0;
				$direc='';
				$totalcar=$totalins=0;							
				//$salida.="<tr><td width=60 align='CENTER'>CARGO</td><td width=200 align=\"CENTER\">DESCRIPCION</td><td width=40>CANT.</td><td width=70 align=CENTER>PRECIO</td><td width=70 align=\"CENTER\">VALOR NO CUB.</td><td width=75 align=\"CENTER\">VALOR CUB.</td><td width=80 align=\"CENTER\">VAL. CARGO</td></tr>";
				//$salida.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";				
				
				for($i=0; $i<sizeof($var);){
					if(!empty($var[$i][codigo_agrupamiento_id]) AND empty($var[$i][consecutivo])){
						$sub=0;
						$salida.="<tr><td width='60%'></td><td width='80%'><B>".$var[$i][descripcion]."</B></td></tr>";
						$x=$i;
						while($var[$i][codigo_agrupamiento_id]==$var[$x][codigo_agrupamiento_id]){
							$d=$x;
							$cant=$valor=0;
							while($var[$x][cargo]==$var[$d][cargo] AND $var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id]){
								$salida.="<tr><td width='20%'>".FechaStamp($var[$d][fecha_cargo])."</td><td width='20%' align='CENTER'>".$var[$d][cargo]."</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='20%' align=\"CENTER\">".$var[$d][departamento]."</td><td width='40%'>".substr($var[$x][desccargo],0,37)."</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='20%' align=CENTER>".FormatoValor($var[$d][cantidad])."</td><td width='20%' align=\"CENTER\">0</td><td width='20%' align=\"CENTER\">0</td><td width='20%' align=\"CENTER\">0</td><td width='20%' align=\"CENTER\">0</td><td width='10%' align=\"CENTER\">".$var[$d][transaccion]."</td></tr>";
								if(!empty($var[$d][nombre_tercero])){
									$salida.="<tr><td width='20%'></b>PROFESIONAL:</td><td width='80%'></b>".$var[$d][nombre_tercero]."</td></tr>";
								}
								$sub+=$var[$d][valor_cargo];
								$valor+=$var[$d][valor_cargo];
								$cant+=$var[$d][cantidad];
								$d++;
							}
							$x=$d;
							$salida.="<tr><td width='80%'><B>  TOTAL </B></td><td width=40 align=\"CENTER\"><B>".FormatoValor($cant)."</B></td><td width='10%' align=\"CENTER\">&nbsp;</td><td width='10%' align=\"CENTER\"><B>0</B></td></tr>";
						}
						//$salida.="<tr><td width=520><B>  TOTAL ".$var[$i][descripcion]."----------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
						$totalcar+=$sub;
						$i=$x;
					}elseif(empty($var[$i][codigo_agrupamiento_id])){
						$direc.="<tr><td width='20%'>".FechaStamp($var[$i][fecha_cargo])."</td><td width='20%' align='CENTER'>".$var[$i][cargo]."</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='20%' align=\"CENTER\">".$var[$i][departamento]."</td><td width='40%'>".substr($var[$i][desccargo],0,37)."</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='20%' align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width='20%' align=\"CENTER\">0</td><td width='20%' align=\"CENTER\">0</td><td width='20%' align=\"CENTER\">0</td><td width='20%' align=\"CENTER\">0</td><td width='20%' align=\"CENTER\">".$var[$i][transaccion]."</td></tr>";
						if(!empty($var[$i][nombre_tercero])){
							$direc.="<tr><td width='20%'></b>PROFESIONAL:</td><td width='80%'></b>".$var[$i][nombre_tercero]."</td></tr>";
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
				$salida.="<tr><td width='80%'><b>".$ins[0][desagru]."</b></td></tr>";
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
																		
							$salida.="<tr><td width='20%'>&nbsp;</td><td width='20%' align='CENTER'>".$ins[$k][codigo_producto]."</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width=20 align=\"CENTER\">".$ins[$k][departamento]."</td><td width='40%'>".substr($ins[$k][descripcion],0,37)."</td><td width='20%' align=\"CENTER\">&nbsp;</td><td width='20%' align=CENTER>".FormatoValor($cant)."</td><td width='20%' align=\"CENTER\">0</td><td width='20%' align=\"CENTER\">0</td><td width='20%' align=\"CENTER\">0</td><td width='20%' align=\"CENTER\">0</td><td width='20%' align=\"CENTER\">&nbsp;</td></tr>";
							$k=$h;																		
						}
						$d=$k;
						$salida.="<tr><td width='60%'>&nbsp;</td><td width='80%'><B>  ".$ins[$i][bodega]."-----------------------------------------------------------------------------------------</B></td><td width='60%' align=\"CENTER\"><B>0</B></td></tr>";
						
          }
					$i=$d;
        }
        //$salida.="<tr><td width=520><B>  TOTAL ".$ins[0][desagru]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>0</B></td></tr>";
      }	
			if(!empty($var) || !empty($medDevo) || !empty($ins)){
				//$salida.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
				$salida.="<tr><td width='80%' colspan=\"11\" align='CENTER'>CUALQUIER DUDA AL RESPECTO DE ESTE DOCUMENTO, POR FAVOR COMUNIQUELO A COORDINACION GENERAL</td></tr>";
				$salida.="</table>";
			}else{
				$salida.="<tr><td width='80%' colspan=\"11\" align='CENTER'>CUALQUIER DUDA AL RESPECTO DE ESTE DOCUMENTO, POR FAVOR COMUNIQUELO A COORDINACION GENERAL</td></tr>";
				$salida.="</table>";
			}
			return $salida;
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
                    order by a.codigo_agrupamiento_id,b.descripcion asc;";
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

	function PagosCuentaCobro($cuenta)
	{
				$var='';
				list($dbconn) = GetDBconn();
				$query = "select b.fecha_registro, a.prefijo, a.facturafiscal, b.total_abono,
									b.total_efectivo, b.total_cheques, b.total_tarjetas, b.total_bonos,
									b.usuario_id, c.nombre
									from fac_facturas_cuentas as a, fac_facturas as b, system_usuarios as c
									where a.numerodecuenta=$cuenta and a.prefijo=b.prefijo and a.factura_fiscal=b.factura_fiscal
									and b.usuario_id=c.usuario_id";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
								return false;
				}
				if(!$result->EOF)
				{
						while(!$result->EOF)
						{
										$var[]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
						}
						$result->Close();
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

	function DatosPrincipales($cuenta)
	{
        list($dbconn) = GetDBconn();
        $query = "select (a.valor_total_paciente -(a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos)) as saldo,
                  a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
                  c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
                  e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
                  e.residencia_telefono, e.residencia_direccion, d.departamento_actual as dpto, h.descripcion,
                  i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid, i.id, j.departamento,
									k.municipio, d.fecha_registro, a.valor_total_empresa, a.total_cuenta, l.servicio, l.descripcion AS deservicio
                  from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
                  empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d, servicios AS l
                  where a.numerodecuenta=$cuenta and a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
                  and b.tipo_tercero_id=c.tipo_id_tercero
                  and d.ingreso=a.ingreso and d.tipo_id_paciente=e.tipo_id_paciente
                  and d.paciente_id=e.paciente_id
                  and a.empresa_id=i.empresa_id and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                  and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
                  and d.departamento_actual=h.departamento
									and h.servicio=l.servicio;";

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

	function GetDatosCierre($cierre)
	{
				unset($_SESSION['CAJA']['FACTURA']['encabezado']);
         $var[0]=$this->EncabezadoCuentaCobro($cuenta);
				$_SESSION['CAJA']['FACTURA']['encabezado']=$var[0];

        list($dbconn) = GetDBconn();
        
				//siempre se hace la del paciente
				$query = "select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
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
									order by b.grupo_tipo_cargo desc ";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}
				$i=0;
				while(!$result->EOF)
				{
								$var[$i]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
								$i++;
				}
				$result->Close();
        
                return $var;
    
	}

	function GetDatosCuentaCobro($cuenta)
	{
				unset($_SESSION['CAJA']['FACTURA']['encabezado']);
					$var[0]=$this->EncabezadoCuentaCobro($cuenta);
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
				
				return $var;    
	}

function GetFacturaXEmpresa($switche,$cuenta)
{
	list($dbconn) = GetDBconn();
	if(!empty($switche))
        {
							//$var[0]=$this->EncabezadoFactura($cuenta);
							$var[0]=$_SESSION['CAJA']['FACTURA']['encabezado'];
							$query = "select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
												a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
												b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
												e.texto1, e.texto2, e.mensaje, f.*
												from cuentas_detalle as a, tarifarios_detalle as b,
												fac_facturas_cuentas as c, documentos as e, fac_facturas as f
												where a.numerodecuenta=$cuenta and a.cargo=b.cargo
												and a.tarifario_id=b.tarifario_id
												and a.cargo!='DESCUENTO'
												and c.numerodecuenta=a.numerodecuenta
												and c.sw_tipo=1
												and a.empresa_id=e.empresa_id
												and c.prefijo=e.prefijo
												and c.prefijo=f.prefijo
												and c.factura_fiscal=f.factura_fiscal
												order by b.grupo_tipo_cargo desc ";
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

        }
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
	
}
?>

