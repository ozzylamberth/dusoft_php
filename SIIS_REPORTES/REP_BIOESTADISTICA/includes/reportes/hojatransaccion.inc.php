<?php

/**
 * $Id: hojatransaccion.inc.php,v 1.2 2005/06/07 18:40:58 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

  function InsumosCuentaT($cuenta)
  {
        list($dbconn) = GetDBconn();
        $querys = "select a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo,
                    a.transaccion, a.precio, a.cantidad,  e.descripcion, b.codigo_producto,
                    f.descripcion as desccargo, a.departamento, g.descripcion as bodega,
                    h.valor_nocubierto,  h.valor_cubierto, h.valor_cuota_paciente,
                    h.valor_cuota_moderadora, h.valor_cargo
                    from cuentas_detalle as a, bodegas_documentos_d b, bodegas_documentos as c,
                    inventarios_productos as e, tarifarios_detalle as f, bodegas as g,
                    bodegas_documentos_d_cobertura as h
                    where a.numerodecuenta=$cuenta
                    and a.cargo='IMD'
                    and a.cargo!='DIMD' and a.cargo!='DCTOREDON' and a.cargo!='APROVREDON'
                    and a.transaccion=c.transaccion and b.documento=c.documento and
                    b.empresa_id=c.empresa_id and b.centro_utilidad=c.centro_utilidad and
                    b.bodega=c.bodega and b.prefijo=c.prefijo
                    and b.codigo_producto=e.codigo_producto
                    and a.cargo=f.cargo  and a.tarifario_id=f.tarifario_id
                    and g.bodega=c.bodega
                    and h.consecutivo_detalle=b.consecutivo
                    order by g.bodega, a.transaccion, e.descripcion";
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

  function DetalleCargoT($Cuenta,$transaccion)
  {
        list($dbconn) = GetDBconn();
        $query = "select c.*, a.cargo, a.tarifario_id, a.cantidad, a.precio, a.fecha_cargo, b.descripcion as desccargo,
                a.valor_cargo, a.departamento, a.transaccion, a.valor_nocubierto,
                a.valor_cubierto, a.valor_cuota_paciente, a.valor_cuota_moderadora
                from ayudas_diagnosticas as a, tarifarios_detalle as b,
                grupos_tipos_cargo as c where a.transaccion=$transaccion and
                ((a.numerodecuenta=$cuenta and a.cargo=b.cargo
                and a.tarifario_id=b.tarifario_id and
                b.grupo_tipo_cargo=c.grupo_tipo_cargo and c.grupo_tipo_cargo!='SYS'))
                order by a.transaccion, a.departamento";
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


  function GenerarHojaTransaccion($datos)
  {
      $_SESSION['REPORTES']['VARIABLE']='hoja_transaccion';
      $_SESSION['REPORTES']['HOJACARGOS']['ARREGLO']=$datos;
      IncludeLib("tarifario");
      $Dir="cache/hojatransaccion.pdf";
      //require("classes/fpdf/html_class.php");
      //include("classes/fpdf/conversor.php");
      define('FPDF_FONTPATH','font/');
      $pdf2=new PDF();
      $pdf2->AddPage();
      $pdf2->SetFont('Arial','',7);
      $usu=NombreUsuario();
      $html.="<table border=0 width=100 align='center' CELLSPACING=\"1\" CELLPADDING=\"1\">";
      $var=CargosFactura($datos[numerodecuenta]);
      $total=$descuentos=$pagado=0;
      $direc='';
      $totalcar=0;
      for($i=0; $i<sizeof($var);)
      {
          if($var[$i][tarifario_id]=='SYS')
          {
              $sub=0;
              $html.="<tr><td width=60>".$var[$i][cargo]."</td><td width=700>".$var[$i][desccargo]."</td></tr>";
              $x=$i;
              while($var[$i][cargo]==$var[$x][cargo])
              {
                  $arr=DetalleCargoT($datos[numerodecuenta],$var[$i][transaccion]);
                  for($j=0; $j<sizeof($arr);)
                  {
                      $d=$j;
                      $valor=0;
                      while($arr[$j][transaccion]==$arr[$d][transaccion])
                      {
                          $valpac=$arr[$d][valor_cuota_paciente]+$arr[$d][valor_cuota_moderadora]+$arr[$d][valor_nocubierto];
                          $html.="<tr><td width=70>".FechaStamp($arr[$d][fecha_cargo])."</td><td width=70 align='CENTER'>".$arr[$d][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$arr[$d][departamento]."</td><td width=160>".substr($arr[$d][desccargo],0,25)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($arr[$d][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($arr[$d][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($arr[$d][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($arr[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=60 align=\"CENTER\">".$arr[$d][transaccion]."</td></tr>";
                          $total+=$arr[$d][valor_cargo];
                          $valor+=$arr[$d][valor_cargo];
                          $totalcar+=$arr[$d][valor_cargo];
                          $sub+=$arr[$d][valor_cargo];
                          $d++;
                      }
                      $html.="<tr><td width=200><B>".FechaStamp($var[$i][fecha_cargo])."</B></td><td width=200 align=\"CENTER\"><B>".$var[$i][desccargo]."</B></td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">TOTAL  ---></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
                      $j=$d;
                  }
                  $x++;
              }
              $html.="<tr><td width=520><B>  TOTAL ".$var[$i][desccargo]."--------------------------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($sub)."</B></td></tr>";
              $i=$x;
          }
          else
          {
              $valpac=$var[$i][valor_cuota_paciente]+$var[$i][valor_cuota_moderadora]+$var[$i][valor_nocubierto];
              $direc.="<tr><td width=70>".FechaStamp($var[$i][fecha_cargo])."</td><td width=70 align='CENTER'>".$var[$i][cargo]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$var[$i][departamento]."</td><td width=160>".substr($var[$i][desccargo],0,25)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($var[$i][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=60 align=\"CENTER\">".$var[$i][transaccion]."</td></tr>";
              //$direc.="<tr><td width=60 align='center'>".$var[$i][cargo]."</td><td width=550>".substr($var[$i][desccargo],0,90)."</td><td width=50 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=80 align=\"RIGHT\">".FormatoValor($var[$i][precio])."</td></tr>";
              $total+=$var[$i][valor_cargo];
              $totalcar+=$var[$i][valor_cargo];
              $i++;
          }
      }
      $ins=InsumosCuentaT($datos[numerodecuenta]);
      $totalins=0;
      if(!empty($ins))
      {
           $sub=0;
          $html.="<tr><td width=60>".$ins[0][cargo]."</td><td width=700>".$ins[0][desccargo]."</td></tr>";
          for($i=0; $i<sizeof($ins);)
          {
              $d=$i;
              $valor=0;
              while($ins[$i][bodega]==$ins[$d][bodega])
              {
                $j=$d;
                $valor=0;
                while($ins[$d][transaccion]==$ins[$j][transaccion])
                {
                  $valpac=$ins[$j][valor_cuota_paciente]+$ins[$j][valor_cuota_moderadora]+$ins[$d][valor_nocubierto];
                  $html.="<tr><td width=70>".FechaStamp($ins[$j][fecha_cargo])."</td><td width=70 align='CENTER'>".$ins[$j][codigo_producto]."</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=50 align=\"CENTER\">".$ins[$j][departamento]."</td><td width=160>".substr($ins[$j][descripcion],0,25)."</td><td width=20 align=\"CENTER\">&nbsp;</td><td width=40 align=CENTER>".FormatoValor($ins[$j][cantidad])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$j][precio])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$j][valor_cargo])."</td><td width=60 align=\"CENTER\">".FormatoValor($ins[$d][valor_cubierto])."</td><td width=60 align=\"CENTER\">".FormatoValor($valpac)."</td><td width=60 align=\"CENTER\">".$ins[$j][transaccion]."</td></tr>";
                  $total+=$ins[$j][total_costo];
                  $valor+=$ins[$j][valor_cargo];
                  $totalins+=$ins[$j][valor_cargo];
                   $sub+=$ins[$j][valor_cargo];
                  $j++;
                }
                $html.="<tr><td width=200><B>".FechaStamp($ins[$d][fecha_cargo])."</B></td><td width=200 align=\"CENTER\"><B>".$ins[$d][bodega]."</B></td><td width=60 align=\"CENTER\">&nbsp;</td><td width=60 align=\"CENTER\">TOTAL  ---></td><td width=60 align=\"CENTER\"><B>".FormatoValor($valor)."</B></td></tr>";
                $d=$j;
              }
              $i=$d;
          }
          $html.="<tr><td width=520><B>  TOTAL ".$ins[0][desccargo]."-----------------------------------------------------------------------------------------------------</B></td><td width=60 align=\"CENTER\"><B>".FormatoValor($totalins)."</B></td></tr>";
      }
      $html.=$direc;
      $html.="<tr><td width=520><B>TOTAL DE CARGOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($totalcar)."</B></td></tr>";
      $html.="<tr><td width=520><B>TOTAL DE MEDICAMENTOS E INSUMOS: </B></td><td width=240 align=\"RIGHT\"><B>".FormatoValor($totalins)."</B></td></tr>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      $caja=Caja($datos[numerodecuenta]);
      if(!empty($caja))
      {
          $html.="<tr><td width=80 align=\"CENTER\">INGCAJA</td><td width=100 align=\"CENTER\">FECHA</td><td width=80 align=\"CENTER\">CAJERA</td><td width=70 align=\"CENTER\">EFECTIVO</td><td width=70 align=\"CENTER\">CHEQUES</td><td width=70 align=\"CENTER\">TARJETAS</td><td width=70 align=\"CENTER\">BONOS</td><td width=70 align=\"CENTER\">RET FTE</td><td width=70 align=\"CENTER\">&nbsp;</td><td width=80 align=\"CENTER\">TOTAL PAGADO</td></tr>";
          for($i=0; $i<sizeof($caja); $i++)
          {
              $html.="<tr><td width=80 align=\"CENTER\">".$caja[$i][prefijo]."".$caja[$i][recibo_caja]."</td><td width=100 align=\"CENTER\">".$caja[$i][fecha_ingcaja]."</td><td width=80 align=\"CENTER\">".$caja[$i][nombre]."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_efectivo])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_cheques])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_tarjetas])."</td><td width=70 align=\"CENTER\">".FormatoValor($caja[$i][total_bonos])."</td><td width=70 align=\"CENTER\">RET FTE</td><td width=70 align=\"CENTER\">&nbsp;</td><td width=80 align=\"RIGHT\">".FormatoValor($caja[$i][total_abono])."</td></tr>";
          }
      }
      $html.="<tr><td width=520>TOTAL DE ABONOS: </td><td width=240 align=\"RIGHT\">".FormatoValor($datos[abonos])."</td></tr>";
      $html.="<tr><td width=520>SUBTOTAL FACTURA: </td><td width=240 align=\"RIGHT\">".FormatoValor($totalcar-$datos[abonos])."</td></tr>";
      $html.="<tr><td width=520>TOTAL FACTURA: </td><td width=240 align=\"RIGHT\">".FormatoValor($totalcar)."</td></tr>";
      $html.="<tr><td width=150>CARGO A CUENTA DE: </td><td width=370>".$datos[nombre_tercero]."</td><td width=240 align=\"RIGHT\">".FormatoValor($totalcar)."</td></tr>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      $html.="<tr><td width=760>CUALQUIER DUDA AL RESPECTO DE ESTE DOCUMENTO, POR FAVOR COMUNIQUELO A COORDINACION GENERAL</td></tr>";
      $html.="</table>";
      //$pdf2->SetFont('Arial','B',18);
      //$pdf2->SetTextColor(203,203,203);
      //$pdf2->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
      //$pdf2->SetFont('Arial','',8);
      $pdf2->WriteHTML($html);
      //$pdf2->SetLineWidth(0.5);
      //$pdf2->RoundedRect(7, 7, 196, 284, 3.5, '');
      $pdf2->Output($Dir,'F');
      return true;
  }

 /**
  * Se encarga de separar la fecha del formato timestamp
  * @access private
  * @return string
  * @param date fecha
  */
/* function FechaStamp($fecha)
 {
   if($fecha){
      $fech = strtok ($fecha,"-");
      for($l=0;$l<3;$l++)
      {
        $date[$l]=$fech;
        $fech = strtok ("-");
      }
    //  return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
      return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
   }
 }*/
?>
