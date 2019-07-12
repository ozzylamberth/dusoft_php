<?php
// Security.inc.php  17/08/2004
// ----------------------------------------------------------------------
// SIIS v 1.0
// Copyright (C) 2004 IPSOFT S.A.
// Email: mail@ipsoft-sa.com
// ----------------------------------------------------------------------
// Autor: Lorena Aragon
// Proposito del Archivo: Cambiar estructura de datos de la solicitudes QX de historia clinica.
// ----------------------------------------------------------------------


$VISTA='HTML';
include 'includes/enviroment.inc.php';

$PermisosDBconn = ADONewConnection($ConfigDB['dbtype']);



if (!($PermisosDBconn->Connect($ConfigDB['dbhost'],$ConfigDB['dbuser'],$ConfigDB['dbpass'],$ConfigDB['dbname']))) {
    die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$PermisosDBconn->ErrorMsg()));
}
 
echo "<center><h1>CONECTADO A LA BASE DE DATOS </h1></center><BR>";
echo "<center><h1><b>$ConfigDB[dbhost]/$ConfigDB[dbname]</b></h1></center><BR><BR>";

$PermisosDBconn->BeginTrans(); 
$query="SELECT a.hc_os_solicitud_id,a.evolucion_id,b.observacion
FROM  hc_os_solicitudes a,hc_os_solicitudes_datos_acto_qx b
WHERE a.hc_os_solicitud_id=b.hc_os_solicitud_id;"; 
$result = $PermisosDBconn->Execute($query);
if ($PermisosDBconn->ErrorNo() != 0) {
  $this->error = "Error al Consultar por el numero de ingreso";
  $this->mensajeDeError = "Error DB : " . $PermisosDBconn->ErrorMsg();
  $PermisosDBconn->RollbackTrans();
  return false;
}
while(!$result->EOF){
  $vars[]=$result->GetRowAssoc($ToUpper = false);
  $result->MoveNext();
}
;
$query= "ALTER TABLE hc_os_solicitudes_datos_acto_qx DROP COLUMN tipo_cirugia_id;";
$query.="ALTER TABLE hc_os_solicitudes_datos_acto_qx DROP COLUMN ambito_cirugia_id;";
$query.="ALTER TABLE hc_os_solicitudes_datos_acto_qx DROP COLUMN finalidad_procedimiento_id;";
$query.="ALTER TABLE hc_os_solicitudes_datos_acto_qx DROP COLUMN observacion;";
$query.="ALTER TABLE hc_os_solicitudes_datos_acto_qx ADD COLUMN acto_qx_id SERIAL;";
$query.="ALTER TABLE hc_os_solicitudes_datos_acto_qx ADD COLUMN evolucion_id integer;";
$query.="ALTER TABLE hc_os_solicitudes_datos_acto_qx ADD FOREIGN KEY (evolucion_id) REFERENCES hc_evoluciones(evolucion_id) ON UPDATE CASCADE ON DELETE RESTRICT;";
echo $query;echo "<br>";
$PermisosDBconn->Execute($query);
if ($PermisosDBconn->ErrorNo() != 0) {
  $this->error = "Error al Consultar por el numero de ingreso";
  $this->mensajeDeError = "Error DB : " . $PermisosDBconn->ErrorMsg();
  $PermisosDBconn->RollbackTrans();
  return false;
} 
 
$query="CREATE TABLE hc_os_solicitudes_acto_qx (
          hc_os_solicitud_id integer PRIMARY KEY,
          observacion text,
          acto_qx_id integer NOT NULL
        );";
$query.="ALTER TABLE ONLY hc_os_solicitudes_acto_qx ADD FOREIGN KEY (hc_os_solicitud_id) REFERENCES hc_os_solicitudes(hc_os_solicitud_id) ON UPDATE CASCADE ON DELETE RESTRICT;";

echo $query;echo "<br>";
$PermisosDBconn->Execute($query);
if ($PermisosDBconn->ErrorNo() != 0) {
  $this->error = "Error al Consultar por el numero de ingreso";
  $this->mensajeDeError = "Error DB : " . $PermisosDBconn->ErrorMsg();
  $PermisosDBconn->RollbackTrans();
  return false;
}   

