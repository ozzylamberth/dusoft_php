<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ReclamacionServiciosSQL.class.php,v 1.1 2008/01/09 11:23:08 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
  /**
  * Clase : Reesolucion3047
  * Contiene metodos para la generacion de archivos xml de la reolucion
  *  
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F. Manrique
  */
  class Resolucion3047 extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function Resolucion3047(){}
    /**
    * Funcion donde se toman los datos de la atencion de urgencias y se
    * convierten al vector para generar el archivo xml
    *
    * @param array $datos Arreglo de datos de la resolucion
    *
    * @return array
    */
    function ParseDatosAutorizacionServicios($datos)
    {
      $campos["General"]["Numero"] = $datos['numero_solicitud'];
      $campos["General"]["Fecha"] = $datos['fecha'];
      $campos["General"]["Hora"] = $datos['hora'].":00";
      $campos["General"]["Prestador"] = substr($datos['razon_social'],0,250);
      $campos["General"]["TipoIdPrestador"] = $datos['tipo_id_tercero'];
      $campos["General"]["IDPrestador"] = $datos['id_emp'];
      $campos["General"]["DigVerif"] = $datos['digito_verificacion'];
      $campos["General"]["CodPrestador"] = $datos['codigo_sgsss'];
      $campos["General"]["DireccionPrestador"] = substr($datos['direccion_emp'],0,80);
      $campos["General"]["IndicTelefPrestador"] = $datos['indicativo_emp'];
      $campos["General"]["TelefonoPrestador"] = $datos['telefonos_emp'];
      $campos["General"]["DepartamentoPrestador"] = $datos['tipo_dpto_id_emp'];
      $campos["General"]["MunicipioPrestador"] = $datos['tipo_mpio_id_emp'];
      
      $campos["Pagador"]["EntidadResponsable"] = substr($datos['nombre_tercero'],0,150);
      $campos["Pagador"]["CodigoEntidad"] = $datos['codigo_sgsss_p'];

      $campos["Paciente"]["Nombre"]["PrimerApellido"] = $datos['primer_apellido_u'];
    	$campos["Paciente"]["Nombre"]["SegundoApellido"] = $datos['segundo_apellido_u'];
    	$campos["Paciente"]["Nombre"]["PrimerNombre"] = $datos['primer_nombre_u'];
    	$campos["Paciente"]["Nombre"]["SegundoNombre"] = $datos['segundo_nombre_u'];
    	$campos["Paciente"]["Identificacion"]["TipoIdentificacion"] = $datos['tipo_id_paciente'];
    	$campos["Paciente"]["Identificacion"]["NumeroIdentificacion"] = $datos['paciente_id'];
    	$campos["Paciente"]["DatosPersonales"]["FechaNacimiento" ] = $datos['fecha_nacimiento_u'];
      $campos["Paciente"]["DatosPersonales"]["Ubicacion"]["DireccionResidenciaHabitual"] = substr($datos['residencia_direccion_u'],0,80);
      $campos["Paciente"]["DatosPersonales"]["Ubicacion"]["TelefonoFijo"] = substr($datos['residencia_telefono_u'],0,7);
      $campos["Paciente"]["DatosPersonales"]["Ubicacion"]["Departamento"] = $datos['tipo_dpto_id_u'];
    	$campos["Paciente"]["DatosPersonales"]["Ubicacion"]["Ciudad"] = $datos['tipo_mpio_id_u'];
    	$campos["Paciente"]["DatosPersonales"]["TelefonoCelular"] = $datos['celular_telefono'];
    	$campos["Paciente"]["DatosPersonales"]["CorreoElectronico"] = $datos['email'];
      
      $campos["CoberturaSalud" ] = $datos['regimen_res_3047'];      
      $campos["OrigenAtencion" ] = $datos['origen_atencion'];      
      $campos["UbicacionPaciente" ] = $datos['cargos'][0]['servicio'];      
      $campos["ServicioHospitalizacion"] = $datos['cargos'][0]['descripcion'];
      $campos["CamaHospitalizacion"] = $datos['cama'];
      $campos["GuiaManejoIntegral"] = "";
      $i = 1;
      foreach($datos['cargos'] as $key => $dtl)
      {
        $campos["ServiciosSolicitados"]["TipoServicio".$i] = $datos['tipo_servicio'];
        $campos["ServiciosSolicitados"]["CodigoCUPS".$i] = $dtl['cargo'];
        $campos["ServiciosSolicitados"]["Cantidad".$i ] = $dtl['cantidad'];
        $campos["ServiciosSolicitados"]["Descripcion".$i] = $dtl['desc_cargo'];
        $i++;
      }
      for($i;$i <= 12; $i++)
      {
        $campos["ServiciosSolicitados"]["TipoServicio".$i] = "";
        $campos["ServiciosSolicitados"]["CodigoCUPS".$i] = "";
        $campos["ServiciosSolicitados"]["Cantidad".$i ] = "";
        $campos["ServiciosSolicitados"]["Descripcion".$i] = "";
      }
      $campos["JustificacionClinica"] = "" ;
      
      $campos["ImpresionDiagnostica"]["CodigoCIE10Principal"] = $datos['diagnosticos'][0]['diagnostico_id'];
      $campos["ImpresionDiagnostica"]["DescripcionPrincipal"] = $datos['diagnosticos'][0]['diagnostico_nombre'];
      $campos["ImpresionDiagnostica"]["CodigoCIE101"] = $datos['diagnosticos'][1]['diagnostico_id'];
      $campos["ImpresionDiagnostica"]["Descripcion1"] = $datos['diagnosticos'][1]['diagnostico_nombre'];
      $campos["ImpresionDiagnostica"]["CodigoCIE102"] = $datos['diagnosticos'][2]['diagnostico_id'];
      $campos["ImpresionDiagnostica"]["Descripcion2"] = $datos['diagnosticos'][2]['diagnostico_nombre'];
      
      $campos["ProfesionalSolicitante"]["Nombre"] = substr($datos['nomb_prof'],0,60);
      $campos["ProfesionalSolicitante"]["Cargo"] = substr($datos['desc_prof'],0,30);
      $campos["ProfesionalSolicitante"]["IndicaTel"] = $datos['indicativo_prof'];
      $campos["ProfesionalSolicitante"]["Telefono"] = substr(trim($datos['tel_prof']),0,10);
      $campos["ProfesionalSolicitante"]["ExtTele"] = substr(trim($datos['extencion_prof']),0,6);
      $campos["ProfesionalSolicitante"]["TelefonoCelular"] = substr(trim($datos['celular_prof']),0,10);
      return $campos;
    }
    /**
    * Funcion donde se toman los datos de la atencion de urgencias y se
    * convierten al vector para generar el archivo xml
    *
    * @param array $datos Arreglo de datos de la resolucion
    *
    * @return array
    */
    function ParseDatosInformeUrgencias($datos)
    {
      $campos['General']["Numero"] = $datos['num_atencion'];
      $campos['General']["Fecha"] = $datos['fecha'] ;
      $campos['General']["Hora"] = $datos['hora'].":00";
      $campos['General']["Prestador"] = substr($datos['razon_social'],0,250) ;
      $campos['General']["TipoIdPrestador"] = $datos['tipo_id_tercero'] ;
      $campos['General']["IdPrestador"]  = $datos['id_emp'];
      $campos['General']["DigVerif"]  = $datos['digito_verificacion'];
      $campos['General']["CodPrestador"]  = $datos['codigo_sgsss'] ;
      $campos['General']["DireccionPrestador"] = substr($datos['direccion_emp'],0,80);
      $campos['General']["IndicTelefPrestador"] = $datos['indicativo_emp'] ;
      $campos['General']["TelefonoPrestador"] = $datos['telefonos_emp'] ;
      $campos['General']["DepartamentoPrestador"] = $datos['tipo_dpto_id_emp'] ;
      $campos['General']["MunicipioPrestador"] = $datos['tipo_mpio_id_emp'];
            
      $campos["Pagador"]["EntidadResponsable"] = substr($datos['nombre_tercero'],0,150);
   		$campos["Pagador"]["CodigoEntidad"] = $datos['codigo_sgsss_p'];
            
      $campos["Paciente"]["Nombre"]["PrimerApellido"] = $datos['primer_apellido_u'];
			$campos["Paciente"]["Nombre"]["SegundoApellido"]  = $datos['segundo_apellido_u'];
			$campos["Paciente"]["Nombre"]["PrimerNombre"]  = $datos['primer_nombre_u'];
			$campos["Paciente"]["Nombre"]["SegundoNombre"]  = $datos['segundo_nombre_u'];
			
      $campos["Paciente"]["Identificacion"]["TipoIdentificacion"] = $datos['tipo_id_paciente'];
			$campos["Paciente"]["Identificacion"]["NumeroIdentificacion"] = $datos['paciente_id'];
      
			$campos["Paciente"]["DatosPersonales"]["FechaNacimiento"] = $datos['fecha_nacimiento_u'];
			$campos["Paciente"]["DatosPersonales"]["Ubicacion"]["DireccionResidencia"] = substr($datos['residencia_direccion_u'],0,80);
			$campos["Paciente"]["DatosPersonales"]["Ubicacion"]["TelefonoFijo"] = substr($datos['residencia_telefono_u'],0,7);
			$campos["Paciente"]["DatosPersonales"]["Ubicacion"]["Departamento"] = $datos['tipo_dpto_id_u'];
			$campos["Paciente"]["DatosPersonales"]["Ubicacion"]["Ciudad"] = $datos['tipo_mpio_id_u'];
      
      $f = explode(" ",$datos['fecha_ingreso']);
      
      $campos["CoberturaSalud"] = $datos['regimen_res_3047'];
      $campos["OrigenAtencion"] = $datos['origen_atencion'];
      $campos["FechaIngreso"] = $f[0];
      $campos["HoraIngreso"] = substr($f[1],0,5).":00";
      $campos["PacienteRemitido"] = (($datos['paciente_remitido_id'])? "true":"false");
      $campos["PrestadorRemite"]["NombrePrestador"] = substr($datos['nomb_rem'],0,150);
      $campos["PrestadorRemite"]["DepartamentoPR"] = $datos['tipo_dpto_id_pr'];
      $campos["PrestadorRemite"]["MuncipioPR"] = $datos['tipo_mpio_id_pr'];

      $campos["MotivoConsulta"] = substr($datos['desc_motivo'],0,200);
      $campos["ImpresionDiagnostica"]["CodigoCIE10Principal"] = $datos['diagnosticos'][0]['diagnostico_id'];
      $campos["ImpresionDiagnostica"]["DescripcionPrincipal"] = $datos['diagnosticos'][0]['diagnostico_nombre'];
      $campos["ImpresionDiagnostica"]["CodigoCIE101"] = $datos['diagnosticos'][1]['diagnostico_id'];
      $campos["ImpresionDiagnostica"]["Descripcion1"] = $datos['diagnosticos'][1]['diagnostico_nombre'];
      $campos["ImpresionDiagnostica"]["CodigoCIE102"] = $datos['diagnosticos'][2]['diagnostico_id'];
      $campos["ImpresionDiagnostica"]["Descripcion2"] = $datos['diagnosticos'][2]['diagnostico_nombre'];
      $campos["ImpresionDiagnostica"]["CodigoCIE103"] = $datos['diagnosticos'][3]['diagnostico_id'];
      $campos["ImpresionDiagnostica"]["Descripcion3"] = $datos['diagnosticos'][3]['diagnostico_nombre'];
      $campos["ImpresionDiagnostica"]["CodigoCIE104"] = $datos['diagnosticos'][4]['diagnostico_id'];
      $campos["ImpresionDiagnostica"]["Descripcion4"] = $datos['diagnosticos'][4]['diagnostico_nombre'];
      $campos["DestinoPaciente"] = $datos['destino_paciente_id'];
      
      $campos["Informante"]["Nombre"] = substr($datos['nombre_us'],0,60);
			$campos["Informante"]["Cargo"] = substr($datos['descripcion_us'],0,30);
      $campos["Informante"]["IndicaTel"] = $datos['indicativo_us'];
     	$campos["Informante"]["Telefono"] = $datos['telefono_us'];
      $campos["Informante"]["ExtTele"] = $datos['extension_us'];
			$campos["Informante"]["CelularInstitucional"] = $datos['tel_celular_us'];
      return $campos;
    }
    /**
    * Funcion donde se toman los datos de la atencion de urgencias y se
    * convierten al vector para generar el archivo xml
    *
    * @param array $datos Arreglo de datos de la resolucion
    *
    * @return array
    */
    function ParseDatosInformePresuntaInconsistencia($datos)
    {
      $campos["General"]["Numero"] = $datos['num_informe'];
      $campos["General"]["Fecha"] = $datos['fecha'];
      $campos["General"]["Hora"] = $datos['hora'].":00";
      $campos["General"]["Prestador"] = substr($datos['razon_social'],0,250);
      $campos["General"]["TipoIdPrestador"] = $datos['tipo_id_tercero'];
      $campos["General"]["IDPrestador"]= $datos['id_emp'];;
      $campos["General"]["DigVerif"] = $datos['digito_verificacion'];
      $campos["General"]["CodPrestador"] = $datos['codigo_sgsss'];
      $campos["General"]["DireccionPrestador"] = substr($datos['direccion_emp'],0,80);
      $campos["General"]["IndicTelefPrestador"] = $datos['indicativo_emp'];
      $campos["General"]["TelefonoPrestador"] = $datos['telefonos_emp'];
      $campos["General"]["DepartamentoPrestador"] = $datos['tipo_dpto_id_emp'];
      $campos["General"]["MunicipioPrestador"] = $datos['tipo_mpio_id_emp'];
        			
      $campos["Pagador"]["EntidadResponsable"] = $datos['nombre_tercero'];
      $campos["Pagador"]["CodigoEntidad"] = $datos['codigo_sgsss_p'];
      
      $campos["Paciente"]["Nombre"]["PrimerApellido"] = substr($datos['primer_apellido_u'],0,20);
  		$campos["Paciente"]["Nombre"]["SegundoApellido"] = substr($datos['segundo_apellido_u'],0,30);
  		$campos["Paciente"]["Nombre"]["PrimerNombre"] = substr($datos['primer_nombre_u'],0,20);
  		$campos["Paciente"]["Nombre"]["SegundoNombre"]= substr($datos['segundo_nombre_u'],0,30);
        
  		$campos["Paciente"]["Identificacion"]["TipoIdentificacion"] = $datos['tipo_id_paciente'];
  		$campos["Paciente"]["Identificacion"]["NumeroIdentificacion"] = $datos['paciente_id'];
      
  		$campos["Paciente"]["DatosPersonales"]["FechaNacimiento"] = $datos['fecha_nacimiento_u'];
  		$campos["Paciente"]["DatosPersonales"]["Ubicacion"]["DireccionResidenciaHabitual"] = substr($datos['residencia_direccion_u'],0,80);
  		$campos["Paciente"]["DatosPersonales"]["Ubicacion"]["TelefonoFijo"] = substr(trim($datos['residencia_telefono_u']),0,7);
      $campos["Paciente"]["DatosPersonales"]["Ubicacion"]["Departamento"] = $datos['tipo_dpto_id_u'];
  		$campos["Paciente"]["DatosPersonales"]["Ubicacion"]["Municipio"] = $datos['municipio_u'];
        
      $campos["CoberturaSalud"] = $datos['regimen_res_3047'];

      if($datos['sw_primer_apellido'] == '1')
      {
        $campos[0]["Inconsistencias"]["VariableInconsistencia"] = "PrimerApellido";
        $campos[0]["Inconsistencias"]["DatoErrado"] = substr($datos['primer_apellido_e'],0,20);
        $campos[0]["Inconsistencias"]["DatoDocumento"] = substr($datos['primer_apellido_d'],0,20);
      }
      if($datos['sw_segundo_apellido'] == '1')
      {
        $campos[1]["Inconsistencias"]["VariableInconsistencia"] = "SegundoApellido";
        $campos[1]["Inconsistencias"]["DatoErrado"] = substr($datos['segundo_apellido_e'],0,30);
        $campos[1]["Inconsistencias"]["DatoDocumento"] = substr($datos['segundo_apellido_d'],0,30);
      }
      
      if($datos['sw_primer_nombre'] == '1')
      {
        $campos[2]["Inconsistencias"]["VariableInconsistencia"] = "PrimerNombre";
        $campos[2]["Inconsistencias"]["DatoErrado"] = substr($datos['primer_nombre_e'],0,20);
        $campos[2]["Inconsistencias"]["DatoDocumento"] = substr($datos['primer_nombre_d'],0,20);
      }
      if($datos['sw_segundo_nombre'] == '1')
      {
        $campos[3]["Inconsistencias"]["VariableInconsistencia"] = "SegundoNombre";
        $campos[3]["Inconsistencias"]["DatoErrado"] = substr($datos['segundo_nombre_e'],0,30);
        $campos[3]["Inconsistencias"]["DatoDocumento"] = substr($datos['segundo_nombre_d'],0,30);
      }
      if($datos['sw_tipo_id_paciente'] == '1')
      {
        $campos[4]["Inconsistencias"]["VariableInconsistencia"] = "TipoIdentificacion";
        $campos[4]["Inconsistencias"]["DatoErrado"] = $datos['tipo_id_paciente_e'];
        $campos[4]["Inconsistencias"]["DatoDocumento"] = $datos['tipo_id_paciente_d'];
      }
      if($datos['sw_paciente_id'] == '1')
      {
        $campos[5]["Inconsistencias"]["VariableInconsistencia"] = "NumeroIdentificacion";
        $campos[5]["Inconsistencias"]["DatoErrado"] = $datos['paciente_id_e'];
        $campos[5]["Inconsistencias"]["DatoDocumento"] = $datos['paciente_id_d'];
      }
      if($datos['sw_fecha_nacimiento'] == '1')
      {
        $campos[6]["Inconsistencias"]["VariableInconsistencia"] = "FechaNacimiento";
        $campos[6]["Inconsistencias"]["DatoErrado"] = $datos['fecha_nacimiento_e'];
        $campos[6]["Inconsistencias"]["DatoDocumento"] = $datos['fecha_nacimiento_d'];
      }

      $campos["Observaciones"] = substr($datos['observaciones'],0,200);
      $campos["Reportante"]["Nombre"] = substr($datos['nombre_us'],0,60);
  		$campos["Reportante"]["Cargo"] = substr($datos['descripcion_us'],0,30);
      $campos["Reportante"]["IndicaTel"] = $datos['indicativo_us'];
      $campos["Reportante"]["Telefono"] = $datos['telefono_us'];
      $campos["Reportante"]["ExtTele"] = $datos['extension_us'];
  		$campos["Reportante"]["CelularInstitucional"] = $datos['tel_celular_us'];
		
      return $campos;
    }
    /**
    * Funcion donde se obtiene la informacion de la base de datos de la atencion inicial
    * de urgencias.
    *
    * @param array $filtro Arreglo con los filtros para la busqueda del registro
    *
    * @return mixed
    */
    function ObtenerDatosInformeUrgencias($filtro)
    {
      $sql  = "SELECT num_atencion ,";
      $sql .= "     	fecha ,";
      $sql .= "	      hora ,";
      $sql .= "	      usuario_id ,";
      $sql .= "	      ingreso,";
      $sql .= " 	    plan_id,";
      $sql .= " 	    empresa_id,";
      $sql .= " 	    paciente_id,";
      $sql .= " 	    tipo_id_paciente ";
      $sql .= "FROM   atencion_inicial_urgencias ";
      $sql .= "WHERE  fecha = '".$filtro['fecha']."' ";
      $sql .= "AND    num_atencion = '".$filtro['formulario_no']."' ";
      $sql .= "AND 	  paciente_id = '".$filtro['paciente_id']."' ";
      $sql .= "AND    tipo_id_paciente = '".$filtro['tipo_id_paciente']."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }    
    /**
    * Funcion donde se obtiene la informacion de la base de datos del registro de 
    * inconsistencias
    *
    * @param array $filtro Arreglo con los filtros para la busqueda del registro
    *
    * @return mixed
    */
    function ObtenerDatosInformePresuntaInconsistencia($filtro)
    {
      $sql  = "SELECT ID.num_informe, ";
      $sql .= "       ID.fecha, ";
      $sql .= "       ID.hora, ";
      $sql .= "       ID.inconsistencia_id, ";
      $sql .= "       ID.sw_primer_apellido, ";
      $sql .= "       ID.sw_segundo_apellido, ";
      $sql .= "       ID.sw_primer_nombre, ";
      $sql .= "       ID.sw_segundo_nombre, ";
      $sql .= "       ID.sw_tipo_id_paciente, ";
      $sql .= "       ID.sw_paciente_id, ";
      $sql .= "       ID.sw_fecha_nacimiento, ";
      $sql .= "       ID.primer_apellido AS primer_apellido_d, ";
      $sql .= "       ID.segundo_apellido AS segundo_apellido_d, ";
      $sql .= "       ID.primer_nombre AS primer_nombre_d, ";
      $sql .= "       ID.segundo_nombre AS segundo_nombre_d, ";
      $sql .= "       ID.tipo_id_paciente AS tipo_id_paciente_d, ";
      $sql .= "       ID.paciente_id AS paciente_id_d, ";
      $sql .= "       ID.fecha_nacimiento AS fecha_nacimiento_d, ";
      $sql .= "       ID.tipo_id_paciente_u AS tipo_id_paciente_e, ";
      $sql .= "       ID.paciente_id_u AS paciente_id_e, "; 
      $sql .= "       ID.primer_apellido_u AS primer_apellido_e, ";
      $sql .= "       ID.segundo_apellido_u AS segundo_apellido_e, "; 
      $sql .= "       ID.primer_nombre_u AS primer_nombre_e, ";
      $sql .= "       ID.segundo_nombre_u AS segundo_nombre_e, ";
      $sql .= "       ID.fecha_nacimiento_u AS fecha_nacimiento_e, ";
      $sql .= "       ID.observaciones, ";
      $sql .= "       ID.usuario_id, ";
      $sql .= "       ID.ingreso, ";
      $sql .= "       ID.plan_id, ";
      $sql .= "       ID.empresa_id, ";
      $sql .= "       TP.descripcion AS descripcion_documento ";
      $sql .= "FROM   inconsistencias_pagador ID ";
      $sql .= "       LEFT JOIN tipos_id_pacientes TP ";
      $sql .= "       ON (TP.tipo_id_paciente = ID.tipo_id_paciente) ";
      $sql .= "WHERE  ID.fecha = '".$filtro['fecha']."' ";
      $sql .= "AND    ID.num_informe = ".$filtro['formulario_no']." ";
      $sql .= "AND 	  ID.ingreso = ".$filtro['ingreso']." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion donde se obtiene la informacion de la base de datos del registro de 
    * la autorizacion de servicios
    *
    * @param array $filtro Arreglo con los filtros para la busqueda del registro
    *
    * @return mixed
    */
    function ObtenerDatosSolicitudAutorizacionServicios($filtro)
    {
      //$this->debug = true;
      $sql  = "SELECT numero_solicitud, ";
      $sql .= "       fecha, ";
      $sql .= "       hora, ";
      $sql .= "       usuario_id, ";
      $sql .= "       estado, ";
      $sql .= "       solicitud_manual, ";
      $sql .= "       tipo_id_paciente, ";
      $sql .= "       paciente_id, ";
      $sql .= "       plan_id, ";
      $sql .= "       ingreso, ";
      $sql .= "       profesional_id, ";
      $sql .= "       prioridad, ";
      $sql .= "       tipo_servicio, ";
      $sql .= "       origen_atencion_id AS origen_atencion ";
      $sql .= "FROM   solicitud_autorizacion_serv ";
      $sql .= "WHERE  fecha = '".$filtro['fecha']."' ";
      $sql .= "AND    numero_solicitud = '".$filtro['formulario_no']."' ";
      $sql .= "AND 	  paciente_id = '".$filtro['paciente_id']."' ";
      $sql .= "AND    tipo_id_paciente = '".$filtro['tipo_id_paciente']."' ";

      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      $datos = array();
      if(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();

      $sql  = "SELECT SC.cargo, ";
      $sql .= "       SC.numero_solicitud, ";
      $sql .= "       SC.fecha, ";
      $sql .= "       SC.servicio, ";
      $sql .= "       SC.ubicacion, ";
      $sql .= "       SC.cantidad, ";
      $sql .= "       SC.hc_os_solicitud_id, ";
      $sql .= "       CU.descripcion AS desc_cargo, ";
      $sql .= "       SE.descripcion ";
      $sql .= "FROM   solicitud_autorizacion_cargos SC, ";
      $sql .= "       cups CU, ";
      $sql .= "       servicios SE ";
      $sql .= "WHERE  SC.fecha = '".$filtro['fecha']."' ";
      $sql .= "AND    SC.numero_solicitud = '".$filtro['formulario_no']."' ";
      $sql .= "AND    SC.cargo = CU.cargo ";
      $sql .= "AND    SC.servicio = SE.servicio ";

      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos['cargos'][] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      
      $rst->Close();   
      return $datos;
    }
  }
?>