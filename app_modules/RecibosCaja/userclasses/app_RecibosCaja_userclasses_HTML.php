<?php

/* * ************************************************************************************  
 * $Id: app_RecibosCaja_userclasses_HTML.php,v 1.3 2010/04/12 19:35:44 hugo Exp $ 
 * 
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS-FI
 * 
 * $Revision: 1.3 $ 
 * 
 * @autor Hugo F  Manrique 
 * ************************************************************************************* */
IncludeClass("ClaseHTML");

class app_RecibosCaja_userclasses_HTML extends app_RecibosCaja_user {

    function app_RecibosCaja_userclasses_HTML()
    {
        return true;
    }

    /*     * *********************************************************************************
     * Muestra el menu de las empresas y centros de utilidad 
     * 
     * @access public 
     * ********************************************************************************** */

    function FormaMostrarMenuEmpresasRC($forma)
    {
        $this->salida .= $forma;
        return true;
    }

    /*     * ********************************************************************************* 
     * Muestra el men principal de recibos de caja
     * 
     * @access public 
     * ********************************************************************************** */

    function FormaMostrarMenuPrincipalRC()
    {
        $this->salida = ThemeAbrirTabla('MENU PRINCIPAL');
        $this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">\n";
        $this->salida .= "	<tr>\n";
        $this->salida .= "		<td >\n";
        $this->salida .= "			<table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "				<tr>\n";
        $this->salida .= "					<td align=\"center\" class=\"modulo_table_list_title\" width=\"30%\"> MENU</td>\n";
        $this->salida .= "				</tr>\n";

        /* echo '<pre>menus:';
          print_r($this->menus);
          echo '</pre>';
          echo '<pre>SESION:';
          print_r($_SESSION);
          echo '</pre>'; */

        foreach ($this->menus as $key => $menu)
        {
            $permiso_menu = $this->getPermisoTiposDocumentos($_SESSION['RCFactura']['empresa'], $_SESSION['SYSTEM_USUARIO_ID'], $menu['rc_tipo_documento']);
            /* echo '<pre>Permisos:';
              print_r($permiso_menu);
              echo '</pre>'; */
            if ($permiso_menu['sw_permiso'] == 1)
            {
                $this->salida .= "				<tr>\n";
                $this->salida .= "					<td class=\"modulo_list_oscuro\"label\" align=\"center\">\n";
                $this->salida .= "						<a href=\"" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarClientes', array("menu" => $menu)) . "\"><b>GENERAR " . strtoupper(trim($menu['descripcion'])) . "</b></a>\n";
                $this->salida .= "					</td>\n";
                $this->salida .= "				</tr>\n";
            }
        }

        $this->salida .= "				<tr>\n";
        $this->salida .= "					<td class=\"modulo_list_oscuro\"label\" align=\"center\">\n";
        $this->salida .= "						<a href=\"" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosCerrados') . "\"><b>BUSQUEDA DE DOCUMENTOS</b></a>\n";
        $this->salida .= "					</td>\n";
        $this->salida .= "				</tr>\n";

        /* echo '<pre>';
          print_r($_SESSION);
          echo '</pre>'; */

        $permiso_anulacion = $this->TraerPermisoAnulacionRC($_SESSION[RCFactura][empresa], $_SESSION[RCFactura][caja], $_SESSION[SYSTEM_USUARIO_ID]);

        if ($permiso_anulacion[sw_permiso] === '1')
        {
            $this->salida .= "				<tr>\n";
            $this->salida .= "                                  <td class=\"modulo_list_oscuro\"label\" align=\"center\">\n";
            $this->salida .= "                                      <a href=\"" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarOpcionesAnulacionDocumentos') . "\" ><b>ANULACIÓN DE DOCUMENTOS</b></a>\n";
            $this->salida .= "                                  </td>\n";
            $this->salida .= "				</tr>\n";
            
            
             $this->salida .= "				<tr>\n";
            $this->salida .= "                                  <td class=\"modulo_list_oscuro\"label\" align=\"center\">\n";
            $this->salida .= "                                      <a href=\"" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosSincronizados') . "\" ><b>SINCRONIZACION DE RECIBOS</b></a>\n";
            $this->salida .= "                                  </td>\n";
            $this->salida .= "				</tr>\n";
        }

        $this->salida .= "			</table>\n";
        $this->salida .= "		</td></tr>\n";
        $this->salida .= "	<tr>\n";
        $this->salida .= "		<td align=\"center\"><br>\n";
        $this->salida .= "			<form name=\"form\" action=\"" . $this->accion . "\" method=\"post\">\n";
        $this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
        $this->salida .= "			</form>\n";
        $this->salida .= "		</td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "</table>\n";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /*     * ***********************************************************************************
     * Funcion donde se realiza la forma donde se muestra el listado de clientes 
     * ************************************************************************************ */
    
    
    function formaMostrarRecibos($recibos){
        
       // echo print_r($recibos);
        $this->salida .= ThemeAbrirTabla("RECIBOS");

            $this->salida .= "<script language=\"javascript\">
                                              function  sincronizarRecibo(numero, prefijo){
                                                        if(confirm('Desa sincronizar el recibo ' +numero)){
                                                           xajax_sincronizar_recibos_pendientes_ws_fi(numero,prefijo);
                                                        }
                                              }
                                     </script>";
            
            $this->salida .= "<form action='".ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarRecibosSincronizados')."' method='post' ><table width=\"20%\" align=\"center\" >\n";
            $this->salida .= " <tr>    
                                             <td class=\"modulo_table_list_title\">
                                                    BUSCAR RECIBO
                                             </td>
                                            <td class=\"modulo_table_list_title\" style='text-align:left;'>
                                                <input type='text' value='' name='recibo' class='input-text'/>
                                                <input type='submit' value='BUSCAR' class='input-submit' />
                                             </td>
                        
                </tr>";
            $this->salida .= "</table></form>";
                
            $this->salida .= "	<table width=\"55%\" align=\"center\" >\n";
   
             $this->salida .="
                    <tr>
                            <td class=\"modulo_table_list_title\">PREFIJO</td>
                            <td class=\"modulo_table_list_title\">NUMERO</td>
                            <td class=\"modulo_table_list_title\">MENSAJE</td>
                            <td class=\"modulo_table_list_title\">ACCION</td>
                        
                    </tr>

            ";
             
            foreach($recibos as $recibo){
                
                $prefijo = trim($recibo['prefijo']);
                $numero = trim($recibo['numero_documento']);
                
                $this->salida .= "  <tr>\n";
                $this->salida .="   <td class=\"modulo_list_oscuro\"label\">{$prefijo}</td>";             
                 $this->salida .="   <td class=\"modulo_list_oscuro\"label\">{$numero}</td>";          
                 $this->salida .="   <td class=\"modulo_list_oscuro\"label\">{$recibo['mensaje']}</td>";       
                $this->salida .= "	<td class=\"modulo_list_oscuro\"label\" align=\"center\">\n";
                $this->salida .= "	     <a href='#' onclick=\"sincronizarRecibo('{$numero}', '{$prefijo}')\"><b>SINCRONIZAR " . strtoupper(trim($menu['descripcion'])) . "</b></a>\n";
                $this->salida .= "	</td>\n";
                $this->salida .= "  </tr>\n";
            }
            
            
        $this->salida .= "		</table>\n";
        
         $this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
        $this->salida .= "				<form name=\"volver\" action=\"" .  ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarMenuEmpresasRC'). "\" method=\"post\">\n";
        $this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "				</form>\n";
        $this->salida .= "			</td></tr>\n";
        $this->salida .= "		</table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    function FormaMostrarClientes()
    {
        $this->salida .= ThemeAbrirTabla("CONSULTAR CLIENTES");
        if (!empty($_SESSION['RCFactura']['empresa']))
        {
            $this->salida .= "		<script language=\"javascript\">\n";
            $this->salida .= "			function mOvr(src,clrOver)\n";
            $this->salida .= "			{\n";
            $this->salida .= "				src.style.background = clrOver;\n";
            $this->salida .= "			}\n";
            $this->salida .= "			function mOut(src,clrIn)\n";
            $this->salida .= "			{\n";
            $this->salida .= "				src.style.background = clrIn;\n";
            $this->salida .= "			}\n";
            $this->salida .= "		</script>\n";
            $this->salida .= "	<table width=\"55%\" align=\"center\" >\n";
            $this->salida .= "		<tr>\n";
            $this->salida .= "			<td align=\"center\">\n";
            $this->salida .= $this->BuscadorTerceros();
            $this->salida .= "			</td>\n";
            $this->salida .= "		</tr>\n";
            $this->salida .= "	</table><br>\n";

            $Clientes = $this->ObtenerDatosCliente();

            if (sizeof($Clientes) > 0)
            {
                $this->salida .= "	<table width=\"75%\" align=\"center\" class=\"modulo_table_list\">\n";
                $this->salida .= "			<tr class=\"modulo_table_list_title\" height=\"19\">\n";
                $this->salida .= "				<td width=\"22%\">DOCUMENTO</b></td>\n";
                $this->salida .= "				<td width=\"%\"><b>NOMBRE CLIENTE</b></td>\n";
                $this->salida .= "				<td width=\"20%\"><b>OPCIONES</b></td>\n";
                $this->salida .= "			</tr>";
                for ($i = 0; $i < sizeof($Clientes); $i++)
                {
                    if ($i % 2 == 0)
                    {
                        $estilo = 'modulo_list_oscuro';
                        $background = "#CCCCCC";
                    }
                    else
                    {
                        $estilo = 'modulo_list_claro';
                        $background = "#DDDDDD";
                    }

                    $Celdas = $Clientes[$i];
        
                    if($Celdas['tipo_bloqueo_id']==1){
                    $accion = ModuloGetURL('app', 'RecibosCaja', 'user', 'GenerarReciboCaja', array("pagina" => $this->paginaActual, "tercero_id" => $Celdas['tercero_id'],
                        "tercero_tipo" => $Celdas['tipo_id_tercero'],
                        "tercero_nombre" => $Celdas['nombre_tercero']));
                   
                    
                    $opcion = "	<a class=\"label_error\" href=\"" . $accion . "\" title=\"GENERAR RECIBO DE CAJA\">\n";
                    $opcion .= "	<img src=\"" . GetThemePath() . "/images/editar.png\" border=\"0\">";
                    $opcion .= "	<b>DOCUMENTO</b></a>\n";
                    }elseif($Celdas['tipo_bloqueo_id']==2){
                        $opcion="BLOQUEO";
                    }else{
                        $opcion="INACTIVO";
                    }
                    $this->salida .= "			<tr class=\"" . $estilo . "\" height=\"21\" onmouseout=mOut(this,\"" . $background . "\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
                    $this->salida .= "				<td align=\"left\"   >" . $Celdas['tipo_id_tercero'] . " " . $Celdas['tercero_id'] . "</td>\n";
                    $this->salida .= "				<td align=\"justify\">" . $Celdas['nombre_tercero'] . "</td>\n";
                    $this->salida .= "				<td align=\"center\" >" . $opcion . "</td>\n";
                    $this->salida .= "			</tr>\n";
                }
                $this->salida .= "	</table><br>\n";

                $Paginador = new ClaseHTML();
                $this->salida .= "		" . $Paginador->ObtenerPaginado($this->conteo, $this->paginaActual, $this->actionPg);
                $this->salida .= "		<br>\n";
            }
            else
            {
                $this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br><br>\n";
            }
        }
        else
        {
            $this->salida .= "<br><center><b class=\"label_error\">SU USUARIO NO TIENE PERMISOS SOBRE ESTA EMPRESA</b></center><br><br>\n";
        }
        $this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
        $this->salida .= "				<form name=\"volver\" action=\"" . $this->action . "\" method=\"post\">\n";
        $this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "				</form>\n";
        $this->salida .= "			</td></tr>\n";
        $this->salida .= "		</table>\n";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /*     * ***********************************************************************************
     * Funcion donde se realiza la forma de generar un recibo de caja y de ver los recibos 
     * en estado pendiente 
     * ************************************************************************************ */

    function FormaGenerarReciboCaja()
    {
        /* echo '<pre>REQUEST: ';
          print_r($_REQUEST);
          echo '</pre>';

          echo '<pre>SESSION: ';
          print_r($_SESSION);
          echo '</pre>'; */

        /* echo '<pre>1.==SESSION: ';
          print_r($this->menu);
          echo '</pre>';

          echo '<pre>2.==Formas Pago: ';
          print_r($this->FormaPago);
          echo '</pre>'; */

        $datos['tercero_id'] = $_REQUEST['tercero_id'];
        $datos['tipo_id_tercero'] = $_REQUEST['tercero_tipo'];
        $datos['tercero_nombre'] = $_REQUEST['tercero_nombre'];

        $this->salida .= ThemeAbrirTabla("CREAR " . strtoupper(trim($this->menu['descripcion'])) . "");
        $this->salida .= "<script>\n";
        $this->salida .= "	function acceptNum(evt)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		var nav4 = window.Event ? true : false;\n";
        $this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
        $this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
        $this->salida .= "	}\n";
        $this->salida .= "	function mOvr(src,clrOver)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		src.style.background = clrOver;\n";
        $this->salida .= "	}\n";
        $this->salida .= "	function mOut(src,clrIn)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		src.style.background = clrIn;\n";
        $this->salida .= "	}\n";
        $this->salida .= "	function BuscarTercero()\n";
        $this->salida .= "	{\n";
        $this->salida .= "		var url=\"" . $this->actionT . "\"\n";
        $this->salida .= "		window.open(url,'','width=750,height=650,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no');\n";
        $this->salida .= "	}\n";

        $this->salida .= "	function pasarValor(frm)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		frm.valor_rc.value = frm.valorsaldo.value\n";
        $this->salida .= "	}\n";

        $this->salida .= "	function EvaluarDatos(frm)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		if(frm.valor_rc.value == '')\n";
        $this->salida .= "		{\n";
        $this->salida .= "			document.getElementById('error').innerHTML= 'DEBE ESPECIFICARSE EL VALOR DEL DOCUMENTO';\n";
        $this->salida .= "			return;\n";
        $this->salida .= "		}\n";
        $this->salida .= "		if(frm.forma_pago.value == '0')\n";
        $this->salida .= "		{\n";
        $this->salida .= "			document.getElementById('error').innerHTML= 'DEBE ESPECIFICARSE LA FORMA DE PAGO';\n";
        $this->salida .= "			return;\n";
        $this->salida .= "		}\n";
        if ($this->menu['sw_cruce_endosos'] == '1')
        {
            $this->salida .= "		if(frm.tercero_id_endoso.value == '' && ";
            $this->salida .= "				frm.tipo_id_tercero_endoso.value == '')\n";
            $this->salida .= "		{\n";
            $this->salida .= "			document.getElementById('error').innerHTML= 'DEBE ESPECIFICARSE EL TERCERO AL CUAL SE LE HACE EL ENDOSO';\n";
            $this->salida .= "			return;\n";
            $this->salida .= "		}\n";
        }
        if ($this->menu['sw_cruzar_anticipos'] == '1')
        {
            $this->salida .= "		if(frm.valor_rc.value*1 > frm.valorsaldo.value*1 )\n";
            $this->salida .= "		{\n";
            $this->salida .= "			document.getElementById('error').innerHTML= 'EL VALOR DEL RECIBO NO DEBE SER MAYOR A $'+frm.valorsaldo.value;\n";
            $this->salida .= "			return;\n";
            $this->salida .= "		}\n";
        }
        if (/* $this->menu['rc_tipo_documento'] == 1 OR */ $this->menu['rc_tipo_documento'] == 8 OR $this->menu['rc_tipo_documento'] == 38 OR $this->menu['rc_tipo_documento'] == 18 OR $this->menu['rc_tipo_documento'] == 13 OR $this->menu['rc_tipo_documento'] == 33 OR $this->menu['rc_tipo_documento'] == 3 OR $this->menu['rc_tipo_documento'] == 23 OR $this->menu['rc_tipo_documento'] == 28 OR $this->menu['rc_tipo_documento'] == 37 OR $this->menu['rc_tipo_documento'] == 17 OR $this->menu['rc_tipo_documento'] == 12 OR $this->menu['rc_tipo_documento'] == 32 OR $this->menu['rc_tipo_documento'] == 2 OR $this->menu['rc_tipo_documento'] == 22 OR $this->menu['rc_tipo_documento'] == 7 OR $this->menu['rc_tipo_documento'] == 17 OR $this->menu['rc_tipo_documento'] == 27 OR $this->menu['rc_tipo_documento'] == 36 OR $this->menu['rc_tipo_documento'] == 16 OR $this->menu['rc_tipo_documento'] == 11 OR $this->menu['rc_tipo_documento'] == 31 OR $this->menu['rc_tipo_documento'] == 21 OR $this->menu['rc_tipo_documento'] == 6 OR $this->menu['rc_tipo_documento'] == 26)
        {
            $rc_traslado = "&rc_traslado=0";
        }
        else
        {
            $rc_traslado = "&rc_traslado=1";
        }

        //echo "<b>ES TRASLADO ?: </b>".$rc_traslado."<br><br>";
        $this->salida .= "		frm.action =\"" . $this->action2 . $rc_traslado . "\";\n";
        $this->salida .= "		frm.submit();\n";
        $this->salida .= "		document.generarRC.btn_crear_documento.disabled=true;\n";
        $this->salida .= "	}\n";

        //echo "CREAR DOCUMENTO: ".$this->action2;

        $this->salida .= "</script>\n";
        $this->salida .= "<table border=\"0\" width=\"68%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "	<tr>\n";
        $this->salida .= "		<td class=\"modulo_table_list_title\" width=\"10%\">\n";
        $this->salida .= "			ENTIDAD\n";
        $this->salida .= "		</td>\n";
        $this->salida .= "		<td>\n";
        $this->salida .= "			<b class=\"label_mark\">" . $this->Cliente[1] . "</b>\n";
        $this->salida .= "		</td>\n";
        $this->salida .= "		<td class=\"modulo_table_list_title\" width=\"10%\">NIT</td>\n";
        $this->salida .= "		<td >\n";
        $this->salida .= "			<b class=\"label_mark\">" . $this->Cliente[0] . "</b>\n";
        $this->salida .= "		</td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "</table><br>\n";
        $this->salida .= "<form name=\"generarRC\" action =\"javascript:EvaluarDatos(document.generarRC)\" method=\"post\" >\n";
        $this->salida .= "	<center><div id=\"error\" class=\"label_error\"></div></center>\n";
        $this->salida .= "	<table align=\"center\" width=\"60%\">\n";
        $this->salida .= "		" . $this->SetStyle($this->parametro) . "\n";
        $this->salida .= "	</table>\n";

        if ($this->Imprimir == 1)
        {
            $reporte = new GetReports();
            $mostrar = $reporte->GetJavaReport('app', 'RecibosCaja', 'reciboscaja', $this->Arreglo, array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $funcion = $reporte->GetJavaFunction();

            $this->salida .= "		" . $mostrar . "\n";
            $this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\" >\n";
            $this->salida .= "	<tr>\n";
            $this->salida .= "			<td align=\"center\">\n";
            $this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Imprimir Documento\" onclick=\"$funcion\">\n";
            $this->salida .= "			</td>\n";
            $this->salida .= "	</tr>\n";
            $this->salida .= "</table><br>\n";
        }

        $this->salida .= "	<table border=\"0\" width=\"70%\" align=\"center\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td>\n";
        $this->salida .= "				<fieldset><legend class=\"label\">CREAR " . strtoupper(trim($this->menu['descripcion'])) . ":</legend>\n";
        $this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "							<td width=\"35%\" align=\"left\">&nbsp;VALOR</td>\n";
        $this->salida .= "							<td class=\"modulo_list_claro\" align=\"left\">\n";
        if ($this->menu['sw_cruzar_anticipos'] == '1')
        {
            $this->salida .= "								<label class=\"normal_10AN\">SALDO DE ANTICIPOS: $  </label><label class=\"normal_10AN\" id=\"valores_anticipos\" >" . (($this->menu['sw_cruce_endosos'] == '0') ? FormatoValor($this->saldo_anticipo) : "&nbsp;&nbsp;&nbsp;&nbsp;") . "</label>\n";
            $this->salida .= "								<a href=\"javascript:pasarValor(document.generarRC)\" title=\"ASIGNAR VALOR SALDO\">\n";
            $this->salida .= "									<img src=\"" . GetThemePath() . "/images/siguiente.png\"  border=\"0\">\n";
            $this->salida .= "								</a>\n";
            $this->salida .= "								<input type=\"hidden\" name=\"valorsaldo\" value=\"" . (($this->menu['sw_cruce_endosos'] == '0') ? $this->saldo_anticipo : "") . "\">\n";
        }
        $this->salida .= "								<b>$</b> <input type=\"text\" name=\"valor_rc\" class=\"input-text\" size=\"20\" maxlength=\"14\" value=\"" . $this->ValorRC . "\" onKeypress=\"return acceptNum(event);\">\n";
        $this->salida .= "							</td>\n";
        $this->salida .= "						</tr>\n";
        if (/* $this->menu['rc_tipo_documento'] == 1 OR */ $this->menu['rc_tipo_documento'] == 8 OR $this->menu['rc_tipo_documento'] == 38 OR $this->menu['rc_tipo_documento'] == 18 OR $this->menu['rc_tipo_documento'] == 13 OR $this->menu['rc_tipo_documento'] == 33 OR $this->menu['rc_tipo_documento'] == 3 OR $this->menu['rc_tipo_documento'] == 23 OR $this->menu['rc_tipo_documento'] == 28 OR $this->menu['rc_tipo_documento'] == 37 OR $this->menu['rc_tipo_documento'] == 17 OR $this->menu['rc_tipo_documento'] == 12 OR $this->menu['rc_tipo_documento'] == 32 OR $this->menu['rc_tipo_documento'] == 2 OR $this->menu['rc_tipo_documento'] == 22 OR $this->menu['rc_tipo_documento'] == 7 OR $this->menu['rc_tipo_documento'] == 17 OR $this->menu['rc_tipo_documento'] == 27 OR $this->menu['rc_tipo_documento'] == 36 OR $this->menu['rc_tipo_documento'] == 16 OR $this->menu['rc_tipo_documento'] == 11 OR $this->menu['rc_tipo_documento'] == 31 OR $this->menu['rc_tipo_documento'] == 21 OR $this->menu['rc_tipo_documento'] == 6 OR $this->menu['rc_tipo_documento'] == 26)
        {
            $this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "							<td align=\"left\">&nbsp;FORMA DE PAGO:</td>\n";
            $this->salida .= "							<td class=\"modulo_list_claro\" align=\"left\">&nbsp;&nbsp;\n";
            $this->salida .= "								<select name=\"forma_pago\" class=\"select\">\n";

            switch ($this->FormaPago)
            {
                case 'CH': $ch = "selected";
                    break;
                case 'CO': $co = "selected";
                    break;
                case 'EF': $ef = "selected";
                    break;
                case 'TC': $tc = "selected";
                    break;
                case 'TD': $td = "selected";
                    break;
            }


            //$this->salida .= "									<option value='0'>-----SELECCIONAR-----</option>\n";
            //$this->salida .= "									<option value='CH' $ch>Cheque</option>\n";
           // $this->salida .= "									<option value='CO' $co>Consignacion</option>\n";
            $this->salida .= "									<option value='EF' $ef>Efectivo</option>\n";
           /* $this->salida .= "									<option value='TC' $tc>Tarjeta Credito</option>\n";
            $this->salida .= "									<option value='TD' $td>Tarjeta Debito</option>\n";*/
            $this->salida .= "								</select>\n";
            $this->salida .= "							</td>\n";
            $this->salida .= "						</tr>\n";

            /* $rcf = new app_RecibosCaja_Funciones();
              $forma_pago_cod = $rcf->getFormasDePago();

              $this->salida .= "		<option value='0'>-----SELECCIONAR-----</option>\n";
              for ($i = 0; $i < count($forma_pago_cod); $i++) {
              $this->salida .= "		<option value='" . $forma_pago_cod[$i]['id_forma_pago'] . "' $ch>" . $forma_pago_cod[$i]['descripcion'] . "</option>\n";
              }
              $this->salida .= "		</select>\n";
              $this->salida .= "	</td>\n";
              $this->salida .= "</tr>\n"; */
            
              $this->salida .= "<tr class=\"modulo_table_list_title\">
                                    <td align=\"left\">
                                    &nbsp;
                                        EMPRESA:
                                    </td>
                                    <td align=\"left\" class=\"modulo_list_claro\">&nbsp;&nbsp;
                                        <select name='empresa_recibo' class=\"select\">
                                            <option value='0'>DUANA</option>
                                            <option value='1'>COSMITET</option>
                                        </select>
                                    </td>
                  
                               </tr>";
        }
        else
        {
            $this->salida .= "		<input type=\"hidden\" name=\"forma_pago\" value=\"OT\">\n";
        }
        if ($this->menu['sw_cruce_endosos'] == '1')
        {
            $this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "							<td align=\"left\">\n";
            $this->salida .= "								&nbsp;TERCERO ENDOSO\n";
            $this->salida .= "								<input type=\"button\" class=\"input-submit\" name=\"terceroadd\" value=\"Adicionar\" onclick=\"BuscarTercero()\">\n";
            $this->salida .= "								<input type=\"hidden\" name=\"tercero_id_endoso\" value=\"\">\n";
            $this->salida .= "								<input type=\"hidden\" name=\"tipo_id_tercero_endoso\" value=\"\">\n";
            $this->salida .= "							</td>\n";
            $this->salida .= "							<td class=\"modulo_list_claro\" align=\"left\">\n";
            $this->salida .= "								<label id=\"nombre_tercero\" class=\"normal_10AN\"><label>\n";
            $this->salida .= "							</td>\n";

            $this->salida .= "						</tr>\n";
        }
        $this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "							<td colspan=\"2\">OBSERVACION</td>\n";
        $this->salida .= "						</tr>\n";
        $this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
        $this->salida .= "								<textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"observa\" >" . $this->Observacion . "</textarea>\n";
        $this->salida .= "							</td>\n";
        $this->salida .= "						</tr>\n";
        $this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "							<td class=\"modulo_list_claro\" colspan=\"2\" align=\"center\">\n";
        $this->salida .= "								<input type=\"submit\" class=\"input-submit\" name='btn_crear_documento' value=\"Crear Documento\" >\n";
        $this->salida .= "								<input type='hidden' name='hidden_btn' value='1' /> \n";
        $this->salida .= "							</td>\n";
        $this->salida .= "						</tr>\n";
        $this->salida .= "					</table>\n";
        $this->salida .= "				</fieldset>\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= "</form><br>\n";

        $rc_pendientes = $this->ObtenerRecibosPendientes();
        if (sizeof($rc_pendientes) > 0)
        {
            if ($_REQUEST['hidden_btn'] == 1)
            {
                $this->salida .= "<script>
                                                        document.generarRC.btn_crear_documento.disabled=true;
                                                  </script>";
            }
            else
            {
                $this->salida .= "<script>
                                                        document.generarRC.btn_crear_documento.disabled=false;
                                                  </script>";
            }

            $this->salida .= "	<table border=\"0\" width=\"100%\" align=\"center\">\n";
            $this->salida .= "		<tr>\n";
            $this->salida .= "			<td>\n";
            $this->salida .= "				<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">" . strtoupper(trim($this->menu['descripcion'])) . " PENDIENTES:</legend>\n";
            $this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "						<tr class=\"formulacion_table_list\">\n";
            $this->salida .= "							<td width=\"7%\" >N DOC</td>\n";
            $this->salida .= "							<td width=\"7%\" >REGISTRO</td>\n";
            $this->salida .= "							<td width=\"9%\">V. DOCUM.</td>\n";
            $this->salida .= "							<td width=\"9%\">DEBITOS</td>\n";
            $this->salida .= "							<td width=\"9%\">CREDITOS</td>\n";
            $this->salida .= "							<td width=\"\" colspan= \"12\">OPCIONES</td>\n";
            $this->salida .= "						</tr>\n";

            for ($i = 0; $i < sizeof($rc_pendientes); $i++)
            {

                $Celdas = $rc_pendientes[$i];

                if ($i % 2 == 0)
                {
                    $estilo = 'modulo_list_oscuro';
                    $background = "#CCCCCC";
                }
                else
                {
                    $estilo = 'modulo_list_claro';
                    $background = "#DDDDDD";
                }

                //**$valor_rc_traslado  = "&valor_rc_traslado=".$rc_pendientes[$i][total_abono];
                $valor_rc_traslado = "&valor_rc_traslado=" . $Celdas[total_abono];
                $valor_rc_debito = "&rc_debito=" . (($Celdas[debito] * 1) + ($Celdas[total_nota] * 1));
                $valor_rc_credito = "&rc_credito=" . $Celdas[credito];

                //echo $this->action3[$i]."<br>";
                //**$opcion0  = "	<a class=\"label_error\" href=\"".$this->action3[$i].$valor_rc_traslado.$rc_traslado."\" title=\"CRUZAR FACTURAS\">\n";
                $opcion0 = "	<a class=\"label_error\" href=\"" . $this->action3[$i] . $valor_rc_traslado . $valor_rc_debito . $valor_rc_credito . $rc_traslado . "\" title=\"CRUZAR FACTURAS\">\n";
                $opcion0 .= "		<img src=\"" . GetThemePath() . "/images/editar.png\" border=\"0\">";
                $opcion0 .= "	<b class=\"label-error\">ADIC</b></a>\n";

                $opcion1 = "	<a class=\"label_error\" href=\"" . $this->action5[$i] . $rc_traslado . "\" title=\"CERRAR " . strtoupper(trim($this->menu['descripcion'])) . "\">\n";
                $opcion1 .= "		<img src=\"" . GetThemePath() . "/images/editar.png\"  border=\"0\">";
                $opcion1 .= "	<b class=\"label-error\">CER</b></a>\n";

                $opcion2 = "	<a class=\"label_error\" href=\"" . $this->action4[$i] . "\" title=\"ADICIONAR CONCEPTOS\">\n";
                $opcion2 .= "		<img src=\"" . GetThemePath() . "/images/editar.png\"  border=\"0\">";
                $opcion2 .= "	<font class=\"label-error\">CONC</font></a>\n";

                $opcion3 = "	<a class=\"label_error\" href=\"" . $this->action7[$i] . "\" title=\"ELIMINAR FACTURAS DEL DOCUMENTO\">\n";
                $opcion3 .= "		<img src=\"" . GetThemePath() . "/images/error_digitacion.png\"  border=\"0\">";
                $opcion3 .= "	<font class=\"label-error\">FACT</font></a>\n";

                $opcion4 = "	<a class=\"label_error\" href=\"" . $this->action6[$i] . "\" title=\"ELIMINAR " . strtoupper(trim($this->menu['descripcion'])) . "\">\n";
                $opcion4 .= "		<img src=\"" . GetThemePath() . "/images/elimina.png\"  border=\"0\">";
                $opcion4 .= "	<font class=\"label-error\">ELIM</font></a>\n";

                $opcion5 = "	<a class=\"label_error\" href=\"" . $this->action8[$i] . "\" title=\"MODIFICAR INFORMACION\">\n";
                $opcion5 .= "		<img src=\"" . GetThemePath() . "/images/auditoria.png\"  border=\"0\">";
                $opcion5 .= "	<font class=\"label-error\">INFO</font></a>\n";

                $opcion6 = "	<a class=\"label_error\" href=\"" . $this->action9[$i] . "\" title=\"ADICIONAR NOTAS\">\n";
                $opcion6 .= "		<img src=\"" . GetThemePath() . "/images/pmodificar.png\"  border=\"0\">";
                $opcion6 .= "	<font class=\"label-error\">NOTA</font></a>\n";

                $opcion7 = "	<a href=\"" . $this->actionA[$i] . "\" target=\"lista\" onclick=\"window.open('" . $this->actionA[$i] . "','lista','toolbar=no,width=700,height=550,resizable=no,scrollbars=yes').focus(); return false;\" class=\"label_error\" title=\"CRUCE AUTOMATICO DE FACTURAS\">\n";
                $opcion7 .= "		<img src=\"" . GetThemePath() . "/images/pcopiar.png\"  border=\"0\">";
                $opcion7 .= "	<font class=\"label-error\">AUTO</font></a>\n";

                $datos[numero_documento] = $rc_pendientes[$i][tmp_recibo_id];
                $datos[fecha_creacion] = $rc_pendientes[$i][registro];
                $datos[usuario] = $_SESSION[SYSTEM_USUARIO_ID];
                $datos[valor_debito] = $rc_pendientes[$i]['debito'] + $rc_pendientes[$i]['total_nota'];
                $datos[valor_credito] = $rc_pendientes[$i]['credito'];
                $datos[valor_documento] = $rc_pendientes[$i][total_abono];
                $datos[empresa_id] = $_SESSION[RCFactura][empresa];
                /* echo '<pre>';
                  print_r($datos);
                  echo '</pre>'; */
                $reporte = new GetReports();
                $mostrar = $reporte->GetJavaReport('app', 'RecibosCaja', 'reciboscajatmp', $datos, array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                $funcion = "ReciboCaja$i" . $reporte->GetJavaFunction();
                $mostrar = str_replace("function W", "function ReciboCaja" . $i . "W", $mostrar);
                //$opcion8  = "	<a href=\"".$this->actionA[$i]."\" target=\"lista\" onclick=\"window.open('".$this->actionA[$i]."','lista','toolbar=no,width=700,height=550,resizable=no,scrollbars=yes').focus(); return false;\" class=\"label_error\" title=\"CRUCE AUTOMATICO DE FACTURAS\">\n";
                $opcion8 = $mostrar . " <a href='javascript:$funcion' class='label_error' title='IMPRESION TEMPORAL - REPORTE'>";
                $opcion8 .= "		<img src=\"" . GetThemePath() . "/images/imprimir.png\"  border=\"0\">";
                $opcion8 .= "	<font class=\"label-error\">TMP</font></a>\n";

                $opcion9 = "    <a href='" . $this->moddocumento[$i] . "' class='label_error' title='MODIFICAR VALOR DOCUMENTO'>";
                $opcion9 .= "       <img src=\"" . GetThemePath() . "/images/editar.png\"  border=\"0\" />";
                $opcion9 .= "       <font class=\"label-error\">V. DOC</font>\n";
                $opcion9 .= "   </a>\n";

                //*$Celdas = $rc_pendientes[$i];
				
				//if ($_SESSION['Documentos']['sw_cruce_recibos'] === '1') {
                    $opcion10 = "	<a class=\"label_error\" href=\"" . $this->action10[$i] . $valor_rc_traslado . $valor_rc_debito . $valor_rc_credito . $rc_traslado . "\" title=\"SUBIR ARCHIVO PLANO (CSV)\">\n";
                    $opcion10 .= "		<img src=\"" . GetThemePath() . "/images/tabla.png\" border=\"0\">";
                    $opcion10 .= "	<b class=\"label-error\">CSV</b></a>\n";
               /* } else {
                    $opcion10 = "<form autocomplete='off' name=\"importar_$i\" action=\"" . ModuloGetURL('app', 'RecibosCaja', 'user', 'validarArchivoCSV', array('request' => $_REQUEST)) . "\" enctype=\"multipart/form-data\" method=\"post\" onsubmit='return validate()'>
                                <td width=\"5%\" align=\"center\">
                                    <input id='archivo_$Celdas[tmp_recibo_id]' accept='.csv' name=\"importar[$Celdas[tmp_recibo_id]]\" type=\"file\" required/>
                                    <input type=hidden name='files[]' value='$Celdas[tmp_recibo_id]'>
                                    <td width=\"5%\" align=\"center\">
                                        <input type=\"submit\" class=\"input-submit\" value=\"IMPORTAR CSV\">
                                    </td>
                                </form>";
                }*/

                $this->salida .= "						<tr class=\"" . $estilo . "\" onmouseout=mOut(this,\"" . $background . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
                $this->salida .= "							<td align=\"right\" >" . $Celdas['tmp_recibo_id'] . "</td>\n";
                $this->salida .= "							<td align=\"center\">" . $Celdas['registro'] . "</td>\n";
                $this->salida .= "							<td align=\"right\" >" . formatoValor($Celdas['total_abono']) . "</td>\n";
                $this->salida .= "							<td align=\"right\" >" . formatoValor($Celdas['debito'] + $Celdas['total_nota']) . "</td>\n";
                $this->salida .= "							<td align=\"right\" >" . formatoValor($Celdas['credito']) . "</td>\n";
                $this->salida .= "							<td width=\"5%\" align=\"center\">$opcion5</td>\n";
                $this->salida .= "							<td width=\"5%\" align=\"center\">$opcion0</td>\n";
                $this->salida .= "							<td width=\"5%\" align=\"center\">$opcion2</td>\n";
                $this->salida .= "							<td width=\"5%\" align=\"center\">$opcion6</td>\n";
                $this->salida .= "							<td width=\"5%\" align=\"center\">$opcion3</td>\n";
                $this->salida .= "							<td width=\"5%\" align=\"center\">$opcion4</td>\n";
                $this->salida .= "							<td width=\"5%\" align=\"center\">$opcion1</td>\n";
                $this->salida .= "							<td width=\"5%\" align=\"center\">$opcion7</td>\n";
                $this->salida .= "							<td width=\"5%\" align=\"center\">$opcion9</td>\n";
                $this->salida .= "							<td width=\"5%\" align=\"center\">$opcion8</td>\n";
				$this->salida .= "							<td width=\"5%\" align=\"center\">$opcion10</td>";
                $this->salida .= "						</tr>\n";
            }
            $this->salida .= "					</table>\n";
            $this->salida .= "				</fieldset>\n";
            $this->salida .= "			</td>\n";
            $this->salida .= "		</tr>\n";
            $this->salida .= "	</table>\n";
        }

        $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
        $this->salida .= "		<tr><td align=\"center\">\n";
        $this->salida .= "			<form name=\"volver\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "			</form>\n";
        $this->salida .= "		</td></tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();

        return true;
    }

    /*     * ********************************************************************************
     * Funcion donde se muestra la forma donde se adicionan los conceptos a un RC 
     * ********************************************************************************* */

    function FormaAdicionarConceptos()
    {
        $this->salida .= ThemeAbrirTabla("DETALLE " . strtoupper(trim($this->menu['descripcion'])) . "");
        $this->salida .= "	<script>\n";
        $this->salida .= "		function acceptNum(evt)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			var nav4 = window.Event ? true : false;\n";
        $this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
        $this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function Cambiar(objeto)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			sw_centro = objeto.concepto.value.split(\"-\");\n";
        $this->salida .= "			if(sw_centro[2] == 1)\n";
        $this->salida .= "			{\n";
        $this->salida .= "				objeto.departamento.disabled = false;\n";
        $this->salida .= "			}\n";
        $this->salida .= "			else\n";
        $this->salida .= "				{\n";
        $this->salida .= "					objeto.departamento.selectedIndex = 0;\n";
        $this->salida .= "					objeto.departamento.disabled = true;\n";
        $this->salida .= "				}\n";
        $this->salida .= "		}\n";
        $this->salida .= "	</script>\n";
        $this->salida .= "	<table align=\"center\" width=\"60%\">\n";
        $this->salida .= "		" . $this->SetStyle($this->parametro) . "\n";
        $this->salida .= "	</table><br>\n";

        $conceptos = $this->ObtenerConceptosTesoreria();
			
        if (sizeof($conceptos) > 0)
        {
            $this->salida .= "<form name=\"conceptos\" action=\"" . $this->action2 . "\" method=\"post\">\n";
            $this->salida .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "			<td align=\"left\" width=\"10%\"><b>CONCEPTO</b></td>\n";
            $this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
            $this->salida .= "				<select name=\"concepto\" class=\"select\" onChange=\"Cambiar(document.conceptos)\">\n";
            $this->salida .= "					<option value='0'>-----SELECCIONAR-----</option>\n";
            for ($i = 0; $i < sizeof($conceptos); $i++)
            {
                $opciones = $conceptos[$i];
                ($this->Concepto == ($opciones['concepto_id'] . "-" . $opciones['sw_naturaleza'] . "-" . $opciones['sw_centro_costo'])) ? $sel = " selected " : $sel = "";

                $this->salida .= "					<option value='" . $opciones['concepto_id'] . "-" . $opciones['sw_naturaleza'] . "-" . $opciones['sw_centro_costo'] . "' $sel>" . ucwords(strtolower($opciones['descripcion'])) . "</option>\n";
            }
            $this->salida .= "				</select>\n";
            $this->salida .= "			</td>\n";
            $this->salida .= "			<td class=\"modulo_list_claro\" align=\"center\" width=\"18%\">\n";
            $this->salida .= "				<b>$</b><input type=\"text\" name=\"valor_concepto\" class=\"input-text\" size=\"15\" maxlength=\"15\" value=\"" . $this->ValorCRC . "\" onKeypress=\"return acceptNum(event);\">\n";
            $this->salida .= "			</td>\n";
            $this->salida .= "		</tr>\n";

            $Deptno = $this->ObtenerDepartamentos();
            if (sizeof($Deptno))
            {
                $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
                $this->salida .= "			<td align=\"left\" width=\"10%\"><b>DEPARTAMENTO</b></td>\n";
                $this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">\n";
                $this->salida .= "				<select name=\"departamento\" class=\"select\" disabled>\n";
                $this->salida .= "					<option value='0'>-------SELECCIONAR-------</option>\n";
                for ($i = 0; $i < sizeof($Deptno); $i++)
                {
                    ($this->Departamento == $Deptno[$i]['departamento']) ? $sel = " selected " : $sel = "";

                    $this->salida .= "					<option value='" . $Deptno[$i]['departamento'] . "' $sel>" . $Deptno[$i]['descripcion'] . "</option>\n";
                }
                $this->salida .= "				</select>\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "		</tr>\n";

                if ($this->Script == 1)
                {
                    $this->salida .= "<script>\n";
                    $this->salida .= "		document.conceptos.departamento.disabled = false;\n";
                    $this->salida .= "</script>\n";
                }
            }


            $this->salida .= "		<tr>\n";
            $this->salida .= "			<td class=\"modulo_list_claro\" colspan=\"3\" align=\"center\" width=\"15%\">\n";
            $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Agregar Concepto\">\n";
            $this->salida .= "			</td>\n";
            $this->salida .= "		</tr>\n";
            $this->salida .= "	</table>\n";
            $this->salida .= "</form><br>\n";
        }

        $valorRC = $this->ObtenerValorReciboCaja();
        $Debitos = $Creditos = 0;
        $Debitos += $valorRC[0];

        $this->salida .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "			<td width=\"40%\"><b>CONCEPTO</b></td>\n";
        $this->salida .= "			<td width=\"25%\"><b>DEPARTAMENTO</b></td>\n";
        $this->salida .= "			<td width=\"15%\"><b>DEBITO</b></td>\n";
        $this->salida .= "			<td width=\"15%\"><b>CREDITO</b></td>\n";
        $this->salida .= "			<td width=\"5%\"><b>X</b></td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td class=\"modulo_list_oscuro\" colspan=\"2\"><b>VALOR " . strtoupper(trim($this->menu['descripcion'])) . "</b></td>\n";
        $this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>" . formatoValor($valorRC[0]) . "</b></td>\n";
        $this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>0</b></td>\n";
        $this->salida .= "			<td class=\"modulo_list_claro\"></td>\n";
        $this->salida .= "		</tr>\n";

        if ($this->ValorEnNotas > 0)
        {
            $Debitos += $this->ValorEnNotas;
            $this->salida .= "		<tr>\n";
            $this->salida .= "			<td class=\"modulo_list_oscuro\" colspan=\"2\"><b>TOTAL EN NOTAS</b></td>\n";
            $this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>" . formatoValor($this->ValorEnNotas) . "</b></td>\n";
            $this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>0</b></td>\n";
            $this->salida .= "			<td class=\"modulo_list_claro\"></td>\n";
            $this->salida .= "		</tr>\n";
        }

        if ($valorRC[1] > 0)
        {
            $Creditos += $valorRC[1];
            $this->salida .= "		<tr>\n";
            $this->salida .= "			<td class=\"modulo_list_oscuro\" colspan=\"2\"><b>TOTAL FACTURAS</b></td>\n";
            $this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>0</b></td>\n";
            $this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>" . formatoValor($valorRC[1]) . "</b></td>\n";
            $this->salida .= "			<td class=\"modulo_list_claro\"></td>\n";
            $this->salida .= "		</tr>\n";
        }

        $ConceptosV = $this->ObtenerValorConceptos();
        if (sizeof($ConceptosV) > 0)
        {
            for ($i = 0; $i < sizeof($ConceptosV); $i++)
            {
                $Celdas = $ConceptosV[$i];

                switch ($Celdas['naturaleza'])
                {
                    case 'C':
                        $credito = formatoValor($Celdas['valor']);
                        $debito = "0";
                        $Creditos += $Celdas['valor'];
                        break;
                    case 'D':
                        $debito = formatoValor($Celdas['valor']);
                        $credito = "0";
                        $Debitos += $Celdas['valor'];
                        break;
                }

                $opcion = "	<a href=\"" . $this->actionX[$i] . "\" >\n";
                $opcion .= "		<img src=\"" . GetThemePath() . "/images/delete.gif\" title=\"ELIMINAR CARGO\" border=\"0\">";
                $opcion .= "	</a>\n";

                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_list_oscuro\"><b>" . $Celdas['descripcion'] . "</b></td>\n";
                $this->salida .= "			<td class=\"modulo_list_oscuro\"><b>" . $Celdas['departamento'] . "</b></td>\n";
                $this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>" . $debito . "</b></td>\n";
                $this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>" . $credito . "</b></td>\n";
                $this->salida .= "			<td class=\"modulo_list_claro\" align=\"center\">$opcion</td>\n";
                $this->salida .= "		</tr>\n";
            }
        }

        $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "			<td align=\"left\" colspan=\"2\"><b>TOTALES</b></td>\n";
        $this->salida .= "			<td align=\"right\"><b>" . formatoValor($Debitos) . "</b></td>\n";
        $this->salida .= "			<td align=\"right\"><b>" . formatoValor($Creditos) . "</b></td>\n";
        $this->salida .= "			<td align=\"center\"></td>\n";
        $this->salida .= "		</tr>\n";

        if ($Debitos > $Creditos)
        {
            $credito = $Debitos - $Creditos;
            if ($credito < 0)
                $credito = $credito * (-1);
            $debito = "0";
        }
        else
        {
            $debito = $Debitos - $Creditos;
            if ($debito < 0)
                $debito = $debito * (-1);
            $credito = "0";
        }

        $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "			<td align=\"left\" colspan=\"2\"><b>SALDO</b></td>\n";
        $this->salida .= "			<td align=\"right\"><b>" . formatoValor($debito) . "</b></td>\n";
        $this->salida .= "			<td align=\"right\"><b>" . formatoValor($credito) . "</b></td>\n";
        $this->salida .= "			<td ></td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table><br>\n";
        $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
        $this->salida .= "		<tr><td align=\"center\">\n";
        $this->salida .= "			<form name=\"volver\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "			</form>\n";
        $this->salida .= "		</td></tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    //--[RQ-10589]------------------------------------------------------------------------------
    function visualizaFormas()
    {
        $sw_cruce_recibos = $_SESSION[Documentos][sw_cruce_recibos];

        if (trim($sw_cruce_recibos) == 1)
        {
           // echo print_r($_SESSION[Documentos][sw_cruce_recibos]). " ===========";
            $this->FormaMostrarFacturas();
        }
        else
        {
            $this->FormaSinRecibos();
        }
    }
	
	    function visualizaFormasCSV($error = "", $success = "", $doc = "") {
		
		$sw_cruce_recibos = $_SESSION[Documentos][sw_cruce_recibos];

        if (trim($sw_cruce_recibos) == 1)
        {
           // echo print_r($_SESSION[Documentos][sw_cruce_recibos]). " ===========";
            $this->SeleccionarReciboCSV($error, $success, $doc);
        }
        else
        {
            $this->FormaSinRecibosCSV($error, $success, $doc);
        }
		
    }

    //--[fin RQ-10589]--------------------------------------------------------------------------


    /*     * ******************************************************************************** 
     * Funcion donde se realiza la forma donde se muestra la informacion de las factura
     * de un cliente  
     * ********************************************************************************* */
    function FormaMostrarFacturas()
    {
	
        //echo "======= Vista FormaMostrarFacturas ===========";


        /* echo "<pre> === request ===";
          print_r($_REQUEST);
          echo "</pre>"; */

        $solo_lectura = "";
        $tercero_tipo = $_REQUEST['tercero_tipo'];
        $tercero_id = $_REQUEST['tercero_id'];
        $rc_detalles = $this->ObtenerRcDetalles($tercero_tipo, $tercero_id);
        $cantidad_rc_detalles = count($rc_detalles);
        $total_rc_detalles = 0;
        for ($var = 0; $var < count($rc_detalles); $var++)
        {
            $total_rc_detalles+= $rc_detalles[$var]['valor_actual'];
        }

        if ($cantidad_rc_detalles < 1)
        {
            $solo_lectura = " readonly ";
        }
        else
        {
            $solo_lectura = " readonly ";
        }


        $this->salida .= ThemeAbrirTabla("DETALLE DEL DOCUMENTO");
        $this->salida .= "	<script>\n";
        $this->salida .= "		function acceptNum(evt)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			var nav4 = window.Event ? true : false;\n";
        $this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
        $this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function mOvr(src,clrOver)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			src.style.background = clrOver;\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function mOut(src,clrIn)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			src.style.background = clrIn;\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function pasarValor(objeto,i)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			try\n";
        $this->salida .= "			{\n";
        $this->salida .= "				objeto.valorfac[i].value = objeto.valorsaldo[i].value;\n";
        $this->salida .= "			}\n";
        $this->salida .= "			catch(error)\n";
        $this->salida .= "			{\n";
        $this->salida .= "				objeto.valorfac.value = objeto.valorsaldo.value;}\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function pasarTodos(objeto,i)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			for(j=0; j<i; j++)";
        $this->salida .= "			{\n";
        $this->salida .= "				objeto.valorfac[j].value = objeto.valorsaldo[j].value;\n";
        $this->salida .= "			}\n";
        $this->salida .= "		}\n";
        $this->salida .= "  function sumatoriaFacturasRC(e,valorfac)
                                            {
                                                var self = e.target || e.srcElement,
                                                    sum = 0;
                                                    inputs = self.parentNode.getElementsByTagName('input'),
                                                    valor_factura = document.getElementsByName(valorfac);
                                                for (var i=0, inp; inp = inputs[i]; i++)
                                                {
                                                    /*if(inp.value == '')
                                                    {
                                                        inp.value = 0;
                                                    }*/
                                                    sum += parseInt(inp.value);
                                                }
                                                if(parseInt(sum) < 1 || isNaN(sum))
                                                {
                                                    valor_factura[0].value = '';
                                                }
                                                else
                                                {
                                                    valor_factura[0].value = sum
                                                }
                                            }";
        $this->salida .= "  function sumarImpuestos(name_valor,indice_impuesto,name_valor_total_imp)
                                            {
                                                var valor = document.getElementsByName(name_valor),
                                                    imp_fuente = document.getElementsByName('retefuente'+indice_impuesto),
                                                    imp_cre = document.getElementsByName('retecre'+indice_impuesto),
                                                    imp_ica = document.getElementsByName('reteica'+indice_impuesto),
                                                    total_imp_valor = document.getElementsByName(name_valor_total_imp),
                                                    suma_impuestos = 0,
                                                    suma = 0,
                                                    valor_tmp = valor[0].value;
                                                    
                                                //SI EL CAMPO ESTA VACIO LE ASIGNA CERO -0-
                                                if(imp_fuente[0].value == '')
                                                {
                                                    imp_fuente[0].value = 0;
                                                }
                                                if(imp_cre[0].value == '')
                                                {
                                                    imp_cre[0].value = 0;
                                                }
                                                if(imp_ica[0].value == '')
                                                {
                                                    imp_ica[0].value = 0;
                                                }
                                                suma_impuestos = parseInt(imp_fuente[0].value) + parseInt(imp_cre[0].value) + parseInt(imp_ica[0].value);
                                                suma = parseInt(suma_impuestos)+parseInt(valor_tmp);
                                                total_imp_valor[0].value = suma;
                                            }
                                            ";
        //$this->salida .= "  var back_value = 0; ";
        $this->salida .= "  function sumatoriaExcedentes(e,name_rcs,name_rc_general,valor_rc_real,name_valor,indice_rc,indice)
                                            {
                                                //alert('name_rcs: '+name_rcs+'- name_rc_general:'+name_rc_general+'  - valor_rc_real: '+valor_rc_real+'  -  name_valor: '+name_valor+'  -  indice_rc: '+indice_rc+ ' - indice: '+indice);
                                                
                                                var self = e.target || e.srcElement,
                                                    inputs = self.parentNode.getElementsByTagName('input'),
                                                    name_rc = document.getElementsByName(name_rcs),
                                                    name_valor_sum = document.getElementsByName(name_valor),
                                                    rc_general = document.getElementById(name_rc_general),
                                                    excedente_rc = 0,
                                                    sumatoria = 0;
                                                    
                                                //alert(indice_rc+'----'+name_rc.length);

                                                //alert('inputs['+indice_rc+'].value: '+inputs[indice_rc].value);
                                                if(inputs[indice_rc].value == '')
                                                {
                                                    inputs[indice_rc].value = 0;
                                                    /*excedente_rc = parseInt(valor_rc_real) - parseInt(rc_general.value);
                                                    if(excedente_rc>=0)
                                                    {
                                                        rc_general.value = parseInt(rc_general.value) + parseInt(excedente_rc);
                                                    }*/
                                                }
                                                
                                                //alert('sumatoria de rc: '+sumatoria);
                                                //alert(inputs[indice_rc].value+'<='+valor_rc_real+'?')
                                                if(parseInt(inputs[indice_rc].value)<=parseInt(valor_rc_real))
                                                {
                                                    for(var i=parseInt(indice_rc);i<name_rc.length;i+=" . $cantidad_rc_detalles . ")
                                                    {   
                                                        if(parseInt(name_rc[i].value) > 0)
                                                        {
                                                            //alert(i+': '+name_rc[i].value);
                                                            sumatoria += parseInt(name_rc[i].value);
                                                        }
                                                    }
                                                    //alert('sumatoria de los rc: '+sumatoria);
                                                    //alert(sumatoria+'<='+valor_rc_real+'?');
                                                    if(parseInt(sumatoria)<=parseInt(valor_rc_real))
                                                    {
                                                        //ASIGNACION AL RC GENERAL DE EXCEDENTES
                                                        rc_general.value = parseInt(valor_rc_real)-parseInt(sumatoria);
                                                        //alert('   nuevo excedente rc_general:'+rc_general.value);
                                                    }
                                                    else
                                                    {
                                                        inputs[indice_rc].value = 0;
                                                    }
                                                }
                                                else
                                                {
                                                    inputs[indice_rc].value = 0;
                                                }
                                            }";

        $this->salida .= "  function duplicarValor(valor,nombre)
                                            {   
                                                var duplicador = document.getElementById(nombre);
                                                duplicador.value = valor;
                                            }";
        $this->salida .= "	</script>\n";

        $this->salida .= "	<table width=\"70%\" align=\"center\" >\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td>\n";
        $this->salida .= $this->BuscadorEnviosFacturas();
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table><br>\n";
		
        $saldo_anticipo_valor = $this->ObtnerSaldoAnticiposTercero($_SESSION['RCFactura']['empresa'], $_REQUEST['tercero_tipo'], $_REQUEST['tercero_id']);

        $this->salida .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td width='20%' class=\"modulo_table_list_title\">SALDO ANTICIPOS: </td>\n";
        $this->salida .= "			<td width='10%'> <b>$" . formatoValor($saldo_anticipo_valor['saldo']) . "</b></td>\n";
        $this->salida .= "			<td width='20%' class=\"modulo_table_list_title\">VALOR DOCUMENTO: </td>\n";
        $this->salida .= "			<td width='10%'> <b>$" . formatoValor($_REQUEST['valor_rc_traslado']) . "</b></td>\n";
        $this->salida .= "			<td width='25%' class=\"modulo_table_list_title\">VALOR TOTAL RECIBOS DE CAJA: </td>\n";
        $this->salida .= "			<td width='15%'> <b>$" . formatoValor($total_rc_detalles) . "</b></td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table><br>\n";

        if ($cantidad_rc_detalles < 1)
        {
            $this->salida .= "<p align='center' class='label_error'>LOS RECIBOS DE CAJA ESTAN CON SALDO EN 0 -CERO-..</p>";
        }
        else
        {
            $this->salida .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "			<td width='15%' colspan='3'> R E C I B O S &nbsp; D E &nbsp; C A J A </td>\n";
            $this->salida .= "		</tr>\n";
            $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                  <td>RECIBO CAJA</td>\n";
            $this->salida .= "                  <td>VALOR</td>\n";
            $this->salida .= "                  <td>EXCEDENTE</td>\n";
            $this->salida .= "		</tr>\n";
            for ($ste = 0; $ste < count($rc_detalles); $ste++)
            {
                if ($ste % 2 == 0)
                {
                    $estilo = 'modulo_list_oscuro';
                    $background = "#CCCCCC";
                }
                else
                {
                    $estilo = 'modulo_list_claro';
                    $background = "#DDDDDD";
                }
                $this->salida .= "		<tr class=\"" . $estilo . "\" onmouseout=mOut(this,\"" . $background . "\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
                $this->salida .= "                  <td width='20%' align='center'><b>" . $rc_detalles[$ste]['prefijo'] . " - " . $rc_detalles[$ste]['recibo_caja'] . "</b></td>\n";
                $this->salida .= "                  <td width='25%' align='center'><b>$" . formatoValor($rc_detalles[$ste]['valor_actual']) . "</b></td>\n";
                $this->salida .= "                  <td width='25%' align='center'><input class='input-text' type='text' name='rc_general' id='rc_" . $ste . "' value='" . (int) ($rc_detalles[$ste]['valor_actual']) . "' onChange='sumatoriaTotalExcedentes()' readonly/></td>\n";
                $this->salida .= "		</tr>\n";
            }
            $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                  <td align='right'><b>TOTAL: </b></td>\n";
            $this->salida .= "                  <td align='center'><b>$" . formatoValor($total_rc_detalles) . "</b></td>\n";
            $this->salida .= "                  <td align='center'><input type='text' class='input-text' name='rc_total' id='rc_total_excedente' value='" . $total_rc_detalles . "' /></td>\n";
            $this->salida .= "		</tr>\n";
            $this->salida .= "	</table><br>\n";
        }

        $Facturas = $this->ObtenerDatosFacturas();

        //echo "<hr>".$this->action3."<hr>";

        if (sizeof($Facturas) > 0)
        {
            $opcion = "";
            $es = " style=\"color:#FFFFFF;text-decoration: none;\" ";
            $this->salida .= "<form name=\"pagarfactura\" action=\"" . $this->action3 . "\" method=\"post\">\n";

            for ($r = 0; $r < count($rc_detalles); $r++)
            {
                //HIDDEN 
                $this->salida .= "<input type='hidden' name='rc_exceden_h_$r' value='" . (int) $rc_detalles[$r]['valor_actual'] . "' />";
                $this->salida .= "<input type='hidden' name='rc_name_h_$r' value='" . $rc_detalles[$r]['prefijo'] . "*" . $rc_detalles[$r]['recibo_caja'] . "' />";
            }
            $this->salida .= "<input type='hidden' name='cantidad_rc_h' value='" . $cantidad_rc_detalles . "' />";
            $this->salida .= "	<table align=\"center\" width=\"60%\">\n";
            $this->salida .= "		" . $this->SetStyle("MensajeError") . "\n";
            $this->salida .= "	</table>\n";
            $this->salida .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "				<td width=\"5%\"><b><a href=\"" . $this->actionH[0] . "\" $es>FACT.</a></b></td>\n";
            $this->salida .= "				<td width=\"5%\"><b><a href=\"" . $this->actionH[1] . "\" $es>FECHA</a></b></td>\n";
            $this->salida .= "				<td width=\"5%\"><b><a href=\"" . $this->actionH[2] . "\" $es>ENVIO</a></b></td>\n";
            $this->salida .= "				<td width=\"5%\" title=\"FECHA DE RADICACION\"><b><a href=\"" . $this->actionH[3] . "\" $es>F. RAD.</a></b></td>\n";
            $this->salida .= "				<td width=\"5%\"><b>GLOSA</b></td>\n";
            $this->salida .= "				<td width=\"5%\"><b>ACEPTADO</b></td>\n";
            $this->salida .= "				<td width=\"5%\"><b>ABONO</b></td>\n";
            $this->salida .= "				<td width=\"5%\"><b><a href=\"" . $this->actionH[4] . "\" $es>TOTAL</a></b></td>\n";
            $this->salida .= "				<td width=\"5%\">V RTF</td>\n";
            $this->salida .= "				<td width=\"5%\"><b><a href=\"" . $this->actionH[5] . "\" $es>SALDO</a></b></td>\n";
            //$this->salida .= "				<td width=\"8%\"><b>SUGERIDO</b></td>\n";
            $this->salida .= "				<td width=\"10%\">VALOR</td>\n";
            $this->salida .= "				<td width=\"15%\">RC</td>\n";
            $this->salida .= "				<td width=\"5%\">RETE<br>FUENTE</td>\n";
            $this->salida .= "				<td width=\"5%\">RETECRE</td>\n";
            $this->salida .= "				<td width=\"5%\">RETEICA</td>\n";
            $this->salida .= "				<td width=\"10%\">TOTAL<br>VALOR</td>\n";

            /* if(sizeof($Facturas) > 1)
              {
              $this->salida .= "				<td width=\"1%\" >\n";
              $this->salida .= "					<img src=\"".GetThemePath()."/images/siguiente.png\" onclick=\"pasarTodos(document.pagarfactura,".sizeof($Facturas).")\" title=\"ASIGNAR TODOS LOS VALORES\" border=\"0\" >";
              $this->salida .= "				</td>\n";
              $this->salida .= "				<td width=\"%\"></td>\n";
              }
              else
              {
              $this->salida .= "				<td colspan=\"2\"></td>\n";
              } */
            $this->salida .= "			</tr>";

            for ($i = 0; $i < sizeof($Facturas); $i++)
            {
                if ($i % 2 == 0)
                {
                    $estilo = 'modulo_list_oscuro';
                    $background = "#CCCCCC";
                }
                else
                {
                    $estilo = 'modulo_list_claro';
                    $background = "#DDDDDD";
                }

                $nuevoV = $Facturas[$i]['saldo'] - $Facturas[$i]['abono'];

                $this->parametros['parametro'] = $i;
//					$this->action2 = ModuloGetURL('app','RecibosCaja','user','ObtenerSqlFacturas',$arreglo);

                $this->salida .= "			<tr class=\"" . $estilo . "\" onmouseout=mOut(this,\"" . $background . "\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
                $this->salida .= "				<td align='center'>&nbsp;" . $Facturas[$i]['prefijo'] . " " . $Facturas[$i]['factura_fiscal'] . "</td>\n";
                $this->salida .= "				<td align=\"center\">" . $Facturas[$i]['fecha1'] . "</td>\n";
                $this->salida .= "				<td align=\"right\" >" . $Facturas[$i]['envio_id'] . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"center\">" . $Facturas[$i]['fecha2'] . "</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['valor_glosa']) . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['valor_aceptado']) . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['abono']) . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['total_factura']) . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['retencion_fuente']) . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['saldo'] - $Facturas[$i]['abono']) . "&nbsp;</td>\n";
                //$this->salida .= "				<td align=\"right\" >".formatoValor($nuevoV)."&nbsp;</td>\n";
                //$this->salida .= "				<td><img src=\"".GetThemePath()."/images/siguiente.png\" onclick=\"pasarValor(document.pagarfactura,".$i.")\" title=\"ASIGNAR VALOR SALDO\" border=\"0\"></td>\n";
                $this->salida .= "				<td>\n";
                $this->salida .= "					<input type=\"hidden\" id=\"valorsaldo\" name=\"valorsaldo$i\" value=\"" . $nuevoV . "\">\n";
                $this->salida .= "					<input type=\"hidden\" name=\"factura$i\" value=\"" . $Facturas[$i]['prefijo'] . "*" . $Facturas[$i]['factura_fiscal'] . "\">\n";
                //$this->salida .= "					<b>$</b><input type=\"text\" id=\"valorfac\" name=\"valorfac$i\" class=\"input-text\" style=\"width:90%\" onkeypress=\"return acceptNum(event)\" value=\"".$this->Valor[$i]."\" readonly \>\n";
                $this->salida .= "					<b>$</b><input type=\"text\" id=\"valorfac\" name=\"valorfac$i\" class=\"input-text\" style=\"width:90%\" value=\"" . $this->Valor[$i] . "\" $solo_lectura \>\n";
                //HIDDEN
                for ($s = 0; $s < $cantidad_rc_detalles; $s++)
                {
                    $factura_rc = $Facturas[$i]['prefijo'] . "*" . $Facturas[$i]['factura_fiscal'] . "*" . $rc_detalles[$s]['prefijo'] . "*" . $rc_detalles[$s]['recibo_caja'];
                    //HIDDEN
                    $this->salida .= "<input type='hidden' name='factura_rc[]'  value='" . $factura_rc . "' />";
                    $this->salida .= "<input type='hidden' name='factura_rc_valor_" . $s . "[]'  id='frv$s$i' value='0' />";
                }
                $this->salida .= "				</td>\n";
                $this->salida .= "				<td>\n";
                for ($j = 0; $j < $cantidad_rc_detalles; $j++)
                {
                    //$this->salida .= "  <input class='input-text' type='text' name='rc_ti_".$Facturas[$i]['prefijo']."_".$Facturas[$i]['factura_fiscal']."_$j' id='rc_ti_$j' value='0' onChange=\"sumatoriaExcedentes(),sumatoriaFactura(this.value,this.name,'valorfac$i'),valorExcedentes(this.value)\" /><br/>";
                    $this->salida .= "  <b>" . $rc_detalles[$j]['prefijo'] . "-" . $rc_detalles[$j]['recibo_caja'] . ": </b><input class='input-text' type='text' name='rc_ti[]' id='rc_id_$j' value='0' onkeypress=\"return acceptNum(event)\" onChange=\"sumatoriaExcedentes(event,this.name,'rc_$j'," . $rc_detalles[$j]['valor_actual'] . ",'valorfac$i','$j','$i'), sumatoriaFacturasRC(event,'valorfac$i'), duplicarValor(this.value,'frv$j$i'), sumarImpuestos('valorfac$i','$i','total_valor_impuestos$i')\" /><br/>";
                }
                $this->salida .= "                              </td>";

                //NUEVO IMPUESTOS
                $this->salida .= "                              <td><input type='text' name='retefuente$i' id='retefuente' class='input-text' style=\"width:100%\" onkeypress=\"return acceptNum(event)\" value='0' onChange=\"sumarImpuestos('valorfac$i','$i','total_valor_impuestos$i')\" /></td>";
                $this->salida .= "                              <td><input type='text' name='retecre$i' id='retecre' class='input-text' style=\"width:100%\" onkeypress=\"return acceptNum(event)\" value='0' onChange=\"sumarImpuestos('valorfac$i','$i','total_valor_impuestos$i')\" /></td>";
                $this->salida .= "                              <td><input type='text' name='reteica$i' id='reteica' class='input-text' style=\"width:100%\" onkeypress=\"return acceptNum(event)\" value='0' onChange=\"sumarImpuestos('valorfac$i','$i','total_valor_impuestos$i')\" /></td>";
                //INPUT TOTAL (SUMA DE VALO+IMPUESTOS)
                $this->salida .= "                              <td><input type='text' name='total_valor_impuestos$i' id='total_valor_impuestos' class='input-text' style=\"width:100%\" readonly /></td>";
                $this->salida .= "			</tr>";
            }

            $this->salida .= "	</table><br>\n";
            $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
            $this->salida .= "		<tr><td align=\"center\">\n";
            $this->salida .= "				<input type=\"hidden\" name=\"total\" value=\"$i\">\n";
            $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Pagar Factura\">\n";
            $this->salida .= "		</td></tr>\n";
            $this->salida .= "	</table><br>\n";
            /*
              echo 'REQUEST TRASLADO:    <pre>';
              print_r($_REQUEST);
              echo '</pre>';

              echo 'SESSION TRASLADO:    <pre>';
              print_r($_SESSION);
              echo '</pre>'; */

            $Paginador = new ClaseHTML();
            $this->salida .= "		" . $Paginador->ObtenerPaginado($this->conteo, $this->paginaActual, $this->actionPg);
            $this->salida .= "		<br>\n";
            $this->salida .= "</form>\n";
        }
        else
        {
            $this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br><br>\n";
        }

        $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
        $this->salida .= "		<tr><td align=\"center\">\n";
        $this->salida .= "			<form name=\"volver\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "			</form>\n";
        $this->salida .= "		</td></tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    /*     * ********************************************************************************
     * 
     * ******************************************************************************** */

    //--[RQ-10589]------------------------------------------------------------------------------

    function FormaSinRecibos()
    {

        /* echo "<pre>=====2.REQUEST================";
          print_r($_REQUEST);
          echo "=====================</pre>"; */

        // echo "======= Vista FormaSinRecibos ===========";

        if (trim($_REQUEST[recibo_caja]) != '')
        {
            $_SESSION[RC][recibo_caja] = $_REQUEST[recibo_caja];
        }

        if (trim($_REQUEST[valor_rc_traslado]) != '')
        {
            $_SESSION[RC][valor_rc_traslado] = $_REQUEST[valor_rc_traslado];
        }

        if (trim($_REQUEST[rc_debito]) != '')
        {
            $_SESSION[RC][rc_debito] = $_REQUEST[rc_debito];
        }

        if (trim($_REQUEST[rc_credito]) != '')
        {
            $_SESSION[RC][rc_credito] = $_REQUEST[rc_credito];
        }

        $recibo_caja = $_SESSION[RC][recibo_caja];
        $valor_rc_traslado = $_SESSION[RC][valor_rc_traslado];
        $rc_debito = $_SESSION[RC][rc_debito];
        $rc_credito = $_SESSION[RC][rc_credito];

        //-----------------------------------------------------------
        //***$valor_a_cuadrar = $rc_debito - $rc_credito;
        //HACER CONSULTA DE ABONOS PARA CALCULAR EL VALOR DEL EXCEDENTE (VLR A CUADRAR)
        $suma_abonos = $this->ObtenerAbonos($recibo_caja);
        $valor_a_cuadrar = ($valor_rc_traslado * 1) - ($suma_abonos * 1);
        //-----------------------------------------------------------

        $solo_lectura = "";
        $tercero_tipo = $_REQUEST['tercero_tipo'];
        $tercero_id = $_REQUEST['tercero_id'];
        $rc_detalles = $this->ObtenerRcDetalles($tercero_tipo, $tercero_id);
        $cantidad_rc_detalles = count($rc_detalles);
        $total_rc_detalles = 0;
        for ($var = 0; $var < count($rc_detalles); $var++)
        {
            $total_rc_detalles+= $rc_detalles[$var]['valor_actual'];
        }

        /* if($cantidad_rc_detalles<1)
          {
          $solo_lectura = " readonly ";
          }
          else
          {
          $solo_lectura = " readonly ";
          } */

        //echo "<h3>CANTIDAD RC ASOCIADOS: ".$cantidad_rc_detalles."</h3> <br>";
        //echo "submit: this->action3: ".$this->action3."<br><br>";


        $this->salida .= ThemeAbrirTabla("DETALLE DEL DOCUMENTO");
        $this->salida .= "	<script>\n";
        $this->salida .= "		function acceptNum(evt)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			var nav4 = window.Event ? true : false;\n";
        $this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
        $this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function mOvr(src,clrOver)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			src.style.background = clrOver;\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function mOut(src,clrIn)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			src.style.background = clrIn;\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function pasarValor(objeto,i)\n";
        $this->salida .= "		{\n";
        //$this->salida .= "                      alert('i:'+i+' :: valorsaldo:'+objeto.valorsaldo[i].value);\n";
        $this->salida .= "			try\n";
        $this->salida .= "			{\n";
        //$this->salida .= "				objeto.valorfac[i].value = objeto.valorsaldo[i].value;\n";
        $this->salida .= "                              document.getElementById('valorfac'+i).value = objeto.valorsaldo[i].value;\n";
        $this->salida .= "			}\n";
        $this->salida .= "			catch(error)\n";
        $this->salida .= "			{\n";
        $this->salida .= "                              var valorfac = objeto.valorfac;
                                                        if(!valorfac){ valorfac =  document.getElementById('valorfac'+i) }    ";
        $this->salida .= "				valorfac.value = objeto.valorsaldo.value;\n";
        $this->salida .= "                      }\n";
        $this->salida .= "                      sumaFacturas();\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function pasarTodos(objeto,i)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			for(j=0; j<i; j++)";
        $this->salida .= "			{\n";
        $this->salida .= "				objeto.valorfac[j].value = objeto.valorsaldo[j].value;\n";
        $this->salida .= "			}\n";
        $this->salida .= "		}\n";
        $this->salida .= "  function sumatoriaFacturasRC(e,valorfac)
                                            {
                                                var self = e.target || e.srcElement,
                                                    sum = 0;
                                                    inputs = self.parentNode.getElementsByTagName('input'),
                                                    valor_factura = document.getElementsByName(valorfac);
                                                for (var i=0, inp; inp = inputs[i]; i++)
                                                {
                                                    /*if(inp.value == '')
                                                    {
                                                        inp.value = 0;
                                                    }*/
                                                    sum += parseInt(inp.value);
                                                }
                                                if(parseInt(sum) < 1 || isNaN(sum))
                                                {
                                                    valor_factura[0].value = '';
                                                }
                                                else
                                                {
                                                    valor_factura[0].value = sum
                                                }
                                            }";
        $this->salida .= "  function sumarImpuestos(name_valor,indice_impuesto,name_valor_total_imp)
                                            {
                                                var valor = document.getElementsByName(name_valor),
                                                    imp_fuente = document.getElementsByName('retefuente'+indice_impuesto),
                                                    imp_cre = document.getElementsByName('retecre'+indice_impuesto),
                                                    imp_ica = document.getElementsByName('reteica'+indice_impuesto),
                                                    total_imp_valor = document.getElementsByName(name_valor_total_imp),
                                                    suma_impuestos = 0,
                                                    suma = 0,
                                                    valor_tmp = valor[0].value;

                                                //SI EL CAMPO ESTA VACIO LE ASIGNA CERO -0-
                                                if(imp_fuente[0].value == '')
                                                {
                                                    imp_fuente[0].value = 0;
                                                }
                                                if(imp_cre[0].value == '')
                                                {
                                                    imp_cre[0].value = 0;
                                                }
                                                if(imp_ica[0].value == '')
                                                {
                                                    imp_ica[0].value = 0;
                                                }
                                                suma_impuestos = parseInt(imp_fuente[0].value) + parseInt(imp_cre[0].value) + parseInt(imp_ica[0].value);
                                                suma = parseInt(suma_impuestos)+parseInt(valor_tmp);
                                                total_imp_valor[0].value = suma;
                                            }
                                            ";
        //$this->salida .= "  var back_value = 0; ";
        $this->salida .= "  function sumatoriaExcedentes(e,name_rcs,name_rc_general,valor_rc_real,name_valor,indice_rc,indice)
                                            {
                                                //alert('name_rcs: '+name_rcs+'- name_rc_general:'+name_rc_general+'  - valor_rc_real: '+valor_rc_real+'  -  name_valor: '+name_valor+'  -  indice_rc: '+indice_rc+ ' - indice: '+indice);

                                                var self = e.target || e.srcElement,
                                                    inputs = self.parentNode.getElementsByTagName('input'),
                                                    name_rc = document.getElementsByName(name_rcs),
                                                    name_valor_sum = document.getElementsByName(name_valor),
                                                    rc_general = document.getElementById(name_rc_general),
                                                    excedente_rc = 0,
                                                    sumatoria = 0;

                                                //alert(indice_rc+'----'+name_rc.length);

                                                //alert('inputs['+indice_rc+'].value: '+inputs[indice_rc].value);
                                                if(inputs[indice_rc].value == '')
                                                {
                                                    inputs[indice_rc].value = 0;
                                                    /*excedente_rc = parseInt(valor_rc_real) - parseInt(rc_general.value);
                                                    if(excedente_rc>=0)
                                                    {
                                                        rc_general.value = parseInt(rc_general.value) + parseInt(excedente_rc);
                                                    }*/
                                                }

                                                //alert('sumatoria de rc: '+sumatoria);
                                                //alert(inputs[indice_rc].value+'<='+valor_rc_real+'?')
                                                if(parseInt(inputs[indice_rc].value)<=parseInt(valor_rc_real))
                                                {
                                                    for(var i=parseInt(indice_rc);i<name_rc.length;i+=" . $cantidad_rc_detalles . ")
                                                    {
                                                        if(parseInt(name_rc[i].value) > 0)
                                                        {
                                                            //alert(i+': '+name_rc[i].value);
                                                            sumatoria += parseInt(name_rc[i].value);
                                                        }
                                                    }
                                                    //alert('sumatoria de los rc: '+sumatoria);
                                                    //alert(sumatoria+'<='+valor_rc_real+'?');
                                                    if(parseInt(sumatoria)<=parseInt(valor_rc_real))
                                                    {
                                                        //ASIGNACION AL RC GENERAL DE EXCEDENTES
                                                        rc_general.value = parseInt(valor_rc_real)-parseInt(sumatoria);
                                                        //alert('   nuevo excedente rc_general:'+rc_general.value);
                                                    }
                                                    else
                                                    {
                                                        inputs[indice_rc].value = 0;
                                                    }
                                                }
                                                else
                                                {
                                                    inputs[indice_rc].value = 0;
                                                }
                                            }";

        $this->salida .= "  function duplicarValor(valor,nombre)
                                            {
                                                var duplicador = document.getElementById(nombre);
                                                duplicador.value = valor;
                                            }";

        $this->salida .= "  function validaSaldo(objSaldo,saldo)
                                            {
                                                if(objSaldo.value > saldo){
                                                    alert('El valor ingresado es mayor al valor del saldo de la Factura');
                                                    objSaldo.focus();
                                                }
                                                else{
                                                    //alert('no');
                                                    var totalFacturas = document.getElementById('total').value
                                                    var sumaFacturas = document.getElementById('sumaFacturas');
                                                    var sumaFac = 0;
                                                    //alert('totalFacturas:'+totalFacturas);
                                                    for(i=0;i<totalFacturas;i++){
                                                        sumaFac = (sumaFac*1) + (document.getElementById('valorfac'+i).value*1);
                                                    }
                                                    //alert('sumaFac:'+sumaFac);
                                                    sumaFacturas.value = sumaFac;
                                                }
                                            }
                                            ";

        $this->salida .= "  function sumaFacturas()
                                            {
                                                var totalFacturas = document.getElementById('total').value
                                                var sumaFacturas = document.getElementById('sumaFacturas');
                                                var sumaFac = 0;
                                                //alert('totalFacturas:'+totalFacturas);
                                                for(i=0;i<totalFacturas;i++){
                                                    sumaFac = (sumaFac*1) + (document.getElementById('valorfac'+i).value*1);
                                                }
                                                //alert('sumaFac:'+sumaFac);
                                                sumaFacturas.value = sumaFac;
                                            }";

        $this->salida .= "  function pagarFactura()
                                            {
                                                var valor_a_cuadrar = document.getElementById('valor_a_cuadrar').value
                                                var totalFacturas = document.getElementById('total').value
                                                var sumaFacturas = 0;
                                                for(i=0;i<totalFacturas;i++){
                                                    //document.pagarfactura.valorfac
                                                    //alert('valorfac['+i+']:'+document.getElementById('valorfac'+i).value);
                                                    sumaFacturas = (sumaFacturas*1) + (document.getElementById('valorfac'+i).value*1);
                                                    //alert('sumaFacturas:'+sumaFacturas);
                                                }

                                                //alert('sumaFacturas:'+sumaFacturas+' :: valor_a_cuadrar:'+valor_a_cuadrar);

                                                if(sumaFacturas>valor_a_cuadrar){
                                                    alert('La suma de valores ingresados es mayor al valor del excedente');
                                                }
                                                else{
                                                    document.pagarfactura.submit();
                                                }

                                            }
                                            ";


        $this->salida .= "	</script>\n";

        $this->salida .= "	<table width=\"70%\" align=\"center\" >\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td>\n";
        $this->salida .= $this->BuscadorEnviosFacturas();
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table><br>\n";

        $saldo_anticipo_valor = $this->ObtnerSaldoAnticiposTercero($_SESSION['RCFactura']['empresa'], $_REQUEST['tercero_tipo'], $_REQUEST['tercero_id']);
        //**$saldo_anticipo_valor = $this->ObtnerSaldoAnticiposTercero();

        $this->salida .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td width='15%' class=\"modulo_table_list_title\">SALDO ANTICIPOS: </td>\n";
        $this->salida .= "			<td width='10%'> <b>$" . formatoValor($saldo_anticipo_valor['saldo']) . "</b></td>\n";
        $this->salida .= "			<td width='15%' class=\"modulo_table_list_title\">VLR DOCUMENTO: </td>\n";
        //*$this->salida .= "			<td width='10%'> <b>$".formatoValor($_REQUEST['valor_rc_traslado'])."</b>";
        $this->salida .= "			<td width='10%'> <b>$" . formatoValor($valor_rc_traslado) . "</b>";
        $this->salida .= "                          <input type='hidden' name='valor_rc_traslado' id='valor_rc_traslado' value='" . $valor_rc_traslado . "'>\n";
        $this->salida .= "			</td>\n";

        $this->salida .= "			<td width='15%' class=\"modulo_table_list_title\">EXCEDENTE: </td>\n";
        $this->salida .= "			<td width='10%'> <b>$" . formatoValor($valor_a_cuadrar) . "</b>";
        $this->salida .= "                          <input type='hidden' name='valor_a_cuadrar' id='valor_a_cuadrar' value='" . $valor_a_cuadrar . "'>\n";
        $this->salida .= "			</td>\n";

        $this->salida .= "			<td width='20%' class=\"modulo_table_list_title\">VLR TOT RECIBOS DE CAJA: </td>\n";
        $this->salida .= "			<td width='10%'> <b>$" . formatoValor($total_rc_detalles) . "</b></td>\n";

        $this->salida .= "			<td width='20%' class=\"modulo_table_list_title\">SUMA TOTAL FACTURAS: </td>\n";
        $this->salida .= "			<td width='10%' nowrap> $ <input type='text' name='sumaFacturas' id='sumaFacturas' value='' style='text-align:right;' readonly></td>\n";

        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table><br>\n";

        if ($cantidad_rc_detalles < 1)
        {
            $this->salida .= "<p align='center' class='label_error'>LOS RECIBOS DE CAJA ESTAN CON SALDO EN 0 -CERO-.</p>";
        } 

        $Facturas = $this->ObtenerDatosFacturas();

        /* echo "<pre>====== 6.FACTURAS=========";
          var_dump($Facturas);
          echo "===============</pre>"; */


        if (sizeof($Facturas) > 0)
        {
            $opcion = "";
            $es = " style=\"color:#FFFFFF;text-decoration: none;\" ";
            $this->salida .= "<form name=\"pagarfactura\" action=\"" . $this->action3 . "\" method=\"post\">\n";

            for ($r = 0; $r < count($rc_detalles); $r++)
            {
                //HIDDEN
                $this->salida .= "<input type='hidden' name='rc_exceden_h_$r' value='" . (int) $rc_detalles[$r]['valor_actual'] . "' />";
                $this->salida .= "<input type='hidden' name='rc_name_h_$r' value='" . $rc_detalles[$r]['prefijo'] . "*" . $rc_detalles[$r]['recibo_caja'] . "' />";
            }
            $this->salida .= "<input type='hidden' name='cantidad_rc_h' value='" . $cantidad_rc_detalles . "' />";
            $this->salida .= "	<table align=\"center\" width=\"60%\">\n";
            $this->salida .= "		" . $this->SetStyle("MensajeError") . "\n";
            $this->salida .= "	</table>\n";
            $this->salida .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "				<td width=\"8%\"><b><a href=\"" . $this->actionH[0] . "\" $es>FACT.</a></b></td>\n";
            $this->salida .= "				<td width=\"8%\"><b><a href=\"" . $this->actionH[1] . "\" $es>FECHA</a></b></td>\n";
            $this->salida .= "				<td width=\"8%\"><b><a href=\"" . $this->actionH[2] . "\" $es>ENVIO</a></b></td>\n";
            $this->salida .= "				<td width=\"8%\" title=\"FECHA DE RADICACION\"><b><a href=\"" . $this->actionH[3] . "\" $es>F. RAD.</a></b></td>\n";
            $this->salida .= "				<td width=\"9%\"><b>GLOSA</b></td>\n";
            $this->salida .= "				<td width=\"8%\"><b>ACEPTADO</b></td>\n";
            $this->salida .= "				<td width=\"9%\"><b>ABONO</b></td>\n";
            $this->salida .= "				<td width=\"9%\"><b><a href=\"" . $this->actionH[4] . "\" $es>TOTAL</a></b></td>\n";
            $this->salida .= "				<td width=\"9%\">V RTF</td>\n";
            $this->salida .= "				<td width=\"9%\"><b><a href=\"" . $this->actionH[5] . "\" $es>SALDO</a></b></td>\n";
            $this->salida .= "				<td width=\"2%\"><b></b></td>\n";
            $this->salida .= "				<td width=\"11%\">VALOR</td>\n";

            /* --[RQ-10589]---------------------------------------------------------------------------------
              $this->salida .= "				<td width=\"15%\">RC</td>\n";
              $this->salida .= "				<td width=\"5%\">RETE<br>FUENTE</td>\n";
              $this->salida .= "				<td width=\"5%\">RETECRE</td>\n";
              $this->salida .= "				<td width=\"5%\">RETEICA</td>\n";
              $this->salida .= "				<td width=\"10%\">TOTAL<br>VALOR</td>\n";
              ---------------------------------------------------------------------------------------------- */

            $this->salida .= "			</tr>";

            for ($i = 0; $i < sizeof($Facturas); $i++)
            {
                if ($i % 2 == 0)
                {
                    $estilo = 'modulo_list_oscuro';
                    $background = "#CCCCCC";
                }
                else
                {
                    $estilo = 'modulo_list_claro';
                    $background = "#DDDDDD";
                }

                $nuevoV = $Facturas[$i]['saldo'] - $Facturas[$i]['abono'];

                $this->parametros['parametro'] = $i;
//					$this->action2 = ModuloGetURL('app','RecibosCaja','user','ObtenerSqlFacturas',$arreglo);

                $this->salida .= "			<tr class=\"" . $estilo . "\" onmouseout=mOut(this,\"" . $background . "\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
                $this->salida .= "				<td align='center'>&nbsp;" . $Facturas[$i]['prefijo'] . " " . $Facturas[$i]['factura_fiscal'] . "</td>\n";
                $this->salida .= "				<td align=\"center\">" . $Facturas[$i]['fecha1'] . "</td>\n";
                $this->salida .= "				<td align=\"right\" >" . $Facturas[$i]['envio_id'] . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"center\">" . $Facturas[$i]['fecha2'] . "</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['valor_glosa']) . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['valor_aceptado']) . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['abono']) . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['total_factura'],2) . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['retencion_fuente']) . "&nbsp;</td>\n";
                $saldo = ($Facturas[$i]['saldo'] - $Facturas[$i]['abono']);
                $this->salida .= "				<td align=\"right\" >" . formatoValor($saldo, 2) . "&nbsp;";
                $this->salida .= "                                  <input type='hidden' name='saldo' id='saldo' value='" . $saldo . "'>";
                $this->salida .= "				</td>\n";

                $this->salida .= "				<td align=\"center\"><img src=\"" . GetThemePath() . "/images/siguiente.png\" onclick=\"pasarValor(document.pagarfactura," . $i . ")\" title=\"ASIGNAR VALOR SALDO\" border=\"0\"></td>\n";

                //$this->salida .= "				<td align=\"right\" >".formatoValor($nuevoV)."&nbsp;</td>\n";
                //$this->salida .= "				<td><img src=\"".GetThemePath()."/images/siguiente.png\" onclick=\"pasarValor(document.pagarfactura,".$i.")\" title=\"ASIGNAR VALOR SALDO\" border=\"0\"></td>\n";
                $this->salida .= "				<td align=\"right\" >\n";
                $this->salida .= "					<input type=\"hidden\" id=\"valorsaldo\" name=\"valorsaldo$i\" value=\"" . $nuevoV . "\">\n";
                $this->salida .= "					<input type=\"hidden\" name=\"factura$i\" value=\"" . $Facturas[$i]['prefijo'] . "*" . $Facturas[$i]['factura_fiscal'] . "\">\n";
                //$this->salida .= "					<b>$</b><input type=\"text\" id=\"valorfac\" name=\"valorfac$i\" class=\"input-text\" style=\"width:90%\" onkeypress=\"return acceptNum(event)\" value=\"".$this->Valor[$i]."\" readonly \>\n";
                $this->salida .= "					<b>$ </b><input type=\"text\" id=\"valorfac$i\" name=\"valorfac$i\" value=\"" . $this->Valor[$i] . "\" class=\"input-text\" style='text-align:right;' size=\"20\" maxlength=\"15\" onkeypress=\"return acceptNum(event)\" onblur=\"validaSaldo(this,$saldo)\" \>\n";
                //HIDDEN
                for ($s = 0; $s < $cantidad_rc_detalles; $s++)
                {
                    $factura_rc = $Facturas[$i]['prefijo'] . "*" . $Facturas[$i]['factura_fiscal'] . "*" . $rc_detalles[$s]['prefijo'] . "*" . $rc_detalles[$s]['recibo_caja'];
                    //HIDDEN
                    $this->salida .= "<input type='hidden' name='factura_rc[]'  value='" . $factura_rc . "' />";
                    $this->salida .= "<input type='hidden' name='factura_rc_valor_" . $s . "[]'  id='frv$s$i' value='0' />";
                }
                $this->salida .= "				</td>\n";

                $this->salida .= "			</tr>";
            }

            $this->salida .= "	</table><br>\n";
            $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
            $this->salida .= "		<tr><td align=\"center\">\n";
            $this->salida .= "				<input type=\"hidden\" name=\"total\" id=\"total\" value=\"$i\">\n";
            //**$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Pagar Factura\">\n";
            $this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Pagar Factura\" onclick=\"pagarFactura()\">\n";
            $this->salida .= "		</td></tr>\n";
            $this->salida .= "	</table><br>\n";
            /*
              echo 'REQUEST TRASLADO:    <pre>';
              print_r($_REQUEST);
              echo '</pre>';

              echo 'SESSION TRASLADO:    <pre>';
              print_r($_SESSION);
              echo '</pre>'; */

            $Paginador = new ClaseHTML();
            $this->salida .= "		" . $Paginador->ObtenerPaginado($this->conteo, $this->paginaActual, $this->actionPg);
            $this->salida .= "		<br>\n";
            $this->salida .= "</form>\n";
        }
        else
        {
            $this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br><br>\n";
        }

        $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
        $this->salida .= "		<tr><td align=\"center\">\n";
        $this->salida .= "			<form name=\"volver\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "			</form>\n";
        $this->salida .= "		</td></tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    //--[fin RQ-10589]--------------------------------------------------------------------------

    /*     * ****************************************************************************** */

    function FormaRealizarPago()
    {
        $this->salida .= ThemeAbrirTabla("DETALLE DEL DOCUMENTO-2");
        $this->salida .= "	<table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td class=\"modulo_table_list_title\" width=\"10%\">\n";
        $this->salida .= "				ENTIDAD\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "			<td>\n";
        $this->salida .= "				<b>" . $this->Cliente[0] . "</b>\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "			<td class=\"modulo_table_list_title\" width=\"10%\">\n";
        $this->salida .= "				" . $this->Cliente[2] . "\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "			<td >\n";
        $this->salida .= "				<b>" . $this->Cliente[1] . "</b>\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table><br>\n";
        $this->salida .= "<form name=\"formaP\" action=\"" . $this->action2 . "\" method=\"post\">\n";
        $this->salida .= "	<script>\n";
        $this->salida .= "		function acceptDate(evt)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			var nav4 = window.Event ? true : false;\n";
        $this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
        $this->salida .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function Recargar(val)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			valores(val);\n";
        $this->salida .= "		}\n";
        $this->salida .= "	</script>\n";
        $this->salida .= "	<table align=\"center\" width=\"60%\">\n";
        $this->salida .= "		" . $this->SetStyle("MensajeError") . "\n";
        $this->salida .= "	</table>\n";
        $this->salida .= "	<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
        switch ($this->FormaPago)
        {
            case 'CH':
                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" width=\"21%\">N CHEQUE:</td>\n";
                $this->salida .= "			<td colspan=\"3\">\n";
                $this->salida .= "				<input type=\"text\" name=\"numero_cheque\" class=\"input-text\" size=\"20\" maxlength=\"10\" value=\"" . $this->NumeroCheque . "\"\>\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "		</tr>\n";
                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_table_title\">BANCO</td>\n";
                $this->salida .= "			<td colspan=\"3\">\n";
                $this->salida .= "				<select name=\"banco\" class=\"select\">\n";
                $this->salida .= "					<option value='FA'>--------SELECCIONAR--------</option>\n";
                $Bancos = $this->ObtenerBancos();
                for ($i = 0; $i < sizeof($Bancos); $i++)
                {
                    $opciones = explode("*", $Bancos[$i]);
                    $selected = "";
                    if ($this->BancoS == $opciones[0])
                    {
                        $selected = " selected ";
                    }
                    $this->salida .= "					<option value='" . $opciones[0] . "' $selected>" . $opciones[1] . "</option>\n";
                }
                $this->salida .= "				</select>\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "		</tr>\n";
                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_table_title\">N CUENTA CORRIENTE:</td>\n";
                $this->salida .= "			<td width=\"27%\">\n";
                $this->salida .= "				<input type=\"text\" name=\"numero_cuenta\" class=\"input-text\" size=\"20\" maxlength=\"40\" value=\"" . $this->NumeroCuenta . "\"\>\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" width=\"21%\" align=\"left\">\n";
                $this->salida .= "				GIRADOR\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td width=\"27%\">\n";
                $this->salida .= "				<input type=\"text\" name=\"girador\" class=\"input-text\" size=\"20\" maxlength=\"30\" value=\"" . $this->Girador . "\"\>\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "		</tr>\n";
                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" >\n";
                $this->salida .= "				FECHA DEL CHEQUE:\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td class=\"label\" >\n";
                $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"fecha_cheque\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"" . $this->FechaCheque . "\">\n";
                $this->salida .= "					" . ReturnOpenCalendario('formaP', 'fecha_cheque', '/') . "\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" >\n";
                $this->salida .= "				FECHA DE TRANSACCION:\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td class=\"label\">\n";
                $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"fecha_transaccion\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"" . $this->FechaTransaccion . "\">\n";
                $this->salida .= "					" . ReturnOpenCalendario('formaP', 'fecha_transaccion', '/') . "\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "		</tr>\n";
                break;
            case 'CO':
                $this->IncludeJS('RemoteScripting');
                $this->IncludeJS('RemoteScripting/misfunciones.js', $contenedor = 'app', $modulo = 'RecibosCaja');

                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" width=\"22%\" >\n";
                $this->salida .= "				BANCO\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td colspan=\"3\">\n";
                $this->salida .= "				<select name=\"banco\" class=\"select\" onchange=\"Recargar(this.value)\">\n";
                $this->salida .= "					<option value='FA'>---------SELECCIONAR---------</option>\n";
                $Bancos = $this->ObtenerBancosCuentas();
                for ($i = 0; $i < sizeof($Bancos); $i++)
                {
                    $opciones = explode("*", $Bancos[$i]);
                    $selected = "";
                    if ($this->BancoS == $opciones[0])
                    {
                        $selected = " selected ";
                    }
                    $this->salida .= "					<option value='" . $opciones[0] . "' $selected>" . $opciones[1] . "</option>\n";
                }
                $this->salida .= "				</select>\n";
                $this->salida .= "			</td>\n";

                $this->salida .= "		</tr>\n";
                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_table_title\">\n";
                $this->salida .= "				N CUENTA CORRIENTE:\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td colspan=\"3\">\n";
                $this->salida .= "				<select name=\"numero_cuenta\" class=\"select\">\n";
                $this->salida .= "					<option value='NC'>---------SELECCIONAR---------</option>\n";
                $this->salida .= "				</select>\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "		</tr>\n";
                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_table_title\">FECHA DE TRANSACCION:</td>\n";
                $this->salida .= "			<td class=\"label\" width=\"30%\">\n";
                $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"fecha_transaccion\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"" . $this->FechaTransaccion . "\">\n";
                $this->salida .= "					" . ReturnOpenCalendario('formaP', 'fecha_transaccion', '/') . "\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" width=\"22%\">N TRANSACCION:</td>\n";
                $this->salida .= "			<td class=\"label\" >\n";
                $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"num_transaccion\" size=\"20\" maxlength=\"50\" value=\"" . $this->NumeroTransaccion . "\">\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "		</tr>\n";
                break;
            case 'TC':
                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" width=\"23%\" >\n";
                $this->salida .= "				TARJETA\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td width=\"27%\">\n";
                $this->salida .= "				<select name=\"tarjeta\" class=\"select\" >\n";
                $this->salida .= "					<option value='FA'>-SELECCIONAR-</option>\n";
                $Tarjetas = $this->ObtenerTarjetas();
                for ($i = 0; $i < sizeof($Tarjetas); $i++)
                {
                    $opciones = explode("*", $Tarjetas[$i]);
                    $selected = "";
                    if ($this->TarjetasS == $opciones[0])
                    {
                        $selected = " selected ";
                    }
                    $this->salida .= "					<option value='" . $opciones[0] . "' $selected>" . $opciones[1] . "</option>\n";
                }
                $this->salida .= "				</select>\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" width=\"23%\">\n";
                $this->salida .= "				N TARJETA\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td width=\"27%\">\n";
                $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"num_tarjeta\" size=\"20\" maxlength=\"20\" value=\"" . $this->NumeroTarjeta . "\">\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "		</tr>\n";
                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" width=\"23%\">\n";
                $this->salida .= "				N AUTORIZACION\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td width=\"27%\">\n";
                $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"num_autorizacion\" size=\"20\" maxlength=\"15\" value=\"" . $this->NumeroAutorizacion . "\">\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" width=\"23%\">\n";
                $this->salida .= "				SOCIO\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td width=\"27%\">\n";
                $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"socio\" size=\"20\" maxlength=\"40\" value=\"" . $this->Socio . "\">\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "		</tr>\n";
                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_table_title\">\n";
                $this->salida .= "				FECHA DE EXPIRACION:\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td class=\"label\" width=\"27%\">\n";
                $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"fecha_expiracion\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"" . $this->FechaExpiracion . "\">\n";
                $this->salida .= "					" . ReturnOpenCalendario('formaP', 'fecha_expiracion', '/') . "\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" width=\"23%\">\n";
                $this->salida .= "				AUTORIZADO POR:\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td class=\"label\" >\n";
                $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"autorizado\" size=\"20\" maxlength=\"50\" value=\"" . $this->AutorizadoPor . "\">\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "		<tr>\n";
                $this->salida .= "		</tr>\n";
                $this->salida .= "			<td class=\"modulo_table_title\">\n";
                $this->salida .= "				FECHA DE TRANSACCION:\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td class=\"label\" width=\"27%\">\n";
                $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"fecha_transaccion\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"" . $this->FechaTransaccion . "\">\n";
                $this->salida .= "					" . ReturnOpenCalendario('formaP', 'fecha_transaccion', '/') . "\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "		</tr>\n";
                break;
            case 'TD':
                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" width=\"23%\" >\n";
                $this->salida .= "				TARJETA\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td width=\"27%\">\n";
                $this->salida .= "				<select name=\"tarjeta\" class=\"select\" >\n";
                $this->salida .= "					<option value='FA'>-SELECCIONAR-</option>\n";
                $Tarjetas = $this->ObtenerTarjetas();
                for ($i = 0; $i < sizeof($Tarjetas); $i++)
                {
                    $opciones = explode("*", $Tarjetas[$i]);
                    $selected = "";
                    if ($this->TarjetasS == $opciones[0])
                    {
                        $selected = " selected ";
                    }
                    $this->salida .= "					<option value='" . $opciones[0] . "' $selected>" . $opciones[1] . "</option>\n";
                }
                $this->salida .= "				</select>\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" width=\"23%\">\n";
                $this->salida .= "				N TARJETA\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td width=\"27%\">\n";
                $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"num_tarjeta\" size=\"20\" maxlength=\"20\" value=\"" . $this->NumeroTarjeta . "\">\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "		</tr>\n";
                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_table_title\" width=\"23%\">\n";
                $this->salida .= "				N AUTORIZACION\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "			<td colspan=\"3\">\n";
                $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"num_autorizacion\" size=\"20\" maxlength=\"15\" value=\"" . $this->NumeroAutorizacion . "\">\n";
                $this->salida .= "			</td>\n";
                $this->salida .= "		</tr>\n";
                break;
        }

        $this->salida .= "	</table><br>\n";
        $this->salida .= "	<table width=\"70%\" align=\"center\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td align=\"center\" width=\"50%\">\n";
        $this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Guardar\">\n";
        $this->salida .= "			</form>\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "			<td align=\"center\" width=\"50%\">\n";
        $this->salida .= "				<form name=\"volver\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "				</form>\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    /*     * ********************************************************************************
     * Funcion donde se crea la forma, en la que se muestran los recibos de caja 
     * cerrados
     * ********************************************************************************* */

    function FormaMostrarRecibosCerrados()
    {
        $this->salida .= ThemeAbrirTabla("CONSULTA DE DOCUMENTOS CREADOS");
        $this->salida .= "<script>\n";
        $this->salida .= "	function acceptNum(evt)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		var nav4 = window.Event ? true : false;\n";
        $this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
        $this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
        $this->salida .= "	}\n";
        $this->salida .= "	function mOvr(src,clrOver)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		src.style.background = clrOver;\n";
        $this->salida .= "	}\n";
        $this->salida .= "	function mOut(src,clrIn)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		src.style.background = clrIn;\n";
        $this->salida .= "	}\n";
        $this->salida .= "</script>\n";
        $this->salida .= "<table align=\"center\" width=\"60%\">\n";
        $this->salida .= "	" . $this->SetStyle($this->parametro) . "\n";
        $this->salida .= "</table>\n";
        $this->salida .= "<table width=\"50%\" align=\"center\" >\n";
        $this->salida .= "	<tr>\n";
        $this->salida .= "		<td>\n";
        $this->salida .= $this->BuscadorRecibosCerrados();
        $this->salida .= "		</td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "</table><br>\n";

        $rc_pendientes = $this->ObtenerRecibosCajaCerrados();
        if (sizeof($rc_pendientes) > 0)
        {
            $reporte = new GetReports();
            $mostrar = $reporte->GetJavaReport('app', 'RecibosCaja', 'todosrecibos', $this->arreglo, array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $funcion = $reporte->GetJavaFunction();

            $this->salida .= "<center>" . $mostrar . "\n";
            $this->salida .= "<a href=\"javascript:$funcion\" class=\"label_error\" ><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'>REPORTE GENERAL\n";
            $this->salida .= "</center><br>\n";
            $this->salida .= "	<table border=\"0\" width=\"100%\" align=\"center\">\n";
            $this->salida .= "		<tr>\n";
            $this->salida .= "			<td>\n";
            $this->salida .= "				<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">DOCUMENTOS CREADOS</legend>\n";
            $this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "						<tr height=\"21\" class=\"modulo_table_list_title\">\n";
            $this->salida .= "							<td width=\"6%\" >N RC</td>\n";
            $this->salida .= "							<td width=\"20%\">CLIENTE</td>\n";
            $this->salida .= "							<td width=\"8%\" >REGISTRO</td>\n";
            $this->salida .= "							<td width=\"9%\" >V. RECIBO</td>\n";
            $this->salida .= "							<td width=\"9%\" >V. FINAL</td>\n";
            $this->salida .= "							<td width=\"17%\">FORMA PAGO</td>\n";
            $this->salida .= "							<td width=\"22%\">RESPONSABLE</td>\n";
            $this->salida .= "							<td width=\"8%\" colspan=\"2\">OPCIONES</td>\n";
            $this->salida .= "						</tr>\n";
            /* echo '<pre>';
              print_r($this->datos);
              //print_r($rc_pendientes);
              echo '</pre>'; */

            for ($i = 0; $i < sizeof($rc_pendientes); $i++)
            {
                if ($i % 2 == 0)
                {
                    $estilo = 'modulo_list_oscuro';
                    $background = "#CCCCCC";
                }
                else
                {
                    $estilo = 'modulo_list_claro';
                    $background = "#DDDDDD";
                }

                $reporte = new GetReports();
                $mostrar = $reporte->GetJavaReport('app', 'RecibosCaja', 'reciboscaja', $this->datos[$i], array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                $funcion = "ReciboCaja$i" . $reporte->GetJavaFunction();
                $mostrar = str_replace("function W", "function ReciboCaja" . $i . "W", $mostrar);

                $opcion = "		<a class=\"label_error\" href=\"" . $this->action4[$i] . "\" title=\"VER DETALLE RECIBOS DE CAJA\">\n";
                $opcion .= "			<img src=\"" . GetThemePath() . "/images/auditoria.png\" border=\"0\"><b>DETAL</b></a>\n";

                $Celdas = $rc_pendientes[$i];
                $valor_final = $Celdas['valor'] + $Celdas['total_abono'];

                $this->arreglo['prefijo'] = $Celdas['prefijo'];
                $this->arreglo['recibo_caja'] = $Celdas['recibo_caja'];

                $this->action4 = ModuloGetURL('app', 'RecibosCaja', 'user', 'AnularReciboCaja', array('datos' => $this->arreglo));

                $this->salida .= "						<tr class=\"" . $estilo . "\" onmouseout=mOut(this,\"" . $background . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
                $this->salida .= "							<td align=\"left\"   >" . $Celdas['prefijo'] . " " . $Celdas['recibo_caja'] . "</td>\n";
                $this->salida .= "							<td align=\"justify\">" . $Celdas['nombre_tercero'] . "</td>\n";
                $this->salida .= "							<td align=\"center\" >" . $Celdas['fecha_registro'] . "</td>\n";
                $this->salida .= "							<td align=\"right\"  >" . formatoValor($Celdas['total_abono']) . "</td>\n";
                $this->salida .= "							<td align=\"right\"  >" . formatoValor($valor_final) . "</td>\n";
                $this->salida .= "							<td align=\"justify\"><menu><b class= \"label_mark\">" . $Celdas['forma_pago'] . "</b></menu></td>\n";
                $this->salida .= "							<td align=\"justify\">" . $Celdas['nombre'] . "</td>\n";
                $this->salida .= "							<td align=\"center\">\n";
                $this->salida .= "								" . $mostrar . "\n";
                $this->salida .= " 								<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"DETALLE DEL DOCUMENTO - REPORTE\"><img src=\"" . GetThemePath() . "/images/imprimir.png\" border='0'>\n";
                $this->salida .= " 								</a>\n";
                $this->salida .= "							</td>\n";
                $this->salida .= "							<td align=\"center\">\n";
                //$this->salida .= " 								<a href=\"".$this->action4."\" class=\"label_error\"  title=\"ANULAR RECIBO DE CAJA\"><img src=\"".GetThemePath()."/images/elimina.png\" border='0'>\n";
                $this->salida .= " 								</a>\n";
                $this->salida .= "							</td>\n";
                $this->salida .= "						</tr>\n";
            }
            $this->salida .= "					</table>\n";
            $this->salida .= "				</fieldset>\n";
            $this->salida .= "			</td>\n";
            $this->salida .= "		</tr>\n";
            $this->salida .= "	</table>\n";
        }
        else
        {
            $this->salida .= "<br><center><b class=\"label_error\">LA BUSQUEDAD NO ARROJO NINGÃšN RESULTADO</b></center><br><br>\n";
        }
        $Paginador = new ClaseHTML();
        $this->salida .= "		" . $Paginador->ObtenerPaginado($this->conteo, $this->paginaActual, $this->action2);
        $this->salida .= "		<br>\n";
        $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
        $this->salida .= "		<tr><td align=\"center\">\n";
        $this->salida .= "			<form name=\"volver\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "			</form>\n";
        $this->salida .= "		</td></tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    /*     * ********************************************************************************
     * Funcion que muestra las 2 opciones de anulacion
     * ********************************************************************************* */

    function FormaMostrarOpcionesAnulacionDocumentos()
    {
        $permisos = $this->getPermisoAnularDcoumentos($_SESSION['RCFactura']['empresa'], $_SESSION['SYSTEM_USUARIO_ID']);
        $this->salida .= ThemeAbrirTabla("ANULACIÓN DE DOCUMENTOS");

        $estilo1 = 'modulo_list_oscuro';
        $background1 = "#CCCCCC";
        $estilo2 = 'modulo_list_claro';
        $background2 = "#DDDDDD";

        $this->salida .= "<table border=\"0\" width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "	<tr height=\"21\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "		<td>MENÚ</td>\n";
        $this->salida .= "	</tr>\n";
        if ($permisos['anular_consignaciones'] == 1)
        {
            $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "' align='center'>\n";
            $this->salida .= "		<td><a href=\"" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarNoIdentificados') . "\" ><b>ANULACIÓN DE CONSIGNACIONES</b></a></td>\n";
            $this->salida .= "	</tr>\n";
        }
        if ($permisos['contra_factura'] == 1)
        {
            $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "' align='center'>\n";
            $this->salida .= "		<td><a href=\"" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarFormaAnularContraFactura') . "\" ><b>CONTRA FACTURA</b></a></td>\n";
            $this->salida .= "	</tr>\n";
        }
        if ($permisos['busqueda_documentos_anulados'] == 1)
        {
            $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "' align='center'>\n";
            $this->salida .= "		<td><a href=\"" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarBusquedaAnulados') . "\" ><b>BÚSQUEDA DE DOCUMENTOS ANULADOS</b></a></td>\n";
            $this->salida .= "	</tr>\n";
        }
        $this->salida .= "</table>\n";
        $this->salida .= "";
        $this->salida .= "<br> <br> <center>";
        $this->salida .= "	<form name=\"volver\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "	</form>\n";
        $this->salida .= "</center>";
        $this->salida .= ThemeCerrarTabla();
    }

    function FormaAnularContraFactura()
    {
        /* echo '<pre>request: ';
          print_r($_REQUEST);
          echo '</pre>';

          echo '<pre>session:';
          print_r($_SESSION);
          echo '</pre>'; */

        $this->salida .= ThemeAbrirTabla("CONTRA FACTURA - ANULACIÓN DE DOCUMENTOS");

        $estilo1 = 'modulo_list_oscuro';
        $background1 = "#CCCCCC";
        $estilo2 = 'modulo_list_claro';
        $background2 = "#DDDDDD";

        $this->salida .= "     <script language='javascript'>
                                            var nav4 = window.Event ? true : false;
                                            function IsNumber(evt)
                                            {
                                                // Backspace = 8, Enter = 13, ?0? = 48, ?9? = 57, ?.? = 46
                                                var key = nav4 ? evt.which : evt.keyCode;
                                                return (key <= 13 || (key >= 48 && key <= 57) || key == 46);
                                            }
                                            </script>\n";
        $this->salida .="<script>
                                        function LimpiarForm()
                                        {
                                            //alert('hola');
                                            busqueda_frm.recibo_caja.value = '';
                                            busqueda_frm.nombre_tercero.value='';
                                            busqueda_frm.tercero_id.value ='';
                                        }
                                        </script>\n";

        //$url = ModuloGetURL('app','RecibosCaja','user','MostrarAdvertencia');
        $url_confirmacion_ACF = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarMensajeAdvertenciaAnulacionContraFactura');

        $this->salida .= "<script>\n";
        $this->salida .= "  function IrConfirmacionAnulacionCF(observacion, prefijo, recibo_caja)\n";
        $this->salida .= "  {\n";
        //$this->salida .= "      alert ('Este es el valor a update: ' + observacion + prefijo + recibo_caja);\n";
        $this->salida .= "      document.location.href='" . $url_confirmacion_ACF . "'+'&datos[prefijo]='+prefijo+'&datos[recibo_caja]='+recibo_caja+'&datos[observacion]='+observacion; \n";
        $this->salida .= "      \n";
        $this->salida .= "      \n";
        $this->salida .= "  }\n";
        $this->salida .= "</script>\n";

        $this->salida.= "<form method='POST' name='busqueda_frm' action='" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarFormaAnularContraFactura') . "'>";
        $this->salida .= "<table border=\"0\" width=\"45%\" align=\"center\" class=\"modulo_table_list\" cellspacing='2' cellpadding='2'>\n";
        $this->salida .= "	<tr height=\"21\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "		<td colspan='2'>BÚSQUEDA DE DOCUMENTO</td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "'>\n";
        $this->salida .= "		<td align='left' width='35%'><b>PREFIJO: </b></td>\n";

        $prefijo = $this->ObtenerPrefijoRecibosContraFactura($_SESSION[RCFactura][empresa]);

        $this->salida .= "		<td align='left'>\n";
        $this->salida .= "                  <select name='prefijo'>\n";
        for ($i = 0; $i < count($prefijo); $i++)
        {
            if ($_REQUEST[prefijo] == $prefijo[$i][prefijo])
            {
                $seleccion = "selected";
            }
            else
            {
                $seleccion = "";
            }
            $this->salida .= "                  <option value='" . $prefijo[$i][prefijo] . "' $seleccion>" . $prefijo[$i][prefijo] . "</option>\n";
        }
        $this->salida .= "                  </select>\n";
        $this->salida .= "		</td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "' >\n";
        $this->salida .= "		<td align='left'><b>NUMERO DE DOCUMENTO: </b></td>\n";
        $this->salida .= "		<td align='left'><input type='text' name='recibo_caja' value='" . $_REQUEST[recibo_caja] . "' onkeypress='return IsNumber(event);'></input></td>\n";
        $this->salida .= "	</tr>\n";
        /* $this->salida .= "	<tr class='".$estilo1."' background='".$background1."'>\n";
          $this->salida .= "		<td align='left'><b>NOMBRE TERCERO: </b></td>\n";
          $this->salida .= "		<td align='left'><input type='text' name='nombre_tercero' value='".$_REQUEST[nombre_tercero]."'></input></td>\n";
          $this->salida .= "	</tr>\n";
          $this->salida .= "	<tr class='".$estilo1."' background='".$background1."'>\n";
          $this->salida .= "		<td align='left'><b>ID TERCERO: </b></td>\n";
          $this->salida .= "		<td align='left'><input type='text' name='tercero_id' onkeypress='return IsNumber(event);' value='".$_REQUEST[tercero_id]."'></input></td>\n";
          $this->salida .= "	</tr>\n"; */
        $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "'>\n";
        $this->salida .= "		<td colspan='2' align='center' height='30'><input type='submit' value='Buscar'>\n";
        $this->salida .= "              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
        $this->salida .= "                                                         <input type='button' value='Limpiar Campos' onclick='LimpiarForm()'>\n";
        $this->salida .= "              </td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "</table>\n";
        $this->salida .= "</form>";
        $this->salida .= "<br><br>";

        if ($_REQUEST[prefijo])
        {
            $totales_rdtf = $this->ObtenerTotalesFacturasRC($_REQUEST[recibo_caja], $_REQUEST[prefijo]);

            /* echo '<pre>totales_rdtf:';
              print_r($totales_rdtf);
              echo '</pre>'; */

            $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "      <tr height=\"21\" class=\"modulo_table_list_title\">\n";
            $this->salida .= "		<td>DOCUMENTO</td>\n";
            $this->salida .= "		<td>CANTIDAD FACTURAS</td>\n";
            $this->salida .= "		<td>VALOR</td>\n";
            $this->salida .= "		<td>OBSERVACION</td>\n";
            $this->salida .= "		<td>ANULAR</td>\n";
            $this->salida .= "		<td>VER DETALLE</td>\n";
            $this->salida .= "      </tr>\n";

            for ($i = 0; $i < count($totales_rdtf); $i++)
            {
                $datos_rdtf = $this->ObtenerFacturasRC($totales_rdtf[$i][recibo_caja], $_REQUEST[prefijo]);
                
                $observacion_anulados = $this->TraerObservacionDAT($_SESSION[RCFactura][empresa], $totales_rdtf[$i][recibo_caja], $_REQUEST[prefijo]);
                $rc_anulados = $this->TraerDocumentoAnulacionRC($_SESSION[RCFactura][empresa], $totales_rdtf[$i][recibo_caja], $_REQUEST[prefijo]);

                $this->salida .= "      <tr height='25' class='" . $estilo1 . "' background='" . $background1 . "'>\n";
                $this->salida .= "		<td align='center' width='15%'><b>" . $_REQUEST[prefijo] . " - " . $totales_rdtf[$i][recibo_caja] . "</b></td>\n";
                $this->salida .= "		<td align='center' width='15%'><b>" . $totales_rdtf[$i][total_facturas] . "</b></td>\n";
                $this->salida .= "		<td align='center' width='15%'><b>" . formatoValor($totales_rdtf[$i][total_suma]) . "</b></td>\n";

                if ($totales_rdtf[$i][sw_estado] == 1 OR $rc_anulados[prefijo_id] == 'ARC')
                {
                    if ($observacion_anulados[observacion] != '')
                    {
                        $this->salida .= "		<td align='center' width='35%'><b>" . strtoupper($observacion_anulados[observacion]) . "</b></td>\n";
                    }
                    else
                    {
                        $this->salida .= "		<td align='center' width='35%'><b>SIN OBSERVACIÓN</b></td>\n";
                    }

                    $this->salida .= "		<td align='center' width=''><b class='label_mark'>ANULADO</b></td>\n";
                }
                else
                {
                    $this->salida .= "		<td align='center' width='35%'><b><textarea name='observacion_anulacion_" . $i . "' id='observacion_anulacion_" . $i . "' cols='47'></textarea></b></td>\n";
                    $this->salida .= "		<td align='center' width=''><b><input type='button' value='ANULAR' class=\"input-submit\" onclick=\"IrConfirmacionAnulacionCF(observacion_anulacion_" . $i . ".value, '" . $_REQUEST[prefijo] . "', " . $totales_rdtf[$i][recibo_caja] . ")\" /></b></td>\n";
                }
                $this->salida .= "		<td align='center' width='10%'>
                                                            <input class=\"input-submit\" type='button' id='btn_ver_detalle_$i' value='VER DETALLE' onclick=\"javascript: detalle_factura_$i.style.display=''; btn_ver_detalle_$i.hidden = true; btn_cerrar_detalle_$i.hidden = false;\" />
                                                            <input class=\"input-submit\" type='button' id='btn_cerrar_detalle_$i' hidden value='CERRAR DETALLE' onclick=\"javascript: detalle_factura_$i.style.display='none'; btn_ver_detalle_$i.hidden = false; btn_cerrar_detalle_$i.hidden = true;\" />
                                                        </td>\n";
                $this->salida .= "       </tr>\n";

                //FACTURAS OCULTAS PARA CADA RECIBO DE CAJA
                $this->salida .= "<tr>\n";
                $this->salida .= "  <td width='' ></td>\n";
                $this->salida .= "  <td colspan='4'>\n";
                $this->salida .= "      <div id='detalle_factura_" . $i . "' style='display:none;'>\n";
                $this->salida .= "\n";
                $this->salida .= "          <table border=\"0\" width=\"100%\" align=\"center\" cellspacing='1' cellpadding='1' class=\"modulo_table_list\" >\n";
                $this->salida .= "              <tr class=\"modulo_table_list_title\">\n";
                $this->salida .= "                  <td colspan='4'>\n";
                $this->salida .= "                      F A C T U R A S\n";
                $this->salida .= "                  </td>\n";
                $this->salida .= "              </tr>\n";
                $this->salida .= "              <tr class=\"modulo_table_list_title\">\n";
                $this->salida .= "                  <td>FACTURA</td>\n";
                $this->salida .= "                  <td>VALOR ABONADO</td>\n";
                $this->salida .= "                  <td>RESPONSABLE FACTURA</td>\n";
                $this->salida .= "                  <td>FECHA REGISTRO</td>\n";
                $this->salida .= "              </tr>\n";
                for ($j = 0; $j < count($datos_rdtf); $j++)
                {
                    $this->salida .= "              <tr class='" . $estilo1 . "' background='" . $background1 . "'>\n";
                    $this->salida .= "                  <td width='20%' align='center'>" . ($j + 1) . ". " . $datos_rdtf[$j][prefijo_factura] . " - " . $datos_rdtf[$j][factura_fiscal] . "</td>\n";
                    $this->salida .= "                  <td width='20%' align='center'>" . formatoValor($datos_rdtf[$j][valor_abonado]) . "</td>\n";
                    $this->salida .= "                  <td width='47%' align='center'>" . strtoupper($datos_rdtf[$j][nombre]) . "</td>\n";
                    $this->salida .= "                  <td width='' align='center'>" . $datos_rdtf[$j][fecha_registro] . "</td>\n";
                    $this->salida .= "              </tr>\n";
                }
                $this->salida .= "          </table>\n";
                $this->salida .= "<br>\n";
                $this->salida .= "      </div>\n";
                $this->salida .= "  </td>\n";
                $this->salida .= "  <td></td>\n";
                $this->salida .= "</tr>\n";
            }

            $this->salida .= "  </table>\n";
        }

        $this->salida .= "<br> <br> <center>";
        $this->salida .= "	<form name=\"volver\" action=\"" . $this->volver . "\" method=\"post\">\n";
        $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "	</form>\n";
        $this->salida .= "</center>";
        $this->salida .= ThemeCerrarTabla();
    }

    function FormaMostrarMensajeAdvertenciaAnulacionContraFactura()
    {
        /* echo '<pre>request: ';
          print_r($_REQUEST);
          echo '</pre>';

          echo '<pre>session:';
          print_r($_SESSION);
          echo '</pre>'; */

        $this->salida .= ThemeAbrirTabla("M E N S A J E");
        $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "      <tr height=\"21\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "		<td>\n";
        $this->salida .= "              ESTA SEGURO QUE DESEA ANULAR ESTE RECIBO No. " . $_REQUEST[datos][prefijo] . " - " . $_REQUEST[datos][recibo_caja] . "\n";
        $this->salida .= "          </td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= "  <br>\n";
        $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">\n";
        $this->salida .= "      <tr height=\"21\">\n";
        $this->salida .= "		<td align='center' width='25%'>\n";
        //$this->salida .= "              <form name=\"volver\" action=\"".ModuloGetURL('app','RecibosCaja','user','MostrarFormaAnularRC',array('datos'=>$_REQUEST[datos]))."\" method=\"post\">\n";
        $this->salida .= "              <form name=\"volver\" action=\"" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarAnularContraFacturaRC', array('datos' => $_REQUEST[datos])) . "\" method=\"post\">\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-submit\" value=\"Continuar / Anular\">\n";
        $this->salida .= "              </form>\n";
        $this->salida .= "		</td>\n";
        $this->salida .= "		<td align='center' width='25%'>\n";
        $this->salida .= "              <form name=\"volver\" action=\"" . $this->volver2 . "\" method=\"post\">\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
        $this->salida .= "              </form>\n";
        $this->salida .= "		</td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    function FormaMostrarAnularContraFacturaRC()
    {

        /*
          echo '<pre>request: ';
          print_r($_REQUEST);
          echo '</pre>';

          echo '<pre>session:';
          print_r($_SESSION);
          echo '</pre>';
         */
        //DEVUELVE LOS VALORES A LOS RCs DE LA TABLA rc_detalles
        $facturas_rc = $this->obtenerFacturas($_REQUEST['datos']['prefijo'], $_REQUEST['datos']['recibo_caja']);
        
        

        for ($ste = 0; $ste < count($facturas_rc); $ste++)
        {

            $facturas_detalles_rc = $this->obtenerFacturasDetalles($_REQUEST['datos']['prefijo'], $_REQUEST['datos']['recibo_caja'], $facturas_rc[$ste]['prefijo_factura'], $facturas_rc[$ste]['factura_fiscal']);
            
            //echo print_r($facturas_detalles_rc)."</br>";
            for ($ven = 0; $ven < count($facturas_detalles_rc); $ven++)
            {
                $valor_rc = $this->obtenerValorRc($facturas_detalles_rc[$ven]['recibo_caja'], $facturas_detalles_rc[$ven]['prefijo_rc']);
                $valor_new = 0;
                $valor_new = $valor_rc['valor_actual'] + $facturas_detalles_rc[$ven]['valor_detalle'];
                $updateValor = $this->updateValorRc($valor_new, $facturas_detalles_rc[$ven]['recibo_caja'], $facturas_detalles_rc[$ven]['prefijo_rc']);

            }
        }
        

        //exit(0);
        //TRAE LOS DATOS DE LAS FACTURAS (PREFIJO, FACTURA FISCAL, Y VALOR ABONADO)

        $datos_facturas = $this->ObtenerFacturasRC($_REQUEST[datos][recibo_caja], $_REQUEST[datos][prefijo]);
       
        $total_valor_abonado = 0;

        for ($i = 0; $i < count($datos_facturas); $i++)
        {
            //TOTAL DE LAS FACTURAS
            $total_valor_abonado += $datos_facturas[$i][valor_abonado];
            //TRAE EL SALDO DE LA TABLA DE FAC_FACTURAS
            $saldo_facturas[$i] = $this->ObtenerSaldoFacturasFF($_SESSION[RCFactura][empresa], $datos_facturas[$i][prefijo_factura], $datos_facturas[$i][factura_fiscal], $datos_facturas[$i][tipo]);
          
            //--[RQ-13564]---------------------------------------------------------------------------------------
            //SUMA EL SALDO CON EL VALOR_ABONADO
            $valor_saldo_abonado[$i] = $saldo_facturas[$i][0][saldo] + $datos_facturas[$i][valor_abonado];
            //*echo "vsa:".$valor_saldo_abonado[$i]." = sf:".$saldo_facturas[$i][0][saldo]." + df:".$datos_facturas[$i][valor_abonado]."<br>";
            //---------------------------------------------------------------------------------------------------
            //ACTUALIZA EL SALDO DE FAC_FACTURAS, ACTUALIZA ESTADOS A 1, Y GUARDA REGISTRO EN DOCUMENTOS_ANULACION_TESORERIA

            $ejecucion_updates = $this->ActualizarFacturasEstadosContraFactura($_SESSION[RCFactura][empresa], $_REQUEST[datos][prefijo], $_REQUEST[datos][recibo_caja], $datos_facturas[$i][prefijo_factura], $datos_facturas[$i][factura_fiscal], $datos_facturas[$i][tipo], $valor_saldo_abonado[$i], $datos_facturas[$i][valor_efectivo], $datos_facturas[$i][tipo_id_tercero], $datos_facturas[$i][tercero_id]);
        }

        $ejecucion_doc_anulacion = $this->CrearDocumentoDATContraFactura($_SESSION[RCFactura][empresa], $_REQUEST[datos][prefijo], $_REQUEST[datos][recibo_caja], $total_valor_abonado, $_REQUEST[datos][observacion]);

        $datos_anulacion = $this->TraerDocumentoAnulacionRC($_SESSION[RCFactura][empresa], $_REQUEST[datos][recibo_caja], $_REQUEST[datos][prefijo]);

        $mensaje = "";

        if ($ejecucion_updates == '0' OR $ejecucion_doc_anulacion == '0')
        {
            if ($ejecucion_updates == '0')
            {
                $mensaje.="Las Facturas No Fueron Anuladas.<br>";
            }
            else
            {
                $mensaje.="Las Facturas Fueron Anuladas y el Saldo Fue Actualizado.<br>";
            }
            if ($ejecucion_doc_anulacion == '0')
            {
                $mensaje.="El Documento de Anulacion No Fue Creado.<br>";
            }
            else
            {
                $mensaje.="El Documento de Anulacion Fue Creado.<br>";
            }

            /* $terceros_data = $this->TraerDocumentosAnuladosDAT($_REQUEST[datos][prefijo],$_REQUEST[datos][recibo_caja]);

              echo '<pre>';
              print_r($terceros_data);
              echo '</pre>'; */

            $this->salida .= ThemeAbrirTabla("C O N F I R M A C I Ó N");

            $this->salida .= "  <table border=\"0\" width=\"35%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "      <tr height=\"21\" class=\"modulo_table_list_title\">\n";
            $this->salida .= "		<td>\n";
            $this->salida .= "              Error con el Recibo de Caja No. " . $_REQUEST[datos][prefijo] . " " . $_REQUEST[datos][recibo_caja] . ": <br><br>\n";
            $this->salida .= "              " . $mensaje . "\n";
            $this->salida .= "          </td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "  </table>\n";
            $this->salida .= "  <br>\n";

            $this->salida .= "<center>";
            $this->salida .= "              <form name=\"volver\" action=\"" . $this->volver2 . "\" method=\"post\">\n";
            $this->salida .= "                  <input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
            $this->salida .= "              </form>\n";
            $this->salida .= "</center>\n";
            $this->salida .= ThemeCerrarTabla();
        }
        else
        {

            $terceros_data = $this->TraerDocumentosAnuladosDAT($_REQUEST[datos][prefijo], $_REQUEST[datos][recibo_caja]);

            $this->salida .= ThemeAbrirTabla("C O N F I R M A C I Ó N");
            $this->salida .= "  <table border=\"0\" width=\"35%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "      <tr height=\"21\" class=\"modulo_table_list_title\">\n";
            $this->salida .= "		<td>\n";
            $this->salida .= "              El Recibo de Caja No. " . $_REQUEST[datos][prefijo] . " " . $_REQUEST[datos][recibo] . " Fue Anulada. <br>\n";
            //$this->salida .= "              Verifique Que No Este Anulada. <br>\n";
            $this->salida .= "              Documento Anulado No. <i>" . $datos_anulacion[prefijo_id] . " " . $datos_anulacion[documentos_anulacion_tesoreria_id] . "</i>. \n";
            $this->salida .= "          </td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "  </table>\n";
            $this->salida .= "  <br>\n";

            $datos[tercero_id] = $terceros_data[0][tercero_id];
            $datos[tipo_id_tercero] = $terceros_data[0][tipo_id_tercero];
            $datos[tercero_nombre] = $terceros_data[0][nombre_tercero];
            $datos[prefijo] = $_REQUEST[datos][prefijo];
            $datos[recibo_caja] = $_REQUEST[datos][recibo_caja];
            $datos[valor_recibo] = $terceros_data[0][valor];
            $datos[sw_anular] = 1;

            $reporte = new GetReports();
            $mostrar = $reporte->GetJavaReport('app', 'RecibosCaja', 'reciboscajaanulacion', $datos, array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $funcion = "ReciboCaja$j" . $reporte->GetJavaFunction();
            $mostrar = str_replace("function W", "function ReciboCaja" . $j . "W", $mostrar);

            $this->salida .= "<center>";
            $this->salida .= "              <form name=\"volver\" action=\"" . $this->volver2 . "\" method=\"post\">\n";
            $this->salida .= "                  <input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
            $this->salida .= "              </form>\n";
            $this->salida .= "              <br>\n";
            //$this->salida .= "              <form name=\"volver\" action=\"".$this->volver2."\" method=\"post\">\n";
            $this->salida .= "                  " . $mostrar . "<input type=\"button\" class=\"input-submit\" value=\"Imprimir\" onclick=\"javascript:$funcion\" />\n";
            //$this->salida .= "              </form>\n";
            $this->salida .= "</center>\n";
            $this->salida .= ThemeCerrarTabla();
        }
    }

    /*     * ********************************************************************************
     * Funcion que muestra las 2 opciones de anulacion
     * ********************************************************************************* */

    function FormaMostrarNoIdentificados()
    {
        //$this->salida .= ThemeAbrirTabla("NO IDENTIFICADOS - ANULACIÓN DE DOCUMENTOS");
        $this->salida .= ThemeAbrirTabla("ANULACIÓN DE DOCUMENTOS");

        $estilo1 = 'modulo_list_oscuro';
        $background1 = "#CCCCCC";
        $estilo2 = 'modulo_list_claro';
        $background2 = "#DDDDDD";

        $this->salida .= "     <script language='javascript'>
                                                var nav4 = window.Event ? true : false;
                                                function IsNumber(evt)
                                                {
                                                    // Backspace = 8, Enter = 13, ?0? = 48, ?9? = 57, ?.? = 46
                                                    var key = nav4 ? evt.which : evt.keyCode;
                                                    return (key <= 13 || (key >= 48 && key <= 57) || key == 46);
                                                }
                                                </script>\n";
        $this->salida .="<script>
                                            function LimpiarForm()
                                            {
                                                //alert('hola');
                                                busqueda_frm.recibo_caja.value = '';
                                                busqueda_frm.nombre_tercero.value='';
                                                busqueda_frm.tercero_id.value ='';
                                            }
                                         </script>\n";

        $url = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarAdvertencia');

        $this->salida .= "<script>\n";
        $this->salida .= "  function IrAdvertencia(valor_rca_rc, prefijo, recibo, valor, usuario, tipo_id_tercero, tercero_id)\n";
        $this->salida .= "  {\n";
        //$this->salida .= "      alert ('Este es el valor a update: ' + valor_rca_rc + prefijo + recibo + valor + usuario + rcid);\n";
        $this->salida .= "      nombre_observacion = prefijo+recibo;\n";
        $this->salida .= "      observacion = document.getElementById(nombre_observacion);\n";
        $this->salida .= "      document.location.href='" . $url . "&datos[valor_rca_rc]=' + valor_rca_rc + '&datos[prefijo]=' + prefijo + '&datos[recibo]=' + recibo + '&datos[valor]=' + valor + '&datos[usuario]=' + usuario + '&datos[tipo_id_tercero]=' + tipo_id_tercero + '&datos[tercero_id]=' + tercero_id + '&datos[observacion]=' + observacion.value; \n";
        $this->salida .= "      \n";
        $this->salida .= "      \n";
        $this->salida .= "  }\n";
        $this->salida .= "</script>\n";

        $this->salida.= "<form method='POST' name='busqueda_frm' action='" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarNoIdentificados') . "'>";
        $this->salida .= "<table border=\"0\" width=\"45%\" align=\"center\" class=\"modulo_table_list\" cellspacing='2' cellpadding='2'>\n";
        $this->salida .= "	<tr height=\"21\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "		<td colspan='2'>BÚSQUEDA DE DOCUMENTO</td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "'>\n";
        $this->salida .= "		<td align='left' width='35%'><b>PREFIJO: </b></td>\n";

        $prefijo = $this->ObtenerPrefijoRecibos($_SESSION[RCFactura][empresa]);

        $this->salida .= "		<td align='left'>\n";
        $this->salida .= "                  <select name='prefijo'>\n";
        for ($i = 0; $i < count($prefijo); $i++)
        {
            if ($_REQUEST[prefijo] == $prefijo[$i][prefijo])
            {
                $seleccion = "selected";
            }
            else
            {
                $seleccion = "";
            }
            $this->salida .= "                  <option value='" . $prefijo[$i][prefijo] . "' $seleccion>" . $prefijo[$i][prefijo] . "</option>\n";
        }
        $this->salida .= "                  </select>\n";
        $this->salida .= "		</td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "' >\n";
        $this->salida .= "		<td align='left'><b>NUMERO DE DOCUMENTO: </b></td>\n";
        $this->salida .= "		<td align='left'><input type='text' name='recibo_caja' value='" . $_REQUEST[recibo_caja] . "' onkeypress='return IsNumber(event);'></input></td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "'>\n";
        $this->salida .= "		<td align='left'><b>NOMBRE TERCERO: </b></td>\n";
        $this->salida .= "		<td align='left'><input type='text' name='nombre_tercero' value='" . $_REQUEST[nombre_tercero] . "'></input></td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "'>\n";
        $this->salida .= "		<td align='left'><b>ID TERCERO: </b></td>\n";
        $this->salida .= "		<td align='left'><input type='text' name='tercero_id' onkeypress='return IsNumber(event);' value='" . $_REQUEST[tercero_id] . "'></input></td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "'>\n";
        $this->salida .= "		<td colspan='2' align='center' height='30'><input type='submit' value='Buscar'>\n"; //-21-1
        $this->salida .= "              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
        $this->salida .= "                                                         <input type='button' value='Limpiar Campos' onclick='LimpiarForm()'>\n";
        $this->salida .= "              </td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "</table>\n";
        $this->salida .= "</form>";

        $this->salida .= "<br><br>";
        /* echo '<pre>REQUEST: ';
          print_r($_REQUEST);
          echo '</pre>';
          echo '<pre>SESSION: ';
          print_r($_SESSION);
          echo '</pre>'; */
        if ($_REQUEST[prefijo])
        {
            $recibos_caja = $this->TraerRecibosCajaBusqueda($_REQUEST[prefijo], $_REQUEST[recibo_caja], $_REQUEST[nombre_tercero], $_REQUEST[tercero_id]);

            $this->salida .= "<form method='POST'>";
            $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "      <tr height=\"21\" class=\"modulo_table_list_title\">\n";
            $this->salida .= "		<td>DOCUMENTO</td>\n";
            $this->salida .= "		<td>CLIENTE</td>\n";
            $this->salida .= "		<td>REGISTRO</td>\n";
            $this->salida .= "		<td>V. RECIBO</td>\n";
            //$this->salida .= "		<td>V. FINAL</td>\n";
            $this->salida .= "		<td>FORMA PAGO</td>\n"; //-21-3
            $this->salida .= "		<td>RESPONSABLE</td>\n";
            $this->salida .= "		<td>OBSERVACION</td>\n";
            $this->salida .= "		<td>ANULAR</td>\n";
            $this->salida .= "      </tr>\n";

            /* echo '<pre>RC: ';
              print_r($recibos_caja);
              echo '</pre>'; */
            for ($j = 0; $j < count($recibos_caja); $j++)
            {
                $descripcion_terceros = $this->TraerDescripcionTerceros($recibos_caja[$j][tercero_id], $recibos_caja[$j][tipo_id_tercero]);
                $nombre_usuario = $this->TraerNombreUsuario($recibos_caja[$j][usuario_id]);
                $saldo_rca = $this->TraeSaldoRCControlAnticipos($recibos_caja[$j][empresa_id], $recibos_caja[$j][tipo_id_tercero], $recibos_caja[$j][tercero_id]);
                $rc_id = $this->TraerRcIdRDTC($recibos_caja[$j][empresa_id], $recibos_caja[$j][recibo_caja], $recibos_caja[$j][prefijo]);
                $observacion = $this->TraerObservacionDAT($recibos_caja[$j][empresa_id], $recibos_caja[$j][recibo_caja], $recibos_caja[$j][prefijo]);
                /* echo '<pre>RC_ID: ';
                  print_r($rc_id);
                  echo '</pre>'; */
                $valor_rca_rc = $saldo_rca[saldo] - ($recibos_caja[$j][total_consignacion]);

                $forma_pago = "";
                if ($recibos_caja[$j]['otros'] > 0)
                {
                    $forma_pago .= "OTRO CONCEPTO ";
                }
                if ($recibos_caja[$j]['total_cheques'] > 0)
                {
                    $forma_pago .= "CHEQUE ";
                }
                if ($recibos_caja[$j]['total_efectivo'] > 0)
                {
                    $forma_pago .= "EFECTIVO ";
                }
                if ($recibos_caja[$j]['total_tarjetas'] > 0)
                {
                    $forma_pago .= "TARJETA ";
                }
                if ($recibos_caja[$j]['total_consignacion'] > 0)
                {
                    $forma_pago .= "CONSIGNACIÓN ";
                }
                /* echo '<pre>';
                  print_r($descripcion_terceros);
                  echo '</pre>'; */
                /* echo '<pre>';
                  print_r($recibos_caja);
                  echo '</pre>'; */
                $this->salida .= "      <tr class='" . $estilo1 . "' background='" . $background1 . "'>\n";
                $this->salida .= "		<td align='center' width='10%'><b>" . $recibos_caja[$j][prefijo] . " - " . $recibos_caja[$j][recibo_caja] . "</b></td>\n";
                $this->salida .= "		<td align='center' width=''><b>" . $descripcion_terceros[nombre_tercero] . "</b></td>\n";
                $this->salida .= "		<td align='center' width='10%'><b>" . $recibos_caja[$j][fecha_registro] . "</b></td>\n";
                $this->salida .= "		<td align='center' width='10%'><b>" . formatoValor($recibos_caja[$j][total_abono]) . "</b></td>\n";
                $this->salida .= "		<td align='center' width='10%'><b class= \"label_mark\">" . $forma_pago . "</b></td>\n";
                $this->salida .= "		<td align='center' width='15%'><b>" . $nombre_usuario[nombre] . "</b></td>\n";

                if ($recibos_caja[$j][estado] != 1)
                {
                    if ($saldo_rca[saldo] >= ($recibos_caja[$j][total_consignacion]))
                    {
                        $this->salida .= "		<td align='center' width='15%'><b><textarea name='observacion_anulacion' id='" . $recibos_caja[$j][prefijo] . $recibos_caja[$j][recibo_caja] . "' cols='30'></textarea></b></td>\n";
                        $this->salida .= "		<td align='center' width='5%'><input type='button' class=\"input-submit\" value='Anular' onclick=\"IrAdvertencia(" . $valor_rca_rc . ", '" . $recibos_caja[$j][prefijo] . "', " . $recibos_caja[$j][recibo_caja] . ", " . $recibos_caja[$j][total_abono] . ", " . $recibos_caja[$j][usuario_id] . ", '" . $recibos_caja[$j][tipo_id_tercero] . "', " . $recibos_caja[$j][tercero_id] . ")\"/></td>\n";
                    }
                    else
                    {
                        $this->salida .= "          <td align='center' width='15%'><b>" . $observacion[observacion] . "</b></td>\n";
                        $this->salida .= "          <td align='center' width='5%'><b class= \"label_mark\">Saldo Inferior</b></td>";
                    }
                }
                else
                {
                    //$this->salida .= "		<td align='center' width='5%'><input type='button' class=\"input-submit\" value='Anular' onclick='IrAdvertencia(".$valor_rca_rc.")' DISABLED/></td>\n";
                    $this->salida .= "          <td align='center' width='15%'><b>" . $observacion[observacion] . "</b></td>\n";
                    $this->salida .= "          <td align='center' width='5%'><b class= \"label_mark\">ANULADO</b></td>";
                }
                $this->salida .= "      </tr>\n";
            }

            $this->salida .= "  </table>\n";
            $this->salida .= "</form>";
        }

        $this->salida .= "";
        $this->salida .= "<br> <br> <center>";
        $this->salida .= "	<form name=\"volver\" action=\"" . $this->volver . "\" method=\"post\">\n";
        $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "	</form>\n";
        $this->salida .= "</center>";
        $this->salida .= ThemeCerrarTabla();
    }

    function FormaMostrarAdvertencia()
    {
        /* echo '<pre>';
          print_r($_REQUEST);
          echo '</pre>'; */
        /* echo '<pre>SESSION: ';
          print_r($_SESSION);
          echo '</pre>'; */
        $this->salida .= ThemeAbrirTabla("M E N S A J E");
        $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "      <tr height=\"21\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "		<td>\n";
        $this->salida .= "              ESTA SEGURO QUE DESEA ANULAR ESTE RECIBO DE CUENTA No. " . $_REQUEST[datos][prefijo] . " - " . $_REQUEST[datos][recibo] . "\n";
        $this->salida .= "          </td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= "  <br>\n";
        $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">\n";
        $this->salida .= "      <tr height=\"21\">\n";
        $this->salida .= "		<td align='center' width='25%'>\n";
        $this->salida .= "              <form name=\"volver\" action=\"" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarFormaAnularRC', array('datos' => $_REQUEST[datos])) . "\" method=\"post\">\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-submit\" value=\"Continuar / Anular \">\n";
        $this->salida .= "              </form>\n";
        $this->salida .= "		</td>\n";
        $this->salida .= "		<td align='center' width='25%'>\n";
        $this->salida .= "              <form name=\"volver\" action=\"" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarNoIdentificados') . "\" method=\"post\">\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
        $this->salida .= "              </form>\n";
        $this->salida .= "		</td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "  </table>\n";

        $this->salida .= "\n";
        $this->salida .= ThemeCerrarTabla();
    }

    function FormaAnularRC()
    {
	
		$inv_factura_mod  = '0';
               
        //echo "<pre>".print_r($_REQUEST)."</pre>";
        
        $anulacion_confirmacion = $this->ActualizarSaldoyEstados($_REQUEST[datos][valor_rca_rc], $_SESSION[RCFactura][empresa], $_REQUEST[datos][tipo_id_tercero], $_REQUEST[datos][tercero_id], $_REQUEST[datos][recibo], $_REQUEST[datos][prefijo], $_REQUEST[datos][valor], $_SESSION[SYSTEM_USUARIO_ID], $_REQUEST[datos][observacion]);
        $datos_anulacion = $this->TraerDocumentoAnulacionRC($_SESSION[RCFactura][empresa], $_REQUEST[datos][recibo], $_REQUEST[datos][prefijo]);
       // echo var_dump($anulacion_confirmacion);
        /* echo '<pre>datos_anulacion: ';
          print_r($datos_anulacion);
          echo '</pre>'; */
        if ($anulacion_confirmacion == '1')
        {
			$inv_factura_mod = $this->ActualizarSaldoFactura($_REQUEST[datos][prefijo],$_REQUEST[datos][recibo]);
			
		}
		
		if ($inv_factura_mod == '1')
        {
		
            $tercero_nombre = $this->TraerDescripcionTerceros($_REQUEST[datos][tercero_id], $_REQUEST[datos][tipo_id_tercero]);

            $datos[tercero_id] = $_REQUEST[datos][tercero_id];
            $datos[tipo_id_tercero] = $_REQUEST[datos][tipo_id_tercero];
            $datos[tercero_nombre] = $tercero_nombre[nombre_tercero];
            $datos[prefijo] = $_REQUEST[datos][prefijo];
            $datos[recibo_caja] = $_REQUEST[datos][recibo];
            $datos[valor_recibo] = $_REQUEST[datos][valor];
            $datos[sw_anular] = 1;

            $reporte = new GetReports();
            $mostrar = $reporte->GetJavaReport('app', 'RecibosCaja', 'reciboscajaanulacion', $datos, array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
            $funcion = "ReciboCaja$j" . $reporte->GetJavaFunction();
            $mostrar = str_replace("function W", "function ReciboCaja" . $j . "W", $mostrar);

            $this->salida .= ThemeAbrirTabla("C O N F I R M A C I Ó N");
            $this->salida .= "  <table border=\"0\" width=\"35%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "      <tr height=\"21\" class=\"modulo_table_list_title\">\n";
            $this->salida .= "		<td>\n";
            $this->salida .= "              El Recibo de Caja No. " . $_REQUEST[datos][prefijo] . " " . $_REQUEST[datos][recibo] . " Ha Sido Anulado. <br>\n";
            $this->salida .= "              Documento Anulado No. <i>" . $datos_anulacion[prefijo_id] . " " . $datos_anulacion[documentos_anulacion_tesoreria_id] . "</i>. \n";
            $this->salida .= "          </td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "  </table>\n";
            $this->salida .= "  <br>\n";
            $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">\n";
            $this->salida .= "      <tr height=\"21\">\n";
            $this->salida .= "		<td align='center'>\n";
            $this->salida .= "              <form name=\"volver\" action=\"" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarNoIdentificados') . "\" method=\"post\">\n";
            $this->salida .= "                  <input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
            $this->salida .= "              </form>\n";
            $this->salida .= "		</td>\n";
            $this->salida .= "		<td align='center'>\n";
            $this->salida .= "                  " . $mostrar . "<input type='button' class=\"input-submit\" value='imprimir' onclick='javascript:$funcion' />";
            $this->salida .= "		</td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "  </table>\n";
            $this->salida .= "\n";
            $this->salida .= ThemeCerrarTabla();
        }
        else
        {
            $this->salida .= ThemeAbrirTabla("C O N F I R M A C I Ó N");
            $this->salida .= "  <table border=\"0\" width=\"35%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "      <tr height=\"21\" class=\"modulo_table_list_title\">\n";
            $this->salida .= "		<td>\n";
            $this->salida .= "              El Recibo de Caja No. " . $_REQUEST[datos][prefijo] . " " . $_REQUEST[datos][recibo] . " No Puede Ser Anulada. <br>\n";
            $this->salida .= "              Verifique Que No Este Anulada. <br>\n";
            //$this->salida .= "              Documento Anulado No. <i>".$datos_anulacion[prefijo_id]." ".$datos_anulacion[documentos_anulacion_tesoreria_id]."</i>. \n";
            $this->salida .= "          </td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "  </table>\n";
            $this->salida .= "  <br>\n";
            $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\">\n";
            $this->salida .= "      <tr height=\"21\">\n";
            $this->salida .= "		<td align='center'>\n";
            $this->salida .= "              <form name=\"volver\" action=\"" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarNoIdentificados') . "\" method=\"post\">\n";
            $this->salida .= "                  <input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
            $this->salida .= "              </form>\n";
            $this->salida .= "		</td>\n";
            $this->salida .= "      </tr>\n";
            $this->salida .= "  </table>\n";
            $this->salida .= "\n";


            $this->salida .= ThemeCerrarTabla();
        }
    }

    function FormaMostrarBusquedaAnulados()
    {
        $this->salida .= ThemeAbrirTabla("BÚSQUEDA DOCUMENTOS ANULADOS");
        $estilo1 = 'modulo_list_oscuro';
        $background1 = "#CCCCCC";
        $estilo2 = 'modulo_list_claro';
        $background2 = "#DDDDDD";

        $this->salida .= "     <script language='javascript'>
                                            var nav4 = window.Event ? true : false;
                                            function IsNumber(evt)
                                            {
                                                // Backspace = 8, Enter = 13, ?0? = 48, ?9? = 57, ?.? = 46
                                                var key = nav4 ? evt.which : evt.keyCode;
                                                return (key <= 13 || (key >= 48 && key <= 57) || key == 46);
                                            }
                                            </script>\n";
        $this->salida .="<script>
                                        function LimpiarForm()
                                        {
                                            //alert('hola');
                                            busqueda_frm.recibo_caja.value = '';
                                            busqueda_frm.nombre_tercero.value='';
                                            busqueda_frm.tercero_id.value ='';
                                        }
                                        </script>\n";

        $url = ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarAdvertencia');

        $this->salida .= "<script>\n";
        $this->salida .= "  function IrAdvertencia(valor_rca_rc, prefijo, recibo, valor, usuario, tipo_id_tercero, tercero_id)\n";
        $this->salida .= "  {\n";
        //$this->salida .= "      alert ('Este es el valor a update: ' + valor_rca_rc + prefijo + recibo + valor + usuario + rcid);\n";
        $this->salida .= "      nombre_observacion = prefijo+recibo;\n";
        $this->salida .= "      observacion = document.getElementById(nombre_observacion);\n";
        $this->salida .= "      document.location.href='" . $url . "&datos[valor_rca_rc]=' + valor_rca_rc + '&datos[prefijo]=' + prefijo + '&datos[recibo]=' + recibo + '&datos[valor]=' + valor + '&datos[usuario]=' + usuario + '&datos[tipo_id_tercero]=' + tipo_id_tercero + '&datos[tercero_id]=' + tercero_id + '&datos[observacion]=' + observacion.value; \n";
        $this->salida .= "      \n";
        $this->salida .= "      \n";
        $this->salida .= "  }\n";
        $this->salida .= "</script>\n";

        $this->salida.= "<form method='POST' name='busqueda_frm' action='" . ModuloGetURL('app', 'RecibosCaja', 'user', 'MostrarBusquedaAnulados') . "'>";
        $this->salida .= "<table border=\"0\" width=\"45%\" align=\"center\" class=\"modulo_table_list\" cellspacing='2' cellpadding='2'>\n";
        $this->salida .= "	<tr height=\"21\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "		<td colspan='2'>BÚSQUEDA DE DOCUMENTO ANULADO</td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "'>\n";
        $this->salida .= "		<td align='left' width='35%'><b>PREFIJO: </b></td>\n";

        $prefijo = $this->ObtenerPrefijoRecibos($_SESSION[RCFactura][empresa]);

        $this->salida .= "		<td align='left'>\n";
        $this->salida .= "                  <select name='prefijo'>\n";
        for ($i = 0; $i < count($prefijo); $i++)
        {
            if ($_REQUEST[prefijo] == $prefijo[$i][prefijo])
            {
                $seleccion = "selected";
            }
            else
            {
                $seleccion = "";
            }
            $this->salida .= "                  <option value='" . $prefijo[$i][prefijo] . "' $seleccion>" . $prefijo[$i][prefijo] . "</option>\n";
        }
        $this->salida .= "                  </select>\n";
        $this->salida .= "		</td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "' >\n";
        $this->salida .= "		<td align='left'><b>NÚMERO DE DOCUMENTO: </b></td>\n";
        $this->salida .= "		<td align='left'><input type='text' name='recibo_caja' value='" . $_REQUEST[recibo_caja] . "' onkeypress='return IsNumber(event);'></input></td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "'>\n";
        $this->salida .= "		<td align='left'><b>NÚMERO DE ANULACIÓN: </b></td>\n";
        $this->salida .= "		<td align='left'><input type='text' name='id_anulacion' onkeypress='return IsNumber(event);' value='" . $_REQUEST[id_anulacion] . "'></input></td>\n";
        $this->salida .= "	</tr>\n";
        /* $this->salida .= "	<tr class='".$estilo1."' background='".$background1."'>\n";
          $this->salida .= "		<td align='left'><b>ID TERCERO: </b></td>\n";
          $this->salida .= "		<td align='left'><input type='text' name='tercero_id' onkeypress='return IsNumber(event);' value='".$_REQUEST[tercero_id]."'></input></td>\n";
          $this->salida .= "	</tr>\n"; */
        $this->salida .= "	<tr class='" . $estilo1 . "' background='" . $background1 . "'>\n";
        $this->salida .= "		<td colspan='2' align='center' height='30'><input type='submit' value='Buscar'>\n";
        $this->salida .= "              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
        $this->salida .= "                                                     <input type='button' value='Limpiar Campos' onclick='LimpiarForm()'>\n";
        $this->salida .= "              </td>\n";
        $this->salida .= "	</tr>\n";
        $this->salida .= "</table>\n";
        $this->salida .= "</form>";
        $this->salida .= "<br><br>";

        /* echo '<pre>';
          print_r($_REQUEST);
          echo '</pre>'; */

        if ($_REQUEST[prefijo])
        {
            $docs_anulados = $this->TraerDocumentosAnuladosDAT($_REQUEST[prefijo], $_REQUEST[recibo_caja], $_REQUEST[id_anulacion]);
            if ($docs_anulados)
            {
                $this->salida .= "<form method='POST'>";
                $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
                $this->salida .= "      <tr height=\"21\" class=\"modulo_table_list_title\">\n";
                $this->salida .= "		<td>DOCUMENTO ANULADO</td>\n";
                $this->salida .= "		<td>DOCUMENTO</td>\n";
                $this->salida .= "		<td>CLIENTE</td>\n";
                $this->salida .= "		<td>REGISTRO</td>\n";
                $this->salida .= "		<td>VALOR</td>\n";
                //$this->salida .= "		<td>FORMA PAGO</td>\n";
                $this->salida .= "		<td>RESPONSABLE</td>\n";
                $this->salida .= "		<td>OBSERVACION</td>\n";
                $this->salida .= "		<td>IMPRIMIR</td>\n";
                $this->salida .= "      </tr>\n";

                /* echo '<pre>';
                  print_r($docs_anulados);
                  echo '</pre>'; */
                for ($j = 0; $j < count($docs_anulados); $j++)
                {
                    $nombre_usuario = $this->TraerNombreUsuario($docs_anulados[$j][usuario_id]);

                    $datos[tercero_id] = $docs_anulados[$j][tercero_id];
                    $datos[tipo_id_tercero] = $docs_anulados[$j][tipo_id_tercero];
                    $datos[tercero_nombre] = $docs_anulados[$j][nombre_tercero];
                    $datos[prefijo] = $docs_anulados[$j][prefijo];
                    $datos[recibo_caja] = $docs_anulados[$j][recibo_caja];
                    $datos[valor_recibo] = $docs_anulados[$j][valor];
                    $datos[sw_anular] = 1;

                    $this->salida .= "      <tr class='" . $estilo1 . "' background='" . $background1 . "'>\n";
                    $this->salida .= "		<td align='center' width='15%'><b>" . $docs_anulados[$j][prefijo_id] . " - " . $docs_anulados[$j][documentos_anulacion_tesoreria_id] . "</b></td>\n";
                    $this->salida .= "		<td align='center' width='10%'><b>" . $docs_anulados[$j][prefijo] . " - " . $docs_anulados[$j][recibo_caja] . "</b></td>\n";
                    $this->salida .= "		<td align='center' width=''><b>" . $docs_anulados[$j][nombre_tercero] . "</b></td>\n";
                    $this->salida .= "		<td align='center' width='10%'><b>" . $docs_anulados[$j][fecha_registro] . "</b></td>\n";
                    $this->salida .= "		<td align='center' width='10%'><b>" . formatoValor($docs_anulados[$j][valor]) . "</b></td>\n";
                    $this->salida .= "		<td align='center' width='10%'><b>" . $nombre_usuario[nombre] . "</b></td>\n";
                    $this->salida .= "		<td align='center' width='15%'><b>" . $docs_anulados[$j][observacion] . "</b></td>\n";

                    $reporte = new GetReports();
                    $mostrar = $reporte->GetJavaReport('app', 'RecibosCaja', 'reciboscajaanulacion', $datos, array('rpt_name' => '', 'rpt_dir' => 'cache', 'rpt_rewrite' => TRUE));
                    $funcion = "ReciboCaja$j" . $reporte->GetJavaFunction();
                    $mostrar = str_replace("function W", "function ReciboCaja" . $j . "W", $mostrar);

                    $this->salida .= "		" . $mostrar . " <td align='center' width='8%'><a href='javascript:$funcion'><img src=\"" . GetThemePath() . "/images/imprimir.png\"  border=\"0\"></a></td>\n";
                    //$this->salida .= "		 <td align='center' width='8%'><input type='button' onclick='javascript:$funcion' /><img src=\"".GetThemePath()."/images/imprimir.png\"  border=\"0\"></a></td>\n";
                }
                $this->salida .= "  </table>\n";
                $this->salida .= "</form>\n";
            }
            else
            {
                $this->salida .= "<center><b class='label_error'>LA BÚSQUEDA NO ARROJO RESULTADOS.</b></center>\n";
            }
        }

        $this->salida .= "<br><br>";
        $this->salida .= "<center>";
        $this->salida .= "	<form name=\"volver\" action=\"" . $this->volver . "\" method=\"post\">\n";
        $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "	</form>\n";
        $this->salida .= "</center>";

        $this->salida .= ThemeCerrarTabla();
    }

    /*
     * 
     */

    function FormaModificarValorDocumento()
    {
        /* echo '<pre>';
          print_r($_REQUEST);
          echo '</pre>';
          echo '<pre>menu';
          print_r($this->menu);
          echo '</pre>'; */
        $this->salida .= ThemeAbrirTabla("MODIFICAR VALOR DOCUMENTO");
        $this->salida .= "<form method='POST' action=''>";
        $this->salida .= " <table width='30%' align='center' class=\"modulo_table_list\" cellpadding='5'>";
        $this->salida .= "      <tr class=\"modulo_table_list_title\"><td colspan='2'>MODIFICAR VALOR DOCUMENTO</td></tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "          <td align='left' width='10%'><b>VALOR NUEVO:</b></td>";
        $this->salida .= "          <td align='left' width='20%'><input type='text' class='input-text' name='valor_documento'/></td>";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "          <td colspan='2' align='center'><input type='submit' value='Guardar' class='input-submit'/></td>";
        $this->salida .= "      </tr>";
        $this->salida .= " </table>";
        $this->salida .= "</form>";

        if ($_REQUEST['valor_documento'] != '')
        {
            if (is_numeric($_REQUEST['valor_documento']))
            {
                $update_valor = $this->UpdateModificarValorDocumento($_REQUEST['documento'], $_REQUEST['valor_documento'], $this->menu['sw_cruce_endosos']);
                if ($update_valor)
                {
                    $this->salida .= "<p align='center' class='label_error'>VALOR MODIFICADO SATISFACTORIAMENTE.</p>";
                }
                else
                {
                    $this->salida .= "<p align='center' class='label_error'>EL VALOR NO FUE MODIFICADO.</p>";
                }
            }
            else
            {
                $this->salida .= "<p align='center' class='label_error'>DEBE INGRESAR UN VALOR NUMERICO.</p>";
            }
        }
        /* else
          {
          $this->salida .= "<p align='center' class='label_error'>DEBE INGRESAR UN VALOR.</p>";
          } */

        $this->salida .= "<br>";
        $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
        $this->salida .= "		<tr><td align=\"center\">\n";
        $this->salida .= "			<form name=\"volver\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "			</form>\n";
        $this->salida .= "		</td></tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    /*     * ********************************************************************************
     *
     * ********************************************************************************* */

    function FormaEliminarFacturasReciboCaja()
    {
        /* echo "submit: ".$this->action2."<br><br>";

          echo '<pre>';
          print_r($_REQUEST);
          echo '</pre>';

          echo '<pre>';
          print_r($_SESSION);
          echo '</pre>'; */

        $this->salida .= ThemeAbrirTabla(" ELIMINAR FACTURAS DE EL/LA " . strtoupper(trim($this->menu['descripcion'])) . "");
        $this->salida .= "<script>\n";
        $this->salida .= "	function seleccionarTodos(objeto,longitud)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		marca = true;\n";
        $this->salida .= "		if(!objeto.checkall.checked) marca = false;\n";
        $this->salida .= "		try\n";
        $this->salida .= "		{\n";
        $this->salida .= "			for(i=0;i<longitud; i++ )\n";
        $this->salida .= "				objeto.factura[i].checked = marca; \n";
        $this->salida .= "		}\n";
        $this->salida .= "		catch(error)\n";
        $this->salida .= "		{\n";
        $this->salida .= "				objeto.factura.checked = marca; \n";
        $this->salida .= "		}\n";
        $this->salida .= "	}\n";
        $this->salida .= "	function mOvr(src,clrOver)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		src.style.background = clrOver;\n";
        $this->salida .= "	}\n";
        $this->salida .= "	function mOut(src,clrIn)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		src.style.background = clrIn;\n";
        $this->salida .= "	}\n";
        $this->salida .= "	function autoCheck(check_id,check_gen)
                                                {
                                                    //alert('hola: '+check_gen);
                                                    var check = document.getElementById(check_id),
                                                        check_p = document.getElementsByName(check_gen);
                                                        
                                                    if(check_p[0].checked)
                                                    {
                                                        //alert('checked');
                                                        check.checked = true;
                                                    }
                                                    else
                                                    {
                                                        //alert('no checked');
                                                        check.checked = false;
                                                    }
                                                }
                                                \n";
        $this->salida .= "</script>\n";

        $Facturas = $this->ObtenerFacturasAbonadas();
        $centro_util = "&centro=" . $_REQUEST['centro'];
        /* echo $this->action2;
          echo "<br>";
          echo "<pre>";
          print_r($_REQUEST);
          echo "</pre>"; */
        if (sizeof($Facturas) > 0)
        {
            $this->salida .= "<form name=\"pagarfactura\" action=\"" . $this->action2 . $centro_util . "\" method=\"post\">\n";
            $this->salida .= "	<table align=\"center\" width=\"60%\">\n";
            $this->salida .= "		" . $this->SetStyle("MensajeError") . "\n";
            $this->salida .= "	</table>\n";
            $this->salida .= "	<table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "				<td width=\"8%\"><b>FACTURA</b></td>\n";
            $this->salida .= "				<td width=\"8%\"><b>FECHA</b></td>\n";
            $this->salida .= "				<td width=\"7%\"><b>N ENVIO</b></td>\n";
            $this->salida .= "				<td width=\"8%\" title=\"FECHA DE RADICACION\"><b>F. RAD.</b></td>\n";
            $this->salida .= "				<td width=\"9%\"><b>V. GLOSA</b></td>\n";
            $this->salida .= "				<td width=\"9%\"><b>V. ACEPTADO</b></td>\n";
            $this->salida .= "				<td width=\"9%\"><b>TOTAL</b></td>\n";
            $this->salida .= "				<td width=\"9%\"><b>SALDO</b></td>\n";
            $this->salida .= "				<td width=\"9%\"><b>ABONO</b></td>\n";
            $this->salida .= "				<td width=\"2%\" ><input type=\"checkbox\" name=\"checkall\" onclick=\"seleccionarTodos(document.pagarfactura," . sizeof($Facturas) . ")\"></td>\n";
            $this->salida .= "			</tr>";

            for ($i = 0; $i < sizeof($Facturas); $i++)
            {
                if ($i % 2 == 0)
                {
                    $estilo = 'modulo_list_oscuro';
                    $background = "#CCCCCC";
                }
                else
                {
                    $estilo = 'modulo_list_claro';
                    $background = "#DDDDDD";
                }

                $this->salida .= "			<tr class=\"" . $estilo . "\" onmouseout=mOut(this,\"" . $background . "\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
                $this->salida .= "				<td >&nbsp;" . $Facturas[$i]['prefijo'] . " " . $Facturas[$i]['factura_fiscal'] . "</td>\n";
                $this->salida .= "				<td align=\"center\">" . $Facturas[$i]['fecha1'] . "</td>\n";
                $this->salida .= "				<td align=\"right\" >" . $Facturas[$i]['envio_id'] . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"center\">" . $Facturas[$i]['fecha2'] . "</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['valor_glosa']) . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['valor_aceptado']) . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['total_factura']) . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['saldo'] - $Facturas[$i]['abono']) . "&nbsp;</td>\n";
                $this->salida .= "				<td align=\"right\" >" . formatoValor($Facturas[$i]['abono']) . "&nbsp;</td>\n";
                $this->salida .= "				<td>\n";
                $this->salida .= "					<input type=\"checkbox\" id=\"factura\" name=\"factura[$i]\" value=\"" . $Facturas[$i]['prefijo'] . "*" . $Facturas[$i]['factura_fiscal'] . "\" onclick=\"autoCheck('factura_id_hidden_$i',this.name)\" >\n";
                $this->salida .= "					<input type=\"hidden\" name=\"recibo[$i]\" value=\"" . $Facturas[$i]['tmp_rc_id'] . "\" >\n";
                $this->salida .= "					<input type=\"checkbox\" name=\"factura_hidden[]\" id='factura_id_hidden_$i' value=\"" . $Facturas[$i]['prefijo'] . "*" . $Facturas[$i]['factura_fiscal'] . "\" style=\"display:none;\" hidden>\n";
                $this->salida .= "				</td>\n";
                $this->salida .= "			</tr>";
            }

            $this->salida .= "	</table><br>\n";
            $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
            $this->salida .= "		<tr><td align=\"center\">\n";
            $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Desvincular Facturas\">\n";
            $this->salida .= "		</td></tr>\n";
            $this->salida .= "	</table><br>\n";

            $Paginador = new ClaseHTML();
            $this->salida .= "		" . $Paginador->ObtenerPaginado($this->conteo, $this->paginaActual, $this->action3);
            $this->salida .= "		<br>\n";
            $this->salida .= "</form>\n";
        }
        else
        {
            $this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUNA FACTURA VINCULADA</b></center><br><br>\n";
        }

        $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
        $this->salida .= "		<tr><td align=\"center\">\n";
        $this->salida .= "			<form name=\"volver\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "			</form>\n";
        $this->salida .= "		</td></tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    /*     * ********************************************************************************
     *
     * ********************************************************************************* */

    function FormaModificarInformacion()
    {
        $est = " style=\"text-align:left;text-indent:8pt\" ";

        $this->salida .= ThemeAbrirTabla("LISTADO DE FACTURAS");
        $this->salida .= "	<script>\n";
        $this->salida .= "		function acceptDate(evt)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			var nav4 = window.Event ? true : false;\n";
        $this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
        $this->salida .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
        $this->salida .= "		}\n";
        $this->salida .= "	</script>\n";
        $this->salida .= "<table align=\"center\" width=\"60%\">\n";
        $this->salida .= "	" . $this->SetStyle($this->Parametro) . "\n";
        $this->salida .= "</table>\n";
        $this->salida .= "<form name=\"modificarNota\" action =\"" . $this->action2 . "\" method=\"post\" >\n";
        $this->salida .= "	<table border=\"0\" width=\"60%\" align=\"center\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td>\n";
        $this->salida .= "				<fieldset><legend class=\"normal_10AN\">INFORMACION DEL DOCUMENTO:</legend>\n";
        $this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "						<tr height= \"18\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "							<td $est>FECHA ULTIMO RECIBO:</td>\n";
        $this->salida .= "							<td $est align=\"left\"class=\"modulo_list_claro\">" . $this->DatosRecibo['fecha_limite'] . "\n";
        $this->salida .= "								<input type=\"hidden\" name=\"fecha_limite\" size=\"12\" value=\"" . $this->DatosRecibo['fecha_limite'] . "\">\n";
        $this->salida .= "							</td>\n";
        $this->salida .= "						</tr>\n";
        $this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "							<td $est>FECHA REGISTRO:</td>\n";
        $this->salida .= "							<td $est class=\"modulo_list_claro\" align=\"left\" valign=\"top\" >\n";
        $this->salida .= "								<input type=\"text\" class=\"input-text\" name=\"fecha_registro\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"" . $this->DatosRecibo['fecha_registro'] . "\">\n";
        $this->salida .= "									" . ReturnOpenCalendario('modificarNota', 'fecha_registro', '/') . "\n";
        $this->salida .= "							</td>\n";
        $this->salida .= "						</tr>\n";
        $this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "							<td height= \"18\" colspan=\"2\">OBSERVACION</td>\n";
        $this->salida .= "						</tr>\n";
        $this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" >\n";
        $this->salida .= "								<textarea style=\"width:100%\" rows=\"3\" class=\"textarea\" name=\"observa\" >" . $this->DatosRecibo['observacion'] . "</textarea>\n";
        $this->salida .= "							</td>\n";
        $this->salida .= "						</tr>\n";
        $this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "							<td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\">\n";
        $this->salida .= "								<input type=\"submit\" class=\"input-submit\" value=\"Modificar Informacion\">\n";
        $this->salida .= "							</td>\n";
        $this->salida .= "						</tr>\n";
        $this->salida .= "					</table>\n";
        $this->salida .= "				</fieldset>\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= "</form><br>\n";
        $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
        $this->salida .= "		<tr><td align=\"center\">\n";
        $this->salida .= "			<form name=\"volver\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "			</form>\n";
        $this->salida .= "		</td></tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    /*     * ******************************************************************************** 
     * Funcion que retorna el mensaje que se desea desplegar en la forma 
     * 
     * @return String cadena con el mensaje 
     * ********************************************************************************* */

    function SetStyle($campo = "MensajeError")
    {
        if ($this->frmError[$campo])
        {
            if ($campo == "MensajeError" || $campo == "MensajeError2")
            {
                return ("<tr><td class='label_error' colspan='3' align='center'>" . $this->frmError[$campo] . "</td></tr>");
            }
            else if ($campo != "")
            {
                $mensaje .= "	<tr>\n";
                $mensaje .= "		<td width=\"19\"><img src=\"" . GetThemePath() . "/images/infor.png\" border=\"0\"></td>\n";
                $mensaje .= "		<td class=\"label\" align=\"justify\">" . $this->frmError[$campo] . "</td>\n";
                $mensaje .= "	</tr>\n";

                return $mensaje;
            }
            return ("<tr><td>&nbsp;</td></tr>");
        }
        return ("<tr><td>&nbsp;</td></tr>");
    }

    /*     * ******************************************************************************
     * Funcion donde se realiza la forma del buscador de recibos de caja de tesoreria
     * o las notas de ajuste segun sea la opcion
     * 
     * @return string forma del buscador 
     * ******************************************************************************* */

    function BuscadorRecibosCerrados()
    {
        $buscador = "<form name=\"buscador\" action=\"" . $this->action3 . "\" method=\"post\">\n";
        $buscador .= "	<script>\n";
        $buscador .= "		function acceptDate(evt)\n";
        $buscador .= "		{\n";
        $buscador .= "			var nav4 = window.Event ? true : false;\n";
        $buscador .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
        $buscador .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
        $buscador .= "		}\n";
        $buscador .= "		function acceptNum(evt)\n";
        $buscador .= "		{\n";
        $buscador .= "			var nav4 = window.Event ? true : false;\n";
        $buscador .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
        $buscador .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
        $buscador .= "		}\n";
        $buscador .= "		function limpiarCampos(objeto)\n";
        $buscador .= "		{\n";
        $buscador .= "			objeto.fecha_fin.value = \"\";\n";
        $buscador .= "			objeto.fecha_inicio.value = \"\";\n";
        $buscador .= "			objeto.numero_recibo.value = \"\";\n";
        $buscador .= "		}\n";
        $buscador .= "	</script>\n";
        $buscador .= "	<fieldset  class=\"fieldset\"><legend>BUSCADOR AVANZADO</legend>\n";
        $buscador .= "		<table>\n";
        $buscador .= "			<tr class=\"normal_10AN\" >\n";
        $buscador .= "				<td >RECIBO N</td>\n";
        $buscador .= "				<td colspan=\"2\">\n";
        $buscador .= "					<input type=\"text\" class=\"input-text\" name=\"numero_recibo\" size=\"12\" maxlength=\"32\" onkeypress=\"return acceptNum(event)\" value=\"" . $this->RNNumero . "\">\n";
        $buscador .= "				</td>\n";
        $buscador .= "			</tr>\n";
        $buscador .= "			<tr class=\"normal_10AN\">\n";
        $buscador .= "				<td >FECHA INICIO</td>\n";
        $buscador .= "				<td >\n";
        $buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"" . $this->FechaInicio . "\">\n";
        $buscador .= "				</td>\n";
        $buscador .= "				<td >" . ReturnOpenCalendario('buscador', 'fecha_inicio', '/') . "</td>\n";
        $buscador .= "			</tr>\n";
        $buscador .= "			<tr class=\"normal_10AN\">\n";
        $buscador .= "				<td >FECHA FIN</td>\n";
        $buscador .= "				<td >\n";
        $buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_fin\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"" . $this->FechaFin . "\">\n";
        $buscador .= "				</td>\n";
        $buscador .= "				<td >" . ReturnOpenCalendario('buscador', 'fecha_fin', '/') . "</td>\n";
        $buscador .= "			</tr>\n";
        $buscador .= "			<tr class=\"normal_10AN\">\n";
        $buscador .= "				<td >RESPONSABLE</td>\n";
        $buscador .= "				<td colspan=\"2\" >\n";
        $buscador .= "					<select name=\"usuario\" class=\"select\">\n";
        $buscador .= "						<option value='0'>-----SELECCIONAR-----</option>\n";

        $responsable = $this->ObtenerUsuariosRecibos();
        for ($i = 0; $i < sizeof($responsable); $i++)
        {
            $opciones = $responsable[$i];
            ($this->Usuario == $opciones['usuario_id']) ? $sel = " selected " : $sel = "";

            $buscador .= "						<option value='" . $opciones['usuario_id'] . "' $sel >" . $opciones['nombre'] . "</option>\n";
        }

        $buscador .= "					</select>\n";
        $buscador .= "				</td>\n";
        $buscador .= "			</tr>\n";
        $buscador .= "			<tr>\n";
        $buscador .= "				<td class=\"label\" align=\"center\" colspan=\"3\"><br>\n";
        $buscador .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
        $buscador .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
        $buscador .= "				</td>\n";
        $buscador .= "			</tr>\n";
        $buscador .= "		</table>\n";
        $buscador .= "	</fieldset>\n";
        $buscador .= "</form>\n";

        return $buscador;
    }

    /*     * ***********************************************************************************
     * Funcion donde se realiza la forma del buscador de terceros 
     * 
     * @return string forma del buscador 
     * ************************************************************************************ */

    function BuscadorTerceros()
    {

        $buscador = "<form name=\"buscador\" action=\"" . $this->actionB . "\" method=\"post\">\n";
        $buscador .= "	<script>\n";
        $buscador .= "		function limpiarCampos(objeto)\n";
        $buscador .= "		{\n";
        $buscador .= "			objeto.nombre_tercero.value = \"\";\n";
        $buscador .= "			objeto.tercero_id.value = \"\";\n";
        $buscador .= "			objeto.tipo_id_tercero.selectedIndex='0';\n";
        $buscador .= "		}\n";
        $buscador .= "	</script>\n";
        $buscador .= "	<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
        $buscador .= "		<table>\n";
        $buscador .= "			<tr><td class=\"label\">TIPO DOCUMENTO CLIENTE</td>\n";
        $buscador .= "				<td>\n";
        $buscador .= "					<select name=\"tipo_id_tercero\" class=\"select\">\n";
        $buscador .= "						<option value='0'>-----SELECCIONAR-----</option>\n";

        $TiposTerceros = $this->ObtenerTipoIdTerceros();
        for ($i = 0; $i < sizeof($TiposTerceros); $i++)
        {
            $selected = "";
            $opciones = explode("/", $TiposTerceros[$i]);
            if ($this->TerceroTipoId == $opciones[0])
            {
                $selected = " selected ";
            }
            $buscador .= "						<option value='" . $opciones[0] . "' $selected >" . ucwords(strtolower($opciones[1])) . "</option>\n";
        }

        $buscador .= "					</select>\n";
        $buscador .= "				</td>\n";
        $buscador .= "			</tr>\n";
        $buscador .= "			<tr>\n";
        $buscador .= "				<td class=\"label\">DOCUMENTO</td>\n";
        $buscador .= "				<td>\n";
        $buscador .= "					<input type=\"text\" class=\"input-text\" name=\"tercero_id\" size=\"30\" maxlength=\"32\" value=\"" . $this->TerceroDocumento . "\">\n";
        $buscador .= "				</td>\n";
        $buscador .= "			</tr>\n";
        $buscador .= "			<tr>\n";
        $buscador .= "				<td class=\"label\">NOMBRE</td>\n";
        $buscador .= "				<td>\n";
        $buscador .= "					<input type=\"text\" class=\"input-text\" name=\"nombre_tercero\" size=\"30\" maxlength=\"100\" value=\"" . $this->TerceroNombre . "\">\n";
        $buscador .= "				</td>\n";
        $buscador .= "			</tr>\n";
        $buscador .= "			<tr>\n";
        $buscador .= "				<td class=\"label\" align=\"center\" colspan=\"2\"><br>\n";
        $buscador .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
        $buscador .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador)\">\n";
        $buscador .= "				</td>\n";
        $buscador .= "			</tr>\n";
        $buscador .= "		</table>\n";
        $buscador .= "	</fieldset>\n";
        $buscador .= "</form>\n";

        return $buscador;
    }

    /*     * ***********************************************************************************
     * Funcion donde se realiza la forma del buscador de facturas envio 
     * 
     * @return string forma del buscador 
     * ************************************************************************************ */

    function BuscadorEnviosFacturas()
    {
        $buscador = "<form name=\"buscador2\" action=\"" . $this->actionB . "\" method=\"post\">\n";
        $buscador .= "	<script>\n";
        $buscador .= "		function limpiarCampos(objeto)\n";
        $buscador .= "		{\n";
        $buscador .= "			objeto.numero.value = \"\";\n";
        $buscador .= "		}\n";
        $buscador .= "		function acceptNum(evt)\n";
        $buscador .= "		{\n";
        $buscador .= "			var nav4 = window.Event ? true : false;\n";
        $buscador .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
        $buscador .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
        $buscador .= "		}\n";
        $buscador .= "	</script>\n";
        $buscador .= "	<table class=\"modulo_table_list\" width=\"100%\">\n";
        $buscador .= "		<tr><td class=\"modulo_table_list_title\">\n";
        $buscador .= "				BUSCADOR:&nbsp;\n";
        $buscador .= "			</td>\n";
        $buscador .= "			<td>\n";
        $buscador .= "				<select name=\"combo\" class=\"select\">\n";
        $buscador .= "					<option value='01' >ENVIO</option>\n";

        $Filas = $this->ObtenerPrefijos();
        for ($i = 0; $i < sizeof($Filas); $i++)
        {
            if ($this->ComboBSQ == $Filas[$i])
                $buscador .= "				<option value='" . $Filas[$i] . "' selected>" . $Filas[$i] . "</option>\n";
            else
                $buscador .= "				<option value='" . $Filas[$i] . "' >" . $Filas[$i] . "</option>\n";
        }
        $buscador .= "				</select>\n";
        $buscador .= "			</td>\n";
        $buscador .= "			<td>\n";
        $buscador .= "				<b class=\"label_mark\">NUMERO: </b>";
        $buscador .= "				<input type=\"text\" class=\"input-text\" name=\"numero\" size=\"25\" maxlength=\"10\" onkeypress=\"return acceptNum(event)\" value=\"" . $this->FacturaFiscal . "\">\n";
        $buscador .= "			</td>\n";
        $buscador .= "			<td>\n";
        $buscador .= "				<input type=\"hidden\"  name=\"valor_rc_traslado\" value='{$_REQUEST['valor_rc_traslado']}'>\n";
        $buscador .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
        $buscador .= "				<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"limpiarCampos(document.buscador2)\">\n";
        $buscador .= "		</td></tr>\n";
        $buscador .= "	</table>\n";
        $buscador .= "</form>\n";
        $buscador .= "<table width=\"90%\" align=\"center\">\n";
        $buscador .= "	<tr><td align=\"center\">\n";
        $buscador .= "		<form name=\"volver\" action=\"" . $this->action2 . "\" method=\"post\">\n";
        $buscador .= "			<input type=\"hidden\"  name=\"valor_rc_traslado\" value='{$_REQUEST['valor_rc_traslado']}'>\n";
        $buscador .= "			<input type=\"submit\" class=\"input-submit\" value=\"Todas Las Facturas\">\n";
        $buscador .= "		</form>\n";
        $buscador .= "	</td></tr>\n";
        $buscador .= "</table>\n";

        return $buscador;
    }

    /*     * ******************************************************************************* 
     * Funcion que presenta una forma de informacion al usario sobre lo que ha acabado 
     * de ocurrir con la accion que realizo 
     * 
     * @return boolean 
     * ******************************************************************************** */

    function FormaInformacion($parametro, $cerrar = false)
    {
        
        $this->salida .= ThemeAbrirTabla('INFORMACION');
        $this->salida .= "<script>\n";
        $this->salida .= "	function finMes(nMes)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		var nRes = 0;\n";
        $this->salida .= "		switch (nMes)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			case '01': nRes = 31; break;\n";
        $this->salida .= "			case '02': nRes = 29; break;\n";
        $this->salida .= "			case '03': nRes = 31; break;\n";
        $this->salida .= "			case '04': nRes = 30; break;\n";
        $this->salida .= "			case '05': nRes = 31; break;\n";
        $this->salida .= "			case '06': nRes = 30; break;\n";
        $this->salida .= "			case '07': nRes = 31; break;\n";
        $this->salida .= "			case '08': nRes = 31; break;\n";
        $this->salida .= "			case '09': nRes = 30; break;\n";
        $this->salida .= "			case '10': nRes = 31; break;\n";
        $this->salida .= "			case '11': nRes = 30; break;\n";
        $this->salida .= "			case '12': nRes = 31; break;\n";
        $this->salida .= "		}\n";
        $this->salida .= "		return nRes;\n";
        $this->salida .= "	}\n";
        $this->salida .= "	function IsDate(fecha)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		var bol = true;\n";
        $this->salida .= "		var arr = fecha.split('/');\n";
        $this->salida .= "		if(arr.length > 3)\n";
        $this->salida .= "			return false;\n";
        $this->salida .= "		else\n";
        $this->salida .= "		{\n";
        $this->salida .= "			bol = bol && (IsNumeric(arr[0]));\n";
        $this->salida .= "			bol = bol && (IsNumeric(arr[1]));\n";
        $this->salida .= "			bol = bol && (IsNumeric(arr[2]));\n";
        $this->salida .= "			bol = bol && ((arr[1] >= 1) && (arr[1] <= 12));\n";
        $this->salida .= "			bol = bol && (arr[0] <= finMes(arr[1]));\n";
        $this->salida .= "			return bol;\n";
        $this->salida .= "		}\n";
        $this->salida .= "	}\n";
        $this->salida .= "	function acceptDate(evt)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		var nav4 = window.Event ? true : false;\n";
        $this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
        $this->salida .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
        $this->salida .= "	}\n";
        $this->salida .= "	function IsNumeric(valor)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		var log = valor.length; \n";
        $this->salida .= "		var sw='S';\n";
        $this->salida .= "		var puntos = 0;\n";
        $this->salida .= "		for (x=0; x<log; x++)\n";
        $this->salida .= "		{ \n";
        $this->salida .= "			v1 = valor.substr(x,1);\n";
        $this->salida .= "			v2 = parseInt(v1);\n";
        $this->salida .= "			//Compruebo si es un valor numerico\n";
        $this->salida .= "			if(v1 == '.')\n";
        $this->salida .= "			{\n";
        $this->salida .= "				puntos ++;\n";
        $this->salida .= "			}\n";
        $this->salida .= "			else if (isNaN(v2)) \n";
        $this->salida .= "			{ \n";
        $this->salida .= "				sw= 'N';\n";
        $this->salida .= "				break;\n";
        $this->salida .= "			}\n";
        $this->salida .= "		}\n";
        $this->salida .= "		if(log == 0) sw = 'N';\n";
        $this->salida .= "		if(puntos > 1) sw = 'N';\n";
        $this->salida .= "		if(sw=='S')\n";
        $this->salida .= "			return true;\n";
        $this->salida .= "		return false;\n";
        $this->salida .= "	} \n";
        $this->salida .= "	function fechaMayorOIgualQue(fec0, fec1)\n";
        $this->salida .= "	{\n";
        $this->salida .= "		var bRes = false; \n";
        $this->salida .= "		var sDia0 = fec0.substr(0, 2); \n";
        $this->salida .= "		var sMes0 = fec0.substr(3, 2); \n";
        $this->salida .= "		var sAno0 = fec0.substr(6, 4); \n";
        $this->salida .= "		var sDia1 = fec1.substr(0, 2); \n";
        $this->salida .= "		var sMes1 = fec1.substr(3, 2); \n";
        $this->salida .= "		var sAno1 = fec1.substr(6, 4); \n";
        $this->salida .= "		if (sAno0 > sAno1) bRes = true; \n";
        $this->salida .= "		else { \n";
        $this->salida .= "		if (sAno0 == sAno1){ \n";
        $this->salida .= "			if (sMes0 > sMes1) bRes = true; \n";
        $this->salida .= "		  else { \n";
        $this->salida .= "		    if (sMes0 == sMes1) \n";
        $this->salida .= "		     if (sDia0 >= sDia1) bRes = true; \n";
        $this->salida .= "		  } \n";
        $this->salida .= "		 } \n";
        $this->salida .= "		} \n";
        $this->salida .= "		return bRes; \n";
        $this->salida .= "	}\n";
        $this->salida .= "	function Aceptar(frm)\n";
        $this->salida .= "	{\n";
        if ($cerrar)
        {
            $this->salida .= "		if(!IsDate(frm.fecha_registro.value))\n";
            $this->salida .= "		{\n";
            $this->salida .= "			document.getElementById('error').innerHTML='FORMATO DE FECHA INCORRECTO';\n";
            $this->salida .= "			return;\n";
            $this->salida .= "		}\n";
            $this->salida .= "		if(!fechaMayorOIgualQue(frm.fecha_registro.value,'" . $this->DatosRecibo['fecha_limite'] . "' ))\n";
            $this->salida .= "		{\n";
            $this->salida .= "			document.getElementById('error').innerHTML='LA FECHA DE REGISTRO DEBE SER MAYOR O IGUAL A LA FECHA ULTIMO RECIBO';\n";
            $this->salida .= "			return;\n";
            $this->salida .= "		}\n";
        }
        $this->salida .= "		frm.action = \"" . $this->action . "&rc_traslado=" . $_REQUEST['rc_traslado'] . "\"\n";
        $this->salida .= "		frm.submit()\n";
        $this->salida .= "	}\n";
        $this->salida .= "</script>";
        $this->salida .= "	<form name=\"formaInformacion\" action=\"javascript:Aceptar(document.formaInformacion)\" method=\"post\">\n";
        $this->salida .= "		<center>\n";
        $this->salida .= "			<div class=\"label_error\" id=\"error\"></div>\n";
        $this->salida .= "		</center>\n";
        $this->salida .= "		<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
        $this->salida .= "			<tr><td class=\"label\" colspan=\"3\" align=\"center\" ><br>";
        $this->salida .= "				" . $parametro . "<br>\n";
        $this->salida .= "			</td></tr>\n";
        $this->salida .= "		</table>\n";
        if ($cerrar)
        {
            $this->salida .= "		<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
            $this->salida .= "			<tr height= \"18\" class=\"modulo_table_list_title\">\n";
            $this->salida .= "				<td $est>FECHA ULTIMO RECIBO:</td>\n";
            $this->salida .= "				<td $est align=\"left\"class=\"modulo_list_claro\">" . $this->DatosRecibo['fecha_limite'] . "\n";
            $this->salida .= "				</td>\n";
            $this->salida .= "			</tr>\n";
            $this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "				<td $est> FECHA REGISTRO:</td>\n";
            $this->salida .= "				<td $est class=\"modulo_list_claro\" align=\"left\" valign=\"top\" >\n";
            $this->salida .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_registro\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"" . $this->DatosRecibo['fecha_registro'] . "\">\n";
            $this->salida .= "					" . ReturnOpenCalendario('formaInformacion', 'fecha_registro', '/') . "\n";
            $this->salida .= "				</td>\n";
            $this->salida .= "			</tr>\n";
            $this->salida .= "		</table>\n";
        }
        $this->salida .= "		<table align=\"center\" width=\"60%\">\n";
        $this->salida .= "			<tr><td align=\"center\">\n";
        $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
        $this->salida .= "			</td></form>\n";
        /*
          echo "actionM: ".$this->actionM."<br><br>";
          echo "action: ".$this->action;

          echo 'Request:: <pre>';
          print_r($_REQUEST);
          echo '</pre>';
         */
        if ($this->actionM)
        {
            $this->salida .= "		<form name=\"cancelar\" action=\"" . $this->actionM . "\" method=\"post\">\n";
            $this->salida .= "			<td align=\"center\">\n";
            $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
            $this->salida .= "			</td></form>\n";
        }
        $this->salida .= "		</tr></table>\n";
        $this->salida .= "	\n";
        $this->salida .= ThemeCerrarTabla();

        return true;
    }

    /*     * ******************************************************************************* 
     * Funcion que presenta una forma de informacion al usario sobre lo que ha acabado 
     * de ocurrir con la accion que realizo 
     * 
     * @return boolean 
     * ******************************************************************************** */

    function FormaCruzarNotasCredito()
    {
        $this->salida .= ThemeAbrirTabla('CRUCE NOTAS');

        $this->salida .= "	<script>\n";
        $this->salida .= "		function MostrarInformacion(objeto)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			nota = objeto.notascredito.value.split(\"*\");\n";
        $this->salida .= "			objeto.valor.value = nota[0];\n";
        $this->salida .= "			objeto.observacion.value = nota[1];\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function acceptNum(evt)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			var nav4 = window.Event ? true : false;\n";
        $this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
        $this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
        $this->salida .= "		}\n";
        $this->salida .= "	</script>\n";
        $this->salida .= "		<table align=\"center\" width=\"60%\">\n";
        $this->salida .= "			" . $this->SetStyle($this->parametro) . "\n";
        $this->salida .= "		</table>\n";
        $this->salida .= "		<table align=\"center\" width=\"60%\">\n";
        $this->salida .= "			" . $this->SetStyle("MensajeError") . "\n";
        $this->salida .= "		</table>\n";
        if (sizeof($this->NotasC) > 0)
        {
            $this->salida .= "<form name=\"adicionarnota\" action=\"" . $this->action2 . "\" method=\"post\">\n";
            $this->salida .= "	<table class=\"modulo_table_list\" width=\"60%\" align=\"center\">\n";
            $this->salida .= "		<tr>\n";
            $this->salida .= "			<td width=\"25%\" class=\"modulo_table_list_title\">NOTA DE AJUSTE:</td>\n";
            $this->salida .= "			<td width=\"25%\" >\n";
            $this->salida .= "				<select name=\"notascredito\" class=\"select\" onChange=\"MostrarInformacion(document.adicionarnota);\">\n";
            $this->salida .= "					<option value='0' >-SELECCIONAR-</option>\n";

            for ($i = 0; $i < sizeof($this->NotasC); $i++)
            {
                ($this->ComboBSQ == $this->NotasC[$i]) ? $sel = "selected" : $sel = "";
                $valor = $this->NotasC[$i]['valor'] . "*" . $this->NotasC[$i]['observacion'] . "*" . $this->NotasC[$i]['prefijo'] . "*" . $this->NotasC[$i]['nota_credito_ajuste'];
                $this->salida .= "					<option value='" . $valor . "' $sel>" . $this->NotasC[$i]['prefijo'] . " " . $this->NotasC[$i]['nota_credito_ajuste'] . "</option>\n";
            }
            $this->salida .= "				</select>\n";
            $this->salida .= "			</td>\n";
            $this->salida .= "			<td width=\"25%\" class=\"modulo_table_list_title\">VALOR NOTA:</td>\n";
            $this->salida .= "			<td width=\"25%\">\n";
            $this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"valor\" size=\"25\" maxlength=\"10\" readonly value=\"" . $this->FacturaFiscal . "\">\n";
            $this->salida .= "			</td>\n";
            $this->salida .= "		</tr>\n";
            $this->salida .= "		<tr>\n";
            $this->salida .= "			<td colspan=\"4\" class=\"modulo_table_list_title\" >OBSERVACION:</td>\n";
            $this->salida .= "		</tr>\n";
            $this->salida .= "		<tr>\n";
            $this->salida .= "			<td colspan=\"4\">\n";
            $this->salida .= "				<textarea style=\"width:100%\" rows=\"2\" class=\"textarea\" name=\"observacion\" readonly >" . $this->Observacion . "</textarea>\n";
            $this->salida .= "			</td>\n";
            $this->salida .= "		</tr>\n";
            $this->salida .= "		<tr>\n";
            $this->salida .= "			<td colspan=\"4\" align=\"center\">\n";
            $this->salida .= "				<input type=\"submit\" class=\"input-submit\" name=\"adicionar\" value=\"Adicionar\">\n";
            $this->salida .= "			</td>\n";
            $this->salida .= "		</tr>\n";
            $this->salida .= "	</table><br>\n";
            $this->salida .= "</form>\n";
        }
        else
        {
            $this->salida .= "<br><center><b class=\"label_error\">NO HAY NOTAS DISPONIBLES PARA ESTA EMPRESA</b></center><br><br>\n";
        }
        if (sizeof($this->NotasCruzadas) > 0)
        {
            $this->salida .= "	<table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "			<td width=\"25%\"><b>NOTA</b></td>\n";
            $this->salida .= "			<td width=\"50%\"><b>OBSERVACIÃ–N</b></td>\n";
            $this->salida .= "			<td width=\"20%\"><b>VALOR</b></td>\n";
            $this->salida .= "			<td width=\"5%\"><b>X</b></td>\n";
            $this->salida .= "		</tr>\n";

            $total = 0;

            for ($i = 0; $i < sizeof($this->NotasCruzadas); $i++)
            {
                $Celdas = $this->NotasCruzadas[$i];

                $total += $Celdas['valor'];

                $opcion = "	<a href=\"" . $this->actionX[$i] . "\" >\n";
                $opcion .= "		<img src=\"" . GetThemePath() . "/images/delete.gif\" title=\"ELIMINAR CARGO\" border=\"0\">";
                $opcion .= "	</a>\n";

                $this->salida .= "		<tr>\n";
                $this->salida .= "			<td class=\"modulo_list_oscuro\"><b>" . $Celdas['prefijo_nota'] . " " . $Celdas['nota_credito_ajuste'] . "</b></td>\n";
                $this->salida .= "			<td class=\"modulo_list_oscuro\">" . $Celdas['observacion'] . "</td>\n";
                $this->salida .= "			<td class=\"modulo_list_claro\" align=\"right\"><b>" . formatoValor($Celdas['valor']) . "</b></td>\n";
                $this->salida .= "			<td class=\"modulo_list_claro\" align=\"center\">$opcion</td>\n";
                $this->salida .= "		</tr>\n";
            }

            $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "			<td align=\"left\" colspan=\"2\"><b>TOTALES</b></td>\n";
            $this->salida .= "			<td align=\"right\"><b>" . formatoValor($total) . "</b></td>\n";
            $this->salida .= "			<td align=\"center\"></td>\n";
            $this->salida .= "		</tr>\n";
            $this->salida .= "	</table><br>\n";
        }
        $this->salida .= "	<table align=\"center\" width=\"60%\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<form name=\"cancelar\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "				<td align=\"center\">\n";
        $this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "				</td>\n";
        $this->salida .= "			</form>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    /*     * ********************************************************************************
     *
     * ********************************************************************************* */

    function FormaBuscarTerceros()
    {
        $this->BuscarTerceros();
        $menu = SessionGetVar("Documentos");

        $this->salida .= ThemeAbrirTabla("TERCEROS");
        $this->salida .= "	<script>\n";
        $this->salida .= "		function Guardar(tipo_id_tercero,tercero_id,nombre,saldo,saldo2)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			window.opener.document.generarRC.tercero_id_endoso.value = tercero_id;\n";
        $this->salida .= "			window.opener.document.generarRC.tipo_id_tercero_endoso.value = tipo_id_tercero;\n";
        $this->salida .= "			window.opener.document.generarRC.terceroadd.value = 'Cambiar';\n";
        $this->salida .= "			window.opener.document.getElementById('nombre_tercero').innerHTML = nombre;\n";
        if ($menu['sw_cruzar_anticipos'] == '1' && $menu['sw_cruce_endosos'] == '1')
        {
            $this->salida .= "			window.opener.document.getElementById('valores_anticipos').innerHTML = saldo;\n";
            $this->salida .= "			window.opener.document.generarRC.valorsaldo.value = saldo2;\n";
        }
        $this->salida .= "			Cerrar();\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function Cerrar()\n";
        $this->salida .= "		{\n";
        $this->salida .= "			window.close();\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function mOvr(src,clrOver)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			src.style.background = clrOver;\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function mOut(src,clrIn)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			src.style.background = clrIn;\n";
        $this->salida .= "		}\n";
        $this->salida .= "	</script>\n";
        $this->salida .= "	<table width=\"70%\" align=\"center\" >\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td>\n";
        $this->salida .= $this->BuscadorTerceros();
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table><br>\n";
        $terceros = $this->ObtenerTerceros();
        if (sizeof($terceros) > 0)
        {
            $this->salida .= "	<table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "			<tr class=\"modulo_table_list_title\" height=\"19\">\n";
            $this->salida .= "				<td width=\"22%\"><b>DOCUMENTO</b></td>\n";
            $this->salida .= "				<td width=\"75%\"><b>NOMBRE CLIENTE</b></td>\n";
            $this->salida .= "				<td width=\"3%\" ><b>OPCIONES</b></td>\n";
            $this->salida .= "			</tr>";
            for ($i = 0; $i < sizeof($terceros); $i++)
            {
                if ($i % 2 == 0)
                {
                    $estilo = 'modulo_list_oscuro';
                    $background = "#CCCCCC";
                }
                else
                {
                    $estilo = 'modulo_list_claro';
                    $background = "#DDDDDD";
                }

                $Celdas = $terceros[$i];

                $opcion = "	<a class=\"label_error\" href=\"javascript:Guardar('" . $Celdas['tipo_id_tercero'] . "','" . $Celdas['tercero_id'] . "','" . $Celdas['nombre_tercero'] . "','" . FormatoValor($Celdas['saldo']) . "','" . $Celdas['saldo'] . "')\" title=\"SELECCIONAR\">\n";
                $opcion .= "	<img src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\"></a>\n";

                $this->salida .= "			<tr class=\"" . $estilo . "\" height=\"21\" onmouseout=mOut(this,\"" . $background . "\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
                $this->salida .= "				<td align=\"left\"   >" . $Celdas['tipo_id_tercero'] . " " . $Celdas['tercero_id'] . "</td>\n";
                $this->salida .= "				<td align=\"justify\">" . $Celdas['nombre_tercero'] . "</td>\n";
                $this->salida .= "				<td align=\"center\" >$opcion</td>\n";
                $this->salida .= "			</tr>\n";
            }
            $this->salida .= "	</table><br>\n";

            $Paginador = new ClaseHTML();
            $this->salida .= "		" . $Paginador->ObtenerPaginado($this->conteo, $this->paginaActual, $this->action1);
            $this->salida .= "		<br>\n";
        }

        $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td align=\"center\">\n";
        $this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Cerrar\" onclick=\"Cerrar()\" >\n";
        $this->salida .= "			</td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /*     * ******************************************************************************* 
     * Funcion que presenta una forma de informacion al usario sobre lo que ha acabado 
     * de ocurrir con la accion que realizo 
     * 
     * @return boolean 
     * ******************************************************************************** */

    function FormaCruceAutomatico()
    {
        IncludeClass('RecaudoElectronico_HTML', '', 'app', 'RecibosCaja');
        $RE = new RecaudoElectronico_HTML();
        $this->salida = $mostrar = $RE->RevisarFacturas();
        return true;
    }
	
	function SeleccionarReciboCSV($error = "", $success = "", $doc = "") 
	{
	
        $solo_lectura = "";
        $tercero_tipo = $_REQUEST['tercero_tipo'];
        $tercero_id = $_REQUEST['tercero_id'];
        $rc_detalles = $this->ObtenerRcDetalles($tercero_tipo, $tercero_id);
        $cantidad_rc_detalles = count($rc_detalles);
        $total_rc_detalles = 0;
        for ($var = 0; $var < count($rc_detalles); $var++)
        {
            $total_rc_detalles+= $rc_detalles[$var]['valor_actual'];
        }

		$solo_lectura = " readonly ";

        $this->salida .= ThemeAbrirTabla("DETALLE DEL DOCUMENTO");
        $this->salida .= "	<script>\n";
        $this->salida .= "		function acceptNum(evt)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			var nav4 = window.Event ? true : false;\n";
        $this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
        $this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function mOvr(src,clrOver)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			src.style.background = clrOver;\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function mOut(src,clrIn)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			src.style.background = clrIn;\n";
        $this->salida .= "		}\n";
                
        $this->salida .= "	</script>\n";

     	
        $saldo_anticipo_valor = $this->ObtnerSaldoAnticiposTercero($_SESSION['RCFactura']['empresa'], $_REQUEST['tercero_tipo'], $_REQUEST['tercero_id']);

        $this->salida .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td width='20%' class=\"modulo_table_list_title\">SALDO ANTICIPOS: </td>\n";
        $this->salida .= "			<td width='10%'> <b>$" . formatoValor($saldo_anticipo_valor['saldo']) . "</b></td>\n";
        $this->salida .= "			<td width='20%' class=\"modulo_table_list_title\">VALOR DOCUMENTO: </td>\n";
        $this->salida .= "			<td width='10%'> <b>$" . formatoValor($_REQUEST['valor_rc_traslado']) . "</b></td>\n";
        $this->salida .= "			<td width='25%' class=\"modulo_table_list_title\">VALOR TOTAL RECIBOS DE CAJA: </td>\n";
        $this->salida .= "			<td width='15%'> <b>$" . formatoValor($total_rc_detalles) . "</b></td>\n";
        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table><br>\n";

        if ($cantidad_rc_detalles < 1)
        {
            $this->salida .= "<p align='center' class='label_error'>LOS RECIBOS DE CAJA ESTAN CON SALDO EN 0 -CERO-..</p>";
        }
        else
        {
            $this->salida .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "			<td width='15%' colspan='3'> R E C I B O S &nbsp; D E &nbsp; C A J A </td>\n";
            $this->salida .= "		</tr>\n";
            $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                  <td>RECIBO CAJA</td>\n";
            $this->salida .= "                  <td>VALOR</td>\n";
            $this->salida .= "                  <td>EXCEDENTE</td>\n";
            $this->salida .= "		</tr>\n";
            for ($ste = 0; $ste < count($rc_detalles); $ste++)
            {
                if ($ste % 2 == 0)
                {
                    $estilo = 'modulo_list_oscuro';
                    $background = "#CCCCCC";
                }
                else
                {
                    $estilo = 'modulo_list_claro';
                    $background = "#DDDDDD";
                }
                $this->salida .= "		<tr class=\"" . $estilo . "\" onmouseout=mOut(this,\"" . $background . "\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
                $this->salida .= "                  <td width='20%' align='center'><b>" . $rc_detalles[$ste]['prefijo'] . " - " . $rc_detalles[$ste]['recibo_caja'] . "</b></td>\n";
                $this->salida .= "                  <td width='25%' align='center'><b>$" . formatoValor($rc_detalles[$ste]['valor_actual']) . "</b></td>\n";
                $this->salida .= "                  <td width='25%' align='center'><input class='input-text' type='text' name='rc_general' id='rc_" . $ste . "' value='" . (int) ($rc_detalles[$ste]['valor_actual']) . "' onChange='sumatoriaTotalExcedentes()' readonly/></td>\n";
                $this->salida .= "		</tr>\n";
            }
            $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                  <td align='right'><b>TOTAL: </b></td>\n";
            $this->salida .= "                  <td align='center'><b>$" . formatoValor($total_rc_detalles) . "</b></td>\n";
            $this->salida .= "                  <td align='center'><input type='text' class='input-text' name='rc_total' id='rc_total_excedente' value='" . $total_rc_detalles . "' /></td>\n";
            $this->salida .= "		</tr>\n";
            $this->salida .= "	</table><br>\n";
        }

				/*--------------------------------------GERMAN----------------------------------*/
		
		//$subirDocumento = ModuloGetURL('app', 'RecibosCaja', 'user', 'subirArchivo');
        
        $this->salida .= "<form name=\"documento\" enctype=\"multipart/form-data\" action=\"" . $this->actionCarga . "\" method=\"post\">\n";
        $this->salida .= "    <table width=\"35%\" align=\"center\" class=\"modulo_table_list\">\n";
		
	if ($error != "") {
            $this->salida .= "<tr align='center'>
					<td colspan='8' align=\"center\"><center style='color:red;'>{$error}</center></td>
				 </tr>";
        }

        if ($success != "") {
            $this->salida .= "<tr align='center'>
					<td colspan='8' align=\"center\"><center style='color:blue;'>{$success}</center></td>
				 </tr>";
        }

        if ($doc != "") {
            $this->salida .= "<tr>
					<td colspan='8' align=\"center\"><a href='{$doc}' target='_blank' >Descargar plano de productos con error</a></td>
				 </tr>";
        }

		$this->salida.="<tr>";
		$this->salida.="<td class=\"modulo_table_list_title\" align=\"center\" colspan='10'>ESTRUCTURA ARCHIVO PLANO (separado por ';')</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_list_claro\">";
		$this->salida.="<td align=\"center\" colspan='1'>Prefijo Factura</td>";
		$this->salida.="<td align=\"center\" colspan='1'>Numero Factura </td>";
		$this->salida.="<td align=\"center\" colspan='1'>Prefijo Recibo</td>";
		$this->salida.="<td align=\"center\" colspan='1'>Numero Recibo </td>";
		$this->salida.="<td align=\"center\" colspan='1'>Valor Abonado</td>";
		$this->salida.="<td align=\"center\" colspan='1'>Retefuente</td>";
		$this->salida.="<td align=\"center\" colspan='1'>ReteCre</td>";
		$this->salida.="<td align=\"center\" colspan='1'>ReteIca </td>";
		$this->salida.="</tr>"; 
		
		
        $this->salida .= "      <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"8\">CARGAR ARCHIVO CSV</td></tr>\n";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "        <td align=\"center\" colspan='4'\"\"><input type=\"file\" class=\"input-submit\" name=\"archivo\" value=\"Subir archivo\"></td>";
        $this->salida .= "      <td colspan='4' align=\"center\" width=\"50%\">\n";
        $this->salida .= "        <input type=\"submit\" class=\"input-submit\" value=\"Cargar\"/>";
        $this->salida .= "      </td>\n";
        $this->salida .= "</tr>\n";
        $this->salida .= "    </table>\n";
        $this->salida .= "</form>";
        $this->salida .= "<br>";
		
		
		/*--------------------------------------GERMAN----------------------------------*/
		

        $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
        $this->salida .= "		<tr><td align=\"center\">\n";
        $this->salida .= "			<form name=\"volver\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "			</form>\n";
        $this->salida .= "		</td></tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

    /*     * ********************************************************************************
     * 
     * ******************************************************************************** */

    function FormaSinRecibosCSV($error = "", $success = "", $doc = "") {


        if (trim($_REQUEST['recibo_caja']) != '') {
            $_SESSION['RC']['recibo_caja'] = $_REQUEST['recibo_caja'];
        }

        if (trim($_REQUEST['valor_rc_traslado']) != '') {
            $_SESSION['RC']['valor_rc_traslado'] = $_REQUEST['valor_rc_traslado'];
        }

        if (trim($_REQUEST['rc_debito']) != '') {
            $_SESSION['RC']['rc_debito'] = $_REQUEST['rc_debito'];
        }

        if (trim($_REQUEST['rc_credito']) != '') {
            $_SESSION['RC']['rc_credito'] = $_REQUEST['rc_credito'];
        }

        $recibo_caja = $_SESSION['RC']['recibo_caja'];
        $valor_rc_traslado = $_SESSION['RC']['valor_rc_traslado'];
        $rc_debito = $_SESSION['RC']['rc_debito'];
        $rc_credito = $_SESSION['RC']['rc_credito'];


        $suma_abonos = $this->ObtenerAbonos($recibo_caja, $_SESSION['Documentos']['sw_nota_cartera']);
        $valor_a_cuadrar = ($valor_rc_traslado * 1) - ($suma_abonos * 1);

        $solo_lectura = "";
        $tercero_tipo = $_REQUEST['tercero_tipo'];
        $tercero_id = $_REQUEST['tercero_id'];
        $empresa = $_REQUEST['empresa'];
        $rc_detalles = $this->ObtenerRcDetalles($empresa, $tercero_tipo, $tercero_id);
        $cantidad_rc_detalles = count($rc_detalles);
        $total_rc_detalles = 0;
        for ($var = 0; $var < count($rc_detalles); $var++) {
            $total_rc_detalles += $rc_detalles[$var]['valor_actual'];
        }

        $this->salida .= ThemeAbrirTabla("DETALLE DEL DOCUMENTO");
        $this->salida .= "	<script>\n";
        $this->salida .= "		function acceptNum(evt)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			var nav4 = window.Event ? true : false;\n";
        $this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
        $this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function mOvr(src,clrOver)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			src.style.background = clrOver;\n";
        $this->salida .= "		}\n";
        $this->salida .= "		function mOut(src,clrIn)\n";
        $this->salida .= "		{\n";
        $this->salida .= "			src.style.background = clrIn;\n";
        $this->salida .= "		}\n";

        $this->salida .= "	</script>\n";

        $saldo_anticipo_valor = $this->ObtnerSaldoAnticiposTercero($_SESSION['RCFactura']['empresa'], $_REQUEST['tercero_tipo'], $_REQUEST['tercero_id']);

        $this->salida .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "		<tr>\n";
        $this->salida .= "			<td width='15%' class=\"modulo_table_list_title\">SALDO ANTICIPOS-12-: </td>\n";
        $this->salida .= "			<td width='10%'> <b>$" . formatoValor($saldo_anticipo_valor['saldo']) . "</b></td>\n";
        $this->salida .= "			<td width='15%' class=\"modulo_table_list_title\">VLR DOCUMENTO: </td>\n";
        $this->salida .= "			<td width='10%'> <b>$" . formatoValor($valor_rc_traslado) . "</b>";
        $this->salida .= "                          <input type='hidden' name='valor_rc_traslado' id='valor_rc_traslado' value='" . $valor_rc_traslado . "'>\n";
        $this->salida .= "			</td>\n";

        $this->salida .= "			<td width='15%' class=\"modulo_table_list_title\">EXCEDENTE: </td>\n";
        $this->salida .= "			<td width='10%'> <b>$" . formatoValor($valor_a_cuadrar) . "</b>";
        $this->salida .= "                          <input type='hidden' name='valor_a_cuadrar' id='valor_a_cuadrar' value='" . $valor_a_cuadrar . "'>\n";
        $this->salida .= "			</td>\n";

        $this->salida .= "			<td width='20%' class=\"modulo_table_list_title\">VLR TOT RECIBOS DE CAJA: </td>\n";
        $this->salida .= "			<td width='10%'> <b>$" . formatoValor($total_rc_detalles) . "</b></td>\n";

        $this->salida .= "		</tr>\n";
        $this->salida .= "	</table><br>\n";


						/*--------------------------------------GERMAN----------------------------------*/
		
		//$subirDocumento = ModuloGetURL('app', 'RecibosCaja', 'user', 'subirArchivo');
        
        $this->salida .= "<form name=\"documento\" enctype=\"multipart/form-data\" action=\"" . $this->actionCarga . "\" method=\"post\">\n";
        $this->salida .= "    <table width=\"35%\" align=\"center\" class=\"modulo_table_list\">\n";
		
	if ($error != "") {
            $this->salida .= "<tr align='center'>
					<td colspan='6' align=\"center\"><center style='color:red;'>{$error}</center></td>
				 </tr>";
        }

        if ($success != "") {
            $this->salida .= "<tr align='center'>
					<td colspan='6' align=\"center\"><center style='color:blue;'>{$success}</center></td>
				 </tr>";
        }

        if ($doc != "") {
            $this->salida .= "<tr>
					<td colspan='6' align=\"center\" ><a href='{$doc}' target='_blank' >Descargar plano de productos con error</a></td>
				 </tr>";
        }

		
		$this->salida.="<tr>";
		$this->salida.="<td class=\"modulo_table_list_title\" align=\"center\" colspan='10'>ESTRUCTURA ARCHIVO PLANO (separado por ';')</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"modulo_list_claro\">";
		$this->salida.="<td align=\"center\" colspan='1'>Prefijo Factura</td>";
		$this->salida.="<td align=\"center\" colspan='1'>Numero Factura </td>";
		$this->salida.="<td align=\"center\" colspan='1'>Valor Abonado</td>";
		$this->salida.="<td align=\"center\" colspan='1'>Retefuente</td>";
		$this->salida.="<td align=\"center\" colspan='1'>ReteCre</td>";
		$this->salida.="<td align=\"center\" colspan='1'>ReteIca </td>";
		$this->salida.="</tr>"; 
		
		
        $this->salida .= "      <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"6\">CARGAR ARCHIVO CSV</td></tr>\n";
        $this->salida .= "      <tr class=\"modulo_list_claro\">";
        $this->salida .= "        <td align=\"center\" colspan='3'\"\"><input type=\"file\" class=\"input-submit\" name=\"archivo\" value=\"Subir archivo\"></td>";
        $this->salida .= "      <td colspan='3' align=\"center\" width=\"50%\">\n";
        $this->salida .= "        <input type=\"submit\" class=\"input-submit\" value=\"Cargar\"/>";
        $this->salida .= "      </td>\n";
        $this->salida .= "</tr>\n";
        $this->salida .= "    </table>\n";
        $this->salida .= "</form>";
        $this->salida .= "<br>";
		
		
		/*--------------------------------------GERMAN----------------------------------*/
		
		
        if ($cantidad_rc_detalles < 1) {
            $this->salida .= "<p align='center' class='label_error'>LOS RECIBOS DE CAJA ESTAN CON SALDO EN 0 -CERO-.</p>";
        }


        $this->salida .= "	<table width=\"90%\" align=\"center\">\n";
        $this->salida .= "		<tr><td align=\"center\">\n";
        $this->salida .= "			<form autocomplete='off' name=\"volver\" action=\"" . $this->action1 . "\" method=\"post\">\n";
        $this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "			</form>\n";
        $this->salida .= "		</td></tr>\n";
        $this->salida .= "	</table>\n";
        $this->salida .= ThemeCerrarTabla();
    }

}

?>