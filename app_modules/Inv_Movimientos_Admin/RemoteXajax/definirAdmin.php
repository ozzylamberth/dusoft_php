<?php
	/**
	* $Id: definirAdmin.php,v 1.2 2011/05/19 22:19:10 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Jaime gomez
	**/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	//include "../../../app_modules/InvTomaFisica/classes/TomaFisicaSQL.class.php";
	//include "../../../app_modules/InvTomaFisica/RemoteXajax/definirToma.js";
    include "../../../classes/ClaseHTML/ClaseHTML.class.php";

    /**
    * Metodo para rotornar la lista de dias de segun el lapso contable que se escoja
    * @return string $salida  
    * @access public
   */
    function PonerNuevosDias($lapso)
    {

        $objResponse = new xajaxResponse();
       if(!empty($lapso))
        {
            $ano = substr($lapso, 0, 4);
            $mes = substr($lapso, 4, 2);
            //$fecha_inicial = date('d',mktime(0, 0, 0, $mes, 1, $ano));
            $fecha_final = date('d',mktime(0, 0, 0, $mes+1, 0, $ano));
    
            $salida .="                           <option value=\"-\" SELECTED>----</option> \n";
            for($i=1;$i<$fecha_final;$i++)
            {
                $salida .="                           <option value=\"".$i."\">".$i."</option> \n";
            }
    
            $salida1 .="                           <option value=\"-\" SELECTED>----</option> \n";
            for($i=1;$i<=$fecha_final;$i++)
            {
                $salida1 .="                           <option value=\"".$i."\">".$i."</option> \n";
            }

        }
        else
        {       
            $salida .="                           <option value=\"-\" SELECTED>----</option> \n";
            $salida1.="                           <option value=\"-\" SELECTED>----</option> \n";
        }

        $objResponse->assign("dia1","innerHTML",$salida);
        $objResponse->assign("dia2","innerHTML",$salida1);
        //$objResponse->assign("dia1","disabled",false);
        //$objResponse->assign("dia2","disabled",false);
        return $objResponse;
    }
   /**
    * Metodo para rotornar el select con lOS GRUPOS del buscador 
    * @return string $salida retorna la forma para la edicion del precio. 
    * @access public
   */
   
    function PonerGrupoVolver($seleccionado)
    {
        $consulta=new MovBodegasAdminSQL();
        $objResponse = new xajaxResponse();
        $Grupos=$consulta->GetGrupos();
          //var_dump($clasex);
        if(!empty($Grupos))
        {
            $salida .="                         <select name=\"grupos_pro\" id=\"grupos_pro\" class=\"select\" onchange=\"GetClasex(this.value);\">";
            $salida .="                           <option value=\"0\">SELECCIONAR</option> \n";         
            for($i=0;$i<count($Grupos);$i++)
            {
            if($seleccionado==$Grupos[$i]['grupo_id'])
            {
                $salida .="                           <option value=\"".$Grupos[$i]['grupo_id']."\" selected>".strtoupper($Grupos[$i]['descripcion'])."</option> \n";
            }
            else
            {
                $salida .="                           <option value=\"".$Grupos[$i]['grupo_id']."\">".strtoupper($Grupos[$i]['descripcion'])."</option> \n";
            }
    
            }
            $salida .="                         </select>\n";
            $objResponse->assign("supergrupos","innerHTML",$salida);
        }
        return $objResponse;
    }

    /**
    *pop up para imprimir
    *@return $salida con el formulario a pintar
    **/
        
    function RetornarImpresionDoc1($direccion,$alt,$empresa_id,$centro_id,$bodega,$codigo,$fecha_inicio_lapso,$fecha_final_lapso,$tipo_movimiento,$tipo_doc_general_id)
    {    
        global $VISTA;
        $salida1 ="<a title='".$alt."' href=javascript:Imprimir1('".trim($direccion)."','".trim($empresa_id)."','".trim($centro_id)."','".trim($bodega)."','".trim($codigo)."','".$fecha_inicio_lapso."','".$fecha_final_lapso."','".$tipo_movimiento."','".$tipo_doc_general_id."')>".$codigo."</a>";
        return $salida1;
    }
 
 
    /**
    * funcion que muestra el paginador
    *
    * @param string $path ruta de las imagenes
    * @param integer $slc cantidad ttal de registros
    * @param string $op posicion del paginador
    * @param string $empresa
    * @param string $centro_id
    * @param string $bodega
    * @param string $codigo_pro
    * @param string $nom_pro
    * @param string $grupos_pro
    * @param string $clasexx clase del producto
    * @param string $subclasexy subclase del producto
    * @param string $pagina a mostrar
    * @return $salida con el formulario a pintar
    **/
    function ObtenerPaginadoPro($path,$slc,$op,$empresa,$centro_id,$bodega,$codigo_pro,$nom_pro,$grupos_pro,$clasexx,$subclasexy,$fecha_inicio,$fecha_final,$tipo_movimiento,$tipo_doc_general_id,$centro_utilidad_bus,$bodega_bus,$molecula_bus,$pagina)
    {
      $path = GetThemePath();
        $TotalRegistros = $slc;
        $TotalRegistros;
        $TablaPaginado = "";
        
        if($limite == null)
        {
            $uid = UserGetUID();
            $LimitRow = 20;//intval(GetLimitBrowser())
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
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Páginas:</td>\n";
            if($pagina > 1)
            {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                                                                            //   $path,$slc,$op,$empresa,$centro,$bodega,$codigo,$nombre,$grupo,$clase,$subclase,$pagina
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:GetLixtado('".$empresa."','".$centro_id."','".$bodega."','".$codigo_pro."','".$nom_pro."','".$grupos_pro."','".$clasexx."','".$subclasexy."','".$fecha_inicio."','".$fecha_final."','".$tipo_movimiento."','".$tipo_doc_general_id."','".$centro_utilidad_bus."','".$bodega_bus."','".$molecula_bus."','1')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:GetLixtado('".$empresa."','".$centro_id."','".$bodega."','".$codigo_pro."','".$nom_pro."','".$grupos_pro."','".$clasexx."','".$subclasexy."','".$fecha_inicio."','".$fecha_final."','".$tipo_movimiento."','".$tipo_doc_general_id."','".$centro_utilidad_bus."','".$bodega_bus."','".$molecula_bus."','".($pagina-1)."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
                $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:GetLixtado('".$empresa."','".$centro_id."','".$bodega."','".$codigo_pro."','".$nom_pro."','".$grupos_pro."','".$clasexx."','".$subclasexy."','".$fecha_inicio."','".$fecha_final."','".$tipo_movimiento."','".$tipo_doc_general_id."','".$centro_utilidad_bus."','".$bodega_bus."','".$molecula_bus."','".$i."')\">".$i."</a></td>\n";
            }
            $columnas++;
            }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:GetLixtado('".$empresa."','".$centro_id."','".$bodega."','".$codigo_pro."','".$nom_pro."','".$grupos_pro."','".$clasexx."','".$subclasexy."','".$fecha_inicio."','".$fecha_final."','".$tipo_movimiento."','".$tipo_doc_general_id."','".$centro_utilidad_bus."','".$bodega_bus."','".$molecula_bus."','".($pagina+1)."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:GetLixtado('".$empresa."','".$centro_id."','".$bodega."','".$codigo_pro."','".$nom_pro."','".$grupos_pro."','".$clasexx."','".$subclasexy."','".$fecha_inicio."','".$fecha_final."','".$tipo_movimiento."','".$tipo_doc_general_id."','".$centro_utilidad_bus."','".$bodega_bus."','".$molecula_bus."','".$NumeroPaginas."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     Página&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
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

    
  /**
    * Metodo para rotornar toda la informacion con respecto a un producto
    * @param string $empresa_id
    * @param string $centro_id
    * @param string $bodega
    * @param string $codigo_producto
    * @param string $limite
    * @param string $pagina
    * @return string $salida retorna la forma para la edicion del precio. 
    * @access public
   */  
    function InfoProducto($empresa_id,$centro_id,$bodega,$codigo_producto,$limite,$pagina)
    {
        $path = SessionGetVar("rutaImagenes");
        $consulta=new MovBodegasAdminSQL();
        $consulta1=new BodegasProductos();
        $resultado=$consulta1->GetInfoProducto($empresa_id,$centro_id,$bodega,$codigo_producto,$limite);
               // GetInfoProductoConFiltros($empresa_id,$centro_id,$bodega,$codigo_producto,$count=null, $limit=null, $offset=null, $fecha_inicial=null, $fecha_final=null, $tipo=null, $tipo_movimiento=null) 
        $objResponse = new xajaxResponse();
//     $objResponse->alert($empresa_id);
//     $objResponse->alert($centro_id);
//     $objResponse->alert($bodega);
//     $objResponse->alert($codigo_producto);
//     $objResponse->alert($limite);                        
//     $objResponse->alert($pagina);             
        $salida .= "                 <table width=\"90%\" border='0' align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td colspan='2' align=\"center\">\n";
        $salida .= "                        CONSULTA DE PRODUCTOS";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                       <td class=\"modulo_table_list_title\" width=\"35%\" align=\"center\">\n";
        $salida .= "                        CODIGO DE LA EMPRESA";
        $salida .= "                       </td>\n";
        $salida .= "                       <td class=\"modulo_list_claro\" width=\"65%\" align=\"center\">\n";
        $salida .= "                        ".$empresa_id;
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                       <td width=\"35%\" align=\"center\" class=\"modulo_table_list_title\">\n";
        $salida .= "                        NOMBRE EMPRESA";
        $salida .= "                       </td>\n";
        $salida .= "                       <td class=\"modulo_list_claro\" width=\"65%\" align=\"center\">\n";
        $nombre_empresa=$consulta->ColocarEmpresa($empresa_id);
        $salida .= "                        ".$nombre_empresa[0]['razon_social'];
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                 </table>\n";
        $salida .= "                    <br>\n";
        $salida .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                       <td colspan='5' align=\"center\" class=\"modulo_table_list_title\">\n";
        $salida .= "                        DATOS DEL PRODUCTO";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        //$salida .= "                 </table>\n";
        //$salida .= "                 <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                       <td class=\"modulo_table_list_title\" width=\"10%\" align=\"center\">\n";
        $salida .= "                        CODIGO";
        $salida .= "                       </td>\n";
        $salida .= "                       <td class=\"modulo_table_list_title\" width=\"40%\" align=\"center\">\n";
        $salida .= "                        NOMBRE";
        $salida .= "                       </td>\n";
        $salida .= "                       <td class=\"modulo_table_list_title\" width=\"15%\" align=\"center\">\n";
        $salida .= "                        UNIDAD";
        $salida .= "                       </td>\n";
        $salida .= "                       <td class=\"modulo_table_list_title\" width=\"25%\" align=\"center\">\n";
        $salida .= "                       <a title='CONTENIDO UNIDAD VENTA'>";
        $salida .= "                        CONTENIDO UNIDAD VENTA";
        $salida .= "                       </a>\n";
        $salida .= "                       </td>\n";
        $salida .= "                       <td class=\"modulo_table_list_title\" width=\"10%\" align=\"center\">\n";
        $salida .= "                        ESTADO";
        $salida .= "                       </td>\n";
        $salida .= "                     </tr>\n";
         
        //for($i=0;$i<count($resultado);$i++)
    // {
        $salida .= "                    <tr>\n";
        $salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
        $salida .= "                        ".$resultado['codigo_producto'];
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
        $salida .= "                        ".$resultado['descripcion'];
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
        $salida .= "                        ".$resultado['descripcion_unidad'];
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
        $salida .= "                        ".$resultado['contenido_unidad_venta'];
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
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
        $salida .= "                   <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\" >\n";
        $salida .= "                       <td colspan='3' align=\"center\">\n";
        $salida .= "                        EXISTENCIAS";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                       <td align=\"center\" class=\"modulo_table_list_title\" width='34%'>\n";
        $salida .= "                          EXISTENCIA";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" width='33%' class=\"modulo_table_list_title\">\n";
        $salida .= "                          EXISTENCIA MAXIMA";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" width='33%' class=\"modulo_table_list_title\">\n";
        $salida .= "                          EXISTENCIA MINIMA";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
        $salida .= "                        ".$resultado['existencia'];
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
        $salida .= "                        ".$resultado['existencia_maxima'];
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" class=\"modulo_list_claro\">\n";
        $salida .= "                        ".$resultado['existencia_minima'];
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                   </table>\n";
        $salida .= "                   <br>\n";   
        if(!empty($resultado['EXISTENCIAS']))
        {
            $salida .= "                   <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                       <td colspan='6' align=\"center\" class=\"modulo_table_list_title\">\n";
            $salida .= "                        EXISTENCIAS POR BODEGAS";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td align=\"center\" width='10%'>\n";
            $salida .= "                          CENTRO DE UTILIDAD";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='10%'>\n";
            $salida .= "                          BODEGA";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='41%'>\n";
            $salida .= "                          DESCRIPCION";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='13%'>\n";
            $salida .= "                          EXISTENCIA";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='13%'>\n";
            $salida .= "                          EXISTENCIA MINIMA";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='13%'>\n";
            $salida .= "                          EXISTENCIA MAXIMA";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            for($i=0;$i<count($resultado['EXISTENCIAS']);$i++)
            {
                $salida .= "                    <tr class=\"modulo_list_claro\">\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['EXISTENCIAS'][$i]['centro_utilidad'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['EXISTENCIAS'][$i]['bodega'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['EXISTENCIAS'][$i]['descripcion'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['EXISTENCIAS'][$i]['existencia'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['EXISTENCIAS'][$i]['existencia_minima'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['EXISTENCIAS'][$i]['existencia_maxima'];
                $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
            }
            $salida .= "                   </table>\n";
        }   
        $salida .= "                   <br>\n";
        $salida .= "                   <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr>\n";
        $salida .= "                       <td class=\"modulo_table_list_title\" colspan='4' align=\"center\">\n";
        $salida .= "                        COSTOS";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\" width='25%'>\n";
        $salida .= "                          COSTO";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" width='25%'>\n";
        $salida .= "                          COSTO ANTERIOR";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" width='25%'>\n";
        $salida .= "                          COSTO ULTIMA COMPRA";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\" width='25%'>\n";
        $salida .= "                          COSTO PENULTIMA COMPRA";
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        ".$resultado['costo'];
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        ".$resultado['costo_anterior'];
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        ".$resultado['costo_ultima_compra'];
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        ".$resultado['costo_penultima_compra'];
        $salida .= "                       </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                   </table>\n";
        $salida .= "                   <br>\n";   
//          $salida .= "                   <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
//          $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
//          $salida .= "                       <td align=\"center\" width='20%'>\n";
//          $salida .= "                          PRECIO DE VENTA";
//          $salida .= "                       </td>\n";
//          $salida .= "                       <td align=\"center\" width='20%'>\n";
//          $salida .= "                          % UTILIDAD";
//          $salida .= "                       </td>\n";
//          $salida .= "                       <td align=\"center\" width='20%'>\n";
//          $salida .= "                          PRECIO ANTERIOR";
//          $salida .= "                       </td>\n";
//          $salida .= "                       <td align=\"center\" width='20%'>\n";
//          $salida .= "                          PRECIO MAXIMO";
//          $salida .= "                       </td>\n";
//          $salida .= "                       <td align=\"center\" width='20%'>\n";
//          $salida .= "                          PRECIO MINIMO";
//          $salida .= "                       </td>\n";
//          $salida .= "                    </tr>\n";
//          $salida .= "                    <tr>\n";
//          $salida .= "                       <td align=\"center\">\n";
//          $salida .= "                        ".$resultado['precio_venta'];
//          $salida .= "                       </td>\n";
//          $salida .= "                       <td align=\"center\">\n";
//          $salida .= "                        ".$resultado['porcentaje_utlidad'];
//          $salida .= "                       </td>\n";
//          $salida .= "                       <td align=\"center\">\n";
//          $salida .= "                        ".$resultado['precio_venta_anterior'];
//          $salida .= "                       </td>\n";
//          $salida .= "                       <td align=\"center\">\n";
//          $salida .= "                        ".$resultado['precio_maximo'];
//          $salida .= "                       </td>\n";
//          $salida .= "                       <td align=\"center\">\n";
//          $salida .= "                        ".$resultado['precio_minimo'];
//          $salida .= "                       </td>\n";
//          $salida .= "                    </tr>\n";
//          $salida .= "                   </table>\n";
//          $salida .= "                   <br>\n"; 
// 
//          if(!empty($resultado['LISTAS_DE_PRECIOS']))
//          {
//               $salida .= "                 <table BORDER='1' width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
//               $salida .= "                    <tr>\n";
//               $salida .= "                        <td COLSPAN='7' align=\"center\">\n";
//               $salida .= "                         <a>";
//               $salida .= "                           PRECIOS SEGUN LISTAS";
//               $salida .= "                         </a>";
//               $salida .= "                        </td>\n";
//               $salida .= "                    </tr>\n";
//               $salida .= "                    <tr>\n";
//               $salida .= "                        <td width='15%' align=\"center\">\n";
//               $salida .= "                           CODIGO LISTA ";
//               $salida .= "                        </td>\n";
//               $salida .= "                        <td width='40%' align=\"center\">\n";
//               $salida .= "                           NOMBRE LISTA ";
//               $salida .= "                        </td>\n";
//               $salida .= "                        <td width='20%' align=\"center\">\n";
//               $salida .= "                           PRECIO VENTA ";
//               $salida .= "                        </td>\n";
//               $salida .= "                        <td width='25%' align=\"center\">\n";
//               $salida .= "                           PORCENTAJE UTILIDAD ";
//               $salida .= "                        </td>\n";
//               $salida .= "                    </tr>\n";
//             foreach($resultado['LISTAS_DE_PRECIOS'] as $doc_val=>$valor)
//             {
//                 //var_dump($resultado['DETALLE']);
//                 $salida .= "                    <tr>\n";
//                 $salida .= "                      <td align=\"left\">\n";
//                 $salida .= "                       ".$valor['codigo_lista'];
//                 $salida .= "                      </td>\n";
//                 $salida .= "                      <td align=\"left\">\n";
//                 $salida .= "                       ".$valor['descripcion'];
//                 $salida .= "                      </td>\n";
//                 $salida .= "                      <td align=\"right\">\n";
//                 $salida .= "                       ".$valor['precio_venta'];
//                 $salida .= "                      </td>\n";
//                 $salida .= "                      <td align=\"right\">\n";
//                 $salida .= "                       ".$valor['porcentaje_utlidad'];
//                 $salida .= "                      </td>\n";
//                 $salida .= "                    </tr>\n";
//             }
//               $salida .= "                   </table>\n";
//           }
//          
//          $salida .= "                    <br>\n";
         //var_dump($resultado['KARDEX']);
        if(!empty($resultado['KARDEX']))
        {
            $salida .= "                   <table width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td colspan='12' align=\"center\">\n";
            $salida .= "                        KARDEX";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td align=\"center\" width='2%'>\n";
            $salida .= "                         &nbsp; ";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='4%'>\n";
            $salida .= "                          TIPO";
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
            $salida .= "                       <td align=\"center\" width='8%'>\n";
            $salida .= "                          COSTO";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='9%'>\n";
            $salida .= "                          CANTIDAD";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='9%'>\n";
            $salida .= "                          EXISTENCIA ANTERIOR";
            $salida .= "                       </td>\n";
            $salida .= "                       <td align=\"center\" width='9%'>\n";
            $salida .= "                          EXISTENCIA ACTUAL";
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
            $SALDO_ACT=$resultado['existencia'];
            for($i=0;$i<count($resultado['KARDEX']);$i++)
            {
                $salida .= "                    <tr class=\"modulo_list_claro\">\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$i;
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['KARDEX'][$i]['tipo'];
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
                $salida .= "                       <td align=\"left\">\n";
                $salida .= "                        ".$resultado['KARDEX'][$i]['observacion'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"right\">\n";
                $salida .= "                        ".$resultado['KARDEX'][$i]['costo'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $partes=explode(".", $resultado['KARDEX'][$i]['cantidad']);
                if($partes[1]>0)
                {
                    $resultado['KARDEX'][$i]['cantidad']=$partes[0].".".$partes[1];
                }
                else
                {
                    $resultado['KARDEX'][$i]['cantidad']=$partes[0];
                }
              
                $salida .= "                        ".$resultado['KARDEX'][$i]['cantidad'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"right\">\n";
                if($resultado['KARDEX'][$i]['tipo']=='EGRESO')
                { 
                    $SALDO_ACT1=$SALDO_ACT+$espejo[$i]['cantidad'];
                }
                elseif($resultado['KARDEX'][$i]['tipo']=='INGRESO')
                {
                    $SALDO_ACT1=$SALDO_ACT-$espejo[$i]['cantidad'];
                }  
                $salida .= "                        ".$SALDO_ACT1;
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"right\">\n";
                $salida .= "                        ".$SALDO_ACT;
                $SALDO_ACT=$SALDO_ACT1;
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"center\">\n";
                $salida .= "                        ".$resultado['KARDEX'][$i]['usuario'];
                $salida .= "                       </td>\n";
//               $salida .= "                       <td align=\"center\">\n";
//               $salida .= "                        ".$resultado['KARDEX'][$i]['numerodecuenta'];
//               $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
            }
            $salida .= "                   </table>\n";
        }   
        $salida .= "                   <br>\n";
        $objResponse->assign("ContenidoCent","innerHTML",$salida);
        return $objResponse;
    }                   
  
  
  /**
    * Metodo para retornar toda la informacion al listado de productos que resulten de una consulta 
    * @param string $empresa_id
    * @param string $centro_id
    * @param string $bodega
    * @param string $codigo_producto
    * @param string $nom_pro
    * @param string $grupo
    * @param string $clase
    * @param string $pagina
    * @return string $salida retorna la forma para la edicion del precio. 
    * @access public
   */  
    function GetLixtadox($empresa_id,$centro_id,$bodega,$codigo_pro,$nom_pro,$grupo,$clase,$subclase,$fecha_inicio,$fecha_final,$tipo_movimiento,$tipo_doc_general_id,$centro_utilidad_bus,$bodega_bus,$molecula_bus,$pagina)
    {
      $path = GetThemePath();
      $consulta=new MovBodegasAdminSQL();
      $Empresas=SessionGetVar("EMPRESAS");
      
	  /*EN CASO DE Q SE QUIERA CONSULTAR UNA BODEGA DIFERENTE A LA QUE SE SELECCIONÒ EN EL LOGIN*/
	  if(!empty($centro_utilidad_bus) && !empty($bodega_bus))
	  {
	  $centro_id =$centro_utilidad_bus;
	  $bodega =$bodega_bus;
	  }
	  
	  /*$centro_utilidad_bus,$bodega_bus*/
	  
      $consulta1=AutoCarga::Factory("BodegasProductos");
      $objResponse = new xajaxResponse();
        
      list( $dia1, $mes1, $ano1 ) = split( '[/.-]', $fecha_inicio ); 
      list( $dia2, $mes2, $ano2 ) = split( '[/.-]', $fecha_final ); 
       
      $fecha_inicio_lapso = $ano1."-".$mes1;
      $fecha_final_lapso = $ano2."-".$mes2;
       
      if($fecha_inicio!="")
      {
       $mensaje_inicio ="Lapso Inicio: ".$fecha_inicio_lapso;
      }
       
      if($fecha_final!="")
      {
       $mensaje_final ="Lapso Final: ".$fecha_final_lapso;
      }
         
      $mensaje = $mensaje_inicio." - ".$mensaje_final;
         
      $VECTOR1=$consulta1->GetBodegaProductos($empresa_id,$centro_id,$bodega,$codigo_pro,$nom_pro,$grupo,$clase,$subclase,$molecula_bus,$count=1, $limit=null, $offset=null, $orderby =null);
         
        $limit=20;
        $pagina1=($pagina-1)*$limit;
        $offset=$pagina1;
        $VECTOR=$consulta1->GetBodegaProductos($empresa_id,$centro_id,$bodega,$codigo_pro,$nom_pro,$grupo,$clase,$subclase,$molecula_bus,$count=null, $limit, $offset, $orderby = 'ASC');
        /*$prueba=$consulta1->GetBodegaProductos($empresa_id,$centro_id,$codigo_pro,$nom_pro,$grupo,$clase,$subclase,$count=null, $limit, $offset, $orderby = 'ASC');*/
        
        if(!empty($VECTOR))
        {
            $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                  <tr>\n";
            $salida .= "                   <td align='left' class=\"modulo_table_list_title\">\n";
            $salida .= "                   ".$mensaje." LIMITE DE REGISTROS PARA EL KARDEX";
            $salida .= "                   </td>\n";
            
            $salida .= "                   <td align='left' class='modulo_list_claro'>\n";
            $salida .= "                         <select name=\"limit\" id=\"limit\" class=\"select\">";
            $salida .="                           <option value=\"10\" selected>10</option> \n";
            $salida .="                           <option value=\"50\">50</option> \n";
            $salida .="                           <option value=\"100\">100</option> \n";
            $salida .="                           <option value=\"200\">200</option> \n";
            $salida .="                           <option value=\"400\">400</option> \n";
            $salida .="                           <option value=\"500\">500</option> \n";
            $salida .="                           <option value=\"600\">600</option> \n";
            $salida .="                           <option value=\"700\">700</option> \n";
            $salida .="                           <option value=\"1000\">1000</option> \n";
            $salida .="                           <option value=\"\">TODOS</option> \n";
            $salida .="                         </select>\n";
            $salida .= "                   </td>\n";
            $salida .= "                  </tr>\n";
            $salida .= "                 </table>\n";
            //$salida .= "                 <br>\n";
            $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                          CODIGO";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"50%\" align=\"center\">\n";
            $salida .= "                          NOMBRE";
            $salida .= "                       </td>\n";
            /*$salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                          UNIDAD";
            $salida .= "                       </td>\n";*/
            $salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                          EXISTENCIA";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                          COSTO";
            $salida .= "                       </td>\n";

            $salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                          COSTO ULT. COMPRA";
            $salida .= "                       </td>\n";			
			
            $salida .= "                       <td width=\"14%\" align=\"center\">\n";
    //         $salida .= "                         <a title='COSTO ANTERIOR'>\n";
            $salida .= "                          EXISTENCIA TOTAL";
    //         $salida .= "                         </a>\n";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                          PRECIO";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"5%\" align=\"center\">\n";
            $salida .= "                          IVA";
            $salida .= "                       </td>\n";			
            /*$salida .= "                       <td width=\"4%\" align=\"center\">\n";
            $salida .= "                         <a title='PORCENTAJE DE UTILIDAD '>\n";
            $salida .= "                          PU";
            $salida .= "                       </td>\n";*/
            /*$salida .= "                       <td width=\"\" align=\"center\">\n";
            $salida .= "                          ";
            $salida .= "                       </td>\n";*/
            $salida .= "                    </tr>\n";         
            for($i=0;$i<count($VECTOR);$i++)
            {
                $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $salida .= "                       <td align=\"center\">\n";
                                                    $direccion="app_modules/Inv_Movimientos_Admin/Imprimir/imprimir_producto.php";
                                                    $codigo = $VECTOR[$i]['codigo_producto'];//"themes/$VISTA/" . GetTheme() ."/images//imprimir.png";
                                                    $alt="VER INFORMACION DEL PRODUCTO";
                                                    $x=RetornarImpresionDoc1(trim($direccion),$alt,trim($empresa_id),trim($centro_id),trim($bodega),$VECTOR[$i]['codigo_producto'],$fecha_inicio_lapso,$fecha_final_lapso,$tipo_movimiento,$tipo_doc_general_id);
                $salida .= "                       ".$x."";
               	//$salida .= "                       ".$VECTOR[$i]['codigo_producto'];
                $salida .= "                       </td>\n";
                //$salida .= "<pre>".print_r($prueba,true)."</pre>";
                $salida .= "                       <td align=\"left\">\n";
                $salida .= "                       ".$VECTOR[$i]['nombre'];
                $salida .= "                       </td>\n";
                /*$salida .= "                       <td align=\"center\">\n";
                $salida .= "                       ".$VECTOR[$i]['descripcion_unidad'];
                $salida .= "                       </td>\n";*/
                $salida .= "                       <td align=\"right\">\n";
                $salida .= "                       ".$VECTOR[$i]['existencia'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td align=\"right\">\n";
                if($Empresas['priv']=='1')
				$salida .= "                       ".$VECTOR[$i]['costo'];
                $salida .= "                       </td>\n";

                $salida .= "                       <td align=\"right\">\n";
                if($Empresas['priv']=='1')
				$salida .= "                       ".$VECTOR[$i]['costo_ultima_compra'];
                $salida .= "                       </td>\n";				
				
                $salida .= "                       <td align=\"right\">\n";
                $salida .= "                       ".$VECTOR[$i]['existencia_global'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td id='".$VECTOR[$i]['codigo_producto']."' align=\"right\">\n";
                $salida .= "                       ".$VECTOR[$i]['precio_venta'];
                $salida .= "                       </td>\n";
                $salida .= "                       <td id='".$VECTOR[$i]['codigo_producto']."' align=\"right\">\n";
                $salida .= "                       ".$VECTOR[$i]['iva_pdcto'];
                $salida .= "                       </td>\n";				
               /* $salida .= "                       <td id='".$VECTOR[$i]['codigo_producto']."' align=\"right\">\n";
                $salida .= "                       ".$VECTOR[$i]['porcentaje_utlidad']."%";
                $salida .= "                       </td>\n";*/
              /*  $salida .= "                       <td align=\"center\">\n";
                  //$empresa_id,$centro_id,$bodega,$codigo_producto,$limite,$pagina                                                                        //             $VECTOR=$consulta1->GetBodegaProductos($empresa_id,$centro_id,$bodega,$codigo_pro,$nom_pro,$grupo,$clase,$subclase,$count=null, $limit, $offset, $orderby = 'ASC');                                                                                                              $pagina=$_REQUEST['Bodeguix']['bodega'];$fecha_inicio,$fecha_final 
                $CONSULTARPRO=ModuloGetURL('app','Inv_Movimientos_Admin','user','DatosProducto',array('empresa_id'=>$empresa_id,'centro_id'=>$centro_id,'bodega'=>$bodega,'codigo_producto'=>$VECTOR[$i]['codigo_producto'],'codigo_pro_bus'=>$codigo_pro,'nombre'=>$nom_pro,'grupo'=>$grupo,'clasex'=>$clase,'subclasex'=>$subclase,'fecha_inicio_lapso'=>$fecha_inicio,'fecha_final_lapso'=>$fecha_final,'limite'=>15,'pagina'=>'1'));
    
                $salida .= "                          <a  title=\"VER KARDEX DEL PRODUCTO\" class=\"label_error\" href=\"".$CONSULTARPRO."\">\n";
                $salida .= "                          <sub><img src=\"".$path."/images/mail_find.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
                $salida .= "                         ";
                $salida .= "                       </td>\n";*/
                $salida .= "                    </tr>\n";
            }   
            $salida .= "                  </table>";
        
                                            //$empresa_id,$centro_id,$bodega,$codigo_pro,$nom_pro,$grupo,$clase,$subclase,$count=null, $limit=20, $offset=0, $orderby = 'ASC'
            $salida .="".ObtenerPaginadoPro($path,$VECTOR1,'1',$empresa_id,$centro_id,$bodega,$codigo_pro,$nom_pro,$grupo,$clase,$subclase,$fecha_inicio,$fecha_final,$tipo_movimiento,$tipo_doc_general_id,$centro_utilidad_bus,$bodega_bus,$molecula_bus,$pagina);
        
 
        }
        else
        {
            $salida .= "                  <table width='100%'>";
            $salida .= "                   <tr>\n";
            $salida .= "                    <td align='center'>\n";
            $salida .="                        <label class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
            $salida .= "                    </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                  </table>";
        }
    
        $objResponse->assign("ListadoGeneral","innerHTML",$salida);
        return $objResponse;
  
  
    }
   
   function BuscadorTBodega($empresa_id,$centro_id,$bodega,$codigo_pro,$nom_pro,$pagina)
   {
      $path = SessionGetVar("rutaImagenes");
      $objResponse = new xajaxResponse();  
      $limit=20;
      $offset=$pagina1;
      $salida .= " <form name=\"buscador_vent\" action=\"javascript:BuscadorTBodegam('".SessionGetVar("EMPRESA")."','".$centro_id."',document.getElementById('codigo_pr').value,document.getElementById('nom_producto').value,'".$pagina."');\" method=\"post\"> \n";
      $salida .= "  <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "   <tr class=\"modulo_table_list_title\">\n";
      $salida .= "    <td width=\"8%\" align=\"center\">\n";
      $salida .= "      CODIGO";
      $salida .= "    </td>\n";
      $salida .= "    <td width=\"8%\" align=\"center\" class=\"modulo_list_claro\">\n";
      $salida .= "     <input type=\"text\" class=\"input-text\" name=\"codigo_pr\" id=\"codigo_pr\" size=\"30\" onkeypress=\"return acceptNum(event)\" value=\"\">\n";
      $salida .= "    </td>\n";
      $salida .= "    </tr>\n";
      $salida .= "   <tr class=\"modulo_table_list_title\">\n";
      $salida .= "    <td width=\"20%\" align=\"center\">\n";
      $salida .= "       NOMBRE";
      $salida .= "    </td>\n";
      $salida .= "    <td width=\"8%\" align=\"center\" class=\"modulo_list_claro\">\n";
      $salida .= "     <input type=\"text\" class=\"input-text\" name=\"nom_producto\" id=\"nom_producto\" size=\"30\" onkeypress=\"return acceptNum(event)\" value=\"\">\n";
      $salida .= "    </td>\n";
      $salida .= "    </tr>\n";
      $salida .= "    <tr>\n";
      $salida .= "    <td align=\"center\" class=\"modulo_list_claro\" colspan='2'>\n";
      $salida .= "       <input type=\"submit\" class=\"input-submit\" value=\"BUSCAR PRODUCTOS\" >\n";
      $salida .= "    </td>\n";
      $salida .= "    </tr>\n";
      
      $salida .= "  </table>";
       $salida .= "  </form>";
      $objResponse->assign("Contenido","innerHTML",$salida);
        
      $objResponse->call("MostrarSpan");
      return $objResponse;
    }
   
   
   function BuscadorTBodegam($empresa_id,$centro_id,$codigo_pro,$nom_pro,$pagina)
   {
      $objResponse = new xajaxResponse();
      $mdl = AutoCarga::factory('MovBodSQL','classes','app','Inv_Movimientos_Admin');
      $pghtml = AutoCarga::factory("ClaseHTML");  
      $offset=$pagina;
      
      $VECTOR=$mdl->BuscarPBodegas($empresa_id,$centro_id,$codigo_pro,$nom_pro,$pagina);

      if(!empty($VECTOR))
      {
        $salida .= "  <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "   <tr class=\"modulo_table_list_title\">\n";
        $salida .= "    <td width=\"8%\" align=\"center\">\n";
        $salida .= "      CODIGO";
        $salida .= "    </td>\n";
        $salida .= "    <td width=\"20%\" align=\"center\">\n";
        $salida .= "       NOMBRE";
        $salida .= "    </td>\n";
        $salida .= "    <td width=\"15%\" align=\"center\">\n";
        $salida .= "       UNIDAD";
        $salida .= "    </td>\n";
        
        $salida .= "    <td width=\"10\" align=\"center\">\n";
        $salida .= "     <a title='EXISTENCIA MINIMA'>\n";
        $salida .= "       EXISTENCIA MIN";
        $salida .= "     </a>\n";
        $salida .= "    </td>\n";
        $salida .= "    <td width=\"10%\" align=\"center\">\n";
        $salida .= "      <a title='EXISTENCIA MAXIMA'>\n";
        $salida .= "       EXISTENCIA MAX";
        $salida .= "      </a>\n";
        $salida .= "    </td>\n";
        $salida .= "    <td width=\"10%\" align=\"center\">\n";
        $salida .= "      <a title='EXISTENCIA'>\n";
        $salida .= "       EXISTENCIA";
        $salida .= "      </a>\n";
        $salida .= "    </td>\n";
        $salida .= "    <td width=\"20%\" align=\"center\">\n";
        $salida .= "     <a title='BODEGA '>\n";
        $salida .= "       BODEGA";
        $salida .= "    </td>\n";
        $salida .= " </tr>\n";         
        for($i=0;$i<count($VECTOR);$i++)
        {
          $salida .= " <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
          $direccion="app_modules/Inv_Movimientos_Admin/Imprimir/imprimir_producto.php";
          //$x=RetornarImpresionDoc1($direccion,$alt,$empresa_id,$centro_id,$bodega,$VECTOR[$i]['codigo_producto']);
          $x=RetornarImpresionDoc1($direccion,$alt,$empresa_id,$centro_id,$_REQUEST['Bodeguix']['bodega'],$VECTOR[$i]['codigo_producto']);
          $salida .= "                       ".$x."";
          $salida .= "                       <td align=\"center\">\n";
          $salida .= "                       ".$VECTOR[$i]['nombrepro'];
          $salida .= "                       </td>\n";
          $salida .= "                       <td align=\"right\">\n";
          $salida .= "                       ".$VECTOR[$i]['unidades'];
          $salida .= "                       </td>\n";
          $salida .= "                       <td align=\"right\">\n";
          $salida .= "                       ".$VECTOR[$i]['existencia_minima'];
          $salida .= "                       </td>\n";
          $salida .= "                       <td align=\"right\">\n";
          $salida .= "                       ".$VECTOR[$i]['existencia_maxima'];
          $salida .= "                       </td>\n";
          $salida .= "                       <td align=\"right\">\n";
          $salida .= "                       ".$VECTOR[$i]['existencia'];
          $salida .= "                       </td>\n";
          $salida .= "                       <td align=\"center\">\n";
          $salida .= "                       ".$VECTOR[$i]['bodega_desc'];
          $salida .= "                       </td>\n";
          $salida .= " </tr>\n";
        }   
       $salida .= "</table>";
       //$action['paginador']= "paginador('".$empresa_id."','".$centro_id."','".$bodega."','".$codigo_pro."','".$nom_pro."'";
       $action['paginador']= "paginador('".$empresa_id."','".$centro_id."','".$codigo_pro."','".$nom_pro."'";
       
       $salida .= $pghtml->ObtenerPaginadoXajax($mdl->conteo,$mdl->pagina,$action['paginador']); 
      }
      else
      {
        $salida .= "                  <table width='100%'>";
        $salida .= "                   <tr>\n";
        $salida .= "                    <td align='center'>\n";
        $salida .="                        <label class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
        $salida .= "                    </td>\n";
        $salida .= "                   </tr>\n";
        $salida .= "                  </table>";
      }
     $objResponse->assign("Contenido","innerHTML",$salida);
     //$objResponse->call("MostrarSpan");
     return $objResponse;
  }
   
   
   
    /**
    * Metodo para rotornar el select con las subclases q ue pertenecen a una clase
    * @param string  $Grupo_id (id de la empresa).
    * @return string $salida retorna la forma para la edicion del precio.
    * @access public
    */
    function GetSubbClasex1($Grupo_id,$clase,$subclase)
    {
        $consulta=new MovBodegasAdminSQL();
        $objResponse = new xajaxResponse();
        $SubClasex=$consulta->SacarSubClases($Grupo_id,$clase);
    //var_dump($clasex);
        if(!empty($SubClasex))
        {
            $salida .= "                         <select name=\"subclasexy\" id=\"subclasexy\" class=\"select\" onchange=\"\">";
            $salida .= "                           <option value=\"0\">SELECCIONAR</option> \n";
            for($i=0;$i<count($SubClasex);$i++)
            {
                if($subclase==$SubClasex[$i]['subclase_id'])
                {
                    $salida .="                           <option value=\"".$SubClasex[$i]['subclase_id']."\" selected>".strtoupper($SubClasex[$i]['descripcion'])."</option> \n";
                }
                else
                {
                    $salida .="                           <option value=\"".$SubClasex[$i]['subclase_id']."\">".strtoupper($SubClasex[$i]['descripcion'])."</option> \n";
                }
        
            }
            $salida .="                         </select>\n";
            $objResponse->assign("subclase","innerHTML",$salida);
        }
    
        return $objResponse;
  
  
    }  
    
    /**
    * Metodo para rotornar el select con las clases q ue pertenecen a un grupo
    * @param string  $Grupo_id (id de la empresa).
    * @return string $salida retorna la forma para la edicion del precio.
    * @access public
    */
    function GetSubbClasex($Grupo_id,$clase)
    {
        $consulta=new MovBodegasAdminSQL();
        $objResponse = new xajaxResponse();
        $SubClasex=$consulta->SacarSubClases($Grupo_id,$clase);
        //var_dump($clasex);
        if(!empty($SubClasex))
        {
            $salida .= "                         <select name=\"subclasexy\" id=\"subclasexy\" class=\"select\" onchange=\"\">";
            $salida .= "                           <option value=\"0\" selected>SELECCIONAR</option> \n";
            for($i=0;$i<count($SubClasex);$i++)
            {
                $salida .="                           <option value=\"".$SubClasex[$i]['subclase_id']."\">".strtoupper($SubClasex[$i]['descripcion'])."</option> \n";
            }
            $salida .="                         </select>\n";
            $objResponse->assign("subclase","innerHTML",$salida);
        }
    
        return $objResponse;

  
    }
   
  
    /**
    * Metodo para rotornar el select con las clases q ue pertenecen a un grupo
    * @param string  $Grupo_id (id de la empresa).
    * @return string $salida retorna la forma para la edicion del precio.
    * @access public
    */
    function GetClasex1($Grupo_id,$clase_sel)
    {
        $consulta=new MovBodegasAdminSQL();
        $objResponse = new xajaxResponse();
        $clasex=$consulta->SacarClases($Grupo_id);
        //var_dump($clasex);
        if(!empty($clasex))
        {
            $salida .= "                         <select id=\"clasexx\" name=\"clasexx\" class=\"select\" onchange=\"GetSubClasex(document.getElementById('grupos_pro').value,this.value);\">";
            $salida .="                           <option value=\"0\">SELECCIONAR</option> \n";
            for($i=0;$i<count($clasex);$i++)
            {
                if($clase_sel==$clasex[$i]['clase_id'])
                {
                    $salida .="                           <option value=\"".$clasex[$i]['clase_id']."\" selected>".strtoupper($clasex[$i]['descripcion'])."</option> \n";
                }
                else
                {
                    $salida .="                           <option value=\"".$clasex[$i]['clase_id']."\">".strtoupper($clasex[$i]['descripcion'])."</option> \n";
                }
        
            }
            $salida .="                         </select>\n";
            $objResponse->assign("clasexy","innerHTML",$salida);
        }
    
    return $objResponse;
  
  
  }
   
    /**
    * Metodo para rotornar el select con las clases q ue pertenecen a un grupo
    * @param string  $Grupo_id (id de la empresa).
    * @return string $salida retorna la forma para la edicion del precio.
    * @access public
    */
    function GetClasex($Grupo_id)
    {
        $consulta=new MovBodegasAdminSQL();
        $objResponse = new xajaxResponse();
        $clasex=$consulta->SacarClases($Grupo_id);
        //var_dump($clasex);
        if(!empty($clasex))
        {
            $salida .= "                         <select id=\"clasexx\" name=\"clasexx\" class=\"select\" onchange=\"GetSubClasex(document.getElementById('grupos_pro').value,this.value);\">";
            $salida .="                           <option value=\"0\" selected>SELECCIONAR</option> \n";
            for($i=0;$i<count($clasex);$i++)
            {
                $salida .="                           <option value=\"".$clasex[$i]['clase_id']."\">".strtoupper($clasex[$i]['descripcion'])."</option> \n";         
            }
            $salida .="                         </select>\n";
            $objResponse->assign("clasexy","innerHTML",$salida);
        }
        
        return $objResponse;
    
    
    }
  
  
    /**
    * Metodo para retornar el select con los prefijos q ue pertenecen a una bodega especifica
    * @param string  $Grupo_id (id de la empresa).
    * @return string $salida retorna la forma para la edicion del precio. 
    * @access public
    */
  
    function Poner_pref($prefijo,$centro,$bodega)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $consulta=new MovBodegasAdminSQL();  
        $vector=$consulta->Get_Prefijos(SessionGetVar("EMPRESA"),$centro,$bodega);
        //var_dump($vector);
        if(!empty($vector))
        {
            $salida .="<select name=\"prefijo_s\" id=\"prefijo_s\" class=\"select\" onchange=\"Poner_des(this.value,'".$centro."','".$bodega."');\">";
            $salida .="  <option value=\"0\">--</option> \n";
            for($i=0;$i<count($vector);$i++)
            {
                if($vector[$i]['prefijo']==$prefijo)
                {
                    $salida .="<option value=\"".$vector[$i]['prefijo']."\" selected>".$vector[$i]['prefijo']."</option> \n";
                }
                else
                {
                    $salida .="<option value=\"".$vector[$i]['prefijo']."\">".$vector[$i]['prefijo']."</option> \n";
                }
            }
        
            $salida .=" </select>";
        }
      
      
        $salida=$objResponse->setTildes($salida);
        $objResponse->assign("prefijo_solo","innerHTML",$salida);
        return $objResponse;
  
  }
  
    /**
    * Metodo para retornar el select con los prefijos q ue pertenecen a una bodega especifica
    * @param string  $Grupo_id (id de la empresa).
    * @return string $salida retorna la forma para la edicion del precio.
    * @access public
    */
  
    function Poner_descr($prefijo,$centro,$bodega)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $consulta=new MovBodegasAdminSQL();
        $vector=$consulta->Get_Prefijos(SessionGetVar("EMPRESA"),$centro,$bodega);
        //var_dump($vector);
        if(!empty($vector))
        {
            $salida .="<select name=\"prefijo_s\" id=\"prefijo_s\" class=\"select\" onchange=\"Poner_pre(this.value,'".$centro."','".$bodega."');\">";
            $salida .="  <option value=\"0\">SELECCIONAR</option> \n";         
            for($i=0;$i<count($vector);$i++)
            {
                if($vector[$i]['prefijo']==$prefijo)
                {
                   $salida .="<option value=\"".$vector[$i]['prefijo']."\" selected>".$vector[$i]['descripcion_documento']."</option> \n";
                }
                else
                {
                    $salida .="<option value=\"".$vector[$i]['prefijo']."\">".$vector[$i]['descripcion_documento']."</option> \n";
                }
            }

            $salida .=" </select>";
        }
      
      
        $salida=$objResponse->setTildes($salida);
        $objResponse->assign("prefijo_nombre","innerHTML",$salida);
        return $objResponse;
  
    }
    /**
    * Metodo que sirve para la busqueda de documentos
    * @param string $empresa
    * @param string $centro
    * @param string $bodega
    * @param string $fecha_ini
    * @param string $fecha_fin
    * @param string $nom_bodega
    * @return  string $salida con el listado de documentos
    **/
    function BuscarDocumentx($empresa,$centro,$bodega,$fecha_ini,$fecha_fin,$nom_bodega)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $consulta=new MovBodegasAdminSQL();
        $consulta1= new BodegasDocumentos();
        $vector=$consulta1->GetDocumentosByBodega($empresa,$centro,$bodega,$fecha_ini,$fecha_fin);
            
        if(!empty($vector))
        {
            $salida .= "                 <table width=\"90%\" align=\"center\">\n";
            $salida .= "                   <tr>\n";
            $salida .= "                      <td  class=\"normal_10AN\" align=\"left\">\n";
            $NOMBRE=$consulta->bodegasname($bodega);
            $salida .= "                        TIPOS DE DOCUMENTOS DE LA BODEGA: ".$bodega."-".$NOMBRE[0]['descripcion'];
            $salida .= "                      </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                 </table>\n";
            $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                   <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td width='5%' align=\"center\">\n";
            $salida .= "                       <a title='CENTRO DE UTILIDAD'>";
            $salida .= "                         CU";
            $salida .= "                      </a>\n";
            $salida .= "                      </td>\n";
            $salida .= "                      <td width='25%' align=\"center\">\n";
            $salida .= "                         BODEGA";
            $salida .= "                      </td>\n";
            $salida .= "                      <td width='3%' align=\"center\">\n";
            $salida .= "                         TIPO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td width='7%' align=\"center\">\n";
            $salida .= "                         PREFIJO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td width='45%' align=\"center\">\n";
            $salida .= "                         DESCRIPCION";
            $salida .= "                      </td>\n";
            $salida .= "                      <td width='10%' align=\"center\">\n";
            $salida .= "                         CANTIDAD";
            $salida .= "                      </td>\n";
            $salida .= "                      <td width='5%' align=\"center\">\n";
            $salida .= "                         ACCIONES";
            $salida .= "                      </td>\n";
            $salida .= "                   </tr>\n";
            for($i=0;$i<count($vector);$i++)
            {
                $BODEGA=ModuloGetURL('app','Inv_Movimientos_Admin','user','ListarDocumentos' ,array('empresa_idx'=>$empresa,'documento_id'=>$vector[$i]['documento_id'],'fecha1'=>$fecha_ini,'fecha2'=>$fecha_fin,'nombre_doc'=>$vector[$i]['descripcion'],'bodega'=>$bodega,'nom_bodega'=>$nom_bodega));
                $salida .= "                   <tr class=\"modulo_list_claro\">\n";
                $salida .= "                      <td class=\"normal_10AN\" align=\"center\">\n";
                $salida .= "                        ".$vector[$i]['centro_utilidad'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td class=\"normal_10AN\" align=\"left\">\n";
                $salida .= "                       <a title='".$vector[$i]['bodega']."' class=\"label_error\">";
                $salida .= "                        ".$vector[$i]['nom_bodega'];
                $salida .= "                      </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td  class=\"normal_10AN\" align=\"center\">\n";
                $salida .= "                       <a title='".$vector[$i]['tipo_clase_documento']."' class=\"label_error\">";
                $salida .= "                         ".$vector[$i]['tipo_movimiento'];
                $salida .= "                       </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td class=\"normal_10AN\" align=\"center\">\n";
                $salida .= "                        ".$vector[$i]['prefijo'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td class=\"normal_10AN\" align=\"left\">\n";
                $salida .= "                       <a title='".$vector[$i]['documento_id']."'>";
                $salida .= "                        ".$vector[$i]['descripcion'];
                $salida .= "                       </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td class=\"normal_10AN\" align=\"right\">\n";
                $salida .= "                        ".$vector[$i]['numero_documentos'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td class=\"normal_10AN\" align=\"center\">\n";
                $salida .= "                          <a  title=\"LISTAR DOCUMENTOS\" class=\"label_error\" href=\"".$BODEGA."\">\n";
                $salida .= "                          <sub><img src=\"".$path."/images/mvto_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>";
            }
            
            $salida .= "                 </table>";
            $salida .= "             </form>";
        }
        else
        {
            $salida .= "                 <table width=\"90%\" align=\"center\">\n";
            $salida .= "                   <tr>\n";
            $salida .= "                      <td align=\"center\">\n";
            $salida .= "                        <label class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
            $salida .= "                      </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                 </table>\n"; 
        } 
      
        $salida=$objResponse->setTildes($salida);
        $objResponse->assign("docs_bodega","innerHTML",$salida);
        //$objResponse->assign("docs_bodega","innerHTML",$vector);
    
        return $objResponse;
    }
  
    /**
    * Metodo que sirve para colocar un select con el nombre escogido de la bodega
    * @param string $empresa
    * @param string $centro
    * @param string $bodega
    * @param string $fecha_ini
    * @param string $fecha_fin
    * @param string $nom_bodega
    * @return  string $salida con el listado de bodegas
    **/
  
    function GetUpBodega($centro)
    {
        $objResponse = new xajaxResponse();
        $consulta=new MovBodegasAdminSQL();
        $bodegas=$consulta->GetBodegas(SessionGetVar("EMPRESA"),$centro);
        if(!empty($bodegas))
        {
            $salida .="                         <select name=\"bodegas\" id=\"bodegas\" class=\"select\" onchange=\"\">";
            for($i=0;$i<count($bodegas);$i++)
            {
                if($bodegas[$i]['bodega']==$id)
                {
                    $salida .="                           <option value=\"".$bodegas[$i]['bodega']."\" selected>".$bodegas[$i]['descripcion']."</option> \n";
                }
                else
                {
                    $salida .="                           <option value=\"".$bodegas[$i]['bodega']."\">".$bodegas[$i]['descripcion']."</option> \n";
                }
            }
            $salida .="                         </select>\n";  
        
        }
    
        $salida=$objResponse->setTildes($salida);
        $objResponse->assign("Select_bodega","innerHTML",$salida);
        return $objResponse;
    }

    /**
    * Metodo que reotna todos los datos que le pertenecen a un documento especifico
    * @param string  $empresa_id.
    * @param string  $prefijo.
    * @param string  $numero.
    * @return string $salida.
    * @access public
    */
    function ObtenerDatosDocumento1($empresa_id,$prefijo,$numero)
    {
        $consulta=new MovBodegasAdminSQL();
        $objResponse = new xajaxResponse();
        $resultado=$consulta->SacarDocumento($empresa_id,$prefijo,$numero);
        //var_dump($resultado);
    
        $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                   <tr>\n";
        $salida .= "                      <td  class=\"normal_10AN\" align=\"left\">\n";
        $salida .= "                        DETALLES DOCUMENTOS: PREFIJO: ".$prefijo." NUMERO: ".$numero;
        $salida .= "                      </td>\n";
        $salida .= "                   </tr>\n";
        $salida .= "                    <tr>\n";
        $salida .= "                      <td>\n";
        $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td width=\"35%\" align=\"center\">\n";
        $salida .= "                       <a title='RAZON SOCIAL DE LA EMPRESA'>";
        $salida .= "                        EMPRESA";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $nombre=$consulta->ColocarEmpresa($resultado['empresa_id']);
        $salida .= "                       <td width=\"65%\" class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$nombre[0]['razon_social'];
        $salida .= "                         </td>\n";
        $salida .= "                       </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td width=\"8%\" align=\"center\">\n";
        $salida .= "                       <a title='CENTRO DE UTULIDAD'>";
        $salida .= "                        CENTRO DE UTULIDAD";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $centro=$consulta->ColocarCentro($resultado['centro_utilidad']);
        $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$centro[0]['descripcion'];
        $salida .= "                         </td>\n";
        $salida .= "                       </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td width=\"5%\" align=\"center\">\n";
        $salida .= "                       <a title='BODEGA'>";
        $salida .= "                        BODEGA";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $bodega=$consulta->bodegasname($resultado['bodega']);
        $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$bodega[0]['descripcion'];
        $salida .= "                         </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                   </table>\n";
        $salida .= "                   <br>\n";   
    
    
    
        $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td width=\"35%\" align=\"center\">\n";
        $salida .= "                       <a>";
        $salida .= "                        TIPO MOVIMIENTO";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$resultado['tipo_movimiento'];
        $salida .= "                       </td>\n";
        $salida .= "                       <td width=\"25%\"align=\"center\">\n";
        $salida .= "                       <a TITLE='TIPO DOCUMENTO BODEGA ID'>";
        $salida .= "                        DOC BOD ID";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$resultado['tipo_doc_bodega_id'];
        $salida .= "                        </td>\n";
        $salida .= "                       </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                        <td width=\"35%\" align=\"center\">\n";
        $salida .= "                          <a>";
        $salida .= "                            DESCRIPCION";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                       <td COLSPAN='3' class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$resultado['descripcion'];
        $salida .= "                       </td>\n";
        $salida .= "                      </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td width=\"8%\" align=\"center\">\n";
        $salida .= "                       <a title='NUMERO'>";
        $salida .= "                        NUMERO";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$resultado['prefijo']."-".$resultado['numero'];
        $salida .= "                         </td>\n";
        $salida .= "                       <td width=\"8%\" align=\"center\">\n";
        $salida .= "                       <a title='FECHA DE REGISTRO'>";
        $salida .= "                        FECHA";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".substr($resultado['fecha_registro'],0,10);
        $salida .= "                         </td>\n";
        $salida .= "                       </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        OBSERVACIONES";
        $salida .= "                       </td>\n";
        $salida .= "                        <td COLSPAN='3'class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$resultado['observacion'];
        $salida .= "                         </td>\n";
        $salida .= "                      </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                       <a title='USUARIO QUE ELABORO EL RECIBO'>";
        $salida .= "                        USUARIO";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $USUARIO=$consulta->NombreUsu($resultado['usuario_id']);
        $salida .= "                        <td COLSPAN='3'class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$resultado['usuario_id']."-".$USUARIO[0]['nombre'];
        $salida .= "                         </td>\n";
        $salida .= "                      </tr>\n";
        $salida .= "                   </table>\n";
    
        $salida .= "                   <br>\n";
        if(!empty($resultado['DATOS_ADICIONALES']))
        {
            $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                        <td COLSPAN='7' align=\"center\">\n";
            $salida .= "                         <a>";
            $salida .= "                           DATOS ADICIONALES";
            $salida .= "                         </a>";
            $salida .= "                        </td>\n";
            $salida .= "                    </tr>\n";
            foreach($resultado['DATOS_ADICIONALES'] as $doc_val=>$valor)
            {
                //var_dump($resultado['DETALLE']);
                $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $salida .= "                      <td WIDTH='35%' class=\"modulo_table_list_title\" align=\"left\">\n";
                $salida .= "                       ".$doc_val;
                $salida .= "                      </td>\n";
                $salida .= "                      <td WIDTH='65%' align=\"left\">\n";
                $salida .= "                       <a>";
                $salida .= "                       ".$valor;
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
            }
            $salida .= "                   <br>\n";
        }
        $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                        <td COLSPAN='7' align=\"center\">\n";
        $salida .= "                         <a>";
        $salida .= "                           PRODUCTOS QUE CONTIENE ESTE DOCUMENTO";
        $salida .= "                         </a>";
        $salida .= "                        </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                        <td WIDTH='8%' align=\"center\">\n";
        $salida .= "                          <a TITLE='MOVIMIENTO ID'>";
        $salida .= "                            MOV ID";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td WIDTH='15%' align=\"center\">\n";
        $salida .= "                          <a TITLE='CODIGO DEL PRODUCTO'>";
        $salida .= "                            CODIGO";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td WIDTH='35%' align=\"center\">\n";
        $salida .= "                          <a TITLE='DESCRIPCION DEL PRODUCTO'>";
        $salida .= "                            DESCRIPCION";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td WIDTH='15%' align=\"center\">\n";
        $salida .= "                          <a TITLE='UNIDAD DEL PRODUCTO'>";
        $salida .= "                            UNIDAD";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td WIDTH='10%' align=\"center\">\n";
        $salida .= "                          <a TITLE='UNIDAD DEL PRODUCTO'>";
        $salida .= "                            CANTIDAD";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td WIDTH='7%' align=\"center\">\n";
        $salida .= "                          <a TITLE='PORCENTAJE DEL GRAVAMEN'>";
        $salida .= "                           % GRAV";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td WIDTH='10%' align=\"center\">\n";
        $salida .= "                          <a TITLE='TOTAL COSTO'>";
        $salida .= "                            TOTAL";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                       </tr>\n";
        $valorTotal=0;
        foreach($resultado['DETALLE'] as $doc_val=>$valor)
        {       
            //var_dump($resultado['DETALLE']);
            $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
            $salida .= "                      <td class=\"normal_10AN\" align=\"left\">\n";
            $salida .= "                       ".$valor['movimiento_id'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                       <a>";
            $salida .= "                       ".$valor['codigo_producto'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                       ".$valor['descripcion'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                       ".$valor['descripcion_unidad'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            list($entero,$decimal) = explode(".",$valor['cantidad']);
            if($decimal>0)
            {
                $salida .= "                       ".$valor['cantidad'];
            }
            else
            {
                $salida .= "                       ".$entero;
            }
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                       ".$valor['porcentaje_gravamen'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"RIGHT\">\n";
            $salida .= "                       ".FormatoValor($valor['total_costo']);
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $valorTotal=$valorTotal+$valor['total_costo'];
        }
        $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
        $salida .= "                      <td colspan='5' align=\"right\">\n";
        $salida .= "                       ";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"right\">\n";
        $salida .= "                       <label class='label_error'>TOTAL</label>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"right\">\n";
        $salida .= "                       ".FormatoValor($valorTotal);
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                   </table>\n";
        $salida .= "                   </td>\n";
        $salida .= "                 </tr>\n";
        $salida .= "                   </table>\n";
        
        $salida = $objResponse->setTildes($salida);
        $objResponse->assign("docs_bodega","innerHTML",$salida);
        return $objResponse;
    
    }  
    
    /**
    * Metodo que sirve para la busqueda de documentos
    * @param string $empresa_id
    * @param string $prefijo
    * @param string $numero
    * @return  string $salida con el listado de documentos
    **/
    function ObtenerDatosDocumento($empresa_id,$prefijo,$numero)
    {
        $consulta=new MovBodegasAdminSQL();
        $objResponse = new xajaxResponse();
        $resultado=$consulta->SacarDocumento($empresa_id,$prefijo,$numero);
        //var_dump($resultado);
        $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td width=\"35%\" align=\"center\">\n";
        $salida .= "                       <a title='RAZON SOCIAL DE LA EMPRESA'>";
        $salida .= "                        EMPRESA";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $nombre=$consulta->ColocarEmpresa($resultado['empresa_id']);
        $salida .= "                       <td width=\"65%\" class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$nombre[0]['razon_social'];
        $salida .= "                         </td>\n";
        $salida .= "                       </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td width=\"8%\" align=\"center\">\n";
        $salida .= "                       <a title='CENTRO DE UTULIDAD'>";
        $salida .= "                        CENTRO DE UTULIDAD";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $centro=$consulta->ColocarCentro($resultado['centro_utilidad']);
        $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$centro[0]['descripcion'];
        $salida .= "                         </td>\n";
        $salida .= "                       </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td width=\"5%\" align=\"center\">\n";
        $salida .= "                       <a title='BODEGA'>";
        $salida .= "                        BODEGA";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $bodega=$consulta->bodegasname($resultado['bodega']);
        $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$bodega[0]['descripcion'];
        $salida .= "                         </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                   </table>\n";
        $salida .= "                   <br>\n";
        $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td width=\"35%\" align=\"center\">\n";
        $salida .= "                       <a>";
        $salida .= "                        TIPO MOVIMIENTO";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $salida .= "                       <td class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$resultado['tipo_movimiento'];
        $salida .= "                       </td>\n";
        $salida .= "                       <td width=\"25%\"align=\"center\">\n";
        $salida .= "                       <a TITLE='TIPO DOCUMENTO BODEGA ID'>";
        $salida .= "                        DOC BOD ID";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$resultado['tipo_doc_bodega_id'];
        $salida .= "                        </td>\n";
        $salida .= "                       </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                        <td width=\"35%\" align=\"center\">\n";
        $salida .= "                          <a>";
        $salida .= "                            DESCRIPCION";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                       <td COLSPAN='3' class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$resultado['descripcion'];
        $salida .= "                       </td>\n";
        $salida .= "                      </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td width=\"8%\" align=\"center\">\n";
        $salida .= "                       <a title='NUMERO'>";
        $salida .= "                        NUMERO";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$resultado['prefijo']."-".$resultado['numero'];
        $salida .= "                         </td>\n";
        $salida .= "                       <td width=\"8%\" align=\"center\">\n";
        $salida .= "                       <a title='FECHA DE REGISTRO'>";
        $salida .= "                        FECHA";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $salida .= "                        <td class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".substr($resultado['fecha_registro'],0,10);
        $salida .= "                         </td>\n";
        $salida .= "                       </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                        OBSERVACIONES";
        $salida .= "                       </td>\n";
        $salida .= "                        <td COLSPAN='3'class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$resultado['observacion'];
        $salida .= "                         </td>\n";
        $salida .= "                      </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                       <td align=\"center\">\n";
        $salida .= "                       <a title='USUARIO QUE ELABORO EL RECIBO'>";
        $salida .= "                        USUARIO";
        $salida .= "                       </a>";
        $salida .= "                       </td>\n";
        $USUARIO=$consulta->NombreUsu($resultado['usuario_id']);
        $salida .= "                        <td COLSPAN='3'class=\"modulo_list_claro\" align=\"left\">\n";
        $salida .= "                          ".$resultado['usuario_id']."-".$USUARIO[0]['nombre'];
        $salida .= "                         </td>\n";
        $salida .= "                      </tr>\n";
        $salida .= "                   </table>\n";
        $salida .= "                   <br>\n";
        if(!empty($resultado['DATOS_ADICIONALES']))
        {
            $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                        <td COLSPAN='7' align=\"center\">\n";
            $salida .= "                         <a>";
            $salida .= "                           DATOS ADICIONALES";
            $salida .= "                         </a>";
            $salida .= "                        </td>\n";
            $salida .= "                    </tr>\n";
            foreach($resultado['DATOS_ADICIONALES'] as $doc_val=>$valor)
            {
                //var_dump($resultado['DETALLE']);
                $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $salida .= "                      <td WIDTH='35%' class=\"modulo_table_list_title\" align=\"left\">\n";
                $salida .= "                       ".$doc_val;
                $salida .= "                      </td>\n";
                $salida .= "                      <td WIDTH='65%' align=\"left\">\n";
                $salida .= "                       <a>";
                $salida .= "                       ".$valor;
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
            }
            $salida .= "                   <br>\n";
        }
        $salida .= "                 <table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                        <td COLSPAN='7' align=\"center\">\n";
        $salida .= "                         <a>";
        $salida .= "                           PRODUCTOS QUE CONTIENE ESTE DOCUMENTO";
        $salida .= "                         </a>";
        $salida .= "                        </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                        <td WIDTH='8%' align=\"center\">\n";
        $salida .= "                          <a TITLE='MOVIMIENTO ID'>";
        $salida .= "                            MOV ID";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td WIDTH='15%' align=\"center\">\n";
        $salida .= "                          <a TITLE='CODIGO DEL PRODUCTO'>";
        $salida .= "                            CODIGO";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td WIDTH='35%' align=\"center\">\n";
        $salida .= "                          <a TITLE='DESCRIPCION DEL PRODUCTO'>";
        $salida .= "                            DESCRIPCION";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td WIDTH='15%' align=\"center\">\n";
        $salida .= "                          <a TITLE='UNIDAD DEL PRODUCTO'>";
        $salida .= "                            UNIDAD";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td WIDTH='10%' align=\"center\">\n";
        $salida .= "                          <a TITLE='UNIDAD DEL PRODUCTO'>";
        $salida .= "                            CANTIDAD";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td WIDTH='7%' align=\"center\">\n";
        $salida .= "                          <a TITLE='PORCENTAJE DEL GRAVAMEN'>";
        $salida .= "                           % GRAV";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                        <td WIDTH='10%' align=\"center\">\n";
        $salida .= "                          <a TITLE='TOTAL COSTO'>";
        $salida .= "                            TOTAL";
        $salida .= "                          </a>";
        $salida .= "                        </td>\n";
        $salida .= "                       </tr>\n";
        $valorTotal=0;
        foreach($resultado['DETALLE'] as $doc_val=>$valor)
        {
            //var_dump($resultado['DETALLE']);
            $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
            $salida .= "                      <td class=\"normal_10AN\" align=\"left\">\n";
            $salida .= "                       ".$valor['movimiento_id'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                       <a>";
            $salida .= "                       ".$valor['codigo_producto'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                       ".$valor['descripcion'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                       ".$valor['descripcion_unidad'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            list($entero,$decimal) = explode(".",$valor['cantidad']);
            if($decimal>0)
            {
                $salida .= "                       ".$valor['cantidad'];
            }
            else
            {
                $salida .= "                       ".$entero;
            }
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                       ".$valor['porcentaje_gravamen'];
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"RIGHT\">\n";
            $salida .= "                       ".FormatoValor($valor['total_costo']);
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $valorTotal=$valorTotal+$valor['total_costo'];  
        }       
        $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
        $salida .= "                      <td colspan='5' align=\"right\">\n";
        $salida .= "                       ";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"right\">\n";
        $salida .= "                       <label class='label_error'>TOTAL</label>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"right\">\n";
        $salida .= "                       ".FormatoValor($valorTotal);
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                   </table>\n";
      
          
        $salida = $objResponse->setTildes($salida);
        $objResponse->assign("ContenidoDet","innerHTML",$salida);
        return $objResponse;
      
    }
    
    
     /**
    * Metodo para retornar el select con los prefijos q ue pertenecen a una bodega especifica
    * @param string  $Grupo_id (id de la empresa).
    * @return string $salida retorna la forma para la edicion del precio. 
    * @access public
    */
  
    function ListadoDocGeneral($inv_tipo_movimiento)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $consulta=new MovBodegasAdminSQL();  
        $vector=$consulta->ListarDocGenerales($inv_tipo_movimiento);
        //var_dump($vector);
        
            $salida .="<select name=\"tipo_doc_general_id\" id=\"tipo_doc_general_id\" class=\"select\">";
            $salida .="  <option value=\"\">Todos</option> \n";
            foreach($vector as $key=>$td)
            {
             $salida .="  <option value=\"".$td['tipo_doc_general_id']."\">".$td['tipo_doc_general_id']."-".$td['descripcion']."</option> \n";
            }
        
            $salida .=" </select>";
         
      
        $salida=$objResponse->setTildes($salida);
        $objResponse->assign("doc_general","innerHTML",$salida);
        return $objResponse;
  }   
  
  /**
    * Metodo Para Cargar las Bodegas de una empresa y centro de Utilidad seleccionada
    * @param string  $Empresa_id
    * @param string  $CentroUtilidad
    * @return string $html retorna las opciones Bodega
    * @access public
    */
  
    function BodegasBusqueda($empresa_id,$centro_utilidad)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $sql=new MovBodegasAdminSQL();  
        $datos=$sql->Bodegas_EmpresaCentro($empresa_id,$centro_utilidad);
        
        if(empty($datos))
		$html = "<option value=\"\">-- BODEGA POR DEFECTO --</option>";
		foreach($datos as $key=>$valor)
		{
		$html .= "<option value=\"".$valor['bodega']."\">".$valor['descripcion']."</option>";
		}
        
        $objResponse->assign("bodega_bus","innerHTML",$html);
        return $objResponse;
  }
?>