<?
          $VISTA='HTML';
          include 'includes/enviroment.inc.php';
     
          $dbconn = ADONewConnection('postgres');
     
          if (!($dbconn->Connect('192.1.1.30', 'siis', 'siis2006','SIIS'))) {
               die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
          }
     
          $query = "SELECT DISTINCT f.ingreso
                    
                    FROM hc_vistosok_salida_detalle as vistos,
                         hc_ordenes_medicas as om 
                         left join pacientes_urgencias as a
                         on (a.ingreso=om.ingreso), 
                         pacientes as b, 
                         historias_clinicas as c,
                         ingresos as f,
                         estaciones_enfermeria as e,
                         cuentas as g 

                    WHERE f.ingreso=om.ingreso 
                    AND om.hc_tipo_orden_medica_id in ('06','07','99') 
                    AND om.sw_estado = '0' 
                    AND a.sw_estado in('4','5','6') 
                    AND a.ingreso=f.ingreso AND vistos.ingreso=om.ingreso 
                    AND f.estado != '2' AND f.tipo_id_paciente=b.tipo_id_paciente 
                    AND f.paciente_id=b.paciente_id AND c.tipo_id_paciente=b.tipo_id_paciente 
                    AND c.paciente_id=b.paciente_id AND e.estacion_id=a.estacion_id 
                    AND f.ingreso=g.ingreso and g.empresa_id='01' 
                    AND g.centro_utilidad='01' 
                    AND g.estado IN ('0','5');";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error INSERT INTO egresos_no_atencion";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          while($data = $result->FetchRow())
          {
          	$ingresos[] = $data;
          }
          
          for($i=0; $i<sizeof($ingresos); $i++)
          {
               $query1 = "UPDATE ingresos SET estado='2',fecha_cierre='now()'
                         WHERE ingreso=".$ingresos[$i][0]."";
               $dbconn->Execute($query1);
               if ($dbconn->ErrorNo() != 0)
               {
                    $dbconn->RollbackTrans();
                    $resultado['mensaje']='ERROR EN ACTUALIZAR EL INGRESO'. $dbconn->ErrorMsg();
                    return $resultado;
               }
     
               $queryB = "SELECT ingreso 
               		 FROM ingresos_salidas WHERE ingreso = ".$ingresos[$i][0].";";
               $resultado = $dbconn->Execute($queryB);
               if(empty($resultado->fields[0]))
               {
               	echo $query2 = "INSERT INTO ingresos_salidas (ingreso,fecha_registro,usuario_id,observacion_salida)
                               VALUES(".$ingresos[$i][0].",'now()','2','Paciente Dado de Alta Automaticamente por el Sistema')"; echo "<br><br>";
                    $dbconn->Execute($query2);               
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $dbconn->RollbackTrans();
                         $resultado['mensaje']='ERROR EN INSERTAR'. $dbconn->ErrorMsg();
                         return $resultado;
                    }
               }
          }
          echo "SALIO BIEN";
          return true;


?>

