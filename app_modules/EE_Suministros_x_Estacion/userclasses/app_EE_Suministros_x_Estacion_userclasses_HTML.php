<?php

/**
 * $Id: app_EE_Suministros_x_Estacion_userclasses_HTML.php,v 1.4 2005/12/23 16:24:37 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_Suministros_x_Estacion_userclasses_HTML extends app_EE_Suministros_x_Estacion_user
{
     /**
     * Constructor
     *
     * @return boolean
     */
     function app_EE_Suministros_x_Estacion_user_HTML()
     {
          $this->app_EE_Suministros_x_Estacion_user();
          $this->salida='';
          return true;
     }
     
     
     /**
     * Metodo Default
     *
     * @return boolean
     */
     function main()
     {
          $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $titulo='FALTA METODO EN EL LLAMADO';
          $mensaje='Este modulo requiere un METODO especifico y debe ser llamado desde la Estacion De Enfermeria.';
          $this->frmMSG($url, $titulo, $mensaje);
          return true;
     }
     
     
     /**
     * Forma para mostrar mensaje
     *
     * @param string $url opcional url de retorno
     * @param string $titulo opcional titulo de la ventana
     * @param string $mensaje opcional mensaje a mostrar
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function frmMSG($url='', $titulo='', $mensaje='', $link='')
     {
          if(empty($titulo))  $titulo  = $this->titulo;
          if(empty($mensaje)) $mensaje = "EL USUARIO NO TIENE PERMISOS EN ESTE MODULO.";
          if(empty($link)) $link = "VOLVER";
          $this->salida  = themeAbrirTabla($titulo);
          $this->salida .= "<div class='titulo3' align='center'><br><br><b>$mensaje</b>";
          if($url)
          {
               $this->salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
               $this->salida.="    <tr>\n";
               $this->salida.="        <td align='center' class=\"label_error\">\n";
               $this->salida.="            <a href='$url'>$link</a>\n";
               $this->salida.="        </td>\n";
               $this->salida.="    </tr>\n";
               $this->salida.="  </table>\n";
     
          }
          $this->salida .= "<br><br></div>";
          $this->salida .= themeCerrarTabla();
          return true;
     }
     
     
     /**
     * Forma para mostrar la cabecera de la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmDatosEstacion($datos)
     {
          $this->salida .= ThemeAbrirTabla("ESTACI&Oacute;N DE ENFERMERIA : ".$datos['estacion_descripcion']);
          $this->salida .= "<center>\n";
          $this->salida .= "    <table class='modulo_table_title' border='0' width='80%'>\n";
          $this->salida .= "        <tr class='modulo_table_title'>\n";
          $this->salida .= "            <td>Empresa</td>\n";
          $this->salida .= "            <td>Centro Utilidad</td>\n";
          $this->salida .= "            <td>Unidad Funcional</td>\n";
          $this->salida .= "            <td>Departamento</td>\n";
          $this->salida .= "        </tr>\n";
          $this->salida .= "        <tr class='modulo_list_oscuro'>\n";
          $this->salida .= "            <td>".$datos['empresa_descripcion']."</td>\n";
          $this->salida .= "            <td>".$datos['centro_utilidad_descripcion']."</td>\n";
          $this->salida .= "            <td>".$datos['unidad_funcional_descripcion']."</td>\n";
          $this->salida .= "            <td>".$datos['departamento_descripcion']."</td>\n";
          $this->salida .= "        </tr>\n";
          $this->salida .= "    </table>\n";
          $this->salida .= "</center>\n";
          return true;
     }
     
     
     /**
     * Forma para mostrar el pie de pagina de la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmPieDePagina()
     {
          $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
     
          $this->salida .= "<center>\n";
          $this->salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
          $this->salida.="    <tr>\n";
          $this->salida.="        <td align='center' class=\"label_error\">\n";
          $this->salida.="            <a href='$url'>VOLVER</a>\n";
          $this->salida.="        </td>\n";
          $this->salida.="    </tr>\n";
          $this->salida.="  </table>\n";
          $this->salida .= "</center>\n";
          $this->salida .= themeCerrarTabla();
          return true;
     }
     
     
     function SetStyle($campo)
     {
          if ($this->frmError[$campo] || $campo=="MensajeError"){
               if ($campo=="MensajeError"){
                    $arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
                    return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
               }
     	     return ("label_error");
     	}
     	return ("label");
	}
     
     
	/**
	*		FrmShowBodega: Bodegas de Solicitud de la EE.
	*
	*		@Tizziano Perea O.
	*		@access Private
	*		@param array datos de la estacion
	*		@return boolean
	*/
	function FrmShowBodega($datos_estacion,$SWITCHE)
	{
          //Validar si el usuario esta logueado y si tiene permisos.
          if(!$this->GetUserPermisos('11'))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo='VALIDACION DE PERMISOS';
               $mensaje='El usuario no tiene permiso para : Bodegas Estacion - Solictud de Insumos y Medicamentos [11]';
               $this->frmMSG($url, $titulo, $mensaje);
               return true;
          }
          
          if(empty($datos_estacion))
          {
               $datos_estacion = $_REQUEST['datos_estacion'];
               $SWITCHE = $_REQUEST['switche'];
               //esta variable de session la usamos para trabajar esta forma indiferente de
               //q sea medicamentos o insumos,para llamar frmshowbodega
               if(empty($_SESSION['ESTACION_MEDICAMENTOS']['ACTION']))
               {$_SESSION['ESTACION_MEDICAMENTOS']['ACTION']=$_REQUEST['accion'];}
          }
		
          if(empty($datos_estacion))
          $datos_estacion = &$this->GetdatosEstacion();
     
          //VALIDACION DE PERMISOS
          if(!is_array($datos_estacion))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo = "VALIDACION DE PERMISOS";
               $this->frmMSG($url,$titulo);
               return true;
          }
          
          unset($_SESSION['ESTAR']);
          unset($_SESSION['codigos']);
          unset($_SESSION['cantidad_a_perdi_sol']);
          
          $datos=$this->GetEstacionBodega($datos_estacion,1);

          if(is_array($datos))
          {
               $this->salida .= ThemeAbrirTabla("SELECCIONAR BODEGAS DE LA ESTACION &nbsp;".$datos_estacion[descripcion_estacion]."");
               
			if($SWITCHE=='Confirmar_sol')
               {
                    $f = ModuloGetURL('app','EE_Suministros_x_Estacion','user','CallConSuministros_x_estacion',array("datos_estacion"=>$datos_estacion,'switche'=>'Confirmar_sol'));
               }elseif($SWITCHE=='Solicitar_sol')
               {
                    $f = ModuloGetURL('app','EE_Suministros_x_Estacion','user','CallSolSuministros_x_estacion',array("datos_estacion"=>$datos_estacion,'switche'=>'Solicitar_sol'));
               }

               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

               $this->salida .= "	<br><table align=\"center\" width=\"50%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
               $this->salida .= "		<tr class='modulo_table_list_title'>\n";
               $this->salida .= "			<td width=\"2%\" >BODEGAS</td>\n";
               $this->salida .= "		</tr>\n";
               $this->salida .= "		<tr class='modulo_list_claro'>\n";
               $this->salida .= "			<td width=\"2%\"  align=\"center\" >\n";

               $this->salida.="<select name='bodega' class='select'>";
               
               if(empty($empresa))
               {
                    for($i=0;$i<sizeof($datos);$i++)
                    {
                         $this->salida.="<option value=".$datos[$i][bodega].">".$datos[$i][descripcion]."</option>";
                    }
	               $this->salida.="</select>";
               }
               $this->salida .= "			</td>\n";
               $this->salida .= "		</tr>\n";
               $this->salida.=" <tr class='modulo_list_oscuro'>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"BUSCAR\"></form>";
               $this->salida.=" </td>";
               $this->salida .= "		</tr>\n";
               $this->salida.="</table><br>";
          }
          //DATOS DEL PIE DE PAGINA
          $this->FrmPieDePagina();
          return true;
	}
     
     
     /*
     * Forma que los datos para solicitar Insumos o Medicamentos
     * para la EE.
     *
     * @autor Tizziano Perea.
     * @param $datos_estacion
     */
     function SolSuministros_x_estacion($datos_estacion,$bodega,$SWITCHE)
	{
          if(!$datos_estacion)
          {
               $datos_estacion = $_REQUEST["datos_estacion"];
               $bodega = $_REQUEST["bodega"];
               $SWITCHE = $_REQUEST["switche"];
          }	
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
          $cadena .= "	function CargarPagina(href,valor) {\n";
          $cadena .= "		var url=href;\n";
          $cadena .= "		location.href=url+'&bodega='+valor;\n";
          $cadena .= "	}\n\n";
          $this->salida .=$cadena;
          $this->salida .= "</SCRIPT>";
          $datos1=$this->GetEstacionBodega($datos_estacion,1);
          $this->salida .= ThemeAbrirTabla("SOLICITUD DE SUMINISTROS POR ESTACION");
          $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
          $this->salida .= "		<tr class=\"modulo_table_title\">\n";
          $this->salida .= "			<td>EMPRESA</td>\n";
          $this->salida .= "			<td>CENTRO</td>\n";
          $this->salida .= "			<td>ESTACION</td>\n";
          $this->salida .= "			<td>FECHA</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
          $this->salida .= "			<td>".$datos_estacion['empresa_descripcion']."</td>\n";
          $this->salida .= "			<td>".$datos_estacion['centro_utilidad_descripcion']."</td>\n";
          $this->salida .= "			<td>".$datos_estacion['estacion_descripcion']."</td>\n";
          $this->salida .= "			<td>".date('Y-m-d')."</td>\n";
          $this->salida.="</tr></table><br>";
          
          $accion = ModuloGetURL('app','EE_Suministros_x_Estacion','user','SolSuministros_x_estacion',array("conteo"=>$_REQUEST['conteo'],"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso'],"datos_estacion"=>$datos_estacion));
               
          $this->salida .="<form name=\"suministro_e\" action=\"$accion\" method=\"post\">";
          
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";

          $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\" class=\"modulo_table_list_title\">";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="<td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO DE SUMINISTROS</td>";
          $this->salida.="</tr>";
     
          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="<td width=\"5%\">BODEGA</td>";
          $this->salida.="<td width=\"10%\">";
                    
          $this->salida.="<select name=bodega class='select'>";
          for($i=0;$i<sizeof($datos1);$i++)
          {
               if($datos1[$i][bodega]==$_REQUEST['bodega'])
               {
                    $this->salida.="<option value=".$datos1[$i][bodega]." selected>".$datos1[$i][descripcion]."</option>";
                    $a=1;
               }
               else
               {
                    $this->salida.="<option value=".$datos1[$i][bodega].">".$datos1[$i][descripcion]."</option>";
               }	
          }
          if($a !=1){$selected="selected";}else{$selected="";}
          $this->salida.="<option value=\"-1\" $selected>-- SELECCIONE --</option>";
          $this->salida.="</select>";
          $this->salida.="</td>";
     
               
          $this->salida.="<td width=\"10%\" align = left >";
          $this->salida.="<select size = 1 name = 'criterio'  class =\"select\">";
          if($_REQUEST['criterio']=='1')
          {$sel1="selected";$sel2="";}else{$sel2="selected";$sel1="";}
          $this->salida.="<option value = '1' $sel1>Codigo</option>";
          $this->salida.="<option value = '2' $sel2>Suministro</option>";
          $this->salida.="</select>";
          $this->salida.="</td>";
          $this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'busqueda'  size=\"40\" maxlength=\"40\"  value =\"$buscar\"></td>" ;
     
          $this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSCAR\"></td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          if($_REQUEST['busqueda'])
          {
               $cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
          }
          else
          {
               $cadena="Buscador Avanzado: Busqueda de los suministros";
          }
          $this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
     
          if($_REQUEST['buscar'] OR $_REQUEST['ADD'])
          {
               $filtro=$this->GetFiltro($_REQUEST['criterio'],$_REQUEST['busqueda']);
          }
	
          //estos if de aqui en adelante,es importante ya que si hemos presionado el boton aicionar temp
          if(empty($_REQUEST['paso']))
               {$pas=1;}else{$pas=$_REQUEST['paso'];}
		
		//si presionamos quitar.
		//cabe decir que segun el paso quitamos todos los items q esten en variable de 
		//session.
          if($_REQUEST['DEL'])
          {
               if($_SESSION['ESTAR'][$pas])
               {
                    foreach($_SESSION['ESTAR'][$pas] as $k => $v)
                    {
                    	unset($_SESSION['codigos'][$k]);
                         unset($_SESSION['cantidad_a_perdi_sol'][$k]);
                    }
                    unset($_SESSION['ESTAR'][$pas]);
               }
               $variable="SE QUITO TODOS LOS INSUMOS ADICIONADOS DE LA PAGINA &nbsp; $pas";
          }
          else
          {
               $variable='';
          }          
          
          //si presionamos adicionar........
          if($_REQUEST['ADD'])
          {	
               foreach($_REQUEST['op'] as $index=>$valor)
               {          
                    if(is_numeric($_REQUEST['cant'.$valor]) && $_REQUEST['cant'.$valor] > 0)
                    {$_SESSION['ESTAR'][$pas][$valor]=$valor."*".$_REQUEST['cant'.$valor];}
                    $_SESSION['codigos'][$valor] = $valor;
                    $_SESSION['cantidad_a_perdi_sol'][$valor] = $_REQUEST['cant'.$valor];
               }				
          }

          if($_SESSION['codigos'])
          {
          	unset($salida);
          	foreach ($_SESSION['codigos'] as $k => $info)
               {
               	$codiguitos[] = $k;
               }

               for($jj=0; $jj<sizeof($codiguitos); $jj++)
               {
                    $arr_temp[] = $this->Get_SuministrosEstacion($_REQUEST['bodega'],'',$codiguitos[$jj],1);
                    $salida="<br><table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"hc_table_submodulo_list_title\">\n";
                    $salida.="<tr class=\"hc_table_submodulo_list_title\"><td colspan=\"4\">MEDICAMENTOS ADICIONADOS</td></tr>";
                    $salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $salida.="<td width=\"10%\">CODIGO</td>";
                    $salida.="<td width=\"75%\" colspan='2'>PRODUCTO - UNIDAD DE MEDIDA</td>";
                    $salida.="<td width=\"20%\">CANT</td>";
                    $salida.="</tr>";               
                    foreach($arr_temp as  $V => $vector)
                    {
                    	foreach($vector as $V2 => $vector)
                         { 
                              if( $i % 2){ $estilo='modulo_list_claro';}
                              else {$estilo='modulo_list_oscuro';}
                              $salida.="<tr class=\"$estilo\">";
                              $salida.="<td align=\"center\" width=\"10%\">".$vector[codigo_producto]."</td>";
                              $salida.="<td align=\"left\" width=\"75%\" colspan='2'>".$vector[descripcion]."</td>";
                              $salida.="<td align=\"center\" width=\"20%\">".$_SESSION['cantidad_a_perdi_sol'][$vector[codigo_producto]]."</td>";
                              $salida.="</tr>";               
                         }
                    }
                    $salida.="</table>";
               }
          }
          
          $this->salida.= $salida;


          $arr_vect=$this->Get_SuministrosEstacion($_REQUEST['bodega'],$filtro,0,0);
          if(is_array($arr_vect))
          {
               $this->salida.="<br><div align='center'><label class='label_mark'>$variable</label></div>";
               $this->salida.="<br><table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td width=\"10%\">CODIGO</td>";
               $this->salida.="<td width=\"75%\" colspan='2'>PRODUCTO - UNIDAD DE MEDIDA</td>";
               $this->salida.="<td width=\"20%\">CANT</td>";
               $this->salida.='<form name="vv" method="post" action="'.$o.'">';
               $this->salida.="<td width=\"5%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
               $this->salida.="</tr>";               
               for($i=0;$i<sizeof($arr_vect);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class='$estilo' align='left'>";
                    $this->salida.="  <td width=\"10%\">".$arr_vect[$i][codigo_producto]."</td>";
                    $this->salida.="  <td width=\"40%\">".$arr_vect[$i][descripcion]."</td>";
                    $this->salida.="  <td width=\"35%\">".$arr_vect[$i][unidad]."</td>";
                    
                    $info=explode("*",$_SESSION['ESTAR'][$pas][$arr_vect[$i][codigo_producto]]);
                    $this->salida.="  <td align=\"center\" width=\"20%\"><label class='label_mark'>Cant &nbsp;</label><input type='text' class='input-text' name=cant".$arr_vect[$i][codigo_producto]." value='".$info[1]."' size='8' maxlength='8'></td>";
                    
                    if($info[0]== $arr_vect[$i][codigo_producto])
                    {$check="checked";}else{$check="";}
                    $this->salida.="  <td width=\"5%\" align=\"center\"><input type=checkbox name=op[$i] value=".$arr_vect[$i][codigo_producto]." $check></td>";unset($check);
                    $this->salida.="</tr>";
               }
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td colspan='4'><input type=submit name=DEL value='QUITAR ITEMS SELECCIONADOS DE ESTA PAGINA' class=input-submit></td>";
               $this->salida.="<td><input type=submit name=ADD value=ADICIONAR class=input-submit></form></td>";
               $this->salida.="</tr>";
               $this->salida.="</table>";
               
          
               $this->salida.=$this->RetornarBarra($filtro,1);
          }
          else
          {
               $this->salida .= "<br><br><div align='center'><label class='label_mark'>SELECCIONE LA BODEGA</label></div>";
          }
          
          $XYS = ModuloGetURL('app','EE_Suministros_x_Estacion','user','Seleccion_BodegaManual',array("datos_estacion"=>$datos_estacion,"criterio"=>$_REQUEST['criterio'],"busqueda"=>$_REQUEST['busqueda'],"bodega"=>$_REQUEST['bodega'],'parametro'=>'inicial'));	
          $this->salida .= "<form name=\"formainsert\" action=\"$XYS\" method=\"post\">";
          $this->salida .= '<br><br><table align="center" width="40%" border="0">';
          $this->salida .= '<tr>';
          $this->salida .= '<td align="center">';
          $this->salida .= '<input type="submit" name="GUARDAR" value="GUARDAR" class="input-submit">';
          $this->salida .= '</form>';
          $this->salida .= '</td>';
     
          $o = ModuloGetURL('app','EE_Suministros_x_Estacion','user','FrmShowBodega',array("datos_estacion"=>$datos_estacion,'switche'=>'Solicitar_sol'));
          $this->salida .= '<form name="volver" method="post" action="'.$o.'">';
          $this->salida .= '<td align="center">';
          $this->salida .= '<input type="submit" name="volver" value="SELECCION BODEGA" class="input-submit">';
          $this->salida .= '</form>';
          $this->salida .= '</td>';
          $this->salida .= '</tr>';
          
          $this->salida .= '</table><br>';
          //DATOS DEL PIE DE PAGINA
          $this->FrmPieDePagina();
          return true;		
     }
     
     
	/**
	*		Seleccion_BodegaManual
	*
	*		@autor Tizziano Perea.
	*		@param array datos de la estacion
	*		@param SWITCHE para determinar si es despacho o devolucion
	*		@return boolean
	*/
	function Seleccion_BodegaManual($opcion,$despacho,$estacion,$bodega,$SWITCHE)
	{
          if($_REQUEST['parametro'] == 'inicial')
          {
               $estacionInicial = $_REQUEST['datos_estacion'];
               $bodega = $_REQUEST['bodega'];
               $SWITCHE = $_REQUEST['switche'];
               $datos=$this->GetEstacion_BodegaReposicionManual($estacionInicial,"");
          }else
          {
          	$bodega_Cargar = $_SESSION['bodega_ConsumoD'];
               unset($_SESSION['bodega_ConsumoD']);
          	$datos=$this->GetEstacion_BodegaReposicionManual($estacion,$bodega_Cargar);          
          }

          if(is_array($datos))
          {
               $this->salida .= ThemeAbrirTabla("SELECCIONAR BODEGAS DE CONSUMO DIRECTO DE LA ESTACION &nbsp;".$estacion[descripcion_estacion]."");
          
               if($_REQUEST['parametro'] == 'inicial')
               {
          		$f = ModuloGetURL('app','EE_Suministros_x_Estacion','user','Solicitar_SuministrosEstacion',array("datos_estacion"=>$estacionInicial,"criterio"=>$_REQUEST['criterio'],"busqueda"=>$_REQUEST['busqueda'],"bodega"=>$_REQUEST['bodega']));	
               }
               else
               {
                    $f = ModuloGetURL('app','EE_Suministros_x_Estacion','user','CargarBodega_ComsumoDirecto',array('estacion'=>$estacion,'bodega'=>$bodega,'switche'=>$SWITCHE,'opcion'=>$opcion,'despachos'=>$despacho));
               }
               
               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";

               $this->salida .= "	<br><table align=\"center\" width=\"50%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
               $this->salida .= "		<tr class='modulo_table_list_title'>\n";
               $this->salida .= "			<td width=\"2%\" >BODEGAS DE CONSUMO DIRECTO</td>\n";
               $this->salida .= "		</tr>\n";
               $this->salida .= "		<tr class='modulo_list_claro'>\n";
               $this->salida .= "			<td width=\"2%\"  align=\"center\" >\n";

               $this->salida.="<select name='bodega_ConsumoD' class='select'>";
               if(empty($empresa))
               {
                    for($i=0;$i<sizeof($datos);$i++)
                    {
                         $this->salida.="<option value=".$datos[$i][bodega].">".$datos[$i][descripcion]."</option>";
                    }
	               $this->salida.="</select>";
               }
               $this->salida .= "			</td>\n";
               $this->salida .= "		</tr>\n";
               $this->salida.=" <tr class='modulo_list_oscuro'>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"CargarBodega\" type=\"submit\" class=\"input-submit\"  value=\"CARGAR BODEGA\"></form>";
               $this->salida.=" </td>";
               $this->salida .= "		</tr>\n";
               $this->salida.="</table><br>";
          }
          else
          {
               $this->salida .= ThemeAbrirTabla("ALERTA","50%");
               $this->salida .= "<div  align='center'<label class='label_error'>NO EXISTEN BODEGAS DE CONSUMO DIRECTO ASOCIADAS A LA ESTACION</label>";
          }

          if($_REQUEST['parametro'] == 'inicial')
          {
	          $href = ModuloGetURL('app','EE_Suministros_x_Estacion','user','CallSolSuministros_x_estacion',array('datos_estacion'=>$estacionInicial,'bodega'=>$bodega,'switche'=>$SWITCHE));
          }else
          {
	          $href = ModuloGetURL('app','EE_Suministros_x_Estacion','user','CallConSuministros_x_estacion',array('datos_estacion'=>$estacion,'bodega'=>$bodega,'switche'=>$SWITCHE));
          }
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>VOLVER</a><br>";

          $this->salida .= themeCerrarTabla();
          return true;
	}

     
     
      /*
     *	Funcion que muestra la vista para la confirmacion de los suministros
     *	despachados desde la bodega.
     *
     *	@Author Tizziano Perea Ocoro.
     */
     function ConSuministros_x_estacion($datos_estacion,$bodega,$SWITCHE)
     {
          if(empty($datos_estacion))
          {
               $datos_estacion=$_REQUEST['datos_estacion'];
               $SWITCHE=$_REQUEST['switche'];
               $bodega=$_REQUEST['bodega'];
          }
          
          $nom_bodega=$this->TraerNombreBodega($datos_estacion,$bodega);

          $actionCon = ModuloGetURL('app','EE_Suministros_x_Estacion','user','AccionCancelCon_Solicitud',array('datos_estacion'=>$datos_estacion,'bodega'=>$bodega,'switche'=>$SWITCHE,'accion'=>'confirmar'));
          $this->salida .= ThemeAbrirTabla("SOLICITUDES REALIZADAS DE SUMINISTRO POR ESTACION &nbsp; -- &nbsp; BODEGA  ".strtoupper($nom_bodega)."");
          $this->salida .="<form name=\"AccionCon\" action=\"$actionCon\" method=\"post\">";
          $this->salida .= " <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= " </table>";

          //consulta las solicitudes de suministro para la estacion.
          $solicitudes=$this->GetSolicitudes_x_Estacion($datos_estacion,$bodega);

          if(!empty($solicitudes))
          {
               $this->salida .= "<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
               $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida .= "<td colspan=\"2\">SOLICITUDES DE SUMINISTRO POR CONFIRMAR DE LA ESTACION: $estacion[descripcion4]</td>";
               $this->salida .= "</tr>";
               
               $desabilitarX = 1;
               
               for($i=0;$i<sizeof($solicitudes);$i++)
               {
                    $despachos=$this->GetSuministrosSolicitadosConfirmar_x_Estacion($solicitudes[$i][solicitud_id]);
                    $sizevar = sizeof($despachos);
                    if(!empty($despachos))
                    {
                         $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida .= "<td colspan=\"2\">";
                         
                         $desabilitarX = 0;
                         $this->salida .= "	<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
     
                         $this->salida .= "		<tr class='modulo_table_title'>\n";
                         $this->salida .= "			<td width=\"10%\" >SOLICITUD</td>\n";
                         $this->salida .= "			<td width=\"15%\" >CODIGO</td>\n";
                         $this->salida .= "			<td width=\"40%\" >DESCRIPCION PRODUCTO&nbsp;&nbsp;</td>\n";
                         $this->salida .= "			<td width=\"46%\" >UNIDAD DE MEDIDA</td>\n";
                         $this->salida .= "			<td width=\"15%\" >CANT&nbsp;&nbsp;</td>\n";
                         $this->salida .= "			<td width=\"8%\" >DESPACHO</td>\n";
                         $this->salida .= "			<td width=\"3%\" >&nbsp;</td>\n";
                         $this->salida .= "		</tr>\n";
                    
                         $this->salida .= "<input type=\"hidden\" name=\"despachos\" value=\"$sizevar\">";
                         $this->salida .= "<tr>\n";
                         $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$solicitudes[$i][solicitud_id]."</td>\n";
                         $this->salida .= "<td colspan = 6 width=\"65%\">";
                         $this->salida .= "<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         
                         for($j=0;$j<sizeof($despachos); $j++)
                         {
                              if($j % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"12%\">".$despachos[$j][codigo_producto]."</td>\n";
                              $this->salida .= "<td $estilo width=\"36%\">".$despachos[$j][descripcion]."</td>\n";
                              $this->salida .= "<td $estilo width=\"33%\">".$despachos[$j][unidad]."</td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"9%\">".floor($despachos[$j][cantidad])."</td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"11%\" ><b>".$despachos[$j][cantidad]." Uds.</b></td>\n";
                              $this->salida .= "<td $estilo width=\"3%\" align=\"center\"><input type=checkbox name=opcion[] value=\"".$despachos[$j][solicitud_id].",".$despachos[$j][consecutivo].",".$despachos[$j][confirmacion_id].",".$solicitudes[$i][bodega_solicita]."\"></td>";unset($chek);
                              $this->salida .="</tr>";
                         }
                         $this->salida .= "</table>"; 
                         $this->salida .= "</td>";
                         $this->salida .= "</tr>";
                         $this->salida .= "<td colspan=\"2\" class=\"modulo_table_title\" align=\"center\">BODEGA SOLICITANTE: " ;
                         $this->salida .= "</td>";
                         $nom_bodega=$this->TraerNombreBodega($datos_estacion,$solicitudes[$i][bodega_solicita]);
                         $this->salida .= "<td class=\"modulo_table_title\" colspan=\"5\">".$nom_bodega."" ;
                         $this->salida .= "</td>";
                         $this->salida .= "</tr>";
                         $this->salida .= "</tr>";
                         $this->salida .= "</table><br>";
                         $this->salida .= "</td>";
                         $this->salida .= "</tr>";
                    }
			}
               
               if($desabilitarX != 0)
               {
                    $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida .= "<td colspan=\"2\">";
                    $title="NO EXISTEN SUMINISTROS PARA CONFIRMAR O DEVOLVER REQUERIDOS A LA BODEGA: ".strtoupper($nom_bodega)."";
                    $this->salida.="<DIV ALIGN='CENTER'><LABEL CLASS='label_mark'>$title</LABEL></DIV>";
                    $desabilitarX = 1;
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
               }
               
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td colspan=\"2\" nowrap width=\"40\" align=\"center\">";
               if ($desabilitarX == 1)
               { $this->salida.="<input type=\"submit\" class=\"input-submit\" name=\"confirmar_con\" value=\"CONFIRMAR\" disabled>"; }
               else
               { $this->salida.="<input type=\"submit\" class=\"input-submit\" name=\"confirmar_con\" value=\"CONFIRMAR\">"; }
               $this->salida.="</td>";
               $this->salida.="</tr>";
               $this->salida.= "</table><br>";
			$this->salida.= "</form>";               
          }
          
          $actionCan = ModuloGetURL('app','EE_Suministros_x_Estacion','user','AccionCancelCon_Solicitud',array('datos_estacion'=>$datos_estacion,'bodega'=>$bodega,'switche'=>$SWITCHE,'accion'=>'cancelar'));
          $this->salida .="<form name=\"AccionCon\" action=\"$actionCan\" method=\"post\">";

          if(!empty($solicitudes))
          {
               $this->salida .= "<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
               $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida .= "<td colspan=\"2\">SOLICITUDES DE SUMINISTRO POR CANCELAR DE LA ESTACION: $estacion[descripcion4]</td>";
               $this->salida .= "</tr>";
               
               $desabilitar = 1;
               for($i=0;$i<sizeof($solicitudes);$i++)
               { 
                    $despachos = $this->GetSuministrosSolicitadosCancelar_x_Estacion($solicitudes[$i][solicitud_id]);
                    $sizevar = sizeof($despachos);
				if(!empty($despachos))
                    {                    
                         $desabilitar = 0; 
                         $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida .= "<td colspan=\"2\">";
                         $this->salida .= "	<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
     
                         $this->salida .= "		<tr class='modulo_table_title'>\n";
                         $this->salida .= "			<td width=\"10%\" >SOLICITUD</td>\n";
                         $this->salida .= "			<td width=\"15%\" >CODIGO</td>\n";
                         $this->salida .= "			<td width=\"40%\" >DESCRIPCION PRODUCTO&nbsp;&nbsp;</td>\n";
                         $this->salida .= "			<td width=\"46%\" >UNIDAD DE MEDIDA</td>\n";
                         $this->salida .= "			<td width=\"15%\" >CANT&nbsp;&nbsp;</td>\n";
                         $this->salida .= "			<td width=\"3%\" >&nbsp;</td>\n";
                         $this->salida .= "		</tr>\n";
                    
                         $this->salida .= "<input type=\"hidden\" name=\"despachos\" value=\"$sizevar\">";
                         $this->salida .= "<tr>\n";
                         $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$solicitudes[$i][solicitud_id]."</td>\n";
                         $this->salida .= "<td colspan = 6 width=\"65%\">";
                         $this->salida .= "<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         
                         for($j=0;$j<sizeof($despachos); $j++)
                         {
                              if($j % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"15%\">".$despachos[$j][codigo_producto]."</td>\n";
                              $this->salida .= "<td $estilo width=\"41%\">".$despachos[$j][descripcion]."</td>\n";
                              $this->salida .= "<td $estilo width=\"37%\">".$despachos[$j][unidad]."</td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"15%\">".floor($despachos[$j][cantidad])."</td>\n";
                              $this->salida .= "<td $estilo width=\"3%\" align=\"center\"><input type=checkbox name=opcion[] value=\"".$despachos[$j][solicitud_id].",".$despachos[$j][consecutivo]."\"></td>";unset($chek);
                              $this->salida .="</tr>";
                         }
                         $this->salida .= "</table>"; 
                         $this->salida .= "</td>";
                         $this->salida .= "</tr>";
                         $this->salida .= "</table><br>";
                         $this->salida .= "</td>";
		               $this->salida .= "</tr>";
                    }                              
			}
               
               if($desabilitar != 0)
               {    
                    $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida .= "<td colspan=\"2\">";
                    $title="NO EXISTEN SUMINISTROS PARA CANCELAR REQUERIDOS A LA BODEGA: ".strtoupper($nom_bodega)."";
                    $this->salida.="<DIV ALIGN='CENTER'><LABEL CLASS='label_mark'>$title</LABEL></DIV>";
                    $desabilitar = 1;
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
               }
               
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td colspan=\"2\" width=\"40\" align=\"center\">";
               if($desabilitar == 1)
               { $this->salida.="<input type=\"submit\" class=\"input-submit\" name=\"cancelar_con\" value=\"CANCELAR\" disabled>"; }
               else
               { $this->salida.="<input type=\"submit\" class=\"input-submit\" name=\"cancelar_con\" value=\"CANCELAR\">"; }
               $this->salida.="</td>";
               $this->salida.="</tr>"; 
               $this->salida.= "</table>";
			$this->salida.= "</form>";               
          }
          
          /*if(!empty($solicitudes))
          {
               $this->salida .= "<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
               $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida .= "<td colspan=\"2\">SOLICITUDES DE SUMINISTRO POR DEVOLVER EN LA ESTACION: $estacion[descripcion4]</td>";
               $this->salida .= "</tr>";
               
               $desabilitarJ = 1;
               
               for($i=0;$i<sizeof($solicitudes);$i++)
               {
                    $despachos=$this->GetSuministrosSolicitados_Devoluciones_x_Estacion($solicitudes[$i][solicitud_id]);
                    $sizevar = sizeof($despachos);
                    if(!empty($despachos))
                    {
                         $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
                         $this->salida .= "<td colspan=\"2\">";
                         
                         $desabilitarJ = 0;
                         $this->salida .= "	<br><table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class='modulo_list_table'\n>";
     
                         $this->salida .= "		<tr class='modulo_table_title'>\n";
                         $this->salida .= "			<td width=\"10%\" >SOLICITUD</td>\n";
                         $this->salida .= "			<td width=\"15%\" >CODIGO</td>\n";
                         $this->salida .= "			<td width=\"40%\" >DESCRIPCION PRODUCTO&nbsp;&nbsp;</td>\n";
                         $this->salida .= "			<td width=\"46%\" >UNIDAD DE MEDIDA</td>\n";
                         $this->salida .= "			<td width=\"15%\" >CANT&nbsp;&nbsp;</td>\n";
                         $this->salida .= "			<td width=\"8%\" >DESPACHO</td>\n";
                         $this->salida .= "			<td width=\"3%\" >&nbsp;</td>\n";
                         $this->salida .= "		</tr>\n";
                    
                         $this->salida .= "<input type=\"hidden\" name=\"despachos\" value=\"$sizevar\">";
                         $this->salida .= "<tr>\n";
                         $this->salida .= "<td colspan = 1  align=\"center\" class=modulo_list_claro width=\"10%\">".$solicitudes[$i][solicitud_id]."</td>\n";
                         $this->salida .= "<td colspan = 6 width=\"65%\">";
                         $this->salida .= "<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         
                         for($j=0;$j<sizeof($despachos); $j++)
                         {
                              if($j % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"12%\">".$despachos[$j][codigo_producto]."</td>\n";
                              $this->salida .= "<td $estilo width=\"36%\">".$despachos[$j][descripcion]."</td>\n";
                              $this->salida .= "<td $estilo width=\"33%\">".$despachos[$j][unidad]."</td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"9%\">".floor($despachos[$j][cantidad])."</td>\n";
                              $this->salida .= "<td $estilo align=\"center\" width=\"11%\" ><b>".$despachos[$j][cantidad]." Uds.</b></td>\n";
                              $this->salida .= "<td $estilo width=\"3%\" align=\"center\"><input type=checkbox name=opcion[] value=\"".$despachos[$j][solicitud_id].",".$despachos[$j][consecutivo].",".$despachos[$j][confirmacion_id].",".$solicitudes[$i][bodega_solicita]."\"></td>";unset($chek);
                              $this->salida .="</tr>";
                         }
                         $this->salida .= "</table>"; 
                         $this->salida .= "</td>";
                         $this->salida .= "</tr>";
                         $this->salida .= "<td colspan=\"2\" align=\"center\">BODEGA SOLICITANTE: " ;
                         $this->salida .= "</td>";
                         $nom_bodega=$this->TraerNombreBodega($estacion,$solicitudes[$i][bodega_solicita]);
                         $this->salida .= "<td colspan=\"5\">".$nom_bodega."" ;
                         $this->salida .= "</td>";
                         $this->salida .= "</tr>";
                         $this->salida .= "</tr>";
                         $this->salida .= "</table><br>";
                         $this->salida .= "</td>";
                         $this->salida .= "</tr>";
                    }
			}
               
               if($desabilitarJ != 0)
               {
                    $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida .= "<td colspan=\"2\">";
                    $title="NO EXISTEN SUMINISTROS PARA DEVOLVER REQUERIDOS A LA BODEGA: ".strtoupper($nom_bodega)."";
                    $this->salida.="<DIV ALIGN='CENTER'><LABEL CLASS='label_mark'>$title</LABEL></DIV>";
                    $desabilitarJ = 1;
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
               }
               
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td colspan=\"2\" nowrap width=\"40\" align=\"center\">";
               if ($desabilitarX == 1)
               { $this->salida.="<input type=\"submit\" class=\"input-submit\" name=\"devolver_con\" value=\"DEVOLVER\" disabled>"; }
               else
               { $this->salida.="<input type=\"submit\" class=\"input-submit\" name=\"devolver_con\" value=\"DEVOLVER\">"; }
               $this->salida.="</td>";
               $this->salida.="</tr>";
               $this->salida.= "</table><br>";
			$this->salida.= "</form>";               
          }*/
          
          if(empty($solicitudes))
          {    
               $this->salida .= "<br><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
               $this->salida .= "<tr>";
               $this->salida .= "<td>";
               $title="NO EXISTEN SUMINISTROS PARA CANCELAR NI CONFIRMAR REQUERIDOS A LA BODEGA: ".strtoupper($nom_bodega)."";
               $this->salida.="<DIV ALIGN='CENTER'><LABEL CLASS='label_mark'>$title</LABEL></DIV>";
               $this->salida .= "</td>";
               $this->salida .= "</tr>";
               $this->salida .= "</table><br>";
          }

          $hr = ModuloGetURL('app','EE_Suministros_x_Estacion','user','FrmShowBodega',array("datos_estacion"=>$datos_estacion,"switche"=>$SWITCHE));
          $this->salida .= "<div class='normal_10' align='center'><br><a href='".$hr."'>SELECCION DE BODEGA</a><br>";
          //DATOS DEL PIE DE PAGINA
          $this->FrmPieDePagina();
          return true;
	}
     
          
     //funciones para generar la barra de segmentos en el buscador
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

	 function RetornarBarra($filtro,$uno){
          if($this->limit>=$this->conteo){
               return '';
		}
		//if($filtro){$_SESSION['USUARIOS']['FILTRO']=$filtro;}//esto guarda el filtro...
		//de busqueda...
	  	$paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
		
          $datos_estacion = $_REQUEST["datos_estacion"];
		$datosPaciente = $_REQUEST["datosPaciente"];
          if($uno == 1)
          {
			$accion=ModuloGetURL('app','EE_Suministros_x_Estacion','user','SolSuministros_x_estacion',array('conteo'=>$this->conteo,'busqueda'=>$_REQUEST['busqueda'],"datos_estacion"=>$datos_estacion,"bodega"=>$_REQUEST['bodega'],'buscar'=>$_REQUEST['buscar'],'criterio'=>$_REQUEST['criterio']));
          }
          else
          {
               $accion=ModuloGetURL('app','EE_AdministracionMedicamentos','user','AgregarInsumos_A_Paciente',array('conteo'=>$this->conteo,'busqueda'=>$_REQUEST['busqueda'],"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$_REQUEST['bodega']));
          }
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$this->salida .= "<br><table width='22%' border='0'  align='center' cellspacing=\"5\"  cellpadding=\"1\"><tr><td width='20%' class='label' bgcolor=\"#D3DCE3\">Páginas</td>";
		if($paso > 1){
			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'><img src=\"".GetThemePath()."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'><img src=\"".GetThemePath()."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td width='7%' bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}else{
               $diferencia=$numpasos-9;
               if($diferencia<0){$diferencia=1;}
               for($i=($diferencia);$i<=$numpasos;$i++){
                    if($paso==$i){
                         $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\" >$i</td>";
                    }else{
                         $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
                    }
                    $colspan++;
               }
               if($paso!=$numpasos){
                    $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
                    $this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
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
				
			$this->salida .= "</tr><tr><td  class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td></tr></table>";
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
			$this->salida .= "</tr><tr><td   class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td></tr></table>";
		}
	}
	//fin de las fujnciones para la barra de segnentacion
     
     

}//fin de la clase

?>

