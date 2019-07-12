<?php
	/**
	* $Id: vencimientos.report.php,v 1.1 2010/04/09 19:50:04 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('AutoCarga');
	class reporte_estados_usuarios_report 
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
	  function reporte_estados_usuarios_report($datos=array())
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
			$titulo .= "<b $est >PACIENTES MARCADOS<br>";
			
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
           
  			$detl = $nc->ObtenerPacientesEstados($this->datos);
        
     //   print_r($detl);
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";

      if(!empty($detl))
      {
  			$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
  			 $html .= "		<tr class=\"formulacion_table_list\" >\n";
  			$html .= "			<td width=\"10%\" class=\"label\" >TIPO ID</td>\n";
        $html .= "			<td width=\"15%\" class=\"label\">ID</td>\n";
        $html .= "			<td width=\"20%\" class=\"label\">NOMBRES</td>\n";
        $html .= "			<td width=\"20%\" class=\"label\">APELLIDOS</td>\n";
        $html .= "			<td width=\"20%\" class=\"label\">DIRECCION</td>\n";
        $html .= "			<td width=\"10%\" class=\"label\">TELEFONO</td>\n";
        $html .= "			<td width=\"15%\" class=\"label\">ESTADO</td>\n";

  			$html .= "		</tr>\n";
  			
  			foreach($detl as $k1 => $dtl)
        {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
         
          $html .= "		<tr ".$clase." onmouseout=mOut(this,\"".$bck."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
  				$html .= "			<td >".$dtl['tipo_id_paciente']."</td>\n";
          $html .= "			<td >".$dtl['paciente_id']."</td>\n";
          $html .= "			<td >".$dtl['primer_nombre']." ".$dtl['segundo_nombre']."</td>\n";
          $html .= "			<td >".$dtl['primer_apellido']." ".$dtl['segundo_apellido']."</td>\n";
          $html .= "			<td >".$dtl['residencia_direccion']."</td>\n";
          $html .= "			<td >".$dtl['residencia_telefono']."</td>\n";
          $html .= "			<td ><b>".$dtl['descripcion']."</b></td>\n";
          $html .= "		</tr>\n";
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