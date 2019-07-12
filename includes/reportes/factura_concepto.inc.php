<?php

/**
 * $Id: factura_concepto.inc.php,v 1.5 2008/10/07 23:25:32 cahenao Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */
function CargosFactura($cuenta)
{
    list($dbconn) = GetDBconn();
    $querys = " SELECT 	a.*, 
        					b.grupo_tipo_cargo, 
        					b.descripcion as desccargo,
							c.descripcion, 
							x.valor_cuota_moderadora, 
							x.valor_cuota_paciente,coalesce(z.valor,0) as honorar,
							case  when z.valor is null then a.precio else a.precio-z.valor end as precio
                    FROM 	cuentas as x, 
                    		tarifarios_detalle as b,
							cuentas_detalle as a 
							left join cuentas_codigos_agrupamiento as c on(a.codigo_agrupamiento_id=c.codigo_agrupamiento_id)						
							left join cuentas_detalle_honorarios as z on(a.transaccion=z.transaccion)
              WHERE a.numerodecuenta=$cuenta
					AND 	a.numerodecuenta=x.numerodecuenta
					AND 	a.cargo=b.cargo
					AND 	a.tarifario_id=b.tarifario_id
                    AND 	a.cargo!='DCTOREDON'
					AND 	a.cargo!='APROVREDON'
                    ORDER BY a.codigo_agrupamiento_id,a.facturado desc,b.descripcion asc ";

    $result = $dbconn->Execute($querys);
    if ($dbconn->ErrorNo() != 0)
    {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }
    while (!$result->EOF)
    {
        $var[] = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
    }
    return $var;
}

function InsumosCuenta($cuenta, $codigo)
{

    list($dbconn) = GetDBconn();
    $querys = "SELECT 	e.descripcion, c.codigo_producto
				   FROM 	cuentas_detalle as a,
							bodegas_documentos_d as c, inventarios_productos as e
				   WHERE 	a.codigo_agrupamiento_id='" . $codigo . "'
				   AND 		a.numerodecuenta=$cuenta
				   AND 		a.facturado='1'
				   AND 		a.consecutivo=c.consecutivo
				   AND 		c.codigo_producto=e.codigo_producto";
    $result = $dbconn->Execute($querys);
    if ($dbconn->ErrorNo() != 0)
    {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }
    unset($varIyM);
    while (!$result->EOF)
    {
        $varIyM[] = $result->GetRowAssoc($ToUpper = false);
        $result->MoveNext();
    }
    return $varIyM;
}

//se creo este metodo para contar los medicamentos no facturados y sumarlos la variabel que suneta el total de cargos
function InsumosCuentaNoFacturados($cuenta, $codigo)
{

    list($dbconn) = GetDBconn();
    $querys = "SELECT   count(*) as total
           FROM   cuentas_detalle as a,
              bodegas_documentos_d as c, inventarios_productos as e
           WHERE  a.codigo_agrupamiento_id='" . $codigo . "'
           AND    a.numerodecuenta=$cuenta
           AND    a.facturado='0'
           AND    a.consecutivo=c.consecutivo
           AND    c.codigo_producto=e.codigo_producto";
    $result = $dbconn->Execute($querys);
    if ($dbconn->ErrorNo() != 0)
    {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }
    else
    {
        $varIyMNG = $result->GetRowAssoc($ToUpper = false);
    }

    return $varIyMNG['total'];
}

//fin

function NombreUsuario($factura)
{
    list($dbconn) = GetDBconn();

    $sql .= "SELECT SU.nombre AS nombre ";
    $sql .= "FROM	system_usuarios SU,";
    $sql .= "		fac_facturas FF ";
    $sql .= "WHERE	FF.prefijo = '" . $factura[0]['prefijo'] . "' ";
    $sql .= "AND	FF.factura_fiscal = " . $factura[0]['factura_fiscal'] . " ";
    $sql .= "AND	FF.usuario_id = SU.usuario_id ";
    $result = $dbconn->Execute($sql);
    if ($dbconn->ErrorNo() != 0)
    {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }

    $var = $result->GetRowAssoc($ToUpper = false);
    return $var;
}

