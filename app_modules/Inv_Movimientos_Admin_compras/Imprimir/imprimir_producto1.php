<?php
    /**
    * Archivo que obtiene el KARDEX de un producto
    * @param string $empresa_id
    * @param string $centro_id
    * @param string $bodega
    * @param string $codigo_producto
    * @param string $limite
    * @param string $fecha_inicial
    * @param string $fecha_final
    * @return pop-up con toda la informacion del producto
    **/
		$_ROOT='../../../';
		$VISTA='HTML';
		include $_ROOT.'includes/enviroment.inc.php';
		IncludeClass('MovBodegasAdminSQL',null,'app','Inv_Movimientos_Admin');
    //include "../../../app_modules/Cg_Movimientos/classes/MovimientosSQL.class.php";
    $fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
    if (!IncludeClass('BodegasProductos'))
    {
        die(MsgOut("Error al incluir archivo","BodegasProductos"));
    }
		IncludeFile($fileName);
   	
    $empresa_id=$_REQUEST['empresa_id'];
    $centro_id=$_REQUEST['centro_id'];
    $bodega=$_REQUEST['bodega'];
    $codigo_producto=$_REQUEST['codigo'];
    $limite=$_REQUEST['limit'];
    $lapso=$_REQUEST['lapso'];
    $fecha_inicial=$_REQUEST['fecha_inicial'];
    $fecha_final=$_REQUEST['fecha_final'];
    //print_r($_REQUEST);
    //$print_r($_REQUEST);
    
    //vaR_DUMP($limite);
    if($limite=='')
    {
      $limite=null;
    }
    
    if($fecha_inicial=='')
    {
      $fecha_inicial=null;
    }
    
    if($fecha_final=='')
    {
      $fecha_final=null;
    }
    
    
    $consulta=new MovBodegasAdminSQL();
    $consulta1=new BodegasProductos();      
                       
    
                 //                                $empresa_id,$centro_id,$bodega,$codigo_producto,$lapso,$fecha_inicial,$fecha_final  GetInfoProductoPorLapso($empresa_id, $centro_id,$bodega, $codigo_producto, $limite, $offset,      $count=null, $lapso,$dia_inicial,$dia_final,$tipo=null,$tipo_movimiento);
                //                      GetInfoProductoPorLapso($empresa_id,$centro_utilidad,$bodega, $codigo_producto, $limit=null, $offset=null, $count=null, $lapso=null, $dia_inicial=null, $dia_final=null,$tipo,$tipo_movimiento, $fecha_inicio_lapso,$fecha_final_lapso)
                 $resultado=$consulta1->GetInfoProductoPorLapso($empresa_id,$centro_id,$bodega,$codigo_producto,$limite,$offset=null, $count=null,$lapso,$dia_inicial=null, $dia_final=null,$tipo,$tipo_movimiento,$fecha_inicial, $fecha_final);
    
    //VAR_DUMP($resultado);
    echo $consulta1->mensajeDeError;
    $TITLE="DETALLE DEL DOCUMENTO";
    print(ReturnHeader($TITLE));
		print(ReturnBody());
    $path = SessionGetVar("rutaImagenes");
    //$salida .=ThemeAbrirTabla('DETALLE DOCUMENTO');
     //var_dump($resultado);
