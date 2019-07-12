<?php
	/**
	* $Id: vencimientos.report.php,v 1.1 2010/04/09 19:50:04 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('AutoCarga');
	class vencimientos_report 
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
		
		//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	  function vencimientos_report($datos=array())
	  {
			$this->datos=$datos;			
	    return true;
	  }
		/**
    *
    */
		function GetMembrete()
		{
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:10pt\"";
			$titulo .= "<b $est >PRODUCTOS PROXIMOS A VENCER<br>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$nc = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");
			$cl = AutoCarga::factory('ClaseUtil');
      
			$detl = $nc->ObtenerListadoProductosVencimiento($this->datos['empresa_id'],$this->datos,$this->datos['dias_vencimiento'],0,0);

			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";

      if(!empty($detl))
      {
  			$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
  			$html .= "		<tr class=\"label\">\n";
  			$html .= "			<td align=\"center\" width=\"10%\">CODIGO</td>\n";
  			$html .= "			<td align=\"center\" width=\"30%\">DESCRIPCION</td>\n";
  			$html .= "			<td align=\"center\" width=\"15%\">MOLECULA</td>\n";
  			$html .= "			<td align=\"center\" width=\"15%\">LABORATORIO</td>\n";
  			$html .= "			<td align=\"center\" width=\"10%\">FECHA VENCIMIENTO</td>\n";
  			$html .= "			<td align=\"center\" width=\"10%\">LOTE</td>\n";
  			$html .= "			<td align=\"center\" width=\"5%\" >EXIS</td>\n";
  			$html .= "			<td align=\"center\" width=\"10%\">MENSAJE</td>\n";
  			$html .= "		</tr>\n";
  			
  			foreach($detl as $key => $dtl)
  			{          
          $color = "";
          if($cl->CompararFechas($dtl['fecha_vencimiento'],date("d/m/Y")) < 0)
            $color = "<label style=\"color:".$this->datos['colores']['VN']."\" ><b>VENCIDO</b></label>";
          else if($cl->CompararFechas($dtl['fecha_vencimiento'],$this->datos['fecha_proxima_vencimiento']) < 0)
            $color = "<label style=\"color:".$this->datos['colores']['PV']."\" ><b>EN PERIODO VENCIMIENTO</b></label>";
          
  				$html .= "		<tr class=\"normal_10\">\n";
  				$html .= "			<td >".$dtl['codigo_producto']."</td>\n";
  				$html .= "			<td >".($cl->NombreProducto($dtl,$this->datos['empresa_id']))."</td>\n";
  				$html .= "			<td >".$dtl['molecula']."</td>\n";
  				$html .= "			<td >".$dtl['laboratorio']."</td>\n";
  				$html .= "			<td align=\"center\" class=\"label\">".$tipo_producto."</td>\n";
  				$html .= "			<td align=\"center\">".$dtl['fecha_vencimiento']."</td>\n";
  				$html .= "			<td align=\"right\">".$dtl['existencia']."</td>\n";
  				$html .= "			<td align=\"center\">".$color."</td>\n";
  				$html .= "		</tr>\n";
  			}			
  			$html .= "	</table><br><br><br>\n";
			}
			$usuario = $nc->ObtenerInformacionUsuario($this->datos['usuario_id']);
			$html .= "	<br><table border='0' width=\"100%\">\n";
			$html .= "		<tr>\n";
      $html .= "			<td align=\"justify\" width=\"50%\">\n";
			$html .= "				<font size='1' face='arial'>\n";
			$html .= "					Imprimió:&nbsp;".$usuario['nombre']."\n";
			$html .= "				</font>\n";
			$html .= "			</td>\n";
			$html .= "			<td align=\"right\" width=\"50%\">\n";
			$html .= "				<font size='1' face='arial'>\n";
			$html .= "					Fecha Impresión :&nbsp;&nbsp;".date("d/m/Y - h:i a")."\n";
			$html .= "				</font>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
	    return $html;
		}
	}
?>