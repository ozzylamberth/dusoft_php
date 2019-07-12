<?
          $VISTA='HTML';
          include 'includes/enviroment.inc.php';
     
          $dbconn = ADONewConnection('postgres');
     
          if (!($dbconn->Connect('192.1.1.30', 'siis', 'siis2006','SIIS'))) {
               die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
          }
     
		$query = "
                    SELECT
                         A.departamento_actual, 
                         B.ingreso
                    FROM 
                         ingresos AS A,
                         ingresos_salidas AS B
                    WHERE A.ingreso = B.ingreso
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
                    ECHO $query_insert = "UPDATE ingresos_salidas
                                          SET departamento_egreso = '".$data[0]."'
                                          WHERE ingreso = ".$data[1].";";
                    $dbconn->Execute($query_insert);
                    if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error UPDATE hc_ordenes_medicas";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
          }
     
          echo "SALIO BIEN";
     
          return true;


?>

