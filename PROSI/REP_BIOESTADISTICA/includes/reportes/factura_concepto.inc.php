<?php

/**
 * $Id: factura_concepto.inc.php,v 1.4 2006/12/13 18:49:30 carlos Exp $
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
        while(!$result->EOF)
        {
            $var[]=$result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        return $var;
  }

  function InsumosCuenta($cuenta,$codigo)
  {
    
		list($dbconn) = GetDBconn();
		$querys = "SELECT 	e.descripcion, c.codigo_producto
				   FROM 	cuentas_detalle as a,
							bodegas_documentos_d as c, inventarios_productos as e
				   WHERE 	a.codigo_agrupamiento_id='".$codigo."'
				   AND 		a.numerodecuenta=$cuenta
				   AND 		a.facturado='1'
				   AND 		a.consecutivo=c.consecutivo
				   AND 		c.codigo_producto=e.codigo_producto";
        $result = $dbconn->Execute($querys);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        unset($varIyM);
        while(!$result->EOF)
        {
                $varIyM[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
        }        
        return $varIyM;
  }
  //se creo este metodo para contar los medicamentos no facturados y sumarlos la variabel que suneta el total de cargos
  function InsumosCuentaNoFacturados($cuenta,$codigo)
  {
    
    list($dbconn) = GetDBconn();
    $querys = "SELECT   count(*) as total
           FROM   cuentas_detalle as a,
              bodegas_documentos_d as c, inventarios_productos as e
           WHERE  a.codigo_agrupamiento_id='".$codigo."'
           AND    a.numerodecuenta=$cuenta
           AND    a.facturado='0'
           AND    a.consecutivo=c.consecutivo
           AND    c.codigo_producto=e.codigo_producto";
        $result = $dbconn->Execute($querys);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }else{               
          $varIyMNG=$result->GetRowAssoc($ToUpper = false);
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
        $sql .= "WHERE	FF.prefijo = '".$factura[0]['prefijo']."' ";
        $sql .= "AND	FF.factura_fiscal = ".$factura[0]['factura_fiscal']." ";
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
  
  function NombreUsuarioCierre($factura)
  {
        list($dbconn) = GetDBconn();
        
        $sql .= "SELECT SU.nombre AS nombre ";
        $sql .= "FROM system_usuarios SU, cuentas cta ";
        $sql .= "WHERE cta.numerodecuenta  = '".$factura['numerodecuenta']."' ";        
        $sql .= "AND SU.usuario_id=cta.usuario_cierre";
        
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }

        $var=$result->GetRowAssoc($ToUpper = false);
        return $var;
  }


  function GenerarFactura($dat,$tipo_factura)
  {
    //$swTipoFactura es porque cuendo se llama la favtura del cliente
    //se sobreescribe la del paciente y cuando se intenta abrir
    //siempre muestra la del cliente esto es mandado en 1 desde 
    //facturacion Fiscal funcion FormaFacturarImpresion
    $_SESSION['REPORTES']['VARIABLE']='facturaconcepto';
    $_SESSION['REPORTES']['FACTURACONCEPTO']['ARREGLO']=$dat;
    IncludeLib("tarifario");
		IncludeLib("funciones_admision");
		IncludeLib("funciones_facturacion");
    $Dir="cache/factura_concepto".$tipo_factura.$dat[0][prefijo].$dat[0][factura_fiscal].".pdf";
		require_once("classes/fpdf/html_class.php");
		include_once("classes/fpdf/conversor.php");
    define('FPDF_FONTPATH','font/');
    $pdf2=new PDF();
    $pdf2->AddPage();
    $usu=NombreUsuario($dat);
    if(!is_array($usu)){
      $usu=NombreUsuarioCierre($dat);
    }
    $html.="<table border=0 width=100 align='center' border=0>";
    $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
    $html.="<tr><td width=510> CARGOS DE FACTURACION</td><td width=50 align=\"CENTER\">CANT.</td><td width=100 align=\"CENTER\">VALOR</td><td width=100 align=\"CENTER\">VALOR CON IVA</td></tr>";
    $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
   // $var = CargosFactura($datos[numerodecuenta]);
    $moderadora = $total = $descuentos = $pagado = $total_precio_sin_iva = 0;
    $direc='';
		$cont=0;
		$subtotal=0;    
    for($i=1; $i<sizeof($dat);$i++)
    {
			$html.="<tr>";
				if(empty($dat[$i][concepto]))
					$html.="<td width=510>".$dat[$i][descripcion]."</td>";
				else
					$html.="<td width=510>".$dat[$i][descripcion]."--".$dat[$i][concepto]."</td>";
					$html.="<td width=50>".$dat[$i][cantidad]."</td>";
			$precio_sin_iva = $dat[$i][precio]/(1+$dat[$i][porcentaje_gravamen]/100);
			$total_precio_sin_iva += ($precio_sin_iva*$dat[$i][cantidad]);
			$html.="<td width=100>$ ".FormatoValor($precio_sin_iva)."</td>";
			$html.="<td width=100>$ ".FormatoValor($dat[$i][precio])."</td>";
			$html.="</tr>";
		}
				//$salida .= "<tr><td colspan=\"2\"><br></td></tr>";
		$html.="<tr><td colspan=\"2\" ><br></td></tr>";
		$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
		$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL </td><td width=80 align=RIGHT>".FormatoValor($total_precio_sin_iva)."</td></tr>";
    for($i=1; $i<sizeof($dat);)
    {
			$j=$i;
			while($dat[$i][porcentaje_gravamen]==$dat[$j][porcentaje_gravamen])
			{
				$total_valor_iva += ($dat[$j][valor_gravamen]*$dat[$j][cantidad]);
				$j++;
			}
			$i=$j;
			$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL IVA ".$dat[$i-1][porcentaje_gravamen]."%</td><td width=80 align=RIGHT>".FormatoValor($total_valor_iva)."</td></tr>";
			$total_valor_iva = 0;
		}
		$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL CON IVA</td><td width=80 align=RIGHT>".FormatoValor($dat[0][total_factura])."</td></tr>";
		$valor_pagado=$total_efectivo=$total_cheques=$total_tarjetas=0;
		$total=FormatoValor($dat[0][total_factura]);
		if($tipo_factura=='contado')
		{
			$total_efectivo=$dat[0][total_efectivo];
			$total_cheques=$dat[0][total_cheques];
			$total_tarjetas=$dat[0][total_tarjetas];
			$valor_pagado=$dat[0][total_abono];
			$total=FormatoValor($dat[0][total_factura]-$dat[0][total_abono]);
		}
		$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL EFECTIVO</td><td width=80 align=RIGHT>".FormatoValor($total_efectivo)."</td></tr>";
		$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL CHEQUES</td><td width=80 align=RIGHT>".FormatoValor($total_cheques)."</td></tr>";
		$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL TARJETAS</td><td width=80 align=RIGHT>".FormatoValor($total_tarjetas)."</td></tr>";
		$html.="<tr><td width=530>&nbsp;</td><td width=130><b>TOTAL PAGADO</b></td><td width=80 align=RIGHT><b>".FormatoValor($valor_pagado)."</b></td></tr>";
		//$total=FormatoValor($dat[0][total_factura]);
		$totaletras=FormatoValor($dat[0][total_factura]);
		$totall=str_replace(".","",$totaletras);
		$html.="<tr><td width=530>SON :"."  ".convertir_a_letras($totall)."</td><td width=130>VALOR A PAGAR</td><td width=80 align=RIGHT>".$total."</td></tr>";
		$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
		$html.="<tr><td width=760>&nbsp;</td></tr>";
		$html.="<tr><td width=70>&nbsp;</td><td width=180>----------------------------------------------------</td><td width=200>&nbsp;</td><td width=310>----------------------------------------------------</td></tr>";
		$html.="<tr><td width=100>&nbsp;</td><td width=130>FIRMA PACIENTE</td><td width=150>&nbsp;</td><td width=500>ELABORADO POR:  ".$usu['nombre']."</td></tr>";
		$html.="<tr><td width=760>&nbsp;</td></tr>";
		$html.="<tr><td width=20>&nbsp;</td><td width=740 align='center'>ESTA FACTURA CAMBIARIA DE COMPRAVENTA SE ASIMILA PARA TODOS SUS EFECTOS LEGALES A LA LETRA DE CAMBIO  (ARTICULO 621 - 774</td></tr>";
		$html.="<tr><td width=20>&nbsp;</td><td width=740 align='center'>DEL CODIGO DE COMERCIO), EL COMPRADOR ACEPTA QUE LA FIRMA QUE APARECE COMO RECIBIDO ESTA AVALANDO LA FIRMA DEL MISMO.</td></tr>";
		$html.="</table>";

		$pdf2->WriteHTML($html);
		//$pdf2->SetLineWidth(0.5);
		//$pdf2->RoundedRect(7, 7, 196, 284, 3.5, '');
		$pdf2->Output($Dir,'F');
		return true;
  }

?>
