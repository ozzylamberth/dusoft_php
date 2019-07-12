<?php
	/**
	* $Id: reporte_general_auditoria.report.php,v 1.1 2010/04/08 20:36:35 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('ClaseUtil');
  IncludeClass("ListaReportes","classes","app","ESM_PlaneacionRequerimientos");
	class reporte_general_auditoria_report 
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
	  function reporte_general_auditoria_report($datos=array())
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
			$titulo .= "<b $est >PLANEACION DE REQUERIMIENTOS - DISTRIBUCION/SUMINISTRO<br>";
			
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
      
			//$detl = $nc->ObtenerListadoLogAuditoria($this->datos['empresa_id'],$this->datos,0,0);
			$detl = $nc->Obtener_Reporte($this->datos['empresa_id'],$this->datos,0,0);
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";

      if(!empty($detl))
      {
  			$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
        $html .= "		<tr class=\"label\" >\n";
        $html .= "			<td width=\"35%\">PRODUCTO</td>\n";
        $html .= "			<td width=\"10%\">CANTIDAD</td>\n";
        $html .= "			<td width=\"15%\">PROMEDIO MENSUAL</td>\n";
        /*$html .= "			<td width=\"15%\">FECHA DE REGISTRO</td>\n";
        $html .= "			<td width=\"5%\" >USUARIO</td>\n";*/
        $html .= "		</tr>\n";
  			
  			foreach($detl as $key => $dtl)
  			{
                     
  				$html .= "		<tr class=\"normal_10\">\n";
  				$html .= "			<td >".$dtl['producto']."</td>\n";
  				$html .= "			<td >".FormatoValor($dtl['total'],1)."</td>\n";
  				$html .= "			<td >".FormatoValor($dtl['prom'],3)."</td>\n";
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