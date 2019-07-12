<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: DocumentosMovimientos.report.php,v 1.1 2011/05/19 22:19:10 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase reporte: DocumentosMovimientos_report
  * Clase encargada de la creacion de un reporte en html
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class DocumentosMovimientos_report 
	{ 
		/**
    * @var $datos
    * Vector de datos o parametros para generar el reporte
		*/
    var $datos;		
 		/**
    * @var string $title
		*/
		var $title       = '';
 		/**
    * @var string $author
		*/
		var $author      = '';
 		/**
    * @var string $sizepage
		*/
		var $sizepage    = 'leter';
 		/**
    * @var string $Orientation
		*/
		var $Orientation = '';
 		/**
    * @var boolean $grayScale
		*/
    var $grayScale = false;
 		/**
    * @var array $headers
		*/
		var $headers = array();
 		/**
    * @var array $footers
		*/
		var $footers = array();
		/**
    * Constuctor de la clase - recibe el vector de datos
    *
    * @param array $datos Arreglo de datos pasados por referencia
    *
    * @return boolean
    */
		function DocumentosMovimientos_report($datos=array())
		{
			$this->datos=$datos;
			return true;
		}
		/**
    * Metodo donde se obtiene el membrete que se le dara al reporte
    *
    * @return array
    */
		function GetMembrete()
		{			
			$mmb = array(
              'file'=>false,
              'datos_membrete' => 
                array (
                  'titulo'=>"",
                  'subtitulo'=>' ',
                  'logo'=>'logocliente.png',
                  'align'=>'left'
                      )
                  );
			return $mmb;
		}
		/**
    * Metodo donde se crea el cuerpo del reporte
    *
    * @return string
    */
    function CrearReporte()
		{
      IncludeClass("ConexionBD");
      IncludeClass("CierreBodegas","","app","Inv_Movimientos_Admin");
      $crb = new CierreBodegas();
	  /*$crb->debug = true;*/
		/*print_r($this->datos);*/
      $documentos = $crb->ObtenerInformacionCierreDocumentos(trim($this->datos['empresa_id']),trim($this->datos['usuario']));
      
      foreach($documentos as $key => $docs)
      {
        $flag= true;
        foreach($docs as $k1 => $dtl)
        {
          if($flag)
          {
            $html .= "<table class=\"normal_10\" align=\"center\" width=\"100%\" border=\"1\" rules=\"all\">\n";
            $html .= "  <tr class=\"label\">\n";
            $html .= "    <td width=\"15%\">BODEGA:</td>\n";
            $html .= "    <td width=\"45%\">".$key." ".$dtl['bodega_descripcion']."</td>\n";
            $html .= "    <td width=\"15%\">PERIODO:</td>";
            $html .= "    <td width=\"25%\">".$dtl['lapso_cerrado']."</td>\n";
            $html .= "  </tr>\n";
            $html .= "  <tr>\n";
            $html .= "    <td colspan=\"4\">\n";
            $html .= "      <table width=\"100%\" border=\"1\" rules=\"all\">\n";
            $html .= "        <tr class=\"label\" align=\"center\">\n";
            $html .= "          <td width=\"15%\">Nº DOCUMENTO</td>\n";
            $html .= "          <td width=\"60%\">DESCRIPCION</td>\n";
            $html .= "          <td width=\"25%\">TOTAL COSTO</td>\n";
            $html .= "        </tr>\n";
            $flag = false;
          }
          $html .= "        <tr>\n";
          $html .= "          <td >".$dtl['prefijo']." ".$dtl['numero']."</td>\n";
          $html .= "          <td >".$dtl['descripcion']."</td>\n";
          $html .= "          <td align=\"right\">".FormatoValor($dtl['total_costo'])."</td>\n";
          $html .= "        </tr>\n";
        }
        $html .= "      </table>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table><br>\n";
      }
      return $html;
		}
	}
?>