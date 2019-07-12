 <?php

/**
 * $Id: malla_validadora.inc.php,v 1.24 2005/10/24 23:39:57 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Malla de validacion automatica para cargos.
 */

/** CVS INFORMACION ******************************************************
* $RCSfile: malla_validadora.inc.php,v $
* $Source: /SIIS/cvsroot/SIIS/includes/malla_validadora.inc.php,v $
* $Revision: 1.24 $
* $Date: 2005/10/24 23:39:57 $
**************************************************************************
* $Id: malla_validadora.inc.php,v 1.24 2005/10/24 23:39:57 darling Exp $
**************************************************************************/

function MallaValidadora($evolucion)
{
    if(empty($evolucion)){
        return false;
    }

    //echo "Iniciar MallaValidadora<br>";
    list($dbconn) = GetDBconn();
    global $ADODB_FETCH_MODE;

    //Buscar si la evolucion tiene numero de cuenta.
    $query = "SELECT a.numerodecuenta,a.fecha,b.servicio, a.ingreso
                FROM hc_evoluciones AS a, departamentos AS b
                WHERE evolucion_id=$evolucion
                AND a.departamento = b.departamento";

    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    if($resultado->EOF){
        return false;
    }

    list($numero_cuenta,$fecha_evolucion,$servicio_evolucion,$ingreso)=$resultado->FetchRow();
    $resultado->Close();

    //traer los datos de la cuenta
    $datosCuenta = MallaValidadoraGetDatosCuenta($numero_cuenta);

    if(!is_array($datosCuenta) || empty($datosCuenta))
    {
        return false;
    }

        //cambio dar
        //busca si es plan con bd y valida el estado en bd
    $query = "SELECT a.tipo_id_paciente, a.paciente_id, b.plan_id,c.sw_afiliacion
                            FROM ingresos as a, cuentas as b, planes as c
                            WHERE a.ingreso=$ingreso AND a.ingreso=b.ingreso
                            and b.plan_id=c.plan_id";
    $resultado = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        return false;
    }
    if($resultado->EOF){
        return false;
    }
    list($tipo_id_paciente,$paciente_id,$plan,$sw_afiliados)=$resultado->FetchRow();
    $resultado->Close();
        //tiene base de datos
        if($sw_afiliados==1)
        {
                    if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
                    {
                            $this->error = "Error";
                            $this->mensajeDeError = "NO SE PUDO INCLUIR : classes/notas_enfermeria/revision_sistemas.class.php";
                            return false;
                    }
                    if(!class_exists('BDAfiliados'))
                    {
                            $this->error="Error";
                            $this->mensajeDeError="NO EXISTE BD AFILIADOS";
                            return false;
                    }

                    $class= New BDAfiliados($tipo_id_paciente,$paciente_id,$plan);
                    $class->GetDatosAfiliado();
                    if($class->GetDatosAfiliado()==false)
                    {
                                $this->frmError["MensajeError"]=$class->mensajeDeError;
                    }

                    if(!empty($class->salida))
                    {
                            $arreglo=$class->salida;
                            //en 1 esta activo
                            if($arreglo['campo_activo']!=1)
                            {
                                    return false;
                            }
                    }
        }
        //fin cambio dar

    //Buscar las solicitudes realizadas en la evolucion actual
    $query = "SELECT cargo,hc_os_solicitud_id,cantidad,sw_estado
							FROM hc_os_solicitudes
							WHERE evolucion_id = $evolucion AND sw_estado in('1','3')";

    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $resultado = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    if($resultado->EOF){
        $resultado->Close();
        return false;
    }

    $cargos_autorizadados = array();

    while($cargo_solicitud = $resultado->FetchRow())
    {
				//si es una solicitud ambulatoria en servicio es el 3 ambulatorio
				if($cargo_solicitud[sw_ambulatorio]==1)
				{   $servicio = 3;  }
				else
				{		$servicio = $servicio_evolucion;	}
				
        $cargo_autorizado = MallaValidadoraValidarCargo($cargo_solicitud[cargo],$datosCuenta[plan_id],$servicio,$cargo_solicitud[hc_os_solicitud_id],$cargo_solicitud[cantidad],$cargo_solicitud[sw_estado]);

        if(is_array($cargo_autorizado)){
            $cargos_autorizadados[$servicio][$cargo_autorizado[departamento]][]=$cargo_autorizado;
            //$cargos_autorizadados[$servicio][]=$cargo_autorizado;
        }
    }

    $resultado->Close();

    $emp = BuscarEmpleadorIngreso($evolucion);

    foreach($cargos_autorizadados as $k=>$v)
		{
		    foreach($v as $k1=>$v1)
				{
						//$servicio antes estaba $k, $k es el servicio antes estaba $servicio_evolucion
						MallaValidadoraGenerarOS($v1,
																		$datosCuenta[tipo_id_paciente],
																		$datosCuenta[paciente_id],
																		$datosCuenta[plan_id],
																		$datosCuenta[tipo_afiliado_id],
																		$datosCuenta[rango],
																		$datosCuenta[semanas_cotizadas],
																		$k,
																		$fecha_evolucion,
																		$emp[tipo_id_empleador],
																	$emp[empleador_id]);
				}												
    }
    return true;

}//fin MallaValidadora

//busca el empleador q tenga el ingreso
function BuscarEmpleadorIngreso($evolucion)
{
            list($dbconn) = GetDBconn();
            $query = "SELECT a.* FROM ingresos_empleadores as a, hc_evoluciones as b
                                WHERE b.evolucion_id=$evolucion and b.ingreso=a.ingreso";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            $vars=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $vars;
}

//Funciones para las Solicitudes de Cargos de forma manual
//Argumentos:
//Plan,Servicios
//Cargos array (hc_os_solicitud_id,cargo,cantidad)

function MallaValidadoraSolicitudesManuales($plan_id,$Servicio,$cargos)
{
    foreach($cargos as $k=>$v){
        $cargo_autorizado = MallaValidadoraValidarCargo($v[cargo],$plan_id,$Servicio,$v[hc_os_solicitud_id],$v[cantidad]);
        if($cargo_autorizado){
            $cargos_autorizadados[$cargo_autorizado[departamento]][]=$cargo_autorizado;
        }
    }
    return $cargos_autorizadados;
}

