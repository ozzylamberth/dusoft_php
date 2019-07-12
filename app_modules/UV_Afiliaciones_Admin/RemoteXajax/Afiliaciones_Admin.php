<?php
  /**
  * Archivo Ajax (Afiliaciones_Admin.php)
  * Archivo que contiene funciones las cuales permiten trabajar sobre el browser para la obtencion de datos
  * @version $Id: Afiliaciones_Admin.php,v 1.13 2008/06/13 19:38:44 jgomez Exp $
  * @package IPSOFT-SIIS
  * @author Jaime Gomez  
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  */


    function CrearConvUsu($datos)
     {
         $path = SessionGetVar("rutaImagenes");
         $objResponse = new xajaxResponse();
         $afi = AutoCarga::factory("CrearTerceroConvenio","", "app","UV_Afiliaciones_Admin");
         $resultado=$afi->Guardar_TercerosConvenios($datos);

         // var_dump($resultado);

          if($resultado===true)
          {
                $html = "<label clASS='LABEL_ERROR'>ENTIDAD CONVENIO CREADA SATISFACTORIAMENTE</label>";
                $html = $objResponse->setTildes($html);
                $objResponse->assign("btn_crear_bd","disabled",true);
          }
          else
          {
               $html = " ".$afi->error."-".$afi->mensajeDeError; 

          }

         $objResponse->assign("error_ter","innerHTML",$html);
         return $objResponse;
     }

    
    
    /**
    * Funcion pra buscar tercero por razon social
    * @param string $nombre
    * @param string $interfaz si busca en una interfaz externa
    * @param string $pagina
    * @param string $usu_cant contador de registros
    * @return string $html con la tabla de terceros encontrados
    **/         
    function BuscarTerceroByRazonSocial($nombre,$interfaz,$pagina,$usu_cant)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
    
        $afi = AutoCarga::factory("CrearTerceroConvenio","", "app","UV_Afiliaciones_Admin");


        if($interfaz==1)
        {
            $interfaz=true;
            $u=1;
        }
        else
        {
            $interfaz=false;
            $u=0;
        }
        if($usu_cant==0)
        {
            
            $usu_cant = $afi->BuscarTerceroPorNombre($interfaz, $nombre, $count=true, $limit=null, $offset=null);
        }

        $limit=10;
        $offset=($pagina-1)*$limit;
        $usuarios = $afi->BuscarTerceroPorNombre($interfaz, $nombre, $count=null, $limit, $offset);
       // var_dump($usuarios);
        if(!empty($usuarios))
        {
//             $html .= "      <table width=\"85%\" align=\"center\">\n";
//             $html .= "        <tr class=\"normal_10AN\">\n";
//             $html .= "          <td width=\"100%\" align=\"left\">\n";
//             $html .= "            SE ENCONTRARON (".$usu_cant.") REGISTRO(S)";
//             $html .= "          </td>\n";
//             $html .= "        </tr>\n";
//             $html .= "      </tABLE>\n";
            $html .= "      <table border=\"0\" width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "        <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td width='20%'  align=\"center\">\n";
            $html .= "            TERCERO ID";
            $html .= "          </td>\n";
            $html .= "          <td width='70%'  align=\"center\">\n";
            $html .= "            NOMBRE";
            $html .= "          </td>\n";
            $html .= "          <td width='10%'  align=\"center\">\n";
            $html .= "            SELECCIONAR";
            $html .= "          </td>\n";
//             $html .= "                <td width='20%'  align=\"center\">\n";
//             $html .= "                  ACCIONES";
//             $html .= "                </td>\n";
            $html .= "              </tr>\n";
            for($i=0;$i<count($usuarios);$i++)
            {
                

                    $sitio="switch_admin".$i;
                    $sitio_perfil="perfil_sitio".$i;
                    $sitio_accion="accion".$i;
                    $html .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                    $html .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                    $html .= "                       ".$usuarios[$i]['tipo_id_tercero']."-".$usuarios[$i]['tercero_id'];
                    $html .= "                       </td>\n";
                    $html .= "                      <td align=\"left\">\n";
                    $html .= "                       <a title='".$usuarios[$i]['nombre_tercero']."'>";
                    $html .= "                       ".$usuarios[$i]['nombre_tercero'];
                    $html .= "                       </a>\n";
                    $html .= "                      </td>\n";
                    $html .= "                      <td id='$sitio_accion' align=\"center\">\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
                    if(empty($usuarios[$i]['sw_estado']))
                    {
                        $EDITA_PERFIL = "javascript:SeleccionarParaCrear('".$usuarios[$i]['tipo_id_tercero']."','".$usuarios[$i]['tercero_id']."','".$u."','".$usuarios[$i]['nombre_tercero']."','".$usuarios[$i]['tipo_pais_id']."','".$usuarios[$i]['tipo_dpto_id']."','".$usuarios[$i]['tipo_mpio_id']."','".$usuarios[$i]['direccion']."','".$usuarios[$i]['telefono']."','".$usuarios[$i]['fax']."','".$usuarios[$i]['email']."','".$usuarios[$i]['celular']."');";//
                        $html .= "                         <a title='SELECCIONAR' href=\"".$EDITA_PERFIL."\">";
                        $html .= "                          <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                        $html .= "                         </a>\n";
                    }
                    else
                    {
                        $html .= "                         <a title='YA ESTA CREADO COMO TERCERO CONVENIO' href=\"#\">";
                        $html .= "                          <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                        $html .= "                         </a>\n";
                    }
                    $html .= "                      </td>\n";
                
            }
            $html .= "               </tr>\n";
            $html .= "            </table>\n";

            $html .= "".ObtenerPaginadorCUC($nombre,$interfaz,$pagina,$path,$usu_cant,$op);
            
        }
        else
        {
            $html .= "                 <table width=\"100%\" align=\"center\">\n";
            $html .= "                    <tr class=\"label_error\">\n";
            $html .= "                       <td width=\"100%\" align=\"center\">\n";
            $html .= "                       NO SE ENCONTRARON RESULTADOS CON ESE CRITERIO DE BUSQUEDA";
            $html .= "                      </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                 </table>\n";
            $objResponse->Call("DesactivarRadio");
            
        }
        $html = $objResponse->setTildes($html);
        $objResponse->assign("ResultadoBusqueda","innerHTML",$html);
        
        return $objResponse;
    }



    /**
    * Funcion que se encarga de mostrar una tabla paginadora para la lista de usuarios afiliados
    * @param string $nombre
    * @param string $interfaz
    * @param string $pagina
    * @param string $path
    * @param string $slc
    * @param string $op
    * @return string $Tabla con la tabla que indica el listado del paginador
    **/      
    function ObtenerPaginadorCUC($nombre,$interfaz,$pagina,$path,$slc,$op)
    {

      
  
      $TotalRegistros = $slc;
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $LimitRow = 10;
      }
      else
      {
        $LimitRow = 10;
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
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                                                                          //  BuscarTerceroPorNombrePaginador(nombre,interfaz,pagina,usu_cant)
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarTerceroPorNombrePaginador('".$nombre."','".$interfaz."','1','".$slc."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarTerceroPorNombrePaginador('".$nombre."','".$interfaz."','".($pagina-1)."','".$slc."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarTerceroPorNombrePaginador('".$nombre."','".$interfaz."','".$i."','".$slc."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarTerceroPorNombrePaginador('".$nombre."','".$interfaz."','".($pagina+1)."','".$slc."');\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarTerceroPorNombrePaginador('".$nombre."','".$interfaz."','".($NumeroPaginas)."','".$slc."');\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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

    /**
    * FUNCION PARA para cambiar el estado de activado a desactivado y viceversa
    * @param string $tipo_id_ter
    * @param string $tercero_id
    * @param string $sw_estado
    * @return string $html
    **/    
    function CambiarEstado($tipo_id_tercero,$tercero_id,$td)
    {


        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        //var_dump($filtros);
        $afi = AutoCarga::factory("TercerosConvenios","", "app","UV_Afiliaciones_Admin");
        $sw_estado = $afi->CambiarEstadoTerceroConvenio($tipo_id_tercero,$tercero_id);

       //echo $afi->error;
       //echo $afi->mensajeDeError;
                if($sw_estado==='0')
                {
                    $cambiar_usu_admin = "javascript:CambiarEstadoConv('".$tipo_id_tercero."','".$tercero_id."','".$td."');";//
                    $html .= "                      <a title='CAMBIAR A ESTADO ACTIVADO' href=\"".$cambiar_usu_admin."\">";
                    $html .= "                          <sub><img src=\"".$path."/images/checkN.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $html .= "                      </a>\n";
                }
                elseif($sw_estado==='1')
                {
                    $cambiar_usu_admin = "javascript:CambiarEstadoConv('".$tipo_id_tercero."','".$tercero_id."','".$td."');";//
                    $html .= "                      <a title='CAMBIAR A ESTADO DESACTIVADO' href=\"".$cambiar_usu_admin."\">";
                    $html .= "                        <sub><img src=\"".$path."/images/checkS.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $html .= "                      </a>\n";
                }


         $objResponse->assign($td,"innerHTML",$html);
        
        return $objResponse;






    }


    /**
    * Funcion que se encarga de mostrar una tabla paginadora para la lista de terceros convenio
    * @param string $nombre
    * @param string $interfaz
    * @param string $pagina
    * @param string $path
    * @param string $slc
    * @param string $op
    * @return string $Tabla con la tabla que indica el listado del paginador
    **/      
    function ObtenerPaginadorConv($pagina,$path,$usu_cant,$op)
    {

        
    
        $TotalRegistros = $usu_cant;
        $TablaPaginado = "";
            
        if($limite == null)
        {
            $LimitRow = 10;
        }
        else
        {
            $LimitRow = 10;
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
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P&#225;gina</td>\n";
            if($pagina > 1)
            {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                                                                            //  BuscarTerceroPorNombrePaginador(nombre,interfaz,pagina,usu_cant)
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:ObtenerTercerosConvenio_v1('1','".$usu_cant."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:ObtenerTercerosConvenio_v1('".($pagina-1)."','".$usu_cant."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
                $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:ObtenerTercerosConvenio_v1('".$i."','".$usu_cant."');\">".$i."</a></td>\n";
                }
                $columnas++;
            }
            }
            if($pagina <  $NumeroPaginas )
            {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:ObtenerTercerosConvenio_v1('".($pagina+1)."','".$usu_cant."');\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:ObtenerTercerosConvenio_v1('".($NumeroPaginas)."','".$usu_cant."');\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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


    /**
    * FUNCION PARA BUSCAR LAS ENTIDADES O TERCEROS QUE HACER PARTE DE LOS COMVENIOS
    * @param array $filtros vector de criterios de busqueda
    * @param string $pagina 
    * @param string $usu_cant contador de registros
    * @return string $html con la lista de entidades convenio
    **/    
    function BuscarTerceroConvenio($filtros,$pagina,$usu_cant)
    {
        
        
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        //var_dump($filtros);
        $afi = AutoCarga::factory("TercerosConvenios","", "app","UV_Afiliaciones_Admin");

        if($filtros=='1')
        {
            $filtros=SessionGetVar("BUSQUEDA");
        }
        
        if($usu_cant==0)
        {
            $usu_cant = $afi->GetTercerosConvenios($filtros, $count=true, $limit=false, $offset=false);
        }

        $limit=10;
        $offset=($pagina-1)*$limit;
        $usuarios = $afi->GetTercerosConvenios($filtros, $count=null, $limit, $offset);
        
        if(!empty($usuarios))
        {
            SessionDelVar("BUSQUEDA");
            SessionSetVar("BUSQUEDA",$filtros);
            $html .= "                 <table width=\"60%\" align=\"center\">\n";
            $html .= "                    <tr class=\"normal_10AN\">\n";
            $html .= "                       <td width=\"100%\" align=\"left\">\n";
            $html .= "                       SE ENCONTRARON (".$usu_cant.") REGISTRO(S)";
            $html .= "                      </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                 </tABLE>\n";
            $html .= "          <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "              <tr class=\"modulo_table_list_title\">\n";
            $html .= "                <td width='20%'  align=\"center\">\n";
            $html .= "                  TERCERO ID";
            $html .= "                </td>\n";
            $html .= "                <td width='70%'  align=\"center\">\n";
            $html .= "                  NOMBRE";
            $html .= "                </td>\n";
            $html .= "                <td width='10%'  align=\"center\">\n";
            $html .= "                  ESTADO";
            $html .= "                </td>\n";
            $html .= "              </tr>\n";
            for($i=0;$i<count($usuarios);$i++)
            {
                $sitio_accion="accion".$i;
                $html .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $html .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                $html .= "                       ".$usuarios[$i]['tipo_id_tercero']."-".$usuarios[$i]['tercero_id'];
                $html .= "                       </td>\n";
                $html .= "                      <td align=\"left\">\n";
                $html .= "                       <a title='".$usuarios[$i]['nombre_tercero']."'>";
                $html .= "                       ".$usuarios[$i]['nombre_tercero'];
                $html .= "                       </a>\n";
                $html .= "                      </td>\n";
                $html .= "                      <td align=\"center\" id='".$sitio_accion."'>\n";
                if($usuarios[$i]['sw_estado']==='1')
                {
                    $cambiar_usu_admin = "javascript:CambiarEstadoConv('".$usuarios[$i]['tipo_id_tercero']."','".$usuarios[$i]['tercero_id']."','".$sitio_accion."');";//
                    $html .= "                      <a title='CAMBIAR A ESTADO DESACTIVADO' href=\"".$cambiar_usu_admin."\">";
                    $html .= "                        <sub><img src=\"".$path."/images/checkS.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $html .= "                      </a>\n";
                }
                elseif($usuarios[$i]['sw_estado']==='0')
                {
                    $cambiar_usu_admin = "javascript:CambiarEstadoConv('".$usuarios[$i]['tipo_id_tercero']."','".$usuarios[$i]['tercero_id']."','".$sitio_accion."');";//
                    $html .= "                      <a title='CAMBIAR A ESTADO ACTIVADO' href=\"".$cambiar_usu_admin."\">";
                    $html .= "                          <sub><img src=\"".$path."/images/checkN.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $html .= "                      </a>\n";
                }
                $html .= "                       </td>\n";
            }
            $html .= "               </tr>\n";
            $html .= "            </table>\n";

            $html .= "".ObtenerPaginadorConv($pagina,$path,$usu_cant,$op);
            
        }
        else
        {
            $html .= "                 <table width=\"100%\" align=\"center\">\n";
            $html .= "                    <tr class=\"label_error\">\n";
            $html .= "                       <td width=\"100%\" align=\"center\">\n";
            $html .= "                       NO SE ENCONTRARON RESULTADOS CON ESE CRITERIO DE BUSQUEDA";
            $html .= "                      </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                 </table>\n";
            
        }
        $objResponse->assign("lista_ter","innerHTML",$html);
        
        return $objResponse;

    }


     
    /**
    * Metodo para colocar el menu dependiendo del tipo de busqueda
    *
    * @param integer  $tipo_de_busqueda (CRITERIO DE BUSQUEDA)
    * @return string  $salida con la forma del menu
    * @access public
    */
    function TipoBusqueda($tipo_de_busqueda)
    {
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("Afiliaciones_Admin", "", "app","UV_Afiliaciones_Admin");  

        if($tipo_de_busqueda==0)
        {
            $salida .= "           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            $objResponse->call("Volver");
        }
        if($tipo_de_busqueda==1)
        {
            $combo_tipos_id=$afi->ObtenerTercerosTiposId();
            $salida .= "                          TIPO DE DOCUMENTO";
            //var_dump($combo_tipos_id);
            if(!empty($combo_tipos_id))
            {
            $salida.= "                            <select id=\"tipo_id_tercero\" name=\"tipo_id_tercero\" class=\"select\" onchange=\"Tachar(this.value);\">";
            foreach($combo_tipos_id as $key => $datos)
            {
                $salida .= "                          <option value=\"".$datos['tipo_id_tercero']."\" title='".$datos['descripcion']."'>".$datos['tipo_id_tercero']."</option>\n";
            }
            $salida.= "                             </select>\n";
            }
            $salida .= "                             &nbsp; TERCERO ID";
            $salida .= "                             <input type=\"text\" class=\"input-text\" id=\"tercero_id\" name=\"tercero_id\" maxlength=\"20\" size=\"20\" value=\"\" onkeypress=\"return acceptNum(event)\" onkeydown=\"recogerTecla(event);\" onclick=\"limpiar()\">";

        }
        elseif($tipo_de_busqueda==2)
        {
            $salida .= "                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NOMBRE TERCERO";
            $salida .= "                             <input type=\"text\" class=\"input-text\" id=\"nombre_tercero\" name=\"nombre_tercero\" maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"return acceptm(event)\" onkeydown=\"recogerTecla(event);\">";
        }       
        $objResponse->assign("aux","innerHTML",$salida);
        return $objResponse;
    }

    /**
    * Metodo para la eliminacion de usuario del sistema
    * @param string $usuario_id
    * @param string $usuario_tr la posicion del td html
    * @return string  $salida con la forma del menu
    * @access public
    */    
    function EliminarUsuarioBD($usuario_id,$usuario_tr)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("UsuariosPer","", "app","UV_Afiliaciones_Admin");
        $resultado=$afi->DelUsuarioAfiliaciones($usuario_id);
        if($resultado===true)
        {
            
            $objResponse->assign("error_usuarios2","innerHTML","EL USUARIO ".$usuario_id." HA SIDO ELIMINADO");
            $objResponse->remove($usuario_tr);
        }
        else
        {
            $objResponse->assign("ContenidoConf","innerHTML","ERROR ".$afi->mensajeDeError."");
           
        }

        return $objResponse;
    }


    /**
    * Funcion que se encarga de mostrar una ventana de confirmacion para la eliminacion de usuarios
    * @param string $usuario login del usuario
    * @param string $usuario_id
    * @param string $nombre nombre del usuario
    * @param string $usuario_tr id del tr html de la tabla en caso de que el usuario see eliminado y borrado de la tabla
    * @return string con la forma de la ventana de confirmacion
    **/

    function ConfirmaEliminaUsu($usuario,$usuario_id,$nombre,$usuario_tr)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        
        $da = "     <form name=\"eliminar_movimiento\">\n";  
        $da .= "      <table width='100%' border='0'>\n";  
        $da .= "       <tr>\n";  
        $da .= "        <td colspan='2' class=\"label_error\">\n";  
        $da .= "          ¿ ESTA SEGURO DE ELIMINAR EL USUARIO ".$usuario." ";
        $da .= "          CON NOMBRE ".$nombre." ?";
        $da .= "        </td>\n";  
        $da .= "       </tr>\n";  
        $da .= "       <tr>\n";  
        $da .= "        <td align='center' colspan='2'>\n";  
        $da .= "          &nbsp;"; 
        $da .= "        </td>\n";  
        $da .= "       </tr>\n";  
        $da .= "       <tr>\n";  
        $da .= "        <td align='center'>\n";  
        $da .= "          <input type=\"button\" class=\"input-submit\" value=\"ELIMINAR\" name=\"ELIMINAR_MOV\" onclick=\"xajax_EliminarUsuarioBD('".$usuario_id."','".$usuario_tr."');Cerrar('ContenedorConf');\">\n";
        $da .= "        </td>\n";  
        $da .= "        <td align='center'>\n";  
        $da .= "          <input type=\"button\" class=\"input-submit\" value=\"CANCELAR\" name=\"CANCELAR\" onclick=\"Cerrar('ContenedorConf');\">\n";
        $da .= "        </td>\n";  
        $da .= "       </tr>\n";  
        $da .= "      </table>\n";  
        $da .= "     </form>\n";  
        $objResponse->assign("ContenidoConf","innerHTML",$da);
        return $objResponse;
    }
    

    
    /**
    * Funciqon mque muestra un menu para la adicion y asignacion de perfiles a un usuario
    * @param string $usuario login del usuario
    * @param string $usuario_id
    * @param string $usu_nom nombre usuario
    * @param string $perfil
    * @param string $sw_admin switch administrador
    * @param string $sitio_accion td que sera eliminado despues de ser agregado
    * @return string con la forma del menu
    **/
    function AdicionarUsuarioConPerfilBD($usuario,$usuario_id,$usu_nom,$perfil,$sw_admin,$sitio_accion)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("UsuariosPer","", "app","UV_Afiliaciones_Admin");
        $resultado=$afi->AddUsuarioAfiliaciones($usuario_id,$perfil,$sw_admin);
        if($resultado===true)
        {
            $html="EL USUARIO ".$usuario." FUE ASIGNADO SATISFACTORIAMENTE";
        }
        else
        {
            $html="ERROR".$afi->mensajeDeError; 
        }
        $objResponse->assign("error_usuarios2","innerHTML",$html);
        $objResponse->call("CerrarPerfiles");
        
        $objResponse->call("HacerSubmit");
        return $objResponse;

    }



    /**
    * Funcion mque muestra un menu para la adicion y asignacion de perfiles a un usuario
    * @param string $usuario login del usuario
    * @param string $usuario_id
    * @param string $nombre nombre usuario
    * @param string $sitio_accion td que sera eliminado despues de ser agregado
    * @return string con la forma del menu
    **/
    function AdicionarUser($usuario,$usuario_id,$nombre,$sitio_accion)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("Afiliaciones_Admin", "", "app","UV_Afiliaciones_Admin");
        $perfiles=$afi->GetPerfiles();
        $html .= "          <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "              <tr class=\"modulo_table_list_title\">\n";
        $html .= "                <td align=\"center\" COLSPAN='2'>\n";
        $html .= "                  SELECCIONAR PERFIL DEL USUARIO ".$usuario;
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr >\n";
        $html .= "                <td class=\"modulo_table_list_title\" align=\"center\">\n";
        $html .= "                 NOMBRE";
        $html .= "                </td>\n";
        $html .= "                <td align=\"left\" class=\"modulo_list_claro\" >\n";
        $html .= "                      <a title='USUARIO ".$usuario_id."'>";
        $html .= "                       ".$nombre;
        $html .= "                      </a>";
        $html .= "                </td>\n";
        $html .= "              <tr class=\"modulo_list_claro\">\n";
        $html .= "                <td class=\"modulo_table_list_title\" align=\"center\">\n";
        $html .= "                 PERFIL";
        $html .= "                </td>\n";
        $html .= "                <td align=\"left\">\n";
        $html .= "                  <select name=\"perfil1\" id=\"perfil1\" class=\"select\" onchange=\"\">";
        $html .= "                    <option value=\"0\">--SELECCIONAR--</option>\n";
        foreach($perfiles as $key => $valor)
        {
            if($valor['perfil_id']==$perfil_usu)
            {
                $html .= "            <option value=\"".$valor['perfil_id']."\" selected>".$valor['descripcion_perfil']."</option>\n";
            }
            else
            {
                $html .= "            <option value=\"".$valor['perfil_id']."\">".$valor['descripcion_perfil']."</option>\n";
            }            
        }
        $html .= "                  </select>\n";

        $html .= "                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"sw_administrador\" id=\"sw_administrador\" value=\"1\" onclick=\"document.getElementById('errorB3').innerHTML='';\">\n";
        $html .= "                ADMINISTRADOR";
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"modulo_list_claro\">\n";
        $html .= "                <td align=\"center\" colspan='2'>\n";                                          //                                                               usuario,      usuario_id,nombre,                          perfil,                    sitio_perfil,sitio_accion
        $html .= "                  <input type=\"button\" class=\"input-submit\" name=\"cancellar\" id=\"cancellar\" value=\"CANCELAR\" onclick=\"CerrarPerfiles();\">\n";
        $html .= "                &nbsp;&nbsp;&nbsp;";
                                               //                                                               usuario,      usuario_id,nombre,                          perfil,                    sitio_perfil,sitio_accion
        $html .= "                  <input type=\"button\" class=\"input-submit\" name=\"buscar_usu\" id=\"buscar_usu\" value=\"ADICIONAR USUARIO\" onclick=\"AdicionarUsuarioConPerfil('".$usuario."','".$usuario_id."','".$usu_nom."',document.getElementById('perfil1').value,'".$sitio_accion."');\">\n";
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        $html .= "            </table>\n";
        
        $objResponse->assign("ContenidoB3","innerHTML",$html);
        return $objResponse;





    }

    /**
    * funcion que sirve para buscar los usuarios que no pertenecen al sistema EPS
    * @param array $filtros vector con os datos a consultar 
    * @param string $pagina con el numero de la pagina  buscar
    * @param string $usu_cant contador de usuarios cuando se utiliza por primera vez sera cero
    * @return string $html con la forma dela consulta
    **/    
    function BuscarUsuSys($filtros,$pagina,$usu_cant)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("UsuariosPer","", "app","UV_Afiliaciones_Admin");
        
        if($usu_cant==0)
        {
            $usu_cant = $afi->GetSystemUsers($filtros, $count=true, $limit=false, $offset=false);
        }

        $limit=10;
        $offset=($pagina-1)*$limit;
        $usuarios = $afi->GetSystemUsers($filtros, $count=null, $limit, $offset);
        //$afiliados = $afi->GetAfiliados($datos, $count=false, $limit, $offset);
        
        //$objResponse->assign("JJJJJ");
        //var_dump($usu_cant);
        //var_dump($usuarios);
        if(!empty($usuarios))
        {
            $html .= "                 <table width=\"60%\" align=\"center\">\n";
            $html .= "                    <tr class=\"normal_10AN\">\n";
            $html .= "                       <td width=\"100%\" align=\"left\">\n";
            $html .= "                       SE ENCONTRARON (".$usu_cant.") REGISTRO(S)";
            $html .= "                      </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                 </tABLE>\n";
            $html .= "          <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "              <tr class=\"modulo_table_list_title\">\n";
            $html .= "                <td width='16%'  align=\"center\">\n";
            $html .= "                  USUARIO ID";
            $html .= "                </td>\n";
            $html .= "                <td width='16%'  align=\"center\">\n";
            $html .= "                  LOGIN";
            $html .= "                </td>\n";
            $html .= "                <td width='48%'  align=\"center\">\n";
            $html .= "                  NOMBRE";
            $html .= "                </td>\n";
            $html .= "                <td width='20%'  align=\"center\">\n";
            $html .= "                  ACCIONES";
            $html .= "                </td>\n";
            $html .= "              </tr>\n";
            for($i=0;$i<count($usuarios);$i++)
            {
                $sitio="switch_admin".$i;
                $sitio_perfil="perfil_sitio".$i;
                $sitio_accion="accion".$i;
                $html .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $html .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
                $html .= "                       ".$usuarios[$i]['usuario_id'];
                $html .= "                       </td>\n";
                $html .= "                      <td align=\"center\">\n";
                $html .= "                       ".$usuarios[$i]['usuario'];
                $html .= "                       </td>\n";
                $html .= "                      <td align=\"left\">\n";
                $html .= "                       <a title='".$usuarios[$i]['nombre']."'>";
                $html .= "                       ".$usuarios[$i]['nombre'];
                $html .= "                       </a>\n";
                $html .= "                      </td>\n";
                $html .= "                      <td id='$sitio_accion' align=\"center\">\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
                $EDITA_PERFIL = "javascript:AdicionarUser('".$usuarios[$i]['usuario']."','".$usuarios[$i]['usuario_id']."','".$usuarios[$i]['nombre']."','".$sitio_accion."');MostrarCapa('ContenedorB3');IniciarB3('ADICIONAR USUARIO');";//
                $html .= "                         <a title='EDITAR PERFIL' href=\"".$EDITA_PERFIL."\">";
                $html .= "                          <sub><img src=\"".$path."/images/editar.gif\" border=\"0\" width=\"17\" height=\"17\"> SELECCIONAR</sub>\n";
                $html .= "                         </a>\n";

            }
            $html .= "               </tr>\n";
            $html .= "            </table>\n";

            $html .= "".ObtenerPaginadorNOAFI($pagina,$path,$usu_cant,$op);
            
        }
        else
        {
            $html .= "                 <table width=\"100%\" align=\"center\">\n";
            $html .= "                    <tr class=\"label_error\">\n";
            $html .= "                       <td width=\"100%\" align=\"center\">\n";
            $html .= "                       NO SE ENCONTRARON RESULTADOS CON ESE CRITERIO DE BUSQUEDA";
            $html .= "                      </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                 </table>\n";
            
        }
        $objResponse->assign("resultado_usuarios_sys","innerHTML",$html);
        return $objResponse;
    }

    /**
    * Funcion que se encarga de mostrar una tabla paginadora para la lista de usuarios afiliados
    * @param string $pagina
    * @param string $path
    * @param string $slc
    * @param string $op
    * @return string $Tabla con la tabla que indica el listado del paginador
    **/
    function ObtenerPaginadorNOAFI($pagina,$path,$slc,$op)
    {

      
      //echo "io";
      $TotalRegistros = $slc;
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $LimitRow = 10;
      }
      else
      {
        $LimitRow = 10;
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
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                                                                                         //  xajax_ObtenerDocumentosBodegaFinal(empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarUsuSysx('1','".$slc."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarUsuSysx('".($pagina-1)."','".$slc."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarUsuSysx('".$i."','".$slc."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarUsuSysx('".($pagina+1)."','".$slc."');\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarUsuSysx('".($NumeroPaginas)."','".$slc."');\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     P�ina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
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
    * Funcion que sirve para actulizar el perfil de un usuario determinado
    * @param string $usuario
    * @param string $usuario_id usuario id del usuario al cual se le hara la actualzacion de perfil
    * @param string $perfil id del nuevo perfil que se le asignara al usuario
    * @param string $nombre 
    * @param string $sitio_perfil 
    * @param string $sitio_accion 
    * @param string $texto 
    * @return $respuesta boolean que indicara con uno si se hizo la actualizacion o ó cero si no se hizo la actualizacion
    **/          
    function Asignar_Perfil($usuario,$usuario_id,$nombre,$perfil,$sitio_perfil,$sitio_accion,$texto)
    {            

        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("UsuariosPer", "", "app","UV_Afiliaciones_Admin");    
        $usuario_r=$afi-> ModificarPerfilUsuarioAfiliaciones($usuario_id,$perfil);
       
        //var_dump($usuario_r);

        if($usuario_r===true)
        {
            $html .= "                       <a title='".$texto."'>";
            $html .= "                       ".$perfil;
            $html .= "                       </a>\n";
            $objResponse->assign($sitio_perfil,"innerHTML",$html);
            $salida="SE HA ACTUALIZADO EL PERFIL DEL USUARIO ".$usuario." SATISFACTORIAMENTE";
            $objResponse->assign("error_usuarios2","innerHTML",$salida);
            $EDITA_PERFIL = "javascript:MostrarPerfiles('".$usuario."','".$usuario_id."','".$nombre."','".$perfil."','".$sitio_perfil."','".$sitio_accion."');MostrarCapa('ContenedorB3');IniciarB3('EDITAR PERFIL');";//
            $html1 .= "                         <a title='EDITAR PERFIL' href=\"".$EDITA_PERFIL."\">";
            $html1 .= "                          <sub><img src=\"".$path."/images/editar.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
            $html1 .= "                         </a>\n";
            $html1 .= "                         &nbsp;";
            $nuevousu = "javascript:EliminarUsu('".$usuario_id."');MostrarCapa('ContenedorB3');IniciarB3('DATOS DEL DOCUMENTO');";//
            $html1 .= "                         <a title='ELIMINAR USUARIO' href=\"".$nuevousu."\">";
            $html1 .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
            $html1 .= "                         </a>\n";
            $html1 .= "                       </td>\n";
            $objResponse->assign($sitio_accion,"innerHTML",$html1);
            $objResponse->call("CerrarPerfiles");
            
        }
        else
        {


            $salida="NO SE HA ACTUALIZADO EL PERFIL DEL USUARIO ".$usuario." ASEGURESE DE HABER SELECCIONADO UN PERFIL";
            $objResponse->assign("errorB3","innerHTML",$salida);
        }
            
        return $objResponse;
    }
    
    /**
    * Funcion que sirve para mostrar un listado de perfiles disponibles para los usuarios del sistema
    * @param string $usuario 
    * @param string $usuario_id 
    * @param string $usu_nom
    * @param string $perfil_usu
    * @param string $sitio_perfil 
    * @param string $sitio_accion 
    * @return string $listado listado de perfiles disponibles
    **/   
    function ColocarPerfiles($usuario,$usuario_id,$usu_nom,$perfil_usu,$sitio_perfil,$sitio_accion)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("Afiliaciones_Admin", "", "app","UV_Afiliaciones_Admin");
        $perfiles=$afi->GetPerfiles();
        $html .= "          <table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
        $html .= "              <tr class=\"formulacion_table_list\">\n";
        $html .= "                <td align=\"center\" COLSPAN='2'>\n";
        $html .= "                  SELECCIONAR PERFIL DEL USUARIO ".$usuario;
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr >\n";
        $html .= "                <td class=\"formulacion_table_list\" align=\"center\">\n";
        $html .= "                 NOMBRE";
        $html .= "                </td>\n";
        $html .= "                <td align=\"left\" class=\"modulo_list_claro\" >\n";
        $html .= "                      <a title='USUARIO ".$usuario_id."'>";
        $html .= "                 ".$usu_nom;
        $html .= "                      </a>";
        $html .= "                </td>\n";
        $html .= "              <tr class=\"modulo_list_claro\">\n";
        $html .= "                <td class=\"formulacion_table_list\" align=\"center\">\n";
        $html .= "                 PERFIL";
        $html .= "                </td>\n";
        $html .= "                <td align=\"left\">\n";
        $html .= "                  <select name=\"perfil1\" id=\"perfil1\" class=\"select\" onchange=\"\">";
        $html .= "                    <option value=\"\">--SELECCIONAR--</option>\n";
        foreach($perfiles as $key => $valor)
        {
            if($valor['perfil_id']==$perfil_usu)
            {
                $html .= "            <option value=\"".$valor['perfil_id']."\" selected>".$valor['descripcion_perfil']."</option>\n";
            }
            else
            {
                $html .= "            <option value=\"".$valor['perfil_id']."\">".$valor['descripcion_perfil']."</option>\n";
            }            
        }
        $html .= "                  </select>\n";
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr class=\"modulo_list_claro\">\n";
        $html .= "                <td align=\"center\" colspan='2'>\n";                                          //                                                               usuario,      usuario_id,nombre,                          perfil,                    sitio_perfil,sitio_accion
        $html .= "                  <input type=\"button\" class=\"input-submit\" name=\"buscar_usu\" id=\"buscar_usu\" value=\"SELECCIONAR\" onclick=\"PerfilSeleccionado('".$usuario."','".$usuario_id."','".$usu_nom."',document.getElementById('perfil1').value,'".$sitio_perfil."','".$sitio_accion."');\">\n";
        $html .= "                </td>\n";
        $html .= "              </tr>\n";
        $html .= "            </table>\n";
        
        $objResponse->assign("ContenidoB3","innerHTML",$html);
        return $objResponse;
    }





    /**
    *   Funcion que sirve para cambiar el estado del usuario
    *   @param string $usuario_id
    *   @param string $sitio
    *   @return string estado del Usuario.
    **/
    function CambiarPermisoAdminUsuario($usuario_id,$sitio)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("UsuariosPer", "", "app","UV_Afiliaciones_Admin");    
        $usuario=$afi->CambiarPermisoAdminUsuarioAfiliaciones($usuario_id);
        //var_dump($usuario);
        if($usuario==='1')
        {
            $cambiar_usu_admin = "javascript:CambiarEstadoAd('".$usuario_id."','".$sitio."');";//
            $html .= "                      <a title='CAMBIAR ESTADO' href=\"".$cambiar_usu_admin."\">";
            $html .= "                        <sub><img src=\"".$path."/images/activo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
            $html .= "                      </a>\n";
        }
        elseif($usuario==='0')
        {
            $cambiar_usu_admin = "javascript:CambiarEstadoAd('".$usuario_id."','".$sitio."');";//
            $html .= "                      <a title='CAMBIAR ESTADO' href=\"".$cambiar_usu_admin."\">";
            $html .= "                          <sub><img src=\"".$path."/images/inactivo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
            $html .= "                      </a>\n";
        }
        
        $objResponse->assign($sitio,"innerHTML",$html);
        return $objResponse;
    }

    /**
    *   Funcion que sirve para obtener la lista de usuarios que se encuentren en el sistema
    *   @param array $filtros con el vector de datos enviados por el formuario
    *   @param string $pagina numero de pagina de la lista de resultados encontrados por el buscador de usuarios
    *   @param string $usu_cant total de registros encontrados por la consulta (cuando se hace por primera vez sera igual a cero)
    *   @return string $html con la lista de usuarios
    **/
    function BuscarUsu($filtros,$pagina,$usu_cant)
    {
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("UsuariosPer", "", "app","UV_Afiliaciones_Admin");
        
        if($usu_cant==0)
        {
            $usu_cant = $afi->GetUsuariosAfiliaciones($filtros, $count=true, $limit=false, $offset=false);
        }

        $limit=10;
        $offset=($pagina-1)*$limit;
        $usuarios = $afi->GetUsuariosAfiliaciones($filtros, $count=null, $limit, $offset);
        //$afiliados = $afi->GetAfiliados($datos, $count=false, $limit, $offset);
        
        //$objResponse->assign("JJJJJ");
        //var_dump($usu_cant);
        if(!empty($usuarios))
        {
            $html .= "          <table border=\"0\" width=\"60%\" align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "              <tr class=\"formulacion_table_list\">\n";
            $html .= "                <td width='14%'  align=\"center\">\n";
            $html .= "                  USUARIO ID";
            $html .= "                </td>\n";
            $html .= "                <td width='10%'  align=\"center\">\n";
            $html .= "                  LOGIN";
            $html .= "                </td>\n";
            $html .= "                <td width='48%'  align=\"center\">\n";
            $html .= "                  NOMBRE";
            $html .= "                </td>\n";
            $html .= "                <td width='8%'  align=\"center\">\n";
            $html .= "                  PERFIL";
            $html .= "                </td>\n";
            $html .= "                <td width='8%'  align=\"center\">\n";
            $html .= "                  ADMIN";
            $html .= "                </td>\n";
            $html .= "                <td width='12%'  align=\"center\">\n";
            $html .= "                  ACCIONES";
            $html .= "                </td>\n";
            $html .= "              </tr>\n";
            for($i=0;$i<count($usuarios);$i++)
            {
                $sitio="switch_admin".$i;
                $sitio_perfil="perfil_sitio".$i;
                $sitio_accion="accion".$i;
                $usuario_tr="tr".$i;
                $html .= "                    <tr id='".$usuario_tr."' class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $html .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
                $html .= "                       ".$usuarios[$i]['usuario_id'];
                $html .= "                       </td>\n";
                $html .= "                      <td align=\"center\">\n";
                $html .= "                       ".$usuarios[$i]['usuario'];
                $html .= "                       </td>\n";
                $html .= "                      <td align=\"left\">\n";
                $html .= "                       <a title='".$usuarios[$i]['nombre']."'>";
                $html .= "                       ".$usuarios[$i]['nombre'];
                $html .= "                       </a>\n";
                $html .= "                      </td>\n";
                $html .= "                      <td id='".$sitio_perfil."' align=\"center\">\n";
                $html .= "                       <a title='".$usuarios[$i]['descripcion_perfil']."'>";
                $html .= "                       ".$usuarios[$i]['perfil_id'];
                $html .= "                       </a>\n";
                $html .= "                      </td>\n";
                $html .= "                      <td id='".$sitio."' align=\"CENTER\">\n";
                $html .= "                       <a title='".$usuarios[$i]['sw_admin']."'>";
                
                if($usuarios[$i]['sw_admin']=='1')
                {   
                    $cambiar_usu_admin = "javascript:CambiarEstadoAd('".$usuarios[$i]['usuario_id']."','".$sitio."');";//
                    $html .= "                      <a title='CAMBIAR ESTADO' href=\"".$cambiar_usu_admin."\">";
                    $html .= "                        <sub><img src=\"".$path."/images/activo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                    $html .= "                      </a>\n";
                }
                elseif($usuarios[$i]['sw_admin']=='0')
                {
                    $cambiar_usu_admin = "javascript:CambiarEstadoAd('".$usuarios[$i]['usuario_id']."','".$sitio."');";//
                    $html .= "                      <a title='CAMBIAR ESTADO' href=\"".$cambiar_usu_admin."\">";
                    $html .= "                          <sub><img src=\"".$path."/images/inactivo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
                    $html .= "                      </a>\n";
                }
                
                $html .= "                       </a>\n";
                $html .= "                      </td>\n";
                $html .= "                      <td id='$sitio_accion' align=\"center\">\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
                $EDITA_PERFIL = "javascript:MostrarPerfiles('".$usuarios[$i]['usuario']."','".$usuarios[$i]['usuario_id']."','".$usuarios[$i]['nombre']."','".$usuarios[$i]['perfil_id']."','".$sitio_perfil."','".$sitio_accion."');MostrarCapa('ContenedorB3');IniciarB3('EDITAR PERFIL');";//
                $html .= "                         <a title='EDITAR PERFIL' href=\"".$EDITA_PERFIL."\">";
                $html .= "                          <sub><img src=\"".$path."/images/editar.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $html .= "                         </a>\n";
                $html .= "                         &nbsp;";    
                $nuevousu = "javascript:EliminarUsuario('".$usuarios[$i]['usuario']."','".$usuarios[$i]['usuario_id']."','".$usuarios[$i]['nombre']."','".$usuario_tr."');MostrarCapa('ContenedorConf');IniciarConf('ELIMINAR USUARIO');";//
                $html .= "                         <a title='ELIMINAR USUARIO' href=\"".$nuevousu."\">";
                $html .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $html .= "                         </a>\n";
                $html .= "                       </td>\n";
            }
            $html .= "               </tr>\n";
            $html .= "            </table>\n";

            $html .= "".ObtenerPaginadorBAFI($pagina,$path,$usu_cant,$op);
            
        }
        else
        {
            $html .= "                 <table width=\"100%\" align=\"center\">\n";
            $html .= "                    <tr class=\"label_error\">\n";
            $html .= "                       <td width=\"100%\" align=\"center\">\n";
            $html .= "                       NO SE ENCONTRARON RESULTADOS CON ESE CRITERIO DE BUSQUEDA";
            $html .= "                      </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                 </table>\n";
            
        }
        $objResponse->assign("resultado_usuarios","innerHTML",$html);
        return $objResponse;
    }


    
    /**
    * Funcion que se encarga de mostrar una tabla paginadora para la lista de usuarios afiliados
    * @param string $pagina
    * @param string $path
    * @param string $slc
    * @param string $op
    * @return string $Tabla con la tabla que indica el listado del paginador
    **/
    function ObtenerPaginadorBAFI($pagina,$path,$slc,$op)
    {

      
      //echo "io";
      $TotalRegistros = $slc;
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $LimitRow = 10;
      }
      else
      {
        $LimitRow = 10;
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
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                                                                                         //  xajax_ObtenerDocumentosBodegaFinal(empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarUsuAdmin('1','".$slc."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarUsuAdmin('".($pagina-1)."','".$slc."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarUsuAdmin('".$i."','".$slc."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarUsuAdmin('".($pagina+1)."','".$slc."');\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarUsuAdmin('".($NumeroPaginas)."','".$slc."');\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     P�ina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
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
    *   Funcion que sirve para obtener la busqueda de afiliados por diferentes criterios de busqueda
    *   @param string $datos
    *   @param string $pagina
    *   @param string $contador
    *   @return array $salida vector con todos datos de los afiliados encontrados en la busqueda
    **/
    function  BuscarDatos($datos,$pagina,$contador)
    {
        //var_dumP($datos);
        //var_dump($pagina);
      //var_dump($contador);

//         foreach($datos as $key=>$valor)
//         {
//             if(!empty($valor))
//             {
//                 echo "aa".$key."--".$valor;
//             }
//         }
        $path = SessionGetVar("rutaImagenes");
        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("ConsultarAfiliados", "", "app","UV_Afiliaciones");

        if($contador==0)
        {
            $contador = $afi->GetAfiliados($datos, $count=true, $limit=false, $offset=0);
        }
             
        $limit=20;
        $offset=($pagina-1)*$limit;
        $afiliados = $afi->GetAfiliados($datos, $count=false, $limit, $offset);
       //  var_dump($afiliados);
       //$objResponse->alert();

        if(!empty($afiliados))
        {
            $salida .= "                 <table width=\"100%\" align=\"center\">\n";
            $salida .= "                    <tr class=\"normal_10AN\">\n";
            $salida .= "                       <td width=\"100%\" align=\"left\">\n";
            $salida .= "                       SE ENCONTRARON (".$contador.") REGISTRO(S)";
            $salida .= "                      </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                 </tABLE>\n";
            $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td width=\"2%\" align=\"center\">\n";
            $salida .= "                       <a title='EPS TIPO AFILIADO'>";
            $salida .= "                        T";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"8%\" align=\"center\">\n";
            $salida .= "                       <a title='ESTADO DEL AFILIADO'>";
            $salida .= "                        ESTADO";
            $salida .= "                       </a>";
//             $salida .= "                       </td>\n";
//             $salida .= "                       <td width=\"5%\" align=\"center\">\n";
//             $salida .= "                       <a title='SUBESTADO DEL AFILIADO'>";
//             $salida .= "                        SUBESTADO";
//             $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"13%\" align=\"center\">\n";
            $salida .= "                       <a title='IDENTIFICACION DEL AFILIADO'>";
            $salida .= "                         IDENTIFICACION";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"27%\" align=\"center\">\n";
            $salida .= "                       <a title='NOMBRE DEL AFILIADO'>";
            $salida .= "                          NOMBRE";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"12%\" align=\"center\">\n";
            $salida .= "                       <a title='ESTAMENTO'>";
            $salida .= "                        ESTAMENTO";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"12%\" align=\"center\">\n";
            $salida .= "                       <a title='DEPENDENCIA'>";
            $salida .= "                        DEP";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"12%\" align=\"center\">\n";
            $salida .= "                       <a title='TIPO DE APORTANTE'>";
            $salida .= "                        APORTANTE";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"10%\" align=\"center\">\n";
            $salida .= "                       <a title='FECHA DE REGISTRO'>";
            $salida .= "                          FECHA";
            $salida .= "                       </a>";
            $salida .= "                       </td>\n";
            $salida .= "                       <td colspan='2' width=\"4%\" align=\"center\">\n";
            $salida .= "                          ACCIONES";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";
            for($i=0;$i<count($afiliados);$i++)
            {   
                $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $salida .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_eps_tipo_afiliado']."'>";
                $salida .= "                       ".$afiliados[$i]['eps_tipo_afiliado_id'];
                $salida .= "                       </a>\n";
                $salida .= "                       </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_estado']."'>";
                $salida .= "                       ".$afiliados[$i]['estado_afiliado_id'];
                $salida .= "                      </a>\n";
                $salida .= "                      - ";
//                 $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_subestado']."'>";
                $salida .= "                       ".$afiliados[$i]['subestado_afiliado_id'];
                $salida .= "                       </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       ".$afiliados[$i]['afiliado_tipo_id']."-".$afiliados[$i]['afiliado_id'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td class=\"normal_10AN\" align=\"left\">\n";
                $salida .= "                       ".$afiliados[$i]['nombre_afiliado'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_subestado']."'>";
                $salida .= "                       ".$afiliados[$i]['descripcion_estamento'];
                $salida .= "                       </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_dependencia']."'>";
                $salida .= "                       ".$afiliados[$i]['codigo_dependencia_id'];
                $salida .= "                       </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                       <a title='".$afiliados[$i]['descripcion_tipo_aportante']."'>";
                $salida .= "                       ".$afiliados[$i]['tipo_aportante_id'];
                $salida .= "                       </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                       ".$afiliados[$i]['fecha_afiliacion'];
                $salida .= "                      </td>\n";       
                $salida .= "                      <td  align=\"center\">\n";                          //a.bodegas_doc_id,    a.tipo_doc_bodega_id,
                $nuevousu = "javascript:MostarDatosDocumento('".$empresa_id."','".$valor['prefijo']."','".$valor['numero']."');MostrarCapa('ContenedorDet');IniciarDoc('DATOS DEL DOCUMENTO');";//
                $salida .= "                         <a title='BODEGA DOCUMENTO' href=\"".$nuevousu."\">";
                $salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $salida .= "                         </a>\n";
                $salida .= "                       </td>\n";
//                 $salida .= "                      <td  align=\"center\">\n";
// //                                                     $direccion="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I001/imprimir/imprimir_docI001.php";
// //                                                     $imagen = "themes/$VISTA/" . GetTheme() ."/images//imprimir.png";
// //                                                     $actualizar="false";
// //                                                     $alt="IMPRIMIR DOCUMENTO";
// //                                                     $x=RetornarImpresionDoc($direccion,$alt,$imagen,SessionGetVar("EMPRESA"),$valor['prefijo'],$valor['numero']);
// //                 $salida .= "                     ".$x."";
//                 $salida .= "                       &nbsp;";
//                 $salida .= "                       </td>\n";
                $salida .= "                    </tr>\n";
            } 
            $salida .= "                    </table>\n";
            $salida .= "                    <br>\n";
            $op="1";
            $slc=$documentos;        
            $salida .= "".ObtenerPaginadorAFI($pagina,$path,$contador,$op,$datos);
            
        }
        else
        {
            
            $salida .= "                 <table width=\"100%\" align=\"center\">\n";
            $salida .= "                    <tr class=\"label_error\">\n";
            $salida .= "                       <td width=\"100%\" align=\"center\">\n";
            $salida .= "                       NO SE ENCONTRARON RESULTADOS CON ESE CRITERIO DE BUSQUEDA";
            $salida .= "                      </td>\n";
            $salida .= "                   </tr>\n";
            $salida .= "                 </table>\n";
        }
        $objResponse->assign("tabla_afiliados","innerHTML",$salida);
        return $objResponse;
    }


    /**
    * Funcion que sirve para la paginacion de registros generados por el buscador
    * @param string $pagina
    * @param string $path direccion de los temas visuales(imagenes) de la aplicacion
    * @param string $slc cantidad total de registros  
    * @param string $op opcion  para mostrar el paginador (arriba =0 , abajo =1)
    * @param array $datos vector que contiene los datos a buscar
    * @return string $Tabla con la forma del paginador
    *
    **/
    function ObtenerPaginadorAFI($pagina,$path,$slc,$op,$datos)
    {

      
     // var_dump($slc);
      $TotalRegistros = $slc;
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $LimitRow = 20;
      }
      else
      {
        $LimitRow = 20;
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
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                                                                                         //  xajax_ObtenerDocumentosBodegaFinal(empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'1','".$slc."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'".($pagina-1)."','".$slc."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'".$i."','".$slc."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'".($pagina+1)."','".$slc."');\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarAfiliados((xajax.getFormValues('consulta_afiliacion')),'".($NumeroPaginas)."','".$slc."');\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     P�ina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
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
    * Funcion que devuleve los subestados de los afiliados a partir de un estado.
    * @param string $estado
    * @return string $Tabla con la lista de subestados
    *
    **/
    function ObtenerSubestados($estado)
    {

        $objResponse = new xajaxResponse();
        $afi = AutoCarga::factory("Afiliaciones", "", "app","UV_Afiliaciones");
        $subestados = $afi->ObtenerTiposSubestadosAfiliados($estado);        
        //$objResponse->assign("JJJJJ");
        //var_dump($subestados);
         if(!empty($subestados))
         {
            $html .= "    <option value=\"0\">---Seleccionar---</option>\n";
            
            foreach($subestados as $key => $datos)
            {   
                $html .= "                  <option value=\"".$datos['subestado_afiliado_id']."\" >".$datos['descripcion_subestado']."</option>\n";
            }
            $html .= "              </select>\n";
             $objResponse->assign("subestado_afiliado_id","innerHTML",$html);
         }
   
        return $objResponse;
    }



    

    /**
    *   Funcion que devuelve el paginador de datos
    *   @param string $pagina
    *   @param string $path
    *   @param string $slc
    *   @param string $op
    *   @param string $empresa_id
    *   @param string $centro_utilidad
    *   @param string $bodega
    *   @param string $usuario_id
    *   @param string $clas_documento
    *   @param string $tipos_documento
    *   @return array $subestados vector con todos los subestados del afiliado
    **/
    function ObtenerPaginador($pagina,$path,$slc,$op,$empresa_id,$centro_utilidad,$bodega,$usuario_id,$clas_documento,$tipos_documento)
    {

      
      //echo "io";
      $TotalRegistros = $slc['contador'];
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $LimitRow = 20;
      }
      else
      {
        $LimitRow = 20;
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
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                                                                                         //  xajax_ObtenerDocumentosBodegaFinal(empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:MostrarDocusFinal('1','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:MostrarDocusFinal('".($pagina-1)."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:MostrarDocusFinal('".$i."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:MostrarDocusFinal('".($pagina+1)."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:MostrarDocusFinal('".($NumeroPaginas)."','".$empresa_id."','".$centro_utilidad."','".$bodega."','".$usuario_id."','".$clas_documento."','".$tipos_documento."');\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     P�ina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
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


?>