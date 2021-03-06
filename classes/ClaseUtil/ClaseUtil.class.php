<?php
	/**
	* $Id: ClaseUtil.class.php,v 1.2 2010/06/03 20:44:21 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.2 $
	*
  * Clase: ClaseUtil
  * El proposito de esta clase es proveer de una serie de javascripts y funciones 
  * de validacion generales para los modulos
  *
	* @autor Hugo F  Manrique
	*/
	class ClaseUtil
	{
    /**
    *
    */
    var $cntBuscadorDiag = 0;   
    /**
    *
    */
    var $pasoSql = false;
    /**
    * Constructor de la clase
    */
		function ClaseUtil(){}
		/**
		* Crea una funcion javascript que permite limpiar los campos de un formulario
    *
    * @return string
		*/
		function LimpiarCampos()
		{
			$html  = "<script>\n";
			$html .= "	function LimpiarCampos(frm)\n";
			$html .= "	{\n";
			$html .= "		for(i=0; i<frm.length; i++)\n";
			$html .= "		{\n";
			$html .= "			switch(frm[i].type)\n";
			$html .= "			{\n";
			$html .= "				case 'text': frm[i].value = ''; break;\n";
			$html .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$html .= "				case 'checkbox': frm[i].checked = false; break;\n";
			$html .= "				case 'textarea': frm[i].value = ''; break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			return $html;
		}
		/**
		* Crea una funcion javascript que permite restringir los datos ingresados 
    * por teclado a que sean solo numeros 
    *
    * @param boolean $puntos Indica si se desea que el numero ingresado 
    *        contenga puntos decimales o no
    *
    * @return string
		*/
		function AcceptNum($puntos = true)
		{
			$html  = "<script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			if(!$puntos)
        $html .= "		return (key <= 13 ||(key >= 48 && key <= 57));\n";
      else
        $html .= "		return (key <= 13 || key == 46 || (key >= 48 && key <= 57));\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			return $html;
		}				
    /**
		* Crea una funcion javascript que permite restringir los datos ingresados 
    * por teclado para que sean solo numeros y un separador
    *
    * @param boolean $puntos Indica si se desea que el numero ingresado 
    *        contenga puntos decimales o no
    *
    * @return string
		*/
		function AcceptDate($separador)
		{
			$ascii = "";
      switch($separador)
      {
        case "-": $ascii = 45; break;
        case "/": $ascii = 47; break;
      }
      
      $html  = "<script>\n";
			$html .= "	function acceptDate(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 ||(key >= 48 && key <= 57) || key == ".$ascii.");\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			return $html;
		}		
		/**
		* Crea una funcion javascript que permite visualizar un efecto sobre las 
    * filas de una tabla, cuando el mouse pasa sobre ellas 
    *
    * @return string
		*/
		function RollOverFilas()
		{
			$html  = "<script>\n";
			$html .= "	function mOvr(src,clrOver)\n";
			$html .= "	{\n";
			$html .= "		src.style.background = clrOver;\n";
			$html .= "	}\n";
			$html .= "	function mOut(src,clrIn)\n";
			$html .= "	{\n";
			$html .= "		src.style.background = clrIn;\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			return $html;
		}    
		/**
		* Crea una funcion javascript que permite hacer el equivalente del trim 
    * de php en javascript 
    *
    * @return string
		*/
		function TrimScript()
		{
			$html  = "<script>\n";
      $html .= "  function trim(cadena)\n";
      $html .= "	{\n";
      $html .= "    for(i=0; i<cadena.length; )\n";
      $html .= "		{\n";
      $html .= "      if(cadena.charAt(i)==\" \")\n";
      $html .= "    	  cadena=cadena.substring(i+1, cadena.length);\n";
      $html .= "    	else\n";
			$html .= "        break;\n";
      $html .= "		}\n";
      $html .= "    for(i=cadena.length-1; i>=0; i=cadena.length-1)\n";
      $html .= "		{\n";
      $html .= "      if(cadena.charAt(i)==\" \")\n";
      $html .= "    	  cadena=cadena.substring(0,i);\n";
      $html .= "    	else\n";
      $html .= "    	  break;\n";
			$html .= "	  } \n";
      $html .= "    return cadena;\n";
			$html .= "	} \n";
			$html .= "</script>\n";
			return $html;
		}
		/**
		* Crea una funcion javascript que permite hacer la validacion de un valor
    * indicando si este es numerico o no
    *
    * @return string
		*/
		function IsNumeric()
		{
      $this->numeric = true;
      
			$html  = "<script>\n";
			$html .= "	function IsNumeric(valor)\n";
			$html .= "	{\n";
			$html .= "		var log = valor.length; \n";
			$html .= "		var sw='S';\n";
			$html .= "		var puntos = 0;\n";
			$html .= "		for (x=0; x<log; x++)\n";
			$html .= "		{ \n";
			$html .= "			v1 = valor.substr(x,1);\n";
			$html .= "			v2 = parseInt(v1);\n";
			$html .= "			//Compruebo si es un valor num?rico\n";
			$html .= "			if(v1 == '.')\n";
			$html .= "			{\n";
			$html .= "				puntos ++;\n";
			$html .= "			}\n";
			$html .= "			else if (isNaN(v2)) \n";
			$html .= "			{ \n";
			$html .= "				sw= 'N';\n";
			$html .= "				break;\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		if(log == 0) sw = 'N';\n";
			$html .= "		if(puntos > 1) sw = 'N';\n";
			$html .= "		if(sw=='S') return true;\n";
			$html .= "		return false;\n";
			$html .= "	} \n";
			$html .= "</script>\n";

			return $html;
		}		
    /**
		* Crea una funcion javascript que permite hacer la validacion de un valor
    * indicando si este es tipo fecha o no
    *
    * @return string
		*/
		function IsDate($separador = "/")
		{
			$html  = "<script>\n";
      $html .= "	function finMes(nMes)\n";
			$html .= "	{\n";
			$html .= "		var nRes = 0;\n";
			$html .= "		switch (nMes)\n";
			$html .= "		{\n";
			$html .= "			case '01': nRes = 31; break;\n";
			$html .= "			case '02': nRes = 29; break;\n";
			$html .= "			case '03': nRes = 31; break;\n";
			$html .= "			case '04': nRes = 30; break;\n";
			$html .= "			case '05': nRes = 31; break;\n";
			$html .= "			case '06': nRes = 30; break;\n";
			$html .= "			case '07': nRes = 31; break;\n";
			$html .= "			case '08': nRes = 31; break;\n";
			$html .= "			case '09': nRes = 30; break;\n";
			$html .= "			case '10': nRes = 31; break;\n";
			$html .= "			case '11': nRes = 30; break;\n";
			$html .= "			case '12': nRes = 31; break;\n";
			$html .= "		}\n";
			$html .= "		return nRes;\n";
			$html .= "	}\n";
			$html .= "	function IsDate(fecha)\n";
			$html .= "	{\n";
			$html .= "		if(fecha == '' || fecha == undefined)	return false;\n";
			$html .= "		var bol = true;\n";
			$html .= "		var arr = fecha.split('".$separador."');\n";
			$html .= "		if(arr.length != 3)\n";
			$html .= "			return false;\n";			
      $html .= "		else if(arr[0] == '00')\n";
			$html .= "			return false;\n";
			$html .= "		else\n";
			$html .= "		{\n";
			$html .= "			bol = bol && (IsNumeric(arr[0]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[1]));\n";
			$html .= "			bol = bol && (IsNumeric(arr[2]));\n";
			$html .= "			bol = bol && ((arr[1] >= 1) && (arr[1] <= 12));\n";
			$html .= "			bol = bol && (arr[0] <= finMes(arr[1]));\n";
			$html .= "			return bol;\n";
			$html .= "		}\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			
      if(!$this->numeric)  $html .= $this->IsNumeric();
			return $html;
		}
		/**
		*
		*/
		function CrearCapaVentana(&$obj)
		{
			if($obj)
			{
				$obj->IncludeJS("CrossBrowserEvent");
				$obj->IncludeJS("CrossBrowserDrag");
				$obj->IncludeJS("CrossBrowser");
			}
			
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 2;\n";
			$html .= "	function OcultarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function CrearMensaje(mensaje)\n";
			$html .= "	{\n";
			$html .= "		xGetElementById('confirmacion').innerHTML = mensaje;\n";
			$html .= "		Iniciar();\n";
			$html .= "		MostrarSpan('Contenedor');\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan(Seccion)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
			$html .= "			e = xGetElementById(Seccion);\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";
			$html .= "	function Iniciar()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Contenedor';\n";
			$html .= "		titulo = 'titulo';\n";
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,350, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+100);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,330, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele, 330, 0);\n";
			$html .= "	}\n";
			$html .= "	function myOnDragStart(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	  window.status = '';\n";
			$html .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "	  else xZIndex(ele, hiZ++);\n";
			$html .= "	  ele.myTotalMX = 0;\n";
			$html .= "	  ele.myTotalMY = 0;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag(ele, mdx, mdy)\n";
			$html .= "	{\n";
			$html .= "	  if (ele.id == titulo) {\n";
			$html .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "	  }\n";
			$html .= "	  else {\n";
			$html .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "	  }  \n";
			$html .= "	  ele.myTotalMX += mdx;\n";
			$html .= "	  ele.myTotalMY += mdy;\n";
			$html .= "	}\n";
			$html .= "	function myOnDragEnd(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	}\n";	
			
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">INFORMACI?N</div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div>\n";
			$html .= "	<div id='Contenido' class='d2Content' style=\"background:#EFEFEF\"><br><br>\n";
			$html .= "		<form name=\"oculta\" action=\"javascript:OcultarSpan('Contenedor')\" method=\"post\">\n";
			$html .= "			<table width=\"100%\" align=\"center\">\n";
			$html .= "				<tr>\n";
			$html .= "					<td align=\"center\" class=\"label\">\n";
			$html .= "					<div style=\"text-transform: uppercase;\" id=\"confirmacion\" class=\"normal_10AN\"></div>\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";			
			$html .= "				<tr>\n";
			$html .= "					<td colspan=\"3\" align=\"center\">\n";
			$html .= "						<input type=\"submit\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\">\n";
			$html .= "					</td>\n";
			$html .= "				</tr>\n";
			$html .= "			</table>\n";
			$html .= "		</form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";

			return $html;
		}
		/**
		* Funcion donde se evalua si una fecha es correcta o no 
		* 
		* @param string $fecha valor a evaluar (formato dd/mm/yyyy o dd-mm-yyyy)
		* @param string $narca Separador de fecha
    *
		* @return boolean 
		*/
		function ValidarFecha($fecha,$marca)
		{			
			$f = explode($marca,$fecha); 
			
			$resultado = checkdate($f[1],$f[0],$f[2]);
			if($resultado != 1 || sizeof($f) != 3)
				return false;
						
			return true;
		}
		/**
		* Funcion donde se evalua si un valor de hora es correcto o no
		* 
		* @param string $hora valor a evaluar (formato hh:mm:ss)
    *
		* @return boolean 
		*/		
    function ValidarHora($hora)
		{			
			$h = explode(":",$hora); 
			
			$hora = intval($h[0]);
			$minuto = intval($h[1]);
			
			if($hora > 23 || $hora < 0  || $minuto > 59 || $minuto < 0)
				return false;
			
      if($h[3])
      {
        $seg = intval($h[3]);
        if($seg > 59 || $seg < 0)
          return false;
      }
			return true;
		}
		/**
    * Funcion que devuelve la parte del sql correspondiente al filtro de 
    * los nombres y los apellidos 
    * 
		* @param String $nombres Cadena, con la que se hara el filtro de los nombre
		* @param String $apellidos Cadena, con la que se hara el filtro de los apellidos
		* @param String $alias Cadena que contiene el alias de la tabla, si lo hay
    *
    * @return String
		*/
		function FiltrarNombres($nombres,$apellidos,$alias,$c_pn = "primer_nombre",$c_sn="segundo_nombre",$c_pa = "primer_apellido",$c_sa = "segundo_apellido")
		{
			$nombres = trim(strtoupper($nombres));
			$apellidos = trim(strtoupper($apellidos));
			if($alias) $alias .= ".";
			
			if ($nombres != '')
			{
				$a = explode(' ',preg_replace("/\s{2,}/"," ",$nombres));//QUITA DOBLE ESPACIOS INTERNOS

				switch(count($a))
				{
					case 1:
						$filtroNombres .= " (".$alias."".$c_pn."   SIMILAR TO '(".current($a)."|".current($a)."[[:space:]]%|%[[:space:]]".current($a)."|%[[:space:]]".current($a)."[[:space:]]%)' OR ".$alias."".$c_sn." SIMILAR TO '(".current($a)."|".current($a)."[[:space:]]%|%[[:space:]]".current($a)."|%[[:space:]]".current($a)."[[:space:]]%)')";
					break;
					case 2:
						$filtroNombres  = " ".$alias."".$c_pn."  SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
						next($a);
						$filtroNombres .= " AND ((".$alias."".$c_pn." SIMILAR TO '%[[:space:]]".current($a)."') OR (".$alias."".$c_sn." ILIKE '".current($a)."'))";
					break;
					default:
						$filtroNombres = " ".$alias."".$c_pn." SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
						for($i=2;$i<count($a);$i++)
						{
							next($a);
							$filtroNombres .= " AND ((".$alias."".$c_pn." SIMILAR TO '%[[:space:]](".current($a)."|".current($a)."[[:space:]]%)')
																	OR (".$alias."".$c_sn." SIMILAR TO '(".current($a)."[[:space:]]%|%[[:space:]]".current($a)."[[:space:]]%)'))";
						}
						next($a);
						$filtroNombres .= " AND ((".$alias."$c_pn SIMILAR TO '%[[:space:]]".current($a)."')  OR  (".$alias."".$c_sn." SIMILAR TO '(".current($a)."|%[[:space:]]".current($a).")') )";
					break;
				}
			}

			if ($apellidos != '')
			{
				$a = explode(' ',preg_replace("/\s{2,}/"," ",$apellidos));

				switch(count($a))
				{
					case 1:
							$filtroApellidos  = " ".$alias."".$c_pa." ILIKE '".current($a)."'";
					break;

					case 2:
							$filtroApellidos  = " ".$alias."".$c_pa." SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
							next($a);
							$filtroApellidos .= " AND ((".$alias."".$c_pa." SIMILAR TO '%[[:space:]]".current($a)."') OR (".$alias."".$c_sa." ILIKE '".current($a)."'))";
					break;

					default:
							$filtroApellidos  = " ".$alias."".$c_pa." SIMILAR TO'(".current($a)."|".current($a)."[[:space:]]%)'";
							for($i=2;$i<count($a);$i++)
							{
								next($a);
								$filtroApellidos .= " AND ((".$alias."".$c_pa." SIMILAR TO '%[[:space:]](".current($a)."|".current($a)."[[:space:]]%)')
																						OR (".$alias."".$c_sa." SIMILAR TO '(".current($a)."[[:space:]]%|%[[:space:]]".current($a)."[[:space:]]%)'))";
							}
							next($a);
							$filtroApellidos .= " AND ((".$alias."".$c_pa." SIMILAR TO '%[[:space:]]".current($a)."')  OR  (".$alias."".$c_sa." SIMILAR TO '(".current($a)."|%[[:space:]]".current($a).")') )";
					break;
				}
			}

			if(!empty($filtroNombres))
			{
				if(!empty($filtroApellidos))
				{
					$filtroPrincipalTipo2= $filtroNombres ." AND ".$filtroApellidos;
				}
				else
				{
					$filtroPrincipalTipo2 = $filtroNombres;
				}
			}
			else
			{
				if(!empty($filtroApellidos))
				{
					$filtroPrincipalTipo2 = $filtroApellidos;
				}
			}
			return $filtroPrincipalTipo2;
		}
    /**
    * Funcion que devuelve la parte del sql correspondiente al filtro de 
    * los nombres y los apellidos 
    * 
		* @param String $nombres Cadena, con la que se hara el filtro de los nombre
		* @param String $apellidos Cadena, con la que se hara el filtro de los apellidos
		* @param String $alias Cadena que contiene el alias de la tabla, si lo hay
    *
    * @return String
		*/
		function FiltrarNombresOci8($nombres,$apellidos,$alias,$c_pn = "primer_nombre",$c_sn="segundo_nombre",$c_pa = "primer_apellido",$c_sa = "segundo_apellido")
		{
			$nombres = trim(strtoupper($nombres));
			$apellidos = trim(strtoupper($apellidos));
			if($alias) $alias .= ".";
			
			if ($nombres != '')
			{
				$a = explode(' ',preg_replace("/\s{2,}/"," ",$nombres));//QUITA DOBLE ESPACIOS INTERNOS

				switch(count($a))
				{
					case 1:
						$filtroNombres .= " REGEXP_LIKE (".$alias."".$c_pn.",'([ ]*)".current($a)."([ ]*)') OR REGEXP_LIKE (".$alias."".$c_sn.",'([ ]*)".current($a)."([ ]*)')";
					break;
					case 2:
						$filtroNombres  = " REGEXP_LIKE (".$alias."".$c_pn.",'^".current($a)."([ ]*)')";
						next($a);
						$filtroNombres .= " AND (REGEXP_LIKE (".$alias."".$c_pn.",'([ ]*)".current($a)."$') OR (".$alias."".$c_sn." LIKE '".current($a)."'))";
					break;
					default:
						$filtroNombres  = " REGEXP_LIKE (".$alias."".$c_pn.",'^".current($a)."([ ]*)')";
						for($i=2;$i<count($a);$i++)
						{
							next($a);
							$filtroNombres .= " AND (REGEXP_LIKE (".$alias."".$c_pn.",'([ ]*)".current($a)."([ ]*)')
																	OR REGEXP_LIKE (".$alias."".$c_sn.",'([ ]*)".current($a)."([ ]*)'))";
						}
						next($a);
						$filtroNombres .= " AND (REGEXP_LIKE (".$alias."".$c_pn.",'([ ]*)".current($a)."$')  OR  REGEXP_LIKE (".$alias."".$c_sn.",'([ ]*)".current($a)."([ ]*)') )";
					break;
				}
			}

			if ($apellidos != '')
			{
				$a = explode(' ',preg_replace("/\s{2,}/"," ",$apellidos));

				switch(count($a))
				{
					case 1:
						$filtroNombres .= " REGEXP_LIKE (".$alias."".$c_pa.",'([ ]*)".current($a)."([ ]*)') OR REGEXP_LIKE (".$alias."".$c_sa.",'([ ]*)".current($a)."([ ]*)')";
					break;
					case 2:
						$filtroNombres  = " REGEXP_LIKE (".$alias."".$c_pa.",'^".current($a)."([ ]*)')";
						next($a);
						$filtroNombres .= " AND (REGEXP_LIKE (".$alias."".$c_pa.",'([ ]*)".current($a)."$') OR (".$alias."".$c_sa." LIKE '".current($a)."'))";
					break;
					default:
						$filtroNombres  = " REGEXP_LIKE (".$alias."".$c_pa.",'^".current($a)."([ ]*)')";
						for($i=2;$i<count($a);$i++)
						{
							next($a);
							$filtroNombres .= " AND (REGEXP_LIKE (".$alias."".$c_pa.",'([ ]*)".current($a)."([ ]*)')
																	OR REGEXP_LIKE (".$alias."".$c_sa.",'([ ]*)".current($a)."([ ]*)'))";
						}
						next($a);
						$filtroNombres .= " AND (REGEXP_LIKE (".$alias."".$c_pa.",'([ ]*)".current($a)."$')  OR  REGEXP_LIKE (".$alias."".$c_sa.",'([ ]*)".current($a)."([ ]*)') )";
					break;
				}
			}

			if(!empty($filtroNombres))
			{
				if(!empty($filtroApellidos))
				{
					$filtroPrincipalTipo2= $filtroNombres ." AND ".$filtroApellidos;
				}
				else
				{
					$filtroPrincipalTipo2 = $filtroNombres;
				}
			}
			else
			{
				if(!empty($filtroApellidos))
				{
					$filtroPrincipalTipo2 = $filtroApellidos;
				}
			}
			return $filtroPrincipalTipo2;
		}
    /** 
    * Funcion que dado un numero lo devuelve escrito.
    * @param number $num  Numero a convertir. 
    * @param bool $fem  Forma femenina (true) o no (false). 
    * @param bool $dec  Sin decimales (false) o con decimales (true). 
    *
    * @result string  
    */ 
    function num2letras($num, $fem = true, $dec = false) 
    { 
      $matuni[2]  = "dos"; 
      $matuni[3]  = "tres"; 
      $matuni[4]  = "cuatro"; 
      $matuni[5]  = "cinco"; 
      $matuni[6]  = "seis"; 
      $matuni[7]  = "siete"; 
      $matuni[8]  = "ocho"; 
      $matuni[9]  = "nueve"; 
      $matuni[10] = "diez"; 
      $matuni[11] = "once"; 
      $matuni[12] = "doce"; 
      $matuni[13] = "trece"; 
      $matuni[14] = "catorce"; 
      $matuni[15] = "quince"; 
      $matuni[16] = "dieciseis"; 
      $matuni[17] = "diecisiete"; 
      $matuni[18] = "dieciocho"; 
      $matuni[19] = "diecinueve"; 
      $matuni[20] = "veinte"; 
      $matunisub[2] = "dos"; 
      $matunisub[3] = "tres"; 
      $matunisub[4] = "cuatro"; 
      $matunisub[5] = "quin"; 
      $matunisub[6] = "seis"; 
      $matunisub[7] = "sete"; 
      $matunisub[8] = "ocho"; 
      $matunisub[9] = "nove"; 

      $matdec[2] = "veint"; 
      $matdec[3] = "treinta"; 
      $matdec[4] = "cuarenta"; 
      $matdec[5] = "cincuenta"; 
      $matdec[6] = "sesenta"; 
      $matdec[7] = "setenta"; 
      $matdec[8] = "ochenta"; 
      $matdec[9] = "noventa"; 
      $matsub[3]  = 'mill'; 
      $matsub[5]  = 'bill'; 
      $matsub[7]  = 'mill'; 
      $matsub[9]  = 'trill'; 
      $matsub[11] = 'mill'; 
      $matsub[13] = 'bill'; 
      $matsub[15] = 'mill'; 
      $matmil[4]  = 'millones'; 
      $matmil[6]  = 'billones'; 
      $matmil[7]  = 'de billones'; 
      $matmil[8]  = 'millones de billones'; 
      $matmil[10] = 'trillones'; 
      $matmil[11] = 'de trillones'; 
      $matmil[12] = 'millones de trillones'; 
      $matmil[13] = 'de trillones'; 
      $matmil[14] = 'billones de trillones'; 
      $matmil[15] = 'de billones de trillones'; 
      $matmil[16] = 'millones de billones de trillones'; 

      $num = trim((string)@$num); 
      if ($num[0] == '-') 
      { 
        $neg = 'menos '; 
        $num = substr($num, 1); 
      }
      else 
        $neg = ''; 
      while ($num[0] == '0') $num = substr($num, 1); 
      if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
      $zeros = true; 
      $punt = false; 
      $ent = ''; 
      $fra = ''; 
      for ($c = 0; $c < strlen($num); $c++) 
      { 
        $n = $num[$c]; 
        if (! (strpos(".,'''", $n) === false)) 
        { 
          if ($punt) break; 
          else
          { 
            $punt = true; 
            continue; 
          }
        }
        elseif (! (strpos('0123456789', $n) === false)) 
        { 
          if ($punt) 
          { 
            if ($n != '0') $zeros = false; 
            $fra .= $n; 
          }
          else
            $ent .= $n; 
        }
        else 
          break;
      } 
      $ent = '     ' . $ent; 
      if ($dec and $fra and ! $zeros) 
      { 
        $fin = ' coma'; 
        for ($n = 0; $n < strlen($fra); $n++) 
        { 
          if (($s = $fra[$n]) == '0') 
            $fin .= ' cero'; 
          elseif ($s == '1') 
            $fin .= $fem ? ' una' : ' un'; 
          else 
            $fin .= ' ' . $matuni[$s]; 
        } 
      }
      else 
        $fin = ''; 
      if ((int)$ent === 0) return 'Cero ' . $fin; 
      $tex = ''; 
      $sub = 0; 
      $mils = 0; 
      $neutro = false; 
      while ( ($num = substr($ent, -3)) != '   ') 
      { 
        $ent = substr($ent, 0, -3); 
        if (++$sub < 3 and $fem) 
        { 
          $matuni[1] = 'una'; 
          $subcent = 'as'; 
        }
        else
        { 
          $matuni[1] = $neutro ? 'un' : 'uno'; 
          $subcent = 'os'; 
        } 
        $t = ''; 
        $n2 = substr($num, 1); 
        if ($n2 == '00') 
        { 
        }
        elseif ($n2 < 21) 
          $t = ' ' . $matuni[(int)$n2]; 
        elseif ($n2 < 30) 
        { 
          $n3 = $num[2]; 
          if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
          $n2 = $num[1]; 
          $t = ' ' . $matdec[$n2] . $t; 
        }
        else
        { 
          $n3 = $num[2]; 
          if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
          $n2 = $num[1]; 
          $t = ' ' . $matdec[$n2] . $t; 
        } 
        $n = $num[0]; 
        if ($n == 1) 
        {
          $t = ' ciento' . $t; 
        }
        elseif ($n == 5)
        { 
          $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
        }
        elseif ($n != 0)
        { 
          $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
        } 
        if ($sub == 1) { 
        }
        elseif (! isset($matsub[$sub])) 
        { 
          if ($num == 1) 
          { 
            $t = ' mil'; 
          }
          elseif ($num > 1)
          { 
            $t .= ' mil'; 
          } 
        }
        elseif ($num == 1) 
        { 
          $t .= ' ' . $matsub[$sub] . '?n'; 
        }
        elseif ($num > 1)
        { 
          $t .= ' ' . $matsub[$sub] . 'ones'; 
        }   
        if ($num == '000') $mils ++; 
        elseif ($mils != 0) 
        { 
          if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
          $mils = 0; 
        } 
        $neutro = true; 
        $tex = $t . $tex; 
      } 
      $tex = $neg . substr($tex, 1) . $fin; 
      return ucfirst($tex); 
    }
    /**
    *
    */
    function OpenDiagnostico($nombre_forma,$nc_diagnostico_id,$nc_diagnostico_descripcion,$label_descripcion)
    {
      global $_ROOT;
      
      $ruta = $_ROOT ."classes/BuscadorDiagnosticosI/BuscadorDiagnosticosI.class.php?";
      $datos["nombre_forma"] = $nombre_forma;
      $datos["nc_diagnostico_id"] = $nc_diagnostico_id;
      $datos["nc_diagnostico_descripcion"] = $nc_diagnostico_descripcion;
      $datos["label_descripcion"] = $label_descripcion;
      
      $html  = "<script language='javascript'>\n";
      $html .= "  function abrirVentanaDiagnostico_".$this->cntBuscadorDiag."()\n";
      $html .= "  {\n";
      $html .= "    var alto = screen.height\n";
      $html .= "    var ancho = screen.width\n";
      $html .= "    var nombre=\"Diagnosticos\";\n";
      $html .= "    var str =\"ancho,alto,resizable=no,status=no,scrollbars=yes\";\n";
      $html .= "    var url = \"".$ruta.URLRequest(array("datos_vp"=>$datos))."\";\n";
      $html .= "    rem = window.open(url, nombre, str).focus();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<input type=\"button\" class=\"input-submit\" name=\"diagnosticobsc\" value=\"Buscar\" onclick=\"javascript:abrirVentanaDiagnostico_".$this->cntBuscadorDiag."()\" class=\"label_error\">\n";
      
      $this->cntBuscadorDiag++;
      return $html;
    }
    /**
    *
    */
    function MarcaReportes($capaPagina,$capaMarca,$nombreImagen)
    {
      $html .= "<script>\n";
      $html .= "  ele1 = document.getElementById('".$capaPagina."');\n";
      $html .= "  ele2 = document.getElementById('".$capaMarca."');\n";
      $html .= "  alto = ele1.offsetHeight;\n";
      $html .= "  cantidad = alto/300\n";
      $html .= "  if (cantidad == 0) cantidad = 1;\n";
      $html .= "  for(i=0; i<cantidad; i++)\n";
      $html .= "    ele2.innerHTML += \"<div><img width='300' src=\\\"../images/".$nombreImagen."\\\"></div>\";\n";
      $html .= "</script>\n";
      
      return $html;
    }
    /**
    * Funcion para hacer la validacion del numero de identificacion de Ecuador
    *
    * @param number $idnumber Numero de identificacion a validar
    *
    * @return boolean
    */
    function ValidarCedulaEC($idnumber)
    {
      $identidad = trim($idnumber);
      if (strlen($identidad) > 10) return false;
      if ($identidad == "") return false;
      if (!is_numeric($identidad)) return false;
      
      $modulo10 = array("2","1","2","1","2","1","2","1","2");
      $sumaResiduos = 0;
      //Tomar los 9 primeros digitos
      for ($i=0; $i < 9; $i++)
      { 
        $producto = $modulo10[$i] * $identidad[$i];
        $sumaResiduos = $sumaResiduos + (($producto <= 9) ? $producto : ($producto - 9));
      }
      $residuo = ($sumaResiduos % 10);
      $digitoVerificacion = (($residuo == 0) ? $residuo : 10 - $residuo);
      
      if ($identidad[9] != $digitoVerificacion)
        return false;
      
      return true;
    }
    /**
		* Crea una funcion javascript que permite visualizar un efecto sobre las 
    * filas de una tabla, cuando el mouse pasa sobre ellas 
    *
    * @return string
		*/
		function NoControl()
		{
      $html  = "<script>\n";
      $html .= "  function noControl(e)\n";
      $html .= "	{\n";
      $html .= "    var keynum;\n";
      $html .= "    if(window.event) // IE\n";
      $html .= "    {\n";
      $html .= "      keynum = e.keyCode;\n";
      $html .= "    }\n";
      $html .= "    else if(e.which) // Netscape/Firefox/Opera\n";
      $html .= "    {\n";
      $html .= "      keynum = e.which;\n";
      $html .= "    }\n";
      $html .= "      return !(keynum==86 && e.ctrlKey)";
      $html .= "  }\n";
      $html .= "</script>\n";
			return $html;
		}  
    /**
    * Funci?n para comparar dos fechas devolviendo un valor positivo, negativo o nulo 
    * si la primera fecha es respectivamente mayor, menor o igual que la segunda.
    * La funci?n usa expresiones regulares para que admita fechas tanto en formato 
    * "dd-mm-aaaa" como con formato "dd/mm/aaaa"
    *
    * @param string $fecha1 Fecha
    * @param string $fecha2 
    */
    function CompararFechas($fecha1,$fecha2)       
    {
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
        list($dia1,$mes1,$a?o1)=split("/",$fecha1);
            
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
        list($dia1,$mes1,$a?o1)=split("-",$fecha1);
        
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
        list($dia2,$mes2,$a?o2)=split("/",$fecha2);
            
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
        list($dia2,$mes2,$a?o2)=split("-",$fecha2);
      
      $dif = mktime(0,0,0,$mes1,$dia1,$a?o1) - mktime(0,0,0, $mes2,$dia2,$a?o2);
      
      return ($dif);
    }
    /**
    * Funcion donde se crea la estructura xml de un archivo
    *
    * @param array $campos Arreglo de datos de los campos a incluir
    * @param object $nodo Objeto retornado por la funcion append_child
    * @param object $dom Objeto retornado por la funcion append_child
    */
    function CrearEstruecturaXml($campos,&$nodo,&$dom)
    {
      foreach ($campos as $key => $dtl) 
      {
    		if(is_array($dtl))
        {
          if(!is_numeric($key))
          {
            $nodo1 = $nodo->append_child($dom->create_element($key));
            $this->CrearEstruecturaXml($dtl,$nodo1,$dom);
          }
          else
            $this->CrearEstruecturaXml($dtl,$nodo,$dom);
        }
        else
        {
       		$campoTexto = $nodo->append_child($dom->create_element($key));
          $campoTexto->append_child($dom->create_text_node($dtl));
        }
    	}
    }
    /**
    *
    */
    function toXML($campo, $nombreItem,$nomre_arch) 
    {
      $dom = domxml_new_doc("1.0");
      $nodo = $dom->append_child($dom->create_element($nombreItem));
      $this->CrearEstruecturaXml($campo,$nodo,$dom);
      
      $dom->dump_file(GetVarConfigAplication("DIR_SIIS")."/tmp/".$nomre_arch.".xml", false, true);
      return true;
    }
    /**
    * Funci?n Cambio de estado o cambio del par?metro estado de un registro
    * Comunmente llamado "sw_estado" o simplemente "estado" en el que se alimenta de
    * unos datos as?
    * @param String $tabla Nombre de la Tabla
    * @param String $campo campo a Modificar
    * @param String $valor Valor Nuevo a Aplicar en la Tabla
    * @param String $id Identificacion del Registro a Modificar
    * @param String $campo_id Es el Campo o nombre de la llave Primaria
    *
    * @return Boolean
    */
    function Cambio_Estado($tabla,$campo,$valor,$id,$campo_id)
		{
      $sql  = "Update ".$tabla." ";
      $sql .= "SET ".$campo." = '".$valor."'";
      $sql .= "Where ";
      $sql .= $campo_id."='".$id."';";
      
      $cxn = new ConexionBD();
      
			if(!$rst = $cxn->ConexionBaseDatos($sql)) 
        return false;
			
			return true;
		}
    
    /**
    * Funci?n Borrar Registro de una sola llave primaria
    * @param String $tabla Nombre de la Tabla
    * @param String $id Identificacion del Registro a Modificar
    * @param String $campo_id Es el Campo o nombre de la llave Primaria
    *
    * @return Boolean
    */
    function Borrar_Registro($tabla,$id,$campo_id)
		{
      $sql  = "Delete from ".$tabla." ";
      $sql .= "Where ".$campo_id." = '".$id."';";
            
      $cxn = new ConexionBD();
     //$this->debug=true; 
			if(!$rst = $cxn->ConexionBaseDatos($sql)) 
        return false;
			
			return true;
		}
    /**
    * Funcion q suma una fecha con un dias.
    *
    * @param Date $fecha fecha, dd/mm/yyyy
    * @param integer $dia Dia que necesita sumar
    * @param string $sepa Separador a usar
    * 
    * @return date
    */
    function sumaDia($fecha,$dia,$sepa = "-")
    {	
      list($day,$mon,$year) = explode($sepa,$fecha);
	    return date("d".$sepa."m".$sepa."Y",mktime(0,0,0,$mon,$day+$dia,$year));		
    }
    /**
    * Funci?n donde se obtiene el nombre del producto segun parametros de configuracion
    *
    * @param array $producto Datos del producto
    * @param String $empresa Identificador de la empresa
    *
    * @return Boolean
    */
    function NombreProducto($producto,$empresa)
		{
      if(!$this->pasoSql)
      {
        $sql  = "SELECT campo ";
        $sql .= "FROM   productos_campos_descripcion_medicmanetos ";
        $sql .= "WHERE  empresa_id = '".$empresa."' ";
        $sql .= "ORDER BY indice_orden ";
              
        $cxn = new ConexionBD();

  			if(!$rst = $cxn->ConexionBaseDatos($sql)) 
          return false;
  			
  			while (!$rst->EOF)
  			{
  				$this->datosNombreProducto[] = $rst->GetRowAssoc($ToUpper = false);
  				$rst->MoveNext();
  		  }
  			$rst->Close();
        $this->pasoSql = true;
      }
      
      $nombre = "";
      foreach($this->datosNombreProducto as $k => $datos)
        $nombre .= $producto[$datos['campo']]." ";
      
      if(trim($nombre) == "")
        $nombre = $producto['descripcion'];
			return $nombre;
		}
    
		/**
		* Funcion Para Calcular el Numero de d?as del Mes.
		* @param String $Mes
		* @param String $A?o
		*
		* Return String
		*/
		function ObtenerDiasDelMes($Mes,$Anio)  
		{  
			if( is_callable("cal_days_in_month"))  
			{  
			//print_r("Hola");
			return cal_days_in_month(CAL_GREGORIAN, $Mes, $Anio);  
			}  
		else  
			{  
			return date("t",mktime(0,0,0,$Mes,1,$Anio));  
			}  
		}  
		/**
		* Funcion Para Obtener Los Periodos Semanales Por Mes Teniendo en cuenta
		* Que el mes empieza desde 01
		* El Arreglo que retorna es [#]['final']: Indica el d?a final del corte semanal
		* El Arreglo que retorna es [#]['siguiente']: Indica el d?a que empieza el corte Semanal siguiente
		* El Indice [#]  indica el numero de la semana en el mes.
		* El Indice [4] Solo tiene 'Final' debido a que es el ultimo dia del mes.
		* @param String $Fecha (AAAA-MM-DD)
		*
		* Return Array
		*/
		function Obtener_PeriodosSemanales($fecha)
		{
			/*$fecha = '2011-08-01';*/
			
			$fecha_explode = explode('-',$fecha);
			$diasMes=$this->ObtenerDiasDelMes($fecha_explode[1],$fecha_explode[0]);
			for($i=1;$i<=4;$i++)
			{
			if ($i===1 || $i===2 || $i===3)
			{
				$periodos_semanales[$i]['final'] = date('Y-m-d',strtotime($fecha.' +1 week -1 day'));
				$periodos_semanales[$i]['siguente'] = date('Y-m-d',strtotime($fecha.' +1 week'));
			}
			if ($i===4)
			$periodos_semanales[$i]['final'] = date('Y-m-d',strtotime($fecha_explode[0]."-".$fecha_explode[1]."-".$diasMes));
			/*$this->salida .= '<label>Dia Siguiente: '.  date('Y-m-d',strtotime($fecha.' +1 week')) ."\n</label> <br><hr>";*/
			$fecha=date('Y-m-d',strtotime($fecha.' +1 week'));
			}
			/*$this->salida .= $PrimeraSemana ." - ".$SegundaSemana." - ".$TerceraSemana." - ".$CuartaSemana;*/
		return $periodos_semanales;
		}
		
		  /**
    * Funci?n Javascript para la comparacion de fechas
	* La funcion recibe fechas en el formato DD/MM/YYYY
	* Retorna 0 si la Primera Fecha es Menor a la Segunda.
	* Retorna 1 Si la Primera Fecha es Mayor a la Segunda
	* Retorna -1 si Son Iguales
    * @param 
    * Return string
    */
    function CompararFechas_Javascript()       
    {
	$script .= "<script>";
	/*
	* Script para Convertir Un String a Fecha. El Formato que 
	* deben ir las fechas es de DD/MM/AAAA
	*/
	$script .= "	function convertirAFecha(string) 
						{
							var date = new Date()
							mes = parseInt(string.substring(3, 5));
							date.setMonth(mes - 1); //en javascript los meses van de 0 a 11
							date.setDate(string.substring(0, 2));
							date.setYear(string.substring(6, 10));
							return date;
						}";
	
	$script .= " function CompararFechas(fecha_1,fecha_2) ";
	$script .= " { ";
	
	$script .= "		var fecha1=0;";
	$script .= "		var fecha2=0;";
		
	$script .= "		fecha_1 = convertirAFecha(fecha_1);";
	$script .= "		fecha_2 = convertirAFecha(fecha_2);";
		
	$script .= " 		fecha1 = Date.parse(fecha_1);
							fecha2 = Date.parse(fecha_2); ";
	$script .= " 	if( fecha1 < fecha2 ) 
							return 0;
								else if( fecha1 > fecha2 ) 
									return 1; 
									else
										return -1; ";
	/*$script .= " 	if( fecha1 < fecha2 ) 
							alert(fecha1 + \" es menor a \" + fecha2);
								else if( fecha1 > fecha2 ) 
									alert(fecha1 + \" es mayor \" + fecha2); 
									else
										alert(fecha1 + \" es igual a \" + fecha2); ";*/
	$script .= " } ";
	$script .= "</script>";
      
	return ($script);
    }
	
	/*
	* Funcion que permite Eliminar Directorios y su
	* Contenido.
	* Ojo, si la ruta del directorio no es Correcta Pueden Borrar cosas
	* Que Lamentaran Bastante
	* @param String $dirname Ruta del Directorio
	* Return Boolean
	*/
	
	function BorrarDirectorio($dirname) {
   if (is_dir($dirname)) {    //Operate on dirs only
       $result=array();
       if (substr($dirname,-1)!='/') {$dirname.='/';}    //Append slash if necessary
       $handle = opendir($dirname);
       while (false !== ($file = readdir($handle))) {
           if ($file!='.' && $file!= '..') {    //Ignore . and ..
               $path = $dirname.$file;
               if (is_dir($path)) {    //Recurse if subdir, Delete if file
                   $result=array_merge($result,BorrarDirectorio($path));
               }else{
                   unlink($path);
                   $result[].=$path;
               }
           }
       }
       closedir($handle);
       rmdir($dirname);    //Remove dir
       $result[].=$dirname;
       return $result;    //Return array of deleted items
   }else{
       return false;    //Return false if attempting to operate on a file
   }
}   
		
	}
?>