<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Reintegros.php,v 1.1 2009/01/14 22:22:50 hugo Exp $
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
  * 
  * @return object
  */
  function IngresarLabPlantilla($form)
  {
    $pnt = AutoCarga::factory('Plantillas','classes','app','Os_Mantenimiento_Apoyod');
    $objResponse = new xajaxResponse();
    
    if($form['datos']['opcion_des'] == "")
    {
      $mensaje = "<b class=\"label_error\">FAVOR INGRESAR LA DESCRIPCION DE LA OPCION</b>\n";
      $objResponse->assign("error","innerHTML",$mensaje);
      return $objResponse;
    }
    
    $rst = $pnt->DatosPlantilla($form['datos']);
    $lab_examen = $form['datos']['lab_examen'];
    //$objResponse->alert($lab_examen);
    
    if($rts === false)
    {
      $objResponse->alert($pnt->mensajeDeError);
    }
    else
    {
      if($rst == 0)
      {
        //Insertar Plantilla y opcion
        $rst = $pnt->InsertarPlantilla($form['datos']);
      }
      else
      {
        //Insertar opciones
        
        $rst = $pnt->InsertarOpcionPlantilla($form['datos']);
        
      }
      if(!$rst)
        $mensaje = "<b class=\"label_error\">".$pnt->mensajeDeError."</b>";
      else
      {
        $tecnica = explode("||//",$form['datos']['tecnica']);
        
        $opc = $pnt->BucarOpcionesEditar($form['datos']['cargo'],$form['datos']['lab_examen'],$form['datos']['opcion_id'],$tecnica[0]);
        //$objResponse->alert(print_r($form['datos'],true));
        $html = "	<table border=\"0\" align=\"center\"  width=\"100%\" class=\"modulo_table_list\">";
        foreach($opc as $indice => $valor)
        {
          $html .= "	  <tr class='modulo_list_claro' align=\"center\">";
          $html .= "		  <td width=\"15%\" align=\"center\">".$valor['descripcion']."</td>"; 
          $html .= "		</tr>\n";   
        }
        $html .= "		</table>\n";
        $objResponse->assign("div_opcion", "innerHTML", $html);//
        $mensaje = "<b class=\"normal_10AN\">LOS DATOS SE HAN REGISTRADO SATISFACTORIAMENTE</b>";
        $objResponse->assign("opcion_descripcion","value","");
      }
      $objResponse->assign("error","innerHTML",$mensaje);
    }
    return $objResponse;
  }
  /**
  *
  */
  function PruebaF($cargo,$lab_examen_id,$lab_examen_opcion_id,$tecnica_id)
  {
    $pmt = AutoCarga::factory('Plantillas','classes','app','Os_Mantenimiento_Apoyod');
    $pnt = AutoCarga::factory('PlantillasHTML','views','app','Os_Mantenimiento_Apoyod');
    $objResponse = new xajaxResponse(); 
    
    $rst = $pmt->BuscarParaEditar($cargo,$lab_examen_id,$lab_examen_opcion_id,$tecnica_id);
    $rst2 = $pmt->BucarOpcionesEditar($cargo,$lab_examen_id,$lab_examen_opcion_id,$tecnica_id);
    
    //$objResponse->alert(print_r($rst2,true));
    $htm = $pnt->EditarDatos($rst, $rst2);
   
    
    $objResponse->assign("capa_buscador","innerHTML",$htm);
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  function EliminarOpciones ($opcion_id, $cargo, $lab_examen_id, $lab_examen_opcion_id, $tecnica_id)
  {
    $pmt = AutoCarga::factory('Plantillas','classes','app','Os_Mantenimiento_Apoyod');
    $pnt = AutoCarga::factory('PlantillasHTML','views','app','Os_Mantenimiento_Apoyod');
    $objResponse = new xajaxResponse();
    $rst = $pmt ->EliminarOpcion($opcion_id, $cargo,$lab_examen_id,$lab_examen_opcion_id,$tecnica_id);
    $scp = "Prueba('".$cargo."','".$lab_examen_id."','".$lab_examen_opcion_id."','".$tecnica_id."'); ";
    
    $objResponse->script($scp);
		$html = "	<a class=\"hcPaciente\" href=\"javascript:Actualizar()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a>\n";
    $objResponse->assign("cerrar","innerHTML",$html);
    return $objResponse;
  }

  function ActualizarP($cargo, $lab_examen_id, $lab_examen_opcion_id, $tecnica_id,$form)
  {
    $pmt = AutoCarga::factory('Plantillas','classes','app','Os_Mantenimiento_Apoyod');
    $pnt = AutoCarga::factory('PlantillasHTML','views','app','Os_Mantenimiento_Apoyod');
    $objResponse = new xajaxResponse();
    //$objResponse->alert(print_r($form,true));
    $rst = $pmt->ActualizacionPlan($form['datos'], $cargo, $lab_examen_id, $lab_examen_opcion_id,$tecnica_id);
    $rst2 = $pmt->InsertarSolodescPlantilla ($form['datos'],$cargo, $lab_examen_id, $lab_examen_opcion_id, $tecnica_id );
    
    $htm = $pnt->EditarDatos($rst, $rst2);
    $scp = "Prueba('".$cargo."','".$lab_examen_id."','".$lab_examen_opcion_id."','".$tecnica_id."'); ";
    
    $objResponse->script($scp);
    $html = "	<a class=\"hcPaciente\" href=\"javascript:Actualizar()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a>\n";
    $objResponse->assign("cerrar","innerHTML",$html);  
    return $objResponse;
  }
?>