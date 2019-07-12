 <?
 
 
 /**
 * $Id: app_EstacionEnfermeria_QX_user.php,v 1.26 2006/09/19 19:58:08 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria de Cirugia para la atencion del paciente que va a entrar o llega de la cirugia
 */



/**
*		class app_EstacionEnfermeria_QX_user
*
*		Clase que maneja todas los metodos que llaman a las vistas relacionadas a la estación de Enfermería de Cirugia
*		ubicadas en la clase hija html
*		ubicacion => app_modules/EstacionEnfermeria_QX/app_EstacionEnfermeria_QX_user.php
*		fecha creación => 10/26/2005 10:35 am
*
*		@Author => Lorena Aragón G.
*		@version =>
*		@package SIIS
*/
class app_EstacionEnfermeria_QX_user extends classModulo
{
	var $frmError = array();


	/**
	*		app_EstacionEnfermeria_QX_user()
	*
	*		constructor
	*
	*		@Author Lorena Aragón G.
	*		@access Public
	*		@return bool
	*/
	function app_EstacionEnfermeria_QX_user()//Constructor padre
	{
		return true;
	}

	/**
	*		main
	*
	*		Esta función permite seleccionar todas los departamentos que agrupa la estacion
	*		organizadas por su empresa, departamento
	*		a la cual pertenecen.
	*
	*		@Author Lorena Aragón G.
	*		@access Public
	*		@return bool
	*/

	function main(){
		if(!$this->FrmLogueoEstacionQX()){
			$this->error = "No se puede cargar la vista";
			$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmLogueoEstacionQX\"";
			return false;
		}
		return true;
	}//FIN main

	/**
	*		GetLogueoEstacion
	*
	*		Esta función obtiene los departamentos asociados a la Estacion de Cirugia.
	*
	*		@Lorena Aragón G.
	*		@access Public
	*		@return bool
	*/
	function GetLogueoEstacion(){

	 $query =  "SELECT e.razon_social as descripcion1,
											cu.descripcion as descripcion2,
											uf.descripcion as descripcion3,
											c.descripcion as descripcion4,
											c.empresa_id,
											c.centro_utilidad,
											c.unidad_funcional,
											c.departamento
							FROM  userpermisos_estacion_enfermeria_qx a,
										estacion_enfermeria_qx_departamentos b,
										departamentos c,
										empresas e,
										centros_utilidad cu,
										unidades_funcionales uf
							WHERE a.usuario_id=".UserGetUID()." AND
										a.departamento=b.departamento AND
										c.departamento=b.departamento AND
										e.empresa_id=c.empresa_id AND
										cu.empresa_id=c.empresa_id AND
										cu.centro_utilidad=c.centro_utilidad AND
										uf.empresa_id=c.empresa_id AND
										uf.centro_utilidad=c.centro_utilidad AND
										uf.unidad_funcional=c.unidad_funcional
							ORDER BY c.empresa_id, c.centro_utilidad, c.unidad_funcional, c.departamento";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if (!$result) {
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}

		while ($data = $result->FetchRow()){

			$vectorDptos[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']][$data['descripcion4']]=$data;
		}

		$mtz[0]="EMPRESA";
    $mtz[1]="CENTRO UTILIDAD";
    $mtz[2]="UNIDAD FUNCIONAL";
		$mtz[3]="DEPARTAMENTO";
    $url[0]='app';
    $url[1]='EstacionEnfermeria_QX';
    $url[2]='user';
    $url[3]='LlamaMenu';
    $url[4]='datos_query';
		$Datos[0]=$mtz;
		$Datos[1]=$vectorDptos;
		$Datos[2]=$url;
		return $Datos;
	}

  /**
  *		LlamaMenu
  *
  *		Esta función lista el menu de la estacion de enfermeria de Cirugia.
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@param array datos de la estacion
  *		@return bool
  */
  function LlamaMenu(){
    $_SESSION['ESTACION_ENFERMERIA_QX']['Empresa']=$_REQUEST['datos_query']['empresa_id'];
    $_SESSION['ESTACION_ENFERMERIA_QX']['NombreEmp']=$_REQUEST['datos_query']['descripcion1'];
    $_SESSION['ESTACION_ENFERMERIA_QX']['CentroUtili']=$_REQUEST['datos_query']['centro_utilidad'];
		$_SESSION['ESTACION_ENFERMERIA_QX']['NombreCU']=$_REQUEST['datos_query']['descripcion2'];
    $_SESSION['ESTACION_ENFERMERIA_QX']['UnidadFunc']=$_REQUEST['datos_query']['unidad_funcional'];
    $_SESSION['ESTACION_ENFERMERIA_QX']['NombreFunc']=$_REQUEST['datos_query']['descripcion3'];
    $_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']=$_REQUEST['datos_query']['departamento'];
    $_SESSION['ESTACION_ENFERMERIA_QX']['NombreDpto']=$_REQUEST['datos_query']['descripcion4'];
    if(!$this->Menu()){
      $this->error = "No se puede cargar la vista";
      $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"Menu\"";
      return false;
    }
    return true;
  }
  