$query= "ALTER TABLE hc_os_solicitudes_requerimientos_equipo_quirofano DROP CONSTRAINT \"hc_os_solicitudes_requerimientos_equipo_quirofano_pkey\";";
$query.="ALTER TABLE hc_os_solicitudes_requerimientos_equipos_moviles DROP CONSTRAINT \"hc_os_solicitudes_requerimientos_equipos_moviles_pkey\";";
$query.="ALTER TABLE hc_os_solicitudes_estancia DROP CONSTRAINT \"hc_os_solicitudes_estancia_pkey\";";
$query.="ALTER TABLE hc_os_solicitudes_otros_productos_inv DROP CONSTRAINT \"hc_os_solicitudes_otros_productos_inv_pkey\";";
$query.="ALTER TABLE hc_os_solicitudes_datos_acto_qx DROP CONSTRAINT \"hc_os_solicitudes_datos_acto_qx_pkey\" CASCADE;";
$query.="ALTER TABLE hc_os_solicitudes_datos_acto_qx ADD PRIMARY KEY(acto_qx_id);";

$query.="ALTER TABLE hc_os_solicitudes_requerimientos_equipo_quirofano ADD COLUMN acto_qx_id integer;";
$query.="ALTER TABLE hc_os_solicitudes_estancia ADD COLUMN acto_qx_id integer;";
$query.="ALTER TABLE hc_os_solicitudes_requerimientos_equipos_moviles ADD COLUMN acto_qx_id integer;";
$query.="ALTER TABLE banco_sangre_reserva_hc ADD COLUMN acto_qx_id integer NULL;";
$query.="ALTER TABLE hc_os_solicitudes_otros_productos_inv ADD COLUMN acto_qx_id integer;";
$query.="ALTER TABLE hc_os_solicitudes_observaciones ADD COLUMN acto_qx_id integer NULL;"; 

$query.="ALTER TABLE hc_os_solicitudes_procedimientos DROP COLUMN tipo_cirugia_id;";
$query.="ALTER TABLE hc_os_solicitudes_procedimientos DROP COLUMN ambito_cirugia_id;";
$query.="ALTER TABLE hc_os_solicitudes_procedimientos DROP COLUMN finalidad_procedimiento_id;";
echo $query;echo "<br>";
$PermisosDBconn->Execute($query);
if ($PermisosDBconn->ErrorNo() != 0) {
  $this->error = "Error al Consultar por el numero de ingreso;";
  $this->mensajeDeError = "Error DB : " . $PermisosDBconn->ErrorMsg();
  $PermisosDBconn->RollbackTrans();
  return false;
}

