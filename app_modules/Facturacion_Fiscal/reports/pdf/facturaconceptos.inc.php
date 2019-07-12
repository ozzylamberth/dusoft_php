<?php

/**
 * $Id: facturaconceptos.inc.php,v 1.1.1.1 2009/09/11 20:36:46 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

  function CargosFacturaCon($cuenta)
  {
        list($dbconn) = GetDBconn();
        $querys = "select  a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo,
                    a.transaccion, b.descripcion as desccargo, a.precio, a.cantidad, a.valor_cargo
                    from cuentas_detalle as a, tarifarios_detalle as b
                    where a.numerodecuenta=$cuenta and a.cargo=b.cargo
                    and a.tarifario_id=b.tarifario_id  and a.cargo!='DIMD'
                    and a.cargo!='DCTOREDON' and a.cargo!='APROVREDON'
                    order by a.cargo desc";
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


  function GenerarFacturaConceptos($datos)
  {
      $_SESSION['REPORTES']['VARIABLE']='factura';
      $_SESSION['REPORTES']['HOJACARGOS']['ARREGLO']=$datos;
      IncludeLib("tarifario");
      $Dir="cache/facturaconceptos.pdf";
      define('FPDF_FONTPATH','font/');
      $pdf=new PDF();
      $pdf->AddPage();
      //$pdf->SetFont('Arial','',7);
      //$pdf->Cell(0,2,'Pagina No '.$pdf->PageNo());
      //$html="".$pdf->image('images/logocliente.png',170,6,18)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
      $usu=NombreUsuario();
      $html.="<table border=0 width=100 align='center' border=0>";
      /*$html.="<tr><td width=760><BR><BR></td></tr>";
      $html.="<tr><td width=460><B>".$datos[razon_social]."</B></td><td width=300 align='center'>FACTURA CAMBIARIA DE COMPRAVENTA</td></tr>";
      $html.="<tr><td width=460>".$datos[tipoid].": ".$datos[id]."</td><td width=300>No FP  ".$datos[prefijo]."".$datos[factura_fiscal]."</td></tr>";
      $html.="<tr><td width=230>DIRECCION: ".$datos[direccion]."</td><td width=230> TELEFONOS: ".$datos[telefonos]."</td><td width=300>".$datos[municipio]."-".$datos[departamento]."</td></tr>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      $html.="<tr><td width=230>CLIENTE: ".$datos[nombre_tercero]."</td><td width=300>PLAN: ".$datos[plan_descripcion]."</td><td width=230> DPTO: ".$datos[descripcion]."</td></tr>";
      $html.="<tr><td width=230>DIRECCION: ".$datos[residencia_direccion]."</td><td width=230>TELEFONOS: ".$datos[residencia_telefono]."</td><td width=150>FECHA ELAB.: ".date('d/m/Y')."</td><td width=150>FECHA VENC.: ".$datos[X]."</td></tr>";
      $html.="<tr><td width=270>PACIENTE: ".$datos[nombre]."</td><td width=190>HIS/CLI: ".$datos[X]."</td><td width=150>FECHA INGR.: ".FechaStamp($datos[fecha_registro])."</td><td width=150>FECHA EGRE.: ".$datos[X]."</td></tr>";
      */
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      $html.="<tr><td width=660> CONCEPTO DE FACTURACION</td><td width=100 align=\"CENTER\">VALOR</td></tr>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      $var=CargosFacturaCon($datos[numerodecuenta]);
      $total=$descuentos=$pagado=0;
      $direc='';
      for($i=0; $i<sizeof($var);)
      {
          if($var[$i][tarifario_id]=='SYS' AND $var[$i][cargo]!='IMD')
          {
              $html.="<tr><td width=60>".$var[$i][cargo]."</td><td width=600>".$var[$i][desccargo]."</td><td width=80 align=\"RIGHT\">".FormatoValor($var[$i][valor_cargo])."</td></tr>";
              $arr=DetalleCargo($datos[numerodecuenta],$var[$i][transaccion]);
              $total+=$var[$i][valor_cargo];
              $i++;
          }
          elseif($var[$i][tarifario_id]=='SYS' AND $var[$i][cargo]=='IMD')
          {
              $cant=$valor=0;
              $d=$i;
              while($var[$i][cargo]==$var[$d][cargo])
              {
                  $cant+=$var[$d][cantidad];
                  $valor+=$var[$d][valor_cargo];
                  $d++;
              }
              $html.="<tr><td width=60>".$var[$i][cargo]."</td><td width=600>".$var[$i][desccargo]."</td><td width=80 align=RIGHT>".FormatoValor($valor)."</td></tr>";
              $i=$d;
          }
          else
          {
              $direc.="<tr><td width=60 align='center'>".$var[$i][cargo]."</td><td width=600>".substr($var[$i][desccargo],0,90)."</td><td width=80 align=\"RIGHT\">".FormatoValor($var[$i][valor_cargo])."</td></tr>";
              $total+=$var[$i][valor_cargo];
              $i++;
          }
      }
      $html.=$direc;
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      $html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL FACTURADO</td><td width=80 align=RIGHT>".FormatoValor($total)."</td></tr>";
      $html.="<tr><td width=530>&nbsp;</td><td width=130>DESCUENTOS</td><td width=80 align=RIGHT>$descuentos</td></tr>";
      $html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL PAGADO</td><td width=80 align=RIGHT>$pagado</td></tr>";
      $totalpagar=$total-$descuentos-$pagar;
      $total=str_replace(".","",FormatoValor($totalpagar));
      $html.="<tr><td width=530>SON :"."  ".convertir_a_letras($total)."</td><td width=130>TOTAL A PAGAR</td><td width=80 align=RIGHT>".FormatoValor($totalpagar)."</td></tr>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      $html.="<tr><td width=760>&nbsp;</td></tr>";
      $html.="<tr><td width=170>&nbsp;</td><td width=180>----------------------------------------------------</td><td width=100>&nbsp;</td><td width=310>----------------------------------------------------</td></tr>";
      $html.="<tr><td width=200>&nbsp;</td><td width=130>FIRMA PACIENTE</td><td width=150>&nbsp;</td><td width=280>ELABORADO POR  ".$usu[usuario]."</td></tr>";
      $html.="</table>";
      $pdf->WriteHTML($html);
      $pdf->SetLineWidth(0.5);
      $pdf->RoundedRect(7, 7, 196, 284, 3.5, '');      
      $pdf->Output($Dir,'F');
      return true;
  }

?>