function MallaValidadoraValidarCargo($cargo_base,$plan_id,$Servicio,$hc_os_solicitud_id=0,$cantidad=1,$sw_estado)
{
    if(empty($cargo_base) || empty($plan_id) || empty($Servicio)){
        return "DATOS INCOMPLETOS PARA LA VALIDACION EN LA MALLA.";
    }

    list($dbconn) = GetDBconn();
    global $ADODB_FETCH_MODE;

    $query = "SELECT a.tarifario_id, a.cargo

            FROM tarifarios_detalle AS a, plan_tarifario AS b,
            (SELECT tarifario_id,cargo FROM tarifarios_equivalencias
            WHERE cargo_base='$cargo_base') AS c

            WHERE b.plan_id = $plan_id
            AND b.tarifario_id = a.tarifario_id
            AND b.grupo_tarifario_id = a.grupo_tarifario_id
            AND b.subgrupo_tarifario_id = a.subgrupo_tarifario_id
            AND c.tarifario_id =  a.tarifario_id
            AND c.cargo = a.cargo";

        $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    $NumeroCargos=$resultado->RecordCount();
    if($NumeroCargos == 0){
        $resultado->Close();
        return "EL CARGO $cargo_base NO TIENE EQUIVALENCIAS O NO ESTA CONTRATADO";
    }

    if($NumeroCargos > 1){
        $resultado->Close();
        return "EL CARGO $cargo_base TIENE $NumeroCargos EQUIVALENCIAS";
    }

    list($tarifario,$cargo)=$resultado->FetchRow();
    $resultado->Close();

    $query = "
                SELECT b.sw_no_contratado, b.por_cobertura
                FROM tarifarios_detalle a, excepciones b
                WHERE

                b.plan_id = $plan_id AND
                a.tarifario_id = '$tarifario' AND
                a.cargo = '$cargo' AND
                b.tarifario_id = a.tarifario_id AND
                b.cargo = a.cargo ";

    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    if(!$resultado->EOF){

        list($sw_no_contratado,$cobertura)=$resultado->FetchRow();
        $resultado->Close();

        if($sw_no_contratado){
            return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario ESTA MARCADO COMO NO CONTRATADO EN LA TABLA DE EXCEPCIONES.";
        }

        if($cobertura != 100 && $sw_estado!='3'){
            return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario SOLO TIENE COBERTURA DEL ".round($cobertura,2)." % EN LA TABLA DE EXCEPCIONES.";
        }

    }else{

        $resultado->Close();
        $query = "
                    SELECT b.por_cobertura
                    FROM tarifarios_detalle a, plan_tarifario b
                    WHERE
                    b.plan_id = $plan_id and
                    a.tarifario_id = '$tarifario' AND
                    a.cargo = '$cargo' AND
                    b.grupo_tarifario_id = a.grupo_tarifario_id AND
                    b.subgrupo_tarifario_id = a.subgrupo_tarifario_id AND
                    b.tarifario_id = a.tarifario_id ";

        $resultado = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            return "SQL " . $dbconn->ErrorMsg();
        }

        if($resultado->EOF){
            return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario NO ESTA INCLUIDO EN EL PLAN TARIFARIO.";
        }

        list($cobertura)=$resultado->FetchRow();
        $resultado->Close();

        if($cobertura != 100 && $sw_estado!='3'){
            return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario SOLO TIENE COBERTURA DEL ".round($cobertura,2)." % EN EL PLAN TARIFARIO.";
        }
    }

        //cambio dar
    $query = "SELECT count(*)
                FROM excepciones_aut_int
                WHERE plan_id = $plan_id AND
                cargo = '$cargo_base' AND
                servicio = $Servicio AND
                sw_autorizado=0";
        //asi estaba salia error
   /* $query = "SELECT count(*)
                FROM excepciones_aut_int
                WHERE plan_id = $plan_id AND
                tarifario_id = '$tarifario' AND
                cargo = '$cargo' AND
                servicio = $Servicio AND
                sw_autorizado=0";*/

    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    list($NumeroCargos)=$resultado->FetchRow();
    $resultado->Close();

    if($NumeroCargos != 0){
        return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario REQUIERE AUTORIZACION INTERNA (EXCEPCION) EN EL SERVICIO $Servicio";
    }

    $query = "SELECT count(*)
                FROM tarifarios_detalle as a, planes_autorizaciones_int as b
                WHERE b.plan_id = $plan_id AND
                a.tarifario_id = '$tarifario' AND
                a.cargo = '$cargo' AND
                b.servicio = $Servicio AND
                a.grupo_tipo_cargo = b.grupo_tipo_cargo AND
                a.tipo_cargo=b.tipo_cargo AND
                a.nivel = b.nivel";

    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    list($NumeroCargos)=$resultado->FetchRow();
    $resultado->Close();

    if($NumeroCargos != 0){
        return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario REQUIERE AUTORIZACION INTERNA (GRUPO)";
    }

        //cambio dar
    $query = "SELECT count(*)
                FROM excepciones_aut_ext
                WHERE plan_id = $plan_id AND
                cargo = '$cargo_base' AND
                servicio = $Servicio AND
                sw_autorizado=0";
        //asi estaba salia error

   /* $query = "SELECT count(*)
                FROM excepciones_aut_ext
                WHERE plan_id = $plan_id AND
                tarifario_id = '$tarifario' AND
                cargo = '$cargo' AND
                servicio = $Servicio AND
                sw_autorizado=0";*/

    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    list($NumeroCargos)=$resultado->FetchRow();
    $resultado->Close();

    if($NumeroCargos != 0){
        return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario REQUIERE AUTORIZACION EXTERNA (EXCEPCION)";
    }

    $query = "SELECT count(*)
                FROM tarifarios_detalle as a, planes_autorizaciones_ext as b
                WHERE b.plan_id = $plan_id AND
                a.tarifario_id = '$tarifario' AND
                a.cargo = '$cargo' AND
                b.servicio = $Servicio AND
                a.grupo_tipo_cargo = b.grupo_tipo_cargo AND
                a.tipo_cargo=b.tipo_cargo AND
                a.nivel = b.nivel";

    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    list($NumeroCargos)=$resultado->FetchRow();
    $resultado->Close();

    if($NumeroCargos != 0){
        return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario REQUIERE AUTORIZACION EXTERNA (GRUPO)";
    }
//OJO REVISAR AQUI
        //cambio dar
        $dat='';
        $dat = DatosSolicitud($hc_os_solicitud_id);
        $filtro=ModuloGetVar('app','CentroAutorizacion','filtro_os');

        //es una solicitud manual y no eligieron el depto y se hace igual como si fuera por empresa
        if(empty($dat) OR $filtro=='empresa')
        {
                $query="SELECT departamento
                                FROM departamentos_cargos WHERE cargo='$cargo_base'
                                AND departamento IN (SELECT departamento
                                                                                FROM departamentos
                                                                                WHERE empresa_id=(SELECT empresa_id FROM planes where plan_id='$plan_id'))";
                $resultado = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        return "SQL " . $dbconn->ErrorMsg();
                }

                $NoDptosCargo=$resultado->RecordCount();
        }
        elseif(!empty($dat))
        {       //si tiene dpto, hay q validar el sw_escojer
                $escojer='';
                $escojer = DptoEscojerOS($dat['departamento'],$cargo_base);

                //el sw_escojer esta en 0 para este dpto, osea q la os va con este dpto
                if(!empty($escojer))
                {
                        $departamento = $escojer;
                        $NoDptosCargo=1;
                }
                else
                {       //esta en 1 hay q seguir evaluando el filtro
                        if($filtro=='centro')
                        {
                                $query="SELECT departamento
                                                FROM departamentos_cargos WHERE cargo='$cargo_base'
                                                AND departamento IN
                                                 ( SELECT departamento FROM departamentos
                                                     WHERE centro_utilidad='".$dat[centro_utilidad]."'
                                                      and empresa_id= (SELECT empresa_id FROM planes where plan_id='$plan_id'))";
                                $resultado = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                        return "SQL " . $dbconn->ErrorMsg();
                                }

                                $NoDptosCargo=$resultado->RecordCount();
                        }
                        elseif($filtro=='unidad')
                        {
                                $query="SELECT departamento
                                                FROM departamentos_cargos WHERE cargo='$cargo_base'
                                                AND departamento IN
                                                 ( SELECT departamento FROM departamentos
                                                     WHERE unidad_funcional='".$dat[unidad_funcional]."'
                                                      and empresa_id= (SELECT empresa_id FROM planes where plan_id='$plan_id'))";
                                $resultado = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                        return "SQL " . $dbconn->ErrorMsg();
                                }

                                $NoDptosCargo=$resultado->RecordCount();
                        }
                        elseif($filtro=='departamento')
                        {
                                $query="SELECT departamento
                                                FROM departamentos_cargos WHERE cargo='$cargo_base'
                                                AND departamento IN
                                                 ( SELECT departamento FROM departamentos
                                                     WHERE departamento='".$dat[departamento]."'
                                                      and empresa_id= (SELECT empresa_id FROM planes where plan_id='$plan_id'))";
                                $resultado = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                        return "SQL " . $dbconn->ErrorMsg();
                                }

                                $NoDptosCargo=$resultado->RecordCount();
                        }
                }
        }

   /* $query="SELECT departamento
            FROM departamentos_cargos WHERE cargo='$cargo_base'
            AND departamento IN (SELECT departamento
                                    FROM departamentos
                                    WHERE empresa_id=(SELECT empresa_id FROM planes where plan_id='$plan_id'))";
    $resultado = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    $NoDptosCargo=$resultado->RecordCount();*/

    if($NoDptosCargo == 0){
        $resultado->Close();
        return "NO ESTA PARAMETRIZADO NINGUN DEPARTAMENTO QUE PRESTE EL SERVICIO DEL CARGO $cargo_base";
    }

    if($NoDptosCargo > 1){
        $resultado->Close();
        return "MULTIPLES DEPARTAMENTOS DE LA EMPRESA PRESTAN EL CARGO $cargo_base NO SE SABE CUAL ESCOGER";
    }

        if(empty($departamento))
        {
            list($departamento)=$resultado->FetchRow();
            $resultado->Close();
        }

    if(!empty($departamento)){
                    if(!empty($hc_os_solicitud_id))
                    {
                        $query = "SELECT sw_estado FROM hc_os_solicitudes WHERE hc_os_solicitud_id=$hc_os_solicitud_id";
                        $resultado = $dbconn->Execute($query);

                        if ($dbconn->ErrorNo() != 0) {
                                return "SQL " . $dbconn->ErrorMsg();
                        }

                        list($estado_solicitud) = $resultado->FetchRow();
                    }

        return array('cargo_cups'=>$cargo_base,'cargos'=>array('0'=>array('tarifario'=>$tarifario,'cargo'=>$cargo)),'departamento'=>$departamento,'cantidad'=>$cantidad,'hc_os_solicitud_id'=>$hc_os_solicitud_id,'sw_estado_solicitud'=>$estado_solicitud,'servicio'=>$Servicio);
    }

    return "ERROR AL INTENTAR ASIGNAR LA ORDEN DE SERVICIO A UN DEPARTAMENTO.";
}

