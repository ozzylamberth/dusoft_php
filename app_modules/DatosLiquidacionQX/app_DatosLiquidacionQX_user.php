<?php

/**
 * $Id: app_DatosLiquidacionQX_user.php,v 1.74 2008/03/28 23:03:20 cahenao Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Clase que maneja los metodos que llaman a las vistas relacionadas al manejo de los
 * inventarios
 */

class app_DatosLiquidacionQX_user extends classModulo
{

  var $limit;
    var $conteo;

    function app_DatosLiquidacionQX_user()
    {
    $this->limit=GetLimitBrowser();
        //$this->limit=2;
    return true;
    }

/**
* Function que llama al menu
* @return boolean;
*/
    function main(){
      if(!$this->FrmLogueoDepartamento()){
        return false;
    }
        return true;
  }

/**
* Funcion que consulta en la base de datos los permisos del usuario para trabajar con las bodegas
* @return array
*/
    function LogueoDepartamento(){

        list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;
        $query = "SELECT a.empresa_id,b.razon_social as descripcion1,a.departamento,c.descripcion as descripcion2,a.cargue_iym
        FROM userpermisos_liquidacion_qx as a,empresas as b,departamentos as c
        WHERE a.usuario_id = ".UserGetUID()." AND a.empresa_id=b.empresa_id AND a.departamento=c.departamento";
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        if($result->EOF){
            $this->error = "Error al ejecutar la consulta.<br>";
            $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
            return false;
        }else{
      if($result->RecordCount()>0){
        while ($data = $result->FetchRow()){
          $datos[$data['descripcion1']][$data['descripcion2']]=$data;
        }
        $mtz[0]="EMPRESA";
        $mtz[1]="DEPARTAMENTO";
        $vars[0]=$mtz;
        $vars[1]=$datos;
      }
            return $vars;
        }
    }

/**
* Funcion que llama a la forma del menu para la seleccion de las opciones
* @return boolean
*/

    function LlamaFormaMenu(){
      UNSET($_SESSION['Liquidacion_QX']);
    UNSET($_SESSION['LIQUIDACION_QX']);
    $_SESSION['LIQUIDACION_QX']['Empresa']=$_REQUEST['datos_query']['empresa_id'];
        $_SESSION['LIQUIDACION_QX']['NombreEmp']=$_REQUEST['datos_query']['descripcion1'];
        $_SESSION['LIQUIDACION_QX']['Departamento']=$_REQUEST['datos_query']['departamento'];
        $_SESSION['LIQUIDACION_QX']['NombreDpto']=$_REQUEST['datos_query']['descripcion2'];
        $_SESSION['LIQUIDACION_QX']['CargueIyM']=$_REQUEST['datos_query']['cargue_iym'];
        $this->FormaMenu();
        return true;
    }

    function LlamaSolicitudIdPaciente(){
			unset($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']);
			unset($_SESSION['ARREGLO_LIQUIDACIONQX_EQUIPOS']);
			unset($_SESSION['LIQUIDACION_QX']['Bodega']);
			unset($_SESSION['LIQUIDACION_QX']['NombreBodega']);
			unset($_SESSION['LIQUIDACION_QX']['PROGRAMACION_INSUMOS']);			
			$this->DatosPacientes();
      return true;
    }

    function PedirDatosPaciente(){
        $_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_REQUEST['Documento'];
        $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['TipoDocumento'];
        $_SESSION['PACIENTES']['PACIENTE']['plan_id']=2;
        $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
        $_SESSION['PACIENTES']['RETORNO']['modulo']='DatosLiquidacionQX';
        $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
        $_SESSION['PACIENTES']['RETORNO']['metodo']='LlamaDatosRequeridosLiquidacion';
        $_SESSION['PACIENTES']['RETORNO']['argumentos']=array("TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"ingreso"=>$_REQUEST['ingreso'],"cuenta"=>$_REQUEST['cuenta'],"nombrePaciente"=>$_REQUEST['nombrePaciente'],"retornoDatosPac"=>1);
        $this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
        return true;
    }

    /**
* Funcion que retorna los tipo de documentos de la base de datos que puede tener el paciente
* @return array
*/
    function tipo_id_paciente(){
        list($dbconn) = GetDBconn();
        $query = "SELECT tipo_id_paciente,descripcion
        FROM tipos_id_pacientes ORDER BY indice_de_orden";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
                return false;
            }
            while (!$result->EOF) {
                $vars[$result->fields[0]]=$result->fields[1];
                $result->MoveNext();
            }
        }
        $result->Close();
        return $vars;
    }

    function LlamaDatosRequeridosLiquidacion(){

    if($_REQUEST['retornoDatosPac']==1){
      $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
          return true;
        }

    if($_REQUEST['Buscar']){
      $this->DatosPacientes($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['NoIngreso'],$_REQUEST['NoCuenta'],$_REQUEST['Estado'],$_REQUEST['FechaCirugia']);
      return true;
    }

    if($_REQUEST['TipoDocumento']!='AS' && $_REQUEST['TipoDocumento']!='MS'){
            if(!$_REQUEST['Documento']){
                $this->frmError["MensajeError"]="El tipo de Documento del Paciente es Obligatorio";
                $this->DatosPacientes($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['NoIngreso'],$_REQUEST['NoCuenta'],$_REQUEST['Estado'],$_REQUEST['FechaCirugia']);
                return true;
            }
        }
        $TipoId=$_REQUEST['TipoDocumento'];
        $PacienteId=$_REQUEST['Documento'];
        list($dbconn) = GetDBconn();
        $query = "SELECT a.ingreso,b.numerodecuenta,c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
        FROM ingresos a
        JOIN cuentas b ON (a.ingreso=b.ingreso AND b.estado='1')
        ,pacientes c
        WHERE a.tipo_id_paciente='".$TipoId."' AND a.paciente_id='".$PacienteId."' AND
        a.estado IN ('0','1','2') AND c.tipo_id_paciente=a.tipo_id_paciente AND c.paciente_id=a.paciente_id";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->RecordCount()<1){
        $this->frmError["MensajeError"]="Error: el Paciente No tiene un ingreso Activo o no se pueden Ingresar registros a la Cuenta ";
                $this->DatosPacientes($_REQUEST['TipoDocumento'],$_REQUEST['Documento']);
                return true;
            }
            $vars=$result->GetRowAssoc($toUpper=false);
        }
    $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$vars['nombre'],$vars['numerodecuenta'],$vars['ingreso']);
        return true;
    }

