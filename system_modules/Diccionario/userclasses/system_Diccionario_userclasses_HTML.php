
<?php

/**
* Modulo de Diccionario (PHP).
*
//*
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* system_Diccionario_userclasses_HTML.php
*
//*
**/

class system_Diccionario_userclasses_HTML extends system_Diccionario_user
{
	function system_Diccionario_userclasses_HTML()
	{
		$this->system_Diccionario_user(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	function AbrirTablas()//Muestra la cabecera de la forma
	{
		global $ConfigDB;
		$this->salida .= ThemeAbrirTabla('DICCIONARIO DE DATOS '._SIIS_APLICATION_TITLE .'  (SERVIDOR : '.$ConfigDB['dbhost'].' -  DATABASE : '.$ConfigDB['dbname'].')');
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td width=\"100%\">";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"100%\" align=\"center\">";
		$this->salida .= "  <fieldset><legend class=\"field\">BÚSQUEDA AVANZADA</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"20%\" align=\"center\" class=\"label\">TABLA:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"60%\" align=\"center\">";
		$accion=ModuloGetURL('system','Diccionario','user','BuscarTablas');
		$this->salida .= "      <form name=\"formatabla\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"NomTablaDic\" value=\"".$_POST['NomTablaDic']."\" maxlength=\"30\" size=\"30\">";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"20%\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"buscatabla\" value=\"BUSCAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"20%\" align=\"center\" class=\"label\">CAMPO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"60%\" align=\"center\">";
		$accion=ModuloGetURL('system','Diccionario','user','BuscarCampos');
		$this->salida .= "      <form name=\"formacampo\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"NomCampoDic\" value=\"".$_POST['NomCampoDic']."\" maxlength=\"30\" size=\"30\">";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"20%\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"buscacampo\" value=\"BUSCAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"20%\" align=\"center\" class=\"label\">COMENTARIO:";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"60%\" align=\"center\">";
		$accion=ModuloGetURL('system','Diccionario','user','BuscarComentario');
		$this->salida .= "      <form name=\"formacomen\" action=\"$accion\" method=\"post\">";
		$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"NomComenDic\" value=\"".$_POST['NomComenDic']."\" maxlength=\"30\" size=\"30\">";
		$this->salida .= "      </td>";
		$this->salida .= "      <td width=\"20%\" align=\"center\">";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"buscacomen\" value=\"BUSCAR\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </form>";
		$this->salida .= "      </tr>";
		if($_SESSION['editar']==1)
		{
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			$this->salida .= "      <td width=\"20%\" align=\"center\" class=\"label\">COMENTAR LLAVES:";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"60%\" align=\"center\">";
			$accion=ModuloGetURL('system','Diccionario','user','ImprimirComments');
			$this->salida .= "      <form name=\"campos\" action=\"$accion\" method=\"post\">";
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"NomCampo\" maxlength=\"30\" size=\"30\">";
			$this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"NomComen\" maxlength=\"30\" size=\"30\">";
			$this->salida .= "      </td>";
			$this->salida .= "      <td width=\"20%\" align=\"center\">";
			$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
			$this->salida .= "      </td>";
			$this->salida .= "      </form>";
			$this->salida .= "      </tr>";
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		return true;
	}

	function CerrarTablas()//Muestra el final de la forma
	{
		$this->salida .= "</td></tr></table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaTablas($shema,$tablas)//Muestra las tablas de cada esquema en la BD
	{
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\">";
		$this->salida .= "  <fieldset><legend class=\"field\">ESQUEMA DE LA BASE DE DATOS</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">ESQUEMA</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$shemav=$shema;
		$this->salida .= "      ".strtoupper($shemav)."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">NO. DE TABLAS</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$shemav=sizeof($tablas);
		$this->salida .= "      ".strtoupper($shemav)."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$shemav=$shema;
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";// class=\"modulo_table_list\"
		$this->salida .= "  <tr><td><fieldset><legend class=\"field\">TABLAS DEL ESQUEMA => ".strtoupper($shemav)."</legend>";
		$this->salida .= "      <table height=\"40\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\"></td>";
		$this->salida .= "      <td align=\"center\">NOMBRE DE LA TABLA</td>";
		$this->salida .= "      <td align=\"center\">PROPIETARIO</td>";
		$this->salida .= "      <td align=\"center\">No. CAMPOS</td>";
		$this->salida .= "      <td align=\"center\">FILAS APROX.</td>";
		$this->salida .= "      <td align=\"center\" width=\"100%\">COMENTARIO</td>";
		$this->salida .= "      <td align=\"center\">EDI.</td>";
		$this->salida .= "      </tr>";
		$i=0;
		$j=0;
		$ciclo=sizeof($tablas);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$j=0;
			}
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<img src=\"".GetThemePath()."/images/tabla.png\" border=\"0\">";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "<a href=\"". ModuloGetURL('system','Diccionario','user','ListCamposTabla',
			array('NombreEsquema'=>$shema,'NombreTabla'=>$tablas[$i][0])) ."\">".$tablas[$i][0]."</a>";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$tablas[$i][1]."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$tablas[$i][3]."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$tablas[$i][4]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$tablas[$i][2]."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('system','Diccionario','user','ListCamposTablaEdicion',
			array('NombreEsquema'=>$shema,'NombreTabla'=>$tablas[$i][0])) ."\"><img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table><br>";
		return true;
	}

	function FormaCampos($shema,$tabla,$campos,$forkey,$refforkey)//Muestra los campos de una tabla
	{
		global $ConfigDB;
		$this->salida  = ThemeAbrirTabla('DICCIONARIO DE DATOS '._SIIS_APLICATION_TITLE .'  (SERVIDOR : '.$ConfigDB['dbhost'].' -  DATABASE : '.$ConfigDB['dbname'].')');
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td width=\"100%\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td><fieldset><legend class=\"field\">INFORMACIÓN DE LA BASE DE DATOS</legend>";
		$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">ESQUEMA</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$this->salida .= "      ".strtoupper($shema)."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">NOMBRE DE LA TABLA</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$this->salida .= "      ".$tabla[0][0]."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">PROPIETARIO</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$this->salida .= "      ".strtoupper($tabla[0][1])."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">COMENTARIO</td>";// width=\"100%\"
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$this->salida .= "      ".strtoupper($tabla[0][2])."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\" width=\"20%\">NOMBRE DEL CAMPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"18%\">TIPO DEL CAMPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"8%\" >NO NULO</td>";
		$this->salida .= "      <td align=\"center\" width=\"10%\">DEFAULT</td>";
		$this->salida .= "      <td align=\"center\" width=\"44%\">COMENTARIO</td>";
		$this->salida .= "      </tr>";
		$i=0;
		$j=0;
		$ciclo=sizeof($campos);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$j=0;
			}
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i][0]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i][1]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i][2]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i][3]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i][4]."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		$this->salida .= "      </table><br>";
		if(!empty($forkey))
		{
			$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=modulo_table_list_title>";
			$this->salida .= "      <td align=\"center\" width=\"100%\">LLAVES FORANEAS</td>";
			$this->salida .= "      </tr>";
			$i=0;
			$j=0;
			$ciclo=sizeof($forkey);
			while($i<$ciclo)
			{
				if($j==0)
				{
					$this->salida .= "<tr class=\"modulo_list_claro\">";
					$j=1;
				}
				else
				{
					$this->salida .= "<tr class=\"modulo_list_oscuro\">";
					$j=0;
				}
				$this->salida .= "<td>";
				$this->salida .= "".$forkey[$i][1]."";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$i++;
			}
			$this->salida .= "      </table><br>";
		}
		if(!empty($refforkey))
		{
			$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=modulo_table_list_title>";
			$this->salida .= "      <td align=\"center\" width=\"100%\">TABLAS REFERENCIADAS</td>";
			$this->salida .= "      </tr>";
			$i=0;
			$j=0;
			$ciclo=sizeof($refforkey);
			while($i<$ciclo)
			{
				if($j==0)
				{
					$this->salida .= "<tr class=\"modulo_list_claro\">";
					$j=1;
				}
				else
				{
					$this->salida .= "<tr class=\"modulo_list_oscuro\">";
					$j=0;
				}
				$this->salida .= "<td>";
				$this->salida .= "".$refforkey[$i][0]."";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$i++;
			}
			$this->salida .= "      </table>";
		}
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('system','Diccionario','user','main');
		$this->salida .= "  <form name=\"menu\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LAS TABLAS\"><br>";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "</td></tr></table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaCamposEdicion($shema,$tabla,$campos)//Permite modificar los comentarios a las tablas y sus campos
	{
		global $ConfigDB;
		$_SESSION['Diccionario']['tabla']=$tabla;
		$_SESSION['Diccionario']['campo']=$campos;
		$this->salida  = ThemeAbrirTabla('DICCIONARIO DE DATOS '._SIIS_APLICATION_TITLE .'  (SERVIDOR : '.$ConfigDB['dbhost'].' -  DATABASE : '.$ConfigDB['dbname'].')');
		$accion=ModuloGetURL('system','Diccionario','user','GrabarComentario');
		$this->salida .= "<form name=\"campoedicion\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td width=\"100%\">";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td><fieldset><legend class=\"field\">INFORMACIÓN DE LA BASE DE DATOS</legend>";
		$this->salida .= "      <table height=\"20\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">ESQUEMA</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$shemav=$shema;
		$this->salida .= "      ".strtoupper($shemav)."";
		$this->salida .= "      <input type=\"hidden\" name=\"shema\" value=\"$shema\" class=\"input-text\">";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">NOMBRE DE LA TABLA</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$shemav=$tabla[0][0];
		$this->salida .= "      ".$shemav."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">PROPIETARIO</td>";
		$this->salida .= "      <td class=\"label\" width=\"80%\">";
		$shemav=$tabla[0][1];
		$this->salida .= "      ".strtoupper($shemav)."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=modulo_table_list_title width=\"20%\">COMENTARIO</td>";// width=\"100%\"
		$this->salida .= "      <td width=\"80%\">";
		$this->salida .= "      <input type=\"text\" name=\"tablacomen\" value=\"".$tabla[0][2]."\" class=\"input-text\" maxlength=\"255\" size=\"80\" >";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "      <table height=\"40\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\" width=\"20%\">NOMBRE DEL CAMPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"18%\">TIPO DEL CAMPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"8%\" >NO NULO</td>";
		$this->salida .= "      <td align=\"center\" width=\"10%\">DEFAULT</td>";
		$this->salida .= "      <td align=\"center\" width=\"44%\">COMENTARIO</td>";
		$this->salida .= "      </tr>";
		$i=0;
		$j=0;
		$ciclo=sizeof($campos);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$j=0;
			}
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i][0]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i][1]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i][2]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i][3]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "<input type=\"text\" name=\"comentario".$campos[$i][0]."\" value=\"".$campos[$i][4]."\" class=\"input-text\" maxlength=\"255\" size=\"50\" >";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  <table border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('system','Diccionario','user','main');
		$this->salida .= "  <form name=\"menu\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "</td></tr></table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaTablasBusqueda($tablas,$busqueda)//Muestra las tablas encontradas en una búsqueda
	{
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\">";
		$this->salida .= "  <fieldset><legend class=\"field\">RESULTADOS DE LA BÚSQUEDA</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"60%\">NÚMERO DE TABLAS QUE COINCIDEN CON LA BÚSQUEDA -->> ".$busqueda."</td>";
		$this->salida .= "      <td class=\"label\" width=\"40%\">";
		$shemav=sizeof($tablas);
		$this->salida .= "      ".strtoupper($shemav)."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td><fieldset><legend class=\"field\">TABLAS ENCONTRADAS</legend>";
		$this->salida .= "      <table height=\"40\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">NOMBRE DE LA TABLA</td>";
		$this->salida .= "      <td align=\"center\">ESQUEMA</td>";
		$this->salida .= "      <td align=\"center\">PROPIETARIO</td>";
		$this->salida .= "      <td align=\"center\" width=\"100%\">COMENTARIO</td>";
		if($_SESSION['editar']==1)
		{
			$this->salida .= "      <td align=\"center\" class=\"label\" >EDI.</td>";
		}
		$this->salida .= "      </tr>";
		$i=0;
		$j=0;
		$ciclo=sizeof($tablas);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$j=0;
			}
			$this->salida .= "<td>";
			$this->salida .= "<a href=\"". ModuloGetURL('system','Diccionario','user','ListCamposTabla',
			array('NombreEsquema'=>$tablas[$i][0],'NombreTabla'=>$tablas[$i][1])) ."\">".$tablas[$i][1]."</a>";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$tablas[$i][0]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$tablas[$i][2]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$tablas[$i][3]."";
			$this->salida .= "</td>";
			if($_SESSION['editar']==1)
			{
				$this->salida .= "<td align=\"center\">";
				$this->salida .= "<a href=\"". ModuloGetURL('system','Diccionario','user','ListCamposTablaEdicion',
				array('NombreEsquema'=>$tablas[$i][0],'NombreTabla'=>$tablas[$i][1])) ."\"><img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"></a>";
				$this->salida .= "</td>";
			}
			$this->salida .= "</tr>";
			$i++;
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('system','Diccionario','user','main');
		$this->salida .= "  <form name=\"menu\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LAS TABLAS\"><br>";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		return true;
	}

	function FormaCamposBusqueda($campos,$busqueda)//Muestra los campos encontrados en una búsqueda
	{
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\">";
		$this->salida .= "  <fieldset><legend class=\"field\">RESULTADOS DE LA BÚSQUEDA</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"60%\">NÚMERO DE CAMPOS QUE COINCIDEN CON LA BÚSQUEDA -->> ".$busqueda."</td>";
		$this->salida .= "      <td class=\"label\" width=\"40%\">";
		$shemav=sizeof($campos);
		$this->salida .= "      ".strtoupper($shemav)."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td><fieldset><legend class=\"field\">TABLAS QUE CONTIENEN EL CAMPO BUSCADO</legend>";
		$this->salida .= "      <table height=\"40\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">NOMBRE DE LA TABLA</td>";
		$this->salida .= "      <td align=\"center\">ESQUEMA</td>";
		$this->salida .= "      <td align=\"center\">NOMBRE DEL CAMPO</td>";
		$this->salida .= "      <td align=\"center\">TIPO DEL CAMPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"100%\">COMENTARIO</td>";
		$this->salida .= "      </tr>";
		$i=0;
		$j=0;
		$ciclo=sizeof($campos);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$j=0;
			}
			$this->salida .= "<td>";
			$this->salida .= "<a href=\"". ModuloGetURL('system','Diccionario','user','ListCamposTabla',
			array('NombreEsquema'=>$campos[$i][0],'NombreTabla'=>$campos[$i][1])) ."\">".$campos[$i][1]."</a>";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i][0]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i][2]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i][3]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$campos[$i][4]."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('system','Diccionario','user','main');
		$this->salida .= "  <form name=\"menu\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LAS TABLAS\"><br>";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		return true;
	}

	function FormaComentBusqueda($coment,$busqueda)//Muestra los comentarios encontrados en una búsqueda
	{
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td width=\"50%\">";
		$this->salida .= "  <fieldset><legend class=\"field\">RESULTADOS DE LA BÚSQUEDA</legend>";
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td class=\"label\" width=\"60%\">NÚMERO DE COMENTARIOS QUE COINCIDEN CON LA BÚSQUEDA -->> ".$busqueda."</td>";
		$this->salida .= "      <td class=\"label\" width=\"40%\">";
		$shemav=sizeof($coment);
		$this->salida .= "      ".strtoupper($shemav)."";
		$this->salida .= "      </td>";
		$this->salida .= "      </tr>";
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td><fieldset><legend class=\"field\">TABLAS QUE CONTIENEN EL CAMPO CON EL COMENTARIO BUSCADO</legend>";
		$this->salida .= "      <table height=\"40\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td align=\"center\">NOMBRE DE LA TABLA</td>";
		$this->salida .= "      <td align=\"center\">ESQUEMA</td>";
		$this->salida .= "      <td align=\"center\">NOMBRE DEL CAMPO</td>";
		$this->salida .= "      <td align=\"center\">TIPO DEL CAMPO</td>";
		$this->salida .= "      <td align=\"center\" width=\"100%\">COMENTARIO</td>";
		$this->salida .= "      </tr>";
		$i=0;
		$j=0;
		$ciclo=sizeof($coment);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$j=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$j=0;
			}
			$this->salida .= "<td>";
			$this->salida .= "<a href=\"". ModuloGetURL('system','Diccionario','user','ListCamposTabla',
			array('NombreEsquema'=>$coment[$i][0],'NombreTabla'=>$coment[$i][1])) ."\">".$coment[$i][1]."</a>";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$coment[$i][0]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$coment[$i][2]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$coment[$i][3]."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$coment[$i][4]."";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$i++;
		}
		$this->salida .= "      </table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$accion=ModuloGetURL('system','Diccionario','user','main');
		$this->salida .= "  <form name=\"menu\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER A LAS TABLAS\"><br>";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		return true;
	}

}//fin de la class
?>
