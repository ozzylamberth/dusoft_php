<?php
  $VISTA='HTML';
  include 'includes/enviroment.inc.php';
  IncludeClass('ConexionBD');
  
  $notas = ObtenerNotasOperatorias();
  //echo "<pre>".print_r($notas,true)."</pre>";
  
  foreach($notas as $key => $dtl)
  {
    $acto = CrearActoQx($key);
    
    foreach($dtl as $k => $dtl1)
    {
      $rst = ActualizarDatos($dtl1['ingreso'],$dtl1['hc_nota_operatoria_cirugia_id'],$acto);
    
      if($rst != true) 
      {
        echo "Ha ocurrido un error: ".$rst;
        exit;
      }
    }
  }
  
  echo "Actualizado";
  
  function CrearActoQx($ingreso)
  {
    $cxn = new ConexionBD();
    $cxn->debug = true;
    $cxn->ConexionTransaccion();
    $sql = "SELECT nextval('acto_quirurgico_acto_quiru_seq') AS acto ";
    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;
    
    $acto = $rst->GetRowAssoc($ToUpper = false);
    
    $sql  = " INSERT INTO acto_quirurgico ";
    $sql .= " (";
    $sql .= "   acto_quiru,";
    $sql .= "   ingreso ";
    $sql .= " ) ";
    $sql .= " VALUES ";
    $sql .= " ( ";
    $sql .= "   ".$acto['acto'].", ";
    $sql .= "   ".$ingreso." ";
    $sql .= " )";
    
    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;
    
    $cxn->Commit();
    return $acto['acto'];
  }
  
  function ActualizarDatos($ingreso,$hc_nota_operatoria_cirugia_id,$acto)
  {
    $cxn = new ConexionBD();
    $cxn->debug = true;
    $cxn->ConexionTransaccion();
  
    $sql  = "UPDATE hc_notas_operatorias_cirugias ";
    $sql .= "SET    acto_quiru = ".$acto." ";
    $sql .= "WHERE  hc_nota_operatoria_cirugia_id = ".$hc_nota_operatoria_cirugia_id. " ";
    
    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;
      
    $sql  = "UPDATE hc_cultivos_quirurgicos ";
    $sql .= "SET    acto_quiru = ".$acto." ";
    $sql .= "WHERE  ingreso = ".$ingreso." ";
    
    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;
    
    $sql  = "UPDATE hc_descripcion_cirugia ";
    $sql .= "SET    acto_quiru = ".$acto." ";
    $sql .= "WHERE  ingreso = ".$ingreso." ";
    
    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;
      
    $sql  = "UPDATE hc_hallazgos_quirurgicos ";
    $sql .= "SET    acto_quiru = ".$acto." ";
    $sql .= "WHERE  ingreso = ".$ingreso." ";
    
    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;
    
    $sql  = "UPDATE hc_notaqx_gases_anestesicos ";
    $sql .= "SET    acto_quiru = ".$acto." ";
    $sql .= "WHERE  ingresoid = ".$ingreso." ";
    
    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;
      
    $sql  = "UPDATE hc_notas_operatorias_procedimientos ";
    $sql .= "SET    acto_quiru = ".$acto." ";
    $sql .= "WHERE  hc_nota_operatoria_cirugia_id = ".$hc_nota_operatoria_cirugia_id. " ";
    
    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;
      
    $sql  = "UPDATE hc_notas_operatorias_procedimientos_diags ";
    $sql .= "SET    acto_quiru = ".$acto." ";
    $sql .= "WHERE  hc_nota_operatoria_cirugia_id = ".$hc_nota_operatoria_cirugia_id. " ";

    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;
      
    $sql  = "UPDATE hc_notas_operatorias_procedimientos_opciones ";
    $sql .= "SET    acto_quiru = ".$acto." ";
    $sql .= "WHERE  hc_nota_operatoria_cirugia_id = ".$hc_nota_operatoria_cirugia_id. " ";

    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;
      
    $sql  = "UPDATE hc_patologia_quirurgicos ";
    $sql .= "SET    acto_quiru = ".$acto." ";
    $sql .= "WHERE  ingreso = ".$ingreso." ";
    
    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;
    
    $cxn->Commit();
    return true;
  }
  
  function ObtenerNotasOperatorias()
  {
    $cxn = new ConexionBD();
    $sql  = "SELECT HC.hc_nota_operatoria_cirugia_id, ";
    $sql .= "       HE.ingreso ";
    $sql .= "FROM   hc_notas_operatorias_cirugias HC, ";
    $sql .= "       hc_evoluciones HE ";
    $sql .= "WHERE  HC.acto_quiru IS NULL ";
    $sql .= "AND    HC.evolucion_id = HE.evolucion_id ";
    
    if(!$rst = $cxn->ConexionBaseDatos($sql))
      return false;
    
    $datos = array();
    while(!$rst->EOF)
    {
      $datos[$rst->fields[1]][] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    $rst->Close();
    return $datos;
  }
?>