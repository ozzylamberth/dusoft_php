<?php
	/********************************************************************************* 
 	* $Id: hc_InscripcionReno_Renopro_HTML.class.php,v 1.2 2007/02/01 20:50:16 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_InscripcionCPN_Inscripcion_HTML
	* 
 	**********************************************************************************/
	
	class Renopro_HTML
	{

		function Renopro_HTML()
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
			return true;
		}

		function frmRenoproteccion($apoyosI,$signos,$sw_inscrito,$consulta)
		{
			$pfj=SessionGetVar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$paso=SessionGetVar("Paso");
			
			$this->salida.= ThemeAbrirTablaSubModulo('INSCRIPCION AL PROGRAMA RENOPROTECCION');
			
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
			
			$op1='';$op2='';
			if($sw_inscrito)
				$op1='disabled';
			else
				$op2='disabled';
			
			$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'InscripcionReno'));
			
			$this->salida.="<form name=\"formains$pfj\" action=\"$accion1\" method=\"post\">";
			
			$this->salida.="	<table  align=\"center\" border=\"0\"  width=\"100%\" cellpadding=\"0\" cellspacing=\"2\">";
			$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
			if(sizeof($signos)>0)
			{
				$ta_baja=$signos[0][tabaja];
				$ta_alta=$signos[0][taalta];
				$peso=$signos[0][peso];
			}
			else
			{
				$ta_baja=$_REQUEST['ta_baja'.$pfj];
				$ta_alta=$_REQUEST['ta_alta'.$pfj];
				$peso=$_REQUEST['peso'.$pfj];
				$this->salida.="<input type=\"hidden\" name=\"bandera$pfj\" value=\"1\">";
			}
			$style1="";
			if(!empty($ta_alta) AND $ta_alta>139)
				$style1="style=\"color:#990000;font-weight : bold; \"";
			
			$style2="";
			if(!empty($ta_baja) AND $ta_baja<55)
				$style2="style=\"color:#990000;font-weight : bold; \"";
				
			
			$check1="";$check2="";
			if($_REQUEST['diabetes'.$pfj])
				$check1="checked";

			if($_REQUEST['hta'.$pfj])
				$check2="checked";
			
			$this->salida.="			<td align=\"left\"><label class=\"".$this->SetStyle("ta")."\" width=\"25%\">PRESION ARTERIAL</label></td>";
			$this->salida.="			<td align=\"left\"><input type=\"text\" class=\"input-text\" $style1 name=\"ta_alta$pfj\" size=\"5\" maxlength=\"3\" value=\"$ta_alta\"> / <input type=\"text\" class=\"input-text\" $style2 name=\"ta_baja$pfj\" size=\"5\" maxlength=\"3\" value=\"$ta_baja\">";
			$this->salida.="			<td align=\"left\"><label class=\"".$this->SetStyle("peso")."\" width=\"25%\">PESO</label><input type=\"text\" class=\"input-text\" name=\"peso$pfj\" size=\"8\" maxlength=\"6\" value=\"$peso\"></td>";
			$this->salida.="			<td align=\"left\"><label width=\"25%\">RECIBE TRATAMIENTO DIABETES</label><input type=\"checkbox\" name=\"diabetes$pfj\" value=\"true\" $check1></td>";
			$this->salida.="			<td align=\"left\"><label width=\"25%\">RECIBE TRATAMIENTO HTA</label><input type=\"checkbox\" name=\"hta$pfj\" value=\"true\" $check2></td>";
			
			$this->salida.="		</tr>";
			$this->salida.="		<tr>";
			$this->salida.=" 			<td colspan=\"5\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Inscribir$pfj\" value=\"INSCRIBIR\" $op1></td>";
			$this->salida.="  	</tr>";
			$this->salida.=" </table>";
			$this->salida.="</form>";
			
			
			if(sizeof($apoyosI) > 0)
			{
				$accion=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'InscripcionReno'));
				$this->salida.="<br>";
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
					$ban=0;
					for($j=0;$j<sizeof($consulta);$j++)
					{
						if($apoyosI[$i][cargo]==$consulta[$j][cargo])
						{
							$this->salida.="   <td align=\"center\" width=\"10%\"><input type=\"checkbox\" name=\"apoyos".$pfj."[]\" value=\"".$apoyosI[$i][cargo]."\" checked></td>";
							$ban=1;
							break;
						}
					}
					if($ban==0)
						$this->salida.="   <td align=\"center\" width=\"10%\"><input type=\"checkbox\" name=\"apoyos".$pfj."[]\" value=\"".$apoyosI[$i][cargo]."\"></td>";
					
					$this->salida.="  </tr>";
					$k++;
				}
				$this->salida.="  <tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="   <td align=\"right\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"solicitar$pfj\" value=\"SOLICITAR\"></td>";
				$this->salida.="  </tr>";
				$this->salida.=" </table>";
				$this->salida.="</form>";
			}
			else
			{
				$this->frmError["MensajeError"]="NO HAY APOYOS DIAGNOSTICOS REALIZACIONADOS";
				$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= " </table>";
			}
			
			$this->salida.="<br>";
			
			$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'AtencionReno','Iniciar'=>'1'));
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