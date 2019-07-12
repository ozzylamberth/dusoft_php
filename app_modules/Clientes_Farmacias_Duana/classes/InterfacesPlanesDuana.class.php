<?php
 /**
  * @package -SIIS
  * @version $Id$
  * @copyright
  * @author  Ronald Marin 
  */
 /**
  * Clase: InterfacesPlanesDuana  
  * @package -SIIS
  * @version $Revision: 1.2 $
  * @copyright 
  * @author 
  */
  
  class InterfacesPlanesDuana extends ConexionBD
 {
 
  /*********************************************************
    *Constructor de la clase
    **********************************************************/
    function InterfacesPlanesDuana(){}
 
 
  /**********************************************************
     *funcion obtener permisos / listar empresas - Duana
     *@param $usuario - id del usuario de la sesion
     *@return array $datosAF
    **********************************************************/
    function ObtenerPermisos($usuario)
	{
	 $sql  = " SELECT UPF.empresa_id, ";
	 $sql .= "  E.razon_social, ";
	 $sql .= "  UPF.usuario_id ";
	 $sql .= " FROM userpermisos_afiliacion_clientes_farmacia UPF, ";
	 $sql .= "  empresas E ";
     $sql .= " WHERE UPF.usuario_id = ".$usuario." ";
     $sql .= " AND UPF.empresa_id = E.empresa_id ";

     if(!$rst = $this->ConexionBaseDatos($sql))
		return false;
	
	$datosAF = array();
	
	while(!$rst->EOF)
     {
	  $datosAF[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }
	 $rst->Close();
	 
	 return $datosAF;
	 
	}
	
    /**********************************************************
	 *Obtener tipos de identificacion de clientes
          *@return array $datos
         **********************************************************/
	
	function ObtenerTiposIdentificacion()
    {
        $sql  = "SELECT tipo_id_paciente, ";
        $sql .= "       descripcion ";
        $sql .= "FROM   tipos_id_pacientes ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();

        while (!$rst->EOF)
        {
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
  
	/****************************************************************
	* Obtener datos de un cliente ingresado anteriormente
	*@param $datos - id del usuario de la sesion
         *@return array $datosaf
	****************************************************************/
	function ObtenerDatosClientes($datos)
    {
      $sql  = "SELECT IPF.afiliado_tipo_id, ";
      $sql .= "       IPF.afiliado_id, ";
      $sql .= "       IPF.primer_apellido, ";
      $sql .= "       IPF.segundo_apellido, ";
      $sql .= "       IPF.primer_nombre, ";
      $sql .= "       IPF.segundo_nombre, ";
      $sql .= "       TO_CHAR(IPF.fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
      $sql .= "       IPF.sexo_id, ";
      $sql .= "       IPF.tipo_pais_id, ";
      $sql .= "       IPF.tipo_dpto_id, ";
      $sql .= "       IPF.tipo_mpio_id, ";
      $sql .= "       IPF.zona_residencia, ";
      $sql .= "       IPF.direccion_residencia, ";
      $sql .= "       IPF.telefono_residencia, ";
      $sql .= "       IPF.telefono_movil, ";
      $sql .= "       IPF.tipo_estrato_id, ";
      $sql .= "       IPF.tipo_estado_civil_id, ";
      $sql .= "       IPF.tipo_afiliado_id ";
      $sql .= "FROM   interfaces_planes.".$datos['tipo_plan']." IPF ";               
      $sql .= "WHERE  IPF.afiliado_tipo_id = '".$datos['tipo_id_paciente']."' ";                                       
      $sql .= "AND    IPF.afiliado_id = '".$datos['documento']."' ";									
          
      $sql .= "GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18 ";
	  
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datosaf = array();
      while(!$rst->EOF)
      {
        $datosaf = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datosaf;
    }
  
    /****************************************************************
	* Obtener los nombres de los planes - interfaces_planes
	****************************************************************/
    function ObtenerPlanes()
	{
	 $sql  = "SELECT EP.plan_id, ";
	 $sql .= " EP.nombre_tbl, EP.descripcion ";
	 $sql .= "FROM estructura_planes EP ";
	 $sql .= "ORDER BY EP.descripcion ";
	 
	 if(!$rst = $this->ConexionBaseDatos($sql)) return false;
	 
	 $datosP = array();
	 while(!$rst->EOF)
	 {
	  $datosP[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }
	  $rst->Close();
	  return $datosP;
	}
  

   /*****************************************************************
       * Funcion para consultar los departamentos de colombia
       * @param string $psis_id - obtenido a traves de variable de configuracion de aplicacion
       * @return array $datos
       ******************************************************************/
    function ObtenerDepartamentos($psis_id)
    {
       $sql = "SELECT *
               FROM   tipo_dptos AS a
               WHERE  a.tipo_pais_id  ='".$psis_id."' ";
       $sql .="ORDER BY a.departamento ";
        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while(!$rst->EOF)
        {
            $datos[] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }    
    
    /************************************************************
	 * Funcion obtener datos de municipios
          *@param $filtros array de datos del cliente
	 *@return array $datos
         ************************************************************/
    function ObtenerMunicipios($filtros)
    {

        $sql="SELECT *
                FROM  tipo_mpios AS a
                WHERE  a.tipo_pais_id  ='".$filtros['tipo_pais_id']."'
                AND    a.tipo_dpto_id  ='".$filtros['dpto']."' ";


        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while(!$rst->EOF)
        {
            $datos[] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
  
    /************************************************************
          * Funcion obtener genero
	 * @return array $datos
         ************************************************************/
    function ObtenerGenero()
    {

        $sql = "SELECT ts.sexo_id, 
                     ts.descripcion
              FROM  tipo_sexo AS ts ";
        $sql.="WHERE ts.sexo_id <> '0' ";

        $datos = array();
        if(!$rst = $this->ConexionBaseDatos($sql))
            return false;

        while(!$rst->EOF)
        {
            $datos[] =  $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
  
    /**********************************************************
	*Funcion obtener zonas residencia
	 *@return array $datos
         **********************************************************/
	
	function ObtenerZona()
    {
        $sql  = "SELECT z.zona_residencia, ";
        $sql .= "       z.descripcion ";
        $sql .= "FROM   zonas_residencia z ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();

        while (!$rst->EOF)
        {
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }
  
    /**********************************************************
	*Funcion obtener estratos sociales
	 *@return array $datos
         **********************************************************/
	
	function ObtenerEstrato()
    {
        $sql  = "SELECT e.tipo_estrato_id, ";
        $sql .= "       e.descripcion ";
        $sql .= "FROM   tipos_estratos e
				 ORDER BY e.descripcion ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();

        while (!$rst->EOF)
        {
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }  
  
    /**********************************************************
	*Funcion obtener estado civil
	 *@return array $datos
         **********************************************************/
	
	function ObtenerEstadoCivil()
    {
        $sql  = "SELECT ec.tipo_estado_civil_id, ";
        $sql .= "       ec.descripcion ";
        $sql .= "FROM   tipo_estado_civil ec
				 ORDER BY ec.descripcion ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();

        while (!$rst->EOF)
        {
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    } 

    /**********************************************************
	*Funcion obtener tipo de afiliado
	 *@return array $datos
         **********************************************************/
	
	function ObtenerTipoAfiliado()
    {
        $sql  = "SELECT ta.tipo_afiliado_id, ";
        $sql .= "       ta.tipo_afiliado_nombre ";
        $sql .= "FROM   tipos_afiliado ta
				 ORDER BY ta.tipo_afiliado_nombre ";

        if(!$rst = $this->ConexionBaseDatos($sql)) return false;

        $datos = array();

        while (!$rst->EOF)
        {
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    } 

    
    /*****************************************************************
         * Funcion para ingresar los datos de un nuevo Cliente en BD
         *@param $datos array de datos del cliente
         *@return boolean
        ******************************************************************/    
  function IngresarDatosAfiliacion($datos)
  {
   // Organizar fecha formato postgres yyyy-mm-dd
   $fec_nac = $this->DividirFecha($datos['fecha_naci'],"-");
   //$this->debug=true;
   
   $sql  = "INSERT INTO interfaces_planes.".$datos['plan']." (";  
   $sql .= "            afiliado_tipo_id, ";
   $sql .= "            afiliado_id, ";
   $sql .= "            primer_apellido, ";  
   $sql .= "            segundo_apellido, ";
   $sql .= "            primer_nombre, ";
   $sql .= "            segundo_nombre, ";
   $sql .= "            fecha_nacimiento, ";
   $sql .= "            sexo_id, ";
   $sql .= "            tipo_pais_id, ";
   $sql .= "            tipo_dpto_id, ";
   $sql .= "            tipo_mpio_id, ";
   $sql .= "            zona_residencia, ";
   $sql .= "            direccion_residencia, ";
   $sql .= "            telefono_residencia, ";
   $sql .= "            telefono_movil, ";
   $sql .= "            tipo_estrato_id, ";   
   $sql .= "            tipo_estado_civil_id, ";
   
   if($datos['plan'] == 'hosp_ablanque')
   {
    $sql .= "           planes_convenio, ";
   }
   
   $sql .= "            rango, ";
   $sql .= "            tipo_afiliado_id ) ";   
   $sql .= "VALUES      ("; 
   $sql .= "            '".$datos['tipo_id']."', ";   
   $sql .= "            '".$datos['documento']."', ";   
   $sql .= "            '".strtoupper(str_replace("'","''",$datos['pmer_apellido']))."', "; 
   if($datos['sgdo_apellido'] == "")
   {
    $sql .= "           'N/A', ";
   }
   else
   {
    $sql .= "            '".strtoupper(str_replace("'","''",$datos['sgdo_apellido']))."', ";  
   } 
   $sql .= "            '".strtoupper(str_replace("'","''",$datos['primer_nombre']))."', ";
   if($datos['sgdo_nombre'] == "")
   {
    $sql .= "           'N/A', ";
   }   
   else
   {
    $sql .= "            '".strtoupper(str_replace("'","''",$datos['sgdo_nombre']))."', ";
   }
   $sql .= "            '".$fec_nac."', "; 
   $sql .= "            '".$datos['sexo']."', ";    
   $sql .= "            '".$datos['tipo_pais_id']."', ";    
   $sql .= "            '".$datos['dpto']."', ";    
   $sql .= "            '".$datos['municipio']."', ";    
   $sql .= "            '".$datos['zona']."', ";    
   $sql .= "            '".$datos['direccion']."', ";    
   $sql .= "            '".$datos['telefono']."', "; 
   if($datos['movil'] == "")
   {   
    $sql .= "           '000000', ";
   }
   else
   {
    $sql .= "            '".$datos['movil']."', ";    
   }
   $sql .= "            '".trim($datos['estrato'])."', ";    
   $sql .= "            '".$datos['estado_civil']."', ";   
   
   if($datos['plan'] == 'hosp_ablanque')
   {
    $sql .= "           '".$datos['subplan']."', ";
   }
   
   $sql .= "            '".$datos['rango']."', ";    
   $sql .= "            '".$datos['tipo_afiliado']."' ";
   $sql .= "            ) ";    
   
   
   if(!$rst = $this->ConexionBaseDatos($sql)) return false;
   
  
   return true;
  
  }
  
     /****************************************************************
	* Obtener los subplanes que atiende un convenio
	****************************************************************/
    function GetSubplan($convenio)
	{
	 $sql  = "SELECT IFS.subplan ";
	 $sql .= "FROM interfaces_planes.subplanes_convenios IFS, public.estructura_planes EP ";
	 $sql .= "WHERE IFS.convenio = EP.nombre_tbl ";
	 $sql .= "AND IFS.convenio ='".trim($convenio)."' ";
	 $sql .= "ORDER BY IFS.subplan ";
	 
	 if(!$rst = $this->ConexionBaseDatos($sql)) 
	    return false;
	 
	 $datosP = array();
	 while(!$rst->EOF)
	 {
	  $datosP[ ] = $rst->GetRowAssoc($ToUpper = false);
	  $rst->MoveNext();
	 }
	  $rst->Close();
	  return $datosP;
	} 
  
  
 }
?>