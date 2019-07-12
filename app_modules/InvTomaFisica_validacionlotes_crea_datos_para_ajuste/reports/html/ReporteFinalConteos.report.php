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
       $salida .= " <table width=\"95%\" border='0' cellspacing='0' align=\"center\" >\n";
       $salida .= "  <tr class=\"normal_10AN\">\n";
       $salida .= "    <td width=\"100%\" align=\"center\">\n";
       $salida .= "      &nbsp;";
       $salida .= "    </td>\n";
       $salida .= "  </tr>\n";
       $salida .= "  <tr class=\"normal_10AN\">\n";
       $salida .= "   <td width=\"50%\" align=\"center\">\n";
       $salida .= "      <b $estilo>REPORTE DE FINAL DE CONTEOS. TOMA FISICA #".$this->datos['datos']['toma_fisica']."</b>";
       $salida .= "   </td>\n";
       $salida .= "  </tr>\n";
       $salida .= " </table>\n";
       $salida .= "<br>\n";
       $salida .= " <table width=\"98%\" border='1' cellspacing='0' align=\"center\" rules=\"\">\n";
       $salida .= "  <tr>\n";
       $salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
       $salida .= "     <b>ET.</b>";
       $salida .= "   </td>\n";
		$salida .= "   <td $ESTILO2 width=\"55%\" align=\"center\">\n";
		$salida .= "    <b>PRODUCTO</b>";
		$salida .= "   </td>\n";
		$salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
		$salida .= "     <b>LOTE.</b>";
		$salida .= "   </td>\n";
		$salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
		$salida .= "     <b>FECH/V.</b>";
		$salida .= "   </td>\n";
	   $salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
       $salida .= "     <b>EXIST.</b>";
       $salida .= "   </td>\n";
       $salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
       $salida .= "     <b>C1</b>";
       $salida .= "   </td>\n";
       $salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
       $salida .= "     <b>C2</b>";
       $salida .= "   </td>\n";
       $salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
       $salida .= "     <b>C3</b>";
       $salida .= "   </td>\n";
       $salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
       $salida .= "     <b>AJUSTE</b>";
       $salida .= "   </td>\n";
       $salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
       $salida .= "     <b>T.AJ.</b>";
       $salida .= "   </td>\n";
	   $salida .= "  </tr>\n";
	   $i=0;
       foreach($datox as $key=>$valor)
       {
          if($i==20)
		  {
			$i=0;
			$salida .= "  <tr>\n";
			$salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
			$salida .= "     <b>ET.</b>";
			$salida .= "   </td>\n";
			$salida .= "   <td $ESTILO2 width=\"55%\" align=\"center\">\n";
			$salida .= "    <b>PRODUCTO</b>";
			$salida .= "   </td>\n";
			$salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
			$salida .= "     <b>LOTE.</b>";
			$salida .= "   </td>\n";
			$salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
			$salida .= "     <b>FECH/V.</b>";
			$salida .= "   </td>\n";
			$salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
			$salida .= "     <b>EXIST.</b>";
			$salida .= "   </td>\n";
			$salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
			$salida .= "     <b>C1</b>";
			$salida .= "   </td>\n";
			$salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
			$salida .= "     <b>C2</b>";
			$salida .= "   </td>\n";
			$salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
			$salida .= "     <b>C3</b>";
			$salida .= "   </td>\n";
			$salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
			$salida .= "     <b>AJUSTE</b>";
			$salida .= "   </td>\n";
			$salida .= "   <td $ESTILO2 width=\"5%\" align=\"center\">\n";
			$salida .= "     <b>T.AJ.</b>";
			$salida .= "   </td>\n";
			$salida .= "  </tr>\n";
		  }
		  $salida .= " <tr>\n";
          $salida .= "  <td align=\"center\" $ESTILO3>\n";
          $salida .= "    ".$valor['etiqueta_x_producto'];
          $salida .= "  </td>\n";
          $salida .= "  <td align=\"left\" $ESTILO3>\n";
          $salida .= "    ".$valor['codigo_producto']." - ";
          $salida .= "    ".$valor['descripcion'];
          $salida .= "  </td>\n";
          $salida .= "  <td $ESTILO3 align=\"center\">\n";
          $salida .= "    ".$valor['lote']."";
          $salida .= "  </td>\n";
          $salida .= "  <td $ESTILO3 align=\"center\">\n";
          $salida .= "    ".$valor['fecha_vencimiento']."";
          $salida .= "  </td>\n";
		  $salida .= "  <td $ESTILO3 align=\"center\">\n";
          $salida .= "    ".FormatoValor($valor['existencia'])."";
          $salida .= "  </td>\n";
          $salida .= "  <td $ESTILO3 align=\"center\">\n";
          $salida .= "    ".(($valor['conteo_1']=="")? "---":FormatoValor($valor['conteo_1']))."";
          $salida .= "  </td>\n";
          $salida .= "  <td $ESTILO3 align=\"center\">\n";
          $salida .= "    ".(($valor['conteo_2']=="")? "---":FormatoValor($valor['conteo_2']))."";
          $salida .= "  </td>\n";
          $salida .= "  <td $ESTILO3 align=\"center\">\n";
          $salida .= "    ".(($valor['conteo_3']=="")? "---":FormatoValor($valor['conteo_3']))."";
          $salida .= "  </td>\n";
          $salida .= "  <td $ESTILO3 align=\"center\">\n";
		  $salida .= "    ".(($valor['nueva_existencia']=="")? "---":FormatoValor($valor['nueva_existencia']))."";
          /*salida .= "    ".FormatoValor($valor['nueva_existencia'])."";*/
          $salida .= "  </td>\n";
		  $tipo_ajuste=explode("@",$valor['tipo_ajuste']);
		  $salida .= "		<td $ESTILO3 align=\"center\">\n";
		  $salida .= "			<img src=\"".GetThemePath()."/images/".$tipo_ajuste[0]."\" border=\"0\" width=\"17\" height=\"17\" title=\"".$tipo_ajuste[1]."\">";
		  $salida .= "		</td>";
          $salida .= " </tr>\n";
		  $i++;
       }
       $salida .= "</table>\n";
       $salida .= "<br>\n";
     }
    return $salida;
  }
}
?>