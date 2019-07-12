<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: BuscadorModuloHTML.class.php,v 1.2 2008/05/28 15:18:54 gerardo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Gerardo Amador Vidal
  */
  /**
  * Clase Vista: BuscadorModuloHTML
  * Clase encargada de crear las formas que se utilizan en el buscador de vacunas y la creacion de las mismas 
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Gerardo Amador Vidal
  */

class BuscadorModuloHTML
{
  /**
  * Constructosr de la clase
  */
  function BuscadorModuloHTML(){}
  
  /**
  * Crea una forma que muestra un buscador y lista las vacunas existentes
  *@param array $action, Arreglo de datos de los links de la aplicacion 
  *@param mixed $vectorCons, contiene los registros y los datos de las vacunas 
  *@param int $conteo, valor del conteo
  *@param int $pagina, valor de paginas
  *@return string
  */
  function FormaBuscador($action, $vectorCons=null, $conteo=null, $pagina=null)
  {
    
    //var_dump($vectorCons);
   
    $html.="<script language=\"javaScript\">
     function mOvr(src,clrOver)
               {
                 src.style.background = clrOver;
               }

               function mOut(src,clrIn)
               {
                 src.style.background = clrIn;
               }  ";
 

    $html .= "function valida_envia(){\n";
    /*$html .= "  if(confirm('Desea realizar la consulta?')){\n";     
    $html .= "    document.forma1.submit();\n";     
    //$html .= "    document.forma1.nombre_vacuna.focus();\n";   
    $html .= "  }\n";*/ 
    $html .= "    document.forma1.submit();\n";  
    $html .= "}\n\n";  
    
    /*$html .= "function conf_eliminar(valor){\n";
    $html .= "  bandera = confirm('Desea eliminar esta vacuna?');\n";     
    $html .= "  if(bandera== true)
                {                     
                     window.location.href=valor; 
                }\n";    
    $html .= "  \n";     
    $html .= "}\n";*/              

    $html .= "function conf_eliminar(valor, id_valor){\n";
    $html .= "  bandera = confirm('Desea eliminar esta vacuna?');\n";     
    $html .= "  if(bandera== true)
                {                     
                
                  if(id_valor.length==0)    
                     window.location.href=valor;
                  else{
                      
                    alert('No es posible eliminar la vacuna! \\n\\n Solo se pueden eliminar las vacunas que no han sido parametrizadas!');
                  }   
                    //alert(id_valor.length);   
                     
                      
                }\n";    
    $html .= "  \n";     
    $html .= "}\n";     
    $html .= "</script>\n";      
        
    
    
    $html .= ThemeAbrirTabla('BUSQUEDA DE VACUNAS');
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "       <tr class=\"formulacion_table_list\">\n";
    $html .= "         <td align=\"center\" colspan=\"3\">BUSCADOR</td>\n";
    $html .= "       </tr>\n";
    
    $html .= "       <tr>\n"; 
    $html .= "         <td class=\"modulo_buscar\" align=\"center\">NOMBRE</td>\n";
    $html .= "         <td class=\"modulo_buscar\" align=\"center\" >\n";            
    $html .= "           <form name=\"forma1\" action=\"".$action['btnBuscar']."\" method=\"post\">\n";
    $html .= "             <input class=\"input-submit\" type=\"input\" name=\"nombre_vacuna\" value=\"\" size=\"50\" >\n";
    //$html .= "           </form>\n";
    $html .= "         </td>\n";
    $html .= "         <td>\n";            
    //$html .= "           <form name=\"form\" action=\"".$action['btnBuscar']."\" method=\"post\">\n";
    $html .= "             <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnBuscar\" value=\"BUSCAR\" onclick=\"valida_envia()\">\n";
    $html .= "           </form>\n";
    $html .= "         </td>\n";
    $html .= "       </tr>\n";     
    $html .= "</table>\n";
    
    $html .= "<br>\n";
    
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td align= \"center\">\n";    
    $html .= "      <a class=\"label_error\" href=\"".$action['crearNueVac']."\" align=\"center\">Crear Nueva Vacuna</a>\n";
    $html .= "    </td>";
    $html .= "  </tr>\n";     
    $html .= "</table>";
    //$html .= "<a href=\"".$action['crearNueVac']."\" align=\"center\">Crear Nueva Vacuna</a>\n";
    
    $html .= "<br>\n";    
    
    $pghtml = AutoCarga::factory('ClaseHTML');    
    
    $html .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "      <tr class=\"formulacion_table_list\">\n";
    $html .= "        <td align=\"center\" colspan=\"1\" rowspan=\"2\">CODIGO</td>\n";
    $html .= "        <td align=\"center\" colspan=\"1\" rowspan=\"2\">NOMBRE VACUNA</td>\n";    
    //$html .= "        <td align=\"center\" colspan=\"1\" rowspan=\"2\">PARAM_ID</td>\n";
    $html .= "        <td align=\"center\" colspan=\"1\" rowspan=\"2\">OBSERVACIONES</td>\n";    
    $html .= "        <td align=\"center\" colspan=\"3\" rowspan=\"1\">ACCIONES</td>\n";
    $html .= "      </tr>\n";
    
    $html .= "  <tr class=\"formulacion_table_list\">";
    $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"1\">MODIFICAR</td>\n";
    $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"1\">ELIMINAR</td>\n";
    $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"1\">PARAMETRIZAR</td>\n";     
    $html .= "  </tr>\n";      
    
    /*for($i=0;$i<count($vectorCons);$i++)
    {       
      $html .= "      <tr>\n";
      $html .= "        <td align=\"center\" colspan=\"1\">".$vectorCons[$i]['vacuna_id']."</td>\n";
      $html .= "        <td align=\"center\" colspan=\"1\">".$vectorCons[$i]['nombre_vacuna']."</td>\n";
      $html .= "        <td align=\"center\" colspan=\"1\">Dato</td>\n";
      $html .= "        <td align=\"center\" colspan=\"1\">\n";
      $html .= "          <a href= \"hey\" align=\"center\">Modi</a>\n";
      $html .= "        </td>";  
      $html .= "        <td align=\"center\" colspan=\"1\">\n";
      $html .= "          <a href= \"".$action[$i]['elimVac']."\" align=\"center\">Elim</a>\n";    
      $html .= "        </td>\n";        
      $html .= "      </tr>\n";
    }*/
    
    $path = GetThemePath();
    foreach($vectorCons as $key => $posvec )
    {       
      $html .= "      <tr class='modulo_list_claro' onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
      $html .= "        <td   align=\"center\" colspan=\"1\">".$posvec['vt_vacuna_id']."</td>\n";
      $html .= "        <td align=\"center\" colspan=\"1\">".$posvec['vt_nombre_vacuna']."</td>\n";
      //$html .= "        <td align=\"center\" colspan=\"1\">".$posvec['vp_vacuna_param_id']."</td>\n";
      $html .= "        <td align=\"justify\" colspan=\"1\">".$posvec['vt_observacion']."</td>\n";      
      
      $action['editVac'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'ModificarVacuna', array('vt_vacuna_id'=>$posvec['vt_vacuna_id'], 'vt_nombre_vacuna'=>$posvec['vt_nombre_vacuna'], 'vt_observacion'=>$posvec['vt_observacion']));      
      
      $html .= "        <td align=\"center\" >\n";
      $html .= "          <a href= \"".$action['editVac']."\" align=\"center\">\n";
      $html .= "            <sub><img src=\"".$path."/images/edita.png\" border=\"0\" width=\"20\" height=\"20\"></sub>\n";  
      $html .= "          </a>\n";      
      $html .= "        </td>";
        
      $html .= "        <td align=\"center\">\n";
      
      //$action['elimVac'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'BorrarVacuna', array('vt_vacuna_id'=>$posvec['vt_vacuna_id']));
      
      $action['elimVac'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'BorrarVacuna', array('vt_vacuna_id'=>$posvec['vt_vacuna_id']));      
      
      
      //$html .= "          <a href= \"javascript:conf_eliminar('".$action['elimVac']."')\" align=\"center\" onclick=\"\">Elim</a>\n";          
      //$html .= "          <a href= \"javascript:conf_eliminar('".$action['elimVac']."', '".$posvec['vp_vacuna_param_id']."')\" align=\"center\" onclick=\"\">Elim</a>\n";
      
      if(empty($posvec['vp_vacuna_param_id'])){
      
        $html .= "          <a href= \"javascript:conf_eliminar('".$action['elimVac']."', '".$posvec['vp_vacuna_param_id']."')\" align=\"center\" onclick=\"\">\n";      
        $html .= "            <sub><img src=\"".$path."/images/elimina.png\" border=\"0\" width=\"20\" height=\"20\"></sub>\n";
        $html .= "          </a>\n";    
      }
      
      $html .= "        </td>\n";
      
      //$action['paramVac'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'Parametrizar', null);      
      
      $action['paramVac'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'Parametrizar', array('vt_vacuna_id'=>$posvec['vt_vacuna_id'], 'vt_nombre_vacuna'=>$posvec['vt_nombre_vacuna']));
      
      $html .= "        <td align=\"center\" >\n";
      $html .= "          <a href= \"".$action['paramVac']."\" align=\"center\">\n";      
      $html .= "          <sub><img src=\"".$path."/images/modificar.gif\" border=\"0\" width=\"20\" height=\"20\"></sub>\n";      
      
      $html .= "        </td>\n";
                    
      $html .= "      </tr>\n";
    }         
    
    $html .= "</table>  <br>\n";
    
    $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador'],5);    
    
    $html .= "<br>\n";
    
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "   <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
    $html .= "    <td align=\"center\" colspan= \"2\">\n";            
    $html .= "        <input class=\"input-submit\" type=\"submit\" class=\"input-submit\" name=\"btnvolver\" value=\"VOLVER\" >\n";
    $html .= "    </td>\n";
    $html .= "   </form>\n";
    $html .= "  </tr>\n";     
    $html .= "</table>\n";  
    
    $html .= ThemeCerrarTabla();
    
    return $html;
    
  }

