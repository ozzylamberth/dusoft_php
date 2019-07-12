<?php
	/**
	* Archivo Xajax
	* Tiene como responsabilidad hacer el manejo de las funciones
	* que son invocadas por medio de xajax
	*
	* @package IPSOFT-SIIS
	* @version 1.0 $
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	
	IncludeClass("ClaseHTML");
	IncludeClass("ClaseUtil");
  	
  /**
  * Funcion que permite mostrar las farmacias existentes
  *
  * @param array $form Arreglo de datos de la forma
  *
  * @return Object $objResponse objeto de respuesta al formulario  
  */
	function ListaFarmacias($form)
	{
		$objResponse = new xajaxResponse();
    
		$mdl = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");
		$html = "";
    if($form['empresa_id'] != "-1")
    {
      $centros = $mdl->ObtenerCentrosUtilidad($form['empresa_id']);
			$contador = 1;			
			$est = "modulo_list_oscuro";
      $html .= "<table class=\"modulo_table_list\" border=\"0\" align=\"center\" width=\"100%\">\n";
      $html .= "  <tr  class=\"".$est."\" >\n";
      
			foreach ($centros as $key =>$dtl)
			{
        $html .= "    <td align=\"left\" width=\"1%\" >\n";
        $html .= "      <input type=\"checkbox\" name=\"centros[".$dtl['centro_utilidad']."]\" value=\"".$dtl['centro_utilidad']."\" checked>\n";
        $html .= "      <input type=\"hidden\"  name=\"descripcion[".$dtl['centro_utilidad']."]\"  value=\"".$dtl['descripcion']."\">\n";
        $html .= "    </td>\n";
        $html .= "    <td width=\"32%\" >".$dtl['descripcion']."</td>\n";
        if($contador=='3')
        {
          $html .= "    </tr>\n";
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $html .= "  <tr class=\"".$est."\"  >\n";
          $contador=0;
        }
        $contador++;
			}
      
      $aux = sizeof($centros)%3;
			if($aux > 0)
      {
        $html .= "      <td colspan=\"".((3-$aux)*2)."\"></td>\n";
      }
      
      $html .= "    </tr>\n";
			$html .= "  </table>\n";
		}	
		$objResponse->assign("farmacias","innerHTML",$html);
		
		return $objResponse;
  }
  /**
  *
  */
  function RotacionFarmacia($centro,$empresa_id,$fechai, $fechaf)
  {
    $objResponse = new xajaxResponse();
    //$mdl = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");
    $mdl = new RotacionGerenciaSQL();
   
    $rotacion = $mdl->ObtenerRotacionXBodega($centro,$empresa_id,$fechai,$fechaf);
    
    /*
    foreach($rotacion as $key => $meses)
    {
      foreach($meses as $k1 => $dtl)
      {
        $objResponse->assign("I".$dtl['codigo_producto']."_".$key,"innerHTML",$dtl['cnt_ingreso']);
        $objResponse->assign("E".$dtl['codigo_producto']."_".$key,"innerHTML",$dtl['cnt_egreso']);
      }
    }*/
    return $objResponse;
  }
?>