for($i=0;$i<sizeof($vars);$i++){
    $query="SELECT acto_qx_id FROM hc_os_solicitudes_datos_acto_qx WHERE hc_os_solicitud_id='".$vars[$i]['hc_os_solicitud_id']."';";
    $result = $PermisosDBconn->Execute($query);
    $actoQX=$result->fields[0];
    $query="INSERT INTO hc_os_solicitudes_acto_qx(hc_os_solicitud_id,observacion,acto_qx_id)
    VALUES('".$vars[$i]['hc_os_solicitud_id']."','".$vars[$i]['observacion']."','".$actoQX."');";
    
    $PermisosDBconn->Execute($query);
    if ($PermisosDBconn->ErrorNo() != 0) {
      $this->error = "Error al Consultar por el numero de ingreso;";
      $this->mensajeDeError = "Error DB : " . $PermisosDBconn->ErrorMsg();
      $PermisosDBconn->RollbackTrans();
      return false;
    }
    
    $query="UPDATE hc_os_solicitudes_datos_acto_qx SET evolucion_id='".$vars[$i]['evolucion_id']."'
    WHERE hc_os_solicitud_id='".$vars[$i]['hc_os_solicitud_id']."' AND acto_qx_id='".$actoQX."';";
    
    $PermisosDBconn->Execute($query);
    if ($PermisosDBconn->ErrorNo() != 0) {
      $this->error = "Error al Consultar por el numero de ingreso";
      $this->mensajeDeError = "Error DB : " . $PermisosDBconn->ErrorMsg();
      $PermisosDBconn->RollbackTrans();
      return false;
    }
    
    $query="UPDATE hc_os_solicitudes_requerimientos_equipo_quirofano SET acto_qx_id='".$actoQX."' 
    WHERE hc_os_solicitud_id='".$vars[$i]['hc_os_solicitud_id']."';";
    echo $query;echo "<br>";
    $PermisosDBconn->Execute($query);
    if ($PermisosDBconn->ErrorNo() != 0) {
      $this->error = "Error al Consultar por el numero de ingreso";
      $this->mensajeDeError = "Error DB : " . $PermisosDBconn->ErrorMsg();
      $PermisosDBconn->RollbackTrans();
      return false;
    }
    $query="UPDATE hc_os_solicitudes_requerimientos_equipos_moviles SET acto_qx_id='".$actoQX."' 
    WHERE hc_os_solicitud_id='".$vars[$i]['hc_os_solicitud_id']."';";
    echo $query;echo "<br>";
    $PermisosDBconn->Execute($query);
    if ($PermisosDBconn->ErrorNo() != 0) {
      $this->error = "Error al Consultar por el numero de ingreso";
      $this->mensajeDeError = "Error DB : " . $PermisosDBconn->ErrorMsg();
      $PermisosDBconn->RollbackTrans();
      return false;
    }
    
    $query="UPDATE  banco_sangre_reserva_hc SET acto_qx_id='".$actoQX."' 
    WHERE hc_os_solicitud_id='".$vars[$i]['hc_os_solicitud_id']."';";
    echo $query;echo "<br>";
    $PermisosDBconn->Execute($query);
    if ($PermisosDBconn->ErrorNo() != 0) {
      $this->error = "Error al Consultar por el numero de ingreso";
      $this->mensajeDeError = "Error DB : " . $PermisosDBconn->ErrorMsg();
      $PermisosDBconn->RollbackTrans();
      return false;
    }
    
    $query="UPDATE hc_os_solicitudes_otros_productos_inv SET acto_qx_id='".$actoQX."' 
    WHERE hc_os_solicitud_id='".$vars[$i]['hc_os_solicitud_id']."';";
    
    $PermisosDBconn->Execute($query);
    if ($PermisosDBconn->ErrorNo() != 0) {
      $this->error = "Error al Consultar por el numero de ingreso";
      $this->mensajeDeError = "Error DB : " . $PermisosDBconn->ErrorMsg();
      $PermisosDBconn->RollbackTrans();
      return false;
    } 
    echo $query;echo "<br>";
    $query="UPDATE  hc_os_solicitudes_observaciones SET acto_qx_id='".$actoQX."' 
    WHERE hc_os_solicitud_id='".$vars[$i]['hc_os_solicitud_id']."';";
    echo $query;echo "<br>";
    $PermisosDBconn->Execute($query);
    if ($PermisosDBconn->ErrorNo() != 0) {
      $this->error = "Error al Consultar por el numero de ingreso";
      $this->mensajeDeError = "Error DB : " . $PermisosDBconn->ErrorMsg();
      $PermisosDBconn->RollbackTrans();
      return false;
    }   
}      

$query="ALTER TABLE hc_os_solicitudes_datos_acto_qx DROP COLUMN hc_os_solicitud_id;";
$query.="ALTER TABLE hc_os_solicitudes_requerimientos_equipo_quirofano DROP COLUMN hc_os_solicitud_id;";
$query.="ALTER TABLE hc_os_solicitudes_requerimientos_equipos_moviles DROP COLUMN hc_os_solicitud_id;";
$query.="ALTER TABLE hc_os_solicitudes_estancia DROP COLUMN hc_os_solicitud_id;";
$query.="ALTER TABLE hc_os_solicitudes_otros_productos_inv DROP COLUMN hc_os_solicitud_id;";
$query.="ALTER TABLE hc_os_solicitudes_observaciones DROP COLUMN hc_os_solicitud_id;";

