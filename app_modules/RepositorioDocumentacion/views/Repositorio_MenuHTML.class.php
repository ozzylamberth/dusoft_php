<?php

/* * *******************************************************
 * @package DUANA & CIA
 * @version 1.0 $Id: Repositorio_MenuHTML.class
 * @copyright DUANA & CIA
 * @author R.O.M.A
 * ******************************************************** */

/* * *********************************************************
 * Clase Vista: Repositorio_MenuHTML
 * Clase Contiene menus de modulo 
 * ********************************************************** */

class Repositorio_MenuHTML {
    /*     * ******************************************************
     * Constructor de la clase
     * ****************************************************** */

    function Repositorio_MenuHTML() {
        
    }

    /*     * ******************************************************************
     * Menu de bodegas empresa
     * ****************************************************************** */

    function MenuBod($action, $bodegas) {
        $emp = SessionGetVar('empresa_id');
        $html = ThemeMenuAbrirTabla('FARMACIAS EMPRESA - ' . $emp, "50%");
        $html .="	<table width='100%' border='0' cellspacing=\"0\" cellpadding=\"12\">";
        $html .="		<tr>";
        $html .="			<td>\n";
        $html .="				<table border='0' width='0' cellspacing=\"0\" cellpadding=\"0\">\n";
        $html .="					<tr>\n";
        $html .="						<td class='normal_10N'><img src=\"" . GetThemePath() . "/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;FARMACIAS</td>\n";
        $html .="					</tr>";
        $html .="				</table>";
        foreach ($bodegas as $key => $value) {
            $href = "";
            //llamado a submenu de opciones
            $href = ModuloGetURL('app', 'RepositorioDocumentacion', 'controller', 'SubMenuOp') . "&datos[empresa_id]=" . $emp . "&datos[bodega]=" . $value['bodega'] . "&datos[bodname]=" . $value['descripcion'];
            $html .=ThemeSubMenuTabla("<a href=\"" . $href . "\">" . $value['descripcion'] . "</a>", "100%");
        }
        $html .="			</td>";
        $html .="		</tr>";
        $html .="	</table>";
        if (!empty($action['volver'])) {
            $html .="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">";
            $html .="  <tr>";
            $html .="  <td align='center' class=\"label_error\">\n";
            $html .="  <a href='" . $action['volver'] . "'>VOLVER</a>";
            $html .="  </td>";
            $html .="  </tr>";
            $html .="  </table>";
        }
        $html .=ThemeMenuCerrarTabla();

        return $html;
    }

    /*     * ******************************************************
     * SubMenu de opciones de repositorio
     * ****************************************************** */

