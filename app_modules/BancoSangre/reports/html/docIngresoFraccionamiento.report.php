<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: docIngresoFraccionamiento.report.php,v 1.1 2009/04/06 17:10:48 manuel Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  **/
  /**
  * Clase Reporte: docIngresoFraccionamiento_report 
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  **/
  
  class docIngresoFraccionamiento_report
  {
    //VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
    var $datos;
    
    //PARAMETROS PARA LA CONFIGURACION DEL REPORTE
    var $title        = '';
    var $autor        = '';
    var $sizepage     = 'leter';
    var $orientation  = '';
    var $grayScale    = false;
    var $headers      = array();
    var $footers      = array();
    /**
    * Constuctor de la clase
    *
    * @param array $datos
    * 
    * @return boolean
    **/
    function docIngresoFraccionamiento_report($datos=array())
    {
      $this->datos=$datos;
      return true;
    }
    /**
    * Funcion que coloca el membrete del reporte
    *
    * @return array
    **/
    function GetMembrete()
    {
      $estilo = "style=\"font-family: sans_serif, Verdana, helvetica, Arial;font-size:14px\"";
      $titulo = "<b>REPORTE REGISTRO FRACCIONAMIENTO DE SANGRE";
      
      $Membrete = array('file'=>false, 'datos_membrete'=>array('titulo'=>$titulo, 'subtilulo'=>' ', 'logo'=>'logocliente.png', 'align'=>'left'));
      return $Membrete;
    }
    /**
    * 
    *
    * @return string $html retorna la cadena con el codigo html de la pagina
    **/
    function CrearReporte()
    {
      IncludeClass('ConexionBD');
      IncludeClass('BancoSangreSQL', '', 'app', 'BancoSangre');
      $mdl = new BancoSangreSQL();
      
      
      $militar = $mdl->ConsultarMilitar($this->datos['noId'], $this->datos['tipoId']);
      $datos_d = $mdl->ConsultarDonante($this->datos['noId'], $this->datos['tipoId'], $militar);
      $detalle_fs = $mdl->ConsultarDetFracSang($this->datos['det_frac']);
      $estado_dc = $mdl->ConsultarEstadoDC($this->datos['noId'], $this->datos['tipoId']);
      
      $tipificacion_d = $mdl->ConsultarTipificacion($this->datos['cod_don']);
      
      $fe = explode("-",$datos_d[0]['fecha_registro']);
      if(sizeof($fe)==3) 
      {
        $fMes=$fe[1];
        $fDia=$fe[2];
      }
      $html .= "  <table border=\"1\" align=\"center\" width=\"70%\" cellpading=\"0\" cellspacing=\"0\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\">DATOS DEL PACIENTE\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td width=\"10%\">Cod. Donante:\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"13%\">".$fMes.$fDia."-".$datos_d[0]['codigo_donante']."\n"; 
      $html .= "      </td>\n";
      $html .= "      <td width=\"10%\">Identificacion:\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"37%\" colspan=\"3\">".$datos_d[0]['tipo_id_donante']." - ".$this->datos['noId']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if($estado_dc[0]['estado_donante_id']!="")
      {
        $html .= "  <tr class=\"label\">\n";
        if($estado_dc[0]['desc_cau_donante']=="")
        {
          $html .= "    <td>Estado Donante:\n";      
          $html .= "    </td>\n";        
          $html .= "    <td colspan=\"5\">".$estado_dc[0]['desc_est_donante']."\n";
          $html .= "    </td>\n";
        }else{
          $html .= "    <td>Estado Donante:\n";      
          $html .= "    </td>\n";        
          $html .= "    <td colspan=\"2\">".$estado_dc[0]['desc_est_donante']."\n";
          $html .= "    </td>\n";
          $html .= "    <td>Causas:\n";      
          $html .= "    </td>\n";
          $html .= "    <td colspan=\"2\">".$estado_dc[0]['desc_cau_donante']."\n";
          $html .= "    </td>\n";
        }
        $html .= "  </tr>\n";              
      }else{
        $html .= "  <tr class=\"label\">\n";
        $html .= "    <td>Estado Donante:\n";
        $html .= "    </td>\n";
        $html .= "    <td colspan=\"5\">PENDIENTE POR REALIZAR EL REGISTRO DE DONACIONES\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td>Nombre:\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"5\">".$datos_d[0]['primer_nombre']." ". $datos_d[0]['segundo_nombre']." ".$datos_d[0]['primer_apellido']." ".$datos_d[0]['segundo_apellido']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $fn = explode('-',$datos_d[0]['fecha_nacimiento']);
      if(sizeof($fn)==3)
      {
        $fNac = $fn[2]."/".$fn[1]."/".$fn[0];
      }
      $html .= "    <tr class=\"label\">\n";
      if($datos_d[0]['desc_tipo_donante']!="Convenio")
      {
        $html .= "    <td>Tipo de Donador:\n";
        $html .= "    </td>\n";
        $html .= "    <td colspan=\"5\">".$datos_d[0]['desc_tipo_donante']."\n";
        $html .= "    </td>\n";
      }else{
        $html .= "    <td>Tipo de Donador:\n";
        $html .= "    </td>\n";
        $html .= "    <td colspan=\"2\">".$datos_d[0]['desc_tipo_donante']."\n";
        $html .= "    </td>\n";
        $html .= "    <td>Convenio:\n";
        $html .= "    </td>\n";
        $html .= "    <td colspan=\"2\">".$datos_d[0]['desc_convenio']."&nbsp\n";
        $html .= "    </td>\n";
      }
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td width=\"10%\">Edad:\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"13%\">".$datos_d[0]['edad']."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"10%\">Sexo:\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"13%\">".$datos_d[0]['desc_sexo']."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"10%\">Estado Civil:\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"13%\">".$datos_d[0]['desc_est_civil']."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      if($tipificacion_d['grupo_sanguineo'])
      {
        $html .= "  <tr class=\"label\">\n";
        $html .= "    <td width=\"10%\">Grupo Sanguineo:\n";
        $html .= "    </td>\n";
        $html .= "    <td width=\"13%\">".$tipificacion_d['grupo_sanguineo']."\n";
        $html .= "    </td>\n";
        $html .= "    <td width=\"10%\">Factor RH:\n";
        $html .= "    </td>\n";
        $html .= "    <td width=\"13%\">".$tipificacion_d['rh_gs']."\n";
        $html .= "    </td>\n";
        $html .= "    <td width=\"10%\">Subgrupo RH-:\n";
        $html .= "    </td>\n";
        $html .= "    <td width=\"13%\">".$tipificacion_d['subgrupo_rh'].$tipificacion_d['rh_sg']."&nbsp\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";      
        $html .= "  <tr class=\"label\">\n";
        $html .= "    <td>Observaciones:\n";
        $html .= "    </td>\n";
        $html .= "    <td colspan=\"5\">".$tipificacion_d['observaciones']."&nbsp\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "  </table>\n";
      $html .= "<br>\n";
      $html .= "  <table border=\"1\" align=\"center\" width=\"70%\" cellpading=\"0\" cellspacing=\"0\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\">INFORMACION HEMOCOMPONENTES\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td>Leucorreducidos:\n";
      $html .= "      </td>\n";
      $html .= "      <td align=\"left\" colspan=\"5\">".$detalle_fs['leucorreducidos']."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td>Irradiados:\n";
      $html .= "      </td>\n";
      $html .= "      <td align=\"left\" colspan=\"5\">".$detalle_fs['irradiados']."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $fh = explode(" ", $detalle_fs['fecha_hora_frac']);
      $f = $fh[0];
      $h = $fh[1];
      
      $fe = explode("-", $f);
      $fExt = $fe[2]."/".$fe[1]."/".$fe[0];
      
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td width=\"10%\">Fecha Extraccion:\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"13%\">".$fExt."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"12%\">Fecha Fraccionam.:\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"11%\">".$fExt."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"10%\">Hora Fraccionam.:\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"13%\">".$h."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";

      $fc = explode("-", $detalle_fs['fecha_caducidad']);
      $fCad = $fc[2]."/".$fc[1]."/".$fc[0];
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td>Fecha Caducidad:\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"2\">".$fCad."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "      <td>Tipo de Producto:\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"2\">".$detalle_fs['desc_prod_frac']."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td>Cantidad:\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"2\">".($detalle_fs['cantidad']*1)."&nbsp\n";  
      $html .= "      </td>\n";
      $html .= "      <td>Responsable:\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"2\">&nbsp\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td>Observacion:\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"5\">".$detalle_fs['observacion']."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      return $html;
    }
  }
?>