/**
* Funcion que busca en los profesionales especialistas existentes en la base de datos
* @return array
*/
    function profesionalesEspecialista($TipoDocumentoBus,$DocumentoBus,$NomcirujanoBus,$barra){
    $departamento=$_SESSION['LocalCirugias']['departamento'];
        list($dbconn) = GetDBconn();
        $query = "SELECT  x.tercero_id,z.nombre_tercero as nombre,x.tipo_id_tercero
        FROM profesionales x,terceros z,
        profesionales_especialidades a,especialidades b
        WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND x.estado='1' AND
        x.tercero_id=z.tercero_id AND x.tipo_id_tercero=z.tipo_id_tercero AND
        x.tercero_id=a.tercero_id AND x.tipo_id_tercero=a.tipo_id_tercero AND
        a.especialidad=b.especialidad AND b.sw_cirujano=1";
        if($barra==1){
            if(!empty($TipoDocumentoBus) && $TipoDocumentoBus!=-1 && !empty($DocumentoBus)){
                $query.=" AND x.tercero_id='".$DocumentoBus."' AND x.tipo_id_tercero='".$TipoDocumentoBus."'";
            }
            if(!empty($NomcirujanoBus)){
                $query.=" AND z.nombre_tercero LIKE '%".strtoupper($NomcirujanoBus)."%'";
            }
            if(empty($_REQUEST['conteo'])){
                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $this->conteo=$result->RecordCount();
            }else{
                $this->conteo=$_REQUEST['conteo'];
            }
            if(!$_REQUEST['Of']){
                    $Of='0';
            }else{
                $Of=$_REQUEST['Of'];
            }
            $query.=" LIMIT " . $this->limit . " OFFSET $Of";
        }
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        return $vars;
    }

    function InsertarDatosReqLiquidacion(){
    if($_REQUEST['volverMenu']){
      unset($_SESSION['Liquidacion_QX']);
      $this->DatosPacientes();
            return true;
        }
      //Almacenamiento de los Datos de REQUEST
      if($_REQUEST['ayudante']!=-1 && !empty($_REQUEST['ayudante'])){
      $_SESSION['Liquidacion_QX']['AYUDANTE']=$_REQUEST['ayudante'];
        }else{
      unset($_SESSION['Liquidacion_QX']['AYUDANTE']);
        }
        if(!empty($_REQUEST['AyudanteIgualEsp'])){
      $_SESSION['Liquidacion_QX']['AYUDANTE_IGUAL_ESP']=1;
        }else{
      unset($_SESSION['Liquidacion_QX']['AYUDANTE_IGUAL_ESP']);
        }
    if($_REQUEST['anestesiologo']!=-1 && !empty($_REQUEST['anestesiologo'])){
      $_SESSION['Liquidacion_QX']['ANESTESIOLOGO']=$_REQUEST['anestesiologo'];
        }else{
      unset($_SESSION['Liquidacion_QX']['ANESTESIOLOGO']);
        }
        if($_REQUEST['TipoAnestesia']!=-1 && !empty($_REQUEST['TipoAnestesia'])){
      $_SESSION['Liquidacion_QX']['TIPO_ANESTESIA']=$_REQUEST['TipoAnestesia'];
            if($_REQUEST['nogas']=='1'){
        $_SESSION['Liquidacion_QX']['NO_GAS']='1';
            }else{
        $_SESSION['Liquidacion_QX']['NO_GAS']='0';
            }
        }else{
      unset($_SESSION['Liquidacion_QX']['TIPO_ANESTESIA']);
            unset($_SESSION['Liquidacion_QX']['NO_GAS']);
        }
        /*if($_REQUEST['gasAnestesico']!=-1 && !empty($_REQUEST['gasAnestesico'])){
      $_SESSION['Liquidacion_QX']['GAS_ANESTESICO']=$_REQUEST['gasAnestesico'];
        }else{
      unset($_SESSION['Liquidacion_QX']['GAS_ANESTESICO']);
        }
    if($_REQUEST['gasAnestesicoMe']!=-1 && !empty($_REQUEST['gasAnestesicoMe'])){
      $_SESSION['Liquidacion_QX']['GAS_ANESTESICO_ME']=$_REQUEST['gasAnestesicoMe'];
        }else{
      unset($_SESSION['Liquidacion_QX']['GAS_ANESTESICO_ME']);
        }
        if(!empty($_REQUEST['DuracionGas'])){
      $_SESSION['Liquidacion_QX']['DURACION_GAS']=$_REQUEST['DuracionGas'];
        }else{
      unset($_SESSION['Liquidacion_QX']['DURACION_GAS']);
        }*/
        if($_REQUEST['ambitoCirugia']!=-1 && !empty($_REQUEST['ambitoCirugia'])){
      $_SESSION['Liquidacion_QX']['AMBITO_CIRUGIA']=$_REQUEST['ambitoCirugia'];
        }else{
      unset($_SESSION['Liquidacion_QX']['AMBITO_CIRUGIA']);
        }
        if($_REQUEST['tipoCirugia']!=-1 && !empty($_REQUEST['tipoCirugia'])){
      $_SESSION['Liquidacion_QX']['TIPO_CIRUGIA']=$_REQUEST['tipoCirugia'];
        }else{
      unset($_SESSION['Liquidacion_QX']['TIPO_CIRUGIA']);
        }
        if($_REQUEST['finalidadCirugia']!=-1 && !empty($_REQUEST['finalidadCirugia'])){
      $_SESSION['Liquidacion_QX']['FINALIDAD_CIRUGIA']=$_REQUEST['finalidadCirugia'];
        }else{
      unset($_SESSION['Liquidacion_QX']['FINALIDAD_CIRUGIA']);
        }
        if($_REQUEST['viaAcceso']!=-1 && !empty($_REQUEST['viaAcceso'])){
      $_SESSION['Liquidacion_QX']['VIA_ACCESO']=$_REQUEST['viaAcceso'];
        }else{
      unset($_SESSION['Liquidacion_QX']['VIA_ACCESO']);
        }

        if(!empty($_REQUEST['politraumatismo'])){
      $_SESSION['Liquidacion_QX']['POLITRAUMATISMO']=1;
        }else{
      unset($_SESSION['Liquidacion_QX']['POLITRAUMATISMO']);
        }

        if($_REQUEST['TipoPolitrauma']!=-1 && !empty($_REQUEST['TipoPolitrauma'])){
      $_SESSION['Liquidacion_QX']['TIPO_POLITRAUMA']=$_REQUEST['TipoPolitrauma'];
        }else{
      unset($_SESSION['Liquidacion_QX']['TIPO_POLITRAUMA']);
        }
        if($_REQUEST['TipoSala']!=-1 && !empty($_REQUEST['TipoSala'])){
      $_SESSION['Liquidacion_QX']['TIPO_SALA']=$_REQUEST['TipoSala'];
            if($_REQUEST['noquiro']=='1'){
        $_SESSION['Liquidacion_QX']['NO_QUIRO']='1';
            }else{
        $_SESSION['Liquidacion_QX']['NO_QUIRO']='0';
            }
        }else{
      unset($_SESSION['Liquidacion_QX']['TIPO_SALA']);
            unset($_SESSION['Liquidacion_QX']['NO_QUIRO']);
        }
        if($_REQUEST['quirofano']!=-1 && !empty($_REQUEST['quirofano'])){
      $_SESSION['Liquidacion_QX']['QUIROFANO']=$_REQUEST['quirofano'];
        }else{
      unset($_SESSION['Liquidacion_QX']['QUIROFANO']);
        }
        if(!empty($_REQUEST['FechaCirugia'])){
      $_SESSION['Liquidacion_QX']['FECHA_CIRUGIA']=$_REQUEST['FechaCirugia'];
        }else{
      unset($_SESSION['Liquidacion_QX']['FECHA_CIRUGIA']);
        }
    if($_REQUEST['FechaCirugia']!=-1 && !empty($_REQUEST['FechaCirugia'])){
      $_SESSION['Liquidacion_QX']['FECHA_CIRUGIA']=$_REQUEST['FechaCirugia'];
        }else{
      unset($_SESSION['Liquidacion_QX']['FECHA_CIRUGIA']);
        }
    if($_REQUEST['HoraInicio']!=-1 && !empty($_REQUEST['HoraInicio'])){
      $_SESSION['Liquidacion_QX']['HORA_INICIO']=$_REQUEST['HoraInicio'];
        }else{
      unset($_SESSION['Liquidacion_QX']['HORA_INICIO']);
        }
        if($_REQUEST['minutosInicio']!=-1 && !empty($_REQUEST['minutosInicio'])){
      $_SESSION['Liquidacion_QX']['MIN_INICIO']=$_REQUEST['minutosInicio'];
        }else{
      unset($_SESSION['Liquidacion_QX']['MIN_INICIO']);
        }
        if($_REQUEST['hora']!=-1 && !empty($_REQUEST['hora'])){
      $_SESSION['Liquidacion_QX']['HORA_DURACION']=$_REQUEST['hora'];
        }else{
      unset($_SESSION['Liquidacion_QX']['HORA_DURACION']);
        }
        if($_REQUEST['minutos']!=-1 && !empty($_REQUEST['minutos'])){
      $_SESSION['Liquidacion_QX']['MIN_DURACION']=$_REQUEST['minutos'];
        }else{
      unset($_SESSION['Liquidacion_QX']['MIN_DURACION']);
        }
        
        if(!empty($_REQUEST['recuperacion'])){
      $_SESSION['Liquidacion_QX']['RECUPERACION']=$_REQUEST['recuperacion'];
        }else{
          $_SESSION['Liquidacion_QX']['RECUPERACION']=0;
        }
        
    unset($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_BILATERAL']);
    $bilaterales=$_REQUEST['bilateral'];
    if($bilaterales){
      foreach($bilaterales as $cargo=>$valor){
        $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_BILATERAL'][$cargo]=1;
      }
    }

    if($_REQUEST['InsertarCirujano']){
      if($_REQUEST['cirujano']!=-1){
              if(sizeof($_SESSION['Liquidacion_QX']['CIRUJANOS'])>0){
          $cont=sizeof($_SESSION['Liquidacion_QX']['CIRUJANOS'])+1;
                }else{
          $cont=1;
                }
        $_SESSION['Liquidacion_QX']['CIRUJANOS'][$cont]=$_REQUEST['cirujano'];
            }
        }

    if($_REQUEST['buscarProfesional']){
      $this->BuscadorProfesional($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
            return true;
        }

    if($_REQUEST['GuardaDatos']){

      if(empty($_SESSION['Liquidacion_QX']['FINALIDAD_CIRUGIA'])){$this->frmError['finalidadCirugia']=1;$errorr=1;}
      if(empty($_SESSION['Liquidacion_QX']['AMBITO_CIRUGIA'])){$this->frmError['ambitoCirugia']=1;$errorr=1;}
      if(empty($_SESSION['Liquidacion_QX']['TIPO_CIRUGIA'])){$this->frmError['tipoCirugia']=1;$errorr=1;}
      if(empty($_SESSION['Liquidacion_QX']['VIA_ACCESO'])){$this->frmError['viaAcceso']=1;$errorr=1;}
      if(empty($_SESSION['Liquidacion_QX']['FECHA_CIRUGIA'])){$this->frmError['FechaCirugia']=1;$errorr=1;}
      if(empty($_SESSION['Liquidacion_QX']['HORA_INICIO'])){$this->frmError['HoraInicio']=1;$errorr=1;}
      if(empty($_SESSION['Liquidacion_QX']['MIN_INICIO'])){$this->frmError['HoraInicio']=1;$errorr=1;}
      if(empty($_SESSION['Liquidacion_QX']['HORA_DURACION'])){$this->frmError['duracion']=1;$errorr=1;}
      if(empty($_SESSION['Liquidacion_QX']['MIN_DURACION'])){$this->frmError['duracion']=1;$errorr=1;}
      if(empty($_SESSION['Liquidacion_QX']['TIPO_SALA'])){$this->frmError['TipoSala']=1;$errorr=1;}

      if($errorr==1){
        $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
        $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
             return true;
      }
      if(!empty($_SESSION['Liquidacion_QX']['LIQUIDACION_ID'])){
        if($this->confirmarRelacionCuenta($_SESSION['Liquidacion_QX']['LIQUIDACION_ID'])==1){
          $this->EliminarRelacionCuentasDetalle($_SESSION['Liquidacion_QX']['LIQUIDACION_ID'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
          return true;
        }
        if($this->confirmarRelacionLiquidacion($_SESSION['Liquidacion_QX']['LIQUIDACION_ID'])==1){
          $this->EliminarRelacionLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
          return true;
        }
      }
      if($this->GuardarDatosCuentaLiquidacion($_REQUEST['cuenta'],$_REQUEST['ingreso'])==true){
        $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
      }else{
        $this->frmError["MensajeError"]="ERROR AL GUARDAR LOS DATOS.";
      }
    }
    $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
    }

  function confirmarRelacionCuenta($NoLiquidacion){
    list($dbconn) = GetDBconn();
    $query = "SELECT *
    FROM cuentas_cargos_qx_procedimientos
    WHERE cuenta_liquidacion_qx_id='".$NoLiquidacion."'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Guardar en la Base de Datos";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        return 1;
      }
    }
    return 0;
  }

  function confirmarRelacionLiquidacion($NoLiquidacion){
    list($dbconn) = GetDBconn();
    $query = "SELECT *
    FROM cuentas_liquidacion_cargos
    WHERE cuentas_liquidacion_qx_id='".$NoLiquidacion."'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Guardar en la Base de Datos";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        return 1;
      }
    }
    return 0;
  }

  function ElimacionCargosCirugiaCuantasDet(){

    if($_REQUEST['Aceptar']){
      if($this->EliminaRegistrosCuentasDetalle($_REQUEST['NoLiquidacion'])==true){
        if($this->GuardarDatosCuentaLiquidacion($_REQUEST['cuenta'],$_REQUEST['ingreso'])==true){
          $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
          return true;
        }else{
          $this->frmError["MensajeError"]="ERROR AL GUARDAR LOS DATOS DE LA CURUGIA.";
        }
      }else{
        $this->frmError["MensajeError"]="ERROR AL ELIMINAR LOS REGISTROS DE LA CUENTA.";
      }
    }
    $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
  }


  function EliminaRegistrosCuentasDetalle($NoLiquidacion){
    list($dbconn) = GetDBconn();
    $dbconn->BeginTrans();
    $query = "SELECT DISTINCT a.codigo_agrupamiento_id
    FROM cuentas_codigos_agrupamiento a,cuentas_detalle b,cuentas c
    WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.descripcion='ACTO QUIRURGICO' AND a.bodegas_doc_id IS NULL AND a.numeracion IS NULL AND
    a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND b.numerodecuenta=c.numerodecuenta AND (c.estado='1' OR c.estado='2')";
    
		$result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }else{
        while(!$result->EOF){
            $codigosAgrupamiento[]=$result->GetRowAssoc($toUpper=false);
            $result->MoveNext();
        }
    }
    if($codigosAgrupamiento){
      $query="DELETE FROM cuentas_cargos_qx_procedimientos
      WHERE cuenta_liquidacion_qx_id='".$NoLiquidacion."';";
  
     
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Guardar en la Base de Datos";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;
      }else{
        $query="UPDATE cuentas_liquidaciones_qx_equipos_fijos
        SET transaccion=NULL WHERE cuenta_liquidacion_qx_id='".$NoLiquidacion."';";
  
    
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Guardar en la Base de Datos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }else{
          $query="UPDATE cuentas_liquidaciones_qx_equipos_moviles
          SET transaccion=NULL WHERE cuenta_liquidacion_qx_id='".$NoLiquidacion."';";
  
     
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }else{
            $query="UPDATE cuentas_liquidaciones_qx SET estado='0'
            WHERE cuenta_liquidacion_qx_id='".$NoLiquidacion."';";

    
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;
            }else{
              $query="DELETE
              FROM cuentas_liquidaciones_qx_procedimientos_cargos
              WHERE consecutivo_procedimiento IN (SELECT consecutivo_procedimiento FROM cuentas_liquidaciones_qx_procedimientos WHERE cuenta_liquidacion_qx_id='".$NoLiquidacion."');";

    
              $resulta=$dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0){
                $this->error = "Error al insertar en cuentas_liquidaciones_qx_procedimientos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
              }
            }  
          }
        }
      }
                
      for($cont=0;$cont<sizeof($codigosAgrupamiento);$cont++){
      $codigoAgrupamiento=$codigosAgrupamiento[$cont]['codigo_agrupamiento_id'];
      if($codigoAgrupamiento){
         $query = "INSERT INTO audit_cuentas_detalle(
              transaccion,empresa_id,centro_utilidad,numerodecuenta,
              departamento,tarifario_id,cargo,cantidad,precio,
              porcentaje_descuento_empresa,valor_cargo,valor_nocubierto,
              valor_cubierto,facturado,fecha_cargo,usuario_id,
              fecha_registro,sw_liq_manual,valor_descuento_empresa,
              valor_descuento_paciente,porcentaje_descuento_paciente,
              servicio_cargo,autorizacion_int,autorizacion_ext,
              porcentaje_gravamen,sw_cuota_paciente,sw_cuota_moderadora,
              codigo_agrupamiento_id,consecutivo,usuario_id_act,
              fecha_registro_act,sw_actualizacion,sw_cargue,cargo_cups)
              SELECT transaccion,empresa_id,centro_utilidad,numerodecuenta,
              departamento,tarifario_id,cargo,cantidad,precio,
              porcentaje_descuento_empresa,valor_cargo,valor_nocubierto,
              valor_cubierto,facturado,fecha_cargo,usuario_id,fecha_registro,
              sw_liq_manual,valor_descuento_empresa,valor_descuento_paciente,
              porcentaje_descuento_paciente,servicio_cargo,autorizacion_int,
              autorizacion_ext,porcentaje_gravamen,sw_cuota_paciente,
              sw_cuota_moderadora,codigo_agrupamiento_id,consecutivo,
              '".UserGetUID()."','".date("Y-m-d H:i:s")."','0',
              sw_cargue,cargo_cups
              FROM cuentas_detalle
              WHERE codigo_agrupamiento_id='".$codigoAgrupamiento."';";
  
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Guardar en la Base de Datos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }else{
          $query="DELETE FROM cuentas_detalle_profesionales
          WHERE transaccion IN (SELECT transaccion
          FROM cuentas_detalle WHERE codigo_agrupamiento_id='".$codigoAgrupamiento."');";
  
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }else{
            $query="DELETE FROM cuentas_detalle
            WHERE codigo_agrupamiento_id='".$codigoAgrupamiento."';";
  
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;
            }
          }
        }
      }
      }           
      $dbconn->CommitTrans();
      return true;
    }
                  
    /*no elimine de cuentas agrupacmiento porque tiene contraint con auditorias
    $query="DELETE FROM cuentas_codigos_agrupamiento
    WHERE codigo_agrupamiento_id='".$codigoAgrupamiento."'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Guardar en la Base de Datos";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;
      }else{
      */
    return false;
  }

  function EliminacionCargosLiquidacionCirugia(){

    if($_REQUEST['Aceptar']){
      list($dbconn) = GetDBconn();
      $dbconn->BeginTrans();
      $query="DELETE FROM cuentas_liquidacion_cargos
      WHERE cuentas_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."'";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Guardar en la Base de Datos";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;
      }else{
        $query="DELETE
        FROM cuentas_liquidaciones_qx_procedimientos_cargos
        WHERE consecutivo_procedimiento IN (SELECT consecutivo_procedimiento FROM cuentas_liquidaciones_qx_procedimientos WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."')";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
          $this->error = "Error al insertar en cuentas_liquidaciones_qx_procedimientos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }else{
          $query="UPDATE cuentas_liquidaciones_qx SET estado='0'
          WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."'";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }
        }
        $dbconn->CommitTrans();
        if($this->GuardarDatosCuentaLiquidacion($_REQUEST['cuenta'],$_REQUEST['ingreso'])==true){
          $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
          return true;
        }
      }
    }
    $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
  }

    function LlamaGuardarDatosCuentaLiquidacion(){
        $this->GuardarDatosCuentaLiquidacion($_REQUEST['Cuenta'],$_REQUEST['Ingreso']);
        return true;
    }


  function GuardarDatosCuentaLiquidacion($cuenta,$ingreso){

    list($dbconn) = GetDBconn();
    $dbconn->BeginTrans();
    (list($diaCir,$mesCir,$anoCir)=explode('/',$_SESSION['Liquidacion_QX']['FECHA_CIRUGIA']));
    $fechaCir=$anoCir.'-'.$mesCir.'-'.$diaCir.' '.$_SESSION['Liquidacion_QX']['HORA_INICIO'].':'.$_SESSION['Liquidacion_QX']['MIN_INICIO'].':00';
    $duracion=$_SESSION['Liquidacion_QX']['HORA_DURACION'].':'.$_SESSION['Liquidacion_QX']['MIN_DURACION'];
    (list($TipoSala,$val)=explode('/',$_SESSION['Liquidacion_QX']['TIPO_SALA']));
    if(!empty($_SESSION['Liquidacion_QX']['AYUDANTE'])){
      (list($ayudanteId,$ayudante)=explode('||//',$_SESSION['Liquidacion_QX']['AYUDANTE']));
      $ayudanteId="'$ayudanteId'";
      $ayudante="'$ayudante'";
    }else{
      $ayudanteId='NULL';
      $ayudante='NULL';
    }
        if(!empty($_SESSION['Liquidacion_QX']['AYUDANTE_IGUAL_ESP'])){
      $AyudanteIgualEsp='1';
        }else{
      $AyudanteIgualEsp='0';
        }
    if(!empty($_SESSION['Liquidacion_QX']['ANESTESIOLOGO'])){
      (list($anestesiologoId,$anestesiologo)=explode('||//',$_SESSION['Liquidacion_QX']['ANESTESIOLOGO']));
      $anestesiologoId="'$anestesiologoId'";
      $anestesiologo="'$anestesiologo'";
    }else{
      $anestesiologoId='NULL';
      $anestesiologo='NULL';
    }
    if($_SESSION['Liquidacion_QX']['POLITRAUMATISMO']){$politrauma=1;}else{$politrauma=0;}
    if(!empty($_SESSION['Liquidacion_QX']['TIPO_ANESTESIA'])){
      (list($tipoAnestesia,$val)=explode('/',$_SESSION['Liquidacion_QX']['TIPO_ANESTESIA']));
      $tipoAnestesia="'$tipoAnestesia'";
    }else{
      $tipoAnestesia='NULL';
    }
    /*if(!empty($_SESSION['Liquidacion_QX']['GAS_ANESTESICO'])){
      $gasAnestesico="'".$_SESSION['Liquidacion_QX']['GAS_ANESTESICO']."'";
    }else{
      $gasAnestesico='NULL';
    }
    if(!empty($_SESSION['Liquidacion_QX']['GAS_ANESTESICO_ME'])){
      $gasAnestesicoMe="'".$_SESSION['Liquidacion_QX']['GAS_ANESTESICO_ME']."'";
    }else{
      $gasAnestesicoMe='NULL';
    }
    if(empty($_SESSION['Liquidacion_QX']['DURACION_GAS'])){$minitosDuracionGas=0;}else{$minitosDuracionGas=$_SESSION['Liquidacion_QX']['DURACION_GAS'];}
    */
    if(!empty($_SESSION['Liquidacion_QX']['QUIROFANO'])){
      $quirofano="'".$_SESSION['Liquidacion_QX']['QUIROFANO']."'";
    }else{
      $quirofano='NULL';
    }
    if(!empty($_SESSION['Liquidacion_QX']['TIPO_POLITRAUMA'])){
      $tipoPolitrauma="'".$_SESSION['Liquidacion_QX']['TIPO_POLITRAUMA']."'";
    }else{
      $tipoPolitrauma='NULL';
    }
    
    //Validacion Cuenta
    if(!empty($cuenta)){$cuentaIns="'$cuenta'";}else{$cuentaIns='NULL';}
    //Fin Cuenta
    if(empty($_SESSION['Liquidacion_QX']['LIQUIDACION_ID'])){
      $query="SELECT servicio FROM departamentos WHERE departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."'";
      $result = $dbconn->Execute($query);
      $Servicio=$result->fields[0];
      $query="SELECT nextval('cuentas_liquidaciones_qx_cuenta_liquidacion_qx_id_seq')";
      $result = $dbconn->Execute($query);
      $liquidacionId=$result->fields[0];
            $_SESSION['Liquidacion_QX']['LIQUIDACION_ID']=$liquidacionId;
            if($_SESSION['Liquidacion_QX']['PROGRAMACION_ID']){$programacion="'".$_SESSION['Liquidacion_QX']['PROGRAMACION_ID']."'";}else{$programacion='NULL';}
      $query="INSERT INTO cuentas_liquidaciones_qx(
                        cuenta_liquidacion_qx_id,ingreso,
                        numerodecuenta,finalidad,
                        ambito_cirugia_id,via_acceso,
                        tipo_cirugia_id,fecha_cirugia,
                        duracion_cirugia,tipo_sala_id,
                        tipo_id_ayudante,ayudante_id,
                        tipo_id_anestesiologo,anestesiologo_id,
                        sw_politrauma,qx_tipo_anestesia_id,fecha_registro,
                        usuario_id,quirofano,tipo_politrauma,
                        servicio,departamento,programacion_id,
                                                ayudante_igual_especialidad)VALUES(
                        '".$liquidacionId."','".$ingreso."',
                          $cuentaIns,'".$_SESSION['Liquidacion_QX']['FINALIDAD_CIRUGIA']."',
                          '".$_SESSION['Liquidacion_QX']['AMBITO_CIRUGIA']."','".$_SESSION['Liquidacion_QX']['VIA_ACCESO']."',
                          '".$_SESSION['Liquidacion_QX']['TIPO_CIRUGIA']."','".$fechaCir."',
                          '".$duracion."','".$TipoSala."',
                          $ayudanteId,$ayudante,
                          $anestesiologoId,$anestesiologo,
                          '".$politrauma."',$tipoAnestesia,
                          '".date("Y-m-d H:i:s")."',
                          '".UserGetUID()."',$quirofano,$tipoPolitrauma,
                          '".$Servicio."','".$_SESSION['LIQUIDACION_QX']['Departamento']."',$programacion,'$AyudanteIgualEsp')";


      $resulta=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0){
        $this->error = "Error al insertar en cuentas_liquidaciones_qx";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;
      }else{
                if($_SESSION['Liquidacion_QX']['PROGRAMACION_ID']){
                    $query="SELECT DISTINCT b.plan_id
                    FROM qx_programaciones a,qx_procedimientos_programacion b
                    WHERE a.programacion_id='".$_SESSION['Liquidacion_QX']['PROGRAMACION_ID']."' AND
                    a.programacion_id=b.programacion_id";
                    $resulta=$dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0){
                        $this->error = "Error al insertar en cuentas_liquidaciones_qx";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }else{
                        if($resulta->RecordCount()>1){
                            $TotalPlanes=$resulta->RecordCount();
                            $query="SELECT count(*)
                            FROM cuentas_liquidaciones_qx
                            WHERE programacion_id='".$_SESSION['Liquidacion_QX']['PROGRAMACION_ID']."'";
                            $resulta=$dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al insertar en cuentas_liquidaciones_qx";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }else{
                                if($TotalPlanes==$resulta->RecordCount()){
                                    $query="UPDATE qx_programaciones SET estado='2' WHERE programacion_id='".$_SESSION['Liquidacion_QX']['PROGRAMACION_ID']."'";
                                    $resulta=$dbconn->Execute($query);
                                    if($dbconn->ErrorNo() != 0){
                                        $this->error = "Error al insertar en cuentas_liquidaciones_qx";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                    }
                                }
                            }
                        }else{
                            $query="UPDATE qx_programaciones SET estado='2' WHERE programacion_id='".$_SESSION['Liquidacion_QX']['PROGRAMACION_ID']."'";
                            $resulta=$dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al insertar en cuentas_liquidaciones_qx";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }
                        }
                    }
                }
            }
    }else{
      $liquidacionId=$_SESSION['Liquidacion_QX']['LIQUIDACION_ID'];
      $query="UPDATE cuentas_liquidaciones_qx SET
              finalidad='".$_SESSION['Liquidacion_QX']['FINALIDAD_CIRUGIA']."',
              ambito_cirugia_id='".$_SESSION['Liquidacion_QX']['AMBITO_CIRUGIA']."',
              via_acceso='".$_SESSION['Liquidacion_QX']['VIA_ACCESO']."',
              tipo_cirugia_id='".$_SESSION['Liquidacion_QX']['TIPO_CIRUGIA']."',
              fecha_cirugia='".$fechaCir."',
              duracion_cirugia='".$duracion."',
              tipo_sala_id='".$TipoSala."',
              tipo_id_ayudante=$ayudanteId,
              ayudante_id=$ayudante,
              tipo_id_anestesiologo=$anestesiologoId,
              anestesiologo_id=$anestesiologo,
              sw_politrauma=$politrauma,
              qx_tipo_anestesia_id=$tipoAnestesia,              
              quirofano=$quirofano,
              tipo_politrauma=$tipoPolitrauma,
                            ayudante_igual_especialidad='$AyudanteIgualEsp',
                            minutos_recuperacion='".$_SESSION['Liquidacion_QX']['RECUPERACION']."'
              WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."'";

      $resulta=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0){
        $this->error = "Error al insertar en cuentas_liquidaciones_qx";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;
      }else{
        $query="DELETE FROM cuentas_liquidaciones_qx_procedimientos WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."'";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
          $this->error = "Error al insertar en cuentas_liquidaciones_qx_procedimientos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }else{
          $query="DELETE FROM cuentas_liquidaciones_qx_gases_anestesicos WHERE cuenta_liquidacion_qx_id='".$_SESSION['Liquidacion_QX']['LIQUIDACION_ID']."'";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0){
            $this->error = "Error al insertar en cuentas_liquidaciones_qx_procedimientos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }
        }
      }
    }
    if(sizeof($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'])>0){
      foreach($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'] as $cirujanoVector => $vector){
        foreach($vector as $indice => $procedimiento){
          (list($tipoIdCir,$IdCir,$NomCir)=explode('||//',$cirujanoVector));
          (list($cargo,$descripcion,$sw_bilateral)=explode('||//',$procedimiento));
          if($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_BILATERAL'][$cargo]==1){$sw_bilateral=1;}else{$sw_bilateral=0;}
          if($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoVector][$indice]][1]){
            (list($codigo,$descripcion)=explode('||//',$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoVector][$indice]][1]));
            $diagnostico1="'$codigo'";
          }else{
            $diagnostico1='NULL';
          }
          if($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoVector][$indice]][2]){
            (list($codigo,$descripcion)=explode('||//',$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoVector][$indice]][2]));
            $diagnostico2="'$codigo'";
          }else{
            $diagnostico2='NULL';
          }
          if($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoVector][$indice]][3]){
            (list($codigo,$descripcion)=explode('||//',$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujanoVector][$indice]][3]));
            $diagnostico3="'$codigo'";
          }else{
            $diagnostico3='NULL';
          }
                    $query="INSERT INTO cuentas_liquidaciones_qx_procedimientos(
                          cuenta_liquidacion_qx_id,tipo_id_cirujano,
                          cirujano_id,cargo_cups,
                          autorizacion_ext,autorizacion_int,
                          sw_bilateral,diagnostico_uno,
                          diagnostico_dos,complicacion)VALUES(
                          '".$liquidacionId."','".$tipoIdCir."',
                          '".$IdCir."','".$cargo."',
                          NULL,NULL,
                          '".$sw_bilateral."',$diagnostico1,
                          $diagnostico2,$diagnostico3)";

          $resulta=$dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
            $this->error = "Error al insertar en hc_os_solicitudes";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }
        }
      }
    }
    //Guardo los gases solicitados
    if(sizeof($_SESSION['Liquidacion_QX']['GASES'])>0){
      foreach($_SESSION['Liquidacion_QX']['GASES'] as $i=>$vector){
        //jab
	$query="INSERT INTO cuentas_liquidaciones_qx_gases_anestesicos(
                cuenta_liquidacion_qx_id,tipo_gas_id,tipo_suministro_id,
                frecuencia_id,tiempo_suministro,fecha_registro,usuario_id,
                transaccion_cuenta)
                VALUES('".$liquidacionId."','".$vector['TipoGas']."','".$vector['MetodoGas']."',
                '".$vector['FrecuenciaGas']."','".$vector['MinutosGas']."',
                '".date("Y-m-d H:i:s")."','".UserGetUID()."',NULL)";
        $resulta=$dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al insertar en hc_os_solicitudes";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }
      }      
    }
    //fin
    $dbconn->CommitTrans();
    return true;
  }


  function LlamaFormaLiquidarCargosCuenta(){

    list($dbconn) = GetDBconn();
    $query = "SELECT a.ingreso,b.numerodecuenta,c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
    FROM ingresos a
    JOIN cuentas b ON(a.ingreso=b.ingreso AND (b.estado='1' OR b.estado='2'))
    ,pacientes c
    WHERE a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."' AND a.paciente_id='".$_REQUEST['Documento']."' AND
    a.estado IN ('1','2') AND c.tipo_id_paciente=a.tipo_id_paciente AND c.paciente_id=a.paciente_id";
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()<1){
        $this->frmError["MensajeError"]="Debe Verificar, el Paciente no tiene una cuenta Activa";
        $this->DatosPacientes($_REQUEST['TipoDocumentoFil'],$_REQUEST['DocumentoFil'],$_REQUEST['NoIngresoFil'],$_REQUEST['NoCuentaFil'],$_REQUEST['EstadoFil'],$_REQUEST['FechaCirugiaFil']);
        return true;
      }else{
        $ingresoActivo=$result->fields[0];
        $cuentaActiva=$result->fields[1];
      }
    }
    $query = "SELECT *
    FROM cuentas_liquidaciones_qx_procedimientos
    WHERE cuenta_liquidacion_qx_id='".$_REQUEST['liquidacionId']."'";
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()<1){
        $this->frmError["MensajeError"]="DEBE INSERTAR LOS CIRUJANOS PARTICIPANTES DE LA CIRUGIA Y SUS PROCEDIMIENTOS.";
        $this->DatosPacientes($_REQUEST['TipoDocumentoFil'],$_REQUEST['DocumentoFil'],$_REQUEST['NoIngresoFil'],$_REQUEST['NoCuentaFil'],$_REQUEST['EstadoFil'],$_REQUEST['FechaCirugiaFil']);
        return true;
      }
    }
    $this->FormaLiquidarCargosCuenta($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$cuentaActiva,$ingresoActivo);
    return true;
  }

  function EliminaCuentaReliquidacion(){

    if($_REQUEST['Aceptar']){
      if($this->EliminaRegistrosCuentasDetalle($_REQUEST['NoLiquidacion'])==true){
        $this->CargarCargosCirugiaTemporal($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
      }else{
        $this->frmError["MensajeError"]="ERROR AL ELIMINAR LOS DATOS DE LA CUENTA.";
        $this->FormaMostrarDatosLiquidacion($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
      }
    }
    $this->FormaMostrarDatosLiquidacion($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
    return true;
  }


  function LlamaCargarCargosCirugiaTemporal(){
    unset($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']);
    unset($_SESSION['ARREGLO_LIQUIDACIONQX_EQUIPOS']);
    $_REQUEST['der_cirujano']=1;
    $_REQUEST['der_anestesiologo']=1;
    $_REQUEST['der_ayudante']=1;
    $_REQUEST['der_sala']=1;
    $_REQUEST['der_materiales']=1;
		//lo agregue por el error de guardar los datos
		list($dbconn) = GetDBconn();
		$query="DELETE
		FROM cuentas_liquidaciones_qx_procedimientos_cargos
		WHERE consecutivo_procedimiento IN (SELECT consecutivo_procedimiento FROM cuentas_liquidaciones_qx_procedimientos WHERE cuenta_liquidacion_qx_id='".$_REQUEST['NoLiquidacion']."');";
		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al insertar en cuentas_liquidaciones_qx_procedimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();			
			return false;
		}
		//fin
    $this->FormaEquivalentesLiquidacion($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
    return true;
  }

  function CargarCargosCirugiaTemporal($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso){

    if (!IncludeClass("LiquidacionQX")){
      $this->frmError["MensajeError"]=$a->ErrMsg();
            $_REQUEST['der_cirujano']=1;
            $_REQUEST['der_anestesiologo']=1;
            $_REQUEST['der_ayudante']=1;
            $_REQUEST['der_sala']=1;
            $_REQUEST['der_materiales']=1;
      $this->FormaEquivalentesLiquidacion($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso);
      return true;
    }else{
      $a= new LiquidacionQX;
      if($a->SetDatosLiquidacion($NoLiquidacion)===false){
        $this->frmError["MensajeError"]=$a->ErrMsg();
                $_REQUEST['der_cirujano']=1;
                $_REQUEST['der_anestesiologo']=1;
                $_REQUEST['der_ayudante']=1;
                $_REQUEST['der_sala']=1;
                $_REQUEST['der_materiales']=1;
        $this->FormaEquivalentesLiquidacion($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso);
        return true;
      }else{
        if(($retorno = $a->GetLiquidacion())===false){
          $this->frmError["MensajeError"]=$a->ErrMsg();
                    $_REQUEST['der_cirujano']=1;
                    $_REQUEST['der_anestesiologo']=1;
                    $_REQUEST['der_ayudante']=1;
                    $_REQUEST['der_sala']=1;
                    $_REQUEST['der_materiales']=1;
          $this->FormaEquivalentesLiquidacion($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso);
          return true;
        }else{
          if(is_array($retorno)){
					/*echo '<pre>';*/
					/*print_r($retorno);*/
            $_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']=$retorno;
                        if(($retornoEquipos = $a->GetLiquidacionEquiposQX())===false){
                            $this->frmError["MensajeError"]=$a->ErrMsg();
                            $_REQUEST['der_cirujano']=1;
                            $_REQUEST['der_anestesiologo']=1;
                            $_REQUEST['der_ayudante']=1;
                            $_REQUEST['der_sala']=1;
                            $_REQUEST['der_materiales']=1;
                            $this->FormaEquivalentesLiquidacion($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso);
                            return true;
                        }
            if(is_array($retornoEquipos)){
              $_SESSION['ARREGLO_LIQUIDACIONQX_EQUIPOS']=$retornoEquipos;
            }
                        $_REQUEST['der_cirujano']=1;
                        $_REQUEST['der_anestesiologo']=1;
                        $_REQUEST['der_ayudante']=1;
                        $_REQUEST['der_sala']=1;
                        $_REQUEST['der_materiales']=1;
            $this->FormaMostrarDatosLiquidacion($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso);
            return true;
          }else{
            $this->frmError["MensajeError"]="No se liquido ningun Procedimiento.";
                        $_REQUEST['der_cirujano']=1;
                        $_REQUEST['der_anestesiologo']=1;
                        $_REQUEST['der_ayudante']=1;
                        $_REQUEST['der_sala']=1;
                        $_REQUEST['der_materiales']=1;
            $this->FormaMostrarDatosLiquidacion($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso);
            return true;
          }
        }
      }
    }
  }


  function GuardarDatosRetornadosLiquidacion(){

    if($_REQUEST['GuardarReliquidar']){
      if($this->confirmarRelacionCuenta($_REQUEST['NoLiquidacion'])==1){
        $this->EliminarRelacionCuentasDetalle($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$bandera=1);
        return true;
      }
      list($dbconn) = GetDBconn();
      $query="DELETE FROM cuentas_liquidacion_cargos
      WHERE cuentas_liquidacion_qx_id='".$_REQUEST['NoLiquidacion']."'";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Guardar en la Base de Datos";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        $query="UPDATE cuentas_liquidaciones_qx SET estado='0'
        WHERE cuenta_liquidacion_qx_id='".$_REQUEST['NoLiquidacion']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Guardar en la Base de Datos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
      }
      $_REQUEST['der_cirujano']=1;
      $_REQUEST['der_anestesiologo']=1;
      $_REQUEST['der_ayudante']=1;
      $_REQUEST['der_sala']=1;
      $_REQUEST['der_materiales']=1;
      $this->FormaEquivalentesLiquidacion($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
      return true;
    }
    $datosDpto=$this->ServicioCentroUtilidadDepartamento($_SESSION['LIQUIDACION_QX']['Departamento']);
    if($this->GuardarCuentaDetalle($_REQUEST['NoLiquidacion'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$datosDpto['centro_utilidad'],$_REQUEST['valoresManual'])==false){
      $this->mensajeDeError = "Error al Liquidar los Cargos";
      return false;
    }else{
      $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
    }
    $this->FormaMostrarDatosLiquidacion($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
    return true;
  }


  function ServicioCentroUtilidadDepartamento($departamento){
    list($dbconn) = GetDBconn();
    $query="SELECT servicio,centro_utilidad
    FROM departamentos
    WHERE departamento='".$departamento."'";
    $resulta=$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0){
      $this->error = "Error al insertar en cuentas_liquidaciones_qx";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($resulta->RecordCount()>0){
        $vars=$resulta->GetRowAssoc($toUpper=false);
        return $vars;
      }
    }
    return false;
  }


    function LlamaGuardarCuentaDetalle(){
        $this->GuardarCuentaDetalle($_SESSION['Liquidacion_QX']['LIQUIDACION_ID'],$_REQUEST['Cuenta'],$_REQUEST['Ingreso'],$_SESSION['CUENTAS']['CENTROUTILIDAD'],0);
        return true;
    }


  function GuardarCuentaDetalle($NoLiquidacion,$cuenta,$ingreso,$centroUtilidad,$valoresManual){
    
    if($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']){
      list($dbconn) = GetDBconn();
      $dbconn->BeginTrans();
            $query="";
            //$valoresManual indica que la liquidacion tuvo correcciones manualmente
            if($valoresManual==1){
                $Porcentajes=$_REQUEST['Porcentajes'];
                $valoresCubiertos=$_REQUEST['valoresCubiertos'];
                $valoresNoCubiertos=$_REQUEST['valoresNoCubiertos'];

                foreach($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'] as $indiceCirujano=>$Vector){
                    foreach($Vector as $indiceProcedimiento=>$DatosQX){
                        $materiales=$DatosQX['sw_medicamentos_consumo'];
                        foreach($DatosQX['liquidacion'] as $derecho=>$DatosDerecho){
                            $autorizacion_int=$DatosQX['autorizacion_int'];
                            if(!$autorizacion_int){$autorizacion_int1='NULL';}else{$autorizacion_int1="'$autorizacion_int'";}
                            $autorizacion_ext=$DatosQX['autorizacion_ext'];
                            if(!$autorizacion_ext){$autorizacion_ext1='NULL';}else{$autorizacion_ext1="'$autorizacion_ext'";}

                            $tipo_id_profesional=$DatosDerecho['tipo_id_tercero'];
                            if(!$tipo_id_profesional){$tipo_id_profesional='NULL';}else{$tipo_id_profesional="'$tipo_id_profesional'";}
                            $profesional_id=$DatosDerecho['tercero_id'];
                            if(!$profesional_id){$profesional_id='NULL';}else{$profesional_id="'$profesional_id'";}
                            $valor_cargo=str_replace('.',"",$valoresCubiertos[$indiceCirujano][$indiceProcedimiento]['liquidacion'][$derecho])+str_replace('.',"",$valoresNoCubiertos[$indiceCirujano][$indiceProcedimiento]['liquidacion'][$derecho]);
                            $precio_plan=($valor_cargo/$DatosDerecho['cantidad']);
                            //Datos eliminados en el insert
                            //$DatosDerecho['valor_descuento_empresa']
                            //$DatosDerecho['valor_descuento_paciente']
                            //$DatosDerecho['porcentaje_gravamen']
                            //$DatosDerecho['sw_cuota_paciente']
                            //$DatosDerecho['sw_cuota_moderadora']

                            $query.="INSERT INTO cuentas_liquidacion_cargos(
                                            cuentas_liquidacion_qx_id,empresa_id,centro_utilidad,
                                            numerodecuenta,departamento,tarifario_id,
                                            cargo,cantidad,precio,
                                            porcentaje_descuento_empresa,valor_cargo,valor_nocubierto,
                                            valor_cubierto,facturado,fecha_cargo,
                                            sw_liq_manual,valor_descuento_empresa,valor_descuento_paciente,
                                            porcentaje_descuento_paciente,servicio_cargo,
                                            autorizacion_int,autorizacion_ext,porcentaje_gravamen,
                                            sw_cuota_paciente,sw_cuota_moderadora,cargo_cups,
                                            sw_cargue,tipo_id_profesional,profesional_id,
                                            consecutivo_procedimiento,tarifario_id_procedimiento,cargo_procedimiento,
                                            tipo_cargo_qx_id,secuencia,porcentaje,manual)VALUES(
                                            '".$NoLiquidacion."','".$_SESSION['LIQUIDACION_QX']['Empresa']."','$centroUtilidad',
                                            '".$cuenta."','".$_SESSION['LIQUIDACION_QX']['Departamento']."','".$DatosDerecho['tarifario_id']."',
                                            '".$DatosDerecho['cargo']."','".$DatosDerecho['cantidad']."','".$precio_plan."',
                                            '0','".$valor_cargo."','".str_replace('.',"",$valoresNoCubiertos[$indiceCirujano][$indiceProcedimiento]['liquidacion'][$derecho])."',
                                            '".str_replace('.',"",$valoresCubiertos[$indiceCirujano][$indiceProcedimiento]['liquidacion'][$derecho])."','".$DatosDerecho['facturado']."','".date("Y-m-d H:i:s")."',
                                            '0','".$DatosDerecho['valor_descuento_empresa']."','".$DatosDerecho['valor_descuento_paciente']."',
                                            '0','6',
                                            $autorizacion_int1,$autorizacion_ext1,'".$DatosDerecho['porcentaje_gravamen']."',
                                            '".$DatosDerecho['sw_cuota_paciente']."','".$DatosDerecho['sw_cuota_moderadora']."','".$DatosDerecho['cargo_cups']."',
                                            '0',$tipo_id_profesional,$profesional_id,
                                            '".$DatosQX['consecutivo_procedimiento']."','".$DatosQX['tarifario_id']."','".$DatosQX['cargo']."',
                                            '".$derecho."','".$DatosDerecho['SECUENCIA']."','".$Porcentajes[$indiceCirujano][$indiceProcedimiento]['liquidacion'][$derecho]."','1');";

                                            $_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][$indiceCirujano][$indiceProcedimiento]['liquidacion'][$derecho]['PORCENTAJE']=$Porcentajes[$indiceCirujano][$indiceProcedimiento]['liquidacion'][$derecho];
                                            $_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][$indiceCirujano][$indiceProcedimiento]['liquidacion'][$derecho]['valor_cubierto']=str_replace('.',"",$valoresCubiertos[$indiceCirujano][$indiceProcedimiento]['liquidacion'][$derecho]);
                                            $_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'][$indiceCirujano][$indiceProcedimiento]['liquidacion'][$derecho]['valor_no_cubierto']=str_replace('.',"",$valoresNoCubiertos[$indiceCirujano][$indiceProcedimiento]['liquidacion'][$derecho]);

                                if($DatosQX['uvrs'])
                                {
                                    $query.="UPDATE cuentas_liquidaciones_qx_procedimientos_cargos SET uvrs='".$DatosQX['uvrs']."' WHERE consecutivo_procedimiento='".$DatosQX['consecutivo_procedimiento']."' AND tarifario_id='".$DatosQX['tarifario_id']."' AND cargo='".$DatosQX['cargo']."';";
                                }
                                elseif($DatosQX['grupo_qx'])
                                {
                                    $query.="UPDATE cuentas_liquidaciones_qx_procedimientos_cargos SET uvrs='".$DatosQX['grupo_qx']."' WHERE consecutivo_procedimiento='".$DatosQX['consecutivo_procedimiento']."' AND tarifario_id='".$DatosQX['tarifario_id']."' AND cargo='".$DatosQX['cargo']."';";
                                }
                        }
                    }
                }

        if($DatosQXEquipos=$_SESSION['ARREGLO_LIQUIDACIONQX_EQUIPOS']){
          $valoresCubiertosEquipos=$_REQUEST['valoresCubiertosEquipos'];
                  $valoresNoCubiertosEquipos=$_REQUEST['valoresNoCubiertosEquipos'];
          for($i=0;$i<sizeof($DatosQXEquipos);$i++){
            $autorizacion_int=$DatosQXEquipos[$i]['autorizacion_int'];
            if(!$autorizacion_int){$autorizacion_int1='NULL';}else{$autorizacion_int1="'$autorizacion_int'";}
            $autorizacion_ext=$DatosQXEquipos[$i]['autorizacion_ext'];
            if(!$autorizacion_ext){$autorizacion_ext1='NULL';}else{$autorizacion_ext1="'$autorizacion_ext'";}

            $tipo_id_profesional=$DatosQXEquipos[$i]['tipo_id_tercero'];
            if(!$tipo_id_profesional){$tipo_id_profesional='NULL';}else{$tipo_id_profesional="'$tipo_id_profesional'";}
            $profesional_id=$DatosQXEquipos[$i]['tercero_id'];
            if(!$profesional_id){$profesional_id='NULL';}else{$profesional_id="'$profesional_id'";}
            if($DatosQXEquipos[$i]['tipo_equipo']=='movil'){
              $tipo_equipo='M';
            }else{
              $tipo_equipo='F';
            }
            $valor_cargo=str_replace('.',"",$valoresCubiertosEquipos[$i])+str_replace('.',"",$valoresNoCubiertosEquipos[$i]);
            $precio_plan=($valor_cargo/$DatosQXEquipos[$i]['cantidad']);
            //Datos eliminados en el insert
            //$DatosDerecho['valor_descuento_empresa']
            //$DatosDerecho['valor_descuento_paciente']
            //$DatosDerecho['porcentaje_gravamen']
            //$DatosDerecho['sw_cuota_paciente']
            //$DatosDerecho['sw_cuota_moderadora']
            $query.="INSERT INTO cuentas_liquidacion_cargos(
                    cuentas_liquidacion_qx_id,empresa_id,centro_utilidad,
                    numerodecuenta,departamento,tarifario_id,
                    cargo,cantidad,precio,
                    porcentaje_descuento_empresa,valor_cargo,valor_nocubierto,
                    valor_cubierto,facturado,fecha_cargo,
                    sw_liq_manual,valor_descuento_empresa,valor_descuento_paciente,
                    porcentaje_descuento_paciente,servicio_cargo,
                    autorizacion_int,autorizacion_ext,porcentaje_gravamen,
                    sw_cuota_paciente,sw_cuota_moderadora,cargo_cups,
                    sw_cargue,tipo_id_profesional,profesional_id,
                    consecutivo_procedimiento,tarifario_id_procedimiento,cargo_procedimiento,
                    tipo_cargo_qx_id,secuencia,porcentaje,manual,tipo_equipo,equipo_id)VALUES(
                    '".$NoLiquidacion."','".$_SESSION['LIQUIDACION_QX']['Empresa']."','$centroUtilidad',
                    '".$cuenta."','".$_SESSION['LIQUIDACION_QX']['Departamento']."','".$DatosQXEquipos[$i]['tarifario_id']."',
                    '".$DatosQXEquipos[$i]['cargo']."','".$DatosQXEquipos[$i]['cantidad']."','".$precio_plan."',
                    '0','".$valor_cargo."','".str_replace('.',"",$valoresNoCubiertosEquipos[$i])."',
                    '".str_replace('.',"",$valoresCubiertosEquipos[$i])."','".$DatosQXEquipos[$i]['facturado']."','".date("Y-m-d H:i:s")."',
                    '0','".$DatosQXEquipos[$i]['valor_descuento_empresa']."','".$DatosQXEquipos[$i]['valor_descuento_paciente']."',
                    '0','6',
                    $autorizacion_int1,$autorizacion_ext1,'".$DatosQXEquipos[$i]['porcentaje_gravamen']."',
                    '".$DatosQXEquipos[$i]['sw_cuota_paciente']."','".$DatosQXEquipos[$i]['sw_cuota_moderadora']."','".$DatosQXEquipos[$i]['cargo_cups']."',
                    '0',$tipo_id_profesional,$profesional_id,
                    NULL,NULL,NULL,
                    NULL,NULL,NULL,'1','$tipo_equipo','".$DatosQXEquipos[$i]['equipo_id']."');";
                    $_SESSION['ARREGLO_LIQUIDACIONQX_EQUIPOS'][$i]['valor_cubierto']=str_replace('.',"",$valoresCubiertos[$i]);
                    $_SESSION['ARREGLO_LIQUIDACIONQX_EQUIPOS'][$i]['valor_no_cubierto']=str_replace('.',"",$valoresNoCubiertosEquipos[$i]);

          }

        }
            }else{
                
                foreach($_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS'] as $indiceCirujano=>$Vector){
                    foreach($Vector as $indiceProcedimiento=>$DatosQX){                        
                        $materiales=$DatosQX['sw_medicamentos_consumo'];                        
                        foreach($DatosQX['liquidacion'] as $derecho=>$DatosDerecho){

                            if($indiceCirujano==1 && $indiceProcedimiento==1){
                                $principalPro=$DatosQX['cargo_cups'];
                            }
                            $autorizacion_int=$DatosQX['autorizacion_int'];
                            if(!$autorizacion_int){$autorizacion_int1='NULL';}else{$autorizacion_int1="'$autorizacion_int'";}
                            $autorizacion_ext=$DatosQX['autorizacion_ext'];
                            if(!$autorizacion_ext){$autorizacion_ext1='NULL';}else{$autorizacion_ext1="'$autorizacion_ext'";}

                            $tipo_id_profesional=$DatosDerecho['tipo_id_tercero'];
                            if(!$tipo_id_profesional){$tipo_id_profesional='NULL';}else{$tipo_id_profesional="'$tipo_id_profesional'";}
                            $profesional_id=$DatosDerecho['tercero_id'];
                            if(!$profesional_id){$profesional_id='NULL';}else{$profesional_id="'$profesional_id'";}
                            //Falta el Servicio $DatosQX['servicio']
                            $query.="INSERT INTO cuentas_liquidacion_cargos(
                                            cuentas_liquidacion_qx_id,empresa_id,centro_utilidad,
                                            numerodecuenta,departamento,tarifario_id,
                                            cargo,cantidad,precio,
                                            porcentaje_descuento_empresa,valor_cargo,valor_nocubierto,
                                            valor_cubierto,facturado,fecha_cargo,
                                            sw_liq_manual,valor_descuento_empresa,valor_descuento_paciente,
                                            porcentaje_descuento_paciente,servicio_cargo,
                                            autorizacion_int,autorizacion_ext,porcentaje_gravamen,
                                            sw_cuota_paciente,sw_cuota_moderadora,cargo_cups,
                                            sw_cargue,tipo_id_profesional,profesional_id,
                                            consecutivo_procedimiento,tarifario_id_procedimiento,cargo_procedimiento,
                                            tipo_cargo_qx_id,secuencia,porcentaje,manual)VALUES(
                                            '".$NoLiquidacion."','".$_SESSION['LIQUIDACION_QX']['Empresa']."','$centroUtilidad',
                                            '".$cuenta."','".$_SESSION['LIQUIDACION_QX']['Departamento']."','".$DatosDerecho['tarifario_id']."',
                                            '".$DatosDerecho['cargo']."','".$DatosDerecho['cantidad']."','".$DatosDerecho['precio_plan']."',
                                            '0','".$DatosDerecho['valor_cargo']."','".$DatosDerecho['valor_no_cubierto']."',
                                            '".$DatosDerecho['valor_cubierto']."','".$DatosDerecho['facturado']."','".date("Y-m-d H:i:s")."',
                                            '0','".$DatosDerecho['valor_descuento_empresa']."','".$DatosDerecho['valor_descuento_paciente']."',
                                            '0','6',
                                            $autorizacion_int1,$autorizacion_ext1,'".$DatosDerecho['porcentaje_gravamen']."',
                                            '".$DatosDerecho['sw_cuota_paciente']."','".$DatosDerecho['sw_cuota_moderadora']."','".$DatosDerecho['cargo_cups']."',
                                            '0',$tipo_id_profesional,$profesional_id,
                                            '".$DatosQX['consecutivo_procedimiento']."','".$DatosQX['tarifario_id']."','".$DatosQX['cargo']."',
                                            '".$derecho."','".$DatosDerecho['SECUENCIA']."','".$DatosDerecho['PORCENTAJE']."','0');";

                                if($DatosQX['uvrs'])
                                {
                                    $query.="UPDATE cuentas_liquidaciones_qx_procedimientos_cargos SET uvrs='".$DatosQX['uvrs']."' WHERE consecutivo_procedimiento='".$DatosQX['consecutivo_procedimiento']."' AND tarifario_id='".$DatosQX['tarifario_id']."' AND cargo='".$DatosQX['cargo']."';";
                                }
                                elseif($DatosQX['grupo_qx'])
                                {
                                    $query.="UPDATE cuentas_liquidaciones_qx_procedimientos_cargos SET uvrs='".$DatosQX['grupo_qx']."' WHERE consecutivo_procedimiento='".$DatosQX['consecutivo_procedimiento']."' AND tarifario_id='".$DatosQX['tarifario_id']."' AND cargo='".$DatosQX['cargo']."';";
                                }
                         }
                    }
                }
        
        if($DatosQXEquipos=$_SESSION['ARREGLO_LIQUIDACIONQX_EQUIPOS']){
          for($i=0;$i<sizeof($DatosQXEquipos);$i++){

              $autorizacion_int=$DatosQXEquipos[$i]['autorizacion_int'];
                            if(!$autorizacion_int){$autorizacion_int1='NULL';}else{$autorizacion_int1="'$autorizacion_int'";}
                            $autorizacion_ext=$DatosQXEquipos[$i]['autorizacion_ext'];
                            if(!$autorizacion_ext){$autorizacion_ext1='NULL';}else{$autorizacion_ext1="'$autorizacion_ext'";}

                            $tipo_id_profesional=$DatosQXEquipos[$i]['tipo_id_tercero'];
                            if(!$tipo_id_profesional){$tipo_id_profesional='NULL';}else{$tipo_id_profesional="'$tipo_id_profesional'";}
                            $profesional_id=$DatosQXEquipos[$i]['tercero_id'];
                            if(!$profesional_id){$profesional_id='NULL';}else{$profesional_id="'$profesional_id'";}
              if($DatosQXEquipos[$i]['tipo_equipo']=='movil'){
                $tipo_equipo='M';
              }else{
                $tipo_equipo='F';
              }
              $query.="INSERT INTO cuentas_liquidacion_cargos(
              cuentas_liquidacion_qx_id,empresa_id,centro_utilidad,
              numerodecuenta,departamento,tarifario_id,
              cargo,cantidad,precio,
              porcentaje_descuento_empresa,valor_cargo,valor_nocubierto,
              valor_cubierto,facturado,fecha_cargo,
              sw_liq_manual,valor_descuento_empresa,valor_descuento_paciente,
              porcentaje_descuento_paciente,servicio_cargo,
              autorizacion_int,autorizacion_ext,porcentaje_gravamen,
              sw_cuota_paciente,sw_cuota_moderadora,cargo_cups,
              sw_cargue,tipo_id_profesional,profesional_id,
              consecutivo_procedimiento,tarifario_id_procedimiento,cargo_procedimiento,
              tipo_cargo_qx_id,secuencia,porcentaje,manual,tipo_equipo,equipo_id)VALUES(
              '".$NoLiquidacion."','".$_SESSION['LIQUIDACION_QX']['Empresa']."','$centroUtilidad',
              '".$cuenta."','".$_SESSION['LIQUIDACION_QX']['Departamento']."','".$DatosQXEquipos[$i]['tarifario_id']."',
              '".$DatosQXEquipos[$i]['cargo']."','".$DatosQXEquipos[$i]['cantidad']."','".$DatosQXEquipos[$i]['precio_plan']."',
              '0','".$DatosQXEquipos[$i]['valor_cargo']."','".$DatosQXEquipos[$i]['valor_no_cubierto']."',
              '".$DatosQXEquipos[$i]['valor_cubierto']."','".$DatosQXEquipos[$i]['facturado']."','".date("Y-m-d H:i:s")."',
              '0','".$DatosQXEquipos[$i]['valor_descuento_empresa']."','".$DatosQXEquipos[$i]['valor_descuento_paciente']."',
              '0','6',
              $autorizacion_int1,$autorizacion_ext1,'".$DatosQXEquipos[$i]['porcentaje_gravamen']."',
              '".$DatosQXEquipos[$i]['sw_cuota_paciente']."','".$DatosQXEquipos[$i]['sw_cuota_moderadora']."','".$DatosQXEquipos[$i]['cargo_cups']."',
              '0',$tipo_id_profesional,$profesional_id,
              NULL,NULL,NULL,NULL,NULL,NULL,'0','$tipo_equipo','".$DatosQXEquipos[$i]['equipo_id']."');";

          }
        }

            }
      //aumentado para los materiales      
      if($materiales==1){$der_materiales=1;}else{$der_materiales=0;}            
      $query.="UPDATE cuentas_liquidaciones_qx SET sw_medicamentos_consumo='$der_materiales'
            WHERE cuenta_liquidacion_qx_id='".$NoLiquidacion."'";      
      //fin
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Guardar en la Base de Datos";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;
      }else{
        if($principalPro){$principalPro="'$principalPro'";}else{$principalPro='NULL';}
         $query = "UPDATE cuentas_liquidaciones_qx
          SET estado='1',numerodecuenta=$cuenta,ingreso=$ingreso,cargo_principal=$principalPro
          WHERE cuenta_liquidacion_qx_id='".$NoLiquidacion."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Guardar en la Base de Datos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }else{
          $dbconn->CommitTrans();
          return true;
        }
      }
    }
    return false;
  }

  function LlamaFormaModificarLiquidacion(){

    if($this->DatosCargoCirugia($_REQUEST['liquidacionId'],$_REQUEST['estado'])===true){
      $this->FormaMostrarDatosLiquidacion($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
      return true;
    }
    return false;
  }



  function CargarALaCuentaPaciente(){
    list($dbconn) = GetDBconn();
    
    $cuenta = $_REQUEST['cuenta'];
    if($cuenta)
    {
    	 $filtroCuenta = "AND b.numerodecuenta = ".$cuenta."";
    }

    $query = "SELECT MAX(a.ingreso), MAX(b.numerodecuenta)
              FROM ingresos a
              JOIN cuentas b ON(a.ingreso=b.ingreso AND (b.estado='1' OR b.estado='2'))
              WHERE a.tipo_id_paciente='".$_REQUEST['TipoDocumento']."'
              AND a.paciente_id='".$_REQUEST['Documento']."' 
              --AND a.estado IN ('1','2')
              $filtroCuenta;";

    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()<1){
        $this->frmError["MensajeError"]="Debe Verificar, el Paciente no tiene una cuenta Activa";
                if(empty($_REQUEST['externo'])){
            $this->FormaMostrarDatosLiquidacion($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
                }
        return true;
      }else{
        $ingresoActivo=$result->fields[0];
        $cuentaActiva=$result->fields[1];
      }
      $query = "SELECT a.empresa_id,a.centro_utilidad,a.numerodecuenta,
                        a.departamento,a.tarifario_id,a.cargo,
                        a.cantidad,a.precio,a.porcentaje_descuento_empresa,
                        a.valor_cargo,a.valor_nocubierto,a.valor_cubierto,
                        a.facturado,b.fecha_cirugia as fecha_cargo,a.sw_liq_manual,
                        a.valor_descuento_empresa,a.valor_descuento_paciente,
                        a.porcentaje_descuento_paciente,a.servicio_cargo,
                        a.autorizacion_int,a.autorizacion_ext,a.porcentaje_gravamen,
                        a.sw_cuota_paciente,a.sw_cuota_moderadora,a.cargo_cups,
                        a.sw_cargue,a.tipo_id_profesional,a.profesional_id,
                        a.consecutivo_procedimiento,a.tarifario_id_procedimiento,
                        a.cargo_procedimiento,a.tipo_cargo_qx_id,a.secuencia,
                        a.porcentaje,a.manual,a.tipo_equipo,a.equipo_id
                        FROM cuentas_liquidacion_cargos a,
						     cuentas_liquidaciones_qx b
                        WHERE a.cuentas_liquidacion_qx_id='".$_REQUEST['NoLiquidacion']."'
						AND   a.cuentas_liquidacion_qx_id = b.cuenta_liquidacion_qx_id";

      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        if($result->RecordCount()>0){
          while(!$result->EOF){
            $DatosCargos[]=$result->GetRowAssoc($toUpper=false);
            $result->MoveNext();
          }
        }
      }

      if($DatosCargos){
        $dbconn->BeginTrans();
        $query = "SELECT nextval('cuentas_codigos_agrupamiento_codigo_agrupamiento_id_seq')";
        $result = $dbconn->Execute($query);
        $codigoAgrupamiento=$result->fields[0];
        $query = "INSERT INTO cuentas_codigos_agrupamiento(codigo_agrupamiento_id,
                                                          descripcion,
                                                          bodegas_doc_id,
                                                          numeracion,
                                                          cuenta_liquidacion_qx_id)
                                                          VALUES('$codigoAgrupamiento',
                                                          'ACTO QUIRURGICO',
                                                          NULL,NULL,
                                                            '".$_REQUEST['NoLiquidacion']."')";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Guardar en la Base de Datos INSERT INTO cuentas_codigos_agrupamiento";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }
        for($i=0;$i<sizeof($DatosCargos);$i++){
          $query="SELECT nextval('cuentas_detalle_transaccion_seq')";
          $result=$dbconn->Execute($query);
          $Transaccion=$result->fields[0];

          $autorizacion_int=$DatosCargos[$i]['autorizacion_int'];
          if(!$autorizacion_int){$autorizacion_int1='NULL';}else{$autorizacion_int1="'$autorizacion_int'";}
          $autorizacion_ext=$DatosCargos[$i]['autorizacion_ext'];
          if(!$autorizacion_ext){$autorizacion_ext1='NULL';}else{$autorizacion_ext1="'$autorizacion_ext'";}

          $query="INSERT INTO cuentas_detalle(transaccion,empresa_id,centro_utilidad,
                  numerodecuenta,departamento,tarifario_id,
                  cargo,cantidad,precio,
                  porcentaje_descuento_empresa,valor_cargo,valor_nocubierto,
                  valor_cubierto,facturado,fecha_cargo,
                  usuario_id,fecha_registro,sw_liq_manual,
                  valor_descuento_empresa,valor_descuento_paciente,porcentaje_descuento_paciente,
                  servicio_cargo,autorizacion_int,autorizacion_ext,
                  porcentaje_gravamen,sw_cuota_paciente,sw_cuota_moderadora,
                  codigo_agrupamiento_id,consecutivo,cargo_cups,sw_cargue)
                  VALUES($Transaccion,'".$DatosCargos[$i]['empresa_id']."','".$DatosCargos[$i]['centro_utilidad']."',
                  $cuentaActiva,'".$DatosCargos[$i]['departamento']."','".$DatosCargos[$i]['tarifario_id']."',
                  '".$DatosCargos[$i]['cargo']."','".$DatosCargos[$i]['cantidad']."','".$DatosCargos[$i]['precio']."',
                  '".$DatosCargos[$i]['porcentaje_descuento_empresa']."','".$DatosCargos[$i]['valor_cargo']."','".$DatosCargos[$i]['valor_nocubierto']."',
                  '".$DatosCargos[$i]['valor_cubierto']."','".$DatosCargos[$i]['facturado']."','".$DatosCargos[$i]['fecha_cargo']."',
                  '".UserGetUID()."','".date("Y-m-d H:i:s")."','".$DatosCargos[$i]['sw_liq_manual']."',
                  '".$DatosCargos[$i]['valor_descuento_empresa']."','".$DatosCargos[$i]['valor_descuento_paciente']."','".$DatosCargos[$i]['porcentaje_descuento_paciente']."',
                  '".$DatosCargos[$i]['servicio_cargo']."',$autorizacion_int1,$autorizacion_ext1,
                  '".$DatosCargos[$i]['porcentaje_gravamen']."','".$DatosCargos[$i]['sw_cuota_paciente']."','".$DatosCargos[$i]['sw_cuota_moderadora']."',
                  '$codigoAgrupamiento',NULL,'".$DatosCargos[$i]['cargo_cups']."','".$DatosCargos[$i]['sw_cargue']."')";

          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo - INSERT INTO cuentas_detalle";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }else{
            if(!empty($DatosCargos[$i]['tipo_id_profesional'])&&!empty($DatosCargos[$i]['profesional_id'])){
              $query="INSERT INTO cuentas_detalle_profesionales(transaccion,tipo_tercero_id,tercero_id)
              VALUES($Transaccion,'".$DatosCargos[$i]['tipo_id_profesional']."','".$DatosCargos[$i]['profesional_id']."')";

              $result = $dbconn->Execute($query);
              if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
              }
            }
            
            if(!empty($DatosCargos[$i]['consecutivo_procedimiento'])){
              $query="INSERT INTO cuentas_cargos_qx_procedimientos(transaccion,consecutivo_procedimiento,
                        tarifario_id,cargo,tipo_cargo_qx_id,cuenta_liquidacion_qx_id,secuencia,porcentaje,manual)
                        VALUES($Transaccion,'".$DatosCargos[$i]['consecutivo_procedimiento']."',
                        '".$DatosCargos[$i]['tarifario_id_procedimiento']."','".$DatosCargos[$i]['cargo_procedimiento']."',
                        '".$DatosCargos[$i]['tipo_cargo_qx_id']."','".$_REQUEST['NoLiquidacion']."',
                      '".$DatosCargos[$i]['secuencia']."','".$DatosCargos[$i]['porcentaje']."','".$DatosCargos[$i]['manual']."')";

              $result = $dbconn->Execute($query);
              if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
              }
            }elseif($DatosCargos[$i]['tipo_equipo']=='F'){
              $query="UPDATE cuentas_liquidaciones_qx_equipos_fijos
              SET transaccion=$Transaccion
              WHERE cuenta_liquidacion_qx_id='".$_REQUEST['NoLiquidacion']."' AND equipo_id='".$DatosCargos[$i]['equipo_id']."'";

              $result = $dbconn->Execute($query);
              if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
              }
            }elseif($DatosCargos[$i]['tipo_equipo']=='M'){
              $query="UPDATE cuentas_liquidaciones_qx_equipos_moviles
              SET transaccion=$Transaccion
              WHERE cuenta_liquidacion_qx_id='".$_REQUEST['NoLiquidacion']."' AND equipo_id='".$DatosCargos[$i]['equipo_id']."'";

              $result = $dbconn->Execute($query);
              if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
              }
            }
          }
        }

        $query="DELETE FROM cuentas_liquidacion_cargos
        WHERE cuentas_liquidacion_qx_id='".$_REQUEST['NoLiquidacion']."'";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }else{
          $query="UPDATE cuentas_liquidaciones_qx SET estado='2'
          WHERE cuenta_liquidacion_qx_id='".$_REQUEST['NoLiquidacion']."'";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="CARGOS GUARDADOS EN LA CUENTA DEL PACIENTE";
                if(empty($_REQUEST['externo'])){
            $this->FormaMostrarDatosLiquidacion($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
                }
        return true;
      }
    }
    $this->frmError["MensajeError"]="ERROR AL GUARDAR EN LA CUENTA DEL PACIENTE";
    return true;
  }


    function EliminarCirDatosReqLiquidacion(){
    //Foreach para elimanr los diagnosticos de los procedimientos
    foreach($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_SESSION['Liquidacion_QX']['CIRUJANOS'][$_REQUEST['contadorProc']]] as $indiceProc=>$valor){
      unset($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_SESSION['Liquidacion_QX']['CIRUJANOS'][$_REQUEST['contadorProc']]][$indiceProc]][1]);
      unset($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_SESSION['Liquidacion_QX']['CIRUJANOS'][$_REQUEST['contadorProc']]][$indiceProc]][2]);
      unset($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_SESSION['Liquidacion_QX']['CIRUJANOS'][$_REQUEST['contadorProc']]][$indiceProc]][3]);
    }
        unset($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_SESSION['Liquidacion_QX']['CIRUJANOS'][$_REQUEST['contadorProc']]]);
        $limites=$_REQUEST['contadorProc']+1;
    for($i=$limites;$i<=sizeof($_SESSION['Liquidacion_QX']['CIRUJANOS']);$i++){
      $_SESSION['Liquidacion_QX']['CIRUJANOS'][$i-1]=$_SESSION['Liquidacion_QX']['CIRUJANOS'][$i];
        }
        unset($_SESSION['Liquidacion_QX']['CIRUJANOS'][$i-1]);
        $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
    }

    function LlamaInsertarProcedReqLiquidacion(){
    $this->InsertarProcedReqLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['contadorProc']);
        return true;
    }

    function SeleccionProfesionalBuscador(){
        if($_REQUEST['Filtrar']){
      $this->BuscadorProfesional($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['TipoDocumentoBus'],$_REQUEST['DocumentoBus'],$_REQUEST['NomcirujanoBus']);
            return true;
        }
    if($_REQUEST['cirSeleccionado']){
      if(sizeof($_SESSION['Liquidacion_QX']['CIRUJANOS'])>0){
              $cont=sizeof($_SESSION['Liquidacion_QX']['CIRUJANOS'])+1;
            }else{
                $cont=1;
            }
            $_SESSION['Liquidacion_QX']['CIRUJANOS'][$cont]=$_REQUEST['cirSeleccionado'];
        }
        $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
    }

    function SeleccionProcedimientoQX(){
    if($_REQUEST['filtrar']){
      $this->InsertarProcedReqLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['contadorProc'],$_REQUEST['procedimientoBus'],$_REQUEST['codigoBus'],$_REQUEST['tipoProcedimiento']);
            return true;
        }
    $encontro=0;
        if($_REQUEST['procedimientoSelect']){
      foreach($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'] as $cirujano=>$vector){
        foreach($vector as $contador=>$procedimiento){
          if($procedimiento!=$_REQUEST['procedimientoSelect']){
            $encontro=0;
          }else{
            $encontro=1;
            break;
          }
        }
      }
      if($encontro==0){
        if(sizeof($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_SESSION['Liquidacion_QX']['CIRUJANOS'][$_REQUEST['contadorProc']]])>0){
          $cont=sizeof($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_SESSION['Liquidacion_QX']['CIRUJANOS'][$_REQUEST['contadorProc']]])+1;
        }else{
          $cont=1;
        }
        $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_SESSION['Liquidacion_QX']['CIRUJANOS'][$_REQUEST['contadorProc']]][$cont]=$_REQUEST['procedimientoSelect'];
      }
            (list($cargo,$proc,$swtche)=explode('||//',$_REQUEST['procedimientoSelect']));
            $_SESSION['Liquidacion_QX']['ULTIMO_PROCEDIMIENTO']=$cargo;
    }
        $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;

    }

