<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Vacunacion.class.php,v 1.2 2008/05/28 15:18:54 gerardo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Gerardo Amador Vidal
  */
    /**
  * Clase : Vacunacion
  * Clase que se utilza para ingresar, consultar, eliminar, desabilitar y modificar las vacunas y sus parametros
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Gerardo Amador Vidal
  */

class Vacunacion extends ConexionBD{
  

  /**
  * Constructor de la clase
  *
  */
  function Vacunacion(){
  }
  
  
    /**
    * Funcion donde se validan los permisos de un usuario sobre el modulo
    * 
    * @return mixed
    */  
  function ObtenerPermisos(){
  
      $sql  = "SELECT EM.empresa_id AS empresa, ";
      $sql .= "       EM.razon_social AS razon_social ";
      $sql .= "FROM   userpermisos_vacunacion US,";
      $sql .= "       empresas EM ";
      $sql .= "WHERE  US.usuario_id = ".UserGetUID()." ";
      $sql .= "AND    US.empresa_id = EM.empresa_id ";
      $sql .= "AND    US.sw_activo = '1' ";
      
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;      
          
  
  }
  
  
  /**
  *Funcion que busca y lista las vacunas
  *
  *@param string $nombre, Nombre de la vacuna
  *@return mixed 
  */
  function BuscarVacuna($solicitud, $off)
  {
    //$this->debug = true;
  
    //$sql = "SELECT * ";
    //$sql .= "FROM vacunas_tipo vt ";
    //$sql .= "WHERE  vt.nombre_vacuna ILIKE '%".$solicitud['nombre_vacuna']."%' ";
    
    //$sql = "SELECT * ";
    //$sql = "SELECT vt.vacuna_id, vt.nombre_vacuna, vp.vacuna_param_id "
    //$sql = "SELECT vt.vacuna_id AS vt_vacuna_id, vt.nombre_vacuna AS vt_nombre_vacuna, ";
    $sql = "SELECT DISTINCT ON (vt_vacuna_id) vt.vacuna_id AS vt_vacuna_id, vt.nombre_vacuna AS vt_nombre_vacuna, ";    
    $sql .= "vp.vacuna_param_id AS vp_vacuna_param_id, vt.observacion AS vt_observacion ";
    $sql .= "FROM vacunas_tipo vt ";
    $sql .= "LEFT OUTER JOIN vacunas_parametros vp ON vt.vacuna_id = vp.vacuna_id ";
    $sql .= "WHERE  vt.nombre_vacuna ILIKE '%".$solicitud['nombre_vacuna']."%' "; 
    $sql .= "AND  vt.sw_habilitar = '1'";
    
    //Parte del contador
    $cont = "SELECT COUNT(*) FROM(".$sql.") AS A";
    
    
    
    $this->ProcesarSqlConteo($cont, $off,null,5);
    
    //$sql .= "ORDER BY ES.codigo_proveedor_id ";
    $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
     
    
    if(!$rst = $this->ConexionBaseDatos($sql))  return false;
    
    $datos = array();
    
    while(!$rst->EOF)
    {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    } 
    
      $rst->Close();
      return $datos;       
  }
  
  
  /**
  * Funcion que ingresa una vacuna
  * @param mixed $solicitud, contiene los datos que hacen parte del registro de una vacuna
  * @return int $indice['sq'], es el identificador de la vacuna que se va a insertar
  */
  function InsertarVacuna($solicitud)
  {
    $indice = array();
    $sql = "SELECT NEXTVAL('vacunas_tipo_vacuna_id_seq') AS sq";
    
    if(!$rst = $this->ConexionBaseDatos($sql))  return false;
    
    if(!$rst->EOF)
    {
      $indice = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();     
    }
    
    $rst->Close(); 
    
    $sqlerror = "SELECT setval('vacunas_tipo_vacuna_id_seq', ".($indice['sq']-1).") ";    
    
    $this->ConexionTransaccion();
   
    $sql = "INSERT INTO vacunas_tipo( ";
    $sql .= " vacuna_id,";
    $sql .= " nombre_vacuna,";
    $sql .= " usuario_reg,";
    $sql .= " usuario_ult_act,";
    $sql .= " fecha_reg,";
    $sql .= " fecha_ult_act,"; 
    $sql .= " observacion, ";
    $sql .= " sw_habilitar) ";
  
    $sql .= "VALUES(";
    $sql .= " ".$indice['sq'].",";
    $sql .= " '".$solicitud['nombre_vacuna']."',";
    $sql .= " ".UserGetUID().",";
    $sql .= " NULL,";    
    $sql .= " NOW(),";
    $sql .= " NULL,";    
    $sql .= " '".$solicitud['observacion']."', ";
    $sql .= " 1) ;";
    
        
    if(!$rst = $this->ConexionTransaccion($sql))
    {
      if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
      return false;      
    }    
       
    
    $this->Commit();
    return $indice['sq'];
    
    //return true;
  }
  
  
  /**
  *Funcion que sirve para eliminar el registro de una vacuna
  *@param int $valor, clave de la vacuna que se va ha eliminar
  *@return boolean
  */
  function EliminarVacuna($valor){
  
    $this->ConexionTransaccion();  
  
    $sql = "DELETE FROM vacunas_tipo ";
    $sql .= "WHERE vacuna_id = '".$valor."';";
    
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
      //if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
      return false;      
    }
    
