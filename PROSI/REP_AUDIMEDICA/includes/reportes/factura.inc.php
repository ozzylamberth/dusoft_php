<?php

/**
 * $Id: factura.inc.php,v 1.29 2006/07/19 14:29:46 lorena Exp $
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


  function GenerarFactura($datos,$swTipoFactura)
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
      $Dir="cache/factura".$datos[numerodecuenta]."".$datos[prefijo]."".$datos[factura_fiscal].".pdf";
    }else{
      $Dir="cache/factura".$datos[numerodecuenta].".pdf";
    }
		require_once("classes/fpdf/html_class.php");
		include_once("classes/fpdf/conversor.php");
    define('FPDF_FONTPATH','font/');
    $pdf2=new PDF();
    $pdf2->AddPage();		
    $usu=NombreUsuario($datos);
    if(!is_array($usu)){
      $usu=NombreUsuarioCierre($datos);
    }
    $html.="<table border=0 width=100 align='center' border=0>";
    $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
    $html.="<tr><td width=610> CARGOS DE FACTURACION</td><td width=50 align=\"CENTER\">CANT.</td><td width=100 align=\"CENTER\">VALOR</td></tr>";
    $html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
    $var = CargosFactura($datos[numerodecuenta]);
    $moderadora = $total = $descuentos = $pagado = 0;
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
				$html.="<tr><td width=60></td><td width=700>".$var[$i][descripcion]."</td></tr>";
				$j=$i;
				while($var[$i][codigo_agrupamiento_id]==$var[$j][codigo_agrupamiento_id])
				{
          $val=$var[$j][valor_cargo]-$var[$j][honorar];
					$html.="<tr><td width=10>&nbsp;</td><td width=80 align='center'>".$var[$j][cargo]."</td><td width=550>".substr($var[$j][desccargo],0,90)."</td><td width=50 align=CENTER>".FormatoValor($var[$j][cantidad])."</td><td width=80 align=\"RIGHT\">".FormatoValor($val)."</td></tr>";
					$subtotal+=$val;
					$j++;
				}
				$i=$j;
      }
      elseif(!empty($var[$i][codigo_agrupamiento_id]) AND !empty($var[$i][consecutivo]))
      {
				$html.="<tr><td width=60></td><td width=700>".$var[$i][descripcion]."</td></tr>";
				$j=$i;
				$dat=InsumosCuenta($var[$i][numerodecuenta],$var[$i][codigo_agrupamiento_id]);
        
				for($k=0; $k<sizeof($dat); $k++)
				{
					$html.="<tr><td width=10>&nbsp;</td><td width=80 align='center'>".$dat[$k][codigo_producto]."</td><td width=550>".substr($dat[$k][descripcion],0,90)."</td><td width=50 align=CENTER>".FormatoValor($var[$j][cantidad])."</td><td width=80 align=\"RIGHT\">".FormatoValor($var[$j][valor_cargo])."</td></tr>";
					$subtotal+=$var[$j][valor_cargo];
					$j++;
				}
        $datNF=InsumosCuentaNoFacturados($var[$i][numerodecuenta],$var[$i][codigo_agrupamiento_id]);
        $j+=$datNF;
				$i=$j;
			}
			elseif(empty($var[$i][codigo_agrupamiento_id]))
      {
				$val=$var[$i][valor_cargo]-$var[$i][honorar];
				$direc.="<tr><td width=80 align='center'>".$var[$i][cargo]."</td><td width=550>".substr($var[$i][desccargo],0,90)."</td><td width=50 align=CENTER>".FormatoValor($var[$i][cantidad])."</td><td width=80 align=\"RIGHT\">".FormatoValor($val)."</td></tr>";
				$subtotal+=$val;
				$i++;
      }
		}
		$html.=$direc;
		$html.="<tr><td width=660 align='LEFT'>SUBTOTAL INGRESOS PARA LA ENTIDAD</td><td width=80 align=\"RIGHT\">".FormatoValor($subtotal)."</td></tr>";
		//profesionales
		$pro=DatosHonorariosVariasCuentas($cuentas);
		if(!empty($pro))
		{
			$total=0;
			$html .= "		 <table WIDTH=\"60%\" border=\"0\" cellspacing=\"4\" cellpadding=\"4\" align=\"left\" class=\"normal_10\">";
			$html .= "			<tr>";
			$html .= "				<td class=\"normal_10N\" colspan=\"3\">INGRESOS PARA TERCEROS</td>";
			$html .= "			</tr>";
			for($i=0; $i<sizeof($pro);)
			{
					$html .= "			<tr><td width=10>&nbsp;</td>";
					$html .= "				<td WIDTH=\"80%\" align=\"left\">".$pro[$i][tercero_id]."</td>";
					$html .= "				<td WIDTH=\"570%\" align=\"left\">".$pro[$i][nombre]."</td>";
					
					$d = $i;
					$valor = 0;
					while($pro[$i][tercero_id]==$pro[$d][tercero_id]
					   AND	$pro[$i][tipo_tercero_id]==$pro[$d][tipo_tercero_id])
					{   $valor += $pro[$d][valor];  $d++; }
					$total+=$valor;
					$i=$d;
					$html .= "				<td WIDTH=\"80%\" align=\"RIGHT\">".FormatoValor($valor)."</td>";
					$html .= "			</tr>";
			}
			$html .= "			<tr>";
			$html .= "				<td class=\"normal_10N\" colspan=\"2\" WIDTH=\"200\">SUBTOTAL INGRESOS PARA TERCEROS</td>";
			$html .= "				<td class=\"normal_10N\" colspan=\"2\" WIDTH=\"460\">&nbsp;</td>";
			$html .= "				<td class=\"normal_10N\" WIDTH=\"80\" align=\"RIGHT\">".FormatoValor($total)."</td>";
			$html .= "			</tr>";
			$html .= "	</table><BR>";
	}
	$descuentopac=$datos[valor_descuento_paciente];
	$descuentocliente=$datos[valor_descuento_empresa];
	$pagado=$datos[abono_efectivo]+$datos[abono_cheque]+$datos[abono_tarjetas]+$datos[abono_chequespf];
	$valpagarpaciente=$datos[valor_total_paciente];
	$totalpac=$datos[valor_total_paciente]-$pagado;
  //se realizo este cambio pues cuando el paciente paga mas de lo que es, el valor a pagar aparece negativo 
  if($totalpac<0){
    $totalpac=$datos[valor_total_paciente];
  }
  //fin cambio   
	$valpagarcliente=$datos[valor_total_empresa];
	$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
	$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL FACTURADO</td><td width=80 align=RIGHT>".FormatoValor($datos[total_cuenta])."</td></tr>";
	$html.="<tr><td width=530>&nbsp;</td><td width=130>VALOR PACIENTE</td><td width=80 align=RIGHT>".FormatoValor($valpagarpaciente)."</td></tr>";
	$html.="<tr><td width=530>&nbsp;</td><td width=130>SUBTOTAL</td><td width=80 align=RIGHT>".FormatoValor($datos[total_cuenta]-$valpagarpaciente)."</td></tr>";
	//sw_tipo 1->cliente 0->paciente 2->particular
	if($datos[sw_tipo]===0 OR $datos[sw_tipo]==2)
	{
			$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL A PAGAR</td><td width=80 align=RIGHT>".FormatoValor($valpagarpaciente)."</td></tr>";
			
			$html.="<tr><td width=530>&nbsp;</td><td width=130>DESCUENTOS</td><td width=80 align=RIGHT>$descuentopac</td></tr>";
			$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL PAGADO</td><td width=80 align=RIGHT>$pagado</td></tr>";
			//$total=str_replace(".","",FormatoValor($valpagarpaciente));
			$total=FormatoValor($totalpac);
			$totall=str_replace(".","",$total);
			$html.="<tr><td width=530>SON :"."  ".convertir_a_letras($totall)."</td><td width=130>VALOR A PAGAR</td><td width=80 align=RIGHT>".$total."</td></tr>";
	}
	elseif($datos[sw_tipo]==1 OR empty($datos[sw_tipo]))
	{
			$html.="<tr><td width=530>&nbsp;</td><td width=130>DESCUENTOS</td><td width=80 align=RIGHT>$descuentocliente</td></tr>";
			//$html.="<tr><td width=530>&nbsp;</td><td width=130>TOTAL PAGADO</td><td width=80 align=RIGHT>$pagado</td></tr>";
			if($var[0]['valor_cuota_moderadora']>0)
			{  $html.="<tr><td width=530>&nbsp;</td><td width=130>CUOTA MODERADORA</td><td width=80 align=RIGHT>".FormatoValor($var[0]['valor_cuota_moderadora'])."</td></tr>";  }
			if($var[0]['valor_cuota_paciente']>0)
			{  $html.="<tr><td width=530>&nbsp;</td><td width=130>COPAGO</td><td width=80 align=RIGHT>".FormatoValor($var[0]['valor_cuota_paciente'])."</td></tr>";  }

			$total=FormatoValor($valpagarcliente);
			$totall=str_replace(".","",$total);
			$html.="<tr><td width=530>SON :"."  ".convertir_a_letras($totall)."</td><td width=130>VALOR A PAGAR</td><td width=80 align=RIGHT><b>".$total."</b></td></tr>";
	}
	$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
	$html.="<tr><td width=760>&nbsp;</td></tr>";
	$html.="<tr><td width=70>&nbsp;</td><td width=180>----------------------------------------------------</td><td width=200>&nbsp;</td><td width=310>----------------------------------------------------</td></tr>";
	$html.="<tr><td width=100>&nbsp;</td><td width=130>FIRMA PACIENTE</td><td width=150>&nbsp;</td><td width=500>ELABORADO POR:  ".$usu['nombre']."  C = ".$datos[numerodecuenta]."</td></tr>";
	$html.="</table>";

	$pdf2->WriteHTML($html);
	//$pdf2->SetLineWidth(0.5);
	//$pdf2->RoundedRect(7, 7, 196, 284, 3.5, '');
	$pdf2->Output($Dir,'F');
	return true;
  }

?>
