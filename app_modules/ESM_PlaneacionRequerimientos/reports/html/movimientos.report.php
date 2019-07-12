<?php
	/**
	* $Id: movimientos.report.php,v 1.1 2010/04/08 20:36:35 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('ClaseUtil');
  IncludeClass("ListaReportes","classes","app","ReportesInventariosGral");
	class movimientos_report 
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
	  function movimientos_report($datos=array())
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
			$titulo .= "<b $est >PRODUCTOS SIN MOVIMIENTO<br>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$nc = new ListaReportes();
			$cl = new ClaseUtil();
      
			$detl = $nc->ObtenerListadoProductosSinMovimiento($this->datos['empresa_id'],$this->datos,0,0);
			
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";

      if(!empty($detl))
      {
  			$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
  			$html .= "		<tr class=\"label\">\n";
  			$html .= "			<td align=\"center\" width=\"10%\">CODIGO</td>\n";
  			$html .= "			<td align=\"center\" width=\"30%\">DESCRIPCION</td>\n";
  			$html .= "			<td align=\"center\" width=\"15%\">MOLECULA</td>\n";
  			$html .= "			<td align=\"center\" width=\"15%\">LABORATORIO</td>\n";
  			$html .= "			<td align=\"center\" width=\"5%\" >TIPO</td>\n";
  			$html .= "			<td align=\"center\" width=\"10%\">FECHA MOVIMIENTO</td>\n";
  			$html .= "			<td align=\"center\" width=\"10%\">EXISTENCIA ACTUAL</td>\n";
  			$html .= "		</tr>\n";
  			
  			foreach($detl as $key => $dtl)
  			{
          $tipo_producto = "";
          if($dtl['sw_insumos'] == '1')
            $tipo_producto = "INS";
          else if($dtl['sw_medicamento'] == '1')
            $tipo_producto = "MED";
            
  				$html .= "		<tr class=\"normal_10\">\n";
  				$html .= "			<td >".$dtl['codigo_producto']."</td>\n";
  				$html .= "			<td >".($cl->NombreProducto($dtl,$this->datos['empresa_id']))."</td>\n";
  				$html .= "			<td >".$dtl['molecula']."</td>\n";
  				$html .= "			<td >".$dtl['laboratorio']."</td>\n";
  				$html .= "			<td align=\"center\" class=\"label\">".$tipo_producto."</td>\n";
  				$html .= "			<td >".$dtl['fecha_movimiento']."</td>\n";
  				$html .= "			<td align=\"right\">".$dtl['existencia']."</td>\n";
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