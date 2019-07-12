<?php
  /******************************************************************************
  * $Id: PermisosSQL.class.php,v 1.1 2006/10/10 14:27:44 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.1 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class PermisosSQL
	{
		function PermisosSQL(){}
		/**********************************************************************************
		* Funcion donde se listan modulos
		* 
		* @return array 
		***********************************************************************************/
		function ListarModulos()
		{	
			$sql="select * from userpermisos_modulo_permisos";
			if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="ne se hizo la consulta";
         return $cad;
       }
			else
       {      
         
          $retorno = array();
          while(!$rst->EOF)
          {
             $retorno[] = $rst->GetRowAssoc($ToUpper = false);
             $rst->MoveNext();
          }
          $rst->Close();
          return $retorno;
       
       }
		}
		
    
    
    
    /******************************************************************************
    * 
    *funcion que lista los modulos segun el grupo de componentes
    *
    *********************************************************************************/
    function ListarGruposModulo($modulo)
    { 
      $sql="select * from system_modulos_permisos_grupos where modulo='".$modulo."'";
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="ne se hizo la consulta";
         return $cad;
       }
      else
       {      
         
          $retorno = array();
          while(!$rst->EOF)
          {
             $retorno[] = $rst->GetRowAssoc($ToUpper = false);
             $rst->MoveNext();
          }
          $rst->Close();
          return $retorno;
       
       }
    }
    
    
    
    /**********************************************************************************
    * Funcion donde se listan perfiles segun el modulo escogido
    * 
    * @return array 
    ***********************************************************************************/
    function ListarPerfilesModulo($modulo)
    { 
      $sql="select * from system_modulos_permisos_perfiles where modulo='".$modulo."'";
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="ne se hizo la consulta";
         return $cad;
       }
      else
       {      
         
          $retorno = array();
          while(!$rst->EOF)
          {
             $retorno[] = $rst->GetRowAssoc($ToUpper = false);
             $rst->MoveNext();
          }
          $rst->Close();
          return $retorno;
       
       }
    }
    /**********************************************************************************
    * Funcion donde se listan componentes segun el grupo escogido
    * 
    * @return array 
    ***********************************************************************************/
    function ListarComponentesSegunGrupo($modulo)
    { 
      GLOBAL $ADODB_FETCH_MODE;
      $sql="select a.descripcion_grupo,a.grupo_id,b.modulo_tipo,b.modulo,b.componente_id,b.descripcion_componente 
      from  
      system_modulos_permisos_grupos_componentes as b,
      system_modulos_permisos_grupos as a 
      where a.modulo=b.modulo and 
      a.grupo_id=b.grupo_id and 
      b.modulo='".$modulo."' order by a.descripcion_grupo"; 
      //if(!$rst = $this->ConexionBaseDatos($sql)) 
       //{  $cad="ne se hizo la consulta";
        // return $cad;
       //}
      //else
       //{     
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM; 
         
          $retorno = array();
          /*while(!$rst->EOF)
          {
             $retorno[] = $rst->GetRowAssoc($ToUpper = false);
             $rst->MoveNext();
          }*/
          
          while($datos=$result->FetchRow())
          {
           $retorno[$datos['descripcion_grupo']][]=$datos;
          }
              
          $result->Close();
          return $retorno;
       
       //}
    }
    
    /**********************************************************************************
    * Funcion donde se listan componentes segun el grupo escogido
    * 
    * @return array 
    ***********************************************************************************/
    function ListarGrupo1($modulo)
    { 
      $sql="select distinct a.descripcion_grupo 
      from  
      system_modulos_permisos_grupos_componentes as b,
      system_modulos_permisos_grupos as a 
      where a.modulo=b.modulo and 
      a.grupo_id=b.grupo_id and 
      b.modulo='".$modulo."'";
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="ne se hizo la consulta";
         return $cad;
       }
      else
       {      
         
          $retorno = array();
          while(!$rst->EOF)
          {
             $retorno[] = $rst->GetRowAssoc($ToUpper = false);
             $rst->MoveNext();
          }
          
              
          $rst->Close();
          return $retorno;
       
       }
    }
    /**********************************************************************************
    * Funcion donde se consulta si en system_modulos_permisos_perfiles_componentes se 
    * encunetran tuplas con los siguientes datos;
    * @return valor
    ***********************************************************************************/
    function RecolectarDatos($componente_id)
    { 
                        
                       
       $sql="select * from system_modulos_permisos_grupos_componentes 
             where componente_id=".$componente_id." ";
                  
      
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la consulta";
         return $cad;
       }
      else
       {      
         
          $retorno = array();
          while(!$rst->EOF)
          {
             $retorno[] = $rst->GetRowAssoc($ToUpper = false);
             $rst->MoveNext();
          }
          
              
          $rst->Close();
          return $retorno;
       
       }
    
    }
    
    
    /**************************************************************************
    *
    *
    ****************************************************************************/
    
    function BuscarDatos($perfil_id)
    { 
                                      
                        
                         
      $sql="select * from system_modulos_permisos_perfiles_componentes 
            where perfil_id=".$perfil_id."";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="ne se hizo la consulta";
         return $cad;
       }
      else
       {      
         
          $tuplas=$rst->RecordCount();
          
               
              
          $rst->Close();
          return $tuplas;
       
       }
    
    }
    
    /********************************************************************************
    * selecciona el id del grupo
    * 
    *********************************************************************/
    
    function ConsultarGrupo_id($grupo)
    { 
      
       $sql="select grupo_id from system_modulos_permisos_grupos  
            where descripcion_grupo='".$grupo."'";
   
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        $grupo = array();
      while(!$resultado->EOF)
      {
        $grupo[]=$resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $grupo;
            
            
             
     
    
    }
    
    /**************************************************************************
    *
    *recolecta los componentes_id de cierto perfil
    ****************************************************************************/
    function ConsultarPerfil($modulo,$perfil)
    { 
                        
                       
       $sql="select componente_id from system_modulos_permisos_perfiles_componentes 
             where modulo='".$modulo."' and perfil_id=".$perfil."";
                  
      
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la consulta";
         return $cad;
       }
      else
       {      
         
          $retorno = array();
          while(!$rst->EOF)
          {
             $retorno[] = $rst->GetRowAssoc($ToUpper = false);
             $rst->MoveNext();
          }
          
              
          $rst->Close();
          return $retorno;
       
       }
    
    }
    
    
    
    
    /**********************************************************************************
    * Funcion que inserta modulo,modulo_tipo,componente_id,perfil_id,grupo_id en la tabla, 
    * system_modulos_permisos_perfiles_componentes 
    * @return array 
    ***********************************************************************************/
    function InsertarDatos($modulo,$modulo_tipo,$perfil_id,$grupo_id,$componente_id)
    { 
      $sql="insert into system_modulos_permisos_perfiles_componentes values('".$modulo."','".$modulo_tipo."',
            ".$perfil_id.",".$grupo_id.",".$componente_id.");";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la inserci�n";
         return $cad;
       }
      else
       {      
         $cad="Inserci�n Hecha Satisfactoriamente";
         $rst->Close();
         return $cad;
       }
    
    }
    
    /**********************************************************************************
    * Funcion que averigua si ese usuario ya tienen asignado un perfil
      
    ***********************************************************************************/
    function ConsultarUsuario($usuario_id)
    { 
      $sql="select * from userpermisos_modulo_permisos where usuario_id=".$usuario_id."";
                 
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="Operaci�n invalida";
         return $cad;
       }
      else
       {      
         $cad=$rst->RecordCount();
         $rst->Close();
         return $cad;
       }
    
    }
    /**********************************************************************************
    * Funcion que trae el perfil_id  usuario ya tienen asignado un perfil
      
    ***********************************************************************************/
    function Consultarperfil_idUsuario($usuario_id)
    { 
      $sql="select perfil_id from userpermisos_modulo_permisos where usuario_id=".$usuario_id."";
                 
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="Operaci�n invalida";
         return $cad;
       }
      else
       { 
          $perfil = array();
          while(!$rst->EOF)
          {
            $perfil[]=$rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
          }
        
          $rst->Close();
          return $perfil;
             
       }
    
    }
    
    /**********************************************************************************
    * Funcion que trae el eatdo del permiso del usuario
      
    ***********************************************************************************/
    function ConsultarEstadoUsuario($usuario_id)
    { 
      $sql="select sw_estado,perfil_id from userpermisos_modulo_permisos where usuario_id=".$usuario_id."";
                 
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="Operaci�n invalida";
         return $cad;
       }
      else
       { 
          $perfil = array();
          while(!$rst->EOF)
          {
            $perfil[]=$rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
          }
        
          $rst->Close();
          return $perfil;
             
       }
    
    }
    
    /**********************************************************************************
    * Funcion que cambia el eatdo del permiso del usuario
      
    ***********************************************************************************/
    function ActuaEstadoUsuario($usuario_id,$valor)
    { 
              $sql="update userpermisos_modulo_permisos 
                   set sw_estado='".$valor."'
                   where usuario_id=".$usuario_id."";
                 
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la inserci�n";
         return $cad;
       }
      else
       {      
         $cad="Permiso cambiado";
         $rst->Close();
         return $cad;
       }
    
    }
    /**********************************************************************************
    * Funcion que inserta en la tabla userpermisos_modulos_permisos
    ***********************************************************************************/
    function InsertarPerfilUsuario($usuario_id,$modulo,$modulo_tipo,$perfil_id)
    { 
      $sql="insert into userpermisos_modulo_permisos values(".$usuario_id.",'".$modulo."',
      '".$modulo_tipo."',1,".$perfil_id.");";      
             
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="Operaci�n invalida";
         return $cad;
       }
      else
       {      
         $cad="Perfil Asignado Satisfactoriamente";
         $rst->Close();
         return $cad;
       }
    
    }
    
    /********************************************************************************
    * selecciona LAS EXCEPCIONES DEL USUARIO
    * 
    *********************************************************************/
    
    function ConsultarExepciones($modulo,$usuario_id)
    { 
      
            $sql="select * from system_modulos_permisos_excepciones
            where modulo='".$modulo."' and usuario_id=".$usuario_id."";
   
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        $excepciones = array();
        while(!$resultado->EOF)
        {
          $excepciones[]=$resultado->GetRowAssoc($ToUpper = false);
          $resultado->MoveNext();
        }
      
        $resultado->Close();
        return $excepciones;
    }
    
    
    /********************************************************************************
    * selecciona el id del grupo
    * 
    *********************************************************************/
    
    function ConsultarGrupo_id_c($componente_id)
    { 
      
       $sql="select grupo_id from system_modulos_permisos_grupos_componentes
            where componente_id=".$componente_id."";
   
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        $grupo = array();
        while(!$resultado->EOF)
        {
          $grupo[]=$resultado->GetRowAssoc($ToUpper = false);
          $resultado->MoveNext();
        }
      
        $resultado->Close();
        return $grupo;
    }
    /**********************************************************************************
    * Funcion que inserta en la tabla system_modulos_permisos_excepciones
    ***********************************************************************************/
    function InsertarExcepcion($modulo,$modulo_tipo,$grupo_id,$componente_id,$usuario_id,$sw_permiso)
    { 
        $sql="insert into system_modulos_permisos_excepciones values('".$modulo."','".$modulo_tipo."',".$grupo_id.",
      ".$componente_id.",".$usuario_id.",'".$sw_permiso."');";      
             
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="Operaci�n invalida";
          return $cad;
       }
      else
       {      
         $cad="Excepci�n Hecha Satisfactoriamente";
         $rst->Close();
         return $cad;
       }
    
    }
    
    /**********************************************************************************
    * Funcion que consulta si esta en la tabla system_modulos_permisos_excepciones
    ***********************************************************************************/
    function ConsultarExcepcion($grupo_id,$componente_id,$usuario_id)
    { 
           $sql="select * from system_modulos_permisos_excepciones 
           where grupo_id=".$grupo_id." and componente_id=".$componente_id."
           and usuario_id=".$usuario_id.");";      
             
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        $grupo = array();
        while(!$resultado->EOF)
        {
          $grupo[]=$resultado->GetRowAssoc($ToUpper = false);
          $resultado->MoveNext();
        }
      
        $resultado->Close();
        return $grupo;
    
     }
     /**********************************************************************************
    * Funcion que actualiza  en la tabla system_modulos_permisos_excepciones
    ***********************************************************************************/
    function LimpiarExcepcion($usuario_id)
    { 
       $sql="delete from system_modulos_permisos_excepciones 
             where usuario_id=".$usuario_id.";";      
             
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="Limpieza invalida";
          return $cad;
       }
      else
       {      
         $cad="bien";
         $rst->Close();
         return $cad;
       }  
     }   
    /**********************************************************************************
    * Funcion que actualiza en la tabla userpermisos_modulos_permisos
    ***********************************************************************************/
    function ActualizarPerfilUsuario($usuario_id,$perfil_id)
    {       
      $sql="update userpermisos_modulo_permisos SET perfil_id=".$perfil_id." WHERE usuario_id=".$usuario_id."; ";      
             
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="Operaci�n invalida";
         return $cad;
       }
      else
       {      
         $cad="Perfil Asignado Satisfactoriamente";
         $rst->Close();
         return $cad;
       }
    
    }
    /**********************************************************************************
    * Funcion que actualiza modulo,modulo_tipo,componente_id,perfil_id,grupo_id en la tabla, 
    * system_modulos_permisos_perfiles_componentes 
    * @return array 
    ***********************************************************************************/
    function BorrarDatos($modulo,$perfil_id,$grupo,$componente)
    { 
      
       $sql="delete from system_modulos_permisos_perfiles_componentes 
            where perfil_id=".$perfil_id." and grupo_id=".$grupo." 
            and componente_id=".$componente." and modulo='".$modulo."'";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="Operaci�n invalida";
         return $cad;
       }
      else
       {      
         $cad="Actualizacion Hecha Satisfactoriamente";
         $rst->Close();
         return $cad;
       }
    
    }
    
    
     /**********************************************************************************
    * Funcion que actualiza modulo,modulo_tipo,componente_id,perfil_id,grupo_id en la tabla, 
    * system_modulos_permisos_perfiles_componentes 
    * @return array 
    ***********************************************************************************/
    function MarcarChecks($perfil_id)
    { 
      
       $sql="select a.componente_Id,c.modulo,c.modulo_tipo,b.descripcion_grupo 
        from system_modulos_permisos_perfiles_componentes as a, 
        system_modulos_permisos_grupos as b,
        system_modulos_permisos_grupos_componentes as c
            where a.perfil_id=".$perfil_id." and 
            a.grupo_id=b.grupo_id and
            a.componente_id=c.componente_id";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la consulta";
         return $cad;
       }
      else
       {      
         
          $retorno = array();
          while(!$rst->EOF)
          {
             $retorno[] = $rst->GetRowAssoc($ToUpper = false);
             $rst->MoveNext();
          }
          
              
          $rst->Close();
          return $retorno;
       
       }
    
    }
      
       /**********************************************************************************
    * Funcion que actualiza modulo,modulo_tipo,componente_id,perfil_id,grupo_id en la tabla, 
    * system_modulos_permisos_perfiles_componentes 
    * @return array 
    ***********************************************************************************/
    function DesmarcarChecks($componente_id)
    { 
        
        $sql="select distinct c.componente_Id, c.modulo,c.modulo_tipo,b.descripcion_grupo 
        from system_modulos_permisos_grupos as b,
        system_modulos_permisos_perfiles_componentes as c
        where c.componente_id=".$componente_id." and 
        c.grupo_id=b.grupo_id";
      
        
      
           
      
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la consulta";
         return $cad;
       }
      else
       {      
         
          $retorno = array();
          while(!$rst->EOF)
          {
             $retorno[] = $rst->GetRowAssoc($ToUpper = false);
             $rst->MoveNext();
          }
          
              
          $rst->Close();
          return $retorno;
       
       }
    
    }
  /********************************************************************************
    * Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
    * importantes a la hora de referenciar al paginador
    *********************************************************************/
    
    function ConsultarPermisos()
    { 
      
       $sql="select c.modulo,c.modulo_tipo,c.text_1,c.text_2,c.text_3,c.text_4,c.text_5
             from userpermisos_modulo_permisos as a, 
              system_modulos_permisos_titulos as c
            where a.modulo=c.modulo and a.modulo_tipo=c.modulo_tipo  and
             a.usuario_id=".UserGetUID()."";
   
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      while(!$resultado->EOF)
      {
        $modulos[$resultado->fields[2]]=$resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $modulos;
            
            
             
     
    
    }
    
		
    
/************************************************************************************
*
*Funcion que realiza la funcion avanazada de usuarios
*
*************************************************************************************/    
function BuscarUsuario($modulo,$criterio,$elemento)
{ 
      
       if($criterio==1)
        {      
                $sql="select A.* 
                      from       
                      (select  a.usuario_id,a.usuario,a.nombre,
                      b.modulo,b.modulo_tipo,b.sw_estado,
                      c.descripcion_perfil 
                      FROM 
                      system_usuarios as a LEFT JOIN 
                      (select * from userpermisos_modulo_permisos 
                      where modulo='".$modulo."') as b
                      ON (a.usuario_id=b.usuario_id) LEFT JOIN 
                      system_modulos_permisos_perfiles as c 
                      ON  (b.perfil_id=c.perfil_id))as A where a.usuario_id=".$elemento."";
                     
    
        }
       
        
       
        if($criterio==2)
        {      
                $sql="select A.* 
                      from       
                      (select  a.usuario_id,a.usuario,a.nombre,
                      b.modulo,b.modulo_tipo,b.sw_estado,
                      c.descripcion_perfil 
                      FROM 
                      system_usuarios as a LEFT JOIN 
                      (select * from userpermisos_modulo_permisos 
                      where modulo='".$modulo."') as b
                      ON (a.usuario_id=b.usuario_id) LEFT JOIN 
                      system_modulos_permisos_perfiles as c 
                      ON  (b.perfil_id=c.perfil_id))as A where a.usuario='".$elemento."'";
    
        }
       
       if($criterio==3)
        {      
                     $sql="select a.* 
                      from       
                      (select  a.usuario_id,a.usuario,a.nombre,
                      b.modulo,b.modulo_tipo,b.sw_estado,
                      c.descripcion_perfil 
                      FROM 
                      system_usuarios as a LEFT JOIN 
                      (select * from userpermisos_modulo_permisos 
                      where modulo='".$modulo."') as b
                      ON (a.usuario_id=b.usuario_id) LEFT JOIN 
                      system_modulos_permisos_perfiles as c 
                      ON  (b.perfil_id=c.perfil_id))as a where a.nombre LIKE '%".strtoupper ($elemento)."%'";
    
        }
       
       
       
       
       if(!$resultado = $this->ConexionBaseDatos($sql))
          return false;
        
       $retorno=Array();
       while(!$resultado->EOF)
          { 
            $retorno[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
          }
      
      $resultado->Close();
      return $retorno;
            
             
     
    
}
    
    
    
/************************************************************************************
*
*Funcion que realiza la consulta de usuarios
*
*************************************************************************************/    
     function MostrarUsuariosPer($modulo,$offset)
    { 
      
      $sql1="select  count(*) 
                     FROM 
                     system_usuarios as a LEFT JOIN 
                     (select * from userpermisos_modulo_permisos 
                     where modulo='".$modulo."') as b 
                     ON (a.usuario_id=b.usuario_id) LEFT JOIN 
                     system_modulos_permisos_perfiles as c 
                     ON  (b.perfil_id=c.perfil_id)" ;
      $this->ProcesarSqlConteo($sql1,20,$offset);      
       
       $sql="select  a.usuario_id,a.usuario,a.nombre,
                     b.modulo,b.modulo_tipo,b.sw_estado,
                     c.descripcion_perfil 
                     FROM 
                     system_usuarios as a LEFT JOIN 
                     (select * from userpermisos_modulo_permisos 
                     where modulo='".$modulo."') as b
                     ON (a.usuario_id=b.usuario_id) LEFT JOIN 
                     system_modulos_permisos_perfiles as c 
                     ON  (b.perfil_id=c.perfil_id) order by a.usuario_id 
                     limit ".$this->limit." OFFSET ".$this->offset."" ;
   
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $retorno=Array();
      while(!$resultado->EOF)
      {
        $retorno[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $retorno;
            
       
     
    
    }
    
    
////////////////////////////////    
   

    
    
///////////////////////////////    
      
  
    /********************************************************************************
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*********************************************************************************/
		function ProcesarSqlConteo($consulta,$limite=null,$offset=null)
		{ 
			$this->offset = 0;
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
			}
			else
			{
				$this->limit = $limite;
			}
			
			if($offset)
			{
				$this->paginaActual = intval($offset);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		

			if(!$result = $this->ConexionBaseDatos($consulta))
				return false;

			if(!$result->EOF)
			{
				$this->conteo = $result->fields[0];
				$result->MoveNext();
			}
			$result->Close();
      
      
			return true;
		}

 
		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar 
		* @return rst 
		************************************************************************************/
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
		/**********************************************************************************
		* Funcion que permite crear una transaccion 
		* @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
		* @param char $num Numero correspondiente a la sentecia sql - por defect es 1
		*
		* @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
		*								 se devuelve nada
		***********************************************************************************/

	}
?>