/**
* Funcion que consulta de la base de tado los tipos de cargos agrupados
* @return array
*/
    function tiposdeProcedimientos(){
    list($dbconn) = GetDBconn();
        $query="SELECT a.tipo_cargo,a.grupo_tipo_cargo,a.descripcion
        FROM tipos_cargos a,qx_grupos_tipo_cargo b
        WHERE a.grupo_tipo_cargo=b.grupo_tipo_cargo";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

    function BusquedaProcedimientosQX($tipoProcedimiento,$codigoBus,$procedimientoBus){

        list($dbconn) = GetDBconn();
        $query="SELECT d.grupo_tipo_cargo,d.cargo,d.descripcion,d.sw_bilateral
        FROM qx_grupos_tipo_cargo a,tipos_cargos c,cups d
        WHERE a.grupo_tipo_cargo=c.grupo_tipo_cargo AND
        c.grupo_tipo_cargo=d.grupo_tipo_cargo AND c.tipo_cargo=d.tipo_cargo";
    if(!empty($tipoProcedimiento) && $tipoProcedimiento!=-1){
          (list($val,$descrip)=explode('/',$tipoProcedimiento));
          $query.=" AND c.tipo_cargo='".$val."'";
        }
    if($codigoBus){
      $query.=" AND d.cargo='".$codigoBus."'";
        }
        if($procedimientoBus){
      $query.=" AND d.descripcion LIKE '%".strtoupper($procedimientoBus)."%'";
    }
        $query.=" ORDER BY d.descripcion";
        if(empty($_REQUEST['conteo'])){
          $result = $dbconn->Execute($query);
            if($result->EOF){
                $this->error = "Error al ejecutar la consulta.<br>";
                $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
                return false;
            }
            $this->conteo=$result->RecordCount();
    }else{
      $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of']){
        $Of='0';
        }else{
       $Of=$_REQUEST['Of'];
        }
        $query.=" LIMIT " . $this->limit . " OFFSET $Of";
        $result = $dbconn->Execute($query);
        if($result->EOF){
            $this->error = "Error al ejecutar la consulta.<br>";
            $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $vars;
    }

    /**
* Funcion que consulta de la base de datos los tipos de ambito que puede tener una cirugia
* @return array
*/
    function TiposdeAmbitosdeCirugia(){
    list($dbconn) = GetDBconn();
        $query = "SELECT ambito_cirugia_id,descripcion
        FROM qx_ambitos_cirugias";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          if($result->RecordCount()){
                while(!$result->EOF) {
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

    function tipocirugia(){

        list($dbconn) = GetDBconn();
        $query= "SELECT tipo_cirugia_id,descripcion FROM qx_tipos_cirugia";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          if($result->RecordCount()){
                while(!$result->EOF){
                    $vector[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vector;
    }

    function tipofinalidad(){

        list($dbconn) = GetDBconn();
        $query= "SELECT finalidad_procedimiento_id,descripcion FROM qx_finalidades_procedimientos";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          if($result->RecordCount()){
                while(!$result->EOF){
                    $vector[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vector;
    }

/**
* Funcion que busca los profesionales Ayudantes existentes en la base de datos
* @return array
*/
    function profesionalesAyudantes(){
        list($dbconn) = GetDBconn();
        $query = "SELECT x.tercero_id,z.nombre_tercero as nombre,x.tipo_id_tercero
        FROM profesionales x,terceros z
        WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND x.estado='1' AND
        x.tercero_id=z.tercero_id AND x.tipo_id_tercero=z.tipo_id_tercero ORDER BY z.nombre_tercero";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->RecordCount()){
                while(!$result->EOF) {
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

/**
* Funcion que busca en los profesionales especialistas anestesiologos existentes en la base de datos
* @return array
*/
    function profesionalesEspecialistaAnestecistas(){
        list($dbconn) = GetDBconn();
        $query = "SELECT  x.tercero_id,c.nombre_tercero as nombre,x.tipo_id_tercero
        FROM profesionales x,especialidades z,profesionales_especialidades l,terceros c
        WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND z.especialidad=l.especialidad AND x.estado='1' AND
        z.sw_anestesiologo='1' AND x.tercero_id=l.tercero_id AND x.tipo_id_tercero=l.tipo_id_tercero  AND
        x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->RecordCount()){
                while (!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

/**
* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
* @return array
*/
  function TiposDeAnestesias(){

        list($dbconn) = GetDBconn();
        $query = "SELECT qx_tipo_anestesia_id,descripcion,sw_uso_gases
        FROM qx_tipos_anestesia";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->RecordCount()){
        while (!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
              }
            }
        }
        $result->Close();
        return $vars;
    }

    /**
* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
* @return array
*/
  function TiposDeSalas(){

        list($dbconn) = GetDBconn();
        $query = "SELECT tipo_sala_id,descripcion,(CASE WHEN tipo_sala_id='01' THEN '1' ELSE '0' END) as sw_quirofano
        FROM qx_tipos_salas";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->RecordCount()){
        while (!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
              }
            }
        }
        $result->Close();
        return $vars;
    }

/**
* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
* @return array
*/
  function TiposGasesAnestesicos(){

        list($dbconn) = GetDBconn();
        $query = "SELECT tipo_gas_id,descripcion
        FROM tipos_gases";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      if($result->RecordCount()){
                while(!$result->EOF){
                    $vars[$result->fields[0]]=$result->fields[1];
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }
    
/**
* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
* @return array
*/
  function TiposMetodosSuministrosGases(){

        list($dbconn) = GetDBconn();
        $query = "SELECT tipo_suministro_id,descripcion
        FROM tipos_metodos_suministro_gases";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      if($result->RecordCount()){
                while(!$result->EOF){
                    $vars[$result->fields[0]]=$result->fields[1];
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }
    


/**
* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
* @return array
*/
  function TiposQuirofanosTotal(){

        list($dbconn) = GetDBconn();
        $query = "SELECT quirofano,descripcion FROM qx_quirofanos WHERE estado='1'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      if($result->RecordCount()>0){
                while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

        /**
* Funcion que retorna los tipo de gases anestesicos existentes en la base de datos
* @return array
*/
  function TiposPolitraumasBD(){

        list($dbconn) = GetDBconn();
        $query = "SELECT  politraumatismo_id,descripcion
        FROM qx_tipos_politraumatismo";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      if($result->RecordCount()){
                while(!$result->EOF){
                    $vars[$result->fields[0]]=$result->fields[1];
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

    function EliminarProDatosReqLiquidacion(){
      $limites=$_REQUEST['indice']+1;
    unset($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_REQUEST['cirujanoArray']][$_REQUEST['indice']]][1]);
    unset($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_REQUEST['cirujanoArray']][$_REQUEST['indice']]][2]);
    unset($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_REQUEST['cirujanoArray']][$_REQUEST['indice']]][3]);
    for($i=$limites;$i<=sizeof($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_REQUEST['cirujanoArray']]);$i++){
      $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_REQUEST['cirujanoArray']][$i-1]=$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_REQUEST['cirujanoArray']][$i];
        }
        unset($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_REQUEST['cirujanoArray']][$i-1]);
        $_SESSION['Liquidacion_QX']['ULTIMO_PROCEDIMIENTO']=$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$_REQUEST['cirujanoArray']][$i];
        $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
    }

    /**
* Funcion que busca los profesionales Ayudantes existentes en la base de datos
* @return array
*/
    function viaAccesoSegunProcedimientos(){
        list($dbconn) = GetDBconn();
        $cantidadProcedimientos=0;
        foreach($_SESSION['Liquidacion_QX']['CIRUJANOS'] as $indice=>$cirujano)
	{
      		$cantidadProcedimientos+=sizeof($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujano]);
		//echo '<br><br>sizeof: '.$cantidadProcedimientos;
        }
        if(($cantidadProcedimientos<2) || empty($_SESSION['Liquidacion_QX']['PROCEDIMIENTOS']))
	{
            $query = "SELECT via_acceso,descripcion
            FROM qx_vias_acceso
            WHERE (via_acceso='1' OR via_acceso='6')";
        }else
	{
	        //echo '<br><br>sizeof cirujanos: '.sizeof($_SESSION['Liquidacion_QX']['CIRUJANOS']);
      		if(sizeof($_SESSION['Liquidacion_QX']['CIRUJANOS'])>1)
		{
                $query = "SELECT via_acceso,descripcion
                FROM qx_vias_acceso
                WHERE (via_acceso='2' OR via_acceso='4')";
            	}else
		{
                $query = "SELECT via_acceso,descripcion
                FROM qx_vias_acceso
                WHERE (via_acceso='3' OR via_acceso='5')";
            	}
        }
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->RecordCount()){
                while(!$result->EOF) {
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

  function LlamaBuscadorDiagnosticos(){
    $this->BuscadorDiagnosticos($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['procedimiento'],$_REQUEST['numDiagnostico']);
    return true;
  }

  /**
* Funcion que busca en los profesionales especialistas existentes en la base de datos
* @return array
*/
    function DiagnosticosBD($codigoBus,$descripcionBus){

        list($dbconn) = GetDBconn();
        $query = "SELECT diagnostico_id,diagnostico_nombre FROM diagnosticos";
        if($codigoBus || $descripcionBus){
      $query.=" WHERE ";
      if($codigoBus){
        $query.=" diagnostico_id LIKE '%".strtoupper($codigoBus)."%'";
        $and=1;
      }
      if($descripcionBus){
        if($and==1){
          $query.=" AND ";
        }
        $query.=" diagnostico_nombre LIKE '%".strtoupper($descripcionBus)."%'";
      }
    }
        if(empty($_REQUEST['conteo'])){
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->conteo=$result->RecordCount();
        }else{
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of']){
            $Of='0';
        }else{
            $Of=$_REQUEST['Of'];
        }
        $query.=" LIMIT " . $this->limit . " OFFSET $Of";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        return $vars;
    }

  function SeleccionDiagnosticoBuscador(){
    if($_REQUEST['Filtrar']){
      $this->BuscadorDiagnosticos($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['procedimiento'],$_REQUEST['numDiagnostico'],$_REQUEST['codigoBus'],$_REQUEST['descripcionBus']);
      return true;
    }
    if($_REQUEST['cetinela']){
      if($_REQUEST['numDiagnostico']==1){
        $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_REQUEST['procedimiento']][1]=$_REQUEST['codigoDiagnostico'].'||//'.$_REQUEST['diagnostico_nombre'];
      }
      if($_REQUEST['numDiagnostico']==2){
        $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_REQUEST['procedimiento']][2]=$_REQUEST['codigoDiagnostico'].'||//'.$_REQUEST['diagnostico_nombre'];
      }
      if($_REQUEST['numDiagnostico']==3){
        $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_REQUEST['procedimiento']][3]=$_REQUEST['codigoDiagnostico'].'||//'.$_REQUEST['diagnostico_nombre'];
      }
    }
    $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
    return true;
  }

  function BuscarDatosLiquidaciones($TipoDocumento,$Documento,$NoIngreso,$NoCuenta,$Estado,$FechaCirugia){

    list($dbconn) = GetDBconn();
    
     
                                         
    
    //Adicion de filtro para query
    $query = "SELECT a.cuenta_liquidacion_qx_id,c.tipo_id_paciente,c.paciente_id,
    c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre,
    a.fecha_cirugia,a.duracion_cirugia,a.estado,a.ingreso,a.numerodecuenta,pl.plan_descripcion,
    (CASE WHEN (SELECT count(*) FROM qx_documentos_iym_cirugia WHERE cuenta_liquidacion_qx_id=a.cuenta_liquidacion_qx_id AND estado='0') > 0 THEN '1' ELSE '0' END) as documentos_in,        
    a.programacion_id
    FROM cuentas_liquidaciones_qx a, cuentas cta,
    ingresos b, pacientes c, planes pl
    WHERE cta.numerodecuenta=a.numerodecuenta AND (cta.estado='1' OR cta.estado='2') AND
    a.ingreso=b.ingreso AND b.estado IN ('0','1','2') AND b.ingreso=cta.ingreso
    AND b.tipo_id_paciente=c.tipo_id_paciente AND b.paciente_id=c.paciente_id AND
    a.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' AND
    cta.plan_id=pl.plan_id";

    if($TipoDocumento!=-1 && !empty($TipoDocumento) && !empty($Documento)){
      $query.=" AND c.tipo_id_paciente='".$TipoDocumento."' AND c.paciente_id='".$Documento."'";
    }
    if($NoIngreso){
      $query.=" AND a.ingreso='$NoIngreso'";
    }
    if($NoCuenta){
      $query.=" AND a.numerodecuenta='$NoCuenta'";
    }
    if($Estado==1){
      $query.=" AND (a.estado='0' OR a.estado='1' OR a.estado='2' OR (a.estado='3' AND 
                        (
                          SELECT COUNT(*)
                          FROM cuentas_codigos_agrupamiento x,cuentas_detalle y
                          WHERE a.numerodecuenta=y.numerodecuenta
                          AND y.cargo='IMD'
                          AND x.codigo_agrupamiento_id=y.codigo_agrupamiento_id
                          AND x.cuenta_liquidacion_qx_id=a.cuenta_liquidacion_qx_id
                        )>1))";
    }
    if($Estado==2  OR empty($Estado)){
      $query.=" AND a.estado='0'";
    }
    if($Estado==3){
      $query.=" AND a.estado='1'";
    }
    if($Estado==4){
      $query.=" AND a.estado='2'";
    }
    if($Estado==5){
      $query.=" AND a.estado='3'";
    }
    if($FechaCirugia){
      (list($dia,$mes,$ano)=explode('/',$FechaCirugia));
      $query.=" AND date(a.fecha_cirugia)='".$ano."-".$mes."-".$dia."'";
    }
    $query.=" ORDER BY a.fecha_registro DESC,a.estado";
    
        if(empty($_REQUEST['conteo'])){
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->conteo=$result->RecordCount();
        }else{
            $this->conteo=$_REQUEST['conteo'];
        }
        $query.=" LIMIT 20 OFFSET ".$this->offset."";        
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        return $vars;
        /*
        (CASE WHEN (SELECT count(*) 
        FROM cuentas_codigos_agrupamiento ctagrupa, cuentas_detalle ctadet 
        WHERE ctadet.numerodecuenta=cta.numerodecuenta AND
        ctadet.codigo_agrupamiento_id=ctagrupa.codigo_agrupamiento_id AND
        ctagrupa.cuenta_liquidacion_qx_id=a.cuenta_liquidacion_qx_id
        AND ctadet.cargo='IMD') > 0 THEN '1' ELSE '0' END) as insumos,
        */
  }

  function LlamaModificarLiquidacion(){

    list($dbconn) = GetDBconn();
        $query="SELECT a.ingreso,a.numerodecuenta,a.finalidad,
    a.ambito_cirugia_id,a.via_acceso,a.tipo_cirugia_id,
    a.fecha_cirugia,a.duracion_cirugia,a.tipo_sala_id,
    a.tipo_id_ayudante,a.ayudante_id,a.tipo_id_anestesiologo,
    a.anestesiologo_id,a.sw_politrauma,
    a.qx_tipo_anestesia_id,
    (CASE WHEN b.tipo_sala_id='01' THEN '1' ELSE '0' END) as sw_quirofano,c.sw_uso_gases,
    d.tipo_id_paciente,d.paciente_id,
    e.primer_nombre||' '||e.segundo_nombre||' '||e.primer_apellido||' '||e.segundo_apellido as nombre,
    a.quirofano,a.tipo_politrauma,a.ayudante_igual_especialidad,a.minutos_recuperacion
    FROM cuentas_liquidaciones_qx a
    LEFT JOIN qx_tipos_anestesia c ON(a.qx_tipo_anestesia_id=c.qx_tipo_anestesia_id),
    qx_tipos_salas b,ingresos d,pacientes e
    WHERE a.cuenta_liquidacion_qx_id='".$_REQUEST['liquidacionId']."' AND
    a.tipo_sala_id=b.tipo_sala_id AND
    a.ingreso=d.ingreso AND d.tipo_id_paciente=e.tipo_id_paciente AND d.paciente_id=e.paciente_id";

    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $vars=$result->GetRowAssoc($toUpper=false);
        }
    $_SESSION['Liquidacion_QX']['LIQUIDACION_ID']=$_REQUEST['liquidacionId'];
    $_SESSION['Liquidacion_QX']['VIA_ACCESO']=$vars['via_acceso'];
    $_SESSION['Liquidacion_QX']['POLITRAUMATISMO']=$vars['sw_politrauma'];
    $_SESSION['Liquidacion_QX']['TIPO_POLITRAUMA']=$vars['tipo_politrauma'];
    $_SESSION['Liquidacion_QX']['AYUDANTE']=$vars['tipo_id_ayudante'].'||//'.$vars['ayudante_id'];
        $_SESSION['Liquidacion_QX']['AYUDANTE_IGUAL_ESP']=$vars['ayudante_igual_especialidad'];
    $_SESSION['Liquidacion_QX']['ANESTESIOLOGO']=$vars['tipo_id_anestesiologo'].'||//'.$vars['anestesiologo_id'];
    $_SESSION['Liquidacion_QX']['TIPO_ANESTESIA']=$vars['qx_tipo_anestesia_id'].'/'.$vars['sw_uso_gases'];
    if($vars['sw_uso_gases']='1'){
      $_SESSION['Liquidacion_QX']['NO_GAS']=1;
    }
    //$_SESSION['Liquidacion_QX']['GAS_ANESTESICO']=$vars['qx_gas_anestesico'];
    //$_SESSION['Liquidacion_QX']['GAS_ANESTESICO_ME']=$vars['qx_gas_medicinal'];
    //$_SESSION['Liquidacion_QX']['DURACION_GAS']=$vars['minutos_duracion_gas'];
    $_SESSION['Liquidacion_QX']['TIPO_SALA']=$vars['tipo_sala_id'].'/'.$vars['sw_quirofano'];
    if($vars['sw_quirofano']=='1'){
      $_SESSION['Liquidacion_QX']['NO_QUIRO']=1;
    }
    $_SESSION['Liquidacion_QX']['QUIROFANO']=$vars['quirofano'];
    (list($fechaCir,$horaCir)=explode(' ',$vars['fecha_cirugia']));
    (list($ano,$mes,$dia)=explode('-',$fechaCir));
    $_SESSION['Liquidacion_QX']['FECHA_CIRUGIA']=$dia.'/'.$mes.'/'.$ano;
    (list($hora,$min)=explode(':',$horaCir));
    $_SESSION['Liquidacion_QX']['HORA_INICIO']=$hora;
    $_SESSION['Liquidacion_QX']['MIN_INICIO']=$min;
    (list($horaDur,$minDur)=explode(':',$vars['duracion_cirugia']));
    $_SESSION['Liquidacion_QX']['HORA_DURACION']=$horaDur;
    $_SESSION['Liquidacion_QX']['MIN_DURACION']=$minDur;
    $_SESSION['Liquidacion_QX']['AMBITO_CIRUGIA']=$vars['ambito_cirugia_id'];
    $_SESSION['Liquidacion_QX']['TIPO_CIRUGIA']=$vars['tipo_cirugia_id'];
    $_SESSION['Liquidacion_QX']['FINALIDAD_CIRUGIA']=$vars['finalidad'];
    $_SESSION['Liquidacion_QX']['RECUPERACION']=$vars['minutos_recuperacion'];

    $query="SELECT a.tipo_id_cirujano,a.cirujano_id,a.consecutivo_procedimiento,a.cargo_cups,a.sw_bilateral as cargobilateral,c.sw_bilateral,a.diagnostico_uno,a.diagnostico_dos,a.complicacion,
    b.nombre_tercero,c.descripcion,x.diagnostico_nombre as diagnosticouno,y.diagnostico_nombre as diagnosticodos,z.diagnostico_nombre as nom_complicacion
    FROM cuentas_liquidaciones_qx_procedimientos a
    LEFT JOIN diagnosticos x ON(a.diagnostico_uno=x.diagnostico_id)
    LEFT JOIN diagnosticos y ON(a.diagnostico_dos=y.diagnostico_id)
    LEFT JOIN diagnosticos z ON(a.complicacion=z.diagnostico_id)
    ,terceros b,cups c
    WHERE a.cuenta_liquidacion_qx_id='".$_REQUEST['liquidacionId']."' AND
    a.tipo_id_cirujano=b.tipo_id_tercero AND a.cirujano_id=b.tercero_id AND
    a.cargo_cups=c.cargo
    ORDER BY a.cirujano_id,a.tipo_id_cirujano";

    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      while(!$result->EOF){
              $vars1[]=$result->GetRowAssoc($toUpper=false);
        $result->MoveNext();
      }
      if(sizeof($vars1)>0){
        $cirAnt=-1;
        $cont=1;

        for($i=0;$i<sizeof($vars1);$i++){

          if($vars1[$i]['tipo_id_cirujano'].'-'.$vars1[$i]['cirujano_id']!=$cirAnt){
            $j=1;
            $_SESSION['Liquidacion_QX']['CIRUJANOS'][$cont]=$vars1[$i]['tipo_id_cirujano'].'||//'.$vars1[$i]['cirujano_id'].'||//'.$vars1[$i]['nombre_tercero'];
            $cont++;
            $cirAnt=$vars1[$i]['tipo_id_cirujano'].'-'.$vars1[$i]['cirujano_id'];
          }
          $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$vars1[$i]['tipo_id_cirujano'].'||//'.$vars1[$i]['cirujano_id'].'||//'.$vars1[$i]['nombre_tercero']][$j]=$vars1[$i]['cargo_cups'].'||//'.$vars1[$i]['descripcion'].'||//'.$vars1[$i]['sw_bilateral'];
          $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_BILATERAL'][$vars1[$i]['cargo_cups']]=$vars1[$i]['cargobilateral'];
          if(!empty($vars1[$i]['diagnostico_uno']) && !empty($vars1[$i]['diagnosticouno'])){
          $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$vars1[$i]['cargo_cups'].'||//'.$vars1[$i]['descripcion'].'||//'.$vars1[$i]['sw_bilateral']][1]=$vars1[$i]['diagnostico_uno'].'||//'.$vars1[$i]['diagnosticouno'];
          }
          if(!empty($vars1[$i]['diagnostico_dos']) && !empty($vars1[$i]['diagnosticodos'])){
          $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$vars1[$i]['cargo_cups'].'||//'.$vars1[$i]['descripcion'].'||//'.$vars1[$i]['sw_bilateral']][2]=$vars1[$i]['diagnostico_dos'].'||//'.$vars1[$i]['diagnosticodos'];
          }
          if(!empty($vars1[$i]['complicacion']) && !empty($vars1[$i]['complicacion'])){
          $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$vars1[$i]['cargo_cups'].'||//'.$vars1[$i]['descripcion'].'||//'.$vars1[$i]['sw_bilateral']][3]=$vars1[$i]['complicacion'].'||//'.$vars1[$i]['nom_complicacion'];
          }
                    if((sizeof($vars1)-1)==$i){
                        $_SESSION['Liquidacion_QX']['ULTIMO_PROCEDIMIENTO']=$vars1[$i]['cargo_cups'];
                    }
          $j++;
        }
      }
      $query="SELECT a.tipo_gas_id,b.descripcion as nom_tipo_gas_id,
      a.tipo_suministro_id,c.descripcion as nom_tipo_suministro_id,
      d.frecuencia_id,a.tiempo_suministro,d.unidad
      FROM cuentas_liquidaciones_qx_gases_anestesicos a
      JOIN  tipos_gases b ON(a.tipo_gas_id=b.tipo_gas_id)
      JOIN tipos_metodos_suministro_gases c ON(a.tipo_suministro_id=c.tipo_suministro_id)
      JOIN tipos_frecuencia_gases d ON(a.tipo_suministro_id=d.tipo_suministro_id AND a.frecuencia_id=d.frecuencia_id)      
      WHERE a.cuenta_liquidacion_qx_id='".$_REQUEST['liquidacionId']."' AND 
      a.transaccion_cuenta IS NULL";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }else{
        while(!$result->EOF){
          $vars2[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
        for($i=0;$i<sizeof($vars2);$i++){
          $_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGas']=$vars2[$i]['tipo_gas_id'];           
          $_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGasDes']=$vars2[$i]['nom_tipo_gas_id'];           
          $_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGas']=$vars2[$i]['tipo_suministro_id'];           
          $_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGasDes']=$vars2[$i]['nom_tipo_suministro_id'];            
          $_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGas']=$vars2[$i]['frecuencia_id'];           
          $_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGasDes']=$vars2[$i]['frecuencia_id'].' '.$vars2[$i]['unidad'];           
          $_SESSION['Liquidacion_QX']['GASES'][$i]['MinutosGas']=$vars2[$i]['tiempo_suministro'];                                       
        }
      }
    }  
    $this->DatosRequeridosLiquidacion($vars['tipo_id_paciente'],$vars['paciente_id'],$vars['nombre'],$vars['numerodecuenta'],$vars['ingreso']);
        return true;
  }

  function DatosCargoCirugia($NoLiquidacion,$estado){
    GLOBAL $ADODB_FETCH_MODE;
    list($dbconn) = GetDBconn();
    if($estado=='2'){
/*		   $query="SELECT d.tipo_id_cirujano,d.cirujano_id,d.cargo_cups,
					d.consecutivo_procedimiento,c.tarifario_id as tarifario_id_procedimiento,
					c.cargo as cargo_procedimiento,c.tipo_cargo_qx_id,b.tarifario_id,
					b.cargo,c.porcentaje,c.secuencia,b.valor_nocubierto,
					b.valor_cubierto,e.tipo_tercero_id as tipo_id_profesional,
					e.tercero_id as profesional_id,f.descripcion,uv.uvrs,
					b.facturado
			FROM cuentas_codigos_agrupamiento a,cuentas_detalle b
				LEFT JOIN cuentas_detalle_profesionales e 
							ON (b.transaccion=e.transaccion)
				,cuentas_cargos_qx_procedimientos c
				JOIN cuentas_liquidaciones_qx_procedimientos_cargos uv 
				ON (uv.consecutivo_procedimiento = c.consecutivo_procedimiento 
						AND uv.tarifario_id = c.tarifario_id 
						AND uv.cargo = c.cargo)
						,cuentas_liquidaciones_qx_procedimientos d,
						tarifarios_detalle f,
						tipos_cargos_qx g
			WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' 
			AND a.descripcion='ACTO QUIRURGICO' 
			AND a.bodegas_doc_id IS NULL 
			AND a.numeracion IS NULL 
			AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id 
			AND b.transaccion=c.transaccion 
			AND c.consecutivo_procedimiento=d.consecutivo_procedimiento 
			AND c.cargo=f.cargo 
			AND c.tarifario_id=f.tarifario_id 
			AND c.tipo_cargo_qx_id=g.tipo_cargo_qx_id
			ORDER BY c.secuencia,g.indice_de_orden;";*/
			$query="SELECT d.tipo_id_cirujano,d.cirujano_id,d.cargo_cups,
					d.consecutivo_procedimiento,c.tarifario_id as tarifario_id_procedimiento,
					c.cargo as cargo_procedimiento,c.tipo_cargo_qx_id,b.tarifario_id,
					b.cargo,c.porcentaje,c.secuencia,b.valor_nocubierto,
					b.valor_cubierto,e.tipo_tercero_id as tipo_id_profesional,
					e.tercero_id as profesional_id,f.descripcion,uv.uvrs,
					b.facturado
			FROM cuentas_codigos_agrupamiento a,
			 	cuentas_liquidaciones_qx CLQX,
				cuentas_detalle b
				LEFT JOIN cuentas_detalle_profesionales e 
							ON (b.transaccion=e.transaccion)
				,cuentas_cargos_qx_procedimientos c
				JOIN cuentas_liquidaciones_qx_procedimientos_cargos uv 
				ON (uv.consecutivo_procedimiento = c.consecutivo_procedimiento 
						AND uv.tarifario_id = c.tarifario_id 
						AND uv.cargo = c.cargo)
						,cuentas_liquidaciones_qx_procedimientos d,
						tarifarios_detalle f,
						tipos_cargos_qx g
			WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' 
			AND a.cuenta_liquidacion_qx_id = CLQX.cuenta_liquidacion_qx_id
			AND CLQX.numerodecuenta = b.numerodecuenta
			AND a.descripcion='ACTO QUIRURGICO' 
			AND a.bodegas_doc_id IS NULL 
			AND a.numeracion IS NULL 
			AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id 
			AND b.transaccion=c.transaccion 
			AND c.consecutivo_procedimiento=d.consecutivo_procedimiento 
			AND c.cargo=f.cargo 
			AND c.tarifario_id=f.tarifario_id 
			AND c.tipo_cargo_qx_id=g.tipo_cargo_qx_id
			ORDER BY c.secuencia,g.indice_de_orden;";
    }else{
      $query="SELECT b.tipo_id_cirujano,b.cirujano_id,b.cargo_cups,a.consecutivo_procedimiento,
      a.tarifario_id_procedimiento,a.cargo_procedimiento,
      a.tipo_cargo_qx_id,a.tarifario_id,a.cargo,a.porcentaje,a.secuencia,a.valor_nocubierto,a.valor_cubierto,
      a.tipo_id_profesional,a.profesional_id,e.descripcion,c.uvrs,a.facturado
      FROM cuentas_liquidacion_cargos a,cuentas_liquidaciones_qx_procedimientos b,
      cuentas_liquidaciones_qx_procedimientos_cargos c,tipos_cargos_qx d,tarifarios_detalle e
      WHERE a.cuentas_liquidacion_qx_id='".$NoLiquidacion."'  AND
      a.consecutivo_procedimiento=b.consecutivo_procedimiento AND
      a.consecutivo_procedimiento=c.consecutivo_procedimiento AND
      a.tarifario_id_procedimiento=c.tarifario_id AND a.cargo_procedimiento=c.cargo AND
      a.tipo_cargo_qx_id=d.tipo_cargo_qx_id AND c.cargo=e.cargo AND c.tarifario_id=e.tarifario_id
      ORDER BY a.secuencia,d.indice_de_orden";
    }
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

    while($cargo=$result->FetchRow()){

      $secuencia=explode('-',$cargo['secuencia']);
      $v[$secuencia[0]][$secuencia[1]]['tipo_id_cirujano']=$cargo['tipo_id_cirujano'];
      $v[$secuencia[0]][$secuencia[1]]['cirujano_id']=$cargo['cirujano_id'];
      $v[$secuencia[0]][$secuencia[1]]['consecutivo_procedimiento']=$cargo['consecutivo_procedimiento'];
      $v[$secuencia[0]][$secuencia[1]]['cargo_cups']=$cargo['cargo_cups'];
      $v[$secuencia[0]][$secuencia[1]]['tarifario_id']=$cargo['tarifario_id_procedimiento'];
      $v[$secuencia[0]][$secuencia[1]]['cargo']=$cargo['cargo_procedimiento'];
      $v[$secuencia[0]][$secuencia[1]]['descripcion']=$cargo['descripcion'];
            $v[$secuencia[0]][$secuencia[1]]['uvrs']=$cargo['uvrs'];

      $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['tarifario_id']=$cargo['tarifario_id'];
      $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['cargo']=$cargo['cargo'];
      $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['valor_cubierto']=$cargo['valor_cubierto'];
      $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['valor_no_cubierto']=$cargo['valor_nocubierto'];
      $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['PORCENTAJE']=$cargo['porcentaje'];
      $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['SECUENCIA']=$cargo['secuencia'];
			$v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['facturado']=$cargo['facturado'];
      if(!empty($cargo['tipo_id_profesional']) && !empty($cargo['profesional_id'])){
        $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['tipo_id_tercero']=$cargo['tipo_id_profesional'];
        $v[$secuencia[0]][$secuencia[1]]['liquidacion'][$cargo['tipo_cargo_qx_id']]['tercero_id']=$cargo['profesional_id'];
      }
    }
    $_SESSION['ARREGLO_LIQUIDACIONQX_CARGOS']=$v;

    if($estado=='2'){
      $query="SELECT a.*
      FROM (SELECT c.precio as precio_plan,c.cantidad,c.valor_cargo,c.valor_cubierto,c.valor_nocubierto as valor_no_cubierto,c.porcentaje_gravamen,
      e.descripcion,c.tarifario_id,c.cargo,c.facturado,c.sw_cuota_paciente,c.sw_cuota_moderadora,c.valor_descuento_empresa,
      c.valor_descuento_paciente,'fijo' as tipo_equipo,b.equipo_id,
      (SELECT te.descripcion FROM qx_equipos_quirofanos te WHERE te.equipo_id=b.equipo_id) as descripcion_equipo,
      b.duracion,c.cargo_cups
      FROM cuentas_liquidaciones_qx_equipos_fijos b,
      cuentas_codigos_agrupamiento a,cuentas_detalle c,tarifarios_detalle e
      WHERE b.cuenta_liquidacion_qx_id='".$NoLiquidacion."'  AND
      b.cuenta_liquidacion_qx_id=a.cuenta_liquidacion_qx_id AND
      a.codigo_agrupamiento_id=c.codigo_agrupamiento_id AND
      b.transaccion=c.transaccion AND
      c.cargo=e.cargo AND c.tarifario_id=e.tarifario_id
      UNION
      SELECT c.precio as precio_plan,c.cantidad,c.valor_cargo,c.valor_cubierto,c.valor_nocubierto as valor_no_cubierto,c.porcentaje_gravamen,
      e.descripcion,c.tarifario_id,c.cargo,c.facturado,c.sw_cuota_paciente,c.sw_cuota_moderadora,c.valor_descuento_empresa,
      c.valor_descuento_paciente,'movil' as tipo_equipo,b.equipo_id,
      (SELECT te.descripcion FROM qx_equipos_moviles te WHERE te.equipo_id=b.equipo_id) as descripcion_equipo,
      b.duracion,c.cargo_cups
      FROM cuentas_liquidaciones_qx_equipos_moviles b,
      cuentas_codigos_agrupamiento a,cuentas_detalle c,tarifarios_detalle e
      WHERE b.cuenta_liquidacion_qx_id='".$NoLiquidacion."'  AND
      b.cuenta_liquidacion_qx_id=a.cuenta_liquidacion_qx_id AND
      a.codigo_agrupamiento_id=c.codigo_agrupamiento_id AND
      b.transaccion=c.transaccion AND
      c.cargo=e.cargo AND c.tarifario_id=e.tarifario_id
      ) a
      ORDER BY a.tipo_equipo";

    }else{
      $query="(SELECT a.*
      FROM (SELECT c.precio as precio_plan,c.cantidad,c.valor_cargo,c.valor_cubierto,c.valor_nocubierto as valor_no_cubierto,c.porcentaje_gravamen,
      e.descripcion,c.tarifario_id,c.cargo,c.facturado,c.sw_cuota_paciente,c.sw_cuota_moderadora,c.valor_descuento_empresa,
      c.valor_descuento_paciente,'fijo' as tipo_equipo,b.equipo_id,
      (SELECT te.descripcion FROM qx_equipos_quirofanos te WHERE te.equipo_id=b.equipo_id) as descripcion_equipo,
      b.duracion,c.cargo_cups,c.facturado
      FROM cuentas_liquidaciones_qx_equipos_fijos b,
      cuentas_liquidacion_cargos c,tarifarios_detalle e
      WHERE b.cuenta_liquidacion_qx_id='".$NoLiquidacion."'  AND
      b.cuenta_liquidacion_qx_id=c.cuentas_liquidacion_qx_id AND
      b.equipo_id=c.equipo_id AND c.tipo_equipo='F' AND
      c.cargo=e.cargo AND c.tarifario_id=e.tarifario_id AND c.consecutivo_procedimiento IS NULL
      UNION
      SELECT c.precio,c.cantidad,c.valor_cargo,c.valor_cubierto,c.valor_nocubierto as valor_no_cubierto,c.porcentaje_gravamen,
      e.descripcion,c.tarifario_id,c.cargo,c.facturado,c.sw_cuota_paciente,c.sw_cuota_moderadora,c.valor_descuento_empresa,
      c.valor_descuento_paciente,'movil' as tipo_equipo,b.equipo_id,
      (SELECT te.descripcion FROM qx_equipos_moviles te WHERE te.equipo_id=b.equipo_id) as descripcion_equipo,
      b.duracion,c.cargo_cups,c.facturado
      FROM cuentas_liquidaciones_qx_equipos_moviles b,
      cuentas_liquidacion_cargos c,tarifarios_detalle e
      WHERE b.cuenta_liquidacion_qx_id='".$NoLiquidacion."'  AND
      b.cuenta_liquidacion_qx_id=c.cuentas_liquidacion_qx_id AND
      b.equipo_id=c.equipo_id AND c.tipo_equipo='M' AND
      c.cargo=e.cargo AND c.tarifario_id=e.tarifario_id AND c.consecutivo_procedimiento IS NULL
      ) a
      ORDER BY a.tipo_equipo)";
    }
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    while(!$result->EOF){
      $vars[]=$result->GetRowAssoc($toUpper=false);
      $result->MoveNext();
    }
    $_SESSION['ARREGLO_LIQUIDACIONQX_EQUIPOS']=$vars;
    return true;
  }

  function TraeDatosCirugia($NoLiquidacion){
    list($dbconn) = GetDBconn();
    $query="SELECT va.descripcion as via,am.descripcion as ambito,fn.descripcion as finalidad,ti.descripcion as tipo,
    a.fecha_cirugia,a.duracion_cirugia,a.estado
    FROM cuentas_liquidaciones_qx a
    JOIN qx_vias_acceso va ON(a.via_acceso=va.via_acceso)
    JOIN qx_ambitos_cirugias am ON(a.ambito_cirugia_id=am.ambito_cirugia_id)
    JOIN qx_finalidades_procedimientos fn ON(a.finalidad=fn.finalidad_procedimiento_id)
    JOIN qx_tipos_cirugia ti ON(a.tipo_cirugia_id=ti.tipo_cirugia_id)
    WHERE a.cuenta_liquidacion_qx_id='$NoLiquidacion'";
    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $vars=$result->GetRowAssoc($toUpper=false);
    }
    return $vars;
  }



  /*function InsertarDocumentosBodegasCirugia(){

    IncludeLib("despacho_medicamentos");
    $cantidades=$_REQUEST['Cantidad'];
    foreach($cantidades as $producto=>$valor){
      $_SESSION['IYM_CIRUGIA_CANTIDADES'][$producto]=$valor;
    }

    if($_REQUEST['SeleccionProducto']){
      $this->BuscadorProductoInv($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
      $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento']);
      return true;
    }

    if($_REQUEST['SeleccionPaquete']){
      //Valida paquetes por defecto de acuerdo a los procedimientos insertados
      $query="SELECT DISTINCT paquete_insumos_id
      FROM cuentas_liquidaciones_qx_procedimientos a,qx_cups_paquetes_insumos b
      WHERE a.cuenta_liquidacion_qx_id='".$_REQUEST['liquidacionId']."' AND
      a.cargo_cups=b.cargo";
      list($dbconn) = GetDBconn();
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        while(!$result->EOF){
          $_SESSION['PAQUETES_CIRUGIA'][$result->fields[0]]=1;
          $result->MoveNext();
        }
      }
      //Fin valida
      $this->BuscadorPaquetesInv($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
      $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento']);
      return true;
    }

    if($_REQUEST['productoFV']&&$_REQUEST['NomproductoFV']){
      $this->CreacionDocumentosBodegas($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
      $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento'],$ProductoFechaVence=$_REQUEST['productoFV'],$NomProductoFechaVence=$_REQUEST['NomproductoFV']);
      return true;
    }

    if($_REQUEST['producto']){
      unset($_SESSION['IYM_CIRUGIA'][$_REQUEST['producto']]);
      unset($_SESSION['IYM_CIRUGIA_CANTIDADES'][$_REQUEST['producto']]);
      unset($_SESSION['IYM_EXISTENCIAS'][$_REQUEST['producto']]);
      unset($_SESSION['IYM_CIRUGIA_FV'][$_REQUEST['producto']]);
      unset($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$_REQUEST['producto']]);
      $this->CreacionDocumentosBodegas($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
      $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento']);
      return true;
    }

    if($_REQUEST['insertarFV']){

      if(empty($_REQUEST['NoLote']) || empty($_REQUEST['cantidadLote']) || empty($_REQUEST['FechaVmto'])){
        $this->frmError["MensajeError"]="Inserte Todos Los Datos Para la Fecha de Vencimiento";
        $this->CreacionDocumentosBodegas($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
        $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento'],$_REQUEST['ProductoFechaVence'],$_REQUEST['NomProductoFechaVence']);
        return true;
      }else{
        if($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$_REQUEST['ProductoFechaVence']]){
          foreach($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$_REQUEST['ProductoFechaVence']] as  $lote=>$arreglo){
            (list($cantidades,$fecha)=explode('||//',$arreglo));
            $sumaCantLotes+=$cantidades;
          }
        }
        if(($sumaCantLotes+$_REQUEST['cantidadLote']) > $_SESSION['IYM_CIRUGIA_CANTIDADES'][$_REQUEST['ProductoFechaVence']]){
          $this->frmError["MensajeError"]="La Cantidades de los Lotes superan las Cantidades para Devolucion";
          $this->CreacionDocumentosBodegas($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
          $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento'],$_REQUEST['ProductoFechaVence'],$_REQUEST['NomProductoFechaVence']);
          return true;
        }
        $_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$_REQUEST['ProductoFechaVence']][$_REQUEST['NoLote']]=$_REQUEST['cantidadLote'].'||//'.$_REQUEST['FechaVmto'];
        $_REQUEST['FechaVmto']='';$_REQUEST['NoLote']='';$_REQUEST['cantidadLote']='';
      }
      $this->CreacionDocumentosBodegas($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
      $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento'],$_REQUEST['ProductoFechaVence'],$_REQUEST['NomProductoFechaVence']);
      return true;
    }

    if($_REQUEST['movimiento']=='I'){
      foreach($_SESSION['IYM_CIRUGIA'] as $codigoPro=>$descripcionPro){
        if($_SESSION['IYM_CIRUGIA_FV'][$codigoPro]==1){
          if($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$codigoPro]){
          foreach($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$codigoPro] as  $lote=>$arreglo){
            (list($cantidades,$fecha)=explode('||//',$arreglo));
            $sumaCantLotes+=$cantidades;
          }
          }
          if($sumaCantLotes<$_SESSION['IYM_CIRUGIA_CANTIDADES'][$codigoPro]){
            $this->frmError["MensajeError"]="Imposible Insertar Los Datos, debe Insertar los Lotes de los Productos que lo Requieren";
            $this->CreacionDocumentosBodegas($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
            $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento'],$codigoPro,$descripcionPro);
            return true;
          }
        }
      }
    }
    list($dbconn) = GetDBconn();
        $numeracion=AsignarNumeroDocumentoDespacho($_REQUEST['bodegasDocId']);
        $numeracion=$numeracion['numeracion'];
    $query = "INSERT INTO bodegas_documentos(bodegas_doc_id,
              numeracion,fecha,total_costo,transaccion,
              observacion,usuario_id,fecha_registro,
              centro_utilidad_transferencia,
              bodega_destino_transferencia)VALUES(
              '".$_REQUEST['bodegasDocId']."','".$numeracion."',
              '".date("Y-m-d")."','0',NULL,'','".UserGetUID()."',
              '".date("Y-m-d H:i:s")."',NULL,NULL)";
    $result=$dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
      foreach($_SESSION['IYM_CIRUGIA'] as $codigoPro=>$descripcionPro){
              $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
                $result = $dbconn->Execute($query);
                $consecutivo=$result->fields[0];
                $query="SELECT costo FROM inventarios WHERE codigo_producto='".$codigoPro."' AND empresa_id='".$_SESSION['LIQUIDACION_QX']['Empresa']."'";
                $result = $dbconn->Execute($query);
                $costo=$result->fields[0];

                $query="INSERT INTO bodegas_documentos_d(consecutivo,
                                                                                                    codigo_producto,
                                                                                                    cantidad,
                                                                                                    total_costo,
                                                                                                    bodegas_doc_id,
                                                                                                    numeracion)VALUES(
                                                                                                    '$consecutivo',
                                                                                                    '".$codigoPro."',
                                                                                                    '".$_SESSION['IYM_CIRUGIA_CANTIDADES'][$codigoPro]."',
                                                                                                    '$costo',
                                                                                                    '".$_REQUEST['bodegasDocId']."',
                                                                                                    '$numeracion')";
                $result=$dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }else{
          $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='".$codigoPro."' AND empresa_id='".$_SESSION['LIQUIDACION_QX']['Empresa']."' AND centro_utilidad='".$_SESSION['LIQUIDACION_QX']['CENTRO_UTILIDAD']."' AND bodega='".$_SESSION['LIQUIDACION_QX']['BODEGA']."'";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
          }else{
            $datos=$result->RecordCount();
            if($datos){
              $exis=$result->GetRowAssoc($toUpper=false);
            }
          }
          if($_REQUEST['movimiento']=='E'){
            $query = "SELECT sw_control_fecha_vencimiento
            FROM existencias_bodegas
            WHERE empresa_id='".$_SESSION['LIQUIDACION_QX']['Empresa']."' AND centro_utilidad='".$_SESSION['LIQUIDACION_QX']['CENTRO_UTILIDAD']."' AND bodega='".$_SESSION['LIQUIDACION_QX']['BODEGA']."' AND codigo_producto='".$codigoPro."'";
            $result=$dbconn->Execute($query);
            $sw_control_fecha_vencimiento=$result->fields[0];
            if($sw_control_fecha_vencimiento=='1'){
              DescargarLotesBodega($_SESSION['LIQUIDACION_QX']['Empresa'],$_SESSION['LIQUIDACION_QX']['CENTRO_UTILIDAD'],$_SESSION['LIQUIDACION_QX']['BODEGA'],$codigoPro,$_SESSION['IYM_CIRUGIA_CANTIDADES'][$codigoPro]);
            }
            $TotalExistencias=$exis['existencia']-$_SESSION['IYM_CIRUGIA_CANTIDADES'][$codigoPro];
            if($TotalExistencias<0){
              $mensaje="La Transferencia No tuvo Exito, no hay Suficientes Existencias en Bodega para el Producto".' '.$codigoPro;
              $titulo="DESCARGO DE MEDICAMENTOS";
              $accion=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaCreacionDocumentosBodegas',array("liquidacionId"=>$_REQUEST['liquidacionId'],"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"nombrePaciente"=>$_REQUEST['nombrePaciente'],"cuenta"=>$_REQUEST['cuenta'],"ingreso"=>$_REQUEST['ingreso'],
              "movimiento"=>$_REQUEST['movimiento'],"bodegasDocId"=>$_REQUEST['bodegasDocId'],"nomdocumento"=>$_REQUEST['nomdocumento']));
              $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
              return true;
            }
          }else{
            if($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$codigoPro]){
              foreach($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$codigoPro] as  $lote=>$arreglo){
                (list($cantidades,$fecha)=explode('||//',$arreglo));
                (list($dia,$mes,$ano)=explode('/',$fecha));
                $query="INSERT INTO bodegas_documentos_d_fvencimiento_lotes(
                  lote,saldo,cantidad,empresa_id,centro_utilidad,
                  bodega,codigo_producto,consecutivo,fecha_vencimiento)
                  VALUES('$lote','0','$cantidades','".$_SESSION['LIQUIDACION_QX']['Empresa']."',
                  '".$_SESSION['LIQUIDACION_QX']['CENTRO_UTILIDAD']."',
                  '".$_SESSION['LIQUIDACION_QX']['BODEGA']."','$codigoPro',
                  '$consecutivo','".$ano."-".$mes."-".$dia."')";
                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0){
                  $this->error = "Error al Cargar el Modulo";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  $this->GuardarNumeroDocumento($commit=false);
                  return false;
                }
              }
            }
            $TotalExistencias=$exis['existencia']+$_SESSION['IYM_CIRUGIA_CANTIDADES'][$codigoPro];
          }
          $query="UPDATE existencias_bodegas SET existencia='$TotalExistencias' WHERE codigo_producto='".$codigoPro."' AND empresa_id='".$_SESSION['LIQUIDACION_QX']['Empresa']."' AND centro_utilidad='".$_SESSION['LIQUIDACION_QX']['CENTRO_UTILIDAD']."' AND bodega='".$_SESSION['LIQUIDACION_QX']['BODEGA']."'";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
          }
        }
      }
      $query="UPDATE bodegas_documentos SET
      total_costo=(SELECT sum(a.total_costo*a.cantidad) as tcosto
      FROM bodegas_documentos_d as a
      WHERE a.numeracion='".$numeracion."' AND
      a.bodegas_doc_id='".$_REQUEST['bodegasDocId']."')
      WHERE bodegas_doc_id='".$_REQUEST['bodegasDocId']."' AND numeracion='".$numeracion."'";

      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $this->GuardarNumeroDocumento($commit=false);
                return false;
      }else{
        $query=" INSERT INTO qx_documentos_iym_cirugia(bodegas_doc_id,numeracion,
        cuenta_liquidacion_qx_id,acto_id,estado)VALUES('".$_REQUEST['bodegasDocId']."','".$numeracion."','".$_REQUEST['liquidacionId']."',NULL,'0')";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $this->GuardarNumeroDocumento($commit=false);
          return false;
        }
      }
      $this->GuardarNumeroDocumento($commit=true);
      unset($_SESSION['IYM_CIRUGIA']);
      unset($_SESSION['IYM_EXISTENCIAS']);
      unset($_SESSION['IYM_CIRUGIA_CANTIDADES']);
      unset($_SESSION['IYM_CIRUGIA_FV']);
      unset($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS']);
      $mensaje="Documento de Bodega Guardado Satisfactoriamente";
      $titulo="DESCARGO DE MEDICAMENTOS";
      $accion=ModuloGetURL('app','DatosLiquidacionQX','user','SeleccionBodegaCargaInsumos',array("liquidacionId"=>$_REQUEST['liquidacionId'],"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"nombrePaciente"=>$_REQUEST['nombrePaciente'],"cuenta"=>$_REQUEST['cuenta'],"ingreso"=>$_REQUEST['ingreso']));
      $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
      return true;
    }
  }*/

  /*function SeleccionProductoInventariosQx(){
    if($_REQUEST['Filtrar']){
      $this->BuscadorProductoInv($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
      $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento'],$_REQUEST['codigoBus'],$_REQUEST['DescripcionBus']);
      return true;
    }
    if($_REQUEST['producto'] && $_REQUEST['descripcion']){
      $_SESSION['IYM_CIRUGIA'][$_REQUEST['producto']]=$_REQUEST['descripcion'];
      $_SESSION['IYM_EXISTENCIAS'][$_REQUEST['producto']]=$_REQUEST['existencia'];
    }
    $this->CreacionDocumentosBodegas($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
    $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento']);
    return true;
  }*/

  /*function LlamaCreacionDocumentosBodegas(){

    $this->CreacionDocumentosBodegas($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
    $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento']);
    return true;
  }*/

  /*function GuardarNumeroDocumento($commit=true)
    {
            list($dbconn) = GetDBconn();
            if($commit)
            {
                $sql="COMMIT;";
            }
            else
            {
                $sql="ROLLBACK;";
            }
            $result = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                die(MsgOut("Error al terminar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
                return false;
            }
            return true;
    }*/



  /*function DocumentosBodegaCreados($liquidacionId){
    list($dbconn) = GetDBconn();
    $query="SELECT a.bodegas_doc_id,a.numeracion,b.fecha,b.total_costo,c.codigo_producto,c.cantidad,c.total_costo as total,d.descripcion,
    f.descripcion as nomdocumento,(CASE WHEN e.tipo_movimiento='I' THEN 'INGRESO' ELSE 'EGRESO' END) as tipomov,g.descripcion as nombodega,
    (SELECT count(*) FROM bodegas_documentos_d y WHERE a.bodegas_doc_id=y.bodegas_doc_id AND a.numeracion=y.numeracion) as contador
    FROM qx_documentos_iym_cirugia a,bodegas_documentos b,bodegas_documentos_d c,inventarios_productos d,
    bodegas_doc_numeraciones e,tipos_doc_bodega f,bodegas g
    WHERE a.cuenta_liquidacion_qx_id='".$_REQUEST['liquidacionId']."' AND a.estado='0' AND
    a.bodegas_doc_id=b.bodegas_doc_id AND a.numeracion=b.numeracion AND b.bodegas_doc_id=c.bodegas_doc_id AND
    b.numeracion=c.numeracion AND c.codigo_producto=d.codigo_producto AND
    a.bodegas_doc_id=e.bodegas_doc_id AND e.tipo_doc_bodega_id=f.tipo_doc_bodega_id AND
    e.empresa_id=g.empresa_id AND e.centro_utilidad=g.centro_utilidad AND e.bodega=g.bodega";
    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        return $vars;
  }*/

  /*function PaquetesInventariosBodega($liquidacionId,$codigoBus,$DescripcionBus){


    list($dbconn) = GetDBconn();
        $query = "SELECT a.paquete_insumos_id,a.descripcion,
    (CASE WHEN (SELECT count(*)
    FROM qx_cups_paquetes_insumos b,cuentas_liquidaciones_qx_procedimientos c
    WHERE b.paquete_insumos_id=a.paquete_insumos_id AND c.cuenta_liquidacion_qx_id='".$liquidacionId."' AND
    b.cargo=c.cargo_cups) > 0 THEN '1' ELSE '0' END) as existe
    FROM qx_paquetes_insumos a";
    if($codigoBus){
      $query.=" WHERE a.paquete_insumos_id LIKE '$codigoBus%'";
      $yaand=1;
    }
    if($DescripcionBus){
      if($yaand==1){
        $query.=" AND a.descripcion LIKE '%".strtoupper($DescripcionBus)."%'";
      }else{
        $query.=" WHERE a.descripcion LIKE '%".strtoupper($DescripcionBus)."%'";
      }
    }
    $query.=" ORDER BY existe DESC,a.descripcion";

        if(empty($_REQUEST['conteo'])){
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->conteo=$result->RecordCount();
        }else{
            $this->conteo=$_REQUEST['conteo'];
        }
        $query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        return $vars;
  }*/

  /*function SeleccionPaquetesInventariosQx(){

    if($_REQUEST['Filtrar']){
      $this->BuscadorPaquetesInv($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
      $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento'],$_REQUEST['codigoBus'],$_REQUEST['DescripcionBus']);
      return true;
    }

    if($_REQUEST['SeleccionPaquete']){

      foreach($_REQUEST['SeleccionActual'] as $codPaqueteActual=>$val){
        if(!in_array($codPaqueteActual,$_REQUEST['Seleccion'])){
          unset($_SESSION['PAQUETES_CIRUGIA'][$codPaqueteActual]);
        }
      }
      foreach($_REQUEST['Seleccion'] as $codPaquete=>$val){
        $_SESSION['PAQUETES_CIRUGIA'][$codPaquete]=1;
      }
      $this->BuscadorPaquetesInv($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
      $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento'],$_REQUEST['codigoBus'],$_REQUEST['DescripcionBus']);
      return true;
    }
    foreach($_SESSION['PAQUETES_CIRUGIA'] as $codigoPaquete=>$valor){
      list($dbconn) = GetDBconn();
      $query="SELECT a.codigo_producto,c.descripcion,a.cantidad,d.existencia
      FROM qx_paquetes_contiene_insumos a,inventarios b,inventarios_productos c,existencias_bodegas d
      WHERE a.paquete_insumos_id='".$codigoPaquete."' AND a.empresa_id='".$_SESSION['LIQUIDACION_QX']['Empresa']."' AND
      a.empresa_id=b.empresa_id AND a.codigo_producto=b.codigo_producto AND
      b.codigo_producto=c.codigo_producto AND b.empresa_id=d.empresa_id AND
      d.centro_utilidad='".$_SESSION['LIQUIDACION_QX']['CENTRO_UTILIDAD']."' AND d.bodega='".$_SESSION['LIQUIDACION_QX']['BODEGA']."' AND
      a.codigo_producto=d.codigo_producto";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        while(!$result->EOF){
          if(!$_SESSION['IYM_CIRUGIA'][$result->fields[0]]){
          $_SESSION['IYM_CIRUGIA'][$result->fields[0]]=$result->fields[1];
          $_SESSION['IYM_CIRUGIA_CANTIDADES'][$result->fields[0]]=$result->fields[2];
          $_SESSION['IYM_EXISTENCIAS'][$result->fields[0]]=$result->fields[3];
          }else{
          $_SESSION['IYM_CIRUGIA_CANTIDADES'][$result->fields[0]]+=$result->fields[2];
          }
          $result->MoveNext();
        }
      }
    }
    unset($_SESSION['PAQUETES_CIRUGIA']);
    $this->CreacionDocumentosBodegas($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
    $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento']);
    return true;
  }*/

  /*function ConsultaPaquetesInventariosQx(){
    $this->LlamaConsultaPaquetesInventariosQx($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
    $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento'],$_REQUEST['paqueteId'],
    $_REQUEST['nomPaquete'],$_REQUEST['codigoBus'],$_REQUEST['DescripcionBus']);
    return true;
  }*/

  /*function ProductosPaquetesInventariosBodega($paqueteId){
    list($dbconn) = GetDBconn();
    $query="SELECT a.codigo_producto,c.descripcion,a.cantidad
    FROM qx_paquetes_contiene_insumos a,inventarios b,inventarios_productos c
    WHERE a.paquete_insumos_id='".$paqueteId."' AND
    a.empresa_id=b.empresa_id AND a.codigo_producto=b.codigo_producto AND
    b.codigo_producto=c.codigo_producto";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      while(!$result->EOF){
        $vars[]=$result->GetRowAssoc($toUpper=false);
        $result->MoveNext();
      }
    }
    $result->Close();
    return $vars;
  }*/

  function TraeProcedimientosCirugia($NoLiquidacion){
    list($dbconn) = GetDBconn();
    $query="SELECT a.tipo_id_cirujano,a.cirujano_id,
    a.cargo_cups,b.descripcion,a.sw_bilateral,c.nombre_tercero,a.consecutivo_procedimiento,
    (SELECT count(*) FROM cuentas_liquidaciones_qx_procedimientos WHERE cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND tipo_id_cirujano=a.tipo_id_cirujano AND cirujano_id=a.cirujano_id) as contador
    FROM cuentas_liquidaciones_qx_procedimientos a,cups b,terceros c
    WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.cargo_cups=b.cargo AND
    a.tipo_id_cirujano=c.tipo_id_tercero AND a.cirujano_id=c.tercero_id";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      while(!$result->EOF){
        $vars[]=$result->GetRowAssoc($toUpper=false);
        $result->MoveNext();
      }
    }
    $result->Close();
    return $vars;
  }

