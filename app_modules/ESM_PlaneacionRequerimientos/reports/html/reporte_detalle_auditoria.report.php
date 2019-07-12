<?php
	/**
	* $Id: reporte_detalle_auditoria.report.php,v 1.1 2010/04/08 20:36:35 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('ClaseUtil');
  IncludeClass("ListaReportes","classes","app","ReportesInventariosGral");
	class reporte_detalle_auditoria_report 
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
	  function reporte_detalle_auditoria_report($datos=array())
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
			$titulo .= "<b $est >REPORTE DE AUDITORIA SELECCIONADA<br>";
			
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
      
      
      
			$detl = $nc->ObtenerListadoDetalleLogAuditoria($this->datos['auditoria_general_id']);
			
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
      //print_r($detl);
      if(!empty($detl))
      {
  			$html .= "	<table width=\"70%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
  			$html .= "		<tr class=\"label\">\n";
  			$html .= "			<td align=\"center\" colspan=\"2\">DETALLE DE CAMBIOS DE DATOS POR TABLAS</td>\n";
  			$html .= "		</tr>\n";
        $html .= "		<tr class=\"label\">\n";
  			$html .= "			<td class=\"label\" >TABLA: </td><td>".$detl[0]['table_name']." </td>\n";
  			$html .= "		</tr>\n";
        $html .= "		<tr class=\"label\">\n";
  			$html .= "			<td >ACCION: </td><td>".$detl[0]['descripcion']." </td>\n";
  			$html .= "		</tr>\n";
        $html .= "		<tr class=\"label\">\n";
  			$html .= "			<td >NOVEDAD INDICADOR: </td><td>".$detl[0]['indicador']." </td>\n";
  			$html .= "		</tr>\n";
        $html .= "		<tr class=\"label\">\n";
  			$html .= "			<td >PUNTAJE NOVEDAD: </td><td>".$detl[0]['puntaje']." </td>\n";
  			$html .= "		</tr>\n";
        $html .= "		<tr class=\"label\">\n";
  			$html .= "			<td >USUARIO: </td><td>".$detl[0]['nombre']." </td>\n";
  			$html .= "		</tr>\n";
        $html .= "		<tr class=\"label\">\n";
  			$html .= "			<td >FECHA: </td><td>".$detl[0]['fecha_registro']." </td>\n";
  			$html .= "		</tr>\n";
  			$html .= "	</table><br>";
        
        $html .= "	<table width=\"80%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
        $html .= "		<tr class=\"label\">\n";
  			$html .= "			<td align=\"center\" colspan=\"2\">CAMPOS DE LA TABLA</td>\n";
  			$html .= "		</tr>\n";
        for($i=1;$i<13;$i++)
        {
  				if($detl[0]['campo_'.$i]!="")
          {
          $html .= "		<tr class=\"normal_10\" align=\"center\">\n";
          $html .= "			<td colspan=\"2\" ><u><b>".$detl[0]['campo_'.$i]."</b></u></td>\n";
  				$html .= "		</tr>\n";
          $html .= "		<tr>\n";
          $html .= "			<td width=\"50%\"><b>Antiguo Valor: </b>(".$detl[0]['antiguo_valor_'.$i].")</td>\n";
          $html .= "			<td width=\"50%\"><b>Nuevo Valor: </b>(".$detl[0]['nuevo_valor_'.$i].")</td>\n";
  				
  				$html .= "		</tr>\n";
          }
  			}
  			$html .= "	</table><br><br><br>\n";
			}
			$usuario = $nc->ObtenerInformacionUsuario(UserGetUID());
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