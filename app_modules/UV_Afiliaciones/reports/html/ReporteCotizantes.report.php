<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ReporteCotizantes.report.php,v 1.2 2009/09/23 21:42:42 hugo Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
  /**
  * Clase Reporte: ReporteCotizantes 
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
  IncludeClass('AutoCarga');

  class ReporteCotizantes_report 
  { 
    /**
    * Vector de datos o parametros para generar el reporte
    *
    * @var array $datos
    */
    var $datos;
    //Parametros para la configuracion del reporte
    //No modificar por el momento - delen un tiempito para terminar el desarrollo
    var $title       = '';
    var $author      = '';
    var $sizepage    = 'leter';
    var $Orientation = '';
    var $grayScale   = false;
    var $headers     = array();
    var $footers     = array();
    /**
    * Constuctor de la clase - recibe el vector de datos - metodo privado no modificar
    * @param array $datos
    * @return boolean
    */
    function ReporteCotizantes_report($datos=array())
    {
      $this->datos=$datos;
      return true;
    }
    /**
    * Funcion que coloca el menbrete del reporte
    * @return array $Membrete
    *
    **/
    function GetMembrete()
    {
      $estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
      $titulo .= "<b $estilo>INFORMACION DE AFILIADOS AL SERVICIO EN SALUD <br> COTIZANTES Y SUS RESPECTIVO BENEFICIARIOS</b>";
  
      $Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
                        'subtitulo'=>"<b $estilo>UNIVERSIDAD DEL VALLE </b>",'logo'=>'logocliente.png','align'=>'left'));//logocliente.png
      return $Membrete;
    }
    /**
    * Funcion que retorna el html del reporte (lo que va dentro del tag <body>)
    *
    * @return string $html con la forma del reporte
    **/
    function CrearReporte()
    {
      $buscador = SessionGetVar("BuscadorAfiliados");
      $ca = AutoCarga::factory('ConsultarAfiliados','','app','UV_Afiliaciones');
      $datos = $ca->ObtenerDatosAfiliadosCotizante($buscador);
      
      //$html .= "<pre>".print_r($datos,true)."</pre>";
      
      foreach($datos['cotizantes'] as $key => $dtl)
      {
        $html .= "  <table width=\"100%\"  border=\"1\" cellpading=\"0\" cellspacing=\"0\" align=\"center\" rules=\"all\">\n";
        $html .= "    <tr class=\"label\" align=\"center\">\n";
        $html .= "      <td width=\"20%\" rowspan=\"2\">AFILIADO</td>\n";
        $html .= "      <td width=\"8%\"  rowspan=\"2\">F. NACIMIENTO.</td>\n";
        $html .= "      <td width=\"12%\" rowspan=\"2\">DIRECCION</td>\n";
        $html .= "      <td width=\"14%\" colspan=\"2\">TELEFONO</td>\n";
        $html .= "      <td width=\"13%\" rowspan=\"2\">ESTAMENTO</td>\n";
        $html .= "      <td width=\"13%\" rowspan=\"2\">ESTADO</td>\n";
        $html .= "      <td width=\"4%\"  rowspan=\"2\">COP</td>\n";
        $html .= "      <td width=\"%\"  rowspan=\"2\">FIRMA</td>\n";
        $html .= "    </tr>\n";
        $html .= "	  <tr class=\"label\" align=\"center\">\n";
        $html .= "      <td >RES</td>\n";
        $html .= "      <td >MOVIL</td>\n";
        $html .= "    </tr>\n";      
        $html .= "      <tr class=\"normal_10\">\n";
        $html .= "        <td>".$dtl['afiliado_tipo_id']." ".$dtl['afiliado_id']."&nbsp;&nbsp;<b>".$dtl['nombre_afiliado']."</b></td>\n";
        $html .= "        <td>".$dtl['fecha_nacimiento']."</td>\n";
        $html .= "        <td>".$dtl['direccion_residencia']."<br>".$dtl['lugar']."</td>\n";
        $html .= "        <td width=\"7%\">".$dtl['telefono_residencia']."</td>\n";
        $html .= "        <td width=\"7%\">".$dtl['telefono_movil']."</td>\n";
        $html .= "        <td>".$dtl['descripcion_estamento']."</td>\n";
        $html .= "        <td>".$dtl['descripcion_estado']." - ".$dtl['descripcion_subestado']."</td>\n";
        $html .= "        <td>";
        if($dtl['cobrar_copagos'] == 't' )
          $html .= "          SI";
        else if($dtl['cobrar_copagos'] == 'f' )
          $html .= "          NO";
        else
          $html .= "        &nbsp;";
        $html .= "        </td>\n";
        $html .= "        <td>&nbsp;</td>\n";
        $html .= "      </tr>\n";

        if($dtl['estamento_siis'] == 'V')
        {
          $html .= "      <tr >\n";
          $html .= "        <td class=\"label\">ENTIDAD CONVENIO</td>\n";
          $html .= "        <td class=\"normal_10\" colspan=\"8\">".$dtl['nombre_tercero']."</td>\n";
          $html .= "      </tr>\n";
        }
        $html .= "</table>\n";
        if(!empty($datos['beneficiarios'][$dtl['afiliado_tipo_id']][$dtl['afiliado_id']]))
        {
          $html .= "  <table width=\"100%\" border=\"1\" cellpading=\"0\" cellspacing=\"0\" align=\"center\" rules=\"all\">\n";
          $html .= "	  <tr class=\"label\" align=\"center\">\n";
          $html .= "      <td rowspan=\"2\" width=\"20%\">NOMBRE BENEFICIARIO</td>\n";
          $html .= "      <td rowspan=\"2\" width=\"8%\">F. NACIMIENTO.</td>\n";
          $html .= "      <td rowspan=\"2\" width=\"12%\">DIRECCION</td>\n";
          $html .= "      <td colspan=\"2\" width=\"14%\">TELEFONO</td>\n";
          $html .= "      <td rowspan=\"2\" width=\"13%\">PARENTESCO</td>\n";
          $html .= "      <td rowspan=\"2\" width=\"17%\">ESTADO</td>\n";
          $html .= "      <td rowspan=\"2\" width=\"%\">FIRMA</td>\n";
          $html .= "    </tr>\n";
          $html .= "	  <tr class=\"label\" align=\"center\">\n";
          $html .= "      <td >RES</td>\n";
          $html .= "      <td >MOVIL</td>\n";
          $html .= "    </tr>\n";
          foreach($datos['beneficiarios'][$dtl['afiliado_tipo_id']][$dtl['afiliado_id']] as $keyI => $detalle)
          {
            $html .= "    <tr class=\"normal_10\">\n";
            $html .= "      <td>".$detalle['afiliado_tipo_id']." ".$detalle['afiliado_id']."&nbsp;&nbsp;<b>".strtoupper($detalle['nombre_afiliado'])."</b></td>\n";
            $html .= "      <td>".$detalle['fecha_nacimiento']."</td>\n";
            $html .= "      <td>".$detalle['direccion_residencia']."<br>".$detalle['lugar']."</td>\n";
            $html .= "      <td width=\"7%\">".trim($detalle['telefono_residencia'])."</td>\n";
            $html .= "      <td width=\"7%\">".trim($detalle['telefono_movil'])."</td>\n";
            $html .= "      <td>".$detalle['descripcion_parentesco']."</td>\n";
            $html .= "      <td>".$detalle['descripcion_estado']." - ".$detalle['descripcion_subestado']."</td>\n";
            $html .= "      <td>&nbsp;</td>\n";
            $html .= "    </tr>\n";
          }
          $html .= "  </table>\n";
        }
        $html .= "<br>";
      } 
    
      return $html; 
    }
	}
?>