function DptoEscojerOS($departamento,$cargo)
{
            list($dbconn) = GetDBconn();
            $query = "SELECT departamento FROM departamentos_cargos
                                WHERE departamento='$departamento'
                                and sw_escojer ='0' and cargo='$cargo'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            $vars=$result->fields[0];
            $result->Close();
            return $vars;
}


function DatosSolicitud($solicitud)
{
            list($dbconn) = GetDBconn();
            $query = "SELECT evolucion_id
                                FROM hc_os_solicitudes WHERE hc_os_solicitud_id=$solicitud";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }

            // es desde la hc
            if(!empty($result->fields[0]))
            {
                        $query = "SELECT a.departamento, b.centro_utilidad, b.unidad_funcional, b.empresa_id
                                            FROM hc_evoluciones as a, departamentos as b
                                            WHERE a.evolucion_id=".$result->fields[0]." and a.departamento=b.departamento";
                        $results = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
            }
            else
            {       // es manual
                        $query = "SELECT a.departamento, b.centro_utilidad, b.unidad_funcional, b.empresa_id
                                            FROM  hc_os_solicitudes_manuales as a, departamentos as b
                                            WHERE a.hc_os_solicitud_id=$solicitud and a.departamento=b.departamento";
                        $results = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
            }

            $result->Close();
            if(!$results->EOF)
            {  $vars=$results->GetRowAssoc($ToUpper = false);  }
            $results->Close();
            return $vars;
}

//funcion para traer los datos de una cuenta que se necesitan en la malla validadora.
function MallaValidadoraGetDatosCuenta($cuenta=0)
{
    if(empty($cuenta))
    {
        return false;
    }

    static $DatosCuenta = array();

    if (isset($DatosCuenta[$cuenta])) {
        return $DatosCuenta[$cuenta];
    }

    list($dbconn) = GetDBconn();
    global $ADODB_FETCH_MODE;

    $query="SELECT a.ingreso, a.numerodecuenta, a.empresa_id, a.plan_id, a.tipo_afiliado_id, a.rango,
            a.semanas_cotizadas , b.tipo_id_paciente, b.paciente_id
            FROM cuentas a, ingresos b
            WHERE
            a.ingreso = b.ingreso
            and a.numerodecuenta=$cuenta";

    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $resultado = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

    if ($dbconn->ErrorNo() != 0) {
        return false;
    }

    if($resultado->EOF){
        return false;
    }
    $row0=$resultado->GetRows();
    $resultado->Close();

    $DatosCuenta[$cuenta]=$row0[0];

    return $DatosCuenta[$cuenta];

}//fin MallaValidadoraGetDatosCuenta



