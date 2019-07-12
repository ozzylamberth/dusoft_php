<?php
/**
 * $Id: app_Censo_user.php,v 1.17 2007/08/17 14:39:17 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Clase que maneja todos los metodos que llaman a las vistas relacionadas al censo
 * enla clinica
 */

/**
 * Clase user del modulo Censo
 * 
 * @author    Ehudes Garcia <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.17 $
 * @package   IPSOFT-SIIS-CENSO
 */
class app_Censo_user extends classModulo
{
	var $frmError = array();
	
	/**
	 * Empresa Id
	 *
	 * @var string
	 */
	var $empresa_id;
	
	/**
	 * Razon social de la empresa
	 *
	 * @var string
	 */
	var $razon_social;
	
	/**
	 * Constructor
	 */
	function app_Censo_user()
	{
		return true;
	}//Fin Constructor
	
	/**
	 * funcion principal del modulo
	 *
	 * Permite al usuario loguearse a un centro de utilidad
	 *
	 * @Author Rosa Maria Angel
	 * @access Public
	 * @return bool
	 */
	function main()
	{
		if(!$this->FrmLogueo()){
			return false;
		}
		return true;
	}//Fin main

	/**
	 * function GetEstaciones => Obtiene todas las estaciones, utilizada en el censo
	 *
	 * @access Private
	 * @return array
	 */
	function GetEstaciones($DepartamentoId=null)
	{
		if(!empty($DepartamentoId))
		{
			$sqlFiltro1 = " D.departamento='$DepartamentoId' AND ";
		}
		else
			$sqlFiltro1 = "";
		$query = "
			SELECT 
				EE.estacion_id, 
				EE.descripcion
			FROM 
				estaciones_enfermeria EE,
				departamentos D
			WHERE 
				EE.departamento=D.departamento AND
				$sqlFiltro1
				D.empresa_id = '".$this->empresa_id."'
			ORDER BY 
				EE.descripcion;";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener las estaciones de enfermer�.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF){
			return "ShowMensaje";
		}
		else
		{
			while ($data = $result->FetchRow()) {
				$estaciones[] = $data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $estaciones;
		}
	}//function GetEstaciones

	/**
	 * function GetDepartamentos
	 *
	 * obtiene todos los departamentos de la empresa, utilizada para el censo
	 *
	 * @access Private
	 * @return array
	 */
	function GetDepartamentos()
	{
		$query = "
			SELECT DISTINCT
				D.departamento,
				D.descripcion
			FROM 
				departamentos D,
				estaciones_enfermeria EE
			WHERE
				D.departamento=EE.departamento AND
				D.empresa_id = '".$this->empresa_id."' 
			ORDER BY
				2;";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener los departamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF){
			return "ShowMensaje";
		}
		else
		{
			while ($data = $result->FetchRow()) {
				$dptos[] = $data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $dptos;
		}
	}//Fin GetDepartamentos

	/**
	 * function GetTerceros => obtiene todos los terceros de la empresa, utilizada para el censo
	 *
	 * Obtiene solo aquellos terceros que tenfan cuenta activa
	 *
	 * @access Private
	 *  @return array
	 */
	function GetTerceros()
	{
		$query = "
			SELECT DISTINCT 
				T.tercero_id,
				T.tipo_id_tercero,
				T.nombre_tercero
			FROM
				terceros T,
				planes P,
				cuentas C
			WHERE 
				P.tipo_tercero_id = T.tipo_id_tercero
				AND P.tercero_id = T.tercero_id
				AND C.plan_id = P.plan_id
				AND C.empresa_id = '".$this->empresa_id."'
				AND C.estado = '1' 
			ORDER BY
				3";//ORDER BY T.nombre_tercero
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener los terceros.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF){
			return "ShowMensaje";
		}
		else
		{
			while ($data = $result->FetchRow()) {
				$tercero[] = $data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $tercero;
		}
	}

