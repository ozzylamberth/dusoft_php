<?
          $VISTA='HTML';
          include 'includes/enviroment.inc.php';
     
          $dbconn = ADONewConnection('postgres');
     
          if (!($dbconn->Connect('192.1.1.30', 'siis', 'siis2006','SIIS'))) {
               die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
          }
     
		$query = "SELECT DISTINCT B.ingreso, A.evolucion_id, C.estacion_id
                    FROM  hc_control_suministro_medicamentos AS A, hc_evoluciones AS B
                    RIGHT JOIN movimientos_habitacion AS C ON (C.ingreso = B.ingreso)
                    WHERE A.evolucion_id = B.evolucion_id";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error INSERT INTO egresos_no_atencion";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          while($data = $result->FetchRow())
          {
               ECHO $query_insert = "UPDATE hc_control_suministro_medicamentos
                                     SET estacion_id = '$data[2]' 
                                     WHERE evolucion_id = ".$data[1].";"; echo "<br>";
               $dbconn->Execute($query_insert);
               if ($dbconn->ErrorNo() != 0) 
               {
                    $this->error = "Error UPDATE estaciones_enfermeria_usuarios";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
     
          echo "SALIO BIEN";
          return true;


?>

