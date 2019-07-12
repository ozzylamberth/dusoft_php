<?
          $VISTA='HTML';
          include 'includes/enviroment.inc.php';
     
          $dbconn = ADONewConnection('postgres');
     
          if (!($dbconn->Connect('192.1.1.30', 'siis', 'siis','SIIS'))) {
               die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
          }
echo "<br> Inicia consulta";
		$query = "
                    SELECT 	
                              B.tipo_id_paciente,
                              B.paciente_id ,
                              A.hc_os_solicitud_id
                    FROM
                         hc_os_solicitudes A,
                         hc_os_solicitudes_manuales B
                    WHERE
                         A.hc_os_solicitud_id = B.hc_os_solicitud_id
                         AND A.evolucion_id IS NULL
                         
                         ";

          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error INSERT INTO egresos_no_atencion";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
echo "<br> Inicia update";
          while($data = $result->FetchRow())
          {
          	if(!empty($data[2]))
               {
                    ECHO $query_insert = "UPDATE hc_os_solicitudes
                                          SET tipo_id_paciente = '".$data[0]."',
                                          	 paciente_id = '".$data[1]."'
                                          WHERE hc_os_solicitud_id = ".$data[2].""; echo "<br><br>";
                    
                    $dbconn->Execute($query_insert);
                    if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error UPDATE hc_os_solicitudes";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         ECHO "Error UPDATE hc_os_solicitudes";
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
          }
     
          echo "SALIO BIEN";
     
          return true;


?>

