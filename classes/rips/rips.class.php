<?php
// rips.class.php  10/02/2004
// -------------------------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 IPSOFT SA.
// Email: mail@ipsoft-sa.com
// -------------------------------------------------------------------------------------
// Autor:  Tizziano Perea - Darling Liliana Dorado
// Proposito del Archivo: Clase para creacion de archivos rips.
// -------------------------------------------------------------------------------------
/** $Id: rips.class.php,v 1.6 2010/05/12 14:19:04 hugo Exp $
*/
class rips
{
    var $error;
    var $mensajeDeError;
        var $archivo;
        var $ruta;
    /**
     * Almacena el caracter para una Nueva linea o salto de linea
     *
     * @var char
     */
    var $nl;
    
    function rips()
    {
        $this->nl = PHP_EOL;
          return true;
    }


    function GetError()
    {
        return $this->error;
    }


    function MensajeDeError()
    {
        return $this->mensajeDeError;
    }
   /**
    * Cambia el formato de la fecha de dd/mm/YY a YY/mm/dd
    * @access private
    * @return string
    * @param date fecha
    * @var    cad   Cadena con el nuevo formato de la fecha
    */
    function ConvFecha($fecha)
    {   
        if($fecha){
            $fech = strtok ($fecha,"/");
            for($i=0;$i<3;$i++)
            {
                $date[$i]=$fech;
                $fech = strtok ("/");
            }
            $cad = $date[2]."-".$date[1]."-".$date[0];
            return $cad;
        }
    }

 /**
  * Se encarga de separar la fecha del formato timestamp
  *
  * @param date fecha
  * @access private
  * @return string
  */
 function FechaStamp($fecha)
 {
   if($fecha){
        list($fecha1) = explode(" ",$fecha);
        return date("d/m/Y",strtotime($fecha1));
   }
 }

    function HoraStamp($hora)
    {
        $hor = strtok ($hora," ");
        for($l=0;$l<4;$l++)
        {
            $time[$l]=$hor;
            $hor = strtok (":");
        }
        $x=explode('.',$time[3]);
        return  $time[1].":".$time[2];
    }
 
    
    /**
    * Metodo  para abrir el archivo de rips dependiendo su tipo
    *
    * @return boolean
    */
    function AbrirArchivo($name,$modo,$envio,$rangos)
    {
      $ruta=GetVarConfigAplication('DirGeneracionRips');
      if(!file_exists($ruta))
        return false;
      
      if(!file_exists($ruta.'/ENVIO'.$envio.(($rangos)? "_".$rangos:"")))
        mkdir($ruta.'/ENVIO'.$envio.(($rangos)? "_".$rangos:""),0777);
      
      $ruta = $ruta.'/ENVIO'.$envio.(($rangos)? "_".$rangos:"");
      $file = $ruta.'/'.$name;

      $this->archivo = fopen($file,$modo);
      if(!$this->archivo)
      {
        $this->error = "Error Rips";
        $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO.';
        return false;
      }

      if(feof($this->archivo))
      {
        $this->error = "Error Rips";
        $this->mensajeDeError = 'Fin del Archivo...';
        return false;
      }
      return true;
    }
    /**
    Metodo que escribe en el archivo
    */
    function EscribirArchivo($texto)
    {
                fwrite($this->archivo,$texto);
                return true;
    }


    function CerrarArchivo()
    {
      if(!fclose($this->archivo))
      {
                        $this->error = "Error Rips";
                        $this->mensajeDeError = 'No pude cerrar El archivo...';
                        return false;
      }
        return true;
    }

    /**
     * Retorna de la cadena str la cantidad de caracteres desde el primer
     * caracter hasta la longitud que indica long
     *
     * @param string str
     * @param int long
     * @return string
     */
    function GetFormatoCampoTexto($str,$long)
    {
        $str = trim($str);
        if($long > 0 && strlen($str) > 0)
            $str = substr($str,0,$long);
        return $str;
    }
    
        /**
        * trae los datos del prestador del servicio
        * ejemplo: Clinica de Ocidente
        * @param string empresa,  codigo de la empresa
        * */
    function GetDatosPrestadorServicio($empresa)
    { 
      list($dbconn) = GetDBconn();
      
      $query = "select   c.codigo_sgsss,
                        c.razon_social,
                        case c.tipo_id_tercero when 'NIT' then 'NI' else c.tipo_id_tercero end as tipo_id_tercero, 
                        c.id
              from      empresas as c 
              where   c.empresa_id='$empresa'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error al consultar empresas : " . $dbconn->ErrorMsg();
                return false;
            }

