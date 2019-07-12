<?php

/**
 * $Id: app_InvBodegas_userclasses_HTML.php,v 1.61 2007/05/17 19:48:37 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Inventario del Sistema
 */

/**
*Contiene los metodos visuales para realizar la administracion de los Inventario de la clinica
*/

IncludeClass("ClaseHTML");
class app_InvBodegas_userclasses_HTML extends app_InvBodegas_user
{
	/**
	*Constructor de la clase app_Inventarios_user_HTML
	*El constructor de la clase app_Inventarios_user_HTML se encarga de llamar
	*a la clase app_Inventarios_user que se encarga del tratamiento
	* de la base de datos.
	*/

  function app_InvBodegas_user_HTML()
	{
		$this->salida='';
		$this->app_InvBodegas_user();
		return true;
	}
  /**
	* Function que muestra al usuario la diferentes bodegas, la empresa y el centro de utilidad

	* al que pertenecen y en las que el usuario tiene permiso de trabajar
	* @return boolean
	*/

	function FrmLogueoBodega(){

    $Empresas=$this->LogueoBodega();
		if(sizeof($Empresas)>0){
			$url[0]='app';
			$url[1]='InvBodegas';
			$url[2]='user';
			$url[3]='LlamaFormaMenu';
			$url[4]='datos_query';
			$this->salida .= gui_theme_menu_acceso("SELECCION BODEGA INVENTARIOS",$Empresas[0],$Empresas[1],$url,ModuloGetURL('system','Menu'));
		}else{
      $mensaje = "EL USUARIO NO TIENE PERMISOS PARA ACCESAR A UN INVENTARIO.";
			$titulo = "INVENTARIO GENERAL";
			$boton = "";//REGRESAR
			$accion="";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		return true;
	}

	function Encabezado(){

    $this->salida .= "    <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr><td class=\"modulo_table_list_title\" align=\"center\"><b>EMPRESA</b></td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" align=\"center\"><b>CENTRO DE UTILIDAD</b></td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" align=\"center\"><b>BODEGA</b></td></tr>";
		$this->salida .= "      <tr><td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['BODEGAS']['NombreEmp']."</b></td>";
    $this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['BODEGAS']['NombreCU']."</b></td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$_SESSION['BODEGAS']['BodegaId']." - ".$_SESSION['BODEGAS']['NombreBodega']."</b></td></tr>";
    $this->salida .= "		</table><BR>";
		return true;
	}
/**
* Function que muestra al menu con la opciones que puede seleccionar para trabajar
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa en la que se esta trabajando
* @param string centro de utilidad en el que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string bodega en la que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
*/
	function MenuInventarios(){

		$this->salida .= ThemeAbrirTabla('MENU INVENTARIO GENERAL');
		$actionMenu=ModuloGetURL('app','InvBodegas','user','main');
		$this->salida .= "    <form name=\"forma\" action=\"$actionMenu\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$action=ModuloGetURL('app','InvBodegas','user','LlamaMenuInventarios4');
    $action2=ModuloGetURL('app','InvBodegas','user','LlamaMenuInventarios3');
		$action3=ModuloGetURL('app','InvBodegas','user','LlamaMenuInventarios5');
		$action4=ModuloGetURL('app','InvBodegas','user','LlamaMenuInventarios2');
		$action5=ModuloGetURL('app','InvBodegas','user','LlamaMenuInventarios6');
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action\" ><b>EXISTENCIAS BODEGAS</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action2\" class=\"link\"><b>DOCUMENTOS BODEGAS</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action3\" class=\"link\"><b>MOVIMIENTOS DE LA BODEGA</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action4\" class=\"link\"><b>TOMAS FISICAS</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action5\" class=\"link\"><b>REGISTRO DE TEMPERATURAS</b></a></td></tr>";
		$this->salida .= "			     </table><BR>";
    $this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"MENU\"></td></tr>";
    $this->salida .= "    </table>";
    $this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

/**
* Function que muestra al menu con la opciones que puede seleccionar para trabajar
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa en la que se esta trabajando
* @param string centro de utilidad en el que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string bodega en la que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
*/

	function MenuInventarios4(){

    $this->salida .= ThemeAbrirTabla('MENU EXISTENCIAS BODEGAS');
		$action=ModuloGetURL('app','InvBodegas','user','MenuInventarios');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
    $action=ModuloGetURL('app','InvBodegas','user','LlamaMemuExis');
		$action1=ModuloGetURL('app','InvBodegas','user','LlamaFormaSeleccionProductosInv');
    $action2=ModuloGetURL('app','InvBodegas','user','LlamaReporteProductosCercaFVmto');
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action\" ><b>CONSULTA EXISTENCIAS </b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action1\" class=\"link\"><b>INSERTAR PRODUCTO BODEGA</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action2\" class=\"link\"><b>PRODUCTOS CERCANOS A LA FECHA DE VENCIMIENTO</b></a></td></tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		<table width=\"40%\" align=\"center\" class='normal_10N'>";
		$this->salida .= "    <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "		</table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
* Function que muestra el listado de las existencias de la bodega seleccionada
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa en la que se esta trabajando
* @param string centro de utilidad en el que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string bodega en la que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
*/

	function FormaExistenciasBodegas($codigoProd,$descripcion,$grupo,$NomGrupo,$clasePr,$NomClase,$subclase,$NomSubClase){

    $Empresa=$_SESSION['BODEGAS']['Empresa'];
    $CentroUtili=$_SESSION['BODEGAS']['CentroUtili'];
    $BodegaId=$_SESSION['BODEGAS']['BodegaId'];
		$this->salida .= ThemeAbrirTabla('EXISTENCIAS EN LA BODEGA');
		$this->salida .= "<script>";
    $this->salida .= "  function ventanaubicacion(nombre, url, ancho, altura, x,frm,Cdproducto){";
    $this->salida .="     var str = 'width=380,height=200,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .="     var url2 = url+'?Empresa='+$Empresa+'&centroutilidad='+$CentroUtili+'&Bodega='+'$BodegaId'+'&CodigoPr='+Cdproducto;";
    $this->salida .="     var rems = window.open(url2, nombre, str);\n";
		$this->salida .="     if (rems != null) {\n";
		$this->salida .="       if (rems.opener == null) {\n";
		$this->salida .="	        rems.opener = self;\n";
		$this->salida .="       }\n";
		$this->salida .="     }\n";
		$this->salida .= "  }";
		$this->salida .= "  function xxx(){";
		$this->salida .= "   document.location.reload();";
		//window.location.href
    $this->salida .= "  }";
		$this->salida .="function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		unset($_SESSION['SQL']);
		unset($_SESSION['SQLA']);
		unset($_SESSION['SQLB']);
    unset($_SESSION['SQLC']);
		unset($_SESSION['SQLD']);
		$this->salida .=" f=frm;\n";
		$this->salida .=" var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .=" var ban=1;";
		$this->salida .=" var url2 = url+'?bandera='+ban;";
		$this->salida .=" var rems = window.open(url2, nombre, str);\n";
		$this->salida .=" if (rems != null) {\n";
		$this->salida .="   if (rems.opener == null) {\n";
		$this->salida .="	    rems.opener = self;\n";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .="}\n";
		$this->salida .= "</script>";
		$action=ModuloGetURL('app','InvBodegas','user','LlamaConsultaExistenciasBodegas');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "         <table class=\"modulo_table_list\" border=\"0\" width=\"95%\" align=\"center\" >";
		$this->salida .= "         <tr><td colspan=\"2\" class=\"modulo_table_list_title\">FILTROS DE BUSQUEDA</td></tr>";
    $this->salida .= "         <tr><td class=\"modulo_list_claro\" width=\"60%\">";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "	  	   <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">GRUPO:</td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomGrupo\" value=\"$NomGrupo\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "         <input type=\"hidden\" name=\"grupo\" value=\"$grupo\" class=\"input-text\">";
		$this->salida .= "	  	   </tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">CLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomClase\"  value=\"$NomClase\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "         <input type=\"hidden\" name=\"clasePr\" value=\"$clasePr\" class=\"input-text\" >";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">SUBCLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomSubClase\"  value=\"$NomSubClase\" size=\"30\" class=\"input-text\" readonly>";
		$this->salida .= "         <input type=\"hidden\" name=\"subclase\" value=\"$subclase\" class=\"input-text\" >";
		$ruta='app_modules/InvBodegas/ventanaClasificacion.php';
		$this->salida .= "         <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\"></td>";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     </table><BR><BR>";
    $this->salida .= "		     </td>";
    $this->salida .= "		     <td valign=\"top\" class=\"modulo_list_claro\" width=\"40%\"> ";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">CODIGO</td><td><input type=\"text\" class=\"input-text\" name=\"codigoProd\" value=\"$codigoProd\"></td></tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">DESCRIPCION</td><td><input type=\"text\" class=\"input-text\" name=\"descripcion\" value=\"$descripcion\"></td></tr>";
		$this->salida .= "		     </table><BR>";
		$this->salida .= "         </td></tr>";
		$this->salida .= "         <tr><td colspan=\"2\" align=\"center\" class=\"modulo_list_claro\"><input type=\"submit\" class=\"input-submit\" value=\"FILTRAR\" name=\"filtrar\"></td></tr>";
    $this->salida .= "		     </table><BR>";
		$this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "       <tr><td>&nbsp&nbsp;</td></tr>";
		$this->salida .= "       </td></tr>";
		$this->salida .= "			 </table>";
		$ExistenciasBodegas=$this->ConsultaExistenciasBodegas($grupo,$clasePr,$subclase,$codigoProd,$descripcion);
		if($ExistenciasBodegas){
		$this->salida .= "	  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "    <tr>";
		$this->salida .= "			<td class=\"modulo_table_list_title\">CODIGO</td>\n";
		$this->salida .= "			<td width=\"50%\" class=\"modulo_table_list_title\">DESCRIPCION</td>\n";
		$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIA</td>\n";
		$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIA MIN</td>\n";
		$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIA MAX</td>\n";
		$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIAS</td>\n";
		$this->salida .= "			<td width=\"40%\" class=\"modulo_table_list_title\">UBICACION ACTUAL</td>\n";
		$this->salida .= "			<td class=\"modulo_table_list_title\">UBICACION</td>\n";
		$this->salida .= "			<td class=\"modulo_table_list_title\">LOTES</td>\n";
		$this->salida .= "			<td class=\"modulo_table_list_title\">ESTADO</td>\n";
		$this->salida .= "		 </tr>\n";
		$y=0;
		for($i=0;$i<sizeof($ExistenciasBodegas);$i++){
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "	 <tr class=\"$estilo\">\n";
			$CodigoPr=$ExistenciasBodegas[$i]['codigo_producto'];
			$this->salida .= "	 <td>".$ExistenciasBodegas[$i]['codigo_producto']."</td>";
			$DescripProd=$ExistenciasBodegas[$i]['desprod'];
			$this->salida .= "	 <td>$DescripProd</td>";
			$this->salida .= "	 <td>".$ExistenciasBodegas[$i]['existencia']."</td>";
			$this->salida .= "	 <td>".$ExistenciasBodegas[$i]['existencia_minima']."</td>";
			$this->salida .= "	 <td>".$ExistenciasBodegas[$i]['existencia_maxima']."</td>";
			$actionExis=ModuloGetURL('app','InvBodegas','user','LlamaModificacionExistenciasMinMax',array("codProducto"=>$ExistenciasBodegas[$i]['codigo_producto'],"descripcion"=>$DescripProd,"conteo"=>$this->conteo,"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso'],"codigoProd"=>$codigoProd,"descripcionProd"=>$descripcion,"grupo"=>$grupo,"NomGrupo"=>$NomGrupo,"clasePr"=>$clasePr,"NomClase"=>$NomClase,"subclase"=>$subclase,"NomSubClase"=>$NomSubClase));
			$this->salida .= "	 <td align=\"center\"><a href=\"$actionExis\"><img border=\"0\" src=\"".GetThemePath()."/images/pmodificar.png\"></a></td>";
			$this->salida .= "	 <td>".$ExistenciasBodegas[$i]['ubicacion']."</td>";
			//$this->salida .= "	 <td><input type=\"text\" class=\"input-text\" value=\"".$ExistenciasBodegas[$i]['ubicacion_id']."\" READONLY></td>";
			$ruta='app_modules/InvBodegas/ventanaClasifyUbicacion.php';
			$this->salida .= "			<td align=\"center\"><a href=\"javascript:ventanaubicacion('CLASIFICACION','$ruta',450,200,0,this.form,'$CodigoPr');\" class=\"link\" ><img border=\"0\" src=\"".GetThemePath()."/images/ubicacion.png\"></a></td>";
      if($ExistenciasBodegas[$i]['sw_control_fecha_vencimiento']=='1'){
			$actionLotes=ModuloGetURL('app','InvBodegas','user','LlamaMostrasrLotesProducto',array("codigoProducto"=>$ExistenciasBodegas[$i]['codigo_producto'],"conteo"=>$this->conteo,"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso'],"DescripProd"=>$DescripProd,"codigoProd"=>$codigoProd,"descripcionProd"=>$descripcion,"grupo"=>$grupo,"NomGrupo"=>$NomGrupo,"clasePr"=>$clasePr,"NomClase"=>$NomClase,"subclase"=>$subclase,"NomSubClase"=>$NomSubClase));
      $this->salida .= "     <td align=\"center\"><a href=\"$actionLotes\"><img border=\"0\" src=\"".GetThemePath()."/images/pparamed.png\"></a></td>";
			}else{
      $this->salida .= "     <td align=\"center\">&nbsp;</td>";
			}
			if($ExistenciasBodegas[$i]['estado']==1){
			  $action1=ModuloGetURL('app','InvBodegas','user','CambioEstadoProductoInv',array("Empresa"=>$Empresa,"NombreEmp"=>$NombreEmp,"CentroUtili"=>$CentroUtili,"NombreCU"=>$NombreCU,"BodegaId"=>$BodegaId,"NombreBodega"=>$NombreBodega,"codProd"=>$ExistenciasBodegas[$i]['codigo_producto'],"bandera"=>1,"conteo"=>$this->conteo,"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso']));
			  $this->salida .= "				       <td align=\"center\"><a href=\"$action1\"><img border=\"0\" src=\"".GetThemePath()."/images/checksi.png\"></a></td>";
			}else{
        $action1=ModuloGetURL('app','InvBodegas','user','CambioEstadoProductoInv',array("Empresa"=>$Empresa,"NombreEmp"=>$NombreEmp,"CentroUtili"=>$CentroUtili,"NombreCU"=>$NombreCU,"BodegaId"=>$BodegaId,"NombreBodega"=>$NombreBodega,"codProd"=>$ExistenciasBodegas[$i]['codigo_producto'],"bandera"=>0,"conteo"=>$this->conteo,"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso']));
        $this->salida .= "				       <td align=\"center\"><a href=\"$action1\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
			}
			$this->salida .= "	 </tr>\n";
			$y++;
		}
		$this->salida .= "       </table><br>";
		$bandera=1;
		$this->salida .=$this->RetornarBarra($bandera);
		}else{
      $this->salida .= "       <table border=\"0\" width=\"90%\" class=\"normal_10N\" align=\"center\">";
		  $this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS EN ESTA BODEGA</td></tr>";
      $this->salida .= "       </table>";
		}
		$this->salida .= "       <table border=\"0\" width=\"90%\" class=\"normal_10N\" align=\"center\">";
		$this->salida .= "        <tr><td align=\"center\"><BR><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "       </table>";
		$this->salida .= "       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ModificacionExistenciasMinMax($codProducto,$descripcion,$codigoProd,$descripcionProd,$grupo,$NomGrupo,$clasePr,$NomClase,$subclase,$NomSubClase){
	  $this->salida .= ThemeAbrirTabla('MODIFICACION DE LAS EXISTENCIAS EN LA BODEGA');
		$action=ModuloGetURL('app','InvBodegas','user','InsertarActualExistencias',array("conteo"=>$this->conteo,"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso'],"codigoProd"=>$_REQUEST['codigoProd'],"descripcionProd"=>$_REQUEST['descripcionProd'],"grupo"=>$_REQUEST['grupo'],"NomGrupo"=>$_REQUEST['NomGrupo'],"clasePr"=>$_REQUEST['clasePr'],"NomClase"=>$_REQUEST['NomClase'],"subclase"=>$_REQUEST['subclase'],"NomSubClase"=>$_REQUEST['NomSubClase']));
		$this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "       <input type=\"hidden\" name=\"codProducto\" value=\"$codProducto\">";
		$this->salida .= "       <input type=\"hidden\" name=\"descripcion\" value=\"$descripcion\">";
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
		$this->salida .= "       <tr><td width=\"100%\">";
		$this->salida .= "       <fieldset><legend class=\"field\">DATOS DE LAS EXISTENCIAS</legend>";
    $this->salida .= "       <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	      <tr><td></td></tr>";
		$this->salida .= "	      <tr class=\"modulo_table_title\"><td colspan=\"2\" align=\"center\">$codProducto&nbsp&nbsp&nbsp;$descripcion</td></tr>";
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td><label class=\"label\">EXISTENCIA MINIMA</td>";
		$this->salida .= "	      <td><input type=\"text\" class=\"input-text\" name=\"existencia_min\" value=\"".$_REQUEST['existencia_min']."\"></td>";
		$this->salida .= "	      </tr>";
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td><label class=\"label\">EXISTENCIA MAXIMA</td>";
		$this->salida .= "	      <td><input type=\"text\" class=\"input-text\" name=\"existencia_max\" value=\"".$_REQUEST['existencia_max']."\"></td>";
		$this->salida .= "	      </tr>";
		$this->salida .= "	      <tr><td></td></tr>";
		$this->salida .= "	      <tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"VOLVER\" name=\"regresar\" class=\"input-submit\" >";
		$this->salida .= "	      <input class=\"input-submit\" type=\"submit\" value=\"ACTUALIZAR\" name=\"actualizar\"></td></tr>";
    $this->salida .= "			  </table>";
		$this->salida .= "		    </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
    $this->salida .= "       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

 function CalcularNumeroPasos($conteo){
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	function CalcularBarra($paso){
		$barra=floor($paso/10)*10;
		if(($paso%10)==0){
			$barra=$barra-10;
		}
		return $barra;
	}

	function CalcularOffset($paso){
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	 function RetornarBarra($bandera,$TomaFisica){

		if($this->limit>=$this->conteo){
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
    if($bandera==1){
      $accion=ModuloGetURL('app','InvBodegas','user','LlamaMemuExis',array('conteo'=>$this->conteo,"Of"=>$_REQUEST['Of']));
		}elseif($bandera==2){
      $accion=ModuloGetURL('app','InvBodegas','user','LlamaFormaSeleccionProductosInvUno',array('conteo'=>$this->conteo,"Of"=>$_REQUEST['Of'],"codigoProd"=>$_REQUEST['codigoProd'],"descripcion"=>$_REQUEST['descripcion'],"grupo"=>$_REQUEST['grupo'],"clasePr"=>$_REQUEST['clasePr'],"subclase"=>$_REQUEST['subclase'],"NomGrupo"=>$_REQUEST['NomGrupo'],"NomClase"=>$_REQUEST['NomClase'],"NomSubClase"=>$_REQUEST['NomSubClase']));
		}elseif($bandera==3){
      $accion=ModuloGetURL('app','InvBodegas','user','selecBusquedaDocumento1',array('conteo'=>$this->conteo,"Of"=>$_REQUEST['Of'],"BodegaId"=>$_REQUEST['BodegaId'],"NombreBodega"=>$_REQUEST['NombreBodega'],"Aceptar"=>$_REQUEST['Aceptar'],"TipoBusquedaInv"=>$_REQUEST['TipoBusquedaInv'],"BuscarTotal"=>$_REQUEST['BuscarTotal'],"Buscar"=>$_REQUEST['Buscar'],"NumBusqueda"=>$_REQUEST['NumBusqueda'],"Salir"=>$_REQUEST['Salir'],"numSolicitud"=>$_REQUEST['numSolicitud'],"numDevolucion"=>$_REQUEST['numDevolucion'],
			"conceptoInv"=>$_REQUEST['conceptoInv'],"FechaInicial"=>$_REQUEST['FechaInicial'],"FechaFinal"=>$_REQUEST['FechaFinal'],"numDocumento"=>$_REQUEST['numDocumento']));
		}elseif($bandera==4){
      $accion=ModuloGetURL('app','InvBodegas','user','InsertarTomaFisica',array('conteo'=>$this->conteo,"Of"=>$_REQUEST['Of'],"TomaFisica"=>$TomaFisica));
		}elseif($bandera==5){
		  $accion=ModuloGetURL('app','InvBodegas','user','InsertarCantidadesFisica',array('conteo'=>$this->conteo,"Of"=>$_REQUEST['Of'],"TomaFisica"=>$_REQUEST['TomaFisica'],"Fecha"=>$_REQUEST['Fecha'],"bandera"=>$_REQUEST['bandera']));
		}elseif($bandera==6){
      $accion=ModuloGetURL('app','InvBodegas','user','BuscarPtosParaReposicionExisMenores',array("DatosBodega"=>$_REQUEST['DatosBodega'],'conteo'=>$this->conteo));
		}elseif($bandera==7){
      $accion=ModuloGetURL('app','InvBodegas','user','InsertarTomaFisica',array('conteo'=>$this->conteo,"Of"=>$_REQUEST['Of'],"Aleatoria"=>1));
		}elseif($bandera==8){
      $accion=ModuloGetURL('app','InvBodegas','user','MtoFechasVencimiento',array('conteo'=>$this->conteo,"Of"=>$_REQUEST['Of']));
		}elseif($bandera==9){
      $accion=ModuloGetURL('app','InvBodegas','user','LlamaBuscadorProductoExistencias',array('conteo'=>$this->conteo,"Of"=>$_REQUEST['Of'],"Documento"=>$_REQUEST['Documento'],"conceptoInv"=>$_REQUEST['conceptoInv'],"fechaDocumento"=>$_REQUEST['fechaDocumento'],"codigoBus"=>$_REQUEST['codigoBus'],"descripcionBus"=>$_REQUEST['descripcionBus'],"origen"=>$_REQUEST['origen'],"CentroUtilityDest"=>$_REQUEST['CentroUtilityDest'],"BodegaDest"=>$_REQUEST['BodegaDest'],
      "tipoIdProveedor"=>$_REQUEST['tipoIdProveedor'],"ProveedorId"=>$_REQUEST['ProveedorId'],"proveedor"=>$_REQUEST['proveedor'],"numFactura"=>$_REQUEST['numFactura'],"iva"=>$_REQUEST['iva'],"valorFletes"=>$_REQUEST['valorFletes'],"otrosGastos"=>$_REQUEST['otrosGastos'],"observaciones"=>$_REQUEST['observaciones']));
		}elseif($bandera==10){
      $accion=ModuloGetURL('app','InvBodegas','user','LlamaBuscadorProveedores',array('conteo'=>$this->conteo,"Of"=>$_REQUEST['Of'],
			"Documento"=>$_REQUEST['Documento'],"fechaDocumento"=>$_REQUEST['fechaDocumento'],"conceptoInv"=>$_REQUEST['conceptoInv'],
			"nombreProducto"=>$_REQUEST['nombreProducto'],"codigo"=>$_REQUEST['codigo'],"unidadProducto"=>$_REQUEST['unidadProducto'],"ExisProducto"=>$_REQUEST['ExisProducto'],"costoProducto"=>$_REQUEST['costoProducto'],"precioProducto"=>$_REQUEST['precioProducto'],
			"cantSolicitada"=>$_REQUEST['cantSolicitada'],"costoUnit"=>$_REQUEST['costoUnit'],"tipoIdProveedor"=>$_REQUEST['tipoIdProveedor'],"ProveedorId"=>$_REQUEST['ProveedorId'],
			"proveedor"=>$_REQUEST['proveedor'],"numFactura"=>$_REQUEST['numFactura'],"iva"=>$_REQUEST['iva'],"valorFletes"=>$_REQUEST['valorFletes'],"otrosGastos"=>$_REQUEST['otrosGastos'],"observaciones"=>$_REQUEST['observaciones'],"TipoDocumentoBus"=>$_REQUEST['TipoDocumentoBus'],"DocumentoBus"=>$_REQUEST['DocumentoBus'],"descripcionBus"=>$_REQUEST['descripcionBus']));
		}
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}else{
     // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
    }
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<=0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}else{
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
		}
		$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan='15' align='center'>Página $paso de $numpasos</td><tr></table>";
	}
/**
* Funcion que se encarga de visualizar un error en un campo
* @return string
*/

	function SetStyle($campo)
	{
		if ($this->frmError[$campo] || $campo=="MensajeError"){
		  if ($campo=="MensajeError"){
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}

	function FormaSeleccionProductosInv($codigoProd,$descripcion,$grupo,$clasePr,$subclase,$NomGrupo,$NomClase,$NomSubClase){

		$this->salida  = ThemeAbrirTabla('PRODUCTOS EN INVENTARIO QUE NO EXISTEN EN LA BODEGA');
		$this->salida .= "<SCRIPT>";
		$this->salida .="function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		unset($_SESSION['SQL']);
		unset($_SESSION['SQLA']);
		unset($_SESSION['SQLB']);
    unset($_SESSION['SQLC']);
		unset($_SESSION['SQLD']);
		$this->salida .=" f=frm;\n";
		$this->salida .=" var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .=" var ban=1;";
		$this->salida .=" var url2 = url+'?bandera='+ban;";
		$this->salida .=" var rems = window.open(url2, nombre, str);\n";
		$this->salida .=" if (rems != null) {\n";
		$this->salida .="   if (rems.opener == null) {\n";
		$this->salida .="	    rems.opener = self;\n";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .="}\n";
		$this->salida .= "function chequeoTotal(frm,x){";
    $this->salida .= "  if(x==true){";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && frm.elements[i].value=='1'){";
    $this->salida .= "        frm.elements[i].checked=true";
		$this->salida .= "      }";
		$this->salida .= "    }";
    $this->salida .= "  }else{";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && frm.elements[i].value=='1'){";
    $this->salida .= "        frm.elements[i].checked=false";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }";
    $this->salida .= "}";
		$this->salida .= "</SCRIPT>";
		$this->salida .= "			 <br>";
		$action=ModuloGetURL('app','InvBodegas','user','DestinoFormaSeleccionInv',array("conteo"=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of']));
		$this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "         <table class=\"modulo_table_list\" border=\"0\" width=\"85%\" align=\"center\" >";
		$this->salida .= "         <tr><td colspan=\"2\" class=\"modulo_table_list_title\">FILTROS DE BUSQUEDA</td></tr>";
    $this->salida .= "         <tr><td class=\"modulo_list_claro\" width=\"60%\">";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "	  	   <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">GRUPO:</td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomGrupo\" value=\"$NomGrupo\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "         <input type=\"hidden\" name=\"grupo\" value=\"$grupo\" class=\"input-text\">";
		$this->salida .= "	  	   </tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">CLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomClase\"  value=\"$NomClase\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "         <input type=\"hidden\" name=\"clasePr\" value=\"$clasePr\" class=\"input-text\" >";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">SUBCLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomSubClase\"  value=\"$NomSubClase\" size=\"30\" class=\"input-text\" readonly>";
		$this->salida .= "         <input type=\"hidden\" name=\"subclase\" value=\"$subclase\" class=\"input-text\" >";
		$ruta='app_modules/InvBodegas/ventanaClasificacion.php';
		$this->salida .= "         <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\"></td>";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     </table><BR><BR>";
    $this->salida .= "		     </td>";
    $this->salida .= "		     <td valign=\"top\" class=\"modulo_list_claro\" width=\"40%\"> ";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">CODIGO</td><td><input type=\"text\" class=\"input-text\" name=\"codigoProd\" value=\"$codigoProd\"></td></tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">DESCRIPCION</td><td><input type=\"text\" class=\"input-text\" name=\"descripcion\" value=\"$descripcion\"></td></tr>";
		$this->salida .= "		     </table><BR>";
		$this->salida .= "         </td></tr>";
		$this->salida .= "         <tr><td colspan=\"2\" align=\"center\" class=\"modulo_list_claro\"><input type=\"submit\" class=\"input-submit\" value=\"FILTRAR\" name=\"filtrar\"></td></tr>";
    $this->salida .= "		     </table><BR>";
		$this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "       <tr><td>&nbsp&nbsp;</td></tr>";
		$this->salida .= "       </td></tr>";
		$this->salida .= "			 </table>";
		$DatosProducto=$this->DatosProductoInventario($codigoProd,$descripcion,$grupo,$clasePr,$subclase);
		if($DatosProducto){
    $this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
    $this->salida .= "			    <td>COD PRODUCTO</td>";
		$this->salida .= "          <td>DESCRIPCION</td>";
    $this->salida .= "          <td>CONTROL FECHA VENCIMIENTO</td>";
		$this->salida .= "          <td><input type=\"checkbox\" name=\"checkTotal\" onclick=\"chequeoTotal(this.form,this.checked)\"></td>";
		$this->salida .= "        </tr>";
		if(empty($_REQUEST['paso']))
		{
		  $_REQUEST['paso']=1;
		}
		for($i=0;$i<sizeof($DatosProducto);$i++){
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "	 <tr class=\"$estilo\">\n";
			$Pr=$DatosProducto[$i]['codigo_producto'];
      $this->salida .= "				<td>".$DatosProducto[$i]['codigo_producto']."</td>";
			$this->salida .= "				<td width=\"100%\">".$DatosProducto[$i]['descripcion']."</td>";

			$checked='';
			$chequeado1='';
      if($DatosProducto[$i]['sw_control_fecha_vencimiento']=='1'){
        $checked='checked';
			}elseif($_SESSION['CONTROLFECHAS'][$DatosProducto[$i]['codigo_producto']]==1)
			{
 				$chequeado1='checked';
			}
			$this->salida .= "        <td align=\"center\"><input type=\"checkbox\" name=\"controlFecha[$Pr]\"$checked $chequeado1></td>";
			$chequeado='';
			if($_SESSION['Existencias'][$DatosProducto[$i]['codigo_producto']]==1)
			{
 				$chequeado='checked';
			}
			$this->salida .= "        <td width=\"5%\" align=\"center\"><input type=\"checkbox\" name=\"Seleccion[$Pr]\" value=\"1\" $chequeado></td>";
      if($chequeado){
			  $this->salida .= "        <input type=\"hidden\" name=\"SeleccionActual[$Pr]\" value=\"1\"></td>";
			}
			$this->salida .= "      </tr>";
			$y++;
		}
    $this->salida .= "      </table><BR>";
		$bandera=2;
		$this->salida .= "         <table class=\"normal_10N\" border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "         <tr>";
		$this->salida .= "         <td  width=\"100%\" align=\"right\"><BR><input class=\"input-submit\" type=\"submit\" name=\"InsertarTotal\" value=\"INSERTAR TODOS LOS PRODUCTOS\">";
		$this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"INSERTAR\">";
		$this->salida .= "         </td></tr>";
		$this->salida .= "			   </table>";
		$this->salida .=$this->RetornarBarra($bandera);
		$this->salida .= "			   <BR>";
    $this->salida .= "			   <BR>";
		}else{
      $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"85%\" align=\"center\">";
		  $this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON PRODUCTOS NUEVOS PARA INSERTAR A LA BODEGA</td></tr>";
      $this->salida .= "       </table>";
		}
		$this->salida .= "        <table class=\"normal_10N\" border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "         <tr><td align=\"center\"><BR><input class=\"input-submit\" type=\"submit\" name=\"SalirSinGuardar\" value=\"SALIR SIN GUARDAR\">";
		$this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"GUARDAR\"></td></tr>";
    $this->salida .= "        </table>";
		$this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
* La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
* @return boolean
* @param string mensaje a retornar para el usuario
* @param string titulo de la ventana a mostrar
* @param string lugar a donde debe retornar la ventana
* @param boolean tipo boton de la ventana
*/

	function FormaMensaje($mensaje,$titulo,$accion,$boton,$origen,$imprimir){
		$this->salida .= ThemeAbrirTabla($titulo);
		$this->salida .= "			      <table class=\"normal_10\" width=\"60%\" align=\"center\">";
		$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "				       <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
		if($boton){
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"$boton\"></td></tr>";
		}
	  else{
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"Aceptar\">";
			if($origen==1){
        $this->salida .= "				      <input class=\"input-submit\" type=\"submit\" name=\"CancelarProceso\" value=\"Cancelar\">";
			}
			$this->salida .= "				       </td></tr>";
	  }
		if(is_array($imprimir)){
			$this->salida .= "				       <tr><td colspan=\"2\" class=\"label\" align=\"center\">";
			$rep= new GetReports();
			$mostrar=$rep->GetJavaReport('app','InvBodegas','ConsultaDocumentosBodega_html',array("numeracion"=>$imprimir[1],"prefijo"=>$documentos[$i]['prefijo'],"bodegas_doc_id"=>$imprimir[0]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
			$nombre_funcion=$rep->GetJavaFunction();
			$this->salida .=$mostrar;	
			$this->salida .= "				       <BR><a href=\"javascript:$nombre_funcion\" class=\"link\"><img title=\"Imprimir\" border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\">&nbsp;&nbsp;&nbsp;IMPRIMIR DOCUMENTO</a>";			
			$this->salida .= "				       </td></tr>";
		}
		$this->salida .= "			     </form>";
		$this->salida .= "			     </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ExistenciasMenores($DatosBodega){
		$this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
    $cadena=explode('/',$DatosBodega);
		$centroUtilidad=$cadena[0];
    $Bodega=$cadena[1];
		$TipoReposicion=$cadena[2];
    $this->salida .= ThemeAbrirTabla('SELECCION DE PRODUCTOS A TRANSFERIR');
		$action=ModuloGetURL('app','InvBodegas','user','BuscarPtosParaReposicionExisMenores',array("DatosBodega"=>$DatosBodega,"conteo"=>$this->conteo,"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso']));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= "<SCRIPT>";
		$this->salida .= "function chequeoTotal(frm,x){";
    $this->salida .= "  if(x==true){";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && frm.elements[i].name=='seleccion[]'){";
    $this->salida .= "        frm.elements[i].checked=true";
		$this->salida .= "      }";
		$this->salida .= "    }";
    $this->salida .= "  }else{";
    $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "      if(frm.elements[i].type=='checkbox' && frm.elements[i].name=='seleccion[]'){";
    $this->salida .= "        frm.elements[i].checked=false";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }";
    $this->salida .= "}";
		$this->salida .= "</SCRIPT>";
		$this->Encabezado();
		$this->salida .= "         <table class=\"normal_10\" border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
		$this->salida .= "         <tr><td width=\"100%\">";
		$this->salida .= "         <fieldset><legend class=\"field\">BODEGA DESTINO DE LA TRANSFERENCIA</legend>";
    $this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	        <tr><td></td></tr>";
		$this->salida .= "	        <tr class=\"modulo_list_claro\">";
		$this->salida .= "		      <td width=\"25%\" class=\"".$this->SetStyle("numBodega")."\">CENTRO UTILIDAD</td>";
		$NombreCentro=$this->NombreCentroUtilidad($centroUtilidad);
		$this->salida .= "		      <td>".$NombreCentro['descripcion']."</td>";
		$this->salida .= "		      </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"5%\"><label class=\"label\">BODEGA</td>";
		$NombreBodega=$this->NombreBodegasInventario($Bodega,$centroUtilidad);
		$this->salida .= "	         <td>$Bodega   ".$NombreBodega['descripcion']."</td>";
		$this->salida .= "		      </tr>";
    $this->salida .= "	        <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"5%\"><label class=\"label\">ULTIMA FECHA DE REPOSICION</td>";
		$UltimaFechaReposicion=$this->UltimaFechaReposicionBodega($Bodega,$centroUtilidad);
		if($UltimaFechaReposicion){
    	(list($ano,$mes,$dia)=explode('-',$UltimaFechaReposicion['fecha']));		
			$this->salida .= "	         <td>".strftime('%d %b de %Y',mktime(0,0,0,$mes,$dia,$ano))."</td>";
		}else{
			$this->salida .= "	         <td>&nbsp;</td>";
		}
		$this->salida .= "		      </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_claro\">";
		if($TipoReposicion=='MIN'){
		$this->salida .= "	         <td align=\"center\" colspan=\"2\"><label class=\"label\">REPOSICION SOBRE EXISTENCIAS MINIMAS</td>";
		}else{
    $this->salida .= "	         <td align=\"center\" colspan=\"2\"><label class=\"label\">REPOSICION SOBRE EXISTENCIAS MAXIMAS</td>";
		}
		$this->salida .= "		      </tr>";
		$this->salida .= "	        <tr><td></td></tr>";
    $this->salida .= "			    </table>";
		$this->salida .= "		      </fieldset></td><BR>";
		$this->salida .= "         </table><BR>";
		$TotalProductosMin=$this->ConsultaMenorExistencia($Bodega,$centroUtilidad);
		if($TotalProductosMin){
			$this->salida .= "	  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "    <tr>";
			$this->salida .= "			<td colspan=\"2\" class=\"modulo_table_list_title\">&nbsp;</td>\n";
			$NombreBodega1=$this->NombreBodegasInventario($_SESSION['BODEGAS']['BodegaId'],$_SESSION['BODEGAS']['CentroUtili']);
			$this->salida .= "			<td colspan=\"2\" class=\"modulo_table_list_title\">BODEGA ORIGEN<br>".$_SESSION['BODEGAS']['BodegaId']." ".$NombreBodega1['descripcion']."</td>\n";
			$this->salida .= "			<td colspan=\"5\" class=\"modulo_table_list_title\">BODEGA DESTINO<br>$Bodega   ".$NombreBodega['descripcion']."</td>\n";
      $this->salida .= "    </tr>";
			$this->salida .= "    <tr>";
			$this->salida .= "			<td class=\"modulo_table_list_title\">CODIGO</td>\n";
			$this->salida .= "			<td width=\"100%\" class=\"modulo_table_list_title\">DESCRIPCION</td>\n";
      $this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIA</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIAS MINIMAS</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIAS</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIAS MINIMAS</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIAS MAXIMAS</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIAS A TRANSFERIR</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\"><input type=\"checkbox\" name=\"checkTotal\" onclick=\"chequeoTotal(this.form,this.checked)\"></td>\n";
			$this->salida .= "		 </tr>\n";
			$y=0;
			$rep= new GetReports();
			for($i=0;$i<sizeof($TotalProductosMin);$i++){
				$Pr=$TotalProductosMin[$i]['codigo_producto'];
				if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "	 <tr class=\"$estilo\">\n";
				$this->salida .= "	 <td>".$TotalProductosMin[$i]['codigo_producto']."</td>";
				$DescripProd=substr($TotalProductosMin[$i]['descripcion'],0,20);
				$this->salida .= "	 <td width=\"100%\">$DescripProd</td>";
				$this->salida .= "	 <td>".$TotalProductosMin[$i]['exisobodega']."</td>";
				$this->salida .= "	 <td>".$TotalProductosMin[$i]['exismin']."</td>";
				$this->salida .= "	 <td>".$TotalProductosMin[$i]['existencia']."</td>";
				$this->salida .= "	 <td>".$TotalProductosMin[$i]['existencia_minima']."</td>";
				$this->salida .= "	 <td>".$TotalProductosMin[$i]['existencia_maxima']."</td>";
				if(empty($_REQUEST['paso'])){$_REQUEST['paso']=1;}
				$checked='';
				if($_SESSION['EXISTENCIAS']['TRANSFER'][$_REQUEST['paso']][$Pr]==1)
				{
					$checked='checked';
				}
				if($TipoReposicion=='MIN'){
				  $cantidad=$TotalProductosMin[$i]['existencia_minima']-$TotalProductosMin[$i]['existencia'];
					if($cantidad > 0 && $cantidad<=$TotalProductosMin[$i]['exisobodega']){
						$defecto=$cantidad;
					}elseif($cantidad > 0 && $TotalProductosMin[$i]['exisobodega']>0){
						$defecto=$TotalProductosMin[$i]['exisobodega'];
					}else{
						$defecto=0;
					}
				}else{
          $cantidad=$TotalProductosMin[$i]['existencia_maxima']-$TotalProductosMin[$i]['existencia'];
					if($cantidad > 0 && $cantidad<=$TotalProductosMin[$i]['exisobodega']){
						$defecto=$cantidad;
					}elseif($cantidad > 0 && $TotalProductosMin[$i]['exisobodega']>0){
						$defecto=$TotalProductosMin[$i]['exisobodega'];
					}else{
						$defecto=0;
					}
				}
				if($_SESSION['EXISTENCIAS']['CANTIDAD'][$_REQUEST['paso']][$Pr])
				{
					$defecto=$_SESSION['EXISTENCIAS']['CANTIDAD'][$_REQUEST['paso']][$Pr];
				}
				$this->salida .= "	 <td><input size=\"11\" type=\"text\" name=\"canDespachar[$Pr]\" value=\"$defecto\"></td>";
				$this->salida .= "	 <td><input type=\"checkbox\" name=\"seleccion[]\" value=\"$Pr\" $checked></td>";
				$this->salida .= "	 </tr>\n";
				$y++;
			}
			$this->salida .= "       </table><br>";
			$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\" >";
			$this->salida .= "        <tr><td align=\"right\">";
			if($mes && $dia && $ano){
				$FechaUltimaReposicion=strftime('%d %b de %Y',mktime(0,0,0,$mes,$dia,$ano));
			}else{
				$FechaUltimaReposicion='';
			}
			$mostrar=$rep->GetJavaReport('app','InvBodegas','RepocisionesBodega_html',array("DatosBodega"=>$DatosBodega,"NombreCentro"=>$NombreCentro['descripcion'],"NombreBodega"=>$NombreBodega['descripcion'],"UltimaTransferencia"=>$FechaUltimaReposicion),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
			$nombre_funcion=$rep->GetJavaFunction();
			$this->salida .=$mostrar;
			$this->salida .= "	      <BR><a class=\"Menu\" href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a>";
			//FIN IMPRESION
			$this->salida .= "	      &nbsp&nbsp&nbsp;<input type=\"submit\" class=\"input-text\" value=\"INSERTAR\" name=\"insertar\">";
			$this->salida .= "	      </td></tr>";
			$this->salida .= "        <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR DOCUMENTO\"></td></tr>";
			$this->salida .= "       </table>";
			//$bandera=6;
		  //$this->salida .=$this->RetornarBarra($bandera);
			$Paginador = new ClaseHTML();		
			$this->actionPaginador=ModuloGetURL('app','InvBodegas','user','BuscarPtosParaReposicionExisMenores',array("DatosBodega"=>$DatosBodega));
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
		}else{
      $this->salida .= "       <table class=\"normal_10N\"  border=\"0\" width=\"90%\" align=\"center\" >";
			if($TipoReposicion=='MIN'){
		  $this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON PRODUCTOS CON EXISTENCIAS MINIMAS</td></tr>";
			}else{
			$this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON PRODUCTOS CON EXISTENCIAS MAXIMAS</td></tr>";
			}
      $this->salida .= "       </table>";
		}
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\" >";
		$this->salida .= "        <tr><td align=\"center\"><BR><input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "       </table>";
		$this->salida .= "    </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

/**
* Function que muestra al menu con la opciones que puede seleccionar para trabajar
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa en la que se esta trabajando
* @param string centro de utilidad en el que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string bodega en la que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
*/

	function MenuInventarios3(){

    $this->salida .= ThemeAbrirTabla('MENU DOCUMENTOS BODEGAS');
		$action=ModuloGetURL('app','InvBodegas','user','MenuInventarios');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
		$action=ModuloGetURL('app','InvBodegas','user','LlamaCreacionTiposDocBodegas');
		$action1=ModuloGetURL('app','InvBodegas','user','LlamaFormaCrearDocumentosBodega');
		$action2=ModuloGetURL('app','InvBodegas','user','LlamaConsultaDocumentos');
		$action3=ModuloGetURL('app','InvBodegas','user','LlamaMtoFechasVencimiento');
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action\" class=\"link\"><b>CREACION TIPOS DE DOCUMENTOS BODEGA</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action1\" class=\"link\"><b>CREACION DOCUMENTO</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action2\" class=\"link\"><b>CONSULTA DOCUMENTOS</b></a></td></tr>";
		//$this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action3\" class=\"link\"><b>MANTENIMIENTO FECHAS VENCIMIENTO</b></a></td></tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		<table width=\"40%\" align=\"center\" class='normal_10N'>";
		$this->salida .= "    <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "		</table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
* La funcion qu visualiza los datos requeridos para crear la cabecera de un documento de la bodega
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa en la que se esta trabajando
* @param string centro de utilidad en el que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string bodega en la que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
* @param string concepto del inventario por el cual se crea el documento
* @param string fecha de creacion del documento
* @param string observaciones acerca de la creacion de un documento
*/

	function FormaCrearDocumentosBodega($conceptoInv,$FechaDocumento,$observacion)
	{
    $this->salida .= ThemeAbrirTabla('CREACION DE DOCUMENTO BODEGA');
		$action=ModuloGetURL('app','InvBodegas','user','InsertarDocumentoBodega');
		$this->salida .= "       <form name=\"formaInventarios\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "       <table border=\"0\" width=\"60%\"class=\"normal_10\" align=\"center\">";
		$this->salida .= "	        <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	      	</td><tr>";
		$this->salida .= "		    <tr height=\"20\"><td class=\"".$this->SetStyle("conceptoInv")."\">CONCEPTO INVENTARIO:</td><td><select name=\"conceptoInv\"  class=\"select\">";
		$ConceptosInv=$this->ConceptosInventarios();
		$this->MostrarConceptosInv($ConceptosInv,'False',$conceptoInv);
		$this->salida .= "        </select></td></tr>";
    $this->salida .= "		    <tr>";
		$this->salida .= "	  	  <td class=\"".$this->SetStyle("FechaDocumento")."\">FECHA DOCUMENTO: </td>";
		$this->salida .= "	  	  <td><input type=\"text\" name=\"FechaDocumento\" value=\"$FechaDocumento\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
		$this->salida .= "	  	  ".ReturnOpenCalendario('formaInventarios','FechaDocumento','/')."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "				<tr><td class=\"label\" colspan=\"2\">OBSERVACIONES</td></tr><tr><td colspan=\"2\"><textarea name=\"observacion\" cols=\"65\" rows=\"3\" class=\"textarea\">$observacion</textarea></td></tr>";
		$this->salida .= "        <tr><td colspan=\"2\" align=\"center\"><BR><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"CANCELAR\">";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"CONTINUAR\"></td></tr>";
		$this->salida .= "        </table>";
		$this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

  function DetalleDocumentosBodega($Documento,$conceptoInv,$fechaDocumento,$cantSolicitada,
	$costoProducto,$nombreProducto,$codigo,$unidadProducto,$ExisProducto){
	
    $Empresa=$_SESSION['BODEGAS']['Empresa'];
    $CentroUtili=$_SESSION['BODEGAS']['CentroUtili'];
    $BodegaId=$_SESSION['BODEGAS']['BodegaId'];
    $RUTA = $_ROOT ."classes/classbuscador/buscador.php?";
		$this->salida.="\n<script language='javascript'>\n";
		$this->salida.="  var rem=\"\";\n";
		$this->salida.="  function abrirVentana(){\n";
		unset($_SESSION['SQL']);
		unset($_SESSION['SQLA']);
		unset($_SESSION['SQLB']);
    unset($_SESSION['SQLC']);
		unset($_SESSION['SQLD']);
		$this->salida.="    var str =\"width=550,height=350,resizable=no,status=no,scrollbars=yes,top=200\";\n";
		$this->salida.="    var url2 ='$RUTA'+'&sql='+'$Empresa'+'&sqla='+'$CentroUtili'+'&sqlb='+'$BodegaId'+'&tipo=inventarios'+'&forma=forma';\n";
		$this->salida.="    rem = window.open(url2, 'BUSCAR PRODUCTO', str)}\n";
		$this->salida.="</script>\n";
		$this->salida .= ThemeAbrirTabla('DETALLE DEL DOCUMENTO DE BODEGA');
		$this->salida.=$mostrar;
		$this->salida .="</script>\n";
    $this->salida .= "       <BR>";
		$action=ModuloGetURL('app','InvBodegas','user','InsDetalleDocumentosBodega');
		$this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "		      <input type=\"hidden\" name=\"Documento\" value=\"$Documento\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"conceptoInv\" value=\"$conceptoInv\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"fechaDocumento\" value=\"$fechaDocumento\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"origen\" value=\"1\">";
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "         <tr><td width=\"100%\">";
		$this->salida .= "         <fieldset><legend class=\"field\">DOCUMENTO BODEGA</legend>";
    $this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	        <tr><td></td></tr>";
		$this->salida .= "	        <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"5%\"><label class=\"label\">CONCEPTO</td>";
    $NombreconceptoInv=$this->NomConceptoDocumento($conceptoInv);
		$this->salida .= "	         <td colspan=\"3\">".$NombreconceptoInv['descripcion']."</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td width=\"5%\"><label class=\"label\">FECHA</td>";
		$this->salida .= "	         <td colspan=\"3\">$fechaDocumento</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr><td></td></tr>";
    $this->salida .= "			     </table>";
		$this->salida .= "		      </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
		$ProductosDocumento=$this->ConsultaProductosDocumento($Documento,$conceptoInv);
		if($ProductosDocumento){
		$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
    $this->salida .= "			   <td width=\"10%\">CODIGO</td>";
		$this->salida .= "         <td>PRODUCTO</td>";
		$this->salida .= "			   <td width=\"10%\">CANTIDAD</td>";
    $this->salida .= "			   <td width=\"10%\">COSTO UNIT.</td>";
    $this->salida .= "			   <td width=\"10%\">COSTO TOTAL</td>";
		$this->salida .= "        </tr>";
		$y=0;
		for($i=0;$i<sizeof($ProductosDocumento);$i++){
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "	 <tr class=\"$estilo\">\n";
			$this->salida .= "	 <td>".$ProductosDocumento[$i]['codigo_producto']."</td>";
			$this->salida .= "	 <td>".$ProductosDocumento[$i]['descripcion']."</td>";
      if($ProductosDocumento[$i]['cantidad'] % (int)($ProductosDocumento[$i]['cantidad'])){
			  $this->salida .= "	 <td>".$ProductosDocumento[$i]['cantidad']."</td>";
      }else{
        $this->salida .= "	 <td>".(int)($ProductosDocumento[$i]['cantidad'])."</td>";
      }
      if($ProductosDocumento[$i]['costo_unitario'] % (int)($ProductosDocumento[$i]['costo_unitario'])){
        $this->salida .= "	 <td>".$ProductosDocumento[$i]['costo_unitario']."</td>";
      }else{
        $this->salida .= "	 <td>".(int)($ProductosDocumento[$i]['costo_unitario'])."</td>";
      }
      $this->salida .= "			   <td>".$SumaCosto=($ProductosDocumento[$i]['cantidad']*$ProductosDocumento[$i]['costo_unitario'])."</td>";
      $TotalSuma+=$SumaCosto;
			$this->salida .= "	 </tr>\n";
			$y++;
		}
    $this->salida .= "	 <tr class=\"$estilo\">\n";
    $this->salida .= "	 <td colspan=\"4\" class=\"label\" align=\"right\">TOTAL</td>";
		$this->salida .= "	 <td>".$TotalSuma."</td>";
    $this->salida .= "	 </tr>\n";
    $this->salida .= "       </table><BR>";
		}

		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "	      <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	      </td><tr>";
    $this->salida .= "	      <tr width=\"100%\" class=\"modulo_table_title\"><td align=\"center\">DATOS DEL PRODUCTO</td></tr>";
		$this->salida .= "        <tr class=\"modulo_list_oscuro\"><td width=\"100%\">";
		$this->salida .= "        <BR><table class=\"normal_10\" cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida.= "<tr class=\"modulo_list_claro\">";
		$this->salida.= "<td width=\"5%\" class=\"".$this->SetStyle("nombreProducto")."\">NOMBRE</td>";
		$this->salida.= "<td><input type=\"text\" name=\"nombreProducto\" maxlength=\"50\" size=\"50\" class=\"input-text\" value=\"$nombreProducto\" READONLY></td>";
		$this->salida.= "<td width=\"5%\" class=\"".$this->SetStyle("codigo")."\">CODIGO</td>";
    $this->salida.= "<td><input type=\"text\" name=\"codigo\" maxlength=\"10\" size=\"10\" class=\"input-text\" value=\"$codigo\" READONLY></td>";
		$this->salida.= "</tr>";
		$this->salida.= "<tr class=\"modulo_list_claro\">";
		$this->salida.= "<td width=\"5%\" class=\"".$this->SetStyle("unidadProducto")."\">UNIDAD</td>";
		$this->salida.= "<td><input type=\"text\" name=\"unidadProducto\" maxlength=\"40\" size=\"40\" class=\"input-text\" value=\"$unidadProducto\" READONLY></td>";
		$this->salida.= "<td width=\"5%\" class=\"".$this->SetStyle("ExisProducto")."\">EXISTENCIA</td>";
		$this->salida.= "<td><input type=\"text\" name=\"ExisProducto\" maxlength=\"11\" size=\"11\" class=\"input-text\" value=\"$ExisProducto\" READONLY>";
		$this->salida.= "<input type=\"hidden\" name=\"costoProducto\" maxlength=\"17\" size=\"17\" class=\"input-text\" value=\"$costoProducto\" READONLY>&nbsp&nbsp&nbsp;";
		$this->salida.= "<input type=\"hidden\" name=\"ExisDest\" size=\"17\" class=\"input-text\" value=\"$ExisDest\">";
		$this->salida.= "<input type=\"submit\" name=\"buscar\" value=\"BUSCAR\" class=\"input-submit\"></td>";
		$this->salida.= "</tr>";
		//$this->salida.= "<td><label class=\"".$this->SetStyle("precioProducto")."\">PRECIO</td>";
		$this->salida.= "<input type=\"hidden\" name=\"precioProducto\" maxlength=\"17\" size=\"17\" class=\"input-text\" value=\"$precioProducto\" READONLY>";
		$this->salida .= "	          <tr class=\"modulo_list_claro\">";
    $this->salida .= "	          <td width=\"5%\" class=\"".$this->SetStyle("cantSolicitada")."\">CANTIDAD</td>";
		$this->salida .= "	  	      <td colspan=\"3\"><input type=\"text\" maxlength=\"16\" size=\"16\" name=\"cantSolicitada\" value=\"$cantSolicitada\" class=\"input-text\"></td>";
		$this->salida .= "		    </tr>";
    $this->salida .= "		    <tr class=\"modulo_list_claro\">";
    $this->salida .= "           <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= "           <input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"INSERTAR\"></td></tr>";
    $this->salida .= "			     </table><BR>";
		$this->salida .= "		      </td></tr>";
		$this->salida .= "       </table><BR>";
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "         <tr><td align=\"center\">";
    $this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"Regresar\" value=\"VOLVER\">";
		$this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"GUARDAR DOCUMENTO\">";
    $this->salida .= "         </td></tr>";
    $this->salida .= "       </table><BR>";
		$this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function BuscadorProductoExistencias($Documento,$conceptoInv,$fechaDocumento,$codigoBus,$descripcionBus,$origen,$CentroUtilityDest,$BodegaDest,
    $tipoIdProveedor,$ProveedorId,$proveedor,$numFactura,$iva,$valorFletes,$otrosGastos,$observaciones){

    $this->salida .= ThemeAbrirTabla('SELECCION PRODUCTOS DOCUMENTO BODEGA');
		$action=ModuloGetURL('app','InvBodegas','user','LlamaBuscadorProductoExistencias',array("Documento"=>$Documento,"conceptoInv"=>$conceptoInv,"fechaDocumento"=>$fechaDocumento,"origen"=>$origen,"CentroUtilityDest"=>$CentroUtilityDest,"BodegaDest"=>$BodegaDest,
    "tipoIdProveedor"=>$tipoIdProveedor,"ProveedorId"=>$ProveedorId,"proveedor"=>$proveedor,"numFactura"=>$numFactura,"iva"=>$iva,"valorFletes"=>$valorFletes,"otrosGastos"=>$otrosGastos,"observaciones"=>$observaciones));
		$this->salida .= "      <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "       <tr class=\"modulo_table_list_title\"><td colspan=\"5\">FILTRO DE BUSQUEDA</td></tr>";
		$this->salida .= "       <tr class=\"modulo_list_claro\" >";
		$this->salida .= "       <td width=\"10%\" class=\"label\">CODIGO</td>";
    $this->salida .= "       <td width=\"15%\"><input size=\"10\" maxlength=\"10\" class=\"input-text\" type=\"text\" name=\"codigoBus\" value=\"$codigoBus\"></td>";
		$this->salida .= "       <td width=\"15%\" class=\"label\">DESCRIPCION</td>";
		$this->salida .= "       <td><input size=\"50\" class=\"input-text\" type=\"text\" name=\"descripcionBus\" value=\"$descripcionBus\"></td>";
		$this->salida .= "       <td width=\"10%\" align=\"center\"><input type=\"submit\" name=\"Buscar\" value=\"BUSCAR\" class=\"input-submit\"></td>";
    $this->salida .= "       </tr>";
    $this->salida .= "       </table><BR>";
    $this->salida .="       </form>";
		$datos=$this->DocumentoProductosExistencias($codigoBus,$descripcionBus,$CentroUtilityDest,$BodegaDest);
    if($datos){
    $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "       <tr class=\"modulo_table_list_title\">";
    $this->salida .= "       <td width=\"15%\">CODIGO</td>";
		$this->salida .= "       <td>DESCRIPCION</td>";
		$this->salida .= "       <td width=\"15%\">PRECIO</td>";
		$this->salida .= "       <td width=\"15%\">EXISTENCIAS</td>";
		$this->salida .= "       <td width=\"15%\">UNIDAD</td>";
		$this->salida .= "       <td width=\"5%\">&nbsp;</td>";
		$this->salida .= "       </tr>";
		$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
		for($i=0;$i<sizeof($datos);$i++){
		if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
		$this->salida .= "       <tr  class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
    $this->salida .= "       <td>".$datos[$i]['codigo_producto']."</td>";
		$this->salida .= "       <td>".$datos[$i]['descripcion']."</td>";
		$this->salida .= "       <td>".$datos[$i]['precio_venta']."</td>";
		$this->salida .= "       <td>".$datos[$i]['existencia']."</td>";
		$this->salida .= "       <td>".$datos[$i]['unidad']."</td>";
		if($origen=='1'){
		  $action1=ModuloGetURL('app','InvBodegas','user','LlamaDetalleDocumentosBodega',array("Documento"=>$Documento,"fechaDocumento"=>$fechaDocumento,"conceptoInv"=>$conceptoInv,
      "tipoIdProveedor"=>$tipoIdProveedor,"ProveedorId"=>$ProveedorId,"proveedor"=>$proveedor,"numFactura"=>$numFactura,"iva"=>$iva,"valorFletes"=>$valorFletes,"otrosGastos"=>$otrosGastos,"observaciones"=>$observaciones,
      "costoProducto"=>$datos[$i]['costo'],"nombreProducto"=>$datos[$i]['descripcion'],"codigo"=>$datos[$i]['codigo_producto'],"unidadProducto"=>$datos[$i]['unidad'],"ExisProducto"=>$datos[$i]['existencia']));
		}elseif($origen=='2'){
      $action1=ModuloGetURL('app','InvBodegas','user','LlamaPtosTransferenciaBodegas',array("Documento"=>$Documento,"conceptoInv"=>$conceptoInv,"fechaDocumento"=>$fechaDocumento,"BodegaDest"=>$BodegaDest,"CentroUtilityDest"=>$CentroUtilityDest,
      "tipoIdProveedor"=>$tipoIdProveedor,"ProveedorId"=>$ProveedorId,"proveedor"=>$proveedor,"numFactura"=>$numFactura,"iva"=>$iva,"valorFletes"=>$valorFletes,"otrosGastos"=>$otrosGastos,"observaciones"=>$observaciones,
			"costoProducto"=>$datos[$i]['costo'],"nombreProducto"=>$datos[$i]['descripcion'],"codigo"=>$datos[$i]['codigo_producto'],"unidadProducto"=>$datos[$i]['unidad'],"ExisProducto"=>$datos[$i]['existencia'],"ExisDest"=>$datos[$i]['exisdes']));
		}else{
      $action1=ModuloGetURL('app','InvBodegas','user','LlamaDetDocumentoBodegaLotes',array("Documento"=>$Documento,"fechaDocumento"=>$fechaDocumento,"conceptoInv"=>$conceptoInv,
      "tipoIdProveedor"=>$tipoIdProveedor,"ProveedorId"=>$ProveedorId,"proveedor"=>$proveedor,"numFactura"=>$numFactura,"iva"=>$iva,"valorFletes"=>$valorFletes,"otrosGastos"=>$otrosGastos,"observaciones"=>$observaciones,
			"nombreProducto"=>$datos[$i]['descripcion'],"codigo"=>$datos[$i]['codigo_producto'],"unidadProducto"=>$datos[$i]['unidad'],"ExisProducto"=>$datos[$i]['existencia'],
			"costoProducto"=>$datos[$i]['costo'],"precioProducto"=>$datos[$i]['precio_venta'],
			$_REQUEST['cantSolicitada']));
		}
		$this->salida .= "       <td align=\"center\"><a href=\"$action1\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
    $this->salida .= "       </tr>";
		}
		$this->salida .= "       </table><BR>";
		$this->salida .=$this->RetornarBarra(9);
		}else{
    $this->salida .= "       <br><table class=\"normal_10N\" border=\"0\" width=\"80%\" align=\"center\">";
    $this->salida .= "       <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS</td></tr>";
    $this->salida .= "       </table><BR>";
		}
		if($origen=='1'){
		$action=ModuloGetURL('app','InvBodegas','user','LlamaDetalleDocumentosBodega',array("Documento"=>$Documento,"conceptoInv"=>$conceptoInv,"fechaDocumento"=>$fechaDocumento));
		}elseif($origen=='2'){
		$action=ModuloGetURL('app','InvBodegas','user','LlamaPtosTransferenciaBodegas',array("Documento"=>$Documento,"conceptoInv"=>$conceptoInv,"fechaDocumento"=>$fechaDocumento,"BodegaDest"=>$BodegaDest,"CentroUtilityDest"=>$CentroUtilityDest,
    "tipoIdProveedor"=>$tipoIdProveedor,"ProveedorId"=>$ProveedorId,"proveedor"=>$proveedor,"numFactura"=>$numFactura,"iva"=>$iva,"valorFletes"=>$valorFletes,"otrosGastos"=>$otrosGastos,"observaciones"=>$observaciones));
		}else{
    $action=ModuloGetURL('app','InvBodegas','user','LlamaDetDocumentoBodegaLotes',array("Documento"=>$Documento,"conceptoInv"=>$conceptoInv,"fechaDocumento"=>$fechaDocumento));
		}
		$this->salida .= "      <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "       <br><table class=\"normal_10N\" border=\"0\" width=\"80%\" align=\"center\">";
    $this->salida .= "       <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "       </table><BR>";
    $this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function MostrarBodegas($Arreglo,$Seleccionado='False',$variable=''){
	  switch($Seleccionado){
			case 'False':{
			  $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
			  for($i=0;$i<sizeof($Arreglo);$i++){
				  $value=$Arreglo[$i]['centro_utilidad'].'/'.$Arreglo[$i]['bodega'].'/'.$Arreglo[$i]['tipo_reposicion'];
          $titulo=$Arreglo[$i]['descripcion'];
					if($value==$variable){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
		  }
			case 'True':{
			  for($i=0;$i<sizeof($Arreglo);$i++){
				  $value=$Arreglo[$i]['centro_utilidad'].'/'.$Arreglo[$i]['bodega'].'/'.$Arreglo[$i]['tipo_reposicion'];
					$titulo=$Arreglo[$i]['descripcion'];
					if($value==$variable){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
			}
		}
	}

	function MostrarConceptosInv($Arreglo,$Seleccionado='False',$variable=''){
	  switch($Seleccionado){
			case 'False':{
			  $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
			  for($i=0;$i<sizeof($Arreglo);$i++){
				  $value=$Arreglo[$i]['bodegas_doc_id'];
          $titulo=$Arreglo[$i]['nombremov'].' '.$Arreglo[$i]['descripcion'];
					if($value==$variable){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
		  }
			case 'True':{
			  for($i=0;$i<sizeof($Arreglo);$i++){
				  $value=$Arreglo[$i]['bodegas_doc_id'];
          $titulo=$Arreglo[$i]['nombremov'].' '.$Arreglo[$i]['descripcion'];
					if($value==$variable){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
			}
		}
	}

	function Mostrar($Arreglo,$Seleccionado='False',$variable=''){
	  switch($Seleccionado){
			case 'False':{
			  $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
			  foreach($Arreglo as $value=>$titulo){
					if($value==$variable){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
		  }
			case 'True':{
			  foreach($Arreglo as $value=>$titulo){
					if($value==$variable){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
			}
		}
	}


/**
* La funcion que visualiza el detalle del documento de la bodega
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa a la que pertenece el documento
* @param string centro de utilidad al que pertenece el documento
* @param string nombre del centro de utilidad al que pertenece ele documento
* @param string bodega en donde se realizo el documento
* @param string nombre de la bodega en el que se realizo el documento
* @param string codigo del documento de la bodega
* @param string prefijo del documento de la bodega
* @param array documentos pertenecientes a la bodega
* @param date fecha de la realizacion del documento de la bodega
* @param string codigo de la solicitud por la que se hizo el documento
* @param string concepto del inventario del documento
* @param string costo total del documento
*/

	function DetalleDelDocumentoBodega($Documento,$concepto,$documentos,$fecha,$solicitud,$nomconcepto,$costo,$centroutiliTrans,$BodegaTrans,
    $BusquedaBus,$documentosBus,$FechaInicialBus,$FechaFinalBus,$conceptoInvBus,$numDocumentoBus,$usuario){

		$this->salida .= themeAbrirTabla('DETALLE DOCUMENTO BODEGA')."<br>";
    //$action = ModuloGetURL('app','Inventarios','user','RegresarDocumentoBodega',array("documentos"=>$documentos));
		$action = ModuloGetURL('app','InvBodegas','user','selecBusquedaDocumento1',array(
		"conteo"=>$this->conteo,"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso'],
    "Busqueda"=>$BusquedaBus,"documentos"=>$documentosBus,"FechaInicial"=>$FechaInicialBus,
    "FechaFinal"=>$FechaFinalBus,"conceptoInv"=>$conceptoInvBus,"numDocumento"=>$numDocumentoBus));
		$this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "		      <input type=\"hidden\" name=\"Documento\" value=\"$Documento\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"concepto\" value=\"$concepto\" >";
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "          <tr><td width=\"100%\">";
		$this->salida .= "          <fieldset><legend class=\"field\">DOCUMENTO BODEGA</legend>";
    $this->salida .= "          <table class=\"normal_10\" cellspacing=\"2\" cellpadding=\"3\" border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	         <tr><td></td></tr>";
		$this->salida .= "	         <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">DOCUMENTO</td>";
    $this->salida .= "	         <td>$Documento</td>";
		$this->salida .= "	         <td width=\"5%\"><label class=\"label\">CONCEPTO</td>";
		$this->salida .= "	         <td>$nomconcepto</td>";
		$this->salida .= "	         </tr>";
		$this->salida .= "	         <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">FECHA</td>";
		$this->salida .= "	         <td>$fecha</td>";
		$this->salida .= "	         <td width=\"5%\"><label class=\"label\">COSTO TOTAL</td>";
		$this->salida .= "	         <td>$costo</td>";
		$this->salida .= "	         </tr>";
		$this->salida .= "	         <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">USUARIO</td>";
		$this->salida .= "	         <td colspan=\"3\">$usuario</td>";
		$this->salida .= "	         </tr>";
    $pacienteDat=$this->ConfirmarDocumentoPaciente($Documento,$concepto);
    if($pacienteDat){
      $this->salida .= "           <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "           <td width=\"20%\"><label class=\"label\">PACIENTE</td>";
      $this->salida .= "           <td colspan=\"3\">".$pacienteDat['tipo_id_paciente']." ".$pacienteDat['paciente_id']." ".$pacienteDat['nombrepac']."</td>";      
      $this->salida .= "           </tr>";
      $this->salida .= "           <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "           <td width=\"5%\"><label class=\"label\">CUENTA</td>";
      $this->salida .= "           <td>".$pacienteDat['numerodecuenta']."</td>";
      $this->salida .= "           <td width=\"5%\"><label class=\"label\">PIEZA - CAMA</td>";
      $this->salida .= "           <td>".$pacienteDat['pieza']." ".$pacienteDat['cama']."</td>";
      $this->salida .= "           </tr>";
    }
    $datosDetalleDoc=$this->DatosDetalleDelDocumento($Documento,$concepto);
    if(!empty($datosDetalleDoc[0]['numero_factura'])){
    $this->salida .= "	         <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">No. FACTURA</td>";
		$this->salida .= "	         <td colspan=\"3\">".$datosDetalleDoc[0]['numero_factura']."</td>";
		$this->salida .= "	         </tr>";
    $this->salida .= "	         <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">FLETES</td>";
		$this->salida .= "	         <td>".$datosDetalleDoc[0]['costo_fletes']."</td>";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">OTROS GASTOS</td>";
		$this->salida .= "	         <td>".$datosDetalleDoc[0]['otros_gastos']."</td>";
		$this->salida .= "	         </tr>";
    $this->salida .= "	         <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">PROVEEDOR</td>";
		$this->salida .= "	         <td colspan=\"3\">".$datosDetalleDoc[0]['nombre_tercero']."</td>";
		$this->salida .= "	         </tr>";
    $this->salida .= "	         <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">OBSERVACIONES</td>";
		$this->salida .= "	         <td colspan=\"3\">".$datosDetalleDoc[0]['observaciones']."</td>";
		$this->salida .= "	         </tr>";
    $SumaTotalCosto=0;
    for($i=0;$i<sizeof($datosDetalleDoc);$i++){
      if(!empty($datosDetalleDoc[$i]['iva_compra'])){
        $valorIva=($datosDetalleDoc[$i]['total_costo']*$datosDetalleDoc[$i]['iva_compra'])/100;
        $totalIva=$datosDetalleDoc[$i]['total_costo']+$valorIva;
      }else{
        $totalIva=$datosDetalleDoc[$i]['total_costo'];
      }
      $SumaCostoTot=$datosDetalleDoc[$i]['cantidad'] * $totalIva;
      $SumaTotalCosto+=$SumaCostoTot;
    }
    $this->salida .= "	         <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">TOTAL</td>";
		$this->salida .= "	         <td colspan=\"3\">".($SumaTotalCosto+$datosDetalleDoc[0]['otros_gastos'])."</td>";
		$this->salida .= "	         </tr>";
    }else{
		$this->salida .= "	         <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">SOLICITUD</td>";
		$this->salida .= "	         <td colspan=\"3\">$solicitud</td>";
		$this->salida .= "	         </tr>";
		$this->salida .= "	         <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td colspan=\"2\" width=\"20%\"><label class=\"label\">CENTRO UTILIDAD MVTO. TRANSFERENCIA</td>";
		$NombreCentro=$this->NombreCentroUtilidad($centroutiliTrans);
		$this->salida .= "	         <td colspan=\"2\">".$NombreCentro['descripcion']."</td>";
		$this->salida .= "	         </tr>";
		$this->salida .= "	         <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td colspan=\"2\" width=\"20%\"><label class=\"label\">BODEGA MVTO. TRANSFERENCIA</td>";
		$NombreBodega=$this->NombreBodegasInventario($BodegaTrans,$centroutiliTrans);
		$this->salida .= "	         <td colspan=\"2\">$BodegaTrans ".$NombreBodega['descripcion']."</td>";
		$this->salida .= "	         </tr>";
    }
    $this->salida .= "			     </table>";
		$this->salida .= "		      </fieldset></td><BR>";
		$this->salida .= "       </table><BR><BR>";
    if($datosDetalleDoc){
      $y=0;
		  $this->salida .= "     <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "     <tr>";
		  $this->salida .= "			<td width=\"10%\" class=\"modulo_table_list_title\">CODIGO PRODUCTO</td>\n";
      $this->salida .= "			<td class=\"modulo_table_list_title\">NOMBRE</td>\n";
			$this->salida .= "			<td width=\"10%\" class=\"modulo_table_list_title\">CANTIDAD</td>\n";
			$this->salida .= "			<td width=\"10%\" class=\"modulo_table_list_title\">COSTO</td>\n";
      $this->salida .= "			<td width=\"10%\" class=\"modulo_table_list_title\">IVA</td>\n";
      $this->salida .= "			<td width=\"10%\" class=\"modulo_table_list_title\">COSTO + IVA </td>\n";
      $this->salida .= "			<td width=\"10%\" class=\"modulo_table_list_title\">COSTO TOTAL</td>\n";
      $this->salida .= "	    </tr>\n";
      $SumaTotalCosto=0;
		  for($i=0;$i<sizeof($datosDetalleDoc);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			  $this->salida .= "	 <tr class=\"$estilo\">\n";
			  $this->salida .= "	 <td>".$datosDetalleDoc[$i]['codigo_producto']."</td>";
				$this->salida .= "	 <td>".$datosDetalleDoc[$i]['descripcion']."</td>";
        if($datosDetalleDoc[$i]['cantidad'] % (int)($datosDetalleDoc[$i]['cantidad'])){
          $this->salida .= "	 <td>".$datosDetalleDoc[$i]['cantidad']."</td>";
        }else{
          $this->salida .= "	 <td>".(int)($datosDetalleDoc[$i]['cantidad'])."</td>";
        }        
        $this->salida.="       <td>".$datosDetalleDoc[$i]['total_costo']."</td>";          
        if(!empty($datosDetalleDoc[$i]['iva_compra'])){
          $valorIva=(($datosDetalleDoc[$i]['total_costo']*$datosDetalleDoc[$i]['iva_compra'])/100);
          $this->salida .= "	 <td>".floor($datosDetalleDoc[$i]['iva_compra'])." % - ".$valorIva."</td>";
          $this->salida .= "	 <td>".$totalIva=$datosDetalleDoc[$i]['total_costo']+$valorIva."</td>";
          $this->salida .= "	 <td>".$SumaCostoTot=($datosDetalleDoc[$i]['cantidad'] * $totalIva)."</td>";
        }else{
          $this->salida .= "	 <td>0</td>";
          $this->salida .= "	 <td>".$totalIva=$datosDetalleDoc[$i]['total_costo']."</td>";
          $this->salida .= "	 <td>".$SumaCostoTot=($datosDetalleDoc[$i]['cantidad'] * $totalIva)."</td>";
        }
				$this->salida .= "	 </tr>\n";
        $SumaTotalCosto+=$SumaCostoTot;
        $SumaTotalCostoUnit+=$totalIva;
        $SumaIvas+=$valorIva;
        $SumaCostos+=$datosDetalleDoc[$i]['total_costo'];
				$y++;
			}
      $this->salida .= "	 <tr class=\"$estilo\">\n";
      $this->salida .= "	 <td class=\"label\">TOTALES</td>";
      $this->salida .= "	 <td colspan=\"2\">&nbsp;</td>";
      $this->salida .= "	 <td>".$SumaCostos."</td>";
      $this->salida .= "	 <td>".$SumaIvas."</td>";
      $this->salida .= "	 <td>".$SumaTotalCostoUnit."</td>";
			$this->salida .= "	 <td>".$SumaTotalCosto."</td>";
      $this->salida .= "	 </tr>\n";
			$this->salida .= "   </table><BR>";
		}
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "         <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir1\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "       </table><br>";
		$this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}//
	/**
	* Funcion que se encarga de separar la fecha del formato timestamp
	* @return array
	*/
	function FechaStamp($fecha){

    if($fecha){
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
    }
  }

/**
* Function que muestra al menu con la opciones que puede seleccionar para trabajar
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa en la que se esta trabajando
* @param string centro de utilidad en el que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string bodega en la que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
*/

	function MenuInventarios5(){

    $this->salida .= ThemeAbrirTabla('MENU MOVIMIENTOS DE BODEGAS');
		$action=ModuloGetURL('app','InvBodegas','user','MenuInventarios');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
		$action1=ModuloGetURL('app','InvBodegas','user','LlamaConfirmacionTransferenciasBodegas');
		$action3=ModuloGetURL('app','InvBodegas','user','MenuInventariosDespachos');
		$action4=ModuloGetURL('app','InvBodegas','user','MenuInventariosDevolucion');
    $action5=ModuloGetURL('app','InvBodegas','user','LlamaListadoSolicidudesnoConfirmar');
		$action6=ModuloGetURL('app','InvBodegas','user','LlamaRecibirOrdenesCompra');
		$action7=ModuloGetURL('app','InvBodegas','user','LlamaReposicionAutomaticaPtos');
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action3\" class=\"link\"><b>DESPACHO SOLICITUDES</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action4\" class=\"link\"><b>DEVOLUCIONES SOLICITUDES</b></a></td></tr>";
		//$this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action5\" class=\"link\"><b>REPORTE SOLICITUDES SIN CONFIRMAR</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action1\" class=\"link\"><b>CONFIRMACION TRANSFERENCIAS</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action6\" class=\"link\"><b>RECEPCION ORDENES DE COMPRA</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action7\" class=\"link\"><b>REPOSICION AUTOMATICA DE PRODUCTOS</b></a></td></tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		<table width=\"40%\" align=\"center\" class='normal_10N'>";
		$this->salida .= "    <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "		</table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
* Function que muestra al menu con la opciones que puede seleccionar para trabajar
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa en la que se esta trabajando
* @param string centro de utilidad en el que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string bodega en la que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
*/

	function MenuInventariosDespachos(){

    $this->salida .= ThemeAbrirTabla('MENU DESPACHOS SOLICITUDES A LA BODEGA');
		$action=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
		$action3=ModuloGetURL('app','InvBodegas','user','LlamaSoliciMedica');
    $action1=ModuloGetURL('app','InvBodegas','user','LlamaSolicitudesSuministroEst');
    $action2=ModuloGetURL('app','InvBodegas','user','LlamaSolicitudesProductosResposables');
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action3\" class=\"link\"><b>DESPACHO SOLICITUDES MEDICAMENTOS E INSUMOS</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action1\" class=\"link\"><b>DESPACHO SUMINISTROS ESTACION</b></a></td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action2\" class=\"link\"><b>DESPACHO DE PRODUCTOS A RESPONSABLES</b></a></td></tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		<table width=\"40%\" align=\"center\" class='normal_10N'>";
		$this->salida .= "    <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "		</table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

  /**
* Function que muestra al menu con la opciones que puede seleccionar para trabajar
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa en la que se esta trabajando
* @param string centro de utilidad en el que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string bodega en la que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
*/

	function MenuInventariosDevolucion(){

    $this->salida .= ThemeAbrirTabla('MENU DEVOLUCIONES DE SOLICITUDES A LA BODEGA');
		$action=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "		<tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
		$action4=ModuloGetURL('app','InvBodegas','user','LlamaDevolucionMedicamentos');
    $action2=ModuloGetURL('app','InvBodegas','user','LlamaDevolucionSuministrosEstacion');
		$this->salida .= "		<tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action4\" class=\"link\"><b>DEVOLUCIONES DE MEDICAMENTOS E INSUMOS</b></a></td></tr>";
    //$this->salida .= "		<tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action2\" class=\"link\"><b>DEVOLUCIONES DE SUMINISTROS ESTACION</b></a></td></tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		<table width=\"40%\" align=\"center\" class='normal_10N'>";
		$this->salida .= "    <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "		</table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function MenuInventarios2(){

    $this->salida .= ThemeAbrirTabla('MENU TOMAS FISICAS');
		$action=ModuloGetURL('app','InvBodegas','user','MenuInventarios');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
    $action6=ModuloGetURL('app','InvBodegas','user','LlamaSelecProdTomaFisica');
		$action7=ModuloGetURL('app','InvBodegas','user','LlamaReporteTomasFisicas');
		//$action8=ModuloGetURL('app','Inventarios','user','LlamaReporteDifTomasFisicas',array("Empresa"=>$Empresa,"NombreEmp"=>$NombreEmp,"CentroUtili"=>$CentroUtili,"NombreCU"=>$NombreCU,"BodegaId"=>$BodegaId,"NombreBodega"=>$NombreBodega));
		//$action9=ModuloGetURL('app','Inventarios','user','ActualTomaFisicas',array("Empresa"=>$Empresa,"NombreEmp"=>$NombreEmp,"CentroUtili"=>$CentroUtili,"NombreCU"=>$NombreCU,"BodegaId"=>$BodegaId,"NombreBodega"=>$NombreBodega));
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\"><b>MENU</b></td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action6\" class=\"link\"><b>SELECCION PRODUCTOS TOMA FISICA</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action7\" class=\"link\"><b>TOMAS FISICAS</b></a></td></tr>";
		//$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action8\" class=\"link\">DIFERENCIAS TOMAS FISICAS</a></td></tr>";
		//$this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action9\" class=\"link\">ACTUALIZACION SISTEMA TOMAS FISICAS</a></td></tr>";
		$this->salida .= "		</table>";
		$this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "    <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "		</table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function SeleccionProductosTomaFisica($TomaFisica, $CancelarTomAlet, $codigoProd, $descripcion, $grupo, $NomGrupo, $clasePr, $NomClase, $subclase, $NomSubClase){
          
          $Empresa=$_SESSION['BODEGAS']['Empresa'];
          $CentroUtili=$_SESSION['BODEGAS']['CentroUtili'];
          $BodegaId=$_SESSION['BODEGAS']['BodegaId'];          
          
          $this->paginaActual = 1;
          $this->offset = 0;
          if($_REQUEST['offset']){
               $this->paginaActual = intval($_REQUEST['offset']);
               if($this->paginaActual > 1){
                    $this->offset = ($this->paginaActual - 1) * ($this->limit);
               }
          }
          $this->salida .= ThemeAbrirTabla('SELECCION DE PRODUCTOS TOMA FISICA');
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function chequeoTotal(frm,x){";
          $this->salida .= "  if(x==true){";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "        frm.elements[i].checked=true";
		$this->salida .= "      }";
		$this->salida .= "    }";
          $this->salida .= "  }else{";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "        frm.elements[i].checked=false";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }";
          $this->salida .= "}";
		$this->salida .= "</SCRIPT>";
          
          $this->salida .= "<script>";
          $this->salida .= "  function ventanaubicacion(nombre, url, ancho, altura, x,frm,Cdproducto){";
          $this->salida .="     var str = 'width=380,height=200,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .="     var url2 = url+'?Empresa='+$Empresa+'&centroutilidad='+$CentroUtili+'&Bodega='+'$BodegaId'+'&CodigoPr='+Cdproducto;";
		$this->salida .="     var rems = window.open(url2, nombre, str);\n";
		$this->salida .="     if (rems != null) {\n";
		$this->salida .="       if (rems.opener == null) {\n";
		$this->salida .="	        rems.opener = self;\n";
		$this->salida .="       }\n";
		$this->salida .="     }\n";
		$this->salida .= "  }";
		$this->salida .= "  function xxx(){";
		$this->salida .= "   document.location.reload();";
          $this->salida .= "  }";
		$this->salida .="function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		unset($_SESSION['SQL']);
		unset($_SESSION['SQLA']);
		unset($_SESSION['SQLB']);
    		unset($_SESSION['SQLC']);
		unset($_SESSION['SQLD']);
		$this->salida .=" f=frm;\n";
		$this->salida .=" var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .=" var ban=1;";
		$this->salida .=" var url2 = url+'?bandera='+ban;";
		$this->salida .=" var rems = window.open(url2, nombre, str);\n";
		$this->salida .=" if (rems != null) {\n";
		$this->salida .="   if (rems.opener == null) {\n";
		$this->salida .="	    rems.opener = self;\n";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .="}\n";
		$this->salida .= "</script>";
          
          $actionBuscar = ModuloGetURL('app','InvBodegas','user','ConsultaProductosSelectTomaXFiltro');
		$this->salida .= "    <form name=\"forma\" action=\"$actionBuscar\" method=\"post\">";
		
          $this->Encabezado();
		
          $this->salida .= "         <table class=\"modulo_table_list\" border=\"0\" width=\"90%\" align=\"center\" >";
		$this->salida .= "         <tr><td colspan=\"2\" class=\"modulo_table_list_title\">FILTROS DE BUSQUEDA</td></tr>";
		$this->salida .= "         <tr><td class=\"modulo_list_claro\" width=\"60%\">";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
		$this->salida .= "	  	   <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">GRUPO:</td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomGrupo\" value=\"$NomGrupo\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "         <input type=\"hidden\" name=\"grupo\" value=\"$grupo\" class=\"input-text\">";
		$this->salida .= "	  	   </tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">CLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomClase\"  value=\"$NomClase\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "         <input type=\"hidden\" name=\"clasePr\" value=\"$clasePr\" class=\"input-text\" >";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">SUBCLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomSubClase\"  value=\"$NomSubClase\" size=\"30\" class=\"input-text\" readonly>";
		$this->salida .= "         <input type=\"hidden\" name=\"subclase\" value=\"$subclase\" class=\"input-text\" >";
		$ruta='app_modules/InvBodegas/ventanaClasificacion.php';
		$this->salida .= "         <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\"></td>";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     </table><BR><BR>";
          $this->salida .= "		     </td>";
          $this->salida .= "		     <td valign=\"top\" class=\"modulo_list_claro\" width=\"40%\"> ";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">CODIGO</td><td><input type=\"text\" class=\"input-text\" name=\"codigoProd\" value=\"$codigoProd\"></td></tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">DESCRIPCION</td><td><input type=\"text\" class=\"input-text\" name=\"descripcion\" value=\"$descripcion\"></td></tr>";
		$this->salida .= "		     </table><BR>";
		$this->salida .= "         </td></tr>";
		$this->salida .= "         <tr><td colspan=\"2\" align=\"center\" class=\"modulo_list_claro\"><input type=\"submit\" class=\"input-submit\" value=\"FILTRAR\" name=\"filtrar\"></td></tr>";
		$this->salida .= "		     </table><BR>";
		$this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "       <tr><td>&nbsp&nbsp;</td></tr>";
		$this->salida .= "       </td></tr>";
		$this->salida .= "			 </table>";
          $this->salida .= "         <input type=\"hidden\" name=\"TomaFisica\" value=\"$TomaFisica\" class=\"input-text\" >";
          $this->salida .= "         <input type=\"hidden\" name=\"CancelarTomAlet\" value=\"$CancelarTomAlet\" class=\"input-text\" >";
          $this->salida .= "    </form>";
          
		$action=ModuloGetURL('app','InvBodegas','user','InsertarTomaFisica',array("conteo"=>$this->conteo,"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso']));
		$this->salida .= "    <form name=\"formaListado\" action=\"$action\" method=\"post\">";

		$this->salida .= "		<input type=\"hidden\" name=\"TomaFisica\" value=\"$TomaFisica\" >";
		$this->salida .= "		<input type=\"hidden\" name=\"CancelarTomAlet\" value=\"$CancelarTomAlet\" >";
		$this->salida .= "    <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "	  <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	  </td><tr>";
		$ExistenciasBodegas=$this->ConsultaProductosSelectToma($grupo, $clasePr, $subclase, $codigoProd, $descripcion);
		if($ExistenciasBodegas){
		$this->salida .= "	  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "    <tr>";
		$this->salida .= "			<td class=\"modulo_table_list_title\">CODIGO</td>\n";
		$this->salida .= "			<td width=\"100%\" class=\"modulo_table_list_title\">DESCRIPCION</td>\n";
		$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIA</td>\n";
		$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIA MIN</td>\n";
		$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIA MAX</td>\n";
		$this->salida .= "     <td class=\"modulo_table_list_title\"><input type=\"checkbox\" name=\"checkTotal\" onclick=\"chequeoTotal(this.form,this.checked)\"></td>";
		$this->salida .= "		 </tr>\n";
		$y=0;
		if(empty($_REQUEST['paso']))
		{
		  $_REQUEST['paso']=1;
		}
		for($i=0;$i<sizeof($ExistenciasBodegas);$i++){
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "	 <tr class=\"$estilo\">\n";
			$Pr=$ExistenciasBodegas[$i]['codigo_producto'].'/'.$ExistenciasBodegas[$i]['existencia'];
			$this->salida .= "	 <td>".$ExistenciasBodegas[$i]['codigo_producto']."</td>";
			$this->salida .= "	 <td width=\"100%\">".$ExistenciasBodegas[$i]['desprod']."</td>";
			$this->salida .= "	 <td>".$ExistenciasBodegas[$i]['existencia']."</td>";
			$this->salida .= "	 <td>".$ExistenciasBodegas[$i]['existencia_minima']."</td>";
			$this->salida .= "	 <td>".$ExistenciasBodegas[$i]['existencia_maxima']."</td>";
			$chequeado='';
 			if($_SESSION['Inventarios'][$_REQUEST['paso']][$ExistenciasBodegas[$i]['codigo_producto']][$ExistenciasBodegas[$i]['existencia']]==1)
			{
 				$chequeado='checked';
			}
			$this->salida .= "  <td width=\"5%\" align=\"center\"><input type=\"checkbox\" name=\"Seleccion[$i]\" value=\"$Pr\" $chequeado></td>";
			$this->salida .= "	 </tr>\n";
			$y++;
		}
		$this->salida .= "       </table><br>";
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "        <tr><td align=\"center\"><BR>";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Aleatoria\" value=\"TOMA FISICA ALEATORIA\">";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"GUARDAR PRODUCTOS SELECCION\">";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"VerTomaFisica\" value=\"VER REPORTE TOMA FISICA\">";
		$this->salida .= "        </td></tr>";
		$this->salida .= "       </table><BR>";
		//$bandera=4;
		//$this->salida .=$this->RetornarBarra($bandera,$TomaFisica);
		$Paginador = new ClaseHTML();		
		$this->actionPaginador=ModuloGetURL('app','InvBodegas','user','InsertarTomaFisica',array("TomaFisica"=>$TomaFisica));
		$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
		}else{
               $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
               $this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS EN ESTA BODEGA</td></tr>";
               $this->salida .= "       </table>";
		}
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "        <tr><td align=\"center\"><BR><input class=\"input-submit\" type=\"submit\" name=\"SalirSinGuardar\" value=\"SALIR SIN GUARDAR\">";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
          $this->salida .= "       </table>";
          $this->salida .= "       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

  function TomarTomaAleatoria($bandera,$cantidadPro,$grupo,$clasePr,$subclase){

    $this->salida .= ThemeAbrirTabla('PRODUCTOS ELEGIDOS ALEATORIAMENTE DE LA TOMA FISICA');
		$this->salida .= "<SCRIPT>";
		$this->salida .="function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		unset($_SESSION['SQL']);
		unset($_SESSION['SQLA']);
		unset($_SESSION['SQLB']);
    unset($_SESSION['SQLC']);
		unset($_SESSION['SQLD']);
		$this->salida .=" f=frm;\n";
		$this->salida .=" var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .=" var ban=1;";
		$this->salida .=" var url2 = url+'?bandera='+ban;";
		$this->salida .=" var rems = window.open(url2, nombre, str);\n";
		$this->salida .=" if (rems != null) {\n";
		$this->salida .="   if (rems.opener == null) {\n";
		$this->salida .="	    rems.opener = self;\n";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .="}\n";
		$this->salida .= "</SCRIPT>";
		$action=ModuloGetURL('app','InvBodegas','user','InsertarSeleccionFisicaAlea');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "		<input type=\"hidden\" name=\"bandera\" value=\"$bandera\" >";
		$this->salida .= "		<input type=\"hidden\" name=\"cantidadPro\" value=\"$cantidadPro\" >";
		if(!$bandera){
		$this->salida .= "         <table class=\"modulo_table_list\" border=\"0\" width=\"50%\" align=\"center\" >";
		$this->salida .= "         <tr><td class=\"modulo_table_list_title\">REQUERIMIENTOS DE SELECCION</td></tr>";
    $this->salida .= "         <tr><td class=\"modulo_list_claro\" width=\"60%\">";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "	  	   <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">GRUPO:</td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomGrupo\" value=\"$NomGrupo\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "         <input type=\"hidden\" name=\"grupo\" value=\"$grupo\" class=\"input-text\">";
		$this->salida .= "	  	   </tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">CLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomClase\"  value=\"$NomClase\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "         <input type=\"hidden\" name=\"clasePr\" value=\"$clasePr\" class=\"input-text\" >";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">SUBCLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomSubClase\"  value=\"$NomSubClase\" size=\"30\" class=\"input-text\" readonly>";
		$this->salida .= "         <input type=\"hidden\" name=\"subclase\" value=\"$subclase\" class=\"input-text\" >";
		$ruta='app_modules/InvBodegas/ventanaClasificacion.php';
		$this->salida .= "         <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\"></td>";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     </table><BR>";
    $this->salida .= "		     </td></tr>";
    $this->salida .= "		     </table><br>";
		$this->salida .= "    <table class=\"normal_10\"  border=\"0\" width=\"30%\" align=\"center\" >";
		$this->salida .= "    <tr><td width=\"100%\">";
		$this->salida .= "    <fieldset><legend class=\"field\">PRODUCTOS DE LA SELECCION</legend>";
		$this->salida .= "    <table class=\"normal_10\"  cellspacing=\"2\" cellpadding=\"3\"border=\"0\"   width=\"95%\" align=\"center\">";
		$this->salida .= "	  <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	  </td><tr>";
		$this->salida .= "	   <tr>";
		$this->salida .= "	   <td width=\"30%\"><label class=\"label\">CANTIDAD</td>";
		$this->salida .= "	   <td><input type=\"text\" size=\"5\" class=\"input-text\" name=\"cantidadPro\"></td>";
		$this->salida .= "	   </tr>";
		$this->salida .= "	   <tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td></tr>";
		$this->salida .= "	   <tr><td></td></tr>";
		$this->salida .= "		 </table>";
		$this->salida .= "		 </fieldset></td><BR>";
		$this->salida .= "    </table><BR>";
		$this->salida .= "	  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "	  <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "	  </table>";
		}
		if($bandera==1){
			$TotalProductos=$this->TomaFisicaAleatoriaInv($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase']);
			if(sizeof($TotalProductos)<$cantidadPro){
        $cantidadPro=sizeof($TotalProductos);
			}
			if(sizeof($TotalProductos)>0){
				srand ((float) microtime() * 10000000);
				$rand_keys=array_rand($TotalProductos,$cantidadPro);
				$this->salida .= "	  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "    <tr>";
				$this->salida .= "			<td class=\"modulo_table_list_title\">CODIGO</td>\n";
				$this->salida .= "			<td width=\"100%\" class=\"modulo_table_list_title\">DESCRIPCION</td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIA</td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIA MIN</td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">EXISTENCIA MAX</td>\n";
				$this->salida .= "		 </tr>\n";
				$y=0;
				for($i=0;$i<$cantidadPro;$i++){
					$codigoPr=$TotalProductos[$rand_keys[$i]];
					$datosTotalesPr=$this->DatosProductosExsitenciaAlea($codigoPr);
					if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					$this->salida .= "	 <tr class=\"$estilo\">\n";
					$this->salida .= "	 <td>".$datosTotalesPr['codigo_producto']."</td>";
					$DescripProd=substr($datosTotalesPr['desprod'],0,20);
					$this->salida .= "	 <td width=\"100%\">$DescripProd</td>";
					$this->salida .= "	 <td>".$datosTotalesPr['existencia']."</td>";
					$this->salida .= "	 <td>".$datosTotalesPr['existencia_minima']."</td>";
					$this->salida .= "	 <td>".$datosTotalesPr['existencia_maxima']."</td>";
					$this->salida .= "	 </tr>\n";
					$y++;
					$_SESSION['Inventarios'][$i][$codigoPr][$datosTotalesPr['existencia']]=1;
				}
				$this->salida .= "		</table><BR>";
				$bandera=7;
				//$this->salida .=$this->RetornarBarra($bandera);
				$this->salida .= "	  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "	  <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"GuardarToma\" value=\"GUARDAR PRODUCTOS\">";
				$this->salida .= "	  <input type=\"submit\" class=\"input-submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
        $this->salida .= "	  </table>";
			}else{
				$this->salida .= "	  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "	  <tr><td align=\"center\" class=\"label_error\">NO EXISTEN PRODUCTOS PARA ELEGIR CON ESTOS REQUERIMIENTOS</td></tr>";
				$this->salida .= "	  <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Cancelar\" value=\"CANCELAR\"></td></tr>";
				$this->salida .= "	  </table>";
		  }
		}
		$this->salida .= "    </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function DetalleListadoTomasFisicas($TomaFisica,$Fecha,$bandera,$grupo,$clasePr,$subclase,$codigoProd,$descripcion,$NomGrupo,$NomClase,$NomSubClase){

	  if($bandera!=1){
	  $titulo='PRODUCTOS SELECCIONADOS DE LA TOMA FISICA';
		}else{
    $titulo='DIFERENCIAS DE LAS TOMAS FISICAS';
		}
    $this->salida .= ThemeAbrirTabla($titulo);
		$this->salida .= "<SCRIPT>";
		$this->salida .="function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		unset($_SESSION['SQL']);
		unset($_SESSION['SQLA']);
		unset($_SESSION['SQLB']);
    unset($_SESSION['SQLC']);
		unset($_SESSION['SQLD']);
		$this->salida .=" f=frm;\n";
		$this->salida .=" var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .=" var ban=1;";
		$this->salida .=" var url2 = url+'?bandera='+ban;";
		$this->salida .=" var rems = window.open(url2, nombre, str);\n";
		$this->salida .=" if (rems != null) {\n";
		$this->salida .="   if (rems.opener == null) {\n";
		$this->salida .="	    rems.opener = self;\n";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .="}\n";
		$this->salida .= "</SCRIPT>";
		$action=ModuloGetURL('app','InvBodegas','user','InsertarCantidadesFisica',array("conteo"=>$this->conteo,"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso']));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "       <table class=\"normal_10\"  border=\"0\" width=\"40%\" align=\"center\" >";
		$this->salida .= "        <tr><td width=\"100%\">";
		$this->salida .= "        <fieldset><legend class=\"field\">TOMA FISICA</legend>";
		$this->salida .= "          <table class=\"normal_10\"  cellspacing=\"2\" cellpadding=\"3\"border=\"0\"   width=\"95%\" align=\"center\">";
		$this->salida .= "	          <tr><td></td></tr>";
		$this->salida .= "	          <tr class=\"modulo_list_claro\">";
		$this->salida .= "	          <td><label class=\"label\">NUMERO</td>";
		$this->salida .= "	          <td>$TomaFisica</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	          <td><label class=\"label\">FECHA</td>";
		$this->salida .= "	          <td>$Fecha</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr><td></td></tr>";
		$this->salida .= "			    </table>";
		$this->salida .= "		     </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
    if($bandera!=1){
		$this->salida .= "         <table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\" >";
		$this->salida .= "         <tr><td colspan=\"2\" class=\"modulo_table_list_title\">FILTROS DE BUSQUEDA</td></tr>";
    $this->salida .= "         <tr><td class=\"modulo_list_claro\" width=\"60%\">";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "	  	   <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">GRUPO:</td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomGrupo\" value=\"$NomGrupo\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "         <input type=\"hidden\" name=\"grupo\" value=\"$grupo\" class=\"input-text\">";
		$this->salida .= "	  	   </tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">CLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomClase\"  value=\"$NomClase\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "         <input type=\"hidden\" name=\"clasePr\" value=\"$clasePr\" class=\"input-text\" >";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">SUBCLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomSubClase\"  value=\"$NomSubClase\" size=\"30\" class=\"input-text\" readonly>";
		$this->salida .= "         <input type=\"hidden\" name=\"subclase\" value=\"$subclase\" class=\"input-text\" >";
		$ruta='app_modules/InvBodegas/ventanaClasificacion.php';
		$this->salida .= "         <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\"></td>";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     </table><BR><BR>";
    $this->salida .= "		     </td>";
    $this->salida .= "		     <td valign=\"top\" class=\"modulo_list_claro\" width=\"40%\"> ";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">CODIGO</td><td><input type=\"text\" class=\"input-text\" name=\"codigoProd\" value=\"$codigoProd\"></td></tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">DESCRIPCION</td><td><input type=\"text\" class=\"input-text\" name=\"descripcion\" value=\"$descripcion\"></td></tr>";
		$this->salida .= "		     </table><BR>";
		$this->salida .= "         </td></tr>";
		$this->salida .= "         <tr><td colspan=\"2\" align=\"center\" class=\"modulo_list_claro\"><input type=\"submit\" class=\"input-submit\" value=\"FILTRAR\" name=\"filtrar\"></td></tr>";
    $this->salida .= "		     </table><BR>";
		$this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "       <tr><td>&nbsp&nbsp;</td></tr>";
		$this->salida .= "       </td></tr>";
		$this->salida .= "			 </table>";
    }
		$this->salida .= "		<input type=\"hidden\" name=\"TomaFisica\" value=\"$TomaFisica\" >";
		$this->salida .= "		<input type=\"hidden\" name=\"Fecha\" value=\"$Fecha\" >";
		$this->salida .= "		<input type=\"hidden\" name=\"bandera\" value=\"$bandera\" >";
		if($TomaFisica){
		  $TotalProductos=$this->ConsultaProductosTomaFisica($TomaFisica,$bandera,$grupo,$clasePr,$subclase,$codigoProd,$descripcion);
		}
		$this->salida .= "	  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "	  <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	  </td><tr>";
		$this->salida .= "	  </table>";
		if($TotalProductos){
			$this->salida .= "	  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "    <tr>";
			$this->salida .= "			<td class=\"modulo_table_list_title\">CODIGO</td>\n";
			$this->salida .= "			<td width=\"100%\" class=\"modulo_table_list_title\">DESCRIPCION</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">CANT. SISTEMA</td>\n";
			if($bandera!=1){
			$this->salida .= "			<td class=\"modulo_table_list_title\">CANTIDAD</td>\n";
			}else{
			$this->salida .= "			<td class=\"modulo_table_list_title\">CANT. TOMA</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">DIFERENCIA CANT.</td>\n";
			}
			$this->salida .= "		 </tr>\n";
			$y=0;
			for($i=0;$i<sizeof($TotalProductos);$i++){
			  $TotalProductos[$i]['codigo_producto'];
				if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "	 <tr class=\"$estilo\">\n";
				$Pr=$TotalProductos[$i]['codigo_producto'];
				$this->salida .= "	 <td>".$TotalProductos[$i]['codigo_producto']."</td>";
				$DescripProd=substr($TotalProductos[$i]['desprod'],0,20);
				$this->salida .= "	 <td width=\"100%\">$DescripProd</td>";
				$this->salida .= "	 <td>".$TotalProductos[$i]['cantidad_sistema']."</td>";
				if($bandera!=1){
				  $valorDefecto=$this->DefectoValorToma($TomaFisica,$Pr);
				$this->salida .= "	 <td><input type=\"text\" class=\"input-text\" name=\"CantToma[$Pr]\" value=\"".$valorDefecto['cantidad_fisica']."\" size=\"11\"></td>";
				}else{
				$this->salida .= "	 <td>".$TotalProductos[$i]['cantidad_fisica']."</td>";
				$diferenciaCanti=$TotalProductos[$i]['cantidad_fisica']-$TotalProductos[$i]['cantidad_sistema'];
				if($diferenciaCanti<0){
          $this->salida .= "	 <td class=\"label_error\">$diferenciaCanti</td>";
				}else{
				  $this->salida .= "	 <td>$diferenciaCanti</td>";
				}
				}
				$this->salida .= "	 </tr>\n";
				$y++;
			}
			$this->salida .= "       </table><br>";
			$this->salida .= "       <table class=\"normal_10N\"  border=\"0\" width=\"90%\" align=\"center\" >";
			$this->salida .= "        <tr><td align=\"center\">";
			$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\">";
			if($bandera!=1){
			$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\">";
			$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"imprimir\" value=\"IMPRIMIR\">";
			}
			$this->salida .= "        </td></tr>";
			$this->salida .= "       </table>";
			$bandera1=5;
			$this->salida .=$this->RetornarBarra($bandera1,'');
		}else{
      $this->salida .= "       <table class=\"normal_10N\"  border=\"0\" width=\"90%\" align=\"center\" >";
		  $this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO SE PRODUCTOS SELECCIONDOS EN LA MUESTRA</td></tr>";
      $this->salida .= "        <tr><td>&nbsp;</td></tr>";
			$this->salida .= "        <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></tr></td>";
      $this->salida .= "       </table>";
		}
		$this->salida .= "       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

	function ListadoTomasFisicas($bandera){
	if($bandera!=1){
    $titulo='LISTADO DE TOMAS FISICAS';
	}else{
    $titulo='DIFERENCIAS DE TOMAS FISICAS';
	}
    $this->salida .= ThemeAbrirTabla($titulo);
		$action=ModuloGetURL('app','InvBodegas','user','LlamaMenuInventarios2');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "		<input type=\"hidden\" name=\"TomaFisica\" value=\"$TomaFisica\" >";
		$TotalTomas=$this->ConsultaTotalTomas();
		if($TotalTomas){
			$this->salida .= "	  <BR><table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "    <tr>";
			$this->salida .= "			<td class=\"modulo_table_list_title\">CODIGO TOMA</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">FECHA</td>\n";
      $this->salida .= "			<td class=\"modulo_table_list_title\">&nbsp;</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">&nbsp;</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">&nbsp;</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">&nbsp;</td>\n";
			$this->salida .= "		 </tr>\n";
			$y=0;
			for($i=0;$i<sizeof($TotalTomas);$i++){
				if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "	 <tr class=\"$estilo\">\n";
				$this->salida .= "	 <td>".$TotalTomas[$i]['toma_fisica_id']."</td>";
				$this->salida .= "	 <td>".$TotalTomas[$i]['fecha_registro']."</td>";
				$actionElim=ModuloGetURL('app','InvBodegas','user','EliminarToma',array("TomaId"=>$TotalTomas[$i]['toma_fisica_id'],"Fecha"=>$TotalTomas[$i]['fecha_registro'],"bandera"=>$bandera));
				$this->salida .= "	 <td><a href=\"$actionElim\" class=\"link\"><b>Eliminar</b></a></td>";
				$actionVer=ModuloGetURL('app','InvBodegas','user','VerDetalleToma',array("TomaFisica"=>$TotalTomas[$i]['toma_fisica_id'],"Fecha"=>$TotalTomas[$i]['fecha_registro'],"bandera"=>0));
				$this->salida .= "	 <td><a href=\"$actionVer\" class=\"link\"><b>Guardar Toma</b></a></td>";
				$actionVer1=ModuloGetURL('app','InvBodegas','user','VerDetalleToma',array("TomaFisica"=>$TotalTomas[$i]['toma_fisica_id'],"Fecha"=>$TotalTomas[$i]['fecha_registro'],"bandera"=>1));
				$this->salida .= "	 <td><a href=\"$actionVer1\" class=\"link\"><b>Toma Fisica v/s Sistema</b></a></td>";
				$actualSistema=ModuloGetURL('app','InvBodegas','user','ActualizarSistema',array("BodegaId"=>$BodegaId,"NombreBodega"=>$NombreBodega,"TomaFisica"=>$TotalTomas[$i]['toma_fisica_id'],"Fecha"=>$TotalTomas[$i]['fecha_registro']));
				$this->salida .= "	 <td><a href=\"$actualSistema\" class=\"link\"><b>Actualizar Sistema</b></a></td>";
				$this->salida .= "	 </tr>\n";
				$y++;
			}
			$this->salida .= "       </table><br>";
		}else{
      $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\" >";
		  $this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO SE PRODUCTOS SELECCIONDOS EN LA MUESTRA</td></tr>";
      $this->salida .= "       </table>";
		}
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\" >";
		$this->salida .= "        <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "       </table>";
		$this->salida .= "       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;

	}

/**
* Function que muestra al menu con la opciones que puede seleccionar para trabajar
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa en la que se esta trabajando
* @param string centro de utilidad en el que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string bodega en la que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
*/

	function MenuInventarios6(){

    $this->salida .= ThemeAbrirTabla('MENU SOLICITUDES MEDICAMENTOS');
		$action=ModuloGetURL('app','InvBodegas','user','MenuInventarios');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
		$action1=ModuloGetURL('app','InvBodegas','user','LlamaTemperaturasEquipos');
		$action2=ModuloGetURL('app','InvBodegas','user','LlamaDatosRegTemperaturas');
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action1\" class=\"link\"><b>REGISTRO DE VALORES</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_oscuro\" align=\"center\"><a href=\"$action2\" class=\"link\"><b>CONSULTA</b></a></td></tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		<table width=\"40%\" align=\"center\" class='normal_10N'>";
		$this->salida .= "    <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "		</table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	 function TemperaturasEquipos($equipo,$Fecha,$Hora,$Minutos,$bandera){

		$this->salida .= ThemeAbrirTabla('REGISTRO DE TEMPERATURAS');
		$action=ModuloGetURL('app','InvBodegas','user','InsertarTemperaturaEquipo');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		if($bandera==1){
      $this->salida .= "		<table class=\"normal_10\"  width=\"40%\" border=\"0\" align=\"center\">";
			$this->salida .= "    <tr><td width=\"100%\">";
			$this->salida .= "    <fieldset><legend class=\"field\">DATOS EQUIPO</legend>";
			$this->salida .= "    <table class=\"normal_10\"  cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "	  <tr><td align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "	  </td><tr>";
      $this->salida .= "		  <tr height=\"20\"><td class=\"".$this->SetStyle("equipo")."\">NOMBRE DEL EQUIPO:</td><td><select name=\"equipo\"  class=\"select\">";
			$TotalEquipos=$this->TipoEquiposTemperaturas();
			$this->Mostrar($TotalEquipos,'False',$equipo);
			$this->salida .= "      </select></td>";
			$this->salida .= "		   <tr>";
			$this->salida .= "		  <tr><td></td></tr>";
			$this->salida .= "		  <tr>";
			$this->salida .= "	  	<td colspan=\"4\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"insertar\" value=\"BUSCAR\"></td>";
			$this->salida .= "		  </tr>";
			$this->salida .= "		<input type=\"hidden\" name=\"bandera\" value=\"$bandera\" >";
			$this->salida .= "		 </table>";
			$this->salida .= "		 </fieldset></td></tr>";
			$this->salida .= "     </table><BR>";
		}else{
		  $this->salida .= "	<table class=\"normal_10\"  width=\"30%\" border=\"0\" align=\"center\">";
			$this->salida .= "   <tr><td width=\"100%\">";
			$this->salida .= "   <fieldset><legend class=\"field\">DATOS EQUIPO</legend>";
			$this->salida .= "   <table class=\"normal_10\"  ellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "		<tr><td></td></tr>";
      $this->salida .= "		<tr><td class=\"modulo_table_list_title\">NOMBRE EQUIPO</td></tr>";
			$NomEquipo=$this->NombreEquipo($equipo);
			$this->salida .= "		<tr><td align=\"center\" class=\"modulo_list_claro\">".$NomEquipo['descripcion']."</td></tr>";
			$this->salida .= "		<tr><td></td></tr>";
			$this->salida .= "		</table>";
			$this->salida .= "	  </fieldset></td></tr>";
			$this->salida .= "   </table><BR>";
			$this->salida .= "		<table class=\"normal_10\"  width=\"65%\" border=\"0\" align=\"center\">";
			$this->salida .= "    <tr><td width=\"100%\">";
			$this->salida .= "    <fieldset><legend class=\"field\">DATOS REGISTRO</legend>";
			$this->salida .= "    <table class=\"normal_10\"  cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "	  <tr><td align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "	  </td><tr>";
			$this->salida .= "		<input type=\"hidden\" name=\"equipo\" value=\"$equipo\" >";
			$this->salida .= "	  <td width=\"10%\" width=\"35%\" class=\"".$this->SetStyle("Fecha")."\">FECHA :</td>";
			$this->salida .= "	  <td><input type=\"text\" name=\"Fecha\" value=\"$Fecha\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">&nbsp&nbsp&nbsp;";
			$this->salida .= "	  ".ReturnOpenCalendario('forma','Fecha','/')."</td>";
			$this->salida .= "    <td class=\"".$this->SetStyle("Hora")."\" width=\"10%\">HORA (H:m)</td><td><input size=\"2\" name=\"Hora\" value=\"$Hora\" class=\"input-text\"><b> : </b>";
			$this->salida .= "    <input size=\"2\" name=\"Minutos\" value=\"$Minutos\" class=\"input-text\"></td>";
			$this->salida .= "		</tr>";
			$this->salida .= "		<tr>";
			$medidas=$this->SelleccionMedidasEquipos($equipo);
			for($j=0;$j<sizeof($medidas);$j++){
			  $codigoMed=$medidas[$j]['codigo_medida'];
			  $vector='';
				for($z=($medidas[$j]['valor_desde']-5);$z<=($medidas[$j]['valor_hasta']+5);$z++){
				  $vector[$z]=$z;
				}
        $this->salida .= "		  <tr height=\"20\"><td class=\"".$this->SetStyle("")."\">".$medidas[$j]['descripcion']."</td><td><select name=\"medida[$j][$codigoMed]\"  class=\"select\">";
				$this->Mostrar($vector,'False','');
				$this->salida .= "      </select></td>";
			}
			$this->salida .= "		  <tr>";
			$this->salida .= "	  	<td colspan=\"4\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"insertar\" value=\"INSERTAR\"></td>";
			$this->salida .= "		  </tr>";
			$this->salida .= "		 </table>";
			$this->salida .= "		 </fieldset></td></tr>";
			$this->salida .= "     </table><BR>";
			$registros=$this->SacaRegistrosEquipoTemp($equipo,$Fecha);
		  if($registros){
				$this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "      <tr class=\"modulo_table_list_title\"><td align=\"center\" ><b>FECHA</b></td>";
				$this->salida .= "      <td align=\"center\" ><b>TEMPERATURA ºC</b></td>";
				$this->salida .= "      <td align=\"center\" ><b>HUMEDAD %</b></td>";
				$this->salida .= "      <td align=\"center\" >&nbsp;</td>";
				$this->salida .= "      <td align=\"center\" ><b>FECHA</b></td>";
				$this->salida .= "      <td align=\"center\" ><b>TEMPERATURA ºC</b></td>";
				$this->salida .= "      <td align=\"center\" ><b>HUMEDAD %</b></td>";
				$this->salida .= "      <td align=\"center\" >&nbsp;</td>";
				$this->salida .= "      </tr>";
				$y=1;
				$z=0;
				$this->salida .= "      <tr class=\"modulo_list_claro\">";
				for($i=0;$i<sizeof($registros);$i++){
				  $registroId=$registros[$i]['registro_id'];
				  $actionElim=ModuloGetURL('app','InvBodegas','user','EliminarRegistroTemperatura',array("registroId"=>$registroId,"Empresa"=>$Empresa,"NombreEmp"=>$NombreEmp,"CentroUtili"=>$CentroUtili,"NombreCU"=>$NombreCU,"BodegaId"=>$BodegaId,"NombreBodega"=>$NombreBodega,"equipo"=>$equipo,"Fecha"=>$Fecha,"Hora"=>$Hora,"Minutos"=>$Minutos,"bandera"=>$bandera));
				  if($z % 2){
						$this->salida .= "      <td class=\"$estilo\" align=\"center\">".$registros[$i]['fecha_toma']."</td>";
						$this->salida .= "      <td class=\"$estilo\" align=\"center\">".$registros[$i]['temperatura']."</td>";
						$this->salida .= "      <td class=\"$estilo\" align=\"center\">".$registros[$i]['humedad']."</td>";
						$this->salida .= "      <td><a href=\"$actionElim\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
						$this->salida .= "      </tr>";
						$y++;
						if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
						$this->salida .= "      <tr class=\"$estilo\">";
					}else{
            $this->salida .= "      <td class=\"$estilo\" align=\"center\">".$registros[$i]['fecha_toma']."</td>";
						$this->salida .= "      <td class=\"$estilo\" align=\"center\">".$registros[$i]['temperatura']."</td>";
						$this->salida .= "      <td class=\"$estilo\" align=\"center\">".$registros[$i]['humedad']."</td>";
						$this->salida .= "      <td><a href=\"$actionElim\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
					}
					$z++;
				}
				if($z % 2){
					$this->salida .= "      <td align=\"center\">&nbsp;</td>";
					$this->salida .= "      <td align=\"center\">&nbsp;</td>";
					$this->salida .= "      <td align=\"center\">&nbsp;</td>";
					$this->salida .= "      <td align=\"center\">&nbsp;</td>";
					$this->salida .= "      </tr>";
			  }
				$this->salida .= "		</table><BR>";
			}
		}
		$this->salida .= "    <table class=\"normal_10N\"  border=\"0\" width=\"30%\" align=\"center\" >";
		$this->salida .= "    <tr><td width=\"50%\" align=\"center\">";
		$this->salida .= "    <input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\">";
		$this->salida .= "    </td>";
		$this->salida .= "    </form>";
		if(sizeof($registros)>1){
		  $cadena=explode('-',$Fecha);
			$dia=$cadena[0];
			$mes=$cadena[1];
			$ano=$cadena[2];
		  $actionUn=ModuloGetURL('app','InvBodegas','user','VerGraficaTemperaturaEquipo',array("Empresa"=>$Empresa,"NombreEmp"=>$NombreEmp,"CentroUtili"=>$CentroUtili,"NombreCU"=>$NombreCU,"BodegaId"=>$BodegaId,"NombreBodega"=>$NombreBodega,"equipo"=>$equipo,"ano"=>$ano,"mes"=>$mes,"centinela"=>1));
			$this->salida .= "    <td width=\"50%\">";
		  $this->salida .= "    <form name=\"forma\" action=\"$actionUn\" method=\"post\">";
		  $this->salida .= "    <input class=\"input-submit\" type=\"submit\" name=\"verGrafica\" value=\"VER GRAFICA\">";
			$this->salida .= "    </form>";
			$this->salida .= "    </td>";

		}
		$this->salida .= "    </tr>";
		$this->salida .= "    </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

	function consultaRegistrosTemperaturas($equipo,$ano,$mes){

		$this->salida .= ThemeAbrirTabla('REPORTE REGISTRO DE TEMPERATURAS');
		$this->salida .= "<br>";
		$action=ModuloGetURL('app','InvBodegas','user','VerGraficaTemperaturaEquipo');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "		<input type=\"hidden\" name=\"equipo\" value=\"$equipo\" >";
		$this->salida .= "		<input type=\"hidden\" name=\"ano\" value=\"$ano\" >";
		$this->salida .= "		<input type=\"hidden\" name=\"mes\" value=\"$mes\" >";
    $this->salida .= "	<table class=\"normal_10\" width=\"30%\" border=\"0\" align=\"center\">";
		$this->salida .= "   <tr><td width=\"100%\">";
		$this->salida .= "   <fieldset><legend class=\"field\">DATOS EQUIPO</legend>";
		$this->salida .= "   <table class=\"normal_10\"  cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "		<tr><td></td></tr>";
		$this->salida .= "		<tr><td class=\"modulo_table_list_title\">NOMBRE EQUIPO</td></tr>";
		$NomEquipo=$this->NombreEquipo($equipo);
		$this->salida .= "		<tr><td align=\"center\" class=\"modulo_list_claro\">".$NomEquipo['descripcion']."</td></tr>";
		$this->salida .= "		<tr><td></td></tr>";
		$this->salida .= "		</table>";
		$this->salida .= "	  </fieldset></td></tr>";
		$this->salida .= "   </table><BR>";
		$Fecha='01'.'-'.$mes.'-'.$ano;
		$registros=$this->SacaRegistrosEquipoTemp($equipo,$Fecha);
		if($registros){
			$this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr class=\"modulo_table_list_title\"><td align=\"center\" ><b>FECHA</b></td>";
			$this->salida .= "      <td align=\"center\" ><b>TEMPERATURA ºC</b></td>";
			$this->salida .= "      <td align=\"center\" ><b>HUMEDAD %</b></td>";
			$this->salida .= "      <td align=\"center\" ><b>FECHA</b></td>";
			$this->salida .= "      <td align=\"center\" ><b>TEMPERATURA ºC</b></td>";
			$this->salida .= "      <td align=\"center\" ><b>HUMEDAD %</b></td>";
			$this->salida .= "      </tr>";
			$y=1;
			$z=0;
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
			for($i=0;$i<sizeof($registros);$i++){
				if($z % 2){
          $this->salida .= "      <td align=\"center\">".$registros[$i]['fecha_toma']."</td>";
					$this->salida .= "      <td align=\"center\">".$registros[$i]['temperatura']."</td>";
					$this->salida .= "      <td align=\"center\">".$registros[$i]['humedad']."</td>";
					$this->salida .= "      </tr>";
					$y++;
					if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					$this->salida .= "      <tr class=\"$estilo\">";
				}else{
          $this->salida .= "      <td align=\"center\">".$registros[$i]['fecha_toma']."</td>";
					$this->salida .= "      <td align=\"center\">".$registros[$i]['temperatura']."</td>";
					$this->salida .= "      <td align=\"center\">".$registros[$i]['humedad']."</td>";
				}
				$z++;
			}
			if($z % 2){
        $this->salida .= "      <td align=\"center\">&nbsp;</td>";
				$this->salida .= "      <td align=\"center\">&nbsp;</td>";
				$this->salida .= "      <td align=\"center\">&nbsp;</td>";
			}
			$this->salida .= "		</table><BR>";
		}
		$this->salida .= "    <table class=\"normal_10N\"  border=\"0\" width=\"90%\" align=\"center\" >";
		$this->salida .= "    <tr><td align=\"center\">";
		if(sizeof($registros)>1){$this->salida .= "    <input class=\"input-submit\" type=\"submit\" name=\"verGrafica\" value=\"VER GRAFICA\">";}
		$this->salida .= "    <input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "    </table><BR>";
		$this->salida .= "    </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function DatosRegTemperaturas($equipo,$ano,$mes){

		$this->salida .= ThemeAbrirTabla('GRAFICA DE MEDIDAS');
		$action=ModuloGetURL('app','InvBodegas','user','DatosGraficarMedidas');
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "		<table class=\"normal_10\"  width=\"40%\" border=\"0\" align=\"center\">";
		$this->salida .= "    <tr><td width=\"100%\">";
		$this->salida .= "    <fieldset><legend class=\"field\">DATOS CONSULTA</legend>";
		$this->salida .= "    <table class=\"normal_10\"  cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "	  <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	  </td><tr>";
		$this->salida .= "		  <tr height=\"20\"><td class=\"".$this->SetStyle("equipo")."\">NOMBRE DEL EQUIPO:</td><td><select name=\"equipo\"  class=\"select\">";
		$TotalEquipos=$this->TipoEquiposTemperaturas();
		$this->Mostrar($TotalEquipos,'False',$equipo);
		$this->salida .= "      </select></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "			<tr><td class=\"".$this->SetStyle("ano")."\">AÑO</td><td><select name=\"ano\" class=\"select\">";
		if(empty($ano)){$ano=date("Y");}
		if(empty($mes)){$mes=date("m");}
		$this->AnosAgenda(True,$ano);
		$this->salida .= "</select></td></tr>";
		$this->salida .= "<tr><td class=\"".$this->SetStyle("mes")."\">MES</td><td><select name=\"mes\" class=\"select\">";
		$this->MesesAgenda(True,$ano,$mes);
		$this->salida .= "          </select></td></tr>";
		$this->salida .= "		  <tr>";
		$this->salida .= "	  	<td colspan=\"4\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"insertar\" value=\"VER REPORTE\"></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		 </table>";
		$this->salida .= "		 </fieldset></td></tr>";
		$this->salida .= "     </table><BR>";
		$this->salida .= "    <table class=\"normal_10N\"  border=\"0\" width=\"90%\" align=\"center\" >";
		$this->salida .= "     <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "    </table>";
		$this->salida .= "    </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function AnosAgenda($Seleccionado='False',$ano){

		$anoActual=date("Y");
		$anoActual1=$anoActual;
		$l=5;
    for($i=0;$i<=10;$i++){
      $vars[$i]=$anoActual-$l;
      $anoActual+=1;
			//$vars[$i]=$anoActual1;
			//$anoActual1=$anoActual1+1;
		}
		switch($Seleccionado){
			case 'False':{
				foreach($vars as $value=>$titulo){
          if($titulo==$ano){
					  $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$titulo\">$titulo</option>";
				  }
				}
				break;
		  }case 'True':{
			  foreach($vars as $value=>$titulo){
					if($titulo==$ano){
				    $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
				  }else{
				    $this->salida .=" <option value=\"$titulo\">$titulo</option>";
					}
				}
				break;
		  }
	  }
	}

	function MesesAgenda($Seleccionado='False',$Año,$Defecto){
		$anoActual=date("Y");
		$vars[1]='ENERO';
    $vars[2]='FEBRERO';
		$vars[3]='MARZO';
		$vars[4]='ABRIL';
		$vars[5]='MAYO';
		$vars[6]='JUNIO';
		$vars[7]='JULIO';
		$vars[8]='AGOSTO';
		$vars[9]='SEPTIEMBRE';
		$vars[10]='OCTUBRE';
		$vars[11]='NOVIEMBRE';
		$vars[12]='DICIEMBRE';
		$mesActual=date("m");
		switch($Seleccionado){
			case 'False':{
			  if($anoActual==$Año){
			    foreach($vars as $value=>$titulo){
						if($value==$Defecto){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}else{
							$this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				}else{
          foreach($vars as $value=>$titulo){
						if($value==$Defecto){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}else{
									$this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				}
				break;
			}
			case 'True':{
			  if($anoActual==$Año){
				  foreach($vars as $value=>$titulo){
						if($value==$Defecto){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}else{
							$this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				}else{
          foreach($vars as $value=>$titulo){
						if($value==$Defecto){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}else{
							$this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				}
				break;
			}
		}
	}

	function GraficaMedidas($equipo,$ano,$mes,$array1T,$array2T,$array1H,$array2H,$centinela){

	  IncludeLib("jpgraph/Temperatura_Y_Humedad");
		$this->salida .= ThemeAbrirTabla('HOJA DE CONTROL');
		$this->salida .= "<br>";
		if($centinela!=1){
		  $action=ModuloGetURL('app','InvBodegas','user','DatosGraficarMedidas',array("equipo"=>$equipo,"mes"=>$mes,"ano"=>$ano));
		}else{
      $action=ModuloGetURL('app','InvBodegas','user','InsertarTemperaturaEquipo',array("equipo"=>$equipo,"Fecha"=>date("d/m/Y"),"Hora"=>date("H"),"Minutos"=>date("i"),"insertar"=>1,"bandera"=>1));
		}
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr><td class=\"modulo_table_list_title\" align=\"center\" ><b>EQUIPO</b></td>";
		$this->salida .= "      <td class=\"modulo_table_list_title\" align=\"center\" ><b>FECHA</b></td></tr>";
		$Nomequip=$this->NombreEquipo($equipo);
		$this->salida .= "      <tr><td class=\"modulo_list_claro\" align=\"center\" ><b>".$Nomequip['descripcion']."</b></td>";
    $mes=str_pad($mes,2,0, STR_PAD_LEFT);
		$this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\" ><b>$ano - $mes</b></td></tr>";
    $this->salida .= "		</table><BR><BR>";
		$this->salida .= "		<input type=\"hidden\" name=\"equipo\" value=\"$equipo\" >";
		$this->salida .= "		<input type=\"hidden\" name=\"Fecha\" value=\"$Fecha\" >";
		$this->salida .= "    <table class=\"normal_10\"  border=\"0\" width=\"60%\" align=\"center\">";
		if(sizeof($array1T)>1){
		$this->salida .= "    <tr><td>";
		$RutalImg=GraficarTemperatura($array1T,$array2T,1);
		$this->salida .= "    <img border=\"0\" src=$RutalImg>";
    $this->salida .= "    </td></tr>";
		}else{
      $indicador=1;
		}
		$this->salida .= "    <tr><td><BR></td></tr>";
		if(sizeof($array1H)>1){
		$this->salida .= "    <tr><td>";
		$RutalImgH=GraficarTemperatura($array1H,$array2H,2);
		$this->salida .= "    <img border=\"0\" src=$RutalImgH>";
    $this->salida .= "    </td></tr>";
		}else{
      $indicador1=1;
		}
    $this->salida .= "    </table>";
		$this->salida .= "    <table class=\"normal_10N\"  border=\"0\" width=\"90%\" align=\"center\" >";
		if($indicador==1 && $indicador1==1){
      $this->salida .= "    <tr><td class=\"label_error\" align=\"center\">NO HAY DATOS SUFICIENTES PARA REALIZAR LA GRAFICA</td></tr>";
		}
		$this->salida .= "    <tr><td align=\"center\"><BR>";
		$this->salida .= "    <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\">";
		$this->salida .= "    <input class=\"input-submit\" type=\"submit\" name=\"imprimir\" value=\"IMPRIMIR\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "    </table><BR>";
    $this->salida .= "    </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
* La funcion que visualiza las solicitudes de devolucion a partir de los parametros de busqueda
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa en la que se esta trabajando
* @param string centro de utilidad en el que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string bodega en la que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
* @param string numero de busqueda elegida por el usuario del sistema
* @param array documentos de devolucion de solicitud pendientes
* @param date fecha inicial como parametro de busqueda
* @param date fecha final como parametro de busqueda
*/
	function BusquedaDocumentosBodega($Busqueda,$documentos,$FechaInicial,$FechaFinal,$conceptoInv,$numDocumento){

		$this->salida .= themeAbrirTabla('BUSQUEDA DOCUMENTOS BODEGA');
		$accion=ModuloGetURL('app','InvBodegas','user','selecBusquedaDocumento1');
		$this->salida .= "       <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "      <table class=\"normal_10\" border=\"0\" width=\"80%\" align=\"center\">";
    $this->salida .= "			  <table class=\"normal_10\"width=\"70%\" border=\"0\" align=\"center\">";
		$this->salida .= "          <tr><td width=\"100%\">";
		$this->salida .= "          <fieldset><legend class=\"field\">PARAMETROS DE BUSQUEDA DEL DOCUMENTO DE BODEGA</legend>";
		$this->salida .= "          <table class=\"normal_10\" cellspacing=\"2\" cellpadding=\"3\" border=\"0\" width=\"100%\" align=\"center\">";
    $this->salida .= "	      	  <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	      		</td><tr>";
		$this->salida .= "		    <tr>";
		$this->salida .= "	  	  <td class=\"".$this->SetStyle("FechaInicial")."\">FECHA INICIAL: </td>";
		$this->salida .= "	  	  <td><input size=\"10\" maxlength=\"10\" type=\"text\" name=\"FechaInicial\" value=\"$FechaInicial\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
		$this->salida .= "	  	  ".ReturnOpenCalendario('forma','FechaInicial','/')."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "		    <tr>";
		$this->salida .= "	  	  <td class=\"".$this->SetStyle("FechaFinal")."\">FECHA FINAL: </td>";
		$this->salida .= "	  	  <td><input size=\"10\" maxlength=\"10\" type=\"text\" name=\"FechaFinal\" value=\"$FechaFinal\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
		$this->salida .= "	  	  ".ReturnOpenCalendario('forma','FechaFinal','/')."</td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "				<tr><td class=\"".$this->SetStyle("numDocumento")."\">NUMERO DOCUMENTO</td><td><input type=\"text\" class=\"input-text\" name=\"numDocumento\" value=\"$numDocumento\" size=\"15\"></td></tr>";
		$this->salida .= "		    <tr height=\"20\"><td class=\"".$this->SetStyle("conceptoInv")."\">CONCEPTO INVENTARIO:</td><td><select name=\"conceptoInv\"  class=\"select\">";
		$ConceptosInv=$this->ConceptosInventarios();
		$this->MostrarConceptosInv($ConceptosInv,'False',$conceptoInv);
		$this->salida .= "        </select></td></tr>";

    $this->salida .= "          <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"MENU\">";
    $this->salida .= "          <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"FILTRAR\">";
		$this->salida .= "          </td></tr>";
		$this->salida .= "			    </table>";
		$this->salida .= "		      </fieldset></td></tr>";
    $this->salida .= "          </table><BR>";
    $documentos=$this->ConsultaDocumentosBodega($numDocumento,$FechaInicial,$FechaFinal,$conceptoInv);
		if($documentos){
		  $y=0;
		  $this->salida .= "     <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "     <tr>";
		  $this->salida .= "			<td class=\"modulo_table_list_title\">DOCUMENTO</td>\n";
      $this->salida .= "			<td class=\"modulo_table_list_title\">PF</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">USUARIO</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">FECHA</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">SOLICITUD</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">CONCEPTO</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">COSTO</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">DETALLE</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">IMPRIMIR</td>\n";
			$this->salida .= "			<td class=\"modulo_table_list_title\">IMPRIMIR</td>\n";
      $this->salida .= "	    </tr>\n";
			$rep= new GetReports();
		  for($i=0;$i<sizeof($documentos);$i++){
        
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			  $this->salida .= "	 <tr class=\"$estilo\">\n";
			  $this->salida .= "	 <td>".$documentos[$i]['numeracion']."</td>";
				$this->salida .= "	 <td>".$documentos[$i]['prefijo']."</td>";
				$this->salida .= "	 <td>".$documentos[$i]['usuario']."</td>";
				$this->salida .= "	 <td width=\"10%\">".$documentos[$i]['fecha']."</td>";
				$solicitud=$this->HallarSolicitudDocumento($documentos[$i]['numeracion'],$documentos[$i]['bodegas_doc_id']);
				$this->salida .= "	 <td width=\"10%\">".$solicitud['solicitud_id']."</td>";
				$NombreconceptoInv=$this->NomConceptoDocumento($documentos[$i]['bodegas_doc_id']);
		    $this->salida .= "	 <td width=\"50%\">".$NombreconceptoInv['descripcion']."</td>";
				$this->salida .= "	 <td>".($documentos[$i]['total_costo']+$documentos[$i]['otros_gastos'])."</td>";
				$accionDet=ModuloGetURL('app','InvBodegas','user','VerDetalleDocumentoBodega',
				array("Documento"=>$documentos[$i]['numeracion'],"concepto"=>$documentos[$i]['bodegas_doc_id'],
				"fecha"=>$documentos[$i]['fecha'],"solicitud"=>$solicitud['solicitud_id'],
				"nomconcepto"=>$NombreconceptoInv['descripcion'],"costo"=>$documentos[$i]['total_costo'],
				"conteo"=>$this->conteo,"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso'],
				"centroutiliTrans"=>$documentos[$i]['centro_utilidad_transferencia'],"BodegaTrans"=>$documentos[$i]['bodega_destino_transferencia'],
        "BusquedaBus"=>$Busqueda,"FechaInicialBus"=>$FechaInicial,"FechaFinalBus"=>$FechaFinal,"conceptoInvBus"=>$conceptoInv,"numDocumentoBus"=>$numDocumento,"usuario"=>$documentos[$i]['usuario']));
				$this->salida .= "	 <td align=\"center\" width=\"10%\"><a href=\"$accionDet\" class=\"link\"><img title=\"Consultar\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a></td>";
				$accionD=ModuloGetURL('app','InvBodegas','user','LlamaImprimirDocumentoBodega',array(
				"fecha"=>$documentos[$i]['fecha'],"Documento"=>$documentos[$i]['numeracion'],"concepto"=>$documentos[$i]['bodegas_doc_id'],
				"solicitud_id"=>$solicitud['solicitud_id'],"nomconcepto"=>$NombreconceptoInv['descripcion'],"costo"=>$documentos[$i]['total_costo'],
        "centroutiliTrans"=>$documentos[$i]['centro_utilidad_transferencia'],"BodegaTrans"=>$documentos[$i]['bodega_destino_transferencia'],
				"Busqueda"=>$Busqueda,"documentos"=>$documentos,"FechaInicial"=>$FechaInicial,"FechaFinal"=>$FechaFinal,"prefijo"=>$documentos[$i]['prefijo']));
				$this->salida .= "	 <td align=\"center\" width=\"10%\"><a href=\"$accionD\" class=\"link\"><img title=\"Imprimir Formato POS\" border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\"></a></td>";
				
				$mostrar=$rep->GetJavaReport('app','InvBodegas','ConsultaDocumentosBodega_html',
        array("numeracion"=>$documentos[$i]['numeracion'],"bodegas_doc_id"=>$documentos[$i]['bodegas_doc_id'],
        'centroutiliTrans'=>$documentos[$i]['centro_utilidad_transferencia'],'BodegaTrans'=>$documentos[$i]['bodega_destino_transferencia'],'solicitud'=>$solicitud['solicitud_id'],
        'fecha'=>$documentos[$i]['fecha'],"nomconcepto"=>$NombreconceptoInv['descripcion'],"costo"=>$documentos[$i]['total_costo']),
        array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
				$nombre_funcion=$rep->GetJavaFunction();
				$this->salida .=$mostrar;				
				$this->salida .= "	 <td align=\"center\" width=\"10%\"><a href=\"javascript:$nombre_funcion\" class=\"link\"><img title=\"Imprimir\" border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\"></a></td>";
				$this->salida .= "	 </tr>\n";
				$y++;
      }
			$this->salida .= "   </table><BR>";
			$bandera=3;
		  $this->salida .=$this->RetornarBarra($bandera);
		}else{
      $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		  $this->salida .= "        <tr><td align=\"center\" class=\"label_error\">DATOS SIN ENCONTRAR</td></tr>";
      $this->salida .= "       </table>";
		}
    $this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;

	}

	function DetDocumentoBodegaLotes($Documento,$fechaDocumento,$conceptoInv,
	$nombreProducto,$codigo,$unidadProducto,$ExisProducto,$costoProducto,$precioProducto,
	$cantSolicitada,$costoUnit,$tipoIdProveedor,$ProveedorId,
	$proveedor,$numFactura,$iva,$valorFletes,$otrosGastos,$observaciones){
    $ProductosDocumento=$this->ConsultaProductosDocumento($Documento,$conceptoInv);
    if(empty($iva)){$iva='0';}
    $Empresa=$_SESSION['BODEGAS']['Empresa'];
    $CentroUtili=$_SESSION['BODEGAS']['CentroUtili'];
    $BodegaId=$_SESSION['BODEGAS']['BodegaId'];
    $RUTA = $_ROOT ."classes/classbuscador/buscador.php?";
		$this->salida.="\n<script language='javascript'>\n";
		$this->salida.="  var rem=\"\";\n";
		$this->salida.="  function abrirVentana(){\n";
		unset($_SESSION['SQL']);
		unset($_SESSION['SQLA']);
		unset($_SESSION['SQLB']);
    unset($_SESSION['SQLC']);
		unset($_SESSION['SQLD']);
		$this->salida.="    var str =\"width=450,height=250,resizable=no,status=no,scrollbars=yes\";\n";
		$this->salida.="    var url2 ='$RUTA'+'&sql='+'$Empresa'+'&sqla='+'$CentroUtili'+'&sqlb='+'$BodegaId'+'&tipo=inventarios'+'&forma=forma';\n";
		$this->salida.="    rem = window.open(url2, 'BUSCAR PRODUCTO', str)}\n";
		$this->salida.="</script>\n";
		$this->salida .= ThemeAbrirTabla('DETALLE DEL DOCUMENTO DE BODEGA');
		$this->salida.=$mostrar;
		$this->salida .="</script>\n";
    $this->salida .= "       <BR>";
		$action=ModuloGetURL('app','InvBodegas','user','DetDocumentosBodegaFechaVmto');
		$this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "		      <input type=\"hidden\" name=\"Documento\" value=\"$Documento\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"conceptoInv\" value=\"$conceptoInv\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"fechaDocumento\" value=\"$fechaDocumento\" >";
		$this->salida .= "         <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "         <tr><td width=\"100%\">";
		$this->salida .= "         <fieldset><legend class=\"field\">DOCUMENTO BODEGA</legend>";
    $this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida .= "	        <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"30%\"><label class=\"label\">CONCEPTO</td>";
    $NombreconceptoInv=$this->NomConceptoDocumento($conceptoInv);
		$this->salida .= "	         <td>".$NombreconceptoInv['descripcion']."</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"30%\"><label class=\"label\">FECHA</td>";
		$this->salida .= "	         <td>$fechaDocumento</td>";
		$this->salida .= "	        </tr>";
    $this->salida .= "			     </table>";
		$this->salida .= "		      </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
    if($NombreconceptoInv['sw_compras']==1){
      $this->salida .= "        <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
      $this->salida .= "        <tr><td width=\"100%\">";
      $this->salida .= "        <fieldset><legend class=\"field\">DATOS DEL DOCUMENTO DE COMPRAS</legend>";
      $this->salida .= "        <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
      $this->salida .= "		    <tr class=\"modulo_list_claro\">";
      $this->salida .= "	      <td width=\"20%\" class=\"".$this->SetStyle("numFactura")."\">No. FACTURA</td>";
      $this->salida .= "		    <td colspan=\"3\"><input type=\"text\" class=\"text-input\" value=\"$numFactura\" name=\"numFactura\" size=\"16\"></td>";
      $this->salida .= "		    </tr>";
      $this->salida .= "	      <tr class=\"modulo_list_claro\">";
			$this->salida .= "	      <td width=\"20%\" class=\"".$this->SetStyle("valorFletes")."\">VALOR FLETE</td>";
			$this->salida .= "	  	  <td><input type=\"text\" maxlength=\"16\" size=\"16\" name=\"valorFletes\" value=\"$valorFletes\" class=\"input-text\"></td>";
			$this->salida .= "	      <td width=\"20%\" class=\"".$this->SetStyle("otrosGastos")."\">OTROS GASTOS</td>";
			$this->salida .= "	  	  <td><input type=\"text\" maxlength=\"16\" size=\"16\" name=\"otrosGastos\" value=\"$otrosGastos\" class=\"input-text\"></td>";
			$this->salida .= "		    </tr>";
      $this->salida .= "		    <tr class=\"modulo_list_claro\">";
      $this->salida .= "	      <td width=\"20%\" class=\"".$this->SetStyle("proveedor")."\">PROVEEDOR</td>";
			$this->salida .= "		    <input type=\"hidden\" name=\"tipoIdProveedor\" value=\"$tipoIdProveedor\">";
			$this->salida .= "		    <input type=\"hidden\" name=\"ProveedorId\" value=\"$ProveedorId\">";
			$this->salida .= "		    <td colspan=\"3\">";
			$this->salida .= "		    <input type=\"text\" size=\"50\" name=\"proveedor\" value=\"$proveedor\" class=\"input-text\" READONLY>&nbsp&nbsp;";
			$this->salida .= "		    <input type=\"submit\" name=\"BuscarProveedor\" value=\"BUSCAR\" class=\"input-submit\">";
			$this->salida .= "		    </td>";
			$this->salida .= "		    </tr>";
      $this->salida .= "		    <tr class=\"modulo_list_claro\">";
      $this->salida .= "	      <td width=\"20%\" class=\"".$this->SetStyle("observaciones")."\">OBSERVACIONES</td>";
      $this->salida .= "		    <td colspan=\"3\"><textarea name=\"observaciones\" class=\"'textarea\" cols=\"60\" rows=\"3\">$observaciones</textarea></td>";
      $this->salida .= "		    </tr>";
      $TotalSuma=0;
      for($i=0;$i<sizeof($ProductosDocumento);$i++){
        if($ProductosDocumento[$i]['iva_compra']>0){
          $valorIva=($ProductosDocumento[$i]['costo_unitario'] * floor($ProductosDocumento[$i]['iva_compra'])) / 100;
        }else{
          $valorIva=0;
        }
        $costoUniIva=$ProductosDocumento[$i]['costo_unitario'] + $valorIva;
        $SumaCosto=$ProductosDocumento[$i]['cantidad']*$costoUniIva;
        $TotalSuma+=$SumaCosto;
      }
      $this->salida .= "		    <tr class=\"modulo_list_claro\">";
      $this->salida .= "	      <td width=\"20%\" class=\"".$this->SetStyle("total")."\">TOTAL</td>";
      $this->salida .= "		    <td colspan=\"3\">".($TotalSuma+$otrosGastos)."</td>";
      $this->salida .= "		    </tr>";
      $this->salida .= "			  </table>";
      $this->salida .= "		    </fieldset></td><BR>";
      $this->salida .= "       </table><BR>";
    }

		if($ProductosDocumento){
		$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
    $this->salida .= "			   <td width=\"10%\">CODIGO</td>";
		$this->salida .= "         <td>PRODUCTO</td>";
		$this->salida .= "			   <td width=\"10%\">CANTIDAD</td>";
    $this->salida .= "			   <td width=\"10%\">COSTO UNIT.</td>";
    if($NombreconceptoInv['sw_compras']==1){
      $this->salida .= "			   <td width=\"10%\">IVA</td>";
		  $this->salida .= "			   <td width=\"10%\">COSTO UNIT. + IVA</td>";
    }
    $this->salida .= "			   <td width=\"10%\">COSTO TOTAL</td>";
		$this->salida .= "			   <td width=\"5%\">&nbsp;</td>";
		$this->salida .= "			   <td width=\"5%\">&nbsp;</td>";
		$this->salida .= "        </tr>";
		$y=0;
    $SumaCosto=0;
    $TotalSuma=0;
		for($i=0;$i<sizeof($ProductosDocumento);$i++){
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "	 <tr class=\"$estilo\">\n";
			$this->salida .= "	 <td>".$ProductosDocumento[$i]['codigo_producto']."</td>";
			$this->salida .= "	 <td>".$ProductosDocumento[$i]['descripcion']."</td>";
      if($ProductosDocumento[$i]['cantidad'] % (int)($ProductosDocumento[$i]['cantidad'])){
			  $this->salida .= "	 <td>".$ProductosDocumento[$i]['cantidad']."</td>";
      }else{
        $this->salida .= "	 <td>".(int)($ProductosDocumento[$i]['cantidad'])."</td>";
      }
      if($ProductosDocumento[$i]['costo_unitario'] % (int)($ProductosDocumento[$i]['costo_unitario'])){
        $this->salida .= "	 <td>".$ProductosDocumento[$i]['costo_unitario']."</td>";
      }else{
        $this->salida .= "	 <td>".(int)($ProductosDocumento[$i]['costo_unitario'])."</td>";
      }
      $costoUniIva=$ProductosDocumento[$i]['costo_unitario'];
      if($NombreconceptoInv['sw_compras']==1){
        if($ProductosDocumento[$i]['iva_compra']>0){
          $this->salida .= "<td>".floor($ProductosDocumento[$i]['iva_compra'])." % -  ".($valorIva=$ProductosDocumento[$i]['costo_unitario'] * floor($ProductosDocumento[$i]['iva_compra']) / 100)."</td>";
        }else{
          $valorIva=0;
          $this->salida .= "<td>&nbsp;</td>";
        }
        $this->salida .= "<td>".($costoUniIva=$ProductosDocumento[$i]['costo_unitario'] + $valorIva)."</td>";
      }
      $this->salida .= "			   <td>".$SumaCosto=($ProductosDocumento[$i]['cantidad']*$costoUniIva)."</td>";
      $TotalSuma+=$SumaCosto;
			$descripcion=urlencode($ProductosDocumento[$i]['descripcion']);
			$action=ModuloGetURL('app','InvBodegas','user','LlamaEliminarPtosLotes',array("Documento"=>$Documento,"fechaDocumento"=>$fechaDocumento,"conceptoInv"=>$conceptoInv,"consecutivo"=>$ProductosDocumento[$i]['consecutivo'],
      "tipoIdProveedor"=>$tipoIdProveedor,"ProveedorId"=>$ProveedorId,"proveedor"=>$proveedor,"numFactura"=>$numFactura,"iva"=>$iva,"valorFletes"=>$valorFletes,"otrosGastos"=>$otrosGastos,"observaciones"=>$observaciones));
			$this->salida .= "		<td align=\"center\"><a title=\"Eliminar\" href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
			if($ProductosDocumento[$i]['sw_control_fecha_vencimiento']!=0){
				$action=ModuloGetURL('app','InvBodegas','user','LlamaDetallePtosLotes',array("Documento"=>$Documento,"fechaDocumento"=>$fechaDocumento,"conceptoInv"=>$conceptoInv,"codigoProducto"=>$ProductosDocumento[$i]['codigo_producto'],"descripcion"=>$descripcion,"cantidad"=>$ProductosDocumento[$i]['cantidad'],"consecutivo"=>$ProductosDocumento[$i]['consecutivo'],
        "tipoIdProveedor"=>$tipoIdProveedor,"ProveedorId"=>$ProveedorId,"proveedor"=>$proveedor,"numFactura"=>$numFactura,"iva"=>$iva,"valorFletes"=>$valorFletes,"otrosGastos"=>$otrosGastos,"observaciones"=>$observaciones));
				$this->salida .= "		<td align=\"center\"><a title=\"Ver Detalle\" href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a></td>";
			}else{
        $this->salida .= "			   <td>&nbsp;</td>";
			}
			$this->salida .= "	 </tr>\n";
			$y++;
		}
    $this->salida .= "	 <tr class=\"$estilo\">\n";
    if($NombreconceptoInv['sw_compras']==1){
      $rows=6;
    }else{
      $rows=4;
    }
    $this->salida .= "	 <td colspan=\"$rows\" class=\"label\" align=\"right\">TOTAL</td>";
		$this->salida .= "	 <td>".$TotalSuma."</td>";
    $this->salida .= "	 <td colspan=\"2\">&nbsp;</td>";
    $this->salida .= "	 </tr>\n";
    $this->salida .= "       </table><BR>";
    $this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\">";
    $this->salida .= "	     <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Guardar\" value=\"GUARDAR DOCUMENTO\"></td></tr>";
		$this->salida .= "       </table><BR>";
		}
		$this->salida .= "    <table class=\"normal_10\" border=\"0\" width=\"75%\" align=\"center\">";
		$this->salida .= "    <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	   </td><tr>";
		$this->salida .= "     <tr class=\"modulo_table_title\"><td align=\"center\">DATOS DEL PRODUCTO</td></tr>";
		$this->salida .= "     <tr><td class=\"modulo_list_claro\">";
		$this->salida .= "     <BR><table class=\"normal_10\" cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida.= "       <tr class=\"modulo_list_oscuro\">";
		$this->salida.= "       <td width=\"10%\" class=\"".$this->SetStyle("nombreProducto")."\">NOMBRE</td>";
		$this->salida.= "       <td><input type=\"text\" name=\"nombreProducto\" maxlength=\"50\" size=\"40\" class=\"input-text\" value=\"$nombreProducto\" READONLY></td>";
		$this->salida.= "       <td width=\"10%\" class=\"".$this->SetStyle("codigo")."\">CODIGO</td>";
    $this->salida.= "       <td><input type=\"text\" name=\"codigo\" maxlength=\"10\" size=\"10\" class=\"input-text\" value=\"$codigo\" READONLY></td>";
		$this->salida.= "       </tr>";
		$this->salida.= "       <tr class=\"modulo_list_oscuro\">";
		$this->salida.= "       <td width=\"10%\" class=\"".$this->SetStyle("unidadProducto")."\">UNIDAD</td>";
		$this->salida.= "       <td><input type=\"text\" name=\"unidadProducto\" maxlength=\"40\" size=\"40\" class=\"input-text\" value=\"$unidadProducto\" READONLY></td>";
		$this->salida.= "       <td width=\"10%\" class=\"".$this->SetStyle("ExisProducto")."\">EXISTENCIA</td>";
		$this->salida.= "       <td><input type=\"text\" name=\"ExisProducto\" maxlength=\"11\" size=\"11\" class=\"input-text\" value=\"$ExisProducto\" READONLY>";
		$this->salida.= "       <input type=\"hidden\" name=\"costoProducto\" maxlength=\"17\" size=\"17\" class=\"input-text\" value=\"$costoProducto\" READONLY>&nbsp&nbsp&nbsp;";
		$this->salida.= "       <input type=\"submit\" name=\"buscar\" value=\"BUSCAR\" class=\"input-submit\"></td>";
		$this->salida.= "       </tr>";
		$this->salida.= "       <input type=\"hidden\" name=\"precioProducto\" maxlength=\"17\" size=\"17\" class=\"input-text\" value=\"$precioProducto\" READONLY>";
		$this->salida.= "      <input type=\"hidden\" name=\"ExisDest\" size=\"17\" class=\"input-text\" value=\"$ExisDest\">";
		if($NombreconceptoInv['sw_compras']==1){
		  $this->salida .= "		    <input type=\"hidden\" name=\"compras\" value=\"1\">";
		  $this->salida .= "	     <tr class=\"modulo_list_oscuro\">";
			$this->salida .= "	     <td width=\"10%\" class=\"".$this->SetStyle("cantSolicitada")."\">CANTIDAD</td>";
			$this->salida .= "	  	 <td><input type=\"text\" maxlength=\"16\" size=\"16\" name=\"cantSolicitada\" value=\"$cantSolicitada\" class=\"input-text\"></td>";
			$this->salida .= "	     <td width=\"10%\" class=\"".$this->SetStyle("costoUnit")."\">COSTO UNITARIO</td>";
			$this->salida .= "	  	 <td><input type=\"text\" maxlength=\"16\" size=\"16\" name=\"costoUnit\" value=\"$costoUnit\" class=\"input-text\"></td>";
			$this->salida .= "		    </tr>";
      $this->salida .= "		    <tr class=\"modulo_list_oscuro\">";
			$this->salida .= "	      <td width=\"10%\" class=\"".$this->SetStyle("iva")."\">IVA</td>";
      $this->salida .= "		    <td colspan=\"3\"><input type=\"text\" value=\"$iva\" name=\"iva\" size=\"2\" class=\"input-submit\">&nbsp&nbsp&nbsp;<b>%</b></td>";
			$this->salida .= "		    </tr>";
		}else{
      $this->salida .= "	     <tr class=\"modulo_list_oscuro\">";
			$this->salida .= "	     <td width=\"10%\" class=\"".$this->SetStyle("cantSolicitada")."\">CANTIDAD</td>";
			$this->salida .= "	  	 <td colspan=\"3\"><input type=\"text\" maxlength=\"16\" size=\"16\" name=\"cantSolicitada\" value=\"$cantSolicitada\" class=\"input-text\"></td>";
			$this->salida .= "		    </tr>";
		}
    $this->salida .= "		    <tr>";
    $this->salida .= "        <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"INSERTAR\"></td></tr>";
    $this->salida .= "			  </table><BR>";
		$this->salida .= "		    </td></tr>";
		$this->salida .= "        </table>";
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "         <tr><td align=\"center\">";
    $this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"Regresar\" value=\"VOLVER\">";
    $this->salida .= "         </td></tr>";
    $this->salida .= "       </table><BR>";
		$this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function LotesFechaVmtoPto($Documento,$conceptoInv,$fechaDocumento,$codigo,$nombreProducto,$cantSolicitada,$consecutivo,$cantidadLote,$NoLote,$FechaVmto,
  $tipoIdProveedor,$ProveedorId,$proveedor,$numFactura,$iva,$valorFletes,$otrosGastos,$observaciones){

    $this->salida .= ThemeAbrirTabla('REGISTRO DE LAS FECHAS DE VENCIMIENTO Y LOTES DE LA CANTIDAD INSERTADA');
		$action=ModuloGetURL('app','InvBodegas','user','InsertarLotesProducto',array("tipoIdProveedor"=>$tipoIdProveedor,"ProveedorId"=>$ProveedorId,"proveedor"=>$proveedor,
    "numFactura"=>$numFactura,"iva"=>$iva,"valorFletes"=>$valorFletes,"otrosGastos"=>$otrosGastos,"observaciones"=>$observaciones));
    $this->salida .= "         <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "		      <input type=\"hidden\" name=\"Documento\" value=\"$Documento\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"conceptoInv\" value=\"$conceptoInv\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"fechaDocumento\" value=\"$fechaDocumento\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"codigo\" value=\"$codigo\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"nombreProducto\" value=\"$nombreProducto\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"cantSolicitada\" value=\"$cantSolicitada\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"consecutivo\" value=\"$consecutivo\" >";
		$this->salida .= "         <table class=\"normal_10\" border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "         <tr><td width=\"100%\">";
		$this->salida .= "         <fieldset><legend class=\"field\">DATOS DEL PRODUCTO DEL DOCUMENTO DE BODEGA</legend>";
    $this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	        <tr><td></td></tr>";
		$this->salida .= "	        <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">CONCEPTO</td>";
    $NombreconceptoInv=$this->NomConceptoDocumento($conceptoInv);
		$this->salida .= "	         <td colspan=\"3\">".$NombreconceptoInv['descripcion']."</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">FECHA</td>";
		$this->salida .= "	         <td colspan=\"3\">$fechaDocumento</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">CODIGO PRODUCTO</td>";
		$this->salida .= "	         <td colspan=\"3\">$codigo</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "	         <td width=\"20%\"><label class=\"label\">DESCRIPCION</td>";
		$this->salida .= "	         <td colspan=\"3\">$nombreProducto</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">CANTIDAD TOTAL</td>";
		$this->salida .= "	         <td colspan=\"3\">$cantSolicitada</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr><td></td></tr>";
    $this->salida .= "			     </table>";
		$this->salida .= "		      </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
    $ProductosDocumentoLotes=$this->ConsultaProductosDocumentoLotes($consecutivo,$codigo);
		if(!empty($ProductosDocumentoLotes)){
		$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
    $this->salida .= "			   <td>FECHA VENCIMIENTO</td>";
		$this->salida .= "         <td>No. LOTE</td>";
		$this->salida .= "			   <td>CANTIDAD</td>";
		$this->salida .= "			   <td>&nbsp;</td>";
		$this->salida .= "        </tr>";
		$y=0;
		for($i=0;$i<sizeof($ProductosDocumentoLotes);$i++){
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "	 <tr class=\"$estilo\">\n";
			$this->salida .= "	 <td>".$ProductosDocumentoLotes[$i]['fecha_vencimiento']."</td>";
			$this->salida .= "	 <td>".$ProductosDocumentoLotes[$i]['lote']."</td>";
			$this->salida .= "	 <td>".$ProductosDocumentoLotes[$i]['cantidad']."</td>";
			$nombreProducto=urlencode($nombreProducto);
			$actionElim=ModuloGetURL('app','InvBodegas','user','EliminarRegistroFVLote',array('Documento'=>$Documento,'conceptoInv'=>$conceptoInv,'fechaDocumento'=>$fechaDocumento,'codigo'=>$codigo,'nombreProducto'=>$nombreProducto,'cantSolicitada'=>$cantSolicitada,'consecutivo'=>$consecutivo,'FechaVmto'=>$ProductosDocumentoLotes[$i]['fecha_vencimiento'],'lote'=>$ProductosDocumentoLotes[$i]['lote'],'cantidad'=>$ProductosDocumentoLotes[$i]['cantidad'],
      "tipoIdProveedor"=>$tipoIdProveedor,"ProveedorId"=>$ProveedorId,"proveedor"=>$proveedor,
      "numFactura"=>$numFactura,"iva"=>$iva,"valorFletes"=>$valorFletes,"otrosGastos"=>$otrosGastos,"observaciones"=>$observaciones));
			$this->salida .= "	 <td><a href=\"$actionElim\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
			$this->salida .= "	 </tr>\n";
			$y++;
		}
    $this->salida .= "       </table><BR>";
		}
    $this->salida .= "<table class=\"normal_10\" border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
		$this->salida .= "<tr class=\"modulo_table_title\"><td align=\"center\">DATOS DEL PRODUCTO</td></tr>";
		$sumaCant=$this->SumaCantidadesLotes($consecutivo,$codigo);
		$cantidadRest=$cantSolicitada-$sumaCant['sumacantidadeslotes'];
		$this->salida .= "<tr class=\"modulo_table_title\"><td align=\"center\">CANTIDAD QUE FALTA POR INSERTAR ".$cantidadRest."</td></tr>";
		$this->salida .= "<tr><td class=\"modulo_list_claro\">";
		$this->salida .= "  <BR><table class=\"normal_10\" cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "   <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "   <td class=\"".$this->SetStyle("cantidadLote")."\">CANTIDAD</td><td><input type=\"text\" name=\"cantidadLote\" value=\"$cantidadLote\"></td>";
    $this->salida .= "   <td class=\"".$this->SetStyle("NoLote")."\">NUMERO LOTE</td><td><input type=\"text\" name=\"NoLote\" value=\"$NoLote\"></td>";
		$this->salida .= "	 <td class=\"".$this->SetStyle("FechaVmto")."\">FECHA VENCIMIENTO </td>";
		$this->salida .= "	 <td><input type=\"text\" name=\"FechaVmto\" value=\"$FechaVmto\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
		$this->salida .= "	 ".ReturnOpenCalendario('forma','FechaVmto','/')."</td>";
    $this->salida.= "   </tr>";
		$this->salida.= "   <tr>";
    $this->salida .= "	 <td align=\"center\" colspan=\"6\"><input class=\"input-submit\" type=\"submit\" name=\"insertar\" value=\"INSERTAR\"></td>";
    $this->salida.= "   </tr>";
		$this->salida.= "   </table><BR>";
		$this->salida .= "</td></tr>";
		$this->salida.= "</table>";
		$this->salida .="</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function DetalleConsultaPtosLotes($Documento,$fechaDocumento,$conceptoInv,$codigo,$nombreProducto,$cantSolicitada,$consecutivo,
    $tipoIdProveedor,$ProveedorId,$proveedor,$numFactura,$iva,$valorFletes,$otrosGastos,$observaciones){
    $this->salida .= ThemeAbrirTabla('DATOS DEL PRODUCTO');
		$action=ModuloGetURL('app','InvBodegas','user','LlamaDetDocumentoBodegaLotes',array("tipoIdProveedor"=>$tipoIdProveedor,"ProveedorId"=>$ProveedorId,
    "proveedor"=>$proveedor,"numFactura"=>$numFactura,"iva"=>$iva,"valorFletes"=>$valorFletes,"otrosGastos"=>$otrosGastos,"observaciones"=>$observaciones));
    $this->salida .= "         <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "		      <input type=\"hidden\" name=\"Documento\" value=\"$Documento\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"conceptoInv\" value=\"$conceptoInv\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"fechaDocumento\" value=\"$fechaDocumento\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"codigo\" value=\"$codigo\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"nombreProducto\" value=\"$nombreProducto\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"cantSolicitada\" value=\"$cantSolicitada\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"consecutivo\" value=\"$consecutivo\" >";
		$this->salida .= "         <table class=\"normal_10\" border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "         <tr><td width=\"100%\">";
		$this->salida .= "         <fieldset><legend class=\"field\">DATOS DEL PRODUCTO DEL DOCUMENTO DE BODEGA</legend>";
    $this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	        <tr><td></td></tr>";
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">No. DOCUMENTO</td>";
		$this->salida .= "	         <td colspan=\"3\">$Documento</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">CONCEPTO</td>";
    $NombreconceptoInv=$this->NomConceptoDocumento($conceptoInv);
		$this->salida .= "	         <td colspan=\"3\">".$NombreconceptoInv['descripcion']."</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">FECHA</td>";
		$this->salida .= "	         <td colspan=\"3\">$fechaDocumento</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">CODIGO PRODUCTO</td>";
		$this->salida .= "	         <td colspan=\"3\">$codigo</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "	         <td width=\"20%\"><label class=\"label\">DESCRIPCION</td>";
		$this->salida .= "	         <td colspan=\"3\">$nombreProducto</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td width=\"20%\"><label class=\"label\">CANTIDAD TOTAL</td>";
		$this->salida .= "	         <td colspan=\"3\">$cantSolicitada</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr><td></td></tr>";
    $this->salida .= "			     </table>";
		$this->salida .= "		      </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
    $ProductosDocumentoLotes=$this->ConsultaProductosDocumentoLotes($consecutivo,$codigo);
		if(!empty($ProductosDocumentoLotes)){
		$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
    $this->salida .= "			   <td>FECHA VENCIMIENTO</td>";
		$this->salida .= "         <td>No. LOTE</td>";
		$this->salida .= "			   <td>CANTIDAD</td>";
		$this->salida .= "        </tr>";
		$y=0;
		for($i=0;$i<sizeof($ProductosDocumentoLotes);$i++){
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "	 <tr class=\"$estilo\">\n";
			$this->salida .= "	 <td>".$ProductosDocumentoLotes[$i]['fecha_vencimiento']."</td>";
			$this->salida .= "	 <td>".$ProductosDocumentoLotes[$i]['lote']."</td>";
			$this->salida .= "	 <td>".$ProductosDocumentoLotes[$i]['cantidad']."</td>";
			$this->salida .= "	 </tr>\n";
			$y++;
		}
    $this->salida .= "       </table><BR>";
		}else{
      $this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\">";
      $this->salida .= "	     <tr><td align=\"center\" class=\"label_error\">No Existen Registros</td></tr>";
      $this->salida .= "	     </table>";
		}
    $this->salida .= "	     <BR><table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\">";
    $this->salida .= "	     <tr><td align=\"center\"><input type=\"submit\" name=\"regresar\" class=\"input-submit\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "       </table>";
		$this->salida .="</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function PedirBodegaTrasladoPtos($conceptoInv,$FechaDocumento,$observacion){
	  $this->salida .= ThemeAbrirTabla('BODEGA DESTINO TRANFERENCIA DE PRODUCTOS');
		$action=ModuloGetURL('app','InvBodegas','user','SeleccionBodegaTransferencia');
    $this->salida .= "         <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "         <input type=\"hidden\" value=\"$conceptoInv\" name=\"conceptoInv\">";
		$this->salida .= "         <input type=\"hidden\" value=\"$FechaDocumento\" name=\"FechaDocumento\">";
		$this->salida .= "         <input type=\"hidden\" value=\"$observacion\" name=\"observacion\">";
    $this->salida .= "         <table class=\"normal_10\" border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
		$this->salida .= "         <tr><td width=\"100%\">";
		$this->salida .= "         <fieldset><legend class=\"field\">DATOS DE LA TRANSFERENCIA</legend>";
    $this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	        <tr><td></td></tr>";
		$this->salida .= "	        <tr class=\"modulo_list_claro\">";
		$this->salida .= "	        <td width=\"25%\"><label class=\"label\">CONCEPTO</td>";
    $NombreconceptoInv=$this->NomConceptoDocumento($conceptoInv);
		$this->salida .= "	         <td colspan=\"3\">".$NombreconceptoInv['descripcion']."</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td width=\"25%\"><label class=\"label\">FECHA</td>";
		$this->salida .= "	         <td colspan=\"3\">$FechaDocumento</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td width=\"25%\" class=\"".$this->SetStyle("numBodega")."\">BODEGA DESTINO:</td><td colspan=\"3\"><select name=\"numBodega\"  class=\"select\">";
		$TotalBodegas=$this->BodegasInventarioReposicion();
		$this->MostrarBodegas($TotalBodegas,'False','');
		$this->salida .= "        </select></td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "	        <tr><td></td></tr>";
    $this->salida .= "			     </table>";
		$this->salida .= "		      </fieldset></td><BR>";
    $this->salida .= "		      <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\">";
		$this->salida .= "		      <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\"></td></tr>";
		$this->salida .= "       </table><BR>";
    $this->salida .="</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function PtosTransferenciaBodegas($consecutivo,$conceptoInv,$FechaDocumento,$BodegaDest,$CentroUtilityDest,
	  $cantSolicitada,$costoProducto,$nombreProducto,$codigo,$unidadProducto,$ExisProducto,$ExisDest,$TipoReposicion,
		$codigoProductoProd,$descripcionProd,$CantidadProd,$consecutivoProd){
		
    $Empresa=$_SESSION['BODEGAS']['Empresa'];
    $CentroUtili=$_SESSION['BODEGAS']['CentroUtili'];
    $BodegaId=$_SESSION['BODEGAS']['BodegaId'];
    $RUTA = $_ROOT ."classes/classbuscador/buscador.php?";
		$this->salida.="\n<script language='javascript'>\n";
		$this->salida.="  var rem=\"\";\n";
		$this->salida.="  function abrirVentana(){\n";
		unset($_SESSION['SQL']);
		unset($_SESSION['SQLA']);
		unset($_SESSION['SQLB']);
    unset($_SESSION['SQLC']);
		unset($_SESSION['SQLD']);
		$this->salida.="    var str =\"width=450,height=250,resizable=no,status=no,scrollbars=yes\";\n";
		$this->salida.="    var url2 ='$RUTA'+'&sql='+'$Empresa'+'&sqla='+'$CentroUtili'+'&sqlb='+'$BodegaId'+'&BodegaDest='+'$BodegaDest'+'&CentroDest='+'$CentroUtilityDest'+'&tipo=inventarios'+'&forma=forma';\n";
		$this->salida.="    rem = window.open(url2, 'BUSCAR PRODUCTO', str)}\n";
		$this->salida.="</script>\n";
		$this->salida .= ThemeAbrirTabla('PRODUCTOS DE LA TRANSFERENCIA');
		$this->salida.=$mostrar;
		$this->salida .="</script>\n";
    $this->salida .= "       <BR>";		
		$this->Encabezado();		
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "         <tr><td width=\"100%\">";
		$this->salida .= "         <fieldset><legend class=\"field\">DOCUMENTO BODEGA</legend>";
    $this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	        <tr><td></td></tr>";
		$this->salida .= "	        <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"5%\"><label class=\"label\">CONCEPTO</td>";
    $NombreconceptoInv=$this->NomConceptoDocumento($conceptoInv);
		$this->salida .= "	         <td colspan=\"3\">".$NombreconceptoInv['descripcion']."</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td width=\"5%\"><label class=\"label\">FECHA</td>";
		$this->salida .= "	         <td>$FechaDocumento</td>";
		$this->salida .= "	         <td width=\"5%\"><label class=\"label\">BODEGA DESTINO </td>";
		$NombreBodega=$this->NombreBodegasInventario($BodegaDest,$CentroUtilityDest);
		$this->salida .= "	         <td>$BodegaDest   ".$NombreBodega['descripcion']."</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr><td></td></tr>";
    $this->salida .= "			     </table>";
		$this->salida .= "		      </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
		$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "	      <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	      </td></tr>";
		$this->salida .= "       </table>";
		
		if($consecutivo){
		$ProductosDocumento=$this->ConsultaProductosDocumentoTransaccion($consecutivo);
		if($ProductosDocumento){
			$action=ModuloGetURL('app','InvBodegas','user','TransferenciasBodegasDocs',array("consecutivo"=>$consecutivo,"conceptoInv"=>$conceptoInv,"FechaDocumento"=>$FechaDocumento,
			"BodegaDest"=>$BodegaDest,"CentroUtilityDest"=>$CentroUtilityDest,"cantSolicitada"=>$cantSolicitada,"costoProducto"=>$costoProducto,"nombreProducto"=>$nombreProducto,"codigo"=>$codigo,
			"unidadProducto"=>$unidadProducto,"ExisProducto"=>$ExisProducto,"ExisDest"=>$ExisDest,"TipoReposicion"=>$TipoReposicion));
			$this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";			
			$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida .= "			   <td width=\"10%\">COD PRODUCTO</td>";
			$this->salida .= "         <td>DESCRIPCION</td>";
			$this->salida .= "			   <td width=\"10%\">CANTIDAD</td>";
			$this->salida .= "			   <td width=\"10%\">COSTO UNIT.</td>";
			$this->salida .= "			   <td width=\"30%\">LOTES</td>";			
			$this->salida .= "			   <td width=\"10%\">COSTO TOTAL</td>";
			$this->salida .= "        </tr>";
			$y=0;
			for($i=0;$i<sizeof($ProductosDocumento);$i++){
				if($y % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
				$this->salida .= "	 <tr class=\"$estilo\">\n";
				$this->salida .= "	 <td>".$ProductosDocumento[$i]['codigo_producto']."</td>";
				$this->salida .= "	 <td>".$ProductosDocumento[$i]['descripcion']."</td>";
				$this->salida .= "	 <td>".$ProductosDocumento[$i]['cantidad']."</td>";
				$this->salida .= "	 <td>".$ProductosDocumento[$i]['costo']."</td>";				
				$this->salida .= "	 <td>";
				$this->salida .= "	     <table cellspacing=\"0\"  cellpadding=\"0\"border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= "	 		 <tr>";
				$sumaTotal=$this->SumaFechasLotesProductos($consecutivo,$ProductosDocumento[$i]['codigo_producto']);
				 if(($ProductosDocumento[$i]['sw_control_fecha_vencimiento_dest']==1) && ($sumaTotal['suma']<$ProductosDocumento[$i]['cantidad'])){
					$actionLotes=ModuloGetURL('app','InvBodegas','user','LlamaMostrarLotesPtosDocs',array("consecutivo"=>$consecutivo,"conceptoInv"=>$conceptoInv,"FechaDocumento"=>$FechaDocumento,
					"BodegaDest"=>$BodegaDest,"CentroUtilityDest"=>$CentroUtilityDest,"cantSolicitada"=>$cantSolicitada,"costoProducto"=>$costoProducto,"nombreProducto"=>$nombreProducto,"codigo"=>$codigo,
					"unidadProducto"=>$unidadProducto,"ExisProducto"=>$ExisProducto,"ExisDest"=>$ExisDest,"TipoReposicion"=>$TipoReposicion,
					"codigoProductoProd"=>$ProductosDocumento[$i]['codigo_producto'],"descripcionProd"=>$ProductosDocumento[$i]['descripcion'],"CantidadProd"=>$ProductosDocumento[$i]['cantidad'],
					"consecutivoProd"=>$ProductosDocumento[$i]['consecutivo']));
					$this->salida .= "     <td align=\"center\"><a href=\"$actionLotes\"><img title=\"Insertar Fechas Vencimiento\" border=\"0\" src=\"".GetThemePath()."/images/pparamed.png\"></a></td>";
				}else{
					$this->salida .= "     <td align=\"center\">&nbsp;</td>";
				}
				$Datos=$this->FechasLotesProductos($consecutivo,$ProductosDocumento[$i]['codigo_producto']);
				if($Datos){				
					$this->salida .= "     <td align=\"center\" colspan=\"3\">";
					$this->salida .= "	     <table cellspacing=\"2\" class=\"normal_10\" cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\">";
					$this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
					$this->salida .= "			   <td>FECHA</td>";
					$this->salida .= "         <td>LOTE</td>";
					$this->salida .= "			   <td>CANTIDAD</td>";
					$this->salida .= "			   <td>&nbsp;</td>";
					$this->salida .= "        </tr>";
					for($j=0;$j<sizeof($Datos);$j++){
						$this->salida .= "	      <tr class=\"$estilo1\">\n";
						$this->salida .= "	      <td width=\"32%\">".$Datos[$j]['fecha_vencimiento']."</td>";
						$this->salida .= "	      <td width=\"35%\">".$Datos[$j]['lote']."</td>";
						$this->salida .= "	      <td>".$Datos[$j]['cantidad']."</td>";
						$ElimFechaV=ModuloGetURL('app','InvBodegas','user','LlamaEliminarFechaVDocs',array("consecutivo"=>$consecutivo,"conceptoInv"=>$conceptoInv,"FechaDocumento"=>$FechaDocumento,
						"BodegaDest"=>$BodegaDest,"CentroUtilityDest"=>$CentroUtilityDest,"cantSolicitada"=>$cantSolicitada,"costoProducto"=>$costoProducto,"nombreProducto"=>$nombreProducto,"codigo"=>$codigo,
						"unidadProducto"=>$unidadProducto,"ExisProducto"=>$ExisProducto,"ExisDest"=>$ExisDest,"TipoReposicion"=>$TipoReposicion,
						"codigoProductoProd"=>$ProductosDocumento[$i]['codigo_producto'],"descripcionProd"=>$ProductosDocumento[$i]['descripcion'],"CantidadProd"=>$ProductosDocumento[$i]['cantidad'],
						"consecutivoProd"=>$ProductosDocumento[$i]['consecutivo'],"FechaVencimiento"=>$Datos[$j]['fecha_vencimiento'],"Lote"=>$Datos[$j]['lote'],"Cantidad"=>$Datos[$j]['cantidad']));
						$this->salida .= "	      <td width=\"5%\"><a href=\"$ElimFechaV\"><img title=\"Eliminar Fecha Vencimiento\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
						$this->salida .= "	      </tr>";
					}
					$this->salida .= "	     </table>";
					$this->salida .= "</td>";
				}else{
					$this->salida .= "     <td colspan=\"3\" align=\"center\">&nbsp;</td>";
				}
				$this->salida .= "	 		 </tr>";
				$this->salida .= "	     </table>";
				$this->salida .= "	 </td>";
				$this->salida .= "	 <td>".$valoSum=($ProductosDocumento[$i]['costo'] *$ProductosDocumento[$i]['cantidad'])."</td>";
				$this->salida .= "	 </tr>\n";
				$y++;
				$valoSumaTotal+=$valoSum;
			}
			$this->salida .= "	 <tr class=\"$estilo\">\n";
			$this->salida .= "	 <td colspan=\"5\" align=\"right\" class=\"label\">TOTAL</td>";
			$this->salida .= "	 <td>".$valoSumaTotal."</td>";
			$this->salida .= "	 </tr>\n";
			$this->salida .= "       </table>";
			$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "         <tr><td align=\"center\">";
			$this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"GuardarDocumento\" value=\"GUARDAR DOCUMENTO\">";
			$this->salida .= "         </td></tr>";
			$this->salida .= "       </table>";
			$this->salida .="       </form>";
		}
		}
		
		if($codigoProductoProd){
		  $actionDos=ModuloGetURL('app','InvBodegas','user','InsertarFechaVencimientoLoteDocs',array("consecutivo"=>$consecutivo,"conceptoInv"=>$conceptoInv,"FechaDocumento"=>$FechaDocumento,
			"BodegaDest"=>$BodegaDest,"CentroUtilityDest"=>$CentroUtilityDest,"cantSolicitada"=>$cantSolicitada,"costoProducto"=>$costoProducto,"nombreProducto"=>$nombreProducto,"codigo"=>$codigo,
			"unidadProducto"=>$unidadProducto,"ExisProducto"=>$ExisProducto,"ExisDest"=>$ExisDest,"TipoReposicion"=>$TipoReposicion,
			"codigoProductoProd"=>$codigoProductoProd,"descripcionProd"=>$descripcionProd,"CantidadProd"=>$CantidadProd,
			"consecutivoProd"=>$consecutivoProd));
		  $this->salida .= "       <form name=\"formaUno\" action=\"$actionDos\" method=\"post\">";			
			$sumaTotal=$this->SumaFechasLotesProductos($consecutivo,$codigoProductoProd);			
			$cantidadFalta=$CantidadProd-$sumaTotal['suma'];
		  $this->salida .= "          <table class=\"normal_10\" border=\"0\" width=\"85%\" align=\"center\">";
			$this->salida .= "          <tr><td width=\"100%\">";
			$this->salida .= "          <fieldset><legend class=\"field\">DATOS DEL PRODUCTO</legend>";
			$this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "          <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">$codigoProductoProd&nbsp&nbsp&nbsp;$descripcionProd</td></tr>";
			$this->salida .= "          <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">CANTIDAD QUE FALTA PARA ALCANZAR EL TOTAL&nbsp&nbsp&nbsp;$cantidadFalta</td></tr>";
			$this->salida .= "	        <tr class=\"modulo_list_claro\">";
			$this->salida .= "	        <td class=\"label\">FECHA VENCIMIENTO</td>";
      $this->salida .= "	  	    <td align=\"center\"><input type=\"text\" name=\"fechaVencimiento\" value=\"".$_REQUEST['fechaVencimiento']."\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
			$this->salida .= "	  	    ".ReturnOpenCalendario('formaUno','fechaVencimiento','/')."</td>";
			$this->salida .= "	        <td class=\"label\">No. LOTE</td>";
			$this->salida .= "	        <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"lote\" value=\"".$_REQUEST['lote']."\"></td>";
			$this->salida .= "	        <td class=\"label\">CANTIDAD</td>";
			$this->salida .= "	        <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"cantidadLote\" value=\"".$_REQUEST['cantidadLote']."\"></td>";
			$this-> salida .= "	        <tr><td></td></tr>";
      $this->salida .= "	        <tr><td colspan=\"6\" align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"CANCELAR\">";
			$this->salida .= "	        <input type=\"submit\" class=\"input-submit\" name=\"insertar\" value=\"INSERTAR\"></td></tr>";
			$this->salida .= "			     </table>";
			$this->salida .= "		      </fieldset></td>";
			$this->salida .= "         </table>";
			$this->salida .="       </form>";
		}
		
		$action=ModuloGetURL('app','InvBodegas','user','InsPtosTransferenciaBodegas',array("Documento"=>$consecutivo,"conceptoInv"=>$conceptoInv,"FechaDocumento"=>$FechaDocumento,
		"BodegaDest"=>$BodegaDest,"CentroUtilityDest"=>$CentroUtilityDest,"cantSolicitada"=>$cantSolicitada,"costoProducto"=>$costoProducto,"nombreProducto"=>$nombreProducto,"codigo"=>$codigo,
		"unidadProducto"=>$unidadProducto,"ExisProducto"=>$ExisProducto,"ExisDest"=>$ExisDest,"TipoReposicion"=>$TipoReposicion));
		
		$this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"85%\" align=\"center\">";		
    $this->salida .= "	      <tr width=\"100%\" class=\"modulo_table_title\"><td align=\"center\">DATOS DEL PRODUCTO</td></tr>";
		$this->salida .= "        <tr class=\"modulo_list_oscuro\"><td width=\"100%\">";
		$this->salida .= "        <BR><table class=\"normal_10\" cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida.= "<tr class=\"modulo_list_claro\">";
		$this->salida.= "<td width=\"5%\" class=\"".$this->SetStyle("nombreProducto")."\">NOMBRE</td>";
		$this->salida.= "<td><input type=\"text\" name=\"nombreProducto\" maxlength=\"50\" size=\"50\" class=\"input-text\" value=\"$nombreProducto\" READONLY></td>";
		$this->salida.= "<td width=\"5%\" class=\"".$this->SetStyle("codigo")."\">CODIGO</td>";
    $this->salida.= "<td><input type=\"text\" name=\"codigo\" maxlength=\"10\" size=\"10\" class=\"input-text\" value=\"$codigo\" READONLY></td>";
		$this->salida.= "</tr>";
		$this->salida.= "<tr class=\"modulo_list_claro\">";
		$this->salida.= "<td width=\"5%\" class=\"".$this->SetStyle("unidadProducto")."\">UNIDAD</td>";
		$this->salida.= "<td><input type=\"text\" name=\"unidadProducto\" maxlength=\"40\" size=\"40\" class=\"input-text\" value=\"$unidadProducto\" READONLY></td>";
		$this->salida.= "<td width=\"5%\" class=\"".$this->SetStyle("ExisProducto")."\">EXISTENCIAS</td>";
		$this->salida.= "<td><input type=\"text\" name=\"ExisProducto\" maxlength=\"11\" size=\"11\" class=\"input-text\" value=\"$ExisProducto\" READONLY>";
		$this->salida.= "<input type=\"hidden\" name=\"costoProducto\" maxlength=\"17\" size=\"17\" class=\"input-text\" value=\"$costoProducto\" READONLY>&nbsp&nbsp&nbsp;";
		$this->salida.= "<input type=\"submit\" name=\"buscar\" value=\"BUSCAR\" class=\"input-submit\"></td>";
		$this->salida.= "</tr>";
		//$this->salida.= "<td><label class=\"".$this->SetStyle("precioProducto")."\">PRECIO</td>";
		$this->salida.= "            <input type=\"hidden\" name=\"precioProducto\" maxlength=\"17\" size=\"17\" class=\"input-text\" value=\"$precioProducto\" READONLY>";
		$this->salida .= "	          <tr class=\"modulo_list_claro\">";
		$this->salida .= "	          <td width=\"5%\" class=\"".$this->SetStyle("cantSolicitada")."\">EXIXSTENCIAS BODEGA DESTINO</td>";
		$this->salida.=  "            <td><input type=\"text\" name=\"ExisDest\" size=\"17\" class=\"input-text\" value=\"$ExisDest\" readonly>";
    $this->salida .= "	          <td width=\"5%\" class=\"".$this->SetStyle("cantSolicitada")."\">CANTIDAD</td>";
		$this->salida .= "	  	      <td><input type=\"text\" maxlength=\"16\" size=\"16\" name=\"cantSolicitada\" value=\"$cantSolicitada\" class=\"input-text\"></td>";
		$this->salida .= "		    </tr>";
    $this->salida .= "		    <tr class=\"modulo_list_claro\">";
    $this->salida .= "           <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= "           <input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"INSERTAR\"></td></tr>";
    $this->salida .= "			     </table><BR>";
		$this->salida .= "		      </td></tr>";
		$this->salida .= "       </table><BR>";
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "         <tr><td align=\"center\">";
    $this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"Regresar\" value=\"VOLVER\">";
    $this->salida .= "         </td></tr>";
    $this->salida .= "       </table><BR>";
		$this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ConfirmacionTransferenciasBodegas(){
    $this->salida .= ThemeAbrirTabla('TRANSFERENCIAS SIN CONFIRMAR');
		$action=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
		$this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
    $confirmaciones=$this->TranferenciasConfirmarBodega();
		if($confirmaciones){
		$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "         <td width=\"35%\">BODEGA ORIGEN</td>";
		$this->salida .= "         <td>CODIGO</td>";
    $this->salida .= "			   <td width=\"28%\">FECHA</td>";
    $this->salida .= "			   <td width=\"15%\">DETALLE</td>";
		$y=0;
		for($i=0;$i<sizeof($confirmaciones);$i++){
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "	 <tr class=\"$estilo\">\n";
      $NombreBodega=$this->NombreBodegasInventario($confirmaciones[$i]['bodega'],$confirmaciones[$i]['centro_utilidad']);
			$this->salida .= "	 <td>".$confirmaciones[$i]['bodega']."  ".$NombreBodega['descripcion']."</td>";
			$this->salida .= "	 <td>".$confirmaciones[$i]['inv_documento_transferencia_id']."</td>";
			$this->salida .= "	 <td>".$confirmaciones[$i]['fecha_transferencia']."</td>";
			$actionh=ModuloGetURL('app','InvBodegas','user','LlamaDetalleTransferenciaBodega',array("consecutivo"=>$confirmaciones[$i]['inv_documento_transferencia_id'],"bodegaOrigen"=>$confirmaciones[$i]['bodega'],"FechaTransferencia"=>$confirmaciones[$i]['fecha_transferencia'],"centroUtilidadOrigen"=>$confirmaciones[$i]['centro_utilidad']));
      $this->salida .= "	 <td width=\"15%\" align=\"center\"><a href=\"$actionh\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a></td>";
			$this->salida .= "	 </tr>\n";
			$y++;
		}
		$this->salida .= "       </table><BR>";
		$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "	     <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"regresar\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "      </table>";
		}else{
      $this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\">";
			$this->salida .= "	     <tr><td align=\"center\" class=\"label_error\">NO EXISTEN TRASLADOS DE PRODUCTOS PENDIENTES CON OTRAS BODEGAS</td></tr>";
			$this->salida .= "	     <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"regresar\" value=\"VOLVER\"></td></tr>";
			$this->salida .= "      </table>";
		}
		$this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function DetalleTransferenciaBodega($consecutivo,$bodegaOrigen,$centroUtilidadOrigen,$FechaTransferencia,$bandera,$codigoIns,$cantidadTotal,$descipIns){
    $this->salida .= ThemeAbrirTabla('PRODUCTOS DE LA TRANSFERENCIA');
		$action=ModuloGetURL('app','InvBodegas','user','ConfirmarTransferenciasBodegas');
		$this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "       <input type=\"hidden\" name=\"consecutivo\" value=\"$consecutivo\">";
		$this->salida .= "       <input type=\"hidden\" name=\"FechaTransferencia\" value=\"$FechaTransferencia\">";
		$this->salida .= "       <input type=\"hidden\" name=\"bodegaOrigen\" value=\"$bodegaOrigen\">";
		$this->salida .= "       <input type=\"hidden\" name=\"centroUtilidadOrigen\" value=\"$centroUtilidadOrigen\">";
    $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "         <tr><td width=\"100%\">";
		$this->salida .= "         <fieldset><legend class=\"field\">DATOS DE LA TRANSFERENCIA</legend>";
    $this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	        <tr><td></td></tr>";
		$this->salida .= "	        <tr class=\"modulo_list_claro\">";
		$this->salida .= "	         <td width=\"40%\"><label class=\"label\">BODEGA ORIGEN</td>";
		$NombreBodega=$this->NombreBodegasInventario($bodegaOrigen,$centroUtilidadOrigen);
		$this->salida .= "	         <td>$bodegaOrigen ".$NombreBodega['descripcion']."</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	         <td width=\"40%\"><label class=\"label\">FECHA DE LA TRANSFERENCIA</td>";
		$this->salida .= "	         <td>$FechaTransferencia</td>";
		$this->salida .= "	        </tr>";
		$this->salida .= "	        <tr><td></td></tr>";
    $this->salida .= "			     </table>";
		$this->salida .= "		      </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
		$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "	      <tr><td colspan=\"4\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	      </td><tr>";
		$this->salida .= "	     </table>";
    $ProductosDocumento=$this->ConsultaProductosDocumentoTransaccion($consecutivo);
		if($ProductosDocumento){
		$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\" align=\"center\">";
    $this->salida .= "			   <td>COD PRODUCTO</td>";
		$this->salida .= "         <td>DESCRIPCION</td>";
		$this->salida .= "			   <td>CANTIDAD TOTAL</td>";
		$this->salida .= "			   <td>&nbsp;</td>";
		$this->salida .= "			   <td width=\"20%\">FECHA VENCIMIENTO</td>";
		$this->salida .= "			   <td width=\"15%\">LOTE</td>";
		$this->salida .= "			   <td width=\"15%\">CANTIDAD LOTE</td>";
		$this->salida .= "        </tr>";
		$y=0;
		$z=1;
		for($i=0;$i<sizeof($ProductosDocumento);$i++){
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			if($z % 2){$estilo1='modulo_list_claro';}else{$estilo1='modulo_list_oscuro';}
			$this->salida .= "	 <tr class=\"$estilo\">\n";
			$Pr=$ProductosDocumento[$i]['codigo_producto'];
			$this->salida .= "	 <td>".$ProductosDocumento[$i]['codigo_producto']."</td>";
			$this->salida .= "	 <td>".$ProductosDocumento[$i]['descripcion']."</td>";
			$this->salida .= "	 <td>".$ProductosDocumento[$i]['cantidad']."</td>";
			$sumaTotal=$this->SumaFechasLotesProductos($consecutivo,$ProductosDocumento[$i]['codigo_producto']);
			if(($ProductosDocumento[$i]['sw_control_fecha_vencimiento']=='1') && ($sumaTotal['suma']<$ProductosDocumento[$i]['cantidad'])){
				$actionAdicion=ModuloGetURL('app','InvBodegas','user','LlamaInsertarFechaVenciLotePto',array("consecutivo"=>$consecutivo,"bodegaOrigen"=>$bodegaOrigen,"centroUtilidadOrigen"=>$centroUtilidadOrigen,"FechaTransferencia"=>$FechaTransferencia,"codigoProducto"=>$ProductosDocumento[$i]['codigo_producto'],"cantidadTotal"=>$ProductosDocumento[$i]['cantidad'],"descripcion"=>$ProductosDocumento[$i]['descripcion']));
				$this->salida .= "	 <td><a href=\"$actionAdicion\"><img border=\"0\" src=\"".GetThemePath()."/images/pparamed.png\"></a></td>";
			}else{
        $this->salida .= "	 <td>&nbsp;</td>";
			}
      $this->salida .= "	 <td colspan=\"3\">";
			if($ProductosDocumento[$i]['sw_control_fecha_vencimiento']=='1'){
			  $Datos=$this->FechasLotesProductos($consecutivo,$ProductosDocumento[$i]['codigo_producto']);
				if($Datos){
					$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
					for($j=0;$j<sizeof($Datos);$j++){
						$this->salida .= "	      <tr class=\"$estilo1\">\n";
						$this->salida .= "	      <td width=\"40%\">".$Datos[$j]['fecha_vencimiento']."</td>";
						$this->salida .= "	      <td width=\"31%\">".$Datos[$j]['lote']."</td>";
						$this->salida .= "	      <td>".$Datos[$j]['cantidad']."</td>";
            $ElimFechaV=ModuloGetURL('app','InvBodegas','user','LlamaEliminarFechaV',array("consecutivo"=>$consecutivo,"bodegaOrigen"=>$bodegaOrigen,"centroUtilidadOrigen"=>$centroUtilidadOrigen,"FechaTransferencia"=>$FechaTransferencia,"codigoProducto"=>$ProductosDocumento[$i]['codigo_producto'],"FechaVencimiento"=>$Datos[$j]['fecha_vencimiento'],"Lote"=>$Datos[$j]['lote'],"Cantidad"=>$Datos[$j]['cantidad']));
            $this->salida .= "	      <td width=\"5%\"><a href=\"$ElimFechaV\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
						$this->salida .= "	      </tr>";
					}
					$this->salida .= "	     </table>";
          $this->salida .= "</td>";
				}else{
          $this->salida .= "</td>";
				}
			}
			$this->salida .= "	 </tr>\n";
			$y++;
		}
    $this->salida .= "       </table><BR>";
		}
		$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "	     <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"regresar\" value=\"VOLVER\">";
		$this->salida .= "	     <input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"CONFIRMAR\"></td></tr>";
		$this->salida .= "      </table>";
		$this->salida .="       </form>";
		if($bandera==1){
		  $actionDos=ModuloGetURL('app','InvBodegas','user','InsertarFechaVencimientoLotetmp');
		  $this->salida .= "       <form name=\"formaUno\" action=\"$actionDos\" method=\"post\">";
			$this->salida .= "       <input type=\"hidden\" name=\"codigoIns\" value=\"$codigoIns\">";
		  $this->salida .= "       <input type=\"hidden\" name=\"descipIns\" value=\"$descipIns\">";
			$this->salida .= "       <input type=\"hidden\" name=\"consecutivo\" value=\"$consecutivo\">";
			$this->salida .= "       <input type=\"hidden\" name=\"FechaTransferencia\" value=\"$FechaTransferencia\">";
			$this->salida .= "       <input type=\"hidden\" name=\"bodegaOrigen\" value=\"$bodegaOrigen\">";
			$this->salida .= "       <input type=\"hidden\" name=\"centroUtilidadOrigen\" value=\"$centroUtilidadOrigen\">";
			$this->salida .= "       <input type=\"hidden\" name=\"cantidadTotal\" value=\"$cantidadTotal\">";
		  $this->salida .= "          <table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "          <tr><td width=\"100%\">";
			$this->salida .= "          <fieldset><legend class=\"field\">DATOS DEL PRODUCTO</legend>";
			$this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "	        <tr><td></td></tr>";
			$sumaTotal=$this->SumaFechasLotesProductos($consecutivo,$codigoIns);
			$cantidadRes=$cantidadTotal-$sumaTotal['suma'];
			$this->salida .= "         <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">$codigoIns&nbsp&nbsp&nbsp;$descipIns</td></tr>";
			$this->salida .= "         <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">CANTIDAD FALTANTE POR INSERTAR $cantidadRes</td></tr>";
			$this->salida .= "	        <tr class=\"modulo_list_claro\">";
			$this->salida .= "	        <td class=\"label\">FECHA VENCIMIENTO</td>";
      $this->salida .= "	  	    <td align=\"center\"><input type=\"text\" name=\"fechaVencimiento\" value=\"$fechaVencimiento\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
			$this->salida .= "	  	    ".ReturnOpenCalendario('formaUno','fechaVencimiento','/')."</td>";
			$this->salida .= "	        <td class=\"label\">No. LOTE</td>";
			$this->salida .= "	        <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"lote\" value=\"$lote\"></td>";
			$this->salida .= "	        <td class=\"label\">CANTIDAD</td>";
			$this->salida .= "	        <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"cantidad\" value=\"$cantidad\"></td>";
			$this-> salida .= "	        <tr><td></td></tr>";
      $this->salida .= "	        <tr><td colspan=\"6\" align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"CANCELAR\">";
			$this->salida .= "	        <input type=\"submit\" class=\"input-submit\" name=\"insertar\" value=\"INSERTAR\"></td></tr>";
			$this->salida .= "			     </table>";
			$this->salida .= "		      </fieldset></td><BR>";
			$this->salida .= "         </table><BR>";
			$this->salida .="       </form>";
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function MostrasrLotesProducto($codigoProducto,$descripcion,$codigoProd,$descripcionProd,$grupo,$NomGrupo,$clasePr,$NomClase,$subclase,$NomSubClase){
    $this->salida .= ThemeAbrirTabla('LOTES Y FECHA DE VENCIMIETNO DEL PRODUCTO');
		$action=ModuloGetURL('app','InvBodegas','user','RegresoFormaExistenciasBodegas',array("conteo"=>$this->conteo,"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso'],"codigoProd"=>$codigoProd,"descripcionProd"=>$descripcionProd,"grupo"=>$grupo,"NomGrupo"=>$NomGrupo,"clasePr"=>$clasePr,"NomClase"=>$NomClase,"subclase"=>$subclase,"NomSubClase"=>$NomSubClase));
		$this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$lotes=$this->lotesFechasProducto($codigoProducto);
		if($lotes){
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"55%\" align=\"center\">";
		$this->salida .= "         <tr><td width=\"100%\">";
		$this->salida .= "         <fieldset><legend class=\"field\">DATOS DEL LOS LOTES EXISTENTES DEL PRODUCTO</legend>";
    $this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	        <tr><td></td></tr>";
		$this->salida .= "	        <tr class=\"modulo_table_list_title\"><td colspan=\"3\" align=\"center\">$codigoProducto&nbsp&nbsp&nbsp;$descripcion</td></tr>";
		$this->salida .= "	        <tr class=\"modulo_table_list_title\">";
		$this->salida .= "	        <td><label>FECHA DE VENCIMIENTO</td>";
		$this->salida .= "	        <td><label>LOTE</td>";
		$this->salida .= "	        <td><label>CANTIDAD</td>";
		$this->salida .= "	        </tr>";
    for($i=0;$i<sizeof($lotes);$i++){
			$this->salida .= "	        <tr class=\"modulo_list_oscuro\">";
			$this->salida .= "	         <td>".$lotes[$i]['fecha_vencimiento']."</td>";
			$this->salida .= "	         <td>".$lotes[$i]['lote']."</td>";
			$this->salida .= "	         <td>".$lotes[$i]['cantidad']."</td>";
			$this->salida .= "	        </tr>";
		}
		$this->salida .= "	        <tr><td></td></tr>";
    $this->salida .= "			     </table>";
		$this->salida .= "		      </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
		}else{
      $this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
      $this->salida .= "	        <tr><td class=\"label_error\" align=\"center\">NO HAY LOTES DE ESTE PRODUCTO</td></tr>";
      $this->salida .= "			    </table>";
		}
    $this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	        <tr><td align=\"center\"><input type=\"submit\" name=\"regresar\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
    $this->salida .= "			    </table>";
	  $this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function PedirBodegaReposicion(){
	  
	  $this->salida .= ThemeAbrirTabla('REPOSICION AUTOMATICA DE PRODUCTOS');
		$action=ModuloGetURL('app','InvBodegas','user','BuscarPtosParaReposicion');
    $this->salida .= "         <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "         <table class=\"normal_10\" border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
    $this->salida .= "         <tr><td width=\"100%\">";
		$this->salida .= "         <fieldset><legend class=\"field\">SELECCION BODEGA DE LA CONSULTA EXISTENCIAS</legend>";
    $this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	        <tr><td></td></tr>";
		$this->salida .= "	        <tr class=\"modulo_list_claro\">";
		$this->salida .= "		    <td class=\"".$this->SetStyle("DatosBodega")."\">BODEGA</td><td colspan=\"3\"><select name=\"DatosBodega\"  class=\"select\">";
		$TotalBodegas=$this->BodegasInventarioReposicion();
		$this->MostrarBodegas($TotalBodegas,'False','');
		$this->salida .= "        </select></td>";
		$this->salida .= "		    </tr>";
		$this->salida .= "	        <tr><td></td></tr>";
    $this->salida .= "			     </table>";
		$this->salida .= "		      </fieldset></td><BR>";
    $this->salida .= "		      <tr><td align=\"center\"><BR><input class=\"input-submit\" type=\"submit\" name=\"salir\" value=\"VOLVER\">";
		$this->salida .= "		      <input class=\"input-submit\" type=\"submit\" name=\"continuar\" value=\"CONTINUAR\"></td></tr>";
		$this->salida .= "       </table><BR>";
    $this->salida .="</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
* La funcion qu visualiza las solicitudes de despacho  de medicamentos realizadas a la bodega
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa en la que se esta trabajando
* @param string centro de utilidad en el que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string bodega en la que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
*/

	function FormaListadoSolicitudes(){

		$this->salida .= ThemeAbrirTabla('LISTADO SOLICITUDES MEDICAMENTOS E INSUMOS');
		$actionReg=ModuloGetURL('app','InvBodegas','user','MenuInventariosDespachos');
		$this->salida .= "       <form name=\"forma\" action=\"$actionReg\" method=\"post\">";
		$this->Encabezado();
		$solicitudes=$this->SolicitudesMedicamentos();
		if($solicitudes)
          {
			$this->salida .= "       <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "        <tr class=\"modulo_table_list_title\">";
			$this->salida .= "          <td width=\"30%\" nowrap align=\"center\"><b>DEPARTAMENTO</b></td>";
			$this->salida .= "          <td width=\"30%\" nowrap align=\"center\"><b>ESTACION<br>SOLICITANTE</b></td>";
			$this->salida .= "          <td width=\"20%\" nowrap align=\"center\"><b>FECHA</b></td>";
			$this->salida .= "          <td width=\"14%\" nowrap align=\"center\"><b>CODIGO</b></td>";
			$this->salida .= "          <td width=\"11%\" nowrap align=\"center\"></td>";
			$y=0;
			$rep= new GetReports();
			foreach($solicitudes as $departamento => $vector)
               {
				if($y % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
				$this->salida .= "	 <tr class=\"$estilo\">\n";
				(list($dpto,$descripcionDpto)=explode('-',$departamento));
				$this->salida .= "	 <td>";
				$this->salida .= "	 <table width=\"100%\" border=\"0\">";
				$this->salida .= "	  <tr class=\"$estilo\"><td>$descripcionDpto</td></tr>";
				$this->salida .= "	  <tr class=\"$estilo\"><td align=\"right\">";
				$action=ModuloGetURL('app','InvBodegas','user','ImprimirTotalesSolicitudesDpto',array("departamento"=>$dpto,"descripcionDpto"=>$descripcionDpto));
				$this->salida .= "	 <a title=\"Imprimir Totales de las Solicitudes\" href=\"$action\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\"></a>&nbsp&nbsp&nbsp&nbsp;";
				$action1=ModuloGetURL('app','InvBodegas','user','LlamaDespachoSolicitudesDpto',array("departamento"=>$dpto,"descripcionDpto"=>$descripcionDpto));
				$this->salida .= "	 <a title=\"Despachar Todas las Solicitudes\" href=\"$action1\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/producto.png\"></a>&nbsp&nbsp&nbsp&nbsp;";
				$action=ModuloGetURL('app','InvBodegas','user','ImprimirSolicitudMedTotalDpto',array("departamento"=>$dpto,"descripcionDpto"=>$descripcionDpto));
				$this->salida .= "	 <a title=\"Imprimir Todas las Solicitudes\" href=\"$action\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\"></a>";
				$this->salida .= "	 </td></tr>";
                    $this->salida .= "	  <tr class=\"$estilo\"><td align=\"left\">";
                    $mostrar=$rep->GetJavaReport('app','InvBodegas','SolicDespachosTotalizadas_html',array("departamento"=>$dpto,"descripcionDpto"=>$descripcionDpto),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                    $nombre_funcion=$rep->GetJavaFunction();
                    $this->salida .=$mostrar;
				$this->salida .= "	 <BR><a class=\"Menu\" title=\"Imprimir Totales de las Solicitudes\"  href=\"javascript:$nombre_funcion\"><img border=\"0\" src=\"".GetThemePath()."/images/pcopiar.png\">&nbsp&nbsp;PDF</a>&nbsp&nbsp&nbsp&nbsp;";
                    $mostrar1=$rep->GetJavaReport('app','InvBodegas','SolicitudesDespachosDpto_html',array("departamento"=>$dpto,"descripcionDpto"=>$descripcionDpto),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                    $nombre_funcion1=$rep->GetJavaFunction();
                    $this->salida .=$mostrar1;
                    $this->salida .= "	 <a class=\"Menu\" title=\"Imprimir Todas las Solicitudes\"  href=\"javascript:$nombre_funcion1\"><img border=\"0\" src=\"".GetThemePath()."/images/pcopiar.png\">&nbsp&nbsp;PDF";
                    $accion=ModuloGetURL('app','InvBodegas','user','ConsultarSolicitudesSinConfimar',array("departamento"=>$dpto,"descripcionDpto"=>$descripcionDpto));
                    $this->salida .= "   <BR><BR><a class=\"Menu\" href=\"$accion\">SOLICITUDES SIN CONFIRMAR&nbsp&nbsp;<img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a>";
                    $this->salida .= "	 </td></tr>";
				$this->salida .= "	 </table>";
				$this->salida .= "	 </td>";
				$this->salida .= "	 <td colspan=\"4\">";
				$this->salida .= "       <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
				foreach($vector as $solicitudId => $Datos)
                    {
					$NombreEstacion=$this->NombreEstacion($Datos['estacion_id']);
					$this->salida .= "	 <tr class=\"$estilo1\">\n";
					$this->salida .= "	 <td width=\"43%\">".$NombreEstacion['descripcion']."</td>";
					(list($fecha,$HoraTot)=explode(' ',$Datos['fecha_solicitud']));
					(list($ano,$mes,$dia)=explode('-',$fecha));
					(list($hora,$min)=explode(':',$HoraTot));
					$this->salida .= "	 <td width=\"30%\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
					$this->salida .= "	 <td width=\"20%\">".$Datos['solicitud_id']."</td>";
					$action=ModuloGetURL('app','InvBodegas','user','DetalleSolicitudMedicamento',array("EstacionId"=>$Datos['estacion_id'],"NombreEstacion"=>$NombreEstacion['descripcion'],"SolicitudId"=>$Datos['solicitud_id'],"Fecha"=>$Datos['fecha_solicitud'],"Ingreso"=>$Datos['ingreso'],"usuarioestacion"=>$Datos['usuario_id'].' '.$Datos['usuarioestacion'],"nombrepac"=>$Datos['nombrepac'],"tipo_id_paciente"=>$Datos['tipo_id_paciente'],"paciente_id"=>$Datos['paciente_id'],"cama"=>$Datos['cama']));
					$this->salida .= "	 <td align=\"center\"><a href=\"$action\" class=\"link\"><img title=\"Ver Detalle\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></td>";
					$medicamentos=$this->medicamentosReportImprimir($Datos['solicitud_id']);
					$action=ModuloGetURL('app','InvBodegas','user','ImprimirSolicitudMed',array("EstacionId"=>$Datos['estacion_id'],
					"NombreEstacion"=>$NombreEstacion['descripcion'],"SolicitudId"=>$Datos['solicitud_id'],
					"Fecha"=>$Datos['fecha_solicitud'],"usuarioestacion"=>$Datos['usuarioestacion'],
					"usuarioId"=>$Datos['usuario_id'],"deptoestacion"=>$Datos['deptoestacion'],
					"medicamentos"=>$medicamentos,"rango"=>$Datos['rango'],"tipoafil"=>$Datos['tipo_afiliado_id'],
					"cama"=>$Datos['cama'],"pieza"=>$Datos['pieza'],"plan"=>$Datos['plan_descripcion'],"tipoidPac"=>$Datos['tipo_id_paciente'],
					"paciente"=>$Datos['paciente_id'],"nombrepac"=>$Datos['nombrepac']));
					$this->salida .= "	 <td align=\"center\"><a title=\"Imprimir\" href=\"$action\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\"><a></td>";
					$this->salida .= "	 </tr>";
				}
				$this->salida .= "       </table>";
				$this->salida .= "	 </td>";
				$this->salida .= "	 </tr>";
				$y++;
			}
			$this->salida .= "   </table><BR>";
		}else{
			$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON SOLICITUDES PENDIENTES</td></tr>";
			$this->salida .= "       </table><br>";
		}
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "        <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "       </table>";
		$this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
  
  function FrmConsultarSolicitudesSinConfimar($departamento,$descripcionDpto){
  
    $this->salida .= ThemeAbrirTabla('LISTADO SOLICITUDES MEDICAMENTOS E INSUMOS SIN CONFIRMAR');
    $actionReg=ModuloGetURL('app','InvBodegas','user','LlamaSoliciMedica');
    $this->salida .= "       <form name=\"forma\" action=\"$actionReg\" method=\"post\">";
    $this->Encabezado();
    $solicitudes=$this->SolicitudesMedicamentosSinConfirmar($departamento);
    if($solicitudes){
      $this->salida .= "       <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "        <tr class=\"modulo_table_list_title\">";
      $this->salida .= "          <td width=\"17%\" nowrap align=\"center\"><b>DEPARTAMENTO</b></td>";
      $this->salida .= "          <td width=\"17%\" nowrap align=\"center\"><b>ESTACION<br>SOLICITANTE</b></td>";
      $this->salida .= "          <td width=\"25%\" nowrap align=\"center\"><b>PACIENTE</b></td>";
      $this->salida .= "          <td width=\"10%\" nowrap align=\"center\"><b>ESTADO DE LA<BR>CONFIRMACION</b></td>";
      $this->salida .= "          <td width=\"12%\" nowrap align=\"center\"><b>FECHA</b></td>";
      $this->salida .= "          <td width=\"10%\" nowrap align=\"center\"><b>CODIGO</b></td>";
      $this->salida .= "          <td width=\"3%\" nowrap align=\"center\"></td>";
      //$this->salida .= "        <td align=\"center\">IMPRIMIR</td>";
      $y=0;
      $rep= new GetReports();
      foreach($solicitudes as $departamentoVec => $vector){
        if($y % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
        $this->salida .= "   <tr class=\"$estilo\">\n";
        (list($dpto,$descripcionDpto)=explode('-',$departamentoVec));
        $this->salida .= "   <td>";
        $this->salida .= "   <table width=\"100%\" border=\"0\">";
        $this->salida .= "    <tr class=\"$estilo\"><td>$descripcionDpto</td></tr>";        
        $this->salida .= "   </table>";
        $this->salida .= "   </td>";
        $this->salida .= "   <td colspan=\"6\">";
        $this->salida .= "       <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
        foreach($vector as $solicitudId => $Datos){
          $NombreEstacion=$this->NombreEstacion($Datos['estacion_id']);
          $this->salida .= "   <tr class=\"$estilo1\">\n";
          $this->salida .= "   <td width=\"22%\">".$NombreEstacion['descripcion']."</td>";
          $this->salida .= "   <td width=\"33%\">".$Datos['nombrepac']."</td>";
          if($Datos['sw_estado']=='6'){
            $this->salida .= "   <td align=\"center\" width=\"13%\" class=\"label_error\">CANCELADA</td>";
          }else{
            $this->salida .= "   <td align=\"center\" width=\"13%\" class=\"label_mark\">PENDIENTE</td>";
          }
          (list($fecha,$HoraTot)=explode(' ',$Datos['fecha_solicitud']));
          (list($ano,$mes,$dia)=explode('-',$fecha));
          (list($hora,$min)=explode(':',$HoraTot));          
          $this->salida .= "   <td width=\"16%\">".ucfirst(strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano)))."</td>";
          $this->salida .= "   <td width=\"13%\">".$Datos['solicitud_id']."</td>";
          $action=ModuloGetURL('app','InvBodegas','user','DetalleSolicitudMedicamentoSinConfirmar',array("EstacionId"=>$Datos['estacion_id'],"NombreEstacion"=>$NombreEstacion['descripcion'],"SolicitudId"=>$Datos['solicitud_id'],"Fecha"=>$Datos['fecha_solicitud'],"Ingreso"=>$Datos['ingreso'],"usuarioestacion"=>$Datos['usuario_id'].' '.$Datos['usuarioestacion'],"nombrepac"=>$Datos['nombrepac'],"tipo_id_paciente"=>$Datos['tipo_id_paciente'],"paciente_id"=>$Datos['paciente_id'],"cama"=>$Datos['cama'],"departamento"=>$dpto,"descripcionDpto"=>$descripcionDpto,"estado"=>$Datos['sw_estado']));
          $this->salida .= "   <td align=\"center\"><a href=\"$action\" class=\"link\"><img title=\"Ver Detalle\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></td>";          
          $this->salida .= "   </tr>";
        }
        $this->salida .= "       </table>";
        $this->salida .= "   </td>";
        $this->salida .= "   </tr>";
        $y++;
      }
      $this->salida .= "   </table><BR>";
    }else{
      $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON SOLICITUDES PENDIENTES</td></tr>";
      $this->salida .= "       </table><br>";
    }
    $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
    $this->salida .= "        <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "       </table>";
    $this->salida .="       </form>";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }

/**
* La funcion que visualiza el detalle de la solicitud de un paciente
* @return boolean
* @param array datos de al solicitud de despacho de medicamentos o insumos
* @param array datos de ubicacion de la bodega a la que se hizo la solicitud
* @param string nombre de la empresa en la que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
* @param string nombre de la estacion que hace la solicitud
* @param adte fecha de realizacion de la solicitud
*/

	function FrmAtenderSolicitudPaciente($SolicitudId,$Ingreso,$EstacionId,$NombreEstacion,$Fecha,$usuarioestacion,$nombrepac,
		$tipo_id_paciente,$paciente_id,$cama){

	  $tipoSolicitud=$this->GetTipoSolicitudBodega($SolicitudId);
		if($tipoSolicitud['tipo_solicitud']=='M'){
      $Vector = $this->GetMedicamentosSolicitud($SolicitudId);
		  $palabra='MEDICAMENTOS';
		}elseif($tipoSolicitud['tipo_solicitud']=='Z'){
      $Vector = $this->GetMezclasSolicitud($SolicitudId);
		  $palabra='MEZCLAS';
		}elseif($tipoSolicitud['tipo_solicitud']=='I'){
      $Vector = $this->GetInsumosSolicitud($SolicitudId);
			$palabra='INSUMOS';
		}
		//pRINT_R($Vector);
		if(!$Vector){
			$mensaje = "NO SE ENCONTRARON MEDICAMENTOS EN LA SOLICITUD SELECCIONADA";
			$titulo = "DETALLE DOCUMENTO BODEGA";
			$accion = ModuloGetURL('app','InvBodegas','user','LlamaSoliciMedica');
			$boton = "REGRESAR";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}else{
			//ordenar por solicitud
			foreach($Vector as $key=>$value){
			$datosOrdenados[$value[solicitud_id]][$key] = $value; //echo "<br>--><br>"; print_r($value);
		  }
			$this->salida .= themeAbrirTabla('SOLICITUDES DE '.' '.$palabra);
			$this->salida .="<script>\n\n";
			$this->salida.="  function buscaCampos(campo) {\n";
			$this->salida.="    var i=0;var j=0;";
			$this->salida.="    while(!i){";
			$this->salida.="      if(document.Solicitud.elements[j].name!=campo){";
			$this->salida.="        j++;";
			$this->salida.="      }else{";
			$this->salida.="       return(j);";
      $this->salida.="      }";
			$this->salida.="    } \n";
			$this->salida.="    return (-1);\n";
			$this->salida.="  }\n\n";
			$this->salida.="  function getSelectedOptions(obj){\n";
			$this->salida.="    var selectedOptions = new Array(); \n";
			$this->salida.="    for(i = 0; i < obj.options.length; i++) {\n";
			$this->salida.="    if (obj.options[i].selected) {\n";
			$this->salida.="      selectedOptions.push(obj.options[i]);\n}\n";
			$this->salida.="    }\n";
			$this->salida.="    return selectedOptions;\n";
			$this->salida.="  }\n";
			$this->salida.="  function Change_Valor(obj,pos){\n";
			$this->salida.="   var ValSelect='SelectMedicamentos'+pos;\n";
			$this->salida.="   var PosCantExistente=0;\n";
			$this->salida.="   var PosSelect=parseInt(buscaCampos('SelectMedicamentos['+pos+']'),10); \n";
			$this->salida.="   var CodigoPodructo = document.Solicitud.elements[PosSelect].options[document.Solicitud.elements[PosSelect].selectedIndex].value;\n";
			$i=0;
			foreach($datosOrdenados as $key=>$value){
				foreach($value as $keyMed => $ValueMed){
				  if($tipoSolicitud['tipo_solicitud']!='I'){
				    $MedSimilares = $this->GetMedicamentosSimilares($ValueMed[medicamento_id],$ValueMed[cant_solicitada]);
					}
					$this->salida .= "PosCantExistente=parseInt(buscaCampos('CantExistente$i'),10);\n";
					if(sizeof($MedSimilares)){
						$this->salida.="if (pos==$i){\n";
						for($j=0; $j<sizeof($MedSimilares); $j++){
							$this->salida.="if(CodigoPodructo=='".$MedSimilares[$j][codigo_producto]."'){\n";
							$ff=$this->GetCantidadExistenteBodega($MedSimilares[$j][codigo_producto]);
							$this->salida .="obj.elements[PosCantExistente].value=".$ff['existencia'].";";
							$this->salida.="}\n";
						}
						$this->salida.="}\n";
					}
					$i++;
				}
			}
			$this->salida .= "}\n";
			$this->salida .= "  function ValidaSolicitud(forma){\n";
			$this->salida .= "    var Cantidad=parseInt(forma.ContadorJs.value,10);\n";
			$this->salida .= "    var PosCantSol=parseInt(buscaCampos('CantSolicitada[]'),10); \n";
			$this->salida .= "    var PosCantDes=buscaCampos('CantDespachar[]'); \n";
			$this->salida .= "    var PosCant=PosCantSol;\n";
			$this->salida .= "    var vector=new Array();\n";
			$this->salida .= "    for(i=0;i<=Cantidad;i++){\n";
			$this->salida .= "      vector[i]=PosCant;\n";
			$this->salida .= "      PosCant+=4;\n";
			$this->salida .= "    }\n";
			$this->salida .= "		for (i=0;i<Cantidad;i++) {\n";
			$this->salida .= "			if(forma.elements[vector[i]].name!='CantSolicitada[]') {\n";//}alert (forma.elements[vector[i]+1].name+'**-'+forma.elements[vector[i]+1].value); \n";
			$this->salida .= "				if(forma.elements[vector[i]+3].checked){";
			$this->salida .= "				    if(forma.elements[vector[i]+2].value<=0){";
      $this->salida .= "					    alert('Verifique la cantidad a despachar no puede ser menor o igual a 0');";
			$this->salida .= "					    forma.elements[vector[i]+2].focus();\n";
			$this->salida .= "					    return false; \n";
      $this->salida .= "					  }";
			$this->salida .= "				    if(parseInt(forma.elements[vector[i]+2].value,10)>parseInt(forma.elements[vector[i]+1].value,10)){\n";
			$this->salida .= "					    alert('Verifique la cantidad a despachar no puede ser mayor a la Solicitada');";
			$this->salida .= "					    forma.elements[vector[i]+2].focus();\n";
			$this->salida .= "					    return false; \n";
      $this->salida .= "					  }";
      $this->salida .= "				    if(parseInt(forma.elements[vector[i]+2].value,10)>parseInt(forma.elements[vector[i]].value,10)){\n";
			$this->salida .= "					    alert('Verifique la cantidad a despachar no puede ser mayor a las Existencias en Bodega');";
			$this->salida .= "					    forma.elements[vector[i]+2].focus();\n";
			$this->salida .= "					    return false; \n";
      $this->salida .= "					  }";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else{\n";
			$this->salida .= "				if(forma.elements[vector[i]+2].checked){";
			$this->salida .= "				    if(forma.elements[vector[i]+1].value<=0){";
      $this->salida .= "					    alert('Verifique la cantidad a despachar no puede ser menor o igual a 0');";
			$this->salida .= "              forma.elements[vector[i]+1].focus();\n";
			$this->salida .= "					    return false; \n";
      $this->salida .= "					  }";
			$this->salida .= "				    if(parseInt(forma.elements[vector[i]+1].value,10)>parseInt(forma.elements[vector[i]].value,10)){\n";
			$this->salida .= "					    alert('Verifique la cantidad a despachar no puede ser mayor a la Solicitada');";
			$this->salida .= "              forma.elements[vector[i]+1].focus();\n";
			$this->salida .= "					    return false; \n";
      $this->salida .= "					  }";
      $this->salida .= "				    if(parseInt(forma.elements[vector[i]+1].value,10)>parseInt(forma.elements[vector[i]-1].value,10)){\n";
			$this->salida .= "					    alert('Verifique la cantidad a despachar no puede ser mayor a las Existencias en Bodega');";
			$this->salida .= "              forma.elements[vector[i]+1].focus();\n";
			$this->salida .= "					    return false; \n";
      $this->salida .= "					  }";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "    return true;\n";
			$this->salida .= "  }\n";
			$this->salida .= "  function Seleccionartodos(frm,x){";
      $this->salida .= "    if(x==true){";
			$this->salida .= "      for(i=0;i<frm.elements.length;i++){";
			$this->salida .= "        if(frm.elements[i].type=='checkbox'){";
			$this->salida .= "          frm.elements[i].checked=true";
			$this->salida .= "        }";
			$this->salida .= "      }";
			$this->salida .= "    }else{";
			$this->salida .= "      for(i=0;i<frm.elements.length;i++){";
			$this->salida .= "        if(frm.elements[i].type=='checkbox'){";
			$this->salida .= "          frm.elements[i].checked=false";
			$this->salida .= "        }";
			$this->salida .= "      }";
			$this->salida .= "    }\n";
			$this->salida .= "  }\n";
			$this->salida .= "</script>\n";
			$action = ModuloGetURL('app','InvBodegas','user','DespacharMedicamentos',array("SolicitudId"=>$SolicitudId,"Ingreso"=>$Ingreso,"EstacionId"=>$EstacionId,"NombreEstacion"=>$NombreEstacion,"Fecha"=>$Fecha,"usuarioestacion"=>$usuarioestacion,"TipoSolicitud"=>$tipoSolicitud['tipo_solicitud'],
			"nombrepac"=>$nombrepac,"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"cama"=>$cama));
			$this->salida .= "<form name='Solicitud' action='$action' method='POST' onsubmit=\"return ValidaSolicitud(this);\">\n";
			$this->Encabezado();
			$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .= "        <tr><td width=\"100%\">";
			$this->salida .= "        <fieldset><legend class=\"field\">DOCUMENTO SOLICITUD MEDICAMENTO</legend>";
			$this->salida .= "          <table class=\"normal_10\"cellspacing=\"2\" cellpadding=\"3\"border=\"0\"  width=\"95%\" align=\"center\">";
			$this->salida .= "	          <tr><td></td></tr>";
			$this->salida .= "	          <tr class=\"modulo_list_claro\">";
			$this->salida .= "	          <td width=\"20%\"><label class=\"label\">SOLICITANTE</td>";
			$this->salida .= "	          <td colspan=\"3\">$NombreEstacion</td>";
			$this->salida .= "	          </tr>";
			$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
			$this->salida .= "	          <td width=\"20%\"><label class=\"label\">FECHA SOLICITUD</td>";
      (list($fecha,$HoraTot)=explode(' ',$Fecha));
      (list($ano,$mes,$dia)=explode('-',$fecha));
      (list($hora,$min)=explode(':',$HoraTot));
      $this->salida .= "            <td width=\"45%\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";			
			$this->salida .= "	          <td width=\"20%\"><label class=\"label\">CODIGO SOLICITUD</td>";
			$this->salida .= "	          <td width=\"15%\">$SolicitudId</td>";
			$this->salida .= "	          </tr>";
			$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
			$this->salida .= "	          <td width=\"20%\"><label class=\"label\">PACIENTE</td>";
			$this->salida .= "	          <td width=\"45%\">$tipo_id_paciente - $paciente_id  $nombrepac</td>";
			$this->salida .= "	          <td width=\"20%\"><label class=\"label\">CAMA</td>";
			$this->salida .= "	          <td width=\"15%\">$cama</td>";
			$this->salida .= "	          </tr>";
			$this->salida .= "	          <tr><td></td></tr>";
			$this->salida .= "		      <input type=\"hidden\" name=\"EstacionId\" value=\"$EstacionId\" >";
			$this->salida .= "		      <input type=\"hidden\" name=\"NombreEstacion\" value=\"$NombreEstacion\" >";
			$this->salida .= "		      <input type=\"hidden\" name=\"Fecha\" value=\"$Fecha\" >";
			$this->salida .= "			    </table>";
			$this->salida .= "		     </fieldset></td><BR>";
			$this->salida .= "       </table><BR><BR>";
			$this->salida .= "			<table width=\"85%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			//$this->salida .= "					<td>OBSERVACIONES</td>\n";
			if($tipoSolicitud['tipo_solicitud']=='Z'){
			if($ValueMed[mezcla_recetada_id]){
        $this->salida .= "					<td>MEZCLA</td>\n";
			}
			}
			$this->salida .= "					<td>MEDICAMENTO</td>\n";
			$this->salida .= "					<td>CANT <BR> EXISTENTE</td>\n";
			$this->salida .= "					<td>CANT <BR> SOLICITADA</td>\n";
			$this->salida .= "					<td>CANT A<BR> DESPACHAR</td>\n";
			$this->salida .= "					<td><input type=\"checkbox\" name=\"selectodo\" onclick=\"Seleccionartodos(this.form,this.checked)\"></td>\n";
			$this->salida .= "				</tr>\n";
			$l = $i = 0;
			foreach($datosOrdenados as $key=>$value){//print_r($value);
				$contadorRowSpan = sizeof($value);
				foreach($value as $keyMed => $ValueMed){
				  if(($l++) % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
          $CodigoProd=$ValueMed[medicamento_id];
					$this->salida .= "				<tr align=\"center\" class=\"$estilo\">\n";
					$Existencia=$this->GetCantidadExistenteBodega($ValueMed[medicamento_id]);
					$cantidadExistente=$Existencia['existencia'];
					if($tipoSolicitud['tipo_solicitud']!='I'){
					  $MedSimilares = $this->GetMedicamentosSimilares($ValueMed[medicamento_id],$ValueMed[cant_solicitada]);
					}else{
            unset($MedSimilares);
          }
          if($contadorRowSpan == sizeof($value)){
					  //$this->salida .= "					<td rowspan=\"".sizeof($value)."\"><textarea name=\"observaciones\" class=\"'textarea\"></textarea></td>\n";
					}
					if($cantidadExistente){
					if($tipoSolicitud['tipo_solicitud']=='Z'){
					if($ValueMed[mezcla_recetada_id] && $ValueMed[mezcla_recetada_id]!=$mezclaAnterior){
              $contadorRowSpan1=$this->rowspanMezclas($SolicitudId,$ValueMed[mezcla_recetada_id]);
						  $this->salida .= "					<td rowspan=\"".$contadorRowSpan1['contador']."\">".$ValueMed[mezcla_recetada_id]."</td>\n";
							$mezclaAnterior=$ValueMed[mezcla_recetada_id];
					}
					}
					if(sizeof($MedSimilares)<2 || !$MedSimilares){
						$this->salida .= "					<td>".$ValueMed[medicamento_id]." => ".$ValueMed[nommedicamento]." ".$ValueMed[ff]."</td>\n";
					}else{
						$this->salida .= "					<td>\n";
						$this->salida .= "						<select name=\"SelectMedicamentos[".$i."]\" class=\"select\" onchange=\"Change_Valor(this.form,$i);\">\n";
						for($j=0; $j<sizeof($MedSimilares); $j++){
							if($MedSimilares[$j][codigo_producto] == $ValueMed[medicamento_id]){ $selected = "selected"; } else{$selected = ""; }
							$this->salida .= "						<option value=\"".$MedSimilares[$j][codigo_producto]."\" $selected >".$MedSimilares[$j][codigo_producto]." => ".$MedSimilares[$j][nommedicamento]." ".$MedSimilares[$j][concentracion]." ".$MedSimilares[$j][ff]."</option> \n";
						}
						$this->salida .= "						</select>\n";
						$this->salida .= "					</td>\n";
					}
					$this->salida .= "					<td><input type=\"text\" name=\"CantExistente$i\" class=\"input-text\" value=\"".$cantidadExistente."\" size=\"10\" READONLY></td>\n";
					$this->salida .= "					<td><input type=\"hidden\" name=\"CantSolicitada[]\" value=\"".$ValueMed[cant_solicitada]."\">".$ValueMed[cant_solicitada]."</td>\n";
          if($ValueMed[cant_solicitada] <= $cantidadExistente){
            $cantidad=$ValueMed[cant_solicitada];
					}else{
					  if($cantidadExistente<0){
						  $cantidad=0;
						}else{
              $cantidad=$cantidadExistente;
						}
					}
					$this->salida .= "					<td><input type=\"text\" name=\"CantDespachar[]\" value=\"$cantidad\" size='10' class=\"input-text\"></td>\n";
					$this->salida .= "					<td><input type=\"checkbox\" name=\"CheckDespachar[]\" value=\"".$ValueMed[solicitud_id].".-.".$ValueMed[medicamento_id].".-.".$cantidad.".-.".$i.".-.".$CodigoProd.".-.".$ValueMed[consecutivo_d]."\"></td>\n";
					$contadorRowSpan--;
					$i++;
					}else{
					$this->salida .= "					<td>".$ValueMed[medicamento_id]." => ".$ValueMed[nommedicamento]." ".$ValueMed[ff]."</td>";
          $this->salida .= "					<td colspan=\"4\" class=\"label_error\">PRODUCTO NO EXISTE EN LA BODEGA</td>";
					$contadorRowSpan--;
					}
					$this->salida .= "				</tr>\n";
				}
			}
			$contadorJs=$i;
			$this->salida .= "			<input type=\"hidden\" name=\"ContadorJs\" value=\"$contadorJs\">\n";
			$this->salida .= "			</table><BR>\n";
			$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "         <tr><td align=\"center\">";
      $this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\">&nbsp&nbsp;";
			$this->salida .= "         <input type=\"submit\" name=\"DESPACHAR\" value=\"DESPACHAR\" class=\"input-submit\">&nbsp&nbsp;";
			$this->salida .= "         <input type=\"reset\" name=\"reset\" value=\"REESTABLECER\" class=\"input-submit\">&nbsp&nbsp;";
			$this->salida .= "         </td></tr>";
			$this->salida .= "       </table><BR>";
			$this->salida .= "	</form>\n";
			$this->salida .= themeCerrarTabla();
			return true;
		}
	}

	function ConfirmacionDespachoPendientes($datos,$CantDespachar,$datos_bodega,$concepto,$Ingreso,$SolicitudId,$TipoSolicitud,$EstacionId,$NombreEstacion,$Fecha,$usuarioestacion,
		$nombrepac,$tipo_id_paciente,$paciente_id,$cama,$pendiente,$cancelar,$motivoCancelacion,$observaciones){

    $this->salida .= themeAbrirTabla('CONFIRMACION DE SOLICITUDES DE DESPACHO');
		$action=ModuloGetURL('app','InvBodegas','user','ConfirmarDespachoSolicitudes',array("datos"=>$datos,"CantDespachar"=>$CantDespachar,"datos_bodega"=>$datos_bodega,"concepto"=>$concepto,"Ingreso"=>$Ingreso,"SolicitudId"=>$SolicitudId,
		"TipoSolicitud"=>$TipoSolicitud,"EstacionId"=>$EstacionId,"NombreEstacion"=>$NombreEstacion,"Fecha"=>$Fecha,"usuarioestacion"=>$usuarioestacion,
		"nombrepac"=>$nombrepac,"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"cama"=>$cama));
		$this->salida .= "<form name='Solicitud' action='$action' method='POST'>\n";
		$this->salida .= "<script>";
		$this->salida .= "function chequearCancelacion(frm,valor,indice){";
    $this->salida .= "  if(valor==true){";
		$this->salida .= "    frm.elements[indice-1].checked=false;";
    $this->salida .= "    frm.elements[indice].checked=true;";
		$this->salida .= "    frm.elements[indice+1].disabled=false;";
		$this->salida .= "    frm.elements[indice+2].disabled=false;";
		$this->salida .= "  }";
    $this->salida .= "}";

		$this->salida .= "function chequearPendiente(frm,valor,indice){";
    $this->salida .= "  if(valor==true){";
    $this->salida .= "    var suma=indice+1;";
		$this->salida .= "    frm.elements[indice].checked=true;";
    $this->salida .= "    frm.elements[indice+1].checked=false;";
		$this->salida .= "    frm.elements[indice+2].disabled=true;";
		$this->salida .= "    frm.elements[indice+3].disabled=true;";
		$this->salida .= "  }";
		$this->salida .= "}";
		$this->salida .= "function EliminaObservacion(valor){";
    $this->salida .= "  window.location.href='".ModuloGetUrl('app','InvBodegas','user','ConfirmarDespachoSolicitudes',array("datos"=>$_REQUEST['datos'],"CantDespachar"=>$_REQUEST['CantDespachar'],"datos_bodega"=>$_REQUEST['datos_bodega'],"concepto"=>$_REQUEST['concepto'],"Ingreso"=>$_REQUEST['Ingreso'],"SolicitudId"=>$_REQUEST['SolicitudId'],"TipoSolicitud"=>$_REQUEST['TipoSolicitud'],"EstacionId"=>$_REQUEST['EstacionId'],"NombreEstacion"=>$_REQUEST['NombreEstacion'],"Fecha"=>$_REQUEST['Fecha'],"usuarioestacion"=>$_REQUEST['usuarioestacion'],
		"nombrepac"=>$_REQUEST['nombrepac'],"tipo_id_paciente"=>$_REQUEST['tipo_id_paciente'],"paciente_id"=>$_REQUEST['paciente_id'],"cama"=>$_REQUEST['cama'],"pendiente"=>$_REQUEST['pendiente'],"cancelar"=>$_REQUEST['cancelar'],"motivoCancelacion"=>$_REQUEST['motivoCancelacion'],"observaciones"=>$_REQUEST['observaciones']))."&observacion_elimina='+valor;\n";
    $this->salida .= "}";
		$this->salida .= "function EditarObservacion(valor){";
		$this->salida .= "  window.location.href='".ModuloGetUrl('app','InvBodegas','user','LlamaEditarDespachoPendientes',array("datos"=>$_REQUEST['datos'],"CantDespachar"=>$_REQUEST['CantDespachar'],"datos_bodega"=>$_REQUEST['datos_bodega'],"concepto"=>$_REQUEST['concepto'],"Ingreso"=>$_REQUEST['Ingreso'],"SolicitudId"=>$_REQUEST['SolicitudId'],"TipoSolicitud"=>$_REQUEST['TipoSolicitud'],"EstacionId"=>$_REQUEST['EstacionId'],"NombreEstacion"=>$_REQUEST['NombreEstacion'],"Fecha"=>$_REQUEST['Fecha'],"usuarioestacion"=>$_REQUEST['usuarioestacion'],
		"nombrepac"=>$_REQUEST['nombrepac'],"tipo_id_paciente"=>$_REQUEST['tipo_id_paciente'],"paciente_id"=>$_REQUEST['paciente_id'],"cama"=>$_REQUEST['cama'],"pendiente"=>$_REQUEST['pendiente'],"cancelar"=>$_REQUEST['cancelar'],"motivoCancelacion"=>$_REQUEST['motivoCancelacion'],"observaciones"=>$_REQUEST['observaciones']))."&editar='+valor;\n";
    $this->salida .= "}";
		$this->salida .= "function InsertarObservacion(){";
    $this->salida .= "  window.location.href='".ModuloGetUrl('app','InvBodegas','user','LlamaEditarDespachoPendientes',array("datos"=>$_REQUEST['datos'],"CantDespachar"=>$_REQUEST['CantDespachar'],"datos_bodega"=>$_REQUEST['datos_bodega'],"concepto"=>$_REQUEST['concepto'],"Ingreso"=>$_REQUEST['Ingreso'],"SolicitudId"=>$_REQUEST['SolicitudId'],"TipoSolicitud"=>$_REQUEST['TipoSolicitud'],"EstacionId"=>$_REQUEST['EstacionId'],"NombreEstacion"=>$_REQUEST['NombreEstacion'],"Fecha"=>$_REQUEST['Fecha'],"usuarioestacion"=>$_REQUEST['usuarioestacion'],
		"nombrepac"=>$_REQUEST['nombrepac'],"tipo_id_paciente"=>$_REQUEST['tipo_id_paciente'],"paciente_id"=>$_REQUEST['paciente_id'],"cama"=>$_REQUEST['cama'],"pendiente"=>$_REQUEST['pendiente'],"cancelar"=>$_REQUEST['cancelar'],"motivoCancelacion"=>$_REQUEST['motivoCancelacion'],"observaciones"=>$_REQUEST['observaciones']))."';\n";
    $this->salida .= "}";
		$this->salida .= "</script>";
		$this->Encabezado();
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "        <tr><td width=\"100%\">";
		$this->salida .= "        <fieldset><legend class=\"field\">DOCUMENTO SOLICITUD MEDICAMENTO</legend>";
		$this->salida .= "          <table class=\"normal_10\"cellspacing=\"2\" cellpadding=\"3\"border=\"0\"  width=\"95%\" align=\"center\">";
		$this->salida .= "	          <tr><td></td></tr>";
		$this->salida .= "	          <tr class=\"modulo_list_claro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">SOLICITANTE</td>";
		$this->salida .= "	          <td colspan=\"3\">$NombreEstacion</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">FECHA SOLICITUD</td>";
    (list($fecha,$HoraTot)=explode(' ',$Fecha));
    (list($ano,$mes,$dia)=explode('-',$fecha));
    (list($hora,$min)=explode(':',$HoraTot));
    $this->salida .= "            <td width=\"45%\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";          		
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">CODIGO SOLICITUD</td>";
		$this->salida .= "	          <td width=\"15%\">$SolicitudId</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">PACIENTE</td>";
		$this->salida .= "	          <td width=\"45%\">$tipo_id_paciente - $paciente_id  $nombrepac</td>";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">CAMA</td>";
		$this->salida .= "	          <td width=\"15%\">$cama</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr><td></td></tr>";
		$this->salida .= "			    </table>";
		$this->salida .= "		     </fieldset></td><BR>";
		$this->salida .= "       </table><BR><BR>";
		$this->salida .= "<table width=\"95%\" border=\"0\" align=\"center\"> \n";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
		$this->salida .= "</table>";
    $vars=$this->ConfirmacionDespachoDetalleSolicitud($SolicitudId,$TipoSolicitud);
		if($vars){
		  $this->salida .= "			<table width=\"95%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td width=\"30%\" nowrap>MEDICAMENTO/INSUMO</td>\n";
			$this->salida .= "					<td width=\"8%\" nowrap>CANT <BR> SOLICITADA</td>\n";
			$this->salida .= "					<td width=\"8%\" nowrap>CANT A<BR> DESPACHAR</td>\n";
      $this->salida .= "					<td width=\"8%\" nowrap>CANT <BR> PENDIENTE</td>\n";
			$this->salida .= "					<td width=\"8%\" nowrap>DEJAR <BR> PENDIENTE</td>\n";
			$this->salida .= "					<td width=\"8%\" nowrap>CANCELAR</td>\n";
			$this->salida .= "					<td width=\"15%\" nowrap>MOTIVO<br>CANCELACION</td>\n";
			$this->salida .= "					<td width=\"15%\" nowrap>OBSERVACIONES</td>\n";
			$this->salida .= "				</tr>\n";
			$numElement=1;
			for($i=0;$i<sizeof($vars);$i++){
			  $che='';
				$che1='';
				$d='';
				if(array_key_exists($vars[$i]['codigo_producto'],$cancelar)){
					$che='checked';
					$che1='';
				}else{
          $d='disabled';
					$che='';
					$che1='checked';
				}
			  $continue=1;
			  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "        <tr class=\"$estilo\">";
        $cantidadDespachar=0;
				foreach($datos as $solici=>$vector){
				  if($continue==1){
						foreach($vector as $indice=>$datosTol){
							if($vars[$i]['codigo_producto']==$datosTol[4] && $vars[$i]['consecutivo_d']==$datosTol[5]){
                $contador=$datosTol[3];
								$cantidadDespachar=$CantDespachar[$contador];
								$continue=0;
								if($datosTol[4]!=$datosTol[1]){
                  $equivalente=$datosTol[1];
								}
								break;
							}
						}
					}
				}
				$this->salida .= "				<td>";
				$this->salida .= "				".$vars[$i]['codigo_producto']." => ".$vars[$i]['descripcion']."";
				if(!empty($equivalente)){
					$nombre=$this->DescripcionProductoInv($equivalente);
					$this->salida .= "				<BR><b>Equivalente:</b>&nbsp&nbsp&nbsp;<label class=\"label_error\">".$equivalente." => ".$nombre['descripcion']."</label>";
				}
				$this->salida .= "				</td>\n";
				$this->salida .= "				<td>".$vars[$i]['cantidad']."</td>\n";
				$this->salida .= "				<td>".$cantidadDespachar."</td>\n";
				$Cantpendiente=$vars[$i]['cantidad']-$cantidadDespachar;
				$this->salida .= "				<td>".$Cantpendiente."</td>\n";
				if($Cantpendiente>0){
          $this->salida .= "				<td align=\"center\"><input $che1 type=\"radio\" name=\"pendiente[".$vars[$i]['codigo_producto']."]\" onclick=\"chequearPendiente(this.form,this.checked,$numElement)\" value=\"".$Cantpendiente."||//".$vars[$i]['evolucion_id']."||//".$vars[$i]['ingreso']."\"></td>\n";
					$numElement++;
          $this->salida .= "				<td align=\"center\"><input $che type=\"radio\" name=\"cancelar[".$vars[$i]['codigo_producto']."]\" onclick=\"chequearCancelacion(this.form,this.checked,$numElement)\" value=\"".$Cantpendiente."||//".$vars[$i]['evolucion_id']."||//".$vars[$i]['ingreso']."\"></td>\n";
					$numElement++;
					$this->salida .= "       <td><select $d name=\"motivoCancelacion[".$vars[$i]['codigo_producto']."]\" class=\"select\">";
					$motivos=$this->MotivosCancelacionDespacho();
					$motivoCancelacionU='';
					if($motivoCancelacion[$vars[$i]['codigo_producto']]!=-1){
            $motivoCancelacionU=$motivoCancelacion[$vars[$i]['codigo_producto']];
					}
					$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
					for($cont=0;$cont<sizeof($motivos);$cont++){
						if($motivos[$cont]['motivo_id']==$motivoCancelacionU){
						  $this->salida .=" <option value=\"".$motivos[$cont]['motivo_id']."\" selected>".substr($motivos[$cont]['descripcion'], 0, 30)."</option>";
						}else{
							$this->salida .=" <option value=\"".$motivos[$cont]['motivo_id']."\">".substr($motivos[$cont]['descripcion'], 0, 30)."</option>";
						}
					}
					$this->salida .= "      </select></td>";
					$numElement++;
					$val='';
					if(!empty($observaciones[$vars[$i]['codigo_producto']])){
            $val=$observaciones[$vars[$i]['codigo_producto']];
					}
					$this->salida .= "			<td><textarea $d rows=\"2\" cols=\"10\" name=\"observaciones[".$vars[$i]['codigo_producto']."]\" class=\"'textarea\">$val</textarea></td>\n";
          $numElement++;
				}else{
					$this->salida .= "				<td align=\"center\">&nbsp;</td>\n";
					$this->salida .= "				<td align=\"center\">&nbsp;</td>\n";
					$this->salida .= "				<td align=\"center\">&nbsp;</td>\n";
					$this->salida .= "				<td align=\"center\">&nbsp;</td>\n";
				}
				$this->salida .= "				</tr>\n";
				$y++;
			}
			$this->salida .= "			</table>";
      $this->salida .= "			<table width=\"95%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
      $this->salida .= "			<tr><td align=\"right\"><input type=\"submit\" class=\"input-submit\" name=\"confirmar\" value=\"CONFIRMAR\"></td></tr>";
			$this->salida .= "			</table><BR>";
		}
		$observaciones=$this->registrosObservacionesSolicitud($SolicitudId);
		if($observaciones){
			$this->salida .= "			<table width=\"95%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
      $this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td>&nbsp;</td>\n";
			$this->salida .= "				<td>OBSERVACIONES</td>\n";
      $this->salida .= "			</tr>";
			for($i=0;$i<sizeof($observaciones);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "			<tr class=\"$estilo\">";
        $this->salida .= "			<td width=\"30%\" >";
        $this->salida .= "			  <table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
        $this->salida .= "			  <tr class=\"$estilo\">";
				$this->salida .= "			  <td class=\"label\">USUARIO</td>";
				$this->salida .= "			  <td>".$observaciones[$i]['usuario']."</td>";
				$this->salida .= "			  </tr>";
				(list($fecha,$HoraTot)=explode(' ',$observaciones[$i]['fecha_registro']));
				(list($ano,$mes,$dia)=explode('-',$fecha));
				(list($hora,$min)=explode(':',$HoraTot));
				$this->salida .= "			  <tr class=\"$estilo\">";
				$this->salida .= "			  <td class=\"label\">FECHA CREACION</td>";
				$this->salida .= "			  <td>".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
				$this->salida .= "			  </tr>";
				(list($fecha,$HoraTot)=explode(' ',$observaciones[$i]['fecha_ultima_modificacion']));
				(list($ano,$mes,$dia)=explode('-',$fecha));
				(list($hora,$min)=explode(':',$HoraTot));
				$this->salida .= "			  <tr class=\"$estilo\">";
				$this->salida .= "			  <td class=\"label\">ULTIMA MODIFICACION</td>";
				$this->salida .= "			  <td>".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
				$this->salida .= "			  </tr>";
				if($observaciones[$i]['propio']){
        $this->salida .= "			  <tr class=\"$estilo\"><td>";
        $this->salida .= "			  <a href=\"javascript:EliminaObservacion(".$observaciones[$i]['observacion_id'].")\"><img title=\"Eliminar\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a>&nbsp&nbsp&nbsp;";
				$this->salida .= "			  <a href=\"javascript:EditarObservacion(".$observaciones[$i]['observacion_id'].")\"><img title=\"Modificar\" border=\"0\" src=\"".GetThemePath()."/images/pmodificar.png\"></a>";
				$this->salida .= "			  </td></tr>";
				}
				$this->salida .= "			  </table>";
        $this->salida .= "			</td>";
				$this->salida .= "			<td>".$observaciones[$i]['observacion']."</td>";
				$this->salida .= "			</tr>";
				$y++;
			}
			$this->salida .= "			</table>";
		}
		$this->salida .= "			<table width=\"95%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "			<tr>\n";
		$this->salida .= "				<td algin=\"left\" class=\"label\"><a href=\"javascript:InsertarObservacion()\">NUEVA OBSERVACION</a></td>\n";
		$this->salida .= "			</tr>";
		$this->salida .= "			</table>";

		$this->salida .= "	</form>\n";
    $action=ModuloGetURL('app','InvBodegas','user','DetalleSolicitudMedicamento',array("datos"=>$datos,"CantDespachar"=>$CantDespachar,"datos_bodega"=>$datos_bodega,"Ingreso"=>$Ingreso,"SolicitudId"=>$SolicitudId,
		"TipoSolicitud"=>$TipoSolicitud,"EstacionId"=>$EstacionId,"NombreEstacion"=>$NombreEstacion,"Fecha"=>$Fecha,"usuarioestacion"=>$usuarioestacion,
		"nombrepac"=>$nombrepac,"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"cama"=>$cama));
		$this->salida .= "<form name='Solicitud' action='$action' method='POST'>\n";
		$this->salida .= "			<table width=\"95%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "			<tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "			</table>";
    $this->salida .= "	</form>\n";
		$this->salida .= themeCerrarTabla();
		return true;
	}


	function DevolucionesSolicitudesDpto($departamento,$descripcionDpto,$Lotes,$codigoProducto,$descripcionProd,$cantidad){
    $action=ModuloGetURL('app','InvBodegas','user','RealizarDevolucionMedicamentosDpto',array("departamento"=>$departamento,"descripcionDpto"=>$descripcionDpto));
		$this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= themeAbrirTabla('DEVOLUCION DE MEDICAMENTOS E INSUMOS POR DEPARTAMENTO');
		$this->Encabezado();
		$this->salida .= "    <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "    <tr><td width=\"40%\" class=\"modulo_table_list_title\" align=\"center\"><b>DEPARTAMENTO</b></td>";
		$this->salida .= "    <td class=\"modulo_list_claro\" align=\"center\"><b>".$descripcionDpto."</b></td></tr>";
    $this->salida .= "		</table><BR>";
		$this->salida .= "<table width=\"95%\" border=\"0\" align=\"center\"> \n";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
		$this->salida .= "</table>";
		$ProductosTotalDevolucion=$this->ProductosTotalesDevolucion($departamento);
    if($ProductosTotalDevolucion){
		$this->salida .= "			<table width=\"95%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "					<td>PRODUCTO</td>\n";
		$this->salida .= "					<td nowrap width=\"10%\">CANTIDAD DEVOLUCION</td>\n";
		$this->salida .= "					<td nowrap width=\"5%\">LOTES</td>\n";
		$this->salida .= "			    <td nowrap width=\"13%\">FECHA VENCIMIENTO</td>";
		$this->salida .= "			    <td nowrap width=\"13%\">LOTE</td>";
		$this->salida .= "			    <td nowrap width=\"14%\">CANTIDAD LOTE</td>";
		$this->salida .= "			    <td nowrap width=\"5%\">&nbsp;</td>";
		$this->salida .= "				</tr>";
		for($i=0;$i<sizeof($ProductosTotalDevolucion);$i++){
      if($y % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
			$this->salida .= "       <tr class=\"$estilo\">";
			$this->salida .= "				<td>".$ProductosTotalDevolucion[$i]['codigo_producto']." => ".$ProductosTotalDevolucion[$i]['descripcion']."</td>\n";
			$entero=(int)($ProductosTotalDevolucion[$i]['cantidad']);
      if($ProductosTotalDevolucion[$i]['cantidad']%$entero){
        $this->salida .= "				<td>".$ProductosTotalDevolucion[$i]['cantidad']."</td>\n";
			}else{
        $this->salida .= "				<td>".$entero."</td>\n";
			}
			if(($ProductosTotalDevolucion[$i]['sw_control_fecha_vencimiento']==1) && ($sumaTotal['suma']<$ProductosTotalDevolucion[$i]['cantidad'])){
				$actionLotes=ModuloGetURL('app','InvBodegas','user','LlamaDevolucionesSolicitudesDpto',array("departamento"=>$departamento,"descripcionDpto"=>$descripcionDpto,"Lotes"=>1,"codigoProducto"=>$ProductosTotalDevolucion[$i]['codigo_producto'],"descripcionProd"=>$ProductosTotalDevolucion[$i]['descripcion'],"cantidad"=>$ProductosTotalDevolucion[$i]['cantidad']));
				$this->salida .= "     <td align=\"center\"><a href=\"$actionLotes\"><img border=\"0\" src=\"".GetThemePath()."/images/pparamed.png\"></a></td>";
      }else{
				$this->salida .= "     <td align=\"center\">&nbsp;</td>";
			}
			$Datos=$this->FechasLotesProductosDevolDpto($departamento,$ProductosTotalDevolucion[$i]['codigo_producto']);
			if($Datos){
				$this->salida .= "     <td align=\"center\" colspan=\"3\">";
				$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\">";
				for($j=0;$j<sizeof($Datos);$j++){
					$this->salida .= "	      <tr class=\"$estilo1\">\n";
					$this->salida .= "	      <td width=\"32%\">".$Datos[$j]['fecha_vencimiento']."</td>";
					$this->salida .= "	      <td width=\"35%\">".$Datos[$j]['lote']."</td>";
          $entero=(int)($Datos[$j]['cantidad']);
					if($Datos[$j]['cantidad']%$entero){
						$this->salida .= "	      <td>".$Datos[$j]['cantidad']."</td>";
					}else{
						$this->salida .= "				<td>".$entero."</td>\n";
					}
					$ElimFechaV=ModuloGetURL('app','InvBodegas','user','LlamaEliminarFechaVDevol',array("departamento"=>$departamento,"descripcionDpto"=>$descripcionDpto,"codigoProducto"=>$ProductosTotalDevolucion[$i]['codigo_producto'],"Cantidad"=>$Datos[$j]['cantidad'],"consecutivo"=>$Datos[$j]['consecutivo'],"FechaVencimiento"=>$Datos[$j]['fecha_vencimiento'],"Lote"=>$Datos[$j]['lote'],"destino"=>1));
					$this->salida .= "	      <td width=\"5%\"><a href=\"$ElimFechaV\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
					$this->salida .= "	      </tr>";
					$totalCan+=$Datos[$j]['cantidad'];
				}
				$this->salida .= "	     </table>";
				$this->salida .= "</td>";
			}else{
			  $this->salida .= "				<td colspan=\"3\"></td>\n";
			}
			$this->salida .= "				<td align=\"center\"><input type=\"checkbox\" name=\"checkboxDevol[".$ProductosTotalDevolucion[$i]['codigo_producto']."]\" value=\"".$ProductosTotalDevolucion[$i]['cantidad']."||//".$totalCan."\"></td>\n";
      $this->salida .= "				</tr>";
		}
		$this->salida .= "			</table>";
		$this->salida .= "    <table border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "    <tr><td align=\"right\"><b><input class=\"input-submit\" type=\"submit\" name=\"Confirmar\" value=\"CONFIRMAR\"></b></td></tr>";
    $this->salida .= "		</table><BR>";
		$this->salida .="     </form>";
		}
		if($Lotes==1){
		  $actionDos=ModuloGetURL('app','InvBodegas','user','InsertarFechaVencimientoLoteDevolDpto',array("departamento"=>$departamento,"descripcionDpto"=>$descripcionDpto,"codigoProducto"=>$codigoProducto,"descripcionProd"=>$descripcionProd,"cantidad"=>$cantidad));
		  $this->salida .= "       <form name=\"formaUno\" action=\"$actionDos\" method=\"post\">";
			$sumaTotal=$this->SumaFechasLotesProductosDevolDpto($departamento,$codigoProducto);
			$cantidadFalta=$cantidad-$sumaTotal['suma'];
		  $this->salida .= "          <table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "          <tr><td width=\"100%\">";
			$this->salida .= "          <fieldset><legend class=\"field\">DATOS DEL PRODUCTO</legend>";
			$this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "	        <tr><td></td></tr>";
			$this->salida .= "         <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">$codigoProducto&nbsp&nbsp&nbsp;$descripcionProd</td></tr>";
			$this->salida .= "         <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">CANTIDAD QUE FALTA PARA ALCANZAR EL TOTAL&nbsp&nbsp&nbsp;$cantidadFalta</td></tr>";
			$this->salida .= "	        <tr class=\"modulo_list_claro\">";
			$this->salida .= "	        <td class=\"label\">FECHA VENCIMIENTO</td>";
      $this->salida .= "	  	    <td align=\"center\"><input type=\"text\" name=\"fechaVencimiento\" value=\"".$_REQUEST['fechaVencimiento']."\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
			$this->salida .= "	  	    ".ReturnOpenCalendario('formaUno','fechaVencimiento','/')."</td>";
			$this->salida .= "	        <td class=\"label\">No. LOTE</td>";
			$this->salida .= "	        <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"lote\" value=\"".$_REQUEST['lote']."\"></td>";
			$this->salida .= "	        <td class=\"label\">CANTIDAD</td>";
			$this->salida .= "	        <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"cantidadLote\" value=\"".$_REQUEST['cantidadLote']."\"></td>";
			$this->salida .= "	        <tr><td></td></tr>";
      $this->salida .= "	        <tr><td colspan=\"6\" align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"CANCELAR\">";
			$this->salida .= "	        <input type=\"submit\" class=\"input-submit\" name=\"insertar\" value=\"INSERTAR\"></td></tr>";
			$this->salida .= "			     </table>";
			$this->salida .= "		      </fieldset></td><BR>";
			$this->salida .= "         </table><BR>";
			$this->salida .="       </form>";
		}
		$action=moduloGetURL('app','InvBodegas','user','FormaDevolucionMedicamentos',array("departamento"=>$departamento,"descripcionDpto"=>$descripcionDpto));
    $this->salida .= "<form name='form' action='$action' method='POST'>\n";
		$this->salida .= "			<table width=\"95%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "			<tr><td align=\"center\"><input type=\"submit\" name=\"Confirmar\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
    $this->salida .= "			</table>";
		$this->salida .= "</form>";
		$this->salida .= themeCerrarTabla();
		return true;
	}


	function ConfirmacionDespachoPendientesDpto($datos,$CantDespachar,$departamento,$descripcionDpto,$concepto){

		$action = ModuloGetURL('app','InvBodegas','user','GuardaDespachoMedDepartamentoConfirmacion',array("datos"=>$datos,"CantDespachar"=>$CantDespachar,"departamento"=>$departamento,"descripcionDpto"=>$descripcionDpto,"concepto"=>$concepto));
		$this->salida .= "<form name='Solicitud' action='$action' method='POST'>\n";
		$this->salida .= "<script>";
		$this->salida .= "function chequearCancelacion(frm,valor,indice){";
		//$this->salida .="   for(i=0;i<frm.elements.length;i++){\n";
		//$this->salida .="     alert(frm.elements[i].type);\n";
		//$this->salida .="   }\n";
    $this->salida .= "  if(frm.elements[indice].checked){";
		$this->salida .= "    frm.elements[indice-1].checked=false;";
		$this->salida .= "    frm.elements[indice+1].disabled=false;";
		$this->salida .= "    frm.elements[indice+2].disabled=false;";
		$this->salida .= "  }";
    $this->salida .= "}";
		$this->salida .= "function chequearPendiente(frm,valor,indice){";
    $this->salida .= "  if(frm.elements[indice].checked){";
    $this->salida .= "    frm.elements[indice+1].checked=false;";
		$this->salida .= "    frm.elements[indice+2].disabled=true;";
		$this->salida .= "    frm.elements[indice+3].disabled=true;";
		$this->salida .= "  }";
		$this->salida .= "}";
    $this->salida .= "</script>";
		$this->salida .= themeAbrirTabla('CONFIRMACION DE SOLICITUDES DE DESPACHO POR DEPARTAMENTO');
		$this->Encabezado();
		$this->salida .= "    <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "    <tr><td width=\"40%\" class=\"modulo_table_list_title\" align=\"center\"><b>DEPARTAMENTO</b></td>";
		$this->salida .= "    <td class=\"modulo_list_claro\" align=\"center\"><b>".$descripcionDpto."</b></td></tr>";
    $this->salida .= "		</table><BR>";
		$this->salida .= "<table width=\"95%\" border=\"0\" align=\"center\"> \n";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
		$this->salida .= "</table>";
		$numElement=0;
    if($datos){
		foreach($datos as $SolicitudId=>$vector){
		  foreach($vector as $indice=>$Datos){
			  if($SolicitudIdAnt!=$SolicitudId){
				$vars=$this->ConfirmacionDespachoDetalleSolicitud($SolicitudId,$Datos[6]);
				if($vars){
					$this->salida .= "			<table width=\"95%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "					<td colspan=\"8\">SOLICITUD No.:&nbsp&nbsp&nbsp;$SolicitudId</td>\n";
          $this->salida .= "				</tr>\n";
					$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "					<td width=\"30%\" nowrap>MEDICAMENTO/INSUMO</td>\n";
					$this->salida .= "					<td width=\"8%\" nowrap>CANT <BR> SOLICITADA</td>\n";
					$this->salida .= "					<td width=\"8%\" nowrap>CANT A<BR> DESPACHAR</td>\n";
					$this->salida .= "					<td width=\"8%\" nowrap>CANT <BR> PENDIENTE</td>\n";
					$this->salida .= "					<td width=\"8%\" nowrap>DEJAR <BR> PENDIENTE</td>\n";
					$this->salida .= "					<td width=\"8%\" nowrap>CANCELAR</td>\n";
					$this->salida .= "					<td width=\"15%\" nowrap>MOTIVO<br>CANCELACION</td>\n";
					$this->salida .= "					<td width=\"15%\" nowrap>OBSERVACIONES</td>\n";
					$this->salida .= "				</tr>\n";
					for($i=0;$i<sizeof($vars);$i++){
						$che='';
						$che1='';
						$d='';
						if(array_key_exists($vars[$i]['codigo_producto'],$cancelar)){
							$che='checked';
							$che1='';
						}else{
							$d='disabled';
							$che='';
							$che1='checked';
						}
						$continue=1;
						if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
						$this->salida .= "        <tr class=\"$estilo\">";
						$cantidadDespachar=0;
						foreach($datos as $solici=>$vector){
						  if($SolicitudId==$solici){
								if($continue==1){
									foreach($vector as $indice=>$datosTol){
										if($vars[$i]['codigo_producto']==$datosTol[4]){
											$contador=$datosTol[3];
											$cantidadDespachar=$CantDespachar[$contador];
											$continue=0;
											$equivalente='';
											if($datosTol[4]!=$datosTol[1]){
												$equivalente=$datosTol[1];
											}
											break;
										}
									}
								}
							}
						}
						$this->salida .= "				<td>";
						$this->salida .= "				".$vars[$i]['codigo_producto']." => ".$vars[$i]['descripcion']."";
						if(!empty($equivalente)){
							$nombre=$this->DescripcionProductoInv($equivalente);
							$this->salida .= "				<BR><b>Equivalente:</b>&nbsp&nbsp&nbsp;<label class=\"label_error\">".$equivalente." => ".$nombre['descripcion']."</label>";
						}
						$this->salida .= "				</td>\n";
						$this->salida .= "				<td>".$vars[$i]['cantidad']."</td>\n";
						$this->salida .= "				<td>".$cantidadDespachar."</td>\n";
						$Cantpendiente=$vars[$i]['cantidad']-$cantidadDespachar;
						$this->salida .= "				<td>".$Cantpendiente."</td>\n";
						if($Cantpendiente>0){
							$this->salida .= "				<td align=\"center\"><input $che1 type=\"radio\" name=\"pendiente[".$SolicitudId."||//".$vars[$i]['codigo_producto']."]\" onclick=\"chequearPendiente(this.form,this.checked,$numElement)\" value=\"".$Cantpendiente."||//".$vars[$i]['evolucion_id']."\"></td>\n";
							$numElement++;
							$this->salida .= "				<td align=\"center\"><input $che type=\"radio\" name=\"cancelar[".$SolicitudId."||//".$vars[$i]['codigo_producto']."]\" onclick=\"chequearCancelacion(this.form,this.checked,$numElement)\" value=\"".$Cantpendiente."||//".$vars[$i]['evolucion_id']."\"></td>\n";
							$numElement++;
							$this->salida .= "       <td><select $d name=\"motivoCancelacion[".$SolicitudId."||//".$vars[$i]['codigo_producto']."]\" class=\"select\">";
							$motivos=$this->MotivosCancelacionDespacho();
							$motivoCancelacionU='';
							if($motivoCancelacion[$vars[$i]['codigo_producto']]!=-1){
								$motivoCancelacionU=$motivoCancelacion[$vars[$i]['codigo_producto']];
							}
							$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
							for($cont=0;$cont<sizeof($motivos);$cont++){
								if($motivos[$cont]['motivo_id']==$motivoCancelacionU){
									$this->salida .=" <option value=\"".$motivos[$cont]['motivo_id']."\" selected>".substr($motivos[$cont]['descripcion'], 0, 30)."</option>";
								}else{
									$this->salida .=" <option value=\"".$motivos[$cont]['motivo_id']."\">".substr($motivos[$cont]['descripcion'], 0, 30)."</option>";
								}
							}
							$this->salida .= "      </select></td>";
							$numElement++;
							$val='';
							if(!empty($observaciones[$vars[$i]['codigo_producto']])){
								$val=$observaciones[$vars[$i]['codigo_producto']];
							}
							$this->salida .= "			<td><textarea $d rows=\"2\" cols=\"10\" name=\"observaciones[".$SolicitudId."||//".$vars[$i]['codigo_producto']."]\" class=\"'textarea\">$val</textarea></td>\n";
							$numElement++;
						}else{
							$this->salida .= "				<td align=\"center\">&nbsp;</td>\n";
							$this->salida .= "				<td align=\"center\">&nbsp;</td>\n";
							$this->salida .= "				<td align=\"center\">&nbsp;</td>\n";
							$this->salida .= "				<td align=\"center\">&nbsp;</td>\n";
						}
					}
					$this->salida .= "			</table><BR>";
					}
					$SolicitudIdAnt=$SolicitudId;
				}
			}
		}
		$this->salida .= "			<table width=\"95%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "			<tr><td align=\"right\"><input type=\"submit\" name=\"Confirmar\" value=\"CONFIRMAR\" class=\"input-submit\"></td></tr>";
    $this->salida .= "			</table>";
		}
		$this->salida .= "</form>";
		$action=moduloGetURL('app','InvBodegas','user','LlamaDespachoSolicitudesDpto',array("departamento"=>$departamento,"descripcionDpto"=>$descripcionDpto));
    $this->salida .= "<form name='Solicitud' action='$action' method='POST'>\n";
		$this->salida .= "			<table width=\"95%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "			<tr><td align=\"center\"><input type=\"submit\" name=\"Confirmar\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
    $this->salida .= "			</table>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	/**
	* La funcion que visualiza el detalle de la solicitud de un paciente
	* @return boolean
	*/
	function DespachoSolicitudesDpto($departamento,$descripcionDpto){
	  $this->salida .= themeAbrirTabla('DESPACHO SOLICITUDES DE MEDICAMENTOS E INSUMOS POR DEPARTAMENTO');
		$this->salida .="<script>\n\n";
		$this->salida.="  function buscaCampos(campo) {\n";
		$this->salida.="    var i=0;var j=0;";
		$this->salida.="    while(!i){";
		$this->salida.="      if(document.Solicitud.elements[j].name!=campo){";
		$this->salida.="        j++;";
		$this->salida.="      }else{";
		$this->salida.="       return(j);";
		$this->salida.="      }";
		$this->salida.="    } \n";
		$this->salida.="    return (-1);\n";
		$this->salida.="  }\n\n";

    $this->salida.="  function getSelectedOptions(obj){\n";
		$this->salida.="    var selectedOptions = new Array(); \n";
		$this->salida.="    for(i = 0; i < obj.options.length; i++) {\n";
		$this->salida.="    if (obj.options[i].selected) {\n";
		$this->salida.="      selectedOptions.push(obj.options[i]);\n}\n";
		$this->salida.="    }\n";
		$this->salida.="    return selectedOptions;\n";
		$this->salida.="  }\n";
		$this->salida.="  function Change_Valor(obj,pos,Solici){\n";
		$this->salida.="   var ValSelect='SelectMedicamentos'+pos;\n";
		$this->salida.="   var PosCantExistente=0;\n";
		$this->salida.="   var PosSelect=parseInt(buscaCampos('SelectMedicamentos['+pos+']'),10); \n";
		$this->salida.="   var CodigoPodructo = document.Solicitud.elements[PosSelect].options[document.Solicitud.elements[PosSelect].selectedIndex].value;\n";
		$Datos=$this->DatosSolicitudesDepartamento($departamento);
		$i=0;
    foreach($Datos as $paciente=>$vector){
      foreach($vector as $solicitudId=>$vector1){
        $this->salida.="if (Solici==$solicitudId){\n";
        foreach($vector1 as $consecutivoId=>$datos){
          if($datos['tipo_solicitud']!='I'){
						$MedSimilares = $this->GetMedicamentosSimilares($datos[codigo_producto],$datos[cant_solicitada]);
					}
          $this->salida .= "PosCantExistente=parseInt(buscaCampos('CantExistente$i'),10);\n";
          if(sizeof($MedSimilares)){
					  $this->salida.="if (pos==$i){\n";
						for($j=0; $j<sizeof($MedSimilares); $j++){
							$this->salida.="if(CodigoPodructo=='".$MedSimilares[$j][codigo_producto]."'){\n";
							$ff=$this->GetCantidadExistenteBodega($MedSimilares[$j][codigo_producto]);
							$this->salida .="obj.elements[PosCantExistente].value=".$ff['existencia'].";";
							$this->salida.="}\n";
						}
						$this->salida.="}\n";
					}
					$i++;
				}
				$this->salida.="}";
			}
		}
		$this->salida .= "}\n";
		$this->salida .= "  function ValidaSolicitud(forma){\n";
		$this->salida .= "    var Cantidad=parseInt(forma.ContadorJs.value,10);\n";
		$this->salida .= "    var PosCantSol=parseInt(buscaCampos('CantSolicitada[]'),10); \n";
		$this->salida .= "    var PosCantDes=buscaCampos('CantDespachar[]'); \n";
		$this->salida .= "    var PosCant=PosCantSol;\n";
		$this->salida .= "    var vector=new Array();\n";
		$this->salida .= "    for(i=0;i<=Cantidad;i++){\n";
		$this->salida .= "      vector[i]=PosCant;\n";
		$this->salida .= "      PosCant+=4;\n";
		$this->salida .= "    }\n";
		$this->salida .= "		for (i=0;i<Cantidad;i++) {\n";
		$this->salida .= "			if(forma.elements[vector[i]].name!='CantSolicitada[]') {\n";//}alert (forma.elements[vector[i]+1].name+'**-'+forma.elements[vector[i]+1].value); \n";
		$this->salida .= "				if(forma.elements[vector[i]+3].checked){";
		$this->salida .= "				    if(forma.elements[vector[i]+2].value<=0){";
		$this->salida .= "					    alert('Verifique la cantidad a despachar no puede ser menor o igual a 0');";
		$this->salida .= "					    forma.elements[vector[i]+2].focus();\n";
		$this->salida .= "					    return false; \n";
		$this->salida .= "					  }";
		$this->salida .= "				    if(parseInt(forma.elements[vector[i]+2].value,10)>parseInt(forma.elements[vector[i]+1].value,10)){\n";
		$this->salida .= "					    alert('Verifique la cantidad a despachar no puede ser mayor a la Solicitada');";
		$this->salida .= "					    forma.elements[vector[i]+2].focus();\n";
		$this->salida .= "					    return false; \n";
		$this->salida .= "					  }";
		$this->salida .= "				    if(parseInt(forma.elements[vector[i]+2].value,10)>parseInt(forma.elements[vector[i]].value,10)){\n";
		$this->salida .= "					    alert('Verifique la cantidad a despachar no puede ser mayor a las Existencias en Bodega');";
		$this->salida .= "					    forma.elements[vector[i]+2].focus();\n";
		$this->salida .= "					    return false; \n";
		$this->salida .= "					  }";
		$this->salida .= "				}\n";
		$this->salida .= "			}\n";
		$this->salida .= "			else{\n";
		$this->salida .= "				if(forma.elements[vector[i]+2].checked){";
		$this->salida .= "				    if(forma.elements[vector[i]+1].value<=0){";
		$this->salida .= "					    alert('Verifique la cantidad a despachar no puede ser menor o igual a 0');";
		$this->salida .= "              forma.elements[vector[i]+1].focus();\n";
		$this->salida .= "					    return false; \n";
		$this->salida .= "					  }";
		$this->salida .= "				    if(parseInt(forma.elements[vector[i]+1].value,10)>parseInt(forma.elements[vector[i]].value,10)){\n";
		$this->salida .= "					    alert('Verifique la cantidad a despachar no puede ser mayor a la Solicitada');";
		$this->salida .= "              forma.elements[vector[i]+1].focus();\n";
		$this->salida .= "					    return false; \n";
		$this->salida .= "					  }";
		$this->salida .= "				    if(parseInt(forma.elements[vector[i]+1].value,10)>parseInt(forma.elements[vector[i]-1].value,10)){\n";
		$this->salida .= "					    alert('Verifique la cantidad a despachar no puede ser mayor a las Existencias en Bodega');";
		$this->salida .= "              forma.elements[vector[i]+1].focus();\n";
		$this->salida .= "					    return false; \n";
		$this->salida .= "					  }";
		$this->salida .= "				}\n";
		$this->salida .= "			}\n";
		$this->salida .= "		}\n";
		$this->salida .= "    return true;\n";
		$this->salida .= "  }\n";
		$this->salida .= "  function Seleccionartodos(frm,x){";
		$this->salida .= "    if(x==true){";
		$this->salida .= "      for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "        if(frm.elements[i].type=='checkbox'){";
		$this->salida .= "          frm.elements[i].checked=true";
		$this->salida .= "        }";
		$this->salida .= "      }";
		$this->salida .= "    }else{";
		$this->salida .= "      for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "        if(frm.elements[i].type=='checkbox'){";
		$this->salida .= "          frm.elements[i].checked=false";
		$this->salida .= "        }";
		$this->salida .= "      }";
		$this->salida .= "    }\n";
		$this->salida .= "  }\n";
		$this->salida .= "</script>\n";
    $action = ModuloGetURL('app','InvBodegas','user','DespacharMedicamentosDepartamento',array("departamento"=>$departamento,"descripcionDpto"=>$descripcionDpto));
		$this->salida .= "<form name='Solicitud' action='$action' method='POST' onsubmit=\"return ValidaSolicitud(this);\">\n";
		$Datos=$this->DatosSolicitudesDepartamento($departamento);
		$this->Encabezado();
		$this->salida .= "    <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr><td width=\"40%\" class=\"modulo_table_list_title\" align=\"center\"><b>DEPARTAMENTO</b></td>";
		$this->salida .= "      <td class=\"modulo_list_claro\" align=\"center\"><b>".$descripcionDpto."</b></td></tr>";
    $this->salida .= "		</table><BR>";

		if($Datos){
		$this->salida .= "			<table width=\"90%\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"1\"> \n";
		$i=0;
		foreach($Datos as $paciente=>$vector){
		  $pacienteAnt=-1;
      foreach($vector as $solicitudId=>$vector1){
			  $solicitudIdAnt=-1;
        foreach($vector1 as $consecutivoId=>$datos){
					if($paciente!=$pacienteAnt){
            $pacienteAnt=$paciente;
						$this->salida .= "			<tr><td colspan=\"2\">";
						$this->salida .= "		  <BR><BR><table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"1\"> \n";
						if(!$ban){
            $this->salida .= "			<tr class=\"modulo_table_list_title\"><td colspan=\"2\" align=\"right\">SELECCIONAR TODOS&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"SeleccionTodos\" onclick=\"Seleccionartodos(this.form,this.checked)\"></td></tr>";
						$ban=1;
						}
						$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
						$this->salida .= "			<td width=\"80%\">PACIENTE</td>\n";
						$this->salida .= "			<td width=\"20%\">PIEZA - CAMA</td>\n";
						$this->salida .= "			</tr>";
						$this->salida .= "			<tr class=\"modulo_list_claro\">";
            $this->salida .= "			<td>$paciente ".$datos['nombrepac']."</td>\n";
						$this->salida .= "			<td align=\"center\">".$datos['pieza']."&nbsp&nbsp&nbsp;".$datos['cama']."</td>\n";
						$this->salida .= "			</tr>";
            $this->salida .= "			</table>";
						$this->salida .= "			</td></tr>";
						if($solicitudId!=$solicitudIdAnt){
						  $solicitudIdAnt=$solicitudId;
							$this->salida .= "			<tr><td colspan=\"2\">";
							$this->salida .= "			<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"1\"> \n";
							$this->salida .= "			<tr class=\"modulo_table_title\">";
							$this->salida .= "			<td width=\"20%\">No. SOLICITUD</td>\n";
							$this->salida .= "			<td width=\"30%\">FECHA</td>\n";
							$this->salida .= "			<td width=\"30%\">ESTACION</td>\n";
							$this->salida .= "			<td width=\"20%\">USUARIO</td>\n";
							$this->salida .= "			</tr>";
							$this->salida .= "			<tr class=\"modulo_list_claro\">";
							$this->salida .= "			<td width=\"20%\">".$solicitudId."</td>\n";
							$this->salida .= "			<td width=\"30%\">".$datos['fecha_solicitud']."</td>\n";
							$cadenaestacion=substr($datos['estacion_id'].' - '.$datos['nomestacion'],0,31);
							$this->salida .= "			<td width=\"30%\">".$cadenaestacion."</td>\n";
							$cadenausuario=substr($datos['usuario_id'].' - '.$datos['usuarioestacion'],0,31);
							$this->salida .= "			<td width=\"20%\">".$cadenausuario."</td>\n";
							$this->salida .= "			</tr>";
							$this->salida .= "			</table>";
							$this->salida .= "			</td></tr>";

							$this->salida .= "			<tr><td colspan=\"2\">";
              $this->salida .= "			<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"1\"> \n";
							$this->salida .= "			<tr class=\"modulo_list_oscuro\">";
              $this->salida .= "			<td class=\"label\" align=\"center\" width=\"35%\">MEDICAMENTO</td>\n";
							$this->salida .= "			<td class=\"label\" align=\"center\" width=\"20%\">CANT EXISTENTE</td>\n";
							$this->salida .= "			<td class=\"label\" align=\"center\" width=\"20%\">CANT SOLICITADA</td>\n";
							$this->salida .= "			<td class=\"label\" align=\"center\" width=\"20%\">CANT A DESPACHAR</td>\n";
							$this->salida .= "			<td width=\"5%\">&nbsp;</td>\n";
              $this->salida .= "			</tr>";
							$this->salida .= "			<tr class=\"modulo_list_claro\">";
							if($datos['tipo_solicitud']!='I'){
								$MedSimilares = $this->GetMedicamentosSimilares($datos['codigo_producto'],$datos['cant_solicitada']);
							}else{
                unset($MedSimilares);
              }
							if(sizeof($MedSimilares)<2 || !$MedSimilares){
								$this->salida .= "					<td align=\"center\">".$datos['codigo_producto']." => ".$datos['desmed']."</td>\n";
							}else{
								$this->salida .= "					<td align=\"center\">\n";
								$this->salida .= "						<select name=\"SelectMedicamentos[".$i."]\" class=\"select\" onchange=\"Change_Valor(this.form,$i,$solicitudId);\">\n";
								for($j=0; $j<sizeof($MedSimilares); $j++){
									if($MedSimilares[$j]['codigo_producto'] == $datos['codigo_producto']){ $selected = "selected"; } else{$selected = ""; }
									$this->salida .= "						<option value=\"".$MedSimilares[$j]['codigo_producto']."\" $selected >".$MedSimilares[$j]['codigo_producto']." => ".$MedSimilares[$j]['nommedicamento']." ".$MedSimilares[$j]['concentracion']." ".$MedSimilares[$j]['ff']."</option> \n";
								}
								$this->salida .= "						</select>\n";
								$this->salida .= "					</td>\n";
							}
							$cantidadExistente=$datos['existencia'];
							$this->salida .= "			<td align=\"center\"><input type=\"text\" name=\"CantExistente$i\" class=\"input-text\" value=\"".$cantidadExistente."\" size=\"10\" READONLY></td>\n";
					    $this->salida .= "			<td align=\"center\"><input type=\"hidden\" name=\"CantSolicitada[]\" value=\"".$datos['cant_solicitada']."\">".$datos['cant_solicitada']."</td>\n";
							if($datos['cant_solicitada'] <= $cantidadExistente){
								$cantidad=$datos['cant_solicitada'];
							}else{
								if($cantidadExistente<0){
									$cantidad=0;
								}else{
									$cantidad=$cantidadExistente;
								}
							}
							$this->salida .= "			<td align=\"center\"><input type=\"text\" name=\"CantDespachar[]\" value=\"$cantidad\" size='10' class=\"input-text\"></td>\n";
					    $this->salida .= "			<td align=\"center\"><input type=\"checkbox\" name=\"CheckDespachar[]\" value=\"".$datos['solicitud_id'].".-.".$datos['codigo_producto'].".-.".$cantidad.".-.".$i.".-.".$datos['codigo_producto'].".-.".$datos['consecutivo_d'].".-.".$datos['tipo_solicitud']."\"></td>\n";
							$this->salida .= "			</tr>";
							$this->salida .= "			</table>";
							$this->salida .= "			</td></tr>";
						}else{
						  $this->salida .= "			<tr><td colspan=\"2\">";
              $this->salida .= "			<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"1\"> \n";
							$this->salida .= "			<tr class=\"modulo_list_claro\">";
							if($datos['tipo_solicitud']!='I'){
								$MedSimilares = $this->GetMedicamentosSimilares($datos['codigo_producto'],$datos['cant_solicitada']);
							}else{
                unset($MedSimilares);
              }
							if(sizeof($MedSimilares)<2 || !$MedSimilares){
								$this->salida .= "					<td align=\"center\" width=\"35%\">".$datos['codigo_producto']." => ".$datos['desmed']."</td>\n";
							}else{
								$this->salida .= "					<td align=\"center\" width=\"35%\">\n";
								$this->salida .= "						<select name=\"SelectMedicamentos[".$i."]\" class=\"select\" onchange=\"Change_Valor(this.form,$i,$solicitudId);\">\n";
								for($j=0; $j<sizeof($MedSimilares); $j++){
									if($MedSimilares[$j]['codigo_producto'] == $datos['codigo_producto']){ $selected = "selected"; } else{$selected = ""; }
									$this->salida .= "						<option value=\"".$MedSimilares[$j]['codigo_producto']."\" $selected >".$MedSimilares[$j]['codigo_producto']." => ".$MedSimilares[$j]['nommedicamento']." ".$MedSimilares[$j]['concentracion']." ".$MedSimilares[$j]['ff']."</option> \n";
								}
								$this->salida .= "						</select>\n";
								$this->salida .= "					</td>\n";
							}
							$cantidadExistente=$datos['existencia'];
							$this->salida .= "			<td align=\"center\" width=\"20%\"><input type=\"text\" name=\"CantExistente$i\" class=\"input-text\" value=\"".$cantidadExistente."\" size=\"10\" READONLY></td>\n";
					    $this->salida .= "			<td align=\"center\" width=\"20%\"><input type=\"hidden\" name=\"CantSolicitada[]\" value=\"".$datos['cant_solicitada']."\">".$datos['cant_solicitada']."</td>\n";
							if($datos['cant_solicitada'] <= $cantidadExistente){
								$cantidad=$datos['cant_solicitada'];
							}else{
								if($cantidadExistente<0){
									$cantidad=0;
								}else{
									$cantidad=$cantidadExistente;
								}
							}
							$this->salida .= "			<td align=\"center\" width=\"20%\"><input type=\"text\" name=\"CantDespachar[]\" value=\"$cantidad\" size='10' class=\"input-text\"></td>\n";
					    $this->salida .= "			<td align=\"center\" width=\"5%\"><input type=\"checkbox\" name=\"CheckDespachar[]\" value=\"".$datos['solicitud_id'].".-.".$datos['codigo_producto'].".-.".$cantidad.".-.".$i.".-.".$datos['codigo_producto'].".-.".$datos['consecutivo_d'].".-.".$datos['tipo_solicitud']."\"></td>\n";
							$this->salida .= "			</tr>";
							$this->salida .= "			</table>";
							$this->salida .= "			</td></tr>";
						}
					}else{
					  if($solicitudId!=$solicitudIdAnt){
						  $solicitudIdAnt=$solicitudId;
							$this->salida .= "			<tr><td colspan=\"2\">";
							$this->salida .= "			<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"1\"> \n";
							$this->salida .= "			<tr class=\"modulo_table_title\">";
							$this->salida .= "			<td width=\"20%\">No. SOLICITUD</td>\n";
							$this->salida .= "			<td width=\"30%\">FECHA</td>\n";
							$this->salida .= "			<td width=\"30%\">ESTACION</td>\n";
							$this->salida .= "			<td width=\"20%\">USUARIO</td>\n";
							$this->salida .= "			</tr>";
							$this->salida .= "			<tr class=\"modulo_list_claro\">";
							$this->salida .= "			<td width=\"20%\">".$solicitudId."</td>\n";
							$this->salida .= "			<td width=\"30%\">".$datos['fecha_solicitud']."</td>\n";
							$cadenaestacion=substr($datos['estacion_id'].' - '.$datos['nomestacion'],0,31);
							$this->salida .= "			<td width=\"30%\">".$cadenaestacion."</td>\n";
							$cadenausuario=substr($datos['usuario_id'].' - '.$datos['usuarioestacion'],0,31);
							$this->salida .= "			<td width=\"20%\">".$cadenausuario."</td>\n";
							$this->salida .= "			</tr>";
							$this->salida .= "			</table>";
							$this->salida .= "			</td></tr>";

							$this->salida .= "			<tr><td colspan=\"2\">";
              $this->salida .= "			<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"1\"> \n";
							$this->salida .= "			<tr class=\"modulo_list_oscuro\">";
              $this->salida .= "			<td class=\"label\" align=\"center\" width=\"35%\">MEDICAMENTO</td>\n";
							$this->salida .= "			<td class=\"label\" align=\"center\" width=\"20%\">CANT EXISTENTE</td>\n";
							$this->salida .= "			<td class=\"label\" align=\"center\" width=\"20%\">CANT SOLICITADA</td>\n";
							$this->salida .= "			<td class=\"label\" align=\"center\" width=\"20%\">CANT A DESPACHAR</td>\n";
							$this->salida .= "			<td width=\"5%\">&nbsp;</td>\n";
              $this->salida .= "			</tr>";
							$this->salida .= "			<tr class=\"modulo_list_claro\">";
							if($datos['tipo_solicitud']!='I'){
								$MedSimilares = $this->GetMedicamentosSimilares($datos['codigo_producto'],$datos['cant_solicitada']);
							}else{
                unset($MedSimilares);
              }
							if(sizeof($MedSimilares)<2 || !$MedSimilares){
								$this->salida .= "					<td align=\"center\">".$datos['codigo_producto']." => ".$datos['desmed']."</td>\n";
							}else{
								$this->salida .= "					<td align=\"center\">\n";
								$this->salida .= "						<select name=\"SelectMedicamentos[".$i."]\" class=\"select\" onchange=\"Change_Valor(this.form,$i,$solicitudId);\">\n";
								for($j=0; $j<sizeof($MedSimilares); $j++){
									if($MedSimilares[$j]['codigo_producto'] == $datos['codigo_producto']){ $selected = "selected"; } else{$selected = ""; }
									$this->salida .= "						<option value=\"".$MedSimilares[$j]['codigo_producto']."\" $selected >".$MedSimilares[$j]['codigo_producto']." => ".$MedSimilares[$j]['nommedicamento']." ".$MedSimilares[$j]['concentracion']." ".$MedSimilares[$j]['ff']."</option> \n";
								}
								$this->salida .= "						</select>\n";
								$this->salida .= "					</td>\n";
							}
							$cantidadExistente=$datos['existencia'];
							$this->salida .= "			<td align=\"center\"><input type=\"text\" name=\"CantExistente$i\" class=\"input-text\" value=\"".$cantidadExistente."\" size=\"10\" READONLY></td>\n";
					    $this->salida .= "			<td align=\"center\"><input type=\"hidden\" name=\"CantSolicitada[]\" value=\"".$datos['cant_solicitada']."\">".$datos['cant_solicitada']."</td>\n";
							if($datos['cant_solicitada'] <= $cantidadExistente){
								$cantidad=$datos['cant_solicitada'];
							}else{
								if($cantidadExistente<0){
									$cantidad=0;
								}else{
									$cantidad=$cantidadExistente;
								}
							}
							$this->salida .= "			<td align=\"center\"><input type=\"text\" name=\"CantDespachar[]\" value=\"$cantidad\" size='10' class=\"input-text\"></td>\n";
					    $this->salida .= "			<td align=\"center\"><input type=\"checkbox\" name=\"CheckDespachar[]\" value=\"".$datos['solicitud_id'].".-.".$datos['codigo_producto'].".-.".$cantidad.".-.".$i.".-.".$datos['codigo_producto'].".-.".$datos['consecutivo_d'].".-.".$datos['tipo_solicitud']."\"></td>\n";
							$this->salida .= "			</tr>";
							$this->salida .= "			</table>";
							$this->salida .= "			</td></tr>";
						}else{
              $this->salida .= "			<tr><td colspan=\"2\">";
              $this->salida .= "			<table width=\"100%\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"1\"> \n";
							$this->salida .= "			<tr class=\"modulo_list_claro\">";
							if($datos['tipo_solicitud']!='I'){
								$MedSimilares = $this->GetMedicamentosSimilares($datos['codigo_producto'],$datos['cant_solicitada']);
							}else{
                unset($MedSimilares);
              }
							if(sizeof($MedSimilares)<2 || !$MedSimilares){
								$this->salida .= "					<td align=\"center\" width=\"35%\">".$datos['codigo_producto']." => ".$datos['desmed']."</td>\n";
							}else{
								$this->salida .= "					<td align=\"center\" width=\"35%\">\n";
								$this->salida .= "						<select name=\"SelectMedicamentos[".$i."]\" class=\"select\" onchange=\"Change_Valor(this.form,$i,$solicitudId);\">\n";
								for($j=0; $j<sizeof($MedSimilares); $j++){
									if($MedSimilares[$j]['codigo_producto'] == $datos['codigo_producto']){ $selected = "selected"; } else{$selected = ""; }
									$this->salida .= "						<option value=\"".$MedSimilares[$j]['codigo_producto']."\" $selected >".$MedSimilares[$j]['codigo_producto']." => ".$MedSimilares[$j]['nommedicamento']." ".$MedSimilares[$j]['concentracion']." ".$MedSimilares[$j]['ff']."</option> \n";
								}
								$this->salida .= "						</select>\n";
								$this->salida .= "					</td>\n";
							}
							$cantidadExistente=$datos['existencia'];
							$this->salida .= "			<td align=\"center\" width=\"20%\"><input type=\"text\" name=\"CantExistente$i\" class=\"input-text\" value=\"".$cantidadExistente."\" size=\"10\" READONLY></td>\n";
					    $this->salida .= "			<td align=\"center\" width=\"20%\"><input type=\"hidden\" name=\"CantSolicitada[]\" value=\"".$datos['cant_solicitada']."\">".$datos['cant_solicitada']."</td>\n";
							if($datos['cant_solicitada'] <= $cantidadExistente){
								$cantidad=$datos['cant_solicitada'];
							}else{
								if($cantidadExistente<0){
									$cantidad=0;
								}else{
									$cantidad=$cantidadExistente;
								}
							}
							$this->salida .= "			<td align=\"center\" width=\"20%\"><input type=\"text\" name=\"CantDespachar[]\" value=\"$cantidad\" size='10' class=\"input-text\"></td>\n";
					    $this->salida .= "			<td align=\"center\" width=\"5%\"><input type=\"checkbox\" name=\"CheckDespachar[]\" value=\"".$datos['solicitud_id'].".-.".$datos['codigo_producto'].".-.".$cantidad.".-.".$i.".-.".$datos['codigo_producto'].".-.".$datos['consecutivo_d'].".-.".$datos['tipo_solicitud']."\"></td>\n";
							$this->salida .= "			</tr>";
							$this->salida .= "			</table>";
							$this->salida .= "			</td></tr>";
						}
					}
					$i++;
				}
			}
		}
		$contadorJs=$i;
		$this->salida .= "			<input type=\"hidden\" name=\"ContadorJs\" value=\"$contadorJs\">\n";
		$this->salida .= "			</table>";
		$this->salida .= "			<table width=\"90%\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"1\"> \n";
		$this->salida .= "			<tr>";
		$this->salida .= "			<td align=\"right\"><input type=\"submit\" name=\"Despachar\" value=\"DESPACHAR\" class=\"input-submit\"></td>\n";
		$this->salida .= "			</tr>";
		$this->salida .= "			</table>";
    }else{
      $this->salida .= "			<table width=\"90%\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"1\"> \n";
			$this->salida .= "			<tr>";
			$this->salida .= "			<td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS</td>\n";
			$this->salida .= "			</tr>";
			$this->salida .= "			</table>";
		}
		$this->salida .= "     </form>";
		$action=ModuloGetURL('app','InvBodegas','user','LlamaSoliciMedica');
    $this->salida .= "     <form name='Forma' action='$action' method='POST'>\n";
		$this->salida .= "			<table width=\"90%\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"1\"> \n";
		$this->salida .= "			<tr>";
		$this->salida .= "			<td align=\"center\"><input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\"></td>\n";
		$this->salida .= "			</tr>";
		$this->salida .= "			</table>";
		$this->salida .= "     </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
* La funcion que visualiza las solicitudes de devolucion realizadas a al bodega
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa en la que se esta trabajando
* @param string centro de utilidad en el que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string bodega en la que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
*/

	function FormaDevolucionMedicamentos(){

		$this->salida .= ThemeAbrirTabla('DEVOLUCIONES MEDICAMENTOS E INSUMOS');
		$actionReg=ModuloGetURL('app','InvBodegas','user','RetornarFormaMenuDevoluciones');
		$this->salida .= "       <form name=\"forma\" action=\"$actionReg\" method=\"post\">";
		$this->Encabezado();
		$devoluciones=$this->DevolucionesMedicamentos();
		if($devoluciones){
      $this->salida .= "       <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "        <tr class=\"modulo_table_list_title\">";
			$this->salida .= "          <td width=\"30%\" nowrap align=\"center\"><b>DEPARTAMENTO</b></td>";
			$this->salida .= "          <td width=\"29%\" nowrap align=\"center\"><b>ESTACION<br>DEVUELVE</b></td>";
      $this->salida .= "          <td width=\"20%\" nowrap align=\"center\"><b>FECHA</b></td>";
			$this->salida .= "          <td width=\"13%\" nowrap align=\"center\"><b>CODIGO</b></td>";
			$this->salida .= "          <td width=\"8%\" nowrap align=\"center\"></td>";
			//$this->salida .= "          <td align=\"center\">IMPRIMIR</td>";
			$y=0;
      $rep= new GetReports();
			foreach($devoluciones as $departamento => $vector){
        if($y % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
			  $this->salida .= "	 <tr class=\"$estilo\">\n";
        (list($dpto,$descripcionDpto)=explode('-',$departamento));
				$this->salida .= "	 <td>";
				$this->salida .= "	 <table width=\"100%\" >";
        $this->salida .= "	  <tr class=\"$estilo\"><td>$descripcionDpto</td></tr>";
				$this->salida .= "	  <tr class=\"$estilo\"><td align=\"right\">";
				$action=ModuloGetURL('app','InvBodegas','user','ImprimirTotalesDevolucionesDpto',array("departamento"=>$dpto,"descripcionDpto"=>$descripcionDpto));
				$this->salida .= "	 <a title=\"Imprimir Totales de las Devoluciones\" href=\"$action\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\"><a>";
				$action1=ModuloGetURL('app','InvBodegas','user','LlamaDevolucionesSolicitudesDpto',array("departamento"=>$dpto,"descripcionDpto"=>$descripcionDpto));
        $this->salida .= "	 <a title=\"Recibir Todas las Solicitudes de Devolucion\" href=\"$action1\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/producto.png\"><a>";
				$action=ModuloGetURL('app','InvBodegas','user','ImprimirDevolucionesMedTotalDpto',array("departamento"=>$dpto,"descripcionDpto"=>$descripcionDpto));
				$this->salida .= "	 <a title=\"Imprimir Todas las Devoluciones\" href=\"$action\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\"><a>";
				$this->salida .= "	 </td></tr>";

        $this->salida .= "	  <tr class=\"$estilo\"><td align=\"left\">";
        $mostrar=$rep->GetJavaReport('app','InvBodegas','SolicDevolucionesTotalizadas_html',array("departamento"=>$dpto,"descripcionDpto"=>$descripcionDpto),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
        $nombre_funcion=$rep->GetJavaFunction();
        $this->salida .=$mostrar;
				$this->salida .= "	 <BR><a class=\"Menu\" title=\"Imprimir Totales de las Devoluciones\"  href=\"javascript:$nombre_funcion\"><img border=\"0\" src=\"".GetThemePath()."/images/pcopiar.png\">&nbsp&nbsp;PDF</a>&nbsp&nbsp&nbsp&nbsp;";
        $mostrar1=$rep->GetJavaReport('app','InvBodegas','SolicitudesDevolucionesDpto_html',array("departamento"=>$dpto,"descripcionDpto"=>$descripcionDpto),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
        $nombre_funcion1=$rep->GetJavaFunction();
        $this->salida .=$mostrar1;
        $this->salida .= "	 <a class=\"Menu\" title=\"Imprimir Todas las Devoluciones\"  href=\"javascript:$nombre_funcion1\"><img border=\"0\" src=\"".GetThemePath()."/images/pcopiar.png\">&nbsp&nbsp;PDF";
        $this->salida .= "	 </td></tr>";

				$this->salida .= "	 </table>";
				$this->salida .= "	 </td>";
				$this->salida .= "	 <td colspan=\"4\">";
				$this->salida .= "       <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
        foreach($vector as $devolucionId => $Datos){
          $NombreEstacion=$this->NombreEstacion($Datos['estacion_id']);
					$this->salida .= "	 <tr class=\"$estilo1\">\n";
					$this->salida .= "	 <td width=\"43%\">".$NombreEstacion['descripcion']."</td>";					
					(list($fecha,$Hora)=explode(' ',$Datos['fecha']));					
					(list($ano,$mes,$dia)=explode('-',$fecha));
					(list($hora,$min)=explode(':',$Hora));															
					$this->salida .= "	 <td width=\"30%\">".ucfirst(strftime('%b %d de %Y %H:%M',mktime($hora,$min,0,$mes,$dia,$ano)))."</td>";
					$this->salida .= "	 <td width=\"20%\">".$Datos['documento']."</td>";
					$identificacion=$Datos['tipo_id_paciente'].' '.$Datos['paciente_id'];
					$action=ModuloGetURL('app','InvBodegas','user','DetalleDevolucionMedicamentos',array("EstacionId"=>$Datos['estacion_id'],"NombreEstacion"=>$NombreEstacion['descripcion'],"Documento"=>$Datos['documento'],"Fecha"=>$Datos['fecha'],"Ingreso"=>$Datos['ingreso'],"observacion"=>$Datos['observacion'],"identificacion"=>$identificacion,"nombrepac"=>$Datos['nombrepac'],"cama"=>$Datos['cama'],"pieza"=>$Datos['pieza'],"observaciones"=>$Datos['observacion'],"parametro"=>$Datos['parametro']));
				  $this->salida .= "	 <td width=\"10%\" align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a></td>";
					$action=ModuloGetURL('app','InvBodegas','user','ImprimirDevolucionIndividual',array("EstacionId"=>$Datos['estacion_id'],
					"NombreEstacion"=>$NombreEstacion['descripcion'],"documento"=>$Datos['documento'],
					"Fecha"=>$Datos['fecha'],"usuarioestacion"=>$Datos['usuarioestacion'],
					"usuarioId"=>$Datos['usuario_id'],"deptoestacion"=>$Datos['deptoestacion'],
					"rango"=>$Datos['rango'],"tipoafil"=>$Datos['tipo_afiliado_id'],
					"cama"=>$Datos['cama'],"pieza"=>$Datos['pieza'],"plan"=>$Datos['plan_descripcion'],"tipoidPac"=>$Datos['tipo_id_paciente'],
					"paciente"=>$Datos['paciente_id'],"nombrepac"=>$Datos['nombrepac']));
					$this->salida .= "	 <td align=\"center\"><a title=\"Imprimir\" href=\"$action\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/imprimir.png\"><a></td>";
					$this->salida .= "	 </tr>";
				}
				$this->salida .= "       </table>";
        $this->salida .= "	 </td>";
				$this->salida .= "	 </tr>";
				$y++;
			}
			$this->salida .= "   </table><BR>";
		}else{
      $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		  $this->salida .= "        <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON SOLICITUDES PENDIENTES</td></tr>";
      $this->salida .= "       </table><br>";
		}
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "        <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "       </table>";
		$this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
* La funcion que visualiza el detalle de una devolucion a la bodega
* @return boolean
* @param string empresa en la que se esta trabajando
* @param string nombre de la empresa en la que se esta trabajando
* @param string centro de utilidad en el que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string bodega en la que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
* @param string codigo de la estacion que realizo la solicitud de la devolucion
* @param string nombre de la estacion que realizo la solicitud de la devolucion
* @param date fecha de realizacion de la devolucion
* @param string codigo unico que identifica la solicitud de devolucion
* @param string codigo del ingreso del paciente
* @param string obeservaciones de la solicitud de devolucion
*/

	function FormaDetalleDevolucionMedicamentos($EstacionId,$NombreEstacion,$Fecha,$Documento,$Ingreso,$observaciones,$bandera,$codigoProducto,$descripcion,$Cantidad,$consecutivo,
	$identificacion,$nombrepac,$cama,$pieza,$parametro){

    $this->salida .= themeAbrirTabla('PRODUCTOS DE LA DEVOLUCIONES DE MEDICAMENTOS E INSUMOS');
		$this->salida .= "<SCRIPT>\n";
		$this->salida .= "function Seleccionartodos(frm,x){";
		$this->salida .= "  if(x==true){";
		$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
		$this->salida .= "        frm.elements[i].checked=true";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }else{";
		$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
		$this->salida .= "        frm.elements[i].checked=false";
		$this->salida .= "      }";
		$this->salida .= "    }";
		$this->salida .= "  }\n";
		$this->salida .= "}\n";
		$this->salida .= "</SCRIPT>\n";
    $action = ModuloGetURL('app','InvBodegas','user','RealizarDevolucionMedicamentos');
		$this->salida .= "       <form name='Solicitud' action='$action' method='POST' onsubmit=\"return ValidaSolicitud(this);\">\n";
		$this->Encabezado();
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "        <tr><td width=\"100%\">";
		$this->salida .= "        <fieldset><legend class=\"field\">DOCUMENTO DEVOLUCION MEDICAMENTO</legend>";
		$this->salida .= "          <table class=\"normal_10\" cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "	          <tr><td></td></tr>";
		$this->salida .= "	          <tr class=\"modulo_list_claro\">";
		$this->salida .= "	          <td width=\"15%\" ><label class=\"label\">PACIENTE</td>";
		$this->salida .= "	          <td>".$identificacion." ".$nombrepac."</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_claro\">";
		$this->salida .= "	          <td width=\"20%\" ><label class=\"label\">CAMA</td>";
		$this->salida .= "	          <td>".$cama." - ".$pieza."</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_claro\">";
		$this->salida .= "	          <td width=\"20%\" ><label class=\"label\">SOLICITANTE</td>";
		$this->salida .= "	          <td>$NombreEstacion</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">FECHA SOLICITUD</td>";
    (list($fecha,$HoraTot)=explode(' ',$Fecha));
    (list($ano,$mes,$dia)=explode('-',$fecha));
    (list($hora,$min)=explode(':',$HoraTot));
    $this->salida .= "            <td>".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";		
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_claro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">CODIGO</td>";
		$this->salida .= "	          <td>$Documento</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">OBSERVACIONES</td>";
		$this->salida .= "	          <td>$observaciones</td>\n";
		$this->salida .= "	          </tr>";
    $this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">PARAMETRO DE DEVOLUCION</td>";
		$this->salida .= "	          <td>$parametro</td>\n";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr><td></td></tr>";
		$this->salida .= "		      <input type=\"hidden\" name=\"EstacionId\" value=\"$EstacionId\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"NombreEstacion\" value=\"$NombreEstacion\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"Fecha\" value=\"$Fecha\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"Documento\" value=\"$Documento\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"Ingreso\" value=\"$Ingreso\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"observaciones\" value=\"$observaciones\" >";

		$this->salida .= "		      <input type=\"hidden\" name=\"identificacion\" value=\"$identificacion\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"nombrepac\" value=\"$nombrepac\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"cama\" value=\"$cama\" >";
		$this->salida .= "		      <input type=\"hidden\" name=\"pieza\" value=\"$pieza\" >";
    $this->salida .= "		      <input type=\"hidden\" name=\"parametro\" value=\"$parametro\" >";
		$this->salida .= "			    </table>";
		$this->salida .= "		     </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
		$this->salida .= "<table width=\"95%\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
    $this->salida .= "</table>";
		$ProductosDevolucion=$this->ProductosDevolucion($Documento);
    if($ProductosDevolucion){
		$this->salida .= "			<table width=\"95%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "					<td width=\"10%\">CODIGO PRODUCTO</td>\n";
		$this->salida .= "					<td>NOMBRE</td>\n";
		$this->salida .= "					<td width=\"10%\">CANTIDAD DEVOLUCION</td>\n";
		$this->salida .= "					<td>LOTES</td>\n";
		$this->salida .= "			    <td width=\"10%\">FECHA VENCIMIENTO</td>";
		$this->salida .= "			    <td width=\"10%\">LOTE</td>";
		$this->salida .= "			    <td width=\"10%\">CANTIDAD LOTE</td>";
		$this->salida .= "					<td><input type=\"checkbox\" name=\"selectodo\" onclick=\"Seleccionartodos(this.form,this.checked)\"></td>\n";
		$this->salida .= "				</tr>\n";
		$y=0;
		$z=0;
		for($i=0;$i<sizeof($ProductosDevolucion);$i++){
		  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			if($z % 2){$estilo1='modulo_list_oscuro';}else{$estilo1='modulo_list_claro';}
		  $this->salida .= "	 <tr class=\"$estilo\">\n";
			$this->salida .= "	 <td>".$ProductosDevolucion[$i]['codigo_producto']."</td>";
			$this->salida .= "	 <td>".$ProductosDevolucion[$i]['descripcion']."</td>";
			$this->salida .= "	 <td>".$ProductosDevolucion[$i]['cantidad']."</td>";
			$sumaTotal=$this->SumaFechasLotesProductosDevol($ProductosDevolucion[$i]['consecutivo'],$ProductosDevolucion[$i]['codigo_producto']);
			if(($ProductosDevolucion[$i]['sw_control_fecha_vencimiento']==1) && ($sumaTotal['suma']<$ProductosDevolucion[$i]['cantidad'])){
				$actionLotes=ModuloGetURL('app','InvBodegas','user','LlamaMostrarLotesPtosDevols',array("EstacionId"=>$EstacionId,"NombreEstacion"=>$NombreEstacion,"Fecha"=>$Fecha,"Documento"=>$Documento,"Ingreso"=>$Ingreso,"observaciones"=>$observaciones,"codigoProducto"=>$ProductosDevolucion[$i]['codigo_producto'],"descripcion"=>$ProductosDevolucion[$i]['descripcion'],"Cantidad"=>$ProductosDevolucion[$i]['cantidad'],"consecutivo"=>$ProductosDevolucion[$i]['consecutivo'],
				"identificacion"=>$identificacion,"nombrepac"=>$nombrepac,"cama"=>$cama,"pieza"=>$pieza,"parametro"=>$parametro));
				$this->salida .= "     <td align=\"center\"><a href=\"$actionLotes\"><img border=\"0\" src=\"".GetThemePath()."/images/pparamed.png\"></a></td>";
      }else{
				$this->salida .= "     <td align=\"center\">&nbsp;</td>";
			}
			$Datos=$this->FechasLotesProductosDevol($ProductosDevolucion[$i]['consecutivo'],$ProductosDevolucion[$i]['codigo_producto']);
			if($Datos){
				$this->salida .= "     <td align=\"center\" colspan=\"3\">";
				$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\">";
				for($j=0;$j<sizeof($Datos);$j++){
					$this->salida .= "	      <tr class=\"$estilo1\">\n";
					$this->salida .= "	      <td width=\"32%\">".$Datos[$j]['fecha_vencimiento']."</td>";
					$this->salida .= "	      <td width=\"35%\">".$Datos[$j]['lote']."</td>";
					$this->salida .= "	      <td>".$Datos[$j]['cantidad']."</td>";
					$ElimFechaV=ModuloGetURL('app','InvBodegas','user','LlamaEliminarFechaVDevol',array("EstacionId"=>$EstacionId,"NombreEstacion"=>$NombreEstacion,"Fecha"=>$Fecha,"Documento"=>$Documento,"Ingreso"=>$Ingreso,"observaciones"=>$observaciones,"codigoProducto"=>$ProductosDevolucion[$i]['codigo_producto'],"Cantidad"=>$Datos[$j]['cantidad'],"consecutivo"=>$ProductosDevolucion[$i]['consecutivo'],"FechaVencimiento"=>$Datos[$j]['fecha_vencimiento'],"Lote"=>$Datos[$j]['lote'],
					"identificacion"=>$identificacion,"nombrepac"=>$nombrepac,"cama"=>$cama,"pieza"=>$pieza,"parametro"=>$parametro));
					$this->salida .= "	      <td width=\"5%\"><a href=\"$ElimFechaV\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
					$this->salida .= "	      </tr>";
				}
				$this->salida .= "	     </table>";
				$this->salida .= "</td>";
			}else{
        $this->salida .= "     <td colspan=\"3\" align=\"center\">&nbsp;</td>";
			}
			$this->salida .= "	 <td width=\"5%\" align=\"center\"><input type=\"checkbox\" name=\"checkboxDevol[]\" value=\"".$ProductosDevolucion[$i]['codigo_producto'].".-.".$ProductosDevolucion[$i]['cantidad'].".-.".$ProductosDevolucion[$i]['consecutivo']."\"></td>";
			$this->salida .= "	 </tr>\n";
			$y++;
			$z++;
		}
		$this->salida .= "			</table>";
    $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "       <tr><td align=\"right\"><input type=\"submit\" name=\"CancelarProductos\" value=\"CANCELAR PRODUCTOS\" class=\"input-text\"></td></tr>";
		$this->salida .= "       </table><br>";
		}else{
    $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "       <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS</td></tr>";
		$this->salida .= "       </table><br>";
    }
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "         <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\">&nbsp&nbsp;";
		$this->salida .= "         <input type=\"submit\" name=\"devolucion\" value=\"ACEPTAR DEVOLUCION\" class=\"input-submit\"></td></tr>";
		$this->salida .= "       </table><br>";
		$this->salida .="       </form>";
		if($bandera==1){
		  $actionDos=ModuloGetURL('app','InvBodegas','user','InsertarFechaVencimientoLoteDevol',array("EstacionId"=>$EstacionId,"NombreEstacion"=>$NombreEstacion,"Fecha"=>$Fecha,"Documento"=>$Documento,"Ingreso"=>$Ingreso,"observaciones"=>$observaciones,"codigoProducto"=>$codigoProducto,"descripcion"=>$descripcion,"Cantidad"=>$Cantidad,"consecutivo"=>$consecutivo,
      "identificacion"=>$identificacion,"nombrepac"=>$nombrepac,"cama"=>$cama,"pieza"=>$pieza,"parametro"=>$parametro));
		  $this->salida .= "       <form name=\"formaUno\" action=\"$actionDos\" method=\"post\">";
			$sumaTotal=$this->SumaFechasLotesProductosDevol($consecutivo,$codigoProducto);
			$cantidadFalta=$Cantidad-$sumaTotal['suma'];
		  $this->salida .= "          <table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "          <tr><td width=\"100%\">";
			$this->salida .= "          <fieldset><legend class=\"field\">DATOS DEL PRODUCTO</legend>";
			$this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "	        <tr><td></td></tr>";
			$this->salida .= "         <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">$codigoProducto&nbsp&nbsp&nbsp;$descripcion</td></tr>";
			$this->salida .= "         <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">CANTIDAD QUE FALTA PARA ALCANZAR EL TOTAL&nbsp&nbsp&nbsp;$cantidadFalta</td></tr>";
			$this->salida .= "	        <tr class=\"modulo_list_claro\">";
			$this->salida .= "	        <td class=\"label\">FECHA VENCIMIENTO</td>";
      $this->salida .= "	  	    <td align=\"center\"><input type=\"text\" name=\"fechaVencimiento\" value=\"$fechaVencimiento\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
			$this->salida .= "	  	    ".ReturnOpenCalendario('formaUno','fechaVencimiento','/')."</td>";
			$this->salida .= "	        <td class=\"label\">No. LOTE</td>";
			$this->salida .= "	        <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"lote\" value=\"$lote\"></td>";
			$this->salida .= "	        <td class=\"label\">CANTIDAD</td>";
			$this->salida .= "	        <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"cantidadLote\" value=\"$cantidadLote\"></td>";
			$this-> salida .= "	        <tr><td></td></tr>";
      $this->salida .= "	        <tr><td colspan=\"6\" align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"CANCELAR\">";
			$this->salida .= "	        <input type=\"submit\" class=\"input-submit\" name=\"insertar\" value=\"INSERTAR\"></td></tr>";
			$this->salida .= "			     </table>";
			$this->salida .= "		      </fieldset></td><BR>";
			$this->salida .= "         </table><BR>";
			$this->salida .="       </form>";
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

  function CancelarSolicitudesDevoluciones($checkboxDevol,$EstacionId,$NombreEstacion,$Fecha,$Documento,$Ingreso,$observaciones,$bandera,$codigoProducto,$descripcion,$Cantidad,$consecutivo,
	$identificacion,$nombrepac,$cama,$pieza){
    $this->salida .= ThemeAbrirTabla('CANCELACION PRODUCTOS DE LA SOLICITUD DE DEVOLUCION');
		$action=ModuloGetURL('app','InvBodegas','user','GuardarCancelacionDevoluciones',array("checkboxDevol"=>$checkboxDevol,"EstacionId"=>$EstacionId,"NombreEstacion"=>$NombreEstacion,"Fecha"=>$Fecha,"Documento"=>$Documento,"Ingreso"=>$Ingreso,"observaciones"=>$observaciones,"bandera"=>$bandera,"codigoProducto"=>$codigoProducto,"descripcion"=>$descripcion,"Cantidad"=>$Cantidad,"consecutivo"=>$consecutivo,
	  "identificacion"=>$identificacion,"nombrepac"=>$nombrepac,"cama"=>$cama,"pieza"=>$pieza));
		$this->salida .= "       <form name=\"formaInventarios\" action=\"$action\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "       <table border=\"0\" width=\"60%\" class=\"normal_10\" align=\"center\">";
		$this->salida .= "	        <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	      	</td><tr>";
		$this->salida .= "		    <tr height=\"20\"><td width=\"20%\" class=\"".$this->SetStyle("MotivoId")."\">MOTIVO CANCELACION</td><td><select name=\"MotivoId\"  class=\"select\">";
    $this->salida .="         <option value=\"-1\" selected>---Seleccione---</option>";
		$Motivos=$this->MoivosCancelacionDevolucion();
    for($i=0;$i<sizeof($Motivos);$i++){
      $value=$Motivos[$i]['motivo_id'];
      $titulo=$Motivos[$i]['descripcion'];
      if($value==$_REQUEST['MotivoId']){
			  $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
      }else{
        $this->salida .=" <option value=\"$value\">$titulo</option>";
      }
    }
		$this->salida .= "        </select></td></tr>";
		$this->salida .= "				<tr><td class=\"label\" colspan=\"2\" width=\"20%\">OBSERVACIONES</td></tr><tr><td colspan=\"2\"><textarea name=\"observacion\" cols=\"65\" rows=\"3\" class=\"textarea\">".$_REQUEST['observacion']."</textarea></td></tr>";
		$this->salida .= "        <tr><td colspan=\"2\" align=\"center\"><BR><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\">";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"ACEPTAR\"></td></tr>";
		$this->salida .= "        </table>";
		$this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function ListadoSolicidudesnoConfirmar(){
	  $this->salida .= themeAbrirTabla('SOLICITUDES DE DESPACHO Y DEVOLUCIONES SIN CONFIRMAR');
    $action = ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
		$this->salida .= "       <form name='forma' action='$action' method='POST' onsubmit=\"return ValidaSolicitud(this);\">\n";
		$this->Encabezado();
    $Solicitudes=$this->SolicitudesSinConfirmar();
    if($Solicitudes){
		$this->salida .= "			<table width=\"80%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "					<td width=\"10%\">CODIGO</td>\n";
		$this->salida .= "					<td>INGRESO</td>\n";
		$this->salida .= "					<td>PACIENTE</td>\n";
		$this->salida .= "					<td>FECHA</td>\n";
		$this->salida .= "					<td>ESTACION</td>\n";
		$this->salida .= "					<td>DETALLE</td>\n";
		$this->salida .= "				</tr>\n";
		$y=0;
		for($i=0;$i<sizeof($Solicitudes);$i++){
		  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
		  $this->salida .= "	 <tr class=\"$estilo\">\n";
			$this->salida .= "	 <td>".$Solicitudes[$i]['codigo']."</td>";
      $this->salida .= "	 <td>".$Solicitudes[$i]['ingreso']."</td>";
			$this->salida .= "	 <td>".$Solicitudes[$i]['primer_nombre']."&nbsp&nbsp&nbsp;".$Solicitudes[$i]['segundo_nombre']."&nbsp&nbsp&nbsp;".$Solicitudes[$i]['primer_apellido']."&nbsp&nbsp&nbsp;".$Solicitudes[$i]['segundo_apellido']."</td>";
      (list($fecha,$HoraTot)=explode(' ',$Solicitudes[$i]['fecha']));
      (list($ano,$mes,$dia)=explode('-',$fecha));
      (list($hora,$min)=explode(':',$HoraTot));
			$this->salida .= "	 <td>".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
			$NombreEstacion=$this->NombreEstacion($Solicitudes[$i]['estacion']);
			$this->salida .= "	 <td>".$NombreEstacion['descripcion']."</td>";
			$Detalle = ModuloGetURL('app','InvBodegas','user','DetalleSolicitudNoConfirmar',array("SolicitudId"=>$Solicitudes[$i]['codigo'],"tipoSolicitud"=>$Solicitudes[$i]['tiposolicitud'],"tipoSol"=>$Solicitudes[$i]['tipo'],"ingreso"=>$Solicitudes[$i]['ingreso'],"primerNombre"=>$Solicitudes[$i]['primer_nombre'],"segundoNombre"=>$Solicitudes[$i]['segundo_nombre'],"primerApellido"=>$Solicitudes[$i]['primer_apellido'],"segundoApellido"=>$Solicitudes[$i]['segundo_apellido'],"Fecha"=>$Solicitudes[$i]['fecha']));
			$this->salida .= "	      <td width=\"5%\"><a href=\"$Detalle\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a></td>";
      $this->salida .= "	  </tr>";
			$y++;
		}
		$this->salida .= "			</table><BR>";
		}else{
      $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "         <tr><td align=\"center\" class=\"label_error\">NO EXISTEN SILICITUDES SIN CONFIRMAR</td></tr>";
			$this->salida .= "       </table>";
		}
	  $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "         <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "       </table>";
		$this->salida .= "       </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaDetalleSolicitudNoConfirmar($tipoSolicitud,$SolicitudId,$tipoSol,$ingreso,$primerNombre,$segundoNombre,$primerApellido,$segundoApellido,$Fecha){
	  $this->salida .= themeAbrirTabla('DETALLE DE LA SOLICITUD');
    $action = ModuloGetURL('app','InvBodegas','user','ListadoSolicidudesnoConfirmar');
		$this->salida .= "       <form name='forma' action='$action' method='POST' onsubmit=\"return ValidaSolicitud(this);\">\n";
		$this->Encabezado();

		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "       <tr><td width=\"100%\">";
		$this->salida .= "       <fieldset><legend class=\"field\">DATOS DE LA SOLICITUD</legend>";
    $this->salida .= "       <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	      <tr><td></td></tr>";
		$this->salida .= "	      <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	      <td><label class=\"label\">PACIENTE</td>";
		$this->salida .= "	      <td colspan=\"5\">$primerNombre $segundoNombre $primerApellido $segundoApellido</td>";
		$this->salida .= "	      </tr>";
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td><label class=\"label\">No. SOLICITUD</td>";
		$this->salida .= "	      <td>$SolicitudId</td>";
		$this->salida .= "	      <td><label class=\"label\">FECHA</td>";
		$this->salida .= "	      <td>$Fecha</td>";
		$this->salida .= "	      <td><label class=\"label\">INGRESO</td>";
		$this->salida .= "	      <td>$ingreso</td>";
		$this->salida .= "	      </tr>";
		$this->salida .= "	      <tr><td></td></tr>";
    $this->salida .= "			  </table>";
		$this->salida .= "		    </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";

		$Productos=$this->DetalleMtosSolicitud($tipoSolicitud,$SolicitudId,$tipoSol);
		if($Productos){
		$this->salida .= "			<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
    if($tipoSolicitud=='S'){$palabra1='DESPACHO';}else{$palabra1='DEVOLUCION';}
    if($tipoSol=='M'){$palabra2='MEDICAMENTOS';}elseif($tipoSol=='Z'){$palabra2='MEZCLAS';}else{$palabra2='INSUMOS';}
		$this->salida .= "				<tr class=\"modulo_table_list_title\"><td colspan=\"3\">SOLICITUD DE $palabra1 DE $palabra2</td></tr>\n";
		$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "					<td width=\"10%\">CODIGO</td>\n";
		$this->salida .= "					<td>DESCRIPCION</td>\n";
		$this->salida .= "					<td width=\"10%\">CANTIDAD</td>\n";
		$this->salida .= "				</tr>\n";
		$y=0;
    for($i=0;$i<sizeof($Productos);$i++){
		  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
		  $this->salida .= "	 <tr class=\"$estilo\">\n";
			$this->salida .= "	 <td>".$Productos[$i]['mto']."</td>";
      $this->salida .= "	 <td>".$Productos[$i]['descripcion']."</td>";
			$this->salida .= "	 <td>".$Productos[$i]['cant']."</td>";
      $this->salida .= "	  </tr>";
			$y++;
		}
    $this->salida .= "       </table><BR>";
		}
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "       <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "       </table>";
    $this->salida .= "       </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RecibirOrdenesCompra(){
	  $this->salida .= themeAbrirTabla('ORDENES DE COMPRA PENDIENTES POR RECIBIR');
		$accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
		$this->salida .= "  <form name=\"compravee1\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "       <tr><td width=\"100%\">";
		$this->salida .= "       <fieldset><legend class=\"field\">SELECCION PROVEEDOR</legend>";
		$this->salida .= "      <BR><table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "      <tr class=\"modulo_table_list_title\">";
		$this->salida .= "      <td width=\"3%\" >No.</td>";
		$this->salida .= "      <td width=\"20%\">DOCUMENTO</td>";
		$this->salida .= "      <td width=\"60%\">NOMBRE</td>";
		$this->salida .= "      <td width=\"10%\">NUMERO ORDENES</td>";
		$this->salida .= "      <td width=\"7%\" >MENÚ</td>";
		$this->salida .= "      </tr>";
		$provempr=$this->BuscarProveedoresProductosCompra();
		$i=0;
		$j=0;
		$ciclo=sizeof($provempr);
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
			$this->salida .= "".($i+1)."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$provempr[$i]['tipo_id_tercero']."".' -- '."".$provempr[$i]['tercero_id']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$provempr[$i]['nombre_tercero']."";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "".$provempr[$i]['numerorden']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "<a href=\"". ModuloGetURL('app','InvBodegas','user','DetalleRecepcionCompra',
			array('provelegip'=>$provempr[$i]['codigo_proveedor_id'],"tipoProv"=>$provempr[$i]['tipo_id_tercero'],"nombreProv"=>$provempr[$i]['nombre_tercero'])) ."\">
			<img src=\"".GetThemePath()."/images/tabla.png\" border=\"0\"></a>";
			$this->salida .= "</td>";
			$i++;
		}
		if(empty($provempr))
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td colspan=\"5\" align=\"center\">";
			$this->salida .= "'NO SE ENCONTRÓ NINGÚN PROVEEDOR CON ORDENES DE COMPRA PENDIENTES POR RECIBIR'";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
		}
		$this->salida .= "      </table><BR>";
		$this->salida .= "		    </fieldset></td></tr><BR>";

		//$this->salida .= "       </table><BR>";
    //$this->salida .= "       </form>";
		$this->salida .= "  <tr>";
		$this->salida .= "  <td align=\"center\"><br>";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><br>";
		$var1=$this->RetornarBarraProvee();
		if(!empty($var1))
		{
			$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"100%\" align=\"center\">";
			$this->salida .=$var1;
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  </table><br>";
		}
		$accion=ModuloGetURL('app','InvBodegas','user','RecibirOrdenesCompra',
		array('codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp']));
		$this->salida .= "  <form name=\"compravee2\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"1\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td width=\"27%\" class=\"label\">NÚMERO:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td width=\"73%\">";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"codigocomp\" value=\"".$_REQUEST['codigocomp']."\" maxlength=\"20\" size=\"20\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td class=\"label\">NOMBRE:";
		$this->salida .= "  </td>";
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"text\" class=\"input-text\" name=\"descricomp\" value=\"".$_REQUEST['descricomp']."\" maxlength=\"60\" size=\"40\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"BUSCAR\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=modulo_list_claro>";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">";
		$accion=ModuloGetURL('app','InvBodegas','user','RecibirOrdenesCompra');
		$this->salida .= "  <form name=\"compravee3\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"reiniciar\" value=\"REINICIAR  BÚSQUEDA\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </form>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

	function RetornarBarraProvee()//Barra paginadora de los proveedores activos
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','InvBodegas','user','RecibirOrdenesCompra',array('conteo'=>$this->conteo,
		'codigocomp'=>$_REQUEST['codigocomp'],'descricomp'=>$_REQUEST['descricomp']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	function DetalleRecepcionCompra(){
	  $this->salida .= themeAbrirTabla('DETALLE DE LAS ORDENES DE COMPRA PENDIENTES POR RECIBIR');
		$accion=ModuloGetURL('app','InvBodegas','user','RealizarDocumentoCompra');
		$this->salida .= "  <form name=\"compravee1\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "       <tr><td width=\"100%\">";
		$this->salida .= "       <fieldset><legend class=\"field\">DATOS DEL PROVEEDOR</legend>";
    $this->salida .= "       <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	      <tr><td></td></tr>";
		$this->salida .= "	      <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	      <td><label class=\"label\">CODIGO</td>";
    $this->salida .= "	      <td>".$_REQUEST['provelegip']."</td>";
		$this->salida .= "	      <td><label class=\"label\">NOMBRE</td>";
    $this->salida .= "	      <td>".$_REQUEST['tipoProv']." ".$_REQUEST['nombreProv']."</td>";
		$this->salida .= "	      </tr>";
		$this->salida .= "	      <tr><td></td></tr>";
    $this->salida .= "			  </table>";
		$this->salida .= "		    </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
		$this->salida .= "<table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
		$this->salida .= "</table>";
    $this->salida .= "			<input type=\"hidden\" name=\"provelegip\" value=\"".$_REQUEST['provelegip']."\">";
		$this->salida .= "			<input type=\"hidden\" name=\"tipoProv\" value=\"".$_REQUEST['tipoProv']."\">";
		$this->salida .= "			<input type=\"hidden\" name=\"nombreProv\" value=\"".$_REQUEST['nombreProv']."\">";
		$this->salida .= "			<table width=\"98%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
    $this->salida .= "				<tr class=\"modulo_table_list_title\"><td colspan=\"7\">DETALLE DE LOS PRODUCTOS DE LA ORDEN DE COMPRA</td></tr>\n";
		$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "				<td width=\"12%\">CODIGO</td>\n";
		$this->salida .= "				<td width=\"25%\">DESCRIPCION</td>\n";
		$this->salida .= "				<td width=\"12%\">NUMERO ORDEN</td>\n";
		$this->salida .= "				<td width=\"9%\">FECHA ORDEN</td>\n";
		$this->salida .= "				<td width=\"18%\">CANTIDAD ENVIADA</td>\n";
		$this->salida .= "				<td width=\"18%\">VALOR UND</td>\n";
		$this->salida .= "				<td>IVA</td>\n";
		$this->salida .= "				</tr>\n";
    $Productos=$this->HallarDetalleOrdenProveedor($_REQUEST['provelegip']);
		$i=0;
		$j=0;
		$ciclo=sizeof($Productos);
		while($i<$ciclo)
		{
			if($j==0)
			{
				$color="class=\"modulo_list_claro\"";
				$j=1;
			}
			else
			{
				$color="class=\"modulo_list_oscuro\"";
				$j=0;
			}
			$this->salida .= "<tr $color>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$Productos[$i]['codigo_producto']."";
			$this->salida .= "</td>";
			$this->salida .= "<td align=\"center\">";
			$this->salida .= "".$Productos[$i]['descripcion']."";
			$this->salida .= "</td>";

			$k=$i;
			$total=0;
			$this->salida .= "<td colspan=\"5\">";
			while($Productos[$i]['codigo_producto']==$Productos[$k]['codigo_producto'])
			{
				$this->salida .= "  <table border=\"1\" width=\"100%\" align=\"center\" $color>";
				$this->salida .= "  <tr>";
				$this->salida .= "  <td width=\"18%\" nowrap>";
				$this->salida .= "".$Productos[$k]['orden_pedido_id']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td width=\"15%\" nowrap>";
				$this->salida .= "".$Productos[$k]['fecha_orden']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td width=\"29%\" nowrap>";
				$this->salida .= "".$Productos[$k]['numero_unidades'] - $Productos[$k]['numero_unidades_recibidas']."";
				$total=$total+$Productos[$k]['numero_unidades']-$Productos[$k]['numero_unidades_recibidas'];
				$this->salida .= "  </td>";
				$this->salida .= "  <td width=\"29%\" nowrap>";
				$this->salida .= "".$Productos[$k]['valor']."";
				$this->salida .= "  </td>";
				$this->salida .= "  <td>";
				$this->salida .= "".$Productos[$k]['porc_iva']."";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$this->salida .= "  </table>";
				$k++;
			}
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr $color>";
			$this->salida .= "<td colspan=\"4\" class=\"label\"><BR>";
			$this->salida .= "TOTAL UNIDADES";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "<b>CANT. RECIBIDA</b>&nbsp&nbsp&nbsp;";
      $this->salida .= "<input type=\"hidden\" name=\"totalCompar[".$Productos[$i]['codigo_producto']."]\" value=\"$total\">";
			$this->salida .= "<input type=\"class=\"input-text\" size=\"7\" text name=\"cantidaRecibida[".$Productos[$i]['codigo_producto']."]\" value=\"$total\">";
			$this->salida .= "</td>";
      $this->salida .= "<td><b>TOTAL + IVA</b>&nbsp&nbsp&nbsp;";
			$this->salida .= "<input type=\"class=\"input-text\" size=\"7\" text name=\"totalesRecibida[".$Productos[$i]['codigo_producto']."]\" value=\"$totalValor\">";
			$this->salida .= "</td>";
			$this->salida .= "<td>&nbsp;</td>";
			$this->salida .= "</tr>";
			$i=$k;
		}
    $this->salida .= "       </table><BR>";
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "       <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\">&nbsp&nbsp&nbsp;";
		$this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"GUARDAR DOCUMENTO EN LA BODEGA\"></td></tr>";
		$this->salida .= "       </table>";
    $this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaPedirFechaVenceCompra($proveedor,$tipoprov,$nomprov,$documento,$concepto,$bandera,$consecutivo,$codigoProducto,$cantidadTotal,$descripcion){

	  $this->salida .= themeAbrirTabla('FECHAS DE VENCIMIENTO Y LOTES PARA LOS PRODUCTOS RECIBIDOS CON ORDEN DE COMPRA');
		$accion=ModuloGetURL('app','InvBodegas','user','GuardarDocumentoCompra');
		$this->salida .= "  <form name=\"compravee1\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "       <tr><td width=\"100%\">";
		$this->salida .= "       <fieldset><legend class=\"field\">DATOS DEL PROVEEDOR</legend>";
    $this->salida .= "       <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	      <tr><td></td></tr>";
		$this->salida .= "	      <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	      <td><label class=\"label\">CODIGO</td>";
    $this->salida .= "	      <td>".$_REQUEST['proveedor']."</td>";
		$this->salida .= "	      <td><label class=\"label\">NOMBRE</td>";
    $this->salida .= "	      <td>".$_REQUEST['tipoprov']." ".$_REQUEST['nomprov']."</td>";
		$this->salida .= "	      </tr>";
		$this->salida .= "	      <tr><td></td></tr>";
    $this->salida .= "			  </table>";
		$this->salida .= "		    </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
    $this->salida .= "			<input type=\"hidden\" name=\"proveedor\" value=\"$proveedor\">";
		$this->salida .= "			<input type=\"hidden\" name=\"tipoprov\" value=\"$tipoprov\">";
		$this->salida .= "			<input type=\"hidden\" name=\"nomprov\" value=\"$nomprov\">";
		$this->salida .= "			<input type=\"hidden\" name=\"documento\" value=\"$documento\">";
		$this->salida .= "			<input type=\"hidden\" name=\"concepto\" value=\"$concepto\">";
    $ProductosDocumento=$this->DetalleDocumentoComprasFechaVence($documento,$concepto);
		if($ProductosDocumento){
		$this->salida .= "			<table width=\"80%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "			<td width=\"10%\">CODIGO</td>\n";
		$this->salida .= "			<td>DESCRIPCION</td>\n";
		$this->salida .= "			<td width=\"10%\">CANTIDAD</td>\n";
		$this->salida .= "			<td width=\"5%\">LOTES</td>\n";
		$this->salida .= "			<td width=\"15%\">FECHA VENCIMIENTO</td>";
		$this->salida .= "			<td width=\"15%\">LOTE</td>";
		$this->salida .= "			<td width=\"15%\">CANTIDAD LOTE</td>";
		$this->salida .= "			</tr>\n";
		$y=0;
		$z=0;
    for($i=0;$i<sizeof($ProductosDocumento);$i++){
		  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			if($z % 2){$estilo1='modulo_list_oscuro';}else{$estilo1='modulo_list_claro';}
		  $this->salida .= "	 <tr class=\"$estilo\">\n";
			$this->salida .= "	 <td>".$ProductosDocumento[$i]['codigo_producto']."</td>";
      $this->salida .= "	 <td>".$ProductosDocumento[$i]['descripcion']."</td>";
			$this->salida .= "	 <td>".$ProductosDocumento[$i]['cantidad']."</td>";
			$sumaTotal=$this->SumaFechasLotesProductosCompras($ProductosDocumento[$i]['consecutivo'],$ProductosDocumento[$i]['codigo_producto']);
			if(($sumaTotal['suma']<$ProductosDocumento[$i]['cantidad'])){
				$actionAdicion=ModuloGetURL('app','InvBodegas','user','LlamaInsertarFechaVenciLotePtoCompras',array("consecutivo"=>$ProductosDocumento[$i]['consecutivo'],"codigoProducto"=>$ProductosDocumento[$i]['codigo_producto'],"cantidadTotal"=>$ProductosDocumento[$i]['cantidad'],"descripcion"=>$ProductosDocumento[$i]['descripcion'],"proveedor"=>$proveedor,"tipoprov"=>$tipoprov,"nomprov"=>$nomprov,"documento"=>$documento,"concepto"=>$concepto));
				$this->salida .= "	 <td><a href=\"$actionAdicion\"><img border=\"0\" src=\"".GetThemePath()."/images/pparamed.png\"></a></td>";
			}else{
        $this->salida .= "	 <td>&nbsp;</td>";
			}
			$this->salida .= "	 <td colspan=\"3\">";
			$Datos=$this->FechasLotesProductosCompras($ProductosDocumento[$i]['consecutivo'],$ProductosDocumento[$i]['codigo_producto']);
			if($Datos){
				$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
				for($j=0;$j<sizeof($Datos);$j++){
					$this->salida .= "	      <tr class=\"$estilo1\">\n";
					$this->salida .= "	      <td width=\"32%\">".$Datos[$j]['fecha_vencimiento']."</td>";
					$this->salida .= "	      <td width=\"36%\">".$Datos[$j]['lote']."</td>";
					$this->salida .= "	      <td>".$Datos[$j]['cantidad']."</td>";
					$ElimFechaV=ModuloGetURL('app','InvBodegas','user','LlamaEliminarFechaVCompras',array("consecutivo"=>$ProductosDocumento[$i]['consecutivo'],"codigoProducto"=>$ProductosDocumento[$i]['codigo_producto'],"fechaVencimiento"=>$Datos[$j]['fecha_vencimiento'],"lote"=>$Datos[$j]['lote'],"cantidad"=>$Datos[$j]['cantidad'],"proveedor"=>$proveedor,"tipoprov"=>$tipoprov,"nomprov"=>$nomprov,"documento"=>$documento,"concepto"=>$concepto));
					$this->salida .= "	      <td width=\"5%\"><a href=\"$ElimFechaV\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
					$this->salida .= "	      </tr>";
				}
				$this->salida .= "	     </table>";
				$this->salida .= "</td>";
			}else{
				$this->salida .= "</td>";
			}
      $this->salida .= "	 </tr>";
			$y++;
			$z++;
		}
    $this->salida .= "       </table><BR>";
		}else{
      $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "       <tr><td align=\"center\" class=\"label_error\">NO HAY PRODUCTOS QUE REQUIERAN FECHAS DE VENCIMIENTO, ELIJA GUARDAR EL DOCUMENTO SI LO DESEA</td></tr>";
			$this->salida .= "       </table><BR>";
		}
		$this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "       <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"SALIR SIN GUARDAR\">&nbsp&nbsp&nbsp;";
		$this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"GUARDAR DOCUMENTO\"></td></tr>";
		$this->salida .= "       </table>";
		$this->salida .= "       </form>";
		$this->salida .= "<table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
		$this->salida .= "</table>";
		if($bandera==1){
		  $actionDos=ModuloGetURL('app','InvBodegas','user','InsertarFechaVencimientoLoteCompras',array("consecutivo"=>$consecutivo,"codigoProducto"=>$codigoProducto,"cantidadTotal"=>$cantidadTotal,"descripcion"=>$descripcion));
		  $this->salida .= "       <form name=\"formaUno\" action=\"$actionDos\" method=\"post\">";
			$sumaTotal=$this->SumaFechasLotesProductosCompras($consecutivo,$codigoProducto);
			$cantidadFalta=$cantidadTotal-$sumaTotal['suma'];
			$this->salida .= "			<input type=\"hidden\" name=\"proveedor\" value=\"$proveedor\">";
			$this->salida .= "			<input type=\"hidden\" name=\"tipoprov\" value=\"$tipoprov\">";
			$this->salida .= "			<input type=\"hidden\" name=\"nomprov\" value=\"$nomprov\">";
			$this->salida .= "			<input type=\"hidden\" name=\"documento\" value=\"$documento\">";
		  $this->salida .= "			<input type=\"hidden\" name=\"concepto\" value=\"$concepto\">";
		  $this->salida .= "          <table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "          <tr><td width=\"100%\">";
			$this->salida .= "          <fieldset><legend class=\"field\">DATOS DEL PRODUCTO</legend>";
			$this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "	        <tr><td></td></tr>";
			$this->salida .= "         <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">$codigoProducto&nbsp&nbsp&nbsp;$descripcion</td></tr>";
			$this->salida .= "         <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">CANTIDAD QUE FALTA PARA ALCANZAR EL TOTAL&nbsp&nbsp&nbsp;$cantidadFalta</td></tr>";
			$this->salida .= "	        <tr class=\"modulo_list_claro\">";
			$this->salida .= "	        <td class=\"label\">FECHA VENCIMIENTO</td>";
      $this->salida .= "	  	    <td align=\"center\"><input type=\"text\" name=\"fechaVencimiento\" value=\"$fechaVencimiento\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
			$this->salida .= "	  	    ".ReturnOpenCalendario('formaUno','fechaVencimiento','/')."</td>";
			$this->salida .= "	        <td class=\"label\">No. LOTE</td>";
			$this->salida .= "	        <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"lote\" value=\"$lote\"></td>";
			$this->salida .= "	        <td class=\"label\">CANTIDAD</td>";
			$this->salida .= "	        <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"cantidadLote\" value=\"$cantidadLote\"></td>";
			$this-> salida .= "	        <tr><td></td></tr>";
      $this->salida .= "	        <tr><td colspan=\"6\" align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"CANCELAR\">";
			$this->salida .= "	        <input type=\"submit\" class=\"input-submit\" name=\"insertar\" value=\"INSERTAR\"></td></tr>";
			$this->salida .= "			     </table>";
			$this->salida .= "		      </fieldset></td><BR>";
			$this->salida .= "         </table><BR>";
			$this->salida .="       </form>";
		}
		$this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ReporteProductosCercaFVmto(){
    $this->salida .= themeAbrirTabla('PRODUCTOS CERCANOS A LA FECHA DE VENCIMIENTO');
		$accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios4');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
    $Productos=$this->ProductosCercaVencer();
		if($Productos){
		$this->salida .= "			<br><table width=\"95%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "			<td width=\"10%\">CODIGO</td>\n";
		$this->salida .= "			<td>DESCRIPCION</td>\n";
		$this->salida .= "			<td width=\"10%\">FECHA VENCIMIENTO</td>";
		$this->salida .= "			<td width=\"10%\">DIAS PREVIOS AL VENCIMIENTO</td>";
		$this->salida .= "			<td width=\"15%\">No. LOTE</td>\n";
		$this->salida .= "			<td width=\"10%\">CANTIDAD LOTE</td>";
		$this->salida .= "			</tr>\n";
		$y=0;
    for($i=0;$i<sizeof($Productos);$i++){
		  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
		  $this->salida .= "	 <tr class=\"$estilo\">\n";
			$this->salida .= "	 <td>".$Productos[$i]['codigo_producto']."</td>";
      $this->salida .= "	 <td>".$Productos[$i]['descripcion']."</td>";
      $this->salida .= "	 <td>".$Productos[$i]['fecha_vencimiento']."</td>";
			$this->salida .= "	 <td>".$Productos[$i]['dias_previos_vencimiento']."</td>";
			$this->salida .= "	 <td>".$Productos[$i]['lote']."</td>";
			$this->salida .= "	 <td>".$Productos[$i]['cantidad']."</td>";
			$y++;
		}
		$this->salida .= "       </table><BR>";
		}else{
      $this->salida .= "			<table width=\"80%\" border=\"0\" align=\"center\"> \n";
      $this->salida .= "			<tr><td align=\"center\" class=\"label_error\">NO EXISTEN PRODUCTOS PROXIMOS A VENCERSE</td></tr>";
      $this->salida .= "			</table><br>";
		}
		$this->salida .= "			<table width=\"80%\" border=\"0\" align=\"center\"> \n";
		$this->salida .= "			<tr><td align=\"center\"><input type=\"submit\"class=\"input-submit\" name=\"regresar\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "			</table>";
		$this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

	function CreacionTiposDocBodegas($tipoDocumento,$prefijo,$descripcion,
		$numeracion,$movimiento,$digitos,$cambio,$concepto,$BodegaDocId){
		$var='checked';
    $this->salida .= themeAbrirTabla('CREACION TIPOS DOCUMENTOS DE BODEGA');
		$accion=ModuloGetURL('app','InvBodegas','user','GuardarTipDocBodega');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "<input type=\"hidden\" name=\"BodegaDocId\" value=\"$BodegaDocId\">";
		$this->salida .= "<input type=\"hidden\" name=\"cambio\" value=\"$cambio\">";
		$this->salida .= "<table width=\"70%\" class=\"class=\"normal_10\"\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
    $this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
    $this->salida .= "      <br><table width=\"95%\" class=\"normal_10\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "      <tr class=\"modulo_table_title\"><td colspan=\"2\"  align=\"center\">DATOS DEL DOCUMENTO</td></tr>";
    $this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"".$this->SetStyle("tipoDocumento")."\">TIPO DOCUMENTO</td>";
		$this->salida .= "      <td><select name=\"tipoDocumento\" class=\"select\">";
		$TotalDocumentos=$this->ConsultaTiposDocumento();
		$this->Mostrar($TotalDocumentos,'False',$tipoDocumento);
		$this->salida .= "      </select></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"".$this->SetStyle("prefijo")."\">PREFIJO</td>";
    $this->salida .= "			<td><input size=\"5\" maxlength=\"5\" type=\"text\" name=\"prefijo\" value=\"$prefijo\"></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"".$this->SetStyle("descripcion")."\">DESCRIPCION</td>";
    $this->salida .= "			<td><input size=\"20\" maxlength=\"20\" type=\"text\" name=\"descripcion\" value=\"$descripcion\"></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		if($BodegaDocId){
      $chequeado='readonly';
		}
		$this->salida .= "			<td class=\"".$this->SetStyle("numeracion")."\">INICIO NUMERACION</td>";
    $this->salida .= "			<td><input size=\"20\" type=\"text\" name=\"numeracion\" $chequeado value=\"$numeracion\"></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"".$this->SetStyle("movimiento")."\">TIPO MOVIMIENTO</td>";
		$this->salida .= "      <td><select name=\"movimiento\" class=\"select\">";
		$tipoMovimientos=$this->TipoMovimiento();
		$this->Mostrar($tipoMovimientos,'False',$movimiento);
		$this->salida .= "      </select></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			<td class=\"".$this->SetStyle("digitos")."\">NUMERO DIGITOS</td>";
    $this->salida .= "			<td><input size=\"20\" type=\"text\" name=\"digitos\" value=\"$digitos\"></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "      </table><br>";
		$this->salida .= "</td></tr>";
    $this->salida .= "<tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "      <br><table width=\"95%\" class=\"normal_10\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "      <tr class=\"modulo_table_title\"><td colspan=\"4\"  align=\"center\">CONCEPTO DEL DOCUMENTO</td></tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		if($concepto=='swninguno'){$var='checked';}else{$var='';}
		if($concepto=='swajuste'){$var1='checked';}else{$var1='';}
		if($concepto=='swtraslado'){$var2='checked';}else{$var2='';}
		if($concepto=='swcompras'){$var3='checked';}else{$var3='';}
		if($concepto=='transmed'){$var4='checked';}else{$var4='';}
    $this->salida .= "			<td class=\"".$this->SetStyle("swninguno")."\">NINGUNO</td>";
    $this->salida .= "			<td width=\"5%\" colspan=\"3\"><input $var type=\"radio\" name=\"concepto\" value=\"swninguno\"></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			  <td class=\"".$this->SetStyle("swajuste")."\">AJUSTE INVENTARIOS</td>";
    $this->salida .= "			  <td width=\"5%\"><input $var1 type=\"radio\" name=\"concepto\" value=\"swajuste\"></td>";
		$this->salida .= "			  <td class=\"".$this->SetStyle("swtraslado")."\">TRANSFERENCIAS ENTRE BODEGAS</td>";
    $this->salida .= "			  <td width=\"5%\"><input $var2 type=\"radio\" name=\"concepto\" value=\"swtraslado\"></td>";
		$this->salida .= "			</tr>";
		$this->salida .= "			<tr class=\"modulo_list_claro\">";
		$this->salida .= "			  <td class=\"".$this->SetStyle("swcompras")."\">INGRESO COMPRAS</td>";
    $this->salida .= "			  <td width=\"5%\"><input $var3 type=\"radio\" name=\"concepto\" value=\"swcompras\"></td>";
		$this->salida .= "			  <td class=\"".$this->SetStyle("transmed")."\">TRANSACCION MEDICAMENTOS</td>";
    $this->salida .= "			  <td width=\"5%\"><input $var4 type=\"radio\" name=\"concepto\" value=\"transmed\"></td>";
		$this->salida .= "			</tr>";
    $this->salida .= "			</table><br>";
    $this->salida .= "</td></tr>";
		$this->salida .= "<tr>";
    $this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"CANCELAR\" name=\"Cancelar\">";
		$this->salida .= "<input class=\"input-submit\" type=\"submit\" value=\"GUARDAR\" name=\"Guardar\"></td>";
    $this->salida .= "</tr>";
		$this->salida .= "</table><BR>";
		if(!$cambio){
		$this->salida .= "<table width=\"70%\" class=\"class=\"normal_10\"\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$actionDoc=ModuloGetURL('app','InvBodegas','user','VerDocumentosCreados');
		$this->salida .= "<tr><td><a href=\"$actionDoc\" class=\"link\">Ver Tipos Documentos</a></td></tr>";
		$this->salida .= "</table><BR>";
		}else{
      $tiposDocumentos=$this->RegistroDocumentosCreados();
			if($tiposDocumentos){
				$this->salida .= "			<br><table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "			<td>No.</td>\n";
				$this->salida .= "			<td>CONCEPTO</td>\n";
				$this->salida .= "			<td>PF</td>";
				$this->salida .= "			<td>MOVIMIENTO</td>";
				$this->salida .= "			<td>DESCRIPCION</td>\n";
				$this->salida .= "			<td>AJUSTE</td>";
				$this->salida .= "			<td>TRANSF</td>";
				$this->salida .= "			<td>COMPRAS</td>";
				$this->salida .= "			<td>MEDICA</td>";
				$this->salida .= "			<td>&nbsp;</td>";
				$this->salida .= "			</tr>\n";
				$y=0;
				for($i=0;$i<sizeof($tiposDocumentos);$i++){
					if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					$this->salida .= "	 <tr class=\"$estilo\">\n";
					$this->salida .= "	 <td>".$tiposDocumentos[$i]['bodegas_doc_id']."</td>";
					$this->salida .= "	 <td>".$tiposDocumentos[$i]['nomtipodocumento']."</td>";
					$this->salida .= "	 <td>".$tiposDocumentos[$i]['prefijo']."</td>";
					$this->salida .= "	 <td>".$tiposDocumentos[$i]['tipomov']."</td>";
					$this->salida .= "	 <td>".$tiposDocumentos[$i]['descripcion']."</td>";
					if($tiposDocumentos[$i]['sw_ajuste']){
            $this->salida .= "	 <td><img border=\"0\" src=\"".GetThemePath()."/images/endturn.png\"></td>";
					}else{
            $this->salida .= "	 <td>&nbsp;</td>";
					}
					if($tiposDocumentos[$i]['sw_traslado']){
            $this->salida .= "	 <td><img border=\"0\" src=\"".GetThemePath()."/images/endturn.png\"></td>";
					}else{
            $this->salida .= "	 <td>&nbsp;</td>";
					}
					if($tiposDocumentos[$i]['sw_compras']){
            $this->salida .= "	 <td><img border=\"0\" src=\"".GetThemePath()."/images/endturn.png\"></td>";
					}else{
            $this->salida .= "	 <td>&nbsp;</td>";
					}
					if($tiposDocumentos[$i]['sw_transaccion_medicamentos']){
            $this->salida .= "	 <td><img border=\"0\" src=\"".GetThemePath()."/images/endturn.png\"></td>";
					}else{
            $this->salida .= "	 <td>&nbsp;</td>";
					}
          $actionEdit=ModuloGetURL('app','InvBodegas','user','EditarTipoDocumento',array("BodegaDocId"=>$tiposDocumentos[$i]['bodegas_doc_id'],"tipoDocumento"=>$tiposDocumentos[$i]['tipo_doc_bodega_id'],"prefijo"=>$tiposDocumentos[$i]['prefijo'],"descripcion"=>$tiposDocumentos[$i]['descripcion'],
					"movimiento"=>$tiposDocumentos[$i]['tipo_movimiento'],"digitos"=>$tiposDocumentos[$i]['numero_digitos'],"numeracion"=>($tiposDocumentos[$i]['numeracion']+1),
					"ajuste"=>$tiposDocumentos[$i]['sw_ajuste'],"traslado"=>$tiposDocumentos[$i]['sw_traslado'],"compras"=>$tiposDocumentos[$i]['sw_compras'],"medica"=>$tiposDocumentos[$i]['sw_transaccion_medicamentos']));
					$this->salida .= "	 <td><a href=\"$actionEdit\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/editar.png\"></a></td>";
					$y++;
				}
				$this->salida .= "			</tr>\n";
				$this->salida .= "      </table><BR>";
			}else{
        $this->salida .= "<table width=\"70%\" class=\"class=\"normal_10\"\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
				$this->salida .= "<tr><td class=\"label_error\" align=\"center\">NO EXISTEN TIPOS DE DOCUMENTOS CREADOS EN ESTA BODEGA</td></tr>";
				$this->salida .= "</table>";
			}
		}
		$this->salida .= "<table width=\"70%\" class=\"class=\"normal_10\"\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "<tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"VOLVER\" name=\"Salir\"></td></tr>";
		$this->salida .= "</table>";
		$this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

	function MtoFechasVencimiento($codigoProd,$descripcion){
	  $this->salida .= themeAbrirTabla('SELECCION PRODUCTO CAMBIO FECHAS VENCIMIENTO');
		$accion=ModuloGetURL('app','InvBodegas','user','BusquedaPtoFechasVmto');
		$this->salida .= "  <form name=\"formaBuscar\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "  <table class=\"modulo_table_list\" border=\"0\" width=\"85%\" align=\"center\" >";
		$this->salida .= "  <tr><td class=\"modulo_table_list_title\">FILTROS DE BUSQUEDA</td></tr>";
    $this->salida .= "	 <tr><td valign=\"top\" class=\"modulo_list_claro\" width=\"40%\"> ";
		$this->salida .= "      <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "	     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	     <td width=\"10%\" class=\"label\">CODIGO</td><td width=\"30%\"><input type=\"text\" class=\"input-text\" name=\"codigoProd\" value=\"$codigoProd\"></td>";
		$this->salida .= "		    <td width=\"10%\" class=\"label\">DESCRIPCION</td><td><input size=\"50\" type=\"text\" class=\"input-text\" name=\"descripcion\" value=\"$descripcion\"></td>";
		$this->salida .= "	    </tr>";
		$this->salida .= "	    </table><BR>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td align=\"center\" class=\"modulo_list_claro\"><input type=\"submit\" class=\"input-submit\" value=\"FILTRAR\" name=\"filtrar\"></td></tr>";
    $this->salida .= "	</table><BR>";
		$this->salida .= "	</form>";
		$accion1=ModuloGetURL('app','InvBodegas','user','MenuInventarios3');
		$this->salida .= "  <form name=\"forma\" action=\"$accion1\" method=\"post\">";
		$productos=$this->BuscarPtosModFechasVmto($codigoProd,$descripcion);
		if($productos){
			$this->salida .= "  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "  <tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida .= "  <td>COD PRODUCTO</td>";
			$this->salida .= "  <td>DESCRIPCION</td>";
			$this->salida .= "  <td>EXISTENCIAS</td>";
			$this->salida .= "	 <td>&nbsp;</td>";
			$this->salida .= "  </tr>";
			$y=0;
			for($i=0;$i<sizeof($productos);$i++){
				if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "	 <tr class=\"$estilo\">\n";
				$this->salida .= "	 <td>".$productos[$i]['codigo_producto']."</td>";
				$this->salida .= "	 <td>".$productos[$i]['descripcion']."</td>";
				$this->salida .= "	 <td>".$productos[$i]['existencia']."</td>";
				$actionCambio=ModuloGetURL('app','InvBodegas','user','LlamaModificacionFechaVmto',array("producto"=>$productos[$i]['codigo_producto'],"descripcion"=>$productos[$i]['descripcion'],"existencias"=>$productos[$i]['existencia']));
				$this->salida .= "	 <td align=\"center\"><a href=\"$actionCambio\"><img border=\"0\" src=\"".GetThemePath()."/images/pparamed.png\"></a></td>";
				$this->salida .= "  </tr>";
				$y++;
			}
			$this->salida .= "  </table>";
			$this->salida .=$this->RetornarBarra(8);
		}
		$this->salida .= "<BR><table width=\"70%\" class=\"class=\"normal_10\"\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
		$this->salida .= "<tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"VOLVER\" name=\"Salir\"></td></tr>";
		$this->salida .= "</table>";
		$this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ModificacionFechaVmto($producto,$descripcion,$existencias){
	  $accion=ModuloGetURL('app','InvBodegas','user','InsertarModifyFechas',array("producto"=>$producto,"descripcion"=>$descripcion,"existencias"=>$existencias));
	  $this->salida .= "  <form name=\"formaBuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= themeAbrirTabla('CAMBIO FECHAS VENCIMIENTO');
		$this->Encabezado();
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "       <tr><td width=\"100%\">";
		$this->salida .= "       <fieldset><legend class=\"field\">DATOS DE LAS EXISTENCIAS</legend>";
    $this->salida .= "       <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "	      <tr><td></td></tr>";
		$this->salida .= "	      <tr class=\"modulo_table_title\"><td colspan=\"2\" align=\"center\">$codProducto&nbsp&nbsp&nbsp;$descripcion</td></tr>";
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td><label class=\"label\">CODIGO</td>";
		$this->salida .= "	      <td>$producto</td>";
		$this->salida .= "	      </tr>";
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td><label class=\"label\">DESCRIPCION</td>";
		$this->salida .= "	      <td>$descripcion</td>";
		$this->salida .= "	      </tr>";
		$this->salida .= "	      <tr class=\"modulo_list_claro\">";
		$this->salida .= "	      <td><label class=\"label\">EXISTENCIAS</td>";
		$this->salida .= "	      <td>$existencias</td>";
		$this->salida .= "	      </tr>";
		$this->salida .= "	      <tr><td></td></tr>";
    $this->salida .= "			  </table>";
		$this->salida .= "		    </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
		//$ProductosDocumentoLotes=$this->ConsultaProductosDocumentoLotes($consecutivo,$codigo);
		if(!empty($ProductosDocumentoLotes)){
		$this->salida .= "    <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\" align=\"center\">";
    $this->salida .= "		  <td>FECHA VENCIMIENTO</td>";
		$this->salida .= "      <td>No. LOTE</td>";
		$this->salida .= "		  <td>CANTIDAD</td>";
		$this->salida .= "			 <td>&nbsp;</td>";
		$this->salida .= "     </tr>";
		$y=0;
		for($i=0;$i<sizeof($ProductosDocumentoLotes);$i++){
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "	  <tr class=\"$estilo\">\n";
			$this->salida .= "	  <td>".$ProductosDocumentoLotes[$i]['fecha_vencimiento']."</td>";
			$this->salida .= "	  <td>".$ProductosDocumentoLotes[$i]['lote']."</td>";
			$this->salida .= "	  <td>".$ProductosDocumentoLotes[$i]['cantidad']."</td>";
			$nombreProducto=urlencode($nombreProducto);
			$actionElim=ModuloGetURL('app','InvBodegas','user','EliminarRegistroFVLote',array('Documento'=>$Documento,'conceptoInv'=>$conceptoInv,'fechaDocumento'=>$fechaDocumento,'codigo'=>$codigo,'nombreProducto'=>$nombreProducto,'cantSolicitada'=>$cantSolicitada,'consecutivo'=>$consecutivo,'FechaVmto'=>$ProductosDocumentoLotes[$i]['fecha_vencimiento'],'lote'=>$ProductosDocumentoLotes[$i]['lote'],'cantidad'=>$ProductosDocumentoLotes[$i]['cantidad']));
			$this->salida .= "	  <td><a href=\"$actionElim\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
			$this->salida .= "	 </tr>\n";
			$y++;
		}
    $this->salida .= "     </table><BR>";
		}
    $this->salida .= "    <table class=\"normal_10\" border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "    <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "    </td><tr>";
		$this->salida .= "    <tr class=\"modulo_table_title\"><td align=\"center\">DATOS DEL PRODUCTO</td></tr>";
		//$sumaCant=$this->SumaCantidadesLotes($consecutivo,$codigo);
		$cantidadRest=$cantSolicitada-$sumaCant['sumacantidadeslotes'];
		$this->salida .= "    <tr class=\"modulo_table_title\"><td align=\"center\">CANTIDAD QUE FALTA POR INSERTAR ".$cantidadRest."</td></tr>";
		$this->salida .= "    <tr><td class=\"modulo_list_claro\">";
		$this->salida .= "      <BR><table class=\"normal_10\" cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "      <tr class=\"modulo_list_oscuro\">";
    $this->salida .= "      <td class=\"".$this->SetStyle("cantidadLote")."\">CANTIDAD</td><td><input type=\"text\" name=\"cantidadLote\" value=\"$cantidadLote\"></td>";
    $this->salida .= "      <td class=\"".$this->SetStyle("NoLote")."\">NUMERO LOTE</td><td><input type=\"text\" name=\"NoLote\" value=\"$NoLote\"></td>";
		$this->salida .= "	    <td class=\"".$this->SetStyle("FechaVmto")."\">FECHA VENCIMIENTO </td>";
		$this->salida .= "	    <td><input type=\"text\" name=\"FechaVmto\" value=\"$FechaVmto\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
		$this->salida .= "	    ".ReturnOpenCalendario('forma','FechaVmto','/')."</td>";
    $this->salida.= "       </tr>";
		$this->salida.= "       <tr>";
    $this->salida .= "	    <td align=\"center\" colspan=\"6\"><input class=\"input-submit\" type=\"submit\" name=\"insertar\" value=\"INSERTAR\"></td>";
    $this->salida.= "       </tr>";
		$this->salida.= "     </table><BR>";
		$this->salida .= "  </td></tr>";
		$this->salida.= "   </table>";
    $this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

	function EditarDespachoPendientes($datos,$CantDespachar,$datos_bodega,$concepto,$Ingreso,$SolicitudId,$TipoSolicitud,$EstacionId,$NombreEstacion,$Fecha,$usuarioestacion,
	  $nombrepac,$tipo_id_paciente,$paciente_id,$cama,$pendiente,$cancelar,$motivoCancelacion,$observaciones,$editar){

		$accion=ModuloGetURL('app','InvBodegas','user','InsertarDespachosPendientes',array("datos"=>$datos,"CantDespachar"=>$CantDespachar,"datos_bodega"=>$datos_bodega,"concepto"=>$concepto,"Ingreso"=>$Ingreso,"SolicitudId"=>$SolicitudId,"TipoSolicitud"=>$TipoSolicitud,"EstacionId"=>$EstacionId,"NombreEstacion"=>$NombreEstacion,"Fecha"=>$Fecha,"usuarioestacion"=>$usuarioestacion,
	  "nombrepac"=>$nombrepac,"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"cama"=>$cama,"pendiente"=>$pendiente,"cancelar"=>$cancelar,"motivoCancelacion"=>$motivoCancelacion,"observaciones"=>$observaciones,"editar"=>$editar));
    $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= themeAbrirTabla('OBSERVACIONES DE LA SOLICITUD');
		$this->Encabezado();
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "        <tr><td width=\"100%\">";
		$this->salida .= "        <fieldset><legend class=\"field\">DOCUMENTO SOLICITUD MEDICAMENTO</legend>";
		$this->salida .= "          <table class=\"normal_10\"cellspacing=\"2\" cellpadding=\"3\"border=\"0\"  width=\"95%\" align=\"center\">";
		$this->salida .= "	          <tr><td></td></tr>";
		$this->salida .= "	          <tr class=\"modulo_list_claro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">SOLICITANTE</td>";
		$this->salida .= "	          <td colspan=\"3\">$NombreEstacion</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">FECHA SOLICITUD</td>";
    (list($fecha,$HoraTot)=explode(' ',$Fecha));
    (list($ano,$mes,$dia)=explode('-',$fecha));
    (list($hora,$min)=explode(':',$HoraTot));
    $this->salida .= "            <td width=\"45%\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";          		
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">CODIGO SOLICITUD</td>";
		$this->salida .= "	          <td width=\"15%\">$SolicitudId</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">PACIENTE</td>";
		$this->salida .= "	          <td width=\"45%\">$tipo_id_paciente - $paciente_id  $nombrepac</td>";
		$this->salida .= "	          <td width=\"20%\"><label class=\"label\">CAMA</td>";
		$this->salida .= "	          <td width=\"15%\">$cama</td>";
		$this->salida .= "	          </tr>";
		$this->salida .= "	          <tr><td></td></tr>";
		$this->salida .= "			    </table>";
		$this->salida .= "		     </fieldset></td><BR>";
		$this->salida .= "       </table><BR><BR>";
		$this->salida .= "    <table class=\"normal_10\" border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "    <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "    </td><tr>";
		$this->salida .= "    </table>";
		$datos=$this->DatosObservacionesSolicitud($editar);
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
		if($datos){
    $this->salida .= "	          <tr>";
		$this->salida .= "	          <td class=\"modulo_table_list_title\" width=\"15%\" class=\"label\">USUARIO</td>";
		$this->salida .= "	          <td class=\"modulo_list_oscuro\" width=\"30%\">".$datos['usuario']."</td>";
		(list($fecha,$HoraTot)=explode(' ',$datos['fecha_registro']));
		(list($ano,$mes,$dia)=explode('-',$fecha));
		(list($hora,$min)=explode(':',$HoraTot));
		$this->salida .= "	          <td class=\"modulo_table_list_title\" width=\"20%\" class=\"label\">FECHA CREACION</td>";
    $this->salida .= "	          <td class=\"modulo_list_oscuro\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
		$this->salida .= "	          </tr>";
		}
    $this->salida .= "	          <tr class=\"modulo_list_claro\">";
		$this->salida .= "	          <td colspan=\"4\" align=\"center\"><textarea name=\"observacionesEdit\" cols=\"65\" rows=\"5\" class=\"textarea\">".$datos['observacion']."</textarea></td>";
    $this->salida .= "	          </tr>";
    $this->salida .= "       </table>";
		$this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
    $this->salida .= "	      <tr><td align=\"center\">";
		$this->salida .= "	      <input type=\"submit\" class=\"input-submit\" name=\"VOLVER\" value=\"VOLVER\">";
		$this->salida .= "	      <input type=\"submit\" class=\"input-submit\" name=\"GUARDAR\" value=\"GUARDAR\">";
		$this->salida .= "	      </td></tr>";
		$this->salida .= "       </table>";
		$this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

	function BuscadorProveedores($Documento,$fechaDocumento,$conceptoInv,
		$nombreProducto,$codigo,$unidadProducto,$ExisProducto,$costoProducto,$precioProducto,
		$cantSolicitada,$costoUnit,$tipoIdProveedor,$ProveedorId,
		$proveedor,$numFactura,$iva,$valorFletes,$otrosGastos,$observaciones,$TipoDocumentoBus,$DocumentoBus,$descripcionBus){
    $this->salida .= themeAbrirTabla('SELECCION PRODUCTO CAMBIO FECHAS VENCIMIENTO');
		$accion=ModuloGetURL('app','InvBodegas','user','LlamaBuscadorProveedores',array("Documento"=>$Documento,"fechaDocumento"=>$fechaDocumento,"conceptoInv"=>$conceptoInv,
		"nombreProducto"=>$nombreProducto,"codigo"=>$codigo,"unidadProducto"=>$unidadProducto,"ExisProducto"=>$ExisProducto,"costoProducto"=>$costoProducto,"precioProducto"=>$precioProducto,
		"cantSolicitada"=>$cantSolicitada,"costoUnit"=>$costoUnit,"tipoIdProveedor"=>$tipoIdProveedor,"ProveedorId"=>$ProveedorId,
		"proveedor"=>$proveedor,"numFactura"=>$numFactura,"iva"=>$iva,"valorFletes"=>$valorFletes,"otrosGastos"=>$otrosGastos,"observaciones"=>$observaciones));
		$this->salida .= "  <form name=\"formaBuscar\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "  <table class=\"modulo_table_list\" border=\"0\" width=\"85%\" align=\"center\" >";
		$this->salida .= "  <tr><td class=\"modulo_table_list_title\">FILTROS DE BUSQUEDA</td></tr>";
    $this->salida .= "	 <tr><td valign=\"top\" class=\"modulo_list_claro\" width=\"40%\"> ";
		$this->salida .= "      <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "	     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	     <td width=\"20%\" class=\"label\">TIPO DOCUMENTO</td>";
		$this->salida .= "		   <td><select name=\"TipoDocumentoBus\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->Mostrar($tipo_id,'False',$TipoDocumentoBus);
		for($i=0;$i<sizeof($tipo_id);$i++){
			$value=$tipo_id[$i]['tipo_id_tercero'];
			$titulo=$tipo_id[$i]['descripcion'];
			if($value==$TipoDocumentoBus){
				$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}
		$this->salida .= "      </select></td>";
		$this->salida .= "		   <td class=\"".$this->SetStyle("DocumentoBus")."\">DOCUMENTO </td>";
		$this->salida .= "		   <td><input type=\"text\" class=\"input-text\" name=\"DocumentoBus\" maxlength=\"32\" value=\"$DocumentoBus\"></td>";
    $this->salida .= "	    </tr>";
		$this->salida .= "	     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "		   <td class=\"label\">DESCRIPCION</td>";
		$this->salida .= "		   <td colspan=\"3\"><input size=\"50\" type=\"text\" class=\"input-text\" name=\"descripcionBus\" value=\"$descripcionBus\"></td>";
		$this->salida .= "	    </tr>";
		$this->salida .= "	    </table><BR>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td align=\"center\" class=\"modulo_list_claro\">";
		$this->salida .= "  <input type=\"submit\" class=\"input-submit\" value=\"FILTRAR\" name=\"filtrar\">";
    $this->salida .= "  <input class=\"input-submit\" type=\"submit\" value=\"VOLVER\" name=\"Volver\">";
		$this->salida .= "  </td></tr>";
    $this->salida .= "	</table><BR>";
		$proveedores=$this->BuscarProveedoresProductos($TipoDocumentoBus,$DocumentoBus,$descripcionBus);
		if($proveedores){
			$this->salida .= "  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "  <tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida .= "  <td>TIPO ID PROVEEDOR</td>";
			$this->salida .= "  <td>DESCRIPCION</td>";
			$this->salida .= "  <td width=\"5%\">&nbsp;</td>";
			$this->salida .= "  </tr>";
			$y=0;
			for($i=0;$i<sizeof($proveedores);$i++){
				if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "	 <tr class=\"$estilo\">\n";
				$this->salida .= "	 <td>".$proveedores[$i]['tipo_id_tercero']."  ".$proveedores[$i]['tercero_id']."</td>";
				$this->salida .= "	 <td>".$proveedores[$i]['nombre_tercero']."</td>";
				$action=ModuloGetURL('app','InvBodegas','user','LlamaBuscadorProveedores',array("centinela"=>1,"Documento"=>$Documento,"fechaDocumento"=>$fechaDocumento,"conceptoInv"=>$conceptoInv,
				"nombreProducto"=>$nombreProducto,"codigo"=>$codigo,"unidadProducto"=>$unidadProducto,"ExisProducto"=>$ExisProducto,"costoProducto"=>$costoProducto,"precioProducto"=>$precioProducto,
				"cantSolicitada"=>$cantSolicitada,"costoUnit"=>$costoUnit,"tipoIdProveedor"=>$tipoIdProveedor,"ProveedorId"=>$ProveedorId,
				"proveedor"=>$proveedor,"numFactura"=>$numFactura,"iva"=>$iva,"valorFletes"=>$valorFletes,"otrosGastos"=>$otrosGastos,"observaciones"=>$observaciones,"TipoProveedor"=>$proveedores[$i]['tipo_id_tercero'],"ProveedorIdd"=>$proveedores[$i]['tercero_id'],
				"NomTercero"=>$proveedores[$i]['nombre_tercero']));
				$this->salida .= "	 <td align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
				$this->salida .= "  </tr>";
				$y++;
			}
			$this->salida .= "  </table>";
			$this->salida .=$this->RetornarBarra(10);
		}else{
      $this->salida .= "<BR><table width=\"70%\" class=\"class=\"normal_10\"\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
			$this->salida .= "<tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
			$this->salida .= "</table>";
		}
		$this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

  function SolicitudesSuministroEst(){
    $this->salida .= themeAbrirTabla('SOLICITUDES DE SUMINISTROS DE LA ESTACION');
		//$accion=ModuloGetURL('app','InvBodegas','user','',array());
		$this->salida .= "  <form name=\"formaBuscar\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
    $solicitudes=$this->ConsultaSolicitudesSuministrosEst();
    if($solicitudes){
      $this->salida .= "  <table class=\"modulo_table_list\" border=\"0\" width=\"65%\" align=\"center\" >";
      $this->salida .= "  <tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida .= "  <td colspan=\"2\" width=\"55%\">ESTACION SOLICITANTE</td>";
			$this->salida .= "  <td width=\"25%\">FECHA</td>";
      $this->salida .= "  <td width=\"20%\">CODIGO</td>";
			$this->salida .= "  </tr>";
			$y=0;
      $estacionAnt=-1;
			foreach($solicitudes as $estacion=>$vectorDat){
				if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        foreach($vectorDat as $solicitud=>$vector){
          $this->salida .= "	 <tr class=\"$estilo\">\n";
          if($estacionAnt!=$estacion){
            $action=ModuloGetURL('app','InvBodegas','user','LlamaDetalleSolicitudSuministros',array("estacion_id"=>$vector['estacion_id'],"estacion"=>$estacion));
            $this->salida .= "    <td rowspan=\"".sizeof($vectorDat)."\"><a href=\"$action\"><img title=\"Detalle Solicitudes\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a></td>";
            $this->salida .= "    <td rowspan=\"".sizeof($vectorDat)."\">$estacion</td>";
            $estacionAnt=$estacion;
          }
          (list($ano,$mes,$dia)=explode('-',$vector['fecha']));
		      $this->salida .= "	<td align=\"center\">".ucfirst((strftime('%b %d de %Y',mktime(0,0,0,$mes,$dia,$ano))))."</td>";
          $this->salida .= "  <td>".$vector['solicitud_id']."</td>";
          $this->salida .= "  </tr>";
        }
        $y++;
      }
      $this->salida .= "  </table>";
    }else{
      $this->salida .= "  <table border=\"0\" width=\"85%\" align=\"center\" >";
      $this->salida .= "  <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS</td></tr>";
      $this->salida .= "  </table><BR>";
    }
    $this->salida .= "  </form>";
    $accion=ModuloGetURL('app','InvBodegas','user','MenuInventariosDespachos');
		$this->salida .= "  <form name=\"formaBuscar\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <table border=\"0\" width=\"85%\" align=\"center\" >";
    $this->salida .= "  <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "  </table>";
    $this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
  }

  function DetalleSolicitudSuministros($estacion_id,$estacion){
    $Seleccion=$_REQUEST['Seleccion'];
    $Cantidades=$_REQUEST['Cantidades'];
    $this->salida .="<script>";
    $this->salida .="function CancelarSuministro(consec,Solicitud){";
    $this->salida.="    document.forma.ConsecutivoCancel.value=consec;";
    $this->salida.="    document.forma.SolicitudCancel.value=Solicitud;";
    $this->salida.="    document.forma.submit();";
    $this->salida .="}";
    $this->salida .="</script>";
    $this->salida .= themeAbrirTabla('SUMINISTROS SOLICITADOS POR LA ESTACION');
		$accion=ModuloGetURL('app','InvBodegas','user','GuardarSolicitudesSuministros',array("estacion_id"=>$estacion_id,"estacion"=>$estacion));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
    $datos=$this->RegDetalleSolicitudSuministros($estacion_id);
    $this->salida .= "  <input type=\"hidden\" name=\"ConsecutivoCancel\">";
    $this->salida .= "  <input type=\"hidden\" name=\"SolicitudCancel\">";
    $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "    <tr><td align=\"center\">";
    $this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= "    </td><tr>";
    $this->salida .= "  </table>";
    if($datos){
      $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" >";
      $this->salida .= "  <tr class=\"modulo_table_list_title\" align=\"center\">";
      $this->salida .= "  <td colspan=\"11\" align=\"center\">ESTACION :&nbsp&nbsp&nbsp&nbsp; $estacion</td>";
      $this->salida .= "  </tr>";
      $this->salida .= "  <tr class=\"modulo_table_list_title\" align=\"center\">";
      $this->salida .= "  <td width=\"5%\" align=\"center\">SOLICITUD</td>";
      $this->salida .= "  <td width=\"5%\" align=\"center\">CODIGO</td>";
      $this->salida .= "  <td align=\"center\">PRODUCTO</td>";
      $this->salida .= "  <td width=\"5%\" align=\"center\">CANTIDAD SOLICITADA</td>";
      $this->salida .= "  <td width=\"5%\" align=\"center\">CANTIDAD DESPACHADA</td>";
      $this->salida .= "  <td width=\"5%\" align=\"center\">CANTIDAD PENDIENTE</td>";
      $this->salida .= "  <td width=\"5%\" align=\"center\">EXIST. BODEGA</td>";
      $this->salida .= "  <td width=\"5%\" align=\"center\">CANTIDAD A DESPACHAR</td>";
      $this->salida .= "  <td width=\"5%\" align=\"center\">&nbsp;</td>";
      $this->salida .= "  <td align=\"center\">MOTIVO CANCELACION</td>";
      $this->salida .= "  <td width=\"5%\" align=\"center\">&nbsp;</td>";
      $this->salida .= "  </tr>";
      $solicitudIdAnt=-1;
      $Seleccion=$_REQUEST['Seleccion'];
      $Cantidades=$_REQUEST['Cantidades'];
      foreach($datos as $solicitudId=>$vector){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        foreach($vector as $producto=>$datosSol){
          $this->salida .= "	 <tr class=\"$estilo\">\n";
          if($solicitudIdAnt!=$solicitudId){
            $this->salida .= "  <td rowspan=\"".sizeof($vector)."\" align=\"center\">".$solicitudId."</td>";
            $solicitudIdAnt=$solicitudId;
          }
          $this->salida .= "  <td>".$datosSol['codigo_producto']."</td>";
          $this->salida .= "  <td>".$datosSol['descripcion']."</td>";
          $this->salida .= "  <td>".$datosSol['cantidad_solicitada']."</td>";
          $this->salida .= "  <td>".$datosSol['cantidad_despachada']."</td>";
          $this->salida .= "  <td>".$datosSol['cantidad_pendiente']."</td>";
          $this->salida .= "  <td>".$datosSol['existencia']."</td>";
          $this->salida .= "  <input type=\"hidden\" name=\"Existencias[".$solicitudId."][".$datosSol['consecutivo']."]\" value=\"".$datosSol['existencia']."\">";
          $che='';
          $cantidad=$datosSol['cantidad_pendiente'];
          if($Seleccion[$solicitudId][$datosSol['consecutivo']]){
            $che='checked';
            if($Cantidades[$solicitudId][$datosSol['consecutivo']]){
            $cantidad=$Cantidades[$solicitudId][$datosSol['consecutivo']];
            }
          }
          $this->salida .= "  <td align=\"center\"><input size=\"8\" type=\"text\" name=\"Cantidades[".$solicitudId."][".$datosSol['consecutivo']."]\" value=\"".$cantidad."\"></td>";
          $this->salida .= "  <td align=\"center\"><input $che type=\"checkbox\" name=\"Seleccion[".$solicitudId."][".$datosSol['consecutivo']."]\" value=\"".$datosSol['cantidad_pendiente']."\"></td>";
          $this->salida .= "  <td>";
          $this->salida .= "   <select name=\"MotivoCancel[".$solicitudId."][".$datosSol['consecutivo']."]\"  class =\"select\">";
			    $this->salida .= "   <option value =\"-1\" selected>--Seleccione---</option>";
          $motivos=$this->MotivosCancelacionSuministros();
          for($i=0;$i<sizeof($motivos);$i++){
          $this->salida .= "   <option value =\"".$motivos[$i]['motivo_id']."\">".$motivos[$i]['descripcion']."</option>";
          }
          $this->salida .= "   </td>";
          $this->salida .= "  <td align=\"center\"><a href=\"javascript:CancelarSuministro(".$datosSol['consecutivo'].",$solicitudId)\"><img title=\"Cancelar Cantidad Pendiente\" border=\"0\" src=\"".GetThemePath()."/images/delete.gif\"></a></td>";
          $this->salida .= "  </tr>";
        }
        $y++;
      }
      $this->salida .= "  </table>";
      $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" >";
      $this->salida .= "  <tr><td align=\"right\"><input type=\"submit\" class=\"input-submit\" name=\"Despachar\" value=\"DESPACHAR SUMINISTROS\"></td></tr>";
      $this->salida .= "  </table><BR>";
    }else{
      $this->salida .= "  <table border=\"0\" width=\"85%\" align=\"center\" >";
      $this->salida .= "  <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON NUEVOS REGISTROS</td></tr>";
      $this->salida .= "  </table><BR>";
    }
    $this->salida .= "  </form>";
    $accion=ModuloGetURL('app','InvBodegas','user','LlamaSolicitudesSuministroEst');
		$this->salida .= "  <form name=\"formaBuscar\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <table border=\"0\" width=\"85%\" align=\"center\" >";
    $this->salida .= "  <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\"></td></tr>";
    $this->salida .= "  </table>";
    $this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
  }

  function SolicitudesProductosResposables($solicitudBus,$estacionBus,$usuarioResBus,$EstadoBus,$FechaBus){
    $this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
    $this->salida .= themeAbrirTabla('SOLICITUDES INSUMOS Y MEDICAMENTOS PARA RESPONSABLES');
    $this->salida .="<script language='javascript'>";
    $this->salida .= "function cambioEstacion(frm){";
		$this->salida .= "  frm.submit();";
		$this->salida .= "}";
		$this->salida .= 'function mOvr(src,clrOver){';
		$this->salida .= '  src.style.background = clrOver;';
		$this->salida .= '}';
		$this->salida .= 'function mOut(src,clrIn){';
		$this->salida .= '  src.style.background = clrIn;';
		$this->salida .= '}';
		$this->salida .= '</script>';
		$accion=ModuloGetURL('app','InvBodegas','user','LlamaSolicitudesProductosResposables');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
    $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" >";
    $this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
    $this->salida .= "  </table>";
    $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "       <tr><td width=\"100%\">";
		$this->salida .= "       <fieldset><legend class=\"field\">FILTRO DE BUSQUEDA</legend>";
    $this->salida .= "       <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "        <tr>";
    $this->salida .= "        <td class=\"".$this->SetStyle("solicitudBus")."\" width=\"25%\" nowrap>No. SOLICITUD</td>";
    $this->salida .= "        <td><input type=\"text\" size=\"20\" name=\"solicitudBus\" value=\"$solicitudBus\" class=\"input-text\"></td>";
    $this->salida .= "        </tr>";
    $this->salida .= "        <tr>";
    $this->salida .= "        <td class=\"".$this->SetStyle("estacionBus")."\" width=\"25%\" nowrap>ESTACION</td>";
    $this->salida .= "        <td><select name=\"estacionBus\"  class=\"select\" onchange=\"cambioEstacion(this.form)\" >";
    $this->salida .="         <option value=\"-1\" selected>---Seleccione---</option>";
		$Estaciones=$this->EstacionesBodega();
    foreach($Estaciones as $estacionId=>$datos){
      if($estacionId==$estacionBus){
        $this->salida .=" <option value=\"$estacionId\" selected>".$datos['descripcion']."</option>";
      }else{
        $this->salida .=" <option value=\"$estacionId\">".$datos['descripcion']."</option>";
      }
    }
		$this->salida .= "        </select></td>";
    $this->salida .= "        </tr>";
    $this->salida .= "        <tr>";
    $this->salida .= "        <td class=\"".$this->SetStyle("usuarioResBus")."\" width=\"25%\" nowrap>USUARIO RESPONSABLE</td>";
    $this->salida .= "        <td><select name=\"usuarioResBus\" class=\"select\">";
    $this->salida .="         <option value=\"-1\" selected>---Seleccione---</option>";
		$usuarios=$this->UsuariosResponsablesSol($estacionBus);
    foreach($usuarios as $usuarioId=>$datos){
      if($usuarioId==$usuarioResBus){
        $this->salida .="     <option value=\"$usuarioId\" selected>".$datos['nombre']."</option>";
      }else{
        $this->salida .="     <option value=\"$usuarioId\">".$datos['nombre']."</option>";
      }
    }
		$this->salida .= "        </select></td>";
    $this->salida .= "        </tr>";
    $this->salida .= "		    <tr>";
		$this->salida .= "	  	  <td class=\"".$this->SetStyle("FechaBus")."\">FECHA </td>";
		$this->salida .= "	  	  <td><input size=\"10\" maxlength=\"10\" type=\"text\" name=\"FechaBus\" value=\"".$FechaBus."\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\"><input type=\"hidden\" value=\"$FechaNacimientoCalculada\" name=\"FechaNacimientoCalculada\">";
		$this->salida .= "	  	  ".ReturnOpenCalendario('forma','FechaBus','/')."</td>";
		$this->salida .= "		    </tr>";
    $this->salida .= "        <tr>";
    $this->salida .= "        <td class=\"".$this->SetStyle("EstadoBus")."\" width=\"25%\" nowrap>ESTADO</td>";
    $this->salida .= "        <td><select name=\"EstadoBus\" class=\"select\">";
    $che=$che0=$che1=$che2='';
    if($EstadoBus=='1'){
      $che0='selected';
    }elseif($EstadoBus=='2'){
      $che1='selected';
    }elseif($EstadoBus=='3'){
      $che2='selected';
    }else{
      $che='selected';
    }
    $this->salida .="         <option value=\"-1\" $che>---Todas---</option>";
    $this->salida .="         <option value=\"1\" $che0>SOLICITUDES NO AJUSTADAS</option>";
    $this->salida .="         <option value=\"2\" $che1>SOLICITUDES AJUSTADAS</option>";
    $this->salida .="         <option value=\"3\" $che2>CANCELADAS</option>";
		$this->salida .= "        </select></td>";
    $this->salida .= "        </tr>";
    $this->salida .= "		    <tr><td colspan=\"2\" align=\"center\">";
    $this->salida .= "		    <input type=\"submit\" value=\"FILTRAR\" name=\"Filtrar\" class=\"input-submit\">";
    $this->salida .= "		    <input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
    $this->salida .= "		    </td></tr>";
    $this->salida .= "			  </table>";
		$this->salida .= "		    </fieldset></td><BR>";
		$this->salida .= "       </table><BR>";
    $datos=$this->BusquedaSolicitudesResponsanble($solicitudBus,$estacionBus,$usuarioResBus,$EstadoBus,$FechaBus);
    if($datos){
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\" >";
      $this->salida .= "  <tr class=\"modulo_table_list_title\" align=\"center\">";
      $this->salida .= "  <td width=\"15%\" align=\"center\">ESTACION</td>";
      $this->salida .= "  <td width=\"15%\" align=\"center\">No. SOLICITUD</td>";
      $this->salida .= "  <td align=\"center\">RESPONSABLE DESPACHO</td>";
      $this->salida .= "  <td width=\"15%\" align=\"center\">FECHA DESPACHO</td>";
      //$this->salida .= "  <td width=\"10%\" align=\"center\">ESTADO DESPACHO</td>";
      $this->salida .= "  <td width=\"5%\" align=\"center\">&nbsp;</td>";
      $this->salida .= "  </tr>";
      $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
      foreach($datos as $estacion_id=>$vector){
        $estacion_idAnt=-1;
        foreach($vector as $nom_estacion=>$vector1){
          foreach($vector1 as $solicitid=>$datosVec){
            if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
            $this->salida .= "  <tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
            if($estacion_id!=$estacion_idAnt){
              $this->salida .= "  <td rowspan=\"".sizeof($vector1)."\">".$nom_estacion."</td>";
              $estacion_idAnt=$estacion_id;
            }
            $this->salida .= "  <td>".$solicitid."</td>";
            $this->salida .= "  <td>".$datosVec['nom_responsable']."</td>";
            (list($fecha,$hora)=explode(' ',$datosVec['fecha_registro']));
            (list($ano,$mes,$dia)=explode('-',$fecha));
            (list($hh,$mm)=explode(':',$hora));
            $this->salida .= "  <td>".ucfirst(strftime("%b %d de %Y",mktime($hh,$mm,0,$mes,$dia,$ano)))."</td>";
            if($datosVec['estado_modifi']!='1'){
              $actionM=ModuloGetURL('app','InvBodegas','user','ModificacionSolicitudResponsable',array("NoSolicitud"=>$solicitid,"solicitudBus"=>$solicitudBus,"estacionBus"=>$estacionBus,
              "usuarioResBus"=>$usuarioResBus,"EstadoBus"=>$EstadoBus,"FechaBus"=>$FechaBus));
              $this->salida .= "  <td align=\"center\"><a href=\"$actionM\"><img title=\"Modificar Solicitud\" border=\"0\" src=\"".GetThemePath()."/images/pmodificar.png\"></td>";
            }else{
              $actionC=ModuloGetURL('app','InvBodegas','user','ConsultaSolicitudResponsable',array("NoSolicitud"=>$solicitid,"solicitudBus"=>$solicitudBus,"estacionBus"=>$estacionBus,
              "usuarioResBus"=>$usuarioResBus,"EstadoBus"=>$EstadoBus,"FechaBus"=>$FechaBus));
              $this->salida .= "  <td class=\"label\"><a href=\"$actionC\">AJUSTADA</a></td>";
            }
            $this->salida .= "  </tr>";
          }
        }
      }
      $this->salida .= "  </table>";
      $Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','InvBodegas','user','LlamaSolicitudesProductosResposables',array("solicitudBus"=>$solicitudBus,"estacionBus"=>$estacionBus,"usuarioResBus"=>$usuarioResBus,
      "EstadoBus"=>$EstadoBus,"FechaBus"=>$FechaBus));
      $this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
    }else{
      $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" >";
      $this->salida .= "  <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS</td></tr>";
      $this->salida .= "  </table>";
    }
    $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\" >";
    $actionE=ModuloGetURL('app','InvBodegas','user','LlamaCreacionSolicitudResponsable');
    $this->salida .= "  <tr><td class=\"label\" align=\"right\"><a href=\"$actionE\"><img border=\"0\" src=\"".GetThemePath()."/images/pplan.png\">&nbsp&nbsp;NUEVA SOLICITUD</a></td></tr>";
    $this->salida .= "  </table>";
    $this->salida .= "  </form>";
    /*$accion=ModuloGetURL('app','InvBodegas','user','MenuInventariosDespachos');
    $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "  <tr><td class=\"label\" align=\"center\"><input type=\"submit\" align=\"center\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
    $this->salida .= "  </table>";
    $this->salida .= "  </form>";*/
    $this->salida .= ThemeCerrarTabla();
		return true;
  }

  function CreacionSolicitudResponsable($solicitudBus,$estacionBus,$usuarioResBus,$EstadoBus,$FechaBus){
    $this->salida .= themeAbrirTabla('NUEVA SOLICITUD DE INSUMOS Y MEDICAMENTOS PARA RESPONSABLES');
    $this->Encabezado();
    $this->salida .="<script language='javascript'>";
		$this->salida .= "function cambioEstacion(frm){";
		$this->salida .= "  frm.submit();";
		$this->salida .= "}";
		$this->salida .= '</script>';
    $accion=ModuloGetURL('app','InvBodegas','user','LlamaCreacionSolicitudResponsable',array("solicitudBus"=>$solicitudBus,
    "estacionBus"=>$estacionBus,"usuarioResBus"=>$usuarioResBus,"EstadoBus"=>$EstadoBus,"FechaBus"=>$FechaBus));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <table width=\"60%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">SELECCION ESTACION Y RESPONSABLE SOLICITUD</legend>";
    $this->salida .= "  <BR><table border=\"0\" width=\"95%\" align=\"center\" >";
    if($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['SOLICITUD']){
      $this->salida .= "  <tr>";
      $this->salida .= "    <td class=\"".$this->SetStyle("estacion")."\" width=\"25%\" nowrap>No. SOLICITUD</td>";
      $this->salida .= "    <td class=\"normal_10N\">".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['SOLICITUD']."</td>";
      $this->salida .= "  </tr>";    
    }
    $this->salida .= "  <tr>";
    $this->salida .= "    <td class=\"".$this->SetStyle("estacion")."\" width=\"25%\" nowrap>ESTACION</td>";
    $this->salida .= "    <td><select name=\"estacion\"  class=\"select\" onchange=\"cambioEstacion(this.form)\" >";
    $this->salida .="     <option value=\"-1\" selected>---Seleccione---</option>";
		$Estaciones=$this->EstacionesBodega();
    foreach($Estaciones as $estacionId=>$datos){
      if($estacionId==$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ESTACION']){
        $this->salida .=" <option value=\"$estacionId\" selected>".$datos['descripcion']."</option>";
      }else{
        $this->salida .=" <option value=\"$estacionId\">".$datos['descripcion']."</option>";
      }
    }
		$this->salida .= "    </select></td>";
    $this->salida .= "  </tr>";
    $this->salida .= "  <tr>";
    $this->salida .= "    <td class=\"".$this->SetStyle("usuarioRes")."\" width=\"25%\" nowrap>USUARIO RESPONSABLE</td>";
    $this->salida .= "    <td><select name=\"usuarioRes\" class=\"select\">";
    $this->salida .="     <option value=\"-1\" selected>---Seleccione---</option>";
		$usuarios=$this->UsuariosResponsablesSol($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ESTACION']);
    foreach($usuarios as $usuarioId=>$datos){
      if($usuarioId==$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['RESPONSABLE']){
        $this->salida .=" <option value=\"$usuarioId\" selected>".$datos['nombre']."</option>";
      }else{
        $this->salida .=" <option value=\"$usuarioId\">".$datos['nombre']."</option>";
      }
    }
		$this->salida .= "    </select></td>";
    $this->salida .= "  </tr>";
    $this->salida .= "  </table><BR>";
    $this->salida .= "	 </fieldset>";
    $this->salida .= "	 </td></tr>";
		$this->salida .= "	 </table><br>";
    $this->salida .= "  <table width=\"90%\" border=\"0\" align=\"center\">";
    if(sizeof($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['PRODUCTOS'])>0){
      $this->salida .= "  <tr class=\"modulo_table_title\">";
      $this->salida .= "  <td width=\"15%\" >CODIGO</td>";
      $this->salida .= "  <td>DESCRIPCION</td>";
      $this->salida .= "  <td width=\"15%\">EXISTENCIAS</td>";
      $this->salida .= "  <td width=\"15%\">ESTADO</td>";
      $this->salida .= "  <td width=\"15%\">CANTIDAD<br>DESPACHADA</td>";
      $this->salida .= "  <td width=\"15%\">CANTIDAD<br>AJUSTADA</td>";
      $this->salida .= "  <td width=\"5%\">&nbsp;</td>";
      $this->salida .= "  </tr>";
      foreach($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['PRODUCTOS'] as $codigo=>$Vect){
        foreach($Vect as $descrip=>$canti){
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">";
          $this->salida .= "  <td align=\"left\" width=\"15%\" >$codigo</td>";
          $this->salida .= "  <td align=\"left\">$descrip</td>";
          $this->salida .= "  <td align=\"center\" width=\"15%\">".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['EXISTENCIAS_PRODUCTOS'][$codigo]."</td>";
          if($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ESTADOS_PRODUCTOS'][$codigo]==2){
            $this->salida .= "  <td>AJUSTADA</td>";
            $this->salida .= "  <td>$canti</td>";
            $this->salida .= "  <td>".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['CANTIDADES_AJUSTADAS'][$codigo]."</td>"; 
            $this->salida .= "  <td>&nbsp;</td>";             
          }elseif($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ESTADOS_PRODUCTOS'][$codigo]==3){
            $this->salida .= "  <td class=\"label_error\">CANCELADA X<BR>CONFIRMAR</td>";
            $this->salida .= "  <td>$canti</td>";
            $this->salida .= "  <td>".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['CANTIDADES_AJUSTADAS'][$codigo]."</td>"; 
            $actionSel=ModuloGetURL('app','InvBodegas','user','ConfirmarCancelacionDetalle',array("producto"=>$codigo,"destino"=>1));
            $this->salida .= "   <td  width=\"5%\" align=\"center\"><a title=\"Confirmar Entrega de Productos\" href=\"$actionSel\"><img border=\"0\" src=\"".GetThemePath()."/images/egresook.png\"></a></td>";
          }elseif($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ESTADOS_PRODUCTOS'][$codigo]==4){
            $this->salida .= "  <td>CANCELADA<BR>CONFIRMADA</td>";
            $this->salida .= "  <td>$canti</td>";
            $this->salida .= "  <td>".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['CANTIDADES_AJUSTADAS'][$codigo]."</td>"; 
            $this->salida .= "  <td>&nbsp;</td>";                                  
          }else{
            $this->salida .= "  <td>&nbsp;</td>";
            $this->salida .= "  <td align=\"center\" width=\"15%\"><input size=\"3\" type=\"text\" class=\"text-input\" name=\"cantidad[$codigo]\" value=\"$canti\"></td>";            
            $this->salida .= "  <td>".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['CANTIDADES_AJUSTADAS'][$codigo]."</td>"; 
            if($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['CANTIDADES_AJUSTADAS'][$codigo]>0){
              $this->salida .= "  <td>&nbsp;</td>";              
            }else{
              $actionSel=ModuloGetURL('app','InvBodegas','user','BorrarProductoResponsable',array("producto"=>$codigo,"destino"=>1,"solicitudBus"=>$solicitudBus,"estacionBus"=>$estacionBus,"usuarioResBus"=>$usuarioResBus,"EstadoBus"=>$EstadoBus,"FechaBus"=>$FechaBus));
              $this->salida .= "   <td  width=\"5%\" align=\"center\"><a title=\"Eliminar\" href=\"$actionSel\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
            }            
          }         
          $this->salida .= "  </tr>";
        }
      }
    }else{
      $this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"7\" align=\"center\" class=\"label_error\">NO SE HAN SELECCIONADO PRODUCTOS PARA LA SOLICITUD</td></tr>";
    }
    $this->salida .= "  <tr class=\"modulo_table_title\"><td align=\"center\" colspan=\"7\"><input type=\"submit\" name=\"SeleccionProd\" value=\"SELECCIONAR PRODUCTO\" class=\"input-submit\"></td></tr>";
    $this->salida .= "	</table>";
    if(sizeof($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['PRODUCTOS'])>0){
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\" >";
      $this->salida .= "  <tr><td class=\"label\" align=\"right\"><input type=\"submit\" align=\"center\" name=\"Guardar\" value=\"GUARDAR SOLICITUD\" class=\"input-submit\"></td></tr>";
      $this->salida .= "  </table>";
    }
    $this->salida .= "  </form>";
    $accion=ModuloGetURL('app','InvBodegas','user','LlamaSolicitudesProductosResposables',array("solicitudBus"=>$solicitudBus,"estacionBus"=>$estacionBus,"usuarioResBus"=>$usuarioResBus,"EstadoBus"=>$EstadoBus,"FechaBus"=>$FechaBus));
    $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "  <tr><td class=\"label\" align=\"center\"><input type=\"submit\" align=\"center\" name=\"VolverLista\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
    $this->salida .= "  </table>";
    $this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
  }

  function FormaBuscadorProductosBodega($codigoProd,$descripcion,$grupo,$NomGrupo,$clasePr,$NomClase,$subclase,$NomSubClase){

    $this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
    $this->salida .= themeAbrirTabla('BUSCADOR PRODUCTOS DE LA BODEGA');
    $this->Encabezado();
    $this->salida .= "<script>";
		$this->salida .="function abrirVentanaClass(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .=" f=frm;\n";
		$this->salida .=" var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
		$this->salida .=" var ban=1;";
		$this->salida .=" var url2 = url+'?bandera='+ban;";
		$this->salida .=" var rems = window.open(url2, nombre, str);\n";
		$this->salida .=" if (rems != null) {\n";
		$this->salida .="   if (rems.opener == null) {\n";
		$this->salida .="	    rems.opener = self;\n";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .="}\n";
		$this->salida .= 'function mOvr(src,clrOver){';
		$this->salida .= '  src.style.background = clrOver;';
		$this->salida .= '}';
		$this->salida .= 'function mOut(src,clrIn){';
		$this->salida .= '  src.style.background = clrIn;';
		$this->salida .= '}';
		$this->salida .= "</script>";
    $accion=ModuloGetURL('app','InvBodegas','user','LlamaFormaBuscadorProductosBodega');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= "         <table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\" >";
		$this->salida .= "         <tr><td colspan=\"2\" class=\"modulo_table_list_title\">FILTROS DE BUSQUEDA</td></tr>";
    $this->salida .= "         <tr><td class=\"modulo_list_claro\" width=\"60%\">";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "	  	   <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">GRUPO:</td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomGrupo\" value=\"$NomGrupo\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "         <input type=\"hidden\" name=\"grupo\" value=\"$grupo\" class=\"input-text\">";
		$this->salida .= "	  	   </tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">CLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomClase\"  value=\"$NomClase\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "         <input type=\"hidden\" name=\"clasePr\" value=\"$clasePr\" class=\"input-text\" >";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	   <td width=\"10%\" class=\"label\">SUBCLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" name=\"NomSubClase\"  value=\"$NomSubClase\" size=\"30\" class=\"input-text\" readonly>";
		$this->salida .= "         <input type=\"hidden\" name=\"subclase\" value=\"$subclase\" class=\"input-text\" >";
		$ruta='app_modules/InvBodegas/ventanaClasificacion.php';
		$this->salida .= "         <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\"></td>";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     </table><BR><BR>";
    $this->salida .= "		     </td>";
    $this->salida .= "		     <td valign=\"top\" class=\"modulo_list_claro\" width=\"40%\"> ";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">CODIGO</td><td><input type=\"text\" class=\"input-text\" name=\"codigoProd\" value=\"$codigoProd\"></td></tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">DESCRIPCION</td><td><input type=\"text\" class=\"input-text\" name=\"descripcion\" value=\"$descripcion\"></td></tr>";
		$this->salida .= "		     </table><BR>";
		$this->salida .= "         </td></tr>";
		$this->salida .= "         <tr><td colspan=\"2\" align=\"center\" class=\"modulo_list_claro\">";
    $this->salida .= "         <input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"volver\">";
    $this->salida .= "         <input type=\"submit\" class=\"input-submit\" value=\"FILTRAR\" name=\"filtrar\">";
    $this->salida .= "         </td></tr>";
    $this->salida .= "		     </table><BR>";
    $ExistenciasBodegas=$this->ConsultaExistenciasBodegas($grupo,$clasePr,$subclase,$codigoProd,$descripcion,$filtroEstado=1);
		if($ExistenciasBodegas){
      $this->salida .= "	  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
      $this->salida .= "    <tr>";
      $this->salida .= "			<td width=\"15%\" class=\"modulo_table_list_title\">CODIGO</td>\n";
      $this->salida .= "			<td class=\"modulo_table_list_title\">DESCRIPCION</td>\n";
      $this->salida .= "			<td width=\"15%\" class=\"modulo_table_list_title\">EXISTENCIAS</td>\n";
      $this->salida .= "			<td width=\"5%\" class=\"modulo_table_list_title\">&nbsp;</td>\n";
      $this->salida .= "		 </tr>\n";
      $y=0;
      $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
      for($i=0;$i<sizeof($ExistenciasBodegas);$i++){
        if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "  <tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
        $this->salida .= "	 <tr class=\"$estilo\">\n";
        $this->salida .= "	 <td>".$ExistenciasBodegas[$i]['codigo_producto']."</td>\n";
        $this->salida .= "	 <td>".$ExistenciasBodegas[$i]['desprod']."</td>\n";
        $this->salida .= "	 <td>".$ExistenciasBodegas[$i]['existencia']."</td>\n";
        if($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['PRODUCTOS'][$ExistenciasBodegas[$i]['codigo_producto']]){
          $actionSel=ModuloGetURL('app','InvBodegas','user','BorrarProductoResponsable',array("producto"=>$ExistenciasBodegas[$i]['codigo_producto'],
          "codigoProd"=>$codigoProd,"descripcion"=>$descripcion,"grupo"=>$grupo,"NomGrupo"=>$NomGrupo,"clasePr"=>$clasePr,"NomClase"=>$NomClase,"subclase"=>$subclase,"NomSubClase"=>$NomSubClase));
          $this->salida .= "	 <td align=\"center\"><a href=\"$actionSel\"><img border=\"0\" src=\"".GetThemePath()."/images/checksi.png\"></a></td>";
        }else{
          $actionSel=ModuloGetURL('app','InvBodegas','user','InsertarProductoResponsable',array("producto"=>$ExistenciasBodegas[$i]['codigo_producto'],"descripcionProd"=>$ExistenciasBodegas[$i]['desprod'],"existencias"=>$ExistenciasBodegas[$i]['existencia'],
          "codigoProd"=>$codigoProd,"descripcion"=>$descripcion,"grupo"=>$grupo,"NomGrupo"=>$NomGrupo,"clasePr"=>$clasePr,"NomClase"=>$NomClase,"subclase"=>$subclase,"NomSubClase"=>$NomSubClase));
          $this->salida .= "	 <td align=\"center\"><a href=\"$actionSel\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
        }
        $this->salida .= "	 </tr>\n";
      }
      $this->salida .= "		     </table><BR>";
    }
    $Paginador = new ClaseHTML();
    $this->actionPaginador=ModuloGetURL('app','InvBodegas','user','LlamaFormaBuscadorProductosBodega',array("codigoProd"=>$codigoProd,
    "descripcion"=>$descripcion,"grupo"=>$grupo,"NomGrupo"=>$NomGrupo,"clasePr"=>$clasePr,"NomClase"=>$NomClase,
    "subclase"=>$subclase,"NomSubClase"=>$NomSubClase));
    $this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
    $this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
    return true;
  }

  function FormaConsultaSolicitudResponsable($NoSolicitud,$solicitudBus,$estacionBus,$usuarioResBus,$EstadoBus,$FechaBus){
    $this->salida .= themeAbrirTabla('SOLICITUD DE INSUMOS Y MEDICAMENTOS PARA RESPONSABLES');
    //$accion=ModuloGetURL('app','InvBodegas','user','LlamaCreacionSolicitudResponsable',array("solicitudBus"=>$solicitudBus,
    //"estacionBus"=>$estacionBus,"usuarioResBus"=>$usuarioResBus,"EstadoBus"=>$EstadoBus,"FechaBus"=>$FechaBus));
    $this->Encabezado();
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $vec=$this->DetalleSolicitudResponsable($NoSolicitud);
    $this->salida .= "  <table width=\"50%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS DE LA SOLICITUD</legend>";
    $this->salida .= "  <BR><table  class=\"normal_10\" border=\"0\" width=\"95%\" align=\"center\" >";
    $this->salida .= "  <tr>";
    $this->salida .= "    <td class=\"".$this->SetStyle("estacion")."\" width=\"25%\" nowrap>ESTACION</td>";
    $this->salida .= "    <td width=\"40%\">".$vec[0]['nom_estacion']."</td>";
    $this->salida .= "  </tr>";
    $this->salida .= "  <tr>";
    $this->salida .= "    <td class=\"".$this->SetStyle("fechaRes")."\" width=\"25%\" nowrap>FECHA</td>";
    $this->salida .= "    <td width=\"40%\">".$vec[0]['fecha']."</td>";
    $this->salida .= "  </tr>";
    $this->salida .= "  <tr>";
    $this->salida .= "    <td class=\"".$this->SetStyle("usuarioRes")."\" width=\"25%\" nowrap>USUARIO RESPONSABLE</td>";
    $this->salida .= "    <td width=\"40%\">".$vec[0]['nom_responsable']."</td>";
    $this->salida .= "  </tr>";
    $this->salida .= "  </table><BR>";
    $this->salida .= "	 </fieldset>";
    $this->salida .= "	 </td></tr>";
		$this->salida .= "	 </table><br>";
    if(sizeof($vec)>0){
      $this->salida .= "  <table width=\"90%\" border=\"0\" align=\"center\">";
      $this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\">PRODUCTOS DE LA SOLICITUD</td></tr>";
      $this->salida .= "  <tr class=\"modulo_table_title\">";
      $this->salida .= "  <td width=\"15%\" >CODIGO</td>";
      $this->salida .= "  <td>DESCRIPCION</td>";
      $this->salida .= "  <td width=\"15%\">EXISTENCIAS</td>";
      $this->salida .= "  <td width=\"15%\">CANTIDAD</td>";
      $this->salida .= "  </tr>";
      for($i=0;$i<sizeof($vec);$i++){
        $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">";
        $this->salida .= "  <td align=\"left\" width=\"15%\" >".$vec[$i]['codigo_producto']."</td>";
        $this->salida .= "  <td align=\"left\">".$vec[$i]['descripcion']."</td>";
        $this->salida .= "  <td align=\"center\" width=\"15%\">".$vec[$i]['existencia']."</td>";
        $this->salida .= "  <td align=\"center\" width=\"15%\">".$vec[$i]['cantidad']."</td>";
        $this->salida .= "  </tr>";
      }
    }else{
      $this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\" class=\"label_error\">NO SE HAN SELECCIONADO PRODUCTOS PARA LA SOLICITUD</td></tr>";
    }
    $this->salida .= "	</table>";
    $this->salida .= "  </form>";
    $accion=ModuloGetURL('app','InvBodegas','user','LlamaSolicitudesProductosResposables',array("solicitudBus"=>$solicitudBus,"estacionBus"=>$estacionBus,"usuarioResBus"=>$usuarioResBus,"EstadoBus"=>$EstadoBus,"FechaBus"=>$FechaBus));
    $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "  <tr><td class=\"label\" align=\"center\"><input type=\"submit\" align=\"center\" name=\"VolverLista\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
    $this->salida .= "  </table>";
    $this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
  }

  function DevolucionSuministrosEstacion(){
    $this->salida .= themeAbrirTabla('DEVOLUCIONES DE SUMINISTROS DE LA ESTACION');
    $this->Encabezado();
    $this->salida .= "  <table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "  <tr><td align=\"center\">";
    $this->salida .=    $this->SetStyle("MensajeError");
    $this->salida .= "  </td></tr>";
    $this->salida .= "  </table>";
    $datos=$this->ConsultaDevolucionesSuministrosEst();
    if($datos){
      $i=0;
      foreach($datos[0] as $bodega=>$vector){
        $accion=ModuloGetURL('app','InvBodegas','user','GuardarDevolucionSuministrosEstacion',array("BodegaDestino"=>$bodega));
		    $this->salida .= "  <form name=\"forma$i\" action=\"$accion\" method=\"post\">";
        $this->salida .= "  <table width=\"100%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr class=\"modulo_table_title\">";
        $this->salida .= "  <td width=\"20%\">BODEGA SOLICITA</td>";
        $this->salida .= "  <td width=\"10%\">No. SOLICITUD</td>";
        $this->salida .= "  <td>PRODUCTO</td>";
        $this->salida .= "  <td width=\"15%\">FECHAS VENCE</td>";
        $this->salida .= "  <td width=\"10%\">CANTIDAD DEVUELTA</td>";
        $this->salida .= "  <td width=\"5%\">&nbsp;</td>";
        $this->salida .= "  </tr>";
        if($y % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
        $this->salida .= "  <tr class=\"$estilo\">";
        $this->salida .= "  <td>".$datos[1][$bodega]."</td>";
        $this->salida .= "  <td colspan=\"5\">";
        $this->salida .= "    <table width=\"100%\" border=\"0\" align=\"center\">";
        foreach($vector as $NoConfirmacion=>$datos){
          $this->salida .= "  <tr class=\"$estilo1\">";
          $this->salida .= "  <td width=\"12%\">".$datos['solicitud_id']."</td>";
          $this->salida .= "  <td>".$datos['codigo_producto']." - ".$datos['descripcion']."</td>";
          if($datos['sw_control_fecha_vencimiento']==1){
            $accion=ModuloGetURL('app','InvBodegas','user','LlamaFechaVenceSuministroDevol',array("NoConfirmacion"=>$NoConfirmacion,"cantidad"=>$datos['cantidad'],"codigo_producto"=>$datos['codigo_producto'],"descripcion"=>$datos['descripcion']));
            $this->salida .= "  <td width=\"19%\"><a href=\"$accion\"><img title=\"Insertar Fechas Vencimiento\" border=\"0\" src=\"".GetThemePath()."/images/pparamed.png\"/></a></td>";
          }else{
            $this->salida .= "  <td width=\"19%\">&nbsp;</td>";
          }
          $this->salida .= "  <td width=\"13%\">".$datos['cantidad']."</td>";
          if($datos['sw_control_fecha_vencimiento']){$sw_control_fecha_vencimiento=1;}else{$sw_control_fecha_vencimiento=0;}
          $this->salida .= "  <td width=\"5%\"><input type=\"checkbox\" name=\"Seleccion[".$datos['confirmacion_id']."]\" value=\"".$datos['codigo_producto'].",".$datos['cantidad'].",".$sw_control_fecha_vencimiento."\"></td>";
          $this->salida .= "  </tr>";
        }
        $this->salida .= "  </table>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  <table width=\"100%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td align=\"right\"><input type=\"submit\" name=\"Devolver\" value=\"DEVOLVER\" class=\"input-submit\"></td></tr>";
        $this->salida .= "  </table></BR>";
        $this->salida .= "  </form>";
        $i++;
      }
    }else{
      $this->salida .= "  <table width=\"95%\" border=\"0\" align=\"center\">";
      $this->salida .= "  <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON SUMINISTROS DE LAS ESTACIONES PARA DEVOLUCION</td></tr>";
      $this->salida .= "  </table>";
    }
    $accion=ModuloGetURL('app','InvBodegas','user','RetornarFormaMenuDevoluciones');
    $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "  <tr><td align=\"center\"><input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
    $this->salida .= "  </table>";
    $this->salida .= "  </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
  }

  function FechaVenceSuministroDevol($NoConfirmacion,$cantidad,$codigo_producto,$descripcion){

	  $this->salida .= themeAbrirTabla('FECHAS DE VENCIMIENTO Y LOTES DE PRODUCTOS DEVUELTOS POR SUMINISTRO');
		$this->Encabezado();
		$this->salida .= "<table class=\"normal_10N\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
		$this->salida .= "</table>";
    $actionDos=ModuloGetURL('app','InvBodegas','user','InsertarFechaVencimientoLoteSuministros',array("NoConfirmacion"=>$NoConfirmacion,"cantidad"=>$cantidad,"codigo_producto"=>$codigo_producto,"descripcion"=>$descripcion));
    $this->salida .= "       <form name=\"formaUno\" action=\"$actionDos\" method=\"post\">";
    $cantidadSum=0;
    foreach($_SESSION['SUMINISTROS_ESTACION_FECHAS_VENCE'][$NoConfirmacion] as $lote => $vector){
      foreach($vector as $fechaVence => $cantidadSuma){
        $cantidadSumTot+=$cantidadSuma;
      }
    }
    $cantidadFalta=$cantidad-$cantidadSumTot;
    $this->salida .= "          <table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\">";
    $this->salida .= "          <tr><td width=\"100%\">";
    $this->salida .= "          <fieldset><legend class=\"field\">DATOS DEL PRODUCTO</legend>";
    $this->salida .= "          <table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "         <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">$codigoProducto&nbsp&nbsp&nbsp;$descripcion</td></tr>";
    $this->salida .= "         <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">CANTIDAD QUE FALTA PARA ALCANZAR EL TOTAL&nbsp&nbsp&nbsp;$cantidadFalta</td></tr>";
    $this->salida .= "	        <tr class=\"modulo_list_claro\">";
    $this->salida .= "	        <td class=\"label\">FECHA VENCIMIENTO</td>";
    $this->salida .= "	  	    <td align=\"center\"><input type=\"text\" name=\"fechaVencimiento\" value=\"".$_REQUEST['fechaVencimiento']."\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
    $this->salida .= "	  	    ".ReturnOpenCalendario('formaUno','fechaVencimiento','/')."</td>";
    $this->salida .= "	        <td class=\"label\">No. LOTE</td>";
    $this->salida .= "	        <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"lote\" value=\"".$_REQUEST['lote']."\"></td>";
    $this->salida .= "	        <td class=\"label\">CANTIDAD</td>";
    $this->salida .= "	        <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"cantidadLote\" value=\"".$_REQUEST['cantidadLote']."\"></td>";
    $this-> salida .= "	        <tr><td></td></tr>";
    $this->salida .= "	        <tr><td colspan=\"6\" align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"VOLVER\">";
    $this->salida .= "	        <input type=\"submit\" class=\"input-submit\" name=\"insertar\" value=\"INSERTAR\"></td></tr>";
    $this->salida .= "			     </table>";
    $this->salida .= "		      </fieldset></td>";
    $this->salida .= "         </table><BR>";
    if($_SESSION['SUMINISTROS_ESTACION_FECHAS_VENCE'][$NoConfirmacion]){
      $this->salida .= "			<table width=\"80%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
      $this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "			<td width=\"20%\">FECHA VENCIMIENTO</td>";
      $this->salida .= "			<td width=\"35%\">LOTE</td>";
      $this->salida .= "			<td width=\"20%\">CANTIDAD LOTE</td>";
      $this->salida .= "			<td width=\"5%\">&nbsp;</td>";
      $this->salida .= "			</tr>\n";
      $y=0;
      foreach($_SESSION['SUMINISTROS_ESTACION_FECHAS_VENCE'][$NoConfirmacion] as $lote => $vector){
        foreach($vector as $fechaVence => $cantidadSuma){
          if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
          $this->salida .= "	 <tr class=\"$estilo\">\n";
          $this->salida .= "	 <td>".$fechaVence."</td>";
          $this->salida .= "	 <td>".$lote."</td>";
          $this->salida .= "	 <td>".$cantidadSuma."</td>";
          $ElimFechaV=ModuloGetURL('app','InvBodegas','user','LlamaEliminarFechaVSuministros',array("lote"=>$lote,"NoConfirmacion"=>$NoConfirmacion,"cantidad"=>$cantidad,"codigo_producto"=>$codigo_producto,"descripcion"=>$descripcion));
          $this->salida .= "	 <td align=\"center\" width=\"5%\"><a href=\"$ElimFechaV\"><img title=\"Eliminar Lote\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
          $this->salida .= "	 </tr>";
          $y++;
        }
      }
      $this->salida .= "       </table><BR>";
    }
    $this->salida .="       </form>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}
  
  /**
* La funcion que visualiza el detalle de la solicitud de un paciente
* @return boolean
* @param array datos de al solicitud de despacho de medicamentos o insumos
* @param array datos de ubicacion de la bodega a la que se hizo la solicitud
* @param string nombre de la empresa en la que se esta trabajando
* @param string nombre del centro de utilidad en el que se esta trabajando
* @param string nombre de la bodega en la que se esta trabajando
* @param string nombre de la estacion que hace la solicitud
* @param adte fecha de realizacion de la solicitud
*/

  function FrmAtenderSolicitudPacienteSinConfirmar($SolicitudId,$Ingreso,$EstacionId,$NombreEstacion,$Fecha,$usuarioestacion,$nombrepac,
    $tipo_id_paciente,$paciente_id,$cama,$departamento,$descripcionDpto,$estado){
     
    $tipoSolicitud=$this->GetTipoSolicitudBodega($SolicitudId);
    if($tipoSolicitud['tipo_solicitud']=='M'){
      $Vector = $this->GetMedicamentosSolicitud($SolicitudId);
      $palabra='MEDICAMENTOS';
    }elseif($tipoSolicitud['tipo_solicitud']=='Z'){
      $Vector = $this->GetMezclasSolicitud($SolicitudId);
      $palabra='MEZCLAS';
    }elseif($tipoSolicitud['tipo_solicitud']=='I'){
      $Vector = $this->GetInsumosSolicitud($SolicitudId);
      $palabra='INSUMOS';
    }
    //pRINT_R($Vector);
    if(!$Vector){
      $mensaje = "NO SE ENCONTRARON MEDICAMENTOS EN LA SOLICITUD SELECCIONADA";
      $titulo = "DETALLE DOCUMENTO BODEGA";
      $accion = ModuloGetURL('app','InvBodegas','user','ConsultarSolicitudesSinConfimar',array("departamento"=>$departamento,"descripcionDpto"=>$descripcionDpto));
      $boton = "REGRESAR";
      $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
      return true;
    }else{
      
      //ordenar por solicitud
      foreach($Vector as $key=>$value){
      $datosOrdenados[$value[solicitud_id]][$key] = $value; //echo "<br>--><br>"; print_r($value);
      }
      $this->salida .= themeAbrirTabla('SOLICITUDES DE '.' '.$palabra);      
      $accion = ModuloGetURL('app','InvBodegas','user','ConsultarSolicitudesSinConfimar',array("departamento"=>$departamento,"descripcionDpto"=>$descripcionDpto));
      $this->salida .= "<form name='Solicitud' action='$accion' method='POST' onsubmit=\"return ValidaSolicitud(this);\">\n";
      $this->Encabezado();
      $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"80%\" align=\"center\">";
      $this->salida .= "        <tr><td width=\"100%\">";
      $this->salida .= "        <fieldset><legend class=\"field\">DOCUMENTO SOLICITUD MEDICAMENTO</legend>";
      $this->salida .= "          <table class=\"normal_10\"cellspacing=\"2\" cellpadding=\"3\"border=\"0\"  width=\"95%\" align=\"center\">";
      $this->salida .= "            <tr><td></td></tr>";
      $this->salida .= "            <tr class=\"modulo_list_claro\">";
      $this->salida .= "            <td width=\"20%\"><label class=\"label\">SOLICITANTE</td>";
      $this->salida .= "            <td colspan=\"3\">$NombreEstacion</td>";
      $this->salida .= "            </tr>";
      $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "            <td width=\"20%\"><label class=\"label\">FECHA SOLICITUD</td>";
      (list($fecha,$HoraTot)=explode(' ',$Fecha));
      (list($ano,$mes,$dia)=explode('-',$fecha));
      (list($hora,$min)=explode(':',$HoraTot));
      $this->salida .= "            <td width=\"45%\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";                 
      $this->salida .= "            <td width=\"20%\"><label class=\"label\">CODIGO SOLICITUD</td>";
      $this->salida .= "            <td width=\"15%\">$SolicitudId</td>";
      $this->salida .= "            </tr>";
      $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "            <td width=\"20%\"><label class=\"label\">PACIENTE</td>";
      $this->salida .= "            <td width=\"45%\">$tipo_id_paciente - $paciente_id  $nombrepac</td>";
      $this->salida .= "            <td width=\"20%\"><label class=\"label\">CAMA</td>";
      $this->salida .= "            <td width=\"15%\">$cama</td>";
      $this->salida .= "            </tr>";
      $this->salida .= "            <tr><td></td></tr>";
      $this->salida .= "          <input type=\"hidden\" name=\"EstacionId\" value=\"$EstacionId\" >";
      $this->salida .= "          <input type=\"hidden\" name=\"NombreEstacion\" value=\"$NombreEstacion\" >";
      $this->salida .= "          <input type=\"hidden\" name=\"Fecha\" value=\"$Fecha\" >";
      $this->salida .= "          </table>";
      $this->salida .= "         </fieldset></td><BR>";
      $this->salida .= "       </table><BR><BR>";
      $this->salida .= "      <table width=\"85%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\"> \n";
      $this->salida .= "        <tr class=\"modulo_table_list_title\">\n";
      //$this->salida .= "          <td>OBSERVACIONES</td>\n";
      if($tipoSolicitud['tipo_solicitud']=='Z'){
      if($ValueMed[mezcla_recetada_id]){
        $this->salida .= "          <td>MEZCLA</td>\n";
      }
      }
      $this->salida .= "          <td>MEDICAMENTO</td>\n";
      $this->salida .= "          <td>CANT <BR> EXISTENTE</td>\n";
      $this->salida .= "          <td>CANT <BR> SOLICITADA</td>\n";            
      $this->salida .= "        </tr>\n";
      $l = $i = 0;
      foreach($datosOrdenados as $key=>$value){//print_r($value);
        $contadorRowSpan = sizeof($value);
        foreach($value as $keyMed => $ValueMed){
          if(($l++) % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
          $CodigoProd=$ValueMed[medicamento_id];
          $this->salida .= "        <tr align=\"center\" class=\"$estilo\">\n";
          $Existencia=$this->GetCantidadExistenteBodega($ValueMed[medicamento_id]);
          $cantidadExistente=$Existencia['existencia'];            
          if($cantidadExistente){  
          $this->salida .= "          <td>".$ValueMed[medicamento_id]." => ".$ValueMed[nommedicamento]." ".$ValueMed[ff]."</td>\n";          
          $this->salida .= "          <td><input type=\"text\" name=\"CantExistente$i\" class=\"input-text\" value=\"".$cantidadExistente."\" size=\"10\" READONLY></td>\n";
          $this->salida .= "          <td><input type=\"hidden\" name=\"CantSolicitada[]\" value=\"".$ValueMed[cant_solicitada]."\">".$ValueMed[cant_solicitada]."</td>\n";
          if($ValueMed[cant_solicitada] <= $cantidadExistente){
            $cantidad=$ValueMed[cant_solicitada];
          }else{
            if($cantidadExistente<0){
              $cantidad=0;
            }else{
              $cantidad=$cantidadExistente;
            }
          }          
          $contadorRowSpan--;
          $i++;
          }else{
          $this->salida .= "          <td>".$ValueMed[medicamento_id]." => ".$ValueMed[nommedicamento]." ".$ValueMed[ff]."</td>";
          $this->salida .= "          <td colspan=\"4\" class=\"label_error\">PRODUCTO NO EXISTE EN LA BODEGA</td>";
          $contadorRowSpan--;
          }
          $this->salida .= "        </tr>\n";
        }
      }
      if($estado=='6'){      
        $this->salida .= "            <tr class=\"modulo_table_list_title\">";
        $this->salida .= "            <td align=\"center\">JUSTIFICACION CANCELACION</td>";
        $this->salida .= "            <td align=\"center\">USUARIO</td>";
        $this->salida .= "            <td align=\"center\">FECHA REGISTRO</td>";
        $this->salida .= "            </tr>\n";
        $this->salida .= "            <tr class=\"hc_table_submodulo_list_title\">";
        $this->salida .= "            <td align=\"center\">".$tipoSolicitud['observacion']."</td>";
        $this->salida .= "            <td align=\"center\">".$tipoSolicitud['nombre']."</td>";
        (list($fecha,$HoraTot)=explode(' ',$tipoSolicitud['fecha_registro']));
        (list($ano,$mes,$dia)=explode('-',$fecha));
        (list($hora,$min)=explode(':',$HoraTot));                             
        $this->salida .= "            <td align=\"center\">".ucfirst(strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano)))."</td>";
        $this->salida .= "            </tr>\n";        
      }     
      $this->salida .= "      </table><BR>\n";
      $this->salida .= "       <table class=\"normal_10N\" border=\"0\" width=\"100%\" align=\"center\">";
      $this->salida .= "         <tr><td align=\"center\">";
      $this->salida .= "         <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\">";      
      $this->salida .= "         </td></tr>";
      $this->salida .= "       </table><BR>";
      $this->salida .= "  </form>\n";
      $this->salida .= themeCerrarTabla();
      return true;
    }
  }



}//fin clase user
?>