/**
* Funcion que inserta y calcula los valore del cargos del medicamento o insumo
* @return array
* @param string codigo unico que el identifica el registro de insercion del medicamento o insumo
*/
  /*function InsertarBodegasDocumentosdCober($Consecutivo,$fechaCargo,$cuenta,$codigo,$cantidad,$precio,$codigoAgrupamiento,$planId,$Servicio,$Empresa,$CentroUtili,$departamento,$devolucion,$tipoCargo){

      IncludeLib("tarifario_cargos");
      if(empty($Consecutivo)){
      $Consecutivo=$_REQUEST['Consecutivo'];
        }
        list($dbconn) = GetDBconn();
        $varsCuenDet=LiquidarIyM($cuenta,$codigo,$cantidad,$descuento_manual_empresa=0,$descuento_manual_paciente=0,$aplicar_descuento_empresa=false,$aplicar_descuento_paciente=false,$precio,$planId,$autorizar=false,$departamento,$Empresa);
        $autorizacion_int=$varsCuenDet['autorizacion_int'];
        if(!$autorizacion_int){$autorizacion_int1='NULL';}else{$autorizacion_int1="'$autorizacion_int'";}
        $autorizacion_ext=$varsCuenDet['autorizacion_ext'];
        if(!$autorizacion_ext){$autorizacion_ext1='NULL';}else{$autorizacion_ext1="'$autorizacion_ext'";}

        $query="SELECT nextval('cuentas_detalle_transaccion_seq')";
        $result=$dbconn->Execute($query);
        $Transaccion=$result->fields[0];
        if($devolucion=='1'){
          $valor_cargo=($varsCuenDet['valor_cargo']*-1);
            $valor_nocubierto=($varsCuenDet['valor_nocubierto']*-1);
            $valor_cubierto=($varsCuenDet['valor_cubierto']*-1);
        }else{
      $valor_cargo=$varsCuenDet['valor_cargo'];
            $valor_nocubierto=$varsCuenDet['valor_nocubierto'];
            $valor_cubierto=$varsCuenDet['valor_cubierto'];
        }
        if(empty($tipoCargo)){
      $tipoCargo='IMD';
        }
        $query = "INSERT INTO cuentas_detalle(transaccion,empresa_id,centro_utilidad,
                                                                                    numerodecuenta,departamento,tarifario_id,
                                                                                    cargo,cantidad,precio,
                                                                                    porcentaje_descuento_empresa,valor_cargo,valor_nocubierto,
                                                                                    valor_cubierto,facturado,fecha_cargo,
                                                                                    usuario_id,fecha_registro,sw_liq_manual,
                                                                                    valor_descuento_empresa,valor_descuento_paciente,porcentaje_descuento_paciente,
                                                                                    servicio_cargo,autorizacion_int,autorizacion_ext,
                                                                                    porcentaje_gravamen,sw_cuota_paciente,sw_cuota_moderadora,
                                                                                    codigo_agrupamiento_id,consecutivo,cargo_cups,sw_cargue)VALUES
                                                                                    ('$Transaccion','$Empresa','$CentroUtili',
                                                                                    $cuenta,'$departamento','SYS',
                                                                                    '$tipoCargo','$cantidad','".$varsCuenDet['precio_plan']."',
                                                                                    '".$varsCuenDet['porcentaje_descuento_empresa']."','".$valor_cargo."','".$valor_nocubierto."',
                                                                                    '".$valor_cubierto."','".$varsCuenDet['facturado']."','$fechaCargo',
                                                                                    '".UserGetUID()."','".date('Y-m-d H:i:s')."','0',
                                                                                    '".$varsCuenDet['valor_descuento_empresa']."','".$varsCuenDet['valor_descuento_paciente']."','".$varsCuenDet['porcentaje_descuento_paciente']."',
                                                                                    '$Servicio',$autorizacion_int1,$autorizacion_ext1,
                                                                                    '".$varsCuenDet['porcentaje_gravamen']."','".$varsCuenDet['sw_cuota_paciente']."','".$varsCuenDet['sw_cuota_moderadora']."',
                                                                                    '$codigoAgrupamiento','$Consecutivo',NULL,'3')";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
          //Falta Validar lo de la Cuenta estado
            $query = "SELECT a.transaccion,a.cargo,a.cantidad
            FROM cuentas_detalle a, bodegas_documentos_d b
            WHERE a.numerodecuenta='$cuenta' AND a.consecutivo=b.consecutivo AND
            b.codigo_producto='$codigo' AND a.consecutivo <> '$Consecutivo'";

            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
            }else{
        $datos=$result->RecordCount();
                if($datos){
                    while(!$result->EOF){
            $vars=$result->GetRowAssoc($toUpper=false);
                        $varsCuenDet=LiquidarIyM($cuenta,$codigo,$vars['cantidad'],$descuento_manual_empresa=0,$descuento_manual_paciente=0,$aplicar_descuento_empresa=false,$aplicar_descuento_paciente=false,$precio,$planId,$autorizar=false,$departamento,$Empresa);
                        if($vars['cargo']=='DIMD'){
              $valor_cargo=($varsCuenDet['valor_cargo']*-1);
                            $valor_nocubierto=($varsCuenDet['valor_nocubierto']*-1);
                            $valor_cubierto=($varsCuenDet['valor_cubierto']*-1);
                        }else{
              $valor_cargo=$varsCuenDet['valor_cargo'];
                            $valor_nocubierto=$varsCuenDet['valor_nocubierto'];
              $valor_cubierto=$varsCuenDet['valor_cubierto'];
                        }
                        $query = "UPDATE cuentas_detalle
                        SET precio='".$varsCuenDet['precio_plan']."',
                        porcentaje_descuento_empresa='".$varsCuenDet['porcentaje_descuento_empresa']."',
                        valor_cargo='".$valor_cargo."',valor_nocubierto='".$valor_nocubierto."',
                        valor_cubierto='".$valor_cubierto."',
                        facturado='".$varsCuenDet['facturado']."',valor_descuento_empresa='".$varsCuenDet['valor_descuento_empresa']."',
                        valor_descuento_paciente='".$varsCuenDet['valor_descuento_paciente']."',porcentaje_descuento_paciente='".$varsCuenDet['porcentaje_descuento_paciente']."',
                        porcentaje_gravamen='".$varsCuenDet['porcentaje_gravamen']."',sw_cuota_paciente='".$varsCuenDet['sw_cuota_paciente']."',
                        sw_cuota_moderadora='".$varsCuenDet['sw_cuota_moderadora']."'
                        WHERE transaccion='".$vars['transaccion']."'";

            $result1 = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Guardar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }
                        $result->MoveNext();
                    }
                }
            }
      return true;
        }
        return false;
    }*/

 /*function ProductosDespachadosPaciente($liquidacionId){

    list($dbconn) = GetDBconn();
    $query = "SELECT b.codigo_producto,c.descripcion,b.cantidad,d.existencia,d.sw_control_fecha_vencimiento
    FROM qx_documentos_iym_cirugia a,bodegas_documentos_d b,inventarios_productos c,existencias_bodegas d
    WHERE a.bodegas_doc_id=b.bodegas_doc_id AND a.numeracion=b.numeracion AND
    b.codigo_producto=c.codigo_producto AND c.codigo_producto=d.codigo_producto AND
    d.empresa_id='".$_SESSION['LIQUIDACION_QX']['Empresa']."' AND d.centro_utilidad='".$_SESSION['LIQUIDACION_QX']['CENTRO_UTILIDAD']."' AND
    d.bodega='".$_SESSION['LIQUIDACION_QX']['BODEGA']."' AND a.cuenta_liquidacion_qx_id='".$liquidacionId."' AND a.estado='0'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      while(!$result->EOF){
        if(!$_SESSION['IYM_CIRUGIA'][$result->fields[0]]){
          $_SESSION['IYM_CIRUGIA'][$result->fields[0]]=$result->fields[1];
          $_SESSION['IYM_EXISTENCIAS'][$result->fields[0]]=$result->fields[3];
          $_SESSION['IYM_CIRUGIA_CANTIDADES'][$result->fields[0]]=$result->fields[2];
          if($result->fields[4]==1){
            $_SESSION['IYM_CIRUGIA_FV'][$result->fields[0]]=1;
          }
        }else{
          $_SESSION['IYM_CIRUGIA_CANTIDADES'][$result->fields[0]]+=$result->fields[2];
        }
        $result->MoveNext();
      }
      $result->Close();
    }
    return true;
  }*/

  function LlamaFormaEquivalentesLiquidacion(){
    $_REQUEST['der_cirujano']=1;
    $_REQUEST['der_anestesiologo']=1;
    $_REQUEST['der_ayudante']=1;
    $_REQUEST['der_sala']=1;
    $_REQUEST['der_materiales']=1;
    $this->FormaEquivalentesLiquidacion($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
    $_REQUEST['TipoDocumentoFil'],$_REQUEST['DocumentoFil'],$_REQUEST['NoIngresoFil'],$_REQUEST['NoCuentaFil'],$_REQUEST['EstadoFil'],$_REQUEST['FechaCirugiaFil']);
    return true;
  }

  /*function EliminarLoteProducto(){
    unset($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$_REQUEST['producto']][$_REQUEST['LoteProducto']]);
    if(sizeof($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$_REQUEST['producto']])==0){
      unset($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS'][$_REQUEST['producto']]);
    }
    $this->CreacionDocumentosBodegas($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
    $_REQUEST['movimiento'],$_REQUEST['bodegasDocId'],$_REQUEST['nomdocumento']);
    return true;
  }*/

