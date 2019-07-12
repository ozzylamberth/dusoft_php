<?
      $VISTA='HTML';
      include 'includes/enviroment.inc.php';
      list($dbconn) = GetDBconn();
      
      
          
      // var_dump($dbconn->debug = true);
      $query = "select distinct ingreso from hc_sistemas"; //seleccionamos todos los ingresos
      
                    
		   
       $resultado=$dbconn->Execute($query);//ejecutamos query
       if($dbconn->ErrorNo() != 0)
       { echo "y1 esta mal";
         return false;
       }
       else
       {
               while(!$resultado->EOF)
               {
                 $ingresos[]=$resultado->GetRowAssoc($ToUpper = false);//cargamos vector de ingresos
                 $resultado->MoveNext();
               }
    
             unset($resultado);
      }
    
    
    
    
    for($i=0;$i<count($ingresos);$i++)//contador para el vector de ingresos
       { echo"i".var_dump($i);
    
            $query1 = "select distinct evolucion_id from hc_sistemas 
            where ingreso=".$ingresos[$i]['ingreso'].""; //seleccionamos todas las evoluciones     
            $resultado=$dbconn->Execute($query1);//ejecutamos query1
            
            
            if($dbconn->ErrorNo() != 0)
             {
               $this->error = "Error INSERT INTO egresos_no_atencion";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
             }
             else
             {
                    while(!$resultado->EOF)
                       {
                         $evolucion[]=$resultado->GetRowAssoc($ToUpper = false);//cargamos vector de evoluciones
                         $resultado->MoveNext();
                       }
    
                  unset($resultado);
             }
          
          
          
          for($j=0;$j<count($evolucion);$j++)//contador para el vector de evoluciones
           { 
              echo "AQUI BA:".count($evolucion);
    
       // en esta consulta buscamos el examen fisico del paciente que pertenesca a ese ingreso 
       //  y esa evolucion 
              echo $query3 = "select normal,anormal,tipo_sistema_id 
                        from hc_sistemas 
                        where ingreso='".$ingresos[$i]['ingreso']."' and 
                        evolucion_id='".$evolucion[$j]['evolucion_id']."'
                        order by evolucion_id";
    
    
                $resultado=$dbconn->Execute($query3);//ejecuta
                if($dbconn->ErrorNo() != 0)
                 {
                       $this->error = "Error INSERT INTO egresos_no_atencion";
                       $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                       $dbconn->RollbackTrans();
                       return false;
                 }
                 else
                 {
                   while(!$resultado->EOF)
                      {
                         $tabla[]=$resultado->GetRowAssoc($ToUpper = false);//cargamos vector de consulta
                         $resultado->MoveNext();
                      }
    
                        unset($resultado);
                 }   
    
    
                     for($k=0;$k<count($tabla);$k++)
                        {     
                        
                             //concatenamos todos los hallazgos de los sistemas(concatenamos columna anormal)
                             
                               $hallazgo.=$tabla[$k]['anormal'].".";
                        
                        //traducimos en la tabla anterior estados 0=normal y 1=anormal
                               if($tabla[$k]['normal']==1)
                                {
                                  $sw='A';                             
                                }  
                                elseif($tabla[$k]['normal']==0) 
                                {
                                    $sw='N';                             
                                }
                             //concatenamos cadena de consultas para ingresar el tipo sistema y su switch normal o anormal
                                $Insertar="insert into hc_revision_por_sistemas values(".$tabla[$k]['tipo_sistema_id'].",".$evolucion[$j]['evolucion_id'].",".$ingresos[$i]['ingreso'].",'".$sw."');";
                                $result1 = $dbconn->Execute($Insertar);                     
                        }
                      unset($tabla);
                      
                // necesitamos para llenar la tabla el id del profesional que hizo la labor  
                       $queryu = "select usuario_id from hc_evoluciones 
                                  where evolucion_id=".$evolucion[$j]['evolucion_id'].""; 
                       $resultado=$dbconn->Execute($queryu);
                         if($dbconn->ErrorNo() != 0)
                           {
                              return false;
                           }
                           else
                           {
                                 while(!$resultado->EOF)
                                  {
                                     $usuario[]=$resultado->GetRowAssoc($ToUpper = false);//vector del profesional
                                     $resultado->MoveNext();
                                  }
    
                                unset($resultado);
                            }   
                         
        echo "usuario".$usuario[0]['usuario_id'];
        //insertamos tabla hallazgos                
         $query2="insert into hc_revision_por_sistemas_hallazgos values(".$evolucion[$j]['evolucion_id'].",".$ingresos[$i]['ingreso'].",'".addslashes($hallazgo)."',".$usuario[0]['usuario_id'].");";
         unset($usuario);
         
        $result2 = $dbconn->Execute($query2);
         
     if($dbconn->ErrorNo() != 0)
                {
                  return false;
                }
                  else
                  {
                     ECHO "FANTASTICO NELLY FELIZ CUMPLE";
                  }
     
            
              unset($hallazgo);
              
                 
         }
    unset($evolucion); 
   
    }
    
    
     
          return true;


?>