  /**
  *Crea la forma en donde se ingresa una nueva vacuna. 
  *@param array $action Arreglo de datos de los links de la aplicacion
  *@return string
  */  
  function FormaNuevaVacuna($action)
  {
  
    $html = "<script>\n";
    $html .= "function valida_envia(){\n";
    $html .= "  if(document.forma1.nombre_vacuna.value.length==0){\n";
    //$html .= "    alert('Debe ingresar el nombre de una vacuna!');\n";
    $html .= "    document.getElementById('error').innerHTML = 'Debe ingresar el nombre de una vacuna!';\n";
    $html .= "    document.forma1.nombre_vacuna.focus();\n";   
    $html .= "    return false;}\n"; 
    
    $html .= "  if(document.forma1.observacion.value.length==0){\n";
    //$html .= "    alert('Debe ingresar el nombre de una vacuna!');\n";
    $html .= "    document.getElementById('error').innerHTML = 'Debe ingresar las observaciones de la vacuna!';\n";
    $html .= "    document.forma1.observacion.focus();\n";   
    $html .= "    return false;}\n";     
    
    $html .= "    document.forma1.submit();\n";   
    $html .= "}\n";    
    $html .= "</script>\n";  
  
    $html  .= ThemeAbrirTabla('CREAR VACUNA');
    $html .= "<form name=\"forma1\" action=\"".$action['aceptar']."\" method=\"post\">\n";
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "  <tr class=\"formulacion_table_list\">\n";
    $html .= "    <td class=\"modulo_buscar\" align=\"center\"><b>Nombre Vacuna</b></td>\n";
    $html .= "    <td>\n";            
    
    $html .= "        <input class=\"input-submit\" type=\"input\" name=\"nombre_vacuna\" value=\"\">\n";
    
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    
    $html .= "  <tr>\n";
    $html .= "    <td align=\"center\" colspan=\"2\"><b>Observacion</b></td>\n";
    $html .= "  </tr>";

    $html .= "  <tr>\n";    
    $html .= "    <td align=\"center\" colspan= \"2\" rowspan=\"2\">\n";
    
    $html .= "        <textarea cols=55 rows=3 name=\"observacion\"></textarea>\n";  
    //$html .= "        <input class=\"input-submit\" type=\"input\" name=\"txtObserva\" value=\"\">\n";
       
    $html .= "    </td>\n";    
    $html .= "  </tr>\n";
    $html .= "</table><br>\n";
    $html .= "<center>\n"; 
    $html .= "  <div id=\"error\" class=\"label_error\"></div>\n";
    $html .= "</center>\n";
    
    $html .= "<table width=\"60%\" align=\"center\">\n";
    $html .= "  <tr>\n"; 
    $html .= "    <td align=\"center\" colspan= \"2\">\n";            
    $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\" value=\"CREAR\" onclick=\"valida_envia()\">\n";
    $html .= "    </td>\n";
    $html .= "   </form>\n";
    $html .= "   <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
    $html .= "    <td align=\"center\" colspan= \"2\">\n";            
    $html .= "        <input class=\"input-submit\" type=\"submit\" class=\"input-submit\" name=\"btnvolver\" value=\"VOLVER\" >\n";
    $html .= "    </td>\n";
    $html .= "   </form>\n";     
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    
    /*
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td align= \"center\">\n";    
    $html .= "      <a href=\"".$action['volver']."\" align=\"center\">Volver</a>\n";
    $html .= "    </td>";
    $html .= "  </tr>\n";     
    $html .= "</table>";     
    */
    $html .= ThemeCerrarTabla();
    
    return $html;
  }
  
