 <?
 /**
 * $Id: app_EstacionEnfermeria_IYM_Usuarios_user.php,v 1.11 2006/06/23 16:55:58 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria para usuarios con despachos con insumos y medicamentos para los pacientes
 */



/**
*		class app_EstacionEnfermeria_IYM_Usuarios_user
*
*		Clase que maneja todas los metodos que llaman a las vistas relacionadas a la estación de Enfermería de los usuarion con despachos pendientes de Insumos y Medicamentos para los pacientes
*		ubicadas en la clase hija html
*		ubicacion => app_modules/EstacionEnfermeria_IYM_Usuarios/app_EstacionEnfermeria_IYM_Usuarios_user.php
*		fecha creación => 10/26/2005 10:35 am
*
*		@Author => Lorena Aragón G.
*		@version =>
*		@package SIIS
*/
class app_EstacionEnfermeria_IYM_Usuarios_user extends classModulo
{
	var $frmError = array();


	/**
	*		app_EstacionEnfermeria_IYM_Usuarios_user()
	*
	*		constructor
	*
	*		@Author Lorena Aragón G.
	*		@access Public
	*		@return bool
	*/
	function app_EstacionEnfermeria_IYM_Usuarios_user()//Constructor padre
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

		return true;
	}//FIN main

  /**
	*		ConsultaMyIDespachosPendientes
	*
	*		Esta función recibe variables de la estacion de enfermeria y llama a la forma  que visualiza los
  *   despachos pendientes realizados al usuario para un paciente
	*
	*		@Author Lorena Aragón G.
	*		@access Public
	*		@return bool
	*/

  function ConsultaMyIDespachosPendientes(){
    $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']=$_REQUEST['estacion'];
    $this->FomaMyIDespachosPendientes();
    return true;
  }

  /**
	*		SolicitudesPendientesIyM
	*
	*		Esta función consulta en la base de datos las solicitudes
	*
	*		@Author Lorena Aragón G.
	*		@access Public
	*		@return bool
	*/

  function SolicitudesPendientesIyM($estacion_id){
    list($dbconn) = GetDBconn();
    $query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,a.inv_solicitudes_iym_id,
    b.consecutivo,b.codigo_producto,b.cantidad,b.cantidad_ajustada,c.nombre as usuario_bodega,b.codigo_producto,d.descripcion,
    date(a.fecha_registro) as fecha,bod.descripcion as nom_bodega
    FROM inv_solicitudes_iym_responsable a,inv_solicitudes_iym_responsable_d b,system_usuarios c,inventarios_productos d,bodegas bod
    WHERE a.inv_solicitudes_iym_id=b.inv_solicitudes_iym_id AND
    a.responsable_solicitud='".UserGetUID()."' AND a.estacion_id='".$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']['estacion_id']."' AND
    b.sw_estado='1' AND  a.usuario_id=c.usuario_id AND b.codigo_producto=d.codigo_producto AND
    a.empresa_id=bod.empresa_id AND a.centro_utilidad=bod.centro_utilidad AND a.bodega=bod.bodega";
    GLOBAL $ADODB_FETCH_MODE;
    list($dbconn) = GetDBconn();
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al ejecutar la conexion";
      $this->mensajeDeError = "Ocurrió un error al intentar obtener los controles de la estación.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
      return false;
    }else{
      while($data = $result->FetchRow()){
				$vars[$data['bodega']][$data['inv_solicitudes_iym_id']][$data['consecutivo']]=$data;
        $vars2[$data['bodega']][0]=$data['empresa_id'];
        $vars2[$data['bodega']][1]=$data['centro_utilidad'];
        $vars2[$data['bodega']][3]=$data['nom_bodega'];
			}
    }
    $vector[0]=$vars;
    $vector[1]=$vars2;
    return $vector;
  }

  /**
	*		BusquedaPaciente
	*
	*		Busca los Pacientes de la Estacion para la asignacion de los medicamentos
	*
	*		@Author Lorena Aragón G.
	*		@access Public
	*		@return bool
	*/

  function BusquedaPaciente(){
    $VectorSel=$_REQUEST['Seleccion'];
    $VectorCan=$_REQUEST['Cantidad'];
    $VectorLim=$_REQUEST['Limites'];
  	if(sizeof($VectorSel)<1){
      $this->frmError["MensajeError"]="Debe Realizar la Seleccion de los Insumos y Medicamentos para el Ajuste.";
      $this->FomaMyIDespachosPendientes();
      return true;
    }
    foreach($VectorSel as $consecutivo=>$indice){
      if(empty($VectorCan[$consecutivo]) || ($VectorCan[$consecutivo] > $VectorLim[$consecutivo])){
        $this->frmError["MensajeError"]="No debe Colocar las Cantidades vacias ni el valor puede ser mayor a la cantidad despachada.";
        $this->FomaMyIDespachosPendientes();
        return true;
      }
    }
    $this->ListRevisionPorSistemas($_REQUEST['empresa_id'],$_REQUEST['centro_utilidad'],$_REQUEST['BodegaId'],$_REQUEST['nom_Bodega'],$VectorSel,$VectorCan);
    return true;
  }
