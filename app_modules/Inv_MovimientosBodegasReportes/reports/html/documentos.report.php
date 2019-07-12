<?php
	/**
	* $Id: documentos.report.php,v 1.1 2010/04/08 20:36:35 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	*/
  IncludeClass('ConexionBD');
  IncludeClass("ListaReportes","classes","app","Inv_MovimientosBodegasReportes");
	class documentos_report 
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
	  function documentos_report($datos=array())
	  {
			$this->datos=$datos;			
	    return true;
	  }
		/**
    *
    */
		function GetMembrete()
		{
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:10pt\"";
			$titulo .= "<b $est >INFORME ".$this->datos['nombre_doc']."<br>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$nc = new ListaReportes();
      //$nc->debug = true;
			$detl = $nc->ObtenerListadoDocumentos($this->datos['empresa_id'],$this->datos['documento_id'],$this->datos['fecha_inicio'],$this->datos['fecha_fin']);
			
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";

      if(!empty($detl))
      {
  			
  			$sum = 0;
  			foreach($detl as $key => $dtl)
  			{
          $suma = $docs = 0;
          foreach($dtl as $k1 => $dtl2)
          {
            $suma += $dtl2['total_costo'];
            $docs++;
          }			
          /*$html .= "		<tr class=\"normal_10\">\n";*/
         /* $html .= "			<td >".$dtl2['empresa_id']."</td>\n";*/
          /*$html .= "			<td >".$dtl2['razon_social']." : ". $dtl2['observacion']."</td>\n";*/
          /*$html .= "			<td ></td>\n";*/
          /*$html .= "			<td align=\"right\" >".$docs."</td>\n";
          $html .= "			<td align=\"right\">$".Formatovalor($suma)."</td>\n";
          $html .= "		</tr>\n";*/
          $sum += $suma;
  			}
		$html .= "	<table width=\"80%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
		$html .= "		<tr class=\"label\">\n";
		/*$html .= "			<td align=\"center\" width=\"10%\">COD EMPRESA</td>\n";
		$html .= "			<td align=\"center\" width=\"40%\">DESCRIPCION FARMACIA</td>\n";*/
		$html .= "			<td align=\"center\">CANTIDAD DOCUMENTOS</td>\n";
		$html .= "			<td align=\"center\" >".$docs."</td>\n";
		$html .= "			<td align=\"center\" >VALOR TOTAL DOCUMENTOS</td>\n";
		$html .= "			<td align=\"center\" >$".Formatovalor($suma,2)."</td>\n";
		$html .= "		</tr>\n";
       /* $html .= "		<tr class=\"normal_10\">\n";*/
       /* $html .= "			<td >&nbsp;</td>\n";
        $html .= "			<td >&nbsp;</td>\n";*/
        /*$html .= "			<td class=\"label\" >VALOR TOTAL</td>\n";
        $html .= "			<td align=\"right\">$".Formatovalor($suma)."</td>\n";
        $html .= "		</tr>\n"; */
  			$html .= "	</table><br>\n";
        
        $html .= "	<table width=\"80%\" align=\"center\" style=\"border:1px solid #000000;\" rules=\"all\">\n";		
  			$html .= "		<tr class=\"label\">\n";
  			$html .= "			<td align=\"center\" width=\"10%\">COD EMPRESA</td>\n";
  			$html .= "			<td align=\"center\" width=\"40%\">DESCRIPCION FARMACIA</td>\n";
  			$html .= "			<td align=\"center\" width=\"20%\">Nª DOCUMENTOS</td>\n";
  			$html .= "			<td align=\"center\" width=\"25%\">VALOR DOCUMENTOS</td>\n";
  			$html .= "		</tr>\n";
  			$sum = 0;
  			foreach($detl as $key => $dtl)
  			{
          $suma = 0;
          foreach($dtl as $k1 => $dtl2)
          {
            $html .= "		<tr class=\"normal_10\">\n";
            $html .= "			<td >".$dtl2['empresa_id']."</td>\n";
            /*$html .= "			<td >".(($dtl2['razon_social'])? $dtl2['observacion']:"CONTRATOS")."</td>\n";*/
            $html .= "			<td >".$dtl2['razon_social']." : ".$dtl2['observacion']."</td>\n";
            $html .= "			<td >".$dtl2['prefijo']." ".$dtl2['numero']."</td>\n";
            $html .= "			<td align=\"right\">$".Formatovalor($dtl2['total_costo'],2)."</td>\n";
            $html .= "		</tr>\n";
            $suma += $dtl2['total_costo']; 
          }			
          $html .= "		<tr class=\"normal_10\">\n";
          $html .= "			<td >&nbsp;</td>\n";
          $html .= "			<td >&nbsp;</td>\n";
          $html .= "			<td class=\"label\" >TOTAL</td>\n";
          $html .= "			<td align=\"right\">$".Formatovalor($suma,2)."</td>\n";
          $html .= "		</tr>\n";
          $sum += $suma;
  			}
        $html .= "		<tr class=\"normal_10\">\n";
        $html .= "			<td >&nbsp;</td>\n";
        $html .= "			<td >&nbsp;</td>\n";
        $html .= "			<td class=\"label\" >VALOR TOTAL</td>\n";
        $html .= "			<td align=\"right\">$".Formatovalor($suma,2)."</td>\n";
        $html .= "		</tr>\n"; 
  			$html .= "	</table><br><br><br>\n";
			}
			$usuario = $nc->ObtenerInformacionUsuario($this->datos['usuario_id']);
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