$query.="DELETE FROM hc_os_solicitudes_requerimientos_equipo_quirofano WHERE acto_qx_id IS NULL;";
$query.="ALTER TABLE hc_os_solicitudes_requerimientos_equipo_quirofano ADD FOREIGN KEY (acto_qx_id) REFERENCES hc_os_solicitudes_datos_acto_qx(acto_qx_id) ON UPDATE CASCADE ON DELETE RESTRICT;";
$query.="DELETE FROM hc_os_solicitudes_requerimientos_equipos_moviles WHERE acto_qx_id IS NULL;";
$query.="ALTER TABLE hc_os_solicitudes_requerimientos_equipos_moviles ADD FOREIGN KEY (acto_qx_id) REFERENCES hc_os_solicitudes_datos_acto_qx(acto_qx_id) ON UPDATE CASCADE ON DELETE RESTRICT;";
$query.="DELETE FROM hc_os_solicitudes_estancia WHERE acto_qx_id IS NULL;";
$query.="ALTER TABLE hc_os_solicitudes_estancia ADD FOREIGN KEY (acto_qx_id) REFERENCES hc_os_solicitudes_datos_acto_qx(acto_qx_id) ON UPDATE CASCADE ON DELETE RESTRICT;";
$query.="DELETE FROM banco_sangre_reserva_hc WHERE acto_qx_id IS NULL;";
$query.="ALTER TABLE banco_sangre_reserva_hc ADD FOREIGN KEY (acto_qx_id) REFERENCES hc_os_solicitudes_datos_acto_qx(acto_qx_id) ON UPDATE CASCADE ON DELETE RESTRICT;";
$query.="DELETE FROM hc_os_solicitudes_otros_productos_inv WHERE acto_qx_id IS NULL;";
$query.="ALTER TABLE hc_os_solicitudes_otros_productos_inv ADD FOREIGN KEY (acto_qx_id) REFERENCES hc_os_solicitudes_datos_acto_qx(acto_qx_id) ON UPDATE CASCADE ON DELETE RESTRICT;";
$query.="DELETE FROM hc_os_solicitudes_observaciones WHERE acto_qx_id IS NULL;";
$query.="ALTER TABLE hc_os_solicitudes_observaciones ADD FOREIGN KEY (acto_qx_id) REFERENCES hc_os_solicitudes_datos_acto_qx(acto_qx_id) ON UPDATE CASCADE ON DELETE RESTRICT;";
$query.="DELETE FROM hc_os_solicitudes_acto_qx WHERE acto_qx_id IS NULL;";
$query.="ALTER TABLE hc_os_solicitudes_acto_qx ADD FOREIGN KEY (acto_qx_id) REFERENCES hc_os_solicitudes_datos_acto_qx(acto_qx_id) ON UPDATE CASCADE ON DELETE RESTRICT;";

$query.="ALTER TABLE hc_os_solicitudes_requerimientos_equipo_quirofano ADD PRIMARY KEY(acto_qx_id,tipo_equipo_fijo_id);";
$query.="ALTER TABLE hc_os_solicitudes_requerimientos_equipos_moviles ADD PRIMARY KEY(acto_qx_id,tipo_equipo_id);";
$query.="ALTER TABLE hc_os_solicitudes_estancia ADD PRIMARY KEY(acto_qx_id,tipo_clase_cama_id);";
$query.="ALTER TABLE hc_os_solicitudes_otros_productos_inv ADD PRIMARY KEY(acto_qx_id);";

$query.="ALTER TABLE hc_os_solicitudes_estancia DROP COLUMN cantidad_dias;";

$query.="DROP TABLE hc_os_solicitudes_otros_procedimientos_qx;";
$query.="DROP TABLE hc_os_solicitudes_procedimientos_apoyos;";
echo $query;echo "<br>";
$PermisosDBconn->Execute($query);
if ($PermisosDBconn->ErrorNo() != 0) {
  $this->error = "Error al Consultar por el numero de ingreso";
  $this->mensajeDeError = "Error DB : " . $PermisosDBconn->ErrorMsg();
  $PermisosDBconn->RollbackTrans();
  return false;
}

$PermisosDBconn->CommitTrans();
echo "TERMINADO SATISFACTORIAMENTE";
?>