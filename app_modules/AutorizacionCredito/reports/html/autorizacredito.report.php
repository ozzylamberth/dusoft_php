<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: autorizacredito.report.php,v 1.1 2009/01/06 15:57:30 manuel Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */
  /**
  * Clase Reporte: creditos_report 
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Manuel Ruiz Fernandez
  */
  
  class autorizacredito_report
  {
    //VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
    var $datos;
    
    //PARAMETROS PARA LA CONFIGURACION DEL REPORTE
    var $title        = '';
    var $author       = '';
    var $sizepage     = 'leter';
    var $Orientation  = '';
    var $grayScale    = false;
    var $headers      = array();
    var $footers      = array();
    /**
    * Contructor de la clase
    * 
    * @param array $datos
    *
    * @return boolean
    */
    function autorizacredito_report($datos=array())
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
      $titulo = "<b>REPORTE AUTORIZACIONES DE CREDITOS";
      
      $Membrete = array('file'=>false, 'datos_membrete'=>array('titulo'=>$titulo, 'subtilulo'=>' ', 'logo'=>'logocliente.png', 'align'=>'left'));
      return $Membrete;
    }
    /**
    * Funcion en donde se consulta y se muestra la informacion del reporte
    * @return string $html retorna la cadena con el codigo html de la pagina
    */
    function CrearReporte()
    {
      IncludeClass('ConexionBD');
      IncludeClass('AutorizacionCreditoSQL','','app','AutorizacionCredito');      

      $mdl = new AutorizacionCreditoSQL();
      $report = $mdl->ReporteAutorizacionFecha($this->datos);
      
      //$mdl = AutoCarga::factory("AutorizacionCreditoSQL", "classes", "app", "AutorizacionCredito");
      //$report = $mdl->ReporteAutorizacionFecha($this->datos['fechaInicio'], $this->datos['fechaFinal'], $this->datos['oculto']);
      
      $html .= "  <table border=\"1\" width=\"80%\" align=\"center\" cellpading= \"0\" cellspacing=\"0\">\n";
      $html .= "    <tr class=\"label\" >\n";
      $html .= "      <td align=\"center\">IDENTIFICACION</td>\n";
      $html .= "      <td align=\"center\">NUMERO</td>\n";
      $html .= "      <td align=\"center\">NOMBRES</td>\n";
      $html .= "      <td align=\"center\">APELLIDOS</td>\n";
      $html .= "      <td align=\"center\">No. AUTORIZACION</td>\n";
      $html .= "      <td align=\"center\">VALOR CUENTA</td>\n";
      $html .= "      <td align=\"center\">AUTORIZADOR</td>\n";
      $html .= "      <td align=\"center\">FECHA AUTORIZACION</td>\n";
      $html .= "      <td align=\"center\">No. FACTURA</td>\n";
      $html .= "    </tr>\n";
            
      foreach($report as $indice => $valor)
      {
        $html .= "    <tr class=\"label\">\n";
        $html .= "      <td align=\"center\">".$valor['tipo_id_paciente']."</td>\n";
        $html .= "      <td align=\"center\">".$valor['paciente_id']."</td>\n";
        $html .= "      <td align=\"center\">".$valor['primer_nombre']." ".$valor['segundo_nombre']."</td>\n";
        $html .= "      <td align=\"center\">".$valor['primer_apellido']."  ".$valor['segundo_apellido']."</td>\n";
        $html .= "      <td align=\"center\">".$valor['autorizacion_cr_id']."</td>\n";
        $html .= "      <td align=\"center\">".$valor['total_cuenta']."</td>\n";
        $html .= "      <td align=\"center\">".$valor['nombre']."</td>\n";
        if($valor['fecha_registro'])
        {
          $f = explode('-',$valor['fecha_registro']);
          if(sizeof($f)==3) $fr = $f[2]."/".$f[1]."/".$f[0];
        }
        $html .= "      <td align=\"center\">".$fr."</td>\n";
        if($valor['factura_fiscal']=="")
          $valor['factura_fiscal']="-";
        $html .= "      <td align=\"center\">".$valor['factura_fiscal']."</td>\n";
        $html .= "    </tr>\n";
      }      
      $html .= "  </table>\n";
      
      return $html;
    }
  }
?> 
