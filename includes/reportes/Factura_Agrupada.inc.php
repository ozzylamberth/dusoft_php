<?php

/**
 * $Id: Factura_Agrupada.inc.php,v 1.5 2008/06/23 19:58:05 cahenao Exp $
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

  /**
  *
  */
  function DatosFactura($prefijo,$factura)
  {
			list($dbconn) = GetDBconn();
			$query = "SELECT f.numerodecuenta, f.prefijo, f.factura_fiscal, z.plan_id,
								b.plan_descripcion, c.nombre_tercero, c.tipo_id_tercero, c.tercero_id,
								i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid, i.id,
								j.departamento, k.municipio, c.direccion as dirter, c.telefono as telter,
								z.total_factura,z.concepto,z.fecha_registro, e.texto1, e.texto2, e.mensaje,
								z.gravamen, z.descuento, z.valor_cuota_paciente
								FROM fac_facturas_cuentas as f, fac_facturas as z, planes as b, terceros as c,
								empresas as i, tipo_dptos as j, tipo_mpios as k,
								fac_tipos_facturas as e
								WHERE f.factura_fiscal=$factura and f.prefijo='$prefijo'
								and z.prefijo=f.prefijo and z.factura_fiscal=f.factura_fiscal
								and z.plan_id=b.plan_id and b.tercero_id=c.tercero_id
								and b.tipo_tercero_id=c.tipo_id_tercero
								and z.prefijo=e.prefijo
								and z.empresa_id=i.empresa_id and i.tipo_pais_id=j.tipo_pais_id
								and i.tipo_dpto_id=j.tipo_dpto_id and i.tipo_pais_id=k.tipo_pais_id
								and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			while(!$result->EOF)
			{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
			}

			$result->Close();
			return $vars;
  }

  function GenerarFactura_Agrupada($datos)
  {


	$_SESSION['REPORTES']['VARIABLE']='factura_agrupada';

	$NumerodeCuenta = $datos[numerodecuenta];
	$datos = DatosFactura($datos[prefijo],$datos[factura_fiscal]);
	$_SESSION['REPORTES']['HOJACARGOS']['ARREGLO']=$datos;
	IncludeLib("tarifario");
	IncludeLib("funciones_admision");
	IncludeLib("funciones_facturacion");
	$Dir="cache/Factura_Agrupada".$NumerodeCuenta.".pdf";
	require_once("classes/fpdf/html_class.php");
	include_once("classes/fpdf/conversor.php");
	define('FPDF_FONTPATH','font/');
	$pdf2=new PDF();
	$pdf2->AddPage();		
	
	$html.="<table border=0 width=100 align='center' border=0>";
	$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
	$html.="<tr><td width=610>CONCEPTO</td><td width=50 align=\"CENTER\">&nbsp;</td><td width=100 align=\"CENTER\">VALOR</td></tr>";
	$html.="<tr><td width=690 colspan=\"2\" align='LEFT'>".$datos[0][concepto]."</td><td width=100>".FormatoValor($datos[0][total_factura])."</td></tr>";
	$html.="<tr><td width=590>&nbsp;</td><td width=65>TOTAL FACTURADO</td><td width=35>&nbsp;</td>".FormatoValor($datos[0][total_factura])."</td></tr>";
	$html.="<tr><td width=590>&nbsp;</td><td width=65>TOTAL IVA</td><td width=35>&nbsp;</td>".FormatoValor($datos[0][gravamen])."</td></tr>";
	$total=FormatoValor($datos[0][total_factura]);

	$totall=str_replace(".","",$total);
	$html.="<tr><td width=170>SON :"." ".convertir_a_letras($totall)." M/C</td><td width=420>&nbsp;</td><td width=75>TOTAL A PAGAR</td><td width=25>&nbsp;</td><td>".FormatoValor($datos[0][total_factura])."</td></tr>";
	
	
	
	
	
	$html.="<tr><td width=760>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td></tr>";
	$html.="<tr><td width=760>&nbsp;</td></tr>";
	$html.="<tr><td width=70>&nbsp;</td><td width=180>----------------------------------------------------</td><td width=200>&nbsp;</td><td width=310>----------------------------------------------------</td></tr>";
	$html.="<tr><td width=100>&nbsp;</td><td width=130>FIRMA  CLIENTE</td><td width=250>&nbsp;</td><td width=500>FIRMA DEL PRESTADOR".$usu['nombre']."   ".$datos[numerodecuenta]."</td></tr>";
	$html.="<tr><td width=760>&nbsp;</td></tr>";
	$pdf2->SetFont('Arial','',7);
//	$html.="<tr><td width=1>&nbsp;</td><td width=600 align='center'>ESTA FACTURA CAMBIARIA DE COMPRAVENTA SE ASIMILA PARA TODOS SUS EFECTOS LEGALES A LA LETRA DE CAMBIO (ARTICULO 621 - 774</td></tr>";
//	$html.="<tr><td width=1>&nbsp;</td><td width=600 align='center'>DEL CODIGO DE COMERCIO), EL COMPRADOR ACEPTA QUE LA FIRMA QUE APARECE COMO RECIBIDO ESTA AVALANDO LA FIRMA DEL MISMO.</td></tr>";
	$html.="</table>";
	
	$pdf2->WriteHTML($html);
	//$pdf2->SetLineWidth(0.5);
	//$pdf2->RoundedRect(7, 7, 196, 284, 3.5, '');
	$pdf2->Output($Dir,'F');
	return true;
  }

?>