function MallaValidadoraGenerarOS($cargos,$tipo_id_paciente,$paciente_id,$plan,$tipo_afiliado,$rango,$semanas_cotizadas,$servicio,$fecha_solicitud,$tipo_empleador='',$empleador_id='')
{
//echo "tipo emp=>".$tipo_empleador."  emp id=>".$empleador_id;
        list($dbconn) = GetDBconn();
        $query="SELECT nextval('os_ordenes_servicios_orden_servicio_id_seq')";
        $result=$dbconn->Execute($query);
        $orden=$result->fields[0];
        $query = "INSERT INTO os_ordenes_servicios
                                                                    (orden_servicio_id,
                                                                    autorizacion_int,
                                                                    autorizacion_ext,
                                                                    plan_id,
                                                                    tipo_afiliado_id,
                                                                    rango,
                                                                    semanas_cotizadas,
                                                                    servicio,
                                                                    tipo_id_paciente,
                                                                    paciente_id,
                                                                    usuario_id,
                                                                    fecha_registro,
                                                                    observacion)
        VALUES($orden,1,1,".$plan.",'".$tipo_afiliado."',
        '".$rango."',".$semanas_cotizadas.",'".$servicio."','".$tipo_id_paciente."','".$paciente_id."',".UserGetUID().",'now()','')";
        $dbconn->BeginTrans();
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                        $dbconn->RollbackTrans();
                        //echo "salida 1 <br>";
                        return false;
        }
                //cambio dar
                //INSERTAR EL EMPLEADOR
                if(!empty($tipo_empleador) AND !empty($empleador_id))
                {
                            $query = "INSERT INTO os_ordenes_servicios_empleadores(
                                                                                                            orden_servicio_id,
                                                                                                            tipo_id_empleador,
                                                                                                            empleador_id)
                                                VALUES($orden,'$tipo_empleador','$empleador_id')";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error INSERT INTO os_ordenes_servicios_empleadores ";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                            }
                }
                //fin cambio dar

        //para insertar en os_maestro y os_maestro_cargos
        foreach($cargos as $k=>$v)
        {
                        $query = "select * from os_tipos_periodos_planes
                                                                                                        where plan_id=".$plan."
                                                                                                        and cargo='".$v[cargo_cups]."'";
                        $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                                        //echo "salida 2 <br>";
                                                        return false;
                        }
                        if(!$result->EOF)
                        {
                                $var=$result->GetRowAssoc($ToUpper = false);
                                $Fecha=MallaValidadoraFechaStamp($fecha_solicitud);
                                $infoCadena = explode ('/',$Fecha);
                                $intervalo=MallaValidadoraHoraStamp($fecha_solicitud);
                                $infoCadena1 = explode (':', $intervalo);
                                $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
                                if($fechaAct < date("Y-m-d H:i:s"))
                                {  $fechaAct=date("Y-m-d H:i:s");  }
                                $Fecha=MallaValidadoraFechaStamp($fechaAct);
                                $infoCadena = explode ('/',$Fecha);
                                $intervalo=MallaValidadoraHoraStamp($fechaAct);
                                $infoCadena1 = explode (':', $intervalo);
                                $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
                                //fecha refrendar
                                $Fecha=MallaValidadoraFechaStamp($venc);
                                $infoCadena = explode ('/',$Fecha);
                                $intervalo=MallaValidadoraHoraStamp($venc);
                                $infoCadena1 = explode (':', $intervalo);
                                $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                        }
                        else
                        {                //si no hay unos tiempos especificos para el cargo toma los genericos
                                $query = "select * from os_tipos_periodos_tramites
                                                                                                                where cargo='".$v[cargo_cups]."'";
                                $result=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                                //echo "salida 3 <br>";
                                                                return false;
                                }
                                if(!$result->EOF)
                                {
                                        $var=$result->GetRowAssoc($ToUpper = false);
                                        $Fecha=MallaValidadoraFechaStamp($fecha_solicitud);
                                        $infoCadena = explode ('/',$Fecha);
                                        $intervalo=MallaValidadoraHoraStamp($fecha_solicitud);
                                        $infoCadena1 = explode (':', $intervalo);
                                        $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
                                        if($fechaAct < date("Y-m-d H:i:s"))
                                        {  $fechaAct=date("Y-m-d H:i:s");  }
                                        $Fecha=MallaValidadoraFechaStamp($fechaAct);
                                        $infoCadena = explode ('/',$Fecha);
                                        $intervalo=MallaValidadoraHoraStamp($fechaAct);
                                        $infoCadena1 = explode (':', $intervalo);
                                        $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
                                        //fecha refrendar
                                        $Fecha=MallaValidadoraFechaStamp($venc);
                                        $infoCadena = explode ('/',$Fecha);
                                        $intervalo=MallaValidadoraHoraStamp($venc);
                                        $infoCadena1 = explode (':', $intervalo);
                                        $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                                }
                                else
                                {
                                        $tramite=ModuloGetVar('app','CentroAutorizacion','dias_tramite_os');
                                        $vigencia=ModuloGetVar('app','CentroAutorizacion','dias_vigencia');
                                        $var=$result->GetRowAssoc($ToUpper = false);
                                        $Fecha=MallaValidadoraFechaStamp($fecha_solicitud);
                                        $infoCadena = explode ('/',$Fecha);
                                        $intervalo=MallaValidadoraHoraStamp($fecha_solicitud);
                                        $infoCadena1 = explode (':', $intervalo);
                                        $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$tramite),$infoCadena[2]));
                                        if($fechaAct < date("Y-m-d H:i:s"))
                                        {  $fechaAct=date("Y-m-d H:i:s");  }
                                        $Fecha=MallaValidadoraFechaStamp($fechaAct);
                                        $infoCadena = explode ('/',$Fecha);
                                        $intervalo=MallaValidadoraHoraStamp($fechaAct);
                                        $infoCadena1 = explode (':', $intervalo);
                                        $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$vigencia),$infoCadena[2]));
                                        //fecha refrendar
                                        $Fecha=MallaValidadoraFechaStamp($venc);
                                        $infoCadena = explode ('/',$Fecha);
                                        $intervalo=MallaValidadoraHoraStamp($venc);
                                        $infoCadena1 = explode (':', $intervalo);
                                        $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                                }
                                $query="SELECT nextval('os_maestro_numero_orden_id_seq')";
                                $result=$dbconn->Execute($query);
                                $numorden=$result->fields[0];

                                                                        $estado=1;
                                                                        if($v[sw_estado_solicitud]=='3')
                                                                        {  $estado=0; }

                                                                if(empty($v[cargo_cups]))
                                                                {  $v[cargo_cups]=1;  }

                                $query = "INSERT INTO os_maestro
                                                                        (numero_orden_id,
                                                                        orden_servicio_id,
                                                                        sw_estado,
                                                                        fecha_vencimiento,
                                                                        hc_os_solicitud_id,
                                                                        fecha_activacion,
                                                                        cantidad,
                                                                        cargo_cups,
                                                                        fecha_refrendar)
                                                                VALUES($numorden,$orden,'$estado','$venc',".$v[hc_os_solicitud_id].",'$fechaAct',".$v[cantidad].",'".$v[cargo_cups]."','$refrendar')";
                                $result=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                                $dbconn->RollbackTrans();
                                                                //echo "salida 4 <br>";
                                                                return false;
                                }
                                //insertar en hc_os_autorizaciones para que le aparezca a claudia
                                $query = "INSERT INTO hc_os_autorizaciones
                                                                                (autorizacion_int,autorizacion_ext,
                                                                                hc_os_solicitud_id)
                                                                        VALUES(1,1,'".$v[hc_os_solicitud_id]."')";
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                                $dbconn->RollbackTrans();
                                                                //echo "salida 5 <br>";
                                                                return false;
                                }

                                //for con los cargos equivalentes
                                foreach($v[cargos] as $x=>$y)
                                {
                                                        $query = "INSERT INTO os_maestro_cargos
                                                                                                                                                                        (numero_orden_id,
                                                                                                                                                                        tarifario_id,
                                                                                                                                                                        cargo)
                                                                                                VALUES($numorden,'".$y[tarifario]."','".$y[cargo]."')";
                                                        $dbconn->Execute($query);
                                                        if ($dbconn->ErrorNo() != 0) {
                                                                                        $dbconn->RollbackTrans();
                                                                                        //echo "salida 6<br>";
                                                                                        return false;
                                                        }
                                }

                                $query = "INSERT INTO os_internas(numero_orden_id,
                                                                                                        cargo,
                                                                                                        departamento)
                                                    VALUES($numorden,'".$v[cargo_cups]."','".$v[departamento]."')";
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                                $dbconn->RollbackTrans();
                                                                //echo "salida 7<br>";
                                                                return false;
                                }
                                //actualiza a 0 para indicar que ya paso por el proceso de autorizacion
                                $query = "UPDATE hc_os_solicitudes SET    sw_estado=0
                                                                                WHERE hc_os_solicitud_id=".$v[hc_os_solicitud_id]."";
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                                //echo "salida 8<br>";
                                                                $dbconn->RollbackTrans();
                                                                return false;
                                }
                        }
        }
    //echo "OK";
        $dbconn->CommitTrans();
    return true;
}

    /**
    * Separa la fecha del formato timestamp
    * @access private
    * @return string
    * @param date fecha
    */
     function MallaValidadoraFechaStamp($fecha)
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
    * Separa la hora del formato timestamp
    * @access private
    * @return string
    * @param date hora
    */
    function MallaValidadoraHoraStamp($hora)
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

//-------------------------------DARLING-------------------------------

//Funcion que revisa la autrizacion interna y externa de un cargo cups
function MallaValidadoraAutorizacionesCups($cargo_base,$plan_id,$Servicio)
{
    $resultado=array();
    $resultado['error']=false;
    $resultado['mensajeDeError']='';
    $resultado['fileError']='';
    $resultado['lineError']='';
    $resultado['Aut_int_excepcion']='0';
    $resultado['Aut_int_grupo']='0';
    $resultado['Aut_ext_excepcion']='0';
    $resultado['Aut_ext_grupo']='0';

    if(empty($cargo_base) || empty($plan_id) || empty($Servicio)){
        $resultado['error']='ERROR EN LA MALLA DE VALIDACION DE AUTORIZACIONES';
        $resultado['mensajeDeError']='Parametros de la funcion incompletos';
        $resultado['fileError']=__FILE__;
        $resultado['lineError']=__LINE__;
        return $resultado;
    }


    list($dbconn) = GetDBconn();
    $query = "SELECT count(*)
                FROM excepciones_aut_int
                WHERE plan_id = $plan_id AND
                cargo = '$cargo_base' AND
                servicio = '$Servicio' AND
                sw_autorizado='0'";

    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        $resultado['error']='ERROR EN LA MALLA DE VALIDACION DE AUTORIZACIONES';
        $resultado['mensajeDeError']="ERROR EN SQL " . $dbconn->ErrorMsg();
        $resultado['fileError']=__FILE__;
        $resultado['lineError']=__LINE__;
        return $resultado;
    }

    list($NumeroCargos)=$resultado->FetchRow();
    $resultado->Close();

    if($NumeroCargos = 0){
        $resultado['Aut_int_excepcion']='1';
    }


    $query = "SELECT count(*)
                FROM cups as a, planes_autorizaciones_int as b
                WHERE a.cargo = '$cargo_base' AND
                b.plan_id = $plan_id AND
                b.grupo_tipo_cargo=a.grupo_tipo_cargo AND
                b.tipo_cargo=a.tipo_cargo AND
                b.servicio = $Servicio AND
                b.nivel = a.nivel";


    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        $resultado['error']='ERROR EN LA MALLA DE VALIDACION DE AUTORIZACIONES';
        $resultado['mensajeDeError']="ERROR EN SQL " . $dbconn->ErrorMsg();
        $resultado['fileError']=__FILE__;
        $resultado['lineError']=__LINE__;
        return $resultado;
    }

    list($NumeroCargos)=$resultado->FetchRow();
    $resultado->Close();

    if($NumeroCargos != 0){
        $resultado['Aut_int_grupo']='1';
    }

    $query = "SELECT count(*)
                FROM excepciones_aut_ext
                WHERE plan_id = $plan_id AND
                cargo = '$cargo_base' AND
                servicio = '$Servicio' AND
                sw_autorizado='0'";


    $resultado = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        $resultado['error']='ERROR EN LA MALLA DE VALIDACION DE AUTORIZACIONES';
        $resultado['mensajeDeError']="ERROR EN SQL " . $dbconn->ErrorMsg();
        $resultado['fileError']=__FILE__;
        $resultado['lineError']=__LINE__;
        return $resultado;
    }

    list($NumeroCargos)=$resultado->FetchRow();
    $resultado->Close();

    if($NumeroCargos != 0){
        $resultado['Aut_ext_excepcion']='1';
    }

    $query = "SELECT count(*)
                FROM cups as a, planes_autorizaciones_ext as b
                WHERE a.cargo = '$cargo_base' AND
                b.plan_id = $plan_id AND
                b.grupo_tipo_cargo=a.grupo_tipo_cargo AND
                b.tipo_cargo=a.tipo_cargo AND
                b.servicio = $Servicio AND
                b.nivel = a.nivel";

    $resultado = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        $resultado['error']='ERROR EN LA MALLA DE VALIDACION DE AUTORIZACIONES';
        $resultado['mensajeDeError']="ERROR EN SQL " . $dbconn->ErrorMsg();
        $resultado['fileError']=__FILE__;
        $resultado['lineError']=__LINE__;
        return $resultado;
    }

    list($NumeroCargos)=$resultado->FetchRow();
    $resultado->Close();

    if($NumeroCargos != 0){
        $resultado['Aut_ext_grupo']='1';
    }

    return $resultado;

}//fin MallaValidadoraAutorizacionesCups



