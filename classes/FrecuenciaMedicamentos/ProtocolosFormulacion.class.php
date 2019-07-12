<?php
  /**
  *
  */
  class ProtocolosFormulacion
  {
    /**
    * Constructor de la clase
    */
    function ProtocolosFormulacion(){}
    /**
    *
    */
    function Main()
    {
      $request = $_REQUEST;
      
      $action['volver'] = "javascript:window.close()";
      $ptl = $this->ObtenereProtocoloMedicamento($request['codigo_medicamento']);
      
      if($request['ingreso'])
        $dias = $this->ObtenereFechasSuministro($request['codigo_medicamento'],$request['ingreso']);
      
      $html  = ReturnHeader('Buscador');
      $html .= ReturnBody()."<br>\n";
      $html .= $this->FormaMensaje($action,$ptl,$dias);
      
      return $html;
    }
    /**
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		*
		* @param array $action vector que continen los link de la aplicacion
    * @param string $mensaje Cadena con el texto del mensaje a mostrar 
    *         en pantalla
    *
		* @return string
		*/
		function FormaMensaje($action,$datos,$dias,$tmx = "100")
		{
      
      $mensaje = str_replace("\\n","<br>",$datos['protocolo']);
      $suministro = str_replace("\\n","<br>",$datos['suministro_texto']);
      $html  = ThemeAbrirTabla('INFORMACION');
      if($dias['dias'])
      {
        $html .= "<div style=\"text-align:center\" class=\"label2_error\">\n";
        $html .= "  <b >DIAS DE SUMISTRO: <blink>".$dias['dias']."<blink></b> \n";
        $html .= "</div>\n";
			}
      $html .= "<table border=\"0\" width=\"".$tmx."%\" align=\"center\" class=\"modulo_table_list\" >\n";
			$html .= "	<tr class=\"formulacion_table_list\">\n";
			$html .= "	  <td colspan=\"2\">PROTOCOLOS DE FORMULACION</td>\n";
			$html .= "	</tr>\n";				
      $html .= "	<tr class=\"formulacion_table_list\">\n";
			$html .= "	  <td >MEDICAMENTO</td>\n";
			$html .= "	  <td class=\"modulo_list_oscuro\" align=\"left\">".$datos['descripcion']."</td>\n";
			$html .= "	</tr>\n";			
      $html .= "	<tr class=\"formulacion_table_list\">\n";
			$html .= "	  <td colspan=\"2\">SUMINISTRO</td>\n";
			$html .= "	</tr>\n";     
      $html .= "	<tr class=\"modulo_list_claro\">\n";
			$html .= "	  <td colspan=\"2\" align=\"justify\" class=\"normal_10AN\">\n".$suministro."\n</td>\n";
			$html .= "	</tr>\n";
      $html .= "	<tr class=\"formulacion_table_list\">\n";
			$html .= "	  <td colspan=\"2\">PROTOCOLO</td>\n";
			$html .= "	</tr>\n";        
      $html .= "	<tr class=\"modulo_list_claro\">\n";
			$html .= "	  <td colspan=\"2\" align=\"justify\" class=\"normal_10AN\">\n".$mensaje."\n</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "<table width=\"100%\" align=\"center\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "			</form>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
    /**
    * Funcion donde se obtiene el protocolo de formulacion para el medicamente
    * solicitado
    *
    * @param string $codigo Codigo del medicamento
    *
    * @return mixed
    */
    function ObtenereProtocoloMedicamento($codigo)
    {
      IncludeClass("ConexionBD");
      $cxn = new ConexionBD();
      
      $sql  = "SELECT MP.protocolo, ";
      $sql .= "       ME.descripcion, ";
      $sql .= "       MP.suministro_texto ";
      $sql .= "FROM   inventarios_productos ME, ";
      $sql .= "       medicamentos_protocolos MP ";
      $sql .= "WHERE  ME.codigo_producto = '".$codigo."' ";
      $sql .= "AND    ME.codigo_producto = MP.codigo_medicamento ";
      
      $datos = array();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        $datos['protocolo'] = "HA OCURRIDO UN ERROR: <br>".$cxn->mensajeDeError;
        return $datos;
      }
      
      if (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      
			return $datos;
    }
    /**
    * Funcion donde se obtienen los dias de suministro del medicamento
    *
    * @param string $codigo Codigo del medicamento
    *
    * @return mixed
    */
    function ObtenereFechasSuministro($codigo,$ingreso)
    {     
      $sql  = "SELECT date_part('days',B.fecha_suministro - A.fecha_suministro) + 1 AS dias ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  MIN(HS.fecha_realizado) AS fecha_suministro  ";
      $sql .= "         FROM    hc_formulacion_medicamentos HF,  ";
      $sql .= "                 hc_formulacion_suministro_medicamentos HS  ";
      $sql .= "         WHERE  HF.codigo_producto = '".$codigo."'  ";
      $sql .= "         AND    HS.num_reg_formulacion = HF.num_reg_formulacion  ";
      $sql .= "         AND    HF.ingreso = ".$ingreso." ";
      $sql .= "         AND    HS.ingreso = ".$ingreso." ";
      $sql .= "       ) A, ";
      $sql .= "       ( ";
      $sql .= "         SELECT MAX(HS.fecha_realizado) AS fecha_suministro  ";
      $sql .= "         FROM   hc_formulacion_medicamentos HF, "; 
      $sql .= "                hc_formulacion_suministro_medicamentos HS  ";
      $sql .= "         WHERE  HF.codigo_producto = '".$codigo."'  ";
      $sql .= "         AND    HS.num_reg_formulacion = HF.num_reg_formulacion  ";
      $sql .= "         AND    HF.ingreso = ".$ingreso." ";
      $sql .= "         AND    HS.ingreso = ".$ingreso." ";
      $sql .= "       ) B ";
      
      IncludeClass("ConexionBD");
      $cxn = new ConexionBD();
      
      $datos = array();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
        return false;
      
      if (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      
			return $datos;
    }
  }
  $VISTA='HTML';
	$_ROOT='../../';
	include $_ROOT.'includes/enviroment.inc.php';
	
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	IncludeFile($fileName);
	
	$prt = new ProtocolosFormulacion();
	echo $prt->Main();
?>