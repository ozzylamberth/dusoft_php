<?php
/**
 * @package IPSOFT-SIIS
 * @version $Id: ReporteFinalConteos.report.php
 * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Johanna Alarcon Duque
 */
    
 /**
  * Clase Reporte: ReporteFinalConteos
  * reporte con los datos de todos los productos En los conteos y muestra la ubicacion
  * @package IPSOFT-SIIS
  * @version 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  */

  IncludeClass("TomaFisicaSQL","","app","InvTomaFisica");
  class ReporteFinalConteos_report
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
  function ReporteFinalConteos_report($datos=array())
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
    $datox=$consulta->ProductosReporteIngresoEgresoUbicacion($this->datos['datos']['toma_fisica']);
     
    $ESTILO4="style=\"border-color:#000000;border-style:solid; border-width: thin;\"";

    if(!empty($datox))
    {
       $estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:12px\"";  
       $salida .= " <table width=\"80%\" border='0' cellspacing='0' align=\"center\" >\n";
       $salida .= "  <tr class=\"normal_10AN\">\n";
       $salida .= "    <td width=\"100%\" align=\"center\">\n";
       $salida .= "      &nbsp;";
       $salida .= "    </td>\n";
       $salida .= "  </tr>\n";
       $salida .= "  <tr class=\"normal_10AN\">\n";
       $salida .= "   <td width=\"50%\" align=\"center\">\n";
       $salida .= "      <b $estilo>REPORTE DE FINAL DE CONTEOS ".$this->datos['datos']['toma_fisica']."</b>";
       $salida .= "   </td>\n";
       $salida .= "  </tr>\n";
       $salida .= " </table>\n";
       $salida .= "<br>\n";
       $salida .= " <table width=\"80%\" border='1' cellspacing='0' align=\"center\" >\n";
       $salida .= "  <tr>\n";
       $salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
       $salida .= "     <b>ETIQUETA</b>";
       $salida .= "   </td>\n";
       $salida .= "   <td $ESTILO2 width=\"10%\" align=\"center\">\n";
       $salida .= "    <b>CODIGO PRODUCTO</b>";
       $salida .= "   </td>\n";
       $salida .= "   <td $ESTILO2 width=\"30%\" align=\"center\">\n";
       $salida .= "     <b>DESCRIPCION</b>";
       $salida .= "   </td>\n";
       $salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
       $salida .= "     <b>NUMEROS CONTEOS</b>";
       $salida .= "    </td>\n";
       $salida .= "    <td $ESTILO2 width=\"5%\" align=\"center\">\n";
       $salida .= "      <b>INGRESO</b>";
       $salida .= "    </td>\n";
       $salida .= "    <td $ESTILO2 width=\"5%\" align=\"center\">\n";
       $salida .= "       <b>EGRESO</b>";
       $salida .= "    </td>\n";
       $salida .= "    <td $ESTILO2 width=\"20%\" align=\"center\">\n";
       $salida .= "       <b>UBICACION</b>";
       $salida .= "    </td>\n";
       $salida .= "  </tr>\n";
       foreach($datox as $key=>$valor)
       {
          $salida .= " <tr>\n";
          $salida .= "  <td align=\"center\" $ESTILO3>\n";
          $salida .= "    ".$valor['etiqueta_x_producto'];
          $salida .= "  </td>\n";
          $salida .= "  <td align=\"left\" $ESTILO3>\n";
          $salida .= "    ".$valor['codigo_producto']."";
          $salida .= "  </td>\n";
          $salida .= "  <td $ESTILO3 align=\"left\">\n";
          $salida .= "    ".$valor['descripcion_producto'];
          $salida .= "  </td>\n";
          $salida .= "  <td $ESTILO3 align=\"center\">\n";
          $salida .= "    ".$valor['num_conteo']."&nbsp;";
          $salida .= "  </td>\n";
          if(!empty($valor['egreso']))
          {
            $cantidad_nueva=$valor['ingreso']-$valor['egreso'];
            if($cantidad_nueva>0)
            {
              $salida .= "  <td $ESTILO3 align=\"center\">\n";
              $salida .= "    ".$cantidad_nueva."&nbsp;";
              $salida .= "  </td>\n";
            }
           elseif($cantidad_nueva<0)
           {
             $cantidad_abs= abs($cantidad_nueva);
             $salida .= "  <td $ESTILO3 align=\"center\">\n";
             $salida .= "    &nbsp;";
             $salida .= "  </td>\n";
             $salida .= "  <td $ESTILO3 align=\"center\">\n";
             $salida .= "    ". $cantidad_abs."&nbsp;";
             $salida .= "  </td>\n";
           }              
         }
         elseif(empty($valor['egreso']))
         {
            $salida .= "  <td $ESTILO3 align=\"center\">\n";
            $salida .= "    ".$valor['ingreso']."&nbsp;";
            $salida .= "  </td>\n";
            $salida .= "  <td $ESTILO3 align=\"center\">\n";
            $salida .= "    &nbsp;";
            $salida .= "  </td>\n";   
         }
         $salida .= "  <td $ESTILO3 align=\"left\">\n";
         $salida .= "    ".$valor['ubicacion']."&nbsp;";
         $salida .= "  </td>\n";
         $salida .= " </tr>\n";
       }
       $salida .= "</table>\n";
       $salida .= "<br>\n";
     }
    return $salida;
  }
}
?>