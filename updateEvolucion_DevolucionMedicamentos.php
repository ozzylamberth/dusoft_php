<?
          $VISTA='HTML';
          include 'includes/enviroment.inc.php';
     
          $dbconn = ADONewConnection('postgres');
     
          if (!($dbconn->Connect('192.1.1.30', 'siis', 'siis2006','SIIS'))) {
               die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
          }
     
		$query = "SELECT DISTINCT B.consecutivo, A.ingreso, 
          					 B.codigo_producto, C.evolucion_id
                    FROM inv_solicitudes_devolucion AS A
                    LEFT JOIN hc_medicamentos_recetados_hosp AS C ON (C.ingreso = A.ingreso),
                         inv_solicitudes_devolucion_d AS B
                    WHERE A.documento = B.documento";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error INSERT INTO egresos_no_atencion";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          while($data = $result->FetchRow())
          {
          	$VectorDevoluciones[] = $data;
          }

          for($i=0; $i<sizeof($VectorDevoluciones); $i++)
          {
               if($VectorDevoluciones[$i-1][0] != $VectorDevoluciones[$i][0])
               {
                    ECHO $query_insert = "UPDATE inv_solicitudes_devolucion_d
                                          SET evolucion_id = ".$VectorDevoluciones[$i][3]." 
                                          WHERE consecutivo = ".$VectorDevoluciones[$i][0].";"; echo "<br>";
                    $dbconn->Execute($query_insert);
                    if ($dbconn->ErrorNo() != 0) 
                    {
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
