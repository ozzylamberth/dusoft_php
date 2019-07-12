<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ReporteProductosTomaFisicaSC1.report.php,v 1.2 2010/02/01 21:17:14 johanna Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */
  
  /**
  * Clase Reporte: ReporteProductosTomaFisica_report 
  * reporte con los datos de todos los productos de una toma fisica determinada.
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Jaime Gomez
  */

  IncludeClass("TomaFisicaSQL","","app","InvTomaFisica");
  class ReporteProductosTomaFisicaSC1_report
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
       function ReporteProductosTomaFisicaSC1_report($datos=array())
       {
           $this->datos=$datos;
           //var_dump($this->datos);
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
          
          //$datox=$consulta->SacarNoCuadroC1($this->datos['datos']['toma_fisica']);
          $datox=$consulta->SacarNoCuadroC1Reporte($this->datos['datos']['toma_fisica']);
          
          $ESTILO3="";//style=\"border-color:#000980;border-style:solid; border-width: thin; font-family: sans_serif, sans_serif, Verdana, helvetica, Arial; font-size: 10px; color:#000000; font-weight: bold\"";
          $ESTILO2="";//style=\"border-color:#000980;border-style:solid; border-width: thin; font-family:sans_serif, Verdana, helvetica, Arial; font-size: 10px;color: #000980;font-weight: bold\"";
          $ESTILO4="style=\"border-color:#000000;border-style:solid; border-width: thin;\"";
 
          if(!empty($datox))
          {
             $datobodega=$consulta->bodegasname($this->datos['datos']['bodega'],$this->datos['datos']['centro_utilidad'],$this->datos['datos']['empresa_id']);
             $estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:12px\"";  
             $salida .= " <table width=\"80%\"  border='0' align=\"center\" >\n";
             $salida .= "  <tr class=\"normal_10AN\">\n";
             $salida .= "   <td width=\"100%\" align=\"left\">\n";
             $salida .= "     &nbsp;";
             $salida .= "   </td>\n";
             $salida .= "  </tr>\n";
             $salida .= " </table>\n";
             $salida .= " <table width=\"90%\" border='0' cellspacing='0' align=\"center\" >\n";
             $salida .= "  <tr class=\"normal_10AN\">\n";
             $salida .= "   <td width=\"55%\" align=\"left\">\n";
             //$salida .= "     <b $estilo>PRODUCTOS NO CUADRADOS EN CONTEO (1) TOMA FISICA ".$this->datos['datos']['toma_fisica']."</b>";
             $salida .= "     <b $estilo>PRODUCTOS DEL CONTEO (1) TOMA FISICA ".$this->datos['datos']['toma_fisica']."</b>";
             $salida .= "   </td>\n";
             $salida .= "   <td width=\"30%\" align=\"left\">\n";
             $salida .= "     <b$estilo>NUMERO DE LISTA:</b>";
             $salida .= "   </td>\n";
             $salida .= "   <td $ESTILO4 width=\"10%\" align=\"left\">\n";
             $salida .= "     &nbsp;";
             $salida .= "   </td>\n";
             $salida .= "   <td width=\"5%\" align=\"left\">\n";
             $salida .= "     &nbsp;";
             $salida .= "   </td>\n";
             $salida .= "  </tr>\n";
             $salida .= "  <tr class=\"normal_10AN\">\n";
             $salida .= "    <td  align=\"left\">\n";
             $salida .= "      <b$estilo>BODEGA :".$datobodega[0]['descripcion']."</b>";
             $salida .= "    </td>\n";
             $salida .= "    <td  align=\"left\">\n";
             $salida .= "      <b$estilo>CONTEO:</b>";
             $salida .= "    </td>\n";
             $salida .= "    <td $ESTILO4  align=\"left\">\n";
             $salida .= "       &nbsp;";
             $salida .= "    </td>\n";
             $salida .= "    <td align=\"left\">\n";
             $salida .= "       &nbsp;";
             $salida .= "    </td>\n";
             $salida .= "   </tr>\n";
             $salida .= "   <tr class=\"normal_10AN\">\n";
             $salida .= "    <td  align=\"left\">\n";
             $salida .= "      <b$estilo>USUARIO :______________________________</b>";
             $salida .= "    </td>\n";
             $salida .= "    <td colspan='3' align=\"left\">\n";
             $salida .= "     <b$estilo>FECHA :_______________________</b>";
             $salida .= "    </td>\n";
             $salida .= "   </tr>\n";
             $salida .= "   <tr>\n";
             $salida .= "    <td COLSPAN='2'>\n";
             $salida .= "       &nbsp;";
             $salida .= "     </td>\n";
             $salida .= "   </tr>\n";
             $salida .= " </table>\n";
             $salida .= " <table width=\"90%\" border='1' cellspacing='0' align=\"center\" >\n";
             $salida .= "   <tr>\n";
             $salida .= "    <td $ESTILO2 width=\"10%\" align=\"center\">\n";
             $salida .= "         CODIGO PRODUCTO";
             $salida .= "    </td>\n";
             $salida .= "    <td $ESTILO2 width=\"20%\" align=\"center\">\n";
             $salida .= "         DESCRIPCION";
             $salida .= "    </td>\n";
             $salida .= "    <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          FECHA VENCIMIENTO";
             $salida .= "     </td>\n";
             $salida .= "     <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          LOTE";
             $salida .= "     </td>\n";
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          LABORATORIO";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"15%\" align=\"center\">\n";
             $salida .= "        UBICACION";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          EXISTENCIA ACTUAL";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          CANTIDAD TOMA";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          DIFERENCIA";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          COSTO";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          COSTO DIFERENCIA";
             $salida .= "      </td>\n";
             /*$salida .= "      <td $ESTILO2 width=\"10%\" align=\"center\">\n";
             $salida .= "          TOTAL COSTO DIFERENCIA";
             $salida .= "      </td>\n";*/
             $salida .= "   </tr>\n";
               
             for($i=0;$i<count($datox);$i++)
             {
                //$salida .= "<pre>".print_r($datox,true)."</pre>";
                $salida .= " <tr>\n";
                $salida .= "  <td align=\"left\" $ESTILO3>\n";
                $salida .= "    ".$datox[$i]['codigo_producto']."";
                $salida .= "  </td>\n";
                $salida .= "  <td $ESTILO3 align=\"left\">\n";
                $salida .= "    ".$datox[$i]['descripcion_prod'];
                $salida .= "  </td>\n";
                $salida .= "   <td $ESTILO3 align=\"left\">\n";
                $salida .= "     ".$datox[$i]['fecha_vencimiento'];
                $salida .= "   </td>\n";
                $salida .= "   <td $ESTILO3 align=\"left\">\n";
                $salida .= "     ".$datox[$i]['lote'];
                $salida .= "   </td>\n";
                $salida .= "   <td $ESTILO3 align=\"left\">\n";
                $salida .= "     ".$datox[$i]['laboratorio']."&nbsp;";
                $salida .= "   </td>\n";
                $salida .= "  <td align=\"center\" $ESTILO3>\n";
                $salida .= "    ".$datox[$i]['ubicacion'];
                $salida .= "  </td>\n";
                $salida .= "   <td $ESTILO3 align=\"center\">\n";
                $salida .= "     ".$datox[$i]['cantidad'];
                $salida .= "   </td>\n";
                $salida .= "   <td $ESTILO3 align=\"center\">\n";
                $salida .= "      ".FormatoValor($datox[$i]['cantidad_conteo']);
                $salida .= "   </td>\n";
                $salida .= "   <td $ESTILO3 align=\"center\">\n";
                $salida .= "      ".FormatoValor($datox[$i]['cantidad_diferencia']);
                $salida .= "    </td>\n";
                $salida .= "   <td $ESTILO3 align=\"center\">\n";
                $salida .= "      ".FormatoValor($datox[$i]['costo']);
                $salida .= "    </td>\n";
                $salida .= "    <td $ESTILO3 align=\"center\">\n";
                $salida .= "        ".FormatoValor($datox[$i]['costo_diferencia']);
                $salida .= "    </td>\n";
                $total_diferencia+=$datox[$i]['costo_diferencia'];
                $salida .= " </tr>\n";                        
              }
              $salida .= "   <tr>\n";
              $salida .= "    <td COLSPAN='9'>\n";
              $salida .= "       &nbsp;";
              $salida .= "     </td>\n";
              $salida .= "    <td $ESTILO2 width=\"5%\" align=\"center\">\n";
              $salida .= "        TOTAL DIFERENCIA";
              $salida .= "    </td>\n";
              $salida .= "    <td $ESTILO3 align=\"center\">\n";
              $salida .= "        ".FormatoValor($total_diferencia);
              $salida .= "    </td>\n";
              $salida .= "                    </table>\n";
              $salida .= "                    <br>\n";
           }
         return $salida;
      }
	}
?>