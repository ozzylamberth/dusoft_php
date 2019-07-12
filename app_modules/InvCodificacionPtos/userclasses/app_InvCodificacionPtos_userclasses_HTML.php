<?php

/**
 * $Id: app_InvCodificacionPtos_userclasses_HTML.php,v 1.8 2008/06/26 19:21:03 cahenao Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Inventario del Sistema
 */

/**
*Contiene los metodos visuales para realizar la administracion de los Inventario de la clinica
*/


class app_InvCodificacionPtos_userclasses_HTML extends app_InvCodificacionPtos_user
{
	/**
	*Constructor de la clase app_Inventarios_user_HTML
	*El constructor de la clase app_Inventarios_user_HTML se encarga de llamar
	*a la clase app_Inventarios_user que se encarga del tratamiento
	* de la base de datos.
	*/

  function app_InvCodificacionPtos_user_HTML()
	{
		$this->salida='';
		$this->app_InvCodificacionPtos_user();
		return true;
	}

	/**
	* Function que muestra las diferentes opciones del menu
	* @return boolean
  * @param string codigo de la empresa en la que esta trabajando el usuario
	* @param string nombre de la empresa en la que esta trabajando el usuario
	*/
	function MenuInventariosPrincipal(){

		$this->salida .= ThemeAbrirTabla('MENU CODIFICACION PRODUCTOS DEL INVENTARIO');
		$this->salida .= "			<br>";
		$actionMenu=ModuloGetURL('system','Menu');
		$this->salida .= "    <form name=\"forma\" action=\"$actionMenu\" method=\"post\">";
		$this->salida .= "			      <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
    $action1=ModuloGetURL('app','InvCodificacionPtos','user','main1');
		$action2=ModuloGetURL('app','InvCodificacionPtos','user','main2');
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action2\" class=\"link\"><b>CLASIFICACION GENERAL GRUPOS INVENTARIOS</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action1\" class=\"link\"><b>CODIFICACION DE LOS PRODUCTOS</b></a></td></tr>";
		$this->salida .= "			     </table><BR>";
		$this->salida .= "    <table border=\"0\" width=\"40%\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"MENU\"></td></tr>";
    $this->salida .= "    </table>";
    $this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }

