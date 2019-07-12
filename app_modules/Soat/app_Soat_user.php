<?php
  /**
  * $Id: app_Soat_user.php,v 1.29 2007/06/01 15:34:36 carlos Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * Modulo para el manejo de los eventos del Soat (determinar los acontecimientos de un evento soat)
  */
  /**
  * app_Soat_user.php
  *
  * Clase que establece las bosquedas y motodos de acceso a la informacion de las
  * caracterosticas del accidente, del vehoculo, de la poliza y la E.P.S.,
  * que se relacionan con un evento SOAT
  **/
  class app_Soat_user extends classModulo
  {
    /**
    * Variable para el manejo de errores
    *
    * @var string
    * @access public
    */
    var $uno;
    /**
    * Variable para el manejo de poliza falsa (830031511-6)
    *
    * @var string
    * @access public
    */
    var $polizamala;
    /**
    * Variable
    *
    * @var integer
    * @access public
    */
    var $admin1;
    /**
    * Variable
    *
    * @var integer
    * @access public
    */
    var $admin2;
    /**
    * Constructor de la clase
    */
    function app_Soat_user(){}
    /**
    *
    * @return boolean
    */
    function main()
    {
      $this->PrincipalSoat2();
      return true;
    }
    /**
    * Funcion donde se realiza la busqueda de permisos de acceso al modulo
    *
    * @return mixed
    */
    function UsuariosSoat()
    {
      $evt = AutoCarga::factory("EventosSoat","classes","app","Soat");
      $var2 = $evt->ObtenerPermisos(UserGetUID());
      $mtz[0]='EMPRESAS';
      $mtz[1]='CENTRO DE UTILIDAD';
      $url[0]='app';
      $url[1]='Soat';
      $url[2]='user';
      $url[3]='PrincipalSoat';
      $url[4]='permisosoat';
      $this->salida .=gui_theme_menu_acceso('SOAT', $mtz, $var2, $url, ModuloGetURL('system','Menu'));
      return true;
    }

    function SetStyle($campo)//Mensaje de error en caso de no encontrar los datos
    {
        if ($this->frmError[$campo] || $campo=="MensajeError")
        {
            if ($campo=="MensajeError")
            {
                return ("<tr><td class='label_error' colspan='2' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
            }
            else
            {
                return ("label_error");
            }
        }
        return ("label");
    }

    function SaldoEvento($ano)//Funcion que establece el saldo inicial al crear el evento
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT salario_mes
                FROM salario_minimo_ano
                WHERE ano='".$ano."';";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $salmin=doubleval($resulta->fields[0]);
        $salmin=number_format((($salmin/30)*800), 2, '.', '');
        return $salmin;
    }

    function BuscarPacienteSoat($TipoDo,$Docume)//Trae los datos personales del paciente
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT B.descripcion,
                C.descripcion AS descripcionsexo,
                A.primer_apellido,
                A.segundo_apellido,
                A.primer_nombre,
                A.segundo_nombre,
                A.residencia_direccion,
                A.residencia_telefono,
                A.fecha_nacimiento,
                A.tipo_pais_id,
                A.tipo_dpto_id,
                A.tipo_mpio_id,
                A.lugar_expedicion_documento
                FROM pacientes AS A,
                tipos_id_pacientes AS B,
                tipo_sexo AS C
                WHERE A.tipo_id_paciente='".$TipoDo."'
                AND A.paciente_id='".$Docume."'
                AND A.tipo_id_paciente=B.tipo_id_paciente
                AND A.sexo_id=C.sexo_id;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarIdPaciente($tipo_id,$TipoId='')//Busca el tipo de docuemento
    {
        foreach($tipo_id as $value=>$titulo)
        {
            if($value==$TipoId)
            {
                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
            }
            else
            {
                $this->salida .=" <option value=\"$value\">$titulo</option>";
            }
        }
    }

//  function BuscarEventoSoat($TipoDo,$Docume)//Busca si el paciente tiene un evento ya creado
//  {
//      //      --A.ambulancia_id,
//      //      --D.fecha_accidente,
//      //      --LEFT JOIN soat_accidente AS D ON (A.accidente_id=D.accidente_id),
// 
//      list($dbconn) = GetDBconn();
//          $query = "SELECT A.evento,
//                                      A.poliza,
//                                      A.condicion_accidentado,
//                                      A.saldo,
//                                      A.codigo_eps,
//                                      A.accidente_id,
//                                      A.asegurado,
//                                      A.empresa_id,
//                                      C.nombre_tercero,
//                                      E.razon_social,
//                                      D.ambulancia_id
//                                  FROM soat_eventos AS A,
//                                      soat_polizas AS B,
//                                      terceros AS C,
//                                      empresas AS E,
//                                      soat_ambulancias AS D
//                                  WHERE A.tipo_id_paciente='".$TipoDo."'
//                                  AND A.paciente_id='".$Docume."'
//                                  AND A.poliza=B.poliza
//                                  AND B.tipo_id_tercero=C.tipo_id_tercero
//                                  AND B.tercero_id=C.tercero_id
//                                  AND A.empresa_id=E.empresa_id
//                                  AND A.evento=D.evento
//                                  ORDER BY poliza;";exit;
//      $resulta = $dbconn->Execute($query);
//      if ($dbconn->ErrorNo() != 0)
//      {
//          $this->error = "Error al Cargar el Modulo";
//          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//          return false;
//      }
//      $i=0;
//      while(!$resulta->EOF)
//      {
//          $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
//          $resulta->MoveNext();
//          $i++;
//      }
//      return $var;
//  }

    function BuscarEventoSoat($TipoDo,$Docume)//Busca si el paciente tiene un evento ya creado
    {
        //      --A.ambulancia_id,
        //      --D.fecha_accidente,
        //      --LEFT JOIN soat_accidente AS D ON (A.accidente_id=D.accidente_id),

        list($dbconn) = GetDBconn();
                $query ="SELECT A.evento,
                                        A.poliza,
                                        A.condicion_accidentado,
                                        A.saldo,
                                        A.codigo_eps,
                                        A.accidente_id,
                                        A.asegurado,
                                        A.empresa_id,
                                        C.nombre_tercero,
                                        E.razon_social,
                                        D.ambulancia_id,
                                        A.saldo_inicial
                                    FROM soat_eventos AS A
                                         LEFT JOIN soat_ambulancias AS D
                                         ON(A.evento = D.evento), 
                                        soat_polizas AS B,
                                        terceros AS C,
                                        empresas AS E
                                    WHERE A.tipo_id_paciente='".$TipoDo."'
                                    AND A.paciente_id='".$Docume."'
                                    AND A.poliza=B.poliza
                                    AND B.tipo_id_tercero=C.tipo_id_tercero
                                    AND B.tercero_id=C.tercero_id
                                    AND A.empresa_id=E.empresa_id                                   
                                    ORDER BY A.poliza";
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
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarEventoSoat2($TipoDo,$Docume)//Busca si el paciente tiene un evento ya creado
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT DISTINCT A.evento,
                A.poliza,
                A.condicion_accidentado,
                A.saldo,
                A.codigo_eps,
                A.accidente_id,
                --A.ambulancia_id,
                A.asegurado,
                A.empresa_id,
                C.nombre_tercero,
                D.fecha_accidente,
                E.razon_social
        --,F.ingreso
                FROM soat_eventos AS A
                LEFT JOIN soat_accidente AS D ON (A.accidente_id=D.accidente_id)
                LEFT JOIN ingresos_soat AS F ON
                (A.evento=F.evento),
                soat_polizas AS B,
                terceros AS C,
                empresas AS E
                WHERE A.tipo_id_paciente='".$TipoDo."'
                AND A.paciente_id='".$Docume."'
                AND A.poliza=B.poliza
                AND B.tipo_id_tercero=C.tipo_id_tercero
                AND B.tercero_id=C.tercero_id
                AND A.empresa_id=E.empresa_id
                ORDER BY poliza;";
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
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarEventoSoatMod($evenelegmo)//Busca la informacion del evento elegido a modificar
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT A.evento,
                A.poliza,
                A.condicion_accidentado,
                A.saldo,
                A.accidente_id,
                A.asegurado,
                A.codigo_eps,
                A.ambulancia_propia_ips,
                A.tipo_ambulancia_id,
                A.soat_naturaleza_evento_id,
                A.tipo_servicio_vehiculo_id,
                A.intervension_autoridad,
                B.vigencia_desde,
                B.vigencia_hasta,
                B.tipo_id_tercero,
                B.tercero_id,
                B.sucursal,
                B.placa_vehiculo,
                B.marca_vehiculo,
                B.tipo_vehiculo,
                C.nombre_tercero,
                D.fecha_accidente,
                D.sitio_accidente,
                D.zona,
                D.tipo_pais_id,
                D.tipo_dpto_id,
                D.tipo_mpio_id,
                D.informe_accidente,
                D.tipo_tratamiento
                FROM soat_eventos AS A
                LEFT JOIN soat_accidente AS D ON
                (A.accidente_id=D.accidente_id),
                soat_polizas AS B,
                terceros AS C
                WHERE A.evento=".$evenelegmo."
                AND A.poliza=B.poliza
                AND B.tipo_id_tercero=C.tipo_id_tercero
                AND B.tercero_id=C.tercero_id;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarNombrePaci($TipoDo,$Docume)//Busca el nombre completo del paciente
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT tipo_id_paciente,
                paciente_id,
                lugar_expedicion_documento,
                primer_apellido,
                segundo_apellido,
                primer_nombre,
                segundo_nombre
                FROM pacientes
                WHERE tipo_id_paciente='".$TipoDo."'
                AND paciente_id='".$Docume."';";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarInforAccidente($accieleg)//Busca la informacion sobre el accidente
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT fecha_accidente,
                sitio_accidente,
                tipo_pais_id,
                tipo_dpto_id,
                tipo_mpio_id,
                zona,
                informe_accidente
                FROM soat_accidente
                WHERE accidente_id=".$accieleg.";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarModificarEventoPropiVeh($evento)//Busca la informacion sobre el propietario del vehiculo
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT apellidos_propietario,
                nombres_propietario,
                tipo_id_propietario,
                propietario_id,
                extipo_pais_id,
                extipo_dpto_id,
                extipo_mpio_id,
                direccion_propietario,
                telefono_propietario,
                tipo_pais_id,
                tipo_dpto_id,
                tipo_mpio_id
                FROM soat_vehiculo_propietario
                WHERE evento=".$evento.";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarModificarEventoConduVeh($evento)//Busca la informacion sobre el conductor del vehiculo
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT apellidos_conductor,
                nombres_conductor,
                tipo_id_conductor,
                conductor_id,
                extipo_pais_id,
                extipo_dpto_id,
                extipo_mpio_id,
                direccion_conductor,
                telefono_conductor,
                tipo_pais_id,
                tipo_dpto_id,
                tipo_mpio_id
                FROM soat_vehiculo_conductor
                WHERE evento=".$evento.";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarInforAmbulancia($ambueleg)//Busca la informacion de la poliza
    {
      list($dbconn) = GetDBconn();
             
      $query = "SELECT * 
                FROM    soat_ambulancias 
                WHERE   ambulancia_id=".$ambueleg.";";   
      $resulta = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }

      $var=$resulta->GetRowAssoc($ToUpper = false);
      $resulta->Close();  
      return $var;
    }
    /**
    *
    * @param integer evento Numero del evento
    *
    * @return boolean
    */
    function BuscarAmbulanciasEvento($evento)
    {
      list($dbconn) = GetDBconn();
      $query = "SELECT  *,
                        date(fecha_traslado) as fecha_traslado, 
                        CASE tipo_traslado WHEN 1 THEN 'Traslado Inicial' 
                            ELSE 'Remisión' END AS tipo_traslado
                FROM    soat_ambulancias 
                WHERE   evento=".$evento.";";       
      $resulta = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      while(!$resulta->EOF)
      {
        $var[]=$resulta->GetRowAssoc($ToUpper = false);
        $resulta->MoveNext();
      }
      return $var;
    }
    //-----------fin vuevo dar
    
    function BuscarCondicion()//Busca las condiciones del accidentado
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT condicion_accidentado,
                descripcion
                FROM condicion_accidentados
                ORDER BY descripcion;";
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
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }
    
	/************************************************************************************ 


	*
	*************************************************************************************/
	function ObtenerTiposEventos()
	{
		list($dbconn) = GetDBconn();
		$sql  = "SELECT * ";
		$sql .= "FROM	soat_naturaleza_evento ";
		$sql .= "ORDER BY soat_naturaleza_evento_id ";
		$rst = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$rangos = array();
		while (!$rst->EOF)
		{
			$rangos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
		}
		$rst->Close();
		return $rangos;
	}
	/************************************************************************************ 
	*
	*************************************************************************************/
	function ObtenerTiposServiciosVehiculos()
	{
		list($dbconn) = GetDBconn();
		$sql  = "SELECT * ";
		$sql .= "FROM	tipos_servicios_vehiculos ";
		$sql .= "ORDER BY tipo_servicio_vehiculo_id ";
		$rst = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$rangos = array();
		while (!$rst->EOF)
		{
			$rangos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
		}
		$rst->Close();
		return $rangos;
	}

    function BuscarZonaResidencia()//Busca las zonas residenciales
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT zona_residencia,
                descripcion
                FROM zonas_residencia
                ORDER BY zona_residencia DESC;";
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
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarAseguradoraSoat()//Busca la aseguradora del SOAT
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT A.nombre_tercero,
                B.tipo_id_tercero,
                B.tercero_id,
                B.identificador_at
                FROM terceros AS A,
                terceros_soat AS B
                WHERE A.tercero_id=B.tercero_id
                AND A.tipo_id_tercero=B.tipo_id_tercero
                ORDER BY A.nombre_tercero;";
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
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarEpsSoat()//Busca las entidades de la EPS
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT codigo_eps,
                descripcion
                FROM entidades_eps
                ORDER BY descripcion;";
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
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarConsultaPolizaSoat()//
    {
        list($dbconn) = GetDBconn();
        $polizasoat=$_POST['poliza1'].'-'.$_POST['poliza2'].'-'.$_POST['poliza3'];
        $query = "SELECT poliza,
                vigencia_desde,
                vigencia_hasta,
                tipo_id_tercero,
                tercero_id,
                sucursal,
                placa_vehiculo,
                marca_vehiculo,
                tipo_vehiculo
                FROM soat_polizas
                WHERE poliza='".$polizasoat."';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL CONSULTAR LA POLIZA";
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        if(empty($var))
        {
            $this->frmError["MensajeError"]="NO SE ENCONTRÓ LA POLIZA EN LA BASE DE DATOS";
        }
        else
        {
            $this->frmError["MensajeError"]="LA POLIZA SE ENCUENTRA EN LA BASE DE DATOS";
            $_SESSION['soat']['polizacons']=$var;
        }
        $this->uno=1;
        $this->ConsultaPolizaSoat();
        return true;
    }

    function ValidarDatosAccidente()//Valida y Guarda los datos del accidente en "soat_accidente"
    {
        $this->uno=0;
        if(empty($_POST['condicion']) AND $_POST[tiponaturaleza]=='01')
        {
            $this->frmError["condicion"]=1;
        }
        if(empty($_POST['fecha']))
        {
            $this->frmError["fecha"]=1;
        }
        else
        {
            $valfec=explode('/',$_POST['fecha']);
            $day=$valfec[0];
            $mon=$valfec[1];
            $yea=$valfec[2];
            if(checkdate($mon, $day, $yea)==0)
            {
                $_POST['fecha']='';
                $this->frmError["fecha"]=1;
            }
            else
            {
                $fec=date ("Y-m-d");
                if($fec < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                {
                    $_POST['fecha']='';
                    $this->frmError["fecha"]=1;
                }
                else
                {
                    $fecha = $yea.'-'.$mon.'-'.$day.' ';
                    $_SESSION['soat']['accidentes']['ano']=$yea;
                    $fechahora=1;
                }
            }
        }
        if($_POST['horario']==-1||$_POST['minutero']==-1)
        {
            $this->frmError["horario"]=1;
        }
        else
        {
            if($fechahora==1)
            {
                $horac=intval(date("H"));
                $minac=intval(date("i"));
                $hor=intval($_POST['horario']);
                $min=intval($_POST['minutero']);
                if($horac<$hor AND $fec <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                {
                    $_POST['horario']=-1;
                    $this->frmError["horario"]=1;
                }
                else
                {
                    if($minac<$min AND $horac<=$hor AND $fec <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['minutero']=-1;
                        $this->frmError["horario"]=1;
                    }
                }
            }
        }
        if(empty($_POST['pais']))
        {
            $this->frmError["pais"]=1;
        }
        if(empty($_POST['dpto']))
        {
            $this->frmError["dpto"]=1;
        }
        if(empty($_POST['mpio']))
        {
            $this->frmError["mpio"]=1;
        }
        if(empty($_POST['zona']))
        {
            $this->frmError["zona"]=1;
        }//epssoat
        if($_POST['poliza1']==NULL||$_POST['poliza2']==NULL||$_POST['poliza3']==NULL)
        {
            $this->frmError["poliza1"]=1;
        }
        else
        {
            list($dbconn) = GetDBconn();
            $polizasoat=$_POST['poliza1'].'-'.$_POST['poliza2'].'-'.$_POST['poliza3'];
            $query = "SELECT poliza,
                    vigencia_desde,
                    vigencia_hasta,
                    tipo_id_tercero,
                    tercero_id,
                    sucursal,
                    placa_vehiculo,
                    marca_vehiculo,
                    tipo_vehiculo
                    FROM soat_polizas
                    WHERE poliza='".$polizasoat."';";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL CONSULTAR LA POLIZA";
            }
            while(!$resulta->EOF)
            {
                $var=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
            }
            if(empty($var))
            {
                $this->frmError["MensajeError"]="NO SE ENCONTRÓ LA POLIZA EN LA BASE DE DATOS";
            }
            else
            {
                $this->frmError["MensajeError"]="LA POLIZA SE ENCUENTRA EN LA BASE DE DATOS";
                $_SESSION['soat']['polizaenco']=$var;
            }
            $this->uno=1;
        }
        if($this->frmError["condicion"] == 1 ||empty($_POST['fecha'])||
        $_POST['horario']==-1||$_POST['minutero']==-1||
        empty($_POST['pais'])||empty($_POST['dpto'])||
        empty($_POST['mpio'])||empty($_POST['zona'])/*||$fechahora==0*/)
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            $this->uno=1;
            $this->IngresaDatosAccidente();
            return true;
        }
        else
        {
	if(empty($_POST['condicion']))
	{
		$_POST['condicion'] = '9';
	}
            $fecha .= $_POST['horario'].':'.$_POST['minutero'].':'.'00';
            $_SESSION['soat']['accidentes']['fecha']=$fecha;
            $_SESSION['soat']['accidentes']['lugaracci']=$_POST['lugaracci'];
            $_SESSION['soat']['accidentes']['pais']=$_POST['pais'];
            $_SESSION['soat']['accidentes']['dpto']=$_POST['dpto'];
            $_SESSION['soat']['accidentes']['mpio']=$_POST['mpio'];
            $_SESSION['soat']['accidentes']['zona']=$_POST['zona'];
            $_SESSION['soat']['accidentes']['informeacci']=$_POST['informeacci'];
            $_SESSION['soat']['accidentes']['condicion']=$_POST['condicion'];
            $_SESSION['soat']['accidentes']['tiponaturaleza']=$_POST['tiponaturaleza'];
            $_SESSION['soat']['accidentes']['traslado']=$_POST['traslado'];
            $_SESSION['soat']['accidentes']['tiposerviciovehiculo']=$_POST['tiposerviciovehiculo'];
            $_SESSION['soat']['accidentes']['tipoambulancia']=$_POST['tipoambulancia'];
            $_SESSION['soat']['accidentes']['intervencion']=$_POST['intervencion'];
            $_SESSION['soat']['accidentes']['epssoat']=$_POST['epssoat'];
            $this->IngresaDatosVehiculo();//Llama la forma que captura los datos del vehiculo
            return true;
        }
    }

    function ValidarDatosVehiculo()//Valida y Guarda los datos del vehoculo en "soat_vehiculos, soat_eventos, soat_poliza esta, si es por primera vez"
    {
  
        $this->uno=0;
        $this->polizamala=0;
        $caso=0;
        if(empty($_POST['asegurado']))
        {
            $this->frmError["MensajeError"]="FALTA LA CLASIFICACIÓN DEL ASEGURADO";
            $this->frmError["asegurado"]=1;
            $this->uno=1;
            $this->IngresaDatosVehiculo();
            return true;
        }
        else if($_POST['asegurado']==1)//Si
        {
          if($_POST['poliza1']==NULL||$_POST['poliza2']==NULL||$_POST['poliza3']==NULL)
            {
                $this->frmError["poliza1"]=1;
            }
            else
            {
                list($dbconn) = GetDBconn();
                $dbconn -> BeginTrans();
                $dbconn -> debug = false;

              $dato_tercero=explode(',',$_POST['aseguradora']);
              $verificacion=$this->BuscarDigitoVerificacion($dato_tercero[1],$dato_tercero[0]);
              $digito=$verificacion['digito_verificacion'];
            if($digito==1)
            {  
                if($this->ValidarPoliza($_POST['poliza2'],$_POST['poliza3'])==false)
                {
                    $_POST['poliza2']='';
                    $_POST['poliza3']='';
                    $this->frmError["poliza1"]=1;
                    $this->polizamala=1;
                }
            }
            else
            {                   
                    $polizasoat=$_POST['poliza1'].'-'.$_POST['poliza2'].'-'.$_POST['poliza3'];
                    $query = "SELECT poliza,
                            vigencia_desde,
                            vigencia_hasta,
                            tipo_id_tercero,
                            tercero_id,
                            sucursal,
                            placa_vehiculo,
                            marca_vehiculo,
                            tipo_vehiculo
                            FROM soat_polizas
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL CONSULTAR LA POLIZA " . $dbconn->ErrorMsg();
                    }
                    while(!$resulta->EOF)
                    {
                        $var=$resulta->GetRowAssoc($ToUpper = false);
                        $resulta->MoveNext();
                    }
                    if(empty($var))
                    {
                        //$this->frmError["MensajeError"]="NO SE ENCONTRo LA POLIZA EN LA BASE DE DATOS";
                        $tipoacci=explode(',',$_POST['aseguradora']);
                        $fechapo=explode('/',$_POST['fechadesde']);
                        $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                        $fechapo=explode('/',$_POST['fechahasta']);
                        $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                        $query = "INSERT INTO soat_polizas
                                (poliza,
                                vigencia_desde,
                                vigencia_hasta,
                                tipo_id_tercero,
                                tercero_id,
                                sucursal,
                                placa_vehiculo,
                                marca_vehiculo,
                                tipo_vehiculo)
                                VALUES
                                ('".$polizasoat."',
                                '".$fechades."',
                                '".$fechahas."',
                                '".$tipoacci[0]."',
                                '".$tipoacci[1]."',
                                '".$_POST['sucursal']."',
                                '".$_POST['placa']."',
                                '".$_POST['marca']."',
                                '".$_POST['tipove']."');";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS " .$dbconn->ErrorMsg();
                            $dbconn->RollBackTrans();
                        }
                    }
                    else
                    {
                        $this->frmError["MensajeError"]="LA POLIZA SE ENCUENTRA EN LA BASE DE DATOS";
                        $_SESSION['soat']['polizaenco']=$var;
                    }
            }
                $dbconn->CommitTrans();
            }
            if(empty($_POST['fechadesde']))
            {
                $this->frmError["fechadesde"]=1;
            }
            else
            {
                $fecdes=explode('/',$_POST['fechadesde']);
                $day=$fecdes[0];
                $mon=$fecdes[1];
                $yea=$fecdes[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechadesde']='';
                    $this->frmError["fechadesde"]=1;
                }
                else
                {
                    $fecd=date ("Y-m-d");
                    if($fecd < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechadesde']='';
                        $this->frmError["fechadesde"]=1;
                    }
                }
            }
            if(empty($_POST['fechahasta']))
            {
                $this->frmError["fechahasta"]=1;
            }
            else
            {
                $fechas=explode('/',$_POST['fechahasta']);
                $day=$fechas[0];
                $mon=$fechas[1];
                $yea=$fechas[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechahasta']='';
                    $this->frmError["fechahasta"]=1;
                }
            }
            if($_POST['aseguradora']==NULL)
            {
                $this->frmError["aseguradora"]=1;
            }
            else
            {
                $asegurador=explode(',',$_POST['aseguradora']);
              
                if($asegurador[2]<>$_POST['poliza1'])
                {
                    $this->frmError["aseguradora"]=1;
                }
            }
	    if($_SESSION['soat']['accidentes']['tiponaturaleza'] == '01')
	    {
		if(empty($_POST['marca']))
		{
			$this->frmError["marca"]=1;
		}
		if(empty($_POST['placa']))
		{
			$this->frmError["placa"]=1;
		}
		if(empty($_POST['tipove']))
		{
			$this->frmError["tipove"]=1;
		}
		if(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop']))
		{
			$seguir=1;
		}
		else
		{
			$seguir=2;
		}
		/*if(empty($_POST['tiposerviciovehiculo']))
		{
			$this->frmError["tiposerviciovehiculo"]=1;
		}*/
    if($_POST['tiposerviciovehiculo']==NULL)
		{
			$this->frmError["tiposerviciovehiculo"]=1;
		}
 	    }

/*           if(empty($_POST['marca'])||empty($_POST['placa'])||
            empty($_POST['tipove'])||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1 || $seguir==1)*/
           if($this->frmError["marca"] == 1||$this->frmError["placa"] == 1||
            $this->frmError["tipove"] == 1||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1 || $seguir==1 || $this->frmError["tiposerviciovehiculo"]==1)
            {
                if($asegurador[2]<>$_POST['poliza1'])
                {
                    $this->frmError["MensajeError"]="LA POLIZA NO CORRRESPONDE A LA ASEGURADORA";
                }
                else if($this->frmError["MensajeError"]==NULL)
                {
                    $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
                }
                $this->uno=1;
                $this->IngresaDatosVehiculo();
                return true;
            }
            else
            {
                $caso=1;
            }
        }
        else if($_POST['asegurado']==4)//poliza falsa
        {
         if($_POST['poliza1']==NULL||$_POST['poliza2']==NULL||$_POST['poliza3']==NULL)
            {
                $this->frmError["poliza1"]=1;
            }
            else
            {
                /*if($this->ValidarPoliza($_POST['poliza2'],$_POST['poliza3'])==false)
                {
                    $_POST['poliza2']='';
                    $_POST['poliza3']='';
                    $this->frmError["poliza1"]=1;
                    $this->polizamala=1;
                }*/
                list($dbconn) = GetDBconn();
                $polizasoat=$_POST['poliza1'].'-'.$_POST['poliza2'].'-'.$_POST['poliza3'];
                $query = "SELECT poliza,
                        vigencia_desde,
                        vigencia_hasta,
                        tipo_id_tercero,
                        tercero_id,
                        sucursal,
                        placa_vehiculo,
                        marca_vehiculo,
                        tipo_vehiculo
                        FROM soat_polizas
                        WHERE poliza='".$polizasoat."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL CONSULTAR LA POLIZA " . $dbconn->ErrorMsg();
                }
                while(!$resulta->EOF)
                {
                    $var=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                }
                if(empty($var))
                {
                    $this->frmError["MensajeError"]="NO SE ENCONTRÓ LA POLIZA EN LA BASE DE DATOS";
                }
                else
                {
                    $this->frmError["MensajeError"]="LA POLIZA SE ENCUENTRA EN LA BASE DE DATOS";
                    $_SESSION['soat']['polizaenco']=$var;
                }
            }
            if(empty($_POST['fechadesde']))
            {
                $this->frmError["fechadesde"]=1;
            }
            else
            {
                $fecdes=explode('/',$_POST['fechadesde']);
                $day=$fecdes[0];
                $mon=$fecdes[1];
                $yea=$fecdes[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechadesde']='';
                    $this->frmError["fechadesde"]=1;
                }
                else
                {
                    $fecd=date ("Y-m-d");
                    if($fecd < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechadesde']='';
                        $this->frmError["fechadesde"]=1;
                    }
                }
            }
            if(empty($_POST['fechahasta']))
            {
                $this->frmError["fechahasta"]=1;
            }
            else
            {
                $fechas=explode('/',$_POST['fechahasta']);
                $day=$fechas[0];
                $mon=$fechas[1];
                $yea=$fechas[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechahasta']='';
                    $this->frmError["fechahasta"]=1;
                }
                else
                {
                    $fech=date ("Y-m-d");
                    if($fech > date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechahasta']='';
                        $this->frmError["fechahasta"]=1;
                    }
                }
            }
            if($_POST['aseguradora']==NULL)
            {
                $this->frmError["aseguradora"]=1;
            }
            /*else
            {
                $asegurador=explode(',',$_POST['aseguradora']);
                if($asegurador[2]<>$_POST['poliza1'])
                {
                    $this->frmError["aseguradora"]=1;
                }
            }*/
	    if($_SESSION['soat']['accidentes']['tiponaturaleza'] == '01')
	    {
		if(empty($_POST['marca']))
		{
			$this->frmError["marca"]=1;
		}
		if(empty($_POST['placa']))
		{
			$this->frmError["placa"]=1;
		}
		if(empty($_POST['tipove']))
		{
			$this->frmError["tipove"]=1;
		}
		if(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop']))
		{
			$seguir=1;
		}
		else
		{
			$seguir=2;
		}
	    }
/*            if(empty($_POST['marca'])||empty($_POST['placa'])||
            empty($_POST['tipove'])||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1 || $seguir==1)*/
            if($this->frmError["marca"] == 1||$this->frmError["placa"]== 1||
            $this->frmError["tipove"] == 1||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1 || $seguir==1)
            {
                if($this->frmError["MensajeError"]==NULL)
                {
                    $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
                }
                $this->uno=1;
                $this->IngresaDatosVehiculo();
                return true;
            }
            else
            {
                $caso=4;
            }
        }
        else if($_POST['asegurado']==5)//poliza vencida
        {
      

           if($_POST['poliza1']==NULL||$_POST['poliza2']==NULL||$_POST['poliza3']==NULL)
            {
                $this->frmError["poliza1"]=1;
            }
            else
            {
            
              $dato_tercero=explode(',',$_POST['aseguradora']);
              $verificacion=$this->BuscarDigitoVerificacion($dato_tercero[1],$dato_tercero[0]);
              $digito=$verificacion['digito_verificacion'];
            if($digito==1)
            {
                if($this->ValidarPoliza($_POST['poliza2'],$_POST['poliza3'])==false)
                {
                    $_POST['poliza2']='';
                    $_POST['poliza3']='';
                    $this->frmError["poliza1"]=1;
                    $this->polizamala=1;
                }
            }
            else
                {
                    list($dbconn) = GetDBconn();
                    $polizasoat=$_POST['poliza1'].'-'.$_POST['poliza2'].'-'.$_POST['poliza3'];
                    $query = "SELECT poliza,
                            vigencia_desde,
                            vigencia_hasta,
                            tipo_id_tercero,
                            tercero_id,
                            sucursal,
                            placa_vehiculo,
                            marca_vehiculo,
                            tipo_vehiculo
                            FROM soat_polizas
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL CONSULTAR LA POLIZA " . $dbconn->ErrorMsg();
                    }
                    while(!$resulta->EOF)
                    {
                        $var=$resulta->GetRowAssoc($ToUpper = false);
                        $resulta->MoveNext();
                    }
                    if(empty($var))
                    {
                        $this->frmError["MensajeError"]="NO SE ENCONTRÓ LA POLIZA EN LA BASE DE DATOS";
                    }
                    else
                    {
                        $this->frmError["MensajeError"]="LA POLIZA SE ENCUENTRA EN LA BASE DE DATOS";
                        $_SESSION['soat']['polizaenco']=$var;
                    }
                }
            }
            if(empty($_POST['fechadesde']))
            {
                $this->frmError["fechadesde"]=1;
            }
            else
            {
                $fecdes=explode('/',$_POST['fechadesde']);
                $day=$fecdes[0];
                $mon=$fecdes[1];
                $yea=$fecdes[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechadesde']='';
                    $this->frmError["fechadesde"]=1;
                }
                else
                {
                    $fecd=date ("Y-m-d");
                    if($fecd < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechadesde']='';
                        $this->frmError["fechadesde"]=1;
                    }
                }
            }
            if(empty($_POST['fechahasta']))
            {
                $this->frmError["fechahasta"]=1;
            }
            else
            {
                $fechas=explode('/',$_POST['fechahasta']);
                $day=$fechas[0];
                $mon=$fechas[1];
                $yea=$fechas[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechahasta']='';
                    $this->frmError["fechahasta"]=1;
                }
                /*else
                {
                    $fech=date ("Y-m-d");
                    if($fech > date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechahasta']='';
                        $this->frmError["fechahasta"]=1;
                    }
                }*/
            }
            if($_POST['aseguradora']==NULL)
            {
                $this->frmError["aseguradora"]=1;
            }
            else
            {
                $asegurador=explode(',',$_POST['aseguradora']);
                if($asegurador[2]<>$_POST['poliza1'])
                {
                    $this->frmError["aseguradora"]=1;
                }
            }
	    
            if($_SESSION['soat']['accidentes']['tiponaturaleza'] == '01')
	    {
		if(empty($_POST['marca']))
		{
			$this->frmError["marca"]=1;
		}
		if(empty($_POST['placa']))
		{
			$this->frmError["placa"]=1;
		}
		if(empty($_POST['tipove']))
		{
			$this->frmError["tipove"]=1;
		}
		if(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop']))
		{
			$seguir=1;
		}
		else
		{
			$seguir=2;
		}
	    }
/*            if(empty($_POST['marca'])||empty($_POST['placa'])||
            empty($_POST['tipove'])||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1 || $seguir==1)*/
            if($this->frmError["marca"] == 1||$this->frmError["placa"]== 1||
            $this->frmError["tipove"] == 1||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1 || $seguir==1)
            {
                if($this->frmError["MensajeError"]==NULL)
                {
                    $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
                }
                $this->uno=1;
                $this->IngresaDatosVehiculo();
                return true;
            }
            else
            {
                $caso=5;
            }
        }
        else if($_POST['asegurado']==2)//No
        {
           if(!empty($_POST['poliza1']) OR !empty($_POST['poliza2']) OR !empty($_POST['poliza3']))
            {
                $_POST['poliza1']='';
                $_POST['poliza2']='';
                $_POST['poliza3']='';
            }
            if($_POST['aseguradora']<>NULL)
            {
                $_POST['aseguradora']='';
            }
            if(!empty($_POST['sucursal']))
            {
                $_POST['sucursal']='';
            }
            if(!empty($_POST['fechadesde']))
            {
                $_POST['fechadesde']='';
            }
            if(!empty($_POST['fechahasta']))
            {
                $_POST['fechahasta']='';
            }
	    
    if($_SESSION['soat']['accidentes']['tiponaturaleza'] == '01')
	    {
		if(empty($_POST['marca']))
		{
			$this->frmError["marca"]=1;
		}
		if(empty($_POST['placa']))
		{
			$this->frmError["placa"]=1;
		}
		if(empty($_POST['tipove']))
		{
			$this->frmError["tipove"]=1;
		}
		if(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop']))
		{
			$seguir=1;
		}
		else
		{
			$seguir=2;
		}
		
		if(empty($_POST['marca'])||empty($_POST['placa'])||
		empty($_POST['tipove'])||$seguir==1)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno=1;
			$this->IngresaDatosVehiculo();
			return true;
		}
		else
		{
			$caso=2;
		}
	   }
        }
        else if($_POST['asegurado']==3)//Fantasma
        {
  
            $caso=3;
        }
        if($caso==1 OR $caso==4 OR $caso==5)
        {
        
            list($dbconn) = GetDBconn();
            $usuario=UserGetUID();
            $dbconn->BeginTrans();
            $this->frmError["MensajeError"]='';
            $polizasoat=$_POST['poliza1'].'-'.$_POST['poliza2'].'-'.$_POST['poliza3'];
            $query = "SELECT poliza
                    FROM soat_polizas
                    WHERE poliza='".$polizasoat."';";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS 1" . $dbconn->ErrorMsg();
                //$dbconn->RollBackTrans();
            }
            if($resulta->EOF)
            {
                $tipoacci=explode(',',$_POST['aseguradora']);
                $fechapo=explode('/',$_POST['fechadesde']);
                $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                $fechapo=explode('/',$_POST['fechahasta']);
                $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                $query = "INSERT INTO soat_polizas
                        (poliza,
                        vigencia_desde,
                        vigencia_hasta,
                        tipo_id_tercero,
                        tercero_id,
                        sucursal,
                        placa_vehiculo,
                        marca_vehiculo,
                        tipo_vehiculo)
                        VALUES
                        ('".$polizasoat."',
                        '".$fechades."',
                        '".$fechahas."',
                        '".$tipoacci[0]."',
                        '".$tipoacci[1]."',
                        '".$_POST['sucursal']."',
                        '".$_POST['placa']."',
                        '".$_POST['marca']."',
                        '".$_POST['tipove']."');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS 2 " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                    $dbconn->RollBackTrans();
                }
            }
            $query = "SELECT NEXTVAL ('soat_accidente_accidente_id_seq');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIÓ UN ERROR EN LA SECUENCIA soat_accidente_accidente_id_seq" . $dbconn->ErrorMsg();
                $dbconn->RollBackTrans();
            }
            $accidentes=$resulta->fields[0];
            $query = "INSERT INTO soat_accidente
                    (accidente_id,
                    fecha_accidente,
                    sitio_accidente,
                    tipo_pais_id,
                    tipo_dpto_id,
                    tipo_mpio_id,
                    zona,
                    informe_accidente,
                    fecha_registro,
                    usuario_id)
                    VALUES
                    (".$accidentes.",
                    '".$_SESSION['soat']['accidentes']['fecha']."',
                    '".$_SESSION['soat']['accidentes']['lugaracci']."',
                    '".$_SESSION['soat']['accidentes']['pais']."',
                    '".$_SESSION['soat']['accidentes']['dpto']."',
                    '".$_SESSION['soat']['accidentes']['mpio']."',
                    '".$_SESSION['soat']['accidentes']['zona']."',
                    '".$_SESSION['soat']['accidentes']['informeacci']."',
                    '".date("Y-m-d H:i:s")."',
                    ".$usuario.");";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS 3" . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                $dbconn->RollBackTrans();
            }
            $query ="SELECT NEXTVAL ('soat_eventos_evento_seq');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIÓ UN ERROR EN LA SECUENCIA soat_eventos_evento_seq" . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                $dbconn->RollBackTrans();
            }
            $eventosoat=$resulta->fields[0];
            $salmin=$this->SaldoEvento($_SESSION['soat']['accidentes']['ano']);
            if($_SESSION['soat']['accidentes']['epssoat']==NULL)
            {
                $epssoat="NULL";
            }
            else
            {
                $epssoat="'".$_SESSION['soat']['accidentes']['epssoat']."'";
            }
            $query = "INSERT INTO soat_eventos
                    (evento,
                    poliza,
                    tipo_id_paciente,
                    paciente_id,
                    condicion_accidentado,
                    saldo,
                    saldo_inicial,
                    accidente_id,
                    asegurado,
                    empresa_id,
                    centro_utilidad,
                    fecha_registro,
                    usuario_id,
                    codigo_eps,
		    soat_naturaleza_evento_id,
		    ambulancia_propia_ips,
		    tipo_ambulancia_id,
		    tipo_servicio_vehiculo_id,
		    intervension_autoridad)
                    VALUES
                    (".$eventosoat.",
                    '".$polizasoat."',
                    '".$_SESSION['soat']['evento']['TipoDocum']."',
                    '".$_SESSION['soat']['evento']['Documento']."',
                    '".$_SESSION['soat']['accidentes']['condicion']."',
                    ".$salmin.",
                    ".$salmin.",
                    ".$accidentes.",
                    '".$_POST['asegurado']."',
                    '".$_SESSION['soa1']['empresa']."',
                    '".$_SESSION['soa1']['centroutil']."',
                    '".date("Y-m-d H:i:s")."',
                    ".$usuario.",
                    $epssoat,
		'".$_SESSION['soat']['accidentes']['tiponaturaleza']."',
		'".$_SESSION['soat']['accidentes']['traslado']."',
		'".$_SESSION['soat']['accidentes']['tipoambulancia']."',
		'".$_POST['tiposerviciovehiculo']."',
		'".$_SESSION['soat']['accidentes']['intervencion']."');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                echo $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS 4 " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                $dbconn->RollBackTrans();
            }
            if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop']))
	    AND $_SESSION['soat']['accidentes']['tiponaturaleza'] == '01')
            {
                $query = "INSERT INTO soat_vehiculo_propietario
                        (evento,
                        apellidos_propietario,
                        nombres_propietario,
                        tipo_id_propietario,
                        propietario_id,
                        extipo_pais_id,
                        extipo_dpto_id,
                        extipo_mpio_id,
                        direccion_propietario,
                        telefono_propietario,
                        tipo_pais_id,
                        tipo_dpto_id,
                        tipo_mpio_id,
                        fecha_registro,
                        usuario_id)
                        VALUES
                        (".$eventosoat.",
                        '".$_POST['apelliprop']."',
                        '".$_POST['nombreprop']."',
                        '".$_POST['tidocuprop']."',
                        '".$_POST['documeprop']."',
                        '".$_POST['paisE']."',
                        '".$_POST['dptoE']."',
                        '".$_POST['mpioE']."',
                        '".$_POST['direccprop']."',
                        '".$_POST['telefoprop']."',
                        '".$_POST['pais']."',
                        '".$_POST['dpto']."',
                        '".$_POST['mpio']."',
                        '".date("Y-m-d H:i:s")."',
                        ".$usuario.");";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    echo $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS 5" . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                    $dbconn->RollBackTrans();
                }
            }
            if(!(empty($_POST['propicondu'])) AND $_POST['propicondu']==1
            AND $_POST['apelliprop']<>NULL AND $_POST['nombreprop']<>NULL
            AND $_POST['documeprop']<>NULL AND $_POST['tidocuprop']<>NULL
	    AND $_SESSION['soat']['accidentes']['tiponaturaleza'] == '01')
            {
              //$this->debug=true;
                $query = "INSERT INTO soat_vehiculo_conductor
                        (evento,
                        apellidos_conductor,
                        nombres_conductor,
                        tipo_id_conductor,
                        conductor_id,
                        extipo_pais_id,
                        extipo_dpto_id,
                        extipo_mpio_id,
                        direccion_conductor,
                        telefono_conductor,
                        tipo_pais_id,
                        tipo_dpto_id,
                        tipo_mpio_id,
                        fecha_registro,
                        usuario_id)
                        VALUES
                        (".$eventosoat.",
                        '".$_POST['apelliprop']."',
                        '".$_POST['nombreprop']."',
                        '".$_POST['tidocuprop']."',
                        '".$_POST['documeprop']."',
                        '".$_POST['paisE']."',
                        '".$_POST['dptoE']."',
                        '".$_POST['mpioE']."',
                        '".$_POST['direccprop']."',
                        '".$_POST['telefoprop']."',
                        '".$_POST['pais']."',
                        '".$_POST['dpto']."',
                        '".$_POST['mpio']."',
                        '".date("Y-m-d H:i:s")."',
                        ".$usuario.");";
                $resulta = $dbconn->Execute($query);
             
                if ($dbconn->ErrorNo() != 0)
                {
                    echo $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS 6" . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                    $dbconn->RollBackTrans();
                }
            }
            $dbconn->CommitTrans();
            if($this->frmError["MensajeError"]==NULL)
            {
                $this->frmError["MensajeError"]="DATOS INSERTADOS CORRECTAMENTE";
            }
            if(!(empty($_POST['propicondu'])) AND $_POST['propicondu']==1
            AND $_POST['apelliprop']<>NULL AND $_POST['nombreprop']<>NULL
            AND $_POST['documeprop']<>NULL AND $_POST['documeprop']<>NULL
	    AND $_SESSION['soat']['accidentes']['tiponaturaleza'] == '01')
            {
                $this->DatosAccidente();
                return true;
            }
            elseif($_SESSION['soat']['accidentes']['tiponaturaleza'] == '01')
            {
                $_SESSION['soat']['eventonews']=$eventosoat;
                $this->uno=1;
                $this->IngresaDatosConductor();
                return true;
            }
	    else
	    {
		if($this->frmError["MensajeError"]==NULL)
		{
		$this->frmError["MensajeError"]="DATOS INSERTADOS CORRECTAMENTE";
		}
		$this->DatosAccidente();
		return true;
	    }
        }
        else if($caso==2)
        {
       
            list($dbconn) = GetDBconn();
            $usuario=UserGetUID();
            $dbconn->BeginTrans();
            $this->frmError["MensajeError"]='';
            $query = "SELECT NEXTVAL ('sota_polizas_fidusalud_seq');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIÓ UN ERROR EN LA SECUENCIA sota_polizas_fidusalud_seq " . $dbconn->ErrorMsg();
                $dbconn->RollBackTrans();
            }
            $polizasoat='0'.'-'.$resulta->fields[0];
            $fechades='NULL';
            $fechahas='NULL';
            $tipoacci[0]=ModuloGetVar('app','Soat','nit_tipo_fidusalud');//NIT
            $tipoacci[1]=ModuloGetVar('app','Soat','nit_id_fidusalud');//'830031511-6'
            $_POST['sucursal']='';
            $query = "INSERT INTO soat_polizas
                    (poliza,
                    vigencia_desde,
                    vigencia_hasta,
                    tipo_id_tercero,
                    tercero_id,
                    sucursal,
                    placa_vehiculo,
                    marca_vehiculo,
                    tipo_vehiculo)
                    VALUES
                    ('".$polizasoat."',
                    ".$fechades.",
                    ".$fechahas.",
                    '".$tipoacci[0]."',
                    '".$tipoacci[1]."',
                    '".$_POST['sucursal']."',
                    '".$_POST['placa']."',
                    '".$_POST['marca']."',
                    '".$_POST['tipove']."');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                echo $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                $dbconn->RollBackTrans();
            }
            $query = "SELECT NEXTVAL ('soat_accidente_accidente_id_seq');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIÓ UN ERROR EN LA SECUENCIA soat_accidente_accidente_id_seq " . $dbconn->ErrorMsg();
                $dbconn->RollBackTrans();
            }
            $accidentes=$resulta->fields[0];
            $query = "INSERT INTO soat_accidente
                    (accidente_id,
                    fecha_accidente,
                    sitio_accidente,
                    tipo_pais_id,
                    tipo_dpto_id,
                    tipo_mpio_id,
                    zona,
                    informe_accidente,
                    fecha_registro,
                    usuario_id)
                    VALUES
                    (".$accidentes.",
                    '".$_SESSION['soat']['accidentes']['fecha']."',
                    '".$_SESSION['soat']['accidentes']['lugaracci']."',
                    '".$_SESSION['soat']['accidentes']['pais']."',
                    '".$_SESSION['soat']['accidentes']['dpto']."',
                    '".$_SESSION['soat']['accidentes']['mpio']."',
                    '".$_SESSION['soat']['accidentes']['zona']."',
                    '".$_SESSION['soat']['accidentes']['informeacci']."',
                    '".date("Y-m-d H:i:s")."',
                    ".$usuario.");";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                echo $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                $dbconn->RollBackTrans();
            }
            $query ="SELECT NEXTVAL ('soat_eventos_evento_seq');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIÓ UN ERROR EN LA SECUENCIA soat_eventos_evento_seq " . $dbconn->ErrorMsg();
                $dbconn->RollBackTrans();
            }
            $eventosoat=$resulta->fields[0];
            $salmin=$this->SaldoEvento($_SESSION['soat']['accidentes']['ano']);
            if($_SESSION['soat']['accidentes']['epssoat']==NULL)
            {
                $epssoat="NULL";
            }
            else
            {
                $epssoat="'".$_SESSION['soat']['accidentes']['epssoat']."'";
            }
            $query = "INSERT INTO soat_eventos
                    (evento,
                    poliza,
                    tipo_id_paciente,
                    paciente_id,
                    condicion_accidentado,
                    saldo,
                    saldo_inicial,
                    accidente_id,
                    asegurado,
                    empresa_id,
                    centro_utilidad,
                    fecha_registro,
                    usuario_id,
                    codigo_eps,
		    soat_naturaleza_evento_id,
		    ambulancia_propia_ips,
		    tipo_ambulancia_id,
		    tipo_servicio_vehiculo_id,
		    intervension_autoridad)
                    VALUES
                    (".$eventosoat.",
                    '".$polizasoat."',
                    '".$_SESSION['soat']['evento']['TipoDocum']."',
                    '".$_SESSION['soat']['evento']['Documento']."',
                    '".$_SESSION['soat']['accidentes']['condicion']."',
                    ".$salmin.",
                    ".$salmin.",
                    ".$accidentes.",
                    '".$_POST['asegurado']."',
                    '".$_SESSION['soa1']['empresa']."',
                    '".$_SESSION['soa1']['centroutil']."',
                    '".date("Y-m-d H:i:s")."',
                    ".$usuario.",
                    $epssoat,
		'".$_SESSION['soat']['accidentes']['tiponaturaleza']."',
		'".$_SESSION['soat']['accidentes']['traslado']."',
		'".$_SESSION['soat']['accidentes']['tipoambulancia']."',
		'".$_POST['tiposerviciovehiculo']."',
		'".$_SESSION['soat']['accidentes']['intervencion']."');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                echo $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                $dbconn->RollBackTrans();
            }
            if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop']))
	    AND $_SESSION['soat']['accidentes']['tiponaturaleza'] == '01')
            {
                $query = "INSERT INTO soat_vehiculo_propietario
                        (evento,
                        apellidos_propietario,
                        nombres_propietario,
                        tipo_id_propietario,
                        propietario_id,
                        extipo_pais_id,
                        extipo_dpto_id,
                        extipo_mpio_id,
                        direccion_propietario,
                        telefono_propietario,
                        tipo_pais_id,
                        tipo_dpto_id,
                        tipo_mpio_id,
                        fecha_registro,
                        usuario_id)
                        VALUES
                        (".$eventosoat.",
                        '".$_POST['apelliprop']."',
                        '".$_POST['nombreprop']."',
                        '".$_POST['tidocuprop']."',
                        '".$_POST['documeprop']."',
                        '".$_POST['paisE']."',
                        '".$_POST['dptoE']."',
                        '".$_POST['mpioE']."',
                        '".$_POST['direccprop']."',
                        '".$_POST['telefoprop']."',
                        '".$_POST['pais']."',
                        '".$_POST['dpto']."',
                        '".$_POST['mpio']."',
                        '".date("Y-m-d H:i:s")."',
                        ".$usuario.");";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    echo $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                    $dbconn->RollBackTrans();
                }
            }
            if(!(empty($_POST['propicondu'])) AND $_POST['propicondu']==1
            AND $_POST['apelliprop']<>NULL AND $_POST['nombreprop']<>NULL
            AND $_POST['documeprop']<>NULL AND $_POST['tidocuprop']<>NULL
	    AND $_SESSION['soat']['accidentes']['tiponaturaleza'] == '01')
            {
                $query = "INSERT INTO soat_vehiculo_conductor
                        (evento,
                        apellidos_conductor,
                        nombres_conductor,
                        tipo_id_conductor,
                        conductor_id,
                        extipo_pais_id,
                        extipo_dpto_id,
                        extipo_mpio_id,
                        direccion_conductor,
                        telefono_conductor,
                        tipo_pais_id,
                        tipo_dpto_id,
                        tipo_mpio_id,
                        fecha_registro,
                        usuario_id)
                        VALUES
                        (".$eventosoat.",
                        '".$_POST['apelliprop']."',
                        '".$_POST['nombreprop']."',
                        '".$_POST['tidocuprop']."',
                        '".$_POST['documeprop']."',
                        '".$_POST['paisE']."',
                        '".$_POST['dptoE']."',
                        '".$_POST['mpioE']."',
                        '".$_POST['direccprop']."',
                        '".$_POST['telefoprop']."',
                        '".$_POST['pais']."',
                        '".$_POST['dpto']."',
                        '".$_POST['mpio']."',
                        '".date("Y-m-d H:i:s")."',
                        ".$usuario.");";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    echo $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                    $dbconn->RollBackTrans();
                }
            }
            $dbconn->CommitTrans();
            if($this->frmError["MensajeError"]==NULL)
            {
                $this->frmError["MensajeError"]="DATOS INSERTADOS CORRECTAMENTE";
            }
            if(!(empty($_POST['propicondu'])) AND $_POST['propicondu']==1
            AND $_POST['apelliprop']<>NULL AND $_POST['nombreprop']<>NULL
            AND $_POST['documeprop']<>NULL AND $_POST['tidocuprop']<>NULL
	    AND $_SESSION['soat']['accidentes']['tiponaturaleza'] == '01')
            {
                $this->DatosAccidente();
                return true;
            }
            elseif($_SESSION['soat']['accidentes']['tiponaturaleza'] == '01')
            {
                $_SESSION['soat']['eventonews']=$eventosoat;
                $this->uno=1;
                $this->IngresaDatosConductor();
                return true;
            }
        }
        else if($caso==3)
        {
      
            list($dbconn) = GetDBconn();
            $usuario=UserGetUID();
            $dbconn->BeginTrans();
            $this->frmError["MensajeError"]='';
            $query = "SELECT NEXTVAL ('sota_polizas_fidusalud_seq');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIÓ UN ERROR EN LA SECUENCIA sota_polizas_fidusalud_seq " . $dbconn->ErrorMsg();
                $dbconn->RollBackTrans();
            }
            $polizasoat='0'.'-'.$resulta->fields[0];
            $fechades='NULL';
            $fechahas='NULL';
            $tipoacci[0]=ModuloGetVar('app','Soat','nit_tipo_fidusalud');//NIT
            $tipoacci[1]=ModuloGetVar('app','Soat','nit_id_fidusalud');//'830031511-6'
            $_POST['sucursal']='';
            $query = "INSERT INTO soat_polizas
                    (poliza,
                    vigencia_desde,
                    vigencia_hasta,
                    tipo_id_tercero,
                    tercero_id,
                    sucursal,
                    placa_vehiculo,
                    marca_vehiculo,
                    tipo_vehiculo)
                    VALUES
                    ('".$polizasoat."',
                    ".$fechades.",
                    ".$fechahas.",
                    '".$tipoacci[0]."',
                    '".$tipoacci[1]."',
                    '".$_POST['sucursal']."',
                    '".$_POST['placa']."',
                    '".$_POST['marca']."',
                    '".$_POST['tipove']."');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                echo $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                $dbconn->RollBackTrans();
            }
            $query = "SELECT NEXTVAL ('soat_accidente_accidente_id_seq');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIÓ UN ERROR EN LA SECUENCIA soat_accidente_accidente_id_seq " . $dbconn->ErrorMsg();
                $dbconn->RollBackTrans();
            }
            $accidentes=$resulta->fields[0];
            $query = "INSERT INTO soat_accidente
                    (accidente_id,
                    fecha_accidente,
                    sitio_accidente,
                    tipo_pais_id,
                    tipo_dpto_id,
                    tipo_mpio_id,
                    zona,
                    informe_accidente,
                    fecha_registro,
                    usuario_id)
                    VALUES
                    (".$accidentes.",
                    '".$_SESSION['soat']['accidentes']['fecha']."',
                    '".$_SESSION['soat']['accidentes']['lugaracci']."',
                    '".$_SESSION['soat']['accidentes']['pais']."',
                    '".$_SESSION['soat']['accidentes']['dpto']."',
                    '".$_SESSION['soat']['accidentes']['mpio']."',
                    '".$_SESSION['soat']['accidentes']['zona']."',
                    '".$_SESSION['soat']['accidentes']['informeacci']."',
                    '".date("Y-m-d H:i:s")."',
                    ".$usuario.");";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                echo $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                $dbconn->RollBackTrans();
            }
            $query ="SELECT NEXTVAL ('soat_eventos_evento_seq');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIÓ UN ERROR EN LA SECUENCIA soat_eventos_evento_seq " . $dbconn->ErrorMsg();
                $dbconn->RollBackTrans();
            }
            $eventosoat=$resulta->fields[0];
            $salmin=$this->SaldoEvento($_SESSION['soat']['accidentes']['ano']);
            if($_SESSION['soat']['accidentes']['epssoat']==NULL)
            {
                $epssoat="NULL";
            }
            else
            {
                $epssoat="'".$_SESSION['soat']['accidentes']['epssoat']."'";
            }
            $query = "INSERT INTO soat_eventos
                    (evento,
                    poliza,
                    tipo_id_paciente,
                    paciente_id,
                    condicion_accidentado,
                    saldo,
                    saldo_inicial,
                    accidente_id,
                    asegurado,
                    empresa_id,
                    centro_utilidad,
                    fecha_registro,
                    usuario_id,
                    codigo_eps,
		    soat_naturaleza_evento_id,
		    ambulancia_propia_ips,
		    tipo_ambulancia_id,
		    tipo_servicio_vehiculo_id,
		    intervension_autoridad)
                    VALUES
                    (".$eventosoat.",
                    '".$polizasoat."',
                    '".$_SESSION['soat']['evento']['TipoDocum']."',
                    '".$_SESSION['soat']['evento']['Documento']."',
                    '".$_SESSION['soat']['accidentes']['condicion']."',
                    ".$salmin.",
                    ".$salmin.",
                    ".$accidentes.",
                    '".$_POST['asegurado']."',
                    '".$_SESSION['soa1']['empresa']."',
                    '".$_SESSION['soa1']['centroutil']."',
                    '".date("Y-m-d H:i:s")."',
                    ".$usuario.",
                    $epssoat,
		'".$_SESSION['soat']['accidentes']['tiponaturaleza']."',
		'".$_SESSION['soat']['accidentes']['traslado']."',
		'".$_SESSION['soat']['accidentes']['tipoambulancia']."',
		'".$_POST['tiposerviciovehiculo']."',
		'".$_SESSION['soat']['accidentes']['intervencion']."');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                echo $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS " . $dbconn->ErrorMsg().'['.get_class($this).']['.__LINE__.']';
                $dbconn->RollBackTrans();
            }
            $dbconn->CommitTrans();
            if($this->frmError["MensajeError"]==NULL)
            {
                $this->frmError["MensajeError"]="DATOS INSERTADOS CORRECTAMENTE";
            }
            $this->DatosAccidente();
            return true;
        }
    }

    function ValidarPoliza($poliza,$valida)//Valida si la poliza es autentica o no
    {
        if(!($poliza==NULL) AND !($valida==NULL))
        {
            if(is_numeric($poliza)==0||is_numeric($valida)==0)
            {
                return false;
            }
            else
            {
                $poli=intval($poliza);
                $veri=intval($valida);
                //if($poli<99999)
                if(strlen($poliza) < 6)
                {
                    return false;
                }
                else
                {
                    $result=$poli/7;
                    $result=intval($result)*7;
                    $result=($poli-$result);
                    if($result==$veri)
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
        }
        else
        {
            return false;
        }
        //return true;
    }

    function ValidarDatosConductor()//Volida y guarda los datos del conductor del vehiculo
    {
        $this->uno=0;
        if($_POST['apellicond']==NULL)
        {
            $this->frmError["apellicond"]=1;
        }
        if($_POST['nombrecond']==NULL)
        {
            $this->frmError["nombrecond"]=1;
        }
        if($_POST['tidocucond']==NULL)
        {
            $this->frmError["tidocucond"]=1;
        }
        if($_POST['documecond']==NULL)
        {
            $this->frmError["documecond"]=1;
        }
        if($_POST['direcicond']==NULL)
        {
            $this->frmError["direcicond"]=1;
        }
        if(is_numeric($_POST['telefocond'])==0)
        {
            $this->frmError["telefocond"]=1;
            $_POST['telefocond']='';
        }
        if($_POST['apellicond']==NULL||$_POST['nombrecond']==NULL||
        $_POST['tidocucond']==NULL||$_POST['documecond']==NULL||
        $_POST['direcicond']==NULL||$_POST['telefocond']==NULL)
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            $this->uno=1;
            $this->IngresaDatosConductor();
            return true;
        }
        else
        {
            list($dbconn) = GetDBconn();
            $usuario=UserGetUID();
            $query = "INSERT INTO soat_vehiculo_conductor
                    (evento,
                    apellidos_conductor,
                    nombres_conductor,
                    tipo_id_conductor,
                    conductor_id,
                    extipo_pais_id,
                    extipo_dpto_id,
                    extipo_mpio_id,
                    direccion_conductor,
                    telefono_conductor,
                    tipo_pais_id,
                    tipo_dpto_id,
                    tipo_mpio_id,
                    fecha_registro,
                    usuario_id)
                    VALUES
                    (".$_SESSION['soat']['eventonews'].",
                    '".$_POST['apellicond']."',
                    '".$_POST['nombrecond']."',
                    '".$_POST['tidocucond']."',
                    '".$_POST['documecond']."',
                    '".$_POST['paisE']."',
                    '".$_POST['dptoE']."',
                    '".$_POST['mpioE']."',
                    '".$_POST['direcicond']."',
                    '".$_POST['telefocond']."',
                    '".$_POST['pais']."',
                    '".$_POST['dpto']."',
                    '".$_POST['mpio']."',
                    '".date("Y-m-d H:i:s")."',
                    ".$usuario.");";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS DEL CONDUCTOR";
            }
            else
            {
                $this->frmError["MensajeError"]="DATOS INSERTADOS CORRECTAMENTE";
            }
            $this->DatosAccidente();
            return true;
        }
    }
    /**
    * Funcion que valida y guarda las modificaciones del accidente
    *
    * @return mixed
    */
    function ValidarGuardarAcci()
    {   
        $this->uno=0;
        if(empty($_POST['condicionM']) AND $_POST[tiponaturaleza]=='01')
        {
            $this->frmError["condicionM"]=1;
        }
        if(empty($_POST['fechaM']))
        {
            $this->frmError["fechaM"]=1;
        }
        else
        {
            $valfec=explode('/',$_POST['fechaM']);
            $day=$valfec[0];
            $mon=$valfec[1];
            $yea=$valfec[2];
            if(checkdate($mon, $day, $yea)==0)
            {
                $_POST['fechaM']='';
                $this->frmError["fechaM"]=1;
            }
            else
            {
                $fec=date ("Y-m-d");
                if($fec < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                {
                    $_POST['fechaM']='';
                    $this->frmError["fechaM"]=1;
                }
                else
                {
                    $fecha = $yea.'-'.$mon.'-'.$day.' ';
                    $fechahora=1;
                }
            }
        }
        if($_POST['horarioM']==-1||$_POST['minuteroM']==-1)
        {
            $this->frmError["horarioM"]=1;
        }
        else
        {
            if($fechahora==1)
            {
                $horac=intval(date("H"));
                $minac=intval(date("i"));
                $hor=intval($_POST['horarioM']);
                $min=intval($_POST['minuteroM']);
                if($horac<$hor AND $fec <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                {
                    $_POST['horarioM']=-1;
                    $this->frmError["horarioM"]=1;
                }
                else
                {
                    if($minac<$min AND $horac<=$hor AND $fec <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['minuteroM']=-1;
                        $this->frmError["horarioM"]=1;
                    }
                }
            }
        }
        if(empty($_POST['pais']))
        {
            $this->frmError["pais"]=1;
        }
        if(empty($_POST['dpto']))
        {
            $this->frmError["dpto"]=1;
        }
        if(empty($_POST['mpio']))
        {
            $this->frmError["mpio"]=1;
        }
        if(empty($_POST['zonaM']))
        {
            $this->frmError["zonaM"]=1;
        }
        //CAMBIO DAR
        if(empty($_POST['epssoatM']))
        {       
            $this->frmError["epssoatM"]=1;
        }
          
        if($this->frmError["condicionM"] == 1 ||empty($_POST['fechaM'])||
        $_POST['horarioM']==-1||$_POST['minuteroM']==-1||
        empty($_POST['pais'])||empty($_POST['dpto'])||
        empty($_POST['mpio'])||empty($_POST['zonaM'])OR empty($_POST['epssoatM']))
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            $this->uno=1;
            $this->ModificarDatosEventoAcc();
            return true;
        }
        else
        {
            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();
            $this->frmError["MensajeError"]='';
            $fecha .= $_POST['horarioM'].':'.$_POST['minuteroM'].':'.'00';
	    
            if(empty($_POST['condicionM']))
            {
              $_POST['condicionM'] = '9';//SIN CONDICION DE ACCIDENTE - EVENTO Q NO ES DE TRANSITO
            }

            if($_POST['tratamiento']=='Observacion')
            {
                $tratamiento='0';
            }elseif($_POST['tratamiento']=='Hospitalario')
            {
                $tratamiento='1';
            }elseif($_POST['tratamiento']=='Ambulatorio')
            {
                $tratamiento='2';
            }
            if(!empty($_SESSION['soat']['acciverM']))
            {
                $query = "UPDATE soat_accidente SET
                        fecha_accidente='".$fecha."',
                        sitio_accidente='".$_POST['lugaracciM']."',
                        tipo_pais_id='".$_POST['pais']."',
                        tipo_dpto_id='".$_POST['dpto']."',
                        tipo_mpio_id='".$_POST['mpio']."',
                        zona='".$_POST['zonaM']."',
                        informe_accidente='".$_POST['informeacciM']."',
                        tipo_tratamiento='".$tratamiento."'
                        WHERE accidente_id=".$_SESSION['soat']['acciverM'].";";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                if(empty($_POST['epssoatM']))
                {
                    $_POST['epssoatM']="NULL";
                    $query = "UPDATE  soat_eventos SET
                                      condicion_accidentado='".$_POST['condicionM']."',
                                      codigo_eps=".$_POST['epssoatM'].",
                            			    ambulancia_propia_ips = '".$_POST[traslado]."',
                            			    tipo_ambulancia_id = '".$_POST[tipoambulancia]."',
                            			    soat_naturaleza_evento_id = '".$_POST[tiponaturaleza]."',
                            			    intervension_autoridad = '".$_POST[intervencion]."'
                              WHERE   evento=".$_SESSION['soat']['eventoelegMA'].";";
                }
                else
                {
                    $query = "UPDATE  soat_eventos SET
                                      condicion_accidentado='".$_POST['condicionM']."',
                                      codigo_eps='".$_POST['epssoatM']."',
                            			    ambulancia_propia_ips = '".$_POST[traslado]."',
                            			    tipo_ambulancia_id = '".$_POST[tipoambulancia]."',
                            			    soat_naturaleza_evento_id = '".$_POST[tiponaturaleza]."',
                            			    intervension_autoridad = '".$_POST[intervencion]."'
                              WHERE   evento=".$_SESSION['soat']['eventoelegMA'].";";
                }
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
            }
            else
            {
              
                $query = "SELECT NEXTVAL ('soat_accidente_accidente_id_seq');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                $indice=$resulta->fields[0];
                $usuario=UserGetUID();
                $query = "INSERT INTO soat_accidente
                            (accidente_id,
                            fecha_accidente,
                            sitio_accidente,
                            tipo_pais_id,
                            tipo_dpto_id,
                            tipo_mpio_id,
                            zona,
                            informe_accidente,
                            fecha_registro,
                            usuario_id,
                            tipo_tratamiento)
                            VALUES
                            (".$indice.",
                            '".$fecha."',
                            '".$_POST['lugaracciM']."',
                            '".$_POST['pais']."',
                            '".$_POST['dpto']."',
                            '".$_POST['mpio']."',
                            '".$_POST['zonaM']."',
                            '".$_POST['informeacciM']."',
                            '".date("Y-m-d H:i:s")."',
                            ".$usuario.",
                            '".$tratamiento."');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                if(empty($_POST['epssoatM']))
                {
                    $_POST['epssoatM']="NULL";
                    $query = "UPDATE  soat_eventos SET
                                      accidente_id=".$indice.",
                                      condicion_accidentado='".$_POST['condicionM']."',
                                      codigo_eps='".$_POST['epssoatM']."',
                                  		ambulancia_propia_ips = '".$_POST[traslado]."',
                            			    tipo_ambulancia_id = '".$_POST[tipoambulancia]."',
                            			    soat_naturaleza_evento_id = '".$_POST[tiponaturaleza]."',
                            			    intervension_autoridad = '".$_POST[intervencion]."'
                              WHERE   evento=".$_SESSION['soat']['eventoelegMA'].";";
                }
                else
                {
                    $query = "UPDATE  soat_eventos SET
                                      accidente_id=".$indice.",
                                      condicion_accidentado='".$_POST['condicionM']."',
                                      codigo_eps='".$_POST['epssoatM']."',
                            			    ambulancia_propia_ips = '".$_POST[traslado]."',
                            			    tipo_ambulancia_id = '".$_POST[tipoambulancia]."',
                            			    soat_naturaleza_evento_id = '".$_POST[tiponaturaleza]."',
                            			    intervension_autoridad = '".$_POST[intervencion]."'
                              WHERE   evento=".$_SESSION['soat']['eventoelegMA'].";";
                }
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
            }
            $dbconn->CommitTrans();
            if($this->frmError["MensajeError"]==NULL)
            {
                $this->frmError["MensajeError"]="DATOS MODIFICADOS CORRECTAMENTE";
            }
            $this->salida  = "<script>\n";
            $this->salida .= "	location.href = \"".ModuloGetURL('app','Soat','user','DatosAccidente',array("mensaje"=>$this->frmError["MensajeError"]))."\"\n";
            $this->salida .= "</script>\n";
            //$this->DatosAccidente();
            return true;
        }
        return true;
    }

    function ValidarModificarEventoPropiVeh()//Funcion que vaida y guarda las modificaciones del vehiculo y la poliza
    {

        $this->uno=0;
        $this->polizamala=0;
        $caso=0;
        if(empty($_POST['asegurado']))
        {
            $this->frmError["MensajeError"]="FALTA LA CLASIFICACIÓN DEL ASEGURADO";
            $this->frmError["asegurado"]=1;
            $this->uno=1;
            $this->ModificarEventoPropiVeh();
            return true;
        }
        else if($_POST['asegurado']==1)//Si
        {
            if($_POST['poliza1']==NULL||$_POST['poliza2']==NULL||$_POST['poliza3']==NULL)
            {
                $this->frmError["poliza1"]=1;
            }
            else
            {
              $dato_tercero=explode(',',$_POST['aseguradora']);
              $verificacion=$this->BuscarDigitoVerificacion($dato_tercero[1],$dato_tercero[0]);
              $digito=$verificacion['digito_verificacion'];
              
              if($digito==1)
              {  
                if($this->ValidarPoliza($_POST['poliza2'],$_POST['poliza3'])==false)
                {
                    $_POST['poliza2']='';
                    $_POST['poliza3']='';
                    $this->frmError["poliza1"]=1;
                    $this->polizamala=1;
                }
              }
            }
            if(empty($_POST['fechadesde']))
            {
                $this->frmError["fechadesde"]=1;
            }
            else
            {
                $fecdes=explode('/',$_POST['fechadesde']);
                $day=$fecdes[0];
                $mon=$fecdes[1];
                $yea=$fecdes[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechadesde']='';
                    $this->frmError["fechadesde"]=1;
                }
                else
                {
                    $fecd=date ("Y-m-d");
                    if($fecd < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechadesde']='';
                        $this->frmError["fechadesde"]=1;
                    }
                }
            }
            if(empty($_POST['fechahasta']))
            {
                $this->frmError["fechahasta"]=1;
            }
            else
            {
                $fechas=explode('/',$_POST['fechahasta']);
                $day=$fechas[0];
                $mon=$fechas[1];
                $yea=$fechas[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechahasta']='';
                    $this->frmError["fechahasta"]=1;
                }
/*                else
                {
                    $fech=date ("Y-m-d");
                    if($fech > date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechahasta']='';
                        $this->frmError["fechahasta"]=1;
                    }
                }*/
            }
            if($_POST['aseguradora']==NULL)
            {
                $this->frmError["aseguradora"]=1;
            }
            else
            {
                $asegurador=explode(',',$_POST['aseguradora']);
                if($asegurador[2]<>$_POST['poliza1'])
                {
                    $this->frmError["aseguradora"]=1;
                }
            }
            if(empty($_POST['marca']))
            {
                $this->frmError["marca"]=1;
            }
            if(empty($_POST['placa']))
            {
                $this->frmError["placa"]=1;
            }
            if(empty($_POST['tipove']))
            {
                $this->frmError["tipove"]=1;
            }
            if(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop']))
            {
                $seguir=1;
            }
            else
            {
                $seguir=2;
            }
            if(empty($_POST['marca'])||empty($_POST['placa'])||
            empty($_POST['tipove'])||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1||$seguir==1)
            {
                if($asegurador[2]<>$_POST['poliza1'])
                {
                    $this->frmError["MensajeError"]="LA POLIZA NO CORRRESPONDE A LA ASEGURADORA";
                }
                else
                {
                    $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
                }
                $this->uno=1;
                $this->ModificarEventoPropiVeh();
                return true;
            }
            else
            {
                $caso=1;
            }
        }
        else if($_POST['asegurado']==4)//poliza falsa
        {
            if($_POST['poliza1']==NULL||$_POST['poliza2']==NULL||$_POST['poliza3']==NULL)
            {
                $this->frmError["poliza1"]=1;
            }
            /*else
            {
                if($this->ValidarPoliza($_POST['poliza2'],$_POST['poliza3'])==false)
                {
                    $_POST['poliza2']='';
                    $_POST['poliza3']='';
                    $this->frmError["poliza1"]=1;
                    $this->polizamala=1;
                }
            }*/
            if(empty($_POST['fechadesde']))
            {
                $this->frmError["fechadesde"]=1;
            }
            else
            {
                $fecdes=explode('/',$_POST['fechadesde']);
                $day=$fecdes[0];
                $mon=$fecdes[1];
                $yea=$fecdes[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechadesde']='';
                    $this->frmError["fechadesde"]=1;
                }
                else
                {
                    $fecd=date ("Y-m-d");
                    if($fecd < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechadesde']='';
                        $this->frmError["fechadesde"]=1;
                    }
                }
            }
            if(empty($_POST['fechahasta']))
            {
                $this->frmError["fechahasta"]=1;
            }
            else
            {
                $fechas=explode('/',$_POST['fechahasta']);
                $day=$fechas[0];
                $mon=$fechas[1];
                $yea=$fechas[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechahasta']='';
                    $this->frmError["fechahasta"]=1;
                }
                //BUG xxxx37
/*              else
                {
                    $fech=date ("Y-m-d");
                    if($fech > date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechahasta']='';
                        $this->frmError["fechahasta"]=1;
                    }
                }*/
            }
            if($_POST['aseguradora']==NULL)
            {
                $this->frmError["aseguradora"]=1;
            }
            /*else
            {
                $asegurador=explode(',',$_POST['aseguradora']);
                if($asegurador[2]<>$_POST['poliza1'])
                {
                    $this->frmError["aseguradora"]=1;
                }
            }*/
            if(empty($_POST['marca']))
            {
                $this->frmError["marca"]=1;
            }
            if(empty($_POST['placa']))
            {
                $this->frmError["placa"]=1;
            }
            if(empty($_POST['tipove']))
            {
                $this->frmError["tipove"]=1;
            }
            if(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop']))
            {
                $seguir=1;
            }
            else
            {
                $seguir=2;
            }
            if(empty($_POST['marca'])||empty($_POST['placa'])||
            empty($_POST['tipove'])||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1||$seguir==1)
            {
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
                $this->uno=1;
                $this->ModificarEventoPropiVeh();
                return true;
            }
            else
            {
                $caso=4;
            }
        }
        else if($_POST['asegurado']==5)//poliza vencida
        {
            if($_POST['poliza1']==NULL||$_POST['poliza2']==NULL||$_POST['poliza3']==NULL)
            {
                $this->frmError["poliza1"]=1;
            }
            else
            {
              $dato_tercero=explode(',',$_POST['aseguradora']);
              $verificacion=$this->BuscarDigitoVerificacion($dato_tercero[1],$dato_tercero[0]);
              $digito=$verificacion['digito_verificacion'];
               if($digito==1)
              {  
                if($this->ValidarPoliza($_POST['poliza2'],$_POST['poliza3'])==false)
                {
                    $_POST['poliza2']='';
                    $_POST['poliza3']='';
                    $this->frmError["poliza1"]=1;
                    $this->polizamala=1;
                }
              }
            }
            if(empty($_POST['fechadesde']))
            {
                $this->frmError["fechadesde"]=1;
            }
            else
            {
                $fecdes=explode('/',$_POST['fechadesde']);
                $day=$fecdes[0];
                $mon=$fecdes[1];
                $yea=$fecdes[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechadesde']='';
                    $this->frmError["fechadesde"]=1;
                }
                else
                {
                    $fecd=date ("Y-m-d");
                    if($fecd < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechadesde']='';
                        $this->frmError["fechadesde"]=1;
                    }
                }
            }
            if(empty($_POST['fechahasta']))
            {
                $this->frmError["fechahasta"]=1;
            }
            else
            {
                $fechas=explode('/',$_POST['fechahasta']);
                $day=$fechas[0];
                $mon=$fechas[1];
                $yea=$fechas[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechahasta']='';
                    $this->frmError["fechahasta"]=1;
                }
                /*else
                {
                    $fech=date ("Y-m-d");
                    if($fech > date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechahasta']='';
                        $this->frmError["fechahasta"]=1;
                    }
                }*/
            }
            if($_POST['aseguradora']==NULL)
            {
                $this->frmError["aseguradora"]=1;
            }
            else
            {
                $asegurador=explode(',',$_POST['aseguradora']);
                if($asegurador[2]<>$_POST['poliza1'])
                {
                    $this->frmError["aseguradora"]=1;
                }
            }
            if(empty($_POST['marca']))
            {
                $this->frmError["marca"]=1;
            }
            if(empty($_POST['placa']))
            {
                $this->frmError["placa"]=1;
            }
            if(empty($_POST['tipove']))
            {
                $this->frmError["tipove"]=1;
            }
            if(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop']))
            {
                $seguir=1;
            }
            else
            {
                $seguir=2;
            }
            if(empty($_POST['marca'])||empty($_POST['placa'])||
            empty($_POST['tipove'])||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1||$seguir==1)
            {
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
                $this->uno=1;
                $this->ModificarEventoPropiVeh();
                return true;
            }
            else
            {
                $caso=5;
            }
        }
        else if($_POST['asegurado']==2)//No
        {
            if(!empty($_POST['poliza1']) OR !empty($_POST['poliza2']) OR !empty($_POST['poliza3']))
            {
                $_POST['poliza1']='';
                $_POST['poliza2']='';
                $_POST['poliza3']='';
            }
            if($_POST['aseguradora']<>NULL)
            {
                $_POST['aseguradora']='';
            }
            if(!empty($_POST['sucursal']))
            {
                $_POST['sucursal']='';
            }
            if(!empty($_POST['fechadesde']))
            {
                $_POST['fechadesde']='';
            }
            if(!empty($_POST['fechahasta']))
            {
                $_POST['fechahasta']='';
            }
            if(empty($_POST['marca']))
            {
                $this->frmError["marca"]=1;
            }
            if(empty($_POST['placa']))
            {
                $this->frmError["placa"]=1;
            }
            if(empty($_POST['tipove']))
            {
                $this->frmError["tipove"]=1;
            }
            if(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop']))
            {
                $seguir=1;
            }
            else
            {
                $seguir=2;
            }
            if(empty($_POST['marca'])||empty($_POST['placa'])||
            empty($_POST['tipove'])||$seguir==1)
            {
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
                $this->uno=1;
                $this->ModificarEventoPropiVeh();
                return true;
            }
            else
            {
                $caso=2;
            }
        }
        else if($_POST['asegurado']==3)//Fantasma
        {
            $caso=3;
        }
        $polizasoat=$_POST['poliza1'].'-'.$_POST['poliza2'].'-'.$_POST['poliza3'];
        if($caso==1 OR $caso==2 OR $caso==3 OR $caso==4 OR $caso==5)
        {
            list($dbconn) = GetDBconn();
            $usuario=UserGetUID();
            $dbconn->BeginTrans();
            $this->frmError["MensajeError"]='';
            if($_SESSION['soat']['asegverM'] == 1 AND $caso == 1)//SI y SI
            {
            //Modifico vehiculo, no Llave; Modifico poliza, si Llave (validar segon caso)
            //Mismo vehiculo, misma poliza; Diferente vehiculo, diferente poliza
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIO UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $tipoacci=explode(',',$_POST['aseguradora']);
                $fechapo=explode('/',$_POST['fechadesde']);
                $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                $fechapo=explode('/',$_POST['fechahasta']);
                $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                if($_SESSION['soat']['poliverM']==$polizasoat)
                {
                    $query = "UPDATE soat_polizas SET
                            vigencia_desde='".$fechades."',
                            vigencia_hasta='".$fechahas."',
                            tipo_id_tercero='".$tipoacci[0]."',
                            tercero_id='".$tipoacci[1]."',
                            sucursal='".$_POST['sucursal']."',
                            placa_vehiculo='".$_POST['placa']."',
                            marca_vehiculo='".$_POST['marca']."',
                            tipo_vehiculo='".$_POST['tipove']."'
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                else
                {
                    $query = "SELECT poliza
                            FROM soat_polizas
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    if($resulta->EOF)
                    {
                        $query = "INSERT INTO soat_polizas
                                (poliza,
                                vigencia_desde,
                                vigencia_hasta,
                                tipo_id_tercero,
                                tercero_id,
                                sucursal,
                                placa_vehiculo,
                                marca_vehiculo,
                                tipo_vehiculo)
                                VALUES
                                ('".$polizasoat."',
                                '".$fechades."',
                                '".$fechahas."',
                                '".$tipoacci[0]."',
                                '".$tipoacci[1]."',
                                '".$_POST['sucursal']."',
                                '".$_POST['placa']."',
                                '".$_POST['marca']."',
                                '".$_POST['tipove']."');";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
/*                        $query = "UPDATE soat_eventos SET
                                poliza='".$polizasoat."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }*/
                    }
										$query = "UPDATE soat_eventos SET
														poliza='".$polizasoat."'
														WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
										$resulta = $dbconn->Execute($query);
										if($dbconn->ErrorNo() != 0)
										{
												$this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
												$dbconn->RollBackTrans();
										}
                    //aqui elimino poliza sin relación
                    //$query = "DELETE FROM soat_polizas
                    //      WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                    //$resulta = $dbconn->Execute($query);
                }
		$query = "UPDATE soat_eventos SET
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
			WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			echo $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS".$dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
			$dbconn->RollBackTrans();
		}
            }
            else if($_SESSION['soat']['asegverM'] == 1 AND $caso == 2)//SI y NO
            {

            //Modifico vehiculo, no Llave; Modifico poliza, si Llave (secuencia a fidusalud)
            //Mismo vehiculo, cambio la poliza por la secuencia
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $query = "SELECT NEXTVAL ('sota_polizas_fidusalud_seq');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                $polizasoat='0'.'-'.$resulta->fields[0];
                $fechades='NULL';
                $fechahas='NULL';
                $tipoacci[0]=ModuloGetVar('app','Soat','nit_tipo_fidusalud');//NIT
                $tipoacci[1]=ModuloGetVar('app','Soat','nit_id_fidusalud');//'830031511-6'
                $_POST['sucursal']='';
                $query = "INSERT INTO soat_polizas
                        (poliza,
                        vigencia_desde,
                        vigencia_hasta,
                        tipo_id_tercero,
                        tercero_id,
                        sucursal,
                        placa_vehiculo,
                        marca_vehiculo,
                        tipo_vehiculo)
                        VALUES
                        ('".$polizasoat."',
                        ".$fechades.",
                        ".$fechahas.",
                        '".$tipoacci[0]."',
                        '".$tipoacci[1]."',
                        '".$_POST['sucursal']."',
                        '".$_POST['placa']."',
                        '".$_POST['marca']."',
                        '".$_POST['tipove']."');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                //aqui elimino poliza sin relación
                //$query = "DELETE FROM soat_polizas
                //      WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                //$resulta = $dbconn->Execute($query);
            }
            /*else if($_SESSION['soat']['asegverM'] == 1 AND $caso == 3)//SI y Fant
            {
            //Elimino vehiculo, si Llave; Modifico poliza, si Llave (secuencia a fidusalud)
            //Opcion no volida
            }*/
            else if($_SESSION['soat']['asegverM'] == 1 AND $caso == 4)//SI y P. FALSA
            {
            //Modifico vehiculo, no Llave; Modifico poliza, si Llave (validar segon caso)
            //Mismo vehiculo, cambio la poliza
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $tipoacci=explode(',',$_POST['aseguradora']);
                $fechapo=explode('/',$_POST['fechadesde']);
                $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                $fechapo=explode('/',$_POST['fechahasta']);
                $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                if($_SESSION['soat']['poliverM']==$polizasoat)
                {
                    $query = "UPDATE soat_polizas SET
                            vigencia_desde='".$fechades."',
                            vigencia_hasta='".$fechahas."',
                            tipo_id_tercero='".$tipoacci[0]."',
                            tercero_id='".$tipoacci[1]."',
                            sucursal='".$_POST['sucursal']."',
                            placa_vehiculo='".$_POST['placa']."',
                            marca_vehiculo='".$_POST['marca']."',
                            tipo_vehiculo='".$_POST['tipove']."'
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                else
                {
                    $query = "SELECT poliza
                            FROM soat_polizas
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    if($resulta->EOF)
                    {
                        $query = "INSERT INTO soat_polizas
                                (poliza,
                                vigencia_desde,
                                vigencia_hasta,
                                tipo_id_tercero,
                                tercero_id,
                                sucursal,
                                placa_vehiculo,
                                marca_vehiculo,
                                tipo_vehiculo)
                                VALUES
                                ('".$polizasoat."',
                                '".$fechades."',
                                '".$fechahas."',
                                '".$tipoacci[0]."',
                                '".$tipoacci[1]."',
                                '".$_POST['sucursal']."',
                                '".$_POST['placa']."',
                                '".$_POST['marca']."',
                                '".$_POST['tipove']."');";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                //aqui elimino poliza sin relacion
                //$query = "DELETE FROM soat_polizas
                //      WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                //$resulta = $dbconn->Execute($query);
            }
            else if($_SESSION['soat']['asegverM'] == 1 AND $caso == 5)//SI y P. VENCIDA
            {
            //Modifico vehiculo, no Llave; Modifico poliza, si Llave (validar segon caso)
            //Mismo vehiculo, cambio la poliza
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $tipoacci=explode(',',$_POST['aseguradora']);
                $fechapo=explode('/',$_POST['fechadesde']);
                $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                $fechapo=explode('/',$_POST['fechahasta']);
                $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                if($_SESSION['soat']['poliverM']==$polizasoat)
                {
                    $query = "UPDATE soat_polizas SET
                            vigencia_desde='".$fechades."',
                            vigencia_hasta='".$fechahas."',
                            tipo_id_tercero='".$tipoacci[0]."',
                            tercero_id='".$tipoacci[1]."',
                            sucursal='".$_POST['sucursal']."',
                            placa_vehiculo='".$_POST['placa']."',
                            marca_vehiculo='".$_POST['marca']."',
                            tipo_vehiculo='".$_POST['tipove']."'
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                else
                {
                    $query = "SELECT poliza
                            FROM soat_polizas
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    if($resulta->EOF)
                    {
                        $query = "INSERT INTO soat_polizas
                                (poliza,
                                vigencia_desde,
                                vigencia_hasta,
                                tipo_id_tercero,
                                tercero_id,
                                sucursal,
                                placa_vehiculo,
                                marca_vehiculo,
                                tipo_vehiculo)
                                VALUES
                                ('".$polizasoat."',
                                '".$fechades."',
                                '".$fechahas."',
                                '".$tipoacci[0]."',
                                '".$tipoacci[1]."',
                                '".$_POST['sucursal']."',
                                '".$_POST['placa']."',
                                '".$_POST['marca']."',
                                '".$_POST['tipove']."');";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                //aqui elimino poliza sin relacion
                //$query = "DELETE FROM soat_polizas
                //      WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                //$resulta = $dbconn->Execute($query);
            }
            else if($_SESSION['soat']['asegverM'] == 2 AND $caso == 1)//NO y SI
            {
            //Modifico vehiculo, no Llave; Modifico poliza, si Llave (validar segon caso)
            //Mismo vehiculo, cambio la secuencia por la poliza
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $query = "SELECT poliza
                        FROM soat_polizas
                        WHERE poliza='".$polizasoat."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                if($resulta->EOF)
                {
                    $tipoacci=explode(',',$_POST['aseguradora']);
                    $fechapo=explode('/',$_POST['fechadesde']);
                    $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                    $fechapo=explode('/',$_POST['fechahasta']);
                    $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                    $query = "INSERT INTO soat_polizas
                            (poliza,
                            vigencia_desde,
                            vigencia_hasta,
                            tipo_id_tercero,
                            tercero_id,
                            sucursal,
                            placa_vehiculo,
                            marca_vehiculo,
                            tipo_vehiculo)
                            VALUES
                            ('".$polizasoat."',
                            '".$fechades."',
                            '".$fechahas."',
                            '".$tipoacci[0]."',
                            '".$tipoacci[1]."',
                            '".$_POST['sucursal']."',
                            '".$_POST['placa']."',
                            '".$_POST['marca']."',
                            '".$_POST['tipove']."');";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                //aqui elimino poliza sin relacin
                $query = "DELETE FROM soat_polizas
                        WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
            }
            else if($_SESSION['soat']['asegverM'] == 2 AND $caso == 2)//NO y NO
            {

      //Modifico vehiculo, no Llave; NO Modifico poliza
            //Diferente o error en el vehiculo, lo sigue cubriendo difusalud
                //if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                //{
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                       $query .= "UPDATE soat_polizas 
				SET placa_vehiculo='".$_POST['placa']."',
					marca_vehiculo='".$_POST['marca']."',
					tipo_vehiculo='".$_POST['tipove']."'
				WHERE poliza=(SELECT A.poliza
				FROM soat_polizas A, soat_eventos B
				WHERE B.evento=".$_SESSION['soat']['eventoelegMVP']."
				AND A.poliza=B.poliza);";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                //}
            }
            /*else if($_SESSION['soat']['asegverM'] == 2 AND $caso == 3)//NO y Fant
            {
            //Elimino vehiculo, si Llave; NO Modifico poliza
            //Opcion no volida
            }*/
            else if($_SESSION['soat']['asegverM'] == 2 AND $caso == 4)//NO y P. FALSA
            {
            //Modifico vehiculo, no Llave; Modifico poliza, si Llave (validar segon caso)
            //Mismo vehiculo, cambio la secuencia por la poliza falsa (cambia la validacion)
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $query = "SELECT poliza
                        FROM soat_polizas
                        WHERE poliza='".$polizasoat."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                if($resulta->EOF)
                {
                    $tipoacci=explode(',',$_POST['aseguradora']);
                    $fechapo=explode('/',$_POST['fechadesde']);
                    $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                    $fechapo=explode('/',$_POST['fechahasta']);
                    $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                    $query = "INSERT INTO soat_polizas
                            (poliza,
                            vigencia_desde,
                            vigencia_hasta,
                            tipo_id_tercero,
                            tercero_id,
                            sucursal,
                            placa_vehiculo,
                            marca_vehiculo,
                            tipo_vehiculo)
                            VALUES
                            ('".$polizasoat."',
                            '".$fechades."',
                            '".$fechahas."',
                            '".$tipoacci[0]."',
                            '".$tipoacci[1]."',
                            '".$_POST['sucursal']."',
                            '".$_POST['placa']."',
                            '".$_POST['marca']."',
                            '".$_POST['tipove']."');";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                //aqui elimino poliza sin relación
                $query = "DELETE FROM soat_polizas
                        WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
            }
            else if($_SESSION['soat']['asegverM'] == 2 AND $caso == 5)//No y P. VENCIDA
            {
            //Modifico vehiculo, no Llave; Modifico poliza, si Llave (validar segon caso)
            //Mismo vehiculo, cambio la secuencia por la poliza falsa (cambia la validacion)
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $query = "SELECT poliza
                        FROM soat_polizas
                        WHERE poliza='".$polizasoat."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                if($resulta->EOF)
                {
                    $tipoacci=explode(',',$_POST['aseguradora']);
                    $fechapo=explode('/',$_POST['fechadesde']);
                    $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                    $fechapo=explode('/',$_POST['fechahasta']);
                    $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                    $query = "INSERT INTO soat_polizas
                            (poliza,
                            vigencia_desde,
                            vigencia_hasta,
                            tipo_id_tercero,
                            tercero_id,
                            sucursal,
                            placa_vehiculo,
                            marca_vehiculo,
                            tipo_vehiculo)
                            VALUES
                            ('".$polizasoat."',
                            '".$fechades."',
                            '".$fechahas."',
                            '".$tipoacci[0]."',
                            '".$tipoacci[1]."',
                            '".$_POST['sucursal']."',
                            '".$_POST['placa']."',
                            '".$_POST['marca']."',
                            '".$_POST['tipove']."');";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                //aqui elimino poliza sin relación
                $query = "DELETE FROM soat_polizas
                        WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
            }
            else if($_SESSION['soat']['asegverM'] == 3 AND $caso == 1)//Fant y SI
            {
            //Creo vehiculo, si Llave; Modifico poliza, si Llave (validar segon caso)
            //Vehiculo insertado, modifica el evento y la poliza
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    $query = "INSERT INTO soat_vehiculo_propietario
                            (evento,
                            apellidos_propietario,
                            nombres_propietario,
                            tipo_id_propietario,
                            propietario_id,
                            extipo_pais_id,
                            extipo_dpto_id,
                            extipo_mpio_id,
                            direccion_propietario,
                            telefono_propietario,
                            tipo_pais_id,
                            tipo_dpto_id,
                            tipo_mpio_id,
                            fecha_registro,
                            usuario_id)
                            VALUES
                            (".$_SESSION['soat']['eventoelegMVP'].",
                            '".$_POST['apelliprop']."',
                            '".$_POST['nombreprop']."',
                            '".$_POST['tidocuprop']."',
                            '".$_POST['documeprop']."',
                            '".$_POST['paisE']."',
                            '".$_POST['dptoE']."',
                            '".$_POST['mpioE']."',
                            '".$_POST['direccprop']."',
                            '".$_POST['telefoprop']."',
                            '".$_POST['pais']."',
                            '".$_POST['dpto']."',
                            '".$_POST['mpio']."',
                            '".date("Y-m-d H:i:s")."',
                            ".$usuario.");";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                $query = "SELECT poliza
                        FROM soat_polizas
                        WHERE poliza='".$polizasoat."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                if($resulta->EOF)
                {
                    $tipoacci=explode(',',$_POST['aseguradora']);
                    $fechapo=explode('/',$_POST['fechadesde']);
                    $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                    $fechapo=explode('/',$_POST['fechahasta']);
                    $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                    $query = "INSERT INTO soat_polizas
                            (poliza,
                            vigencia_desde,
                            vigencia_hasta,
                            tipo_id_tercero,
                            tercero_id,
                            sucursal,
                            placa_vehiculo,
                            marca_vehiculo,
                            tipo_vehiculo)
                            VALUES
                            ('".$polizasoat."',
                            '".$fechades."',
                            '".$fechahas."',
                            '".$tipoacci[0]."',
                            '".$tipoacci[1]."',
                            '".$_POST['sucursal']."',
                            '".$_POST['placa']."',
                            '".$_POST['marca']."',
                            '".$_POST['tipove']."');";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                //aqui elimino poliza sin relacion
                $query = "DELETE FROM soat_polizas
                        WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
            }
            else if($_SESSION['soat']['asegverM'] == 3 AND $caso == 2)//Fant y NO
            {
            //Creo vehiculo, si Llave; NO Modifico poliza
            //Vehiculo insertado, modifica el evento
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    $query = "INSERT INTO soat_vehiculo_propietario
                            (evento,
                            apellidos_propietario,
                            nombres_propietario,
                            tipo_id_propietario,
                            propietario_id,
                            extipo_pais_id,
                            extipo_dpto_id,
                            extipo_mpio_id,
                            direccion_propietario,
                            telefono_propietario,
                            tipo_pais_id,
                            tipo_dpto_id,
                            tipo_mpio_id,
                            fecha_registro,
                            usuario_id)
                            VALUES
                            (".$_SESSION['soat']['eventoelegMVP'].",
                            '".$_POST['apelliprop']."',
                            '".$_POST['nombreprop']."',
                            '".$_POST['tidocuprop']."',
                            '".$_POST['documeprop']."',
                            '".$_POST['paisE']."',
                            '".$_POST['dptoE']."',
                            '".$_POST['mpioE']."',
                            '".$_POST['direccprop']."',
                            '".$_POST['telefoprop']."',
                            '".$_POST['pais']."',
                            '".$_POST['dpto']."',
                            '".$_POST['mpio']."',
                            '".date("Y-m-d H:i:s")."',
                            ".$usuario.");";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                $query = "UPDATE soat_eventos SET
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = ".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
            }
            /*else if($_SESSION['soat']['asegverM'] == 3 AND $caso == 3)//Fant y Fant
            {
            //No modifico nada
            //Opcion no volida
            }*/
            else if($_SESSION['soat']['asegverM'] == 3 AND $caso == 4)//Fant y P. FALSA
            {
            //Creo vehiculo, si Llave; Modifico poliza, si Llave (validar segon caso)
            //Vehiculo insertado, modifica el evento y la poliza (cambia la validacion)
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    $query = "INSERT INTO soat_vehiculo_propietario
                            (evento,
                            apellidos_propietario,
                            nombres_propietario,
                            tipo_id_propietario,
                            propietario_id,
                            extipo_pais_id,
                            extipo_dpto_id,
                            extipo_mpio_id,
                            direccion_propietario,
                            telefono_propietario,
                            tipo_pais_id,
                            tipo_dpto_id,
                            tipo_mpio_id,
                            fecha_registro,
                            usuario_id)
                            VALUES
                            (".$_SESSION['soat']['eventoelegMVP'].",
                            '".$_POST['apelliprop']."',
                            '".$_POST['nombreprop']."',
                            '".$_POST['tidocuprop']."',
                            '".$_POST['documeprop']."',
                            '".$_POST['paisE']."',
                            '".$_POST['dptoE']."',
                            '".$_POST['mpioE']."',
                            '".$_POST['direccprop']."',
                            '".$_POST['telefoprop']."',
                            '".$_POST['pais']."',
                            '".$_POST['dpto']."',
                            '".$_POST['mpio']."',
                            '".date("Y-m-d H:i:s")."',
                            ".$usuario.");";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                $query = "SELECT poliza
                        FROM soat_polizas
                        WHERE poliza='".$polizasoat."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                if($resulta->EOF)
                {
                    $tipoacci=explode(',',$_POST['aseguradora']);
                    $fechapo=explode('/',$_POST['fechadesde']);
                    $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                    $fechapo=explode('/',$_POST['fechahasta']);
                    $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                    $query = "INSERT INTO soat_polizas
                            (poliza,
                            vigencia_desde,
                            vigencia_hasta,
                            tipo_id_tercero,
                            tercero_id,
                            sucursal,
                            placa_vehiculo,
                            marca_vehiculo,
                            tipo_vehiculo)
                            VALUES
                            ('".$polizasoat."',
                            '".$fechades."',
                            '".$fechahas."',
                            '".$tipoacci[0]."',
                            '".$tipoacci[1]."',
                            '".$_POST['sucursal']."',
                            '".$_POST['placa']."',
                            '".$_POST['marca']."',
                            '".$_POST['tipove']."');";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                //aqui elimino poliza sin relacion
                $query = "DELETE FROM soat_polizas
                        WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
            }
            else if($_SESSION['soat']['asegverM'] == 3 AND $caso == 5)//Fant y P. VENCIDA
            {
            //Creo vehiculo, si Llave; Modifico poliza, si Llave (validar segon caso)
            //Vehiculo insertado, modifica el evento y la poliza (cambia la validacion)
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    $query = "INSERT INTO soat_vehiculo_propietario
                            (evento,
                            apellidos_propietario,
                            nombres_propietario,
                            tipo_id_propietario,
                            propietario_id,
                            extipo_pais_id,
                            extipo_dpto_id,
                            extipo_mpio_id,
                            direccion_propietario,
                            telefono_propietario,
                            tipo_pais_id,
                            tipo_dpto_id,
                            tipo_mpio_id,
                            fecha_registro,
                            usuario_id)
                            VALUES
                            (".$_SESSION['soat']['eventoelegMVP'].",
                            '".$_POST['apelliprop']."',
                            '".$_POST['nombreprop']."',
                            '".$_POST['tidocuprop']."',
                            '".$_POST['documeprop']."',
                            '".$_POST['paisE']."',
                            '".$_POST['dptoE']."',
                            '".$_POST['mpioE']."',
                            '".$_POST['direccprop']."',
                            '".$_POST['telefoprop']."',
                            '".$_POST['pais']."',
                            '".$_POST['dpto']."',
                            '".$_POST['mpio']."',
                            '".date("Y-m-d H:i:s")."',
                            ".$usuario.");";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                $query = "SELECT poliza
                        FROM soat_polizas
                        WHERE poliza='".$polizasoat."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                if($resulta->EOF)
                {
                    $tipoacci=explode(',',$_POST['aseguradora']);
                    $fechapo=explode('/',$_POST['fechadesde']);
                    $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                    $fechapo=explode('/',$_POST['fechahasta']);
                    $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                    $query = "INSERT INTO soat_polizas
                            (poliza,
                            vigencia_desde,
                            vigencia_hasta,
                            tipo_id_tercero,
                            tercero_id,
                            sucursal,
                            placa_vehiculo,
                            marca_vehiculo,
                            tipo_vehiculo)
                            VALUES
                            ('".$polizasoat."',
                            '".$fechades."',
                            '".$fechahas."',
                            '".$tipoacci[0]."',
                            '".$tipoacci[1]."',
                            '".$_POST['sucursal']."',
                            '".$_POST['placa']."',
                            '".$_POST['marca']."',
                            '".$_POST['tipove']."');";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                //aqui elimino poliza sin relacion
                $query = "DELETE FROM soat_polizas
                        WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
            }
            else if($_SESSION['soat']['asegverM'] == 4 AND $caso == 1)//P. FALSA Y SI
            {
            //Modifico vehiculo, si Llave; Modifico poliza, si Llave (validar segon caso)
            //Vehiculo insertado, modifica el evento y la poliza (cambia la validacion)
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $tipoacci=explode(',',$_POST['aseguradora']);
                $fechapo=explode('/',$_POST['fechadesde']);
                $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                $fechapo=explode('/',$_POST['fechahasta']);
                $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                if($_SESSION['soat']['poliverM']==$polizasoat)
                {
                    $query = "UPDATE soat_polizas SET
                            vigencia_desde='".$fechades."',
                            vigencia_hasta='".$fechahas."',
                            tipo_id_tercero='".$tipoacci[0]."',
                            tercero_id='".$tipoacci[1]."',
                            sucursal='".$_POST['sucursal']."',
                            placa_vehiculo='".$_POST['placa']."',
                            marca_vehiculo='".$_POST['marca']."',
                            tipo_vehiculo='".$_POST['tipove']."'
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                else
                {
                    $query = "SELECT poliza
                            FROM soat_polizas
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    if($resulta->EOF)
                    {
                        $query = "INSERT INTO soat_polizas
                                (poliza,
                                vigencia_desde,
                                vigencia_hasta,
                                tipo_id_tercero,
                                tercero_id,
                                sucursal,
                                placa_vehiculo,
                                marca_vehiculo,
                                tipo_vehiculo)
                                VALUES
                                ('".$polizasoat."',
                                '".$fechades."',
                                '".$fechahas."',
                                '".$tipoacci[0]."',
                                '".$tipoacci[1]."',
                                '".$_POST['sucursal']."',
                                '".$_POST['placa']."',
                                '".$_POST['marca']."',
                                '".$_POST['tipove']."');";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
            }
            else if($_SESSION['soat']['asegverM'] == 4 AND $caso == 2)//P. FALSA Y NO
            {
            //Modifico vehiculo, si Llave; Modifico poliza, si Llave (validar segon caso)
            //Vehiculo insertado, modifica el evento y la poliza (cambia la validacion)
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $query = "SELECT NEXTVAL ('sota_polizas_fidusalud_seq');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                $polizasoat='0'.'-'.$resulta->fields[0];
                $fechades='NULL';
                $fechahas='NULL';
                $tipoacci[0]=ModuloGetVar('app','Soat','nit_tipo_fidusalud');//NIT
                $tipoacci[1]=ModuloGetVar('app','Soat','nit_id_fidusalud');//'830031511-6'
                $_POST['sucursal']='';
                $query = "INSERT INTO soat_polizas
                        (poliza,
                        vigencia_desde,
                        vigencia_hasta,
                        tipo_id_tercero,
                        tercero_id,
                        sucursal,
                        placa_vehiculo,
                        marca_vehiculo,
                        tipo_vehiculo)
                        VALUES
                        ('".$polizasoat."',
                        ".$fechades.",
                        ".$fechahas.",
                        '".$tipoacci[0]."',
                        '".$tipoacci[1]."',
                        '".$_POST['sucursal']."',
                        '".$_POST['placa']."',
                        '".$_POST['marca']."',
                        '".$_POST['tipove']."');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                //aqui elimino poliza sin relacion
                //$query = "DELETE FROM soat_polizas
                //      WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                //$resulta = $dbconn->Execute($query);
            }/*aqui*/
            /*else if($_SESSION['soat']['asegverM'] == 4 AND $caso == 3)//P. FALSA y Fant
            {
            //No modifico nada
            //Opcion no volida
            }*/
            else if($_SESSION['soat']['asegverM'] == 4 AND $caso == 4)//P. FALSA y P FALSA
            {
            //Modifico vehiculo, no Llave; Modifico poliza, si Llave (validar segon caso)
            //Mismo vehiculo, misma poliza; (cambia la validacion)
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $tipoacci=explode(',',$_POST['aseguradora']);
                $fechapo=explode('/',$_POST['fechadesde']);
                $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                $fechapo=explode('/',$_POST['fechahasta']);
                $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                if($_SESSION['soat']['poliverM']==$polizasoat)
                {
                    $query = "UPDATE soat_polizas SET
                            vigencia_desde='".$fechades."',
                            vigencia_hasta='".$fechahas."',
                            tipo_id_tercero='".$tipoacci[0]."',
                            tercero_id='".$tipoacci[1]."',
                            sucursal='".$_POST['sucursal']."',
                            placa_vehiculo='".$_POST['placa']."',
                            marca_vehiculo='".$_POST['marca']."',
                            tipo_vehiculo='".$_POST['tipove']."'
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                else
                {
                    $query = "SELECT poliza
                            FROM soat_polizas
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    if($resulta->EOF)
                    {
                        $query = "INSERT INTO soat_polizas
                                (poliza,
                                vigencia_desde,
                                vigencia_hasta,
                                tipo_id_tercero,
                                tercero_id,
                                sucursal,
                                placa_vehiculo,
                                marca_vehiculo,
                                tipo_vehiculo)
                                VALUES
                                ('".$polizasoat."',
                                '".$fechades."',
                                '".$fechahas."',
                                '".$tipoacci[0]."',
                                '".$tipoacci[1]."',
                                '".$_POST['sucursal']."',
                                '".$_POST['placa']."',
                                '".$_POST['marca']."',
                                '".$_POST['tipove']."');";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                        $query = "UPDATE soat_eventos SET
                                poliza='".$polizasoat."',
				tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    //aqui elimino poliza sin relacion
                    //$query = "DELETE FROM soat_polizas
                    //      WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                    //$resulta = $dbconn->Execute($query);
                }
            }
            else if($_SESSION['soat']['asegverM'] == 4 AND $caso == 5)//P. FALSA y P VENCIDA
            {
            //Modifico vehiculo, no Llave; Modifico poliza, si Llave (validar segon caso)
            //Mismo vehiculo, misma poliza; (cambia la validacion)
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $tipoacci=explode(',',$_POST['aseguradora']);
                $fechapo=explode('/',$_POST['fechadesde']);
                $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                $fechapo=explode('/',$_POST['fechahasta']);
                $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                if($_SESSION['soat']['poliverM']==$polizasoat)
                {
                    $query = "UPDATE soat_polizas SET
                            vigencia_desde='".$fechades."',
                            vigencia_hasta='".$fechahas."',
                            tipo_id_tercero='".$tipoacci[0]."',
                            tercero_id='".$tipoacci[1]."',
                            sucursal='".$_POST['sucursal']."',
                            placa_vehiculo='".$_POST['placa']."',
                            marca_vehiculo='".$_POST['marca']."',
                            tipo_vehiculo='".$_POST['tipove']."'
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                else
                {
                    $query = "SELECT poliza
                            FROM soat_polizas
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    if($resulta->EOF)
                    {
                        $query = "INSERT INTO soat_polizas
                                (poliza,
                                vigencia_desde,
                                vigencia_hasta,
                                tipo_id_tercero,
                                tercero_id,
                                sucursal,
                                placa_vehiculo,
                                marca_vehiculo,
                                tipo_vehiculo)
                                VALUES
                                ('".$polizasoat."',
                                '".$fechades."',
                                '".$fechahas."',
                                '".$tipoacci[0]."',
                                '".$tipoacci[1]."',
                                '".$_POST['sucursal']."',
                                '".$_POST['placa']."',
                                '".$_POST['marca']."',
                                '".$_POST['tipove']."');";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                //aqui elimino poliza sin relacion
                //$query = "DELETE FROM soat_polizas
                //      WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                //$resulta = $dbconn->Execute($query);
            }
            else if($_SESSION['soat']['asegverM'] == 5 AND $caso == 1)//P. VENCIDA Y SI
            {
            //Modifico vehiculo, si Llave; Modifico poliza, si Llave (validar segon caso)
            //Vehiculo insertado, modifica el evento y la poliza (cambia la validacion)
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $tipoacci=explode(',',$_POST['aseguradora']);
                $fechapo=explode('/',$_POST['fechadesde']);
                $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                $fechapo=explode('/',$_POST['fechahasta']);
                $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                if($_SESSION['soat']['poliverM']==$polizasoat)
                {
                    $query = "UPDATE soat_polizas SET
                            vigencia_desde='".$fechades."',
                            vigencia_hasta='".$fechahas."',
                            tipo_id_tercero='".$tipoacci[0]."',
                            tercero_id='".$tipoacci[1]."',
                            sucursal='".$_POST['sucursal']."',
                            placa_vehiculo='".$_POST['placa']."',
                            marca_vehiculo='".$_POST['marca']."',
                            tipo_vehiculo='".$_POST['tipove']."'
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                else
                {
                    $query = "SELECT poliza
                            FROM soat_polizas
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    if($resulta->EOF)
                    {
                        $query = "INSERT INTO soat_polizas
                                (poliza,
                                vigencia_desde,
                                vigencia_hasta,
                                tipo_id_tercero,
                                tercero_id,
                                sucursal,
                                placa_vehiculo,
                                marca_vehiculo,
                                tipo_vehiculo)
                                VALUES
                                ('".$polizasoat."',
                                '".$fechades."',
                                '".$fechahas."',
                                '".$tipoacci[0]."',
                                '".$tipoacci[1]."',
                                '".$_POST['sucursal']."',
                                '".$_POST['placa']."',
                                '".$_POST['marca']."',
                                '".$_POST['tipove']."');";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
            }
            else if($_SESSION['soat']['asegverM'] == 5 AND $caso == 2)//P. VENCIDA Y NO
            {
            //Modifico vehiculo, si Llave; Modifico poliza, si Llave (validar segon caso)
            //Vehiculo insertado, modifica el evento y la poliza (cambia la validacion)
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $query = "SELECT NEXTVAL ('sota_polizas_fidusalud_seq');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                $polizasoat='0'.'-'.$resulta->fields[0];
                $fechades='NULL';
                $fechahas='NULL';
                $tipoacci[0]=ModuloGetVar('app','Soat','nit_tipo_fidusalud');//NIT
                $tipoacci[1]=ModuloGetVar('app','Soat','nit_id_fidusalud');//'830031511-6'
                $_POST['sucursal']='';
                $query = "INSERT INTO soat_polizas
                        (poliza,
                        vigencia_desde,
                        vigencia_hasta,
                        tipo_id_tercero,
                        tercero_id,
                        sucursal,
                        placa_vehiculo,
                        marca_vehiculo,
                        tipo_vehiculo)
                        VALUES
                        ('".$polizasoat."',
                        ".$fechades.",
                        ".$fechahas.",
                        '".$tipoacci[0]."',
                        '".$tipoacci[1]."',
                        '".$_POST['sucursal']."',
                        '".$_POST['placa']."',
                        '".$_POST['marca']."',
                        '".$_POST['tipove']."');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                //aqui elimino poliza sin relacion
                //$query = "DELETE FROM soat_polizas
                //      WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                //$resulta = $dbconn->Execute($query);
            }
            /*else if($_SESSION['soat']['asegverM'] == 5 AND $caso == 3)//P. VENCIDA y Fant
            {
            //No modifico nada
            //Opcion no volida
            }*/
            else if($_SESSION['soat']['asegverM'] == 5 AND $caso == 4)//P. VENCIDA y P FALSA
            {
            //Modifico vehiculo, no Llave; Modifico poliza, si Llave (validar segon caso)
            //Mismo vehiculo, misma poliza; (cambia la validacion)
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $tipoacci=explode(',',$_POST['aseguradora']);
                $fechapo=explode('/',$_POST['fechadesde']);
                $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                $fechapo=explode('/',$_POST['fechahasta']);
                $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                if($_SESSION['soat']['poliverM']==$polizasoat)
                {
                    $query = "UPDATE soat_polizas SET
                            vigencia_desde='".$fechades."',
                            vigencia_hasta='".$fechahas."',
                            tipo_id_tercero='".$tipoacci[0]."',
                            tercero_id='".$tipoacci[1]."',
                            sucursal='".$_POST['sucursal']."',
                            placa_vehiculo='".$_POST['placa']."',
                            marca_vehiculo='".$_POST['marca']."',
                            tipo_vehiculo='".$_POST['tipove']."'
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                else
                {
                    $query = "SELECT poliza
                            FROM soat_polizas
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    if($resulta->EOF)
                    {
                        $query = "INSERT INTO soat_polizas
                                (poliza,
                                vigencia_desde,
                                vigencia_hasta,
                                tipo_id_tercero,
                                tercero_id,
                                sucursal,
                                placa_vehiculo,
                                marca_vehiculo,
                                tipo_vehiculo)
                                VALUES
                                ('".$polizasoat."',
                                '".$fechades."',
                                '".$fechahas."',
                                '".$tipoacci[0]."',
                                '".$tipoacci[1]."',
                                '".$_POST['sucursal']."',
                                '".$_POST['placa']."',
                                '".$_POST['marca']."',
                                '".$_POST['tipove']."');";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $query = "UPDATE soat_eventos SET
                        poliza='".$polizasoat."',
                        asegurado='".$caso."',
			tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                //aqui elimino poliza sin relación
                //$query = "DELETE FROM soat_polizas
                //      WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                //$resulta = $dbconn->Execute($query);
            }
            else if($_SESSION['soat']['asegverM'] == 5 AND $caso == 5)//P. VENCIDA y P VENCIDA
            {
            //Modifico vehiculo, no Llave; Modifico poliza, si Llave (validar segon caso)
            //Mismo vehiculo, misma poliza; (cambia la validacion)
                if(!(empty($_POST['apelliprop']) AND empty($_POST['nombreprop']) AND empty($_POST['documeprop'])))
                {
                    if($_SESSION['soat']['guarmodico']==1)
                    {
                        $query = "UPDATE soat_vehiculo_propietario SET
                                apellidos_propietario='".$_POST['apelliprop']."',
                                nombres_propietario='".$_POST['nombreprop']."',
                                tipo_id_propietario='".$_POST['tidocuprop']."',
                                propietario_id='".$_POST['documeprop']."',
                                extipo_pais_id='".$_POST['paisE']."',
                                extipo_dpto_id='".$_POST['dptoE']."',
                                extipo_mpio_id='".$_POST['mpioE']."',
                                direccion_propietario='".$_POST['direccprop']."',
                                telefono_propietario='".$_POST['telefoprop']."',
                                tipo_pais_id='".$_POST['pais']."',
                                tipo_dpto_id='".$_POST['dpto']."',
                                tipo_mpio_id='".$_POST['mpio']."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                    else if($_SESSION['soat']['guarmodico']==0)
                    {
                        $query = "INSERT INTO soat_vehiculo_propietario
                                (evento,
                                apellidos_propietario,
                                nombres_propietario,
                                tipo_id_propietario,
                                propietario_id,
                                extipo_pais_id,
                                extipo_dpto_id,
                                extipo_mpio_id,
                                direccion_propietario,
                                telefono_propietario,
                                tipo_pais_id,
                                tipo_dpto_id,
                                tipo_mpio_id,
                                fecha_registro,
                                usuario_id)
                                VALUES
                                (".$_SESSION['soat']['eventoelegMVP'].",
                                '".$_POST['apelliprop']."',
                                '".$_POST['nombreprop']."',
                                '".$_POST['tidocuprop']."',
                                '".$_POST['documeprop']."',
                                '".$_POST['paisE']."',
                                '".$_POST['dptoE']."',
                                '".$_POST['mpioE']."',
                                '".$_POST['direccprop']."',
                                '".$_POST['telefoprop']."',
                                '".$_POST['pais']."',
                                '".$_POST['dpto']."',
                                '".$_POST['mpio']."',
                                '".date("Y-m-d H:i:s")."',
                                ".$usuario.");";
                        $resulta = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                $tipoacci=explode(',',$_POST['aseguradora']);
                $fechapo=explode('/',$_POST['fechadesde']);
                $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                $fechapo=explode('/',$_POST['fechahasta']);
                $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                if($_SESSION['soat']['poliverM']==$polizasoat)
                {
                    $query = "UPDATE soat_polizas SET
                            vigencia_desde='".$fechades."',
                            vigencia_hasta='".$fechahas."',
                            tipo_id_tercero='".$tipoacci[0]."',
                            tercero_id='".$tipoacci[1]."',
                            sucursal='".$_POST['sucursal']."',
                            placa_vehiculo='".$_POST['placa']."',
                            marca_vehiculo='".$_POST['marca']."',
                            tipo_vehiculo='".$_POST['tipove']."'
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                }
                else
                {
                    $query = "SELECT poliza
                            FROM soat_polizas
                            WHERE poliza='".$polizasoat."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                        $dbconn->RollBackTrans();
                    }
                    if($resulta->EOF)
                    {
                        $query = "INSERT INTO soat_polizas
                                (poliza,
                                vigencia_desde,
                                vigencia_hasta,
                                tipo_id_tercero,
                                tercero_id,
                                sucursal,
                                placa_vehiculo,
                                marca_vehiculo,
                                tipo_vehiculo)
                                VALUES
                                ('".$polizasoat."',
                                '".$fechades."',
                                '".$fechahas."',
                                '".$tipoacci[0]."',
                                '".$tipoacci[1]."',
                                '".$_POST['sucursal']."',
                                '".$_POST['placa']."',
                                '".$_POST['marca']."',
                                '".$_POST['tipove']."');";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                        $query = "UPDATE soat_eventos SET
                                poliza='".$polizasoat."',
				tipo_servicio_vehiculo_id = '".$_POST[tiposerviciovehiculo]."'
                                WHERE evento=".$_SESSION['soat']['eventoelegMVP'].";";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                            $dbconn->RollBackTrans();
                        }
                    }
                }
                //aqui elimino poliza sin relacion
                //$query = "DELETE FROM soat_polizas
                //      WHERE poliza='".$_SESSION['soat']['poliverM']."';";
                //$resulta = $dbconn->Execute($query);
            }
            $dbconn->CommitTrans();
            if($this->frmError["MensajeError"]==NULL)
            {
                $this->frmError["MensajeError"]="DATOS MODIFICADOS CORRECTAMENTE";
            }
            $this->DatosAccidente();
            return true;
        }
    }

    function ValidarModificarEventoConduVeh()//
    {
        $this->uno=0;
        if($_POST['apellicond']==NULL)
        {
            $this->frmError["apellicond"]=1;
        }
        if($_POST['nombrecond']==NULL)
        {
            $this->frmError["nombrecond"]=1;
        }
        if($_POST['tidocucond']==NULL)
        {
            $this->frmError["tidocucond"]=1;
        }
        if($_POST['documecond']==NULL)
        {
            $this->frmError["documecond"]=1;
        }
        if($_POST['direcicond']==NULL)
        {
            $this->frmError["direcicond"]=1;
        }
        if(is_numeric($_POST['telefocond'])==0)
        {
            $this->frmError["telefocond"]=1;
            $_POST['telefocond']='';
        }
        if($_POST['apellicond']==NULL||$_POST['nombrecond']==NULL||
        $_POST['tidocucond']==NULL||$_POST['documecond']==NULL||
        $_POST['direcicond']==NULL||$_POST['telefocond']==NULL)
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            $this->uno=1;
            $this->ModificarEventoConduVeh();
            return true;
        }
        else
        {
            list($dbconn) = GetDBconn();
            $usuario=UserGetUID();
            $this->frmError["MensajeError"]='';
            if($_POST['insertarco']==1)
            {
                $query = "INSERT INTO soat_vehiculo_conductor
                        (evento,
                        apellidos_conductor,
                        nombres_conductor,
                        tipo_id_conductor,
                        conductor_id,
                        extipo_pais_id,
                        extipo_dpto_id,
                        extipo_mpio_id,
                        direccion_conductor,
                        telefono_conductor,
                        tipo_pais_id,
                        tipo_dpto_id,
                        tipo_mpio_id,
                        fecha_registro,
                        usuario_id)
                        VALUES
                        (".$_SESSION['soat']['eventoelegMVC'].",
                        '".$_POST['apellicond']."',
                        '".$_POST['nombrecond']."',
                        '".$_POST['tidocucond']."',
                        '".$_POST['documecond']."',
                        '".$_POST['paisE']."',
                        '".$_POST['dptoE']."',
                        '".$_POST['mpioE']."',
                        '".$_POST['direcicond']."',
                        '".$_POST['telefocond']."',
                        '".$_POST['pais']."',
                        '".$_POST['dpto']."',
                        '".$_POST['mpio']."',
                        '".date("Y-m-d H:i:s")."',
                        ".$usuario.");";
            }
            else
            {
                $query = "UPDATE soat_vehiculo_conductor SET
                        apellidos_conductor='".$_POST['apellicond']."',
                        nombres_conductor='".$_POST['nombrecond']."',
                        tipo_id_conductor='".$_POST['tidocucond']."',
                        conductor_id='".$_POST['documecond']."',
                        extipo_pais_id='".$_POST['paisE']."',
                        extipo_dpto_id='".$_POST['dptoE']."',
                        extipo_mpio_id='".$_POST['mpioE']."',
                        direccion_conductor='".$_POST['direcicond']."',
                        telefono_conductor='".$_POST['telefocond']."',
                        tipo_pais_id='".$_POST['pais']."',
                        tipo_dpto_id='".$_POST['dpto']."',
                        tipo_mpio_id='".$_POST['mpio']."'
                        WHERE evento=".$_SESSION['soat']['eventoelegMVC'].";";
            }
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL INSERTAR LOS DATOS DEL CONDUCTOR";
            }
            else
            {
                $this->frmError["MensajeError"]="DATOS INSERTADOS O MODIFICADOS CORRECTAMENTE";
            }
            $this->DatosAccidente();
            return true;
        }
    }

    function ValidarGuardarAmbu()
    {
        if(empty($_POST['nombrecondA']))
        {
            $this->frmError["nombrecondA"]=1;
        }
        if(empty($_POST['docucondA']))
        {
            $this->frmError["docucondA"]=1;
        }
        if(empty($_POST['paisE']))
        {
            $this->frmError["paisE"]=1;
        }
        if(empty($_POST['dptoE']))
        {
            $this->frmError["dptoE"]=1;
        }
        if(empty($_POST['mpioE']))
        {
            $this->frmError["mpioE"]=1;
        }
        if(empty($_POST['pais']))
        {
            $this->frmError["pais"]=1;
        }
        if(empty($_POST['dpto']))
        {
            $this->frmError["dpto"]=1;
        }
        if(empty($_POST['mpio']))
        {
            $this->frmError["mpio"]=1;
        }
        if(empty($_POST['placaA']))
        {
            $this->frmError["placaA"]=1;
        }
        //--cambio dar, campos nuevos
        if(empty($_POST['traslado']))
        {
            $this->frmError["traslado"]=1;
        }       
        if(!empty($_POST['FechaTraslado']))
        {
                $f=explode('/',$_POST['FechaTraslado']);
                $_POST['FechaTraslado']=$f[2].'-'.$f[1].'-'.$f[0];
        }       

        //-------------fin cambio dar       
        if(empty($_POST['nombrecondA'])||empty($_POST['docucondA'])||
        empty($_POST['paisE'])||empty($_POST['pais'])||
        empty($_POST['dptoE'])||empty($_POST['dpto'])||
        empty($_POST['mpioE'])||empty($_POST['mpio'])||
        empty($_POST['placaA']) OR empty($_POST['traslado']))
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            $this->uno=1;
            $this->ModificarDatosEventoAmb();
            return true;
        }
        else
        {
            if($_REQUEST['ambugumo']==1)//guarda
            {
                $usuario=UserGetUID();
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $this->frmError["MensajeError"]='';
                $query = "SELECT NEXTVAL ('soat_ambulancias_ambulancia_id_seq');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS";
                    $dbconn->RollBackTrans();
                }
                $indiceam=$resulta->fields[0];
                $query = "INSERT INTO soat_ambulancias
                        (ambulancia_id,
                        tipo_id_paciente,
                        conductor_id,
                        nombre_conductor,
                        direccion,
                        telefono,
                        extipo_pais_id,
                        extipo_dpto_id,
                        extipo_mpio_id,
                        tipo_pais_id,
                        tipo_dpto_id,
                        tipo_mpio_id,
                        placa_ambulancia,
                        lugar_desde,
                        lugar_hasta,
                        fecha_registro,
                        usuario_id,
                        evento,
                        tipo_traslado,
                        fecha_traslado)
                        VALUES
                        (".$indiceam.",
                        '".$_POST['tidocucondA']."',
                        '".$_POST['docucondA']."',
                        '".$_POST['nombrecondA']."',
                        '".$_POST['direcondA']."',
                        '".$_POST['telecondA']."',
                        '".$_POST['paisE']."',
                        '".$_POST['dptoE']."',
                        '".$_POST['mpioE']."',
                        '".$_POST['pais']."',
                        '".$_POST['dpto']."',
                        '".$_POST['mpio']."',
                        '".$_POST['placaA']."',
                        '".$_POST['lugardesdeA']."',
                        '".$_POST['lugarhastaA']."',
                        '".date("Y-m-d H:i:s")."',
                        ".$usuario.",
                        ".$_SESSION['soat']['eventoelegMM'].",
                        '".$_POST['traslado']."',
                        '".$_POST['FechaTraslado']."');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL CREAR LOS DATOS ". $dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
                //ACTUALIZAR LA AMBULANCIA DEL EVENTO
                $query = "UPDATE soat_eventos SET
                        ambulancia_id=".$indiceam."
                        WHERE evento=".$_SESSION['soat']['eventoelegMM'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS ". $dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
            }
            else if($_REQUEST['ambugumo']==2)//modifica
            {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $query = "UPDATE soat_ambulancias SET
                        tipo_id_paciente='".$_POST['tidocucondA']."',
                        conductor_id='".$_POST['docucondA']."',
                        nombre_conductor='".$_POST['nombrecondA']."',
                        direccion='".$_POST['direcondA']."',
                        telefono='".$_POST['telecondA']."',
                        extipo_pais_id='".$_POST['paisE']."',
                        extipo_dpto_id='".$_POST['dptoE']."',
                        extipo_mpio_id='".$_POST['mpioE']."',
                        tipo_pais_id='".$_POST['pais']."',
                        tipo_dpto_id='".$_POST['dpto']."',
                        tipo_mpio_id='".$_POST['mpio']."',
                        placa_ambulancia='".$_POST['placaA']."',
                        lugar_desde='".$_POST['lugardesdeA']."',
                        lugar_hasta='".$_POST['lugarhastaA']."',
                        fecha_traslado='".$_POST['FechaTraslado']."',
                        tipo_traslado='".$_POST['traslado']."'
                        WHERE ambulancia_id=".$_SESSION['soat']['ambuverM'].";";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS". $dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
            }
            $dbconn->CommitTrans();
            if($this->frmError["MensajeError"]==NULL)
            {
                $this->frmError["MensajeError"]="DATOS INSERTADOS O MODIFICADOS CORRECTAMENTE";
            }
            $this->DatosAccidente();
            return true;
        }
    }

    function IraConsumoSoat()
    {
            $_SESSION['soat']['consumo']['TipoDocum']=$_SESSION['soat']['evento']['TipoDocum'];
            $_SESSION['soat']['consumo']['Documento']=$_SESSION['soat']['evento']['Documento'];
            UNSET($_SESSION['soat']['evento']);
            $this->DatosConsumo();
            return true;
    }

    function IraEventoSoat()
    {
            $_SESSION['soat']['evento']['TipoDocum']=$_SESSION['soat']['consumo']['TipoDocum'];
            $_SESSION['soat']['evento']['Documento']=$_SESSION['soat']['consumo']['Documento'];
            UNSET($_SESSION['soat']['consumo']);
            $this->DatosAccidente();
            return true;
    }

    function BuscarRemitirEventoAcc($evento)//
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT A.remision_id,
                A.centro_remision,
                A.fecha_remision,
                A.fecha_registro,
                B.descripcion
                FROM soat_remision AS A,
                centros_remision AS B
                WHERE A.evento=".$evento."
                AND A.centro_remision=B.centro_remision
                ORDER BY A.remision_id;";
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
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarCentroRemision()//
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT centro_remision,
                descripcion
                FROM centros_remision
                ORDER BY centro_remision;";
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
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarConsultarRemitirEventoAcc($evento,$remision)//
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT A.fecha_remision,
                A.observacion,
                B.descripcion,
		CASE WHEN A.tipo_referencia = 'R' THEN 'REMISION'
		     WHEN A.tipo_referencia = 'OS' THEN 'ORDEN DE SERVICIO' END as referencia,
		A.codigo_inscripcion,
		A.hora,
		A.nombre_profesional_recibe,
		A.profesional_recibe_cargo
                FROM soat_remision AS A,
                centros_remision AS B
                WHERE A.evento=".$evento."
                AND A.remision_id=".$remision."
                AND A.centro_remision=B.centro_remision;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function ValidaIngresaRemisionSoat()//
    {
        if($_POST['centrosrem']==NULL)
        {
            $this->frmError["centrosrem"]=1;
        }
        if(empty($_POST['fecha']))
        {
            $this->frmError["fecha"]=1;
        }
        else
        {
            $valfec=explode('/',$_POST['fecha']);
            $day=$valfec[0];
            $mon=$valfec[1];
            $yea=$valfec[2];
            if(checkdate($mon, $day, $yea)==0)
            {
                $_POST['fecha']='';
                $this->frmError["fecha"]=1;
            }
            else
            {
                $_POST['fecha']=$yea.'-'.$mon.'-'.$day;
            }
        }
        if($_POST['observacion']==NULL)
        {
            $this->frmError["observacion"]=1;
        }
	
        if($_POST['inscripcion']==NULL)
        {
            $this->frmError["inscripcion"]=1;
        }
	
        if($_POST['horario2']==-1)
        {
            $this->frmError["hora"]=1;
        }
        if($_POST['minutero2']==-1)
        {
            $this->frmError["hora"]=1;
        }
	
	//
	if($_POST[referencia]=='OS')
	{
		if(empty($_POST['prefesionalrem']))
		{
			$this->frmError["prefesionalrem"]=1;
		}
		if(empty($_POST['cargoprefesionalrem']))
		{
			$this->frmError["cargoprefesionalrem"]=1;
		}
	}
	if($_POST[referencia]=='R')
	{
		if($_POST['horario2recepcion'] == -1)
		{
			$this->frmError["fecharecepcion"]=1;
		}
		if($_POST['minutero2recepcion'] == -1)
		{
			$this->frmError["fecharecepcion"]=1;
		}
	}
	//
	
        if($_POST['centrosrem']==NULL||$_POST['observacion']==NULL||empty($_POST['fecha'])||$_POST['inscripcion'] == NULL
	|| $_POST[horario2] == -1 || $_POST['minutero2'] == -1 || $this->frmError["prefesionalrem"]==1|| $this->frmError["cargoprefesionalrem"]==1
	||$this->frmError["fecharecepcion"]==1)
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            $this->uno=1;
            $this->IngresaRemisionSoat();
            return true;
        }
        else
        {

    	    $horaR = $_POST['horario2'].':'.$_POST['minutero2'];
	    $campos = $valores = $campos2 = $valores2 = $campos3 = $valores3 = "";
            list($dbconn) = GetDBconn();
            $usuario=UserGetUID();
	    //if($_POST['referencia']=='OS')
	    //{
		//$tercero = explode("||//",$_POST[prefesionalrem]);
		$nombreprof = $_POST['prefesionalrem'];
		$cargonombreprof = $_POST['cargoprefesionalrem'];
		$campos=",
		    nombre_profesional_recibe,
		    profesional_recibe_cargo";
		$valores=",'$nombreprof',
		    '$cargonombreprof'";
	    //}
	    //if($_POST['referencia']=='R')
	    //{
		$valfec=explode('/',$_POST['fecharecepcion']);
		$day=$valfec[0];
		$mon=$valfec[1];
		$yea=$valfec[2];
                $_POST['fecharecepcion']=$yea.'-'.$mon.'-'.$day;
		$horaRR = $_POST['horario2recepcion'].':'.$_POST['minutero2recepcion'];
		$campos2=",
		    fecha_recepcion_remision,
		    hora_recepcion_remision";
		$valores2=",'".$_POST['fecharecepcion']."',
		    '$horaRR'";
	    //}
	    
	    	$campos3 = ",nombre_profesional_remite, profesional_remite_cargo";
		$valores3 = ",'".$_POST['prefesionalr']."', '".$_POST['cargoprefesionalr']."'";
		
                $query = "INSERT INTO soat_remision
                    (evento,
                    centro_remision,
                    fecha_remision,
                    observacion,
                    fecha_registro,
                    usuario_id,
		    tipo_referencia,
		    hora,
		    codigo_inscripcion $campos $campos2 $campos3)
                    VALUES
                    (".$_SESSION['soat']['eventoelegRE'].",
                    '".$_POST['centrosrem']."',
                    '".$_POST['fecha']."',
                    '".$_POST['observacion']."',
                    '".date("Y-m-d H:i:s")."',
                    ".$usuario.",
		    '".$_POST['referencia']."',
		    '".$horaR."',
		    '".$_POST['inscripcion']."' $valores $valores2 $valores3);";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->frmError["MensajeError"]="DATOS GUARDADOS CORECTAMENTE";
            $this->uno=1;
            $this->RemitirEventoAcc();
            return true;
        }
    }

    function ValidarCrearCertificado()//
    {
        if(is_numeric($_POST['compania'])==0)
        {
            $this->frmError["compania"]=1;
            $_POST['compania']='';
        }
        else
        {
            $valorcontr=doubleval($_POST['compania']);
            if($valorcontr >= 100000000000)
            {
                $this->frmError["compania"]=1;
                $_POST['compania']='';
            }
        }
        if(is_numeric($_POST['consorcio'])==0)
        {
            $this->frmError["consorcio"]=1;
            $_POST['consorcio']='';
        }
        else
        {
            $valorcontr=doubleval($_POST['consorcio']);
            if($valorcontr >= 100000000000)
            {
                $this->frmError["consorcio"]=1;
                $_POST['consorcio']='';
            }
        }
        if($_POST['observacion']==NULL)
        {
            $this->frmError["observacion"]=1;
        }
        if($_POST['compania']==NULL||$_POST['consorcio']==NULL||$_POST['observacion']==NULL)
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            $this->uno=1;
            $this->CrearCertificado();
            return true;
        }
        else
        {
            $this->ImprimirCrearCertificado();
            return true;
        }
    }

    function BuscarReporteCertificadoSoat($tipoid,$docuid,$evento,$compania,$consorcio,$observacion)
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT A.paciente_id,
                B.poliza,
                C.nombre_tercero,
                E.estado,
                E.fecha_ingreso,
                G.razon_social,
                G.tipo_id_tercero,
                G.id,
                H.descripcion
                FROM soat_eventos AS A,
                soat_polizas AS B,
                terceros AS C,
                ingresos_soat AS D,
                ingresos AS E,
                departamentos AS F,
                empresas AS G,
                tipos_id_pacientes AS H
                WHERE A.tipo_id_paciente='".$tipoid."'
                AND A.paciente_id='".$docuid."'
                AND A.evento=".$evento."
                AND A.poliza=B.poliza
                AND B.tipo_id_tercero=C.tipo_id_tercero
                AND B.tercero_id=C.tercero_id
                AND D.evento=".$evento."
                AND D.ingreso=E.ingreso
                AND E.departamento_actual=F.departamento
                AND F.empresa_id=G.empresa_id
                AND A.tipo_id_paciente=H.tipo_id_paciente;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        if(!empty($var))
        {
            $var['diaact']=strftime("%d");
            $var['mesact']=strftime("%B");
            $var['anoact']=strftime("%Y");
            $fecha=explode(' ',$var['fecha_ingreso']);
            $fecha=explode('-',$fecha[0]);
            $var['mes']=strftime("%B",mktime(1,1,1,$fecha[1],$fecha[2],$fecha[0]));
            $var['mesact']=strtoupper($var['mesact']);
            $var['dia']=$fecha[2];
            $var['ano']=$fecha[0];
            $var['compania']=$compania;
            $var['consorcio']=$consorcio;
            $var['observacion']=$observacion;
            $var['suma']=$var['compania']+$var['consorcio'];
            $var['nombrepaci']=$_SESSION['soat']['evento']['nombresoat']['primer_apellido'].' '.$_SESSION['soat']['evento']['nombresoat']['segundo_apellido'].' '.$_SESSION['soat']['evento']['nombresoat']['primer_nombre'].' '.$_SESSION['soat']['evento']['nombresoat']['segundo_nombre'];
            //
            IncludeLib("reportes/soat_constancia");
            GenerarSoatConstancia($var);
            return $var;
            //
        }
        else
        {
            $this->frmError["MensajeError"]="EL PACIENTE NO TIENE UN INGRESO ASOCIADO AL EVENTO";
            $this->uno=1;
            return $var;
        }
    }

    function ValidarDatosConsumo()//Valida y Guarda los datos del consumo
    {
        $this->uno=0;
        if(empty($_POST['fechadelrep']))
        {
            $this->frmError["fechadelrep"]=1;
        }
        else
        {
            $valfec=explode('/',$_POST['fechadelrep']);
            $day=$valfec[0];
            $mon=$valfec[1];
            $yea=$valfec[2];
            if(checkdate($mon, $day, $yea)==0)
            {
                $_POST['fechadelrep']='';
                $this->frmError["fechadelrep"]=1;
            }
            else
            {
                $fec=date ("Y-m-d");
                if($fec < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                {
                    $_POST['fechadelrep']='';
                    $this->frmError["fechadelrep"]=1;
                }
                else
                {
                    $fechahora=1;
                    $fecha = $yea.'-'.$mon.'-'.$day.' '.$_POST['horariorep'].':'.$_POST['minuterrep'].':'.'00';
                }
            }
        }
        if($_POST['valorconsu']==NULL)//if(empty($_POST['valorconsu']))
        {
            $this->frmError["valorconsu"]=1;
            $grabar=0;
        }
        else
        {
            if(is_numeric($_POST['valorconsu'])==0)
            {
                $this->frmError["valorconsu"]=1;
                $grabar=0;
            }
            else
            {
                $valorcon=doubleval($_POST['valorconsu']);
                $saldocon=doubleval($_SESSION['soat']['saldoconsu']);
                if($_REQUEST['guarmodi']==2)
                {
                    $valorvie=doubleval($_SESSION['soat']['valorviejo']);
                    $saldocon=(($saldocon)+($valorvie));
                }
                if($saldocon < $valorcon)
                {
                    $this->frmError["valorconsu"]=1;
                    $grabar=0;
                }
                else
                {
                    $grabar=1;
                }
            }
        }
        if($fechahora==0||$grabar==0)
        {
            if(!empty($_POST['valorconsu'])&&($saldocon < $valorcon))
            {
                $this->frmError["MensajeError"]="EL VALOR DEL CONSUMO ES MAYOR AL SALDO";
            }
            else
            {
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            }
            $this->uno=1;
            $this->IngresaDatosConsumo();
            return true;
        }
        else
        {
            $nuevosaldo=(($saldocon)-($valorcon));
            if($_REQUEST['guarmodi']==1 AND $valorcon>0)
            {//guarda
                list($dbconn) = GetDBconn();
                $usuario=UserGetUID();
                $query = "INSERT INTO soat_consumos
                        (evento,
                        entidad_reporta,
                        funcionario_reporta,
                        fecha_reporte,
                        valor_consumo,
                        fecha_registro,
                        usuario_id)
                        VALUES
                        (".$_SESSION['soat']['evenconsumo'].",
                        '".$_POST['entidadrep']."',
                        '".$_POST['funcionrep']."',
                        '".$fecha."',
                        ".$valorcon.",
                        '".date("Y-m-d H:i:s")."',
                        ".$usuario.");";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $evenmodi=intval($_SESSION['soat']['evenconsumo']);
                $query = "UPDATE soat_eventos SET
                        saldo=".$nuevosaldo."
                        WHERE evento=".$evenmodi.";";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
            else if($_REQUEST['guarmodi']==2)
            {//modifica
                list($dbconn) = GetDBconn();
                $usuario=UserGetUID();
                $query = "UPDATE soat_consumos SET
                        entidad_reporta='".$_POST['entidadrep']."',
                        funcionario_reporta='".$_POST['funcionrep']."',
                        fecha_reporte='".$fecha."',
                        valor_consumo=".$valorcon.",
                        usuario_id=".$usuario."
                        WHERE consumo=".$_SESSION['soat']['consumoele'].";";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $evenmodi=intval($_SESSION['soat']['evenconsumo']);
                $query = "UPDATE soat_eventos SET
                        saldo=".$nuevosaldo."
                        WHERE evento=".$evenmodi.";";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
            $_SESSION['soat']['saldoconsu']=$nuevosaldo;//ojo si modifica, tenga en cuenta
            $this->MostrarDatosConsumo();
            return true;
        }
    }

    function BuscarConsumosSoat($consumoseven)//Busca todos los consumos relacionados a un evento
    {
        list($dbconn) = GetDBconn();
        $usuario=UserGetUID();
        $query = "SELECT consumo,
                entidad_reporta,
                funcionario_reporta,
                fecha_reporte,
                valor_consumo
                FROM soat_consumos
                WHERE evento=".$consumoseven.";";
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
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarConsumosSoatMod($consumoeleg)//Busca la informacion de un consumo para modificar
    {
        list($dbconn) = GetDBconn();
        $usuario=UserGetUID();
        $query = "SELECT consumo,
                entidad_reporta,
                funcionario_reporta,
                fecha_reporte,
                valor_consumo
                FROM soat_consumos
                WHERE consumo=".$consumoeleg.";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

//Metodo externo
    function ValidarSoatAdmiVie()//Funcion que volida si se ha escogido un evento
    {
        $this->admin1 = 0;
        if(empty($_POST['eligevento']))
        {
            $this->admin2 = 0;
            $this->admin1 = 1;
            $this->frmError["MensajeError"]="SELECCIONE UN EVENTO";
            $this->SoatAdmision();
            return true;
        }
        else
        {
            $var=explode(',',$_POST['eligevento']);
            $contenedor=$_SESSION['SOAT']['RETORNO']['contenedor'];//app
            $modulo=$_SESSION['SOAT']['RETORNO']['modulo'];//Triage
            $tipo=$_SESSION['SOAT']['RETORNO']['tipo'];//user
            $metodo=$_SESSION['SOAT']['RETORNO']['metodo'];//LlamaFormaIngresoEventos
            $argumentos=$_SESSION['SOAT']['RETORNO']['argumentos'];
            $_SESSION['SOAT']['NOEVENTO']=FALSE;
            $_SESSION['SOAT']['RETORNO']['evento']=$var[0];
            $_SESSION['SOAT']['RETORNO']['poliza']=$var[1];
            $_SESSION['SOAT']['RETORNO']['saldo']=$var[2];
            $_SESSION['SOAT']['RETORNO']['sw']=2;
            $_SESSION['SOAT']['RETORNO']['paciente_id']=$_SESSION['SOAT']['PACIENTE']['paciente_id'];
            $_SESSION['SOAT']['RETORNO']['tipo_id_paciente']=$_SESSION['SOAT']['PACIENTE']['tipo_id_paciente'];
            $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
            return true;
        }
    }

//Metodo externo
    function ValidarSoatAdmiNue()//Funcion que volida los datos de un nuevo evento
    {
        $this->admin2 = 0;
        $this->polizamala=0;
        $caso=0;
        if(empty($_POST['asegurado']))
        {
            $this->frmError["MensajeError"]="FALTA LA CLASIFICACIÓN DEL ASEGURADO";
            $this->frmError["asegurado"]=1;
            $this->admin2=1;
            $this->SoatAdmision();
            return true;
        }
        else if($_POST['asegurado']==1)//Si
        {
            if($_POST['poliza1']==NULL||$_POST['poliza2']==NULL||$_POST['poliza3']==NULL)
            {
                $this->frmError["poliza1"]=1;
            }
            elseif($_POST[tiponaturaleza]=='01')
            {
                if($this->ValidarPoliza($_POST['poliza2'],$_POST['poliza3'])==false)
                {
                    $_POST['poliza2']='';
                    $_POST['poliza3']='';
                    $this->frmError["poliza1"]=1;
                    $this->polizamala=1;
                }
            }
            if(empty($_POST['fechadesde']))
            {
                $this->frmError["fechadesde"]=1;
            }
            else
            {
                $fecdes=explode('/',$_POST['fechadesde']);
                $day=$fecdes[0];
                $mon=$fecdes[1];
                $yea=$fecdes[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechadesde']='';
                    $this->frmError["fechadesde"]=1;
                }
                else
                {
                    $fecd=date ("Y-m-d");
                    if($fecd < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechadesde']='';
                        $this->frmError["fechadesde"]=1;
                    }
                }
            }
            if(empty($_POST['fechahasta']))
            {
                $this->frmError["fechahasta"]=1;
            }
            else
            {
                $fechas=explode('/',$_POST['fechahasta']);
                $day=$fechas[0];
                $mon=$fechas[1];
                $yea=$fechas[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechahasta']='';
                    $this->frmError["fechahasta"]=1;
                }
                else
                {
                    $fech=date ("Y-m-d");
                    if($fech > date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechahasta']='';
                        $this->frmError["fechahasta"]=1;
                    }
                }
            }
            if($_POST['aseguradora']==NULL)
            {
                $this->frmError["aseguradora"]=1;
            }
            else
            {
                $asegurador=explode(',',$_POST['aseguradora']);
                if($asegurador[2]<>$_POST['poliza1'])
                {
                    $this->frmError["aseguradora"]=1;
                }
            }
	if($_POST[tiponaturaleza]=='01')
	{
		if(empty($_POST['marca']))
		{
			$this->frmError["marca"]=1;
		}
		if(empty($_POST['placa']))
		{
			$this->frmError["placa"]=1;
		}
		if(empty($_POST['tipove']))
		{
			$this->frmError["tipove"]=1;
		}
	}
            //$edad=CalcularEdad($_SESSION['SOAT']['PACIENTE']['fecha_nacimiento'],'');
            /*

            */
	    
/*            if(empty($_POST['marca'])||empty($_POST['placa'])||
            empty($_POST['tipove'])||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1)*/
            if($this->frmError["marca"] == 1||$this->frmError["placa"] == 1||
            $this->frmError["tipove"] == 1||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1)
            {
                if($asegurador[2]<>$_POST['poliza1'])
                {
                    $this->frmError["MensajeError"]="LA POLIZA NO CORRRESPONDE A LA ASEGURADORA";
                }
                else
                {
                    $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
                }
                $this->admin2=1;
                $this->SoatAdmision();
                return true;
            }
            else
            {
                $caso=1;
            }
        }
        else if($_POST['asegurado']==4)//poliza falsa
        {
            if($_POST['poliza1']==NULL||$_POST['poliza2']==NULL||$_POST['poliza3']==NULL)
            {
                $this->frmError["poliza1"]=1;
            }
            /*else
            {
                if($this->ValidarPoliza($_POST['poliza2'],$_POST['poliza3'])==false)
                {
                    $_POST['poliza2']='';
                    $_POST['poliza3']='';
                    $this->frmError["poliza1"]=1;
                    $this->polizamala=1;
                }
            }*/
            if(empty($_POST['fechadesde']))
            {
                $this->frmError["fechadesde"]=1;
            }
            else
            {
                $fecdes=explode('/',$_POST['fechadesde']);
                $day=$fecdes[0];
                $mon=$fecdes[1];
                $yea=$fecdes[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechadesde']='';
                    $this->frmError["fechadesde"]=1;
                }
                else
                {
                    $fecd=date ("Y-m-d");
                    if($fecd < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechadesde']='';
                        $this->frmError["fechadesde"]=1;
                    }
                }
            }
            if(empty($_POST['fechahasta']))
            {
                $this->frmError["fechahasta"]=1;
            }
            else
            {
                $fechas=explode('/',$_POST['fechahasta']);
                $day=$fechas[0];
                $mon=$fechas[1];
                $yea=$fechas[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechahasta']='';
                    $this->frmError["fechahasta"]=1;
                }
                else
                {
                    $fech=date ("Y-m-d");
                    if($fech > date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechahasta']='';
                        $this->frmError["fechahasta"]=1;
                    }
                }
            }
            if($_POST['aseguradora']==NULL)
            {
                $this->frmError["aseguradora"]=1;
            }
            /*else
            {
                $asegurador=explode(',',$_POST['aseguradora']);
                if($asegurador[2]<>$_POST['poliza1'])
                {
                    $this->frmError["aseguradora"]=1;
                }
            }*/
	if($_POST[tiponaturaleza]=='01')
	{
            if(empty($_POST['marca']))
            {
                $this->frmError["marca"]=1;
            }
            if(empty($_POST['placa']))
            {
                $this->frmError["placa"]=1;
            }
            if(empty($_POST['tipove']))
            {
                $this->frmError["tipove"]=1;
            }
	}
/*            if(empty($_POST['marca'])||empty($_POST['placa'])||
            empty($_POST['tipove'])||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1)*/
            if($this->frmError["marca"] == 1||$this->frmError["placa"] == 1 ||
	    $this->frmError["tipove"] == 1 ||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1)
            {
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
                $this->admin2=1;
                $this->SoatAdmision();
                return true;
            }
            else
            {
                $caso=4;
            }
        }
        else if($_POST['asegurado']==5)//poliza vencida
        {
            if($_POST['poliza1']==NULL||$_POST['poliza2']==NULL||$_POST['poliza3']==NULL)
            {
                $this->frmError["poliza1"]=1;
            }
            elseif($_POST[tiponaturaleza]=='01')
            {
                if($this->ValidarPoliza($_POST['poliza2'],$_POST['poliza3'])==false)
                {
                    $_POST['poliza2']='';
                    $_POST['poliza3']='';
                    $this->frmError["poliza1"]=1;
                    $this->polizamala=1;
                }
            }
            if(empty($_POST['fechadesde']))
            {
                $this->frmError["fechadesde"]=1;
            }
            else
            {
                $fecdes=explode('/',$_POST['fechadesde']);
                $day=$fecdes[0];
                $mon=$fecdes[1];
                $yea=$fecdes[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechadesde']='';
                    $this->frmError["fechadesde"]=1;
                }
                else
                {
                    $fecd=date ("Y-m-d");
                    if($fecd < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechadesde']='';
                        $this->frmError["fechadesde"]=1;
                    }
                }
            }
            if(empty($_POST['fechahasta']))
            {
                $this->frmError["fechahasta"]=1;
            }
            else
            {
                $fechas=explode('/',$_POST['fechahasta']);
                $day=$fechas[0];
                $mon=$fechas[1];
                $yea=$fechas[2];
                if(checkdate($mon, $day, $yea)==0)
                {
                    $_POST['fechahasta']='';
                    $this->frmError["fechahasta"]=1;
                }
                /*else
                {
                    $fech=date ("Y-m-d");
                    if($fech > date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['fechahasta']='';
                        $this->frmError["fechahasta"]=1;
                    }
                }*/
            }
            if($_POST['aseguradora']==NULL)
            {
                $this->frmError["aseguradora"]=1;
            }
            else
            {
                $asegurador=explode(',',$_POST['aseguradora']);
                if($asegurador[2]<>$_POST['poliza1'])
                {
                    $this->frmError["aseguradora"]=1;
                }
            }
		if($_POST[tiponaturaleza]=='01')
		{
			if(empty($_POST['marca']))
			{
			$this->frmError["marca"]=1;
			}
			if(empty($_POST['placa']))
			{
			$this->frmError["placa"]=1;
			}
			if(empty($_POST['tipove']))
			{
			$this->frmError["tipove"]=1;
			}
		}
/*            if(empty($_POST['marca'])||empty($_POST['placa'])||
            empty($_POST['tipove'])||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1)*/
            if($this->frmError["marca"] == 1||$this->frmError["placa"] == 1 ||
	    $this->frmError["tipove"] == 1 ||empty($_POST['fechadesde'])||
            empty($_POST['fechahasta'])||$_POST['poliza1']==NULL||
            $_POST['poliza2']==NULL||$_POST['poliza3']==NULL||
            $this->frmError["aseguradora"]==1)
            {
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
                $this->admin2=1;
                $this->SoatAdmision();
                return true;
            }
            else
            {
                $caso=5;
            }
        }
        else if($_POST['asegurado']==2)//No
        {
            if(!empty($_POST['poliza1']) OR !empty($_POST['poliza2']) OR !empty($_POST['poliza3']))
            {
                $_POST['poliza1']='';
                $_POST['poliza2']='';
                $_POST['poliza3']='';
            }
            if($_POST['aseguradora']<>NULL)
            {
                $_POST['aseguradora']='';
            }
            if(!empty($_POST['sucursal']))
            {
                $_POST['sucursal']='';
            }
            if(!empty($_POST['fechadesde']))
            {
                $_POST['fechadesde']='';
            }
            if(!empty($_POST['fechahasta']))
            {
                $_POST['fechahasta']='';
            }
		if($_POST[tiponaturaleza]=='01')
		{
			if(empty($_POST['marca']))
			{
				$this->frmError["marca"]=1;
			}
			if(empty($_POST['placa']))
			{
				$this->frmError["placa"]=1;
			}
			if(empty($_POST['tipove']))
			{
				$this->frmError["tipove"]=1;
			}
		}
            if(($_POST[tiponaturaleza]=='01') AND (empty($_POST['marca'])||empty($_POST['placa'])||empty($_POST['tipove'])))
            {
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
                $this->admin2=1;
                $this->SoatAdmision();
                return true;
            }
            else
            {
                $caso=2;
            }
        }
        else if($_POST['asegurado']==3)//Fantasma
        {
            $caso=3;
        }
        if(empty($_POST['fechadmis']))
        {
            $this->frmError["fechadmis"]=1;
        }
        else
        {
            $valfec=explode('/',$_POST['fechadmis']);
            $day=$valfec[0];
            $mon=$valfec[1];
            $yea=$valfec[2];
            if(checkdate($mon, $day, $yea)==0)
            {
                $_POST['fechadmis']='';
                $this->frmError["fechadmis"]=1;
            }
            else
            {
                $fec=date ("Y-m-d");
                if($fec < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                {
                    $_POST['fechadmis']='';
                    $this->frmError["fechadmis"]=1;
                }
                else
                {
                    $fecha = $yea.'-'.$mon.'-'.$day.' ';
                    $fechahora=1;
                }
            }
        }
        if($fechahora == 1)
        {
            $horac=intval(date("H"));
            $minac=intval(date("i"));
            $hor=intval($_POST['horadmin']);
            $min=intval($_POST['minudmin']);
            if($horac<$hor AND $fec <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
            {
                $_POST['horadmin']=0;
                $this->frmError["horadmin"]=1;
            }
            else
            {
                if($minac<$min AND $horac<=$hor AND $fec <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                {
                    $_POST['minudmin']=0;
                    $this->frmError["horadmin"]=1;
                }
            }
        }
        if(empty($_POST['pais']))
        {
            $this->frmError["pais"]=1;
        }
        if(empty($_POST['dpto']))
        {
            $this->frmError["dpto"]=1;
        }
        if(empty($_POST['mpio']))
        {
            $this->frmError["mpio"]=1;
        }
        if(empty($_POST['zonadmin']))
        {
            $this->frmError["zonadmin"]=1;
        }
        if(empty($_POST['fechadmis'])||empty($_POST['pais'])||
        empty($_POST['dpto'])||empty($_POST['mpio'])||
        empty($_POST['zonadmin'])||$caso == 0)
        {
            $this->admin1 = 0;
            $this->admin2 = 1;
            if($asegurador[2]<>$_POST['poliza1'])
            {
                $this->frmError["MensajeError"]="LA POLIZA NO CORRRESPONDE A LA ASEGURADORA";
            }
            else
            {
                $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            }
            $this->SoatAdmision();
            return true;
        }
        else
        {
            $usuario=UserGetUID();
            list($dbconn) = GetDBconn();//$dbconn->BeginTrans();
            $fecha .= $_POST['horadmin'].':'.$_POST['minudmin'].':'.'00';
            $query = "SELECT NEXTVAL ('soat_accidente_accidente_id_seq');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $indice=$resulta->fields[0];
            $query = "INSERT INTO soat_accidente
                    (accidente_id,
                    fecha_accidente,
                    tipo_pais_id,
                    tipo_dpto_id,
                    tipo_mpio_id,
                    zona,
                    fecha_registro,
                    usuario_id)
                    VALUES
                    (".$indice.",
                    '".$fecha."',
                    '".$_POST['pais']."',
                    '".$_POST['dpto']."',
                    '".$_POST['mpio']."',
                    '".$_POST['zonadmin']."',
                    '".date("Y-m-d H:i:s")."',
                    ".$usuario.");";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if($caso==2 || $caso==3)//No y Fantasma
            {
                $query = "SELECT NEXTVAL ('sota_polizas_fidusalud_seq');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $polizadmin='0'.'-'.$resulta->fields[0];
                $fechades='NULL';
                $fechahas='NULL';
                $tipoacci[0]=ModuloGetVar('app','Soat','nit_tipo_fidusalud');//NIT
                $tipoacci[1]=ModuloGetVar('app','Soat','nit_id_fidusalud');//'830031511-6'
                $_POST['sucursal']='';
//echo '<br>';
                $query = "INSERT INTO soat_polizas
                        (poliza,
                        vigencia_desde,
                        vigencia_hasta,
                        tipo_id_tercero,
                        tercero_id,
                        sucursal,
                        placa_vehiculo,
                        marca_vehiculo,
                        tipo_vehiculo)
                        VALUES
                        ('".$polizadmin."',
                        ".$fechades.",
                        ".$fechahas.",
                        '".$tipoacci[0]."',
                        '".$tipoacci[1]."',
                        '".$_POST['sucursal']."',
                        '".$_POST['placa']."',
                        '".$_POST['marca']."',
                        '".$_POST['tipove']."');";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
            else if($caso==1 || $caso==4 || $caso==5)////SI, P. FALSA Y P. VENCIDA
            {
                $polizadmin=$_POST['poliza1'].'-'.$_POST['poliza2'].'-'.$_POST['poliza3'];
                $query = "SELECT poliza
                        FROM soat_polizas
                        WHERE poliza='".$polizadmin."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                if($resulta->EOF)
                {
                    $tipoacci=explode(',',$_POST['aseguradora']);
                    $fechapo=explode('/',$_POST['fechadesde']);
                    $fechades=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
                    $fechapo=explode('/',$_POST['fechahasta']);
                    $fechahas=$fechapo[2].'-'.$fechapo[1].'-'.$fechapo[0];
//echo '<br>';
                    $query = "INSERT INTO soat_polizas
                            (poliza,
                            vigencia_desde,
                            vigencia_hasta,
                            tipo_id_tercero,
                            tercero_id,
                            sucursal,
                            placa_vehiculo,
                            marca_vehiculo,
                            tipo_vehiculo)
                            VALUES
                            ('".$polizadmin."',
                            '".$fechades."',
                            '".$fechahas."',
                            '".$tipoacci[0]."',
                            '".$tipoacci[1]."',
                            '".$_POST['sucursal']."',
                            '".$_POST['placa']."',
                            '".$_POST['marca']."',
                            '".$_POST['tipove']."');";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                }
            }
            $query = "SELECT NEXTVAL ('soat_eventos_evento_seq');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $indiceev=$resulta->fields[0];
            $fechasaldo=explode('-',$fecha);
            $salmin=$this->SaldoEvento($fechasaldo[0]);
//echo '<br>';
		if ($_POST[tiponaturaleza] <> '01') 
		{
			$_POST[condicion] = "9";//SIN CONDICION CIUANDO ES DIFERENTE A UN ACCIDENTE DE TRANSITo
		}	
            $query = "INSERT INTO soat_eventos
                    (evento,
                    poliza,
                    tipo_id_paciente,
                    paciente_id,
                    saldo,
                    saldo_inicial,
                    accidente_id,
                    asegurado,
                    empresa_id,
                    centro_utilidad,
                    fecha_registro,
                    usuario_id,
		    soat_naturaleza_evento_id,
		    ambulancia_propia_ips,
		    tipo_ambulancia_id,
		    condicion_accidentado)
                    VALUES
                    (".$indiceev.",
                    '".$polizadmin."',
                    '".$_SESSION['SOAT']['PACIENTE']['tipo_id_paciente']."',
                    '".$_SESSION['SOAT']['PACIENTE']['paciente_id']."',
                    ".$salmin.",
                    ".$salmin.",
                    ".$indice.",
                    '".$caso."',
                    '".$_SESSION['SOAT']['PACIENTE']['empresa_id']."',
                    '".$_SESSION['SOAT']['PACIENTE']['centro_utilidad']."',
                    '".date("Y-m-d H:i:s")."',
                    ".$usuario.",
		    '".$_POST[tiponaturaleza]."',
		    '".$_POST[traslado]."',
		    '".$_POST[tipoambulancia]."',
		    '".$_POST[condicion]."');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $contenedor=$_SESSION['SOAT']['RETORNO']['contenedor'];//app
            $modulo=$_SESSION['SOAT']['RETORNO']['modulo'];//Triage
            $tipo=$_SESSION['SOAT']['RETORNO']['tipo'];//user
            $metodo=$_SESSION['SOAT']['RETORNO']['metodo'];//LlamaFormaIngresoEventos
            $argumentos=$_SESSION['SOAT']['RETORNO']['argumentos'];
            $_SESSION['SOAT']['RETORNO']['evento']=$indiceev;
            $_SESSION['SOAT']['RETORNO']['poliza']=$polizadmin;
            $_SESSION['SOAT']['RETORNO']['saldo']=$salmin;
            $_SESSION['SOAT']['RETORNO']['sw']=1;
            $_SESSION['SOAT']['RETORNO']['paciente_id']=$_SESSION['SOAT']['PACIENTE']['paciente_id'];
            $_SESSION['SOAT']['RETORNO']['tipo_id_paciente']=$_SESSION['SOAT']['PACIENTE']['tipo_id_paciente'];
            $this->ReturnMetodoExterno($contenedor,$modulo,$tipo,$metodo,$argumentos);
            return true;
        }
    }

    function BuscarCuentasSoat($empresa,$centrou)//Funcion que busca las cuentas tipo SOAT
    {
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $query = "SELECT A.numerodecuenta,
                A.ingreso,
                B.tipo_id_paciente,
                B.paciente_id,
                B.estado AS estado_ingreso,
                C.primer_apellido,
                C.segundo_apellido,
                C.primer_nombre,
                C.segundo_nombre,
                D.evento,
                E.saldo,
                E.poliza,
                G.nombre_tercero,
                I.descripcion,
                (
                    SELECT SUM(X.valor_consumo)
                    FROM soat_consumos AS X,
                    soat_eventos AS E
                    WHERE E.evento=X.evento
                ) AS consumoext,
                (
                    SELECT SUM(A.valor_cubierto+A.gravamen_valor_cubierto)
                    FROM soat_consumos_internos AS Y,
                    cuentas AS A,
                    soat_eventos AS E
                    WHERE E.evento=Y.evento
                    AND A.numerodecuenta=Y.numerodecuenta
                    AND A.estado=0
                ) AS consumoint,
                (
                    SELECT SUM(A.valor_cubierto+A.gravamen_valor_cubierto)
                    FROM soat_consumos_internos AS Y,
                    cuentas AS A,
                    soat_eventos AS E
                    WHERE E.evento=Y.evento
                    AND A.numerodecuenta=Y.numerodecuenta
                    AND A.estado>0
                ) AS consumointactual
                FROM cuentas AS A,
                ingresos AS B,
                pacientes AS C,
                ingresos_soat AS D,
                soat_eventos AS E,
                soat_polizas AS F,
                terceros AS G,
                planes AS H,
                cuentas_estados AS I
                WHERE A.empresa_id='".$empresa."'
                AND A.centro_utilidad='".$centrou."'
                AND A.plan_id=H.plan_id
                AND H.sw_tipo_plan='1'
                AND A.estado>0
                AND A.ingreso=B.ingreso
                AND A.estado=I.estado
                AND B.ingreso=D.ingreso
                AND B.tipo_id_paciente=C.tipo_id_paciente
                AND B.paciente_id=C.paciente_id
                AND D.evento=E.evento
                AND E.poliza=F.poliza
                AND F.tipo_id_tercero=G.tipo_id_tercero
                AND F.tercero_id=G.tercero_id
                ORDER BY A.estado, A.numerodecuenta,
                B.tipo_id_paciente, B.paciente_id;";
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
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function BuscarReporteAmbulanciaSoat($ambulancia)//Busca la informacion de la ambulancia para imprimir
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT A.tipo_id_paciente,
                A.conductor_id,
                A.nombre_conductor,
                A.direccion,
                A.telefono,
                A.placa_ambulancia,
                A.lugar_desde,
                A.lugar_hasta,
                B.poliza,
                C.tipo_id_tercero,
                C.id,
                D.descripcion,
                E.municipio AS exmunicipio,
                F.municipio AS municipio,
                G.fecha_accidente
                FROM soat_ambulancias AS A,
                soat_eventos AS B,
                empresas AS C,
                tipo_id_terceros AS D,
                tipo_mpios AS E,
                tipo_mpios AS F,
                soat_accidente AS G
                WHERE A.ambulancia_id=".$ambulancia."
                AND A.evento=B.evento
                AND B.empresa_id=C.empresa_id
                AND C.tipo_id_tercero=D.tipo_id_tercero
                AND A.extipo_pais_id=E.tipo_pais_id
                AND A.extipo_dpto_id=E.tipo_dpto_id
                AND A.extipo_mpio_id=E.tipo_mpio_id
                AND A.tipo_pais_id=F.tipo_pais_id
                AND A.tipo_dpto_id=F.tipo_dpto_id
                AND A.tipo_mpio_id=F.tipo_mpio_id
                AND B.accidente_id=G.accidente_id;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        $var['empresa']=$_SESSION['soa1']['razonso'];
        $var['pacient']=$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['evento']['nombresoat']['paciente_id'];
        $var['nombrpa']=$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre'];
        $var['lugar_expedicion_documento']=$_SESSION['soat']['evento']['nombresoat']['lugar_expedicion_documento'];
        $fecha=explode(' ',$var['fecha_accidente']);
        $hora=$fecha[1];
        $fecha=explode('-',$fecha[0]);
        $fecha=$fecha[2].'/'.$fecha[1].'/'.$fecha[0].' '.' '.$hora;
        $var['fecha_accidente']=$fecha;
        //
        IncludeLib("reportes/soat_ambulancia");
        GenerarSoatAmbulancia($var);
        //
        return $var;
    }

    function ImprimirSoatAmbu()
    {
        $var=$this->BuscarReporteAmbulanciaSoat($_SESSION['soat']['ambuverM']);
        if (!IncludeFile("classes/reports/reports.class.php"))
        {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }
        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pdf');
        $reporte=$classReport->PrintReport($tipo_reporte='pdf',$tipo_modulo='app',$modulo='Soat',$reporte_name='soat_ambulancia',$var,$impresora,$orientacion='P',$unidades='mm',$formato='soat',$html=1);
        if(!$reporte)
        {
            $this->error = $classReport->GetError();
            $this->mensajeDeError = $classReport->MensajeDeError();
            UNSET($classReport);
            return false;
        }
        $resultado=$classReport->GetExecResultado();
        UNSET($classReport);
        $this->ModificarDatosEventoAmb();
        return true;
    }

    function BuscarReporteEventoSoat($TipoDo,$Docume,$evento,$tipo_reporte,$ingreso)//Trae los datos personales del paciente
    {
      list($dbconn) = GetDBconn();

      $query = "SELECT  A.residencia_direccion,
                        A.residencia_telefono,
                        A.fecha_nacimiento,
                        A.lugar_expedicion_documento,
                        B.sexo_id,
                        C.municipio AS munipaciente,
                        D.poliza,
                        CASE  WHEN D.asegurado='1' THEN 'SI'
                              WHEN D.asegurado='2' THEN 'NO'
                              WHEN D.asegurado='4' THEN 'POLIZA FALSA'
                              WHEN D.asegurado='5' THEN 'POLIZA VENCIDA'
                              ELSE 'FANTASMA' END AS asegura,
                        E.descripcion AS descondicion,
                        F.fecha_accidente,
                        F.sitio_accidente,
                        F.informe_accidente,
                        G.municipio AS muniaccidente,
                        H.descripcion AS deszona,
                        I.departamento,
                        J.poliza,
                        J.vigencia_desde,
                        J.vigencia_hasta,
                        J.sucursal,
                        J.placa_vehiculo,
                        J.marca_vehiculo,
                        J.tipo_vehiculo,
                        K.nombre_tercero,
                        M.apellidos_conductor,
                        M.nombres_conductor,
                        M.tipo_id_conductor,
                        M.conductor_id,
                        M.direccion_conductor,
                        M.telefono_conductor,
                        N.tipo_id_tercero,
                        N.id,
                        N.codigo_sgsss,
                        N.direccion,
                        N.telefonos,
                        O.municipio AS muniempresa,
                        P.municipio AS munivehiculo,
                        Q.fecha_remision,
                        R.descripcion AS descentro,
                        S.municipio AS municentro,
                    		D.soat_naturaleza_evento_id,
                    		A.tipo_pais_id,
                    		A.tipo_dpto_id,
                    		A.tipo_mpio_id,
                    		F.tipo_pais_id AS pais_id_evento,
                    		F.tipo_dpto_id AS dpto_id_evento,
                    		F.tipo_mpio_id AS mpio_id_evento,
                    		D.tipo_servicio_vehiculo_id,
                    		D.intervension_autoridad,
                    		Q.tipo_referencia,
                    		Q.codigo_inscripcion,
                    		Q.fecha_recepcion_remision,
                    		Q.hora_recepcion_remision,
                    		Q.hora AS hora_os,
                    		Q.fecha_remision AS fecha_os,
                    		Q.nombre_profesional_recibe,
                    		Q.profesional_recibe_cargo,
                    		Q.nombre_profesional_remite,
                        Q.profesional_remite_cargo
                FROM    pacientes AS A,
                        tipo_sexo AS B,
                        tipo_mpios AS C,
                        soat_eventos AS D
                        LEFT JOIN condicion_accidentados E 
                        ON (  D.condicion_accidentado=E.condicion_accidentado )
                        LEFT JOIN soat_vehiculo_conductor AS M 
                        ON (  M.evento = D.evento)
                        LEFT JOIN tipo_mpios AS P 
                        ON (  M.tipo_pais_id=P.tipo_pais_id
                          AND M.tipo_dpto_id=P.tipo_dpto_id
                          AND M.tipo_mpio_id=P.tipo_mpio_id)
                        LEFT JOIN soat_remision AS Q 
                        ON (  Q.evento = D.evento
                          AND Q.remision_id = (
                                                SELECT MAX(remision_id)
                                                FROM    soat_remision
                                                WHERE   evento=".$evento."
                                              )
                            )
                        LEFT JOIN centros_remision AS R 
                        ON (  Q.centro_remision=R.centro_remision )
                        LEFT JOIN tipo_mpios AS S 
                        ON (  R.tipo_pais_id=S.tipo_pais_id
                          AND R.tipo_dpto_id=S.tipo_dpto_id
                          AND R.tipo_mpio_id=S.tipo_mpio_id),
                        soat_accidente AS F,
                        tipo_mpios AS G,
                        zonas_residencia AS H,
                        tipo_dptos AS I,
                        soat_polizas AS J,
                        terceros AS K,
                        empresas AS N,
                        tipo_mpios AS O
                WHERE   A.tipo_id_paciente='".$TipoDo."'
                AND     A.paciente_id='".$Docume."'
                AND     A.sexo_id=B.sexo_id
                AND     A.tipo_pais_id=C.tipo_pais_id
                AND     A.tipo_dpto_id=C.tipo_dpto_id
                AND     A.tipo_mpio_id=C.tipo_mpio_id
                AND     A.tipo_id_paciente=D.tipo_id_paciente
                AND     A.paciente_id=D.paciente_id
                AND     D.evento=".$evento."
                AND     D.accidente_id=F.accidente_id
                AND     F.tipo_pais_id=G.tipo_pais_id
                AND     F.tipo_dpto_id=G.tipo_dpto_id
                AND     F.tipo_mpio_id=G.tipo_mpio_id
                AND     F.zona=H.zona_residencia
                AND     F.tipo_pais_id=I.tipo_pais_id
                AND     F.tipo_dpto_id=I.tipo_dpto_id
                AND     D.poliza=J.poliza
                AND     J.tipo_id_tercero=K.tipo_id_tercero
                AND     J.tercero_id=K.tercero_id
                AND     D.empresa_id=N.empresa_id
                AND     N.tipo_pais_id=O.tipo_pais_id
                AND     N.tipo_dpto_id=O.tipo_dpto_id
                AND     N.tipo_mpio_id=O.tipo_mpio_id;";
      $resulta = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      
      while(!$resulta->EOF)
      {
        $var=$resulta->GetRowAssoc($ToUpper = false);
        $resulta->MoveNext();
      }

      $EdadArr=CalcularEdad($var['fecha_nacimiento'],'');
      $var['edad']=$EdadArr['edad_aprox'];
      $var['empresa']=$_SESSION['soa1']['razonso'];
      $var['tipo_paciente_id']=$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente'];
      $var['paciente_id']=$_SESSION['soat']['evento']['nombresoat']['paciente_id'];
      $var['primer_apellido']=$_SESSION['soat']['evento']['nombresoat']['primer_apellido'];
      $var['segundo_apellido']=$_SESSION['soat']['evento']['nombresoat']['segundo_apellido'];
      $var['primer_nombre']=$_SESSION['soat']['evento']['nombresoat']['primer_nombre'];
      $var['segundo_nombre']=$_SESSION['soat']['evento']['nombresoat']['segundo_nombre'];
      $fecha=explode(' ',$var['fecha_accidente']);
      $var['hora']=$fecha[1];
      $fecha=explode('-',$fecha[0]);
      $var['fecha_evento'] = $var['fecha_accidente'];
      $var['fecha_accidente']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
      $fecha=explode('-',$var['vigencia_desde']);
      $var['vigencia_desde']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
      $fecha=explode('-',$var['vigencia_hasta']);
      $var['vigencia_hasta']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
      $fecha=explode(' ',$var['fecha_remision']);
      $fecha=explode('-',$fecha[0]);
      $var['fecha_remision']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];

			$query = "SELECT  A.ingreso AS ingreso_id,
                        TO_CHAR(B.fecha_registro, 'YYYY-MM-DD hh:mi:ss') as fecha_ingreso,
                        C.via_ingreso_nombre,
                        E.evolucion_id,
                        I.diagnostico_nombre AS ingreso,
                        J.fecha_registro AS fecha_cierre
                FROM    ingresos_soat AS A,
                        vias_ingreso AS C,
                        ingresos AS B
                        LEFT JOIN hc_evoluciones AS E 
                        ON  (B.ingreso=E.ingreso)
                        LEFT JOIN hc_diagnosticos_ingreso AS F 
                        ON  (E.evolucion_id = F.evolucion_id)
                        LEFT JOIN diagnosticos AS I 
                        ON	(F.tipo_diagnostico_id = I.diagnostico_id)
                        LEFT JOIN ingresos_salidas AS J 
                        ON	(B.ingreso = J.ingreso)
                WHERE   A.evento = ".$evento."
                AND     A.ingreso = ".$ingreso."
                AND     A.ingreso=B.ingreso
                AND     B.via_ingreso_id=C.via_ingreso_id
                AND     F.sw_principal='1'
                ORDER BY E.fecha DESC ";
      $resulta = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      
      while(!$resulta->EOF)
      {
        $var2=$resulta->GetRowAssoc($ToUpper = false);
        $resulta->MoveNext();
      }

      $fecha=explode(' ',$var2['fecha_ingreso']);
      $var['hora_ingreso']=$fecha[1];
      $fecha=explode('-',$fecha[0]);
      $var['fecha_ingreso']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
      $var['tratamiento']=$var2['via_ingreso_nombre'];
      $fecha=explode(' ',$var2['fecha_cierre']);
      $fechaE=explode('-',$fecha[0]);
      $var['fecha_egreso']=$fechaE[2].'/'.$fechaE[1].'/'.$fechaE[0];
      $var['evolucion_id_ingre']=$var2['evolucion_id'];

		 	$query = "SELECT  A.ingreso AS ingreso_id,
                        B.fecha_ingreso,
              					C.via_ingreso_nombre,
              					E.fecha_cierre,
              					E.evolucion_id,
              					I.diagnostico_nombre AS egreso
                FROM    ingresos_soat AS A,
                        vias_ingreso AS C,
                        ingresos AS B
                        LEFT JOIN hc_evoluciones AS E 
                        ON  (B.ingreso=E.ingreso)
                        LEFT JOIN hc_diagnosticos_egreso AS G 
                        ON  (E.evolucion_id = G.evolucion_id)
                        LEFT JOIN diagnosticos AS I 
                        ON	(G.tipo_diagnostico_id = I.diagnostico_id)
                WHERE   A.evento = ".$evento."
                AND     A.ingreso = ".$ingreso."
                AND     A.ingreso = B.ingreso
                AND     B.via_ingreso_id = C.via_ingreso_id
                AND     G.sw_principal='1'
                ORDER BY E.fecha DESC ";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Selecionar datos2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
      while(!$resulta->EOF)
			{
				$var3=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}

      $fecha=explode(' ',$var2['fecha_cierre']);
			$horaE=explode('.',$fecha[1]);
			$var['hora_egreso']=$horaE[0];
			$var['dias_estancia']=abs($var['fecha_egreso']-$var['fecha_ingreso']);
			
      if(empty($var['dias_estancia']))
				$var['dias_estancia']='0';
			
      //DIAGNOSTICOS
			$var['desc_diagnostico_in'] = $var2['ingreso'];
			if(!empty($var3['egreso']))
				$var['desc_diagnostico_de'] = $var3['egreso'];
			else
				$var['desc_diagnostico_de'] = $var3['ingreso'];
			
      if($var2['ingreso']<>NULL)
      {
        $query = "SELECT  C.diagnostico_nombre AS causa_muerte,
                  				A.fecha AS fecha_defuncion, 
                  				D.nombre AS profesional_defuncion,
                  				D.registro_salud_departamental
                  FROM    hc_conducta_defuncion A,
                  				hc_conducta_diagnosticos_defuncion B,
                  				diagnosticos C,
                  				profesionales D
              		WHERE   A.evolucion_id=".$var2['evolucion_id']."
              		AND     A.ingreso=".$var2['ingreso_id']."
              		AND     A.evolucion_id=B.evolucion_id
              		AND     A.ingreso=B.ingreso
              		AND     B.diagnostico_defuncion_id=C.diagnostico_id
              		AND     A.usuario_id=D.usuario_id;";
        $resulta = $dbconn->Execute($query);//la ultima evolucion
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        if(!$resulta->EOF)
          $var6=$resulta->GetRowAssoc($ToUpper = false);
        
        $var['causa_muerte']=$var6['causa_muerte'];
        $var['fecha_defuncion']=$var6['fecha_defuncion'];
        $var['profesional_defuncion']=$var6['profesional_defuncion'];
        $var['registro_salud_departamental']=$var6['registro_salud_departamental'];
      }
      //FIN DIAGNOSTICOS

      //FIN DATOS DE EGRESO DEL EVENTO
      $query = "SELECT  departamento
                FROM    tipo_dptos
                WHERE   tipo_pais_id='".$var['tipo_pais_id']."'
                AND     tipo_dpto_id=".$var['tipo_dpto_id'].";";
      
      $resulta = $dbconn->Execute($query);//la ultima evolucion
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      if(!$resulta->EOF)
        $var6=$resulta->GetRowAssoc($ToUpper = false);
      
      $var['departamento_paciente']=$var6['departamento'];
		
      $query = "SELECT  municipio
          			FROM    tipo_mpios
          			WHERE   tipo_pais_id='".$var['tipo_pais_id']."'
          			AND     tipo_dpto_id='".$var['tipo_dpto_id']."'
          			AND     tipo_mpio_id='".$var['tipo_mpio_id']."';";
      
      $resulta = $dbconn->Execute($query);//la ultima evolucion
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      
      if(!$resulta->EOF)
        $var6=$resulta->GetRowAssoc($ToUpper = false);
      
      $var['municipio_paciente']=$var6['municipio'];

      //DATOS PAIS / DEPARTAMENTO / CIUDAD EVENTO
      $query = "SELECT  departamento
                FROM    tipo_dptos
                WHERE   tipo_pais_id='".$var['pais_id_evento']."'
                AND     tipo_dpto_id=".$var['dpto_id_evento'].";";
      
      $resulta = $dbconn->Execute($query);//la ultima evolucion
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      
      if(!$resulta->EOF)
        $var6=$resulta->GetRowAssoc($ToUpper = false);
      
      $var['departamento_evento']=$var6['departamento'];
		
      $query = "SELECT  municipio
          			FROM    tipo_mpios
          			WHERE   tipo_pais_id='".$var['pais_id_evento']."'
          			AND     tipo_dpto_id=".$var['dpto_id_evento']."
          			AND     tipo_mpio_id=".$var['mpio_id_evento'].";";
      $resulta = $dbconn->Execute($query);//la ultima evolucion
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      if(!$resulta->EOF)
        $var6=$resulta->GetRowAssoc($ToUpper = false);
      
      $var['municipio_evento']=$var6['municipio'];
	    //FIN DATOS PAIS / DEPARTAMENTO / CIUDAD EVENTO

      $query = "SELECT  a.codigo_sgsss 
                FROM    terceros_sgsss a, 
                        soat_polizas b
                WHERE   a.tipo_id_tercero=b.tipo_id_tercero
                AND     a.tercero_id=b.tercero_id
                AND     b.poliza='".$var['poliza']."'";
      $resulta = $dbconn->Execute($query);//la ultima evolucion
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      if(!$resulta->EOF)
        $var6=$resulta->GetRowAssoc($ToUpper = false);

      $var['codigo_aseguradora']=$var6['codigo_sgsss'];

      $query = "SELECT  a.apellidos_propietario,
                        a.nombres_propietario,
                				a.tipo_id_propietario,
                        a.propietario_id,
                        a.direccion_propietario,
                				a.telefono_propietario,
                        a.tipo_pais_id,
                				a.tipo_dpto_id,
                        a.tipo_mpio_id, 
                        b.municipio, 
                        c.departamento
                FROM    soat_vehiculo_propietario a, 
                        tipo_mpios b, 
                        tipo_dptos c
          			WHERE a.evento = ".$evento."
          			AND a.tipo_pais_id=b.tipo_pais_id
          			AND a.tipo_dpto_id=b.tipo_dpto_id
          			AND a.tipo_mpio_id=b.tipo_mpio_id
          			AND a.tipo_pais_id=c.tipo_pais_id
          			AND a.tipo_dpto_id=c.tipo_dpto_id ";
      $resulta = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      
      if(!$resulta->EOF)
        $var6=$resulta->GetRowAssoc($ToUpper = false);
        
  		$var['apellidos_propietario']=$var6['apellidos_propietario'];
  		$var['nombres_propietario']=$var6['nombres_propietario'];
  		$var['tipo_id_propietario']=$var6['tipo_id_propietario'];
  		$var['propietario_id']=$var6['propietario_id'];
  		$var['direccion_propietario']=$var6['direccion_propietario'];
  		$var['telefono_propietario']=$var6['telefono_propietario'];
  		$var['tipo_pais_id_propietario']=$var6['tipo_pais_id'];
  		$var['tipo_dpto_id_propietario']=$var6['tipo_dpto_id'];
  		$var['tipo_mpio_id_propietario']=$var6['tipo_mpio_id'];
  		$var['municipio_propietario']=$var6['municipio'];
  		$var['departamento_propietario']=$var6['departamento'];
		
      //CONDUCTOR VEHICULO INVOLUCRADO
      $query = "SELECT  a.apellidos_conductor,
                        a.nombres_conductor,
                				a.tipo_id_conductor,
                        a.conductor_id,
                        a.direccion_conductor,
                				a.telefono_conductor,
                        a.tipo_pais_id,
                				a.tipo_dpto_id,
                        a.tipo_mpio_id, 
                        b.municipio, 
                        c.departamento
          			FROM    soat_vehiculo_conductor a, 
                        tipo_mpios b, 
                        tipo_dptos c
          			WHERE   a.evento = ".$evento."
          			AND     a.tipo_pais_id=b.tipo_pais_id
          			AND     a.tipo_dpto_id=b.tipo_dpto_id
          			AND     a.tipo_mpio_id=b.tipo_mpio_id
          			AND     a.tipo_pais_id=c.tipo_pais_id
          			AND     a.tipo_dpto_id=c.tipo_dpto_id ";
      
      $resulta = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      if(!$resulta->EOF)
        $var6=$resulta->GetRowAssoc($ToUpper = false);
        
  		$var['apellidos_conductor']=$var6['apellidos_conductor'];
  		$var['nombres_conductor']=$var6['nombres_conductor'];
  		$var['tipo_id_conductor']=$var6['tipo_id_conductor'];
  		$var['conductor_id']=$var6['conductor_id'];
  		$var['direccion_conductor']=$var6['direccion_conductor'];
  		$var['telefono_conductor']=$var6['telefono_conductor'];
  		$var['tipo_pais_id_conductor']=$var6['tipo_pais_id'];
  		$var['tipo_dpto_id_conductor']=$var6['tipo_dpto_id'];
  		$var['tipo_mpio_id_conductor']=$var6['tipo_mpio_id'];
  		$var['municipio_conductor']=$var6['municipio'];
  		$var['departamento_conductor']=$var6['departamento'];
    	//FIN CONDUCTOR VEHICULO INVOLUCRADO
    		
    	//TRANSPORTE Y MOVILIZACION DE LA VICTIMA
      $query = "SELECT  a.*, 
                        e.descripcion AS descripcion_ambulancia
          			FROM    soat_ambulancias a, 
                        tipo_mpios b, 
                        tipo_dptos c,
                  			soat_eventos d,
                  			tipos_ambulancias e
          			WHERE   a.evento = $evento
          			AND     a.tipo_pais_id=b.tipo_pais_id
          			AND     a.tipo_dpto_id=b.tipo_dpto_id
          			AND     a.tipo_mpio_id=b.tipo_mpio_id
          			AND     a.tipo_pais_id=c.tipo_pais_id
          			AND     a.tipo_dpto_id=c.tipo_dpto_id
          			AND     a.ambulancia_id = d.ambulancia_id
          			AND     d.tipo_ambulancia_id = e.tipo_ambulancia_id ";
      
      $resulta = $dbconn->Execute($query);//la ultima evolucion
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      if(!$resulta->EOF)
        $var6=$resulta->GetRowAssoc($ToUpper = false);
  		
      $var['placa_ambulancia']=$var6['placa_ambulancia'];
  		$var['lugar_desde']=$var6['lugar_desde'];
  		$var['lugar_hasta']=$var6['lugar_hasta'];
  		$var['descripcion_ambulancia']=$var6['descripcion_ambulancia'];
  		$var['deszona_traslado']=$var['deszona'];

      if($var2['evolucion_id']!="")
      {
        $query = "SELECT  B.diagnostico_nombre AS ingreso, 
                          B.*
                  FROM    hc_diagnosticos_ingreso AS A,
                          diagnosticos AS B
                  WHERE   A.evolucion_id=".$var2['evolucion_id']."
                  AND     A.tipo_diagnostico_id=B.diagnostico_id
                  ORDER BY A.sw_principal DESC ";

        $resulta = $dbconn->Execute($query);//la primera evolucion
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        
        while(!$resulta->EOF)
        {
          $var4[]=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->MoveNext();
        }
	
  	    $var[diagnostio_id_ingreso] = $var4[0][diagnostico_id];
  	    $var[diagnostico_id_ingreso1] = $var4[1][diagnostico_id];
  	    $var[diagnostico_id_ingreso2] = $var4[2][diagnostico_id];
        

        $query = "SELECT  B.diagnostico_nombre AS egreso, 
                          B.*
                  FROM    hc_diagnosticos_egreso AS A,
                          diagnosticos AS B
                  WHERE   A.evolucion_id=".$var3['evolucion_id']."
                  AND     A.tipo_diagnostico_id=B.diagnostico_id
                  ORDER BY sw_principal DESC;";

        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        while(!$resulta->EOF)
        {
          $var5[]=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->MoveNext();
        }
        $var[diagnostio_id_egreso] = $var5[0][diagnostico_id];
        $var[diagnostico_id_egreso1] = $var5[1][diagnostico_id];
        $var[diagnostico_id_egreso2] = $var5[2][diagnostico_id];
        
        $query = "SELECT  c.nombre, 
                          c.tipo_id_tercero,
                          c.tercero_id,
                          c.tarjeta_profesional
                  FROM    hc_evoluciones AS A,
                          profesionales_usuarios b,
                          profesionales c
                  WHERE   A.evolucion_id=".$var3['evolucion_id']."
                  AND     A.usuario_id=B.usuario_id
                  AND     b.tipo_tercero_id = c.tipo_id_tercero 
                  AND     b.tercero_id = c.tercero_id ;";
      }
      $resulta = $dbconn->Execute($query);//la primera evolucion
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      if(!$resulta->EOF)
        $var4=$resulta->GetRowAssoc($ToUpper = false);
      
      $varmd_terceroid = ModuloGetVar('','','id_FIDUFOSYGA');
      $varmd_tipo = ModuloGetVar('','','tipo_FIDUFOSYGA');
  
      $query1 =" SELECT SUM(A2.total_factura) AS valor_factura
                 FROM   ingresos_soat A, 
                        cuentas B,
                        fac_facturas_cuentas A1,
                        fac_facturas A2,
                        planes C
                  WHERE C.sw_tipo_plan = '1'
                  AND   A.ingreso = B.ingreso
                  AND   B.numerodecuenta = A1.numerodecuenta
                  AND   A1.factura_fiscal = A2.factura_fiscal
                  AND   A1.prefijo = A2.prefijo
                  AND   A2.estado IN ('0','1')
                  AND   A1.empresa_id = A2.empresa_id
                  AND   C.plan_id = A2.plan_id
                  AND   A2.tipo_id_tercero||A2.tercero_id  != '".$varmd_tipo.$varmd_terceroid."'
                  AND   A.evento = ".$evento." 
                  AND   A.ingreso = ".$ingreso." ";
             
      $resulta1 = $dbconn->Execute($query1);//
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      if(!$resulta1->EOF)
      {
        $var5=$resulta1->GetRowAssoc($ToUpper = false);
        $resulta1->MoveNext();
      }
            
      $query2 = " SELECT  SUM(A2.total_factura) AS valor_facturaf
                  FROM    ingresos_soat A, 
                          cuentas B,
                          fac_facturas_cuentas A1,
                          fac_facturas A2,
                          planes C
                  WHERE   C.sw_tipo_plan = '1'
                  AND     A.ingreso = B.ingreso
                  AND     B.numerodecuenta = A1.numerodecuenta
                  AND     A1.factura_fiscal = A2.factura_fiscal
                  AND     A1.prefijo = A2.prefijo
                  AND     A1.empresa_id = A2.empresa_id
                  AND     C.plan_id = A2.plan_id
                  AND     A2.tipo_id_tercero || A2.tercero_id = '".$varmd_tipo.$varmd_terceroid."'
                  AND     A.evento = ".$evento." 
                  AND     A.ingreso = ".$ingreso." ";
      $resulta2 = $dbconn->Execute($query2);//
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      $var6 = array();
      if(!$resulta2->EOF)
      {
        $var6=$resulta2->GetRowAssoc($ToUpper = false);
        $resulta2->MoveNext();
      }
	    $var[nombre_medico] = $var4[nombre];
	    $var[tipo_id_tercero_medico] = $var4[tipo_id_tercero];
	    $var[tercero_id_medico] = $var4[tercero_id];
	    $var[tarjeta_profesional] = $var4[tarjeta_profesional];
      $var[valor_factura]= $var5[valor_factura];
      $var[valor_facturaf] = $var6[valor_facturaf]; 
      IncludeLib("reportes/soat_reclamo_dinero");
      GenerarSoatReclamoDinero($var);
        
      if($tipo_reporte == 0)
      {
        GenerarSoatReclamoDinero_forecat($var);
        return $var;
      }
      else
      {
        UNSET($_SESSION['REPORTES']['VARIABLE']);
        $Dir="cache/reclamacion_entidades_1.pdf";
        
        define('FPDF_FONTPATH','font/');
        if(!IncludeFile("classes/ReportesSoat/fpdf_reporte_soat.class.php"))
        {
          $this->error = "No se pudo inicializar la Clase de fpdf_reporte_soat";
          $this->mensajeDeError = "No se pudo Incluir el archivo : classes/ReportesSoat/fpdf_reporte_soat.class.php";
          return false;
        }
        $pdf= new fpdf_reporte_soat('P','mm','legal');
        
        $pdf->set_correcion_x(0.92);
        $pdf->set_correcion_y(0.60);
        $datos=$pdf->TraerDatosReclamacionEntidades($TipoDo,$Docume,$evento,$ingreso);
        $pdf->AddPage();
        $pdf->SetFont('Arial','',8);
        foreach($datos as $k=>$v)
        { 
          $pdf->Text_corregida($v[0],$v[1],$v[2]);
        }
            
        $pdf->Output($Dir,'F');
        return $var;
      }
    }
//
    function BuscarReportePdf($TipoDo,$Docume,$evento)
    {
        if(!IncludeLib("reportes/soat_reclamo_dinero1"))
        {echo 'ERROR : AL INCLUIR EL ARCHIVO DEL REPORTE.'; return false;}
        GenerarReporte($TipoDo,$Docume,$evento);
        return  true;
    }
//


    function ImprimirSoatEven()
    {
        if($_REQUEST['switch'] == 1)
        {
            $var=$this->BuscarReporteEventoSoat($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente'],
            $_SESSION['soat']['evento']['nombresoat']['paciente_id'],$_REQUEST['eventoeleg']);
        }
        else if($_REQUEST['switch'] == 2)
        {
            $var=$this->BuscarReporteEventoSoat($_SESSION['soat']['consumo']['nombresoat']['tipo_id_paciente'],
            $_SESSION['soat']['consumo']['nombresoat']['paciente_id'],$_REQUEST['eventoeleg']);
        }
        if (!IncludeFile("classes/reports/reports.class.php"))
        {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }
        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pdf');
        $reporte=$classReport->PrintReport($tipo_reporte='pdf',$tipo_modulo='app',$modulo='Soat',$reporte_name='soat_reclamo_dinero',$var,$impresora,$orientacion='P',$unidades='mm',$formato='letter',$html=1);
        if(!$reporte)
        {
            $this->error = $classReport->GetError();
            $this->mensajeDeError = $classReport->MensajeDeError();
            UNSET($classReport);
            return false;
        }
        $resultado=$classReport->GetExecResultado();
        UNSET($classReport);
        $this->MostrarDatosAdicional();
        return true;
    }

    function BuscarProfesional()//
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT A.tipo_id_tercero,
                A.tercero_id,
                B.nombre_tercero
                FROM profesionales AS A,
                terceros AS B
                WHERE A.tipo_id_tercero=B.tipo_id_tercero
                AND A.tercero_id=B.tercero_id
                AND (A.tipo_profesional='1'
                OR A.tipo_profesional='2')
                ORDER BY B.nombre_tercero;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }
    
    function BuscarProfesionalRemision()//
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT A.tipo_id_tercero||'||//'||A.tercero_id AS tercero_id,
                --A.tercero_id,
                A.nombre
                FROM profesionales AS A
                WHERE A.tipo_profesional IN ('1','2','3','4')
		AND A.estado = '1'
                ORDER BY A.nombre;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarAtencionMedica($evento,$ingreso)//
    {
      list($dbconn) = GetDBconn();
      //$dbconn->debug = true;
      $query = "SELECT A.razon_social,
              A.direccion,
              A.telefonos,
              B.departamento AS deparempre,
              C.municipio AS municempre
              FROM empresas AS A,
              tipo_dptos AS B,
              tipo_mpios AS C
              WHERE A.empresa_id='".$_SESSION['soa1']['empresa']."'
              AND A.tipo_pais_id=B.tipo_pais_id
              AND A.tipo_dpto_id=B.tipo_dpto_id
              AND A.tipo_pais_id=C.tipo_pais_id
              AND A.tipo_dpto_id=C.tipo_dpto_id
              AND A.tipo_mpio_id=C.tipo_mpio_id;";
      $resulta = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }
      while(!$resulta->EOF)
      {
          $var2=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->MoveNext();
      }
      $query = "SELECT  C.nombres_declara,
                        C.apellidos_declara,
                        C.tipo_id_paciente,
                        C.declara_id,
                        C.extipo_pais_id,
                        C.extipo_dpto_id,
                        C.extipo_mpio_id,
                        C.fecha_ingreso,
                        C.datos1_ta,
                        C.datos2_fc,
                        C.datos3_fr,
                        C.datos4_te,
                        C.datos5_conciencia,
                        C.datos6_glasgow,
                        C.estado_embriaguez,
                        C.diagnostico1,
                        C.diagnostico2,
                        C.diagnostico3,
                        C.diagnostico4,
                        C.diagnostico5,
                        C.diagnostico6,
                        C.diagnostico7,
                        C.diagnostico8,
                        C.diagnostico9,
                        C.diagnostico_def,
                        C.tipo_id_tercero,
                        C.tercero_id,
                        C.fecha_registro,
                        C.usuario_id,
                        A.fecha_accidente,
                        D.tarjeta_profesional,
                        E.nombre_tercero,
                        F.municipio AS expedida
                FROM    soat_accidente AS A,
                        soat_eventos AS B
                        LEFT JOIN soat_atencion_medica AS C ON
                        ( 
                          B.evento=C.evento
                        )
                        LEFT JOIN profesionales AS D ON
                        ( C.tipo_id_tercero=D.tipo_id_tercero
                          AND C.tercero_id=D.tercero_id)
                        LEFT JOIN terceros AS E ON
                        ( C.tipo_id_tercero=E.tipo_id_tercero
                          AND C.tercero_id=E.tercero_id)
                        LEFT JOIN tipo_mpios AS F ON
                        ( C.extipo_pais_id=F.tipo_pais_id
                          AND C.extipo_dpto_id=F.tipo_dpto_id
                          AND C.extipo_mpio_id=F.tipo_mpio_id)
                WHERE   B.evento=".$evento."
                AND     A.accidente_id=B.accidente_id;";
      $resulta = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }
      while(!$resulta->EOF)
      {
          $var=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->MoveNext();
      }
      $var['apellido'] = $_SESSION['soat']['pacisoat']['primer_apellido'].' '.$_SESSION['soat']['pacisoat']['segundo_apellido'];
      $var['nombre'] = $_SESSION['soat']['pacisoat']['primer_nombre'].' '.$_SESSION['soat']['pacisoat']['segundo_nombre'];
      $var['residencia_direccion'] = $_SESSION['soat']['pacisoat']['residencia_direccion'];
      $var['residencia_telefono'] = $_SESSION['soat']['pacisoat']['residencia_telefono'];
      $dpto=$this->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array(
          'Pais'=>$_SESSION['soat']['pacisoat']['tipo_pais_id'],
          'Dpto'=>$_SESSION['soat']['pacisoat']['tipo_dpto_id']));
      $mpio=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array(
          'Pais'=>$_SESSION['soat']['pacisoat']['tipo_pais_id'],
          'Dpto'=>$_SESSION['soat']['pacisoat']['tipo_dpto_id'],
          'Mpio'=>$_SESSION['soat']['pacisoat']['tipo_mpio_id']));
      $_SESSION['soat']['pacisoat']['departamento']=$var['departamento']=$dpto;
      $_SESSION['soat']['pacisoat']['municipio']=$var['municipio']=$mpio;
      
      $var['tipo_id_paciente_eve']=$_SESSION['soat']['evento']['TipoDocum'];
      $var['paciente_id_eve']=$_SESSION['soat']['evento']['Documento'];
      $var['lugar_expedicion_eve']=$_SESSION['soat']['pacisoat']['lugar_expedicion_documento'];
      $var['razon_social']=$var2['razon_social'];
      $var['direccion']=$var2['direccion'];
      $var['telefonos']=$var2['telefonos'];
      $var['deparempre']=$var2['deparempre'];
      $var['municempre']=$var2['municempre'];
      
      IncludeLib("reportes/soat_certificado_atencion");
      GenerarSoatAtencion($var);
      return $var;
    }

    function ValidarAtencionMedica()//
    {
        if(empty($_POST['fechaingre']))
        {
            $this->frmError["fechaingre"]=1;
        }
        else
        {
            $valfec=explode('/',$_POST['fechaingre']);
            $day=$valfec[0];
            $mon=$valfec[1];
            $yea=$valfec[2];
            if(checkdate($mon, $day, $yea)==0)
            {
                $_POST['fechaingre']='';
                $this->frmError["fechaingre"]=1;
            }
            else
            {
                $fec=date ("Y-m-d");
                if($fec < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                {
                    $_POST['fechaingre']='';
                    $this->frmError["fechaingre"]=1;
                }
                else
                {
                    $fecha = $yea.'-'.$mon.'-'.$day.' ';
                    $fechahora=1;
                }
            }
        }
        if($_POST['horario2']==-1||$_POST['minutero2']==-1)
        {
            $this->frmError["fechaingre"]=1;
        }
        else
        {
            if($fechahora==1)
            {
                $horac=intval(date("H"));
                $minac=intval(date("i"));
                $hor=intval($_POST['horario2']);
                $min=intval($_POST['minutero2']);
                if($horac<$hor AND $fec <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                {
                    $_POST['horario2']=-1;
                    $this->frmError["fechaingre"]=1;
                }
                else
                {
                    if($minac<$min AND $horac<=$hor AND $fec <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_POST['minutero2']=-1;
                        $this->frmError["fechaingre"]=1;
                    }
                }
            }
        }
        if($_POST['apelliprop']==NULL OR $_POST['nombreprop']==NULL)
        {
            $this->frmError["apelliprop"]=1;
        }
        if($_POST['documeprop']==NULL)
        {
            $this->frmError["documeprop"]=1;
        }
        if($_POST['tidocuprop']==NULL)
        {
            $this->frmError["tidocuprop"]=1;
        }
        if($_POST['noapmedico']==NULL)
        {
            $this->frmError["noapmedico"]=1;
        }
        if($this->frmError["fechaingre"]==1 || $this->frmError["apelliprop"]==1 ||
        $this->frmError["documeprop"]==1 || $this->frmError["tidocuprop"]==1 ||
        $this->frmError["noapmedico"]==1)
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            $this->uno=1;
            $this->AtencionMedica();
            return true;
        }
        else
        {
            $fecha .= $_POST['horario2'].':'.$_POST['minutero2'].':'.'00';
            if($_POST['paisE']==NULL)
            {
                $pais="NULL";
            }
            else
            {
                $pais="'".$_POST['paisE']."'";
            }
            if($_POST['dptoE']==NULL)
            {
                $dpto="NULL";
            }
            else
            {
                $dpto="'".$_POST['dptoE']."'";
            }
            if($_POST['mpioE']==NULL)
            {
                $mpio="NULL";
            }
            else
            {
                $mpio="'".$_POST['mpioE']."'";
            }
            $prof=explode(',',$_POST['noapmedico']);
            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();
            $this->frmError["MensajeError"]='';
            if($_REQUEST['atenmedi']==1)//guarda
            {
                $usuario=UserGetUID();
                $query = "INSERT INTO soat_atencion_medica
                        (evento,
                        nombres_declara,
                        apellidos_declara,
                        tipo_id_paciente,
                        declara_id,
                        extipo_pais_id,
                        extipo_dpto_id,
                        extipo_mpio_id,
                        fecha_ingreso,
                        datos1_ta,
                        datos2_fc,
                        datos3_fr,
                        datos4_te,
                        datos5_conciencia,
                        datos6_glasgow,
                        estado_embriaguez,
                        diagnostico1,
                        diagnostico2,
                        diagnostico3,
                        diagnostico4,
                        diagnostico5,
                        diagnostico6,
                        diagnostico7,
                        diagnostico8,
                        diagnostico9,
                        diagnostico_def,
                        tipo_id_tercero,
                        tercero_id,
                        fecha_registro,
                        usuario_id)
                        VALUES
                        (".$_SESSION['soat']['eventoelegCM'].",
                        '".$_POST['nombreprop']."',
                        '".$_POST['apelliprop']."',
                        '".$_POST['tidocuprop']."',
                        '".$_POST['documeprop']."',
                        $pais,
                        $dpto,
                        $mpio,
                        '".$fecha."',
                        '".$_POST['datos1']."',
                        '".$_POST['datos2']."',
                        '".$_POST['datos3']."',
                        '".$_POST['datos4']."',
                        '".$_POST['datos5']."',
                        '".$_POST['datos6']."',
                        '".$_POST['embriaguez']."',
                        '".$_POST['diagnos1']."',
                        '".$_POST['diagnos2']."',
                        '".$_POST['diagnos3']."',
                        '".$_POST['diagnos4']."',
                        '".$_POST['diagnos5']."',
                        '".$_POST['diagnos6']."',
                        '".$_POST['diagnos7']."',
                        '".$_POST['diagnos8']."',
                        '".$_POST['diagnos9']."',
                        '".$_POST['diagnosd']."',
                        '".$prof[0]."',
                        '".$prof[1]."',
                        '".date("Y-m-d H:i:s")."',
                        ".$usuario.");";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS ". $dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
            }
            else if($_REQUEST['atenmedi']==2)//modifica
            {
                $query = "UPDATE soat_atencion_medica SET
                        nombres_declara='".$_POST['nombreprop']."',
                        apellidos_declara='".$_POST['apelliprop']."',
                        tipo_id_paciente='".$_POST['tidocuprop']."',
                        declara_id='".$_POST['documeprop']."',
                        extipo_pais_id=$pais,
                        extipo_dpto_id=$dpto,
                        extipo_mpio_id=$mpio,
                        fecha_ingreso='".$fecha."',
                        datos1_ta='".$_POST['datos1']."',
                        datos2_fc='".$_POST['datos2']."',
                        datos3_fr='".$_POST['datos3']."',
                        datos4_te='".$_POST['datos4']."',
                        datos5_conciencia='".$_POST['datos5']."',
                        datos6_glasgow='".$_POST['datos6']."',
                        estado_embriaguez='".$_POST['embriaguez']."',
                        diagnostico1='".$_POST['diagnos1']."',
                        diagnostico2='".$_POST['diagnos2']."',
                        diagnostico3='".$_POST['diagnos3']."',
                        diagnostico4='".$_POST['diagnos4']."',
                        diagnostico5='".$_POST['diagnos5']."',
                        diagnostico6='".$_POST['diagnos6']."',
                        diagnostico7='".$_POST['diagnos7']."',
                        diagnostico8='".$_POST['diagnos8']."',
                        diagnostico9='".$_POST['diagnos9']."',
                        diagnostico_def='".$_POST['diagnosd']."',
                        tipo_id_tercero='".$prof[0]."',
                        tercero_id='".$prof[1]."'
                        WHERE evento=".$_SESSION['soat']['eventoelegCM'].";";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="OCURRIÓ UN ERROR AL MODIFICAR LOS DATOS ". $dbconn->ErrorMsg();
                    $dbconn->RollBackTrans();
                }
            }
            $dbconn->CommitTrans();
            if($this->frmError["MensajeError"]==NULL)
            {
                $this->frmError["MensajeError"]="DATOS INSERTADOS O MODIFICADOS CORRECTAMENTE";
            }
            $this->uno=1;
            $this->DatosAccidente();
            return true;
        }
    }

    function EpicrisisSoat()//
    {
        $_SESSION['EPICRISIS']['RETORNO']['contenedor']='app';//app
        $_SESSION['EPICRISIS']['RETORNO']['modulo']='Soat';//Triage
        $_SESSION['EPICRISIS']['RETORNO']['tipo']='user';//user
        $_SESSION['EPICRISIS']['RETORNO']['metodo']='SoatAdmision';//LlamaFormaIngresoEventos
        $this->ReturnMetodoExterno('app','EJEMPLO','user','Revisar',array('ingreso'=>$_REQUEST['ingreso']));
        return true;
    }

    function SalarioAnoSoat($ano)//Funcion que establece el saldo inicial al crear el evento
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT salario_mes
                FROM salario_minimo_ano
                WHERE ano='".$ano."';";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $salmin=doubleval($resulta->fields[0]);
        $salmin=number_format(($salmin/4), 2, '.', '');
        return $salmin;
    }

    function ValidarDatosInformeSoat()//
    {
        list($dbconn) = GetDBconn();
        $var=explode('/',$_POST['fechadradi']);
        $day=$var[0];
        $mon=$var[1];
        $yea=$var[2];
        if(checkdate($mon, $day, $yea)==0)
        {
            $_POST['fechadradi']='';
            $this->frmError["fechadradi"]=1;
        }
        else
        {
            $fech=date("Y-m-d");
            if($fech < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
            {
                $_POST['fechadradi']='';
                $this->frmError["fechadradi"]=1;
            }
            else
            {
                $_SESSION['soat']['reportes']['fechadradi']=$yea.'-'.$mon.'-'.$day;
                $_SESSION['soat']['reportes']['fechadrad2']=$_POST['fechadradi'];
            }
        }
        $var=explode('/',$_POST['fechainici']);
        $day=$var[0];
        $mon=$var[1];
        $yea=$var[2];
        if(checkdate($mon, $day, $yea)==0)
        {
            $_POST['fechainici']='';
            $this->frmError["fechainici"]=1;
        }
        else
        {
            $fech=date("Y-m-d");
            if($fech < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
            {
                $_POST['fechainici']='';
                $this->frmError["fechainici"]=1;
            }
            else
            {
                $_SESSION['soat']['reportes']['fechainici']=$yea.'-'.$mon.'-'.$day;
                $_SESSION['soat']['reportes']['fechainic2']=$_POST['fechainici'];
            }
        }
        $var=explode('/',$_POST['fechafinal']);
        $day=$var[0];
        $mon=$var[1];
        $yea=$var[2];
        if(checkdate($mon, $day, $yea)==0)
        {
            $_POST['fechafinal']='';
            $this->frmError["fechafinal"]=1;
        }
        else
        {
            $fech=date("Y-m-d");
            if($fech < date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
            {
                $_POST['fechafinal']='';
                $this->frmError["fechafinal"]=1;
            }
            else
            {
                $_SESSION['soat']['reportes']['fechafinal']=$yea.'-'.$mon.'-'.$day;
                $_SESSION['soat']['reportes']['fechafina2']=$_POST['fechafinal'];
            }
        }
        $var=explode('/',$_POST['periodorec']);
        $mon=$var[0];
        $yea=$var[1];
        if(checkdate($mon, 1, $yea)==0)
        {
            $_POST['periodorec']='';
            $this->frmError["periodorec"]=1;
        }
        else
        {
            $fech=date("Y-m-d");
            if($fech < date("Y-m-d", mktime(1,1,1,$mon,1,$yea)))
            {
                $_POST['periodorec']='';
                $this->frmError["periodorec"]=1;
            }
            else
            {
                $_SESSION['soat']['reportes']['salariomon']=$this->SalarioAnoSoat($yea);
                $_SESSION['soat']['reportes']['periodorec']=$yea.'-'.$mon;
                $query ="SELECT A.ingreso,
                        B.fecha_ingreso,
                        C.fecha_cierre,
                        F.fecha_registro
                        FROM ingresos_soat AS A,
                        ingresos AS B,
                        cuentas AS C,
                        planes AS D,
                        fac_facturas_cuentas AS E,
                        fac_facturas AS F
                        WHERE A.ingreso=B.ingreso
                        AND B.ingreso=C.ingreso
                        AND C.empresa_id='".$_SESSION['soa1']['empresa']."'
                        AND C.estado='0'
                        AND C.plan_id=D.plan_id
                        AND C.numerodecuenta=E.numerodecuenta
                        AND E.prefijo=F.prefijo
                        AND E.factura_fiscal=F.factura_fiscal
                        AND F.fecha_registro LIKE '".$_SESSION['soat']['reportes']['periodorec']."%'
                        AND F.total_factura<=".$_SESSION['soat']['reportes']['salariomon']."
                        ORDER BY A.ingreso;";
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
                    $datosreporte[$i]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                    $resu1=explode(' ',$datosreporte[$i]['fecha_ingreso']);
                    $resu2=explode(' ',$datosreporte[$i]['fecha_cierre']);
                    $resu3=explode(' ',$datosreporte[$i]['fecha_registro']);
                    $datosreporte[$i]['fecha_ingreso']=$resu1[0];
                    $datosreporte[$i]['fecha_cierre']=$resu2[0];
                    $datosreporte[$i]['fecha_registro']=$resu3[0];
                    $i++;
                }
            }
        }
        if($_SESSION['soat']['reportes']['fechainici']<>NULL AND $_SESSION['soat']['reportes']['fechafinal']<>NULL)
        {
            $j=0;
            for($i=0;$i<sizeof($datosreporte);$i++)
            {
                if($datosreporte[$i]['fecha_registro']>=$_SESSION['soat']['reportes']['fechainici']
                AND $datosreporte[$i]['fecha_registro']<=$_SESSION['soat']['reportes']['fechafinal'])
                {
                    $datosdefinit[$j]=$datosreporte[$i];
                    $j++;
                }
            }
        }
        if(sizeof($datosdefinit)>0)
        {
            /*$query ="SELECT NEXTVAL ('soat_fosyga_id_seq');";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="OCURRIo UN ERROR AL GENERAR EL NoMERO DE REMISIoN";
                $_SESSION['soat']['reportes']['numeroradi']='';
            }
            else
            {
                $_SESSION['soat']['reportes']['numeroradi']=print str_pad($resulta->fields[0], 6, "0", STR_PAD_LEFT);
                echo $_SESSION['soat']['reportes']['numeroradi'];
            }*/
            $_SESSION['soat']['reportes']['numeroradi']=1;
            $_SESSION['soat']['reportes']['numeroradi']=str_pad($_SESSION['soat']['reportes']['numeroradi'], 6, "0", STR_PAD_LEFT);//print
        }
        else
        {
            $this->frmError["MensajeError"]="LA CONSULTA NO ARROJÓ RESULTADOS";
        }
        $_SESSION['soat']['reportes']['datovector']=$datosdefinit;
        if($_POST['fechadradi']==NULL||$_POST['periodorec']==NULL||
        $_POST['fechainici']==NULL||$_POST['fechafinal']==NULL||
        empty($_SESSION['soat']['reportes']['numeroradi']))
        {
            if($this->frmError["MensajeError"]==NULL)
            {
                $this->frmError["MensajeError"]="FECHA(S) CON FORMATO(S) NO VÁLIDO(S) O VALOR(ES) VACIO(S)";
            }
            $this->uno=1;
            $this->DatosInformeSoat();
            return true;
        }
        else
        {
            $this->GenerarInformeSoat();
            return true;
        }
    }

    function BuscarDatosInformeSoat()//
    {
        $fileName =  "classes/rips/rips.class.php";
        if(!IncludeFile($fileName))
        {
                $this->error = "No se pudo cargar el Modulo";
                $this->mensajeDeError = "El archivo de vistas '" . $fileName . "' no existe.";
                return false;
        }
        $clase="rips";
        $rips = new $clase();
        list($dbconn) = GetDBconn();
        $query ="SELECT A.razon_social,
                A.codigo_sgsss,
                A.tipo_id_tercero,
                A.id
                FROM empresas AS A
                WHERE A.empresa_id='".$_SESSION['soa1']['empresa']."';";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $ripdatos1=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        $vectorips='';
        $vectorips.=str_pad($ripdatos1['razon_social'], 60, " ", STR_PAD_RIGHT).",".str_pad($ripdatos1['codigo_sgsss'], 12, " ", STR_PAD_RIGHT).",".str_pad($ripdatos1['id'], 20, " ", STR_PAD_RIGHT).",".$_SESSION['soat']['reportes']['fechainic2'].",".$_SESSION['soat']['reportes']['fechafina2'].",".$_SESSION['soat']['reportes']['fechadrad2'].","."AA".$_SESSION['soat']['reportes']['numeroradi'].",".sizeof($_SESSION['soat']['reportes']['datovector'])."\x0a";
        $vectorips.=str_pad($ripdatos1['razon_social'], 60, " ", STR_PAD_RIGHT).",".str_pad($ripdatos1['codigo_sgsss'], 12, " ", STR_PAD_RIGHT).",".str_pad($ripdatos1['id'], 20, " ", STR_PAD_RIGHT).",".$_SESSION['soat']['reportes']['fechainic2'].",".$_SESSION['soat']['reportes']['fechafina2'].",".$_SESSION['soat']['reportes']['fechadrad2'].","."VH".$_SESSION['soat']['reportes']['numeroradi'].",".sizeof($_SESSION['soat']['reportes']['datovector'])."\x0a";
        $vectorips.=str_pad($ripdatos1['razon_social'], 60, " ", STR_PAD_RIGHT).",".str_pad($ripdatos1['codigo_sgsss'], 12, " ", STR_PAD_RIGHT).",".str_pad($ripdatos1['id'], 20, " ", STR_PAD_RIGHT).",".$_SESSION['soat']['reportes']['fechainic2'].",".$_SESSION['soat']['reportes']['fechafina2'].",".$_SESSION['soat']['reportes']['fechadrad2'].","."AV".$_SESSION['soat']['reportes']['numeroradi'].",".sizeof($_SESSION['soat']['reportes']['datovector'])."\x0a";
        $rips->AbrirArchivo('cache/soat_fosyga/AC'.$_SESSION['soat']['reportes']['numeroradi'].'.txt','w+');
        $rips->EscribirArchivo($vectorips);
        $rips->CerrarArchivo();
        $query ="SELECT A.ingreso,
                G.tipo_id_paciente,
                G.paciente_id,
                H.primer_apellido,
                H.segundo_apellido,
                H.primer_nombre,
                H.segundo_nombre,
                H.sexo_id,
                H.residencia_direccion,
                H.tipo_dpto_id AS dptopaci,
                H.tipo_mpio_id AS mpiopaci,
                H.residencia_telefono,
                H.fecha_nacimiento,
                01 AS tipo_evento,
                I.sitio_accidente,
                I.fecha_accidente,
                I.tipo_dpto_id AS dptoacci,
                I.tipo_mpio_id AS mpioacci,
                I.zona,
                I.informe_accidente
                FROM ingresos_soat AS A,
                ingresos AS B,
                cuentas AS C,
                planes AS D,
                fac_facturas_cuentas AS E,
                fac_facturas AS F,
                soat_eventos AS G,
                pacientes AS H,
                soat_accidente AS I
                WHERE A.ingreso=B.ingreso
                AND B.ingreso=C.ingreso
                AND C.empresa_id='".$_SESSION['soa1']['empresa']."'
                AND C.estado='0'
                AND C.plan_id=D.plan_id
                AND C.numerodecuenta=E.numerodecuenta
                AND E.prefijo=F.prefijo
                AND E.factura_fiscal=F.factura_fiscal
                AND F.fecha_registro LIKE '".$_SESSION['soat']['reportes']['periodorec']."%'
                AND F.total_factura<=".$_SESSION['soat']['reportes']['salariomon']."
                AND A.evento=G.evento
                AND G.tipo_id_paciente=H.tipo_id_paciente
                AND G.paciente_id=H.paciente_id
                AND G.accidente_id=I.accidente_id
                ORDER BY A.ingreso;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $ripdatos2[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        $vectorips='';
        $j=0;
        for($i=0;$i<sizeof($ripdatos2);$i++)
        {
            if($_SESSION['soat']['reportes']['datovector'][$j]['ingreso']==$ripdatos2[$i]['ingreso'])
            {
                $resu1=explode(' ',$ripdatos2[$i]['fecha_accidente']);
                $resu2=explode('-',$resu1[0]);
                $resu3=$resu2[2].'/'.$resu2[1].'/'.$resu2[0];
                $resu2=explode(':',$resu1[1]);
                $resu1=$resu2[0].':'.$resu2[1];
                $edadacci=CalcularEdad($ripdatos2[$i]['fecha_nacimiento'],$ripdatos2[$i]['fecha_accidente']);
                $vectorips.=str_pad($ripdatos1['codigo_sgsss'], 12, " ", STR_PAD_RIGHT).",".$ripdatos2[$i]['tipo_id_paciente'].",".str_pad($ripdatos2[$i]['paciente_id'], 20, " ", STR_PAD_RIGHT).",".str_pad($ripdatos2[$i]['primer_apellido'], 30, " ", STR_PAD_RIGHT).",".str_pad($ripdatos2[$i]['segundo_apellido'], 30, " ", STR_PAD_RIGHT).
                ",".str_pad($ripdatos2[$i]['primer_nombre'], 20, " ", STR_PAD_RIGHT).",".str_pad($ripdatos2[$i]['segundo_nombre'], 20, " ", STR_PAD_RIGHT).",".str_pad($edadacci['anos'], 3, " ", STR_PAD_RIGHT).",".$edadacci['unidad_rips'].",".$ripdatos2[$i]['sexo_id'].",".str_pad($ripdatos2[$i]['residencia_direccion'], 40, " ", STR_PAD_RIGHT).
                ",".$ripdatos2[$i]['dptopaci'].",".$ripdatos2[$i]['mpiopaci'].",".str_pad($ripdatos2[$i]['residencia_telefono'], 9, " ", STR_PAD_RIGHT).",".$ripdatos2[$i]['tipo_evento'].",".str_pad($ripdatos2[$i]['sitio_accidente'], 60, " ", STR_PAD_RIGHT).",".$resu3.",".$resu1.",".$ripdatos2[$i]['dptoacci'].",".$ripdatos2[$i]['mpioacci'].
                ",".$ripdatos2[$i]['zona'].",".str_pad($ripdatos2[$i]['informe_accidente'], 255, " ", STR_PAD_RIGHT)."\x0a";
                $j++;
            }
        }
        $rips->AbrirArchivo('cache/soat_fosyga/AA'.$_SESSION['soat']['reportes']['numeroradi'].'.txt','w+');
        $rips->EscribirArchivo($vectorips);
        $rips->CerrarArchivo();
        $query ="SELECT A.ingreso,
                G.tipo_id_paciente,
                G.paciente_id,
                G.asegurado,
                H.marca_vehiculo,
                H.placa_vehiculo,
                H.tipo_vehiculo,
                I.nombre_tercero,
                H.poliza,
                H.vigencia_desde,
                H.vigencia_hasta,
                J.apellidos_propietario,
                J.nombres_propietario,
                J.tipo_id_propietario,
                J.propietario_id,
                J.tipo_dpto_id AS dptoprop,
                J.tipo_mpio_id AS mpioprop,
                J.direccion_propietario,
                J.telefono_propietario,
                K.apellidos_conductor,
                K.nombres_conductor,
                K.tipo_id_conductor,
                K.conductor_id,
                K.tipo_dpto_id AS dptocond,
                K.tipo_mpio_id AS mpiocond,
                K.direccion_conductor,
                K.telefono_conductor
                FROM ingresos_soat AS A,
                ingresos AS B,
                cuentas AS C,
                planes AS D,
                fac_facturas_cuentas AS E,
                fac_facturas AS F,
                soat_eventos AS G
                LEFT JOIN soat_vehiculo_propietario AS J ON
                (G.evento=J.evento)
                LEFT JOIN soat_vehiculo_conductor AS K ON
                (G.evento=K.evento),
                soat_polizas AS H,
                terceros AS I
                WHERE A.ingreso=B.ingreso
                AND B.ingreso=C.ingreso
                AND C.empresa_id='".$_SESSION['soa1']['empresa']."'
                AND C.estado='0'
                AND C.plan_id=D.plan_id
                AND C.numerodecuenta=E.numerodecuenta
                AND E.prefijo=F.prefijo
                AND E.factura_fiscal=F.factura_fiscal
                AND F.fecha_registro LIKE '".$_SESSION['soat']['reportes']['periodorec']."%'
                AND F.total_factura<=".$_SESSION['soat']['reportes']['salariomon']."
                AND A.evento=G.evento
                AND G.poliza=H.poliza
                AND H.tipo_id_tercero=I.tipo_id_tercero
                AND H.tercero_id=I.tercero_id
                ORDER BY A.ingreso;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $ripdatos3[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        $vectorips='';
        $j=0;
        for($i=0;$i<sizeof($ripdatos3);$i++)
        {
            if($_SESSION['soat']['reportes']['datovector'][$j]['ingreso']==$ripdatos3[$i]['ingreso'])
            {
                $resu1=explode('-',$ripdatos3[$i]['vigencia_desde']);
                $resu2=$resu1[2].'/'.$resu1[1].'/'.$resu1[0];
                $resu1=explode('-',$ripdatos3[$i]['vigencia_hasta']);
                $resu3=$resu1[2].'/'.$resu1[1].'/'.$resu1[0];
                $prop1=explode(' ',$ripdatos3[$i]['apellidos_propietario']);
                $prop2=explode(' ',$ripdatos3[$i]['nombres_propietario']);
                $cond1=explode(' ',$ripdatos3[$i]['apellidos_conductor']);
                $cond2=explode(' ',$ripdatos3[$i]['nombres_conductor']);
                $vectorips.=str_pad($ripdatos1['codigo_sgsss'], 12, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['tipo_id_paciente'], 2, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['paciente_id'], 20, " ", STR_PAD_RIGHT).",".$ripdatos3[$i]['asegurado'].",".str_pad($ripdatos3[$i]['marca_vehiculo'], 15, " ", STR_PAD_RIGHT).
                ",".str_pad($ripdatos3[$i]['placa_vehiculo'], 8, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['tipo_vehiculo'], 20, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['nombre_tercero'], 40, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['poliza'], 20, " ", STR_PAD_RIGHT).",".str_pad($resu2, 10, " ", STR_PAD_RIGHT).
                ",".str_pad($resu3, 10, " ", STR_PAD_RIGHT).",".str_pad($prop1[0], 30, " ", STR_PAD_RIGHT).",".str_pad($prop1[1], 30, " ", STR_PAD_RIGHT).",".str_pad($prop2[0], 20, " ", STR_PAD_RIGHT).",".str_pad($prop2[1], 20, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['tipo_id_propietario'], 2, " ", STR_PAD_RIGHT).
                ",".str_pad($ripdatos3[$i]['propietario_id'], 20, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['dptoprop'], 2, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['mpioprop'], 3, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['direccion_propietario'], 40, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['telefono_propietario'], 9, " ", STR_PAD_RIGHT).
                ",".str_pad($cond1[0], 30, " ", STR_PAD_RIGHT).",".str_pad($cond1[1], 30, " ", STR_PAD_RIGHT).",".str_pad($cond2[0], 20, " ", STR_PAD_RIGHT).",".str_pad($cond2[1], 20, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['tipo_id_conductor'], 2, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['conductor_id'], 20, " ", STR_PAD_RIGHT).
                ",".str_pad($ripdatos3[$i]['dptocond'], 2, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['mpiocond'], 3, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['direccion_conductor'], 40, " ", STR_PAD_RIGHT).",".str_pad($ripdatos3[$i]['telefono_conductor'], 9, " ", STR_PAD_RIGHT)."\x0a";
                $j++;
            }
        }
        $rips->AbrirArchivo('cache/soat_fosyga/VH'.$_SESSION['soat']['reportes']['numeroradi'].'.txt','w+');
        $rips->EscribirArchivo($vectorips);
        $rips->CerrarArchivo();
        $var['fechadradi']=$_SESSION['soat']['reportes']['fechadradi'];
        $var['numeroradi']=$_SESSION['soat']['reportes']['numeroradi'];
        $var['periodorec']=$_SESSION['soat']['reportes']['periodorec'];
        $var['fechainici']=$_SESSION['soat']['reportes']['fechainici'];
        $var['fechafinal']=$_SESSION['soat']['reportes']['fechafinal'];
        $var['datovector']=$_SESSION['soat']['reportes']['datovector'];
        $var['salariomon']=$_SESSION['soat']['reportes']['salariomon'];
        $var['empresa']=$_SESSION['soa1']['empresa'];
        UNSET($_SESSION['soat']['reportes']);
        return $var;
    }
    //------------nuevo dar
    function RetornoPacientes()
    {
                //$_SESSION['soat']['evento']['plan_id']=$_SESSION['PACIENTES']['PACIENTE']['plan_id'];
                $_SESSION['soat']['evento']['Documento']=$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
                $_SESSION['soat']['evento']['TipoDocum']=$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
                
                //si paso   
                if($_SESSION['PACIENTES']['RETORNO']['PASO'])
                {
                            unset($_SESSION['PACIENTES']);
                            $_SESSION['soat']['pantalla']=1;
                            $this->IngresaDatosAccidente();
                            return true;
                }
                //no paso (cancelaron)
                unset($_SESSION['PACIENTES']);
                $this->DatosAccidentado();
                return true;    
    }
    
    function LlamarModificarAmbulancia()
    {
            $_REQUEST['ambugumo']=2;
            $_SESSION['soat']['ambuverM']=$_REQUEST['ambulancia'];
            
            $this->ModificarDatosEventoAmb();
            return true;
    }
    
    function ConsultarConsumosInternos($evento)//Funcion que establece el saldo inicial al crear el evento
    {
        //$evento=$_REQUEST[eventoeleg];
        //$tipodocumento=$_REQUEST[tipodocumento];
        //$documento=$_REQUEST[documento];
        //$saldo=$_REQUEST[saldo];
        list($dbconn) = GetDBconn();
/*     echo   $query = "SELECT A.prefijo, 
                                            A.factura_fiscal, 
                                            A.total_factura,
                                            B.saldo,
                                            B.saldo_inicial
                            FROM soat_facturas_ingresos AS A,
                                    soat_eventos AS B
                            WHERE B.evento=$evento 
                            AND B.evento=A.evento;";*/
        $query = "SELECT A.prefijo, A.factura_fiscal, 
                        A.total_factura, B.saldo, 
                        B.saldo_inicial, 
                        D.numerodecuenta,
                        D.fecha_registro 
                    FROM soat_facturas_ingresos AS A, 
                        soat_eventos AS B, 
                        fac_facturas_cuentas C, cuentas D 
                    WHERE B.evento = $evento 
                    AND B.evento = A.evento 
                    AND A.prefijo = C.prefijo 
                    AND A.factura_fiscal = C.factura_fiscal
                    AND C.numerodecuenta = D.numerodecuenta;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return $vect;
        }
        else
        {
            while(!$resulta->EOF)
            {
                    $vect[]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
            }
            //$this->FormaCargosInternos($tipodocumento,$documento,$saldo,$vect);
            return $vect;
        }
    }

    function ConsultarConsumosHospitalizacion($evento)
    {
        list($dbconn) = GetDBconn();
/*      $query = "SELECT *, B.descripcion AS descargo,
                                    C.descripcion AS desdepartamento, 
                                    D.descripcion AS destarifario
                            FROM  soat_cargos_atencion AS A, tarifarios_detalle B,
                                        departamentos C, tarifarios D
                            WHERE A.evento=$evento
                            AND A.tarifario_id = B.tarifario_id
                            AND A.cargo = B.cargo
                            AND A.departamento = C.departamento
                            AND A.tarifario_id = D.tarifario_id;";*/
        $query = "SELECT B.numerodecuenta, B.valor_total_cargos
                            FROM  soat_cargos_atencion AS A,
                                        cuentas B
                            WHERE A.evento=$evento
                            AND A.numerodecuenta = B.numerodecuenta;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return $vect;
        }
        else
        {
            while(!$resulta->EOF)
            {
                    $vect[]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
            }
/*            $query = "SELECT *, B.descripcion AS descargo,
                                        C.descripcion AS desdepartamento, 
                                        D.descripcion AS destarifario, DATE(E.fecha_cargo) AS fcargo
                                FROM  soat_cargos_atencion AS A, tarifarios_detalle B,
                                            departamentos C, tarifarios D, cuentas_detalle E
                                WHERE A.evento=$evento
                                AND A.tarifario_id = B.tarifario_id
                                AND A.cargo = B.cargo
                                AND A.departamento = C.departamento
                                AND A.tarifario_id = D.tarifario_id
                                AND A.transaccion = E.transaccion;";*/
             $query = "SELECT F.prefijo,F.factura_fiscal,
                                G.total_factura,*, 
                                B.descripcion AS descargo, 
                                C.descripcion AS desdepartamento, 
                                D.descripcion AS destarifario, 
                                DATE(E.fecha_cargo) AS fcargo,
                                H.fecha_registro 
                    FROM soat_cargos_atencion AS A 
                        LEFT JOIN fac_facturas_cuentas F 
                            ON (A.numerodecuenta = F.numerodecuenta) 
                        LEFT JOIN fac_facturas G 
                            ON (F.prefijo = G.prefijo 
                                AND F.factura_fiscal = G.factura_fiscal), 
                        tarifarios_detalle B, 
                        departamentos C, 
                        tarifarios D, 
                        cuentas_detalle E,
                        cuentas H
                    WHERE A.evento = $evento 
                    AND A.tarifario_id = B.tarifario_id 
                    AND A.cargo = B.cargo 
                    AND A.departamento = C.departamento 
                    AND A.tarifario_id = D.tarifario_id 
                    AND A.transaccion = E.transaccion
                    AND A.numerodecuenta = H.numerodecuenta;";
            $resulta = $dbconn->Execute($query);
            while(!$resulta->EOF)
            {
                    $vect[detalle][]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
            }
            return $vect;
        }
    }
	function ModificarDatosIngreso()
	{
		$evento = $_REQUEST['evento'];
		list($dbconn) = GetDBconn();
		$query = "SELECT A.ingreso AS ingreso_id,
				B.fecha_ingreso,
				C.via_ingreso_nombre,
				E.evolucion_id,
				I.diagnostico_nombre AS ingreso,
				I.diagnostico_id as diagnostico_id_ingreso,
				J.fecha_registro AS fecha_cierre
			FROM ingresos_soat AS A,
				vias_ingreso AS C,
				ingresos AS B
				LEFT JOIN hc_evoluciones AS E ON
				(
					B.ingreso=E.ingreso
				)
				LEFT JOIN hc_diagnosticos_ingreso AS F ON
				(
					E.evolucion_id = F.evolucion_id
				)
				LEFT JOIN diagnosticos AS I ON
				(
					F.tipo_diagnostico_id = I.diagnostico_id
				)
				LEFT JOIN ingresos_salidas AS J ON
				(
					B.ingreso = J.ingreso
				)
			WHERE A.evento=".$evento."
			AND A.ingreso=B.ingreso
			AND B.via_ingreso_id=C.via_ingreso_id
			AND F.sw_principal='1'
			AND E.estado IN ('0')
			ORDER BY E.fecha DESC;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			echo $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
		$var2=$resulta->GetRowAssoc($ToUpper = false);
		$resulta->MoveNext();
		}
		//FECHA INGRESO
		$fecha=explode(' ',$var2['fecha_ingreso']);
		$var['hora_ingreso']=$fecha[1];
		$fecha=explode('-',$fecha[0]);
		$var['fecha_ingreso']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
		//$var['tratamiento']=$var2['via_ingreso_nombre'];
		$fecha=explode(' ',$var2['fecha_cierre']);
		$fechaE=explode('-',$fecha[0]);
		$var['fecha_egreso']=$fechaE[2].'/'.$fechaE[1].'/'.$fechaE[0];
		$var['diagnostico_id_ingreso']=$var2['diagnostico_id_ingreso'];
		
		
		$query = "SELECT A.ingreso AS ingreso_id,
				B.fecha_ingreso,
				C.via_ingreso_nombre,
				E.fecha_cierre,
				E.evolucion_id,
				I.diagnostico_nombre AS egreso
				,I.diagnostico_id as diagnostico_id_egreso
			FROM ingresos_soat AS A,
				vias_ingreso AS C,
				ingresos AS B
				LEFT JOIN hc_evoluciones AS E ON
				(
					B.ingreso=E.ingreso
				)
				LEFT JOIN hc_diagnosticos_egreso AS G ON
				(
					E.evolucion_id = G.evolucion_id 
					
				)
				LEFT JOIN diagnosticos AS I ON
				(
					G.tipo_diagnostico_id = I.diagnostico_id
				)
			WHERE A.evento=".$evento."
			AND A.ingreso=B.ingreso
			AND B.via_ingreso_id=C.via_ingreso_id
			AND G.sw_principal='1'
			AND E.estado IN ('0')
			ORDER BY E.fecha DESC;";//ORDER BY A.ingreso
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			echo $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
	
		while(!$resulta->EOF)
		{
			$var3=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		$var['dias_estancia']=abs($var['fecha_egreso']-$var['fecha_ingreso']);
		//DIAGNOSTICOS
		$var['desc_diagnostico_in'] = $var2['ingreso'];
		if(!empty($var3['egreso']))
		{
			$var['desc_diagnostico_de'] = $var3['egreso'];
		}
		else
		{
			$var['desc_diagnostico_de'] = $var3['ingreso'];
		}
		$var['diagnostico_id_egreso'] = $var3['diagnostico_id_egreso'];
		$var['ingreso'] = $var2['ingreso_id'];
		$this->FrmEditaDatosIngreso($var);
		return true;
	}
	
	function BusquedaDiagnosticos()
	{
		//print_r($_REQUEST);exit;
	}
	
	function Busqueda_Diagnosticos()
	{


		list($dbconn) = GetDBconn();
		$codigo = STRTOUPPER ($_REQUEST['codigo']);
		$diagnostico  =STRTOUPPER($_REQUEST['diagnostico']);

		$busqueda1 = '';
		$busqueda2 = '';

		if ($codigo != '')
		{
			$busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
		}

		if (($diagnostico != '') AND ($codigo != ''))
		{
			if (eregi('%',$diagnostico))
			{
				$busqueda2 ="AND diagnostico_nombre LIKE '$diagnostico'";
			}
			else
			{
				$busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
			}
		}

		if (($diagnostico != '') AND ($codigo == ''))
		{
			if (eregi('%',$diagnostico))
			{
				$busqueda2 ="WHERE diagnostico_nombre LIKE '$diagnostico'";
			}
			else
			{
				$busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
			}
		}

		if(empty($_REQUEST['conteo'.$pfj]))
		{
			$query = "SELECT count(*)
			FROM diagnosticos
			$busqueda1 $busqueda2";

			$resulta = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			list($this->conteo)=$resulta->fetchRow();
		}
		else
		{
			$this->conteo=$_REQUEST['conteo'.$pfj];
		}
		if(!$_REQUEST['Of'.$pfj])
		{
			$Of='0';
		}
		else
		{
			$Of=$_REQUEST['Of'.$pfj];
			if($Of > $this->conteo)
			{
				$Of=0;
				$_REQUEST['Of'.$pfj]=0;
				$_REQUEST['paso1'.$pfj]=1;
			}
		}


		//filtro por clasificacion de diagnosticos
		$filtro='';
// 		if(empty($busqueda1) AND empty($busqueda2))
// 		{
// 			$filtro = "WHERE (sexo_id='".$this->datosPaciente['sexo_id']."' OR sexo_id is null)
// 					 AND   (edad_max>=".$edad_paciente[edad_en_dias]." OR edad_max is null)
// 					 AND   (edad_min<=".$edad_paciente[edad_en_dias]." OR edad_min is null)";
// 		}
// 		else
// 		{
// 			$filtro = "AND (sexo_id='".$this->datosPaciente['sexo_id']."' OR sexo_id is null)
// 					 AND (edad_max>=".$edad_paciente[edad_en_dias]." OR edad_max is null)
// 					 AND (edad_min<=".$edad_paciente[edad_en_dias]." OR edad_min is null)";
// 		}

// 		$filtro1='';
// 		if(!empty($this->capitulo))
// 		{
// 			$filtro1 = " AND (B.capitulo='".$this->capitulo."' OR B.capitulo is null)";
// 		}
// 		if(!empty($this->grupo))
// 		{
// 			$filtro1 .= " AND (B.grupo='".$this->grupo."' OR B.grupo is null)";
// 		}
// 		if(!empty($this->categoria))
// 		{
// 			$filtro1 .= " AND (B.categoria='".$this->categoria."' OR B.categoria is null)";
// 		}

		$query = "SELECT diagnostico_id, diagnostico_nombre
                    FROM diagnosticos
                    $busqueda1 $busqueda2
                    $filtro $filtro1
                    order by diagnostico_id
                    --LIMIT ".$this->limit." OFFSET $Of
		    ;";
		$resulta = $dbconn->Execute($query);
		$conteo=$resulta->RecordCount();
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}

		if($conteo==='0')
		{
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			$this->FrmBusquedaDiagnosticos();
			return false;
		}
		$this->FrmBusquedaDiagnosticos($var);
		return true;
	}
	
	/***
	****
	***/
	function GuardarDatosIngreso()
	{
		//print_r($_REQUEST); exit;
		
	}
	
	/***
	****
	***/
	function AsignarValores()
	{
		//print_r($_REQUEST); exit;
		for($i=0;$i<$_REQUEST['vectorD']; $i++)
		{
			$dat = explode('||//',$_REQUEST['descripcion'.$i]);
			if($_REQUEST['tipod']=='i')
			{
				if(trim($dat[0]) == trim($_REQUEST['radioD']))
				{
					$_REQUEST['codigoi'] = $dat[0];
					$_REQUEST['diagnosticoi'] = $dat[1];
					$i = $_REQUEST['vectorD'];
					SessionSetVar('codigoi',$_REQUEST['codigoi']);
					SessionSetVar('diagnosticoi',$_REQUEST['diagnosticoi']);
				}
			}
			elseif($_REQUEST['tipod']=='i1')
			{
				if(trim($dat[0]) == trim($_REQUEST['radioD']))
				{
					$_REQUEST['codigoi1'] = $dat[0];
					$_REQUEST['diagnosticoi1'] = $dat[1];
					$i = $_REQUEST['vectorD'];
					SessionSetVar('codigoi1',$_REQUEST['codigoi1']);
					SessionSetVar('diagnosticoi1',$_REQUEST['diagnosticoi1']);
				}
			}
			elseif($_REQUEST['tipod']=='i2')
			{
				if(trim($dat[0]) == trim($_REQUEST['radioD']))
				{
					$_REQUEST['codigoi2'] = $dat[0];
					$_REQUEST['diagnosticoi2'] = $dat[1];
					$i = $_REQUEST['vectorD'];
					SessionSetVar('codigoi2',$_REQUEST['codigoi2']);
					SessionSetVar('diagnosticoi2',$_REQUEST['diagnosticoi2']);
				}
			}
			elseif($_REQUEST['tipod']=='e')
			{
				if(trim($dat[0]) == trim($_REQUEST['radioD']))
				{
					$_REQUEST['codigoe'] = $dat[0];
					$_REQUEST['diagnosticoe'] = $dat[1];
					$i = $_REQUEST['vectorD'];
					SessionSetVar('codigoe',$_REQUEST['codigoe']);
					SessionSetVar('diagnosticoe',$_REQUEST['diagnosticoe']);
				}
			}
			elseif($_REQUEST['tipod']=='e1')
			{
				if(trim($dat[0]) == trim($_REQUEST['radioD']))
				{
					$_REQUEST['codigoe1'] = $dat[0];
					$_REQUEST['diagnosticoe1'] = $dat[1];
					$i = $_REQUEST['vectorD'];
					SessionSetVar('codigoe1',$_REQUEST['codigoe1']);
					SessionSetVar('diagnosticoe1',$_REQUEST['diagnosticoe1']);
				}
			}
			elseif($_REQUEST['tipod']=='e2')
			{
				if(trim($dat[0]) == trim($_REQUEST['radioD']))
				{
					$_REQUEST['codigoe2'] = $dat[0];
					$_REQUEST['diagnosticoe2'] = $dat[1];
					$i = $_REQUEST['vectorD'];
					SessionSetVar('codigoe2',$_REQUEST['codigoe2']);
					SessionSetVar('diagnosticoe2',$_REQUEST['diagnosticoe2']);
				}
			}
		
		}
		$this->AtencionMedica();
		return true;
	}
	
	
	function InsertarNuevaAtencionMedica()
	{
		//print_r($_REQUEST);
    if(empty($_REQUEST[codigoi]) OR empty($_REQUEST[minutero1]) OR empty($_REQUEST[horario1])
		OR empty($_REQUEST[codigoe]) OR empty($_REQUEST[minutero2]) OR empty($_REQUEST[horario2])
		OR empty($_REQUEST[tipodocumento]) OR empty($_REQUEST[documento])
		OR empty($_REQUEST[primerapellido])
		OR empty($_REQUEST[primernombre]) OR empty($_REQUEST[registro]))
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS<BR>Codigo/fecha/hora Ingreso Codigo/fecha/hora Egreso Tipo/numero del documento del profesional<BR>NOmbre/apellido del profesional";
			$this->AtencionMedica();
			return true;
		}
	
		if(!empty($_REQUEST[codigoi1]))
		{
			$diagnostico1_ingreso_id = "diagnostico1_ingreso_id ";
			$valuei1 = "'".$_REQUEST[codigoi1]."' ";
		}
		if(!empty($_REQUEST[codigoi2]))
		{
			$diagnostico2_ingreso_id = "diagnostico2_ingreso_id ";
			$valuei2 = "'".$_REQUEST[codigoi2]."' ";
		}
		if(!empty($_REQUEST[codigoe1]))
		{
			$diagnostico1_egreso_id = "diagnostico1_egreso_id ";
			$valuee1 = "'".$_REQUEST[codigoe1]."' ";
		}
		
		if(!empty($_REQUEST[codigoe2]))
		{
			$diagnostico2_egreso_id = "diagnostico2_egreso_id ";
			$valuee2 = "'".$_REQUEST[codigoe2]."' ";
			
		}

		if(!empty($_REQUEST[segundoapellido]))
		{
			$segundo_apellido = "segundo_apellido  ";
			$vsegundo_apellido = "'".$_REQUEST[segundoapellido]."' ";
		}
		
		if(!empty($_REQUEST[segundonombre]))
		{
			$segundo_nombre = "segundo_nombre ";
			$vsegundo_nombre = "'".$_REQUEST[segundonombre]."' ";
			
		}
		
		$fechai = $this->FormatoFecha2($_REQUEST[fechaingreso]);
		$fechae = $this->FormatoFecha2($_REQUEST[fechaegreso]);
		
    $ingreso = SessionGetVar("ingreso_soat");
    
		list($dbconn) = GetDBconn();
	  //$dbconn->debug = true;
		$sql="SELECT COUNT(*)
          FROM  soat_atencion_medica_furips
          WHERE evento = ".$_REQUEST[evento]."
          AND   ingreso = ".$ingreso." ";
		$resulta = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
			return false;
		}
		if($resulta->fields[0]>0)
		{
			if(!empty($diagnostico1_ingreso_id))
			{
				$seti1 = $diagnostico1_ingreso_id."= ".$valuei1." ,";
				
			}
			if(!empty($diagnostico2_ingreso_id))
			{
				$seti2 = $diagnostico2_ingreso_id."= ".$valuei2." ,";
				
			}
			if(!empty($diagnostico1_egreso_id))
			{
				$sete1 = $diagnostico1_egreso_id."= ".$valuee1." ,";
				
			}
			if(!empty($diagnostico2_egreso_id))
			{
				$sete2 = $diagnostico2_egreso_id."= ".$valuee2." ,";
				
			}
			if(!empty($segundo_apellido))
			{
				$setsegundo_apellido = $segundo_apellido."= ".$vsegundo_apellido." ,";
			}
			if(!empty($segundo_nombre))
			{
				$setsegundo_nombre = $segundo_nombre." = ".$vsegundo_nombre." ,";
			}
      
			$query = "UPDATE soat_atencion_medica_furips SET 
					fecha_ingreso = '".$fechai."',
					hora_ingreso = '".$_REQUEST[horario1].":".$_REQUEST[minutero1]."',
					fecha_egreso = '$fechae',
					hora_egreso = '".$_REQUEST[horario2].":".$_REQUEST[minutero2]."',
					diagnostico_principal_ingreso_id = '".$_REQUEST[codigoi]."', 
					$seti1
					$seti2
					$sete1
					$sete2
					diagnostico_principal_egreso_id = '".$_REQUEST[codigoe]."',
					tipo_id_tercero = '".$_REQUEST[tipodocumento]."',
					tercero_id = '".$_REQUEST[documento]."',
					primer_apellido = '".$_REQUEST[primerapellido]."',
					$setsegundo_apellido
					primer_nombre = '".$_REQUEST[primernombre]."',
					$setsegundo_nombre
					registro_medico = '".$_REQUEST[registro]."'
				
				WHERE evento = ".$_REQUEST[evento]." 
        AND   ingreso = ".$ingreso." ";
		}
		else
		{
			$query  = "INSERT INTO soat_atencion_medica_furips";
			$query .= "   (";
      $query .= "     evento, ";
			$query .= "   	fecha_ingreso,";
			$query .= "   	hora_ingreso,";
			$query .= "   	fecha_egreso,";
			$query .= "   	hora_egreso,";
			$query .= "   	diagnostico_principal_ingreso_id,";
        
      if($diagnostico1_ingreso_id) $query .= "   	".$diagnostico1_ingreso_id.", ";
      if($diagnostico2_ingreso_id) $query .= "   	".$diagnostico2_ingreso_id.",";
        
      $query .= "     diagnostico_principal_egreso_id ,";
      
      if($diagnostico1_egreso_id) $query .= "   	".$diagnostico1_egreso_id.",";
      if($diagnostico2_egreso_id) $query .= "   	".$diagnostico2_egreso_id.",";
      
      $query .= "   tipo_id_tercero , ";
      $query .= "   tercero_id , ";
      $query .= "   primer_apellido , ";
      
      if($segundo_apellido) $query .= "   	".$segundo_apellido.",";
      
      $query .= "   primer_nombre ,";
      
      if($segundo_nombre)  $query .= "   	".$segundo_nombre.",";
      
      $query .= "     registro_medico, ";
      $query .= "     ingreso ";
      $query .= "   ) ";
			$query .= "VALUES ";
			$query .= "   ( ";
			$query .= "      ".$_REQUEST[evento].",";
			$query .= "   	'".$fechai."',";
			$query .= "   	'".$_REQUEST[horario1].":".$_REQUEST[minutero1]."',";
			$query .= "   	'".$fechae."',";
			$query .= "   	'".$_REQUEST[horario2].":".$_REQUEST[minutero2]."',";
			$query .= "   	'".$_REQUEST[codigoi]."',";
      
			if($valuei1) $query .= "   	".$valuei1.",";
			if($valuei2) $query .= "   	".$valuei2.",";
        
			$query .= "	  '".$_REQUEST[codigoe]."',";
			
      if($valuee1) $query .= "   	".$valuee1.",";
			if($valuee2) $query .= "   	".$valuee2.",";
			
      $query .= "   	'".$_REQUEST[tipodocumento]."', ";
			$query .= "   	'".$_REQUEST[documento]."', ";
			$query .= "   	'".$_REQUEST[primerapellido]."', ";
			
      if($vsegundo_apellido) $query .= "   	".$vsegundo_apellido.",";
			
      $query .= "   	'".$_REQUEST[primernombre]."',";
			
      if($vsegundo_nombre) $query .= "   	".$vsegundo_nombre.",";
      
			$query .= "   	'".$_REQUEST[registro]."', ";
			$query .= "   	 ".$ingreso." ";
			$query .= "   ); ";
		}
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
			return false;
		}
		$this->frmError["MensajeError"]="DATOS GUARDADOS";
		$this->AtencionMedica();
		return true;
	}
	
	
	function GetTipoTerceros()
	{
		$query = "SELECT tipo_id_tercero,descripcion
			FROM tipo_id_terceros
			ORDER BY descripcion;";
		list($dbconn) = GetDBconn();
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}
//---------------------------------------------------------
		/**
		* Cambia el formato de la fecha de YYYY-mm-dd hh:mm:ss a dd/mm/YYYY
		* @access private
		* @return string
		* @param date fecha
		* @var 	  cad	Cadena con el nuevo formato de la fecha
		*/
		function FormatoFecha($f)
		{	
			$fecha = explode(' ',$f);
			
			if($f)
			{
				$fech = strtok ($fecha[0],"-");
				for($i=0;$i<3;$i++)
				{
					$date[$i]=$fech;
					$fech = strtok ("-");
				}
				$cad = $date[2]."/".$date[1]."/".$date[0];
				return $cad;
			}
		}
		/**
		* Cambia el formato de la fecha dd/mm/YYYY a YYYY-mm-dd
		* @access private
		* @return string
		* @param date fecha
		* @var 	  cad	Cadena con el nuevo formato de la fecha
		*/
		function FormatoFecha2($f)
		{	
			$fecha = explode(' ',$f);
			
			if($f)
			{
				$fech = strtok ($fecha[0],"/");
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
    * Funcion donde se genera la consulta de los datos del soat. 
    *
    * @param integer $evento numero del evento de un paciente.
    * @param integer $ingreso numero del ingreso.
    *
    * @return array $var retorna los datos de la consulta.
    **/
    function BuscarDatosSoat($evento,$ingreso)
    {
      list($dbconn) = GetDBconn();
      //$dbconn->debug = true;
      $sql   = "SELECT A.diagnostico_principal_ingreso_id, ";    
      $sql  .= "       B.diagnostico_nombre AS principal_ingreso, ";
      $sql  .= "       A.diagnostico1_ingreso_id, ";
      $sql  .= "       B1.diagnostico_nombre AS diagnostico1_ingreso, ";
      $sql  .= "       A.diagnostico2_ingreso_id, "; 
      $sql  .= "       B2.diagnostico_nombre AS diagnostico2_ingreso, ";
      $sql  .= "       A.diagnostico_principal_egreso_id, "; 
      $sql  .= "       D.diagnostico_nombre AS principal_egreso, ";
      $sql  .= "       A.diagnostico1_egreso_id, ";
      $sql  .= "       D1.diagnostico_nombre AS diagnostico1_egreso, ";
      $sql  .= "       A.diagnostico2_egreso_id , "; 
      $sql  .= "       D2.diagnostico_nombre AS diagnostico2_egreso, ";
      $sql  .= "       TO_CHAR(A.fecha_ingreso,'DD/MM/YYYY') AS fecha_ingreso, ";
      $sql  .= "       TO_CHAR(A.fecha_egreso,'DD/MM/YYYY') AS fecha_egreso, ";
      $sql  .= "       A.tipo_id_tercero, ";
      $sql  .= "       A.tercero_id, ";
      $sql  .= "       A.primer_apellido, ";
      $sql  .= "       A.segundo_apellido, ";
      $sql  .= "       A.primer_nombre, ";
      $sql  .= "       A.segundo_nombre, ";
      $sql  .= "       A.registro_medico ";
      $sql  .= "FROM   soat_atencion_medica_furips AS A "; 
      $sql  .= "       left join diagnosticos AS B1 ";
      $sql  .= "       on(A.diagnostico1_ingreso_id = B1.diagnostico_id ) ";
      $sql  .= "       left join diagnosticos AS  B2 ";
      $sql  .= "       on(A.diagnostico2_ingreso_id = B2.diagnostico_id ) ";
      $sql  .= "       left join diagnosticos AS D1 ";
      $sql  .= "       on(A.diagnostico1_egreso_id = D1.diagnostico_id ) ";
      $sql  .= "       left join diagnosticos AS D2 ";
      $sql  .= "       on(A.diagnostico2_egreso_id = D2.diagnostico_id )";
      $sql  .= "       , diagnosticos AS B,diagnosticos AS D ";       
      $sql  .= "WHERE A.diagnostico_principal_ingreso_id = B.diagnostico_id ";
      $sql  .= "AND   A.diagnostico_principal_egreso_id = D.diagnostico_id ";
      $sql  .= "AND   A.evento = ".$evento." ";
      $sql  .= "AND   A.ingreso = ".$ingreso." ";
      
      $resulta = $dbconn->Execute($sql);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      while(!$resulta->EOF)
      {
        $var=$resulta->GetRowAssoc($ToUpper = false);
        $resulta->MoveNext();
      }
      return $var;
    }
    function BuscarDigitoVerificacion($tercero,$tipo_tercero)
    {
      list($dbconn) = GetDBconn();
      //$dbconn->debug = true;
      $sql   = "SELECT  digito_verificacion ";
      $sql   .= " FROM    terceros_soat ";
      $sql   .= " WHERE   tipo_id_tercero = '".$tipo_tercero."' ";
      $sql   .= " AND     tercero_id = '".$tercero."' ";
    
      $resulta = $dbconn->Execute($sql);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      while(!$resulta->EOF)
      {
        $var=$resulta->GetRowAssoc($ToUpper = false);
        $resulta->MoveNext();
      }
      return $var;
    }
    
    
    
    
    
    
    
  }
?>