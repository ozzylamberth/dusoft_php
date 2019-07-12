<?php

class signos 
{

/**
* Esta funciï¿½ Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function signos($objeto=null)
	{
     	$this->obj=$objeto;
          return true;
	}

/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'10/25/2006',
		'autor'=>'JAIME ANDRES GOMEZ',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}

/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

	function ConsultaSignos()
	{  
          $obj=$this->obj;
          list($dbconn) = GetDBconn();
               
          $query1 = "select nombre,indice_orden 
          from hc_tipos_sistemas order by indice_orden";
          
          $result = $dbconn->Execute($query1);
          if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
          else
          {
               while(!$result->EOF)
               {
                    $vector[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
		return $vector;
	}
     
     /******************************************************************************
     *
     *******************************************************************************/
	
     function ConsultaExamenes($indicador)
	{  
	     $obj=$this->obj;
          list($dbconn) = GetDBconn();
		
     	if($indicador==1)
          {
               $query1="select a.nombre,c.sw_normal,c.evolucion_id 
               from hc_tipos_sistemas as a,hc_revision_por_sistemas as c 
               where a.tipo_sistema_id=c.tipo_sistema_id AND 
               c.ingreso=".$obj->datosEvolucion['ingreso']." and
               c.evolucion_id <>".$obj->datosEvolucion['evolucion_id']."order by evolucion_id";				
               $result = $dbconn->Execute($query1);
          }
          if($indicador==2)
          {
               $query2="select a.nombre,c.sw_normal,c.evolucion_id 
               from hc_tipos_sistemas as a,hc_revision_por_sistemas as c 
               where a.tipo_sistema_id=c.tipo_sistema_id AND
               c.ingreso=".$obj->datosEvolucion['ingreso']." and
               c.evolucion_id=".$obj->datosEvolucion['evolucion_id']."order by evolucion_id";        
               $result = $dbconn->Execute($query2);
          }
        
          if($indicador==3)
          {
               $query2="select a.nombre,c.sw_normal,c.evolucion_id 
               from hc_tipos_sistemas as a,hc_revision_por_sistemas as c 
               where a.tipo_sistema_id=c.tipo_sistema_id AND
               c.ingreso=".$obj->datosEvolucion['ingreso']." order by evolucion_id";        
               $result = $dbconn->Execute($query2);
          }
    
	     if($dbconn->ErrorNo() != 0)
          {
               //return $query1;
               return false;
          }
          else
          {
               while(!$result->EOF)
               {
                    $vector[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
                         
          }
          //return $query1;
          return $vector;
	}

	function ConsultarNombre()
	{	
    		$obj=$this->obj;
		    list($dbconn) = GetDBconn();
        $query="select nombre from profesionales where tercero_id=
                (select tercero_id from profesionales_usuarios 
                where usuario_id='".$obj->datosEvolucion['usuario_id']."')";
        $result1 = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
              return false;
        }
        else
        {
              while(!$result1->EOF)
              {
                  $nombre[]=$result1->GetRowAssoc($ToUpper = false);
                  $result1->MoveNext();
              }
		  
          return $nombre;
        }
	}
	
	function ConsultarHallazgo($indicador)
	{  
		$obj=$this->obj;
          list($dbconn) = GetDBconn();
	       
		if($indicador==1)   
     	{ 
             
               $query2="select a.hallazgo,a.fecha_registro,a.evolucion_id,b.nombre 
	                      from hc_revision_por_sistemas_hallazgos as a,profesionales as b   
                        where a.ingreso =".$obj->datosEvolucion['ingreso']."  
                        and a.evolucion_id <> ".$obj->datosEvolucion['evolucion_id']."
                        and a.usuario_id=b.usuario_id order by evolucion_id";
      }
     
	     if($indicador==2)    
          {  
               $query2="select a.hallazgo,a.fecha_registro,a.evolucion_id,b.nombre 
               from hc_revision_por_sistemas_hallazgos as a,profesionales as b   
               where a.ingreso =".$obj->datosEvolucion['ingreso']." 
               and a.evolucion_id =".$obj->datosEvolucion['evolucion_id']."
               and a.usuario_id=b.usuario_id order by evolucion_id";
          }      
        
          if($indicador==3)    
          {
               $query2="select a.hallazgo,a.fecha_registro,a.evolucion_id,b.nombre 
               from hc_revision_por_sistemas_hallazgos as a,profesionales as b   
               where a.ingreso =".$obj->datosEvolucion['ingreso']."
               and a.usuario_id=b.usuario_id order by evolucion_id";
          }           
       
          $resultado=$dbconn->Execute($query2);
          if($dbconn->ErrorNo() != 0)
          {
            //return $query2;
            return false;
	        }
          else
          {
               while(!$resultado->EOF)
               {
                    $lsistemas[]=$resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
               }
     
               //return $query2;
               return $lsistemas;
          }
	}
	
	
	
	function InsertarExamenes($sistemas=array(),$n_sistemas,$hallazgo)
	{  
		$obj=$this->obj;
		list($dbconn) = GetDBconn();
		$dbconn->debug = true;
          	
		for($i=1;$i<=$n_sistemas;$i++)
		{
          	$query1.="insert into hc_revision_por_sistemas values(".$i.",".$obj->datosEvolucion['evolucion_id'].",".$obj->datosEvolucion['ingreso'].",'".$sistemas[$i]."');";
		}
		
          $query2="insert into hc_revision_por_sistemas_hallazgos values(".$obj->datosEvolucion['evolucion_id'].",".$obj->datosEvolucion['ingreso'].",'".$hallazgo."',".$obj->datosEvolucion['usuario_id'].");";
		
          $result1 = $dbconn->Execute($query1);
          $result2 = $dbconn->Execute($query2);
		return true;
	}
}

?>