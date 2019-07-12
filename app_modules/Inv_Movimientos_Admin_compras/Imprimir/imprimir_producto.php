<?php
		$_ROOT='../../../';
		$VISTA='HTML';
		include $_ROOT.'includes/enviroment.inc.php';
		IncludeClass('MovBodegasAdminSQL',null,'app','Inv_Movimientos_Admin_compras');
		IncludeClass('MovBodegasSQL',null,'app','Inv_MovimientosBodegas');
    $Empresas=SessionGetVar("EMPRESAS");
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
    $LapsoInicial = $_REQUEST['fecha_inicial'];
    $LapsoFinal = $_REQUEST['fecha_final'];
    $tipo_movimiento = $_REQUEST['tipo_movimiento'];
    $tipo_doc_general_id = $_REQUEST['tipo_doc_general_id'];
    //print_r($_REQUEST['fecha_inicial']);
	//print_r($_REQUEST['fecha_final']);
       
    //vaR_DUMP($limite);
    if($limite=='')
    {
      $limite=null;
    }
    $consulta=new MovBodegasAdminSQL();
    $consulta1=new BodegasProductos();
    $sql=new MovBodegasSQL();
	$producto = $consulta->nom_producto($codigo_producto);
	$resultado2=$consulta->Listado_Documentos_por_Producto($empresa_id,$centro_id,$bodega,$codigo_producto,$LapsoInicial,$LapsoFinal,$tipo_movimiento,$tipo_doc_general_id);
    //$resultado=$consulta1->GetInfoProductoLapsoActual($empresa_id,$centro_id,$bodega,$codigo_producto,$LapsoInicial,$LapsoFinal,$tipo_movimiento,$tipo_doc_general_id);
    
    //$SaldoInicial=$consulta1->ObtenerCierreMes($empresa_id,$centro_id,$bodega,$codigo_producto,$LapsoInicial);
   // $SaldoInicial=0;
    //$SaldoInicial=$consulta1->ObtenerCantidadInicial($empresa_id,$centro_id,$bodega,$codigo_producto,$LapsoInicial);                       
    //Para sacar el saldo Inicial
    //$saldo =0;
    //$saldo =$SaldoInicial['existencia_cierre'];
    /*foreach($SaldoInicial as $key => $v)
    {
    if($v['tipo']=='INGRESO')
    $saldo = $saldo+$v['cantidad'];
    
    if($v['tipo']=='EGRESO')
    $saldo = $saldo - $v['cantidad'];
     }*/
    
    //print_r($saldo);
    echo $consulta1->mensajeDeError;
    $TITLE="DETALLE DEL DOCUMENTO";
    print(ReturnHeader($TITLE));
		print(ReturnBody());
    $path = SessionGetVar("rutaImagenes");
    //$salida .=ThemeAbrirTabla('DETALLE DOCUMENTO');
     //var_dump($resultado);
         ///////////////////////