//          $salida .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
//          $salida .= "                    <tr>\n";
//          $salida .= "                       <td colspan='2' align=\"center\">\n";
//          $salida .= "                        CONSULTA DE PRODUCTOS";
//          $salida .= "                       </td>\n";
//          $salida .= "                    </tr>\n";
//          $salida .= "                    <tr>\n";
//          $salida .= "                       <td width=\"35%\" align=\"center\">\n";
//          $salida .= "                        EMPRESA";
//          $salida .= "                       </td>\n";
//          $salida .= "                       <td width=\"65%\" align=\"left\">\n";
//          $nombre_empresa=$consulta->ColocarEmpresa($empresa_id);
//          $salida .= "                        " .$empresa_id." - ".$nombre_empresa[0]['razon_social'];
//          $salida .= "                       </td>\n";
//          $salida .= "                    </tr>\n";
//          $salida .= "                    <tr>\n";
//          $salida .= "                       <td width=\"35%\" align=\"center\">\n";
//          $salida .= "                        NOMBRE BODEGA";
//          $salida .= "                       </td>\n";
//          $salida .= "                       <td width=\"65%\" align=\"left\">\n";
//          $nombre=$consulta->bodegasname($bodega);
//          $salida .= "                        BODEGA: ".$bodega."-".$nombre[0]['descripcion'];
//          $salida .= "                       </td>\n";
//          $salida .= "                    </tr>\n";
//          $salida .= "                 </table>\n";
//          $salida .= "                    <br>\n";
         //
         $salida .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td colspan='4' align=\"center\">\n";
         $salida .= "                        KARDEX";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td width=\"20%\" align=\"center\">\n";
         $salida .= "                        EMPRESA";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"35%\" align=\"left\">\n";
         $nombre_empresa=$consulta->ColocarEmpresa($empresa_id);
         $salida .= "                        " .$empresa_id." - ".$nombre_empresa[0]['razon_social'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"20%\" align=\"center\">\n";
         $salida .= "                          FECHA DE IMPRESION";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"25%\" align=\"left\">\n";
         $salida .= "                         <a>";
         $salida .= "                           ".date("Y-m-d H:i:s");
         $salida .= "                         </a>";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        NOMBRE BODEGA";
         $salida .= "                       </td>\n";
         $salida .= "                       <td  align=\"left\">\n";
         $nombre=$consulta->bodegasname($bodega);
         $salida .= "                        BODEGA: ".$bodega."-".$nombre[0]['descripcion'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td  align=\"center\">\n";
         $salida .= "                        USUARIO";
         $salida .= "                       </td>\n";
         $salida .= "                       <td  align=\"left\">\n";
         $salida .= "                         <a>";
         $USUARIO=$consulta->NombreUsu(UserGetUID());
         $salida .= "                          ".UserGetUID()."-".$USUARIO[0]['nombre'];
         $salida .= "                         </a>";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                 </table>\n";
         //
        $salida .= "                    <br>\n";
         $salida .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td colspan='5' align=\"center\">\n";
         $salida .= "                        DATOS DEL PRODUCTO";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         //$salida .= "                 </table>\n";
         //$salida .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td width=\"10%\" align=\"center\">\n";
         $salida .= "                        CODIGO";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"40%\" align=\"center\">\n";
         $salida .= "                        NOMBRE";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"15%\" align=\"center\">\n";
         $salida .= "                        UNIDAD";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"25%\" align=\"center\">\n";
         $salida .= "                       <a title='CONTENIDO UNIDAD VENTA'>";
         $salida .= "                        CONTENIDO UNIDAD VENTA";
         $salida .= "                       </a>\n";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"10%\" align=\"center\">\n";
         $salida .= "                        ESTADO";
         $salida .= "                       </td>\n";
         $salida .= "                     </tr>\n";
         
         //for($i=0;$i<count($resultado);$i++)
        // {
            $salida .= "                    <tr>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['codigo_producto'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['descripcion'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['descripcion_unidad'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['contenido_unidad_venta'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            if($resultado['estado']=='1')
            {
             $salida .= "                        ACTIVO";
            }
            elseif($resultado['estado']=='0')
            {
             $salida .= "                        DESACTIVO";
            }
            $salida .= "                       </td>\n";
    //          $bodega=$consulta->bodegasname($resultado['bodega']);
    //          $salida .= "                        <td align=\"left\">\n";
    //          $salida .= "                          ".$bodega[0]['descripcion'];
    //          $salida .= "                         </td>\n";
            $salida .= "                    </tr>\n";
         //}   
         $salida .= "                   </table>\n";
         $salida .= "                   <br>\n";   

         
//          if(!empty($resultado['EXISTENCIAS']))
//          {
//             $salida .= "                   <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
//             $salida .= "                    <tr>\n";
//             $salida .= "                       <td colspan='6' align=\"center\">\n";
//             $salida .= "                        EXISTENCIAS POR BODEGAS";
//             $salida .= "                       </td>\n";
//             $salida .= "                    </tr>\n";
//             $salida .= "                    <tr>\n";
//             $salida .= "                       <td align=\"center\" width='10%'>\n";
//             $salida .= "                          CENTRO DE UTILIDAD";
//             $salida .= "                       </td>\n";
//             $salida .= "                       <td align=\"center\" width='10%'>\n";
//             $salida .= "                          BODEGA";
//             $salida .= "                       </td>\n";
//             $salida .= "                       <td align=\"center\" width='41%'>\n";
//             $salida .= "                          DESCRIPCION";
//             $salida .= "                       </td>\n";
//             $salida .= "                       <td align=\"center\" width='13%'>\n";
//             $salida .= "                          EXISTENCIA";
//             $salida .= "                       </td>\n";
//             $salida .= "                       <td align=\"center\" width='13%'>\n";
//             $salida .= "                          EXISTENCIA MINIMA";
//             $salida .= "                       </td>\n";
//             $salida .= "                       <td align=\"center\" width='13%'>\n";
//             $salida .= "                          EXISTENCIA MAXIMA";
//             $salida .= "                       </td>\n";
//             $salida .= "                    </tr>\n";
//             for($i=0;$i<count($resultado['EXISTENCIAS']);$i++)
//             {
//               $salida .= "                    <tr>\n";
//               $salida .= "                       <td align=\"center\">\n";
//               $salida .= "                        ".$resultado['EXISTENCIAS'][$i]['centro_utilidad'];
//               $salida .= "                       </td>\n";
//               $salida .= "                       <td align=\"center\">\n";
//               $salida .= "                        ".$resultado['EXISTENCIAS'][$i]['bodega'];
//               $salida .= "                       </td>\n";
//               $salida .= "                       <td align=\"center\">\n";
//               $salida .= "                        ".$resultado['EXISTENCIAS'][$i]['descripcion'];
//               $salida .= "                       </td>\n";
//               $salida .= "                       <td align=\"center\">\n";
//               $salida .= "                        ".$resultado['EXISTENCIAS'][$i]['existencia'];
//               $salida .= "                       </td>\n";
//               $salida .= "                       <td align=\"center\">\n";
//               $salida .= "                        ".$resultado['EXISTENCIAS'][$i]['existencia_minima'];
//               $salida .= "                       </td>\n";
//               $salida .= "                       <td align=\"center\">\n";
//               $salida .= "                        ".$resultado['EXISTENCIAS'][$i]['existencia_maxima'];
//               $salida .= "                       </td>\n";
//               $salida .= "                    </tr>\n";
//             }
//             $salida .= "                   </table>\n";
//          }   
          $salida .= "                   <br>\n";   
         $salida .= "                   <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td colspan='5' align=\"center\">\n";
         $salida .= "                        COSTOS";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          COSTO";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          COSTO ANTERIOR";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          COSTO ULTIMA COMPRA";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          COSTO PENULTIMA COMPRA";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          TOTAL INVENTARIO";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        ".FormatoValor($resultado['costo']);
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        ".FormatoValor($resultado['costo_anterior']);
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        ".FormatoValor($resultado['costo_ultima_compra']);
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        ".FormatoValor($resultado['costo_penultima_compra']);
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        ".FormatoValor($resultado['costo']*$resultado['existencia']);
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                   </table>\n";
         $salida .= "                   <br>\n";   
         $salida .= "                   <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          PRECIO DE VENTA";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          % UTILIDAD";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          PRECIO ANTERIOR";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          PRECIO MAXIMO";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          PRECIO MINIMO";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        ".$resultado['precio_venta'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        ".$resultado['porcentaje_utlidad'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        ".$resultado['precio_venta_anterior'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        ".$resultado['precio_maximo'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        ".$resultado['precio_minimo'];
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                   </table>\n";
         $salida .= "                   <br>\n"; 

         if(!empty($resultado['LISTAS_DE_PRECIOS']))
         {
              $salida .= "                 <table BORDER='1' width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
              $salida .= "                    <tr>\n";
              $salida .= "                        <td COLSPAN='7' align=\"center\">\n";
              $salida .= "                         <a>";
              $salida .= "                           PRECIOS SEGUN LISTAS";
              $salida .= "                         </a>";
              $salida .= "                        </td>\n";
              $salida .= "                    </tr>\n";
              $salida .= "                    <tr>\n";
              $salida .= "                        <td width='15%' align=\"center\">\n";
              $salida .= "                           CODIGO LISTA ";
              $salida .= "                        </td>\n";
              $salida .= "                        <td width='40%' align=\"center\">\n";
              $salida .= "                           NOMBRE LISTA ";
              $salida .= "                        </td>\n";
              $salida .= "                        <td width='20%' align=\"center\">\n";
              $salida .= "                           PRECIO VENTA ";
              $salida .= "                        </td>\n";
              $salida .= "                        <td width='25%' align=\"center\">\n";
              $salida .= "                           PORCENTAJE UTILIDAD ";
              $salida .= "                        </td>\n";
              $salida .= "                    </tr>\n";
            foreach($resultado['LISTAS_DE_PRECIOS'] as $doc_val=>$valor)
            {
                //var_dump($resultado['DETALLE']);
                $salida .= "                    <tr>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       ".$valor['codigo_lista'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       ".$valor['descripcion'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\">\n";
                $salida .= "                       ".$valor['precio_venta'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\">\n";
                $salida .= "                       ".$valor['porcentaje_utlidad'];
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
            }
              $salida .= "                   </table>\n";
          }
        if($_REQUEST['periodo']==date("Ym"))
        {
                $salida .= "                   <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
                $salida .= "                    <tr>\n";
                $salida .= "                       <td  colspan='6' align=\"center\">\n";
                $salida .= "                        EXISTENCIAS";
                $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
                $salida .= "                    <tr>\n";
                $salida .= "                       <td align=\"center\" width='12%'>\n";
                $salida .= "                          INICIAL";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width='13%'>\n";
                $salida .= "                          INGRESOS";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width='12%'>\n";
                $salida .= "                          EGRESOS";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width='13%'>\n";
                $salida .= "                          ACTUAL";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width='12%'>\n";
                $salida .= "                          DESCUADRE";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width='13%'>\n";
                $salida .= "                          GLOBAL";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width='12%'>\n";
                $salida .= "                          MINIMA";
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\" width='13%'>\n";
                $salida .= "                          MAXINA";
                $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
                $salida .= "                    <tr>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['existencia_inicial'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['ingresos'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['egresos'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['existencia'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['descuadre'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['existencia_global'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['existencia_minima'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['existencia_maxima'];
                $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
                $salida .= "                   </table>\n";
                $salida .= "                   <br>\n";
        }
        else
        {
           // VAR_DUMP($resultado);
            $salida .= "                   <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                       <td  colspan='8' align=\"center\">\n";
            $salida .= "                        EXISTENCIAS";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr>\n";
            $salida .= "                       <td align=\"center\" width='12%'>\n";
            $salida .= "                          INICIAL";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='13%'>\n";
            $salida .= "                          INGRESOS";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='12%'>\n";
            $salida .= "                          EGRESOS";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='13%'>\n";
            $salida .= "                          FINAL";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='12%'>\n";
            $salida .= "                           DESCUADRE";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['existencia_inicial'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['ingresos'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['egresos'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['existencia_final'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['descuadre'];
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                   </table>\n";
            $salida .= "                   <br>\n";
           

        }
         $salida .= "                    <br>\n";
         //var_dump($resultado['KARDEX']);
         if(!empty($resultado['KARDEX']))
         {
            $salida .= "                   <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                       <td colspan='12' align=\"center\">\n";
            $nombre=$consulta->bodegasname($bodega);
            $salida .= "                        KARDEX BODEGA: ".$bodega."-".$nombre[0]['descripcion'];
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr>\n";
            $salida .= "                       <td align=\"center\" width='2%'>\n";
            $salida .= "                         &nbsp; ";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='4%'>\n";
            $salida .= "                          T_M";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='13%'>\n";
            $salida .= "                          FECHA";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='11%'>\n";
            $salida .= "                          NUMERO";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='33%'>\n";
            $salida .= "                          OBSERVACIONES DOCUMENTO";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='2%'>\n";
            $salida .= "                          ENTRADAS";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='11%'>\n";
            $salida .= "                          SALIDAS";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='9%'>\n";
            $salida .= "                          EXISTENCIA";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='8%'>\n";
            $salida .= "                          COSTO UNITARIO";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='9%'>\n";
            $salida .= "                          COSTO TOTAL";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='11%'>\n";
            $salida .= "                          USUARIO";
            $salida .= "                       </td>\n";
//             $salida .= "                       <td align=\"center\" width='7%'>\n";
//             $salida .= "                          CUENTA";
//             $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $SALDO_ACT=0;
            for($i=(count($resultado['KARDEX'])-1);$i>=0;$i--)
            {
              if($resultado['KARDEX'][$i]['tipo']=='EGRESO')
               { 
                 $espejo[$i]['tipo']='S';
                 $espejo[$i]['cantidad']=$resultado['KARDEX'][$i]['cantidad'];
                 $SALDO_ACT=$SALDO_ACT+$espejo[$i]['cantidad'];
               }
               elseif($resultado['KARDEX'][$i]['tipo']=='INGRESO')
               {
                  $espejo[$i]['tipo']='R';
                  $espejo[$i]['cantidad']=$resultado['KARDEX'][$i]['cantidad'];
                  $SALDO_ACT=$SALDO_ACT-$espejo[$i]['cantidad'];
               }  
                       
            
            }
            
            //var_dump($espejo);
            $BODEXXXX=$consulta->bodegasname($bodega);
            //var_dump($BODEXXXX);
            $SALDO_ACT=$resultado['existencia_inicial'];
            $suma_egresos=0;
            $suma_ingresos=0;
            $suma_egresos_por_costo=0;
            $suma_ingresos_por_costo=0;
            for($i=0;$i<count($resultado['KARDEX']);$i++)
            {
              $salida .= "                    <tr>\n";
              $salida .= "                       <td align=\"center\">\n";
              $salida .= "                        ".$i+1;
              $salida .= "                       </td>\n";
              $salida .= "                       <td align=\"center\">\n";
              $salida .= "                        ".$resultado['KARDEX'][$i]['tipo_movimiento'];
              $salida .= "                       </td>\n";
              
              $salida .= "                       <td align=\"center\">\n";
              $salida .= "                        ".$resultado['KARDEX'][$i]['fecha'];
              $salida .= "                       </td>\n";
              $salida .= "                       <td align=\"left\">\n";
              $salida .= "                        ".$resultado['KARDEX'][$i]['prefijo']."-".$resultado['KARDEX'][$i]['numero'];
              $salida .= "                       </td>\n";
	      $datosPacientes = "";
              if($resultado['KARDEX'][$i]['numerodecuenta'])
              {
                $datosPacientes = $consulta->GetDatosPaciente($resultado['KARDEX'][$i]['numerodecuenta']);
              }
              $salida .= "                       <td align=\"left\">\n";
              $salida .= "                        ".$resultado['KARDEX'][$i]['observacion']." <br><b>".$datosPacientes."</b>";
              $salida .= "                       </td>\n";

              
              $partes=explode(".", $resultado['KARDEX'][$i]['cantidad']);
              if($partes[1]>0)
              {
                $resultado['KARDEX'][$i]['cantidad']=$partes[0].".".$partes[1];
              }
              else
              {
               $resultado['KARDEX'][$i]['cantidad']=$partes[0];
              }
              
              
              //////
              if($resultado['KARDEX'][$i]['tipo']=='INGRESO')
               { 
                  $salida .= "                       <td align=\"right\">\n";
                  $salida .= "                        ".$resultado['KARDEX'][$i]['cantidad'];
                  $salida .= "                       </td>\n";
                  $salida .= "                       <td align=\"right\">\n";
                  $salida .= "                        &nbsp;";
                  $salida .= "                       </td>\n";
                  $suma_ingresos +=$resultado['KARDEX'][$i]['cantidad'];
               }
               elseif($resultado['KARDEX'][$i]['tipo']=='EGRESO')
               {   
                  $salida .= "                       <td align=\"right\">\n";
                  $salida .= "                        &nbsp;";
                  $salida .= "                       </td>\n";
                  $salida .= "                       <td align=\"right\">\n";
                  $salida .= "                        ".$resultado['KARDEX'][$i]['cantidad'];
                  $salida .= "                       </td>\n";
                $suma_egresos +=$resultado['KARDEX'][$i]['cantidad'];
                }
              /////////
              if($resultado['KARDEX'][$i]['tipo']=='EGRESO')
               { 
                  $suma_egresos_por_costo +=($resultado['KARDEX'][$i]['costo']*$resultado['KARDEX'][$i]['cantidad']);
                  //$SALDO_ACT1=$SALDO_ACT+$espejo[$i]['cantidad'];
                  $SALDO_ACT -= $espejo[$i]['cantidad'];  
                  $salida .= "                       <td align=\"right\">\n";
                  $salida .= "                        ".$SALDO_ACT;
                  $salida .= "                       </td>\n";
                  
               }
               elseif($resultado['KARDEX'][$i]['tipo']=='INGRESO')
               {
                 $suma_ingresos_por_costo +=($resultado['KARDEX'][$i]['costo']*$resultado['KARDEX'][$i]['cantidad']);
                 //$SALDO_ACT1=$SALDO_ACT-$espejo[$i]['cantidad'];
                 $SALDO_ACT += $espejo[$i]['cantidad'];
                 $salida .= "                       <td align=\"right\">\n";
                 $salida .= "                        ".$SALDO_ACT;
                 $salida .= "                       </td>\n";
               }  
              $salida .= "                       <td align=\"right\">\n";
              $salida .= "                        ".FormatoValor($resultado['KARDEX'][$i]['costo']);
              $salida .= "                       </td>\n";  
              $salida .= "                       <td align=\"right\">\n";
              $salida .= "                        ".FormatoValor($resultado['KARDEX'][$i]['costo']*$resultado['KARDEX'][$i]['cantidad']);
              $salida .= "                       </td>\n";      
              
              
              
              
              

              
              //$SALDO_ACT=$SALDO_ACT1;
              
              $salida .= "                       <td align=\"center\">\n";
              $salida .= "                        ".$resultado['KARDEX'][$i]['usuario'];
              $salida .= "                       </td>\n";
//               $salida .= "                       <td align=\"center\">\n";
//               $salida .= "                        ".$resultado['KARDEX'][$i]['numerodecuenta'];
//               $salida .= "                       </td>\n";
              $salida .= "                    </tr>\n";
            }
            $salida .= "                   </table>\n";
            $salida .= "                    <br>\n";
//             $salida .= "                   <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
//             $salida .= "                    <tr>\n";
//             $salida .= "                    <td colspan='1' align='right'>\n";
//             $salida .= "                    <label class='label_error'>TOTAL UNIDADES INGRESOS</label>";
//             $salida .= "                    </td>\n";
//             $salida .= "                    <td align='right'>\n";
//             $salida .= "                     <label>".$suma_ingresos."</label>";
//             $salida .= "                    </td>\n";
//             $salida .= "                    <td colspan='1' align='right'>\n";
//             $salida .= "                    <label class='label_error'>TOTAL UNIDADES EGRESOS</label>";
//             $salida .= "                    </td>\n";
//             $salida .= "                    <td align='right'>\n";
//             $salida .= "                     <label>".$suma_egresos."</label>";
//             $salida .= "                    </td>\n";
//             $salida .= "                    </tr>\n";
//             $salida .= "                    <tr>\n";
//             $salida .= "                     <td colspan='1' align='right'>\n";
//             $salida .= "                      <label class='label_error'>TOTAL COSTO INGRESOS</label>";
//             $salida .= "                      </td>\n";
//             $salida .= "                    <td align='right'>\n";
//             $salida .= "                     <label>".FormatoValor($suma_ingresos_por_costo)."</label>";
//             $salida .= "                    </td>\n";
//             $salida .= "                     <td colspan='1' align='right'>\n";
//             $salida .= "                      <label class='label_error'>TOTAL COSTO EGRESOS</label>";
//             $salida .= "                     </td>\n";
//             $salida .= "                    <td align='right'>\n";
//             $salida .= "                     <label>".FormatoValor($suma_egresos_por_costo)."</label>";
//             $salida .= "                    </td>\n";
//             $salida .= "                    </tr>\n";
//             $salida .= "                    <tr>\n";
//             
//             
//             $salida .= "                    </tr>\n";
//             $salida .= "                    <tr>\n";
//             $salida .= "                     <td colspan='2' align='right'>\n";
//             $salida .= "                      <label class='label_error'>DIFERENCIA</label>";
//             $salida .= "                      </td>\n";
//             $salida .= "                    </tr>\n";
//             if()
//             {
//              $salida .= "                    <tr>\n";
//              $salida .= "                    <td align='right'>\n";
//              $salida .= "                     <label>".FormatoValor($suma_ingresos_por_costo)."</label>";
//              $salida .= "                    </td>\n";
//              $salida .= "                    <td align='right'>\n";
//              $salida .= "                     <label>".FormatoValor($suma_egresos_por_costo)."</label>";
//              $salida .= "                    </td>\n";
//              $salida .= "                    </tr>\n";  
//             
//             
//             }
//             
 //           $salida .= "                   </table>\n";
         }   

    //$salida .=ThemeCerrarTabla();
    echo $salida; 
  
	
	print(ReturnFooter());
?>