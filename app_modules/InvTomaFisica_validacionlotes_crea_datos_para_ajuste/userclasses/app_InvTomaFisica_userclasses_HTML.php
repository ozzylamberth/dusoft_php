<?php
	/**************************************************************************************  
	* $Id: app_InvTomaFisica_userclasses_HTML.php,v 1.17 2010/02/01 21:16:07 johanna Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.17 $ 
	* 
	* @autor Jaime G�ez 
	***************************************************************************************/
 IncludeClass("ClaseUtil");
    IncludeClass("ClaseHTML");
	class app_InvTomaFisica_userclasses_HTML extends app_InvTomaFisica_user
	{
		function app_InvTomaFisica_userclasses_HTML(){	}
		
    /***********************************************************************************
    * Muestra el menu de los empresas y centros de utilidad 
    * 
    * @access public 
    ***********************************************************************************/
     function SelectEmpresa()
     { 
      
       $this->MostrarEmpresas();
       $this->CrearElementos();
       $titulo[0]='EMPRESA';
       $url[0]='app';//contenedor 
       $url[1]='InvTomaFisica';//m�ulo
       $url[2]='user';//clase 
       $url[3]='MenuTomaFisica';//m�odo
       $url[4]='Empresas';//indice del request
       $this->salida .= gui_theme_menu_acceso('SELECCIONE EMPRESA',$titulo,$this->TodasEmpresas,$url,ModuloGetURL('system','Menu'));
       return true;
     }
/********************************************************************************** 
* Funci� principal del m�ulo 
* 
* @return boolean
***********************************************************************************/
    function main()
    {
      $this->SelectEmpresa();
      return true;
    }
		
    function MenuTomaFisica()
    { 
    
      $this->CrearElementos();
      IncludeClass("ClaseHTML");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $path = SessionGetVar("rutaImagenes");
      $consulta= new TomaFisicaSQL();
      $this->salida .= ThemeAbrirTabla("MOVIMIENTOS"); 
      $CONSULTARDOC=ModuloGetURL('app','InvTomaFisica','user','Admon');
      $CREARDOC=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisicaLogueo');
      $REVISARDOCS=ModuloGetURL('app','InvTomaFisica','user','ValidacionTomaFisicaLogueo');
      $VALIDARJEFE=ModuloGetURL('app','InvTomaFisica','user','ValidacionJefes');
      $VALIDARLOTES=ModuloGetURL('app','InvTomaFisica','user','ValidacionLotes');
      $CARGUEINVENTARIO=ModuloGetURL('app','InvTomaFisica','user','Cargue_inventario');
      $AJUSTARINVENTARIO=ModuloGetURL('app','InvTomaFisica','user','Ajustar_inventario');
      $MOFICICARPRODUCTO=ModuloGetURL('app','InvTomaFisica','user','Modificar_Producto');//$MOFICICARPRODUCTO
      $INFORMECONTEO2=ModuloGetURL('app','InvTomaFisica','user','Informe_Conteo');//$MOFICICARPRODUCTO
      
      $this->salida .= "            <form name=\"menu_docu\" action=\"javascript:LlamarDocu(1);\" method=\"post\">\n";
      $this->salida .= "                 <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $this->salida .= "                   <tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "                      <td align=\"center\">\n";
      $this->salida .= "                         MENU DE OPCIONES";
      $this->salida .= "                      </td>\n";
      $this->salida .= "                   </tr>\n";         
      $this->salida .= "                   <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a  title=\"PARAMETRIZACION TOMA FISICA\" class=\"label_error\" href=\"".$CONSULTARDOC."\">ADMINISTRACION TOMA FISICA</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a  title=\"RECOLECCION DE DATOS TOMA FISICA\" class=\"label_error\" href=\"".$CREARDOC."\">CAPTURA TOMA FISICA</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a  title=\"VALIDACION TOMA FISICA\" class=\"label_error\" href=\"".$REVISARDOCS."\">VALIDACION TOMA FISICA</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a  title=\"VALIDACION DE LOTES\" class=\"label_error\" href=\"".$VALIDARLOTES."\">VALIDACION DE LOTES</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a  title=\"CARGUE DE INVENTARIO\" class=\"label_error\" href=\"".$CARGUEINVENTARIO."\">CARGUE DE INVENTARIO</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a  title=\"MODIFICAR PRODUCTOS DE CONTEO\" class=\"label_error\" href=\"".$MOFICICARPRODUCTO."\">MODIFICAR PRODUCTOS DE CONTEO</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a  title=\"AJUSTAR INVENTARIO (TABLA NUEVA)\" class=\"label_error\" href=\"".$AJUSTARINVENTARIO."\">AJUSTAR INVENTARIO (TABLA NUEVA)</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a  title=\"INFORME CONTEO 2(DIFERENCIAS) \" class=\"label_error\" href=\"".$INFORMECONTEO2."\">INFORME CONTEO 2(DIFERENCIAS)</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";
      /*$this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "                       <td   align=\"center\" class=\"normal_10AN\">\n";
      $this->salida .= "                          <a  title=\"VALIDACION DE JEFES\" class=\"label_error\" href=\"".$VALIDARJEFE."\">VALIDACION DE JEFES</a>\n";
      $this->salida .= "                       </td>";
      $this->salida .= "                    </tr>";*/
      $this->salida .= "                 </table>";
      $this->salida .= "             </form>";        
      
      $Exit = ModuloGetURL('app','InvTomaFisica','user','SelectEmpresa');
      $this->salida .= " <form name=\"volver\" action=\"".$Exit."\" method=\"post\">\n";//".$this->action[0]."
      $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
      $this->salida .= "    <tr>\n";
      $this->salida .= "       <td align=\"center\" colspan='7'>\n";
      $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
      $this->salida .= "       </td>\n";  
      $this->salida .= "    </tr>\n"; 
      $this->salida .= "  </table>\n"; 
      $this->salida .= " </form>\n"; 
      $this->salida .= ThemeCerrarTabla();
      return true;
 }       
    

/***********************************************************************
*Crear Toma fisica
************************************************************************/
function CrearTomaFisica()
    {
        $path = SessionGetVar("rutaImagenes");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $consulta= new TomaFisicaSQL();
        $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
        $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
        $this->SetXajax(array("CrearTomaxFisicas"),$file);
        $this->salida .= ThemeAbrirTabla("CREACION DE TOMAS FISICAS");
        $accion1=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisica');
        $this->salida .="    <div id='error_en_mov' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "                 <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                       <td width=\"15%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "                          EMPRESA";
        $this->salida .= "                       </td>\n";
        $empresa_nom=$consulta->ColocarEmpresa(SessionGetVar("EMPRESA"));
        $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
        $this->salida .= "                        ".$empresa_nom[0]['razon_social'];
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                       <td width=\"30%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "                          USUARIO ID";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
        $this->salida .= "                        ".UserGetUID();
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td width=\"30%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "                          NOMBRE USUARIO";
        $this->salida .= "                       </td>\n";
        $usuario_idx=$consulta->BuscarUsuario(UserGetUID());
        $this->salida .= "                       <td align=\"left\">\n";
        $this->salida .= "                        ".$usuario_idx[0]['nombre'];
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                 </table>";
        $this->salida .= "                 <br>";
        $tomas=$consulta->ListaBodegasProductos(SessionGetVar("EMPRESA"));
        //var_dump($tomas);
        

        $this->salida .="                  <div id='refresh'>";
        if(!EMPTY($tomas))
        {
            $this->salida .= "          <form id=\"Nuevas_Tomas\" name=\"Nuevas_Tomas\" action=\"#\" method=\"post\">";
            $this->salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       <td rowspan='2' width=\"5%\" align=\"center\">\n";
            $this->salida .= "                       <a title='CENTRO DE UTILIDAD'>";
            $this->salida .= "                         C.U";
            $this->salida .= "                       </a>";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td rowspan='2' width=\"5%\" align=\"center\">\n";
            $this->salida .= "                          BODEGA ID";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td rowspan='2' width=\"30%\" align=\"center\">\n";
            $this->salida .= "                          BODEGA";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td colspan='2' width=\"40%\" align=\"center\">\n";
            $this->salida .= "                         <a title='CANTIDAD PRODUCTOS'>\n";
            $this->salida .= "                          CANTIDAD PRODUCTOS";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td rowspan='2' width=\"35%\" align=\"center\">\n";
            $this->salida .= "                          DESCRIPCION TOMA FISICA";
            $this->salida .= "                       </td>\n";
//             $this->salida .= "                       <td rowspan='2' COLSPAN='2' width=\"15%\" align=\"center\">\n";
//             $this->salida .= "                         <a title='ADICIONALES'>\n";
//             $this->salida .= "                          DATOS ADICIONALES";
//             $this->salida .= "                         </a>\n";
//             $this->salida .= "                       </td>\n";
//             $this->salida .= "                       <td width=\"25%\" align=\"center\">\n";
//             $this->salida .= "                         <a title='TOTAL PRODUCTOS'>\n";
//             $this->salida .= "                          TOTAL PRODUCTOS";
//             $this->salida .= "                         </a>\n";
//             $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
            $this->salida .= "                         <a title='PRODUCTOS CON EXISTENCIA'>\n";
            $this->salida .= "                          CON EXISTENCIA";
            $this->salida .= "                         </a>\n";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
            $this->salida .= "                         <a title='TOTAL PRODUCTOS'>\n";
            $this->salida .= "                          TOTAL PRODUCTOS";
            $this->salida .= "                         </a>\n";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            $reporte = new GetReports();                                                                  //$toma_fisica,$empresa_id,$centro_utilidad,$bodega
            for($i=0;$i<count($tomas);$i++)
            {
                $tr="linea".$i;
                $i_radio="este".$i;
                $parahiden="turn_off".$i;
                $OBSERVACION_TOMA="OBSERVACION_TOMA[".$tomas[$i]['bodega']."]";
                $this->salida .= "                    <tr id='".$tr."' class=\"modulo_list_claro\">\n";
                $this->salida .= "                       <td align=\"center\">\n";
                $this->salida .= "                         <a title='".$tomas[$i]['nom_centro']."'>\n";
                $this->salida .= "                           ".$tomas[$i]['centro_utilidad'];
                $this->salida .= "                         </a>\n";
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                $this->salida .= "                       ".$tomas[$i]['bodega'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                $this->salida .= "                       ".$tomas[$i]['descripcion'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                      <td align=\"left\">\n";
                $this->salida .= "                        <input type=\"hidden\" name=\"deactiva[".$i."]\" id=\"deactiva[".$i."]\" value=\"nada\">\n";
                $this->salida .= "                        <input type=\"radio\" name=\"con_existencia[".$tomas[$i]['empresa_id']."_".$tomas[$i]['centro_utilidad']."_".$tomas[$i]['bodega']."]\" id=\"con_existencia[".$tomas[$i]['empresa_id']."_".$tomas[$i]['centro_utilidad']."_".$tomas[$i]['bodega']."]\" value=\"1\" onclick=\"PintarDeRojo('".$tr."',this,document.getElementById('deactiva[".$i."]'),'".$tomas[$i]['bodega']."');\">\n";
                $this->salida .= "                         ".$tomas[$i]['num_productos_existencia'];
                $this->salida .= "                      </td>\n";
                $this->salida .= "                       <td align=\"left\">\n";
                $this->salida .= "                        <input type=\"radio\" name=\"con_existencia[".$tomas[$i]['empresa_id']."_".$tomas[$i]['centro_utilidad']."_".$tomas[$i]['bodega']."]\" id=\"con_existencia[".$tomas[$i]['empresa_id']."_".$tomas[$i]['centro_utilidad']."_".$tomas[$i]['bodega']."]\" value=\"0\" onclick=\"PintarDeRojo('".$tr."',this,document.getElementById('deactiva[".$i."]'),'".$tomas[$i]['bodega']."');\">\n";
                $this->salida .= "                       ".$tomas[$i]['num_productos'];
                $this->salida .= "                       </td>\n";
//                 $this->salida .= "                       <td class=\"normal_10AN\" align=\"center\" colspan='2'>\n";
//                 $this->salida .= "                         CONTEOS <input type=\"text\" class=\"input-text\" size=\"2\" Maxlength=\"2\" value=\"\">\n";
//                 $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
                $this->salida .= "                         <input type=\"text\" name='".$OBSERVACION_TOMA."' id='".$OBSERVACION_TOMA."' class=\"input-text\" size=\"55\" Maxlength=\"55\" disabled>\n";
                $this->salida .= "                       </td>\n";
// $this->salida .= "                       <td width='3%' align=\enter\">\n";
//                 
//                 $mostrar = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosTomaFisica',array("datos"=>array("toma_fisica"=>$tomas[$i]['toma_fisica_id'],"empresa_id"=>$tomas[$i]['empresa_id'],"centro_utilidad"=>$tomas[$i]['centro_utilidad'],"bodega"=>$tomas[$i]['bodega'])),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
//                 $funcion1 = $reporte->GetJavaFunction();
//                 $this->salida .= $mostrar;
//                 //$reporte = "app_modules/UV-Afiliaciones/reports/html/ReportePorUsuario.report.php?".URLRequest($fecha_ini);
//                 $this->salida .= "              <a href=\"javascript:$funcion1\" class=\"label_error\"><sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS DE LA TOMA FISICA ".$tomas[$i]['toma_fisica_id']."\"></sub>&nbsp;</a>\n";
//                 $this->salida .= "                       </td>\n";
//                 $this->salida .= "                       <td width='3%' align=\"center\">\n";
//                 if(!empty($tomas[$i]['fecha_inicio']))
//                 {
//                     $CONTEO=ModuloGetURL('app','InvTomaFisica','user','FormaAjusteConteos',array('toma_id'=>$tomas[$i]['toma_fisica_id'],'registros'=>$tomas[$i]['cantidad_reg'],'bodegax'=>$tomas[$i]['nom_bodega']));
//                     $this->salida .= "                         <a title='TOMA FISICA ".$tomas[$i]['descripcion']."' href=\"".$CONTEO."\">";
//                     $this->salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
//                     $this->salida .= "                         </a>\n";
//                 }
//                 else
//                 {
//                     $this->salida .= "       &nbsp;";
//                 }
//                 $this->salida .= "                       </td>\n";
//                 $this->salida .= "                       <td width='3%' align=\"center\" id='".$td_fecha."'>\n";
//                 if(empty($tomas[$i]['fecha_inicio']))
//                 {
//                     $this->salida .= "                         <a title='ACTIVAR TOMA FISICA' href=\"javascript:ActivarTomaFisica('".$td_fecha."','".$tomas[$i]['toma_fisica_id']."');\">";
//                     $this->salida .= "                          <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
//                     $this->salida .= "                         </a>\n";
//                 }
//                 else
//                 {
//                     $this->salida .= "                         <a title='FECHA DE ACTIVACION: ".substr($tomas[$i]['fecha_inicio'],0,19)."'>";
//                     $this->salida .= "                          <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
//                     $this->salida .= "                         </a>\n";
//                 }
//                 $this->salida .= "                       </td>\n";
                $this->salida .= "                    </tr>\n";
            }
                 $this->salida .= "      </table>";
                
                $this->salida .= "  <table align=\"center\" width=\"80%\" class=\"modulo_table_list\">\n";
                $this->salida .= "    <tr class=\"modulo_table_list_title\">\n";
                $this->salida .= "       <td align=\"center\" colspan='2'>\n";
                $this->salida .= "         NUMERO CONTEOS \n";
                $this->salida .= "       </td>\n";
                $this->salida .= "       <td align=\"center\" colspan='2'>\n";
                $this->salida .= "        ORGANIZAR LAS ETIQUETAS POR : ";
                $this->salida .= "       </td>\n";
                $this->salida .= "    </tr>\n";
                $this->salida .= "    <tr class=\"modulo_list_claro\">\n";
                $this->salida .= "       <td align=\"center\" colspan='2'>\n";
                //$this->salida .= "         <input type=\"text\" name='conteos' class=\"input-text\" size=\"2\" Maxlength=\"2\" value=\"\">\n";
                $this->salida .= "                       <select id=\"conteos\" name=\"conteos\" class=\"select\" onchange=\"\">";
                $this->salida .= "                           <option value=\"1\" SELECTED>1</option> \n";
                $this->salida .= "                           <option value=\"2\">2</option> \n";
                $this->salida .= "                           <option value=\"3\">3</option> \n";
                $this->salida .= "                       </select>\n";
                $this->salida .= "       </td>\n";
                $this->salida .= "       <td align=\"center\" colspan='2'>\n";
                $this->salida .= "         <input type=\"radio\" id=\"orderby\" name=\"orderby[]\" class=\"input-text\" size=\"2\" Maxlength=\"2\" value=\"codigo\"> CODIGO\n";
                $this->salida .= "         <input type=\"radio\" id=\"orderby\" name=\"orderby[]\" class=\"input-text\" size=\"2\" Maxlength=\"2\" value=\"descripcion\" checked> DESCRIPCION\n";
                $this->salida .= "         <input type=\"radio\" id=\"orderby\" name=\"orderby[]\" class=\"input-text\" size=\"2\" Maxlength=\"2\" value=\"laboratorio\" checked> CLASE (Clasificacion)\n";
                $this->salida .= "       </td>\n";
                $this->salida .= "    </tr>\n";
                $this->salida .= " </form>";
                $this->salida .= "    <tr class=\"modulo_list_claro\">\n";
                $this->salida .= "       <td align=\"center\" colspan='6'>\n";
                $this->salida .= "          <input type=\"button\" class=\"input-submit\" value=\"Crear Tomas Fisicas\" onclick=\"xajax_CrearTomaxFisicas(xajax.getFormValues('Nuevas_Tomas'));\">\n";
                $this->salida .= "       </td>\n";
                $this->salida .= "    </tr>\n";
                $this->salida .= "  </table>\n";
                
        }  
        $this->salida .= "            </div>\n";
        //$this->salida .= "               <br>";
        $this->salida .= "            <div id='error_en_toma'>\n";
        $this->salida .= "            </div>\n";
        $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','Admon');
        $this->salida .= " <form name=\"VolverMenuAdmin\" action=\"".$MENUMOV."\" method=\"post\">\n";
        $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "       <td align=\"center\" colspan='7'>\n";
        $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "       </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= " </form>\n";
        $this->salida.="<script language=\"javaScript\">
            function mOvr(src,clrOver)
            {
                src.style.background = clrOver;
            }
            function mOut(src,clrIn)
            {
                src.style.background = clrIn;
            }
            </script>";
        $this->salida .= ThemeCerrarTabla();
        return true;
 }


/**************************************************************************
* forma para la administracion de usuARIO DE LAS TOMAS FISICAS REGISTRADAS
****************************************************************************/

  
    function Admin_Usuarios()
    {
        $path = SessionGetVar("rutaImagenes");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $consulta= new TomaFisicaSQL();
        $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
        $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
        $this->SetXajax(array("BuscarUsuSys"),$file);
        $this->salida .= ThemeAbrirTabla("ADMINISTRACION DE USUARIOS PARA LA TOMA FISICA: ".$_REQUEST['toma_id']." DE LA BODEGA: ".$_REQUEST['bodegax']);
        $accion1=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisica');
        $this->salida .="    <div id='error_en_mov' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td width=\"10%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                          EMPRESA";
      $this->salida .= "                       </td>\n";
      $nombre=$consulta->ColocarEmpresa(SessionGetVar("EMPRESA"));
      $this->salida .= "                       <td width=\"25%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ".$nombre[0]['razon_social'];
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td width=\"10%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                          BODEGA";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td width=\"25%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                          ".$_REQUEST['bodegax'];
      $this->salida .= "                       </td>\n"; 
      $this->salida .= "                       <td width=\"10%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                          USUARIO ";
      $this->salida .= "                       </td>\n";
      $usuario_idx=$consulta->BuscarUsuario(UserGetUID());
      $this->salida .= "                       <td width=\"20%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ".$usuario_idx[0]['nombre'];
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         TOMA FISICA";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ".$_REQUEST['toma_id'];
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                          FECHA";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                          ".date("Y-m-d  H:i")."";
      $this->salida .= "                       </td>\n"; 
      $this->salida .= "                       <td  align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                          # PRODUCTOS";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ".$_REQUEST['registros'];
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                 </table>";
        $this->salida .= "                 <br>";
        $usuarios_conteo=$consulta->Sacar_Usuarios_Toma_Conteo($_REQUEST['toma_id']);
        //var_dump($usuarios_conteo);
        $this->salida .="                  <div id='error_en_toma'>";
        $this->salida .="                  </div>";
        
///////////////////////////////////////////////////////////////////////////////
        //$CONTEO=ModuloGetURL('app','InvTomaFisica','user','Admin_Usuarios',array('toma_id'=>$tomas[$i]['toma_fisica_id'],'bodegax'=>$tomas[$i]['nom_bodega'],'registros'=>$tomas[$i]['cantidad_reg']));
        $NUEVO_USU1=ModuloGetURL('app','InvTomaFisica','user','Lista_System_Usuarios',array('toma_fisica_id'=>$_REQUEST['toma_id'],'bodegax'=>$_REQUEST['bodegax'],'registros'=>$_REQUEST['registros'],'Conteo'=>"SI"));
        $this->salida .= "                 <table width=\"100%\" align=\"center\">\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                       <td align=\"center\" class='label_error'>\n";
        $this->salida .= "                         <a title='ADICIONAR NUEVO USUARIO PARA CONTEO' href=\"".$NUEVO_USU1."\">";
        $this->salida .= "                          <sub><img src=\"".$path."/images/activo.gif\" border=\"0\" width=\"14\" height=\"14\"> NUEVO USUARIO PARA CONTEO</sub>\n";
        $this->salida .= "                         </a>\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                    </tr>";
        $this->salida .= "                 </table>";
        $this->salida .="                  <div id='refresh'>";
        if(!EMPTY($usuarios_conteo))
        {
        
            $this->salida .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       <td colspan='4' align=\"center\">\n";
            $this->salida .= "                          USUARIOS CON PERMISOS DE CONTEO";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       <td width=\"15%\" align=\"center\">\n";
            $this->salida .= "                          USUARIO ID";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"20%\" align=\"center\">\n";
            $this->salida .= "                          LOGIN";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"55%\" align=\"center\">\n";
            $this->salida .= "                          NOMBRE DEL USUARIO";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
            $this->salida .= "                          ACCIONES";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            for($i=0;$i<count($usuarios_conteo);$i++)
            {
                $td_fecha="este".$i;
                $this->salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $this->salida .= "                       <td align=\"center\">\n";
                $this->salida .= "                       ".$usuarios_conteo[$i]['usuario_id'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                $this->salida .= "                       ".$usuarios_conteo[$i]['usuario'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                      <td align=\"left\">\n";
                $this->salida .= "                       ".$usuarios_conteo[$i]['nombre'];
                $this->salida .= "                      </td>\n";
                $this->salida .= "                      <td align=\"center\">\n";
                    $CONTEO=ModuloGetURL('app','InvTomaFisica','user','Admin_Usuarios',array('toma_id'=>$tomas[$i]['toma_fisica_id'],'registros'=>$tomas[$i]['cantidad_reg'],'bodegax'=>$tomas[$i]['nom_bodega']));
                    $this->salida .= "                         <a title='ELIMINAR EL PERMISO AL USUARIO ".$usuarios_conteo[$i]['usuario']."' href=\"".$CONTEO."\">";
                    $this->salida .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $this->salida .= "                         </a>\n";
                $this->salida .= "                       </td>\n";
                $this->salida .= "                    </tr>\n";
            }
            $this->salida .= "               </table>";
        }  
        $this->salida .= "            </div>\n";
        $this->salida .= "               <br>";
        ///////////////////////////////////////////////////////////////

        $usuarios_validacion=$consulta->Sacar_Usuarios_Toma_Validacion($_REQUEST['toma_id']);
        //var_dump($usuarios_validacion);
            //$NUEVO_USU1=ModuloGetURL('app','InvTomaFisica','user','Lista_System_Usuarios',array('toma_fisica_id'=>$_REQUEST['toma_id'],'bodegax'=>$_REQUEST['bodegax'],'registros'=>$_REQUEST['registros'],'Conteo'=>"SI"));
        $NUEVO_USU=ModuloGetURL('app','InvTomaFisica','user','Lista_System_Usuarios',array('toma_fisica_id'=>$_REQUEST['toma_id'],'bodegax'=>$_REQUEST['bodegax'],'registros'=>$_REQUEST['registros'],'Conteo'=>"NO"));
        $this->salida .= "                 <table width=\"100%\" align=\"center\">\n";
        $this->salida .= "                   <tr>\n";
        $this->salida .= "                       <td align=\"center\" class='label_error'>\n";
        $this->salida .= "                         <a title='ADICIONAR NUEVO USUARIO PARA VALIDACION ' href=\"".$NUEVO_USU."\">";
        $this->salida .= "                          <sub><img src=\"".$path."/images/activo.gif\" border=\"0\" width=\"14\" height=\"14\"> NUEVO USUARIO PARA VALIDACION</sub>\n";
        $this->salida .= "                         </a>\n";
        $this->salida .= "                       </td>";
        $this->salida .= "                    </tr>";
        $this->salida .= "                 </table>";
        $this->salida .="                  <div id='refresh2'>";
        if(!EMPTY($usuarios_validacion))
        {
            $this->salida .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       <td colspan='4' align=\"center\">\n";
            $this->salida .= "                          USUARIOS CON PERMISOS DE VALIDACION";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "                       <td width=\"15%\" align=\"center\">\n";
            $this->salida .= "                          USUARIO ID";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"20%\" align=\"center\">\n";
            $this->salida .= "                          LOGIN";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"55%\" align=\"center\">\n";
            $this->salida .= "                          NOMBRE DEL USUARIO";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
            $this->salida .= "                          ACCIONES";
            $this->salida .= "                       </td>\n";
            $this->salida .= "                    </tr>\n";
            for($i=0;$i<count($usuarios_validacion);$i++)
            {
                $td_fecha="este".$i;
                $this->salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $this->salida .= "                       <td align=\"center\">\n";
                $this->salida .= "                       ".$usuarios_validacion[$i]['usuario_id'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                $this->salida .= "                       ".$usuarios_validacion[$i]['usuario'];
                $this->salida .= "                       </td>\n";
                $this->salida .= "                      <td align=\"left\">\n";
                $this->salida .= "                       ".$usuarios_validacion[$i]['nombre'];
                $this->salida .= "                      </td>\n";
                $this->salida .= "                      <td align=\"center\">\n";
                    $CONTEO=ModuloGetURL('app','InvTomaFisica','user','Admin_Usuarios',array('toma_id'=>$tomas[$i]['toma_fisica_id'],'registros'=>$tomas[$i]['cantidad_reg'],'bodegax'=>$tomas[$i]['nom_bodega']));
                    $this->salida .= "                         <a title='ELIMINAR EL PERMISO AL USUARIO ".$usuarios_conteo[$i]['usuario']."' href=\"".$CONTEO."\">";
                    $this->salida .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $this->salida .= "                         </a>\n";
                $this->salida .= "                       </td>\n";
                $this->salida .= "                    </tr>\n";
            }
            $this->salida .= "               </table>";
        }      
        $this->salida .= "               </div>";
        $this->salida .= "               </br>";
        ////////////////////////////////////////////////////////////////

        $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','Admon');
        $this->salida .= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
        $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "       <td align=\"center\" colspan='7'>\n";
        $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "       </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= " </form>\n";
        $this->salida.="<script language=\"javaScript\">
            function mOvr(src,clrOver)
            {
                src.style.background = clrOver;
            }
            function mOut(src,clrIn)
            {
                src.style.background = clrIn;
            }
            </script>";
        $this->salida .= ThemeCerrarTabla();
//////////////////////////////////////////////////////////////////////////////

        return true;
 }





/**************************************************************************
* forma para la administracion de usuARIO DE LAS TOMAS FISICAS REGISTRADAS
****************************************************************************/

  
    function Lista_System_Usuarios()
    {
        $path = SessionGetVar("rutaImagenes");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $consulta= new TomaFisicaSQL();
        $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
        $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
        $this->SetXajax(array("BuscarUsuSysConteo","BuscarUsuSysValidacion","AdicionarUserConteoBD","AdicionarUserValidacionBD"),$file,"ISO-8859-1");
        $this->salida .= ThemeAbrirTabla("ADMINISTRACION DE USUARIOS PARA LA TOMA FISICA ");
        $accion1=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisica');
        $this->salida .="    <div id='error_en_mov' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "                 <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                       <td width=\"15%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "                          EMPRESA";
        $this->salida .= "                       </td>\n";
        $empresa_nom=$consulta->ColocarEmpresa(SessionGetVar("EMPRESA"));
        $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
        $this->salida .= "                        ".$empresa_nom[0]['razon_social'];
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                    <tr>\n";
        $this->salida .= "                       <td width=\"30%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "                          USUARIO ID";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
        $this->salida .= "                        ".UserGetUID();
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "                       <td width=\"30%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "                          NOMBRE USUARIO";
        $this->salida .= "                       </td>\n";
        $usuario_idx=$consulta->BuscarUsuario(UserGetUID());
        $this->salida .= "                       <td align=\"left\">\n";
        $this->salida .= "                        ".$usuario_idx[0]['nombre'];
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $this->salida .= "                 </table>";
        $this->salida .= "                 <br>";
        //$tomas=$consulta->SacarAdmonTomaFisica(UserGetUID());
        //var_dump($tomas);
        $this->salida .="                  <div id='refresh'>";
        $javaC = "<script>\n";
        $javaC .= "var contenedor1=''\n";
        $javaC .= "   var titulo1=''\n";
        $javaC .= "   var hiZ = 2;\n";
        $javaC .= "   var DatosFactor = new Array();\n";
        $javaC .= "   var EnvioFactor = new Array();\n";
        $javaC .= "   function IniciarB3(tit)\n";
        $javaC .= "   {\n";
        $javaC .= "       contenedor1 = 'ContenedorB3';\n";
        $javaC .= "       titulo1 = 'tituloB3';\n";
        $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
        $javaC.= "        Capa = xGetElementById(contenedor1);\n";
        $javaC .= "       xResizeTo(Capa, 360, 160);\n";
        $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-0);\n";
        $javaC .= "       ele = xGetElementById(titulo1);\n";
        $javaC .= "       xResizeTo(ele, 340, 20);\n";
        $javaC .= "       xMoveTo(ele, 0, 0);\n";
        $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
        $javaC .= "       ele = xGetElementById('cerrarB3');\n";
        $javaC .= "       xResizeTo(ele, 20, 20);\n";
        $javaC .= "       xMoveTo(ele, 340, 0);\n";
        $javaC .= "   }\n";
        $javaC.= "</script>\n";
        $salida.= $javaC;
        $javaC1.= "<script>\n";
        $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     window.status = '';\n";
        $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
        $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
        $javaC1 .= "     ele.myTotalMX = 0;\n";
        $javaC1 .= "     ele.myTotalMY = 0;\n";
        $javaC1 .= "   }\n";
        $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "     if (ele.id == titulo1) {\n";
        $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
        $javaC1 .= "     }\n";
        $javaC1 .= "     else {\n";
        $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
        $javaC1 .= "     }  \n";
        $javaC1 .= "     ele.myTotalMX += mdx;\n";
        $javaC1 .= "     ele.myTotalMY += mdy;\n";
        $javaC1 .= "   }\n";
        $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
        $javaC1 .= "   {\n";
        $javaC1 .= "   }\n";
        $javaC1.= "function MostrarCapa(Elemento)\n";
        $javaC1.= "{\n";
        $javaC1.= "    capita = xGetElementById(Elemento);\n";
        $javaC1.= "    capita.style.display = \"\";\n";
        $javaC1.= "}\n";
        $javaC1.= "function Cerrar(Elemento)\n";
        $javaC1.= "{\n";
        $javaC1.= "    capita = xGetElementById(Elemento);\n";
        $javaC1.= "    capita.style.display = \"none\";\n";
        $javaC1.= "}\n";
        $javaC1.= "</script>\n";
        $salida.= $javaC1;
        $salida.="
        <script language=\"javaScript\">
        function mOvr(src,clrOver)
                    {
                    src.style.background = clrOver;
                    }

                    function mOut(src,clrIn)
                    {
                    src.style.background = clrIn;
                    }
        </script>";
        $html .=$salida;
        $html .= " <div id='ContenedorB3' class='d2Container' style=\"display:none;\">";
        $html .= "    <div id='tituloB3' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
        $html .= "    <div id='cerrarB3' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorB3');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $html .= "    <div id='errorB3' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $html .= "    <div id='ContenidoB3'  class='d2Content' style='z-index:10;'>\n";
        $html .= "    </div>\n";
        $html .= " </div>\n";
        $html .="    <div id='refresh'>";
        $html .= "        <form name=\"busqueda_usu_sys\" id=\"busqueda_usu_sys\" action=\"#\" method=\"post\">";
        $html .= "          <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "              <tr>\n";
        $html .= "                  <td colspan='3' align=\"center\" class=\"modulo_table_list_title\" ><b style=\"color:#ffffff\">BUSCADOR DE USUARIOS</td>\n";
        $html .= "              </tr>\n";
        $html .= "              <tr>\n";
        $html .= "                  <td width='25%' class=\"modulo_list_claro\" align=\"left\">\n";
        $html .= "                  TIPO <select name=\"tipo_u\" id=\"tipo_u\" class=\"select\" onchange=\"GetPerfiles1(this.value);\">\n";
        $html .= "                         <option value=\"\">SELECCIONAR</option>\n";
        $html .= "                         <option value=\"usuario_id\">ID</option>\n";
        $html .= "                         <option value=\"usuario\">LOGIN</option>\n";
        $html .= "                         <option value=\"nombre\">NOMBRE</option>\n";
        $html .= "                       </select>";
        $html .= "                  </td>\n";
        $html .= "                  <td width='65%' id='descripcione' class=\"modulo_list_claro\" align=\"left\">\n";
        $html .= "                    <div id='descrip_2' style=\"display:block;\">";
        $html .= "                     DESCRIPCION\n";
        $html .= "                     <input type=\"text\" class=\"input-text\" name=\"valor\" id=\"valor\" size=\"40\" value=\"\" disabled>\n";
        $html .= "                    </div>";
        $html .= "                    <div id='perfilix' style=\"display:none;\">";
        $html .= "                     <select name=\"perfil\" id=\"tipo_id\" class=\"select\">";
        $html .= "                       <option value=\"\">--SELECCIONAR--</option>\n";
        foreach($perfiles as $key => $valor)
        {
            $html .= "                     <option value=\"".$valor['perfil_id']."\">".$valor['descripcion_perfil']."</option>\n";
        }
        $html .= "                     </select>\n";
        $html .= "                   </div>";
        $html .= "                  </td>\n";
        if($_REQUEST['Conteo']=="SI")
        {
            $html .= "                  <td width='10%' class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                     <input type=\"button\" class=\"input-submit\" name=\"buscar_usu\" id=\"buscar_usu\" value=\"BUSCAR\" onclick=\"xajax_BuscarUsuSysConteo(xajax.getFormValues('busqueda_usu_sys'),1,0,'".$_REQUEST['toma_fisica_id']."');\">\n";
            $html .= "                  </td>\n";
        }
        elseif($_REQUEST['Conteo']=="NO")
        {
            $html .= "                  <td width='10%' class=\"modulo_list_claro\" align=\"center\">\n";
            $html .= "                     <input type=\"button\" class=\"input-submit\" name=\"buscar_usu\" id=\"buscar_usu\" value=\"BUSCAR\" onclick=\"xajax_BuscarUsuSysValidacion(xajax.getFormValues('busqueda_usu_sys'),1,0,'".$_REQUEST['toma_fisica_id']."');\">\n";
            $html .= "                  </td>\n";
        }
        $html .= "              </tr>\n";
        $html .= "          </table>\n";
        $html .= "        </form>";
        $html .= "<br>";
        $html .="    <div id='error_usuarios2' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $html .= "<table width='100%' align='center'>";
        $html .= "  <div id='resultado_usuarios_sys'>";
        $html .= "  </div>";
        $html .= "</table>";
        $html .= "<br>";
        if($_REQUEST['Conteo']=="SI")
        {
            $html.="<script language=\"javaScript\">
                        xajax_BuscarUsuSysConteo(xajax.getFormValues('busqueda_usu_sys'),1,0,'".$_REQUEST['toma_fisica_id']."');

                        function Actualizar_Lista_Usuarios()
                        {
                            xajax_BuscarUsuSysConteo(xajax.getFormValues('busqueda_usu_sys'),1,0,'".$_REQUEST['toma_fisica_id']."');
                        }
                    </script>";
        }
        elseif($_REQUEST['Conteo']=="NO")
        {
            $html.="<script language=\"javaScript\">
                        xajax_BuscarUsuSysValidacion(xajax.getFormValues('busqueda_usu_sys'),1,0,'".$_REQUEST['toma_fisica_id']."');

                        function Actualizar_Lista_Usuarios()
                        {
                            xajax_BuscarUsuSysValidacion(xajax.getFormValues('busqueda_usu_sys'),1,0,'".$_REQUEST['toma_fisica_id']."');
                        }
                    </script>";

        }
        $this->salida .=$html;
        $this->salida .= "            </div>\n";
        $this->salida .= "               <br>";
        //$NUEVO_USU=ModuloGetURL('app','InvTomaFisica','user','Lista_System_Usuarios',array('toma_fisica_id'=>$_REQUEST['toma_id'],'bodegax'=>$tomas[$i]['nom_bodega'],'registros'=>$tomas[$i]['cantidad_reg'],'Conteo'=>"NO"));
        $CONTEO=ModuloGetURL('app','InvTomaFisica','user','Admin_Usuarios',array('toma_id'=>$_REQUEST['toma_fisica_id'],'registros'=>$_REQUEST['registros'],'bodegax'=>$_REQUEST['bodegax']));
        $this->salida .= " <form name=\"volver\" action=\"".$CONTEO."\" method=\"post\">\n";
        $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "       <td align=\"center\" colspan='7'>\n";
        $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "       </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= " </form>\n";
        $this->salida.="<script language=\"javaScript\">
            function mOvr(src,clrOver)
            {
                src.style.background = clrOver;
            }
            function mOut(src,clrIn)
            {
                src.style.background = clrIn;
            }
            </script>";
        $this->salida .= ThemeCerrarTabla();
        return true;
 }




/*****************************************************************
*Administracion toma fisica
****************************************************************/

    function Admon()
    {
        $path = SessionGetVar("rutaImagenes");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserDrag");
        $this->IncludeJS("CrossBrowserEvent");
        $consulta= new TomaFisicaSQL();
        $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
        $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
        $this->SetXajax(array("ActivarTomaFisica","ListaProductos","AdicionarLoteFV"),$file);
        $this->salida .= ThemeAbrirTabla("ADMINISTRACION DE TOMAS FISICA");
        $accion1=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisica');
        $this->salida .= "<script language='javascript'>\n";
        $this->salida .= "  var rem=\"\";\n";
        $this->salida .= "  function abreVentana(url2)\n";
        $this->salida .= "  {\n";
        $this->salida .= "    var width=\"400\"\n";
        $this->salida .= "    var height=\"300\"\n";
        $this->salida .= "    var winX=Math.round(screen.width/2)-(width/2);\n";
        $this->salida .= "    var winY=Math.round(screen.height/2)-(height/2);\n";
        $this->salida .= "    var nombre=\"Printer_Mananger\";\n";
        $this->salida .= "    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
        $this->salida .= "    window.open(url2, nombre, str).focus();\n";
        $this->salida .= "  };\n";
        $this->salida .= "</script>\n";
        $this->salida .=" <div id='error_en_mov' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
        $this->salida .= "  <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "       <td width=\"15%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "          EMPRESA";
        $this->salida .= "       </td>\n";
        $empresa_nom=$consulta->ColocarEmpresa(SessionGetVar("EMPRESA"));
        $this->salida .= "       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
        $this->salida .= "           ".$empresa_nom[0]['razon_social'];
        $this->salida .= "       </td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "        <td width=\"30%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "            USUARIO ID";
        $this->salida .= "        </td>\n";
        $this->salida .= "        <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
        $this->salida .= "            ".UserGetUID();
        $this->salida .= "        </td>\n";
        $this->salida .= "       </tr>\n";
        $this->salida .= "       <tr class=\"modulo_list_claro\">\n";
        $this->salida .= "         <td width=\"30%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
        $this->salida .= "             NOMBRE USUARIO";
        $this->salida .= "         </td>\n";
        $usuario_idx=$consulta->BuscarUsuario(UserGetUID());
        $this->salida .= "         <td align=\"left\">\n";
        $this->salida .= "             ".$usuario_idx[0]['nombre'];
        $this->salida .= "         </td>\n";
        $this->salida .= "        </tr>\n";
        $this->salida .= "  </table>";
        $this->salida .= " <br>";
        $tomas=$consulta->SacarAdmonTomaFisica(UserGetUID(),$empresa_nom[0]['empresa_id']);
        //var_dump($tomas);
        $CREAR_TOMA=ModuloGetURL('app','InvTomaFisica','user','CrearTomaFisica');
        $this->salida .= " <table width=\"100%\" align=\"center\">\n";
        $this->salida .= "  <tr>\n";
        $this->salida .= "   <td   align=\"right\">\n";
        $this->salida .= "     <a  title=\"PARAMETRIZACION TOMA FISICA\" class=\"label_error\" href=\"".$CREAR_TOMA."\">CREAR TOMA FISICA</a>\n";
        $this->salida .= "   </td>";
        $this->salida .= "  </tr>";
        $this->salida .= " </table>";
        $this->salida .=" <div id='error_en_toma'>";
        $this->salida .=" </div>";
        $this->salida .=" <div id='refresh'>";
        if(!EMPTY($tomas))
        {
            $this->salida .= " <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "    <td width=\"10%\" align=\"center\">\n";
            $this->salida .= "      TOMA FISICA ID";
            $this->salida .= "    </td>\n";
            $this->salida .= "    <td width=\"20%\" align=\"center\">\n";
            $this->salida .= "      DESCRIPCION";
            $this->salida .= "    </td>\n";
            $this->salida .= "    <td width=\"7%\" align=\"center\">\n";
            $this->salida .= "     <a title='CENTRO DE UTILIDAD'>\n";
            $this->salida .= "       CEN_UTIL";
            $this->salida .= "    </td>\n";
            $this->salida .= "    <td width=\"7%\" align=\"center\">\n";
            $this->salida .= "     <a title='NUMERO DE CONTEOS'>\n";
            $this->salida .= "       CONTEOS";
            $this->salida .= "     </a>\n";
            $this->salida .= "    </td>\n";
            $this->salida .= "    <td width=\"20%\" align=\"center\">\n";
            $this->salida .= "     <a title='BODEGA ID'>\n";
            $this->salida .= "        BODEGA";
            $this->salida .= "     <a>";
            $this->salida .= "    </td>\n";
            $this->salida .= "    <td width=\"7%\" align=\"center\">\n";
            $this->salida .= "      <a title='CANTIDAD DE PRODUCTOS'>PRODUCTOS\n";
            $this->salida .= "    </td>\n";
            $this->salida .= "    <td width=\"17%\" align=\"center\">\n";
            $this->salida .= "        OBSERVACION";
            $this->salida .= "    </td>\n";
            $this->salida .= "    <td colspan='6' width=\"12%\" align=\"center\">\n";
            $this->salida .= "         ACCIONES";
            $this->salida .= "     </td>\n";
            $this->salida .= "   </tr>\n";
            $reporte = new GetReports();                                                                  //$toma_fisica,$empresa_id,$centro_utilidad,$bodega
            for($i=0;$i<count($tomas);$i++)
            {
                $td_fecha="este".$i;
                $this->salida .= " <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $this->salida .= "   <td align=\"center\">\n";
                $this->salida .= "     ".$tomas[$i]['toma_fisica_id'];
                $this->salida .= "   </td>\n";
                $this->salida .= "   <td class=\"normal_10AN\" align=\"left\">\n";
                $this->salida .= "      ".$tomas[$i]['descripcion'];
                $this->salida .= "   </td>\n";
                $this->salida .= "   <td align=\"left\">\n";
                $this->salida .= "      ".$tomas[$i]['centro_utilidad'];
                $this->salida .= "   </td>\n";
                $this->salida .= "   <td align=\"left\">\n";
                $this->salida .= "      ".$tomas[$i]['numero_conteos'];
                $this->salida .= "   </td>\n";
                $this->salida .= "   <td align=\"left\">\n";
                $this->salida .= "      ".$tomas[$i]['nom_bodega'];
                $this->salida .= "   </td>\n";
                $this->salida .= "   <td align=\"left\">\n";
                $this->salida .= "      ".$tomas[$i]['cantidad_reg'];
                $this->salida .= "   </td>\n";
                $this->salida .= "   <td class=\"normal_10AN\" align=\"left\">\n";
                $this->salida .= "       ".$tomas[$i]['observacion'];
                $this->salida .= "    </td>\n";
                $this->salida .= "    <td width='3%' align=\"center\">\n";
                $CONTEO=ModuloGetURL('app','InvTomaFisica','user','Admin_Usuarios',array('toma_id'=>$tomas[$i]['toma_fisica_id'],'bodegax'=>$tomas[$i]['nom_bodega'],'registros'=>$tomas[$i]['cantidad_reg']));
                $this->salida .= "         <a title='PERMISOS DE USUARIOS' href=\"".$CONTEO."\">";
                $this->salida .= "              <sub><img src=\"".$path."/images/usuarios.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                $this->salida .= "         </a>\n";
                $this->salida .= "     </td>\n";
                $this->salida .= "    <td width='3%' align=\"center\">\n";
                $mostrar = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosTomaFisica',array("datos"=>array("toma_fisica"=>$tomas[$i]['toma_fisica_id'],"empresa_id"=>$tomas[$i]['empresa_id'],"centro_utilidad"=>$tomas[$i]['centro_utilidad'],"bodega"=>$tomas[$i]['bodega'])),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                $funcion1 = $reporte->GetJavaFunction();
                $this->salida .= $mostrar;
                $conteos=ModuloGetURL('app','InvTomaFisica','user','FormaConteos',array('toma_id'=>$tomas[$i]['toma_fisica_id'],'registros'=>$tomas[$i]['cantidad_reg'],'bodegax'=>$tomas[$i]['nom_bodega'],"bodega"=>$tomas[$i]['bodega'],'centro_utilidad'=>$tomas[$i]['centro_utilidad'],"empresa_id"=>$tomas[$i]['empresa_id']));
                $this->salida .= "            <a href=\"javascript:abreVentana('".$conteos."')\" class=\"label_error\"><sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS DE LA TOMA FISICA ".$tomas[$i]['toma_fisica_id']."\"></sub>&nbsp;</a>\n";
                //$this->salida .= "              <a href=\"javascript:$funcion1\" class=\"label_error\"><sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS DE LA TOMA FISICA ".$tomas[$i]['toma_fisica_id']."\"></sub>&nbsp;</a>\n";
                $this->salida .= "      </td>\n";
               
                $productos_cuadre=$consulta->BuscarTomaFProductosCuadrados($tomas[$i]['toma_fisica_id']);
                if($productos_cuadre['total']=='0')
                {
                  $this->salida .= "     <td width='3%' align=\"center\">\n"; 
                  $mostrar2 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosStock',array("datos"=>array("toma_fisica"=>$tomas[$i]['toma_fisica_id'],"empresa_id"=>$tomas[$i]['empresa_id'],"centro_utilidad"=>$tomas[$i]['centro_utilidad'],"bodega"=>$tomas[$i]['bodega'])),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                  $funcion2 = $reporte->GetJavaFunction();
                  $this->salida .= $mostrar2;
                  $this->salida .= "          <a href=\"javascript:$funcion2\" class=\"label_error\"><sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS STOCK INICIAL\"></sub>&nbsp;</a>\n";
                  $this->salida .= "       </td>\n";
         
                  $csv = Autocarga::factory("ReportesCsv");
                  $mostrar5 .= $csv->GetJavacriptReporte('app','InvTomaFisica','ReporteProductosStock',array("toma_fisica"=>$tomas[$i]['toma_fisica_id'],"empresa_id"=>$tomas[$i]['empresa_id'],"centro_utilidad"=>$tomas[$i]['centro_utilidad'],"bodega"=>$tomas[$i]['bodega'],"nombre"=>"ReporteStockI".$tomas[$i]['toma_fisica_id'].""),'comas',array("interface"=>1,"cabecera"=>1,"nombre"=>"ReporteStockI".$tomas[$i]['toma_fisica_id']."","extension"=>"txt"));
                  $fncn1  = $csv->GetJavaFunction();      
                  $this->salida .= $mostrar5;
              
                   $this->salida .= "       <td class=\"modulo_list_claro\" align=\"center\">\n";
                  $this->salida .= "	        <a href=\"javascript:".$fncn1."\" class=\"label_error\">\n";
                  $this->salida .= "            <sub><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR ARCHIVO DE LOS PRODUCTOS STOCK INICIAL\"></sub>";
                  $this->salida .= "         </a>\n";
                  $this->salida .= "       </td>\n";
                }
                else
                {
                  //$this->salida .= "<pre>".print_r($productos_cuadre,true)."</pre>";
                  $this->salida .= "     <td width='3%' align=\"center\">\n";
                  $mostrar19 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosStock',array("datos"=>array("toma_fisica"=>$tomas[$i]['toma_fisica_id'],"empresa_id"=>$tomas[$i]['empresa_id'],"centro_utilidad"=>$tomas[$i]['centro_utilidad'],"bodega"=>$tomas[$i]['bodega'])),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                  $funcion2 = $reporte->GetJavaFunction();
                  $this->salida .= $mostrar19;
                  $this->salida .= "          <a href=\"javascript:$funcion2\" class=\"label_error\"><sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS STOCK FINAL\"></sub>&nbsp;</a>\n";
                  $this->salida .= "       </td>\n";
                  $csv = Autocarga::factory("ReportesCsv");
                  $mostrar5 .= $csv->GetJavacriptReporte('app','InvTomaFisica','ReporteProductosStock',array("toma_fisica"=>$tomas[$i]['toma_fisica_id'],"empresa_id"=>$tomas[$i]['empresa_id'],"centro_utilidad"=>$tomas[$i]['centro_utilidad'],"bodega"=>$tomas[$i]['bodega'],"nombre"=>"ReporteStockI".$tomas[$i]['toma_fisica_id'].""),'comas',array("interface"=>1,"cabecera"=>1,"nombre"=>"ReporteStockFinal".$tomas[$i]['toma_fisica_id']."","extension"=>"txt"));
                  $fncn1  = $csv->GetJavaFunction();      
                  $this->salida .= $mostrar5;
              
                   $this->salida .= "       <td class=\"modulo_list_claro\" align=\"center\">\n";
                  $this->salida .= "	        <a href=\"javascript:".$fncn1."\" class=\"label_error\">\n";
                  $this->salida .= "            <sub><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS STOCK FINAL\"></sub>";
                  $this->salida .= "         </a>\n";
                  $this->salida .= "       </td>\n";
                }
                
                $this->salida .= "      <td width='3%' align=\"center\">\n";
                if(!empty($tomas[$i]['fecha_inicio']))
                {
                    $CONTEO=ModuloGetURL('app','InvTomaFisica','user','FormaAjusteConteos',array('toma_id'=>$tomas[$i]['toma_fisica_id'],'registros'=>$tomas[$i]['cantidad_reg'],'bodegax'=>$tomas[$i]['nom_bodega'],"bodega"=>$tomas[$i]['bodega'],'centro_utilidad'=>$tomas[$i]['centro_utilidad']));
                    $this->salida .= "       <a title='TOMA FISICA ".$tomas[$i]['descripcion']."' href=\"".$CONTEO."\">";
                    $this->salida .= "         <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $this->salida .= "       </a>\n";
                }
                else
                {
                    $this->salida .= "       &nbsp;";
                }
                $this->salida .= "       </td>\n";
                $this->salida .= "       <td width='3%' align=\"center\" id='".$td_fecha."'>\n";
                if(empty($tomas[$i]['fecha_inicio']))
                {
                    $this->salida .= "      <a title='ACTIVAR TOMA FISICA' href=\"javascript:ActivarTomaFisica('".$td_fecha."','".$tomas[$i]['toma_fisica_id']."');\">";
                    $this->salida .= "        <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $this->salida .= "     </a>\n";
                }
                else
                {
                    $this->salida .= "      <a title='FECHA DE ACTIVACION: ".substr($tomas[$i]['fecha_inicio'],0,19)."'>";
                    $this->salida .= "        <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
                    $this->salida .= "      </a>\n";
                }
                $this->salida .= "      </td>\n";
                $this->salida .= "     </tr>\n";
            }
                $this->salida .= " </table>";
        }  
        $this->salida .= "  </div>\n";
        $this->salida .= "  <br>";
        $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','MenuTomaFisica');
        $this->salida .= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
        $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "       <td align=\"center\" colspan='7'>\n";
        $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
        $this->salida .= "       </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= " </form>\n";
        $this->salida.="<script language=\"javaScript\">
            function mOvr(src,clrOver)
            {
                src.style.background = clrOver;
            }
            function mOut(src,clrIn)
            {
                src.style.background = clrIn;
            }
            </script>";
        $this->salida .= ThemeCerrarTabla();
        return true;
 }
 /*
 * Funcion donde se genera el reporte de la toma fisica, dice en que conteo se necesita.
 */
 function FormaConteos()
 {
   $xml = Autocarga::factory("ReportesCsv");
   $mst .= $xml->GetJavacriptReporteFPDF('app','InvTomaFisica','ReporteProductosTomaFisica',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'],"empresa_id"=>$_REQUEST['empresa_id'],"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'],"filtro"=>"1")),array("interface"=>5));
   $fnc = $xml->GetJavaFunction();
   $this->salida .= $mst;
   $mst1 .= $xml->GetJavacriptReporteFPDF('app','InvTomaFisica','ReporteProductosTomaFisica',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'],"empresa_id"=>$_REQUEST['empresa_id'],"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'],"filtro"=>"2")),array("interface"=>5));
   $fnc1 = $xml->GetJavaFunction();
   $this->salida .= $mst1;
   $mst2 .= $xml->GetJavacriptReporteFPDF('app','InvTomaFisica','ReporteProductosTomaFisica',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'],"empresa_id"=>$_REQUEST['empresa_id'],"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'],"filtro"=>"3")),array("interface"=>5));
   $fnc2 = $xml->GetJavaFunction();
   $this->salida .= $mst2;
   $consulta= new TomaFisicaSQL();
   $reporte = new GetReports();/*
    $mostrar = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosTomaFisica',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'],"empresa_id"=>$_REQUEST['empresa_id'],"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'],"filtro"=>"1")),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
    $funcion1 = $reporte->GetJavaFunction();*/
    
    /*$mostrar1 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosTomaFisica',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'],"empresa_id"=>$_REQUEST['empresa_id'],"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'],"filtro"=>"2")),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
    $funcion2 = $reporte->GetJavaFunction();*/
    //$this->salida .= $mostrar1;
    //$mostrar2 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosTomaFisica',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'],"empresa_id"=>$_REQUEST['empresa_id'],"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'],"filtro"=>"3")),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
    //$funcion3 = $reporte->GetJavaFunction();
    //$this->salida .= $mostrar2;
    $this->salida .= " <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
    //$this->salida .= "<pre>".print_r($_REQUEST,true)."</pre>";
    $this->salida .= "  <tr>\n";
    $this->salida .= "   <td width=\"15%\" align=\"center\" class=\"label_error\">\n";
    $this->salida .= "     LABORATORIO ";
    $this->salida .= "      <a href=\"javascript:$fnc\" class=\"label_error\">\n";
    $this->salida .= "       <sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS DE LA TOMA FISICA ".$_REQUEST['toma_id']."\"></sub>&nbsp;</a>\n";
    $this->salida .= "   </td>\n";
    $this->salida .= "  </tr>\n";
    $this->salida .= "  <tr>\n";
    $this->salida .= "   <td width=\"15%\" align=\"center\" class=\"label_error\">\n";
    $this->salida .= "     MOLECULAS ";
    $this->salida .= "      <a href=\"javascript:$fnc1\" class=\"label_error\">\n";
    $this->salida .= "       <sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS DE LA TOMA FISICA ".$_REQUEST['toma_fisica_id']."\"></sub>&nbsp;</a>\n";
    $this->salida .= "   </td>\n";
    $this->salida .= "  </tr>\n";
    $this->salida .= "  <tr>\n";
    $this->salida .= "   <td width=\"15%\" align=\"center\" class=\"label_error\">\n";
    $this->salida .= "     UBICACION ";
    $this->salida .= "      <a href=\"javascript:$fnc2\" class=\"label_error\">\n";
    $this->salida .= "       <sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS DE LA TOMA FISICA ".$_REQUEST['toma_fisica_id']."\"></sub>&nbsp;</a>\n";
    $this->salida .= "   </td>\n";
    $this->salida .= "  </tr>\n";
    $this->salida .= " </table>";
    return true;
 }
 