//         $salida .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
//          $salida .= "                    <tr>\n";
//          $salida .= "                        <td COLSPAN='1' align=\"center\">\n";
//          $salida .= "                         <a>";
//          $salida .= "                           FECHA DE IMPRESION";
//          $salida .= "                         </a>";
//          $salida .= "                        </td>\n";
//          $salida .= "                        <td COLSPAN='1' align=\"center\">\n";
//          $salida .= "                         <a>";
//          $salida .= "                           USUARIO IMPRESION";
//          $salida .= "                         </a>";
//          $salida .= "                        </td>\n";
//          $salida .= "                    </tr>\n";
//          $salida .= "                    <tr>\n";
//          $salida .= "                        <td COLSPAN='1' align=\"center\">\n";
//          $salida .= "                         <a>";
//          $salida .= "                           ".date("Y-m-d H:i:s");
//          $salida .= "                         </a>";
//          $salida .= "                        </td>\n";
//          $salida .= "                        <td COLSPAN='1' align=\"center\">\n";
//          $salida .= "                         <a>";
//          $USUARIO=$consulta->NombreUsu(UserGetUID());
//          $salida .= "                          ".UserGetUID()."-".$USUARIO[0]['nombre'];
//          $salida .= "                         </a>";
//          $salida .= "                        </td>\n";
//          $salida .= "                    </tr>\n";
//          $salida .= "                  </table>\n";



         /////////////////////////



         $salida .= "                 <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td colspan='4' align=\"center\">\n";
         $salida .= "                        <b>KARDEX</b>";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td width=\"20%\" align=\"center\">\n";
         $salida .= "                        <b>EMPRESA</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"35%\" align=\"left\">\n";
         $nombre_empresa=$consulta->ColocarEmpresa($empresa_id);
         $salida .= "                        " .$empresa_id." - ".$nombre_empresa[0]['razon_social'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"20%\" align=\"center\">\n";
         $salida .= "                          <b>FECHA DE IMPRESION</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"25%\" align=\"left\">\n";
         $salida .= "                         <a>";
         $salida .= "                           ".date("Y-m-d H:i:s");
         $salida .= "                         </a>";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\">\n";
         $salida .= "                        <b>NOMBRE BODEGA</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td  align=\"left\">\n";
         $nombre=$consulta->bodegasname($bodega);
         $salida .= "                        <b>BODEGA:</b> ".$bodega."-".$nombre[0]['descripcion'];
         $salida .= "                       </td>\n";
         $salida .= "                       <td  align=\"center\">\n";
         $salida .= "                        <b>USUARIO</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td  align=\"left\">\n";
         $salida .= "                         <a>";
         $USUARIO=$consulta->NombreUsu(UserGetUID());
         $salida .= "                          ".UserGetUID()."-".$USUARIO[0]['nombre'];
         $salida .= "                         </a>";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                 </table>\n";
         $salida .= "                    <br>\n";
         /*
         $salida .= "                 <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td colspan='5' align=\"center\">\n";
         $salida .= "                        <b>DATOS DEL PRODUCTO</b>";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         //$salida .= "                 </table>\n";
         //$salida .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td width=\"10%\" align=\"center\">\n";
         $salida .= "                        <b>CODIGO</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"80%\" align=\"center\">\n";
         $salida .= "                        <b>NOMBRE</b>";
         $salida .= "                       </td>\n";*/
         /*$salida .= "                       <td width=\"15%\" align=\"center\">\n";
         $salida .= "                        UNIDAD";
         $salida .= "                       </td>\n";
         $salida .= "                       <td width=\"25%\" align=\"center\">\n";
         $salida .= "                       <a title='CONTENIDO UNIDAD VENTA'>";
         $salida .= "                        CONTENIDO UNIDAD VENTA";
         $salida .= "                       </a>\n";
         $salida .= "                       </td>\n";*/
        
/*
		$salida .= "                       <td width=\"10%\" align=\"center\">\n";
         $salida .= "                        <b>ESTADO</b>";
         $salida .= "                       </td>\n";
         $salida .= "                     </tr>\n";*/
//          var_dump($resultado);
         //for($i=0;$i<count($resultado);$i++)
        // {
         /*   $salida .= "                    <tr>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['codigo_producto'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['nombre'];
            $salida .= "                       </td>\n";*/
            /*$salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['descripcion_unidad'];
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\">\n";
            $salida .= "                        ".$resultado['contenido_unidad_venta'];
            $salida .= "                       </td>\n";*/
           /* $salida .= "                       <td align=\"center\">\n";
            if($resultado['estado']=='1')
            {
             $salida .= "                        <b>ACTIVO</b>";
            }
            elseif($resultado['estado']=='0')
            {
             $salida .= "                        <b>DESACTIVO</b>";
            }
            $salida .= "                       </td>\n";
    //          $bodega=$consulta->bodegasname($resultado['bodega']);
    //          $salida .= "                        <td align=\"left\">\n";
    //          $salida .= "                          ".$bodega[0]['descripcion'];
    //          $salida .= "                         </td>\n";
            $salida .= "                    </tr>\n";
         //}   
        $salida .= "                   </table>\n";*/
       // $salida .= "                   <br>\n";

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
//           $salida .= "                   <br>\n";   


         /*Solo lo Puedan Visualizar Usuarios con los Privilegios*/	 
		  /*if($Empresas['priv']=='1')
		  {
		 $salida .= "                   <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td colspan='5' align=\"center\">\n";
         $salida .= "                        <b>COSTOS</b>";
         $salida .= "                       </td>\n";
         $salida .= "                    </tr>\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          <b>COSTO</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          <b>COSTO ANTERIOR</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          <b>COSTO ULTIMA COMPRA</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          <b>COSTO PENULTIMA COMPRA</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          <b>TOTAL INVENTARIO</b>";
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
		 }*/
		 /*Fin Privilegios - Costos*/
		 
		 
      //   $salida .= "                   <br>\n";   
         /*$salida .= "                   <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
         $salida .= "                    <tr>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          <b>PRECIO DE VENTA</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          <b>% UTILIDAD</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          <b>PRECIO ANTERIOR</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          <b>PRECIO MAXIMO</b>";
         $salida .= "                       </td>\n";
         $salida .= "                       <td align=\"center\" width='20%'>\n";
         $salida .= "                          <b>PRECIO MINIMO</b>";
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
         $salida .= "                   </table>\n";*/
      //   $salida .= "                   <br>\n";
        /*$salida .= "                   <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                       <td  colspan='8' align=\"center\">\n";
        $salida .= "                        <b>EXISTENCIAS</b>";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                       <td align=\"center\" width='13%'>\n";
        $salida .= "                          <b>INICIAL</b>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" width='12%'>\n";
        $salida .= "                          <b>INGRESOS</b>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" width='13%'>\n";
        $salida .= "                          <b>EGRESOS</b>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" width='12%'>\n";
        $salida .= "                          <b>ACTUAL</b>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" width='13%'>\n";
        $salida .= "                          <b>DESCUADRE</b>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" width='12%'>\n";
        $salida .= "                          <b>GLOBAL</b>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" width='13%'>\n";
        $salida .= "                          <b>MINIMA</b>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" width='12%'>\n";
        $salida .= "                          <b>MAXIMA</b>";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        ".FormatoValor($resultado['existencia_inicial']);
       
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        ".FormatoValor($resultado['ingresos']);
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        ".FormatoValor($resultado['egresos']);
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        ".FormatoValor($resultado['existencia']);
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        ".FormatoValor($resultado['descuadre']);
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        ".FormatoValor($resultado['existencia_global']);
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        ".FormatoValor($resultado['existencia_minima']);
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        ".FormatoValor($resultado['existencia_maxima']);
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                   </table>\n";
        */
       //  $salida .= "                   <br>\n"; 
/*
         if(!empty($resultado['LISTAS_DE_PRECIOS']))
         {
              $salida .= "                 <table BORDER='1' width=\"95%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
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
          }*/
         
        // $salida .= "                    <br>\n";
         //var_dump($resultado['KARDEX']);
                 if($LapsoInicial=="--" && $LapsoFinal=="--")
                 $periodo = date("Y-m");
                 else
                    if($LapsoFinal=="--" && $LapsoInicial!="--")
                    $periodo = $LapsoInicial;
                    else
                        $periodo = $LapsoInicial."->".$LapsoFinal."";
         
//         print_r($resultado);
         if(!empty($resultado2['KARDEX']))
         {
  
            $salida .= "                   <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                       <td colspan='12' align=\"center\">\n";
            $nombre=$consulta->bodegasname($bodega);
            //$salida .= "                        MOVIMIENTO DEL PRODUCTO: <b>".$resultado2['KARDEX'][1]['codigo_producto']."-".$resultado2['KARDEX'][1]['nombre']."</b> EN EL PERIODO  <b>".$periodo."</b>";
            $salida .= "                        MOVIMIENTO DEL PRODUCTO: <b>".$codigo_producto."-".$producto['producto_']."</b> EN EL PERIODO  <b>".$periodo."</b>";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr>\n";
           /* $salida .= "                       <td align=\"center\" width='2%'>\n";
            $salida .= "                         &nbsp; ";
            $salida .= "                       </td>\n";*/
            $salida .= "                       <td align=\"center\" width='2%'>\n";
            $salida .= "                          <a title=\"'I': Ingreso - 'E': Egreso\"><b>T_M</b></a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='13%'>\n";
            $salida .= "                          <b>FECHA</b>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='11%'>\n";
            $salida .= "                          <b>NUMERO</b>";
            $salida .= "                       </td>\n";
           $salida .= "                       <td align=\"center\" width='33%'>\n";
            $salida .= "                          <b>TERCEROS</b>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='11%'>\n";
            $salida .= "                          <b>ENTRADAS</b>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='11%'>\n";
            $salida .= "                          <b>SALIDAS</b>";
            $salida .= "                       </td>\n";
            
            
            /*$salida .= "                       <td align=\"center\" width='9%'>\n";
            $salida .= "                          <b>COSTO TOTAL</b>";
            $salida .= "                       </td>\n";*/
            
            /*$salida .= "                       <td align=\"center\" width='9%'>\n";
            $salida .= "                          <b>EXISTENCIA</b>";
            $salida .= "                       </td>\n";*/
            $salida .= "                       <td align=\"center\" width='8%'>\n";
            $salida .= "                          <b>COSTO</b>";
            $salida .= "                       </td>\n";
            /*$salida .= "                       <td align=\"center\" width='11%'>\n";
            $salida .= "                          <b>LOTE</b>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='11%'>\n";
            $salida .= "                          <b>FECHA VENCIMIENTO</b>";
            $salida .= "                       </td>\n";*/
            /*$salida .= "                       <td align=\"center\" width='11%'>\n";
            $salida .= "                          <b>USUARIO</b>";
            $salida .= "                       </td>\n";*/
//             $salida .= "                       <td align=\"center\" width='7%'>\n";
//             $salida .= "                          CUENTA";
//             $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            
            
            
            //$SALDO_ACT=0;
			
			
			
          //  print_r($resultado['KARDEX']);
        /*  for($i=(count($resultado2['KARDEX'])-1);$i>=0;$i--)
            {
              if($resultado2['KARDEX'][$i]['tipo']=='EGRESO')
               { 
                 $espejo[$i]['tipo']='S';
                 $espejo[$i]['cantidad']=$resultado2['KARDEX'][$i]['cantidad'];
                 $SALDO_ACT=$SALDO_ACT+$espejo[$i]['cantidad'];
               }
               elseif($resultado2['KARDEX'][$i]['tipo']=='INGRESO')
               {
                  $espejo[$i]['tipo']='R';
                  $espejo[$i]['cantidad']=$resultado2['KARDEX'][$i]['cantidad'];
                  $SALDO_ACT=$SALDO_ACT-$espejo[$i]['cantidad'];
               }  
                       
            
            }*/
            
            //var_dump($espejo);
            //$BODEXXXX=$consulta->bodegasname($bodega);
            //var_dump($BODEXXXX);
            /*if($resultado['existencia_inicial']=='0')*/
			
            //$SALDO_ACT = $resultado['existencia_inicial'];
            
			/*$SALDO_ACT = $saldo;*/
            $suma_egresos=0;
            $suma_ingresos=0;
           // $suma_ingresos_pro=0;
            //$suma_egresos_pro=0;
            $suma_egresos_por_costo=0;
            $suma_ingresos_por_costo=0;
			
            for($i=0;$i<count($resultado2['KARDEX']);$i++)
            {
              //$documento_detalle=$consulta->SacarDocumento($empresa_id,$resultado2['KARDEX'][$i]['prefijo'],$resultado2['KARDEX'][$i]['numero']);
              $documento_detalle=$consulta->GetDocDatosAdicionales_mod($empresa_id,$resultado2['KARDEX'][$i]['prefijo'],$resultado2['KARDEX'][$i]['numero'],$resultado2['KARDEX'][$i]['tipo_doc_bodega_id'] );
              //print_r($documento_detalle);
                //$tercero = "";
                  //  if(!empty($documento_detalle['DATOS_ADICIONALES']))
                    //{
					//$tercero  .= "entra al for".$documento_detalle['DATOS_ADICIONALES']['datos']." ";
                        /*foreach($documento_detalle['DATOS_ADICIONALES'] as $doc_val=>$valor)
                        {
                        //$tercero  .= "entra al for".;
                        //$tercero  .= "<b>".$doc_val.": </b>".$valor."    ";
                        //$tercero  .= "<td>";
                        }*/
                 //   }
                   // else
                     //   {
                       // $tercero="";
                        //}
              
              $salida .= "                    <tr>\n";
              /*$salida .= "                       <td align=\"center\">\n";
              $salida .= "                        ".$i+1;
              $salida .= "                       </td>\n";*/
              $salida .= "                       <td align=\"center\">\n";
              $salida .= "                        ".$resultado2['KARDEX'][$i]['tipo_movimiento'];
              $salida .= "                       </td>\n";
              
              $salida .= "                       <td align=\"center\">\n";
              $salida .= "                        ".$resultado2['KARDEX'][$i]['fecha'];
              $salida .= "                       </td>\n";
              $salida .= "                       <td align=\"left\">\n";
              $salida .= "                        ".$resultado2['KARDEX'][$i]['prefijo']."-".$resultado2['KARDEX'][$i]['numero'];
              $salida .= "                       </td>\n";
              $salida .= "                       <td align=\"left\">\n";
              $salida .= "                        ".$documento_detalle['datos'];
              $salida .= "                       </td>\n";
              
              
              $partes=explode(".", $resultado2['KARDEX'][$i]['cantidad']);
             // print_r($resultado['KARDEX']);
              if($partes[1]>0)
              {
                $resultado2['KARDEX'][$i]['cantidad']=$partes[0].".".$partes[1];
              }
              else
              {
               $resultado2['KARDEX'][$i]['cantidad']=$partes[0];
              }
              
              
              //////
              if($resultado2['KARDEX'][$i]['tipo']=='INGRESO')
               { 
			    $salida .= "                       <td align=\"right\">\n";
		          $salida .= "                        ".$resultado2['KARDEX'][$i]['cantidad'];
                  $salida .= "                       </td>\n";
                  $salida .= "                       <td align=\"right\">\n";
                  $salida .= "                        &nbsp;";
                  $salida .= "                       </td>\n";
                 // $suma_ingresos +=$resultado2['KARDEX'][$i]['cantidad'];
				// }} 
               }
               elseif($resultado2['KARDEX'][$i]['tipo']=='EGRESO')
               {   
                  $salida .= "                       <td align=\"right\">\n";
                  $salida .= "                        &nbsp;";
                  $salida .= "                       </td>\n";
                  $salida .= "                       <td align=\"right\">\n";
                  $salida .= "                        ".$resultado2['KARDEX'][$i]['cantidad'];
                  $salida .= "                       </td>\n";
                //$suma_egresos +=$resultado2['KARDEX'][$i]['cantidad'];
                }
                
              /////////
             /* $salida .= "                       <td align=\"right\">\n";
              $salida .= "                        ".FormatoValor($resultado['KARDEX'][$i]['costo']*$resultado['KARDEX'][$i]['cantidad']);
              $salida .= "                       </td>\n";      */
              
              
              
              /*
              
              if($resultado['KARDEX'][$i]['tipo']=='EGRESO')
               { 
                  $suma_egresos_por_costo +=($resultado['KARDEX'][$i]['costo']*$resultado['KARDEX'][$i]['cantidad']);
                  $SALDO_ACT -= $espejo[$i]['cantidad'];
                  $salida .= "                       <td align=\"right\">\n";
                  $salida .= "                        ".$SALDO_ACT;
                  $salida .= "                       </td>\n";
                  
               }
               elseif($resultado['KARDEX'][$i]['tipo']=='INGRESO')
               {
                 $suma_ingresos_por_costo +=($resultado['KARDEX'][$i]['costo']*$resultado['KARDEX'][$i]['cantidad']);
                 $SALDO_ACT += $espejo[$i]['cantidad'];
                 $salida .= "                       <td align=\"right\">\n";
                 $salida .= "                        ".$SALDO_ACT;
                 $salida .= "                       </td>\n";
               }*/
               
              $salida .= "                       <td align=\"right\">\n";
               if($Empresas['priv']=='1')
			  $salida .= "                        ".FormatoValor($resultado2['KARDEX'][$i]['costo']);
              $salida .= "                       </td>\n";
             /* $salida .= "                       <td align=\"center\">\n";
              $salida .= "                        ".$resultado['KARDEX'][$i]['lote'];
              $salida .= "                       </td>\n";
              $salida .= "                       <td align=\"center\">\n";
              $salida .= "                        ".$resultado['KARDEX'][$i]['fecha_vencimiento'];
              $salida .= "                       </td>\n";*/
              /*$salida .= "                       <td align=\"center\">\n";
              $salida .= "                        ".$resultado['KARDEX'][$i]['usuario'];
              $salida .= "                       </td>\n";*/
//               $salida .= "                       <td align=\"center\">\n";
//               $salida .= "                        ".$resultado['KARDEX'][$i]['numerodecuenta'];
//               $salida .= "                       </td>\n";
              $salida .= "                    </tr>\n";
            
            //Para que salgan los terceros Amarrados al documento
                    //print_r($documento_detalle);
                    //!empty($resultado['DATOS_ADICIONALES'])
                  
            /* $salida .= "                     <tr>"; 
             $salida .= "                            <td colspan=\"11\" align=\"\">";
             
             $salida .= "                        <b>OBSERVACION :</b>".$resultado['KARDEX'][$i]['observacion']."";
              if($Empresas['priv']=='1')
			 $salida .= "                        <b>COSTO TOTAL :</b>".FormatoValor($resultado['KARDEX'][$i]['costo']*$resultado['KARDEX'][$i]['cantidad']);
             
             
             //$salida .= "                               ".$tercero;
             $salida .= "                            </td>";
             $salida .= "                     </tr>";*/
            }
            $salida .= "                   </table>\n";

            
            
//             $salida .= "                    <br>\n";
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
//             $salida .= "                   </table>\n";
	//$salida .= "                        <b>PENDIENTES POR COMPRAR DE ".$producto['producto_']."</b>";
        
		}   
        
		
		$salida .= "                   <br>\n";   
	
        /*
        NUEVO DESARROLLO PARA INCLUIR EN EL KARDEX, INFORMACION QUE NO ES DE MOVIMIENTO PERO IMPORTANTE EN LO INFORMATIVO.
        */      
         //$compras=$consulta1->ObtenerProductosPendientesPorComprar($empresa_id,$centro_id,$bodega,$codigo_producto);
         $compras=$consulta->ObtenerProductosPendientesCompras_k($empresa_id,$centro_id,$bodega,$codigo_producto);
         
         if(!empty($compras))
         {
               $salida .= "                 <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
               $salida .= "                    <tr>\n";
               $salida .= "                       <td colspan='5' align=\"center\">\n";
               $salida .= "                        <b>PENDIENTES POR COMPRAR DE ".$producto['producto_']."</b>";
               $salida .= "                       </td>\n";
               $salida .= "                    </tr>\n";
               
               $salida .= "                    <tr align=\"center\">\n";
               $salida .= "                       <td>";
               $salida .= "                         <b>ORDEN DE COMPRA</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>CANTIDAD</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>PROVEEDOR</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>FECHA</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>USUARIO</b>";
               $salida .= "                       </td>";
               $salida .= "                    </tr>\n";
               foreach($compras as $llave => $deta)
               {
               $salida .= "                    <tr>\n";
               $salida .= "                       <td>";
               $salida .= "                         ".$deta['orden_pedido_id'];
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".FormatoValor($deta['cantidad']);
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".$deta['tipo_id_tercero']."-".$deta['tercero_id']." : ".$deta['nombre_tercero'];
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".$deta['fecha_registro'];
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".$deta['usuario'];
               $salida .= "                       </td>";
               $salida .= "                    </tr>\n";
               }
              $salida .= "                   </table>\n";
              $salida .= "                   <br>\n";         
          }//312-6235-323
          
          
          
          /*
        NUEVO DESARROLLO PARA INCLUIR EN EL KARDEX, INFORMACION QUE NO ES DE MOVIMIENTO PERO IMPORTANTE EN LO INFORMATIVO.
        */      
         /*$comprasIngresadas=$consulta1->ObtenerProductosIngresadosCompras($empresa_id,$centro_id,$bodega,$codigo_producto,$LapsoInicial,$LapsoFinal);
         if(!empty($comprasIngresadas))
         {
               $salida .= "                 <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
               $salida .= "                    <tr>\n";
               $salida .= "                       <td colspan='4' align=\"center\">\n";
               $salida .= "                        <b>COMPRAS INGRESADAS DE ".$resultado['nombre']."</b>";
               $salida .= "                       </td>\n";
               $salida .= "                    </tr>\n";
               
               $salida .= "                    <tr align=\"center\">\n";
               $salida .= "                       <td>";
               $salida .= "                         <b>ORDEN DE COMPRA</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>CANTIDAD</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>PROVEEDOR</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>FECHA</b>";
               $salida .= "                       </td>";
               $salida .= "                    </tr>\n";
               foreach($comprasIngresadas as $llave => $detaci)
               {
               $salida .= "                    <tr>\n";
               $salida .= "                       <td>";
               $salida .= "                         ".$detaci['orden_pedido_id'];
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".FormatoValor($detaci['cantidad']);
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".$detaci['tipo_id_tercero']."-".$detaci['tercero_id']." : ".$detaci['nombre_tercero'];
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".$detaci['fecha_registro'];
               $salida .= "                       </td>";
               $salida .= "                    </tr>\n";
               }
              $salida .= "                   </table>\n";
              $salida .= "                   <br>\n";         
          }
*/          
		/*
		NUEVO DESARROLLO PARA INCLUIR EN EL KARDEX, INFORMACION QUE NO ES DE MOVIMIENTO PERO IMPORTANTE EN LO INFORMATIVO.
		*/      
 //        $pendienteFarmacias=$consulta1->ObtenerProductosPendientesDespacharAFarmacias($empresa_id,$centro_id,$bodega,$codigo_producto);
         //print_r($pendienteFarmacias);
      /*   if(!empty($pendienteFarmacias))
         {
               $salida .= "                 <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
               $salida .= "                    <tr>\n";
               $salida .= "                       <td colspan='5' align=\"center\">\n";
               $salida .= "                        <b>PENDIENTES POR DESPACHAR A FARMACIAS DE ".$resultado['nombre']."</b>";
               $salida .= "                       </td>\n";
               $salida .= "                    </tr>\n";
               
               $salida .= "                    <tr align=\"center\">\n";
               $salida .= "                       <td>";
               $salida .= "                         <b># SOLICITUD</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>CANT. SOLICITADA</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>CANT. PENDIENTE</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>FARMACIA</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>USUARIO</b>";
               $salida .= "                       </td>";
               $salida .= "                    </tr>\n";
               foreach($pendienteFarmacias as $llave => $pfdeta)
               {
               $salida .= "                    <tr>\n";
               $salida .= "                       <td>";
               $salida .= "                         ".$pfdeta['solicitud_prod_a_bod_ppal_id'];
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".FormatoValor($pfdeta['cantidad_solicitada']);
               $salida .= "                       </td>";
        //      /* $salida .= "                       <td>";
               //$salida .= "                         ".FormatoValor($pfdeta['cantidad_despachada']);
              // $salida .= "                       </td>";*/
            /*   $salida .= "                       <td>";
               $salida .= "                         ".FormatoValor($pfdeta['cantidad_pendiente']);
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".$pfdeta['razon_social'];
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".$pfdeta['usuario'];
               $salida .= "                       </td>";
               $salida .= "                    </tr>\n";
               }
              $salida .= "                   </table>\n";
              $salida .= "                   <br>\n";         
          }*/
              
		/*
		NUEVO DESARROLLO PARA INCLUIR EN EL KARDEX, INFORMACION QUE NO ES DE MOVIMIENTO PERO IMPORTANTE EN LO INFORMATIVO.
		*/      
 //        $TemporalesFarmacias=$consulta1->ObtenerProductosTemporalesDespacharAFarmacias($empresa_id,$centro_id,$bodega,$codigo_producto);
         //print_r($pendienteFarmacias);
        /* if(!empty($TemporalesFarmacias))
         {
               $salida .= "                 <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
               $salida .= "                    <tr>\n";
               $salida .= "                       <td colspan='5' align=\"center\">\n";
               $salida .= "                        <b>PENDIENTES POR CONFIRMAR EN PEDIDOS DE FARMACIAS (TEMPORALES) DE ".$resultado['nombre']."</b>";
               $salida .= "                       </td>\n";
               $salida .= "                    </tr>\n";
               
               $salida .= "                    <tr align=\"center\">\n";
               $salida .= "                       <td>";
               $salida .= "                         <b>CANTIDAD</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>FARMACIA</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>USUARIO</b>";
               $salida .= "                       </td>";
               $salida .= "                    </tr>\n";
               foreach($TemporalesFarmacias as $llave => $temp_farm)
               {
               $salida .= "                    <tr>\n";
               $salida .= "                       <td>";
               $salida .= "                         ".FormatoValor($temp_farm['cantidad']);
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".$temp_farm['farmacia'];
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".$temp_farm['usuario_id']."-".$temp_farm['nombre'];
               $salida .= "                       </td>";
              
               $salida .= "                    </tr>\n";
               }
              $salida .= "                   </table>\n";
              $salida .= "                   <br>\n";         
          }*/
          
             /*
        NUEVO DESARROLLO PARA INCLUIR EN EL KARDEX, INFORMACION QUE NO ES DE MOVIMIENTO PERO IMPORTANTE EN LO INFORMATIVO.
        */      
//         $pendienteClientes=$consulta1->ObtenerProductosPendientesDespacharAClientes($empresa_id,$centro_id,$bodega,$codigo_producto);
         //print_r($pendienteFarmacias);
         /*if(!empty($pendienteClientes))
         {
               $salida .= "                 <table width=\"95%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
               $salida .= "                    <tr>\n";
               $salida .= "                       <td colspan='5' align=\"center\">\n";
               $salida .= "                        <b>PENDIENTES POR DESPACHAR A CLIENTES DE ".$resultado['nombre']."</b>";
               $salida .= "                       </td>\n";
               $salida .= "                    </tr>\n";
               
               $salida .= "                    <tr align=\"center\">\n";
               $salida .= "                       <td>";
               $salida .= "                         <b># PEDIDO</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>CANT. SOLICITADA</b>";
               $salida .= "                       </td>";
               /*$salida .= "                       <td>";
               $salida .= "                         <b>CANT. DESPACHADA</b>";
               $salida .= "                       </td>";*/
           /*    $salida .= "                       <td>";
               $salida .= "                         <b>CLIENTE</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>FECHA</b>";
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         <b>USUARIO</b>";
               $salida .= "                       </td>";
               $salida .= "                    </tr>\n";
               foreach($pendienteClientes as $llave => $pcdeta)
               {
               $salida .= "                    <tr>\n";
               $salida .= "                       <td>";
               $salida .= "                         ".$pcdeta['pedido_cliente_id'];
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".FormatoValor($pcdeta['numero_unidades']);
               $salida .= "                       </td>";
              /* $salida .= "                       <td>";
               $salida .= "                         ".FormatoValor($pfdeta['cantidad_despachada']);
               $salida .= "                       </td>";*/
           /*     $salida .= "                       <td>";
               $salida .= "                         ".$pcdeta['tipo_id_tercero']."-".$pcdeta['tercero_id']." : ".$pcdeta['nombre_tercero'];
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".$pcdeta['fecha_registro'];
               $salida .= "                       </td>";
               $salida .= "                       <td>";
               $salida .= "                         ".$pcdeta['usuario'];
               $salida .= "                       </td>";
               $salida .= "                    </tr>\n";
               }
              $salida .= "                   </table>\n";
              $salida .= "                   <br>\n";         
          }*/
    //$salida .=ThemeCerrarTabla();
    echo $salida; 
  
	
	print(ReturnFooter());
?>