            $var=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $var;
    }
    /**
    * trae los datos de la entidad administradora que presta el cargo cobrado
    * ejemplo: Coomeca EPS prepago
    * 
    * */
    function GetDatosEntidadAdministradora($plan_id)
    { 
        static $datosentidad;
        
        if(!empty($datosentidad[$plan_id])) 
        {
            return $datosentidad[$plan_id];
        }
        else
        {
            list($dbconn) = GetDBconn();
             $query = "
                            SELECT  P.num_contrato, 
                                            P.plan_descripcion,
                                            P.sw_rips_con_cargo_cups,
                                            P.sw_tipo_plan,
                                            TS.codigo_sgsss,
                                            T.nombre_tercero
                            FROM        planes P
                                            LEFT JOIN terceros_sgsss as TS
                                                ON( P.tipo_tercero_id = TS.tipo_id_tercero
                                                        AND P.tercero_id = TS.tercero_id),
                                            terceros T
                            WHERE       P.plan_id = '".$plan_id."'
                                            AND T.tipo_id_tercero = P.tipo_tercero_id
                                            AND T.tercero_id = P.tercero_id
            ";
            global $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar datos de la entidad administradora";
                $this->mensajeDeError = "Error al consultar planes : " . $dbconn->ErrorMsg();
                return false;
            }

            $datosentidad[$plan_id] = $result->FetchRow();
            $result->Close();
            return $datosentidad[$plan_id];
        }
    }
    /**
    *
    */
    function InitSeq()
		{
			list($dbconn) = GetDBconn();
				$query = "	SELECT SETVAL('tmp_rips_cuentas_detalle_tmp_rips_cuentas_detalle_id_seq',0);";
				$dbconn->debug = false;
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al reiniciar seq tmp_rips_cuentas_detalle_tmp_rips_cuentas_detalle_id_seq";
						$this->mensajeDeError = "Error al iniciar seq tmp_rips_cuentas_detalle_tmp_rips_cuentas_detalle_id_seq : " . $dbconn->ErrorMsg();
						echo $this->mensajeDeError;
						return false;
				}
			return true;
		}
    /**
    *
    */
    function DelTabletmp($envio)
		{
			list($dbconn) = GetDBconn();
				$query = "SELECT count(*) 
									FROM tmp_rips_cuentas_detalle
									WHERE envio_id = $envio;";
				$result = $dbconn->Execute($query);
				if($result->fields[0]>0)
				{
					return $envio;
				}
				else
				{
						$query = "DELETE FROM tmp_rips_cuentas_detalle;";
						$dbconn->debug = false;
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al vaciar tmp_rips_cuentas_detalle";
								$this->mensajeDeError = "Error al vaciar tmp_rips_cuentas_detalle : " . $dbconn->ErrorMsg();
								echo $this->mensajeDeError;
								return false;
						}
					return 0;
				}
		}
    /**
    *
    */
    function InsertarDetalleEnvio($envio)
    {
        list($dbconn) = GetDBconn();
        $query = "	INSERT INTO tmp_rips_cuentas_detalle
												(
													envio_id,
													fecha_inicial, 
													fecha_final,
													edempresa_id,
													edprefijo,
													edfactura_fiscal,
													fecha_registro, 
													total_factura,
													valor_cuota_paciente,
													valor_cuota_moderadora,
													descuento,
													prefijo,
													factura_fiscal,
													empresa_id,
													numerodecuenta,
													ingreso,
													plan_id,
													valor_total_empresa,
													transaccion,
													tarifario_id,
													cargo_tarifario,
													cargo_cups,
													fecha_cargo,
													cantidad,
													precio,
													valor_cubierto,
													autorizacion_int,
													autorizacion_ext,
													servicio_cargo,
													consecutivo,
													codigo_agrupamiento_id,
													t_equipo,
													consecutivo1,
													codigo_producto,
													--cantbdd,
													--total_costo,
													codigo_medicamento,
													cargo_manual,
													autorizacion,
													ac_fechaconsulta,
													ac_tipofinalidad,
													ac_causaexterna,
													ac_diagnostico,
													ac_tipodiagnostico,
													ap_fechaprocedimiento,
													ap_ambitoprocedimiento,
													ap_finalidadprocedimiento,
													au_fechaingreso,
													au_horaingreso,
													au_causaexterna,
													au_diagnosticosalida,
													au_destinosalida,
													au_estadosalida,
													au_fechasalida,
													au_horasalida,
													ah_viaingreso,
													ah_fechaingreso,
													ah_horarioingreso,
													ah_causaexterna,
													ah_diagnosticoingreso,
													ah_diagnosticosalida,
													ah_estadosalida,
													ah_fechasalida,
													ah_horariosalida,
													cargo,
													programacion_id,
													diagnostico_relacionado
												)
											SELECT DISTINCT A.*,
																	BDD.consecutivo as consecutivo1,
																	BDD.codigo_producto,
																	--BDD.cantidad as cantBDD,
																	--BDD.total_costo,
																	M.codigo_medicamento,
											
																	RCD.cargo_cups As cargo_manual,
																	RCD.autorizacion,
																	RCD.AC_FechaConsulta,
																	RCD.AC_tipofinalidad,
																	RCD.AC_CausaExterna,
																	RCD.AC_Diagnostico,
																	RCD.AC_TipoDiagnostico,
																	RCD.AP_FechaProcedimiento,
																	RCD.AP_AmbitoProcedimiento,
																	RCD.AP_FinalidadProcedimiento,
																	RCD.AU_FechaIngreso,
																	RCD.AU_HoraIngreso,
																	RCD.AU_CausaExterna,
																	RCD.AU_DiagnosticoSalida,
																	RCD.AU_DestinoSalida,
																	RCD.AU_EstadoSalida,
																	RCD.AU_FechaSalida,
																	RCD.AU_HoraSalida,
																	RCD.AH_ViaIngreso,
																	RCD.AH_FechaIngreso,
																	RCD.AH_horarioingreso,
																	RCD.AH_CausaExterna,
																	RCD.AH_DiagnosticoIngreso,
																	RCD.AH_DiagnosticoSalida,
																	RCD.AH_EstadoSalida,
																	RCD.AH_FechaSalida,
																	RCD.AH_horariosalida,
																	CLQP.cargo_cups AS cargo,
																	CLQ.programacion_id,
																	--HCNOC.via_acceso AS forma_realizacion,
																	--HCNOC.diagnostico_post_qx AS diagnostico_ppal,
																	CLQP.diagnostico_dos AS diagnostico_relacionado 
																	--HCNOC.diagnostico_id_complicacion AS complicacion,
																	--HCNOC.usuario_id AS usuario_nota,
																	--HCDI.tipo_diagnostico_id
											FROM
															(
															SELECT ED.*,
																							FF.fecha_registro, 
																							FF.total_factura,
																							FF.valor_cuota_paciente,
																							FF.valor_cuota_moderadora,
																							FF.descuento,
																							FFC.prefijo,
																							FFC.factura_fiscal,
																							FFC.empresa_id,
																							FFC.numerodecuenta,
																							C.ingreso,
																							C.plan_id,
																							C.valor_total_empresa,
																							CD.transaccion,
																							CD.tarifario_id,
																							CD.cargo AS cargo_tarifario,
																							CD.cargo_cups,
																							CD.fecha_cargo,
																							CD.cantidad,
																							CD.precio,
																							CD.valor_cubierto,
																							CD.autorizacion_int,
																							CD.autorizacion_ext,
																							CD.servicio_cargo,
																							CD.consecutivo,
																							CD.codigo_agrupamiento_id,
																							CLQEM.transaccion AS t_equipo
															FROM
																							(
																									SELECT E.envio_id,
																																	E.fecha_inicial, 
																																	E.fecha_final,
																																	ED.empresa_id AS EDempresa_id,
																																	ED.prefijo AS EDprefijo,
																																	ED.factura_fiscal AS EDfactura_fiscal
																									FROM envios E,
																												envios_detalle ED
																									WHERE   E.envio_id=$envio
																																	AND E.sw_estado in('1','0','3') 
																																	AND E.envio_id = ED.envio_id
																							) AS ED,
																							fac_facturas FF,
																							fac_facturas_cuentas FFC,
																							cuentas C,
																							cuentas_detalle CD LEFT JOIN cuentas_liquidaciones_qx_equipos_moviles CLQEM ON 
																							(CD.transaccion=CLQEM.transaccion) 
															WHERE
																							ED.EDempresa_id = FF.empresa_id
																							AND ED.EDprefijo = FF.prefijo
																							AND ED.EDfactura_fiscal = FF.factura_fiscal
																							AND FF.empresa_id = FFC.empresa_id
																							AND FF.prefijo = FFC.prefijo
																							AND FF.factura_fiscal = FFC.factura_fiscal
																							AND FFC.numerodecuenta = CD.numerodecuenta
																							AND FFC.numerodecuenta = C.numerodecuenta
																							AND (CD.valor_cubierto > 0 OR (CD.valor_cubierto <= 0 AND CD.cargo='DIMD'))
																							--AND CD.valor_cubierto > 0
																							AND ((CD.facturado = '1' AND paquete_codigo_id IS NULL)
																									OR (paquete_codigo_id IS NOT NULL AND CD.sw_paquete_facturado = '1'))
															) AS A
															LEFT JOIN bodegas_documentos_d BDD
																	ON (BDD.consecutivo = A.consecutivo)
															LEFT JOIN medicamentos M
																	ON (BDD.codigo_producto = M.codigo_medicamento)
															LEFT JOIN rips_cuentas_detalle RCD
																	ON (RCD.numerodecuenta = A.numerodecuenta
																			AND RCD.transaccion = A.transaccion)
															LEFT JOIN cuentas_cargos_qx_procedimientos  CCQP 
																	ON (CCQP.transaccion=A.transaccion)
															LEFT JOIN cuentas_liquidaciones_qx_procedimientos CLQP
																	ON (CLQP.consecutivo_procedimiento=CCQP.consecutivo_procedimiento)
															LEFT JOIN cuentas_liquidaciones_qx CLQ
																	ON (CLQ.cuenta_liquidacion_qx_id=CLQP.cuenta_liquidacion_qx_id)
															--LEFT JOIN hc_notas_operatorias_cirugias HCNOC
															--    ON (CLQ.programacion_id=HCNOC.programacion_id)
															--LEFT JOIN hc_evoluciones HCE
															--    ON (HCE.ingreso=A.ingreso)
															--LEFT JOIN hc_diagnosticos_ingreso HCDI
															--    ON (HCDI.evolucion_id=HCE.evolucion_id 
															--      AND HCDI.sw_principal ='1')
 --limit 10 offset 0;";
					$dbconn->debug = false;
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Insertar en tmp_rips_ciuentas_detalle";
							$this->mensajeDeError = "Error al consultar tmp_rips_cuentas_detalle : " . $dbconn->ErrorMsg();
							echo $this->mensajeDeError;
							return false;
					}
				return true;
    }

    /**
    *
    */
    function GetDetalleEnvio($envio,$aq,$reg,$rangos)
    {
        $sql="";
        if($aq) $sql=" AND TR.tarifario_id<>'SYS'";

        if($rangos) $sql .= " AND  CU.rango IN(".$rangos.") ";
        
        list($dbconn) = GetDBconn();
        
        $query = "SELECT TR.*, UF.codigo_prestador
                  FROM tmp_rips_cuentas_detalle TR,
                       cuentas CU,
                       departamentos DE,
                       unidades_funcionales UF,
                       centros_utilidad CT,
                       cuentas_detalle CD
                  WHERE TR.tmp_rips_cuentas_detalle_id=".$reg."
                  AND   TR.envio_id = $envio
                  AND   CU.numerodecuenta = TR.numerodecuenta
                  AND   CU.centro_utilidad = CT.centro_utilidad
                  AND   CU.empresa_id = CT.empresa_id
                  AND   TR.transaccion = CD.transaccion
                  AND   CD.departamento = DE.departamento
                  AND   DE.empresa_id = UF.empresa_id
                  AND   DE.centro_utilidad = UF.centro_utilidad
                  AND   DE.unidad_funcional = UF.unidad_funcional
                  $sql ;";

        global $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        //if($aq){$x='1';}else{$x='0';}
        //echo 'numOfRows['.$x.']-->'.$result->_numOfRows.'<br><br><br>';
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Consultar los detales del envio";
            $this->mensajeDeError = "Error al consultar rips_cuentas_detalle : " . $dbconn->ErrorMsg();
            echo $this->mensajeDeError;
            return false;
        }
        while(!$result->EOF)
        {
            $dato = $result->FetchRow();
            $var[]= $dato;
        }
        $result->Close();
        return $var;
    }
    
            /**
        * trae los datos de la entidad administradora que presta el cargo cobrado
        * ejemplo: Coomeca EPS prepago
        * 
        * */
    function GetPolizaSoat($ingreso)
    { 
        static $datospoliza;
        
        if(!empty($datospoliza[$ingreso])) 
        {
            return $datospoliza[$ingreso];
        }
        else
        {
            list($dbconn) = GetDBconn();
             $query = "
									SELECT  SE.poliza
									FROM        
																	ingresos_soat as I
																			LEFT JOIN soat_eventos as SE
																					ON(I.evento = SE.evento)
									WHERE       I.ingreso = '".$ingreso."'
            ";
            global $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar datos de la poliza";
                $this->mensajeDeError = "Error al consultar  ingresos_soat: " . $dbconn->ErrorMsg();
                return false;
            }

            $datospoliza[$ingreso] = $result->FetchRow();
            $result->Close();
            return $datospoliza[$ingreso];
        }
    }
    
    /**
    *
    **/
    function GetDatosCups($cargo_cups)
    {
        static $datoscups;
        
        if(!empty($datoscups[$cargo_cups])) 
        {
            return $datoscups[$cargo_cups];
        }
        else
        {
            list($dbconn) = GetDBconn();
            $query = "
                            SELECT  A.descripcion AS descripcion_cups,
                                            A.grupo_tarifario_id,
                                            A.subgrupo_tarifario_id,
                                            A.grupo_tipo_cargo,
                                            A.tipo_cargo,
                                            A.concepto_rips,
                                            B.descripcion,
                                            B.tipo_servicio,
                                            B.tipo_servicio_descripcion
                            FROM        cups A,
                                            rips_conceptos B
                            WHERE       A.cargo = '".$cargo_cups."'
                                            AND A.concepto_rips = B.concepto_rips
            ";
            global $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar datos del cups";
                $this->mensajeDeError = "Error rips_conceptos : " . $dbconn->ErrorMsg();
                return false;
            }

            $datoscups[$cargo_cups] = $result->FetchRow();
            $result->Close();
            return $datoscups[$cargo_cups];
        }
    }
    /**
    *   Consulta el tipo de rips que se debe trabajar segun el cargo
    */
    function GetTipoRips($ConsultaTipoRips,$tipo_cargo,$grupo_tipo_cargo,$grupo,$subgrupo,$tarifario,$cargo,$empresa)
    {
            list($dbconn) = GetDBconn();
            
            $query = "SELECT rips_tipo_id
                                FROM rips_parametros_tipos_excepciones 
                                WHERE cargo='$cargo' and tarifario_id='$tarifario' and empresa_id='$empresa'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB rips_parametros_tipos_excepciones: " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
            }
            if(!$result->EOF)
            {       //tiene excepcion
                    return $result->fields[0];
            }
            else
            {//caso para Tulua Tulua
                if($ConsultaTipoRips == '1')
                {
                    $query = "SELECT rips_tipo_id
                                        FROM    rips_parametros_tipos
                                        WHERE empresa_id='$empresa' 
                                                    AND grupo_tarifario_id='$grupo' 
                                                    AND subgrupo_tarifario_id='$subgrupo'";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error  al Consultar rips_parametros_tipos";
                        $this->mensajeDeError = "Error DB rips_parametros_tipos: " . $dbconn->ErrorMsg();
                        echo $this->mensajeDeError;
                        return false;
                    }   
                    if(!$result->EOF)
                    {       return $result->fields[0];      }   
                    else                    
                    {   //no estan llenas las tablas
                            return '0';   
                    }
                }
                else
                {//caso para Cali
                    $query = "SELECT rips_tipos_id
                                        FROM rips_tipos_cargos
                                        WHERE tipo_cargo='$tipo_cargo' 
                                                    AND grupo_tipo_cargo='$grupo_tipo_cargo'";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Consultar rips_tipos_cargos";
                        $this->mensajeDeError = "Error DB rips_tipos_cargos: " . $dbconn->ErrorMsg();
                        echo $this->mensajeDeError;
                        return false;
                    }   
                    
                    if(!$result->EOF)
                    {       return $result->fields[0];      }   
                    else                    
                    {   //no estan llenas las tablas
                            return '0';   
                    }
                }
            }
    }
    /**
    * Consulta los datos del paciente dado el numero de ingreso
    **/
    function GetDatosUsuario($ingreso)
    {
        static $datosusuario;
        
        if(!empty($datosusuario[$ingreso])) 
        {
            return $datosusuario[$ingreso];
        }
        else
        {
            list($dbconn) = GetDBconn();
            $query = "
                            SELECT  P.tipo_id_paciente,
                                            P.paciente_id,
                                            P.primer_nombre,
                                            P.segundo_nombre,
                                            P.primer_apellido,
                                            P.segundo_apellido,
                                            P.primer_nombre ||' '||P.segundo_nombre ||' '|| P.primer_apellido ||' '|| P.segundo_apellido AS nombre,
                                            P.fecha_nacimiento,
                                            P.sexo_id,
                                            P.tipo_dpto_id,
                                            P.tipo_mpio_id,
                                            P.zona_residencia
                            FROM        ingresos I,
                                            pacientes  P
                            WHERE       ingreso = '".$ingreso."'
                                            AND I.tipo_id_paciente = P.tipo_id_paciente
                                            AND I.paciente_id = P.paciente_id
            ";
            global $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar datos del Usuario";
                $this->mensajeDeError = "Error DB pacientes: " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
            }

            $datosusuario[$ingreso] = $result->FetchRow();
            $result->Close();
            return $datosusuario[$ingreso];
        }
    }
		
		/**
		***
		**/
		
		function DatosAutorizacionEnvios($envio)
		{
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
				$query = "SELECT CD.transaccion, 
										CD.cargo_cups,
										I.tipo_id_paciente,
										I.paciente_id,
										HCA.autorizacion_int,
										HCA.autorizacion_ext,
                    C.numerodecuenta
									FROM	cuentas C,
												ingresos I,
												cuentas_detalle CD,
												fac_facturas_cuentas FFC,
												envios_detalle ED,
												hc_os_solicitudes_manuales HCSM,
												hc_os_autorizaciones HCA
									WHERE ED.envio_id = $envio
									AND FFC.prefijo = ED.prefijo
									AND FFC.factura_fiscal = ED.factura_fiscal
									AND CD.numerodecuenta = FFC.numerodecuenta
									AND CD.numerodecuenta = C.numerodecuenta
									AND C.ingreso = I.ingreso
									AND I.tipo_id_paciente = HCSM.tipo_id_paciente
									AND I.paciente_id = HCSM.paciente_id
									AND CD.cargo_cups IS NOT NULL
									AND HCSM.hc_os_solicitud_id = HCA.hc_os_solicitud_id;";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					echo " ERROR AL SELECCIONAR AUTORIZACION: " . $dbconn->ErrorMsg()."<BR>[".get_class($this)."][".__LINE__."]";
					$dbconn->RollbackTrans();
					return false;
				}
				while(!$resulta->EOF)
				{
					$var[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				$query = "SELECT CD.transaccion, FFC.numerodecuenta
		
									FROM cuentas_detalle CD,
												fac_facturas_cuentas FFC,
												envios_detalle ED
									WHERE ED.envio_id = $envio
									AND FFC.prefijo = ED.prefijo
									AND FFC.factura_fiscal = ED.factura_fiscal
									AND CD.numerodecuenta = FFC.numerodecuenta
									AND CD.cargo_cups IS NOT NULL;";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
					echo " ERROR AL SELECCIONAR transaccion: " . $dbconn->ErrorMsg()."<BR>[".get_class($this)."][".__LINE__."]";
					$dbconn->RollbackTrans();
					return false;
				}
				while(!$resulta->EOF)
				{
					$var2[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
					for($i=0;$i<sizeof($var);$i++)
					{
						for($j=0;$j<sizeof($var2);$j++)
						{
							if($var[$i][transaccion]==$var2[$j][transaccion])
								{
									$query0= "UPDATE cuentas
														SET estado='1'
														WHERE numerodecuenta = ".$var[$i][numerodecuenta].";";
									$resulta=$dbconn->Execute($query0);
									if ($dbconn->ErrorNo() != 0) {
										echo " ERROR UPDATE en cuentas: " . $dbconn->ErrorMsg()."<BR>[".get_class($this)."][".__LINE__."]";
										$dbconn->RollbackTrans();
										return false;
									}
									$query1= "UPDATE cuentas_detalle
														SET autorizacion_int = ".$var[$i][autorizacion_int].", autorizacion_ext=".$var[$i][autorizacion_ext]."
														WHERE transaccion=".$var[$i][transaccion].";";
									$resulta=$dbconn->Execute($query1);
									if ($dbconn->ErrorNo() != 0) {
										echo " ERROR UPDATE autorizacion int- ext en cuentas_detalle: " . $dbconn->ErrorMsg()."<BR>[".get_class($this)."][".__LINE__."]";
										$dbconn->RollbackTrans();
										return false;
									}
									$query2= "UPDATE cuentas
														SET estado='0'
														WHERE numerodecuenta=".$var[$i][numerodecuenta].";";
									$resulta=$dbconn->Execute($query2);
									if ($dbconn->ErrorNo() != 0) {
										echo " ERROR UPDATE  cuentas: " . $dbconn->ErrorMsg()."<BR>[".get_class($this)."][".__LINE__."]";
										$dbconn->RollbackTrans();
										return false;
									}
								}
						}
					}//fin for
			$dbconn->CommitTrans();
			return true;
		}
    /**
    *
    */
    function GetAutorizacionExterna($auto)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT  c.codigo_autorizacion as codelect,
                                                d.codigo_autorizacion as codelectsos, 
                                                e.codigo_autorizacion as codescr,
                                                f.codigo_autorizacion as codcert, 
                                                g.codigo_autorizacion as codtel
                                FROM        hc_os_autorizaciones as b
                                                left join autorizaciones_electronicas as c on(b.autorizacion_ext=c.autorizacion)
                                                left join autorizaciones_electronicas_sos as d on(b.autorizacion_ext=d.autorizacion)
                                                left join autorizaciones_escritas as e on(b.autorizacion_ext=e.autorizacion)
                                                left join autorizaciones_certificados as f on(b.autorizacion_ext=f.autorizacion)
                                                left join autorizaciones_telefonicas as g on(b.autorizacion_ext=g.autorizacion)

                                WHERE b.autorizacion_ext=$auto
                                            AND b.autorizacion_ext IS NOT NULL";

            $dbconn-> debug = false;
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB hc_os_autorizaciones: " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$result->EOF)
            {
                    $var=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }

            $result->Close();
            return $var;
    }
		
		
    /**
    * Retorna el numero de autorizacion
    * Si no hay retorna uno (1) como valor por defecto
    * @param autorizacion_ext String
    * @return auto String
    */
    function GetAutorizacion($d_envio,$autorizacion_ext)
    {
        $auto='1';
        if((!empty($autorizacion_ext)) && ($autorizacion_ext != '1'))
        {
                $aut='1';
                $aut=$this->GetAutorizacionExterna($autorizacion_ext);

                if(!empty($aut[codelect]))
                {  $auto=$aut[codelect];  }
                elseif(!empty($aut[codelectsos]))
                {  $auto=$aut[codelectsos];  }
                elseif(!empty($aut[codelescr]))
                {  $auto=$aut[codelescr];  }
                elseif(!empty($aut[codcert]))
                {  $auto=$aut[codcert];  }
                elseif(!empty($aut[codtel]))
                {  $auto=$aut[codtel];  }
        }
        else
        {
            if(!empty($d_envio[autorizacion_int]))
            {
                $auto = $d_envio[autorizacion_int];
            }
        }
        return $auto;
    }

    function GetAutorizacionIngreso($ingreso)
    {
if($ingreso)
{
            list($dbconn) = GetDBconn();
            $query = "SELECT   codigo_autorizacion
                      FROM    autorizaciones
                      WHERE ingreso = $ingreso ";
            global $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar autoriacion";
                $this->mensajeDeError = "Error DB medicamentos: " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
            }
            $auto = $result->FetchRow();
    
        return $auto[codigo_autorizacion];
}
return true;
    }

    /**
    * Consulta los datos del medicamento
    */
    function GetDatosMedicamento($cod_medicamento,$cod_producto)
    {
        static $datosmedicamento;
        
        if(!empty($datosmedicamento[$cod_medicamento][$cod_producto])) 
        {
            return $datosmedicamento[$cod_medicamento][$cod_producto];
        }
        else
        {
            list($dbconn) = GetDBconn();
            $query = "SELECT    e.descripcion_abreviada, 
                                                b.descripcion as farmaco,
                                                case a.sw_pos when 0 then 2 else 1 end as sw_pos,
                                                case a.sw_pos when 0 then a.codigo_medicamento
                                                    else a.cod_anatomofarmacologico||''||a.cod_principio_activo||''||a.cod_forma_farmacologica||''||a.cod_concentracion
                                                        end as codigo_producto,
                                                d.descripcion as unidad, 
                                                a.concentracion_forma_farmacologica
                                FROM    medicamentos as a, 
                                            inv_med_cod_forma_farmacologica as b,
                                            inv_unidades_medida_medicamentos as d,
                                            inventarios_productos as e
                                WHERE a.codigo_medicamento = '".$cod_medicamento."'
                                            AND a.cod_forma_farmacologica = b.cod_forma_farmacologica
                                            AND a.unidad_medida_medicamento_id = d.unidad_medida_medicamento_id
                                            AND e.codigo_producto = '".$cod_producto."'
                                            ";
            global $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar datos del Medicamento";
                $this->mensajeDeError = "Error DB medicamentos: " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
            }

            $datosmedicamento[$cod_medicamento][$cod_producto] = $result->FetchRow();
            $result->Close();
            return $datosmedicamento[$cod_medicamento][$cod_producto];
        }
    }
    
    /**
    * Consulta los datos del insumo
    */
    function GetDatosInsumo($cod_producto)
    {
        static $datosinsumo;
        
        if(!empty($datosinsumo[$cod_producto])) 
        {
            return $datosinsumo[$cod_producto];
        }
        else
        {
            list($dbconn) = GetDBconn();
            $query = "SELECT A.descripcion_abreviada,
                                            A.unidad_id,
                                            A.cod_ihosp
                                FROM    inventarios_productos as A
                                WHERE A.codigo_producto = '".$cod_producto."'
                                            ";
            global $ADODB_FETCH_MODE;
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al consultar datos del insumo";
                $this->mensajeDeError = "Error DB inventarios_productos: " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
            }

            $datosinsumo[$cod_producto] = $result->FetchRow();
            $result->Close();
            return $datosinsumo[$cod_producto];
        }
    }
    /**
    *
    */
    function GetDatosPrincipalesCita($ingreso)
    {
        static $datoscita;
        
        if(!empty($datoscita[$ingreso])) 
        {
            return $datoscita[$ingreso];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            
            $query = "  SELECT case when a.tipo_finalidad_id='NULL' then '10' 
                                     when a.tipo_finalidad_id<>'NULL' then a.tipo_finalidad_id end as tipo_finalidad_id,
                                case when b.tipo_atencion_id='NULL' then '15' when b.tipo_atencion_id<>'NULL' then b.tipo_atencion_id end as tipo_atencion_id,
                                c.tipo_diagnostico_id, 
                                c.sw_principal, 
                                c.tipo_diagnostico,
                                d.evolucion_id
                        FROM    hc_diagnosticos_ingreso as c,
                                hc_evoluciones as d 
                                left join hc_finalidad as a 
                                on(a.evolucion_id=d.evolucion_id)
                                left join hc_atencion as b 
                                on(b.evolucion_id=d.evolucion_id)
                        WHERE   d.ingreso=".$ingreso." 
                        and c.evolucion_id=d.evolucion_id
                        AND     c.sw_programa IS NULL
                        UNION ALL 
                        SELECT  case when a.tipo_finalidad_id='NULL' then '10' 
                                     when a.tipo_finalidad_id<>'NULL' then a.tipo_finalidad_id end as tipo_finalidad_id,
                                case when b.tipo_atencion_id='NULL' then '15' when b.tipo_atencion_id<>'NULL' then b.tipo_atencion_id end as tipo_atencion_id,
                                c.tipo_diagnostico_id, 
                                '1' AS sw_principal, 
                                c.tipo_diagnostico,
                                d.evolucion_id
                        FROM    hc_diagnosticos_ingreso c,
                                hc_evoluciones d
                                left join hc_atencion b 
                                ON(b.evolucion_id = d.evolucion_id),
                                hc_finalidad_pyp a
                        WHERE   d.ingreso=".$ingreso."
                        AND     a.evolucion_id=d.evolucion_id
                        AND     a.diagnostico_id= c.tipo_diagnostico_id
                        and     c.evolucion_id=d.evolucion_id
                        AND     c.sw_programa IS NOT NULL";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB hc_diagnosticos_ingreso: " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
            }

            while(!$result->EOF)
            {
                    $datoscita[$ingreso][]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }

            $result->Close();
            return $datoscita[$ingreso];
        }
    }
    
    /**
    *
    */
    function FechaCita($transaccion)
    {
        static $fechacita;
        
        if(!empty($fechacita[$transaccion])) 
        {
            return $fechacita[$transaccion];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "SELECT e.fecha_turno
                                FROM    os_cruce_citas as a, 
                                            agenda_citas_asignadas as b,
                                            agenda_citas as c, 
                                            agenda_turnos as e,
                                            os_maestro_cargos f
                                WHERE a.numero_orden_id = f.numero_orden_id
                                and a.agenda_cita_asignada_id = b.agenda_cita_asignada_id
                                and b.agenda_cita_id = c.agenda_cita_id
                                and c.agenda_turno_id = e.agenda_turno_id
                                AND f.transaccion = $transaccion";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB os_cruce_citas: " . $dbconn->ErrorMsg();
                return false;
            }
            else
            {
                $fechacita[$transaccion] = $result->fields[0];
                $result->Close();
                return $fechacita[$transaccion];
            }
        }
    }
    
    /**
    *
    */
    function    GetAmbito($servicio)
    {
        static $ambito;
        
        if(!empty($ambito[$servicio])) 
        {
            return $ambito[$servicio];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "SELECT    ambito_rips_id 
                                FROM        servicios 
                                WHERE   servicio = '".$servicio."'
                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Consultar ambito";
                $this->mensajeDeError = "Error DB servicios: " . $dbconn->ErrorMsg();
                echo $this->mensajeDeError;
                return false;
            }
            if(!$result->EOF)
            {
                    $ambito[$servicio] = $result->fields[0];
            }
            $result->Close();
            return $ambito[$servicio];
        }
    }
    
    /**
    *
    */
        function GetDiagnosticosOdontologiaAP($transaccions)
    {
            list($dbconn) = GetDBconn();
            
            $query = "SELECT    a.numero_orden_id
                                FROM        os_maestro_cargos as a  
                                WHERE   a.transaccion=$transaccions;
                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB os_maestro_cargos: " . $dbconn->ErrorMsg();
                return false;
            }   
            $numeroOs=$result->fields[0];
            $result->Close();   
            
            if(!empty($numeroOs))
            {
                $query = "SELECT    hc_os_solicitud_id 
                                    FROM        os_maestro
                                    WHERE   numero_orden_id = $numeroOs;
                                    ";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB os_maestro: " . $dbconn->ErrorMsg();
                    return false;
                }   
                $solicitud=$result->fields[0];
                $result->Close();   
                
                $query = "SELECT b.diagnostico_id, b.sw_principal
                                    FROM hc_odontogramas_primera_vez_detalle  as a, hc_odontogramas_tratamientos_evolucion_primera_vez as b
                                    WHERE a.hc_os_solicitud_id=$solicitud and a.hc_odontograma_primera_vez_detalle_id=b.hc_odontograma_primera_vez_detalle_id
                                    order by b.sw_principal desc;";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB hc_odontogramas_primera_vez_detalle: " . $dbconn->ErrorMsg();
                    return false;
                }               
                //encontro los diagnosticos
                if(!$result->EOF)   
                {       $diag='';
                        $i=0;
                        while(!$result->EOF AND $i<=1)
                        {
                                if($i==0)
                                {  $diag=$result->fields[0];  }
                                elseif($i==1)
                                {  $diag.=','.$result->fields[0];  }
                                $i++;
                                $result->MoveNext();
                        }
                        if($i==1)
                        { $diag.=',';}
                        return  $diag;
                }
                else
                {
                        $query = "SELECT b.diagnostico_id, b.sw_principal
                                            FROM hc_odontogramas_tratamientos_detalle  as a, hc_odontogramas_tratamientos_evolucion_tratamiento as b
                                            WHERE a.hc_os_solicitud_id=$solicitud and a.hc_odontograma_tratamiento_detalle_id=b.hc_odontograma_tratamiento_detalle_id
                                            order by b.sw_principal desc;";
                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB hc_odontogramas_tratamientos_detalle: " . $dbconn->ErrorMsg();
                            return false;
                        }       
                        //encontro los diagnosticos
                        if(!$result->EOF)   
                        {       $diag='';
                                $i=0;
                                while(!$result->EOF AND $i<=1)
                                {
                                        if($i==0)
                                        {  $diag=$result->fields[0];  }
                                        elseif($i==1)
                                        {  $diag.=','.$result->fields[0];  }
                                        $i++;
                                        $result->MoveNext();
                                }
                                if($i==1)
                                { $diag.=',';}
                                return  $diag;                  
                        }           
                        else
                        {
                            $query = "SELECT diagnostico_id, sw_principal
                                                    FROM hc_odontogramas_tratamientos_evolucion_presupuesto
                                                    WHERE hc_os_solicitud_id=$solicitud order by sw_principal desc;";
                                $result = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Cargar el Modulo";
                                    $this->mensajeDeError = "Error DB hc_odontogramas_tratamientos_evolucion_presupuesto: " . $dbconn->ErrorMsg();
                                    return false;
                                }   
                                //encontro los diagnosticos
                                if(!$result->EOF)   
                                {       $diag='';
                                        $i=0;
                                        while(!$result->EOF AND $i<=1)
                                        {
                                                if($i==0)
                                                {  $diag=$result->fields[0];  }
                                                elseif($i==1)
                                                {  $diag.=','.$result->fields[0];  }
                                                $i++;
                                                $result->MoveNext();
                                        }
                                        if($i==1)
                                        { $diag.=',';}
                                        return  $diag;                          
                                }   
                                else
                                {
                                    $query = "SELECT diagnostico_id, sw_principal
                                                            FROM hc_odontogramas_tratamientos_evolucion_apoyod
                                                            WHERE hc_os_solicitud_id=$solicitud order by sw_principal desc;";
                                        $result = $dbconn->Execute($query);
                                        if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error al Cargar el Modulo";
                                            $this->mensajeDeError = "Error DB hc_odontogramas_tratamientos_evolucion_apoyod: " . $dbconn->ErrorMsg();
                                            return false;
                                        }
                                        //encontro los diagnosticos
                                        if(!$result->EOF)   
                                        {       $diag='';
                                                $i=0;
                                                while(!$result->EOF AND $i<=1)
                                                {
                                                        if($i==0)
                                                        {  $diag=$result->fields[0];  }
                                                        elseif($i==1)
                                                        {  $diag.=','.$result->fields[0];  }
                                                        $i++;
                                                        $result->MoveNext();
                                                }
                                                if($i==1)
                                                { $diag.=',';}
                                                return  $diag;                          
                                        }
                                        else
                                        { return "error no se guardaron solicitud=>$solicitud"; }                                                                       
                                }                                           
                        }       
                }
            }
    }
    
    /**
    *
    */
    function GetDatosCama($transaccion)
    {
        static $datoscama;
        
        if(!empty($datoscama[$transaccion])) 
        {
            return $datoscama[$transaccion];
        }
        else
        {   
            list($dbconn) = GetDBconn();
/*          $query = "SELECT    a.fecha_ingreso, 
                                                a.fecha_egreso, 
                                                b.tipo_clase_cama_id
                                FROM    movimientos_habitacion as a, 
                                            tipos_camas as b
                                WHERE a.transaccion = $transaccion
                                            and a.tipo_cama_id = b.tipo_cama_id;";*/
/*            $query = "SELECT    a.fecha_ingreso, 
                                                a.fecha_egreso, 
                                                b.tipo_clase_cama_id
                                FROM    movimientos_habitacion as a, 
                                            tipos_camas as b
                                WHERE a.numerodecuenta = $transaccion
                                            and a.tipo_cama_id = b.tipo_cama_id;";*/
            $query = "SELECT  	I.fecha_ingreso, 
								CASE WHEN I.fecha_cierre IS NULL THEN S.fecha_registro 
								ELSE I.fecha_cierre END AS fecha_egreso,
								TC.tipo_clase_cama_id
						FROM    ingresos I left join ingresos_salidas S ON (I.ingreso = S.ingreso)
								INNER JOIN movimientos_habitacion MH ON(I.ingreso = MH.ingreso AND MH.movimiento_id = (SELECT MAX(movimiento_id)
																								FROM  movimientos_habitacion
																								WHERE ingreso = $transaccion)),
								tipos_camas TC																						
						WHERE 	I.ingreso = $transaccion
						AND	    MH.tipo_cama_id = TC.tipo_cama_id;";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB movimientos_habitacion: " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$result->EOF)
            {
                    $datoscama[$transaccion]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }

            $result->Close();
            return $datoscama[$transaccion];
        }
    }

    /**
    *
    */
    function GetCausaExterna($ingreso)
    {
        static $causaexterna;
        
        if(!empty($causaexterna[$ingreso])) 
        {
            return $causaexterna[$ingreso];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "SELECT    tipo_atencion_id 
                                FROM        hc_atencion 
                                WHERE   ingreso=$ingreso";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB hc_atencion: " . $dbconn->ErrorMsg();
                return false;
            }

            $result->Close();
            $causaexterna[$ingreso] = $result->fields[0];
						if(empty($causaexterna[$ingreso]))
						{
							$query = "SELECT 	causa_externa_id
												FROM ingresos 
												WHERE ingreso = $ingreso";
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB hc_atencion: " . $dbconn->ErrorMsg();
									return false;
							}
							$result->Close();
							$causaexterna[$ingreso] = $result->fields[0];
						}
            return $causaexterna[$ingreso];
        }
    }
    
    /**
    *
    */
    function GetDatosEgresoPaciente($ingreso)
    {
        static $egresopaciente;
        
        if(!empty($egresopaciente[$ingreso])) 
        {
            return $egresopaciente[$ingreso];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "  SELECT c.tipo_diagnostico_id, c.sw_principal, c.tipo_diagnostico
                                    FROM hc_diagnosticos_egreso as c, hc_evoluciones as d
                                    WHERE d.ingreso=$ingreso
                                    and c.evolucion_id=d.evolucion_id";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB hc_diagnosticos_egreso: " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$result->EOF)
            {
                    $egresopaciente[$ingreso][]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }

            $result->Close();
            return $egresopaciente[$ingreso];
        }
    }
    
    /**
    *
    */
    function GetDatosDefuncion($ingreso)
    {
        static $datosdefuncion;
        
        if(!empty($datosdefuncion[$ingreso])) 
        {
            return $datosdefuncion[$ingreso];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "  SELECT c.diagnostico_defuncion_id
                                    FROM    hc_conducta_defuncion as a, 
                                                hc_conducta_diagnosticos_defuncion as c
                                    WHERE a.ingreso=$ingreso 
                                                and a.evolucion_id=c.evolucion_id
                                                and c.sw_principal='1'
                                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if(!$result->EOF)
            {
                    $datosdefuncion[$ingreso]=$result->fields[0];
            }

            $result->Close();
            return $datosdefuncion[$ingreso];
        }
    }


    /**
    *
    */
    function GetDestinoPaciente($ingreso,$cuenta)
    {
            list($dbconn) = GetDBconn();
            $var='';
            //alta de urgencias
            $query = "  SELECT a.sw_estado FROM pacientes_urgencias as a
                                    WHERE a.ingreso=$ingreso AND a.sw_estado IN('4','2')";
            /*$query = "  SELECT case when a.sw_estado=2 then 3 when a.sw_estado=4 then 1 end as estado
                                    FROM pacientes_urgencias as a WHERE a.ingreso=$ingreso";*/
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if(!$result->EOF)
            {
                    if($result->fields[0]==4)
                    {   $var=1;  }
                    else
                    {       //hospitalizado
                            $query = "SELECT b.tipo_clase_cama_id
                                                FROM movimientos_habitacion as a, tipos_camas as b
                                                WHERE a.numerodecuenta=$cuenta
                                                and a.tipo_cama_id=b.tipo_cama_id and b.tipo_clase_cama_id<>'3'";
                            $result = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }
                            if(!$result->EOF)
                            {
                                    $var=3;
                            }
                            else
                            {       //remirido
                                    $query = "  SELECT ingreso FROM hc_conducta_remision WHERE ingreso=$ingreso";
                                    $result = $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                                    }
                                    if(!$result->EOF)
                                    {
                                            $var=2;
                                    }
                                    else
                                    {  $var=1;  }
                            }
                    }
            }
            else
            {       //remirido
                    $query = "  SELECT ingreso FROM hc_conducta_remision WHERE ingreso=$ingreso";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    if(!$result->EOF)
                    {
                            $var=2;
                    }
                    else
                    {       //hospitalizado
                            $query = "SELECT b.tipo_clase_cama_id
                                                FROM movimientos_habitacion as a, tipos_camas as b
                                                WHERE a.numerodecuenta=$cuenta
                                                and a.tipo_cama_id=b.tipo_cama_id and b.tipo_clase_cama_id<>'3'";
                            $result = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }
                            if(!$result->EOF)
                            {
                                    $var=3;
                            }
                    }
            }

            $result->Close();
            return $var;
    }

    /**
    *
    */
    function GetRegimen($plan)
    {
        static $regimen;
        
        if(!empty($regimen[$plan])) 
        {
            return $regimen[$plan];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "SELECT b.regimen_id
                                FROM    planes as a 
                                            left join tipos_cliente as b 
                                                on(a.tipo_cliente=b.tipo_cliente)
                                WHERE a.plan_id=$plan  ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $regimen[$plan]=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $regimen[$plan];
        }
    }
        /**
    *
    */
    function GetViasIngreso()
    {
        list($dbconn) = GetDBconn();
        $query = "select    via_ingreso_id,
                                            via_ingreso_nombre
                            from        vias_ingreso
                            ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Consultar datos del vias_ingreso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $datos=$result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $datos;
    }
    /**
    *
    */
    function GetQxTarifarioCargo($transaccion)
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT tarifario_id, cargo       
                  FROM cuentas_cargos_qx_procedimientos
                  WHERE transaccion = $transaccion";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Consultar datos del vias_ingreso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $datos=$result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $datos;
    }
    /**
    *
    */
    function GetDatosIngreso($ingreso)
    {
        static $datosingreso;
        
        if(!empty($datosingreso[$ingreso])) 
        {
            return $datosingreso[$ingreso];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "select    A.tipo_id_paciente, 
                                                A.paciente_id, 
                                                B.tipo_afiliado_id, 
                                                B.numerodecuenta,
                                                A.ingreso, 
                                                A.via_ingreso_id, 
                                                A.autorizacion_ext, 
                                                A.causa_externa_id,
                                                D.tipo_diagnostico_id,
                                                A.fecha_registro,
                                                A.fecha_cierre
                                from        ingresos as A,
                                                cuentas as B,
                                                hc_evoluciones C,
                                                hc_diagnosticos_ingreso D
                                where   A.ingreso = '".$ingreso."'
                                                AND A.ingreso = B.ingreso
                                                AND A.ingreso = C.ingreso
                                                AND C.evolucion_id = D.evolucion_id
                                                AND D.sw_principal  = '1';";
            $dbconn->debug = false;
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Consultar datos del ingreso";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $datosingreso[$ingreso]=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $datosingreso[$ingreso];
        }
    }

    /**
    *
    */
    function GetTipoProfesional($transaccion)
    {
        static $tipoprofesional;
        
        if(!empty($tipoprofesional[$transaccion])) 
        {
            return $tipoprofesional[$transaccion];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "SELECT    TP.codigo_rips
                                FROM        cuentas_detalle_profesionales CDP,
                                                profesionales P,
                                                tipos_profesionales TP
                                WHERE       CDP.transaccion = ".$transaccion."
                                                AND CDP.tipo_tercero_id = P.tipo_id_tercero
                                                AND CDP.tercero_id = P.tercero_id
                                                AND P.tipo_profesional = TP.tipo_profesional
                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Consultar tipo profesional";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $tipoprofesional[$transaccion]=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $tipoprofesional[$transaccion];
        }
    }
    
    function GetTipoProfesionalRips($user)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT    TP.codigo_rips
                                FROM        profesionales_usuarios PU,
                                                profesionales P,
                                                tipos_profesionales TP
                                WHERE       PU.usuario_id = $user
                                                AND PU.tipo_tercero_id = P.tipo_id_tercero
                                                AND PU.tercero_id = P.tercero_id
                                                AND P.tipo_profesional = TP.tipo_profesional
                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Consultar tipo profesional";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $tipoprofesional[$user]=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $tipoprofesional[$user];
    }
    /**
    *
    */
    function GetSwDatosComplementarios($cargo_cups,$numerocuenta)
    {
        static $datoscomplementarios;
        
        if(!empty($datoscomplementarios[$numerocuenta][$cargo_cups])) 
        {
            return $datoscomplementarios[$numerocuenta][$cargo_cups];
        }
        else
        {   
            list($dbconn) = GetDBconn();
            $query = "SELECT    sw_au,
                                                sw_ah,
                                                sw_an
                                FROM        rips_datos_complementarios
                                WHERE       cargo  ='".$cargo_cups."'
                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Consultar  rips_datos_complementarios";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $datoscomplementarios[$numerocuenta][$cargo_cups]=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $datoscomplementarios[$numerocuenta][$cargo_cups];
        }
    }
    
    /**
    *
    */
    function TraerValorEquipos($transaccion)
    {
            list($dbconn) = GetDBconn();
            $query = "
                    (
                    SELECT A.valor_cubierto
                    FROM cuentas_detalle A,
                        cuentas_liquidaciones_qx_equipos_fijos B
                    WHERE A.transaccion = B.transaccion
                    AND A.transaccion = $transaccion
                    )
                    UNION
                    (
                    SELECT A.valor_cubierto
                    FROM cuentas_detalle A,
                        cuentas_liquidaciones_qx_equipos_moviles B
                    WHERE A.transaccion = B.transaccion
                    AND A.transaccion = $transaccion
                    );
                    ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Consultar tipo profesional";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            $valor=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $valor[valor_cubierto];
    }
    /**
    *
    */
    function GetDatosActoQuirurgico($transaccion)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT ap_diagnostico_post_qx AS diagnostico_ppal,
                                            ap_diagnostico_id_complicacion AS complicacion,
                                            ap_forma_realizacion AS forma_realizacion,
                                            ap_diagnostico_post_qx2 AS diagnostico_relacionado
                                FROM rips_cuentas_detalle
                                WHERE transaccion  = ".$transaccion."
                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Consultar  rips_datos_complementarios";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $actoquirurgico=$result->GetRowAssoc($ToUpper = false);
            $result->Close();
            return $actoquirurgico;
    }

		function GetDatosActoQuirurgicoExcepcion($programacion)
		{
						list($dbconn) = GetDBconn();
						/*$query = "
											SELECT HCNOC.via_acceso AS forma_realizacion,
												HCNOC.diagnostico_post_qx AS diagnostico_ppal,
												HCNOC.diagnostico_id_complicacion AS complicacion,
												HCNOC.usuario_id AS usuario_nota,
												HCDI.tipo_diagnostico_id
											FROM hc_notas_operatorias_cirugias HCNOC,
												hc_evoluciones HCE,
												hc_diagnosticos_ingreso HCDI
											WHERE HCNOC.programacion_id  = ".$programacion."
											AND HCE.ingreso=$ingreso
											AND HCNOC.evolucion_id=HCE.evolucion_id 
											AND HCDI.evolucion_id=HCE.evolucion_id 
											AND HCDI.sw_principal ='1';
											";
						*/
							$query = "
											SELECT HCNOC.via_acceso AS forma_realizacion,
												HCNOC.diagnostico_post_qx AS diagnostico_ppal,
												HCNOC.diagnostico_id_complicacion AS complicacion,
												HCNOC.usuario_id AS usuario_nota
											FROM hc_notas_operatorias_cirugias HCNOC
											WHERE HCNOC.programacion_id  = ".$programacion.";
											";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Consultar  rips_datos_complementarios";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
						}
						$actoquirurgico=$result->GetRowAssoc($ToUpper = false);
						$result->Close();
						return $actoquirurgico;
		}

    function GetIMD($Cuenta)
    {
				list($dbconn) = GetDBconn();
				GLOBAL $ADODB_FETCH_MODE;
				$query=
					" (SELECT a.*
					
						FROM (SELECT a.codigo_producto,a.cargo_nom,
						a.precio,sum(a.cantidad) as cantidad,a.unidad,sum(a.valor_cargo) as valor_cargo,
						sum(a.valor_paciente) as valor_paciente,sum(a.valor_cliente) as valor_cliente,
						a.codigo_medicamento,a.autorizacion_ext,a.autorizacion_int,a.cargo_cups
						--,a.cargo as cargo_tarifario
						
									FROM (SELECT c.codigo_producto,        
									(CASE WHEN p_act.cod_principio_activo <> '000000' THEN
										(CASE WHEN p_act.descripcion <> '000000' THEN 
											(p_act.descripcion||' '||
											(CASE WHEN u.abreviatura IS NOT NULL THEN
											u.abreviatura 
											ELSE u.unidad_id END)||' '||         
											d.contenido_unidad_venta)
											
											ELSE             
											d.descripcion
											END
										)
									ELSE d.descripcion 
									END) as cargo_nom,
									(CASE WHEN a.facturado='1' THEN a.precio ELSE '0' END) as precio,
									(CASE WHEN a.cargo='DIMD' THEN (sum(a.cantidad))*-1 ELSE sum(a.cantidad) END) as cantidad,
									(CASE WHEN u.abreviatura IS NOT NULL THEN
									u.abreviatura 
									ELSE u.unidad_id END) as unidad,
									(CASE WHEN a.facturado='1' THEN sum(a.valor_cargo) ELSE '0' END) as valor_cargo, 
									(CASE WHEN a.facturado='1' THEN sum(a.valor_nocubierto) ELSE '0' END) as valor_paciente,
									(CASE WHEN a.facturado='1' THEN sum(a.valor_cubierto) ELSE '0' END) as valor_cliente,
									a.cargo,        
									med.codigo_medicamento,
									a.autorizacion_ext,
									a.autorizacion_int,
									a.cargo_cups
									FROM cuentas_detalle a,departamentos b,
									bodegas_documentos_d c,inventarios_productos d
									JOIN unidades u ON (d.unidad_id=u.unidad_id)
									LEFT JOIN medicamentos med ON(d.codigo_producto=med.codigo_medicamento)        
									LEFT JOIN inv_med_cod_principios_activos p_act ON(p_act.cod_principio_activo=med.cod_principio_activo)
									WHERE a.numerodecuenta = $Cuenta
									AND (a.cargo='DIMD' OR a.cargo='IMD')         
									AND a.departamento_al_cargar=b.departamento
									AND a.consecutivo=c.consecutivo
									AND c.codigo_producto=d.codigo_producto        
									AND a.paquete_codigo_id IS NULL
									GROUP BY c.codigo_producto,cargo_nom,a.facturado,a.precio,unidad,a.cargo,
									a.valor_cargo,a.valor_nocubierto,a.valor_cubierto,med.codigo_medicamento,a.autorizacion_ext,
									a.autorizacion_int,a.cargo_cups) as a
									GROUP BY 
									a.codigo_producto,a.cargo_nom,a.precio,
									a.unidad,a.codigo_medicamento,a.autorizacion_ext,
									a.autorizacion_int,a.cargo_cups
									--,a.cargo
								
								) as a
						WHERE a.cantidad>0)

						UNION

						(SELECT a.*
					
						FROM (SELECT a.codigo_producto,a.cargo_nom,
						a.precio,sum(a.cantidad) as cantidad,a.unidad,sum(a.valor_cargo) as valor_cargo,
						sum(a.valor_paciente) as valor_paciente,sum(a.valor_cliente) as valor_cliente,
						a.codigo_medicamento,a.autorizacion_ext,a.autorizacion_int,a.cargo_cups
						--,a.cargo as cargo_tarifario
						
									FROM (SELECT c.codigo_producto,        
									(CASE WHEN p_act.cod_principio_activo <> '000000' THEN
										(CASE WHEN p_act.descripcion <> '000000' THEN 
											(p_act.descripcion||' '||
											(CASE WHEN u.abreviatura IS NOT NULL THEN
											u.abreviatura 
											ELSE u.unidad_id END)||' '||         
											d.contenido_unidad_venta)
											
											ELSE             
											d.descripcion
											END
										)
									ELSE d.descripcion 
									END) as cargo_nom,
									(CASE WHEN a.sw_paquete_facturado='1' THEN a.precio ELSE '0' END) as precio,
									(CASE WHEN a.cargo='DIMD' THEN (sum(a.cantidad))*-1 ELSE sum(a.cantidad) END) as cantidad,
									(CASE WHEN u.abreviatura IS NOT NULL THEN
									u.abreviatura 
									ELSE u.unidad_id END) as unidad,
									(CASE WHEN a.sw_paquete_facturado='1' THEN sum(a.valor_cargo) ELSE '0' END) as valor_cargo, 
									(CASE WHEN a.sw_paquete_facturado='1' THEN sum(a.valor_nocubierto) ELSE '0' END) as valor_paciente,
									(CASE WHEN a.sw_paquete_facturado='1' THEN sum(a.valor_cubierto) ELSE '0' END) as valor_cliente,
									a.cargo,        
									med.codigo_medicamento,
									a.autorizacion_ext,
									a.autorizacion_int,
									a.cargo_cups
									FROM cuentas_detalle a,departamentos b,
									bodegas_documentos_d c,inventarios_productos d
									JOIN unidades u ON (d.unidad_id=u.unidad_id)
									LEFT JOIN medicamentos med ON(d.codigo_producto=med.codigo_medicamento)        
									LEFT JOIN inv_med_cod_principios_activos p_act ON(p_act.cod_principio_activo=med.cod_principio_activo)
									WHERE a.numerodecuenta = $Cuenta
									AND (a.cargo='DIMD' OR a.cargo='IMD')         
									AND a.departamento_al_cargar=b.departamento
									AND a.consecutivo=c.consecutivo
									AND c.codigo_producto=d.codigo_producto        
									AND a.paquete_codigo_id IS NOT NULL
									GROUP BY c.codigo_producto,cargo_nom,a.sw_paquete_facturado,a.precio,unidad,a.cargo,
									a.valor_cargo,a.valor_nocubierto,a.valor_cubierto,med.codigo_medicamento,a.autorizacion_ext,
									a.autorizacion_int,a.cargo_cups) as a
									GROUP BY 
									a.codigo_producto,a.cargo_nom,a.precio,
									a.unidad,a.codigo_medicamento,a.autorizacion_ext,
									a.autorizacion_int,a.cargo_cups
									--,a.cargo
								
								) as a
						WHERE a.cantidad>0)
				";
        
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if($result->EOF){
						$this->error = "Error al ejecutar la consulta.<br>";
						$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
						return false;
				}
				if($result->RecordCount()>0){
					while ($data = $result->FetchRow()){
/*            $datos[$data['departamento_al_cargar']][$data['departamento_nom']]
						[$data['grupo_tipo_cargo']][$data['grupo_cargo_nom']]
						[$data['cargo_cups']]=$data;*/
						$datos[]=$data;
					}        
				}
				return $datos;
     }

    /**
    *
    */

    function GetConsultaCargo($codigo_producto,$cargo_tarifario)
    {
				list($dbconn) = GetDBconn();
				GLOBAL $ADODB_FETCH_MODE;
				$query="SELECT a.cargo as cargo_tarifario,
									a.cargo_cups
								FROM cuentas_detalle a,bodegas_documentos_d c,         
									inventarios_productos d
								WHERE c.codigo_producto = '".$codigo_producto."'
									--AND (a.cargo='DIMD' OR a.cargo='IMD')         
									AND a.cargo='IMD'        
									AND a.consecutivo=c.consecutivo
									AND c.codigo_producto=d.codigo_producto  
									limit 1 offset 0 ;";  
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if($result->EOF){
						$this->error = "Error al ejecutar la consulta cuentas_detalle.<br>";
						$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
						return false;
				}
				if($result->RecordCount()>0){
						$datos=$result->FetchRow();       
				}
				return $datos[cargo_tarifario];
     }
		 
    /**
    *
    */
    function GetConsultaCargoCuenta($transaccion)
    {
				list($dbconn) = GetDBconn();
				GLOBAL $ADODB_FETCH_MODE;
				$query="SELECT a.cargo as cargo_tarifario,
									a.cargo_cups
								FROM cuentas_detalle a
								WHERE a.transaccion = $transaccion;";  
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if($result->EOF){
						$this->error = "Error al ejecutar la consulta cuentas_detalle.<br>";
						$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
						return false;
				}
				if($result->RecordCount()>0){
						$datos=$result->FetchRow();       
				}
				return $datos;
     }
    /**
    *
    */
    function GetTotalFacturaContado($cuenta)
    {
				list($dbconn) = GetDBconn();
				GLOBAL $ADODB_FETCH_MODE;
				$query="SELECT b.total_factura
								FROM fac_facturas_cuentas a,
										fac_facturas b
								WHERE a.numerodecuenta = $cuenta
								AND a.sw_tipo IN ('0')
								AND a.empresa_id = b.empresa_id
								AND a.prefijo = b.prefijo
								AND a.factura_fiscal = b.factura_fiscal;";  
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if($result->EOF){
						$this->error = "Error al ejecutar la consulta cuentas_detalle.<br>";
						$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
						return false;
				}
				if(!$result->EOF){
						$datos=$result->fields[0];       
				}
				return $datos;
     }

    /**
    *
    */
    function InsertaRipsCuentasDetalle($cargo_cups)
    {
        //$cargo_cups = $_SESSION['TMP_DATOS']['cargos_cups'];
    
        $ac_fechaconsulta = $this->ConvFecha($_REQUEST['ac_fechaconsulta']);
        $ap_fechaprocedimiento = $this->ConvFecha($_REQUEST['ap_fechaprocedimiento']);
        $au_fechaingreso = $this->ConvFecha($_REQUEST['au_fechaingreso']);
        $au_fechasalida = $this->ConvFecha($_REQUEST['au_fechasalida']);
        $ah_fechaingreso = $this->ConvFecha($_REQUEST['ah_fechaingreso']);
        $ah_fechasalida = $this->ConvFecha($_REQUEST['ah_fechasalida']);
        $au_horarioingreso = $_REQUEST['au_horarioingreso'];
        $au_minuteroingreso =$_REQUEST['au_minuteroingreso'];
        $au_horariosalida = $_REQUEST['au_horariosalida'];
        $au_minuterosalida = $_REQUEST['au_minuterosalida'];
        $ah_horariosalida =$_REQUEST['ah_horariosalida'];
        $ah_minuterosalida = $_REQUEST['ah_minuterosalida'];
        $ah_horarioingreso =$_REQUEST['ah_horarioingreso'];
        $ah_minuteroingreso = $_REQUEST['ah_minuteroingreso'];
        
        ( $ac_fechaconsulta== '')?      $ac_fechaconsulta= 'NULL': $ac_fechaconsulta = "'$ac_fechaconsulta 00:00:00'";
        ( $ap_fechaprocedimiento== '')? $ap_fechaprocedimiento= 'NULL':$ap_fechaprocedimiento = "'$ap_fechaprocedimiento 00:00:00'";
        ($au_fechaingreso == '')?       $au_fechaingreso= 'NULL':$au_fechaingreso = "'$au_fechaingreso 00:00:00'";
        ( $au_fechasalida== '')?        $au_fechasalida = 'NULL':$au_fechasalida = "'$au_fechasalida 00:00:00'";
        ($ah_fechaingreso == '')?               $ah_fechaingreso= 'NULL':$ah_fechaingreso = "'$ah_fechaingreso 00:00:00'";
        ($ah_fechasalida == '')?                $ah_fechasalida= 'NULL':$ah_fechasalida = "'$ah_fechasalida 00:00:00'";
        ($au_horarioingreso == '')?         $au_horarioingreso = 'NULL':$au_horarioingreso = "'$au_horarioingreso:$au_minuteroingreso'";
        ($au_horariosalida == '')?          $au_horariosalida = 'NULL':$au_horariosalida = "'$au_horariosalida:$au_minuterosalida'";
        ($ah_horariosalida == '')?          $ah_horariosalida ='NULL' :$ah_horariosalida = "'$ah_horariosalida:$ah_minuterosalida'";
        ($ah_horarioingreso == '')?         $ah_horarioingreso = 'NULL' : $ah_horarioingreso ="'$ah_horarioingreso:$ah_minuteroingreso'";
        
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
       $query = "INSERT INTO rips_cuentas_detalle 
                                                    (numerodecuenta, autorizacion, cargo_cups,ac_fechaconsulta, ac_tipofinalidad, ac_causaexterna, ac_diagnostico, ac_tipodiagnostico, ap_fechaprocedimiento, ap_ambitoprocedimiento, ap_finalidadprocedimiento, au_fechaingreso, au_horaingreso, au_causaexterna, au_diagnosticosalida, au_destinosalida, au_estadosalida, au_fechasalida, au_horasalida, ah_viaingreso, ah_fechaingreso, ah_horarioingreso, ah_causaexterna, ah_diagnosticoingreso, ah_diagnosticosalida, ah_estadosalida, ah_fechasalida, ah_horariosalida, usuario_id)
                                                    VALUES (".$_REQUEST['numerodecuenta'].", 
                                                                    ".$_REQUEST['autorizacion'].", 
                                                                    '".$cargo_cups."',
                                                                    ".$ac_fechaconsulta.",
                                                                    '".$_REQUEST['ac_tipofinalidad']."', 
                                                                    '".$_REQUEST['ac_causaexterna']."', 
                                                                    '".$_REQUEST['ac_diagnostico']."', 
                                                                    '".$_REQUEST['ac_tipodiagnostico']."', 
                                                                    ".$ap_fechaprocedimiento.", 
                                                                    '".$_REQUEST['ap_ambitoprocedimiento']."', 
                                                                    '".$_REQUEST['ap_finalidadprocedimiento']."', 
                                                                    ".$au_fechaingreso.", 
                                                                    ".$au_horarioingreso.", 
                                                                    '".$_REQUEST['au_causaexterna']."', 
                                                                    '".$_REQUEST['au_diagnosticosalida']."', 
                                                                    '".$_REQUEST['au_destinosalida']."', 
                                                                    '".$_REQUEST['au_estadosalida']."', 
                                                                    ".$au_fechasalida.", 
                                                                    ".$au_horariosalida.", 
                                                                    '".$_REQUEST['ah_viaingreso']."', 
                                                                    ".$ah_fechaingreso.", 
                                                                    ".$ah_horarioingreso.", 
                                                                    '".$_REQUEST['ah_causaexterna']."', 
                                                                    '".$_REQUEST['ah_diagnosticoingreso']."', 
                                                                    '".$_REQUEST['ah_diagnosticosalida']."', 
                                                                    '".$_REQUEST['ah_estadosalida']."', 
                                                                    ".$ah_fechasalida.", 
                                                                    ".$ah_horariosalida.",
                                                                    ".UserGetUID().")   

                                ";
        $dbconn->debug = false;
        $result = $dbconn->Execute($query);
        
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al insertar en rips_cuentas_detalle";
              echo  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg().'=='.$query;
                $dbconn->RollBackTrans();
                return false;
            }
        
        $dbconn->CommitTrans();
        $result->Close();
        return true;
    }
    /**
    * Metodo encargado de construir la cadena de informacion para el Archivo AF
    * @param $d_envio Array informacio basica de la factura realcionada en el envio
    * @param $datos_prestador_servicio Array Informacion del prestador de Servicios
    * @param $datos_entidad_administradora Array Informacion de la entidad adminitradora
    */
    function CallConstruyeAF(&$arregloAF,$d_envio,$datos_prestador_servicio,$datos_entidad_administradora)
    {
				if($datos_entidad_administradora[sw_tipo_plan] == '1')//CASO PLANES SOAT
				{
					$dat=$this->GetPolizaSoat($d_envio[ingreso]);
					$poliza=explode('-',$dat[poliza]);//EXTRAER SOLO EL CAMPO NECESARIO DEL NRO DE LA POLIZA
				}
				else
				{
					$poliza[1]="";
				}
				//
				$totalContado = $this->GetTotalFacturaContado($d_envio[numerodecuenta]);
				//
        $arregloAF.=$datos_prestador_servicio[codigo_sgsss].",";//R codigo_sgsss del prestador de servicio
        $arregloAF.=$this->GetFormatoCampoTexto($datos_prestador_servicio[razon_social],30).",";//R razon_social del prestador de servicio
        $arregloAF.=$datos_prestador_servicio[tipo_id_tercero].",";//R tipo_id_tercero del prestador de servicio
        $arregloAF.=$datos_prestador_servicio[id].",";//R tipo_id_tercero del prestador de servicio
        $arregloAF.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";//R numero de factura
        $arregloAF.=$this->FechaStamp($d_envio[fecha_registro]).",";//R
        $arregloAF.=$this->FechaStamp($d_envio[fecha_inicial]).",";//R
        $arregloAF.=$this->FechaStamp($d_envio[fecha_final]).",";//R
        $arregloAF.=$datos_entidad_administradora[codigo_sgsss].",";//R codigo_sgsss de la entidad administradora
        $arregloAF.=$this->GetFormatoCampoTexto($datos_entidad_administradora[nombre_tercero],30).",";// R nombre  de la entidad administradora
        $arregloAF.=$datos_entidad_administradora[num_contrato].",";//num_contrato de la entidad administradora
        $arregloAF.=$this->GetFormatoCampoTexto($datos_entidad_administradora[plan_descripcion],30).",";//plan_descripcion de la entidad administradora
        $arregloAF.=$poliza[1].",";//numero de poliza si el ingreso fue por SOAT
				if($totalContado > 0)
				{
					$arregloAF.=(int)$totalContado.",";
				}
				else
				{
        	$arregloAF.=(int)($d_envio[valor_cuota_paciente] + $d_envio[valor_cuota_moderadora]).","; //R valor del copago
				}
        $arregloAF.='0'.",";//valor de la comision
        $arregloAF.=(int)$d_envio[descuento].",";//descuento dado a la factura
        $arregloAF.=(int)$d_envio[total_factura];//R valor total de la factura
        $arregloAF.=$this->nl;
        return true;
    }
    /**.
    *
    */
    function CallConstruyeAM(&$arregloAM,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD,$v_cuentas)
    {
        $datos_medicamento = $this->GetDatosMedicamento($d_envio[codigo_medicamento],$d_envio[codigo_producto]);
        
				if(!empty($d_envio[valor_cliente]))
				{
					$valor_cubierto =$d_envio[valor_cliente];
				}
				else
				{
					$valor_cubierto =$d_envio[valor_cubierto];
				}
        
				if(!empty($v_cuentas[prefijo]) AND !empty($v_cuentas[factura_fiscal]))
				{
					$prefijo=$v_cuentas[prefijo];
					$factura_fiscal=$v_cuentas[factura_fiscal];
				}
				else
				{
					$prefijo=$d_envio[prefijo];
					$factura_fiscal=$d_envio[factura_fiscal];
				}
        $arregloAM.=$prefijo."".$factura_fiscal.",";//R
        $arregloAM.=$datos_prestador_servicio[codigo_sgsss].",";//R
        $arregloAM.=$datos_usuario[tipo_id_paciente].",";//R
        $arregloAM.=$datos_usuario[paciente_id].",";// R 
				if(empty($autorizacion)) $autorizacion = "1";
        $arregloAM.=$autorizacion.",";
        $arregloAM.=$datos_medicamento[codigo_producto].",";
        $arregloAM.=$datos_medicamento[sw_pos].",";//R tipo medicamento
        //$arregloAM.=$datos_medicamento[descripcion_abreviada].",";
		$arregloAM.=$this->GetFormatoCampoTexto($datos_medicamento[descripcion_abreviada],30).",";
        $arregloAM.=$this->GetFormatoCampoTexto($datos_medicamento[farmaco],20).",";
        $arregloAM.=$this->GetFormatoCampoTexto($datos_medicamento[concentracion_forma_farmacologica],20).",";
        $arregloAM.=$this->GetFormatoCampoTexto($datos_medicamento[unidad],5).",";
        $arregloAM.=(int)$d_envio[cantidad].",";//R  
        $arregloAM.=(int)$d_envio[precio].",";//R
        $arregloAM.=(int)$valor_cubierto;//R
        $arregloAM.=$this->nl;
        //$this -> SetAcumuladorAD(&$vectorAD,$prefijo."".$factura_fiscal,$datos_prestador_servicio[codigo_sgsss],'12',$d_envio[cantidad],$valor_cubierto);
        return true;
    }
    /**.
    *
    */
			//FUNCION ANTERIOR 03/11/2006