  /**
  *Funcion que crea la forma donde se puede editar una vacuna
  *@param array $action Arreglo de datos de los links de la aplicacion
  *@param array $arrVac, contiene los datos de la vacuna a modificar
  *@return string
  */  
  function FormaEditarVacuna($action, $arrVac){
  
    $html .= "<script>
    
              function valida_envia(){\n
              
                if(document.forma1.nombre_vacuna.value.length==0){\n
                  document.getElementById('error').innerHTML = 'Debe ingresar el nombre de una vacuna!';\n
                  document.forma1.nombre_vacuna.focus();\n
                  return false;}\n
                  
                if(document.forma1.observacion.value.length==0){\n
                  document.getElementById('error').innerHTML = 'Debe ingresar las observaciones de la vacuna!';\n
                  document.forma1.observacion.focus();\n
                  return false;}\n
                  
                document.getElementById('error').innerHTML = null;
                
                document.forma1.submit(); 
              }
                  
              </script>";
  
    $html .= ThemeAbrirTabla('MODIFICACION DE VACUNA');
    $html .= "<form name=\"forma1\" action=\"".$action['aceptar']."\" method=\"post\">\n
              <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n
                <tr>\n
                  <td class=\"formulacion_table_list\" align=\"center\" colspan\"1\">\n
                    CODIGO VACUNA\n
                  </td>\n
                    <td class=\"modulo_table_list\" align=\"center\" colspan\"1\">".$arrVac['vt_vacuna_id']."
                    <input type=\"hidden\" name=\"vacuna_id\" value=\"".$arrVac['vt_vacuna_id']."\">\n    
                  </td>\n
                </tr>\n
                <tr>\n
                  <td class=\"formulacion_table_list\" align=\"center\" colspan\"1\">\n
                    NOMBRE VACUNA\n
                  </td>\n
                  <td class=\"modulo_table_list\" align=\"center\" colspan\"1\">
                    <input class=\"input-submit\" type=\"input\" name=\"nombre_vacuna\" value=\"".$arrVac['vt_nombre_vacuna']."\">\n
                  </td>\n
                </tr> \n              
                <tr>\n
                
                <tr>
                  <td align=\"center\" colspan=\"2\"><b>OBSERVACIONES</b></td>\n
                </tr>
                   
                <tr>
                  <td align=\"center\" colspan= \"2\" rowspan=\"2\">
                    <textarea cols=55 rows=3 name=\"observacion\">".$arrVac['vt_observacion']."</textarea>\n
                  </td>                
                </tr>
                </table>  <br>\n";
                
    $html .= "<center>\n
                <div id=\"error\" class=\"label_error\"></div>\n
              </center> <br>\n";                 
                
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\">\n
                <td>\n
                  <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnEditar\" value=\"MODIFICAR\" onclick=\"valida_envia()\">\n                
                </td>\n
                </form>\n
                                
                <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n
                  <td align= \"center\">\n
                    <input class=\"input-submit\" type=\"submit\" class=\"input-submit\" name=\"btnvolver\" value=\"VOLVER\">\n    
                  </td>\n
                </form>\n
              </table>\n";               
    
     
    $html .= ThemeCerrarTabla();
    
    return $html;
  
  }


}

?>