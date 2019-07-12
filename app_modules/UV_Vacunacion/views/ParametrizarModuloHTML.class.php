<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ParametrizarModuloHTML.class.php,v 1.2 2008/05/28 15:18:54 gerardo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Gerardo Amador Vidal
  */
  /**
  * Clase Control: ParametrizarModuloHTML
  * Clase encargada de crear las formas utilizadas para la creacion y modificacion de los parametros de las vacunas
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Gerardo Amador Vidal
  */

class ParametrizarModuloHTML
{
  
  /**
  * Constructor de la clase
  */
  function ParametrizarModuloHTML(){}
  
  /**
  *Funcion que crea la forma donde se muestan los parametros de una vacuna
  *@param array $action Vector con los links de la forma
  *@param array $arrVac arreglo con los datos de la vacuna
  *@param array $arrParam arreglo con los registros de los parametros 
  *@return string
  */
  function FormaListarParametros($action, $arrVac=null, $arrParam=null)
  {
    
    $html .= "<script language=\"javaScript\">
              function mOvr(src,clrOver)
               {
                 src.style.background = clrOver;
               }

               function mOut(src,clrIn)
               {
                 src.style.background = clrIn;
               }  \n";
               
    $html .= "function conf_eliminar(valor, id_valor){\n
                //bandera = confirm('Desea eliminar este parametro?');\n     
                bandera = confirm('Desea deshabilitar este parametro?');\n
                
                if(bandera == true)                    
                  window.location.href=valor;                     
                //else
                //alert('No es posible eliminar este parametro!'); 
                        
              }\n
                              
             </script> \n";           
                 
  
    $html .= ThemeAbrirTabla("LISTADO DE PARAMETROS");   
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td class=\"formulacion_table_list\" align=\"center\" colspan\"1\">CODIGO VACUNA</td>\n";
    $html .= "    <td class=\"modulo_table_list\" align=\"center\" colspan\"1\">".$arrVac['vt_vacuna_id']."</td>\n";
    $html .= "  </tr>\n";
    $html .= "  <tr>\n";
    $html .= "    <td class=\"formulacion_table_list\" align=\"center\" colspan\"1\">NOMBRE VACUNA</td>\n";
    $html .= "    <td class=\"modulo_table_list\" align=\"center\" colspan\"1\">".$arrVac['vt_nombre_vacuna']."</td>\n";
    $html .= "  </tr>\n";    
    $html .= "</table>  <br>\n";
    
    
    $html .= "<table border=\"0\" width=\"30%\" align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <a class=\"label_error\" href=\"".$action['crearNueParam']."\">Crear Nuevo Parametro</a>\n";
    $html .= "    </td>\n";        
    $html .= "  </tr>\n";    
    $html .= "</table>  <br>\n";        

    
    $html .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "  <tr class=\"formulacion_table_list\">\n";
    //$html .= "    <td align=\"center\" colspan\"1\">Codigo</td>\n";
    $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"2\">PARAMETRO</td>\n";
    $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"2\">RECIEN NACIDO</td>\n";    
    $html .= "    <td align=\"center\" colspan=\"3\" rowspan=\"1\">RANGO EDAD</td>\n";
    $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"2\">OBSERVACIONES</td>\n";    
    $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"2\">USUARIO REGISTRO</td>\n";
    $html .= "    <td align=\"center\" colspan=\"2\" rowspan=\"1\">ACCIONES</td>\n";        
    $html .= "  </tr>\n";
    
    $html .= "  <tr class=\"formulacion_table_list\">";
    $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"1\">MIN</td>\n";
    $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"1\">MAX</td>\n";
    $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"1\">UNIDAD</td>\n";
    $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"1\">MODIFICAR</td>\n";
    $html .= "    <td align=\"center\" colspan=\"1\" rowspan=\"1\">DESABILITAR</td>\n";       
    $html .= "  </tr>\n";    
    
    $path = GetThemePath();
    
