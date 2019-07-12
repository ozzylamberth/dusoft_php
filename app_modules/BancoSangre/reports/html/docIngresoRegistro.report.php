<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: docIngresoRegistro.report.php,v 1.1 2009/04/06 10:41:26 manuel Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  **/
  /**
  * Clase Reporte: registro_report 
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  **/
  
  class docIngresoRegistro_report
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
    function docIngresoRegistro_report($datos=array())
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
      $titulo = "<b>REPORTE REGISTRO DONACION";
      
      $Membrete = array('file'=>false, 'datos_membrete'=>array('titulo'=>$titulo, 'subtilulo'=>' ', 'logo'=>'logocliente.png', 'align'=>'left'));
      return $Membrete;
    }
    /**
    * Funcion en donde se consulta y se muestra la informacion del reporte del registro
    * de la donacion
    * @return string $html retorna la cadena con el codigo html de la pagina
    **/
    function CrearReporte()
    {
      IncludeClass('ConexionBD');
      IncludeClass('BancoSangreSQL', '', 'app', 'BancoSangre');
      $mdl = new BancoSangreSQL();
      
      $militar = $mdl->ConsultarMilitar($this->datos['noId'], $this->datos['tipoId']);
      $datos_d = $mdl->ConsultarDonante($this->datos['noId'], $this->datos['tipoId'], $militar);
      $detalle_rd = $mdl->ConsultarDetRegDonacion($this->datos['detRegDon']);
      $lugar_naci = $mdl->ConsultarLugarNacimiento($this->datos['noId'], $this->datos['tipoId']);
      $lugar_domi = $mdl->ConsultarLugarDomicilio($this->datos['noId'], $this->datos['tipoId']);
      //$estado_dc = $mdl->ConsultarEstadoDC($this->datos['noId'], $this->datos['tipoId']);
      
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
      $html .= "      <td width=\"16.6%\">Cod. Donante:\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"13.6%\">".$fMes.$fDia."-".$datos_d[0]['codigo_donante']."\n"; 
      $html .= "      </td>\n";
      $html .= "      <td width=\"16.6%\">Identificacion:\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"19.6%\">".$datos_d[0]['tipo_id_donante']." - ".$this->datos['noId']."\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"13.6%\">Estado Donante:\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"19.6%\">".$detalle_rd['desc_estado']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
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
      $html .= "      <td>Fecha Nacimiento:\n";
      $html .= "      </td>\n";
      $html .= "      <td>".$fNac."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "      <td>Lugar de Nacimiento:\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"3\">".$lugar_naci[0]['desc_naci_pais']." - ".$lugar_naci[0]['desc_naci_dpto']." - ".$lugar_naci[0]['desc_naci_mpio']."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td>Edad:\n";
      $html .= "      </td>\n";
      $html .= "      <td>".$datos_d[0]['edad']."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "      <td>Sexo:\n";
      $html .= "      </td>\n";
      $html .= "      <td>".$datos_d[0]['desc_sexo']."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "      <td>Estado Civil:\n";
      $html .= "      </td>\n";
      $html .= "      <td>".$datos_d[0]['desc_est_civil']."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td>Dir. Domicilio:\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"5\">".$datos_d[0]['dir_domicilio']." ".$lugar_domi[0]['desc_domi_pais']." - ".$lugar_domi[0]['desc_domi_dpto']." - ".$lugar_domi[0]['desc_domi_mpio']."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td>Tel. Domicilio:\n";
      $html .= "      </td>\n";
      $html .= "      <td>".$datos_d[0]['tel_domicilio']."&nbsp\n";
      $html .= "      </td>\n";
      if($datos_d[0]['no_celular'])
      {
        $html .= "    <td>No. Celular:\n";
        $html .= "    </td>\n";
        $html .= "    <td colspan=\"3\">".$datos_d[0]['no_celular']."&nbsp\n";
        $html .= "    </td>\n";
      }else{
        $html .= "    <td colspan=\"4\">&nbsp\n";
        $html .= "    </td>\n";
      }
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td>Ocupacion:\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"5\">".$datos_d[0]['desc_ocupacion']."&nbsp\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      if($datos_d[0]['dir_trabajo'] && $datos_d[0]['tel_trabajo'])
      {
        $html .= "      <td>Dir. Trabajo:\n";
        $html .= "      </td>\n";
        $html .= "      <td colspan=\"3\">".$datos_d[0]['dir_trabajo']."&nbsp\n";
        $html .= "      </td>\n";
      }else if($datos_d[0]['dir_trabajo'] && !$datos_d[0]['tel_trabajo'])
      {
        $html .= "      <td>Dir. Trabajo:\n";
        $html .= "      </td>\n";
        $html .= "      <td colspan=\"5\">".$datos_d[0]['dir_trabajo']."&nbsp\n";
        $html .= "      </td>\n";
      }
      if($datos_d[0]['tel_trabajo'] && $datos_d[0]['dir_trabajo'])
      {
        $html .= "      <td>Tel. Trabajo\n";
        $html .= "      </td>\n";
        $html .= "      <td>".$datos_d[0]['tel_trabajo']."&nbsp\n";
        $html .= "      </td>\n";
      }else if($datos_d[0]['tel_trabajo'] && !$datos_d[0]['dir_trabajo'])
      {
        $html .= "      <td>Tel. Trabajo\n";
        $html .= "      </td>\n";
        $html .= "      <td colspan=\"5\">".$datos_d[0]['tel_trabajo']."&nbsp\n";
        $html .= "      </td>\n";
      }
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      if($detalle_rd['desc_causa'] && $detalle_rd['desc_estado']!="Diferido")
      {
        $html .= "      <td>Causa Estado:\n";      
        $html .= "      </td>\n";
        $html .= "      <td colspan=\"5\">".$detalle_rd['desc_causa']."&nbsp\n";
        $html .= "      </td>\n";
      }else if($detalle_rd['desc_causa'] && $detalle_rd['desc_estado']=="Diferido")
      {
        $html .= "      <td>Causa Estado:\n";      
        $html .= "      </td>\n";
        $html .= "      <td colspan=\"2\">".$detalle_rd['desc_causa']."&nbsp\n";
        $html .= "      </td>\n";
        $html .= "      <td>Estado Diferido:\n";
        $html .= "      </td>\n";
        $html .= "      <td colspan=\"2\">".$detalle_rd['tiempo_estado']."\n";
        $html .= "      </td>\n";
      }else if(!$detalle_rd['desc_causa'] && $detalle_rd['desc_estado']=="Diferido")
      {
        $html .= "      <td>Estado Diferido:\n";
        $html .= "      </td>\n";
        $html .= "      <td colspan=\"5\">".$detalle_rd['tiempo_estado']."\n";
        $html .= "      </td>\n";
      }
      $html .= "    </tr>\n";
      if($detalle_rd['observaciones_estado'])
      {
        $html .= "  <tr class=\"label\">\n";
        $html .= "    <td>Observaciones Estado:\n";
        $html .= "    </td>\n";
        $html .= "    <td colspan=\"5\">".$detalle_rd['observaciones_estado']."&nbsp\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      if($datos_d[0]['desc_tipo_fuerza'])
      {
        $html .= "  <tr class=\"label\">\n";
        $html .= "      <td>Fuerza:\n";
        $html .= "      </td>\n";
        $html .= "      <td>".$datos_d[0]['desc_tipo_fuerza']."&nbsp\n";
        $html .= "      </td>\n";
        $html .= "      <td>Categoria:\n";
        $html .= "      </td>\n";
        $html .= "      <td>".$datos_d[0]['categoria']."&nbsp\n";
        $html .= "      </td>\n";
        $html .= "      <td>Grado:\n";
        $html .= "      </td>\n";
        $html .= "      <td>".$datos_d[0]['desc_tipo_grado']."&nbsp\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"label\">\n";
        $html .= "      <td>Clasif. Financiera:\n";
        $html .= "      </td>\n";
        $html .= "      <td colspan=\"5\">".$datos_d[0]['desc_clasi_finan']."&nbsp\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
      }
      if($datos_d[0]['email'])
      {
        $html .= "  <tr class=\"label\">\n";
        $html .= "      <td>e-mail:\n";
        $html .= "      </td>\n";
        $html .= "      <td colspan=\"5\">".$datos_d[0]['email']."&nbsp\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "  </table>\n";
      $html .= "<br>\n";
      $html .= "  <table align=\"center\" border=\"1\" width=\"70%\" cellpading= \"0\" cellspacing=\"0\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td colspan=\"6\" align=\"center\">INFORMACION REGISTRO DE DONACION\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td width=\"10%\">No. Registro Donacion:\n";
      $html .= "      </td>\n";
      $html .= "      <td colspan=\"2\" width=\"20%\">".$this->datos['detRegDon']."\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"10%\">Fecha Registro:\n";
      $html .= "      </td>\n";
      $frd = explode('-', $detalle_rd['fecha_registro']);
      if(sizeof($frd)==3)
      {
        $fRegDon = $frd[2]."/".$frd[1]."/".$frd[0];
      }
      $html .= "      <td colspan=\"2\" width=\"20%\">".$fRegDon."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      if($datos_d[0]['desc_tipo_donante']!="Convenio")
      {
        $html .= "      <td>Tipo de Donador:\n";
        $html .= "      </td>\n";
        $html .= "      <td colspan=\"5\">".$datos_d[0]['desc_tipo_donante']."\n";
        $html .= "      </td>\n";
      }else{
        $html .= "      <td>Tipo de Donador:\n";
        $html .= "      </td>\n";
        $html .= "      <td colspan=\"2\">".$datos_d[0]['desc_tipo_donante']."\n";
        $html .= "      </td>\n";
        $html .= "      <td>Convenio:\n";
        $html .= "      </td>\n";
        $html .= "      <td colspan=\"2\">".$datos_d[0]['desc_convenio']."&nbsp\n";
        $html .= "      </td>\n";
      }
      $html .= "    </tr>\n";
      $html .= "</table>\n";
      
      if($tipificacion_d['grupo_sanguineo'])
      {
        $html .= "  <br>\n";
        $html .= "  <table border=\"1\" align=\"center\" width=\"70%\" cellpading=\"0\" cellspacing=\"0\" class=\"modulo_table_list\">\n";
        $html .= "    <tr class=\"label\">\n";
        $html .= "      <td align=\"center\" colspan=\"6\">TIPIFICACION\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"label\">\n";
        $html .= "      <td width=\"10%\">Grupo Sanguineo:\n";
        $html .= "      </td>\n";
        $html .= "      <td width=\"13%\">".$tipificacion_d['grupo_sanguineo']."\n";
        $html .= "      </td>\n";
        $html .= "      <td width=\"10%\">Factor RH:\n";
        $html .= "      </td>\n";
        $html .= "      <td width=\"13%\">".$tipificacion_d['rh_gs']."\n";
        $html .= "      </td>\n";
        $html .= "      <td width=\"10%\">Subgrupo RH-:\n";
        $html .= "      </td>\n";
        $html .= "      <td width=\"13%\">".$tipificacion_d['subgrupo_rh'].$tipificacion_d['rh_sg']."&nbsp\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"label\">\n";
        $html .= "      <td>Observaciones:\n";
        $html .= "      </td>\n";
        $html .= "      <td colspan=\"5\">".$tipificacion_d['observaciones']."&nbsp\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
        $html .= "  </table>\n";
      }
      $html .= "  <br>\n";
      $tipo_bolsa = $mdl->ConsultarTipoBolsaD($this->datos['cod_don']);
      $html .= "  <table align=\"center\" border=\"1\" width=\"70%\" cellpading=\"0\" cellspacing=\"0\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td align=\"center\" colspan=\"4\">REGISTRO DE BOLSAS\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      if($tipo_bolsa['descripcion']=="OTROS")
      {
        $html .= "      <td width=\"10%\">Tipo de Bolsa:\n";
        $html .= "      </td>\n";
        $html .= "      <td width=\"25%\">".$tipo_bolsa['descripcion']."\n";
        $html .= "      </td>\n";
        $html .= "      <td width=\"10%\">Otros:\n";
        $html .= "      </td>\n";
        $html .= "      <td width=\"25%\">".$tipo_bolsa['otra_bolsa']."&nbsp\n";
        $html .= "      </td>\n";
      }else{
        $html .= "      <td width=\"5%\">Tipo de Bolsa:\n";
        $html .= "      </td>\n";
        $html .= "      <td width=\"25%\" colspan=\"3\">".$tipo_bolsa['descripcion']."\n";
        $html .= "      </td>\n";
      }
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <br>\n";
      $html .= "  <table align=\"center\" border=\"1\" width=\"70%\" cellpading=\"0\" cellspacing=\"0\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td align=\"center\" colspan=\"6\">OTROS DATOS DE INTERES\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td align=\"left\" width=\"20%\">Aspecto general del donante sano\n";
      $html .= "      </td>\n";
      $html .= "      <td width=\"50%\">".$detalle_rd['aspecto_general']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td align=\"left\">Brazos sin lesion de agujas\n";
      $html .= "      </td>\n";
      $html .= "      <td>".$detalle_rd['brazos_lesion']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td align=\"left\">Actividad peligrosa post donacion\n";
      $html .= "      </td>\n";
      $html .= "      <td>".$detalle_rd['actividad_peligrosa']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td align=\"left\">Flebotomia del brazo\n";
      $html .= "      </td>\n";
      $html .= "      <td>".$detalle_rd['flebotomia_brazo']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "      <td align=\"left\">Puncion\n";
      $html .= "      </td>\n";
      $html .= "      <td>".$detalle_rd['puncion']."\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      return $html;
    }
  }
?>