    function FormaSubMenu($action, $empresa, $bodega, $bodname) {
        $html = ThemeAbrirTabla('MENU DE OPCIONES REPOSITORIO');

        $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
        $html .= "<tr class=\"label\">\n";
        $html .= "  <td align=\"right\">\n";
        $html .= "   [" . $bodname . "]";
        $html .= "  </td>\n";
        $html .= "</tr>\n";
        $html .= "</table>\n";
        $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "     <td align=\"center\">M E N U\n";
        $html .= "     </td>\n";
        $html .= "  </tr>\n";
        $link1 = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "Control_tipoArchivos") . "&datos[empresa_id]=" . $empresa . "&datos[bodega]=" . $bodega . "&datos[bodname]=" . $bodname;
        $link2 = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "DocsRepositorio") . "&datos[empresa_id]=" . $empresa . "&datos[bodega]=" . $bodega . "&datos[bodname]=" . $bodname;
        $link3 = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "DocsRepositorioProd") . "&datos[empresa_id]=" . $empresa . "&datos[bodega]=" . $bodega . "&datos[bodname]=" . $bodname;
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td   class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $link1 . "\">CARGAR ARCHIVOS AL REPOSITORIO</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td   class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $link2 . "\">CONSULTAR DOCUMENTOS</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr  class=\"modulo_list_oscuro\">\n";
        $html .= "      <td   class=\"label\" align=\"center\">\n";
        $html .= "        <a href=\"" . $link3 . "\">CONSULTAR MEDICAMENTOS EN DOCS. ESPECIALES</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "<br>";
        $html .= "  <tr>\n";
        $html .= "      <td align=\"center\" class=\"label_error\">\n";
        $html .= "        <a href=\"" . $action['volver'] . "\">VOLVER</a>\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*     * ******************************************************************************
     * Vista: seleccion tipo de documento a subir al repositorio y tipo de
     * datos requeridos para el cargue del archivo
     * ******************************************************************************* */

    function FormaTipoArchivoRep($action, $datosTipo, $empresa, $bodega) {
        $ctl = AutoCarga::factory("ClaseUtil");
        $url = ModuloGetURL("app", "RepositorioDocumentacion", "controller", "Subirimagen");

        $html = $ctl->AcceptDate('/');
        $html .= "<script>\n";
        $html .= "   var contenedor1=''\n";
        $html .= "   var titulo1=''\n";
        $html .= "   var hiZ = 2;\n";

        $html .= "   function Iniciar4(tit)\n";
        $html .= "   {\n";
        $html .= "       contenedor1 = 'containerBus';\n";
        $html .= "       titulo1 = 'tituloBus';\n";
        $html .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $html .= "        Capa = xGetElementById(contenedor1);\n";
        $html .= "       xResizeTo(Capa, 700, 422);\n";
        $html .= "       xMoveTo(Capa, xClientWidth()/11, xScrollTop()+100);\n";
        $html .= "       ele = xGetElementById(titulo1);\n";
        $html .= "       xResizeTo(ele, 700, 20);\n";
        $html .= "       xMoveTo(ele, 0, 0);\n";
        $html .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $html .= "       ele = xGetElementById('cerrarBus');\n";
        $html .= "       xResizeTo(ele, 20, 20);\n";
        $html .= "       xMoveTo(ele, 680, 0);\n";
        $html .= "   }\n";

        $html .= "   function myOnDragStart(ele, mx, my)\n";
        $html .= "   {\n";
        $html .= "     window.status = '';\n";
        $html .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
        $html .= "     else xZIndex(ele, hiZ++);\n";
        $html .= "     ele.myTotalMX = 0;\n";
        $html .= "     ele.myTotalMY = 0;\n";
        $html .= "   }\n";
        $html .= "   function myOnDrag(ele, mdx, mdy)\n";
        $html .= "   {\n";
        $html .= "     if (ele.id == titulo1) {\n";
        $html .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
        $html .= "     }\n";
        $html .= "     else {\n";
        $html .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $html .= "     }  \n";
        $html .= "     ele.myTotalMX += mdx;\n";
        $html .= "     ele.myTotalMY += mdy;\n";
        $html .= "   }\n";
        $html .= "   function myOnDragEnd(ele, mx, my)\n";
        $html .= "   {\n";
        $html .= "   }\n";

        $html .= "	function acceptNum(evt)\n";
        $html .= "	{\n";
        $html .= "		var nav4 = window.Event ? true : false;\n";
        $html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
        $html .= "		return (key == 8 || key <=13 || (key >= 48 && key <= 57));\n";
        $html .= "	}\n";
		
		/*****************************************************************************************************/
		$html .= "	function selectivo(evt)\n";
        $html .= "	{\n";
		$html .= "  if(evt === \"11\") { ";
		
		//LIMPIAR CAMPOS
		$html .= "		document.getElementById(\"nomQuienEntrega\").value = \" \";\n";
		$html .= "		document.getElementById(\"nomQuienRecibe\").value = \" \";\n";
		$html .= "		document.getElementById(\"valorFacturado\").value = \" \";\n";
		$html .= "		document.getElementById(\"nro_idenficacion\").value = \" \";\n";
		$html .= "		document.getElementById(\"tipoIdPaciente\").value = \" \";\n";
		
		$html .= "		document.getElementById(\"tipoSelectivo\").style.display = \"block\";\n";
		$html .= "		document.getElementById(\"tipoSelectivoLabel\").style.display = \"block\";\n";
        //$html .= "		document.getElementById(\"ocultarSelectivo\").style.display = \"block\";\n";
		$html .= "		document.getElementById(\"selectivoEstado\").style.display = \"block\";\n";
		$html .= "		document.getElementById(\"selectivoEstadoLabel\").style.display = \"block\";\n";
		//OCULTAR TEXTFIELD NUMERO DE FORMULA
		$html .= "		document.getElementById(\"nom_infor\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"nom_infor_label\").style.display = \"none\";\n";
		
		//OCULTAR TEXTFIELD VALOR FACTURADO
		$html .= "		document.getElementById(\"valorFacturado\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"valorFacturadoLabel\").style.display = \"none\";\n";
		
		//OCULTAR TEXTFIELD QUIEN ENTREGA
		$html .= "		document.getElementById(\"nomQuienEntrega\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"nomQuienEntregaLabel\").style.display = \"none\";\n";
		
		//OCULTAR TEXTFIELD QUIEN RECIBE
		$html .= "		document.getElementById(\"nomQuienRecibe\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"nomQuienRecibeLabel\").style.display = \"none\";\n";
		
		
		//OCULTAR TEXTFIELD NUMERO IDENTIFICACION
		$html .= "		document.getElementById(\"nro_idenficacion_label\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"nro_idenficacion\").style.display = \"none\";\n";
		
		
		//MOSTRAR DROPDOWN TIPO ID PACIENTE
		$html .= "		document.getElementById(\"tipoIdPacienteLabel\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"tipoIdPaciente\").style.display = \"none\";\n";
		
		$html .= "	}";
		
		$html .= "  else if(evt === \"3\") { ";
		//LIMPIAR CAMPOS
		$html .= "		document.getElementById(\"tipoSelectivo\").value = \" \";\n";
		$html .= "		document.getElementById(\"tipoSelectivo\").text = \" \";\n";
		$html .= "		document.getElementById(\"nomQuienEntrega\").value = \" \";\n";
		$html .= "		document.getElementById(\"nomQuienRecibe\").value = \" \";\n";
		$html .= "		document.getElementById(\"valorFacturado\").value = \" \";\n";
		$html .= "		document.getElementById(\"selectivoEstado\").value = \" \";\n";
		$html .= "		document.getElementById(\"selectivoEstado\").text = \" \";\n";
		
		$html .= "		document.getElementById(\"tipoSelectivo\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"tipoSelectivoLabel\").style.display = \"none\";\n";
        //$html .= "		document.getElementById(\"ocultarSelectivo\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"selectivoEstado\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"selectivoEstadoLabel\").style.display = \"none\";\n";
		//OCULTAR TEXTFIELD QUIEN ENTREGA
		$html .= "		document.getElementById(\"nomQuienEntrega\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"nomQuienEntregaLabel\").style.display = \"none\";\n";
		
		$html .= "		document.getElementById(\"nomQuienRecibe\").style.display = \"none\";\n";
		
		$html .= "		document.getElementById(\"nomQuienRecibeLabel\").style.display = \"none\";\n";
		
		//MOSTRAR TEXTFIELD NUMERO DE FORMULA
		$html .= "		document.getElementById(\"nom_infor\").style.display = \"block\";\n";
		$html .= "		document.getElementById(\"nom_infor_label\").style.display = \"block\";\n";
		
		//OCULTAR TEXTFIELD VALOR FACTURADO
		$html .= "		document.getElementById(\"valorFacturado\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"valorFacturadoLabel\").style.display = \"none\";\n";
		
		//MOSTRAR TEXTFIELD NUMERO IDENTIFICACION
		$html .= "		document.getElementById(\"nro_idenficacion_label\").style.display = \"block\";\n";
		$html .= "		document.getElementById(\"nro_idenficacion\").style.display = \"block\";\n";
		
		//MOSTRAR DROPDOWN TIPO ID PACIENTE
		$html .= "		document.getElementById(\"tipoIdPacienteLabel\").style.display = \"block\";\n";
		$html .= "		document.getElementById(\"tipoIdPaciente\").style.display = \"block\";\n";
		
		$html .= "	}";
		
		$html .= "else{";
		
		$html .= "		document.getElementById(\"tipoSelectivoLabel\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"tipoSelectivo\").style.display = \"none\";\n";
        //$html .= "		document.getElementById(\"ocultarSelectivo\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"selectivoEstado\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"selectivoEstadoLabel\").style.display = \"none\";\n";
		//MOSTRAR TEXTFIELD NUMERO DE FORMULA
		$html .= "		document.getElementById(\"nom_infor\").style.display = \"block\";\n";
		$html .= "		document.getElementById(\"nom_infor_label\").style.display = \"block\";\n";
		
		//MOSTRAR TEXTFIELD VALOR FACTURADO
		$html .= "		document.getElementById(\"valorFacturado\").style.display = \"block\";\n";
		$html .= "		document.getElementById(\"valorFacturadoLabel\").style.display = \"block\";\n";//
		
		//OCULTAR TEXTFIELD QUIEN ENTREGA
		$html .= "		document.getElementById(\"nomQuienEntrega\").style.display = \"block\";\n";
		$html .= "		document.getElementById(\"nomQuienEntregaLabel\").style.display = \"block\";\n";
		
		//OCULTAR TEXTFIELD QUIEN RECIBE
		$html .= "		document.getElementById(\"nomQuienRecibe\").style.display = \"block\";\n";
		$html .= "		document.getElementById(\"nomQuienRecibeLabel\").style.display = \"block\";\n";
		
		
		//OCULTAR TEXTFIELD NUMERO IDENTIFICACION
		$html .= "		document.getElementById(\"nro_idenficacion_label\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"nro_idenficacion\").style.display = \"none\";\n";
		
		//OCULTAR DROPDOWN TIPO ID PACIENTE
		$html .= "		document.getElementById(\"tipoIdPacienteLabel\").style.display = \"none\";\n";
		$html .= "		document.getElementById(\"tipoIdPaciente\").style.display = \"none\";\n";
		$html .= "	}";
		
		//LIMPIAR CAMPOS 
		$html .= "		document.getElementById(\"tipoSelectivo\").value = \" \";\n";
		$html .= "		document.getElementById(\"tipoSelectivo\").text = \" \";\n";
		
		$html .= "		document.getElementById(\"selectivoEstado\").value = \" \";\n";
		$html .= "		document.getElementById(\"selectivoEstado\").text = \" \";\n";
		$html .= "		document.getElementById(\"nro_idenficacion\").value = \" \";\n";
		$html .= "		document.getElementById(\"tipoIdPaciente\").value = \" \";\n";
		
		$html .= "		document.getElementById(\"selectivoEstado\").value = \" \";\n";
		
        $html .= "	}\n";
		/********************************************************************************************************/
		
        $html .= "function MostrarCapa(id){\n  ";
        $html .= "    if (document.getElementById){ \n"; //se obtiene el id
        $html .= "        var el = document.getElementById(id); \n";
        $html .= "        el.style.display =\"\"; \n";
        $html .= "     } \n";
        $html .= " } \n";

        $html .= "function Cerrar(Elemento)\n";
        $html .= "{\n";
        $html .= "    var capa = document.getElementById(Elemento);\n";
        $html .= "          capa.style.display = \"none\";\n";
        $html .= "}\n";

        $html .= "function mOvr(src,clrOver) ";
        $html .= "{ ";
        $html .= "   src.style.background = clrOver; ";
        $html .= "} ";
        $html .= "function mOut(src,clrIn) ";
        $html .= "{  ";
        $html .= "  src.style.background = clrIn; ";
        $html .= "}  ";

        $html .= "</script>\n";

        $html .="<script language=\"JavaScript\"> ";
        $html .="function acceptm(evt) ";
        $html .="{ ";
        $html .="  var nav4 = window.Event ? true : false; ";
        $html .="  var key = nav4 ? evt.which : evt.keyCode; ";
        $html .="  return (key != 13 ); ";
        $html .="} ";

        $html .="function recogerTeclaBus(evt) ";
        $html .="{ ";
        $html .="  var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   ";
        $html .="  var keyChar = String.fromCharCode(keyCode); ";
        $html .="  if(keyCode==13) "; //Si se pulsa enter da directamente el resultado
        $html .="   {         ";
        $html .="     Bus_Pro(document.getElementById('empresa_busq').value,document.getElementById('bodega_busq').value,document.getElementById('tip_bus').value,document.getElementById('criterio').value,1); ";
        $html .="   }         ";
        $html .="}            ";

        $html .="function Bus_Pro(empresa_id,bodega,tip_bus,criterio,offset) ";
        $html .="{   ";
        $html .="    xajax_BuscarProducto1(empresa_id,bodega,tip_bus,criterio,offset); ";
        $html .="}   ";

        $html .="function AsignarPro(codigo_producto,descripcion) ";
        $html .="{   ";
        $html .="  document.getElementById('codigo_pro').value=codigo_producto; ";
        $html .="  document.getElementById('descrip_pro').value=descripcion;   ";
        $html .="  Cerrar('containerBus');  ";
        $html .="}   ";

        $html .="function ValidaProdTemp(frmgral)  ";
        $html .="{  ";
        $html .="  if(frmgral.codigo_pro.value==\"\" || frmgral.descrip_pro.value==\"\")  ";
        $html .="   { ";
        $html .="     document.getElementById('error_productos').innerHTML = 'NO SE HAN SELECCIONADO PRODUCTOS'; ";
        $html .="     return;  ";
        $html .="   } ";
        $html .="  if(frmgral.nradicado.value==\"\")  ";
        $html .="   { ";
        $html .="     document.getElementById('error_productos').innerHTML = 'SE DEBE DIGITAR EL NRO. RADICADO TUTELA'; ";
        $html .="     return;  ";
        $html .="   } ";
        $html .="   document.getElementById('error_productos').innerHTML =\"\";  ";
        $html .= "  xajax_guardarProdsTemp(frmgral.tipo_arch.value,frmgral.codigo_pro.value,frmgral.descrip_pro.value,frmgral.nradicado.value);  ";
        $html .="} ";
        
        $html .= "
                    function agregar_productos_factura(campos){
                        
                        if(campos.codigo_pro.value=='' || campos.descrip_pro.value==''){
                            document.getElementById('error_productos').innerHTML = 'NO SE HAN SELECCIONADO PRODUCTOS';
                            return;
                        }
                        
                        if(campos.num_factura.value == '0' || campos.num_factura.value==''){
                            document.getElementById('error_productos').innerHTML = 'SE DEBE DIGITAR EL NRO. DE FACTURA';
                            return;
                        }
                        document.getElementById('error_productos').innerHTML ='';
                        
                        xajax_guardarProdsTemp(campos.tipo_arch.value,campos.codigo_pro.value,campos.descrip_pro.value,campos.num_factura.value);
                    }
        ";
		
		//guardarProductoCMT
			$html .="function guardarProductoCTC(frmgral)  ";
			$html .="{  ";
			$html .="  if(frmgral.num_formula.value==\"\")  ";
			$html .="   { ";			
			$html .="     document.getElementById('error_productos').innerHTML = 'DEBE DIGITAR EL NUMERO DE FORMULA'; ";		
			$html .="     return;  ";	
			$html .="   } ";
			$html .="   document.getElementById('error_productos').innerHTML =\"\";  ";
			$html .="   xajax_guardarProdsTempCTC(frmgral.tipo_arch.value, frmgral.codigo_pro.value, frmgral.descrip_pro.value, frmgral.num_formula.value);  ";
			$html .="} ";
		
        $html .="function MostrarSelProd() ";
        $html .="{ ";
        $html .="  document.getElementById('codigo_pro').value=\"\";       ";
        $html .="  document.getElementById('descrip_pro').value=\"\";       ";
        $html .="    var selp = document.getElementById('selec_prod_temp'); ";
        $html .="    selp.style.display=\"\"; ";
        $html .="} ";
		//NUEVA FUNCION AÃ‘ADIDA 14/12/2015
		$html .="function EliminaProdTmpCMT(frmgral,codigo) ";
		$html .="{ ";
		$html .="  xajax_EliminaProductoTmpCTC(frmgral.tipo_arch.value, frmgral.num_formula.value, codigo); ";
		$html .="} ";
        $html .="function EliminaProdTmp(frmgral,codigo ) ";
        $html .="{  var numeros = \"\";							  ";
		$html .=" if(frmgral.tipo_arch.value =='5'){  numeros = frmgral.num_factura.value }   ";
		$html .=" if(frmgral.tipo_arch.value =='10'){  numeros = frmgral.nradicado.value }   ";
        $html .="  xajax_EliminaProductoTmp(frmgral.tipo_arch.value, numeros, codigo); ";
        $html .="} ";

        $html .="function confirmar(){";
        $html .="var num_formula = document.getElementById('num_formula').value;";
        $html .="var num_id = document.getElementById('num_id').value;";
        $html .="var nombrePaciente = document.getElementById('nombrePaciente').value;";
        $html .="var medicoFormula = document.getElementById('medicoFormula').value;";
        $html .="var fecha_infor = document.getElementById('fecha_infor').value;";
        $html .="var observacion = document.getElementById('observacion').value;";
        $html .="if(num_formula.trim() =='' || num_id.trim() =='' || nombrePaciente.trim() =='' || medicoFormula.trim() =='' || fecha_infor.trim() =='' || observacion.trim() ==''){";
        $html .=" alert('SE DEBE INGRESAR TODOS LOS DATOS');";
        $html .="return false;";
        $html .=" }else{";
        $html .=" document.formCargarArchivo.submit();";
        $html .=" }";
        $html .="}";
        $html .="</script> ";
        /* END SCRIPTS */
		$html .= ReturnOpenCalendarioScript("formCargarArchivo", "fecha_infor", '-') . "\n";
		$html .= ReturnOpenCalendarioScript("formCargarArchivo", "fecha_fac", '-') . "\n";
        $html .= ThemeAbrirTabla('CARGAR ARCHIVOS AL REPOSITORIO', '80%');
        $html .= "<form name=\"formCargarArchivo\" id=\"formCargarArchivo\" action=\"" . $url . "\" method=\"post\">";
        $html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "	<tr>\n";
        $html .= "		<td>\n";
        $html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "		    <tr class=\"normal_10AN\">\n";
        $html .= "		      <td align=\"center\"> TIPO DE DOCUMENTO A SUBIR.</td>\n";
        $html .= "            <td class=\"modulo_table_list_title\">";
        $html .= "		        <select id=\"tipo_arch\" name=\"tipo_arch\" class=\"select\" onchange=\"xajax_Campos_tipoArch(this.value,'" . $empresa . "','" . $bodega . "');\">\n";
        $html .= "		          <option value=\"-1\">--SELECCIONAR--</option>\n";
        foreach ($datosTipo as $clave => $valor) {
            $html .= "		          <option value=\"" . $valor['cod_tipo'] . "\">" . $valor['tipo_nombre'] . "</option>\n";
        }
        $html .= "              </select>\n";
        $html .= "            </td>";
        $html .= "            <td align=\"center\">";
        $html .= "            </td>";
        $html .= "		    </tr>\n";
		
		/*$html .= "      <td  class=\"\">";
        $html .= "        <input type=\"text\" name=\"buscador[fecha]\" id=\"fecha\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" class=\"input-text\" style=\"width:100%\">";
        $html .= "      " . ReturnOpenCalendario('formCargarArchivo', 'fecha', '/') . "\n";
        $html .= "      </td>\n";*/
		
        $html .= "		  </table>\n";
        $html .= "		</td>\n";
        $html .= "	</tr>\n";
        $html .= "  <tr class=\"normal_10AN\">";
        $html .= "    <td align=\"center\">";
        $html .= "	    <div id=\"capa_tipos\">\n";
        $html .= "	    </div>\n";
        $html .= "     </td>";
        $html .= "  </tr>";
        $html .= "  <tr>";
        $html .= "    <td>";
        $html .= "      <input type=\"hidden\" name=\"datos[empresa_id]\" id=\"empresa_id\" value=\"" . $empresa . "\">"; //datos xra permiso del modulo		
        $html .= "      <input type=\"hidden\" name=\"datos[bodega]\" id=\"bodega\" value=\"" . $bodega . "\">";    //datos xra permiso del modulo		
        $html .= "    </td>";
        $html .= "  </tr>";
        $html .= " </table>";
        /* Capa oculta */
        $html .= "     <div id=\"containerBus\" class='d2Container' style=\"display:none\">\n";
        $html .= "        <div id='tituloBus' class='draggable' style=\"text-transform: uppercase;\"></div>";
        $html .= "        <div id='cerrarBus' class='draggable'><a style='cursor: pointer;' title=\"Cerrar\" onclick=\"javascript:Cerrar('containerBus')\">X</a></div>";
        $html .= "        <div id='errorBus' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $html .= "        <div id='ContenidoBus'><br><br>\n";
        $html .= "                 <form name=\"formaBusProd\" action=\"\" method=\"post\">\n";
        $html .= "                 <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                       <td COLSPAN='2' align=\"center\">\n";
        $html .= "                          BUSQUEDA DE PRODUCTOS";
        $html .= "                          <input type=\"hidden\" id=\"empresa_busq\" value=\"" . $empresa . "\">\n";
        $html .= "                          <input type=\"hidden\" id=\"bodega_busq\" value=\"" . $bodega . "\">\n";
        $html .= "                       </td>\n";
        $html .= "                    </tr>\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                       <td width=\"35%\" align=\"center\">\n";
        $html .= "                          BUSCAR POR ";
        $html .= "                       <select id=\"tip_bus\" name=\"tip_bus\" class=\"select\" onchange=\"\">";
        $html .= "                           <option value=\"1\" SELECTED>DESCRIPCION</option> \n";
        $html .= "                           <option value=\"2\">CODIGO</option> \n";
        $html .= "                       </select>\n";
        $html .= "                       </td>\n";
        $html .= "                       <td width=\"55%\" align=\"left\" id=\"ventanatabla\">\n";
        $html .= "                          CRITERIO";
        $html .= "                          <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"30\" onkeypress=\"return acceptm(event);\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";
        $html .= "                       </td>\n";
        $html .= "                    </tr>\n";
        $html .= "                </table>\n";
        $html .= "                </form>\n";
        $html .= "                 <br>\n";
        $html .="              <div id=\"tabla_bus\">\n";
        $html .="              </div>\n";
        $html .= "        </div>\n";
        $html .= "     </div>\n";
        /* Capa oculta */
        $html .= "</form>";
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "	<tr>\n";
        $html .= "		<td align=\"center\"><br>\n";
        $html .= "			<form name=\"form\" action=\"" . $action['volver'] . "\" method=\"post\">";
        $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
        $html .= "			</form>";
        $html .= "		</td>";
        $html .= "	</tr>";
        $html .= "</table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*     * ***************************************************************************
     * Funcion vista: Forma cargar archivo
     * @param array $action Vector que continen los link de la aplicacion
     * @param array 
     * @return 
     * **************************************************************************** */

    function FormaSubir($action, $request, $tipoDoc) {
       // echo "<pre>aaaaaaa";print_r($request);
        //$request['tipo_arch'] : tipo de archivo a subir
        $cls = AutoCarga::factory("Permisos", "", "app", "RepositorioDocumentacion");
        $campos_h = $cls->GetFields_tipo($request['tipo_arch']);

        $html = ThemeAbrirTabla('CARGAR ARCHIVOS AL REPOSITORIO', '70%');
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\">\n";
        $html .= "	<tr>\n";
        $html .= "		<td>\n";
        $html .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
        $html .= "				<tr>\n";
        $html .= "					<td align=\"center\" height=\"40%\" class=\"formulacion_table_list\">";
        $html .= "                  <b style=\"color:#ffffff\">S E L E C C I O N A R  -  A R C H I V O</b>";
        $html .= "                  </td>\n";
        $html .= "				</tr>\n";
        $html .= "				<tr>\n";
        $html .= "				  <td>\n";

        $html .= " <form name=\"form8\" method=\"post\" enctype=\"multipart/form-data\" action=\"" . $action['imagenCtrler'] . "\">";
        $html .= "<table  width=\"70%\" align=\"center\" border=\"1\" >\n";
        $html .= "  <tr  class=\"modulo_list_claro\" align=\"center\" >\n";
        $html .= "      <td  colspan=\"10\" ><b>TIPO DOCUMENTO :</b></td>\n";
        $html .= " <br>";
        $html .= "      <td colspan=\"10\" >" . $tipoDoc['tipo_nombre'] . "</td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= " <br>";
        $html .= " <table width=\"100\" border=\"1\" align=\"center\">";
        $html .= " <tr class=\"modulo_list_claro\" > ";
        $html .= " <td class=\"modulo_list_claro\"  ><b>ARCHIVO</b> ";
        $html .= "    </td>\n";
        $html .= "    <td>\n";
        $html .= "     <input type=\"file\" name=\"archivo\" size=\"30\" style=\"border: 1px solid #7F9DB7;\" >";
        $html .= "    </td>\n";
        $html .= " </tr>  ";
        $html .= "</table>\n";
        $html .= " <table width=\"50\" border=\"0\" align=\"center\" class=\"modulo_list_title\" >";
        /* Campos ocultos request */
        $html .= "		<tr>\n";
        $html .= "		  <td>\n";
        $html .= "         <input type=\"hidden\" name=\"tipo_arch\" id=\"tipo_arch\" value=\"" . $request['tipo_arch'] . "\">";
        if ($request['tipo_arch'] == 11 ||$request['tipo_arch'] == 12 ||$request['tipo_arch'] == 13 ||$request['tipo_arch'] == 14 ||$request['tipo_arch'] == 4 || $request['tipo_arch'] == 7) {
            $html .= "         <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"" . $request['empresa_id'] . "\">";
            $html .= "         <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"" . $request['bodega'] . "\">";
        }
        foreach ($campos_h as $k => $v) {
            $html .= "        <input type=\"hidden\" name=\"" . $v['campo'] . "\" id=\"" . $v['campo'] . "\" value=\"" . $request[$v['campo']] . "\">";
        }                                 //El campo del formulario debe		
        $html .= "		  </td>\n";                         //debe tener el mismo nombre
        $html .= "		</tr>\n";                         //definido en tabla esm_documentos para
        /* --------------------------------- */                        //el tipo de archivoa procesar
        $html .= "		<tr>\n";
        $html .= "			<td align=\center\" >\n";
        $html .= "			  <input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"Guardar Imagen\">\n";
        $html .= "	  	    </td>\n";
        $html .= "		</tr>\n";
        $html .= "</table>\n";
        $html .= "</form>";

        $html .= "				  </td>\n";
        $html .= "				</tr>\n";
        $html .= "			</table>\n";
        $html .= "		</td>\n";
        $html .= "	</tr>\n";
        $html .= "	<tr>\n";
        $html .= "		<td align=\"center\"><br>\n";
        $html .= "			<form name=\"form\" action=\"" . $action['volver'] . "\" method=\"post\">";
        $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
        $html .= "			</form>";
        $html .= "		</td>";
        $html .= "	</tr>";
        $html .= "</table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*     * ******************************************************
     * Mensajes
     * ****************************************************** */

    function FormaMensajeModulo($action, $msg1) {
        $html = ThemeAbrirTabla('REPOSITORIO', '70%');
        $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
        $html .= "	<tr>\n";
        $html .= "		<td>\n";
        $html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "		    <tr class=\"normal_10AN\">\n";
        $html .= "		      <td align=\"center\">" . $msg1 . "</td>\n";
        $html .= "		    </tr>\n";
        $html .= "		  </table>\n";
        $html .= "		</td>\n";
        $html .= "	</tr>\n";
        $html .= "	<tr>\n";
        $html .= "		<td align=\"center\"><br>\n";
        $html .= "			<form name=\"form\" action=\"" . $action['volver'] . "\" method=\"post\">";
        $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
        $html .= "			</form>";
        $html .= "		</td>";
        $html .= "	</tr>";
        $html .= "</table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }

    /*     * ***************************************************************************
     * Funcion vista: Forma listado documentos del repositorio
     * @param array $action Vector que continen los link de la aplicacion
     * @param array 
     * @return 
     * **************************************************************************** */

    function Listado_DocsRep($action, $todasEmpresas, $Dwnld, $ruta_archivo, $datosTipo, $DatosRep, $conteo, $pagina) {
        $html = ThemeAbrirTabla(' DOCUMENTOS REPOSITORIO ');

        $ctl = AutoCarga::factory("ClaseUtil");
        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("/");
        $html .= $ctl->AcceptNum("-");
        $html .= $ctl->LimpiarCampos();
        $html .= $ctl->RollOverFilas();
        $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
        $html .= "<br>";
        $html .= "<form name=\"Buscador\" id=\"Buscador\" action=\"" . $action['buscador'] . "\" method=\"post\">";
        $html .= "<table  width=\"50%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
        $html .= "  <tr align=\"center\" >\n";
        $html .= "      <td  class=\"formulacion_table_list\">TIPO DE DOCUMENTO</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\">";
        $html .= "           <select name=\"buscador[tipo_doc]\" id=\"buscador[tipo_doc]\" class=\"select\">   ";
        $html .= "            <option value=\"-1\">--SELECCIONAR--</option>";
        foreach ($datosTipo as $k => $v) {
            $html .= "            <option value=\"" . $v['cod_tipo'] . "\">" . $v['tipo_nombre'] . "</option>";
        }
        $html .= "           </select> ";
        $html .= "     </td>\n";
        $html .= "      <td  class=\"formulacion_table_list\">FECHA CREACION DCTO</td>\n";
        $html .= "      <td  class=\"\">";
        $html .= "        <input type=\"text\" name=\"buscador[fecha]\" id=\"fecha\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" class=\"input-text\" style=\"width:100%\">";
        $html .= "      " . ReturnOpenCalendario('Buscador', 'fecha', '/') . "\n";
        $html .= "      </td>\n";
        $html .= "	</tr>";
		
		
		
		
		
		

        $html .= "	<tr>";
        $html .= "   <td  colspan=\"2\" style=\"text-align:right\" class=\"formulacion_table_list\">EMPRESA</td>\n";
        $html .= "      <td colspan=\"2\" style=\"text-align:left\" class=\"formulacion_table_list\">";
        $html .= "           <select name=\"buscador[filtroempresa]\" id=\"buscador[filtroempresa]\" class=\"select\" onchange=\"xajax_AllBodegas(this.value);\">   ";
        $html .= "            <option value=\"-1\">--SELECCIONAR--</option>";
        foreach ($todasEmpresas as $key => $value) {
            $html .= "            <option value=\"" . $value['empresa_id'] . "\">" . $value['razon_social'] . "</option>";
        }
        $html .= "           </select> ";
        $html .= "     </td>\n";
        $html .= "	</tr>";

        $html .= "	<tr>";
        $html .= "   <td colspan=\"2\" style=\"text-align:right\" class=\"formulacion_table_list\">FARMACIA/BODEGA</td>\n";
        $html .= "      <td colspan=\"2\" style=\"text-align:left\" class=\"formulacion_table_list\">";
        $html .= "        <div id=\"select_bod\"> ";
        $html .= "           <select name=\"buscador[filtrobodega]\" id=\"buscador[filtrobodega]\" class=\"select\">   ";
        $html .= "            <option value=\"-1\">--SELECCIONAR--</option>";
        $html .= "           </select> ";
        $html .= "        </div> ";
        $html .= "     </td>\n";
        $html .= "	</tr>";

        $html .= "	<tr>";
        $html .= "	<td  class=\"formulacion_table_list\"  colspan=\"4\" align=\"center\"><input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\"></td>\n";
        $html .= "	</tr>";
        $html .= "</table>";
        $html .= "</form>";

        if (!empty($DatosRep)) {
            $pgn = AutoCarga::factory("ClaseHTML");
            $html .= "		" . $pgn->ObtenerPaginado($conteo, $pagina, $action['paginador']);
            $html .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                    <tr class=\"modulo_table_list_title\">\n";
            $html .= "                      <td align=\"center\" width=\"2%\">\n";
            $html .= "                        <a title=''>TIPO ARCHIVO </a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\" >\n";
            $html .= "                        <a title=''>NOMBRE DOC.</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\" >\n";
            $html .= "                        <a title='EXTENSION'>FORMATO</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO ORDEN REQUISICION'>ORDEN REQ.</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO ORDEN SUMINISTRO'>ORDEN SUMIN.</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO DE FORMULA'># FORMULA</a>";
            $html .= "                      </td>\n";
            
			//NUEVO
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='TIPO DE DOCUMENTO'>TIPO DE DOCUMENTO</a>";
            $html .= "                      </td>\n";
			
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO DE IDENTIFICACION PACIENTE'>ID PACIENTE</a>";
            $html .= "                      </td>\n";
			
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO DE GLOSA'># GLOSA</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO DE FACTURA DE LA GLOSA'>FAC. GLOSA</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO DE CORTE GENERADO'># CORTE</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='FECHA INICIAL DEL CORTE'>FECHA INIC. CORTE</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='FECHA FINAL DEL CORTE'>FECHA  FIN. CORTE</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NOMBRE DEL INFORME'>NOM. INFORME</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='FECHA INICIAL DEL INFORME'>FECHA INIC. INFOR</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='FECHA FINAL DEL INFORME'>FECHA  FIN. INFOR</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO DE LA FACTURA'># FACTURA</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='FECHA DE LA FACTURA'>FEC. FACTURA</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='TIPO DE FACTURACION'>TIPO FACTURA</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title=''>TIPO TUTELA</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO RADICADO TUTELA'>RADICADO</a>";
            $html .= "                      </td>\n";

			//NUEVO
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='MEDICO QUE FORMULA'>MEDICO QUIEN FORMULA</a>";
            $html .= "                      </td>\n";			
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='MEDICO QUE AUTORIZA'>MEDICO QUIEN AUTORIZA</a>";
            $html .= "                      </td>\n";
			
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='DURACION'>DURACION</a>";
            $html .= "                      </td>\n";
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='DURACION'>TIEMPO</a>";
            $html .= "                      </td>\n";


			
			//INFORME
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='TIPO DE PRODUCTO'>TIPO DE PRODUCTO</a>";
            $html .= "                      </td>\n";
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='QUIEN ENTREGA'>QUIEN ENTREGA</a>";
            $html .= "                      </td>\n";
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='QUIEN RECIBE'>QUIEN RECIBE</a>";
            $html .= "                      </td>\n";
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='VALOR FACTURADO'>VALOR CTC</a>";
            $html .= "                      </td>\n";
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='VALOR FACTURADO'>VALOR FACTURADO</a>";
            $html .= "                      </td>\n";
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='SELECTIVO'>SELECTIVO</a>";
            $html .= "                      </td>\n";
			
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='SELECTIVO'>TIPO SELECTIVO</a>";
            $html .= "                      </td>\n";
			
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO FORMULAS'>CANT. FORMULAS</a>";
            $html .= "                      </td>\n";
            //fecha entrega
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO FORMULAS'>FECHA ENTREGA</a>";
            $html .= "                      </td>\n";
            //observacion
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO FORMULAS'>OBSERVACIÓN</a>";
            $html .= "                      </td>\n";
			//
            $html .= "                      <td align=\"center\" >\n";
            $html .= "                        <a title='ACCIONES: DESCARGAR'>ACC.</a>";
            $html .= "                      </td>\n";
			
			
			
            $html .= "                    </tr>\n";

            foreach ($DatosRep as $key => $valor) {
                $extension = explode("/", $valor['formato_archivo']);

                $html .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $html .= "						<td>" . $valor['tipo_nombre'] . "</td>";
                $html .= "						<td>" . $valor['nombre_archivo'] . "</td>";
                $html .= "						<td>" . strtoupper($extension[1]) . "</td>";
                $html .= "						<td>" . $valor['num_orden_req'] . "</td>";
                $html .= "						<td>" . $valor['num_orden_sum'] . "</td>";
                $html .= "						<td>" . $valor['num_formula'] . "</td>";
				$html .= "						<td>" . $valor['tipo_paciente_id'] . "</td>";
                $html .= "						<td>" . $valor['paciente_id'] . "</td>";
                $html .= "						<td>" . $valor['num_glosa'] . "</td>";
                $html .= "						<td>" . $valor['num_factura_glosa'] . "</td>";
                $html .= "						<td>" . $valor['numero_corte'] . "</td>";
                $html .= "						<td>" . $valor['fecha_corte_ini'] . "</td>";
                $html .= "						<td>" . $valor['fecha_corte_fin'] . "</td>";
                $html .= "						<td>" . $valor['nombre_informe'] . "</td>";
                $html .= "						<td>" . $valor['fecha_ini_inf'] . "</td>";
                $html .= "						<td>" . $valor['fecha_fin_inf'] . "</td>";
                $html .= "						<td>" . $valor['num_factura'] . "</td>";
                $html .= "						<td>" . $valor['fecha_factura'] . "</td>";
                $html .= "						<td>" . $valor['tipo_factura'] . "</td>";
                $html .= "						<td>" . $valor['tipo_tutela'] . "</td>";
                $html .= "						<td><a title='AUTORIZA: " . $valor['autoriza_tutela'] . "'>" . $valor['radicado'] . "</a></td>";
			    //CTC
				$html .= "						<td>" . $valor['medico_formula'] . "</td>";
				$html .= "						<td>" . $valor['medico_autoriza'] . "</td>";
				$html .= "						<td>" . $valor['tipo_tiempo_duracion'] . "</td>";
				$html .= "						<td>" . $valor['tduracion_tutela'] . "</td>";

				//INFORME
				$html .= "						<td>" . $valor['desc_tipo_producto'] . "</td>";
				$html .= "						<td>" . $valor['corte_entregado_por'] . "</td>";
				$html .= "						<td>" . $valor['corte_auditado_por'] . "</td>";
				$html .= "						<td>" . $valor['valor_corte'] . "</td>";
				$html .= "						<td>" . $valor['valor_ctc'] . "</td>";
				$html .= "						<td>" . $valor['selectivo'] . "</td>";
				$html .= "						<td>" . $valor['tipo_selectivo'] . "</td>";
				$html .= "						<td>" . $valor['corte_cant_formulas'] . "</td>";
				$html .= "						<td>" . $valor['fecha_entrega'] . "</td>";
				$html .= "						<td>" . $valor['observacion'] . "</td>";
				
                $html .= "						<td>";
                if (!empty($Dwnld)) {
                    $html .= "					<center><a class=\"label_error\" href=\"" . $ruta_archivo . $valor['nombre_archivo'] . "\"  title=\"DESCARGAR ARCHIVO\"><img src=\"" . GetThemePath() . "/images/guarda.png\" border='0'></a></center>\n";
                } else {
                    $html .= "					<center><a class=\"label_error\" href=\"#\"  title=\"PERMISO NO ASIGNADO\"><img src=\"" . GetThemePath() . "/images/pass.png\" border='0'></a></center>\n";
                }
                $html .= "						</td>";
				
                $html .= "                   </tr>\n";
            }
            $html .= "				</table>";
            $html .= "<br>";
        }

        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
        $html .= "        VOLVER\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();

        return $html;
    }

    /*     * ***************************************************************************
     * Funcion vista: Forma listar productos del documento
     * @param array $action Vector que continen los link de la aplicacion
     * @param array 
     * @return 
     * **************************************************************************** */

    function Listado_DocsRepProd($action, $todasEmpresas, $Dwnld, $ruta_archivo, $datosTipo, $DatosRepProd, $conteo, $pagina) {
        $html = ThemeAbrirTabla(' BUSCAR MEDICAMENTOS EN DOCUMENTOS ESP. DEL REPOSITORIO ');

        $ctl = AutoCarga::factory("ClaseUtil");

        $html .= "<script>\n";
        $html .= "	function acceptNum(evt)\n";
        $html .= "	{\n";
        $html .= "		var nav4 = window.Event ? true : false;\n";
        $html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
        $html .= "		return (key <= 13 ||(key >= 48 && key <= 57));\n";
        $html .= "	}\n";
        $html .= "</script>\n";

        $html .= $ctl->IsDate("-");
        $html .= $ctl->AcceptDate("/");
        $html .= $ctl->LimpiarCampos();
        $html .= $ctl->RollOverFilas();
        $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
        $html .= "<br>";
        $html .= "<form name=\"Buscador\" id=\"Buscador\" action=\"" . $action['buscador'] . "\" method=\"post\">";
        $html .= "<table  width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
        $html .= "  <tr align=\"center\" >\n";
        $html .= "      <td  class=\"formulacion_table_list\">TIPO DE DOCUMENTO</td>\n";
        $html .= "      <td  class=\"formulacion_table_list\">";
        $html .= "           <select name=\"buscador[tipo_doc]\" id=\"buscador[tipo_doc]\" class=\"select\">   ";
        $html .= "            <option value=\"-1\">--SELECCIONAR--</option>";
        foreach ($datosTipo as $k => $v) {
            $html .= "            <option value=\"" . $v['cod_tipo'] . "\">" . $v['tipo_nombre'] . "</option>";
        }
        $html .= "           </select> ";
        $html .= "     </td>\n";
        $html .= "      <td  class=\"formulacion_table_list\">FECHA CREACION DCTO</td>\n";
        $html .= "      <td  class=\"\">";
        $html .= "        <input type=\"text\" name=\"buscador[fecha]\" id=\"fecha\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" class=\"input-text\" style=\"width:100%\">";
        $html .= "      " . ReturnOpenCalendario('Buscador', 'fecha', '/') . "\n";
        $html .= "      </td>\n";
        $html .= "	</tr>";

        $html .= "	<tr>";
        $html .= "   <td colspan=\"1\" style=\"text-align:right\" class=\"formulacion_table_list\">CODIGO</td>\n";
        $html .= "   <td colspan=\"1\" style=\"text-align:left\" class=\"formulacion_table_list\">";
        $html .= "     <input type=\"text\" id=\"buscador[codigoid]\" name=\"buscador[codigoid]\"  maxlength=\"20\" class=\"input-text\" style=\"width:100%\" value=\"\"> ";
        $html .= "   </td>\n";
        $html .= "   <td colspan=\"1\" style=\"text-align:right\" class=\"formulacion_table_list\">DESCRIPCION</td>\n";
        $html .= "   <td colspan=\"1\" style=\"text-align:left\" class=\"formulacion_table_list\">";
        $html .= "     <input type=\"text\" id=\"buscador[descripcion]\" name=\"buscador[descripcion]\" class=\"input-text\" style=\"width:100%\" value=\"\"> ";
        $html .= "	</td>";
        $html .= "	</tr>";

        $html .= "	<tr>";
        $html .= "	<td  class=\"formulacion_table_list\"  colspan=\"4\" align=\"center\"><input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\"></td>\n";
        $html .= "	</tr>";
        $html .= "</table>";
        $html .= "</form>";

        if (!empty($DatosRepProd)) {
            $pgn = AutoCarga::factory("ClaseHTML");
            $html .= "		" . $pgn->ObtenerPaginado($conteo, $pagina, $action['paginador']);
            $html .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "                    <tr class=\"modulo_table_list_title\">\n";
            $html .= "                      <td align=\"center\" >\n";
            $html .= "                        <a title='EMPRESA/FARMACIA QUE SUBIO DOC.'>EMP/FARM</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\" >\n";
            $html .= "                        <a title=''>NOMBRE DOC.</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO DE IDENTIFICACION PACIENTE'>ID PACIENTE</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NOMBRE PACIENTE'>NOM. PACIENTE</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title=''>TIPO TUTELA</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO RADICADO TUTELA'>RADICADO</a>";
            $html .= "                      </td>\n";
			
			$html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='NUMERO FORMULA'>NUMERO FORMULA</a>";
            $html .= "                      </td>\n";
			
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='CODIGO DE PRODUCTO'>CODIGO</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='DESCRIPCION PRODUCTO'>DESCRIPCION</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td> REGULADO </td>\n";
          //  if(){
            $html .= "                      <td> FECHA ENTREGA </td>\n";
            $html .= "                      <td> OBSERVACION </td>\n";
            //}
            $html .= "                      <td align=\"center\">\n";
            $html .= "                        <a title='FECHA DE CARGUE AL REPOSITORIO'>FECHA</a>";
            $html .= "                      </td>\n";
            $html .= "                      <td align=\"center\" >\n";
            $html .= "                        <a title='ACCIONES: DESCARGAR'>ACC.</a>";
            $html .= "                      </td>\n";
            $html .= "                    </tr>\n";

            foreach ($DatosRepProd as $key => $valor) {


                $html .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
               // $html .= "						<td>" . $valor['empresa_doc'] . "-" . $valor['bodega_doc'] . "</td>";
			    $html .= "						<td>" . $valor['razon_social'] . "-" . $valor['bodega_doc'] . "</td>";
                $html .= "						<td>" . $valor['nombre_archivo'] . "</td>";
                $html .= "						<td>" . $valor['paciente_id'] . "</td>";
                $html .= "						<td>" . $valor['nombre_paciente'] . "</td>";
                $html .= "						<td>" . $valor['tipo_tutela'] . "</td>";
                $html .= "						<td>" . $valor['radicado'] . "</td>";
		$html .= "						<td>" . $valor['num_formula'] . "</td>";
                $html .= "						<td>" . $valor['codigo_producto'] . "</td>";
                $html .= "						<td>" . $valor['descripcion'] . "</td>";
                $html .= "						<td>" . $valor['regulado'] . "</td>";
                
                $html .= "						<td>" .$valor['fecha_entrega']. "</td>";
                $html .= "						<td>" . $valor['observacion'] . "</td>";
                
                $html .= "						<td>" . $valor['fecha_registro'] . "</td>";
                $html .= "						<td>";
                if (!empty($Dwnld)) {
                    $html .= "					<center><a class=\"label_error\" href=\"" . $ruta_archivo . $valor['nombre_archivo'] . "\"  title=\"DESCARGAR ARCHIVO\"><img src=\"" . GetThemePath() . "/images/guarda.png\" border='0'></a></center>\n";
                } else {
                    $html .= "					<center><a class=\"label_error\" href=\"#\"  title=\"PERMISO NO ASIGNADO\"><img src=\"" . GetThemePath() . "/images/pass.png\" border='0'></a></center>\n";
                }
                $html .= "						</td>";
                $html .= "                   </tr>\n";
            }
            $html .= "				</table>";
            $html .= "<br>";
        }

        $html .= "<table align=\"center\" width=\"50%\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\"  class=\"label_error\">\n";
        $html .= "        VOLVER\n";
        $html .= "      </a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();

        return $html;
    }

}

?>