/*    function CallConstruyeAM(&$arregloAM,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD)
    {
        $datos_medicamento = $this->GetDatosMedicamento($d_envio[codigo_medicamento],$d_envio[codigo_producto]);
        
        $arregloAM.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";//R
        $arregloAM.=$datos_prestador_servicio[codigo_sgsss].",";//R
        $arregloAM.=$datos_usuario[tipo_id_paciente].",";//R
        $arregloAM.=$datos_usuario[paciente_id].",";// R 
        $arregloAM.=$autorizacion.",";
        $arregloAM.=$datos_medicamento[codigo_producto].",";
        $arregloAM.=$datos_medicamento[sw_pos].",";//R tipo medicamento
        $arregloAM.=$datos_medicamento[descripcion_abreviada].",";
        $arregloAM.=$this->GetFormatoCampoTexto($datos_medicamento[farmaco],20).",";
        $arregloAM.=$this->GetFormatoCampoTexto($datos_medicamento[concentracion_forma_farmacologica],20).",";
        $arregloAM.=$this->GetFormatoCampoTexto($datos_medicamento[unidad],5).",";
        $arregloAM.=$d_envio[cantidad].",";//R  
        $arregloAM.=$d_envio[precio].",";//R
        $arregloAM.=$d_envio[valor_cubierto];//R
        $arregloAM.=$this->nl;

        $this -> SetAcumuladorAD(&$vectorAD,$d_envio[prefijo]."".$d_envio[factura_fiscal],$datos_prestador_servicio[codigo_sgsss],'12',$d_envio[cantidad],$d_envio[valor_cubierto]);
//          //entra si es insumo y no medicamento
//      if(!empty($datos_cups[concepto_rips]))
//      {
//          $this -> SetAcumuladorAD(&$vectorAD,$d_envio[prefijo]."".$d_envio[factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$d_envio[cantidad],$d_envio[valor_cubierto]);
//      }
        return true;
    }*/
    
    /**
    *
    */
    function CallConstruyeAT(&$arregloAT,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD,$datos_entidad_administradora,$v_cuentas)
    {
        $codigo_servivio ='';
        $tipo_servicio ='';
        $descripcion ='';
        if(!empty($d_envio[codigo_medicamento]))
        {
            $datos_medicamento = $this->GetDatosMedicamento($d_envio[codigo_medicamento],$d_envio[codigo_producto]);
            $descripcion=$this->GetFormatoCampoTexto($datos_medicamento[descripcion_abreviada],60);
        }
        else//es un insumo
        {
            $cod_producto=$d_envio[codigo_producto];//Nuevo 11/05/2006
            $datos_medicamento = $this->GetDatosInsumo($cod_producto);
            $tipo_servicio = '1';
            
            if($d_envio[cargo_tarifario]
               AND $d_envio[cargo_tarifario] <> 'IMD'
               AND $d_envio[cargo_tarifario] <> 'DMD')
            {
              //$codigo_servivio = $d_envio[cargo_tarifario];
              $cargo = $this->GetConsultaCargoCuenta($d_envio[transaccion]);
              if($datos_entidad_administradora[sw_rips_con_cargo_cups] == '1')
              {
               $codigo_servivio = $cargo[cargo_cups];
              }
              else
              {
               $codigo_servivio = $cargo[cargo_tarifario];
              }
            }
            else
            {
             $codigo_servivio = $this->GetConsultaCargo($d_envio[codigo_producto]);
            }
           $descripcion=$this->GetFormatoCampoTexto($datos_medicamento[descripcion_abreviada],60);
        }

        if($datos_cups[tipo_servicio]=='2' || $datos_cups[tipo_servicio]=='3' || $datos_cups[tipo_servicio]=='4')
        {
            $descripcion = $datos_cups[descripcion_cups];
            $tipo_servicio = $datos_cups[tipo_servicio];
        }

        if($datos_entidad_administradora[sw_tipo_plan] == '3')
        {
                $valor_cubierto = 0;
                $precio = 0;
        }
        else
        {
              if(!empty($d_envio[valor_cliente]))
              {
                $valor_cubierto =$d_envio[valor_cliente];
              }
              else
              {
                $valor_cubierto =$d_envio[valor_cubierto];
              }
              $precio =$d_envio[precio];
        }
        if(!empty($v_cuentas[prefijo]) AND !empty($v_cuentas[factura_fiscal]))
        {
         $prefijo=$v_cuentas[prefijo];
         $factura_fiscal=$v_cuentas[factura_fiscal];
        }
        else
        {
         $prefijo=$d_envio[prefijo];
         $factura_fiscal=$d_envio[factura_fiscal];
        }
        if(empty($descripcion))
        {
         $descripcion = $datos_cups[descripcion_cups];
        }
        $descrip = substr($descripcion,0,60);//este campo debe ser de maximo 60 caracteres
        $arregloAT.=$prefijo."".$factura_fiscal.",";//R
        $arregloAT.=$datos_prestador_servicio[codigo_sgsss].",";//R
        $arregloAT.=$datos_usuario[tipo_id_paciente].",";//R
        $arregloAT.=$datos_usuario[paciente_id].",";//R
        $arregloAT.=$autorizacion.",";
        $arregloAT.=$tipo_servicio.",";//R
        $arregloAT.=$codigo_servivio.",";
        $arregloAT.=$descrip.",";
        $arregloAT.=(int)$d_envio[cantidad].",";//R
        $arregloAT.=(int)$precio.",";//R
        $arregloAT.=(int)$valor_cubierto;//R
        $arregloAT.=$this->nl;
        if($d_envio[valor_cubierto])
        {$this -> SetAcumuladorAD(&$vectorAD,$prefijo."".$factura_fiscal,$datos_prestador_servicio[codigo_sgsss],'09',$d_envio[cantidad],$d_envio[valor_cubierto]);}
        return true;
    }
    /**
    *
    */
			//FUNCION ANTERIOR 03/11/2006