  function LlamaMenuSinModify(){    
    if(!$this->Menu()){
      $this->error = "No se puede cargar la vista";
      $this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"Menu\"";
      return false;
    }
    return true;
  }

  /**
  *		PacientesIngresadosEstacionQX
  *
  *		Esta función Busca los pacientes ingresados a la estacion de cirugia.
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return bool
  */

  function PacientesIngresadosEstacionQX(){
     list($dbconn) = GetDBconn();    
     $query = "SELECT a.*,ter.nombre_tercero as profesional
               FROM (SELECT a.numero_registro,a.numerodecuenta,i.tipo_id_paciente,i.paciente_id,
                    c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombrepac,
                    a.fecha_ingreso,i.ingreso,d.programacion_id,e.quirofano_id,f.descripcion as nom_quirofano,
                    e.hora_inicio,e.hora_fin,d.tipo_id_cirujano,d.cirujano_id
                    FROM estacion_enfermeria_qx_pacientes_ingresados a,cuentas b,ingresos i,
                    pacientes c,qx_programaciones d,qx_quirofanos_programacion e,qx_quirofanos f
  
                    WHERE a.departamento='".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."' AND a.sw_estado='1' AND
                    a.numerodecuenta=b.numerodecuenta AND b.ingreso=i.ingreso AND
                    i.tipo_id_paciente=c.tipo_id_paciente AND i.paciente_id=c.paciente_id AND
                    a.programacion_id=d.programacion_id AND d.estado='1'AND
                    d.programacion_id=e.programacion_id AND e.qx_tipo_reserva_quirofano_id='3' AND
                    f.quirofano=e.quirofano_id) as a
                LEFT JOIN terceros ter ON (a.tipo_id_cirujano=ter.tipo_id_tercero AND a.cirujano_id=ter.tercero_id)";
    $result=$dbconn->execute($query);
    if($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $var[]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
        }
      }
    }
    return $var;
  }

  /**
  *		PacientesPendientesXIngresar
  *
  *		Esta función Busca los pacientes que estan por ingresar.
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return bool
  */

  function PacientesPendientesXIngresar(){
    list($dbconn) = GetDBconn();
    $query = "SELECT DISTINCT c.tipo_id_paciente,c.paciente_id,
              d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_apellido as nombrepac,
              a.fecha_registro,a.programacion_id,b.numerodecuenta,c.ingreso,a.fecha_ingreso_estacion,a.numero_registro,
              e.qx_quirofano_programacion_id,f.programacion_id as procedimientos
              FROM estacion_enfermeria_qx_pendientes_ingresar a
              LEFT JOIN qx_quirofanos_programacion e ON(a.programacion_id=e.programacion_id)
              LEFT JOIN qx_procedimientos_programacion f ON(a.programacion_id=f.programacion_id),
              cuentas b,ingresos c,pacientes d
              WHERE a.numerodecuenta=b.numerodecuenta 
              AND b.ingreso=c.ingreso 
              AND c.tipo_id_paciente=d.tipo_id_paciente 
              AND c.paciente_id=d.paciente_id 
              AND (b.estado='1' OR b.estado='2') 
              AND c.estado='1' 
              AND a.departamento='".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."'
              ORDER BY procedimientos,a.programacion_id ASC";
    $result=$dbconn->execute($query);
    if($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $var[]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
        }
      }
    }
    return $var;
  }
  
  

  

  /**
  *		ProgramacionesQXDepartamento
  *
  *		Esta función Busca las programaciones del departamento
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return bool
  */

  function ProgramacionesQXDepartamento(){

    $query = "SELECT a.*,ter.nombre_tercero as cirujano,f.nombre_tercero as anestesiologo,
              g.nombre_tercero as instrumentador,h.nombre_tercero as circulante,i.nombre_tercero as ayudante 
              
              FROM
                (SELECT a.programacion_id,a.quirofano_id,b.descripcion,c.tipo_id_paciente,c.paciente_id,
                d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_apellido as nombre_pac,
                c.tipo_id_cirujano,c.cirujano_id,b.abreviatura,a.hora_inicio,a.hora_fin
                
                FROM qx_quirofanos_programacion a, qx_quirofanos b,qx_programaciones c,pacientes d
                
                WHERE a.quirofano_id=b.quirofano AND
                a.departamento='".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."' AND a.departamento=b.departamento AND
                a.qx_tipo_reserva_quirofano_id='3' AND a.programacion_id=c.programacion_id AND c.estado='1' AND
                c.tipo_id_paciente=d.tipo_id_paciente AND c.paciente_id=d.paciente_id AND
                date(a.hora_inicio) = '".date("Y-m-d")."') as a
                
              LEFT JOIN terceros ter ON(a.tipo_id_cirujano=ter.tipo_id_tercero AND a.cirujano_id=ter.tercero_id)
              LEFT JOIN qx_anestesiologo_programacion e ON(a.programacion_id=e.programacion_id)
              LEFT JOIN terceros f ON(e.tipo_id_tercero=f.tipo_id_tercero AND e.tercero_id=f.tercero_id)
              LEFT JOIN terceros g ON(e.tipo_id_instrumentista=g.tipo_id_tercero AND e.instrumentista_id=g.tercero_id)
              LEFT JOIN terceros h ON(e.tipo_id_circulante=h.tipo_id_tercero AND e.circulante_id=h.tercero_id)
              LEFT JOIN terceros i ON(e.tipo_id_ayudante=i.tipo_id_tercero AND e.ayudante_id=i.tercero_id)
              
              ORDER BY a.quirofano_id,a.hora_inicio";
    list($dbconn) = GetDBconn();
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
        while($data = $result->FetchRow()){
				  $datos[0][$data['quirofano_id']][$data['programacion_id']]=$data;
          $datos[1][$data['quirofano_id']]=$data['abreviatura'];
			  }
      }
    }
    return $datos;
  }

  /**
  *		LlamaFormaListadoProgramaciones
  *
  *		Esta función Llama la forma que lista las programaciones por dia del departamento
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return bool
  */

  function LlamaFormaListadoProgramaciones(){
    if($_REQUEST['LlenarQuirofanos']){
      $SalasCirugia=$this->SeleccionQuirofanosDpto();
      for($l=0;$l<sizeof($SalasCirugia);$l++){
        $vec[$SalasCirugia[$l]['quirofano']]=1;
      }
      $_REQUEST['FiltroQuirofanos']=$vec;
    }
    $this->FormaListadoProgramaciones($_REQUEST['FiltroProfesionales'],$_REQUEST['FiltroQuirofanos']);
    return true;
  }

  /**
  *		SeleccionQuirofanosDpto
  *
  *		Funcion trae de la base de datos los deferentes quirofanos que pertenecen al departamento en donde el usuario se encuentra logueado
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return bool
  */

	function SeleccionQuirofanosDpto($FiltroQuirofanos){

		list($dbconn) = GetDBconn();
		$query = "SELECT a.quirofano,a.descripcion,a.abreviatura
    FROM qx_quirofanos a
    WHERE a.departamento='".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."' AND estado='1' AND a.sw_programacion='1'";
    if(sizeof($FiltroQuirofanos)>0){
      $i=1;
      $query.=" AND (";
      foreach($FiltroQuirofanos as $quiro=>$indice){
        if($i==sizeof($FiltroQuirofanos)){
          $query.=" a.quirofano='".$quiro."'";
        }else{
          $query.=" a.quirofano='".$quiro."' OR ";
        }
        $i++;
      }
      $query.=" )";
    }		
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
        while (!$result->EOF) {
				  $vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
		}
		$result->Close();
 		return $vars;
	}

  /**
  *   ComprobarExisReserva
  *
  *   Funcion que comprueba si esta fecha que llega por paramentro se encuentra reservada por otra programacion
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return bool

  */
	function ComprobarExisReserva($Quiro,$SumaHora,$rango,$plan,$empresa,$IdTercero,$TerceroId){
    list($dbconn) = GetDBconn();
		$time=date("H:i:s",mktime(0,(0+$rango),0,date('m'),date('d'),date('Y')));
		$query ="SELECT revisar_rango_reserva_quirofano('$Quiro','$SumaHora','$time','$plan','$empresa', '$IdTercero','$TerceroId')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $respuesta=$result->fields[0];
      if($respuesta=='t'){
        $vars=1;
			}else{
        $vars=0;
			}
		}
		$result->Close();
 		return $vars;
	}

  /**
* Funcion que comprueba si esta fecha que llega por paramentro se encuentra reservada por otra programacion
* @return array
* @param integer numero que identifica al quirofano
* @param date fecha que se va a evasluar en la reserva
* @param time rango de tiempo minimo para realizar una reserva
*/
	function consultaProgramacion($Quiro,$SumaHora,$rango){
    list($dbconn) = GetDBconn();
		$time=date("H:i:s",mktime(0,(0+$rango),0,date('m'),date('d'),date('Y')));
		$query = "SELECT consulta_programacion('$Quiro','$SumaHora','$time')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $respuesta=$result->fields[0];
      if($respuesta=='0'){
        $vars=0;
			}else{
        $vars=$respuesta;
        $datos=$this->DatosProgramacionQX($vars);
        $vector[0]=$datos[0];
        $vector[1]=$datos[1][0];
      }
		}
		$result->Close();
 		return $vector;
	}

  /**
  *		consultaProgramacionCliente
  *
  * Funcion que comprueba si esta fecha que llega por paramentro se encuentra reservada por otra un cliente
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */


  function consultaProgramacionCliente($Quiro,$SumaHora,$rango){
    list($dbconn) = GetDBconn();
		$time=date("H:i:s",mktime(0,(0+$rango),0,date('m'),date('d'),date('Y')));
    $query = "SELECT a.qx_quirofano_programacion_id,ter.nombre_tercero
    FROM  qx_quirofanos_programacion a,qx_reservas_quirofanos_clientes b,terceros ter
    WHERE a.qx_quirofano_programacion_id=b.qx_quirofano_programacion_id AND a.quirofano_id='$Quiro' AND
    a.qx_tipo_reserva_quirofano_id<>'0' AND '$SumaHora' >= a.hora_inicio AND '$SumaHora' < a.hora_fin + '$rango' AND
    ter.tipo_id_tercero=b.tipo_id_tercero AND ter.tercero_id=b.tercero_id";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        $vector=$result->GetRowAssoc($toUpper=false);
      }
    }
    return $vector;
  }

  /**
  *		consultaProgramacionCliente
  *
  * Funcion que comprueba si esta fecha que llega por paramentro se encuentra reservada por otra un plan
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */


  function consultaProgramacionPlan($Quiro,$SumaHora,$rango){
    list($dbconn) = GetDBconn();
		$time=date("H:i:s",mktime(0,(0+$rango),0,date('m'),date('d'),date('Y')));
    $query = "SELECT a.qx_quirofano_programacion_id,pl.plan_descripcion
    FROM  qx_quirofanos_programacion a,qx_reservas_quirofanos_planes b,planes pl
    WHERE a.qx_quirofano_programacion_id=b.qx_quirofano_programacion_id AND a.quirofano_id='$Quiro' AND
    a.qx_tipo_reserva_quirofano_id<>'0' AND '$SumaHora' >= a.hora_inicio AND '$SumaHora' < a.hora_fin + '$rango' AND
    pl.plan_id=b.plan_id";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        $vector=$result->GetRowAssoc($toUpper=false);
      }
    }
    return $vector;
  }


  /**
  *		ModificacionesProgramaciones
  *
  *		Esta función Llama la forma que lista las programaciones por dia del departamento
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

  function ModificacionesProgramaciones(){
    if($_REQUEST['FILTRAR']){
      $this->FormaListadoProgramaciones($_REQUEST['FiltroProfesionales'],$_REQUEST['QuiroSelect']);
      return true;
    }
  }

  /**
  *		EditarProfesionalesProgramacion
  *
  *		Esta función Llama la forma de editar los profesionales de la estacion
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

  function EditarProfesionalesProgramacion(){
    $this->FrmEditarProfesionalesProgramacion($_REQUEST['programacionId']);
    return true;
  }

  /**
  *		profesionalesEspecialistaAnestecistas
  *
  *		Funcion que busca en los profesionales especialistas anestesiologos existentes en la base de datos
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

	function profesionalesEspecialistaAnestecistas(){

		list($dbconn) = GetDBconn();
		$query = "SELECT x.tercero_id,c.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
    WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND
    y.departamento='".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."' AND z.especialidad=l.especialidad AND z.sw_anestesiologo='1' AND
    x.tercero_id=l.tercero_id AND x.tipo_id_tercero=l.tipo_id_tercero  AND x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND
    profesional_activo(c.tipo_id_tercero,c.tercero_id,'".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."')='1'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

 /**
  *		profesionalesAyudantes
  *
  *		Funcion que busca los profesionales Ayudantes existentes en la base de datos
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

	function profesionalesAyudantes(){

		list($dbconn) = GetDBconn();
		$query = "SELECT x.tercero_id,z.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,terceros z
    WHERE (x.tipo_profesional='1' OR x.tipo_profesional='2') AND x.tipo_id_tercero=y.tipo_id_tercero AND
    x.tercero_id=y.tercero_id AND y.departamento='".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."' AND
    x.tercero_id=z.tercero_id AND x.tipo_id_tercero=z.tipo_id_tercero AND
    profesional_activo(z.tipo_id_tercero,z.tercero_id,'".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."')='1'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

 /**
  *		profesionalesEspecialistaInstrumentistas
  *
  *		Funcion que busca en los profesionales especialistas anestesiologos existentes en la base de datos
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

	function profesionalesEspecialistaInstrumentistas(){

		list($dbconn) = GetDBconn();
		$query = "SELECT  x.tercero_id,c.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
    WHERE x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND
    y.departamento='".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."' AND z.especialidad=l.especialidad AND
    z.sw_instrumentista='1' AND x.tercero_id=l.tercero_id AND x.tipo_id_tercero=l.tipo_id_tercero  AND
    x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND
    profesional_activo(c.tipo_id_tercero,c.tercero_id,'".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."')='1'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

  /**
  *		profesionalesEspecialistaCiculantes
  *
  *		Funcion que busca en los profesionales especialistas anestesiologos existentes en la base de datos
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

	function profesionalesEspecialistaCiculantes(){

		list($dbconn) = GetDBconn();
		$query = "SELECT  x.tercero_id,c.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
     WHERE x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND y.departamento='".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."' AND
     z.especialidad=l.especialidad AND z.sw_circulante='1' AND x.tercero_id=l.tercero_id AND x.tipo_id_tercero=l.tipo_id_tercero  AND
     x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND profesional_activo(c.tipo_id_tercero,c.tercero_id,'".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."')='1'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

  /**
  *		DatosProgramacionQX
  *
  *		Funcion que busca en los los dtos de la Programacion
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

  function DatosProgramacionQX($programacion){

    list($dbconn) = GetDBconn();
    $query = "SELECT a.programacion_id,ter.nombre_tercero as cirujano,
    a.tipo_id_paciente,a.paciente_id,b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre_pac,
    f.nombre_tercero as anestesiologo,f.tipo_id_tercero as tipo_id_tercero_aneste,f.tercero_id as tercero_id_aneste,
    g.nombre_tercero as instrumentador,g.tipo_id_tercero as tipo_id_tercero_instru,g.tercero_id as tercero_id_instru,
    h.nombre_tercero as circulante,h.tipo_id_tercero as tipo_id_tercero_circu ,h.tercero_id as tercero_id_circu,
    i.nombre_tercero as ayudante,i.tipo_id_tercero as tipo_id_tercero_ayud,i.tercero_id as tercero_id_ayud,
    c.hora_inicio,c.hora_fin,d.descripcion as quirofano,pl.plan_descripcion,terpl.nombre_tercero as tercero_plan,diag.diagnostico_nombre,
    c.qx_quirofano_programacion_id
    FROM qx_programaciones a
    LEFT JOIN terceros ter ON (a.cirujano_id=ter.tercero_id AND a.tipo_id_cirujano=ter.tipo_id_tercero)
    LEFT JOIN qx_anestesiologo_programacion e ON(a.programacion_id=e.programacion_id)
    LEFT JOIN terceros f ON(e.tipo_id_tercero=f.tipo_id_tercero AND e.tercero_id=f.tercero_id)
    LEFT JOIN terceros g ON(e.tipo_id_instrumentista=g.tipo_id_tercero AND e.instrumentista_id=g.tercero_id)
    LEFT JOIN terceros h ON(e.tipo_id_circulante=h.tipo_id_tercero AND e.circulante_id=h.tercero_id)
    LEFT JOIN terceros i ON(e.tipo_id_ayudante=i.tipo_id_tercero AND e.ayudante_id=i.tercero_id)
    LEFT JOIN planes pl ON(a.plan_id=pl.plan_id)
    LEFT JOIN terceros terpl ON(pl.tipo_tercero_id=terpl.tipo_id_tercero AND pl.tercero_id=terpl.tercero_id)
    LEFT JOIN diagnosticos diag ON(a.diagnostico_id=diag.diagnostico_id),
    pacientes b,qx_quirofanos_programacion c,qx_quirofanos d
    WHERE a.programacion_id='".$programacion."' AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND
    a.programacion_id=c.programacion_id AND c.quirofano_id=d.quirofano AND c.qx_tipo_reserva_quirofano_id='3'";

    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        $vector[0]=$result->GetRowAssoc($toUpper=false);
        $query = "SELECT a.procedimiento_qx,b.descripcion,a.tipo_id_cirujano||' '||a.cirujano_id as cirujano_id,ter.nombre_tercero as cirujano,a.observaciones
        FROM qx_procedimientos_programacion a
        LEFT JOIN terceros ter ON (ter.tipo_id_tercero=a.tipo_id_cirujano AND ter.tercero_id=a.cirujano_id),
        cups b
        WHERE a.programacion_id='".$programacion."' AND a.procedimiento_qx=b.cargo
        ORDER BY a.tipo_id_cirujano,a.cirujano_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }else{
          $datos=$result->RecordCount();
          if($datos){
            while(!$result->EOF){
              $vector[1][]=$result->GetRowAssoc($toUpper=false);
              $result->MoveNext();
            }
          }
        }
      }
    }
    return $vector;
  }

  /**
  *		ModificacionesProfesionalesProgramaciones
  *
  *		Funcion que modifica en la base de datos los profesionales asignados a la programacion
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */


  function ModificacionesProfesionalesProgramaciones(){

    list($dbconn) = GetDBconn();
    if($_REQUEST['anestesiologo']!=-1){
      (list($tipoIdAnestesiologo,$AnestesiologoId)=explode(',',$_REQUEST['anestesiologo']));
      $tipoIdAnestesiologo="'".$tipoIdAnestesiologo."'";
      $AnestesiologoId="'".$AnestesiologoId."'";
    }else{
      $tipoIdAnestesiologo='NULL';
      $AnestesiologoId='NULL';
    }
    if($_REQUEST['ayudante']!=-1){
      (list($tipoIdAyudante,$Ayudante)=explode(',',$_REQUEST['ayudante']));
      $tipoIdAyudante="'".$tipoIdAyudante."'";
      $Ayudante="'".$Ayudante."'";
    }else{
      $tipoIdAyudante='NULL';
      $Ayudante='NULL';
    }
    if($_REQUEST['instrumentador']!=-1){
      (list($tipoIdInstrumentador,$Instrumentador)=explode(',',$_REQUEST['instrumentador']));
      $tipoIdInstrumentador="'".$tipoIdInstrumentador."'";
      $Instrumentador="'".$Instrumentador."'";
    }else{
      $tipoIdInstrumentador='NULL';
      $Instrumentador='NULL';
    }
    if($_REQUEST['circulante']!=-1){
      (list($tipoIdCirculante,$Circulante)=explode(',',$_REQUEST['circulante']));
      $tipoIdCirculante="'".$tipoIdCirculante."'";
      $Circulante="'".$Circulante."'";
    }else{
      $tipoIdCirculante='NULL';
      $Circulante='NULL';
    }

    $query = "UPDATE qx_anestesiologo_programacion
    SET tipo_id_tercero=$tipoIdAnestesiologo,tercero_id=$AnestesiologoId,
    tipo_id_ayudante=$tipoIdAyudante,ayudante_id=$Ayudante,
    tipo_id_instrumentista=$tipoIdInstrumentador,instrumentista_id=$Instrumentador,
    tipo_id_circulante=$tipoIdCirculante,circulante_id=$Circulante
    WHERE programacion_id='".$_REQUEST['programacionId']."'";
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    $this->FrmEditarProfesionalesProgramacion($_REQUEST['programacionId']);
    return true;
  }

  /**
  *		ConsultaProgramacionQX
  *
  *		Funcion que Llama la funcion que consulta la programacion quirurgica
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

  function ConsultaProgramacionQX(){    
    $this->FrmConsultaProgramacionQX($_REQUEST['programacionId'],$_REQUEST['actionVar']);
    return true;
  }

  /**
  *		ConsultaProgramacionQX
  *
  *		Funcion que Consulta los datos de la cirugia - rips
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */
  function DatosCirugia($programacionId){

    list($dbconn) = GetDBconn();
    $query = "SELECT a.tipo_cirugia,b.descripcion as tipo,a.ambito_cirugia,c.descripcion as ambito,
    a.via_acceso,d.descripcion as via,a.finalidad_procedimiento_id,e.descripcion as finalidad
    FROM qx_datos_procedimientos_cirugias a
    LEFT JOIN qx_tipos_cirugia b ON (a.tipo_cirugia=b.tipo_cirugia_id)
    LEFT JOIN qx_ambitos_cirugias c ON(a.ambito_cirugia=c.ambito_cirugia_id)
    LEFT JOIN qx_vias_acceso d ON(a.via_acceso=d.via_acceso)
    LEFT JOIN qx_finalidades_procedimientos e ON(a.finalidad_procedimiento_id=e.finalidad_procedimiento_id)
    WHERE a.programacion_id='".$programacionId."'";
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        $vector=$result->GetRowAssoc($toUpper=false);
      }
    }
    return $vector;
  }

  /**
  *		DatosEquiposProgramacionCirugia
  *
  *		Funcion que Consulta los equipos reservados para la cirugia
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */
  function DatosEquiposProgramacionCirugia($QuirofanoProgramacionId){

    list($dbconn) = GetDBconn();
    $query = "SELECT a.equipo_id,b.descripcion as nom_equipo
    FROM qx_equipos_programacion a,qx_equipos_moviles b
    WHERE a.qx_quirofano_programacion_id='".$QuirofanoProgramacionId."' AND a.equipo_id=b.equipo_id";
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        while(!$result->EOF){
          $vector[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
    return $vector;
  }

  /**
  *		LlamaConsultaProgramacionPlan
  *
  *		Funcion que Consulta los equipos reservados para la cirugia
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

  function LlamaConsultaProgramacionPlan(){
    $this->FrmConsultaProgramacionPlan($_REQUEST['programacionId']);
    return true;
  }

  /**
  *		LlamaConsultaProgramacionPlan
  *
  *		Funcion que Consulta los equipos reservados para la cirugia
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

  function LlamaConsultaProgramacionCliente(){
    $this->FrmConsultaProgramacionCliente($_REQUEST['programacionId']);
    return true;
  }

  /**
  *		DatosProgramacionQXPlan
  *
  *		Funcion que busca en los los dtos de la Programacion
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

  function DatosProgramacionQXPlan($programacion){

    list($dbconn) = GetDBconn();
    $query = "SELECT a.hora_inicio,a.hora_fin,b.descripcion as tipo_reserva,d.plan_descripcion
    FROM qx_quirofanos_programacion a,qx_tipo_reservas_quirofanos b,qx_reservas_quirofanos_planes c,planes d
    WHERE a.qx_quirofano_programacion_id=".$programacion." AND a.qx_tipo_reserva_quirofano_id=b.qx_tipo_reserva_quirofano_id AND
    a.qx_quirofano_programacion_id=c.qx_quirofano_programacion_id AND c.plan_id=d.plan_id";

    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        $vector=$result->GetRowAssoc($toUpper=false);
      }
    }
    return $vector;
  }

  /**
  *		DatosProgramacionQXPlan
  *
  *		Funcion que busca en los los dtos de la Programacion
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

  function DatosProgramacionQXCliente($programacion){

    list($dbconn) = GetDBconn();
    $query = "SELECT a.hora_inicio,a.hora_fin,b.descripcion as tipo_reserva,d.nombre_tercero
    FROM qx_quirofanos_programacion a,qx_tipo_reservas_quirofanos b,qx_reservas_quirofanos_clientes c,terceros d
    WHERE a.qx_quirofano_programacion_id=".$programacion." AND a.qx_tipo_reserva_quirofano_id=b.qx_tipo_reserva_quirofano_id AND
    a.qx_quirofano_programacion_id=c.qx_quirofano_programacion_id AND c.tipo_id_tercero=d.tipo_id_tercero AND c.tercero_id=d.tercero_id";

    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        $vector=$result->GetRowAssoc($toUpper=false);
      }
    }
    return $vector;
  }

  /**
  *		ReservaQuirofanoProgramacion
  *
  *		Funcion que llama la forma para realizar una reserva de un quirofano
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */


  function ReservaQuirofanoProgramacion(){
    $this->FrmReservaQuirofanoProgramacion($_REQUEST['numeroRegistro']);
    return true;
  }

  /**
  *		DatosPacienteAsignarProgramQX
  *
  *		Funcion que busca los datos del paciente que se va asignar a un quirofano
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */
  function DatosPacienteAsignarProgramQX($numeroRegistro){
    list($dbconn) = GetDBconn();
    $query = "SELECT c.tipo_id_paciente,c.paciente_id,d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_apellido as identificacion
    FROM estacion_enfermeria_qx_pendientes_ingresar a,cuentas b,ingresos c,pacientes d
    WHERE a.numero_registro='".$numeroRegistro."' AND a.numerodecuenta=b.numerodecuenta AND
    b.ingreso=c.ingreso AND
    c.paciente_id=d.paciente_id AND c.tipo_id_paciente=d.tipo_id_paciente";

    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        $vector=$result->GetRowAssoc($toUpper=false);
      }
    }
    return $vector;
  }

  /**
  *		InsertarProgramacionQxPaciente
  *
  *		Funcion que inserta la programacion de un quirofano para el paciente
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

  function InsertarProgramacionQxPaciente(){
    if($_REQUEST['Reservar']){
      if($_REQUEST['seleccionReserv']){
        foreach($_REQUEST['seleccionReserv'] as $x=>$valor){
          $vectorTemp[]=$valor;
        }
      }
      if($vectorTemp<1){
        $this->frmError["MensajeError"]="No Selecciono ningun Rango de la Reserva De Cick para Continuar.";
        $this->FrmReservaQuirofanoProgramacion($_REQUEST['ingreso']);
        return true;
      }
      $valorTmp=$vectorTemp[0];
      $cadena=explode('/',$valorTmp);
      $Quirofano=$cadena[0];
      $FechaIni=$cadena[1];
      (list($Fecha,$HoraDef)=explode(' ',$FechaIni));
      (list($ano,$mes,$dia)=explode('-',$Fecha));
      $rango=$_REQUEST['rango'];
      if(sizeof($vectorTemp)==1){
        (list($Hora,$Minutos)=explode(':',$HoraDef));
        $FechaFin=date('Y-m-d H:i:s',mktime($Hora,($Minutos+($rango-1)),0,$mes,$dia,$ano));
      }else{
        $cont=sizeof($vectorTemp)-1;
        $valorTmp=$vectorTemp[$cont];
        $cadena=explode('/',$valorTmp);
        $FechaFin=$cadena[1];
        (list($Fecha,$HoraDef)=explode(' ',$FechaFin));
        (list($Hora,$Minutos)=explode(':',$HoraDef));
        $FechaFin=date('Y-m-d H:i:s',mktime($Hora,$Minutos+($rango-1),0,$mes,$dia,$ano));
      }
      list($dbconn) = GetDBconn();
      $dbconn->BeginTrans();
      $query="SELECT b.plan_id
      FROM ingresos a,cuentas b 
      WHERE a.estado='1' AND a.ingreso=b.ingreso
      AND b.estado='1' AND a.tipo_id_paciente='".$_REQUEST['tipoIdPaciente']."'
      AND a.paciente_id='".$_REQUEST['PacienteId']."'";
               
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;
      }
      if($result->fields[0]){
        $plan=$result->fields[0];
      }else{
        $plan='NULL';
      }
      $query="SELECT nextval('qx_programaciones_programacion_id_seq') 	";
      $result=$dbconn->Execute($query);
      $Programacion=$result->fields[0];
      $query="INSERT INTO qx_programaciones(programacion_id,departamento,tipo_id_cirujano,cirujano_id,tipo_id_paciente,paciente_id,
      plan_id,estado,usuario_id,fecha_registro,diagnostico_id)
      VALUES('$Programacion','".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."',NULL,NULL,'".$_REQUEST['tipoIdPaciente']."','".$_REQUEST['PacienteId']."',$plan,'1','".UserGetUID()."','".date("Y-m-d H:i:d")."',NULL)";
      $result = $dbconn->Execute($query);      
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;
      }else{
        $query="SELECT nextval('qx_quirofanos_programacion_qx_quirofano_programacion_id_seq')";
        $result=$dbconn->Execute($query);
        $QuirofanoProgramacion=$result->fields[0];
        $query="INSERT INTO qx_quirofanos_programacion(qx_quirofano_programacion_id,quirofano_id,hora_inicio,hora_fin,programacion_id,qx_tipo_reserva_quirofano_id,departamento,usuario_id,fecha_registro)
                VALUES('$QuirofanoProgramacion','$Quirofano','$FechaIni','$FechaFin','".$Programacion."','3','".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."','".UserGetUID()."','".date("Y-m-d H:i:s")."')";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }else{
          $query="UPDATE estacion_enfermeria_qx_pendientes_ingresar SET programacion_id='".$Programacion."' WHERE numero_registro='".$_REQUEST['numeroRegistro']."'";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
          }
        }
        $mensaje = "DATOS GUARDADOS SATISFACTORIAMENTE";
        $titulo = "PROGRAMACION DE CIRUGIAS";
        $boton = "";//REGRESAR
        $accion=$accion = ModuloGetURL('app','EstacionEnfermeria_QX','user','Menu');
        $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
        $dbconn->CommitTrans();
        return true;

      }
    }
  }

  /**
  *		CancelarReservaQuirofano
  *
  *		Funcion que cancela la reserva del qurofano del paciente
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

  function CancelarReservaQuirofano(){

    if($_REQUEST['origen']==1){
      $this->FrmConsultaProgramacionQX($_REQUEST['NoProgramacion']);
      return true;
    }elseif($_REQUEST['origen']==2){
      $this->FrmConsultaProgramacionPlan($_REQUEST['NoProgramacion']);
      return true;
    }else{
      $this->FrmConsultaProgramacionCliente($_REQUEST['NoProgramacion']);
      return true;
    }
    return true;
    
  }

  function AdmisionEstacionCirugia(){
    list($dbconn) = GetDBconn();
    $dbconn->BeginTrans();
    $query="SELECT a.numerodecuenta,a.estacion_origen,
    a.observaciones,a.programacion_id
    FROM estacion_enfermeria_qx_pendientes_ingresar a,cuentas b,ingresos i
    WHERE a.numero_registro='".$_REQUEST['numeroRegistro']."' AND a.sw_estado='1' AND
    a.numerodecuenta=b.numerodecuenta AND (b.estado='1' OR b.estado='2') AND
    b.ingreso=i.ingreso AND i.estado='1'";
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        $vector=$result->GetRowAssoc($toUpper=false);
      }else{
        $this->frmError["MensajeError"]="Debe Verificar, el Paciente no tiene una cuenta Activa";
        $this->Menu();
        return true;
      }
    }
		if($vector['estacion_origen']){$estacion_O="'".$vector['estacion_origen']."'";}else{$estacion_O='NULL';}
		if($vector['programacion_id']){$programacion="'".$vector['programacion_id']."'";}else{$programacion='NULL';}
    $query="INSERT INTO estacion_enfermeria_qx_pacientes_ingresados(
            numerodecuenta,departamento,
            fecha_ingreso,usuario_id,programacion_id,sw_estado,estacion_origen,observaciones)
            VALUES('".$vector['numerodecuenta']."','".$_SESSION['ESTACION_ENFERMERIA_QX']['Departamento']."','".date("Y-m-d H:i:s")."','".UserGetUID()."',
            $programacion,'1',$estacion_O,'".$vector['observaciones']."')";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
      $dbconn->RollbackTrans();
      return false;
    }else{
      $query="DELETE FROM estacion_enfermeria_qx_pendientes_ingresar WHERE numero_registro='".$_REQUEST['numeroRegistro']."'";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error al Insertar : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;
      }else{
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="Paciente Ingresado a la Estacion";
        $this->Menu();
        return true;
      }
    }
  }
  
  










}//fin class
?>
