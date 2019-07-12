<?php
	/**
	* $Id: reporte_detalle_auditoria.report.php,v 1.1 2010/04/08 20:36:35 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass('ClaseUtil');
  IncludeClass("DevolucionVentaSQL","classes","app","DevolucionVenta");
	class DevolucionVenta_report
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
		
		//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	  function DevolucionVenta_report($datos=array())
	  {
			$this->datos=$datos;
      return true;
	  }
		/**
    *
    */
		function GetMembrete()
		{
	
	$nc = new DevolucionVentaSQL();

	$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:20pt\"";
	$html = "	<table width=\"70%\" align=\"center\" $est rules=\"all\">\n";		
   	$html .= "		<tr class=\"label\">\n";
    $html .= "			<td align=\"center\">DEVOLUCION AL CLIENTE</td>\n";
	$html .= "		</tr>\n";
    $html .= "		<tr class=\"label\">\n";
    $html .= "			<td align=\"center\">FACTURA: ".$this->datos['prefijo_factura']."-".$this->datos['factura_fiscal']."</td>\n";
    $html .= "		</tr>\n";
	$imagen ='logocliente.png';
    $html .= "</table>";
      //$titulo .= "<b $est >REPORTE DE AUDITORIA SELECCIONADA<br>";
      $titulo .= $html;
			
	$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
	'subtitulo'=>' ',
	'logo'=>$imagen,
	'align'=>'left'));
			
	return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
		$nc = new DevolucionVentaSQL();
		$cl = new ClaseUtil();

			
		$Devolucion=$nc->ConsultarDevolucion_Cliente($this->datos['bodegas_doc_id'],$this->datos['numeracion']);
		
	
		$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
     // print_r($DevolucionVenta_Detalle);
      if(!empty($Devolucion))
      {
		$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" >\n";		
		$html .= "		<tr class=\"label\" align=\"center\">\n";
		$html .= "			<td class=\"label\" > CLIENTE: ".$Devolucion[0]['cliente']."</td>\n";
		$html .= "			<td class=\"label\" > TELEFONO/CELULAR: ".$Devolucion[0]['telefono']."/".$Devolucion[0]['celular']."</td>\n";
		$html .= "			<td class=\"label\" > DIRECCION: ".$Devolucion[0]['localizacion']." : ".$Devolucion[0]['direccion']."</td>\n";
		$html .= "		</tr>\n";	
		$html .= "		<tr class=\"label\" align=\"center\">\n";
		$html .= "			<td class=\"label\" >DOCUMENTO DE DEVOLUCION: ".$Devolucion[0]['prefijo']." - ".$this->datos['numeracion']." </td>\n";
		$html .= "		</tr>\n";
		$html .= "	</table>";
		$html .= "  <br>";
        
		$html .= "	      <fieldset class=\"fieldset\" style=\"width:60%\">\n";
		$html .= "          <legend class=\"normal_10AN\">OBSERVACIONES</legend>\n";
		$html .= "	<table width=\"100%\" align=\"center\" style=\"border:1px solid #000000;\" >\n";		
		$html .= "		<tr class=\"label\" >\n";
		$html .= "			<td class=\"label\" >".$Devolucion[0]['observacion']."</td>\n";
		$html .= "		</tr>\n";
		$html .= "	</table>";
		$html .= "      </fieldset>";
        $html .= "  <br>";
        //style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"
		$html .= "	<table width=\"100%\" align=\"center\"  rules=\"all\">\n";		/*style=\"border:1px solid #000000;font-size:8.5px\"*/
		$html .= "		<tr class=\"label\">\n";
		$html .= "			<td align=\"center\" colspan=\"5\">PRODUCTOS</td>\n";
		$html .= "		</tr>\n";	
		$html .= "		<tr class=\"label\" align=\"center\">\n";
		$html .= "			<td >CODIGO</td><td >DESCRIPCION</td><td >CANTIDAD</td><td >VL/U</td><td >VALOR TOTAL</td>\n";
		$html .= "		</tr>\n";
		$sum=0;
        foreach($Devolucion as $key=>$valor)
        {
        $html .= "		<tr>\n";
        $html .= "        <td>".$valor['codigo_producto']."</td>";
        $html .= "        <td>".$valor['producto']."</td>";
        $html .= "        <td>".FormatoValor($valor['cantidad'])."</td>";
        $html .= "        <td>$".FormatoValor(($valor['total_costo']/$valor['cantidad']),2)."</td>";
        $html .= "        <td>$".FormatoValor(($valor['total_costo']),2)."</td>";
        $html .= "		</tr>\n";
		$sum += ($valor['total_costo']);
        }
        $html .= "		<tr>\n";
		$html .= "			<td></td>";
		$html .= "			<td></td>";
		$html .= "			<td></td>";
        $html .= "        	<td  align=\"right\">TOTAL : </td>";
		$html .= "			<td><b>$".FormatoValor($sum,2)."</b></td>";
        $html .= "		</tr>\n";
        
		$html .= "	</table><br><br>\n";
		}
		$usuario = $nc->ObtenerInformacionUsuario(UserGetUID());
		$html .= "	<br><table border='0' width=\"100%\">\n";
		$html .= "		<tr>\n";
		$html .= "			<td align=\"justify\" width=\"50%\">\n";
		$html .= "				<font size='1' face='arial'>\n";
		$html .= "					Imprimió:&nbsp;".$usuario['nombre']."\n";
		$html .= "				</font>\n";
		$html .= "			</td>\n";
		$html .= "			<td align=\"right\" width=\"50%\">\n";
		$html .= "				<font size='1' face='arial'>\n";
		$html .= "					Fecha Impresión :&nbsp;&nbsp;".date("d/m/Y - h:i a")."\n";
		$html .= "				</font>\n";
		$html .= "			</td>\n";
		$html .= "		</tr>\n";
		$html .= "	</table>\n";
		return $html;
		}
	}
?>