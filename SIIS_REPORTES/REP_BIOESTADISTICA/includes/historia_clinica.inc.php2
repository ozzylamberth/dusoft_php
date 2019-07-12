<?php

/**
 * $Id: historia_clinica.inc.php,v 1.5 2005/06/07 19:03:54 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

// historia_clinica.inc.php  23/02/2005

function GetDatosEvolucion($evolucion)
{
    if(empty($evolucion)) {
        return false;
    }

    static $evolucion_vars = array();
    
    if (isset($evolucion_vars[$evolucion])) {
        return $evolucion_vars[$evolucion];
    }
    
    
    list($dbconn) = GetDBconn();
    
    $query = "SELECT *
            FROM hc_evoluciones
            WHERE evolucion_id = $evolucion";
    
    
    GLOBAL $ADODB_FETCH_MODE;
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    
    $result = $dbconn->Execute($query);
    
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    
    if($dbconn->ErrorNo() != 0) {
        return false;
    }
    
    if ($result->EOF) {
        return false;
    }
    
    
    $vars = $result->FetchRow();
    $result->Close();
    
    $evolucion_vars[$evolucion]=$vars;
    return($vars);
}


function GetDatosIngreso($ingreso)
{
    if(empty($ingreso)) {
        return false;
    }
    
    static $ingreso_vars = array();
    
    if (isset($evolucion_vars[$ingreso])) {
        return $evolucion_vars[$ingreso];
    }
    
    
    list($dbconn) = GetDBconn();
    
    $query = "SELECT *
            FROM ingresos
            WHERE ingreso = $ingreso";
    
    
    GLOBAL $ADODB_FETCH_MODE;
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    
    $result = $dbconn->Execute($query);
    
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    
    if($dbconn->ErrorNo() != 0) {
        return false;
    }
    
    if ($result->EOF) {
        return false;
    }


    $vars = $result->FetchRow();
    $result->Close();
    
    $ingreso_vars[$ingreso]=$vars;
    return($vars);

}

function ErrorSubModulo($error,$mesgerror,$titulo)
{
    $salida='';
    $salida.=ThemeAbrirTablaSubModulo($titulo);
    $salida.="<tr><td class='titulo1_error'>$error</td></tr>";
    $salida.="<tr><td class='label'>$mesgerror</td></tr>";
    $salida.=ThemeCerrarTablaSubModulo();
    return $salida;
}

function IncluirSubModuloHC($submodulo)
{
  global $VISTA;
    if(empty($submodulo)){
        return false;
    }

    $fileName = "hc_modules/$submodulo/hc_$submodulo.php";

    if(!IncludeFile($fileName)){
        return "El archivo '$fileName' no existe.";
    }

    $fileName = "hc_modules/$submodulo/hc_$submodulo"."_$VISTA.php";

    if(!IncludeFile($fileName)){
        return "El archivo '$fileName' no existe.";
    }

    $className="$submodulo";

    if(!class_exists($className)){
        return "La clase '$className' no existe.";
    }

    $className="$submodulo"."_$VISTA";

    if(!class_exists($className)){
        return "La clase '$className' no existe.";
    }

    $SUBMODULO= new $className();

    return $SUBMODULO;
}

function GetTipoProfesional($usuario_id=0,$tipo_tercero_id='',$tercero_id='')
{
  static $TipoProfesionales=array();
  if (isset($TipoProfesionales[$usuario_id])) {
      return $TipoProfesionales[$usuario_id];
  }

  if(!empty($usuario_id)){

      static $TipoProfesionales=array();
      if (isset($TipoProfesionales[$usuario_id])) {
          return $TipoProfesionales[$usuario_id];
      }

   $query = "SELECT tipo_profesional
                FROM profesionales_usuarios a, profesionales b
                WHERE a.tipo_tercero_id=b.tipo_id_tercero and
                a.tercero_id=b.tercero_id and
                a.usuario_id=$usuario_id";

  }elseif(!empty($tipo_tercero_id) && !empty($tercero_id)){

      static $TipoProfesionalesTipo2=array();
      if (isset($TipoProfesionalesTipo2[$tipo_tercero_id][$tercero_id])) {
          return $TipoProfesionalesTipo2[$tipo_tercero_id][$tercero_id];
      }

      $query = "SELECT tipo_profesional
                FROM profesionales
                WHERE tipo_id_tercero='$tipo_tercero_id' AND
                tercero_id='$tercero_id'";
  }else{

      return false;
  }

  list($dbconn) = GetDBconn();
  $result = $dbconn->Execute($query);

  if($dbconn->ErrorNo() != 0) {
      return false;
  }

  if ($result->EOF) {
      return false;
  }

  list($tipo_profesional) = $result->FetchRow();
  $result->Close();
  return $tipo_profesional;

}

function GetEspecialidadesProfesional($tipo_tercero_id='',$tercero_id='')
{
    if(empty($tipo_tercero_id) || empty($tercero_id))
    {
        return false;
    }
    
    static $EspecialidadesProfesional=array();
    if (isset($EspecialidadesProfesional[$tipo_tercero_id][$tercero_id])) {
        return $EspecialidadesProfesional[$tipo_tercero_id][$tercero_id];
    }    
    
    list($dbconn) = GetDBconn();
    GLOBAL $ADODB_FETCH_MODE;
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        
    $query = "";    
    $result = $dbconn->Execute($query);        
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    
    if($dbconn->ErrorNo() != 0) {
        return false;
    }
    
    if($result->EOF) {
        return false;
    }
    
    while(!$especialidad = $result->FetchRow())
    {
        $EspecialidadesProfesional[$tipo_tercero_id][$tercero_id][]=$especialidad;
    }
    return $EspecialidadesProfesional[$tipo_tercero_id][$tercero_id];        
}

function GetDatosProfesional($usuariId,$tipo_tercero_id='',$tercero_id='')
{

    if(!empty($usuariId)){
    
        static $DatosProfesionales_UsuarioId=array();
        if (isset($DatosProfesionales_UsuarioId[$usuariId])) {
            return $DatosProfesionales_UsuarioId[$usuariId];
        }

        list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;    
        
           $query ="SELECT a.tipo_tercero_id, a.tercero_id, b.nombre_tercero, b.tipo_pais_id, b.tipo_dpto_id, b.tipo_mpio_id, b.direccion, b.telefono, b.celular, b.busca_persona, b.email, b.fax, c.tipo_profesional, d.descripcion as tipo_profesional_descripcion,
                c.tarjeta_profesional, c.estado, c.sexo_id, c.universidad, c.observacion, c.sw_registro_defuncion, c.registro_salud_departamental
                
                FROM profesionales_usuarios a, terceros b, profesionales c, tipos_profesionales d
                
                WHERE a.usuario_id = $usuariId
                AND b.tipo_id_tercero = a.tipo_tercero_id 
                AND b.tercero_id = a.tercero_id 
                AND c.tipo_id_tercero = a.tipo_tercero_id
                AND c.tercero_id = a.tercero_id
                AND d.tipo_profesional = c.tipo_profesional";
                
        $result = $dbconn->Execute($query);        
           $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        
        if($dbconn->ErrorNo() != 0) {
            return false;
        }
        
        if($result->EOF) {
            return false;
        }
        
        $DatosProfesionales_UsuarioId[$usuariId] = $result->FetchRow();        
        $result->Close();

        return $DatosProfesionales_UsuarioId[$usuariId];
    }
    elseif(!empty($tipo_tercero_id) && !empty($tercero_id))
    {
    
        static $DatosProfesionales_TerceroId=array();
        if (isset($DatosProfesionales_TerceroId[$tipo_tercero_id][$tercero_id])) {
            return $DatosProfesionales_TerceroId[$tipo_tercero_id][$tercero_id];
        }

        list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;    
        
           $query ="SELECT  b.tipo_id_tercero, b.tercero_id, b.nombre_tercero, b.tipo_pais_id, b.tipo_dpto_id, b.tipo_mpio_id, b.direccion, b.telefono, b.celular, b.busca_persona, b.email, b.fax, c.tipo_profesional, d.descripcion as tipo_profesional_descripcion,
                c.tarjeta_profesional, c.estado, c.sexo_id, c.universidad, c.observacion, c.sw_registro_defuncion, c.registro_salud_departamental
                
                FROM terceros b, profesionales c, tipos_profesionales d
                
                WHERE b.tipo_id_tercero = '".$tipo_tercero_id."'
                AND b.tercero_id = '".$tercero_id."' 
                AND c.tipo_id_tercero = b.tipo_id_tercero
                AND c.tercero_id = b.tercero_id
                AND d.tipo_profesional = c.tipo_profesional";
                
        $result = $dbconn->Execute($query);        
           $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        
        if($dbconn->ErrorNo() != 0) {
            return false;
        }
        
        if($result->EOF) {
            return false;
        }
        
        $DatosProfesionales_TerceroId[$tipo_tercero_id][$tercero_id] = $result->FetchRow();        
        $result->Close();

        return $DatosProfesionales_TerceroId[$tipo_tercero_id][$tercero_id];    
    }
    else
    {
        return false;
    }

}
?>