/*    function CallConstruyeAT(&$arregloAT,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD,$datos_entidad_administradora)
    {
        $codigo_servivio ='';
        $tipo_servicio ='';
        $descripcion ='';
        if(!empty($d_envio[codigo_medicamento]))
        {
            $datos_medicamento = $this->GetDatosMedicamento($d_envio[codigo_medicamento],$d_envio[codigo_producto]);
            $descripcion=$this->GetFormatoCampoTexto($datos_medicamento[descripcion_abreviada],60);
        }
        else//es un insumo
        {
            $cod_producto=$d_envio[codigo_producto];//Nuevo 11/05/2006
            $datos_medicamento = $this->GetDatosInsumo($cod_producto);
            $tipo_servicio = '1';
            $codigo_servivio = $d_envio[cargo_tarifario];
            $descripcion=$this->GetFormatoCampoTexto($datos_medicamento[descripcion_abreviada],60);
        }

        if($datos_cups[tipo_servicio]=='2' || $datos_cups[tipo_servicio]=='3' || $datos_cups[tipo_servicio]=='4')
        {
            $descripcion = $datos_cups[descripcion_cups];
            $tipo_servicio = $datos_cups[tipo_servicio];
        }

        if($datos_entidad_administradora[sw_tipo_plan] == '3')
        {
                $valor_cubierto = 0;
                $precio = 0;
        }
        else
        {
                $valor_cubierto =$d_envio[valor_cubierto];
                $precio =$d_envio[precio];
        }
        $descrip = substr($descripcion,0,60);//este campo debe ser de maximo 60 caracteres
        $arregloAT.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";//R
        $arregloAT.=$datos_prestador_servicio[codigo_sgsss].",";//R
        $arregloAT.=$datos_usuario[tipo_id_paciente].",";//R
        $arregloAT.=$datos_usuario[paciente_id].",";//R
        $arregloAT.=$autorizacion.",";
        $arregloAT.=$tipo_servicio.",";//R
        $arregloAT.=$codigo_servivio.",";
        $arregloAT.=$descrip.",";
        $arregloAT.=$d_envio[cantidad].",";//R
        $arregloAT.=$precio.",";//R
        $arregloAT.=$valor_cubierto;//R
        $arregloAT.=$this->nl;
        //$this -> SetAcumuladorAD(&$vectorAD,$d_envio[prefijo]."".$d_envio[factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$d_envio[cantidad],$d_envio[valor_cubierto]);
        $this -> SetAcumuladorAD(&$vectorAD,$d_envio[prefijo]."".$d_envio[factura_fiscal],$datos_prestador_servicio[codigo_sgsss],'09',$d_envio[cantidad],$d_envio[valor_cubierto]);

        return true;
    }*/
    
    /**
    *
    */
    function CallConstruyeAC(&$arregloAC,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_entidad_administradora,$datos_cups,&$vectorAD)
    {
      $registros = 0;
        static $cuotamoderadora;
        $valor_cuota_moderadora = 0;
        
        if(empty($cuotamoderadora[$d_envio[numerodecuenta]])) 
        {
            $cuotamoderadora[$d_envio[numerodecuenta]] = true;
            $valor_cuota_moderadora = $d_envio[valor_cuota_moderadora];
        }
        if($datos_entidad_administradora[sw_tipo_plan] == '3')
        {
            $valor_cubierto = 0;
            $valor_cuota_moderadora = 0;
            $valor_total_empresa = 0;
        }
        else
        {
            $valor_cubierto =$d_envio[valor_cubierto];
            $valor_cuota_moderadora = $d_envio[valor_cuota_moderadora];
            //$valor_total_empresa = $d_envio[valor_total_empresa];
            //$valor_total_empresa = ($d_envio[valor_cubierto] - $d_envio[valor_cuota_moderadora]);
            //$valor_cuota_moderadora = 0;
            $valor_total_empresa = ($d_envio[valor_cubierto] - $valor_cuota_moderadora);
        }
        //parametro segun la contratacion.Se debe trabajar con cargo tarifario o cargo cups
        if($datos_entidad_administradora[sw_rips_con_cargo_cups] == '1')
            $cod_cargo = $d_envio[cargo_cups];
        else
            $cod_cargo = $d_envio[cargo_tarifario];
            
        if(empty($d_envio[cargo_manual]))
        { 
          $datoscita = $this -> GetDatosPrincipalesCita($d_envio[ingreso]);
          $diagp = $diag = $finalidades = array();
          for($m=0; $m<sizeof($datoscita); $m++)
          {
            //Por si no se esta llenando la finalidad de la evolucion por
            //tener el modulo de finalidad como no obligatorio
            if(empty($datoscita[$m]['tipo_finalidad_id']))
              $datoscita[$m]['tipo_finalidad_id'] = '10';
            
            if(empty($datoscita[0]['tipo_atencion_id']))
              $datoscita[$m]['tipo_atencion_id'] = '15';
            
            $finalidades[$datoscita[$m]['tipo_finalidad_id']] = $datoscita[$m]['tipo_atencion_id'];
            
            if(!empty($datoscita[$m][sw_principal]))
            {
              $diagp[$datoscita[$m]['tipo_finalidad_id']] = $datoscita[$m][tipo_diagnostico_id];
              $tipo[$datoscita[$m]['tipo_finalidad_id']] = $datoscita[$m][tipo_diagnostico];
            }
            else
            {  
              $diag[$datoscita[$m]['tipo_finalidad_id']][]=$datoscita[$m][tipo_diagnostico_id];  
            }
          }
          
          $FC = $this->FechaCita($d_envio[transaccion]);
          if(empty($FC)) $FC=$d_envio[fecha_cargo];  
          
          foreach($finalidades as $key => $atencion)
          {
            if(empty($diagp[$key]))
            {
              $diagp[$key] = 'T888'; 
              $tipo[$key] = 1;
            }
            
            $arregloAC .= $d_envio['prefijo']."".$d_envio['factura_fiscal'].",";
            $arregloAC .= $d_envio['codigo_prestador'].",";
            $arregloAC .= $datos_usuario['tipo_id_paciente'].",";
            $arregloAC .= $datos_usuario['paciente_id'].",";
            $arregloAC .= $this->FechaStamp($FC).",";//Fecha de la consulta
            $arregloAC .= $autorizacion.",";//numero de autorizacion
            $arregloAC .= $cod_cargo.",";//codigo procedimiento
            $arregloAC .= $key.",";//finalidad de la consulta
            $arregloAC .= $atencion.",";//causa externa
            $arregloAC .= $diagp[$key].",";//cod diag ppal
            $arregloAC .= $diag[$key][0].",";//cod diag relacionado No.1
            $arregloAC .= $diag[$key][1].",";//cod diag relacionado No.2
            $arregloAC .= $diag[$key][2].",";//cod diag relacionado No.3
            $arregloAC .= $tipo[$key].",";//tipo diag ppal
            $arregloAC .= (int)$valor_cubierto.",";//valor consulta
            $arregloAC .= (int)$valor_cuota_moderadora.",";
            $arregloAC .= (int)$valor_total_empresa;
            $arregloAC .= $this->nl;
            $registros++;
          }
        }
        else
        {
          //cargos ingresados manualmente
          $FC = $d_envio[ac_fechaconsulta];
          $autorizacion = $d_envio[autorizacion];
          $tipo_finalidad_id = $d_envio[ac_tipofinalidad];
          $tipo_atencion_id = $d_envio[ac_causaexterna];
          $diagp = $d_envio[ac_diagnostico];
          $diag[0] = '';
          $diag[1] = '';
          $diag[2] = '';
          $tipo = $d_envio[ac_tipodiagnostico];
          
          if(empty($diagp))
          {
            $diagp = 'T888'; 
            $tipo = 1;
          }
        
          $arregloAC.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";
          $arregloAC.=$d_envio['codigo_prestador'].",";
          $arregloAC.=$datos_usuario[tipo_id_paciente].",";
          $arregloAC.=$datos_usuario[paciente_id].",";
          $arregloAC.=$this->FechaStamp($FC).",";//Fecha de la consulta
          $arregloAC.=$autorizacion.",";//numero de autorizacion
          $arregloAC.=$cod_cargo.",";//codigo procedimiento
          $arregloAC.=$tipo_finalidad_id.",";//finalidad de la consulta
          $arregloAC.=$tipo_atencion_id.",";//causa externa
          $arregloAC.=$diagp.",";//cod diag ppal
          $arregloAC.=$diag[0].",";//cod diag relacionado No.1
          $arregloAC.=$diag[1].",";//cod diag relacionado No.2
          $arregloAC.=$diag[2].",";//cod diag relacionado No.3
          $arregloAC.=$tipo.",";//tipo diag ppal
          $arregloAC.=(int)$valor_cubierto.",";//valor consulta
          $arregloAC.=(int)$valor_cuota_moderadora.",";
          $arregloAC.=(int)$valor_total_empresa;
          $arregloAC.=$this->nl;
          $registros++;
        }
        $this -> SetAcumuladorAD(&$vectorAD,$d_envio['prefijo']."".$d_envio[factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$d_envio[cantidad],$valor_total_empresa);
        return $registros;
    }
    
    /**
    *
    */
    function CallConstruyeAP(&$arregloAP,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$tipo_rips,$datos_cups,&$vectorAD,$datos_entidad_administradora)
    {
            $ambito='';
            //arciho AP
            if($datos_entidad_administradora[sw_rips_con_cargo_cups] == '1')
                $cod_cargo = $d_envio[cargo_cups];
            else
                $cod_cargo = $d_envio[cargo_tarifario];
            $diag_odontologicoOP = '';
            $atendido_por ='5';// este coresponde a "otro", revizar por que no esta parametrizado
            if($tipo_rips == 'OP')
            {
                $diag_odontologicoOP = GetDiagnosticosOdontologiaAP($d_envio[transaccion]);
                $atendido_por ='5';
            }
            else
            {
                //$ActoQuirurgico = $this -> GetDatosActoQuirurgico($d_envio[transaccion]);
/*              $diag_odontologicoOP=$d_envio[diagnostico_ppal];
                $diagnostico_relacionado=$d_envio[diagnostico_relacionado];
                $complicacion=$d_envio[complicacion];
                $forma_realizacion=$d_envio[forma_realizacion];*/
                $ActoQuirurgico = $this -> GetDatosActoQuirurgicoExcepcion($d_envio[programacion_id]);
                $diag_odontologicoOP=$ActoQuirurgico[diagnostico_ppal];
                $diagnostico_relacionado=$ActoQuirurgico[diagnostico_relacionado];
                $complicacion=$ActoQuirurgico[complicacion];
                $forma_realizacion=$ActoQuirurgico[forma_realizacion];
                $atendido_por = $this -> GetTipoProfesional($d_envio[transaccion]);
            }
            if(empty($atendido_por[codigo_rips]))
            {
                $atendido_por[codigo_rips] ='5';
            }
            if(empty($d_envio[cargo_manual]))
            {
                $ambito=$this->GetAmbito($d_envio[servicio_cargo]);
                $finalidad_proc = "1";
                $fecha_proc = $this->FechaStamp($d_envio[fecha_cargo]);
            }
            else
            {//cargos ingresados manualmente
                $fecha_proc     = $this->FechaStamp($d_envio[ap_fechaprocedimiento]);
                //$autorizacion   = $d_envio[autorizacion];
                $ambito         = $d_envio[ap_ambitoprocedimiento];
                $finalidad_proc = $d_envio[ap_finalidadprocedimiento];
            }

            if($datos_entidad_administradora[sw_tipo_plan] == '3')
            {
                    $valor_cubierto = 0;
            }
            else
            {
                    $valor_cubierto =$d_envio[valor_cubierto];
            }
            $arregloAP.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";//R
            //$arregloAP.=$datos_prestador_servicio[codigo_sgsss].",";
            $arregloAP.=$d_envio['codigo_prestador'].",";
            $arregloAP.=$datos_usuario[tipo_id_paciente].",";//R
            $arregloAP.=$datos_usuario[paciente_id].",";//R
            $arregloAP.=$fecha_proc.",";//R fecha procedimiento
            $arregloAP.=$autorizacion.",";//numero autorizacion
            $arregloAP.=$cod_cargo.",";//R codigo procedimiento
            $arregloAP.=$ambito.",";//R ambito de realizacion del procedimiento
            $arregloAP.=$finalidad_proc.",";//R finalidad del procedimeinto
            $arregloAP.=$atendido_por[codigo_rips].",";//personal que atiende
            $arregloAP.=$diag_odontologicoOP.",";//diag ppal $diag_odontologicoOP.
            $arregloAP.=$diagnostico_relacionado.",";//diag relacionado $diagnostico_relacionado.
            $arregloAP.=$complicacion.",";//complicacion $complicacion.
            $arregloAP.=$forma_realizacion.",";//forma de realizacion del acto quirurgico $forma_realizacion.
            $arregloAP.=(int)$valor_cubierto;//R
            $arregloAP.=$this->nl;
            $this -> SetAcumuladorAD(&$vectorAD,$d_envio[prefijo]."".$d_envio[factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$d_envio[cantidad],$d_envio[valor_cubierto]);
            return true;
    }
    
    /**
    *
    */
    function CallConstruyeAU(&$arregloAU,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion)
    {
        $destino='';
        $diagEp=$diagE='';
        $datoscama = $this ->GetDatosCama($d_envio[numerodecuenta]);
        $causaExt=$this->GetCausaExterna($d_envio[ingreso]);
        $diagEgre=$this->GetDatosEgresoPaciente($d_envio[ingreso]);
        if(empty($d_envio[cargo_manual]))
        {
            for($m=0; $m<sizeof($diagEgre); $m++)
            {
                        if(!empty($diagEgre[$m][sw_principal]))
                        {
                                $diagEp=$diagEgre[$m][tipo_diagnostico_id];
                        }
                        else
                        {  $diagE[]=$diagEgre[$m][tipo_diagnostico_id];  }
            }
    
            $defuncion=$this->GetDatosDefuncion($d_envio[ingreso]);
            if(!empty($defuncion))
            {  $estado=2;   }//muerto
            else
            {  $estado=1;   }//vivo
            
            if($datoscama[tipo_clase_cama_id] == 3 OR $datoscama[tipo_clase_cama_id] == 2 OR $datoscama[tipo_clase_cama_id] == 1)
            {   
                    $destino = $this->GetDestinoPaciente($d_envio[ingreso],$d_envio[numerodecuenta]);
            }
        }
        else
        {//cargos ingresados manualmente
           $datoscama[fecha_ingreso] = $d_envio[au_fechaingreso];
            $datoscama[fecha_ingreso] = $d_envio[au_horaingreso];
            $autorizacion = $d_envio[autorizacion];
            $causaExt = $d_envio[au_causaexterna];
            $diagEp = $d_envio[au_diagnosticosalida];
            $diagE[0] = '';
            $diagE[1] = '';
            $diagE[2] = '';
            $destino = $d_envio[au_destinosalida];
            $estado = $d_envio[au_estadosalida];
            $defuncion = '';//ojo mirar esto
            $datoscama[fecha_egreso] = $d_envio[au_fechasalida];
            $datoscama[fecha_egreso] = $d_envio[au_horasalida];
		}
        if(empty($destino))
        {   
            $destino = 2;
		}			
        $arregloAU.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";
        $arregloAU.=$datos_prestador_servicio[codigo_sgsss].",";
        $arregloAU.=$datos_usuario[tipo_id_paciente].",";
        $arregloAU.=$datos_usuario[paciente_id].",";
        $arregloAU.=$this->FechaStamp($datoscama[fecha_ingreso]).",";//R fecha ingreso
        $arregloAU.=$this->HoraStamp($datoscama[fecha_ingreso]).",";//R hora de egreso
        $arregloAU.=$autorizacion.",";//numero autorizacion
        $arregloAU.=$causaExt.",";//R causa externa
        $arregloAU.=$diagEp.",";//R diag salida
        $arregloAU.=$diagE[0].",";//diag salida No1
        $arregloAU.=$diagE[1].",";//diag salida No2
        $arregloAU.=$diagE[2].",";//diag salida No3
        $arregloAU.=$destino.",";//R destino Salida
        $arregloAU.=$estado.",";//R estado salida
        $arregloAU.=$defuncion.",";//causa muerte
        $arregloAU.=$this->FechaStamp($datoscama[fecha_egreso]).",";//R fecha salida
        $arregloAU.=$this->HoraStamp($datoscama[fecha_egreso]);//r hora Salida
        $arregloAU.=$this->nl;
        return true;
    }
    
    /**
    *
    */
    function CallConstruyeUS(&$arregloUS,$d_envio,$datos_entidad_administradora,$datos_usuario)
    {
        $regimen = $this->GetRegimen($d_envio[plan_id]);
        $Edad=CalcularEdad($datos_usuario[fecha_nacimiento],date('Y-m-d'));
        
        $arregloUS.=$datos_usuario[tipo_id_paciente].",";//R
        $arregloUS.=$datos_usuario[paciente_id].",";//R
        $arregloUS.=$datos_entidad_administradora[codigo_sgsss].",";//R
        $arregloUS.=$regimen[regimen_id].",";//R
        $arregloUS.=$datos_usuario[primer_apellido].",";
        $arregloUS.=$datos_usuario[segundo_apellido].",";
        $arregloUS.=$datos_usuario[primer_nombre].",";
        $arregloUS.=$datos_usuario[segundo_nombre].",";
        $arregloUS.=$Edad[edad_rips].",";
        $arregloUS.=$Edad[unidad_rips].",";
        $arregloUS.=$datos_usuario[sexo_id].",";
        $arregloUS.=$datos_usuario[tipo_dpto_id].",";
        $arregloUS.=$datos_usuario[tipo_mpio_id].",";
        $arregloUS.=$datos_usuario[zona_residencia];
        $arregloUS.=$this->nl;
        return true;
    }
    
    /**
    *
    */
    function CallConstruyeAH(&$arregloAH,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion)
    { 
        $fechas='';
        $datosingreso = $this -> GetDatosIngreso($d_envio[ingreso]);
        $causaExt=$this->GetCausaExterna($d_envio[ingreso]);
        $diagEgre=$this->GetDatosEgresoPaciente($d_envio[ingreso]);
        //$datoscama = $this ->GetDatosCama($transaccion);
        //$datoscama = $this ->GetDatosCama($d_envio[numerodecuenta]);
        $datoscama = $this ->GetDatosCama($d_envio[ingreso]);
        if(empty($d_envio[cargo_manual]))
        {
            //$DatosIngreso=$this->GetDatosIngreso($ing[0][ingreso]);
            $DatosIngreso=$this->GetDatosIngreso($d_envio[ingreso]);
            for($m=0; $m<sizeof($diagEgre); $m++)
            {
                        if(!empty($diagEgre[$m][sw_principal]))
                        {
                                $diagEp=$diagEgre[$m][tipo_diagnostico_id];
                        }
                        else
                        {  $diagE[]=$diagEgre[$m][tipo_diagnostico_id];  }
            }
            $defuncion=$this->GetDatosDefuncion($d_envio[ingreso]);
            if(!empty($defuncion))
            {  $estado=2;   }//muerto
            else
            {  $estado=1;   }//vivo
        }
        else
        {//cargos ingresados manualmente
            $DatosIngreso[via_ingreso_id] = $d_envio[ah_viaingreso];
            $datoscama[fecha_ingreso] = $d_envio[ah_fechaingreso];
            $datoscama[fecha_ingreso] = $d_envio[ah_horarioingreso];
            $autorizacion = $d_envio[autorizacion];
            $causaExt = $d_envio[ah_causaexterna];
            $DatosIngreso[tipo_diagnostico_id] = $d_envio[ah_diagnosticoingreso];
            $diagEp = $d_envio[ah_diagnosticosalida];
            $diagE[0] = '';
            $diagE[1] = '';
            $diagE[2] = '';
            $estado = $d_envio[ah_estadosalida];
            $datoscama[fecha_egreso] = $d_envio[ah_fechasalida];
            $datoscama[fecha_egreso] = $d_envio[ah_horariosalida];
        }
        if(empty($causaExt)) $causaExt='13';
        $arregloAH.=$d_envio[prefijo]."".$d_envio[factura_fiscal].",";//R
        $arregloAH.=$datos_prestador_servicio[codigo_sgsss].",";//R
        $arregloAH.=$datos_usuario[tipo_id_paciente].",";//R
        $arregloAH.=$datos_usuario[paciente_id].",";//R
        $arregloAH.=$datosingreso[via_ingreso_id].",";//R
        $arregloAH.=$this->FechaStamp($datoscama[fecha_ingreso]).",";
        $arregloAH.=$this->HoraStamp($datoscama[fecha_ingreso]).",";//R
        $arregloAH.=$autorizacion.",";
        $arregloAH.=$causaExt.",";//R
        $arregloAH.=$DatosIngreso[tipo_diagnostico_id].",";//R
        $arregloAH.=$diagEp.",";//R
        $arregloAH.=$diagE[0].",";
        $arregloAH.=$diagE[1].",";
        $arregloAH.=$diagE[2].",";
        $arregloAH.=",";
        $arregloAH.=$estado.",";//R
        $arregloAH.=$defuncion.",";
        $arregloAH.=$this->FechaStamp($datoscama[fecha_egreso]).",";//R
        $arregloAH.=$this->HoraStamp($datoscama[fecha_egreso]);//R
        $arregloAH.=$this->nl;
        return true;
    }
        /**
    *
    */
    function SetAcumuladorAD(&$vectorAD,$factura,$cod_prestador,$cod_concepto,$cantidad,$vlr_tot_concepto)
    {
    
        $vectorAD['VECTOR'][$factura][$cod_concepto]['factura']=$factura;
        $vectorAD['VECTOR'][$factura][$cod_concepto]['codigo_sgsss']=$cod_prestador;
        $vectorAD['VECTOR'][$factura][$cod_concepto]['concepto_rips']=$cod_concepto;
        $vectorAD['VECTOR'][$factura][$cod_concepto]['cantidad']+=$cantidad;
        $vectorAD['VECTOR'][$factura][$cod_concepto]['valor_cubierto']+=$vlr_tot_concepto;
        
        return true;
    }
    /**
    *
    */
    function CallConstruyeAD(&$arregloAD,&$vectorAD,&$ad)
    {
        foreach($vectorAD as $vAD => $AD1)
        {
            foreach($AD1 as $AD2 => $AD3)
            {
                foreach($AD3 as $AD => $datos)
                {
                    $arregloAD.=$datos[factura].",";
                    $arregloAD.=$datos[codigo_sgsss].",";
                    $arregloAD.=$datos[concepto_rips].",";
                    $arregloAD.=$datos[cantidad].",";
                    $arregloAD.=0 .",";
                    $arregloAD.=$datos[valor_cubierto];
                    $arregloAD.=$this->nl;
                    $ad++;
                }
            }
        }
        return true;
    }
    
    /**
    *
    */
    function CallConstuyeArchivos($codigo_sgsss,$envio,
                                  $arregloAF,$arregloAT,$arregloAM,$arregloAC,$arregloAP,$arregloAH,$arregloAD,$arregloUS,$arregloAU,
                                  $ap,$ac,$ad,$us,$af,$at,$ah,$am,$au,$rangos)
    {
      $envio=str_pad($envio, 6, "0", STR_PAD_LEFT);
      $rangos = str_replace("'","",$rangos);
      $rangos = $rangos = str_replace(" ","",$rangos);
      
      if(!$this->AbrirArchivo('AF'.$envio.'.txt','w+',$envio,$rangos))
        return false;

      $this->EscribirArchivo($arregloAF);
      $this->CerrarArchivo();
      $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AF".$envio.",".$af.$this->nl;

      if(!$this->AbrirArchivo('US'.$envio.'.txt','w+',$envio,$rangos))
        return false;
      
      $this->EscribirArchivo($arregloUS);
      $this->CerrarArchivo();
      $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",US".$envio.",".$us.$this->nl;

      if(!empty($arregloAD))
      {
        if(!$this->AbrirArchivo('AD'.$envio.'.txt','w+',$envio,$rangos))
          return false;
      
        $this->EscribirArchivo($arregloAD);
        $this->CerrarArchivo();
        $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AD".$envio.",".$ad.$this->nl;
      }
      if(!empty($arregloAC))
      {
        if(!$this->AbrirArchivo('AC'.$envio.'.txt','w+',$envio,$rangos))
          return false;
        
        $this->EscribirArchivo($arregloAC);
        $this->CerrarArchivo();
        $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AC".$envio.",".$ac.$this->nl;
      }
      if(!empty($arregloAP))
      {
        if(!$this->AbrirArchivo('AP'.$envio.'.txt','w+',$envio,$rangos))
          return false;

        $this->EscribirArchivo($arregloAP);
        $this->CerrarArchivo();
        $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AP".$envio.",".$ap.$this->nl;
      }
      if(!empty($arregloAM))
      {
        if(!$this->AbrirArchivo('AM'.$envio.'.txt','w+',$envio,$rangos))
          return false;
        
        $this->EscribirArchivo($arregloAM);
        $this->CerrarArchivo();
        $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AM".$envio.",".$am.$this->nl;
      }
      if(!empty($arregloAT))
      {
        if(!$this->AbrirArchivo('AT'.$envio.'.txt','w+',$envio,$rangos))
          return false;
        
        $this->EscribirArchivo($arregloAT);
        $this->CerrarArchivo();
        $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AT".$envio.",".$at.$this->nl;
      }
      if(!empty($arregloAU))
      {
        if(!$this->AbrirArchivo('AU'.$envio.'.txt','w+',$envio,$rangos))
          return false;
        
        $this->EscribirArchivo($arregloAU);
        $this->CerrarArchivo();
        $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AU".$envio.",".$au.$this->nl;
      }
      if(!empty($arregloAH))
      {
        if(!$this->AbrirArchivo('AH'.$envio.'.txt','w+',$envio,$rangos))
          return false;
        
        $this->EscribirArchivo($arregloAH);
        $this->CerrarArchivo();
        $arregloCT .= $codigo_sgsss.",".date("d/m/Y").",AH".$envio.",".$ah.$this->nl;
      }
      if(!$this->AbrirArchivo('CT'.$envio.'.txt','w+',$envio,$rangos))
        return false;

      $this->EscribirArchivo($arregloCT);
      $this->CerrarArchivo();
      return true;
    }
    //---------------------ENVIOS-----------------------------------------------------
    function GetCuentasEnvio($envio,$rangos)
    {
			list($dbconn) = GetDBconn();
      //$dbconn->debug = true;
			$query = "SELECT b.numerodecuenta, 
												b.prefijo,
												b.factura_fiscal,
												c.ingreso,
												c.plan_id
								FROM envios_detalle a, 
											fac_facturas_cuentas b,
											cuentas c
								WHERE a.envio_id = $envio
								AND a.prefijo = b.prefijo
								AND a.factura_fiscal = b.factura_fiscal
								AND b.numerodecuenta = c.numerodecuenta ";
        if($rangos)
          $query .= "AND  c.rango IN(".$rangos.") ";
          
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB hc_diagnosticos_egreso: " . $dbconn->ErrorMsg();
					return false;
			}

			while(!$result->EOF)
			{
				$cuentas[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}

			$result->Close();
			return $cuentas;
    }
    //con envios el vector es asi=> [envio] [empresa]
    /**
    *   @var ConsultaTipoRips = character
    *       Esta variable determina el modo por el cual se va a consutar el tipo de rips
    *       con el que se crera el archivo. Se maneja por defecto '1' que es como se maneja
    *       en Tulua consultando por 'grupo_tarifario' y 'subgrupo_tarifario'. En cali, por problemas
    *       de  parametrizacion se va a menejar con 'tipo_cargo' y 'grupo_tipo_cargo' 
    */
    function Envios($tipo,$id,$arregloenvio)
    {
      $ap=$ac=$ad=$us=$af=$at=$ah=$au=$am=0;
      $arregloAF=$arregloAM=$arregloAT=$arregloAC=$arregloAP=$arregloAU=$arregloAN=$arregloAH=$arregloUS=$arregloCT=$vectorAD=$arregloCT='';
      $ConsultaTipoRips         = ModuloGetVar('app','Facturacion_Fiscal','ConsultaTipoRips');
      $datos_prestador_servicio = $this->GetDatosPrestadorServicio($arregloenvio[0][empresa]);
			//REINICIAR SECUENCIA PARA EL ID DE LA TABLA TEMPORAL
			if(!$this->InitSeq())
			{
				echo $this->mensajeDeError;
				return false;
			}
			//BORRAR tmp_de rips_cuentas_detalle
			$envio = $this->DelTabletmp($arregloenvio[0][envio]);
			if($arregloenvio[0][envio]!=$envio)
			{
				//INSERTAR EN LA TABLE TEMPORAL EL DETALLE DEL ENVIO 
				if(!$this->InsertarDetalleEnvio($arregloenvio[0][envio]))
				{
					echo $this->mensajeDeError;
					return false;
				}
			}
      //*********************************************
			list($dbconn) = GetDBconn();
			$query = "SELECT count(*)
								FROM tmp_rips_cuentas_detalle
								WHERE envio_id = ".$arregloenvio[0][envio].";";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB tmp_rips_cuentas_detalle: " . $dbconn->ErrorMsg();
					echo $this->mensajeDeError;
					return false;
			}
			if(!$result->EOF)
			{
				$cont=$result->fields[0];
			}
      //*********************************************
      $dat=$this->GetActoQuirurgico($arregloenvio[0][envio],$ConsultaTipoRips,$arregloenvio,$datos_prestador_servicio,&$arregloAP,&$vectorAD,$cont,$arregloenvio[0]['rangos']);
      $dat1=explode('\*/', $dat);
      $arregloAP=$dat1[0];//actos para ap
      $ap=$dat1[1];       //lineas de ap
      //*********************************************
      $cuentas_envio = $this->GetCuentasEnvio($arregloenvio[0][envio],$arregloenvio[0]['rangos']);
      if($cuentas_envio)
      {
        foreach($cuentas_envio AS $i => $v)
        {
          $detalleimd = $this->GetIMD($v[numerodecuenta]);
          $this->ConstruirIMD($detalleimd,$datos_prestador_servicio,&$am,&$at,&$arregloAM,&$arregloAT,&$vectorAD,$v);
        }
      }

      //ACTUALIZAR NUMEROS DE AUTORIZACI�
      if(!$this->DatosAutorizacionEnvios($arregloenvio[0][envio]))
      {echo '<br><center>NO SE ACTUALIZARON LAS AUTORIZACIONES PARA RIPS-AH.</center><br>';}
      //FIN ACTUALIZAR NUMEROS DE AUTORIZACI�

      for($lim=1; $lim<=$cont; $lim++)
      {
        $detalle_envio= $this->GetDetalleEnvio($arregloenvio[0][envio],false,$lim,$arregloenvio[0]['rangos']);
        foreach($detalle_envio AS $det_envio => $d_envio)
        {
          $datos_entidad_administradora = $this->GetDatosEntidadAdministradora($d_envio[plan_id]);
          $datos_cups                   = $this->GetDatosCups($d_envio[cargo_cups]);
          $tipo_rips                    = $this->GetTipoRips($ConsultaTipoRips,$datos_cups[tipo_cargo],$datos_cups[grupo_tipo_cargo],$datos_cups[grupo_tarifario_id],$datos_cups[subgrupo_tarifario_id],$d_envio[tarifario_id],$d_envio[cargo_tarifario],$arregloenvio[0][empresa]);
          $datos_usuario                = $this->GetDatosUsuario($d_envio[ingreso]);
          $autorizacion                 = $this->GetAutorizacionIngreso($d_envio[ingreso]);
          $sw_dato_complementario       = $this->GetSwDatosComplementarios($d_envio[cargo_cups]);
          //Se crea el Archivo AF
          if(empty($sw_AF[$d_envio[prefijo]][$d_envio[factura_fiscal]]))
          {
            $this->CallConstruyeAF(&$arregloAF,$d_envio,$datos_prestador_servicio,$datos_entidad_administradora);
            $sw_AF[$d_envio[prefijo]][$d_envio[factura_fiscal]] = true;
            $af++;
          }
              
          //Se crea el Archivo US
          if( empty( $sw_US[$datos_usuario[tipo_id_paciente]][$datos_usuario[paciente_id]] ) )
          {
            $this->CallConstruyeUS(&$arregloUS,$d_envio,$datos_entidad_administradora,$datos_usuario);
            $sw_US[$datos_usuario[tipo_id_paciente]][$datos_usuario[paciente_id]] = true;
            $us++;
          }            
  
          if(!empty($d_envio[consecutivo1]))
          {
            if(!empty($d_envio[codigo_medicamento]))
            {
              //ingresa medicamentos
              $this -> SetAcumuladorAD(&$vectorAD,$d_envio[prefijo]."".$d_envio[factura_fiscal],$datos_prestador_servicio[codigo_sgsss],'12',$d_envio[cantidad],$d_envio[valor_cubierto]);
              //$am++;
            }
            elseif(empty($d_envio[codigo_medicamento]))
            {
              //ingresa insumos
              $this -> SetAcumuladorAD(&$vectorAD,$d_envio[prefijo]."".$d_envio[factura_fiscal],$datos_prestador_servicio[codigo_sgsss],'09',$d_envio[cantidad],$d_envio[valor_cubierto]);
              //$at++;
            }
          }
          
          switch ($tipo_rips)
          {
            case 'AT':
            {
              if(empty($d_envio[t_equipo]) AND empty($d_envio[programacion_id]) AND empty($d_envio[cargo]))
              {
                $this->CallConstruyeAT(&$arregloAT,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD,$datos_entidad_administradora);
                $at++;
                break;
              }
              else
              {
                break;
              }
            }
            case 'AC':
            {
              $cnt = $this->CallConstruyeAC(&$arregloAC,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_entidad_administradora,$datos_cups,&$vectorAD);
              $ac += $cnt;
              break;
            }
            case 'AP':
            { 
              if($d_envio[consecutivo]==NULL AND $d_envio[cargo]==NULL)
              { 
                $this->CallConstruyeAP(&$arregloAP,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$tipo_rips,$datos_cups,&$vectorAD,$datos_entidad_administradora);
                $ap++;
                break;
              }
              else
              {
                break;
              }
            }
            case 'OP':
            {
              $this->CallConstruyeAP(&$arregloAP,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion,$tipo_rips,$datos_cups,&$vectorAD,$datos_entidad_administradora);
              $ap++;
              break;
            }
            case 'AU':
            {
              $this->CallConstruyeAU(&$arregloAU,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion);
              $au++;
              break;
            }
            case 'AN':
            {
              break;
            }
            case 'AH':
            {
              $this->CallConstruyeAH(&$arregloAH,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion);
              $ah++;
              break;
            }
            default:
            {
              break;
            }
          }//fin switch
          //si se inserta informacion en los archivos Ac,AP,AT, puede ser necesario 
          //el ingreso de informacion necesaria dentro de AU,AN,AH segun se parametrize se la tabla rips_datos_complementarios
          if($sw_dato_complementario)
          {
            if($sw_dato_complementario[sw_au] == '1')
            {
              $this->CallConstruyeAU(&$arregloAU,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion);
              $au++;
            }
            if($sw_dato_complementario[sw_an] == '1')
            {
              // construir archivo AN
            }
            if($sw_dato_complementario[sw_ah] == '1')
            { 
              $this->CallConstruyeAH(&$arregloAH,$d_envio,$datos_prestador_servicio,$datos_usuario,$autorizacion);
              $ah++;
            }
          }
        }//fin foreach
      }
				
      $this -> CallConstruyeAD(&$arregloAD,&$vectorAD,&$ad);

			if($this -> CallConstuyeArchivos( $datos_prestador_servicio[codigo_sgsss],$arregloenvio[0][envio],
																				$arregloAF,$arregloAT,$arregloAM,$arregloAC,$arregloAP,$arregloAH,$arregloAD,$arregloUS,$arregloAU,
																				$ap,$ac,$ad,$us,$af,$at,$ah,$am,$au,$arregloenvio[0]['rangos']) )
          return true;
				
				return false;
    }

//**************************************************************************************

		function ConstruirIMD($det,$datos_prestador_servicio,&$am,&$at,&$arregloAM,&$arregloAT,&$vectorAD,$v_cuentas)
		{
				foreach($det as $i=>$v)
				{
					if($v[precio] > 0 
						AND $v[valor_cargo] > 0 
						AND ($v[valor_paciente] > 0 
									OR $v[valor_cliente] > 0)
						AND !empty($v[codigo_medicamento]))
					{
						$datos_usuario                              = $this->GetDatosUsuario($v_cuentas[ingreso]);
						$datos_cups                                     = $this->GetDatosCups($v[cargo_cups]);
						//$autorizacion                               = $this->GetAutorizacion($v,$v[autorizacion_ext]);
						$autorizacion                               = $this->GetAutorizacionIngreso($v_cuentas[ingreso]);
						//ingresa medicamentos
						$this->CallConstruyeAM(&$arregloAM,$v,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD,$v_cuentas);
						$am++;
					}
					else
					if($v[precio] > 0 
						AND $v[valor_cargo] > 0 
						AND ($v[valor_paciente] > 0 
									OR $v[valor_cliente] > 0)
						AND empty($v[codigo_medicamento]))
					{
						$datos_entidad_administradora = $this->GetDatosEntidadAdministradora($v_cuentas[plan_id]);
						$datos_usuario                              = $this->GetDatosUsuario($v_cuentas[ingreso]);
						$datos_cups                                     = $this->GetDatosCups($v[cargo_cups]);
//						$autorizacion                               = $this->GetAutorizacion($v,$v[autorizacion_ext]);
						$autorizacion                               = $this->GetAutorizacionIngreso($v_cuentas[ingreso]);
						//ingresa insumos
						$this->CallConstruyeAT(&$arregloAT,$v,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD,$datos_entidad_administradora,$v_cuentas);
						$at++;
					}
				}
				return true;
		}


//     function ConstruirIMD($det,$datos_prestador_servicio,&$am,&$at,&$arregloAM,&$arregloAT,&$vectorAD)
//     {
//         $j=$k=$l=$j1=$k1=$l1=$cantidadMD=$precioMD=$cantidadDMD=$precioDMD=$cantidadI=$precioI=$cantidadDI=$precioDI=0;
//         //for($i=0; $i<=sizeof($det);$i++)
//         foreach($det AS $m => $v)
//         {
//             //if(!empty($det[$i][consecutivo1]))
//             if(!empty($v[consecutivo1]))
//             {
//                 //MEDICAMENTOS
//                 //if(!empty($det[$i][codigo_medicamento]))
//                 if(!empty($v[codigo_medicamento]))
//                 {
//                     $j=$m;
//                     //while($det[$i][prefijo]==$det[$j][prefijo] 
//                     //            AND $det[$i][factura_fiscal]==$det[$j][factura_fiscal]
//                     //            AND $det[$j][cargo_tarifario]=='IMD'
//                     //            AND $det[$i][codigo_medicamento]==$det[$j][codigo_medicamento]
//                     //            )
//                     while($v[prefijo]==$det[$j][prefijo] 
//                                 AND $v[factura_fiscal]==$det[$j][factura_fiscal]
//                                 AND $det[$j][cargo_tarifario]=='IMD'
//                                 AND $v[codigo_medicamento]==$det[$j][codigo_medicamento]
//                                 )
//                     {
//                         if (!empty($det[$j][codigo_medicamento]))
//                             //AND $v[transaccion] <> $det[$j+1][transaccion])
//                         {
//                             $cantidadMD+=$det[$j][cantidad];
//                             $precioMD+=$det[$j][valor_cubierto];
//                         }
//                         $det[$j][cantidad]=$cantidadMD;
//                         $det[$j][valor_cubierto]=$precioMD;
//                         $j++;
//                     }
//                     if($cantidadMD>0)
//                     {
//                     $vectMD[$k]=$det[$j-1];
//                     $k++;
//                     $cantidadMD=$precioMD=0;
//                     }
//                     //while($det[$i][prefijo]==$det[$j][prefijo] 
//                     //            AND $det[$i][factura_fiscal]==$det[$j][factura_fiscal]
//                     //            AND $det[$j][cargo_tarifario]=='DIMD'
//                     //            AND $det[$i][codigo_medicamento]==$det[$j][codigo_medicamento]
//                     //            )
//                     while($v[prefijo]==$det[$j][prefijo] 
//                                AND $v[factura_fiscal]==$det[$j][factura_fiscal]
//                                AND $det[$j][cargo_tarifario]=='DIMD'
//                                AND $v[codigo_medicamento]==$det[$j][codigo_medicamento]
//                                )
//                     {
//                         if (!empty($det[$j][codigo_medicamento]))
//                         {
//                             $cantidadDMD+=$det[$j][cantidad];
//                             if($det[$j][valor_cubierto] < 0)
//                              $det[$j][valor_cubierto]=$det[$j][valor_cubierto];
//                             else
//                             if($det[$j][valor_cubierto] > 0)
//                              $det[$j][valor_cubierto]=$det[$j][valor_cubierto]*(-1);
//                              
//                             $precioDMD+=$det[$j][valor_cubierto];
//                         }
//                         $det[$j][cantidad]=$cantidadDMD;
//                         $det[$j][valor_cubierto]=$precioDMD;
//                         $j++;
//                     }
//                     if($cantidadDMD>0)
//                     {
//                     $vectDMD[$l]=$v;//$det[$j-1];
//                     $l++;
//                     $cantidadDMD=$precioDMD=0;
//                     }
//                     //ingresa medicamentos
//                     //$this->CallConstruyeAM(&$arregloAM,$det[$i],$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD);
//                     //$am++;
//                     //$i=$j-1;
//                     $m=$j-1;
//                 }
//                 //INSUMOS
//                 elseif(empty($v[codigo_medicamento]))
//                 {
//                         $j1=$m;
//                         while($v[prefijo]==$det[$j1][prefijo] 
//                                     AND $v[factura_fiscal]==$det[$j1][factura_fiscal]
//                                     AND $det[$j1][cargo_tarifario]=='IMD'
//                                     AND $v[codigo_producto]==$det[$j1][codigo_producto])
//                         {    
//                                 $cantidadI+=$det[$j1][cantidad];
//                                 $precioI+=$det[$j1][valor_cubierto];
//                                 $j1++;
//                         }
//                         if($cantidadI>0)
//                         {
//                             $det[$j1-1][cantidad]=$cantidadI;
//                             $det[$j1-1][valor_cubierto]=$precioI;
//                             $vectI[$k1]=$det[$j1-1];
//                             $k1++;
//                             $cantidadI=$precioI=0;
//                         }
//                         while($v[prefijo]==$det[$j1][prefijo] 
//                                     AND $v[factura_fiscal]==$det[$j1][factura_fiscal]
//                                     AND $det[$j1][cargo_tarifario]=='DIMD'
//                                     AND $v[codigo_producto]==$det[$j1][codigo_producto]
//                                     )
//                         {
//                             $cantidadDI+=$det[$j1][cantidad];
//                             if($det[$j1][valor_cubierto] < 0)
//                              $det[$j1][valor_cubierto] = $det[$j1][valor_cubierto];
//                             else
//                             if($det[$j1][valor_cubierto] > 0)
//                              $det[$j1][valor_cubierto] = $det[$j1][valor_cubierto]*(-1);
//                             
//                             $precioDI+=$det[$j1][valor_cubierto];
//                             $j1++;
//                         }
//                         if($cantidadDI>0)
//                         {
//                             $det[$j1-1][cantidad]=$cantidadDI;
//                             $det[$j1-1][valor_cubierto]=$precioDI;
//                             $vectDI[$l1]=$det[$j1-1];
//                             $l1++;
//                             $cantidadDI=$precioDI=0;
//                         }
//                         //ingresa insumos
//                         //$this->CallConstruyeAT(&$arregloAT,$det[$i],$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD);
//                         //$at++;
//                     $i=$j1-1;
//                 }
//             }
//         }
//  
//         foreach($vectMD as $i=>$v)
//         {
//             foreach($vectDMD as $i1=>$v1)
//             {
//                 if($v[prefijo]==$v1[prefijo]
//                     AND $v[factura_fiscal]==$v1[factura_fiscal]
//                     AND $v[codigo_producto]==$v1[codigo_producto]
//                     AND $v[codigo_medicamento]==$v1[codigo_medicamento])
//                     {
//                         $v[cantidad]-=$v1[cantidad];
//                         $v[valor_cubierto]+=$v1[valor_cubierto];
//                         if($v[cantidad]==0 AND $v[valor_cubierto]==0)
//                         {
//                             UNSET($vectMD[$i]);
//                         }
//                         else
//                         {
//                             $vectMD[$i][cantidad]=$v[cantidad];
//                             $vectMD[$i][valor_cubierto]=$v[valor_cubierto];
//                         }
//                     }
//             }
//         }
// 
//         foreach($vectMD as $i=>$v)
//         {
//             $datos_usuario                              = $this->GetDatosUsuario($v[ingreso]);
//             $datos_cups                                     = $this->GetDatosCups($v[cargo_cups]);
//             $autorizacion                               = $this->GetAutorizacion($v,$v[autorizacion_ext]);
//              //ingresa medicamentos
//              $this->CallConstruyeAM(&$arregloAM,$v,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD);
//              $am++;
//         }
//         //echo 'AM->'.$arregloAM.'<br>';
//         foreach($vectI as $i=>$v)
//         {
//             foreach($vectDI as $i1=>$v1)
//             {
//                 if($v[prefijo]==$v1[prefijo]
//                     AND $v[factura_fiscal]==$v1[factura_fiscal]
//                     AND $v[codigo_producto]==$v1[codigo_producto])
//                     {
//                         $v[cantidad]-=$v1[cantidad];
//                         $v[valor_cubierto]+=$v1[valor_cubierto];
//                         if($v[cantidad]==0 AND $v[valor_cubierto]==0)
//                         {
//                             UNSET($vectI[$i]);
//                         }
//                         else
//                         {
//                             $vectI[$i][cantidad]=$v[cantidad];
//                             $vectI[$i][valor_cubierto]=$v[valor_cubierto];
//                         }
//                     }
//             }
//         }
// //print_r($vectI);exit;
//         foreach($vectI as $i=>$v)
//         {
// 						$datos_entidad_administradora = $this->GetDatosEntidadAdministradora($v[plan_id]);
// 						$datos_usuario                              = $this->GetDatosUsuario($v[ingreso]);
// 						$datos_cups                                     = $this->GetDatosCups($v[cargo_cups]);
// 						$autorizacion                               = $this->GetAutorizacion($v,$v[autorizacion_ext]);
// 						//ingresa insumos
// 						$this->CallConstruyeAT(&$arregloAT,$v,$datos_prestador_servicio,$datos_usuario,$autorizacion,$datos_cups,&$vectorAD,$datos_entidad_administradora);
// 						$at++;
//         }
//         //echo 'AT->'.$arregloAT.'<br>';exit;
//         return true;
//     }

    function XXDiagnosticoIngreso($ingreso)
    {
            list($dbconn) = GetDBconn();
            $query = "  SELECT c.tipo_diagnostico_id
                                    FROM    hc_diagnosticos_ingreso as c, hc_evoluciones as d
                                    WHERE d.ingreso=$ingreso and c.evolucion_id=d.evolucion_id
                                    and c.sw_principal='1'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if(!$result->EOF)
            {
                    $var=$result->fields[0];
            }

            $result->Close();
            return $var;
    }


    /**
    *
    */
    function XXBuscarNumeroOrden($transaccion)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT a.numero_orden_id
                                FROM os_maestro_cargos as a WHERE a.transaccion=$transaccion";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if(!$result->EOF)
            {  $var=$result->fields[0];  }
            $result->Close();
            return $var;
    }

    function XXDatosHonorarios($transaccion)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT b.valor
                                FROM cuentas_detalle_honorarios as b
                                WHERE b.transaccion=$transaccion";
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










    /**
    *
    */
    function XXBuscarProcedimientos($transaccion)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT c.autorizacion_int, c.autorizacion_ext, b.numero_orden_id
                                FROM os_maestro_cargos as a, os_maestro as b, os_ordenes_servicios as c
                                WHERE a.transaccion=$transaccion
                                and a.numero_orden_id=b.numero_orden_id
                                and b.orden_servicio_id=c.orden_servicio_id";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if(!$result->EOF)
            {  $var=$result->GetRowAssoc($ToUpper = false);  }
            $result->Close();
            return $var;
    }



    /**
    *
    */
    function XXDatosRipsEnvios($envio)
    {
    //, e.codigo_sgsss
    //left join terceros_sgsss as e on(c.tipo_id_tercero=e.tipo_id_tercero and c.tercero_id=e.tercero_id)
            list($dbconn) = GetDBconn();
            echo "<pre>".$query = "
                                SELECT  a.envio_id, 
                                                a.fecha_inicial, 
                                                a.fecha_final, 
                                                b.prefijo, 
                                                b.factura_fiscal,
                                                c.fecha_registro, 
                                                x.codigo_sgsss,
                                                --c.total_factura, 
                                                --c.valor_cuota_paciente, 
                                                --c.valor_cuota_moderadora, 
                                                --c.descuento,
                                                CASE g.sw_tipo_plan WHEN 3 THEN 0 ELSE c.total_factura END AS total_factura,
                                                CASE g.sw_tipo_plan WHEN 3 THEN 0 ELSE c.valor_cuota_paciente END AS valor_cuota_paciente,
                                                CASE g.sw_tipo_plan WHEN 3 THEN 0 ELSE c.valor_cuota_moderadora END AS valor_cuota_moderadora,
                                                CASE g.sw_tipo_plan WHEN 3 THEN 0 ELSE c.descuento END AS descuento,
                                                g.plan_descripcion,
                                                CASE C.TIPO_ID_TERCERO WHEN 'NIT' THEN 'NI' ELSE C.TIPO_ID_TERCERO END AS tipo_id_tercero,
                                                c.tercero_id, 
                                                g.num_contrato, 
                                                k.poliza, 
                                                i.ingreso, 
                                                i.tipo_afiliado_id, 
                                                i.numerodecuenta, 
                                                i.plan_id
                                FROM    envios as a, 
                                            envios_detalle as b, 
                                            fac_facturas as c,
                                            planes as g 
                                                LEFT JOIN terceros_sgsss as x
                                                    ON( x.tipo_id_tercero=g.tipo_tercero_id 
                                                            AND x.tercero_id=g.tercero_id),
                                            fac_facturas_cuentas as h,
                                            cuentas as i 
                                                LEFT JOIN ingresos_soat as j 
                                                    ON (i.ingreso=j.ingreso)
                                                LEFT JOIN soat_eventos as k 
                                                    ON(j.evento=k.evento)
                                WHERE a.envio_id=$envio 
                                            AND a.sw_estado in(1,0) 
                                            AND a.envio_id=b.envio_id
                                            AND b.prefijo=c.prefijo 
                                            AND b.factura_fiscal=c.factura_fiscal
                                            AND c.plan_id=g.plan_id 
                                            AND h.numerodecuenta=i.numerodecuenta
                                            AND h.prefijo=c.prefijo 
                                            AND h.factura_fiscal=c.factura_fiscal
                                ORDER BY c.prefijo,c.factura_fiscal
                                
                                ";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }

            $result->Close();
            return $var;
    }




