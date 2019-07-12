<?php

/**
 * $Id: funciones_facturacion.inc.php,v 1.46 2007/06/29 16:13:54 alexgiraldo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

    function BuscarCamaActiva($ingreso)
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT cama FROM movimientos_habitacion
                                    WHERE ingreso=$ingreso and fecha_egreso IS NULL";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                return false;
                }
                if(!$result->EOF)
                {   $var=$result->fields[0];   }
                $result->Close();
                return $var;
    }

    function BuscarUbicacionPaciente($ingreso)
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT a.cama , b.pieza, c.ubicacion
                                    FROM movimientos_habitacion as a, camas as b, piezas as c
                                    WHERE a.ingreso=$ingreso and a.fecha_egreso IS NULL
                                    and a.cama=b.cama and b.pieza=c.pieza";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                return false;
                }
                if(!$result->EOF)
                {   $var=$result->GetRowAssoc($ToUpper = false);   }
                $result->Close();
                return $var;
    }

    /**
    *
    */
    function PagosCuenta($cuenta)
    {
                $var='';
                list($dbconn) = GetDBconn();
                $query = "select b.fecha_ingcaja, a.prefijo, a.recibo_caja, b.total_abono,
                                    b.total_efectivo, b.total_cheques, b.total_tarjetas, b.total_bonos,
                                    b.usuario_id, c.nombre
                                    from rc_detalle_hosp as a, recibos_caja as b, system_usuarios as c
                                    where a.numerodecuenta=$cuenta and a.prefijo=b.prefijo and a.recibo_caja=b.recibo_caja
                                    and b.usuario_id=c.usuario_id and b.estado='0'";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                return false;
                }
                if(!$result->EOF)
                {
                        while(!$result->EOF)
                        {
                                        $var[]=$result->GetRowAssoc($ToUpper = false);
                                        $result->MoveNext();
                        }
                        $result->Close();
                }
                return $var;
    }

    function PagosCuentaDivision($cuenta)
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT  b.fecha_ingcaja, a.prefijo, a.recibo_caja, b.total_abono, b.total_efectivo, b.total_cheques, b.total_tarjetas, b.total_bonos, b.usuario_id,c.cuenta
                                            FROM rc_detalle_hosp as a, recibos_caja as b
                                            LEFT JOIN tmp_division_cuenta_abonos as c ON(c.prefijo=b.prefijo and c.recibo_caja=b.recibo_caja)
                                            WHERE a.numerodecuenta=$cuenta and a.prefijo=b.prefijo and a.recibo_caja=b.recibo_caja";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
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


    /**
    *
    */
    function PagosCajaRapida($cuenta)
    {
                list($dbconn) = GetDBconn();
                $query = "select b.fecha_registro, a.prefijo, a.factura_fiscal, b.total_abono,
                                    b.total_efectivo, b.total_cheques, b.total_tarjetas, b.total_bonos,
                                    b.usuario_id, c.nombre
                                    from fac_facturas_cuentas as a, fac_facturas_contado as b, system_usuarios as c,
                                         fac_facturas ff
                                    where a.numerodecuenta=$cuenta
                                    and a.prefijo=b.prefijo and a.factura_fiscal=b.factura_fiscal
                                    and b.usuario_id=c.usuario_id
                                    and ff.empresa_id = b.empresa_id
                                    and ff.prefijo = b.prefijo
                                    and ff.factura_fiscal = b.factura_fiscal
                                    and ff.estado not in ('2','3');";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                return false;
                }
                if(!$result->EOF)
                {
                        while(!$result->EOF)
                        {
                                        $var[]=$result->GetRowAssoc($ToUpper = false);
                                        $result->MoveNext();
                        }
                        $result->Close();
                }
                return $var;
    }

    /**
    *
    */
    function SaldoCuentaPaciente($cuenta)
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT (b.valor_total_paciente-(b.abono_efectivo + b.abono_cheque + b.abono_tarjetas + b.abono_bonos
                                        + b.abono_letras + abono_chequespf)) as saldo
                                    FROM cuentas as b
                                    WHERE b.numerodecuenta=$cuenta";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                return false;
                }

                if($result->fields[0] == 'NULL')
                {  $result->fields[0]=0;  }

                $saldo = $result->fields[0];
                $result->Close();

                $apro = BuscarCargoAjusteApro($cuenta);
                if(!empty($apro['precio']))
                {  $saldo = $saldo+$apro['precio'];  }

                $des = BuscarCargoAjusteDes($cuenta);
                if(!empty($des['precio']))
                {  $saldo = $saldo+$des['precio'];  }

                return $saldo;
    }


  /**
  *
  */
  function BuscarCargoAjusteDes($Cuenta)
  {
                $des=ModuloGetVar('app','Facturacion_Fiscal','CargoDescuento');

                list($dbconn) = GetDBconn();
                $query = "select a.precio, a.transaccion from cuentas_detalle as a
                                    where a.numerodecuenta=$Cuenta
                                    and a.cargo='$des'";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->fileError = __FILE__;
                        $this->lineError = __LINE__;
                        return false;
                }
                if(!$result->EOF)
                {   $var=$result->GetRowAssoc($ToUpper = false);   }
                $result->Close();
                return $var;
  }

    /**
    *
    */
  function BuscarCargoAjusteApro($Cuenta)
  {
                $apr=ModuloGetVar('app','Facturacion_Fiscal','CargoAprovechamiento');

                list($dbconn) = GetDBconn();
                $query = "select a.precio, a.transaccion from cuentas_detalle as a
                                    where a.numerodecuenta=$Cuenta
                                    and a.cargo='$apr'";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->fileError = __FILE__;
                        $this->lineError = __LINE__;
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
    function DatosPlan($plan)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT a.sw_tipo_plan, a.sw_afiliacion, a.protocolos,
                                a.sw_autoriza_sin_bd,a.plan_descripcion, a.tipo_tercero_id,a.tercero_id
                                FROM planes as a
                                WHERE a.estado='1' and a.plan_id=$plan
                                and a.fecha_final >= now() and a.fecha_inicio <= now()";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return false;
            }

            $var=$result->GetRowAssoc($ToUpper = false);;
            $result->Close();
            return $var;
    }

    /**
    *
    */
    function AbonoPaciente($cuenta)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT (b.abono_efectivo + b.abono_cheque + b.abono_tarjetas + b.abono_bonos
                                        + b.abono_letras + abono_chequespf) as abono
                                    FROM cuentas as b
                                    WHERE b.numerodecuenta=$cuenta";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return false;
            }

            $result->Close();
            return $result->fields[0];
    }

    /**
    *
    */
    function PendientesCargar($ingreso)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT e.fecha, f.nombre, g.descripcion as desdpto,
                                a.cargo_cups, d.descripcion as descups,
                                a.procedimiento_pendiente_cargar_id, g.empresa_id,
                                g.centro_utilidad, g.servicio, a.autorizacion_int,
                                a.autorizacion_ext, g.departamento,x.sw_tipo_cargo,
                                f.tipo_id_tercero, f.tercero_id, h.descripcion as tipo,i.tipo_sala_id,ter.nombre_tercero
                                FROM procedimientos_pendientes_cargar as a
                                LEFT JOIN hc_sub_procedimientos_realizados_caracteristicas i ON(a.ingreso=i.ingreso and a.evolucion_id=i.evolucion_id and a.cargo_cups=i.cargo_cups),
                                cups as d, hc_evoluciones as e,
                                profesionales as f, departamentos as g, tipos_profesionales as h,
                                terceros ter,hc_sub_procedimientos_realizados_cups_dpto x
                                WHERE a.ingreso=$ingreso and a.cargo_cups=d.cargo
                                and a.evolucion_id=e.evolucion_id and e.usuario_id=f.usuario_id
                                and e.departamento=g.departamento
                                and f.tipo_profesional=h.tipo_profesional
                                and ter.tipo_id_tercero=f.tipo_id_tercero and ter.tercero_id=f.tercero_id AND
                                a.cargo_cups=x.cargo_cups AND x.departamento=g.departamento";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
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

    /**
    *
    */
    function PendientesCargarEquivalencias($id,$cargo,$tarifario)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT count(b.cargo)
                                FROM procedimientos_pendientes_cargar_det as b
                                WHERE b.procedimiento_pendiente_cargar_id=$id
                                and b.cargo='$cargo' and b.tarifario_id='$tarifario'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return false;
            }

            $result->Close();
            return $result->fields[0];
    }

    /**
    *
    */
    function FirmaResultado($transaccion)
    {
        if (empty($transaccion))
        {
            $this->error = "Argumento Vacio transaccion";
            $this->mensajeDeError = "Argumento Vacio transaccion";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        list($dbconn) = GetDBconn();
        $query = "SELECT c.resultado_id
                            FROM os_maestro_cargos as a, os_maestro as b, hc_resultados_sistema as c
                            WHERE a.transaccion=$transaccion
                            and a.numero_orden_id=b.numero_orden_id
                            and b.sw_estado='4'
                            and b.numero_orden_id=c.numero_orden_id; ";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                return false;
        }

        $result->Close();
        return $result->fields[0];
    }

    /**
    *
    */
    function BuscarPendientesCargar($ingreso)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT count(a.ingreso)
                                FROM procedimientos_pendientes_cargar as a
                                WHERE a.ingreso=$ingreso";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return false;
            }

            $result->Close();
            return $result->fields[0];
    }


    /**
    *
    */
    function ValdiarEquivalencias($plan_id,$cargo)
    {
        list($dbconn) = GetDBconn();
            $query = "(  SELECT b.plan_id, a.grupo_tarifario_id, a.subgrupo_tarifario_id,
                                            a.grupo_tipo_cargo, a.tipo_cargo, a.tarifario_id, a.cargo,
                                            a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura,
                                            b.sw_descuento, a.sw_cantidad, e.sw_copagos, g.cargo_base
                                            FROM tarifarios_detalle a, plan_tarifario b,
                                            subgrupos_tarifarios e, tarifarios_equivalencias as g
                                            WHERE   g.cargo_base='$cargo' and g.cargo=a.cargo
                                            and g.tarifario_id=a.tarifario_id and
                                            b.plan_id = $plan_id and
                                            b.grupo_tarifario_id = a.grupo_tarifario_id AND
                                            b.subgrupo_tarifario_id    = a.subgrupo_tarifario_id AND
                                            b.tarifario_id = a.tarifario_id AND
                                            a.grupo_tarifario_id<>'00' AND
                                            a.grupo_tipo_cargo<>'SYS' AND
                                            e.subgrupo_tarifario_id = a.subgrupo_tarifario_id AND
                                            e.grupo_tarifario_id = a.grupo_tarifario_id AND
                                            excepciones(b.plan_id,b.tarifario_id, a.cargo) = 0
                    )
                    UNION
                    (
                        SELECT  b.plan_id, a.grupo_tarifario_id, a.subgrupo_tarifario_id,
                                                a.grupo_tipo_cargo, a.tipo_cargo, a.tarifario_id, a.cargo,
                                                a.descripcion, a.precio, a.gravamen, b.porcentaje, b.por_cobertura,
                                                b.sw_descuento, a.sw_cantidad, e.sw_copagos, g.cargo_base
                                                FROM tarifarios_detalle a, excepciones b,
                                                subgrupos_tarifarios e, tarifarios_equivalencias as g
                                                WHERE g.cargo_base='$cargo' and g.cargo=a.cargo
                                                and g.tarifario_id=a.tarifario_id and
                                                b.plan_id = $plan_id AND
                        b.tarifario_id = a.tarifario_id AND
                        b.sw_no_contratado = 0 AND
                        b.cargo = a.cargo AND
                        e.grupo_tarifario_id = a.grupo_tarifario_id AND
                        e.subgrupo_tarifario_id = a.subgrupo_tarifario_id
                    )";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
          return false;
        }
                if(!$result->EOF)
                {
                        while (!$result->EOF)
                        {
                            $vars[]= $result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                        }
                }
                $result->Close();
                return $vars;
    }


    /**
    *
    */
    function BuscarGrupoTipoCargo($cargo,$tarifario,&$dbconn)
    //function BuscarGrupoTipoCargo($cups)
    {
                //trae informacion para ver si es una cama
                $query = "SELECT b.sw_internacion, a.grupo_tipo_cargo
                                    FROM tarifarios_detalle as a
                                    LEFT JOIN grupos_tarifarios as b
                                    ON(a.grupo_tarifario_id=b.grupo_tarifario_id)
                                    WHERE a.cargo='$cargo' and a.tarifario_id='$tarifario'";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    $dbconn->RollbackTrans();
          return false;
        }
                $result->Close();

                //es una habitacion el codigo siempre es uno (1)
                if($result->fields[0]==1)
                {    return 1;  }
                else
                {       //no es internacion hay q buscarlo
                        $query = "SELECT codigo_agrupamiento_id FROM grupos_tipos_cargo
                                            WHERE grupo_tipo_cargo='".$result->fields[1]."'
                                            and codigo_agrupamiento_id is not NULL";
                        $results=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->fileError = __FILE__;
                            $this->lineError = __LINE__;
                            $dbconn->RollbackTrans();
                            return false;
                        }

                        if(!$result->EOF)
                        {   return $results->fields[0];   }
                        return false;
                }
                /*
                $query = "select b.codigo_agrupamiento_id
                                    from cups as a, grupos_tipos_cargo as b
                                    where a.cargo='$cups' and a.grupo_tipo_cargo=b.grupo_tipo_cargo
                                    and b.codigo_agrupamiento_id is not NULL";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
          return false;
        }

                if(!$result->EOF)
                { $vars= $result->GetRowAssoc($ToUpper = false); }

                $result->Close();
                return $vars;*/
    }

    function NombreCargoCups($cups)
    {
        list($dbconn) = GetDBconn();
                $query = "select a.descripcion from cups as a where a.cargo='$cups'";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
          return false;
        }

                $result->Close();
                return $result->fields[0];
    }

    function NombreTarifario($tarifario)
    {
        list($dbconn) = GetDBconn();
                $query = "select descripcion from tarifarios where tarifario_id='$tarifario'";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
          return false;
        }

                $result->Close();
                return $result->fields[0];
    }

    function NombreClaseCama($clase)
    {
        list($dbconn) = GetDBconn();
                $query = "select a.descripcion from tipos_clases_camas as a
                                    where a.tipo_clase_cama_id=$clase";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
          return false;
        }

                $result->Close();
                return $result->fields[0];
    }

    function BuscarLiqHabitacion($clase_cama,$plan)
    {
        list($dbconn) = GetDBconn();
                $query = "select a.* from planes_liq_camas as a
                                    where a.plan_id=$plan AND a.tipo_clase_cama_id=$clase_cama";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
          return false;
        }

                if(!$result->EOF)
                {
                        $vars = $result->GetRowAssoc($ToUpper = false);
                        $result->Close();
                }
                else
                {   $vars = 'EL PLAN NO TIENE DEFINIDO LA LIQUIDACION DE CAMAS, INFORME A CONTRATACION'; }
                return $vars;
    }

    function DiasHospitalizacion($date1,$date2)
    {
                if(empty($date2))
                {  $date2=date('Y-m-d');  }

                $s = strtotime($date1)-strtotime($date2);
                $d = intval($s/86400);
                //$s -= $d*86400;
                //$h = intval($s/3600);
                //$s -= $h*3600;
                //$m = intval($s/60);
                //$s -= $m*60;
                //$dif= (($d*24)+$h).hrs." ".$m."min";
                //$dif2= $d.$space.dias." ".$h.hrs." ".$m."min";

                return  $d+1;
    }

    function HorasHospitalizacion($date1,$date2)
    {
                if(empty($date2))
                {  $date2=date('Y-m-d h:i');  }

                $s = strtotime($date1)-strtotime($date2);
                //$s -= $d*86400;
                $h = intval($s/3600);
                //$s -= $h*3600;
                //$m = intval($s/60);
                //$s -= $m*60;
                //$dif= (($d*24)+$h).hrs." ".$m."min";
                //$dif2= $d.$space.dias." ".$h.hrs." ".$m."min";

                return  $h;
    }

    /**
    *
    */
    function InsertarCuentasDetalle($EmpresaId,$CUtilidad,$cuenta,$plan,$arr,$sql,&$dbconn)
    {
                //arr es asociativo cargo,tarifario,servicio, aut_int, aut_ext, cups,
                //cantidad, departamento, sw_cargue *tipo_tercero y tercero son utilizados para honorarios
                IncludeLib("tarifario_cargos");
                $x='';
                if(empty($dbconn))
                {
                        list($dbconn) = GetDBconn();
                        $dbconn->BeginTrans();
                        $x=1;
                }

                                $costo_variable = false;
                                foreach($_SESSION['CUENTAS']['ADD_CARGOS_VARIABLES'] AS $i => $v)
                                {
                                        foreach($arr AS $i1 => $v1)
                                        {
                                            if(isset($v[precio])
                                                AND $v[Cuenta]==$cuenta
                                                AND $v[departamento]==$v1[departamento]
                                                AND $v[codigo]==$v1[cups])
                                                {
                                                    $tmp_liq[precio_plan] = $v[precio];
                                                    $tmp_liq[cantidad] = $v[cantidad];
                                                    $tmp_liq[valor_cargo] = $v[cantidad]*$v[precio];
                                                    $tmp_liq[valor_cubierto] = $v[cantidad]*$v[precio];
                                                    $costo_variable = true;
                                                }
                                        }
                                }
                for($i=0; $i<sizeof($arr); $i++)
                {
                        $query=" SELECT nextval('cuentas_detalle_transaccion_seq')";
                        $result=$dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0) {
                                $dbconn->RollbackTrans();
                                return false;
                        }
                        $Transaccion=$result->fields[0];
                        $result->Close();

                                                                //($cuenta=0 ,$tarifario ,$cargo ,$cantidad=1 ,$descuento_manual_empresa=0 ,$descuento_manual_paciente=0 ,$aplicar_descuento_empresa=true ,$aplicar_descuento_paciente=true, $precio=0, $planId='', $Servicio='', $semanas_cotizacion='')
                        $liq=LiquidarCargoCuenta($cuenta,$arr[$i][tarifario],$arr[$i][cargo],$arr[$i][cantidad],0,0,true,true,0,$plan,$arr[$i][servicio],'');
                        if($costo_variable)
                        {
                            $liq[precio_plan] = $tmp_liq[precio_plan];
                            $liq[valor_cargo] = $tmp_liq[valor_cargo];
                            $liq[valor_cubierto] = $tmp_liq[valor_cubierto];
                            $liq[valor_no_cubierto] = 0;
                        }
                        $codigo='NULL';
                        //$agru=BuscarGrupoTipoCargo($arr[$i][cups]);
                        $agru = BuscarGrupoTipoCargo($arr[$i][cargo],$arr[$i][tarifario],&$dbconn);
                        if(!empty($agru))
                        {  $codigo=$agru;  }
                        //{  $codigo=$agru[codigo_agrupamiento_id];  }

                        if($arr[$i][aut_int]==='0' OR $arr[$i][aut_intcion_int] >0)
                        {   $arr[$i][aut_int]=$arr[$i][aut_int];   }
                        else
                        {   $arr[$i][aut_int]='NULL';   }
                        if($arr[$i][aut_ext]==='0' OR $arr[$i][aut_ext] >0)
                        {   $arr[$i][aut_ext]=$arr[$i][aut_ext];   }
                        else
                        {   $arr[$i][aut_ext]='NULL';   }

            if(!empty($arr[$i][fecha_cargo])){
              $fecha_cargo=$arr[$i][fecha_cargo];
            }else{
              $fecha_cargo=date("Y-m-d");
            }
                        $query = "INSERT INTO cuentas_detalle (
                                                                                                transaccion,
                                                                                                empresa_id,
                                                                                                centro_utilidad,
                                                                                                numerodecuenta,
                                                                                                departamento,
                                                                                                tarifario_id,
                                                                                                cargo,
                                                                                                cantidad,
                                                                                                precio,
                                                                                                valor_cargo,
                                                                                                valor_nocubierto,
                                                                                                valor_cubierto,
                                                                                                usuario_id,
                                                                                                facturado,
                                                                                                fecha_cargo,
                                                                                                valor_descuento_empresa,
                                                                                                valor_descuento_paciente,
                                                                                                servicio_cargo,
                                                                                                autorizacion_int,
                                                                                                autorizacion_ext,
                                                                                                porcentaje_gravamen,
                                                                                                sw_cuota_paciente,
                                                                                                sw_cuota_moderadora,
                                                                                                codigo_agrupamiento_id,
                                                                                                fecha_registro,
                                                                                                cargo_cups,
                                                                                                sw_cargue)
                                            VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$cuenta,'".$arr[$i][departamento]."','".$arr[$i][tarifario]."','".$arr[$i][cargo]."',".$arr[$i][cantidad].",".$liq[precio_plan].",".$liq[valor_cargo].",".$liq[valor_no_cubierto].",".$liq[valor_cubierto].",".UserGetUID().",".$liq[facturado].",'$fecha_cargo',".$liq[valor_descuento_paciente].",".$liq[valor_descuento_empresa].",".$arr[$i][servicio].",".$arr[$i][aut_int].",".$arr[$i][aut_ext].",".$liq[porcentaje_gravamen].",'".$liq[sw_cuota_paciente]."','".$liq[sw_cuota_moderadora]."',".$codigo.",'now()','".$arr[$i][cups]."','".trim($arr[$i][sw_cargue])."');";
                        $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0) {
                                $dbconn->RollbackTrans();
				echo $dbconn->ErrorMsg();
                                return false;
                        }

                        if(!empty($arr[$i][tipo_tercero]) AND !empty($arr[$i][tercero]))
                        {
                                    $query = "INSERT INTO cuentas_detalle_profesionales(
                                                                                                                                    transaccion,
                                                                                                                                    tipo_tercero_id,
                                                                                                                                    tercero_id)
                                                        VALUES($Transaccion,'".$arr[$i][tipo_tercero]."','".$arr[$i][tercero]."')";
                                    $dbconn->Execute($query);
                                    if($dbconn->ErrorNo() != 0) {
                                            $dbconn->RollbackTrans();
					     echo $dbconn->ErrorMsg();
                                            return false;
                                    }
                        }

                        //es de atencion de ordenes de servicio (Os_Atencion)
                        if(!empty($arr[$i][numero_orden_id]))
                        {
                                $sql = "UPDATE os_maestro_cargos SET transaccion=$Transaccion
                                            WHERE numero_orden_id=".$arr[$i][numero_orden_id]."
                                            AND cargo='".$arr[$i][cargo]."'
                                            AND tarifario_id='".$arr[$i][tarifario]."';";
                                $dbconn->Execute($sql);
                                if($dbconn->ErrorNo() != 0) {echo "22222222222";
                                        $dbconn->RollbackTrans();
					 echo $dbconn->ErrorMsg();
                                        return false;
                                }
                        }
                }

                if(!empty($sql))
                {
                        $dbconn->Execute($sql);
                        if($dbconn->ErrorNo() != 0) {
                                $dbconn->RollbackTrans();
                                return false;
                        }
                }
                if(!empty($x))
                {   $dbconn->CommitTrans();     }
                return true;
    }

    /**
    *
    */
    function InsertarTmpCuentasDetalle($EmpresaId,$CUtilidad,$cuenta,$plan,$arr)
    {
                //arr es asociativo cargo,tarifario,servicio, aut_int, aut_ext, cups, fecha
                //cantidad, departamento, sw_cargue *tipo_tercero y tercero son utilizados para honorarios
                IncludeLib("tarifario_cargos");
        list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();

                                $costo_variable = false;
                                foreach($_SESSION['CUENTAS']['ADD_CARGOS'] AS $i => $v)
                                {
                                        foreach($arr AS $i1 => $v1)
                                        {
                                            if(isset($v[precio])
                                                AND $v[Cuenta]==$cuenta
                                                AND $v[departamento]==$v1[departamento]
                                                AND $v[codigo]==$v1[cups])
                                                {
                                                    $tmp_liq[precio_plan] = $v[precio];
                                                    $tmp_liq[cantidad] = $v[cantidad];
                                                    $tmp_liq[valor_cargo] = $v[cantidad]*$v[precio];
                                                    $tmp_liq[valor_cubierto] = $v[cantidad]*$v[precio];
                                                    $costo_variable = true;
                                                }
                                        }
                                }
                for($i=0; $i<sizeof($arr); $i++)
                {
                        $query=" SELECT nextval('tmp_cuentas_detalle_transaccion_seq')";
                        $result=$dbconn->Execute($query);
                        $Transaccion=$result->fields[0];

                                                                //($cuenta=0 ,$tarifario ,$cargo ,$cantidad=1 ,$descuento_manual_empresa=0 ,$descuento_manual_paciente=0 ,$aplicar_descuento_empresa=true ,$aplicar_descuento_paciente=true, $precio=0, $planId='', $Servicio='', $semanas_cotizacion='')
                        $liq=LiquidarCargoCuenta($cuenta,$arr[$i][tarifario],$arr[$i][cargo],$arr[$i][cantidad],0,0,true,true,0,$plan,$arr[$i][servicio],'');
                        if($costo_variable)
                        {
                            $liq[precio_plan] = $tmp_liq[precio_plan];
                            $liq[valor_cargo] = $tmp_liq[valor_cargo];
                            $liq[valor_cubierto] = $tmp_liq[valor_cubierto];
                            $liq[valor_no_cubierto] = 0;
                        }

                        $codigo='NULL';
                        /*$agru=BuscarGrupoTipoCargo($arr[$i][cups]);
                        if(!empty($agru))
                        {  $codigo=$agru[codigo_agrupamiento_id];  }
                        */
                        $agru = BuscarGrupoTipoCargo($arr[$i][cargo],$arr[$i][tarifario],&$dbconn);
                        if(!empty($agru))
                        {  $codigo=$agru;  }

                        if($arr[$i][aut_int]==='0' OR $arr[$i][aut_intcion_int] >0)
                        {   $arr[$i][aut_int]=$arr[$i][aut_int];   }
                        else
                        {   $arr[$i][aut_int]='NULL';   }
                        if($arr[$i][aut_ext]==='0' OR $arr[$i][aut_ext] >0)
                        {   $arr[$i][aut_ext]=$arr[$i][aut_ext];   }
                        else
                        {   $arr[$i][aut_ext]='NULL';   }

                        $query = "INSERT INTO tmp_cuentas_detalle (
                                                                                                transaccion,
                                                                                                empresa_id,
                                                                                                centro_utilidad,
                                                                                                numerodecuenta,
                                                                                                departamento,
                                                                                                tarifario_id,
                                                                                                cargo,
                                                                                                cantidad,
                                                                                                precio,
                                                                                                valor_cargo,
                                                                                                valor_nocubierto,
                                                                                                valor_cubierto,
                                                                                                usuario_id,
                                                                                                facturado,
                                                                                                fecha_cargo,
                                                                                                valor_descuento_empresa,
                                                                                                valor_descuento_paciente,
                                                                                                servicio_cargo,
                                                                                                autorizacion_int,
                                                                                                autorizacion_ext,
                                                                                                porcentaje_gravamen,
                                                                                                sw_cuota_paciente,
                                                                                                sw_cuota_moderadora,
                                                                                                codigo_agrupamiento_id,
                                                                                                fecha_registro,
                                                                                                cargo_cups,
                                                                                                sw_cargue)
                                            VALUES ($Transaccion,'$EmpresaId','$CUtilidad',$cuenta,'".$arr[$i][departamento]."','".$arr[$i][tarifario]."','".$arr[$i][cargo]."',".$arr[$i][cantidad].",".$liq[precio_plan].",".$liq[valor_cargo].",".$liq[valor_no_cubierto].",".$liq[valor_cubierto].",".UserGetUID().",".$liq[facturado].",'".$arr[$i][fecha_cargo]."',".$liq[valor_descuento_paciente].",".$liq[valor_descuento_empresa].",".$arr[$i][servicio].",".$arr[$i][aut_int].",".$arr[$i][aut_ext].",".$liq[porcentaje_gravamen].",'".$liq[sw_cuota_paciente]."','".$liq[sw_cuota_moderadora]."',".$codigo.",'now()','".$arr[$i][cups]."','".$arr[$i][sw_cargue]."')";

                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0) {
                                $dbconn->RollbackTrans();
                                return false;
                        }

                        if(!empty($arr[$i][tipo_tercero]) AND !empty($arr[$i][tercero]))
                        {
                                    $query = "INSERT INTO tmp_cuentas_detalle_profesionales(
                                                                                                                                    transaccion,
                                                                                                                                    tipo_tercero_id,
                                                                                                                                    tercero_id)
                                                        VALUES($Transaccion,'".$arr[$i][tipo_tercero]."','".$arr[$i][tercero]."')";
                                    $result = $dbconn->Execute($query);
                                    if($dbconn->ErrorNo() != 0) {
                                            $dbconn->RollbackTrans();
                                            return false;
                                    }
                        }
                }

                $dbconn->CommitTrans();
                return true;
    }

    function DatosHonorariosCuenta($cuenta)
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT b.tipo_tercero_id, b.tercero_id, c.nombre, b.valor
                                    FROM cuentas_detalle as a, cuentas_detalle_honorarios as b,
                                    profesionales as c
                                    WHERE a.numerodecuenta=$cuenta and a.transaccion=b.transaccion
                                    and b.tipo_tercero_id=c.tipo_id_tercero and b.tercero_id=c.tercero_id
                                    ORDER BY c.tipo_id_tercero,c.tercero_id";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return false;
                }

                while (!$result->EOF)
                {
                    $vars[]= $result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }
                $result->Close();
                return $vars;
    }



    function DatosHonorariosVariasCuentas($cuentas)
    {
        $cuentas=str_replace(" ","",$cuentas);
        $vec=explode(",",$cuentas);
        $vec=array_unique($vec);

        if(empty($vec))
        {
            $this->error = "ERROR EN LLAMADO A FUNCION DatosHonorariosVariasCuentas";
            $this->mensajeDeError = "PARAMETRO $cuentas EMPTY";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        if(count($vec)==1)
        {
            $filtro = "numerodecuenta = " . current($vec);
        }
        else
        {
            foreach($vec as $k=>$v)
            {
                $filtro .= $v . " ";
            }
            $filtro = trim($filtro);
            $filtro = str_replace(" ",",",$filtro);
            $filtro = "numerodecuenta IN (" . $filtro . ") ";
        }



        list($dbconn) = GetDBconn();

        $query = "
                    SELECT
                    b.tipo_tercero_id,
                    b.tercero_id,
                    c.nombre,
                    b.valor
                    FROM
                    (
                        SELECT DISTINCT
                            transaccion
                        FROM
                            cuentas_detalle
                        WHERE
                            $filtro
                    ) AS a,
                    cuentas_detalle_honorarios as b,
                    profesionales as c

                    WHERE
                    b.transaccion = a.transaccion
                    AND (c.tipo_id_tercero = b.tipo_tercero_id AND c.tercero_id = b.tercero_id)

                    ORDER BY c.tipo_id_tercero, c.tercero_id
        ";

        $result=$dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        }

        while (!$result->EOF)
        {
            $vars[]= $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }

        $result->Close();
        return $vars;
    }



    function BuscarEmpleadorOrden($orden)
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT a.*
                                    FROM os_ordenes_servicios_empleadores as a, os_maestro as b
                                    WHERE b.numero_orden_id=$orden and a.orden_servicio_id=b.orden_servicio_id";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return false;
                }

                if(!$result->EOF)
                {
                    $vars= $result->GetRowAssoc($ToUpper = false);
                }
                $result->Close();
                return $vars;
    }

    function BuscarServicio($departamento)
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT servicio FROM departamentos WHERE departamento='$departamento'";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return false;
                }

                $result->Close();
                return $result->fields[0];
    }

    function BuscarMoviemientosCamas($ingreso)
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT a.cargo,a.fecha_ingreso,a.fecha_egreso,a.precio,a.cama,b.pieza,b.ubicacion,
                                        c.tipo_clase_cama_id, a.movimiento_id, a.departamento, f.descripcion, a.transaccion,
                                        g.descripcion as descar
                                        FROM movimientos_habitacion a, camas b, tipos_camas as c,
                                        departamentos as f, cups as g
                                        WHERE a.ingreso='$ingreso'
                                        AND a.cama=b.cama AND a.tipo_cama_id=c.tipo_cama_id
                                        AND a.departamento=f.departamento
                                        AND a.cargo=g.cargo
                                        ORDER BY a.fecha_ingreso;";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return false;
                }

                while (!$result->EOF)
                {
                    $vars[]= $result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }

                $result->Close();
                return $vars;
    }

    function BuscarEstadoCuenta($cuenta)
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT case when estado=1 then 'A' when estado=2 then 'I' when estado=3 then 'C' when estado=0 then 'F' end as estado
                                    FROM cuentas WHERE numerodecuenta=$cuenta";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    return false;
                }

                $result->Close();
                return $result->fields[0];
    }

    function ResponsableFacturaPaciente($tipoPaciente,$idPaciente,$empresa,&$dbconn)
    {
                $query = "SELECT tipo_id_tercero_generico_pacientes, tercero_id_generico_pacientes
                                    FROM cg_parametros_generales_contabilidad WHERE empresa_id='$empresa'";
                $results=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    $dbconn->RollbackTrans();
                    return false;
                }
                //generico para las facturas para el paciente
                if(!$results->EOF)
                {
                        $var['tipo_id_tercero']=$results->fields[0];
                        $var['tercero_id']=$results->fields[1];
                        if(!empty($var['tipo_id_tercero']) && !empty($var['tercero_id']))
                        {
                                return $var;
                        }
                        unset($var);
                }
                //verifica q si exista el tercero si no lo crea o actualiza
                $query = "SELECT b.residencia_telefono, b.residencia_direccion, b.tipo_pais_id, b.tipo_dpto_id, b.tipo_mpio_id,
                                    b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
                                    a.nombre_tercero
                                    FROM pacientes as b left join terceros as a on(a.tipo_id_tercero=b.tipo_id_paciente and a.tercero_id=b.paciente_id)
                                    WHERE b.tipo_id_paciente='$tipoPaciente' and b.paciente_id='$idPaciente'";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->fileError = __FILE__;
                        $this->lineError = __LINE__;
                        $dbconn->RollbackTrans();
                        return false;
                }
                //ya existe en tercero
                if(!empty($result->fields[6]))
                {       //actualiza terceros
                        $query = "UPDATE terceros SET nombre_tercero='".$result->fields[5]."',
                                                                                    tipo_pais_id='".$result->fields[2]."',
                                                                                    tipo_dpto_id='".$result->fields[3]."',
                                                                                    tipo_mpio_id='".$result->fields[4]."',
                                                                                    direccion='".$result->fields[1]."',
                                                                                    telefono='".$result->fields[0]."'
                                            WHERE tipo_id_tercero='$tipoPaciente' and tercero_id='$idPaciente'";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                $dbconn->RollbackTrans();
                                return false;
                        }
                }
                else
                {       //no existe
                  $query1 = "INSERT INTO terceros(tipo_id_tercero,tercero_id,nombre_tercero,tipo_pais_id,tipo_dpto_id,
                                            tipo_mpio_id,direccion,telefono,fax,email,celular,sw_persona_juridica,cal_cli,usuario_id,fecha_registro,busca_persona)
                                            VALUES('$tipoPaciente','$idPaciente','".$result->fields[5]."','".$result->fields[2]."','".$result->fields[3]."','".$result->fields[4]."','".$result->fields[1]."','".$result->fields[0]."','','','','1','0',".UserGetUID().",'now()','');";
                        $dbconn->Execute($query1);
                        if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";echo "===>".$dbconn->ErrorMsg();
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                $dbconn->RollbackTrans();
                                return false;
                        }
                }
                //es el mismo paciente
                $var['tipo_id_tercero']=$tipoPaciente;
                $var['tercero_id']=$idPaciente;

                return $var;
    }


    function CargarHabitacionCuenta($posiciones,$vector,$todo=false,&$dbconn,$empresa,$cuenta,$cargue=3)
    {       //$vector el vector de loquidacion con todos los cargos, posiciones elegidas
            //todo el vector se va a cargar
            //cargue 3=>manual

            if($cargue==3)
            { $sw_liq_manual=1; }
            else
            { $sw_liq_manual=0; }

            if($todo)
            {  $posiciones=sizeof($vector); }

            for($i=0; $i<$posiciones; $i++)
            {
                    $liq = '';
                    if($todo)
                    {  $liq =$vector[$i];   }
                else
                    {  $liq =$vector[$posiciones[$i]];  }
                    $agru = BuscarGrupoTipoCargo($liq[cargo],$liq[tarifario_id],&$dbconn);
                    if(!empty($agru))
                    {  $codigo=$agru;  }

                    $query=" SELECT nextval('cuentas_detalle_transaccion_seq')";
                    $result=$dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                            $this->error = "Error SELECT nextval cuentas_detalle";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->fileError = __FILE__;
                            $this->lineError = __LINE__;
                            $dbconn->RollbackTrans();
                            return false;
                    }
                    $Transaccion=$result->fields[0];
                    $result->Close();

                    $liq[aut_int]='NULL';
                    $liq[aut_ext]='NULL';
                    //$liq[departamento]='010201';
                    //$liq[servicio]='4';

                    //--buscar el centro de utilidad del dpto
                    $query=" SELECT centro_utilidad,servicio FROM departamentos WHERE departamento='".$liq[departamento]."'";
                    $result=$dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                            $this->error = "Error SELECT nextval cuentas_detalle";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->fileError = __FILE__;
                            $this->lineError = __LINE__;
                            $dbconn->RollbackTrans();
                            return false;
                    }
                    $cu=$result->fields[0];
                    $liq[servicio]=$result->fields[1];
                    $result->Close();

                    $query = "INSERT INTO cuentas_detalle (
                                                            transaccion,empresa_id,
                                                            centro_utilidad,numerodecuenta,
                                                            departamento,tarifario_id,
                                                            cargo,cantidad,
                                                            precio,valor_cargo,
                                                            valor_nocubierto,valor_cubierto,
                                                            usuario_id,facturado,
                                                            fecha_cargo,valor_descuento_empresa,
                                                            valor_descuento_paciente,servicio_cargo,
                                                            autorizacion_int,autorizacion_ext,
                                                            porcentaje_gravamen,sw_cuota_paciente,
                                                            sw_cuota_moderadora,codigo_agrupamiento_id,
                                                            fecha_registro,cargo_cups,sw_cargue,sw_liq_manual)
                                        VALUES ($Transaccion,'$empresa','$cu',$cuenta,'".$liq[departamento]."','".$liq[tarifario_id]."','".$liq[cargo]."',".$liq[cantidad].",".$liq[precio_plan].",".$liq[valor_cargo].",".$liq[valor_no_cubierto].",".$liq[valor_cubierto].",".UserGetUID().",".$liq[facturado].",'now()',".$liq[valor_descuento_paciente].",".$liq[valor_descuento_empresa].",".$liq[servicio].",".$liq[aut_int].",".$liq[aut_ext].",".$liq[porcentaje_gravamen].",'".$liq[sw_cuota_paciente]."','".$liq[sw_cuota_moderadora]."',".$codigo.",'now()','".$liq[cargo_cups]."','$cargue','$sw_liq_manual')";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                            $this->error = "INSERT INTO cuentas_detalle";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->fileError = __FILE__;
                            $this->lineError = __LINE__;
                            $dbconn->RollbackTrans();
                            return false;
                    }

                    /*for($j=0; $j<sizeof($liq[$i]['movimientos']); $j++)
                    {
                            $query = "UPDATE movimientos_habitacion
                                                SET transaccion=$Transaccion
                                                WHERE movimiento_id=".$liq[$i]['movimientos'][$j]."";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0) {
                                    $this->error = "UPDATE movimientos_habitacion";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $this->fileError = __FILE__;
                                    $this->lineError = __LINE__;
                                    $dbconn->RollbackTrans();
                                    return false;
                            }
                    }*/
            }
            return true;
    }

    function CrearCuentaIngreso(&$dbconn,$tipo_id_paciente,$paciente_id,$departamento,$plan,$empresa,$centro_utilidad,$afiliado,$rango,$semanas,$autorizacion)
    {
                $query = "SELECT sw_tipo_plan, sw_afiliacion
                                    FROM planes
                                    WHERE estado='1' and plan_id=".$plan."
                                    and fecha_final >= now() and fecha_inicio <= now()";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al traer la secuencia ingresos_ingreso_seq ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                list($TipoPlan,$swAfiliados,$Protocolos,$swAutoSinBD)=$result->FetchRow();

                $query="SELECT nextval('ingresos_ingreso_seq')";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al traer la secuencia ingresos_ingreso_seq ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                $IngresoId=$result->fields[0];
                $query = "INSERT INTO ingresos (ingreso,
                                                                                tipo_id_paciente,
                                                                                paciente_id,
                                                                                fecha_ingreso,
                                                                                causa_externa_id,
                                                                                via_ingreso_id,
                                                                                comentario,
                                                                                departamento,
                                                                                estado,
                                                                                fecha_registro,
                                                                                usuario_id,
                                                                                departamento_actual)
                                    VALUES($IngresoId,'".$tipo_id_paciente."','".$paciente_id."','now()','15','1','','".$departamento."','0','now()','".UserGetUID()."','".$departamento."')";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error ingresos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->lineError = __LINE__;
                                $dbconn->RollbackTrans();
                                return false;
                }

                //bd afiliados
                if($swAfiliados==1)
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
                                    unset($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
                                    $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$class->salida;
                        }

                        if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador']))
                        {
                                $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_empleador'];
                                $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_id_empleador'];
                                $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador'];
                                $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['telefono_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_telefono_empresa'];
                                $_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['direccion_empleador']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_direccion_empresa'];

                                list($dbconn) = GetDBconn();
                                $query = "SELECT * FROM empleadores
                                                    WHERE tipo_id_empleador='".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador']."'
                                                    AND empleador_id='".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador']."'";
                                $result = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error ingresos";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->lineError = __LINE__;
                                        $dbconn->RollbackTrans();
                                }
                                //no existe el empleador en la tabla
                                if($result->EOF)
                                {
                                        $query = "INSERT INTO empleadores(
                                                                                empleador_id,
                                                                                tipo_id_empleador,
                                                                                nombre,
                                                                                direccion,
                                                                                telefono,
                                                                                usuario_id)
                                                            VALUES('".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador']."','".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador']."','".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['empleador']."','".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['direccion_empleador']."','".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['telefono_empleador']."',".UserGetUID().")";
                                        $dbconn->Execute($query);
                                        if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error ingresos";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                $this->lineError = __LINE__;
                                                $dbconn->RollbackTrans();
                                                return false;
                                        }
                                }
                                $result->Close();

                                $query = "INSERT INTO ingresos_empleadores(
                                                                        empleador_id,
                                                                        tipo_id_empleador,
                                                                        ingreso)
                                                    VALUES('".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['id_empleador']."','".$_SESSION['AUTORIZACIONES']['RETORNO']['EMPLEADOR']['tipo_empleador']."',$IngresoId)";
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error ingresos";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->lineError = __LINE__;
                                        $dbconn->RollbackTrans();
                                        return false;
                                }
                        }
                }
                //fin afiliados

                $query="SELECT nextval('cuentas_numerodecuenta_seq')";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al traer la secuencia cuentas_numerodecuenta_seq ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                }
                $Cuenta=$result->fields[0];

                if(empty($semanas)) {  $sem=0; }
                else {  $sem=$semanas;  }
                 $query = "INSERT INTO cuentas (numerodecuenta,
                                                                                empresa_id,
                                                                                centro_utilidad,
                                                                                ingreso,
                                                                                plan_id,
                                                                                estado,
                                                                                usuario_id,
                                                                                fecha_registro,
                                                                                tipo_afiliado_id,
                                                                                rango,
                                                                                autorizacion_int,
                                                                                autorizacion_ext,
                                                                                semanas_cotizadas,
                                                                                sw_estado_paciente,
                                                                                fecha_cierre,
                                                                                usuario_cierre)
                                    VALUES($Cuenta,'".$empresa."','".$centro_utilidad."',$IngresoId,'".$plan."','1','".UserGetUID()."','now()','".$afiliado."','".$rango."',".$autorizacion.",NULL,$sem,0,'now()','".UserGetUID()."')";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error cuentas";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                //$dbconn->RollbackTrans();
                                $this->lineError = __LINE__;
                                $dbconn->RollbackTrans();
                                return false;
                }

                //Esto lo hacemos para determinar si se hizo una cuenta, para tratar de
                // controlar el ghost de que se inserta en caja y no se esta insertando en ingresos, ni en cuenta <DUVAN>
                $query = "SELECT COUNT(*) FROM cuentas WHERE numerodecuenta='$Cuenta'";
                $rest=$dbconn->Execute($query);
                if($rest->fields[0]<1)
                {
                        $this->error = "(cuenta) SE PERDIO LA SECCION, INTENTE EL PROCESO OTRA VEZ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->lineError = __LINE__;
                        $dbconn->RollbackTrans();
                        return false;
                }
                unset($_SESSION['AUTORIZACIONES']);

                $_SESSION['FUNCIONES']['FACTURACION']['CUENTA']=$Cuenta;
                $_SESSION['FUNCIONES']['FACTURACION']['INGRESO']=$IngresoId;
                return true;
    }

  function EncontrarFormatoFactura($empresa,$plan,$documento,$tipo){
        $sql3="";
        if($plan)
        {
            $sql="AND (plan_id='".$plan."' OR plan_id IS NULL)";
        }
        else
        {
            $sql="AND plan_id IS NULL";
        }
        if(($documento=='factura' AND $tipo=='cliente') OR $documento=='resumen')
        {
            $sql2=" AND sw_factura_cliente='1'";
        }
        elseif($documento=='factura' AND $tipo=='paciente')
        {
            $sql4=" AND sw_factura_paciente = '1'";
        }
        if($documento=='conceptos')
        {
            $sql2=" AND sw_factura_conceptos='1'";
        }
        if($documento=='hojacargos')
        {
            $sql2=" AND sw_hoja_cargos='1'";

        }
        if($documento=='factura_agrupada')
        {
            $sql2=" AND sw_factura_agrupada = '1'";
        $sql3=" ,titulo";

        }
    list($dbconn) = GetDBconn();
        $query = "SELECT a.ruta_reporte $sql3
                            FROM reportes_facturas_clientes_planes a
                            WHERE a.empresa_id='".$empresa."'
                            $sql $sql2 $sql4 order by plan_id";
        $result=$dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error reportes_facturas_clientes_planes";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            if(!$result->EOF)
            {
                $datos = $result->GetRowAssoc($ToUpper = false);
            }
            return $datos['ruta_reporte'];
        }
    return false;
  }


//--------------------------------------------------------
?>
