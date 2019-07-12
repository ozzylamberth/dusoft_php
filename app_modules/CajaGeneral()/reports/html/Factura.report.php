<?php

/**
 * $Id: Factura.report.php,v 1.16 2008/01/22 15:49:24 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class Factura_report
{

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function Factura_report($datos=array())
	{
	$this->datos=$datos;
  //print_r($datos."Hola");
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
					
					$empresa=$this->EncabezadoFactura($this->datos['cuenta']);
					$arr=$this->GetDatosFactura($this->datos['cuenta']);
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
          //$salida .= "<pre>".print_r($arr,true)."</pre>";
          for($m=1;$m<sizeof($arr);$m++)
          {
              $salida .= "<table width='100%' border=5>\n";
              $salida .= "  <TR class=\"normal_11\">\n";
              $salida .= "    <TD COLSPAN='2' align=\"center\">\n";
              $salida .= "      <b>&nbsp;".$empresa[razon_social]."&nbsp;".$empresa[tipo_id_tercero]."-&nbsp;".$empresa[id]."</b>&nbsp;<BR>\n";
              $salida .= "      ".$empresa[direccion]."-&nbsp;".$empresa[telefonos]."\n";
              $salida .= "    </TD>\n";
              $salida .= "  </TR>\n";
              $salida .= "  <TR class=\"normal_10\">\n";
              $salida .= "    <TD COLSPAN='2' >\n";
              $salida .= "      <b>RECIBO DE CAJA Nro</b>\n";
              $salida .= "      :&nbsp;".$arr[$m]['prefijo']."-&nbsp;".$arr[$m]['factura_fiscal']." \n";
              $salida .= "      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$copia\n";
              $salida .= "    </TD>";
              $salida .= "  </TR>";				
              $salida .= "  <tr class=\"normal_10\">";
              $salida .= "    <TD ><b>FECHA</b> :&nbsp;".date('d/m/Y h:i')."</TD>";
              $salida .= "    <TD ><b>CENTRO DE ATENCION</b> :&nbsp;".$arr[0][descripcion]."</TD>";
              $salida .= "  </TR>";
              $salida .= "  <TR class=\"normal_10\">";
              $salida .= "    <TD><b>NOMBRE PACIENTE</b>:&nbsp;".$arr[0][nombre]."</TD>";
              $salida .= "    <TD><b>NO.IDENTIFICACION</b>:&nbsp;".$arr[0][tipo_id_paciente]."-&nbsp;".$arr[0][paciente_id]."</TD>";
              $salida .= "  </TR>";
              $salida .= "</table>";
              $salida .= "<table width='100%' border=5 class=\"normal_10\">\n";
              $salida .= "  <TR>";
              $salida .= "    <TD colspan='5' WIDTH='60%'><label>DETALLE</label></TD>";
              $salida .= "    <TD  WIDTH='40%'>VALOR</TD>";
              $salida .= "  </TR>";
              $salida .= "  <TR>";
              $salida .= "    <TD colspan='5'>";
              $salida .= "      <table width='100%' border=0 class=\"normal_10\">";
              $total_factura=0;
             // for($i=1;$i<sizeof($arr);$i++)
              //{
                      //factura cliente
                     // if($arr[1][sw_tipo]==1)
                      //{
                          $salida.=" <TR><TD WIDTH='60%'><font size='1'>".$arr[$m][desccargo]."</font></TD></TR>";
                          
                      //}
                      //else
                      //{   //factura paciente	
                         // $salida.=" <TR><TD WIDTH='60%'><font size='1'>".$arr[$i][desccargo]."</font></TD></TR>";
                      //}
                //if($arr[$i][total_factura]!=NULL AND $total_factura==0)
                  //$total_factura=$arr[$i][total_factura];
              //}
                $salida.="</table>";
                $salida.="</TD>";

                  $salida.="  <TD  ROWSPAN='3' WIDTH='40%' class=\"normal_10\">";
                  
				  if($arr[$m]['sw_cuota_moderadora']=='1')
                  {
				  
					  if($arr[1][valor_cuota_paciente]>0)
					  {
						$salida.=$arr[0][nombre_copago].":&nbsp;$&nbsp;".FormatoValor($arr[1][valor_cuota_paciente])."<br>";
						$total_factura=$arr[1][valor_cuota_paciente];
					  }

					  if($arr[0][valor_cuota_moderadora]>0)
					  {
						$salida.=$arr[0][nombre_cuota_moderadora].":&nbsp;$&nbsp;".FormatoValor($arr[0][valor_cuota_moderadora])."<br>";
						$total_factura=$arr[0][valor_cuota_moderadora];
					  }
                  }
                  else
                  {
                     {
                      $salida.="VALOR NO CUBIERTO:&nbsp;$&nbsp;".FormatoValor($arr[0][valor_nocubierto])."<br>";
                      $total_factura=$arr[0][valor_nocubierto];
					 }
                  }

                  /*if($arr[1][valor_cargo]>0)
                  {
                      $salida.="Valor no Cubierto :&nbsp;$&nbsp;".FormatoValor($datos[1][valor_cargo])."<br>";
                  }

                  if($arr[1][gravamen] > 0)
                  {
                      $salida.="Valor no Cubierto :&nbsp;$&nbsp;".FormatoValor($arr[1][gravamen])."<br>";
                  }*/
                  $salida.="VALOR RECIBIDO:&nbsp;$&nbsp;".FormatoValor($total_factura)."<br>";
                  $salida.="  </TD>";	

                $salida.="</TR>";

                $salida.="</table>";
          $total=str_replace(".","",FormatoValor($arr[1][total_factura]));
          //$total=str_replace(".","",$ );
          if($total >0)
          {
            $salida.="<table width='100%' border=5>";
            $salida.="  <TR><font size='3'><b>";
            $salida.="<TD bgcolor='#CCCCCC'  COLSPAN='2' WIDTH='70'><label><font size='2'><b>TOTAL EN LETRAS</b> </font>:&nbsp;".convertir_a_letras($total_factura)."******************** </label></TD>";
            $salida.="  </TR>";
            $salida.="  </table>";
          }
                 
          $salida.="<table width='100%' border=5>";
          $salida.="  <TR><font size='3'><b>";
          $salida.="<TD bgcolor='#CCCCCC'  COLSPAN='2' WIDTH='70'><label><font size='2'><b>ATENDIO</b> </font>:&nbsp;".$arr[0][usuario_id]."-&nbsp;".$arr[0][usuario]." </label></TD>";
          $salida.="  </TR>";
    /*			$salida.="  <TR><font size='2' ALIGN=\"CENTER\"><b>";
          $salida.="<TD bgcolor='#CCCCCC'  COLSPAN='2' WIDTH='70' ALIGN=\"CENTER\"><label><font size='2'>Esta factura cambiaria de compraventa se asimila para todos sus efectos legales a la Letra de Cambio (articulo 621 - 774 del Codigo de Comercio), el comprador acepta que la firma que aparece como recibido esta avalando la firma del mismo.</font> </label></TD>";
          $salida.="  </TR>";*/
          $salida.="  </table>";
       }
			//$salida.="  </table>";
						
       return $salida;
    }



	function GetDatosCierre($cierre)
	{
				unset($_SESSION['CAJA']['FACTURA']['encabezado']);
         $var[0]=$this->EncabezadoFactura($cuenta);
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
				
				
				
      // print_r($var);
        
        return $var;
    
	}
			
		
	function GetDatosFactura($cuenta)
	{
				unset($_SESSION['CAJA']['FACTURA']['encabezado']);
         $var[0]=$this->EncabezadoFactura($cuenta);
				$_SESSION['CAJA']['FACTURA']['encabezado']=$var[0];

        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        //c.sw_tipo=0 PACIENTE
				//c.sw_tipo=2 PARTICULAR
				//siempre se hace la del paciente
				$query = " SELECT c.prefijo,
                                   c.factura_fiscal, 
                                   a.valor_nocubierto, 
                                   a.precio, 
                                   a.cargo, 
                                   a.tarifario_id, 
                                   a.cantidad, 
                                   a.fecha_cargo, 
                                   a.transaccion, 
                                   b.descripcion as desccargo,
                                   f.empresa_id, 
                                   c.total_efectivo,
                                   f.valor_cuota_paciente,
                                   f.valor_nocubierto,
                                   f.valor_cubierto,
                                   f.valor_cuota_moderadora,
                                   c.sw_cuota_moderadora
                      FROM    cuentas_detalle as a, 
                                  tarifarios_detalle as b, 
                                  fac_facturas_contado as c, 
                                   cuentas as f 
                      WHERE  a.numerodecuenta=".$cuenta." 
                      AND       a.numerodecuenta=f.numerodecuenta
                      AND       a.cargo=b.cargo 
                      AND       a.tarifario_id=b.tarifario_id 
                      AND       a.cargo!='DESCUENTO' 
                      AND       c.numerodecuenta=a.numerodecuenta 
                       ";
        /*$query = "(
									select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
									a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
									b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
									e.texto1, e.texto2, e.mensaje, f.empresa_id, f.prefijo, f.factura_fiscal,
									f.estado, f.usuario_id, f.fecha_registro,f.total_factura,
									f.gravamen,f.valor_cargos,f.valor_cuota_paciente,
									f.valor_cuota_moderadora,f.descuento,plan_id,
									f.tipo_id_tercero,f.tercero_id,f.sw_clase_factura,
									f.concepto,f.total_capitacion_real,f.documento_id,
									f.tipo_factura,f.documento_contable_id
									from cuentas_detalle as a, tarifarios_detalle as b,
									fac_facturas_cuentas as c, documentos as e, fac_facturas as f
									where a.numerodecuenta=$cuenta and a.cargo=b.cargo
									and a.tarifario_id=b.tarifario_id
									and a.cargo!='DESCUENTO'
									and c.numerodecuenta=a.numerodecuenta
									and (c.sw_tipo=0 OR c.sw_tipo=2)
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
									NULL as tipo_factura,NULL as documento_contable_id
									from cuentas_detalle as a,bodegas_documentos_d as b, 
									inventarios_productos c
									where a.numerodecuenta=$cuenta
									and a.consecutivo=b.consecutivo
									and b.codigo_producto=c.codigo_producto
									)";*/
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





function EncabezadoFactura($cuenta)
  {
        list($dbconn) = GetDBconn();
      $query = "select (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos) as abonos,
                  a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
                  c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
                  e.primer_nombre||' '||e.segundo_nombre||' '||e.primer_apellido||' '||e.segundo_apellido as nombre,
                  e.residencia_telefono, e.residencia_direccion, d.departamento_actual as dpto, h.descripcion,
                  i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid,
                  i.id, j.departamento, k.municipio, d.fecha_registro, a.rango, Z.tipo_afiliado_nombre,
                  b.nombre_cuota_moderadora, b.nombre_copago, x.nombre as nombre_usuario, x.usuario_id,x.usuario,
									a.valor_cuota_moderadora, a.valor_cuota_paciente, a.valor_nocubierto,
									a.valor_total_paciente, a.valor_total_empresa, a.valor_descuento_paciente,
									a.valor_descuento_empresa, a.valor_cubierto
                  from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
                  empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d,
                  system_usuarios as x, tipos_afiliado as Z
                  where a.numerodecuenta=$cuenta and a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
                  and b.tipo_tercero_id=c.tipo_id_tercero
                  and x.usuario_id=".UserGetUID()."
                  and a.tipo_afiliado_id=Z.tipo_afiliado_id
                  and d.ingreso=a.ingreso and d.tipo_id_paciente=e.tipo_id_paciente
                  and d.paciente_id=e.paciente_id
                  and a.empresa_id=i.empresa_id and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                  and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
                  and d.departamento_actual=h.departamento";
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