function MallaValidadoraCargoCups($cargo_base,$plan_id,$Servicio)
{
    $resultado=array();
    $resultado['error']=false;
    $resultado['mensajeDeError']='';
    $resultado['fileError']='';
    $resultado['lineError']='';
    $resultado['validacion']=true;
    $resultado['mensaje']='';
    $resultado['aut_int']='0';
    $resultado['aut_ext']='0';
    $resultado['validacion_cargar_cuenta']='0';

        //paso 1=>no equi o no contra  2=>multiples equi  3=>solo 1 equi

    if(empty($cargo_base) || empty($plan_id) || empty($Servicio)){
        $resultado['error']='ERROR EN LA MALLA DE VALIDACION DE CARGOS CUPS';
        $resultado['mensajeDeError']='Parametros de la funcion incompletos';
        $resultado['fileError']=__FILE__;
        $resultado['lineError']=__LINE__;

        $resultado['validacion']=false;
        $resultado['mensaje']='';
        $resultado['aut_int']='0';
        $resultado['aut_ext']='0';

        return $resultado;
    }

    $vector_autorizaciones = MallaValidadoraAutorizacionesCups($cargo_base,$plan_id,$Servicio);
    if($vector_autorizaciones['error'])
    {
        $resultado['error']=$vector_autorizaciones['error'];
        $resultado['mensajeDeError']=$vector_autorizaciones['mensajeDeError'];
        $resultado['fileError']=$vector_autorizaciones['fileError'];
        $resultado['lineError']=$vector_autorizaciones['lineError'];

        $resultado['validacion']=false;
        $resultado['mensaje']='';
        $resultado['aut_int']='0';
        $resultado['aut_ext']='0';

        return $resultado;
    }

    if($vector_autorizaciones['Aut_int_excepcion'] || $vector_autorizaciones['Aut_int_grupo'])
    {
        $resultado['aut_int']= '0';//SI SE REQUIERE AUTORIZACION MANDA COMO NUMERO DE AUT 0 (SIN AUTORIZAR)
    }
    else
    {
         $resultado['aut_int']= '1';//SI NO SE REQUIERE AUTORIZACION MANDA COMO NUMERO DE AUT 1 (AUT. POR SISTEMA)
    }

    if($vector_autorizaciones['Aut_ext_excepcion'] || $vector_autorizaciones['Aut_ext_grupo'])
    {
        $resultado['aut_ext']= '0';//SI SE REQUIERE AUTORIZACION MANDA COMO NUMERO DE AUT 0 (SIN AUTORIZAR)
    }
    else
    {
         $resultado['aut_ext']= '1';//SI NO SE REQUIERE AUTORIZACION MANDA COMO NUMERO DE AUT 1 (AUT. POR SISTEMA)
    }


    list($dbconn) = GetDBconn();
    global $ADODB_FETCH_MODE;

    $query = "SELECT a.tarifario_id, a.cargo
                FROM tarifarios_detalle AS a, plan_tarifario AS b,
                (SELECT tarifario_id,cargo FROM tarifarios_equivalencias
                WHERE cargo_base='$cargo_base') AS c
                WHERE b.plan_id = $plan_id
                AND b.tarifario_id = a.tarifario_id
                AND b.grupo_tarifario_id = a.grupo_tarifario_id
                AND b.subgrupo_tarifario_id = a.subgrupo_tarifario_id
                AND c.tarifario_id =  a.tarifario_id
                AND c.cargo = a.cargo";

    $resulta = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        $resultado['error']='ERROR EN LA MALLA DE VALIDACION DE CARGOS CUPS';
        $resultado['mensajeDeError'] = "ERROR EN SQL " . $dbconn->ErrorMsg();
        $resultado['fileError']=__FILE__;
        $resultado['lineError']=__LINE__;

        $resultado['validacion']=false;
        $resultado['mensaje']='';
        $resultado['aut_int']='0';
        $resultado['aut_ext']='0';

        return $resultado;
    }

    $NumeroCargos=$resulta->RecordCount();
    if($NumeroCargos == 0){
        $resulta->Close();

                    $resultado['validacion']=false;
                    $resultado['validacion_cargar_cuenta']=0;
                    $resultado['mensaje']="EL CARGO $cargo_base NO TIENE EQUIVALENCIAS O NO ESTA CONTRATADO";

        return $resultado;
    }

    if($NumeroCargos > 1){
        $resulta->Close();

        $resultado['validacion']=true;
                $resultado['validacion_cargar_cuenta']=0;
        $resultado['mensaje']="EL CARGO $cargo_base TIENE MULTIPLES EQUIVALENCIAS";

        return $resultado;
    }

        if($NumeroCargos == 1){
        $resulta->Close();
                $resultado['validacion_cargar_cuenta']=1;
        }

    list($tarifario,$cargo)=$resulta->FetchRow();
    $resulta->Close();
    $query = "
                SELECT b.sw_no_contratado, b.por_cobertura, a.descripcion
                FROM tarifarios_detalle a, excepciones b
                WHERE
                b.plan_id = $plan_id AND
                a.tarifario_id = '$tarifario' AND
                a.cargo = '$cargo' AND
                b.tarifario_id = a.tarifario_id AND
                b.cargo = a.cargo ";
    $resul = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        $resultado['error']='ERROR EN LA MALLA DE VALIDACION DE CARGOS CUPS';
        $resultado['mensajeDeError'] = "ERROR EN SQL " . $dbconn->ErrorMsg();
        $resultado['fileError']=__FILE__;
        $resultado['lineError']=__LINE__;

        $resultado['validacion']=false;
        $resultado['mensaje']='';
        $resultado['aut_int']='0';
        $resultado['aut_ext']='0';
    }

    if(!$resul->EOF){

        list($sw_no_contratado,$cobertura,$descripcion)=$resul->FetchRow();
        $resul->Close();

        if($sw_no_contratado){
                        $resultado['validacion']=true;
                        $resultado['validacion_cargar_cuenta']=0;
                        $resultado['mensaje']="EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario ESTA MARCADO COMO NO CONTRATADO EN LA TABLA DE EXCEPCIONES.";
                        return $resultado;
        }

        if($cobertura != 100){
                        $resultado['validacion']=true;
                        $resultado['validacion_cargar_cuenta']=0;
                        $resultado['mensaje']="EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario SOLO TIENE COBERTURA DEL ".round($cobertura,2)." % EN LA TABLA DE EXCEPCIONES.";
                        return $resultado;
        }

    }else{

        $resul->Close();
        $query = "
                    SELECT b.por_cobertura, a.descripcion
                    FROM tarifarios_detalle a, plan_tarifario b
                    WHERE
                    b.plan_id = $plan_id and
                    a.tarifario_id = '$tarifario' AND
                    a.cargo = '$cargo' AND
                    b.grupo_tarifario_id = a.grupo_tarifario_id AND
                    b.subgrupo_tarifario_id = a.subgrupo_tarifario_id AND
                    b.tarifario_id = a.tarifario_id ";

        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $resultado['error']='ERROR EN LA MALLA DE VALIDACION DE CARGOS CUPS';
            $resultado['mensajeDeError'] = "ERROR EN SQL " . $dbconn->ErrorMsg();
            $resultado['fileError']=__FILE__;
            $resultado['lineError']=__LINE__;

            $resultado['validacion']=false;
            $resultado['mensaje']='';
            $resultado['aut_int']='0';
            $resultado['aut_ext']='0';
        }

        if($result->EOF){
                        $resultado['validacion']=true;
                        $resultado['validacion_cargar_cuenta']=0;
                        $resultado['mensaje']="EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario NO ESTA INCLUIDO EN EL PLAN TARIFARIO.";

                        return $resultado;
        }

        list($cobertura,$descripcion)=$result->FetchRow();
        $result->Close();

        if($cobertura != 100){
                        $resultado['validacion']=true;
                        $resultado['validacion_cargar_cuenta']=0;
                        $resultado['mensaje']="EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario SOLO TIENE COBERTURA DEL ".round($cobertura,2)." % EN EL PLAN TARIFARIO.";
                        return $resultado;
        }

                $resultado['validacion']=true;
                return $resultado;
    }
        $resultado['validacion']=true;
        return $resultado;

}


