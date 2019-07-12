<?php

/*
incluimos rs_server.class.php que contiene la class rs_server que será la que "extenderemos"
*/
     $VISTA = "HTML";
     $_ROOT="../../../";
     include  "../../../classes/rs_server/rs_server.class.php";
     include	 "../../../includes/enviroment.inc.php";
class procesos_admin extends rs_server 
{
      
 /**********************************************************************************
 *funcion que guardara en un ID
 ***********************************************************************************/             
    function guardar_id($id) 
    {
       list($sw,$id1) = explode("_", $id[0]);  
       // tipo_sistema_id   evolucion_id  ingreso   sw_normal
       ECHO "sw".$sw;
       ECHO "EVOLUCION".$id[1];
       $insertar="Insert into hc_revision_por_sistemas  
       values(".$id1.",".$id[1].",".$id[2].",'".$sw."')";
       if(!$resultado = $this->ConexionBaseDatos($insertar))
        {
          
         echo $delete="delete from hc_revision_por_sistemas  
           where 
           tipo_sistema_id=".$id1." and
           evolucion_id=".$id[1]." and
           ingreso=".$id[2]."";   
            if(!$resultado = $this->ConexionBaseDatos($delete))
             {
               return $cad=$this->frmError['MensajeError'];
             }
            else
            {
             echo  $insertar="Insert into hc_revision_por_sistemas  
                values(".$id1.",".$id[1].",".$id[2].",'".$sw."')";
                if(!$resultado = $this->ConexionBaseDatos($insertar))
                 {
                  return $cad=$this->frmError['MensajeError'];
                 } 
                else
                {
                  return $cad="EXITO1";
                }
            }        
        
        } 
        else 
        {
          $cad=var_dump($resultado)."EXITO2";
          //return $sql;
          return $cad;
        }   
    
    }                
/***********************************************************************************
*para la conexcion con la base datos
*************************************************************************************/          
   function ConexionBaseDatos($sql)
    {
      list($dbconn)=GetDBConn();
      //$dbconn->debug=true;
      $rst = $dbconn->Execute($sql);
        
      if ($dbconn->ErrorNo() != 0)
      {
        $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
         "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
        return false;
      }
      return $rst;
    }  

/***************************************************************************************
*para registrar el hallazgo     
*****************************************************************************************/

function get_hallazgo($datos)
{
       //evolucion_id  ingreso   hallazgo  usuario_id  
       $insertar="Insert into hc_revision_por_sistemas_hallazgos  
       values(".$datos[0].",".$datos[1].",'".$datos[2]."',".$datos[3].")";
       if(!$resultado = $this->ConexionBaseDatos($insertar))
        {
          
         echo $delete="delete from hc_revision_por_sistemas_hallazgos    
           where 
           evolucion_id=".$datos[0]." and
           ingreso=".$datos[1]." and
           usuario_id=".$datos[3]."";   
            if(!$resultado = $this->ConexionBaseDatos($delete))
             {
               return $cad=$this->frmError['MensajeError'];
             }
            else
            {
             echo  $insertar="Insert into hc_revision_por_sistemas_hallazgos  
                values(".$datos[0].",".$datos[1].",'".$datos[2]."','".$datos[3]."')";
                if(!$resultado = $this->ConexionBaseDatos($insertar))
                 {
                  return $cad=$this->frmError['MensajeError'];
                 } 
                else
                {
                  return $cad="DATOS ACTUALIZADOS CORRECTAMENTE";
                }
            }        
        
        } 
        else 
        {
          $cad=var_dump($resultado)."DATOS INSERTADOS CORRECTAMENTE";
          //return $sql;
          return $cad;
        }   
    
}














     
          
          function get_valores_exa($vector)  
          {
                    //var_dump($vector);
               $registrar=$this->Insertar_exa($vector);
                         return $registrar;
          }
          
          /************************************************************
          *funcion que inserta valores a las dos bd
          **********************************************************/  
          
          function Insertar_exa($vector)
               {
                    $query="Select tipo_sistema_id, evolucion_id,ingreso,sw_normal from hc_revision_por_sistemas 
                    	   where evolucion_id=".$vector[3]." and  ingreso=".$vector[2]."";
                    list($dbconn) = GetDBconn();
                    $consul = $dbconn->Execute($query);
                    $cont=$consul->RecordCount();
                    if($cont==0) 
                    {   
                         for($i=4;$i<16;$i++)
                         {
                              $z=$i-3; 
                              $query1.="insert into hc_revision_por_sistemas 
                                        values(".$z.",".$vector[3].",".$vector[2].",'".$vector[$i]."');";
                         }
                         
                         $query2="insert into hc_revision_por_sistemas_hallazgos values(".$vector[3].",".$vector[2].",'".$vector[0]."',".$vector[1].");";
                         list($dbconn) = GetDBconn();
                         $result1 = $dbconn->Execute($query1);
                         $result2 = $dbconn->Execute($query2);
                                   
                         if ($dbconn->ErrorNo() != 0) 
                         {
                              $this->error = "Insertar examen";
                              $this->mensajeDeError = $dbconn->ErrorMsg();
                              $string="DATOS INSERTADOS INCORRECTAMENTE";
		                    return $dbconn->ErrorMsg();//$string;
                         }
                         else
                         {
                              $string="DATOS INSERTADOS CORRECTAMENTE";
          		          return $string;
                         }
               	} 
                    else
                    {
                         for($i=4;$i<16;$i++)
                         { 
                         	$z=$i-3; 
                              $Update.="UPDATE hc_revision_por_sistemas SET 
                              sw_normal='".$vector[$i]."' 
                              where tipo_sistema_id=".$z." and evolucion_id=".$vector[3]." and 
                              ingreso=".$vector[2].";";
                         }
               
                         $Update2="UPDATE hc_revision_por_sistemas_hallazgos SET 
                         hallazgo='".$vector[0]."'
                         where usuario_id=".$vector[1]."and evolucion_id=".$vector[3]." and 
                         ingreso=".$vector[2].";";
                         
                         
                         list($dbconn) = GetDBconn();
                         $result1 = $dbconn->Execute($Update);
                         $result2 = $dbconn->Execute($Update2);
     
          
                    if ($dbconn->ErrorNo() != 0) 
                    {
                         $this->error = "Insertar examen";
                         $this->mensajeDeError = $dbconn->ErrorMsg();
                         $string="DATOS ACTUALIZADOS INCORRECTAMENTE";
                         return $dbconn->ErrorMsg();
                    }
                    else
                    {
                         $string="DATOS ACTUALIZADOS CORRECTAMENTE";
                         return $string;
                    }
               }
          }
     
     }//end of class

    /*
        cuando creamos el objeto que tiene los procesos debemos indicar como único parámetro un
        array con todas las funciones posibles ... esto se hace para evitar que se pueda llamar
        a cualquier método del objeto.

    */

    $oRS = new procesos_admin( array( 'get_valores','get_valores_depto', "get_valores_mpio"));
    // el metodo action es el que recoge los datos (POST) y actua en consideración ;-)
    $oRS->action();


?>