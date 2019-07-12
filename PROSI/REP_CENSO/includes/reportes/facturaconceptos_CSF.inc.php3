<?php

/**
 * $Id: facturaconceptos.inc.php,v 1.13 2006/05/26 18:53:49 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

  function CargosFacturaCon($cuenta)
  {
        list($dbconn) = GetDBconn();
        $querys = "select a.*, b.grupo_tipo_cargo, b.descripcion as desccargo,
										c.descripcion,coalesce(z.valor,0) as honorar
                    from tarifarios_detalle as b,
										cuentas_detalle as a 
                    left join cuentas_codigos_agrupamiento as c on(a.codigo_agrupamiento_id=c.codigo_agrupamiento_id)
                    left join cuentas_detalle_honorarios as z on(a.transaccion=z.transaccion)
                    where a.numerodecuenta=$cuenta and a.cargo=b.cargo
										and a.tarifario_id=b.tarifario_id
                    and a.cargo!='DCTOREDON'
										and a.cargo!='APROVREDON'
                    and a.facturado='1'
                    order by a.codigo_agrupamiento_id asc";
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
	
	function NombreUsuarios($factura)
  {
        list($dbconn) = GetDBconn();
        
        $sql .= "SELECT SU.nombre AS nombre ";
        $sql .= "FROM	system_usuarios SU,";
        $sql .= "		fac_facturas FF ";
        $sql .= "WHERE	FF.prefijo = '".$factura['prefijo']."' ";
        $sql .= "AND	FF.factura_fiscal = ".$factura['factura_fiscal']." ";
        $sql .= "AND	FF.usuario_id = SU.usuario_id ";
        
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }

        $var=$result->GetRowAssoc($ToUpper = false);
        return $var;
  }

  function GenerarFacturaConceptos($datos,$swTipoFactura)
  {
      //$swTipoFactura es porque cuendo se llama la favtura del cliente
      //se sobreescribe la del paciente y cuando se intenta abrir
      //siempre muestra la del cliente esto es mandado en 1 desde 
      //facturacion Fiscal funcion FormaFacturarImpresion  
      $_SESSION['REPORTES']['VARIABLE']='factura';
      $_SESSION['REPORTES']['HOJACARGOS']['ARREGLO']=$datos;
      IncludeLib("tarifario");
			IncludeLib("funciones_admision");
			IncludeLib("funciones_facturacion");
      if($swTipoFactura==1){
        $Dir="cache/facturaconceptos".$datos[numerodecuenta]."".$datos[prefijo]."".$datos[factura_fiscal].".pdf";
      }else{
        $Dir="cache/facturaconceptos".$datos[numerodecuenta].".pdf";
      }  
      require_once("classes/fpdf/html_class.php");
      include_once("classes/fpdf/conversor.php");
      define('FPDF_FONTPATH','font/');
      $pdf=new PDF();
      $pdf->AddPage();
      //$pdf->SetFont('Arial','',7);
      //$pdf->Cell(0,2,'Pagina No '.$pdf->PageNo());
      //$html="".$pdf->image('images/logocliente.png',170,6,18)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
      $usu=NombreUsuarios($datos);
			//$usu=UserGetUID();
      $html.="<table border=0 width=100 align='center'>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      $html.="<tr><td width=660> CONCEPTO DE FACTURACION</td><td width=100 align=\"CENTER\">VALOR</td></tr>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      $var=CargosFacturaCon($datos[numerodecuenta]);
      $total=$descuentos=$pagado=0;
      $direc='';
      $cont=0;
      $subtotal=0;
      for($i=0; $i<sizeof($var);)
      {
            if($cont==0)
            {  
              $cuentas .=$var[$i][numerodecuenta];  $cont++;
            }
            else
            {  
              $cuentas .=','.$var[$i][numerodecuenta];  
            }
						if(!empty($var[$i][codigo_agrupamiento_id]) AND empty($var[$i][consecutivo]))
						{
								$cant=$valor=0;
								$d=$i;
								while($var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
								{
										$cant+=$var[$d][cantidad];
										$valor+=$var[$d][valor_cargo]-$var[$d][honorar];                    
										$d++;
								}
              $subtotal+=$valor;
              $html.="<tr><td width=60 align='center'>&nbsp;</td><td width=550>".substr($var[$i][descripcion],0,90)."</td><td width=50 align=CENTER>".FormatoValor($cant)."</td><td width=80 align=\"RIGHT\">".FormatoValor($valor)."</td></tr>";
								$i=$d;
						}
						elseif(!empty($var[$i][codigo_agrupamiento_id]) AND !empty($var[$i][consecutivo]))
						{
								$cant=$valor=0;
								$d=$i;
								while($var[$i][codigo_agrupamiento_id]==$var[$d][codigo_agrupamiento_id])
								{
										$cant+=$var[$d][cantidad];
										$valor+=$var[$d][valor_cargo];                    
										$d++;
								}
              $subtotal+=$valor;  
              $html.="<tr><td width=60 align='center'>&nbsp;</td><td width=550>".substr($var[$i][descripcion],0,90)."</td><td width=50 align=CENTER>".FormatoValor($cant)."</td><td width=80 align=\"RIGHT\">".FormatoValor($valor)."</td></tr>";
								$i=$d;
						}
          elseif(empty($var[$i][codigo_agrupamiento_id]))
          {   
              $val=$var[$i][valor_cargo]-$var[$i][honorar];
              $direc.="<tr><td width=60 align='center'>".$var[$i][cargo]."</td><td width=550>".substr($var[$i][desccargo],0,90)."</td><td width=50 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=80 align=\"RIGHT\">".FormatoValor($val)."</td></tr>";              
              $subtotal+=$val;
							$i++;
          }
      }
      $html.=$direc;
      $html.="<tr><td width=60 align='center'>&nbsp;</td><td width=600>SUBTOTAL INGRESOS PARA LA ENTIDAD</td><td width=80 align=\"RIGHT\">".FormatoValor($subtotal)."</td></tr>";
      //profesionales
      $pro=DatosHonorariosVariasCuentas($cuentas);
      if(!empty($pro))
      {
        $total=0;             
        $html.= "<tr><td width=700 align='center'>&nbsp;</td></tr>";
        $html.= "<tr><td width=60 align='center'>&nbsp;</td><td width=700>INGRESOS PARA TERCEROS</td></tr>";        
        for($i=0; $i<sizeof($pro);)
        {
            $html.= "<tr>";
            $html.= "<td width=60 align='center'>&nbsp;</td>";
            $html.= "<td width=600 align='center'>".$pro[$i][tercero_id]." ".$pro[$i][nombre]."</td>";            
                        
            $d = $i;
            $valor = 0;
            while($pro[$i][tercero_id]==$pro[$d][tercero_id]
              AND  $pro[$i][tipo_tercero_id]==$pro[$d][tipo_tercero_id])
            {   $valor += $pro[$d][valor];  $d++; }
            $total+=$valor;
            $i=$d;
            $html.= "<td width=80 align=RIGHT>".FormatoValor($valor)."</td>";                        
            $html.= "</tr>";
        }            
        $html.= "<tr>";
        $html.= "<td width=60 align='center'>&nbsp;</td>";
        $html.= "<td width=550>SUBTOTAL INGRESOS PARA TERCEROS</td>";
        $html.= "<td width=50>&nbsp;</td>";
        $html.= "<td width=80 align=RIGHT>".FormatoValor($total)."</td>";
        $html.= "</tr>";
        //$html .= "        <td width=550>SUBTOTAL INGRESOS PARA TERCEROS</td>";
        //$html .= "        <td width=50>&nbsp;</td>";
        //$html .= "        <td width=80>".FormatoValor($total)."</td>";
             
        
      }     	
			$descuentopac=$datos[valor_descuento_paciente];
			$descuentocliente=$datos[valor_descuento_empresa];
			$pagado=$datos[abono_efectivo]+$datos[abono_cheque]+$datos[abono_tarjetas]+$datos[abono_chequespf];
			$valpagarpaciente=$datos[valor_total_paciente];
			$totalpac=$datos[valor_total_paciente]-$pagado;
			$valpagarcliente=$datos[valor_total_empresa];
			$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
			$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL FACTURADO</td><td width=80 align=RIGHT>".FormatoValor($datos[total_cuenta])."</td></tr>";
			$html.="<tr><td width=530>&nbsp;</td><td width=130>VALOR PACIENTE</td><td width=80 align=RIGHT>".FormatoValor($datos[valor_total_paciente])."</td></tr>";
			$subtotal=$datos[total_cuenta]-$datos[valor_total_paciente];
			$html.="<tr><td width=530>&nbsp;</td><td width=130>SUBTOTAL</td><td width=80 align=RIGHT>".FormatoValor($subtotal)."</td></tr>";
			//sw_tipo 1->cliente 0->paciente 2->particular
			if($datos[sw_tipo]==0 OR $datos[sw_tipo]==2)
			{
					$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL A PAGAR</td><td width=80 align=RIGHT>".FormatoValor($valpagarpaciente)."</td></tr>";
					$html.="<tr><td width=530>&nbsp;</td><td width=130>DESCUENTOS</td><td width=80 align=RIGHT>$descuentopac</td></tr>";
					$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL PAGADO</td><td width=80 align=RIGHT>$pagado</td></tr>";
					//$total=str_replace(".","",FormatoValor($valpagarpaciente));
					$total=FormatoValor($totalpac);
					$totall=str_replace(".","",$total);
					$html.="<tr><td width=530>SON :"."  ".convertir_a_letras($totall)."</td><td width=130>VALOR A PAGAR</td><td width=80 align=RIGHT>".$total."</td></tr>";
			}
			elseif($datos[sw_tipo]==1)
			{
					$html.="<tr><td width=530>&nbsp;</td><td width=130>DESCUENTOS</td><td width=80 align=RIGHT>$descuentocliente</td></tr>";
					//$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL PAGADO</td><td width=80 align=RIGHT>$pagado</td></tr>";
					//$totalpagar=$total-$descuentos-$pagar;
					$total=FormatoValor($valpagarcliente);
					$totall=str_replace(".","",$total);
					$html.="<tr><td width=530>SON :"."  ".convertir_a_letras($totall)."</td><td width=130>VALOR A PAGAR</td><td width=80 align=RIGHT><b>".$total."</b></td></tr>";
			}
			$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
			$html.="<tr><td width=760>&nbsp;</td></tr>";
			$html.="<tr><td width=70>&nbsp;</td><td width=180>----------------------------------------------------</td><td width=200>&nbsp;</td><td width=310>----------------------------------------------------</td></tr>";
			$html.="<tr><td width=100>&nbsp;</td><td width=130>FIRMA PACIENTE</td><td width=150>&nbsp;</td><td width=500>ELABORADO POR:  ".$usu['nombre']."  C = ".$datos[numerodecuenta]."</td></tr>";
			$html.="</table>";
			$pdf->WriteHTML($html);
			//$pdf->SetLineWidth(0.5);
			//$pdf->RoundedRect(7, 7, 196, 284, 3.5, '');
			$pdf->Output($Dir,'F');
			return true;
  }

?>
