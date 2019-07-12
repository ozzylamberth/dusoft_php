<?php
  /**
	* $Id: pagares.report.php,v 1.1 2009/02/12 20:14:13 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
 	* @author Hugo Freddy Manrique
	*/
  IncludeClass('ConexionBD');
  IncludeClass('AutoCarga');
  class pagares_report
  {
    /**
    * Vector de datos o parametros para generar el reporte
    * 
    * @var array
    */
		var $datos = array();
		var $title       = '';
		var $author      = '';
		var $sizepage    = 'leter';
		var $Orientation = '';
		var $grayScale   = false;
		var $headers     = array();
		var $footers     = array();
		/**
    * Constuctor de la clase - recibe el vector de datos - 
    * Metodo privado no modificar
    */
	  function pagares_report($datos=array())
	  {
			$this->datos=$datos;
	  }
    /**
    * @return array
    */
    function GetMembrete()
		{
			$est = "style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$ttl = "<b ".$est.">REPORTE PAGARES</b>";
			
			$mbr = array( 'file'=> false,
                    'datos_membrete'=> array(
                      'titulo'=> $ttl,
                      'subtitulo'=> ' ',
                      'logo'=> 'logocliente.png',
                      'align'=> 'left'
                      )
                  );
			return $mbr;
		}
    /**
    * Funcion que retorna el html del reporte (lo que va dentro del tag <BODY>)
    *
    * @return String
    */
		function CrearReporte()
	  {
      $pgr = AutoCarga::factory('CarteraPagares','classes','app','Cartera');
      $lista = $pgr->ObtenerListaPagares($this->datos,null,1);

      $html .= "<table border=\"1\" cellpading=\"0\" cellspacing=\"0\" align=\"left\" class=\"normal_10\">\n";
      $html .= "  <tr class=\"label\" align=\"center\">\n";
      $html .= "	  <td width=\"7%\" >Nº DOC</td>\n";
      $html .= "		<td width=\"8%\" >FECHA</td>\n";
      $html .= "		<td width=\"8%\" >VENCIMIENTO</td>\n";
      $html .= "		<td width=\"18%\">FORMA PAGO</td>\n";
      $html .= "		<td width=\"%\" colspan=\"2\">CLIENTE</td>\n";
      $html .= "		<td width=\"9%\" >VALOR</td>\n";
      $html .= "	</tr>\n";
      
      $sl = 0;
      foreach($lista as $key => $dtl )
      {
        $sl += $dtl['valor'];
        $html .= "	<tr class=\"normal_10\">\n";
        $html .= "	  <td align=\"left\"  >".$dtl['prefijo']." ".$dtl['numero']."</td>\n";
        $html .= "		<td align=\"center\">".$dtl['fecha_registro']."</td>\n";
        $html .= "		<td align=\"center\">".$dtl['vencimiento']."</td>\n";
        $html .= "		<td >".$dtl['formapago']."</td>\n";
        $html .= "		<td width=\"15%\">".$dtl['tipo_id_paciente']." ".$dtl['paciente_id']."</td>\n";
        $html .= "		<td align=\"justify\">".$dtl['primer_apellido']." ".$dtl['segundo_apellido']." ".$dtl['primer_nombre']." ".$dtl['segundo_nombre']."</td>\n";
        $html .= "		<td align=\"right\" >$".formatoValor($dtl['valor'])."</td>\n";
        $html .= "	</tr>\n";
      }
      $html .= "  <tr class=\"label\">\n";
      $html .= "    <td colspan=\"6\" align=\"right\">TOTAL PAGARES&nbsp</td>\n";
      $html .= "    <td align=\"right\">$".formatoValor($sl)."</td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      
      return $html;
    }
  }
?>