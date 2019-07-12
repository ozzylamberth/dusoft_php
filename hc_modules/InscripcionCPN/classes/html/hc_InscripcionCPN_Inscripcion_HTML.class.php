<?php
	/********************************************************************************* 
 	* $Id: hc_InscripcionCPN_Inscripcion_HTML.class.php,v 1.3 2007/02/01 20:55:52 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_InscripcionCPN_Inscripcion_HTML
	* 
 	**********************************************************************************/
	
	class Inscripcion_HTML
	{
		function Inscripcion_HTML()
		{
			return true;
		}
		
		function frmHistoria()
		{
			$this->salida="";
			return $this->salida;
		}
		
		function frmConsulta()
		{
			$this->salida="";
			return $this->salida;
		}

		function frmForma($apoyosI,$programa,$sw_inscrito,$semana_gestante=null,$fcp=null,$consulta)
		{
			$evolucion=SessionGetvar("Evolucion");
			$programa=SessionGetvar("Programa");
			$inscripcion=SessionGetvar("Inscripcion_$programa");
			$pfj=SessionGetVar("Prefijo");
			$paso=SessionGetVar("Paso");
			
			$op1="";$op2="";
			
			if($sw_inscrito)
				$op1="disabled";
			else
				$op2="disabled";
			
			$this->salida.= ThemeAbrirTablaSubModulo('INSCRIPCION AL PROGRAMA CPN');

			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
			
			if($sw_inscrito)
			{
				$cpn=SessionGetVar("cpn");
				$this->frmGestacion($semana_gestante,$fcp);
				$this->salida.="<br>";
			}
			
			$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'InscripcionCPN'));
			
			$this->salida.="<form name=\"formains$pfj\" action=\"$accion1\" method=\"post\">";
			$this->salida.=" <table  align=\"center\" border=\"0\"  width=\"100%\" cellpadding=\"0\" cellspacing=\"2\">";
			$this->salida.="  <tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="   <td align=\"left\"><label class=\"".$this->SetStyle("fum")."\" width=\"25%\">FECHA ULTIMA MESTRUACION:</label><input type=\"text\" class=\"input-text\" name=\"fum$pfj\" size=\"8\" maxlength=\"10\" value=\"".$_REQUEST['fum'.$pfj]."\"><sub>".ReturnOpenCalendario("formains$pfj","fum$pfj","-")."</sub></td>";
			$this->salida.="   <td align=\"left\"><label class=\"".$this->SetStyle("fup")."\" width=\"25%\">FECHA ULTIMO PARTO:</label><input type=\"text\" class=\"input-text\" name=\"fup$pfj\" size=\"8\" maxlength=\"10\" value=\"".$_REQUEST['fup'.$pfj]."\"><sub>".ReturnOpenCalendario("formains$pfj","fup$pfj","-")."</sub></td>";
			$this->salida.="   <td align=\"left\"><label class=\"".$this->SetStyle("fpp")."\" width=\"25%\">FECHA PRIMER PARTO:</label><input type=\"text\" class=\"input-text\" name=\"fpp$pfj\" size=\"8\" maxlength=\"10\" value=\"".$_REQUEST['fpp'.$pfj]."\"><sub>".ReturnOpenCalendario("formains$pfj","fpp$pfj","-")."</sub></td>";
			$this->salida.="  </tr>";
			$this->salida.="  <tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="   <td colspan=\"3\" align=\"right\"><label class=\"".$this->SetStyle("num_previos")."\" width=\"25%\"> NUMERO DE EMBARAZOS PREVIOS: </label><input type=\"text\" class=\"input-text\" name=\"num_previos$pfj\" size=\"5\" maxlength=\"2\" value=\"\"></td>";
			$this->salida.="  </tr>";
			$this->salida.=" </table><br>";
			$this->salida.=" <input class=\"input-submit\" type=\"hidden\" name=\"programa$pfj\" value=\"$programa\">";
			$this->salida.=" <input class=\"input-submit\" type=\"hidden\" name=\"$apoyod\" value=\"$apoyos\">";
			$this->salida.=" <table align=\"center\" border=\"0\" width=\"50%\">";
			$this->salida.="	<tr>";
			$this->salida.=" 		<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Inscribir$pfj\" value=\"INSCRIBIR\" $op1></td>";
			$this->salida.="  </tr>";
			$this->salida.=" </table>";
			$this->salida.="</form>";
			$this->salida.="<br>";
			
			if(sizeof($apoyosI) > 0)
			{
				$accion=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'InscripcionCPN'));
				
				$this->salida.="<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
				$this->salida.=" <table align=\"center\" border=\"0\"  width=\"100%\">";
				$this->salida.="  <tr class=\"modulo_table_list_title\">";
				$this->salida.="   <td align=\"center\">LISTA DE APOYOS DIAGNOSTICOS RELACIONADOS</td>";
				$this->salida.="   <td align=\"center\">SOLICITAR</td>";
				$this->salida.="  </tr>";
				$k=0;
				
				for($i=0;$i<sizeof($apoyosI);$i++)
				{
					if($k%2==0)
						$estilo='hc_submodulo_list_claro';
					else
						$estilo='hc_submodulo_list_oscuro';
						
					$this->salida.="  <tr class=\"$estilo\">";
					if(empty($apoyosI[$i][alias]))
						$descripcion=$apoyosI[$i][descripcion];
					else
						$descripcion=$apoyosI[$i][alias];
					$this->salida.="   <td><label class=\"label\">".strtoupper($descripcion)."</label></td>";
					$this->salida.="<input type=\"hidden\" name=\"programa$pfj\" value=\"$programa\">";
					$apoyod='apoyos'.$pfj.'[]';
					$ban1=0;
					for($j=0;$j<sizeof($consulta);$j++)
					{
						if($apoyosI[$i][cargo]==$consulta[$j][cargo])
						{
							$ban1=1;
							break;
						}
					}
					if($ban1==1)
					{
						$this->salida.="   <td align=\"center\" width=\"10%\"><input type=\"checkbox\" name=\"$apoyod\" value=\"".$apoyosI[$i][cargo]."\" checked></td>";
					}
					else
					{
						if($_REQUEST[$apoyod])
						{
							$this->salida.="   <td align=\"center\" width=\"10%\"><input type=\"checkbox\" name=\"$apoyod\" value=\"".$apoyosI[$i][cargo]."\" checked></td>";
						}
						else
						{
							$this->salida.="   <td align=\"center\" width=\"10%\"><input type=\"checkbox\" name=\"$apoyod\" value=\"".$apoyosI[$i][cargo]."\"></td>";
						}
					}
					
					$this->salida.="  </tr>";
					$k++;
				}
			}
			
			$this->salida.="  <tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="   <td align=\"right\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"solicitar$pfj\" value=\"SOLICITAR\"></td>";
			$this->salida.="  </tr>";
			$this->salida.=" </table>";
			$this->salida.="</form>";
			$this->salida.="<br>";
			
			$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'AtencionCPN','Iniciar'=>1));
			$accion3=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>''));
			
			$this->salida.=" <table align=\"center\" border=\"0\" width=\"50%\">";
			$this->salida.="	<tr>";
			$this->salida.="	<form name=\"formaini$pfj\" action=\"$accion2\" method=\"post\">";
			$this->salida.="		<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"IniciarAtencion$pfj\" value=\"INICIAR ATENCION\" $op2></td>";
			$this->salida.="	</form>";
			$this->salida.="	<form name=\"formavolver$pfj\" action=\"$accion3\" method=\"post\">";
			$this->salida.="		<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER\"></td>";
			$this->salida.="  </tr>";
			$this->salida.="</form>";
			$this->salida.=" </table>";
			
			
			$this->salida.=ThemeCerrarTablaSubModulo();
			
			return $this->salida;
		}
		
		function frmGestacion($semana,$fecha)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\" cellpadding=\"0\" cellspacing=\"2\">";
			$this->salida.="	<tr>";
			$this->salida.="		<td width=\"25%\" class=\"modulo_table_list_title\">SEMANA DE GESTACION</td>";
			$this->salida.="		<td width=\"25%\" class=\"modulo_table_list_title\">FECHA PROBLABLE DE PARTO</td>";
			$this->salida.="  </tr>";
			$this->salida.="	<tr>";
			$this->salida.="		<td width=\"25%\" class=\"hc_table_submodulo_list_title\"><label class=\"label\">$semana</label></td>";
			$this->salida.="		<td width=\"25%\" class=\"hc_table_submodulo_list_title\"><label class=\"label\">$fecha</label></td>";
			$this->salida.="  </tr>";
			$this->salida.="</table><br>";
			return true;
		}
		
		function SetStyle($campo)
		{
			if ($this->frmError[$campo]||$campo=="MensajeError")
			{
				if ($campo=="MensajeError")
				{
					return ("<tr><td align=\"center\" class=\"label_error\">".$this->frmError["MensajeError"]."</td></tr>");
				}
				return ("label_error");
			}
			return ("label");
		}
	}
?>