/*******************************************************
*
*Ajuste conteo toma fisica
*************************************************************/
    function FormaAjusteConteos()
    {
       //VAR_DUMP($_REQUEST);
      $path = SessionGetVar("rutaImagenes");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
			 
      $this->IncludeJS("TabPaneLayout");
      $this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
      $clas = AutoCarga ::factory("ClaseUtil");
      $this->salida.= $clas->IsNumeric();
      $this->salida.= $clas->AcceptNum(false,false);
      $this->salida.= $clas->AcceptDate("-");
      $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
      $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
	  
      $this->SetXajax(array("Ajustar_Cero",
	  "Ajustar_A_Cero","AjustesAutomaticos",
	  "ConsultaParaCierre","Borrar","BorrarAjuste",
	  "SetCuadrarPro2","CuadrarPro2","SetCuadrarPro1",
	  "CuadrarPro1","SetCuadrarPro","CuadrarPro",
	  "NoCuadraConteo3","NoCuadraConteo2","InfoConteo1x",
	  "InfoConteo2x","InfoConteo3x","InfoSinConteo",
	  "NoCuadraConteo1","ListarDocumentosIngresoAjax",
	  "CrearDocumentoIngreso","ListarDocumentosEgresoAjax",
	  "CrearDocumentoEgreso","ModificarC2","ModificarConteo2",
	  "ModificarC3","ModificarConteo3","CuadreAutomatico"),$file,"ISO-8859-1");
	  
        $consulta= new TomaFisicaSQL();
        $numero_conteos=$consulta ->ObtenerNumeroConteos($_REQUEST['toma_id']);

      $javaC = "<script>\n";
      $javaC .= "   var contenedor1=''\n";
      $javaC .= "   var titulo1=''\n";
      $javaC .= "   var hiZ = 2;\n";
      $javaC .= "   var DatosFactor = new Array();\n";
      $javaC .= "   var EnvioFactor = new Array();\n";
      $javaC .= "   function Iniciar4(tit)\n";
      $javaC .= "   {\n";
      $javaC .= "       contenedor1 = 'ContenedorBus';\n";
      $javaC .= "       titulo1 = 'tituloBus';\n";
      $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
      $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
      $javaC .= "       xResizeTo(Capa, 600, 400);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/11, xScrollTop()+100);\n";
      $javaC .= "       ele = xGetElementById(titulo1);\n";
      $javaC .= "       xResizeTo(ele, 580, 20);\n";
      $javaC .= "       xMoveTo(ele, 0, 0);\n";
      $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $javaC .= "       ele = xGetElementById('cerrarBus');\n";
      $javaC .= "       xResizeTo(ele, 20, 20);\n";
      $javaC .= "       xMoveTo(ele, 580, 0);\n";
      $javaC .= "   }\n";
	  
	  
      $javaC .= "   function IniciarAj(tit)\n";
      $javaC .= "   {\n";
      $javaC .= "       contenedor1 = 'ContenedorAj';\n";
      $javaC .= "       titulo1 = 'tituloAj';\n";
      $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
      $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
      /*Tamaño Contenido*/
	  $javaC .= "       xResizeTo(Capa, 500, 250);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-90);\n";
      $javaC .= "       ele = xGetElementById(titulo1);\n";
      /*Tamaño Barra Titulo*/
	  $javaC .= "       xResizeTo(ele, 490, 20);\n";
      /*Mueve Barra Titulo*/
	  $javaC .= "       xMoveTo(ele, 0, 0);\n";
      $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $javaC .= "       ele = xGetElementById('cerrarAj');\n";
      $javaC .= "       xResizeTo(ele, 20, 20);\n";
      /*Mover X de cerrar*/
	  $javaC .= "       xMoveTo(ele, 480, 0);\n";
      $javaC .= "   }\n";
	  
	  
	  
      $javaC .= "   function IniciarAj1(tit)\n";
      $javaC .= "   {\n";
      $javaC .= "       contenedor1 = 'ContenedorAj1';\n";
      $javaC .= "       titulo1 = 'tituloAj1';\n";
      $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
      $javaC.= "        Capa = xGetElementById(contenedor1);\n";  
	  /*Tamaño Contenido*/
	  $javaC .= "       xResizeTo(Capa, 500, 250);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-90);\n";
      $javaC .= "       ele = xGetElementById(titulo1);\n";
      /*Tamaño Barra Titulo*/
	  $javaC .= "       xResizeTo(ele, 490, 20);\n";
      /*Mueve Barra Titulo*/
	  $javaC .= "       xMoveTo(ele, 0, 0);\n";
      $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $javaC .= "       ele = xGetElementById('cerrarAj1');\n";
      $javaC .= "       xResizeTo(ele, 20, 20);\n";
      /*Mover X de cerrar*/
	  $javaC .= "       xMoveTo(ele, 480, 0);\n";
      $javaC .= "   }\n";
	  
	  
      $javaC .= "   function IniciarModificarC1C2(tit)\n";
      $javaC .= "   {\n";
      $javaC .= "       contenedor1 = 'ContenedorModificarC1C2';\n";
      $javaC .= "       titulo1 = 'tituloModificarC1C2';\n";
      $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
      $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
	   /*Tamaño Contenido*/
	  $javaC .= "       xResizeTo(Capa, 500, 250);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-90);\n";
      $javaC .= "       ele = xGetElementById(titulo1);\n";
      /*Tamaño Barra Titulo*/
	  $javaC .= "       xResizeTo(ele, 490, 20);\n";
      /*Mueve Barra Titulo*/
	  $javaC .= "       xMoveTo(ele, 0, 0);\n";
      $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $javaC .= "       ele = xGetElementById('CerrarModificarC1C2');\n";
      $javaC .= "       xResizeTo(ele, 20, 20);\n";
      /*Mover X de cerrar*/
	  $javaC .= "       xMoveTo(ele, 480, 0);\n";
      $javaC .= "   }\n";
      $javaC .= "   function IniciarModificarC3(tit)\n";
      $javaC .= "   {\n";
      $javaC .= "       contenedor1 = 'ContenedorModificarC3';\n";
      $javaC .= "       titulo1 = 'tituloModificarC3';\n";
      $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
      $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
      $javaC .= "       xResizeTo(Capa, 300, 220);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-90);\n";
      $javaC .= "       ele = xGetElementById(titulo1);\n";
      $javaC .= "       xResizeTo(ele, 280, 20);\n";
      $javaC .= "       xMoveTo(ele, 0, 0);\n";
      $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $javaC .= "       ele = xGetElementById('cerrarModificarC3');\n";
      $javaC .= "       xResizeTo(ele, 20, 20);\n";
      $javaC .= "       xMoveTo(ele, 280, 0);\n";
      $javaC .= "   }\n";
      $javaC .= "   function IniciarAj2(tit)\n";
      $javaC .= "   {\n";
      $javaC .= "       contenedor1 = 'ContenedorAj2';\n";
      $javaC .= "       titulo1 = 'tituloAj2';\n";
      $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
      $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
      $javaC .= "       xResizeTo(Capa, 300, 240);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-90);\n";
      $javaC .= "       ele = xGetElementById(titulo1);\n";
      $javaC .= "       xResizeTo(ele, 280, 20);\n";
      $javaC .= "       xMoveTo(ele, 0, 0);\n";
      $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $javaC .= "       ele = xGetElementById('cerrarAj2');\n";
      $javaC .= "       xResizeTo(ele, 20, 20);\n";
      $javaC .= "       xMoveTo(ele, 280, 0);\n";
      $javaC .= "   }\n";
      $javaC .= "   function IniciarDocumentos(tit)\n";
      $javaC .= "   {\n";
      $javaC .= "       contenedor1 = 'ContenedorDocumentos';\n";
      $javaC .= "       titulo1 = 'tituloDocumentos';\n";
      $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
      $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
      $javaC .= "       xResizeTo(Capa, 400, 260);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/4, xScrollTop());\n";
      $javaC .= "       ele = xGetElementById(titulo1);\n";
      $javaC .= "       xResizeTo(ele, 380, 20);\n";
      $javaC .= "       xMoveTo(ele, 0, 0);\n";
      $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $javaC .= "       ele = xGetElementById('cerrarDocumentos');\n";
      $javaC .= "       xResizeTo(ele, 20, 20);\n";
      $javaC .= "       xMoveTo(ele, 380, 0);\n";
      $javaC .= "   }\n";       
      $javaC .= "   function IniciarB1(tit)\n";
      $javaC .= "   {\n";
      $javaC .= "       contenedor1 = 'ContenedorB1';\n";
      $javaC .= "       titulo1 = 'tituloB1';\n";
      $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
      $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
      $javaC .= "       xResizeTo(Capa, 200, 160);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-0);\n";
      $javaC .= "       ele = xGetElementById(titulo1);\n";
      $javaC .= "       xResizeTo(ele, 180, 20);\n";
      $javaC .= "       xMoveTo(ele, 0, 0);\n";
      $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $javaC .= "       ele = xGetElementById('cerrarB1');\n";
      $javaC .= "       xResizeTo(ele, 20, 20);\n";
      $javaC .= "       xMoveTo(ele, 180, 0);\n";
      $javaC .= "   }\n";      
      $javaC .= "   function IniciarB2(tit)\n";
      $javaC .= "   {\n";
      $javaC .= "       contenedor1 = 'ContenedorB2';\n";
      $javaC .= "       titulo1 = 'tituloB2';\n";
      $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
      $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
      $javaC .= "       xResizeTo(Capa, 200, 160);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-0);\n";
      $javaC .= "       ele = xGetElementById(titulo1);\n";
      $javaC .= "       xResizeTo(ele, 180, 20);\n";
      $javaC .= "       xMoveTo(ele, 0, 0);\n";
      $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $javaC .= "       ele = xGetElementById('cerrarB2');\n";
      $javaC .= "       xResizeTo(ele, 20, 20);\n";
      $javaC .= "       xMoveTo(ele, 180, 0);\n";
      $javaC .= "   }\n";      
      $javaC .= "   function IniciarB3(tit)\n";
      $javaC .= "   {\n";
      $javaC .= "       contenedor1 = 'ContenedorB3';\n";
      $javaC .= "       titulo1 = 'tituloB3';\n";
      $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
      $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
      $javaC .= "       xResizeTo(Capa, 200, 160);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-0);\n";
      $javaC .= "       ele = xGetElementById(titulo1);\n";
      $javaC .= "       xResizeTo(ele, 180, 20);\n";
      $javaC .= "       xMoveTo(ele, 0, 0);\n";
      $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $javaC .= "       ele = xGetElementById('cerrarB3');\n";
      $javaC .= "       xResizeTo(ele, 20, 20);\n";
      $javaC .= "       xMoveTo(ele, 180, 0);\n";
      $javaC .= "   }\n";      
      $javaC .= "   function IniciarB5(tit)\n";
      $javaC .= "   {\n";
      $javaC .= "       contenedor1 = 'ContenedorB5';\n";
      $javaC .= "       titulo1 = 'tituloB5';\n";
      $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
      $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
      $javaC .= "       xResizeTo(Capa, 400, 160);\n";
      $javaC .= "       xMoveTo(Capa, xClientWidth()/3, xScrollTop()-0);\n";
      $javaC .= "       ele = xGetElementById(titulo1);\n";
      $javaC .= "       xResizeTo(ele, 380, 20);\n";
      $javaC .= "       xMoveTo(ele, 0, 0);\n";
      $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $javaC .= "       ele = xGetElementById('cerrarB5');\n";
      $javaC .= "       xResizeTo(ele, 20, 20);\n";
      $javaC .= "       xMoveTo(ele, 380, 0);\n";
      $javaC .= "   }\n";
      $javaC.= "function ConfirmarCuadre()\n";
      $javaC.= "{\n";
      $javaC.= "   if (confirm('ESTA SEGURO DE HACER CUADRE AUTOMATICO'))\n";
      $javaC.= "  {\n";
      $javaC.= "  xajax_CuadreAutomatico();\n";
      $javaC.= "   }\n";
      $javaC.= "}\n";     
      $javaC.= "</script>\n";
      $this->salida.= $javaC;
      $javaC1.= "<script>\n";
      $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
      $javaC1 .= "   {\n";
      $javaC1 .= "     window.status = '';\n";
      $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
      $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
      $javaC1 .= "     ele.myTotalMX = 0;\n";
      $javaC1 .= "     ele.myTotalMY = 0;\n";
      $javaC1 .= "   }\n";
      $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";
      $javaC1 .= "   {\n";
      $javaC1 .= "     if (ele.id == titulo1) {\n";
      $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
      $javaC1 .= "     }\n";
      $javaC1 .= "     else {\n";
      $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
      $javaC1 .= "     }  \n";
      $javaC1 .= "     ele.myTotalMX += mdx;\n";
      $javaC1 .= "     ele.myTotalMY += mdy;\n";
      $javaC1 .= "   }\n";
      $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
      $javaC1 .= "   {\n";
      $javaC1 .= "   }\n";
      $javaC1.= "function MostrarCapa(Elemento)\n";
      $javaC1.= "{\n";
      $javaC1.= "    capita = xGetElementById(Elemento);\n";
      $javaC1.= "    capita.style.display = \"\";\n";
      $javaC1.= "}\n";
      $javaC1.= "function Cerrar(Elemento)\n";
      $javaC1.= "{\n";
      $javaC1.= "    capita = xGetElementById(Elemento);\n";          
      $javaC1.= "    capita.style.display = \"none\";\n";          
      $javaC1.= "}\n"; 
 
      $javaC1.= "</script>\n";
      $this->salida.= $javaC1;
/**************************************************************************************
*final de la ventana3
***********************************************************************************/              
      $this->salida .= ThemeAbrirTabla("TOMA FISICA - BODEGA: ".$_REQUEST['bodegax']);
      $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td width=\"10%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                          EMPRESA";
      $this->salida .= "                       </td>\n";
      $nombre=$consulta->ColocarEmpresa(SessionGetVar("EMPRESA"));
      $this->salida .= "                       <td width=\"25%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ".$nombre[0]['razon_social'];
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td width=\"10%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                          BODEGA";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td width=\"25%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                          ".$_REQUEST['bodegax'];
      $this->salida .= "                       </td>\n"; 
      $this->salida .= "                       <td width=\"10%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                          USUARIO ";
      $this->salida .= "                       </td>\n";
      $usuario_idx=$consulta->BuscarUsuario(UserGetUID());
      $this->salida .= "                       <td width=\"20%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ".$usuario_idx[0]['nombre'];
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         TOMA FISICA";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ".$_REQUEST['toma_id'];
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                          FECHA";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                          ".date("Y-m-d  H:i")."";
      $this->salida .= "                       </td>\n"; 
      $this->salida .= "                       <td  align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                          # PRODUCTOS";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td  align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ".$_REQUEST['registros'];
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                 </table>";
      $this->salida .= "                 <BR>";

   

      
      $this->salida .= "  <table width=\"100%\" align=\"center\">\n";
      $this->salida .= "    <tr>\n";
      $this->salida .= "      <td>\n";
      $this->salida .= "        <table width=\"100%\" align=\"center\">\n";
      $this->salida .= "          <tr>\n";
      $this->salida .= "            <td>\n";
      $this->salida .= "              <div class=\"tab-pane\" id=\"Padre_Conteos\">\n";
      $this->salida .= "                <script>  tabPane = new WebFXTabPane( document.getElementById( \"Padre_Conteos\" ), false); </script>\n";
      $this->salida .= "                <div class=\"tab-page\" id=\"Conteo1\">\n";
      $this->salida .= "                  <h2 id=\"Contix1\" class=\"tab\">CONTEO 1</h2>\n";
      $this->salida .= "                   <script>
                                            tabPane.addTabPage(document.getElementById(\"Conteo1\"));
                                            InfoConteo1('2','".$_REQUEST['toma_id']."',xajax.getFormValues('FormaConteo1_'),'".$numero_conteos['numero_conteos']."','1');
                                           </script>\n";
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/



      $this->salida .= " <div id='ContenedorB1' class='d2Container' style=\"display:none;\">";
      $this->salida .= "    <div id='tituloB1' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='cerrarB1' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorB1');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $this->salida .= "    <div id='errorB1' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $this->salida .= "    <div id='ContenidoB1'  class='d2Content' style='z-index:10;'>\n";
      $this->salida .= "    </div>\n";
      $this->salida .= " </div>\n"; 
	$html  = "							<form name=\"FormaConteo1_\" id=\"FormaConteo1_\" method=\"POST\" action=\"\">";
	$html .= "								<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
	$html .= "									<tr class=\"formulacion_table_list\">";
	$html .= "										<td colspan=\"6\" >";
	$html .= "											BUSCADOR";
	$html .= "										</td>";
	$html .= "									</tr>";
	$html .= "									<tr class=\"modulo_table_list_title\">";
	$html .= "										<td align=\"left\">";
	$html .= "											ETIQUETA";
	$html .= "										</td>";
	$html .= "										<td class=\"modulo_list_oscuro\">";
	$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[etiqueta]\" id=\"etiqueta\" style=\"width:100%\">";
	$html .= "										</td>";
	$html .= "										<td align=\"left\">";
	$html .= "											DESCRIPCION";
	$html .= "										</td>";
	$html .= "										<td class=\"modulo_list_oscuro\">";
	$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[producto]\" id=\"etiqueta\" style=\"width:100%\">";
	$html .= "										</td>";
	$html .= "										<td align=\"left\">";
	$html .= "											CLASE";
	$html .= "										</td>";
	$html .= "										<td class=\"modulo_list_oscuro\">";
	$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[clase_descripcion]\" id=\"etiqueta\" style=\"width:100%\">";
	$html .= "										</td>";
	$html .= "									</tr>";
	$html .= "									<tr class=\"formulacion_table_list\">";
	$html .= "										<td colspan=\"6\">";
	$html .= "											<input class=\"input-submit\" type=\"button\" value=\"BUSCAR\" onclick=\"InfoConteo1('1','".$_REQUEST['toma_id']."',xajax.getFormValues('FormaConteo1_'),'".$numero_conteos['numero_conteos']."','1');\">";
	$html .= "										</td>";
	$html .= "									</tr>";
	$html .= "								</table>";
	$html .= "							</form>";
	$this->salida .= $html;
      $this->salida .= "                <div id=\"InfoConteo1\">\n";
      $this->salida .= "                </div>\n";
      $this->salida .= "                </div>\n";
///////////////////////////    FIN CONTEO 1///////////////////////////////////////
      $this->salida .= "                <div class=\"tab-page\" id=\"SinConteo1\" >\n";
      $this->salida .= "                  <h2 id=\"NoCuadro1\" class=\"tab\" >CONTEO 1 SIN CUADRAR</h2>\n";
      $this->salida .= "                  <script>\n";
      $this->salida .= "                    tabPane.addTabPage( document.getElementById(\"SinConteo1\"));\n";
      $this->salida .= "                  </script>\n";
/////////////////////////////////////////////////////////////////////////////////////
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
      $this->salida.="<div id='ContenedorAj' class='d2Container' style=\"display:none;\">";
      $this->salida .= "    <div id='tituloAj' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='cerrarAj' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorAj');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $this->salida .= "    <div id='errorAj' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $this->salida .= "    <div id='ContenidoAj'  class='d2Content' style='z-index:10;'>\n";
      $this->salida .= "             <form name='ventana_hill'>\n";
      $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td width=\"20%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                          TOMA FISICA";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='toma_fisica' width=\"30%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td width=\"50%\" align=\"LEFT\" class=\"modulo_table_list_title\" colspan=\"2\">\n";
      $this->salida .= "								<table width=\"100%\" class=\"modulo_list_oscuro\">";
	  $this->salida .= "									<tr class=\"modulo_table_list_title\">";
	  $this->salida .= "										<td>";
	  $this->salida .= "											ET.GRAL";
	  $this->salida .= "										</td>";
	  $this->salida .= "                       				<td id='etiquetaGral' align=\"LEFT\" class=\"modulo_list_claro\">\n";
	  $this->salida .= "										</td>";
	  $this->salida .= "										<td>";
	  $this->salida .= "											ET.LOTE";
	  $this->salida .= "										</td>";
	  $this->salida .= "                       				<td id='etiquetaxy' align=\"LEFT\" class=\"modulo_list_claro\">\n";
	  $this->salida .= "										</td>";
	  $this->salida .= "									<tr>";
	  $this->salida .= "								</table>";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         DESCRIPCION";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='desc' COLSPAN='3' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         UNIDAD";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='unidad' COLSPAN='3' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         EXISTENCIA";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='exist' COLSPAN='2' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         <input type=\"radio\" id=\"uno\" name=\"cuadrex\" value=\"1\" checked  onclick=\"\">";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         CONTEO 1";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='conteo1x' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='conteo1xdif1' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         <input type=\"radio\" id=\"dos\" name=\"cuadrex\" value=\"2\" onclick=\"\">";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         MANUAL";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='manual' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                          <input type=\"text\" class=\"input-text\" id=\"nueva_existencia\" name=\"nueva_existencia\" size=\"14\" onkeypress=\"return acceptNum(event);\" onkeyup=\"Calcular();\" onclick=\"limpiarText();\" value=\"\">\n";//
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='dife' align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         ";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         <input type=\"radio\" id=\"tres\" name=\"cuadrex\" value=\"3\" onclick=\"\">";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                      <td id='descxx2' COLSPAN='4' align=\"center\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"tr_h\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"toma_fisica_id_h\" name=\"toma_idx\" value=\"\">\n";//
      $this->salida .= "                            <input type=\"hidden\" id=\"etiqueta_h\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"num_conteo_h\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"sw_manual_h\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"empresa_id_h\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"centro_utilidad_h\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"bodega_h\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"codigo_producto_h\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"existencia_h\" value=\"\">\n";
	  $this->salida .= "                            <input type=\"hidden\" id=\"costo_h\" value=\"\">\n";
	  $this->salida .= "                            <input type=\"hidden\" id=\"lote_h\" value=\"\">\n";
	  $this->salida .= "                            <input type=\"hidden\" id=\"fecha_vencimiento_h\" value=\"\">\n";
      $this->salida .= "                            <input type=\"button\" class=\"input-submit\" id=\"validar\" name=\"validar\" value=\"CUADRAR\" onclick=\"SetCuadre(
                                                                                                                                                                     document.getElementById('tr_h').value,
                                                                                                                                                                     document.getElementById('toma_fisica_id_h').value,
                                                                                                                                                                     document.getElementById('etiqueta_h').value,
                                                                                                                                                                     document.getElementById('num_conteo_h').value,
                                                                                                                                                                     document.getElementById('sw_manual_h').value,
                                                                                                                                                                     document.getElementById('empresa_id_h').value,
                                                                                                                                                                     document.getElementById('centro_utilidad_h').value,
                                                                                                                                                                     document.getElementById('bodega_h').value,
                                                                                                                                                                     document.getElementById('codigo_producto_h').value,
                                                                                                                                                                     document.getElementById('existencia_h').value,
																																									 document.getElementById('nueva_existencia').value,
                                                                                                                                                                     document.getElementById('costo_h').value,
																																									 document.getElementById('lote_h').value,
																																									 document.getElementById('fecha_vencimiento_h').value);\">\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                </table>\n";
      $this->salida .= "         </form>\n";
      $this->salida .= "    </div>\n";
      $this->salida.="</div>";
      $tomas=$consulta->SacarAdmonTomaFisica(UserGetUID());
      ////////////////////////////////////////////////////////////////////////////////////////
      $reporte = new GetReports();                                                                  //$toma_fisica,$empresa_id,$centro_utilidad,$bodega
      //     ANTES array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE))   
      ///$_REQUEST['bodega']     
      //SessionGetVar("EMPRESA")      
      //array("datos"=>array("toma_fisica"=>$tomas[$i],"empresa_id"=>$tomas[$i]['empresa_id'],"centro_utilidad"=>$tomas[$i]['centro_utilidad'],"bodega"=>$tomas[$i]['bodega'])),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE)
      $mostrar3 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosTomaFisicaSC1',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'])),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $funcion1 = $reporte->GetJavaFunction();
      $this->salida .= $mostrar3;
      $para_reportec1 = $consulta->SacarNoCuadroC2($_REQUEST['toma_id']);
      $csv = Autocarga::factory("ReportesCsv");
      $mostrar13 .= $csv->GetJavacriptReporte('app','InvTomaFisica','ReporteProductosTomaFisicaSC1',array("toma_fisica"=>$_REQUEST['toma_id']),'comas',array("interface"=>1,"cabecera"=>1,"nombre"=>"ReporteProductosTomaFisicaSC1-".$_REQUEST['toma_id']."","extension"=>"csv"));
      $fncn6  = $csv->GetJavaFunction();      
      $this->salida .= $mostrar13;

	  
	  /*SE MUESTRA LA INFORMACION DE LOS PRODUCTOS DEL PRIMER CONTEO*/
		 $this->salida .= "	<table width=\"100%\">";
		$this->salida .= "		<td width=\"50%\">";
		$this->salida .= "                  <table class=\"modulo_table_list\" width=\"100%\">\n";
		$this->salida .= "                    <tr>\n";
		$this->salida .= "                      <td align=\"LEFT\" class=\"normal_10AN\">\n";
		$this->salida .= "							GENERAR REPORTE DE LOS PRODUCTOS DEL CONTEO 1 ";
		$this->salida .= "                      </td>\n";
		$this->salida .= "                      <td align=\"LEFT\" >\n";
		$this->salida .= "                        <a href=\"javascript:WindowPrinter0001()\" class=\"label_error\"><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS NO CUADRADOS EN CONTEO 1 \"></a>\n";
		$this->salida .= "                      </td>\n";
		$this->salida .= "                    </tr>\n";
		if($numero_conteos['numero_conteos'] =='1')
		{
		$this->salida .= "						<tr>\n";
		$this->salida .= "							<td class=\"normal_10AN\" colspan=\"2\">";
		$forma = "											<form name=\"forma_AjusteAutomatico\" id=\"forma_AjusteAutomatico\" method=\"POST\" >";
		$forma .= "												<table class=\"modulo_table_list\" width=\"100%\">\n";
		$forma .= "													<tr>";
		$forma .= "														<td colspan=\"2\" class=\"modulo_table_list_title\">";
		$forma .= "															AJUSTES AUTOMATICOS";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "													<tr>";
		$forma .= "														<td class=\"normal_10AN\">";
		$forma .= "															AJUSTE AL CONTEO 1";
		$forma .= "														</td>";
		$forma .= "														<td align=\"center\">";
		$forma .= "															<input type=\"radio\" name=\"opc\" id=\"opc0\" class=\"input-radio\" value=\"conteo_1\" checked>";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "													<tr>";
		$forma .= "														<td class=\"normal_10AN\">";
		$forma .= "															AJUSTE A LA EXISTENCIA";
		$forma .= "														</td>";
		$forma .= "														<td align=\"center\">";
		$forma .= "															<input type=\"radio\" name=\"opc\" id=\"opc1\" class=\"input-radio\" value=\"existencia\">";
		$forma .= "															<input type=\"hidden\" name=\"numero_conteos\" id=\"numero_conteos\"  value=\"".$numero_conteos['numero_conteos']."\">";
		$forma .= "															<input type=\"hidden\" name=\"toma_fisica_id\" id=\"toma_fisica_id\"  value=\"".$_REQUEST['toma_id']."\">";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "													<tr>";
		$forma .= "														<td colspan=\"2\" align=\"center\">";
		$forma .= "															<input type=\"button\" class=\"input-submit\" value=\"AJUSTE AUTOMATICO\" onclick=\"xajax_AjustesAutomaticos(xajax.getFormValues('forma_AjusteAutomatico'));\">";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "												</table>";
		$forma .= "											</form>";
		$this->salida .= "							".$forma;
		$this->salida .= "							</td>";
		$this->salida .= "						</tr>\n";
		}
		$this->salida .= "                  </table>\n";
		$this->salida .= "		</td>";
		$this->salida .= "		<td width=\"50%\">";
		$html  = "							<form name=\"FormaConteo1_NC\" id=\"FormaConteo1_NC\" method=\"POST\" action=\"\">";
		$html .= "								<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
		$html .= "									<tr class=\"formulacion_table_list\">";
		$html .= "										<td colspan=\"6\" >";
		$html .= "											BUSCADOR";
		$html .= "										</td>";
		$html .= "									</tr>";
		$html .= "									<tr class=\"modulo_table_list_title\">";
		$html .= "										<td align=\"left\">";
		$html .= "											ETIQUETA";
		$html .= "										</td>";
		$html .= "										<td class=\"modulo_list_oscuro\">";
		$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[etiqueta]\" id=\"etiqueta\" style=\"width:100%\">";
		$html .= "										</td>";
		$html .= "										<td align=\"left\">";
		$html .= "											DESCRIPCION";
		$html .= "										</td>";
		$html .= "										<td class=\"modulo_list_oscuro\">";
		$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[producto]\" id=\"etiqueta\" style=\"width:100%\">";
		$html .= "										</td>";
		$html .= "										<td align=\"left\">";
		$html .= "											CLASE";
		$html .= "										</td>";
		$html .= "										<td class=\"modulo_list_oscuro\">";
		$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[clase_descripcion]\" id=\"etiqueta\" style=\"width:100%\">";
		$html .= "										</td>";
		$html .= "									</tr>";
		$html .= "									<tr class=\"formulacion_table_list\">";
		$html .= "										<td colspan=\"6\">";
		$html .= "											<input class=\"input-submit\" type=\"button\" value=\"BUSCAR\" onclick=\"SacarNoCuadraConteo1('".$_REQUEST['toma_id']."',xajax.getFormValues('FormaConteo1_NC'),'".$numero_conteos['numero_conteos']."','1');\">";
		$html .= "										</td>";
		$html .= "									</tr>";
		$html .= "								</table>";
		$html .= "							</form>";
		$this->salida .= $html;
		$this->salida .= "		</td>";
		$this->salida .= "	</table>";
		$this->salida .= "<br>";
      $this->salida .= "  <div id=\"InfoSinConteo1\">\n";
      $this->salida .= "  </div>\n";
      if(!empty($para_reportec1))
      {
        $this->salida .= "  <table width=\"100%\" BORDER='0' align=\"center\">\n";
        $this->salida .= "   <tr>\n";
        $this->salida .= "    <td  align=\"center\">\n";
        $this->salida .= "	     <a href=\"javascript:".$fncn6."\" class=\"label_error\">GENERAR REPORTE PRODUCTOS CONTEO 1 CSV\n";
        $this->salida .= "      <sub><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR ARCHIVO DE LOS PRODUCTOS CONTEO 1\"></sub>";
        $this->salida .= "      </a>\n";
        $this->salida .= "    </td>\n";
        $this->salida .= "   </tr>\n";
        $this->salida .= "  </table>\n";
       }
      $this->salida .= " </div>\n";
             
