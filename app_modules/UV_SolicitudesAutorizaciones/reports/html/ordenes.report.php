<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ordenes.report.php,v 1.5 2008/11/14 21:27:49 hugo Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
  /**
  * Clase Reporte: ordenes_report 
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.5 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
	class ordenes_report 
	{ 
		//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
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
		
		/**
    * Contructor de la clase
    * 
    * @param array $datos
    *
    * @return boolean
    */
    function ordenes_report($datos=array())
		{
			$this->datos=$datos;
			return true;
		}
    /**
    * Funcion que coloca el menbrete del reporte
    *
    * @return array
    **/
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$html,
							  'subtitulo'=>' ',
							  'logo'=>'',
							  'align'=>'left'));
			return $Membrete;
		}
		/**
    * Funcion que retorna el html del reporte (lo que va dentro del tag <body>)
		*
    * @return String
    */
    function CrearReporte()
		{
      IncludeClass('ConexionBD');
      IncludeClass('Ordenes','','app','UV_SolicitudesAutorizaciones');
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
		
      $ods = new Ordenes();
      
      $orden = $ods->ObtenerOrdenesServicio($this->datos['empresa_id'],$this->datos);
      $detalle = $ods->ObtenerOrdenesServicioDetalle($this->datos);

      $sty = " style=\"text-align:left;text-indent:8pt\" ";

			foreach($orden as $key => $proveedores)
      {
        foreach($proveedores as $key1 => $ordenes)
        {
          $estamento = $ods->ObtenerEstamento($ordenes);
          $plan = $ods->ObtenerCodigoPlanOrden($ordenes['eps_orden_servicio']);
          $f = explode("/",$ordenes['fecha_registro']);
          
          $html .= "<table width=\"100%\" border=\"1\" rules=\"none\" cellpading=\"8\" cellspacing=\"8\" bordercolor=\"#000000\">\n";
          $html .= "  <tr>\n";
          $html .= "    <td colspan=\"2\">\n";
    			$html .= "      <table width=\"100%\" class=\"label\">\n";
    			$html .= "        <tr>\n";
    			$html .= "          <td rowspan=\"3\"><img src=\"images/Logo_Univalle.png\" height=\"45\"></td>\n";
    			$html .= "          <td align=\"left\">UNIVERSIDAD DEL VALLE</td>\n";
    			$html .= "          <td align=\"right\">ORDEN DE SERVICIO</td>\n";
          $html .= "        </tr>\n";			
          $html .= "        <tr>\n";
    			$html .= "          <td width=\"70%\">VICERRECTORIA DE BIENESTAR UNIVERSITARIO</td>\n";
    			$html .= "          <td align=\"right\" rowspan=\"2\" style=\"color:#990000;font-size:10pt\" >Nº ".$ordenes['eps_orden_servicio']."</pre></td>\n";
          $html .= "        </tr>\n";      
          $html .= "        <tr>\n";
    			$html .= "          <td >SERVICIOS DE SALUD</td>\n";
          $html .= "        </tr>\n";
          $html .= "      </table>\n";        
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr>\n";
          $html .= "    <td colspan=\"2\">\n";
          $html .= "      <table width=\"100%\" border=\"1\" rules=\"all\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
          $html .= "        <tr class=\"normal_09\">\n";
          $html .= "          <td align=\"center\" width=\"13%\">T.O.</td>\n";
          $html .= "          <td align=\"center\" width=\"1%\">1</td>\n";
          $html .= "          <td align=\"center\" width=\"13%\">CONSULTA</td>\n";
          $html .= "          <td align=\"center\" width=\"1%\">2</td>\n";
          $html .= "          <td align=\"center\" width=\"14%\">LABORATORIO</td>\n";
          $html .= "          <td align=\"center\" width=\"1%\">3</td>\n";
          $html .= "          <td align=\"center\" width=\"14%\">IMAGENOLOGIA</td>\n";
          $html .= "          <td align=\"center\" width=\"1%\">4</td>\n";
          $html .= "          <td align=\"center\" width=\"14%\">INTERV. QUIRUR.</td>\n";
          $html .= "          <td align=\"center\" width=\"1%\">5</td>\n";
          $html .= "          <td align=\"center\" width=\"13%\">PROCEDIMIENTO</td>\n";
          $html .= "          <td align=\"center\" width=\"1%\">6</td>\n";
          $html .= "          <td align=\"center\" width=\"13%\">SER. HOSP</td>\n";
          $html .= "        </tr>\n";
          $html .= "      </table><br>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";   
          $html .= "  <tr>\n";
          $html .= "    <td colspan=\"2\">\n";
          $html .= "      <table width=\"100%\" border=\"1\" align =\"center\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
          $html .= "	      <tr class=\"label\">\n";
          $html .= "          <td width=\"15%\" align=\"center\" colspan=\"3\">FECHA DE EXPEDICION</td>\n";
          $html .= "          <td colspan=\"2\" width=\"50%\">MEDICO / ENTIDAD ADSCRITA</td>\n";
          $html .= "          <td colspan=\"2\" width=\"35%\">CODIGO ENTIIDAD</td>\n";
          $html .= "        </tr>\n";
          $html .= "	      <tr class=\"normal_10\">\n";
          $html .= "          <td rowspan=\"2\">\n";
          $html .= "            <table width=\"100%\">\n";
          $html .= "              <tr style=\"font-size:85%\">\n";
          $html .= "                <td valign=\"top\">Dia</td>\n";         
          $html .= "              </tr>\n";         
          $html .= "              <tr>\n";
          $html .= "                <td>".$f[0]."</td>\n";  
          $html .= "              </tr>\n";
          $html .= "            </table>\n";
          $html .= "          </td>\n";
          $html .= "          <td rowspan=\"2\">\n";
          $html .= "            <table width=\"100%\">\n";
          $html .= "              <tr style=\"font-size:85%\">\n";
          $html .= "                <td valign=\"top\">Mes</td>\n";         
          $html .= "              </tr>\n";         
          $html .= "              <tr>\n";
          $html .= "                <td>".$f[1]."</td>\n";  
          $html .= "              </tr>\n";
          $html .= "            </table>\n";
          $html .= "          </td>\n";
          $html .= "          <td rowspan=\"2\">\n";
          $html .= "            <table width=\"100%\">\n";
          $html .= "              <tr style=\"font-size:85%\">\n";
          $html .= "                <td valign=\"top\">Año</td>\n";         
          $html .= "              </tr>\n";         
          $html .= "              <tr>\n";
          $html .= "                <td>".$f[2]."</td>\n";  
          $html .= "              </tr>\n";
          $html .= "            </table>\n";
          $html .= "          </td>\n";
          $html .= "			    <td colspan=\"2\">".$ordenes['nombre_tercero']."</td>\n";
          $html .= "	        <td colspan=\"2\">".$ordenes['tipo_id_tercero']." ".$ordenes['tercero_id']."</td>\n";
          $html .= "		    </tr>\n";
          $html .= "	      <tr class=\"normal_10\">\n";
          $html .= "			    <td class=\"label\" width=\"10%\">DIRECCION</td>\n";
          $html .= "			    <td>".$ordenes['direccion']."&nbsp;</td>\n";
          $html .= "	        <td class=\"label\" width=\"10%\">TELEFONO</td>\n";
          $html .= "	        <td>".$ordenes['telefono']."&nbsp;</td>\n";
          $html .= "		    </tr>\n";
          $html .= "	      <tr class=\"label\">\n";
          $html .= "          <td colspan=\"5\">NOMBRE DEL PACIENTE</td>\n";
          $html .= "          <td colspan=\"2\">CODIGO DEL BENEFICIARIO</td>\n";
          $html .= "		    </tr>\n";
          $html .= "	      <tr class=\"normal_10\">\n";
          $html .= "			    <td colspan=\"5\">".trim($ordenes['primer_nombre']." ".$ordenes['segundo_nombre']." ".$ordenes['primer_apellido']." ".$ordenes['segundo_apellido'])."</td>\n";
          $html .= "	        <td colspan=\"2\">".$ordenes['tipo_id_paciente']." ".$ordenes['paciente_id']."</td>\n";
          $html .= "		    </tr>\n";
          $html .= "	    </table>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr>\n";
          $html .= "    <td colspan=\"2\">\n";
          $html .= "      <table width=\"100%\" height=\"25\">\n";
          $html .= "        <tr>\n";
          $html .= "          <td width=\"85%\">".$estamento['descripcion']."</td>\n";
          $html .= "          <td width=\"10%\" align=\"right\">COD. PLAN:</td>\n";
          $html .= "          <td width=\"5%\" >&nbsp;".$plan."</td>\n";
          $html .= "        </tr>\n";
          $html .= "      </table>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr>\n";
          $html .= "    <td colspan=\"2\">\n";
          $total1 = $total2 = 0;
          
          $html .= "      <table width=\"100%\" border=\"1\" align =\"center\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
          $html .= "        <tr class=\"label\" align=\"center\">\n";
          $html .= "	        <td width=\"10%\">CODIGO</td>\n";
          $html .= "		      <td >RELACION DE SERVICIOS</td>\n";
          $html .= "		      <td width=\"10%\">CANTIDAD</td>\n";
          $html .= "		      <td width=\"10%\">VALOR U</td>\n";
          $html .= "		      <td width=\"10%\">TOTAL</td>\n";
          $html .= "	      </tr>\n";        
          if(!empty($detalle[$key][$key1]['cargos']))
          {
            foreach($detalle[$key][$key1]['cargos'] as $kc => $dtl_cargos_qx)
            {
              if(!is_numeric($kc) && $kc != "")
              {
                $html .= "	      <tr>\n";
                $html .= "          <td colspan=\"5\" align=\"center\">".$kc."</td>\n";
                $html .= "        </tr>\n";
              }
              foreach($dtl_cargos_qx as $kcx => $dtl_cargos)
              {
                foreach($dtl_cargos as $kc1=> $dtl)
                {
                  $html .= "	      <tr>\n";
                  $html .= "          <td>".$dtl['cargo_base']."</td>\n";
                  $html .= "          <td align=\"justify\">".$dtl['descripcion_equivalencia']."</td>\n";
                  $html .= "		      <td>".$dtl['cantidad']."</td>\n";
                  $html .= "		      <td align=\"right\">$".formatoValor($dtl['valor'])."</td>\n";
                  $html .= "		      <td align=\"right\">$".formatoValor($dtl['valor'] * $dtl['cantidad'])."</td>\n";
                  $html .= "        </tr>\n";
                  $total1 += $dtl['valor'] * $dtl['cantidad'];
                  $total2 += $dtl['valor_cubierto'] * $dtl['cantidad'];
                }
              }
            }
          }
          
          if(!empty($detalle[$key][$key1]['medicamentos']))
          {           
            foreach($detalle[$key][$key1]['medicamentos'] as $kc => $dtl)
            {
              $html .= "	        <tr>\n";
              $html .= "            <td>".$dtl['codigo_producto']."</td>\n";
              $html .= "            <td align=\"justify\">".$dtl['descripcion_producto']."</td>\n";
              $html .= "		        <td>".$dtl['cantidad']."</td>\n";
              $html .= "		        <td align=\"right\">$".formatoValor($dtl['valor'])."</td>\n";
              $html .= "		        <td align=\"right\">$".formatoValor($dtl['valor']*$dtl['cantidad'])."</td>\n";
              $html .= "	        </tr>\n";
              $total1 += $dtl['valor'] * $dtl['cantidad'];
            }
          }
          
          if(!empty($detalle[$key][$key1]['conceptos']))
          {          
            foreach($detalle[$key][$key1]['conceptos'] as $kc => $dtl)
            {
              $html .= "	        <tr $est>\n";
              $html .= "            <td>".$dtl['tipo_concepto_id']."</td>\n";
              $html .= "            <td align=\"justify\">".$dtl['descripcion_concepto_adicional']."</td>\n";
              $html .= "		        <td>1</td>\n";
              $html .= "		        <td align=\"right\">$".formatoValor($dtl['valor'])."</td>\n";
              $html .= "		        <td align=\"right\">$".formatoValor($dtl['valor'])."</td>\n";
              $html .= "	        </tr>\n";
              $total1 += $dtl['valor'];
            }
          }
          $html .= "	    </table><br>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr>\n";
          $html .= "    <td colspan=\"2\">\n";        
          $html .= "      <table width=\"100%\" border=\"1\" rules=\"all\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
          $html .= "        <tr class=\"normal_10\" >\n";
          $html .= "          <td class=\"label\" width=\"17%\">VALOR TOTAL</td>\n";
          $html .= "          <td align=\"right\" width=\"16%\">$".formatoValor($total1)."</td>\n";
          $html .= "          <td class=\"label\" width=\"17%\">VALOR A CARGO DEL AFILIADO</td>\n";
          $html .= "          <td align=\"right\" width=\"16%\">$".formatoValor($total1 - $total2)."</td>\n";
          $html .= "          <td class=\"label\" width=\"18%\">VALOR TOTAL A PAGAR UNIVALLE</td>\n";
          $html .= "          <td align=\"right\" width=\"16%\">$".formatoValor($total2)."</td>\n";
          $html .= "        </tr>\n";
          $html .= "      </table>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr>\n";
          $html .= "    <td width=\"67%\" >\n";
          $html .= "      <table width=\"100%\" border=\"1\" rules=\"all\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
          $html .= "        <tr class=\"normal_09\" >\n";
          $html .= "          <td widtht=\"50%\" height=\"30\" valign=\"top\">&nbsp;MEDICO QUE SOLICITA</td>\n";
          $html .= "          <td widtht=\"50%\" height=\"30\" valign=\"top\">&nbsp;CODIGO DIAGNOSTICO</td>\n";
          $html .= "        </tr>\n";
          $html .= "      </table>\n";
          $html .= "    </td>\n";
          $html .= "    <td>\n";
          $html .= "      <center class=\"normal_09\">ADJUNTE A ESTA LA ORDEN MEDICA</center>\n";
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr class=\"normal_09\">\n";
          $html .= "    <td colspan=\"2\" align=\"center\">ORDEN SIN FIRMAR NO ES VALIDA PARA PAGO</td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr>\n";
          $html .= "    <td colspan=\"2\">\n";        
          $html .= "      <table width=\"100%\">\n";
          $html .= "        <tr>\n";
          $html .= "          <td width=\"40%\" align=\"left\">\n";
          $html .= "            <table >\n";
          $html .= "              <tr class=\"label\" valign=\"bottom\" >\n";
          $html .= "                <td height=\"50\">________________________________________</td>\n";
          $html .= "              </tr>\n";        
          $html .= "              <tr class=\"label\" >\n";
          $html .= "                <td align=\"center\">FIRMA Y SELLO AUTORIZADO</td>\n";
          $html .= "              </tr>\n";
          $html .= "              <tr class=\"label\" valign=\"bottom\" >\n";
          $html .= "                <td height=\"50\">________________________________________</td>\n";
          $html .= "              </tr>\n";        
          $html .= "              <tr class=\"label\" >\n";
          $html .= "                <td align=\"center\">FIRMA DEL PACIENTE</td>\n";
          $html .= "              </tr>\n";
          $html .= "            </table>\n";
          $html .= "          </td>\n";
          $html .= "          <td width=\"60%\" >\n";
          $html .= "            <table width=\"100%\" border=\"1\" rules=\"all\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
          $html .= "              <tr class=\"label\" >\n";
          $html .= "                <td colspan=\"2\" align=\"center\">ESPACIO EXCLUSIVO PARA LAS FIRMAS Y SELLOS DE REVISION</td>\n";
          $html .= "              </tr>\n";
          $html .= "              <tr class=\"normal_10\" >\n";
          $html .= "                <td widtht=\"50%\" height=\"70\" valign=\"top\">&nbsp;REVISOR SERVICIO DE SALUD</td>\n";
          $html .= "                <td widtht=\"50%\" height=\"70\" valign=\"top\">&nbsp;REVISOR AUDITORIA</td>\n";
          $html .= "              </tr>\n";
          $html .= "            </table>\n";
          $html .= "          </td>\n";
          $html .= "        </tr>\n";
          $html .= "      </table>\n";        
          $html .= "    </td>\n";
          $html .= "  </tr>\n";
          $html .= "  <tr>\n";
          $html .= "    <td colspan=\"2\">\n";
          $html .= "      <table>\n";
          $html .= "        <tr class=\"label\" >\n";
          $html .= "          <td height=\"20\">C.C. Nº __________________________________</td>\n";
          $html .= "        </tr>\n";
          $html .= "        <tr class=\"label\" >\n";
          $html .= "          <td height=\"20\">CODIGO _________________________________</td>\n";
          $html .= "        </tr>\n";
          $html .= "      </table>\n";
          $html .= "    </td>\n";
          $html .= "</table>\n";
          $html .= "<style type=\"text/css\">\n";
          $html .= "  p {page-break-before: always}\n";
          $html .= "</style>\n";          
          $html .= "<p class=\"salto de página\"></p>\n";

        }
      }
			return $html;
		}
	}
?>