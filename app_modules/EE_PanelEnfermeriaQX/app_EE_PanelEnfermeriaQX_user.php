 <?
 
 
 /**
 * $Id: app_EE_PanelEnfermeriaQX_user.php,v 1.1 2005/12/30 23:35:36 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria de Cirugia para la atencion del paciente que va a entrar o llega de la cirugia
 */



/**
*		class app_EE_PanelEnfermeriaQX_user
*
*		Clase que maneja todas los metodos que llaman a las vistas relacionadas a la estación de Enfermería de Cirugia
*		ubicadas en la clase hija html
*		ubicacion => app_modules/EE_PanelEnfermeriaQX/app_EE_PanelEnfermeriaQX_user.php
*		fecha creación => 10/26/2005 10:35 am
*
*		@Author => Lorena Aragón G.
*		@version =>
*		@package SIIS
*/
class app_EE_PanelEnfermeriaQX_user extends classModulo
{
	var $frmError = array();


	/**
	*		app_EE_PanelEnfermeriaQX_user()
	*
	*		constructor
	*
	*		@Author Lorena Aragón G.
	*		@access Public
	*		@return bool
	*/
	function app_EE_PanelEnfermeriaQX_user()//Constructor padre
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
    $url[1]='EE_PanelEnfermeriaQX';
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
    $_SESSION['EE_PANEL_ENFERMERIA_QX']['Empresa']=$_REQUEST['datos_query']['empresa_id'];
    $_SESSION['EE_PANEL_ENFERMERIA_QX']['NombreEmp']=$_REQUEST['datos_query']['descripcion1'];
    $_SESSION['EE_PANEL_ENFERMERIA_QX']['CentroUtili']=$_REQUEST['datos_query']['centro_utilidad'];
		$_SESSION['EE_PANEL_ENFERMERIA_QX']['NombreCU']=$_REQUEST['datos_query']['descripcion2'];
    $_SESSION['EE_PANEL_ENFERMERIA_QX']['UnidadFunc']=$_REQUEST['datos_query']['unidad_funcional'];
    $_SESSION['EE_PANEL_ENFERMERIA_QX']['NombreFunc']=$_REQUEST['datos_query']['descripcion3'];
    $_SESSION['EE_PANEL_ENFERMERIA_QX']['Departamento']=$_REQUEST['datos_query']['departamento'];
    $_SESSION['EE_PANEL_ENFERMERIA_QX']['NombreDpto']=$_REQUEST['datos_query']['descripcion4'];
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
    $query = "SELECT x.*,b.evolucion_id
							FROM
							(SELECT a.numero_registro,a.numerodecuenta,i.tipo_id_paciente,i.paciente_id,
              c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombrepac,
              a.fecha_ingreso,i.ingreso,d.programacion_id,e.quirofano_id,f.descripcion as nom_quirofano,e.hora_inicio,e.hora_fin,ter.nombre_tercero as profesional
              FROM estacion_enfermeria_qx_pacientes_ingresados a,cuentas b,ingresos i,
              pacientes c,qx_programaciones d
							LEFT JOIN terceros ter ON (d.tipo_id_cirujano=ter.tipo_id_tercero AND d.cirujano_id=ter.tercero_id),
							qx_quirofanos_programacion e,qx_quirofanos f

              WHERE a.departamento='".$_SESSION['EE_PANEL_ENFERMERIA_QX']['Departamento']."' AND a.sw_estado='1' AND
              a.numerodecuenta=b.numerodecuenta AND b.ingreso=i.ingreso AND
              i.tipo_id_paciente=c.tipo_id_paciente AND i.paciente_id=c.paciente_id AND
              a.programacion_id=d.programacion_id AND d.estado='1'AND
              d.programacion_id=e.programacion_id AND e.qx_tipo_reserva_quirofano_id='3' AND
              f.quirofano=e.quirofano_id) x
							LEFT JOIN hc_evoluciones b ON ( b.ingreso = x.ingreso
																						AND b.usuario_id = ".UserGetUID()."
																						AND b.estado = '1' )";
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
  *		PacientesIngresadosEstacionQX
  *
  *		Esta función Busca los pacientes ingresados a la estacion de cirugia.
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return bool
  */
	