//-------------------------------FACTURAS-------------------------------------
//EL vector es asi array('prefijo'=>$a[0],'factura'=>$a[1],'plan'=>$a[2],'empresa'=>$a[3]);
//quite
    /*function Facturas($arregloenvio)
    {
            $dat=$this->DatosPrestadorServicio($arreglofactura[0][empresa]);
            for($i=0; $i<sizeof($arreglofactura); $i++)
            {
                    $this->DatosRipsFacturas($arreglofactura[$i][prefijo],$arreglofactura[$i][factura]);
            }
    }

    function DatosRipsFacturas($prefijo,$factura)
    {
            list($dbconn) = GetDBconn();
            $query = "";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }

            $result->Close();
            return $var;
    }*/

//-----------------------------------------------------------------------------------



    function XXNombreTercero($tipo,$id)
    {
            list($dbconn) = GetDBconn();
            if($tipo == 'NI') $tipo = 'NIT';
            $query = "  select  a.nombre_tercero, 
                                                            b.codigo_sgsss
                                            from        terceros as a 
                                                            left join terceros_sgsss as b
                                                                on( a.tipo_id_tercero=b.tipo_id_tercero 
                                                                        and a.tercero_id=b.tercero_id)
                                            where   a.tipo_id_tercero='$tipo' and a.tercero_id='$id'";
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

    function XXDatosUsuario($tipo,$id)
    {
            list($dbconn) = GetDBconn();
            $query = "select b.* from  pacientes as b
                                where b.tipo_id_paciente='$tipo' and b.paciente_id='$id'";
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




//-------------------------------DATOS CONSULTA---------------------------------

    function XXDatosConsulta($cuenta)
    {
            $des=ModuloGetVar('app','Facturacion_Fiscal','CargoDescuento');
            $apr=ModuloGetVar('app','Facturacion_Fiscal','CargoAprovechamiento');
            list($dbconn) = GetDBconn();
            $query = "
            SELECT 
                a.cantidad, 
                case when b.concepto_rips IS NULL then e.concepto_rips ELSE b.concepto_rips end as concepto_rips,
                d.sw_tipo_plan,
                --case d.sw_tipo_plan when 3 then 0 else a.valor_cargo end as valor_cargo,
                case d.sw_tipo_plan when 3 then 0 else a.valor_cubierto end as valor_cargo,
                case d.sw_tipo_plan when 3 then 0 else a.precio end as precio,
                case d.sw_tipo_plan when 3 then 0 else c.valor_cuota_moderadora end as valor_cuota_moderadora,
                case d.sw_tipo_plan when 3 then 0 else c.valor_cuota_paciente end as valor_cuota_paciente,
                case d.sw_tipo_plan when 3 then 0 else c.valor_total_empresa end as valor_total_empresa,
                a.transaccion,
                a.cargo_cups,
                a.fecha_cargo,
                a.tarifario_id,
                a.cargo,
                c.empresa_id,
                
                a.consecutivo,
                a.autorizacion_ext,
                a.autorizacion_int,
                
                e.descripcion,
                a.servicio_cargo,
                
                b.tipo_cargo,
                b.grupo_tipo_cargo,
                b.grupo_tarifario_id,
                b.subgrupo_tarifario_id,
                
                d.sw_rips_con_cargo_cups
                
            FROM 
                cuentas_detalle as a
                    LEFT JOIN cups as b 
                        ON(a.cargo_cups = b.cargo),
                cuentas as c, 
                planes as d, 
                tarifarios_detalle as e
            WHERE 
                a.numerodecuenta=$cuenta
                AND a.facturado='1'$usu
                and a.cargo not in('$des','$apr')
                and a.numerodecuenta=c.numerodecuenta
                and c.plan_id=d.plan_id
                and a.cargo=e.cargo
                and a.tarifario_id=e.tarifario_id";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }

            $result->Close();
            return $var;
    }


    function XXBuscaCita($cups)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT count(cargo_cita)
                                FROM cargos_citas WHERE cargo_cita='$cups'";
            /*$query = "SELECT count(cargo)
                                FROM cups
                                WHERE cargo='$cups' and grupo_tipo_cargo='CM'";*/
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $var=$result->fields[0];
            
            $result->Close();
            return $var;
    }



    function XXDatosInsumos($consecutivo)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT e.descripcion_abreviada
                                FROM bodegas_documentos_d as c, inventarios_productos as e
                                WHERE c.consecutivo=$consecutivo and c.codigo_producto=e.codigo_producto
                                ";
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
/*
    function GetActoQuirurgico($detalle_envio,$ConsultaTipoRips,$arregloenvio,$datos_prestador_servicio,&$arregloAP,&$vectorAD)
    {
        $detalle=$this->GetDetalleEnvio($detalle_envio,'aq');
        $precio=$k=$lineas=0;
        $tmpcargo=$tmpcodigo_agrupamiento='';
        for($i=0; $i<sizeof($detalle); $i++)
        {
			//si es un acto quirurjico
			while($detalle[$i][consecutivo]==NULL AND $detalle[$i][codigo_agrupamiento_id]!=NULL AND !empty($detalle[$i][cargo]))
			{
				$k=$i;
				while($detalle[$i][codigo_agrupamiento_id]==$detalle[$k][codigo_agrupamiento_id])
				{
					$l=0;
					$l=$k;
//SI EXISTE VALOR POR EQUIPOS
$varlorequipo = $this->TraerValorEquipos($detalle[$i][transaccion]);
if($varlorequipo > 0)
{$precio = $varlorequipo;}
//FIN SI EXISTE VALOR POR EQUIPOS
				   while($detalle[$k][cargo] == $detalle[$l][cargo])
					{
						if(!empty($detalle[$l][cargo]))
						{
							$precio+=$detalle[$l][valor_cubierto];     
						}
						$l++;
					}
					$k=$l-1;
					$ambito='';
					//arciho AP
					$datos_entidad_administradora = $this->GetDatosEntidadAdministradora($detalle[$l-1][plan_id]);
					$datos_cups                                     = $this->GetDatosCups($detalle[$l-1][cargo_cups]);
					$tipo_rips                                      = $this->GetTipoRips($ConsultaTipoRips,$datos_cups[tipo_cargo],$datos_cups[grupo_tipo_cargo],$datos_cups[grupo_tarifario_id],$datos_cups[subgrupo_tarifario_id],$detalle[$l-1][tarifario_id],$detalle[$l-1][cargo_cups],$arregloenvio[0][empresa]);
					$datos_usuario                              = $this->GetDatosUsuario($detalle[$l-1][ingreso]);
					$autorizacion                               = $this->GetAutorizacion($detalle_envio,$detalle[$l-1][autorizacion_ext]);
					$sw_dato_complementario             = $this->GetSwDatosComplementarios($detalle[$l-1][cargo_cups]);

					if($datos_entidad_administradora[sw_rips_con_cargo_cups] == '1')
						$cod_cargo = $detalle[$l-1][cargo];
					else
						$cod_cargo = $detalle[$l-1][cargo_tarifario];
					$diag_odontologicoOP = '';
					$atendido_por ='5';// este coresponde a "otro", revizar por que no esta parametrizado
					if($tipo_rips == 'OP')
					{
						$diag_odontologico = GetDiagnosticosOdontologiaAP($detalle[$l-1][transaccion]);
						$atendido_por ='5';
					}
					else
					{
						if(empty($detalle[$l-1][diagnostico_ppal]))
						{
							$diag_odontologicoOP = $detalle[$i][tipo_diagnostico_id];
						}
						else
						{
							$diag_odontologicoOP = $detalle[$l-1][diagnostico_ppal];
						}
						$diagnostico_relacionado = $detalle[$l-1][diagnostico_relacionado];
						$complicacion = $detalle[$l-1][complicacion];
						$forma_realizacion = $detalle[$l-1][forma_realizacion];
						//$atendido_por = $this -> GetTipoProfesional($detalle[$l-1][transaccion]);
						$atendido_por = $this -> GetTipoProfesionalRips($detalle[$l-1][usuario_nota]);
						if(empty($complicacion))
						{
							$complicacion = $diag_odontologicoOP;
						}
					}

					if(empty($detalle[$l-1][cargo_manual]))
					{
						$ambito=$this->GetAmbito($detalle[$l-1][servicio_cargo]);
						$finalidad_proc = "1";
						$fecha_proc = $this->FechaStamp($detalle[$l-1][fecha_cargo]);
					}
					else
					{//cargos ingresados manualmente
						$fecha_proc         = $this->FechaStamp($detalle[$l-1][ap_fechaprocedimiento]);
						$autorizacion   = $detalle[$l-1][autorizacion];
						$ambito                 = $detalle[$l-1][ap_ambitoprocedimiento];
						$finalidad_proc = $detalle[$l-1][ap_finalidadprocedimiento];
					}
					if($precio>0)
					{
						$arregloAP.=$detalle[$l-1][prefijo]."".$detalle[$l-1][factura_fiscal].",";//R
						$arregloAP.=$datos_prestador_servicio[codigo_sgsss].",";
						$arregloAP.=$datos_usuario[tipo_id_paciente].",";//R
						$arregloAP.=$datos_usuario[paciente_id].",";//R
						$arregloAP.=$fecha_proc.",";//R fecha procedimiento
						$arregloAP.=$autorizacion.",";//numero autorizacion
						$arregloAP.=$cod_cargo.",";//R
						$arregloAP.=$ambito.",";//R ambito de realizacion del procedimiento
						$arregloAP.=$finalidad_proc.",";//R finalidad del procedimeinto
						$arregloAP.=$atendido_por[codigo_rips].",";//personal que atiende
						$arregloAP.=$diag_odontologicoOP.",";//diag ppal
						$arregloAP.=$diagnostico_relacionado.",";//diag relacionado
						$arregloAP.=$complicacion.",";//complicacion
						$arregloAP.=$forma_realizacion.",";//forma de realizacion del acto quirurgico
						$arregloAP.=$precio;
						$arregloAP.=$this->nl;

						$datos_cups = $this->GetDatosCups($detalle[$k][cargo_cups]);
						//$this -> SetAcumuladorAD(&$vectorAD,$detalle[$k][prefijo]."".$detalle[$k][factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$detalle[$k][cantidad],$detalle[$k][valor_cubierto]);
						$this -> SetAcumuladorAD(&$vectorAD,$detalle[$k][prefijo]."".$detalle[$k][factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$detalle[$k][cantidad],$precio);
						$precio=0;
						$lineas++;
					}
					$k++;
				}
				$i=$k;
			//$datos_cups = $this->GetDatosCups($detalle[$i][cargo_cups]);
			//$this -> SetAcumuladorAD(&$vectorAD,$detalle[$i][prefijo]."".$detalle[$i][factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$detalle[$i][cantidad],$detalle[$i][valor_cubierto]);
			}
			//$datos_cups = $this->GetDatosCups($detalle[$i][cargo_cups]);
			//$this -> SetAcumuladorAD(&$vectorAD,$detalle[$i][prefijo]."".$detalle[$i][factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$detalle[$i][cantidad],$detalle[$i][valor_cubierto]);
        }echo $arregloAP; exit;
            return $arregloAP.'\*'.($lineas);//OJO '\*\/'
    }//fin construye ACTO QUIRURJICO -- AP() 
*/

    function GetActoQuirurgico($detalle_envio,$ConsultaTipoRips,$arregloenvio,$datos_prestador_servicio,&$arregloAP,&$vectorAD,$cont,$rangos)
    {
        //$detalle=$this->GetDetalleEnvio($detalle_envio,'aq');
        for($lim=1; $lim<=$cont; $lim++)
				{
					$detalle=$this->GetDetalleEnvio($detalle_envio,true,$lim,$rangos);

					$precio=$k=$lineas=0;
					$tmpcargo=$tmpcodigo_agrupamiento='';
					foreach($detalle AS $i => $v)
					{
						if(empty($v[t_equipo]) and empty($v[cargo]))
						{
							UNSET($detalle[$i]);
						}
					}
					foreach($detalle AS $k=>$v)
					{
							$detalle1[]=$v;
					}
				}
					//CONSTRUIR ACTO QUIRURGICO + EQUIPOS DEL ACTO QUIRURGICO
					$tmp=$this->ConstruirAQ($detalle1,$ConsultaTipoRips,$arregloenvio,$detalle_envio,&$vectorAD,&$vectorAP,$datos_prestador_servicio);
					$t=explode('\*/',$tmp);
					$arregloAP = $t[0];
					$lineas = $t[1];
					return $arregloAP.'\*/'.($lineas);
    }//fin construye ACTO QUIRURJICO -- AP() 
   
    function ConstruirAQ($detalle,$ConsultaTipoRips,$arregloenvio,$detalle_envio,&$vectorAD,&$vectorAP,$datos_prestador_servicio)
		{
        for($i=0; $i<sizeof($detalle); $i++)
        {
			//si es un acto quirurjico
					while($detalle[$i][consecutivo]==NULL AND $detalle[$i][codigo_agrupamiento_id]!=NULL)
					{
						$k=$i;
						while($detalle[$i][codigo_agrupamiento_id]==$detalle[$k][codigo_agrupamiento_id]
								AND $detalle[$i][programacion_id]==$detalle[$k][programacion_id]
								AND $detalle[$i][cargo]==$detalle[$k][cargo])
						//while($detalle[$i][codigo_agrupamiento_id]==$detalle[$k][codigo_agrupamiento_id])
						{
							$precio+=$detalle[$k][valor_cubierto];
							$prog = $detalle[$k][programacion_id];
							$k++;
						}

							$ambito='';
							//arciho AP
							$datos_entidad_administradora = $this->GetDatosEntidadAdministradora($detalle[$i][plan_id]);
							$datos_cups                   = $this->GetDatosCups($detalle[$i][cargo_cups]);
							$tipo_rips                    = $this->GetTipoRips($ConsultaTipoRips,$datos_cups[tipo_cargo],$datos_cups[grupo_tipo_cargo],$datos_cups[grupo_tarifario_id],$datos_cups[subgrupo_tarifario_id],$detalle[$i][tarifario_id],$detalle[$i][cargo_cups],$arregloenvio[0][empresa]);
							$datos_usuario                = $this->GetDatosUsuario($detalle[$i][ingreso]);
							//$autorizacion                 = $this->GetAutorizacion($detalle_envio,$detalle[$i][autorizacion_ext]);
$autorizacion                 = $this->GetAutorizacionIngreso($detalle[$i][ingreso]);
							$sw_dato_complementario       = $this->GetSwDatosComplementarios($detalle[$i][cargo_cups]);
		
							if($datos_entidad_administradora[sw_rips_con_cargo_cups] == '1')
							{
								$cod_cargo = $detalle[$i][cargo];
							}
							else
							{
								//$cod_cargo = $detalle[$i][cargo_tarifario];
								$datos = $this->GetQxTarifarioCargo($detalle[$i][transaccion]);
								$cod_cargo = $datos[cargo];//CARGO TARIFARIO
							}
							$diag_odontologicoOP = '';
							$atendido_por ='5';// este coresponde a "otro", revizar por que no esta parametrizado
							if($tipo_rips == 'OP')
							{
								$diag_odontologico = GetDiagnosticosOdontologiaAP($detalle[$i][transaccion]);
								$atendido_por ='5';
							}
							else
							{
								if(empty($detalle[$i][diagnostico_ppal]))
								{
									$diag_odontologicoOP = $detalle[$i][tipo_diagnostico_id];
								}
								else
								{
									$diag_odontologicoOP = $detalle[$i][diagnostico_ppal];
								}
								//$diagnostico_relacionado = $detalle[$l-1][diagnostico_relacionado];
								//$complicacion = $detalle[$l-1][complicacion];
								//$forma_realizacion = $detalle[$l-1][forma_realizacion];
								//$atendido_por = $this -> GetTipoProfesionalRips($detalle[$l-1][usuario_nota]);
                $ActoQuirurgico = $this -> GetDatosActoQuirurgicoExcepcion($detalle[$i][programacion_id]);
                $diag_odontologicoOP=$ActoQuirurgico[diagnostico_ppal];
                $diagnostico_relacionado=$ActoQuirurgico[diagnostico_relacionado];
                $complicacion=$ActoQuirurgico[complicacion];
                $forma_realizacion=$ActoQuirurgico[forma_realizacion];
                $atendido_por = $this -> GetTipoProfesional($detalle[$i][transaccion]);

								//VALORES POR DEFECTO PARA LOS TIPOS DE DIAGNOSTICOS EN UN PROCEDIEMIENTO
								if(!empty($diag_odontologicoOP) AND empty($diagnostico_relacionado))
								{
									$diagnostico_relacionado = $diag_odontologicoOP;
								}
								if(empty($diag_odontologicoOP) 
									AND empty($diagnostico_relacionado))
								{
								$diag_odontologicoOP=$diagnostico_relacionado = "T888";
								$forma_realizacion = '1';
								}
								//FIN VALORES POR DEFECTO PARA LOS TIPOS DE DIAGNOSTICOS EN UN PROCEDIEMIENTO

								if(empty($complicacion))
								{
									$complicacion = $diag_odontologicoOP;
								}
							}
		
							if(empty($detalle[$i][cargo_manual]))
							{
								$ambito=$this->GetAmbito($detalle[$i][servicio_cargo]);
								$finalidad_proc = "1";
								$fecha_proc = $this->FechaStamp($detalle[$i][fecha_cargo]);
							}
							else
							{//cargos ingresados manualmente
								$fecha_proc     = $this->FechaStamp($detalle[$i][ap_fechaprocedimiento]);
								$autorizacion   = $detalle[$i][autorizacion];
								$ambito         = $detalle[$i][ap_ambitoprocedimiento];
								$finalidad_proc = $detalle[$i][ap_finalidadprocedimiento];
							}

							if($detalle[$i][prefijo] AND $precio>0)
							{
								$arregloAP.=$detalle[$i][prefijo]."".$detalle[$i][factura_fiscal].",";//R
								$arregloAP.=$datos_prestador_servicio[codigo_sgsss].",";
								$arregloAP.=$datos_usuario[tipo_id_paciente].",";//R
								$arregloAP.=$datos_usuario[paciente_id].",";//R
								$arregloAP.=$fecha_proc.",";//R fecha procedimiento
								$arregloAP.=$autorizacion.",";//numero autorizacion
								$arregloAP.=$cod_cargo.",";//R
								$arregloAP.=$ambito.",";//R ambito de realizacion del procedimiento
								$arregloAP.=$finalidad_proc.",";//R finalidad del procedimeinto
								$arregloAP.=$atendido_por[codigo_rips].",";//personal que atiende
								$arregloAP.=$diag_odontologicoOP.",";//diag ppal
								$arregloAP.=$diagnostico_relacionado.",";//diag relacionado
								$arregloAP.=$complicacion.",";//complicacion
								$arregloAP.=$forma_realizacion.",";//forma de realizacion del acto quirurgico
								$arregloAP.=$precio;
								$arregloAP.=$this->nl;
		
								$datos_cups = $this->GetDatosCups($detalle[$i][cargo_cups]);
								$this -> SetAcumuladorAD(&$vectorAD,$detalle[$i][prefijo]."".$detalle[$i][factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$detalle[$i][cantidad],$precio);
								$precio=0;
								$lineas++;
							}

						$i=$k;
					}
        }
		 return $arregloAP.'\*/'.($lineas);
		}
//find ./SIIS/ -name Root -print -exec cp ./Root {} \;
//     function ConstruirAQ($detalle,$ConsultaTipoRips,$arregloenvio,$detalle_envio,&$vectorAD,&$vectorAP,$datos_prestador_servicio)
// 		{
//         for($i=0; $i<sizeof($detalle); $i++)
//         {
// 			//si es un acto quirurjico
// 					while($detalle[$i][consecutivo]==NULL AND $detalle[$i][codigo_agrupamiento_id]!=NULL)
// 					{
// 						$k=$i;
// 						while($detalle[$i][codigo_agrupamiento_id]==$detalle[$k][codigo_agrupamiento_id])
// 						{
// 							$l=0;
// 							$l=$k;
// 
// 							while($detalle[$k][cargo] == $detalle[$l][cargo] AND !empty($detalle[$k][cargo]))
// 							{
// 								$precio+=$detalle[$l][valor_cubierto];     
// 								$l++;
// 							}
// 
// 							$k=$l-1;
// 							$ambito='';
// 							//arciho AP
// 							$datos_entidad_administradora = $this->GetDatosEntidadAdministradora($detalle[$l-1][plan_id]);
// 							$datos_cups                                     = $this->GetDatosCups($detalle[$l-1][cargo_cups]);
// 							$tipo_rips                                      = $this->GetTipoRips($ConsultaTipoRips,$datos_cups[tipo_cargo],$datos_cups[grupo_tipo_cargo],$datos_cups[grupo_tarifario_id],$datos_cups[subgrupo_tarifario_id],$detalle[$l-1][tarifario_id],$detalle[$l-1][cargo_cups],$arregloenvio[0][empresa]);
// 							$datos_usuario                              = $this->GetDatosUsuario($detalle[$l-1][ingreso]);
// 							$autorizacion                               = $this->GetAutorizacion($detalle_envio,$detalle[$l-1][autorizacion_ext]);
// 							$sw_dato_complementario             = $this->GetSwDatosComplementarios($detalle[$l-1][cargo_cups]);
// 		
// 							if($datos_entidad_administradora[sw_rips_con_cargo_cups] == '1')
// 								$cod_cargo = $detalle[$l-1][cargo];
// 							else
// 								$cod_cargo = $detalle[$l-1][cargo_tarifario];
// 							$diag_odontologicoOP = '';
// 							$atendido_por ='5';// este coresponde a "otro", revizar por que no esta parametrizado
// 							if($tipo_rips == 'OP')
// 							{
// 								$diag_odontologico = GetDiagnosticosOdontologiaAP($detalle[$l-1][transaccion]);
// 								$atendido_por ='5';
// 							}
// 							else
// 							{
// 								if(empty($detalle[$l-1][diagnostico_ppal]))
// 								{
// 									$diag_odontologicoOP = $detalle[$i][tipo_diagnostico_id];
// 								}
// 								else
// 								{
// 									$diag_odontologicoOP = $detalle[$l-1][diagnostico_ppal];
// 								}
// 								$diagnostico_relacionado = $detalle[$l-1][diagnostico_relacionado];
// 								$complicacion = $detalle[$l-1][complicacion];
// 								$forma_realizacion = $detalle[$l-1][forma_realizacion];
// 								//$atendido_por = $this -> GetTipoProfesional($detalle[$l-1][transaccion]);
// 								$atendido_por = $this -> GetTipoProfesionalRips($detalle[$l-1][usuario_nota]);
// 								if(empty($complicacion))
// 								{
// 									$complicacion = $diag_odontologicoOP;
// 								}
// 							}
// 		
// 							if(empty($detalle[$l-1][cargo_manual]))
// 							{
// 								$ambito=$this->GetAmbito($detalle[$l-1][servicio_cargo]);
// 								$finalidad_proc = "1";
// 								$fecha_proc = $this->FechaStamp($detalle[$l-1][fecha_cargo]);
// 							}
// 							else
// 							{//cargos ingresados manualmente
// 								$fecha_proc     = $this->FechaStamp($detalle[$l-1][ap_fechaprocedimiento]);
// 								$autorizacion   = $detalle[$l-1][autorizacion];
// 								$ambito         = $detalle[$l-1][ap_ambitoprocedimiento];
// 								$finalidad_proc = $detalle[$l-1][ap_finalidadprocedimiento];
// 							}
// 							if($precio>0)
// 							{
// 								$arregloAP.=$detalle[$l-1][prefijo]."".$detalle[$l-1][factura_fiscal].",";//R
// 								$arregloAP.=$datos_prestador_servicio[codigo_sgsss].",";
// 								$arregloAP.=$datos_usuario[tipo_id_paciente].",";//R
// 								$arregloAP.=$datos_usuario[paciente_id].",";//R
// 								$arregloAP.=$fecha_proc.",";//R fecha procedimiento
// 								$arregloAP.=$autorizacion.",";//numero autorizacion
// 								$arregloAP.=$cod_cargo.",";//R
// 								$arregloAP.=$ambito.",";//R ambito de realizacion del procedimiento
// 								$arregloAP.=$finalidad_proc.",";//R finalidad del procedimeinto
// 								$arregloAP.=$atendido_por[codigo_rips].",";//personal que atiende
// 								$arregloAP.=$diag_odontologicoOP.",";//diag ppal
// 								$arregloAP.=$diagnostico_relacionado.",";//diag relacionado
// 								$arregloAP.=$complicacion.",";//complicacion
// 								$arregloAP.=$forma_realizacion.",";//forma de realizacion del acto quirurgico
// 								$arregloAP.=$precio;
// 								$arregloAP.=$this->nl;
// 		
// 								$datos_cups = $this->GetDatosCups($detalle[$k][cargo_cups]);
// 								$this -> SetAcumuladorAD(&$vectorAD,$detalle[$k][prefijo]."".$detalle[$k][factura_fiscal],$datos_prestador_servicio[codigo_sgsss],$datos_cups[concepto_rips],$detalle[$k][cantidad],$precio);
// 								$precio=0;
// 								$lineas++;
// 							}
// 							$k++;
// 						}
// 						$i=$k;
// 					}
//         }
// 		 return $arregloAP.'\*/'.($lineas);
// 		}
}//fin clase rips
?>