    //for ($i=0; $i<10; $i++){
    foreach($arrParam as $key => $posvec ){
    
      $html .= "  <tr class='modulo_list_claro' onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
      
      $html .= "    <td align=\"center\" colspan=\"1\">".$posvec['vp_vacuna_param_id']."</td>\n";
            
      //$html .= "    <td align=\"center\" colspan=\"1\">".$posvec['vp_sw_rn']."</td>\n";      
      
      if($posvec['vp_sw_rn'] == 1)
        $valRN = "SI";
      
      if($posvec['vp_sw_rn'] == 0)
        $valRN = "NO";  
      
        
              
        
      if($posvec['vp_unidad'] == 1)
        $valUnid = "SEMANAS";
        
      if($posvec['vp_unidad'] == 2)
        $valUnid = "MESES";   
      
      if($posvec['vp_unidad'] == 3)
        $valUnid = "AÑOS"; 
        
      if($posvec['vp_unidad'] == 0)
        $valUnid = "NO APLICA";          

                 
      
      $html .= "    <td align=\"center\" colspan=\"1\">".$valRN."</td>\n";  
      $html .= "    <td align=\"center\" colspan=\"1\">".$posvec['vp_edad_min']."</td>\n";
      $html .= "    <td align=\"center\" colspan=\"1\">".$posvec['vp_edad_max']."</td>\n";
      $html .= "    <td align=\"center\" colspan=\"1\">".$valUnid."</td>\n";
      $html .= "    <td align=\"justify\" colspan=\"1\">".$posvec['vp_observacion']."</td>\n";      
      $html .= "    <td align=\"center\" colspan=\"1\">".$posvec['su_usuario']."</td>\n";
      
      $action['editParam'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'ModificarParametro', array('vt_vacuna_id'=>$arrVac['vt_vacuna_id'], 'vt_nombre_vacuna'=>$arrVac['vt_nombre_vacuna'], 'vp_vacuna_param_id'=>$posvec['vp_vacuna_param_id'],'vp_sw_rn'=>$posvec['vp_sw_rn'], 'vp_edad_min'=>$posvec['vp_edad_min'], 'vp_edad_max'=>$posvec['vp_edad_max'], 'vp_unidad'=>$posvec['vp_unidad'], 'vp_observacion'=>$posvec['vp_observacion']));       
      
      $html .= "    <td align=\"center\" colspan=\"1\">\n";
      $html .= "          <a href= \"".$action['editParam']."\" align=\"center\">\n";
      $html .= "            <sub><img src=\"".$path."/images/edita.png\" border=\"0\" width=\"20\" height=\"20\"></sub>\n";      
      $html .= "    </td>\n";
      
      $action['elimParam'] = ModuloGetURL('app', 'UV_Vacunacion', 'controller', 'BorrarParametro', array('vt_vacuna_id'=>$arrVac['vt_vacuna_id'], 'vt_nombre_vacuna'=>$arrVac['vt_nombre_vacuna'], 'vp_vacuna_param_id'=>$posvec['vp_vacuna_param_id']));      
            
      $html .= "    <td align=\"center\" colspan=\"1\">\n";
      $html .= "          <a href= \"javascript:conf_eliminar('".$action['elimParam']."', '".$posvec['vp_vacuna_param_id']."')\" align=\"center\">\n";
      $html .= "            <sub><img src=\"".$path."/images/elimina.png\" border=\"0\" width=\"20\" height=\"20\"></sub>\n";    
      $html .= "    </td>\n";    
      
      $html .= "  </tr>\n";    
    
    }
    
