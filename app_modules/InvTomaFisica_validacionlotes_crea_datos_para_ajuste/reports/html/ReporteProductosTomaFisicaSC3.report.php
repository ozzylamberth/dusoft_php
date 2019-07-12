<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ReporteProductosTomaFisicaSC3.report.php,v 1.1 2009/12/31 13:52:06 johanna Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */
    
  /**
  * Clase Reporte: ReporteProductosTomaFisica_report 
  * reporte con los datos de todos los productos de una toma fisica determinada.
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Jaime Gomez
  */

   IncludeClass("TomaFisicaSQL","","app","InvTomaFisica");
   class ReporteProductosTomaFisicaSC3_report 
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
      function ReporteProductosTomaFisicaSC3_report($datos=array())
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
          $datox=$consulta->SacarNoCuadroC3Reporte($this->datos['datos']['toma_fisica']);
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
           //$salida .= "     <b $estilo>PRODUCTOS NO CUADRADOS EN CONTEO (3) TOMA FISICA ".$this->datos['datos']['toma_fisica']."</b>";
           $salida .= "     <b $estilo>PRODUCTOS DEL CONTEO (3) TOMA FISICA ".$this->datos['datos']['toma_fisica']."</b>";
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
                        $salida .= " <table width=\"95%\" border='1' cellspacing='0' align=\"center\" rules=\"all\" >\n";
             $salida .= "   <tr>\n";
             /*$salida .= "    <td $ESTILO2 width=\"10%\" align=\"center\">\n";
             $salida .= "         CODIGO PRODUCTO";
             $salida .= "    </td>\n";*/
             $salida .= "    <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "         ET";
             $salida .= "    </td>\n";
             $salida .= "    <td $ESTILO2 width=\"60%\" align=\"center\">\n";
             $salida .= "         DESCRIPCION";
             $salida .= "    </td>\n";
             $salida .= "    <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          FECHA VENCIMIENTO";
             $salida .= "     </td>\n";
             $salida .= "     <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          LOTE";
             $salida .= "     </td>\n";/*
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          LABORATORIO";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"15%\" align=\"center\">\n";
             $salida .= "        UBICACION";
             $salida .= "      </td>\n";*/
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          EXISTENCIA ACTUAL";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          CONTEO 1";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          CONTEO 2";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          CONTEO 3";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          EXITS. Vs 3";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          DIF. 1 Vs 3";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          DIF. 2 Vs 3";
             $salida .= "      </td>\n";
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          COSTO";
             $salida .= "      </td>\n";/*
             $salida .= "      <td $ESTILO2 width=\"5%\" align=\"center\">\n";
             $salida .= "          COSTO DIFERENCIA";
             $salida .= "      </td>\n";*/
             /*$salida .= "      <td $ESTILO2 width=\"10%\" align=\"center\">\n";
             $salida .= "          TOTAL COSTO DIFERENCIA";
             $salida .= "      </td>\n";*/
             $salida .= "   </tr>\n";
               
             for($i=0;$i<count($datox);$i++)
             {
                //$salida .= "<pre>".print_r($datox,true)."</pre>";
                $salida .= " <tr>\n";
                $salida .= "  <td align=\"center\" class=\"normal_10AN\" >\n";
                $salida .= "    ".$datox[$i]['etiqueta_x_producto']."";
				$salida .= "	</td>";
                $salida .= "  <td align=\"left\" $ESTILO3>\n";
                $salida .= "    ".$datox[$i]['codigo_producto']." - ";
                /*$salida .= "  </td>\n";
                $salida .= "  <td $ESTILO3 align=\"left\">\n";*/
                $salida .= "    ".$datox[$i]['descripcion'];
                $salida .= "  </td>\n";
                $salida .= "   <td $ESTILO3 align=\"left\">\n";
                $salida .= "     ".$datox[$i]['fecha_vencimiento'];
                $salida .= "   </td>\n";
                $salida .= "   <td $ESTILO3 align=\"left\">\n";
                $salida .= "     ".$datox[$i]['lote'];
                $salida .= "   </td>\n";/*
                $salida .= "   <td $ESTILO3 align=\"left\">\n";
                $salida .= "     ".$datox[$i]['laboratorio']."&nbsp;";
                $salida .= "   </td>\n";
                $salida .= "  <td align=\"center\" $ESTILO3>\n";
                $salida .= "    ".$datox[$i]['ubicacion'];
                $salida .= "  </td>\n";*/
                $salida .= "   <td $ESTILO3 align=\"center\" title=\"EXISTENCIA\">\n";
                $salida .= "     ".$datox[$i]['existencia_actual'];
                $salida .= "   </td>\n";
                $salida .= "   <td $ESTILO3 align=\"center\" title=\"CONTEO 1\">\n";
                $salida .= "      ".FormatoValor($datox[$i]['conteo_1']);
                $salida .= "   </td>\n";
                $salida .= "   <td $ESTILO3 align=\"center\" title=\"CONTEO 2\">\n";
                $salida .= "      ".FormatoValor($datox[$i]['conteo_2']);
                $salida .= "   </td>\n";
                $salida .= "   <td $ESTILO3 align=\"center\" title=\"CONTEO 3\">\n";
                $salida .= "      ".FormatoValor($datox[$i]['conteo_3']);
                $salida .= "   </td>\n";
                $salida .= "   <td style=\"color:#FF0000;\" align=\"center\" title=\"DIFERENCIA CONTEO 3 Vs EXISTENCIA\">\n";
                $salida .= "      ".FormatoValor($datox[$i]['diferencia_3']);
                $salida .= "    </td>\n";
                $salida .= "   <td style=\"color:#FF0000;\"  align=\"center\" title=\"DIFERENCIA CONTEO 1 Vs 3\">\n";
                $salida .= "      ".FormatoValor($datox[$i]['diferencia_1con3']);
                $salida .= "    </td>\n";
                $salida .= "   <td style=\"color:#FF0000;\"  align=\"center\" title=\"DIFERENCIA CONTEO 2 Vs 3\">\n";
                $salida .= "      ".FormatoValor($datox[$i]['diferencia_2con3']);
                $salida .= "    </td>\n";
                $salida .= "   <td $ESTILO3 align=\"center\" title=\"COSTO\">\n";
                $salida .= "      ".FormatoValor($datox[$i]['costo']);
                $salida .= "    </td>\n";
                /*$salida .= "    <td $ESTILO3 align=\"center\">\n";
                $salida .= "        ".FormatoValor($datox[$i]['diferencia_1con3']*$datox[$i]['costo']);
                $salida .= "    </td>\n";*/
                /*$total_diferencia+=($datox[$i]['diferencia_1con2']*$datox[$i]['costo']);*/
                $salida .= " </tr>\n";                        
              }
              /*$salida .= "   <tr>\n";
              $salida .= "    <td COLSPAN='7'>\n";
              $salida .= "       &nbsp;";
              $salida .= "     </td>\n";*/
             /* $salida .= "    <td $ESTILO2 colspan=\"9\" align=\"right\">\n";
              $salida .= "        TOTAL DIFERENCIA";
              $salida .= "    </td>\n";
              $salida .= "    <td $ESTILO3 align=\"center\">\n";
              $salida .= "        ".FormatoValor($total_diferencia);
              $salida .= "    </td>\n";*/
              $salida .= "                    </table>\n";
              $salida .= "                    <br>\n";
         }
       return $salida;
     }

	}

?>
