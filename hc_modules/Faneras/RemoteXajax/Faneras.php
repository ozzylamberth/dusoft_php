<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Faneras.php,v 1.1 2009/11/06 14:42:11 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */

  /**
  * Funcion donde se ingresa la informacion de la clasificación de piel y faneras
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function IngresarClasificacion($form)
  {
    $objResponse = new xajaxResponse();
    
    $fnr = AutoCarga::factory("FanerasSQL", "classes", "hc1","Faneras");
    
    $mensaje = "";
    
    if($form['sensibilidad'] == '-1')
      $mensaje = "DEBE HACER LA SELECCIÓN DE LA SENSIBILIDAD";
    else if(!$form['persistencia'])
      $mensaje = "DEBE INDICAR SI ES INTERMITENTE O PERSISTENTE";
      else if(!$form['referencia'])
        $mensaje = "DEBE INDICAR SI ES IRRADIADO O REFERIDO";
    
    if($mensaje != "")
      $objResponse->assign("error","innerHTML",$mensaje);
    else
    {
      $rst = false;
     
      if($form['actualizar'] == '1')
        $rst = $fnr->ActualizarClasificacion($form);
      else
        $rst = $fnr->IngresarClasificacion($form);
      
      if(!$rst)
        $objResponse->assign("error","innerHTML",$fnr->mensajeDeError);
      else
      {
        $sectores = $fnr->ObtenerClasificacion($form['evolucion_id']);
        $html = FormaHistorial($sectores);
        $objResponse->assign("datos_ingresados","innerHTML",$html);
        $objResponse->script("CrearSector(document.clasificacion_faneras)");
      }
    }
    return $objResponse;
  }  
  /**
  * Funcion donde se ingresa la informacion del puntaje de eva
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function IngresarPuntajeEva($form)
  {
    $objResponse = new xajaxResponse();
    
    $fnr = AutoCarga::factory("FanerasSQL", "classes", "hc1","Faneras");
    
    $rst = false;
     
    if($form['actualizar'] == '1')
      $rst = $fnr->ActualizarPuntajeEva($form);
    else
      $rst = $fnr->IngresarPuntajeEva($form);
      
    if(!$rst)
      $objResponse->assign("error","innerHTML",$fnr->mensajeDeError);
    else
      $objResponse->assign("actualizar_eva","value","1");
    
    return $objResponse;
  }
  /**
  * Funcion donde se elimina la informacion de un sector
  *
  * @param array $form Vector con los datos de la forma
  *
  * @return object
  */
  function EliminarClasificacion($form)
  {
    $objResponse = new xajaxResponse();
    $objResponse->setCharacterEncoding("ISO-8859-1");
    
    $fnr = AutoCarga::factory("FanerasSQL", "classes", "hc1","Faneras");
    
    $rst = false;
     
    $rst = $fnr->EliminarClasificacion($form);
      
    if(!$rst)
      $objResponse->assign("error","innerHTML",$fnr->mensajeDeError);
    else
    {
      $sectores = $fnr->ObtenerClasificacion($form['evolucion_id']);
      $html = FormaHistorial($sectores);
      $objResponse->assign("datos_ingresados","innerHTML",$html);

      $objResponse->script("Dibujar('".$form['area_id']."',0);");
      $objResponse->script("OcultarSpan('Contenedor');");
    }
    return $objResponse;
  }
  /**
  * funcion donde se crea la tabla con la informacion ingrsda
  *
  * @param array $sectores Arreglo de datos con la informacion de los sectores
  *
  * @return string
  */
  function FormaHistorial($sectores)
  {
    $html = "";
    if(!empty($sectores))
    {
      $html .= "  <table align=\"left\" width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"formulacion_table_list\" align=\"center\">\n";
      $html .= "      <td width=\"1%\"></td>\n";
      $html .= "      <td >SENSIBILIDAD</td>\n";
      $html .= "      <td width=\"10%\">INTERMITENTE</td>\n";
      $html .= "      <td width=\"10%\">PERSISTENTE</td>\n";
      $html .= "      <td >QUE LO AUMENTA</td>\n";
      $html .= "      <td >QUE LO DISMINUYE</td>\n";
      $html .= "      <td width=\"10%\">IRRADIADO</td>\n";
      $html .= "      <td width=\"10%\">REFERIDO</td>\n";
      $html .= "      <td>OBSERVACION</td>\n";
      $html .= "    </tr>\n";
      foreach($sectores as $key => $dtl)
      {
        $html .= "    <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td class=\"label\">".$dtl['franja']."</td>\n";
        $html .= "      <td>".$dtl['sensibiliad_descripcion']."</td>\n";
        $html .= "      <td align=\"center\">".(($dtl['persistencia'] == '2')? "X":"&nbsp;")."</td>\n";
        $html .= "      <td align=\"center\">".(($dtl['persistencia'] == '1')? "X":"&nbsp;")."</td>\n";
        $html .= "      <td>".$dtl['aumento_descripcion']."&nbsp;</td>\n";
        $html .= "      <td>".$dtl['disminucion_descripcion']."&nbsp;</td>\n";
        $html .= "      <td align=\"center\">".(($dtl['referencia'] == '1')? "X":"&nbsp;")."</td>\n";
        $html .= "      <td align=\"center\">".(($dtl['referencia'] == '2')? "X":"&nbsp;")."</td>\n";
        $html .= "      <td>".$dtl['observacion']."&nbsp;</td>\n";
        $html .= "    </tr>\n";
      }
      $html .= "  </table>\n";
    }
    
    return $html;
  }
?>