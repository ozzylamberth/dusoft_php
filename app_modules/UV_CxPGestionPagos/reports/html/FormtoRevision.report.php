<?php
	/**
	* $Id: FormtoRevision.report.php,v 1.1 2008/10/28 13:12:54 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
	class FormtoRevision_report 
	{ 
		//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
		var $datos;
		var $menu;
		
		//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
		//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
		var $title       = '';
		var $author      = '';
		var $sizepage    = 'leter';
		var $Orientation = '';
		var $grayScale   = false;
		var $headers     = array();
		var $footers     = array();
		
		//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	  function FormtoRevision_report($datos=array())
	  {
			$this->datos=$datos;
	    return true;
	  }
		/**
    *
    */
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= "<table width=\"100%\" class=\"label\">\n";
			$titulo .= "  <tr>\n";
			$titulo .= "    <td width=\"70%\">VICERRECTORIA DE BIENESTAR UNIVERSITARIO<td>\n";
			$titulo .= "    <td align=\"right\">FORMATO DE REVISION<td>\n";
      $titulo .= "  </tr>\n";
      $titulo .= "  <tr>\n";
			$titulo .= "    <td colspan=\"2\">SERVICIO DE SALUD<td>\n";
      $titulo .= "  </tr>\n";
      $titulo .= "  <tr>\n";
			$titulo .= "    <td colspan=\"2\">OFICINA REVISIÓN CUENTAS<td>\n";
			$titulo .= "  </tr>\n";
			$titulo .= "</table><br><br><br>\n";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'Logo_Univalle.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
      IncludeClass('ConexionBD');
      IncludeClass('AutoCarga');
      
      $gp = AutoCarga::factory('GestionPagos','','app','UV_CxPGestionPagos');
      
      $facturas = $gp->ObtenerFacturasPreOrdenPago($this->datos['empresa'],$this->datos['cxp_orden_pago_id']);
      $detalle = $gp->ObtenerDetallePreOrdenPago($this->datos['empresa'],$this->datos['cxp_orden_pago_id']);
      $tercero = $gp->ObtenerProveedores($detalle);
      $cuentas = $gp->ObtenerDetalleOrdenGasto($this->datos['empresa'],$this->datos['cxp_orden_pago_id']);
      $pacientes = $gp->ObtenerPacientes($this->datos['empresa'],$this->datos['cxp_orden_pago_id']);
      $ordenes = $gp->ObtenerOrdenesServicio($this->datos['empresa'],$this->datos['cxp_orden_pago_id']);
      
      $estamentos = array();
      foreach($pacientes as $k => $d1)
      {
        foreach($d1 as $e => $d2)
        {
          $estamentos[$d2['descripcion_estamento']]['valor'] += $ordenes[$k][$e]['valor']; 
          $estamentos[$d2['descripcion_estamento']]['cantidad'] += $ordenes[$k][$e]['cantidad']; 
        }
      }

      $total = $total_iva = 0;
      
      $html .= "<table width=\"100%\" class=\"normal_10\" border=\"1\" align =\"center\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
      $html .= "  <tr class=\"label\">\n";
      $html .= "    <td width=\"50%\" colspan=\"2\">NOMBRE DEL BENEFICIARIO</td>\n";
      $html .= "    <td align=\"center\" width=\"25%\">DOCUMENTO DE IDENTIDAD</td>\n";
      $html .= "    <td align=\"center\" width=\"25%\">ORDEN DE GASTO Nº</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"2\">".$tercero['nombre_tercero']."</td>\n";
      $html .= "    <td>".$tercero['tipo_id_tercero']." ".$tercero['tercero_id']."</td>\n";
      $html .= "    <td>".$detalle['cxp_orden_pago_id']."&nbsp;</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"label\">\n";
      $html .= "    <td align=\"left\" colspan=\"2\">DIRECCION</td>\n";
      $html .= "    <td align=\"left\" colspan=\"2\">TELÉFONO</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"2\">".$tercero['direccion']."&nbsp;</td>\n";
      $html .= "    <td colspan=\"2\">".$tercero['telefono']."&nbsp;</td>\n";
      $html .= "  </tr>\n";    
      $html .= "  <tr class=\"label\">\n";
      $html .= "    <td align=\"left\" colspan=\"2\">PERIODO DEL SERVICIO</td>\n";
      $html .= "    <td align=\"left\" colspan=\"2\">ESPECIALIDAD</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"2\">DE ".$detalle['fecha_inicial']." A ".$detalle['fecha_final']."</td>\n";
      $html .= "    <td colspan=\"2\">".$detalle['descripcion_especialidad']."&nbsp;</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"label\">\n";
      $html .= "    <td colspan=\"4\">FACTURAS Nos.</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td colspan=\"4\" align=\"justify\">\n";
      foreach($facturas as $key => $dtl)
      {
        $html .= (($key == 0)? $dtl['prefijo_factura']." ".$dtl['numero_factura']: " ,".$dtl['prefijo_factura']." ".$dtl['numero_factura']);
        $total += $dtl['valor_total'];
        $total_iva += $dtl['valor_iva'];
      }
      
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"label\">\n";
      $html .= "    <td align=\"center\" width=\"25%\">FECHA RECEPCION</td>\n";
      $html .= "    <td align=\"center\" width=\"25%\">FECHA DE ELABORACION</td>\n";
      $html .= "    <td align=\"center\" width=\"25%\">NA</td>\n";
      $html .= "    <td align=\"center\" width=\"25%\">RD</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td>".$detalle['fecha_radicacion']."</td>\n";
      $html .= "    <td>".$detalle['fecha_elab_preorden']."</td>\n";
      $html .= "    <td>&nbsp;</td>\n";
      $html .= "    <td>".$detalle['num_orden_gasto']."&nbsp;</td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
            
      if(!empty($cuentas))
      {
        $html .= "<table width=\"100%\" class=\"normal_10\" border=\"1\" align =\"center\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
        $html .= "  <tr class=\"label\">\n";
        $html .= "    <td align=\"center\" width=\"25%\">CUENTA</td>\n";
        $html .= "    <td align=\"center\" width=\"12%\">VALOR</td>\n";
        $html .= "    <td align=\"center\" width=\"13%\">PORCENTAJE</td>\n";
        $html .= "    <td align=\"center\" width=\"50%\">&nbsp;</td>\n";
        $html .= "  </tr>\n";
      
        foreach($cuentas as $key => $detl)
        {
          $html .= "  <tr>\n";
          $html .= "    <td class=\"label\">".$key."</td>\n";
          $html .= "    <td align=\"right\">".formatoValor($detl['valor_total'])."</td>\n";
          $html .= "    <td align=\"right\">".number_format(($detl['valor_total'] * 100/$total),2,',','.')." %</td>\n";
          $html .= "    <td >&nbsp;</td>\n";
          $html .= "  </tr>\n";
        }
        $html .= "</table>\n";
      }      
      
      if(!empty($estamentos))
      {
        $html .= "<br>\n";
        $html .= "<table width=\"100%\" class=\"normal_10\" border=\"1\" align =\"center\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n";
        $html .= "  <tr class=\"label\">\n";
        $html .= "    <td width=\"25%\">ESTAMENTO</td>\n";
        $html .= "    <td width=\"15%\">No ORDENES</td>\n";
        $html .= "    <td width=\"15%\">VALOR INICIAL</td>\n";
        $html .= "    <td width=\"15%\">NOTA CREDITO</td>\n";
        $html .= "    <td width=\"15%\">DESCUENTO</td>\n";
        $html .= "    <td width=\"15%\">VALOR TOTAL</td>\n";
        $html .= "  </tr>\n";
        
        $tot = $cant = 0;
        foreach($estamentos as $key => $detal)
        {
          $html .= "  <tr>\n";
          $html .= "    <td class=\"label\">".$key."</td>\n";
          $html .= "    <td align=\"right\">".$detal['cantidad']."</td>\n";
          $html .= "    <td align=\"right\">$".formatoValor($detal['valor'])."</td>\n";
          $html .= "    <td align=\"right\">&nbsp;</td>\n";
          $html .= "    <td align=\"right\">&nbsp;</td>\n";
          $html .= "    <td align=\"right\">$".formatoValor($detal['valor'])."</td>\n";
          $html .= "  </tr>\n";
          
          $cant += $detal['cantidad'];
          $tot += $detal['valor'];
        }
        $html .= "  <tr>\n";
        $html .= "    <td class=\"label\">GRAN TOTAL</td>\n";
        $html .= "    <td align=\"right\">".$cant."</td>\n";
        $html .= "    <td align=\"right\">$".formatoValor($tot)."</td>\n";
        $html .= "    <td align=\"right\">&nbsp;</td>\n";
        $html .= "    <td align=\"right\">&nbsp;</td>\n";
        $html .= "    <td align=\"right\">$".formatoValor($tot)."</td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
      }
      $html .= "<br><br><br><br>\n";
      $html .= "<table align =\"left\">\n";
      $html .= "  <tr class=\"label\">\n";
      $html .= "    <td>FIRMA REVISOR</td>\n";
      $html .= "    <td>____________________________________________</td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      return $html;
		}
	}
?>