	/**
	 * Retorna los planes
	 *
	 * @param string TipoIdTercero
	 * @param string TerceroId
	 * @return array
	 */
	function GetPlanes($TipoIdTercero,$TerceroId)
	{
		if(!empty($TipoIdTercero))
		{
			$sqlFiltro = " AND T.tipo_id_tercero='$TipoIdTercero' AND T.tercero_id='$TerceroId'" ;
		}
		$query = "
			SELECT DISTINCT
				P.plan_id,
				P.plan_descripcion
			FROM
				terceros T,
				planes P,
				cuentas C
			WHERE 
				P.tipo_tercero_id = T.tipo_id_tercero 
				AND P.tercero_id = T.tercero_id 
				AND C.plan_id = P.plan_id 
				AND C.empresa_id = '".$this->empresa_id."'
				AND C.estado = '1' 
				$sqlFiltro
			ORDER BY
				2";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener los terceros.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF){
			return "ShowMensaje";
		}
		else
		{
			while ($data = $result->FetchRow()) {
				$planes[] = $data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			return $planes;
		}
	}//Fin GetPlanes

	/** 
	 * Autentica el usuario que va a utilizar el modulo
	 *
	 * @param string modulo
	 * @param string metodo
	 * @return array
	 */
	function GetLogueo($modulo,$metodo)
	{
		$query =  "
			SELECT
				X.empresa_id,
				X.centro_utilidad,
				X.sw_todos_cu,
				e.razon_social as descripcion1,
				cu.descripcion as descripcion2
			FROM 
				userpermisos_censo X,
				empresas e,
				centros_utilidad cu
			WHERE 
				X.usuario_id = ".UserGetUID()." AND
				e.empresa_id = X.empresa_id AND
				cu.centro_utilidad = X.centro_utilidad AND
				cu.empresa_id = X.empresa_id";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		if (!$result) {
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}
		while ($data = $result->FetchRow())
		{
			$centros[$data['descripcion1']][$data['descripcion2']] = $data;
		}
		$mtz[0]="EMPRESA";
		$mtz[1]="CENTRO UTILIDAD";
		$url[0]='app';
		$url[1]='Censo';
		$url[2]='user';
		$url[3]='CallMenu';
		$url[4]='datos';
		$Datos[0]=$mtz;
		$Datos[1]=$centros;
		$Datos[2]=$url;
		return $Datos;
	}//Fin GetLogueo
	
	/**
	 * Retorna los pacientes hospitalizados
	 *
	 * @param string $DepartamentoId
	 * @param string $EstacionId
	 * @param string $TipoIdTercero
	 * @param string $TerceroId
	 * @param string PlanId
	 * @return array
	 */
	function GetCenso($DepartamentoId,$EstacionId,$TipoIdTercero,$TerceroId,$PlanId,$Agrupar=false)
	{
		if(!empty($DepartamentoId))
		{
			$sqlFiltroDpto = " AND h.departamento='$DepartamentoId'";
		}
		if(!empty($EstacionId))
		{
			$sqlFiltroEstacion = " AND a.estacion_id='$EstacionId'";
		}
		if(!empty($TipoIdTercero))
		{
			$sqlFiltroTercero = " AND f.tipo_tercero_id='$TipoIdTercero' AND f.tercero_id='$TerceroId'";
		}
		if(!empty($PlanId))
		{
			$sqlFiltroPlan = " AND f.plan_id=$PlanId";
		}
		$NORMAL=1;//CAMA NORMAL
		$VIRTUAL=2;//CAMA VIRTUAL
		$query = " 
			SELECT 
				--a.estacion_id,
				i.departamento,
				i.descripcion as desc_departamento,
				h.estacion_id,
				h.descripcion as desc_estacion,
				b.pieza,
				a.cama,
				--a.movimiento_id,
				--a.numerodecuenta,
				--a.fecha_ingreso,
				d.ingreso,
				TO_CHAR(d.fecha_ingreso,'YYYY-MM-DD HH:MI') AS fecha_ingreso1,
				d.fecha_ingreso,
				d.tipo_id_paciente||' '||d.paciente_id as paciente_id,
				--e.primer_nombre,
				--e.segundo_nombre,
				--e.primer_apellido,
				--e.segundo_apellido,
				e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo,
				--f.plan_id,
				f.plan_descripcion,
				f.tipo_tercero_id,
				f.tercero_id,
				g.nombre_tercero
			FROM
				movimientos_habitacion a,
				camas b,
				cuentas c,
				ingresos d,
				pacientes e,
				planes f,
				terceros g,
				estaciones_enfermeria h,
				departamentos i
			WHERE
				a.fecha_egreso IS NULL
				AND b.cama = a.cama
				AND b.sw_virtual IN('$NORMAL','$VIRTUAL')
				AND c.numerodecuenta = a.numerodecuenta
				AND d.ingreso = a.ingreso
				AND e.paciente_id = d.paciente_id
				AND e.tipo_id_paciente = d.tipo_id_paciente
				AND f.plan_id = c.plan_id
				AND g.tercero_id = f.tercero_id
				AND g.tipo_id_tercero = f.tipo_tercero_id
				AND h.estacion_id = a.estacion_id
				AND h.departamento =  i.departamento
				AND i.empresa_id='".$this->empresa_id."'
				$sqlFiltroDpto
				$sqlFiltroEstacion
				$sqlFiltroPlan
				$sqlFiltroTercero
			ORDER BY e.primer_nombre , e.segundo_nombre , e.primer_apellido";
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "CENSO-GETCENSO";
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
		}
		if($resultado->EOF)
		{
			return null;
		}
		if($Agrupar)
		{
			while($datos=$resultado->FetchRow())
			{
				$filas[$datos['departamento']][$datos['estacion_id']][]=$datos;
			}
		}
		else
		{
			$filas = $resultado->GetRows();
		}
		$resultado->Close();
		return $filas;
	}//Fin GetCenso
  
  
    /**
    * Metodo para obtener los pacientes internados en una estacion
    *
    * @param string $estacion_id
    * @return array
    * @access public
    */
    function GetCensoPacientesObsUrgencias()
    {
        
        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query = "  SELECT a.*
                    --, b.evolucion_id
                    --, j.diagnostico_id||' - '||j.diagnostico_nombre as diagnostico
                
                    FROM
                        (
                            SELECT h.estacion_id, 
                                h.descripcion as nom_estacion,
                                (SELECT verificacionpaciente_ecirugia(a.numerodecuenta)) as paciente_cirugia,
                                a.movimiento_id,
                                a.numerodecuenta,
                                a.fecha_ingreso AS fecha_hospitalizacion,
                                b.pieza,
                                a.cama,
                                d.ingreso,
                                d.fecha_ingreso,
                                TO_CHAR(d.fecha_ingreso,'YYYY-MM-DD HH:MI') AS fecha_ingreso1,
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
                                terceros g,
                                estaciones_enfermeria h
                            WHERE
                                a.fecha_egreso IS NULL
                                AND a.estacion_id = h.estacion_id
                                AND h.sw_observacion_urgencia='1'
                                AND b.cama = a.cama
                                AND c.numerodecuenta = a.numerodecuenta
                                AND d.ingreso = a.ingreso
                                AND e.paciente_id = d.paciente_id
                                AND e.tipo_id_paciente = d.tipo_id_paciente
                                AND f.plan_id = c.plan_id
                                AND g.tercero_id = f.tercero_id
                                AND g.tipo_id_tercero = f.tipo_tercero_id
                        ) AS a 
                        --LEFT JOIN hc_evoluciones b ON (b.ingreso = a.ingreso)
                        --LEFT JOIN hc_diagnosticos_ingreso i ON (b.evolucion_id=i.evolucion_id AND i.sw_principal = '1')
                        --LEFT JOIN diagnosticos j ON (i.tipo_diagnostico_id=j.diagnostico_id)
                    ORDER BY a.ingreso,a.cama, a.pieza;";              
           
                             ;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "EE_PanelEnfermeria - GetPacientesInternados";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF){
          return null;
        }
        while ($data = $resultado->FetchRow()){
          $datos[$data['estacion_id']][$data['nom_estacion']][$data['ingreso']]=$data;
        }        
        return $datos;

    }//fin del metodo
    
    
    /**
    * Metodo para obtener los pacientes internados en una estacion
    *
    * @param string $estacion_id
    * @return array
    * @access public
    */
    function GetCensoPacientesConsultaUrgencias()
    {
        
        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query = "SELECT  a.*,
                        b.evolucion_id,
                        c.nivel_triage_id,
                        c.plan_id as plan_id_triage,
                        c.triage_id,
                        c.punto_triage_id,
                        c.punto_admision_id,
                        c.sw_no_atender,
                        d.descripcion as descripcion_triage,
                        e.numerodecuenta,
                        e.plan_id,
                        p.marca_prioridad_atencion,
                        p.plan_descripcion,
                        ter.nombre_tercero,
                        (SELECT verificacionpaciente_ecirugia(e.numerodecuenta)) as paciente_cirugia
                FROM
                    (
                    SELECT
                        c.paciente_id,
                        c.tipo_id_paciente,
                        c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre_completo,
                        c.fecha_nacimiento,
                        b.ingreso,
                        TO_CHAR(b.fecha_ingreso,'YYYY-MM-DD HH:MI') AS fecha_ingreso1,
                        b.fecha_ingreso,
                        a.estacion_id,
                        a.triage_id,
                        a.sw_estado

                    FROM
                        pacientes_urgencias a,
                        ingresos as b,
                        pacientes as c

                    WHERE                        
                        a.sw_estado IN ('1','7')                                      
                        AND b.ingreso = a.ingreso
                        AND b.estado = '1'
                        AND c.paciente_id = b.paciente_id
                        AND c.tipo_id_paciente = b.tipo_id_paciente
                    ORDER BY b.fecha_ingreso ASC
                    ) as a
                    LEFT JOIN hc_evoluciones b ON ( b.ingreso = a.ingreso                                                    
                                                    AND b.estado = '1' )
                    LEFT JOIN triages c ON (c.triage_id = a.triage_id)
                    LEFT JOIN niveles_triages d ON (d.nivel_triage_id = c.nivel_triage_id
                                                    AND c.nivel_triage_id != 0
                                                    AND c.sw_estado != '9')
                    LEFT JOIN cuentas e ON (e.ingreso=a.ingreso AND e.estado = '1'),
                    planes p,terceros ter
                    WHERE e.plan_id = p.plan_id
                    AND ter.tercero_id = p.tercero_id
                    AND ter.tipo_id_tercero = p.tipo_tercero_id
                    ORDER BY c.nivel_triage_id ASC";
                             ;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resultado = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "EE_PanelEnfermeria - GetPacientesInternados";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if($resultado->EOF){
          return null;
        }
        while ($data = $resultado->FetchRow()){
          $datos[]=$data;
        }        
        return $datos;

    }//fin del metodo
	
	/**
	 * Retorna un arreglo con las estaciones y la cantidad de pacientes por ingresar
	 *
	 * @return array
	 */
	function GetCantidadPacientesPorIngresar()
	{
		$sql="
			SELECT 
				estacion_id,
				count(*) AS cantidad
			FROM
				estaciones_enfermeria_ingresos_pendientes
			WHERE
				sw_estado = '1'
			GROUP BY
				estacion_id";
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "CENSO-GETPACIENTESPORINGRESAR";
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
		}
		while($datos = $resultado->FetchRow())
		{
			$a[$datos['estacion_id']]=$datos['cantidad'];
		}
		return $a;
	}//Fin GetCantidadPacientesPorIngresar
	
	/**
	 * Cantidad de camas activas por estaciones
	 *
	 * @return array
	 */
	function GetCantidadCamasActivasEstaciones()
	{
		$CAMASNORMALES=1;
		$sql="
		SELECT 
			p.estacion_id,
			count(c.cama) as cantidad
		FROM
			camas c,
			piezas p
		WHERE
			c.pieza=p.pieza AND
			c.sw_virtual = '$CAMASNORMALES'
		GROUP BY
		1";
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "CENSO-GETPACIENTESPORINGRESAR";
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
		}
		while($datos = $resultado->FetchRow())
		{
			$a[$datos['estacion_id']]=$datos['cantidad'];
		}
		return $a;
	}//Fin GetCantidadCamasActivasEstaciones
	
	/**
	 * Retorna un arreglo con la cantidad de pacientes en camas
	 * de los tipos NORMALES Y VIRTUALES
	 * 
	 * @param array tipos
	 * @return array
	 */
	function GetCantidadPacientesEnCamas()
	{
		$sql = "
		SELECT
			mh.estacion_id,
			count(*) as cantidad
		FROM
			movimientos_habitacion as mh,
			camas  c
		WHERE
			mh.fecha_egreso IS NULL
			AND mh.cama=c.cama
			AND c.sw_virtual IN(1,2)
		GROUP BY
			1;";
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0) {
				$this->error = "CENSO-GETPACIENTESPORINGRESAR";
				$this->mensajeDeError = $dbconn->ErrorMsg();
				return false;
		}
		while($datos = $resultado->FetchRow())
		{
			$a[$datos['estacion_id']]=$datos['cantidad'];
		}
		return $a;
	}//Fin GetCantidadPacientesEnCamas
	
	/**
	 * Retorna los dias de hospitalizacion a partir de fecha_ingreso
	 *
	 * @param date fecha_ingreso
	 */
	function GetDiasHospitalizacion($fecha_ingreso)
	{
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
	}//Fin GetDiasHospitalizacion
	
	/**
	 * Retorna un arreglo con los pacientes por ingresar
	 *
	 * @param string DepartamentoId
	 * @param string EstacionId
	 * @return array
	 */
	function GetPacientesPorIngresar($DepartamentoId,$EstacionId)
	{
		if(!empty($DepartamentoId))
		{
			$sqlFiltroDpto = " AND d.departamento='$DepartamentoId'";
		}
		if(!empty($EstacionId))
		{
			$sqlFiltroEstacion = " AND e.estacion_id='$EstacionId'";
		}
		$PACIENTESXINGRESARACTIVOS=1;
		$sql ="
			SELECT 
				e.estacion_id,
				e.descripcion as desc_estacion,
				d.descripcion as desc_dpto,
				p.tipo_id_paciente||' '||p.paciente_id as paciente_id,
				p.primer_nombre || ' ' || p.segundo_nombre || ' ' || p.primer_apellido || ' ' || p.segundo_apellido as nombre_completo
			FROM
				estaciones_enfermeria_ingresos_pendientes ee,
				cuentas c,
				ingresos i,
				pacientes p,
				estaciones_enfermeria e,
				departamentos d
			WHERE
				ee.numerodecuenta=c.numerodecuenta
				AND c.ingreso = i.ingreso
				AND i.paciente_id = p.paciente_id
				AND i.tipo_id_paciente = p.tipo_id_paciente
				AND e.estacion_id = ee.estacion_id 
				AND e.departamento = d.departamento
				AND d.empresa_id = '".$this->empresa_id."'
				AND ee.sw_estado = '$PACIENTESXINGRESARACTIVOS'
				$sqlFiltroDpto
				$sqlFiltroEstacion";
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "CENSO-GetPacientesPorIngresar";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		$pacientes=$resultado->GetRows();
		$resultado->Close();
		return $pacientes;
	}//Fin GetPacientesPorIngresar
	
	/**
	 * Trae la programacion de cirugia para la fecha o el dia actual
	 *
	 * @return array
	 */
	function GetProgramacionQx($fecha)
	{
		if(empty($fecha))
			$fecha = date('Y-m-d');
		$sql = "
		SELECT
			a.departamento,
			e.descripcion as departamento_descripcion,
			a.programacion_id,
			a.hora_inicio,
			a.hora_fin,
			a.quirofano_id,
			a.quirofano,
			a.tipo_id_paciente,
			a.paciente_id,
			b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as paciente,
			a.cargo,
			d.descripcion as cargo_descripcion,
			ter.nombre_tercero as cirujano,
			a.plan_id,
			c.plan_descripcion,
               a.observaciones
		FROM
		(
			SELECT 
				a.departamento,
				a.programacion_id,
				a.tipo_id_paciente,
				a.paciente_id,
				c.hora_inicio,
				c.hora_fin,
				c.quirofano_id,
				d.descripcion as quirofano,
				b.tipo_id_cirujano,
				b.cirujano_id,
				b.procedimiento_qx as cargo,
				b.plan_id,
                    b.observaciones
			FROM 
				qx_programaciones a,
				qx_procedimientos_programacion b,
				qx_quirofanos_programacion c,
				qx_quirofanos d
			
			WHERE 
				a.programacion_id = c.programacion_id 
				AND a.programacion_id = b.programacion_id 
				AND c.quirofano_id=d.quirofano 
				AND c.qx_tipo_reserva_quirofano_id='3'
		) as a 
			LEFT JOIN terceros ter 
			ON (a.cirujano_id=ter.tercero_id AND a.tipo_id_cirujano=ter.tipo_id_tercero),
			pacientes b,
			planes c,
			cups d,
			departamentos e
		WHERE
			a.tipo_id_paciente=b.tipo_id_paciente 
			AND a.paciente_id=b.paciente_id 
			AND a.plan_id=c.plan_id
			AND a.cargo = d.cargo
			AND e.departamento = a.departamento
			AND e.empresa_id='".$this->empresa_id."'
			AND date(a.hora_inicio) = '$fecha'
		ORDER BY
			e.departamento,
			a.quirofano_id,
			a.hora_inicio,
			a.programacion_id,
			cirujano";
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "CENSO-GetProgramacionQx";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			return false;
		}
		while($row = $resultado->FetchRow())
		{
			$datos[$row['departamento']][$row['quirofano_id']][$row['programacion_id']][$row['cargo']] = $row;
		}
		return $datos;
	}//Fin GetProgramacionQx
	/******************************************************************
	*
	*******************************************************************/
	function ObtenerDiagnostico($ingreso)
	{
		$sql .= "SELECT MAX(HE.evolucion_id),";
		$sql .= "				DI.diagnostico_id||' - '||DI.diagnostico_nombre ";
		$sql .= "FROM		hc_evoluciones HE,";
		$sql .= "				hc_diagnosticos_ingreso HI, ";
		$sql .= "				diagnosticos DI ";
		$sql .= "WHERE	HE.evolucion_id = HI.evolucion_id ";
		$sql .= "AND		HE.ingreso = ".$ingreso." ";
		$sql .= "AND		HI.sw_principal = '1' ";
		$sql .= "AND		HI.tipo_diagnostico_id = DI.diagnostico_id ";
		$sql .= "GROUP BY 2 ";
		
		list($dbconn)=GetDBConn();
		$rst = $dbconn->Execute($sql);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$principal = "";
		
		if(!$rst->EOF)
		{
			$principal = $rst->fields[1];
			$rst->MoveNext();
		}
		$rst->Close();
		return $principal;
	}
}//Fin clase
?>