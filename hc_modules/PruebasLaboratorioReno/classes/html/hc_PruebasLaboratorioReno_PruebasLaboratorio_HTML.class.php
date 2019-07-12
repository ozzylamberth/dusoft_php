<?php
	/********************************************************************************* 
 	* $Id: hc_PruebasLaboratorioReno_PruebasLaboratorio_HTML.class.php,v 1.2 2007/02/01 20:51:01 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_ProtocolosAtencion
	* 
 	**********************************************************************************/

	class PruebasLaboratorio_HTML
	{

		function PruebasLaboratorio_HTML()
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
		
		function frmForma($pruebasLab,$consulta)
		{
			$pfj=SessionGetvar("Prefijo");
			$evolucion=SessionGetvar("Evolucion");
			$paso=SessionGetvar("Paso");

			$this->salida.= ThemeAbrirTablaSubModulo('PRUEBAS DE LABORATORIO');
			
			$this->salida.= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida.= $this->SetStyle("MensajeError");
			$this->salida.= "      </table><br>";
			
			$accion=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'PruebasLaboratorioReno'));
			
			$this->salida.="<form name=\"forma_solicitar$pfj\" action=\"$accion\" method=\"post\">";
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td>LISTA DE APOYOS</td>";
			$this->salida.="			<td>SOLICITAR</td>";
			$this->salida.="		</tr>";
			$k=0;
			
			for($i=0;$i<sizeof($pruebasLab);$i++)
			{
				if($k%2==0)
					$estilo='hc_submodulo_list_claro';	
				else
					$estilo='hc_submodulo_list_oscuro';
					
				if($pruebasLab[$i][alias])
					$descripcion=$pruebasLab[$i][alias];
				else
					$descripcion=$pruebasLab[$i][descripcion];
					
				$this->salida.="		<tr class=\"$estilo\">";
				$this->salida.="			<td><label class=\"label\">".strtoupper($descripcion)."</label></td>";
				$check="";
				for($j=0;$j<sizeof($consulta);$j++)
				{
					if($consulta[$j][cargo]==$pruebasLab[$i][cargo])
					{
						$check="checked";
						break;
					}
				}
				if(!$check)
					$this->salida.="			<td align=\"center\" width=\"15%\"><input type=\"checkbox\" name=\"apoyos".$pfj."[]\" value=\"".$pruebasLab[$i][cargo]."\"></td>";
				else
					$this->salida.="			<td align=\"center\" width=\"15%\"><img src=\"".GetThemePath()."/images/checksi.png\"></td>";
			
				$this->salida.="		</tr>";
				$k++;
			}
			$this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="   <td>&nbsp;</td>";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"solicitar$pfj\" value=\"SOLICITAR\"></td>";
			$this->salida.="	</tr>";
			$this->salida.="	</table>";
			$this->salida.="</form>";
			
			$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'AyudasEducativas'));
			$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'GraficasSeguimientoReno'));
			
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
			
			return $this->salida;
		}
		
		function SetStyle($campo)
		{
			$obj=$this->obj;
			$pfj=$obj->frmPrefijo;
			
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