///////////////////////////////////////////CONTEO1 SIN CUADRAR/////////////////////////////////////////////////////////////////////
      $this->salida .= "                <div class=\"tab-page\" id=\"Conteo2\" >\n";
      $this->salida .= "                  <h2 id=\"Contix2\" class=\"tab\" >CONTEO 2</h2>\n";
      $this->salida .= "                  <script>\n";
      $this->salida .= "                    tabPane.addTabPage(document.getElementById(\"Conteo2\"));\n";
      $this->salida .= "                  </script>\n";
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
      $this->salida .= " <div id='ContenedorB2' class='d2Container' style=\"display:none;\">";
      $this->salida .= "    <div id='tituloB2' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='cerrarB2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorB2');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $this->salida .= "    <div id='errorB2' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $this->salida .= "    <div id='ContenidoB2'  class='d2Content' style='z-index:10;'>\n";
      $this->salida .= "    </div>\n";
      $this->salida .= " </div>\n";
	$html  = "							<form name=\"FormaConteo2_\" id=\"FormaConteo2_\" method=\"POST\" action=\"\">";
	$html .= "								<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
	$html .= "									<tr class=\"formulacion_table_list\">";
	$html .= "										<td colspan=\"6\" >";
	$html .= "											BUSCADOR";
	$html .= "										</td>";
	$html .= "									</tr>";
	$html .= "									<tr class=\"modulo_table_list_title\">";
	$html .= "										<td align=\"left\">";
	$html .= "											ETIQUETA";
	$html .= "										</td>";
	$html .= "										<td class=\"modulo_list_oscuro\">";
	$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[etiqueta]\" id=\"etiqueta\" style=\"width:100%\">";
	$html .= "										</td>";
	$html .= "										<td align=\"left\">";
	$html .= "											DESCRIPCION";
	$html .= "										</td>";
	$html .= "										<td class=\"modulo_list_oscuro\">";
	$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[producto]\" id=\"etiqueta\" style=\"width:100%\">";
	$html .= "										</td>";
	$html .= "										<td align=\"left\">";
	$html .= "											CLASE";
	$html .= "										</td>";
	$html .= "										<td class=\"modulo_list_oscuro\">";
	$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[clase_descripcion]\" id=\"etiqueta\" style=\"width:100%\">";
	$html .= "										</td>";
	$html .= "									</tr>";
	$html .= "									<tr class=\"formulacion_table_list\">";
	$html .= "										<td colspan=\"6\">";
	$html .= "											<input class=\"input-submit\" type=\"button\" value=\"BUSCAR\" onclick=\"SacarConteo2('2','".$_REQUEST['toma_id']."',xajax.getFormValues('FormaConteo2_'),'".$numero_conteos['numero_conteos']."','1');\">";
	$html .= "										</td>";
	$html .= "									</tr>";
	$html .= "								</table>";
	$html .= "							</form>";
	$this->salida .= $html;
      $this->salida .= "                <div id=\"InfoConteo2\">\n";
      $this->salida .= "                </div>\n";
      
      $this->salida .= "                </div>\n";
