<?php
	/**************************************************************************************
	* $Id: EECargoXajax.php,v 1.2 2007/11/28 15:58:35 jgomez Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Tizziano Perea O.
	**************************************************************************************/
	IncludeClass("app_EE_Cargos_user","","app","EE_Cargos");


    function EliminarInsumo($Cuenta,$tmp_cuenta_insumos_id)
    {
        $objResponse = new xajaxResponse();
        $path=GetThemePath();
        $consulta = new app_EE_Cargos_user();

        $elimiar_result=$consulta->EliminarCargoTmpIyM_Improved($Cuenta,$tmp_cuenta_insumos_id);

        if($elimiar_result===true)
        {
            $salida .= "                  <table width=\"95%\" align=\"center\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td align=\"center\">\n";
            $salida .= "                        <label ALIGN='center' class='label_error'>CARGO ELIMINADO SATISFACTORIAMENTE</label>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    </table>\n"; 
            $objResponse->assign("error_insumo","innerHTML",$salida);
            $objResponse->Call("MostrarTmpInsumos");
        }
        else
        {
            $salida .= "                  <table width=\"95%\" align=\"center\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td align=\"center\">\n";
            $salida .="                        <label ALIGN='center' class='label_error'>ERROR AL ELIMINAR CARGO ".$consulta->mensajeDeError."</label>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    </table>\n"; 
            $objResponse->assign("error_insumo","innerHTML",$salida);
        }





        return $objResponse;
    }

    function ListarTmpInsumos($Cuenta)
    {
        $objResponse = new xajaxResponse();
        $path=GetThemePath();
        $consulta = new app_EE_Cargos_user();
        $busqueda=$consulta->DatosTmpInsumos($Cuenta);
        //var_dump($busqueda);
        if(!empty($busqueda))
        {             
            $salida .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            $salida .= "                 </div>\n";
            $salida .= "                  <table width=\"90%\" align=\"center\" class=\"\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\"width=\"30%\">\n";
            $salida .= "                        DEPARTAMENTO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" width=\"20%\" class=\"modulo_list_claro\">\n";
            $salida .= "                        ".$_SESSION['ESTANCIA']['DPTO'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\"width=\"30%\">\n";
            $salida .= "                        BODEGA";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" width=\"20%\" class=\"modulo_list_claro\">\n";
            $salida .= "                         <a title='BODEGA'>\n";
            $salida .= "                         ".$_SESSION['ESTANCIA']['BODEGA']['descripcion'];
            $salida .= "                         </a>\n";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    </table>\n";
            $salida .= "                    <br>\n";
            $salida .= "                 <form name=\"adicionar_insumo\" id=\"adicionar_insumo\">\n";
            $salida .= "                  <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\" width=\"7%\">\n";
            $salida .= "                        BODEGA";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\"width=\"10%\">\n";
            $salida .= "                        <a title='CODIGO PRODUCTO'>CODIGO</a> ";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"27%\">\n";
            $salida .= "                        <a title='DESCRIPCION PRODUCTO'>DESCRIPCION<a> ";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"8%\">\n";
            $salida .= "                        CANTIDAD";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"12%\">\n";
            $salida .= "                        EXISTENCIA";
            $salida .= "                      </td>\n";    
            $salida .= "                      <td align=\"center\" width=\"8%\">\n";
            $salida .= "                        PRECIO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"8%\">\n";
            $salida .= "                        USUARIO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"15%\">\n";
            $salida .= "                        FECHA REGISTRO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                       X";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
      //////////////////
//  array(15) {
//     ["numerodecuenta"]=>
//     string(6) "432232"
//     ["codigo_producto"]=>
//     string(10) "0104020002"
//     ["cantidad"]=>
//     string(5) "25.00"
//     ["bodega"]=>
//     string(2) "25"
//     ["tmp_cuenta_insumos_id"]=>
//     string(5) "87098"
//     ["centro_utilidad"]=>
//     string(2) "01"
//     ["empresa_id"]=>
//     string(2) "01"
//     ["departamento"]=>
//     string(6) "010201"
//     ["precio"]=>
//     string(8) "67740.00"
//     ["fecha_cargo"]=>
//     string(19) "2007-11-23 00:00:00"
//     ["plan_id"]=>
//     string(3) "247"
//     ["servicio_cargo"]=>
//     string(1) "4"
//     ["descripcion"]=>
//     string(26) "IRUXOL x 40 gr  1 gr/0.8 U"
//     ["desdpto"]=>
//     string(9) "URGENCIAS"
//     ["desbodega"]=>
//     string(9) "URGENCIAS"
   ////////////////
           for($i=0;$i<count($busqueda);$i++)
            {
                $mrtr="cantidad[$i]";
                $codigo="codigox[$i]";
                $precio="precio_venta[$i]";
                $nom_tr="super_tr".$i;
                
                if($busqueda[$i]['cantidad'] > $busqueda[$i]['existencia'])
                {
                    $salida .= "                    <tr id=\"".$nom_tr."\" bgcolor='#FFDDDD' onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#FFDDDD');\" >\n";
                   // $salida .= "                    <tr bgcolor='#FFDDDD' onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"if(ban==0){mOvrw(this,'0','0','#ffdddd');}else{mOvrw(this,'".$prefijo."','".$Elmov[$i]['numero']."','#dddddd');}\" id=\"".$capaxitron."\">\n"; 
                }
                else
                {
                        $salida .= "                    <tr id=\"".$nom_tr."\" class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff');\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                }    
                    $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
                    $salida .= "                         <a title='".$busqueda[$i]['nom_bodega']."'>\n";
                    $salida .= "                         ".substr($busqueda[$i]['bodega'],0,35);
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                    $salida .= "                         <a title='".$busqueda[$i]['codigo_producto']."'>\n";
                    $salida .= "                         ".substr($busqueda[$i]['codigo_producto'],0,35);
                    $salida .= "                        <input type=\"hidden\" id=\"".$codigo."\" name=\"".$codigo."\" value=\"".$busqueda[$i]['codigo_producto']."\">\n";
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                    $salida .= "                         <a title='".$busqueda[$i]['descripcion']."'>\n";
                    $salida .= "                        ".substr($busqueda[$i]['descripcion'],0,33);
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"right\">\n";
                    $salida .= "                         <a title='CANTIDAD'>\n";
                    $salida .= "                         ".$busqueda[$i]['cantidad'];
                    $salida .= "                         </a>\n";
                    $salida .= "                        <input type=\"hidden\" id=\"".$precio."\" name=\"".$precio."\" value=\"".$busqueda[$i]['precio']."\">\n";
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"right\">\n";
                    $salida .= "                         <a title='EXISTENCIA EN BODEGA ".$busqueda[$i]['nom_bodega']."'>\n";
                    $salida .= "                         ".$busqueda[$i]['existencia'];
                    $salida .= "                         </a>\n";
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"right\">\n";
                    $salida .= "                         <a title='PRECIO PRODUCTO'>\n";
                    $salida .= "                         ".FormatoValor($busqueda[$i]['precio']);
                    $salida .= "                         </a>\n";
                    $salida .= "                      </td>\n";
    
                    $nombre_del_usuario=$consulta->NombreUsu($busqueda[$i]['usuario_id']);
                    
                    $salida .= "                      <td align=\"center\">\n";
                    $salida .= "                         <a title='".$nombre_del_usuario[0]['nombre']."'>\n";
                    $salida .= "                         ".$nombre_del_usuario[0]['usuario'];
                    $salida .= "                         </a>\n";
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"center\">\n";
                    $salida .= "                         <a title='FECHA REGISTRO'>\n";
                    $salida .= "                        ".substr($busqueda[$i]['fecha_registro'],0,16);
                    $salida .= "                         </a>\n";
                    $salida .= "                      </td>\n";
                //$salida .= "                      <input type=\"text\" class=\"input-text\" id=\"".$mrtr."\" name=\"".$mrtr."\" size=\"8\" onkeypress=\"return acceptNum(event);\" value=\"\" onkeyup=\"PonerRojo(this.id,'".$busqueda[$i]['existencia']."')\">\n";//
                    $salida .= "                      <td align=\"center\" onclick=\"EliminarInsumo('".$Cuenta."','".$busqueda[$i]['tmp_cuenta_insumos_id']."');\">\n";
                    $salida .= "                         <a title='ELIMINAR INSUMO'>\n";
                    $salida .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $salida .= "                         </a>\n";
                    $salida .= "                      </td>\n";
                
                $salida .= "                    </tr>\n";
            }
            $salida .= "                </table>\n";
            $salida .= "               </form>";
            //$salida .= "               <br>";
//             $salida .= "               <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
//             $salida .= "                <tr align=\"center\">";
//             $salida .= "                 <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"Eliminar Todos Los Cargos\"></td>";
//             //$accionEliminarTodos=ModuloGetURL('app','EE_Cargos','user','EliminarTodosCargosIyM',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
//             //$salida .= "    <form name=\"formaborrar\" action=\"$accionEliminarTodos\" method=\"post\">";
//             //$salida .= "        <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"ELIMINAR TODOS LOS CARGOS\"></td>";
//             //$salida .= "    </form>";
//             //$accionGuardarTodos=ModuloGetURL('app','EE_Cargos','user','GuardarTodosCargosIyM',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
//             //$salida .= "    <form name=\"formaguardar\" action=\"$accionGuardarTodos\" method=\"post\">";
//             //$salida .= "        <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"GUARDAR TODOS LOS CARGOS\"></td>";
//             //$salida .= "    </form>";
//             $salida .= "      </tr>";
//             $salida .= "      </table>";
            $objResponse->assign("lista_insumos_seleccionados","innerHTML",$salida);
        }
        else
        {
            $salida .= "                  <table width=\"88%\" align=\"center\" >\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td align=\"center\">\n";
            $salida .= "                         <label ALIGN='center' class='label_error'>ESTA CUENTA NO TIENE CARGOS AGREGADOS</label>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    </table>\n";
            $objResponse->assign("lista_insumos_seleccionados","innerHTML",$salida);
        }
           
            return $objResponse;

        

    }



        
    function ActivarCapa($DatosDocumento)
	{
        $objResponse = new xajaxResponse();
        $_SESSION['DATOS_DOCUMENTO'] = $DatosDocumento;
		return $objResponse;
	}

    function BuscarProducto($Datos,$offset,$Cuenta,$PlanId)
    {
        $objResponse = new xajaxResponse();
        $path=GetThemePath();
        $departamento=$_SESSION['CUENTAS']['E']['DEPTO'];
        $consulta = new app_EE_Cargos_user();
        $bo = explode(',',SessionGetVar("bodega"));
        //$objResponse->alert($bo);
        $empresa_id=$bo[2];
        $centro_utilidad=$bo[1];
        $bodega=$bo[0];
        if($Datos==1)
        {
            $Datos = SessionGetVar("BUSQUEDA");
        }
        SessionSetVar("BUSQUEDA",$Datos);
        $busqueda=$consulta->BuscarProducto($empresa_id,$centro_utilidad,$bodega,$Datos,$offset);
        //var_dump($busqueda);
        if(!empty($busqueda))
        {             
            $salida .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
            $salida .= "                 </div>\n";
            $salida .= "                 <form name=\"adicionar_insumo\" id=\"adicionar_insumo\">\n";
            $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\"width=\"15%\">\n";
            $salida .= "                        CODIGO PRODUCTO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"35%\">\n";
            $salida .= "                        <a title='DESCRIPCION PRODUCTO'>DESCRIPCION<a> ";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            $salida .= "                        UNIDAD";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            $salida .= "                        EXISTENCIA";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"13%\">\n";
            $salida .= "                        PRECIO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"13%\">\n";
            $salida .= "                        CANTIDAD";
            $salida .= "                      </td>\n";
//             $salida .= "                      <td align=\"center\" width=\"5%\">\n";
//             $salida .= "                        <a title='SELECCIONAR PRODUCTO'>SL<a>";
//             $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            for($i=0;$i<count($busqueda);$i++)
            {
                $mrtr="cantidad[$i]";
                $codigo="codigox[$i]";
                $precio="precio_venta[$i]";
                $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff');\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                        ".$busqueda[$i]['codigo_producto'];
                $salida .= "                        <input type=\"hidden\" id=\"".$codigo."\" name=\"".$codigo."\" value=\"".$busqueda[$i]['codigo_producto']."\">\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                $salida .= "                         <a title='".$busqueda[$i]['descripcion']."'>\n";
                $salida .= "                        ".substr($busqueda[$i]['descripcion'],0,33);
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                $salida .= "                         <a title='".$busqueda[$i]['descripcion_unidad']."'>\n";
                $salida .= "                         ".substr($busqueda[$i]['descripcion_unidad'],0,35);
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
                $salida .= "                         <a title='EXISTENCIA'>\n";
                $salida .= "                         ".$busqueda[$i]['existencia'];
                $salida .= "                         </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\">\n";
                $salida .= "                         <a title='PRECIO DE VENTA'>\n";
                $salida .= "                         ".$busqueda[$i]['precio_venta'];
                $salida .= "                         </a>\n";
                $salida .= "                        <input type=\"hidden\" id=\"".$precio."\" name=\"".$precio."\" value=\"".$busqueda[$i]['precio_venta']."\">\n";
                $salida .= "                      </td>\n";
                
                $salida .= "                       <td align=\"center\">\n";
            //$inp_cant="conteolista";
            //$inp_cant1="conteolista".$i;
            //list($entero,$decimal) = explode(".",$ListasProducto[$i]['conteo']);
                if($busqueda[$i]['existencia']>0)
                {
                    $salida .= "                      <input type=\"text\" class=\"input-text\" id=\"".$mrtr."\" name=\"".$mrtr."\" size=\"8\" onkeypress=\"return acceptNum(event);\" value=\"\" onkeyup=\"PonerRojo(this.id,'".$busqueda[$i]['existencia']."')\">\n";//
                }
                else
                {
                    $salida .= "                       &nbsp;";
                }
                $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
        

//             $salida .= "                    </tr>\n";
//             $salida .= "                 </table>";
//                 if($busqueda[$i]['existencia']>0)
//                 {
//                     $salida .= "                      <td align=\"center\" onclick=\"AsignarPro('".$busqueda[$i]['codigo_producto']."','".$busqueda[$i]['descripcion']."','".$busqueda[$i]['descripcion_unidad']."');\">\n";
//                     $salida .= "                         <a title='SELECCIONAR PRODUCTO'>\n";
//                     $salida .= "                          <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
//                     $salida .= "                         </a>\n";
//                 }
//                 else
//                 {
//                     $salida .= "                      <td align=\"center\">\n";
//                     $salida .= "                         &nbsp;\n";
//                 }
//                 $salida .= "                      </td>\n";
                
            }     
            $salida .= "                   <tr class=\"modulo_list_claro\">\n";
            $salida .= "                       <td  colspan='8' align=\"right\">\n";
            $salida .= "                         <input type=\"button\" class=\"input-submit\" id=\"agregar\" name=\"agregar\" value=\"AGREGAR\" onclick=\"xajax_Agregar_Tmp_Insumos(xajax.getFormValues('adicionar_insumo'),'".$Cuenta."','".$PlanId."');Cerrar('ContenedorProductos');\">\n";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                </table>\n";
            $salida .= "               </form>";
            $Cont=$consulta->ContarProStip($empresa_id,$centro_utilidad,$bodega,$Datos);
            $malo=$Cont[0]['count'];
            $salida .= "".ObtenerPaginadoPro($path,$Cont,'1','1',$offset,$Cuenta,$PlanId);
        }
        else
        {
            $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td align=\"center\">\n";
            $salida .="                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    </table>\n";
        }

        $objResponse->assign("tabelos","innerHTML",$salida);     
            
        
        return $objResponse;
    }


    
/**
*para mostrar la tabla de clientes
*
*
*
*
**/
    
    function ObtenerPaginadoPro($path,$slc,$op,$Datos,$pagina,$Cuenta,$PlanId)
    {
      
       //echo "io";
      $TotalRegistros = $slc[0]['count'];
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $uid = UserGetUID();
         $LimitRow = 7;//;intval(GetLimitBrowser());
      }
      else
      {
         $LimitRow = $limite;
      }
      if ($TotalRegistros > 0)
      {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros/$LimitRow);
        
         if($TotalRegistros%$LimitRow > 0)
        {
          $NumeroPaginas++;
        }
            
        $Inicio = $pagina;
        if($NumeroPaginas - $pagina < 9 )
        {
          $Inicio = $NumeroPaginas - 9;
        }
        elseif($pagina > 1)
        {
          $Inicio = $pagina - 1;
        }
        
        if($Inicio <= 0)
        {
          $Inicio = 1;
        }
          
        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" "; 

        $TablaPaginado .= "<tr>\n";
        if($NumeroPaginas > 1)
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P&#225;ginas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";   //$empresa_id,$centro_utilidad,$bodega,$tip_bus,$criterio,$offset      
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_Pro('".$Datos."','1','".$Cuenta."','".$PlanId."')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";                                                             
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_Pro('".$Datos."','".($pagina-1)."','".$Cuenta."','".$PlanId."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
          }
          $Fin = $NumeroPaginas + 1;
          if($NumeroPaginas > 10)
          {
            $Fin = 10 + $Inicio;
          }
            
          for($i=$Inicio; $i< $Fin ; $i++)
          {
            if ($i == $pagina )
            {
              $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
            }
            else
            {
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:Bus_Pro('".$Datos."','".$i."','".$Cuenta."','".$PlanId."')\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";   
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_Pro('".$Datos."','".($pagina+1)."','".$Cuenta."','".$PlanId."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:Bus_Pro('".$Datos."','".$NumeroPaginas."','".$Cuenta."','".$PlanId."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     P&#225;gina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
        $aviso .= "   </tr>\n";
        
        if($op == 2)
        {
          $TablaPaginado .= $aviso;
        }
        else
        {
          $TablaPaginado = $aviso.$TablaPaginado;
        }
      }
      
      $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
      $Tabla .= $TablaPaginado;
      $Tabla .= "</table>";

      return $Tabla;
    }


    function  Agregar_Tmp_Insumos($datos,$cuenta,$PlanId)
    {

        $objResponse = new xajaxResponse();
        $consulta = new app_EE_Cargos_user();
        //var_dump($datos);
       // var_dump($cuenta);
        $departamento=$_SESSION['CUENTAS']['E']['DEPTO'];
        $bo = explode(',',SessionGetVar("bodega"));
        //$objResponse->alert($bo);
        $empresa_id=$bo[2];
        $centro_utilidad=$bo[1];
        $bodega=$bo[0];
        $agregar=$consulta->InsertarInsumosImproved($datos,$cuenta,$PlanId,$departamento,$empresa_id,$centro_utilidad,$bodega);
        //var_dump("asas".$agregar);
        //$consulta->mensajeError;
        if($agregar===true)
         {
//             $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
//             $salida .= "                    <tr>\n";
//             $salida .= "                      <td align=\"center\">\n";
//             $salida .="                         <label ALIGN='center' class='label_error'>INSUMOS AGREGADOS SATISFACTORIAMENTE</label>";
//             $salida .= "                      </td>\n";
//             $salida .= "                    </tr>\n";
//             $salida .= "                    </table>\n";
          $objResponse->assign("error_insumo","innerHTML","CARGO(S) AGREGADO(S) SATISFACTORIAMENTE");
          $objResponse->Call("MostrarTmpInsumos");        
         }
         
         
        return $objResponse;
    }
?>