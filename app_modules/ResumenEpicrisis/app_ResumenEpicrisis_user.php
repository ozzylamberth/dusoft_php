 <?php

 /**
 * $Id: app_ResumenEpicrisis_user.php,v 1.5 2006/12/27 18:49:28 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo del resumen de las Epicrisis.
 */

/**
*Contiene los metodos para realizar las autorizaciones.
*/

class app_ResumenEpicrisis_user extends classModulo
{
    var $limit;
    var $conteo;

     function app_ResumenEpicrisis_user()
     {
          $this->limit=GetLimitBrowser();
          return true;
     }

     /**
     *
     */
     function main()
     {
          unset($_SESSION['EPICRISIS']);
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          
          $query = "SELECT A.estacion_id, A.descripcion, A.departamento,
          			  C.empresa_id, C.centro_utilidad, C.unidad_funcional,
                           C.descripcion AS nombre_dpto,
                           D.razon_social
				FROM estaciones_enfermeria AS A, 
                    	estaciones_enfermeria_usuarios AS B, 
                         departamentos AS C,
                         empresas AS D
				WHERE A.estacion_id = B.estacion_id
				AND B.usuario_id = ".UserGetUID()."
				AND A.departamento = C.departamento
                    AND D.empresa_id=C.empresa_id;";
          
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al ejecutar el query de permisos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
          }
          while ($data = $resultado->FetchRow()) {
               $epicrisis[$data['descripcion']]= $data;
          }
     
          $url[0]='app';
          $url[1]='ResumenEpicrisis';
          $url[2]='user';
          $url[3]='FormaMenus';
          $url[4]='DatosEpicrisis';

          $arreglo[0]='ESTACIONES DE ENFERMERIA';
     
          $this->salida.= gui_theme_menu_acceso('EPICRISIS',$arreglo,$epicrisis,$url);
          return true;
     }


     function Menu()
     {	
          if(empty($_SESSION['EPICRISIS']['EMPRESA']))
          {
               $_SESSION['EPICRISIS']['EMPRESA_ID']=$_REQUEST['DatosEpicrisis']['empresa_id'];
               $_SESSION['EPICRISIS']['EMPRESA']=$_REQUEST['DatosEpicrisis']['razon_social'];
               $_SESSION['EPICRISIS']['ESTACION_ID']=$_REQUEST['DatosEpicrisis']['estacion_id'];
               $_SESSION['EPICRISIS']['ESTACION']=$_REQUEST['DatosEpicrisis']['descripcion'];
          }
          if(!$this->FormaMenus()){
               return false;
          }
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
                                   AND estacion_id = '".$_SESSION['EPICRISIS']['ESTACION_ID']."'
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
                        a.estacion_id = '".$_SESSION['EPICRISIS']['ESTACION_ID']."'
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
	*		Calcula los d?as que lleva hospitalizada una persona, basandose en la fecha de ingreso.
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
            $this->mensajeDeError = "Ocurri? un error al intentar seleccionar el contacto del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
            return false;
        }

        if($result->EOF) return null;
        $ContactosPaciente = $result->GetRows();
        $result->Close();
        return $ContactosPaciente;
    }
		
		
		function GetDatosEpicrisis($ingreso)
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
	
			$query="SELECT *
							FROM hc_epicrisis
							WHERE ingreso=$ingreso
							";
							
			$result = $dbconn->Execute($query);
		
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el SubModulo Epicrisis - GetDatosEpicrisis - SQL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				if($result->RecordCount() > 0)
				{
					while(!$result->EOF)
					{
						$vars[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			$dbconn->CommitTrans();
			return $vars;
		}
     
}//fin clase
?>