/////////////////////////////////////////////FIN CONTEO 2//////////////////////////////////////
      $this->salida .= "                <div class=\"tab-page\" id=\"SinConteo2\" >\n";
      $this->salida .= "                  <h2 id=\"NoCuadro2\" class=\"tab\" >CONTEO 2 SIN CUADRAR</h2>\n";
      $this->salida .= "                  <script>\n";
      $this->salida .= "                    tabPane.addTabPage(document.getElementById(\"SinConteo2\"));\n";
      $this->salida .= "                  </script>\n";
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
      $this->salida.="<div id='ContenedorAj1' class='d2Container' style=\"display:none;\">";
      $this->salida .= "    <div id='tituloAj1' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='cerrarAj1' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorAj1');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $this->salida .= "    <div id='errorAj1' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $this->salida .= "    <div id='ContenidoAj1'  class='d2Content' style='z-index:10;'>\n";
      $this->salida .= "             <form name='ventana_hill1'>\n";
      $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td width=\"20%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                          TOMA FISICA";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='toma_fisica1' width=\"30%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td width=\"50%\" align=\"LEFT\" class=\"modulo_table_list_title\" colspan=\"2\">\n";
      /*$this->salida .= "                         ETIQUETA";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='etiquetaxy1' width=\"30%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                         25639 ";
      $this->salida .= "                       </td>\n"; */
	   $this->salida .= "								<table width=\"100%\" class=\"modulo_list_oscuro\">";
	  $this->salida .= "									<tr class=\"modulo_table_list_title\">";
	  $this->salida .= "										<td>";
	  $this->salida .= "											ET.GRAL";
	  $this->salida .= "										</td>";
	  $this->salida .= "                       				<td id='etiquetaGral1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
	  $this->salida .= "										</td>";
	  $this->salida .= "										<td>";
	  $this->salida .= "											ET.LOTE";
	  $this->salida .= "										</td>";
	  $this->salida .= "                       				<td id='etiquetaxy1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
	  $this->salida .= "										</td>";
	  $this->salida .= "									<tr>";
	  $this->salida .= "								</table>";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         DESCRIPCION";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='desc1' COLSPAN='3' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         UNIDAD";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='unidad1' COLSPAN='3' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         EXISTENCIA";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='exist1' COLSPAN='2' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         <input type=\"radio\" id=\"uno1\" name=\"cuadrex\" value=\"1\" checked  onclick=\"\">";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         CONTEO 1";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='conteo1x1' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='conteo1x1dif1' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         <input type=\"radio\" id=\"dos1\" name=\"cuadrex\" value=\"2\" onclick=\"\">";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         CONTEO 2";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='conteo2x1' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='conteo2x1dif2' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         <input type=\"radio\" id=\"tres1\" name=\"cuadrex\" value=\"2\" onclick=\"\">";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         MANUAL";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='manual1' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                          <input type=\"text\" class=\"input-text\" id=\"nueva_existencia1\" name=\"nueva_existencia\" size=\"14\" onkeypress=\"return acceptNum(event);\" onkeyup=\"Calcular1();\" onclick=\"limpiarText1();\" value=\"\">\n";//
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='dife1' align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         ";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         <input type=\"radio\" id=\"cuatro1\" name=\"cuadrex\" value=\"3\" onclick=\"\">";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                      <td id='descxx' COLSPAN='4' align=\"center\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"tr_h1\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"toma_fisica_id_h1\" name=\"toma_idx\" value=\"\">\n";//
      $this->salida .= "                            <input type=\"hidden\" id=\"etiqueta_h1\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"num_conteo_h1\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"sw_manual_h1\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"empresa_id_h1\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"centro_utilidad_h1\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"bodega_h1\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"codigo_producto_h1\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"existencia_h1\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"costo_h1\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"lote_h1\" value=\"\">\n";
	  $this->salida .= "                            <input type=\"hidden\" id=\"fecha_vencimiento_h1\" value=\"\">\n";
	  $this->salida .= "                            <input type=\"button\" class=\"input-submit\" id=\"validar1\" name=\"validar\" value=\"CUADRAR\" onclick=\"SetCuadre1(
                                                                                                                                                                     document.getElementById('tr_h1').value,
                                                                                                                                                                     document.getElementById('toma_fisica_id_h1').value,
                                                                                                                                                                     document.getElementById('etiqueta_h1').value,
                                                                                                                                                                     document.getElementById('num_conteo_h1').value,
                                                                                                                                                                     document.getElementById('sw_manual_h1').value,
                                                                                                                                                                     document.getElementById('empresa_id_h1').value,
                                                                                                                                                                     document.getElementById('centro_utilidad_h1').value,
                                                                                                                                                                     document.getElementById('bodega_h1').value,
                                                                                                                                                                     document.getElementById('codigo_producto_h1').value,
                                                                                                                                                                     document.getElementById('existencia_h1').value,
                                                                                                                                                                     document.getElementById('nueva_existencia1').value,
																																									 document.getElementById('costo_h1').value,
																																									 document.getElementById('lote_h1').value,
																																									 document.getElementById('fecha_vencimiento_h1').value);\">\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                </table>\n";
      $this->salida .= "         </form>\n";
      $this->salida .= "    </div>\n";
      $this->salida.="</div>";
	  
      //$this->salida .= "                <div id=\"Modificacion\" ></div>\n";
      $this->salida.="<div id='ContenedorModificarC1C2' class='d2Container' style=\"display:none;\">";
      $this->salida .= "    <div id='tituloModificarC1C2' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='cerrarModificarC1C2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorModificarC1C2');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $this->salida .= "    <div id='errorModificarC1C2' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $this->salida .= "    <div id='ContenidoModificarC1C2'  class='d2Content' style='z-index:10;'>\n";
      $this->salida .= "      <form name='ventana_hill1'>\n";
      $this->salida .= "      <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "         <tr>\n";
      $this->salida .= "           <td width=\"20%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "              TOMA FISICA";
      $this->salida .= "           </td>\n";
      $this->salida .= "           <td id='toma_fisica_m'  COLSPAN='6' width=\"30%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "            ";
      $this->salida .= "           </td>\n";
      $this->salida .= "           </tr>\n";
      $this->salida .= "           <tr>\n";
      $this->salida .= "             <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                DESCRIPCION";
      $this->salida .= "              </td>\n";  
      $this->salida .= "              <td id='desc_m' COLSPAN='3' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "               ";
      $this->salida .= "              </td>\n";
      $this->salida .= "             </tr>\n"; 
      $this->salida .= "             <tr>\n";
      $this->salida .= "               <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                  CONTEO 2";
      $this->salida .= "               </td>\n";  
      $this->salida .= "               <td id='conteo_m2x1' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                  ";
      $this->salida .= "               </td>\n";
      $this->salida .= "               <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                  MANUAL";
      $this->salida .= "               </td>\n";
      $this->salida .= "                       <td id='manual1' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                          <input type=\"text\" class=\"input-text\" id=\"nueva_conteo2\" name=\"nueva_conteo2\" size=\"14\" onkeypress=\"return acceptNum(event);\" onkeyup=\"Calcular1();\" onclick=\"limpiarModificacion();\" value=\"\">\n";//
      $this->salida .= "                       </td>\n"; 
      $this->salida .= "             </tr>\n";
      
      $this->salida .= "                      <td id='descxx' COLSPAN='4' align=\"center\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"tr_h1_m\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"toma_fisica_id_h1_m\" name=\"toma_idx\" value=\"\">\n";//
      $this->salida .= "                            <input type=\"hidden\" id=\"etiqueta_h1_m\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"num_conteo_h1_m\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"sw_manual_h1_m\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"empresa_id_h1_m\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"centro_utilidad_h1_m\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"bodega_h1_m\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"codigo_producto_h1_m\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"existencia_h1_m\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"costo_h1_m\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"lote_h1_m\" value=\"\">\n";
	  $this->salida .= "                            <input type=\"hidden\" id=\"fecha_vencimiento_h1_m\" value=\"\">\n";
	  $this->salida .= "                            <input type=\"button\" class=\"input-submit\" id=\"validar1\" name=\"validar\" value=\"MODIFICAR\" onclick=\"ModificarConteo2(
                                                                                                                                                                     document.getElementById('tr_h1').value,
                                                                                                                                                                     document.getElementById('toma_fisica_id_h1_m').value,
                                                                                                                                                                     document.getElementById('etiqueta_h1_m').value,
                                                                                                                                                                     document.getElementById('num_conteo_h1_m').value,
                                                                                                                                                                     document.getElementById('sw_manual_h1_m').value,
                                                                                                                                                                     document.getElementById('empresa_id_h1_m').value,
                                                                                                                                                                     document.getElementById('centro_utilidad_h1_m').value,
                                                                                                                                                                     document.getElementById('bodega_h1_m').value,
                                                                                                                                                                     document.getElementById('codigo_producto_h1_m').value,
                                                                                                                                                                     document.getElementById('existencia_h1_m').value,
                                                                                                                                                                     document.getElementById('nueva_conteo2').value,
																																									 document.getElementById('costo_h1_m').value,
																																									 document.getElementById('lote_h1_m').value,
																																									 document.getElementById('fecha_vencimiento_h1_m').value);\">\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                </table>\n";
      $this->salida .= "         </form>\n";
      $this->salida .= "    </div>\n";
      $this->salida.="</div>";
      //$tomas=$consulta->SacarAdmonTomaFisica(UserGetUID());
/////////////////////////////////////////////////////////////////////////////////////////
                                                                        //$toma_fisica,$empresa_id,$centro_utilidad,$bodega
      //   ANTES   array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE)) 

      //$this->salida.="<pre>".print_r($tomas."tomas",true)."</pre>";      
      $mostrar5 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosTomaFisicaSC2',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'] ,"empresa_id"=>SessionGetVar("EMPRESA"),"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'] )),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $funcion1 = $reporte->GetJavaFunction();
      $this->salida .= $mostrar5;
      $para_reportec2 = $consulta->SacarNoCuadroC2($_REQUEST['toma_id']);
      //$this->salida.="<pre>".print_r($para_reportec2,true)."</pre>";      
      $mostrar14 .= $csv->GetJavacriptReporte('app','InvTomaFisica','ReporteProductosTomaFisicaSC2',array("toma_fisica"=>$_REQUEST['toma_id']),'comas',array("interface"=>1,"cabecera"=>1,"nombre"=>"ReporteProductosTomaFisicaSC2-".$_REQUEST['toma_id']."","extension"=>"csv"));
      $fncn3  = $csv->GetJavaFunction();      
      $this->salida .= $mostrar14;      
        $this->salida .= "	<table width=\"100%\">";
		$this->salida .= "		<td width=\"50%\">";
		$this->salida .= "                  <table class=\"modulo_table_list\" width=\"100%\">\n";
		$this->salida .= "                    <tr>\n";
		$this->salida .= "                      <td align=\"LEFT\" >\n";
		$this->salida .= "							GENERAR REPORTE DE LOS PRODUCTOS DEL CONTEO 2 ";
		$this->salida .= "                      </td>\n";
		$this->salida .= "                      <td align=\"LEFT\" >\n";
		$this->salida .= "                        <a href=\"javascript:WindowPrinter0002()\" class=\"label_error\"><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS NO CUADRADOS EN CONTEO 2 \"></a>\n";
		$this->salida .= "                      </td>\n";
		$this->salida .= "                    </tr>\n";
		if($numero_conteos['numero_conteos'] =='2')
		{
		$this->salida .= "						<tr>\n";
		$this->salida .= "							<td class=\"normal_10AN\" colspan=\"2\">";
		$forma = "											<form name=\"forma_AjusteAutomatico\" id=\"forma_AjusteAutomatico\" method=\"POST\" >";
		$forma .= "												<table class=\"modulo_table_list\" width=\"100%\">\n";
		$forma .= "													<tr>";
		$forma .= "														<td colspan=\"2\" class=\"modulo_table_list_title\">";
		$forma .= "															AJUSTES AUTOMATICOS";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "													<tr>";
		$forma .= "														<td class=\"normal_10AN\">";
		$forma .= "															AJUSTE AL CONTEO 1";
		$forma .= "														</td>";
		$forma .= "														<td align=\"center\">";
		$forma .= "															<input type=\"radio\" name=\"opc\" id=\"opc0\" class=\"input-radio\" value=\"conteo_1\" checked>";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "													<tr>";
		$forma .= "														<td class=\"normal_10AN\">";
		$forma .= "															AJUSTE AL CONTEO 2";
		$forma .= "														</td>";
		$forma .= "														<td align=\"center\">";
		$forma .= "															<input type=\"radio\" name=\"opc\" id=\"opc1\" class=\"input-radio\" value=\"conteo_2\" checked>";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "													<tr>";
		$forma .= "														<td class=\"normal_10AN\">";
		$forma .= "															AJUSTE A LA EXISTENCIA";
		$forma .= "														</td>";
		$forma .= "														<td align=\"center\">";
		$forma .= "															<input type=\"radio\" name=\"opc\" id=\"opc1\" class=\"input-radio\" value=\"existencia\">";
		$forma .= "															<input type=\"hidden\" name=\"numero_conteos\" id=\"numero_conteos\"  value=\"".$numero_conteos['numero_conteos']."\">";
		$forma .= "															<input type=\"hidden\" name=\"toma_fisica_id\" id=\"toma_fisica_id\"  value=\"".$_REQUEST['toma_id']."\">";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "													<tr>";
		$forma .= "														<td colspan=\"2\" align=\"center\">";
		$forma .= "															<input type=\"button\" class=\"input-submit\" value=\"AJUSTE AUTOMATICO\" onclick=\"xajax_AjustesAutomaticos(xajax.getFormValues('forma_AjusteAutomatico'));\">";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "												</table>";
		$forma .= "											</form>";
		$this->salida .= "							".$forma;
		$this->salida .= "							</td>";
		$this->salida .= "						</tr>\n";
		}
		$this->salida .= "                  </table>\n";
		$this->salida .= "		</td >";
		$this->salida .= "		<td width=\"50%\">";
		$html  = "							<form name=\"FormaConteo2_NC\" id=\"FormaConteo2_NC\" method=\"POST\" action=\"\">";
		$html .= "								<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
		$html .= "									<tr class=\"formulacion_table_list\">";
		$html .= "										<td colspan=\"6\" >";
		$html .= "											BUSCADOR";
		$html .= "										</td>";
		$html .= "									</tr>";
		$html .= "									<tr class=\"modulo_table_list_title\">";
		$html .= "										<td align=\"left\">";
		$html .= "											ETIQUETA";
		$html .= "										</td>";
		$html .= "										<td class=\"modulo_list_oscuro\">";
		$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[etiqueta]\" id=\"etiqueta\" style=\"width:100%\">";
		$html .= "										</td>";
		$html .= "										<td align=\"left\">";
		$html .= "											DESCRIPCION";
		$html .= "										</td>";
		$html .= "										<td class=\"modulo_list_oscuro\">";
		$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[producto]\" id=\"etiqueta\" style=\"width:100%\">";
		$html .= "										</td>";
		$html .= "										<td align=\"left\">";
		$html .= "											CLASE";
		$html .= "										</td>";
		$html .= "										<td class=\"modulo_list_oscuro\">";
		$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[clase_descripcion]\" id=\"etiqueta\" style=\"width:100%\">";
		$html .= "										</td>";
		$html .= "									</tr>";
		$html .= "									<tr class=\"formulacion_table_list\">";
		$html .= "										<td colspan=\"6\">";
		$html .= "											<input class=\"input-submit\" type=\"button\" value=\"BUSCAR\" onclick=\"SacarNoCuadraConteo2('".$_REQUEST['toma_id']."',xajax.getFormValues('FormaConteo2_NC'),'".$numero_conteos['numero_conteos']."','1');\">";
		$html .= "										</td>";
		$html .= "									</tr>";
		$html .= "								</table>";
		$html .= "							</form>";
		$this->salida .= $html;
		$this->salida .= "		</td>";
		$this->salida .= "	</table>";
		
		$this->salida .= "<br>";
	  
	  $this->salida .= "                <div id=\"InfoSinConteo2\">\n";
      $this->salida .= "                </div>\n";
      if(!empty($para_reportec2))
      {
        $this->salida .= "  <table width=\"100%\" BORDER='0' align=\"center\">\n";
        $this->salida .= "   <tr>\n";
        $this->salida .= "    <td  align=\"center\">\n";
        $this->salida .= "	     <a href=\"javascript:".$fncn3."\" class=\"label_error\">GENERAR REPORTE PRODUCTOS CONTEO 2 CSV\n";
        $this->salida .= "      <sub><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR ARCHIVO DE LOS PRODUCTOS CONTEO 2 \"></sub>";
        $this->salida .= "      </a>\n";
        $this->salida .= "    </td>\n";
        $this->salida .= "   </tr>\n";
        $this->salida .= "  </table>\n";
       }
      $this->salida .= "                </div>\n";
