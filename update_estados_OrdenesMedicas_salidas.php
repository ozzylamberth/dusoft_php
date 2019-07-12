<?
          $VISTA='HTML';
          include 'includes/enviroment.inc.php';
     
          $dbconn = ADONewConnection('postgres');
     
          if (!($dbconn->Connect('192.1.1.30', 'siis', 'siis2006','SIIS'))) {
               die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
          }
     
		$query = "
                    SELECT A.ingreso,
                         A.evolucion_id,
                         A.sw_estado,
                         C.numerodecuenta,
                         D.estado
                    FROM 
                         hc_ordenes_medicas AS A
                         RIGHT JOIN hc_vistosok_salida_detalle AS B ON (A.ingreso = B.ingreso AND A.evolucion_id = B.evolucion_id),
                         hc_evoluciones AS C,
                         cuentas AS D
                    
                    WHERE A.sw_estado = '0'
                    AND A.hc_tipo_orden_medica_id IN ('99','06','07')
                    AND A.ingreso = C.ingreso
                    AND A.evolucion_id = C.evolucion_id
                    AND D.numerodecuenta = C.numerodecuenta
                    AND D.estado IN ('1','2','3')
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
                    ECHO $query_insert = "UPDATE hc_ordenes_medicas
                                          SET sw_estado = '1'
                                          WHERE ingreso = ".$data[0]."
                                          AND evolucion_id = ".$data[1].";";
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

