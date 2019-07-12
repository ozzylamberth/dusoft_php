<?php
	/***
	* $Id: balance.report.php,v 1.1 2009/06/26 13:53:16 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
 	* @author Hugo Freddy Manrique
	*/
	IncludeClass("ConexionBD");
	IncludeClass("CarteraResumen","classes","app","Cartera");
	class balance_report extends CarteraResumen
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
	  function balance_report($datos=array())
	  {
			$this->datos=$datos;
	    return true;
	  }
		
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$ttl = "<b $estilo>BALANCE COMPROBACION DE SALDOS</b><br>";
      if($this->datos['opcion'] == 'R')
				$ttl .= "<b $estilo>VENCIMIENTOS DE CARTERA</b>";
			else if($this->datos['opcion'] == 'N')
				$ttl .= "<b $estilo>NO ENVIADA</b>";

			$sbt = "<b $estilo>Lapso :".$this->datos['anyo']."/".$this->datos['mes']."</b>";
				
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$ttl,
											  'subtitulo'=>$sbt,'logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
		}
		/**
    * FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
    *
    * @return string
    */
		function CrearReporte()
	  {
			$rst = $this->ObtenerReporteTipoEntidad($this->datos,$this->datos['opcion']);

      $html  = "<center><b class=\"label\">NO HAY CARTERA PARA MOSTRAR</b></center>\n";
			if(sizeof($rst) > 0)
			{
        $ini = $rst['inicio'];
        $fin = $rst['final'];
 
				$total = 0;
        $html  = "	<table width=\"100%\" align=\"center\" border=\"1\" cellpading=\"0\" cellspacing=\"0\" align=\"left\" $estilo>\n";
				$html .= "		<tr class=\"label\" align=\"center\">\n";
				$html .= "			<td width=\"20%\">DEUDORES</td>\n";
				$html .= "			<td width=\"20%\">SALDO INICIAL</td>\n";
				$html .= "			<td width=\"20%\">DEBITOS</td>\n";
				$html .= "			<td width=\"20%\">CREDITOS</td>\n";
				$html .= "			<td width=\"20%\">NUEVO SALDO</td>\n";
        $html .= "		</tr>\n";
				
				foreach($fin as $k1 => $dtl1)
				{
          foreach($dtl1 as $key => $dtl)
          {
            $sinicial = $ini[$k1][$key]['debitos'] -$ini[$k1][$key]['creditos'] ;
            if($sinicial != 0 || $dtl['creditos'] != 0 || $dtl['debitos'] != 0)
            {
              $totalTC = $sinicial + $dtl['debitos'] - $dtl['creditos'];
              $totalCreditos += $dtl['creditos'];
              $totalDebitos += $dtl['debitos'];
              
              if($key == "ANTICIPOS (-)")
                $totalSaldoInicial -= $sinicial;
              else
                $totalSaldoInicial += $sinicial;
              
              $html .= "		<tr height=\"23\">\n";
              $html .= "			<td valign=\"top\">".$key."&nbsp;</td>\n";
              $html .= "			<td align=\"right\" valign=\"top\">".formatoValor($sinicial)."</td>\n";
              $html .= "			<td align=\"right\" valign=\"top\">".formatoValor($dtl['debitos'])."</td>\n";
              $html .= "			<td align=\"right\" valign=\"top\">".formatoValor($dtl['creditos'])."</td>\n";
              $html .= "			<td align=\"right\" valign=\"top\">".formatoValor($totalTC)."</td>\n";
              $html .= "		</tr>\n";
            }
          }
				}
				$html .= "		<tr height=\"25\" class=\"label\">\n";
				$html .= "			<td>TOTALES</td>\n";
        $html .= "			<td align=\"right\" valign=\"top\">".formatoValor($totalSaldoInicial)."</td>\n";
        $html .= "			<td align=\"right\" valign=\"top\">".formatoValor($totalDebitos)."</td>\n";
        $html .= "			<td align=\"right\" valign=\"top\">".formatoValor($totalCreditos)."</td>\n";
        $html .= "			<td align=\"right\" valign=\"top\">".formatoValor($totalSaldoInicial + $totalDebitos - $totalCreditos)."</td>\n";
        $html .= "		</tr>\n";
				$html .= "	</table><br>\n";
        $html .= "	<br><table border='0' width=\"100%\">\n";
        $html .= "		<tr>\n";
        $html .= "			<td align=\"justify\" width=\"50%\">\n";
        $html .= "				<font size='1' face='arial'>\n";
        $html .= "					Imprimió:&nbsp;".$this->ObtenerUsuarioNombre(UserGetUID())."\n";
        $html .= "				</font>\n";
        $html .= "			</td>\n";
        $html .= "			<td align=\"right\" width=\"50%\">\n";
        $html .= "				<font size='1' face='arial'>\n";
        $html .= "					Fecha Impresión :&nbsp;&nbsp;".date("d/m/Y - h:i a")."\n";
        $html .= "				</font>\n";
        $html .= "			</td>\n";
        $html .= "		</tr>\n";
        $html .= "	</table>\n";
			}		
			
	    return $html;
		}
	}
?>