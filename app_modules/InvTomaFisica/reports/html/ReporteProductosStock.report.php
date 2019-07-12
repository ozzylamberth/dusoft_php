<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ReporteProductosStock.report.php
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */

  /**
  * Clase Reporte: ReporteProductosStock_report 
  * reporte con los datos de todos los productos de una toma fisica determinada.
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */

  IncludeClass("TomaFisicaSQL","","app","InvTomaFisica");
  class ReporteProductosStock_report 
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
     function ReporteProductosStock_report($datos=array())
     {
        $this->datos=$datos;
       //print_r($this->datos);
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
        $datox=$consulta->ReporteProductosStockInicial($this->datos['datos']['toma_fisica'],$this->datos['datos']['empresa_id'],$this->datos['datos']['centro_utilidad'],$this->datos['datos']['bodega']);
              
       if(!empty($datox))
       {
           $datobodega=$consulta->bodegasname($this->datos['datos']['bodega'],$this->datos['datos']['centro_utilidad'],$this->datos['datos']['empresa_id']);
                
           $estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:12px\"";  
           $salida .= " <table width=\"95%\" border='0' cellspacing='0' align=\"center\" >\n";
           $salida .= "   <tr class=\"normal_10AN\">\n";
           $salida .= "     <td width=\"50%\" align=\"center\">\n";
           $salida .= "        <b $estilo>REPORTE DE PRODUCTOS STOCK INICIAL</b>";
           $salida .= "     </td>\n";
           $salida .= "   </tr>\n";
           $salida .= " </table>\n";
           $salida .= " <br>\n";
           //$salida .= "<pre>".print_r($datox,true)."</pre>";
           $salida .= " <table width=\"95%\" border='1' cellspacing='0' align=\"center\" >\n";
           $salida .= "  <tr>\n";
           $salida .= "   <td $ESTILO2 width=\"50%\" align=\"center\">\n";
           $salida .= "     <b>NOMBRE DEL PRODUCTO</b>";
           $salida .= "   </td>\n";/*
           $salida .= "   <td $ESTILO2 width=\"10%\" align=\"center\">\n";
           $salida .= "     <b>LABORATORIO</b>";
           $salida .= "   </td>\n";*/
           $salida .= "   <td $ESTILO2 width=\"10%\" align=\"center\">\n";
           $salida .= "     <b>LOTE</b>";
           $salida .= "   </td>\n";
           $salida .= "   <td $ESTILO2 width=\"10%\" align=\"center\">\n";
           $salida .= "     <b>FECHA VENC.</b>";
           $salida .= "   </td>\n";
           $salida .= "   <td $ESTILO2 width=\"10%\" align=\"center\">\n";
           $salida .= "     <b>CANTIDAD EXISTENTE</b>";
           $salida .= "   </td>\n";
           $salida .= "   <td $ESTILO2 width=\"10%\" align=\"center\">\n";
           $salida .= "     <b>COSTO</b>";
           $salida .= "   </td>\n";
           $salida .= "   <td $ESTILO2 width=\"10%\" align=\"center\">\n";
           $salida .= "     <b>COSTO PROMEDIO</b>";
           $salida .= "   </td>\n";
           $salida .= "  </tr>\n";
           foreach($datox as $key=>$valor)
           {  
              $salida .= "  <tr>\n";
              $salida .= "   <td align=\"left\" $ESTILO3>\n";
              $salida .= "     ".$valor['descripcion_producto'];
              $salida .= "   </td>\n";
              /*$salida .= "   <td align=\"center\" $ESTILO3>\n";
              $salida .= "      ".$valor['laboratorio']."";
              $salida .= "    </td>\n";*/
              $salida .= "   <td align=\"center\" $ESTILO3>\n";
              $salida .= "      ".$valor['lote']."";
              $salida .= "    </td>\n";
              $salida .= "   <td align=\"center\" $ESTILO3>\n";
              $salida .= "      ".$valor['fecha_vencimiento']."";
              $salida .= "    </td>\n";
			  
              $salida .= "   <td align=\"center\" $ESTILO3>\n";
              $salida .= "      ".$valor['existencia_actual']."";
              $salida .= "    </td>\n";
              $salida .= "    <td $ESTILO3 align=\"center\">\n";
              $salida .= "      ".FormatoValor($valor['costo']);
              $salida .= "    </td>\n";
              $valor_cantpro=$valor['existencia_actual']*$valor['costo'];
              $salida .= "    <td $ESTILO3 align=\"center\">\n";
              $salida .= "      ".FormatoValor($valor_cantpro);
              $salida .= "    </td>\n";
              $total_prome+=$valor_cantpro;
              $total_cantidad+=$valor['existencia_actual'];
              $salida .= "                    </tr>\n";    
           }
           $salida .= "  <tr>\n";
           $salida .= "   <td $ESTILO2 width=\"10%\" align=\"center\">\n";
           $salida .= "    <b>TOTAL CANTIDAD</b>";
           $salida .= "   </td>\n";
           $salida .= "    <td $ESTILO3 align=\"center\"colspan=\"2\">\n";
           $salida .= "      ".$total_cantidad;
           $salida .= "    </td>\n";
           $salida .= "   <td $ESTILO2 width=\"10%\" align=\"center\">\n";
           $salida .= "     <b>TOTAL COSTO PROMEDIO</b>";
           $salida .= "   </td>\n";
           $salida .= "    <td $ESTILO3 align=\"center\">\n";
           $salida .= "      ".FormatoValor($total_prome);
           $salida .= "    </td>\n";
           $salida .= "  </tr>\n";
           $salida .= "                    </table>\n";
           $salida .= "                    <br>\n";        
               
      }      
      return $salida;
    }
	}

?>
