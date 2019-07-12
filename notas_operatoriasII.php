<?php
  $VISTA='HTML';
  include 'includes/enviroment.inc.php';
  IncludeClass('ConexionBD');
  
  $notas = ObtenerNotasOperatoriasII();
  
  foreach($notas as $k => $dtl)
  {
    $rst = ActualizarNotas($dtl);
    if($rst != true) 
    {
      echo "Ha ocurrido un error: ".$rst;
      exit;
    }
  }
  
  $notas = ObtenerNotasOperatoriasI();
  
  foreach($notas as $k => $dtl)
  {
    $rst = ActualizarGases($dtl);
    if($rst != true) 
    {
      echo "Ha ocurrido un error: ".$rst;
      exit;
    }
  }
  
  echo "Actualizado";
  
  function ObtenerNotasOperatoriasI()
  {
    $sql  = "SELECT HC.hc_nota_operatoria_cirugia_id, ";
    $sql .= "       HC.acto_quiru, ";
    $sql .= "       HE.ingreso ";
    $sql .= "FROM   hc_evoluciones HE, ";
    $sql .= "       hc_notas_operatorias_cirugias HC ";
    $sql .= "WHERE  HE.evolucion_id = HC.evolucion_id ";
    $sql .= "GROUP BY 1,2,3 ";
    $sql .= "HAVING COUNT(*) = 1 ";
    
    $cxn = new ConexionBD();
    
    if(!$rst = $cxn->ConexionBaseDatos($sql))
      return false;
    
    $datos = array();
    while(!$rst->EOF)
    {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    $rst->Close();
    return $datos;
  }  
  
  function ObtenerNotasOperatoriasII()
  {
    $sql  = "SELECT HC.hc_nota_operatoria_cirugia_id, ";
    $sql .= "       HC.acto_quiru, ";
    $sql .= "       HE.ingreso, ";
    $sql .= "       HE.evolucion_id ";
    $sql .= "FROM   hc_evoluciones HE, ";
    $sql .= "       hc_notas_operatorias_cirugias HC ";
    $sql .= "WHERE  HE.evolucion_id = HC.evolucion_id ";
    
    $cxn = new ConexionBD();
    
    if(!$rst = $cxn->ConexionBaseDatos($sql))
      return false;
    
    $datos = array();
    while(!$rst->EOF)
    {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    }
    $rst->Close();
    return $datos;
  }
  
  function ActualizarNotas($datos)
  {
    $cxn = new ConexionBD();
    
    $cxn->ConexionTransaccion();
    
    $sql  = "UPDATE  hc_descripcion_cirugia ";
    $sql .= "SET     acto_quiru = ".$datos['acto_quiru'].", ";
    $sql .= "        hc_nota_operatoria_cirugia_id = ".$datos['hc_nota_operatoria_cirugia_id']." ";
    $sql .= "WHERE   ingreso = ".$datos['ingreso']." ";
    $sql .= "AND     evolucion_id = ".$datos['evolucion_id']." ";
    $sql .= "AND     hc_nota_operatoria_cirugia_id IS NULL ";
    
    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;
      
    $sql  = "UPDATE  hc_hallazgos_quirurgicos ";
    $sql .= "SET     acto_quiru = ".$datos['acto_quiru'].", ";
    $sql .= "        hc_nota_operatoria_cirugia_id = ".$datos['hc_nota_operatoria_cirugia_id']." ";
    $sql .= "WHERE   ingreso = ".$datos['ingreso']." ";
    $sql .= "AND     evolucion_id = ".$datos['evolucion_id']." ";
    $sql .= "AND     hc_nota_operatoria_cirugia_id IS NULL ";

    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;

    $sql  = "UPDATE  hc_patologia_quirurgicos ";
    $sql .= "SET     acto_quiru = ".$datos['acto_quiru'].", ";
    $sql .= "        hc_nota_operatoria_cirugia_id = ".$datos['hc_nota_operatoria_cirugia_id']." ";
    $sql .= "WHERE   ingreso = ".$datos['ingreso']." ";
    $sql .= "AND     evolucion_id = ".$datos['evolucion_id']." ";
    $sql .= "AND     hc_nota_operatoria_cirugia_id IS NULL ";

    if(!$rst = $cxn->ConexionTransaccion($sql))
      return $cxn->mensajeDeError;

    $sql  = "UPDATE  hc_cultivos_quirurgicos ";
    $sql .= "SET     acto_quiru = ".$datos['acto_quiru'].", ";
    $sql .= "        hc_nota_operatoria_cirugia_id = ".$datos['hc_nota_operatoria_cirugia_id']." ";
    $sql .= "WHERE   ingreso = ".$datos['ingreso']." ";
    $sql .= "AND     evolucion_id = ".$datos['evolucion_id']." ";
    $sql .= "AND     hc_nota_operatoria_cirugia_id IS NULL ";
    
    $cxn->Commit();
    return true;
  }
  
  function ActualizarGases($datos)
  {
    $sql  = "UPDATE  hc_notaqx_gases_anestesicos ";
    $sql .= "SET     acto_quiru = ".$datos['acto_quiru'].", ";
    $sql .= "        hc_nota_operatoria_cirugia_id = ".$datos['hc_nota_operatoria_cirugia_id']." ";
    $sql .= "WHERE   ingresoid = ".$datos['ingreso']." ";
    $sql .= "AND     hc_nota_operatoria_cirugia_id IS NULL ";
    
    $cxn = new ConexionBD();
    
    
    if(!$rst = $cxn->ConexionBaseDatos($sql))
      return $cxn->mensajeDeError;
    
    return true;
  }
?>