function ValidarCargoMalla($cargo_base,$plan_id,$Servicio)
{
    if(empty($cargo_base) || empty($plan_id) || empty($Servicio)){
        return "DATOS INCOMPLETOS PARA LA VALIDACION EN LA MALLA.";
    }

    list($dbconn) = GetDBconn();
    global $ADODB_FETCH_MODE;

    $query = "SELECT a.tarifario_id, a.cargo
            FROM tarifarios_detalle AS a, plan_tarifario AS b,
            (SELECT tarifario_id,cargo FROM tarifarios_equivalencias
            WHERE cargo_base='$cargo_base') AS c
            WHERE b.plan_id = $plan_id
            AND b.tarifario_id = a.tarifario_id
            AND b.grupo_tarifario_id = a.grupo_tarifario_id
            AND b.subgrupo_tarifario_id = a.subgrupo_tarifario_id
            AND c.tarifario_id =  a.tarifario_id
            AND c.cargo = a.cargo";
        $resultado = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    $NumeroCargos=$resultado->RecordCount();
    if($NumeroCargos == 0){
        $resultado->Close();
        return "EL CARGO $cargo_base NO TIENE EQUIVALENCIAS O NO ESTA CONTRATADO";
    }

    if($NumeroCargos > 1){
        $resultado->Close();
                //VARIAS EQUIVALENCIAS
        return 2;
    }

    list($tarifario,$cargo)=$resultado->FetchRow();
    $resultado->Close();
    $query = "
                SELECT b.sw_no_contratado, b.por_cobertura, a.descripcion
                FROM tarifarios_detalle a, excepciones b
                WHERE
                b.plan_id = $plan_id AND
                a.tarifario_id = '$tarifario' AND
                a.cargo = '$cargo' AND
                b.tarifario_id = a.tarifario_id AND
                b.cargo = a.cargo ";
    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    if(!$resultado->EOF){

        list($sw_no_contratado,$cobertura,$descripcion)=$resultado->FetchRow();
        $resultado->Close();

        if($sw_no_contratado){
            return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario ESTA MARCADO COMO NO CONTRATADO EN LA TABLA DE EXCEPCIONES.";
        }

        if($cobertura != 100){
            return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario SOLO TIENE COBERTURA DEL ".round($cobertura,2)." % EN LA TABLA DE EXCEPCIONES.";
        }

    }else{

        $resultado->Close();
        $query = "
                    SELECT b.por_cobertura, a.descripcion
                    FROM tarifarios_detalle a, plan_tarifario b
                    WHERE
                    b.plan_id = $plan_id and
                    a.tarifario_id = '$tarifario' AND
                    a.cargo = '$cargo' AND
                    b.grupo_tarifario_id = a.grupo_tarifario_id AND
                    b.subgrupo_tarifario_id = a.subgrupo_tarifario_id AND
                    b.tarifario_id = a.tarifario_id ";

        $resultado = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            return "SQL " . $dbconn->ErrorMsg();
        }

        if($resultado->EOF){
            return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario NO ESTA INCLUIDO EN EL PLAN TARIFARIO.";
        }

        list($cobertura,$descripcion)=$resultado->FetchRow();
        $resultado->Close();

        if($cobertura != 100){
            return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario SOLO TIENE COBERTURA DEL ".round($cobertura,2)." % EN EL PLAN TARIFARIO.";
        }
    }

        //cambio dar
    $query = "SELECT count(*)
                FROM excepciones_aut_int
                WHERE plan_id = $plan_id AND
                cargo = '$cargo_base' AND
                servicio = $Servicio AND
                sw_autorizado=0";
        //asi estaba salia error
   /* $query = "SELECT count(*)
                FROM excepciones_aut_int
                WHERE plan_id = $plan_id AND
                tarifario_id = '$tarifario' AND
                cargo = '$cargo' AND
                servicio = $Servicio AND
                sw_autorizado=0";*/

    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    list($NumeroCargos)=$resultado->FetchRow();
    $resultado->Close();

    if($NumeroCargos != 0){
        return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario REQUIERE AUTORIZACION INTERNA (EXCEPCION) EN EL SERVICIO $Servicio";
    }

    $query = "SELECT count(*)
                FROM tarifarios_detalle as a, planes_autorizaciones_int as b
                WHERE b.plan_id = $plan_id AND
                a.tarifario_id = '$tarifario' AND
                a.cargo = '$cargo' AND
                b.servicio = $Servicio AND
                a.grupo_tipo_cargo = b.grupo_tipo_cargo AND
                a.tipo_cargo=b.tipo_cargo AND
                a.nivel = b.nivel";
    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    list($NumeroCargos)=$resultado->FetchRow();
    $resultado->Close();

    if($NumeroCargos != 0){
        return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario REQUIERE AUTORIZACION INTERNA (GRUPO)";
    }

        //cambio dar
    $query = "SELECT count(*)
                FROM excepciones_aut_ext
                WHERE plan_id = $plan_id AND
                cargo = '$cargo_base' AND
                servicio = $Servicio AND
                sw_autorizado=0";
        //asi estaba
    /*$query = "SELECT count(*)
                FROM excepciones_aut_ext
                WHERE plan_id = $plan_id AND
                tarifario_id = '$tarifario' AND
                cargo = '$cargo' AND
                servicio = $Servicio AND
                sw_autorizado=0";*/
    $resultado = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    list($NumeroCargos)=$resultado->FetchRow();
    $resultado->Close();

    if($NumeroCargos != 0){
        return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario REQUIERE AUTORIZACION EXTERNA (EXCEPCION)";
    }

    $query = "SELECT count(*)
                FROM tarifarios_detalle as a, planes_autorizaciones_ext as b
                WHERE b.plan_id = $plan_id AND
                a.tarifario_id = '$tarifario' AND
                a.cargo = '$cargo' AND
                b.servicio = $Servicio AND
                a.grupo_tipo_cargo = b.grupo_tipo_cargo AND
                a.tipo_cargo=b.tipo_cargo AND
                a.nivel = b.nivel";

    $resultado = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    list($NumeroCargos)=$resultado->FetchRow();
    $resultado->Close();

    if($NumeroCargos != 0){
        return "EL CARGO EQUIVALENTE $cargo DEL TARIFARIO $tarifario REQUIERE AUTORIZACION EXTERNA (GRUPO)";
    }