/**
	*		LlamaListRevisionPorSistemas
	*
	*		Busca los Pacientes de la Estacion para la asignacion de los medicamentos
	*
	*		@Author Lorena Aragón G.
	*		@access Public
	*		@return bool
	*/
  function LlamaListRevisionPorSistemas(){
    $vector=$_REQUEST['datos_estacion'];
    $this->ListRevisionPorSistemas($vector[0],$vector[1],$vector[2],$vector[3],$vector[4],$vector[5]);
    return true;
  }

		/*****************************CONSULTAS PACIENTES ESTACION*************************/	
		
     /**
     * Metodo para obtener los pacientes internados en una estacion
     *
     * @param string $estacion_id
     * @return array
     * @access public
     */
     function GetPacientesInternados()
     {
          list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;
     
          $query = "  SELECT a.*, b.evolucion_id
                              
                         FROM
                         (
                              SELECT (SELECT verificacionpaciente_ecirugia(a.numerodecuenta)) as paciente_cirugia,
                                   a.movimiento_id,
                                   a.numerodecuenta,
                                   a.fecha_ingreso,
                                   b.pieza,
                                   a.cama,
                                   d.ingreso,
                                   d.fecha_ingreso,
                                   d.paciente_id,
                                   d.tipo_id_paciente,
                                   e.primer_nombre,
                                   e.segundo_nombre,
                                   e.primer_apellido,
                                   e.segundo_apellido,
                                   e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo,
                                   f.plan_id,
                                   f.plan_descripcion,
                                   f.tercero_id,
                                   f.tipo_tercero_id,
                                   g.nombre_tercero
     
                              FROM
                                   movimientos_habitacion a,
                                   camas b,
                                   cuentas c,
                                   ingresos d,
                                   pacientes e,
                                   planes f,
                                   terceros g
                              WHERE
                                   a.fecha_egreso IS NULL
                                   AND estacion_id = '".$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']['estacion_id']."'
                                   AND b.cama = a.cama
                                   AND c.numerodecuenta = a.numerodecuenta
                                   AND d.ingreso = a.ingreso
                                   AND e.paciente_id = d.paciente_id
                                   AND e.tipo_id_paciente = d.tipo_id_paciente
                                   AND f.plan_id = c.plan_id
                                   AND g.tercero_id = f.tercero_id
                                   AND g.tipo_id_tercero = f.tipo_tercero_id
                         ) AS a LEFT JOIN hc_evoluciones b
                                   ON (b.ingreso = a.ingreso
                                        AND b.usuario_id = ".UserGetUID()."
                                        AND b.estado = '1')
                         ORDER BY a.cama, a.pieza;";
     
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "EE_PanelEnfermeria - GetPacientesInternados";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
     
          if($resultado->EOF)
          {
               return null;
          }
     
          $filas = $resultado->GetRows();
          $resultado->Close();
          return $filas;
     
     }//fin del metodo

          
    /**
    * Metodo para obtener los pacientes en consulta de urgencias en una estacion
    *
    * @param string $estacion_id
    * @return array
    * @access public
    */
    function GetPacientesConsultaUrgencias()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $sql = "SELECT  a.*,
                        b.evolucion_id,
                        c.nivel_triage_id,
                        c.plan_id as plan_id_triage,
                        c.triage_id,
                        c.punto_triage_id,
                        c.punto_admision_id,
                        c.sw_no_atender,
                        d.descripcion as descripcion_triage,
                        e.numerodecuenta,
                        e.plan_id
                FROM
                    (
                    SELECT
                        c.paciente_id,
                        c.tipo_id_paciente,
                        c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre_completo,
                        b.ingreso,
                        b.fecha_ingreso,
                        a.estacion_id,
                        a.triage_id,
                        a.sw_estado

                    FROM
                        pacientes_urgencias a,
                        ingresos as b,
                        pacientes as c

                    WHERE
                        a.estacion_id = '".$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']['estacion_id']."'
                        AND a.sw_estado IN ('1','7')
                        AND b.ingreso = a.ingreso
                        AND b.estado = '1'
                        AND c.paciente_id = b.paciente_id
                        AND c.tipo_id_paciente = b.tipo_id_paciente
                    ) as a
                    LEFT JOIN hc_evoluciones b ON ( b.ingreso = a.ingreso
                                                    AND b.usuario_id = ".UserGetUID()."
                                                    AND b.estado = '1' )
                    LEFT JOIN triages c ON (c.triage_id = a.triage_id)
                    LEFT JOIN niveles_triages d ON (d.nivel_triage_id = c.nivel_triage_id
                                                    AND c.nivel_triage_id != 0
                                                    AND c.sw_estado != '9')
                    LEFT JOIN cuentas e ON (e.ingreso=a.ingreso AND e.estado = '1')";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "EE_PanelEnfermeria - BuscarPacientesConsulta_Urgencias";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF)
        {
            return null;
        }

        $filas = $resultado->GetRows();
        $resultado->Close();
        return $filas;
    }

	/**
	*		GetDiasHospitalizacion
	*
	*		Calcula los días que lleva hospitalizada una persona, basandose en la fecha de ingreso.
	*		Esta funcion tamben es llamada desde el modulo censo
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return integer
	*		@param tiemstamp => fecha de ingreso del paciente
	*/
	function GetDiasHospitalizacion($fecha_ingreso)
     {
		if(empty($fecha_ingreso)){
			$fecha_ingreso = '';
			$fecha_ingreso = $_REQUEST['fecha_ingreso'];
		}
          $date1=date('Y-m-d H:i:s');
          
          $fecha_in=explode(".",$fecha_ingreso);
          $fecha_ingreso=$fecha_in[0];
          $date2=$fecha_ingreso;
          
          $s = strtotime($date1)-strtotime($date2);
          $d = intval($s/86400);
          $s -= $d*86400;
          $h = intval($s/3600);
          $s -= $h*3600;
          $m = intval($s/60);
          $s -= $m*60;
          
          $dif= (($d*24)+$h).hrs." ".$m."min";
          $dif2= $d.$space.dias." ".$h.hrs." ".$m."min";
		return $dif2;
	}
     
    /**
    * Metodo para obtener los datos de un paciente ingresado
    *
    * @param string $ingreso
    * @return array
    * @access public
    */
    function GetDatosPaciente($ingreso)
    {
        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query="SELECT a.ingreso, b.historia_numero, b.historia_prefijo,c.primer_apellido,
            c.segundo_apellido, c.primer_nombre, c.segundo_nombre, sexo_id, c.fecha_nacimiento,
            c.residencia_direccion, c.residencia_telefono, c.tipo_pais_id, c.tipo_dpto_id,
            c.tipo_mpio_id, i.pais, j.departamento, h.municipio,e.tercero_id, e.tipo_tercero_id,
            g.nombre_tercero, e.plan_id, e.plan_descripcion, f.tipo_afiliado_nombre, c.paciente_id,
            c.tipo_id_paciente, a.estado, gestacion.estado as gestacion
            FROM ingresos as a, historias_clinicas as b
            left join gestacion on
            (b.paciente_id=gestacion.paciente_id and b.tipo_id_paciente=gestacion.tipo_id_paciente),
            pacientes as c
            left join tipo_mpios as h on (c.tipo_pais_id=h.tipo_pais_id and c.tipo_dpto_id=h.tipo_dpto_id and   c.tipo_mpio_id=h.tipo_mpio_id)
            left join tipo_pais as i on (c.tipo_pais_id=i.tipo_pais_id)
            left join tipo_dptos as j on (c.tipo_pais_id=j.tipo_pais_id and
            c.tipo_dpto_id=j.tipo_dpto_id),
            cuentas as d left join tipos_afiliado as f on (d.tipo_afiliado_id=f.tipo_afiliado_id),
            planes as e, terceros as g
            WHERE a.ingreso=".$ingreso." and a.tipo_id_paciente=b.tipo_id_paciente and
            a.paciente_id=b.paciente_id and a.tipo_id_paciente=c.tipo_id_paciente and
            a.paciente_id=c.paciente_id and d.ingreso=a.ingreso and d.plan_id=e.plan_id and
            e.tipo_tercero_id=g.tipo_id_tercero and e.tercero_id=g.tercero_id;";

            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                return false;
            }
            else {
                if (!$result) {
                    $this->error = "Error al tratar de realizar la consulta.<br>";
                    $this->mensajeDeError = $query;
                    return false;
                }
                $paciente = $result->GetRowAssoc($ToUpper = false);
            }
            return $paciente;
    }

    /**
    * Metodo para obtener los contactos de un paciente ingresado
    *
    * @param string $ingreso
    * @return array
    * @access public
    */
    function &GetContactosPaciente($ingreso)
    {
        if(empty($ingreso)) return null;
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $query = "SELECT
                        C.nombre_completo,
                        C.telefono,
                        C.direccion,
                        T.descripcion AS parentesco

                  FROM  hc_contactos_paciente C,
                        tipos_parentescos T

                  WHERE C.ingreso = $ingreso
                        AND T.tipo_parentesco_id = C.tipo_parentesco_id";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al ejecutar la conexion";
            $this->mensajeDeError = "Ocurrió un error al intentar seleccionar el contacto del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
            return false;
        }

        if($result->EOF) return null;
        $ContactosPaciente = $result->GetRows();
        $result->Close();
        return $ContactosPaciente;
    }
     

     function DatosConsecutivosSeleccionados($VectorSel){
          $i=1;
          $query = "SELECT b.consecutivo,b.codigo_producto,a.inv_solicitudes_iym_id,c.descripcion,d.nombre as usuario_bodega,date(a.fecha_registro) as fecha,b.cantidad
          FROM inv_solicitudes_iym_responsable a,inv_solicitudes_iym_responsable_d b,inventarios_productos c,system_usuarios d
          WHERE a.inv_solicitudes_iym_id=b.inv_solicitudes_iym_id AND b.sw_estado = '1' AND
          ( ";
          foreach($VectorSel as $consecutivo => $indice){
               if($i==sizeof($VectorSel)){
               $query.=" b.consecutivo = ".$consecutivo."";
               }else{
               $query.=" b.consecutivo = ".$consecutivo." OR ";
               }
               $i++;
          }
          $query.=" ) AND b.codigo_producto=c.codigo_producto AND a.usuario_id=d.usuario_id";
          
          
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener los controles de la estación.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }else{
               while($data = $result->FetchRow()){
                              $vars[$data['inv_solicitudes_iym_id']][$data['consecutivo']]=$data;
               }
          }
          return $vars;
     }

     function AsignarCuentaPaciente(){
          list($dbconn) = GetDBconn();
          
          if(empty($_REQUEST['seleccionIn']))
          {
               $this->frmError["MensajeError"] = "POR FAVOR, SELECCIONE EL PACIENTE AL CUAL DESEA CARGAR EL O LOS PRODUCTOS";
               $this->ListRevisionPorSistemas($_REQUEST['empresa_id'],$_REQUEST['centro_utilidad'],$_REQUEST['BodegaId'],$_REQUEST['nom_Bodega'],$_REQUEST['VectorSel'],$_REQUEST['VectorCan']);
               return true;
          }
          
          (list($ingreso,$cuenta,$plan)=explode(',',$_REQUEST['seleccionIn']));
          
          $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['CUENTA']=$cuenta;
          $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['PLAN']=$plan;
          $est=$this->ubicacionEstacion();
          $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['DEPARTAMENTO']=$est[0];
          $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['SERVICIO']=$est[1];
          
          $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['EMPRESA']=$_REQUEST['empresa_id'];
          $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['CENTRO_UTILIDAD']=$_REQUEST['centro_utilidad'];
          $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['BODEGA']=$_REQUEST['BodegaId'];
          $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['CONSECUTIVOS']=$_REQUEST['VectorSel'];
          $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['CANTIDADES']=$_REQUEST['VectorCan'];
          $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['RETORNO']['contenedor']='app';
          $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['RETORNO']['modulo']='EstacionEnfermeria_IYM_Usuarios';
          $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['RETORNO']['tipo']='user';
          $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['RETORNO']['metodo']='VerificarCargueCuenta';
          
          //$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['RETORNO']['argurmentos']=;
          if($this->ReturnMetodoExterno('app','InvBodegas','user','liquidacionIyMResponsable','')==false){
               $this->frmError["MensajeError"]=$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['Mensaje_Error'];
               unset($_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']);
               $this->ListRevisionPorSistemas($_REQUEST['empresa_id'],$_REQUEST['centro_utilidad'],$_REQUEST['BodegaId'],$_REQUEST['nom_Bodega'],$_REQUEST['VectorSel'],$_REQUEST['VectorCan']);
               return true;
          }else{
               $this->frmError["MensajeError"]=$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['Mensaje_Error'];
               unset($_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']);
               $this->FomaMyIDespachosPendientes();
               return true;
          }
     }
          
     function ubicacionEstacion(){
          $query = "SELECT est.departamento,dpto.servicio
                    FROM estaciones_enfermeria est,departamentos dpto
                    WHERE est.estacion_id='".$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']['estacion_id']."' AND
                    dpto.departamento=est.departamento
                    ";
                    list($dbconn) = GetDBconn();
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0){
                         $this->error = "Error al ejecutar la conexion";
                         $this->mensajeDeError = "Error al obtener el numero de cuenta del ingreso<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         return false;
                    }else{
               $x[0] = $result->fields[0]; //departamento
                         $x[1] = $result->fields[1]; //sevicio
                         return $x;
          }
     }

}//fin class
?>
