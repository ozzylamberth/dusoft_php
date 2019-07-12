<?php
	/********************************************************************************* 
 	* $Id: hc_AyudasEducativas_Ayudas_HTML.class.php,v 1.2 2007/02/01 20:44:14 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_AyudasEducativas
	* 
 	**********************************************************************************/
	require_once("classes/fpdf/html_class.php");
	include_once("classes/fpdf/conversor.php");
	include_once("classes/fpdf/fpdf.php");
	
	class Ayudas_HTML
	{
		function Ayudas_HTML()
		{
			$this->redcolorf="#990000";
			return true;
		}
		
		function frmHistoria()
		{
			$this->salida="";
			return $this->salida;
		}
		
		function frmConsulta()
		{
			return true;
		}

		function frmForma($ayudasPro,$ayudasPa,$semana_gestante=null,$fcp=null)
		{
			$pfj=SessionGetVar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$paso=SessionGetVar("Paso");
			$programa=SessionGetVar("Programa");
			
			$this->salida.= ThemeAbrirTablaSubModulo('AYUDAS MEMORIAS');
			
			if($this->ban==1)
			{
				$this->salida.= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida.= $this->SetStyle("MensajeError");
				$this->salida.= "      </table><br>";
			}
			
			if(SessionGetVar("cpn"))
				$this->frmGestacion($semana_gestante,$fcp);
			
			$k=0;
			
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="<tr height=\"100%\">";
			for($i=0;$i<sizeof($ayudasPro);$i++)
			{
				if($k==2)
				{
					$k=0;
					$this->salida.="<tr>";
				}
				
				if($k<2)
				{
					if(!$ayudasPro[$i][sw_column])
					{
						$this->salida.="<td height=\"100%\" width=\"50%\">";
						$this->salida.="	<table align=\"center\" border=\"0\" width=\"100%\" height=\"100%\">";
						$this->salida.="		<tr class=\"modulo_table_list_title\">";
						$this->salida.="			<td align=\"left\" height=\"5%\"><label>".$ayudasPro[$i][tema]."</label></td>";
						$this->salida.="		</tr>";
						$this->salida.="		<tr class=\"hc_submodulo_list_claro\">";
						$this->salida.="			<td valign=\"top\">";
						$this->salida.="				<table align=\"center\" border=\"0\" width=\"100%\">";
						$this->salida.="					<tr class=\"label\">";
						$this->salida.="						<td>";
						$this->salida.=								$ayudasPro[$i][contenido];
						$this->salida.="						</td>";
						$this->salida.="					</tr>";
						$this->salida.="				</table>";
						$this->salida.="			</td>";
						$this->salida.="		</tr>";
						$this->salida.="	</table>";
						$this->salida.="</td>";
					
						$k++;
					}
				}
				
				if($k==2)
					$this->salida.="</tr>";
			}
			$this->salida.="	</table><br>";
			
			$programa2=ModuloGetVar('hc_submodulo','AtencionPlanFliar','PF');
			
			if($programa==$programa2)
			{
				$b=true;
				$capas="var capas1 = new Array(";
				for($i=0;$i<sizeof($ayudasPro);$i++)
				{
					if($ayudasPro[$i][sw_column])
					{
						$this->salida.="	<table align=\"center\" border=\"0\" width=\"100%\">";
						$this->salida.="		<tr class=\"modulo_table_list_title\">";
						$this->salida.="			<td align=\"center\" height=\"5%\" width=\"2%\"><a href=\"javascript:showhide1('capa$i')\"><div id=\"imgC$i\"><img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\"></div></a></td>";
						$this->salida.="			<td align=\"left\" height=\"5%\" width=\"98%\">".$ayudasPro[$i][tema]."</td>";
						$this->salida.="		</tr>";
						$this->salida.="		<tr class=\"hc_submodulo_list_claro\">";
						$this->salida.="			<td id=\"capa$i\" valign=\"top\" style=\"display:none\" colspan=\"2\">";
						$this->salida.="				<table align=\"center\" border=\"0\" width=\"100%\">";
						$this->salida.="					<tr class=\"label\">";
						$this->salida.="						<td>";
						$this->salida.=								$ayudasPro[$i][contenido];
						$this->salida.="						</td>";
						$this->salida.="					</tr>";
						$this->salida.="				</table>";
						$this->salida.="			</td>";
						$this->salida.="		</tr>";
						$this->salida.="	</table>";
						
						$b? $capas.="'capa$i'":$capas.=",'capa$i'";
						$b=false;
					}
				}
				$this->salida.="	<br>";
			}
			
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"90%\">AYUDAS MEMORIAS PARA PACIENTES</td>";
			$this->salida.="			<td width=\"10%\">SELECCIONE</td>";
			$this->salida.="		</tr>";
			$k=0;
			$dir_upload=ModuloGetVar('app','Promocion_y_PrevencionAdmin','ruta_archivo');
			for($i=0;$i<sizeof($ayudasPa);$i++)
			{
				if($k%2==0)
					$estilo='hc_submodulo_list_claro';	
				else
					$estilo='hc_submodulo_list_oscuro';	

				$ruta=explode("/",$ayudasPa[$i][nombre_archivo]);
				$dir=GetBaseURL().$dir_upload.$ruta[sizeof($ruta)-1];
				
				$this->salida.="		<tr class=\"$estilo\">";
				$this->salida.="			<td>".$ayudasPa[$i][tema]."</td>";
				$this->salida.="			<td align=\"center\"><a href=\"$dir\" target=\"_blank\"> <img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\"></a></td>";	
				$this->salida.="		</tr>";

				$k++;
			}
			
			$this->salida.="	</table>";

			$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'ProtocolosAtencion'));
			if($pfj=="frm_AtencionCPN")
				$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'CronogramaCitasyProcedimientos'));
			elseif($pfj=="frm_AtencionReno")
				$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'PruebasLaboratorioReno'));
			elseif($pfj=="frm_AtencionPlanFliar")
				$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'GraficasSeguimientoPFliar'));
				
			$this->salida.="<table align=\"center\" cellspacing=\"20\">";
			$this->salida.="	<tr>";
			$this->salida.="	<form name=\"formasig$pfj\" action=\"$accion1\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"siguiente$pfj\" value=\"SIGUIENTE\"></td>";
			$this->salida.="	</form>";
			$this->salida.="	<form name=\"formavolver$pfj\" action=\"$accion2\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER\"></td>";
			$this->salida.="	</form>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			
			$this->salida.=ThemeCerrarTablaSubModulo();
			
			$this->salida .= "<script language=\"javascript\">\n";
			$this->salida .= " ".$capas.");\n";
			
			$this->salida .= "	var v_actual=0; \n";
			
			$this->salida .= "	function showhide1(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		for(i=0; i<capas1.length; i++)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(capas1[i]);\n";
			$this->salida .= "			if(capas1[i] != Seccion)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				if(e.style.display == \"none\")\n";
			$this->salida .= "				{\n";
			$this->salida .= "					e.style.display = \"\";\n";
			$this->salida .= "				}\n";
			$this->salida .= "				else \n";
			$this->salida .= "				{\n";
			$this->salida .= "					e.style.display = \"none\";\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "</script>\n";
			
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