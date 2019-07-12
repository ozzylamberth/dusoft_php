<?php
 /**
    * @package IPSOFT-SIIS
    * @version $Id: ReporteProductosExvsC1.report.php
    * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
    * @author Johanna Alarcon Duque
    */
    
    /**
    * Clase Reporte: ReporteProductosExvsC1
    * reporte con los datos de todos los productos Existencias vs Conteo1
    * @package IPSOFT-SIIS
    * @version 
    * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
    */

    IncludeClass("TomaFisicaSQL","","app","InvTomaFisica");
  class ReporteProductosExvsC1_report
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
   function ReporteProductosExvsC1_report($datos=array())
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
      //$consulta=new TomaFisicaSQL();
      //$datobodega=$consulta->bodegasname($this->datos['datos']['bodega'],$this->datos['datos']['centro_utilidad'],$this->datos['datos']['empresa_id']);
      //$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
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
      $datox=$consulta->InconsistenciasC1($this->datos['datos']['toma_fisica']);


      
      $ESTILO3="";//style=\"border-color:#000980;border-style:solid; border-width: thin; font-family: sans_serif, sans_serif, Verdana, helvetica, Arial; font-size: 10px; color:#000000; font-weight: bold\"";
      $ESTILO2="";//style=\"border-color:#000980;border-style:solid; border-width: thin; font-family:sans_serif, Verdana, helvetica, Arial; font-size: 10px;color: #000980;font-weight: bold\"";
      $ESTILO4="style=\"border-color:#000000;border-style:solid; border-width: thin;\"";

       if(!empty($datox))
       {
//print_r($datox);
          $datobodega=$consulta->bodegasname($this->datos['datos']['bodega'],$this->datos['datos']['centro_utilidad'],$this->datos['datos']['empresa_id']);
          
          $estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:12px\"";  
          $salida .= "                 <table width=\"80%\"  border='0' align=\"center\" >\n";
          $salida .= "                    <tr class=\"normal_10AN\">\n";
          $salida .= "                      <td width=\"100%\" align=\"left\">\n";
          $salida .= "                        &nbsp;";
          $salida .= "                      </td>\n";
          $salida .= "                   </tr>\n";
          $salida .= "                 </table>\n";
          $salida .= "                 <table width=\"80%\" border='0' cellspacing='0' align=\"center\" >\n";
          $salida .= "                    <tr class=\"normal_10AN\">\n";
          $salida .= "                      <td width=\"50%\" align=\"left\">\n";
          $salida .= "                        <b $estilo>REPORTE DE EXISTENCIAS VS CONTEO1 ".$this->datos['datos']['toma_fisica']."</b>";
          $salida .= "                      </td>\n";
          $salida .= "                      <td width=\"30%\" align=\"left\">\n";
          $salida .= "                       <b$estilo>NUMERO DE LISTA:</b>";
          $salida .= "                      </td>\n";
          $salida .= "                      <td $ESTILO4 width=\"10%\" align=\"left\">\n";
          $salida .= "                      &nbsp;";
          $salida .= "                      </td>\n";
          $salida .= "                      <td width=\"10%\" align=\"left\">\n";
          $salida .= "                       &nbsp;";
          $salida .= "                      </td>\n";
          $salida .= "                   </tr>\n";
          $salida .= "                    <tr class=\"normal_10AN\">\n";
          $salida .= "                      <td  align=\"left\">\n";
          $salida .= "                       <b$estilo>BODEGA :".$datobodega[0]['descripcion']."</b>";
          $salida .= "                      </td>\n";
          $salida .= "                      <td  align=\"left\">\n";
          $salida .= "                       <b$estilo>CONTEO:</b>";
          $salida .= "                      </td>\n";
          $salida .= "                      <td $ESTILO4  align=\"left\">\n";
          $salida .= "                      &nbsp;";
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"left\">\n";
          $salida .= "                       &nbsp;";
          $salida .= "                      </td>\n";
          $salida .= "                   </tr>\n";
          $salida .= "                    <tr class=\"normal_10AN\">\n";
          $salida .= "                      <td  align=\"left\">\n";
          $salida .= "                       <b$estilo>USUARIO :______________________________</b>";
          $salida .= "                      </td>\n";
          $salida .= "                      <td colspan='3' align=\"left\">\n";
          $salida .= "                       <b$estilo>FECHA :_______________________</b>";
          $salida .= "                      </td>\n";
          $salida .= "                   </tr>\n";
          $salida .= "                   <tr>\n";
          $salida .= "                      <td COLSPAN='2'>\n";
          $salida .= "                      &nbsp;";
          $salida .= "                      </td>\n";
          $salida .= "                   </tr>\n";
          $salida .= "                 </table>\n";
          $salida .= "                 <table width=\"80%\" border='1' cellspacing='0' align=\"center\" >\n";
          $salida .= "                    <tr>\n";
          $salida .= "                       <td $ESTILO2 width=\"10%\" align=\"center\">\n";
          $salida .= "                        ETIQUETA";
          $salida .= "                       </td>\n";
          $salida .= "                       <td $ESTILO2 width=\"20%\" align=\"center\">\n";
          $salida .= "                         CODIGO PRODUCTO";
          $salida .= "                       </td>\n";
          $salida .= "                       <td $ESTILO2 width=\"30%\" align=\"center\">\n";
          $salida .= "                          DESCRIPCION";
          $salida .= "                       </td>\n";
          $salida .= "                       <td $ESTILO2 width=\"10%\" align=\"center\">\n";
          $salida .= "                        EXISTENCIA";
          $salida .= "                       </td>\n";
          $salida .= "                       <td $ESTILO2 width=\"10%\" align=\"center\">\n";
          $salida .= "                          CONTEO 1";
          $salida .= "                       </td>\n";
          $salida .= "                       <td $ESTILO2 width=\"10%\" align=\"center\">\n";
          $salida .= "                          COSTO";
          $salida .= "                       </td>\n";
          $salida .= "                    </tr>\n";
          
          for($i=0;  $i<count($datox);$i++)
          {
              if(($i%40)==0 && $i>0)
              {   
                  $salida .= "                    </table>\n";
                  //$salida .= "                    <H1 >";
                  $salida .= "                 <table width=\"80%\"  border='0' align=\"center\" >\n";
                  $salida .= "                    <tr class=\"normal_10AN\">\n";
                  $salida .= "                      <td width=\"100%\" align=\"left\">\n";
                  $salida .= "                        &nbsp;";
                  $salida .= "                      </td>\n";
                  $salida .= "                   </tr>\n";
                  $salida .= "                 </table>\n";
                  $salida .= "                 <table  width=\"80%\"  border='0' align=\"center\" >\n";
                  $salida .= "                    <tr class=\"normal_10AN\">\n";
                  $salida .= "                      <td width=\"100%\" align=\"left\">\n";
                  $salida .= "                        &nbsp;";
                  $salida .= "                      </td>\n";
                  $salida .= "                   </tr>\n";
                  $salida .= "                 </table>\n";
                  $salida .= "                 <table width=\"80%\"  border='0' align=\"center\" >\n";
                  $salida .= "                    <tr class=\"normal_10AN\">\n";
                  $salida .= "                      <td width=\"100%\" align=\"left\">\n";
                  $salida .= "                        &nbsp;";
                  $salida .= "                      </td>\n";
                  $salida .= "                   </tr>\n";
                  $salida .= "                 </table>\n";
                  $salida .= "                 <table width=\"80%\"  border='0' align=\"center\" >\n";
                  $salida .= "                    <tr class=\"normal_10AN\">\n";
                  $salida .= "                      <td width=\"100%\" align=\"left\">\n";
                  $salida .= "                        &nbsp;";
                  $salida .= "                      </td>\n";
                  $salida .= "                   </tr>\n";
                  $salida .= "                 </table>\n";
                  $salida .= "                 <table width=\"80%\"  border='0' align=\"center\" >\n";
                  $salida .= "                    <tr class=\"normal_10AN\">\n";
                  $salida .= "                      <td width=\"100%\" align=\"left\">\n";
                  $salida .= "                        &nbsp;";
                  $salida .= "                      </td>\n";
                  $salida .= "                   </tr>\n";
                  $salida .= "                 </table>\n";
                  $salida .= "                 <table width=\"80%\" style=\"page-break-after: always\" border='0' align=\"center\" >\n";
                  $salida .= "                    <tr class=\"normal_10AN\">\n";
                  $salida .= "                      <td width=\"100%\" align=\"left\">\n";
                  $salida .= "                        &nbsp;";
                  $salida .= "                      </td>\n";
                  $salida .= "                   </tr>\n";
                  $salida .= "                 </table>\n";
                  $salida .= "                 <table width=\"80%\" border='0' align=\"center\" >\n";
                  $salida .= "                    <tr class=\"normal_10AN\">\n";
                  $salida .= "                      <td width=\"50%\" align=\"left\">\n";
                  $salida .= "                        <b $estilo>REPORTE DE PRODUCTOS DE LA TOMA FISICA ".$this->datos['datos']['toma_fisica']."</b>";
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td width=\"30%\" align=\"left\">\n";
                  $salida .= "                       <b$estilo>NUMERO DE LISTA:</b>";
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td $ESTILO4 width=\"10%\" align=\"left\">\n";
                  $salida .= "                      &nbsp;";
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td width=\"10%\" align=\"left\">\n";
                  $salida .= "                       &nbsp;";
                  $salida .= "                      </td>\n";
                  $salida .= "                   </tr>\n";
                  $salida .= "                    <tr class=\"normal_10AN\">\n";
                  $salida .= "                      <td  align=\"left\">\n";
                  $salida .= "                       <b$estilo>BODEGA :".$datobodega[0]['descripcion']."</b>";
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td  align=\"left\">\n";
                  $salida .= "                       <b$estilo>CONTEO:</b>";
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td $ESTILO4  align=\"left\">\n";
                  $salida .= "                      &nbsp;";
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td align=\"left\">\n";
                  $salida .= "                       &nbsp;";
                  $salida .= "                      </td>\n";
                  $salida .= "                   </tr>\n";
                  $salida .= "                    <tr class=\"normal_10AN\">\n";
                  $salida .= "                      <td  align=\"left\">\n";
                  $salida .= "                       <b$estilo>USUARIO :______________________________</b>";
                  $salida .= "                      </td>\n";
                  $salida .= "                      <td colspan='3' align=\"left\">\n";
                  $salida .= "                       <b$estilo>FECHA :_______________________</b>";
                  $salida .= "                      </td>\n";
                  $salida .= "                   </tr>\n";
                  $salida .= "                   <tr>\n";
                  $salida .= "                      <td COLSPAN='2'>\n";
                  $salida .= "                      &nbsp;";
                  $salida .= "                      </td>\n";
                  $salida .= "                   </tr>\n";
                  $salida .= "                 </table>\n";
                  $salida .= "                 <table width=\"80%\" border='1' align=\"center\" cellspacing='0'>\n";
                  $salida .= "                    <tr>\n";
                  $salida .= "                       <td $ESTILO2 width=\"10%\" align=\"center\">\n";
                  $salida .= "                        ETIQUETA";
                  $salida .= "                       </td>\n";
                  $salida .= "                       <td $ESTILO2 width=\"20%\" align=\"center\">\n";
                  $salida .= "                         CODIGO PRODUCTO";
                  $salida .= "                       </td>\n";
                  $salida .= "                       <td $ESTILO2 width=\"50%\" align=\"center\">\n";
                  $salida .= "                          DESCRIPCION";
                  $salida .= "                       </td>\n";
                  $salida .= "                       <td $ESTILO2 width=\"10%\" align=\"center\">\n";
                  $salida .= "                        UNIDAD";
                  $salida .= "                       </td>\n";
                  
                  $salida .= "                       <td $ESTILO2 width=\"10%\" align=\"center\">\n";
                  $salida .= "                          CANTIDAD";
                  $salida .= "                       </td>\n";
                  $salida .= "                    </tr>\n";
                  //$salida .= "</H1>\n";
              }
              
              $salida .= "                    <tr>\n";
              $salida .= "                      <td align=\"center\" $ESTILO3>\n";
              $salida .= "                       ".$datox[$i]['etiqueta'];
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"left\" $ESTILO3>\n";
              $salida .= "                       ".$datox[$i]['codigo_producto']."";
              $salida .= "                      </td>\n";
              $salida .= "                      <td $ESTILO3 align=\"left\">\n";
              $salida .= "                       ".$datox[$i]['descripcion'];
              $salida .= "                      </td>\n";
              $salida .= "                      <td $ESTILO3 align=\"left\">\n";
              $salida .= "                       ".$datox[$i]['existencia']."&nbsp;";
              $salida .= "                      </td>\n";
              $salida .= "                      <td $ESTILO3 align=\"left\">\n";
              $salida .= "                       ".$datox[$i]['conteo_1']."&nbsp;";
              $salida .= "                      </td>\n";
              $salida .= "                      <td $ESTILO3 align=\"left\">\n";
              $salida .= "                       ".$datox[$i]['costo']."&nbsp;";
              $salida .= "                      </td>\n";
              //$salida .= "                      <td $ESTILO3 align=\"center\">\n";
              //$salida .= "                       "."&nbsp;";
              //$salida .= "                      </td>\n";
              $salida .= "                    </tr>\n";

                  
          }
          $salida .= "                    </table>\n";
          $salida .= "                    <br>\n";
       }
      
      return $salida;
  }
}
?>