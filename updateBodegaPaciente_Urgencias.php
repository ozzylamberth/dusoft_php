<?
          $VISTA='HTML';
          include 'includes/enviroment.inc.php';
     
          $dbconn = ADONewConnection('postgres');
          GLOBAL $ADODB_FETCH_MODE;
          
          if (!($dbconn->Connect('192.1.1.31', 'siis', 'siis','SIIS_DESARROLLO'))) {
               die(MsgOut("PERMISOS DB : Error en la Conexión a la Base de Datos",$dbconn->ErrorMsg()));
          }

          $dbconn->BeginTrans();
          $query = "SELECT ingreso FROM pacientes_urgencias 
                    WHERE sw_estado = '1'
                    ORDER BY ingreso ASC;"; // echo "<br>";echo "<br>";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error INSERT INTO egresos_no_atencion";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          while($data = $result->FetchRow())
          {
            $Ingreso[] = $data;
          }

          echo "<br>********************ACTUALIZACION DE MEDICAMENTOS********************<br>";
          foreach($Ingreso as $k => $Ingresos)
          {
            /*********************************************************************/
               //Busco las Solicitudes de Medicamentos
               $Solicitudes = "";
               $query1 = "SELECT A.ingreso, A.solicitud_id, A.sw_estado, A.tipo_solicitud,
                                 B.consecutivo_d, B.medicamento_id, B.cant_solicitada, B.evolucion_id,
                                 A.documento_despacho, A.bodegas_doc_id
     
                              FROM hc_solicitudes_medicamentos AS A, 
                                   hc_solicitudes_medicamentos_d AS B
     
                              WHERE A.ingreso = ".$Ingresos[ingreso]."
                              AND A.solicitud_id = B.solicitud_id
                              ORDER BY B.medicamento_id;";
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $result = $dbconn->Execute($query1);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0) 
               {
                    $this->error = "Error UPDATE estaciones_enfermeria_usuarios";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               while($dataS = $result->FetchRow())
               {
                $Solicitudes[] = $dataS;
               }

               if(!empty($Solicitudes))
               {
                    for($j=0; $j<sizeof($Solicitudes); $j++)
                    {
                         //Busco inserciones anteriores en Bodega Paciente     
                         $query2 = "SELECT * FROM bodega_paciente 
                                   WHERE ingreso = ".$Solicitudes[$j][ingreso]." 
                                   AND codigo_producto = '".$Solicitudes[$j][medicamento_id]."'
                                   AND sw_tipo_producto = 'M';";
                         $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                         $result = $dbconn->Execute($query2);
                         $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                         
                         $Bodega = $result->FetchRow();
                         
                         if(!$Bodega[codigo_producto])
                         {
                              $stock = $stock_paciente = $stock_almacen = "0";
                              $cantidad_en_solicitud = $cantidad_pendiente_por_recibir = $cantidad_en_devolucion = "0";
                              $total_solicitado = $total_cancelado = $total_cancelado_antes_de_confirmar = "0";
                              $total_despachado = $total_recibido = $total_devuelto = "0";
                              $total_consumo_directo = $total_suministrado = $total_perdidas = "0";
                              $total_aprovechamiento = $total_cancelado_por_la_bodega = "0";
                              
                              if($Solicitudes[$j][sw_estado] == '0')
                              {
                                   $cantidad_en_solicitud = $Solicitudes[$j][cant_solicitada];
                                   $total_solicitado = $Solicitudes[$j][cant_solicitada];
                              }elseif($Solicitudes[$j][sw_estado] == '1' OR $Solicitudes[$j][sw_estado] == '2' OR $Solicitudes[$j][sw_estado] == '5' OR $Solicitudes[$j][sw_estado] == '6')
                              {
                                   
                                   $query_C = "SELECT B.cantidad 
                                             FROM bodegas_documento_despacho_med AS A,
                                                  bodegas_documento_despacho_med_d AS B
                                             WHERE A.documento_despacho_id = ".$Solicitudes[$j][documento_despacho]."
                                             AND B.documento_despacho_id = A.documento_despacho_id
                                             AND B.codigo_producto = '".$Solicitudes[$j][medicamento_id]."';";
                                   $result = $dbconn->Execute($query_C);
     
                                   list($Despachado) = $result->FetchRow();
     
                                   if(!empty($Despachado))
                                   {
                                        if($Solicitudes[$j][sw_estado] == '1')
                                        {
                                             $cantidad_en_solicitud = $Solicitudes[$j][cant_solicitada];
                                             $total_solicitado = $Solicitudes[$j][cant_solicitada];
                                             
                                             $cantidad_pendiente_por_recibir = $Despachado;
                                             $total_despachado = $Despachado;
                                        }
                                        
                                        if($Solicitudes[$j][sw_estado] == '2')
                                        {
                                             $stock = $Despachado;
                                             $stock_almacen = $Despachado;
                                             $cantidad_en_solicitud = $Solicitudes[$j][cant_solicitada] - $Despachado;
                                             //$cantidad_pendiente_por_recibir = $Despachado;
                                             $total_solicitado = $Solicitudes[$j][cant_solicitada];
                                             $total_despachado = $Despachado;
                                             $total_recibido = $Despachado;
                                        }
                                        
                                        if($Solicitudes[$j][sw_estado] == '5')
                                        {
                                             $stock = $Despachado;
                                             $stock_almacen = $Despachado;
                                             $cantidad_en_solicitud = $Solicitudes[$j][cant_solicitada] - $Despachado;
                                             //$cantidad_pendiente_por_recibir = $Despachado;
                                             $total_solicitado = $Solicitudes[$j][cant_solicitada];
                                             $total_despachado = $Despachado;
                                             $total_recibido = $Despachado;
                                        }
                                        
                                        if($Solicitudes[$j][sw_estado] == '6')
                                        {
                                             $total_solicitado = $Solicitudes[$j][cant_solicitada];
                                             $total_cancelado = $Despachado;
                                             $total_cancelado_antes_de_confirmar =  $Despachado;
                                             $total_despachado = $Despachado;
                                        }
                                   }
     
                              }elseif($Solicitudes[$j][sw_estado] == '3')
                              {
                                   $total_solicitado = $Solicitudes[$j][cant_solicitada];
                                   $total_cancelado = $Solicitudes[$j][cant_solicitada];
                                   $total_cancelado_por_la_bodega = $Solicitudes[$j][cant_solicitada];
                              }elseif($Solicitudes[$j][sw_estado] == '4')
                              {
                                   $stock = $Solicitudes[$j][cant_solicitada];
                                   $stock_almacen = $Solicitudes[$j][cant_solicitada];
                                   $total_despachado = $Solicitudes[$j][cant_solicitada];
                                   $total_consumo_directo = $Solicitudes[$j][cant_solicitada];
                              }elseif($Solicitudes[$j][sw_estado] == '6')
                              {
                                   $total_solicitado = $Solicitudes[$j][cant_solicitada];
                                   $total_cancelado = $Solicitudes[$j][cant_solicitada];
                                   $total_cancelado_antes_de_confirmar = $Solicitudes[$j][cant_solicitada];
                              }
                              
                              // Inserto Producto en Bodega Paciente
                              echo $query_I = "INSERT INTO bodega_paciente (ingreso, codigo_producto, sw_tipo_producto,
                                                                      stock, stock_paciente, stock_almacen,
                                                                      cantidad_en_solicitud, cantidad_pendiente_por_recibir, cantidad_en_devolucion,
                                                                      total_solicitado, total_cancelado, total_cancelado_antes_de_confirmar,
                                                                      total_despachado, total_recibido, total_devuelto,
                                                                      total_consumo_directo, total_suministrado, total_perdidas,
                                                                      total_aprovechamiento, total_cancelado_por_la_bodega)
                                                            VALUES    (".$Solicitudes[$j][ingreso].",'".$Solicitudes[$j][medicamento_id]."', 'M',
                                                                      $stock, $stock_paciente, $stock_almacen,
                                                                      $cantidad_en_solicitud, $cantidad_pendiente_por_recibir, $cantidad_en_devolucion,
                                                                      $total_solicitado, $total_cancelado, $total_cancelado_antes_de_confirmar,
                                                                      $total_despachado, $total_recibido, $total_devuelto,
                                                                      $total_consumo_directo, $total_suministrado, $total_perdidas,
                                                                      $total_aprovechamiento, $total_cancelado_por_la_bodega);"; echo "<br>";echo "<br>";
                              $dbconn->Execute($query_I);                                         
                              if ($dbconn->ErrorNo() != 0) 
                              {
                                   $this->error = "Error UPDATE estaciones_enfermeria_usuarios";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
                         }
                         else
                         {
                              $estado0 = $estado1 = $estado2 = $estado3 = $estado4 = $estado5 = $estado6 = "";
                         
                              if($Solicitudes[$j][sw_estado] == '0')
                              {
                                   $estado0 = ", cantidad_en_solicitud = ".$Bodega[cantidad_en_solicitud]." + ".$Solicitudes[$j][cant_solicitada]."
                                             , total_solicitado = ".$Bodega[total_solicitado]." + ".$Solicitudes[$j][cant_solicitada]."";
                              }elseif($Solicitudes[$j][sw_estado] == '1' OR $Solicitudes[$j][sw_estado] == '2' OR $Solicitudes[$j][sw_estado] == '5' OR $Solicitudes[$j][sw_estado] == '6')
                              {
                                   
                                   $query_C = "SELECT B.cantidad 
                                             FROM bodegas_documento_despacho_med AS A,
                                                  bodegas_documento_despacho_med_d AS B
                                             WHERE A.documento_despacho_id = ".$Solicitudes[$j][documento_despacho]."
                                             AND B.documento_despacho_id = A.documento_despacho_id
                                             AND B.codigo_producto = '".$Solicitudes[$j][medicamento_id]."';";
                                   $result = $dbconn->Execute($query_C);
     
                                   list($Despachado) = $result->FetchRow();
                                   
                                   if(!empty($Despachado))
                                   {
                                        if($Solicitudes[$j][sw_estado] == '1')
                                        {
                                             $estado1 = ", cantidad_en_solicitud = ".$Bodega[cantidad_en_solicitud]." + ".$Solicitudes[$j][cant_solicitada].",
                                                  total_solicitado = ".$Bodega[total_solicitado]." + ".$Solicitudes[$j][cant_solicitada].",
                                                  cantidad_pendiente_por_recibir = ".$Bodega[cantidad_pendiente_por_recibir]." + ".$Despachado.",
                                                  total_despachado = ".$Bodega[total_despachado]." + ".$Despachado."";
                                        }
                                        
                                        if($Solicitudes[$j][sw_estado] == '2')
                                        {
                                             $estado2 =", stock = ".$Bodega[stock]." + ".$Despachado.",
                                                       stock_almacen = ".$Bodega[stock_almacen]." + ".$Despachado.",
                                                       cantidad_en_solicitud = ".$Solicitudes[$j][cant_solicitada]." - ".$Despachado.",
                                                       total_solicitado = ".$Bodega[total_solicitado]." + ".$Solicitudes[$j][cant_solicitada].",
                                                       total_despachado = ".$Bodega[total_despachado]." + ".$Despachado.",
                                                       total_recibido = ".$Bodega[total_recibido]." + ".$Despachado."";
                                        }
                                        
                                        if($Solicitudes[$j][sw_estado] == '5')
                                        {
                                             $estado5 =", stock = ".$Bodega[stock]." + ".$Despachado.",
                                                       stock_almacen = ".$Bodega[stock_almacen]." + ".$Despachado.",
                                                       cantidad_en_solicitud = ".$Solicitudes[$j][cant_solicitada]." - ".$Despachado.",
                                                       total_solicitado = ".$Bodega[total_solicitado]." + ".$Solicitudes[$j][cant_solicitada].",
                                                       total_despachado = ".$Bodega[total_despachado]." + ".$Despachado.",
                                                       total_recibido = ".$Bodega[total_recibido]." + ".$Despachado."";
                                        }
                                                                                                                                                                                                                                                                                                 
                                        if($Solicitudes[$j][sw_estado] == '6')
                                        {
                                             $estado6 =", total_solicitado = ".$Bodega[total_solicitado]." + ".$Solicitudes[$j][cant_solicitada].",
                                                       total_cancelado = ".$Bodega[total_cancelado]." + ".$Despachado.",
                                                       total_cancelado_antes_de_confirmar = ".$Bodega[total_cancelado_antes_de_confirmar]." + ".$Despachado.",
                                                       total_despachado = ".$Bodega[total_despachado]." + ".$Despachado."";
                                        }
     
                                   }
                              }elseif($Solicitudes[$j][sw_estado] == '3')
                              {
                                   $estado3 =", total_solicitado = ".$Bodega[total_solicitado]." + ".$Solicitudes[$j][cant_solicitada].",
                                             total_cancelado = ".$Bodega[total_cancelado]." + ".$Solicitudes[$j][cant_solicitada].",
                                             total_cancelado_por_la_bodega = ".$Bodega[total_cancelado_por_la_bodega]." + ".$Solicitudes[$j][cant_solicitada]."";
                              }elseif($Solicitudes[$j][sw_estado] == '4')
                              {
                                   $estado4 =", stock = ".$Bodega[stock]." + ".$Solicitudes[$j][cant_solicitada].",
                                             stock_almacen = ".$Bodega[stock_almacen]." + ".$Solicitudes[$j][cant_solicitada].",
                                             total_despachado = ".$Bodega[total_despachado]." + ".$Solicitudes[$j][cant_solicitada].",
                                             total_consumo_directo = ".$Bodega[total_consumo_directo]." + ".$Solicitudes[$j][cant_solicitada]."";
                              }
                              
                              // Actualizo Producto en Bodega Paciente
                              echo $query_U = "UPDATE bodega_paciente 
                                        SET sw_tipo_producto = 'M'
                                        $estado0
                                        $estado1
                                        $estado2
                                        $estado3
                                        $estado4
                                        $estado5
                                        $estado6
                                        WHERE ingreso = ".$Solicitudes[$j][ingreso]."
                                        AND codigo_producto = '".$Solicitudes[$j][medicamento_id]."'
                                        AND sw_tipo_producto = 'M';"; echo "<br>";echo "<br>";
                              $dbconn->Execute($query_U);                                         
                              if ($dbconn->ErrorNo() != 0) 
                              {
                                   $this->error = "Error UPDATE estaciones_enfermeria_usuarios";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
                         }
                         
                         //Actualizacion de Suministros
                         if($Solicitudes[$j][medicamento_id] != $Solicitudes[$j+1][medicamento_id])
                         {
                              $estado_S = "";
                              $querySUM = "SELECT SUM(A.cantidad_suministrada) 
                                        FROM hc_control_suministro_medicamentos AS A, 
                                             hc_evoluciones AS B
                                        WHERE B.ingreso = ".$Solicitudes[$j][ingreso]."
                                        AND A.evolucion_id = B.evolucion_id
                                        AND codigo_producto = '".$Solicitudes[$j][medicamento_id]."';";
                              $result = $dbconn->Execute($querySUM);
                              list($Suministrado) = $result->FetchRow();
                              
                              if($Suministrado > 0)
                              {
                                   //Busco inserciones anteriores en Bodega Paciente     
                                   $query2 = "SELECT * FROM bodega_paciente 
                                             WHERE ingreso = ".$Solicitudes[$j][ingreso]." 
                                             AND codigo_producto = '".$Solicitudes[$j][medicamento_id]."'
                                             AND sw_tipo_producto = 'M';"; 
                                   $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                                   $result = $dbconn->Execute($query2);
                                   $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                              
                                   $Bodega = $result->FetchRow();
     
                                   $estado_S = ", stock = ".$Bodega[stock]." - ".$Suministrado.",
                                             stock_almacen = ".$Bodega[stock_almacen]." - ".$Suministrado.",
                                             total_suministrado = ".$Bodega[total_suministrado]." + ".$Suministrado."";
                                   
                                   // Actualizo Producto en Bodega Paciente
                                   echo $query_U_SUM = "UPDATE bodega_paciente 
                                                       SET sw_tipo_producto = 'M'
                                                       $estado_S
                                                       WHERE ingreso = ".$Solicitudes[$j][ingreso]."
                                                       AND codigo_producto = '".$Solicitudes[$j][medicamento_id]."'
                                                       AND sw_tipo_producto = 'M';"; echo "<br>";echo "<br>";
                                   $dbconn->Execute($query_U_SUM);                                         
                                   if ($dbconn->ErrorNo() != 0) 
                                   {
                                        $this->error = "Error UPDATE estaciones_enfermeria_usuarios";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                   }
                                   
                                   $Bodega = "";
                              }
                         }
                    }//Fin For Solicitudes Medicamentos
               }// Fin 1er if.
          }
               
          /*********************************************************************/
          //Busco las Solicitudes de Insumos
          echo "<br>********************ACTUALIZACION DE INSUMOS********************<br>";
           
          foreach($Ingreso as $k => $Ingresos)
          {
               $Solicitudes = "";
               $queryII = "SELECT A.ingreso, A.solicitud_id, A.sw_estado, A.tipo_solicitud,
                               A.documento_despacho, A.bodegas_doc_id,
                           B.consecutivo_d, B.codigo_producto, B.cantidad

                         FROM hc_solicitudes_medicamentos AS A, 
                              hc_solicitudes_insumos_d AS B

                    WHERE A.ingreso = ".$Ingresos[ingreso]."
                         AND A.solicitud_id = B.solicitud_id
                         ORDER BY B.codigo_producto;"; // echo "<br>";echo "<br>";
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $result = $dbconn->Execute($queryII);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0) 
               {
                    $this->error = "Error UPDATE estaciones_enfermeria_usuarios";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               while($dataS = $result->FetchRow())
               {
                $Solicitudes[] = $dataS;
               }
               
               if(!empty($Solicitudes))
               {
                    for($j=0; $j<sizeof($Solicitudes); $j++)
                    {
                         //Busco inserciones anteriores en Bodega Paciente     
                         $query2 = "SELECT * FROM bodega_paciente 
                                   WHERE ingreso = ".$Solicitudes[$j][ingreso]." 
                                   AND codigo_producto = '".$Solicitudes[$j][codigo_producto]."';";
                         $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                         $result = $dbconn->Execute($query2);
                         $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                         
                         $Bodega = $result->FetchRow();
                         
                         if(!$Bodega[codigo_producto])
                         {
                              $stock = $stock_paciente = $stock_almacen = "0";
                              $cantidad_en_solicitud = $cantidad_pendiente_por_recibir = $cantidad_en_devolucion = "0";
                              $total_solicitado = $total_cancelado = $total_cancelado_antes_de_confirmar = "0";
                              $total_despachado = $total_recibido = $total_devuelto = "0";
                              $total_consumo_directo = $total_suministrado = $total_perdidas = "0";
                              $total_aprovechamiento = $total_cancelado_por_la_bodega = "0";
                              
                              if($Solicitudes[$j][sw_estado] == '0')
                              {
                                   $cantidad_en_solicitud = $Solicitudes[$j][cantidad];
                                   $total_solicitado = $Solicitudes[$j][cantidad];
                              }elseif($Solicitudes[$j][sw_estado] == '1' OR $Solicitudes[$j][sw_estado] == '2' OR $Solicitudes[$j][sw_estado] == '5' OR $Solicitudes[$j][sw_estado] == '6')
                              {
                                   
                                   $query_C = "SELECT B.cantidad 
                                             FROM bodegas_documento_despacho_med AS A,
                                                  bodegas_documento_despacho_ins_d AS B
                                             WHERE A.documento_despacho_id = ".$Solicitudes[$j][documento_despacho]."
                                             AND B.documento_despacho_id = A.documento_despacho_id
                                             AND B.codigo_producto = '".$Solicitudes[$j][codigo_producto]."';";
                                   $result = $dbconn->Execute($query_C);
     
                                   list($Despachado) = $result->FetchRow();
     
                                   if(!empty($Despachado))
                                   {
                                        if($Solicitudes[$j][sw_estado] == '1')
                                        {
                                             $cantidad_en_solicitud = $Solicitudes[$j][cantidad];
                                             $total_solicitado = $Solicitudes[$j][cantidad];
                                             
                                             $cantidad_pendiente_por_recibir = $Despachado;
                                             $total_despachado = $Despachado;
                                        }
                                        
                                        if($Solicitudes[$j][sw_estado] == '2')
                                        {
                                             $stock = $Despachado;
                                             $stock_almacen = $Despachado;
                                             $cantidad_en_solicitud = $Solicitudes[$j][cantidad] - $Despachado;
                                             //$cantidad_pendiente_por_recibir = $Despachado;
                                             $total_solicitado = $Solicitudes[$j][cantidad];
                                             $total_despachado = $Despachado;
                                             $total_recibido = $Despachado;
                                        }
                                        
                                        if($Solicitudes[$j][sw_estado] == '5')
                                        {
                                             $stock = $Despachado;
                                             $stock_almacen = $Despachado;
                                             $cantidad_en_solicitud = $Solicitudes[$j][cantidad] - $Despachado;
                                             //$cantidad_pendiente_por_recibir = $Despachado;
                                             $total_solicitado = $Solicitudes[$j][cantidad];
                                             $total_despachado = $Despachado;
                                             $total_recibido = $Despachado;
                                        }
                                        
                                        if($Solicitudes[$j][sw_estado] == '6')
                                        {
                                             $total_solicitado = $Solicitudes[$j][cantidad];
                                             $total_cancelado = $Despachado;
                                             $total_cancelado_antes_de_confirmar =  $Despachado;
                                             $total_despachado = $Despachado;
                                        }
                                   }
     
                              }elseif($Solicitudes[$j][sw_estado] == '3')
                              {
                                   $total_solicitado = $Solicitudes[$j][cantidad];
                                   $total_cancelado = $Solicitudes[$j][cantidad];
                                   $total_cancelado_por_la_bodega = $Solicitudes[$j][cantidad];
                              }elseif($Solicitudes[$j][sw_estado] == '4')
                              {
                                   $stock = $Solicitudes[$j][cantidad];
                                   $stock_almacen = $Solicitudes[$j][cantidad];
                                   $total_despachado = $Solicitudes[$j][cantidad];
                                   $total_consumo_directo = $Solicitudes[$j][cantidad];
                              }elseif($Solicitudes[$j][sw_estado] == '6')
                              {
                                   $total_solicitado = $Solicitudes[$j][cantidad];
                                   $total_cancelado = $Solicitudes[$j][cantidad];
                                   $total_cancelado_antes_de_confirmar = $Solicitudes[$j][cantidad];
                              }
                              
                              // Inserto Producto en Bodega Paciente
                              echo $query_I = "INSERT INTO bodega_paciente (ingreso, codigo_producto, sw_tipo_producto,
                                                                      stock, stock_paciente, stock_almacen,
                                                                      cantidad_en_solicitud, cantidad_pendiente_por_recibir, cantidad_en_devolucion,
                                                                      total_solicitado, total_cancelado, total_cancelado_antes_de_confirmar,
                                                                      total_despachado, total_recibido, total_devuelto,
                                                                      total_consumo_directo, total_suministrado, total_perdidas,
                                                                      total_aprovechamiento, total_cancelado_por_la_bodega)
                                                            VALUES    (".$Solicitudes[$j][ingreso].",'".$Solicitudes[$j][codigo_producto]."', 'I',
                                                                      $stock, $stock_paciente, $stock_almacen,
                                                                      $cantidad_en_solicitud, $cantidad_pendiente_por_recibir, $cantidad_en_devolucion,
                                                                      $total_solicitado, $total_cancelado, $total_cancelado_antes_de_confirmar,
                                                                      $total_despachado, $total_recibido, $total_devuelto,
                                                                      $total_consumo_directo, $total_suministrado, $total_perdidas,
                                                                      $total_aprovechamiento, $total_cancelado_por_la_bodega);"; echo "<br>";echo "<br>";
                              $dbconn->Execute($query_I);                                         
                              if ($dbconn->ErrorNo() != 0) 
                              {
                                   $this->error = "Error UPDATE estaciones_enfermeria_usuarios";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
                         }
                         else
                         {
                              $estado0 = $estado1 = $estado2 = $estado3 = $estado4 = $estado5 = $estado6 = "";
                         
                              if($Solicitudes[$j][sw_estado] == '0')
                              {
                                   $estado0 = ", cantidad_en_solicitud = ".$Bodega[cantidad_en_solicitud]." + ".$Solicitudes[$j][cantidad]."
                                             , total_solicitado = ".$Bodega[total_solicitado]." + ".$Solicitudes[$j][cantidad]."";
                              }elseif($Solicitudes[$j][sw_estado] == '1' OR $Solicitudes[$j][sw_estado] == '2' OR $Solicitudes[$j][sw_estado] == '5' OR $Solicitudes[$j][sw_estado] == '6')
                              {
                                   
                                   $query_C = "SELECT B.cantidad 
                                             FROM bodegas_documento_despacho_med AS A,
                                                  bodegas_documento_despacho_ins_d AS B
                                             WHERE A.documento_despacho_id = ".$Solicitudes[$j][documento_despacho]."
                                             AND B.documento_despacho_id = A.documento_despacho_id
                                             AND B.codigo_producto = '".$Solicitudes[$j][codigo_producto]."';";
                                   $result = $dbconn->Execute($query_C);
     
                                   list($Despachado) = $result->FetchRow();
                                   
                                   if(!empty($Despachado))
                                   {
                                        if($Solicitudes[$j][sw_estado] == '1')
                                        {
                                             $estado1 = ", cantidad_en_solicitud = ".$Bodega[cantidad_en_solicitud]." + ".$Solicitudes[$j][cantidad].",
                                                  total_solicitado = ".$Bodega[total_solicitado]." + ".$Solicitudes[$j][cantidad].",
                                                  cantidad_pendiente_por_recibir = ".$Bodega[cantidad_pendiente_por_recibir]." + ".$Despachado.",
                                                  total_despachado = ".$Bodega[total_despachado]." + ".$Despachado."";
                                        }
                                        
                                        if($Solicitudes[$j][sw_estado] == '2')
                                        {
                                             $estado2 =", stock = ".$Bodega[stock]." + ".$Despachado.",
                                                       stock_almacen = ".$Bodega[stock_almacen]." + ".$Despachado.",
                                                       cantidad_en_solicitud = ".$Solicitudes[$j][cantidad]." - ".$Despachado.",
                                                       total_solicitado = ".$Bodega[total_solicitado]." + ".$Solicitudes[$j][cantidad].",
                                                       total_despachado = ".$Bodega[total_despachado]." + ".$Despachado.",
                                                       total_recibido = ".$Bodega[total_recibido]." + ".$Despachado."";
                                        }
                                        
                                        if($Solicitudes[$j][sw_estado] == '5')
                                        {
                                             $estado5 =", stock = ".$Bodega[stock]." + ".$Despachado.",
                                                       stock_almacen = ".$Bodega[stock_almacen]." + ".$Despachado.",
                                                       cantidad_en_solicitud = ".$Solicitudes[$j][cantidad]." - ".$Despachado.",
                                                       total_solicitado = ".$Bodega[total_solicitado]." + ".$Solicitudes[$j][cantidad].",
                                                       total_despachado = ".$Bodega[total_despachado]." + ".$Despachado.",
                                                       total_recibido = ".$Bodega[total_recibido]." + ".$Despachado."";
                                        }
                                        
                                        if($Solicitudes[$j][sw_estado] == '6')
                                        {
                                             $estado6 =", total_solicitado = ".$Bodega[total_solicitado]." + ".$Solicitudes[$j][cantidad].",
                                                       total_cancelado = ".$Bodega[total_cancelado]." + ".$Despachado.",
                                                       total_cancelado_antes_de_confirmar = ".$Bodega[total_cancelado_antes_de_confirmar]." + ".$Despachado.",
                                                       total_despachado = ".$Bodega[total_despachado]." + ".$Despachado."";
                                        }
     
                                   }
                              }elseif($Solicitudes[$j][sw_estado] == '3')
                              {
                                   $estado3 =", total_solicitado = ".$Bodega[total_solicitado]." + ".$Solicitudes[$j][cantidad].",
                                             total_cancelado = ".$Bodega[total_cancelado]." + ".$Solicitudes[$j][cantidad].",
                                             total_cancelado_por_la_bodega = ".$Bodega[total_cancelado_por_la_bodega]." + ".$Solicitudes[$j][cantidad]."";
                              }elseif($Solicitudes[$j][sw_estado] == '4')
                              {
                                   $estado4 =", stock = ".$Bodega[stock]." + ".$Solicitudes[$j][cantidad].",
                                             stock_almacen = ".$Bodega[stock_almacen]." + ".$Solicitudes[$j][cantidad].",
                                             total_despachado = ".$Bodega[total_despachado]." + ".$Solicitudes[$j][cantidad].",
                                             total_consumo_directo = ".$Bodega[total_consumo_directo]." + ".$Solicitudes[$j][cantidad]."";
                              }
                              
                              // Actualizo Producto en Bodega Paciente
                              echo $query_U = "UPDATE bodega_paciente 
                                        SET sw_tipo_producto = 'I'
                                        $estado0
                                        $estado1
                                        $estado2
                                        $estado3
                                        $estado4
                                        $estado5
                                        $estado6
                                        WHERE ingreso = ".$Solicitudes[$j][ingreso]."
                                        AND codigo_producto = '".$Solicitudes[$j][codigo_producto]."';"; echo "<br>";echo "<br>";
                              $dbconn->Execute($query_U);                                         
                              if ($dbconn->ErrorNo() != 0) 
                              {
                                   $this->error = "Error UPDATE estaciones_enfermeria_usuarios";
                                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
                         }
                    }//Fin For Solicitudes Insumos
               }// Fin 2do if
          }
               
          /*********************************************************************/
          echo "<br>********************ACTUALIZACION DE DEVOLUCIONES********************<br>";
          
          foreach($Ingreso as $k => $Ingresos)
          {
               //Devoluciones Realizadas.
               $Devoluciones = "";
               echo $queryDEV = "SELECT A.ingreso, A.documento, A.estado AS estado_padre,
                                  B.codigo_producto, B.cantidad, B.estado

                           FROM inv_solicitudes_devolucion AS A,
                                inv_solicitudes_devolucion_d AS B
                           
                           WHERE A.ingreso = ".$Ingresos[ingreso]."
                           AND A.documento = B.documento;"; echo "<br>";echo "<br>";
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $result = $dbconn->Execute($queryDEV);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               
               if ($dbconn->ErrorNo() != 0) 
               {
                    $this->error = "Error UPDATE estaciones_enfermeria_usuarios";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
               while($dataDEV = $result->FetchRow())
               {
                $Devoluciones[] = $dataDEV;
               }
               
               if(!empty($Devoluciones))
               {
               
                for($j=0; $j<sizeof($Devoluciones); $j++)
                {
                        //Busco inserciones anteriores en Bodega Paciente     
                    $query2 = "SELECT * FROM bodega_paciente 
                                WHERE ingreso = ".$Devoluciones[$j][ingreso]." 
                                AND codigo_producto = '".$Devoluciones[$j][codigo_producto]."';"; 
                        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                        $result = $dbconn->Execute($query2);
                        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                        
                        $Bodega = $result->FetchRow();
                        
                        if($Bodega[codigo_producto])
                        {
                        $estado00 = $estado10 = "";
                            
                            if($Devoluciones[$j][estado_padre] == '0' AND $Devoluciones[$j][estado] == '0')
                            {
                                $estado00 =", cantidad_en_devolucion = ".$Bodega[cantidad_en_devolucion]." + ".$Devoluciones[$j][cantidad]."";
                            }elseif($Devoluciones[$j][estado_padre] == '1' AND $Devoluciones[$j][estado] == '0')
                            {
                                $estado10 =", total_devuelto = ".$Bodega[total_devuelto]." + ".$Devoluciones[$j][cantidad].",
                                        stock = ".$Bodega[stock]." - ".$Devoluciones[$j][cantidad].",
                                            stock_almacen = ".$Bodega[stock_almacen]." - ".$Devoluciones[$j][cantidad]."";
                            }
                            
                            // Actualizo Producto en Bodega Paciente
                            echo $query_U = "UPDATE bodega_paciente 
                                    SET ingreso = ".$Devoluciones[$j][ingreso]."
                                        $estado00
                                        $estado10
                                        WHERE ingreso = ".$Devoluciones[$j][ingreso]."
                                        AND codigo_producto = '".$Devoluciones[$j][codigo_producto]."';"; echo "<br>";echo "<br>";
                            $dbconn->Execute($query_U);                                         
                            if ($dbconn->ErrorNo() != 0) 
                            {
                                $this->error = "Error UPDATE estaciones_enfermeria_usuarios";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }
                        }
                }//Fin For Devoluciones
             }
          }//Fin For Ingreso (Principal)
        $dbconn->CommitTrans();
          echo "SALIO BIEN";
          return true;
?>