//OJO REVISAR AQUI
    $query="SELECT departamento
            FROM departamentos_cargos WHERE cargo='$cargo_base'
            AND departamento IN (SELECT departamento
                                    FROM departamentos
                                    WHERE empresa_id=(SELECT empresa_id FROM planes where plan_id='$plan_id'))";

    $resultado = $dbconn->Execute($query);

    if ($dbconn->ErrorNo() != 0) {
        return "SQL " . $dbconn->ErrorMsg();
    }

    $NoDptosCargo=$resultado->RecordCount();

    if($NoDptosCargo == 0){
        $resultado->Close();
        return "NO ESTA PARAMETRIZADO NINGUN DEPARTAMENTO QUE PRESTE EL SERVICIO DEL CARGO $cargo_base";
    }


    list($departamento)=$resultado->FetchRow();
    $resultado->Close();

    if(!empty($departamento)){
                //PASO LA MALLA

        return array('cargo_cups'=>$cargo_base,'tarifario'=>$tarifario,'cargo'=>$cargo,'descripcion'=>$descripcion);
    }

    return "ERROR.";
}

    /**
    *
    */
    function GenerarVariasOS($arreglo,$datos,$autorizacion,$ext,$tipo,$paciente,$plan,$afiliado,$rango,$semana,$servicio,$msg,$empresa,$trascripcion)
    {
                foreach($arreglo as $key => $value)
                {
                        //AQUI INSERTO LA ORDEN
                        list($dbconn) = GetDBconn();
                        $query="SELECT nextval('os_ordenes_servicios_orden_servicio_id_seq')";
                        $result=$dbconn->Execute($query);
                        $orden=$result->fields[0];
                        $query = "INSERT INTO os_ordenes_servicios
                                                                                (orden_servicio_id,
                                                                                autorizacion_int,
                                                                                autorizacion_ext,
                                                                                plan_id,
                                                                                tipo_afiliado_id,
                                                                                rango,
                                                                                semanas_cotizadas,
                                                                                servicio,
                                                                                tipo_id_paciente,
                                                                                paciente_id,
                                                                                usuario_id,
                                                                                fecha_registro,
                                                                                observacion)
                        VALUES($orden,".$autorizacion.",$ext,".$plan.",'".$afiliado."',
                        '".$rango."',".$semana.",'".$servicio."','".$tipo."','".$paciente."',".UserGetUID().",'now()','".$msg."')";
                        $dbconn->BeginTrans();
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                                        $this->error = "Error INSERT INTO os_ordenes_servicios";
                                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                        $dbconn->RollbackTrans();
                                                        return false;
                        }
                        $ordenes[]=$orden;
                        //DATOS PARA OS_MAESTRO

                        for($i=0; $i<sizeof($value); $i++)
                        {
                                //0 hc_os_solicitud_id 2 tipo_id_tercero o dpto 1 tercero_id o departo si es departamento(interna)
                                //3 tarifario 4 cargo 5 cargocups 6 fecha 7 plan_proveedor 8 cantidad
                                $vect=explode(',',$value[$i]);
                                foreach($datos as $k => $v)
                                {
                                                if(substr_count($k,'Combo'))
                                                {    //0 hc_os_solicitud_id 2 tipo_id_tercero o dpto 1 tercero_id o departo si es departamento(interna)
                                                                //3 tarifario 4 cargo 5 cargocups 6 fecha 7 plan_proveedor 8 cantidad
                                                                $arr=explode(',',$v);
                                                                if($vect[0]==$arr[0])
                                                                {
                                                                                for($j=0; $j<$arr[8]; $j++)
                                                                                {
                                                                                                $query = "select * from os_tipos_periodos_planes
                                                                                                                                                                                where plan_id=".$plan."
                                                                                                                                                                                and cargo='$arr[5]'";
                                                                                                $result=$dbconn->Execute($query);
                                                                                                if ($dbconn->ErrorNo() != 0) {
                                                                                                                                $this->error = "Error os_tipos_periodos_planes";
                                                                                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                                                                                return false;
                                                                                                }
                                                                                                if(!$result->EOF)
                                                                                                {
                                                                                                            $var=$result->GetRowAssoc($ToUpper = false);
                                                                                                            $Fecha=MallaValidadoraFechaStamp($arr[6]);
                                                                                                            $infoCadena = explode ('/',$Fecha);
                                                                                                            $intervalo=MallaValidadoraHoraStamp($arr[6]);
                                                                                                            $infoCadena1 = explode (':', $intervalo);
                                                                                                            $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
                                                                                                            if($fechaAct < date("Y-m-d H:i:s"))
                                                                                                            {  $fechaAct=date("Y-m-d H:i:s");  }
                                                                                                            $Fecha=MallaValidadoraFechaStamp($fechaAct);
                                                                                                            $infoCadena = explode ('/',$Fecha);
                                                                                                            $intervalo=MallaValidadoraHoraStamp($fechaAct);
                                                                                                            $infoCadena1 = explode (':', $intervalo);
                                                                                                            $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
                                                                                                            //fecha refrendar
                                                                                                            $Fecha=MallaValidadoraFechaStamp($venc);
                                                                                                            $infoCadena = explode ('/',$Fecha);
                                                                                                            $intervalo=MallaValidadoraHoraStamp($venc);
                                                                                                            $infoCadena1 = explode (':', $intervalo);
                                                                                                            $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                            $query = "select * from os_tipos_periodos_tramites
                                                                                                                                                                                            where cargo='$arr[5]'";
                                                                                                            $result=$dbconn->Execute($query);
                                                                                                            if ($dbconn->ErrorNo() != 0) {
                                                                                                                                            $this->error = "Error os_tipos_periodos_tramites";
                                                                                                                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                                                                                            return false;
                                                                                                            }
                                                                                                            if(!$result->EOF)
                                                                                                            {
                                                                                                                                $var=$result->GetRowAssoc($ToUpper = false);
                                                                                                                                $Fecha=MallaValidadoraFechaStamp($arr[6]);
                                                                                                                                $infoCadena = explode ('/',$Fecha);
                                                                                                                                $intervalo=MallaValidadoraHoraStamp($arr[6]);
                                                                                                                                $infoCadena1 = explode (':', $intervalo);
                                                                                                                                $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
                                                                                                                                if($fechaAct < date("Y-m-d H:i:s"))
                                                                                                                                {  $fechaAct=date("Y-m-d H:i:s");  }
                                                                                                                                $Fecha=MallaValidadoraFechaStamp($fechaAct);
                                                                                                                                $infoCadena = explode ('/',$Fecha);
                                                                                                                                $intervalo=MallaValidadoraHoraStamp($fechaAct);
                                                                                                                                $infoCadena1 = explode (':', $intervalo);
                                                                                                                                $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
                                                                                                                                //fecha refrendar
                                                                                                                                $Fecha=MallaValidadoraFechaStamp($venc);
                                                                                                                                $infoCadena = explode ('/',$Fecha);
                                                                                                                                $intervalo=MallaValidadoraHoraStamp($venc);
                                                                                                                                $infoCadena1 = explode (':', $intervalo);
                                                                                                                                $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                                                                                                            }
                                                                                                            else
                                                                                                            {
                                                                                                                                $tramite=ModuloGetVar('app','CentroAutorizacion','dias_tramite_os');
                                                                                                                                $vigencia=ModuloGetVar('app','CentroAutorizacion','dias_vigencia');
                                                                                                                                //$var=$result->GetRowAssoc($ToUpper = false);
                                                                                                                                $Fecha=MallaValidadoraFechaStamp($arr[6]);
                                                                                                                                $infoCadena = explode ('/',$Fecha);
                                                                                                                                $intervalo=MallaValidadoraHoraStamp($arr[6]);
                                                                                                                                $infoCadena1 = explode (':', $intervalo);
                                                                                                                                $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$tramite),$infoCadena[2]));
                                                                                                                                if($fechaAct < date("Y-m-d H:i:s"))
                                                                                                                                {  $fechaAct=date("Y-m-d H:i:s");  }
                                                                                                                                $Fecha=MallaValidadoraFechaStamp($fechaAct);
                                                                                                                                $infoCadena = explode ('/',$Fecha);
                                                                                                                                $intervalo=MallaValidadoraHoraStamp($fechaAct);
                                                                                                                                $infoCadena1 = explode (':', $intervalo);
                                                                                                                                $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$vigencia),$infoCadena[2]));
                                                                                                                                //fecha refrendar
                                                                                                                                $Fecha=MallaValidadoraFechaStamp($venc);
                                                                                                                                $infoCadena = explode ('/',$Fecha);
                                                                                                                                $intervalo=MallaValidadoraHoraStamp($venc);
                                                                                                                                $infoCadena1 = explode (':', $intervalo);
                                                                                                                                $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                                                                                                            }
                                                                                                }//fin else

                                                                                                $query="SELECT nextval('os_maestro_numero_orden_id_seq')";
                                                                                                $result=$dbconn->Execute($query);
                                                                                                $numorden=$result->fields[0];

                                                                                                $query = "INSERT INTO os_maestro
                                                                                                                                                                                                (numero_orden_id,
                                                                                                                                                                                                orden_servicio_id,
                                                                                                                                                                                                sw_estado,
                                                                                                                                                                                                fecha_vencimiento,
                                                                                                                                                                                                hc_os_solicitud_id,
                                                                                                                                                                                                fecha_activacion,
                                                                                                                                                                                                cantidad,
                                                                                                                                                                                                cargo_cups,
                                                                                                                                                                                                fecha_refrendar)
                                                                                                VALUES($numorden,$orden,1,'$venc',$arr[0],'$fechaAct',1,'$arr[5]','$refrendar')";
                                                                                                $dbconn->Execute($query);
                                                                                                if ($dbconn->ErrorNo() != 0) {
                                                                                                                                $this->error = "Error INSERT INTO os_maestro";
                                                                                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                                                                                $dbconn->RollbackTrans();
                                                                                                                                return false;
                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                            foreach($datos as $ke => $va)
                                                                                                            {
                                                                                                                    if(substr_count($ke,'Op'))
                                                                                                                    {    // 0 solicitud_id 1 cargo 2 tarifario
                                                                                                                                    $var=explode(',',$va);
                                                                                                                                    if($var[0]==$arr[0])
                                                                                                                                    {
                                                                                                                                                    $query = "INSERT INTO os_maestro_cargos
                                                                                                                                                                                                                                                    (numero_orden_id,
                                                                                                                                                                                                                                                    tarifario_id,
                                                                                                                                                                                                                                                    cargo)
                                                                                                                                                    VALUES($numorden,'$var[2]','$var[1]')";
                                                                                                                                                    $dbconn->Execute($query);
                                                                                                                                                    if ($dbconn->ErrorNo() != 0) {
                                                                                                                                                                                    $this->error = "Error INSERT INTO os_maestro_cargos";
                                                                                                                                                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                                                                                                                                    $dbconn->RollbackTrans();
                                                                                                                                                                                    return false;
                                                                                                                                                    }
                                                                                                                                    }
                                                                                                                    }
                                                                                                            }

                                                                                                            if(empty($trascripcion))
                                                                                                            {
                                                                                                                        //si es interna
                                                                                                                        if($arr[2]=='dpto')
                                                                                                                        {
                                                                                                                                                                        $query = "INSERT INTO os_internas
                                                                                                                                                                                                                                                                                                                        (numero_orden_id,
                                                                                                                                                                                                                                                                                                                        cargo,
                                                                                                                                                                                                                                                                                                                        departamento)
                                                                                                                                                                                                                                                        VALUES($numorden,'$arr[5]','$arr[1]')";
                                                                                                                        }
                                                                                                                        else
                                                                                                                        {
                                                                                                                                                                                        $query = "INSERT INTO os_externas
                                                                                                                                                                                                                                                                                                                        (numero_orden_id,
                                                                                                                                                                                                                                                                                                                        empresa_id,
                                                                                                                                                                                                                                                                                                                        tipo_id_tercero,
                                                                                                                                                                                                                                                                                                                        tercero_id,
                                                                                                                                                                                                                                                                                                                        cargo,
                                                                                                                                                                                                                                                                                                                        plan_proveedor_id)
                                                                                                                                                                                                                                                        VALUES($numorden,'".$empresa."','$arr[2]','$arr[1]','$arr[5]',$arr[7])";
                                                                                                                        }
                                                                                                                        $dbconn->Execute($query);
                                                                                                                        if ($dbconn->ErrorNo() != 0) {
                                                                                                                                                        $this->error = "Error INTO os_externas o  os_internas";
                                                                                                                                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                                                                                                        $dbconn->RollbackTrans();
                                                                                                                                                        return false;
                                                                                                                        }
                                                                                                            }
                                                                                                            else
                                                                                                            {
                                                                                                                            //insertar en transcripcion
                                                                                                                            $query = "INSERT INTO os_ordenes_servicios_transcripcion
                                                                                                                                                                    VALUES($orden,".$plan.",'$tipo','$paciente','now()',".UserGetUID().")";
                                                                                                                            $dbconn->Execute($query);
                                                                                                                            if ($dbconn->ErrorNo() != 0) {
                                                                                                                                                            $this->error = "Error INSERT INTO os_maestro";
                                                                                                                                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                                                                                                            $dbconn->RollbackTrans();
                                                                                                                                                            return false;
                                                                                                                            }

                                                                                                                            $query = "UPDATE hc_os_solicitudes SET  sw_estado=0
                                                                                                                                                                            WHERE hc_os_solicitud_id=".$arr[0]."";
                                                                                                                            $dbconn->Execute($query);
                                                                                                                            if ($dbconn->ErrorNo() != 0) {
                                                                                                                                                            $this->error = "Error UPDATE  hc_os_solicitudes ";
                                                                                                                                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                                                                                                            $dbconn->RollbackTrans();
                                                                                                                                                            return false;
                                                                                                                            }
                                                                                                            }
                                                                                                }//else
                                                                                }//fin for cantidad
                                                                }
                                                }
                                }//fin foreach
                        }//fin for
                }

                if(!empty($trascripcion))
                {
                                $query = "    (select a.hc_os_solicitud_id,b.cargo as cargos,b.plan_id,b.os_tipo_solicitud_id
                                                                                                                from hc_os_autorizaciones as a,hc_os_solicitudes as b
                                                                                                                where (a.autorizacion_int=".$autorizacion." OR
                                                                                                                a.autorizacion_ext=".$autorizacion.") and a.hc_os_solicitud_id=b.hc_os_solicitud_id)";
                                $result = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                                $this->error = "Error select ";
                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                return false;
                                }
                                while(!$result->EOF)
                                {
                                                                $query = "UPDATE hc_os_solicitudes SET
                                                                                                                                                                sw_estado=0
                                                                                                                                                WHERE hc_os_solicitud_id=".$result->fields[1]."";
                                                                $dbconn->Execute($query);
                                                                if ($dbconn->ErrorNo() != 0) {
                                                                                                $this->error = "Error UPDATE  hc_os_solicitudes ";
                                                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                                                $dbconn->RollbackTrans();
                                                                                                return false;
                                                                }
                                                                $result->MoveNext();
                                }
                                $result->Close();
                }

                $dbconn->CommitTrans();
                for($i=0; $i<sizeof($ordenes); $i++)
                {
                                $x.=$ordenes[$i];
                                if($i!=sizeof($ordenes))
                                { $x.=' - ';}
                }
                $Mensaje = 'La Orden de Servicio No. '.$x.' Fue Generada.';

                return $Mensaje;
    }


//-----------------------------------------------------------------------

?>
