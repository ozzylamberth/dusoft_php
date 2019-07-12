<?php
	/********************************************************************************* 
 	* $Id: hc_ProtocolosAtencion_Protocolos_HTML.class.php,v 1.2 2007/02/01 20:50:52 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_ProtocolosAtencion
	* 
 	**********************************************************************************/

	class Protocolos_HTML
	{

		function Protocolos_HTML()
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
		
		function frmForma($ProtocolosAtencion,$semana_gestante=null,$fcp=null)
		{
			$pfj=SessionGetVar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$paso=SessionGetVar("Paso");

			$this->salida.= ThemeAbrirTablaSubModulo('PROTOCOLOS DE ATENCION');
			
			if($this->ban==1)
			{
				$this->salida.= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida.= $this->SetStyle("MensajeError");
				$this->salida.= "      </table><br>";
			}
			
			if(SessionGetVar("cpn"))
				$this->frmGestacion($semana_gestante,$fcp);

			$this->salida.="	<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td>PROTOCOLOS DE ATENCION</td>";
			$this->salida.="		</tr>";
			$k=0;
			
			for($i=0;$i<sizeof($ProtocolosAtencion);$i++)
			{
				if($k%2==0)
					$estilo='hc_submodulo_list_claro';	
				else
					$estilo='hc_submodulo_list_oscuro';
					
				$this->salida.="		<tr class=\"$estilo\">";
				$this->salida.="			<td><a href=\"".$ProtocolosAtencion[$i][url]."\" target=\"_blank\">".$ProtocolosAtencion[$i][nombre]."</td>";
				$this->salida.="		</tr>";
				$k++;
			}
			$this->salida.="	</table>";
			$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'AyudasEducativas'));
			$accion2=ModuloHCGetURL($evolucion,-1,0,'',false);
			
			$this->salida.="<table align=\"center\" cellspacing=\"20\">";
			$this->salida.="	<tr>";
			$this->salida.="	<form name=\"formavolver$pfj\" action=\"$accion1\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER\"></td>";
			$this->salida.="	</form>";
			$this->salida.="	<form name=\"formavolver$pfj\" action=\"$accion2\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER A PRINCIPAL\"></td>";
			$this->salida.="	</form>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			
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