/////////////////////////////////////////////CONTEO2 SIN CUADRAR//////////////////////////////////////////////////////////////
      $this->salida .= "                <div class=\"tab-page\" id=\"Conteo3\">\n";
      $this->salida .= "                  <h2 id=\"Contix3\" class=\"tab\">CONTEO 3</h2>\n";
      $this->salida .= "                  <script>\n";
      $this->salida .= "                    tabPane.addTabPage(document.getElementById(\"Conteo3\"));\n";
      $this->salida .= "                  </script>\n";
      /*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
      $this->salida .= " <div id='ContenedorB3' class='d2Container' style=\"display:none;\">";
      $this->salida .= "    <div id='tituloB3' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='cerrarB3' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorB3');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $this->salida .= "    <div id='errorB3' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $this->salida .= "    <div id='ContenidoB3'  class='d2Content' style='z-index:10;'>\n";
      $this->salida .= "    </div>\n";
      $this->salida .= " </div>\n";
	$html  = "							<form name=\"FormaConteo3_\" id=\"FormaConteo3_\" method=\"POST\" action=\"\">";
	$html .= "								<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
	$html .= "									<tr class=\"formulacion_table_list\">";
	$html .= "										<td colspan=\"6\" >";
	$html .= "											BUSCADOR";
	$html .= "										</td>";
	$html .= "									</tr>";
	$html .= "									<tr class=\"modulo_table_list_title\">";
	$html .= "										<td align=\"left\">";
	$html .= "											ETIQUETA";
	$html .= "										</td>";
	$html .= "										<td class=\"modulo_list_oscuro\">";
	$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[etiqueta]\" id=\"etiqueta\" style=\"width:100%\">";
	$html .= "										</td>";
	$html .= "										<td align=\"left\">";
	$html .= "											DESCRIPCION";
	$html .= "										</td>";
	$html .= "										<td class=\"modulo_list_oscuro\">";
	$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[producto]\" id=\"etiqueta\" style=\"width:100%\">";
	$html .= "										</td>";
	$html .= "										<td align=\"left\">";
	$html .= "											CLASE";
	$html .= "										</td>";
	$html .= "										<td class=\"modulo_list_oscuro\">";
	$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[clase_descripcion]\" id=\"etiqueta\" style=\"width:100%\">";
	$html .= "										</td>";
	$html .= "									</tr>";
	$html .= "									<tr class=\"formulacion_table_list\">";
	$html .= "										<td colspan=\"6\">";
	$html .= "											<input class=\"input-submit\" type=\"button\" value=\"BUSCAR\" onclick=\"SacarConteo3('3','".$_REQUEST['toma_id']."',xajax.getFormValues('FormaConteo3_'),'".$numero_conteos['numero_conteos']."','1');\">";
	$html .= "										</td>";
	$html .= "									</tr>";
	$html .= "								</table>";
	$html .= "							</form>";
	$this->salida .= $html;
	 $this->salida .= "                <div id=\"InfoConteo3\">\n";
      $this->salida .= "                </div>\n";
      $this->salida .= "                </div>\n";
/////////////////////////////////////////FIN CONTEO 3//////////////////////////////////////
      $this->salida .= "                <div class=\"tab-page\" id=\"Sin_Conteo3\">\n";
      $this->salida .= "                  <h2 id=\"NoCuadra3\" class=\"tab\">CONTEO 3 SIN CUADRAR</h2>\n";
      $this->salida .= "                  <script>  tabPane.addTabPage( document.getElementById(\"Sin_Conteo3\")); </script>\n";
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
      $this->salida.="<div id='ContenedorAj2' class='d2Container' style=\"display:none;\">";
      $this->salida .= "    <div id='tituloAj2' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='cerrarAj2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorAj2');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $this->salida .= "    <div id='errorAj2' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $this->salida .= "    <div id='ContenidoAj2'  class='d2Content' style='z-index:10;'>\n";
      $this->salida .= "             <form name='ventana_hill2'>\n";
      $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
//       toma_fisica_id  etiqueta
//       codigo_producto descripcion
//       unidad_id descripcion_unidad
//       existencia  conteo_1
//       validacion_conteo_1 diferencia_1
//       conteo_2  validacion_conteo_2
//       diferencia_2  conteo_3
//       validacion_conteo_3 diferencia_3
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td width=\"20%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                          TOMA FISICA";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='toma_fisica2' width=\"30%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td width=\"20%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         ETIQUETA";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='etiquetaxy2' width=\"30%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                          ";
      $this->salida .= "                       </td>\n"; 
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         DESCRIPCION";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='desc2' COLSPAN='3' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         UNIDAD";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='unidad2' COLSPAN='3' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         EXISTENCIA";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='exist2' COLSPAN='2' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         <input type=\"radio\" id=\"uno2\" name=\"cuadrex\" value=\"1\" checked  onclick=\"\">";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         CONTEO 1";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='conteo1x2' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='conteo1x2dif1' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         <input type=\"radio\" id=\"dos2\" name=\"cuadrex\" value=\"2\" onclick=\"\">";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         CONTEO 2";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='conteo2x2' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='conteo2x2dif2' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         <input type=\"radio\" id=\"tres2\" name=\"cuadrex\" value=\"2\" onclick=\"\">";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         CONTEO 3";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='conteo3x2' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='conteo3x2dif3' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                        ";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         <input type=\"radio\" id=\"cuatro2\" name=\"cuadrex\" value=\"2\" onclick=\"\">";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         MANUAL";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td id='manual2' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                          <input type=\"text\" class=\"input-text\" id=\"nueva_existencia2\" name=\"nueva_existencia\" size=\"14\" onkeypress=\"return acceptNum(event);\" onkeyup=\"Calcular2();\" onclick=\"limpiarText2();\" value=\"\">\n";//
      $this->salida .= "                       </td>\n";
      $this->salida .= "                       <td id='dife2' align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         ";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                         <input type=\"radio\" id=\"cinco2\" name=\"cuadrex\" value=\"3\" onclick=\"\">";
      $this->salida .= "                       </td>\n";  
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                    <tr>\n";
      $this->salida .= "                      <td id='descxx' COLSPAN='4' align=\"center\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"tr_h2\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"toma_fisica_id_h2\" name=\"toma_idx\" value=\"\">\n";//
      $this->salida .= "                            <input type=\"hidden\" id=\"etiqueta_h2\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"num_conteo_h2\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"sw_manual_h2\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"empresa_id_h2\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"centro_utilidad_h2\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"bodega_h2\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"codigo_producto_h2\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"existencia_h2\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"costo_h2\" value=\"\">\n";
	  $this->salida .= "                            <input type=\"hidden\" id=\"lote_h2\" value=\"\">\n";
	  $this->salida .= "                            <input type=\"hidden\" id=\"fecha_vencimiento_h2\" value=\"\">\n";
      $this->salida .= "                            <input type=\"button\" class=\"input-submit\" id=\"validar2\" name=\"validar\" value=\"CUADRAR\" onclick=\"SetCuadre2(
                                                                                                                                                                     document.getElementById('tr_h2').value,
                                                                                                                                                                     document.getElementById('toma_fisica_id_h2').value,
                                                                                                                                                                     document.getElementById('etiqueta_h2').value,
                                                                                                                                                                     document.getElementById('num_conteo_h2').value,
                                                                                                                                                                     document.getElementById('sw_manual_h2').value,
                                                                                                                                                                     document.getElementById('empresa_id_h2').value,
                                                                                                                                                                     document.getElementById('centro_utilidad_h2').value,
                                                                                                                                                                     document.getElementById('bodega_h2').value,
                                                                                                                                                                     document.getElementById('codigo_producto_h2').value,
                                                                                                                                                                     document.getElementById('existencia_h2').value,
                                                                                                                                                                     document.getElementById('nueva_existencia2').value,
																																									 document.getElementById('costo_h2').value,
																																									 document.getElementById('lote_h2').value,
																																									 document.getElementById('fecha_vencimiento_h2').value);\">\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                </table>\n";
      $this->salida .= "         </form>\n";
      $this->salida .= "    </div>\n";
      $this->salida.="</div>";
      $this->salida.="<div id='ContenedorModificarC3' class='d2Container' style=\"display:none;\">";
      $this->salida .= "    <div id='tituloModificarC3' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='cerrarModificarC3' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorModificarC3');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $this->salida .= "    <div id='errorModificarC3' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $this->salida .= "    <div id='ContenidoModificarC3'  class='d2Content' style='z-index:10;'>\n";
      $this->salida .= "      <form name='ventana_hill1'>\n";
      $this->salida .= "      <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
      $this->salida .= "         <tr>\n";
      $this->salida .= "           <td width=\"20%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "              TOMA FISICA";
      $this->salida .= "           </td>\n";
      $this->salida .= "           <td id='toma_fisica_m3'  COLSPAN='6' width=\"30%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "            ";
      $this->salida .= "           </td>\n";
      $this->salida .= "           </tr>\n";
      $this->salida .= "           <tr>\n";
      $this->salida .= "             <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                DESCRIPCION";
      $this->salida .= "              </td>\n";  
      $this->salida .= "              <td id='desc_m3' COLSPAN='3' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "               ";
      $this->salida .= "              </td>\n";
      $this->salida .= "             </tr>\n"; 
      $this->salida .= "             <tr>\n";
      $this->salida .= "               <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                  CONTEO 3";
      $this->salida .= "               </td>\n";  
      $this->salida .= "               <td id='conteo_mi2x1' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                  ";
      $this->salida .= "               </td>\n";
      $this->salida .= "               <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
      $this->salida .= "                  MANUAL";
      $this->salida .= "               </td>\n";
      $this->salida .= "                       <td id='manual1' COLSPAN='1' align=\"LEFT\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                          <input type=\"text\" class=\"input-text\" id=\"nueva_conteo3\" name=\"nueva_conteo3\" size=\"14\" onkeypress=\"return acceptNum(event);\" onkeyup=\"Calcular1();\" onclick=\"limpiarModificacion();\" value=\"\">\n";//
      $this->salida .= "                       </td>\n"; 
      $this->salida .= "             </tr>\n";
      
      $this->salida .= "                      <td id='descxx' COLSPAN='4' align=\"center\" class=\"modulo_list_claro\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"tr_h1_m3\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"toma_fisica_id_h1_m3\" name=\"toma_idx\" value=\"\">\n";//
      $this->salida .= "                            <input type=\"hidden\" id=\"etiqueta_h1_m3\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"num_conteo_h1_m3\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"sw_manual_h1_m3\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"empresa_id_h1_m3\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"centro_utilidad_h1_m3\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"bodega_h1_m3\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"codigo_producto_h1_m3\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"existencia_h1_m3\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"costo_h1_m3\" value=\"\">\n";
      $this->salida .= "                            <input type=\"hidden\" id=\"lote_h1_m3\" value=\"\">\n";
	    $this->salida .= "                            <input type=\"hidden\" id=\"fecha_vencimiento_h1_m3\" value=\"\">\n";
	    $this->salida .= "                            <input type=\"button\" class=\"input-submit\" id=\"validar1\" name=\"validar\" value=\"MODIFICAR\" onclick=\"ModificarConteo3(
                                                                                                                                                                     document.getElementById('tr_h1_m3').value,
                                                                                                                                                                     document.getElementById('toma_fisica_id_h1_m3').value,
                                                                                                                                                                     document.getElementById('etiqueta_h1_m3').value,
                                                                                                                                                                     document.getElementById('num_conteo_h1_m3').value,
                                                                                                                                                                     document.getElementById('sw_manual_h1_m3').value,
                                                                                                                                                                     document.getElementById('empresa_id_h1_m3').value,
                                                                                                                                                                     document.getElementById('centro_utilidad_h1_m3').value,
                                                                                                                                                                     document.getElementById('bodega_h1_m3').value,
                                                                                                                                                                     document.getElementById('codigo_producto_h1_m3').value,
                                                                                                                                                                     document.getElementById('existencia_h1_m3').value,
                                                                                                                                                                     document.getElementById('nueva_conteo3').value,
																																									 document.getElementById('costo_h1_m3').value,
																																									 document.getElementById('lote_h1_m3').value,
																																									 document.getElementById('fecha_vencimiento_h1_m3').value);\">\n";
      $this->salida .= "                       </td>\n";
      $this->salida .= "                    </tr>\n";
      $this->salida .= "                </table>\n";
      $this->salida .= "         </form>\n";
      $this->salida .= "    </div>\n";
      $this->salida.="</div>";
      
/////////////////////////////////////////////////////////////////////////////////////////
      $mostrar6 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosTomaFisicaSC3',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'] ,"empresa_id"=>SessionGetVar("EMPRESA"),"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'] )),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $funcion = $reporte->GetJavaFunction();
      $this->salida .= $mostrar6;
      $para_reporte = $consulta->SacarNoCuadroC3($_REQUEST['toma_id']);
      //$this->salida.="<pre>".print_r($para_reporte,true)."</pre>";      

      $mostrar15 .= $csv->GetJavacriptReporte('app','InvTomaFisica','ReporteProductosTomaFisicaSC3',array("toma_fisica"=>$_REQUEST['toma_id']),'comas',array("interface"=>1,"cabecera"=>1,"nombre"=>"ReporteProductosTomaFisicaSC3-".$_REQUEST['toma_id']."","extension"=>"csv"));
      $fncn4  = $csv->GetJavaFunction();      
		$this->salida .= $mostrar15;
		$this->salida .= "	<table width=\"100%\">";
		$this->salida .= "		<td width=\"50%\">";
		$this->salida .= "                  <table class=\"modulo_table_list\" width=\"100%\">\n";
		$this->salida .= "                    <tr>\n";
		$this->salida .= "                      <td align=\"LEFT\" class=\"normal_10AN\">\n";
		$this->salida .= "							GENERAR REPORTE DE LOS PRODUCTOS DEL CONTEO 3 ";
		$this->salida .= "                      </td>\n";
		$this->salida .= "                      <td align=\"LEFT\" >\n";
		$this->salida .= "                        <a href=\"javascript:WindowPrinter0003()\" class=\"label_error\"><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS NO CUADRADOS EN CONTEO 3 \"></a>\n";
		$this->salida .= "                      </td>\n";
		$this->salida .= "                    </tr>\n";
		if($numero_conteos['numero_conteos'] =='3')
		{
		$this->salida .= "						<tr>\n";
		$this->salida .= "							<td class=\"normal_10AN\" colspan=\"2\">";
		$forma = "											<form name=\"forma_AjusteAutomatico\" id=\"forma_AjusteAutomatico\" method=\"POST\" >";
		$forma .= "												<table class=\"modulo_table_list\" width=\"100%\">\n";
		$forma .= "													<tr>";
		$forma .= "														<td colspan=\"2\" class=\"modulo_table_list_title\">";
		$forma .= "															AJUSTES AUTOMATICOS";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "													<tr>";
		$forma .= "														<td class=\"normal_10AN\">";
		$forma .= "															AJUSTE AL CONTEO 1";
		$forma .= "														</td>";
		$forma .= "														<td align=\"center\">";
		$forma .= "															<input type=\"radio\" name=\"opc\" id=\"opc0\" class=\"input-radio\" value=\"conteo_1\" checked>";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "													<tr>";
		$forma .= "														<td class=\"normal_10AN\">";
		$forma .= "															AJUSTE AL CONTEO 2";
		$forma .= "														</td>";
		$forma .= "														<td align=\"center\">";
		$forma .= "															<input type=\"radio\" name=\"opc\" id=\"opc1\" class=\"input-radio\" value=\"conteo_2\" checked>";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "													<tr>";
		$forma .= "														<td class=\"normal_10AN\">";
		$forma .= "															AJUSTE AL CONTEO 3";
		$forma .= "														</td>";
		$forma .= "														<td align=\"center\">";
		$forma .= "															<input type=\"radio\" name=\"opc\" id=\"opc2\" class=\"input-radio\" value=\"conteo_3\" checked>";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "													<tr>";
		$forma .= "														<td class=\"normal_10AN\">";
		$forma .= "															AJUSTE A LA EXISTENCIA";
		$forma .= "														</td>";
		$forma .= "														<td align=\"center\">";
		$forma .= "															<input type=\"radio\" name=\"opc\" id=\"opc1\" class=\"input-radio\" value=\"existencia\">";
		$forma .= "															<input type=\"hidden\" name=\"numero_conteos\" id=\"numero_conteos\"  value=\"".$numero_conteos['numero_conteos']."\">";
		$forma .= "															<input type=\"hidden\" name=\"toma_fisica_id\" id=\"toma_fisica_id\"  value=\"".$_REQUEST['toma_id']."\">";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "													<tr>";
		$forma .= "														<td colspan=\"2\" align=\"center\">";
		$forma .= "															<input type=\"button\" class=\"input-submit\" value=\"AJUSTE AUTOMATICO\" onclick=\"xajax_AjustesAutomaticos(xajax.getFormValues('forma_AjusteAutomatico'));\">";
		$forma .= "														</td>";
		$forma .= "													</tr>";
		$forma .= "												</table>";
		$forma .= "											</form>";
		$this->salida .= "							".$forma;
		$this->salida .= "							</td>";
		$this->salida .= "						</tr>\n";
		}
		$this->salida .= "                  </table>\n";
		$this->salida .= "		</td>";
		$this->salida .= "		<td width=\"50%\">";
		$html  = "							<form name=\"FormaConteo3_NC\" id=\"FormaConteo3_NC\" method=\"POST\" action=\"\">";
		$html .= "								<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
		$html .= "									<tr class=\"formulacion_table_list\">";
		$html .= "										<td colspan=\"6\" >";
		$html .= "											BUSCADOR";
		$html .= "										</td>";
		$html .= "									</tr>";
		$html .= "									<tr class=\"modulo_table_list_title\">";
		$html .= "										<td align=\"left\">";
		$html .= "											ETIQUETA";
		$html .= "										</td>";
		$html .= "										<td class=\"modulo_list_oscuro\">";
		$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[etiqueta]\" id=\"etiqueta\" style=\"width:100%\">";
		$html .= "										</td>";
		$html .= "										<td align=\"left\">";
		$html .= "											DESCRIPCION";
		$html .= "										</td>";
		$html .= "										<td class=\"modulo_list_oscuro\">";
		$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[producto]\" id=\"etiqueta\" style=\"width:100%\">";
		$html .= "										</td>";
		$html .= "										<td align=\"left\">";
		$html .= "											CLASE";
		$html .= "										</td>";
		$html .= "										<td class=\"modulo_list_oscuro\">";
		$html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[clase_descripcion]\" id=\"etiqueta\" style=\"width:100%\">";
		$html .= "										</td>";
		$html .= "									</tr>";
		$html .= "									<tr class=\"formulacion_table_list\">";
		$html .= "										<td colspan=\"6\">";
		$html .= "											<input class=\"input-submit\" type=\"button\" value=\"BUSCAR\" onclick=\"SacarNoCuadraConteo3('".$_REQUEST['toma_id']."',xajax.getFormValues('FormaConteo3_NC'),'".$numero_conteos['numero_conteos']."','1');\">";
		$html .= "										</td>";
		$html .= "									</tr>";
		$html .= "								</table>";
		$html .= "							</form>";
		$this->salida .= $html;
		$this->salida .= "		</td>";
		$this->salida .= "	</table>";
		$this->salida .= "<br>";
      $this->salida .= "<form name=\"conteo_3\" id=\"conteo_3\" action=\"\" method=\"post\">";
      $this->salida .= "                <div id=\"InfoSinConteo3\">\n";
      $this->salida .= "                </div>\n";
      if(!empty($para_reporte))
      {
        /*$this->salida .= "  <table width=\"100%\" BORDER='0' align=\"center\">\n";
        $this->salida .= "   <tr>\n";
        $this->salida .= "    <td  align=\"center\">\n";
        $this->salida .= "	     <a href=\"javascript:".$fncn4."\" class=\"label_error\">GENERAR REPORTE PRODUCTOS CONTEO 3 CSV\n";
        $this->salida .= "      <sub><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR ARCHIVO DE LOS PRODUCTOS CONTEO 3 \"></sub>";
        $this->salida .= "      </a>\n";
        $this->salida .= "    </td>\n";
        $this->salida .= "   </tr>\n";
        $this->salida .= "  </table>\n";*/
      }
      $this->salida .= "</form>";
       
      $this->salida .= "              </div>\n";
//////////////////////////////////////////FIN CONTEO 3 SIN VALIDAR///////////////////////////
      $this->salida .= "                <div class=\"tab-page\" id=\"Sin_contar\">\n";
      $this->salida .= "                  <h2 id=\"SinContix\" class=\"tab\">PRODUCTOS SIN CONTEO</h2>\n";
      $this->salida .= "                  <script>  tabPane.addTabPage( document.getElementById(\"Sin_contar\")); </script>\n";
      
	  $html  = "							<form name=\"FormaBuscarSinConteo\" id=\"FormaBuscarSinConteo\" method=\"POST\" action=\"\">";
	  $html .= "								<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
	  $html .= "									<tr class=\"formulacion_table_list\">";
	  $html .= "										<td colspan=\"6\" >";
	  $html .= "											BUSCADOR";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr class=\"modulo_table_list_title\">";
	  $html .= "										<td align=\"left\">";
	  $html .= "											ETIQUETA";
	  $html .= "										</td>";
	  $html .= "										<td class=\"modulo_list_oscuro\">";
	  $html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[etiqueta]\" id=\"etiqueta\" style=\"width:100%\">";
	  $html .= "										</td>";
	  $html .= "										<td align=\"left\">";
	  $html .= "											DESCRIPCION";
	  $html .= "										</td>";
	  $html .= "										<td class=\"modulo_list_oscuro\">";
	  $html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[producto]\" id=\"etiqueta\" style=\"width:100%\">";
	  $html .= "										</td>";
	  $html .= "										<td align=\"left\">";
	  $html .= "											CLASE";
	  $html .= "										</td>";
	  $html .= "										<td class=\"modulo_list_oscuro\">";
	  $html .= "											<input type=\"text\" class=\"input-text\" name=\"buscador[clase_descripcion]\" id=\"etiqueta\" style=\"width:100%\">";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr class=\"formulacion_table_list\">";
	  $html .= "										<td colspan=\"6\">";
	  $html .= "											<input class=\"input-submit\" type=\"button\" value=\"BUSCAR\" onclick=\"SacarSinConteo('".$_REQUEST['toma_id']."',xajax.getFormValues('FormaBuscarSinConteo'),'1');\">";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "								</table>";
	  $html .= "							</form>";
	  $this->salida .= $html;
	  $this->salida .= "                <div id=\"SinConteox\">\n";
      $this->salida .= "                </div>\n";
      $this->salida .= "              </div>\n";
////////////////////////////////////////fin sin_contar////////////////////////////

      $this->salida .= "                <div class=\"tab-page\" id=\"Cierre\">\n";
      $this->salida .= "                  <h2 id=\"Cierre\" class=\"tab\">CIERRE</h2>\n";
      $this->salida .= "                  <script>  tabPane.addTabPage( document.getElementById(\"Cierre\")); </script>\n";
        $this->salida .= " <div id='ContenedorDocumentos' class='d2Container' style=\"display:none;\">";
      $this->salida .= "    <div id='tituloDocumentos' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "    <div id='cerrarDocumentos' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorDocumentos');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $this->salida .= "    <div id='errorDocumentos' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $this->salida .= "    <div id='ContenidoDocumentos'  class='d2Content' style='z-index:10;'>\n";
      $this->salida .= "    </div>\n";
      $this->salida .= " </div>\n";   
      $this->salida .= "                <div id='ContenedorB5' class='d2Container' style=\"display:none;\">";
      $this->salida .= "                  <div id='tituloB5' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
      $this->salida .= "                  <div id='cerrarB5' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorB5');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
      $this->salida .= "                  <div id='errorB5' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $this->salida .= "                  <div id='ContenidoB5'  class='d2Content' style='z-index:10;'>\n";
      $this->salida .= "                  </div>\n";
      $this->salida .= "                </div>\n";
     /*  $csv = Autocarga::factory("ReportesCsv");
      $mostrar16 .= $csv->GetJavacriptReporte('app','InvTomaFisica','ReporteAjusteAutomatico',array("toma_fisica"=>$_REQUEST['toma_id']),'comas',array("interface"=>1,"cabecera"=>1,"nombre"=>"ReporteAjusteAutomatico-".$_REQUEST['toma_id']."","extension"=>"csv"));
      $fncn1  = $csv->GetJavaFunction();      
      $this->salida .= $mostrar16;  */
      $this->salida .= "                <div id=\"Cierrex\">\n";
       $this->salida .= "                </div>\n"; 
      /* $this->salida .= "  <table width=\"100%\" BORDER='0' align=\"center\">\n";
      $this->salida .= "   <tr>\n";
      $this->salida .= "    <td  align=\"center\">\n";
      $this->salida .= "	     <a href=\"javascript:".$fncn1."\" class=\"label_error\">GENERAR REPORTE AUTOMATICO CSV\n";
      $this->salida .= "      <sub><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR ARCHIVO DE LOS PRODUCTOS AUTOMATICO \"></sub>";
      $this->salida .= "      </a>\n";
      $this->salida .= "    </td>\n";
      $this->salida .= "   </tr>\n";
      $this->salida .= "  </table>\n";*/
     
      $this->salida .= "                </div>\n";
      $this->salida .= "              </div>\n";
      $mostrar7 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosExvsC1',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'] ,"empresa_id"=>SessionGetVar("EMPRESA"),"centro_utilidad"=>$_REQUEST['centro_utlidad'],"bodega"=>$_REQUEST['bodega'] )),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $funcion = $reporte->GetJavaFunction();
      $this->salida .= $mostrar7;
      $mostrar8 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosExvsC2',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'] ,"empresa_id"=>SessionGetVar("EMPRESA"),"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'] )),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $funcion1 = $reporte->GetJavaFunction();
      $this->salida .= $mostrar8;
      $mostrar9 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosExvsC3',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'] ,"empresa_id"=>SessionGetVar("EMPRESA"),"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'] )),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $funcion1 = $reporte->GetJavaFunction();
      $this->salida .= $mostrar9;
      $mostrar10 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosSistVsTF',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'] ,"empresa_id"=>SessionGetVar("EMPRESA"),"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'] )),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      //$mostrar4 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteAjusteAutomatico',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'] ,"empresa_id"=>SessionGetVar("EMPRESA"),"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'] )),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $funcion1 = $reporte->GetJavaFunction();
      $this->salida .= $mostrar10;
      $mostrar11 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteAjusteAutomatico',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'] ,"empresa_id"=>SessionGetVar("EMPRESA"),"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'] )),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $funcion1 = $reporte->GetJavaFunction();
      $this->salida .= $mostrar11;
      $mostrar12 = $reporte->GetJavaReport('app','InvTomaFisica','ReporteFinalConteos',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'] ,"empresa_id"=>SessionGetVar("EMPRESA"),"centro_utilidad"=>$_REQUEST['centro_utilidad'],"bodega"=>$_REQUEST['bodega'] )),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $funcion1 = $reporte->GetJavaFunction();
      $this->salida .= $mostrar12;
      /*$mostrar = $reporte->GetJavaReport('app','InvTomaFisica','ReporteProductosTomaFisicaSC2',array("datos"=>array("toma_fisica"=>$_REQUEST['toma_id'] ,"empresa_id"=>SessionGetVar("EMPRESA"),"centro_utilidad"=>'01',"bodega"=>$_REQUEST['bodega'] )),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $funcion1 = $reporte->GetJavaFunction();
      $this->salida .= $mostrar;*/
      
      
      
      $this->salida .= "                <div id=\"SistemaVsTomaFisica\">\n";
      $this->salida .= "                </div>\n";
      
      
      $this->salida .= "            </td>\n";
      $this->salida .= "          </tr>\n";
      $this->salida .= "        </table>\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "  </table>\n";
      $Salir=ModuloGetURL('app','InvTomaFisica','user','Admon');
      $this->salida .= "<form name=\"volver\" action=\"".$Salir."\" method=\"post\">";
      $this->salida .= "  <table width=\"100%\" align=\"center\">\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "      <td align=\"center\">\n";
      $this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\">\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "  </table>\n";
      $this->salida .= "</form>\n";
      $this->salida .= "<script type=\"text/javascript\">\n";
//       $this->salida .= "  setupAllTabs();\n";
       $this->salida .= "  var html1 = document.getElementById('Contix1').innerHTML;\n";
       $this->salida .= "  html1 = html1.replace(\"#\",\"javascript:InfoConteo1('1','".$_REQUEST['toma_id']."',xajax.getFormValues('FormaConteo1_'),'".$numero_conteos['numero_conteos']."','1');\");\n";
       $this->salida .= "  document.getElementById('Contix1').innerHTML = html1;\n";
       $this->salida .= "  var html1 = document.getElementById('Contix2').innerHTML;\n";
       $this->salida .= "  html1 = html1.replace(\"#\",\"javascript:SacarConteo2('2','".$_REQUEST['toma_id']."',xajax.getFormValues('FormaConteo2_'),'".$numero_conteos['numero_conteos']."','1');\");\n";
       $this->salida .= "  document.getElementById('Contix2').innerHTML = html1;\n";
       $this->salida .= "  var html1s = document.getElementById('NoCuadro1').innerHTML;\n";
       $this->salida .= "  html1s = html1s.replace(\"#\",\"javascript:SacarNoCuadraConteo1('".$_REQUEST['toma_id']."',xajax.getFormValues('FormaConteo1_NC'),'".$numero_conteos['numero_conteos']."','1');\");\n";
       $this->salida .= "  document.getElementById('NoCuadro1').innerHTML = html1s;\n";
       $this->salida .= "  var html1s = document.getElementById('NoCuadro2').innerHTML;\n";
       $this->salida .= "  html1s = html1s.replace(\"#\",\"javascript:SacarNoCuadraConteo2('".$_REQUEST['toma_id']."',xajax.getFormValues('FormaConteo2_NC'),'".$numero_conteos['numero_conteos']."','1')\");\n";
       $this->salida .= "  document.getElementById('NoCuadro2').innerHTML = html1s;\n";
       $this->salida .= "  var html1s = document.getElementById('NoCuadra3').innerHTML;\n";
       $this->salida .= "  html1s = html1s.replace(\"#\",\"javascript:SacarNoCuadraConteo3('".$_REQUEST['toma_id']."',xajax.getFormValues('FormaConteo3_NC'),'".$numero_conteos['numero_conteos']."','1')\");\n";
       $this->salida .= "  document.getElementById('NoCuadra3').innerHTML = html1s;\n";
       

       $this->salida .= "  var html2 = document.getElementById('Contix3').innerHTML;\n";
       $this->salida .= "  html2 = html2.replace(\"#\",\"javascript:SacarConteo3('3','".$_REQUEST['toma_id']."',xajax.getFormValues('FormaConteo3_'),'".$numero_conteos['numero_conteos']."','1');\");\n";
       $this->salida .= "  document.getElementById('Contix3').innerHTML = html2;\n";
       $this->salida .= "  var html3 = document.getElementById('SinContix').innerHTML;\n";

       $this->salida .= "  html3 = html3.replace(\"#\",\"javascript:SacarSinConteo('".$_REQUEST['toma_id']."',xajax.getFormValues('FormaBuscarSinConteo'),'1')\");\n";
       $this->salida .= "  document.getElementById('SinContix').innerHTML = html3;\n";
       $this->salida .= "  var html4 = document.getElementById('Cierre').innerHTML;\n";
       $this->salida .= "  html4 = html4.replace(\"#\",\"javascript:MostrarCierre('".$_REQUEST['toma_id']."','".trim(SessionGetVar("EMPRESA"))."','".trim($_REQUEST['centro_utilidad'])."','".trim($_REQUEST['bodega'])."','".UserGetUID()."','".$numero_conteos['numero_conteos']."')\");\n";
       //$this->salida .= "  var html5 = document.getElementById('Cierre').innerHTML;\n";
       //$this->salida .= "  html5 = html5.replace(\"#\",\"javascript:SistemaVsTomaFisica('".$_REQUEST['toma_id']."')\");\n";
       //tabPane.setSelectedIndex(NUMERO); ese el numero de la pestaña
       $this->salida .= "  document.getElementById('Cierre').innerHTML = html4;\n";
       $this->salida .= "  tabPane.setSelectedIndex('0');";
       $this->salida.="
                            function mOvr(src,clrOver) 
                              {
                                  src.style.background = clrOver;
                              }
                          function mOut(src,clrIn)
                              {
                                  src.style.background = clrIn;
                              }
                          </script>";
      $this->salida .= ThemeCerrarTabla(); 
      return true;
    }





  
