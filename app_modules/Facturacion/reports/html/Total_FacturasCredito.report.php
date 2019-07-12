<?php

/**
 * $Id: Total_FacturasCredito.report.php,v 1.1 2007/04/03 21:41:43 cjrodriguez Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */


  
class Total_FacturasCredito_report
{
 
 function Total_FacturasCredito_report ()
  {
   $this->plan=$_REQUEST['plan'];
   $this->fechai=$_REQUEST['fechai'];
   $this->fechaf=$_REQUEST['fechaf'];
  
  }
  
  function GenerarTotalFacturasCredito()
  {
	$consulta=new Facturacion();
      	$vector = $consulta->Totalfacturascredito($this->plan,$this->fechai,$this->fechaf);
 	$this->salida .= "<br><center><font size=\"4\">REPORTE TOTAL FACTURAS CREDITO</font><br> PLAN : ".$vector[0]['plan_descripcion']."<br> ENTRE LAS FECHAS: ".$this->fechai." - ".$this->fechaf."</center><br>\n";
	
	$this->salida .= "<br><table width=\"100%\" heigth=\"100%\" border=\"2\" cellspacing=\"1\" align=\"center\" class=\"modulo_table_list\">";
	$this->salida .= "      <tr font size=\"2\" align=\"center\" class=\"modulo_table_list_title\">";
	$this->salida .= "        <td>INGRESO No.</td>";
	$this->salida .= "        <td>PACIENTE</td>";
	$this->salida .= "        <td>IDENTIFICACION</td>";
	$this->salida .= "        <td>CUENTA No.</td>";
	$this->salida .= "        <td>FECHA INGRESO</td>";
	$this->salida .= "        <td>FECHA EGRESO</td>";
	$this->salida .= "        <td>FACTURA No.</td>";
	$this->salida .= "        <td>VALOR FACTURA</td>";
	$this->salida .= "        <td>VALOR PAGADO PACIENTE</td>";
	$this->salida .= "        <td>ESTADO FACTURA</td>";
	$this->salida .= "      </tr>";
	//var_dump($vector);
	for($i=0;$i<sizeof($vector);$i++)
				{
						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$pago= $vector[$i]['abono_efectivo']+$vector[$i]['abono_cheque']+$vector[$i]['abono_tarjetas']+$vector[$i]['abono_chequespf']+$vector[$i]['abono_letras']+$vector[$i]['valor_cuota_paciente'];
						$this->salida .= "      <tr font size=\"2\" class=\"$estilo\">";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['ingreso']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['primer_nombre']."  ".  $vector[$i]['segundo_nombre']."  ".  $vector[$i]['primer_apellido']."  ".  $vector[$i]['segundo_apellido']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['paciente_id']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['numerodecuenta']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['fecha_ingreso']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['fecha_cierre']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['factura_fiscal']."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['total_factura']."</td>";
						$this->salida .= "        <td align=\"center\">".$pago."</td>";
						$this->salida .= "        <td align=\"center\">".$vector[$i]['estado']."</td>";
						$this->salida .= "      </tr>";
				}
	$this->salida .= " </table><br>";
        return $this->salida;
	}
		
  
  
 }
  $VISTA = "HTML";
  $_ROOT = "../../../../";
  include	 $_ROOT."includes/enviroment.inc.php";
  Include 	 $_ROOT."app_modules/Facturacion/classes/Facturacion.class.php";
  $filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
  IncludeFile($filename);
  $Fact= new Total_FacturasCredito_report;
  echo $Fact->GenerarTotalFacturasCredito();
  
?>
