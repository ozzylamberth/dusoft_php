<?php
	class ProtocoloAtencion
	{
		var $request = array();
		function ProtocoloAtencion()
		{
			$this->request = $_REQUEST;
		}
    /**
    *
    */
    function Principal()
    {
      switch($this->request['accion'])
      {
        case '1':
          return $this->MostrarProtocolos();
        break;
        default:
          return $this->ListarProtocolosAtencion();
        break;
      }
    }
		/*************************************************************************************
		*
		**************************************************************************************/
		function ListarProtocolosAtencion()
		{
			$bls = new ProtocoloAtencionSql();
      $protocolos = $bls->ObtenerProtocolos($this->request['paciente']);
			
			$html .= ReturnHeader('LISTADO DE PROTOCOLOS DE ATENCION');
      $html .= ReturnBody()."<br>\n";
			$html .= ThemeAbrirTabla("LISTADO DE PROTOCOLOS DE ATENCION");			

      if(!empty($protocolos))
      {
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend>PROTOCOLOS</legend>\n";
        $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "	  <tr class=\"formulacion_table_list\">\n";
				$html .= "			<td width=\"5%\">ID</td>\n";
				$html .= "			<td width=\"%\">DESCRIPCION</td>\n";
				$html .= "			<td width=\"5%\"></td>\n";
				$html .= "		</tr>\n";

        $est = "modulo_list_claro";
        $bck = "#CCCCCC";
				foreach($protocolos as $key => $dtl)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "		  <td >".$dtl['protocolo_atencion_id']."</td>\n";
					$html .= "		  <td >".$dtl['descripcion_protocolo']."</td>\n";
					
          $url = "ProtocoloAtencion.class.php?accion=1&paciente[tipo_id_paciente]=".$this->request['paciente']['tipo_id_paciente']."&paciente[paciente_id]=".$this->request['paciente']['paciente_id']."&archivo=".$dtl['nombre_protocolo'];
          
          $html .= "		  <td align=\"center\">\n";
          $html .= "		    <a href=\"".$url."\" class=\"label_error\">\n";
          $html .= "          <img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\">\n";
          $html .= "        </a>\n";
          $html .= "		  </td>\n";
				}
				$html .= "	  </table>\n";
				$html .= "</fieldset><br>\n";        
      }
      else
      {
        $html .= "  <center>\n";
        $html .= "    <label class=\"label_error\">NO EXISTEN PROTOCOLOS DE ATENCION RELACIONADOS A LA EDAD DEL PACIENTE</label>\n";
        $html .= "  </center>\n";
      }
      
			$html .= "	<table width=\"80%\" align=\"center\">\n";
			$html .= "		<tr align=\"center\">\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"button\" name=\"cerrar\" value=\"Cerrar\" class=\"input-submit\" onclick=\"window.close()\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= ThemeCerrarTabla();
			$html .= "</body>\n";
			$html .= "</html>\n";
			return $html;
		}
    /**
    *
    */
    function MostrarProtocolos()
    {
      $ex = explode(".",$this->request['archivo']);
      
      $ruta_archivo = "../../protocolos/ProtocolosAtencion/".$this->request['archivo'];
      $lines = file($ruta_archivo);
      
      $html .= ReturnHeader('LISTADO DE PROTOCOLOS DE ATENCION');
      $html .= ReturnBody()."<br>\n";
			$html .= ThemeAbrirTabla(strtoupper($ex[0]));			
      $html .= "<table width=\"98%\" align=\"center\" class=\"label\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      
      foreach ($lines as $line_num => $line)
      {
        if($ex[1] != "html")
          $html .= str_replace("\n","<br>",$line);
        else
          $html .= $line;
      }
      
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";

      $url = "ProtocoloAtencion.class.php?paciente[tipo_id_paciente]=".$this->request['paciente']['tipo_id_paciente']."&paciente[paciente_id]=".$this->request['paciente']['paciente_id'];

      $html .= "<form name=\"forma\" action=\"".$url."\" method=\"post\">\n";
      $html .= "	<table width=\"80%\" align=\"center\">\n";
			$html .= "		<tr align=\"center\">\n";
			$html .= "			<td>\n";
			$html .= "				<input type=\"submit\" name=\"volver\" value=\"Volver\" class=\"input-submit\" >\n";
			$html .= "			</td>\n";			
      $html .= "			<td>\n";
			$html .= "				<input type=\"button\" name=\"cerrar\" value=\"Cerrar\" class=\"input-submit\" onclick=\"window.close()\">\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
			$html .= ThemeCerrarTabla();
      
      return $html;
    }
	}
	
	$VISTA='HTML';
	$_ROOT='../../';
	include $_ROOT.'includes/enviroment.inc.php';
	include $_ROOT.'classes/ProtocoloAtencion/ProtocoloAtencionSql.class.php';

	$fileName = "themes/$VISTA/".GetTheme()."/module_theme.php";
	IncludeFile($fileName);

	$bsc = new ProtocoloAtencion();
	echo $bsc->Principal();
?>