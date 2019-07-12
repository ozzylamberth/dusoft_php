<?php
	/**
	* $Id: vencimientos.report.php,v 1.1 2010/04/09 19:50:04 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('AutoCarga');
	class usuarios_cod_barras_report 
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
	  function usuarios_cod_barras_report($datos=array())
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
			$titulo .= "<b $est >USUARIOS DEL SISTEMA QUE <u>NO</u> USARON COMO METODO DE BUSQUEDA EL CODIGO DE BARRAS<br>";
			
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
      
     // print_r($this->datos);
      
      $this->datos['buscador']['fecha_inicio']=$this->datos['fecha_inicio'];
      $this->datos['buscador']['fecha_final']=$this->datos['fecha_final'];
      
  			$detl = $nc->ObtenerListadoUsuarioCodBarras($this->datos['empresa'],$this->datos['buscador'],0,0);

			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";

      if(!empty($detl))
      {
  			$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
  			 $html .= "		<tr class=\"formulacion_table_list\" >\n";
  			$html .= "			<td width=\"20%\"><b>USUARIO</b></td>\n";
  			$html .= "			<td width=\"30%\"><b>NOMBRE USUARIO</b></td>\n";
  			$html .= "			<td width=\"30%\"><b>EMPRESA</b></td>\n";
  			$html .= "		</tr>\n";
  			
  			foreach($detl as $k1 => $dtl)
        {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
         
          $html .= "		<tr ".$clase." onmouseout=mOut(this,\"".$bck."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
  				$html .= "			<td >".$dtl['usuario']."</td>\n";
  				$html .= "			<td >".$dtl['nombre']."</td>\n";
  				$html .= "			<td >".$dtl['razon_social']."</td>\n";
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