function NombreUsuarioCierre($factura)
{
    list($dbconn) = GetDBconn();

    $sql .= "SELECT SU.nombre AS nombre ";
    $sql .= "FROM system_usuarios SU, cuentas cta ";
    $sql .= "WHERE cta.numerodecuenta  = '" . $factura['numerodecuenta'] . "' ";
    $sql .= "AND SU.usuario_id=cta.usuario_cierre";

    $result = $dbconn->Execute($sql);
    if ($dbconn->ErrorNo() != 0)
    {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }

    $var = $result->GetRowAssoc($ToUpper = false);
    return $var;
}

function GenerarFactura($dat, $tipo_factura, $impuestos = null)
{
    //$swTipoFactura es porque cuendo se llama la favtura del cliente
    //se sobreescribe la del paciente y cuando se intenta abrir
    //siempre muestra la del cliente esto es mandado en 1 desde 
    //facturacion Fiscal funcion FormaFacturarImpresion
    $_SESSION['REPORTES']['VARIABLE'] = 'facturaconcepto';
    $_SESSION['REPORTES']['FACTURACONCEPTO']['ARREGLO'] = $dat;
    IncludeLib("tarifario");
    IncludeLib("funciones_admision");
    IncludeLib("funciones_facturacion");
    $Dir = "cache/factura_concepto" . $tipo_factura . $dat[0][prefijo] . $dat[0][factura_fiscal] . ".pdf";
    require_once("classes/fpdf/html_class.php");
    include_once("classes/fpdf/conversor.php");
    define('FPDF_FONTPATH', 'font/');
    $pdf2 = new PDF();
    $pdf2->AddPage();
    $usu = NombreUsuario($dat);
    if (!is_array($usu))
    {
        $usu = NombreUsuarioCierre($dat);
    }
    
    $html.="<table border=0 width=100 align='center' border=0>";
    $html.="<tr><td width=760>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
    $html.="<tr><td width=510> CARGOS DE FACTURACION</td><!--td width=100 align=\"CENTER\">VALOR</td--><td width=100 align=\"CENTER\">VALOR</td></tr>";
    $html.="<tr><td width=760>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
    // $var = CargosFactura($datos[numerodecuenta]);
    $moderadora = $total = $descuentos = $pagado = $total_precio_sin_iva = 0;
    $direc = '';
    $cont = 0;
    $subtotal = 0;
    $concepto = "";
    for ($i = 1; $i < sizeof($dat); $i++)
    {
        $html.="<tr>";
        if (empty($dat[$i][concepto]))
        {
            $html.="<td width=510>" . $dat[$i][descripcion]  . "</td>";
        }
        else
        {
            $concepto = substr($dat[$i][concepto], 0, 40);
            $html.="<td width=510>" . $dat[$i][descripcion] . "--" . $concepto . "</td>";
        }
        //$html.="<td width=50>" . $dat[$i][cantidad] . "</td>";
        $precio_sin_iva = $dat[$i]['precio']  - $dat[$i]['valor_gravamen'];
        $total_precio_sin_iva += ($precio_sin_iva * $dat[$i][cantidad]);
        //$html.="<td width=100>$ " . FormatoValor($precio_sin_iva) . "</td>";
        $html.="<td width=100>$ " . FormatoValor($dat[$i]['precio']) . "</td>";
        $html.="</tr>";
        //CASO PARA LAS DESCRIPCIONES LARGAS
        $concepto = substr($dat[$i][concepto], 40, 81);
        if (!empty($concepto))
        {
            $html.="<tr>";
            $html.="<td width=710>" . $concepto . "</td>";
            $html.="<td width=50>&nbsp;</td>";
            $html.="</tr>";
        }
        $concepto = substr($dat[$i][concepto], 121, 202);
        if (!empty($concepto))
        {
            $html.="<tr>";
            $html.="<td width=710>" . $concepto . "</td>";
            $html.="<td width=50>&nbsp;</td>";
            $html.="</tr>";
        }
        $concepto = substr($dat[$i][concepto], 202, 283);
        if (!empty($concepto))
        {
            $html.="<tr>";
            $html.="<td width=710>" . $concepto . "</td>";
            $html.="<td width=50>&nbsp;</td>";
            $html.="</tr>";
        }
        $concepto = substr($dat[$i][concepto], 283, 364);
        if (!empty($concepto))
        {
            $html.="<tr>";
            $html.="<td width=710>" . $concepto . "</td>";
            $html.="<td width=50>&nbsp;</td>";
            $html.="</tr>";
        }
        //CASO PARA LAS DESCRIPCIONES LARGAS
    }
    //$salida .= "<tr><td colspan=\"2\"><br></td></tr>";
    $html.="<tr><td colspan=\"2\" ><br></td></tr>";
    $html.="<tr><td width=760>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
    
    $html .= "<table><tr><td>SUBTOTAL</td><td>IVA</td><td>RETEFUENTE</td> <td>RETEICA</td><td>TOTAL FACTURA</td></tr>";
    //$html.="<tr align='center'><td>" . FormatoValor($total_precio_sin_iva) . "</td>";
    
    
     $html.="<td>" . FormatoValor( $dat[0]['total_factura']  -  $dat[0]['gravamen'] ) ." </td>";
   for ($i = 1; $i < sizeof($dat);)
    {
        $j = $i;
        while ($dat[$i][porcentaje_gravamen] == $dat[$j][porcentaje_gravamen])
        {
            $total_valor_iva += ($dat[$j][valor_gravamen] * $dat[$j][cantidad]);
            $j++;
        }
        $i = $j;
        $html.="<td>" . FormatoValor($total_valor_iva) ." </td>";
        $total_valor_iva = 0;
    }
    
    
   
    $retencion_ica = 0;
    $retencion_fuente = 0;
    
    
    
    if(!is_null($impuestos)){
            if ($impuestos['porcentaje_rtf'] > 0) {      
            //echo $valor_subtotal;
              if ($total_precio_sin_iva >= $impuestos['base_rtf']) {
                  //echo print_r($impuestos);
                  $retencion_fuente = $total_precio_sin_iva  * ($impuestos['porcentaje_rtf'] / 100);
                  if ($retencion_fuente > 0) {
                      $retencion_fuente = (int) $retencion_fuente;
                  }
              }
          }


          if ($impuestos['porcentaje_ica'] > 0) {
              if ($total_precio_sin_iva >= $impuestos['base_ica']) {
                  $retencion_ica = $total_precio_sin_iva * ($impuestos['porcentaje_ica'] / 1000);
                  if ($retencion_ica > 0) {
                      $retencion_ica = (int) $retencion_ica;
                  }
              }

          }
          
           $html.="<td>" . FormatoValor($retencion_fuente) . "</td>";
             $html.="<td>" . FormatoValor($retencion_ica) . "</td>";
    }
    
   
   
    // $html .= "</tr><tr><td>TOTAL EFECTIVO</td> <td>TOTAL CHEQUES</td><td>TOTAL TARJETAS</td><td>TOTAL BONOS</td><td>TOTAL ABONO</td></tr>";
    $valor_pagado = $total_efectivo = $total_cheques = $total_tarjetas = 0;
    $total = $dat[0][total_factura] - ($retencion_ica + $retencion_fuente);
    
     $html.="<td>" . FormatoValor($total) . "</td></tr>";
    if ($tipo_factura == 'contado')
    {
        $total_efectivo = $dat[0][total_efectivo];
        $total_cheques = $dat[0][total_cheques];
        $total_tarjetas = $dat[0][total_tarjetas];
        $valor_pagado = $dat[0][total_abono];
       $total = $total - $dat[0][total_abono];
    }
    /*$html.="<tr align='center'><td>" . FormatoValor($total_efectivo) . "</td>";
    $html.="<td>" . FormatoValor($total_cheques) . "</td>";
    $html.="<td>" . FormatoValor($total_tarjetas) . "</td>";
     $html.="<td>" . FormatoValor($dat[0]['total_bonos']) . "</td>";
    $html.="<td><b>" . FormatoValor($valor_pagado) . "</b></td>";*/

    //$total=FormatoValor($dat[0][total_factura]);
    $totaletras =FormatoValor($dat[0][total_factura] - ($retencion_ica + $retencion_fuente));
    $totall = str_replace(".", "", $totaletras);
   // $html.="</tr><tr><td>TOTAL A PAGAR</td></tr><tr><td>" . FormatoValor($total). "</td></tr></table>";
    $html.="<tr><td width=760>&nbsp;</td></tr>";
     $html.="<tr><td width=760>-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
    $html.="<tr><td width=530>SON :" . "  " . convertir_a_letras($totall) . "</td><td width=130>&nbsp;</td><td width=80 align=RIGHT>&nbsp;</td></tr>";
    $html.="<tr><td width=760>-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
    
    $html.="</table>";
    
    $html.="<tr><td width=760>&nbsp;</td></tr>";
    
     $html.="<table border=0 width=100 align='center' border=0>";
     
     $info = "<tr><td>SIRVASE CONSIGNAR A FAVOR DE DUANA Y CIA LTDA LA SUMA CORRESPONDIENTE EN LAS CUENTAS CORRIENTES:</td></tr>";
     $info = "<tr><td>BBVA No. 50200364-3, COLPATRIA No 050104834-8 O BANCO OCCIDENTE No 025041252.</td></tr>";
    if($dat[0][prefijo] == "FB"){
         $html .= "<tr><td>AUTORIZADOS POR LA DIAN PARA FACTURAR  SEGUN RESOLUCION No 310000061722 DE CALI FECHA 24 DE MAYO DE 2012 </td></tr>";
        $html .= "<tr><td>DEL 4331 AL 6000. SOMOS GRANDES CONTRIBUYENTES, NO EFECTUAR RETENCIONDE IVA RES. No 15633 DEL 18/12/2007-ACT. </td></tr>";
        $html .= "<tr><td>ECONOMICA 201-04 ICA  EN CALI 3.3 X 1.000.</td></tr>";
    } else if($dat[0][prefijo] == "FE"){
         $html .= "<tr><td>AUTORIZADOS POR LA DIAN PARA FACTURAR SEGUN RESOLUCION No 310000070278 DE CALI FECHA 04 DE ABRIL DE 2013 </td></tr>";
        $html .= "<tr><td>DEL 4331 AL 6000. SOMOS GRANDES CONTRIBUYENTES, NO EFECTUAR RETENCIONDE IVA RES. No 15633 DEL 18/12/2007-ACT.  </td></tr>";
        $html .= "<tr><td>ECONOMICA 201-04 ICA EN CALI 3.3 X 1.000.</td></tr>";
    } else if($dat[0][prefijo] == "BM"){
        $html .= "<tr><td>AUTORIZADOS POR LA DIAN PARA FACTURAR SEGUN RESOLUCION No 310000071348 DE CALI FECHA 25 DE JUNIO DE 2013 </td></tr>";
        $html .= "<tr><td>DEL 1118 AL 3000. SOMOS GRANDES CONTRIBUYENTES, NO EFECTUAR RETENCIONDE IVA RES. No 15633 DEL 18/12/2007-ACT. </td></tr>";
        $html .= "<tr><td>ECONOMICA 201-04 ICA EN CALI 3.3 X 1.000.</td></tr>";
    }
   
        $html .= "<tr><td>SIRVASE CONSIGNAR A FAVOR DE DUANA Y CIA LTDA LA SUMA CORRESPONDIENTE EN LAS CUENTAS CORRIENTES:</td></tr>";
        $html .= "<tr><td>BBVA No. 50200364-3, COLPATRIA No 050104834-8, BANCO OCCIDENTE No 025041252.</td></tr>";
        $html.="<tr><td width=760>&nbsp;</td></tr>";
        $html.="<tr><td width=760>&nbsp;</td></tr>";
        $html.="<tr><td width=760>&nbsp;</td></tr>";
        $html.="<tr><td width=760>&nbsp;</td></tr>";
        $html.="<tr><td width=760>&nbsp;</td></tr>";
        $html.="<tr><td width=760>&nbsp;</td></tr>";
         $html.="<tr><td width=760>&nbsp;</td></tr>";
        $html.="<tr><td width=70>&nbsp;</td><td width=180>----------------------------------------------------</td><td width=200>&nbsp;</td><td width=310><span style='width:120px;'>NOMBRE CLIENTE</span> <span style='width:120px;'>----------------------------------------------------</span></td></tr>";
        $html.="<tr><td width=70>&nbsp;</td><td width=180>ELABORADO POR: " . $usu['nombre'] . "</td><td width=200>&nbsp;</td><td width=310><span style='width:120px;'>DOCUMENTO</span> <span style='width:120px;'>----------------------------------------------------</span></td></tr>";
        $html.="<tr><td width=70>&nbsp;</td><td width=180>&nbsp;</td><td width=200>&nbsp;</td><td width=310><span style='width:120px;'>FECHA RECIBIDO</span> <span style='width:120px;'>----------------------------------------------------</span></td></tr>";
        $html.="<tr><td width=760>&nbsp;</td></tr>";
       $html.="</table>";

    $pdf2->WriteHTML($html);
    //$pdf2->SetLineWidth(0.5);
    //$pdf2->RoundedRect(7, 7, 196, 284, 3.5, '');
    $pdf2->Output($Dir, 'F');
    return true;
}

?>