    $html .= "</table>  <br>\n";
    
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "   <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
    $html .= "    <td align=\"center\" colspan= \"2\">\n";            
    $html .= "        <input class=\"input-submit\" type=\"submit\" class=\"input-submit\" name=\"btnvolver\" value=\"VOLVER\" >\n";
    $html .= "    </td>\n";
    $html .= "   </form>\n";
    $html .= "  </tr>\n";     
    $html .= "</table>";      
    
    
    $html .= ThemeCerrarTabla();    
    
    
    return $html;
  }
  
  /**
  *Funcion crea la forma donde se crea un nuevo parametro de una vacuna
  *@param array $action Vector con los links de la forma
  *@param array $arrParam, arreglo con los datos que identican el nombre y la clave de la vacuna a la cual pertenece el parametro
  *@return string 
  */
  function FormaNuevoParametro($action, $arrParam=null)
  {
  
    $html .= "<script>\n
    
              function validarEntero(valor){
                valor = parseInt(valor);
                
                if(isNaN(valor)){
                  return \"\";
                }
                else{
                  return valor;
                } 
              }    
    
              function valida_envia(){\n
                
                valchkRecNac = document.forma1.chkRecNac.checked;
                              
                if(document.forma1.EdadMin.value.length==0 && !valchkRecNac){\n
                  document.getElementById('error').innerHTML = 'Debe ingresar la Edad Minima!';\n
                  document.forma1.EdadMin.focus();\n
                  return false;}\n
                  
                if(document.forma1.EdadMax.value.length==0 && !valchkRecNac){\n
                  document.getElementById('error').innerHTML = 'Debe ingresar la Edad Maxima!';\n
                  document.forma1.EdadaMax.focus();\n
                  return false;}\n

                       
                EdadMin = document.forma1.EdadMin.value;
                EdadMin = validarEntero(EdadMin);  
                document.forma1.EdadMin.value=EdadMin;
                
                if(EdadMin === \"\" && !valchkRecNac){
                  document.getElementById('error').innerHTML = 'Debe introducir un numero entero en la Edad Minima!';\n
                  return false;
                }
                
                EdadMax = document.forma1.EdadMax.value;
                EdadMax = validarEntero(EdadMax);  
                document.forma1.EdadMax.value=EdadMax;                                                 
              
                if(EdadMax === \"\" && !valchkRecNac){
                  document.getElementById('error').innerHTML = 'Debe introducir un numero entero en la Edad Maxima!';\n
                  return false;
                }
                
                
                if(EdadMax <= EdadMin && !valchkRecNac){
                  document.getElementById('error').innerHTML = 'La Edad Maxima debe ser MAYOR, que la Edad Minima!';\n
                  return false;                
                }                
                
                if(document.forma1.observacion.value.length==0){\n
                  document.getElementById('error').innerHTML = 'Debe ingresar las observaciones de la vacuna!';\n
                  document.forma1.observacion.focus();\n
                  return false;
                }\n                
                            
                document.getElementById('error').innerHTML = null;
                
                
                alert(  'RecNac: ' + document.forma1.RecNac.value + ' \\n' +
                        'Min: ' + document.forma1.EdadMin.value + ' \\n' +
                        'Max: ' + document.forma1.EdadMax.value + ' \\n' +
                        'Unid: ' + document.forma1.Unidad.value 
                );
                
                document.forma1.submit();                                
                                
              }
              
              function valida_RecNac(){
                  
                if(document.forma1.chkRecNac.checked == true){
                  document.forma1.RecNac.value = 1;
                  
                  document.forma1.EdadMin.disabled = true;
                  document.forma1.EdadMax.disabled = true;
                  
                  document.forma1.optUnid[0].disabled = true;
                  document.forma1.optUnid[1].disabled = true;
                  document.forma1.optUnid[2].disabled = true;
                  
                  document.forma1.EdadMin.value = 0;
                  document.forma1.EdadMax.value = 0;
                  document.forma1.Unidad.value = 0;                  
                  
                  //alert('Checkeado!!!  ' + document.forma1.RecNac.value);
                }
                
                if(document.forma1.chkRecNac.checked == false){
                  document.forma1.RecNac.value = 0;                  
                  
                  document.forma1.EdadMin.disabled = false;
                  document.forma1.EdadMax.disabled = false;
                                   
                  document.forma1.optUnid[0].disabled = false;
                  document.forma1.optUnid[1].disabled = false;
                  document.forma1.optUnid[2].disabled = false;
                                    
                  document.forma1.optUnid[0].checked = true;
                  
                  //alert('Sin Checkear!!!  '+  document.forma1.RecNac.value);
                }                               
              
              }
              
              
              function valida_Unidad(){
                  
                if(document.forma1.optUnid[0].checked == true)   
                  document.forma1.Unidad.value = 1;         
                
                if(document.forma1.optUnid[1].checked == true)   
                  document.forma1.Unidad.value = 2;
                  
                if(document.forma1.optUnid[2].checked == true)   
                  document.forma1.Unidad.value = 3;                                                  
              }
               
              
              </script>";
   
    $html .= ThemeAbrirTabla('CREACION DE PARAMETRO');
    $html .= "<form name=\"forma1\" action=\"".$action['aceptar']."\" method=\"post\">
              <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n
                <tr>\n
                  <td class=\"formulacion_table_list\" align=\"left\" colspan=\"2\"  >\n
                    Codigo Vacuna\n
                  </td>\n
                  <td class=\"modulo_table_list\" align=\"left\" colspan=\"2\" >\n
                    ".$arrParam['vt_vacuna_id']." 
                    <input type=\"hidden\" name=\"vt_vacuna_id\" value=\"".$arrParam['vt_vacuna_id']."\">\n       
                  </td>\n
                </tr>\n
                <tr>\n
                  <td class=\"formulacion_table_list\" align=\"left\" colspan=\"2\">\n
                    Nombre Vacuna\n
                  </td>\n
                  <td class=\"modulo_table_list\" align=\"left\" colspan=\"2\">
                    ".$arrParam['vt_nombre_vacuna']."\n
                    <input type=\"hidden\" name=\"vt_nombre_vacuna\" value=\"".$arrParam['vt_nombre_vacuna']."\">\n                    
                  </td>\n
                </tr> \n              
                <tr>\n
                  <td class=\"formulacion_table_list\" align=\"center\" colspan=\"2\">\n
                    Recien Nacido\n
                  </td>\n
                  <td class=\"modulo_table_list\" align=\"center\" colspan\"2\">\n
                    <input type=\"checkbox\" name=\"chkRecNac\" onclick=\"valida_RecNac()\">\n
                    <input type=\"hidden\" name=\"RecNac\" value=\"0\" >\n                                   
                  </td>\n
                  
                </tr>\n";
                
                
    $html .=  " <tr>\n
                  <td class=\"formulacion_table_list\" align=\"center\" colspan=\"1\" width=\"25%\" >\n
                    EDAD MIN\n
                  </td>\n
                  <td class=\"formulacion_table_list\" align=\"center\" colspan=\"1\" width=\"25%\" >\n
                    EDAD MAX\n
                  </td>\n
                  <td class=\"formulacion_table_list\" align=\"center\" colspan=\"2\" width=\"50%\" >\n
                    UNIDADES\n
                  </td>\n                   
                </tr>\n
                
                <tr>
                  <td class=\"modulo_table_list\" align=\"center\" colspan=\"1\">\n
                    <input class=\"input-submit\" type=\"input\" name=\"EdadMin\" value=\"\" size=\"5\" >\n                  
                  </td>\n
                  <td class=\"modulo_table_list\" align=\"center\" colspan=\"1\">\n
                    <input class=\"input-submit\" type=\"input\" name=\"EdadMax\" value=\"\" size=\"5\" >\n                  
                  </td>\n                           
                
                  <td class=\"modulo_table_list\" colspan=\"1\" align=\"center\" >
                    <input type=\"radio\" name=\"optUnid\" checked onclick=\"valida_Unidad()\" >Semanas
                    <input type=\"radio\" name=\"optUnid\"  onclick=\"valida_Unidad()\" >Meses
                    <input type=\"radio\" name=\"optUnid\"  onclick=\"valida_Unidad()\" >Años
                    
                    <input type=\"hidden\" name=\"Unidad\" value= \"1\" >\n                    
                  </td>
                                  
                </tr>
                
                <tr>
                  <td class=\"formulacion_table_list\" align=\"center\" colspan=\"4\" ><b>OBSERVACIONES</b></td>\n
                </tr>
                   
                <tr>
                  <td align=\"center\" colspan=\"4\" rowspan=\"3\" >
                    <textarea cols=60 rows=3 name=\"observacion\"></textarea>\n
                  </td>                
                </tr>               
                 
              </table>  <br>\n";
              
    $html .= "<center>\n
                <div id=\"error\" class=\"label_error\"></div>\n
              </center> <br>\n";              
              
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\">\n
                <td align=\"center\" colspan= \"2\">\n
                  <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\" value=\"CREAR\" onclick=\"valida_envia()\">\n
                </td>
                </form>
                
                <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n
                  <td align= \"center\">
                    <input class=\"input-submit\" type=\"submit\" class=\"input-submit\" name=\"btnvolver\" value=\"VOLVER\">\n    
                  </td>
                </form>
              </table>\n";                            
         
    $html .= ThemeCerrarTabla(); 
    
    
    return $html; 
  
  }
  
  

 /**
  *Funcion crea la forma donde se modifica el parametro de una vacuna
  *@param array $action Vector con los links de la forma
  *@param array $arrParam, arreglo con los datos que identican el nombre y la clave de la vacuna a la cual pertenece el parametro
  *@return string 
  */
  function FormaEditarParametro($action, $arrParam)
  {
  
    $html .= "<script>\n
    
              function validarEntero(valor){
                valor = parseInt(valor);
                
                if(isNaN(valor)){
                  return \"\";
                }
                else{
                  return valor;
                } 
              }    
    
              function valida_envia(){\n
                
                valchkRecNac = document.forma1.chkRecNac.checked;
                              
                if(document.forma1.EdadMin.value.length==0 && !valchkRecNac){\n
                  document.getElementById('error').innerHTML = 'Debe ingresar la Edad Minima!';\n
                  document.forma1.EdadMin.focus();\n
                  return false;}\n
                  
                if(document.forma1.EdadMax.value.length==0 && !valchkRecNac){\n
                  document.getElementById('error').innerHTML = 'Debe ingresar la Edad Maxima!';\n
                  document.forma1.EdadaMax.focus();\n
                  return false;}\n

                       
                EdadMin = document.forma1.EdadMin.value;
                EdadMin = validarEntero(EdadMin);  
                document.forma1.EdadMin.value=EdadMin;
                
                if(EdadMin === \"\" && !valchkRecNac){
                  document.getElementById('error').innerHTML = 'Debe introducir un numero entero en la Edad Minima!';\n
                  return false;
                }
                
                EdadMax = document.forma1.EdadMax.value;
                EdadMax = validarEntero(EdadMax);  
                document.forma1.EdadMax.value=EdadMax;                                                 
              
                if(EdadMax === \"\" && !valchkRecNac){
                  document.getElementById('error').innerHTML = 'Debe introducir un numero entero en la Edad Maxima!';\n
                  return false;
                }
                
                
                if(EdadMax <= EdadMin && !valchkRecNac){
                  document.getElementById('error').innerHTML = 'La Edad Maxima debe ser MAYOR, que la Edad Minima!';\n
                  return false;                
                }                
                
                if(document.forma1.observacion.value.length==0){\n
                  document.getElementById('error').innerHTML = 'Debe ingresar las observaciones de la vacuna!';\n
                  document.forma1.observacion.focus();\n
                  return false;
                }\n                
                
                valida_Unidad();                       
                valida_RecNac();
                     
                document.getElementById('error').innerHTML = null;
                
                
                alert(  'RecNac: ' + document.forma1.RecNac.value + ' \\n' +
                        'Min: ' + document.forma1.EdadMin.value + ' \\n' +
                        'Max: ' + document.forma1.EdadMax.value + ' \\n' +
                        'Unid: ' + document.forma1.Unidad.value 
                );
                
                document.forma1.submit();                                
                                
              }
              
              function valida_RecNac(){
                  
                if(document.forma1.chkRecNac.checked == true){
                  document.forma1.RecNac.value = 1;
                  
                  document.forma1.EdadMin.disabled = true;
                  document.forma1.EdadMax.disabled = true;
                  
                  document.forma1.optUnid[0].disabled = true;
                  document.forma1.optUnid[1].disabled = true;
                  document.forma1.optUnid[2].disabled = true;
                  
                  document.forma1.EdadMin.value = 0;
                  document.forma1.EdadMax.value = 0;
                  document.forma1.Unidad.value = 0;                  
                  
                  //alert('Checkeado!!!  ' + document.forma1.RecNac.value);
                }
                
                if(document.forma1.chkRecNac.checked == false){
                  document.forma1.RecNac.value = 0;                  
                  
                  document.forma1.EdadMin.disabled = false;
                  document.forma1.EdadMax.disabled = false;
                                   
                  document.forma1.optUnid[0].disabled = false;
                  document.forma1.optUnid[1].disabled = false;
                  document.forma1.optUnid[2].disabled = false;
                  
                  
                  if(!document.forma1.optUnid[0].checked && !document.forma1.optUnid[1].checked && !document.forma1.optUnid[2].checked){                  
                    document.forma1.optUnid[0].checked = true;
                  }
                  
                  //alert('Sin Checkear!!!  '+  document.forma1.RecNac.value);
                }                               
              
              }
              
              
              function valida_Unidad(){
                  
                if(document.forma1.optUnid[0].checked == true)   
                  document.forma1.Unidad.value = 1;         
                
                if(document.forma1.optUnid[1].checked == true)   
                  document.forma1.Unidad.value = 2;
                  
                if(document.forma1.optUnid[2].checked == true)   
                  document.forma1.Unidad.value = 3;                                                  
              }

               
              
              </script>";
   
    $html .= ThemeAbrirTabla('MODIFICACION DE PARAMETRO');
    $html .= "<form name=\"forma1\" action=\"".$action['aceptar']."\" method=\"post\">
              <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n
                <tr>\n
                  <td class=\"formulacion_table_list\" align=\"left\" colspan=\"2\" width=\"40%\">\n
                    CODIGO VACUNA\n
                  </td>\n
                  <td class=\"modulo_table_list\" align=\"center\" colspan=\"2\" width=\"60%\">
                    ".$arrParam['vt_vacuna_id']." 
                    <input type=\"hidden\" name=\"vt_vacuna_id\" value=\"".$arrParam['vt_vacuna_id']."\">\n       
                  </td>\n
                </tr>\n
                <tr>\n
                  <td class=\"formulacion_table_list\" align=\"left\" colspan=\"2\">\n
                    NOMBRE VACUNA\n
                  </td>\n
                  <td class=\"modulo_table_list\" align=\"center\" colspan=\"2\">
                    ".$arrParam['vt_nombre_vacuna']."\n
                    <input type=\"hidden\" name=\"vt_nombre_vacuna\" value=\"".$arrParam['vt_nombre_vacuna']."\">\n                    
                  </td>\n
                </tr>\n";
       
                
    if ($arrParam['vp_sw_rn']==1){
      $valRecNac = "checked";
      $disEdad = "disabled";
      //$valEdadMin = "";
      //$valEdadMax = "";
      $arrParam['vp_edad_min'] = "0";
      $arrParam['vp_edad_max'] = "0";
      $arrParam['vp_unidad'] = 0;      
      
    }else{
      
      //if ($arrParam['vp_sw_rn']==0){
        $valRecNac = "";
        $disEdad = "";
        //$arrParam['vp_unidad'] = 1;         
      //}
      
      /*if(empty($arrParam['vp_unidad'])){
        $arrParam['vp_unidad']= 1;
      }*/
      
      
      if ($arrParam['vp_unidad'] == 1){
        $valUnid1 = "checked"; 
      }
      else if ($arrParam['vp_unidad'] == 2){
        $valUnid2 = "checked";
      }             
      else if ($arrParam['vp_unidad'] == 3){
        $valUnid3 = "checked";
      }
      /*else{
        $arrParam['vp_unidad'] = 1;
        $valUnid1 = "checked";     
      }*/     
    
    }           
                
                         
                
    $html .=   "<tr>
                  <td class=\"formulacion_table_list\" align=\"left\" colspan=\"1\" width=\"25%\" >\n
                    PARAMETRO                  
                  </td>
                  <td class=\"modulo_table_list\" align=\"center\" colspan=\"1\" width=\"25%\" >
                    ".$arrParam['vp_vacuna_param_id']."\n
                    <input type=\"hidden\" name=\"vp_vacuna_param_id\" value=\"".$arrParam['vp_vacuna_param_id']."\">\n                     
                  </td>
                  <td class=\"formulacion_table_list\" align=\"center\" colspan=\"1\" width=\"25%\" >\n
                    RECIEN NACIDO\n
                  </td>\n 
                  <td class=\"modulo_table_list\" align=\"center\" colspan=\"1\" width=\"25%\" >\n
                    <input type=\"checkbox\" name=\"chkRecNac\" ".$valRecNac." onclick=\"valida_RecNac()\" >\n
                    <input type=\"hidden\" name=\"RecNac\" value=\"".$arrParam['vp_sw_rn']."\" >\n                                   
                  </td>\n                                     
                </tr>";
    
                                                            

             
    //$html .= "   <tr><td colspan=\"3\" rowspan=\"1\">   </td></tr>\n";             
      
              
    $html .= "  <tr>\n
                  <td class=\"formulacion_table_list\" align=\"center\" width=\"5\" colspan=\"1\" width=\"30%\" >\n
                    EDAD MIN\n
                  </td>\n
                  <td class=\"formulacion_table_list\" align=\"center\" width=\"5\" colspan=\"1\" width=\"30%\" >\n
                    EDAD MAX\n
                  </td>\n
                  <td class=\"formulacion_table_list\" align=\"center\" colspan=\"2\" width=\"40%\">\n
                    UNIDADES\n
                  </td>\n
                </tr>\n
                
                <tr>\n
                  <td  align=\"center\" colspan=\"1\" class=\"modulo_table_list\">\n
                    <input class=\"input-submit\" type=\"input\" name=\"EdadMin\" ".$disEdad." value=\"".$arrParam['vp_edad_min']."\" size=\"5\" >\n                  
                  </td>\n                  
                  <td  align=\"center\" colspan=\"1\" class=\"modulo_table_list\">\n
                    <input class=\"input-submit\" type=\"input\" name=\"EdadMax\" ".$disEdad." value=\"".$arrParam['vp_edad_max']."\" size=\"5\" >\n                  
                  </td>\n
                  
                  
                  <td class=\"modulo_table_list\" align=\"center\" colspan=\"2\">
                    <input type=\"radio\" name=\"optUnid\" ".$disEdad." ".$valUnid1." onclick=\"valida_Unidad()\" >Semanas
                    <input type=\"radio\" name=\"optUnid\" ".$disEdad." ".$valUnid2." onclick=\"valida_Unidad()\" >Meses
                    <input type=\"radio\" name=\"optUnid\" ".$disEdad." ".$valUnid3." onclick=\"valida_Unidad()\" >Años
                    
                    <input type=\"hidden\" name=\"Unidad\" value=\"".$arrParam['vp_unidad']."\" >\n                    
                  </td>                                    
               
                  
                  
                </tr>\n
                
                <tr>
                  <td class=\"formulacion_table_list\" align=\"center\" colspan=\"4\" ><b>OBSERVACIONES</b></td>\n
                </tr>
                   
                <tr>
                  <td align=\"center\" colspan=\"4\" rowspan=\"3\" >
                    <textarea cols=60 rows=3 name=\"observacion\">".$arrParam['vp_observacion']."</textarea>\n
                  </td>                
                </tr>               
                 
              </table>  <br>\n";
              
    $html .= "<center>\n
                <div id=\"error\" class=\"label_error\"></div>\n
              </center> <br>\n";              
              
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\">\n
                <td align=\"center\" colspan= \"2\">\n
                  <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnEditar\" value=\"MODIFICAR\" onclick=\"valida_envia()\">\n
                </td>
                </form>
                
                <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n
                  <td align= \"center\">
                    <input class=\"input-submit\" type=\"submit\" class=\"input-submit\" name=\"btnvolver\" value=\"VOLVER\">\n    
                  </td>
                </form>
              </table>\n";                            
         
    $html .= ThemeCerrarTabla(); 
    
    
    return $html; 
  
  }  
   
}  
?>