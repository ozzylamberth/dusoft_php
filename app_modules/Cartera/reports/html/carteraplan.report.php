<?php
	/***
	* $Id: carteraplan.report.php,v 1.6 2009/06/26 13:53:16 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
 	* @author Hugo Freddy Manrique
	*/
	IncludeClass("ConexionBD");
	IncludeClass("CarteraRadicada","classes","app","Cartera");
	class carteraplan_report extends CarteraRadicada
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
	  function carteraplan_report($datos=array())
	  {
			$this->datos=$datos;
	    return true;
	  }
		/**
    * Funcion donde se crea el membrete o cabecera del reporte
    *
    * @return array
    */
		function GetMembrete()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			
      if($this->datos['opcion'] == 'R')
				$titulo = "<b $estilo>REPORTE DE VENCIMIENTOS RESUMIDO POR PLAN</b>";
			else if($this->datos['opcion'] == 'N')
				$titulo = "<b $estilo>REPORTE DE CARTERA NO ENVIADA - RESUMIDO POR PLAN</b>";

      
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}
		/**
    * FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
    *
    * @return string
    */
		function CrearReporte()
	  {
      $this->datos['fecha'] = date("d/m/Y");
      $rst = $this->ObtenerReportePlanes($this->datos,$this->datos['opcion'],"P");
      
			$Clientes = $rst['cartera'];
			$intervalos =  $rst['intervalos'];
			$total_cartera =  $rst['total_cartera'];		

			$html  = "			<center><b class=\"label\">NO HAY CARTERA PARA MOSTRAR</b></center>\n";

			if(sizeof($Clientes) > 0)
			{
				$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
				$prc = sizeof($intervalos)*100 + 350;
				$html  = "	<table border=\"1\" width=\"".$prc."\" align=\"center\" cellpading=\"4\" cellspacing=\"0\" align=\"left\" $estilo>\n";
				$html .= "		<tr align=\"center\">\n";
				$html .= "			<td  width=\"150\"><b>PLAN</b></td>\n";
				foreach($intervalos as $key => $intvl)
				{
					$html .= "			<td width=\"100\" align=\"center\" ><b>";
					switch($key)
					{
						case '13': $html .= "Mas de 360"; break;
						case '6': $html .= "151 a 180"; break;
						case '5': $html .= "121 a 150"; break;
						case '4': $html .= "91 a 120"; break;
						case '3': $html .= "61 a 90"; break;
						case '2': $html .= "31 a 60"; break;
						case '1': $html .= "0 a 30"; break;
						case '0': $html .= "Corriente"; break;
            default: $html .= "181 a 360"; break;
					}
					$html .= "			</b></td>\n";
				}
				$html .= "			<td width=\"100\" ><b>TOTAL</b></td>\n";
				$html .= "			<td width=\"100\" ><b>PORCENT</b></td>\n";
				$html .= "		</tr>\n";
				
				$saldo = $total = 0;
				
        foreach($Clientes as $keyA => $planes)
				{
          $html1 = "";
          foreach($planes as $key => $cartera)
          {
            $html1 .= "		<tr class=\"normal_10\">\n";
            $html1 .= "			<td ><b>".$keyA." ".$key."</b></td>\n";
  					$tl = 0;
  					foreach($intervalos as $keyI => $intvl)
            {
              $html1 .= "			<td align=\"right\" valign=\"top\">".formatoValor($cartera[$keyI]['total_intervalo'])."</td>\n";
  						$tl += $cartera[$keyI]['total_intervalo'];
  					}
  					$saldo += $tl;
  					$porcentaje = ($tl/$total_cartera)*100;;
  					
  					$html1 .= "			<td align=\"right\" valign=\"top\">".formatoValor($tl)."</td>\n";
  					$html1 .= "			<td align=\"right\" valign=\"top\">".formatoValor($porcentaje,2)."</td>\n";          
  					$html1 .= "		</tr>\n";
            
            if($tl == 0) $html1 = "";
            $html .= $html1;
          }
				}
        
        $html .= "		<tr height=\"35\">\n";
				$html .= "			<td valign=\"bottom\" colspan=\"".(sizeof($intervalos)+1)."\"><b>TOTAL</b></td>\n";
				$html .= "			<td align=\"right\" valign=\"bottom\" ><b>".formatoValor($saldo)." </b></td>\n";
				$html .= "			<td align=\"right\" valign=\"bottom\" >&nbsp;</td>\n";
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