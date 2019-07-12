<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Pedido.report.php,v 1.5 2010/01/02  
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja T.
  */
  /**
  * Clase Reporte: Pedido_report
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2010  IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja T.
  */

	class FacturaConcepto_report 
	{ 
		var $datos;
		
		var $title       = '';
		var $author      = '';
		var $sizepage    = 'leter';
		var $Orientation = '';
		var $grayScale   = false;
		var $headers     = array();
		var $footers     = array();
		
	 
		function FacturaConcepto_report($datos=array())
		{
			$this->datos=$datos;
			return true;
		}
		
		function GetMembrete()
		{
		    
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= " <b $estilo>FACTURA DE COMPRAVENTA  </b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		
		function CrearReporte()
		{
			IncludeClass('ConexionBD');
			IncludeClass('CajaGeneralEmpresaSQL','','app','CajaGeneralEmpresa');
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$ods = new CajaGeneralEmpresaSQL();
			$dat = $ods->ConsDatosEmpresa($this->datos['farmacia_id']);
			$dats = $ods->ConsultarDatosDelaFactura($this->datos['farmacia_id'],$this->datos['centro'],$this->datos['numeroF'],$this->datos['prefijoF']);
			$usu=$ods->consultarDatosUsuarioActual();
			$tercero=$ods->DatosBasicosTercero($this->datos['tipoid'],$this->datos['id']);
			$prod=$ods->ConsultarDtosDetalleMovimiento($this->datos['farmacia_id'],$this->datos['prefijodoc'],$this->datos['numeracion'],$this->datos['centro'],$this->datos['bodega']);
			
			$sty = " style=\"text-align:left;text-indent:8pt\" ";
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$estilo2 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px; text-indent:6pt\""; 
			$estilo3 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\""; 
			$salida .= "<table width='80%' border='0'>";
			$salida .=  "<tr>";				
			$salida .=  "<td align=\"center\" class=\"titulo2\">".$dat[razon_social]."</td>";
			$salida .=  "</tr>";
			$salida .=  "<tr>";	
			$salida .=  "<td align=\"center\" class=\"normal_10\">".$dat[tipoid].' '.$dat[id]."</td>";
			$salida .=  "</tr>";
			$salida .=  "<tr>";	
			$salida .=  "<td align=\"center\" class=\"normal_10\">".$dat[direccion].' '.$dat[municipio].' - '.$dat[departamento]."</td>";
			$salida .=  "</tr>";
			$salida .= "<tr><td><br></td></tr>";
			$salida .=  "<tr>";	
			$salida .=  "<td align=\"center\" class=\"normal_10N\">FACTURA  DE COMPRAVENTA</td>";
			$salida .=  "</tr>";		
			$salida .=  "<tr>";	
			$salida .=  "<td align=\"center\" class=\"normal_10N\">No. ".$dats[prefijo]." ".$dats[factura_fiscal]."</td>";
			$salida .=  "</tr>";
			$salida .= "</table><br><br><br>";
			$salida .= "<table width='80%' border='1'>";
			$salida .=  "<tr>";	
			$salida .=  "<td align=\"left\" class=\"normal_10\"><b>Fecha  : ".date('d/m/Y h:i')."</b></td>";
			$salida .=  "</tr>";											
			$salida .=  "<tr>";	
			$salida .=  "<td align=\"left\" class=\"normal_10\">".substr('Atendio: '.$usu[usuario_id].' - '.$usu[nombre],0,42)."</td>";
			$salida .=  "</tr>";	
			$salida .=  "<tr>";
			$salida .=  "<td align=\"left\" class=\"normal_10\"><b>Recibo caja No: </b>".$this->datos['recibid']."</td>";
			$salida .=  "</tr>";
			$salida .=  "<tr>";
			$salida .=  "<td align=\"left\" class=\"normal_10\"><b>Identificación: </b>".$tercero[0][tipotercero].' '.$tercero[0][tercero_id]."</td>";
			$salida .=  "</tr>";					
			$salida .=  "<tr>";	
			$salida .=  "<td align=\"left\" class=\"normal_10N\">Cliente : ".$tercero[0][nombre_tercero]."</td>";
			$salida .=  "</tr>";
			$salida .=  "</table><br>";	

			$salida .= "<table width='20%' align=\"left\" border='1'>";
			$salida .=  "<tr>";		
			$salida .=  "<td align=\"left\" class=\"normal_10N\" width='90%'>DETALLE DE LA VENTA </td>";	
			$salida .=  "</tr>";
			$salida .=  "</table>";
			$salida .=  "<br>";
			$salida .=  "<br>";
			$salida .=  "<br>";
      $salida .=  "<table width=\"80%\"   align=\"left\" class=\"modulo_table_list_title\"  border='1' align=\"left\">";
      $salida .=  "<tr align=\" class=\"modulo_table_list_title\" >\n";
      $salida .=  "<td width=\"30%\"><b>MOLECULA</B></td>\n";
      $salida .=  "<td width=\"15%\"><b>CODIGO</b></td>\n";
      $salida .=  "<td width=\"60%\"><b>NOMBRE PRODUCTO</b></td>\n";
      $salida .=  "<td width=\"10%\"><b>CANTIDAD</B></td>\n";
      $salida .=  "<td width=\"10%\"><b>V.TOTAL</B> </td>\n";
      $salida .=  "</tr>\n";
			$est = "modulo_list_claro"; $back = "#DDDDDD";
	    foreach($prod as $key => $dtl)
			{	        
              $salida .=  "<tr class=\"modulo_list_claro\">\n";
	       
	            $salida .=  "<td   align=\"left\">".$dtl['molecula']."</td>\n";
	            $salida .=  "<td   align=\"left\">".$dtl['codigo_producto']."</td>\n";
	            $salida .=  "<td  align=\"left\">".$dtl['descripcion']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']." -".$dtl['laboratorio']."</td>\n";
	            $salida .=  "<td align=\"left\">".$dtl['cantidad']."</td>\n";
	            $salida .=  "<td align=\"left\">".$dtl['total_costo']."</td>\n";
	            $salida .=  "</tr>\n";
				$suma=$suma + $dtl['total_costo'];
			}
			
      $salida .= "	</table><br>\n";
      $salida .=  "<table width=\"80%\" class=\"modulo_table_list_title\"  border='1' align=\"left\"><tr>";	
      $salida .=  "<td align=\"left\" class=\"normal_10N\" width='90%'>TOTAL</td>";				
      $salida .=  "<td align=\"left\" class=\"normal_10N\" width='10%'>$ &nbsp;".$suma."</td>";
      $salida .= "</tr>	</table><br>\n";
      return $salida;
		}
			}
?>