/**********************************************************************************************
*
***********************************************************************************************/
function CapturaTomaFisicaLogueo()
 {
    $path = SessionGetVar("rutaImagenes");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");
    $consulta= new TomaFisicaSQL();
    $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
    $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
    $this->SetXajax(array(),$file);
    $this->salida .= ThemeAbrirTabla("CONTEO DE TOMAS FISICA");
    $accion1=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisica');
    $this->salida .="    <div id='error_en_mov' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "                 <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td width=\"15%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          EMPRESA";
    $this->salida .= "                       </td>\n";
    $empresa_nom=$consulta->ColocarEmpresa(SessionGetVar("EMPRESA"));
    $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                        ".$empresa_nom[0]['razon_social'];
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td width=\"30%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          USUARIO ID";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                        ".UserGetUID();
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                       <td width=\"30%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          NOMBRE USUARIO";
    $this->salida .= "                       </td>\n";
    $usuario_idx=$consulta->BuscarUsuario(UserGetUID());
    $this->salida .= "                       <td align=\"left\">\n";
    $this->salida .= "                        ".$usuario_idx[0]['nombre'];
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                 </table>";
    $this->salida .= "                 <br>";
    $tomas=$consulta->SacarTomaFisica(UserGetUID(),TRUE,$empresa_nom[0]['empresa_id']);
    //var_dump($tomas);
    $this->salida .="                  <div id='refresh'>";
    if(!EMPTY($tomas))
    {
    
        $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
        $this->salida .= "                          TOMA FISICA ID";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"25%\" align=\"center\">\n";
        $this->salida .= "                          DESCRIPCION";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"7%\" align=\"center\">\n";
        $this->salida .= "                         <a title='CENTRO DE UTILIDAD'>\n";
        $this->salida .= "                          CEN_UTIL";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"7%\" align=\"center\">\n";
        $this->salida .= "                         <a title='NUMERO DE CONTEOS'>\n";
        $this->salida .= "                          CONTEOS";
        $this->salida .= "                         </a>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"17%\" align=\"center\">\n";
        $this->salida .= "                         <a title='BODEGA ID'>\n";
        $this->salida .= "                          BODEGA";
        $this->salida .= "                         <a>";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"7%\" align=\"center\">\n";
        $this->salida .= "                         <a title='CANTIDAD DE PRODUCTOS'>PRODUCTOS\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"27%\" align=\"center\">\n";
        $this->salida .= "                          OBSERVACION";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
        $this->salida .= "                          ACCIONES";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        
        for($i=0;$i<count($tomas);$i++)
        { 
          $this->salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
          $this->salida .= "                       <td align=\"center\">\n";
          $this->salida .= "                       ".$tomas[$i]['toma_fisica_id'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['descripcion'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                      <td align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['centro_utilidad'];
          $this->salida .= "                      </td>\n";
          $this->salida .= "                       <td align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['numero_conteos'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['nom_bodega'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['cantidad_reg'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['observacion'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td  align=\"center\">\n";
          $CONTEO=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisica',array('toma_id'=>$tomas[$i]['toma_fisica_id'],'registros'=>$tomas[$i]['cantidad_reg'],'bodegax'=>$tomas[$i]['nom_bodega']));
          $this->salida .= "                         <a title='TOMA FISICA ".$tomas[$i]['descripcion']."' href=\"".$CONTEO."\">";
          $this->salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
          $this->salida .= "                         </a>\n";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                    </tr>\n";
        }
          $this->salida .= "                 </table>";
    }  
    $this->salida .= "            </div>\n";
    $this->salida .= "               <br>";
     
    
    $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','MenuTomaFisica');
    $this->salida .= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
    $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "       <td align=\"center\" colspan='7'>\n";
    $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $this->salida .= "       </td>\n";  
    $this->salida .= "    </tr>\n"; 
    $this->salida .= "  </table>\n"; 
    $this->salida .= " </form>\n"; 
    $this->salida.="<script language=\"javaScript\">
      function mOvr(src,clrOver) 
        {
            src.style.background = clrOver;
        }
     function mOut(src,clrIn)
        {
            src.style.background = clrIn;
        }
      </script>";
    $this->salida .= ThemeCerrarTabla();
    return true;
 }
     
/******************************************************************************************
*Forma toma fisica
*******************************************************************************************/    
 function CapturaTomaFisica()
 { 
    $path = SessionGetVar("rutaImagenes");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");
    $clas = AutoCarga ::factory("ClaseUtil");
    $this->salida.= $clas->IsNumeric();
    $this->salida.= $clas->AcceptNum(false,false);
    $this->salida.= $clas->AcceptDate("-");
    $consulta= new TomaFisicaSQL();
    
    $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
    $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
    $this->SetXajax(array("BuscarProducto1","EliminarCapturaTr","llamarLista","BuscarProducto","Ins_conteo","ListaProductos","AdicionarLoteFV","MoficarFechaLote","UbicacionN2","UbicacionN3","UbicacionN4"),$file,"ISO-8859-1");

    $javaC = "<script>\n";
    $javaC .= "   var contenedor1=''\n";
    $javaC .= "   var titulo1=''\n";
    $javaC .= "   var hiZ = 2;\n";
    $javaC .= "   var DatosFactor = new Array();\n";
    $javaC .= "   var EnvioFactor = new Array();\n";
    $javaC .= "   function Iniciar4(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorBus';\n";
    $javaC .= "       titulo1 = 'tituloBus';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 900, 500);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/11, xScrollTop()+100);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 880, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarBus');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 880, 0);\n";
    $javaC .= "   }\n";
    $javaC .= "   function IniciarAj(tit)\n";
    $javaC .= "   {\n";
    $javaC .= "       contenedor1 = 'ContenedorAj';\n";
    $javaC .= "       titulo1 = 'tituloAj';\n";
    $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
    $javaC.= "        Capa = xGetElementById(contenedor1);\n"; 
    $javaC .= "       xResizeTo(Capa, 600, 500);\n";
    $javaC .= "       xMoveTo(Capa, xClientWidth()/11, xScrollTop()+100);\n";
    $javaC .= "       ele = xGetElementById(titulo1);\n";
    $javaC .= "       xResizeTo(ele, 580, 20);\n";
    $javaC .= "       xMoveTo(ele, 0, 0);\n";
    $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $javaC .= "       ele = xGetElementById('cerrarAj');\n";
    $javaC .= "       xResizeTo(ele, 20, 20);\n";
    $javaC .= "       xMoveTo(ele, 580, 0);\n";
    $javaC .= "   }\n";
    $javaC.= "</script>\n";
    $this->salida.= $javaC;
    $javaC1.= "<script>\n";
    $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
    $javaC1 .= "   {\n";
    $javaC1 .= "     window.status = '';\n";
    $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
    $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
    $javaC1 .= "     ele.myTotalMX = 0;\n";
    $javaC1 .= "     ele.myTotalMY = 0;\n";
    $javaC1 .= "   }\n";
    $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";
    $javaC1 .= "   {\n";
    $javaC1 .= "     if (ele.id == titulo1) {\n";
    $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
    $javaC1 .= "     }\n";
    $javaC1 .= "     else {\n";
    $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
    $javaC1 .= "     }  \n";
    $javaC1 .= "     ele.myTotalMX += mdx;\n";
    $javaC1 .= "     ele.myTotalMY += mdy;\n";
    $javaC1 .= "   }\n";
    $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
    $javaC1 .= "   {\n";
    $javaC1 .= "   }\n";
    $javaC1.= "function MostrarCapa(Elemento)\n";
    $javaC1.= "{\n";
    $javaC1.= "    capita = xGetElementById(Elemento);\n";
    $javaC1.= "    capita.style.display = \"\";\n";
    $javaC1.= "}\n";
    $javaC1.= "function Cerrar(Elemento)\n";
    $javaC1.= "{\n";
    $javaC1.= "    capita = xGetElementById(Elemento);\n";          
    $javaC1.= "    capita.style.display = \"none\";\n";          
    $javaC1.= "}\n";
	
	
	
	
    $javaC1.= "function GuardarCantidadPro(i)\n";
    $javaC1.= "{\n"; 
    //$javaC1.= "alert(i);\n";
    $javaC1.= " vcantidad=document.getElementById('cantidad');\n";
   
    $javaC1.= "     xajax_Ins_conteo(document.getElementById('toma_idx').value,
                                                                                          document.getElementById('etiqueta_h').value,
                                                                                          document.getElementById('num_conteo').value,
                                                                                          xajax.getFormValues('add_movimiento'),                                                                                          '',
                                                                                          document.getElementById('n_lista_h').value, 
                                                                                          document.getElementById('cap_max').value,
                                                                                          document.getElementById('cuantos').value);\n";
    
    $javaC1.= "}\n"; 
    
     
      $imagen = GetThemePath() . "/images/calendario/calendario.png";
      $javaC1 .= "  var ident1;\n";
      $javaC1 .= "  var ident2;\n";
      $javaC1 .= "  function Mostrar_FechaVen(fecha_venci,calendario_pxfecha_venci)\n";
      $javaC1 .= "  {\n";
      $javaC1 .= "    ident1 = fecha_venci;\n";
      $javaC1 .= "    ident2 = calendario_pxfecha_venci;\n";
      $javaC1 .= "    var dia = '';\n";
      $javaC1 .= "    var mes = '';\n";
      $javaC1 .= "    var anyo = '';\n";
      $javaC1 .= "    var valor = '';\n";
      $javaC1 .= "    try{\n";
      $javaC1 .= "      valor = document.getElementById(fecha_venci).value;\n";
      $javaC1 .= "    }catch(error){}\n";
      $javaC1 .= "    if(valor.length == 10)\n";
      $javaC1 .= "    {\n";
      $javaC1 .= "      dia = valor.split('-')[0];\n";
      $javaC1 .= "      mes = parseInt(valor.split('-')[1]) -1;\n";
      $javaC1 .= "      if(mes == -1)\n";
      $javaC1 .= "      {\n";
      $javaC1 .= "        if(valor.split('-')[1] == '08')\n";
      $javaC1 .= "          mes = 7;\n";      
      $javaC1 .= "        else if(valor.split('-')[1] == '09')\n";
      $javaC1 .= "          mes = 8;\n";
      $javaC1 .= "      }\n";
      $javaC1 .= "      anyo = valor.split('-')[2];\n";
      $javaC1 .= "    }\n";
      $javaC1 .= "    CrearCalendario('fecha_venci','-',dia,mes,anyo);\n";
      $javaC1 .= "  }\n";
      $javaC1 .= "  function Ocultar_fecha_venci(fecha)\n";
      $javaC1 .= "  {\n";
      $javaC1 .= "    if(fecha != '')\n";
      $javaC1 .= "      document.getElementById(ident1).value = fecha;\n";
      $javaC1 .= "    document.getElementById(ident2).style.visibility = 'hidden';\n";
      $javaC1 .= "  }\n";
   
    $javaC1.= "</script>\n";
    $this->salida.= $javaC1;        
                    
    
 
/*******************************************************************************
*Ventana emergente 3 aqui es cuando se modifica una cuenta. 
**********************************************************************************/
    $this->salida.="<div id='ContenedorBus' class='d2Container' style=\"display:none\">";
    $this->salida .= "    <div id='tituloBus' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $this->salida .= "    <div id='cerrarBus' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorBus');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "    <div id='errorBus' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "    <div id='ContenidoBus'>\n";


    /**************************************/


	$salida = "                 <form name=\"jukilo\" action=\"".$accion1."\" method=\"post\">\n";         
	$salida .= "                 <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";         
	$salida .= "                    <tr class=\"modulo_table_list_title\">\n";
	$salida .= "                       <td COLSPAN='2' align=\"center\">\n";
	$salida .= "                          BUSCADOR DE PRODUCTOS";
	$salida .= "                       </td>\n";
	$salida .= "                    </tr>\n";
	$salida .= "                    <tr class=\"modulo_table_list_title\">\n";
	$salida .= "                       <td width=\"35%\" align=\"center\">\n";
	$salida .= "                          TIPO DE BUSQUEDA";
	$salida .= "                       <select id=\"tip_bus\" name=\"tip_bus\" class=\"select\" onchange=\"Aplicar(this.value)\">";
	$salida .= "                           <option value=\"8\" SELECTED># CODIGO</option> \n";
	$salida .= "                           <option value=\"2\">DESCRIPCION</option> \n";
	$salida .= "                           <option value=\"3\">FECHA VENCIMIENTO</option> \n";
	$salida .= "                           <option value=\"4\">LOTE</option> \n";
	$salida .= "                           <option value=\"5\">CODIGO BARRAS</option> \n";
	$salida .= "                           <option value=\"6\">MOLECULA</option> \n";
	$salida .= "                           <option value=\"7\">CODIGO INTERNO</option> \n";
	$salida .= "                       </select>\n";
	$salida .= "                       </td>\n";
	$salida .= "                       <td width=\"55%\" align=\"left\" id=\"ventanatabla\">\n";
	$salida .= "                          DESCRIPCION";                                                                                                             
	$salida .= "                          <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"30\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";//
	//$salida .= "                          <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"30\" onkeypress=\"return acceptNum(event);\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";//
	$salida .= "                       </td>\n";
	$salida .= "                    </tr>\n";
	$salida .= "                </table>\n";
	$salida .= "                 </form>\n";
	$salida .= "                 <br>\n";
	$salida .="              <div id=\"tabelos\">";
	$salida .="              </div>\n";
	$salida .= "   </div>\n";     
	$salida.="</div>";
    /******************************************/

    $this->salida .=$salida;
    $this->salida .= "    </div>\n";     
    $this->salida.="</div>";

	/*$salida .= "<table width=\"95%\ align=\"center\" class=\"modulo_table_list\" >\n";
	$salida .= "                       <td COLSPAN='2' align=\"center\">\n";
	$salida .= "                          PRUEBA";
	$salida .= "                       </td>\n";
	$salida .= "                    <tr class=\"modulo_table_list_title\">\n";
	$salida .= "                       <td width=\"35%\" align=\"center\">\n";
	$salida .= "                          TIPO DE BUSQUEDA";
	$salida .= "                       <select id=\"tip_bus\" name=\"tip_bus\" class=\"select\" onchange=\"Aplicar(this.value)\">";
	$salida .= "                           <option value=\"1\" SELECTED># CODIGO</option> \n";
	$salida .= "                           <option value=\"2\">DESCRIPCION</option> \n";
	$salida .= "                           <option value=\"3\">FECHA VENCIMIENTO</option> \n";
	$salida .= "                           <option value=\"4\">LOTE</option> \n";
	$salida .= "                       </select>\n";
	$salida .= "                    </tr>\n";
	$salida .= "                       </td>\n";
	$salida .="</div>";*/
/**************************************************************************************
*final de la ventana3
***********************************************************************************/

    $this->salida .= ThemeAbrirTabla("CAPTURA TOMA FISICA");
    $numero_conteos=$consulta->ObtenerNumeroConteos($_REQUEST['toma_id']);
	
    $numero=$consulta->GetNumeroLista($_REQUEST['toma_id'],'1');
    $accion1=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisica');
    $this->salida .= "            <form id=\"add_movimiento\" name=\"add_movimiento\" action=\"".$accion1."\" method=\"post\">\n";
    $this->salida .= "                <input type=\"hidden\" id=\"toma_idx\" name=\"toma_idx\" value=\"".$_REQUEST['toma_id']."\">\n";//
    $this->salida .= "                <input type=\"hidden\" id=\"n_lista_h\" value=\"".$numero."\">\n";
    $this->salida .= "                <input type=\"hidden\" id=\"etiqueta_h\" value=\"\">\n";
    $this->salida .= "                <input type=\"hidden\" id=\"des_producto_h\" value=\"\">\n";
    $this->salida .= "                <input type=\"hidden\" id=\"unidad_h\" value=\"\">\n";
    $this->salida .= "                <input type=\"hidden\" id=\"codigo_h\" value=\"\">\n";
    $this->salida .= "                <input type=\"hidden\" id=\"num_conteo_h\" value=\"\">\n";
    $this->salida .= "                <input type=\"hidden\" id=\"cuantos\" value=\"0\">\n";
    $this->salida .= "                <input type=\"hidden\" id=\"farmacologico_h\" value=\"0\">\n";
    $this->salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td COLSPAN='5' align=\"center\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          DATOS DEL PRODUCTO";
    $this->salida .= "                      </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td width=\"20%\" align=\"center\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          ETIQUETA";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"30%\" align=\"center\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                          <input type=\"text\" class=\"input-text2\" id=\"etiqueta\" name=\"etiqueta\" value=\"\" size=\"8\" onkeypress=\"return acceptNum(event);\" onkeydown=\"recogerTecla(event)\" onclick=\"\">\n";//
    $java = "javascript:MostrarCapa('ContenedorBus');Bus_Pro('".$_REQUEST['toma_id']."','0','0','1');Iniciar4('BUSCAR PRODUCTO');\"";
    $this->salida .= "                         <a title='BUSCADOR ETIQUETA' href=\"".$java."\">\n";
    $this->salida .= "                          <sub><img src=\"".$path."/images/auditoria.png\" border=\"0\" width=\"19\" height=\"24\"></sub>\n";
    $this->salida .= "                         </a>\n";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                      <td width=\"20%\" align=\"center\" class=\"modulo_table_list_title\">\n\n";
    $this->salida .= "                       CODIGO DE BARRAS\n";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"30%\" align=\"center\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                         <input type=\"text\" class=\"input-text2\" id=\"codigo_barras\" name=\"codigo_barras\" size=\"16\" onkeypress=\"return acceptNum(event);\" onkeydown=\"recogerTecla(event)\" onclick=\"\">\n";
    $java = "javascript:MostrarCapa('ContenedorBus');Bus_Pro('".$_REQUEST['toma_id']."','0','0','1');Iniciar4('BUSCAR PRODUCTO');\"";
    $this->salida .= "                        <a title='BUSCADOR CODIGO DE BARRAS' href=\"".$java."\">\n\n";
    $this->salida .= "                       <sub><img src=\"".$path."/images/auditoria.png\" border=\"0\" width=\"19\" height=\"24\"></sub>\n";
    $this->salida .= "                       </a>\n";
    $this->salida .= "                       </td>\n";
    // $this->salida .= "                          <input type=\"text\" class=\"input-text2\" id=\"etiqueta\" name=\"etiqueta\"
    $this->salida .= "<input type=\"hidden\" id=\"cantidad\" name=\"cantidad\">\n";
    //$this->salida .= "                       \n";
    /*$this->salida .= "                       <td width=\"20%\" align=\"center\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          CANTIDAD";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"20%\" align=\"center\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                          <input type=\"text\" class=\"input-text2\" id=\"cantidad\" name=\"cantidad\" size=\"10\" DISABLED onkeypress=\"return acceptNum(event);\" onkeydown=\"recogerTeclas1(event)\">\n";//
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"10%\" align=\"center\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                        \n";//
    $this->salida .= "                       </td>\n";*/
    /*$xsalida .= "                       <td width=\"20%\" align=\"center\" class=\"modulo_table_list_title\">\n";
    $xsalida .= "                          CODIGO DE BARRAS";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                       <td width=\"30%\" align=\"center\" class=\"modulo_list_claro\">\n";
    $xsalida .= "                          <input type=\"text\" class=\"input-text2\" id=\"codigo_barras\" name=\"codigo_barras\" size=\"8\" onkeypress=\"return acceptNum(event);\" onkeydown=\"recogerTecla(event)\" onclick=\"\">\n";//
    $java = "javascript:MostrarCapa('ContenedorBus');Bus_Pro('".$_REQUEST['toma_id']."','0','0','1');Iniciar4('BUSCAR PRODUCTO');\"";
    $xsalida .= "                         <a title='BUSCADOR CODIGO DE BARRAS' href=\"".$java."\">\n";
    $xsalida .= "                          <sub><img src=\"".$path."/images/auditoria.png\" border=\"0\" width=\"19\" height=\"24\"></sub>\n";
    $xsalida .= "                         </a>\n";
    $xsalida .= "                       </td>\n";*/
    $this->salida .= "                    </tr>\n";
    $this->salida .= "           <div id=\"cen_cost\">";

    $xsalida .= "                    <tr>\n";

    $xsalida .= "                       <td  align=\"center\" class=\"modulo_table_list_title\">\n";
    $xsalida .= "                          DESCRIPCION";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                       <td  COLSPAN='4' align=\"left\" class=\"modulo_list_claro\" id=\"des_producto\">\n";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                    </tr>\n";
    $xsalida .= "                    <tr>\n";
    $xsalida .= "                       <td  align=\"center\" class=\"modulo_table_list_title\">\n";
    $xsalida .= "                          UNIDAD";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                       <td  align=\"left\" class=\"modulo_list_claro\" id=\"unidad\">\n";
    $xsalida .= "                         ";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                       <td  align=\"left\" class=\"modulo_table_list_title\">\n";
    $xsalida .= "                          CONTENIDO u/v";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                       <td  colspan='2' align=\"left\" class=\"modulo_list_claro\" id=\"contenido\">\n";
    $xsalida .= "                         <label class=\"normal_10N\"></label> ";
    $xsalida .= "                       </td>\n";

    $xsalida .= "                    </tr>\n";
    $xsalida .= "                    <tr>\n";
    $xsalida .= "                       <td  align=\"center\" class=\"modulo_table_list_title\">\n";
    $xsalida .= "                         CODIGO";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                       <td  align=\"left\" class=\"modulo_list_claro\" id=\"codigo\">\n";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                       <td  align=\"center\" class=\"modulo_table_list_title\">\n";
    $xsalida .= "                         FORMA FARMACOLOGICO";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                       <td  align=\"left\" class=\"modulo_list_claro\" id=\"farmacologico\">\n";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                     </tr>\n";
    $xsalida .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
    $xsalida .= "                         NUM CONTEO";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                       <td  WIDTH=\"40%\" align=\"left\" class=\"modulo_list_claro\">\n";
    $xsalida .= "                         <select id=\"num_conteo\" name=\"num_conteo\" class=\"select\" onchange=\"SetarNumConteo(document.getElementById('num_conteo').value)\">";
    for($c=1;$c<=$numero_conteos['numero_conteos'];$c++)
	$xsalida .= "                           <option value=\"".$c."\">".$c."</option> \n";
    /*$xsalida .= "                           <option value=\"2\">2</option> \n";
    $xsalida .= "                           <option value=\"3\">3</option> \n";*/
    $xsalida .= "                         </select>\n";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                       <input type=\"hidden\" id=\"conteo_cristian\" value=\"\">\n";
    $xsalida .= "                       <input type=\"hidden\" id=\"etiqueta_CodBar\" value=\"\">\n";  //para pruebas
    //$xsalida .= "                       <input type=\"hidden\" id=\"prueba_etiqueta\" value=\"\">\n";  //para pruebas
    $xsalida .= "                       <td align=\"center\" class=\"modulo_list_claro\" id=\"num_conteo_\" colspan=\"2\">\n";
    $xsalida .= "                       ";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                     </tr>\n";
    //$xsalida.="<pre>".print_r($_REQUEST,true)."</pre>";
    $this->salida .= $xsalida;
    $this->salida .="                 </div>\n";
    $this->salida .="                 </form>";
    $this->salida .= "             </table>\n";
    $this->salida .= "<br>";
    $salida1 .= "  <div id=\"producto_lista\">";
    $salida1 .= " </div>\n";
    $this->salida .= $salida1;
    
    $this->salida .="    <div id='error_canti' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .="    <div id='save_list' class='label_error1' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    //VAR_DUMP($numero);
    
    $this->salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
    $this->salida .= "                    <tr class=\"formulacion_table_list \">\n";
    $this->salida .= "                       <td width=\"15%\" align=\"center\">\n";
    $this->salida .= "                       NUEVA LISTA";
    $javita = "javascript:llamarListaNueva('".$_REQUEST['toma_id']."',document.getElementById('cuantos').value,document.getElementById('n_lista_h').value);";
    $this->salida .= "                         <a title='CREAR NUEVA DE LISTA DE VALIDACION' href=\"".$javita."\">\n";
    $this->salida .= "                          <sub><img src=\"".$path."/images/Listado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
    $this->salida .= "                         </a>\n";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"16%\" align=\"center\">\n";
    $this->salida .= "                          NUMERO DE LISTA";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"5%\" id=\"numero_lista\" align=\"center\">\n";
    $this->salida .= "                          ".$numero."";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"20%\" align=\"center\">\n";
    $this->salida .= "                         CANTIDAD DE PRODUCTOS";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"5%\" id=\"total_productos\" align=\"center\">\n";
    $this->salida .= "                         ".$_REQUEST['registros'];
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"11%\" align=\"center\">\n";
    $this->salida .= "                         INSERTADOS";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"8%\" id=\"cant_productos\" align=\"center\">\n";
    $this->salida .= "                          ";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"15%\" align=\"center\">\n";
    $this->salida .= "                         CANTIDAD MAXIMA";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td width=\"5%\" align=\"left\">\n";
    $this->salida .= "                         <select id=\"cap_max\" name=\"cap_max\" class=\"select\">";
    $this->salida .="                           <option value=\"5\">5</option> \n";
    for($i=10;$i<=100;$i=$i+10)
    {
      if($i==20)
      {      
        $this->salida .="                           <option value=\"".$i."\" selected >".$i."</option> \n";
      }
      else
      {
          $this->salida .="                           <option value=\"".$i."\">".$i."</option> \n";
      }
     
    }
    $this->salida .= "                         </select>\n";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                      </tr>\n";
    $this->salida .= "                    </table>\n";
    $this->salida .= "                   <div id='refresh_conteo'>";
    $this->salida .= "                 </div>\n";
    
    $this->salida .= "               <br>";
     
    
    $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisicaLogueo');
    $this->salida .= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
    $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "       <td align=\"center\" colspan='7'>\n";
    $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $this->salida .= "       </td>\n";  
    $this->salida .= "    </tr>\n"; 
    $this->salida .= "  </table>\n"; 
    $this->salida .= " </form>\n"; 
    $this->salida.="<script language=\"javaScript\">
      function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }
      </script>";
    $this->salida .= ThemeCerrarTabla();
    return true;
 } 
 
 function Informe_Conteo(){
     $consulta= new TomaFisicaSQL();
     $empresa=SessionGetVar("EMPRESA");
     $centro_utilidad=$consulta->ListarCentrodeUtilidad($empresa);
     $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
     $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
     $this->SetXajax(array("bodega_informe","consultar_cabecera_informe","consultar_informe"),$file);
     $html.= ThemeAbrirTabla("INFORME DIFERENCIA DE VALORES");
     $html.="<table width=\"60%\" align=\"center\" border='0' >";
     $html.="<tr class=\"formulacion_table_list\">";
     $html.="<td align=\"center\" colspan='3'><div id='mensaje'></div></td>";
     $html.="</tr>";
     $html.="<tr class=\"modulo_list_claro\">";     
     $html.="<td align=\"center\" >";
     $html.= "CENTRO UTILIDAD: <select id=\"centro\" name=\"centro\" class=\"select\" onchange=\"bodega_informe('$empresa',this.value);\">";
     $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";    
     foreach($centro_utilidad as $key=>$value){
     $id=$value['centro_utilidad'];    
     $html.= "                           <option value=\"$id\">".$value['descripcion']."</option> \n";
     }
     $html.= "                       </select>\n";
     $html.="</td>";
     $html.="<td align=\"center\"><div id='div_bodega'>";
     $html.= "BODEGA: <select id=\"bodega\" name=\"bodega\" class=\"select\" onchange=\"\">";
     $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";
     $html.= "                       </select>\n";         
     $html.= "</div></td>";
     $html.="<td align=\"center\"><div id='div_cabecera'>";
     $html.= "CABECERA: <select id=\"cabcera\" name=\"cabecera\" class=\"select\" onchange=\"\">";
     $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";
     $html.= "                       </select>\n";         
     $html.= "</div></td>";
     $html.="</tr>";
     $html.="<tr>";
     $html.="<td align='center' colspan='3'>";
     $html.="<div id='tabla_conteo'></div>";
     $html.="</td>";
     $html.="</tr>";
     $html.="<tr>";
     $html.="<td colspan='3'>";
     $html.="<br><br><br>";
     /////////////////////////
     if($_REQUEST['empresa']!='' && $_REQUEST['centro_utilidad']!='' && $_REQUEST['bodega']!='' && $_REQUEST['cabecera']!=''){
                $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
                $buscarConteoValor=$sql->BuscarConteoValor($_REQUEST['empresa'],$_REQUEST['bodega'],$_REQUEST['centro_utilidad'],$_REQUEST['cabecera']);
                $sum=0;
                $row =0;
                if(sizeof($buscarConteoValor)>0){
                $htmlc ="<table width=\"100%\" border='1'  class=\"modulo_table_list\">";
                $htmlb ="<tr class=\"formulacion_table_list\">";
                $htmlb.="<td width=\"2%\">#</td>";
                $htmlb.="<td width=\"10%\">Cod Producto</td>";
                $htmlb.="<td width=\"48%\">Nombre Producto</td>";
                $htmlb.="<td width=\"10%\">Stock</td>";
                $htmlb.="<td width=\"10%\">Conteo 2</td>";
                $htmlb.="<td width=\"10%\">Diferencia</td>";
                $htmlb.="<td width=\"10%\">Valor</td>";    
                $htmlb.="</tr>";
                foreach ($buscarConteoValor as $key => $value) {
                    $row++;
                $htmlb.="<tr class=\"modulo_table_list\">";
                $htmlb.="<td align='right' class=\"LABEL\">".$row."</td>";
                $htmlb.="<td class=\"LABEL\">".$value['codigo_producto']."</td>";
                $htmlb.="<td class=\"LABEL\">".$value['nombre']."</td>";
                $htmlb.="<td align='right' class=\"LABEL\">".$value['stock']."</td>";
                $htmlb.="<td align='right' class=\"LABEL\">".$value['conteo']."</td>";
                $htmlb.="<td align='right' class=\"LABEL\">".$value['diferencia']."</td>";
                $htmlb.="<td align='right' class=\"LABEL\">".$value['valor']."</td>";
                
                $htmlb.="</tr>";
                $sum+=$value['valor'];
                }
                $htmlc.="<tr class=\"formulacion_table_list\">";
                $htmlc.="<td colspan='5' align='right' >TOTAL</td>";
                $htmlc.="<td colspan='2' align='right' >".$sum."</td>";
                $htmlc.="</tr>";
                $htmlc.=$htmlb;
                $htmlc.="</table>";
                }else{
                    $htmlc="NO HAY DATOS";
                }
      $html.=$htmlc;
     }
     ///////////////////
     $html.="</td>";
     $html.="</tr>";
     $html.="<tr>";
     $html.="<td colspan='3'>";
     $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','MenuTomaFisica');
     $html.= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
     $html.= "  <table align=\"center\" width=\"50%\">\n";
     $html.= "    <tr>\n";
     $html.= "       <td align=\"center\" colspan='7'>\n";
     $html.= "       <br>\n";
     $html.= "       <br>\n";
     $html.= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
     $html.= "       </td>\n";  
     $html.= "    </tr>\n"; 
     $html.= "  </table>\n"; 
     $html.= " </form>\n"; 
     $html.="</td>";
     $html.="</tr>";
     $html.="</table>";
     $html.= ThemeCerrarTabla();
     $this->salida =$html;
     return true;
 }
 
 function Modificar_Producto(){
     $consulta= new TomaFisicaSQL();
     $empresa=SessionGetVar("EMPRESA");
     $centro_utilidad=$consulta->ListarCentrodeUtilidad($empresa);
     $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
     $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
     $this->SetXajax(array("ConsultarProductoConteo","ModificarProductoConteo","InsertarProducto","verificar_cabecera_producto","consultar_cabecera","busqueda_producto", "modificarProducto"),$file);
     $html.= ThemeAbrirTabla("MODIFICAR PRODUCTO DE CONTEO");
     $html.="<table width=\"60%\" align=\"center\" border='0' >";
     $html.="<tr class=\"formulacion_table_list\">";
     $html.="<td align=\"center\" colspan='4'><div id='mensaje'></div></td>";
     $html.="</tr>";
     $html.="<tr class=\"modulo_list_claro\">";     
     $html.="<td align=\"center\" >";
     $html.= "CENTRO UTILIDAD: <select id=\"centro\" name=\"centro\" class=\"select\" onchange=\"verificar_cabecera_producto(this.value,'$empresa');\">";
     $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";    
     foreach($centro_utilidad as $key=>$value){
     $id=$value['centro_utilidad'];    
     $html.= "                           <option value=\"$id\">".$value['descripcion']."</option> \n";
     }
     $html.= "                       </select>\n";
     $html.="</td>";
     $html.="<td align=\"center\"><div id='div_bodega'>";
     $html.= "BODEGA: <select id=\"bodega\" name=\"bodega\" class=\"select\" onchange=\"\">";
     $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";
     $html.= "                       </select>\n";         
     $html.= "</div></td>";
     $html.="<td>Conteo:";
     $html.= "<select id=\"conteo\" name=\"conteo\" class=\"select\" >";
     $html.= "<option value=\"-1\" SELECTED>-seleccionar-</option> \n"; 
     $html.= "<option value=\"1\">1</option> \n"; 
     $html.= "<option value=\"2\">2</option> \n"; 
     $html.= "<option value=\"3\">todos</option> \n"; 
     $html.= "</select>\n";
     $html.="</td>";
     $html.= "<td>";
     $html.= "<div id='cabecera'></div>";
     $html.= "</td>";
     $html.= "</tr>"; 
     $html.= "<tr>";
     $html.= "<td align='center' colspan='4'>";
     $html.= "<div id='tabla'></div>";
     $html.= "</td>";
     $html.= "</tr>";
     $html.="<tr>";
     $html.="<td align='center' colspan='4'>";
     $html.="<table>";
     $html.="<br>";
     $html.="<tr class=\"formulacion_table_list\">";
     $html.="<td align='center' colspan='5'>";
     $html.="INGRESO DE PRODUCTOS AL CONTEO";
     $html.="</td>";
     $html.="<tr>";
     $html.="<tr class=\"modulo_list_claro\">";
     $html.="<td>Codigo Producto: <input type=\"text\" size=\"20\"  class=\"input-text\" name=\"codigo_producto_insert\" id=\"codigo_producto_insert\" > </td>";
     $html.="<td>Lote: <input type=\"text\" size=\"12\"  class=\"input-text\" name=\"lote_insert\" id=\"lote_insert\" ></td>";
     $html.="<td>Cantidad: <input type=\"text\" size=\"10\"  class=\"input-text\" name=\"cantidad_insert\" id=\"cantidad_insert\" ></td>";
     $html.="<td>Fecha Vencimiento: <input type=\"text\" size=\"12\"  class=\"input-text\" name=\"fecha_insert\" id=\"fecha_insert\" ></td>";
     $html.="<td><input type=\"button\" value=\"Guardar\" id='buscar' name='guardar' class=\"input-submit\" onclick=\"javascript:InsertarProducto();\"></td>";
     $html.="</tr>";
     $html.="</table>";     
     $html.="</td>";
     $html.="</tr>";
     $html.= "</table>";
     $html.="<br>";
     $html.="<br>";
     $html.= "<table align='center' width=\"70%\">";
     $html.= "<tr>";
     $html.= "<td>";
     $html.= "<div id='consulta_productos'></div>";
     //////////
   
     if($_REQUEST['empresa']!='' && $_REQUEST['centro_utilidad']!='' && $_REQUEST['bodega']!='' && $_REQUEST['cabecera_id']!='' && $_REQUEST['conteo']){
       
      $sql = AutoCarga::factory("TomaFisicaSQL", "", "app", "InvTomaFisica");
    $productos=$sql->ConsultarProducto($_REQUEST['empresa'],$_REQUEST['centro_utilidad'],$_REQUEST['bodega'],$_REQUEST['cabecera_id'],$_REQUEST['nombre_producto'],$_REQUEST['codigo_producto'],$_REQUEST['conteo']);
    //echo "<pre>";print_r($producto);
    $html.="<table width=\"100%\" class=\"modulo_table_list\">";
    $html.="<tr class=\"formulacion_table_list\">";
    $html.="<td width=\"1%\">CONTEO</td>";
    $html.="<td width=\"9%\">CODIGO PRODUCTO</td>";
    $html.="<td width=\"50%\">NOMBRE PRODUCTO</td>";
    $html.="<td width=\"5%\">CANTIDAD</td>";
    $html.="<td width=\"5%\">LOTE</td>";
    $html.="<td width=\"5%\">FVTO(YYYY-MM-DD)</td>";
    $html.="<td width=\"20%\">USUARIO</td>";
    $html.="<td width=\"5%\"></td>";
    $html.="</tr>";
    $empresa=$_REQUEST['empresa'];
    $centro_utilidad=$_REQUEST['centro_utilidad'];
    $bodega=$_REQUEST['bodega'];
    $cabecera_id=$_REQUEST['cabecera_id'];
    foreach ($productos as $key => $value) {        
    $html.="<tr>";
    
    $html.="<td align='center'>".$value['conteo']."</td>";
    
    $html.="<td>".$value['codigo_producto']."</td>";

    $html.="<td>".$value['descripcion']."</td>";
   
    $html.="<td align='right'><input type=\"text\" size=\"6\" style='text-align:right' class=\"input-text\" name=\"cantidad$key\" id=\"cantidad$key\" value=".$value['cantidad']." ></td>";
  
    $html.="<td><input type=\"text\" size=\"10\" class=\"input-text\" style='text-align:right' name=\"lote$key\" id=\"lote$key\" value=".$value['lote']." ></td>";
    
    $html.="<td><input type=\"text\" size=\"10\" class=\"input-text\" style='text-align:center' name=\"fecha_vencimiento$key\" id=\"fecha_vencimiento$key\" value=".$value['fecha_vencimiento']." ></td>";
    
    $html.="<td>".$value['nombre']."</td>";
    $codigo_producto=$value['codigo_producto'];
    $conteo=$value['conteo'];
    
    $html.="<td align='center' colspan='7'><input type=\"button\" value=\"Modificar\" id='buscar' name='buscar' class=\"input-submit\" onclick=\"javascript:modificarProducto('$empresa','$centro_utilidad','$bodega','$cabecera_id','$key','$codigo_producto','$conteo');\"></td>";
    $html.="</tr>";
    
    }
    $html.="</tr>";
    
    
    $html.="</table>";
     }
     /////////
     $html.="</td>";
     $html.="</tr>";
     $html.="<tr>";
     $html.="<td>";
     $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','MenuTomaFisica');
     $html.= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
     $html.= "  <table align=\"center\" width=\"50%\">\n";
     $html.= "    <tr>\n";
     $html.= "       <td align=\"center\" colspan='7'>\n";
     $html.= "       <br>\n";
     $html.= "       <br>\n";
     $html.= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
     $html.= "       </td>\n";  
     $html.= "    </tr>\n"; 
     $html.= "  </table>\n"; 
     $html.= " </form>\n"; 
     $html.="</td>";
     $html.="</tr>";
     $html.="</table>";
     $html.= ThemeCerrarTabla();
     $this->salida =$html;
     return true;
 }
 
 function Ajustar_inventario(){
     $consulta= new TomaFisicaSQL();
     $empresa=SessionGetVar("EMPRESA");
     $centro_utilidad=$consulta->ListarCentrodeUtilidad($empresa);
     
     $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
     $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
     $this->SetXajax(array("ProcesarInventario","VerificarCabecera","bodega_inventario","CrearDocumento_Ingreso","CrearDocumento_Egreso","CrearDocumento_Egreso_Ingreso","Backup_existencias_bodegas_lote"),$file);
     $html.= ThemeAbrirTabla("AJUSTAR INVENTARIO");
     
     $html.="<table width=\"40%\" align=\"center\" border='0' >";
     $html.="<tr class=\"formulacion_table_list\">";
     $html.="<td align=\"center\" colspan='2'><div id='mensaje'></div></td>";
     $html.="</tr>";
     $html.="<tr class=\"modulo_list_claro\">";     
     $html.="<td align=\"center\" >";
     $html.= "CENTRO UTILIDAD: <select id=\"centro\" name=\"centro\" class=\"select\" onchange=\"vista_bodega_inventario(this.value,'$empresa');\">";
     $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";    
     foreach($centro_utilidad as $key=>$value){
     $id=$value['centro_utilidad'];    
     $html.= "                           <option value=\"$id\">".$value['descripcion']."</option> \n";
     }
     $html.= "                       </select>\n";
     $html.="</td>";
     $html.="<td align=\"center\"><div id='div_bodega'>";
     $html.= "BODEGA: <select id=\"bodega\" name=\"bodega\" class=\"select\" onchange=\"\">";
     $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";
     $html.= "                       </select>\n";         
     $html.= "</div></td>";
     $html.="</tr>";
     $html.="<tr>";
     $html.="<td colspan='2'  align=\"center\"  >";     
     $html.= "<input type=\"button\" onclick=\"javascript:ProcesarInventario('".$empresa."')\" id='botonAjuste' name='botonAjuste' class=\"input-submit\" value=\"Crear Ajuste\">\n";
     $html.="</td>";
     $html.="</tr>";
     $html.="<tr>";
     $html.="<td colspan='2'>";
     $html.="<div id='tabla'></div>";
     $html.="</td>";
     $html.="</tr>";
     $html.="<tr>";
     $html.="<td colspan='2'>";
     $html.="<div id='tabla_empresas'></div>";
     $html.="</td>";
     $html.="</tr>";
     $html.="<tr>";
     $html.="<td colspan='2'>";
     $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','MenuTomaFisica');
     $html.= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
     $html.= "  <table align=\"center\" width=\"50%\">\n";
     $html.= "    <tr>\n";
     $html.= "       <td align=\"center\" colspan='7'>\n";
     $html.= "       <br>\n";
     $html.= "       <br>\n";
     $html.= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
     $html.= "       </td>\n";  
     $html.= "    </tr>\n"; 
     $html.= "  </table>\n"; 
     $html.= " </form>\n"; 
     $html.="</td>";
     $html.="</tr>";
     $html.="</table>";
     $html.= ThemeCerrarTabla();
     $this->salida =$html;
     return true;
 } 
 
 function Cargue_inventario(){
     $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
     $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
     $this->SetXajax(array("bodega","Guardar_cabecera","validar_cabecera_activa"),$file);
     $html.= ThemeAbrirTabla("CARGUE DE INVENTARIOS POR CSV");
     $consulta= new TomaFisicaSQL();
     $empresa=SessionGetVar("EMPRESA");
     //$bodegas=$consulta->GetBodegas($empresa);
     $action=ModuloGetURL('app','InvTomaFisica','user','CargarArchivo');
     $centro_utilidad=$consulta->ListarCentrodeUtilidad($empresa);
     $html.="<form id='cabecera' name='cabecera'>";
     $html.="<table width=\"40%\" align=\"center\" border='0' >";
     $html.="<tr class=\"formulacion_table_list\">";
     $html.="<td align=\"center\" colspan='2'>CREAR CABECERA</td>";
     $html.="</tr>";
     $html.="<tr class=\"modulo_list_claro\">";     
     $html.="<td align=\"center\" >";
     $html.= "CENTRO UTILIDAD: <select id=\"centro\" name=\"centro\" class=\"select\" onchange=\"vista_bodega(this.value,'$empresa');\">";
     $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";    
     foreach($centro_utilidad as $key=>$value){
     $id=$value['centro_utilidad'];    
     $html.= "                           <option value=\"$id\">".$value['descripcion']."</option> \n";
     }
     $html.= "                       </select>\n";
     $html.="</td>";
     $html.="<td align=\"center\"><div id='div_bodega'>";
     $html.= "BODEGA: <select id=\"bodega\" name=\"bodega\" class=\"select\" onchange=\"\">";
     $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";
     $html.= "                       </select>\n";         
     $html.= "</div></td>";
     $html.="</tr>";
     $html.="<tr class=\"modulo_list_claro\">";
     $html.="<td colspan='1' align=\"center\" >";
     $html.="NOMBRE: <input type=\"text\" size=\"45\" class=\"input-text\" name=\"nombre\" id=\"nombre\">";
     $html.="</td>";
     $html.= "      <td align=\"center\" >\n";
     $html .= "      <input type=\"button\" value=\"Crear\" id='crear_cabecera' name='crear_cabecera' class=\"input-submit\" onclick=\"javascript:crear(this,'$empresa');\">";
     $html.= "      </td>\n";
     $html.="</tr>";
     $html.="</tr>";
     $html.= "      <td align=\"center\" colspan='2' class=\"formulacion_table_list\" >\n";
     $html .= "      <div id='mensaje'></div>";
     $html.= "      </td>\n";
     $html.="</tr>";
     $html.="</table>";
     $html.="</form>";
     $html.="<br><br>";
     
     $html.= "<form name=\"subir\" enctype=\"multipart/form-data\" action=\"javascript:Eval(document.subir,'$action')\" method = \"post\" >\n";
     $html.="<table width=\"40%\" align=\"center\" border='0' >";
     $html.="<tr class=\"modulo_list_claro\">";
     $html.="<td colspan='10'>";
     $html.="<div id='mensaje_cabecera'></div>";
     $html.="</td>";
     $html.="</tr>";
     $html.="<tr>";
     $html.="<td class=\"formulacion_table_list\" align=\"center\" colspan='10'>ESTRUCTURA ARCHIVO PLANO (separado por ';')</td>";
     $html.="</tr>";
     
     $html.="<tr class=\"modulo_list_claro\">";
     $html.="<td align=\"center\" colspan='1'>Empresa id</td>";
     $html.="<td align=\"center\" colspan='1'>Centro utilidad</td>";
     $html.="<td align=\"center\" colspan='1'>Bodega</td>";
     $html.="<td align=\"center\" colspan='1'>Codigo producto</td>";
     $html.="<td align=\"center\" colspan='1'>Cantidad</td>";
     $html.="<td align=\"center\" colspan='1'>Lote</td>";
     $html.="<td align=\"center\" colspan='1'>Fecha vencimiento</td>";
     $html.="<td align=\"center\" colspan='1'>Usuario</td>";
     $html.="<td align=\"center\" colspan='1'>Fecha registro</td>";
     $html.="<td align=\"center\" colspan='1'>Conteo</td>";
     $html.="</tr>";  
     
     $html.="<tr>";
     $html.="<td class=\"formulacion_table_list\" width=\"33%\" colspan='3'>";
     $html.= "CONTEO: <select id=\"conteo\" name=\"conteo\" class=\"select\" >";
     $html.= "                           <option value=\"-1\" SELECTED >---seleccionar---</option> \n";  
     $html.= "                           <option value=\"1\" >Primer Conteo</option>";  
     $html.= "                           <option value=\"2\" >Segundo Conteo</option>";  
     $html.= "</select>";  
     $html.="</td>";
     $html.= "<td width=\"33%\" colspan='3' style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\" >ADJUNTAR ARCHIVO: </td>\n";
     $html.= "<td width=\"33%\" align=\"left\" colspan=\"4\">\n";
     $html.= "<input type=\"file\" size=\"45\" class=\"input-text\" name=\"archivo\" id=\"archivo\">\n";
     $html.= "</td>\n";
     $html.= "</tr>\n";
     $html.= "<tr class=\"modulo_list_claro\">\n";
     $html.= "<td colspan='10'>&nbsp;</td>\n";
     $html.= "</tr>\n";
     $html.= "<tr>\n";
     $html.= "      <td colspan='10' align=\"center\" width=\"50%\">\n";
     $html.= "        <input class=\"input-submit\" type=\"submit\" name=\"enviar\" value=\"Cargar\">\n";
     $html.= "      </td>\n";
     $html.= "</tr>\n";
     $html.="</table>";      
     $html.= "</form>\n";
     $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','MenuTomaFisica');
     $html.= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
     $html.= "  <table align=\"center\" width=\"50%\">\n";
     $html.= "    <tr>\n";
     $html.= "       <td align=\"center\" colspan='7'>\n";
     $html.= "       <br>\n";
     $html.= "       <br>\n";
     $html.= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
     $html.= "       </td>\n";  
     $html.= "    </tr>\n"; 
     $html.= "  </table>\n"; 
     $html.= " </form>\n"; 
     $html.="</td>";
     $html.="</tr>";
     $html.="</table>";
    // $html.=$this->leerArchivo();
     $html.= ThemeCerrarTabla();
     $this->salida .= $html;
     return true;    
 }
 
 function CargarArchivo() {
        $dir_siis = GetVarConfigAplication('DIR_SIIS');
        $ruta_archivo = $dir_siis . "tmp/inventarios/" . "Rotacion.csv";
        copy($_FILES['archivo']['tmp_name'], $ruta_archivo);
        $datos = $this->leerArchivo($ruta_archivo);
        $consulta = new TomaFisicaSQL();
        $empresa_id = SessionGetVar("EMPRESA");
        $html ="<table align='center' border='1'>";
        $html.=$this->cabecera_table();
        foreach ($datos as $key => $values) {
         //  $html.="<tr class=\"modulo_table_list\">";
            if ($key != 0) {
                if ($values[0] == $empresa_id && trim($values[1]) == trim($_REQUEST['centro_id']) && $values[2] == $_REQUEST['bodega_id'] && $values[9] == $_REQUEST['conteo'] &&
                      $values[3] != '' && $values[4] != '' && $values[5] != '' && $values[6] != '' && $values[7] != '' && $values[8] != '' && $values[9]) {
                       $insert = $consulta->Guardar_Detalle_inventario($empresa_id, $_REQUEST['centro_id'], $_REQUEST['bodega_id'], $_REQUEST['cabe_id'], $values[3], $values[4], $values[5], $values[6], $values[7], $values[8], $values[9],'');                   
                    if ($insert) {
                        $htmlOK.=$this->cuerpo_tabla($empresa_id, $_REQUEST['centro_id'], $_REQUEST['bodega_id'], $_REQUEST['cabe_id'], $values[3], $values[4], $values[5], $values[6], $values[7], $values[8], $values[9],"SI INSERTO");
                    } else {
                        $estilo="bgcolor='red'";
                        $html.=$this->cuerpo_tabla($empresa_id, $_REQUEST['centro_id'], $_REQUEST['bodega_id'], $_REQUEST['cabe_id'], $values[3], $values[4], $values[5], $values[6], $values[7], $values[8], $values[9],"NO INSERTO",$estilo);
                    }
                } else {
                        $estilo="bgcolor='red'";
                        $html.=$this->cuerpo_tabla($values[0], $values[1], $values[2], $_REQUEST['cabe_id'], $values[3], $values[4], $values[5], $values[6], $values[7], $values[8], $values[9],"NO COINCIDE LA CABECERA O FALTAN PARAMETROS",$estilo);
                }
            }
        }
         $html.=$htmlOK;
         $html.="</table>";
            $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','Cargue_inventario');
            $html.= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
            $html.= "  <table align=\"center\" width=\"50%\">\n";
            $html.= "    <tr>\n";
            $html.= "       <td align=\"center\" colspan='7'>\n";
            $html.= "       <br>\n";
            $html.= "       <br>\n";
            $html.= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
            $html.= "       </td>\n";  
            $html.= "    </tr>\n"; 
            $html.= "  </table>\n"; 
            $html.= " </form>\n"; 
            $html.="</td>";
            $html.="</tr>";
            $html.="</table>";
         $this->salida .= $html;
        return true;
    }
  
 function cuerpo_tabla($empresa_id,$centro_utilidad,$bodega,$id_conteo_toma_fisica,$codigo_producto,$cantidad,
                        $lote,$fecha_vencimiento,$usuario_id,$fecha_registro,$conteo,$inserto,$estilo){
        $html ="<tr class=\"modulo_table_list\" >";
        $html.="<td>";
        $html.=$empresa_id;
        $html.="</td>";
        $html.="<td>";
        $html.=$centro_utilidad;
        $html.="</td>";
        $html.="<td>";
        $html.=$bodega;
        $html.="</td>";
        $html.="<td>";
        $html.=$codigo_producto;
        $html.="</td>";
        $html.="<td>";
        $html.=$cantidad;
        $html.="</td>";
        $html.="<td>";
        $html.=$lote;
        $html.="</td>";
        $html.="<td>";
        $html.=$fecha_vencimiento;
        $html.="</td>";
        $html.="<td>";
        $html.=$usuario_id;
        $html.="</td>";
        $html.="<td>";
        $html.=$conteo;
        $html.="</td>";
        $html.="<td>";
        $html.=$id_conteo_toma_fisica;
        $html.="</td>";
        $html.="<td>";
        $html.=$fecha_registro;
        $html.="</td>";
        $html.="<td $estilo>";
        $html.=$inserto;
        $html.="</td>";
        $html.="</tr>";
        return $html;
 }   
 
 function cabecera_table(){
        
        $html ="<tr class=\"modulo_table_list_title\">";
        $html.="<td>";
        $html.="EMPRESA";
        $html.="</td>";
        $html.="<td>";
        $html.="CENTRO DE UTILIDAD";
        $html.="</td>";
        $html.="<td>";
        $html.="BODEGA";
        $html.="</td>";
        $html.="<td>";
        $html.="PRODUCTO";
        $html.="</td>";
        $html.="<td>";
        $html.="CANTIDAD";
        $html.="</td>";
        $html.="<td>";
        $html.="LOTE";
        $html.="</td>";
        $html.="<td>";
        $html.="FECHA VENCIMIENTO";
        $html.="</td>";
        $html.="<td>";
        $html.="USUARIO";
        $html.="</td>";
        $html.="<td>";
        $html.="CONTEO";
        $html.="</td>";
        $html.="<td>";
        $html.="CABECERA";
        $html.="</td>";
        $html.="<td>";
        $html.="FECHA REGISTRO";
        $html.="</td>";
        $html.="<td>";
        $html.="ESTADO TRANSACCIONAL";
        $html.="</td>";
        $html.="</tr>";
        return $html;
 }
 
 function leerArchivo($nombre_archivo){
     
     $fp = fopen ( $nombre_archivo, "r" ); $i = 0; 
           while (( $data = fgetcsv ( $fp , 1000 , "\"" )) !== FALSE ) { // Mientras hay líneas que leer... 
                foreach($data as $row) {
                // Muestra todos los campos de la fila actual 
                $porciones[] = explode(";", $row);                
                $i++ ;
                }
            } 
      fclose ($fp);
      return $porciones;
 }
 
 function ValidacionLotes(){
     $consulta= new TomaFisicaSQL();
     $empresa=SessionGetVar("EMPRESA");
     $bodegas=$consulta->GetBodegas($empresa);
     $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
     $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
     $this->SetXajax(array("ProcesarLote"),$file);
     $html.= ThemeAbrirTabla("VALIDAR LOTESll");
     $html.="<table width=\"20%\" align=\"center\" border='0' >";
     $html.="<tr>";
     $html.="<td colspan='2'>";
     $html.="<div id='mensaje'></div>";
     $html.="</td>";
     $html.="</tr>";
     $html.="<tr>";
     $html.="<td align=\"center\" >";
     $html.= "                       <select id=\"bodegas_select_lotes\" name=\"bodegas_select_lotes\" class=\"select\" onchange=\"\">";
     $html.= "                           <option value=\"-1\" SELECTED>---seleccionar---</option> \n";    
     foreach($bodegas as $key=>$value){
     $id=$value['bodega'];    
     $html.= "                           <option value=\"$id\">".$value['descripcion']."</option> \n";
     }
     $html.= "                       </select>\n";
     $html.="</td>";
     $html.="<td>";     
     $html.= "          <input type=\"button\" onclick=\"javascript:ProcesarLote('".$empresa."')\" class=\"input-submit\" value=\"Enviar\">\n";
     $html.="</td>";
     $html.="</tr>";
     $html.="<tr>";
     $html.="<td colspan='2'>";
     $html.="<div id='tabla'></div>";
     $html.="</td>";
     $html.="</tr>";
     $html.="<tr>";
     $html.="<td colspan='2'>";
     $html.="<div id='tabla_empresas'></div>";
     $html.="</td>";
     $html.="</tr>";
     $html.="<tr>";
     $html.="<td colspan='2'>";
     $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','MenuTomaFisica');
     $html.= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
     $html.= "  <table align=\"center\" width=\"50%\">\n";
     $html.= "    <tr>\n";
     $html.= "       <td align=\"center\" colspan='7'>\n";
     $html.= "       <br>\n";
     $html.= "       <br>\n";
     $html.= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
     $html.= "       </td>\n";  
     $html.= "    </tr>\n"; 
     $html.= "  </table>\n"; 
     $html.= " </form>\n"; 
     $html.="</td>";
     $html.="</tr>";
     $html.="</table>";
     $html.= ThemeCerrarTabla();
     $this->salida =$html;
     return true;
 }
 
 ///se repite en xajax solo se coloca aca por requerimiento de ajuste de vencidos 
 function ProcesarLote() {
    $consulta = new TomaFisicaSQL();
    $empresa=$_REQUEST["empresa"];
    $bodega_id=$_REQUEST["bodega"];
    $bodega_nombre=$_REQUEST["nombre_bodega"];
    $diferenciLotes = $consulta->GetDiferenciasLotes($empresa, $bodega_id);
    
   //echo "<pre>";print_r($diferenciLotes);
    $html  = "<table width=\"100%\" class=\"modulo_table_list\" align=\"center\" >";
    $html .= "<tr align=\"center\" class=\"modulo_table_list_title\" >";
    $html .= "<td colspan='7'>";
    $html .= "<div>$bodega_nombre</div>";
    $html .= "</td>";
    $html .= "</tr>";
    $html .= "<tr align=\"center\" class=\"modulo_table_list_title\" >";
    $html .= "<td>PRODUCTO</td>";
    $html .= "<td>EXISTENCIA</td>";
    $html .= "<td>OPERACION</td>";
    $html .= "<td>LOTE</td>";
    $html .= "<td>EXISTENCIA LOTE</td>";
    $html .= "<td>TOTAL LOTES</td>";
    $html .= "<td>CANTIDAD</td>";
    $html .= "</tr>";
    if (sizeof($diferenciLotes) > 0) {
        $n_registros_cambio=0;
      foreach ($diferenciLotes as $keys => $values) {
          $diferencia_a=0;
          if(round($values['titular']['exis'])==$values['lote']['exis']){
              continue;
          }elseif($values['titular']['exis']>$values['lote']['exis']){
              $lotes = $consulta->GetLotedeMayorExistencia($empresa, $bodega_id, $keys, 3);
              $diferencia=$values['titular']['exis']-$values['lote']['exis'];
              //aqui se actualiza el lote sumando lo que hay mas el valor de la diferencia
              $cantidad = ($lotes['existencia_actual']=='0'?0:$lotes['existencia_actual']) + $diferencia;
              $n_registros_cambio+=$consulta->ActualizarLote($empresa,$bodega_id,$keys,$lotes['lote'],$cantidad,$lotes['fecha_vencimiento']);
           $html .=vista_lote($keys,$values['titular']['exis']==0?'0':$values['titular']['exis'],"SUMA",$lotes['lote'],$lotes['existencia_actual']==0?'0':$lotes['existencia_actual'],$values['lote']['exis']==0?'0':$values['lote']['exis'],$diferencia);
           
          }elseif($values['titular']['exis']<$values['lote']['exis']){
               // $lotes = $consulta->GetLotedeMayorExistencia($empresa, $bodega_id, $keys, 0);
                $diferencia=$values['lote']['exis']-$values['titular']['exis'];
                $diferencia_fija=$diferencia;
                $lotes = $consulta->GetLotedeMayorExistencia($empresa, $bodega_id, $keys, 1);
                //echo "<pre>"; print_r($lotes);
                 foreach ($lotes as $value) {
                     if($diferencia>$value['existencia_actual']){
                         
                         $diferencia=$diferencia-$value['existencia_actual'];
                         $diferencia_a+=$diferencia;
                         $cantidad = ($value['existencia_actual']==0?0:$value['existencia_actual']) - $value['existencia_actual'];
                         $n_registros_cambio+=$consulta->ActualizarLote($empresa,$bodega_id,$keys,$value['lote'],$cantidad,$value['fecha_vencimiento']);
                         $html .=vista_lote($keys,$values['titular']['exis']==0?'0':$values['titular']['exis'],"RESTA",$value['lote'],$value['existencia_actual']==0?'0':$value['existencia_actual'],$values['lote']['exis']==0?'0':$values['lote']['exis'],$value['existencia_actual']);
                         if($diferencia_fija<=$value['existencia_actual']){
                         $diferencia_a=0;
                         break;
                         }
                         //aqui se le coloca cero al lote ya que se resto el valor de la existencia 
                     }else{
                         $dif=$diferencia;
                         $diferencia=$value['existencia_actual']-$diferencia;
                         $diferencia_a+=$diferencia;
                          $cantidad = ($value['existencia_actual']==0?0:$value['existencia_actual'])-$dif;
                          $n_registros_cambio+=$consulta->ActualizarLote($empresa,$bodega_id,$keys,$value['lote'],$cantidad,$value['fecha_vencimiento']);
                         $html .=vista_lote($keys,$values['titular']['exis']==0?'0':$values['titular']['exis'],"RESTA",$value['lote'],$value['existencia_actual']==0?'0':$value['existencia_actual'],$values['lote']['exis']==0?'0':$values['lote']['exis'],$dif);
                       // echo $keys." diferencia_fija ".$diferencia_fija." dif ".$dif."<br>";
                         if($diferencia_fija<=$diferencia_a || $diferencia_fija<=$dif){
                         $diferencia_a=0;
                         break;
                         }
                         //aqui se actualiza el lote con el valor de la diferencia
                     }
                     
                 }
          }else{
              if($values['titular']['exis']>0){
                  $lotes = $consulta->GetLotedeMayorExistencia($empresa, $bodega_id, $keys, 0);
                  $diferencia=$values['titular']['exis'];
                  $cantidad =$lotes['lote']+$diferencia;
                  $n_registros_cambio+=$consulta->ActualizarLote($empresa,$bodega_id,$keys,$lotes['lote'],$cantidad,$lotes['fecha_vencimiento']);
                  $html .=vista_lote($keys,$values['titular']['exis']==0?'0':$values['titular']['exis'],"SUMA",$lotes['lote'],"0",$diferencia);
              //aqui se actualiza el lote sumando lo que hay mas el valor de la diferencia
              }else{
                  //$lotes = $consulta->GetLotedeMayorExistencia($empresa, $bodega_id, $keys, 0);
                   $diferencia=$values['lote']['exis'];
                   $diferencia_fija=$diferencia;
                   $lotes = $consulta->GetLotedeMayorExistencia($empresa, $bodega_id, $keys, 1);
                  // echo "<pre>"; print_r($lotes);
                 foreach ($lotes as $value) {
                     if($value['existencia_actual']<$diferencia){
                         $diferencia=$diferencia-$value['existencia_actual'];
                         $diferencia_a+=$diferencia;
                         $cantidad = $value['lote']-$diferencia;
                         $n_registros_cambio+=$consulta->ActualizarLote($empresa,$bodega_id,$keys,$value['lote'],$cantidad,$value['fecha_vencimiento']);
                         $html .=vista_lote($keys,"0","RESTA",$value['lote'],$values['lote']['exis']==0?'0':$values['lote']['exis'],$diferencia);
                         if($diferencia_fija==$diferencia){
                         $diferencia_a=0;
                         break;
                         }
                         //aqui se le coloca cero al lote ya que se resto el valor de la existencia 
                     }else{
                         $diferencia=$value['existencia_actual']-$diferencia;
                         $diferencia_a+=$diferencia;
                         $cantidad = $value['lote']+$diferencia;
                         $n_registros_cambio+=$consulta->ActualizarLote($empresa,$bodega_id,$keys,$value['lote'],$cantidad,$value['fecha_vencimiento']);
                         $html .=vista_lote($keys,$values['titular']['exis']==0?'0':$values['titular']['exis'],"SUMA",$value['lote'],$values['lote']['exis']==0?'0':$values['lote']['exis'],$diferencia);
                         if($diferencia_fija==$diferencia){
                             $diferencia_a=0;
                             break;
                         }
                         //aqui se actualiza el lote con el valor de la diferencia
                     }
                     
                 }
              }
          }          
      }
      $n_registros=sizeof($diferenciLotes);
      $html .="</table>";
      $objResponse.= $html;
      $n_registros_cambio=0;
        
    }else{
    }
     $html=vista_lotes_empresa($consulta,$empresa);
     $objResponse.=$html;
     $this->salida.=$objResponse;
}

 
 
 function ValidacionJefes()
 {
    $path = SessionGetVar("rutaImagenes");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");
    $consulta= new TomaFisicaSQL();
    $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
    $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
    $this->SetXajax(array("GuardarJefe"),$file);
    $this->salida .= ThemeAbrirTabla("VALIDACION DE JEFES");
    $accion1=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisica');
    $this->salida .="    <div id='error_en_mov' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "                 <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td width=\"15%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          EMPRESA";
    $this->salida .= "                       </td>\n";
    $empresa=$consulta->ColocarEmpresa(SessionGetVar("EMPRESA"));
    $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                        ".$empresa[0]['razon_social'];
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td width=\"30%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          USUARIO ID";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                        ".UserGetUID();
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                       <td width=\"30%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          NOMBRE USUARIO";
    $this->salida .= "                       </td>\n";
    $usuario_idx=$consulta->BuscarUsuario(UserGetUID());
    $this->salida .= "                       <td align=\"left\">\n";
    $this->salida .= "                        ".$usuario_idx[0]['nombre'];
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                 </table>";
    $this->salida .= "                 <br>";
    $tomas=$consulta->BuscarUsuarioValidacion(UserGetUID());
    //$this->salida.="<pre>".print_r($tomas,true)."</pre>";
    $this->salida .="                  <div id='refresh'>";
    if(!EMPTY($tomas))
    {
    
        $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
        $this->salida .= "                          TOMA FISICA ID";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"25%\" align=\"center\">\n";
        $this->salida .= "                          DESCRIPCION";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"7%\" align=\"center\">\n";
        $this->salida .= "                         <a title='CENTRO DE UTILIDAD'>\n";
        $this->salida .= "                          CEN_UTIL";
        $this->salida .= "                       </td>\n";
        
        $this->salida .= "                       <td width=\"17%\" align=\"center\">\n";
        $this->salida .= "                         <a title='BODEGA ID'>\n";
        $this->salida .= "                          BODEGA";
        $this->salida .= "                         <a>";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"7%\" align=\"center\">\n";
        $this->salida .= "                         <a title='JEFE DE BODEGA'>JEFE BODEGA\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
        $this->salida .= "                          JEFE DE CONTROL INTERNO";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        $m=0;
        for($i=0;$i<count($tomas);$i++)
        { 
          $this->salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
          $this->salida .= "                       <td align=\"center\">\n";
          $this->salida .= "                       ".$tomas[$i]['toma_fisica_id'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['descripcion'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                      <td align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['centro_utilidad'];
          $this->salida .= "                      </td>\n";
          
          $this->salida .= "                       <td align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['nom_bodega'];
          $this->salida .= "                       </td>\n";
          $empresa2=$consulta->ColocarEmpresa(SessionGetVar("EMPRESA"));
          $jefe=$consulta->Buscarparamprod($empresa2[0]['empresa_id'],$tomas[$i]['toma_fisica_id']);
          
          //$contar=count($tomas);
          //Conteo Jefes
          $contar=count($jefe);
        
            $j=0;
       do{
     
          if($jefe[$j]['sw_jefebodega']=="")
          $jefe[$j]['sw_jefebodega']=0;
          
          if($jefe[$j]['sw_jefecontroli']=="")
          $jefe[$j]['sw_jefecontroli']=0;
          if(UserGetUID()==6 or UserGetUID()==7)
          {
            if($jefe[$j]['sw_jefebodega']==1)
               {
                $this->salida .=  "<td align=\"center\"><a href=\"#\" onclick=\"xajax_GuardarJefe('".$tomas[$i]['toma_fisica_id']."','0','".$jefe[$j]['sw_jefecontroli']."','".$empresa2[0]['empresa_id']."')\">\n";
                $this->salida .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
               }
               else
               {
                $this->salida .=  "<td align=\"center\"><a href=\"#\" onclick=\"xajax_GuardarJefe('".$tomas[$i]['toma_fisica_id']."','1','".$jefe[$j]['sw_jefecontroli']."','".$empresa2[0]['empresa_id']."')\">\n";
                $this->salida .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
                }
               
               if($jefe[$j]['sw_jefecontroli']==1)
               {
                $this->salida .=  "<td align=\"center\"><a href=\"#\" onclick=\"xajax_GuardarJefe('".$tomas[$i]['toma_fisica_id']."','".$jefe[$j]['sw_jefebodega']."','0','".$empresa2[0]['empresa_id']."')\">\n";
                $this->salida .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
               }
                 else
                {
                $this->salida .=  "<td align=\"center\"><a href=\"#\" onclick=\"xajax_GuardarJefe('".$tomas[$i]['toma_fisica_id']."','".$jefe[$j]['sw_jefebodega']."','1','".$empresa2[0]['empresa_id']."')\">\n";
                $this->salida .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
                }
          }
          else
          {
            if($jefe[$j]['sw_jefebodega']==1)
            {
              $this->salida .=  "<td align=\"center\">\n";
              $this->salida .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
            }
            else
            {
              $this->salida .=  "<td align=\"center\">\n";
              $this->salida .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
            if($jefe[$j]['sw_jefecontroli']==1)
            {
              $this->salida .=  "<td align=\"center\">\n";
              $this->salida .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
            }
            else
            {
              $this->salida .=  "<td align=\"center\">\n";
              $this->salida .= "<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
             
          }
           $j++;
           }
            while($j<$contar);
      
          $this->salida .= "                    </tr>\n";
          $m++;
        }
          $this->salida .= "                 </table>";
    }  
    $this->salida .= "            </div>\n";
    $this->salida .= "               <br>";
     
    $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','MenuTomaFisica');
    $this->salida .= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
    $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "       <td align=\"center\" colspan='7'>\n";
    $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $this->salida .= "       </td>\n";  
    $this->salida .= "    </tr>\n"; 
    $this->salida .= "  </table>\n"; 
    $this->salida .= " </form>\n"; 
    $this->salida.="<script language=\"javaScript\">
      function mOvr(src,clrOver) 
        {
            src.style.background = clrOver;
        }
     function mOut(src,clrIn)
        {
            src.style.background = clrIn;
        }
      </script>";
    $this->salida .= ThemeCerrarTabla();
    return true;
 }
 
 
 function ValidacionTomaFisicaLogueo()
 {
    $path = SessionGetVar("rutaImagenes");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");
    $consulta= new TomaFisicaSQL();
    $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
    $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
    $this->SetXajax(array(),$file);
    $this->salida .= ThemeAbrirTabla("CONTEO DE TOMAS FISICA");
    $accion1=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisica');
    $this->salida .="    <div id='error_en_mov' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .= "                 <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td width=\"15%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          EMPRESA";
    $this->salida .= "                       </td>\n";
    $empresa=$consulta->ColocarEmpresa(SessionGetVar("EMPRESA"));
    $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                        ".$empresa[0]['razon_social'];
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td width=\"30%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          USUARIO ID";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                        ".UserGetUID();
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                       <td width=\"30%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          NOMBRE USUARIO";
    $this->salida .= "                       </td>\n";
    $usuario_idx=$consulta->BuscarUsuario(UserGetUID());
    $this->salida .= "                       <td align=\"left\">\n";
    $this->salida .= "                        ".$usuario_idx[0]['nombre'];
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                 </table>";
    $this->salida .= "                 <br>";
    $tomas=$consulta->BuscarUsuarioValidacion(UserGetUID(),$empresa[0]['empresa_id']);
    //var_dump($tomas);
    $this->salida .="                  <div id='refresh'>";
    if(!EMPTY($tomas))
    {
    
        $this->salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $this->salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
        $this->salida .= "                          TOMA FISICA ID";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"25%\" align=\"center\">\n";
        $this->salida .= "                          DESCRIPCION";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"7%\" align=\"center\">\n";
        $this->salida .= "                         <a title='CENTRO DE UTILIDAD'>\n";
        $this->salida .= "                          CEN_UTIL";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"7%\" align=\"center\">\n";
        $this->salida .= "                         <a title='NUMERO DE CONTEOS'>\n";
        $this->salida .= "                          CONTEOS";
        $this->salida .= "                         </a>\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"17%\" align=\"center\">\n";
        $this->salida .= "                         <a title='BODEGA ID'>\n";
        $this->salida .= "                          BODEGA";
        $this->salida .= "                         <a>";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"7%\" align=\"center\">\n";
        $this->salida .= "                         <a title='CANTIDAD DE PRODUCTOS'>PRODUCTOS\n";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"27%\" align=\"center\">\n";
        $this->salida .= "                          OBSERVACION";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                       <td width=\"10%\" align=\"center\">\n";
        $this->salida .= "                          ACCIONES";
        $this->salida .= "                       </td>\n";
        $this->salida .= "                    </tr>\n";
        
        for($i=0;$i<count($tomas);$i++)
        { 
          $this->salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
          $this->salida .= "                       <td align=\"center\">\n";
          $this->salida .= "                       ".$tomas[$i]['toma_fisica_id'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['descripcion'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                      <td align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['centro_utilidad'];
          $this->salida .= "                      </td>\n";
          $this->salida .= "                       <td align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['numero_conteos'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['nom_bodega'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['cantidad_reg'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td align=\"left\">\n";
          $this->salida .= "                       ".$tomas[$i]['observacion'];
          $this->salida .= "                       </td>\n";
          $this->salida .= "                       <td  align=\"center\">\n";
          $CONTEO=ModuloGetURL('app','InvTomaFisica','user','ValidacionTomaFisica',array('toma_id'=>$tomas[$i]['toma_fisica_id'],'registros'=>$tomas[$i]['cantidad_reg'],'bodegax'=>$tomas[$i]['nom_bodega']));
          $this->salida .= "                         <a title='TOMA FISICA ".$tomas[$i]['descripcion']."' href=\"".$CONTEO."\">";
          $this->salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
          $this->salida .= "                         </a>\n";
          $this->salida .= "                       </td>\n";
          $this->salida .= "                    </tr>\n";
        }
          $this->salida .= "                 </table>";
    }  
    $this->salida .= "            </div>\n";
    $this->salida .= "               <br>";
     
    $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','MenuTomaFisica');
    $this->salida .= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
    $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "       <td align=\"center\" colspan='7'>\n";
    $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $this->salida .= "       </td>\n";  
    $this->salida .= "    </tr>\n"; 
    $this->salida .= "  </table>\n"; 
    $this->salida .= " </form>\n"; 
    $this->salida.="<script language=\"javaScript\">
      function mOvr(src,clrOver) 
        {
            src.style.background = clrOver;
        }
     function mOut(src,clrIn)
        {
            src.style.background = clrIn;
        }
      </script>";
    $this->salida .= ThemeCerrarTabla();
    return true;
 }
/**********************************************************************************
*
**********************************************************************************/

  function ValidacionTomaFisica()
 { 
    $path = SessionGetVar("rutaImagenes");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");
    
    $consulta= new TomaFisicaSQL();
    
    $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
    $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
    $this->SetXajax(array(),$file);
    $Listas=$consulta->SacarListas($_REQUEST['toma_id']);
    $this->salida .= ThemeAbrirTabla("VALIDACION TOMA FISICA - BODEGA ".$_REQUEST['bodegax']);
    if(!EMPTY($Listas))
    {
    
    $accion1=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisica');

    $this->salida .= "                 <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td width=\"50%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          EMPRESA";
    $this->salida .= "                       </td>\n";
    $empresa=$consulta->ColocarEmpresa(SessionGetVar("EMPRESA"));
    $this->salida .= "                       <td width=\"50%\" align=\"LEFT\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                        ".$empresa[0]['razon_social'];
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td  align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          USUARIO ID";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                        ".UserGetUID();
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          NOMBRE USUARIO VALIDACION";
    $this->salida .= "                       </td>\n";
    $usuario_idx=$consulta->BuscarUsuario(UserGetUID());
    $this->salida .= "                       <td align=\"left\">\n";
    $this->salida .= "                        ".$usuario_idx[0]['nombre'];
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                 </table>";
    $this->salida .= "                 <br>";
    $salida .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $salida .= "                       <td width=\"15%\" align=\"center\">\n";
    $salida .= "                          NUMERO LISTA";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"20%\" align=\"center\">\n";
    $salida .= "                          FECHA";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"18%\" align=\"center\">\n";
    $salida .= "                         <a title='CANTIDAD DE REGISTROS'>\n";
    $salida .= "                          N REGISTROS";
    $salida .= "                         <a>";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"37%\" align=\"center\">\n";
    $salida .= "                         <a title='USUARIO QUE GENERO LA LISTA'>USUARIO CAPTURA\n";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"10%\" align=\"center\">\n";
    $salida .= "                         ACCIONES\n";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    for($i=0;$i<count($Listas);$i++)
    {
       //numero_lista nombre  fecha cantidad_reg
      //r_dump($producto);
      $salida .= "                   <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
      $salida .= "                       <td align=\"center\">\n";
      $salida .= "                       ".$Listas[$i]['numero_lista'];
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"left\">\n";
      $salida .= "                        ".substr($Listas[$i]['fecha'],0,16);
      $salida .= "                       </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                       ".$Listas[$i]['cantidad_reg'];
      $salida .= "                      </td>\n";
      $salida .= "                       <td align=\"left\">\n";
      $salida .= "                       ".$Listas[$i]['nombre'];
      $salida .= "                       </td>\n";
      $salida .= "                       <td  align=\"center\">\n";
      $CONTEO=ModuloGetURL('app','InvTomaFisica','user','ValidacionListasProductos',array('toma_id'=>$_REQUEST['toma_id'],'lista'=>$Listas[$i]['numero_lista']));
      $salida .= "                         <a title='TOMA FISICA ".$tomas[$i]['descripcion']."' href=\"".$CONTEO."\">";
      $salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
      $salida .= "                         </a>\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
   }

      $salida .= "                 </table>";
   }
   else
   {
     $salida ="    <div id='save_list' class='label_error1' style=\"text-transform: uppercase; text-align:center;\">NO HAY LISTAS CREADAS PARA ESA TOMA FISICA</div>\n";
   }
    $this->salida .=$salida;
    $this->salida .= "               <br>";
     
    
    $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','ValidacionTomaFisicaLogueo');
    $this->salida .= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
    $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "       <td align=\"center\" colspan='7'>\n";
    $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $this->salida .= "       </td>\n";  
    $this->salida .= "    </tr>\n"; 
    $this->salida .= "  </table>\n"; 
    $this->salida .= " </form>\n"; 
     $this->salida.="<script language=\"javaScript\">
      function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }
      </script>";
    $this->salida .= ThemeCerrarTabla();
    return true;
 }    

/********************************************************************
* productos
**********************************************************************/

  function ValidacionListasProductos()
 { 
    $path = SessionGetVar("rutaImagenes");
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");
    $clas = AutoCarga ::factory("ClaseUtil");
    $this->salida.= $clas->IsNumeric();
    $this->salida.= $clas->AcceptNum(false,false);
    $this->salida.= $clas->AcceptDate("-");
    $consulta= new TomaFisicaSQL();
    
    $this->IncludeJS('RemoteXajax/definirToma.js', $contenedor='app', $modulo='InvTomaFisica');
    $file = 'app_modules/InvTomaFisica/RemoteXajax/definirToma.php';
    $this->SetXajax(array("ActualizarUsuValidacion"),$file);
    $ListasProducto=$consulta->SacarProductosLista($_REQUEST['toma_id'],$_REQUEST['lista']);
    if(!EMPTY($ListasProducto))
    {
    $this->salida .= ThemeAbrirTabla("VALIDACION TOMA FISICA");
    $accion1=ModuloGetURL('app','InvTomaFisica','user','CapturaTomaFisica');
     $this->salida .= "<script>\n";
    $this->salida .= "	function ValidarChecks()\n";
		$this->salida .= "	{\n";
    $this->salida .= "   frm=document.validation;\n";
    $this->salida .= "		if(frm.validacherTotal.checked)\n";
    $this->salida .= "		{\n";
   //$this->salida .= "      alert(frm.validacher.value); ";
    $this->salida .= "      for(var i=0;i<= frm.validacher.length;i++)\n ";
    $this->salida .= "      { \n ";
    $this->salida .= "				 frm.validacher[i].checked = true;\n";
    $this->salida .= "			  }\n";
    $this->salida .= "		 }\n";
    $this->salida .= "		 else\n";
    $this->salida .= "		 {\n";
    $this->salida .= "      for(var i=0;i < frm.validacher.length;i++)\n ";
    $this->salida .= "      { \n ";
    //$this->salida .= "      alert(i); ";
    $this->salida .= "				 frm.validacher[i].checked = false;\n";
    $this->salida .= "			  }\n";
    $this->salida .= "		 }\n";
    $this->salida .= "	}\n";
    $this->salida .= "</script>\n";
    
    $this->salida .= "                 <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td width=\"50%\" align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          EMPRESA";
    $this->salida .= "                       </td>\n";
    $empresa_nom=$consulta->ColocarEmpresa(SessionGetVar("EMPRESA"));
    $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                        ".$empresa_nom[0]['razon_social'];
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr>\n";
    $this->salida .= "                       <td  align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          USUARIO ID";
    $this->salida .= "                       </td>\n";
    $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_list_claro\">\n";
    $this->salida .= "                        ".UserGetUID();
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                       <td align=\"LEFT\" class=\"modulo_table_list_title\">\n";
    $this->salida .= "                          NOMBRE USUARIO VALIDACION";
    $this->salida .= "                       </td>\n";
    $usuario_idx=$consulta->BuscarUsuario(UserGetUID());
    $this->salida .= "                       <td align=\"left\">\n";
    $this->salida .= "                        ".$usuario_idx[0]['nombre'];
    $this->salida .= "                       </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                 </table>";
    $this->salida .= "                 <br>";
    $this->salida .= "                 <form name='validation'>";
    $salida .= "    <div id='lista_val'>\n";
    $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    //etiqueta  num_conteo fecha_registro  codigo_producto descripcion descripcion_unidad conteo
    $salida .= "                       <td width=\"5%\" align=\"center\">\n";
    $salida .= "                          ETIQUETA";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"5%\" align=\"center\">\n";
    $salida .= "                         <a title='NUMERO DE CONTEO'>\n";
    $salida .= "                          # CONTEO";
    $salida .= "                         </a>\n";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"13%\" align=\"center\">\n";
    $salida .= "                         <a title='FECHA DE REGISTRO'>\n";
    $salida .= "                          FECHA REG";
    $salida .= "                         <a>";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"12%\" align=\"center\">\n";
    $salida .= "                         <a title='CODIGO DEL PRODUCTO'>\n";
    $salida .= "                          CODIGO";
    $salida .= "                         <a>";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"50%\" align=\"center\">\n";
    $salida .= "                          DESCRIPCION";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"15%\" align=\"center\">\n";
    $salida .= "                          FECHA VENCIMIENTO";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"15%\" align=\"center\">\n";
    $salida .= "                          LOTE";
    $salida .= "                       </td>\n";/*
    $salida .= "                       <td width=\"7%\" align=\"center\">\n";
    $salida .= "                         UNIDAD";
    $salida .= "                       </td>\n";*/
    $salida .= "                       <td width=\"10%\" align=\"center\">\n";
    $salida .= "                        CANTIDAD\n";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"8%\" align=\"center\">\n";
    $salida .= "                         <input type=\"checkbox\" name=\"validacherTotal\" value=\"".$_REQUEST['toma_id']."@".$ListasProducto[$i]['etiqueta']."@".$ListasProducto[$i]['num_conteo']."\" onclick=\"ValidarChecks()\">\n";
    //$salida .= "                         <input type=\"checkbox\" name=\"checkall\" onclick=\"ValidaLista(this.checked);\">\n";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";
    for($i=0;$i<count($ListasProducto);$i++)
    {
       
        
      $salida .= "                   <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
      $salida .= "                       <td align=\"left\">\n";
      $salida .= "                       ".$ListasProducto[$i]['etiqueta_x_producto'];
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\">\n";
      $salida .= "                       ".$ListasProducto[$i]['num_conteo'];
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"left\">\n";
      $salida .= "                        ".substr($ListasProducto[$i]['fecha_registro'],0,16);
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"left\">\n";
      $salida .= "                       ".$ListasProducto[$i]['codigo_producto'];
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"left\">\n";
      $salida .= "                       ".$ListasProducto[$i]['descripcion'];
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"left\">\n";
      $salida .= "                       ".$ListasProducto[$i]['fecha_vencimiento'];
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"left\">\n";
      $salida .= "                       ".$ListasProducto[$i]['lote'];
      $salida .= "                       </td>\n";/*
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                       ".$ListasProducto[$i]['descripcion_unidad'];
      $salida .= "                      </td>\n";*/
      $salida .= "                       <td align=\"center\">\n";
      //$inp_cant="conteolista";
      //$inp_cant1="conteolista".$i;
      list($entero,$decimal) = explode(".",$ListasProducto[$i]['conteo']);
        
       if($decimal>0)
      {
        $salida .= "                      <input type=\"text\" class=\"input-text\" id=\"aaaa\" name=\"conteo_v\" size=\"12\" onkeypress=\"return acceptNum(event);\" value=\"".$ListasProducto[$i]['conteo']."\" onclick=\"Activar(this);\">\n";//
      }
      else
      {
        $salida .= "                      <input type=\"text\" class=\"input-text\" id=\"aaaa\" name=\"conteo_v\" size=\"12\" onkeypress=\"return acceptNum(event);\" value=\"".$entero."\" onclick=\"Activar(this);\">\n";//
      }
      
      $salida .= "                       </td>\n";
      $salida .= "                       <td  align=\"center\">\n";
      $salida .= "                         <input type=\"checkbox\" name=\"validacher\" value=\"".$_REQUEST['toma_id']."@".$ListasProducto[$i]['etiqueta']."@".$ListasProducto[$i]['num_conteo']."\" onclick=\"\">\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
   }
      $salida .= "                   <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td  colspan='10' align=\"right\">\n";
      $salida .= "                         <input type=\"button\" class=\"input-submit\" id=\"validar\" name=\"validar\" value=\"VALIDAR\" onclick=\"ActualizarValidacion('".$_REQUEST['toma_id']."','".$_REQUEST['lista']."');\">\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                 </table>";
      $salida  .="               </div>\n";
   }
   else
   {
     $salida ="    <div id='save_list' class='label_error1' style=\"text-transform: uppercase; text-align:center;\">NO HAY LISTAS CREADAS PARA ESA TOMA FISICA</div>\n";
   }
    $this->salida .=$salida;
    $this->salida .= "               </form>";
    
    $this->salida .="    <div id='resultado_validacion' class='label_error1' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .="    <div id='error_cant' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
     
    
    $MENUMOV=ModuloGetURL('app','InvTomaFisica','user','ValidacionTomaFisica',array('toma_id'=>$_REQUEST['toma_id'],'lista'=>$_REQUEST['lista']));
    $this->salida .= " <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";
    $this->salida .= "  <table align=\"center\" width=\"50%\">\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "       <td align=\"center\" colspan='7'>\n";
    $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $this->salida .= "       </td>\n";  
    $this->salida .= "    </tr>\n"; 
    $this->salida .= "  </table>\n"; 
    $this->salida .= " </form>\n"; 
     $this->salida.="<script language=\"javaScript\">
      function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }
                
      </script>";
    $this->salida .= ThemeCerrarTabla();
    return true;
 }  

}
?>