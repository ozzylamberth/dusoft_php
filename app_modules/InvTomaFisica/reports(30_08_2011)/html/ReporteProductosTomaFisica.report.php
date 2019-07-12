<?php
    /**
    * @package IPSOFT-SIIS
    * @version $Id: ReporteProductosTomaFisica.report.php,v 1.1 2009/12/31 13:52:24 johanna Exp $ 
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
    class ReporteProductosTomaFisica_report 
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
        function ReporteProductosTomaFisica_report($datos=array())
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
            $datox=$consulta->ReporteProductosTomaFisica($this->datos['datos']['toma_fisica'],$this->datos['datos']['empresa_id'],$this->datos['datos']['centro_utilidad'],$this->datos['datos']['bodega'],$this->datos['datos']['filtro']);
            $Dir="cache/TomasFisicasLabora".$this->datos['datos']['toma_fisica'].".pdf";
            //IMPORTANTE $this->datos['filtro']==1 es LABORATORIO, $this->datos['filtro']==2 es MOLECULA, $this->datos['filtro']==3 es UBICACION  
            // print_r($datox);
            //var_dump($datox);
            //exit;
            //$datos=SessionGetVar("BUSQUEDA");
            //$afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");
            //$afiliados = $afi->GetAfiliados($datos, $count=false, $limit=false, $offset=false);
            //$contador_registros=SessionGetVar("CONTADOR");
            //var_dump($datox);
            $ESTILO3="";//style=\"border-color:#000980;border-style:solid; border-width: thin; font-family: sans_serif, sans_serif, Verdana, helvetica, Arial; font-size: 10px; color:#000000; font-weight: bold\"";
            $ESTILO2="";//style=\"border-color:#000980;border-style:solid; border-width: thin; font-family:sans_serif, Verdana, helvetica, Arial; font-size: 10px;color: #000980;font-weight: bold\"";
            $ESTILO4="style=\"border-color:#000000;border-style:solid; border-width: thin;\"";
             require("classes/fpdf/html_class.php");
  
             
            define('FPDF_FONTPATH','font/');
            $pdf=new PDF('P','mm','mcarta1');
               $pdf->AliasNbPages();
               $pdf->AddPage();
               $pdf->SetFont('Arial','',7);
  

                $pdf->Ln(2);
                $pdf->Cell(171,5,"REPORTE DE PRODUCTOS DE LA TOMA FISICA ".$this->datos['datos']['toma_fisica']."",0);
             if(!empty($datox))
             {
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
                //$salida .= "<pre>".print_r($datox,true)."</pre>";
                
                foreach($datox as $key=>$valor)
                {
                  $salida .= "                 <table width=\"80%\" border='1' cellspacing='0' align=\"center\" >\n";
                  $salida .= "                    <tr>\n";
                  $salida .= "                       <td $ESTILO2 width=\"80%\" align=\"center\" colspan=\"8\">\n";
                  $salida .= "                        ".$key." ";
                  $salida .= "                    </tr>\n";
                  $salida .= "                    <tr>\n";
                 foreach($valor as $key1=>$valor1)
                 {
                    $salida .= "                       <td $ESTILO2 width=\"5%\" align=\"center\">\n";
                    $salida .= "                        ETIQUETA";
                    $salida .= "                       </td>\n";
                    $salida .= "                       <td $ESTILO2 width=\"10%\" align=\"center\">\n";
                    $salida .= "                         CODIGO PRODUCTO";
                    $salida .= "                       </td>\n";
                    $salida .= "                       <td $ESTILO2 width=\"20%\" align=\"center\">\n";
                    $salida .= "                          DESCRIPCION";
                    $salida .= "                       </td>\n";
                    $salida .= "                       <td $ESTILO2 width=\"10%\" align=\"center\">\n";
                    $salida .= "                         FECHA VENCIMIENTO";
                    $salida .= "                       </td>\n";
                     $salida .= "                       <td $ESTILO2 width=\"10%\" align=\"center\">\n";
                    $salida .= "                          LOTE";
                    $salida .= "                       </td>\n";
                     $salida .= "                       <td $ESTILO2 width=\"10%\" align=\"center\">\n";
                    $salida .= "                          LABORATORIO";
                    $salida .= "                       </td>\n";
                    $salida .= "                       <td $ESTILO2 width=\"10%\" align=\"center\">\n";
                    $salida .= "                        UNIDAD";
                    $salida .= "                       </td>\n";
                    $salida .= "                       <td $ESTILO2 width=\"5%\" align=\"center\">\n";
                    $salida .= "                          CANTIDAD";
                    $salida .= "                       </td>\n";
                    $salida .= "                    </tr>\n";
                    $i=0;
                    $salida .= "                    <tr>\n";
                    $salida .= "                      <td align=\"center\" $ESTILO3>\n";
                    $salida .= "                       ".$valor1['etiqueta'];
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"left\" $ESTILO3>\n";
                    $salida .= "                       ".$valor1['codigo_producto']."";
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td $ESTILO3 align=\"left\">\n";
                    $salida .= "                       ".$valor1['descripcion_producto'];
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td $ESTILO3 align=\"left\">\n";
                    $salida .= "                       ".$valor1['fecha_vencimiento']."&nbsp;";
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td $ESTILO3 align=\"left\">\n";
                    $salida .= "                       ".$valor1['lote']."&nbsp;";
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td $ESTILO3 align=\"left\">\n";
                    $salida .= "                       ".$valor1['laboratorio']."&nbsp;";
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td $ESTILO3 align=\"left\">\n";
                    $salida .= "                       ".$valor1['descripcion_unidad']."&nbsp;";
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td $ESTILO3 align=\"center\">\n";
                    $salida .= "                       "."&nbsp;";
                    $salida .= "                      </td>\n";
                    $salida .= "                    </tr>\n";
                 }
                $i++;  
                $salida .= "                    </table>\n";
                $salida .= "                    <br>\n";            
               }
               
             }
            $pdf->WriteHTML($salida); 
            //return $pdf2;return true;
            $pdf->Output($Dir,'F');
            return true;
      }
         
            
    //$pdf2->SetLineWidth(0.5);
    //$pdf2->RoundedRect(7, 7, 196, 284, 3.5, '');
    //$pdf2->Output($Dir,'F');

	}

?>
