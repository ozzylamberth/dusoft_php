<?php
  /******************************************************************************
  * $Id: Soluciones.class.php,v 1.1 2006/10/10 14:27:44 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.1 $ 
	* 
	* @autor Hugo F  Manrique 
  ********************************************************************************/
	class Soluciones
	{
		function Soluciones(){}
		/**********************************************************************************
		* Funcion donde se listan modulos
		* 
		* @return array 
		***********************************************************************************/
		function ListarModulos()
		{	
<<<<<<< Soluciones.class.php
			$where = "";
			
			$sql .= "SELECT CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item,";
			$sql .= "				IM.codigo_producto, ";
			$sql .= "				IM.descripcion as producto, ";
			$sql .= "				ME.concentracion_forma_farmacologica,	";
			$sql .= "				ME.unidad_medida_medicamento_id,";
			$sql .= "				ME.factor_conversion, ";
			$sql .= "				ME.factor_equivalente_mg,";
			$sql .= "				IA.descripcion AS principio_activo,";
			$sql .= "				IF.descripcion AS forma,";
			$sql .= "				IF.unidad_dosificacion,";
			$sql .= "				IU.descripcion AS umm, ";
			$sql .= "				IF.cod_forma_farmacologica ";
	
			$where .= "FROM 	inventarios_productos IM, ";
			$where .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IU ";
			$where .= "				ON(ME.unidad_medida_medicamento_id = IU.unidad_medida_medicamento_id), ";
			$where .= "				inv_med_cod_principios_activos IA,  ";
			$where .= "				inv_med_cod_forma_farmacologica IF  ";
			$where .= "WHERE	IM.codigo_producto = ME.codigo_medicamento ";
			$where .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$where .= "AND 		ME.cod_forma_farmacologica = IF.cod_forma_farmacologica ";
			$where .= "AND 		IM.estado = '1' ";
			
			if ($producto != '') $where .= "AND		IM.descripcion ILIKE '%".$producto."%'";
			if ($principio_activo != '') $where .= "AND 		IA.descripcion ILIKE '%".$principio_activo."%'";
			
			$this->ProcesarSqlConteo("SELECT COUNT(*) $where",null,$pagina);
			
			$sql .= $where;
			$sql .= "ORDER BY IM.codigo_producto ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/**********************************************************************************
		* Funcion donde se obtienen las plantillas activas registradas en el sistema
		**********************************************************************************/
		function ObtenerPlantillas()
		{
			$sql .= "SELECT hc_modulo,descripcion ";
			$sql .= "FROM		system_hc_modulos ";
			$sql .= "WHERE	activo = '1' ";
			$sql .= "ORDER BY descripcion ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/********************************************************************************
		* Funcion que permite hacer una busqueda especial de los medicamentos de acuerdo
		* al grupo que se pasa
		*********************************************************************************/
		function BuscarMedicamentosEspecial($producto,$principio_activo,$pagina,$grupo)
		{			
			$sql .= "SELECT CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item,";
			$sql .= "				IM.codigo_producto, ";
			$sql .= "				IM.descripcion as producto, ";
			$sql .= "				ME.concentracion_forma_farmacologica,	";
			$sql .= "				ME.unidad_medida_medicamento_id,";
			$sql .= "				ME.factor_conversion, ";
			$sql .= "				ME.factor_equivalente_mg,";
			$sql .= "				IA.descripcion AS principio_activo,";
			$sql .= "				IF.descripcion AS forma,";
			$sql .= "				IF.unidad_dosificacion,";
			$sql .= "				IU.descripcion AS umm, ";
			$sql .= "				IF.cod_forma_farmacologica, ";
			$sql .= "				SE.marca AS marca ";
			
			$where .= "FROM 	inventarios_productos IM, ";	
			$where .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IU ";
			$where .= "				ON(ME.unidad_medida_medicamento_id = IU.unidad_medida_medicamento_id) ";
			$where .= "				LEFT JOIN ";
			$where .= "				(	SELECT	'1' AS marca, ";
			$where .= "									HD.codigo_medicamento ";
			$where .= "					FROM		hc_formulacion_hospitalaria_grupos_medicamentos_mezclas HM,";
			$where .= "									hc_formulacion_hospitalaria_grupos_medicamentos_mezclas_d HD ";
			$where .= "					WHERE		HM.grupo_id = HD.grupo_id ";
			$where .= "				) AS SE ";
			$where .= "				ON(ME.codigo_medicamento = SE.codigo_medicamento), ";
			$where .= "				inv_med_cod_principios_activos IA,  ";
			$where .= "				inv_med_cod_forma_farmacologica IF  ";
			$where .= "WHERE	IM.codigo_producto = ME.codigo_medicamento ";
			$where .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$where .= "AND 		ME.cod_forma_farmacologica = IF.cod_forma_farmacologica ";
			$where .= "AND 		IM.estado = '1' ";
			
			if ($producto != '') $where .= "AND		IM.descripcion ILIKE '%".$producto."%'";
			if ($principio_activo != '') $where .= "AND 		IA.descripcion ILIKE '%".$principio_activo."%'";
			
			$this->ProcesarSqlConteo("SELECT COUNT(*) $where",null,$pagina);
			
			$sql .= $where;
			$sql .= "ORDER BY producto ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/**********************************************************************************
		* Funcion donde se crean los medicamentos
		* 
		* @return array informacion de los motivo de anulacion de las facturas
		***********************************************************************************/
		function IngresarGrupoMedicamentos($nombre,$medicamentos,$plantilla)
		{
			$sql .= "SELECT COALESCE(TO_NUMBER(MAX(grupo_id),99999999999999999999),0)+1 ";
			$sql .= "FROM hc_formulacion_hospitalaria_grupos_medicamentos ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$id = "01";
			
			if(!$rst->EOF)
				$id = $rst->fields[0];
			
			if(strlen($id."") == 1) $id = "0".$id;
			
			$sql  = "INSERT INTO hc_formulacion_hospitalaria_grupos_medicamentos ";
			$sql .= "				(";
			$sql .= "					grupo_id,";
			$sql .= "					descripcion ";
			$sql .= "				)";
			$sql .= "VALUES(";
			$sql .= "					'".$id."',";
			$sql .= "					'".strtoupper($nombre)."' ";
			$sql .= "				);";
			
			foreach($medicamentos as $key => $datos)
			{
				$sql .= "INSERT INTO hc_formulacion_hospitalaria_grupos_medicamentos_d ";
				$sql .= "				(";
				$sql .= "					grupo_id,";
				$sql .= "					codigo_medicamento ";
				$sql .= "				)";
				$sql .= "VALUES(";
				$sql .= "					'".$id."',";
				$sql .= "					'".$key."' ";
				$sql .= "				);";
			}
			
			foreach($plantilla as $key1 => $plantillas)
			{
				$sql .= "INSERT INTO hc_formulacion_hospitalaria_templates ";
				$sql .= "				(";
				$sql .= "					grupo_id,";
				$sql .= "					hc_modulo ";
				$sql .= "				)";
				$sql .= "VALUES(";
				$sql .= "					'".$id."',";
				$sql .= "					'".$key1."' ";
				$sql .= "				);";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se crean los grupos de soluciones
		* 
		* @return boolean
		***********************************************************************************/
		function IngresarGrupoSolucion($nombre,$plantillas)
		{
			$sql .= "SELECT COALESCE(MAX(grupo_mezcla_id),0)+1 ";
			$sql .= "FROM hc_formulacion_hospitalaria_mezclas_grupos ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$id = "1";
			
			if(!$rst->EOF)	$id = $rst->fields[0];
			
			$sql  = "INSERT INTO hc_formulacion_hospitalaria_mezclas_grupos ";
			$sql .= "				(";
			$sql .= "					grupo_mezcla_id,";
			$sql .= "					descripcion ";
			$sql .= "				)";
			$sql .= "VALUES(";
			$sql .= "					 ".$id.",";
			$sql .= "					'".strtoupper($nombre)."' ";
			$sql .= "				);";
			
			foreach($plantillas as $key => $datos)
			{
				$sql .= "INSERT INTO hc_formulacion_hospitalaria_mezclas_templates ";
				$sql .= "				(";
				$sql .= "					grupo_mezcla_id,";
				$sql .= "					hc_modulo ";
				$sql .= "				)";
				$sql .= "VALUES(";
				$sql .= "					 ".$id.",";
				$sql .= "					'".$key."' ";
				$sql .= "				);";
			}
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/********************************************************************************
=======
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
                  
      /*$sql="select * from system_modulos_permisos_perfiles_componentes 
            where modulo='".$modulo."' 
            and modulo_tipo='".$modulo_tipo."'
            and componente_id='".$componente_id."'
            and perfil_id='".$perfil_id."'
            and grupo_id='".$grupo_id."'
           ";
      */
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
                  
      /*$sql="select * from system_modulos_permisos_perfiles_componentes 
            where modulo='".$modulo."' 
            and modulo_tipo='".$modulo_tipo."'
            and componente_id='".$componente_id."'
            and perfil_id='".$perfil_id."'
            and grupo_id='".$grupo_id."'
           ";
      */
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
       {  $cad="no se hizo la inserción";
         return $cad;
       }
      else
       {      
         $cad="Inserción Hecha Satisfactoriamente";
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
       {  $cad="Operación invalida";
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
       {  $cad="Operación invalida";
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
       {  $cad="Operación invalida";
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
       {  $cad="no se hizo la inserción";
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
       {  $cad="Operación invalida";
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
       {  $cad="Operación invalida";
          return $cad;
       }
      else
       {      
         $cad="Excepción Hecha Satisfactoriamente";
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
       {  $cad="Operación invalida";
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
       {  $cad="Operación invalida";
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
*
*
*************************************************************************************/    
function BuscarUsuario($criterio,$elemento)
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
                      userpermisos_modulo_permisos as b 
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
                      userpermisos_modulo_permisos as b 
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
                      userpermisos_modulo_permisos as b 
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
*
*
*************************************************************************************/    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    function MostrarUsuariosPer($offset)
    { 
      
      $sql1="select  count(*) 
                     FROM 
                     system_usuarios as a LEFT JOIN 
                     userpermisos_modulo_permisos as b 
                     ON (a.usuario_id=b.usuario_id) LEFT JOIN 
                     system_modulos_permisos_perfiles as c 
                     ON  (b.perfil_id=c.perfil_id)" ;
      $this->ProcesarSqlConteo($sql1,20,$offset);      
       
       $sql="select  a.usuario_id,a.usuario,a.nombre,
                     b.modulo,b.modulo_tipo,b.sw_estado,
                     c.descripcion_perfil 
                     FROM 
                     system_usuarios as a LEFT JOIN 
                     userpermisos_modulo_permisos as b 
                     ON (a.usuario_id=b.usuario_id) LEFT JOIN 
                     system_modulos_permisos_perfiles as c 
                     ON  (b.perfil_id=c.perfil_id) 
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
>>>>>>> 1.2
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
<<<<<<< Soluciones.class.php
			return true;
		}
		/********************************************************************
		* Funcion donde se buscan los grupos de soluciones existentes 
		*********************************************************************/  
		function GruposSoluciones()
		{
			$sql .= "SELECT	grupo_mezcla_id, ";
			$sql .= "				descripcion ";
			$sql .= "FROM		hc_formulacion_hospitalaria_mezclas_grupos ";
			$sql .= "ORDER BY descripcion ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$mezclas = array();
			while (!$rst->EOF)
			{
				$mezclas[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}

			$rst->Close();
			return $mezclas;
		}
		/********************************************************************
		* Funcion donde se traen los grupos de medicamentos que han sido
		* catalogados como soluciones
		*********************************************************************/
		function GruposMedicamentosSoluciones()
		{
			$sql .= "SELECT	grupo_id,";
			$sql .= " 			descripcion,";
			$sql .= " 			sw_soluciones ";
			$sql .= "FROM		hc_formulacion_hospitalaria_grupos_medicamentos_mezclas ";
			$sql .= "ORDER BY descripcion ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$mezclas = array();
			while (!$rst->EOF)
			{
				$mezclas[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}

			$rst->Close();
			return $mezclas;
		}
		/********************************************************************
		* Funcion donde se ingresan los datos de la clasificacion de 
		* medicamentos, si son soluciones o no
		*********************************************************************/
		function IngresarGrupoClasificacion($nombre,$sw_solucion)
		{
			$sql .= "SELECT COALESCE(TO_NUMBER(MAX(grupo_id),99999999999999999999),0)+1 ";
			$sql .= "FROM hc_formulacion_hospitalaria_grupos_medicamentos_mezclas ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$id = "01";
			
			if(!$rst->EOF)
				$id = $rst->fields[0];
			
			if(strlen($id."") == 1) $id = "0".$id;
				
			$sql  = "INSERT INTO hc_formulacion_hospitalaria_grupos_medicamentos_mezclas ";
			$sql .= "				(";
			$sql .= "					grupo_id,";
			$sql .= "					descripcion, ";
			$sql .= "					sw_soluciones ";
			$sql .= "				)";
			$sql .= "VALUES(";
			$sql .= "					'".$id."',";
			$sql .= "					'".strtoupper($nombre)."', ";
			$sql .= "					'".$sw_solucion."' ";
			$sql .= "				);";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/********************************************************************
		* Funcion donde se crea la solucion y adiciona el detalle de la misma 
		*********************************************************************/  
		function CrearSolucion($medicamentos,$nombre,$grupo)
		{
			$solucion = "";
			$this->ConexionTransaccion();
			
			$sql  = "SELECT NEXTVAL('hc_formulacion_hospitalaria_mezclas_mezcla_id_seq') ";
			
			if(!$rst = $this->ConexionTransaccion($sql,'1')) return false;
			
			if(!$rst->EOF) $solucion = $rst->fields[0];
=======
>>>>>>> 1.2
      
      
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
				echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
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