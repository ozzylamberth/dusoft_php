<?php

/**
 * $Id: app_EE_ControlesPacientes_user.php,v 1.5 2006/10/12 20:43:51 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_ControlesPacientes_user extends classModulo
{

     /**
     * Valida si el usuario esta logueado en La Estacion de Enfermeria y si tiene permiso
     * Para este componente ('01'= Admision - Asignacion Cama)
     *
     * @return boolean
     * @access private
     */
     function GetUserPermisos($componente=null)
     {
          $estacion_id = $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()];
          
          if($componente)
          {
               if(!empty($_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO'][UserGetUID()][$estacion_id]['COMPONENTES'][$componente]))
               {
                    return true;
               }
               else
               {
                    return null;
               }
          }
     
          if(!empty($_SESSION['EE_PanelEnfermeria']['ESTACIONES_USUARIO'][UserGetUID()][$estacion_id]))
          {
               return true;
          }
          else
          {
               return null;
          }
     }

     /**
     * Retorna los datos de la estacion de enfermeria actual.
     *
     * @return array
     * @access private
     */
     function GetdatosEstacion()
     {
          $estacion_id = $_SESSION['EE_PanelEnfermeria']['ESTACION_SELECCIONADA'][UserGetUID()];
          return $_SESSION['EE_PanelEnfermeria']['DATOS_ESTACION'][$estacion_id];
     }
     
     
     /*
     *	GetSignosVitalesSitios
     *	@Author Tizziano Perea
     *	@access Public
     */
     function GetSignosVitalesSitios($sitio='')
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $sitios=array();

          if(empty($sitio))
          {
               $query="SELECT *
                       FROM hc_signos_vitales_sitios
                       ORDER BY indice_orden";

          }else{
               $query="SELECT *
                       FROM hc_signos_vitales_sitios
                       WHERE sitio_id='$sitio'
                       ORDER BY sitio_id";
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado) {
               $this->error = "Error al consultar la tabla \"hc_signos_vitales_sitios\"<br>";
               $this->mensajeDeError = $query;
               return false;
          }
          if(empty($sitio))
          {
               while ($data = $resultado->FetchRow()){
                    $sitios[]=$data;
               }
          }
          else
          {
               $data = $resultado->FetchRow();
               $sitios[]=$data;
          }
          return $sitios;
     }
  
     
     /**
     *		Trae la fecha de nacimiento del paciente.
     *
     *		@Author Jairo Duvan Diaz
     *		@access Public
     *		@return bool
     *
     */
     function GetFechaNacPaciente($ingreso)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT fecha_nacimiento
                    FROM ingresos a,pacientes b
                    WHERE a.ingreso='$ingreso'
                    AND b.paciente_id=a.paciente_id
                    AND b.tipo_id_paciente=a.tipo_id_paciente
                    AND a.estado='1'";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          if($result->EOF)
          {  return "nada";  }
          if(!$result->EOF)
               $fech=$result->fields[0];
               $result->Close();
          return $fech;
     }
     
     /**
     *		InsertarSignosVitales
     *
     *		Inserta los signos vitales de cada paciente de la estacion
     *
     *		@Author Rosa Maria Angel.
     *		@access Public
     *		@return bool
     */
     function InsertarSignosVitales()
     {
	     list($dbconn) = GetDBconn();
          $fechaHora = $_REQUEST['selectHora'].":".$_REQUEST['selectMinutos'];
          $fc=$_REQUEST['fc'];
          $fr=$_REQUEST['fr'];
          $pvc=$_REQUEST['pvc'];
          $taa=$_REQUEST['taa'];
          $tab=$_REQUEST['tab'];
          $sistole= $tab * 2;
          $diastole=$taa;
          $media= (($sistole + $diastole)/3);
          $sw_invasiva=$_REQUEST['sw_invasiva'];
          $sitio=$_REQUEST['sitio'];
          $tpiel=$_REQUEST['tpiel'];
          $servo=$_REQUEST['servo'];
          $observacion=$_REQUEST['observacion'];
          $manual=$_REQUEST['manual'];
          $eva=$_REQUEST['eva'];
          $pic=$_REQUEST['pic'];
          $peso=$_REQUEST['peso'];
          $ingreso=$_REQUEST['ingreso'];
          $sato=$_REQUEST['sato'];
          $referer_name = $_REQUEST['referer_name'];
          $referer_parameters = $_REQUEST['referer_parameters'];
          
          //valido que por lo menos digitó un dato
          if(empty($fc) && empty($sato) && empty($pvc) && empty($fr) && empty($eva) && empty($taa) && empty($tab) && ($sitio==-1) && empty($tpiel) && empty($servo) && empty($manual) && empty($pic) && empty($peso))
          {
               $this->frmError["MensajeError"] = "DEBE INGRESAR AL MENOS UN DATO";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);
               return true;
          }

          //valido que por lo menos digitó un dato
          if($taa)
          {
		     if(!$tab)
               {
		          $this->frmError["MensajeError"] = "TENSION BAJA &nbsp;SE DEBE LLENAR LAS CASILLAS EN AMBAS PARTES";
	               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);                    
                    return true;
     		}
               if($sitio==-1)
               {
                    $this->frmError["MensajeError"] = "SELECCIONE EL SITIO DE LA TOMA DE LA T.A";
                    $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);
                    return true;
               }
          }

          //valido que por lo menos digitó un dato
          if($tab)
          {
		     if(!$taa)
               {
		          $this->frmError["MensajeError"] = "TENSION ALTA &nbsp;SE DEBE LLENAR LAS CASILLAS EN AMBAS PARTES";
               	$this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);                    
                    return true;
     		}
               if($sitio==-1)
               {
                    $this->frmError["MensajeError"] = "SELECCIONE EL STIO DE LA TOMA DE LA T.A";
	               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);                    
                    return true;
               }
          }

          if(empty ($taa) AND empty ($tab) AND $sitio != -1)
	     {
               $this->frmError["MensajeError"] = "SELECCIONE EL SITIO DE LA TOMA DE T.A.";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);
               return true;
     	}

          $restriccion = $tab - $taa;
          if($restriccion > 0)
          {
               $this->frmError["MensajeError"] = "LA T.A. SISTOLICA DEBE SER MAYOR A LA T.A. DIASTOLICA";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
               return true;
          }
     
          if($tpiel > 43)
          {
               $this->frmError["MensajeError"]="LA TEMPERATURA EXCEDE EL VALOR DEL RANGO NORMAL.";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
               return true;
          }
     
          //luego valido que no existan registros a esa hora
          $query = "SELECT fecha
                    FROM hc_signos_vitales
                    WHERE ingreso=$ingreso AND
                          fecha = '$fechaHora'
                    ORDER BY fecha DESC";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla \"hc_signos_vitales\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if(!$result->EOF)
               {
                    $this->frmError["MensajeError"] = "EN LA FECHA-HORA $fechaHora YA EXISTEN REGISTROS, ESPECIFIQUE UNA HORA DIFERENTE";
	               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);                    
                    return true;
               }
          }

          if (empty($fc)) $fc=0;
          if (empty($fr)) $fr=0;
          if (empty($pvc)) $pvc=0;
          if (empty($taa)) $taa=0;
          if (empty($tab)) $tab=0;
          if (empty($media)) $media=0;
          if (empty($tpiel)) $tpiel=0;
          if (empty($servo)) $servo=0;
          if (empty($manual)) $manual=0;
          if (empty($eva)) $eva=0;
          if (empty($sato)) $sato=0;
          if (empty($pic)) $pic=0;
          if (empty($peso)) $peso=0;
          if ($sitio==-1) $sitio = "NULL"; else $sitio = "'$sitio'";

          if(empty($observacion)){$observacion= '';}

          if (is_numeric($fc) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO FRECUENCIA CARDIACA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
               return true;
          }
          $fc = floor ($fc);

          if (is_numeric($fr) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO FRECUENCIA CARDIACA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
               return true;
          }
          $fr = floor ($fr);

          if (is_numeric($pvc) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO PVC NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
               return true;
          }
          $pvc = floor ($pvc);

          if (is_numeric($pic) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO PIC NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
               return true;
          }

          if (is_numeric($taa) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO TENSION ARTERIAL ALTA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
               return true;
          }
          $taa = floor ($taa);

          if (is_numeric($tab) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO TENSION ARTERIAL BAJA NO ACEPTA VALORES DIFERENTES A UN DATO NUMERICO";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
               return true;
          }
          $tab = floor ($tab);

          if (is_numeric($peso) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO PESO NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
               return true;
          }

          if (is_numeric($tpiel) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
	          return true;
          }

          if (is_numeric($manual) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA MANUAL NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
               return true;
          }

          if (is_numeric($servo) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO TEMPERATURA DE INCUBADORA NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
               return true;
          }

          if (is_numeric($sato) == false)
          {
               $this->frmError["MensajeError"] = "EL CAMPO SAT 0<sub>2</sub> NO ACEPTA VALORES DIFERENTES A UN DATO DECIMAL";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
               return true;
          }

          if ($sato > '100')
          {
               $this->frmError["MensajeError"] = "EL CAMPO SATO DEBE SER MENOR O IGUAL AL 100%";
               $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);               
	          return true;
          }
          $media = floor ($media);

          $query="INSERT INTO hc_signos_vitales ( sitio_id,
                                                  fecha,
                                                  fc,
                                                  pvc,
                                                  ta_alta,
                                                  ta_baja,
                                                  media,
                                                  temp_piel,
                                                  servo,
                                                  manual,
                                                  presion_intracraneana,
                                                  ingreso,
                                                  usuario_id,peso,observacion,fecha_registro,sato2,evaluacion_dolor,fr)
                                        VALUES ($sitio,
                                                '$fechaHora',
                                                $fc,
                                                $pvc,
                                                $taa,
                                                $tab,
                                                $media,
                                                $tpiel,
                                                $servo,
                                                $manual,
                                                $pic,
                                                $ingreso,
                                                ".UserGetUID().",
                                                $peso,'$observacion',now(),$sato,$eva,$fr)";
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }

          if($referer_name && $referer_parameters){//viene de liquidos
               $this->$referer_name($referer_parameters[datosPaciente],$referer_parameters[datos_estacion]);
               return true;
          }else
          {
	          $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);
          }
          return true;
     }

     
     /**
     *	GetAlarmaRangoControl
     *	Verifica si el valor del control se encuentra dentrol del rango para ese control
     *
     *	@Author Tizziano Perea
     *	@access Public
     *	@return bool - string
     *	@param integer => control_id
     *	@param character => sexo del paciente
     *	@param integer => edad del paciente
     *	@param integer => valor a verificar
     */
     function GetAlarmaRangoControl($control,$sexo,$edad,$temp)
     {
          $query = "SELECT *
                    FROM hc_rangos_controles
                    WHERE control_id = $control AND
                         sexo = '".$sexo."' AND
                         ($edad BETWEEN edad_min AND edad_max AND
                         $temp BETWEEN rango_min AND rango_max)";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener la fecha de nacimiento del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if(($result->EOF)){
                    return "Alarma";
               }
               else{
                    return "Normal";
               }
          }
          return true;
     }//GetAlarmaRangoControl
     
     
     /**
     *		GetDatosUsuarioSistema
     *		Obtiene el nombre de usuario del sistema
     *
     *		@Author Rosa Maria Angel
     *		@access Public
     *		@return bool
     *		@param integer => usuario_id
     */
     function GetDatosUsuarioSistema($usuario)
     {
          $query = "SELECT usuario,nombre
                    FROM system_usuarios
                    WHERE usuario_id = $usuario";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener los datos del usuario.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    return "ShowMensaje";
               }
               else
               {
                    while ($data = $result->FetchRow()){
                         $DatosUser[] = $data;
                    }
                    return $DatosUser;
               }
          }
     }/// GetDatosUsuarioSistema
     
     
     /*
     * funcion de borrado de los signos vitales
     */
     function BorradoSignosVitales()
     {
          $fechaHora = $_REQUEST['fecha'];
          $ingreso=$_REQUEST['ingreso'];
          $datos_e=$_REQUEST['datosPaciente'];
          $cantidad=$_REQUEST['contador'];
     
          list($dbconn) = GetDBconn();
          //luego valido que no existan registros a esa hora
          $query = "DELETE
                    FROM hc_signos_vitales
                    WHERE ingreso=".$datos_e[ingreso]." AND
                    fecha = '$fechaHora'";
                    $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla \"hc_signos_vitales\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          $referer_name = $_REQUEST['referer_name'];
          $referer_parameters = $_REQUEST['referer_parameters'];
          //$this->FrmSignosVitales($_REQUEST['datos_estacion'],$_REQUEST['datosPaciente'],$cantidad,$referer_name,$referer_parameters);
          $this->CallFrmsControlesPacientes($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);          
          return true;
     }

     /**
     *	GetSignosVitales
     *
     *	Obtiene los signos vitales de un ingreso X
     *	@Author Tizziano Perea
     *	@return bool - array
     *	@access Public
     */
     function GetSignosVitales ($ingreso)
     {
          $query="SELECT A.*, B.*
                  FROM (SELECT a.*
                         FROM	hc_signos_vitales a
                         WHERE a.ingreso=".$ingreso."
                         )AS A
	                    LEFT JOIN
                         (SELECT b.*
                         FROM	hc_signos_vitales_sitios B
                         )AS B
                         ON A.sitio_id = B.sitio_id
     	          ORDER BY A.fecha DESC LIMIT 10 OFFSET 0";

          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Atención";
               $this->mensajeDeError = "Error al consultar la tabla \"hc_signos_vitales\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          if($resultado->EOF){
               return "ShowMensaje";
          }
          else
          {
               while($data = $resultado->FetchRow()){
                    $vectorSignos[] = $data;
               }
               return $vectorSignos;
          }
     }//GetSignosVitales
    
	
     //	CONTROLES NEUROLOGICOS
     
     /**
     *	GetTallasPupilas
     *	Obtiene las diferentes tipos de talla de pupilas
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     */
     function GetTallasPupilas($datosPaciente,$datos_estacion,$control)
     {
          $query = "SELECT talla_pupila_id,descripcion
                    FROM hc_tipos_talla_pupila";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar consultar las tallas de pupilas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    $this->frmError["MensajeError"] = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_talla_pupila'";
                    $this->CallFrmsControlesPacientes($datosPaciente,$datos_estacion,$control);               
		          return true;
               }
               else
               {
                    while ($data = $result->FetchRow()){
                         $Tallas[] = $data;
                    }
               }
               return $Tallas;
          }
     }//GetTallasPupilas
 
     
     /**
     *	GetReaccionPupilas
     *	Obtiene los tipos de reaccion de pupila
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool
     *	@param array,
     *	@param array
     */
     function GetReaccionPupilas($datosPaciente,$datos_estacion,$control)
     {
          $query = "SELECT reaccion_pupila_id, descripcion
                    FROM hc_tipos_reaccion_pupila";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar consultar las tallas de pupilas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    $this->frmError["MensajeError"] = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_reaccion_pupila'";
                    $this->CallFrmsControlesPacientes($datosPaciente,$datos_estacion,$control);               
		          return true;
               }
               else
               {
                    while ($data = $result->FetchRow()){
                         $Reaccion[] = $data;
                    }
               }
               return $Reaccion;
          }
     }//GetReaccionPupilas


     /**
     *	GetNivelesConciencia
     *	Obtiene los tipos de niveles de consciencia
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool-array-string
     *	@param array,
     */
     function GetNivelesConciencia($datosPaciente,$datos_estacion,$control)
     {
          $query = "SELECT nivel_consciencia_id, descripcion
                    FROM hc_tipos_nivel_consciencia";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla 'hc_tipos_nivel_consciencia'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    $this->frmError["MensajeError"] = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_nivel_conciencia'";
                    $this->CallFrmsControlesPacientes($datosPaciente,$datos_estacion,$control);               
		          return true;
               }
               else
               {
                    while ($data = $result->FetchRow()){
                         $Nivel_Conciencia[] = $data;
                    }
               }
               return $Nivel_Conciencia;
          }
     }//GetNIvelesConciencia


     /**
     *	GetTiposFuerza
     *	Obtiene los tipos de fuerza
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool
     *	@param array,
     *	@param array
     */
     function GetTiposFuerza($datosPaciente,$datos_estacion,$control)
     {
          $query = "SELECT fuerza_id, descripcion
                    FROM hc_tipos_fuerza";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla 'hc_tipos_fuerza'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    $this->frmError["MensajeError"] = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_fuerza'";
                    $this->CallFrmsControlesPacientes($datosPaciente,$datos_estacion,$control);               
		          return true;
               }
               else
               {
                    while ($data = $result->FetchRow()){
                         $TiposFuerza[] = $data;
                    }
               }
               return $TiposFuerza;
          }
     }//fin TiposFuerza


     /**
     *	GetTipoAperturaOcular
     *	Obtiene los tipos de apertura ocular
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool-array-string
     *	@param array,
     */
     function GetTipoAperturaOcular($datosPaciente,$datos_estacion,$control)
     {
          $query = "SELECT apertura_ocular_id, descripcion
                    FROM hc_tipos_apertura_ocular
                    ORDER BY apertura_ocular_id ASC";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla 'hc_tipos_apertura_ocular'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    $this->frmError["MensajeError"] = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_apertura_ocular'";
                    $this->CallFrmsControlesPacientes($datosPaciente,$datos_estacion,$control);               
                    return true;
               }
               else
               {
                    while ($data = $result->FetchRow()){
                         $TipoAperturaOcular[] = $data;
                    }
               }
               return $TipoAperturaOcular;
          }
     }//fin GetTipoAperturaOcular


     /**
     *	GetRespuestaVerbal
     *	Obtiene los direfentes tipos de respuesta verbal
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool-array-string
     *	@param array,
     */
     function GetRespuestaVerbal($datosPaciente,$datos_estacion,$control)
     {
          $FechaInicio = $this->GetFechaNacPaciente($datosPaciente[ingreso]);
          $FechaFin = date("Y-m-d");
          $edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
          if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_lactante'))
          {
               $query = "SELECT respuesta_verbal_id, descripcion_lactante
                              FROM hc_tipos_respuesta_verbal
                              ORDER BY respuesta_verbal_id ASC";
          }
          else
          {
               $query = "SELECT respuesta_verbal_id, descripcion
                              FROM hc_tipos_respuesta_verbal
                              ORDER BY respuesta_verbal_id ASC";
          }
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla 'hc_tipos_respuesta_verbal'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    $this->frmError["MensajeError"] = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_respuesta_verbal'";
                    $this->CallFrmsControlesPacientes($datosPaciente,$datos_estacion,$control);               
		          return true;
               }
               else
               {
                    while ($data = $result->FetchRow())
                    {
                         $RespuestaVerbal[] = $data;
                    }
               }
               return $RespuestaVerbal;
          }
     }//fin GetRespuestaVerbal


     /**
     *	GetRespuestaMotora
     *	Selecciona los tipos de respuesta motora
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool-array-string
     *	@param array,
     */
     function GetRespuestaMotora($datosPaciente,$datos_estacion,$control)
     {
          $FechaInicio = $this->GetFechaNacPaciente($datosPaciente[ingreso]);
          $FechaFin = date("Y-m-d");
          $edad_paciente = CalcularEdad($FechaInicio,$FechaFin);
          if ($edad_paciente[anos] < ModuloGetVar('','','max_edad_lactante'))
          {
               $query = "SELECT respuesta_motora_id, descripcion_lactante
                              FROM hc_tipos_respuesta_motora
                              ORDER BY respuesta_motora_id ASC";
          }
          else
          {
               $query = "SELECT respuesta_motora_id, descripcion
                              FROM hc_tipos_respuesta_motora
                              ORDER BY respuesta_motora_id ASC";
          }
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla 'hc_tipos_respuesta_motora'.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    $this->frmError["MensajeError"] = "NO SE ENCONTRARON REGISTROS EN LA TABLA 'hc_tipos_respuesta_motora'";
                    $this->CallFrmsControlesPacientes($datosPaciente,$datos_estacion,$control);               
		          return true;
               }
               else
               {
                    while ($data = $result->FetchRow())
                    {
                         $RespuestaMotora[] = $data;
                    }
               }
               return $RespuestaMotora;
          }
     }//fin GetRespuestaMotora
     
     /**
     * Esta función inserta los datos del submodulo.
     *
     * @access private
     * @return boolean Informa si lo logro o no.
     */
	function Insertar_ControlesNeurologicos()
	{
		$Tiempo = $_REQUEST['selectHora'].":".$_REQUEST['selectMinutos'];
		$TallaPupilaI = $_REQUEST['pupilaI'];
		$TallaPupilaD = $_REQUEST['pupilaD'];
		$ReaccionPupilaI = $_REQUEST['reaccionI'];
		$ReaccionPupilaD = $_REQUEST['reaccionD'];
		$Niveles_Conciencia = $_REQUEST['orientado'];
		$Brazo_izq = $_REQUEST['braizq'];
		$Brazo_der = $_REQUEST['brader'];
		$Pierna_izq = $_REQUEST['pierizq'];
		$Pierna_der = $_REQUEST['pierder'];
		$Apertura_Ocular = $_REQUEST['ao'];
		$Respuesta_Verbal = $_REQUEST['rv'];
		$Respuesta_Motora = $_REQUEST['rm'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $control = $_REQUEST['control'];

		if (empty($TallaPupilaI) && empty($TallaPupilaD) && empty($Niveles_Conciencia) && empty($Apertura_Ocular) && empty($Respuesta_Verbal) && empty($Respuesta_Motora))
		{
			$this->frmError["MensajeError"] = "DEBE INGRESAR AL MENOS UN DATO";
			$this->FrmControlesNeurologicos($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);
			return true;
		}

		//luego valido que no existan registros a esa hora
		list($dbconn) = GetDBconn();
          $dbconn->BeginTrans();
         
		$query = "SELECT fecha
				  FROM hc_controles_neurologia
				  WHERE ingreso=".$datosPaciente['ingreso']." AND
				  fecha = '$Tiempo'
				  ORDER BY fecha DESC;";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla \"hc_controles_neurologia\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				$this->frmError["MensajeError"] = "YA SE REGISTRARON CONTROLES NEUROLOGICOS EN ESTA HORA ($Tiempo)";
				$this->FrmControlesNeurologicos($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);
				return true;
			}
		}

		if (empty($TallaPupilaI)) $TallaPupilaI='NULL';
		if (empty($TallaPupilaD)) $TallaPupilaD='NULL';
		if (empty($Niveles_Conciencia)) $Niveles_Conciencia='NULL';
		if (empty($Apertura_Ocular)) $Apertura_Ocular='NULL';
		if (empty($Respuesta_Verbal)) $Respuesta_Verbal='NULL';
		if (empty($Respuesta_Motora)) $Respuesta_Motora='NULL';

		if ($Apertura_Ocular == 'NULL' && $Respuesta_Verbal != 'NULL' && $Respuesta_Motora != 'NULL')
		{
			$this->frmError["MensajeError"] = "DEBE COMPLETAR DEBIDAMENTE LA ESCALA DE GLASGOW";
			$this->FrmControlesNeurologicos($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);
			return true;
		}

		if ($Apertura_Ocular != 'NULL' && $Respuesta_Verbal == 'NULL' && $Respuesta_Motora != 'NULL')
		{
			$this->frmError["MensajeError"] = "DEBE COMPLETAR DEBIDAMENTE LA ESCALA DE GLASGOW";
			$this->FrmControlesNeurologicos($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);
			return true;
		}

		if ($Apertura_Ocular != 'NULL' && $Respuesta_Verbal != 'NULL' && $Respuesta_Motora == 'NULL')
		{
			$this->frmError["MensajeError"] = "DEBE COMPLETAR DEBIDAMENTE LA ESCALA DE GLASGOW";
			$this->FrmControlesNeurologicos($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);
			return true;
		}

          $query="INSERT INTO hc_controles_neurologia (fecha,
                                                       pupila_talla_d,
                                                       pupila_talla_i,
                                                       pupila_reaccion_d,
                                                       pupila_reaccion_i,
                                                       tipo_nivel_consciencia_id,
                                                       fuerza_brazo_d,
                                                       fuerza_brazo_i,
                                                       fuerza_pierna_d,
                                                       fuerza_pierna_i,
                                                       tipo_apertura_ocular_id,
                                                       tipo_respuesta_verbal_id,
                                                       tipo_respuesta_motora_id,
                                                       usuario_id,
                                                       ingreso,
                                                       fecha_registro)
                                              VALUES ('$Tiempo',
                                                       $TallaPupilaD,
                                                       $TallaPupilaI,
                                                       '$ReaccionPupilaD',
                                                       '$ReaccionPupilaI',
                                                       $Niveles_Conciencia,
                                                       '$Brazo_der',
                                                       '$Brazo_izq',
                                                       '$Pierna_der',
                                                       '$Pierna_izq',
                                                       $Apertura_Ocular,
                                                       $Respuesta_Verbal,
                                                       $Respuesta_Motora,
                                                       ".UserGetUID().",
                                                       ".$datosPaciente['ingreso'].",
                                                       now());";
          $resultado = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
		}
          
          $queryA = "UPDATE hc_agenda_controles
                    SET estado='1'
                    WHERE ingreso = ".$datosPaciente['ingreso']." AND
                          estacion_id = '".$datos_estacion['estacion_id']."' AND
                          control_id = '10' AND
                    fecha = '".$_REQUEST['turno_hora']."'";
          $resultado = $dbconn->Execute($queryA);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar ingresar el signo vital del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
		}
          
		$dbconn->CommitTrans();
          $this->frmError["MensajeError"] = "LOS DATOS FUERON INSERTADOS SATISFACTORIAMENTE";
          $this->FrmControlesNeurologicos($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control']);
		return true;
	}

	/**
	*	Listar_ControlesNeurologicos
	*	@Author Tizziano Perea O.
	*	@access Public
	*	@return bool-array-string
	*	@param array
	*/
	function Listar_ControlesNeurologicos($ingreso)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.*, B.descripcion
				 FROM hc_controles_neurologia
				 AS A left join hc_tipos_nivel_consciencia AS B
				 on (B.nivel_consciencia_id=A.tipo_nivel_consciencia_id)
				 WHERE ingreso='".$ingreso."'
				 ORDER BY fecha_registro
				 DESC ";

		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$VectorControl[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		return $VectorControl;
	}
     
	/*	Borrar_ControlNeuro
	*	Borra los registros de la tabla de Control Neurologico
	*	@Author Tizziano Perea O.
	*	@access Public
	*	@return bool-array-string
	*	@param array
	*/

	function Borrar_ControlNeuro()
	{
		list($dbconn) = GetDBconn();
		$sql="DELETE FROM  hc_controles_neurologia
			  WHERE ingreso=".$_REQUEST['datosPaciente']['ingreso']."
			  AND fecha_registro='".$_REQUEST['fechar']."';";

		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->frmError["MensajeError"] = "NO SE PUDO BORRAR EL REGISTRO";
	          $this->ShowControl_Neurologico($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control'],$_REQUEST['href_action_hora'],$_REQUEST['href_action_control']);
			return false;
		}
		$this->frmError["MensajeError"] = "REGISTRO BORRADO SATISFACTORIAMENTE";
          $this->ShowControl_Neurologico($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control'],$_REQUEST['href_action_hora'],$_REQUEST['href_action_control']);
		return true;
	}
     
     //	CONTROLES PROGRAMADOS
	/*
     *	GetControles
     *	Busqueda de Controles de Pacientes
     *	@access Public
     */
     function GetControles($ingreso,$control_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();

          $controles=array();
          $query="SELECT cp.*, upper(tc.descripcion) as descripcion
                    FROM  hc_controles_paciente cp,
                         hc_tipos_controles_paciente tc
                    WHERE cp.ingreso=".$ingreso." AND
                         cp.control_id = tc.control_id;";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado) {
               $this->error = "Error al buscar los controles del paciente en \"hc_controles_paciente\"<br>";
               $this->mensajeDeError = $query;
               return false;
          }
          while ($data = $resultado->FetchRow()) {
               $controles[]=$data;
          }
	     return $controles;
     }

	/**
     *	VerificaPosicionesPaciente
     */
     function VerificaPosicionesPaciente($evolucion)
     {
          $query="SELECT * FROM hc_posicion_paciente WHERE evolucion_id=".$evolucion;
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          list($dbconn) = GetDBconn();
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar las posiciones del paciente en \"hc_posicion_paciente\" con evolucion_id=".$evolucion;
               $this->mensajeDeError = $query;
               return false;
          }
          $data=$resultado->FetchRow();
          $resultado->Close();
          return $data;
     }//VerificaPosicionesPaciente

     /*
     *		GetControlPosicion
     *
     *		@Author Arley Velásquez
     *		@access Public
     */
     function GetControlPosicion($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $posicion=array();
                    $query = "SELECT * FROM hc_tipos_posicion_paciente WHERE posicion_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if (!$resultado) {
                         $this->error = "Error, no se encuentra el registro en \"hc_tipos_posicion_paciente\" con la posicion \"$posicion_id\"";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchRow()) {
                         $posicion[]=$data;
                    }
                    return $posicion;
               break;
               case 1:
                    $query = "SELECT * FROM hc_tipos_posicion_paciente";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error, la tabla hc_tipos_posicion_paciente no contiene registros";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchNextObject($toUpper=false))
                    {
                         if ($data->posicion_id==$posicion_id)
                              $option.="<option value='".$data->posicion_id."' selected>".$data->descripcion."</option>\n";
                         else
                              $option.="<option value='".$data->posicion_id."'>".$data->descripcion."</option>\n";
                    }
                    return $option;
               break;
          }
     }

     /*
     *	GetControlOxiMetodo
     */
     function GetControlOxiMetodo($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $metodo=array();
                    $query = "SELECT * FROM hc_tipos_metodos_oxigenoterapia WHERE metodo_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if (!$resultado) {
                         $this->error = "Error, no se encuentra el registro en \"hc_tipos_metodos_oxigenoterapia\" con el metodo_id \"$posicion_id\"";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchRow()) {
                         $metodo[]=$data;
                    }
                    return $metodo;
               break;
               case 1:
                    $query = "SELECT * FROM hc_tipos_metodos_oxigenoterapia";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error, la tabla hc_tipos_metodos_oxigenoterapia no contiene registros";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchNextObject($toUpper=false))
                    {
                         if ($data->metodo_id==$posicion_id)
                              $option.="<option value='".$data->metodo_id."' selected>".$data->descripcion."</option>\n";
                         else
                              $option.="<option value='".$data->metodo_id."'>".$data->descripcion."</option>\n";
                    }
                    return $option;
               break;
          }
     }
     
     /*
     *	GetControlOxiFlujo
     */
     function GetControlOxiFlujo($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $flujo=array();
                    $query = "SELECT * FROM hc_tipos_flujos_oxigenoterapia WHERE flujo_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                         if (!$resultado) {
                              $this->error = "Error, no se encuentra el registro en \"hc_tipos_flujos_oxigenoterapia\" con el flujo_id \"$posicion_id\"";
                              $this->mensajeDeError = $query;
                              return false;
                         }
                    while ($data = $resultado->FetchRow()) {
                         $flujo[]=$data;
                    }
                    return $flujo;
               break;
               case 1:
                    $query = "SELECT * FROM hc_tipos_flujos_oxigenoterapia";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error, la tabla hc_tipos_flujos_oxigenoterapia no contiene registros";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchNextObject($toUpper=false))
                    {
                         if ($data->flujo_id==$posicion_id)
                              $option.="<option value='".$data->flujo_id."' selected>".$data->descripcion."</option>\n";
                         else
                              $option.="<option value='".$data->flujo_id."'>".$data->descripcion."</option>\n";
                    }
                    return $option;
               break;
          }
     }

     /*
     *	GetControlOxiConcentraciones
     */
     function GetControlOxiConcentraciones($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $conc=array();
                    $query = "SELECT * FROM hc_tipos_concentracion_oxigenoterapia WHERE concentracion_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if (!$resultado) {
                         $this->error = "Error, no se encuentra el registro en \"hc_tipos_concentracion_oxigenoterapia\" con la concentracion_id \"$posicion_id\"";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchRow()) {
                         $conc[]=$data;
                    }
                    return $conc;
               break;
               case 1:
                    $query = "SELECT * FROM hc_tipos_concentracion_oxigenoterapia";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error, la tabla hc_tipos_concentracion_oxigenoterapia no contiene registros";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchNextObject($toUpper=false))
                    {
                         if ($data->concentracion_id==$posicion_id)
                              $option.="<option value='".$data->concentracion_id."' selected>".$data->descripcion."</option>\n";
                         else
                              $option.="<option value='".$data->concentracion_id."'>".$data->descripcion."</option>\n";
                    }
                    return $option;
               break;
          }
     }

     /**
     *	VerificaOxigenoterapiaPaciente
     */
     function VerificaOxigenoterapiaPaciente($evolucion)
     {
          $query="SELECT * FROM hc_oxigenoterapia WHERE evolucion_id=".$evolucion;
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          list($dbconn) = GetDBconn();
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar las posiciones del paciente en \"hc_oxigenoterapia\" con evolucion_id=".$evolucion;
               $this->mensajeDeError = $query;
               return false;
          }
          $data=$resultado->FetchRow();
          return $data;
     }
     
	/*
     *	GetControlReposo
     */
     function GetControlReposo($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $reposo=array();
                    $query = "SELECT * FROM hc_tipos_reposo_paciente WHERE tipo_reposo_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if (!$resultado) {
                         $this->error = "Error, no se encuentra el registro en \"hc_tipos_posicion_paciente\" con el tipo_reposo_id \"$posicion_id\"";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchRow()) {
                         $reposo[]=$data;
                    }
                    return $reposo;
               break;
               case 1:
                    $query = "SELECT * FROM hc_tipos_reposo_paciente";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error, la tabla hc_tipos_reposo_paciente no contiene registros";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchNextObject($toUpper=false))
                    {
                         if ($data->tipo_reposo_id==$posicion_id)
                              $option.="<option value='".$data->tipo_reposo_id."' selected>".$data->descripcion."</option>\n";
                         else
                              $option.="<option value='".$data->tipo_reposo_id."'>".$data->descripcion."</option>\n";
                    }
                    return $option;
               break;
               case 2:
                    $reposo=array();
                    $query = "SELECT * FROM hc_tipos_reposo_paciente";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if (!$resultado) {
                         $this->error = "Error, la tabla hc_tipos_reposo_paciente no contiene registros";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchRow()) {
                         $reposo[]=$data;
                    }
                    return $reposo;
               break;
          }
     }
     
     /**
     *	VerificaTerapiasRespiratoriasPacientes
     */
     function VerificaTerapiasRespiratoriasPacientes($evolucion)
     {
          $query="SELECT * FROM hc_terapias_respiratorias WHERE evolucion_id=".$evolucion;
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          list($dbconn) = GetDBconn();
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_terapias_respiratorias\" con evolucion_id=".$evolucion;
               $this->mensajeDeError = $query;
               return false;
          }
          $data=$resultado->FetchRow();
          return $data;
     }

     /*
     *	GetControlTerResp
     */
     function GetControlTerResp($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $terapia=array();
                    $query = "SELECT * FROM hc_tipos_frecuencia_terapia_respiratoria WHERE frecuencia_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if (!$resultado) {
                         $this->error = "Error, no se encuentra el registro en \"hc_tipos_frecuencia_terapia_respiratoria\" con la frecuencia_id \"$posicion_id\"";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchRow()) {
                         $terapia[]=$data;
                    }
                    return $terapia;
               break;
               case 1:
                    $query = "SELECT * FROM hc_tipos_frecuencia_terapia_respiratoria";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error, la tabla hc_tipos_frecuencia_terapia_respiratoria no contiene registros";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchNextObject($toUpper=false))
                    {
                         if ($data->frecuencia_id==$posicion_id)
                              $option.="<option value='".$data->frecuencia_id."' selected>".$data->descripcion."</option>\n";
                         else
                              $option.="<option value='".$data->frecuencia_id."'>".$data->descripcion."</option>\n";
                    }
                    return $option;
               break;
          }
     }

     /*     
     *	VerificaCurvasTermicasPaciente
     */
     function VerificaCurvasTermicasPaciente($evolucion)
     {
          $query="SELECT * FROM hc_curvas_termicas WHERE evolucion_id=".$evolucion;
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          list($dbconn) = GetDBconn();
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar las posiciones del paciente en \"hc_posicion_paciente\" con evolucion_id=".$evolucion;
               $this->mensajeDeError = $query;
               return false;
          }
          $data=$resultado->FetchRow();
          return $data;
     }

	/*
     *	GetControlCurTerm
     */
     function GetControlCurTerm($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $curTerm=array();
                    $query = "SELECT * FROM hc_tipos_frecuencia_curva_termica WHERE frecuencia_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if (!$resultado) {
                         $this->error = "Error, no se encuentra el registro en \"hc_tipos_frecuencia_curva_termica\" con la frecuencia_id \"$posicion_id\"";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchRow()) {
                         $curTerm[]=$data;
                    }
                    return $curTerm;
               break;
               case 1:
                    $query = "SELECT * FROM hc_tipos_frecuencia_curva_termica";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error, la tabla hc_tipos_frecuencia_curva_termica no contiene registros";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchNextObject($toUpper=false))
                    {
                         if ($data->frecuencia_id==$posicion_id)
                              $option.="<option value='".$data->frecuencia_id."' selected>".$data->descripcion."</option>\n";
                         else
                              $option.="<option value='".$data->frecuencia_id."'>".$data->descripcion."</option>\n";
                    }
                    return $option;
               break;
          }
     }
     
     /**
     *	VerificaControlLiquidosPaciente
     */
     function VerificaControlLiquidosPaciente($evolucion)
     {
          $query="SELECT * FROM hc_control_liquidos WHERE evolucion_id=".$evolucion;
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          list($dbconn) = GetDBconn();
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_liquidos\" con evolucion_id=".$evolucion;
               $this->mensajeDeError = $query;
               return false;
          }
          $data=$resultado->FetchRow();
          return $data;
     }

     /*
     *	GetControlLiquidos
     */
     function GetControlLiquidos($posicion_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $liquidos=array();
          $query = "SELECT * FROM hc_control_liquidos WHERE evolucion_id='".$posicion_id."'";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado) {
               $this->error = "Error, no se encuentra el registro en \"hc_control_liquidos\" con la evolucion_id \"$posicion_id\"";
               $this->mensajeDeError = $query;
               return false;
          }
          while ($data = $resultado->FetchRow()) {
               $liquidos[]=$data;
          }
          return $liquidos;
     }

     /**
     *	verificaTensionArterialPaciente
     */
     function verificaTensionArterialPaciente($evolucion)
     {
          $query="SELECT * FROM hc_control_tension_arterial WHERE evolucion_id=".$evolucion;
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          list($dbconn) = GetDBconn();
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_tension_arterial\" con evolucion_id=".$evolucion;
               $this->mensajeDeError = $query;
               return false;
          }
          $data=$resultado->FetchRow();
          return $data;
     }

	/*
     *	GetControlTA
     */
     function GetControlTA($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $ta=array();
                    $query = "SELECT * FROM hc_tipos_frecuencia_ta WHERE frecuencia_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if (!$resultado) {
                         $this->error = "Error, no se encuentra el registro en \"hc_tipos_frecuencia_ta\" con la frecuencia_id \"$posicion_id\"";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchRow()) {
                         $ta[]=$data;
                    }
                    return $ta;
               break;
               case 1:
                    $query = "SELECT * FROM hc_tipos_frecuencia_ta";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error, la tabla hc_tipos_frecuencia_ta no contiene registros";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchNextObject($toUpper=false))
                    {
                         if ($data->frecuencia_id==$posicion_id)
                              $option.="<option value='".$data->frecuencia_id."' selected>".$data->descripcion."</option>\n";
                         else
                              $option.="<option value='".$data->frecuencia_id."'>".$data->descripcion."</option>\n";
                    }
                    return $option;
               break;
          }
     }

     /**
     *	verificaGlucometriaPaciente
     */
     function verificaGlucometriaPaciente($evolucion)
     {
          $query="SELECT * FROM hc_control_glucometria WHERE evolucion_id=".$evolucion;
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          list($dbconn) = GetDBconn();
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_glucometria\" con evolucion_id=".$evolucion;
               $this->mensajeDeError = $query;
               return false;
          }
          $data=$resultado->FetchRow();
          return $data;
     }
     
     /*
     *	GetControlGlucometria
     */
     function GetControlGlucometria($posicion_id,$valor)
     {
	     GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $gluco=array();
                    $query = "SELECT * FROM hc_tipos_frecuencia_glucometrias WHERE frecuencia_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if (!$resultado) {
                         $this->error = "Error, no se encuentra el registro en \"hc_tipos_frecuencia_glucometrias\" con la frecuencia_id \"$posicion_id\"";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchRow()) {
                         $gluco[]=$data;
                    }
                    return $gluco;
               break;
               case 1:
                    $query = "SELECT * FROM hc_tipos_frecuencia_glucometrias";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error, la tabla hc_tipos_frecuencia_glucometrias no contiene registros";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchNextObject($toUpper=false))
                    {
                         if ($data->frecuencia_id==$posicion_id)
                              $option.="<option value='".$data->frecuencia_id."' selected>".$data->descripcion."</option>\n";
                         else
                              $option.="<option value='".$data->frecuencia_id."'>".$data->descripcion."</option>\n";
                    }
                    return $option;
               break;
          }
     }

     /**
     *	verificaControlCuracionesPaciente
     */
     function verificaControlCuracionesPaciente($evolucion)
     {
          $query="SELECT * FROM hc_control_curaciones WHERE evolucion_id=".$evolucion;
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          list($dbconn) = GetDBconn();
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_curaciones\" con evolucion_id=".$evolucion;
               $this->mensajeDeError = $query;
               return false;
          }
          $data=$resultado->FetchRow();
          return $data;
     }

     /*
     *	GetControlCuraciones
     */
     function GetControlCuraciones($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $curacion=array();
                    $query = "SELECT * FROM hc_tipos_frecuencia_curaciones WHERE frecuencia_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if (!$resultado) {
                         $this->error = "Error, no se encuentra el registro en \"hc_tipos_frecuencia_curaciones\" con la frecuencia_id \"$posicion_id\"";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchRow()) {
                         $curacion[]=$data;
                    }
                    $resultado->Close();
                    return $curacion;
               break;
               case 1:
                    $query = "SELECT * FROM hc_tipos_frecuencia_curaciones";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error, la tabla hc_tipos_frecuencia_curaciones no contiene registros";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchNextObject($toUpper=false))
                    {
                         if ($data->frecuencia_id==$posicion_id)
                              $option.="<option value='".$data->frecuencia_id."' selected>".$data->descripcion."</option>\n";
                         else
                              $option.="<option value='".$data->frecuencia_id."'>".$data->descripcion."</option>\n";
                    }
                    $resultado->Close();
                    return $option;
               break;
          }
     }

	/**
     *	verificaControlNeurologicoPaciente
     */
     function verificaControlNeurologicoPaciente($evolucion)
     {
          $query="SELECT * FROM hc_control_neurologico WHERE evolucion_id=".$evolucion;
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          list($dbconn) = GetDBconn();
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_neurologico\" con evolucion_id=".$evolucion;
               $this->mensajeDeError = $query;
               return false;
          }
          $data=$resultado->FetchRow();
          return $data;
     }

	/*
     *	GetControlNeurologico
     */
     function GetControlNeurologico($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $neuro=array();
                    $query = "SELECT * FROM hc_tipos_frecuencia_control_neurologico WHERE frecuencia_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if (!$resultado) {
                         $this->error = "Error, no se encuentra el registro en \"hc_tipos_frecuencia_control_neurologico\" con la frecuencia_id \"$posicion_id\"";
                         $this->mensajeDeError = $query;
                         return false;
                    }
	               while ($data = $resultado->FetchRow()) {
                         $neuro[]=$data;
                    }
                    return $neuro;
               break;
               case 1:
                    $query = "SELECT * FROM hc_tipos_frecuencia_control_neurologico";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error, la tabla hc_tipos_frecuencia_control_neurologico no contiene registros";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchNextObject($toUpper=false))
                    {
                         if ($data->frecuencia_id==$posicion_id)
                              $option.="<option value='".$data->frecuencia_id."' selected>".$data->descripcion."</option>\n";
                         else
                              $option.="<option value='".$data->frecuencia_id."'>".$data->descripcion."</option>\n";
                    }
                    return $option;
               break;
          }
     }
     
     /*
     *	GetControlParto
     */
     function GetControlParto($evolucion_id)
     {
          $parto=array();
          $query = "SELECT * FROM hc_control_trabajo_parto WHERE evolucion_id='".$evolucion_id."'";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if (!$resultado) {
                    $this->error = "Error";
                    $this->mensajeDeError = "no se encuentra el registro en \"hc_control_trabajo_parto\" con la evolucion_id \"$evolucion_id\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
          while ($data = $resultado->FetchRow()) {
               $parto[]=$data;
          }
          return $parto;
     }

     /**
     *	verificaPerimetroAbdominalPaciente
     */
     function verificaPerimetroAbdominalPaciente($evolucion)
     {
          $query="SELECT * FROM hc_control_perimetro_abdominal WHERE evolucion_id=".$evolucion;
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          list($dbconn) = GetDBconn();
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_perimetro_abdominal\" con evolucion_id=".$evolucion;
               $this->mensajeDeError = $query;
               return false;
          }
          $data=$resultado->FetchRow();
          return $data;
     }
     
     /*
     *	GetControlPerAbdominal
     */
     function GetControlPerAbdominal($posicion_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $perAbd=array();
          $query = "SELECT * FROM hc_control_perimetro_abdominal WHERE evolucion_id='".$posicion_id."'";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if (!$resultado) {
                    $this->error = "Error, no se encuentra el registro en \"hc_control_perimetro_abdominal\" con la evolucion_id \"$posicion_id\"";
                    $this->mensajeDeError = $query;
                    return false;
               }
          while ($data = $resultado->FetchRow()) {
               $perAbd[]=$data;
          }
          return $perAbd;
     }
     
     /**
     *	verificaPerimetroCefalicoPaciente
     */
     function verificaPerimetroCefalicoPaciente($evolucion)
     {
          $query="SELECT * FROM hc_control_perimetro_cefalico WHERE evolucion_id=".$evolucion;
          GLOBAL $ADODB_FETCH_MODE;
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          list($dbconn) = GetDBconn();
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_perimetro_cefalico\" con evolucion_id=".$evolucion;
               $this->mensajeDeError = $query;
               return false;
          }
          $data=$resultado->FetchRow();
          return $data;
     }

     /*
     *	GetControlPerCefalico
     */
     function GetControlPerCefalico($posicion_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $perCefalico=array();
          $query = "SELECT * FROM hc_control_perimetro_cefalico WHERE evolucion_id='".$posicion_id."'";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if (!$resultado) {
               $this->error = "Error, no se encuentra el registro en \"hc_control_perimetro_cefalico\" con la evolucion_id \"$posicion_id\"";
               $this->mensajeDeError = $query;
               return false;
          }
          while ($data = $resultado->FetchRow()) {
               $perCefalico[]=$data;
          }
          return $perCefalico;
     }
	
     /*
     *	GetControlPerExtremidades
     */
     function GetControlPerExtremidades($posicion_id,$valor)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $option="";
          switch ($valor)
          {
               case 0:
                    $extremidad=array();
                    $query = "SELECT * FROM hc_tipos_extremidades_paciente WHERE tipo_extremidad_id='".$posicion_id."'";
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado=$dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if (!$resultado) {
                         $this->error = "Error, no se encuentra el registro en \"hc_tipos_extremidades_paciente\" con el tipo_extremidad_id \"$posicion_id\"";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchRow()) {
                         $extremidad[]=$data;
                    }
                    return $extremidad;
               break;
               case 1:
                    $query = "SELECT * FROM hc_tipos_extremidades_paciente";
                    $resultado=$dbconn->Execute($query);
                    if (!$resultado) {
                         $this->error = "Error, la tabla hc_tipos_extremidades_paciente no contiene registros";
                         $this->mensajeDeError = $query;
                         return false;
                    }
                    while ($data = $resultado->FetchNextObject($toUpper=false))
                    {
                         if ($data->tipo_extremidad_id==$posicion_id)
                              $option.="<option value='".$data->tipo_extremidad_id."' selected>".$data->descripcion."</option>\n";
                         else
                              $option.="<option value='".$data->tipo_extremidad_id."'>".$data->descripcion."</option>\n";
                    }
                    return $option;
               break;
               case 2:
                                   $extremidad=array();
                                   $query = "SELECT * FROM hc_tipos_extremidades_paciente";
                                   $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                                   $resultado=$dbconn->Execute($query);
                                   $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                                        if (!$resultado) {
                                             $this->error = "Error, la tabla hc_tipos_extremidades_paciente no contiene registros";
                                             $this->mensajeDeError = $query;
                                             return false;
                                        }
                                   while ($data = $resultado->FetchRow()) {
                                        $extremidad[]=$data;
                                   }
                                   return $extremidad;
               break;
          }
     }
     
     /*
     *	GetCControlDietasDetalle
     */
     function GetCControlDietasDetalle($control)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $query="SELECT dietas_d.*,
                         dietas.descripcion
                  FROM	hc_solicitudes_dietas dietas_d,
                         hc_tipos_dieta dietas
                  WHERE dietas_d.evolucion_id=".$control['evolucion_id']." AND
                        dietas.hc_dieta_id=dietas_d.hc_dieta_id";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$dbconn->Execute($query);
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_dietas\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          while ($data = $resultado->FetchRow()) {
               $dietas_d[]=$data;
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $dietas_d;
     }

     /*
     *	GetCControlDietas
     */
     function GetCControlDietas($control)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $query="SELECT *
                  FROM hc_solicitudes_dietas
                  WHERE evolucion_id=".$control['evolucion_id'];
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$dbconn->Execute($query);
          if (!$resultado)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return "ShowMensaje";
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $resultado->FetchRow();
     }

     
     /*
	*	GetControlesProgramados()
	*/
	function GetControlesProgramadosNoCumplidos($estacion_id,$ingreso,$control)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		//Obtiene todos los controles no cumplidos con un rango de 12 horas hacia atras
		$query="SELECT  estado,
                          fecha
			   FROM hc_agenda_controles
                  WHERE ingreso=$ingreso AND
				    estacion_id='$estacion_id' AND
				    control_id='$control' AND
				    estado = '0' AND
                  (--fecha > (timestamp '".date ("Y-m-d H").":00:00' - interval '12 hours') AND
                   fecha < (timestamp '".date ("Y-m-d H").":00:00'))";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultC = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
			return "ShowMensaje";
		}
		while ($data = $resultC->FetchRow()) {
			$horas_no_cumplidas[]=$data;
		}
		return $horas_no_cumplidas;
	}
     
     /*
	*	GetControlesProgramados()
	*/
	function GetControlesProgramadosSiguientesTurnos($estacion_id,$ingreso,$control)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$next_turno=array();
		//Obtiene todos los siguientes controles
		$query="SELECT estado,
					fecha
                  FROM hc_agenda_controles
			   WHERE  ingreso=$ingreso AND
					estacion_id='$estacion_id' AND
					estado = '0' AND
					control_id='$control' AND
					fecha >= (timestamp '".date ("Y-m-d H").":00:00') ";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultC = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
			return "ShowMensaje";
		}
		while ($data = $resultC->FetchRow()) {
			$next_turno[]=$data;
		}
		return $next_turno;
	}
     
     /**
	*	GetControlProgramadoHojaNeurologica
     */
	function GetControlProgramadoHojaNeurologica($ingreso)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$liquidos_diario=array();
		$query="SELECT fecha
			   FROM hc_controles_neurologia
			   WHERE ingreso=$ingreso";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultLD = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if (!$resultLD) {
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}
		while ($data = $resultLD->FetchRow()) {
			$liquidos_diario[]=$data;
		}
		return $liquidos_diario;
	}
     
     /**
     *	CallFrmProgramarTurnos
     */
     function CallFrmProgramarTurnos()
     {
          if (!$this->FrmProgramarTurnos($_REQUEST['rango'],$_REQUEST['datos_estacion'],$_REQUEST['datosPaciente'],$_REQUEST['turnos_prgmar'],$_REQUEST['turno_fecha_rango'],$_REQUEST['href_action_hora'],$_REQUEST['href_action_control'],$_REQUEST['ingreso'],$_REQUEST['control']))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmProgramarTurnos\"";
               return false;
          }
          return true;
     }
     
     /**
     *	GetTurnosEstacion
	*/
	function GetTurnosEstacion($estacion_id)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

		$query="SELECT extract(hour from hora)as hora
						FROM hc_turnos_estacion
						WHERE estacion_id='$estacion_id'";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if (!$result) {
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}
		while ($data = $result->FetchRow()) {
			$horas[]=$data['hora'];
		}
		return $horas;
	}
     
     /**
     *		CallInsertarAgendaTurnos
     *
     *		@Author Arley Velásquez
     *		@access Public
     *		@return bool
     */
     function CallInsertarAgendaTurnos()
     {
          list($dbconn) = GetDBconn();
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $href_action_hora = $_REQUEST['href_action_hora'];

          if(!$href_action_hora)
          {$href_action_hora=$_SESSION['ESTACION']['DIRECCION']['URL'];}

          $href_action_control=$_REQUEST['href_action_control'];
          if(!$href_action_control)
          {
               $href_action_control=$_SESSION['ESTACION']['DIRECCION']['CONTROL'];
               unset($_SESSION['ESTACION']['DIRECCION']['CONTROL']);
          }
          $horas=$_POST['hora'];
          $fecha=$_POST['fecha'];
          $control=$_REQUEST['datosPaciente']['control_id'];
          $control_descripcion=$_REQUEST['datosPaciente']['control_descripcion'];
          $turno_fecha_rango=$_REQUEST['turno_fecha_rango'];
          if($_SESSION['ESTACION_CONTROL']['INGRESO'])
          {
               $ingreso_id=$_SESSION['ESTACION_CONTROL']['INGRESO'];//[duvan]
          }
          
          $estado=0;

          if (!empty($turno_fecha_rango)){
               $query="DELETE
                       FROM hc_agenda_controles
                       WHERE ingreso=".$datosPaciente['ingreso']." AND
                             estacion_id='".$datos_estacion['estacion_id']."' AND
                             estado='$estado' AND
                             control_id='$control' AND
                             ( fecha >= '".$turno_fecha_rango[0]."' AND fecha <= '".$turno_fecha_rango[1]."');";
               $resultado = $dbconn->Execute($query);
               if (!$resultado)
               {
                    $this->error = "Error al borrar en hc_agenda_controles.<br>";
                    $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
                    return false;
               }
               for ($i=0;$i<sizeof($horas);$i++) {
                    $query="SELECT fecha
	                       FROM hc_agenda_controles
                            WHERE ingreso=".$datosPaciente['ingreso']." AND
                                  control_id='$control' AND
                                  fecha='".$horas[$i]."' AND
                                  estado='1'";
                    $resultado = $dbconn->Execute($query);
                    if ($resultado->EOF)
                    {
                         $query="INSERT INTO hc_agenda_controles(ingreso,estacion_id,control_id,fecha,estado)
                                        VALUES (".$datosPaciente['ingreso'].",'".$datos_estacion['estacion_id']."','$control','".$horas[$i]."','$estado');";

                         $resultado = $dbconn->Execute($query);
                         if ($resultado)
                         {
                              //preguntamos de donde viene si de glucometria,neu
                              if(strtolower($_SESSION['ESTACION']['NOMBRE_CONTROL'])=='control neurologico')
                              {
                                   $str_actividad="Actividad Neurologico:Se debe realizar el control Neurologico para este paciente en la fecha &nbsp;"."$horas[$i]";
                              }
                              else
                              {
                                   $str_actividad="Actividad Glucometria:Se debe realizar el control de glucometria para este paciente en la fecha &nbsp;"."$horas[$i]";
                              }
                              $querys="INSERT INTO
                                       hc_control_apoyosd_pendientes
                                                  (ingreso,fecha,sw_ayuno,observacion,usuario_id,fecha_registro)
                                            VALUES(".$datosPaciente['ingreso'].",'$horas[$i]','0','".$str_actividad."'
                                                   ,".UserGetUID().",'".date("Y-m-d H:m")."')	";
                              $resulta = $dbconn->Execute($querys);
                              if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar el hc_control_apoyod_pendientes";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                              }
                         }
                    }
               }
          }
          else {
               for ($i=0;$i<sizeof($horas);$i++) {
                    $query="INSERT INTO hc_agenda_controles(ingreso,estacion_id,control_id,fecha,estado)
                                   VALUES (".$datosPaciente['ingreso'].",'".$datos_estacion['estacion_id']."','$control','".$horas[$i]."','$estado');";
                    $resultado = $dbconn->Execute($query);
                    if (!$resultado)
                    {
                         $this->error = "Error al ejecutar la consulta.<br>";
                         $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
                         return false;
                    }
                    //preguntamos de donde viene si de glucometria,neu
                    if(strtolower($_SESSION['ESTACION']['NOMBRE_CONTROL'])=='control neurologico')
                    {
                         $str_actividad="Actividad Neurologico:Se debe realizar el control Neurologico para este paciente en la fecha &nbsp;"."$horas[$i]";
                    }
                    else
                    {
                         $str_actividad="Actividad Glucometria:Se debe realizar el control de glucometria para este paciente en la fecha &nbsp;"."$horas[$i]";
                    }

                    $querys="INSERT INTO hc_control_apoyosd_pendientes
                                         (ingreso,fecha,sw_ayuno,observacion,usuario_id,fecha_registro)
                                   VALUES(".$datosPaciente['ingreso'].",'$horas[$i]','0','".$str_actividad."'
                                             ,".UserGetUID().",'".date("Y-m-d H:m")."')	";
                    $resulta = $dbconn->Execute($querys);
                    if ($dbconn->ErrorNo() != 0) {
                              $this->error = "Error al Guardar el hc_control_apoyod_pendientes";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              return false;
                    }
               }
          }
          $this->CallFrmsControlesPacientes($datosPaciente,$datos_estacion,$_REQUEST['control']);
          return true;
     }

	/**
	*
	*
	*		@Author Arley Velásquez
	*		@access Public
	*		@return bool
	*/
	function GetControlProgramadoGlucometria($ingreso)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$liquidos_diario=array();
		$query="SELECT fecha
			   FROM hc_control_diabetes
			   WHERE ingreso=$ingreso ";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultLD = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if (!$resultLD) {
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return "ShowMensaje";
		}
		while ($data = $resultLD->FetchRow()) {
			$liquidos_diario[]=$data;
		}
		return $liquidos_diario;
	}
     
	/*
     *	GetViasInsulina
     *	Obtiene las vias de administracion de insulina
     */
     function GetViasInsulina()
     {
          $query = "SELECT tipo_via_insulina_id, descripcion
                    FROM hc_tipos_vias_insulina";

          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener las vías de administracion de insulina.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    return "ShowMensaje";
               }
               else
               {
                    while ($data = $result->FetchRow()){
                         $ViasInsulina[] = $data;
                    }
                    return $ViasInsulina;
               }
          }
     }//GetViasInsulina
     
     /**
     *	GetTiposInsulina
     *	Obtiene los tipos de insulina existentes
     */
     function GetTiposInsulina()
     {
          $query = "SELECT tipo_insulina_id,descripcion
                    FROM hc_tipos_insulina
                    ORDER BY descripcion";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener los tipos de insulina.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    return "ShowMensaje";
               }
               else
               {
                    while ($data = $result->FetchRow()){
                         $TiposInsulina[] = $data;
                    }
                    return $TiposInsulina;
               }
          }
     }//GetTiposInsulina

     
     /**
     *		InsertarDatosGlucometria
     *
     *		Ingresa a la BD los datos del control de glucometria.
     *		La glucometria es independiente de la insulina no son mutuamente requeridas
     *
     *		@Author Rosa Maria Angel
     *		@access Public
     *		@return bool
     */
     function InsertarDatosGlucometria()
     {
          $datosPaciente = $_REQUEST['datosPaciente'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $fecha = $_REQUEST['turno_hora'];

          $Glucometria = $_REQUEST['Glucometria']; 
          $checkInsulina = $_REQUEST['checkInsulina'];
          $textInsulina = $_REQUEST['textInsulina'];
          $ViaInsulina = $_REQUEST['ViaInsulina'];

          $control = 8;
          //$control_descripcion = $_REQUEST['datos_estacion']['control_descripcion'];

          //valido que los valores se hayan llenado correctamente ####
          if(empty($Glucometria) && (!is_array($checkInsulina)))
          {//todos vacios
               if(!is_array($checkInsulina) || !is_array($textInsulina) || in_array("-1",$ViaInsulina)){ $this->frmError["Insulina"] = 1; }
               if(empty($Glucometria)){ $this->frmError["Glucometria"] = 1; }
               if(!is_array($checkInsulina))  { $this->frmError["SelectInsulina"] = 1; }
               if(in_array("",$textInsulina))   { $this->frmError["TextInsulina"] = 1; }
               if(in_array("-1",$ViaInsulina)){ $this->frmError["ViaInsulina"] = 1; }
               $this->frmError["MensajeError"] = "FALTAN DATOS OBLIGATORIOS";
               $this->FrmIngresarDatosGlucometria($datosPaciente,$datos_estacion,$_REQUEST['control'],$_REQUEST['href_action_hora'],$_REQUEST['href_action_control'],$_REQUEST['turno_hora']);
               return true;
          }
          elseif(is_array($checkInsulina))//estos siempre son array || is_array($textInsulina) || !in_array("-1",$ViaInsulina)
          {//con que uno sea array los demás deben serlo
               if(is_array($checkInsulina) && is_array($textInsulina))// && !in_array("-1",$ViaInsulina)
               {//con que uno sea array los demás deben serlo
                    foreach($checkInsulina as $key => $value)
                    {
                         if(empty($textInsulina[$value]) || $ViaInsulina[$value] == -1)//
                         {
                              if(empty($textInsulina[$value])){ $this->frmError["TextInsulina"] = 1; }
                              if($ViaInsulina[$value] == -1)  { $this->frmError["ViaInsulina"] = 1; }
                              $this->frmError["MensajeError"] = "FALTAN VALORES PARA LA INSULINA SELECCIONADA";
                              $this->FrmIngresarDatosGlucometria($datosPaciente,$datos_estacion,$_REQUEST['control'],$_REQUEST['href_action_hora'],$_REQUEST['href_action_control'],$_REQUEST['turno_hora']);
                              return true;
                         }
                         else
                         {
                              if(!is_numeric($textInsulina[$value]))
                              {
                                   $this->frmError["TextInsulina"] = 1;
                                   $this->frmError["MensajeError"] = "DIGITE NUMEROS EN ESTA CASILLA POR FAVOR!";
							$this->FrmIngresarDatosGlucometria($datosPaciente,$datos_estacion,$_REQUEST['control'],$_REQUEST['href_action_hora'],$_REQUEST['href_action_control'],$_REQUEST['turno_hora']);
                                   return true;
                              }
                         }
                    }
               }
               else
               {//si uno es array los demás deben serlo
                    if(!is_array($checkInsulina))  { $this->frmError["SelectInsulina"] = 1; }
                    if(in_array("",$textInsulina))  { $this->frmError["TextInsulina"] = 1; }
                    if(in_array("-1",$ViaInsulina)){ $this->frmError["ViaInsulina"] = 1; }
                    $this->frmError["MensajeError"] = "LOS DATOS PARA LA INSULINA SELECCIONADA SON INSUFICIENTES";
				$this->FrmIngresarDatosGlucometria($datosPaciente,$datos_estacion,$_REQUEST['control'],$_REQUEST['href_action_hora'],$_REQUEST['href_action_control'],$_REQUEST['turno_hora']);
                    return true;
               }
          }

          if(!is_numeric($Glucometria))
          {
               $this->frmError["Glucometria"] = 1;
               $this->frmError["MensajeError"] = "DIGITE NUMEROS EN ESTA CASILLA POR FAVOR!";
               $this->FrmIngresarDatosGlucometria($datosPaciente,$datos_estacion,$_REQUEST['control'],$_REQUEST['href_action_hora'],$_REQUEST['href_action_control'],$_REQUEST['turno_hora']);
               return true;
          }

          #### una vez validado puedo insertar #######
          list($dbconn) = GetDBconn();

          if(empty($Glucometria)){
               $Glucometria = "NULL";
          }
          if($textInsulina['cristalina']){
               $valor_cristalina = $textInsulina['cristalina'];
          }
          else{
               $valor_cristalina = "NULL";
          }
          if($textInsulina['nph']){
               $valor_nph = $textInsulina['nph'];
          }
          else{
               $valor_nph = "NULL";
          }
          if($ViaInsulina['cristalina']!=-1){
               $via_cristalina = $ViaInsulina['cristalina'];
          }
          else{
               $via_cristalina = "NULL";
          }
          if($ViaInsulina['nph']!=-1){
               $via_nph = $ViaInsulina['nph'];
          }
          else{
               $via_nph = "NULL";
          }

          $query = "INSERT INTO hc_control_diabetes (  ingreso,
                                                       fecha,
                                                       glucometria,
                                                       valor_cristalina,
                                                       via_cristalina,
                                                       valor_nph,
                                                       via_nph,
                                                       usuario)
                              			VALUES ( ".$datosPaciente['ingreso'].",
                                                       '".$fecha."',
                                                       ".$Glucometria.",
                                                       ".ltrim($valor_cristalina).",
                                                       ".ltrim($via_cristalina).",
                                                       ".ltrim($valor_nph).",
                                                       ".ltrim($via_nph).",
                                                       ".UserGetUID().")";

          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar ingresar los datos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }
          else
          {
               $query = "UPDATE hc_agenda_controles
                         SET estado='1'
                         WHERE ingreso = ".$datosPaciente['ingreso']." AND
                               estacion_id = '".$datos_estacion['estacion_id']."' AND
                               control_id = '".$control."' AND
                               fecha = '".$fecha."' ";

               $result = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar ingresar marcar como cumplido el control<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
               else{
                    $dbconn->CommitTrans();
	               $this->frmError["MensajeError"] = "LOS DATOS FUERON INSERTADOS SATISFACTORIAMENTE";
               }
          }
		$this->FrmIngresarDatosGlucometria($datosPaciente,$datos_estacion,$_REQUEST['control'],$_REQUEST['href_action_hora'],$_REQUEST['href_action_control'],$_REQUEST['turno_hora']);         
          return true;
     }//InsertarDatosGlucometria

     /**
     *	GetRangoControl
     *	@Author Arley Velásquez Castillo
     *	@access Public
     *	@return bool
     */
     function GetRangoControl($control_id,$datos_paciente)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $controles=array();

          $query="SELECT *
                  FROM  hc_rangos_controles
                  WHERE control_id='$control_id' AND
                        sexo = '".$datos_paciente["sexo"]."' AND
                        ".$datos_paciente["edad"]["anos"]." BETWEEN edad_min AND edad_max";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          if (!$resultado) {
               $this->error = "Error al buscar el tipo de control en \"hc_tipos_controles_paciente\"<br>";
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          $controles = $resultado->FetchRow();
          if (!empty($controles["control_id"])){
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               return $controles;
          }
          else{
               $query="SELECT *
                       FROM  hc_rangos_tipos_controles
                       WHERE control_id='$control_id'";

               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               if (!$resultado) {
                    $this->error = "Error al buscar el tipo de control en \"hc_tipos_controles_paciente\"<br>";
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    return false;
               }
               $controles = $resultado->FetchRow();
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               return $controles;
          }
     }
     
     /**
     *	Obtiene los registros del control de glucometira del ingreso X
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool-array-string
     */
     function GetResumenGlucometria($ingreso)
     {
          $query = "SELECT CDG.ingreso,
                           CDG.fecha,
                           CDG.glucometria,
                           CDG.valor_cristalina,
                           CDG.via_cristalina,
                           TVIA.descripcion as viacristalina,
                           CDG.valor_nph,
                           CDG.via_nph,
                           TVIB.descripcion as vianph
                    FROM hc_control_diabetes CDG
                    LEFT JOIN hc_tipos_vias_insulina TVIA ON (TVIA.tipo_via_insulina_id = CDG.via_cristalina)
                    LEFT JOIN hc_tipos_vias_insulina TVIB ON (TVIB.tipo_via_insulina_id = CDG.via_nph)
                    WHERE CDG.ingreso = $ingreso
                    ORDER BY CDG.fecha DESC;";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener los controles de diabetes del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    return "ShowMensaje";
               }
               else
               {
                    while ($data = $result->FetchRow())
                    {
                         $controles[$data[fecha]][] = $data;
                    }
                    return $controles;
               }
          }
     }//Fin GetResumenGlucometria
     
     /**
     *	CallFrmIngresarDatosLiquidos
     *	Hace un llamado al formulario de captura de datos de liquidos
     */
     function CallFrmIngresarDatosLiquidos()
     {
		if(!$this->FrmIngresarDatosLiquidos($_REQUEST['referer_parameters'],$_REQUEST["referer_name"],$_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],'','','','',''))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmIngresarDatosLiquidos\"";
               return false;
          }
          return true;
     }
     
     /**
     *	GetTipoLiquidosAdministrados
     *	Consulta la tabla hc_tipo_liquidos_administrados
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool ó array
     */
     function GetTipoLiquidosAdministrados()
     {
          $query = "SELECT * FROM hc_tipo_liquidos_administrados  ORDER BY tipo_liquido_administrado_id";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener los tipos de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               while ($data = $result->FetchRow()) 
               {
                    $liquidos[] = $data;
               }
               return $liquidos;
          }
     }
     
     /**
     *	GetTipoLiquidosEliminados
     *	Consulta la tabla hc_tipo_liquidos_eliminados
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool ó array
     */
     function GetTipoLiquidosEliminados()
     {
          $query = "SELECT * FROM hc_tipo_liquidos_eliminados ORDER BY tipo_liquido_eliminado_id";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener los tipos de liquidos eliminados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               while ($data = $result->FetchRow()) 
               {
                    $liquidos[] = $data;
               }
               return $liquidos;
          }
     }

     /*
     *	InsertarDatosLiquidos
     *
     *	Inserta los datos del control de liquidos al paciente X
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool
     */
     function InsertarDatosLiquidos()
     {
          $datosPaciente = $_REQUEST['datosPaciente'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $cantAdmin = $_REQUEST['cantAdmin'];
          $cantElim = $_REQUEST['cantElim'];
          $selectElim = $_REQUEST['selectElim'];
          $selectHora = $_REQUEST['selectHora'];
          $selectMinutos = $_REQUEST['selectMinutos'];
          $control = $_REQUEST['referer_parameters']['idControl'];
          $control_descripcion = $_REQUEST['referer_parameters']['descripcion'];
          $referer_parameters = $_REQUEST['referer_parameters'];
          $referer_name = $_REQUEST['referer_name'];
          $liquidoA = $_REQUEST['liquidoA'];
          $liquidoE = $_REQUEST['liquidoE'];
          
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

          foreach ($cantElim as $key => $value)
          {
               if(!empty($value))
               {
                    $query = "SELECT *
                              FROM hc_control_liquidos_eliminados
                              WHERE ingreso = ".$datosPaciente['ingreso']."  AND
                                    tipo_liquido_eliminado_id = ".$key." AND
                                    fecha = '".$selectHora.":".$selectMinutos."'";
                    GLOBAL $ADODB_FETCH_MODE;
                    list($dbconn) = GetDBconn();
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $result = $dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al ejecutar la conexion";
                         $this->mensajeDeError = "Ocurrió un error al intentar verificar que los liquidos eliminados existentes antes de insertar.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         return false;
                    }
                    else
                    {
                         if(!$result->EOF)
                         {
                              while ($data = $result->FetchRow()) {
                                   $LiquidosElimExistentes[] = $data;
                              }
                         }
                    }
               }//fin if
          }

          foreach($selectElim as $keyE => $value)
          {
               $query = "SELECT *
                         FROM hc_control_liquidos_eliminados
                         WHERE ingreso = ".$datosPaciente['ingreso']."  AND
                                   tipo_liquido_eliminado_id = ".$key." AND
                                   fecha = '".$selectHora.":".$selectMinutos."'";
               GLOBAL $ADODB_FETCH_MODE;
               list($dbconn) = GetDBconn();
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $result = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar verificar que los liquidos eliminados existentes antes de insertar.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
               else
               {
                    if(!$result->EOF)
                    {
                         while ($data = $result->FetchRow()) {
                              $LiquidosElimExistentes[] = $data;
                         }
                    }
               }
          }

          if(sizeof($LiquidosElimExistentes))
          {
               $this->frmError["MensajeError"] = "EN LA FECHA-HORA: '".$selectHora.":".$selectMinutos."' YA EXISTEN REGISTRO DE LIQUIDOS ELIMINADOS, ESPECIFIQUE UNA HORA DIFERENTE";
               $this->FrmIngresarDatosLiquidos($referer_parameters,$referer_name,$datosPaciente,$datos_estacion,$cantAdmin,$cantElim,$selectElim,$selectHora,$selectMinutos);
               return true;
          }

          ############################# INSERCION DE LOS LIQUIDOS ########################################
          $dbconn->BeginTrans();
          $puedoHacerCommit = array();
          foreach ($cantAdmin as $key => $value)
          {
               if(!empty($value))
               {
                    $XXX=$_REQUEST['liquidoA'.$key];
                    $query = "INSERT INTO hc_control_liquidos_administrados 
                                                       (
                                                            ingreso,
                                                            fecha,
                                                            tipo_liquido_administrado_id,
                                                            cantidad,
                                                            usuario_id,
                                                            fecha_registro,
                                                            detalle
                                                       )
                                                VALUES (
                                                            ".$datosPaciente['ingreso'].",
                                                            '".$selectHora.":".$selectMinutos."',
                                                            ".$key.",
                                                            ".$value.",
                                                            ".UserGetUID().",
                                                            now(),
                                                            '$XXX'
                                                       );";
                    
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al ejecutar la conexion";
                         $this->mensajeDeError = "Ocurrió un error al intentar ingresar los datos de liquidos administrados<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         $puedoHacerCommit[] = 0;
                         return false;
                    }
                    else{
                         $puedoHacerCommit[] = 1;
                    }
               }//fin si esta lleno
          }//fin foreach

          //insert liquidos eliminados
          foreach ($cantElim as $keyE => $valueE)
          {
               if(!empty($valueE))
               {
                    //se revisa si es por eliminación urinaria que es 0
                    //via 1 espontanea
                    // 2 sonda
                    if($keyE==0){$via=$_REQUEST['eliminacionu'];}else{$via='';}
                    $XXE=$_REQUEST['liquidoE'.$keyE];
                    $query = "INSERT INTO hc_control_liquidos_eliminados 
                                                       (
                                                            ingreso,
                                                            fecha,
                                                            tipo_liquido_eliminado_id,
                                                            cantidad,
                                                            usuario_id,
                                                            fecha_registro,
                                                            via,
                                                            detalle
                                                       )
	                                           VALUES (
                                                            ".$datosPaciente['ingreso'].",
                                                            '".$selectHora.":".$selectMinutos."',
                                                            ".$keyE.",
                                                            ".$valueE.",
                                                            ".UserGetUID().",
                                                            now(),
                                                            '$via',
                                                            '$XXE'
                                                       );";
                    
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al ejecutar la conexion";
                         $this->mensajeDeError = "Ocurrió un error al intentar ingresar los datos de liquidos eliminados<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         $puedoHacerCommit[] = 0;
                         return false;
                    }
                    else{
                         $puedoHacerCommit[] = 1;
                    }
               }//fin si esta lleno
          }//fin foreach

          foreach($selectElim as $keyE => $valueE)
          {
               $query = "INSERT INTO hc_control_liquidos_eliminados 
                                             (
                                                  ingreso,
                                                  fecha,
                                                  tipo_liquido_eliminado_id,
                                                  cantidad,
                                                  deposicion,
                                                  usuario_id,
                                                  fecha_registro,
                                                  detalle
                                             )
                                      VALUES (
                                                  ".$datosPaciente['ingreso'].",
                                                  '".$selectHora.":".$selectMinutos."',
                                                  ".$keyE.",
                                                  0,
                                                  '$valueE',
                                                  ".UserGetUID().",
                                                  now(),
                                                  '$XXE'
                                              );";

               $result = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar ingresar los datos de liquidos eliminados<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    $puedoHacerCommit[] = 0;
                    return false;
               }
               else{
                    $puedoHacerCommit[] = 1;
               }
          }

          if(!in_array(0,$puedoHacerCommit))
          {
               $query = "UPDATE hc_agenda_controles
                         SET estado='1'
                         WHERE ingreso = ".$datosPaciente['ingreso']." 
                         AND estacion_id = '".$datos_estacion['estacion_id']."' 
                         AND control_id = '".$control."'
                         AND fecha = '".$selectHora.":".$selectMinutos."';";

               $result = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al ejecutar la conexion";
                    $this->mensajeDeError = "Ocurrió un error al intentar intentar marcar como cumplido el control<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
               else{
                    //$dbconn->RollbackTrans();
                    $dbconn->CommitTrans();
               }
          }
          unset($cantAdmin); unset($cantElim); unset($selectElim); unset($selectHora); unset($selectMinutos);

          if ($referer_name=="FrmControlesLiquidos"){
               $this->FrmControlesLiquidos($datos_estacion,$_SESSION['EE_PanelEnfermeria']['listadoPacientes'],$referer_parameters["control"],$referer_parameters["descripcion"],$referer_parameters['idControl']);
          }
          return true;
     }//InsertarDatosLiquidos
     
     
     /**
	*	GetFechasLiquidos
     *
     *	@Author Arley Velásquez
	*	@access Public
	*	@return bool
	*/
	function GetFechasLiquidos($ingreso,$hora_inicio_turno,$rango_turno,$valor)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

		if (!$valor){
			$query="SELECT fecha
                       FROM hc_control_liquidos_administrados
                       WHERE ingreso=$ingreso AND
					    (
						fecha >= (timestamp '".date ("Y-m-d")." ".$hora_inicio_turno."') AND
						fecha <= (timestamp '".date ("Y-m-d")." ".$hora_inicio_turno."' + interval '$rango_turno hours')
                             )";

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultLD = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultLD) {
				$this->error = "Error al ejecutar la consulta.<br>";
				$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
				return false;
			}
			while ($data = $resultLD->FetchRow()) {
				$liquidos_diario[]=$data;
			}
		}
		else{
			$query="SELECT fecha
                       FROM hc_control_liquidos_administrados
                       WHERE ingreso=$ingreso";

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultLD = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if (!$resultLD) {
				$this->error = "Error al ejecutar la consulta.<br>";
				$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
				return false;
			}
			$liquidos_diario=$resultLD;
		}
		return $liquidos_diario;
	}
     
     /**
     *	CallFrmControlLiquidos
     *
     *	Llama la  vista que muestra un listado con los totales de liquidos adm y elim del día
     *	y llama al balance diario
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool
     */
     function CallFrmControlLiquidos()
     {
          if(!$this->FrmControlLiquidosBalance($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion']))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmControlLiquidos\"";
               return false;
          }
          return true;
     }

     /*
     *	CallFrmControlLiquidosXDias
     *
     *	Llama a la vista que muestra el balance acumulado de los liquidos del paciente
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool
     */
     function CallFrmControlLiquidosXDias()
     {
          if(!$this->FrmControlLiquidosXDias($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['control_id']))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmControlLiquidosXDias\"";
               return false;
          }
          return true;
     }
     
     /**
     *	CallFrmLiquidosEliminados
     *
     *	Muestra el detalle de los liquidos que el paciente X ha eliminado
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool
     */
     function CallFrmLiquidosEliminados()
     {
          if(!$this->FrmLiquidosEliminados($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['datosAlternos']))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmLiquidosEliminados\"";
               return false;
          }
          return true;
     }

     /**
     *	GetLiquidosEliminados
     *
     *	Muestra los liquidos eliminados en el rango de fecha dada al ingreso x
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool ó array
     *	@param integer => numero de ingreso del paciente
     *	@param timestamp => fecha inicial del rango
     *	@param timestamp => fecha final del rango
     */
     function GetLiquidosEliminados($ingreso, $fechaReciente, $fechaProxima)
     {
          $query = "SELECT A.*,
                           B.descripcion
                    FROM (
                           SELECT extract(hour from fecha) as horas,
                                  sum(cantidad) as sumas,
                                  tipo_liquido_eliminado_id,
                                  substring(fecha from 1 for 10) as fechas,
                                  deposicion,via,detalle
                           FROM hc_control_liquidos_eliminados
                           WHERE ingreso = $ingreso AND
                           (fecha between '$fechaReciente' AND '$fechaProxima')
                           GROUP BY horas,
                           tipo_liquido_eliminado_id,
                           fechas,
                           deposicion,via, detalle
                         ) as A,
                         hc_tipo_liquidos_eliminados B
                         WHERE A.tipo_liquido_eliminado_id = B.tipo_liquido_eliminado_id
                         ORDER BY A.fechas, A.horas ";

          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar hacer balance de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               while ($data = $result->FetchRow()){
                    $vLiquido[$data[horas]][] = $data;
               }
               return $vLiquido;
          }
     }//fin GetLiquidosAdministrados

     /**
     *	GetFechaIngreso
     *
     *	Obtiene la fecha de ingreso de un numero de ingreso X
     *
     *    @Author Rosa Maria Angel
     *	@access Public
     *	@return bool ó array
     *	@param integer => numero de ingreso del paciente
     */
     function GetFechaIngreso($ingreso)
     {
          $query = "SELECT fecha_ingreso
                    FROM ingresos
                    WHERE ingreso = $ingreso";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar hacer balance de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else{
               return $result->fields[fecha_ingreso];
          }
     }//GetFechaIngreso

     
     /**
     *	GetTotalAdministrados
     *
     *	Esta funcion calcula el total de liquidos administrados por hora y su acumulado
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool ó array
     *	@param integer => numero de ingreso del paciente
     *	@param timestamp => fecha inicial del rango
     *	@param timestamp => fecha final del rango
     */
     function GetTotalAdministrados($ingreso,$fechaReciente,$fechaProxima)
     {
          //ojo que se estan tomando horas de un rango de fechas, las horas no se repiten, por eso es importante el order by
	     $query = "SELECT extract(hour from fecha) as horas,
                           sum(cantidad) as sumas,
                           substring(fecha from 1 for 10) as fechas
                    FROM hc_control_liquidos_administrados
                    WHERE ingreso = $ingreso 
                    AND ((fecha between '$fechaReciente' AND '$fechaProxima'))
                    GROUP BY fechas, horas ORDER BY horas;";

          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar hacer balance de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               while ($data = $result->FetchRow())
               {
                    $CanXHora += $data[sumas];
                    $laHora = date("H:i:s",mktime($data[horas],0,0,date("m"),date("d"),date("Y")));
                    $VectorReal[$laHora][fila1] = $data[sumas];
                    $VectorReal[$laHora][total] = $CanXHora;
               }
               return $VectorReal;
          }
     }//fin GetTotalAdministrados
     
     /**
     *	GetTotalEliminados
     *
     *	Esta funcion calcula el total de liquidos eliminados por hora y su acumulado
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool ó array
     *	@param integer => numero de ingreso del paciente
     *	@param timestamp => fecha inicial del rango
     *	@param timestamp => fecha final del rango
     */
     function GetTotalEliminados($ingreso,$fechaReciente,$fechaProxima)
     {
          $query = "SELECT extract(hour from fecha) as horas,
                           sum(cantidad) as sumas,
                           substring(fecha from 1 for 10) as fechas
                    FROM hc_control_liquidos_eliminados
                    WHERE ingreso = $ingreso 
                    AND ( fecha >= (timestamp '$fechaReciente') AND
                          fecha <= (timestamp '$fechaProxima'))
                    GROUP BY fechas,horas";

          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar hacer balance de liquidos eliminados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               while ($data = $result->FetchRow())
               {
                    $CanXHora += $data[sumas];
                    $laHora = date("H:i:s",mktime($data[horas],0,0,date("m"),date("d"),date("Y")));
                    $VectorReal[$laHora][fila1] = $data[sumas];
                    $VectorReal[$laHora][total] = $CanXHora;
               }

               return $VectorReal;
          }
     }//fin GetTotalEliminados
     
     /**
     *	GetDiuresis
     *
     *	Calcula el total de diuresis eliminada en el rango de fecha dada
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool ó integer
     *	@param integer => numero de ingreso del paciente
     *	@param timestamp => fecha inicial del rango
     *	@param timestamp => fecha final del rango
     */
     function GetDiuresis($ingreso,$fechaReciente,$fechaProxima)
     {
          $query = "SELECT sum(cantidad)
                    FROM hc_control_liquidos_eliminados
                    WHERE ingreso = $ingreso 
                    AND (fecha between '$fechaReciente' AND '$fechaProxima') 
                    AND tipo_liquido_eliminado_id = '0'";

          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar hacer balance de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if(!$result->EOF){
                    return $result->fields[sum];
               }
          }
     }//fin GetDiuresis
     
     
     /*
     *	GetBalancePrevio
     *
     *	Obtiene el balance del dia anterior
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool ó array
     *	@param integer => numero de ingreso del paciente
     *	@param timestamp => fecha final del rango
     *	@param timestamp => fecha inicial del rango
     *	@param time => hora de inicio del turno
     *	@param integer => rango del turno
     */
     function GetBalancePrevio($ingreso,$fechaReciente,$fechaAnterior,$hora_inicio_turno,$rango_turno)
     {
          $vectorAdm = $this->GetTotalAdministrados($ingreso,$fechaAnterior,$fechaReciente);
          $vectorElim = $this->GetTotalEliminados($ingreso,$fechaAnterior,$fechaReciente);

          foreach ($vectorAdm as $key=>$value)
          {
               $Vector[totalAdmin] += $vectorAdm[$key][fila1];
               $Vector[totalElim] += $vectorElim[$key][fila1];
               if (!empty($vectorAdm[$key][fila1]) || !empty($vectorElim[$key][fila1]))
                    $Vector[balance] = $Vector[totalAdmin]-$Vector[totalElim];
          }
          return $Vector;
     }//fin GetBalancePrevio


	/**
     *	GetBalancesAcum
     *
     *	Calcula los totales diarios de liquidos administrados y eliminados desde la fecha de ingreso
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool ó array
     *	@param integer => numero de ingreso del paciente
     *	@param date => fecha de ingreso del paciente
     *	@param time => hora de inicio del turno de la EE
     *	@param date => duracion del turno
     */
     function GetBalancesAcum($ingreso,$fechaIngreso,$hora_inicio_turno,$rango_turno)
     {
          $fecha = $fechaAnterior = $fechaIngreso;

          while ($fecha <= date("Y-m-d H:i:s"))
          {
               list($h,$m,$s)=explode(":",$hora_inicio_turno);
               list($fecha,$thora) = explode(" ",$fecha);
               $Rfecha = explode("-",$fecha);
               $fecha = date("Y-m-d H:i:s",mktime($h,($m-1),$s,$Rfecha[1],($Rfecha[2]+1),$Rfecha[0]));
               $ojo = $this->GetBalancePrevio($ingreso,$fecha,$fechaAnterior,$hora_inicio_turno,$rango_turno);
               $eje = $this->GetDiuresis($ingreso,$fechaAnterior,$fecha);

               $fechaAnterior =date("Y-m-d H:i:s",mktime($h,$m,$s,$Rfecha[1],($Rfecha[2]+1),$Rfecha[0]));
               if($ojo || $eje)
               {
                    $X[$Rfecha[0]."-".$Rfecha[1]."-".$Rfecha[2]][0] = $ojo;
                    $X[$Rfecha[0]."-".$Rfecha[1]."-".$Rfecha[2]][1] = $eje;
                    unset($ojo); unset($eje);
               }
          }
          return $X;
     }//fin GetBalancesAcumFromFechaIngresao

          
     /*
     *	GetPesoPaciente
     *
     *	Selecciona el peso mas reciente del paciente
     *
     *	@Author Arley Velásquez
     *	@param integer => numero de ingreso del paciente
     */
     function GetPesoPaciente($ingreso)
     {
          $peso=0;
          $query="SELECT peso, max(fecha) as fecha
                  FROM hc_signos_vitales
                  WHERE ingreso=$ingreso 
                  AND peso !=0
                  GROUP BY peso ";
          list($dbconn) = GetDBconn();
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener el peso del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          if ($result->RecordCount())
          {
               $peso=$result->FetchObject(false);
               return $peso->peso;
          }
          else	return -1;
     }
     
     /**
     *	CallFrmLiquidosAdministrados
     *
     *	Muestra el detalle de los liquidos que se le han administrados al paciente
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool
     */
     function CallFrmLiquidosAdministrados()
     {
          if(!$this->FrmLiquidosAdministrados($_REQUEST['datosPaciente'],$_REQUEST['datos_estacion'],$_REQUEST['datosAlternos']))
          {
               $this->error = "No se puede cargar la vista";
               $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmLiquidosAdministrados\"";
               return false;
          }
          return true;
     }
     
     /**
     *	GetLiquidosAdministrados
     *
     *	Muestra los liquidos administrados en la fecha dada al ingreso x
     *
     *	@Author Rosa Maria Angel
     *	@access Public
     *	@return bool ó array
     *	@param integer => numero de ingreso del paciente
     *	@param timestamp => fecha inicial del rango
     *	@param timestamp => fecha final del rango
     */
     function GetLiquidosAdministrados($ingreso,$fechaReciente,$fechaProxima)
     {
          $query = "SELECT A.*,
                              B.descripcion
                    FROM (
                              SELECT extract(hour from fecha) as horas,
                                   sum(cantidad) as sumas,detalle,
                                   tipo_liquido_administrado_id,
                                   substring(fecha from 1 for 10) as fechas
                              FROM hc_control_liquidos_administrados
                              WHERE ingreso = $ingreso AND
                                   (fecha between '$fechaReciente' AND '$fechaProxima')
                              GROUP BY horas, detalle,tipo_liquido_administrado_id, fechas
                         ) as A,
                         hc_tipo_liquidos_administrados B
                    WHERE A.tipo_liquido_administrado_id=B.tipo_liquido_administrado_id
                    ORDER BY A.fechas, A.horas";

          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar hacer balance de liquidos administrados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               while ($data = $result->FetchRow()){
                    $vLiquido[$data[horas]][] = $data;
               }
               return $vLiquido;
          }
     }//fin GetLiquidosAdministrados
     
     
     /**
     *	GetCControlDrenajes
     *
     *	Control de drenajes realizados al paciente
     */     
     function GetCControlDrenajes($control)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT * FROM hc_control_drenajes WHERE evolucion_id=".$control['evolucion_id'];
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_drenajes\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          return $resultado->FetchNextObject($toUpper=false);
     }
     
     /**
     *	GetControlDrenajes
     *
     *	Control de drenajes realizados al paciente
     */     
     function GetControlDrenajes($evolucion_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $Drenajes=array();
          $query = "SELECT A.*, B.* 
                    FROM hc_control_drenajes AS A,
                    hc_tipo_control_drenaje AS B
                    WHERE evolucion_id='".$evolucion_id."'
                    AND A.tipo_drenaje = B.tipo_drenaje";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$this->Verifica_Conexion($query,$dbconn);
               if (!$resultado) {
                    $this->error = "Error, no se encuentra el registro en \"hc_control_drenajes\" con la evolucion_id \"$evolucion_id\"";
                    $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
                    return false;
               }
          while ($data = $resultado->FetchRow()) {
               $Drenajes[]=$data;
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $Drenajes;
     }

     /**
     *	GetCControlesAdicionales
     *
     *	Controles adicionales para cada paciente.
     */     
     function GetCControlesAdicionales($control)
     {
          list($dbconn) = GetDBconn();
          $query="SELECT * FROM hc_control_adicionales WHERE evolucion_id=".$control['evolucion_id'];
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado)
          {
               $this->error = "Error al consultar la tabla \"hc_control_adicionales\" con evolucion_id=".$control['evolucion_id'];
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          return $resultado->FetchNextObject($toUpper=false);
     }

     /**
     *	GetControlesAdicionales
     *
     *	Controles adicionales para cada paciente.
     */     
     function GetControlesAdicionales($evolucion_id)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $transfusiones=array();
          $query = "SELECT * FROM hc_control_adicionales WHERE evolucion_id='".$evolucion_id."'";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado=$this->Verifica_Conexion($query,$dbconn);
          if (!$resultado) {
               $this->error = "Error, no se encuentra el registro en \"hc_control_adicionales\" con la evolucion_id \"$evolucion_id\"";
               $this->mensajeDeError = $query."<br>".$dbconn->ErrorMsg();
               return false;
          }
          while ($data = $resultado->FetchRow()) {
               $ctrlAdicionales[] = $data;
          }
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          return $ctrlAdicionales;
     }
     

     /**
     * Calcula los días que lleva hospitalizada una persona, basandose en la fecha de ingreso.
     *
     * @param timestamp fecha de ingreso del paciente
     * @return integer
     * @access Public
     */
     function GetDiasHospitalizacion($fecha_ingreso)
     {
          if(empty($fecha_ingreso)) return null;
     
          $date1 = date('Y-m-d H:i:s');
     
          $fecha_in=explode(".",$fecha_ingreso);
          $date2=$fecha_in[0];
     
          $s = strtotime($date1)-strtotime($date2);
          $d = intval($s/86400);
          $s -= $d*86400;
          $h = intval($s/3600);
          $s -= $h*3600;
          $m = intval($s/60);
          $s -= $m*60;
     
          if($d>0)
          {
               $dif= "$d  dias ";
          }
          else
          {
               $dif = "$h:$m horas ";
          }
          return $dif;
     }

     /**
	* Separa la fecha del formato timestamp
	* @access private
	* @return string
	* @param date fecha
	*/
	 function FechaStamp($fecha)
	 {
          if($fecha){
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
          }
	}


	/**
	* Separa la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/
	function HoraStamp($hora)
	{
          $hor = strtok ($hora," ");
          for($l=0;$l<4;$l++)
          {
               $time[$l]=$hor;
               $hor = strtok (":");
          }

          $x = explode (".",$time[3]);
          return  $time[1].":".$time[2].":".$x[0];
	}

}//end of class

?>
