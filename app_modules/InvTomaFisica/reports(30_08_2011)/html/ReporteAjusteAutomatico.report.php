<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: ReporteAjusteAutomatico.report.php
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
    
  /**
  * Clase Reporte: ReporteAjusteAutomatico
  * reporte con los datos de todos los productos Existencias vs Conteo1
  * @package IPSOFT-SIIS
  * @version 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  */

  IncludeClass("TomaFisicaSQL","","app","InvTomaFisica");
  class ReporteAjusteAutomatico_report
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
        
   /**
    * CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    * @param array $datos
    * @return boolean
    */
   function ReporteAjusteAutomatico_report($datos=array())
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
    $titulo .= "";//<b $estilo>REPORTE DE PRODUCTOS DE LA TOMA FISICA ".$this->datos['datos']['toma_fisica']."</b>";
    $Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
    'subtitulo'=>"",'logo'=>'','align'=>'left'));//
    return $Membrete;
  }

  /**
   * Funcion que crea el cuerpo del reporte
   * @return string $salida
   *
   **/
  function CrearReporte()
  {
     $consulta=new TomaFisicaSQL();
     $datox=$consulta->ProductosReporteAutomatico($this->datos['datos']['toma_fisica']);
     
     $ESTILO4="style=\"border-color:#000000;border-style:solid; border-width: thin;\"";

     if(!empty($datox))
     {
        $estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:12px\"";  
        $salida .= " <table width=\"80%\"  border='0' align=\"center\" >\n";
        $salida .= "  <tr class=\"normal_10AN\">\n";
        $salida .= "    <td width=\"100%\" align=\"left\">\n";
        $salida .= "      &nbsp;";
        $salida .= "    </td>\n";
        $salida .= "  </tr>\n";
        $salida .= " </table>\n";
        $salida .= "<br>\n";
        //$salida .= "<pre>".print_r($datox,true)."</pre>";
        $salida .= " <table width=\"80%\" border='0' cellspacing='0' align=\"center\" >\n";
        $salida .= "  <tr class=\"normal_10AN\">\n";
        $salida .= "   <td width=\"50%\" align=\"center\">\n";
        $salida .= "      <b $estilo>REPORTE DE AJUSTE AUTOMATICO ".$this->datos['datos']['toma_fisica']."</b>";
        $salida .= "   </td>\n";
        $salida .= "  </tr>\n";
        $salida .= " </table>\n";
        $salida .= "<br>\n";
        $salida .= " <table width=\"80%\" border='1' cellspacing='0' align=\"center\" >\n";
        $salida .= "  <tr>\n";
        $salida .= "   <td $ESTILO2 width=\"10%\" align=\"center\">\n";
        $salida .= "       CODIGO PRODUCTO";
        $salida .= "    </td>\n";
        $salida .= "    <td $ESTILO2 width=\"20%\" align=\"center\">\n";
        $salida .= "       DESCRIPCION";
        $salida .= "    </td>\n";
        $salida .= "    <td $ESTILO2 width=\"10%\" align=\"center\">\n";
        $salida .= "       LABORATORIO";
        $salida .= "     </td>\n";
        $salida .= "     <td $ESTILO2 width=\"5%\" align=\"center\">\n";
        $salida .= "        EXISTENCIA";
        $salida .= "     </td>\n";
        $salida .= "     <td $ESTILO2 width=\"5%\" align=\"center\">\n";
        $salida .= "        INGRESO";
        $salida .= "     </td>\n";
        $salida .= "     <td $ESTILO2 width=\"5%\" align=\"center\">\n";
        $salida .= "        EGRESO";
        $salida .= "     </td>\n";
        $salida .= "     <td $ESTILO2 width=\"5%\" align=\"center\">\n";
        $salida .= "        COSTO";
        $salida .= "     </td>\n";
        $salida .= "     <td $ESTILO2 width=\"10%\" align=\"center\">\n";
        $salida .= "        COSTO TOTAL PRODUCTO";
        $salida .= "     </td>\n";
        $salida .= "     <td $ESTILO2 width=\"10%\" align=\"center\">\n";
        $salida .= "        COSTO TOTAL AJUSTE";
        $salida .= "     </td>\n";
        $salida .= "   </tr>\n";
          
        for($i=0;  $i<count($datox);$i++)
        {
           $salida .= " <tr>\n";
           $salida .= "  <td align=\"center\" $ESTILO3>\n";
           $salida .= "    ".$datox[$i]['codigo_producto'];
           $salida .= "   </td>\n";
           $salida .= "   <td align=\"left\" $ESTILO3>\n";
           $salida .= "     ".$datox[$i]['descripcion_producto']."";
           $salida .= "   </td>\n";
           $salida .= "   <td $ESTILO3 align=\"center\">\n";
           $salida .= "      ".$datox[$i]['laboratorio'];
           $salida .= "   </td>\n";
           $salida .= "   <td $ESTILO3 align=\"center\">\n";
           $salida .= "      ".FormatoValor($datox[$i]['existencia'])."&nbsp;";
           $salida .= "    </td>\n";
           if(empty($datox[$i]['ingreso_menos_egreso']))
           {
              $salida .= "   <td $ESTILO3 align=\"center\">\n";
              $salida .= "      ".FormatoValor($datox[$i]['ingreso'])."&nbsp;";
              $salida .= "    </td>\n";
              $salida .= "  <td $ESTILO3 align=\"center\">\n";
              $salida .= "   "."&nbsp;";
              $salida .= "  </td>\n";
            }
           elseif(!empty($datox[$i]['ingreso_menos_egreso']))
           {
              $salida .= "  <td $ESTILO3 align=\"center\">\n";
              $salida .= "   "."&nbsp;";
              $salida .= "  </td>\n";
              $salida .= "   <td $ESTILO3 align=\"center\">\n";
              $salida .= "      ".FormatoValor($datox[$i]['ingreso_menos_egreso'])."&nbsp;";
              $salida .= "    </td>\n";
            }
            $salida .= " <td $ESTILO3 align=\"center\">\n";
            $salida .= "    ".FormatoValor($datox[$i]['costo'])."&nbsp;";
            $salida .= "  </td>\n";
            $salida .= " <td $ESTILO3 align=\"center\">\n";
            $salida .= "     ".FormatoValor($datox[$i]['costo_total'])."&nbsp;";
            $salida .= "  </td>\n";
            $salida .= " <td $ESTILO3 align=\"center\">\n";
            $salida .= "     ".FormatoValor($datox[$i]['costo_total_ajuste'])."&nbsp;";
            $salida .= "  </td>\n";
             $salida .= " </tr>\n";
        }
        $salida .= " </table>\n";
        $salida .= "<br>\n";
     }
    return $salida;
  }
}
?>