/**
* Metodo para Obtener los cargos contratados para un cargo cups
*
* @return array
* @access private
*/
  function GetEquivalenciasCargosLiquidacion($NoLiquidacion){
    GLOBAL $ADODB_FETCH_MODE;
  /*$query="SELECT a.tipo_id_cirujano,a.cirujano_id,
    a.cargo_cups,b.descripcion,a.sw_bilateral,c.nombre_tercero,
    (SELECT count(*) FROM cuentas_liquidaciones_qx_procedimientos WHERE cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND tipo_id_cirujano=a.tipo_id_cirujano AND cirujano_id=a.cirujano_id) as contador
    FROM cuentas_liquidaciones_qx_procedimientos a,cups b,terceros c
    WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.cargo_cups=b.cargo AND
    a.tipo_id_cirujano=c.tipo_id_tercero AND a.cirujano_id=c.tercero_id";
  */

    list($dbconn) = GetDBconn();
    $sql = "SELECT cargos.consecutivo_procedimiento as consecutivo_procedimiento_prin,cargos.cargo_cups as cargo_cups_prin,tablacups.descripcion as descripcion_prin,
                 cargos.tipo_id_cirujano||'-'||cargos.cirujano_id as cirujano_prin,ter.nombre_tercero as nombre_tercero_prin,tabla.*
            FROM cuentas_liquidaciones_qx_procedimientos cargos
            LEFT JOIN
             ((SELECT b.plan_id, a.tarifario_id, a.cargo, a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura, b.sw_descuento, a.sw_cantidad,
             lqx.consecutivo_procedimiento,lqx.cargo_cups,lqx.descripcion_cups,lqx.sw_bilateral,lqx.cirujano,lqx.nombre_tercero,tarif.descripcion as nomtarifario,pl.plan_descripcion
             FROM tarifarios_detalle a,plan_tarifario b, tarifarios tarif,planes pl,tarifarios_equivalencias c,
             (SELECT z.consecutivo_procedimiento,z.cargo_cups,y.plan_id,w.descripcion as descripcion_cups,z.sw_bilateral,
             z.tipo_id_cirujano||'-'||z.cirujano_id as cirujano,v.nombre_tercero
             FROM cuentas_liquidaciones_qx x,cuentas y,cuentas_liquidaciones_qx_procedimientos z,cups w,terceros v
             WHERE x.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND x.numerodecuenta=y.numerodecuenta AND
             x.cuenta_liquidacion_qx_id=z.cuenta_liquidacion_qx_id AND z.cargo_cups=w.cargo AND
             z.tipo_id_cirujano=v.tipo_id_tercero AND z.cirujano_id=v.tercero_id) as lqx
            WHERE b.plan_id = lqx.plan_id
            AND b.plan_id=pl.plan_id
            AND a.grupo_tarifario_id = b.grupo_tarifario_id
            AND a.subgrupo_tarifario_id = b.subgrupo_tarifario_id
            AND a.tarifario_id = b.tarifario_id
            AND a.tarifario_id=tarif.tarifario_id
            AND excepciones(b.plan_id,b.tarifario_id, a.cargo) = 0
            AND c.cargo_base = lqx.cargo_cups
            AND c.tarifario_id = a.tarifario_id
            AND c.cargo=a.cargo
            )
            UNION
            (SELECT b.plan_id, a.tarifario_id, a.cargo, a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura, b.sw_descuento, a.sw_cantidad,
            lqx.consecutivo_procedimiento,lqx.cargo_cups,lqx.descripcion_cups,lqx.sw_bilateral,lqx.cirujano,lqx.nombre_tercero,tarif.descripcion as nomtarifario,pl.plan_descripcion
            FROM tarifarios_detalle a, excepciones b, tarifarios_equivalencias c,tarifarios tarif,planes pl,

            (SELECT z.consecutivo_procedimiento,z.cargo_cups,y.plan_id,w.descripcion as descripcion_cups,z.sw_bilateral,
             z.tipo_id_cirujano||'-'||z.cirujano_id as cirujano,v.nombre_tercero
             FROM cuentas_liquidaciones_qx x,cuentas y,cuentas_liquidaciones_qx_procedimientos z,cups w,terceros v
             WHERE x.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND x.numerodecuenta=y.numerodecuenta AND
             x.cuenta_liquidacion_qx_id=z.cuenta_liquidacion_qx_id AND z.cargo_cups=w.cargo AND
             z.tipo_id_cirujano=v.tipo_id_tercero AND z.cirujano_id=v.tercero_id) as lqx

            WHERE c.cargo_base = lqx.cargo_cups
            AND b.plan_id = lqx.plan_id
            AND b.plan_id=pl.plan_id
            AND b.tarifario_id = c.tarifario_id
            AND b.cargo = c.cargo
            AND a.tarifario_id = c.tarifario_id
            AND a.tarifario_id=tarif.tarifario_id
            AND a.cargo = c.cargo
            AND b.sw_no_contratado = 0
            )) tabla ON (cargos.cargo_cups=tabla.cargo_cups),cups tablacups,terceros ter
            WHERE cargos.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND tablacups.cargo=cargos.cargo_cups AND
            cargos.tipo_id_cirujano=ter.tipo_id_tercero AND cargos.cirujano_id=ter.tercero_id
            ORDER BY tabla.cirujano,cargos.cargo_cups";


    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($sql);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

    if ($dbconn->ErrorNo() != 0) {
      $this->error = "CLASS LiquidacionQX -  - ERROR 01";
      $this->mensajeDeError = $dbconn->ErrorMsg();
      return false;
    }
    $cargos_contratados_plan=$result->GetRows();
    $result->Close();
    return $cargos_contratados_plan;
  }

  function IntertarEquivalentesLiquidacion(){
        if(!$_REQUEST['der_cirujano'] && !$_REQUEST['der_anestesiologo'] && !$_REQUEST['der_ayudante'] && !$_REQUEST['der_sala'] && !$_REQUEST['der_materiales']){
            $this->frmError["MensajeError"]="Seleccione los derechos para liquidar";
      $this->FormaEquivalentesLiquidacion($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
      $_REQUEST['TipoDocumentoFil'],$_REQUEST['DocumentoFil'],$_REQUEST['NoIngresoFil'],$_REQUEST['NoCuentaFil'],$_REQUEST['EstadoFil'],$_REQUEST['FechaCirugiaFil']);
      return true;
        }
    $Seleccion=$_REQUEST['Seleccion'];
    $Bilaterales=$_REQUEST['Bilateral'];
    $NoLiquidacion=$_REQUEST['NoLiquidacion'];
    $dat=$this->TraeProcedimientosCirugia($NoLiquidacion);
    for($i=0;$i<sizeof($dat);$i++){
      $existe=0;
      foreach($Seleccion as $indice=>$vector){
        foreach($vector as $procedimiento_id=>$valores){
          if($dat[$i]['consecutivo_procedimiento']==$procedimiento_id){
            $existe=1;
            break;
          }
        }
      }
      if($existe==0){
        break;
      }
    }
    if($existe==0){
      $this->frmError["MensajeError"]="Especifique Minimo un Tarifario por Cada Procedimiento";
      $this->FormaEquivalentesLiquidacion($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
      $_REQUEST['TipoDocumentoFil'],$_REQUEST['DocumentoFil'],$_REQUEST['NoIngresoFil'],$_REQUEST['NoCuentaFil'],$_REQUEST['EstadoFil'],$_REQUEST['FechaCirugiaFil']);
      return true;
    }
    /*$error=0;
    $cont=0;
    foreach($Seleccion as $indice=>$vector){
      foreach($vector as $procedimiento_id=>$valores){
        (list($tarifario,$cargo)=explode('||//',$valores));
        if($cont!=0){
          if($tarifario!=$tarifarioAnt){
            $error=1;
            break;
          }
        }
        $tarifarioAnt=$tarifario;
      }
      if($error==1){
        break;
      }
      $cont++;
    }
    if($error==1){
      $this->frmError["MensajeError"]="Imposible Realizar la liquidacion de un procedimiento con diferente Tarifario";
      $this->FormaEquivalentesLiquidacion($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
      $_REQUEST['TipoDocumentoFil'],$_REQUEST['DocumentoFil'],$_REQUEST['NoIngresoFil'],$_REQUEST['NoCuentaFil'],$_REQUEST['EstadoFil'],$_REQUEST['FechaCirugiaFil']);
      return true;
    }*/

    list($dbconn) = GetDBconn();
    if($Seleccion){
      $dbconn->BeginTrans();
      foreach($Seleccion as $indice=>$vector){
        foreach($vector as $procedimiento_id=>$valores){
          (list($tarifario,$cargo)=explode('||//',$valores));
          $query="DELETE FROM cuentas_liquidaciones_qx_procedimientos_cargos
          WHERE consecutivo_procedimiento='".$procedimiento_id."'";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }else{
            if($Bilaterales[$procedimiento_id]){$bilateral=1;}else{$bilateral=0;}
            $query="INSERT INTO cuentas_liquidaciones_qx_procedimientos_cargos(
            consecutivo_procedimiento,tarifario_id,cargo,sw_bilateral)VALUES
            ('".$procedimiento_id."','".$tarifario."','".$cargo."','".$bilateral."')";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $dbconn->RollbackTrans();
              return false;
            }
          }
        }
      }
      if($_REQUEST['der_cirujano']){$der_cirujano=1;}else{$der_cirujano=0;}
      if($_REQUEST['der_anestesiologo']){$der_anestesiologo=1;}else{$der_anestesiologo=0;}
      if($_REQUEST['der_ayudante']){$der_ayudante=1;}else{$der_ayudante=0;}
      if($_REQUEST['der_sala']){$der_sala=1;}else{$der_sala=0;}
      if($_REQUEST['der_materiales']){$der_materiales=1;}else{$der_materiales=0;}
            if($_REQUEST['der_equipos']){$der_equipos=1;}else{$der_equipos=0;}
            if($_REQUEST['der_insumos_consumo']){$der_insumos_consumo=1;}else{$der_insumos_consumo=0;}
      $query="UPDATE cuentas_liquidaciones_qx SET sw_derechos_cirujano='$der_cirujano',sw_derechos_anestesiologo='$der_anestesiologo',
      sw_derechos_ayudante='$der_ayudante',sw_derechos_sala='$der_sala',
      sw_derechos_materiales='$der_materiales',sw_equipos_medicos='$der_equipos',sw_medicamentos_consumo='$der_insumos_consumo'
            WHERE cuenta_liquidacion_qx_id='".$_REQUEST['NoLiquidacion']."'";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;
      }
      $dbconn->CommitTrans();
    }
    $this->CargarCargosCirugiaTemporal($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
    return true;
  }

  function NombreTercero($tipo_id_tercero,$tercero_id){
    list($dbconn) = GetDBconn();
    $query="SELECT nombre_tercero
    FROM terceros
    WHERE tipo_id_tercero='".$tipo_id_tercero."' AND tercero_id='".$tercero_id."'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        $vars=$result->GetRowAssoc($toUpper=false);
      }
    }
    $result->Close();
    return $vars;
  }

  function DescripcionCargosCups($cargo_cups){
    list($dbconn) = GetDBconn();
    $query="SELECT descripcion
    FROM cups
    WHERE cargo='".$cargo_cups."'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        $vars=$result->GetRowAssoc($toUpper=false);
      }
    }
    $result->Close();
    return $vars;
  }

  function DescripcionCargosTarifario($tarifario_id){
    list($dbconn) = GetDBconn();
   $query="SELECT a.descripcion as tarifario
    FROM tarifarios a
    WHERE a.tarifario_id='".$tarifario_id."'";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        $vars=$result->GetRowAssoc($toUpper=false);
      }
    }
    $result->Close();
    return $vars;
  }

  function HallarCostoProducto($Empresa,$Codigo){

        list($dbconn) = GetDBconn();
        $query="SELECT costo FROM inventarios WHERE empresa_id='$Empresa' AND codigo_producto='$Codigo'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() !=0 ){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datosCont=$result->RecordCount();
            if($datosCont){
                $vars=$result->GetRowAssoc($toUpper=false);
                $costoProducto=$vars['costo'];
            }
        }
        return $costoProducto;
    }

   /*function SeleccionBodegaCargaInsumos(){
    unset($_SESSION['IYM_CIRUGIA']);
    unset($_SESSION['IYM_EXISTENCIAS']);
    unset($_SESSION['IYM_CIRUGIA_CANTIDADES']);
    unset($_SESSION['IYM_CIRUGIA_FV']);
    unset($_SESSION['IYM_CIRUGIA_CANTIDADES']);
    unset($_SESSION['IYM_CIRUGIA_FV_PRODUCTOS']);
    $this->FormaSeleccionBodegaCargaInsumos($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
    return true;
  }*/

  /*function BodegasPermisosDescargoIyM(){
    list($dbconn) = GetDBconn();
    $query="SELECT b.centro_utilidad,b.bodega,c.descripcion
    FROM userpermisos_estacion_enfermeria_qx a,
    estacion_enfermeria_qx_departamentos b,
    bodegas c
    WHERE a.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' AND a.usuario_id='".UserGetUID()."' AND a.cargue_iym='1' AND
    a.departamento=b.departamento AND
    b.empresa_id=c.empresa_id AND b.centro_utilidad=c.centro_utilidad AND b.bodega=c.bodega";
    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $vars=$result->GetRowAssoc($toUpper=false);
    }
    return $vars;
  }*/

  /*function SeleccionDocumentoParaIYM(){

    list($dbconn) = GetDBconn();
    $query="SELECT a.bodegas_doc_id,c.descripcion as nomdocumento,d.descripcion as nombodega
    FROM qx_tipos_documentos_bodega_manuales a,bodegas_doc_numeraciones b,tipos_doc_bodega c,bodegas d
    WHERE a.empresa_id='".$_SESSION['LIQUIDACION_QX']['Empresa']."' AND a.centro_utilidad='".$_SESSION['LIQUIDACION_QX']['CENTRO_UTILIDAD']."' AND a.bodega='".$_SESSION['LIQUIDACION_QX']['BODEGA']."' AND
    a.bodegas_doc_id=b.bodegas_doc_id AND b.tipo_movimiento='".$_REQUEST['movimiento']."' AND b.sw_estado='1' AND
    b.tipo_doc_bodega_id=c.tipo_doc_bodega_id AND b.empresa_id=d.empresa_id AND
    b.centro_utilidad=d.centro_utilidad AND b.bodega=d.bodega";
    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      if($result->RecordCount()>0){
        $vars=$result->GetRowAssoc($toUpper=false);
      }else{
        $this->frmError["MensajeError"]="No existe un tipo de Documento de Bodega para realizar este Movimiento";
        $this->FormaSeleccionBodegaCargaInsumos($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
      }
    }
    if($_REQUEST['movimiento']=='I'){
    $this->ProductosDespachadosPaciente($liquidacionId);
    }
    $this->CreacionDocumentosBodegas($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],
    $_REQUEST['movimiento'],$vars['bodegas_doc_id'],$vars['nomdocumento']);
    return true;
  }*/


  function CargaInsumosMedicamentosCuenta(){
    unset($_SESSION['IYM_CUENTAS_QX']);
    unset($_SESSION['IYM_CUENTAS_QX_DEVOL']);
    unset($_SESSION['IYM_CIRUGIA_CANTIDADES']);
    list($dbconn) = GetDBconn();
    $query="SELECT a.bodega,b.descripcion
    FROM estacion_enfermeria_qx_departamentos a,bodegas b
    WHERE a.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' AND
    a.empresa_id=b.empresa_id AND a.centro_utilidad=b.centro_utilidad AND a.bodega=b.bodega";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $vars=$result->GetRowAssoc($toUpper=false);
    }
    $_SESSION['LIQUIDACION_QX']['Bodega']=$vars['bodega'];
    $_SESSION['LIQUIDACION_QX']['NombreBodega']=$vars['descripcion'];
        if($_REQUEST['programacionId']){
            $_SESSION['LIQUIDACION_QX']['PROGRAMACION_INSUMOS']=$_REQUEST['programacionId'];
        }
    $this->frmCargaInsumosMedicamentosCuenta($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
    return true;
  }

  function ProductosHojaInsumos($NoLiquidacion,$programacion){

    list($dbconn) = GetDBconn();
	
        if(!empty($NoLiquidacion)){
            $query="SELECT a.programacion_id
                        FROM cuentas_liquidaciones_qx a
                        WHERE a.cuenta_liquidacion_qx_id='$NoLiquidacion'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }else{
                $programacion=$result->fields[0];
            }
        }
    $query="	SELECT x.*,z.devolucion,(x.despacho - coalesce(z.devolucion,0)) as total
				FROM (	SELECT 	b.codigo_producto,
								b.lote,
								b.fecha_vencimiento,
								sum(b.cantidad) as despacho,
								(	SELECT c.descripcion 
									FROM inventarios_productos c 
									WHERE b.codigo_producto=c.codigo_producto
								) as descripcion,

                (	SELECT 	h.existencia_actual as existencia
					FROM 	existencias_bodegas f,
							estacion_enfermeria_qx_departamentos g, 
							existencias_bodegas_lote_fv h
					WHERE 	g.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' 
					AND		g.empresa_id=f.empresa_id 
					AND 	g.centro_utilidad=f.centro_utilidad 
					AND 	g.bodega=f.bodega 
					AND 	f.codigo_producto=b.codigo_producto
					AND		f.empresa_id = h.empresa_id
					AND     f.centro_utilidad = h.centro_utilidad
					AND     f.bodega = h.bodega
					AND		f.codigo_producto = h.codigo_producto
					AND	    h.lote = b.lote
					AND     h.fecha_vencimiento = b.fecha_vencimiento
				) as existencia

                FROM estacion_enfermeria_qx_iym b
                WHERE b.programacion_id='$programacion' AND b.estado='0'
                GROUP BY b.codigo_producto,b.lote,b.fecha_vencimiento) x
                LEFT JOIN (	SELECT 	e.codigo_producto,
									e.lote,
									e.fecha_vencimiento,
									sum(e.cantidad) as devolucion
							FROM 	estacion_enfermeria_qx_iym_devoluciones e
							WHERE e.programacion_id='$programacion' AND e.estado='0'
							GROUP BY e.codigo_producto,e.lote,e.fecha_vencimiento
						  ) z 
						  ON (x.codigo_producto=z.codigo_producto AND x.lote = z.lote AND x.fecha_vencimiento = z.fecha_vencimiento)
        WHERE   (x.despacho - coalesce(z.devolucion,0)) > 0
		ORDER BY x.codigo_producto, x.lote, x.fecha_vencimiento
        ";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      while(!$result->EOF){
        $vars[]=$result->GetRowAssoc($toUpper=false);
        $result->MoveNext();
      }
    }
    $result->Close();
    return $vars;
  }

  function ConfirmarLiquidacionCuenta($liquidacionId){
    list($dbconn) = GetDBconn();
    $query="SELECT *
    FROM cuentas_codigos_agrupamiento a,cuentas_detalle b,
         cuentas_liquidaciones_qx CLQX
    WHERE a.cuenta_liquidacion_qx_id='".$liquidacionId."' AND a.descripcion='ACTO QUIRURGICO' AND a.bodegas_doc_id IS NULL AND a.numeracion IS NULL 
    AND a.cuenta_liquidacion_qx_id = CLQX.cuenta_liquidacion_qx_id
    AND CLQX.numerodecuenta = b.numerodecuenta
    AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        $result->Close();
        return 1;
      }else{
        $result->Close();
        return 0;
      }
    }
  }

  function CargarIyMCuentaPaciente(){
    
    $query="SELECT a.plan_id
    FROM cuentas a
    WHERE a.numerodecuenta='".$_REQUEST['cuenta']."'";
    list($dbconn) = GetDBconn();
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $Datos=$result->GetRowAssoc($toUpper=false);
      $PlanId=$Datos['plan_id'];
    }
    $_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']=$_REQUEST['NoLiquidacion'];
    $_SESSION['LIQUIDACION_QX']['CUENTA']=$_REQUEST['cuenta'];
    $_SESSION['LIQUIDACION_QX']['PLAN']=$PlanId;
    
    
    if($_REQUEST['CargarCuentaGases']){
      $vectorGases=$_REQUEST['GasesAnestesicos'];
	  
      if(sizeof($vectorGases)<1){
        $this->frmError["MensajeError"]="Elija los gases anestesicos para cargar a la cuenta";
        $this->frmCargaInsumosMedicamentosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
      }     
      $_SESSION['LIQUIDACION_QX']['VECTOR_DATOS']=$vectorGases;      
      $retorno=$this->CallMetodoExterno('app','InvBodegas','user','liquidacionIyMCirugiaGases');      
      if($retorno==false){
        $this->frmError["MensajeError"]=$_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error'];
      }else{
        $this->frmError["MensajeError"]="Cargos Guardados en la Cuenta";
      }
    }else{    
      $Cantidades=$_REQUEST['Cantidades'];
	  $CantidadesSol=$_REQUEST['CantidadesSol'];
		foreach($Cantidades as $codigoProducto=>$vector2){
			foreach($vector2 as $lote=>$vector1){
			  foreach($vector1 as $fecha_vencimiento=>$valor){
				  if(is_numeric($valor) && !empty($valor)){
					  if($valor > $CantidadesSol[$codigoProducto][$lote][$fecha_vencimiento]){
						  $this->frmError["MensajeError"]="La Cantidad para Cargar a la Cuenta no puede ser mayor a la Despachada";
						  $this->frmCargaInsumosMedicamentosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
						  return true;
					  }else{
						  $VectorDatos[$codigoProducto][$lote][$fecha_vencimiento]=$valor;
					  }
				  }
			  }
			}
		}
      $_SESSION['LIQUIDACION_QX']['VECTOR_DATOS']=$VectorDatos;
      if(empty($_SESSION['LIQUIDACION_QX']['PROGRAMACION_INSUMOS'])){
        $retorno=$this->CallMetodoExterno('app','InvBodegas','user','liquidacionIyMCirugia');
      }else{
        $retorno=$this->CallMetodoExterno('app','InvBodegas','user','liquidacionIyMCirugiaNOQX');
      }
      if($retorno==false){
        $this->frmError["MensajeError"]=$_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error'];
      }else{
        $this->frmError["MensajeError"]="Cargos Guardados en la Cuenta";
      }
    }    
    unset($_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']);
    unset($_SESSION['LIQUIDACION_QX']['CUENTA']);
    unset($_SESSION['LIQUIDACION_QX']['PLAN']);
    unset($_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']);
    $this->frmCargaInsumosMedicamentosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
    return true;
  }

  function CargosMedicamentosCuentaPaciente($NoLiquidacion){
/*    $query="SELECT c.codigo_producto,sum(c.cantidad) as cantidad,sum(b.valor_cubierto) as valor_cubierto,
    sum(b.valor_nocubierto) as valor_nocubierto,b.facturado,
    (SELECT d.descripcion FROM inventarios_productos d WHERE c.codigo_producto=d.codigo_producto) as descripcion
    FROM cuentas_codigos_agrupamiento a,cuentas_detalle b,bodegas_documentos_d c
    WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' 
    AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id 
    AND b.cargo='IMD' 
    AND a.bodegas_doc_id=c.bodegas_doc_id 
    AND a.numeracion=c.numeracion 
    AND b.consecutivo=c.consecutivo
    GROUP BY c.codigo_producto,b.facturado";*/
    $query="SELECT 	c.codigo_producto,
					sum(c.cantidad) as cantidad,
					sum(b.valor_cubierto) as valor_cubierto,
					sum(b.valor_nocubierto) as valor_nocubierto,
					b.facturado,
					(	SELECT 	d.descripcion 
						FROM 	inventarios_productos d 
						WHERE 	c.codigo_producto=d.codigo_producto
					) as descripcion,
					J.forma_farmacologica,
					J.concentracion_forma_farmacologica
			FROM 	cuentas_codigos_agrupamiento a,
					cuentas_liquidaciones_qx CLQX,
					cuentas_detalle b,
					bodegas_documentos_d c
					LEFT JOIN 	(	SELECT	ME.codigo_medicamento,
											F.descripcion AS forma_farmacologica,
											ME.concentracion_forma_farmacologica
									FROM	medicamentos ME,
											inv_med_cod_forma_farmacologica F
									WHERE	ME.cod_forma_farmacologica = F.cod_forma_farmacologica
								) AS J
								ON (c.codigo_producto = J.codigo_medicamento)
			WHERE 	a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' 
			AND 	a.cuenta_liquidacion_qx_id = CLQX.cuenta_liquidacion_qx_id
			AND 	CLQX.numerodecuenta = b.numerodecuenta
			AND 	a.codigo_agrupamiento_id=b.codigo_agrupamiento_id 
			AND 	b.cargo='IMD' 
			AND 	a.bodegas_doc_id=c.bodegas_doc_id 
			AND 	a.numeracion=c.numeracion 
			AND 	b.consecutivo=c.consecutivo
			GROUP 	BY c.codigo_producto,b.facturado,J.forma_farmacologica,J.concentracion_forma_farmacologica";
    list($dbconn) = GetDBconn();
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
    return $vars;
  }
  
  function ValidarMedicamentosCuentaPaciente($NoLiquidacion,$Cuenta){
    $query="SELECT b.transaccion    
    FROM cuentas_codigos_agrupamiento a,cuentas_detalle b
    WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND b.cargo='IMD' AND
    b.numerodecuenta=".$Cuenta."";
     
    list($dbconn) = GetDBconn();
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        return true;
      }
    }
    return false;
  }


    function CargosMedicamentosCuentaPacienteSinLiquidacion($cuenta){
    $query="SELECT c.codigo_producto,sum(c.cantidad) as cantidad,sum(b.valor_cubierto) as valor_cubierto,
    sum(b.valor_nocubierto) as valor_nocubierto,b.facturado,
    (SELECT d.descripcion FROM inventarios_productos d WHERE c.codigo_producto=d.codigo_producto) as descripcion
    FROM cuentas_codigos_agrupamiento a,cuentas_detalle b,bodegas_documentos_d c
    WHERE b.numerodecuenta='$cuenta' AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND b.cargo='IMD' AND
    a.bodegas_doc_id=c.bodegas_doc_id AND a.numeracion=c.numeracion AND b.consecutivo=c.consecutivo AND
        a.cuenta_liquidacion_qx_id IS NULL
    GROUP BY c.codigo_producto,b.facturado";
    list($dbconn) = GetDBconn();
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
    return $vars;
  }


  function LlamaSeleccionarCargosCuenta(){
    $this->SeleccionarCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
    return true;
  }

  function GuardarItemsCuentaPaciente(){
    
	if($_REQUEST['Facturar']){
      $CantidatesTot=$_REQUEST['CantFacturar'];
      foreach($CantidatesTot as $codigo=>$valor2){
		foreach($valor2 as $lote=>$valor1){
			foreach($valor1 as $fecha_vencimiento=>$valor){
				if(!empty($valor)){
				  $_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$codigo][$lote][$fecha_vencimiento]=$valor;
				}else{
				  unset($_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$codigo][$lote][$fecha_vencimiento]);
				  unset($_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM'][$codigo][$lote][$fecha_vencimiento]);
				}
			}
		}
      }      
      $query="SELECT a.plan_id FROM cuentas a WHERE a.numerodecuenta='".$_REQUEST['cuenta']."'";
      list($dbconn) = GetDBconn();
	  $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        $Datos=$result->GetRowAssoc($toUpper=false);
        $PlanId=$Datos['plan_id'];
      }
	  $_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']=$_REQUEST['NoLiquidacion'];
      $_SESSION['LIQUIDACION_QX']['CUENTA']=$_REQUEST['cuenta'];
      $_SESSION['LIQUIDACION_QX']['PLAN']=$PlanId;
      $retorno=$this->CallMetodoExterno('app','InvBodegas','user','liquidacionIyMCargosCuenta');
      if($retorno==false){
        $this->frmError["MensajeError"]=$_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error'];
      }else{
        $this->frmError["MensajeError"]="Cargos Guardados en la Cuenta";
      }
      unset($_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']);
      unset($_SESSION['LIQUIDACION_QX']['CUENTA']);
      unset($_SESSION['LIQUIDACION_QX']['PLAN']);
      unset($_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']);
      $this->frmCargaInsumosMedicamentosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
      return true;
    }
    $CantidatesTot=$_REQUEST['CantFacturar'];
    foreach($CantidatesTot as $codigo=>$valor2){
		foreach($valor2 as $lote=>$valor1){
			foreach($valor1 as $fecha_vencimiento=>$valor){
			  if(!empty($valor)){
				$_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$codigo][$lote][$fecha_vencimiento]=$valor;
			  }else{
				$_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$codigo][$lote][$fecha_vencimiento]=0;
			  }
			}
		}
    }
    if($_REQUEST['SeleccionPaquete']){
      $this->BuscadorPaquetesInv($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
      return true;
    }

    if($_REQUEST['SeleccionProducto']){
      $this->BuscadorProductoInv($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
      return true;
    }
  }

  function LlamaBuscadorProductoInv(){
    if($_REQUEST['Volver']){
      $this->SeleccionarCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
      return true;
    }
    $this->BuscadorProductoInv($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['codigoBus'],$_REQUEST['DescripcionBus']);
    return true;
  }

  /**
  *     ProductosInventariosBodega
  *
  *     Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *     @Author Lorena Arag�n G.
  *     @access Public
  *     @return boolean
  */


  function ProductosInventariosBodega($codigoBus,$DescripcionBus){
    $this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
    list($dbconn) = GetDBconn();
        $query = "SELECT 	a.codigo_producto,
					b.descripcion,
					e.lote,
					e.fecha_vencimiento,
					e.existencia_actual as existencia
			FROM 	existencias_bodegas a,
					inventarios_productos b,
					inv_grupos_inventarios c,
					estacion_enfermeria_qx_departamentos d,
					existencias_bodegas_lote_fv e
			WHERE 	d.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' 
			AND 	d.empresa_id=a.empresa_id 
			AND 	d.centro_utilidad=a.centro_utilidad 
			AND 	d.bodega=a.bodega 
			AND		a.codigo_producto=b.codigo_producto 
			AND 	b.grupo_id=c.grupo_id 
			AND		(c.sw_medicamento='1' OR c.sw_insumos='1')
			AND		(	a.empresa_id = e.empresa_id 
					AND a.centro_utilidad = e.centro_utilidad
					AND	a.codigo_producto = e.codigo_producto
					AND	a.bodega = e.bodega
					)";
    if($codigoBus){
      $query.=" AND a.codigo_producto ILIKE '$codigoBus%'";
    }
    if($DescripcionBus){
      $query.=" AND b.descripcion ILIKE '%".strtoupper($DescripcionBus)."%'";
    }
    $query.=" ORDER BY b.descripcion";
        if(empty($_REQUEST['conteo'])){
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->conteo=$result->RecordCount();
        }else{
            $this->conteo=$_REQUEST['conteo'];
        }
        $query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        return $vars;
  }

  /**
  *     ProductosInventariosBodega
  *
  *     Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *     @Author Lorena Arag�n G.
  *     @access Public
  *     @return boolean
  */

  function SeleccionProductoInventariosQx(){

    if(!$_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM'][$_REQUEST['producto']][$_REQUEST['lote']][$_REQUEST['fecha_vencimiento']]){
      $_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM'][$_REQUEST['producto']][$_REQUEST['lote']][$_REQUEST['fecha_vencimiento']][$_REQUEST['descripcion']]=$_REQUEST['existencia'];
      $_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$_REQUEST['producto']][$_REQUEST['lote']][$_REQUEST['fecha_vencimiento']]=0;
    }
    $this->SeleccionarCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['lote'],$_REQUEST['fecha_vencimiento']);
    return true;
  }

  /**
  *     SeleccionPaquetesInventariosQx
  *
  *     Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *     @Author Lorena Arag�n G.
  *     @access Public
  *     @return boolean
  */

  function SeleccionPaquetesInventariosQx(){
    if($_REQUEST['Volver']){
      $this->SeleccionarCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
      return true;
    }
    $this->BuscadorPaquetesInv($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['codigoBus'],$_REQUEST['DescripcionBus']);
    return true;
  }

  /**
  *     ConsultaPaquetesInventariosQx
  *
  *     Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *     @Author Lorena Arag�n G.
  *     @access Public
  *     @return boolean
  */

  function ConsultaPaquetesInventariosQx(){
    $this->LlamaConsultaPaquetesInventariosQx($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['paqueteId'],$_REQUEST['nomPaquete'],$_REQUEST['codigoBus'],$_REQUEST['DescripcionBus']);
    return true;
  }

  /**
  *     ProductosPaquetesInventariosBodega
  *
  *     Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *     @Author Lorena Arag�n G.
  *     @access Public
  *     @return boolean
  */

  function ProductosPaquetesInventariosBodega($paqueteId){
    list($dbconn) = GetDBconn();
    $query="SELECT 	a.codigo_producto,
					c.descripcion,
					a.cantidad,
					f.lote,
					f.fecha_vencimiento,
					f.existencia_actual as existencia
			FROM 	qx_paquetes_contiene_insumos a,
					inventarios b,
					inventarios_productos c,
					existencias_bodegas d,
					estacion_enfermeria_qx_departamentos e,
					existencias_bodegas_lote_fv f
			WHERE 	e.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' 
			AND 	e.empresa_id=d.empresa_id 
			AND 	e.centro_utilidad=d.centro_utilidad 
			AND 	e.bodega=d.bodega 
			AND		d.codigo_producto=a.codigo_producto 
			AND 	d.empresa_id=a.empresa_id 
			AND 	a.paquete_insumos_id='".$paqueteId."' 
			AND  	a.empresa_id=b.empresa_id 
			AND 	a.codigo_producto=b.codigo_producto 
			AND		b.codigo_producto=c.codigo_producto
			AND		(	d.empresa_id = f.empresa_id 
					AND d.centro_utilidad = f.centro_utilidad
					AND	d.codigo_producto = f.codigo_producto
					AND	d.bodega = f.bodega
					AND	f.existencia_actual > 0
					)";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      while(!$result->EOF){
        $vars[]=$result->GetRowAssoc($toUpper=false);
        $result->MoveNext();
      }
    }
    $result->Close();
    return $vars;
  }

  /**
  *     SeleccionPtosPaqueteInv
  *
  *     Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *     @Author Lorena Arag�n G.
  *     @access Public
  *     @return boolean
  */

  function SeleccionPtosPaqueteInv(){
    $regs=$this->ProductosPaquetesInventariosBodega($_REQUEST['paqueteId']);
    for($i=0;$i<sizeof($regs);$i++){
      if(!$_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM'][$regs[$i]['codigo_producto']][$regs[$i]['lote']][$regs[$i]['fecha_vencimiento']]){
        $_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM'][$regs[$i]['codigo_producto']][$regs[$i]['lote']][$regs[$i]['fecha_vencimiento']][$regs[$i]['descripcion']]=$regs[$i]['existencia'];
      }
      $_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$regs[$i]['codigo_producto']][$regs[$i]['lote']][$regs[$i]['fecha_vencimiento']]+=$regs[$i]['cantidad'];
    }
    $this->SeleccionarCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
    return true;
  }

   /**
  *     PaquetesInventariosBodega
  *
  *     Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *     @Author Lorena Arag�n G.
  *     @access Public
  *     @return boolean
  */

  function PaquetesInventariosBodega($codigoBus,$DescripcionBus){


    list($dbconn) = GetDBconn();
        $query = "SELECT a.paquete_insumos_id,a.descripcion
    FROM qx_paquetes_insumos a";
    if($codigoBus){
      $query.=" WHERE a.paquete_insumos_id LIKE '$codigoBus%'";
      $yaand=1;
    }
    if($DescripcionBus){
      if($yaand==1){
        $query.=" AND a.descripcion LIKE '%".strtoupper($DescripcionBus)."%'";
      }else{
        $query.=" WHERE a.descripcion LIKE '%".strtoupper($DescripcionBus)."%'";
      }
    }
    $query.=" ORDER BY a.descripcion";

        if(empty($_REQUEST['conteo'])){
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->conteo=$result->RecordCount();
        }else{
            $this->conteo=$_REQUEST['conteo'];
        }
        $query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        return $vars;
  }

  function EliminarItemsCuentaPaciente(){
    unset($_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM'][$_REQUEST['codigoProducto']][$_REQUEST['lote']][$_REQUEST['fecha_vencimiento']]);
    unset($_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$_REQUEST['codigoProducto']][$_REQUEST['lote']][$_REQUEST['fecha_vencimiento']]);
    $this->SeleccionarCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['lote'],$_REQUEST['fecha_vencimiento']);
    return true;
  }

  function LlamaDevolucionCargosCuenta(){
    $this->DevolucionCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['ProductoFechaVence'],$_REQUEST['NomProductoFechaVence']);
    return true;
  }

  function GuardarDevolucioIyMCuenta(){
    $Productos=$_REQUEST['CantidadDevol'];
	$in=0;
    foreach($Productos as $codigo=>$valor2){
		foreach($valor2 as $lote=>$valor1){
			foreach($valor1 as $fecha_vencimiento=>$valor){
			  if(!empty($valor)){
				$_SESSION['IYM_CUENTAS_QX_DEVOL']['PRODUCTOS_IYM_CANTIDADES_DEV'][$codigo][$lote][$fecha_vencimiento]=$valor;
				$in=1;
			  }else{
				unset($_SESSION['IYM_CUENTAS_QX_DEVOL']['PRODUCTOS_IYM_CANTIDADES_DEV'][$codigo][$lote][$fecha_vencimiento]);
			  }
			}
		}
    }
    if($_REQUEST['Devolver']){
      if($in==0){
        $this->frmError["MensajeError"]="Digite las Cantidades para Realizar la Devolucion a la Cuenta";
        $this->DevolucionCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
      }

      $VectorFv=$_REQUEST['VectorFechas'];
      $VectorTotal=$_REQUEST['VectorTotal'];
	  
      foreach($_SESSION['IYM_CUENTAS_QX_DEVOL']['PRODUCTOS_IYM_CANTIDADES_DEV'] as $codigoP=>$vector2){
		foreach($vector2 as $lote=>$vector1){
			foreach($vector1 as $fecha_vencimiento=>$Cantidad){
				if(in_array($codigoP,$VectorFv)){
				  if(empty($_SESSION['IYM_CUENTAS_QX_DEVOL']['FECHAS_VENCE'][$codigoP])){
					$this->frmError["MensajeError"]="Debe Introducir los lotes y Fechas de Vencimiento de los Productos que lo Requieren";
					$this->DevolucionCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['ProductoFechaVence'],$_REQUEST['NomProductoFechaVence']);
					return true;
				  }else{
					foreach($_SESSION['IYM_CUENTAS_QX_DEVOL']['FECHAS_VENCE'][$codigoP] as  $lote=>$arreglo){
					  (list($cantidades,$fecha)=explode('||//',$arreglo));
					  $sumaCantLotes+=$cantidades;
					}
					if($Cantidad!=$sumaCantLotes){
					  $this->frmError["MensajeError"]="Debe Insertar las Cantidades de los Lotes de las Cantidades a Devolver";
					  $this->DevolucionCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['ProductoFechaVence'],$_REQUEST['NomProductoFechaVence']);
					  return true;
					}
				  }
				}
				if($Cantidad>$VectorTotal[$codigoP][$lote][$fecha_vencimiento]){
				  $this->frmError["MensajeError"]="La Cantidad a Devolver no puede se mayor al Despacho";
				  $this->DevolucionCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['ProductoFechaVence'],$_REQUEST['NomProductoFechaVence']);
				  return true;
				}
			}
		}
      }
      $query="SELECT a.plan_id FROM cuentas a WHERE a.numerodecuenta='".$_REQUEST['cuenta']."'";
      list($dbconn) = GetDBconn();
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        $Datos=$result->GetRowAssoc($toUpper=false);
        $PlanId=$Datos['plan_id'];
      }
      $_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']=$_REQUEST['NoLiquidacion'];
      $_SESSION['LIQUIDACION_QX']['CUENTA']=$_REQUEST['cuenta'];
      $_SESSION['LIQUIDACION_QX']['PLAN']=$PlanId;
      $retorno=$this->CallMetodoExterno('app','InvBodegas','user','DevolucionliquidacionIyMCargosCuenta');
      if($retorno==false){
        $this->frmError["MensajeError"]=$_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error'];
      }else{
        $this->frmError["MensajeError"]="Cargos Guardados en la Cuenta";
      }
      unset($_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']);
      unset($_SESSION['LIQUIDACION_QX']['CUENTA']);
      unset($_SESSION['LIQUIDACION_QX']['PLAN']);
      unset($_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']);
      $this->frmCargaInsumosMedicamentosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
      return true;
    }

    if($_REQUEST['insertarFV']){

      if(empty($_REQUEST['NoLote']) || empty($_REQUEST['cantidadLote']) || empty($_REQUEST['FechaVmto'])){
        $this->frmError["MensajeError"]="Inserte Todos Los Datos Para la Fecha de Vencimiento";
        $this->DevolucionCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['ProductoFechaVence'],$_REQUEST['NomProductoFechaVence']);
        return true;
      }else{
        if($_SESSION['IYM_CUENTAS_QX_DEVOL']['FECHAS_VENCE'][$_REQUEST['ProductoFechaVence']]){
          foreach($_SESSION['IYM_CUENTAS_QX_DEVOL']['FECHAS_VENCE'][$_REQUEST['ProductoFechaVence']] as  $lote=>$arreglo){
            (list($cantidades,$fecha)=explode('||//',$arreglo));
            $sumaCantLotes+=$cantidades;
          }
        }
        if(($sumaCantLotes+$_REQUEST['cantidadLote']) > $_SESSION['IYM_CUENTAS_QX_DEVOL']['PRODUCTOS_IYM_CANTIDADES_DEV'][$_REQUEST['ProductoFechaVence']]){
          $this->frmError["MensajeError"]="La Cantidades de los Lotes superan las Cantidades para Devolucion";
          $this->DevolucionCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['ProductoFechaVence'],$_REQUEST['NomProductoFechaVence']);
          return true;
        }
        $_SESSION['IYM_CUENTAS_QX_DEVOL']['FECHAS_VENCE'][$_REQUEST['ProductoFechaVence']][$_REQUEST['NoLote']]=$_REQUEST['cantidadLote'].'||//'.$_REQUEST['FechaVmto'];
        $_REQUEST['FechaVmto']='';$_REQUEST['NoLote']='';$_REQUEST['cantidadLote']='';
      }
      $this->DevolucionCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['ProductoFechaVence'],$_REQUEST['NomProductoFechaVence']);
      return true;
    }

    $this->DevolucionCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['ProductoFechaVence'],urldecode($_REQUEST['NomProductoFechaVence']));
    
	return true;

  }

  function HallarRequerimientoFechasVence($codigo_producto){
    list($dbconn) = GetDBconn();
        $query = "SELECT b.sw_control_fecha_vencimiento
    FROM estacion_enfermeria_qx_departamentos a,existencias_bodegas b
    WHERE a.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' AND a.empresa_id=b.empresa_id AND a.centro_utilidad=b.centro_utilidad AND a.bodega=b.bodega AND
    b.codigo_producto='".$codigo_producto."'";
    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $vars=$result->GetRowAssoc($toUpper=false);
        }
        return $vars['sw_control_fecha_vencimiento'];
  }

  function EliminarFechaVencimientos(){
    unset($_SESSION['IYM_CUENTAS_QX_DEVOL']['FECHAS_VENCE'][$_REQUEST['codigoProducto']][$_REQUEST['lote']]);
    $this->DevolucionCargosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['ProductoFechaVence'],$_REQUEST['NomProductoFechaVence']);
    return true;
  }

  function CargosIyMCuentaPacienteTotal($NoLiquidacion){
    list($dbconn) = GetDBconn();
	$query="SELECT x.*,J.forma_farmacologica,J.concentracion_forma_farmacologica,(x.cantidad - coalesce(z.cantidad,0)) as total
			FROM 	(SELECT c.codigo_producto,
							c.lote,c.fecha_vencimiento,
							sum(c.cantidad) as cantidad,
							(	SELECT 	d.descripcion 
								FROM 	inventarios_productos d
								WHERE c.codigo_producto=d.codigo_producto
							) as descripcion
					FROM 	cuentas_codigos_agrupamiento a,
							cuentas_detalle b,
							bodegas_documentos_d c
							
					WHERE 	a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' 
					AND 	a.codigo_agrupamiento_id=b.codigo_agrupamiento_id 
					AND 	b.cargo='IMD' 
					AND		a.bodegas_doc_id=c.bodegas_doc_id 
					AND 	a.numeracion=c.numeracion 
					AND 	b.consecutivo=c.consecutivo
					GROUP BY c.codigo_producto,c.lote,c.fecha_vencimiento
					) as x
			LEFT JOIN 	(SELECT c.codigo_producto,
								c.lote,
								c.fecha_vencimiento,
								sum(c.cantidad) as cantidad
						  FROM 	cuentas_codigos_agrupamiento a,
								cuentas_detalle b,
								bodegas_documentos_d c
						  WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' 
						  AND 	a.codigo_agrupamiento_id=b.codigo_agrupamiento_id 
						  AND 	b.cargo='DIMD' 
						  AND	a.bodegas_doc_id=c.bodegas_doc_id 
						  AND 	a.numeracion=c.numeracion 
						  AND 	b.consecutivo=c.consecutivo
						  GROUP BY c.codigo_producto,c.lote,c.fecha_vencimiento
						) z ON (x.codigo_producto=z.codigo_producto
							AND	x.lote = z.lote
							AND	x.fecha_vencimiento = z.fecha_vencimiento)
			LEFT JOIN 	(SELECT	ME.codigo_medicamento,
								F.descripcion AS forma_farmacologica,
								ME.concentracion_forma_farmacologica
						FROM	medicamentos ME,
								inv_med_cod_forma_farmacologica F
						WHERE	ME.cod_forma_farmacologica = F.cod_forma_farmacologica
						) AS J
						ON (x.codigo_producto = J.codigo_medicamento)			
			ORDER BY x.codigo_producto";
			
    list($dbconn) = GetDBconn();
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
    return $vars;
  }

  function CargosMedicamentosCuentaPacienteDevol($NoLiquidacion){
/*    $query="SELECT c.codigo_producto,sum(c.cantidad) as cantidad,sum(b.valor_cubierto) as valor_cubierto,
    sum(b.valor_nocubierto) as valor_nocubierto,b.facturado,
    (SELECT d.descripcion FROM inventarios_productos d WHERE c.codigo_producto=d.codigo_producto) as descripcion
    FROM cuentas_codigos_agrupamiento a,cuentas_detalle b,bodegas_documentos_d c
    WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."'
    AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND b.cargo='DIMD' AND
    a.bodegas_doc_id=c.bodegas_doc_id AND a.numeracion=c.numeracion AND b.consecutivo=c.consecutivo
    GROUP BY c.codigo_producto,b.facturado";*/
    $query="SELECT 	c.codigo_producto,
					sum(c.cantidad) as cantidad,
					sum(b.valor_cubierto) as valor_cubierto,
					sum(b.valor_nocubierto) as valor_nocubierto,
					b.facturado,
					(	SELECT 	d.descripcion 
						FROM 	inventarios_productos d 
						WHERE 	c.codigo_producto=d.codigo_producto
					) as descripcion,
					J.forma_farmacologica,
					J.concentracion_forma_farmacologica
			FROM 	cuentas_codigos_agrupamiento a,
					cuentas_liquidaciones_qx CLQX,
					cuentas_detalle b,
					bodegas_documentos_d c
					LEFT JOIN 	(	SELECT	ME.codigo_medicamento,
											F.descripcion AS forma_farmacologica,
											ME.concentracion_forma_farmacologica
									FROM	medicamentos ME,
											inv_med_cod_forma_farmacologica F
									WHERE	ME.cod_forma_farmacologica = F.cod_forma_farmacologica
								) AS J
								ON (c.codigo_producto = J.codigo_medicamento)
			WHERE 	a.cuenta_liquidacion_qx_id='".$NoLiquidacion."'
			AND 	a.cuenta_liquidacion_qx_id = CLQX.cuenta_liquidacion_qx_id
			AND 	CLQX.numerodecuenta = b.numerodecuenta
			AND 	a.codigo_agrupamiento_id=b.codigo_agrupamiento_id AND b.cargo='DIMD' 
			AND		a.bodegas_doc_id=c.bodegas_doc_id 
			AND 	a.numeracion=c.numeracion 
			AND 	b.consecutivo=c.consecutivo
			GROUP BY c.codigo_producto,b.facturado,J.forma_farmacologica,J.concentracion_forma_farmacologica";
    list($dbconn) = GetDBconn();
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
    return $vars;
  }

     function VerificarCuentaActiva($TipoDocumento,$Documento){
          $query="SELECT MAX(a.ingreso), MAX(b.numerodecuenta)
          FROM ingresos a
          JOIN cuentas b ON(a.ingreso=b.ingreso AND (b.estado='1' OR b.estado='2'))
          WHERE a.tipo_id_paciente='".$TipoDocumento."' AND a.paciente_id='".$Documento."' AND
          a.estado IN ('1','2')";
          list($dbconn) = GetDBconn();
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{
               if($result->RecordCount()>0){
               return 1;
               }
          }
          return 0;
     }

    function ProgramacionesQXPendientes($TipoDocumento,$Documento){
        GLOBAL $ADODB_FETCH_MODE;
	  //Adicion de filtro para query y Tunning del mismo.        
       //echo '<br><br>Query proc: '.
       $query = "SELECT  SUB2.*,
                         liqui.cuenta_liquidacion_qx_id,
                         d.descripcion
               
               FROM
               (
                    SELECT SUB.*,
                           quir.descripcion as quirofano,
                           ter.nombre_tercero as cirujano,
                           pl.plan_descripcion,
                           cd.numerodecuenta
                         
                    FROM
                    (
                         SELECT i.ingreso, i.tipo_id_paciente, i.paciente_id,
                                a.programacion_id,
                                b.hora_inicio, b.hora_fin, b.quirofano_id,
                                
				--c.procedimiento_qx, 
				--JAB
				hcnop.procedimiento_qx,
				--c.tipo_id_cirujano, 
				--c.cirujano_id,
				hcnoc.tipo_id_cirujano, hcnoc.cirujano_id, c.plan_id,
                                pac.primer_nombre||' '||pac.segundo_nombre||' '||pac.primer_apellido||' '||pac.segundo_apellido as nombre
                              
                         FROM ingresos i,
                              qx_programaciones a,
                              qx_quirofanos_programacion b,
                              qx_procedimientos_programacion c,
                              pacientes pac
			      
			      --JAB
			      , hc_notas_operatorias_procedimientos hcnop, 
			      hc_notas_operatorias_cirugias hcnoc
                              
                         WHERE i.tipo_id_paciente = '".$TipoDocumento."' 
                         AND i.paciente_id = '".$Documento."'
                         AND i.estado in ('1','2') 
                         AND i.ingreso = (SELECT MAX(ingreso) FROM ingresos WHERE tipo_id_paciente= '".$TipoDocumento."' AND paciente_id= '".$Documento."' AND estado in ('1','2'))
                         AND a.tipo_id_paciente = i.tipo_id_paciente
                         AND a.paciente_id = i.paciente_id
                         AND a.estado = '1' 
                         AND a.programacion_id = b.programacion_id
                         AND b.qx_tipo_reserva_quirofano_id = '3'
                         AND a.programacion_id = c.programacion_id
                         AND pac.tipo_id_paciente = i.tipo_id_paciente 
                         AND pac.paciente_id = i.paciente_id 
			 
			 --JAB
			 and a.programacion_id=hcnoc.programacion_id 
			 and hcnoc.hc_nota_operatoria_cirugia_id=hcnop.hc_nota_operatoria_cirugia_id 
			 and hcnop.realizado='1'
			 
                    ) AS SUB
                    LEFT JOIN terceros ter ON (SUB.tipo_id_cirujano = ter.tipo_id_tercero AND SUB.cirujano_id = ter.tercero_id),
                    qx_quirofanos quir,
                    planes pl
                    LEFT JOIN cuentas cd ON (pl.plan_id = cd.plan_id AND cd.estado IN('1','2'))
                    
                    WHERE quir.quirofano = SUB.quirofano_id
                    AND   pl.plan_id = SUB.plan_id
                    AND   cd.ingreso = SUB.ingreso
               
               ) AS SUB2
               LEFT JOIN cuentas_liquidaciones_qx liqui ON(liqui.programacion_id = SUB2.programacion_id AND liqui.numerodecuenta = SUB2.numerodecuenta),
               cups d
               
               WHERE d.cargo = SUB2.procedimiento_qx";
        
        /*="SELECT a.programacion_id,pl.plan_id,c.tipo_id_cirujano,c.cirujano_id,ter.nombre_tercero as cirujano,b.hora_inicio,b.hora_fin,quir.descripcion as quirofano,
        pac.tipo_id_paciente,pac.paciente_id,pac.primer_nombre||' '||pac.segundo_nombre||' '||pac.primer_apellido||' '||pac.segundo_apellido as nombre,
        c.procedimiento_qx,d.descripcion,pl.plan_descripcion,cd.numerodecuenta,liqui.cuenta_liquidacion_qx_id,i.ingreso
        FROM qx_programaciones a,qx_quirofanos_programacion b
        LEFT JOIN qx_quirofanos quir ON (b.quirofano_id=quir.quirofano),
        pacientes pac,
        qx_procedimientos_programacion c
        LEFT JOIN terceros ter ON (c.tipo_id_cirujano=ter.tipo_id_tercero AND c.cirujano_id=ter.tercero_id)
        LEFT JOIN planes pl ON (c.plan_id=pl.plan_id)
        LEFT JOIN ingresos i ON (i.tipo_id_paciente='".$TipoDocumento."' AND i.paciente_id='".$Documento."' AND i.estado in ('1','2') 
                                 AND i.ingreso = (SELECT MAX(ingreso)
										   FROM ingresos 
										   WHERE tipo_id_paciente='".$TipoDocumento."' 
										   AND paciente_id='".$Documento."'
                                                     AND estado in ('1','2')))
        LEFT JOIN cuentas cd ON (pl.plan_id=cd.plan_id AND cd.estado IN('1','2') AND cd.ingreso=i.ingreso)
        LEFT JOIN cuentas_liquidaciones_qx liqui ON(c.programacion_id=liqui.programacion_id AND cd.numerodecuenta=liqui.numerodecuenta)
        ,cups d
        WHERE a.tipo_id_paciente='".$TipoDocumento."' AND a.paciente_id='".$Documento."' AND
        a.estado='1' AND a.programacion_id=b.programacion_id AND b.qx_tipo_reserva_quirofano_id='3' AND
        pac.tipo_id_paciente=a.tipo_id_paciente AND pac.paciente_id=a.paciente_id AND
        a.programacion_id=c.programacion_id AND c.procedimiento_qx=d.cargo";*/
               list($dbconn) = GetDBconn();
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $result = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($vars=$result->FetchRow()){
                         $datos['vector'][$vars[programacion_id]][$vars[plan_id]][$vars[tipo_id_cirujano].'-'.$vars[cirujano_id]][$vars[procedimiento_qx]]=$vars;
                         $datos['datos_programacion'][$vars[programacion_id]]=$vars;
                         $datos['datos_planes'][$vars[programacion_id]][$vars[plan_id]]=$vars;
                         $datos['datos_cirujanos'][$vars[programacion_id]][$vars[plan_id]][$vars[tipo_id_cirujano].'-'.$vars[cirujano_id]]=$vars;
               }
               
          return $datos;
     }

    function ConsultarGases($programacion,$ingreso)
	{
		list($dbconn) = GetDBconn();
		
		$VALOR=$programacion;
		//echo '<br>SQL GASES: '.
		$query = "SELECT MAX(a.evolucion_id), a.hc_nota_operatoria_cirugia_id FROM hc_notas_operatorias_cirugias a WHERE  programacion_id= ".$VALOR." GROUP BY hc_nota_operatoria_cirugia_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount() > 0){
				$evolucion_id = $result->fields[0];
			}
		}
		/*	
		echo '<br>SQL GASES2: '.$sql = "select tipo_gas_id, tipo_suministro_id, frecuencia_id, tiempo_suministro from hc_notaqx_gases_anestesicos
		where IngresoId = ".$ingreso."
		";*/
		//jab
		$sql = "select h.tipo_gas_id, h.tipo_suministro_id, h.frecuencia_id, h.tiempo_suministro, g.unidad
		from hc_notaqx_gases_anestesicos h, tipos_frecuencia_gases g
		where h.IngresoId = ".$ingreso." and h.frecuencia_id=g.frecuencia_id
		";
		
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		
			if($result->RecordCount()>0)
			{
				$i=0;
				while (!$result->EOF) {
					$datos[$i][0] =$result->fields[0];
					$datos[$i][1] =$result->fields[1];
					$datos[$i][2] =$result->fields[2];
					$datos[$i][3] =$result->fields[3];
					//jab
					$datos[$i][4] =$result->fields[4];
			  		$result->MoveNext();
					$i++;
				}
				return $datos;
			}
			else{
				return false;
			}
		}
	
		return true;
	}
    
    
    
    
    
    function VariablesLiquidacionCirugia(){

        list($dbconn) = GetDBconn();

        $Fil_plan=" AND b.plan_id='".$_REQUEST['plan_id']."'";
        $Fil_cuenta=" AND b.numerodecuenta='".$_REQUEST['numerodecuenta']."'";

        $query="SELECT a.tipo_id_paciente,a.paciente_id,i.ingreso,b.numerodecuenta,
        pac.primer_nombre||' '||pac.segundo_nombre||' '||pac.primer_apellido||' '||pac.segundo_apellido as nombre
        FROM qx_programaciones a,ingresos i
        JOIN cuentas b ON (i.ingreso=b.ingreso AND (b.estado='1' OR b.estado='2') $Fil_cuenta $Fil_plan),
        pacientes pac
        WHERE a.programacion_id='".$_REQUEST['programacion_id']."' AND
        a.tipo_id_paciente=i.tipo_id_paciente AND a.paciente_id=i.paciente_id AND
        i.estado IN ('1','2') AND i.tipo_id_paciente=pac.tipo_id_paciente AND
        i.paciente_id=pac.paciente_id";

        $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()<1){
                $this->frmError["MensajeError"]="El Paciente no tiene un Ingreso Activo";
                $this->DatosPacientes($_REQUEST['TipoDocumentoBus'],$_REQUEST['DocumentoBus'],$_REQUEST['NoIngresoBus'],$_REQUEST['NoCuentaBus'],$_REQUEST['EstadoBus'],$_REQUEST['FechaCirugiaBus']);
                return true;
            }else{
                $vars=$result->GetRowAssoc($toUpper=false);
                $TipoDocumento=$vars['tipo_id_paciente'];
                $Documento=$vars['paciente_id'];
                $nombrePaciente=$vars['nombre'];
                $cuenta=$vars['numerodecuenta'];
                $ingreso=$vars['ingreso'];
                //d.via_acceso,d.tipo_cirugia,d.ambito_cirugia,d.finalidad_procedimiento_id
                $query="SELECT b.tipo_id_ayudante,b.ayudante_id,b.tipo_id_tercero,b.tercero_id,c.quirofano_id,
                c.hora_inicio,c.hora_fin
                FROM qx_programaciones a
                LEFT JOIN qx_anestesiologo_programacion b ON (a.programacion_id=b.programacion_id)
                LEFT JOIN qx_quirofanos_programacion c ON (a.programacion_id=c.programacion_id AND c.qx_tipo_reserva_quirofano_id='3')
                --LEFT JOIN qx_datos_procedimientos_cirugias d ON (a.programacion_id=d.programacion_id)
                WHERE a.programacion_id='".$_REQUEST['programacion_id']."'";

             	$query="SELECT tipo_id_ayudante,ayudante_id, evolucion_id,tipo_id_anestesiologo as tipo_id_tercero,anestesiologo_id as tercero_id,quirofano_id,
                hora_inicio,hora_fin, via_acceso, tipo_cirugia, ambito_cirugia, finalidad_procedimiento_id 
		FROM hc_notas_operatorias_cirugias WHERE programacion_id = '".$_REQUEST['programacion_id']."'
		ORDER BY evolucion_id ASC";
		
		$result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }else{
                    if($result->RecordCount()>0){
                        $vars1=$result->GetRowAssoc($toUpper=false);
                        if($vars1['tipo_id_ayudante'] && $vars1['ayudante_id']){
                            $_SESSION['Liquidacion_QX']['AYUDANTE']=$vars1['tipo_id_ayudante'].'||//'.$vars1['ayudante_id'];
                        }
                        if($vars1['tipo_id_tercero'] && $vars1['tercero_id']){
                            $_SESSION['Liquidacion_QX']['ANESTESIOLOGO']=$vars1['tipo_id_tercero'].'||//'.$vars1['tercero_id'];
                        }
                        if($vars1['quirofano_id']){
                            $_SESSION['Liquidacion_QX']['TIPO_SALA']='01/1';
                            $_SESSION['Liquidacion_QX']['NO_QUIRO']='1';
                            $_SESSION['Liquidacion_QX']['QUIROFANO']=$vars1['quirofano_id'];
                        }
                        if($vars1['hora_inicio'] && $vars1['hora_fin']){
                            (list($fechaIn,$horaIn)=explode(' ',$vars1['hora_inicio']));
                            (list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
                            $_SESSION['Liquidacion_QX']['FECHA_CIRUGIA']=$diaIn.'/'.$mesIn.'/'.$anoIn;
                            (list($hhIn,$mmIn)=explode(':',$horaIn));
                            $_SESSION['Liquidacion_QX']['HORA_INICIO']=$hhIn;
                            $_SESSION['Liquidacion_QX']['MIN_INICIO']=$mmIn;
                            (list($fechaFn,$horaFn)=explode(' ',$vars1['hora_fin']));
                            (list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
                            (list($hhFn,$mmFn)=explode(':',$horaFn));
                            $segundos=(mktime($hhFn,$mmFn,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn))/60;
                            $Horas=(int)($segundos/60);
                            $Minutos=($segundos%60);
                            $_SESSION['Liquidacion_QX']['HORA_DURACION']=str_pad($Horas,2,0,STR_PAD_LEFT);
                            $_SESSION['Liquidacion_QX']['MIN_DURACION']=str_pad($Minutos,2,0,STR_PAD_LEFT);
                        }
                        if($vars1['via_acceso']){
                            $_SESSION['Liquidacion_QX']['VIA_ACCESO']=$vars1['via_acceso'];
                        }
                        if($vars1['tipo_cirugia']){
                            $_SESSION['Liquidacion_QX']['TIPO_CIRUGIA']=$vars1['tipo_cirugia'];
                        }
                        if($vars1['ambito_cirugia']){
                            $_SESSION['Liquidacion_QX']['AMBITO_CIRUGIA']=$vars1['ambito_cirugia'];
                        }                        
                        if($vars1['finalidad_procedimiento_id']){
                            $_SESSION['Liquidacion_QX']['FINALIDAD_CIRUGIA']=$vars1['finalidad_procedimiento_id'];
                        }
                        $gases = $this->ConsultarGases($_REQUEST['programacion_id'],$vars['ingreso']);
			//echo '<br><br>JULIANBOCA: <pre>';print_r($gases);
			if($gases){
				for($i=0;$i<sizeof($gases);$i++){
					$_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGas']=$gases[$i][0];           
					$_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGasDes']=$this->consultartipogas($gases[$i][0]);           
					$_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGas']=$gases[$i][1];           
					$_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGasDes']=$this->consultartiposuministro($gases[$i][1]);           
					/*$gasfrecuenciades = explode("-",$gases[$i][2]); 
					$_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGas']=$gasfrecuenciades[0];
					$_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGasDes']=$gasfrecuenciades[1];*/
					//jab
					$_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGas']=$gases[$i][2];
					$_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGasDes']=$gases[$i][4];
					$_SESSION['Liquidacion_QX']['GASES'][$i]['MinutosGas']=$gases[$i][3];           
				}
			}
//jab
                        //echo 
			$query="SELECT b.tipo_id_cirujano||'||//'||b.cirujano_id||'||//'||ter.nombre_tercero as cirujano,
                        --b.procedimiento_qx
			--JAB
			hcnop.procedimiento_qx||'||//'||c.descripcion||'||//'||c.sw_bilateral as procedimiento,
                        
			a.diagnostico_id||'||//'||d.diagnostico_nombre as diagnostico
                        FROM qx_programaciones a
                        LEFT JOIN diagnosticos d ON(a.diagnostico_id=d.diagnostico_id),
                        qx_procedimientos_programacion b,cups c,terceros ter
			
			--JAB
			,hc_notas_operatorias_procedimientos hcnop, hc_notas_operatorias_cirugias hcnoc
			
                        WHERE a.programacion_id='".$_REQUEST['programacion_id']."' AND
                        a.programacion_id=b.programacion_id 
			--AND b.procedimiento_qx=c.cargo AND 
			--JAB
			AND hcnop.procedimiento_qx=c.cargo AND
			
			ter.tipo_id_tercero=b.tipo_id_cirujano AND
                        ter.tercero_id=b.cirujano_id $Fil_plan
                        
			--JAB
			and a.programacion_id=hcnoc.programacion_id 
			and hcnoc.hc_nota_operatoria_cirugia_id=hcnop.hc_nota_operatoria_cirugia_id
			and hcnop.realizado='1'";
			
			GLOBAL $ADODB_FETCH_MODE;
                        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                        $result = $dbconn->Execute($query);
                        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }else{
                            if($result->RecordCount()>0){
                                while ($data = $result->FetchRow()){
                                    $datos[$data['cirujano']][]=$data;
                                }
                                $cont=1;
                                $indice=1;
                                foreach($datos as $cirujano => $vectorDat){
                                        $_SESSION['Liquidacion_QX']['CIRUJANOS'][$cont]=$cirujano;
                                        $cont++;
                                    foreach($vectorDat as $plus => $vector){
                                        $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujano][$indice]=$vector['procedimiento'];
                                        $indice++;
                                        if($indice==1){
                                            $_SESSION['Liquidacion_QX']['PROCEDIMIENTOS_DIAGNOSTICOS'][$_SESSION['Liquidacion_QX']['PROCEDIMIENTOS'][$cirujano][$indice]][1]=$vector['diagnostico'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $_SESSION['Liquidacion_QX']['PROGRAMACION_ID']=$_REQUEST['programacion_id'];
        $this->DatosRequeridosLiquidacion($TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso);
        return true;
    }
function consultartipogas($id){
	
		list($dbconn) = GetDBconn();
		$sql="select descripcion from tipos_gases where tipo_gas_id='".$id."'";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		
			if($result->RecordCount()>0)
			{
				$result->Close();
				return $result->fields[0];
			}
		}
		
		return true;
	
	}
	function consultartiposuministro($id){
	
		list($dbconn) = GetDBconn();
		 $sql="select descripcion from tipos_metodos_suministro_gases where tipo_suministro_id='".$id."'";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		
			if($result->RecordCount()>0)
			{
				$result->Close();
				return $result->fields[0];
			}
		}
		
		return true;
	
	}
    function SeleccionGrupoUltimoProcedimiento(){

        list($dbconn) = GetDBconn();
        $query="SELECT a.tipo_cargo||'/'||a.grupo_tipo_cargo as grupo
        FROM cups a
        WHERE a.cargo='".$_SESSION['Liquidacion_QX']['ULTIMO_PROCEDIMIENTO']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $vars=$result->GetRowAssoc($toUpper=false);
            return $vars['grupo'];
        }
    }

    function LiquidacionEquiposQX(){
        $this->EquiposEnlaLiquidacionFijos($_REQUEST['liquidacionId']);
        $this->EquiposEnlaLiquidacionMoviles($_REQUEST['liquidacionId']);

        if(empty($_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO'][$_REQUEST['liquidacionId']]) && empty($_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO'][$_REQUEST['liquidacionId']])){
            $this->EquiposEnlaProgramacionFijos($_REQUEST['liquidacionId']);
            $this->EquiposEnlaProgramacionMoviles($_REQUEST['liquidacionId']);
        }
        $this->FrmLiquidacionEquiposQX($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
    }

    function EquiposEnlaLiquidacionFijos($liquidacionId){
        list($dbconn) = GetDBconn();
        $query="(SELECT a.equipo_id,b.descripcion as nom_equipo,c.departamento,c.descripcion as quirofano,dpto.descripcion as departamento,tipo.descripcion as tipo_equipo,a.duracion
        FROM cuentas_liquidaciones_qx_equipos_fijos a,qx_equipos_quirofanos b,qx_quirofanos c,departamentos dpto,qx_tipo_equipo_fijo tipo
        WHERE a.cuenta_liquidacion_qx_id='".$liquidacionId."' AND a.equipo_id=b.equipo_id AND
        b.quirofano_id=c.quirofano AND b.estado='1' AND dpto.departamento=c.departamento AND
        tipo.tipo_equipo_fijo_id=b.tipo_equipo_fijo_id
        )";
        GLOBAL $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while($datos=$result->FetchRow()){
                $_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO'][$liquidacionId][$datos['departamento']][$datos['quirofano']][$datos['tipo_equipo']][$datos['equipo_id']]=$datos['nom_equipo'];
                $_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO_DURACION'][$liquidacionId][$datos['departamento']][$datos['quirofano']][$datos['tipo_equipo']][$datos['equipo_id']]=$datos['duracion'];
            }
        }
        return true;
    }

    function EquiposEnlaLiquidacionMoviles($liquidacionId){
        list($dbconn) = GetDBconn();
        $query="(SELECT a.equipo_id,b.descripcion as nom_equipo,b.departamento,dpto.descripcion as departamento,tipo.descripcion as tipo_equipo,a.duracion
        FROM cuentas_liquidaciones_qx_equipos_moviles a,qx_equipos_moviles b,departamentos dpto,qx_tipo_equipo_movil tipo
        WHERE a.cuenta_liquidacion_qx_id='".$liquidacionId."' AND a.equipo_id=b.equipo_id AND
        b.estado='1' AND dpto.departamento=b.departamento AND
        tipo.tipo_equipo_id=b.tipo_equipo_id
        )";
        GLOBAL $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while($datos=$result->FetchRow()){
                $_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO'][$liquidacionId][$datos['departamento']][$datos['tipo_equipo']][$datos['equipo_id']]=$datos['nom_equipo'];
                $_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO_DURACION'][$liquidacionId][$datos['departamento']][$datos['tipo_equipo']][$datos['equipo_id']]=$datos['duracion'];
            }
        }
        return true;
    }

    function EquiposEnlaProgramacionFijos($liquidacionId){
        list($dbconn) = GetDBconn();
        $query="(SELECT d.equipo_id,d.descripcion as nom_equipo,c.departamento,c.descripcion as quirofano,dpto.descripcion as departamento,tipo.descripcion as tipo_equipo,b.hora_inicio,b.hora_fin
        FROM cuentas_liquidaciones_qx a,qx_quirofanos_programacion b,qx_quirofanos c,qx_equipos_quirofanos d,departamentos dpto,qx_tipo_equipo_fijo tipo
        WHERE a.cuenta_liquidacion_qx_id='".$liquidacionId."' AND a.programacion_id=b.programacion_id AND
        b.quirofano_id=c.quirofano AND c.quirofano=d.quirofano_id AND d.estado='1' AND dpto.departamento=c.departamento AND
        tipo.tipo_equipo_fijo_id=d.tipo_equipo_fijo_id
        )";
        GLOBAL $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while($datos=$result->FetchRow()){
                $_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO'][$liquidacionId][$datos['departamento']][$datos['quirofano']][$datos['tipo_equipo']][$datos['equipo_id']]=$datos['nom_equipo'];
                (list($fechaIn,$horaIn)=explode(' ',$datos[hora_inicio]));
                (list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
                (list($hhIn,$mmIn)=explode(':',$horaIn));
                (list($fechaFn,$horaFn)=explode(' ',$datos[hora_fin]));
                (list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
                (list($hhFn,$mmFn)=explode(':',$horaFn));
                $segundos=(mktime($hhFn,$mmFn+1,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn));
                $Minutos=($segundos/60);
                $_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO_DURACION'][$liquidacionId][$datos['departamento']][$datos['quirofano']][$datos['tipo_equipo']][$datos['equipo_id']]=$Minutos;
            }
        }
        return true;
    }

    function EquiposEnlaProgramacionMoviles($liquidacionId){
        list($dbconn) = GetDBconn();
        $query="(SELECT d.equipo_id,d.descripcion as nom_equipo,d.departamento,dpto.descripcion as departamento,tipo.descripcion as tipo_equipo,b.hora_inicio,b.hora_fin
        FROM cuentas_liquidaciones_qx a,qx_quirofanos_programacion b,qx_equipos_programacion c,qx_equipos_moviles d,departamentos dpto,qx_tipo_equipo_movil tipo
        WHERE a.cuenta_liquidacion_qx_id='".$liquidacionId."' AND a.programacion_id=b.programacion_id AND
        b.qx_quirofano_programacion_id=c.qx_quirofano_programacion_id AND c.equipo_id=d.equipo_id AND d.departamento=dpto.departamento AND
        tipo.tipo_equipo_id=d.tipo_equipo_id)";

        GLOBAL $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while($datos=$result->FetchRow()){
                $_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO'][$liquidacionId][$datos['departamento']][$datos['tipo_equipo']][$datos['equipo_id']]=$datos['nom_equipo'];
                (list($fechaIn,$horaIn)=explode(' ',$datos[hora_inicio]));
                (list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
                (list($hhIn,$mmIn)=explode(':',$horaIn));
                (list($fechaFn,$horaFn)=explode(' ',$datos[hora_fin]));
                (list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
                (list($hhFn,$mmFn)=explode(':',$horaFn));
                $segundos=(mktime($hhFn,$mmFn+1,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn));
                $Minutos=($segundos/60);
                $_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO_DURACION'][$liquidacionId][$datos['departamento']][$datos['tipo_equipo']][$datos['equipo_id']]=$Minutos;
            }
        }
        return true;
    }

    function EliminarEquipoLiquidacionQX(){

        if($_REQUEST['fijo']==1){
            unset($_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO'][$_REQUEST['liquidacionId']][$_REQUEST['dpto']][$_REQUEST['quirofano']][$_REQUEST['tipoEquipo']][$_REQUEST['equipo']]);
            unset($_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO_DURACION'][$_REQUEST['liquidacionId']][$_REQUEST['dpto']][$_REQUEST['quirofano']][$_REQUEST['tipoEquipo']][$_REQUEST['equipo']]);
        }else{
            unset($_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO'][$_REQUEST['liquidacionId']][$_REQUEST['dpto']][$_REQUEST['tipoEquipo']][$_REQUEST['equipo']]);
            unset($_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO_DURACION'][$_REQUEST['liquidacionId']][$_REQUEST['dpto']][$_REQUEST['tipoEquipo']][$_REQUEST['equipo']]);
        }
        $this->FrmLiquidacionEquiposQX($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
    }

    function BuscadorEquipoQX(){
        if($_REQUEST['Volver']){
            $this->FrmLiquidacionEquiposQX($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
            return true;
        }
        $this->Forma_Seleccion_EquiposQX($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['tipoEquipo'],$_REQUEST['Quirofano'],$_REQUEST['Departamento'],$_REQUEST['descripcionEquipo']);
        return true;
    }

/**
* Funcion que consulta en la base de datos los permisos del usuario para trabajar con las bodegas
* @return array
*/
    function TotalDepartamentos(){

        list($dbconn) = GetDBconn();
        $query = "SELECT departamento,descripcion
        FROM departamentos ORDER BY descripcion";
        $result = $dbconn->Execute($query);
        if($result->EOF){
            $this->error = "Error al ejecutar la consulta.<br>";
            $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
            return false;
        }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
            return $vars;
        }
    }

    function BusquedaEquiposQX($tipoEquipo,$Quirofano,$departamento,$descripcionEquipo){
        $this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
        list($dbconn) = GetDBconn();
        if($Quirofano!=-1 && !empty($Quirofano)){
            $cond=" AND a.quirofano='".$Quirofano."'";
        }
        if($departamento!=-1 && !empty($departamento)){
            $cond1=" AND dpto.departamento='".$departamento."'";
        }
        if($descripcionEquipo){
            $cond2=" AND nom_equipo LIKE '%".STRTOUPPER($descripcionEquipo)."%'";
        }
        if($tipoEquipo=='F'){
            $query = "SELECT b.equipo_id,b.descripcion as nom_equipo,a.departamento,dpto.descripcion as nom_departamento,'1' as fijo,a.descripcion as quirofano,tipo.descripcion as tipo_equipo
            FROM qx_quirofanos a,qx_equipos_quirofanos b,departamentos dpto,qx_tipo_equipo_fijo tipo
            WHERE a.quirofano=b.quirofano_id AND b.estado='1' AND dpto.departamento=a.departamento AND tipo.tipo_equipo_fijo_id=b.tipo_equipo_fijo_id $cond $cond1 $cond2
            ";
        }elseif($tipoEquipo=='M'){
            $query = "SELECT a.equipo_id,a.descripcion as nom_equipo,a.departamento,dpto.descripcion as nom_departamento,'0' as fijo,NULL as quirofano,tipo.descripcion as tipo_equipo
            FROM qx_equipos_moviles a,departamentos dpto,qx_tipo_equipo_movil tipo
            WHERE a.estado='1' AND a.departamento=dpto.departamento AND tipo.tipo_equipo_id=a.tipo_equipo_id $cond1 $cond2";
        }else{
            $query = "SELECT b.equipo_id,b.descripcion as nom_equipo,a.departamento,dpto.descripcion as nom_departamento,'1' as fijo,a.descripcion as quirofano,tipo.descripcion as tipo_equipo
            FROM qx_quirofanos a,qx_equipos_quirofanos b,departamentos dpto,qx_tipo_equipo_fijo tipo
            WHERE a.quirofano=b.quirofano_id AND b.estado='1' AND dpto.departamento=a.departamento AND tipo.tipo_equipo_fijo_id=b.tipo_equipo_fijo_id $cond $cond1 $cond2
            UNION
            SELECT a.equipo_id,a.descripcion as nom_equipo,a.departamento,dpto.descripcion as nom_departamento,'0' as fijo,NULL as quirofano,tipo.descripcion as tipo_equipo
            FROM qx_equipos_moviles a,departamentos dpto,qx_tipo_equipo_movil tipo
            WHERE a.estado='1' AND a.departamento=dpto.departamento AND tipo.tipo_equipo_id=a.tipo_equipo_id $cond1 $cond2";
        }
        if(empty($_REQUEST['conteo'])){
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->conteo=$result->RecordCount();
        }else{
            $this->conteo=$_REQUEST['conteo'];
        }
        $query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        return $vars;
    }

    function GuardarSeleccionEquipos(){
        if($_REQUEST['Salir']){
            unset($_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO'][$_REQUEST['liquidacionId']]);
            UNSET($_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO'][$_REQUEST['liquidacionId']]);
            $this->DatosPacientes();
            return true;
        }
        if($_REQUEST['GuardarEquipos']){
            $duracionesFijas=$_REQUEST['duracionFijo'];
            $duracionesMoviles=$_REQUEST['duracionMovil'];
            foreach($_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO'][$_REQUEST['liquidacionId']] as $dpto=>$datos){
                foreach($datos as $quirofano=>$datos1){
                    foreach($datos1 as $tipoEquipo=>$datos2){
                        foreach($datos2 as $equipo=>$nomequipo){
                            if(!is_numeric($duracionesFijas[$equipo])){
                                $this->frmError["MensajeError"]="Digite la duracion de la utilizacion del equipo quirurgico";
                                $this->FrmLiquidacionEquiposQX($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
                                return true;
                            }
                        }
                    }
                }
            }
            foreach($_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO'][$_REQUEST['liquidacionId']] as $dpto=>$datos){
                foreach($datos as $tipoEquipo=>$datos1){
                    foreach($datos1 as $equipo=>$nomequipo){
                        if(!is_numeric($duracionesMoviles[$equipo])){
                            $this->frmError["MensajeError"]="Digite la duracion de la utilizacion del equipo quirurgico";
                            $this->FrmLiquidacionEquiposQX($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
                            return true;
                        }
                    }
                }
            }
        }
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="DELETE FROM cuentas_liquidaciones_qx_equipos_fijos WHERE cuenta_liquidacion_qx_id='".$_REQUEST['liquidacionId']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        foreach($_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO'][$_REQUEST['liquidacionId']] as $dpto=>$datos){
            foreach($datos as $quirofano=>$datos1){
                foreach($datos1 as $tipoEquipo=>$datos2){
                    foreach($datos2 as $equipo=>$nomequipo){
                        $query="INSERT INTO cuentas_liquidaciones_qx_equipos_fijos (cuenta_liquidacion_qx_id,equipo_id,duracion)VALUES('".$_REQUEST['liquidacionId']."','".$equipo."','".$duracionesFijas[$equipo]."')";
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Guardar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                    }
                }
            }
        }
        $query="DELETE FROM cuentas_liquidaciones_qx_equipos_moviles WHERE cuenta_liquidacion_qx_id='".$_REQUEST['liquidacionId']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        foreach($_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO'][$_REQUEST['liquidacionId']] as $dpto=>$datos){
            foreach($datos as $tipoEquipo=>$datos1){
                foreach($datos1 as $equipo=>$nomequipo){
                    $query="INSERT INTO cuentas_liquidaciones_qx_equipos_moviles (cuenta_liquidacion_qx_id,equipo_id,duracion)VALUES('".$_REQUEST['liquidacionId']."','".$equipo."','".$duracionesMoviles[$equipo]."')";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                }
            }
        }
        $this->frmError["MensajeError"]="Datos Guardados Satisfactoriamente";
        $this->FrmLiquidacionEquiposQX($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        $dbconn->CommitTrans();
    return true;
    }

    function LlamaFormaMostrarDatosLiquidacion(){
        $this->FormaMostrarDatosLiquidacion($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso'],$_REQUEST['valoresManual']);
        return true;
    }

    function SeleccionarEquipoProgramacion(){
        if($_REQUEST['fijo']=='F'){
            $_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO'][$_REQUEST['liquidacionId']][$_REQUEST['dpto']][$_REQUEST['quirofano']][$_REQUEST['tipoEquipoVec']][$_REQUEST['equipo']]=$_REQUEST['nom_equipo'];
            $_SESSION['EQUIPOS_LIQUIDACION_QX_FIJO_PROGRAMADO_DURACION'][$_REQUEST['liquidacionId']][$_REQUEST['dpto']][$_REQUEST['quirofano']][$_REQUEST['tipoEquipoVec']][$_REQUEST['equipo']]=0;
        }else{
            $_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO'][$_REQUEST['liquidacionId']][$_REQUEST['dpto']][$_REQUEST['tipoEquipoVec']][$_REQUEST['equipo']]=$_REQUEST['nom_equipo'];
            $_SESSION['EQUIPOS_LIQUIDACION_QX_MOVILES_PROGRAMADO_DURACION'][$_REQUEST['liquidacionId']][$_REQUEST['dpto']][$_REQUEST['tipoEquipoVec']][$_REQUEST['equipo']]=0;
        }
        $this->FrmLiquidacionEquiposQX($_REQUEST['liquidacionId'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;
    }

    function RegistroNotasPaciente($programacion,$tipoidpaciente,$paciente){
        list($dbconn) = GetDBconn();        
        $query ="SELECT *
        FROM hc_notas_operatorias_cirugias a,hc_evoluciones evol,ingresos ing
        WHERE a.programacion_id='".$programacion."' AND
        a.evolucion_id=evol.evolucion_id AND evol.ingreso=ing.ingreso AND ing.tipo_id_paciente='".$tipoidpaciente."' AND ing.paciente_id='".$paciente."'
        ";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if(!$result->EOF){
                return true;
            }
        }
        return false;
    }

    function InsumosPendientesCanasta($programacion){
        list($dbconn) = GetDBconn();
        $query ="SELECT a.codigo_producto,(a.cantidad - coalesce(b.cantidad,0)) as total

        FROM

            (SELECT a.codigo_producto,sum(a.cantidad) as cantidad
            FROM estacion_enfermeria_qx_iym a
            WHERE a.programacion_id='".$programacion."' AND a.estado='0'
            GROUP BY a.codigo_producto) as a
            LEFT JOIN (SELECT b.codigo_producto,sum(b.cantidad) as cantidad
                                FROM estacion_enfermeria_qx_iym_devoluciones b
                                WHERE b.programacion_id='".$programacion."' AND b.estado='0'
                                GROUP BY b.codigo_producto) as b ON (a.codigo_producto=b.codigo_producto)
        WHERE (a.cantidad - coalesce(b.cantidad,0)) > 0";
        
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if(!$result->EOF){
                return true;
            }
        }
        return false;
    }

    /**
* Funcion que cancela una programacion quirurgica
* @return boolean
*/
    function CancelacionProgramacionQX(){
        $programacion=$_REQUEST['programacion'];
        if($_REQUEST['regresar']){
      $this->DatosPacientes();
      return true;
        }
      list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
    $query = "INSERT INTO qx_programaciones_canceladas(programacion_id,observacion,usuario_id,fecha_registro,qx_motivo_cancelacion_programacion_id)
        VALUES('".$programacion."','".$_REQUEST['observacion']."','".UserGetUID()."','".date("Y-m-d H:i:s")."','".$_REQUEST['motivoCancel']."')";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }else{
      $query = "UPDATE qx_programaciones SET estado='0' WHERE programacion_id='".$programacion."'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }
        $dbconn->CommitTrans();
      $mensaje='La Programacion ha sido Cancelada, de click en Aceptar para Regresar al Menu';
        $titulo='LIQUIDACIONES DE CIRUGIA';
        $accion=ModuloGetURL('app','DatosLiquidacionQX','user','LlamaSolicitudIdPaciente');
        $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
        return true;
    }

    function ProcesoCancelarLaProgramacion(){
    $this->DatosCancelacionProgramacion($_REQUEST['programacion']);
        return true;
    }

    /**
* Funcion que retorna un arreglo de los quirofanos con los que cuenta la ips en el departamento en el que esta logueado el usuario
* @return array
*/
    function MotivosCancelacionProgramacion(){
        list($dbconn) = GetDBconn();
        $query = "SELECT qx_motivo_cancelacion_programacion_id,descripcion FROM qx_motivos_cancelacion_programaciones";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
              while(!$result->EOF){
                    $vars[$result->fields[0]]=$result->fields[1];
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }
		
		function LlamaReliquidarCargosIyMCuenta(){
			$mensaje="De click en Aceptar, si desea realizar la reliquidacion de los insumos y medicamentos de la Cirugia";
			$titulo="RELIQUIDACION INSUMOS Y MEDICAMENTOS";			
			$accion=ModuloGetURL('app','DatosLiquidacionQX','user','ReliquidarCargosIyMCuenta',array("NoLiquidacion"=>$_REQUEST['NoLiquidacion'],"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"nombrePaciente"=>$_REQUEST['nombrePaciente'],"cuenta"=>$_REQUEST['cuenta'],"ingreso"=>$_REQUEST['ingreso']));			
			$origen=1;
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton,$origen);
			return true;
		}
		
	function ReliquidarCargosIyMCuenta(){
          
          if($_REQUEST['CancelarProceso']){
			$this->frmCargaInsumosMedicamentosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
			return true;
		}			
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query = "
									SELECT a.departamento, a.cantidad,
													a.consecutivo, b.bodegas_doc_id, 
													b.numeracion, 
													c.codigo_producto, 
													a.fecha_cargo,
													a.cargo as tipo_mov,
													x.precio_venta,
													y.plan_id,a.transaccion
									FROM
													cuentas y,cuentas_detalle as a, cuentas_codigos_agrupamiento as b,
													bodegas_documentos_d as c,inventarios x
									WHERE
												a.sw_liq_manual=0 
												AND a.numerodecuenta='".$_REQUEST['cuenta']."'
												AND a.consecutivo IS NOT NULL
												AND a.codigo_agrupamiento_id=b.codigo_agrupamiento_id
												AND b.bodegas_doc_id=c.bodegas_doc_id
												AND b.numeracion=c.numeracion
												AND a.consecutivo=c.consecutivo
												AND c.codigo_producto=x.codigo_producto
												AND x.empresa_id=a.empresa_id
												AND a.numerodecuenta=y.numerodecuenta													
												AND b.cuenta_liquidacion_qx_id='".$_REQUEST['NoLiquidacion']."'                
							ORDER BY a.fecha_cargo";
							
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
		}else{				
			if($result->RecordCount()>0){
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}	
			}
		}
		$result->Close();		
		IncludeLib("tarifario_cargos");
		$query='';
		for($i=0; $i<sizeof($vars);$i++){
			$Liq=LiquidarIyMQX($_REQUEST['cuenta'],$vars[$i]['codigo_producto'],$vars[$i]['cantidad'],$descuento_manual_empresa=0,$descuento_manual_paciente=0,$aplicar_descuento_empresa=false,$aplicar_descuento_paciente=false,NULL,$vars[$i]['plan_id'],$autorizar=false,$vars[$i]['departamento'],$_SESSION['LIQUIDACION_QX']['Empresa'],$_REQUEST['NoLiquidacion']);
			if($vars[$i]['tipo_mov']=='DIMD'){
				$valor_cargo=($Liq['valor_cargo']*-1);
				$valor_nocubierto=($Liq['valor_nocubierto']*-1);
				$valor_cubierto=($Liq['valor_cubierto']*-1);
			}else{
				$valor_cargo=$Liq['valor_cargo'];
				$valor_nocubierto=$Liq['valor_nocubierto'];
				$valor_cubierto=$Liq['valor_cubierto'];
			}
			$query.="UPDATE cuentas_detalle SET
											precio=".$Liq[precio_plan].",
											valor_cargo='".$valor_cargo."',
											valor_nocubierto='".$valor_nocubierto."',
											valor_cubierto='".$valor_cubierto."',
											valor_descuento_empresa=".$Liq[valor_descuento_empresa].",
											valor_descuento_paciente=".$Liq[valor_descuento_paciente].",
											porcentaje_gravamen=".$Liq[porcentaje_gravamen].",
											sw_cuota_paciente=".$Liq[sw_cuota_paciente].",
											sw_cuota_moderadora=".$Liq[sw_cuota_moderadora].",
                      facturado=".$Liq[facturado]."
							WHERE   numerodecuenta='".$_REQUEST['cuenta']."' and consecutivo=".$vars[$i][consecutivo]." AND 	transaccion='".$vars[$i][transaccion]."';";			
		}
		$result=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error UPDATE cuentas_detalle";
				$this->mensajeDeError = "Error DB :2 " . $dbconn->ErrorMsg()."<br>$sql";
				$dbconn->RollbackTrans();
				return false;
		}		
		$result->Close();			
		$dbconn->CommitTrans();
		$mensaje="Insumos y medicamentos de la Cirugia reliquidados Satisfactoriamente";
		$titulo="RELIQUIDACION INSUMOS Y MEDICAMENTOS";
		$accion=ModuloGetURL('app','DatosLiquidacionQX','user','LlamafrmCargaInsumosMedicamentosCuenta',array("NoLiquidacion"=>$_REQUEST['NoLiquidacion'],"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"nombrePaciente"=>$_REQUEST['nombrePaciente'],"cuenta"=>$_REQUEST['cuenta'],"ingreso"=>$_REQUEST['ingreso']));
		$boton='ACEPTAR';		
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton,$origen);
		return true;	
	}
	
	function LlamafrmCargaInsumosMedicamentosCuenta(){
		$this->frmCargaInsumosMedicamentosCuenta($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
		return true;
	}
  
  function CancelarLiquidacionQX(){
    $this->FrmCancelarLiquidacionQX($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
    return true;    
  }
  
  function MotivosCancelacionLiquidacion(){
    list($dbconn) = GetDBconn();    
    $query = "SELECT motivo_id,descripcion
    FROM cuentas_liquidaciones_qx_motivos_cancelacion
    ";
    $result=$dbconn->Execute($query);
    if($result->RecordCount()>0){
      while(!$result->EOF){
        $vars[]=$result->GetRowAssoc($toUpper=false);
        $result->MoveNext();
      } 
    }
    return $vars;  
  }

  function InsertarCancelacionLiquidacionQX(){
    
    
    if($_REQUEST['Guardar']){
      if($_REQUEST['motivoCancel']==-1){
        $this->frmError["MensajeError"]="Debe Seleccionar el Motivo de la Cancelacion";
        $this->FrmCancelarLiquidacionQX($_REQUEST['NoLiquidacion'],$_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
        return true;   
      }
      list($dbconn) = GetDBconn();
      $dbconn->BeginTrans();    
      $query = "INSERT INTO cuentas_liquidaciones_qx_canceladas(cuenta_liquidacion_qx_id,motivo_id,observaciones,usuario_id,fecha_registro)
      VALUES('".$_REQUEST['NoLiquidacion']."','".$_REQUEST['motivoCancel']."','".$_REQUEST['observacion']."','".UserGetUID()."','".date("Y-m-d H:i:s")."')";
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
          $this->error = "Error UPDATE cuentas_liquidaciones_qx_canceladas";
          $this->mensajeDeError = "Error DB :2 " . $dbconn->ErrorMsg()."<br>$sql";
          $dbconn->RollbackTrans();
          return false;
      }else{        
        $query = "UPDATE cuentas_liquidaciones_qx 
                  SET estado='3'
                  WHERE cuenta_liquidacion_qx_id='".$_REQUEST['NoLiquidacion']."'";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error UPDATE cuentas_liquidaciones_qx_canceladas";
            $this->mensajeDeError = "Error DB :2 " . $dbconn->ErrorMsg()."<br>$sql";
            $dbconn->RollbackTrans();
            return false;
        }        
        $dbconn->CommitTrans(); 
      }
      $this->frmError["MensajeError"]="Liquidacion Cancelada Correctamente";
    }  
    $this->DatosPacientes();
    return true;   
  }
  
  function EliminarGasQuirurgico(){ 
    
    for($i=$_REQUEST['contador'];$i<sizeof($_SESSION['Liquidacion_QX']['GASES']);$i++){
      $_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGas']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['TipoGas'];           
      $_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGasDes']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['TipoGasDes'];           
      $_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGas']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['MetodoGas'];           
      $_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGasDes']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['MetodoGasDes'];            
      $_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGas']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['FrecuenciaGas'];           
      $_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGasDes']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['FrecuenciaGasDes'];           
      $_SESSION['Liquidacion_QX']['GASES'][$i]['MinutosGas']=$_SESSION['Liquidacion_QX']['GASES'][$i+1]['MinutosGas'];
    }   
    unset($_SESSION['Liquidacion_QX']['GASES'][$i-1]);    
    $this->DatosRequeridosLiquidacion($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombrePaciente'],$_REQUEST['cuenta'],$_REQUEST['ingreso']);
    return true;
  }
  
  function BuscarGasesAnestesicosRegistrados($NoLiquidacion){
    list($dbconn) = GetDBconn();
      /*$query="SELECT a.suministro_gas_id,b.codigo_producto,a.tipo_gas_id,b.descripcion as nom_tipo_gas_id,
      a.tipo_suministro_id,c.descripcion as nom_tipo_suministro_id,
      d.frecuencia_id,d.unidad,d.factor_conversion,a.tiempo_suministro
      FROM cuentas_liquidaciones_qx_gases_anestesicos a
      JOIN  tipos_gases b ON(a.tipo_gas_id=b.tipo_gas_id)
      JOIN tipos_metodos_suministro_gases c ON(a.tipo_suministro_id=c.tipo_suministro_id)
      JOIN tipos_frecuencia_gases d ON(a.tipo_suministro_id=d.tipo_suministro_id AND a.frecuencia_id=d.frecuencia_id)      
      WHERE a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' AND a.transaccion_cuenta IS NULL";*/
	  
	  $query="SELECT 	a.suministro_gas_id,
						b.codigo_producto,
						a.tipo_gas_id,
						b.descripcion as nom_tipo_gas_id,
						a.tipo_suministro_id,
						c.descripcion as nom_tipo_suministro_id,
						d.frecuencia_id,
						d.unidad,
						d.factor_conversion,
						a.tiempo_suministro,
						h.fecha_vencimiento,
						h.lote
				FROM 	cuentas_liquidaciones_qx_gases_anestesicos a
						JOIN tipos_gases b ON(a.tipo_gas_id=b.tipo_gas_id)
						JOIN tipos_metodos_suministro_gases c ON(a.tipo_suministro_id=c.tipo_suministro_id)
						JOIN tipos_frecuencia_gases d ON(a.tipo_suministro_id=d.tipo_suministro_id AND a.frecuencia_id=d.frecuencia_id),      
						cuentas_liquidaciones_qx e,
						estacion_enfermeria_qx_departamentos f,
						existencias_bodegas g,
						existencias_bodegas_lote_fv h
				WHERE 	a.cuenta_liquidacion_qx_id='".$NoLiquidacion."' 
				AND 	a.transaccion_cuenta IS NULL
				AND		a.cuenta_liquidacion_qx_id = e.cuenta_liquidacion_qx_id
				AND		e.departamento = f.departamento
				AND		f.empresa_id = g.empresa_id
				AND		f.centro_utilidad = g.centro_utilidad
				AND		f.bodega = g.bodega
				AND		b.codigo_producto = g.codigo_producto
				AND		g.empresa_id = h.empresa_id
				AND		g.centro_utilidad = h.centro_utilidad
				AND		g.bodega = h.bodega
				AND		g.codigo_producto = h.codigo_producto
				AND		h.existencia_actual > 0
				AND		h.estado = '1'";
	  
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }else{
        while(!$result->EOF){
          $vars2[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
      return $vars2;   
  }      


  /*SELECT b.tipo_id_paciente,b.paciente_id
    FROM cuentas a,ingresos b
    WHERE (a.estado='1' OR a.estado='2') AND a.plan_id='56' AND a.ingreso=b.ingreso AND b.estado='1'*/

}//FIN CLASE USER

