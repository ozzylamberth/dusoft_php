<?php

/**
 * $Id: factura.inc.php,v 1.1.1.1 2009/09/11 20:36:46 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

  function CargosFactura($cuenta)
  {
        list($dbconn) = GetDBconn();
        $querys = "select a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo,
                    a.transaccion, b.descripcion as desccargo, a.precio, a.cantidad,
                    a.valor_cargo, a.departamento, a.transaccion, a.valor_nocubierto,
                    a.valor_cubierto, b.grupo_tipo_cargo
                    from cuentas_detalle as a, tarifarios_detalle as b
                    where a.numerodecuenta=$cuenta and a.cargo=b.cargo  and a.tarifario_id=b.tarifario_id
                    and a.cargo!='IMD'
                    and a.cargo!='DIMD' and a.cargo!='DCTOREDON' and a.cargo!='APROVREDON'
                    order by b.grupo_tipo_cargo desc";
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

  function DetalleCargo($Cuenta,$transaccion)
  {
        list($dbconn) = GetDBconn();
        $query = "select c.*, a.cargo, a.tarifario_id, a.cantidad, a.precio, a.fecha_cargo, b.descripcion as desccargo, a.valor_cargo
                from ayudas_diagnosticas as a, tarifarios_detalle as b,
                grupos_tipos_cargo as c where a.transaccion=$transaccion and
                ((a.numerodecuenta=$cuenta and a.cargo=b.cargo
                and a.tarifario_id=b.tarifario_id and
                b.grupo_tipo_cargo=c.grupo_tipo_cargo and c.grupo_tipo_cargo!='SYS'))";
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
        return $var;
  }

  function InsumosCuenta($cuenta)
  {
        list($dbconn) = GetDBconn();
        $querys = "select a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo,
                    a.transaccion, a.precio, a.cantidad, a.valor_cargo, e.descripcion, b.codigo_producto,
                    f.descripcion as desccargo, h.valor_cargo
                    from cuentas_detalle as a, bodegas_documentos_d b, bodegas_documentos as c,
                    inventarios_productos as e, tarifarios_detalle as f,
                    bodegas_documentos_d_cobertura as h
                    where a.numerodecuenta=$cuenta
                    and a.cargo='IMD'
                    and a.cargo!='DIMD' and a.cargo!='DCTOREDON' and a.cargo!='APROVREDON'
                    and a.transaccion=c.transaccion and b.documento=c.documento and
                    b.empresa_id=c.empresa_id and b.centro_utilidad=c.centro_utilidad and
                    b.bodega=c.bodega and b.prefijo=c.prefijo
                    and b.codigo_producto=e.codigo_producto
                    and a.cargo=f.cargo  and a.tarifario_id=f.tarifario_id
                    and h.consecutivo_detalle=b.consecutivo
                    order by a.fecha_cargo asc";
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

  function NombreUsuario()
  {
        list($dbconn) = GetDBconn();
        $querys = "select usuario
                    from system_usuarios
                    where usuario_id=".UserGetUID()."";
        $result = $dbconn->Execute($querys);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }

        $var=$result->GetRowAssoc($ToUpper = false);
        return $var;
  }


  function GenerarFactura($datos)
  {
      $_SESSION['REPORTES']['VARIABLE']='factura';
      $_SESSION['REPORTES']['HOJACARGOS']['ARREGLO']=$datos;
      IncludeLib("tarifario");
      $Dir="cache/factura.pdf";
      require("classes/fpdf/html_class.php");
      include("classes/fpdf/conversor.php");
      define('FPDF_FONTPATH','font/');
      $pdf2=new PDF();
      $pdf2->AddPage();
      $usu=NombreUsuario();
      $html.="<table border=0 width=100 align='center' border=0>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      $html.="<tr><td width=610> CARGOS DE FACTURACION</td><td width=50 align=\"CENTER\">CANT.</td><td width=100 align=\"CENTER\">VALOR</td></tr>";
      $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
      $var=CargosFactura($datos[numerodecuenta]);
      $total=$descuentos=$pagado=0;
      $direc='';
      for($i=0; $i<sizeof($var); $i++)
      {
          if($var[$i][tarifario_id]=='SYS')
          {
              $html.="<tr><td width=60>".$var[$i][cargo]."</td><td width=700>".$var[$i][desccargo]."</td></tr>";
              $arr=DetalleCargo($datos[numerodecuenta],$var[$i][transaccion]);
              for($j=0; $j<sizeof($arr); $j++)
              {
                  $html.="<tr><td width=10>&nbsp;</td><td width=50 align='center'>".$arr[$j][cargo]."</td><td width=550>".substr($arr[$j][desccargo],0,90)."</td><td width=50 align=CENTER>".FormatoValor($arr[$j][cantidad])."</td><td width=80 align=\"RIGHT\">".FormatoValor($arr[$j][precio])."</td></tr>";
                  $total+=$arr[$j][valor_cargo];
              }
          }
          else
          {
              $direc.="<tr><td width=60 align='center'>".$var[$i][cargo]."</td><td width=550>".substr($var[$i][desccargo],0,90)."</td><td width=50 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=80 align=\"RIGHT\">".FormatoValor($var[$i][precio])."</td></tr>";
              $total+=$var[$i][valor_cargo];
          }
      }
      $ins=InsumosCuenta($datos[numerodecuenta]);
      if(!empty($ins))
      {
              $html.="<tr><td width=60>".$ins[0][cargo]."</td><td width=700>".$ins[0][desccargo]."</td></tr>";
              for($i=0; $i<sizeof($ins); $i++)
              {
                  $html.="<tr><td width=10>&nbsp;</td><td width=70 align='center'>".$ins[$i][codigo_producto]."</td><td width=530>".substr($ins[$i][descripcion],0,90)."</td><td width=50 align=CENTER>".FormatoValor($ins[$i][cantidad])."</td><td width=80 align=\"RIGHT\">".FormatoValor($ins[$i][valor_cargo])."</td></tr>";
                  $total+=$ins[$i][valor_cargo];
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
      $pdf2->WriteHTML($html);
      $pdf2->SetLineWidth(0.5);
      $pdf2->RoundedRect(7, 7, 196, 284, 3.5, '');
      $pdf2->Output($Dir,'F');
      return true;
  }

 /**
  * Se encarga de separar la fecha del formato timestamp
  * @access private
  * @return string
  * @param date fecha
  */
 function FechaStamp($fecha)
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
 }
?>
