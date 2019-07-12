<?
          $VISTA='HTML';
          include 'includes/enviroment.inc.php';
     
          $dbconn = ADONewConnection('postgres');
          GLOBAL $ADODB_FETCH_MODE;
          
          if (!($dbconn->Connect('192.168.1.9', 'siis', 'siis','SIIS'))) {
               die(MsgOut("PERMISOS DB : Error en la Conexi� a la Base de Datos",$dbconn->ErrorMsg()));
          }

          $dbconn->BeginTrans();
          
        echo "<br>";
        echo $query = "SELECT codigo_producto, evolucion_id, ingreso
                       FROM
                       hc_medicamentos_recetados_hosp
                       WHERE ingreso = 301114
                       ORDER BY codigo_producto;"; echo "<br><br>";

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
               echo $selectM = "SELECT *
                                FROM hc_control_suministro_medicamentos
                                WHERE evolucion_id = ".$Formulaciones[$i][evolucion_id]."
                                AND codigo_producto = '".$Formulaciones[$i][codigo_producto]."'
                                ORDER BY hc_control_suministro_id ASC;"; echo "<br><br>";
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $result = $dbconn->Execute($selectM);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error SELECT hc_medicamentos_recetados_hosp";
                    echo $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               
               $vectorSum = "";
               while($datosSum = $result->FetchRow())
               {
                    $vectorSum[] = $datosSum;
               }
                    
               $selectNR = "SELECT num_reg_formulacion
                            FROM hc_formulacion_medicamentos
                            WHERE ingreso = ".$Formulaciones[0][ingreso]."
                            AND codigo_producto = '".$Formulaciones[$i][codigo_producto]."';";
               $result = $dbconn->Execute($selectNR);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error SELECT hc_medicamentos_recetados_hosp";
                    echo $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               $num_reg = $result->fields[0];

			if($vectorSum)
               {             
                    for($j=0; $j<sizeof($vectorSum); $j++)
                    {
                         echo $queryINS = "INSERT INTO hc_formulacion_suministro_medicamentos	    (sw_estado,
                                                                                                    ingreso,
                                                                                                    num_reg_formulacion,
                                                                                                    codigo_producto,
                                                                                                    usuario_id_control,
                                                                                                    fecha_realizado,
                                                                                                    fecha_registro_control,
                                                                                                    cantidad_suministrada,
                                                                                                    cantidad_perdidas,
                                                                                                    cantidad_aprovechada,
                                                                                                    estacion_id,
                                                                                                    observacion) 
                                                                                          VALUES   ('1',
                                                                                                    ".$Formulaciones[0][ingreso].",
                                                                                                    ".$num_reg.",
                                                                                                    '".$vectorSum[$j][codigo_producto]."',
                                                                                                    ".$vectorSum[$j][usuario_id_control].",
                                                                                                    '".$vectorSum[$j][fecha_realizado]."',
                                                                                                    '".$vectorSum[$j][fecha_registro_control]."',
                                                                                                    '".$vectorSum[$j][cantidad_suministrada]."',
                                                                                                    '0',
                                                                                                    '0',
                                                                                                    ".$vectorSum[$j][estacion_id].",
                                                                                                    '".$vectorSum[$j][observacion]."');"; echo "<br><br>";
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
        
          echo "SALIO BIEN";
          return true;
?>
