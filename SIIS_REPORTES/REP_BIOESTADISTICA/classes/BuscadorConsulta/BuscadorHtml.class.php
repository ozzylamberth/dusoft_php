<?php
	/**************************************************************************************
	* $Id: BuscadorHtml.class.php,v 1.3 2006/05/03 12:23:21 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	**************************************************************************************/
	
	// $VISTA='HTML';
	// $_ROOT='../../';
	// include $_ROOT.'includes/enviroment.inc.php';
	
	class BuscadorHtml
	{
		var $Sql;
		var $buscador;
		var $nombreforma;
		var $HtmlBuscador;
		var $titulocampos = array();
		var $nombrecampos = array();
		var $nombrecampossql = array();
		
		function BuscadorHtml()
		{
			return true;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function ArmarBuscador()
		{	
			$this->buscador = new BuscadorConsulta();
			$this->Sql = $_REQUEST['buscador'];
			$this->nombreforma = $_REQUEST['forma'];
			$this->nombrecampos = $_REQUEST['campos'];
			$this->nombrecampossql = $_REQUEST['campossql'];
			$this->titulocampos = $_REQUEST['titulo'];
			
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px\"";
			
			$salida .= ReturnHeader('Buscador');
      $salida .= ReturnBody()."<br>\n";
			$salida .= ThemeAbrirTabla("");
			$salida .= "	<script>\n";
			$salida .= "		function Guardar(objeto)\n";
			$salida .= "		{\n";
			$salida .= "			var cadena = objeto.split('?');\n";
			for($i=0; $i<sizeof($this->nombrecampos); $i++)
			{
				$salida .= "			window.opener.document.".$this->nombreforma.".".$this->nombrecampos[$i].".value = cadena[$i];\n";
				$cadena .= "&campos[$i]=".$this->nombrecampos[$i]."";
			}
			for($i=0; $i<sizeof($this->nombrecampossql);$i++)			
				$cadena .= "&campossql[$i]=".$this->nombrecampossql[$i]."";
			
			for($i=0;$i<sizeof($this->titulocampos);$i++)
				$cadena .= "&titulo[$i]=".$this->titulocampos[$i]."";
			
			$datos = $this->buscador->ObtenerSql($this->Sql);	
			
			$action  = "BuscadorHtml.class.php?buscador=".$this->Sql."&forma=".$this->nombreforma;
			$action .= $cadena.$this->buscador->adicional;
			
			$salida .= "			Cerrar();\n";
			$salida .= "		}\n";
			$salida .= "		function Cerrar()\n";
			$salida .= "		{\n";
			$salida .= "			window.close();\n";
			$salida .= "		}\n";
			$salida .= "		function mOvr(src,clrOver)\n";
			$salida .= "		{\n";
			$salida .= "			src.style.background = clrOver;\n";
			$salida .= "		}\n";
			$salida .= "		function mOut(src,clrIn)\n";
			$salida .= "		{\n";
			$salida .= "			src.style.background = clrIn;\n";
			$salida .= "		}\n";
			$salida .= "	</script>\n";
			$salida .= "	<table width=\"90%\" align=\"center\" $estilo>\n";		
			$salida .= "		<tr>\n";
			$salida .= "			<td align=\"center\">\n";
			$salida .= "				<form name=\"buscador\" action=\"".$action."\" method=\"post\">\n";
			$salida .= "					<fieldset><legend class=\"field\">BUSCADOR</legend>\n";
			$salida .= "						".$this->HtmlBuscador($this->Sql)."\n";
			$salida .= "					</fieldset>\n";
			$salida .= "				</form>\n";
			$salida .= "			</td>\n";
			$salida .= "		</tr>\n";
			$salida .= "	</table><br>\n";
			
			if($datos)
			{
				$salida .= "	<table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$salida .= "			<tr class=\"modulo_table_list_title\" height=\"19\">\n";
				for($i=0; $i<sizeof($this->titulocampos);$i++)
				{
					$salida .= "				<td align=\"center\" width=\"%\"><b><font style=\"color:white\">".$this->titulocampos[$i]."</font></b></td>\n";
				}
				$salida .= "				<td width=\"3%\" ><b><font style=\"color:white\">OPCIONES</font></b></td>\n";
				$salida .= "			</tr>";
				for($i=0; $i< sizeof($datos); $i++ )
				{
					if($i % 2 == 0)
						$background = "#CCCCCC";
					else
						$background = "#DDDDDD";
					
					$Celdas = $datos[$i];
					
					$cadena = "";					
					$salida .= "			<tr bgcolor=\"".$background."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF'); class=\"label\">\n";
					
					for($j=0; $j<sizeof($this->nombrecampossql); $j++)
					{
						$salida .= "				<td align=\"left\"   >".$Celdas[$this->nombrecampossql[$j]]."</td>\n";
						$cadena .= $Celdas[$this->nombrecampossql[$j]]."?";
					}
					$opcion  = "	<a class=\"label_error\" href=\"javascript:Guardar('".$cadena."')\" title=\"SELECCIONAR\">\n";
					$opcion .= "	<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a>\n";
					
					$salida .= "				<td align=\"center\" >$opcion</td>\n";						
					$salida .= "			</tr>\n";
				}
				$this->salida .= "	</table><br><br>\n";
									
				$Paginador = new ClaseHTML();
				$salida .= "		".$Paginador->ObtenerPaginado($this->buscador->conteo,$this->buscador->paginaActual,$action);
				$salida .= "		<br>\n";
			}
		
			$salida .= "	<table width=\"90%\" align=\"center\" $estilo>\n";
			$salida .= "		<tr>\n";
			$salida .= "			<td align=\"center\">\n";
			$salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Cerrar\" onclick=\"Cerrar()\" >\n";
			$salida .= "			</td>\n";
			$salida .= "		</tr>\n";
			$salida .= "	</table>\n";
			$salida .= ThemeCerrarTabla();
			
			return $salida;
		}
		/*********************************************************************************
		*
		**********************************************************************************/
		function HtmlBuscador($opcion)
		{
			$buscador = "";
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px\"";
			switch($opcion)
			{
				case '0':
					$buscador .= "	<script>\n";
					$buscador .= "		function limpiarCampos(objeto)\n";
					$buscador .= "		{\n";
					$buscador .= "			objeto.nombre_tercero.value = \"\";\n";
					$buscador .= "			objeto.tercero_id.value = \"\";\n";
					$buscador .= "			objeto.tipo_id_tercero.selectedIndex='0';\n";
					$buscador .= "		}\n";
					$buscador .= "	</script>\n";
					$buscador .= "		<table $estilo>\n";
					$buscador .= "			<tr>\n";
					$buscador .= "				<td class=\"label\">DOCUMENTO</td>\n";
					$buscador .= "				<td>\n";
					$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"tercero_id\" size=\"30\" maxlength=\"32\" value=\"".$this->TerceroDocumento."\">\n";
					$buscador .= "				</td>\n";
					$buscador .= "			</tr>\n";
					$buscador .= "			<tr>\n";
					$buscador .= "				<td class=\"label\">NOMBRE</td>\n";
					$buscador .= "				<td>\n";
					$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"nombre_tercero\" size=\"30\" maxlength=\"100\" value=\"".$this->TerceroNombre."\">\n";
					$buscador .= "				</td>\n";
					$buscador .= "			</tr>\n";
					$buscador .= "			<tr>\n";
					$buscador .= "				<td class=\"label\" align=\"center\" colspan=\"2\"><br>\n";
					$buscador .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
					$buscador .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
					$buscador .= "				</td>\n";
					$buscador .= "			</tr>\n";
					$buscador .= "		</table>\n";
				break;
				case 'diagnosticos':

					$buscador .= "		<table $estilo>\n";
					$buscador .= "			<tr>\n";
					$buscador .= "				<td class=\"label\">CODIGO</td>\n";
					$buscador .= "				<td>\n";
					$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"diagnostico_id\" size=\"30\" maxlength=\"32\" value=\"".$this->TerceroDocumento."\">\n";
					$buscador .= "				</td>\n";
					$buscador .= "			</tr>\n";
					$buscador .= "			<tr>\n";
					$buscador .= "				<td class=\"label\">DESCRIPCION</td>\n";
					$buscador .= "				<td>\n";
					$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"diagnostico_nombre\" size=\"30\" maxlength=\"100\" value=\"".$this->TerceroNombre."\">\n";
					$buscador .= "				</td>\n";
					$buscador .= "			</tr>\n";
					$buscador .= "			<tr>\n";
					$buscador .= "				<td class=\"label\" align=\"center\" colspan=\"2\"><br>\n";
					$buscador .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
					$buscador .= "				</td>\n";
					$buscador .= "			</tr>\n";
					$buscador .= "		</table>\n";

				break;
			}
			return $buscador;
		}
	}
	
	$VISTA='HTML';
	$_ROOT='../../';
	include $_ROOT.'includes/enviroment.inc.php';
	IncludeClass("ClaseHTML");
	IncludeClass("BuscadorConsulta");	
	
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	IncludeFile($fileName);

	$buscador = new BuscadorHtml();
	echo $buscador->ArmarBuscador();
?>