    $this->Commit();
    
    return true;      
  }
  
  
  /**
  *Funcion que permite editar una vacuna 
  *@param mixed $solicitud, contiene los datos que se van a editar en una vacuna
  *@return boolean
  */
  function EditarVacuna($solicitud){
    
    $this->ConexionTransaccion();  
  
    $sql .= "UPDATE vacunas_tipo SET 
             nombre_vacuna = '".$solicitud['nombre_vacuna']."', 
             observacion =  '".$solicitud['observacion']."',  
             usuario_ult_act =  ".UserGetUID().", 
             fecha_ult_act = NOW() 
             WHERE vacuna_id ='".$solicitud['vacuna_id']."'; 
             ";
    
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
      //if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
      return false;      
    }
    
    $this->Commit();
  
    return true;
  }
  
  
  /**
  *Funcion utlizada para desabilitar una vacuna
  *@param int $solicitud, es el identificador de la vacuna que se va a desabilitar
  *@return boolean
  */
  function DesabilitaVacuna($solicitud){
    
    //$this->debug = true;
  
    $this->ConexionTransaccion();
  
    $sql .= "UPDATE vacunas_tipo SET 
             sw_habilitar = '0' 
             WHERE vacuna_id = '".$solicitud."'   
             ";   
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
      //if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
      return false;      
    }
    
    $this->Commit();
  
    return true;    
    
  }
  
  
  
  /**
  *Funcion con la cual se ingresan los parametros de una vacuna
  *@param mixed $solicitud, contiene los datos que hacen parte del registro de un parametro
  *@return int $indice['sqvp'], es el identificador de el parametro que se va a insertar  
  */
  function InsertarParametro($solicitud){
  
    $indice = array();
    $sql = "SELECT NEXTVAL('vacunas_parametros_vacuna_param_id_seq') AS sqvp ";
    
    if(!$rst = $this->ConexionBaseDatos($sql))  return false;
    
    if(!$rst->EOF)
    {
      $indice = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();     
    }
    
    $rst->Close(); 
    
    $sqlerror = "SELECT setval('vacunas_parametros_vacuna_param_id_seq', ".($indice['sqvp']-1).") ";    
    
    $this->ConexionTransaccion(); 
    
    if(!$solicitud['EdadMin']) $solicitud['EdadMin'] = 0;
    if(!$solicitud['EdadMax']) $solicitud['EdadMax'] = 0;    
    
    $sql = "INSERT INTO vacunas_parametros(
            vacuna_param_id,
            vacuna_id,
            sw_rn, 
            edad_min,
            edad_max,
            usuario_reg,
            usuario_ult_act,  
            fecha_reg,
            fecha_ult_act,
            observacion,
            unidad,
            sw_habilitar)
            VALUES(
            ".$indice['sqvp'].",
            ".$solicitud['vt_vacuna_id'].",
            ".$solicitud['RecNac'].",
            ".$solicitud['EdadMin'].",
            ".$solicitud['EdadMax'].",
            ".UserGetUID().",
            NULL,
            NOW(),
            NULL,
            '".$solicitud['observacion']."', 
            ".$solicitud['Unidad'].", 
            1); ";
            
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
      if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
      return false;      
    }    
       
    
    $this->Commit();
    return $indice['sqvp'];            
    //return true;   
     
  }
  
  /**
  *Funcion que sirve para eliminar el registro de un parametro de vacuna
  *@param mixed $solicitud, contiene los identificadores de la vacuna y del parametro a eliminar
  *@return boolean
  */
  function EliminarParametro($solicitud){
  
    $this->ConexionTransaccion();  
  
    $sql .= "DELETE FROM vacunas_parametros
             WHERE vacuna_param_id = '".$solicitud['vp_vacuna_param_id']."' 
             AND vacuna_id = '".$solicitud['vt_vacuna_id']."'; ";
    
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
      //if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
      return false;      
    }
    
    $this->Commit();
    
    return true;      
  }  
  

  /**
  *Funcion que permite editar un parametro de una vacuna 
  *@param mixed $solicitud, contiene los datos que se van a editar en parametro
  *@return boolean
  */
  function EditarParametro($solicitud){
    //print_r($solicitud);
    //$this->debug = true;  
    
    //Esto es para poder setear las edades cuando los input esten desabilitados
    if(!$solicitud['EdadMin']) $solicitud['EdadMin'] = 0;
    if(!$solicitud['EdadMax']) $solicitud['EdadMax'] = 0;
    
    $this->ConexionTransaccion();  
  
    $sql .= "UPDATE vacunas_parametros SET
              sw_rn = '".$solicitud['RecNac']."',
              edad_min = ".$solicitud['EdadMin'].", 
              edad_max = ".$solicitud['EdadMax'].",
              unidad = '".$solicitud['Unidad']."',
              observacion = '".$solicitud['observacion']."',  
              usuario_ult_act = ".UserGetUID().", 
              fecha_ult_act = NOW()
              WHERE vacuna_param_id = '".$solicitud['vp_vacuna_param_id']."' 
              AND vacuna_id = '".$solicitud['vt_vacuna_id']."' ;";
    
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
      //if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
      return false;      
    }
    
    $this->Commit();
  
    return true; 
  }  
  
  
  /**
  *Funcion utlizada para desabilitar el parametro en una vacuna
  *@param mixed $solicitud, contiene los identificadores de la vacuna y del parametro a desabilitar
  *@return boolean
  */
  function DesabilitaParametro($solicitud){
  
    $this->ConexionTransaccion();
  
    $sql .= "UPDATE vacunas_parametros SET 
             sw_habilitar = '0' 
             WHERE vacuna_param_id = '".$solicitud['vp_vacuna_param_id']."' 
             AND vacuna_id = '".$solicitud['vt_vacuna_id']."' ;";   
             
    
    if(!$rst = $this->ConexionTransaccion($sql))
    {
      //if(!$rst = $this->ConexionTransaccion($sqlerror)) return false;
      return false;      
    }
    
    $this->Commit();
  
    return true;    
    
  }  
  
  
  /**
  *Funcion utilizada para buscar y listar los parametros existentes en una vacuna 
  *@param string $valor, Identificador de la vacuna
  *@return mixed $datos, contiene los registros correspondientes a los parametros de la vacuna 
  */
  function BuscarParametros($valor){
  
    //$this->debug = true;  
  
    /*$sql .= "SELECT vp.vacuna_param_id as vp_vacuna_param_id, vp.vacuna_id as vp_vacuna_id, 
            vp.sw_rn as vp_sw_rn, vp.edad_min as vp_edad_min, vp.edad_max as vp_edad_max, 
            vp.usuario_reg as vp_usuario_reg, vp.observacion as vp_observacion    
            FROM vacunas_parametros vp
            WHERE vp.vacuna_id = '".$valor."';";*/
            
    $sql .= "SELECT vp.vacuna_param_id as vp_vacuna_param_id, vp.vacuna_id as vp_vacuna_id,  
            vp.sw_rn as vp_sw_rn, vp.edad_min as vp_edad_min, vp.edad_max as vp_edad_max, 
            vp.usuario_reg as vp_usuario_reg, vp.observacion as vp_observacion, vp.unidad as vp_unidad, 
            vp.sw_habilitar as vp_sw_habilitar, su.usuario as su_usuario 
            FROM vacunas_parametros vp, system_usuarios su 
            WHERE vp.vacuna_id = '".$valor."' AND su.usuario_id = vp.usuario_reg 
            AND  vp.sw_habilitar = '1'; ";        
            
    
    if(!$rst = $this->ConexionBaseDatos($sql))  return false;
    
    $datos = array();
    
    while(!$rst->EOF)
    {
      $datos[] = $rst->GetRowAssoc($ToUpper = false);
      $rst->MoveNext();
    } 
    
    $rst->Close();
    return $datos;               
    
  }

  

}

?>