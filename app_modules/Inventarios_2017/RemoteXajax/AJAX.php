<?PHP
/*=====================================================================================================
  Llena el Select de Productos de los productos buscados
  $tipoBusqueda: si busca por codigo o por descripcion
/*=====================================================================================================*/
function actualizarSelectProductos($tipoBusqueda,$valor)
{
  $x=new xajaxResponse();

  $obj=new app_Inventarios_user();
  $matrix=$obj->consultaProductos($tipoBusqueda,$valor);
  $linea="---------------------";  $sel = "selected";
 
  $html="<select name=\"s_productos\" id=\"s_productos\" width=\"60%\" class=\"select\" onChange=\"asignarProducto()\">";
  
  //if(count($matrix)>0)
    $html.="    <option value=\"-1\"> ".$linea.$linea.$linea."TODOS".$linea.$linea.$linea." </option>";
  
  foreach($matrix as $key=> $val)
  { $html.="    <option value=\"".$val["codigo_producto"]."\"  > ".substr($val["descripcion"],0,50)."</option>";
    $sel=""; 
  }
  
  $html.="</select>";
  //$x->alert('Valor: '.$cod);
  //$html.="document.form_prod.prod_hide.value=document.getElementById('s_productos').value;";
  
  $x->assign("div_p","innerHTML",$html);
  return $x;
}

//=======================================================================================================

?>