	function FormaMostrarPrInv($grupo,$clasePr,$subclase,$NomGrupo,$NomClase,$NomSubClase,$origenFun,$codigoPro,$descripcionPro,$codigoProAlterno){

    //$descripcionPro=strtoupper($descripcionPro);
    $this->salida .= ThemeAbrirTabla('LISTADO PRODUCTOS INVENTARIO GENERAL');
    $this->salida .="<SCRIPT>";
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
		$this->salida .="</SCRIPT>";
		$action=ModuloGetURL('app','InvCodificacionPtos','user','LlamaAccionInventariosGeneral',array("conteo"=>$this->conteo,"paso"=>$_REQUEST['paso']));
		$this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "         <input type=\"hidden\" name=\"origenFun\" value=\"$origenFun\">";
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
		$ruta='app_modules/InvCodificacionPtos/ventanaClasificacion.php';
		$this->salida .= "         <input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCIONAR\" onclick=\"abrirVentanaClass('PARAMETROS','$ruta',450,200,0,this.form)\"></td>";
		$this->salida .= "		     </tr>";
		$this->salida .= "		     </table><BR><BR>";
    $this->salida .= "		     </td>";
    $this->salida .= "		     <td valign=\"top\" class=\"modulo_list_claro\" width=\"40%\"> ";
		$this->salida .= "         <BR><table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\" >";
    $this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">CODIGO</td><td><input type=\"text\" class=\"input-text\" name=\"codigoPro\" value=\"$codigoPro\"></td></tr>";
    $this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">CODIGO ALTERNO</td><td><input type=\"text\" class=\"input-text\" name=\"codigoProAlterno\" value=\"$codigoProAlterno\"></td></tr>";
		$this->salida .= "		     <tr class=\"modulo_list_oscuro\"><td class=\"label\">DESCRIPCION</td><td><input type=\"text\" class=\"input-text\" name=\"descripcionPro\" value=\"$descripcionPro\"></td></tr>";
		$this->salida .= "		     </table><BR>";
		$this->salida .= "         </td></tr>";
		$this->salida .= "         <tr><td colspan=\"2\" align=\"center\" class=\"modulo_list_claro\"><input type=\"submit\" class=\"input-submit\" value=\"FILTRAR\" name=\"buscar\"></td></tr>";
    $this->salida .= "		     </table><BR>";
		$this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "       <tr><td>&nbsp&nbsp;</td></tr>";
		$this->salida .= "       </td></tr>";
		$this->salida .= "			 </table><BR>";
		if(!$TotalInventario){
		  $this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"85%\" align=\"center\">";
			$this->salida .= "         <tr><td  align=\"left\"><input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"CREA PRODUCTO INVENTARIO\">";
      $this->salida .= "			   </table>";
		}
		$TotalInventario=$this->TotalInventarioProductosInv($grupo,$clasePr,$subclase,$codigoPro,$descripcionPro,$codigoProAlterno);
		if($TotalInventario){
		$this->salida .= "			 <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">";
    $this->salida .= "      <tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "			  <td>CODIGO</td>";
    $this->salida .= "        <td>DESCRIPCION</td>";
    $this->salida .= "        <td>UNIDAD</td>";
		$this->salida .= "        <td>FABRICANTE</td>";
		$this->salida .= "        <td>MTO</td>";
		$this->salida .= "        <td>% IVA</td>";
		$this->salida .= "        <td>COSULTA</td>";
		$this->salida .= "        <td>EDITAR</td>";
		$this->salida .= "        <td>ESTADO</td>";
		$this->salida .= "       </tr>";
		$y=0;
		for($i=0;$i<sizeof($TotalInventario);$i++){
		  if($y % 2){
			  $estilo='modulo_list_claro';
			}else{
			  $estilo='modulo_list_oscuro';
			}
			
			$Concentracion = "";
			$FormaFarmacologica = "";
			if($TotalInventario[$i]['concentracion_forma_farmacologica'])
			{
				$Concentracion = " ".$TotalInventario[$i]['concentracion_forma_farmacologica'];
			}
			if($TotalInventario[$i]['forma_farmacologica'])
			{
				$FormaFarmacologica = " ".$TotalInventario[$i]['forma_farmacologica'];
			}
			
			$this->salida .= "			<tr class=\"$estilo\">";
			$this->salida .= "       <td>".$TotalInventario[$i]['codigo_producto']."</td>";
			$this->salida .= "				<td width=\"80%\">".$TotalInventario[$i]['descripcion']."".$Concentracion."".$FormaFarmacologica."</td>";
      $this->salida .= "				<td>".$TotalInventario[$i]['unidad']."</td>";
			$this->salida .= "				<td>".$TotalInventario[$i]['fabricante']."</td>";
			if(!$TotalInventario[$i]['sw_medicamento']){
			  $this->salida .= "				<td>&nbsp;</td>";
			}else{
        $this->salida .= "				<td align=\"center\" width=\"5%\"><img border=\"0\" src=\"".GetThemePath()."/images/endturn.png\"></td>";
			}
			$this->salida .= "				<td>".$TotalInventario[$i]['porc_iva']."</td>";
			$actionConsulta=ModuloGetURL('app','InvCodificacionPtos','user','EditarProductoInventarioCodifi',array("codigoProducto"=>$TotalInventario[$i]['codigo_producto'],"codigoBusqueda"=>$codigoPro,"descripcionBusqueda"=>$descripcionPro,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"consultaForma"=>1,"origenFun"=>$origenFun));
			$this->salida .= "				<td align=\"center\"><a href=\"$actionConsulta\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"></a></td>";
			if($TotalInventario[$i]['estado']==1){
			  $actionEditar=ModuloGetURL('app','InvCodificacionPtos','user','EditarProductoInventarioCodifi',array("codigoProducto"=>$TotalInventario[$i]['codigo_producto'],"codigoBusqueda"=>$codigoPro,"descripcionBusqueda"=>$descripcionPro,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"origenFun"=>$origenFun));
			  $this->salida .= "				<td align=\"center\"><a href=\"$actionEditar\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/editar.png\"></a></td>";
			  $actionEliminar=ModuloGetURL('app','InvCodificacionPtos','user','EliminarProductoInventarioCodifi',array("codigoProducto"=>$TotalInventario[$i]['codigo_producto'],"bandera"=>1,"grupo"=>$grupo,"clasePr"=>$clasePr,"subclase"=>$subclase,"NomGrupo"=>$NomGrupo,"NomClase"=>$NomClase,"NomSubClase"=>$NomSubClase,"origenFun"=>$origenFun,"codigoPro"=>$codigoPro,"descripcionPro"=>$descripcionPro,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"origenFun"=>$origenFun));
			  $this->salida .= "				<td align=\"center\"><a href=\"$actionEliminar\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/checksi.png\"></a></td>";
			}else{
			  $this->salida .= "				<td>&nbsp;</td>";
			  $actionEliminar=ModuloGetURL('app','InvCodificacionPtos','user','EliminarProductoInventarioCodifi',array("codigoProducto"=>$TotalInventario[$i]['codigo_producto'],"bandera"=>0,"grupo"=>$grupo,"clasePr"=>$clasePr,"subclase"=>$subclase,"NomGrupo"=>$NomGrupo,"NomClase"=>$NomClase,"NomSubClase"=>$NomSubClase,"origenFun"=>$origenFun,"codigoPro"=>$codigoPro,"descripcionPro"=>$descripcionPro,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"origenFun"=>$origenFun));
        $this->salida .= "				<td align=\"center\"><a href=\"$actionEliminar\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
			}
			$this->salida .= "      </tr>";
			$y++;
		}
		$this->salida .="          </table>";
		if($origenFun!=1){
			$this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"85%\" align=\"center\">";
			$this->salida .= "         <tr><td  align=\"left\"><input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"CREA PRODUCTO INVENTARIO\">";
			//$this->salida .= "		     <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACTUALIZACION PRECIOS\"></td>";
			$this->salida .= "         </td></tr>";
			$this->salida .= "			   </table>";
		}
		$this->salida .=$this->RetornarBarra();
		$this->salida .= "			   <BR>";
		}else{
      $this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"85%\" align=\"center\">";
		  $this->salida .= "         <tr><td  align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS DE BUSQUEDA</td></tr>";
			$this->salida .= "         <tr><td  align=\"left\"><input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"CREA PRODUCTO INVENTARIO\">";
      $this->salida .= "			   </table><BR>";
		}
		$this->salida .= "       </form>";
    if($origenFun){
      $action1=ModuloGetURL('app','InvCodificacionPtos','user','ClasificacionProductoGrupo');
		}else{
		  $action1=ModuloGetURL('app','InvCodificacionPtos','user','MenuInventariosPrincipal');
		}
		$this->salida .= "       <form name=\"formauno\" action=\"$action1\" method=\"post\">";
		$this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"85%\" align=\"center\">";
		$this->salida .= "         <tr><td  align=\"center\" width=\"5%\"><BR><input class=\"input-submit\" type=\"submit\" name=\"Regresar\" value=\"VOLVER\">";
    $this->salida .= "			   </table>";
		$this->salida .="          </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Function que muestra la forma en donde se piden los datos requeridos para insertar un producto al inventario
* @return boolean
* @param string codigo de la empresa en la que esta trabajando el usuario
* @param string nombre de la empresa en la que esta trabajando el usuario
*/
	function FormaAdicionarInventario($codProducto,$DescripcionCompleta,$DescripcionAbreviada,
	$unidad,$cantidadUnidadMedida,$fabricante,$valorFab,$PorcentajeIva,$codigoInvima,$fechaVencimiento,$grupo,$NomGrupo,$clasePr,$NomClase,$subclase,$NomSubClase,$OrigenFuct){

    $this->salida .="<SCRIPT>";
	  $this->salida .="function abrirVentanaClas(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .=" f=frm;\n";
    $this->salida .=" var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
    $this->salida .=" var gr=frm.grupo.value;";
		$this->salida .=" var clas=frm.clasePr.value;";
		$this->salida .=" var sbclas=frm.subclase.value;";
		$this->salida .=" var url2 = url+'?grupo='+gr+'&clasePr='+clas+'&subclase='+sbclas;";
    $this->salida .=" var rems = window.open(url2, nombre, str);\n";
		$this->salida .=" if (rems != null) {\n";
		$this->salida .="   if (rems.opener == null) {\n";
		$this->salida .="	    rems.opener = self;\n";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .="}\n";
		$this->salida .="function abrirVentanaFab(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .=" f=frm;";
    $this->salida .=" var str = 'width=550,height=570,resizable=no,status=no,scrollbars=yes,top=200,left=200';\n";
    $this->salida .=" var remd = window.open(url, nombre, str);\n";
		$this->salida .=" if (remd != null) {\n";
		$this->salida .="   if (remd.opener == null) {\n";
		$this->salida .="	    remd.opener = self;\n";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .="}\n";
		$this->salida .="</SCRIPT>";
		$this->salida .= ThemeAbrirTabla('INSERTAR PRODUCTO AL INVENTARIO');
		$action=ModuloGetURL('app','InvCodificacionPtos','user','InsertarProductoInventarios');
		$this->salida .= "       <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "    <table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "		  <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "		  </td></tr>";
    $this->salida .= "    <tr class=\"modulo_table_title\"><td width=\"100%\" align=\"center\">DATOS DEL PRODUCTO</td></tr>";
    $this->salida .= "    <tr><td width=\"100%\" class=\"modulo_list_claro\">";
		$this->salida .= "      <br><table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\">";
    $this->salida .= "		  <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	    <td class=\"".$this->SetStyle("grupo")."\">GRUPO:</td>";
		$this->salida .= "	    <td><input type=\"text\" name=\"NomGrupo\" value=\"$NomGrupo\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "     <input type=\"hidden\" name=\"grupo\" value=\"$grupo\" class=\"input-text\">";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("clasePr")."\">CLASE: </td>";
		$this->salida .= "	  	<td><input type=\"text\" name=\"NomClase\"  value=\"$NomClase\" size=\"30\" class=\"input-text\" readonly>";
		$this->salida .= "     <input type=\"hidden\" name=\"clasePr\" value=\"$clasePr\" class=\"input-text\" ></td>";
    $this->salida .= "		  <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("subclase")."\">SUBCLASE: </td>";
		$this->salida .= "	  	<td><input type=\"text\" name=\"NomSubClase\"  value=\"$NomSubClase\" size=\"30\" class=\"input-text\" readonly>";
		$ruta='app_modules/InvCodificacionPtos/ventanaClasificacion.php';
		$this->salida .= "	 		&nbsp&nbsp&nbsp;<input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCION\" onclick=\"abrirVentanaClas('CLASIFICACION','$ruta',450,200,0,this.form)\" $deshabilitado></td></tr>";
		$this->salida .= "      <input type=\"hidden\" name=\"subclase\" value=\"$subclase\" class=\"input-text\" >";
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("codProducto")."\">CODIGO PRODUCTO</td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"18\" size=\"18\" name=\"codProducto\" value=\"$codProducto\" class=\"input-text\" readonly></td></tr>";
		$ruta1='app_modules/InvCodificacionPtos/AdicionarFabricante.php?Empresa='.$Empresa;
		$this->salida .= "	  	<tr class=\"modulo_list_oscuro\" height=\"20\"><td class=\"".$this->SetStyle("fabricante")."\">FABRICANTE:</td>";
		$this->salida .= "	  	<td><input type=\"text\" name=\"fabricante\" value=\"$fabricante\" size=\"40\" class=\"input-text\" readonly>";
		$this->salida .= "		  <input type=\"hidden\" name=\"valorFab\" value=\"$valorFab\">";
    $this->salida .= "	 		&nbsp&nbsp&nbsp;<input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCION\" onclick=\"abrirVentanaFab('FABRICANTE','$ruta1',700,50,0,this.form)\"></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "	  	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("DescripcionCompleta")."\">DESCRIPCION COMPLETA</td>";
		$this->salida .= "	  	    <td><input width=\"100%\" type=\"text\" size=\"50\" maxlength=\"60\" name=\"DescripcionCompleta\" value=\"$DescripcionCompleta\" class=\"input-text\"></td></tr>";
    $this->salida .= "	  	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("DescripcionAbreviada")."\">DESCRIPCION ABREVIADA</td>";
		$this->salida .= "	  	    <td><input type=\"text\" maxlength=\"30\" size=\"30\" name=\"DescripcionAbreviada\" value=\"$DescripcionAbreviada\" class=\"input-text\">&nbsp;(Para Impresion en POS)</td></tr>";
		$this->salida .= "		      <tr class=\"modulo_list_oscuro\" height=\"20\"><td class=\"".$this->SetStyle("unidad")."\">UNIDAD DE MEDIDA:</td><td><select name=\"unidad\"  class=\"select\">";
		$Unidades=$this->UnidadesMedida();
		$this->Mostrar($Unidades,'False',$unidad);
		$this->salida .= "          </select>&nbsp&nbsp&nbsp;<b>POR</b>&nbsp&nbsp&nbsp;<input size=\"30\" type=\"text\" name=\"cantidadUnidadMedida\" value=\"$cantidadUnidadMedida\">&nbsp&nbsp&nbsp;</td></tr>";
    $this->salida .= "	  	    <tr class=\"modulo_list_oscuro\" ><td class=\"".$this->SetStyle("PorcentajeIva")."\">PORCENTAJE IVA</td>";
		$this->salida .= "	  	    <td><input type=\"text\" maxlength=\"3\" size=\"8\" name=\"PorcentajeIva\" value=\"$PorcentajeIva\" class=\"input-text\">&nbsp;<b>%</b></td></tr>";
		$this->salida .= "	  	    <tr class=\"modulo_list_oscuro\" ><td class=\"".$this->SetStyle("codigoInvima")."\">CODIGO INVIMA</td>";
		$this->salida .= "	  	    <td><input type=\"text\" maxlength=\"30\" size=\"30\" name=\"codigoInvima\" value=\"$codigoInvima\" class=\"input-text\"></td></tr>";
		if($fechaVencimiento){$var6='checked';}
		$this->salida .= "	        <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("fechaVencimiento")."\">PROPONER CONTROL<br>FECHA VENCIMIENTO</td>";
		$this->salida .= "         <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"fechaVencimiento\" $var6></td></tr>";
		$this->salida .= "			    </table><BR>";
		$this->salida .= "         </td></tr>";
    $this->salida .= "			    </table><BR>";
    $this->salida .= "          <table class=\"normal_10\"  border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "          <tr><td  align=\"center\" colspan=\"4\"><BR><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\">&nbsp&nbsp&nbsp;";
		$this->salida .= "          <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td></tr>";
    $this->salida .= "          </table>";
		$this->salida .="          </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ClasificacionProductoGrupo(){

    $this->salida  = ThemeAbrirTabla('CLASIFICACION GENERAL DE LOS ITEMS DEL INVENTARIO');
		$actionTotal=ModuloGetURL('app','InvCodificacionPtos','user','LLamaAdicionCancelacionClas');
		$this->salida .= "            <form name=\"forma\" action=\"$actionTotal\" method=\"post\">";
		$this->salida .= "    <table class=\"normal_10\"  cellspacing=\"3\"  cellpadding=\"3\"border=\"1\" width=\"90%\" align=\"center\">";
    $this->salida .= "      <tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "			 <td>GRUPOS</td>";
		$this->salida .= "       <td>CLASES</td>";
		$this->salida .= "       <td width=\"35%\">SUBCLASES</td>";
		$this->salida .= "       </tr>";
		$y=0;
		$m=1;
		$totalGrupos=$this->GruposClasificacionInv();
		for($i=0;$i<sizeof($totalGrupos);$i++){
		  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
      $GrupoId=$totalGrupos[$i]['grupo_id'];
      $NombreGrupo=$totalGrupos[$i]['descripcion'];
			$this->salida .= "      <tr>";
      $this->salida .= "      <td class=\"$estilo\" width=\"30%\">$GrupoId  $NombreGrupo<BR>";
			$accionEdit=ModuloGetURL('app','InvCodificacionPtos','user','EditarClasificacion',array("grupo"=>$GrupoId,"NombreGrupo"=>$NombreGrupo,"claseIn"=>$ClaseId,"NombreClase"=>$NombreClase,"subclase"=>$SubClaseId,"NombreSubClase"=>$NombreSubClase,'bandera'=>1,'Empresa'=>$Empresa,'NombreEmp'=>$NombreEmp));
			$this->salida .= "			 <a href=\"$accionEdit\"><img border=\"0\" src=\"".GetThemePath()."/images/modificar.png\"></a>";
			$accionAdicClass=ModuloGetURL('app','InvCodificacionPtos','user','AdicionarClasify',array("grupo"=>$GrupoId,"NombreGrupo"=>$NombreGrupo,"claseIn"=>$ClaseId,"NombreClase"=>$NombreClase,"subclase"=>$SubClaseId,"NombreSubClase"=>$NombreSubClase,'bandera'=>1,'Empresa'=>$Empresa,'NombreEmp'=>$NombreEmp));
			$this->salida .= "				      <a href=\"$accionAdicClass\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/planblanco.png\"></a>";
      $indicaElimGrup=$this->PosibleEliminacionGrupo($GrupoId);
			if($indicaElimGrup<1){
				$accionElim=ModuloGetURL('app','InvCodificacionPtos','user','EliminarClasificacionSubclass',array("grupo"=>$GrupoId,"NombreGrupo"=>$NombreGrupo,"claseIn"=>$ClaseId,"NombreClase"=>$NombreClase,"subclase"=>$SubClaseId,"NombreSubClase"=>$NombreSubClase,'bandera'=>1,'Empresa'=>$Empresa,'NombreEmp'=>$NombreEmp));
				$this->salida .= "				      <a href=\"$accionElim\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a>";
			}
      $this->salida .= "      </td>";
      $this->salida .= "      <td colspan=\"2\" valign=\"top\">";
      $totalClases=$this->ClasesClasificacionInv($GrupoId);
      for($x=0;$x<sizeof($totalClases);$x++){
			  if($m % 2){$estilo1='modulo_list_claro';}else{$estilo1='modulo_list_oscuro';}
        $ClaseId=$totalClases[$x]['clase_id'];
				$NombreClase=$totalClases[$x]['descripcion'];
				$this->salida .= "			     <table class=\"normal_10\"  cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\" class=\"$estilo1\">";
				$this->salida .= "              <tr>";
				$this->salida .= "              <td width=\"50%\" class=\"$estilo1\">$ClaseId  $NombreClase<BR>";
				if($totalClases){
				$accionEdit=ModuloGetURL('app','InvCodificacionPtos','user','EditarClasificacion',array("grupo"=>$GrupoId,"NombreGrupo"=>$NombreGrupo,"claseIn"=>$ClaseId,"NombreClase"=>$NombreClase,"subclase"=>$SubClaseId,"NombreSubClase"=>$NombreSubClase,'bandera'=>2,'Empresa'=>$Empresa,'NombreEmp'=>$NombreEmp));
			  $this->salida .= "				      <a href=\"$accionEdit\"><img border=\"0\" src=\"".GetThemePath()."/images/modificar.png\"></a>";
				$accionAdicSbClass=ModuloGetURL('app','InvCodificacionPtos','user','AdicionarClasify',array("grupo"=>$GrupoId,"NombreGrupo"=>$NombreGrupo,"claseIn"=>$ClaseId,"NombreClase"=>$NombreClase,"subclase"=>$SubClaseId,"NombreSubClase"=>$NombreSubClase,'bandera'=>2,'Empresa'=>$Empresa,'NombreEmp'=>$NombreEmp));
			  $this->salida .= "				      <a href=\"$accionAdicSbClass\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/planblanco.png\" alt=\"Nuevo\"></a>";
				$indicaElimClass=$this->PosibleEliminacionClase($GrupoId,$ClaseId);
				if($indicaElimClass<1){
					$accionElim=ModuloGetURL('app','InvCodificacionPtos','user','EliminarClasificacionSubclass',array("grupo"=>$GrupoId,"NombreGrupo"=>$NombreGrupo,"claseIn"=>$ClaseId,"NombreClase"=>$NombreClase,"subclase"=>$SubClaseId,"NombreSubClase"=>$NombreSubClase,'bandera'=>2,'Empresa'=>$Empresa,'NombreEmp'=>$NombreEmp));
					$this->salida .= "				      <a href=\"$accionElim\" class=\"link\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a>";
			  }
				}
        $this->salida .= "              </td>";
				$this->salida .= "              <td valign=\"top\" width=\"50%\">";
				$totalSubClases=$this->SubClasesClasificacionInv($GrupoId,$ClaseId);
        for($z=0;$z<sizeof($totalSubClases);$z++){
          $SubClaseId=$totalSubClases[$z]['subclase_id'];
					$NombreSubClase=$totalSubClases[$z]['descripcion'];
          $this->salida .= "			     <table class=\"normal_10\"  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\" class=\"$estilo1\">";
					if($totalSubClases){
          $this->salida .= "              <tr><td class=\"$estilo1\">$SubClaseId  $NombreSubClase</td>";
					$accionEdit=ModuloGetURL('app','InvCodificacionPtos','user','EditarClasificacion',array("grupo"=>$GrupoId,"NombreGrupo"=>$NombreGrupo,"claseIn"=>$ClaseId,"NombreClase"=>$NombreClase,"subclase"=>$SubClaseId,"NombreSubClase"=>$NombreSubClase,'bandera'=>3,'Empresa'=>$Empresa,'NombreEmp'=>$NombreEmp));
					$this->salida .= "				      <td class=\"$estilo1\" width=\"5%\"><a href=\"$accionEdit\"><img border=\"0\" src=\"".GetThemePath()."/images/modificar.png\"></a></td>";
					$PermisoElim=$this->VerificarEliminacionClasificacion($GrupoId,$ClaseId,$SubClaseId);
					if($PermisoElim >0){
            $accionVerItems=ModuloGetURL('app','InvCodificacionPtos','user','BusquedaBDProductosInventarios',array("grupo"=>$GrupoId,"NomGrupo"=>$NombreGrupo,"clasePr"=>$ClaseId,"NomClase"=>$NombreClase,"subclase"=>$SubClaseId,"NomSubClase"=>$NombreSubClase,"origenFun"=>'1'));
						$this->salida .= "				      <td class=\"$estilo1\" width=\"5%\"><a href=\"$accionVerItems\"><img border=\"0\" src=\"".GetThemePath()."/images/informacion.png\"></a></td>";
					}else{
					  $accionElim=ModuloGetURL('app','InvCodificacionPtos','user','EliminarClasificacionSubclass',array("grupo"=>$GrupoId,"NombreGrupo"=>$NombreGrupo,"claseIn"=>$ClaseId,"NombreClase"=>$NombreClase,"subclase"=>$SubClaseId,"NombreSubClase"=>$NombreSubClase,'bandera'=>3,'Empresa'=>$Empresa,'NombreEmp'=>$NombreEmp));
						$this->salida .= "				      <td class=\"$estilo1\" width=\"5%\"><a href=\"$accionElim\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
					}
          $this->salida .= "             </tr>";
					}else{
            $this->salida .= "              <tr><td class=\"$estilo1\">&nbsp;</td></tr>";
					}
          $this->salida .= "			     </table>";
				}
				$this->salida .= "              </td>";
				$this->salida .= "              </tr>";
				$this->salida .= "			     </table>";
				$m++;
			}
			$this->salida .= "       </td>";
      $this->salida .= "       </tr>";
			$y++;
    }
		$this->salida .= "			    </table><BR>";
    $this->salida .= "         <table class=\"normal_10N\"  border=\"0\" width=\"90%\" align=\"center\">";
		$accionAdicGrupo=ModuloGetURL('app','InvCodificacionPtos','user','InsertarGrupoclasify',array("grupo"=>$GrupoId,"NombreGrupo"=>$NombreGrupo,"claseIn"=>$ClaseId,"NombreClase"=>$NombreClase,"subclase"=>$SubClaseId,"NombreSubClase"=>$NombreSubClase,'Empresa'=>$Empresa,'NombreEmp'=>$NombreEmp));
    $this->salida .= "				       <td><a href=\"$accionAdicGrupo\"><img border=\"0\" src=\"".GetThemePath()."/images/planblanco.png\"></a></td>";
		$this->salida .= "               <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
    $this->salida .="           </table><BR>";
		$this->salida .="          </form>";
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

	 function RetornarBarra($var){
		if($this->limit>=$this->conteo){
				return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
		if($var==1){
      $accion=ModuloGetURL('app','InvCodificacionPtos','user','ProductosNoExisEmpresas',array('conteo'=>$this->conteo,'NombreEmp'=>$_REQUEST['NombreEmp'],'Empresa'=>$_REQUEST['Empresa'],'grupo'=>$_REQUEST['grupo'],'clasePr'=>$_REQUEST['clasePr'],'subclase'=>$_REQUEST['subclase'],'NomGrupo'=>$_REQUEST['NomGrupo'],'NomClase'=>$_REQUEST['NomClase'],'NomSubClase'=>$_REQUEST['NomSubClase']));
		}elseif($var==2){
      $accion=ModuloGetURL('app','InvCodificacionPtos','user','BusquedaInventarios',array('conteo'=>$this->conteo,'NombreEmp'=>$_REQUEST['NombreEmp'],'Empresa'=>$_REQUEST['Empresa'],'grupo'=>$_REQUEST['grupo'],'clasePr'=>$_REQUEST['clasePr'],'subclase'=>$_REQUEST['subclase'],'NomGrupo'=>$_REQUEST['NomGrupo'],'NomClase'=>$_REQUEST['NomClase'],'NomSubClase'=>$_REQUEST['NomSubClase']));
		}else{
      $accion=ModuloGetURL('app','InvCodificacionPtos','user','BusquedaBDProductosInventarios',array('conteo'=>$this->conteo,'grupo'=>$_REQUEST['grupo'],'clasePr'=>$_REQUEST['clasePr'],'subclase'=>$_REQUEST['subclase'],'NomGrupo'=>$_REQUEST['NomGrupo'],'NomClase'=>$_REQUEST['NomClase'],'NomSubClase'=>$_REQUEST['NomSubClase'],'descripcionPro'=>$_REQUEST['descripcionPro'],'codigoPro'=>$_REQUEST['codigoPro'],"origenFun"=>$_REQUEST['origenFun']));
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
			if($diferencia<=0){$diferencia=1;}//CAmbiar en todas
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
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
		$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan='15' align='center'>P�gina $paso de $numpasos</td><tr></table>";
	}

	function FormaEditarProductoInventarioCodifi($NomGrupo,$grupo,$NomClase,$clasePr,$NomSubClase,$subclase,
		$codProducto,$DescripcionCompleta,$DescripcionAbreviada,$valorFab,$fabricante,$unidad,$cantidadUnidadMedida,$PorcentajeIva,$codigoInvima,$fechaVencimiento,
		$codigoPrincipal,$medicamento,$anatomofarmacologico,$principioactivo,$FormasFarmacologica,$concentracion,$concentracionFormaF,$medidaMedicamento,$factorConversion,$factorEquivmg,$viaAdministracion,
		$pos,$usoControlado,$antibiotico,$fotosensible,$refrigerado,$alimparenteral,$alimenteral,$solucion,$diasPrevios,$codigoBusqueda,$descripcionBusqueda,$codigoAnterior,$GrupoAnterior,$ClaseAnterior,$SubClaseAnterior,$paso,$Of,$consultaForma,$origenFun,$CodigoCum){

		if($consultaForma){
      $readonly='READONLY';
			$deshabilitado='DISABLED';
		}
    $this->salida .="<SCRIPT>";
	  $this->salida .="function abrirVentanaClas(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .=" f=frm;\n";
    $this->salida .=" var str = 'width=380,height=180,resizable=no,status=no,scrollbars=no,top=200,left=200';\n";
    $this->salida .=" var gr=frm.grupo.value;";
		$this->salida .=" var clas=frm.clasePr.value;";
		$this->salida .=" var sbclas=frm.subclase.value;";
		$this->salida .=" var url2 = url+'?grupo='+gr+'&clasePr='+clas+'&subclase='+sbclas;";
    $this->salida .=" var rems = window.open(url2, nombre, str);\n";
		$this->salida .=" if (rems != null) {\n";
		$this->salida .="   if (rems.opener == null) {\n";
		$this->salida .="	    rems.opener = self;\n";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .="}\n";
		$this->salida .="function abrirVentanaFab(nombre, url, ancho, altura, x,frm){\n";
		$this->salida .=" f=frm;";
    $this->salida .=" var str = 'width=550,height=570,resizable=no,status=no,scrollbars=yes,top=200,left=200';\n";
    $this->salida .=" var remd = window.open(url, nombre, str);\n";
		$this->salida .=" if (remd != null) {\n";
		$this->salida .="   if (remd.opener == null) {\n";
		$this->salida .="	    remd.opener = self;\n";
		$this->salida .="   }\n";
		$this->salida .=" }\n";
		$this->salida .="}\n";
		$this->salida .="function cambio(frm,valor){";
    $this->salida .=" if(valor!=-1){";
    $this->salida .=" frm.medida.value=valor;";
		$this->salida .=" }\n";
		$this->salida .=" }\n";
		$this->salida .="</SCRIPT>";
		$this->salida .= ThemeAbrirTabla('MODIFICAR PRODUCTO DE LA CODIFICACION GENERAL');
		$paso=$_REQUEST['paso'];
    $Of=$_REQUEST['Of'];
		$action=ModuloGetURL('app','InvCodificacionPtos','user','modificacionProductoCodificacion',array("paso"=>$paso,"Of"=>$Of));
		$this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "<table class=\"normal_10\"  border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "<tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
    $this->salida .= "<tr><td width=\"100%\" class=\"modulo_table_list_title\">DATOS DEL PRODUCTO</td></tr>";
    $this->salida .= "<tr><td width=\"100%\" class=\"modulo_list_claro\">";
		$this->salida .= "      <br><table class=\"normal_10\"  border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "      <input type=\"hidden\" value=\"$codigoAnterior\" name=\"codigoAnterior\">";
		$this->salida .= "      <input type=\"hidden\" value=\"$GrupoAnterior\" name=\"GrupoAnterior\">";
		$this->salida .= "      <input type=\"hidden\" value=\"$ClaseAnterior\" name=\"ClaseAnterior\">";
		$this->salida .= "      <input type=\"hidden\" value=\"$SubClaseAnterior\" name=\"SubClaseAnterior\">";
		$this->salida .= "      <input type=\"hidden\" value=\"$codigoPrincipal\" name=\"codigoPrincipal\">";
		$this->salida .= "      <input type=\"hidden\" value=\"$codigoBusqueda\" name=\"codigoBusqueda\">";
		$this->salida .= "      <input type=\"hidden\" value=\"$descripcionBusqueda\" name=\"descripcionBusqueda\">";
		$this->salida .= "      <input type=\"hidden\" value=\"$consultaForma\" name=\"consultaForma\">";
		$this->salida .= "      <input type=\"hidden\" value=\"$origenFun\" name=\"origenFun\">";
    $this->salida .= "		  <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	    <td class=\"".$this->SetStyle("grupo")."\">GRUPO:</td>";
		$this->salida .= "	    <td><input type=\"text\" name=\"NomGrupo\" value=\"$NomGrupo\" size=\"30\" class=\"input-text\" readonly></td>";
		$this->salida .= "     <input type=\"hidden\" name=\"grupo\" value=\"$grupo\" class=\"input-text\">";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("clasePr")."\">CLASE: </td>";
		$this->salida .= "	  	<td><input type=\"text\" name=\"NomClase\"  value=\"$NomClase\" size=\"30\" class=\"input-text\" readonly>";
		$this->salida .= "     <input type=\"hidden\" name=\"clasePr\" value=\"$clasePr\" class=\"input-text\" ></td>";
    $this->salida .= "		  <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "	  	<td class=\"".$this->SetStyle("subclase")."\">SUBCLASE: </td>";
		$this->salida .= "	  	<td><input type=\"text\" name=\"NomSubClase\"  value=\"$NomSubClase\" size=\"30\" class=\"input-text\" readonly>";
		$ruta='app_modules/InvCodificacionPtos/ventanaClasificacion.php';
		$this->salida .= "	 		&nbsp&nbsp&nbsp;<input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCION\" onclick=\"abrirVentanaClas('CLASIFICACION','$ruta',450,200,0,this.form)\" $deshabilitado></td>";
		$this->salida .= "      <input type=\"hidden\" name=\"subclase\" value=\"$subclase\" class=\"input-text\" >";
		$this->salida .= "		  </tr>";
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("codProducto")."\">CODIGO PRODUCTO</td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"4\" size=\"4\" name=\"codProducto\" value=\"$codProducto\" class=\"input-text\" readonly></td></tr>";
    $this->salida .= "	  	<tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("DescripcionCompleta")."\">DESCRIPCION COMPLETA</td>";
		$this->salida .= "	  	<td><input width=\"100%\" type=\"text\" size=\"50\" maxlength=\"60\" name=\"DescripcionCompleta\" value=\"$DescripcionCompleta\" class=\"input-text\" $readonly></td></tr>";
    $this->salida .= "	  	<tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("DescripcionAbreviada")."\">DESCRIPCION ABREVIADA</td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"30\" size=\"30\" name=\"DescripcionAbreviada\" value=\"$DescripcionAbreviada\" class=\"input-text\" $readonly>&nbsp;(Para Impresion en POS)</td></tr>";
		$ruta1='app_modules/InvCodificacionPtos/AdicionarFabricante.php?Empresa='.$Empresa;
		$this->salida .= "	  	<tr class=\"modulo_list_oscuro\" height=\"20\"><td class=\"".$this->SetStyle("fabricante")."\">FABRICANTE:</td>";
		$this->salida .= "	  	<td><input type=\"text\" name=\"fabricante\" value=\"$fabricante\" size=\"40\" class=\"input-text\" readonly>";
		$this->salida .= "		  <input type=\"hidden\" name=\"valorFab\" value=\"$valorFab\">";
    $this->salida .= "	 		&nbsp&nbsp&nbsp;<input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"SELECCION\" onclick=\"abrirVentanaFab('FABRICANTE','$ruta1',700,50,0,this.form)\" $deshabilitado></td>";
		$this->salida .= "		  </tr>";
		$this->salida .= "		  <tr class=\"modulo_list_oscuro\" height=\"20\"><td class=\"".$this->SetStyle("unidad")."\">UNIDAD DE MEDIDA:</td><td><select name=\"unidad\"  class=\"select\" $deshabilitado>";
		$Unidades=$this->UnidadesMedida();
		$this->Mostrar($Unidades,'False',$unidad);
		$this->salida .= "      </select>&nbsp&nbsp&nbsp;<b>POR</b>&nbsp&nbsp&nbsp;<input size=\"30\" type=\"text\" class=\"input-text\" name=\"cantidadUnidadMedida\" value=\"$cantidadUnidadMedida\" $deshabilitado>&nbsp&nbsp&nbsp;</td></tr>";
    $this->salida .= "	  	 <tr class=\"modulo_list_oscuro\" ><td class=\"".$this->SetStyle("PorcentajeIva")."\">PORCENTAJE IVA</td>";
		$this->salida .= "	  	 <td><input type=\"text\" maxlength=\"3\" size=\"8\" name=\"PorcentajeIva\" value=\"$PorcentajeIva\" class=\"input-text\" $readonly>&nbsp;<b>%</b></td></tr>";
		$this->salida .= "	  	    <tr class=\"modulo_list_oscuro\" ><td class=\"".$this->SetStyle("codigoInvima")."\">CODIGO INVIMA</td>";
		$this->salida .= "	  	    <td><input type=\"text\" maxlength=\"30\" size=\"30\" name=\"codigoInvima\" value=\"$codigoInvima\" class=\"input-text\" $readonly></td></tr>";
    if($fechaVencimiento){$var6='checked';}
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("fechaVencimiento")."\">PROPONER CONTROL<br>FECHA VENCIMIENTO</td>";
		$this->salida .= "      <td align=\"left\"><input type=\"checkbox\" name=\"fechaVencimiento\" $var6></td></tr>";
		$this->salida .= "			 </table><BR>";
		$this->salida .= "</td></tr>";
    if($medicamento){
			$this->salida .= "      <input type=\"hidden\" value=\"$medicamento\" name=\"medicamento\">";
		  $this->salida .= "<tr><td width=\"100%\" class=\"modulo_table_list_title\">DATOS DEL MEDICAMENTO</td></tr>";
      $this->salida .= "<tr><td width=\"100%\" class=\"modulo_list_claro\">";
		  $this->salida .= "      <br><table class=\"normal_10\"  border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("anatomofarmacologico")."\">ANATOMOFARMACOLOGICO</td>";
			$this->salida .= "	    <td><select name=\"anatomofarmacologico\" class=\"select\" $deshabilitado>";
			$AnatomoFarmacologicos=$this->AnatomoFarmacologicos();
			$this->MostrarAnatomo($AnatomoFarmacologicos,'False',$anatomofarmacologico);
			$this->salida .= "      </select></td><td class=\"modulo_list_oscuro\"><input type=\"submit\" class=\"input-submit\" name=\"adicionAnatomo\" value=\"ADICIONAR\" $deshabilitado></td></tr>";
			$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("principioactivo")."\">PRINCIPIO ACTIVO</td>";
			$this->salida .= "	    <td><select name=\"principioactivo\" class=\"select\" $deshabilitado>";
			$PrincipiosActivos=$this->PrincipiosActivos();
			$this->MostrarPrincipioA($PrincipiosActivos,'False',$principioactivo);
			$this->salida .= "      </select></td><td class=\"modulo_list_oscuro\"><input type=\"submit\" class=\"input-submit\" name=\"adicionPrincipio\" value=\"ADICIONAR\" $deshabilitado></td></tr>";
			$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("FormasFarmacologica")."\">FORMA FARMACOLOGICA</td>";
			$this->salida .= "	    <td><select name=\"FormasFarmacologica\" class=\"select\" $deshabilitado>";
			$FormasFarmacologicaTotal=$this->FormasFarmacologicas();
			$this->MostrarFormaFarma($FormasFarmacologicaTotal,'False',$FormasFarmacologica);
			$this->salida .= "      </select></td><td class=\"modulo_list_oscuro\"><input type=\"submit\" class=\"input-submit\" name=\"adicionForma\" value=\"ADICIONAR\" $deshabilitado></td></tr>";
			$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("concentracion")."\">CONCENTRACION</td>";
      $this->salida .= "	  	<td colspan=\"2\"><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"concentracionFormaF\" value=\"$concentracionFormaF\" class=\"input-text\" $readonly>&nbsp&nbsp&nbsp;";
			$this->salida .= "	  	<input type=\"text\" size=\"10\" name=\"concentracion\" value=\"$concentracion\" class=\"input-text\" $readonly>&nbsp&nbsp&nbsp;";
      $this->salida .= "	  	<select name=\"medidaMedicamento\" class=\"select\" $deshabilitado onchange=\"cambio(this.form,this.value)\">";
			$medidasMedicamentos=$this->UnidadesMedidasMedicamentos();
			$this->MostrarMedidasMedicamentos($medidasMedicamentos,'False',$medidaMedicamento);
			$this->salida .= "      </select></td></tr>";
			$this->salida .= "	  	<tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("concentracion")."\">FACTOR CONVERSION A ml</td>";
      $this->salida .= "	  	<td colspan=\"2\"><input type=\"text\" size=\"8\" name=\"factorConversion\" value=\"$factorConversion\" class=\"input-text\" $readonly>";
      if($medidaMedicamento!=-1){
        $medidaMedicamento1=$medidaMedicamento;
		  }
			$this->salida .= "	  	<input type=\"text\" size=\"4\" name=\"medida\" value=\"$medidaMedicamento1\" class=\"input-text\" readonly>&nbsp&nbsp;<label class=\"label\"><b>/</b></label>";
			$this->salida .= "	  	<input type=\"text\" size=\"8\" name=\"factorEquivmg\" value=\"$factorEquivmg\" class=\"input-text\" $readonly>&nbsp&nbsp;<b>mg</b>";
			$this->salida .= "	  	</td></tr>";

			if($pos){$var='checked';}
			$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("pos")."\">MEDICAMENTO POS</td>";
			$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"pos\" $var $deshabilitado></td></tr>";
			if($solucion){$var1='checked';}
			$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("solucion")."\">LIQUIDO ELECTROLITO</td>";
			$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"solucion\" $var1 $deshabilitado></td></tr>";
			if($usoControlado){$var2='checked';}
			$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("usoControlado")."\">USO CONTROLADO</td>";
			$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"usoControlado\" $var2 $deshabilitado></td></tr>";
			if($antibiotico){$var3='checked';}
			$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("antibiotico")."\">ANTIBIOTICO</td>";
			$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"antibiotico\" $var3 $deshabilitado></td></tr>";
			if($fotosensible){$var4='checked';}
			$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("fotosensible")."\">FOTOSENSIBLE</td>";
			$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"fotosensible\" $var4 $deshabilitado></td></tr>";
			if($refrigerado){$var5='checked';}
			$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("refrigerado")."\">REFRIGERACION</td>";
			$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"refrigerado\" $var5 $deshabilitado></td></tr>";
			if($alimparenteral){$var7='checked';}
			$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("alimparenteral")."\">ALIMENTO PARENTERAL</td>";
			$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"alimparenteral\" $var7 $deshabilitado></td></tr>";
      if($alimenteral){$var8='checked';}
			$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("alimenteral")."\">ALIMENTO ENTERAL</td>";
			$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"alimenteral\" $var8 $deshabilitado></td></tr>";
      $this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("diasPrevios")."\">DIAS PREVIOS AL VENCIMIENTO</td>";
			$this->salida .= "      <td colspan=\"2\" align=\"left\"><input size=\"4\" type=\"text\" name=\"diasPrevios\" value=\"$diasPrevios\" $readonly></td></tr>";
			
			//
			$this->salida .= "	  	    <tr class=\"modulo_list_oscuro\" ><td class=\"".$this->SetStyle("CodigoCum")."\">CODIGO CUM</td>";
			$this->salida .= "	  	    <td><input type=\"text\" maxlength=\"40\" size=\"35\" name=\"CodigoCum\" value=\"$CodigoCum\" class=\"input-text\"></td></tr>";
			//

			$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"label\">VIAS ADMINISTRACION</td>";
      $this->salida .= "	    <td colspan=\"2\">";
      $tipoVias=$this->ClasificacionViasAdmon();
			if($tipoVias){
			  $this->ConfirmarExisteViaMedicamento($codigoPrincipal);
			  $this->salida .= "      <BR><table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
        for($i=0;$i<sizeof($tipoVias);$i++){
          $this->salida .= "	    <tr class=\"modulo_list_claro\">";
          $this->salida .= "	    <td class=\"label\" align=\"center\">".$tipoVias[$i]['descripcion']."</td>";
          $this->salida .= "	    <td>";
          $TotalVias=$this->ViasAdmonSegunTipo($tipoVias[$i]['tipo_via_id']);
			    if($TotalVias){
					  $this->salida .= "      <table class=\"normal_10\" width=\"100%\" border=\"0\" align=\"center\">";
						for($j=0;$j<sizeof($TotalVias);$j++){
							$this->salida .= "	    <tr class=\"modulo_list_oscuro\">";
							$checkeado='';
							if($_SESSION['INVENTARIOS']['VIAS']['MEDICAMENTOS'][$TotalVias[$j]['via_administracion_id']]==1){
                $checkeado='checked';
							}
							$this->salida .= "	    <td width=\"5%\"><input type=\"checkbox\" name=\"vias[]\" value=\"".$TotalVias[$j]['via_administracion_id']."\" $deshabilitado $checkeado></td>";
							$this->salida .= "	    <td>".$TotalVias[$j]['nombre']."</td>";
							$this->salida .= "	    </tr>";
						}
						$this->salida .= "      </table>";
					}
          $this->salida .= "	    </td>";
					$this->salida .= "	    </tr>";
				}
				$this->salida .= "      </table><BR>";
			}
			unset($_SESSION['INVENTARIOS']['VIAS']['MEDICAMENTOS']);
			$this->salida .= "	    </td></tr>";
			$this->salida .= "			</table><BR>";
			$this->salida .= "</td></tr>";
		}
		$this->salida .= "<tr><td>&nbsp;</td></tr>";
    $this->salida .= "<tr><td align=\"center\"><input type=\"submit\" name=\"Regresar\" value=\"VOLVER\" class=\"input-submit\">";
		if(!$consultaForma){
		$this->salida .= "<input type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\" class=\"input-submit\">";
		}
		$this->salida .= "</td></tr>";
    $this->salida .= "</table><BR>";
		$this->salida .="</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
/**
* Funcion que se encarga de visualizar un error en un campo
* @return string
*/
	function SetStyle($campo){
		if ($this->frmError[$campo] || $campo=="MensajeError"){
		  if ($campo=="MensajeError"){
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}

/**
* Funcion que se encarga de visualizar en un OBJETO select las opciones del arreglo enviadas por parametro
* @return array
* @param array Arreglo que se va a visualizar en el OBJETO select
* @param boolean que indica si anteriormente habia una opcion seleccionada
* @param string elemento seleccionado anteriormente
*/

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
* Funcion que se encarga de listar los elementos pasados por parametros
* @return array
* @param array codigos y valores que vienen en el arreglo
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los valores del arreglo
* @param string elemento seleccionado en el objeto donde se imprimen los valores
*/
	function MostrarSin($arreglo,$Seleccionado='False',$Defecto=''){

	  switch($Seleccionado){
			case 'False':{
			  foreach($arreglo as $value=>$titulo){
					if($value==$Defecto){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
		  }
			case 'True':{
				foreach($arreglo as $value=>$titulo){
					if($value==$Defecto){
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
* Funcion que se encarga de visualizar en un OBJETO select las opciones del arreglo enviadas por parametro
* @return array
* @param array Arreglo que se va a visualizar en el OBJETO select
* @param boolean que indica si anteriormente habia una opcion seleccionada
* @param string elemento seleccionado anteriormente
*/

	function MostrarAnatomo($Arreglo,$Seleccionado='False',$variable=''){
	  switch($Seleccionado){
			case 'False':{
			  $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
			  foreach($Arreglo as $value=>$titulo){
				  $titulo1=substr($titulo,0,70);
					if($value==$variable){
						$this->salida .=" <option value=\"$value\" selected>$value&nbsp;$titulo1</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$value&nbsp;$titulo1</option>";
					}
				}
				break;
		  }
			case 'True':{
			  foreach($Arreglo as $value=>$titulo){
					if($value==$variable){
					  $titulo1=substr($titulo,0,80);
						$this->salida .=" <option value=\"$value\" selected>$value&nbsp;$titulo1</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$value&nbsp;$titulo1</option>";
					}
				}
				break;
			}
		}
	}

/**
* Funcion que se encarga de visualizar en un OBJETO select las opciones del arreglo enviadas por parametro
* @return array
* @param array Arreglo que se va a visualizar en el OBJETO select
* @param boolean que indica si anteriormente habia una opcion seleccionada
* @param string elemento seleccionado anteriormente
*/

	function MostrarPrincipioA($Arreglo,$Seleccionado='False',$variable=''){
	  switch($Seleccionado){
			case 'False':{
			  $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
			  foreach($Arreglo as $value=>$titulo){
				  $titulo1=substr($titulo,0,70);
					if($value==$variable){
						$this->salida .=" <option value=\"$value\" selected>$value&nbsp;$titulo1</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$value&nbsp;$titulo1</option>";
					}
				}
				break;
		  }
			case 'True':{
			  foreach($Arreglo as $value=>$titulo){
					if($value==$variable){
					  $titulo1=substr($titulo,0,80);
						$this->salida .=" <option value=\"$value\" selected>$value&nbsp;$titulo1</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$value&nbsp;$titulo1</option>";
					}
				}
				break;
			}
		}
	}

/**
* Funcion que se encarga de visualizar en un OBJETO select las opciones del arreglo enviadas por parametro
* @return array
* @param array Arreglo que se va a visualizar en el OBJETO select
* @param boolean que indica si anteriormente habia una opcion seleccionada
* @param string elemento seleccionado anteriormente
*/

	function MostrarFormaFarma($Arreglo,$Seleccionado='False',$variable=''){
	  switch($Seleccionado){
			case 'False':{
			  $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
			  foreach($Arreglo as $value=>$titulo){
				  $titulo1=substr($titulo,0,70);
					if($value==$variable){
						$this->salida .=" <option value=\"$value\" selected>$value&nbsp;$titulo1</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$value&nbsp;$titulo1</option>";
					}
				}
				break;
		  }
			case 'True':{
			  foreach($Arreglo as $value=>$titulo){
					if($value==$variable){
					  $titulo1=substr($titulo,0,80);
						$this->salida .=" <option value=\"$value\" selected>$value&nbsp;$titulo1</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$value&nbsp;$titulo1</option>";
					}
				}
				break;
			}
		}
	}


	function FormaDatosMedicamentos($grupo,$clasePr,$subclase,$NomGrupo,$NomSubGrupo,$NomClase,$NomSubClase,$codMedicamento,$codAnexo,$FormasFarmacologica,$concentracion,$concentracionFormaF,$medidaMedicamento,$factorConversion,$factorEquivmg,$viaAdministracion,$principioactivo,$anatomofarmacologico,$pos,$usoControlado,$antibiotico,$fotosensible,$refrigerado,$alimparenteral,$alimenteral,$codProducto,$solucion,$diasPrevios,$descripcion){

		$this->salida .= ThemeAbrirTabla('MEDICAMENTOS INVENTARIO GRUPO  '.$NomGrupo);
		$this->salida .="<SCRIPT>";
		$this->salida .="function cambio(frm,valor){";
    $this->salida .=" if(valor!=-1){";
    $this->salida .=" frm.medida.value=valor;";
		$this->salida .=" }\n";
		$this->salida .=" }\n";
		$this->salida .="</SCRIPT>";
		$action=ModuloGetURL('app','InvCodificacionPtos','user','InsertarMedicamentoInventarios');
		$this->salida .= "     <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "		        <input type=\"hidden\" name=\"grupo\" value=\"$grupo\" >";
		$this->salida .= "		        <input type=\"hidden\" name=\"subgrupo\" value=\"$subgrupo\" >";
		$this->salida .= "		        <input type=\"hidden\" name=\"clasePr\" value=\"$clasePr\" >";
		$this->salida .= "		        <input type=\"hidden\" name=\"subclase\" value=\"$subclase\" >";
		$this->salida .= "		        <input type=\"hidden\" name=\"NomGrupo\" value=\"$NomGrupo\" >";
		$this->salida .= "		        <input type=\"hidden\" name=\"NomSubGrupo\" value=\"$NomSubGrupo\">";
		$this->salida .= "		        <input type=\"hidden\" name=\"NomClase\" value=\"$NomClase\">";
		$this->salida .= "		        <input type=\"hidden\" name=\"NomSubClase\" value=\"$NomSubClase\">";
		$this->salida .= "		        <input type=\"hidden\" name=\"codProducto\" value=\"$codProducto\">";
		$this->salida .= "		        <input type=\"hidden\" name=\"descripcion\" value=\"$descripcion\">";
		$this->salida .= "		        <input type=\"hidden\" name=\"codMedicamento\" value=\"$codMedicamento\">";
		$this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"40%\" align=\"center\" >";
		$this->salida .= "         <tr><td><fieldset><legend class=\"field\">DATOS MEDICAMENTOS</legend>";
		$this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"85%\" align=\"center\" >";
    $this->salida .= "         <tr class=\"modulo_list_oscuro\"><td>CODIGO</td><td>$codMedicamento</td></tr>";
		$this->salida .= "         <tr class=\"modulo_list_oscuro\"><td>DESCRIPCION</td><td>$descripcion</td></tr>";
		$this->salida .= "			    </table>";
		$this->salida .= "		      </fieldset></td></tr>";
    $this->salida .= "          </table><BR>";
		$this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"95%\" align=\"center\" >";
		$this->salida .= "		      <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "		      </td></tr>";
    $this->salida .= "         <tr class=\"modulo_table_list_title\"><td>DATOS DEL MEDICAMENTO</td></tr>";
    $this->salida .= "         <tr class=\"modulo_list_claro\"><td width=\"100%\">";
		$this->salida .= "         <BR><table class=\"normal_10\"  border=\"0\" width=\"95%\" align=\"center\" >";
		$this->salida .= "	      <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("anatomofarmacologico")."\">ANATOMOFARMACOLOGICO:</td>";
		$this->salida .= "	      <td><select name=\"anatomofarmacologico\" class=\"select\">";
		$AnatomoFarmacologicos=$this->AnatomoFarmacologicos();
		$this->MostrarAnatomo($AnatomoFarmacologicos,'False',$anatomofarmacologico);
    $this->salida .= "       </select></td><td><input type=\"submit\" class=\"input-submit\" name=\"adicionAnatomo\" value=\"ADICIONAR\"></td></tr>";
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("principioactivo")."\">PRINCIPIO ACTIVO:</td>";
		$this->salida .= "	    <td><select name=\"principioactivo\" class=\"select\">";
		$PrincipiosActivos=$this->PrincipiosActivos();
		$this->MostrarPrincipioA($PrincipiosActivos,'False',$principioactivo);
    $this->salida .= "      </select></td><td><input type=\"submit\" class=\"input-submit\" name=\"adicionPrincipio\" value=\"ADICIONAR\"></td></tr>";
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("FormasFarmacologica")."\">FORMA FARMACOLOGICA:</td>";
		$this->salida .= "	    <td><select name=\"FormasFarmacologica\" class=\"select\">";
		$FormasFarmacologicaTotal=$this->FormasFarmacologicas();
		$this->MostrarFormaFarma($FormasFarmacologicaTotal,'False',$FormasFarmacologica);
    $this->salida .= "      </select></td><td><input type=\"submit\" class=\"input-submit\" name=\"adicionForma\" value=\"ADICIONAR\"></td></tr>";
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("concentracion")."\">CONCENTRACION</td>";
		$this->salida .= "	  	<td colspan=\"2\"><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"concentracionFormaF\" value=\"$concentracionFormaF\" class=\"input-text\" $readonly>&nbsp&nbsp&nbsp;";
		$this->salida .= "	  	<input type=\"text\" size=\"10\" name=\"concentracion\" value=\"$concentracion\" class=\"input-text\" $readonly>&nbsp&nbsp&nbsp;";
		$this->salida .= "	  	<select name=\"medidaMedicamento\" class=\"select\" $deshabilitado onchange=\"cambio(this.form,this.value)\">";
		$medidasMedicamentos=$this->UnidadesMedidasMedicamentos();
		$this->MostrarMedidasMedicamentos($medidasMedicamentos,'False',$medidaMedicamento);
		$this->salida .= "      </select></td></tr>";
		$this->salida .= "	  	<tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("concentracion")."\">FACTOR CONVERSION A ml</td>";
		$this->salida .= "	  	<td colspan=\"2\"><input type=\"text\" size=\"8\" name=\"factorConversion\" value=\"$factorConversion\" class=\"input-text\" $readonly>";
		if($medidaMedicamento!=-1){
			$medidaMedicamento1=$medidaMedicamento;
		}
		$this->salida .= "	  	<input type=\"text\" size=\"4\" name=\"medida\" value=\"$medidaMedicamento1\" class=\"input-text\" readonly>&nbsp&nbsp;<label class=\"label\"><b>/</b></label>";
		$this->salida .= "	  	<input type=\"text\" size=\"8\" name=\"factorEquivmg\" value=\"$factorEquivmg\" class=\"input-text\" $readonly>&nbsp&nbsp;<b>mg</b>";
		$this->salida .= "	  	</td></tr>";

		if($pos){$var='checked';}
    $this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("pos")."\">MEDICAMENTO POS:&nbsp&nbsp&nbsp;";
		$this->salida .= "      </td><td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"pos\" $var></td></tr>";
		if($solucion){$var1='checked';}
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("solucion")."\">LIQUIDO ELECTROLITO:&nbsp&nbsp&nbsp;";
		$this->salida .= "      </td><td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"solucion\" $var1></td></tr>";
		if($usoControlado){$var2='checked';}
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("usoControlado")."\">USO CONTROLADO</td>";
		$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"usoControlado\" $var2 $deshabilitado></td></tr>";
		if($antibiotico){$var3='checked';}
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("antibiotico")."\">ANTIBIOTICO</td>";
		$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"antibiotico\" $var3 $deshabilitado></td></tr>";
		if($fotosensible){$var4='checked';}
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("fotosensible")."\">FOTOSENSIBLE</td>";
		$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"fotosensible\" $var4 $deshabilitado></td></tr>";
		if($refrigerado){$var5='checked';}
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("refrigerado")."\">REFRIGERACION</td>";
		$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"refrigerado\" $var5 $refrigerado></td></tr>";
		if($alimparenteral){$var7='checked';}
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("alimparenteral")."\">ALIMENTO PARENTERAL</td>";
		$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"alimparenteral\" $var7 $alimparenteral></td></tr>";
		if($alimenteral){$var8='checked';}
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("alimenteral")."\">ALIMENTO ENTERAL</td>";
		$this->salida .= "      <td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"alimenteral\" $var8 $alimenteral></td></tr>";
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("diasPrevios")."\">DIAS PREVIOS AL VENCIMIENTO</td>";
		$this->salida .= "      <td colspan=\"2\" align=\"left\"><input size=\"4\" type=\"text\" name=\"diasPrevios\" value=\"$diasPrevios\"></td></tr>";
		
		//
		$this->salida .= "	  	    <tr class=\"modulo_list_oscuro\" ><td class=\"".$this->SetStyle("CodigoCum")."\">CODIGO CUM</td>";
		$this->salida .= "	  	    <td><input type=\"text\" maxlength=\"40\" size=\"35\" name=\"CodigoCum\" value=\"$CodigoCum\" class=\"input-text\"></td></tr>";
		//

		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"label\">VIAS ADMINISTRACION</td>";
		$this->salida .= "	    <td colspan=\"2\">";
		$tipoVias=$this->ClasificacionViasAdmon();
		if($tipoVias){
			$this->salida .= "      <BR><table class=\"normal_10\" width=\"95%\" border=\"0\" align=\"center\">";
			for($i=0;$i<sizeof($tipoVias);$i++){
				$this->salida .= "	    <tr class=\"modulo_list_claro\">";
				$this->salida .= "	    <td class=\"label\" align=\"center\">".$tipoVias[$i]['descripcion']."</td>";
				$this->salida .= "	    <td>";
				$TotalVias=$this->ViasAdmonSegunTipo($tipoVias[$i]['tipo_via_id']);
				if($TotalVias){
					$this->salida .= "      <table class=\"normal_10\" width=\"100%\" border=\"0\" align=\"center\">";
					for($j=0;$j<sizeof($TotalVias);$j++){
						$this->salida .= "	    <tr class=\"modulo_list_oscuro\">";
						$this->salida .= "	    <td width=\"5%\"><input type=\"checkbox\" name=\"vias[]\" value=\"".$TotalVias[$j]['via_administracion_id']."\"></td>";
						$this->salida .= "	    <td>".$TotalVias[$j]['nombre']."</td>";
						$this->salida .= "	    </tr>";
					}
					$this->salida .= "      </table>";
				}
				$this->salida .= "	    </td>";
				$this->salida .= "	    </tr>";
			}
			$this->salida .= "      </table><BR>";
		}
		$this->salida .= "	    </td></tr>";
		$this->salida .= "			</table><br>";
    $this->salida .= "      </td></tr>";
		$this->salida .= "      <tr><td colspan=\"2\" align=\"center\"><BR><input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"INSERTAR\"></td></tr>";
    $this->salida .= "     </table>";
    $this->salida .= " </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaDatosAdicionAnatomo($grupo,$clasePr,$subclase,$NomGrupo,$NomSubGrupo,$NomClase,$NomSubClase,$codMedicamento,$codAnexo,$FormasFarmacologica,$concentracion,$concentracionFormaF,$medidaMedicamento,$factorConversion,$factorEquivmg,$viaAdministracion,$principioactivo,$anatomofarmacologico,$pos,$usoControlado,$antibiotico,$fotosensible,$refrigerado,$alimparenteral,$alimenteral,$codProducto,$solucion,$diasPrevios,$descripcion,$action){

    $this->salida .= ThemeAbrirTabla('INSERTAR DATOS ANATOMOFARMACOLOGICO');
		if(!$action){
		  $action=ModuloGetURL('app','InvCodificacionPtos','user','InsertarDatosMedicamentos',array("origenFuncion"=>1,"grupo"=>$grupo,"clasePr"=>$clasePr,"subclase"=>$subclase,"NomGrupo"=>$NomGrupo,"NomSubGrupo"=>$NomSubGrupo,"NomClase"=>$NomClase,"NomSubClase"=>$NomSubClase,"codMedicamento"=>$codMedicamento,"codAnexo"=>$codAnexo,'FormasFarmacologica'=>$FormasFarmacologica,'concentracion'=>$concentracion,'concentracionFormaF'=>$concentracionFormaF,"medidaMedicamento"=>$medidaMedicamento,"factorConversion"=>$factorConversion,"factorEquivmg"=>$factorEquivmg,"viaAdministracion"=>$viaAdministracion,'principioactivo'=>$principioactivo,'anatomofarmacologico'=>$anatomofarmacologico,"pos"=>$pos,"usoControlado"=>$usoControlado,"antibiotico"=>$antibiotico,"fotosensible"=>$fotosensible,"refrigerado"=>$refrigerado,"alimparenteral"=>$alimparenteral,"alimenteral"=>$alimenteral,"codProducto"=>$codProducto,"solucion"=>$solucion,"diasPrevios"=>$diasPrevios,"descripcion"=>$descripcion));
		}
		$this->salida .= "      <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "      <table class=\"normal_10\"  border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "      <tr><td colspan=\"3\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "      </td></tr>";
    $this->salida .= "      <tr class=\"modulo_table_title\"><td align=\"center\">DATOS DEL ANATOMOFARMACOLOGICO</td></tr>";
    $this->salida .= "      <tr><td class=\"modulo_list_oscuro\">";
    $this->salida .= "        <BR><table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_list_claro\"><td class=\"label\">CODIGO</td><td><input size=\"6\" maxlength=\"6\" type=\"text\" class=\"input-text\" value=\"$codigoAnatomo\" name=\"codigoAnatomo\"></td></tr>";
    $this->salida .= "        <tr class=\"modulo_list_claro\"><td class=\"label\">DESCRIPCION</td><td><input type=\"text\" class=\"input-text\" value=\"$descripcionAnatomo\" name=\"descripcionAnatomo\"></td></tr>";
    $this->salida .= "        </table><BR>";
    $this->salida .= "      </td></tr>";
		$this->salida .= "      <tr><td align=\"center\"><BR><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name= \"Regresar\">";
		$this->salida .= "      <input type=\"submit\" class=\"input-submit\" value=\"INSERTAR\" name= \"Insertar\"></td></tr>";
    $this->salida .= "      </table>";
		if($action){
		$this->salida .= "      <input type=\"hidden\" name=\"action\" value=\"$action\">";
		}
		$this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaDatosAdicionPrincipioActivo($grupo,$clasePr,$subclase,$NomGrupo,$NomSubGrupo,$NomClase,$NomSubClase,$codMedicamento,$codAnexo,$FormasFarmacologica,$concentracion,$concentracionFormaF,$medidaMedicamento,$factorConversion,$factorEquivmg,$viaAdministracion,$principioactivo,$anatomofarmacologico,$pos,$usoControlado,$antibiotico,$fotosensible,$refrigerado,$alimparenteral,$alimenteral,$codProducto,$solucion,$diasPrevios,$descripcion,$action){
	  $this->salida .= ThemeAbrirTabla('INSERTAR DATOS PRINCIPIO ACTIVO');

		if(!$action){
		  $action=ModuloGetURL('app','InvCodificacionPtos','user','InsertarDatosMedicamentos',array("origenFuncion"=>2,"grupo"=>$grupo,"clasePr"=>$clasePr,"subclase"=>$subclase,"NomGrupo"=>$NomGrupo,"NomSubGrupo"=>$NomSubGrupo,"NomClase"=>$NomClase,"NomSubClase"=>$NomSubClase,"codMedicamento"=>$codMedicamento,"codAnexo"=>$codAnexo,'FormasFarmacologica'=>$FormasFarmacologica,'concentracion'=>$concentracion,'concentracionFormaF'=>$concentracionFormaF,"medidaMedicamento"=>$medidaMedicamento,"factorConversion"=>$factorConversion,"factorEquivmg"=>$factorEquivmg,'viaAdministracion'=>$viaAdministracion,'principioactivo'=>$principioactivo,'anatomofarmacologico'=>$anatomofarmacologico,"pos"=>$pos,"usoControlado"=>$usoControlado,"antibiotico"=>$antibiotico,"fotosensible"=>$fotosensible,"refrigerado"=>$refrigerado,"alimparenteral"=>$alimparenteral,"alimenteral"=>$alimenteral,"codProducto"=>$codProducto,"solucion"=>$solucion,"diasPrevios"=>$diasPrevios,"descripcion"=>$descripcion));
		}
		$this->salida .= "      <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "      <table class=\"normal_10\"  border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "      <tr><td colspan=\"3\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "      </td></tr>";
    $this->salida .= "      <tr class=\"modulo_table_title\"><td align=\"center\">DATOS DEL PRINCIPIO ACTIVO</td></tr>";
    $this->salida .= "      <tr><td class=\"modulo_list_oscuro\">";
    $this->salida .= "        <BR><table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_list_claro\"><td class=\"label\">CODIGO</td><td><input size=\"6\" maxlength=\"6\" type=\"text\" class=\"input-text\" value=\"$codigoPActivo\" name=\"codigoPActivo\"></td></tr>";
    $this->salida .= "        <tr class=\"modulo_list_claro\"><td class=\"label\">DESCRIPCION</td><td><input type=\"text\" class=\"input-text\" value=\"$descripcionPActivo\" name=\"descripcionPActivo\"></td></tr>";
    $this->salida .= "        </table><BR>";
    $this->salida .= "      </td></tr>";
		$this->salida .= "      <tr><td align=\"center\"><BR><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name= \"Regresar\">";
		$this->salida .= "      <input type=\"submit\" class=\"input-submit\" value=\"INSERTAR\" name= \"Insertar\"></td></tr>";
    $this->salida .= "      </table>";
		if($action){
		$this->salida .= "      <input type=\"hidden\" name=\"action\" value=\"$action\">";
		}
		$this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaDatosAdicionFormaFarma($grupo,$clasePr,$subclase,$NomGrupo,$NomSubGrupo,$NomClase,$NomSubClase,$codMedicamento,$codAnexo,$FormasFarmacologica,$concentracion,$concentracionFormaF,$medidaMedicamento,$factorConversion,$factorEquivmg,$viaAdministracion,$principioactivo,$anatomofarmacologico,$pos,$usoControlado,$antibiotico,$fotosensible,$refrigerado,$alimparenteral,$alimenteral,$codProducto,$solucion,$diasPrevios,$descripcion,$action){
	  $this->salida .= ThemeAbrirTabla('INSERTAR DATOS FORMA FARMACOLOGICA');
		if(!$action){
		  $action=ModuloGetURL('app','InvCodificacionPtos','user','InsertarDatosMedicamentos',array("origenFuncion"=>3,"grupo"=>$grupo,"clasePr"=>$clasePr,"subclase"=>$subclase,"NomGrupo"=>$NomGrupo,"NomSubGrupo"=>$NomSubGrupo,"NomClase"=>$NomClase,"NomSubClase"=>$NomSubClase,"codMedicamento"=>$codMedicamento,"codAnexo"=>$codAnexo,'FormasFarmacologica'=>$FormasFarmacologica,'concentracion'=>$concentracion,'concentracionFormaF'=>$concentracionFormaF,"medidaMedicamento"=>$medidaMedicamento,"factorConversion"=>$factorConversion,"factorEquivmg"=>$factorEquivmg,'viaAdministracion'=>$viaAdministracion,'principioactivo'=>$principioactivo,'anatomofarmacologico'=>$anatomofarmacologico,"pos"=>$pos,"usoControlado"=>$usoControlado,"antibiotico"=>$antibiotico,"fotosensible"=>$fotosensible,"refrigerado"=>$refrigerado,"alimparenteral"=>$alimparenteral,"alimenteral"=>$alimenteral,"codProducto"=>$codProducto,"solucion"=>$solucion,"diasPrevios"=>$diasPrevios,"descripcion"=>$descripcion));
		}
		$this->salida .= "      <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "      <table class=\"normal_10\"  border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "      <tr><td colspan=\"3\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "      </td></tr>";
    $this->salida .= "      <tr class=\"modulo_table_title\"><td align=\"center\">DATOS DE LA FORMA FARMACOLOGICA</td></tr>";
    $this->salida .= "      <tr><td class=\"modulo_list_oscuro\">";
    $this->salida .= "        <BR><table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_list_claro\"><td class=\"label\">CODIGO</td><td><input size=\"6\" maxlength=\"6\" type=\"text\" class=\"input-text\" value=\"$codigoFFarmacologica\" name=\"codigoFFarmacologica\"></td></tr>";
    $this->salida .= "        <tr class=\"modulo_list_claro\"><td class=\"label\">DESCRIPCION</td><td><input type=\"text\" class=\"input-text\" value=\"$descripcionFFarmacologica\" name=\"descripcionFFarmacologica\"></td></tr>";
    $this->salida .= "        </table><BR>";
    $this->salida .= "      </td></tr>";
		$this->salida .= "      <tr><td align=\"center\"><BR><input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name= \"Regresar\">";
		$this->salida .= "      <input type=\"submit\" class=\"input-submit\" value=\"INSERTAR\" name= \"Insertar\"></td></tr>";
    $this->salida .= "      </table>";
		if($action){
		$this->salida .= "      <input type=\"hidden\" name=\"action\" value=\"$action\">";
		}
		$this->salida .="       </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaDatosGrupoClasify($Empresa,$NombreEmp,$CodGrupo,$NombreGrupo,$swmedicamento,$swventa,$swinsumo){

		$this->salida .= ThemeAbrirTabla('ADICION DE UN GRUPO A LA CLASIFICACION DE ITEMS');
    $this->salida .= "<BR>";
		$actionTotal=ModuloGetURL('app','InvCodificacionPtos','user','AdicionDatosGrupoClasify');
    $this->salida .= "      <form name=\"forma\" action=\"$actionTotal\" method=\"post\">";
    $this->salida .= "      <table class=\"normal_10\"  border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "         <tr><td colspan=\"3\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "         </td></tr>";
    $this->salida .= "         <tr class=\"modulo_table_title\"><td align=\"center\" width=\"100%\">DATOS DEL GRUPO</td></tr>";
    $this->salida .= "         <tr><td width=\"100%\" align=\"center\" class=\"modulo_list_oscuro\">";
		$this->salida .= "         <BR><table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\" >";
		$this->salida .= "         <tr class=\"modulo_list_claro\">";
    $this->salida .= "         <td class=\"".$this->SetStyle("CodGrupo")."\">CODIGO GRUPO: </td>";
		$this->salida .= "	  	    <td><input type=\"text\" maxlength=\"2\" size=\"2\" name=\"CodGrupo\" value=\"$CodGrupo\" class=\"input-text\"></td>";
		$this->salida .= "         </tr>";
		$this->salida .= "         <tr class=\"modulo_list_claro\">";
		$this->salida .= "         <td class=\"".$this->SetStyle("NombreGrupo")."\">NOMBRE GRUPO: </td>";
		$this->salida .= "	  	    <td><input type=\"text\" maxlength=\"40\" size=\"30\" name=\"NombreGrupo\" value=\"$NombreGrupo\" class=\"input-text\"></td>";
		$this->salida .= "         </tr>";
		$this->salida .= "         <tr class=\"modulo_list_claro\">";
		if($swmedicamento){$var='checked';}
		$this->salida .= "         <td class=\"".$this->SetStyle("swmedicamento")."\">MEDICAMENTO:</td>";
		$this->salida .= "         <td><input type=\"checkbox\" name=\"swmedicamento\" $var></td>";
		$this->salida .= "         </tr>";

		$this->salida .= "         <tr class=\"modulo_list_claro\">";
		if($swinsumo){$var2='checked';}
		$this->salida .= "         <td class=\"".$this->SetStyle("swinsumo")."\">INSUMOS:</td>";
		$this->salida .= "         <td><input type=\"checkbox\" name=\"swinsumo\" $var2></td>";
		$this->salida .= "         </tr>";

		$this->salida .= "         <tr class=\"modulo_list_claro\">";
		if($swventa){$var1='checked';}
		$this->salida .= "         <td class=\"".$this->SetStyle("swventa")."\">VENTA:</td>";
		$this->salida .= "         <td><input type=\"checkbox\" name=\"swventa\"  $var1></td>";
    $this->salida .= "         </tr>";
		$this->salida .= "		    </table><BR>";
		$this->salida .= "		    </td></tr>";
    $this->salida .= "        <tr><td>&nbsp;</td></tr>";
		$this->salida .= "        <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"INSERTAR\">";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "		    </table><br>";
    $this->salida .="     </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;

	 }

	function LlamaFormaAdicionarClasify($grupo,$NombreGrupo,$claseIn,$NombreClase,$subclase,$NombreSubClase,$Empresa,$NombreEmp,$bandera,$CodSubClase,$NombreSubClass,$CodClase,$NombreClass){

		if($bandera==1){$palabra='CLASE';}else{$palabra='SUBCLASE';}
		$this->salida .= ThemeAbrirTabla('ADICION DE LA '.$palabra.' A LA CLASIFICACION');
    $this->salida .= "<BR>";
		$actionTotal=ModuloGetURL('app','InvCodificacionPtos','user','InsertarNuevoItemClasificacion');
		$this->salida .= "      <form name=\"forma\" action=\"$actionTotal\" method=\"post\">";
		$this->salida .= "	     <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "       <tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "			  <td>GRUPO</td>\n";
		if($bandera==2){
    $this->salida .= "			  <td>CLASE</td>\n";
		}
		$this->salida .= "		    </tr>\n";
		$this->salida .= "       <tr class=\"modulo_list_claro\" align=\"center\">";
    $this->salida .= "			  <td>$grupo $NombreGrupo</td>\n";
		if($bandera==2){
		$this->salida .= "			  <td>$claseIn $NombreClase</td>\n";
		}
    $this->salida .= "		    </tr>\n";
		$this->salida .= "			  </table><BR>";
    $this->salida .= "      <table class=\"normal_10\"  border=\"0\" width=\"70%\" align=\"center\">";
		$this->salida .= "         <input type=\"hidden\" name=\"bandera\" value=\"$bandera\"></td>";
		$this->salida .= "	  	   <input type=\"hidden\" name=\"grupo\" value=\"$grupo\">";
		$this->salida .= "	  	   <input type=\"hidden\" name=\"NombreGrupo\" value=\"$NombreGrupo\">";
		$this->salida .= "	  	   <input type=\"hidden\" name=\"claseIn\" value=\"$claseIn\">";
		$this->salida .= "	  	   <input type=\"hidden\" name=\"NombreClase\" value=\"$NombreClase\">";
		$this->salida .= "	  	   <input type=\"hidden\" name=\"subclase\" value=\"$subclase\">";
		$this->salida .= "	  	   <input type=\"hidden\" name=\"NombreSubClase\" value=\"$NombreSubClase\">";
		$this->salida .= "         <tr><td colspan=\"3\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "         </td></tr>";
    $this->salida .= "         <tr class=\"modulo_table_title\"><td align=\"center\" width=\"100%\">DATOS REQUERIDOS</td></tr>";
		$this->salida .= "         <tr><td width=\"100%\" class=\"modulo_list_oscuro\">";
		$this->salida .= "         <BR><table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\">";
		if($bandera==1){
		$this->salida .= "         <tr class=\"modulo_list_claro\">";
		$this->salida .= "         <td class=\"".$this->SetStyle("CodClase")."\">CODIGO CLASE: </td>";
		$this->salida .= "	  	    <td><input type=\"text\" maxlength=\"2\" size=\"2\" name=\"CodClase\" value=\"$CodClase\" class=\"input-text\"></td>";
		$this->salida .= "         <td class=\"".$this->SetStyle("NombreClass")."\">NOMBRE CLASE: </td>";
		$this->salida .= "	  	    <td><input type=\"text\" maxlength=\"40\" size=\"30\" name=\"NombreClass\" value=\"$NombreClass\" class=\"input-text\"></td>";
		$this->salida .= "         </tr>";
		}elseif($bandera==2){
		$this->salida .= "         <tr class=\"modulo_list_claro\">";
		$this->salida .= "         <td class=\"".$this->SetStyle("CodSubClase")."\">CODIGO SUBCLASE: </td>";
		$this->salida .= "	  	    <td><input type=\"text\" maxlength=\"2\" size=\"2\" name=\"CodSubClase\" value=\"$CodSubClase\" class=\"input-text\"></td>";
		$this->salida .= "         <td class=\"".$this->SetStyle("NombreSubClass")."\">NOMBRE SUBCLASE: </td>";
		$this->salida .= "	  	    <td><input type=\"text\" maxlength=\"40\" size=\"30\" name=\"NombreSubClass\" value=\"$NombreSubClass\" class=\"input-text\"></td>";
		$this->salida .= "         </tr>";
		}
    $this->salida .= "        </td></tr>";
		$this->salida .= "		    </table><BR>";
    $this->salida .= "		    <tr><td>&nbsp&nbsp;</td></tr>";
		$this->salida .= "        <tr><td align=\"center\" colspan=\"4\"><input class=\"input-submit\" type=\"submit\" name=\"Insertar\" value=\"INSERTAR\">&nbsp;";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "		    </table><br>";
    $this->salida .="     </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaEditarClasificacion($grupo,$NombreGrupo,$claseIn,$NombreClase,$subclase,$NombreSubClase,$bandera,$Empresa,$NombreEmp){

		if($bandera==1){$palabra='GRUPO';}elseif($bandera==2){$palabra='CLASE';}elseif($bandera==3){$palabra='SUBCLASE';}
		$this->salida .= ThemeAbrirTabla('EDICION CLASIFICACION INVENTARIO  '.$palabra);
    $this->salida .= "<BR>";
		$actionTotal=ModuloGetURL('app','InvCodificacionPtos','user','InsertarEdicionClasificacion');
		$this->salida .= "      <form name=\"forma\" action=\"$actionTotal\" method=\"post\">";
    if($bandera==2 || $bandera==3){
		$this->salida .= "	           <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "            <tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "			       <td>GRUPO</td>\n";
		if($bandera==3){
    $this->salida .= "			       <td>SUBGRUPO</td>\n";
		}
    $this->salida .= "		         </tr>\n";
		$this->salida .= "            <tr class=\"modulo_list_claro\" align=\"center\">";
    $this->salida .= "			       <td>$grupo $NombreGrupo</td>\n";
		if($bandera==3){
		$this->salida .= "			       <td>$claseIn $NombreClase</td>\n";
		}
    $this->salida .= "		         </tr>\n";
		$this->salida .= "			      </table><BR>";
		}
		$this->salida .= "      <table class=\"normal_10\"  border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "         <input type=\"hidden\" name=\"bandera\" value=\"$bandera\" class=\"input-text\"></td>";
		$this->salida .= "	  	   <input type=\"hidden\" maxlength=\"3\" size=\"3\" name=\"grupo\" value=\"$grupo\" class=\"input-text\" READONLY>";
		$this->salida .= "	  	   <input type=\"hidden\" maxlength=\"3\" size=\"3\" name=\"claseIn\" value=\"$claseIn\" class=\"input-text\" READONLY>";
		$this->salida .= "	  	   <input type=\"hidden\" maxlength=\"3\" size=\"3\" name=\"subclase\" value=\"$subclase\" class=\"input-text\" READONLY>";
		$this->salida .= "         <tr><td colspan=\"3\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "         </td></tr>";
		$this->salida .= "         <tr class=\"modulo_table_title\"><td align=\"center\" width=\"100%\">DESCRIPCION</td></tr>";
    $this->salida .= "         <tr><td width=\"100%\" class=\"modulo_list_oscuro\">";
		$this->salida .= "         <BR><table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\">";
    if($bandera==1){
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"".$this->SetStyle("NombreGrupo")."\">NOMBRE GRUPO: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" size=\"45\" name=\"NombreGrupo\" value=\"$NombreGrupo\" class=\"input-text\"></td>";
		$this->salida .= "        </tr>";
		}
		if($bandera==2){
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"".$this->SetStyle("NombreClase")."\">NOMBRE CLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" maxlength=\"80\" size=\"45\" name=\"NombreClase\" value=\"$NombreClase\" class=\"input-text\"></td>";
		$this->salida .= "        </tr>";
		}
		if($bandera==3){
    $this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"".$this->SetStyle("NombreSubClase")."\">NOMBRE SUBCLASE: </td>";
		$this->salida .= "	  	   <td><input type=\"text\" maxlength=\"80\" size=\"45\" name=\"NombreSubClase\" value=\"$NombreSubClase\" class=\"input-text\"></td>";
		$this->salida .= "        </tr>";
		}
		$this->salida .= "		    </table><BR>";
		$this->salida .= "		    </td></tr>";
		$this->salida .= "		    <tr><td>&nbsp&nbsp;</td></tr>";
		$this->salida .= "        <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Modificar\" value=\"MODIFICAR\">&nbsp;";
		$this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"VOLVER\"></td></tr>";
		$this->salida .= "		    </table><br>";
		$this->salida .="     </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function AdicionarViaAdmonMtos($NomGrupo,$grupo,$NomClase,$clasePr,$NomSubClase,$subclase,
	$codProducto,$DescripcionCompleta,$DescripcionAbreviada,$valorFab,$fabricante,$unidad,$cantidadUnidadMedida,$PorcentajeIva,$codigoInvima,$fechaVencimiento,
	$codigoPrincipal,$medicamento,$anatomofarmacologico,$principioactivo,$FormasFarmacologica,$concentracion,$concentracionFormaF,$medidaMedicamento,$factorConversion,$factorEquivmg,$viaAdministracion,
	$pos,$usoControlado,$antibiotico,$fotosensible,$refrigerado,$alimparenteral,$alimenteral,$solucion,$diasPrevios,$codigoBusqueda,$descripcionBusqueda,$codigoAnterior,$GrupoAnterior,$ClaseAnterior,$SubClaseAnterior,$paso,$Of,$origenFun){
	$this->salida .= ThemeAbrirTabla('MEDICAMENTOS Y SUS VIAS DE ADMINISTRACION');
	$action=ModuloGetURL('app','InvCodificacionPtos','user','InsertarViasAdmonMedicamento',array("NomGrupo"=>$NomGrupo,"grupo"=>$grupo,
	"NomClase"=>$NomClase,"clasePr"=>$clasePr,"NomSubClase"=>$NomSubClase,"subclase"=>$subclase,
	"codProducto"=>$codProducto,"DescripcionCompleta"=>$DescripcionCompleta,"DescripcionAbreviada"=>$DescripcionAbreviada,
	"valorFab"=>$valorFab,"fabricante"=>$fabricante,"unidad"=>$unidad,"cantidadUnidadMedida"=>$cantidadUnidadMedida,"PorcentajeIva"=>$PorcentajeIva,"codigoInvima"=>$codigoInvima,"fechaVencimiento"=>$fechaVencimiento,
	"codigoPrincipal"=>$codigoPrincipal,"medicamento"=>$medicamento,"anatomofarmacologico"=>$anatomofarmacologico,
	"principioactivo"=>$principioactivo,"FormasFarmacologica"=>$FormasFarmacologica,"concentracion"=>$concentracion,"concentracionFormaF"=>$concentracionFormaF,"medidaMedicamento"=>$medidaMedicamento,"factorConversion"=>$factorConversion,"factorEquivmg"=>$factorEquivmg,"viaAdministracion"=>$viaAdministracion,
	"pos"=>$pos,"usoControlado"=>$usoControlado,"antibiotico"=>$antibiotico,"fotosensible"=>$fotosensible,"refrigerado"=>$refrigerado,"alimparenteral"=>$alimparenteral,"alimenteral"=>$alimenteral,"solucion"=>$solucion,"diasPrevios"=>$diasPrevios,"codigoBusqueda"=>$codigoBusqueda,"descripcionBusqueda"=>$descripcionBusqueda,
	"codigoAnterior"=>$codigoAnterior,"GrupoAnterior"=>$GrupoAnterior,"ClaseAnterior"=>$ClaseAnterior,"SubClaseAnterior"=>$SubClaseAnterior,"paso"=>$paso,"Of"=>$Of,"origenFun"=>$origenFun));
		$this->salida .= "     <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= "      <table class=\"normal_10\"  border=\"0\" width=\"40%\" align=\"center\" >";
		$this->salida .= "         <tr><td><fieldset><legend class=\"field\">DATOS DEL MEDICAMENTO</legend>";
		$this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"85%\" align=\"center\" >";
    $this->salida .= "         <tr class=\"modulo_list_oscuro\"><td>CODIGO</td><td>$codigoPrincipal</td></tr>";
		$this->salida .= "         <tr class=\"modulo_list_oscuro\"><td>DESCRIPCION</td><td>$DescripcionCompleta</td></tr>";
		$this->salida .= "			    </table>";
		$this->salida .= "		      </fieldset></td></tr>";
    $this->salida .= "         </table><BR>";
		$this->salida .= "<table class=\"normal_10\"  border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "<tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr><td width=\"100%\" class=\"modulo_table_list_title\">VIAS DE ADMINISTRACION</td></tr>";
		$MedicamentosViasAdmon=$this->ViasAdmonMedicamentos($codigoPrincipal);
		if($MedicamentosViasAdmon){
			$this->salida .= "	    <td align=\"center\" width=\"100%\" class=\"modulo_list_oscuro\">";
			$this->salida .= "      <BR><table class=\"normal_10\" width=\"80%\" border=\"0\" align=\"center\">";
			for($i=0;$i<sizeof($MedicamentosViasAdmon);$i++){
				$this->salida .= "      <tr class=\"modulo_list_claro\"><td>".$MedicamentosViasAdmon[$i]['nombre']."</td></tr>";
			}
			$this->salida .= "      </table><BR>";
			$this->salida .= "</td></tr>";
		}
		$this->salida .= "<tr><td class=\"modulo_list_claro\">";
		$this->salida .= "      <BR><table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("viaAdministracion")."\">VIAS ADMINISTRACION</td><td colspan=\"2\"><select name=\"viaAdministracion\" class=\"select\" $deshabilitado>";
		$ViasAdministracion=$this->ViasAdministracionMedicamento();
		$this->Mostrar($ViasAdministracion,'False',$viaAdministracion);
		$this->salida .= "      </select></td></tr>";
		$this->salida .= "      </table><BR>";
		$this->salida .= "</td></tr>";
    $this->salida .= "<tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"regresar\" value=\"VOLVER\">";
		$this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"insertar\" value=\"INSERTAR\"></td></tr>";
		$this->salida .= "</table>";
    $this->salida .= " </form>";
		$this->salida .= ThemeCerrarTabla();
	}

	function AdicionarViaAdmonMtosUno($codMedicamento,$grupo,$clasePr,$subclase,$NomGrupo,$NomSubGrupo,$NomClase,$NomSubClase,$codMedicamento,$codAnexo,
		$FormasFarmacologica,$concentracion,$concentracionFormaF,$medidaMedicamento,$factorConversion,$factorEquivmg,$viaAdministracion,
		$principioactivo,$anatomofarmacologico,$pos,$usoControlado,$antibiotico,$fotosensible,$refrigerado,$alimparenteral,$alimenteral,
		$codProducto,$solucion,$diasPrevios,$descripcion){
		$this->salida .= ThemeAbrirTabla('MEDICAMENTOS Y SUS VIAS DE ADMINISTRACION');
		$action=ModuloGetURL('app','InvCodificacionPtos','user','InsertarViasAdmonMedicamentoUno',array("codMedicamento"=>$codMedicamento,"grupo"=>$grupo,"clasePr"=>$clasePr,"subclase"=>$subclase,
		"NomGrupo"=>$NomGrupo,"NomSubGrupo"=>$NomSubGrupo,"NomClase"=>$NomClase,"NomSubClase"=>$NomSubClase,"codMedicamento"=>$codMedicamento,"codAnexo"=>$codAnexo,
		"FormasFarmacologica"=>$FormasFarmacologica,"concentracion"=>$concentracion,"concentracionFormaF"=>$concentracionFormaF,"medidaMedicamento"=>$medidaMedicamento,"factorConversion"=>$factorConversion,"factorEquivmg"=>$factorEquivmg,"viaAdministracion"=>$viaAdministracion,
		"principioactivo"=>$principioactivo,"anatomofarmacologico"=>$anatomofarmacologico,"pos"=>$pos,"usoControlado"=>$usoControlado,"antibiotico"=>$antibiotico,"fotosensible"=>$fotosensible,"refrigerado"=>$refrigerado,"alimparenteral"=>$alimparenteral,"alimenteral"=>$alimenteral,
		"codProducto"=>$codProducto,"solucion"=>$solucion,"diasPrevios"=>$diasPrevios,"descripcion"=>$descripcion));
		$this->salida .= "     <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= "      <table class=\"normal_10\"  border=\"0\" width=\"40%\" align=\"center\" >";
		$this->salida .= "         <tr><td><fieldset><legend class=\"field\">DATOS DEL MEDICAMENTO</legend>";
		$this->salida .= "         <table class=\"normal_10\"  border=\"0\" width=\"85%\" align=\"center\" >";
    $this->salida .= "         <tr class=\"modulo_list_oscuro\"><td>CODIGO</td><td>$codigoPrincipal</td></tr>";
		$this->salida .= "         <tr class=\"modulo_list_oscuro\"><td>DESCRIPCION</td><td>$DescripcionCompleta</td></tr>";
		$this->salida .= "			    </table>";
		$this->salida .= "		      </fieldset></td></tr>";
    $this->salida .= "         </table><BR>";
		$this->salida .= "<table class=\"normal_10\"  border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "<tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
		$this->salida .= "<tr><td width=\"100%\" class=\"modulo_table_list_title\">VIAS DE ADMINISTRACION</td></tr>";
		if($_SESSION['INVENTARIOS']['MEDICAMENTOS']){
			$this->salida .= "	    <td align=\"center\" width=\"100%\" class=\"modulo_list_oscuro\">";
			$this->salida .= "      <BR><table class=\"normal_10\" width=\"80%\" border=\"0\" align=\"center\">";
			foreach($_SESSION['INVENTARIOS']['MEDICAMENTOS'] as  $via=>$nombre){
				$this->salida .= "      <tr class=\"modulo_list_claro\"><td>$nombre</td></tr>";
      }
			$this->salida .= "      </table><BR>";
			$this->salida .= "</td></tr>";
		}
		$this->salida .= "<tr><td class=\"modulo_list_claro\">";
		$this->salida .= "      <BR><table class=\"normal_10\"  border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "	    <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("viaAdministracion")."\">VIAS ADMINISTRACION</td><td colspan=\"2\"><select name=\"viaAdministracion\" class=\"select\" $deshabilitado>";
		$ViasAdministracion=$this->ViasAdministracionMedicamento();
		$this->MostrarViaAdmon($ViasAdministracion,'False',$viaAdministracion);
		$this->salida .= "      </select></td></tr>";
		$this->salida .= "      </table><BR>";
		$this->salida .= "</td></tr>";
    $this->salida .= "<tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"regresar\" value=\"VOLVER\">";
		$this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"insertar\" value=\"INSERTAR\"></td></tr>";
		$this->salida .= "</table>";
    $this->salida .= " </form>";
		$this->salida .= ThemeCerrarTabla();

	}

/**
* Funcion que se encarga de visualizar en un OBJETO select las opciones del arreglo enviadas por parametro
* @return array
* @param array Arreglo que se va a visualizar en el OBJETO select
* @param boolean que indica si anteriormente habia una opcion seleccionada
* @param string elemento seleccionado anteriormente
*/

	function MostrarViaAdmon($Arreglo,$Seleccionado='False',$variable=''){
	  switch($Seleccionado){
			case 'False':{
			  $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
			  foreach($Arreglo as $value=>$titulo){
					if($value==$variable){
						$this->salida .=" <option value=\"$value/$titulo\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value/$titulo\">$titulo</option>";
					}
				}
				break;
		  }
			case 'True':{
			  foreach($Arreglo as $value=>$titulo){
					if($value==$variable){
						$this->salida .=" <option value=\"$value/$titulo\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value/$titulo\">$titulo</option>";
					}
				}
				break;
			}
		}
	}

/**
* Funcion que se encarga de visualizar en un OBJETO select las opciones del arreglo enviadas por parametro
* @return array
* @param array Arreglo que se va a visualizar en el OBJETO select
* @param boolean que indica si anteriormente habia una opcion seleccionada
* @param string elemento seleccionado anteriormente
*/

	function MostrarMedidasMedicamentos($Arreglo,$Seleccionado='False',$variable=''){
	  switch($Seleccionado){
			case 'False':{
			  $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
			  for($i=0;$i<sizeof($Arreglo);$i++){
				  $value=$Arreglo[$i]['unidad_medida_medicamento_id'];
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
					$value=$Arreglo[$i]['unidad_medida_medicamento_id'];
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

	/**
* La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
* @return boolean
* @param string mensaje a retornar para el usuario
* @param string titulo de la ventana a mostrar
* @param string lugar a donde debe retornar la ventana
* @param boolean tipo boton de la ventana
*/

	function FormaMensaje($mensaje,$titulo,$accion,$boton,$origen){
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
		$this->salida .= "			     </form>";
		$this->salida .= "			     </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




}//fin clase user
?>