	function DefinicionTipoModuloProfesional(){
	
		list($dbconn) = GetDBconn();
    $query = "SELECT DISTINCT b.tipo_profesional,e.sw_anestesiologo,e.sw_pediatra,e.sw_circulante,e.sw_instrumentista,e.sw_cirujano 										 											
							FROM profesionales_departamentos a,profesionales b,profesionales_usuarios c,profesionales_especialidades d,especialidades e
              WHERE a.departamento='".$_SESSION['EE_PANEL_ENFERMERIA_QX']['Departamento']."' AND 
							a.tipo_id_tercero=b.tipo_id_tercero AND a.tercero_id=b.tercero_id AND 
							profesional_activo(a.tipo_id_tercero,a.tercero_id,'".$_SESSION['EE_PANEL_ENFERMERIA_QX']['Departamento']."')='1' AND 							
							a.tipo_id_tercero=c.tipo_tercero_id AND a.tercero_id=c.tercero_id AND c.usuario_id='".UserGetUID()."' AND 
							a.tipo_id_tercero=d.tipo_id_tercero AND a.tercero_id=d.tercero_id AND d.especialidad=e.especialidad";						              
    $result=$dbconn->execute($query);
    if($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){        
        $var=$result->GetRowAssoc($ToUpper = false);          
      }
			if($var['sw_anestesiologo']==1){
				$query = "SELECT hc_modulo_anestesiologo as modulo_nom FROM estacion_enfermeria_qx_departamentos WHERE departamento='".$_SESSION['EE_PANEL_ENFERMERIA_QX']['Departamento']."'";
			}elseif($var['sw_cirujano']==1){
				$query = "SELECT hc_modulo_cirujano as modulo_nom FROM estacion_enfermeria_qx_departamentos WHERE departamento='".$_SESSION['EE_PANEL_ENFERMERIA_QX']['Departamento']."'";
			}elseif($var['hc_modulo_circulante']==1){
				$query = "SELECT hc_modulo_circulante as modulo_nom FROM estacion_enfermeria_qx_departamentos WHERE departamento='".$_SESSION['EE_PANEL_ENFERMERIA_QX']['Departamento']."'";
			}elseif($var['sw_instrumentista']==1){
				$query = "SELECT hc_modulo_instrumentador as modulo_nom FROM estacion_enfermeria_qx_departamentos WHERE departamento='".$_SESSION['EE_PANEL_ENFERMERIA_QX']['Departamento']."'";	
			}
			$result=$dbconn->execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				if($result->RecordCount()>0){        
					$vars=$result->GetRowAssoc($ToUpper = false);          
				}
				if(!$vars['modulo_nom']){
					if($var['tipo_profesional']=='1' OR $var['tipo_profesional']=='2'){
						$query = "SELECT hc_modulo_cirujano as modulo_nom FROM estacion_enfermeria_qx_departamentos WHERE departamento='".$_SESSION['EE_PANEL_ENFERMERIA_QX']['Departamento']."'";
						$result=$dbconn->execute($query);
						if($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}else{
							if($result->RecordCount()>0){        
								$vars=$result->GetRowAssoc($ToUpper = false);          
							}
							if(!$vars['modulo_nom']){
								$query = "SELECT hc_modulo_enfermeria as modulo_nom FROM estacion_enfermeria_qx_departamentos WHERE departamento='".$_SESSION['EE_PANEL_ENFERMERIA_QX']['Departamento']."'";
								$result=$dbconn->execute($query);
								if($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}else{
									if($result->RecordCount()>0){        
										$vars=$result->GetRowAssoc($ToUpper = false);          
									}
								}	
							}
						}	
					}else{
						$query = "SELECT hc_modulo_enfermeria as modulo_nom FROM estacion_enfermeria_qx_departamentos WHERE departamento='".$_SESSION['EE_PANEL_ENFERMERIA_QX']['Departamento']."'";
						$result=$dbconn->execute($query);
						if($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}else{
							if($result->RecordCount()>0){        
								$vars=$result->GetRowAssoc($ToUpper = false);          
							}
						}	
					}
				}
			}	
    }
    return $vars['modulo_nom'];
	}	
	

}//fin class
?>
