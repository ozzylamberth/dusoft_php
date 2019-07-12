<?php

/**
 * $Id: app_EE_Cargos_user.php,v 1.10 2007/11/28 15:58:16 jgomez Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_Cargos_user extends classModulo
{

     
        var $dbconn;
        var $mensajeError;
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
     
     /**
     * Metodo para obtener los pacientes internados en una estacion
     *
     * @param string $estacion_id
     * @return array
     * @access public
     */
     function GetPacientesInternados($estacion_id)
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
                                   g.nombre_tercero,
																	 c.rango
     
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
                                   AND estacion_id = '".$estacion_id."'
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
    function GetPacientesConsultaUrgencias($estacion_id)
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
                        e.plan_id,
												e.rango
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
                        a.estacion_id = '".$estacion_id."'
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
    * Metodo para obtener los pacientes internados en una estacion de Cirugia
    *
    * @param string $estacion_id
    * @return array
    * @access public
    */
    function GetPacientesInternadosCirugia($departamento)
    {
        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query = "  SELECT a.*, b.evolucion_id
        			 	
                    FROM
                        (
                  		  SELECT
                            	 (SELECT verificacionpaciente_ecirugia(a.numerodecuenta)) as paciente_cirugia,
                                a.numero_registro,
                                a.numerodecuenta,
                                a.fecha_ingreso AS fecha_ingreso_cirugia,
                                a.departamento,
                                a.programacion_id,
                                a.usuario_id,
                                a.sw_estado,
                                a.estacion_origen,
                                a.observaciones,
                                a.fecha_egreso,
                                c.rango,
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
                                estacion_enfermeria_qx_pacientes_ingresados a,
                                cuentas c,
                                ingresos d,
                                pacientes e,
                                planes f,
                                terceros g
                            WHERE
						  (a.sw_estado = '0' OR a.sw_estado = '1')
                                AND a.fecha_egreso IS NULL
                                AND a.departamento = '".$departamento."'
                                AND c.numerodecuenta = a.numerodecuenta
                                AND d.ingreso = c.ingreso
                                AND e.paciente_id = d.paciente_id
                                AND e.tipo_id_paciente = d.tipo_id_paciente
                                AND f.plan_id = c.plan_id
                                AND g.tercero_id = f.tercero_id
                                AND g.tipo_id_tercero = f.tipo_tercero_id
                        ) AS a LEFT JOIN hc_evoluciones b
                                ON (b.ingreso = a.ingreso
                                    AND b.usuario_id = ".UserGetUID()."
                                    AND b.estado = '1')
                    ORDER BY fecha_ingreso_cirugia;";

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
    * Metodo para obtener los pacientes internados en una estacion de Preparacion de Cirugia
    *
    * @param string $estacion_id
    * @return array
    * @access public
    */
    function GetPacientesInternadosPreparacionQX($departamento)
    {
        list($dbconn) = GetDBconn();
        global $ADODB_FETCH_MODE;

        $query = "  SELECT a.*, b.evolucion_id
        			 	
                    FROM
                        (
                  		  SELECT
                            	 (SELECT verificacionpaciente_ecirugia(a.numerodecuenta)) as paciente_cirugia,
                                a.numero_registro,
                                a.numerodecuenta,
                                a.fecha_ingreso AS fecha_ingreso_cirugia,
                                a.departamento,
                                a.programacion_id,
                                a.usuario_id,
                                a.sw_estado,
                                a.estacion_origen,
                                a.observaciones,
                                a.fecha_egreso,
                                c.rango,
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
                                estacion_enfermeria_qx_preparacion a,
                                cuentas c,
                                ingresos d,
                                pacientes e,
                                planes f,
                                terceros g
                            WHERE
						  (a.sw_estado = '0' OR a.sw_estado = '1')
                                AND a.fecha_egreso IS NULL
                                AND a.departamento = '".$departamento."'
                                AND c.numerodecuenta = a.numerodecuenta
                                AND d.ingreso = c.ingreso
                                AND e.paciente_id = d.paciente_id
                                AND e.tipo_id_paciente = d.tipo_id_paciente
                                AND f.plan_id = c.plan_id
                                AND g.tercero_id = f.tercero_id
                                AND g.tipo_id_tercero = f.tipo_tercero_id
                        ) AS a LEFT JOIN hc_evoluciones b
                                ON (b.ingreso = a.ingreso
                                    AND b.usuario_id = ".UserGetUID()."
                                    AND b.estado = '1')
                    ORDER BY fecha_ingreso_cirugia;";

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
    * Metodo para obtener los datos del quirofano donde se realizara Procedimiento QX.
    *
    * @param string $ingreso
    * @return array
    * @access public
    */
 	function QuirofanoPaciente($programacion)
     {
          list($dbconn) = GetDBconn();
		$sql = "SELECT quirofano_id 
                  FROM qx_quirofanos_programacion
                  WHERE programacion_id = ".$programacion.";";
          $resultado = $dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurri�un error al intentar seleccionar el contacto del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
		return $resultado->fields[0];
     
     }

	/**
	*		GetDiasHospitalizacion
	*
	*		Calcula los d�s que lleva hospitalizada una persona, basandose en la fecha de ingreso.
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
            $this->mensajeDeError = "Ocurri�un error al intentar seleccionar el contacto del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
            return false;
        }

        if($result->EOF) return null;
        $ContactosPaciente = $result->GetRows();
        $result->Close();
        return $ContactosPaciente;
    }
		
     /*
     *	LlamarFormaBodegas: Metodo q llama la forma de la seleccion de Bodega.
     *	@Author Tizziano Perea
     *	@access Public
     */
     function LlamarFormaBodegas()
     {
          $Cuenta=$_REQUEST['Cuenta'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];

          $_SESSION['CUENTAS']['E']['tipo_id_paciente']=$TipoId;
          $_SESSION['CUENTAS']['E']['paciente_id']=$PacienteId;

          list($dbconn) = GetDBconn();
          $query = "select a.departamento,b.empresa_id, b.centro_utilidad
                                   from estaciones_enfermeria as a, departamentos as b
                                   where a.estacion_id='".$_REQUEST['estacion']['estacion_id']."'
                                   and a.departamento=b.departamento";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
          }

         $query = "select a.departamento,b.empresa_id, b.centro_utilidad
                                   from estaciones_enfermeria as a, departamentos as b
                                   where a.estacion_id='".$_REQUEST['estacion']['estacion_id']."'
                                   and a.departamento=b.departamento";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
          }

          $_SESSION['CUENTAS']['E']['CENTROUTILIDAD']=$result->fields[2];
          $_SESSION['CUENTAS']['E']['EMPRESA']=$result->fields[1];
          $_SESSION['CUENTAS']['E']['DEPTO']=$result->fields[0];
          $_SESSION['CUENTAS']['E']['INGRESO']=$_REQUEST['ingreso'];
          $_SESSION['CUENTAS']['E']['ESTACION']=$_REQUEST['estacion']['estacion_id'];

          if(!$this->FormaBodegas($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha)){
                         return false;
          }
          return true;
     }

     /**
     * Llama la forma con el combo de las bodegas.
     * @access public
     * @return boolean
     */
     function BodegaInsumos()
     {
          $Cuenta=$_REQUEST['Cuenta'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          unset($_SESSION['CUENTA']['E']['BODEGA']);

          if($_REQUEST['Bodegas']==-1){
               if($_REQUEST['Bodegas']==-1){ $this->frmError["Bodegas"]=1; }
               $this->frmError["MensajeError"]="Debe Elegir la Bodega";
               if(!$this->FormaBodegas($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha)){
                    return false;
               }
               return true;
          }

          $_SESSION['CUENTA']['E']['BODEGA']=$_REQUEST['Bodegas'];
          $this->Insumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId);
          return true;
     }
     
     /**
     *
     */
     function Bodegas()
     {
          $EmpresaId=$_SESSION['CUENTAS']['E']['EMPRESA'];
          $CU="and a.centro_utilidad='".$_SESSION['CUENTAS']['E']['CENTROUTILIDAD']."'
                    and b.centro_utilidad='".$_SESSION['CUENTAS']['E']['CENTROUTILIDAD']."'";

          list($dbconn) = GetDBconn();
          $query="SELECT a.* FROM bodegas as a,bodegas_estaciones as b
                  WHERE a.empresa_id='$EmpresaId' $CU
                  AND b.empresa_id='$EmpresaId' AND a.bodega=b.bodega
                  AND b.estacion_id='".$_SESSION['CUENTAS']['E']['ESTACION']."'
                  AND a.sw_consumo_directo = '1';";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while(!$result->EOF)
          {
               $var[]= $result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }
          $result->Close();
          return $var;
     }

     
     /**
     * Llama la forma FormaInsumos que insertar nuevos cargos.
     * @access public
     * @return boolean
     */
     function Insumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId)
     {
          $this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D,$var);
          return true;
     }
     
     /*
     * Funcion que Consulta el Nombre del Paciente
     */
     function BuscarNombresPaciente($tipo,$documento)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT primer_nombre,segundo_nombre FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               else{
                    if($result->EOF){
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "La tabla 'pacientes' esta vacia ";
                         return false;
                    }
               }
          $Nombres=$result->fields[0]." ".$result->fields[1];
          $result->Close();
          return $Nombres;
     }

     /*
     * Funcion que Consulta los Apellidos del Paciente
     */
     function BuscarApellidosPaciente($tipo,$documento)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT primer_apellido,segundo_apellido FROM pacientes WHERE tipo_id_paciente='$tipo' AND paciente_id='$documento'";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else{
               if($result->EOF){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "La tabla 'paciente' esta vacia ";
                    return false;
               }
          }
          $result->Close();
          $Apellidos=$result->fields[0]." ".$result->fields[1];
          return $Apellidos;
     }


    /**
    * funcion que sirve para obtener el nombre del usuario a aprtir de su usuario_id
    *
    **/
    function NombreUsu($usuario_id)
    {
    $sql=" SELECT
             nombre,
             usuario
            FROM
            system_usuarios
            WHERE
            usuario_id='".trim($usuario_id)."'";

            if(!$resultado = $this->ConexionBaseDatos($sql))
            return false;

            $documentos=Array();
            while(!$resultado->EOF)
            {
            $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
            }

            $resultado->Close();
            return $documentos;




    }
   
     /*
     * DatosTmpInsumos()
     */
     function DatosTmpInsumos($Cuenta)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT
                        a.*,

                        c.descripcion,
                        b.descripcion as nom_bodega,
                        e.existencia
                        
                        
                    FROM
                        tmp_cuenta_insumos as a,
                        bodegas as b,
                        inventarios_productos as c,
                        existencias_bodegas as e
                    WHERE
                    a.numerodecuenta=$Cuenta
                    AND b.empresa_id=a.empresa_id
                    AND b.centro_utilidad=a.centro_utilidad
                    AND b.bodega=a.bodega
                    AND c.codigo_producto=a.codigo_producto
                    AND e.empresa_id= a.empresa_id
                    AND e.centro_utilidad= a.centro_utilidad
                    AND e.bodega= a.bodega
                    AND e.codigo_producto= a.codigo_producto";


          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while(!$result->EOF)
          {
               $var[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }
          return $var;
     }

     function BuscarNombreDpto($Departamento)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT descripcion FROM departamentos WHERE departamento='$Departamento'";
          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $result->Close();
          return $result->fields[0];
     }
     
     /**
     *
     */
     function NombreBodega($Bodega)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT descripcion	FROM bodegas
                    WHERE bodega='$Bodega'";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $var=$result->GetRowAssoc($ToUpper = false);
          return $var;
     }

     function CuentaParticular($Cuenta,$PlanId)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT a.tipo_id_tercero,a.tercero_id, b.nombre_tercero, c.plan_descripcion, c.protocolos
                    FROM cuentas_responsable_particular as a, terceros as b, planes as c
                    WHERE a.numerodecuenta='$Cuenta' AND a.tipo_id_tercero=b.tipo_id_tercero
                    AND a.tercero_id=b.tercero_id AND c.plan_id='$PlanId' ";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          if(!$result->EOF)
          {
               $var=$result->GetRowAssoc($ToUpper = false);
          }
          $result->Close();
          return $var;
     }


     function BuscarPlanes($PlanId,$Ingreso)
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT sw_tipo_plan FROM planes WHERE plan_id='$PlanId'";
          $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $sw=$results->fields[0];
          //soat
          if($sw==1)
          {
               $query = "SELECT  b.nombre_tercero, c.plan_descripcion, e.tipo_id_tercero, e.tercero_id, c.protocolos
                         FROM ingresos_soat as a, terceros as b, planes as c,
                              soat_eventos as d, soat_polizas as e
                         WHERE a.ingreso=$Ingreso AND a.evento=d.evento AND e.tipo_id_tercero=b.tipo_id_tercero
                         AND e.tercero_id =b.tercero_id AND c.plan_id='$PlanId' AND d.poliza=e.poliza";
          }
          //cliente o capitacion
          if($sw==0 OR $sw==3)
          {
               $query = "SELECT a.tipo_tercero_id as tipo_id_tercero,a.tercero_id, a.plan_descripcion, b.nombre_tercero, a.protocolos
                         FROM planes as a, terceros as b
                         WHERE a.plan_id='$PlanId' AND a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
          }
          //particular
          if($sw==2)
          {
               $query = "select b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre_tercero,
                              c.plan_descripcion, b.tipo_id_paciente as tipo_id_tercero, b.paciente_id as tercero_id, c.protocolos
                         from ingresos as a, pacientes as b, planes as c
                         where a.ingreso='$Ingreso' and a.paciente_id=b.paciente_id and a.tipo_id_paciente=b.tipo_id_paciente
                         and c.plan_id='$PlanId'";
          }
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $var=$result->GetRowAssoc($ToUpper = false);
          $result->Close();
          return $var;
     }
          
     /**
     * Llama la forma para modificar un cargo de la cuenta en tmp_cuenta_insumos
     * @ access public
     * @ return boolean
     */
     function LlamaFormaModificarCargoTmpIyM()
     {
          $Datos=$_REQUEST['Datos'];
          $Cuenta=$_REQUEST['Cuenta'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];

          if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Datos)){
               return false;
          }
          return true;
     }
     
     /**
     *
     */
     function ModificarCargoTmpIyM()
     {
          IncludeLib("tarifario");
          $Departamento=$_SESSION['CUENTAS']['E']['DEPTO'];
          $Precio=$_REQUEST['Precio'];
          $Codigo=$_REQUEST['Codigo'];
          $TarifarioId=$_REQUEST['TarifarioId'];
          $Gravamen=$_REQUEST['Gravamen'];
          $Cantidad=$_REQUEST['Cantidad'];
          $Cuenta=$_REQUEST['Cuenta'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $empresa=$_REQUEST['EmpresaId'];
          $cu=$_REQUEST['CU'];
          $bodega=$_REQUEST['Bodegas'];
          $f=explode('/',$_REQUEST['FechaCargo']);
          $_REQUEST['FechaCargo']=$f[2].'-'.$f[1].'-'.$f[0];

          $SystemId=UserGetUID();
          if(!$Cantidad || !$Codigo || !$_REQUEST['FechaCargo']){
               if(!$Cantidad){ $this->frmError["Cantidad"]=1; }
               if(!$Codigo){ $this->frmError["Codigo"]=1; }
               if(!$FechaCargo){ $this->frmError["FechaCargo"]=1; }
               $this->frmError["MensajeError"]="Faltan datos obligatorios.";
               if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$_REQUEST['Datos'])){
                    return false;
               }
               return true;
          }

          $f = (int) $Cantidad;
          $y = $Cantidad - $f;
          if($y != 0){
               if($y != 0){ $this->frmError["Cantidad"]=1; }
               $mensaje='La Cantidad debe ser entera.';
               if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$_REQUEST['Datos'])){
                    return false;
               }
               return true;
          }

          list($dbconn) = GetDBconn();
          $query ="SELECT b.servicio
                              FROM departamentos as b
                              WHERE b.departamento='$Departamento'";
          $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $Servicio=$results->fields[0];

          $query = " UPDATE tmp_cuenta_insumos SET
                              cantidad=$Cantidad,
                              fecha_cargo='".$_REQUEST['FechaCargo']."'
                    WHERE tmp_cuenta_insumos_id=".$_REQUEST['id']."";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
          }
          $mensaje='El insumo se Modifico Correctamente.';
          if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,'')){
               return false;
          }
          return true;
     }

    function InsertarInsumosImproved($datos,$Cuenta,$PlanId,$Departamento,$empresa_id,$cu,$bodega)
    {
        
 
        list($dbconn) = GetDBconn();
        $query ="SELECT b.servicio
                    FROM departamentos as b
                    WHERE b.departamento='$Departamento'";
        $results = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $Servicio=$results->fields[0];
        $fechar_cargo=date("Y-m-d");
        for($i=0;$i<count($datos["codigox"]);$i++)
        {
            $sql="";
            if($datos['cantidad'][$i] > 0 && $datos['cantidad'][$i] !="")
            {
                  $sql = " INSERT INTO tmp_cuenta_insumos(
                                                        numerodecuenta,
                                                        departamento,
                                                        bodega,
                                                        codigo_producto,
                                                        cantidad,
                                                        empresa_id,
                                                        centro_utilidad,
                                                        precio,
                                                        fecha_cargo,
                                                        plan_id,
                                                        servicio_cargo,
                                                        usuario_id,
                                                        fecha_registro
                                                        )
             VALUES($Cuenta,'$Departamento','$bodega','".$datos['codigox'][$i]."',".$datos['cantidad'][$i].",'$empresa_id','$cu',".$datos['precio_venta'][$i].",'".$fechar_cargo."',$PlanId,'$Servicio',".UserGetUID().",now())";
                
    
                if(!$rst = $this->ConexionBaseDatos($sql))
                {  $cad="no se hizo la insercion";
                   return $cad.$sql;
                }
            }

        }

        return true;
    }
     /**
     *
     */
     function InsertarInsumos()
     {
          IncludeLib("tarifario");
          $Departamento=$_SESSION['CUENTAS']['E']['DEPTO'];
          $Precio=$_REQUEST['Precio'];
          $Codigo=$_REQUEST['Codigo'];
          $TarifarioId=$_REQUEST['TarifarioId'];
          $Gravamen=$_REQUEST['Gravamen'];
          $Cantidad=$_REQUEST['Cantidad'];
          $Cuenta=$_REQUEST['Cuenta'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $empresa=$_REQUEST['EmpresaId'];
          $cu=$_REQUEST['CU'];
          $bodega=$_REQUEST['Bodegas'];
          $f=explode('/',$_REQUEST['FechaCargo']);
          $_REQUEST['FechaCargo']=$f[2].'-'.$f[1].'-'.$f[0];
          $SystemId=UserGetUID();
          if(!$Cantidad || !$Codigo || !$_REQUEST['FechaCargo'])
          {
               if(!$Cantidad)
               {
                 $this->frmError["Cantidad"]=1;
               }
               if(!$Codigo){ $this->frmError["Codigo"]=1; }
               if(!$FechaCargo){ $this->frmError["FechaCargo"]=1; }
               $this->frmError["MensajeError"]="Faltan datos obligatorios.";
               if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D)){
                    return false;
               }
               return true;
          }

          $f = (int) $Cantidad;
          $y = $Cantidad - $f;
          if($y != 0){
               if($y != 0){ $this->frmError["Cantidad"]=1; }
               $mensaje='La Cantidad debe ser entera.';
               if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D)){
                    return false;
               }
               return true;
          }

          list($dbconn) = GetDBconn();
          $query ="SELECT b.servicio
                    FROM departamentos as b
                    WHERE b.departamento='$Departamento'";
          $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $Servicio=$results->fields[0];
          $query = " INSERT INTO tmp_cuenta_insumos(
                                                       numerodecuenta,
                                                       departamento,
                                                       bodega,
                                                       codigo_producto,
                                                       cantidad,
                                                       empresa_id,
                                                       centro_utilidad,
                                                       precio,
                                                       fecha_cargo,
                                                       plan_id,
                                                       servicio_cargo)
                    VALUES($Cuenta,'$Departamento','$bodega','$Codigo',$Cantidad,'$empresa','$cu',".$_REQUEST['Precio'].",'".$_REQUEST['FechaCargo']."',$PlanId,'$Servicio')";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
          }
          $mensaje='El insumo se Guardo Correctamente.';
          if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D)){
               return false;
          }
          return true;
     }

     
     /**
     * Elimina un cargo de la cuenta en tmp_cuenta_insumos.
     * @ access public
     * @ return boolean
     */
     function EliminarCargoTmpIyM()
     {
          $Cuenta=$_REQUEST['Cuenta'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];

          list($dbconn) = GetDBconn();
          $query =" DELETE FROM tmp_cuenta_insumos
                    WHERE tmp_cuenta_insumos_id=".$_REQUEST['ID']."
                    AND numerodecuenta=$Cuenta";
          $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Borrar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
          }

          $this->frmError["MensajeError"]="El Cargo se Elimino.";
          if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$D)){
               return false;
          }
          return true;
     }

     /**
     *
     **/
     function EliminarTodosCargosIyM_Improved()
     {
          list($dbconn) = GetDBconn();
          $query1 =" SELECT * FROM tmp_cuenta_insumos WHERE numerodecuenta=$Cuenta";
          $result=$dbconn->Execute($query1);
          $query =" DELETE FROM tmp_cuenta_insumos WHERE numerodecuenta=$Cuenta";
          $dbconn->BeginTrans();
          if ($dbconn->ErrorNo() != 0)
          {
                    $this->error = "Error al Borrar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
          }

         return true;

     }

    /**
     *
     */
     function EliminarTodosCargosIyM()
     {
          $Cuenta=$_REQUEST['Cuenta'];
          $Cancelar=$_REQUEST['Cancelar'];
          $Transaccion=$_REQUEST['Transaccion'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];

          list($dbconn) = GetDBconn();
          $query1 =" SELECT * FROM tmp_cuenta_insumos WHERE numerodecuenta=$Cuenta";
          $result=$dbconn->Execute($query1);
          $query =" DELETE FROM tmp_cuenta_insumos WHERE numerodecuenta=$Cuenta";
          $dbconn->BeginTrans();
          $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
          else
          {
               $dbconn->CommitTrans();
               $x=$result->RecordCount();
               if($x)
               {
                    $url = ModuloGetURL('app','EE_Cargos','user','Call_AgregarInsumos',array("datos_estacion"=>$_SESSION['CUENTAS']['E']['DATOS'],'tipoa'=>2));
                    $mensaje = 'TODOS LOS CARGOS FUERON BORRADOS.';
                    $titulo = 'ELIMINACION DE CARGOS';
                    $link = 'ACEPTAR';
                    if(!$this->frmMSG($url,$titulo,$mensaje,$link)){
                              return false;
                    }
                    return true;
               }
               else
               {
                    $this->Call_AgregarInsumos($_SESSION['CUENTAS']['E']['DATOS'],2);
                   return true;
               }
          }
     }
     /**
     * Elimina un cargo de la cuenta en tmp_cuenta_insumos.
     * @ access public
     * @ return boolean
     */
     function EliminarCargoTmpIyM_Improved($Cuenta,$tmp_cuenta_insumos_id)
     {
//           $Cuenta=$_REQUEST['Cuenta'];
//           $Nivel=$_REQUEST['Nivel'];
//           $PlanId=$_REQUEST['PlanId'];
//           $Ingreso=$_REQUEST['Ingreso'];
//           $Fecha=$_REQUEST['Fecha'];
//           $TipoId=$_REQUEST['TipoId'];
//           $PacienteId=$_REQUEST['PacienteId'];

          list($dbconn) = GetDBconn();
          $query =" DELETE FROM tmp_cuenta_insumos
                    WHERE tmp_cuenta_insumos_id=".$tmp_cuenta_insumos_id."
                    AND numerodecuenta=$Cuenta";
          $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Borrar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
          }

         return true;
     }
   
     
     /**
     *
     */
     function GuardarTodosCargosIyM()
     {
          $Cuenta=$_REQUEST['Cuenta'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];

          $_SESSION['DATOS_INSUMOSTMP'] = $this->DatosTmpInsumos($Cuenta);

          list($dbconn) = GetDBconn();
          
          $query = "SELECT count(a.numerodecuenta)
                                   FROM tmp_cuenta_insumos as a WHERE a.numerodecuenta=$Cuenta";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          if($result->fields[0]==0)
          {
               $this->frmError["MensajeError"]="NO HA AGREGADO NINGUN INSUMO.";
               if(!$this->FormaInsumos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$D)){
                    return false;
               }
               return true;
          }

          $argu = array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
          
          $_SESSION['INVENTARIOS']['RETORNO']['contenedor'] = 'app';
          $_SESSION['INVENTARIOS']['RETORNO']['modulo'] = 'EE_Cargos';
          $_SESSION['INVENTARIOS']['RETORNO']['tipo'] = 'user';
          $_SESSION['INVENTARIOS']['RETORNO']['metodo'] = 'RetornoInsumos';
          $_SESSION['INVENTARIOS']['RETORNO']['argumentos'] = $argu;
          $_SESSION['INVENTARIOS']['CUENTA'] = $Cuenta;

          $this->ReturnMetodoExterno('app','InvBodegas','user','LiquidacionMedicamentos');
          return true;
     }
     
     /**
     * Busca documentos de bodega por el numero del paciente..
     */
     function Get_DocumentosBodega($numerodecuenta)
     {
          list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;
          $query = "SELECT DISTINCT C.numeracion, D.bodegas_doc_id, A.departamento_al_cargar
          		FROM cuentas_detalle AS A,
                    	cuentas_codigos_agrupamiento AS B,
                         bodegas_documentos_d AS C, 
					bodegas_doc_numeraciones AS D
                    WHERE A.numerodecuenta = ".$numerodecuenta."
                    AND A.consecutivo IS NOT NULL
                    AND A.cargo = 'IMD'
                    AND A.codigo_agrupamiento_id = B.codigo_agrupamiento_id
                    AND A.consecutivo = C.consecutivo
                    AND C.numeracion = B.numeracion
                    AND C.bodegas_doc_id = B.bodegas_doc_id
                    AND B.bodegas_doc_id = D.bodegas_doc_id
                    ORDER BY C.numeracion DESC;";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Base de Datos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while ($data = $resultado->FetchRow())
          {
          	$vector[] = $data;
          }
          return $vector;
     }

     /**
     * Forma de retorno despues de la insercion de los Insumos.
     */
     function RetornoInsumos()
     {
          $Cuenta=$_REQUEST['Cuenta'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];

          if(!empty($_SESSION['INVENTARIOS']['RETORNO']['Bodega']))
          {
               $VectorArgumentos = array();
               $VectorArgumentos['verificacion'] = true;
               $VectorArgumentos['argumentos'] = $_SESSION['INVENTARIOS']['RETORNO']['argumentos'];
               
               unset($_SESSION['INVENTARIOS']);
               $mensaje='Los Documentos de Bodega han sido Creados Satisfactoriamente.';
               $url = ModuloGetURL('app','EE_Cargos','user','Call_AgregarInsumos',array("datos_estacion"=>$_SESSION['CUENTAS']['E']['DATOS'],'tipoa'=>2));
               $titulo = 'CREACION DE DOCUMENTOS';
               $link = 'VOLVER';
               
               if(!$this->frmMSG($url,$titulo,$mensaje,$link,$VectorArgumentos)){
                         return false;
               }
               return true;
          }
          else
          {
               unset($_SESSION['INVENTARIOS']);
               $mensaje='ERROR INSERTAR: Los Documentos de Bodega No Fueron Creados.';
               $url = ModuloGetURL('app','EE_Cargos','user','Call_AgregarInsumos',array("datos_estacion"=>$_SESSION['CUENTAS']['E']['DATOS'],'tipoa'=>2));
               $titulo = 'ERROR EN LA CREACION DE DOCUMENTOS';
               $link = 'VOLVER';
               if(!$this->frmMSG($url,$titulo,$mensaje,$link)){
                         return false;
               }
               return true;
          }
     }
          

     /**
     * Se encarga de separar la fecha del formato timestamp
     * @access private
     * @return string
     * @param date hora
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
               return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
          }
     }

     /**
     * Se encarga de separar la hora del formato timestamp
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
          return  $time[1].":".$time[2].":".$time[3];
     }



    /**
    * Funcion que sirve para la busqueda de producto,
    *
    * @param String $empresa_id 
    * @param int $centro_utilidad
    * @param int $bodega
    * @param int $filtro que contiene criterios de busqueda adicionales
    * @param int $offset pagina de registros
    * @return array $productos con la lista de productos encontrados
    **/
                            
    function BuscarProducto($empresa_id,$centro_utilidad,$bodega,$filtros,$offset)
    {


        if(!empty($filtros['criterio']))
        {
                
                if($filtros['tip_bus']=='1')
                {
                    $filtro="AND b.descripcion ILIKE '%".trim($filtros['criterio'])."%'";
                }
                
                elseif($filtros['tip_bus']=='2')
                {
                    $filtro="AND b.codigo_producto ILIKE '%".trim($filtros['criterio'])."%'";
        
                }
                else
                {
                    $filtro="";
                }
       }


        $sql1="SELECT
                    count(*)
                FROM
              (SELECT
                  b.codigo_producto,
                  b.descripcion,
                  b.unidad_id,
                  c.descripcion as descripcion_unidad,
                  a.existencia,
                  d.costo,
                  d.precio_venta  
              FROM
                  existencias_bodegas as a,
                  inventarios_productos as b,
                  unidades as c,
                  inventarios as d
              WHERE
              a.empresa_id = '$empresa_id'
              AND a.centro_utilidad = '$centro_utilidad'
              AND a.bodega = '$bodega'
              ".$filtro."
              AND b.codigo_producto = a.codigo_producto
              AND c.unidad_id = b.unidad_id
              AND a.estado = '1'
              AND a.empresa_id = d.empresa_id
              AND a.codigo_producto = d.codigo_producto) AS t
              LEFT JOIN
              tmp_cuenta_insumos as a
              ON (a.numerodecuenta=".$filtros['SuperCuenta']."
              AND t.codigo_producto=a.codigo_producto)
              WHERE a.numerodecuenta IS NULL";
              $this->ProcesarSqlConteo($sql1,7,$offset);

              $sql="SELECT t.*
           FROM
           (
                  SELECT  
                  b.codigo_producto,
                  b.descripcion,
                  b.unidad_id,
                  c.descripcion as descripcion_unidad,
                  a.existencia,
                  d.costo,
                  d.precio_venta
              FROM
                  existencias_bodegas as a,
                  inventarios_productos as b,
                  unidades as c,
                  inventarios as d
              WHERE
              a.empresa_id = '$empresa_id'
              AND a.centro_utilidad = '$centro_utilidad'
              AND a.bodega = '$bodega'
              ".$filtro." 
              AND b.codigo_producto = a.codigo_producto
              AND c.unidad_id = b.unidad_id
              AND d.empresa_id = a.empresa_id
              AND d.codigo_producto = a.codigo_producto
              AND a.estado = '1'
              ) AS t
              LEFT JOIN
              tmp_cuenta_insumos as a
              ON (a.numerodecuenta=".$filtros['SuperCuenta']."
                    AND t.codigo_producto=a.codigo_producto)
                WHERE a.numerodecuenta IS NULL
              limit ".$this->limit." OFFSET ".$this->offset."";
               //RETURN $sql;
              if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $productos=Array();
                  while(!$resultado->EOF)
                  {
                    $productos[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                  
                  return $productos;   
    }




    
    function ContarProStip($empresa_id,$centro_utilidad,$bodega,$filtros)
    {
         if(!empty($filtros['criterio']))
         {
            if($filtros['tip_bus']=='1')
            {
                $filtro="AND b.descripcion ILIKE '%".trim($filtros['criterio'])."%'";
            }
            
            elseif($filtros['tip_bus']=='2')
            {
                $filtro="AND b.codigo_producto ILIKE '%".trim($filtros['criterio'])."%'";
    
            }
            else
            {
                $filtro="";
            }
          }  
            $sql="SELECT
                    count(*)
                FROM
                  (SELECT
                    b.codigo_producto,
                    b.descripcion,
                    b.unidad_id,
                    c.descripcion as descripcion_unidad,
                    a.existencia,
                    d.costo,
                    d.precio_venta  
                    FROM
                    existencias_bodegas as a,
                    inventarios_productos as b,
                    unidades as c,
                    inventarios as d
                  WHERE
                a.empresa_id = '$empresa_id'
                AND a.centro_utilidad = '$centro_utilidad'
                AND a.bodega = '$bodega'
                ".$filtro."
                AND b.codigo_producto = a.codigo_producto
                AND c.unidad_id = b.unidad_id
                AND a.estado = '1'
                AND a.empresa_id = d.empresa_id
                AND a.codigo_producto = d.codigo_producto) AS t
                LEFT JOIN
                tmp_cuenta_insumos as a
                ON (a.numerodecuenta=".$filtros['SuperCuenta']."
                AND t.codigo_producto=a.codigo_producto)
                WHERE a.numerodecuenta IS NULL";

            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }

                $resultado->Close();

                return $cuentas;   
    }


           

    /**
    * Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
    * importantes a la hora de referenciar al paginador
    * 
    * @param String Cadena que contiene la consulta sql del conteo
    * @param int numero que define el limite de datos,cuando no se desa el del
    *        usuario,si no se pasa se tomara por defecto el del usuario
    * @return boolean
    **/
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
      
      
      return true;
    }

 
    /**
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la
    * consulta sql
    *
    * @param  string  $sql  sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
    * @return rst
    **/
    function ConexionBaseDatos($sql)
    {
      list($dbconn)=GetDBConn();
      //$dbconn->debug=true;
      $rst = $dbconn->Execute($sql);
        
      if ($dbconn->ErrorNo() != 0)
      {
        $this->mensajeError = "ERROR DB : " . $dbconn->ErrorMsg();
         "<b class=\"label\">".$this->frmError."</b>";
        return false;
      }
      return $rst;
    }




     

}//end of class

?>