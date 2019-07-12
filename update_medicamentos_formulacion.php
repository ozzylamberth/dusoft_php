<?
          $VISTA='HTML';
          include 'includes/enviroment.inc.php';
     
          $dbconn = ADONewConnection('postgres');
          GLOBAL $ADODB_FETCH_MODE;
          
          if (!($dbconn->Connect('192.168.1.9', 'admin', 'admin','SIIS'))) {
               die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
          }

          $dbconn->BeginTrans();
          
        echo "<br>";
        echo $query = "SELECT B.medicamento_id, B.evolucion_id, B.ingreso
                    FROM
                         hc_evoluciones A,
                         hc_solicitudes_medicamentos_d B
                    WHERE
                         A.evolucion_id = B.evolucion_id
                         AND B.evolucion_id IS NOT NULL;"; echo "<br><br>";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error SELECT hc_os_solicitudes";
               echo $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          
echo "<br> Inicia update <br>";
          while($data = $result->FetchRow())
          {
            $Formulaciones[] = $data;
          }
               
          for($i=0; $i<sizeof($Formulaciones); $i++)
          {
               if(($Formulaciones[$i][ingreso] != $Formulaciones[$i-1][ingreso]) AND ($Formulaciones[$i][medicamento_id] != $Formulaciones[$i-1][medicamento_id]))
               {
                    echo $selectM = "SELECT A.*, B.usuario_id, B.fecha
                                  FROM hc_medicamentos_recetados_hosp A,
                                     hc_evoluciones AS B
                                    WHERE A.ingreso = ".$Formulaciones[$i][ingreso]."
                                   AND A.codigo_producto = '".$Formulaciones[$i][medicamento_id]."'
                                  ORDER BY A.evolucion_id DESC;"; echo "<br><br>";
                    
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $result = $dbconn->Execute($selectM);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error SELECT hc_medicamentos_recetados_hosp";
                         echo $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         $dbconn->RollbackTrans();
                         return false;
                    }
                    
                    while($datosMedicamentos = $result->FetchRow())
                    {
                        if(!$datosMedicamentos[via_administracion_id])
                         { $datosMedicamentos[via_administracion_id] = 1; } 
                    
                         echo $queryINS = "INSERT INTO hc_formulacion_medicamentos_eventos    (ingreso,
                                                                                          codigo_producto,
                                                                                          evolucion_id,
                                                                                          usuario_id,
                                                                                          fecha_registro,
                                                                                          sw_estado,
                                                                                          observacion,
                                                                                          via_administracion_id,
                                                                                          unidad_dosificacion,
                                                                                          dosis,
                                                                                          frecuencia,
                                                                                          cantidad,
                                                                                          usuario_registro) 
                                                                                VALUES   (".$datosMedicamentos[ingreso].",
                                                                                        '".$datosMedicamentos[codigo_producto]."',
                                                                                        ".$datosMedicamentos[evolucion_id].",
                                                                                        ".$datosMedicamentos[usuario_id].",
                                                                                        '".$datosMedicamentos[fecha]."',
                                                                                        '".$datosMedicamentos[sw_estado]."',
                                                                                        '".$datosMedicamentos[observacion]."',
                                                                                        '".$datosMedicamentos[via_administracion_id]."',
                                                                                        '".$datosMedicamentos[unidad_dosificacion]."',
                                                                                        ".$datosMedicamentos[dosis].",
                                                                                        'Estimada por el profesional',
                                                                                        ".$datosMedicamentos[cantidad].",
                                                                                        ".$datosMedicamentos[usuario_id].");"; echo "<br><br>";
                              $result = $dbconn->Execute($queryINS);
                              if ($dbconn->ErrorNo() != 0) {
                              $this->error = "Error INSERT hc_formulacion_medicamentos_eventos";
                              echo $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              $dbconn->RollbackTrans();
                              return false;
                         }
                                                             
                    }
               }
               $dbconn->CommitTrans();
          }
        
          //$dbconn->CommitTrans();
          echo "SALIO BIEN";
          return true;
?>
