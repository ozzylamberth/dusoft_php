<?
          $VISTA='HTML';
          include 'includes/enviroment.inc.php';
     
          $dbconn = ADONewConnection('postgres');
     
          if (!($dbconn->Connect('192.168.1.9', 'siis', 'siis','SIIS'))) {
               die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
          }
     
		$query = "
                    SELECT hc_evolucion_descripcion_id,
                    	  fecha_registro
                    FROM hc_evolucion_descripcion
                    WHERE fecha_control IS NULL
                    ORDER BY hc_evolucion_descripcion_id ASC;
                    ";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error INSERT INTO egresos_no_atencion";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
          while($data = $result->FetchRow())
          {
          	if(!empty($data[0]))
               {
                    ECHO $query_insert = "UPDATE hc_evolucion_descripcion
                                          SET fecha_control = '".$data[1]."',
                                              servicio_id = '1'
                                          WHERE hc_evolucion_descripcion_id = ".$data[0].";";
                    $dbconn->Execute($query_insert);
                    if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error UPDATE estaciones_enfermeria_usuarios";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
          }
     
          echo "SALIO BIEN";
     
          return true;

?>

